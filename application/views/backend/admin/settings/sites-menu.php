<div class="grey-container shortcut-wrapper">

    <?php

    echo anchor('#newsite','
					<span class="shortcut-icon">
						<i class="fa fa-plus"></i>
					</span>
						   <span class="text">Create Site </span>',array('class'=>'shortcut-link','data-toggle'=>"modal"));
    
    
    echo anchor('admin_system_settings/sites', '
					<span class="shortcut-icon">
						<i class="fa fa-sitemap"></i>
					</span>
						   <span class="text">Sites </span>', array('class' => 'shortcut-link'))
   

    ?>




</div><!-- /grey-container -->

 <div class="modal fade" id="newsite">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h5>Add Site</h5>
                </div>
                <?php echo form_open('admin_system_settings/create_site'); ?>
                <div class="modal-body">


                    <div class="form-group">
                        <label for="folderName">Site Name</label>
                        <input type="text" class="form-control input-sm" id="name" name="name" placeholder="Name" required="required">
                    </div>
                     <div class="form-group">
                        <label for="folderName">Domain</label>
                        <input type="text" class="form-control input-sm" id="domain" name="domain" placeholder="Domain" required="required">
                    </div>
                     <div class="form-group">
                        <label for="folderName">Text</label>
                        <input type="text" class="form-control input-sm" id="domain" name="text" placeholder="Text" required="required">
                    </div>
                     <div class="form-group">
                        <label for="folderName">Leaderboard</label>
                        <input type="text" class="form-control input-sm" id="leaderboard" name="leaderboard" placeholder="leaderboard" required="required">
                    </div>
                     <div class="form-group">
                        <label for="folderName">Sky scrapper</label>
                        <input type="text" class="form-control input-sm" id="skyscrapper" name="skyscrapper" placeholder="skyscrapper" required="required">
                    </div>
                     <div class="form-group">
                        <label for="folderName">banner</label>
                        <input type="text" class="form-control input-sm" id="banner" name="banner" placeholder="banner" required="required">
                    </div>
                     <div class="form-group">
                        <label for="folderName">mpu</label>
                        <input type="text" class="form-control input-sm" id="mpu" name="mpu" placeholder="mpu" required="required">
                    </div>
                     <div class="form-group">
                        <label for="folderName">fivesecs</label>
                        <input type="text" class="form-control input-sm" id="fivesecs" name="fivesecs" placeholder="fivesecs" required="required">
                    </div>
                     <div class="form-group">
                        <label for="folderName">fifteensecs</label>
                        <input type="text" class="form-control input-sm" id="fifteensecs" name="fifteensecs" placeholder="fifteensecs" required="required">
                    </div>
                     <div class="form-group">
                        <label for="folderName">thirtysecs</label>
                        <input type="text" class="form-control input-sm" id="thirtysecs" name="thirtysecs" placeholder="thirtysecs" required="required">
                    </div>
                     <div class="form-group">
                        <label for="folderName">sixtysecs</label>
                        <input type="text" class="form-control input-sm" id="sixtysecs" name="sixtysecs" placeholder="sixtysecs" required="required">
                    </div>


                </div>
                <div class="modal-footer">

                    <button  class="btn btn-danger btn-sm">Save changes</button>
                </div>
                <?php echo form_close();?>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->