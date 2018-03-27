<?php $this->load->view('Layout/backend/header', []); 

$sentitems_year_cost_sms = $this->db->select('CONCAT(YEAR(date), "-", MONTH(date)) AS monthyear,Sum(charge) as charge')
                ->from('sentitems')
                ->where('status', 1)
                ->where('date between "' . $start_date_five_year . '" and "' . $end_date_year . '"')
                ->order_by('date', "ASC")
                ->group_by("MONTH(date), YEAR(date)")
                ->get()->result_array();

$sum_sms = array_sum(array_column($sentitems_year_cost_sms, 'charge'));


?>
<div class="panel panel-default">
    <div class="panel-heading">
    </div>
<?php $this->load->view('backend/admin/finance/menu');
    ?>

    <div class="row">
       <div class="col-md-12 padding-md"> 
        <!--===========FINANCE=============================  
        <!-- Javascript -->
        <script>

            init.push(function () {
                Morris.Area({
                        element: 'sms-finance-traffic',
                        data:eval(<?= json_encode($sentitems_year_cost_sms) ?>),
                        xkey: 'monthyear',
                        ykeys: ['charge'],
                        labels: ['USD'],
                        hideHover: 'auto',
                        lineColors: PixelAdmin.settings.consts.COLORS,
                        fillOpacity: 0.3,
                        behaveLikeLine: true,
                        lineWidth: 2,
                        pointSize: 4,
                        gridLineColor: '#cfcfcf',
                        resize: true
                });
            });

        </script>
        <!-- / Javascript -->

        <div class="panel">
            <div class="panel-heading">
                <i class="fa fa-bar-chart-o"></i><span class="panel-title"> <b>SMS FINANCE LAST 5 YEARS <?=  number_format($sum_sms)?></b></span>
            </div>
            <div class="panel-body">
                <div class="graph-container">
                    <div id="sms-finance-traffic" class="graph" ></div>
                </div>
            </div>
        </div>
        <!--SMS TRAFFIC -->
    </div>
</div>
    
</div>


<?php $this->load->view('Layout/backend/footer', []); ?>    