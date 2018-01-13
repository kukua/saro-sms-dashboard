<?php $this->load->view('Layout/backend/header', []); 

echo form_open('');
?>

<div class="padding-md">
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">Spectrum SMS reports</div>
            <div class="panel-body">
                <form>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Report Type</label>


                        <select class="form-control input-sm" id="exampleInputEmail1" name="Report_Type">
                            <option value="sentsms">Sent Messages</option>
                            <option value="payments">Payments</option>
                            <option value="useraccounts">User Accounts</option>

                        </select>


                    </div><!-- /form-group -->
                    <div class="form-group">
                        <label for="exampleInputPassword1">From </label>
                        <input type="text" class="datepicker form-control" name="from" id="demo4" placeholder="From date" >
                    </div><!-- /form-group -->




                    <div class="form-group">
                        <label for="exampleInputPassword1">To </label>
                        <input type="text" class="form-control input-sm" name="to" id="demo3" onClick="javascript:NewCal('demo3','ddmmmyyyy',false,24)" placeholder="To date" >
                    </div><!-- /form-group -->

                    <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-download"></i> Download</button>
                </form>
            </div>
        </div><!-- /panel -->
    </div><!-- /.col -->

</div><!-- /.row -->



</div><!-- /.padding-md -->


<?php $this->load->view('Layout/backend/footer', []); ?> 
