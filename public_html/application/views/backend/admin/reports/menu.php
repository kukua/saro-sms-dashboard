<div class="grey-container shortcut-wrapper">

    <?php
    
    $user_accounts = $this->db
                ->select('*')
                ->from('users')
                ->where('dept != "admin"')
                ->order_by('email ASC')
                ->get()
                ->result();
    
     echo anchor('#formModal','
					<span class="shortcut-icon">
						<i class="fa fa-pencil-square-o"></i>
					</span><span class="text">Choose Another Month</span>',array('class'=>'shortcut-link','data-toggle'=>"modal"));
     

    echo anchor('admin_reports/messages','
					<span class="shortcut-icon">
						<i class="fa fa-comments"></i>
					</span>
						   <span class="text"> Messages</span>',array('class'=>'shortcut-link'))

    ?>



    <?php

    echo anchor('admin_reports/payments','
					<span class="shortcut-icon">
						<i class="fa fa-credit-card"></i>
					</span>
						   <span class="text"> Payments</span>',array('class'=>'shortcut-link'))

    ?>

</div><!-- /grey-container -->

<div class="modal fade" id="formModal">
        
 <?php echo form_open('admin_reports/messages')?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4>Monthly Messages Report</h4>
                </div>
                <div class="modal-body">

                    <!---->

                    <div class="panel panel-default">

                        <div class="panel-body">

                            <div class="form-group">
                                <label for="exampleInputEmail1">Select Month</label>
                                <select name="month" class="form-control input-sm" id="month" >
                                    <option value="01">January</option>
                                    <option value="02">February</option>
                                    <option value="03">March</option>
                                    <option value="04">April</option>
                                    <option value="05">May</option>
                                    <option value="06">June</option>
                                    <option value="07">July</option>
                                    <option value="08">August</option>
                                    <option value="09">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div><!-- /form-group -->

                            <div class="form-group">
                                <label for="exampleInputEmail1">Select Client</label>
                                <select name="user_id" class="form-control input-sm" id="user_id" >
                                    <?php
                                    foreach ($user_accounts as $user_account):
                                     ?>
                                    <option value="<?=$user_account->id?>"><?=$user_account->email?></option>
                                     <?php
                                    endforeach;
                                    ?>
                                </select>
                            </div><!-- /form-group -->
                        </div>
                    </div>




                    <!---->








                    <div class="form-group text-right">
                            <button href="#" class="btn btn-success"><i class="fa fa-plus"></i> Generate Report</button>

                        </div>

                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->

    <?php echo form_close();?>
    </div>