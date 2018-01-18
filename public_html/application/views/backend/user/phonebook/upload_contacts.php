<?php $this->load->view('Layout/backend/header', []);
?>

<div class="panel panel-default">
    <div class="panel-heading">
    </div>


    <?php $this->load->view('backend/user/phonebook/menu'); ?>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="note note-info">
                    Note:
                    With this tool you can upload an Excel file with messages organised in columns, with each column containing a customized item specific to a receiver of the message. Please do not include headers for the columns, only include data that is to be sent in the message. You can also add multiple files in csv and xls (Microsoft Excel 2003 and before) formats

                    Please make sure filename has no spaces i.e. my contaxts.xls should be named my_contacts.xls.

                </div>


                <?php
//print_r( $this->session->userdata('file_name'));
                ?>

                <?php
                echo form_open('user_phonebook/create_upload_contacts/'.$id*date('Y'), array('enctype' => 'multipart/form-data'));
                ?>

                <input type="file" name="contacts" style="display:none;" id="file" required >

                <div class="btn btn-info button button-blue" style="width:200px; font-size:13px;"> Select File
                </div>
                <div class="result" style="margin-top:5px;"></div>
                <div class="panel-footer text-right">
                    <button class="btn btn-primary" id="upload" name="upload" >Upload contacts</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>

    $(function () {

        $(".button-gray").hide();

        $(".button-blue").click(function () {
            $(".button-gray").hide();
            $("#file").click();




        });


        $("body").delegate("#file", "change", function () {

            var file_ext = $("#file").val();

            var ext = $("#file").val().substring(file_ext.length - 3, file_ext.length);
            if (ext != 'xls')
            {

                alert('Invalid file format, The file has to be Excel i.e. xls (Microsoft Excel 2003 and before)');
                $("#file").val("");
                $(".result").html("Invalid file format, The file has to be Excel i.e. xls (Microsoft Excel 2003 and before)");

            } else {

                $(".result").html('You have choosen ' + $("#file").val());
                $(".button-gray").show();
            }
        });


    });

</script>


<?php $this->load->view('Layout/backend/footer', []); ?>  