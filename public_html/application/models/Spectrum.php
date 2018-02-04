<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php

use Cake\Core\Configure;

class Spectrum extends CI_Model {

    function __construct() {
        //$this->load->database();
    }

    public function update($table, $attributes, $values, $where) {
        $this->db->where($where);
        $this->db->set($attributes, $values, FALSE);
        $this->db->update($table);
    }

    public function getamount($network_id, $mobile) {

        $data = $this->db->select('amount')->from('rate_per_network')->where('userid = "' . $mobile . '" and network_id = "' . $network_id . '"')->get()->result();

        return !empty($data) ? $data[0]->amount : "";
    }

    public function save_email($email_title, $email_body, $list_id, $user_id, $banner_url, $uuid, $email_from) {
        $created_date = date('Y-m-d H:i:s');



        $values = array('email_title' => $email_title,
            'email_body' => $email_body,
            'email_recipients' => $list_id,
            'sent_by' => $user_id,
            'email_template' => 1,
            'email_banner' => $banner_url,
            'uuid' => $uuid,
            'created_at' => $created_date,
            'email_from' => $email_from,
            'charge' => ($this->session->userdata('dept') == 'admin' ? 0 : 1));

        $this->db->insert('email_outbox', $values);



        return $uuid;
    }

    public function getCostPerNetwork($mobile, $account) {
        $substring = substr($mobile, 0, 5);
        $data = $this->db->select('amount')
                        ->from('rate_per_network')
                        ->where('userid = "' . $account . '" and prefix = "' . $substring . '"')->get()->result();
        return $data[0]->amount;
    }

    public function bulk_sms_email($data = null) {
        /* $data = array('senderid'=>$sender,
          'contacts'=>sizeof($numbers),
          'message'=>$msg,
          'totalcharge'=>$totalcharge,
          'email'=>$email); */

        $url = 'http://spectrumug.com/bulk_sms_notifications.php';

        $options = array
            (
            CURLOPT_URL => $url,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $data,
        );

        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);

