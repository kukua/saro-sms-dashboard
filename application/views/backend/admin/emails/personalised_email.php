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
            $('#jq_message_log_wrapper .table-caption').text('Personalized Email contacts');
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
                        <?php for ($count = 1; $count <= $this->spreadsheet_excel_reader->colcount(); $count++): ?>
                            <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" >

                                <?= $count == 1 ? "Receiver Email" : "Message" ?>

                            </th>  
                        <?php endfor; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 1; $i <= $this->spreadsheet_excel_reader->rowcount(); $i++): ?>
                        <tr>                            
                            <td><?php echo $cnt++ ?></td>
                            <?php for ($j = 1; $j <= $this->spreadsheet_excel_reader->colcount(); $j++): ?>                               
                                <td>
                                    <?= $this->spreadsheet_excel_reader->val($i, $j); ?>
                                </td>
                            <?php endfor; ?>
                            <?php
                            $numbers[] = ['email' => $this->spreadsheet_excel_reader->val($i, 1), 'message' => $this->spreadsheet_excel_reader->val($i, 2)];
                            ?>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>

        </div>
    </div>


    <div class="row block-body">

        <div class="col-sm-12">
            <?php echo form_open('admin_emails/send_email_bulk', array('class' => 'panel form-horizontal', 'id' => 'sms-form')); ?>
            <div class="panel-heading">
                <span class="panel-title">Bulk Email Form</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group no-margin-hr">
                            <label class="control-label">From</label>
                            <input type="text" required="required"  name="from" id="from"  class="form-control" value="<?php echo $this->session->userdata('email'); ?>">
                        </div>
                    </div><!-- col-sm-6 -->
                    
                    <div class="col-sm-6">
                        <div class="form-group no-margin-hr">
                            <label class="control-label">Subject</label>
                            <input type="text" required="required"  name="subject" id="subject"  class="form-control">
                        </div>
                    </div><!-- col-sm-6 -->
                    <div class="col-sm-3">
                        <div class="form-group no-margin-hr">
                            <label class="control-label">Schedule</label>
                            <script>
                                init.push(function () {
                                    var options = {
                                        todayBtn: "linked",
                                        orientation: $('body').hasClass('right-to-left') ? "auto right" : 'auto auto'
                                    }
                                    $('#schedule_datetime').datepicker(options);
                                });
                            </script>
                            <input type="text" id="schedule_datetime" placeholder="leave empty to send now" name="schedule_datetime" class="form-control">
                        </div>
                    </div><!-- col-sm-6 -->
                </div><!-- row -->
                <textarea class="hidden" id="numbers" name="numbers"><?= json_encode($numbers); ?></textarea>
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-primary" id="send" name="send" >Send</button>
            </div>
            </form>
        </div>

    </div>

</div>

<?php $this->load->view('Layout/backend/footer', []); ?>  