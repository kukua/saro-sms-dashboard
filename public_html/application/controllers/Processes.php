<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
error_reporting(0);

use \Mailjet\Resources;

class Processes extends CI_Controller {

    function __construct() {
        parent::__construct();
        //$this->load->library(array('form_validation','session','excel/Spreadsheet_Excel_Reader','yoapi','pdf'));
        $this->load->library(array('form_validation', 'session', 'spreadsheet_excel_reader', 'smpp', 'Routesms'));
        $this->load->helper(array('form', 'url', 'html', 'inflector'));
        $this->load->database();
        $this->load->model('spectrum');


        date_default_timezone_set('Africa/Nairobi');
    }

    function notify($subject, $message, $from, $fulllname) {

        //Create a new PHPMailer instance
        $mail = new PHPMailer;
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = 0;
        //Ask for HTML-friendly debug output
        $mail->Debugoutput = 'html';
        //Set the hostname of the mail server
        $mail->Host = 'kukua.cc';
        // use
        // $mail->Host = gethostbyname('smtp.gmail.com');
        // if your network does not support SMTP over IPv6
        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $mail->Port = 25;
        //Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPSecure = '';
        //Whether to use SMTP authentication
        $mail->SMTPAuth = FALSE;
        //Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = "noreply@kukua.cc";
        //Password to use for SMTP authentication
        $mail->Password = "spectrum@6";
        //Set who the message is to be sent from
        $mail->setFrom($from, $fulllname);
        //Set who the message is to be sent to
        $mail->addAddress("bugs@kukua.cc");
        //Set the subject line
        $mail->Subject = $subject;
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $mail->msgHTML($message);

        if (!$mail->send()) {
            //echo "Failed: Mailer Error: " . $mail->ErrorInfo;
        } else {
            //echo "Sent: Message has been sent successfully";
        }
    }

    public function smpp_v2_cron_job() {
        if ($this->db->select('*')->from('status')->where(array('status' => 1, 'id' => 6))->get()->num_rows() == 1):

            //Let us pause server server process to finish this batch
            $this->db->update('status', array('status' => -1), array('id' => 6));
            try {
                $datetime = date('Y-m-d H:i:s');

                $datetime = date('Y-m-d H:i:s');


                $smpp_route = $this->db
                        ->select('*')
                        ->from('routes')
                        ->where('routes.id = 18')
                        ->get()
                        ->result();

                $smpp_route = $smpp_route[0];

                //Let is get one of the message IDS. We need a better way were
                $sch_messages = $this->db
                        ->select('*')
                        ->from('sentitems')
                        ->where('schedule_datetime <= "' . $datetime . '" and status = 0 and routeid = ' . $smpp_route->id . '')
                        ->limit(0, $smpp_route->batch_limit)
                        ->order_by('sentitems.id DESC')
                        //->get()->row(); one
                        ->get()
                        ->result();

                foreach ($sch_messages as $one_sch_message):

                    if (preg_match('/25675/', $one_sch_message->receiver) || preg_match('/25670/', $one_sch_message->receiver)):

                        $sms_response = $this->spectrum->smpp_v2($one_sch_message->senderid, $one_sch_message->receiver, $one_sch_message->message, $smpp_route);

                        if ($sms_response['status'] == 1):
                            $this->db->update('sentitems', array('status' => 1), 'id = "' . $one_sch_message->id . '"');
                            echo 'sent: smpp v2';
                        else:
                            $this->db->update('sentitems', array('status' => 2), 'id = "' . $one_sch_message->id . '"');
                            $this->notify("Failed to Send smpp_v2", print_r($sms_response, true), "smpp_v2 Cron", "smpp_v2 Cron");
                            echo 'failed: smpp v2';
                        endif;
                    elseif (!preg_match('/256/', $one_sch_message->receiver)):
                        //insternational route not supported fallback to beepsend
                        $this->db->update('sentitems', array('routeid' => 15), 'id = "' . $one_sch_message->id . '"');
                    else:
                        //smpp not supported fallback to calton
                        $this->db->update('sentitems', array('routeid' => 10), 'id = "' . $one_sch_message->id . '"');

                    endif;
                endforeach;

                //now we can enable sending for another batch    
                $this->db->update('status', array('status' => 1), array('id' => 6));
            } catch (Exception $exc) {
                echo 'failed: smpp v2:  ' . $exc->getTraceAsString();
                $this->notify("Failed to Send smpp_v2", $exc->getTraceAsString(), "smpp_v2 Cron", "smpp_v2 Cron");
            }
        endif;
    }

