<?php
if(strlen($message) > 0)
{
    ?>

    <div class="alert <?php echo $alert;?>">
        <strong>Well done!</strong> <?php echo $message;?>
    </div>
<?php
}
?>

    [<a class="btn btn-sm " href="#newroute" data-toggle="modal"> <i class="fa fa-plus lg">  </i> New Package </a>]


    <table class="table table-striped" id="dataTable">
        <thead>
        <tr >
            <th>No</th>
            <th>Created</th>
            <th>Package </th>
            <th>From</th>
            <th>To</th>
            <th>Cost</th>

            <th>Action </th>
        </tr>
        </thead>
        <tbody>
        <?php
        $count = 1;
        $rst = $this->db->select('*')
            ->from('smscost')

            ->order_by('from_sms ASC')

            ->get()
            ->result();


        $count = 1;




        foreach($rst as $rows)
        {
            ?>
            <tr>
                <td><?php echo $count;?></td>
                <td><?php echo $rows->created;?></td>
                <td> <?php echo $rows->cost_name;?>     </td>

                <td><?php echo number_format($rows->from_sms);?></td>
                <td><?php echo number_format($rows->to_sms);?></td>
                <td><?php echo $rows->cost;?></td>

                <td >
                    <?php echo anchor('admin/system_settings/messaging_costs/delete/'.$rows->id*date('Y'),'<i class="fa fa-trash-o lg"></i> ',
                        array('onclick'=>"return confirm('You are about to delete this cost')"));?>

                    <a class="btn btn-sm " href="#newroute<?php echo $rows->id?>" data-toggle="modal"><i class="fa fa-edit  lg"></i> </a>
                </td>






            </tr>
            <?php
            $count++;
        }
        ?>
        </tbody>
    </table>


    <div class="modal fade" id="newroute">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h5>Add SMS cost</h5>
                </div>
                <?php echo form_open(); ?>
                <div class="modal-body">


                    <div class="form-group">
                        <label for="folderName">Package Name</label>
                        <input type="text" class="form-control input-sm" id="folderName" name="package_name" placeholder="Package Name" required="required">
                    </div>

                    <div class="form-group">
                        <label for="folderName">From SMS </label>
                        <input type="text"  class="form-control input-sm" id="folderName" name="from_sms" placeholder="From SMS " required="required">
                    </div>


                    <div class="form-group">
                        <label for="folderName">To SMS</label>
                        <input type="text" class="form-control input-sm" id="folderName" name="to_sms" placeholder="To SMS" required="required">
                    </div>

                    <div class="form-group">
                        <label for="folderName">Cost</label>
                        <input type="text" class="form-control input-sm" id="folderName" name="cost" placeholder="Rate" required="required">
                    </div>






                </div>
                <div class="modal-footer">

                    <button  class="btn btn-danger btn-sm">Save changes</button>
                </div>
                <?php echo form_close();?>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


<?php

foreach($rst as $rows)
{
    ?>

    <div class="modal fade" id="newroute<?php echo $rows->id?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h5>Edit SMS Package</h5>
                </div>
                <?php echo form_open(); ?>

                <input type="hidden" name="id" value="<?php echo  $rows->id?>">

                <div class="modal-body">


                    <div class="form-group">
                        <label for="folderName">Package Name</label>
                        <input type="text" class="form-control input-sm" id="folderName" name="package_name_edit" value="<?php echo $rows->cost_name?>" placeholder="Package Name" required="required">
                    </div>

                    <div class="form-group">
                        <label for="folderName">From SMS </label>
                        <input type="text"  class="form-control input-sm" id="folderName" name="from_sms_edit" value="<?php echo $rows->from_sms?>" placeholder="From SMS " required="required">
                    </div>


                    <div class="form-group">
                        <label for="folderName">To SMS</label>
                        <input type="text" class="form-control input-sm" id="folderName" name="to_sms_edit" value="<?php echo $rows->to_sms?>" placeholder="To SMS" required="required">
                    </div>

                    <div class="form-group">
                        <label for="folderName">Cost</label>
                        <input type="text" class="form-control input-sm" id="folderName" name="cost_edit" placeholder="Rate" value="<?php echo $rows->cost?>" required="required">
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