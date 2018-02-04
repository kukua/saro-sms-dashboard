<?php $this->load->view('Layout/backend/header', []); 
$sent_email_logs=[];

if($this->session->userdata('dept')=="admin"):
$sent_email_logs = $this->db
                ->select('e.*,u.fullname, sum(charge) as charge')
                ->from('email_outbox e, users u')
                ->where('e.sent_by = u.id and e.email_status in (2)')        
                ->where('e.created_at >= "' . $start_date . '" <= "' . $end_date . '"')
                ->order_by('e.created_at DESC')
                ->group_by('e.id')
                ->limit(100,0)
                ->get()
                ->result();
else:
    $sent_email_logs = $this->db
                ->select('e.*,u.fullname, sum(charge) as charge')
                ->from('email_outbox e, users u')
                ->where('e.sent_by = u.id and e.email_status in (2)')        
                ->where('e.created_at >= "' . $start_date . '" <= "' . $end_date . '"')
                ->where('u.id='.$this->session->userdata('id'))
                ->order_by('e.created_at DESC')
                ->group_by('e.id')
                ->limit(100,0)
                ->get()
                ->result();
endif;

?>

<div class="panel panel-default">
    <div class="panel-heading">

    </div>

<?php  $this->load->view('backend/admin/emails/menu');
?>
    <script>
    init.push(function () {
        $('#jq_email_log').dataTable();
        $('#jq_email_log_wrapper .table-caption').text('This Month Message Logs');
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
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 8%;">
                        Created
                    </th>                    
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%">
                        Sender
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                        From
                    </th>
                     <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                        To
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 15%;">
                        Subject
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                        Charge (USD)
                    </th>
                     <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 20%;">
                        Error Message
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 1;
                foreach ($sent_email_logs as $sent_email):
                    ?>
                    <tr>
                        <td><?php echo $count++ ?></td>
                        <td><?php
                            echo (new Cake\I18n\Time($sent_email->created_at))->timeAgoInWords();
                            ?></td>
                        <td><?php echo $sent_email->fullname; ?></td>
                        <td><?php echo $sent_email->email_from; ?></td>
                         <td><?php echo $sent_email->email_recipients; ?></td>
                        <td><?php echo anchor('admin_emails/email_detail/' . $sent_email->id, $sent_email->email_title); ?></td>
                        <td><?php echo number_format($sent_email->charge) ?></td>
                         <td><?php echo $sent_email->error_message ?></td>
                        <td>
                           <?php echo $this->spectrum_func->delivery_status($sent_email->email_status);?> &nbsp;
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
<?php
$this->load->view('Layout/backend/footer', []); ?> 






