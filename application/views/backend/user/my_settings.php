<?php $this->load->view('Layout/backend/header', []); ?>

<?php
$useraccount = $this->db->select('*')->from('users')->where(array('id' => $id))->get()->result();
$useraccount = $useraccount[0];


$sent = $this->db->select('count(*) as counts')->from('sentitems')->where(array('sender' => $id,'status'=>1))->get()->result();
$sent = !empty($sent) ? $sent[0]->counts : "0";

$pending = $this->db->select('count(*) as counts')->from('sentitems')->where(array('sender' => $id,'status'=>0))->get()->result();
$pending = !empty($pending) ? $pending[0]->counts : "0";

$failed = $this->db->select('count(*) as counts')->from('sentitems')->where(array('sender' => $id,'status'=>2))->get()->result();
$failed = !empty($failed) ? $failed[0]->counts : "0";

$sent_email = $this->db->select('count(*) as counts')->from('email_outbox')->where(array('sent_by' => $id,'email_status'=>1))->get()->where('schedule_datetime between "' . $start_date . '" and "' . $end_date . '"')->result();
$sent_email = !empty($sent_email) ? $sent_email[0]->counts : "0";

$pending_email = $this->db->select('count(*) as counts')->from('email_outbox')->where(array('sent_by' => $id,'email_status'=>0))->where('schedule_datetime between "' . $start_date . '" and "' . $end_date . '"')->get()->result();
$pending_email = !empty($pending_email) ? $pending_email[0]->counts : "0";

$failed_email = $this->db->select('count(*) as counts')->from('email_outbox')->where(array('sent_by' => $id,'email_status'=>2))->where('schedule_datetime between "' . $start_date . '" and "' . $end_date . '"')->get()->result();
$failed_email = !empty($failed_email) ? $failed_email[0]->counts : "0";

$contacts_email= $this->db->select('count(eb.id) as counts')->from('email_book eb,email_list el,users u')->where('el.id=eb.list_id and u.id=el.user_id and u.id='.$id)->where('date between "' . $start_date . '" and "' . $end_date . '"')->get()->result();
$contacts_email = !empty($contacts_email) ? $contacts_email[0]->counts : "0";

