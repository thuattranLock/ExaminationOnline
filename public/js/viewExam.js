$(document).ready(() => {
	load_question();
	question_navigation();
	
	function load_question(question_id = ''){
		$.ajax({
			url: url + "/users/ajaxLoadQuestion",
			method:"POST",
			data:{exam_id:exam_id, question_id:question_id,},
			success:function(data)
			{
				$('#single_question_area').html(data);
			}
		})
	}

	$(document).on('click', '.next', function(){
		var question_id = $(this).attr('id');
		load_question(question_id);
	});

	$(document).on('click', '.previous', function(){
		var question_id = $(this).attr('id');
		load_question(question_id);
	});
	
	function question_navigation()
	{
		$.ajax({
			url: url + "/users/ajaxQuestionNavigation",
			method:"POST",
			data:{exam_id:exam_id},
			success:function(data){
				$('#question_navigation_area').html(data);
			}
		})
	}

	$(document).on('click', '.question_navigation', function(){
		var question_id = $(this).data('question_id');
		load_question(question_id);
	});

	$("#exam_timer").TimeCircles({ 
		time:{
			Days:{
				show: false
			},
			Hours:{
				show: false
			}
		}
	});

	function submit_exam()
	{
		$.ajax({
			url: url + "/users/ajaxSubmitExam",
			method:"POST",
			dataType: "json",
			data:{exam_id:exam_id},
			success:function(data){
				if(data.success){
					alert(data.message)
				}
			}
		})
	}

	setInterval(function(){
		var remaining_second = $("#exam_timer").TimeCircles().getTime();

		if(remaining_second < 1)
		{
			alert('Exam time over');
			submit_exam();
			location.reload();

		}
	}, 1000);

	$(document).on('click', '.answer_option', function(){
		var question_id = $(this).data('question_id');

		var answer_option = $(this).data('id');

		$.ajax({
			url: url + "/users/ajaxAswer",
			method:"POST",
			data:{question_id:question_id, answer_option:answer_option, exam_id:exam_id},
			success:function(data){
				console.log(data);
			}
		})
	});
});
