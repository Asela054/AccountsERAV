<?php 
include "include/header.php";  
include "include/topnavbar.php"; 
?>
<div id="layoutSidenav">
	<div id="layoutSidenav_nav">
		<?php include "include/menubar.php"; ?>
	</div>
	<div id="layoutSidenav_content">
		<main>
			<div class="page-header page-header-light bg-white shadow">
				<div class="container-fluid">
					<div class="page-header-content py-3">
						<h1 class="page-header-title font-weight-light">
							<div class="page-header-icon"><i class="fas fa-desktop"></i></div>
							<span>Settle Payment Invoices</span>
                            <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right dropdown-toggle" id="btnorderacts" style="position:absolute; right:10px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-plus"></i>&nbsp;Actions</button>
                            <div class="dropdown-menu" aria-labelledby="btnorderacts">
                            	<a class="dropdown-item" id="btnProviderFind" data-toggle="modal" data-target="#providersListModal" href="javascript:void(0);" title="Select a provider/supplier to release the payment">Find Provider/Supplier</a>
                                <a class="dropdown-item" id="btnOrderFind" data-toggle="modal" data-target="#assignmentListModal" href="javascript:void(0);" title="Review invoice and cheque details of a payment">Find Payments to be Settle</a>
                                <a class="dropdown-item" id="btnOrderApprovePost" data-refid="-1" href="javascript:void(0);">Approve Payment</a>
                                <a class="dropdown-item" id="btnAllocCheque" data-refid="-1" href="javascript:void(0);">Allocate Cheque</a>
                                <a class="dropdown-item" id="btnPostCheque" data-refid="-1" href="javascript:void(0);">Post Cheque/Payment</a>
                                <a class="dropdown-item" id="btnOrderDelete" data-refid="-1" href="javascript:void(0);">Delete</a>
                            </div>
						</h1>
					</div>
				</div>
			</div>
            
            <div class="container-fluid mt-2 p-0 p-2">
				<form id="" method="post">
                    <div class="card rounded-0">
                        <div class="card-body p-0 p-2">
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">Company</label>
                                    <select class="form-control form-control-sm" disabled="disabled">
                                        <option>ABC Co.</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Branch</label>
                                    <select class="form-control form-control-sm" disabled="disabled">
                                        <option>Colombo</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Account Period</label>
                                    <input type="text" readonly="readonly" class="form-control form-control-sm" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="">Provider/Supplier</label>
                                    <select class="form-control form-control-sm" disabled="disabled">
                                        <option>DEF Co.</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="">Total value to be released</label>
                                    <input type="text" class="form-control form-control-sm" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="">Remarks</label>
                                    <input type="text" class="form-control form-control-sm" value="" />
                                </div>
                                <div class="col-md-4">
                                    <label for="">Payment Date</label>
                                    <input type="date" class="form-control form-control-sm" value="" />
                                </div>
                            </div>
                            
                            <div class="form-group"><hr /></div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">Invoice #</label>
                                    <select class="form-control form-control-sm">
                                        <option>Supplier invoice 1</option>
                                        <option>Supplier invoice 2</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="">Narration</label>
                                    <input type="text" class="form-control form-control-sm" value="" />
                                </div>
                                <div class="col-md-2">
                                    <label for="">Value</label>
                                    <input type="text" class="form-control form-control-sm" value="" />
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between" align="right">
                        	<div class="small" style="font-weight:bold;">
                            	notify msgs, header-detail value mismatch etc.
                            </div>
                            <div class="small text-muted">
                        		<button type="submit" name="submit" class="btn btn-primary">Add/Save</button>
                            </div>
                        </div>
                    </div>
                </form>
                
                <div class="card rounded-0">
                	<div class="card-body">
                    	<div class="table-responsive">
                            <table class="table table-bordered table-sm" id="iteminfo_dataTable" width="100%" border="0" cellspacing="0">
                              <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Invoice #</th>
                                    
                                    <th>Narration</th>
                                    <th>Value</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                    <td><input type="checkbox" class="" checked="checked" value="" /></td>
                                    <td>Invoice 1</td>
                                    
                                    <td>Comment 1</td>
                                    <td>1000.00</td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                    </div>
                </div>
			</div>
            
            <!-- find providers -->
            <div class="modal fade" id="providersListModal" tabindex="-1" role="dialog" aria-labelledby="providersListModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl" role="document">
                <form id="frmProviderRegList" name="frmProviderRegList" method="post" action="">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="providersListModalLabel"><span>Choose a Provider/Supplier</span></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="row">
                        <div class="col-md-4">
                        table filter options<br />
                        company<br />
                        branch
                        </div>
                        <div class="col-md-8">
                        <div class="datatable table-responsive">
                            <table class="table table-bordered table-hover dataTable no-footer" id="providers_dataTable" width="100%" cellspacing="0">
                              <thead>
                                <tr>
                                  <th width="90%">Provider/Supplier</th>
                                  <th width="10%">Actions</th>
                                </tr>
                              </thead>
                              
                              
                            </table>
                        </div>
                        </div>
                        </div>
                      </div>
                      
                      
                    </div>
                </form>
              </div>
            </div>
            <!-- find providers -->
            
            <!-- find records -->
            <div class="modal fade" id="assignmentListModal" tabindex="-1" role="dialog" aria-labelledby="assignmentListModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl" role="document">
                <form id="frmShipAddr" name="frmShipAddr" method="post" action="">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="assignmentListModalLabel"><span>Choose a Provider/Supplier</span></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="datatable table-responsive">
                            <table class="table table-bordered table-hover dataTable no-footer" id="assignment_dataTable" width="100%" cellspacing="0">
                              <thead>
                                <tr>
                                  <th width="30%">Provider/Supplier</th>
                                  <th width="10%">Bank Account</th>
                                  <th width="40%">Remarks</th>
                                  <th>Total Payment</th>
                                  <th width="5%">Actions</th>
                                </tr>
                              </thead>
                              
                              
                            </table>
                        </div>
                      </div>
                      
                      
                    </div>
                </form>
              </div>
            </div>
            <!-- find records -->
            
		</main>
		<?php include "include/footerbar.php"; ?>
	</div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
$(document).ready(function(){
	$("#frmTest").submit(function(event){
		event.preventDefault();
		$.ajax({
			method: "POST",
			url: "<?php echo base_url('AccountPayment/store'); ?>",
		}).done(function(data){
			alert("Batch number is "+data);
		});
	});
});
</script>
<?php include "include/footer.php"; ?>
