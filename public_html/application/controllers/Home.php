<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends Spectrum_Controller {

    public function sms_api() {

        $this->data['view'] = 'sms_api';
        $this->data['viewstatus'] = 'sms_api';
        $this->load_view($this->data);
    }

    public function email_api() {

        $this->data['view'] = 'email_api';
        $this->data['viewstatus'] = 'email_api';
        $this->load_view($this->data);
    }

    public function confirm_forgot_password() {
        $this->data['view'] = 'frontend/login';

        if ($this->db->select('id')->from('forgot_password')->where(array('email' => $_GET['reset'], 'code' => $_GET['code'], 'status' => 0))->get()->num_rows() == 0) {
            $this->data['alert'] = 'alert-danger';
            $this->data['message'] = 'Expired link used';
        } else {

            $this->db->update('forgot_password', array('status' => 1), array('email' => $_GET['reset']));

            $this->db->update('users', array('password' => sha1('1234')), array('email' => $_GET['reset']));


            $this->data['alert'] = 'alert-success';
            $this->data['message'] = 'Password changed successfully to 1234';

            $email = 'Hi ' . $_GET['reset'] . ',<br>Your password has been reset to 1234. You are requested to login and change it to a stronger password';

            //$this->sendemail('Confirmation about password reset', $_REQUEST['reset'], 'no-reply@spectrumug.com', $email);
            $this->email_notification("Confirmation about password reset", $email, $_GET['reset']);
        }
        $this->load->view($this->data['view'], $this->data);
    }

    public function forgot_password() {
        $this->data['view'] = 'frontend/forgot_password';
        if (isset($_REQUEST['email'])) {

            $email = 'Hi ' . $_REQUEST['email'] . ',<br>Thank you for contacting Kukua B V. Click <a href="' . $url . '">here</a> to reset your password';

            if ($this->db->select('id')->from('users')->where(array('email' => $_REQUEST['email']))->get()->num_rows() == 0) {
                $this->data['alert'] = 'alert-danger';
                $this->data['message'] = 'Unknown email address used';
            } else {

                $code = $this->getToken(20);

                $values = array('created_at' => date('Y-m-d H:i:s'), 'email' => $_REQUEST['email'], 'code' => sha1($code));

                $this->db->insert('forgot_password', $values);

                $url = base_url() . 'home/confirm_forgot_password?' . http_build_query(array('reset' => $_REQUEST['email'], 'code' => sha1($code)));
                $email = 'Hi ' . $_REQUEST['email'] . ',<br>Thank you for contacting Kukua B V. Click <a href="' . $url . '">here</a> to reset your password';

                //$this->sendemail('Reset Password', $_REQUEST['email'], 'no-reply@spectrumug.com', $email);
                $this->email_notification("Confirmation about password reset", $email, $_REQUEST['email']);

                //$user =$this->db->select('id')->from('users')->where(array('email' => $_REQUEST['email']))->get()->row();
                //$this->sms_notification($user->mobile, "Notice", 'Hi ' . $_REQUEST['email'] . ',Your password has been reset to 1234. You are requested to login and change it to a stronger password');

                $this->data['alert'] = 'alert-info';
                $this->data['message'] = 'An email containing your password reset link has been sent. If our email go to SPAM, please add info@kukua.cc to you contact or move it away from SPAM';
            }
        }


        $this->load->view($this->data['view'], $this->data);
    }

    public function index() {

        $this->data['viewstatus'] = 'dashboard';
        $this->data['view'] = 'frontend/home';
        $this->load->view($this->data['view'], $this->data);
    }

    public function login() {

        $this->data['view'] = 'frontend/login';
        if (isset($_REQUEST['email'])) {

            $results = $this->account_exists(trim($_REQUEST['email']), trim($_REQUEST['password']));

            if (isset($results[0]->id)) {

                $session_data = array('id' => $results[0]->id,
                    'email' => $results[0]->email,
                    'fullname' => $results[0]->fullname,
                    'dept' => $results[0]->dept,
                    'mobile' => $results[0]->mobile,
                    'last_login' => $results[0]->logintime);



                $email_details = array('type' => 'login notification', 'message' => $results[0]->email . ' has logged in at ' . date('Y-m-d H:i:s'), 'email' => $results[0]->email);

                $this->session->set_userdata($session_data);

                $this->data['view'] = 'backend/' . $this->session->userdata('dept') . '/dashboard';
                $this->data['viewstatus'] = 'dashboard';

                $this->db->update('users', array('logintime' => date('Y-m-d H:i:s')), array('email' => trim($_REQUEST['email'])));

                //echo '<META http-equiv=refresh content=0;URL=../' . $results[0]->dept . '/>';
            } else {
                $this->data['message'] = 'Invalid username or passcode used';
                $this->data['username'] = $this->input->post('username');
                $this->data['alert'] = 'alert-danger';
            }
        }


        $this->load->view($this->data['view'], $this->data);
    }

    public function create_account() {


        $this->data['view'] = 'frontend/create_account';
        $this->data['message'] = '';

        if (isset($_REQUEST['fullname'])) {


            if (strlen($_REQUEST['password']) > 0) {
                if (trim($_REQUEST['password']) == trim($_REQUEST['passwordagain'])) {
                    $recaptcha = new \ReCaptcha\ReCaptcha('6LeO8xoTAAAAAMU5cIhEHvA5veU2TAPe2xdi8zMG');
                    $resp = $recaptcha->verify($this->input->post('g-recaptcha-response'), $_SERVER['REMOTE_ADDR']);
                    if ($resp->isSuccess()) {

                        $credits = 200;

                        if ($this->db->select('*')->from('users')->where(array('email' => trim($_REQUEST['email'])))->get()->num_rows() == 0) {

                            $values = array(
                                'fullname' => trim($_REQUEST['fullname']),
                                'password' => sha1(trim($_REQUEST['password'])),
                                'dept' => 'user',
                                'credits' => $credits,
                                'owner' => 0,
                                'sms_cost' => 25,
                                'persms_flag' => 1,
                                'email' => trim($_REQUEST['email']),
                                'mobile' => trim($_REQUEST['mobile']),
                                'status' => 1,
                                'routeid' => 17,
                                'created' => date('Y-m-d H:i:s'));

                            $this->db->insert('users', $values);

                            $this->session->set_userdata($values);

                            $this->data['alert'] = 'alert-info';
                            $this->data['message'] = 'Use account created successfully';

                            $email_details = array('type' => '| Account created successfully', 'message' => 'Dear ' . trim($_REQUEST['fullname']) . '<br>Your messaging account has been created on Kukua B Ved with the following credentials<br> Email : ' . $_REQUEST['email'] . '<br> Password : ' . $_REQUEST['password'] . ' <br> On ' . date('Y-m-d H:i:s'), 'email' => $_REQUEST['email']);
                            //TODO send notifications
                            //$this->spectrum->send_notification($email_details);

                            redirect('user', 'refresh');
                        } else {

                            $this->data['alert'] = 'alert-warning';
                            $this->data['message'] = 'Use account already exists';
                        }
                    } else {

                        $captcha_error = "";
                        foreach ($resp->getErrorCodes() as $code) {
                            $captcha_error = $captcha_error . ' ' . $code . ' ';
                        }
                        $this->data['alert'] = 'alert-danger';
                        $this->data['message'] = 'Invalid token has been supplied.' . $captcha_error;
                    }
                } else {

                    $this->data['alert'] = 'alert-warning';
                    $this->data['message'] = 'Password mismatch has occured!';
                }
            }
        }

        $this->data['token'] = $this->getToken();
        $this->session->set_userdata(array('token' => $this->data['token']));




        $this->load->view($this->data['view'], $this->data);
    }

    public function about() {
        $this->data['viewstatus'] = 'about';
        $this->data['view'] = 'frontend/about';
        $this->load->view($this->data['view'], $this->data);
    }

    public function contact() {
        $this->data['viewstatus'] = 'about';
        $this->data['view'] = 'frontend/about';
        $this->load->view($this->data['view'], $this->data);
    }

    public function solutions() {
        $this->data['viewstatus'] = 'about';
        $this->data['view'] = 'frontend/about';
        $this->load->view($this->data['view'], $this->data);
    }

    public function bulksms() {
        $this->data['viewstatus'] = 'about';
        $this->data['view'] = 'frontend/about';
        $this->load->view($this->data['view'], $this->data);
    }

    public function smsapi() {
        $this->data['viewstatus'] = 'about';
        $this->data['view'] = 'frontend/about';
        $this->load->view($this->data['view'], $this->data);
    }

    public function sdks() {
        $this->data['viewstatus'] = 'about';
        $this->data['view'] = 'frontend/about';
        $this->load->view($this->data['view'], $this->data);
    }

    public function projects() {
        $this->data['viewstatus'] = 'about';
        $this->data['view'] = 'frontend/about';
        $this->load->view($this->data['view'], $this->data);
    }

    public function project() {
        $this->data['viewstatus'] = 'about';
        $this->data['view'] = 'frontend/about';
        $this->load->view($this->data['view'], $this->data);
    }

}
