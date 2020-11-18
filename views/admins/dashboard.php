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
         <!-- Content Header -->
         <div class="content-header">
            <div class="container-fluid">
               <div class="row mb-2">
                  <div class="col-sm-6">
                     <h1 class="m-0 text-dark">Dashboard</h1>
                  </div><!-- /.col -->
                  <div class="col-sm-6">
                     <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= URLROOT ?>/admins/index">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard </li>
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
                  <div class="col-lg-4 col-6">
                     <!-- small box -->
                     <div class="small-box bg-info">
                        <div class="inner">
                           <h3><?= $data['total_exam'] ?></h3>

                           <p>Total exam</p>
                        </div>
                        <a href="<?= URLROOT ?>/exams/list" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                     </div>
                  </div>
                  <!-- ./col -->
                  <div class="col-lg-4 col-6">
                     <!-- small box -->
                     <div class="small-box bg-success">
                        <div class="inner">
                           <h3><?= $data['total_user'] ?></h3>

                           <p>User register</p>
                        </div>
                        <a href="<?= URLROOT ?>/admins/userRegisterData" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                     </div>
                  </div>
                  <!-- ./col -->
                  <div class="col-lg-4 col-12">
                     <!-- small box -->
                     <div class="small-box bg-warning">
                        <div class="inner">
                           <h3><?= $data['total_question'] ?></h3>

                           <p>Total question</p>
                        </div>
                     </div>
                  </div>
                  <!-- ./col -->
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
   <!-- <script src="<?php echo URLROOT ?>/public/js/exam.js"></script> -->
</body>

</html>