<?php include APPROOT . '/views/includes/head.php';  ?>

<body data-spy="scroll" data-target="#myScrollspy" data-offset="20">

   <?php include APPROOT . '/views/includes/navbar.php' ?>

   <div class="container-fluid">
      <div class="row content">
         <div class="col-md-3 mb-3 user-overview">

            <?php include APPROOT . '/views/includes/userOverview.php' ?>

         </div>
         <div class="col-md-9 box-right mb-5">

            <div class="box-content mt-2">
               <h1>Online Exam</h1>
               <hr>
               <div class="row">
                  <div class="col-md-8">
                     <div id="single_question_area"></div>
   
                     <div id="question_navigation_area"></div>
                  </div>
                  <div class="col-md-4">
                     <br />
                     <div align="center">
                        <div id="exam_timer" data-timer="<?php echo $data['remaining_minutes']; ?>" style="max-width:400px; width: 100%; height: 200px;"></div>
                     </div>
                     <br />
                     <div id="user_details_area"></div>
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
   var url = "<?php echo URLROOT ?>";
   var exam_id = "<?php echo $exam_id; ?>";
</script>

<script src="<?php echo URLROOT . '/public/js/viewExam.js'; ?>"></script>   

</html>