<?php
$this->load->view('Layout/backend/header', []);
$scheduled_item_logs=[];

if($this->session->userdata('dept')=="admin"):
$scheduled_item_logs = $this->db->select('*,count(*) as contacts,email_outbox.email_status as delivery_status,sum(charge) as totalcharge')
                ->from('email_outbox, users')
                ->where('users.id = email_outbox.sent_by and email_outbox.email_status = 0')
                ->where('email_outbox.created_at >= "' . $start_date_year . '" <= "' . $end_date_year . '"')
        ->limit(100,0)
                ->order_by('email_outbox.id DESC')
                ->group_by('email_outbox.uuid')
                ->get()->result();
else:
    $scheduled_item_logs = $this->db->select('*,count(*) as contacts,email_outbox.email_status as delivery_status,sum(charge) as totalcharge')
                ->from('email_outbox, users')
                ->where('users.id = email_outbox.sent_by and email_outbox.email_status = 0')
                ->where('email_outbox.created_at >= "' . $start_date_year . '" <= "' . $end_date_year . '"')
                ->where('users.id='.$this->session->userdata('id'))
        ->limit(100,0)
                ->order_by('email_outbox.id DESC')
                ->group_by('email_outbox.uuid')
                ->get()->result();
endif;
?>
<div class="panel panel-default">
    <div class="panel-heading">
    </div>

    <?php $this->load->view('backend/admin/emails/menu'); ?>

    <!-- SENT MESSAGE LOGS !-->
    <script>
        init.push(function () {
            $('#jq_message_log').dataTable();
            $('#jq_message_log_wrapper .table-caption').text('Scheduled Emails');
            $('#jq_message_log_wrapper .dataTables_filter input').attr('placeholder', 'Search...');
        });
    </script>
    <div class="table-light">
        <div role="grid" id="jq_message_log_wrapper" class="dataTables_wrapper form-inline no-footer">

            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="jq_message_log" aria-describedby="jq-datatables-example_info">
                <thead>
                    <tr role="row">
                        <th class="sorting_asc" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column ascending" style="width: 5%;">
                            #
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 12%;">
                            Created
                        </th>                    
                        <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 15%">
                            From
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 8%;">
                            Contacts
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 20;">
                            Subject
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 12%;">
                            Scheduled Time
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 8%;">
                            Charge (UGX)
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 5%;">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $count = 1;
                    foreach ($scheduled_item_logs as $sent_item):
                        ?>
                        <tr>
                            <td><?php echo $count++ ?></td>
                            <td><?php
                                echo (new Cake\I18n\Time($sent_item->created_at))->timeAgoInWords();
                                ?></td>
                            
                            <td><?php echo $sent_item->fullname ?></td>
                            
                            <td><?php echo number_format($sent_item->contacts) ?></td>
                            <td><?php echo anchor('admin_emails/email_outbox_list/'.($sent_item->uuid),$sent_item->email_title);?></td>
                            <td><?php echo (new Cake\I18n\Time($sent_item->schedule_datetime))->timeAgoInWords(); ?></td>
                            <td><?php echo number_format($sent_item->totalcharge) ?></td>
                            <td>
                                <?php echo anchor('admin_emails/delete_email/' . $sent_item->uuid, '<i class="fa fa-trash-o fa-lg"></i> ', array('onclick' => "return confirm('You are about to resend this message')"));
                                ?>
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
<?php $this->load->view('Layout/backend/footer', []); ?>