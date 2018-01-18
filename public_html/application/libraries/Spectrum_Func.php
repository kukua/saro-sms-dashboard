<?php

class Spectrum_Func {

    function delivery_status($num) {
        if ($num == 0):
            return '<span class="label label-info ticket-label">PENDING</span>';
        elseif ($num == 1):
            return '<span class="label label-success ticket-label">SUCCEEDED</span>';
        elseif ($num == 3):
            return '<span class="label label-success ticket-label">COMPLETE</span>';
        elseif ($num == 4):
            return '<span class="label label-warning ticket-label">QUEUE</span>';
        else:
            return '<span class="label label-danger ticket-label">FAILED</span>';
        endif;
    }
    function campaign_status($num) {
        if ($num == 'active'):
            return '<span class="label label-success ticket-label">ACTIVE</span>';
        else:
            return '<span class="label label-danger ticket-label">INACTIVE</span>';
        endif;
    }
    function ad_status($num) {
        if ($num == 'active'):
            return '<span class="label label-success ticket-label">ACTIVE</span>';
        else:
            return '<span class="label label-danger ticket-label">INACTIVE</span>';
        endif;
    }


    function status_color($num) {
        if ($num == 0):
            return 'info';
        elseif ($num == 1):
            return 'success';
        else:
            return 'danger';
        endif;
    }

    function credit_color($credit) {
        if ($credit < 100):
            return 'danger';
        elseif ($credit >= 100 && $credit < 500):
            return 'warning';
        else:
            return 'success';
        endif;
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

    public static function deleteDir($dirPath) {
        if (!is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

}
