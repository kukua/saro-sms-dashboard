<?php

chdir("..");

$url = base_url()."assets/backend/mosaico/uploads/static/".$this->session->userdata('id')."/";

$uploads_dir = FCPATH."assets".DS."backend".DS."mosaico".DS."uploads".DS.$this->session->userdata('id').DS;
$static_dir = FCPATH."assets".DS."backend".DS."mosaico".DS."uploads".DS."static".DS.$this->session->userdata('id').DS;
$thumbnails_dir = FCPATH."assets".DS."backend".DS."mosaico".DS."uploads".DS."thumbnails".DS.$this->session->userdata('id').DS;
$thumbnail_width = 90;
$thumbnail_height = 90;

/* run this puppy through premailer */

//$base_url = ( isset( $_SERVER[ "HTTPS" ] ) ? "https" : "http" ) . "://" . $_SERVER[ "SERVER_NAME" ] . dirname( dirname( $_SERVER[ "PHP_SELF" ] ) ) . "/";

$cssToInlineStyles = new TijsVerkoyen\CssToInlineStyles\CssToInlineStyles();

$cssToInlineStyles->setHTML($_POST["html"]);

$html = $cssToInlineStyles->convert();


$num_full_pattern_matches = preg_match_all('#<img.*?src="([^"]*?\/[^/]*\.[^"]+)#i', $html, $matches);

for ($i = 0; $i < $num_full_pattern_matches; $i++) {
    if (stripos($matches[1][$i], "/img?src=") !== FALSE) {
        if (preg_match('#/img\?src=(.*)&amp;method=(.*)&amp;params=(.*)#i', $matches[1][$i], $src_matches) !== FALSE) {
            $file_name=urldecode($src_matches[1]);
            $path_parts = pathinfo( $file_name );
            $file_name = $path_parts[ "basename" ];

            $method = urldecode($src_matches[2]);

            $params = urldecode($src_matches[3]);           
            $params = explode(",", $params);
           
            $width = (int) $params[0];
            $height = (int) $params[1];

            $static_file_name = $method . "_" . $width . "x" . $height . "_" . $file_name;

            $html = str_ireplace($matches[1][$i], $url . $static_file_name, $html);

            require APPPATH . "libraries/Resize.php";
    
            $image->writeImage($static_dir . $static_file_name);
        }
    }
}

/* perform the requested action */

switch ($_POST["action"]) {
    case "download": {
            header("Content-Type: application/force-download");
            header("Content-Disposition: attachment; filename=\"" . $_POST["filename"] . "\"");
            header("Content-Length: " . strlen($html));

            echo $html;

            break;
        }

    case "email": {
            $to = $_POST["rcpt"];
            $subject = $_POST["subject"];

            $headers = array();

            $headers[] = "MIME-Version: 1.0";
            $headers[] = "Content-type: text/html; charset=iso-8859-1";
            $headers[] = "To: $to";
            $headers[] = "Subject: $subject";

            $headers = implode("\r\n", $headers);

            if (mail($to, $subject, $html, $headers) === FALSE) {
                header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");

                echo "ERR";
            } else {
                echo "OK: Mail sent.";
            }

            break;
        }
    case "save": {
            if ($this->db->select('id')->from('email_template')->where(array('templateid' => $_POST["key"], 'user_id' => $this->session->userdata('id')))->get()->num_rows() == 0) {
                $this->db->insert(
                        'email_template', [
                    'templateid' => $_POST["key"],
                    'html' => $html,
                    'metadata' => $_POST['metadata'],
                    'template' => $_POST['template'],
                    'name' => $_POST["name"],
                    'user_id' => $this->session->userdata('id'),
                    'created_at' => date('Y-m-d H:i:s')
                        ]
                );
            } else {
                $this->db->update('email_template', [
                    'templateid' => $_POST["key"],
                    'html' => $html,
                    'metadata' => $_POST['metadata'],
                    'template' => $_POST['template']
                        ], 
                        array('templateid' => $_POST["key"], 'user_id' => $this->session->userdata('id')));
            }

            $this->data['message'] = 'Thank you! email list added successfully';
            $this->data['alert'] = 'alert-success';

            echo "OK: Email Saved.";

            break;
        }
}
