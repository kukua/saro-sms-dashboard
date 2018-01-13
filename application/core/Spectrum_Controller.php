<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Spectrum_Controller extends CI_Controller {

    public $username = "";
    public $password = "";
    public $view_data = array();
    public $header = "";
    public $data_report = "";
    public $title = "";
    public $data = array();
    public $server = [];

    function __construct() {
        parent::__construct();
        //$this->load->library(array('form_validation','session','excel/Spreadsheet_Excel_Reader','yoapi','pdf'));
        $this->load->library(array('form_validation', 'session', 'spreadsheet_excel_reader', 'Spectrum_Func', 'smpp', 'Routesms'));
        $this->load->helper(array('form', 'url', 'html', 'inflector', 'file'));
        $this->load->database();
        $this->load->model('spectrum');

        $this->data['start_date'] = (new DateTime('first day of this month'))->format('Y-m-d');
        $this->data['end_date'] = (new DateTime('last day of this month'))->format('Y-m-d');

        $this->data['start_date_year'] = (new DateTime('first day of January this year'))->format('Y-m-d');
        $this->data['end_date_year'] = (new DateTime('last day of December this year'))->format('Y-m-d');

        $this->data['start_date_five_year'] = strtotime((new DateTime('first day of January this year'))->format('Y-m-d') . ' -5 year');

        $this->data['last_month_start_date'] = (new DateTime('first day of last month'))->format('Y-m-d');
        $this->data['last_month_end_date'] = (new DateTime('last day of last month'))->format('Y-m-d');


        $this->data['breadcrumbs'] = [];
    }

    public function sms_notification($mobile, $sender, $msg) {

        try {
            $msg = trim($msg);


            $date = (new Cake\I18n\Time())->format('Y-m-d H:i:s');


            $message_id = md5(date('Y-m-d H:i:s') . $sender);

            $route = '17';

            $data = array(
                'datetime' => date('Y-m-d H:i:s'),
                'date' => date('Y-m-d'),
                'senderid' => $sender,
                'receiver' => $mobile,
                'message' => $msg,
                'schedule_datetime' => $date,
                'status' => 0,
                'sender' => $this->session->userdata('id'),
                'contacts' => 1,
                'message_id' => $message_id,
                'type' => 'NOTIFICATION BULK',
                'routeid' => $route,
                'process' => 1);

            $this->db->insert('sentitems', $data);
        } catch (Exception $exc) {
            $this->data['flash'] = ['message' => 'Failed to send SMS to Queue', 'class' => 'danger'];
            $this->notify("Failed to Send Notification SMS", $exc->getTraceAsString(), "Notification SMS", "Notification SMS");
        }
    }
   function email_notification($subject, $message, $to) {

        date_default_timezone_set('Africa/Nairobi');

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
        $mail->Host = 'spectrumconnect.ug';
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
        $mail->Username = "info@spectrumconnect.ug";
        //Password to use for SMTP authentication
        $mail->Password = "spectrum@6";
        //Set who the message is to be sent from
        $mail->setFrom("info@spectrumconnect.ug", "Kukua B V");
        //Set who the message is to be sent to
        $mail->addAddress($to);
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
    function notify($subject, $message, $from, $fulllname) {

        date_default_timezone_set('Africa/Nairobi');

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
        $mail->Host = 'spectrumconnect.ug';
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
        $mail->Username = "noreply@spectrumconnect.ug";
        //Password to use for SMTP authentication
        $mail->Password = "spectrum@6";
        //Set who the message is to be sent from
        $mail->setFrom($from, $fulllname);
        //Set who the message is to be sent to
        $mail->addAddress("bugs@spectrumconnect.ug");
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

    function getGUID() {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double) microtime() * 10000); //optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45); // "-"
            $uuid = substr($charid, 0, 8) . $hyphen
                    . substr($charid, 8, 4) . $hyphen
                    . substr($charid, 12, 4) . $hyphen
                    . substr($charid, 16, 4) . $hyphen
                    . substr($charid, 20, 12);
            return $uuid;
        }
    }

    public function reformat_date($date) {
        $str = explode(' ', $date);

        $date = $str[0];
        $hours = $str[1];

        $splitted_date = explode('-', $date);

        $newdate = $splitted_date[2] . '-' . $splitted_date[1] . '-' . str_pad($splitted_date[0], 2, "0", STR_PAD_LEFT);

        return $newdate . ' ' . $hours;
    }

    public function isloggedin() {
        return $this->session->userdata('id') == null ? false : true;
    }

    public function change_destination($destination) {
        echo '<META http-equiv=refresh content=0;URL=../' . $destination . '>';
    }

    public function access_control($controller) {
        if ($this->isloggedin()) {



            return $this->db->select('id')
                            ->from('access')
                            ->where(array(
                                'userid' => $this->session->userdata('id'),
                                'controller' => $controller, 'role' => 'view'))
                            ->get()
                            ->num_rows() > 0 || $this->session->userdata('dept') == 'admin' ? true : false;
        } else {


            $this->logout();
        }
    }

    public function sendemail($subject, $to, $from, $message) {


        $data = array('to' => $to, 'from' => $from, 'subject' => $subject, 'message' => $message);

        $options = array
            (
            CURLOPT_URL => 'http://adrenalinwarehouse.com/emailer.php',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => 1
        );

        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);

        //  print_r($response);
        if (!$response) {
            $response = curl_error($curl);
        }
        curl_close($curl);
    }

    public function getToken($number = 10) {

        $chars = "0123456789";
        srand((double) microtime() * 10000000);

        $serial = '';

        $i = 0;

        while ($i < $number) {
            $num = rand() % 10;
            $tmp = substr($chars, $num, 1);
            $serial = $serial . $tmp;
            $i++;
        }

        return $serial;
    }

    public function load_view($data) {
        if ($this->isloggedin()):
            $data['grav_url'] = $this->get_gravatar($this->session->userdata('email'));
            $this->load->view($data['view'], $data);
        else:
            $this->logout();
        endif;
    }

    public function account_exists($username, $password) {
        $status = 0;

        $where = array(
            'email' => $username,
            'password' => sha1($password),
            'status' => 1);


        $results = $this->db->select('*')->from('users')->where($where)->get()->result();

        return $results;
    }

    public function upload_image($type = null, $data = null) {

        $path = 'upload/' . sha1(date('Y-m-d-H:i:s')) . $this->getToken();
        // $data = $this->stripLineBreaks($data);
        //  $data = base64_decode($data);

        $fp = fopen($path . $type, 'wb');
        fwrite($fp, base64_decode($this->stripLineBreaks($data)));


        fclose($fp);

        return $path . $type;
    }

    public function upload_base64($message) {
        $images = explode('<img src="data:image/jpeg;base64,', $message);




        foreach ($images as $image_data) {
            $image = explode('"', $image_data);



            if ($image[0] != "") {

                $file = $this->upload_image('.jpg', $image[0]);

                $message = str_replace('data:image/jpeg;base64,' . $image[0], base_url() . $file, $message);
            }
        }

        return $message;
    }

    public function stripLineBreaks($encode) {

        $data = str_replace(' ', '+', $encode);

        return $data;
    }

    public function new_date($date) {
        $splitted_date = explode('-', $date);

        $newdate = $splitted_date[2] . '-' . $splitted_date[1] . '-' . str_pad($splitted_date[0], 2, "0", STR_PAD_LEFT);

        return $newdate;
    }

    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param boole $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     * @source http://gravatar.com/site/implement/images/php/
     */
    function get_gravatar($email, $s = 320, $d = 'mm', $r = 'g', $img = false, $atts = array()) {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val)
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

    public function logout() {
        unset($this->session->userdata);

        $this->session->sess_destroy();
        $this->session->unset_userdata('id');
        $this->session->unset_userdata('fullname');
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('mobile');
        $this->session->unset_userdata('dept');
        $this->session->unset_userdata('file_name');
        $this->session->unset_userdata('email_id');
        redirect('home/login', 'refresh');
        //echo '<META http-equiv=refresh content=0;URL=../home/login>';
    }

}
