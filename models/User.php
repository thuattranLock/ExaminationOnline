<?php

class User
{
   protected $db;

   public function __construct()
   {
      $this->db = new Database;
   }

   public function current_datetime()
   {
      return date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')));
   }

   public function checkMail($email)
   {
      if (!empty($email)) {
         $query = "SELECT * FROM user_table WHERE user_email_address = '${email}'";
         $this->db->query($query);
         $this->db->execute();

         $total_row = $this->db->rowCount();
         $result = $this->db->resultSet();

         if ($total_row == 0) {

            return true;
         }

         return false;
      }
   }

   public function register($data)
   {
      $query = "INSERT INTO user_table (user_email_address, user_password, user_verfication_code, user_name, user_gender,
      user_address, user_mobile_no, user_image, user_created_on)
      VALUES (:receiver_email, :user_password, 
      :user_verfication_code, :user_name, :user_gender, :user_address, :user_mobile_no, :user_image, :user_created_on) ";
      $this->db->query($query);
      $this->db->bind(":receiver_email", $data['receiver_email']);
      $this->db->bind(":user_password", $data['user_password']);
      $this->db->bind(":user_verfication_code", $data['user_verfication_code']);
      $this->db->bind(":user_name", $data['user_name']);
      $this->db->bind(":user_gender", $data['user_gender']);
      $this->db->bind(":user_address", $data['user_address']);
      $this->db->bind(":user_mobile_no", $data['user_phone']);
      $this->db->bind(":user_image", $this->uploadFile($data['filedata']));
      $this->db->bind(":user_created_on", $this->current_datetime());

      $this->db->execute();


      $subject = 'Online Examination Registration Verification';

      $body = '
      <p>Thank you for registering.</p>
      <p>This is a verification eMail, please click the link to verify your eMail address by clicking this <a href="' . URLROOT . '/users/verifyEmail/user/' . $data['user_verfication_code'] . '" target="_blank"><b>link</b></a>.</p>
      <p>In case if you have any difficulty please eMail us.</p>
      <p>Thank you,</p>
      <p>Online Examination System</p>
      ';

