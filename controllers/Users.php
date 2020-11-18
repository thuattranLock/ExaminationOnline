<?php 

class Users extends Controller
{
   protected $userModel;

   public function __construct()
   {
      $this->userModel = $this->model('User');
   }

   public function login()
   {
      $this->view('login');
   }

   public function register()
   {
      $this->view('register');
   }

   public function index()
   {
      if (isset($_SESSION['user_id'])) {
         $output = "";
         $user_id = $_SESSION['user_id'];
         $userProfiles = $this->userModel->userDetails($user_id);

         $_SESSION['userProfiles'] = $userProfiles;
        
         $result = $this->userModel->fillExamList();

         foreach($result as $row){
            if($row['online_exam_status'] == 'Created' || $row['online_exam_status'] == 'Started'){
               $output .= '<option value="'.$row["online_exam_id"].'">'.$row["online_exam_title"].'</option>';
            }else{
               $output = "";
            }
         }

         $this->view('home', ['output' => $output]);
      } else {
         $this->redirect(URLROOT . '/users/login');
      }
   }

   public function ajaxCheckEmail()
   {
      if($_SERVER['REQUEST_METHOD'] == "POST"){
         if($_POST['user_email_address']){
            $mail = trim($_POST['user_email_address']);
            $mailCheck = $this->userModel->checkMail($mail);
            if($mailCheck){
               echo 'true';
            }else{
               echo 'false';
            }
         }
      }
   }

   public function ajaxRegister()
   {
      $data = [];
      if($_SERVER['REQUEST_METHOD'] == "POST"){
         $user_verfication_code = md5(rand());

			$receiver_email = $_POST['user_email_address'];

			$filedata = $_FILES['user_avatar'];
         
         $data = [
            'receiver_email' => $receiver_email,
            'user_password' => password_hash($_POST['user_password'], PASSWORD_DEFAULT),
            'user_verfication_code'=>	$user_verfication_code,
				'user_name'	=>	$_POST['user_name'],
            'user_gender' =>	$_POST['user_gender'],
				'user_address'	=>	$_POST['user_address'],
				'user_phone' =>	$_POST['user_phone'],
				'filedata' =>	$filedata,
         ];

         $this->userModel->register($data);

         $output = array(
            'success'		=>	true,
            'message' => 'Check your email'
			);

			echo json_encode($output);
      }
   }


   public function verifyEmail($type, $code)
   {
      if (isset($type) && isset($code)) {
         if($type == 'user'){
            $user = $this->userModel->verifyEmail($code);

            if ($user) {
   
               $this->redirect(URLROOT . '/users/login?verified=success');
            }

         }
      }
   }