    public function birthday_cron_job() {
        if ($this->db->select('*')->from('status')->where(array('status' => 1, 'id' => 7))->get()->num_rows() == 1):

            //Let us pause server server process to finish this batch
           // $this->db->update('status', array('status' => -1), array('id' => 7));
            try {
                $datetime = date('m-d');
                $count =0;

                //Let is get one of the message IDS. We need a better way were
                $sch_messages = $this->db
                        ->select('birthdays.*,users.id as uid,users.routeid as routeid,users.credits as credits,users.bonus as bonus,users.sms_cost as sms_cost')
                        ->from('birthdays,users')
                        ->where('users.id=birthdays.created_by')
                        ->where('birthday like "%' . $datetime . '%"')
                        ->limit(50)
                        ->get()
                        ->result();

                foreach ($sch_messages as $one_sch_message):

                    $birthdays_log = $this->db
                            ->select('*')
                            ->from('birthdaymessages')
                            ->where('created_by = "' . $one_sch_message->created_by . '"')
                            ->order_by('RAND()')
                            ->limit(500)
                            ->get()
                            ->row();


                    $msg =str_replace( '$user', $one_sch_message->name, $birthdays_log->message );
                    
                    $msg_length = strlen($msg);

                    $msg_length = (int) ($msg_length / 160);
                    $msg_length++;
                    
                    if (((($one_sch_message->credits + $one_sch_message->bonus) / $one_sch_message->sms_cost) >= $msg_length)||($one_sch_message->dept=='admin')) {

                        
                            $data = array(
                                'datetime' => date('Y-m-d H:i:s'),
                                'date' => date('Y-m-d'),
                                'senderid' => 'SMS',
                                'receiver' => $one_sch_message->mobile,
                                'message' => $msg,
                                'schedule_datetime' => date('Y-m-d H:i:s'),
                                'status' => 0,
                                'sender' => $one_sch_message->created_by,
                                'contacts' => $msg_length,
                                'message_id' =>  md5(date('Y-m-d H:i:s').($count++)),
                                'charge' => $one_sch_message->sms_cost,
                                'type' => 'SMS BIRTHDAY',
                                'routeid' => $one_sch_message->routeid,
                                'process' => 1);

                            
                            $this->db->insert('sentitems', $data);
                        
                        //Update cost
                        $this->spectrum->update('users', 'credits', 'credits-' . $one_sch_message->sms_cost * $msg_length, array('id' => $one_sch_message->created_by));

                    }
                endforeach;

                //now we can enable sending for another batch    
                $this->db->update('status', array('status' => 1), array('id' => 7));
            } catch (Exception $exc) {
                echo 'failed: birthday service:  ' . $exc->getTraceAsString();
                $this->notify("Failed to Send birthday day", $exc->getTraceAsString(), "birthday day Cron", "birthday day Cron");
            }
        endif;
    }

