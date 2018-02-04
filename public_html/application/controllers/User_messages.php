<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_messages extends Spectrum_Controller {

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

    public function sendbulk() {


        $this->data['view'] = 'backend/user/messages/scheduled_messages';
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
        $credits = $this->db
                ->select('credits, persms_flag, sms_cost, bonus')
                ->from('users')
                ->where(array('id' => $this->session->userdata('id')))
                ->get()
                ->result();
        $credits = $credits[0];
        //Default is beepsend calton
        $route = 18;
        $message_id = md5(date('Y-m-d H:i:s') . $sender);


        if ($credits->persms_flag == 1) {
            if ($credits->sms_cost > 0) {

                $routes = $this->db
                        ->select('*')
                        ->from('routes,routes_assigned')
                        ->where('routes_assigned.userid = ' . $this->session->userdata('id') . ' and routes_assigned.routeid = routes.id')
                        ->get()
                        ->result();

                $route = !empty($routes) ? $routes[rand(0, sizeof($routes) - 1)]->routeid : $route;


                if ((($credits->credits + $credits->bonus) / $credits->sms_cost) >= sizeof($numbers)) {
                    $duplicate = 0;
                    for ($i = 0; $i < sizeof($numbers); $i++) {
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
                            'charge' => $credits->sms_cost,
                            'type' => 'SMS BULK',
                            'routeid' => $route,
                            'process' => 1);

                        if ($this->db->select('id')->from('sentitems')->where(array('message_id' => $message_id, 'receiver' => $numbers[$i]))->get()->num_rows() == 0) {

                            //DETECT DUPLICATE
                            $this->db->insert('sentitems', $data);
                        } else {
                            //IGnore duplicate
                            $duplicate = $duplicate + 1;
                        }
                    }
                    //Update cost while removing dups
                    $this->spectrum->update('users', 'credits', 'credits-' . $credits->sms_cost * (sizeof($numbers) - $duplicate) * $msg_length, array('id' => $this->session->userdata('id')));

                    //TODO notifications
                    $this->data['flash'] = ['message' => sizeof($numbers) . ' Messages Sent to Queue', 'class' => 'success'];
                } else {
                    $this->data['flash'] = ['message' => 'Sorry, Insurficient funds', 'class' => 'danger'];
                }
            } else {
                $this->data['flash'] = ['message' => 'Sorry, Your profile SMS is not complete. please contact admin', 'class' => 'danger'];
            }
        } else { //per network
            $sum = 0;
            for ($i = 0; $i < sizeof($numbers); $i++) {
                $sum = $sum = $this->spectrum->getCostPerNetwork($numbers[$i], $this->session->userdata('id'));
            }
            if (($credits->credits + $credits->bonus) >= $sum) {
                $duplicate = 0;
                for ($i = 0; $i < sizeof($numbers); $i++) {
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
                        'charge' => $this->spectrum->getCostPerNetwork($numbers[$i], $this->session->userdata('id')),
                        'type' => 'SMS BULK',
                        'routeid' => $this->spectrum->getrouteSpecial($numbers[$i]),
                        'process' => 1);

                    if ($this->db->select('id')->from('sentitems')->where(array('message_id' => $message_id, 'receiver' => $numbers[$i]))->get()->num_rows() == 0) {

                        //DETECT DUPLICATE
                        $this->db->insert('sentitems', $data);
                    } else {
                        //IGnore duplicate
                        $duplicate = $duplicate + 1;
                    }

                    $this->spectrum->update('users', 'credits', 'credits-' . ($this->spectrum->getCostPerNetwork($numbers[$i], $this->session->userdata('id')) - $duplicate) * $msg_length, array('id' => $this->session->userdata('id')));
                }

                $this->data['flash'] = ['message' => 'Messages Sent to Queue', 'class' => 'success'];
            } else {
                $this->data['flash'] = ['message' => 'Sorry, Insurficient funds', 'class' => 'danger'];
            }
        }
        $this->load_view($this->data);
    }

    public function send_psms_bulk() {

        $this->data['view'] = 'backend/user/messages/scheduled_messages';
        $this->data['viewstatus'] = 'messages';

        $comma_separated_receivers = '';

        $contact_source = 0;

        $totalcharge = 0;
        $numbers = json_decode($_REQUEST['numbers']);
        $sender = $_REQUEST['sender'];
        $msg = trim($_REQUEST['msg']);


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

        //Default is beepsend calton
        $route = 17;

        $message_id = md5(date('Y-m-d H:i:s') . $sender);

        if ($credits->persms_flag == 1) {
            if ($credits->sms_cost > 0) {

                $routes = $this->db
                        ->select('*')
                        ->from('routes,routes_assigned')
                        ->where('routes_assigned.userid = ' . $this->session->userdata('id') . ' and routes_assigned.routeid = routes.id')
                        ->get()
                        ->result();

                $route = !empty($routes) ? $routes[rand(0, sizeof($routes) - 1)]->routeid : $route;

                if ((($credits->credits + $credits->bonus) / $credits->sms_cost) >= sizeof($numbers)) {
                    $duplicate = 0;
                    for ($i = 0; $i < sizeof($numbers); $i++) {

                        $msg = trim($numbers[$i]->message);
                        $msg_length = strlen($msg);

                        $msg_length = (int) ($msg_length / 160);
                        $msg_length++;

                        $data = array(
                            'datetime' => date('Y-m-d H:i:s'),
                            'date' => date('Y-m-d'),
                            'senderid' => $sender,
                            'receiver' => $numbers[$i]->number,
                            'message' => $msg,
                            'schedule_datetime' => $date,
                            'status' => 0,
                            'sender' => $this->session->userdata('id'),
                            'contacts' => sizeof($numbers),
                            'message_id' => $message_id,
                            'charge' => $credits->sms_cost,
                            'type' => 'SMS BULK',
                            'routeid' => $route,
                            'process' => 1);

                        if ($this->db->select('id')->from('sentitems')->where(array('message_id' => $message_id, 'receiver' => $numbers[$i]->number))->get()->num_rows() == 0) {

                            //DETECT DUPLICATE
                            $this->db->insert('sentitems', $data);
                        } else {
                            //IGnore duplicate
                            $duplicate = $duplicate + 1;
                        }
                    }
                    //Update cost
                    $this->spectrum->update('users', 'credits', 'credits-' . ($credits->sms_cost * sizeof($numbers) - $duplicate) * $msg_length, array('id' => $this->session->userdata('id')));

                    //TODO notifications
                    $this->data['flash'] = ['message' => 'Messages Sent to Queue', 'class' => 'success'];
                } else {
                    $this->data['flash'] = ['message' => 'Sorry, Insurficient funds', 'class' => 'danger'];
                }
            } else {
                $this->data['flash'] = ['message' => 'Sorry, Your profile SMS is not complete. please contact admin', 'class' => 'danger'];
            }
        } else { //per network
            $sum = 0;
            for ($i = 0; $i < sizeof($numbers); $i++) {
                $sum = $sum = $this->spectrum->getCostPerNetwork($numbers[$i]->number, $this->session->userdata('id'));
            }
            if (($credits->credits + $credits->bonus) >= $sum) {
                $duplicate =0;
                for ($i = 0; $i < sizeof($numbers); $i++) {

                    $msg = trim($numbers[$i]->message);
                    $msg_length = strlen($msg);
                    $msg_length = (int) ($msg_length / 160);
                    $msg_length++;

                    $data = array(
                        'datetime' => date('Y-m-d H:i:s'),
                        'date' => date('Y-m-d'),
                        'senderid' => $sender,
                        'receiver' => $numbers[$i]->number,
                        'message' => $msg,
                        'schedule_datetime' => $date,
                        'status' => 0,
                        'sender' => $this->session->userdata('id'),
                        'contacts' => sizeof($numbers),
                        'message_id' => $message_id,
                        'charge' => $this->spectrum->getCostPerNetwork($numbers[$i]->number, $this->session->userdata('id')),
                        'type' => 'SMS BULK',
                        'routeid' => $this->spectrum->getrouteSpecial($numbers[$i]->number),
                        'process' => 1);

                    if ($this->db->select('id')->from('sentitems')->where(array('message_id' => $message_id, 'receiver' => $numbers[$i]->number))->get()->num_rows() == 0) {

                        //DETECT DUPLICATE
                        $this->db->insert('sentitems', $data);
                    } else {
                        //IGnore duplicate
                        $duplicate = $duplicate + 1;
                    }
                    $this->spectrum->update('users', 'credits', 'credits-' . ($this->spectrum->getCostPerNetwork($numbers[$i]->number, $this->session->userdata('id'))-$duplicate) * $msg_length, array('id' => $this->session->userdata('id')));
                }

                $this->data['flash'] = ['message' => 'Messages Sent to Queue', 'class' => 'success'];
            } else {
                $this->data['flash'] = ['message' => 'Sorry, Insurficient funds', 'class' => 'danger'];
            }
        }

        $this->load_view($this->data);
    }

    public function delete_message($id = null) {

        $this->data['view'] = 'backend/user/messages/scheduled_messages';
        $this->data['viewstatus'] = 'messages';

        $sent_messages = $this->db->select('*,sentitems.id as sentitemid,routes.name as routename,sentitems.status as delivery_status,sum(sentitems.charge) as totalcharge')
                        ->from('sentitems,routes')
                        ->where('sentitems.routeid = routes.id and sentitems.message_id = "' . $id . '"')
                        ->where('sentitems.sender=' . $this->session->userdata('id'))
                        ->order_by('sentitems.id DESC')
                        ->group_by('sentitems.message_id')
                        ->get()->result();

        $this->db->where(array('message_id' => $id))->delete('sentitems');
        $this->spectrum->update('users', 'credits', 'credits+' . $sent_messages[0]->totalcharge, array('id' => $this->session->userdata('id')));

        $this->data['flash'] = ['message' => 'Message Deleted from Schedule and your account credit back with ' . $sent_messages[0]->totalcharge . ' USD', 'class' => 'success'];

        $this->load_view($this->data);
    }

    public function scheduled_messages() {
        $this->data['view'] = 'backend/user/messages/scheduled_messages';
        $this->data['viewstatus'] = 'messages';

        $this->data['page_obj'] = ['title' => 'Scheduled Messages', 'icon' => 'fa-clock-o'];
        $this->data['breadcrumbs'][] = ['url' => 'user_messages/messages', 'title' => 'Messages'];
        $this->data['breadcrumbs'][] = ['url' => 'user_messages/scheduled_messages', 'title' => 'Scheduled Messages'];

        $this->load_view($this->data);
    }

    public function message_details($id = null) {
        $this->data['message_id'] = $id;
        $this->data['view'] = 'backend/user/messages/sent-message-details';
        $this->data['viewstatus'] = 'messages';
        $this->load_view($this->data);
    }

    public function resend_sent_messages($id = null) {

        $sent_messages = $this->db->select('*,sentitems.id as sentitemid,routes.name as routename,sentitems.status as delivery_status')
                        ->from('sentitems,routes')
                        ->where('sentitems.routeid = routes.id and sentitems.message_id = "' . $id . '"')
                        ->where('sentitems.sender=' . $this->session->userdata('id'))
                        ->get()->result();

        $credits = $this->db
                ->select('credits, persms_flag, sms_cost, bonus')
                ->from('users')
                ->where(array('id' => $this->session->userdata('id')))
                ->get()
                ->result();
        $credits = $credits[0];


        $message_id = md5(date('Y-m-d H:i:s') . $sent_messages[0]->senderid);
        $date = (new Cake\I18n\Time())->format('Y-m-d H:i:s');

        $route = 17;

        if ($credits->persms_flag == 1) {
            if ($credits->sms_cost > 0) {

                $routes = $this->db
                        ->select('*')
                        ->from('routes,routes_assigned')
                        ->where('routes_assigned.userid = ' . $this->session->userdata('id') . ' and routes_assigned.routeid = routes.id')
                        ->get()
                        ->result();

                $route = !empty($routes) ? $routes[rand(0, sizeof($routes) - 1)]->routeid : $route;

                if ((($credits->credits + $credits->bonus) / $credits->sms_cost) >= sizeof($sent_messages)) {
                    //Update cost
                    //$this->db->update('sentitems', array('status' => 0), array('message_id' => $id, 'status' => 1));
                    for ($i = 0; $i < sizeof($sent_messages); $i++) {


                        $data = array(
                            'datetime' => date('Y-m-d H:i:s'),
                            'date' => date('Y-m-d'),
                            'senderid' => $sent_messages[$i]->senderid,
                            'receiver' => $sent_messages[$i]->receiver,
                            'message' => $sent_messages[$i]->message,
                            'schedule_datetime' => $date,
                            'status' => 0,
                            'sender' => $this->session->userdata('id'),
                            'contacts' => sizeof($sent_messages),
                            'message_id' => $message_id,
                            'charge' => $sent_messages[$i]->charge,
                            'type' => 'SMS BULK',
                            'routeid' => $route,
                            'process' => 1);

                        $this->db->insert('sentitems', $data);

                        $msg = $sent_messages[$i]->message;
                        $msg_length = strlen($msg);
                        $msg_length = (int) ($msg_length / 160);
                        $msg_length++;

                        $this->spectrum->update('users', 'credits', 'credits-' . $credits->sms_cost * $msg_length, array('id' => $this->session->userdata('id')));
                    }


                    //TODO notifications
                    $this->data['flash'] = ['message' => 'Messages Sent to Queue', 'class' => 'success'];
                } else {
                    $this->data['flash'] = ['message' => 'Sorry, Insurficient funds', 'class' => 'danger'];
                }
            } else {
                $this->data['flash'] = ['message' => 'Sorry, Your profile is not complete. please contact admin', 'class' => 'danger'];
            }
        } else { //per network
            $sum = 0;
            for ($i = 0; $i < sizeof($sent_messages); $i++) {
                $sum = $sum = $this->spectrum->getCostPerNetwork($sent_messages[$i]->receiver, $this->session->userdata('id'));
            }
            if (($credits->credits + $credits->bonus) >= $sum) {

                for ($i = 0; $i < sizeof($sent_messages); $i++) {
                    $data = array(
                        'datetime' => date('Y-m-d H:i:s'),
                        'date' => date('Y-m-d'),
                        'senderid' => $sent_messages[$i]->senderid,
                        'receiver' => $sent_messages[$i]->receiver,
                        'message' => $sent_messages[$i]->message,
                        'schedule_datetime' => $date,
                        'status' => 0,
                        'sender' => $this->session->userdata('id'),
                        'contacts' => sizeof($sent_messages),
                        'message_id' => $message_id,
                        'charge' => $this->spectrum->getCostPerNetwork($sent_messages[$i]->receiver, $this->session->userdata('id')),
                        'type' => 'SMS BULK',
                        'routeid' => $this->spectrum->getrouteSpecial($sent_messages[$i]->receiver),
                        'process' => 1);

                    $this->db->insert('sentitems', $data);

                    $msg = $sent_messages[$i]->message;
                    $msg_length = strlen($msg);
                    $msg_length = (int) ($msg_length / 160);
                    $msg_length++;


                    $this->spectrum->update('users', 'credits', 'credits-' . $this->spectrum->getCostPerNetwork($sent_messages[$i]->receiver, $this->session->userdata('id')) * $msg_length, array('id' => $this->session->userdata('id')));
                }

                $this->db->update('sentitems', array('status' => 0), array('message_id' => $id, 'status' => 1));
                $this->data['flash'] = ['message' => 'Messages Sent to Queue', 'class' => 'success'];
            } else {
                $this->data['flash'] = ['message' => 'Sorry, Insurficient funds', 'class' => 'danger'];
            }
        }

        redirect('user_messages/sent_messages', 'refresh');
    }

    public function resend_sent_one_message($id = null) {

        $sent_message = $this->db->select('*,sentitems.id as sentitemid,routes.name as routename,sentitems.status as delivery_status')
                        ->from('sentitems,routes')
                        ->where('sentitems.routeid = routes.id and sentitems.id = "' . $id . '"')
                        ->where('sentitems.sender=' . $this->session->userdata('id'))
                        ->get()->row();

        $credits = $this->db->select('credits, persms_flag, sms_cost, bonus')->from('users')->where(array('id' => $this->session->userdata('id')))->get()->result();
        $credits = $credits[0];

        $message_id = md5(date('Y-m-d H:i:s') . $sent_message->senderid);
        $date = (new Cake\I18n\Time())->format('Y-m-d H:i:s');

        $route = 17;

        if ($credits->persms_flag == 1) {
            if ($credits->sms_cost > 0) {

                $routes = $this->db
                        ->select('*')
                        ->from('routes,routes_assigned')
                        ->where('routes_assigned.userid = ' . $this->session->userdata('id') . ' and routes_assigned.routeid = routes.id')
                        ->get()
                        ->result();

                $route = !empty($routes) ? $routes[rand(0, sizeof($routes) - 1)]->routeid : $route;

                if ((($credits->credits + $credits->bonus) / $credits->sms_cost) >= 1) {

                    $data = array(
                        'datetime' => date('Y-m-d H:i:s'),
                        'date' => date('Y-m-d'),
                        'senderid' => $sent_message->senderid,
                        'receiver' => $sent_message->receiver,
                        'message' => $sent_message->message,
                        'schedule_datetime' => $date,
                        'status' => 0,
                        'sender' => $this->session->userdata('id'),
                        'contacts' => 1,
                        'message_id' => $message_id,
                        'charge' => $sent_message->charge,
                        'type' => 'SMS BULK',
                        'routeid' => $route,
                        'process' => 1);

                    $this->db->insert('sentitems', $data);
                    //Update cost
                    $msg = $sent_message->message;
                    $msg_length = strlen($msg);
                    $msg_length = (int) ($msg_length / 160);
                    $msg_length++;

                    $this->spectrum->update('users', 'credits', 'credits-' . $credits->sms_cost * $msg_length, array('id' => $this->session->userdata('id')));

                    //TODO notifications
                    $this->data['flash'] = ['message' => 'Messages Sent to Queue', 'class' => 'success'];
                } else {
                    $this->data['flash'] = ['message' => 'Sorry, Insurficient funds', 'class' => 'danger'];
                }
            } else {
                $this->data['flash'] = ['message' => 'Sorry, Your profile is not complete. please contact admin', 'class' => 'danger'];
            }
        } else { //per network
            $sum = 0;
            $sum = $sum = $this->spectrum->getCostPerNetwork($sent_message->receiver, $this->session->userdata('id'));

            if (($credits->credits + $credits->bonus) >= $sum) {

                $data = array(
                    'datetime' => date('Y-m-d H:i:s'),
                    'date' => date('Y-m-d'),
                    'senderid' => $sent_message->senderid,
                    'receiver' => $sent_message->receiver,
                    'message' => $sent_message->message,
                    'schedule_datetime' => $date,
                    'status' => 0,
                    'sender' => $this->session->userdata('id'),
                    'contacts' => 1,
                    'message_id' => $message_id,
                    'charge' => $this->spectrum->getCostPerNetwork($sent_message->receiver, $this->session->userdata('id')),
                    'type' => 'SMS BULK',
                    'routeid' => $this->spectrum->getrouteSpecial($sent_message->receiver),
                    'process' => 1);

                $this->db->insert('sentitems', $data);

                //Update cost
                $msg = $sent_message->message;
                $msg_length = strlen($msg);
                $msg_length = (int) ($msg_length / 160);
                $msg_length++;

                $this->spectrum->update('users', 'credits', 'credits-' . $this->spectrum->getCostPerNetwork($sent_message->receiver, $this->session->userdata('id')) * $msg_length, array('id' => $this->session->userdata('id')));

                $this->data['flash'] = ['message' => 'Messages Sent to Queue', 'class' => 'success'];
            } else {
                $this->data['flash'] = ['message' => 'Sorry, Insurficient funds', 'class' => 'danger'];
            }
        }

        redirect('user_messages/sent_messages', 'refresh');
    }

    public function personalize_sms($id = null) {
        if (isset($_POST['upload'])) {
            $this->data['view'] = 'backend/user/messages/personalised_sms';
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
        }

        if (strlen($this->session->userdata('file_name')) > 0) {

            $this->data['message'] = $this->uploaded_personalized;
        } else {
            redirect('user_messages/send_personalised_sms', 'refresh');
        }
        $this->load_view($this->data);
    }

    public function send_bulk_sms() {
        $this->data['view'] = 'backend/user/messages/send-bulk-sms';
        $this->data['viewstatus'] = 'messages';

        $this->data['page_obj'] = ['title' => 'Send Bulk SMS', 'icon' => 'fa-comments-o'];
        $this->data['breadcrumbs'][] = ['url' => 'user_messages/messages', 'title' => 'Messages'];
        $this->data['breadcrumbs'][] = ['url' => 'user_messages/send_bulk_sms', 'title' => 'Send Bulk SMS'];

        $this->load_view($this->data);
    }

    public function send_personalised_sms() {
        $this->data['view'] = 'backend/user/messages/send_personalised_sms';
        $this->data['viewstatus'] = 'messages';

        $this->data['page_obj'] = ['title' => 'Send Personalised SMS', 'icon' => 'fa-comment-o'];
        $this->data['breadcrumbs'][] = ['url' => 'user_messages/messages', 'title' => 'Messages'];
        $this->data['breadcrumbs'][] = ['url' => 'user_messages/send_personalised_sms', 'title' => 'Send Personalised SMS'];

        $this->load_view($this->data);
    }

    public function personalised_sms() {
        $this->data['view'] = 'backend/user/messages/personalised_sms';
        $this->data['viewstatus'] = 'messages';
        $this->load_view($this->data);
    }

    public function messages() {

        $this->data['view'] = 'backend/user/messages/messages_container';
        $this->data['viewstatus'] = 'messages';

        $this->data['page_obj'] = ['title' => 'Messages', 'icon' => 'fa-comments-o'];
        $this->data['breadcrumbs'][] = ['url' => 'User_messages', 'title' => 'Messages'];

        $this->load_view($this->data);
    }

    public function sent_messages() {

        $this->data['view'] = 'backend/user/messages/sent_messages';
        $this->data['viewstatus'] = 'messages';

        $this->data['page_obj'] = ['title' => 'Sent Messages', 'icon' => 'fa-list'];
        $this->data['breadcrumbs'][] = ['url' => 'user_messages/messages', 'title' => 'Messages'];
        $this->data['breadcrumbs'][] = ['url' => 'user_messages/sent_messages', 'title' => 'Sent Messages'];

        $this->load_view($this->data);
    }

    public function messageUsage($method = null) {
        $this->data['view'] = 'messageUsage';
        $this->data['sublink'] = 'Manage_account_settings';
        $this->data['details'] = 'Token Search results';

        $this->load_view($this->data);
    }

    public function downloads($method = null) {

        $this->data['view'] = 'downloads';
        $this->data['sublink'] = 'Manage_account_settings';
        $this->data['details'] = 'Token Search results';

        $this->load_view($this->data);
    }

    public function download_message($messageid = null) {
        $header = '#,Sent Time,Schedule time,SenderID,Message,Receiver,Status,Charge';
        $data = '';
        $count = 1;
        foreach ($this->db->select('*')->from('sentitems')->where(array('message_id' => $messageid, 'sender' => $this->session->userdata('id')))->order_by('id')->get()->result() as $rows) {

            $line = '"' . $count . '"' . "," . '"' . $rows->datetime . '"' . "," . '"' . $rows->schedule_datetime . '"' . "," . '"' . $rows->senderid . '"' . "," .
                    '"' . $rows->message . '"' . "," .
                    '"' . $rows->receiver . '"' . "," . '"' . ($rows->status == 1 ? 'SUCCEEDED' : ($rows->status == 0 ? 'PENDING' : 'FAILED')) . '"' . "," . '"' . $rows->charge . '"' . ",";
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
        foreach ($this->db->select('*')->from('sentitems')->where(array('message_id' => $messageid, 'sender' => $this->session->userdata('id')))->order_by('id')->get()->result() as $rows) {

            $line = '"' . $count . '"' . "," . '"' . $rows->datetime . '"' . "," . '"' . $rows->schedule_datetime . '"' . "," . '"' . $rows->senderid . '"' . "," .
                    '"' . $rows->message . '"' . "," .
                    '"' . $rows->receiver . '"' . "," . '"' . ($rows->status == 1 ? 'SUCCEEDED' : ($rows->status == 0 ? 'PENDING' : 'FAILED')) . '"' . "," . '"' . $rows->charge . '"' . ",";
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
