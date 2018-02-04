<?php $this->load->view('Layout/backend/header', []);
?>
<div class="panel panel-default">
    <div class="panel-heading">

    </div>
    <?php $this->load->view('backend/admin/emails/menu');
    ?>

    <script>
        init.push(function () {
            $('#dataTable').dataTable();
            $('#dataTable_wrapper .table-caption').text('This Month Email Logs');
            $('#dataTable_log_wrapper .dataTables_filter input').attr('placeholder', 'Search...');
        });
    </script>
    <div class="panel panel-default table-responsive">

        <div class="table-light">

            <div role="grid" id="dataTable_wrapper" class="dataTables_wrapper form-inline no-footer">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="dataTable" aria-describedby="jq-datatables-example_info">
                    <thead>
                        <tr>
                            <th class="sorting_asc" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column ascending" style="width: 5%;">
                                #
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                                Created
                            </th>                    
                            <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 20%">
                                From
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                                To
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 30%;">
                                Subject
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                                Charge
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                                Action
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 1;
                        $rst = $this->db
                                ->select('e.*,u.fullname,  charge')
                                ->from('email_outbox e, users u')
                                ->where('e.sent_by = u.id and e.uuid = "' . $id . '"')
                                ->where('e.schedule_datetime between "' . $start_date . '" and "' . $end_date . '"')
                                ->order_by('e.created_at DESC')
                                ->limit(0, 100)
                                ->get()
                                ->result();



                        foreach ($rst as $rows) {
                            ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><?php
                                    echo $rows->created_at
                                    ?></td>

                                <td><?php
                                    echo $rows->fullname
                                    ?></td>

                                <td><?php
                                    echo $rows->email_recipients
                                    ?></td>

                                <td><?php echo anchor('admin_emails/email_preview/' . $rows->uuid, $rows->email_title); ?></td>


                                <td><?php
                                    echo number_format($rows->charge) . ' USD'
                                    ?></td>
                                <td>
                                    <?php echo $this->spectrum_func->delivery_status($rows->email_status); ?> &nbsp;
                                    <?php echo anchor('admin_emails/resend_one_sent_email/' . $rows->id, '<i class="fa fa-refresh fa-lg"></i> ', array('onclick' => "return confirm('You are about to resend this message')"));
                                    ?>&nbsp;
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

    <div class="modal fade" id="formModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4>Create Email list</h4>
                </div>
                <div class="modal-body">
                    <?php echo form_open('') ?>

                    <!---->

                    <div class="panel panel-default">

                        <div class="panel-body">


                            <div class="form-group">
                                <label for="exampleInputEmail1">Email List:</label>
                                <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Email list" name="emaillist"  required="required">
                            </div><!-- /form-group -->





                        </div>
                    </div>




                    <!---->




                    <div class="form-group text-right">
                        <button href="#" class="btn btn-success"><i class="fa fa-plus"></i> Create </button>

                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>



    <?php
    foreach ($rst as $rows) {
        ?>

        <?php echo form_open('') ?>

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
                                    <label for="exampleInputEmail1">Email List:</label>
                                    <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Email list" value="<?php echo $rows->list_name ?>" name="emaillist_edit"  required="required">
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




