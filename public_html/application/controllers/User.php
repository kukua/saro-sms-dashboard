<?php

error_reporting(E_ALL);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends Spectrum_Controller {

    public function load_view($data = null) {
        //$view = $this->access_control($this->data['controller']) == true?'user':'403';
        //REDIRED NON ADMINS
        if ($this->session->userdata('dept') != 'user') {
            $this->data['view'] = 'backend/' . $this->session->userdata('dept') . '/dashboard';
            $this->data['viewstatus'] = 'dashboard';
        }
        parent::load_view($this->data);
    }

    public function downloads($method = null) {
        if ($this->isloggedin()) {

            $this->data['view'] = 'downloads';
            $this->data['sublink'] = 'Manage_account_settings';
            $this->data['details'] = 'Token Search results';

            $this->load_view($this->data);
        } else {

            $this->logout();
        }
    }

     public function save_email_settings() {
        $this->data['view'] = "backend/user/my_settings";
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
    
    
    public function edit_account_single($id) {

        $this->data['view'] = "backend/user/my_settings";
        $this->data['viewstatus'] = 'dashboard';
        $this->data['id'] = $id / date('Y');



        $email = $_REQUEST['email_edit'];

        $fullname = $_REQUEST['fullname_edit'];
        $mobile = $_REQUEST['mobile_edit'];
        $account_type = trim($_REQUEST['accounttype_edit']);

        $id = $_REQUEST['id'];
        $values = array(
            'fullname' => $fullname,
            'email' => $email,
            'mobile' => $mobile,
        );
        $this->db->update('users', $values, array('id' => $id));
        $this->data['flash'] = ["message" => "Account editted successfully, Thank you!", "class" => "success"];

        $this->load_view($this->data);
    }

    public function my_settings($id = null) {
        $this->data['id'] = $this->session->userdata('id');
        $this->data['view'] = 'backend/user/my_settings';
        $this->data['viewstatus'] = 'dashboard';

        $this->load_view($this->data);
    }

    public function top_up() {

        $this->data['id'] = $this->session->userdata('id');
        $this->data['view'] = 'backend/user/top_up';
        $this->data['viewstatus'] = 'payments';
        $this->data['amount'] = $_REQUEST['amount'];
        $this->data['description'] = "Spectrum Credit Purchase";
        $this->data['guid'] = $this->getGUID();
        $this->data['email'] = $this->session->userdata('email');
        $this->data['phone'] = $this->session->userdata('mobile');
        
        $this->session->set_userdata(array('pesapalcategory' => $_REQUEST['pesapalcategory']));
        $this->session->set_userdata(array('pesapalamount' => $_REQUEST['amount']));

        $names = split(" ", $this->session->userdata('fullname'));

        $this->data['firstname'] = !empty($names) ? $names[0] : $this->session->userdata('fullname');
        $this->data['lastname'] = !empty($names) ? $names[1] : $this->session->userdata('fullname');

        $this->data['page_obj'] = ['title' => 'Top up', 'icon' => 'fa-money'];
        $this->data['breadcrumbs'][] = ['url' => 'user/top_up', 'title' => 'Top up'];

        $this->load_view($this->data);
    }

    public function sms_api() {
        
        //$this->data = array();
        $this->data['view'] = 'backend/sms_api';
        $this->data['viewstatus'] = 'sms_api';

        $this->data['page_obj'] = ['title' => 'SMS API', 'icon' => 'fa-code'];
        $this->data['breadcrumbs'][] = ['url' => 'user/sms_api', 'title' => 'SMS API'];

        //COMPATIBILITY
        $oauth_client = $this->db->select('*')->from('oauth_clients')->where(array('user_id' => $this->session->userdata('id')))->get()->row();
        if (empty($oauth_client)):
            $data = array(
                'datetime' => date('Y-m-d H:i:s'),
                'client_id' =>  sha1($this->getGUID()),
                'user_id' => $this->session->userdata('id'),
                'client_secret' => sha1($this->getGUID()),
                'redirect_uri' => "http://fake/");

            $this->db->insert('oauth_clients', $data);
        endif;

        $this->load_view($this->data);
    }
    public function email_api() {
        
        //$this->data = array();
        $this->data['view'] = 'backend/email_api';
        $this->data['viewstatus'] = 'email_api';

        $this->data['page_obj'] = ['title' => 'EMAIL API', 'icon' => 'fa-code'];
        $this->data['breadcrumbs'][] = ['url' => 'user/email_api', 'title' => 'EMAIL API'];

        //COMPATIBILITY
        $oauth_client = $this->db->select('*')->from('oauth_clients')->where(array('user_id' => $this->session->userdata('id')))->get()->row();
        if (empty($oauth_client)):
            $data = array(
                'datetime' => date('Y-m-d H:i:s'),
                'client_id' =>  sha1($this->getGUID()),
                'user_id' => $this->session->userdata('id'),
                'client_secret' => sha1($this->getGUID()),
                'redirect_uri' => "http://fake/");

            $this->db->insert('oauth_clients', $data);
        endif;

        $this->load_view($this->data);
    }

    public function index($id = null) {

        if ($this->isloggedin()) {
            //$this->data = array();
            $this->data['controller'] = 'dashboard';
            $this->data['view'] = 'backend/user/dashboard';
            $this->data['viewstatus'] = 'dashboard';
            $this->data['sublink'] = 'dashboard';
            $this->data['page_obj'] = ['title' => 'Dashboard', 'icon' => 'fa-dashboard'];
            $this->data['details'] = 'This is a broad view of your account performance';


            $start = strlen($id) == 0 ? 0 : ($id * 100 - 100);

            $this->data['start'] = $start;

            $id = strlen($id) == 0 ? 0 : $id;

            $this->data['page'] = $id;


            $this->load_view($this->data);
        } else {

            $this->logout();
        }
    }

}
