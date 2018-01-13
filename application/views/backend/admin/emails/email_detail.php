<?php
$this->load->view('Layout/backend/header', []);


$sent_item = $this->db->select('*,email_outbox.email_status as delivery_status,sum(email_outbox.charge) as totalcharge')
                ->from('email_outbox, users')
                ->where('users.id = email_outbox.sent_by')
                ->where('email_outbox.id = "' . $id . '"')
                 ->limit(100,0)
                ->order_by('email_outbox.id DESC')
                ->group_by('email_outbox.uuid')
                ->get()->result();

$sent_item = $sent_item[0];

?>

<?php $this->load->view('backend/admin/emails/menu'); ?>
<div class="row">
    <div class="col-md-12">

        <!-- 5. $PROFILE_WIDGET_LINKS_EXAMPLE ==============================================================
        
                                        Profile widget - Links example
        -->
        <div class="panel panel-<?php echo $this->spectrum_func->status_color($sent_item->delivery_status); ?> panel-dark widget-profile">
            <div class="panel-heading">
                <div class="widget-profile-bg-icon"><i class="fa fa-envelope"></i></div>
                <div class="widget-profile-header">
                    <h1>from <b><?php echo $sent_item->fullname; ?></b></h1>

                </div>

            </div> <!-- / .panel-heading -->
            <div class="list-group">
                 <a href="#" class="list-group-item" style="color: #000;font-size: 16px;padding-top: 20px;padding-bottom: 20px;">
                    <b> <?php echo $sent_item->email_title; ?></b>
                </a>
                <a href="#" class="list-group-item"><i class="fa fa-clock-o list-group-icon"></i><?php echo (new Cake\I18n\Time($sent_item->created_at))->timeAgoInWords(); ?></a>
                <a href="#" class="list-group-item"><i class="fa fa-money list-group-icon"></i>Total Charge<span class="badge badge-success"><?php echo number_format($sent_item->totalcharge) ?></span></a>
                <?php echo anchor('Admin_emails/resend_sent_email/' . $sent_item->uuid, '<i class="list-group-icon fa fa-refresh"></i> Resend Email', array('onclick' => "return confirm('You are about to resend this message')", 'class' => 'list-group-item'));
                ?>

                 <a href="#" class="list-group-item">
                     <h2> <?php echo $sent_item->error_message; ?></h2>

                </a>
                
                <?php echo anchor('Admin_emails/download_email/' . $sent_item->uuid, '<i class="fa fa-download list-group-icon"></i> Download', array('class' => 'list-group-item')); ?>
                <a href="#" class="list-group-item">
                  <b> <?php echo $sent_item->email_body; ?></b>

                </a>
            </div>
        </div> <!-- / .panel -->
        <!-- /5. $PROFILE_WIDGET_LINKS_EXAMPLE -->

    </div>
</div>

<?php $this->load->view('Layout/backend/footer', []); ?>    