      $this->db->sendMail($data['receiver_email'], $subject, $body);
   }

   public function uploadFile($filedata = [])
   {
      $extension = pathinfo($filedata['name'], PATHINFO_EXTENSION);

      $new_name = uniqid() . '.' . $extension;

      $_source_path = $filedata['tmp_name'];

      $target_path = APPROOT . '/upload/' . $new_name;

      move_uploaded_file($_source_path, $target_path);

      return $new_name;
   }

   public function verifyEmail($code)
   {

      $query = "UPDATE user_table SET user_email_verified = :user_email_verified WHERE user_verfication_code = '${code}'";
      $this->db->query($query);

      $this->db->bind(':user_email_verified', 'yes');

      if ($this->db->execute()) {
         return true;
      } else {
         return false;
      }
   }

   public function login($data)
   {
      $query = "SELECT * FROM user_table WHERE user_email_address = :user_email_address";
      $this->db->query($query);
      $this->db->bind(':user_email_address', $data['user_email_address']);
      $this->db->execute();

      $total_row = $this->db->rowCount();

      if ($total_row > 0) {
         $result = $this->db->resultSet();

         foreach ($result as $row) {
            if ($row['user_email_verified'] == 'yes') {
               if (password_verify($data['user_password'], $row['user_password'])) {
                  $_SESSION['user_id'] = $row['user_id'];
                  $_SESSION['user_email_address'] = $row['user_email_address'];
                  $_SESSION['user_name'] = $row['user_name'];
                  $output = array(
                     'success'   =>   true
                  );
               } else {
                  $output = array(
                     'user_password' => 'Wrong password!'
                  );
               }
            } else {
               $output = array(
                  'user_email_address' => 'Your email is not verify'
               );
            }
         }
      } else {
         $output = array(
            'user_email_address' => 'Wrong email address!'
         );
      }

      return $output;
   }

   public function userDetails($user_id)
   {
      $query = "SELECT * FROM user_table WHERE user_id = '${user_id}'";
      $this->db->query($query);
      $this->db->execute();

      $result = $this->db->resultSet();

      return $result;
   }

   public function updateProfile($data = [])
   {
      if ($data['filedata']['name'] != '') {
         $filedata = $data['filedata'];

         $user_image = $this->uploadFile($filedata);
      } else {
         $user_image = $data['user_image'];
      }

      $query = "UPDATE user_table SET user_name = :user_name, user_gender = :user_gender, user_address = :user_address, 
      user_mobile_no = :user_mobile_no, user_image = :user_image WHERE user_id = :user_id";

      $this->db->query($query);

      $this->db->bind(':user_name',   $this->clean_data($data['user_name']));
      $this->db->bind(':user_gender',   $data['user_gender']);
      $this->db->bind(':user_address',   $this->clean_data($data['user_address']));
      $this->db->bind(':user_mobile_no',   $data['user_phone']);
      $this->db->bind(':user_image',   $user_image);
      $this->db->bind(':user_id',   $data['user_id']);

      if ($this->db->execute()) {
         return true;
      } else {
         return false;
      }
   }

   public function updatePassword($data = [])
   {
      $query = "UPDATE user_table SET user_password = :user_password WHERE user_id = :user_id";

      $this->db->query($query);
      $this->db->bind(':user_password', password_hash($data['user_password'], PASSWORD_DEFAULT));
      $this->db->bind(':user_id', $data['user_id']);

      $this->db->execute();
   }

   public function fillExamList()
   {
      $query = "SELECT online_exam_id, online_exam_title, online_exam_status 
      FROM online_exam_table WHERE online_exam_status = 'Created' OR  
      online_exam_status = 'Started'
      ORDER BY online_exam_title ASC ";

      $this->db->query($query);
      $this->db->execute();

      $result = $this->db->resultSet();

      return $result;
   }

   public function fetchExam($exam_id, $user_id)
   {
      $output = '';

      $query = "SELECT * FROM online_exam_table WHERE online_exam_id = '${exam_id}'";
      $this->db->query($query);
      $this->db->execute();

      $result = $this->db->resultSet();
      $output .= '
         <div class="card">
            <div class="card-header">Exam Details</div>
            <div class="card-body">
               <table class="table table-hover table-bordered">
      ';
      foreach ($result as $row) {
         $output .= '
            <tr>
               <td><b>Exam Title</b></td>
               <td>' . $row["online_exam_title"] . '</td>
            </tr>
            <tr>
               <td><b>Exam Date & Time</b></td>
               <td>' . $row["online_exam_datetime"] . '</td>
            </tr>
            <tr>
               <td><b>Exam Duration</b></td>
               <td>' . $row["online_exam_duration"] . ' Minute</td>
            </tr>
            <tr>
               <td><b>Exam Total Question</b></td>
               <td>' . $row["total_question"] . ' </td>
            </tr>
            <tr>
               <td><b>Marks Per Right Answer</b></td>
               <td>' . $row["marks_per_right_answer"] . ' Mark</td>
            </tr>
            <tr>
               <td><b>Marks Per Wrong Answer</b></td>
               <td>-' . $row["marks_per_wrong_answer"] . ' Mark</td>
            </tr>
         ';

         if ($this->if_user_already_enroll_exam($exam_id, $user_id)) {
            $enroll_button = '
            <tr>
               <td colspan="2" align="center">
                  <button type="button" name="enroll_button" class="btn btn-info">You Already Enroll it</button>
               </td>
            </tr>
            ';
         } else {
            $enroll_button = '
            <tr>
               <td colspan="2" align="center">
                  <button type="button" name="enroll_button" id="enroll_button" class="btn btn-warning" data-exam_id="' . $row['online_exam_id'] . '">Enroll it</button>
               </td>
            </tr>
            ';
         }

         $output .= $enroll_button;
      }

      $output .= '
               </table>
            </div>
         </div>      
      ';

      return $output;
   }

   function if_user_already_enroll_exam($exam_id, $user_id)
   {
      $query = "SELECT * FROM user_exam_enroll_table WHERE exam_id = '${exam_id}' AND user_id = '${user_id}'";
      $this->db->query($query);
      $this->db->execute();
      $total_row = $this->db->rowCount();

      if ($total_row > 0) {
         return true;
      }
      return false;
   }

   public function enrollExam($exam_id, $user_id)
   {
      $query = "INSERT INTO user_exam_enroll_table (user_id, exam_id) VALUES (:user_id, :exam_id)";
      $this->db->query($query);

      $this->db->bind(':user_id', $user_id);
      $this->db->bind(':exam_id', $exam_id);
      $this->db->execute();

      $query = "SELECT question_id FROM question_table WHERE online_exam_id = '${exam_id}'";
      $this->db->query($query);
      $this->db->execute();

      $result = $this->db->resultSet();

      foreach ($result as $row) {
         $query = "INSERT INTO user_exam_question_answer (user_id, exam_id, question_id, user_answer_option, marks) 
         VALUES (:user_id, :exam_id, :question_id, :user_answer_option, :marks)";

         $this->db->query($query);

         $this->db->bind(':user_id', $user_id);
         $this->db->bind(':exam_id', $exam_id);
         $this->db->bind(':question_id', $row['question_id']);
         $this->db->bind(':user_answer_option', '0');
         $this->db->bind(':marks', '0');

         $this->db->execute();
      }
   }

   public function fetchEnrollExam($data = [])
   {
      $output = array();

      $query = "SELECT * FROM user_exam_enroll_table 
      INNER JOIN online_exam_table 
      ON online_exam_table.online_exam_id = user_exam_enroll_table.exam_id 
      WHERE user_exam_enroll_table.user_id = '${data['user_id']}'";

      if (!empty($data['search'])) {
         $query .= " AND (online_exam_table.online_exam_title LIKE '%${data['search']}%'";
         $query .= " OR online_exam_table.online_exam_datetime LIKE '%${data['search']}%'";
         $query .= " OR online_exam_table.online_exam_duration LIKE '%${data['search']}%'";
         $query .= " OR online_exam_table.total_question LIKE '%${data['search']}%'";
         $query .= " OR online_exam_table.marks_per_right_answer LIKE '%${data['search']}%'";
         $query .= " OR online_exam_table.marks_per_wrong_answer LIKE '%${data['search']}%'";
         $query .= " OR online_exam_table.online_exam_status LIKE '%${data['search']}%'";
         $query .= ")";
      }

      if (!empty($data['order'])) {
         $order_by_column = $data['order']['0']['column'];
         $order_by_dir = $data['order']['0']['dir'];

         $order_by_column_name = $data['columns'][$order_by_column]['name'];
         $query .= " ORDER BY ${order_by_column_name} ${order_by_dir} ";
      } else {
         $query .= " ORDER BY online_exam_table.online_exam_id DESC";
      }

      $extra_query = '';

      if ($data['length'] != -1) {
         $extra_query .= " LIMIT {$data['start']} , {$data['length']}";
      }

      $query .= $extra_query;

      $this->db->query($query);
      $this->db->execute();

      $filtered_rows = $this->db->rowCount();
      $result = $this->db->resultSet();

      $query = "SELECT * FROM user_exam_enroll_table 
               INNER JOIN online_exam_table 
               ON online_exam_table.online_exam_id = user_exam_enroll_table.exam_id 
               WHERE user_exam_enroll_table.user_id = '${data['user_id']}'";

      $this->db->query($query);
      $this->db->execute();
      $total_rows = $this->db->rowCount();

      $datatable = array();

      foreach($result as $row){

         $sub_array = array();
         $sub_array[] = html_entity_decode($row["online_exam_title"]);
         $sub_array[] = $row["online_exam_datetime"];
         $sub_array[] = $row["online_exam_duration"] . ' Minute';
         $sub_array[] = $row["total_question"] . ' Question';
         $sub_array[] = $row["marks_per_right_answer"] . ' Mark';
         $sub_array[] = '-' . $row["marks_per_wrong_answer"] . ' Mark';
         $status = "";
         $view_exam = "";

         if($row['online_exam_status'] == 'Pending'){
            $status = '<span class="badge badge-warning">Pending</span>';
         }
         if($row['online_exam_status'] == 'Created'){
            $status = '<span class="badge badge-success">Created</span>';
         }

         if($row['online_exam_status'] == 'Started'){
            $status = '<span class="badge badge-primary">Started</span>';
         }

         if($row['online_exam_status'] == 'Completed'){
            $status = '<span class="badge badge-dark">Completed</span>';
         }

         $sub_array[] = $status;				

         if($row["online_exam_status"] == 'Started'){
            $view_exam = '<a href="'.URLROOT.'/users/viewExam/'.$row["online_exam_code"].'" class="btn btn-info btn-sm">View Exam</a>';
         }

         if($row["online_exam_status"] == 'Completed'){
            $view_exam = '<a href="'.URLROOT.'/users/viewExam/'.$row["online_exam_code"].'" class="btn btn-info btn-sm">View Exam</a>';
         }

         $sub_array[] = $view_exam;
         $datatable[] = $sub_array;
      }

      $output = array(
         "draw"            =>   intval($data["draw"]),
         "recordsTotal"      =>   $total_rows,
         "recordsFiltered"   =>   $filtered_rows,
         "data"            =>   $datatable
      );

      return $output;
   }

   public function getExamId($code)
   {
      $query = "SELECT online_exam_id FROM online_exam_table WHERE online_exam_code = '${code}'";
      $this->db->query($query);
      $result = $this->db->resultSet();

      foreach($result as $row){
         return $row['online_exam_id'];
      }
   }

   public function getExamDetail($exam_id)
   {
      $data = [];

      $query = "SELECT online_exam_status, online_exam_datetime, online_exam_duration FROM
      online_exam_table WHERE online_exam_id = '${exam_id}'";
      $this->db->query($query);
      $result = $this->db->resultSet();

      foreach($result as $row){
         $data['exam_status'] = $row['online_exam_status'];
         $data['exam_start_time'] = $row['online_exam_datetime'];
         $data['duration'] = $row['online_exam_duration'] . 'minute';
         $data['exam_end_time'] = strtotime($data['exam_start_time'] . '+' . $data['duration']);
         
         $data['exam_end_time'] = date('Y-m-d H:i:s', $data['exam_end_time']);
         $data['remaining_minutes'] = strtotime($data['exam_end_time']) -time();
      }

      return $data;
   }

   public function set_attendance_status_to_present($user_id, $exam_id)
   {
      $query = "UPDATE user_exam_enroll_table SET attendance_status = :attendance_status 
      WHERE user_id = :user_id AND exam_id = :exam_id";
      $this->db->query($query);

      $this->db->bind(':user_id', $user_id);
      $this->db->bind(':exam_id', $exam_id);
      $this->db->bind(':attendance_status', 'Present');

      $this->db->execute();
   }

   public function get_data_result_exam($user_id, $exam_id)
   {
      $data = array();

      $query = "SELECT * FROM question_table INNER JOIN user_exam_question_answer ON 
      user_exam_question_answer.question_id = question_table.question_id 
      WHERE question_table.online_exam_id = '${exam_id}' 
      AND user_exam_question_answer.user_id = '${user_id}'";

      $this->db->query($query);
      $data['result_user_answer'] = $this->db->resultSet();

      foreach($data['result_user_answer']  as $row){
         $sub_query = "SELECT * FROM option_table WHERE question_id = '".$row['question_id']."'";
         $this->db->query($sub_query);
         $data['options'] = $this->db->resultSet();      
      }

      $marks_result_query = "SELECT SUM(marks) as total_mark FROM user_exam_question_answer 
      WHERE user_id = '".$user_id."' AND exam_id = '".$exam_id."'";
      $this->db->query($marks_result_query);
		$data['marks_result'] = $this->db->resultSet();

      return $data;
   }

   public function loadQuestion($exam_id, $question_id, $user_id)
   {
      if($question_id == ''){
         $query = "SELECT * FROM question_table WHERE online_exam_id = '${exam_id}' 
         ORDER BY question_id ASC LIMIT 1";

         $sub_query = "SELECT user_answer_option FROM user_exam_question_answer WHERE exam_id = '${exam_id}' 
         AND user_id = '${user_id}'ORDER BY question_id ASC LIMIT 1";

      }else{
         $query = "SElECT * FROM question_table WHERE question_id= '${question_id}'";

         $sub_query = "SELECT user_answer_option FROM user_exam_question_answer WHERE question_id = '${question_id}'
         AND user_id = '${user_id}'ORDER BY question_id ASC LIMIT 1";         
   
      }
      
      $this->db->query($query);
      $result = $this->db->resultSet();

      $this->db->query($sub_query);
      $user_answer = $this->db->resultSet();

      foreach($user_answer as $user_answer_row){
         $user_option_answer = $user_answer_row['user_answer_option'];
      }

      $output = '';

      $sub_count=1;

      foreach($result as $row){
         $output .= '
            
               <div class="question_title" style="color:white"><p class="font-weight-bold"> Question '.$sub_count.'. '.$row['question_title'].'</p></div>
               
                  <div class="row">
         ';
         
         $query = "SELECT * FROM option_table WHERE question_id = '".$row['question_id']."'";
         $this->db->query($query);
         $sub_result = $this->db->resultSet();

         $count = 1;
         foreach($sub_result as $sub_row){
            $output .= '
               <div class="col-md-6" style="margin-bottom:5px;color:white">
                  <div class="radio">
                     <label><p>
                        <input type="radio" name="option_1" class="answer_option"'; 
                        if($count == $user_option_answer){
                           $output .= ' checked="checked" ';
                        }   
                        $output .= 'data-question_id="'.$row["question_id"].'" data-id="'.$count.'"/>
                        &nbsp;'.$sub_row["option_title"].'
                     </p></label>
                  </div>
               </div>
            ';

            $count = $count + 1;
         }

         $sub_count++;

         $output .= '
                  
               </div>

         ';

         $query = "SELECT question_id FROM question_table WHERE question_id < '".$row['question_id']."' 
				AND online_exam_id = '".$exam_id."' ORDER BY question_id DESC LIMIT 1";
         
         $this->db->query($query);
         $previous_result =  $this->db->resultSet();

         $previous_id = '';
         $next_id = '';

         foreach($previous_result as $previous_row)
         {
            $previous_id = $previous_row['question_id'];
         }

         $query = "SELECT question_id FROM question_table WHERE question_id > '".$row['question_id']."' 
         AND online_exam_id = '".$exam_id."'ORDER BY question_id ASC LIMIT 1";
         
         $this->db->query($query);
         $next_result =  $this->db->resultSet();

         // print_r($next_result);
         
         foreach($next_result as $next_row)
         {
            $next_id = $next_row['question_id'];
         }

         $if_previous_disable = '';
         $if_next_disable = '';

         if($previous_id == "")
         {
            $if_previous_disable = 'disabled';
         }
         
         if($next_id == "")
         {
            $if_next_disable = 'disabled';
         }

         $output .= '
            <br /><br />
            <div align="center">
                  <button type="button" name="previous" class="btn btn-info btn-sm previous" id="'.$previous_id.'" '.$if_previous_disable.'><</button>
                  <button type="button" name="next" class="btn btn-info btn-sm next" id="'.$next_id.'" '.$if_next_disable.'>></button>
            </div>
            <br /><br />
         ';
      }
      return $output;
   }

   public function questionNavigation($exam_id)
   {
      
      $query = "SELECT question_id FROM question_table WHERE online_exam_id = '".$exam_id."' 
               ORDER BY question_id ASC ";
      $this->db->query($query);
      $result = $this->db->resultSet();
      $output = '
      <div class="card">
         <div class="card-header">Question Navigation</div>
         <div class="card-body">
            <div class="row">
      ';
      $count = 1;
      foreach($result as $row)	
      {
         $output .= '
         <div class="col-2" style="margin-bottom:24px;">
            <button type="button" class="btn btn-primary btn-sm question_navigation" data-question_id="'.$row["question_id"].'">'.$count.'</button>
         </div>
         ';
         $count++;
      }
      $output .= '
         </div>
      </div></div><br>
      <div><button class="btn btn-primary" id="sunmitExam">Submit</button></div>
      ';

      return $output;
   }

   public function answer($data)
   {
      $exam_right_answer_mark = $this->Get_question_right_answer_mark($data['exam_id']);

      $exam_wrong_answer_mark = $this->Get_question_wrong_answer_mark($data['exam_id']);
      $orignal_answer = $this->Get_question_answer_option($data['question_id']);

      $marks = 0;

      if($orignal_answer == $data['answer_option'])
      {
         $marks = '+' . $exam_right_answer_mark;
      }
      else
      {
         $marks = '-' . $exam_wrong_answer_mark;
      }

      $query = "UPDATE user_exam_question_answer 
      SET user_answer_option = :user_answer_option, marks = :marks 
      WHERE user_id = '".$data["user_id"]."' AND exam_id = '".$data['exam_id']."' 
      AND question_id = '".$data["question_id"]."'
      ";

      $this->db->query($query);
      $this->db->bind(':user_answer_option', $data['answer_option']);
      $this->db->bind(':marks', $marks);
      $this->db->execute();
   }

   function Get_question_right_answer_mark($exam_id)
	{
		$query = "SELECT marks_per_right_answer FROM online_exam_table 
		WHERE online_exam_id = '".$exam_id."' ";
      $this->db->query($query);
		$result = $this->db->resultSet();

		foreach($result as $row)
		{
			return $row['marks_per_right_answer'];
		}
	}

	function Get_question_wrong_answer_mark($exam_id)
	{
		$query = "SELECT marks_per_wrong_answer FROM online_exam_table 
		WHERE online_exam_id = '".$exam_id."' ";
      $this->db->query($query);
		$result = $this->db->resultSet();

		foreach($result as $row)
		{
			return $row['marks_per_wrong_answer'];
		}
	}

	function Get_question_answer_option($question_id)
	{
		$query = "SELECT answer_option FROM question_table 
		WHERE question_id = '".$question_id."'";
      $this->db->query($query);
		$result = $this->db->resultSet();

		foreach($result as $row)
		{
			return $row['answer_option'];
		}
   }
   
   public function updateStatusExamToCompleted($exam_id, $user_id)
   {
      $query = "UPDATE online_exam_table SET online_exam_status = 'Completed' 
      WHERE online_exam_id = '${exam_id}'";

      $this->db->query($query);
      
      if($this->db->execute()){
         return true;
      }else{
         return false;
      }
   }

   function clean_data($data)
   {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
   }
}
