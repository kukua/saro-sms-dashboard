<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_payments extends Spectrum_Controller {

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
            $this->data['view'] = 'backend/admin/payments/payment_container';
            $this->data['viewstatus'] = 'payments';

            if (isset($_REQUEST['paymentmode'])) {
                if ($this->mobilebet->access_control('payments', 'edit')) {

                    $this->db->update('pay_padlock', array('lock' => $_REQUEST['paymentmode']), array('id' => 1));
                }
            }
            
            $this->load_view($this->data);
            
        } else {

            $this->logout();
        }
    }

}
