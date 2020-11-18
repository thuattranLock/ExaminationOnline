<?php

class Question
{
   protected $db;

   public function __construct()
   {
      $this->db = new Database;
   }


   function clean_data($data)
   {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
   }

   public function addQuestion($data)
   {
      $query = "INSERT INTO question_table (online_exam_id, question_title, answer_option) 
      VALUES (:online_exam_id, :question_title, :answer_option)";
      $this->db->query($query);
      $this->db->bind(':online_exam_id', $data['online_exam_id']);
      $this->db->bind(':question_title', $this->clean_data($data['question_title']));
      $this->db->bind(':answer_option',	$data['answer_option']);

      $this->db->execute();
    
      $question_id = $this->db->lastInsertId();

      $output = [];

      for($count = 1; $count <= 4; $count++)
      {
         $query = "INSERT INTO option_table (question_id, option_number, option_title) 
         VALUES (:question_id, :option_number, :option_title)";
         $this->db->query($query);

         $this->db->bind(':question_id', $question_id);
         $this->db->bind(':option_number', $count);
         $this->db->bind(':option_title',	$this->clean_data($data['option_title_' . $count]));
         $this->db->execute();
         $output[] = $this->db->lastInsertId();
      }

      $question_limit = $this->get_exam_question_limit($data['online_exam_id']);
      $question_total = $this->get_exam_total_question($data['online_exam_id']);
      
      if($question_total >= $question_limit){
         $this->update_status_to_created($data['online_exam_id']);
      }

      if(!empty($output[0]) && !empty($output[1]) && !empty($output[2]) && !empty($output[3])){

         return true;
      }
       
      return false;
   }

   function update_status_to_created($exam_id)
   {
      $query = "UPDATE online_exam_table SET online_exam_status = 'Created' WHERE online_exam_id = '${exam_id}'";
      $this->db->query($query);
      $this->db->execute();
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

   public function fetchQuestion(array $data = [])
   {
      $output = [];
      $exam_id = '';

      if(!empty($data['code'])){
         $exam_id = $this->getExamId($data['code']); 
      }
      $query = "SELECT * FROM question_table WHERE online_exam_id = '${exam_id}' ";

      if(!empty($data['search'])){
         $query .= "AND (question_title LIKE %${data['search']}%";
         $query .= ")";
      }
   
      if(!empty($data['order'])){
         $order_by_column = $data['order']['0']['column'];
         $order_by_dir = $data['order']['0']['dir'];

         $order_by_column_name = $data['columns'][$order_by_column]['name'];
         $query .= " ORDER BY ${order_by_column_name} ${order_by_dir} ";
      }else{
         $query .= "ORDER BY question_id ASC ";
      }

      $extra_query = '';
      
      if($data['length'] != -1){
         $extra_query .= "LIMIT {$data['start']} , {$data['length']}";
      }

      $query .= $extra_query;
      $this->db->query($query);
      $this->db->execute();
      $filtered_rows = $this->db->rowCount();

      $result = $this->db->resultSet();

      $query = "SELECT * FROM question_table WHERE online_exam_id = '${exam_id}' ";
      $this->db->query($query);
      $this->db->execute();
      $total_rows = $this->db->rowCount();

      $datatable = array();
      
      foreach($result as $row){
         $sub_array = array();
         $sub_array[] = $row['question_title'];
         $sub_array[] = 'Option ' . $row['answer_option'];

         $edit_button = '';
         $delete_button = '';
         
         if($this->is_exam_is_not_started($exam_id)){
            $edit_button = '<button type="button" name="edit" class="btn btn-primary btn-sm edit" id="'.$row['question_id'].'">Edit</button>';
            $delete_button = '<button type="button" name="delete" class="btn btn-danger btn-sm delete" id="'.$row['question_id'].'">Delete</button>';
         }
         $sub_array[] = $edit_button . ' ' . $delete_button;
			$datatable[] = $sub_array;
      }  

      $output = array(
         "draw"		=>	intval($data["draw"]),
         "recordsTotal"	=>	$total_rows,
         "recordsFiltered"	=>	$filtered_rows,
         "data"		=>	$datatable
      );

      return $output;
   }

   public function getExamId($exam_code)
   {
      $query = "SELECT online_exam_id FROM online_exam_table WHERE online_exam_code = '${exam_code}'";

      $this->db->query($query);
      $this->db->execute();
      $result = $this->db->resultSet();
  
      foreach($result as $row){
         return $row['online_exam_id'];
      }
   }

   public function is_exam_is_not_started($online_exam_id)
   {
      $current_datetime = date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')));

      $exam_datetime = '';

      $query = "SELECT online_exam_datetime FROM online_exam_table WHERE online_exam_id = ${online_exam_id}";
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

   public function getDetailQuestion($question_id)
   {
      $query = "SELECT * FROM question_table WHERE question_id = '${question_id}'";
      $this->db->query($query);
      $this->db->execute();

      $result = $this->db->resultSet();
      $output = [];
    
      foreach($result as $row)
      {
         $output['question_title'] = html_entity_decode($row['question_title']);

         $output['answer_option'] = $row['answer_option'];

         for($count = 1; $count <= 4; $count++)
         {
            $query = "SELECT option_title FROM option_table WHERE question_id = '${question_id}' 
            AND option_number = '${count}'";
            $this->db->query($query);
            $this->db->execute();
            $sub_result = $this->db->resultSet();

            foreach($sub_result as $sub_row)
            {
               $output["option_title_" . $count] = html_entity_decode($sub_row["option_title"]);
            }
         }
      }

      return $output;
   }

   public function editQuestion($data = [])
   {
      $query = "UPDATE question_table SET question_title = :question_title, answer_option = :answer_option 
      WHERE question_id = :question_id";
      $this->db->query($query);

      $this->db->bind(':question_title', $data['question_title']);
      $this->db->bind(':answer_option', $this->clean_data($data['answer_option']));
      $this->db->bind(':question_id',	$data['question_id']);

      $this->db->execute();

      for($count = 1; $count <= 4; $count++){
         $query = "UPDATE option_table SET option_title = :option_title WHERE question_id = :question_id 
         AND option_number = :option_number";
         $this->db->query($query);

         $this->db->bind(':question_id', $data['question_id']);
         $this->db->bind(':option_number', $count);
         $this->db->bind(':option_title',	$data['option_title_'.$count]);
         
         $this->db->execute();
      }

      return true;
   }

   public function deleteQuestion($question_id)
   {
      $query = "DELETE FROM question_table WHERE question_id = :question_id";

      $this->db->query($query);

      $this->db->bind(':question_id', $question_id);

      if($this->db->execute()){
         
         return true;
      }

      return false;
   }
}