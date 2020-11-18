<?php include APPROOT . '/views/admins/includes/head.php';  ?>

<body class="hold-transition sidebar-mini layout-fixed">
   <div class="wrapper">

      <!-- Navbar -->
      <nav class="main-header navbar navbar-expand navbar-white navbar-light">
         <?php include APPROOT . '/views/admins/includes/navbar.php'; ?>
      </nav>
      <!-- /.navbar -->

      <!-- Main Sidebar Container -->
      <aside class="main-sidebar sidebar-dark-primary elevation-4">
         <?php include APPROOT . '/views/admins/includes/sidebar.php'; ?>
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
         <!-- Content Header (Page header) -->
         <div class="content-header">
            <div class="container-fluid">
               <div class="row mb-2">
                  <div class="col-sm-6">
                     <h1 class="m-0 text-dark">Exam Result</h1>
                  </div><!-- /.col -->
                  <div class="col-sm-6">
                     <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= URLROOT ?>/admins/index">Home</a></li>
                        <li class="breadcrumb-item active">Exam Result</li>
                     </ol>
                  </div><!-- /.col -->
               </div><!-- /.row -->
            </div><!-- /.container-fluid -->
         </div>
         <!-- /.content-header -->

         <!-- Main content -->
         <section class="content">
            <div class="container-fluid">
               <div class="row">
                  <ul>
                     <li>
                        <h5>Name: <span class="text-dark"><?= $user['user_name'] ?? null ?></span></h5>
                     </li>
                     <li>
                        <h5>Email Address: <span class="text-dark"><?= $user['user_email_address'] ?? null ?></span></h5>
                     </li>
                  </ul>
               </div>
               <br>
               <div class="table-responsive">
                  <table id="result_table" class="table table-bordered table-striped table-hover">
                     <thead>
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
                     </thead>
                     <tbody>
                        <?php
                        foreach ($data['result_user_answer'] as $row) {
                           $user_answer = '';
                           $orignal_answer = '';
                           $question_result = '';
                           if ($row['marks'] == '0') {
                              $question_result = '<h4 class="badge badge-dark">Not Attend</h4>';
                           }
                           if ($row['marks'] > '0') {
                              $question_result = '<h4 class="badge badge-success">Right</h4>';
                           }
                           if ($row['marks'] < '0') {
                              $question_result = '<h4 class="badge badge-danger">Wrong</h4>';
                           }

                           echo '
										<tr>
											<td>' . $row['question_title'] . '</td>
										';
                           foreach ($data['options'] as $sub_row) {
                              echo '<td>' . $sub_row["option_title"] . '</td>';
                              if ($sub_row["option_number"] == $row['user_answer_option']) {
                                 $user_answer = $sub_row['option_title'];
                              }
                              if ($sub_row['option_number'] == $row['answer_option']) {
                                 $orignal_answer = $sub_row['option_title'];
                              }
                           }

                           echo '
											<td>' . $user_answer . '</td>
											<td>' . $orignal_answer . '</td>
											<td>' . $question_result . '</td>
											<td>' . $row["marks"] . '</td>
										</tr>
										';
                        }
                        ?>

                        <?php foreach ($data['marks_result'] as $row) { ?>
                           <tr>
                              <td colspan="8" align="right">Total Marks</td>
                              <td align="right"><?php echo $row["total_mark"]; ?></td>
                           </tr>
                        <?php } ?>
                     </tbody>
                  </table>
               </div>
            </div><!-- /.container-fluid -->
         </section>
         <!-- /.content -->
      </div>

      <!-- /.content-wrapper -->
      <footer class="main-footer">
         <?php include APPROOT . '/views/admins/includes/footer.php';  ?>
      </footer>

      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark">
         <!-- Control sidebar content goes here -->
      </aside>
      <!-- /.control-sidebar -->
   </div>
   <!-- ./wrapper -->
   <?php include APPROOT . '/views/admins/includes/script.php';  ?>
</body>

</html>