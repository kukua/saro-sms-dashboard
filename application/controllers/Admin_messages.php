<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_messages extends Spectrum_Controller {

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

    public function edit_message_single($id) {


        $message = $_REQUEST['message_edit'];

        $id = $_REQUEST['id'];
        $values = array(
            'message' => $message,
        );
        $this->db->update('sentitems', $values, array('message_id' => $id));
        $this->data['flash'] = ["message" => "Message editted successfully, Thank you!", "class" => "success"];

        $this->data['message_id'] = $id;
        $this->data['view'] = 'backend/admin/messages/sent-message-details';
        $this->data['viewstatus'] = 'messages';
        $this->load_view($this->data);
    }

    public function sendbulk() {
        if ($this->isloggedin()) {

            $this->data['view'] = 'backend/admin/messages/scheduled_messages';
            $this->data['viewstatus'] = 'messages';

            $comma_separated_receivers = '';

            $contact_source = 0;

            $totalcharge = 0;
            $numbers = array();
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


            switch ($_REQUEST['receiver_copy']) {
                case 'copy':

                    $number = $_REQUEST['copypaste'];

                    $text = trim($number);


                    $textAr = explode("\n", $text);


                    $textAr = array_filter($textAr, 'trim');




                    foreach ($textAr as $line) {
                        if (strlen(trim($line)) == 12 || strlen(trim($line)) == 11)
                            array_push($numbers, trim($line));
                        else {
                            
                        }
                    }




                    break;

                case 'group':

                    $group = $_REQUEST['group_option'];

                    $results = $this->db->select('mobile')->from('contacts')->where(array('group_id' => $group))->get()->result();

                    foreach ($results as $rows) {
                        if (strlen(trim($rows->mobile)) == 12 || strlen(trim($rows->mobile)) == 11)
                            array_push($numbers, trim($rows->mobile));
                        else {
                            
                        }
                    }



                    break;
            }



            $comma_separated_receivers = implode(',', $numbers);


            $credits = $this->db->select('credits, persms_flag, sms_cost, bonus')->from('users')->where(array('id' => $this->session->userdata('id')))->get()->result();


            $credits = $credits[0];


            if (($credits->credits + $credits->bonus) >= sizeof($numbers) || $this->session->userdata('dept') == 'admin') {

                $message_id = md5(date('Y-m-d H:i:s') . $sender);
                for ($i = 0; $i < sizeof($numbers); $i++) {

                    $route = '0';

                    if ($_REQUEST['route'] == 'All') {
                        $route = $this->spectrum->getrouteSpecial($numbers[$i]);
                    } else {
                        $route = $_REQUEST['route'];
                    }

                    $data = array(
                        'datetime' => date('Y-m-d H:i:s'),
                        'date' => date('Y-m-d'),
                        'senderid' => $sender,
                        'receiver' => $numbers[$i],
                        'message' => $msg,
                        'schedule_datetime' => $date,
                        'status' => 0,
                        'sender' => $this->session->userdata('id'),
                        'contacts' => sizeof($numbers),
                        'message_id' => $message_id,
                        'type' => 'BULK',
                        'routeid' => $route,
                        'process' => 1);

                    if ($this->db->select('id')->from('sentitems')->where(array('message_id' => $message_id, 'receiver' => $numbers[$i]))->get()->num_rows() == 0) {

                        //DETECT DUPLICATE
                        $this->db->insert('sentitems', $data);
                    } else {
                        //IGnore duplicate
                    }

                    /* if (sizeof($numbers) <= 2) {


                      if (strlen($_REQUEST['schedule_datetime']) == 0)
                      if ($route > 0) {

                      $this->db->update('sentitems', array('status' => 1), 'message_id = "' . $message_id . '" and receiver = "' . $numbers[$i] . '"');

                      ($this->spectrum->sendSpecial($sender, $numbers[$i], $msg, $route));
                      }
                      } */
                }



                if (sizeof($numbers) > 0) {

                    /* $email_details = array('senderid' => $sender,
                      'contacts' => sizeof($numbers),
                      'message' => $msg,
                      'totalcharge' => $totalcharge,
                      'email' => $this->session->userdata('email'));

                      $this->spectrum->bulk_sms_email($email_details); */



                    $this->sms_sent = 1;

                    $this->data['flash'] = ['message' => 'Message sent successfully', 'class' => 'success'];
                    ?>


                    <?php

                } else {


                    $this->sms_sent = 0;
                    $this->data['flash'] = ['message' => 'No contacts to sent to', 'class' => 'danger'];
                    ?>


                    <?php

                }
            }
            parent::load_view($this->data);
        } else {

            $this->logout();
        }
    }

    public function send_psms_bulk() {
        if ($this->isloggedin()) {

            $this->data['view'] = 'backend/admin/messages/scheduled_messages';
            $this->data['viewstatus'] = 'messages';

            $comma_separated_receivers = '';

            $contact_source = 0;

            $totalcharge = 0;
            $numbers = json_decode($_REQUEST['numbers']);
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
                    $date = (new Cake\I18n\Time($_REQUEST['schedule_datetime']))->format('Y-m-d H:i:s');
                }
            }

            $credits = $this->db->select('credits, persms_flag, sms_cost, bonus')->from('users')->where(array('id' => $this->session->userdata('id')))->get()->result();


            $credits = $credits[0];


            if (($credits->credits + $credits->bonus) >= sizeof($numbers) || $this->session->userdata('dept') == 'admin') {

                $message_id = md5(date('Y-m-d H:i:s') . $sender);
                for ($i = 0; $i < sizeof($numbers); $i++) {

                    $route = '0';

                    if ($_REQUEST['route'] == 'All') {
                        $route = $this->spectrum->getrouteSpecial($numbers[$i]->number);
                    } else {
                        $route = $_REQUEST['route'];
                    }

                    $data = array(
                        'datetime' => date('Y-m-d H:i:s'),
                        'date' => date('Y-m-d'),
                        'senderid' => $sender,
                        'receiver' => $numbers[$i]->number,
                        'message' => $numbers[$i]->message,
                        'schedule_datetime' => $date,
                        'status' => 0,
                        'sender' => $this->session->userdata('id'),
                        'contacts' => sizeof($numbers),
                        'message_id' => $message_id,
                        'type' => 'BULK',
                        'routeid' => $route,
                        'process' => 1);

                    if ($this->db->select('id')->from('sentitems')->where(array('message_id' => $message_id, 'receiver' => $numbers[$i]->number))->get()->num_rows() == 0) {

                        //DETECT DUPLICATE
                        $this->db->insert('sentitems', $data);
                    } else {
                        //IGnore duplicate
                    }

                    /* if (sizeof($numbers) <= 2) {


                      if (strlen($_REQUEST['schedule_datetime']) == 0)
                      if ($route > 0) {

                      $this->db->update('sentitems', array('status' => 1), 'message_id = "' . $message_id . '" and receiver = "' . $numbers[$i]->number . '"');

                      ($this->spectrum->sendSpecial($sender, $numbers[$i]->number,  $numbers[$i]->message, $route));
                      }
                      } */
                }



                if (sizeof($numbers) > 0) {

                    /* $email_details = array('senderid' => $sender,
                      'contacts' => sizeof($numbers),
                      'message' => 'You sent customized SMS via excel',
                      'totalcharge' => $totalcharge,
                      'email' => $this->session->userdata('email'));

                      $this->spectrum->bulk_sms_email($email_details); */



                    $this->sms_sent = 1;

                    $this->data['flash'] = ['message' => 'Message sent successfully', 'class' => 'success'];
                    ?>


                    <?php

                } else {


                    $this->sms_sent = 0;
                    $this->data['flash'] = ['message' => 'No contacts to sent to', 'class' => 'danger'];
                    ?>




                    <?php

                }
            }
            parent::load_view($this->data);
        } else {

            $this->logout();
        }
    }

    public function governor_messages() {
        $this->data['view'] = 'backend/admin/messages/governor_messages';
        $this->data['viewstatus'] = 'messages';
        $this->load_view($this->data);
    }

    public function governor_to_queue() {

        $this->data['view'] = 'backend/admin/messages/governor_messages';
        $this->data['viewstatus'] = 'messages';
        $this->load_view($this->data);


        $datetime = date('Y-m-d H:i:s');

        $sch_messages = $this->db
                ->select('*')
                ->from('sentitems')
                ->where('schedule_datetime <= "' . $datetime . '" and status = 4')
                ->limit($_REQUEST['number'])
                ->order_by('sentitems.id DESC')
                //->get()->row(); one
                ->get()
                ->result();
        foreach ($sch_messages as $one_sch_message):
            $this->db->update('sentitems', array('status' => 0), array('id' => $one_sch_message->id));
        endforeach;
        $this->data['flash'] = ['message' => 'Message sent to faster queue', 'class' => 'success'];

        redirect('admin_messages/scheduled_messages', 'refresh');
    }

    public function delete_message($id = null) {
        $this->db->where(array('message_id' => $id))->delete('sentitems');
        redirect('admin_messages/scheduled_messages', 'refresh');
    }

    public function scheduled_messages() {
        $this->data['view'] = 'backend/admin/messages/scheduled_messages';
        $this->data['viewstatus'] = 'messages';
        $this->load_view($this->data);
    }

    public function message_details($id = null) {
        $this->data['message_id'] = $id;
        $this->data['view'] = 'backend/admin/messages/sent-message-details';
        $this->data['viewstatus'] = 'messages';
        $this->load_view($this->data);
    }

    public function send_now($id) {
        if ($this->db->select('*')->from('status')->where(array('status' => 1, 'id' => 5))->get()->num_rows() == 1):
            //Let us pause server server process to finish this batch
            $this->db->update('status', array('status' => -1), array('id' => 5));
            $this->data['message_id'] = $id;
            $this->data['viewstatus'] = 'messages';
            $this->data['view'] = 'backend/admin/messages/sent-message-details';
            try {

                //Now let us get similar messages but the limit to the sender batch
                //let

                $sch_messages = $this->db
                        ->select('*')
                        ->from('sentitems')
                        ->where('message_id = "' . $id . '"')
                        ->order_by('sentitems.id DESC')
                        ->get()
                        ->result();

                $this->notify("Failed to Send smpp_v2", print_r($sch_messages, true), "smpp_v2 Cron", "smpp_v2 Cron");

                $this->smpp->init('185.73.39.159', 8888);

                $tx = $this->smpp;

                $tx->system_type = "SPEC";
                $tx->bindTransmitter("SPEC", "SPEC123"); // systemId, password
                $tx->sms_source_addr_npi = 0; // here, set to "default" unknown
                $tx->sms_source_addr_ton = 5; // alphanum, max 11 chars

                $tx->sms_dest_addr_ton = 1; // international format
                $tx->sms_dest_addr_npi = 0; // "default" unknown

                foreach ($sch_messages as $sch_message):
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

                //now we can enable sending for another batch    
            } catch (Exception $exc) {
                echo 'failed: smpp:  ' . $exc->getTraceAsString();
                $this->notify("Failed to Send smpp_v1", $exc->getTraceAsString(), "smpp_v1 Cron", "smpp_v1 Cron");
            }
            $this->db->update('status', array('status' => 1), array('id' => 5));

            $this->load_view($this->data);

        endif;
        $this->data['flash'] = ['message' => 'Message in Sending', 'class' => 'info'];
    }

    public function resend_sent_messages($id = null) {
        $this->db->where('id > 1')->delete('delivered');
        //$this->db->update('sentitems', array('status' => 0), array('message_id' => $id, 'status' => 1));
        $this->db->update('sentitems', array('status' => 0), array('message_id' => $id, 'status' => 2));

        redirect('admin_messages/sent_messages', 'refresh');
    }

    public function personalize_sms($id = null) {
        if (isset($_POST['upload'])) {
            $this->data['view'] = 'backend/admin/messages/personalised_sms';
            $this->data['viewstatus'] = 'messages';

            $file_name = $_FILES['contacts']['name'];

            $buffer = '';
            for ($i = 0; $i < 10; $i++) {
                $buffer .= rand() % 10;
            }

            $file_name = md5($this->session->userdata('mobile') . '_' . date('Y-m-d H:i:s')) . '_' . $file_name;

            move_uploaded_file($_FILES["contacts"]["tmp_name"], "upload/" . $file_name);

            $file_name = "upload/" . $file_name;
            $this->session->set_userdata(array('file_name' => $file_name));


            // $_SESSION['file_name'] = $file_name;
            //    echo '<META http-equiv=refresh content=0;URL=?page=upload-custom-sms&title=Personalized+SMS&collapse=message>';
        }



        if (strlen($this->session->userdata('file_name')) > 0) {

            $this->data['message'] = $this->uploaded_personalized;
        } else {
            redirect('admin_messages/send_personalised_sms', 'refresh');
        }
        parent::load_view($this->data);
    }

    public function send_bulk_sms() {
        $this->data['view'] = 'backend/admin/messages/send-bulk-sms';
        $this->data['viewstatus'] = 'messages';
        $this->load_view($this->data);

        //$this->sendbulk();
    }

    public function send_personalised_sms() {
        $this->data['view'] = 'backend/admin/messages/send_personalised_sms';
        $this->data['viewstatus'] = 'messages';
        $this->load_view($this->data);
    }

    public function personalised_sms() {
        $this->data['view'] = 'backend/admin/messages/personalised_sms';
        $this->data['viewstatus'] = 'messages';
        $this->load_view($this->data);
    }

    public function messages() {
        if ($this->isloggedin()) {

            $this->data['view'] = 'backend/admin/messages/messages_container';
            $this->data['viewstatus'] = 'messages';
            $this->load_view($this->data);
        } else {

            $this->logout();
        }
    }

    public function sent_messages() {
        if ($this->isloggedin()) {

            $this->data['view'] = 'backend/admin/messages/sent_messages';
            $this->data['viewstatus'] = 'messages';
            $this->load_view($this->data);
        } else {

            $this->logout();
        }
    }

    public function messageUsage($method = null) {
        if ($this->isloggedin()) {

            $this->data['view'] = 'messageUsage';
            $this->data['sublink'] = 'Manage_account_settings';
            $this->data['details'] = 'Token Search results';

            $this->load_view($this->data);
        } else {

            $this->logout();
        }
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

    public function download_message($messageid = null) {
        $header = '#,Sent Time,Schedule time,SenderID,Message,Receiver,Status,Charge';
        $data = '';
        $count = 1;
        foreach ($this->db->select('*')->from('sentitems')->where(array('message_id' => $messageid))->order_by('id')->get()->result() as $rows) {

            $line = '"' . $count . '"' . "," . '"' . $rows->datetime . '"' . "," . '"' . $rows->schedule_datetime . '"' . "," . '"' . $rows->senderid . '"' . "," .
                    '"' . $rows->message . '"' . "," .
                    '"' . $rows->receiver . '"' . "," . '"' . ($rows->status == 1 ? 'SUCCEEDED' : ($rows->status == 0 ? 'PENDING' : 'FAILED')) . '"' . "," . '"' . $rows->logintime . '"' . ",";
            ;

            $data .= trim($line) . "\n";

            $count++;
        }


        $data = str_replace("\r", "", $data);

        $file_name = 'Spectrum_sent_message_' . date('Y-m-d-H-i-s');

        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: csv" . date("Y-m-d") . ".csv");
        header("Content-disposition: filename=" . $file_name . ".csv");
        print "$header\n$data";
        exit;
    }

    public function download_messages($messageid = null) {
        $header = '#,Sent Time,Schedule time,Sender,Message,Receiver,Status,Charge';
        $data = '';
        $count = 1;
        foreach ($this->db->select('*')->from('sentitems')->where(array('message_id' => $messageid))->order_by('id')->get()->result() as $rows) {

            $line = '"' . $count . '"' . "," . '"' . $rows->datetime . '"' . "," . '"' . $rows->schedule_datetime . '"' . "," . '"' . $rows->senderid . '"' . "," .
                    '"' . $rows->message . '"' . "," .
                    '"' . $rows->receiver . '"' . "," . '"' . ($rows->status == 1 ? 'SUCCEEDED' : ($rows->status == 0 ? 'PENDING' : 'FAILED')) . '"' . "," . '"' . $rows->logintime . '"' . ",";
            ;

            $data .= trim($line) . "\n";

            $count++;
        }


        $data = str_replace("\r", "", $data);

        $file_name = 'Spectrum_sent_message_' . date('Y-m-d-H-i-s');

        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: csv" . date("Y-m-d") . ".csv");
        header("Content-disposition: filename=" . $file_name . ".csv");
        print "$header\n$data";
        exit;
    }

}
