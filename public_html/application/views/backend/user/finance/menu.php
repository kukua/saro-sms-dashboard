<div class="grey-container shortcut-wrapper">

    <?php

    /*echo anchor('#formModal','
					<span class="shortcut-icon">
						<i class="fa fa-plus"></i>
					</span>
						   <span class="text">Create Account </span>',array('class'=>'shortcut-link','data-toggle'=>"modal"));
    echo anchor('admin_administration/download','
					<span class="shortcut-icon">
						<i class="fa fa-download"></i>
					</span>
						   <span class="text">Downnload Accounts </span>',array('class'=>'shortcut-link'));*/
 

    ?>

    <h1><i class="fa fa-money"></i> Finance Statistics</h1>


</div><!-- /grey-container -->


    <div class="modal fade" id="formModal">
        
 <?php echo form_open('admin_administration/create_account')?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4>Update Account information</h4>
                </div>
                <div class="modal-body">

                    <!---->

                    <div class="panel panel-default">

                        <div class="panel-body">


                            <div class="form-group">
                                <label for="exampleInputEmail1">Full Name:</label>
                                <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Full Name"  value="" name="fullname"  required="required">
                            </div><!-- /form-group -->


                            <div class="form-group">
                                <label for="exampleInputEmail1">Mobile :</label>
                                <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Mobile" name="mobile"  value=""  required="required">
                            </div><!-- /form-group -->

                            <div class="form-group">
                                <label for="exampleInputEmail1">Email Address :</label>
                                <input type="email" class="form-control input-sm" id="exampleInputEmail1" placeholder="Email Address" name="email"  value=""  required="required">
                            </div><!-- /form-group -->



                            <div class="form-group">
                                <label for="exampleInputEmail1">New Password:</label>
                                <input type="password" class="form-control input-sm" id="exampleInputEmail1" placeholder="Password" name="password"  required="required">
                            </div><!-- /form-group -->


                            <div class="form-group">
                                <label for="exampleInputEmail1">New Password Again:</label>
                                <input type="password" class="form-control input-sm" id="exampleInputEmail1" placeholder="Password Again" name="repassword"  required="required">
                            </div><!-- /form-group -->

                            <div class="form-group">
                                <label for="exampleInputEmail1">Account Type:</label>
                                <select name="accounttype" class="form-control input-sm" id="exampleInputEmail1" >
                                    <option value="reseller">Reseller</option>
                                    <option value="user">Normal User</option>
                                </select>
                            </div><!-- /form-group -->





                        </div>
                    </div>




                    <!---->








                    <div class="form-group text-right">
                            <button href="#" class="btn btn-success"><i class="fa fa-plus"></i> Create User Account</button>

                        </div>

                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->

    <?php echo form_close();?>
    </div>