    public function calton_mobile_cron_job() {

        if ($this->db->select('*')->from('status')->where(array('status' => 1, 'id' => 1))->get()->num_rows() == 1):
            try {
                //Let us pause server server process to finish this batch
                $this->db->update('status', array('status' => -1), array('id' => 1));

                $datetime = date('Y-m-d H:i:s');


                $catlon_mobile_route = $this->db
                        ->select('*')
                        ->from('routes')
                        ->where('routes.id = 10')
                        ->get()
                        ->result();

                $catlon_mobile_route = $catlon_mobile_route[0];

                //Let is get one of the message IDS. We need a better way were
                $one_sch_messages = $this->db
                                ->select('*')
                                ->from('sentitems')
                                ->where('schedule_datetime <= "' . $datetime . '" and status = 0 and routeid = ' . $catlon_mobile_route->id . '')
                                ->limit(0, 1)
                                ->order_by('sentitems.id DESC')
                                ->get()->result();


                if ($one_sch_messages != NULL):

                    $one_sch_message = $one_sch_messages[0];

                    //Now let us get similar messages but the limit to the sender batch
                    $sch_messages = $this->db
                            ->select('*')
                            ->from('sentitems')
                            ->where('status = 0 and routeid = ' . $catlon_mobile_route->id . '')
                            ->where('message_id = "' . $one_sch_message->message_id . '"')
                            ->limit(0, $catlon_mobile_route->batch_limit)
                            ->order_by('sentitems.id DESC')
                            ->get();
                    $number_of_sch_sms = $sch_messages->num_rows();

                    $number_counter = 1;
                    $number_string = "";

                    $sch_messages = $sch_messages->result();

                    foreach ($sch_messages as $sch_message):

                        if (!preg_match('/256/', $one_sch_message->receiver)):
                            //insternational route not supported fallback to beepsend
                            $this->db->update('sentitems', array('routeid' => 15), 'id = "' . $one_sch_message->id . '"');
                        else:
                            $number_string = $number_string . $sch_message->receiver;

                        endif;

                        if ($number_counter++ < $number_of_sch_sms):
                            $number_string = $number_string . ',';
                        endif;
                    endforeach;


                    //check for duplicate

                    $sms_response = $this->spectrum->send_calton_mobile($one_sch_message->senderid, $number_string, $one_sch_message->message, $catlon_mobile_route);

                    if ($sms_response[0] == TRUE):
                        foreach ($sch_messages as $sch_message):
                            $this->db->update('sentitems', array('status' => 1), 'id = "' . $sch_message->id . '"');
                        endforeach;
                        //now we can enable sending for another batch    
                        $this->db->update('status', array('status' => 1), array('id' => 1));
                        echo 'sent: calton mobile';
                    else:
                        if ($sms_response[1] != 003):

                            foreach ($sch_messages as $sch_message):
                                $this->db->update('sentitems', array('status' => 2), 'id = "' . $sch_message->id . '"');
                            endforeach;

                            $this->notify("Failed to Send Calton", print_r($sms_response, true), "Calton Cron", "Calton Cron");

                        else:

                            $this->notify("Failed to send to calton Out of Credit", print_r($sms_response, true), "Calton Out of Credit", "Calton Out of Credit");
                        endif;
                        echo 'failed: calton mobile';
                    endif;


                endif;
            } catch (Exception $exc) {
                echo 'failed: calton mobile:  ' . $exc->getTraceAsString();
                $this->notify("Failed to Send Calton", $exc->getTraceAsString(), "Calton Cron", "Calton Cron");
            }
            $this->db->update('status', array('status' => 1), array('id' => 1));
        endif;
    }

    public function beepsend_cron_job() {
        if ($this->db->select('*')->from('status')->where(array('status' => 1, 'id' => 3))->get()->num_rows() == 1):

            //Let us pause server server process to finish this batch
            $this->db->update('status', array('status' => -1), array('id' => 3));
            try {
                $datetime = date('Y-m-d H:i:s');

                $datetime = date('Y-m-d H:i:s');


                $beep_send_route = $this->db
                        ->select('*')
                        ->from('routes')
                        ->where('routes.id = 14')
                        ->get()
                        ->result();

                $beep_send_route = $beep_send_route[0];

                //Let is get one of the message IDS. We need a better way were
                $one_sch_message = $this->db
                                ->select('*')
                                ->from('sentitems')
                                ->where('schedule_datetime <= "' . $datetime . '" and status = 0 and routeid = ' . $beep_send_route->id . '')
                                ->limit(0, 1)
                                ->order_by('sentitems.id DESC')
                                ->get()->row();

                if ($one_sch_message != NULL):
                    $sms_response = $this->spectrum->send_beep_send_legacy($one_sch_message->senderid, $one_sch_message->receiver, $one_sch_message->message, $beep_send_route);

                    if ($sms_response == TRUE):
                        $this->db->update('sentitems', array('status' => 1), 'id = "' . $one_sch_message->id . '"');
                        echo 'sent: beep send';
                    else:
                        $this->db->update('sentitems', array('status' => 2), 'message_id = "' . $one_sch_message->id . '"');
                        echo 'failed: beep send';
                    endif;
                endif;

                //now we can enable sending for another batch    
                $this->db->update('status', array('status' => 1), array('id' => 3));
            } catch (Exception $exc) {
                echo 'failed: beep send:  ' . $exc->getTraceAsString();
                $this->notify("Failed to Send Beepsend", $exc->getTraceAsString(), "Beepsend Cron", "Beepsend Cron");
            }
        endif;
    }

