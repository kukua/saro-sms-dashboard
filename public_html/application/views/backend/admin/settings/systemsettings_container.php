<?php $this->load->view('Layout/backend/header', []); 

$routes = $this->db
                ->from('routes')
                ->order_by('id DESC')
                ->get()->result();

?>
<div class="panel panel-default">
    <div class="panel-heading">
        
    </div>
<?php  $this->load->view('backend/admin/settings/menu');?>

    <div class="row" style="margin-top: 20px;">
        <div class="col-md-4">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php
                    $resume = $this->db->select('*')->from('status')->where('id=1')->get()->result();
                    $resume = $resume[0];


                    echo anchor('admin_system_settings/change_message_delivery/1', ($resume->status == 1 ? '<center><i class="fa fa-pause lg"></i> Pause Mobile SMS Delivery</center>' : '<center><i class="fa fa-play lg"></i> Resume Mobile SMS Delivery</center>'), array('onclick' => "return confirm('Are you sure, you want to perform this?')"));
                    ?>
                </div>
            </div>
        </div>
    </div>

</div>
<?php $this->load->view('Layout/backend/footer', []); ?>