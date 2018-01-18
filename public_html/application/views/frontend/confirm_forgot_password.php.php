<?php $this->load->view('Layout/frontend/header', []); ?>
<!-- main-container start -->
<!-- ================ -->
<section class="main-container">

    <div class="container">
        <div class="row">

            <!-- main start -->
            <!-- ================ -->
            <div class="main object-non-visible" data-animation-effect="fadeInDownSmall" data-effect-delay="300">
                <div class="form-block center-block">
                    <h2 class="title">Login</h2>
                    <p>
                         <?php
                            if (strlen($message) > 0) {
                                ?>
                                <div class="alert <?php echo $alert ?>">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <h4><?php echo $message ?></h4>
                                </div>
                                <?php
                            }?>
                    </p>
                    <hr>
                </div>
                <p class="text-center space-top">Don't have an account yet? <?php echo anchor('home/create_account', 'Create SMS Account!'); ?> now.</p>
            </div>
            <!-- main end -->

        </div>
    </div>
</section>
<!-- main-container end -->
 <?php $this->load->view('Layout/frontend/footer', []); ?>