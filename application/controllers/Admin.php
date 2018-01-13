<?php
error_reporting(E_ALL);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends Spectrum_Controller {


    public function load_view($data = null) {
        //$view = $this->access_control($this->data['controller']) == true?'admin':'403';
        //REDIRED NON ADMINS
        if ($this->session->userdata('dept') != 'admin') {
            $this->data['view']  ='backend/'.$this->session->userdata('dept').'/dashboard';  
            $this->data['viewstatus'] ='dashboard';
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
  public function edit_account_single($id) {

        $this->data['view'] = "backend/admin/my_settings";
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
        $this->data['view'] = 'backend/admin/my_settings';
        $this->data['viewstatus'] = 'dashboard';

        $this->load_view($this->data);
    }
    public function sms_api() {

        //$this->data = array();
        $this->data['view'] = 'backend/sms_api';
        $this->data['viewstatus'] = 'sms_api';

        $this->data['page_obj'] = ['title' => 'SMS API', 'icon' => 'fa-code'];
        $this->data['breadcrumbs'][] = ['url' => 'admin/sms_api', 'title' => 'SMS API'];

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
        $this->data['breadcrumbs'][] = ['url' => 'admin/email_api', 'title' => 'EMAIL API'];

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
            $this->data['view'] = 'backend/admin/dashboard';
            $this->data['viewstatus'] ='dashboard';       
           
            $this->data['page_obj'] = ['title'=> 'Dashboard','icon'=>'fa-dashboard'];
            
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
