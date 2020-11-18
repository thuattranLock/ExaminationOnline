$(document).ready(function () {
   var dataTable = $("#question_data_table").DataTable({
      "processing" :true,
      "serverSide" :true,
      "order" :[],
      "ajax" :{
         url: url + "/questions/viewQuestion",
         method:"POST",
         data:{code:code}
      },
      "columnDefs":[
         { "name": "question_title",   	"targets": [0] },
			{ "name": "answer_option",  	"targets": [1] },
         { "orderable":false, "targets" :[2] }
      ],
	});
	
	function sweetalert(icon, message)
	{
		const Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 3000,
			timerProgressBar: true,
			didOpen: (toast) => {
			  toast.addEventListener('mouseenter', Swal.stopTimer)
			  toast.addEventListener('mouseleave', Swal.resumeTimer)
			}
		 })
		
		Toast.fire({
			icon: icon,
			title: message
		})
	}

   $('#question_form').on('submit', function(event){
		event.preventDefault();

		$('#question_title').attr('required', 'required');
		$('#option_title_1').attr('required', 'required');
		$('#option_title_2').attr('required', 'required');
		$('#option_title_3').attr('required', 'required');
		$('#option_title_4').attr('required', 'required');
		$('#answer_option').attr('required', 'required');

		Swal.fire({
			title: 'Are you sure?',	
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, update it!'
		}).then((result) => {
			if (result.isConfirmed) {
				if($('#question_form').valid()){
					$.ajax({
						url: url + "/questions/edit",
						method:"POST",
						data:$(this).serialize(),
						dataType:"json",
						success:function(data)
						{
							if(data.success)
							{
								sweetalert('success', data.success)
								resetForm();
								dataTable.ajax.reload();
								$('#questionModal').modal('hide');
							}
						}
					});
				}
			}
		})
		
	});

   function resetForm()
   {
      $('#question_button_action').val('Edit');
		$('#question_form')[0].reset();
   }

   $(document).on('click', '.edit', function(){
		question_id = $(this).attr('id');
		resetForm();
		$.ajax({
			url: url + "/questions/ajaxGetDetail",
			method:"POST",
			data:{question_id:question_id},
			dataType:"json",
			success:function(data)
			{
				$('#question_title').val(data.question_title);
				$('#option_title_1').val(data.option_title_1);
				$('#option_title_2').val(data.option_title_2);
				$('#option_title_3').val(data.option_title_3);
				$('#option_title_4').val(data.option_title_4);
				$('#answer_option').val(data.answer_option);
				$('#question_id').val(question_id);
				$('#question_modal_title').text('Edit Question Details');
				$('#questionModal').modal({
					show: true,
					backdrop: false
				});
			}
		})
	});

	$(document).on('click', '.delete', function () {
		let question_id = $(this).attr('id');
		Swal.fire({
			title: 'Are you sure?',	
			text: "You won't be able to revert this!",
			icon: 'error',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					type: "POST",
					url: url + "/questions/ajaxDelete",
					data: {question_id: question_id},
					dataType: "json",
					success: function (data) {
						if (data.success) {
							sweetalert('success', data.success);
							dataTable.ajax.reload();
							$('#formModal').modal('hide');
						}
					}
				});
			}
		})
	});
});   
