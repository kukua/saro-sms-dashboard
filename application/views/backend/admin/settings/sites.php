<?php $this->load->view('Layout/backend/header', []); 

$sites = $this->db
                ->from('sites')
                ->order_by('id DESC')
                ->get()->result();

?>
<div class="panel panel-default">
    <div class="panel-heading">
        
    </div>
<?php  $this->load->view('backend/admin/settings/sites-menu');?>
<!-- SENT MESSAGE LOGS !-->
<script>
    init.push(function () {
        $('#jq_sites_log').dataTable();
        $('#jq_sites_log_wrapper .table-caption').text('Site Costs');
        $('#jq_sites_log_wrapper .dataTables_filter input').attr('placeholder', 'Search...');
    });
</script>
<div class="table-light">
    <div role="grid" id="jq_sites_log_wrapper" class="dataTables_wrapper form-inline no-footer">

        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="jq_sites_log" aria-describedby="jq-datatables-example_info">
            <thead>
                <tr role="row">
                    <th class="sorting_asc" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column ascending" style="width: 2%;">
                        #
                    </th>
                               
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%">
                       Name
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%">
                       Domain
                    </th>
                     <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 8%">
                       Text
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 8%">
                       Leaderboard
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 8%">
                      Skyscrapper
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 8%">
                       Banner
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 8%">
                       MPU
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 8%">
                       5 secs Video
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 8%">
                      15 secs Video
                    </th>
                     <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 8%">
                       30 secs Video
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 8%">
                      60 secs Video
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 8%;">
                        Action
                    </th>
            </thead>
            <tbody>
                <?php
                $count = 1;
                foreach ($sites as $site):
                    ?>
                    <tr>
                        <td><?php echo $count++ ?></td>                       
                     <td><?php echo $site->name;?></td>
                    <td><?php echo $site->domain;?></td>
                    <td><?php echo number_format($site->text);?></td>
                    <td><?php echo number_format($site->leaderboard);?></td>
                    <td><?php echo number_format($site->skyscrapper);?></td>
                    <td><?php echo number_format($site->banner);?></td>
                    <td><?php echo number_format($site->mpu);?></td>
                    <td><?php echo number_format($site->fivesecs);?></td>
                    <td><?php echo number_format($site->fifteensecs);?></td>
                    <td><?php echo number_format($site->thirtysecs);?></td>
                    <td><?php echo number_format($site->sixtysecs);?></td>

                    <td>
                        <a  href="#newsite<?php echo $site->id?>"  data-toggle="modal"> <i class="fa fa-edit fa-lg"></i></a>&nbsp;
                       <?php echo anchor('admin_system_settings/delete_site/'.$site->id*date('Y'),'<i class="fa fa-trash-o fa-lg"></i>',array('onclick'=>"return confirm('You are about to delete this site')"));?>
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

foreach($sites as $rows)
{
?>


<div class="modal fade" id="newsite<?php echo $rows->id?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5>Edit Sites</h5>
            </div>
            <?php echo form_open('admin_system_settings/site_edit'); ?>


            <div class="modal-body">

                <input type="hidden" id="id" name="id" value="<?php echo $rows->id *date('Y'); ?>">


                <div class="form-group">
                        <label for="folderName">Site Name</label>
                        <input type="text" class="form-control input-sm" id="name" name="name" value="<?php echo $rows->name?>" placeholder="Name" required="required">
                    </div>
                     <div class="form-group">
                        <label for="folderName">Domain</label>
                        <input type="text" class="form-control input-sm" id="domain" name="domain" value="<?php echo $rows->domain?>" placeholder="Domain" required="required">
                    </div>
                     <div class="form-group">
                        <label for="folderName">Text</label>
                        <input type="text" class="form-control input-sm" id="domain" name="text" value="<?php echo $rows->text?>" placeholder="Text" required="required">
                    </div>
                     <div class="form-group">
                        <label for="folderName">Leaderboard</label>
                        <input type="text" class="form-control input-sm" id="leaderboard" name="leaderboard" value="<?php echo $rows->leaderboard?>" placeholder="leaderboard" required="required">
                    </div>
                     <div class="form-group">
                        <label for="folderName">Sky scrapper</label>
                        <input type="text" class="form-control input-sm" id="skyscrapper" name="skyscrapper" value="<?php echo $rows->skyscrapper?>" placeholder="skyscrapper" required="required">
                    </div>
                     <div class="form-group">
                        <label for="folderName">banner</label>
                        <input type="text" class="form-control input-sm" id="banner" name="banner" value="<?php echo $rows->banner?>" placeholder="banner" required="required">
                    </div>
                     <div class="form-group">
                        <label for="folderName">mpu</label>
                        <input type="text" class="form-control input-sm" id="mpu" name="mpu" value="<?php echo $rows->mpu?>" placeholder="mpu" required="required">
                    </div>
                     <div class="form-group">
                        <label for="folderName">fivesecs</label>
                        <input type="text" class="form-control input-sm" id="fivesecs" name="fivesecs" value="<?php echo $rows->fivesecs?>" placeholder="fivesecs" required="required">
                    </div>
                     <div class="form-group">
                        <label for="folderName">fifteensecs</label>
                        <input type="text" class="form-control input-sm" id="fifteensecs" name="fifteensecs" value="<?php echo $rows->fifteensecs?>" placeholder="fifteensecs" required="required">
                    </div>
                     <div class="form-group">
                        <label for="folderName">thirtysecs</label>
                        <input type="text" class="form-control input-sm" id="thirtysecs" name="thirtysecs" value="<?php echo $rows->thirtysecs?>" placeholder="thirtysecs" required="required">
                    </div>
                     <div class="form-group">
                        <label for="folderName">sixtysecs</label>
                        <input type="text" class="form-control input-sm" id="sixtysecs" name="sixtysecs" value="<?php echo $rows->sixtysecs?>" placeholder="sixtysecs" required="required">
                    </div>



            </div>
            <div class="modal-footer">

                <button  class="btn btn-success btn-sm"><i class="fa fa-edit"></i> Save changes</button>
            </div>
            <?php echo form_close();?>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php
}

    ?>
</div>
<?php $this->load->view('Layout/backend/footer', []); ?>