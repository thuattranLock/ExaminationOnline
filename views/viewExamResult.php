<?php include APPROOT . '/views/includes/head.php';  ?>

<body data-spy="scroll" data-target="#myScrollspy" data-offset="20">

	<?php include APPROOT . '/views/includes/navbar.php' ?>

	<div class="container-fluid">
		<div class="row content">
			<div class="col-md-3 mb-3 user-overview">

				<?php include APPROOT . '/views/includes/userOverview.php' ?>

			</div>
			<div class="col-md-9 box-right mb-5">

				<div class="box-content mt-2">
					<h1>Online Exam Result</h1>
					<hr>
					<div class="card">
						<div class="card-header">
							<div class="row">
								<div class="col-md-8"></div>
								<div class="col-md-4" align="right">
									<a href="<?php echo URLROOT ?>/exams/pdfResult/<?= $code ?>" class="btn btn-danger btn-sm" target="_blank">PDF</a>
								</div>
							</div>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-bordered table-hover">
									<tr>
										<th>Question</th>
										<th>Option 1</th>
										<th>Option 2</th>
										<th>Option 3</th>
										<th>Option 4</th>
										<th>Your Answer</th>
										<th>Answer</th>
										<th>Result</th>
										<th>Marks</th>
									</tr>
									<?php 
									foreach($data['result_user_answer'] as $row){
										$user_answer = '';
										$orignal_answer = '';
										$question_result = '';
										if($row['marks'] == '0'){
											$question_result = '<h4 class="badge badge-dark">Not Attend</h4>';
										}
										if($row['marks'] > '0'){
											$question_result = '<h4 class="badge badge-success">Right</h4>';
										}
										if($row['marks'] < '0'){
											$question_result = '<h4 class="badge badge-danger">Wrong</h4>';
										}

										echo '
										<tr>
											<td>'.$row['question_title'].'</td>
										';
										foreach($data['options'] as $sub_row){
											echo '<td>'.$sub_row["option_title"].'</td>';
											if($sub_row["option_number"] == $row['user_answer_option']){
												$user_answer = $sub_row['option_title'];
											}
											if($sub_row['option_number'] == $row['answer_option']){
												$orignal_answer = $sub_row['option_title'];
											}
										}

										echo '
											<td>'.$user_answer.'</td>
											<td>'.$orignal_answer.'</td>
											<td>'.$question_result.'</td>
											<td>'.$row["marks"].'</td>
										</tr>
										';
									}	
									?>

									<?php foreach($data['marks_result'] as $row){ ?>
										<tr>
											<td colspan="8" align="right">Total Marks</td>
											<td align="right"><?php echo $row["total_mark"]; ?></td>
										</tr>
									<?php } ?>

								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php include APPROOT . '/views/includes/footer.php'; ?>

	</div>

</body>

<?php include APPROOT . '/views/includes/script.php'; ?>

</html>