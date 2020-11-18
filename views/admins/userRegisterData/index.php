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
                     <h1 class="m-0 text-dark">Users List</h1>
                  </div><!-- /.col -->
                  <div class="col-sm-6">
                     <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= URLROOT ?>/admins/index">Home</a></li>
                        <li class="breadcrumb-item active">Users List</li>
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
                  <div class="col-12">
                     <div id="message_operation"></div>
                  </div>
                  <div class="col-12">
                     <!-- <button type="button" id="add_button" class="btn btn-info"><i class="fa fa-plus-circle"></i> Add</button> -->
                  </div>
               </div>
               <br>
               <div class="table-responsive">
                  <table id="user_data_table" class="table table-bordered table-striped table-hover">
                     <thead>
                        <tr>
                           <th>Image</th>
                           <th>User Name</th>
                           <th>Email Address</th>
                           <th>Gender</th>
                           <th>Mobile No.</th>
                           <th>Email Verified</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                  </table>
               </div>
            </div><!-- /.container-fluid -->
         </section>
         <!-- /.content -->

         <div class="modal" id="detailModal">
            <div class="modal-dialog">
               <div class="modal-content">

                  <!-- Modal Header -->
                  <div class="modal-header">
                     <h4 class="modal-title">User Details</h4>
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>

                  <!-- Modal body -->
                  <div class="modal-body" id="user_details">

                  </div>

                  <!-- Modal footer -->
                  <div class="modal-footer">
                     <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- End add Modal -->

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
   </script>

   <script src="<?php echo URLROOT ?>/public/js/userRegisterData.js"></script>
</body>

</html>