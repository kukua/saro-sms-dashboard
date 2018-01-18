<?php
$type = $_REQUEST['type'];

$message = $_REQUEST['message'];

$email = $_REQUEST['email'];
//$message = $_REQUEST['message'];



$email_str = '<div style="background: #eee; border: 1px solid #ddd; padding: 10px; width: 550px;  margin: 0 auto; font-family: \'courier new\'; ">
<h3>Kukua B V </h3> <hr>



    <h5> </h5>
            '.$message.'<br><br>
            Thank you for choosing Kukua B V

            <hr>
</div>';



ini_set("SMTP","aspmx.l.google.com");

//ini_set("SMTP","smtp.yahoo.com");



function sendemail($email_to, $email_subject, $msg)
{
    // print_r($msg);
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: '.'no-reply@spectrumug.com' . "\r\n";
    mail($email_to, $email_subject, $msg, $headers);


}



sendemail($email,'Kukua B V '.$type,$email_str) ;



?>
