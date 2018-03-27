<?php $this->load->view('Layout/backend/header', []); ?>

<?php  $this->load->view('backend/admin/emails/menu');?>

<?php echo link_tag('assets/backend/mosaico/mosaico-material.css'); ?>
<?php echo link_tag('assets/backend/mosaico/vendor/notoregular/stylesheet.css'); ?>

<script type="text/javascript" src="<?= base_url() ?>assets/backend/mosaico/vendor/knockout.js"></script>

<script>
    var datatable;
    
    var initialEdits = [];

        var md;
    var td;
    var postUrl = '<?=base_url()?>admin_emails/tl';
    var post = $.post(postUrl, {
        action: 'getall'
    }, null, 'html').success(function () {
        md = JSON.parse(arguments[0]);
        
        for (var i = 0; i < md.length; i++) {
            
            if (typeof md[i].metadata == 'string') {
                initialEdits.push(JSON.parse(md[i].metadata));
            } else {
                //console.log("Ignoring saved key", editKeys[i], "type", typeof md, md);
            }
        }

        initialEdits.sort(function (a, b) {
            var lastA = a.changed ? a.changed : a.created;
            var lastB = b.changed ? b.changed : b.created;
            if (lastA < lastB)
                return 1;
            if (lastA > lastB)
                return -1;
            return 0;
        });
    });

   

    var viewModel = {
        showSaved: ko.observable(false),
        edits: ko.observableArray(initialEdits),
        templates: [{
                name: 'versafix-1', desc: 'The versatile template'
            }, {
                name: 'tedc15', desc: 'The TEDC15 template'
            }]
    };

    viewModel.edits.subscribe(function (newEdits) {
        var keys = [];
        for (var i = 0; i < newEdits.length; i++) {
            keys.push(newEdits[i].key);
            localStorage.setItem('metadata-' + newEdits[i].key, ko.toJSON(newEdits[i]));
        }
        localStorage.setItem('edits', ko.toJSON(keys));
    });

    viewModel.dateFormat = function (unixdate) {
        if (typeof unixdate == 'undefined')
            return 'DD-MM-YYYY';
        var d = new Date();
        d.setTime(ko.utils.unwrapObservable(unixdate));
        var m = "" + (d.getMonth() + 1);
        var h = "" + (d.getHours());
        var i = "" + (d.getMinutes());
        return d.getDate() + "/" + (m.length == 1 ? '0' : '') + m + "/" + d.getFullYear() + " " + (h.length == 1 ? '0' : '') + h + ":" + (i.length == 1 ? '0' : '') + i;
    };

    viewModel.newEdit = function (shorttmplname) {
        console.log("new", this, template);
        var d = new Date();
        var rnd = Math.random().toString(36).substr(2, 7);
        var template = 'http://kukua.cc/assets/backend/mosaico/templates/' + shorttmplname + '/template-' + shorttmplname + '.html';
        viewModel.edits.unshift({created: Date.now(), key: rnd, name: shorttmplname, template: template});
        document.location = 'editor#' + rnd+'#new';
        // { data: 'AAAA-MM-GG', key: 'ABCDE' }
        // viewModel.edits.push(template);
    };
    viewModel.renameEdit = function (index) {
        var newName = window.prompt("Modifica nome", viewModel.edits()[index].name);
        if (newName) {
            var newItem = JSON.parse(ko.toJSON(viewModel.edits()[index]));
            newItem.name = newName;
            viewModel.edits.splice(index, 1, newItem);
            var postUrl = '<?=base_url()?>admin_emails/update_template/';
            var post = $.post(postUrl, {
              action: 'save',
               key: viewModel.edits()[index].key,
               name: viewModel.edits()[index].name
            }, null, 'html');
            post.fail(function() {
                
            });
            post.success(function() {
              document.location = '<?=  base_url()?>admin_emails/templates';
             
            });
        }
        return false;
    };
    viewModel.deleteEdit = function (index) {
        var confirm = window.confirm("Are you sure you want to delete this content?");
        if (confirm) {
            var res = viewModel.edits.splice(index, 1);
            console.log("removing template ", res);
            localStorage.removeItem('template-' + res[0].key);
            document.location = '<?=  base_url()?>admin_emails/templates';
            
        }
        return false;
    };
    viewModel.list = function (clean) {
        for (var i = localStorage.length - 1; i >= 0; i--) {
            var key = localStorage.key(i);
            if (clean) {
                console.log("removing ", key, localStorage.getItem(key));
                localStorage.removeItem(key);
            } else {
                console.log("ls ", key, localStorage.getItem(key));
            }
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        ko.applyBindings(viewModel);
    });
    
