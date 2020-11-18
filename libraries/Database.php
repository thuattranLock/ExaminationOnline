<?php

use PHPMailer\PHPMailer\PHPMailer;
use Dompdf\Dompdf;

class Database
{
    private $dbHost = DB_HOST;
    private $dbUser = DB_USER;
    private $dbPass = DB_PASS;
    private $dbName = DB_NAME;

    private $statement;
    private $dbHandler;
    private $error;

    public function __construct()
    {
        $conn = 'mysql:host=' . $this->dbHost . ';dbname=' . $this->dbName;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        try {
            $this->dbHandler = new PDO($conn, $this->dbUser, $this->dbPass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
        session_start();
    }
    //Allows us to write queries
    public function query($sql)
    {
        $this->statement = $this->dbHandler->prepare($sql);
    }

    //Bind values
    public function bind($parameter, $value, $type = null)
    {
        switch (is_null($type)) {
            case is_int($value):
                $type = PDO::PARAM_INT;
                break;
            case is_bool($value):
                $type = PDO::PARAM_BOOL;
                break;
            case is_null($value):
                $type = PDO::PARAM_NULL;
                break;
            default:
                $type = PDO::PARAM_STR;
        }
        $this->statement->bindValue($parameter, $value, $type);
    }

    //Execute the prepared statement
    public function execute()
    {
        return $this->statement->execute();
    }

    //Return an array
    public function resultSet()
    {
        $this->execute();
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    //Return a specific row as an object
    public function single()
    {
        $this->execute();
        return $this->statement->fetch(PDO::FETCH_ASSOC);
    }

    //Get's the row count
    public function rowCount()
    {
        return $this->statement->rowCount();
    }

    public function lastInsertId()
    {
        return $this->dbHandler->lastInsertId();
    }

    public function sendMail($receiver_email, $subject, $body)
    {
        require 'plugins/PHPMailer/src/PHPMailer.php';
        require 'plugins/PHPMailer/src/SMTP.php';
        require 'plugins/PHPMailer/src/Exception.php';

        $mail = new PHPMailer;

        $mail->IsSMTP();

        $mail->Host = 'smtp.gmail.com';

        $mail->Port = '587';

        $mail->SMTPAuth = true;

        $mail->Username = 'kungfumasteryi1810@gmail.com';

        $mail->Password = 'thuat1908273645';

        // $mail->SMTPSecure = '';
        $mail->setFrom($receiver_email, 'My Name');
        $mail->AddAddress($receiver_email, '');
        $mail->IsHTML(true);

        $mail->Subject = $subject;

        $mail->Body = $body;

        if ($mail->Send()) {
            return true;
        } else {
            return false;
        }
    }

    public function domPdf($fileName, $output)
    {
        require_once 'plugins/dompdf/autoload.inc.php';

        $dompdf = new Dompdf();

        $dompdf->set_paper('letter', 'landscape');

        $file_name = $fileName;

        $dompdf->loadHtml($output);

        $dompdf->render();

        $dompdf->stream($file_name, array("Attachment" => false));
        exit(0);
    }
}
