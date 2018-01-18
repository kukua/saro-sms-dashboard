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
                    <?php echo form_open('home/login', array('class' => 'form-horizontal', 'id' => 'login-form'));?>
                        <div class="form-group has-feedback">
                            <label for="inputUserName" class="col-sm-3 control-label">User Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="email" name="email" placeholder="Email" required>
                                <i class="fa fa-user form-control-feedback"></i>
                            </div>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="inputPassword" class="col-sm-3 control-label">Password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                <i class="fa fa-lock form-control-feedback"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-8">                                										
                                <button type="submit" class="btn btn-group btn-default btn-sm">Log In</button>
                                <ul>
                                    <li><?php echo anchor('home/forgot_password', 'Forgotten password?'); ?></li>
                                </ul>
                            </div>
                        </div>
                    <?php echo form_close();?>
                </div>
                <p class="text-center space-top">Don't have an account yet? <?php echo anchor('home/create_account', 'Create SMS Account!'); ?> now.</p>
            </div>
            <!-- main end -->

        </div>
    </div>
</section>
<!-- main-container end -->
 <?php $this->load->view('Layout/frontend/footer', []); ?>