<?php $this->load->view('Layout/backend/header', []);
?>

<div class="panel panel-default">
    <div class="panel-heading">
    </div>


    <?php $this->load->view('backend/admin/phonebook/menu'); ?>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
               <?php echo form_open('admin_phonebook/save_contact')?>

                <!---->

                <div class="panel panel-default">

                    <div class="panel-body">

                        <div class="form-group">
                            <label for="exampleInputEmail1">Mobile phone:</label>
                            <input type="text" class="form-control input-sm" id="mobile" placeholder="Mobile" name="mobile"  required="required">
                            <input type="hidden" class="form-control input-sm" id="group_id" placeholder="group_id" value="<?=$id *date('Y')?>" name="group_id" >

                        </div><!-- /form-group -->


                    </div>
                </div>


                <div class="form-group text-right">
                    <button href="#" class="btn btn-success"><i class="fa fa-plus"></i> Create </button>

                </div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('Layout/backend/footer', []); ?>  