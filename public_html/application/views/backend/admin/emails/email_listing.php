<?php
$this->load->view('Layout/backend/header', []);

$email_list = $this->db->select('*')->from('email_list')->where(array('id' => $id))->get()->result();
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <?=$email_list[0]->list_name?>
    </div>
    <?php $this->load->view('backend/admin/emails/menu');
    ?>
    <?php
    ?>
    <script>
        init.push(function () {
            $('#jq_message_log').dataTable();
            $('#jq_message_log_wrapper .table-caption').text('Email List');
            $('#jq_message_log_wrapper .dataTables_filter input').attr('placeholder', 'Search...');
        });
    </script>
    <div class="panel panel-default table-responsive">

        <div class="table-light">
            <div role="grid" id="jq_message_log_wrapper" class="dataTables_wrapper form-inline no-footer">

                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="jq_message_log" aria-describedby="jq-datatables-example_info">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Created</th>
                            <th>Fullname</th>
                            <th>Email</th>


                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
<?php
$count = 1;
$rst = $this->db
        ->select('*')
        ->from('email_book')
        ->where('list_id  = "' . $id . '"')
        ->order_by('fullname ASC')
        ->get()
        ->result();



foreach ($rst as $rows) {
    ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><?php
                                echo (new Cake\I18n\Time($rows->created_at))->timeAgoInWords();
                                ?></td>
                                <td><?php
                        echo $rows->fullname
                        ?></td>

                                <td><?php echo $rows->email_address ?></td>

                                <td>

                        <center>
    <?php
    if (1) {
        ?>
                            <a href="#formModal<?php echo $rows->id ?>"  data-toggle="modal"><i class="fa fa-edit fa-lg"></i></a>
                                <?php
                            } else {
                                echo 'Access denied';
                            }
                            ?>
                            <?php
                            if (1) {
                                echo anchor('admin_emails/delete_email_address/' . $rows->id * date('Y') . '/' . $id * date('Y'), '<i class="fa fa-trash-o fa-lg"></i>', array('onclick' => "return confirm('You are about to delete this Email Address')"));
                            }
                            ?>
                        </center>
                        </td>


                        </tr>
    <?php
    $count++;
}
?>
                    </tbody>
                </table>
            </div><!-- /.padding-md -->
        </div>
    </div><!-- /panel -->
    <!---------->

<?php
foreach ($rst as $rows) {
    ?>

        <?php echo form_open('admin_emails/update_email_book/'.$id*date('Y'), array('enctype' => 'multipart/form-data')) ?>

        <div class="modal fade" id="formModal<?php echo $rows->id ?>">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4>Update</h4>
                    </div>
                    <div class="modal-body">


                        <input type="hidden" name="id" value="<?php echo $rows->id ?>">

                        <!---->

                        <div class="panel panel-default">

                            <div class="panel-body">


                                <div class="form-group">
                                    <label for="exampleInputEmail1">Fullname:</label>
                                    <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Fullname" value="<?php echo $rows->fullname ?>" name="fullname"  required="required">
                                </div><!-- /form-group -->


                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email Addresst:</label>
                                    <input type="email" class="form-control input-sm" id="exampleInputEmail1" placeholder="Email Address" value="<?php echo $rows->email_address ?>" name="email_address"  required="required">
                                </div><!-- /form-group -->






                            </div>
                        </div>





                        <div class="form-group text-right">
                            <button href="#" class="btn btn-success"><i class="fa fa-save"></i> Save</button>

                        </div>

                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->

        </div>
    <?php echo form_close(); ?>
    <?php
}
?>
</div>

    <?php $this->load->view('Layout/backend/footer', []); ?> 



