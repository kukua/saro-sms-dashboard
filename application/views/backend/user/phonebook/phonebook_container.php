<?php $this->load->view('Layout/backend/header', []); 

$contact_group_logs = $this->db
                ->select('*')
                ->from('groups')
                ->where('created_by = "'.$this->session->userdata('id').'"')
                ->order_by('created_at','DESC')
                ->get()
                ->result();

?>
<div class="panel panel-default">
    <div class="panel-heading">

    </div>
<?php  $this->load->view('backend/user/phonebook/menu');?>


<!-- SENT MESSAGE LOGS !-->
<script>
    init.push(function () {
        $('#jq_email_log').dataTable();
        $('#jq_email_log_wrapper .table-caption').text('Phone Group');
        $('#jq_email_log_wrapper .dataTables_filter input').attr('placeholder', 'Search...');
    });
</script>
<div class="table-light">
    <div role="grid" id="jq_email_log_wrapper" class="dataTables_wrapper form-inline no-footer">

        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="jq_email_log" aria-describedby="jq-datatables-example_info">
               <thead>
                <tr role="row">
                    <th class="sorting_asc" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column ascending" style="width: 2%;">
                        #
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                        Created
                    </th>                    
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 60%">
                        Group Name
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 20%;">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 1;
                foreach ($contact_group_logs as $contact_group):
                    ?>
                    <tr>
                        <td><?php echo $count++ ?></td>
                        <td><?php
                            echo (new Cake\I18n\Time($contact_group->created_at))->timeAgoInWords();
                            ?>
                        </td>                        
                        <td><?php echo anchor('user_phonebook/contacts/'.$contact_group->id*date('Y'),$contact_group->group_name);?></td>
                        <td>
                            <a  href="#formModal<?php echo $contact_group->id?>"  data-toggle="modal"> <i class="fa fa-edit fa-lg"></i></a>&nbsp;
                           <?php echo anchor('user_phonebook/delete_group/'.$contact_group->id*date('Y'),'<i class="fa fa-trash-o fa-lg"></i>',array('onclick'=>"return confirm('You are about to delete this group name')"));?>
                        </td>

                        

                            <div class="modal fade" id="formModal<?php echo $contact_group->id?>">
                            <?php echo form_open('user_phonebook/group_name_edit')?>
                            <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4>Update</h4>
                                </div>
                                <div class="modal-body">

                                    <input type="hidden" name="id" value="<?php echo $contact_group->id?>">

                                    <!---->

                                    <div class="panel panel-default">

                                        <div class="panel-body">

                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Group name:</label>
                                                <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Group list" value="<?php echo $contact_group->group_name?>" name="group_name_edit"  required="required">
                                            </div><!-- /form-group -->

                                        </div>
                                    </div>

                                    <div class="form-group text-right">
                                        <button href="#" class="btn btn-success"><i class="fa fa-save"></i> Save</button>

                                    </div>

                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                        <?php echo form_close();?>
                            </div>
                        
                    </tr>
                    <?php
                endforeach;
                ?>
                    
            </tbody>
        </table>

    </div>
</div>
</div>

<?php $this->load->view('Layout/backend/footer', []); ?>    