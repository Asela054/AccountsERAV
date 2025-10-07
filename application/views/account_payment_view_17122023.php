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
							<span>Payments</span>
                            <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right dropdown-toggle" id="btnorderacts" style="position:absolute; right:10px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-plus"></i>&nbsp;Actions</button>
                            <div class="dropdown-menu" aria-labelledby="btnorderacts">
                            	<a class="dropdown-item" id="btnOrderCreate" data-toggle="modal" data-target="#sectionsListModal" data-refid="-1" href="javascript:void(0);" title="Create new payment">New</a>
                            	<a class="dropdown-item" id="btnOrderFind" data-toggle="modal" data-target="#assignmentListModal" href="javascript:void(0);" title="Search previously created payments">Find</a>
                                <a class="dropdown-item" id="btnOrderApprovePost" href="javascript:void(0);">Approve and Post</a>
                                <a class="dropdown-item" id="btnOrderDelete" data-refid="-1" href="javascript:void(0);">Delete</a>
                            </div>
						</h1>
					</div>
				</div>
			</div>
            <!--div class="container-fluid mt-2 p-0 p-2">
				<div class="card rounded-0">
					<div class="card-body p-0 p-2">
						<form id="frmTest" method="post">
                        	<button type="submit" class="btn btn-sm btn-primary">Test batch num</button>
                        </form>
					</div>
            	</div>
            </div-->
            <div class="container-fluid mt-2 p-0 p-2">
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
									echo form_dropdown('drp_company_list', $company_list, '-1', $companyAttr); 
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
									echo form_dropdown('drp_company_branch_list', $company_branch_list, '-1', $companyBranchAttr); 
									?>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Account Period</label>
                                    <input type="text" readonly="readonly" id="txt_acc_period" class="form-control form-control-sm" value="<?php echo $account_period; ?>" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">Provider/Supplier</label>
                                    <!--select class="form-control form-control-sm">
                                        <option>DEF Co.</option>
                                    </select-->
                                    <?php 
									$supplierAttr=array('id'=>'drp_supplier_list', 
										 'class'=>'form-control form-control-sm', 
										 'required'=>'required');
									echo form_dropdown('drp_supplier_list', $supplier_list, '-1', $supplierAttr); 
									?>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Supplier Invoice #</label>
                                    <input type="number" id="txt_supp_invoice_num" class="form-control form-control-sm" value="" />
                                </div>
                                <div class="col-md-4">
                                    <label for="">Total Invoice Value</label>
                                    <input type="text" id="txt_supp_invoice_amount" class="form-control form-control-sm" value="0" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="">Remarks</label>
                                    <input type="text" id="txt_supp_invoice_remarks" class="form-control form-control-sm" value="" />
                                </div>
                                <div class="col-md-4">
                                    <label for="">Payment Date</label>
                                    <input type="date" id="txt_supp_invoice_date" class="form-control form-control-sm" value="" />
                                </div>
                            </div>
                            
                            <div class="form-group"><hr /></div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">Account Detail</label>
                                    <!--select class="form-control form-control-sm">
                                        <option>Chart of account detail 1</option>
                                        <option>Chart of account detail 2</option>
                                    </select-->
                                    <?php 
									$childAccountAttr=array('id'=>'drp_child_account_list', 
										 'class'=>'form-control form-control-sm', 
										 'required'=>'required');
									echo form_dropdown('drp_child_account_list', $child_account_list, '-1', $childAccountAttr); 
									?>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Narration</label>
                                    <input type="text" id="txt_acc_detail_narration" class="form-control form-control-sm" value="" />
                                </div>
                                <div class="col-md-2">
                                    <label for="">Value</label>
                                    <input type="text" id="txt_acc_detail_amount" class="form-control form-control-sm" value="" />
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between" align="right">
                        	<div class="small" style="font-weight:bold;">
                            	<!--notify msgs, header-detail value mismatch etc.-->
                                <span id="lbl_warnmsg" style="padding-right:20px; font-weight:bold;">&nbsp;</span>
                            </div>
                            <div class="small text-muted">
                            	<input type="hidden" id="h_acc_period" value="" />
                                <input type="hidden" id="h_acc_detail_running_total" value="" />
                                <input type="hidden" id="h_acc_payable_main_id" value="" />
                        		<button type="submit" name="submit" class="btn btn-primary">Add<!-- Save --></button>
                            </div>
                        </div>
                    </div>
                </form>
                
                <div class="card rounded-0">
                	<div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="accDetail_dataTable" width="100%" border="0" cellspacing="0">
                          <thead>
                            <tr>
                                <th width="5%">#</th>
                            	<th>Account Detail</th>
                                <th>Narration</th>
                                <th>Value</th>
                            </tr>
                          </thead>
                          <!--tbody>
                          	<tr>
                            	<td><input type="checkbox" class="" checked="checked" value="" /></td>
                                <td>Active Detail 1</td>
                                <td>Comment 1</td>
                                <td>1000.00</td>
                            </tr>
                          </tbody-->
                        </table>
                      </div>    
                    </div>
                </div>
			</div>
            
            <!-- find company-branch -->
            <div class="modal fade" id="sectionsListModal" tabindex="-1" role="dialog" aria-labelledby="sectionsListModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl" role="document">
                <form id="frmSectionRegList" name="frmSectionRegList" method="post" action="">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="sectionsListModalLabel"><span>Choose a Section</span></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body" style="min-height:350px;">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Company</label>
                                <!--select class="form-control form-control-sm" disabled="disabled">
                                    <option>ABC Co.</option>
                                </select-->
                                <?php 
                                $companyFilterAttr=array('id'=>'drp_filter_company_list', 
                                     'class'=>'form-control form-control-sm', 
                                     'required'=>'required');
                                echo form_dropdown('drp_filter_company_list', $company_list_filter, '-1', $companyFilterAttr); 
                                ?>
                            </div>
                            <div class="col-md-8">
                                <div class="datatable table-responsive">
                                    <table class="table table-bordered table-hover dataTable no-footer" id="providers_dataTable" width="100%" cellspacing="0">
                                      <thead>
                                        <tr>
                                          <th width="30%">Section/Branch</th>
                                          <th>Code</th>
                                          
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
            <!-- find company-branch -->
            
            <!-- find records -->
            <div class="modal fade" id="assignmentListModal" tabindex="-1" role="dialog" aria-labelledby="assignmentListModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl" role="document">
                <form id="" name="" method="post" action="">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="assignmentListModalLabel"><span>Find Invoices</span></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                      	<div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Company</label>
                                    <?php 
                                    $companyFilterAttr=array('id'=>'drp_filter_company_payment_list', 
                                         'class'=>'form-control form-control-sm', 
                                         'required'=>'required');
                                    //echo form_dropdown('drp_filter_company_payment_list', $company_list_filter, '-1', $companyFilterAttr); 
                                    ?>
                                    
                                    <select class="form-control form-control-sm nest_head" id="drp_filter_company_payment_list" data-findnest="sectionsnest">
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
                                    <?php 
                                    $companyFilterAttr=array('id'=>'drp_filter_branch_payment_list', 
                                         'class'=>'form-control form-control-sm', 
                                         'required'=>'required');
                                    //echo form_dropdown('drp_filter_branch_payment_list', $company_list_filter, '-1', $companyFilterAttr); 
                                    ?>
                                    <select class="form-control form-control-sm" id="drp_filter_branch_payment_list" data-nestname="sectionsnest">
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
                            <div class="col-md-8">
                                <div class="datatable table-responsive">
                                    <table class="table table-bordered table-hover dataTable no-footer" id="allPayments_dataTable" width="100%" cellspacing="0">
                                      <thead>
                                        <tr>
                                          <th width="30%">Provider/Supplier</th>
                                          <th width="40%">Remarks</th>
                                          <th width="10%">Invoice #</th>
                                          <th>Bill Total</th>
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
            
            <!-- post records -->
            <div class="modal fade" id="paymentListPostModal" tabindex="-1" role="dialog" aria-labelledby="paymentListPostModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl" role="document">
                <form id="frmPaymentPost" name="" method="post" action="">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="paymentListPostModalLabel"><span>Approve/Post Invoices</span></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                      	<div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Company</label>
                                    <?php 
                                    $companyFilterAttr=array('id'=>'drp_filter_company_payment_list', 
                                         'class'=>'form-control form-control-sm', 
                                         'required'=>'required');
                                    //echo form_dropdown('drp_filter_company_payment_list', $company_list_filter, '-1', $companyFilterAttr); 
                                    ?>
                                    
                                    <select class="form-control form-control-sm nest_head" id="drp_filter_company_post_list" data-findnest="sectionspost" disabled="disabled">
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
                                    <?php 
                                    $companyFilterAttr=array('id'=>'drp_filter_branch_payment_list', 
                                         'class'=>'form-control form-control-sm', 
                                         'required'=>'required');
                                    //echo form_dropdown('drp_filter_branch_payment_list', $company_list_filter, '-1', $companyFilterAttr); 
                                    ?>
                                    <select class="form-control form-control-sm" id="drp_filter_branch_post_list" data-nestname="sectionspost" disabled="disabled">
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
                            <div class="col-md-8">
                                <div class="datatable table-responsive">
                                    <table class="table table-bordered table-hover dataTable no-footer" id="postPayments_dataTable" width="100%" cellspacing="0">
                                      <thead>
                                        <tr>
                                          <th width="%">#</th>
                                          <th width="30%">Provider/Supplier</th>
                                          <th width="40%">Remarks</th>
                                          <th width="10%">Invoice #</th>
                                          <th>Bill Total</th>
                                          <th width="5%">Actions</th>
                                        </tr>
                                      </thead>
                                      
                                      
                                    </table>
                                </div>
                            </div>
                        </div>
                      </div>
                      <div class="modal-footer d-flex align-items-center justify-content-between">
                        <div>
                        	<span>post-action-user-botifications</span>
                        </div>
                        <div>
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <input class="btn btn-primary" type="submit" name="btnsave" id="btnsave" value="Post Selected Payments"/>
                        </div>
                      </div>
                    </div>
                </form>
              </div>
            </div>
            <!-- post records -->
            
		</main>
		<?php include "include/footerbar.php"; ?>
	</div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