    public function beepsend_rest_cron_job() {
        if ($this->db->select('*')->from('status')->where(array('status' => 1, 'id' => 4))->get()->num_rows() == 1):

            //Let us pause server server process to finish this batch
            $this->db->update('status', array('status' => -1), array('id' => 4));
            try {

                $datetime = date('Y-m-d H:i:s');


                $beepsend_rest_route = $this->db
                        ->select('*')
                        ->from('routes')
                        ->where('routes.id = 15')
                        ->get()
                        ->result();

                $beepsend_rest_route = $beepsend_rest_route[0];

                //Let is get one of the message IDS. We need a better way were
                $one_sch_messages = $this->db
                                ->select('*')
                                ->from('sentitems,users')
                                ->where('users.id =sentitems.sender and sentitems.status = 0 and sentitems.routeid = ' . $beepsend_rest_route->id . '')
                                ->limit(0, 1)
                                ->order_by('sentitems.id DESC')
                                ->get()->result();

                if ($one_sch_messages != NULL):


                    $one_sch_message = $one_sch_messages[0];

                    //Now let us get similar messages but the limit to the sender batch
                    $sch_messages = $this->db
                            ->select('*')
                            ->from('sentitems')
                            ->where('schedule_datetime <= "' . $datetime . '" and status = 0 and routeid = ' . $beepsend_rest_route->id . '')
                            ->where('message_id = "' . $one_sch_message->message_id . '"')
                            ->limit(0, $beepsend_rest_route->batch_limit)
                            ->order_by('sentitems.id DESC')
                            ->get();
                    //$number_of_sch_sms = $sch_messages->num_rows();

                    $messages = $sch_messages->result();


                    $sms_response = $this->spectrum->send_beepsend_rest_multiple($one_sch_message->senderid, $messages, $one_sch_message->message, $beepsend_rest_route);

                    if ($sms_response == TRUE):
                        #update only message ids
                        foreach ($messages as $sch_message):
                            $this->db->update('sentitems', array('status' => 1), 'id = "' . $sch_message->id . '"');
                        endforeach;

                        echo 'sent: beepsend rest';
                    else:
                        #update only message ids
                        foreach ($messages as $sch_message):
                            $this->db->update('sentitems', array('status' => 2), 'id = "' . $sch_message->id . '"');
                        endforeach;
                        echo 'failed: beepsend rest' . print_r($one_sch_message);
                    endif;
                endif;

                //now we can enable sending for another batch    
                $this->db->update('status', array('status' => 1), array('id' => 4));
            } catch (Exception $exc) {
                echo 'failed: beep send rest:  ' . $exc->getTraceAsString();
                $this->notify("Failed to Send Rest Beepsend", $exc->getTraceAsString(), "Beepsend Rest Cron", "Beepsend Rest Cron");
            }
        endif;
    }

    public function routesms_cron_job() {
        if ($this->db->select('*')->from('status')->where(array('status' => 1, 'id' => 8))->get()->num_rows() == 1):
            //Let us pause server server process to finish this batch
            $this->db->update('status', array('status' => -1), array('id' => 8));
            try {

                $datetime = date('Y-m-d H:i:s');


                $smpp_direct_smpp_route = $this->db
                        ->select('*')
                        ->from('routes')
                        ->where('routes.id = 11')
                        ->get()
                        ->result();

                $smpp_direct_smpp_route = $smpp_direct_smpp_route[0];

                //Let is get one of the message IDS. We need a better way were
                $one_sch_messages = $this->db
                                ->select('*')
                                ->from('sentitems,users')
                                ->where('users.id =sentitems.sender and sentitems.status = 0 and sentitems.routeid = ' . $smpp_direct_smpp_route->id . ' and sentitems.type != "CUSTOM"')
                                ->limit(0, 1)
                                ->order_by('sentitems.id DESC')
                                ->get()->result();


                if ($one_sch_messages != NULL):

                    $one_sch_message = $one_sch_messages[0];

                    //Now let us get similar messages but the limit to the sender batch
                    //let

                    $sch_messages = $this->db
                            ->select('*')
                            ->from('sentitems')
                            ->where('schedule_datetime <= "' . $datetime . '" and status = 0 and routeid = ' . $smpp_direct_smpp_route->id . '')
                            ->where('message_id = "' . $one_sch_message->message_id . '"')
                            ->limit(0, $smpp_direct_smpp_route->batch_limit)
                            ->order_by('sentitems.id DESC')
                            ->get()
                            ->result();

                    foreach ($sch_messages as $sch_message):

                        $sms_response = $this->spectrum->send_routesms($sch_message, $sch_message->senderid, $sch_message->receiver, $sch_message->message, $smpp_direct_smpp_route);

                        if ($sms_response[0] == TRUE):
                            $this->db->update('sentitems', array('status' => 1), 'id = "' . $sch_message->id . '"');
                            echo 'sent: route sms';
                        else:
                            $this->db->update('sentitems', array('status' => 2), 'id = "' . $sch_message->id . '"');
                            echo 'failed: route sms';
                            $this->notify("Failed to Send Routesms", print_r($sms_response[1], true), "RouteSMS Cron", "RouteSMS Cron");
                        endif;

                    endforeach;
                endif;

                //now we can enable sending for another batch    
            } catch (Exception $exc) {
                echo 'failed: RouteSMS:  ' . $exc->getTraceAsString();
                $this->notify("Failed to Send Routesms", $exc->getTraceAsString(), "RouteSMS Cron", "RouteSMS Cron");
            }

            $this->db->update('status', array('status' => 1), array('id' => 8));
        endif;
    }

