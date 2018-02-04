<?php $this->load->view('Layout/backend/header', []); ?>


<?php

$email_item_logs=[];

        if($this->session->userdata('dept')=="admin"):
       $email_item_logs = $this->db
                ->select('e.*,count(*) as counts,u.fullname, sum(charge) as charge')
                ->from('email_outbox e, users u')
                ->where('e.sent_by = u.id')
                ->where('e.created_at >= "' . $start_date . '" <= "' . $end_date . '"')
                ->limit(0,50)
                ->order_by('e.id DESC')
                ->group_by('e.uuid')                
                ->get()->result();
        else:
            $email_item_logs = $this->db
                ->select('e.*,count(*)  as counts,u.fullname, sum(charge) as charge')
                ->from('email_outbox e, users u')
                ->where('e.sent_by = u.id')
                ->where('e.sent_by ='.$this->session->userdata('id'))
                ->where('e.created_at >= "' . $start_date . '" <= "' . $end_date . '"')
                ->limit(0,50)
                ->order_by('e.id DESC')
                ->group_by('e.uuid')
                ->get()->result();
        endif;

?>
<div class="panel panel-default">
    <div class="panel-heading">

    </div>
<?php  $this->load->view('backend/admin/emails/menu');

        ?>

<!-- SENT MESSAGE LOGS !-->
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
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 15%">
                        Sender
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                        Receivers
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                        From
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 30%;">
                        Subject
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                        Charge (USD)
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
                        <td><?php echo $email_item->fullname;?></td>
                        <td><?php echo $email_item->counts; ?></td>
                        <td><?php echo $email_item->email_from; ?></td>
                        <td><?php echo anchor('admin_emails/email_outbox_list/'.($email_item->uuid),$email_item->email_title);?></td>
                        <td><?php echo number_format($email_item->charge) ?></td>
                        <td>
                           <?php echo $this->spectrum_func->delivery_status($email_item->email_status);?> &nbsp;
                            <?php echo anchor('admin_emails/resend_sent_email/'.$email_item->uuid,'<i class="fa fa-refresh fa-lg"></i> ',
                                   array('onclick'=>"return confirm('You are about to resend this message')"));?>&nbsp;
                           <?php echo anchor('admin_emails/download_sent_emails/'.$email_item->uuid,'<i class="fa fa-download fa-lg"></i>',array('class'=>''));?>
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




