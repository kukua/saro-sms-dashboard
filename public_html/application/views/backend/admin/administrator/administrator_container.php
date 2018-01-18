<?php $this->load->view('Layout/backend/header', []); 

$user_accounts = $this->db
                ->select('*')
                ->from('users')
                ->where('dept != "admin"')
                ->order_by('created DESC')
                ->get()
                ->result();

?>
<div class="panel panel-default">
    <div class="panel-heading">
    </div>
<?php $this->load->view('backend/admin/administrator/menu');
    ?>
<!-- SENT MESSAGE LOGS !-->
<script>
    init.push(function () {
        $('#jq_user_accounts_log').dataTable();
        $('#jq_user_accounts_log_wrapper .table-caption').text('User Accounts');
        $('#jq_user_accounts_log_wrapper .dataTables_filter input').attr('placeholder', 'Search...');
    });
</script>
<div class="table-light">
    <div role="grid" id="jq_user_accounts_log_wrapper" class="dataTables_wrapper form-inline no-footer">

        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="jq_user_accounts_log" aria-describedby="jq-datatables-example_info">
            <thead>
                <tr role="row">
                    <th class="sorting_asc" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column ascending" style="width: 2%;">
                        #
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 12%;">
                        Created
                    </th>                    
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 15%">
                       Fullname
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 15%">
                       Email
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%">
                       Mobile
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%">
                       Account Type
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%">
                       SMS Credits
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%">
                       Email Credits
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 8%;">
                        Action
                    </th>
            </thead>
            <tbody>
                <?php
                $count = 1;
                foreach ($user_accounts as $user_account):
                    ?>
                    <tr>
                        <td><?php echo $count++ ?></td>
                        <td><?php
                            echo (new Cake\I18n\Time($user_account->created))->timeAgoInWords();
                            ?>
                        </td>                        
                    <td><?php echo anchor('admin_administration/user_account_profile/'.$user_account->id*date('Y'),$user_account->fullname);?></td>
                    <td><?php echo anchor('admin_administration/user_account_profile/'.$user_account->id*date('Y'),$user_account->email);?></td>
                    <td><?php echo $user_account->mobile;?></td>
                    <td><?php echo humanize($user_account->dept);?></td>
                    <td><?php echo number_format($user_account->credits);?></td>
                    <td><?php echo number_format($user_account->email_credits);?></td>
                        <td>
                            <a  href="#formModal<?php echo $user_account->id?>"  data-toggle="modal"> <i class="fa fa-edit fa-lg"></i></a>&nbsp;
                           <?php echo anchor('admin_administration/delete_user/'.$user_account->id*date('Y'),'<i class="fa fa-trash-o fa-lg"></i>',array('onclick'=>"return confirm('You are about to delete this user account')"));?>
                        </td>

                    </tr>
                    <?php
                endforeach;
                ?>
                    
            </tbody>
        </table>

    </div>
</div>
<?php
foreach($user_accounts as $rows)
{

    ?>

    <?php echo form_open('admin_administration/edit_account')?>

    <div class="modal fade" id="formModal<?php echo $rows->id?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4>Update Account information</h4>
                </div>
                <div class="modal-body">


                  <input type="hidden" name="id" value="<?php echo $rows->id*date('Y');?>">

                    <!---->

                    <div class="panel panel-default">

                        <div class="panel-body">


                            <div class="form-group">
                                <label for="exampleInputEmail1">Full Name:</label>
                                <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Full Name"  value="<?php echo $rows->fullname?>" name="fullname_edit"  required="required">
                            </div><!-- /form-group -->


                            <div class="form-group">
                                <label for="exampleInputEmail1">Mobile :</label>
                                <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Mobile" name="mobile_edit"  value="<?php echo $rows->mobile?>"  required="required">
                            </div><!-- /form-group -->

                            <div class="form-group">
                                <label for="exampleInputEmail1">Email Address :</label>
                                <input type="email" class="form-control input-sm" id="exampleInputEmail1" placeholder="Email Address" name="email_edit"  value="<?php echo $rows->email?>"  required="required">
                            </div><!-- /form-group -->



                            <div class="form-group">
                                <label for="exampleInputEmail1">New Password:</label>
                                <input type="password" class="form-control input-sm" id="exampleInputEmail1" placeholder="Password" name="password_edit"  required="required">
                            </div><!-- /form-group -->


                            <div class="form-group">
                                <label for="exampleInputEmail1">New Password Again:</label>
                                <input type="password" class="form-control input-sm" id="exampleInputEmail1" placeholder="Password Again" name="repassword_edit"  required="required">
                            </div><!-- /form-group -->

                            <div class="form-group">
                                <label for="exampleInputEmail1">Account Type:</label>
                                <select name="accounttype_edit" class="form-control input-sm" id="exampleInputEmail1" >
                                    <option value="reseller" <?php echo $rows->dept=="reseller"?'selected="selected"':''?>>Reseller</option>
                                    <option value="user" <?php echo $rows->dept=="user"?'selected="selected"':''?>>Normal User</option>
                                </select>
                            </div><!-- /form-group -->





                        </div>
                    </div>




                    <!---->








                    <div class="form-group text-right">
                            <button href="#" class="btn btn-success"><i class="fa fa-edit"></i> Edit User Account</button>

                        </div>

                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->

    </div>
    <?php echo form_close();?>
<?php

}
?>
</div>


<?php $this->load->view('Layout/backend/footer', []); ?>    