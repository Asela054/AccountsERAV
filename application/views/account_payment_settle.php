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
                            <div class="dropdown-menu m-0" aria-labelledby="btnorderacts">
                            	<a class="dropdown-item" id="btnProviderFind" data-toggle="modal" data-target="#providersListModal" href="javascript:void(0);" title="Select a provider/supplier to release the payment">Find Provider/Supplier</a>
                                <a class="dropdown-item" id="btnOrderFind" data-toggle="modal" data-target="#assignmentListModal" href="javascript:void(0);" title="Review invoice and cheque details of a payment">Find Payments to be Settle</a>
                                <a class="nav-item"><hr class="border-top"></a>
                                <a class="dropdown-item" id="btnOrderApprovePost" data-refid="-1" href="javascript:void(0);">Approve Payment</a>
                                <a class="dropdown-item" id="btnIssueCheque" data-refid="-1" href="javascript:void(0);">Issue Cheque(s)</a>
                                <a class="dropdown-item" id="btnPostCheque" data-refid="-1" href="javascript:void(0);">Post Cheque/Payment</a>
                                <!--a class="dropdown-item" id="btnOrderDelete" data-refid="-1" href="javascript:void(0);">Delete</a-->
                            </div>
                            
                            
						</h1>
					</div>
				</div>
			</div>
            
            <div class="container-fluid mt-2 p-0 p-2">
            <div class="card">
            <div class="card-body p-0 p-2" id="div_enc" style="height:600px; padding:0 !Important;"><!--  -->
            <div class="">
				<form id="frmPaymentInfo" method="post">
                    <div class="card rounded-0">
                        <div class="card-body p-0 p-2">
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">Company</label>
                                    <!--select class="form-control form-control-sm" disabled="disabled">
                                        <option>ABC Co.</option>
                                    </select-->
                                    <?php 
									$companyAttr=array('id'=>'drp_company_list', 
										 'class'=>'form-control form-control-sm', 
										 'required'=>'required',
										 'disabled'=>'disabled');
									//echo form_dropdown('drp_company_list', $company_list, '-1', $companyAttr); 
									echo form_dropdown('drp_company_list', $company_list, $user_org_id, $companyAttr);
									?>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Branch</label>
                                    <!--select class="form-control form-control-sm" disabled="disabled">
                                        <option>Colombo</option>
                                    </select-->
                                    <?php 
									$companyBranchAttr=array('id'=>'drp_company_branch_list', 
										 'class'=>'form-control form-control-sm', 
										 'required'=>'required',
										 'disabled'=>'disabled');
									//echo form_dropdown('drp_company_branch_list', $company_branch_list, '-1', $companyBranchAttr); 
									echo form_dropdown('drp_company_branch_list', $company_branch_list, $user_branch_id, $companyBranchAttr);
									?>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Account Period</label>
                                    <input type="text" readonly="readonly" id="txt_acc_period" class="form-control form-control-sm" value="<?php echo $account_period_txt; ?>" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="">Provider/Supplier</label>
                                    <!--select class="form-control form-control-sm" disabled="disabled">
                                        <option>DEF Co.</option>
                                    </select-->
                                    <?php 
									$supplierAttr=array('id'=>'drp_supplier_list', 
										 'class'=>'form-control form-control-sm', 
										 'required'=>'required',
										 'disabled'=>'disabled');
									echo form_dropdown('drp_supplier_list', $supplier_list, '-1', $supplierAttr); 
									?>
                                </div>
                                <!--div class="col-md-4">
                                    <label for="">Supplier Invoice #</label>
                                    <input type="number" id="txt_supp_invoice_num" class="form-control form-control-sm" value="" />
                                </div-->
                                <div class="col-md-2">
                                    <label for="">Total Payment</label>
                                    <input type="text" id="txt_supp_invoice_total" class="form-control form-control-sm running_tot_figures" value="0" title="Total value to be released" />
                                </div>
                                <div class="col-md-2">
                                    <label for="">Shortage</label>
                                    <input type="text" id="txt_supp_invoice_shortage_amount" class="form-control form-control-sm" value="0" readonly="readonly" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="">Remarks</label>
                                    <input type="text" id="txt_supp_invoice_remarks" class="form-control form-control-sm" value="" />
                                </div>
                                <div class="col-md-4">
                                    <label for="">Payment Date</label>
                                    <input type="date" id="txt_payment_issue_date" class="form-control form-control-sm" value="" />
                                </div>
                            </div>
                            
                            <div class="form-group"><hr /></div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">Invoice #</label>
                                    <!--select class="form-control form-control-sm">
                                        <option>Supplier invoice 1</option>
                                        <option>Supplier invoice 2</option>
                                    </select-->
                                    <?php 
									$childAccountAttr=array('id'=>'drp_supplier_invoices_list', 
										 'class'=>'form-control form-control-sm running_tot_figures', 
										 'required'=>'required');
									echo form_dropdown('drp_supplier_invoices_list', $supplier_invoices, '-1', $childAccountAttr); 
									?>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="">Narration</label>
                                    <input type="text" id="txt_invoice_detail_narration" class="form-control form-control-sm" value="" />
                                </div>
                                <div class="col-md-2">
                                    <label for="">Value:</label>
                                    <label for="" style="float:right;" title="Select for advance payments"><input type="checkbox" class="" value="" disabled="disabled" style="margin-right:3px; width:18px; height:18px; position:relative; top:3px;" />In Advance</label>
                                    <input type="text" id="txt_invoice_detail_amount" class="form-control form-control-sm running_tot_figures" value="" />
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between" align="right">
                        	<div class="small" style="font-weight:bold;">
                            	<!--notify msgs, header-detail value mismatch etc.-->
                                <span id="lbl_warnmsg" style="padding-right:20px; font-weight:bold;">&nbsp;</span>
                            </div>
                            <div class="small text-muted">
                            	<input type="hidden" id="h_acc_period" value="<?php echo $account_period_id; ?>" />
                                <input type="hidden" id="h_invoice_detail_running_total" value="" />
                                <input type="hidden" id="h_paysettle_main_id" value="" />
                        		<button type="submit" name="submit" class="btn btn-primary">Add<!-- Save --></button>
                            </div>
                        </div>
                    </div>
                </form>
                
                <div class="card rounded-0">
                	<div class="card-body">
                    	<div class="table-responsive">
                            <table class="table table-bordered table-sm" id="invoiceDetail_dataTable" width="100%" border="0" cellspacing="0">
                              <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Invoice #</th>
                                    <th>Narration</th>
                                    <th>Value</th>
                                </tr>
                              </thead>
                              <!--tbody>
                                <tr>
                                    <td><input type="checkbox" class="" checked="checked" value="" /></td>
                                    <td>Invoice 1</td>
                                    
                                    <td>Comment 1</td>
                                    <td>1000.00</td>
                                </tr>
                              </tbody-->
                            </table>
                          </div>
                    </div>
                </div>
			</div>
            
            
            <div class="table-responsive">
            	<section class="main">
                	<div class="">
                    	<div id="custom-inner" class="custom-inner">
                        	<!--div class="custom-header clearfix">
                            	<h2>Cut Job</h2>
                            </div-->
                            <div id="event_info" class="custom-content-reveal" style="background:rgba(246, 246, 246);">
                            	<h4 style="padding:12px; text-align:left; margin-bottom:0;">Cheque Details</h4>
                                
                                
                                <form id="frmChequeInfo" method="post">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label>Issued to</label>
                                                    <input type="text" class="form-control form-control-sm" id="txt_issued_to" value="" readonly="readonly" />
                                                </div>
                                                <div class="col-md-8">
                                                	<label>Narration</label>
                                                            <input type="text" class="form-control form-control-sm" id="txt_cheque_issue_narration" value="" />
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label for="">Bank</label>
                                                    <select class="form-control form-control-sm nest_head" id="drp_company_bank_list" data-findnest="banksnest">
                                                        <option value="-1">Select</option>
                                                        <?php 
                                                        foreach($company_bank_list as $pf){
                                                        ?>
                                                        <option value="<?php echo $pf->idtbl_bank; ?>"><?php echo $pf->bankname; ?></option>
                                                        <?php 	
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="">Account</label>
                                                    <select class="form-control form-control-sm" id="drp_company_bank_branch_list" data-nestname="banksnest">
                                                        <option value="-1">Select</option>
                                                        <?php 
                                                        foreach($company_bank_account_list as $qf){
                                                        ?>
                                                        <option class="nestopt d-none" value="<?php echo $qf->idtbl_bank_branch; ?>" data-nestcode="<?php echo $qf->tbl_bank_idtbl_bank; ?>" data-accnum="<?php echo $qf->idtbl_account; ?>" disabled="disabled"><?php echo $qf->accountname; ?></option>
                                                        <?php 	
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Cheque Date</label>
                                                    <input type="date" class="form-control form-control-sm" id="txt_cheque_issue_date" value="" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Payment</label>
                                                    <input type="text" class="form-control form-control-sm" id="txt_cheque_value" value="" />
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="card-footer d-flex align-items-center justify-content-between" align="right">
                                            <div class="small" style="font-weight:bold;">
                                            	<!--notify msgs, header-detail value mismatch etc.-->
                                				<span id="lbl_eventmsg" style="padding:0px; font-size:14px;">&nbsp;</span>
                                            </div>
                                            <div class="small d-flex" style="font-weight:bold; display:none !Important;">
                                                <div class="form-group row" style="">
                                                    <label class="col-md-3" style="line-height:25px;">Total</label>
                                                    <div class="col-md-9"><input type="text" class="form-control form-control-sm" id="txt_cheque_total" value="" readonly="readonly" /></div>
                                                </div>
                                                <div class="form-group row" style="margin-left:25px;">
                                                    <label class="col-md-4" style="line-height:25px;">Balance</label>
                                                    <div class="col-md-8"><input type="text" class="form-control form-control-sm" id="txt_cheque_balance" value="" readonly="readonly" /></div>
                                                </div>
                                            </div>
                                            <div class="small text-muted">
                                                <input type="hidden" id="h_cheque_detail_running_total" value="" />
                                                <button type="submit" name="submit" class="btn btn-success">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                
                                <div class="card rounded-0">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm" id="chequeDetail_dataTable" width="100%" border="0" cellspacing="0">
                                              <thead>
                                                <tr>
                                                    <th width="">Bank/Account</th>
                                                    <th>Cheque Date</th>
                                                    <th>Cheque #</th>
                                                    <th>Narration</th>
                                                    <th>Value</th>
                                                </tr>
                                              </thead>
                                              
                                            </table>
                                          </div>
                                    </div>
                                </div>
                                
                                
                                <span class="custom-content-close"></span>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            
            </div>
            </div>
            
            </div>
            
            
            <!-- find providers -->
            <div class="modal fade" id="providersListModal" tabindex="-1" role="dialog" aria-labelledby="providersListModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <form id="frmProviderRegList" name="frmProviderRegList" method="post" action="">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="providersListModalLabel"><span>Choose a Provider/Supplier</span></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body" style="min-height:350px;">
                        <div class="row">
                            <div class="col-md-4" style="display:none;">
                                <div class="form-group">
                                    <label for="">Company</label>
                                    <select class="form-control form-control-sm nest_head" id="drp_filter_company_supplier_list" data-findnest="suppliersnest">
                                        <option value="">Select</option>
                                        <?php 
										foreach($company_payment_list_filter as $cf){
										?>
                                        <option value="<?php echo $cf->idtbl_company; ?>"><?php echo $cf->company; ?></option>
                                        <?php 	
										}
										?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Branch</label>
                                    <select class="form-control form-control-sm" id="drp_filter_branch_supplier_list" data-nestname="suppliersnest">
                                        <option value="">Select</option>
                                        <?php 
										foreach($branch_payment_list_filter as $df){
										?>
                                        <option class="nestopt d-none" value="<?php echo $df->idtbl_company_branch; ?>" data-nestcode="<?php echo $df->tbl_company_idtbl_company; ?>" disabled="disabled"><?php echo $df->branch; ?></option>
                                        <?php 	
										}
										?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
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
              <div class="modal-dialog modal-lg" role="document">
                <form id="" name="" method="post" action="">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="assignmentListModalLabel"><span>Choose a Provider/Supplier</span></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                      	<div class="row">
                        	<div class="col-md-4" style="display:none;">
                                <div class="form-group">
                                    <label for="">Company</label>
                                    <select class="form-control form-control-sm nest_head" id="drp_filter_company_payment_list" data-findnest="paymentsnest" disabled="disabled">
                                        <option value="">Select</option>
                                        <?php 
										foreach($company_payment_list_filter as $cf){
										?>
                                        <option value="<?php echo $cf->idtbl_company; ?>"><?php echo $cf->company; ?></option>
                                        <?php 	
										}
										?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Branch</label>
                                    <select class="form-control form-control-sm" id="drp_filter_branch_payment_list" data-nestname="paymentsnest" disabled="disabled">
                                        <option value="">Select</option>
                                        <?php 
										foreach($branch_payment_list_filter as $df){
										?>
                                        <option class="nestopt d-none" value="<?php echo $df->idtbl_company_branch; ?>" data-nestcode="<?php echo $df->tbl_company_idtbl_company; ?>" disabled="disabled"><?php echo $df->branch; ?></option>
                                        <?php 	
										}
										?>
                                    </select>
                                </div>
                                <div class="form-group">
                                	<label for="">Supplier</label>
                                    <select class="form-control form-control-sm" id="" disabled="disabled">
                                    	<option value="-1">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="datatable table-responsive">
                                    <table class="table table-bordered table-hover dataTable no-footer" id="supplierPayments_dataTable" width="100%" cellspacing="0">
                                      <thead>
                                        <tr>
                                          <th width="30%">Provider/Supplier</th>
                                          <th width="40%">Remarks</th>
                                          <th>Total Payment</th>
                                          <th width="5%">Actions</th>
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
            <!-- find records -->
            
		</main>
		<?php include "include/footerbar.php"; ?>
	</div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
(function() {
	var cssCustom = document.createElement('link');
	cssCustom.href = '<?php echo base_url('assets/css/account_payment_settle.css'); ?>';
	cssCustom.rel = 'stylesheet';
	cssCustom.type = 'text/css';
	document.getElementsByTagName('head')[0].appendChild(cssCustom);
	
	var cssMain = document.createElement('link');
	cssMain.href = '<?php echo base_url('assets/css/other_styles.css'); ?>';
	cssMain.rel = 'stylesheet';
	cssMain.type = 'text/css';
	document.getElementsByTagName('head')[0].appendChild(cssMain);
})();
</script>
<script>
$(document).ready(function(){
	/*
	var $wrapper = $( '#custom-inner' );
	*/
	/*
	$("#btnfind").on("click", function(){
		findEvents();//alert("x");
	});
	*/
	$(document).on("click", ".custom-content-close", hideInfo);
	
	//$(document).on("click", ".btn_act_close_modal", hideInfo);
	
	function findEvents(){
		
		var $events = $( '#event_info' );
		/*
			$close = $( '<span class="custom-content-close"></span>' ).on( 'click', hideInfo );
		*/

		/*
		$events.append( $contentEl.html() , $close ).insertAfter( $wrapper );
		*/
		/*
		$events.append( unescape($contentEl.data("evs")).replace(",","") , $close ).insertAfter( $wrapper );
		
		$events.append( $close ).insertAfter( $wrapper );
		*/
		setTimeout( function() {
			$events.css( 'top', '0%' );
		}, 25 );

	}
	
	function hideInfo() {

		var $events = $( '#event_info' );
		if( $events.length > 0 ) {
			
			$events.css( 'top', '100%' );
			/*
			Modernizr.csstransitions ? $events.on( transEndEventName, function() { $( this ).remove(); } ) : $events.remove();
			*/

		}

	}
	
	
	var providersTable = $('#providers_dataTable').DataTable({ 
				  "info": false,
				  "processing": true,
				  "serverSide": true,
				  "autoWidth":false,
				  "ajax": {
					  "url":"<?php echo base_url('scripts/company_branch_suppliers_list.php');?>",//AccountPayment/provider_datatable
					  "type":"POST",//GET
				  },
				  "columns":[
						{"data":"supplier_name"}, 
						{"data":"sect_key", "className":'text-center', "width":'5%'}//supplier_regno
					],
				  "sPaginationType": "full_numbers",
				  "language": {
					"search": "_INPUT_", 
					"searchPlaceholder": "Search",
					"paginate": {
						"next": '<i class="fa fa-angle-right"></i>',
						"previous": '<i class="fa fa-angle-left"></i>',
						"first": '<i class="fa fa-angle-double-left"></i>',
						"last": '<i class="fa fa-angle-double-right"></i>'
					}
				  }, 
				  "iDisplayLength": 5,
				  "aLengthMenu": [[5, 10, 25, 50, 100,500,-1], [5, 10, 25, 50,100,500,"All"]], 
				  
				  "columnDefs": [{
						"targets":1,
						render: function( data, type, row ){
							return '<button type="button" class="btn btn-sm btn-success btn_new_payment" data-branchreg="'+row.branch_regno+'" data-companyreg="'+row.parent_company_regno+'" data-supplierreg="'+row.supplier_regno+'" data-mainid="-1"><i class="fa fa-plus"></i></button>';//data+'x'+row.parent_company_regno;
						}
					}], 
				  /*
				  "createdRow": function( row, data, dataIndex ){
						//$( row ).attr('data-location', data.brcode);
						
						if(data[0]==selectedOrder){
							$( row ).addClass('row_high');
						}
						
					}
				  */
			  });
	/*
	$('#drp_filter_company_supplier_list, #drp_filter_branch_supplier_list').on('keyup change', function () {
			var pc = $("#drp_filter_company_supplier_list").find(":selected").val();
			var pd = $("#drp_filter_branch_supplier_list").find(":selected").val();
			if (providersTable.column(1).search() !== (pc+"_"+pd)) {
				providersTable.column(1).search(pc+"_"+pd).draw();
			}
	  });
	*/
	$("#providersListModal").on("shown.bs.modal", function(){
		var pc = $("#drp_company_list").find(":selected").val();//$("#drp_filter_company_supplier_list").find(":selected").val();
		var pd = $("#drp_company_branch_list").find(":selected").val();//$("#drp_filter_branch_supplier_list").find(":selected").val();
		if (providersTable.column(1).search() !== (pc+"_"+pd)) {
			providersTable.column(1).search(pc+"_"+pd).draw();
		}
	});
	
	var supplierPaymentsTable = $('#supplierPayments_dataTable').DataTable({ 
				  "info": false,
				  "processing": true,
				  "serverSide": true,
				  "autoWidth":false,
				  "ajax": {
					  "url":"<?php echo base_url('scripts/company_branch_supplier_payments_list.php');?>",//AccountPayment/provider_datatable
					  "type":"POST",//GET
				  },
				  "columns":[
						{"data":"supplier_name"}, {"data":"pay_remarks"}, //{"data":"pay_invoice"}, 
						{"data":"pay_invoices_total", "className":'text-right', "width":'20%'}, //{"data":"parent_company_regno"}, 
						{"data":"sect_key", "className":'text-center', "width":'5%'} //{"data":"branch_regno"}
					],
				  "sPaginationType": "full_numbers",
				  "language": {
					"search": "_INPUT_", 
					"searchPlaceholder": "Search",
					"paginate": {
						"next": '<i class="fa fa-angle-right"></i>',
						"previous": '<i class="fa fa-angle-left"></i>',
						"first": '<i class="fa fa-angle-double-left"></i>',
						"last": '<i class="fa fa-angle-double-right"></i>'
					}
				  }, 
				  "iDisplayLength": 5,
				  "aLengthMenu": [[5, 10, 25, 50, 100,500,-1], [5, 10, 25, 50,100,500,"All"]], 
				  
				  "columnDefs": [{
						"targets":2,
						render: function( data, type, row ){
							/*
							if(type=="display"){
								return data;//+'x'+row.parent_company_regno+'y';
							}else{
								return row.parent_company_regno;
							}
							*/
							var tot_value = parseFloat(data);
							return tot_value.toFixed(2);//data;
							
						}
					},{
						"targets":3,
						render: function( data, type, row ){
							return '<button type="button" class="btn btn-sm btn-success btn_edit_payment" data-branchreg="'+row.branch_regno+'" data-companyreg="'+row.parent_company_regno+'" data-supplierreg="'+row.supplier_regno+'" data-mainid="'+row.pay_regid+'"><i class="fa fa-edit"></i></button>';//data+'x'+row.parent_company_regno;
						}
					}], 
				  
			  });
	/*
	$('#drp_filter_company_payment_list').on('keyup change', function () {
			if (allPaymentsTable.column(3).search() !== this.value) {
				allPaymentsTable.column(3).search(this.value).draw();
			}
	  });
	$('#drp_filter_branch_payment_list').on('keyup change', function () {
			if (allPaymentsTable.column(4).search() !== this.value) {
				allPaymentsTable.column(4).search(this.value).draw();
			}
	  });
	*/
	/*
	$('#drp_filter_company_payment_list, #drp_filter_branch_payment_list').on('keyup change', function () {
			var c = $("#drp_filter_company_payment_list").find(":selected").val();
			var d = $("#drp_filter_branch_payment_list").find(":selected").val();
			if (allPaymentsTable.column(4).search() !== (c+"_"+d)) {
				allPaymentsTable.column(4).search((c+"_"+d)).draw();
			}
	  });
	*/
	$("#assignmentListModal").on("shown.bs.modal", function(){
		var pc = $("#drp_company_list").find(":selected").val();
		var pd = $("#drp_company_branch_list").find(":selected").val();
		if (supplierPaymentsTable.column(3).search() !== (pc+"_"+pd)) {
			supplierPaymentsTable.column(3).search(pc+"_"+pd).draw();
		}
	});
	
	var invoiceDetailTable = $("#invoiceDetail_dataTable").DataTable({
				"info":false,
				"searching":false,
				"paging":false,
				"columns":[
						{"data":"invoice_paysettle_status"}, {"data":"invoice_paysettle_txt"}, 
						{"data":"invoice_paysettle_narration"}, 
						{"data":"invoice_paysettle_amount", "className":'text-right'}
					],
				"columnDefs":[{
							"targets":0,
							"render":function( data, type, row ){
								var check_str = (data==1)?'checked="checked"':'';
								return '<input type="checkbox" class="chk_accd" value="'+data+'" '+
									'data-payableid="'+row.invoice_paysettle_id+'" data-revisestatus="0" '+
									'data-reviseuser="" data-revisetime="" '+
									check_str+'/>';
							}
						}, {
							"targets":3,
							"render":function( data, type, row ){
								var col_value = parseFloat(data);
								return col_value.toFixed(2);
							}
					}],
				"createdRow": function( row, data, dataIndex ){
					$( row ).attr('id', 'chk_accd-'+data.paysettle_invoice_id);
				}
			});
	
	var chequeDetailTable = $("#chequeDetail_dataTable").DataTable({
				"info":false,
				"searching":false,
				"iDisplayLength": 5,
				"aLengthMenu": [[5], [5]],
				"columns":[
						{"data":"bank_name"}, {"data":"cheque_date"}, 
						{"data":"cheque_no"}, {"data":"cheque_issue_narration"}, {"data":"cheque_value"}
					],
				"columnDefs":[{
						"targets":0,
						render:function( data, type, row ){
							return data+' '+row.bank_account_name;//row.bank_branch_name;//
						}
					},{
						"targets":4,
						"className":'text-right',
						render:function( data, type, row ){
							var cheque_value = parseFloat(data);
							return cheque_value.toFixed(2);
						}
					}]
			});
	
	function prep_supplier_invoices_optlist(optlist){
		//$("#drp_supplier_invoices_list").prop("disabled", false);
		//$("#drp_supplier_invoices_list").empty();
		$("#drp_supplier_invoices_list").html('<option value="-1" >'+
										  'Select</option>');
		$.each(optlist, function(index, obj){
				var str='<option value="'+obj.form_key;
				//str+='" data-parentacc="'+obj.parent_account_id;
				//str+='" '+obj.cust_attr;
				//str+=' data-nestcode="'+obj.style_refno;
				//str+='" data-upcrefno="'+obj.upc_regno;
				str+='" >';
				str+=obj.form_val+'</option>';//escapeTags(obj.form_val)
				$("#drp_supplier_invoices_list").append(str);
				
				
			});
		
	}
	
	$('.nest_head').change(function(){
		prep_nest($(this).data('findnest'), $(this).find(":selected").val(), 0);
		//prep_nest($(this).data('findnest'), $(this).find(":selected").data('colcode'), 0);
	});
	
	function prep_nest(nestname, nestcode, selectedval){
		//console.log(nestname+'--'+nestcode+'--'+selectedval);
		
		var childobj=$('select[data-nestname="'+nestname+'"]')
		
		var blockobj=$(childobj).find('option.nestopt');
		$(blockobj).prop('disabled', true);
		$(blockobj).addClass('d-none');
		
		var allowobj=$(childobj).find('option[data-nestcode="'+nestcode+'"]');
		$(allowobj).prop('disabled', false);
		$(allowobj).removeClass('d-none');
		
		var selected_val=(selectedval!=='')?selectedval:'';
		//console.log(selectedval+'vs'+selected_val);
		var selected_pos=0;
		
		if(selected_val=='0'){
			var selected_opt=$(allowobj).index();
			//selected_val=(typeof($(allowobj).val())=="undefined")?$(childobj).children('option:first').val():$(allowobj).val();
			//console.log(typeof($(allowobj).val())=="undefined");//$(allowobj).length
			//console.log('0--'+$(allowobj).index());
			selected_pos=(selected_opt>0)?selected_opt:0;
		}else{
			var actobj=$(childobj).find('option[data-nestcode="'+nestcode+'"][value="'+selectedval+'"]');
			//console.log('1--'+$(actobj).index());
			var selected_opt=$(actobj).index();
			selected_pos=(selected_opt>0)?selected_opt:0;
		}
		
		selected_pos = 0;
		//$(childobj).val(selected_val);
		$(childobj).find('option').eq(selected_pos).prop("selected", true);
		//$(childobj).trigger("chosen:updated");
	}
	
	function calc_running_tot_figures(){
		//calculate-remaining-amount-of-header-detail-sum-values
		//child_account_id -> supplier_invoice_id
		var fig_supp_invoice_amount = parseFloat($("#txt_supp_invoice_total").val());
		
		var fig_acc_detail_amount = parseFloat($("#txt_invoice_detail_amount").val());
		var supplier_invoice_id = $("#drp_supplier_invoices_list").find(":selected").val();
		
		var acc_detail_running_total = parseFloat($("#h_invoice_detail_running_total").val());
		
		//console.log('#chk_accd-'+supplier_invoice_id);
		
		var c = invoiceDetailTable.cell('tr#chk_accd-'+supplier_invoice_id+' '+'td:nth-child(1)').node();
		//console.log($(c).children('input[type="checkbox"]').is(":checked")?'y':'n');//($(c).html());
		var c_child = $(c).children('input[type="checkbox"]');
		
		var selected_tr = invoiceDetailTable.row('#chk_accd-'+supplier_invoice_id);
		
		var child_account_val = 0;// keep value from detail table
		
		if(selected_tr.length==1){
			var c_child_status = $(c_child).is(":checked")?1:0;
			var d = selected_tr.data();
			//console.log(d.invoice_paysettle_amount);
			child_account_val = parseFloat(d.invoice_paysettle_amount)*c_child_status;
		}
		
		if(isNaN(fig_supp_invoice_amount)){
			fig_supp_invoice_amount = 0;
		}
		
		if(isNaN(fig_acc_detail_amount) || (supplier_invoice_id=='-1')){
			fig_acc_detail_amount = 0;
		}
		
		if(isNaN(acc_detail_running_total)){
			acc_detail_running_total = 0;
		}
		
		if(isNaN(child_account_val)){
			child_account_val = 0;
		}
		
		var new_running_total = fig_supp_invoice_amount - (fig_acc_detail_amount + (acc_detail_running_total - child_account_val));
		$("#lbl_warnmsg").html(fig_supp_invoice_amount + " - (" +fig_acc_detail_amount + " + (" +acc_detail_running_total + " - " + child_account_val+"))="+new_running_total);
		$("#txt_supp_invoice_shortage_amount").val(new_running_total.toFixed(2));
	}
	
	function update_acc_detail_running_total(supplier_invoice_id){
		//child_account_id -> supplier_invoice_id
		var acc_detail_running_total = parseFloat($("#h_invoice_detail_running_total").val());
		if(isNaN(acc_detail_running_total)){
			acc_detail_running_total = 0;
		}
		
		var dd_acc_detail_running_total = acc_detail_running_total;
		
		var fig_acc_detail_amount = parseFloat($("#txt_invoice_detail_amount").val());
		if(isNaN(fig_acc_detail_amount) || (supplier_invoice_id=='-1')){
			fig_acc_detail_amount = 0;
		}
		
		var c = invoiceDetailTable.cell('tr#chk_accd-'+supplier_invoice_id+' '+'td:nth-child(1)').node();
		
		var c_child = $(c).children('input[type="checkbox"]');
		
		var selected_tr = invoiceDetailTable.row('#chk_accd-'+supplier_invoice_id);
		
		var child_account_val = 0;// keep value from detail table
		
		if(selected_tr.length==1){
			var c_child_status = $(c_child).is(":checked")?1:0;
			var d = selected_tr.data();
			child_account_val = parseFloat(d.invoice_paysettle_amount)*c_child_status;
		}
		
		if(isNaN(child_account_val)){
			child_account_val = 0;
		}
		
		acc_detail_running_total -= child_account_val;
		acc_detail_running_total += fig_acc_detail_amount;
		//$("#lbl_warnmsg").html("("+dd_acc_detail_running_total + " - " + child_account_val + ")" + " + " + fig_acc_detail_amount+"="+acc_detail_running_total);
		$("#h_invoice_detail_running_total").val(acc_detail_running_total);
	}
	
	function calc_running_tot_cheque_payments(){
		var cheque_total = parseFloat($("#txt_cheque_total").val());
		var cheque_detail_running_total = parseFloat($("#h_cheque_detail_running_total").val());
		
		if(isNaN(cheque_total)){
			cheque_total = 0;
		}
		
		if(isNaN(cheque_detail_running_total)){
			cheque_detail_running_total = 0;
		}
		
		var cheque_balance = cheque_total-cheque_detail_running_total;
		
		$("#txt_cheque_balance").val(cheque_balance.toFixed(2));
		
		$("#lbl_eventmsg").html("Total payments "+cheque_detail_running_total+"/"+cheque_total+" (balance "+cheque_balance+")");
	}
	function submit_running_tot_cheque_payments(){
		var cheque_total = parseFloat($("#txt_cheque_total").val());
		var cheque_detail_running_total = parseFloat($("#h_cheque_detail_running_total").val());
		var cheque_value = parseFloat($("#txt_cheque_value").val());
		
		if(isNaN(cheque_total)){
			cheque_total = 0;
		}
		
		if(isNaN(cheque_detail_running_total)){
			cheque_detail_running_total = 0;
		}
		
		if(isNaN(cheque_value)){
			cheque_value = 0;
		}
		
		var cheque_balance = cheque_total-(cheque_detail_running_total+cheque_value);
		
		return cheque_balance.toFixed(2);
	}
	
	function update_cheque_detail_running_total(){
		var cheque_detail_running_total = parseFloat($("#h_cheque_detail_running_total").val());
		var cheque_value = parseFloat($("#txt_cheque_value").val());
		
		if(isNaN(cheque_detail_running_total)){
			cheque_detail_running_total = 0;
		}
		
		if(isNaN(cheque_value)){
			cheque_value = 0;
		}
		
		cheque_detail_running_total += cheque_value;
		
		$("#h_cheque_detail_running_total").val(cheque_detail_running_total);
	}
	
	//to-do-block
	$(".running_tot_figures").on('keyup change', function(){
		calc_running_tot_figures();
		
	});
	
	$(document).on("click", ".btn_new_payment, .btn_edit_payment", function(){
		var mainid = $(this).data('mainid');
		var companyreg = $(this).data('companyreg');
		var branchreg = $(this).data('branchreg');
		var supplierreg = $(this).data('supplierreg');
		
		$.ajax({
			method: "POST",
			url: "<?php echo base_url('IssuePayment/create'); ?>",
			data:{company_reg:companyreg, branch_reg:branchreg, supplier_reg:supplierreg, acc_paysettle_id:mainid},
			dataType:"JSON"
		}).done(function(data){
			$("#drp_company_list").val(companyreg);
			$("#drp_company_branch_list").val(branchreg);
			$("#drp_supplier_list").val(supplierreg);//data.view_header_data.supplier//''
			$("#txt_acc_period").val(data.account_period_txt);
			//$("#txt_supp_invoice_num").val(data.view_header_data.invoiceno);//''
			$("#txt_supp_invoice_total").val(data.view_header_data.totalpayment);//amount//0
			$("#txt_supp_invoice_remarks").val(data.view_header_data.remark);//batchno//''
			$("#txt_payment_issue_date").val(data.view_header_data.date);//tradate//''
			
			prep_supplier_invoices_optlist(data.supplier_invoices);
			
			$("#txt_invoice_detail_narration").val('');
			$("#txt_invoice_detail_amount").val('');
			
			$("#h_acc_period").val(data.account_period_id);
			$("#h_invoice_detail_running_total").val(data.payment_figures_total);//0
			$("#h_paysettle_main_id").val(data.view_header_data.idtbl_account_paysettle);
			
			var short_val = parseFloat($("#txt_supp_invoice_total").val())-parseFloat($("#h_invoice_detail_running_total").val());
			$("#txt_supp_invoice_shortage_amount").val(isNaN(short_val)?'0.00':short_val.toFixed(2));
			
			invoiceDetailTable.clear();
			invoiceDetailTable.rows.add(data.view_detail_data);
			invoiceDetailTable.draw();
			
			$("#lbl_warnmsg").html('&nbsp;');
			
			hideInfo();
			
			if($("#h_paysettle_main_id").val()==''){
				$("#providersListModal").modal('hide');
			}else{
				$("#assignmentListModal").modal('hide');
			}
		});
		
	});
	
	$("#frmPaymentInfo").submit(function(event){
		event.preventDefault();
		
		//get revisestatus changed options
		var revisedRows = $('.chk_accd[data-revisestatus="1"]').map(function(i, el) {
							var selected_tr = invoiceDetailTable.row($(this).parent().closest('tr'));
							var d = selected_tr.data();
							//console.log(d.invoice_paysettle_amount);
							var selectedInvoice = $("#drp_supplier_invoices_list").find(":selected").val();
							if(d.paysettle_invoice_id != selectedInvoice){
								var newstatus = $(this).is(":checked")?1:2;//1:0
								return {
									idtbl_account_paysettle_info:$(el).attr('data-payableid'), 
									status:newstatus,
									updateuser:$(el).attr('data-reviseuser'), 
									updatedatetime:$(el).attr('data-revisetime')
								};
							}
						}).get();
		//console.log(revisedRows);
		
		//get revisedstatus=1 and checked rows to validate 
		//total settle amount is not exceeding particular invoice amount
		var restoredRows = $('.chk_accd[data-revisestatus="1"]').map(function(i, el) {
							var selected_tr = invoiceDetailTable.row($(this).parent().closest('tr'));
							var d = selected_tr.data();
							//console.log(d.invoice_paysettle_amount);
							var selectedInvoice = $("#drp_supplier_invoices_list").find(":selected").val();
							if((d.paysettle_invoice_id != selectedInvoice) && ($(this).is(":checked"))){
								return d.paysettle_invoice_id;
							}
						}).get();
		
		var companyId = $("#drp_company_list").find(":selected").val();
		var companyBranchId = $("#drp_company_branch_list").find(":selected").val();
		var supplierId = $("#drp_supplier_list").find(":selected").val();
		//var suppInvoice = $("#txt_supp_invoice_num").val();
		var suppInvoiceTotal = $("#txt_supp_invoice_total").val();
		var suppInvoiceRemarks = $("#txt_supp_invoice_remarks").val();
		var paymentIssueDate = $("#txt_payment_issue_date").val();
		var paysettleInvoiceId = $("#drp_supplier_invoices_list").find(":selected").val();
		var paysettleInvoiceDispCode = $("#drp_supplier_invoices_list").find(":selected").text();
		var invoiceSettleNarration = $("#txt_invoice_detail_narration").val();
		var invoiceSettleAmount = $("#txt_invoice_detail_amount").val();
		var accPeriod = $("#h_acc_period").val();
		var invoiceDetailRunningTotal = $("#h_invoice_detail_running_total").val();
		var paysettleMainId = $("#h_paysettle_main_id").val();
		
		var paymentShortage = $("#txt_supp_invoice_shortage_amount").val();
		
		var restoredInvoices = restoredRows.length;
		
		$.ajax({
			method: "POST",
			url: "<?php echo base_url('IssuePayment/store'); ?>",
			data:{company_id:companyId, company_branch_id:companyBranchId, supplier_id:supplierId, //supp_invoice:suppInvoice, 
				supp_invoice_total:suppInvoiceTotal, supp_invoice_remarks:suppInvoiceRemarks,
				payment_issue_date:paymentIssueDate,
				supplier_invoice_id:paysettleInvoiceId, invoice_settle_narration:invoiceSettleNarration, invoice_settle_amount:invoiceSettleAmount,
				invoice_detail_running_total:invoiceDetailRunningTotal, payment_shortage:paymentShortage, 
				acc_period:accPeriod, paysettle_main_id:paysettleMainId, 
				revised_rows:revisedRows, restored_rows:restoredRows, restored_invoices:restoredInvoices},
			dataType:"JSON",
			beforeSend: function(){
				$("#lbl_warnmsg").html('<i class="fa fa-spinner fa-spin"></i>Updating. please wait');
			}
		}).done(function(data){
			//alert("Batch number is "+data);
			var rres = data.resMsg;
			if(rres!=""){
				$("#lbl_warnmsg").html(rres);//alert(rres);//"something wrong"
			}else{
				if(paysettleMainId==""){
					$("#h_paysettle_main_id").val(data.head_k);
				}
				
				$("#lbl_warnmsg").html('Changes saved.');//'&nbsp;'
				
				
				//go through revisestatus changed options to 
				//update acc-payable-status to 0 or 1 based on particular .chk-accd checked attr and
				//reset data-revisestatus to 0
				$('.chk_accd[data-revisestatus="1"]').each(function(index, value){
					var newstatus = $(this).is(":checked")?1:2;//1:0
					var selected_tr = invoiceDetailTable.row($(this).parent().closest('tr'));
					var d = selected_tr.data();
					d.invoice_paysettle_status = newstatus;
					
					invoiceDetailTable.row(selected_tr).data(d).draw();
					
					$(this).attr('data-revisestatus', '0');
				});
				
				
				update_acc_detail_running_total(paysettleInvoiceId);
				
				
				if(data.sub_k>0){
					var selected_tr = invoiceDetailTable.row('#chk_accd-'+paysettleInvoiceId);
					if(selected_tr.length==0){
						var rowNode = invoiceDetailTable.row.add( {
								"invoice_paysettle_id":data.sub_k,
								"invoice_paysettle_narration":invoiceSettleNarration,
								"invoice_paysettle_amount":invoiceSettleAmount,
								"invoice_paysettle_status":1,
								"paysettle_invoice_id":paysettleInvoiceId, 
								"invoice_paysettle_txt":paysettleInvoiceDispCode
							}).draw( false ).node();
					}else{
						var d=selected_tr.data();
						d.invoice_paysettle_status = 1;
						d.invoice_paysettle_narration = invoiceSettleNarration;
						d.invoice_paysettle_amount = invoiceSettleAmount;
						
						invoiceDetailTable.row(selected_tr).data(d).draw();
					}
					
					//$("#lbl_warnmsg").html("&nbsp;");
					$("#drp_supplier_invoices_list").val('-1');
					$("#txt_invoice_detail_narration").val("");
					$("#txt_invoice_detail_amount").val("");
					
				}
			}
		});
	});
	
	$(document).on('click', '.chk_accd', function(){
		var revisestatus = $(this).data('revisestatus');
		var selected_tr = invoiceDetailTable.row($(this).parent().closest('tr'));
		var d = selected_tr.data();
		//console.log(d.invoice_paysettle_amount);
		var old_payable_status = d.invoice_paysettle_status;
		
		
		var new_payable_status = $(this).is(":checked")?1:2;//1:0
		var CHK_PAYABLE_STATUS = $(this).is(":checked")?1:0
		
		
		var new_revisestatus = (old_payable_status!=new_payable_status)?1:0;
		
		var obj_chk_accd = $(this);
		
		$.ajax({
			method:"POST", 
			url:"<?php echo base_url('IssuePayment/toggle'); ?>",
			dataType:"JSON"
		}).done(function(data){
			if(data.toggle_status=='success'){
				$(obj_chk_accd).attr('data-revisestatus', new_revisestatus);//this
				$(obj_chk_accd).attr('data-reviseuser', data.revise_user);//this
				$(obj_chk_accd).attr('data-revisetime', data.revise_time);//this
				
				var acc_detail_running_total = parseFloat($("#h_invoice_detail_running_total").val());
				
				if(isNaN(acc_detail_running_total)){
					acc_detail_running_total = 0;
				}
				
				acc_detail_running_total += (parseFloat(d.invoice_paysettle_amount)*(CHK_PAYABLE_STATUS+(CHK_PAYABLE_STATUS-1)));
				$("#h_invoice_detail_running_total").val(acc_detail_running_total);
				
				calc_running_tot_figures();
			}else{
				$("#lbl_warnmsg").html('Something wrong');
				$(obj_chk_accd).prop("checked", !($(obj_chk_accd).is(":checked")));
			}
		});
	});
	
	$("#btnOrderApprovePost").on('click', function(){
		if($("#h_paysettle_main_id").val()!=''){
			var opt_approve = confirm('Are you sure you want to approve this payment?');
		
			if(opt_approve){
				
				var revisedRows = ($('.chk_accd[data-revisestatus="1"]').length == 0)?0:1;
				var paysettleMainId = $("#h_paysettle_main_id").val();
				var suppInvoiceTotal = $("#txt_supp_invoice_total").val();
				
				$.ajax({
					method:"POST",
					url:'<?php echo base_url('IssuePayment/approve'); ?>',
					data:{revised_rows:revisedRows, paysettle_main_id:paysettleMainId, exp_rows:0, 
						supp_invoice_total:suppInvoiceTotal},
					dataType:"JSON",
					beforeSend:function(){
						$("#lbl_warnmsg").html('<i class="fa fa-spinner fa-spin"></i>Please wait.');
					}
				}).done(function(data){
					//$("#lbl_warnmsg").html(data.resMsg);
					var rres = data.resMsg;
					if(rres!=""){
						$("#lbl_warnmsg").html(rres);//alert(rres);//"something wrong"
					}else{
						$("#lbl_warnmsg").html("Payment approved successfully");
					}
				});
				
			}
		}else{
			alert('You need to create or open existing payment first');
		}
	});
	
	$("#btnIssueCheque").on("click", function(){
		if($("#h_paysettle_main_id").val()!=''){
			var paysettleMainId = $("#h_paysettle_main_id").val();
			
			$.ajax({
				method:"POST",
				url:"<?php echo base_url('IssuePayment/review'); ?>",
				data:{paysettle_main_id:paysettleMainId},
				dataType:"JSON"
			}).done(function(data){
				var opt_show = true;
				
				if(data.approve_status==0){
					opt_show = confirm("The total payment is still not approved.\r\n"+
							"Do you really wish to proceed with cheque allocation anyway?");
				}
				
				if(opt_show){
					$("#txt_issued_to").val($("#drp_supplier_list").find(":selected").text());
					$("#txt_cheque_total").val($("#txt_supp_invoice_total").val());
					
					$("#txt_cheque_issue_narration").val('');
					$("#drp_company_bank_list").val('-1');
					prep_nest($("#drp_company_bank_list").data('findnest'), '', 0);
					$("#txt_cheque_issue_date").val('');
					$("#txt_cheque_value").val('');
					
					$("#h_cheque_detail_running_total").val(data.cheque_issue_total);//0
					
					calc_running_tot_cheque_payments();
					
					chequeDetailTable.clear();
					chequeDetailTable.rows.add(data.cheque_detail_data);
					chequeDetailTable.draw();
					
					findEvents();//slide-up-cheque-issue
				}
			});
			
		}else{
			alert('You need to create or open existing payment first');
		}
	});
	/*
	$("#txt_cheque_value").on('keyup change', function(){
		calc_running_tot_cheque_payments();
		
	});
	*/
	$("#frmChequeInfo").submit(function(event){
		event.preventDefault();
		
		var paysettleMainId = $("#h_paysettle_main_id").val();
		var chequeIssueNarration = $("#txt_cheque_issue_narration").val();
		var chequeBalance = submit_running_tot_cheque_payments();//$("#txt_cheque_balance").val();
		var chequeIssueDate = $("#txt_cheque_issue_date").val();
		var chequeValue = $("#txt_cheque_value").val();
		var bankId = $("#drp_company_bank_list").find(":selected").val();
		var bankBranchId = $("#drp_company_bank_branch_list").find(":selected").val();
		
		var bankAccountId = $("#drp_company_bank_branch_list").find(":selected").data('accnum');
		
		var bankName = $("#drp_company_bank_list").find(":selected").text();
		var bankBranchName = $("#drp_company_bank_branch_list").find(":selected").text();
		
		$.ajax({
			method:"POST",
			url:"<?php echo base_url('IssuePayment/draw'); ?>",
			data:{bank_id:bankId, bank_branch_id:bankBranchId, bank_account_id:bankAccountId,
				cheque_issue_narration:chequeIssueNarration, cheque_issue_date:chequeIssueDate, cheque_value:chequeValue, 
				paysettle_main_id:paysettleMainId, cheque_balance:chequeBalance},
			dataType:"JSON"
		}).done(function(data){
			var rres = data.resMsg;
			if(rres==""){
				var rowNode = chequeDetailTable.row.add( {
						"bank_name":bankName,
						"bank_account_name":bankBranchName, "bank_branch_name":'',//bankBranchName,
						"cheque_date":chequeIssueDate,
						"cheque_no":data.cheque_no,
						"cheque_issue_narration":chequeIssueNarration, 
						"cheque_value":chequeValue
					}).draw( false ).node();
				
				update_cheque_detail_running_total();
				//calc_running_tot_cheque_payments();
				
				rres = "Cheque details updated";
				
				$("#txt_cheque_issue_date").val('');
				$("#txt_cheque_value").val('');
			}
			
			$("#lbl_eventmsg").html(rres).fadeTo(5000, 1, calc_running_tot_cheque_payments);
			
		});
	});
	
	$("#btnPostCheque").on("click", function(){
		if($("#h_paysettle_main_id").val()!=''){
			var opt_freeze = confirm('Are you sure you want to post this payment?');
			
			if(opt_freeze){
				var paysettleMainId = $("#h_paysettle_main_id").val();
				
				$.ajax({
					method:"POST",
					url:"<?php echo base_url('IssuePayment/freeze'); ?>",
					data:{selected_opt:paysettleMainId},
					dataType:"JSON"
				}).done(function(data){
					var rres = data.resMsg;
					/*
					if(rres!=""){
						//$("#lbl_warnmsg").html(rres);//alert(rres);//"something wrong"
					}else{
						//$("#lbl_warnmsg").html("Payment posted successfully");
						rres = "Payment posted successfully";
					}
					*/
					alert(rres);//data.resMsg
					
				});
			}
			
		}else{
			alert('You need to create or open existing payment first');
		}
	})
	
	
});
</script>
<?php include "include/footer.php"; ?>
