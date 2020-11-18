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
                     <h1 class="m-0 text-dark">Question</h1>
                  </div><!-- /.col -->
                  <div class="col-sm-6">
                     <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= URLROOT ?>/admins/index">Home</a></li>
                        <li class="breadcrumb-item active">Question</li>
                     </ol>
                  </div><!-- /.col -->
               </div><!-- /.row -->
            </div><!-- /.container-fluid -->
         </div>
         <!-- /.content-header -->

         <!-- Main content -->
         <section class="content">
            <div class="container-fluid">
               <div class="table-responsive">
                  <table id="question_data_table" class="table table-bordered table-striped table-hover">
                     <thead>
                        <tr>
                           <th>Question Title</th>
                           <th>Right Option</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                  </table>
               </div>
            </div><!-- /.container-fluid -->
         </section>
         <!-- /.content -->
      </div>
      <!-- End add Modal -->

      <!-- Question Modal -->
      <div class="modal" id="questionModal">
         <div class="modal-dialog modal-lg">
            <form method="post" id="question_form">
               <div class="modal-content">
                  <!-- Modal Header -->
                  <div class="modal-header">
                     <h4 class="modal-title" id="question_modal_title"></h4>
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>

                  <!-- Modal body -->
                  <div class="modal-body">
                     <div class="form-group">
                        <div class="row">
                           <label class="col-md-4 text-right">Question Title <span class="text-danger">*</span></label>
                           <div class="col-md-8">
                              <input type="text" name="question_title" id="question_title" autocomplete="off" class="form-control" />
                           </div>
                        </div>
                     </div>
                     <div class="form-group">
                        <div class="row">
                           <label class="col-md-4 text-right">Option 1 <span class="text-danger">*</span></label>
                           <div class="col-md-8">
                              <input type="text" name="option_title_1" id="option_title_1" autocomplete="off" class="form-control" />
                           </div>
                        </div>
                     </div>
                     <div class="form-group">
                        <div class="row">
                           <label class="col-md-4 text-right">Option 2 <span class="text-danger">*</span></label>
                           <div class="col-md-8">
                              <input type="text" name="option_title_2" id="option_title_2" autocomplete="off" class="form-control" />
                           </div>
                        </div>
                     </div>
                     <div class="form-group">
                        <div class="row">
                           <label class="col-md-4 text-right">Option 3 <span class="text-danger">*</span></label>
                           <div class="col-md-8">
                              <input type="text" name="option_title_3" id="option_title_3" autocomplete="off" class="form-control" />
                           </div>
                        </div>
                     </div>
                     <div class="form-group">
                        <div class="row">
                           <label class="col-md-4 text-right">Option 4 <span class="text-danger">*</span></label>
                           <div class="col-md-8">
                              <input type="text" name="option_title_4" id="option_title_4" autocomplete="off" class="form-control" />
                           </div>
                        </div>
                     </div>
                     <div class="form-group">
                        <div class="row">
                           <label class="col-md-4 text-right">Answer <span class="text-danger">*</span></label>
                           <div class="col-md-8">
                              <select name="answer_option" id="answer_option" class="form-control">
                                 <option value="">Select</option>
                                 <option value="1">1 Option</option>
                                 <option value="2">2 Option</option>
                                 <option value="3">3 Option</option>
                                 <option value="4">4 Option</option>
                              </select>
                           </div>
                        </div>
                     </div>
                  </div>

                  <!-- Modal footer -->
                  <div class="modal-footer">
                     <input type="hidden" name="question_id" id="question_id" />
	        		      <input type="hidden" name="online_exam_id" id="hidden_online_exam_id" />
                     <input type="submit" name="question_button_action" id="question_button_action" class="btn btn-success btn-sm" value="Add" />
                     <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
      <!-- End Question Modal -->

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
      var code = "<?php echo $code ?>";
   </script>
   <script src="<?php echo URLROOT ?>/public/js/question.js"></script>
</body>

</html>