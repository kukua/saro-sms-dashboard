<?php
$this->load->view('Layout/backend/header', []);

$scheduled_item_logs = $this->db->select('*,routes.name as routename,sentitems.status as delivery_status,sum(charge) as totalcharge')
                ->from('sentitems, routes, users')
                ->where('sentitems.routeid = routes.id and users.id = sentitems.sender and sentitems.status = 4')
                ->where('sentitems.date between "' . $start_date_year . '" and "' . $end_date_year . '"')
                ->limit(0, 5000)
                ->order_by('sentitems.id DESC')
                ->group_by('sentitems.message_id')
                ->get()->result();
?>
<div class="panel panel-default">
    <div class="panel-heading">
    </div>

    <?php $this->load->view('backend/admin/messages/menu'); ?>

    <!-- SENT MESSAGE LOGS !-->
    <script>
        init.push(function () {
            $('#jq_message_log').dataTable();
            $('#jq_message_log_wrapper .table-caption').text('This Years Governor Message');
            $('#jq_message_log_wrapper .dataTables_filter input').attr('placeholder', 'Search...');
        });
    </script>
    
   <?php

    echo anchor('#formModal','
					<span class="shortcut-icon">
						<i class="fa fa-plus"></i>
					</span>
						   <span class="text">Send to Queue </span>',array('class'=>'btn btn-primary','data-toggle'=>"modal"));
 

    ?>


    <div class="table-light">
        <div role="grid" id="jq_message_log_wrapper" class="dataTables_wrapper form-inline no-footer">

            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="jq_message_log" aria-describedby="jq-datatables-example_info">
                <thead>
                    <tr role="row">
                        <th class="sorting_asc" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column ascending" style="width: 2%;">
                            #
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 6%;">
                            Created
                        </th>                    
                        <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 45%">
                            Message
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 5%;">
                            Contacts
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 6%;">
                            Route
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 8%;">
                            Scheduled Time
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 8%;">
                            Sender
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 5%;">
                            Charge (USD)
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
                                echo (new Cake\I18n\Time($sent_item->datetime))->timeAgoInWords();
                                ?></td>
                            <td width="25%"><?php echo anchor('admin_messages/message_details/' . $sent_item->message_id, $sent_item->message); ?></td>
                            <td><?php echo number_format($sent_item->contacts) ?></td>
                            <td><?php echo $sent_item->routename ?></td>
                            <td><?php echo (new Cake\I18n\Time($sent_item->schedule_datetime))->timeAgoInWords(); ?></td>
                            <td><?php echo $sent_item->fullname ?></td>
                            <td><?php echo number_format($sent_item->totalcharge) ?></td>
                            <td>
                                <?php echo anchor('admin_messages/delete_message/' . $sent_item->message_id, '<i class="fa fa-trash-o fa-lg"></i> ', array('onclick' => "return confirm('You are about to resend this message')"));
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



  <div class="modal fade" id="formModal">
        
 <?php echo form_open('admin_messages/governor_to_queue')?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4>Send to Queue</h4>
                </div>
                <div class="modal-body">

                    <div class="panel panel-default">

                        <div class="panel-body">

                            <div class="form-group">
                                <label for="exampleInputEmail1">Number of SMS:</label>
                                <input type="text" class="form-control input-sm" id="number" placeholder="No Of SMS"  value="" name="number"  required="required">
                            </div><!-- /form-group -->

                        </div>
                    </div>
                    <div class="form-group text-right">
                            <button href="#" class="btn btn-success"><i class="fa fa-plus"></i> Create</button>

                        </div>

                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->

    <?php echo form_close();?>
    </div>




<?php $this->load->view('Layout/backend/footer', []); ?>