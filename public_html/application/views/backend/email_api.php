<?php $this->load->view('Layout/backend/header', []); ?>
<?php echo link_tag('assets/highlightjs/styles/default.css'); ?>
<script type="text/javascript" src="<?php echo base_url(); ?>/assets/highlightjs/highlight.pack.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
<div class="panel panel-default">
    <div class="panel-heading">
      
    </div>
    <div class="profile-content">

        <ul id="profile-tabs" class="nav nav-tabs">
            <li class="active">
                <a href="#profile-tabs-OAuth-timeline" data-toggle="tab">Digital Signature Authentication (JSON)</a>
            </li>
        </ul>

        <div class="tab-content tab-content-bordered panel-padding">            
            <div class="tab-pane fade widget-followers fade in active" id="profile-tabs-OAuth-timeline">
                <div class="follower">
                    <div class="body">
                        <div class="padding-md">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="tab-content">
                                        <div class="tab-pane fade in active" id="overview">
                                            <div class="row">

                                                <?php
                                                $oauth_client = $this->db->select('*')->from('oauth_clients')->where(array('user_id' => $this->session->userdata('id')))->get()->row();
                                                ?>
                                                <div class="note note-info">
                                                    <p>We use public-key cryptography to protect your credentials</p>
                                                    <p>These are your credentials:</p>
                                                    <p style="font-weight: bold;">
                                                          Client ID : <?php echo $oauth_client->client_id; ?>
                                                    </p>
                                                   <p style="font-weight: bold;">
                                                          Client Secret : <?php echo $oauth_client->client_secret; ?>
                                                    </p>
                                                      <a href="<?=  base_url()?>/assets/spectrumsms_sample.zip" class="">Download Bootstrap PHP Composer Project(PHP)</a>
                                                </div>                                              
                                                <div class="col-md-12">
                                                    <pre>
<h4><p><strong>API INTEGRATION</strong></p></h4>
<p>Before sending your request, please encrypt it with our public key and base64 encode it. The PHP example 
below uses the phpseclib to ecrypt message.
</p>
<p>The items in [] should be replaced by your details.Note that [To] can be comma separated for bulk email addresses</p>
                                                    <code class="php">
&lt;?php<br>
include "./vendor/autoload.php";

//send sms url
$url = '<?php echo base_url()?>api/1/email/send';

$data = [
    'client_id' => '<?php echo $oauth_client->client_id; ?>',
    'client_secret' => '<?php echo $oauth_client->client_secret; ?>',
    'grant_type' => 'client_credentials',
    'from' => '[FROM]',
    'subject' => '[SUBJECT]',
    'message'=>'[MESSAGE]',
    'to'=>'[TO]'
    ];

$data = json_encode($data);

$key =file_get_contents('<?php echo base_url()?>assets/spectrumconnect.pub');
$rsa =  new phpseclib\Crypt\RSA();
$rsa->loadKey($key);
$rsa->setPublicKey($key);
// read the public key
openssl_public_encrypt($data, $encrypted,$rsa->getPublicKey());
// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query(['data'=> base64_encode($encrypted)]),
    ),
);

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);
print_r($result);

                                                    </code>

<h4><p><strong>API Responses:</strong></p></h4>
<p>These are standard Spectrum reponse codes but normal http codes are there by default</p>
<code class="json">
{"code":"1024","message":"EMAILS Sent to Queue","count"=>[NUMBER-OF-EMAIL-SENT]}

{"code":"1025","message":"Failed to send EMAIL to process Queue! Parameters not provided"}

{"code":"1026","message":"Sorry, Insurficient funds"}

{"code":"1027","message":"Sorry, Your profile EMAIL is not complete. please contact admin"}

{"code":"1028","message":"Sorry, You need to encrypt your data"}

</code>
                                                    </pre>
                                                </div>
                                            </div><!-- /.row -->
                                        </div><!-- /tab1 -->
                                    </div><!-- /tab-content -->
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.padding-md -->



                    </div>
                </div>

            </div> <!-- / .tab-pane -->
        </div> <!-- / .tab-content -->
    </div>

    <?php $this->load->view('Layout/backend/footer', []); ?>  