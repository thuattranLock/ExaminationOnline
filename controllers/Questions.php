<?php

class Questions extends Controller
{
   protected $questionModel;

   public function __construct()
   {
      $this->questionModel = $this->model('Question');
   }

   public function list($code)
   {
      if ($_SESSION['admin_id']) {
         $this->view('admins/question/list', [
            'code' => $code
         ]);
      } else {
         header('location: ' . URLROOT . '/admins/login');
      }
   }

   public function ajaxAdd()
   {
      $data = [];

      if(isset($_SERVER['REQUEST_METHOD']) == 'POST'){
         $data['online_exam_id'] = $_POST['online_exam_id'];
         $data['question_title'] = $_POST['question_title'];
         $data['answer_option'] = $_POST['answer_option'];

         for($count=1; $count <= 4; $count++){
            $data['option_title_'.$count] = $_POST['option_title_'. $count];
         }

         $question = $this->questionModel->addQuestion($data);

         if($question){
            $output = array(
               'success' => 'Question added.'
            );
         }
         echo json_encode($output);
      }
   }

   public function viewQuestion()
   {
      $data = [];

      if($_SERVER['REQUEST_METHOD'] == 'POST'){
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

         $output = $this->questionModel->fetchQuestion($data);

         echo json_encode($output);
      }
   }

   public function ajaxGetDetail()
   {
      $data = [];

      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         if(isset($_POST['question_id'])){
            $question_id = $_POST['question_id'];

            $data = $this->questionModel->getDetailQuestion($question_id);
         }

         echo json_encode($data);
      }
   }  

   public function edit()
   {
      $data = [];

      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $data['question_title'] = $_POST['question_title'];
         $data['question_id'] = $_POST['question_id'];
         $data['answer_option'] = $_POST['answer_option'];

         for($count=1; $count<=4; $count++){
            $data['option_title_'.$count] = $_POST['option_title_'.$count];
         }

         if($this->questionModel->editQuestion($data)){
            $output = array(
               'success'	=>	'Question Edit'
            );
         }

			echo json_encode($output);
      }
   }

   public function ajaxDelete()
   {
      if(isset($_SERVER['REQUEST_METHOD']) == 'POST'){
         if(isset($_POST['question_id'])){
            $question_id = $_POST['question_id'];
            $question = $this->questionModel->deleteQuestion($question_id);

            if($question){
               $output = array(
                  'success' => 'Question Details has been removed'
               );
            }          
         }
         echo json_encode($output);
      }
   }
}
