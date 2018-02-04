<?php
$this->load->view('Layout/backend/header', []);

$sent_item_logs = $this->db->select('*,sentitems.id as sentitemid,routes.name as routename,sentitems.status as delivery_status')
                ->from('sentitems,routes')
                ->where('sentitems.routeid = routes.id and sentitems.message_id = "' . $message_id . '"')
                ->where('sentitems.sender='.$this->session->userdata('id'))
                ->get()->result();

$sent_item = $this->db->select('*,sentitems.id as sentitemid,routes.name as routename,sentitems.status as delivery_status,sum(charge) as totalcharge')
                ->from('sentitems, routes, users')
                ->where('sentitems.routeid = routes.id and users.id = sentitems.sender')
                ->where(' sentitems.message_id = "' . $message_id . '"')
                ->where('sentitems.date >= "' . $start_date . '" <= "' . $end_date . '"')
                ->where('sentitems.sender='.$this->session->userdata('id'))
                ->order_by('sentitems.id DESC')
                ->group_by('sentitems.message_id')
                ->get()->result();

$sent_item = $sent_item[0];

?>

<?php $this->load->view('backend/user/messages/menu'); ?>
<div class="row">
    <div class="col-md-12">

        <!-- 5. $PROFILE_WIDGET_LINKS_EXAMPLE ==============================================================
        
                                        Profile widget - Links example
        -->
        <div class="panel panel-<?php echo $this->spectrum_func->status_color($sent_item->status); ?> panel-dark widget-profile">
            <div class="panel-heading">
                <div class="widget-profile-bg-icon"><i class="fa fa-envelope"></i></div>
                <div class="widget-profile-header">
                    <h1>from <b><?php echo $sent_item->fullname; ?></b></h1>

                </div>

            </div> <!-- / .panel-heading -->
            <div class="list-group">
                <a href="#" class="list-group-item" style="color: #000;font-size: 16px;padding-top: 20px;padding-bottom: 20px;">
                    <b> <?php echo $sent_item->message; ?></b>
                </a>
                <a href="#" class="list-group-item"><i class="fa fa-clock-o list-group-icon"></i><?php echo (new Cake\I18n\Time($sent_item->datetime))->timeAgoInWords(); ?></a>
                <a href="#" class="list-group-item"><i class="fa fa-money list-group-icon"></i>Total Charge<span class="badge badge-success"><?php echo number_format($sent_item->totalcharge) ?></span></a>
                <?php echo anchor('user_messages/resend_sent_messages/' . $sent_item->message_id, '<i class="list-group-icon fa fa-refresh"></i> Resend BULK', array('onclick' => "return confirm('You are about to resend this message')", 'class' => 'list-group-item'));
                ?>

                <?php echo anchor('user_messages/download_messages/' . $sent_item->message_id, '<i class="fa fa-download list-group-icon"></i> Download', array('class' => 'list-group-item')); ?>
                <a href="#" class="list-group-item"><i class="fa fa-users list-group-icon"></i>Contacts<span class="badge badge-info"><?php echo number_format($sent_item->contacts) ?></span></a>
                <a href="#" class="list-group-item">
                    <!-- SENT MESSAGE LOGS !-->
                    <script>
                        init.push(function () {
                            $('#jq_message_log').dataTable();
                            $('#jq_message_log_wrapper .table-caption').text('This Month Message Logs');
                            $('#jq_message_log_wrapper .dataTables_filter input').attr('placeholder', 'Search...');
                        });
                    </script>
                    <div class="table-light">
                        <div role="grid" id="jq_message_log_wrapper" class="dataTables_wrapper form-inline no-footer">

                            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="jq_message_log" aria-describedby="jq-datatables-example_info">
                                <thead>
                                    <tr role="row">
                                        <th class="sorting_asc" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column ascending" style="width: 2%;">
                                            #
                                        </th> 
                                        <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 20%;">
                                            Receiver
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 30%;">
                                            Charge (USD)
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($sent_item_logs as $sent_item):
                                        ?>
                                        <tr>
                                            <td><?php echo $count++ ?></td>
                                            <td><?php echo $sent_item->receiver ?></td>
                                            <td><?php echo number_format($sent_item->charge) ?></td>
                                            <td>
                                                <?php echo $this->spectrum_func->delivery_status($sent_item->delivery_status); ?> &nbsp;
                                                <?php echo $sent_item->status==1?anchor('user_messages/resend_sent_one_message/' . $sent_item->sentitemid, '<i class="fa fa-refresh fa-lg"></i> ', array('onclick' => "return confirm('You are about to resend this message')")):"";
                                                ?>
                                                <?php echo $sent_item->delivery_status==0? anchor('user_messages/delete_message/' . $sent_item->message_id, '<i class="fa fa-trash-o fa-lg"></i> ', array('onclick' => "return confirm('You are about to resend this message')")):"";?>

                                            </td>


                                        </tr>
                                        <?php
                                    endforeach;
                                    ?>

                                </tbody>
                            </table>

                        </div>
                    </div>
                </a>
            </div>
        </div> <!-- / .panel -->
        <!-- /5. $PROFILE_WIDGET_LINKS_EXAMPLE -->

    </div>
</div>

<?php $this->load->view('Layout/backend/footer', []); ?>    