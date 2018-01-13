<?php $this->load->view('Layout/backend/header', []);
?>

<div class="panel panel-default">
    <div class="panel-heading">

    </div>

    <?php $this->load->view('backend/admin/emails/menu');
    ?>

<div class="row block-body">

    <div class="col-sm-6">
     <?php echo form_open('admin_emails/send_email', array('class' => 'panel form-horizontal', 'id' => 'sms-form')); ?>
        <div class="panel-heading">
            <span class="panel-title">Email Form</span>
        </div>
        <div class="panel-body">
                  <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group no-margin-hr">
                            <label class="control-label">From</label>
                            <input type="text" required="required"  name="from" id="from" value="<?= $this->session->userdata('email') ?>"  class="form-control">
                        </div>
                    </div><!-- col-sm-6 -->
                </div><!-- row -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group no-margin-hr">
                            <label class="control-label">To</label>
                            <?php foreach ($this->db->select('*')->from('email_list')->where('user_id = "' . $this->session->userdata('id') . '"')->get()->result() as $rows): ?>
                                <label class="checkbox-inline">
                                    <input type="checkbox"name="email_list[]" value="<?php echo $rows->id ?>" class="px"> <span class="lbl"><?php echo $rows->list_name ?></span>
                                </label>       

                            <?php endforeach; ?>
                        </div>
                    </div><!-- col-sm-6 -->
                </div><!-- row -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group no-margin-hr">
                            <label class="control-label">Subject</label>
                            <input type="text" name="subject" class="form-control" placeholder="Email Subject" required="required">
                        </div>
                    </div><!-- col-sm-6 -->
                </div><!-- row -->

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group no-margin-hr">
                            <label class="control-label">Message via Template</label>
                            <select name="template" class="form-control input-sm" id="template" >
                                <?php foreach ($this->db->select('*')->from('email_template')->where('user_id = "' . $this->session->userdata('id') . '"')->get()->result() as $template): ?>
                                    <option value="<?= $template->id ?>"><?= $template->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div><!-- col-sm-12 -->
                </div><!-- row -->
                <div class="row">
                    <div class="col-sm-6">
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
                     <div class="col-sm-6">

                        <div class="form-group no-margin-hr">
                            <label class="control-label">Time</label>
                            <script>

                                init.push(function () {
                                    var options = {
                                        minuteStep: 5,
                                        use24hours: true,
                                        format: 'HH:mm',
                                        orientation: $('body').hasClass('right-to-left') ? {x: 'right', y: 'auto'} : {x: 'auto', y: 'auto'}
                                    }
                                    $('#bs-timepicker').timepicker(options);
                                });

                            </script>
                            <input type="text" id="bs-timepicker" name="bs-timepicker" class="form-control">
                        </div> 
                    </div>
                </div><!-- row -->
        </div>
        <div class="panel-footer text-right">
            <button class="btn btn-primary" id="send" name="send">Send</button>
        </div>
        </form>
    </div>

</div>

</div>

<?php $this->load->view('Layout/backend/footer', []); ?> 
