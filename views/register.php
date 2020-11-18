<!doctype html>
<html lang="en">

<head>
   <!-- Required meta tags -->
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <title>Examination | Sign Up</title>
   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
   <link rel="stylesheet" href="<?php echo URLROOT ?>/public/css/user.css">
   <style>
      label.error {
         color: red;
         font-size: 1rem;
         display: block;
         margin-top: 5px;
      }

      input.error {
         border: 1px dashed red;
         font-weight: 300;
         color: red;
      }
   </style>
</head>

<body>
   <section class="form my-4 mx-5">
      <div class="container">
         <div class="row no-gutters">
            <div class="col-lg-5 box-img">
               <img src="<?php echo URLROOT ?>/public/img/user.jpg" alt="">
            </div>
            <div class="col-lg-7 px-4 pt-5">
               <div id="alert"></div>
               <h1 class="font-weight-bold py-3">Examination Online</h1>
               <h2>Sign into your account</h2>
               <div class="form py-2">
                  <form method="POST" id="user_form_register" enctype="multipart/form-data">
                     <div class="form-group col-lg-9">
                        <label for="user_email_address">
                           <span class="content-name text-secondary">Email Address</span>
                        </label>
                        <input type="email" class="form-control" name="user_email_address" id="user_email_address" autocomplete="off" placeholder="Email Address" required>
                        <small class="errors-admin_email_address errors"></small>
                     </div>
                     <div class="form-group col-lg-9">
                        <label for="user_password">
                           <span class="content-name text-secondary">Password</span>
                        </label>
                        <div class="form-row align-items-center">
                           <div class="form-group col-10">
                              <input type="password" class="form-control toggle-1" name="user_password" id="user_password" autocomplete="off" placeholder="******" required>
                           </div>
                           <div class="form-group col-2">
                              <div class="form-check mb-2">
                                 <input class="form-check-input toggle" value="0" name="checkbox1" type="checkbox" data-toggle="toggle-1">
                                 <label class="form-check-label" for="toggle">
                                    Show
                                 </label>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-12" id="errorPass"></div>
                     </div>
                     <div class="form-group col-lg-9">
                        <label for="user_confirm_password">
                           <span class="content-name text-secondary">Confirm Password</span>
                        </label>
                        <div class="form-row align-items-center">
                           <div class="form-group col-10">
                              <input type="password" class="form-control toggle-2" name="user_confirm_password" id="user_confirm_password" placeholder="******" autocomplete="off" required>
                           </div>
                           <div class="form-group col-2">
                              <div class="form-check mb-2">
                                 <input class="form-check-input toggle" value="0" name="checkbox2" type="checkbox" data-toggle="toggle-2">
                                 <label class="form-check-label" for="toggle">
                                    Show
                                 </label>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-12" id="errorConfirmPass"></div>
                     </div>
                     <div class="form-group col-lg-9">
                        <label for="user_name">
                           <span class="content-name text-secondary">Name</span>
                        </label>
                        <input type="text" class="form-control" name="user_name" id="user_name" autocomplete="off" required>
                        <small class="errors-confirm_admin_password errors"></small>
                     </div>
                     <div class="form-group col-lg-9">
                        <label for="user_gender">
                           <span class="content-name text-secondary">Gender</span>
                        </label>
                        <select name="user_gender" id="user_gender" name="user_gender" class="custom-select" required>
                           <option value="">Choose</option>
                           <option value="Male">Male</option>
                           <option value="Female">Female</option>
                        </select>
                        <small class="errors-user_gender errors"></small>
                     </div>
                     <div class="form-group col-lg-9">
                        <label for="user_address">
                           <span class="content-name text-secondary">Address</span>
                        </label>
                        <textarea name="user_address" id="user_address" cols="auto" rows="2" class="form-control" required></textarea>
                        <small class="errors-user_address errors"></small>
                     </div>
                     <div class="form-group col-lg-9">
                        <label for="user_phone">
                           <span class="content-name text-secondary">Phone Number</span>
                        </label>
                        <input type="text" class="form-control" name="user_phone" id="user_phone" autocomplete="off" required>
                        <small class="errors-confirm_admin_password errors"></small>
                     </div>
                     <div class="form-group col-lg-9">
                        <label for="user_avatar">
                           <span class="content-name text-secondary">Avartar</span>
                        </label>
                        <input type="file" name="user_avatar" id="user_avatar" class="form-control">
                        <small class="errors-user_avatar errors"></small>
                     </div>
                     <div class="form-row">
                        <div class="col-lg-9">
                           <input type="submit" class="btn1 mt-3 mb-5" id="userRegister" value="Register">
                        </div>
                     </div>
                     <div class="footer mb-4">
                        <p>Have an account? <a href="<?php echo URLROOT ?>/users/login">Sign in now</a></p>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </section>

</body>

<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>
   var url = "<?php echo URLROOT ?>"
</script>
<script src="<?php echo URLROOT . '/public/js/userRegister.js'; ?>"></script>

</html>