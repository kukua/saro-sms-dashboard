<?php $this->load->view('Layout/backend/header', []); ?>
<?php

$details = $this->db->select('*')->from('routes')->where(array('id'=>$id))->get()->result();


?>
<div class="panel panel-default">
    <div class="panel-heading">
    </div>
<?php $this->load->view('backend/admin/settings/menu'); ?>
<script>
    init.push(function () {
        $('#jq_routes_log').dataTable();
        $('#jq_routes_log_wrapper .table-caption').text('Set API parameters');
        $('#jq_routes_log_wrapper .dataTables_filter input').attr('placeholder', 'Search...');
    });
</script>
<div class="table-light">
    <div role="grid" id="jq_routes_log_wrapper" class="dataTables_wrapper form-inline no-footer">
        <div class="panel panel-default">
            <div class="panel-heading"><strong><?php echo $details[0]->name?></strong></div>

            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="jq_routes_log" aria-describedby="jq-datatables-example_info">
                <thead>
                <tr >
                    <th>No</th>
                    <th>Created</th>
                    <th> Name</th>
                    <th>API Parameter</th>

                    <th>API Value</th>




                    <th>Action </th>
                </tr>
                </thead>
                <tbody>
                <?php
                $count = 1;
                $rst = $this->db->select('*')
                    ->from('route_parameters')
                ->where(array('routeid'=>$id))

                    ->order_by('id DESC')


                    ->get()->result();

                // $start = $count = $start == 0?1:$start+1;
                $count = 1;




                foreach($rst as $rows)
                {
                    ?>
                    <tr>
                        <td><?php echo $count;?></td>
                        <td><?php echo (new Cake\I18n\Time($rows->created))->timeAgoInWords();?></td>


                        <td><?php echo $rows->parameter_name;?></td>

                        <td><?php echo $rows->parameter;?></td>
                        <td><?php echo $rows->value;?></td>


                        <td >
                            <?php echo anchor('admin_system_settings/delete_route_parameter/'.$rows->id*date('Y').'/'.$id*date('Y'),'<i class="fa fa-trash-o lg"></i> ',
                                array('onclick'=>"return confirm('You are about to delete this parameter')"));?>

                        </td>






                    </tr>
                    <?php
                    $count++;
                }
                ?>
                </tbody>
                </table>
        </div><!-- /panel -->
    </div><!-- /.col -->
 </div>

<div class="panel panel-default">

                <div class="panel-body">
                    <?php echo form_open('admin_system_settings/create_route_parameters');?>

                    <input type="hidden" name="routeid" value="<?php echo $id * date('Y'); ?>">

                        <div class="form-group">
                            <label for="exampleInputEmail1">Parameters</label>
                            <select class="form-control input-sm" name="parameter_name" id="exampleInputEmail1">
                                <option value="username">Username</option>
                                <option value="password">Password</option>
                                <option value="senderid">Sender ID</option>
                                <option value="message">Message</option>
                                <option value="destination">Destination (Use number separator for value e.g , )</option>
                                <option value="others">Others</option>

                            </select>

                        </div>

                        <div class="form-group">

                            <input type="text" class="form-control input-sm" name="name" id="exampleInputEmail1" placeholder="Name ">

                        </div>

                        <div class="form-group">

                            <input type="text" class="form-control input-sm" name="value" id="exampleInputEmail1" placeholder="Value">

                        </div>


                        <button type="submit" class="btn btn-success btn-sm">Submit</button>
                <?php echo form_close();?>
                </div>
                    </div>

</div>
<?php $this->load->view('Layout/backend/footer', []); ?>