    public function sendmail($data, $url) {

        $options = array
            (
            CURLOPT_URL => $url,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => 1
        );

        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        print_r(curl_error($curl));
        if (!$response) {
            $response = curl_error($curl);
        }
        curl_close($curl);

        return $response;
    }

    public function send_emails_cron() {

        if ($this->db->select('*')->from('status')->where(array('status' => 1, 'id' => 2))->get()->num_rows() == 1):
        //send this batch first
        $this->db->update('status', array('status' => -1), array('id' => 2));

        $emails = $this->db
                ->select('email_outbox.id as id,email_outbox.email_recipients,email_outbox.email_title,email_outbox.email_body,email_outbox.email_from,users.fullname as fullname,users.email_username as email_username,users.email_password as email_password,users.email_server as email_server,users.email_port as email_port,users.email_is_smtp as email_is_smtp,users.email_secure as email_secure,users.email_auth as email_auth')
                ->from('email_outbox,users')
                ->where('email_outbox.email_status = 0 and users.id=email_outbox.sent_by')
                ->limit(50)
                ->get()
                ->result();


        foreach ($emails as $email):
            date_default_timezone_set('Etc/UTC');

            //Create a new PHPMailer instance
            $mail = new PHPMailer;
            if ($email->email_is_smtp) {

                //Tell PHPMailer to use SMTP
                $mail->isSMTP();
            }
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
            //Set the hostname of the mail server
            $mail->Host = $email->email_server;
            // use
            // $mail->Host = gethostbyname('smtp.gmail.com');
            // if your network does not support SMTP over IPv6
            //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
            $mail->Port = $email->email_port;
            //Set the encryption system to use - ssl (deprecated) or tls
            $mail->SMTPSecure = $email->email_secure;
            //Whether to use SMTP authentication
            $mail->SMTPAuth = $email->email_auth;
            //Username to use for SMTP authentication - use full email address for gmail
            $mail->Username = $email->email_username;
            //Password to use for SMTP authentication
            $mail->Password = $email->email_password;
            //Set who the message is to be sent from
            $mail->setFrom($email->email_from, $email->fulllname);
            //Set an alternative reply-to address
            $mail->addReplyTo($email->email_from, $email->fullname);
            //Set who the message is to be sent to
            $mail->addAddress($email->email_recipients);
            //Set the subject line
            $mail->Subject = $email->email_title;
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $mail->msgHTML($email->email_body);

            try {
                if (!$mail->send()) {
                    echo "Failed: Mailer Error: " . $mail->ErrorInfo;
                    $this->notify("Failed to Send Email for : " . $email->email_from, $mail->ErrorInfo, "Mail Cron", "Mail Cron");
                    $this->db->update('email_outbox', array('email_status' => 2, 'error_message' => $mail->ErrorInfo,), array('id' => $email->id));
                    //sleep(2);
                } else {
                    echo "Sent: Email has been sent successfully";
                    $this->db->update('email_outbox', array('email_status' => 1), array('id' => $email->id));
                }
            } catch (Exception $exc) {
                echo 'failed: Send Email cron:  ' . $exc->getTraceAsString();
                $this->notify("Send Email cron", $exc->getTraceAsString(), "Send Email cron", "Send Email cron");
            }
            /* $apikey = 'afd02989644faecce97652ddb43bb931';
              $apisecret = 'f1733d268a47e99445e196784e197a1d';

              try {
              $mj = new \Mailjet\Client($apikey, $apisecret);
              $body = [
              'FromEmail' => $email->email_from,
              'FromName' => $email->fullname,
              'Subject' => $email->email_title,
              'Html-part' =>$email->email_body ,
              'Recipients' => [
              [
              'Email' => $email->email_recipients
              ]
              ]
              ];

              $response = $mj->post(Resources::$Email, ['body' => $body]);
              if($response->success()){
              $this->db->update('email_outbox', array('email_status' => 1), array('id' => $email->id));
              }
              sleep(5);

              } catch (Exception $exc) {
              echo 'failed: Send Email cron:  ' . $exc->getTraceAsString();
              //$this->notify("Send Email cron", $exc->getTraceAsString(), "Send Email cron", "Send Email cron");
              } */
        endforeach;
        //next batch
           $this->db->update('status', array('status' => 1), array('id' => 2));
        endif;
    }

