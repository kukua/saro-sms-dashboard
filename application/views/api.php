<style>

    code {
        font-family: Consolas, Monaco, Courier New, Courier, monospace;
        font-size: 13px;
        background-color: #f9f9f9;

        color: #002166;
        display: block;
        margin: 14px 0 14px 0;
        padding: 12px 10px 12px 10px;
    }
</style>

<div class="panel panel-default table-responsive">

    <div class="padding-md clearfix">

<code>
    <p>&lt;?php<br>
     <p>   function SendSMS($sender, $destination, $message)<br>	{<br>
     <p style="margin-left: 30px;">$email = '<?php echo !empty($this->session->userdata('email'))?$this->session->userdata('email'):"[YOUR_EMAIL]"?>';<br>
        $password = 'xxxxxxxxxxx';<br>
        $url = <?php echo base_url();?>api?';<br>
        $parameters = 'username=[EMAIL]&amp;password=[PASSWORD]&amp;contacts=[DESTINATION]&amp;message=[MESSAGE]&amp;sender=[SENDERID]';<br>
        $parameters = str_replace('[EMAIL]',$email,$parameters);<br>
        $parameters = str_replace('[PASSWORD]',urlencode($password),$parameters);<br>
        $parameters = str_replace('[DESTINATION]',$destination,$parameters);<br>
        $parameters = str_replace('[MESSAGE]',urlencode($message),$parameters);<br>
        $parameters = str_replace('[SENDERID]',urlencode($sender),$parameters);<br>	$post_url = $url.$parameters;<br>	$response = file($post_url);<br>	</p><p>	return $response[0];<br>
        }</p></p>

print_r(SendSMS('test-api','<?php echo !empty($this->session->userdata('mobile'))?$this->session->userdata('mobile'):"[YOUR_REGISTERED_MOBILE_WITH_COUNTRY_CODE]" ?>','testing-sms'));

    <p>?&gt;</p>
    <p>Example </p>
    <p><?php echo base_url();?>api?sender=test&amp;contacts=<?php echo !empty($this->session->userdata('mobile'))?$this->session->userdata('mobile'):"[YOUR_REGISTERED_MOBILE_WITH_COUNTRY_CODE]" ?>&amp;message=test&amp;username=<?php echo !empty($this->session->userdata('email'))? $this->session->userdata('email'):"[YOUR_EMAIL]"?>&amp;password=xxxxx</p>

    <h4><p><strong>API Responses:</strong></p></h4>
    <p style="font-size:13px">Some parameters are missing i.e. username, password, message, contacts, sender<br />
        Authorisation failed - For invalid username or password<br />
        Insufficient funds - Your account has run out of credit<br />
        Message_id=17f456c6fa4655d59629afed4f393562 - Successfully sent</p>
</code>

</pre>

</div>