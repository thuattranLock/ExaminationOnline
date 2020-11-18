<?php

use Sabberworm\CSS\Value\URL;

class Exams extends Controller
{
   protected $exam;

   public function __construct()
   {
      $this->exam = $this->model('Exam');
   }

   public function list()
   {
      if ($_SESSION['admin_id']) {
         $this->view('admins/exam/list');
      } else {
         header('location: ' . URLROOT . '/admins/login');
      }
   }

   public function ajaxFetchData()
   {
      $output = [];

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $data['admin_id'] = $_SESSION['admin_id'];
         
         if(isset($_POST['columns'])){
            $data['columns'] = $_POST['columns'];
         }
         if (isset($_POST['search']['value'])) {
            $data['search_value'] = $_POST['search']['value'];
         }
         if (isset($_POST['order'])) {
            $data['order'] = $_POST['order'];
         }
         if (isset($_POST['length'])) {
            $data['start'] = $_POST['start'];
            $data['length'] = $_POST['length'];
         }
         if (isset($_POST['draw'])) {
            $data['draw'] = $_POST['draw'];
         }

         $output = $this->exam->queryExam($data);
         echo json_encode($output);
      }
   }

   public function addExam()
   {
      $data = [];
      $current_datetime = date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')));

      if ($_SERVER['REQUEST_METHOD'] == "POST") {
         $data['admin_id'] = $_SESSION['admin_id'];
         $data['online_exam_title'] = $this->exam->clean_data($_POST['online_exam_title']);
         $data['online_exam_datetime'] = $_POST['online_exam_datetime'] . ':00';
         $data['online_exam_duration'] = $_POST['online_exam_duration'];
         $data['total_question'] = $_POST['total_question'];
         $data['marks_per_right_answer'] = $_POST['marks_per_right_answer'];
         $data['marks_per_wrong_answer'] = $_POST['marks_per_wrong_answer'];
         $data['online_exam_created_on'] = $current_datetime;
         $data['online_exam_code'] = md5(rand());
         $data['online_exam_status'] =   'Pending';

         if ($this->exam->addQueryExam($data)) {
            $output = [
               'success' => 'New Exam Details Added'
            ];

            echo json_encode($output);
         }
      }
   }

   public function ajaxEdit($exam_id)
   {
      if(isset($exam_id)) {
         $output = $this->exam->fetchExamDetails($exam_id);
      }

      echo json_encode($output);
   }

   public function ajaxUpdate($exam_id)
   {  
      $data = [];
      if(isset($_SERVER['REQUEST_METHOD']) == 'POST'){
         $data['online_exam_title']	= $_POST['online_exam_title'];
         $data['online_exam_datetime'] = $_POST['online_exam_datetime'];
         $data['online_exam_duration']	= $_POST['online_exam_duration'];
         $data['total_question']	= $_POST['total_question'];
         $data['marks_per_right_answer'] = $_POST['marks_per_right_answer'];
         $data['marks_per_wrong_answer'] = $_POST['marks_per_wrong_answer'];
         $data['online_exam_id']	= $_POST['online_exam_id'];

         if(isset($exam_id)){
            if ($this->exam->updateExam($exam_id, $data)) {
               $output = array(
                  'success'	=>	'Exam Details has been updated'
               );
            }
         }

         echo json_encode($output);
      }  
   }

   public function ajaxDelete($exam_id)
   {
      if(isset($_SERVER['REQUEST_METHOD']) == 'POST'){
         if(isset($exam_id)){
            $exam = $this->exam->deleteExam($exam_id);

            if($exam){
               $output = array(
                  'success' => 'Exam Details has been removed'
               );
            }
         }

         echo json_encode($output);
      }
   }

   public function ajaxExamStatusCreated()
   {
      $this->exam->checkStatusIsLimit();
   }

   public function pdfResult($code = null)
   {  
      if(isset($code)){
         $exam_id = $this->exam->getExamId($code);
         $user_id = $_SESSION['user_id'];

         $this->exam->pdfResult($exam_id, $user_id);
      }
   }

   public function enrollmentList($code = null)
   {
      if(!isset($_SESSION['admin_id'])){
         $this->redirect(URLROOT . '/admins/login');
      }else{
         if(isset($code)){
            $this->view('admins/exam/enrollList', ['code' => $code]);
         }   
      }
   }

   public function ajaxEnrollmentList()
   {
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $code = $_POST['code'];
         $exam_id = $this->exam->getExamId($code);

         if(isset($_POST['search']['value'])){
            $data['search'] = $_POST['search']['value'];
         }
         if(isset($_POST["order"])){
            $data['order'] = $_POST['order'];
         }
         if(isset($_POST['code'])){
            $data['code'] = $_POST['code'];
         }
         $data['columns'] = $_POST['columns'];
         $data['length'] = $_POST['length'];
         $data['start'] = $_POST['start'];
         $data['draw'] = $_POST['draw'];

         $data['code'] = $code;

         $output = $this->exam->enrollmentList($exam_id, $data);

         echo json_encode($output);
      }
   }

   public function userResult($code, $user_id)
   {
      if(!isset($_SESSION['admin_id'])){
         $this->redirect(URLROOT . '/admins/login');
      }else{
         if(isset($code) && isset($user_id)){
            $exam_id = $this->exam->getExamId($code);
            $data = $this->exam->userExamResult($exam_id, $user_id);
            $user = $this->exam->getUserDetail($user_id);
            $this->view('admins/exam/result', ['data' => $data, 'user' => $user]);
         }
      }
   }

   public function listUserExamResult($code)
   {
      if(!isset($_SESSION['admin_id'])){
         $this->redirect(URLROOT . '/admins/login');
      }else{
         if(isset($code)){
            $this->view('admins/exam/listUserExamResult', ['code' => $code]);
         }
      }
   }

   public function ajaxListUserExamResult()
   {
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $code = $_POST['code'];
         $exam_id = $this->exam->getExamId($code);

         if(isset($_POST['search']['value'])){
            $data['search'] = $_POST['search']['value'];
         }
         if(isset($_POST["order"])){
            $data['order'] = $_POST['order'];
         }
         if(isset($_POST['code'])){
            $data['code'] = $_POST['code'];
         }
         $data['columns'] = $_POST['columns'];
         $data['length'] = $_POST['length'];
         $data['start'] = $_POST['start'];
         $data['draw'] = $_POST['draw'];

         $data['code'] = $code;

         $output = $this->exam->listUserExamResult($exam_id, $data);

         echo json_encode($output);
      }
   }

   public function pdfListUserExamResult($code = null)
   {  
      if(isset($code)){
         $exam_id = $this->exam->getExamId($code);

         $this->exam->pdfListUserExamResult($exam_id);
      }
   }

   public function redirect($url)
   {
      header("location:" . $url . "");
      exit;
   }
}