function format(d){console.log(JSON.stringify(d));
	// `d` is the original data object for the row
    var tr = '';
	if(d.length>0){
		$.each(d, function(index, obj){
			var d_status_txt = (obj.acc_payable_status==1)?'OK':'Deleted';
			tr += '<tr>'+
					'<td>'+obj.acc_detail_txt+'</td>'+
					'<td>'+obj.acc_payable_narration+'</td>'+
					'<td>'+obj.acc_payable_amount+'</td>'+
					'<td>'+d_status_txt+'</td>'+
				'</tr>';
		});
	}else{
		tr += '<tr class="msg_no_img"><td colspan="4">No payments available</td></tr>';
	}
	
	return '<table cellpadding="5" cellspacing="0" border="0" width="100%" align="right" style="padding-left:50px;">'+
        '<tr>'+
            '<th>Account Detail</th>'+
            '<th>Narration</th>'+
			'<th>Value</th>'+
			'<th>#</th>'+
        '</tr>'+
        tr+
    '</table>';
}

$(document).ready(function(){
	var providersTable = $('#providers_dataTable').DataTable({ 
				  "info": false,
				  "processing": true,
				  "serverSide": true,
				  "ajax": {
					  "url":"<?php echo base_url('scripts/company_branch_list.php');?>",//AccountPayment/provider_datatable
					  "type":"POST",//GET
				  },
				  "columns":[
						{"data":"branch_name"}, {"data":"branch_code"}, {"data":"parent_company_regno"}
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
							return '<button type="button" class="btn btn-sm btn-success btn_new_payment" data-branchreg="'+row.branch_regno+'" data-companyreg="'+row.parent_company_regno+'" data-mainid="-1"><i class="fa fa-plus"></i></button>';//data+'x'+row.parent_company_regno;
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
	$('#drp_filter_company_list').on('keyup change', function () {
			if (providersTable.column(2).search() !== this.value) {
				providersTable.column(2).search(this.value).draw();
			}
	  });
	
	var allPaymentsTable = $('#allPayments_dataTable').DataTable({ 
				  "info": false,
				  "processing": true,
				  "serverSide": true,
				  "ajax": {
					  "url":"<?php echo base_url('scripts/company_branch_all_payments_list.php');?>",//AccountPayment/provider_datatable
					  "type":"POST",//GET
				  },
				  "columns":[
						{"data":"supplier_name"}, {"data":"pay_remarks"}, {"data":"pay_invoice"}, 
						{"data":"pay_invoice_total"}, //{"data":"parent_company_regno"}, 
						{"data":"sect_key"} //{"data":"branch_regno"}
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
				  
				  "columnDefs": [/*{
						"targets":3,
						render: function( data, type, row ){
							if(type=="display"){
								return data;//+'x'+row.parent_company_regno+'y';
							}else{
								return row.parent_company_regno;
							}
							/-*
							return data;
							*-/
						}
					},*/{
						"targets":4,
						render: function( data, type, row ){
							return '<button type="button" class="btn btn-sm btn-success btn_edit_payment" data-branchreg="'+row.branch_regno+'" data-companyreg="'+row.parent_company_regno+'" data-mainid="'+row.pay_regid+'"><i class="fa fa-edit"></i></button>';//data+'x'+row.parent_company_regno;
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
	$('#drp_filter_company_payment_list, #drp_filter_branch_payment_list').on('keyup change', function () {
			var c = $("#drp_filter_company_payment_list").find(":selected").val();
			var d = $("#drp_filter_branch_payment_list").find(":selected").val();
			if (allPaymentsTable.column(4).search() !== (c+"_"+d)) {
				allPaymentsTable.column(4).search((c+"_"+d)).draw();
			}
	  });
	
	var accDetailTable = $("#accDetail_dataTable").DataTable({
				"info":false,
				"searching":false,
				"paging":false,
				"columns":[
						{"data":"acc_payable_status"}, {"data":"acc_detail_txt"}, 
						{"data":"acc_payable_narration"}, {"data":"acc_payable_amount"}
					],
				"columnDefs":[{
							"targets":0,
							"render":function( data, type, row ){
								var check_str = (data==1)?'checked="checked"':'';
								return '<input type="checkbox" class="chk_accd" value="'+data+'" '+
									'data-payableid="'+row.acc_payable_id+'" '+
									check_str+'/>';
							}
					}],
				"createdRow": function( row, data, dataIndex ){
					$( row ).attr('id', 'chk_accd-'+data.acc_detail_id);
				}
			});
	
	var postPaymentsTable = $('#postPayments_dataTable').DataTable({ 
				  "info": true,
				  "columns":[
						{"data":"opt_select"}, {"data":"supplier_name"}, {"data":"pay_remarks"}, {"data":"pay_invoice"}, 
						{"data":"pay_invoice_total"}, //{"data":"parent_company_regno"}, 
						{"data":"sect_key"} //{"data":"branch_regno"}
					],
				  "order":[[0,'desc']],
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
							"targets":0,
							"render":function( data, type, row ){
								if(type=='display'){
								var check_str = (data==1)?'checked="checked"':'';
								return '<input type="checkbox" class="chk_post" value="'+data+'" '+
									'data-payableid="'+row.acc_payable_id+'" '+
									check_str+'/>';
								}else{
									return data;
								}
							}
					},{
						"targets":5,
						render: function( data, type, row ){
							return '<button type="button" class="btn btn-sm btn-success act_bundle" data-branchreg="'+row.branch_regno+'" data-companyreg="'+row.parent_company_regno+'" data-mainid="'+row.pay_regid+'"><i class="fa fa-info"></i></button>';//data+'x'+row.parent_company_regno;
						}
					}], 
				  
			  });
	
	function prep_childacc_optlist(optlist){
		//$("#drp_child_account_list").prop("disabled", false);
		//$("#drp_child_account_list").empty();
		$("#drp_child_account_list").html('<option value="-1" data-parentacc="">'+
										  'Select</option>');
		$.each(optlist, function(index, obj){
				var str='<option value="'+obj.form_key;
				str+='" data-parentacc="'+obj.parent_account_id;
				//str+='" '+obj.cust_attr;
				//str+=' data-nestcode="'+obj.style_refno;
				//str+='" data-upcrefno="'+obj.upc_regno;
				str+='" >';
				str+=obj.form_val+'</option>';//escapeTags(obj.form_val)
				$("#drp_child_account_list").append(str);
				
				
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
	
	$(document).on("click", ".act_bundle", function(){
		var par = $(this).parent().parent();
		
		var tr = $(this).parent().closest('tr');
		var row = postPaymentsTable.row( tr );
		
		$.ajax({
			method: "POST",
			url: '<?php echo base_url('AccountPayment/review'); ?>',
			dataType:'JSON',
			data: {selected_opt:$(this).data('mainid')},
			beforeSend: function(){
				/*
				
				*/
			}
		}).done(function(data){
			if ( row.child.isShown() ) {
				// This row is already open - close it
				row.child.hide();
				tr.removeClass('shown');
				
			 }
			 else {
				//var imgname=(data.download_res==0)?data.img_res:'icon'; 
				// Open this row
				row.child( format(data.view_detail_data) ).show();
				tr.addClass('shown');
				
			 }
		});
		
	});
	
	
	//to-do-block
	$(".running-tot-elems").on('keyup change', function(){
		//calculate-remaining-amount-of-header-detail-sum-values
	});
	
	
	
	$(document).on("click", ".btn_new_payment, .btn_edit_payment", function(){
		var mainid = $(this).data('mainid');
		var companyreg = $(this).data('companyreg');
		var branchreg = $(this).data('branchreg');
		
		
		$.ajax({
			method: "POST",
			url: "<?php echo base_url('AccountPayment/create'); ?>",
			data:{company_reg:companyreg, branch_reg:branchreg, acc_payable_main_id:mainid},
			dataType:"JSON"
		}).done(function(data){
			$("#drp_company_list").val(companyreg);
			$("#drp_company_branch_list").val(branchreg);
			$("#drp_supplier_list").val(data.view_header_data.supplier);//''
			$("#txt_acc_period").val(data.account_period_txt);
			$("#txt_supp_invoice_num").val(data.view_header_data.invoiceno);//''
			$("#txt_supp_invoice_amount").val(data.view_header_data.amount);//0
			$("#txt_supp_invoice_remarks").val(data.view_header_data.batchno);//''
			$("#txt_supp_invoice_date").val(data.view_header_data.tradate);//''
			
			prep_childacc_optlist(data.child_accounts);
			
			$("#txt_acc_detail_narration").val('');
			$("#txt_acc_detail_amount").val('');
			
			$("#h_acc_period").val(data.account_period_id);
			$("#h_acc_detail_running_total").val(0);
			$("#h_acc_payable_main_id").val(data.view_header_data.idtbl_account_payable_main);
			
			accDetailTable.clear();
			accDetailTable.rows.add(data.view_detail_data);
			accDetailTable.draw();
			
			$("#lbl_warnmsg").html('&nbsp;');
			
			if($("#h_acc_payable_main_id").val()==''){
				$("#sectionsListModal").modal('hide');
			}else{
				$("#assignmentListModal").modal('hide');
			}
		});
		
	});
	
	$("#frmPaymentInfo").submit(function(event){
		event.preventDefault();
		var companyId = $("#drp_company_list").find(":selected").val();
		var companyBranchId = $("#drp_company_branch_list").find(":selected").val();
		var supplierId = $("#drp_supplier_list").find(":selected").val();
		var suppInvoice = $("#txt_supp_invoice_num").val();
		var suppInvoiceAmount = $("#txt_supp_invoice_amount").val();
		var suppInvoiceRemarks = $("#txt_supp_invoice_remarks").val();
		var suppInvoiceDate = $("#txt_supp_invoice_date").val();
		var childAccountId = $("#drp_child_account_list").find(":selected").val();
		var childAccountName = $("#drp_child_account_list").find(":selected").text();
		var accDetailNarration = $("#txt_acc_detail_narration").val();
		var accDetailAmount = $("#txt_acc_detail_amount").val();
		var accPeriod = $("#h_acc_period").val();
		var accDetailRunningTotal = $("#h_acc_detail_running_total").val();
		var accPayableMainId = $("#h_acc_payable_main_id").val();
		
		$.ajax({
			method: "POST",
			url: "<?php echo base_url('AccountPayment/store'); ?>",
			data:{company_id:companyId, company_branch_id:companyBranchId, supplier_id:supplierId,
				supp_invoice:suppInvoice, supp_invoice_amount:suppInvoiceAmount, supp_invoice_remarks:suppInvoiceRemarks,
				supp_invoice_date:suppInvoiceDate,
				child_account_id:childAccountId, acc_detail_narration:accDetailNarration, acc_detail_amount:accDetailAmount,
				acc_detail_running_total:accDetailRunningTotal, 
				acc_period:accPeriod, acc_payable_main_id:accPayableMainId},
			dataType:"JSON",
			beforeSend: function(){
			}
		}).done(function(data){
			//alert("Batch number is "+data);
			var rres = data.resMsg;
			if(rres!=""){
				$("#lbl_warnmsg").html(rres);//alert(rres);//"something wrong"
			}else{
				if(accPayableMainId==""){
					$("#h_acc_payable_main_id").val(data.head_k);
				}
				
				$("#lbl_warnmsg").html('&nbsp;');
				
				if(data.sub_k>0){
					var selected_tr = accDetailTable.row('#chk_accd-'+childAccountId);
					if(selected_tr.length==0){
						var rowNode = accDetailTable.row.add( {
								"acc_payable_id":data.sub_k,
								"acc_payable_narration":accDetailNarration,
								"acc_payable_amount":accDetailAmount,
								"acc_payable_status":1,
								"acc_detail_id":childAccountId, 
								"acc_detail_txt":childAccountName
							}).draw( false ).node();
					}else{
						var d=selected_tr.data();
						d.acc_payable_status = 1;
						d.acc_payable_narration = accDetailNarration;
						d.acc_payable_amount = accDetailAmount;
						
						accDetailTable.row(selected_tr).data(d).draw();
					}
					
					$("#lbl_warnmsg").html("&nbsp;");
					
					$("#txt_acc_detail_narration").val("");
					$("#txt_acc_detail_amount").val("");
					
				}
			}
		});
	});
	
	$("#btnOrderApprovePost").on("click", function(){
		var mainid = $("#h_acc_payable_main_id").val();
		
		$.ajax({
			method: "POST",
			url: "<?php echo base_url('AccountPayment/invoices'); ?>",
			data:{selected_opt:mainid},
			dataType:"JSON"
		}).done(function(data){
			postPaymentsTable.clear();
			postPaymentsTable.rows.add(data.view_post_data);
			postPaymentsTable.draw();
			$("#paymentListPostModal").modal('show');
		});
	})
	
	$("#frmPaymentPost").submit(function(event){
		event.preventDefault();
		alert('to-do:post selected payments code');
	});
	
});
</script>
<?php include "include/footer.php"; ?>
