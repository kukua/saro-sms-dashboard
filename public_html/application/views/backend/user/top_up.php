<?php $this->load->view('Layout/backend/header', []); ?>


        <?php
       
        //DEMO
        //$pesapal = new Simbacode\Moneycake\Pesapal(false, "dxI27aMQvqoYb0ThrbgtZvaPtmY2ImhG", "EvrA3obNtPYsQz+Wy6n3Tp4N11w=");
        //PRODUCTION
        $pesapal = new Simbacode\Moneycake\Pesapal(true, "F0CtCTSRGkLlvm8ojVSg9i7uz2uouLNT", "X867MI1kc9zUgaHuXOIHZJ2fn90=");
        
        $amount = $this->data['amount'];
        $description = $this->data['description'];
        $transaction_id = $this->data['guid'];
        $email = $this->data['email'];
        $phone_numer = $this->data['phone'];
        $first_name = $this->data['firstname'];
        $last_name = $this->data['lastname'];
        $currency = "USD";

        if (!empty($amount) && !empty($description) && !empty($transaction_id) && !empty($email) && !empty($phone_numer) && !empty($first_name) && !empty($last_name)) {

            $urlredirect = base_url() . "pesapal/purchaseredirect";

            $resutl = $pesapal->PostPesapalDirectOrderV4($urlredirect, $amount, $currency, $description, "MERCHANT", $transaction_id, $email, $phone_numer, $first_name, $last_name);
            //pr($resutl->body);
            //$this->set('iframe', $this->pesapal->getOauthRequest());
            ?>
            <iframe src="<?php echo $pesapal->getOauthRequest(); ?>" width="100%" height="700px"  scrolling="no" frameBorder="0">
            <p>Browser unable to load iFrame</p>
            </iframe>

            <?php
        }
        ?>

<?php $this->load->view('Layout/backend/footer', []); ?>    