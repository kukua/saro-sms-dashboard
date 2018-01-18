<?php

error_reporting(E_ALL);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sms extends Spectrum_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function send() {
        $key = '-----BEGIN RSA PRIVATE KEY-----
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
-----END RSA PRIVATE KEY-----';

        if (openssl_private_decrypt(base64_decode($_REQUEST['data']), $decrypted, $key)) {

            $formdata = json_decode($decrypted);

            $idusers = '';
            $mobile = '';
            $destination = '';
            $rate = 0;
            $charge = 0;

            if (isset($formdata->from) && isset($formdata->to) && isset($formdata->message) && isset($formdata->client_id) && isset($formdata->client_secret) && isset($formdata->grant_type)) {

                $sender = $formdata->from;
                $msg = $formdata->message;
                $numbers = explode(',', $formdata->to);

                $date = '';

                if (isset($formdata->schedule_datetime)) {
                    if ($formdata->schedule_datetime == "") {
                        $date = (new Cake\I18n\Time())->format('Y-m-d H:i:s');
                    } else {
                        $date = (new Cake\I18n\Time($formdata->schedule_datetime))->format('Y-m-d H:i:s');
                    }
                }else{
                      $date = (new Cake\I18n\Time())->format('Y-m-d H:i:s');
                }

                $credits = $this->db
                        ->select('users.credits as credits, users.persms_flag as persms_flag ,users.sms_cost as sms_cost ,users.bonus as bonus,oauth_clients.user_id as user_id')
                        ->from('users,oauth_clients')
                        ->where('oauth_clients.user_id=users.id and oauth_clients.client_id="' . $formdata->client_id . '" and oauth_clients.client_secret="' . $formdata->client_secret . '"')
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
                                'type' => 'API SMS BULK',
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
        } else {
            echo json_encode(array('code' => '1028', 'message' => 'Sorry, You need to encrypt your data'));
        }
    }

}
