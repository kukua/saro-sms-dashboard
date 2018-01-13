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


                    echo anchor('admin_system_settings/set_profile', '<center>['.number_format($rst[0]->counts).'] Set Per SMS UGX 25 </center>', array('onclick' => "return confirm('Are you sure, you want to perform this?')"));
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php
                     echo anchor('#changeDefaultRoute','<center>Change Defualt Route </center>',array('onclick' => "return confirm('Are you sure, you want to perform this?')",'data-toggle'=>"modal"));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="changeDefaultRoute">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h5>Change Default Route</h5>
                </div>
                <?php echo form_open('admin_system_settings/change_default_route'); ?>
                <div class="modal-body">                    

                    <div class="form-group">
                        <label for="folderName">Route Name</label>
                        <select class="form-control input-sm" id="route" name="route" >
                            <?php

                                $routes = $this->db->select('*')
                                    ->from('routes')
                                    ->order_by('name')
                                    ->get()->result();
                                foreach($routes as $rows)
                                {
                                ?>
                                    <option value="<?php echo $rows->id?>"><?php echo $rows->name?></option>
                                    <?php

                                }
                                    
                              ?>

                        </select>
                    </div>

                </div>
                <div class="modal-footer">

                    <button  class="btn btn-danger btn-sm">Save changes</button>
                </div>
                <?php echo form_close();?>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<?php $this->load->view('Layout/backend/footer', []); ?>