<?php $this->load->view('Layout/backend/header', []); 

$payments = $this->db
                ->select('*,payments.created as datetime,payments.status as payment_status')
                ->from('payments, users')
                ->where('payments.sender = users.id ')
                ->where('payments.created between "' . $start_date_year . '" and "' . $end_date_year . '"')
                ->order_by('payments.id DESC')
                ->group_by('payments.id')
                ->get()->result();

?>
<div class="panel panel-default">
    <div class="panel-heading">
         <?php
        echo anchor('admin_reports/pdf_payments', '
					<span class="shortcut-icon">
						<i class="fa fa-paperclip" ></i>
					</span>
						   <span class="text"> PDF</span>', array('class' => '',"style"=>"color:#38b24a;"))
        ?>
    </div>
    <?php $this->load->view('backend/admin/reports/menu'); ?>

<!-- PAYMENT LOGS !-->
<script>
    init.push(function () {
        $('#jq_payment_log').dataTable();
        $('#jq_payment_log_wrapper .table-caption').text('This Years Payment Logs');
        $('#jq_payment_log_wrapper .dataTables_filter input').attr('placeholder', 'Search...');
    });
</script>
<div class="table-light">
    <div role="grid" id="jq_payment_log_wrapper" class="dataTables_wrapper form-inline no-footer">

        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="jq_payment_log" aria-describedby="jq-datatables-example_info">
            <thead>
                <tr role="row">
                    <th class="sorting_asc" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column ascending" style="width: 2%;">
                        #
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                        Created
                    </th>                    
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 30%">
                        Payer
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 20%;">
                        Amount
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 20%;">
                        Method
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                        Status
                    </th>
            </thead>
            <tbody>
                <?php
                $count = 1;
                foreach ($payments as $payment):
                    ?>
                    <tr>
                        <td><?php echo $count++ ?></td>

                        <td><?php
                            echo (new Cake\I18n\Time($payment->datetime))->timeAgoInWords();
                            ?></td>
                        <td><?php echo $payment->fullname ?></td>
                        <td><?php echo number_format($payment->messages) ?> UGX</td>

                        <td><?php echo $payment->method ?></td>
                        <td><?php echo $payment->payment_status ?></td>



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