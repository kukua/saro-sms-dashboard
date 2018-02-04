<?php $this->load->view('Layout/backend/header', []); ?>
<?php

$sent_this_month = $this->db->select('count(*) as count, date')
                ->from('sentitems')
                ->where('status', 1)
                ->where('date between "' . $start_date . '" and "' . $end_date . '"')
                ->where('sender',$this->session->userdata('id'))
                ->order_by('date', 'ASC')
                ->group_by('date')
                ->get()->result();

$sent_last_month = $this->db->select('count(*) as count')
                ->from('sentitems')
                ->where('sentitems.date between "' . $last_month_start_date . '" and "' . $last_month_end_date . '"')
                ->where('sender',$this->session->userdata('id'))
                ->get()->result();

$sent_today = $this->db->select('count(*) as count')
                ->from('sentitems')
                ->where('sender',$this->session->userdata('id'))
                ->where(array('date' => date('Y-m-d')))
                ->get()->result();

$this_month_charge = $this->db->select('sum(charge) as charge')
                ->from('sentitems')
                ->where('sender',$this->session->userdata('id'))
                ->where('date between "' . $start_date . '" and "' . $end_date . '"')
                ->get()->result();

$sentitems_year = $this->db->select('CONCAT(YEAR(date), "-", MONTH(date)) AS monthyear,count(*) as count')
                ->from('sentitems')
                ->where('status', 1)
                ->where('sender',$this->session->userdata('id'))
                ->where('date between "' . $start_date_year . '" and "' . $end_date_year . '"')
                ->order_by('date', "ASC")
                ->group_by("MONTH(date), YEAR(date)")
                ->get()->result();

$today_charge = $this->db->select('sum(charge) as charge')
                ->from('sentitems')
                ->where('sender',$this->session->userdata('id'))
                ->where(array('date' => date('Y-m-d')))
                ->get()->result();


$payments = $this->db->select('*,payments.created as datetime,payments.status as payment_status')
                ->from('payments, users')
                ->where('payments.sender = users.id ')
                ->where('payments.sender',$this->session->userdata('id'))
                ->where('payments.created between "' . $start_date_year . '" and "' . $end_date_year . '"')
                ->order_by('payments.created DESC')
                ->group_by('payments.id')
                ->get()->result();

$sent_item_logs = $this->db->select('*,sentitems.status as delivery_status,sum(charge) as totalcharge')
                ->from('sentitems, routes, users')
                ->where('sentitems.routeid = routes.id and users.id = sentitems.sender')
                ->where('sentitems.sender',$this->session->userdata('id'))
                ->where('sentitems.date between "' . $start_date . '" and "' . $end_date . '"')
                ->order_by('sentitems.datetime DESC')
                ->group_by('sentitems.message_id')
                ->get()->result();

$useraccount = $this->db->select('*')->from('users')->where(array('id' => $this->session->userdata('id')))->get()->result();
$useraccount = $useraccount[0];

?>
<div class="panel panel-default">
    <div class="panel-heading">
        
    </div>
