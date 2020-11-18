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
                     <h1>Profile</h1>
                     <form id="user_form_profile" method="POST">
                        <?php foreach( $userProfiles as $row){ ?>
                           <div class="form-row">
                              <div class="form-group col-md-6">
                                 <label for="name">Name</label>
                                 <input type="text" class="form-control" name="name" id="name" value="<?= $row['user_name'] ?>" placeholder="Your name">
                              </div>
                              <div class="form-group col-md-6">
                                 <label for="gender">Gender</label>
                                 <select class="form-control" name="gender" id="gender">
                                    <option value="">Choose</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                 </select>
                              </div>
                           </div>
                           <div class="form-group">
                              <label for="address">Address</label>
                              <input type="text" class="form-control" name="address" id="address" value="<?= $row['user_address'] ?>" placeholder="Address">
                           </div>
                           <div class="form-row">
                              <div class="col-md-7">
                                 <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="text" class="form-control" name="phone" id="phone" value="<?= $row['user_mobile_no'] ?>" placeholder="Phone Number">
                                 </div>
                                 <div class="form-group">
                                    <label for="image">Image</label>
                                    <input type="file" class="form-control mb-3" name="image" id="image">
                                 </div>
                              </div>
                              <div class="col-md-5 px-auto pl-4 pt-3">
                                 <img src="<?php echo URLROOT ?>/upload/<?= $row["user_image"]; ?>" class="img-thumbnail" width="350"/> <br>
                                 <input type="hidden" name="hidden_user_image" value="<?= $row["user_image"]; ?>" />
                              </div>
                           </div>
                           <input type="submit" name="user_profile" id="user_profile" class="btn btn-info mt-3" value="Save"/>
                        <?php } ?>
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
   $(document).ready(function () {
      $("#gender").val("<?php echo $row['user_gender']; ?>");
   });
                                 

   $(document).ready(function () {
      var url = "<?php echo URLROOT ?>";
      jQuery.validator.addMethod("phone", function (value, element) {
         if (/([0-9]{10})|(\([0-9]{3}\)\s+[0-9]{3}\-[0-9]{4})/.test(value)) {
            return true;
         } else {
            return false;
         };
      }, "Please specify a valid phone number");

      var validator = $('#user_form_profile').validate({
         rules: {
            name:{
               required: true,
               minlength: 3
            },
            gender: {
               required: true
            },
            address: {
               required: true
            },
            image: { 
               accept: "image/*"
            },
            phone: {
               required: true,
               phone: true,
               minlength: 10,
               maxlength: 10,
            }
         },
      });
   
      $("#user_form_profile").on('submit', function (e) { 
         e.preventDefault();
         if($("#user_form_profile").valid()){
            $.ajax({
               url: url + '/users/ajaxUpdateProfile',
               method: "POST",
               data: new FormData(this),
               dataType: "json",
               contentType: false,
               cache: false,
               processData: false,
               beforeSend:function(){
                  $('#user_profile').attr('disabled', 'disabled');
                  $('#user_profile').val('please wait...');
				   },
               success: function(data) {
                  if (data.success) {
                     location.reload(true);
                  }else{
                     $('#message').html('<div class="alert alert-danger">'+data.error+'</div>');
                  }
                  $('#user_profile').attr('disabled', false);
                  $('#user_profile').val('Save');
                  return false;

               }
            })
         }
      });

   });   
</script>
</html>