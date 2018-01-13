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
                    <h2 class="title">Create FREE SMS Account</h2>
                    <hr>
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
                        <?php  echo form_open('home/create_account', array('id' => 'form-register','class'=>'form-horizontal','role'=>'form'));?>
                        <div class="form-group has-feedback">
                            <label for="inputName" class="col-sm-3 control-label">Full Name <span class="text-danger small">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Full name" required>
                                <i class="fa fa-pencil form-control-feedback"></i>
                            </div>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="inputLastName" class="col-sm-3 control-label">Mobile Phone <span class="text-danger small">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile phone" required>
                                <i class="fa fa-pencil form-control-feedback"></i>
                            </div>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="inputUserName" class="col-sm-3 control-label">User Name <span class="text-danger small">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="inputUserName" placeholder="User Name" required>
                                <i class="fa fa-user form-control-feedback"></i>
                            </div>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="inputEmail" class="col-sm-3 control-label">Email <span class="text-danger small">*</span></label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                                <i class="fa fa-envelope form-control-feedback"></i>
                            </div>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="inputPassword" class="col-sm-3 control-label">Password <span class="text-danger small">*</span></label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                <i class="fa fa-lock form-control-feedback"></i>
                            </div>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="inputPassword" class="col-sm-3 control-label">Confirm Password <span class="text-danger small">*</span></label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="passwordagain" name="passwordagain" placeholder="Password" required>
                                <i class="fa fa-lock form-control-feedback"></i>
                            </div>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="inputTokenNumber" class="col-sm-3 control-label">Captcha <span class="text-danger small">*</span> </label>
                            <div class="col-sm-8">
                                <div class="form-group g-recaptcha" data-sitekey="<?php echo '6LeO8xoTAAAAAICq4d5RW5FoO0_SlbKKMHLVcnp0'; ?>"></div>
                                <script type="text/javascript"
                                        src="https://www.google.com/recaptcha/api.js?hl=<?php echo $lang; ?>">
                                </script>
                                <div class="error">
                                </div>
                                <i class="fa fa-info form-control-feedback"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-8">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="term" required> Accept our <a href="#">privacy policy</a> and <a href="#">customer agreement</a>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-8">
                                <button type="submit" class="btn btn-default">Sign Up</button>
                            </div>
                        </div>
                       <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-8">
                                Already signed up? <?php echo anchor('home/login', 'Login here!'); ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- main end -->

        </div>
    </div>
</section>
<!-- main-container end -->

<script>
    function isNumber(input)
    {
        return /^\+?[-0-9]*$/.test(input);
    }

    $(function () {


        $("body").delegate("#mobile", "click keyup", function () {



            var credits = $("#mobile").val();

            if (isNumber($("#mobile").val()))
            {

            } else {

                $("#mobile").val(credits.substring(0, credits.length - 1));

            }

        }

        );

    });
</script>
<?php $this->load->view('Layout/frontend/footer', []); ?>
