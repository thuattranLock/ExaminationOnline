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
                  <div class="col-6">
                     <button align="left" class="btn btn-dark btn-sm" onclick="window.history.back()">Back</button>
                  </div>
                  <div class="col-6">
                     <a href="<?php echo URLROOT ?>/exams/pdfListUserExamResult/<?= $code; ?>" class="btn btn-danger btn-sm float-right" target="_blank">PDF</a>
                  </div>
               </div>
               <br>
               <div class="table-responsive">
                  <table id="result_table" class="table table-bordered table-striped table-hover">
                     <thead>
                        <tr>
                           <th>Image</th>
                           <th>User Name</th>
                           <th>Attendance Status</th>
                           <th>Marks</th>
                        </tr>
                     </thead>
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
   <script>
      var url = "<?php echo URLROOT ?>";
      var code = "<?= $code ?>";
   </script>
   <script>
      $(document).ready(function () {
         $('#result_table').DataTable({
            "processing" : true,
            "serverSide" : true,
            "order" : [],
            "ajax" : {
               url: url + "/exams/ajaxListUserExamResult",
               method:"POST",
               data: {code:code},
            },
            "columnDefs":[
               { "orderable": false, 				"targets": [0] },
               { "name": "list_user_answer_table.user_name",  			"targets": [1] },
               { "name": "user_exam_enroll_table.attendance_status", "targets": [2] },
               { "name": "list_user_answer_table.total_mark", 		   "targets": [3] },
            ],
         });
      });
   </script>
</body>

</html>