?>
<div class="panel panel-default">
    <div class="panel-heading">

    </div>
    <div class="row" style="margin-top: 0px">
        <div class="col-md-12 page-profile">
            <div class="profile-full-name">
                <span class="text-semibold"><?php echo $useraccount->fullname; ?></span>'s profile  
                <?php
                echo anchor('#mformModal' . $id, '<i class="fa fa-edit fa-lg"></i> Edit Profile', array('class' => 'btn btn-default btn-xs m-bottom-sm',
                    'data-toggle' => 'modal'));
                ?>
                  <?php
                          echo anchor('#formModal', '
					<span class="shortcut-icon">
						<i class="fa fa-upload"></i>
					</span>
						   <span class="text">Top up </span>', array('class' => 'btn btn-default btn-xs m-bottom-sm', 'data-toggle' => "modal"));
                    ?>

            </div>
            <div class="profile-row">
                <div class="left-col">
                    <div class="profile-block">
                        <div class="panel profile-photo">
                            <img src="<?=$this->spectrum_func->get_gravatar($useraccount->email)?>"/>
                        </div><br>
                    </div>

                    <div class="panel panel-transparent">

                        <div class="list-group">

                            <a href="#" class="list-group-item">
                                <strong>
                                    <?php
                                    $account_owner = $this->db->select('*')
                                            ->from('users')
                                            ->where(array('id' => $useraccount->owner))
                                            ->get()
                                            ->result();
                                    // print_r($useraccount->owner);
                                    echo!empty($account_owner) ? '<strong>Owner :</strong> ' . $account_owner[0]->fullname : "";
                                    ?>
                                </strong>
                            </a>
                        </div>
                    </div>

                    <div class="panel panel-transparent">
                        <div class="panel-heading">
                            <span class="panel-title">SMS Statistics</span>
                        </div>
                        <div class="list-group">
                            <a href="#" class="list-group-item"><strong><?= $sent ?></strong> Sent SMS</a>
                            <a href="#" class="list-group-item"><strong><?= $pending ?></strong> Pending SMS</a>
                            <a href="#" class="list-group-item"><strong><?= $failed ?></strong> Failed SMS</a>
                        </div>
                    </div>
                    
                    <div class="panel panel-transparent">
                        <div class="panel-heading">
                            <span class="panel-title">Email Statistics</span>
                        </div>
                        <div class="list-group">
                            <a href="#" class="list-group-item"><strong><?= $contacts_email ?></strong> Addresses</a>
                            <a href="#" class="list-group-item"><strong><?= $sent_email ?></strong> Sent Email</a>
                            <a href="#" class="list-group-item"><strong><?= $pending_email ?></strong> Pending Email</a>
                            <a href="#" class="list-group-item"><strong><?= $failed_email ?></strong> Failed Email</a>
                        </div>
                    </div>


                    <div class="panel panel-transparent">
                        <div class="panel-heading">
                            <span class="panel-title">Contacts</span>
                        </div>
                        <div class="list-group">
                            <a href="#" class="list-group-item"><i class="profile-list-icon fa fa-phone" style="color: #4ab6d5"></i> <?php echo $useraccount->mobile; ?></a>
                            <a href="#" class="list-group-item"><i class="profile-list-icon fa fa-envelope" style="color: #888"></i> <?php echo $useraccount->email; ?></a>
                        </div>
                    </div>

                </div>
                <div class="right-col">
                    <?php ?>


                    <?php ?>

                    <hr class="profile-content-hr no-grid-gutter-h">

                    <div class="profile-content">

                        <ul id="profile-tabs" class="nav nav-tabs">
                            <li class="active">
                                <a href="#profile-tabs-overview" data-toggle="tab">Overview</a>
                            </li>
                             <li>
                                <a href="#profile-tabs-email-settings" data-toggle="tab">Email Settings</a>
                            </li>
                            <li>
                                <a href="#profile-tabs-sms-timeline" data-toggle="tab">SMS Timeline</a>
                            </li>
                            <li>
                                <a href="#profile-tabs-email-timeline" data-toggle="tab">Email Timeline</a>
                            </li>
                        </ul>

                        <div class="tab-content tab-content-bordered panel-padding">
                            <div class="tab-pane fade widget-followers fade in active" id="profile-tabs-overview">
                                <div class="follower">
                                    <div class="body">
                                        <div class="padding-md">
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="tab-content">
                                                        <div class="tab-pane fade in active" id="overview">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="stat-panel">
                                                                        <!-- Success background. vertically centered text -->
                                                                        <div class="stat-cell bg-<?= $this->spectrum_func->credit_color($useraccount->credits); ?> valign-middle">
                                                                            <!-- Stat panel bg icon -->
                                                                            <i class="fa fa-money bg-icon"></i>
                                                                            <!-- Extra large text -->
                                                                            <span class="text-xlg"><strong><?php echo number_format($useraccount->credits) ?></strong></span><br>
                                                                            <!-- Big text -->
                                                                            <span class="text-bg">SMS Balance (UGX)</span><br>
                                                                        </div> <!-- /.stat-cell -->
                                                                    </div>
                                                                    <div class="stat-panel">
                                                                        <!-- Success background. vertically centered text -->
                                                                        <div class="stat-cell bg-pa-purple valign-middle">
                                                                            <!-- Stat panel bg icon -->
                                                                            <i class="fa fa-mobile-phone bg-icon"></i>
                                                                            <!-- Extra large text -->
                                                                            <span class="text-xlg"><strong><?php echo number_format($sent); ?></strong></span><br>
                                                                            <!-- Big text -->
                                                                            <span class="text-bg">Sent SMS</span><br>
                                                                        </div> <!-- /.stat-cell -->
                                                                    </div>
                                                                    <div class="stat-panel">
                                                                        <!-- Success background. vertically centered text -->
                                                                        <div class="stat-cell bg-<?= $this->spectrum_func->credit_color($useraccount->email_credits); ?> valign-middle">
                                                                            <!-- Stat panel bg icon -->
                                                                            <i class="fa fa-money bg-icon"></i>
                                                                            <!-- Extra large text -->
                                                                            <span class="text-xlg"><strong><?php echo number_format($useraccount->email_credits) ?></strong></span><br>
                                                                            <!-- Big text -->
                                                                            <span class="text-bg">Email Balance (UGX)</span><br>
                                                                        </div> <!-- /.stat-cell -->
                                                                    </div>
                                                                    <div class="stat-panel">
                                                                        <!-- Success background. vertically centered text -->
                                                                        <div class="stat-cell bg-pa-purple valign-middle">
                                                                            <!-- Stat panel bg icon -->
                                                                            <i class="fa fa-envelope bg-icon"></i>
                                                                            <!-- Extra large text -->
                                                                            <span class="text-xlg"><strong><?php echo number_format($sent_email); ?></strong></span><br>
                                                                            <!-- Big text -->
                                                                            <span class="text-bg">Sent Emails</span><br>
                                                                        </div> <!-- /.stat-cell -->
                                                                    </div>

                                                                    
                                                                </div><!-- /.col -->
                                                            </div><!-- /.row -->
                                                            <div class="panel panel-default table-responsive">
                                                                <div class="panel-heading">
                                                                    Latest Payment logs :

                                                                </div>
                                                                <table class="table table-bordered table-condensed table-hover table-striped table-vertical-center">
                                                                    <thead>
                                                                        <tr>
                                                                            <th></th>
                                                                            <th class="text-center">Created</th>
                                                                            <th class="text-center">Amount</th>
                                                                            <th class="text-center">Method</th>
                                                                            <th class="text-center">Status</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $rst = $this->db->select('*')
                                                                                ->from('payments')
                                                                                ->where(array('sender' => $id))
                                                                                ->limit(8, 0)
                                                                                ->order_by('id DESC')
                                                                                ->get()
                                                                                ->result();

                                                                        $count = 1;

                                                                        foreach ($rst as $rows) {
                                                                            ?>
                                                                            <tr>
                                                                                <td class="text-center">
                                                                                    <?php echo $count; ?>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <?php echo (new Cake\I18n\Time($rows->created))->timeAgoInWords(); ?>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <?php echo number_format($rows->messages) . ' UGX'; ?>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <?php echo $rows->method; ?>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <?php echo $rows->status; ?>
                                                                                </td>
                                                                            </tr>
                                                                            <?php
                                                                            $count++;
                                                                        }
                                                                        ?>

                                                                    </tbody>
                                                                </table>
                                                            </div><!-- /panel -->
                                                        </div><!-- /tab1 -->

                                                        <div class="tab-pane fade" id="message">
                                                            <div class="panel panel-default inbox-panel">
                                                                <div class="panel-heading">

                                                                </div>
                                                                <div class="panel-body">




                                                                </div>
                                                                <ul class="list-group">

                                                                    <?php
                                                                    $sent_sms = $this->db->select('*')->from('sentitems')->where('sender = ' . $id . ' and type = "BULK" ')->limit(20, 0)->group_by('message_id')->order_by('id DESC')->get()->result();

                                                                    $counts = 1;
                                                                    foreach ($sent_sms as $rows) {
                                                                        ?>


                                                                        <li class="list-group-item clearfix inbox-item">
                                                                            <label class="label-checkbox inline">
                                                                                (<?php echo $counts ?>)


                                                                                <span class="custom-checkbox"></span>
                                                                            </label>
                                                                            <span class="starred"><i class="fa fa-star fa-lg"></i></span>
                                                                            <span class="from"><?php echo $rows->senderid ?></span>

                                                                            <span class="detail">
                                                                                <?php echo anchor('user/messages/message_details/' . $rows->message_id, $rows->message)
                                                                                ?>
                                                                            </span>
                                                                            <span class="inline-block pull-right">

                                                                                <span class="time"><?php
                                                                                    $date = strtotime($rows->datetime);

                                                                                    $date_time = strtotime(date('Y-m-d H:i:s'));

                                                                                    $date = $date_time - $date;

                                                                                    $date = $date / (60);
                                                                                    $minutes = (int) $date;

                                                                                    $hours = (int) $date / 60;



                                                                                    //echo $minutes.' seconds';

                                                                                    if ((int) $date == 0) {
                                                                                        echo ' a few seconds ago';
                                                                                    } else

                                                                                    if ($minutes < 60) {
                                                                                        echo (int) $date . ' minutes ago';
                                                                                    } else if ($minutes / (60) <= 24) {

                                                                                        $hours = (int) $minutes / 60;
                                                                                        echo (int) $hours . ' hours ago';
                                                                                    } else {

                                                                                        $hours = (int) $minutes / 60;
                                                                                        $hours = $hours / 24;


                                                                                        if ($hours <= 7) {
                                                                                            echo (int) $hours . ' day';

                                                                                            if ($hours == 1) {
                                                                                                echo '';
                                                                                            } else
                                                                                                echo 's';

                                                                                            echo ' ago';
                                                                                        }

                                                                                        else {

                                                                                            echo $rows->datetime;
                                                                                        }
                                                                                    }
                                                                                    ?></span>
                                                                            </span>
                                                                        </li>
                                                                        <?php
                                                                        $counts++;
                                                                    }
                                                                    ?>

                                                                </ul><!-- /list-group -->

                                                            </div><!-- /panel -->
                                                        </div><!-- /tab3 -->
                                                    </div><!-- /tab-content -->
                                                </div><!-- /.col -->
                                            </div><!-- /.row -->
                                        </div><!-- /.padding-md -->



                                    </div>
                                </div>

                            </div> <!-- / .tab-pane -->
                               <div class="widget-article-comments tab-pane panel no-padding no-border" id="profile-tabs-email-settings">                           
                                <?=form_open('user/save_email_settings');?>
                                    <input type="hidden" id="id" name="id" value="<?=$id*date('Y')?>"/>
                                    
                                    <div class="form-group amount_row">
                                        <label for="exampleInputEmail1">Server :</label>
                                        <input type="text" name="email_server" class="form-control" placeholder="Server" value="<?php echo $useraccount->email_server; ?>" id="email_server"required>
                                         <label for="exampleInputEmail1">Port :</label>
                                        <input type="text" name="email_port" class="form-control" placeholder="Port" value="<?php echo $useraccount->email_port; ?>" id="email_port"required>
                                         <label for="exampleInputEmail1">Username :</label>
                                        <input type="text" name="email_username" class="form-control" placeholder="Username" value="<?php echo $useraccount->email_username; ?>" id="email_username"required>
                                         <label for="exampleInputEmail1">Password :</label>
                                        <input type="text" name="email_password" class="form-control" placeholder="Password" value="<?php echo $useraccount->email_password; ?>" id="email_password"required>
                                        <label for="exampleInputEmail1">Is Secure</label>
                                        <select name="email_auth" id="email_auth" class="form-control ">
                                            <option value="0" <?php echo $useraccount->email_auth == 0 ? "selected='selected'" : "" ?>>False</option>
                                            <option value="1" <?php echo $useraccount->email_auth == 1 ? "selected='selected'" : "" ?>>True</option>

                                        </select>
                                          <label for="exampleInputEmail1">Is SMTP</label>
                                        <select name="email_is_smtp" id="email_is_smtp" class="form-control ">
                                            <option value="0" <?php echo $useraccount->email_is_smtp == 0 ? "selected='selected'" : "" ?>>False</option>
                                            <option value="1" <?php echo $useraccount->email_is_smtp == 1 ? "selected='selected'" : "" ?>>True</option>

                                        </select>
                                        <label for="exampleInputEmail1">Security Option</label>
                                        <select name="email_secure" id="email_secure" class="form-control ">
                                            <option value="tls" <?php echo $useraccount->email_secure == 'tls' ? "selected='selected'" : "" ?>>TLS</option>
                                            <option value="ssl" <?php echo $useraccount->email_secure == 'ssl' ? "selected='selected'" : "" ?>>SSL</option>

                                        </select>
                                          
                                          <div class="form-group panel-footer clearfix">

                                            <button name="save_per_email" class="btn button-orange"><i class="icon-save"></i> Save</button>
                                    </div><!-- /form-group -->
                                    </div><!-- /form-group -->
                                   
                                <?=form_close()?>
                            </div>
                            
                            <div class="tab-pane fade" id="profile-tabs-sms-timeline">
                                <?php
                                $rst = $this->db->select('*,sentitems.status as delivery_status,sum(charge) as totalcharge')
                                                ->from('sentitems, routes, users')
                                                ->where('sentitems.routeid = routes.id and users.id = sentitems.sender and sender=' . $id)
                                                ->where('sentitems.date between "' . $start_date . '" and "' . $end_date . '"')
                                                ->order_by('sentitems.id DESC')
                                                ->group_by('sentitems.message_id')
                                                ->limit(8, 0)
                                                ->get()->result();
                                foreach ($rst as $rows):
                                    ?>
                                    <div class="timeline">
                                        <!-- Timeline header -->
                                        <div class="tl-header now">  
                                            <?php
                                            echo (new Cake\I18n\Time($rows->datetime))->timeAgoInWords();
                                            ?></div>

                                        <div class="tl-entry">
                                            <div class="tl-time">
                                                <?= $rows->contacts ?>&nbsp;<?= humanize('contact') ?>
                                            </div>
                                            <div class="tl-icon bg-<?= $this->spectrum_func->status_color($rows->delivery_status) ?>"><i class="fa fa-mobile-phone"></i></div>
                                            <div class="panel tl-body">

                                                <?php echo anchor('admin_messages/message_details/' . $rows->message_id, $rows->message); ?>

                                            </div> <!-- / .tl-body -->
                                        </div> <!-- / .tl-entry -->
                                    </div> <!-- / .timeline -->
                                <?php endforeach; ?>
                            </div> <!-- / .tab-pane -->
                            <div class="tab-pane fade" id="profile-tabs-email-timeline">
                                <?php
                                $rst = $this->db
                                                ->select('e.*,count(*) as counts,u.fullname, sum(charge) as charge')
                                                ->from('email_outbox e, users u')
                                                ->where('e.sent_by = u.id and sent_by=' . $id)
                                                ->where('e.created_at between "' . $start_date . '" and "' . $end_date . '"')
                                                ->order_by('e.id DESC')
                                                ->group_by('e.uuid')
                                                ->limit(8, 0)
                                                ->get()->result();
                                foreach ($rst as $rows):
                                    ?>
                                    <div class="timeline">
                                        <!-- Timeline header -->
                                        <div class="tl-header now">  
                                            <?php
                                            echo (new Cake\I18n\Time($rows->created_at))->timeAgoInWords();
                                            ?></div>

                                        <div class="tl-entry">
                                            <div class="tl-time">
                                                <?= $rows->counts ?>&nbsp;<?= humanize('addresses') ?>
                                            </div>
                                            <div class="tl-icon bg-<?= $this->spectrum_func->status_color($rows->email_status) ?>"><i class="fa fa-envelope"></i></div>
                                            <div class="panel tl-body">                                   
                                                <?= $rows->email_title ?>
                                            </div> <!-- / .tl-body -->
                                        </div> <!-- / .tl-entry -->
                                    </div> <!-- / .timeline -->
                                <?php endforeach; ?>
                            </div> <!-- / .tab-pane -->                           
            </div>

        </div>
    </div>
</div>
</div>


<?php
$rst = $this->db->select('*')->from('users')->where(array('id' => $id))->get()->result();

foreach ($rst as $rows) {
    ?>

    <?php echo form_open('user/edit_account_single/' . $id * date('Y')) ?>

    <div class="modal fade" id="mformModal<?php echo $rows->id ?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4>Update Account information</h4>
                </div>
                <div class="modal-body">


                    <input type="hidden" name="id" value="<?php echo $rows->id ?>">

                    <!---->

                    <div class="panel panel-default">

                        <div class="panel-body">


                            <div class="form-group">
                                <label for="exampleInputEmail1">Full Name:</label>
                                <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Full Name"  value="<?php echo $rows->fullname ?>" name="fullname_edit"  required="required">
                            </div><!-- /form-group -->


                            <div class="form-group">
                                <label for="exampleInputEmail1">Mobile :</label>
                                <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Mobile" name="mobile_edit"  value="<?php echo $rows->mobile ?>"  required="required">
                            </div><!-- /form-group -->

                            <div class="form-group">
                                <label for="exampleInputEmail1">Email Address :</label>
                                <input type="email" class="form-control input-sm" id="exampleInputEmail1" placeholder="Email Address" name="email_edit"  value="<?php echo $rows->email ?>"  required="required">
                            </div><!-- /form-group -->

                        </div>
                    </div>

                    <div class="form-group text-right">
                        <button href="#" class="btn btn-success"><i class="fa fa-edit"></i> Edit User Account</button>

                    </div>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->

    </div>
    <?php echo form_close(); ?>
    <?php
}
?>
    
    <div class="modal fade" id="formModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4>Top up credit</h4>
            </div>
            <div class="modal-body">
<?php echo form_open('user/top_up') ?>

                <div class="panel panel-default">

                    <div class="panel-body">

                         <label class="control-label">Select Service</label>
                        <select id="pesapalcategory" name="pesapalcategory"  class="form-control" >
                            <option value="sms" selected="selected">SMS</option>
                            <option value="email" >Email</option>
                        </select>
                        
                        <div class="form-group">
                            <label for="exampleInputEmail1">Amount:</label>
                            <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Amount" name="amount"  required="required">
                        </div><!-- /form-group -->

                    </div>
                </div>

                <div class="form-group text-right">
                    <button href="#" class="btn btn-success"><i class="fa fa-plus"></i> Top Up </button>

                </div>
<?php echo form_close(); ?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
    
</div>
<?php $this->load->view('Layout/backend/footer', []); ?>    