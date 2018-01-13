<?php $this->load->view('Layout/backend/header', []);
?>

<div class="panel panel-default">
    <div class="panel-heading">

    </div>

    <?php $this->load->view('backend/user/phonebook/menu'); ?>
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
            $('#jq_message_log_wrapper .table-caption').text('Mobile contacts');
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

                               Mobile Phone Number

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
                            <?php
                            $numbers[] = ['mobile' => $this->spreadsheet_excel_reader->val($i, 1)];
                            ?>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>

        </div>
    </div>


    <div class="row block-body">

        <div class="col-sm-12">
            <?php echo form_open('user_phonebook/save_excel_contacts/'.$id*date('Y'), array('class' => 'panel form-horizontal', 'id' => 'sms-form')); ?>
            <textarea class="hidden" id="numbers" name="numbers"><?= json_encode($numbers); ?></textarea>

            <div class="panel-footer text-right">
                <button class="btn btn-primary" id="send" name="send" >Upload contacts</button>
            </div>
            </form>
        </div>

    </div>

</div>

<?php $this->load->view('Layout/backend/footer', []); ?>  