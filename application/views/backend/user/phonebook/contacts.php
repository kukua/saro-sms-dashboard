<?php
$this->load->view('Layout/backend/header', []);

$group_list = $this->db->select('*')->from('groups')->where(array('id' => $id))->get()->result();

 $rst = $this->db
                                ->select('*')
                                ->from('contacts')
                                ->where('group_id = ' . $id)
                                ->order_by('created_at', 'DESC')
                                ->get()
                                ->result();



 
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <?= $group_list[0]->group_name ?>
    </div>
    <?php $this->load->view('backend/user/phonebook/menu');
    ?>
    <?php
    ?>
    <script>
        init.push(function () {
            $('#jq_message_log').dataTable();
            $('#jq_message_log_wrapper .table-caption').text('Mobile Phone List');
            $('#jq_message_log_wrapper .dataTables_filter input').attr('placeholder', 'Search...');
        });
    </script>
    <div class="panel panel-default table-responsive">

        <div class="table-light">
            <div role="grid" id="jq_message_log_wrapper" class="dataTables_wrapper form-inline no-footer">

                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="jq_message_log" aria-describedby="jq-datatables-example_info">

                    <thead>
                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column ascending" style="width: 2%;">
                                #
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 20%;">
                                Created
                            </th>                    
                            <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 40%">
                                Phone
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 1;
                       
                        foreach ($rst as $rows) {
                            ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><?php
                                    echo (new Cake\I18n\Time($rows->created_at))->timeAgoInWords();
                                    ?></td>
                                <td><?php
                                    echo $rows->mobile
                                    ?>
                                </td>

                                <td>

                        <center>
                            <?php
                            if (1) {
                                ?>
                                <a href="#formModal<?php echo $rows->id ?>"  data-toggle="modal"><i class="fa fa-edit"></i></a>
                                <?php
                            } else {
                                echo 'Access denied';
                            }
                            ?>
                            <?php
                            if (1) {
                                echo anchor('user_phonebook/delete_contact/' . $rows->id * date('Y') . '/' . $id * date('Y'), '<i class="fa fa-trash-o"></i>', array('onclick' => "return confirm('You are about to delete this contact')"));
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

        <?php echo form_open('user_phonebook/update_contact/' . $id * date('Y'), array('enctype' => 'multipart/form-data')) ?>

        <div class="modal fade" id="formModal<?php echo $rows->id ?>">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4>Update</h4>
                    </div>
                    <div class="modal-body">


                        <input type="hidden" name="id" value="<?php echo $rows->id ?>">
                        <input type="hidden" name="group_id" value="<?php echo $id*  date('Y'); ?>">

                        <!---->

                        <div class="panel panel-default">

                            <div class="panel-body">


                                <div class="form-group">
                                    <label for="exampleInputEmail1">Mobile</label>
                                    <input type="text" class="form-control input-sm" id="mobile" placeholder="Mobile" value="<?php echo $rows->mobile ?>" name="mobile"  required="required">
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



