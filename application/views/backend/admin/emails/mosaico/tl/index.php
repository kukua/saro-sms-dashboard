<?php

/* perform the requested action */

switch ($_POST["action"]) {

    case "getmd": {
            $template = $this->db->select('metadata')->from('email_template')->where(array('templateid' => $_POST["key"], 'user_id' => $this->session->userdata('id')))->get()->result();
            echo!empty($template) ? $template[0]->metadata : json_encode("Nothing");

            break;
        }

    case "gettd": {
            $template = $this->db->select('template')->from('email_template')->where(array('templateid' => $_POST["key"], 'user_id' => $this->session->userdata('id')))->get()->result();
            echo!empty($template) ? $template[0]->template : json_encode("Nothing");

            break;
        }
    case "getall": {
            $template = $this->db->select('template,metadata')->from('email_template')->where(array('user_id' => $this->session->userdata('id')))->get()->result();
            echo!empty($template) ? json_encode($template) : json_encode("Nothing");

            break;
        } 
}
?>