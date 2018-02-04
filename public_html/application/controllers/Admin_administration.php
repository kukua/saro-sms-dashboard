<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_administration extends Spectrum_Controller {

    public $sms_sent = 0;
    public $message_delivery = '';
    public $uploaded_personalized = "";

    public function load_view($data = null) {
        //$view = $this->access_control($this->data['controller']) == true?'admin':'403';
        //REDIRED NON ADMINS
        if ($this->isloggedin()) {
            if ($this->session->userdata('dept') != 'admin') {
                $this->data['view'] = 'backend/' . $this->session->userdata('dept') . '/dashboard';
                $this->data['viewstatus'] = 'dashboard';
            }
        } else {
            redirect('home/login', 'refresh');
        }
        parent::load_view($this->data);
    }

    public function download_users() {
        $header = '#,Fullname,Role,SMS Credits,Email Credits,Email,Mobile,Last Login';
        $data = '';
        $count = 1;
        foreach ($this->db->select('*')->from('users')->order_by('fullname')->get()->result() as $rows) {

            $line = '"' . $count . '"' . "," . '"' . $rows->fullname . '"' . "," . '"' . $rows->dept . '"' . "," . '"' . $rows->credits . '"' . "," .
                    '"' . $rows->email_credits . '"' . "," .
                    '"' . $rows->email . '"' . "," . '"' . $rows->mobile . '"' . "," . '"' . $rows->logintime . '"' . ",";
            ;

            $data .= trim($line) . "\n";

            $count++;
        }


        $data = str_replace("\r", "", $data);

        $file_name = 'Spectrum_users_' . date('Y-m-d-H-i-s');

        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: csv" . date("Y-m-d") . ".csv");
        header("Content-disposition: filename=" . $file_name . ".csv");
        print "$header\n$data";
        exit;
    }

    public function save_networks() {
        $this->data['view'] = "backend/admin/administrator/user_profile";
        $this->data['viewstatus'] = 'administration';
        $this->data['id'] = $_REQUEST['id'] / date('Y');

        $this->db->where(array('userid' => $this->data['id']))->delete('rate_per_network');

        foreach ($_REQUEST as $key => $value) {
            $cost = explode('cost_', $key);

            if (strlen($cost[1]) > 0) {
                if (strlen($_REQUEST['cost_' . $cost[1] . 'cost_' . $cost[2]]) > 0) {
                    $values = array(
                        'userid' => $this->data['id'],
                        'network_id' => $cost[1],
                        'amount' => $value,
                        'prefix' => $cost[2]
                    );

                    $this->db->insert('rate_per_network', $values);
                }
            }
        }

        $this->db->update('users', array('persms_flag' => 0, 'routeid' => 0), array('id' => $this->data['id']));
        $this->data['flash'] = ["message" => "Tariffs Changed to Cost for each network, Thank you!", "class" => "success"];



        $this->load_view($this->data);
    }

    public function save_per_sms() {
        $this->data['view'] = "backend/admin/administrator/user_profile";
        $this->data['viewstatus'] = 'administration';
        $this->data['id'] = $_REQUEST['id'] / date('Y');

        $rate_details = $this->db->select('*')->from('users')->where(array('id' => $this->data['id']))->get()->result();
        $rate_details = $rate_details[0];
        $cost = $rate_details->rate * $rate_details->credits;

        $cost = $cost / $_REQUEST['cost'];
        $this->db->update('users', array('sms_cost' => $_REQUEST['cost'],
            'rate' => $_REQUEST['cost'],
            'persms_flag' => 1), array('id' => $this->data['id']));

        $this->data['flash'] = ["message" => "Tariffs Changed to Per SMS, Thank you!", "class" => "success"];




        $this->load_view($this->data);
    }

    public function save_per_email() {
        $this->data['view'] = "backend/admin/administrator/user_profile";
        $this->data['viewstatus'] = 'administration';
        $this->data['id'] = $_REQUEST['id'] / date('Y');

        $this->db->update('users', array('email_cost' => $_REQUEST['cost']), array('id' => $this->data['id']));
        $this->data['flash'] = ["message" => "Email tariffs Changed, Thank you!", "class" => "success"];

        $this->load_view($this->data);
    }
    
    
    public function save_email_settings() {
        $this->data['view'] = "backend/admin/administrator/user_profile";
        $this->data['viewstatus'] = 'administration';
        $this->data['id'] = $_REQUEST['id'] / date('Y');

        $this->db->update('users', 
                array(
                    'email_server' => $_REQUEST['email_server'],
                    'email_port' => $_REQUEST['email_port'],
                    'email_auth' => $_REQUEST['email_auth'],
                    'email_is_smtp' => $_REQUEST['email_is_smtp'],
                    'email_secure' => $_REQUEST['email_secure'],
                    'email_username' => $_REQUEST['email_username'],
                    'email_password' => $_REQUEST['email_password']
                ), 
                array('id' => $this->data['id']));
        
        $this->data['flash'] = ["message" => "Email Settings Changed, Thank you!", "class" => "success"];

        $this->load_view($this->data);
    }
    

    public function topup_amount($id = null) {

        $this->data['view'] = "backend/admin/administrator/user_profile";
        $this->data['viewstatus'] = 'administration';
        $this->data['id'] = $id / date('Y');

        $user_details = $this->db->select('*')->from('users')->where(array('id' => $this->data['id']))->get()->result();
        $credits = $user_details[0]->credits;

        $email_details = array();
        if ($_REQUEST['category'] == 'sms') {
            $email_details = array('type' => 'Account Top up', 'message' => 'Dear ' . $user_details[0]->fullname . '<br>Your account has been credited with USD ' . number_format($_REQUEST['topupamount']) . ' worthy of messages', 'email' => $user_details[0]->email);

            $this->spectrum->update('users', 'credits', 'credits+' . $_REQUEST['topupamount'], array('id' => $this->data['id']));
        } else {
            $email_details = array('type' => 'Account Top up', 'message' => 'Dear ' . $user_details[0]->fullname . '<br>Your account has been credited with USD ' . number_format($_REQUEST['topupamount']) . ' worthy of emails', 'email' => $user_details[0]->email);

            $this->spectrum->update('users', 'email_credits', 'email_credits+' . $_REQUEST['topupamount'], array('id' => $this->data['id']));
        }


        //$this->spectrum->send_notification($email_details);


        $values = array(
            'sender' => $this->data['id'],
            'created' => date('Y-m-d H:i:s'),
            'status' => 'COMPLETED',
            'transref' => sha1(date('Y-m-d H:i:s') . $this->session->userdata('id') . $this->data['id']),
            'messages' => $_REQUEST['topupamount'],
            'date' => date('Y-m-d'),
            'method' => 'MANUAL',
            'rate' => 0,
            'entrant' => $this->session->userdata('id'),
            'credits_after' => $_REQUEST['topupamount'] + $credits
        );

        $this->db->insert('payments', $values);

        $this->data['flash'] = ["message" => "Account Topped up successfully, Thank you!", "class" => "success"];
        $this->load_view($this->data);
    }

    public function remove_topup_amount($id = null) {

        $this->data['view'] = "backend/admin/administrator/user_profile";
        $this->data['viewstatus'] = 'administration';
        $this->data['id'] = $id / date('Y');

        $user_details = $this->db->select('*')->from('users')->where(array('id' => $this->data['id']))->get()->result();
        $credits = $user_details[0]->credits;

        $email_details = array();
        if ($_REQUEST['category'] == 'sms') {

            $this->spectrum->update('users', 'credits', 'credits-' . $_REQUEST['topupamount'], array('id' => $this->data['id']));
        } else {

            $this->spectrum->update('users', 'email_credits', 'email_credits-' . $_REQUEST['topupamount'], array('id' => $this->data['id']));
        }

        $values = array(
            'sender' => $this->data['id'],
            'created' => date('Y-m-d H:i:s'),
            'status' => 'COMPLETED',
            'transref' => sha1(date('Y-m-d H:i:s') . $this->session->userdata('id') . $this->data['id']),
            'messages' => '-' . $_REQUEST['topupamount'],
            'date' => date('Y-m-d'),
            'method' => 'MANUAL',
            'rate' => 0,
            'entrant' => $this->session->userdata('id'),
            'credits_after' => $_REQUEST['topupamount'] + $credits
        );

        $this->db->insert('payments', $values);

        $this->data['flash'] = ["message" => "Account Topped Corrected, Thank you!", "class" => "success"];
        $this->load_view($this->data);
    }

    public function save_messaging_routes() {

        $this->data['view'] = "backend/admin/administrator/user_profile";
        $this->data['viewstatus'] = 'administration';
        $this->data['id'] = $_REQUEST['id'] / date('Y');

        $set_routes = array();
        foreach ($_REQUEST as $key => $value) {


            $values = explode('check_', $key);

            if (isset($values[1]))
                array_push($set_routes, $values[1]);
        }

        $this->db->where(array('userid' => $this->data['id']))->delete('routes_assigned');

        for ($i = 0; $i < sizeof($set_routes); $i++) {
            $values = array(
                'routeid' => $set_routes[$i],
                'userid' => $this->data['id']
            );
            $this->db->insert('routes_assigned', $values);
        }
        $this->data['flash'] = ["message" => "SMS Routes Changed, Thank you!", "class" => "success"];

        $this->load_view($this->data);
    }

    public function user_account_profile($id = null) {
        $this->data['id'] = $id / date('Y');
        $this->data['view'] = 'backend/admin/administrator/user_profile';
        $this->data['viewstatus'] = 'administration';

        $this->load_view($this->data);
    }

    public function activate($id = null) {
        $this->data['view'] = "backend/admin/administrator/user_profile";
        $this->data['viewstatus'] = 'administration';
        $this->data['id'] = $id / date('Y');

        $this->db->update('users', array('status' => 1), array('id' => $id / date('Y')));

        $this->data['flash'] = ["message" => "Account activated successfully, Thank you!", "class" => "success"];
        $this->load_view($this->data);
    }

    public function deactivate($id = null) {
        $this->data['view'] = "backend/admin/administrator/user_profile";
        $this->data['viewstatus'] = 'administration';
        $this->data['id'] = $id / date('Y');

        $this->db->update('users', array('status' => 0), array('id' => $id / date('Y')));

        $this->data['flash'] = ["message" => "Account deactivated successfully, Thank you!", "class" => "success"];
        $this->load_view($this->data);
    }

    public function delete($id = null) {
        $this->db->where(array('id' => $id / date('Y')))->delete('users');
        $this->data['alert'] = 'alert-info';
        $this->data['message'] = "Account deleted successfully, Thank you!";

        $uploads_dir = FCPATH . "assets" . DS . "backend" . DS . "mosaico" . DS . "uploads" . DS . $this->session->userdata('id') . DS;
        $static_dir = FCPATH . "assets" . DS . "backend" . DS . "mosaico" . DS . "uploads" . DS . "static" . DS . $this->session->userdata('id') . DS;
        $thumbnails_dir = FCPATH . "assets" . DS . "backend" . DS . "mosaico" . DS . "uploads" . DS . "thumbnails" . DS . $this->session->userdata('id') . DS;

        $this->Spectrum_Func->deleteDir($uploads_dir);
        $this->Spectrum_Func->deleteDir($static_dir);
        $this->Spectrum_Func->deleteDir($thumbnails_dir);

        redirect('admin/administration', 'refresh');
    }

    public function edit_roles($id = null) {

        if ($this->access_control('administration', 'edit')) {
            $this->data['view'] = 'edit_roles';
            $this->data['id'] = $id;


            if (isset($_REQUEST['id'])) {
                $this->db->where(array('userid' => $id / date('Y')))->delete('access');

                foreach ($_REQUEST as $k => $v) {
                    $info = explode('controller_', $k);

                    if (isset($info[1])) {

                        $controller = $info[1];

                        $role = $info[2];



                        $values = array('controller' => $controller,
                            'userid' => $id / date('Y'),
                            'datetime' => date('Y-m-d H:i:s'),
                            'role' => $role);

                        $this->db->insert('access', $values);
                    }
                }
            }
        }
    }

    public function delete_user($id = null) {

        $this->data['view'] = 'backend/admin/administrator/administrator_container';
        $this->data['viewstatus'] = 'administration';

        $this->data['flash'] = ["message" => "Account deleted successfully", "class" => "success"];


        $this->db->where('id = "' . $id / date('Y') . '"')->delete('users');

        $this->load_view($this->data);
    }

    public function edit_account($id) {

        $this->data['view'] = "backend/admin/administrator/user_profile";
        $this->data['viewstatus'] = 'administration';
        $this->data['id'] = $_REQUEST['id'] / date('Y');

        $password = $_REQUEST['password_edit'];
        $passwordagain = $_REQUEST['repassword_edit'];

        $email = $_REQUEST['email_edit'];

        $fullname = $_REQUEST['fullname_edit'];
        $mobile = $_REQUEST['mobile_edit'];
        $account_type = trim($_REQUEST['accounttype_edit']);

        if ($password == $passwordagain) {
            $id = $_REQUEST['id'] / date('Y');
            $values = array(
                'fullname' => $fullname,
                'email' => $email,
                'password' => sha1($password),
                'dept' => $account_type,
                'mobile' => $mobile,
            );
            $this->db->update('users', $values, array('id' => $id));
            $this->data['flash'] = ["message" => "Account editted successfully, Thank you!", "class" => "success"];
        } else {

            $this->data['flash'] = ["message" => "Access denied, Passwords didn't match", "class" => "warning"];
        }
        $this->load_view($this->data);
    }

    public function edit_account_single($id) {

        $this->data['view'] = "backend/admin/administrator/user_profile";
        $this->data['viewstatus'] = 'administration';
        $this->data['id'] = $id / date('Y');



        $email = $_REQUEST['email_edit'];

        $fullname = $_REQUEST['fullname_edit'];
        $mobile = $_REQUEST['mobile_edit'];
        $account_type = trim($_REQUEST['accounttype_edit']);

        $id = $_REQUEST['id'];
        $values = array(
            'fullname' => $fullname,
            'email' => $email,
            'dept' => $account_type,
            'mobile' => $mobile,
        );
        $this->db->update('users', $values, array('id' => $id));
        $this->data['flash'] = ["message" => "Account editted successfully, Thank you!", "class" => "success"];

        $this->load_view($this->data);
    }

    public function create_account() {

        $this->data['view'] = 'backend/admin/administrator/administrator_container';
        $this->data['viewstatus'] = 'administration';

        $password = trim($_REQUEST['password']);
        $passwordagain = trim($_REQUEST['repassword']);

        $email = trim($_REQUEST['email']);

        $fullname = trim($_REQUEST['fullname']);
        $account_type = trim($_REQUEST['accounttype']);


        if ($password == $passwordagain) {
            if ($this->db->select('id')->from('users')->where(array('email' => $email))->get()->num_rows() == 0) {

                $values = array(
                    'fullname' => $fullname,
                    'email' => $email,
                    'password' => sha1($password),
                    'dept' => $account_type,
                    'mobile' => trim($_REQUEST['mobile']),
                    'status' => 0,
                    'created' => date('Y-m-d H:i:s'),
                    'owner' => $this->session->userdata('id'));

                $this->db->insert('users', $values);

                $this->data['flash'] = ["message" => "Account editted successfully, Thank you!", "class" => "success"];
            } else {

                $this->data['flash'] = ["message" => "Access denied, email already exists, try using another email address", "class" => "danger"];
            }
        } else {

            $this->data['flash'] = ["message" => "Access denied, Passwords didn't match", "class" => "warning"];
        }
        $this->load_view($this->data);
    }

    public function index($id = null) {
        //$data = array();
        $this->data['view'] = 'backend/admin/administrator/administrator_container';
        $this->data['viewstatus'] = 'administration';

        $this->load_view($this->data);
    }

}
