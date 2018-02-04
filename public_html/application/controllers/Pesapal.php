<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pesapal extends Spectrum_Controller {

    public function load_view($data = null) {

        parent::load_view($this->data);
    }

    function __construct() {

        parent::__construct();
    }

    public function purchaseredirect() {

        //$this->data = array();
        $this->data['view'] = 'backend/' . $this->session->userdata('dept') . '/dashboard';
        $this->data['viewstatus'] = 'dashboard';


        $reference = null;
        $pesapal_tracking_id = null;
        if (isset($_GET['pesapal_merchant_reference']))
            $reference = $_GET['pesapal_merchant_reference'];
        if (isset($_GET['pesapal_transaction_tracking_id']))
            $pesapal_tracking_id = $_GET['pesapal_transaction_tracking_id'];
        //store $pesapal_tracking_id in your database against the order with orderid = $reference
        if ($this->db->select('id')->from('pesapal')->where(array('reference' => $reference, 'pesapal_tracking_id ' => $pesapal_tracking_id))->get()->num_rows() == 0) {
            $values = array(
                'reference' => $reference,
                'user_id' => $this->session->userdata('id'),
                'status' => 'PENDING',
                'category' => $this->session->userdata('pesapalcategory'),
                'credit' => $this->session->userdata('pesapalamount'),
                'created_at' => date('Y-m-d H:i:s'),
                'pesapal_tracking_id' => $pesapal_tracking_id);

            $this->db->insert('pesapal', $values);

            $this->data['flash'] = ["message" => "Your transaction has started,please wait..", "class" => "success"];
        } else {
            $this->data['flash'] = ["message" => "Transaction already started,please wait..", "class" => "warning"];
        }
        $this->load_view($this->data);
    }

    public function IPN_OLD() {

        $this->notify("Pesapal IPN", print_r($_GET, true), "Pesapal IPN", "Pesapal IPN");

        //$this->data = array();
        $this->data['view'] = 'backend/querypayments';
        $this->data['viewstatus'] = 'dashboard';


        $reference = null;
        $pesapal_tracking_id = null;
        if (isset($_GET['pesapal_merchant_reference']))
            $reference = $_GET['pesapal_merchant_reference'];
        if (isset($_GET['pesapal_transaction_tracking_id']))
            $pesapal_tracking_id = $_GET['pesapal_transaction_tracking_id'];

        $pesapalNotification = $_GET['pesapal_notification_type'];


        error_reporting(E_ALL);


        //TODO NOTIFICATION TYPE
        $pesapal = new Simbacode\Moneycake\Pesapal(true, "F0CtCTSRGkLlvm8ojVSg9i7uz2uouLNT", "X867MI1kc9zUgaHuXOIHZJ2fn90=");

        $pesapal->InstantPaymentNotification($pesapalNotification, $reference, $pesapal_tracking_id, function($status) {


            if ($this->db->update('pesapal', array('status' => $status), array('reference' => $reference, 'pesapal_tracking_id' => $pesapal_tracking_id))->affected_rows() > 0) {

                if ($status == 'CHANGE'):
                    $pesa = $this->db->select('*')->from('pesapal')->where(array('reference' => $reference, 'pesapal_tracking_id' => $pesapal_tracking_id))->get()->result();
                    $pesa = $pesa[0];

                    $user_details = $this->db->select('*')->from('users')->where(array('id' => $pesa->user_id))->get()->result();
                    $credits = $user_details[0]->credits;

                    if ($pesa->category == 'sms') {
                        $email_details = array('type' => 'Account Top up', 'message' => 'Dear ' . $user_details[0]->fullname . '<br>Your account has been credited with USD ' . number_format($pesa->credit) . ' worthy of messages via pesapal', 'email' => $user_details[0]->email);

                        $this->spectrum->update('users', 'credits', 'credits+' . $pesa->credit, array('id' => $this->data['id']));
                    } else {
                        $email_details = array('type' => 'Account Top up', 'message' => 'Dear ' . $user_details[0]->fullname . '<br>Your account has been credited with USD ' . number_format($pesa->credit) . ' worthy of emails via pesapal', 'email' => $user_details[0]->email);

                        $this->spectrum->update('users', 'email_credits', 'email_credits+' . $pesa->credit, array('id' => $this->data['id']));
                    }


                //$this->spectrum->send_notification($email_details);
                endif;



                return TRUE;
            };
        });

        $this->load->view($this->data['view'], $this->data);
    }

    public function IPN() {

        try {
            //$this->notify("Pesapal IPN call", print_r($_GET, true), "Pesapal IPN call", "Pesapal IPN call");
            //$this->data = array();
            $this->data['view'] = 'backend/querypayments';
            $this->data['viewstatus'] = 'dashboard';


            $reference = null;
            $pesapal_tracking_id = null;
            if (isset($_GET['pesapal_merchant_reference']))
                $reference = $_GET['pesapal_merchant_reference'];
            if (isset($_GET['pesapal_transaction_tracking_id']))
                $pesapal_tracking_id = $_GET['pesapal_transaction_tracking_id'];

            $pesapalNotification = $_GET['pesapal_notification_type'];

            $pesapal = new Simbacode\Moneycake\Pesapal(true, "F0CtCTSRGkLlvm8ojVSg9i7uz2uouLNT", "X867MI1kc9zUgaHuXOIHZJ2fn90=");

            $status = $pesapal->InstantPaymentNotification($pesapalNotification, $reference, $pesapal_tracking_id);

            $this->notify("Pesapal IPN Status", print_r($pesapal, true), "Pesapal IPN Status", "Pesapal IPN Status");

            if ($pesapalNotification == 'CHANGE'):


                if ($status == 'COMPLETED'):
                    $pesa = $this->db->select('*')->from('pesapal')->where(array('reference' => $reference, 'pesapal_tracking_id' => $pesapal_tracking_id))->get()->result();
                    $pesa = $pesa[0];

                    $user_details = $this->db->select('*')->from('users')->where(array('id' => $pesa->user_id))->get()->result();
                    $user_details = $user_details[0];
                    $credits = $user_details->credits;

                    if ($pesa->category == 'sms' && $pesa->status != "COMPLETED"):
                        $this->notify("Pesapal IPN SMS", print_r($status, true), "Pesapal IPN Status", "Pesapal IPN SMS");
                        //$email_details = array('type' => 'Account Top up', 'message' => 'Dear ' . $user_details[0]->fullname . '<br>Your account has been credited with USD ' . number_format($pesa->credit) . ' worthy of messages via pesapal', 'email' => $user_details[0]->email);

                        $this->spectrum->update('users', 'credits', 'credits+' . $pesa->credit, array('id' => $user_details->id));
                    elseif ($pesa->category == 'email' && $pesa->status != "COMPLETED"):
                        //$email_details = array('type' => 'Account Top up', 'message' => 'Dear ' . $user_details[0]->fullname . '<br>Your account has been credited with USD ' . number_format($pesa->credit) . ' worthy of emails via pesapal', 'email' => $user_details[0]->email);

                        $this->spectrum->update('users', 'email_credits', 'email_credits+' . $pesa->credit, array('id' => $user_details->id));
                    endif;



                    $resp = "pesapal_notification_type=$pesapalNotification&pesapal_transaction_tracking_id=$pesapal_tracking_id&pesapal_merchant_reference=$reference";
                    ob_start();
                    echo $resp;
                    ob_flush();
                    exit;
                endif;
                if ($status != ""):
                    $this->db->update('pesapal', array('status' => $status), array('reference' => $reference, 'pesapal_tracking_id' => $pesapal_tracking_id));
                endif;
            endif;
        } catch (Exception $exc) {
            echo 'failed: Pespal IPN:  ' . $exc->getTraceAsString();
            $this->notify("Failed to Send Beepsend", $exc->getTraceAsString(), "Pespal IPN", "Pespal IPN");
        }

        //$this->load->view($this->data['view'], $this->data);
    }

    public function T_IPN() {

        try {
            //$this->notify("Pesapal IPN call", print_r($_GET, true), "Pesapal IPN call", "Pesapal IPN call");
            //$this->data = array();
            $this->data['view'] = 'backend/querypayments';
            $this->data['viewstatus'] = 'dashboard';


            $reference = "1E2148E1-0B75-6148-726B-B8CA9E86D983";
            $pesapal_tracking_id = "cc86e884-5fd7-4e43-9443-dac2225968ec";

            $pesapalNotification = "CHANGE";

            $pesapal = new Simbacode\Moneycake\Pesapal(true, "F0CtCTSRGkLlvm8ojVSg9i7uz2uouLNT", "X867MI1kc9zUgaHuXOIHZJ2fn90=");

            $status = $pesapal->InstantPaymentNotification($pesapalNotification, $reference, $pesapal_tracking_id);
            
            echo $status;
            

           /* ob_start();
            echo $status;
            ob_flush();*/
            exit;
            //$this->notify("Pesapal IPN Status", print_r($pesapal, true), "Pesapal IPN Status", "Pesapal IPN Status");

            $this->db->update('pesapal', array('status' => $status), array('reference' => $reference, 'pesapal_tracking_id' => $pesapal_tracking_id));

            if ($pesapalNotification == 'CHANGE'):


                if ($status == 'COMPLETED'):
                    $pesa = $this->db->select('*')->from('pesapal')->where(array('reference' => $reference, 'pesapal_tracking_id' => $pesapal_tracking_id))->get()->result();
                    $pesa = $pesa[0];


                    $user_details = $this->db->select('*')->from('users')->where(array('id' => $pesa->user_id))->get()->result();
                    $credits = $user_details[0]->credits;

                    if ($pesa->category == 'sms'):
                        //$email_details = array('type' => 'Account Top up', 'message' => 'Dear ' . $user_details[0]->fullname . '<br>Your account has been credited with USD ' . number_format($pesa->credit) . ' worthy of messages via pesapal', 'email' => $user_details[0]->email);

                        $this->spectrum->update('users', 'credits', 'credits+' . $pesa->credit, array('id' => $user_details->id));
                    else:
                        //$email_details = array('type' => 'Account Top up', 'message' => 'Dear ' . $user_details[0]->fullname . '<br>Your account has been credited with USD ' . number_format($pesa->credit) . ' worthy of emails via pesapal', 'email' => $user_details[0]->email);

                        $this->spectrum->update('users', 'email_credits', 'email_credits+' . $pesa->credit, array('id' => $user_details->id));
                    endif;

                endif;

                $resp = "pesapal_notification_type=$pesapalNotification&pesapal_transaction_tracking_id=$pesapal_tracking_id&pesapal_merchant_reference=$reference";
                ob_start();
                echo $resp;
                ob_flush();
                exit;

            endif;
        } catch (Exception $exc) {
            echo 'failed: Pespal IPN:  ' . $exc->getTraceAsString();
            $this->notify("Failed to Send Beepsend", $exc->getTraceAsString(), "Pespal IPN", "Pespal IPN");
        }

        //$this->load->view($this->data['view'], $this->data);
    }

}
