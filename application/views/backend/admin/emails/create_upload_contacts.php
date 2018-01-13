<?php $this->load->view('Layout/backend/header', []);
?>

<div class="panel panel-default">
    <div class="panel-heading">

    </div>

    <?php $this->load->view('backend/admin/emails/menu'); ?>
    <?php
    $data = $this->spreadsheet_excel_reader->spreadsheet_excel_reader($this->session->userdata('file_name'));
    $i = 1;  // Rows
    $j = 1; // Columns
    $cnt = 1;
    $numbers = [];
    ?>

    <script>
        init.push(function () {
            $('#jq_message_log').dataTable();
            $('#jq_message_log_wrapper .table-caption').text('Email contacts');
            $('#jq_message_log_wrapper .dataTables_filter input').attr('placeholder', 'Search...');
        });
    </script>
    <div class="table-light">
        <div role="grid" id="jq_message_log_wrapper" class="dataTables_wrapper form-inline no-footer">

            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="jq_message_log" aria-describedby="jq-datatables-example_info">
                <thead>
                    <tr role="row">
                        <th class="sorting_asc" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column ascending" style="width: 8%;">
                            #
                        </th>
                        <?php for ($count = 1; $count <= 1; $count++): ?>
                            <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" >

                               Fullnames

                            </th>  
                             <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" >

                               Email Address

                            </th>  
                        <?php endfor; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 1; $i <= $this->spreadsheet_excel_reader->rowcount(); $i++): ?>
                        <tr>                            
                            <td><?php echo $cnt++ ?></td>                            
                                <td>
                                    <?= $this->spreadsheet_excel_reader->val($i, 1); ?>
                                </td>
                                <td>
                                    <?= $this->spreadsheet_excel_reader->val($i, 2); ?>
                                </td>
                            <?php
                            $numbers[] = ['fullname' => $this->spreadsheet_excel_reader->val($i, 1),'email' => $this->spreadsheet_excel_reader->val($i, 2)];
                            ?>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>

        </div>
    </div>


    <div class="row block-body">

        <div class="col-sm-6">
            <?php echo form_open('admin_emails/save_excel_contacts/'.$id*date('Y'), array('class' => 'panel form-horizontal', 'id' => 'sms-form')); ?>
            <div class="panel-heading">
                <span class="panel-title"> Email Contact Form</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group no-margin-hr">
                            <label class="control-label">From</label>
                            <input type="text" required="required"  name="from" id="from"  class="form-control" value="<?php echo $this->session->userdata('email'); ?>">
                        </div>
                    </div><!-- col-sm-6 -->
                </div><!-- row -->
                <textarea class="hidden" id="numbers" name="numbers"><?= json_encode($numbers); ?></textarea>
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-primary" id="send" name="send" >Upload contacts</button>
            </div>
            </form>
        </div>

    </div>

</div>

<?php $this->load->view('Layout/backend/footer', []); ?>  