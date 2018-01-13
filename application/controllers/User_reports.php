<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_reports extends Spectrum_Controller {

    public $sms_sent = 0;
    public $message_delivery = '';
    public $uploaded_personalized = "";

    public function load_view($data = null) {
        //$view = $this->access_control($this->data['controller']) == true?'user':'403';
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
        if ($this->isloggedin()) {
            //$data = array();
            $this->data['view'] = 'backend/user/reports/reports_container';
            $this->data['viewstatus'] = 'reports';

            $this->data['page_obj'] = ['title' => 'My Reports', 'icon' => 'fa-file'];
            $this->data['breadcrumbs'][] = ['url' => 'user_reports/index', 'title' => 'My Reports'];

            $this->load_view($this->data);
        } else {

            $this->logout();
        }
    }

    public function payments($id = null) {
        if ($this->isloggedin()) {
            //$data = array();
            $this->data['view'] = 'backend/user/reports/payments';
            $this->data['viewstatus'] = 'reports';
            
            $this->data['page_obj'] = ['title' => 'Payments', 'icon' => 'fa-credit-card'];
            $this->data['breadcrumbs'][] = ['url' => 'user_reports/index', 'title' => 'My Reports'];
            $this->data['breadcrumbs'][] = ['url' => 'user_reports/payments', 'title' => 'Payments'];
            

            $this->load_view($this->data);
        } else {

            $this->logout();
        }
    }

    public function messages() {
        if ($this->isloggedin()) {

            $this->data['view'] = 'backend/user/reports/messages';
            $this->data['viewstatus'] = 'reports';
            
            $this->data['page_obj'] = ['title' => 'Messages', 'icon' => 'fa-comments-o'];
            $this->data['breadcrumbs'][] = ['url' => 'user_reports/index', 'title' => 'My Reports'];
            $this->data['breadcrumbs'][] = ['url' => 'user_reports/messages', 'title' => 'Messages'];
            
            $this->load_view($this->data);
        } else {

            $this->logout();
        }
    }

    public function pdf_messages() {
        if ($this->isloggedin()) {
            ob_end_clean();
            $this->data['view'] = 'backend/user/reports/pdf_messages';
            $this->data['viewstatus'] = 'reports';
            $this->load_view($this->data);
        } else {

            $this->logout();
        }
    }

    public function pdf_payments() {
        if ($this->isloggedin()) {
ob_end_clean();
            $this->data['view'] = 'backend/user/reports/pdf_payments';
            $this->data['viewstatus'] = 'reports';
            $this->load_view($this->data);
        } else {

            $this->logout();
        }
    }

}
