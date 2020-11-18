$(document).ready(function () {

   var dataTable = $('#exam_data_table').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url: url + "/exams/ajaxFetchData",
			method:"POST",
		},
		"columnDefs":[
			{ "name": "online_exam_title",   	"targets": [0] },
			{ "name": "online_exam_datetime",  	"targets": [1] },
			{ "name": "online_exam_duration", 	"targets": [2] },
			{ "name": "total_question",  			"targets": [3] },
			{ "name": "marks_per_wrong_answer", "targets": [5] },
			{ "name": "marks_per_right_answer", "targets": [4] },
			{ "name": "online_exam_status",    	"targets": [6] },
			{ "orderable": false,					"targets": [7] },
			{ "orderable": false, 					"targets": [8] },
		],
	});
	
	setInterval(function(){
		$.ajax({
			type: "POST",
			url: url + "/exams/ajaxExamStatusCreated",
			success: function (response) {
				dataTable.ajax.reload();
			}
		});
	},30000) 


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

	function reset_form()
	{
		$("#modal_title").text("Add exam details.");
		$('#button_action').val('Add');
		$("#online_exam_id").val(0);
		$('#exam_form')[0].reset();
	}

	$("#add_button").click(function () 
	{ 
		reset_form();
		$('#formModal').modal({
			show: true,
			backdrop: false
		});
		$('#message_operation').html('');
	});

	var date = new Date();

	date.setDate(date.getDate());

	$('#online_exam_datetime').datetimepicker({
		startDate :date,
		format: 'yyyy-mm-dd hh:ii',
		autoclose:true
	});

	$("#exam_form").on('submit', function (e) 
	{ 
		e.preventDefault();

		$('#online_exam_title').attr('required', 'required');
		$('#online_exam_datetime').attr('required', 'required');
		$('#online_exam_duration').attr('required', 'required');
		$('#total_question').attr('required', 'required');
		$('#marks_per_right_answer').attr('required', 'required');
		$('#marks_per_wrong_answer').attr('required', 'required');
		if($("#exam_form").valid()){
			if($("#online_exam_id").val() == 0 ){
				$.ajax({
					type: "POST",
					url: url + "/exams/addExam",
					data: $(this).serialize(),
					dataType: "json",
					success: function (data) {
						if(data.success){
							sweetalert('success', data.success);
							reset_form();
							dataTable.ajax.reload();
							$('#formModal').modal('hide');
						}
					}
				});
			}else{
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
						let exam_id = $("#online_exam_id").val();
						$.ajax({
							type: "POST",
							url: url + "/exams/ajaxUpdate/" + exam_id,
							data: $(this).serialize(),
							dataType: "json",
							success: function (data) {
								if (data.success) {
									sweetalert('success','Updated exam successfully');
									dataTable.ajax.reload();
									$('#formModal').modal('hide');
								}
							}
						});
					}
				})
			}
		}
	});

	var exam_id = '';

	$(document).on('click', '.edit', function()
	{
		exam_id = $(this).attr('id');
		reset_form();

		$.ajax({
			url:url + "/exams/ajaxEdit/" + exam_id,
			method:"POST",
			dataType:"json",
			success:function(data)
			{
				$('#online_exam_title').val(data.online_exam_title);

				$('#online_exam_datetime').val(data.online_exam_datetime);

				$('#online_exam_duration').val(data.online_exam_duration);

				$('#total_question').val(data.total_question);

				$('#marks_per_right_answer').val(data.marks_per_right_answer);

				$('#marks_per_wrong_answer').val(data.marks_per_wrong_answer);

				$('#online_exam_id').val(exam_id);

				$('#modal_title').text('Edit Exam Details');

				$('#button_action').val('Edit');

				$('#formModal').modal({
					show: true,
					backdrop: false
				});
			}
		})
	});	

	$(document).on('click', '.delete', function () {
		let exam_id = $(this).attr('id');
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
					url: url + "/exams/ajaxDelete/" + exam_id,
					data: $(this).serialize(),
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

	function reset_question_form()
	{
		$('#question_modal_title').text('Add Question');
		$('#question_form')[0].reset();
	}

	$(document).on('click', '.add_question', function(){
		reset_question_form();
		$('#questionModal').modal({
			show: true,
			backdrop: false
		});
		$('#message_operation').html('');
		let exam_id = $(this).attr('id');
		$('#hidden_online_exam_id').val(exam_id);
	});

	$('#question_form').on('submit', function(event){
		event.preventDefault();

		$('#question_title').attr('required', 'required');
		$('#option_title_1').attr('required', 'required');
		$('#option_title_2').attr('required', 'required');
		$('#option_title_3').attr('required', 'required');
		$('#option_title_4').attr('required', 'required');
		$('#answer_option').attr('required', 'required');

		if($('#question_form').valid())
		{
			$.ajax({
				url:url + "/questions/ajaxAdd",
				method:"POST",
				data:$(this).serialize(),
				dataType:"json",
				success:function(data)
				{
					if(data.success)
					{
						sweetalert('success', data.success);
						reset_question_form();
						dataTable.ajax.reload();
						$('#questionModal').modal('hide');
					}
				}
			});																																																						
		}
	});
});