<div class="grey-container shortcut-wrapper">
    <a href="#" class="">

    </a>

    <?php
    echo anchor('user_messages/messages', '<span class="shortcut-icon">
						<i class="fa fa-envelope"></i>
					</span>
        <span class="text">Sent Messages</span>', array('class' => 'shortcut-link'));
    ?>
    <?php
    $rst = $this->db->select('count(*) as counts')->from('sentitems')->where(array('status' => 0,'sender'=>$this->session->userdata('id')))->get()->result()
    ;
    ?>


    <?php
    echo anchor('user_messages/scheduled_messages', '<span class="shortcut-icon">
						<i class="fa fa-clock-o"></i>
						<span class="shortcut-alert">
							' . number_format($rst[0]->counts) . '
						</span>
					</span>
        <span class="text">SMS Queue</span>', array('class' => 'shortcut-link'));
    ?>
    <?php
    echo anchor('user_payments/index', '				<span class="shortcut-icon">
						<i class="fa fa-credit-card"></i>

					</span>
        <span class="text">Payments</span>', array('class' => 'shortcut-link'));
    ?>



</div>

<div class="row">
    <div class="col-md-8"> 

        <div class="row">
            <div class="col-xs-6">
                <!-- Centered text -->
                <div class="stat-panel text-center">
                    <div class="stat-row">
                        <!-- Dark gray background, small padding, extra small text, semibold text -->
                        <div class="stat-cell bg-pa-purple padding-sm text-xs text-semibold">
                            TODAYS EXPENSE
                        </div>
                    </div> <!-- /.stat-row -->
                    <div class="stat-row">
                        <!-- Danger background, vertically centered text -->
                        <div class="stat-cell bg-defualt valign-middle">
                            <!-- Stat panel bg icon -->
                            <!-- Extra large text -->
                            <span class="text-xlg">
                                <strong>
                                    <?= !empty($today_charge) ? number_format($today_charge[0]->charge) : 0 ?>
                                </strong>
                                <span class="text-lg text-slim"> USD</span></span><br>
                            <!-- Big text -->
                            <span class="text-bg">amount of money spent</span><br>
                        </div> <!-- /.stat-cell -->

                    </div> <!-- /.stat-row -->
                </div> <!-- /.stat-panel -->
            </div>
            <div class="col-xs-6">

                <div class="stat-panel text-center">
                    <div class="stat-row">
                        <!-- Dark gray background, small padding, extra small text, semibold text -->
                        <div class="stat-cell bg-pa-purple padding-sm text-xs text-semibold">
                            THIS MONTHS EXPENSE
                        </div>
                    </div> <!-- /.stat-row -->
                    <div class="stat-row">
                        <!-- Danger background, vertically centered text -->
                        <div class="stat-cell bg-defualt valign-middle">
                            <!-- Stat panel bg icon -->
                            <!-- Extra large text -->
                            <span class="text-xlg">
                                <strong>
                                    <?= !empty($this_month_charge) ? number_format($this_month_charge[0]->charge) : 0 ?>
                                </strong>
                                <span class="text-lg text-slim"> USD</span></span><br>
                            <!-- Big text -->
                            <span class="text-bg">amount of money spent</span><br>
                        </div> <!-- /.stat-cell -->

                    </div> <!-- /.stat-row -->
                </div> <!-- /.stat-panel -->
            </div>
        </div>

        <!--===========SMS TRAFIC=============================
  
        <!-- Javascript -->
        <script>

            var data = eval(<?= json_encode($sent_this_month) ?>);
            init.push(function () {
                Morris.Bar({
                    element: 'sms-traffic',
                    data: data,
                    xkey: 'date',
                    ykeys: ['count'],
                    labels: ['messages'],
                    barRatio: 0.4,
                    xLabelAngle: 55,
                    hideHover: 'auto',
                    barColors: PixelAdmin.settings.consts.COLORS,
                    gridLineColor: '#cfcfcf',
                    resize: true
                });
            });
        </script>
        <!-- / Javascript -->

        <div class="panel">
            <div class="panel-heading">
                <i class="fa fa-bar-chart-o"></i><span class="panel-title"> <b>DAILY SMS TRAFFIC</b></span>
            </div>
            <div class="panel-body">
                <div class="graph-container">
                    <div id="sms-traffic" class="graph"></div>
                </div>
            </div>
        </div>
        <!--SMS TRAFFIC -->
    </div>
    <!-- /6. $EASY_PIE_CHARTS -->
    <div class="col-md-4">  
        <div class="col-sm-4 col-md-12">
              <div class="stat-panel">
                    <!-- Success background. vertically centered text -->
                    <div class="stat-cell bg-<?= $this->spectrum_func->credit_color($useraccount->credits); ?> valign-middle">
                        <!-- Stat panel bg icon -->
                        <!-- Extra large text -->
                        <span class="text-xlg"><strong><?php echo number_format($useraccount->credits) ?></strong></span><br>
                        <!-- Big text -->
                        <span class="text-bg">SMS Balance (USD)</span><br>
                    </div> <!-- /.stat-cell -->
                </div>

                <div class="stat-panel">
                    <!-- Success background. vertically centered text -->
                    <div class="stat-cell bg-<?= $this->spectrum_func->credit_color($useraccount->email_credits); ?> valign-middle">
                        <!-- Stat panel bg icon -->
                        <!-- Extra large text -->
                        <span class="text-xlg"><strong><?php echo number_format($useraccount->email_credits) ?></strong></span><br>
                        <!-- Big text -->
                        <span class="text-bg">Email Balance (USD)</span><br>
                    </div> <!-- /.stat-cell -->
                </div>
        </div>
            <!-- SMS YEAR SENT-->
            <div class="col-sm-4 col-md-12">
                <!-- Javascript -->
                <script>
                    var data_year = eval(<?= json_encode($sentitems_year, JSON_NUMERIC_CHECK) ?>);

                    init.push(function () {
                        Morris.Line({
                            element: 'stats-sparklines-3',
                            data: data_year,
                            xkey: 'monthyear',
                            ykeys: ['count'],
                            labels: ['SMS'],
                            smooth: false
                        });
                    });
                </script>
                <!-- / Javascript -->

                <div class="stat-panel">
                    <div class="stat-row">
                        <!-- Purple background, small padding -->
                        <div class="stat-cell  padding-sm" style="background-color: rgba(147,0,255,0.1)">
                            <!-- Extra small text -->
                            <div class="text-xs" style="margin-bottom: 5px;">SMS ALL TIME YEAR GRAPH</div>
                            <div class="stats-sparklines" id="stats-sparklines-3" style="width: 100%"></div>
                        </div>

                    </div> <!-- /.stat-row -->
                    <div class="stat-row">
                        <!-- Warning background -->
                        <div class="stat-cell bg-pa-purple">
                            <!-- Big text -->
                            <span class="text-bg">
                                <?php
                                if (!empty($sent_last_month) && !empty($sent_this_month)) {
                                    echo substr(((($sent_this_month[0]->count - $sent_last_month[0]->count) / $sent_this_month[0]->count) * 100), 0, 5);
                                } else {
                                    echo 0;
                                }
                                ?>
                            </span> % more<br>
                            <!-- Small text -->
                            <span class="text-sm">SMS this month</span>
                        </div>
                    </div> <!-- /.stat-row -->
                    <div class="stat-row">
                        <!-- Bordered, without top border, horizontally centered text -->
                        <div class="stat-counters bordered no-border-t text-center">
                            <!-- Small padding, without horizontal padding -->
                            <div class="stat-cell col-xs-4 padding-sm no-padding-hr">
                                <!-- Big text -->
                                <span class="text-bg"><strong><?= !empty($sent_today) ? $sent_today[0]->count : '0' ?></strong></span><br>
                                <!-- Extra small text -->
                                <span class="text-xs text-muted">TODAY</span>
                            </div>

                            <!-- Small padding, without horizontal padding -->
                            <div class="stat-cell col-xs-4 padding-sm no-padding-hr">
                                <!-- Big text -->
                                <span class="text-bg"><strong><?= !empty($sent_this_month) ? $sent_this_month[0]->count : '0' ?></strong></span><br>
                                <!-- Extra small text -->
                                <span class="text-xs text-muted">THIS MONTH</span>
                            </div>
                            <!-- Small padding, without horizontal padding -->
                            <div class="stat-cell col-xs-4 padding-sm no-padding-hr">
                                <!-- Big text -->
                                <span class="text-bg"><strong><?= !empty($sent_last_month) ? $sent_last_month[0]->count : '0' ?></strong></span><br>
                                <!-- Extra small text -->
                                <span class="text-xs text-muted">LAST MONTH</span>
                            </div>
                        </div> <!-- /.stat-counters -->
                    </div> <!-- /.stat-row -->

                </div> <!-- /.stat-panel -->
            </div>
            <!-- /8. $RETWEETS_GRAPH_STAT_PANEL -->
    </div>
</div>
<!-- /9. $UNIQUE_VISITORS_STAT_PANEL -->
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
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                        Created
                    </th>                    
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 40%">
                        Message
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 5%;">
                        Contacts
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 20%;">
                        Sender
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                        Total Charge (USD)
                    </th>
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
                        <td width="25%"><?php echo anchor('user_messages/message_details/' . $sent_item->message_id, $sent_item->message); ?></td>
                        <td><?php echo number_format($sent_item->contacts) ?></td>
                        <td><?php echo $sent_item->fullname ?></td>
                        <td><?php echo number_format($sent_item->totalcharge) ?></td>


                    </tr>
                    <?php
                endforeach;
                ?>

            </tbody>
        </table>

    </div>
</div>
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
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 20%;">
                        Created
                    </th>                    
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 30%">
                        Payer
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 15%;">
                        Amount
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 15%;">
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
                        <td><?php echo number_format($payment->messages) ?> USD</td>

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