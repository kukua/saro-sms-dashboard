<?php $this->load->view('Layout/backend/header', []);
?>
<div class="panel panel-default">
    <div class="panel-heading">
    </div>

<?php $this->load->view('backend/admin/crm/menu'); ?>



<style type="text/css">
    .mobilephoneframe {

        margin:0 -20px 0 -20px;
        margin-right:20px;
        padding:20px;
        background:url(<?php echo base_url(); ?>/assets/backend/images/iPhone5_frame.png) 0px 0px no-repeat;
        height:1008px;
        background-size: 280px 500px
    }
    #msgprev {
        margin:55px 0  0 5px;
        width:232px;
        padding:10px;
        border:none;
        height:350px;
        overflow:auto;
        font-size:14px;
        word-wrap: break-word;
        background-color: rgba(147,0,255,0.05);
    }
</style>

<div class="row block-body">

    <div class="col-sm-6">
        <?php echo form_open('admin_crm/sendbulk', array('class' => 'panel form-horizontal', 'id' => 'sms-form')); ?>
        <div class="panel-heading">
            <span class="panel-title">Bulk SMS Form</span>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group no-margin-hr">
                        <label class="control-label">Sender</label>
                        <input type="text" required="required"  name="sender" id="sender"  class="form-control">
                    </div>
                </div><!-- col-sm-6 -->
                <div class="col-sm-6">
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
            </div><!-- row -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group no-margin-hr">
                        <label class="control-label">Message</label>
                        <textarea id="msg" class="form-control" name="msg" placeholder="Messages goes here" cols="30" rows="5" required="required"  ></textarea>
                        <div id="msgcharcount" ></div>
                    </div>
                </div><!-- col-sm-6 -->
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
                        <input type="text" id="schedule_datetime" name="schedule_datetime" class="form-control">
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





<script type="text/javascript">



    $("body").delegate("#sender,#msg", "click keyup", function () {

        var
                sender = $('#sender').val(),
                msg = $("#msg").val(),
                msg_ = '<b>From : ' + sender + '</b><br/><br/> ' + msg,
                count = msg.length;

        if (count > 0) {

            var msg_count = parseInt(count / 160);
            msg_count++;

            $('#msgcharcount').html(msg_count + ' | ' + count);

        } else {
            $('#msgcharcount').html('1');
        }

        $('#msgprev').html(msg_);

    });



    //$(".option1").hide("fast");
    $("#option").attr("value", "copy");
    $(".option2").hide("fast");

    function triggersms()
    {

        if (confirm("You are about to send this SMS"))
        {
            sendsms();

        } else
            return false;

    }


    /*$(".receiver").click(function(){
     
     
     
     });*/


    $("body").delegate("#copypaste_option", "keyup change", function () {

        if ($("#copypaste_option").val() == 'copy')
        {
            $(".option1").show(100);
            $(".option2").hide(100);
            $("#option").attr("value", "copy");

        } else {

            $(".option1").hide(100);
            $(".option2").show(100);
            $("#option").attr("value", "group");
            $("#copypaste").val("");
        }

        //alert($("#copypaste_option").val());

    });

    //var time = setInterval(f, 1000);



    //var time_send = setTimeout(sendsms_now, 3000);

    //});
</script>
<?php $this->load->view('Layout/backend/footer', []); ?>  