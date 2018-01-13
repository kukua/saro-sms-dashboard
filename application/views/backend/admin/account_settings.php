<div class="panel panel-default">
    <div class="panel-heading">
        
    </div>

<div class="padding-md clearfix col-sm-10">

    <?php
    if(strlen($message) > 0 )
    {
        ?>

        <div class="alert <?php echo $alert?>">
            <center> <?php
                echo $message;
                ?></center>

        </div>

    <?php
    }

    ?>

<div class="panel panel-default">
    <?php echo form_open('',array('class'=>'form-horizontal form-border'));?>
<?php
$rst = $this->db->select('*')->from('users')->where(array('id'=>$this->session->userdata('id')))->get()->result();
$rst = $rst[0];
?>
        <div class="panel-heading">
         <h5>  <i class="fa fa-cogs"></i> Account Settings </h5>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label class="control-label col-md-2">Full Name</label>
                <div class="col-md-10">
                    <input type="text" name="fullname" class="form-control input-sm" placeholder="Fullname Name"required="required" value="<?php echo $rst->fullname?>" >
                </div><!-- /.col -->
            </div><!-- /form-group -->

            <div class="form-group">
                <label class="control-label col-md-2">Mobile</label>
                <div class="col-md-10">
                    <input type="text" name="mobile" class="form-control input-sm" placeholder="Mobile Address"required="required" value="<?php echo $rst->mobile?>" >
                </div><!-- /.col -->
            </div><!-- /form-group -->



            <div class="form-group">
                <label class="control-label col-md-2">Email</label>
                <div class="col-md-10">
                    <input type="email" name="email" class="form-control input-sm" placeholder="Email" required="required" value="<?php echo $rst->email?>">
                </div><!-- /.col -->
            </div><!-- /form-group -->

            <div class="form-group">
                <label class="control-label col-md-2">Old Password</label>
                <div class="col-md-10">
                    <input type="password" class="form-control input-sm" name="oldpassword" placeholder="Old Password" required="required">
                </div><!-- /.col -->
            </div><!-- /form-group -->

            <div class="form-group">
                <label class="control-label col-md-2">New Password</label>
                <div class="col-md-10">
                    <input type="password" class="form-control input-sm" name="newpassword" placeholder="New Password" required="required">
                </div><!-- /.col -->
            </div><!-- /form-group -->

            <div class="form-group">
                <label class="control-label col-md-2">New Password Again</label>
                <div class="col-md-10">
                    <input type="password" class="form-control input-sm" name="newpasswordagain" placeholder="New Password Again" required="required">
                </div><!-- /.col -->
            </div><!-- /form-group -->


        </div>
        <div class="panel-footer">
            <div class="text-right">
                <button class="btn btn-sm btn-default"><i class="fa fa-save"></i> Update</button>

            </div>
        </div>
<?php echo form_close();?>
</div><!-- /panel -->
    </div>
</div>