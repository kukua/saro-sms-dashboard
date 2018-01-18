<?php $this->load->view('Layout/backend/header', []); ?>
<?php

$sent_this_month = $this->db->select('count(*) as count, date')
                ->from('sentitems')
                ->where('status', 1)
                ->where('date between "' . $start_date . '" and "' . $end_date . '"')
                ->get()->result();

$sent_this_month_log = $this->db->select('count(*) as count, date')
                ->from('sentitems')
                ->where('status', 1)
                ->where('date between "' . $start_date . '" and "' . $end_date . '"')
                ->order_by('date', 'ASC')
                ->group_by('date')
                ->get()->result();

$sent_last_month = $this->db->select('count(*) as count')
                ->from('sentitems')
                ->where('sentitems.date between "' . $last_month_start_date . '" and "' . $last_month_end_date . '"')
                ->get()->result();

$sent_today = $this->db->select('count(*) as count')
                ->from('sentitems')
                ->where(array('date' => date('Y-m-d')))
                ->get()->result();

$this_month_charge = $this->db->select('sum(charge) as charge')
                ->from('sentitems')
                ->where('date between "' . $start_date . '" and "' . $end_date . '"')
                ->get()->result();

$this_year_charge = $this->db->select('sum(charge) as charge')
                ->from('sentitems')
                ->where('date between "' . $start_date_year . '" and "' . $end_date_year . '"')
                ->get()->result();

$sentitems_year = $this->db->select('CONCAT(YEAR(date), "-", MONTH(date)) AS monthyear,count(*) as count')
                ->from('sentitems')
                ->where('status', 1)
                ->where('date between "' . $start_date_year . '" and "' . $end_date_year . '"')
                ->order_by('date', "ASC")
                ->group_by("MONTH(date), YEAR(date)")
                ->get()->result();

$today_charge = $this->db->select('sum(charge) as charge')
                ->from('sentitems')
                ->where(array('date' => date('Y-m-d')))
                ->get()->result();



$sent_item_logs = $this->db->select('*,sentitems.status as delivery_status,sum(charge) as totalcharge')
                ->from('sentitems, routes, users')
                ->where('sentitems.routeid = routes.id and users.id = sentitems.sender')
                ->where('sentitems.date between "' . $start_date . '" and "' . $end_date . '"')
                ->order_by('sentitems.datetime DESC')
                ->group_by('sentitems.message_id')
                ->get()->result();
?>
<div class="panel panel-default">
    <div class="panel-heading">
        
    </div>