    ///////DIRECT ROUTES///
    public function smpp_direct_cron_job() {

        if ($this->db->select('*')->from('status')->where(array('status' => 1, 'id' => 5))->get()->num_rows() == 1):
            //Let us pause server server process to finish this batch
            $this->db->update('status', array('status' => -1), array('id' => 5));
            try {

                $datetime = date('Y-m-d H:i:s');


                $smpp_direct_smpp_route = $this->db
                        ->select('*')
                        ->from('routes')
                        ->where('routes.id = 17')
                        ->get()
                        ->result();

                $smpp_direct_smpp_route = $smpp_direct_smpp_route[0];

                //Let is get one of the message IDS. We need a better way were
                $one_sch_messages = $this->db
                                ->select('*')
                                ->from('sentitems,users')
                                ->where('users.id =sentitems.sender and sentitems.status = 0 and sentitems.routeid = ' . $smpp_direct_smpp_route->id . ' and sentitems.type != "CUSTOM"')
                                ->limit(0, 1)
                                ->order_by('sentitems.id DESC')
                                ->get()->result();


                if ($one_sch_messages != NULL):

                    $one_sch_message = $one_sch_messages[0];


                    //Now let us get similar messages but the limit to the sender batch
                    //let

                    $sch_messages = $this->db
                            ->select('*')
                            ->from('sentitems')
                            ->where('schedule_datetime <= "' . $datetime . '" and status = 0 and routeid = ' . $smpp_direct_smpp_route->id . '')
                            ->where('message_id = "' . $one_sch_message->message_id . '"')
                            ->limit(0, $smpp_direct_smpp_route->batch_limit)
                            ->order_by('sentitems.id DESC')
                            ->get()
                            ->result();

                    $this->smpp->init('185.73.39.159', 8888);

                    $tx = $this->smpp;

                    $tx->system_type = "SPEC";
                    $tx->bindTransmitter("SPEC", "SPEC123"); // systemId, password
                    // npi = number plan indicator - usually not important
                    // ---------------------------
                    // 0 = unknown - if unsure, set this as default
                    // 1 = isdn
                    // 2 = data
                    // 3 = telex
                    // 4 = land mobile
                    // 5 = national
                    // 6 = private
                    // 7 = ERMES
                    // 8 = Internet
                    // 9 = WAP
                    // ton = type of number - could be important
                    // --------------------
                    // 0 = unknown
                    // 1 = international = means it's in international format, countrycode + mobile number
                    // 2 = national = means it's in national format, usually 0 prefixed, 0xxxzzzyyyy
                    // 3 = network specific
                    // 4 = subscriber number
                    // 5 = alphanumeric, most useful, and usually the default, as you can set this up to 11 chars max.
                    // 6 = abbreviated

                    $tx->sms_source_addr_npi = 0; // here, set to "default" unknown
                    $tx->sms_source_addr_ton = 5; // alphanum, max 11 chars

                    $tx->sms_dest_addr_ton = 1; // international format
                    $tx->sms_dest_addr_npi = 0; // "default" unknown

                    foreach ($sch_messages as $sch_message):
                        /* if (
                          preg_match('/25671/', $sch_message->receiver)||
                          preg_match('/25679/', $sch_message->receiver)||
                          preg_match('/25677/', $sch_message->receiver)||
                          preg_match('/25678/', $sch_message->receiver)||
                          //preg_match('/25675/', $sch_message->receiver)||
                          preg_match('/25776/', $sch_message->receiver)||
                          preg_match('/2567799/', $sch_message->receiver)):

                          //smpp not supported fallback to calton
                          $this->db->update('sentitems', array('routeid' => 10, 'message_id' =>  md5(date('Y-m-d H:i:s') . $sch_message->senderid)), 'id = "' . $sch_message->id . '"');

                          else:
                          $sms_response = $this->spectrum->send_smpp($sch_message->senderid, $sch_message->receiver, $sch_message->message, $tx);

                          if ($sms_response == TRUE):
                          $this->db->update('sentitems', array('status' => 1), 'id = "' . $sch_message->id . '"');
                          echo 'sent: smpp';
                          else:
                          echo 'failed: smpp';
                          endif;
                          endif; */
                        if (preg_match('/25675/', $sch_message->receiver) || preg_match('/25670/', $sch_message->receiver)):

                            $sms_response = $this->spectrum->send_smpp($sch_message->senderid, $sch_message->receiver, $sch_message->message, $tx);

                            if ($sms_response == TRUE):
                                $this->db->update('sentitems', array('status' => 1), 'id = "' . $sch_message->id . '"');
                                echo 'sent: smpp';
                            else:
                                $this->db->update('sentitems', array('status' => 2), 'id = "' . $sch_message->id . '"');
                                echo 'failed: smpp';
                            endif;
                        else:
                            //smpp not supported fallback to calton
                            $this->db->update('sentitems', array('routeid' => 10), 'id = "' . $sch_message->id . '"');

                        endif;

                    endforeach;


                    $tx->close();
                    unset($tx);
                endif;

                //now we can enable sending for another batch    
            } catch (Exception $exc) {
                echo 'failed: smpp:  ' . $exc->getTraceAsString();
                $this->notify("Failed to Send smpp_v1", $exc->getTraceAsString(), "smpp_v1 Cron", "smpp_v1 Cron");
            }

            $this->db->update('status', array('status' => 1), array('id' => 5));
        endif;
    }

