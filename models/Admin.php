<?php

// use PHPMailer\PHPMailer\PHPMailer;

class Admin
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getDataDashboard()
    {
        $data = [];
        $query = "SELECT online_exam_id FROM online_exam_table";
        $this->db->query($query);
        $this->db->execute();
        $data['total_exam'] = $this->db->rowCount();

        $query = "SELECT question_id FROM question_table";
        $this->db->query($query);
        $this->db->execute();
        $data['total_question'] = $this->db->rowCount();

        $query = "SELECT user_id FROM user_table WHERE user_email_verified = 'yes'";
        $this->db->query($query);
        $this->db->execute();
        $data['total_user'] = $this->db->rowCount();
        
        return $data;
    }

    public function register($data)
    {
        $this->db->query('INSERT INTO admin_table (
                admin_email_address, admin_password, admin_verfication_code,
                admin_type, admin_create_on, email_verified) VALUES (
                :admin_email_address, :admin_password, :admin_verfication_code, 
                :admin_type, :admin_create_on, :email_verified)');

        //Bind values
        $this->db->bind(':admin_email_address', $data['admin_email_address']);
        $this->db->bind(':admin_password', $data['admin_password']);
        $this->db->bind(':admin_verfication_code', $data['admin_verfication_code']);
        $this->db->bind(':admin_type', $data['admin_type']);
        $this->db->bind(':admin_create_on', $data['admin_create_on']);
        $this->db->bind(':email_verified', $data['email_verified']);

        //Execute function

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function findUserByEmail($admin_email_address)
    {
        //Prepared statement
        $this->db->query('SELECT * FROM admin_table WHERE admin_email_address = :admin_email_address');

        //Email param will be binded with the email variable
        $this->db->bind(':admin_email_address', $admin_email_address);
        $this->db->execute();
        $row = $this->db->rowCount();

        //Check if email is already registered
        if ($row > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function findUserByEmaiLogin($admin_email_address)
    {
        $this->db->query('SELECT * FROM admin_table WHERE admin_email_address = :admin_email_address');
        $this->db->bind(':admin_email_address', $admin_email_address);
        $this->db->execute();
        $row = $this->db->rowCount();

        if ($row > 0) {
            $result = $this->db->resultSet();
            return $result;
        } else {
            return false;
        }
    }

    public function send_email($receiver_email, $subject, $body)
    {
        $this->db->sendMail($receiver_email, $subject, $body);
    }

    public function verifyEmail($code)
    {
        $this->db->query("UPDATE admin_table SET email_verified = :email_verified WHERE admin_verfication_code = '${code}'");
        $this->db->bind(':email_verified', 'yes');

        if ($this->db->execute()) {
            return $this->db->rowCount();
        } else {
            return false;
        }
    }

    public function fetchUser($data = [])
    {
        $output = array();
        $query = "SELECT * FROM user_table";

        if (!empty($data['search'])) {
            $query .= " WHERE user_name LIKE '%${data['search']}%'";
            $query .= " OR user_email_address LIKE '%${data['search']}%'";
            $query .= " OR user_gender LIKE '%${data['search']}%'";
            $query .= " OR user_mobile_no LIKE '%${data['search']}%'";
            $query .= " OR user_email_verified LIKE '%${data['search']}%'";
        }

        if (!empty($data['order'])) {
            $data['order_by_column'] = $data['order']['0']['column'];
            $data['order_by_dir'] = $data['order']['0']['dir'];

            $data['order_by_column_name'] = $data['columns'][$data['order_by_column']]['name'];
            $query .= " ORDER BY {$data['order_by_column_name']} {$data['order_by_dir']}";
        } else {
            $query .= " ORDER BY user_id DESC";
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

        $query = "SELECT * FROM user_table";

        $this->db->query($query);
        $this->db->execute();

        $total_rows = $this->db->rowCount();

        $datatable = array();

        foreach ($result as $row) {
            $sub_array = array();
            $sub_array[] = '<img class="img-thumbnail" src="' . URLROOT . '/upload/' . $row['user_image'] . '" width="75"/>';
            $sub_array[] = $row['user_name'];
            $sub_array[] = $row['user_email_address'];
            $sub_array[] = $row['user_gender'];
            $sub_array[] = $row['user_mobile_no'];

            $is_email_verified = '';

            if ($row["user_email_verified"] == 'yes') {
                $is_email_verified = '<label class="badge badge-success">Yes</label>';
            } else {
                $is_email_verified = '<label class="badge badge-danger">No</label>';
            }

            $sub_array[] = $is_email_verified;

            $sub_array[] = '<button type="button" name="view_detail" class="btn btn-primary btn-sm details"
            id="' . $row['user_id'] . '">View Details</button>';

            $sub_array[] = '';
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

    public function userDetail($user_id)
    {
        $output = '';

        $query = "SELECT * FROM user_table WHERE user_id = '${user_id}'";
        $this->db->query($query);

        $result = $this->db->resultSet();

        foreach ($result as $row) {
            $is_email_verified = '';

            if ($row["user_email_verified"] == 'yes') {
                $is_email_verified = '<label class="badge badge-success">Email Verified</label>';
            } else {
                $is_email_verified = '<label class="badge badge-danger">Email Not Verified</label>';
            }

            $output .= '
				<div class="row">
					<div class="col-md-12">
						<div align="center">
							<img src="'.URLROOT.'/upload/' . $row["user_image"] . '" class="img-thumbnail" width="200" />
						</div>
						<br />
						<table class="table table-bordered">
							<tr>
								<th>Name</th>
								<td>' . $row["user_name"] . '</td>
							</tr>
							<tr>
								<th>Gender</th>
								<td>' . $row["user_gender"] . '</td>
							</tr>
							<tr>
								<th>Address</th>
								<td>' . $row["user_address"] . '</td>
							</tr>
							<tr>
								<th>Mobile No.</th>
								<td>' . $row["user_mobile_no"] . '</td>
							</tr>
							<tr>
								<th>Email</th>
								<td>' . $row["user_email_address"] . '</td>
							</tr>
							<tr>
								<th>Email Status</th>
								<td>' . $is_email_verified . '</td>
							</tr>
						</table>
					</div>
				</div>
			';
        }

        return $output;
    }
}
