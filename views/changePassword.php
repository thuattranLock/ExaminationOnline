<?php include APPROOT . '/views/includes/head.php';  ?>

<body data-spy="scroll" data-target="#myScrollspy" data-offset="20">

   <?php include APPROOT . '/views/includes/navbar.php' ?>

   <div class="container-fluid">
      <div class="row content">
         <div class="col-md-3 user-overview mb-2">

            <?php include APPROOT . '/views/includes/userOverview.php' ?>

         </div>
         <div class="col-md-9 box-right">
            <div class="box-content">
               <div class="card w-100 card-profile">
                  <div class="card-body">
                     <span id="message"></span>
                     <h1>Change Password</h1>
                     <form id="change_pass_form" method="POST">
                        <div class="row">
                           <div class="col-md-2"></div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label>Enter Password</label>
                                 <input type="text" name="user_password" id="user_password" class="form-control" />
                              </div>
                              <div class="form-group">
                                 <label>Enter Confirm Password</label>
                                 <input type="text" name="confirm_user_password" id="confirm_user_password" class="form-control" />
                              </div>
                              <input type="submit" name="change_password" id="change_password" class="btn btn-info mt-3" value="Change" />
                           </div>
                           <div class="col-md-4"></div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <?php include APPROOT . '/views/includes/footer.php'; ?>

   </div>

</body>

<?php include APPROOT . '/views/includes/script.php'; ?>
<script>
   $(document).ready(function() {
      var url = "<?php echo URLROOT ?>";
      jQuery.validator.addMethod("pwStrong", function(value, element) {
         if (/^[A-Za-z0-9\d=!\-@._*]*$/.test(value) && /[a-z]/.test(value) && /\d/.test(value) && /[A-Z]/.test(value)) {
            return true;
         } else {
            return false;
         };
      }, "The password must contain at least 1 number, at least 1 lower case letter, and at least 1 upper case letter");

      var validator = $('#change_pass_form').validate({
         rules: {
            user_password: {
               pwStrong: true,
               minlength: 6,
               required: true
            },
            confirm_user_password: {
               required: true,
               equalTo: "#user_password"
            },
         },
      });

      $("#change_pass_form").on('submit', function(e) {
         e.preventDefault();
         if ($("#change_pass_form").valid()) {
            $.ajax({
               url: url + '/users/ajaxChangePass',
               method: "POST",
               data: new FormData(this),
               dataType: "json",
               contentType: false,
               cache: false,
               processData: false,
               beforeSend: function() {
                  $('#user_profile').attr('disabled', 'disabled');
                  $('#user_profile').val('please wait...');
               },
               success: function(data) {
                  if(data.success){
                     alert(data.success);
                     location.reload(true);
                  }
                  $('#change_password').attr('disabled', false);
                  $('#change_password').val('Change');
               }
            })
         }
      });

   });
</script>

</html>