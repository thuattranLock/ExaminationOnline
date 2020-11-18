<?php include APPROOT . '/views/includes/head.php';  ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css">

<body data-spy="scroll" data-target="#myScrollspy" data-offset="20">

  <?php include APPROOT . '/views/includes/navbar.php' ?>

  <div class="container-fluid">
    <div class="row content">
      <div class="col-md-3 mb-3 user-overview">

        <?php include APPROOT . '/views/includes/userOverview.php' ?>

      </div>
      <div class="col-md-9 box-right mb-5">

        <div class="box-content mt-2">
            <h1>Enroll Exam</h1>
            <div class="table-responsive bg-white px-2 py-3">
               <table class="table table-bordered table-striped table-hover" id="exam_data_table">
                  <thead class="bg-secondary">
                     <tr>
                        <th>Exam Title</th>
                        <th>Date & Time</th>
                        <th>Duration</th>
                        <th>Total Question</th>
                        <th>Right Answer Mark</th>
                        <th>Wrong Answer Mark</th>
                        <th>Status</th>
                        <th>Action</th>
                     </tr>
                  </thead>
               </table>
		      </div>
        </div>
      </div>
    </div>

    <?php include APPROOT . '/views/includes/footer.php'; ?>

  </div>

</body>

<?php include APPROOT . '/views/includes/script.php'; ?>
<script src="<?php echo URLROOT ?>/public/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>

<script>
   $(document).ready(function () {
      var url = '<?php echo URLROOT ?>';

      var datatable = $('#exam_data_table').DataTable({
         "processing" :true,
         "serverSide" :true,
         "order" :[],
         "ajax" :{
            url: url +"/users/ajaxFetchEnrollExam",
            method:"POST",
         },
         "columnDefs":[
            { "name": "online_exam_title",   	"targets": [0] },
            { "name": "online_exam_datetime",  	"targets": [1] },
            { "name": "online_exam_duration", 	"targets": [2] },
            { "name": "total_question",  			"targets": [3] },
            { "name": "marks_per_wrong_answer", "targets": [5] },
            { "name": "marks_per_right_answer", "targets": [4] },
            { "name": "online_exam_status",    	"targets": [6] },
            { "orderable": false,					"targets": [7] }
         ],
      });

      setInterval(function(){
         datatable.ajax.reload()
      },20000)

   });
</script>
</html>