<!doctype html>
<html lang="en">

<head>
   <!-- Required meta tags -->
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <title>Examination | Log In</title>
   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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
               <?php if(isset($verified)){ ?>
                  <div class="alert alert-success">
                     Your email has been verified, now you can login
                  </div>
               <?php } ?>
               <h1 class="font-weight-bold py-3 text-dark">Examination Online</h1>
               <h3 class="text-muted">Sign into your account</h3>
               <div class="form py-2">   
                  <form method="POST" id="user_login_form">
                     <div class="group-input col-lg-9">
                        <input type="email" name="user_email_address" id="user_email_address" autocomplete="off" required>
                        <label for="name" class="lable-name">
                           <span class="content-name">Email</span>
                        </label>
                     </div>
                     <div class="col-lg-9">
                        <div class="errors-user_email_address errors"></div>
                     </div>
                     <div class="group-input col-lg-9">
                        <input type="password" name="user_password" id="user_password" autocomplete="off" required>
                        <label for="password" class="lable-name">
                           <span class="content-name">Password</span>
                        </label>
                     </div>
                     <div class="col-lg-9">
                        <div class="errors-user_password errors"></div>
                     </div>
                     <div class="form-row">
                        <div class="col-lg-9">
                           <input type="submit" class="mt-3 mb-5" value="Sign In">
                        </div>
                     </div> 
                     <div class="footer mb-4">
                        <a href="#">Forgot passwrod?</a>
                        <p>Don't have an account? <a href="<?php echo URLROOT ?>/users/register"">Register here</a></p>
                     </div>
                 </form>
               </div>
            </div>
         </div>
      </div>
   </section>
</body>

<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>

<script>
   var url = "<?php echo URLROOT ?>";
</script>
<script src="<?php echo URLROOT . '/public/js/userLogin.js'; ?>"></script>

</html>