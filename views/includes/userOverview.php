<div class="card border-dark">
   <div class="card-image mx-auto mt-3">
   <?php
      if(isset($_SESSION['userProfiles'])){
         $userProfiles =  $_SESSION['userProfiles'];
         foreach($userProfiles as $row){ 
   ?>
      <img class="card-img-top" src="<?php echo URLROOT ?>/upload/<?= $row['user_image'] ?>" alt="Card image">
   <?php 
      } }
   ?>

   </div>
   <div class="card-body">
      <h4 class="card-title user-name text-center"><?php echo $_SESSION['user_name'] ?></h4>
      <hr>
      <ul>
         <li><i class="fa fa-sun-o" aria-hidden="true"></i><a href="<?php echo URLROOT ?>/users/index" id="changePassword"> List Exam</a></li>
         <li><i class="fa fa-sun-o" aria-hidden="true"></i><a href="<?php echo URLROOT ?>/users/enrollExam" id="changePassword"> Enroll Exam</a></li>
         <li><i class="fa fa-sun-o" aria-hidden="true"></i><a href="<?php echo URLROOT ?>/users/editProfile" id="changeProfile"> Profile</a></li>
         <li><i class="fa fa-sun-o" aria-hidden="true"></i><a href="<?php echo URLROOT ?>/users/changePassword" id="changePassword"> Change password</a></li>
      </ul>
   </div>
</div>