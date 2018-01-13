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
<!-- SENT MESSAGE LOGS !-->
<script>
    init.push(function () {
        $('#jq_routes_log').dataTable();
        $('#jq_routes_log_wrapper .table-caption').text('Message Routes');
        $('#jq_routes_log_wrapper .dataTables_filter input').attr('placeholder', 'Search...');
    });
</script>
<div class="table-light">
    <div role="grid" id="jq_routes_log_wrapper" class="dataTables_wrapper form-inline no-footer">

        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="jq_routes_log" aria-describedby="jq-datatables-example_info">
            <thead>
                <tr role="row">
                    <th class="sorting_asc" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column ascending" style="width: 2%;">
                        #
                    </th>
                               
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 20%">
                       Route Name
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 30%">
                       Parameters
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%">
                       Method
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%">
                      Batch Limit
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 20%;">
                        Action
                    </th>
            </thead>
            <tbody>
                <?php
                $count = 1;
                foreach ($routes as $route):
                    ?>
                    <tr>
                        <td><?php echo $count++ ?></td>                       
                     <td><?php echo $route->name;?></td>
                    <td><?php
                        echo anchor('admin_system_settings/route_parameters/'.$route->id*date('Y'),'[<i class="fa  lg"> Parameters </i>]') ;

                        ?>        
                    </td>

                    <td><?php echo $route->method;?></td>
                    <td><?php echo number_format($route->batch_limit);?></td>

                    <td>
                        <a  href="#newroute<?php echo $route->id?>"  data-toggle="modal"> <i class="fa fa-edit fa-lg"></i></a>&nbsp;
                       <?php echo anchor('admin_system_settings/delete_route/'.$route->id*date('Y'),'<i class="fa fa-trash-o fa-lg"></i>',array('onclick'=>"return confirm('You are about to delete this user account')"));?>
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

foreach($routes as $rows)
{
?>

<div class="modal fade" id="newroute<?php echo $rows->id?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5>Edit SMS Route</h5>
            </div>
            <?php echo form_open('admin_system_settings/route_edit'); ?>


            <div class="modal-body">

                <input type="hidden" id="id" name="id" value="<?php echo $rows->id *date('Y'); ?>">

                <div class="form-group">
                    <label for="folderName">Route Name</label>
                    <input type="text" class="form-control input-sm" id="folderName" name="route_name_edit" placeholder="Route Name" value="<?php echo $rows->name?>" required="required">
                </div>

                <div class="form-group">
                    <label for="folderName">URL</label>
                    <input type="url" class="form-control input-sm" id="folderName" name="url_edit" placeholder="URL" required="required"  value="<?php echo $rows->url?>">
                </div>


                <div class="form-group">
                    <label for="folderName">Batch Limit</label>
                    <input type="text" class="form-control input-sm" id="folderName" name="batch_limit_edit" placeholder="Batch Limit" required="required"  value="<?php echo $rows->batch_limit?>">
                </div>

                <div class="form-group">
                    <label for="folderName">Method</label>
                    <select class="form-control input-sm" id="folderName" name="method_edit" >
                        <option value="GET" <?php  echo ($rows->name =="GET"?'selected="selected"':'')?>>GET</option>
                        <option value="POST" <?php  echo ($rows->name =="POST"?'selected="selected"':'')?>>POST</option>
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