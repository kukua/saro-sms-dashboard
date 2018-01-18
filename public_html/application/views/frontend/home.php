<?php $this->load->view('Layout/frontend/header', []); ?>
<?php $this->load->view('Layout/frontend/banner', []); ?>

<!-- section start -->
<!-- ================ -->
<div class="section parallax light-translucent-bg parallax-bg-3">
    <div class="container">
        <div class="call-to-action">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="title text-center">BULK WEATHER SMS</h1>
                    <p class="text-center">Get closer to your clients and help them make an impression with instant weather forecasting.It’s easy with us</p>

                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <a href="<?=base_url()?>home/create_account" class="btn btn-default btn-lg">Create Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- section end -->

<!-- section start -->
<!-- ================ -->

<!-- section start -->
<!-- ================ -->
<div class="section gray-bg text-muted footer-top clearfix">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="owl-carousel clients">
                    <div class="client">
                        <a href="http://iihtuganda" target="_blank"><img src="<?=base_url()?>assets/home/images/clinet-1.png" alt=""></a>
                    </div>
                    <div class="client">
                        <a href="http://utamu.ac.ug" target="_blank"><img src="<?=base_url()?>assets/home/images/client-2.png" alt=""></a>
                    </div>
                    <div class="client">
                        <a href="http://sindikasms.com" target="_blank"><img src="<?=base_url()?>assets/home/images/client-3.png" alt=""></a>
                    </div>
                    <div class="client">
                        <a href="http://girlbossinafrica.com/" target="_blank"><img src="<?=base_url()?>assets/home/images/client-4.png" alt=""></a>
                    </div>
                    <div class="client">
                        <a href="http://iuea.ac.ug/" target="_blank"><img src="<?=base_url()?>assets/home/images/client-5.png" alt=""></a>
                    </div>
                    <div class="client">
                        <a href="https://aar-insurance.com/ug/" target="_blank"><img src="<?=base_url()?>assets/home/images/client-6.png" alt=""></a>
                    </div>
                    <div class="client">
                        <a href="http://thepearlguide.co.ug/" target="_blank"><img src="<?=base_url()?>assets/home/images/client-7.png" alt=""></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <blockquote class="inline">
                    <p class="margin-clear">Design is not just what it looks like and feels like. Design is how it works.</p>	
                    <footer><cite title="Source Title">Steve Jobs </cite></footer>
                </blockquote>
            </div>
        </div>
    </div>
</div>
<!-- section end -->

<!-- footer start (Add "light" class to #footer in order to enable light footer) -->
<!-- ================ -->
<?php $this->load->view('Layout/frontend/footer', []); ?>