    public function smpp_direct_cron_job_demo() {
        /* try {
          error_reporting(1);

          // Construct transport and client
          $transport = new Simbacode\PHPSMPP\Network\SocketTransport(array('185.73.39.159'), 8888);

          $transport->setRecvTimeout(2000);
          $transport->setSendTimeout(2000);
          $smpp = new Simbacode\PHPSMPP\SMPP\SmppClient($transport);
          // Activate binary hex-output of server interaction
          $smpp->debug = true;
          $transport->debug = false;
          $transport->forceIpv4 = false;

          // Open the connection
          $transport->open();
          //$smpp->addr_npi = 0; // here, set to "default" unknown
          //$smpp->addr_ton = 1; // alphanum, max 11 chars
          //$smpp->sms_dest_addr_ton = 1; // international format
          //$smpp->sms_dest_addr_npi = 0; // "default" unknown

          $smpp->sms_service_type ="SPEC";
          $smpp->bindTransmitter("SPEC", "SPEC123");
          // Optional connection specific overrides
          //SmppClient::$sms_null_terminate_octetstrings = false;
          //SmppClient::$csms_method = SmppClient::CSMS_PAYLOAD;
          //SmppClient::$sms_registered_delivery_flag = SMPP::REG_DELIVERY_SMSC_BOTH;
          // Prepare message
          /*$message = 'H€llo world';
          $encodedMessage = Simbacode\PHPSMPP\Network\GsmEncoder::utf8_to_gsm0338($message);
          $from = new Simbacode\PHPSMPP\SMPP\SmppAddress('SMPP Test', \Simbacode\PHPSMPP\SMPP\SMPP::TON_ALPHANUMERIC);
          $to = new Simbacode\PHPSMPP\SMPP\SmppAddress(256704543926,  \Simbacode\PHPSMPP\SMPP\SMPP::TON_INTERNATIONAL,  \Simbacode\PHPSMPP\SMPP\SMPP::NPI_E164);


          // Send
          $smpp->sendSMS($from, $to, $encodedMessage, $tags);

          // Close connection
          $smpp->close();
          } catch (Exception $exc) {
          echo 'failed: smpp:  ' . $exc->getTraceAsString();
          } */


        $this->smpp->init('185.73.39.159', 8888);

        $tx = $this->smpp;

        $tx->system_type = "SPEC";
        $tx->bindTransmitter("SPEC", "SPEC123"); // systemId, password
        // npi = number plan indicator - usually not important
        // ---------------------------
        // 0 = unknown - if unsure, set this as default
        // 1 = isdn
        // 2 = data
        // 3 = telex
        // 4 = land mobile
        // 5 = national
        // 6 = private
        // 7 = ERMES
        // 8 = Internet
        // 9 = WAP
        // ton = type of number - could be important
        // --------------------
        // 0 = unknown
        // 1 = international = means it's in international format, countrycode + mobile number
        // 2 = national = means it's in national format, usually 0 prefixed, 0xxxzzzyyyy
        // 3 = network specific
        // 4 = subscriber number
        // 5 = alphanumeric, most useful, and usually the default, as you can set this up to 11 chars max.
        // 6 = abbreviated

        $tx->sms_source_addr_npi = 0; // here, set to "default" unknown
        $tx->sms_source_addr_ton = 5; // alphanum, max 11 chars

        $tx->sms_dest_addr_ton = 1; // international format
        $tx->sms_dest_addr_npi = 0; // "default" unknown


        $msg = 'Praise the Lord, Hosanna in the highest, am thrilled just to remind you that my savior is alive that the grave could not hold Him captive. This gives me all the joy n confidence to tell others of the hope found in knowing Him as Lord and Savior. I love you all, enjoy this Easter Season, BISHOP HARRY MORRIS BUKENYA';

        /* if ($tx->sendSMSLong('Notice', '256704543926', $msg)) {
          echo 'success';
          } */

        $tx->close();
        unset($tx);
    }

