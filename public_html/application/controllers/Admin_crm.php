<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_crm extends Spectrum_Controller {

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
            $this->data['view'] = 'backend/admin/crm/crm_container';
            $this->data['viewstatus'] = 'crm';

            $this->load_view($this->data);
        } else {

            $this->logout();
        }
    }

    public function send_emails() {
        $this->data['view'] = 'backend/admin/crm/compose_email';
        $this->data['viewstatus'] = 'crm';

        $this->data['page_obj'] = ['title' => 'Send Email', 'icon' => 'fa-location-arrow'];
        $this->data['breadcrumbs'][] = ['url' => 'admin_emails/emails', 'title' => 'Email Brodacasts'];
        $this->data['breadcrumbs'][] = ['url' => 'admin_emails/send_emails', 'title' => 'Send Email'];

        $this->load_view($this->data);
    }

    public function send_email($method = null, $id = null, $idparent = null) {



        $this->data['view'] = 'backend/admin/emails/email_container';
        $this->data['viewstatus'] = 'crm';
        try {
            $from = $_REQUEST['from'];
            $subject = $_REQUEST['subject'];
            $date = '';

            if (isset($_REQUEST['schedule_datetime'])) {
                if ($_REQUEST['schedule_datetime'] == "") {
                    $date = (new Cake\I18n\Time())->format('Y-m-d H:i:s');
                } else {
                    $date = (new Cake\I18n\Time($_REQUEST['schedule_datetime'] . ' ' . $_REQUEST['bs-timepicker']))->format('Y-m-d H:i:s');
                }
            }

            $message = $this->db->select('*')->from('email_template')->where('id =' . $_REQUEST['template'] . ' and user_id =' . $this->session->userdata('id'))->get()->result();
            $message = $message[0]->html;



            $email_id = sha1($this->session->userdata('id') . date('Y-m-d H:i:s') . $this->getToken());


            $this->session->set_userdata(array('email_id' => $email_id));

            $email_outboxes = $this->db->select('*')->from('users')->where('dept != "admin"')->get()->result();

            foreach ($email_outboxes as $rows) {

                $values = array(
                    'email_recipients' => $rows->email,
                    'created_at' => date('Y-m-d H:i:s'),
                    'sent_by' => $this->session->userdata('id'),
                    'email_body' => $message,
                    'schedule_datetime' => $date,
                    'charge' => 0,
                    'email_status' => 0,
                    'template_id' => $_REQUEST['template'],
                    'uuid' => $email_id,
                    'emailtype' => 'CRM BULK',
                    'email_from' => $from,
                    'email_title' => $subject,
                    'email_template' => 0);
                $this->db->insert('email_outbox', $values);
            }
            $this->data['flash'] = ['message' => 'Emails Sent to Queue', 'class' => 'success'];
        } catch (Exception $exc) {
            $this->data['flash'] = ['message' => 'Failed to send Emails to Queue', 'class' => 'danger'];
            $this->notify("Failed to Send CRM Email", $exc->getTraceAsString(), "CRM Email", "CRM Email");
        }
        $this->load_view($this->data);
    }

    public function send_bulk_sms() {
        $this->data['view'] = 'backend/admin/crm/send-bulk-sms';
        $this->data['viewstatus'] = 'crm';
        $this->load_view($this->data);

        //$this->sendbulk();
    }

    public function sendbulk() {
        if ($this->isloggedin()) {

            $this->data['view'] = 'backend/admin/messages/scheduled_messages';
            $this->data['viewstatus'] = 'crm';
            try {
                $comma_separated_receivers = '';

                $contact_source = 0;

                $totalcharge = 0;
                $numbers = $this->db->select('*')->from('users')->where('dept != "admin"')->get()->result();

                $sender = $_REQUEST['sender'];
                $msg = trim($_REQUEST['msg']);

                $msg_length = strlen($msg);

                $msg_length = (int) ($msg_length / 160);
                $msg_length++;
                $message_id = '';


                $date = '';



                if (isset($_REQUEST['schedule_datetime'])) {
                    if ($_REQUEST['schedule_datetime'] == "") {
                        $date = (new Cake\I18n\Time())->format('Y-m-d H:i:s');
                    } else {
                        $date = (new Cake\I18n\Time($_REQUEST['schedule_datetime'] . ' ' . $_REQUEST['bs-timepicker']))->format('Y-m-d H:i:s');
                    }
                }


                $message_id = md5(date('Y-m-d H:i:s') . $sender);
                foreach ($numbers as $number) {

                    $route = '0';

                    if ($_REQUEST['route'] == 'All') {
                        $route = $this->spectrum->getrouteSpecial($number->mobile);
                    } else {
                        $route = $_REQUEST['route'];
                    }

                    $data = array(
                        'datetime' => date('Y-m-d H:i:s'),
                        'date' => date('Y-m-d'),
                        'senderid' => $sender,
                        'receiver' => $number->mobile,
                        'message' => $msg,
                        'schedule_datetime' => $date,
                        'status' => 0,
                        'sender' => $this->session->userdata('id'),
                        'contacts' => sizeof($numbers),
                        'message_id' => $message_id,
                        'type' => 'CRM BULK',
                        'routeid' => $route,
                        'process' => 1);

                    $this->db->insert('sentitems', $data);
                    $this->data['flash'] = ['message' => 'SMS Sent to Queue', 'class' => 'success'];
                }
            } catch (Exception $exc) {
                $this->data['flash'] = ['message' => 'Failed to send SMS to Queue', 'class' => 'danger'];
                $this->notify("Failed to Send CRM SMS", $exc->getTraceAsString(), "CRM SMS", "CRM SMS");
            }

            parent::load_view($this->data);
        } else {

            $this->logout();
        }
    }

}
