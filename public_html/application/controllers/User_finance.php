<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_finance extends Spectrum_Controller {

    public $sms_sent = 0;
    public $message_delivery = '';
    public $uploaded_personalized = "";

    public function load_view($data = null) {
        //$view = $this->access_control($this->data['controller']) == true?'admin':'403';
        //REDIRED NON ADMINS
        if ($this->isloggedin()) {
            if ($this->session->userdata('dept') != 'user') {
                $this->data['view'] = 'backend/' . $this->session->userdata('dept') . '/dashboard';
                $this->data['viewstatus'] = 'dashboard';
            }
        } else {
            redirect('home/login', 'refresh');
        }
        parent::load_view($this->data);
    }

    public function index($id = null) {
        //$data = array();
        $this->data['view'] = 'backend/user/finance/finance_container';
        $this->data['viewstatus'] = 'finance';

        $this->load_view($this->data);
    }

}
