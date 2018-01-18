<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Upload_Controller extends Spectrum_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function file_view() {


        $uploads_dir = FCPATH . "assets" . DS . "backend" . DS . "_a_" . DS . $this->session->userdata('id') . DS;
        if (!is_dir($uploads_dir))
            mkdir($uploads_dir, 0777, TRUE);

        $this->load->view('file_view', array('error' => ' '));
    }

    public function uploadleaderboard($advertisement_id) {

        $this->data['advertisement_id'] = $advertisement_id / date('Y');
        ;
        $this->data['view'] = 'backend/admin/advertisements/adimage';
        $this->data['viewstatus'] = 'advertisement';

        $uploads_dir = FCPATH . "assets" . DS . "backend" . DS . "_a_" . DS . $this->session->userdata('id') . DS;

        try {
            $config = array(
                'upload_path' => $uploads_dir,
                'allowed_types' => "gif|jpg|png|jpeg",
                'overwrite' => TRUE,
                'max_size' => "40000", // Can be set to particular file size , here it is 0.04 MB(40 Kb)
                'max_height' => "90",
                'max_width' => "728"
            );
            $this->load->library('upload', $config);

            if ($this->upload->do_upload()) {
                $data = $this->upload->data();
                $this->db->update('advertisements', array('leaderboard' => $data['file_name']), array('id' => $advertisement_id / date('Y')));

                $this->data['flash'] = ["message" => "Leaderboard Ad edited successfully, Thank you!", "class" => "success"];
            } else {
                $this->data['error'] = $this->upload->display_errors();
                //$this->load->view('file_view', $error);
            }
            $this->load_view($this->data);
        } catch (Exception $exc) {
            echo 'failed: smpp v2:  ' . $exc->getTraceAsString();
        }
    }

    public function uploadskyscrapper($advertisement_id) {

        $this->data['advertisement_id'] = $advertisement_id / date('Y');
        ;
        $this->data['view'] = 'backend/admin/advertisements/adimage';
        $this->data['viewstatus'] = 'advertisement';

        $uploads_dir = FCPATH . "assets" . DS . "backend" . DS . "_a_" . DS . $this->session->userdata('id') . DS;

        try {
            $config = array(
                'upload_path' => $uploads_dir,
                'allowed_types' => "gif|jpg|png|jpeg",
                'overwrite' => TRUE,
                'max_size' => "40000", // Can be set to particular file size , here it is 0.04 MB(40 Kb)
                'max_height' => "600",
                'max_width' => "120"
            );
            $this->load->library('upload', $config);

            if ($this->upload->do_upload()) {
                $data = $this->upload->data();
                $this->db->update('advertisements', array('skyscrapper' => $data['file_name']), array('id' => $advertisement_id / date('Y')));

                $this->data['flash'] = ["message" => "Skyscrapper Ad edited successfully, Thank you!", "class" => "success"];
            } else {
                $this->data['error'] = $this->upload->display_errors();
                //$this->load->view('file_view', $error);
            }
            $this->load_view($this->data);
        } catch (Exception $exc) {
            echo 'failed: smpp v2:  ' . $exc->getTraceAsString();
        }
    }

    public function uploadbanner($advertisement_id) {

        $this->data['advertisement_id'] = $advertisement_id / date('Y');
        ;
        $this->data['view'] = 'backend/admin/advertisements/adimage';
        $this->data['viewstatus'] = 'advertisement';

        $uploads_dir = FCPATH . "assets" . DS . "backend" . DS . "_a_" . DS . $this->session->userdata('id') . DS;

        try {
            $config = array(
                'upload_path' => $uploads_dir,
                'allowed_types' => "gif|jpg|png|jpeg",
                'overwrite' => TRUE,
                'max_size' => "40000", // Can be set to particular file size , here it is 0.04 MB(40 Kb)
                'max_height' => "60",
                'max_width' => "468"
            );
            $this->load->library('upload', $config);

            if ($this->upload->do_upload()) {
                $data = $this->upload->data();
                $this->db->update('advertisements', array('banner' => $data['file_name']), array('id' => $advertisement_id / date('Y')));

                $this->data['flash'] = ["message" => "Banner Ad edited successfully, Thank you!", "class" => "success"];
            } else {
                $this->data['error'] = $this->upload->display_errors();
                //$this->load->view('file_view', $error);
            }
            $this->load_view($this->data);
        } catch (Exception $exc) {
            echo 'failed: smpp v2:  ' . $exc->getTraceAsString();
        }
    }

    public function uploadmpu($advertisement_id) {

        $this->data['advertisement_id'] = $advertisement_id / date('Y');
        ;
        $this->data['view'] = 'backend/admin/advertisements/adimage';
        $this->data['viewstatus'] = 'advertisement';

        $uploads_dir = FCPATH . "assets" . DS . "backend" . DS . "_a_" . DS . $this->session->userdata('id') . DS;

        try {
            $config = array(
                'upload_path' => $uploads_dir,
                'allowed_types' => "gif|jpg|png|jpeg",
                'overwrite' => TRUE,
                'max_size' => "40000", // Can be set to particular file size , here it is 0.04 MB(40 Kb)
                'max_height' => "250",
                'max_width' => "300"
            );
            $this->load->library('upload', $config);

            if ($this->upload->do_upload()) {
                $data = $this->upload->data();
                $this->db->update('advertisements', array('mpu' => $data['file_name']), array('id' => $advertisement_id / date('Y')));

                $this->data['flash'] = ["message" => "MPU Ad edited successfully, Thank you!", "class" => "success"];
            } else {
                $this->data['error'] = $this->upload->display_errors();
                //$this->load->view('file_view', $error);
            }
            $this->load_view($this->data);
        } catch (Exception $exc) {
            echo 'failed: smpp v2:  ' . $exc->getTraceAsString();
        }
    }

    public function uploadfivesecs($advertisement_id) {

        $this->data['advertisement_id'] = $advertisement_id / date('Y');
        ;
        $this->data['view'] = 'backend/admin/advertisements/advideo';
        $this->data['viewstatus'] = 'advertisement';

        $uploads_dir = FCPATH . "assets" . DS . "backend" . DS . "_a_" . DS . $this->session->userdata('id') . DS;

        try {
            $config = array(
                'upload_path' => $uploads_dir,
                'allowed_types' => "mp4",
                'overwrite' => TRUE,
                'max_size' => "652", // Can be set to particular file size , here it is 0.6MB(5120 Kb)
                'max_height' => "720",
                'max_width' => "1280"
            );
            $this->load->library('upload', $config);

            if ($this->upload->do_upload()) {
                $data = $this->upload->data();
                $this->db->update('advertisements', array('fivesecs' => $data['file_name']), array('id' => $advertisement_id / date('Y')));

                $this->data['flash'] = ["message" => "Five seconds video Ad edited successfully, Thank you!", "class" => "success"];
            } else {
                $this->data['error'] = $this->upload->display_errors();
                //$this->load->view('file_view', $error);
            }
            $this->load_view($this->data);
        } catch (Exception $exc) {
            echo 'failed: smpp v2:  ' . $exc->getTraceAsString();
        }
    }

    public function uploadfifteensecs($advertisement_id) {

        $this->data['advertisement_id'] = $advertisement_id / date('Y');
        ;
        $this->data['view'] = 'backend/admin/advertisements/advideo';
        $this->data['viewstatus'] = 'advertisement';

        $uploads_dir = FCPATH . "assets" . DS . "backend" . DS . "_a_" . DS . $this->session->userdata('id') . DS;

        try {
            $config = array(
                'upload_path' => $uploads_dir,
                'allowed_types' => "mp4",
                'overwrite' => TRUE,
                'max_size' => "2200", // Can be set to particular file size , here it is 2.2 MB
                'max_height' => "720",
                'max_width' => "1280"
            );
            $this->load->library('upload', $config);

            if ($this->upload->do_upload()) {
                $data = $this->upload->data();
                $this->db->update('advertisements', array('fifteensecs' => $data['file_name']), array('id' => $advertisement_id / date('Y')));

                $this->data['flash'] = ["message" => "Fifteen seconds video Ad edited successfully, Thank you!", "class" => "success"];
            } else {
                $this->data['error'] = $this->upload->display_errors();
                //$this->load->view('file_view', $error);
            }
            $this->load_view($this->data);
        } catch (Exception $exc) {
            echo 'failed: smpp v2:  ' . $exc->getTraceAsString();
        }
    }

    public function uploadthirtysecs($advertisement_id) {

        $this->data['advertisement_id'] = $advertisement_id / date('Y');
        ;
        $this->data['view'] = 'backend/admin/advertisements/advideo';
        $this->data['viewstatus'] = 'advertisement';

        $uploads_dir = FCPATH . "assets" . DS . "backend" . DS . "_a_" . DS . $this->session->userdata('id') . DS;

        try {
            $config = array(
                'upload_path' => $uploads_dir,
                'allowed_types' => "mp4",
                'overwrite' => TRUE,
                'max_size' => "4400", // Can be set to particular file size , here it is 4.4 MB(30720 Kb)
                'max_height' => "720",
                'max_width' => "1280"
            );
            $this->load->library('upload', $config);

            if ($this->upload->do_upload()) {
                $data = $this->upload->data();
                $this->db->update('advertisements', array('thirtysecs' => $data['file_name']), array('id' => $advertisement_id / date('Y')));

                $this->data['flash'] = ["message" => "Thirty seconds video Ad edited successfully, Thank you!", "class" => "success"];
            } else {
                $this->data['error'] = $this->upload->display_errors();
                //$this->load->view('file_view', $error);
            }
            $this->load_view($this->data);
        } catch (Exception $exc) {
            echo 'failed: smpp v2:  ' . $exc->getTraceAsString();
        }
    }

    public function uploadsixtysecs($advertisement_id) {

        $this->data['advertisement_id'] = $advertisement_id / date('Y');
        ;
        $this->data['view'] = 'backend/admin/advertisements/advideo';
        $this->data['viewstatus'] = 'advertisement';

        $uploads_dir = FCPATH . "assets" . DS . "backend" . DS . "_a_" . DS . $this->session->userdata('id') . DS;

        try {
            $config = array(
                'upload_path' => $uploads_dir,
                'allowed_types' => "mp4",
                'overwrite' => TRUE,
                'max_size' => "10800", // Can be set to particular file size , here it is 10 MB(61440 Kb)
                'max_height' => "720",
                'max_width' => "1280"
            );
            $this->load->library('upload', $config);

            if ($this->upload->do_upload()) {
                $data = $this->upload->data();
                $this->db->update('advertisements', array('sixtysecs' => $data['file_name']), array('id' => $advertisement_id / date('Y')));

                $this->data['flash'] = ["message" => "Sixty seconds video Ad edited successfully, Thank you!", "class" => "success"];
            } else {
                $this->data['error'] = $this->upload->display_errors();
                //$this->load->view('file_view', $error);
            }
            $this->load_view($this->data);
        } catch (Exception $exc) {
            echo 'failed: smpp v2:  ' . $exc->getTraceAsString();
        }
    }

}

?>