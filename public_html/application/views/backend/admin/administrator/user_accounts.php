<!-- Datepicker -->
<?php echo link_tag('css/datepicker.css')?>

<script src="<?php echo base_url()?>/js/dateTimePicker.js"></script>


<!-- Timepicker -->
<?php echo link_tag('css/bootstrap-timepicker.css')?>


<!--------->

<div class="panel panel-default table-responsive">
    <div class="panel-heading">
        <h3>User Administration</h3>

        <?php
        //if($this->mobilebet->access_control('administration','add'))
        {
        ?>
        <a href="#formModal"  data-toggle="modal"><i class="fa fa-plus"></i> New User Account:</a> |
            <a href="#formModal_search"  data-toggle="modal"><i class="fa fa-search"></i> Search User Account:</a>

            |
            <?php echo anchor('admin/administration/download','<i class="fa fa-download"></i> Download');?>

<?php
        }
?>


        <?php
        if(strlen($message) > 0 )
        {
            ?>

            <div class="alert <?php echo $alert?>">
                <center> <?php
                    echo $message;
                    ?></center>

            </div>

        <?php
        }

        ?>

    </div>



    <div class="padding-md clearfix">
        <table class="table table-striped" id="dataTable">
            <thead>
            <tr>
                <th>No</th>
                <th>Created</th>
                <th>Fullname</th>

                <th>Email</th>
                <th>Mobile</th>
                <th>Account Type</th>
                <th>SMS Credits</th>

                <th>Email Credits</th>





                <th>Action</th>

            </tr>
            </thead>
            <tbody>
            <?php
            $count = 1;
            $rst = $this->db
                ->select('*')
                ->from('users')
                ->where('dept != "admin"')
                ->order_by('fullname ASC')
                ->get()
                ->result();



            foreach($rst as $rows)
            {
                ?>
                <tr>
                    <td><?php echo $count;?></td>
                    <td><?php

                        $date = strtotime($rows->created);

                        $date_time = strtotime(date('Y-m-d H:i:s'));

                        $date = $date_time - $date;

                        $date = $date / (60);
                        $minutes = (int)$date ;

                        $hours  = (int)$date /60;



                        //echo $minutes.' seconds';

                        if((int)$date == 0)
                        {
                            echo ' a few seconds ago';

                        }else

                            if($minutes < 60)
                            {
                                echo (int)$date.' minutes ago';

                            }else if($minutes / (60) <= 24)
                            {

                                $hours  = (int)$minutes /60;
                                echo (int)$hours.' hours ago';

                            }else {

                                $hours  = (int)$minutes /60;
                                $hours = $hours/24;


                                if($hours <= 7)
                                {
                                    echo  (int)$hours.' day';

                                    if($hours==1)
                                    {
                                        echo '';
                                    }else echo 's';

                                    echo ' ago';


                                }

                                else {

                                    echo $rows->created ;

                                }


                            }






                        ?></td>
                    <td><?php echo anchor('admin/administration/user_account_profile/'.$rows->id*date('Y'),$rows->fullname);?></td>

                    <td><?php echo anchor('admin/administration/user_account_profile/'.$rows->id*date('Y'),$rows->email);?></td>
                    <td><?php echo $rows->mobile;?></td>
                    <td><?php echo humanize($rows->dept);?></td>
                    <td><?php echo number_format($rows->credits);?></td>
                    <td><?php echo number_format($rows->email_credits);?></td>
<td>

                        <center>
                            <?php
                            if(1)
                            {
                            ?>
                            <a href="#formModal<?php echo $rows->id?>"  data-toggle="modal"><i class="fa fa-edit"></i>Edit</a>
                            <?php
                            }else{
                                echo 'Access denied';
                            }
                            ?>
                            <?php
                            if(1)
                            {
                                echo anchor('admin/administration/delete/'.$rows->id*date('Y'),'<i class="fa fa-trash-o"></i> Delete',array('onclick'=>"return confirm('You are about to delete this User Account')"));
                            }?>
                        </center>
                    </td>


                </tr>
                <?php
                $count++;
            }
            ?>
            </tbody>
        </table>
    </div><!-- /.padding-md -->
</div><!-- /panel -->
<!---------->

<div class="panel-footer clearfix">

    <div class="pull-right">

        <ul class="pagination middle">
            <!--   <li class="disabled"><a href="#"><i class="fa fa-step-backward"></i></a></li>-->
            <li >
                <?php


                /* if($page-1 > 0)
                     echo anchor('admin/inbox/'.($page-1),'<i class="fa fa-caret-left large"> Previous</i>');
 */
                ?>
            </li>
            <li>
                <?php

                //  echo anchor('admin/inbox/'.($page+1),'<i class="fa fa-caret-right large"> Next</i>');

                ?>
            </li>
            <!--  <li><a href="#"><i class="fa fa-step-forward"></i></a></li>-->
        </ul>
    </div>
</div>
</div><!-- /panel -->



<div class="modal fade" id="formModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4>Create User Account</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('')?>

                <!---->

                <div class="panel panel-default">

                    <div class="panel-body">


                        <div class="form-group">
                            <label for="exampleInputEmail1">Full Name:</label>
                            <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Full Name" name="fullname"  required="required">
                        </div><!-- /form-group -->


                        <div class="form-group">
                            <label for="exampleInputEmail1">Mobile :</label>
                            <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Mobile" name="mobile"  required="required">
                        </div><!-- /form-group -->

                        <div class="form-group">
                            <label for="exampleInputEmail1">Email Address :</label>
                            <input type="email" class="form-control input-sm" id="exampleInputEmail1" placeholder="Email Address" name="email"  required="required">
                        </div><!-- /form-group -->



                        <div class="form-group">
                            <label for="exampleInputEmail1">Password:</label>
                            <input type="password" class="form-control input-sm" id="exampleInputEmail1" placeholder="Password" name="password"  required="required">
                        </div><!-- /form-group -->


                        <div class="form-group">
                            <label for="exampleInputEmail1">Password Again:</label>
                            <input type="password" class="form-control input-sm" id="exampleInputEmail1" placeholder="Password Again" name="repassword"  required="required">
                        </div><!-- /form-group -->

                        <div class="form-group">
                            <label for="exampleInputEmail1">Account Type:</label>
                            <select name="accounttype" class="form-control input-sm" id="exampleInputEmail1" >
                                <option value="reseller">Reseller</option>
                                <option value="user">Normal User</option>
                            </select>
                        </div><!-- /form-group -->





                    </div>
                </div>




                <!---->




                <div class="form-group text-right">
                    <button href="#" class="btn btn-success"><i class="fa fa-plus"></i> Create User Account</button>

                </div>
                <?php echo form_close();?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>



<?php
foreach($rst as $rows)
{

    ?>

    <?php echo form_open('')?>

    <div class="modal fade" id="formModal<?php echo $rows->id?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4>Update Account information</h4>
                </div>
                <div class="modal-body">


<input type="hidden" name="id" value="<?php echo $rows->id?>">

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





<div class="modal fade" id="formModal_search">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4>Search for SMS Account</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('')?>

                <!---->

                <div class="panel panel-default">

                    <div class="panel-body">


                        <div class="form-group">
                            <label for="exampleInputEmail1">Email Address :</label>
                            <input type="email" class="form-control input-sm" id="exampleInputEmail1" placeholder="Email Address" name="email"  required="required">
                        </div><!-- /form-group -->



                    </div>
                </div>




                <!---->




                <div class="form-group text-right">
                    <button  name="search_account" class="btn btn-success"><i class="fa fa-search"></i> Search for account</button>

                </div>
                <?php echo form_close();?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
