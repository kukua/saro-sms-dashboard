<div class="grey-container shortcut-wrapper   ">

    <?php
    if (isset($id)):
        echo anchor('#nformModal', '
					<span class="shortcut-icon">
						<i class="fa fa-plus"></i>
					</span>
						   <span class="text">Create Address </span>', array('class' => 'shortcut-link', 'data-toggle' => "modal"));
    endif;
    ?>
    <?php
    if (isset($id)):
        echo anchor('admin_emails/upload_contacts/' . $id * date('Y'), '
					<span class="shortcut-icon">
						<i class="fa fa-upload"></i>
					</span>
						   <span class="text">Upload Contacts </span>', array('class' => 'shortcut-link', 'data-toggle' => "modal"));
    endif;
    ?>
    <?php
    echo anchor('#formModal', '
					<span class="shortcut-icon">
						<i class="fa fa-plus"></i>
					</span>
						   <span class="text">Create Contact List </span>', array('class' => 'shortcut-link', 'data-toggle' => "modal"));
    ?>
    <?php
    echo anchor('admin_emails/send_emails', '
					<span class="shortcut-icon">
						<i class="fa fa-location-arrow"></i>
					</span>
						   <span class="text">Send</span>', array('class' => 'shortcut-link'));
    ?>

    <?php
    echo anchor('admin_emails/send_personalised_email', '
					<span class="shortcut-icon">
						<i class="fa fa-envelope"></i>
					</span>
						   <span class="text">Send Personalised </span>', array('class' => 'shortcut-link'))
    ?>


    <?php
    echo anchor('admin_emails/email_addresses', '
					<span class="shortcut-icon">
						<i class="fa fa-book"></i>
					</span>
						   <span class="text">Addresses </span>', array('class' => 'shortcut-link'));
    ?>

    <?php
    $messages = 0;
    echo anchor('admin_emails/sent_emails', '
					<span class="shortcut-icon">
						<i class="fa fa-tasks"></i>' . ($messages > 0 ? '<span class="shortcut-alert">' . number_format($messages) . '</span>' : '') . '</span>
						   <span class="text">Sent </span>', array('class' => 'shortcut-link'));
    ?>
    <?php
    echo anchor('admin_emails/failed_emails', '
					<span class="shortcut-icon">
						<i class="fa fa-exclamation"></i>
					</span>
						   <span class="text">Failed Emails </span>', array('class' => 'shortcut-link'))
    ?>
    
    <?php
    $rst =[];
     if($this->session->userdata('dept')=="admin"):
    $rst = $this->db->select('count(*) as counts')->from('email_outbox')->where(array('email_status' => 0))->get()->result();
     else:
         $rst = $this->db->select('count(*) as counts')->from('email_outbox')->where(array('email_status' => 0,'sent_by'=>$this->session->userdata('id')))->get()->result();
   
     endif;
    
    ?>

    <?php
    echo anchor('admin_emails/scheduled_emails', '<span class="shortcut-icon">
						<i class="fa fa-clock-o"></i>
						<span class="shortcut-alert">
							' . number_format($rst[0]->counts) . '
						</span>
					</span>
        <span class="text">Queue</span>', array('class' => 'shortcut-link'));
    ?>

    <?php
    echo anchor('admin_emails/templates', '
					<span class="shortcut-icon">
						<i class="fa fa-html5"></i>
					</span>
						   <span class="text">Templates</span>', array('class' => 'shortcut-link'));
    ?>


</div><!-- /grey-container -->


<div class="modal fade" id="formModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4>Create Email list</h4>
            </div>
            <div class="modal-body">
<?php echo form_open('admin_emails/create_email_list') ?>

                <div class="panel panel-default">

                    <div class="panel-body">


                        <div class="form-group">
                            <label for="exampleInputEmail1">Email List:</label>
                            <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Email list" name="emaillist"  required="required">
                        </div><!-- /form-group -->

                    </div>
                </div>

                <div class="form-group text-right">
                    <button href="#" class="btn btn-success"><i class="fa fa-plus"></i> Create </button>

                </div>
<?php echo form_close(); ?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div class="modal fade " id="nformModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4>New Mobile Phone</h4>
            </div>
            <div class="modal-body">
<?php echo form_open('admin_emails/save_address') ?>

                <!---->

                <div class="panel panel-default">

                    <div class="panel-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Fullname:</label>
                            <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Fullname" value="" name="fullname"  required="required">
                        </div><!-- /form-group -->


                        <div class="form-group">
                            <label for="exampleInputEmail1">Email Addresst:</label>
                            <input type="email" class="form-control input-sm" id="exampleInputEmail1" placeholder="Email Address" value="" name="email_address"  required="required">
                        </div><!-- /form-group -->


                        <input type="hidden" class="form-control input-sm" id="list_id" placeholder="list_id" value="<?= !empty($id)?$id * date('Y'):"" ?>" name="list_id" >




                    </div>
                </div>


                <div class="form-group text-right">
                    <button href="#" class="btn btn-success"><i class="fa fa-plus"></i> Create </button>

                </div>
<?php echo form_close(); ?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>