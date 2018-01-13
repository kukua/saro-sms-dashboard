<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class OAuth extends Spectrum_Controller {

    public function __construct() {
        parent::__construct();


        $this->load->database();

        OAuth2\Autoloader::register();

        $dsn = 'mysql:dbname=' . $this->db->database . ';host=' . $this->db->hostname;
        $username = $this->db->username;
        $password = $this->db->password;

        // $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
        $storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));

        // Pass a storage object or array of storage objects to the OAuth2 server class
        $this->server = new OAuth2\Server($storage);

        // Add the "Client Credentials" grant type (it is the simplest of the grant types)
        $this->server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));

        // Add the "Authorization Code" grant type (this is where the oauth magic happens)
        $this->server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
    }

    public function token() {
        // Handle a request for an OAuth2.0 Access Token and send the response to the client

        $key = '-----BEGIN RSA PRIVATE KEY-----
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
-----END RSA PRIVATE KEY-----';

        if (openssl_private_decrypt(base64_decode($_REQUEST['data']), $decrypted, $key)) {

            $jsondata = json_decode($decrypted);
            
            $jsondata =['client_id'=>$jsondata->client_id,'client_secret'=>$jsondata->client_secret,'grant_type'=>$jsondata->grant_type];
            
            $globals =OAuth2\Request::createFromGlobals();
            
            $globals->request['data'] =$jsondata;
            
            
            $this->server->handleTokenRequest($globals)->send();
        } else {
            print_r(OAuth2\Request::createFromGlobals()->request['data']);
            echo json_encode(array('code' => '1028', 'message' => 'Sorry, You need to encrypt your data'));
        }
    }

    public function authorize() {
        $request = OAuth2\Request::createFromGlobals();
        $response = new OAuth2\Response();

        // validate the authorize request
        if (!$this->server->validateAuthorizeRequest($request, $response)) {
            $response->send();
            die;
        }
        // display an authorization form
        if (empty($_POST)) {
            exit('
                <form method="post">
                  <label>Do You Authorize?</label><br />
                  <input type="submit" name="authorized" value="yes">
                  <input type="submit" name="authorized" value="no">
                </form>');
        }

        // print the authorization code if the user has authorized your client
        $is_authorized = ($_POST['authorized'] === 'yes');
        $this->server->handleAuthorizeRequest($request, $response, $is_authorized);
        if ($is_authorized) {
            // this is only here so that you get to see your code in the cURL request. Otherwise, we'd redirect back to the client
            $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=') + 5, 40);
            exit("SUCCESS! Authorization Code: $code");
        }
        $response->send();
    }

}