        if (!$response) {
            $response = curl_error($curl);
        }
        curl_close($curl);
    }

    public function send_notification($data = null) {
        /* $data = array('senderid'=>$sender,
          'contacts'=>sizeof($numbers),
          'message'=>$msg,
          'totalcharge'=>$totalcharge,
          'email'=>$email); */

        $url = base_url() . 'notifications.php';

        $options = array
            (
            CURLOPT_URL => $url,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $data,
        );

        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);

        if (!$response) {
            $response = curl_error($curl);
        }
        curl_close($curl);
    }

    public function getcodes() {
        $codes = array();
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        srand((double) microtime() * 1000000);
        $i = 0;
        $code = '';
        $serial = '';

        $i = 0;

        while ($i < 10) {
            $num = rand() % 36;
            $tmp = substr($chars, $num, 1);
            $serial = $serial . $tmp;
            $i++;
        }

        return $serial;
    }

    public function send($sender, $number, $msg) {
        $prefix = substr($number, 0, 5);
        $rst = mysql_fetch_array($this->select('networks n, routes r', 'r.parameters', 'where r.idroutes = n.routeid and prefix = "' . $prefix . '"'));

        $route = str_replace("#SENDER", urlencode($sender), $rst['parameters']);
        $route = str_replace("#DESTINATION", urlencode($number), $route);
        $route = str_replace("#MESSAGE", urlencode($msg), $route);

        file($route);
        echo 'SUCCEEDED';

        //print_r($route);
    }

    public function getroute($sender, $number) {



        $number = substr($number, 0, 5);



        $rst = $this->db->select('r.routeid, n.cost, u.sms_cost')
                ->from('networks n, routes_assigned r, users u')
                ->where('r.routeid = n.routeid  and n.prefix =  "' . $number . '" and r.userid = "' . $sender . '" and u.id = "' . $sender . '"')
                ->limit(1, 0)
                ->get()
                ->result();

        $data['routeid'] = $rst[0]->routeid;
        $data['cost'] = $rst[0]->cost;
        $data['sms_cost'] = $rst[0]->sms_cost;

        return $data;
    }

    public function getrouteSpecial($number) {
        $number = substr($number, 0, 5);

        $rst = $this->db->select('routeid')->from('networks')->where(array('prefix' => $number))->limit(1, 0)->get()->result();

        return $rst[0]->routeid;
    }

    public function post($data, $url) {

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

    public function prepare_send($sender, $number, $msg, $route) {
        $msg = str_replace('\'', "'", $msg);
        $data = array();

        $route_params = $this->db->select('route_parameters.*,routes.method,routes.url')
                ->from('routes, route_parameters')
                ->where('routes.id = ' . $route->id . ' and routes.id = route_parameters.routeid')
                ->group_by('route_parameters.id')
                ->get()
                ->result();

        foreach ($route_params as $route_param) {

            switch ($route_param->parameter_name) {
                default:
                    $data[$route_param->parameter] = $route_param->value;
                    break;

                case 'senderid':
                    $data[$route_param->parameter] = $sender;
                    break;
                case 'message':
                    $data[$route_param->parameter] = $msg;
                    break;
                case 'destination':
                    $data[$route_param->parameter] = $number;

                    break;
            }
        }

        return ['method' => $route->method, 'message' => $msg, 'data' => $data, 'url' => $route->url];
    }

    public function send_calton_mobile($sender, $number, $msg, $route) {
        $success = FALSE;

        $prep_send = $this->prepare_send($sender, $number, $msg, $route);

        if ($prep_send['method'] == 'GET') {
            $respose = file($prep_send['url'] . '?' . http_build_query($prep_send['data']));
            if (isset($respose)) {

                $success = $respose[0] == 400 ? TRUE : FALSE;
            }
        } else {

            $respose = $this->post($prep_send['data'], $prep_send['url']);
        }

        return [$success,$respose];
    }

    public function send_beep_send_legacy($sender, $number, $msg, $route) {

        $success = FALSE;

        $prep_send = $this->prepare_send($sender, $number, $msg, $route);

        if ($prep_send['method'] == 'GET') {
            $respose = file($prep_send['url'] . '?' . http_build_query($prep_send['data']));
            if (isset($respose)) {
                $success = (($respose[0] >= 1024 && $respose[0] <= 1037) || $respose[0] == 255) ? FALSE : TRUE;
            }
        } else {

            $respose = $this->post($prep_send['data'], $prep_send['url']);
        }

        return $success;
    }

    public function smpp_v2($sender, $number, $msg, $route) {

        $success = 0;

        $prep_send = $this->prepare_send($sender, $number, $msg, $route);

        if ($prep_send['method'] == 'GET') {
            $respose = file($prep_send['url'] . '?' . http_build_query($prep_send['data']));
            if (isset($respose)) {
                $success = trim($respose[0]) == "RECIEVED FOR PROCESSING" ? 1 : 0;
            }
        } else {

            $respose = $this->post($prep_send['data'], $prep_send['url']);
        }

        return array('status'=>$success, 'response'=>trim($respose[0]));
    }

    public function send_beepsend_rest_send($sender, $numbers, $msg, $route) {

        $success = FALSE;

        $prep_send = $this->prepare_send($sender, $numbers, $msg, $route);


        $beepsend_client = new Beepsend\Client($prep_send['data']['token']);

        $connection = $beepsend_client->connection->get();

        // print_r($connection);

        $message = $beepsend_client->message->send(
                $numbers, $sender, $msg, $connection['id'], $prep_send['data']['encoding']
        );

        //print_r($message);

        return $success;
    }

    public function send_beepsend_rest_multiple($from, $sch_messages, $msg, $route) {

        $success = FALSE;

        $prep_send = $this->prepare_send($from, [], $msg, $route);


        $beepsend_client = new Beepsend\Client($prep_send['data']['token']);

        $connection = $beepsend_client->connection->get();

        $number_counter = 0;
        $to = [];

        foreach ($sch_messages as $sch_message):
            $to[$number_counter++] = $sch_message->receiver;
        endforeach;

        $respose = $beepsend_client->message->sendouts([], $to, $from, $msg, $connection['id'], $prep_send['data']['encoding']);

        if (isset($respose)):
            $success = ($respose[0] == $connection['id']) ? FALSE : TRUE;
        #update message with beepsend id  
        #foreach ($sch_messages as $sch_message):                           
        #$this->db->update('sentitems', array('beepsend_id' => print_r($respose,true)), 'id = "' . $sch_message->id . '"');
        #endforeach;
        endif;

        return $success;
    }

    public function send_smpp($from, $to, $msg, $tx) {

        $success = FALSE;

        if ($tx->sendSMSLong($from, $to, $msg)) {
            $success = true;
        }
        /* bind to smpp server */
        //$smpp->Start($prep_send['data']['ipaddress'], $prep_send['data']['port'], $prep_send['data']['username'], $prep_send['data']['password'], $prep_send['data']['systemtype']);
        /* send enquire link PDU to smpp server */
        //$smpp->TestLink();
        /* send single message; large messages are automatically split */
        //$response = $smpp->SendMulti($number_string, $msg);
        /* unbind from smpp server */
        // $smpp->End();

        return $success;
    }

    public function send_routesms($sch_message,$sender, $number, $msg, $route) {

        $success = FALSE;

        $prep_send = $this->prepare_send($sender, $number, $msg, $route);

        if ($prep_send['method'] == 'GET') {
            $response = file($prep_send['url'] . '?' . http_build_query($prep_send['data']));
            
            $data  = explode("|", $response[0]);
            
            if (isset($data)) {
                $success = $data[0] == 1701 ? TRUE : FALSE;
                $this->db->update('sentitems', array('routesms_id' => $data[2] ), 'id = ' . $sch_message->id);
            }
        } else {

            $response = $this->post($prep_send['data'], $prep_send['url']);
        }

        return [$success, $response];
    }

    public function sendSpecial($sender, $number, $msg, $routeid) {

        $msg = str_replace('\'', "'", $msg);
        $success = false;



        $route_details = $this->db->select('route_parameters.*,routes.method,routes.url')
                ->from('routes, route_parameters')
                ->where('routes.id = ' . $routeid . ' and routes.id = route_parameters.routeid')
                ->group_by('route_parameters.id')
                ->get()
                ->result();

        $data = array();

        $method = '';
        $url = '';

        // print_r($route_details);

        $parameters = '';


        foreach ($route_details as $rows) {
            $url = $rows->url;
            $method = $rows->method;

            if ($rows->method == 'GET') {

                switch ($rows->parameter_name) {
                    default:
                        $data[$rows->parameter] = $rows->value;
                        break;

                    case 'senderid':
                        $data[$rows->parameter] = $sender;
                        break;
                    case 'message':
                        $data[$rows->parameter] = $msg;
                        break;
                    case 'destination':
                        $data[$rows->parameter] = $number;

                        break;
                }
            } else {


                switch ($rows->parameter_name) {
                    default:
                        $data[$rows->parameter] = $rows->value;
                        break;

                    case 'senderid':
                        $data[$rows->parameter] = $sender;
                        break;
                    case 'message':
                        $data[$rows->parameter] = $msg;
                        break;
                    case 'destination':
                        $data[$rows->parameter] = $number;

                        break;
                }
            }
        }


        //   print_r($data);

        if ($method == 'GET') {
            $respose = file($url . '?' . http_build_query($data));
            echo print_r($respose);
        } else {

            $respose = $this->post($data, $url);
        }

        return $success;
    }

    public function SendEmail($email, $email_subject, $title, $receiver_name, $content) {
        //file('../mailer.php?email='.urlencode($email).'&email_subject='.urlencode($email_subject).'&receiver_name='.urlencode($receiver_name).'&content='.urlencode($content).'');


        $email_sender = 'sales@caltonmobile.com';
        $email_to = $email;
        $email_subject = $email_subject;


        $message = '<div style=" color:blue; padding:10px; 	font-family:\'lucida grande\',tahoma,verdana,arial,sans-serif; font-family:Arial, Helvetica, sans-serif; font-size:18px; margin:-10px; ">

<h1>Calton Mobile</h1> </div>
<div style="margin-top:20px; margin-left:auto; margin-right:auto; width:800px; min-height:400px; padding:20px;  ">

<h2>
' . $title . '
</h2>


<div style="margin:0 auto; padding:40px; width:100%;">
Dear ' . $receiver_name . ',
<br>
<p>' . $content . '</p>

</div>

<p><h3>Protect Your Password</h3>
     Calton Mobile staff will <strong>NEVER</strong> ask you for your password via email. The only places you are asked for your password are when you sign in to on our website if you want to buy something or check your account. You will always sign in via a secure connection, and we ask you to ensure that the address in your browser begins exactly like this http://www.caltonmobile.com. </p>
    <p> Be alert to emails that request account information or urgent action. Be cautious of websites with irregular addresses.</p>

</div>






<style>
body{
	background:#f1f1f1;
}


</style>';

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: ' . $email_sender . "\r\n";
        //print_r($message);
        mail($email_to, $email_subject, $message, $headers);
    }

    public function receipt($email, $email_subject, $receiver_name, $price, $item, $qty) {


        $email_sender = 'sales@caltonmobile.com';
        $email_to = $email;
        $email_subject = $email_subject;

        $name = $receiver_name;


        $message = '<div style="padding:20px; margin:20px;  border:5px solid blue;">
<table width="100%" border="0" style="font-size:12px;">
  <tr>
    <td width="22%" height="120">&nbsp;</td>
    <td width="7%">&nbsp;</td>
    <td width="24%">&nbsp;</td>
    <td width="14%">&nbsp;</td>
    <td width="33%">
    <span style="font-size:12px;">
    <strong>Calton Mobile</strong><br>

Email: sales@caltonmobile.com<br>
</span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Name : </td>
    <td>' . $name . '</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Date : </td>
    <td>' . date('Y-m-d H:i:s') . '</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Receipt :</td>
    <td>#' . rand() . '</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3"><p>Dear ' . $name . ',<br> Thank you for buying from Calton Mobile.</p></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3">

    <table width="90%" border="1" cellspacing="0" style="margin:20px; font-size:13px; ">
      <tr style=" border:1px solid #000; text-align:center;">
        <td width="25%" ><strong>Item</strong></td>
        <td width="33%"><strong>Quantity </strong></td>
        <td width="42%"><strong>Price (USD)</strong></td>
      </tr>
      <tr>
        <td align="center">' . $item . '</td>
        <td align="center">' . number_format($qty) . '</td>
        <td align="center">' . number_format($price) . '</td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
        <td align="center">' . number_format($price) . '</td>
      </tr>
    </table></td>
    <td>&nbsp;</td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td colspan="3"><center>This is an auto generated receipt</center></td>
    <td>&nbsp;</td>
  </tr>
 <tr>
    <td>&nbsp;</td>
    <td colspan="4" rowspan="3">
	<h2>For More details, contact us on the address Below</h2>
	 <span style="font-size:12px;">

      <strong>Calton Mobile</strong><br>

      Email: sales@caltonmobile.com<br>
</span></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</div>
<style>
body{
background:#fff;
font-size:11px;
}
.td{
	 border:1px solid #000; text-align:center;"
}
</style>';

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: ' . $email_sender . "\r\n";

        mail($email_to, $email_subject, $message, $headers);
    }

    public function notification($email, $email_subject, $receiver_name, $date, $sender_mobile) {


        $email_sender = 'sales@caltonmobile.com';
        $email_to = $email;
        $email_subject = $email_subject;

        $name = $receiver_name;


        $message = '<div style="padding:20px; margin:20px;  border:5px solid blue;">
<table width="100%" border="0" style="font-size:12px;">
  <tr>
    <td width="22%" height="120">&nbsp;</td>
    <td width="7%">&nbsp;</td>
    <td width="24%">&nbsp;</td>
    <td width="14%">&nbsp;</td>
    <td width="33%">
    <span style="font-size:12px;">
    <strong>Calton Mobile</strong><br>

Email: sales@caltonmobile.com<br>
</span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Name : </td>
    <td>' . $name . '</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Date : </td>
    <td>' . date('Y-m-d H:i:s') . '</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td colspan="3"><p>Dear ' . $name . ',<br> Thank you for using Calton Mobile.</p></td>
    <td>&nbsp;</td>
  </tr>
  <tr>

    <td colspan="5">

    <table width="100%" border="1" cellspacing="0" style=" font-size:13px; ">';
        $message .=
                '<tr style="  text-align:center;">
              <td  ><strong>DateTime</strong></td>
              <td ><strong>Message </strong></td>
              <td ><strong>Sender ID</strong></td>
                   <td ><strong>Receipients</strong></td>

            </tr>';
        $rst = $this->select('sentitems', '*', 'where date = "' . $date . '" and sender = "' . $sender_mobile . '" group by message_id');


        while ($rows = mysql_fetch_array($rst)) {
            $message .= '
      <tr>
        <td align="center">' . $rows['datetime'] . '</td>
        <td align="center">' . $rows['message'] . '</td>
        <td align="center">' . $rows['senderid'] . '</td>
		    <td align="center">' . number_format($rows['contacts']) . '</td>

      </tr>';
        }






        $message .= '

    </table></td>

  </tr>
    <tr>
    <td>&nbsp;</td>
    <td colspan="3"><center>This is an auto generated Notification</center></td>
    <td>&nbsp;</td>
  </tr>
 <tr>
    <td>&nbsp;</td>
    <td colspan="4" rowspan="3">
	<h2>For More details, contact us on the address Below</h2>
	 <span style="font-size:12px;">

      <strong>Calton Mobile</strong><br>

      Email: sales@caltonmobile.com<br>
	  Tel : +256 703 407 714
</span></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</div>
<style>
body{
background:#fff;
font-size:11px;
}
.td{
	 border:1px solid #000; text-align:center;"
}
</style>';

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: ' . $email_sender . "\r\n";

        mail($email_to, $email_subject, $message, $headers);
    }

}

?>