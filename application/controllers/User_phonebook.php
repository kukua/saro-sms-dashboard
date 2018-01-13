<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_phonebook extends Spectrum_Controller {

    public $sms_sent = 0;
    public $message_delivery = '';
    public $uploaded_personalized = "";

    public function load_view($data = null) {
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

    public function delete_group($id = null) {

        $this->data['view'] = 'backend/user/phonebook/phonebook_container';
        $this->data['viewstatus'] = 'phonebook';

        $this->db->where('id = "' . $id / date('Y') . '"')->delete('groups');
        $this->db->where('created_by =' . $this->session->userdata('id'));
        $this->db->where('group_id = "' . $id / date('Y') . '"')->delete('contacts');
        $this->load_view($this->data);
    }

    public function delete_contact($id = null, $id2 = null) {

        $this->data['view'] = 'backend/user/phonebook/contacts';
        $this->data['viewstatus'] = 'phonebook';

        $this->db->where('id = "' . $id / date('Y') . '"')->delete('contacts');

        $this->data['id'] = $id2 / date('Y');

        $this->load_view($this->data);
    }

    public function update_contact($id = null) {

        $this->data['view'] = 'backend/user/phonebook/contacts';
        $this->data['viewstatus'] = 'phonebook';

        $this->db->update('contacts', array('mobile' => $_REQUEST['mobile']), array('id' => $_REQUEST['id']));

        $this->data['id'] = $_REQUEST['group_id'] / date('Y');

        $this->load_view($this->data);
    }

    public function group_name_edit() {
        $this->data['view'] = 'backend/user/phonebook/phonebook_container';
        $this->data['viewstatus'] = 'phonebook';

        if ($this->db->select('id')->from('groups')->where(array('created_by' => $this->session->userdata('id'), 'group_name' => $_REQUEST['group_name_edit']))->get()->num_rows() == 0) {

            $this->db->update('groups', array(
                'group_name' => $_REQUEST['group_name_edit'],
                'created_by' => $this->session->userdata('id')
                    ), array('id' => $_REQUEST['id']));

            $this->data['flash'] = ['message' => 'Group edited successfully', 'class' => 'success'];
        } else {
            $this->data['flash'] = ['message' => 'Group already exists', 'class' => 'danger'];
        }
        $this->load_view($data);
    }

    public function contacts($id = null) {

        $this->data['view'] = 'backend/user/phonebook/contacts';
        $this->data['viewstatus'] = 'phonebook';
        $this->data['id'] = $id / date('Y');

        $this->load_view($this->data);
    }

    public function upload_contacts($id = null, $idparent = null) {
        $this->data['view'] = 'backend/user/phonebook/upload_contacts';
        $this->data['viewstatus'] = 'phonebook';
        $this->data['id'] = $id / date('Y');
        
        $this->data['page_obj'] = ['title' => 'Upload phone contacts', 'icon' => 'fa-book'];
        $this->data['breadcrumbs'][] = ['url' => 'user_phonebook/phonebook', 'title' => 'Phone book'];
        $this->data['breadcrumbs'][] = ['url' => 'user_phonebook/upload_contacts', 'title' => 'Upload phone contacts'];
        
        $this->load_view($this->data);
    }

    public function create_upload_contacts($id = null, $idparent = null) {
        $this->data['id'] = $id / date('Y');
        if (isset($_POST['upload'])) {
            $this->data['view'] = 'backend/user/phonebook/create_upload_contacts';
            $this->data['viewstatus'] = 'phonebook';

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
            redirect('user_phonebook/phonebook', 'refresh');
        }
        parent::load_view($this->data);
    }

    public function save_excel_contacts($id) {
        if ($this->isloggedin()) {

            $this->data['view'] = 'backend/user/phonebook/contacts';
            $this->data['viewstatus'] = 'phonebook';
            $this->data['id'] = $id / date('Y');
            $numbers = json_decode($_REQUEST['numbers']);

            for ($i = 0; $i < sizeof($numbers); $i++) {

                if ($this->db->select('id')->from('contacts')->where(array('group_id' => $id / date('Y'), 'mobile' => $numbers[$i]->mobile))->get()->num_rows() == 0) {
                    $values = array(
                        'mobile' => $numbers[$i]->mobile,
                        'created_at' => date('Y-m-d H:i:s'),
                        'group_id' => $id / date('Y'));

                    $this->db->insert('contacts', $values);
                }
            }
            $this->data['flash'] = ['message' => 'Contacts Uploaded', 'class' => 'success'];
        } else {

            $this->logout();
        }
        $this->load_view($this->data);
    }

    public function create_group() {
        $this->data['view'] = 'backend/user/phonebook/phonebook_container';
        $this->data['viewstatus'] = 'phonebook';

        if ($this->db->select('id')->from('groups')->where(array('created_by' => $this->session->userdata('id'), 'group_name' => $_REQUEST['group_name']))->get()->num_rows() == 0) {

            $this->db->insert('groups', array(
                'group_name' => $_REQUEST['group_name'],
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('id')));

            $this->data['flash'] = ['message' => 'Group Added', 'class' => 'success'];
        } else {
            $this->data['flash'] = ['message' => 'Failed to add group', 'class' => 'dander'];
        }
        $this->load_view($this->data);
    }

    public function add_contact($id = null) {

        $this->data['view'] = 'backend/user/phonebook/add_contact';
        $this->data['viewstatus'] = 'phonebook';
        $this->data['id'] = $id / date('Y');
        $this->load_view($this->data);
    }

    public function save_contact($id = null) {
        $this->data['view'] = 'backend/user/phonebook/contacts';
        $this->data['viewstatus'] = 'phonebook';
        $this->data['id'] = $_REQUEST['group_id'] / date('Y');

        if ($this->db->select('id')->from('contacts')->where(array('group_id' => $id / date('Y'), 'mobile' => $_REQUEST['mobile']))->get()->num_rows() == 0) {

            $values = array(
                'mobile' => $_REQUEST['mobile'],
                'created_at' => date('Y-m-d H:i:s'),
                'group_id' => $_REQUEST['group_id'] / date('Y'));

            $this->db->insert('contacts', $values);

            $this->data['flash'] = ['message' => 'Contact Added', 'class' => 'success'];
        } else {
            $this->data['flash'] = ['message' => 'Failed to add contact', 'class' => 'dander'];
        }
        $this->load_view($this->data);
    }

    public function phonebook($id = null) {
        $this->data['view'] = 'backend/user/phonebook/phonebook_container';
        $this->data['viewstatus'] = 'phonebook';
        $this->data['id'] = $id / date('Y');

        $this->data['page_obj'] = ['title' => 'Phone book', 'icon' => 'fa-book'];
        $this->data['breadcrumbs'][] = ['url' => 'user_phonebook/phonebook', 'title' => 'Phone book'];

        $this->load_view($this->data);
    }

}
