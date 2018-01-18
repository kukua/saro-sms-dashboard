<div class="grey-container shortcut-wrapper">

    <?php

    echo anchor('#newnetwork','
					<span class="shortcut-icon">
						<i class="fa fa-plus"></i>
					</span>
						   <span class="text">Create Network </span>',array('class'=>'shortcut-link','data-toggle'=>"modal"));
    
    echo anchor('#newroute','
					<span class="shortcut-icon">
						<i class="fa fa-plus"></i>
					</span>
						   <span class="text">Create Route </span>',array('class'=>'shortcut-link','data-toggle'=>"modal"));
    
    echo anchor('admin_system_settings','
					<span class="shortcut-icon">
						<i class="fa fa-road"></i>
					</span>
						   <span class="text">Message Routes </span>',array('class'=>'shortcut-link'));
    echo anchor('admin_system_settings/mobile_networks','
					<span class="shortcut-icon">
						<i class="fa fa-wifi"></i>
					</span>
						   <span class="text">Mobile Networks </span>',array('class'=>'shortcut-link'));
    /*echo anchor('admin_system_settings/messaging_routes','
					<span class="shortcut-icon">
						<i class="fa fa-money"></i>
					</span>
						   <span class="text">Messaging Costs </span>',array('class'=>'shortcut-link'));*/
    echo anchor('admin_system_settings/message_delivery','
					<span class="shortcut-icon">
						<i class="fa fa-check"></i>
					</span>
						   <span class="text">Message Delivery Settings </span>',array('class'=>'shortcut-link'));
        
    echo anchor('admin_system_settings/sites', '
					<span class="shortcut-icon">
						<i class="fa fa-sitemap"></i>
					</span>
						   <span class="text">Sites </span>', array('class' => 'shortcut-link'));
   
    echo anchor('admin_system_settings/tools', '
					<span class="shortcut-icon">
						<i class="fa fa-cog"></i>
					</span>
						   <span class="text">Tools </span>', array('class' => 'shortcut-link'));        

    ?>




</div><!-- /grey-container -->

 <div class="modal fade" id="newnetwork">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h5>Add Network</h5>
                </div>
                <?php echo form_open('admin_system_settings/create_mobile_networks_networks'); ?>
                <div class="modal-body">


                    <div class="form-group">
                        <label for="folderName">Network Name</label>
                        <input type="text" class="form-control input-sm" id="folderName" name="network_name" placeholder="Network Name" required="required">
                    </div>

                    <div class="form-group">
                        <label for="folderName">Phone Prefix </label><small> Include Country code on prefix</small>
                        <input type="text"  class="form-control input-sm" id="folderName" name="prefix" placeholder="Mobile Prefix e.g. 25674 " required="required">
                    </div>


                    <div class="form-group">
                        <label for="folderName">Default Cost</label>
                        <input type="text" class="form-control input-sm" id="folderName" name="cost" placeholder="Default SMS Cost" required="required">
                    </div>

                    <div class="form-group">
                        <label for="folderName">Route Name</label>
                        <select class="form-control input-sm" id="route" name="route" >
                            <?php

                                $routes = $this->db->select('*')
                                    ->from('routes')
                                    ->order_by('name')
                                    ->get()->result();
                                foreach($routes as $rows)
                                {
                                ?>
                                    <option value="<?php echo $rows->id?>"><?php echo $rows->name?></option>
                                    <?php

                                }
                                    
                              ?>

                        </select>
                    </div>



                </div>
                <div class="modal-footer">

                    <button  class="btn btn-danger btn-sm">Save changes</button>
                </div>
                <?php echo form_close();?>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

 <div class="modal fade" id="newroute">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5>Add SMS Route</h5>
            </div>
          <?php echo form_open('admin_system_settings/create_route'); ?>
            <div class="modal-body">


                <div class="form-group">
                    <label for="folderName">Route Name</label>
                    <input type="text" class="form-control input-sm" id="folderName" name="route_name" placeholder="Route Name" required="required">
                </div>

                <div class="form-group">
                    <label for="folderName">URL</label>
                    <input type="url" class="form-control input-sm" id="folderName" name="url" placeholder="URL" required="required">
                </div>


                <div class="form-group">
                    <label for="folderName">Batch Limit</label>
                    <input type="text" class="form-control input-sm" id="folderName" name="batch_limit" placeholder="Batch Limit" required="required">
                </div>

                <div class="form-group">
                    <label for="folderName">Method</label>
                    <select class="form-control input-sm" id="method" name="method" >
                        <option value="GET">GET</option>
                        <option value="POST">POST</option>
                    </select>
                </div>



            </div>
            <div class="modal-footer">

                <button  class="btn btn-danger btn-sm">Save changes</button>
            </div>
     <?php echo form_close();?>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
