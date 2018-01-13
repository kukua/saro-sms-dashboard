<?php
$sent_item_logs = $this->db->select('*,routes.name as routename,sentitems.status as delivery_status,sum(charge) as totalcharge')
                ->from('sentitems, routes, users')
                ->where('sentitems.routeid = routes.id and users.id = sentitems.sender')
                ->where('sentitems.date between "' . $start_date . '" and "' . $end_date . '" and users.id='.$this->session->userdata('id'))
                ->order_by('sentitems.id DESC')
                ->group_by('sentitems.message_id')
                ->get()->result();
?>

<?php


// create new PDF document
$pdf = new TCPDF_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('SPECTRUMCONNECT');
$pdf->SetTitle('SMS MESSAGES');
$pdf->SetSubject('SPECTRUMCONNECT');
$pdf->SetKeywords('SMS, Messages, sms');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Message Deliver for '.$this->session->userdata('fullname'), PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 10, '', true);


// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

        $html ='<table cellspacing="0" cellpadding="1" border="1">'
                . '<tr>'
                . '<th width="3%">#</th>'
                . '<th width="20%">Date</th>'
                . '<th  width="40%">Message</th>'
                . '<th width="10%">Contacts</th>'
                . '<th width="10%">Charge</th>'
                . '<th width="15%">status</th>'
                . '</tr>';
                    $count = 1;
                    foreach ($sent_item_logs as $sent_item):
                        
       $html = $html.'  <tr>
                            <td>'.$count++ .'</td>
                            <td>'.(new Cake\I18n\Time($sent_item->datetime))->timeAgoInWords().'</td>
                            <td>'.$sent_item->message.'</td>
                            <td>'.$sent_item->contacts.'</td>
                            <td>'.number_format($sent_item->totalcharge).'</td>
                            <td>
                                '.$this->spectrum_func->delivery_status($sent_item->delivery_status).'
                            </td>


                        </tr>';
                        
                    endforeach;
        $html = $html .'</table>';

$pdf->writeHTML($html, true, false, false, false, '');

// ---------------------------------------------------------
//avoid PDF error
ob_end_clean();


// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Message Deliver for '.$this->session->userdata('fullname'), 'I');

//============================================================+
// END OF FILE
//============================================================+
?>