<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?php echo SITENAME; ?></title>

   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
   <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css" integrity="sha384-HzLeBuhoNPvSl5KYnjx0BT+WB0QEEqLprO+NBkkk5gbc67FTaL7XIGa2w1L0Xbgc" crossorigin="anonymous">
   <link rel="stylesheet" href="<?php echo URLROOT ?>/public/css/style.css">
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
               <h3>Sign up into your account</h3>
               <div class="form py-2">
                  <form method="POST" id="admin_register_form">
                     <div class="group-input col-lg-9">
                        <input type="text" value="<?= $data['admin_email'] ?? "" ?>" name="admin_email_address" id="admin_email_address" autocomplete="off" required>
                        <label for="admin_email_address" class="lable-name">
                           <span class="content-name">Email Address</span>
                        </label>
                     </div>
                     <div class="col-lg-9">
                        <small class="errors-admin_email_address errors"></small>
                     </div>
                     <div class="group-input col-lg-9">
                        <input type="password" value="<?= $data['admin_password'] ?? "" ?>" name="admin_password" id="admin_password" autocomplete="off" required>
                        <label for="admin_password" class="lable-name">
                           <span class="content-name">Password</span>
                        </label>
                     </div>
                     <div class="col-lg-9">
                        <small class="errors-admin_password errors"></small>
                     </div>
                     <div class="group-input col-lg-9">
                        <input type="password" value="<?= $data['confirm_admin_password'] ?? "" ?>" name="confirm_admin_password" id="confirm_admin_password" autocomplete="off" required>
                        <label for="admin_password" class="lable-name">
                           <span class="content-name">Password</span>
                        </label>
                     </div>
                     <div class="col-lg-9">
                        <small class="errors-confirm_admin_password errors"></small>
                     </div>
                     <div class="form-row">
                        <div class="col-lg-9">
                           <input type="submit" class="btn1 mt-3 mb-5" value="Register">
                        </div>
                     </div>
                     <div class="footer mb-4">
                        <p>Have an account? <a href="<?php echo URLROOT ?>/admins/login">Sign in now</a></p>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </section>

</body>
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

<script>
   var url = "<?php echo URLROOT ?>";
</script>

<script src="<?php echo URLROOT . '/public/js/register.js'; ?>"></script>

</html>