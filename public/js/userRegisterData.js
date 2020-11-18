$(document).ready(function () {

   var datatable = $('#user_data_table').DataTable({
      "processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url: url + "/admins/ajaxFetchUser",
			method:"POST",
		},
		"columnDefs":[
			{ "orderable": false, 				"targets": [0] },
			{ "name": "user_name",  			"targets": [1] },
			{ "name": "user_email_address", 	"targets": [2] },
			{ "name": "user_gender",  			"targets": [3] },
			{ "name": "user_mobile_no", 		"targets": [4] },
			{ "name": "user_email_verified", "targets": [5] },
			{ "orderable": false, 				"targets": [6] },
		],
	});
	
	
	$(document).on('click', '.details',function(){
		var user_id = $(this).attr('id');
		$.ajax({
			url: url + "/admins/ajaxUserDetail",
			method:"POST",
			data:{user_id:user_id},
			success:function(data){
				$('#user_details').html(data);
				$('#detailModal').modal('show');
			}
	    });
	})
});