    public function ipn_cron() {

        try {

            $pesapal_jobs = $this->db
                    ->select('*')
                    ->from('pesapal')
                    ->where('status != "COMPLETED"')
                    ->order_by('pesapal.id DESC')
                    ->get()
                    ->result();

            $pesapal = new Simbacode\Moneycake\Pesapal(true, "F0CtCTSRGkLlvm8ojVSg9i7uz2uouLNT", "X867MI1kc9zUgaHuXOIHZJ2fn90=");

            foreach ($pesapal_jobs as $pesapal_job):

                $reference = $pesapal_job->reference;
                $pesapal_tracking_id = $pesapal_job->pesapal_tracking_id;

                $pesapalNotification = "CHANGE";

                //$this->notify("Pesapal object", print_r($pesapal_jobs, true), "Pesapal object", "Pesapal object");

                $status = $pesapal->InstantPaymentNotification($pesapalNotification, $reference, $pesapal_tracking_id);

                //$this->notify("Pesapal IPN Status", print_r($status, true), "Pesapal IPN Status", "Pesapal IPN Status");

                if ($status == 'COMPLETED'):
                    $pesa = $this->db->select('*')->from('pesapal')->where(array('reference' => $reference, 'pesapal_tracking_id' => $pesapal_tracking_id))->get()->result();
                    $pesa = $pesa[0];

                    $user_details = $this->db->select('*')->from('users')->where(array('id' => $pesa->user_id))->get()->result();
                    $user_details = $user_details[0];
                    $credits = $user_details->credits;

                    if ($pesa->category == 'sms' && $pesa->status != "COMPLETED"):
                        //$email_details = array('type' => 'Account Top up', 'message' => 'Dear ' . $user_details[0]->fullname . '<br>Your account has been credited with USD ' . number_format($pesa->credit) . ' worthy of messages via pesapal', 'email' => $user_details[0]->email);

                        $this->spectrum->update('users', 'credits', 'credits+' . $pesa->credit, array('id' => $user_details->id));
                    elseif ($pesa->category == 'email' && $pesa->status != "COMPLETED"):
                        //$email_details = array('type' => 'Account Top up', 'message' => 'Dear ' . $user_details[0]->fullname . '<br>Your account has been credited with USD ' . number_format($pesa->credit) . ' worthy of emails via pesapal', 'email' => $user_details[0]->email);

                        $this->spectrum->update('users', 'email_credits', 'email_credits+' . $pesa->credit, array('id' => $user_details->id));
                    endif;
                endif;
                if ($status != ""):

                    $this->db->update('pesapal', array('status' => $status), array('reference' => $reference, 'pesapal_tracking_id' => $pesapal_tracking_id));
                endif;
            endforeach;
        } catch (Exception $exc) {
            echo 'failed: Pespal IPN cron:  ' . $exc->getTraceAsString();
            $this->notify("Failed to pesapal cron", $exc->getTraceAsString(), "Pespal cron", "Pespal cron");
        }

        //$this->load->view($this->data['view'], $this->data);
    }

    public function routesms_dlr() {
        $this->notify("RouteSMS Callback", print_r($_POST, true), "RouteSMS Callback", "RouteSMS Callback");
        try {
            if ($_POST['dlr_status'] == "DELIVRD"):
                $this->db->update('sentitems', array('status' => 3), 'routesms_id = "' . $_POST['sMessageId'] . '"');
                // header("HTTP/1.1 200 OK");
                http_response_code(200);
            endif;
        } catch (Exception $exc) {
            echo 'failed: route sms send:  ' . $exc->getTraceAsString();
            $this->notify("Failed to do route sms callback", $exc->getTraceAsString(), "Routesms Callback", "Routesms Callback");
        }
    }

    public function Callback() {
        #$this->notify("Beepsend Callback", print_r($_GET,true), "Beepsend Callback", "Beepsend Callback");
        try {
            if ($_GET['dlr_status'] == "DELIVRD"):
                #$this->db->update('sentitems', array('status' => 3), 'beepsend_id = "' . $_GET['id'] . '"');
                // header("HTTP/1.1 200 OK");
                http_response_code(200);
            endif;
        } catch (Exception $exc) {
            echo 'failed: beep send rest:  ' . $exc->getTraceAsString();
            $this->notify("Failed to do callback", $exc->getTraceAsString(), "Beepsend Callback", "Beepsend Callback");
        }
    }

}
