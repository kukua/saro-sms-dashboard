<?php $this->load->view('Layout/backend/header', []); 

$email_list_logs = $this->db
                ->select('*')
                ->from('email_list')
                ->where('user_id = "'.$this->session->userdata('id').'"')
                ->order_by('created_at DESC')
                ->get()
                ->result();

        #print_r($this->session->userdata('id')==null);

?>
<div class="panel panel-default">
    <div class="panel-heading">

    </div>
<?php  $this->load->view('backend/admin/emails/menu');
?>
<script>
    init.push(function () {
        $('#email_list_log').dataTable();
        $('#email_list_log_wrapper .table-caption').text('Email Lists');
        $('#email_list_log_wrapper .dataTables_filter input').attr('placeholder', 'Search...');
    });
</script>
<div class="table-light">
    <div role="grid" id="email_list_log_wrapper" class="dataTables_wrapper form-inline no-footer">

        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="email_list_log" aria-describedby="jq-datatables-example_info">
            <thead>
                <tr role="row">
                    <th class="sorting_asc" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column ascending" style="width: 3%;">
                        #
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                        Created
                    </th>                    
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 50%">
                        Email List
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 8%;">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 1;
                foreach ($email_list_logs as $email_list):
                    ?>
                    <tr>
                        <td><?php echo $count++ ?></td>
                        <td><?php
                            echo (new Cake\I18n\Time($email_list->created_at))->timeAgoInWords();
                            ?>
                        </td>
                        <td><?php echo anchor('admin_emails/email_listing/'.$email_list->id*date('Y'),$email_list->list_name);?></td>
                        <td>
                          <a href="#formModal<?php echo $email_list->id?>"  data-toggle="modal"><i class="fa fa-edit a-lg"></i></a>
                          <?php echo anchor('admin_emails/delete_email_list/'.$email_list->id*date('Y'),'<i class="fa fa-trash-o fa-lg"></i>',array('onclick'=>"return confirm('You are about to delete this Email list')"));?>
                        </td>


                    </tr>
                    <?php
                endforeach;
                ?>

            </tbody>
        </table>

    </div>
</div>

</div><!-- /panel -->
<!---------->

<?php
foreach($email_list_logs as $rows)
{

    ?>

    <?php echo form_open('admin_emails/emaillist_edit')?>

    <div class="modal fade" id="formModal<?php echo $rows->id?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4>Update</h4>
                </div>
                <div class="modal-body">
      
                <input type="hidden" name="id" value="<?php echo $rows->id?>">

                    <div class="panel panel-default">

                        <div class="panel-body">

                            <div class="form-group">
                                <label for="exampleInputEmail1">Email List:</label>
                                <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Email list" value="<?php echo $rows->list_name?>" name="emaillist_edit"  required="required">
                            </div><!-- /form-group -->

                        </div>
                    </div>

                    <div class="form-group text-right">
                        <button href="#" class="btn btn-success"><i class="fa fa-save"></i> Save</button>

                    </div>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->

    </div>
    <?php echo form_close();?>
<?php

}
?>

<?php $this->load->view('Layout/backend/footer', []); ?> 




