<?php

error_reporting(E_ALL);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sms extends Spectrum_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function send() {


        if (isset($_REQUEST['sender']) && isset($_REQUEST['contacts']) && isset($_REQUEST['message']) && isset($_REQUEST['client_secret']) && isset($_REQUEST['client_id'])) {
            $sender = $_REQUEST['sender'];
            $msg = $_REQUEST['message'];

            $numbers = explode(',', $_REQUEST['contacts']);

            $date = '';

            if (isset($_REQUEST['schedule_datetime'])) {
                if ($_REQUEST['schedule_datetime'] == "") {
                    $date = (new Cake\I18n\Time())->format('Y-m-d H:i:s');
                } else {
                    $date = (new Cake\I18n\Time($_REQUEST['schedule_datetime']))->format('Y-m-d H:i:s');
                }
            } else {
                $date = (new Cake\I18n\Time())->format('Y-m-d H:i:s');
            }


            $credits = $this->db
                    ->select('users.credits as credits, users.persms_flag as persms_flag ,users.sms_cost as sms_cost ,users.bonus as bonus,oauth_clients.user_id as user_id')
                    ->from('users,oauth_clients')
                    ->where('oauth_clients.user_id=users.id and oauth_clients.client_id="' . $_REQUEST['client_id'] . '" and oauth_clients.client_secret="' . $_REQUEST['client_secret'] . '"')
                    ->get()
                    ->result();
            $credits = $credits[0];
            //Default is beepsend calton
            $route = 17;
            $message_id = md5(date('Y-m-d H:i:s') . $sender);


            if ($credits->persms_flag == 1) {
                if ($credits->sms_cost > 0) {

                    $routes = $this->db
                            ->select('*')
                            ->from('routes,routes_assigned')
                            ->where('routes_assigned.userid = ' . $credits->user_id . ' and routes_assigned.routeid = routes.id')
                            ->get()
                            ->result();

                    $route = !empty($routes) ? $routes[rand(0, sizeof($routes) - 1)]->routeid : $route;

                    if ((($credits->credits + $credits->bonus) / $credits->sms_cost) >= sizeof($numbers)) {

                        for ($i = 0; $i < sizeof($numbers); $i++) {
                            $data = array(
                                'datetime' => date('Y-m-d H:i:s'),
                                'date' => date('Y-m-d'),
                                'senderid' => $sender,
                                'receiver' => $numbers[$i],
                                'message' => $msg,
                                'schedule_datetime' => $date,
                                'status' => 0,
                                'sender' => $credits->user_id,
                                'contacts' => sizeof($numbers),
                                'message_id' => $message_id,
                                'charge' => $credits->sms_cost,
                                'type' => 'API SMS BULK',
                                'routeid' => $route,
                                'process' => 1);

                            $this->db->insert('sentitems', $data);
                        }
                        //Update cost
                        $this->spectrum->update('users', 'credits', 'credits-' . $credits->sms_cost * sizeof($numbers), array('id' => $credits->user_id));

                        //TODO notifications
                        echo json_encode(array('code' => '1024', 'message' => ' Messages Sent to Queue', 'count' => sizeof($numbers)));
                    } else {
                        echo json_encode(array('code' => '1026', 'message' => 'Sorry, Insurficient funds'));
                    }
                } else {
                    echo json_encode(array('code' => '1027', 'message' => 'Sorry, Your profile SMS is not complete. please contact admin'));
                }
            } else { //per network
                $sum = 0;
                for ($i = 0; $i < sizeof($numbers); $i++) {
                    $sum = $sum = $this->spectrum->getCostPerNetwork($numbers[$i], $credits->user_id);
                }
                if (($credits->credits + $credits->bonus) >= $sum) {
                    for ($i = 0; $i < sizeof($numbers); $i++) {
                        $data = array(
                            'datetime' => date('Y-m-d H:i:s'),
                            'date' => date('Y-m-d'),
                            'senderid' => $sender,
                            'receiver' => $numbers[$i],
                            'message' => $msg,
                            'schedule_datetime' => $date,
                            'status' => 0,
                            'sender' => $credits->user_id,
                            'contacts' => sizeof($numbers),
                            'message_id' => $message_id,
                            'charge' => $this->spectrum->getCostPerNetwork($numbers[$i], $credits->user_id),
                            'type' => 'OLD API SMS BULK',
                            'routeid' => $this->spectrum->getrouteSpecial($numbers[$i]),
                            'process' => 1);

                        $this->db->insert('sentitems', $data);
                        $this->spectrum->update('users', 'credits', 'credits-' . $this->spectrum->getCostPerNetwork($numbers[$i], $credits->user_id), array('id' => $credits->user_id));
                    }

                    echo json_encode(array('code' => '1024', 'message' => ' Messages Sent to Queue', 'count' => sizeof($numbers)));
                } else {
                    echo json_encode(array('code' => '1026', 'message' => 'Sorry, Insurficient funds'));
                }
            }
        } else {
            echo json_encode(array('code' => '1025', 'message' => 'Failed to send SMS to process Queue! Parameters not provided'));
        }
    }

    public function login() {


        if (isset($_REQUEST['username']) && isset($_REQUEST['password'])) {

            $users = $this->db->select('*')
                    ->from('users')
                    ->where('email = "' . $_REQUEST['username'] . '" and password = "' . sha1($_REQUEST['password']) . '"')
                    ->get()
                    ->result();

            $user = $users[0];
            if (!empty($user)) {

                echo json_encode(array('code' => '1029', 'message' => 'Login Success', 'Account' => $user));
            } else {
                echo json_encode(array('code' => '1030', 'message' => 'Login Failed'));
            }
        } else {
            echo json_encode(array('code' => '1025', 'message' => 'Failed to send SMS to process Queue! Parameters not provided'));
        }
    }

}