   public function ajaxLogin()
   {
      $data = [];

      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $data['user_email_address'] = $_POST['user_email_address'];
         $data['user_password'] = $_POST['user_password'];

         $user = $this->userModel->login($data);

         echo json_encode($user);
      }
   }

   public function editProfile()
   {
      if(isset($_SESSION['user_id'])){
         $this->view('profile',[
            'userProfiles' => $_SESSION['userProfiles'],
         ]);
      }else{
         $this->redirect(URLROOT . '/users/login');
      }
   }

   public function ajaxUpdateProfile()
   {
      $data = [];

      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $data['user_name'] = $_POST['name'];
         $data['user_gender'] = $_POST['gender'];
         $data['user_phone'] = $_POST['phone'];
         $data['user_address'] = $_POST['address'];
         $data['user_image'] = $_POST['hidden_user_image'];
         $data['filedata'] = $_FILES['image'];
         $data['user_id'] = $_SESSION['user_id'];

         // $this->die($data['image']['name']);

         $checkUser = $this->userModel->updateProfile($data);

         if($checkUser){
            $output = array(
               'success' => true,
               'message' => 'Updated success!'
            );

            echo json_encode($output);
         }
      }
   }

   public function changePassword()
   {
      if(isset($_SESSION['user_id'])){
         $this->view('changePassword');
      }else{
         $this->redirect(URLROOT.'/users/login');
      }
   }

   public function ajaxChangePass()
   {
      $data = [];
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $data['user_password'] = $_POST['user_password'];
         $data['user_id'] = $_SESSION['user_id'];

         $this->userModel->updatePassword($data);

         session_destroy();

			$output = array(
				'success'		=>	'Password has been change'
			);

			echo json_encode($output);
      }
   }

   public function ajaxFetchExam()
   {
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $exam_id = $_POST['exam_id'];
         $user_id = $_SESSION['user_id'];
         
         $result = $this->userModel->fetchExam($exam_id, $user_id);

         echo $result;
      }
   }

   public function ajaxEnrollExam()
   {
      if($_SERVER['REQUEST_METHOD'] == "POST"){
         
         $user_id = $_SESSION['user_id'];
         $exam_id = $_POST['exam_id'];

         return $this->userModel->enrollExam($exam_id, $user_id);
      }
   }

   public function enrollExam()
   {
      if(isset($_SESSION['user_id'])){
         $this->view('enrollExam');
      }else{
         $this->redirect(URLROOT.'/users/login');
      }
   }

   public function ajaxFetchEnrollExam()
   {
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
         $data['user_id'] = $_SESSION['user_id'];

         $output = $this->userModel->fetchEnrollExam($data);

         echo json_encode($output);
      }
      
   }

   public function viewExam($code = null)
   {  
      if(!isset($_SESSION['user_id'])){
         $this->redirect(URLROOT . '/users/login');
      }else{
         if(isset($code)){
            $user_id = $_SESSION['user_id'];
            $exam_id = $this->userModel->getExamId($code);
            $data = $this->userModel->getExamDetail($exam_id);

            if($data['exam_status'] == 'Started'){
               $this->userModel->set_attendance_status_to_present($user_id, $exam_id);

               $this->view('viewExam', [
                  'data' => $data,
                  'exam_id' => $exam_id
               ]);
            }

            if($data['exam_status'] == 'Completed'){
               $data = $this->userModel->get_data_result_exam($user_id, $exam_id);
               
               $this->view('viewExamResult', ['data' => $data, 'code' => $code]);
            }
         }else{
            $this->redirect( URLROOT.'/users/enrollExam');
         }
      }
   }

   public function ajaxLoadQuestion()  
   {
      if($_SERVER['REQUEST_METHOD'] == 'POST'){

         $exam_id = $_POST['exam_id'];
         $questions_id = $_POST['question_id'];
         $user_id = $_SESSION['user_id'];
         $output = $this->userModel->loadQuestion($exam_id, $questions_id, $user_id);
         
         echo $output;
      }
   }

   public function ajaxQuestionNavigation()
   {
      if($_SERVER['REQUEST_METHOD'] == "POST"){
         $exam_id = $_POST['exam_id'];

         $output = $this->userModel->questionNavigation($exam_id);

         echo $output;
      }
   }

   public function ajaxAswer()
   {
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $data['exam_id'] = $_POST['exam_id'];
         $data['question_id'] = $_POST['question_id'];
         $data['answer_option'] = $_POST['answer_option'];
         $data['user_id'] = $_SESSION['user_id'];

         $this->userModel->answer($data);
      }
   }

   public function ajaxSubmitExam()
   {
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $exam_id = $_POST['exam_id'];

         if($this->userModel->updateStatusExamToCompleted($exam_id)){
            $output = array(
               'success' => true,
               'message' => "Submit success!"
            );

            echo json_encode($output);
         }
      }
   }

   public function redirect($url)
   {
      header("location:" . $url . "");
      exit;
   }

   public function die($data = [])
   {
      echo '<pre>';
      print_r($data);
      die;
   }

   public function logout()
   {
      session_destroy();
      unset($_SESSION['user_id']);
      unset($_SESSION['user_email_address']);
      $this->redirect( URLROOT . '/users/login');
   }
}
