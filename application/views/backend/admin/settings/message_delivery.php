<?php $this->load->view('Layout/backend/header', []);
?>

<div class="panel panel-default">
    <div class="panel-heading">
    </div>
<?php $this->load->view('backend/admin/settings/menu'); ?>
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-4">
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php
                    $resume = $this->db->select('*')->from('status')->where('id=1')->get()->result();
                    $resume = $resume[0];


                    echo anchor('admin_system_settings/change_message_delivery/1', ($resume->status == 1 ? '<center><i class="fa fa-pause lg"></i> Pause Calton Mobile SMS Delivery</center>' : '<center><i class="fa fa-play lg"></i> Resume Calton Mobile SMS Delivery</center>'), array('onclick' => "return confirm('Are you sure, you want to perform this?')"));
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php
                    $resume = $this->db->select('*')->from('status')->where('id=5')->get()->result();
                    $resume = $resume[0];


                    echo anchor('admin_system_settings/change_message_delivery/5', ($resume->status == 1 ? '<center><i class="fa fa-pause lg"></i> Pause SMPP Delivery</center>' : '<center><i class="fa fa-play lg"></i> Resume SMPP SMS Delivery</center>'), array('onclick' => "return confirm('Are you sure, you want to perform this?')"));
                    ?>
                </div>
            </div>
        </div>
          <div class="col-md-4">
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php
                    $resume = $this->db->select('*')->from('status')->where('id=3')->get()->result();
                    $resume = $resume[0];


                    echo anchor('admin_system_settings/change_message_delivery/3', ($resume->status == 1 ? '<center><i class="fa fa-pause lg"></i> Pause Beepsend SMS Delivery</center>' : '<center><i class="fa fa-play lg"></i> Resume Beepsend SMS Delivery</center>'), array('onclick' => "return confirm('Are you sure, you want to perform this?')"));
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php
                    $resume = $this->db->select('*')->from('status')->where('id=4')->get()->result();
                    $resume = $resume[0];


                    echo anchor('admin_system_settings/change_message_delivery/4', ($resume->status == 1 ? '<center><i class="fa fa-pause lg"></i> Pause Beepsend REST SMS Delivery</center>' : '<center><i class="fa fa-play lg"></i> Resume Beepsend REST SMS Delivery</center>'), array('onclick' => "return confirm('Are you sure, you want to perform this?')"));
                    ?>
                </div>
            </div>
        </div>
         <div class="col-md-4">
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php
                    $resume = $this->db->select('*')->from('status')->where('id=6')->get()->result();
                    $resume = $resume[0];


                    echo anchor('admin_system_settings/change_message_delivery/6', ($resume->status == 1 ? '<center><i class="fa fa-pause lg"></i> Pause SMPP V2 SMS Delivery</center>' : '<center><i class="fa fa-play lg"></i> Resume SMPP V2 Delivery</center>'), array('onclick' => "return confirm('Are you sure, you want to perform this?')"));
                    ?>
                </div>
            </div>
        </div>
         <div class="col-md-4">
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php
                    $resume = $this->db->select('*')->from('status')->where('id=8')->get()->result();
                    $resume = $resume[0];


                    echo anchor('admin_system_settings/change_message_delivery/8', ($resume->status == 1 ? '<center><i class="fa fa-pause lg"></i> Pause Route SMS Delivery</center>' : '<center><i class="fa fa-play lg"></i> Resume Route Delivery</center>'), array('onclick' => "return confirm('Are you sure, you want to perform this?')"));
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php
                    $resume = $this->db->select('*')->from('status')->where('id=2')->get()->result();
                    $resume = $resume[0];


                    echo anchor('admin_system_settings/change_email_delivery', ($resume->status == 1 ? '<center><i class="fa fa-pause lg"></i> Pause Email Delivery</center>' : '<center><i class="fa fa-play lg"></i> Resume Email Delivery</center>'), array('onclick' => "return confirm('Are you sure, you want to perform this?')"));
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php
                    $resume = $this->db->select('*')->from('status')->where('id=7')->get()->result();
                    $resume = $resume[0];


                    echo anchor('admin_system_settings/change_message_delivery/7', ($resume->status == 1 ? '<center><i class="fa fa-pause lg"></i> Pause Birthdays Message Delivery</center>' : '<center><i class="fa fa-play lg"></i> Resume Birthdays Delivery</center>'), array('onclick' => "return confirm('Are you sure, you want to perform this?')"));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('Layout/backend/footer', []); ?>