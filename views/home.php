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
          <h1>Exam</h1>
          <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
              <div class="form-group mb-3">
                <form id="exam_list_form" method="POST">
                  <label for="exam_list">Exam</label>
                  <select name="exam_list" id="exam_list" class="form-control">
                    <option value="">Chosse....</option>
                    <?= $output ?>
                  </select>
                </form>
              </div>
              <div class="load-exam-overview">

              </div>
            </div>
            <div class="col-md-3"></div>
          </div>
        </div>
      </div>
    </div>

    <?php include APPROOT . '/views/includes/footer.php'; ?>

  </div>

</body>

<?php include APPROOT . '/views/includes/script.php'; ?>

<script>
  $(document).ready(() => {

    var exam_id = '';
    var url = "<?php echo URLROOT ?>"

    $("#exam_list_form").validate({
      rules: {
        exam_list: {
          required: true
        }
      }
    });

    $("#exam_list").change(function() {
      if ($("#exam_list_form").valid()) {
        exam_id = $('#exam_list').val();
        $.ajax({
          url: url + "/users/ajaxFetchExam",
          method: "POST",
          data: { exam_id: exam_id },
          success: function(data) {
            $('.load-exam-overview').html(data);
          }
        });
      }
    })

    $(document).on('click', '#enroll_button', function(){
      $("#enroll_button").on('click', function(){
       
      exam_id = $('#enroll_button').data('exam_id');

      $.ajax({
        url: url + "/users/ajaxEnrollExam",
        method:"POST",
        data:{exam_id:exam_id},
        beforeSend:function(){
          $('#enroll_button').attr('disabled', 'disabled');
          $('#enroll_button').text('please wait');
        },
        success:function(){
          $('#enroll_button').attr('disabled', false);
          $('#enroll_button').removeClass('btn-warning');
          $('#enroll_button').addClass('btn-success');
          $('#enroll_button').text('Enroll success');
        }
      });
    })
    })
  });
</script>

</html>