<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_reports extends Spectrum_Controller {

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

    public function index($id = null) {
        if ($this->isloggedin()) {
            //$data = array();
            $this->data['view'] = 'backend/admin/reports/reports_container';
            $this->data['viewstatus'] = 'reports';

            $this->load_view($this->data);
        } else {

            $this->logout();
        }
    }

    public function payments($id = null) {
        if ($this->isloggedin()) {
            //$data = array();
            $this->data['view'] = 'backend/admin/reports/payments';
            $this->data['viewstatus'] = 'reports';

            $this->load_view($this->data);
        } else {

            $this->logout();
        }
    }

    public function messages() {
        if ($this->isloggedin()) {
            if (isset($_REQUEST['month'])):
                $this->data['month'] = $_REQUEST['month'];
                $this->data['user_id'] = $_REQUEST['user_id'];
            endif;
            $this->data['view'] = 'backend/admin/reports/messages';
            $this->data['viewstatus'] = 'reports';
            $this->load_view($this->data);
        } else {

            $this->logout();
        }
    }

    public function pdf_messages($month,$user_id) {
        if ($this->isloggedin()) {
              ob_end_clean();
            $this->data['month'] = $month;
             $this->data['user_id'] = $user_id;
            $this->data['view'] = 'backend/admin/reports/pdf_messages';
            $this->data['viewstatus'] = 'reports';
            $this->load_view($this->data);
        } else {

            $this->logout();
        }
    }

    public function pdf_payments() {
        if ($this->isloggedin()) {
        ob_end_clean();
            $this->data['view'] = 'backend/admin/reports/pdf_payments';
            $this->data['viewstatus'] = 'reports';
            $this->load_view($this->data);
        } else {

            $this->logout();
        }
    }

}
