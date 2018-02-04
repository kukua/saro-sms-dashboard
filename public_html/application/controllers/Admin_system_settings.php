<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_system_settings extends Spectrum_Controller {

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
            $this->data['view'] = 'backend/admin/settings/systemsettings_container';
            $this->data['viewstatus'] = 'system_settings';

            $this->load_view($this->data);
        } else {

            $this->logout();
        }
    }

    public function mobile_networks_delete($id = null) {
        $this->db->where(array('id' => $id / date('Y')))->delete('networks');
        redirect('admin/system_settings/mobile_networks', 'refresh');
    }

    public function create_mobile_networks_networks() {

        $this->data['view'] = "backend/admin/settings/mobile_networks";
        $this->data['viewstatus'] = 'system_settings';

        if ($this->db
                        ->select('*')
                        ->from('networks')
                        ->where(array('prefix' => $_REQUEST['prefix']))->get()->num_rows() < 1) {

            $values = array('network' => $_REQUEST['network_name']
                , 'prefix' => $_REQUEST['prefix'],
                'cost' => $_REQUEST['cost'],
                'routeid' => $_REQUEST['route'],
                'created' => date('Y-m-d H:i:s'));

            $this->db->insert('networks', $values);
            $this->data['flash'] = ["message" => "Network added successfully, Thank you!", "class" => "success"];
        } else {
            $this->data['flash'] = ["message" => "Network Already Exists.Just edit, Thank you!", "class" => "success"];
        }

        $this->load_view($this->data);
    }

    public function change_default_route() {

        $this->data['view'] = "backend/admin/settings/tools";
        $this->data['viewstatus'] = 'system_settings';



       
        $numbers = $this->db->select('*')->from('users')->get()->result();

        foreach ($numbers as $number) {
            
            $this->db->where(array('userid' => $number->id))->delete('routes_assigned');

            $this->db->update('users', array('routeid' => $_REQUEST['route']), array('id' => $number->id));
            $values = array(
                'routeid' => $_REQUEST['route'],
                'userid' => $number->id
            );
            $this->db->insert('routes_assigned', $values);
        }

        if ($this->db->update('users', array('routeid' => $_REQUEST['route']), array())) {

            $this->data['flash'] = ["message" => "Default Route set successfully, Thank you!", "class" => "success"];
        } else {
            $this->data['flash'] = ["message" => "Failed to set default Route!", "class" => "success"];
        }

        $this->load_view($this->data);
    }

    public function mobile_networks_network_name_edit() {

        $this->data['view'] = "backend/admin/settings/mobile_networks";
        $this->data['viewstatus'] = 'system_settings';
        $this->data['id'] = $_REQUEST['id'] / date('Y');

        $values = array(
            'network' => $_REQUEST['network_name_edit']
            , 'prefix' => $_REQUEST['prefix_edit'],
            'cost' => $_REQUEST['cost_edit'],
            'routeid' => $_REQUEST['route_edit']);

        $this->db->update('networks', $values, array('id' => $this->data['id']));

        $this->data['flash'] = ["message" => "Network edited successfully, Thank you!", "class" => "success"];
        $this->load_view($this->data);
    }

    public function route_edit() {

        $this->data['view'] = "backend/admin/settings/systemsettings_container";
        $this->data['viewstatus'] = 'system_settings';
        $this->data['id'] = $_REQUEST['id'] / date('Y');


        $values = array('name' => $_REQUEST['route_name_edit']
            , 'batch_limit' => $_REQUEST['batch_limit_edit'],
            'method' => $_REQUEST['method_edit'],
            'url' => $_REQUEST['url_edit']);

        $this->db->update('routes', $values, array('id' => $this->data['id']));

        $this->data['flash'] = ["message" => "Route edited successfully, Thank you!", "class" => "success"];
        $this->load_view($this->data);
    }

    public function create_route() {

        $this->data['view'] = "backend/admin/settings/systemsettings_container";
        $this->data['viewstatus'] = 'system_settings';

        $values = array('name' => $_REQUEST['route_name']
            , 'batch_limit' => $_REQUEST['batch_limit'],
            'method' => $_REQUEST['method'],
            'url' => $_REQUEST['url']);

        $this->db->insert('routes', $values);

        $this->data['flash'] = ["message" => "Route added successfully, Thank you!", "class" => "success"];
        $this->load_view($this->data);
    }

    public function delete_route($id) {

        $this->data['view'] = "backend/admin/settings/systemsettings_container";
        $this->data['viewstatus'] = 'system_settings';
        $this->data['id'] = $id / date('Y');

        $this->db->where(array('id' => $this->data['id']))->delete('routes');

        $this->data['flash'] = ["message" => "Route deleted successfully, Thank you!", "class" => "success"];
        $this->load_view($this->data);
    }

    public function route_parameters($id) {

        $this->data['view'] = "backend/admin/settings/route_parameters";
        $this->data['viewstatus'] = 'system_settings';
        $this->data['id'] = $id / date('Y');

        $this->load_view($this->data);
    }

    public function create_site() {

        $this->data['view'] = "backend/admin/settings/sites";
        $this->data['viewstatus'] = 'system_settings';


        $values = array(
            'name' => trim($_REQUEST['name']),
            'domain' => trim($_REQUEST['domain']),
            'text' => trim($_REQUEST['text']),
            'leaderboard' => trim($_REQUEST['leaderboard']),
            'skyscrapper' => trim($_REQUEST['skyscrapper']),
            'banner' => trim($_REQUEST['banner']),
            'mpu' => trim($_REQUEST['mpu']),
            'fivesecs' => trim($_REQUEST['fivesecs']),
            'fifteensecs' => trim($_REQUEST['fifteensecs']),
            'thirtysecs' => trim($_REQUEST['thirtysecs']),
            'sixtysecs' => trim($_REQUEST['sixtysecs']),
            'createdby' => $this->session->userdata('id'),
            'modifiedby' => $this->session->userdata('id'),
            'created' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s'));

        $this->db->insert('sites', $values);

        $this->data['flash'] = ["message" => "Site created successfully, Thank you!", "class" => "success"];

        $this->load_view($this->data);
    }

    public function site_edit() {

        $this->data['view'] = "backend/admin/settings/sites";
        $this->data['viewstatus'] = 'system_settings';


        $values = array(
            'name' => trim($_REQUEST['name']),
            'domain' => trim($_REQUEST['domain']),
            'text' => trim($_REQUEST['text']),
            'leaderboard' => trim($_REQUEST['leaderboard']),
            'skyscrapper' => trim($_REQUEST['skyscrapper']),
            'banner' => trim($_REQUEST['banner']),
            'mpu' => trim($_REQUEST['mpu']),
            'fivesecs' => trim($_REQUEST['fivesecs']),
            'fifteensecs' => trim($_REQUEST['fifteensecs']),
            'thirtysecs' => trim($_REQUEST['thirtysecs']),
            'sixtysecs' => trim($_REQUEST['sixtysecs']),
            'modifiedby' => $this->session->userdata('id'),
            'modified' => date('Y-m-d H:i:s'));

        $this->db->update('sites', $values, array('id' => $_REQUEST['id'] / date('Y')));

        $this->data['flash'] = ["message" => "Site updated successfully, Thank you!", "class" => "success"];

        $this->load_view($this->data);
    }

    public function delete_site($id) {

        $this->data['view'] = "backend/admin/settings/sites";
        $this->data['viewstatus'] = 'system_settings';
        $this->data['id'] = $id / date('Y');

        $this->db->where(array('id' => $id / date('Y')))->delete('sites');

        $this->data['flash'] = ["message" => "Deleted site!", "class" => "success"];

        $this->load_view($this->data);
    }

    public function create_route_parameters($id) {

        $this->data['view'] = "backend/admin/settings/route_parameters";
        $this->data['viewstatus'] = 'system_settings';
        $this->data['id'] = $_REQUEST['routeid'] / date('Y');


        $values = array(
            'parameter_name' => trim($_REQUEST['parameter_name']),
            'parameter' => trim(trim($_REQUEST['name'])),
            'value' => trim($_REQUEST['value']),
            'created' => date('Y-m-d H:i:s'),
            'routeid' => trim($this->data['id']));

        if ($this->db->select('*')->from('route_parameters')->where(array('routeid' => $this->data['id'], 'parameter_name' => trim($_REQUEST['parameter_name'])))->get()->num_rows() > 0 && trim($_REQUEST['parameter_name']) != "others") {
            $this->db->update('route_parameters', $values, array('routeid' => $this->data['id'], 'parameter_name' => trim($_REQUEST['parameter_name'])));
        } else {

            $this->db->insert('route_parameters', $values);
        }
        $this->data['flash'] = ["message" => "Route Parameters edited successfully, Thank you!", "class" => "success"];

        $this->load_view($this->data);
    }

    public function delete_route_parameter($id, $routeid) {

        $this->data['view'] = "backend/admin/settings/route_parameters";
        $this->data['viewstatus'] = 'system_settings';
        $this->data['id'] = $routeid / date('Y');

        $this->db->where(array('id' => $id / date('Y')))->delete('route_parameters');

        $this->load_view($this->data);
    }

    public function mobile_networks() {

        $this->data['view'] = 'backend/admin/settings/mobile_networks';
        $this->data['viewstatus'] = 'system_settings';
        $this->load_view($this->data);
    }

    public function message_delivery() {

        $this->data['view'] = "backend/admin/settings/message_delivery";
        $this->data['viewstatus'] = 'system_settings';
        $this->load_view($this->data);
    }

    public function tools() {

        $this->data['view'] = "backend/admin/settings/tools";
        $this->data['viewstatus'] = 'system_settings';
        $this->load_view($this->data);
    }

    public function clean_plus() {

        $this->data['view'] = "backend/admin/settings/tools";
        $this->data['viewstatus'] = 'system_settings';

        $numbers = $this->db->select('*')->from('users')->where('mobile like "+%"')->get()->result();

        foreach ($numbers as $number) {
            $this->db->update('users', array('mobile' => ltrim($number->mobile, '+')), array('id' => $number->id));
        }

        $this->data['flash'] = ["message" => "SMS Numbers cleaned successfully, Thank you!", "class" => "success"];

        $this->load_view($this->data);
    }

    public function set_profile() {

        $this->data['view'] = "backend/admin/settings/tools";
        $this->data['viewstatus'] = 'system_settings';

        $numbers = $this->db->select('*')->from('users')->where('sms_cost =""')->get()->result();

        foreach ($numbers as $number) {
            $this->db->update('users', array('sms_cost' => 0.07,
                'email_cost' => 5,
                'persms_flag' => 1,), array('id' => $number->id));
        }

        $this->data['flash'] = ["message" => "SMS Numbers cleaned successfully, Thank you!", "class" => "success"];

        $this->load_view($this->data);
    }

    public function sites() {

        $this->data['view'] = "backend/admin/settings/sites";
        $this->data['viewstatus'] = 'system_settings';
        $this->load_view($this->data);
    }

    public function change_message_delivery($id) {

        $this->data['view'] = "backend/admin/settings/message_delivery";
        $this->data['viewstatus'] = 'system_settings';

        if ($this->db->select('*')->from('status')->where('status = -1 and id=' . $id)->get()->num_rows() > 0) {
            $this->db->update('status', array('status' => 1), array('id' => $id));

            $this->data['flash'] = ["message" => "SMS Delivery started successfully, Thank you!", "class" => "success"];
        } else {
            $this->db->update('status', array('status' => -1), array('id' => $id));
            $this->data['flash'] = ["message" => "SMS Delivery stopped successfully, Thank you!", "class" => "success"];
        }

        $this->load_view($this->data);
    }

    public function change_email_delivery() {

        $this->data['view'] = "backend/admin/settings/message_delivery";
        $this->data['viewstatus'] = 'system_settings';

        if ($this->db->select('*')->from('status')->where('status = -1 and id=2')->get()->num_rows() > 0) {
            $this->db->update('status', array('status' => 1), array('id' => 2));

            $this->data['flash'] = ["message" => "Email Delivery started successfully, Thank you!", "class" => "success"];
        } else {
            $this->db->update('status', array('status' => -1), array('id' => 2));
            $this->data['flash'] = ["message" => "Email Delivery stopped successfully, Thank you!", "class" => "success"];
        }

        $this->load_view($this->data);
    }

    public function System_Settings($method = null, $submethod = null, $id = null) {
        if ($this->isloggedin()) {

            $this->data['view'] = 'system_settings';
            $this->data['sublink'] = $method;
            $this->data['details'] = 'Token Search results';

            $this->data['message'] = '';

            switch ($method) {

                case 'messaging_costs':
                    if ($submethod == 'delete') {
                        $this->db->where(array('id' => $id / date('Y')))->delete('smscost');
                        redirect('admin/system_settings/messaging_costs', 'refresh');
                    } else {

                        if (isset($_REQUEST['package_name'])) {
                            $values = array(
                                'cost_name' => $_REQUEST['package_name']
                                , 'from_sms' => $_REQUEST['from_sms'],
                                'to_sms' => $_REQUEST['to_sms'],
                                'cost' => $_REQUEST['cost'],
                                'created' => date('Y-m-d H:i:s'),
                            );

                            $this->db->insert('smscost', $values);

                            $this->data['alert'] = 'alert-info';
                            $this->data['message'] = 'SMS package added successfully';
                        }

                        if (isset($_REQUEST['package_name_edit'])) {
                            $values = array('cost_name' => $_REQUEST['package_name_edit']
                                , 'from_sms' => $_REQUEST['from_sms_edit'],
                                'to_sms' => $_REQUEST['to_sms_edit'],
                                'cost' => $_REQUEST['cost_edit']
                            );


                            $this->db->update('smscost', $values, array('id' => $_REQUEST['id']));

                            $this->data['alert'] = 'alert-warning';
                            $this->data['message'] = 'SMS package edited successfully';
                        }
                    }
                    break;
            }

            $this->load_view($data);
        } else {

            $this->logout();
        }
    }

}
