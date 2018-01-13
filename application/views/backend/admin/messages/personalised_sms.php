<?php $this->load->view('Layout/backend/header', []);
?>

<div class="panel panel-default">
    <div class="panel-heading">

    </div>

    <?php $this->load->view('backend/admin/messages/menu'); ?>
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
            $('#jq_message_log_wrapper .table-caption').text('Personalized SMS contacts');
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

                                <?= $count == 1 ? "Receiver" : "Message" ?>

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
                            $numbers[] = ['number' => $this->spreadsheet_excel_reader->val($i, 1), 'message' => $this->spreadsheet_excel_reader->val($i, 2)];
                            ?>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>

        </div>
    </div>


    <div class="row block-body">

        <div class="col-sm-12">
            <?php echo form_open('admin_messages/send_psms_bulk', array('class' => 'panel form-horizontal', 'id' => 'sms-form')); ?>
            <div class="panel-heading">
                <span class="panel-title">Bulk SMS Form</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group no-margin-hr">
                            <label class="control-label">Sender</label>
                            <input type="text" required="required"  name="sender" id="sender"  class="form-control" value="<?php echo $this->session->userdata('mobile'); ?>">
                        </div>
                    </div><!-- col-sm-6 -->
                    <div class="col-sm-4">
                        <div class="form-group no-margin-hr">
                            <label class="control-label">Route</label>
                            <select id="route" name= "route"     class="form-control"  >
                                <option value="All"  >All</option>
                                <?php
                                $rst = $this->db->select('*')->from('routes')->get()->result();

                                foreach ($rst as $rows) {
                                    echo '<option value="' . $rows->id . '">' . $rows->name . '</option>';
                                }
                                ?>

                            </select>
                        </div>
                    </div><!-- col-sm-6 -->
                    <div class="col-sm-4">
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
                            <input type="text" id="schedule_datetime" name="schedule_datetime" class="form-control">
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
        <div class="col-sm-6">
            <div style=" width:450px; height:725px;">
                <div class="mobilephoneframe">
                    <div id="msgprev"><strong></strong>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

<?php $this->load->view('Layout/backend/footer', []); ?>  