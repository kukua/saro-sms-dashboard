<div class="grey-container shortcut-wrapper">

    <?php

    echo anchor('#formModal','
					<span class="shortcut-icon">
						<i class="fa fa fa-plus"></i>
					</span>
						   <span class="text">Create Group </span>',array('class'=>'shortcut-link','data-toggle'=>"modal"));

    ?>
     <?php
   if(isset($id)):
    echo anchor('#nformModal','
					<span class="shortcut-icon">
						<i class="fa fa-plus"></i><i class="fa fa-user"></i>
					</span>
						   <span class="text">Add Contact </span>',array('class'=>'shortcut-link','data-toggle'=>"modal"));
    endif;

    ?>
    
    <?php
   if(isset($id)):
    echo anchor('admin_phonebook/upload_contacts/'.$id*  date('Y'),'
					<span class="shortcut-icon">
						<i class="fa fa-upload"></i>
					</span>
						   <span class="text">Upload Phone Contacts </span>',array('class'=>'shortcut-link','data-toggle'=>"modal"));
    endif;

    ?>

</div><!-- /grey-container -->



<div class="modal fade " id="formModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4>Create Group Name</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('admin_phonebook/create_group')?>

                <!---->

                <div class="panel panel-default">

                    <div class="panel-body">

                        <div class="form-group">
                            <label for="exampleInputEmail1">Group Name:</label>
                            <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Group Name" name="group_name"  required="required">
                        </div><!-- /form-group -->


                    </div>
                </div>


                <div class="form-group text-right">
                    <button href="#" class="btn btn-success"><i class="fa fa-plus"></i> Create </button>

                </div>
                <?php echo form_close();?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div class="modal fade " id="nformModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4>New Mobile Phone</h4>
            </div>
            <div class="modal-body">
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
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>