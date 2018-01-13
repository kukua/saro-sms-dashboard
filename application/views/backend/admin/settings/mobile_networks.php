<?php $this->load->view('Layout/backend/header', []); 

?>

<?php
$networks = $this->db
        ->select('networks.*,routes.name')
        ->from('networks, routes')
        ->where('networks.routeid = routes.id')
        ->order_by('networks.created DESC')
        ->group_by('networks.id')
        ->get()
        ->result();
?>
<div class="panel panel-default">
    <div class="panel-heading">
    </div>
<?php $this->load->view('backend/admin/settings/menu'); ?>
<!-- SENT MESSAGE LOGS !-->
<script>
    init.push(function () {
        $('#jq_networks_log').dataTable();
        $('#jq_networks_log_wrapper .table-caption').text('Phone Group');
        $('#jq_networks_log_wrapper .dataTables_filter input').attr('placeholder', 'Search...');
    });
</script>
<div class="table-light">
    <div role="grid" id="jq_networks_log_wrapper" class="dataTables_wrapper form-inline no-footer">

        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="jq_networks_log" aria-describedby="jq-datatables-example_info">
            <thead>
                <tr role="row">
                    <th class="sorting_asc" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column ascending" style="width: 2%;">
                        #
                    </th>
                               
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 20%">
                       Created
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 30%">
                       Network
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%">
                       Mobile Prefix
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%">
                      Cost
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%">
                      Route
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                        Action
                    </th>
            </thead>
            <tbody>
                <?php
                $count = 1;
                foreach ($networks as $network):
                    ?>
                    <tr>
                        <td><?php echo $count++ ?></td>                       
                     <td><?php echo (new Cake\I18n\Time($network->created))->timeAgoInWords();?></td>
                     <td><?php echo $network->network;?></td>
                    
                    <td><?php echo $network->prefix;?></td>
                    <td><?php echo number_format($network->cost);?></td>
                    <td><?php echo $network->name;?></td>

                    <td>
                        <a  href="#mnformModal<?php echo $network->id?>"  data-toggle="modal"> <i class="fa fa-edit fa-lg"></i></a>&nbsp;
                       <?php echo anchor('dmin/system_settings/mobile_networks/delete/'.$network->id*date('Y'),'<i class="fa fa-trash-o fa-lg"></i>',array('onclick'=>"return confirm('You are about to delete this user account')"));?>
                    </td>
                        
                    </tr>
                    <?php
                endforeach;
                ?>
                    
            </tbody>
        </table>

    </div>
</div>
<?php

foreach($networks as $rows)
{
    ?>

    <div class="modal fade" id="mnformModal<?php echo $rows->id?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h5>Edit Mobile Network</h5>
                </div>
                <?php echo form_open('admin_system_settings/mobile_networks_network_name_edit'); ?>

                <input type="hidden" name="id" value="<?php echo  $rows->id*date('Y')?>">

                <div class="modal-body">

                    <div class="form-group">
                        <label for="folderName">Network Name</label>
                        <input type="text" class="form-control input-sm" id="folderName" name="network_name_edit" placeholder="Network Name" value="<?php echo $rows->network ?>" required="required">
                    </div>

                    <div class="form-group">
                        <label for="folderName">Phone Prefix </label><small> Include Country code on prefix</small>
                        <input type="text"  class="form-control input-sm" id="folderName" name="prefix_edit" placeholder="Mobile Prefix e.g. 25674 " value="<?php echo $rows->prefix ?>" required="required">
                    </div>


                    <div class="form-group">
                        <label for="folderName">Default Cost</label>
                        <input type="text" class="form-control input-sm" id="folderName" name="cost_edit" placeholder="Default SMS Cost" value="<?php echo $rows->cost ?>" required="required">
                    </div>

                    <div class="form-group">
                        <label for="folderName">Route Name</label>
                        <select class="form-control input-sm" id="folderName" name="route_edit"  >
                            <?php

                            $routes = $this->db->select('*')
                                ->from('routes')
                                ->order_by('name')
                                ->get()->result();
                            foreach($routes as $rows_)
                            {
                                ?>
                                <option value="<?php echo $rows_->id?>" <?php echo $rows->routeid == $rows_->id?'selected="selected"':'' ?>><?php echo $rows_->name?></option>
                            <?php

                            }
                            ?>

                        </select>
                    </div>




                </div>
                <div class="modal-footer">

                    <button  class="btn btn-success btn-sm"><i class="fa fa-edit"></i> Save changes</button>
                </div>
                <?php echo form_close();?>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

<?php
}

?>
</div>
<?php $this->load->view('Layout/backend/footer', []); ?>