</script>
<style>

    .template {
        margin: 10px;
        display: inline-block;
        vertical-align: top; 
    }
    .template a {
        display: block;
        outline: 2px solid #333332;
        padding: 2px;
        width: 340px;
        height: 500px;
        overflow-y: auto;
    }
    .template a:hover {
        outline: 5px solid #38b24a;
        transition: outline .2s;
    }

    .ribbon {
        background-color: #900000;
        color: white;
        display: inline-block;
        padding: 3px 10px;
        margin: 6px;
        position: relative;
        z-index: 10;
        outline: 1px solid #600000;
    }
    /* outline su firefox viene fuori dal content */
    @-moz-document url-prefix() { 
        .ribbon {
            outline-color: transparent;
        }
    }
    .ribbon:before, .ribbon:after {
        z-index: -4;
        content: ' ';
        position: absolute;
        width: 5px;
        top: 7px;
        height: 0;
        border-width: 12px 12px;
        border-style: solid;
        border-color: #900000;
    }
    .ribbon:before {
        left: -20px;
        border-left-color: transparent;
    }
    .ribbon:after {
        right: -20px;
        border-right-color: transparent;
    }

</style>
<body style="overflow: auto;" data-bind="visible: true">

    <div style="text-align: center;" >
        <!-- ko ifnot: $root.showSaved --><!-- /ko -->

        <script>
     
            init.push(function () {
                $('#savedTable').dataTable();
                $('#savedTable_wrapper .table-caption').text('Email Templates');
                $('#savedTable_wrapper .dataTables_filter input').attr('placeholder', 'Search...');
            });
        </script>
        <div class="table-light">
            <div role="grid" id="savedTable_wrapper" class="dataTables_wrapper form-inline no-footer">

                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="savedTable" aria-describedby="jq-datatables-example_info">
                    <thead>
                        <tr role="row">
                            <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                                ID
                            </th>                    
                            <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 45%">
                                Name
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 20%;">
                                Created
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="jq-datatables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 10%;">
                                Action
                            </th>
                    </thead>
                    <?php $templates = $this->db->select('*')->from('email_template')->where(array('user_id' => $this->session->userdata('id')))->order_by('created_at','DESC')->get()->result();?>
                    <tbody>
                        <?php 
                        $count=1;
                        foreach ($templates as $template) {?>
                        <tr>
                            <td align="left"><?php echo anchor('admin_emails/editor#'.($template->templateid),'<code>'.$template->templateid.'</code>');?></td>
                            <td style="font-weight: bold" align="left"><?php echo anchor('admin_emails/editor#'.($template->templateid),'<code>'.$template->name.'</code>');?></td>
                            <td><?php
                            echo (new Cake\I18n\Time($template->created_at))->timeAgoInWords();
                            ?></td>
                            <td>
                                <a href="#etformModal<?php echo $template->id?>"  data-toggle="modal"><i class="fa fa-edit a-lg"></i></a>
                                <?php echo anchor('admin_emails/delete_template/'.$template->id*date('Y'),'<i class="fa fa-trash-o fa-lg"></i>',array('onclick'=>"return confirm('You are about to delete this Email list')"));?>
                            </td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>

            </div>
        </div>
        <div class="content" style="background-origin: border; padding-bottom: 2em">
            <h3>Choose any of these template: more to come soon, stay tuned!</h3>
            <div data-bind="foreach: templates">
                <div class="template template-xx" style="" data-bind="attr: { class: 'template template-'+name }">
                    <div class="description" style="padding-bottom:5px"><b data-bind="text: name">xx</b>: <span data-bind="text: desc">xx</span></div>
                    <a href="#" data-bind="click: $root.newEdit.bind(undefined, name), attr: { href: 'http://kukua.cc/assets/backend/mosaico/templates/'+name+'/template-'+name+'.html' }">

                        <img src width="100%" alt="xx" data-bind="attr: { src:'http://kukua.cc/assets/backend/mosaico/templates/'+name+'/edres/_full.png' }">
                    </a>
                </div>
            </div>
        </div>
    </div>
    
<?php
foreach($templates as $template)
{

    ?>

    <?php echo form_open('admin_emails/update_template')?>

    <div class="modal fade" id="etformModal<?php echo $template->id?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4>Update</h4>
                </div>
                <div class="modal-body">
      
                    <input type="hidden" name="id" value="<?php echo $template->id * date('Y');?>">

                    <div class="panel panel-default">

                        <div class="panel-body">

                            <div class="form-group">
                                <label for="exampleInputEmail1">Template Name</label>
                                <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Name" value="<?php echo $template->name?>" name="name"  required="required">
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
</body>
<?php $this->load->view('Layout/backend/footer', []); ?>    
