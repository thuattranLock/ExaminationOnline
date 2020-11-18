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
                     <h1 class="m-0 text-dark">Exam</h1>
                  </div><!-- /.col -->
                  <div class="col-sm-6">
                     <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= URLROOT ?>/admins/index">Home</a></li>
                        <li class="breadcrumb-item active">Exam</li>
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
                     <button type="button" id="add_button" class="btn btn-info"><i class="fa fa-plus-circle"></i> Add</button>
                  </div>
               </div>
               <br>
               <div class="table-responsive">
                  <table id="exam_data_table" class="table table-bordered table-striped table-hover">
                     <thead>
                        <tr>
                           <th>Exam Title</th>
                           <th>Date & Time</th>
                           <th>Duration</th>
                           <th>Total Question</th>
                           <th>Right Answer Mark</th>
                           <th>Wrong Answer Mark</th>
                           <th>Status</th>
                           <th>Enroll</th>
                           <th>Question</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                  </table>
               </div>
            </div><!-- /.container-fluid -->
         </section>
         <!-- /.content -->

         <!-- Confirm modal -->
         <div class="modal" id="modal-confirm" role="dialog">
            <div class="modal-dialog" role="document">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title">Are you sure?</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <div class="modal-body">
                     <p>Update exam details</p>
                  </div>
                  <div class="modal-footer">
                     <button type="button" id="btn-confirm" class="btn btn-primary">Save changes</button>
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
               </div>
            </div>
         </div>
         <!-- End confirm modal -->

         <!--Add Modal -->
         <div class="modal" id="formModal">
            <div class="modal-dialog modal-lg">
               <form method="post" id="exam_form">
                  <div class="modal-content">
                     <!-- Modal Header -->
                     <div class="modal-header">
                        <h4 class="modal-title" id="modal_title"></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                     </div>

                     <!-- Modal body -->
                     <div class="modal-body">
                        <div class="form-group">
                           <div class="row">
                              <label class="col-md-4 text-right">Exam Title <span class="text-danger">*</span></label>
                              <div class="col-md-8">
                                 <input type="text" name="online_exam_title" id="online_exam_title" class="form-control" />
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <div class="row">
                              <label class="col-md-4 text-right">Exam Date & Time <span class="text-danger">*</span></label>
                              <div class="col-md-8">
                                 <input type="text" name="online_exam_datetime" id="online_exam_datetime" class="form-control" readonly />
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <div class="row">
                              <label class="col-md-4 text-right">Exam Duration <span class="text-danger">*</span></label>
                              <div class="col-md-8">
                                 <select name="online_exam_duration" id="online_exam_duration" class="form-control">
                                    <option value="">Select</option>
                                    <option value="5">5 Minute</option>
                                    <option value="30">30 Minute</option>
                                    <option value="60">1 Hour</option>
                                    <option value="120">2 Hour</option>
                                    <option value="180">3 Hour</option>
                                 </select>
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <div class="row">
                              <label class="col-md-4 text-right">Total Question <span class="text-danger">*</span></label>
                              <div class="col-md-8">
                                 <select name="total_question" id="total_question" class="form-control">
                                    <option value="">Select</option>
                                    <option value="5">5 Question</option>
                                    <option value="10">10 Question</option>
                                    <option value="25">25 Question</option>
                                    <option value="50">50 Question</option>
                                    <option value="100">100 Question</option>
                                    <option value="200">200 Question</option>
                                    <option value="300">300 Question</option>
                                 </select>
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <div class="row">
                              <label class="col-md-4 text-right">Marks for Right Answer <span class="text-danger">*</span></label>
                              <div class="col-md-8">
                                 <select name="marks_per_right_answer" id="marks_per_right_answer" class="form-control">
                                    <option value="">Select</option>
                                    <option value="1">+1 Mark</option>
                                    <option value="2">+2 Mark</option>
                                    <option value="3">+3 Mark</option>
                                    <option value="4">+4 Mark</option>
                                    <option value="5">+5 Mark</option>
                                 </select>
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <div class="row">
                              <label class="col-md-4 text-right">Marks for Wrong Answer <span class="text-danger">*</span></label>
                              <div class="col-md-8">
                                 <select name="marks_per_wrong_answer" id="marks_per_wrong_answer" class="form-control">
                                    <option value="">Select</option>
                                    <option value="1">-1 Mark</option>
                                    <option value="1.25">-1.25 Mark</option>
                                    <option value="1.50">-1.50 Mark</option>
                                    <option value="2">-2 Mark</option>
                                 </select>
                              </div>
                           </div>
                        </div>
                     </div>

                     <!-- Modal footer -->
                     <div class="modal-footer">
                        <input type="hidden" name="online_exam_id" id="online_exam_id" value="0"/>
                        <input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="Add" />
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
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
      var url = '<?php echo URLROOT ?>';
   </script>
   <script src="<?php echo URLROOT ?>/public/js/exam.js"></script>

</body>

</html>