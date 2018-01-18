<?php
$this->load->view('Layout/backend/header', []);

$email_item_logs = [];

$email_item_logs = $this->db
                ->select('e.*,count(*) as counts,u.fullname, sum(charge) as charge')
                ->from('email_outbox e, users u')
                ->where('e.sent_by = u.id')
                ->where('e.created_at between "' . $start_date . '" and "' . $end_date . '"')
                ->where('e.emailtype = "CRM BULK"')
                ->order_by('e.id DESC')
                ->group_by('e.uuid')
                ->get()->result();

$sent_item_logs = $this->db->select('*,routes.name as routename,sentitems.status as delivery_status,sum(charge) as totalcharge')
                ->from('sentitems, routes, users')
                ->where('sentitems.routeid = routes.id and users.id = sentitems.sender')
                ->where('sentitems.date between "' . $start_date . '" and "' . $end_date . '"')
                 ->where('sentitems.type = "CRM BULK"')
                ->order_by('sentitems.id DESC')
                ->group_by('sentitems.message_id')
                ->get()->result();
?>

<div class="panel panel-default">
    <div class="panel-heading">
    </div>
    <?php $this->load->view('backend/admin/crm/menu'); ?>
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-6">

            <script>
                init.push(function () {
                    $('#jq_email_log').dataTable();
                    $('#jq_email_log_wrapper .table-caption').text('This Month Email Logs');
                    $('#jq_email_log_wrapper .dataTables_filter input').attr('placeholder', 'Search...');
                });
            </script>
            <div class="table-light">
                <div role="grid" id="jq_email_log_wrapper" class="dataTables_wrapper form-inline no-footer">

                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="jq_email_log" aria-describedby="jq-datatables-example_info">
                        <thead>
                            <tr role="row">
                                <th class="sorting_asc" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column ascending" style="width: 2%;">
                                    #
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                                    Created
                                </th>  
                                <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 30%;">
                                    Subject
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                                    Action
                                </th>
                        </thead>
                        <tbody>
                            <?php
                            $count = 1;
                            foreach ($email_item_logs as $email_item):
                                ?>
                                <tr>
                                    <td><?php echo $count++ ?></td>
                                    <td><?php
                                        echo (new Cake\I18n\Time($email_item->created_at))->timeAgoInWords();
                                        ?>
                                    </td>                        
                                    <td><?php echo anchor('admin_emails/email_outbox_list/' . ($email_item->uuid), $email_item->email_title); ?></td>
                                    <td>
                                        <?php echo $this->spectrum_func->delivery_status($email_item->email_status); ?> &nbsp;
                                        <?php echo anchor('admin_emails/resend_sent_email/' . $email_item->uuid, '<i class="fa fa-refresh fa-lg"></i> ', array('onclick' => "return confirm('You are about to resend this message')"));
                                        ?>&nbsp;
                                        <?php echo anchor('admin_emails/download_sent_emails/' . $email_item->uuid, '<i class="fa fa-download fa-lg"></i>', array('class' => '')); ?>
                                    </td>


                                </tr>
                                <?php
                            endforeach;
                            ?>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        
        
        <div class="col-md-6">

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
                                        <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 8%;">
                                            Created
                                        </th>                    
                                        <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 20%">
                                            Message
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                                            Scheduled
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
                                            <td><?php
                                                echo (new Cake\I18n\Time($sent_item->datetime))->timeAgoInWords();
                                                ?></td>
                                            <td width="25%"><?php echo anchor('admin_messages/message_details/' . $sent_item->message_id, $sent_item->message); ?></td>
                                     
                                            <td><?php echo (new Cake\I18n\Time($sent_item->schedule_datetime))->timeAgoInWords(); ?></td>
                                            <td>
                                                <?php echo $this->spectrum_func->delivery_status($sent_item->delivery_status); ?> &nbsp;
                                                <?php
                                                if ($sent_item->delivery_status == 0):
                                                    echo anchor('admin_messages/send_now/' . $sent_item->message_id, '<i class="fa fa-rocket fa-lg"></i> ', array('onclick' => "return confirm('You are about to send this message')"));
                                                endif;
                                                ?>
                                                <?php echo anchor('admin_messages/resend_sent_messages/' . $sent_item->message_id, '<i class="fa fa-refresh fa-lg"></i> ', array('onclick' => "return confirm('You are about to resend this message')"));
                                                ?>&nbsp;
                                                <?php echo anchor('admin_messages/download_messages/' . $sent_item->message_id, '<i class="fa fa-download fa-lg"></i>', array('class' => '')); ?>
                                            </td>


                                        </tr>
                                        <?php
                                    endforeach;
                                    ?>

                                </tbody>
                            </table>

                        </div>
                    </div>
        </div>
        
        
        
    </div>
</div>

<?php $this->load->view('Layout/backend/footer', []); ?>