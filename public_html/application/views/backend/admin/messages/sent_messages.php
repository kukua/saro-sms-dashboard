<?php
$this->load->view('Layout/backend/header', []);

$sent_item_logs = $this->db->select('*,routes.name as routename,sentitems.status as delivery_status,sum(charge) as totalcharge')
                ->from('sentitems, routes, users')
                ->where('sentitems.routeid = routes.id and users.id = sentitems.sender and sentitems.status=1')
                ->where('sentitems.date >= "' . $start_date . '" <= "' . $end_date . '"')
                ->order_by('sentitems.id DESC')
                ->group_by('sentitems.message_id')
                ->get()->result();
?>
<div class="panel panel-default">
    <div class="panel-heading">
    </div>

    <?php $this->load->view('backend/admin/messages/menu'); ?>


    <!--------->
    <!-- SENT MESSAGE LOGS !-->
    <script>
        init.push(function () {
            $('#jq_message_log').dataTable();
            $('#jq_message_log_wrapper .table-caption').text('This Month Sent Message Logs');
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
                        <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 45%">
                            Message
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 5%;">
                            Contacts
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 8%;">
                            Route
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                            Sender
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 5%;">
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
                            <td><?php
                                echo (new Cake\I18n\Time($sent_item->datetime))->timeAgoInWords();
                                ?></td>
                            <td width="25%"><?php echo anchor('admin_messages/message_details/' . $sent_item->message_id, $sent_item->message); ?></td>
                            <td><?php echo number_format($sent_item->contacts) ?></td>
                            <td><?php echo $sent_item->routename ?></td>
                            <td><?php echo $sent_item->fullname ?></td>
                            <td><?php echo number_format($sent_item->totalcharge) ?></td>
                            <td>
                                <?php echo $this->spectrum_func->delivery_status($sent_item->delivery_status); ?> &nbsp;
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
<!---------->

<?php $this->load->view('Layout/backend/footer', []); ?>    
<?php
/* switch($sublink)
  {

  case 'send_bulk_sms':
  $this->load->view('admin/send-bulk-sms');
  break;

  case 'send_personalised_sms';
  $this->load->view('admin/messaging/send_personalised_sms');
  break;

  case 'personalize_sms';
  $this->load->view('admin/messaging/personalised_sms');
  break;



  case 'message_details':
  $this->load->view('admin/sent-message-details');
  break;
  } */
?>