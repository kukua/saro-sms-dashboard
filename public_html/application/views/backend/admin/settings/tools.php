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
                    
                     $rst = $this->db->select('count(*) as counts')->from('users')->where('mobile like "+%"')->get()->result();


                    echo anchor('admin_system_settings/clean_plus', '<center>['.number_format($rst[0]->counts).'] Remove + </center>', array('onclick' => "return confirm('Are you sure, you want to perform this?')"));
                    ?>
                </div>
            </div>
        </div>
         <div class="col-md-4">
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php
                    
                     $rst = $this->db->select('count(*) as counts')->from('users')->where('sms_cost =""')->get()->result();


                    echo anchor('admin_system_settings/set_profile', '<center>['.number_format($rst[0]->counts).'] Set Per SMS USD 0.07 </center>', array('onclick' => "return confirm('Are you sure, you want to perform this?')"));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('Layout/backend/footer', []); ?>