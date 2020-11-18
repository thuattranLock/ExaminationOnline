<?php
// session_start();

class Admins extends Controller
{
   protected $adminModel;
   
   public function __construct()
   {
      $this->adminModel = $this->model('Admin');
   }

   public function index()
   {
      if(isset($_SESSION['admin_id'])) {
         $data = $this->adminModel->getDataDashboard();
         $this->view('admins/dashboard', ['data' => $data]);
      } else {
         $this->redirect(URLROOT . '/admins/login');
      }
   }

   public function register()
   {
      $this->view('admins/register');
   }

   public function login()
   {
      $this->view('admins/login');
   }

   public function ajaxRegister()
   {
      $data = [];
      $errors = [];

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

         $data['admin_email_address'] = trim(htmlentities($_POST['admin_email_address']));
         $data['admin_password'] = trim(htmlentities($_POST['admin_password']));
         $data['confirm_admin_password'] = trim(htmlentities($_POST['confirm_admin_password']));

         $passwordValidation = "/^(.{0,7}|[^a-z]*|[^\d]*)$/i";

         if (empty($data['admin_email_address'])) {
            $errors['admin_email_address'] = 'Please enter email address.';
         } elseif (!filter_var($data['admin_email_address'], FILTER_VALIDATE_EMAIL)) {
            $errors['admin_email_address'] = 'Please enter the correct format.';
         } else {
            //Check if email exists.
            if ($this->adminModel->findUserByEmail($data['admin_email_address'])) {
               $errors['admin_email_address'] = 'Email is already exist.';
            }
         }

         // Validate password on length, numeric values,
         if (empty($data['admin_password'])) {
            $errors['admin_password'] = 'Please enter password.';
         } elseif (strlen($data['admin_password']) < 6) {
            $errors['admin_password'] = 'Password must be at least 6 characters';
         } elseif (preg_match($passwordValidation, $data['admin_password'])) {
            $errors['admin_password'] = 'Password must be have at least one numeric value.';
         }

         //Validate confirm password
         if (empty($data['confirm_admin_password'])) {
            $errors['confirm_admin_password'] = 'Please enter password.';
         } else {
            if ($data['admin_password'] != $data['confirm_admin_password']) {
               $errors['confirm_admin_password'] = 'Passwords do not match, please try again.';
            }
         }

         if (empty($errors['admin_email_address']) && empty($errors['admin_password']) && empty($errors['confirm_admin_password'])) {
            // Hash password
            $data['admin_type'] = 'sub_master';
            $data['admin_password'] = password_hash($data['admin_password'], PASSWORD_DEFAULT);
            $data['admin_verfication_code'] = md5(rand());
            $data['admin_create_on'] = date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')));
            $data['email_verified'] = 'no';
            //Register user from model function

            if ($this->adminModel->register($data)) {
               $receiver_email = $data['admin_email_address'];
               $subject = 'Online Examination Registration Verification';
               $home_page = URLROOT;
               $controller = 'admins/';
               $action = 'verify_email/';
               $body = '
               <p>Thank you for registering.</p>
               <p>This is a verification eMail, please click the link to verify your eMail address by clicking this 
               <a href="' . $home_page . $controller . $action . 'master/' . $data['admin_verfication_code'] . '" target="_blank"><b>link</b>
               </a>.</p>
               <p>In case if you have any difficulty please eMail us.</p>
               <p>Thank you,</p>
               <p>Online Examination System</p>
               ';

               $this->adminModel->send_email($receiver_email, $subject, $body);

               $data['message'] = 'Please check your email.';

               echo  json_encode(['code' => 200, 'data' => $data]);
            } else {
               die('Something went wrong.');
            }
         } else {
            echo  json_encode(['code' => 404, 'errors' => $errors]);
         }
      }
   }

   public function verify_email($type, $code)
   {
      if (isset($type) && $type == 'master') {
         $admin = $this->adminModel->verifyEmail($code);

         if ($admin) {

            $this->redirect(URLROOT . '/admins/login?verified=success');
         }
      }
   }

   public function ajaxLogin()
   {
      $data = [];
      $errors = [];
      //Check for post
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         //Sanitize post data
         $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

         $data['admin_email_address'] =  trim($_POST['admin_email_address']);
         $data['admin_password'] = trim($_POST['admin_password']);

         if (empty($data['admin_email_address'])) {
            $errors['admin_email_address'] = 'Enter your email address.';
         } elseif (!filter_var($data['admin_email_address'], FILTER_VALIDATE_EMAIL)) {
            $errors['admin_email_address'] = 'Please enter the correct format.';
         }
         if (empty($data['admin_password'])) {
            $errors['admin_password'] = 'Enter your password.';
         }

         if (empty($errors['admin_email_address']) && empty($errors['admin_password'])) {
            $result = $this->adminModel->findUserByEmaiLogin($data['admin_email_address']);
            if ($result) {
               foreach ($result as $row) {
                  if ($row['email_verified'] == 'yes') {
                     if (password_verify($data['admin_password'], $row['admin_password'])) {
                        $this->createAdminSession($row);
                        echo json_encode(['code' => 200, 'data' => $data]);
                        exit;
                     } else {
                        $errors['admin_password'] = 'Wrong password.';
                     }
                  } else {
                     $errors['admin_email_address'] = 'Your Email is not verify, please check your mail.';
                  }
               }
            } else {
               $errors['admin_email_address'] = 'Wrong email address.';
            }
         }
         echo json_encode(['code' => 404, 'errors' => $errors]);
      }
   }

   public function userRegisterData()
   {
      if(isset($_SESSION['admin_id'])){
         $this->view('admins/userRegisterData/index');
      }else{
         $this->redirect(URLROOT . '/admins/login');
      }
   }

   public function ajaxFetchUser()
   {
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

      $output = $this->adminModel->fetchUser($data);

      echo json_encode($output);
   }

   public function ajaxUserDetail()
   {
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $user_id = $_POST['user_id'];

         $result = $this->adminModel->userDetail($user_id);

         echo $result;
      }
   }

   public function redirect($url)
   {
      header("location:" . $url . "");
      exit;
   }

   public function createAdminSession($admin)
   {
      $_SESSION['admin_id'] = $admin['admin_id'];
      $_SESSION['admin_email_address'] = $admin['admin_email_address'];
   }

   public function logout()
   {
      session_destroy();
      unset($_SESSION['admin_id']);
      unset($_SESSION['admin_email_address']);
      $this->redirect( URLROOT . '/admins/login');
   }
}
