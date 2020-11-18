<?php

use Sabberworm\CSS\Value\URL;

class Exam
{
   protected $db;

   public function __construct()
   {
      $this->db = new Database;
   }

   public function queryExam(array $data = [])
   {
      $output = array();

      $query = "SELECT * FROM online_exam_table WHERE admin_id = ${data['admin_id']} ";

      if (!empty($data['search_value'])) {
         $query .= "AND (online_exam_title LIKE '%${data['search_value']}%'";
         $query .= "OR online_exam_datetime LIKE '%${data['search_value']}%'";
         $query .= "OR online_exam_duration LIKE '%${data['search_value']}%'";
         $query .= "OR total_question LIKE '%${data['search_value']}%'";
         $query .= "OR marks_per_right_answer LIKE '%${data['search_value']}%'";
         $query .= "OR marks_per_wrong_answer LIKE '%${data['search_value']}%'";
         $query .= "OR online_exam_status LIKE '%${data['search_value']}%'";
         $query .= ")";
      }

      if (!empty($data['order'])) {
         $data['order_by_column'] = $data['order']['0']['column'];
         $data['order_by_dir'] = $data['order']['0']['dir'];

         $data['order_by_column_name'] = $data['columns'][$data['order_by_column']]['name'];
         $query .= " ORDER BY {$data['order_by_column_name']} {$data['order_by_dir']}";
      } else {
         $query .= " ORDER BY online_exam_id DESC";
      }

      $extra_query = '';

      if ($data['length'] != -1) {
         $extra_query .= " LIMIT ${data['start']}, ${data['length']}";
      }
      $query .= $extra_query;
      $this->db->query($query);
      $this->db->execute();
      $filtered_rows = $this->db->rowCount();

      $result = $this->db->resultSet();

      $query = "SELECT * FROM online_exam_table WHERE admin_id = ${data["admin_id"]} ";

      $this->db->query($query);
      $this->db->execute();

      $total_rows = $this->db->rowCount();

      $datatable = array();

      foreach ($result as $row) {

         $sub_array = array();
         $sub_array[] = html_entity_decode($row['online_exam_title']);
         $sub_array[] = $row['online_exam_datetime'];
         $sub_array[] = $row['online_exam_duration'] . ' Minute';
         $sub_array[] = $row['total_question'] . ' Question';
         $sub_array[] = $row['marks_per_right_answer'] . ' Mark';
         $sub_array[] = $row['marks_per_wrong_answer'] . ' Mark';

         $status = '';
         $edit_button = '';
         $delete_button = '';
         $question_button = '';
         $result_button = '';

         if ($row['online_exam_status'] == 'Pending') {
            $status = '<span class="badge badge-warning">Pending</span>';
         }

         if ($row['online_exam_status'] == 'Created') {
            $status = '<span class="badge badge-success">Created</span>';
         }

         if ($row['online_exam_status'] == 'Started') {
            $status = '<span class="badge badge-primary">Started</span>';
         }

         if ($row['online_exam_status'] == 'Completed') {
            $status = '<span class="badge badge-dark">Completed</span>';
         }

         $sub_array[] = $status;
         $sub_array[] = '
            <a href="' . URLROOT . '/exams/enrollmentList/' . $row['online_exam_code'] . '" class="btn btn-light">Enroll</a>
         ';

         if ($this->is_allowed_add_question($row['online_exam_id'])) {
            $question_button = '
            <button type="button" name="add_question" class="btn btn-info btn-sm add_question" id="' . $row['online_exam_id'] . '">Add Question</button>
            ';
         } else {

            $question_button = '
            <a href="'.URLROOT.'/questions/list/' . $row['online_exam_code'] . '" class="btn btn-warning btn-sm">View Question</a>
            ';
         }

         $sub_array[] = $question_button;

         if ($this->is_exam_is_not_started($row["online_exam_id"])) {
            $edit_button = '
            <button type="button" name="edit" class="btn btn-primary btn-sm edit" id="' . $row['online_exam_id'] . '">Edit</button>
            ';

            $delete_button = '
            <button type="button" name="delete" class="btn btn-danger btn-sm delete" id="' . $row['online_exam_id'] . '">Delete</button>
            ';

            $sub_array[] = $edit_button . ' ' . $delete_button;
         } else {
            $result_button = '<a href="' . URLROOT . '/exams/listUserExamResult/' . $row["online_exam_code"] . '" class="btn btn-dark btn-sm">Result</a>';
            $sub_array[] = $result_button;
         }

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

   public function is_exam_is_not_started($online_exam_id)
   {
      date_default_timezone_set('Asia/Ho_Chi_Minh');
      $current_datetime = date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')));
      $exam_datetime = '';

      $query = "SELECT online_exam_datetime FROM online_exam_table WHERE online_exam_id = '${online_exam_id}'";
      $this->db->query($query);
      $this->db->execute();

      $result = $this->db->resultSet();

      foreach ($result as $row) {
         $exam_datetime = $row['online_exam_datetime'];
      }

      if ($exam_datetime > $current_datetime) {
         return true;
      }

      return false;
   }

   public function is_allowed_add_question($exam_id)
   {
      $exam_question_limit = $this->get_exam_question_limit($exam_id);
      $exam_total_question = $this->get_exam_total_question($exam_id);

      if ($exam_total_question >= $exam_question_limit) {

         return false;
      }

      return true;
   }

   function get_exam_question_limit($exam_id)
   {
      $query = "SELECT total_question FROM online_exam_table WHERE online_exam_id = ${exam_id}";
      $this->db->query($query);
      $this->db->execute();
      $result = $this->db->resultSet();

      foreach ($result as $row) {
         return $row['total_question']; // 20
      }
   }

   function get_exam_total_question($exam_id)
   {
      $query = "SELECT question_id FROM question_table WHERE online_exam_id = ${exam_id}";
      $this->db->query($query);
      $this->db->execute();
      return $this->db->rowCount(); //4
   }

   function clean_data($data)
   {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
   }

   public function addQueryExam($data)
   {
      $this->db->query('INSERT INTO online_exam_table (
         admin_id, online_exam_title, online_exam_datetime, online_exam_duration, total_question, marks_per_right_answer,
         marks_per_wrong_answer, online_exam_created_on, online_exam_status, online_exam_code) VALUES (
         :admin_id, :online_exam_title, :online_exam_datetime, :online_exam_duration, :total_question, :marks_per_right_answer,
         :marks_per_wrong_answer, :online_exam_created_on, :online_exam_status, :online_exam_code)');

      //Bind values
      $this->db->bind(':admin_id', $data['admin_id']);
      $this->db->bind(':online_exam_title', $data['online_exam_title']);
      $this->db->bind(':online_exam_datetime', $data['online_exam_datetime']);
      $this->db->bind(':online_exam_duration', $data['online_exam_duration']);
      $this->db->bind(':total_question', $data['total_question']);
      $this->db->bind(':marks_per_right_answer', $data['marks_per_right_answer']);
      $this->db->bind(':marks_per_wrong_answer', $data['marks_per_wrong_answer']);
      $this->db->bind(':online_exam_created_on', $data['online_exam_created_on']);
      $this->db->bind(':online_exam_status', $data['online_exam_status']);
      $this->db->bind(':online_exam_code', $data['online_exam_code']);

      if ($this->db->execute()) {

         return true;
      }

      return false;
   }

   public function fetchExamDetails($exam_id)
   {
      $query = "SELECT * FROM online_exam_table WHERE online_exam_id = ${exam_id}";
      $this->db->query($query);

      if ($this->db->execute()) {
         $result = $this->db->resultSet();
         foreach ($result as $row) {
            $data['online_exam_title'] = $row['online_exam_title'];
            $data['online_exam_datetime'] = $row['online_exam_datetime'];
            $data['online_exam_duration'] = $row['online_exam_duration'];
            $data['total_question'] = $row['total_question'];
            $data['marks_per_right_answer'] = $row['marks_per_right_answer'];
            $data['marks_per_wrong_answer'] = $row['marks_per_wrong_answer'];
         }
      }

      return $data;
   }

   public function updateExam($exam_id, $data)
   {
      $query = "UPDATE online_exam_table SET online_exam_title = :online_exam_title, online_exam_datetime = :online_exam_datetime, 
      online_exam_duration = :online_exam_duration, total_question = :total_question, marks_per_right_answer = :marks_per_right_answer, 
      marks_per_wrong_answer = :marks_per_wrong_answer WHERE online_exam_id = :online_exam_id";

      $this->db->query($query);

      $this->db->bind(':online_exam_title', $data['online_exam_title']);
      $this->db->bind(':online_exam_datetime', $data['online_exam_datetime']);
      $this->db->bind(':online_exam_duration', $data['online_exam_duration']);
      $this->db->bind(':total_question', $data['total_question']);
      $this->db->bind(':marks_per_right_answer', $data['marks_per_right_answer']);
      $this->db->bind(':marks_per_wrong_answer', $data['marks_per_wrong_answer']);
      $this->db->bind(':online_exam_id', $exam_id);

      if ($this->db->execute()) {

         return true;
      }

      return false;
   }

   public function deleteExam($exam_id)
   {
      $query = "DELETE FROM online_exam_table WHERE online_exam_id = :online_exam_id";
      $this->db->query($query);

      $this->db->bind(':online_exam_id', $exam_id);

      if ($this->db->execute()) {

         return true;
      }

      return false;
   }

   public function checkStatusIsLimit()
   {
      $query = "SELECT * FROM online_exam_table";
      $this->db->query($query);
      $this->db->execute();
      $result = $this->db->resultSet();

      foreach ($result as $row) {
         if ($this->is_exam_is_not_started($row["online_exam_id"])) {
            $sub_query = "SELECT * FROM question_table WHERE online_exam_id = '" . $row['online_exam_id'] . "'";
            $this->db->query($sub_query);
            $this->db->execute();
            $numberQuestion = $this->db->rowCount();

            if ($numberQuestion >= $row['total_question']) {
               $this->update_status_to_created($row['online_exam_id']);
            }
         } else {

            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $current_datetime = date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')));
            $exam_datetime = '';

            $sub_query = "SELECT online_exam_datetime, online_exam_duration FROM online_exam_table WHERE online_exam_id = '" . $row['online_exam_id'] . "'";
            $this->db->query($sub_query);
            $sub_result = $this->db->resultSet();

            foreach ($sub_result as $sub_row) {
               $exam_datetime = $sub_row['online_exam_datetime'];
               $end_time = date('Y-m-d H:i:s', strtotime($exam_datetime . '+' . $sub_row['online_exam_duration'] . 'minute'));
            }

            if ($end_time < $current_datetime) {
               $this->update_status_to_completed($row["online_exam_id"]);
            } else {
               $this->update_status_to_started($row["online_exam_id"]);
            }
         }
      }
   }

   function update_status_to_created($online_exam_id)
   {
      $query = "UPDATE online_exam_table SET online_exam_status = 'Created' WHERE online_exam_id = '${online_exam_id}'";
      $this->db->query($query);
      $this->db->execute();
   }

   function update_status_to_started($online_exam_id)
   {
      $query = "UPDATE online_exam_table SET online_exam_status = 'Started' WHERE online_exam_id = '$online_exam_id'";
      $this->db->query($query);
      $this->db->execute();
   }

   function update_status_to_completed($online_exam_id)
   {
      $query = "UPDATE online_exam_table SET online_exam_status = 'Completed' WHERE online_exam_id = '$online_exam_id'";
      $this->db->query($query);
      $this->db->execute();
   }

   function getExamId($code)
   {
      $query = "SELECT online_exam_id FROM online_exam_table WHERE online_exam_code = '${code}'";
      $this->db->query($query);
      $result = $this->db->resultSet();

      foreach ($result as $row) {
         $exam_id = $row['online_exam_id'];
      }

      return $exam_id;
   }

   function getExamStatus($exam_id)
   {
      $query = "SELECT online_exam_status FROM online_exam_table 
      WHERE online_exam_id = '" . $exam_id . "'";
      $this->db->query($query);

      $result = $this->db->resultSet();
      foreach ($result as $row) {
         return $row["online_exam_status"];
      }
   }

   function get_user_exam_status($exam_id, $user_id)
   {
      $query = "SELECT attendance_status FROM user_exam_enroll_table 
      WHERE exam_id = '${exam_id}' AND user_id = '${user_id}'";
      $this->db->query($query);
      $result = $this->db->resultSet();
      foreach ($result as $row) {
         return $row["attendance_status"];
      }
   }

   public function pdfResult($exam_id, $user_id)
   {
      $query = "SELECT * FROM question_table INNER JOIN user_exam_question_answer 
      ON user_exam_question_answer.question_id = question_table.question_id 
      WHERE question_table.online_exam_id = '${exam_id}' 
      AND user_exam_question_answer.user_id = '${user_id}'";

      $this->db->query($query);
      $result = $this->db->resultSet();

      $output = '
      <table width="100%" border="1" cellpadding="5" cellspacing="0">
         <h3 align="center">Online exam result</h3
         <tr>
            <th>Question</th>
            <th>Your answer</th>
            <th>Answer</th>
            <th>Result</th>
            <th>Marks</th>
         </tr>
      ';

      $total_mark = 0;

      foreach ($result as $row) {
         $query = "SELECT * FROM option_table WHERE question_id = '" . $row['question_id'] . "'";
         $this->db->query($query);
         $sub_result = $this->db->resultSet();

         $user_answer = '';
         $orignal_answer = '';
         $question_result = '';

         if ($row["marks"] == '0') {
            $question_result = 'Not Attend';
         }

         if ($row["marks"] > '0') {
            $question_result = 'Right';
         }

         if ($row['marks'] < '0') {
            $question_result = 'Wrong';
         }

         $output .= '
            <tr>
               <td>' . $row['question_title'] . '</td>
         ';

         foreach ($sub_result as $sub_row) {
            if ($sub_row['option_number'] == $row['user_answer_option']) {
               $user_answer = $sub_row['option_title'];
            }
            if ($sub_row['option_number'] == $row['answer_option']) {
               $orignal_answer = $sub_row['option_title'];
            }
         }

         $output .= '
            <td>' . $user_answer . '</td>
            <td>' . $orignal_answer . '</td>
            <td>' . $question_result . '</td>
            <td>' . $row["marks"] . '</td>
         </tr>
         ';
      }
      // echo $output;
      // die;
      $query = "SELECT SUM(marks) as total_mark FROM user_exam_question_answer 
         WHERE user_id = '${user_id}' AND exam_id = '${exam_id}'";

      $this->db->query($query);
      $marks_result = $this->db->resultSet();

      foreach ($marks_result as $row) {
         $output .= '
            <tr>
               <td colspan="4" align="right">Total Marks</td>
               <td align="right">' . $row["total_mark"] . '</td>
            </tr>
         ';
      }

      $output .= '</table>';

      $fileName = 'Exam Result.pdf';
      $this->db->domPdf($fileName, $output);
   }

   public function enrollmentList($exam_id, $data)
   {
      $output = array();

      $query = "SELECT * FROM user_exam_enroll_table INNER JOIN user_table 
      ON user_exam_enroll_table.user_id = user_table.user_id 
      WHERE user_exam_enroll_table.exam_id = '${exam_id}'";

      if (!empty($data['search'])) {
         $query .= " AND (user_table.user_name LIKE '%${data['search']}%'";
         $query .= " OR user_table.user_gender LIKE '%${data['search']}%'";
         $query .= " OR user_table.user_mobile_no LIKE '%${data['search']}%'";
         $query .= " OR user_table.user_email_verified LIKE '%${data['search']}%'";
         $query .= ")";
      }

      if (!empty($data['order'])) {
         $order_by_column = $data['order']['0']['column'];
         $order_by_dir = $data['order']['0']['dir'];

         $order_by_column_name = $data['columns'][$order_by_column]['name'];
         $query .= " ORDER BY ${order_by_column_name} ${order_by_dir} ";
      } else {
         $query .= " ORDER BY user_table.user_id DESC";
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

      $query = "SELECT * FROM user_exam_enroll_table INNER JOIN user_table 
      ON user_exam_enroll_table.user_id = user_table.user_id 
      WHERE user_exam_enroll_table.exam_id = '${exam_id}'";

      $this->db->query($query);
      $this->db->execute();
      $total_rows = $this->db->rowCount();

      $datatable = array();

      foreach ($result as $row) {

         $sub_array = array();
         $sub_array[] = '<img class="img-thumbnail" src="' . URLROOT . '/upload/' . $row['user_image'] . '" width="75"/>';
         $sub_array[] = $row["user_name"];
         $sub_array[] = $row["user_gender"];
         $sub_array[] = $row["user_mobile_no"];
         $is_email_verified = '';
         $result = '';

         if ($row["user_email_verified"] == 'yes') {
            $is_email_verified = '<label class="badge badge-success">Yes</label>';
         } else {
            $is_email_verified = '<label class="badge badge-danger">No</label>';
         }
         $sub_array[] = $is_email_verified;

         if ($this->getExamStatus($exam_id) == 'Completed') {
            $result = '<a href="' . URLROOT . '/exams/userResult/' . $data['code'] . '/' . $row['user_id'] . '" class="btn btn-info btn-sm" target="_blank">Result</a>';
         }

         $sub_array[] = $result;

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

   public function userExamResult($exam_id, $user_id)
   {
      $data = array();

      $query = "SELECT * FROM question_table INNER JOIN user_exam_question_answer ON 
      user_exam_question_answer.question_id = question_table.question_id 
      WHERE question_table.online_exam_id = '${exam_id}' 
      AND user_exam_question_answer.user_id = '${user_id}'";

      $this->db->query($query);
      $data['result_user_answer'] = $this->db->resultSet();

      foreach ($data['result_user_answer']  as $row) {
         $sub_query = "SELECT * FROM option_table WHERE question_id = '" . $row['question_id'] . "'";
         $this->db->query($sub_query);
         $data['options'] = $this->db->resultSet();
      }

      $marks_result_query = "SELECT SUM(marks) as total_mark FROM user_exam_question_answer 
      WHERE user_id = '" . $user_id . "' AND exam_id = '" . $exam_id . "'";
      $this->db->query($marks_result_query);
      $data['marks_result'] = $this->db->resultSet();

      return $data;
   }

   public function getUserDetail($user_id)
   {
      $data = [];

      $query = "SELECT * FROM user_table WHERE user_id = '${user_id}'";
      $this->db->query($query);
      $result = $this->db->resultSet();

      foreach ($result as $row) {
         $data['user_name'] = $row['user_name'];
         $data['user_email_address'] = $row['user_email_address'];
      }

      return $data;
   }

   public function listUserExamResult($exam_id, $data)
   {
      $output = array();

      $query = "SELECT user_exam_enroll_table.user_id, list_user_answer_table.user_image, 
      list_user_answer_table.user_name, user_exam_enroll_table.attendance_status, 
      list_user_answer_table.total_mark 
      FROM list_user_answer_table 
      INNER JOIN user_exam_enroll_table 
      ON list_user_answer_table.user_id = user_exam_enroll_table.user_id 
      WHERE user_exam_enroll_table.exam_id = '${exam_id}'";

      if (!empty($data['search'])) {
         $query .= " AND (list_user_answer_table.user_name LIKE '%${data['search']}%'";
         $query .= " OR user_exam_enroll_table.attendance_status LIKE '%${data['search']}%'";
         $query .= " OR list_user_answer_table.total_mark LIKE '%${data['search']}%'";
         $query .= ")";
      }

      if (!empty($data['order'])) {
         $order_by_column = $data['order']['0']['column'];
         $order_by_dir = $data['order']['0']['dir'];

         $order_by_column_name = $data['columns'][$order_by_column]['name'];
         $query .= " ORDER BY ${order_by_column_name} ${order_by_dir} ";
      } else {
         $query .= " ORDER BY list_user_answer_table.total_mark DESC";
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

      $query = "SELECT user_exam_enroll_table.user_id, list_user_answer_table.user_image, 
      list_user_answer_table.user_name, user_exam_enroll_table.attendance_status, 
      list_user_answer_table.total_mark 
      FROM list_user_answer_table 
      INNER JOIN user_exam_enroll_table 
      ON list_user_answer_table.user_id = user_exam_enroll_table.user_id 
      WHERE user_exam_enroll_table.exam_id = '${exam_id}' 
      ORDER BY list_user_answer_table.total_mark DESC";

      $this->db->query($query);
      $this->db->execute();
      $total_rows = $this->db->rowCount();

      $datatable = array();

      foreach ($result as $row) {

         $sub_array = array();
         $sub_array[] = '<img class="img-thumbnail" src="'.URLROOT.'/upload/' . $row['user_image'] . '" width="75"/>';
         $sub_array[] = $row["user_name"];
         $sub_array[] = $row['attendance_status'];
         $sub_array[] = $row["total_mark"];

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

   public function pdfListUserExamResult($exam_id)
   {
      $query = "SELECT user_exam_enroll_table.user_id, list_user_answer_table.user_image, 
      list_user_answer_table.user_name, user_exam_enroll_table.attendance_status, 
      list_user_answer_table.total_mark 
      FROM list_user_answer_table 
      INNER JOIN user_exam_enroll_table 
      ON list_user_answer_table.user_id = user_exam_enroll_table.user_id 
      WHERE user_exam_enroll_table.exam_id = '${exam_id}' 
      ORDER BY list_user_answer_table.total_mark DESC";

      $this->db->query($query);
      $result = $this->db->resultSet();

      $output = '
      <h2 align="center">Exam Result</h2><br />
      <table width="100%" border="1" cellpadding="5" cellspacing="0">
         <tr>
            <th>Rank</th>
            <th>Image</th>
            <th>User Name</th>
            <th>Attendance Status</th>
            <th>Marks</th>
		   </tr>
      ';

      $count = 1;

      foreach ($result as $row) {
         $output .= '
         <tr>
            <td>'.$count.'</td>
            <td><img src="'.URLROOT.'/upload/'.$row["user_image"].'" width="75" /></td>
            <td>'.$row["user_name"].'</td>
            <td>'.$row['attendance_status'].'</td>
            <td>'.$row["total_mark"].'</td>
         </tr>
      ';
      
      $count = $count + 1;
      }

      $output .= '</table>';

      $fileName = 'Exam Result.pdf';
      $this->db->domPdf($fileName, $output);
   }
}