<div class="grey-container shortcut-wrapper">
    <a href="#" class="">

    </a>

    <?php
    echo anchor('admin_messages/messages', '<span class="shortcut-icon">
						<i class="fa fa-envelope"></i>
					</span>
        <span class="text">Sent Messages</span>', array('class' => 'shortcut-link'));
    ?>
    <?php
    $rst = $this->db->select('count(*) as counts')->from('sentitems')->where(array('status' => 0))->get()->result()
    ;
    ?>


    <?php
    echo anchor('admin_messages/scheduled_messages', '<span class="shortcut-icon">
						<i class="fa fa-clock-o"></i>
						<span class="shortcut-alert">
							' . number_format($rst[0]->counts) . '
						</span>
					</span>
        <span class="text">SMS Queue</span>', array('class' => 'shortcut-link'));
    ?>


    <?php
    echo anchor('admin_system_settings/index', '<span class="shortcut-icon">
						<i class="fa fa-cogs"></i></span>
        <span class="text">System Settings</span>', array('class' => 'shortcut-link'));
    ?>

</div>

<div class="row">
    <div class="col-md-8"> 

        <div class="row">
            <div class="col-xs-4">
                <!-- Centered text -->
                <div class="stat-panel text-center">
                    <div class="stat-row">
                        <!-- Dark gray background, small padding, extra small text, semibold text -->
                        <div class="stat-cell bg-pa-purple padding-sm text-xs text-semibold">
                            TODAYS SALES
                        </div>
                    </div> <!-- /.stat-row -->
                    <div class="stat-row">
                        <!-- Danger background, vertically centered text -->
                        <div class="stat-cell bg-defualt valign-middle">
                            <!-- Extra large text -->
                            <span class="text-xlg">
                                <strong>
                                    <?= !empty($today_charge) ? number_format($today_charge[0]->charge) : 0 ?>
                                </strong>
                                <span class="text-lg text-slim"> UGX</span></span><br>
                            <!-- Big text -->
                        </div> <!-- /.stat-cell -->

                    </div> <!-- /.stat-row -->
                </div> <!-- /.stat-panel -->
            </div>
            <div class="col-xs-4">

                <div class="stat-panel text-center">
                    <div class="stat-row">
                        <!-- Dark gray background, small padding, extra small text, semibold text -->
                        <div class="stat-cell bg-pa-purple padding-sm text-xs text-semibold">
                            THIS MONTHS SALES
                        </div>
                    </div> <!-- /.stat-row -->
                    <div class="stat-row">
                        <!-- Danger background, vertically centered text -->
                        <div class="stat-cell bg-defualt valign-middle">
                            <!-- Extra large text -->
                            <span class="text-xlg">
                                <strong>
                                    <?= !empty($this_month_charge) ? number_format($this_month_charge[0]->charge) : 0 ?>
                                </strong>
                                <span class="text-lg text-slim"> UGX</span></span><br>
                            <!-- Big text -->
                        </div> <!-- /.stat-cell -->

                    </div> <!-- /.stat-row -->
                </div> <!-- /.stat-panel -->
            </div>
            <div class="col-xs-4">

                <div class="stat-panel text-center">
                    <div class="stat-row">
                        <!-- Dark gray background, small padding, extra small text, semibold text -->
                        <div class="stat-cell bg-pa-purple padding-sm text-xs text-semibold">
                            THIS YEARS SALES
                        </div>
                    </div> <!-- /.stat-row -->
                    <div class="stat-row">
                        <!-- Danger background, vertically centered text -->
                        <div class="stat-cell bg-defualt valign-middle">
                            <!-- Extra large text -->
                            <span class="text-xlg">
                                <strong>
                                    <?= !empty($this_year_charge) ? number_format($this_year_charge[0]->charge) : 0 ?>
                                </strong>
                                <span class="text-lg text-slim"> UGX</span></span><br>
                            <!-- Big text -->
                        </div> <!-- /.stat-cell -->

                    </div> <!-- /.stat-row -->
                </div> <!-- /.stat-panel -->
            </div>
        </div>

        <!--===========SMS TRAFIC=============================
  
        <!-- Javascript -->
        <script>

            var data = eval(<?= json_encode($sent_this_month_log) ?>);
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


                <div class="stat-panel text-center">
                    <div class="stat-row">
                        <!-- Dark gray background, small padding, extra small text, semibold text -->
                        <div class="stat-cell bg-pa-purple padding-sm text-xs text-semibold">
                            SMS ACCOUNTS
                        </div>
                    </div> <!-- /.stat-row -->
                    <div class="stat-row">
                        <div class="stat-cell bg-defualt valign-middle">
                            <!-- Stat panel bg icon -->
                            <i class="fa fa-users bg-icon"></i>
                            <!-- Extra large text -->
                            <span class="text-xlg">
                                <strong>
                                    <?= $this->db->select('*')->from('users')->where('dept != "admin"')->get()->num_rows(); ?>
                                </strong><span class="text-lg text-slim"> Users</span></span><br>
                            <!-- Big text -->
                            <span class="text-bg">active</span><br>
                        </div> <!-- /.stat-cell -->
                    </div> <!-- /.stat-row -->
                </div> <!-- /.stat-panel -->
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
                                if (!empty($sent_last_month) && !empty($sent_this_month) && $sent_this_month[0]->count !=0) {
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
                        Total Charge (UGX)
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
                        <td width="25%"><?php echo anchor('admin_messages/message_details/' . $sent_item->message_id, $sent_item->message); ?></td>
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
</div>
<?php $this->load->view('Layout/backend/footer', []); ?>        