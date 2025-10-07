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
							<span>Bank Reconciliation</span>
                            <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right dropdown-toggle" id="btnorderacts" style="position:absolute; right:10px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-plus"></i>&nbsp;Actions</button>
                            <div class="dropdown-menu" aria-labelledby="btnorderacts">
                            	<a class="dropdown-item" id="btnOngoingRecs" data-toggle="modal" data-target="#bankAccListModal" data-refid="-1" href="javascript:void(0);" title="Ongoing bank reconciliation">Ongoing Bank Recs.</a>
                            	<a class="dropdown-item" id="btnConfirmedRecs" data-toggle="modal" data-target="#bankRecListModal" href="javascript:void(0);" title="Search confirmed bank reconciliations">Completed Recs.</a>
                                <a class="dropdown-item" id="btnApproveRecs" href="javascript:void(0);">Approve</a>
                                <!--a class="dropdown-item" id="btnOrderDelete" data-refid="-1" href="javascript:void(0);">Delete</a-->
                            </div>
						</h1>
					</div>
				</div>
			</div>
			<div class="container-fluid mt-2 p-0 p-2">
				<form id="frmRecInfo" method="post">
                    <div class="card rounded-0">
                        <div class="card-body p-0 p-2">
                            <div class="">
                            	<div class="form-group row">
                                	<label class="col-md-3">Bank Account & Rec. Period</label>
                                    <div class="col-md-6">
                                    	<select class="form-control form-control-sm" id="drp_bank_accounts" disabled="disabled">
                                        	<option value="-1">Select</option>
                                            <?php 
											foreach($company_bank_account_list as $qf){
											?>
											<option class="" value="<?php echo $qf->idtbl_account; ?>"><?php echo $qf->accountname; ?></option>
											<?php 	
											}
											?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                    	<input type="text" class="form-control form-control-sm" id="txt_bank_rec_period" value="" readonly="readonly" />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                            	<div class="col-md-3">
                                	<label>Statement Open Bal.</label>
                                    <input type="text" class="form-control form-control-sm stmt_bal_input" id="txt_statement_open_bal" value="" required />
                                </div>
                                <div class="col-md-3">
                                	<label>CR.</label>
                                    <input type="text" class="form-control form-control-sm stmt_bal_input" id="txt_statement_tot_cr" value="" required />
                                </div>
                                <div class="col-md-3">
                                	<label>DR.</label>
                                    <input type="text" class="form-control form-control-sm stmt_bal_input" id="txt_statement_tot_dr" value="" required />
                                </div>
                                <div class="col-md-3">
                                	<label>Statement Closed Bal.</label>
                                    <input type="text" class="form-control form-control-sm stmt_bal_output" id="txt_statement_closed_bal" value="" readonly="readonly" />
                                </div>
                            </div>
                            
                            <div class="row">
                            	<div class="col-md-3">
                                	<label>Account Open Bal.</label>
                                    <input type="text" class="form-control form-control-sm" id="txt_account_open_bal" value="" readonly="readonly" />
                                </div>
                                <div class="col-md-3">
                                	<label>CR.</label>
                                    <input type="text" class="form-control form-control-sm" id="txt_account_tot_cr" value="" readonly="readonly" />
                                </div>
                                <div class="col-md-3">
                                	<label>DR.</label>
                                    <input type="text" class="form-control form-control-sm" id="txt_account_tot_dr" value="" readonly="readonly" />
                                </div>
                                <div class="col-md-3">
                                	<label>Account Closed Bal.</label>
                                    <input type="text" class="form-control form-control-sm" id="txt_account_closed_bal" value="" readonly="readonly" />
                                </div>
                            </div>
                            
                            <div class="row" style="margin-top:10px;">
                                <div class="col-md-3">
                                    <!--label>Rec. Date</label-->
                                    <fieldset>
                                    	<legend class="small">Rec. Date</legend>
                                        <input type="date" class="form-control form-control-sm" id="txt_bank_rec_date" value="" />
                                    </fieldset>
                                </div>
                                <div class="col-md-9">
                                    <fieldset>
                                        <legend class="small">Differences</legend>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <input type="text" class="form-control form-control-sm" id="txt_diff_cr" value="" readonly="readonly" />
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control form-control-sm" id="txt_diff_dr" value="" readonly="readonly" />
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control form-control-sm" id="txt_diff_closed_bal" value="" readonly="readonly" />
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            
                            <div class="row" style="margin-top:15px;">
                                <div class="col-md-3">
                                	<label>Branch</label>
                                    <select class="form-control form-control-sm" id="drp_company_branch_list">
                                    	<option value="">Select Branch</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-3">
                                    <label for="">Account</label>
                                    <label for="drp_rec_value_dr" style="float:right;" title="Select for debit account"><input type="radio" name="drp_rec_value_group" id="drp_rec_value_dr" class="" value="" data-crkey="0" data-drkey="1" style="margin-right:3px; width:18px; height:18px; position:relative; top:3px;" />DR</label>
                                    <label for="drp_rec_value_cr" style="float:right; margin-right:15px;" title="Select for credit account"><input type="radio" name="drp_rec_value_group" id="drp_rec_value_cr" class="" value="" data-crkey="1" data-drkey="0" style="margin-right:3px; width:18px; height:18px; position:relative; top:3px;" checked="checked" />CR</label>
                                    <!--select class="form-control form-control-sm">
                                        <option>Chart of account detail 1</option>
                                        <option>Chart of account detail 2</option>
                                    </select-->
                                    <?php 
									$mainAccountAttr=array('id'=>'drp_account_list', 
										 'class'=>'form-control form-control-sm', 
										 'required'=>'required');
									echo form_dropdown('drp_account_list', $main_accounts, '-1', $mainAccountAttr); 
									?>
                                </div>
                                
                                <div class="col-md-3">
                                    <label for="">Narration</label>
                                    <input type="text" id="txt_bank_narration" class="form-control form-control-sm" value="" />
                                </div>
                                <div class="col-md-3">
                                    <label for="">Value</label>
                                    <input type="text" id="txt_bank_amount" class="form-control form-control-sm" value="" />
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer d-flex align-items-center justify-content-between" align="right">
                        	<div class="small" style="font-weight:bold;">
                            	<!--notify msgs, header-detail value mismatch etc.-->
                                <span id="lbl_warnmsg" style="padding-right:20px; font-weight:bold;">&nbsp;</span>
                            </div>
                            <div class="small text-muted">
                            	<input type="hidden" id="h_rec_period_year" value="" />
                                <input type="hidden" id="h_rec_period_month" value="" />
                                <input type="hidden" id="h_rec_detail_cr_running_total" value="" />
                                <input type="hidden" id="h_rec_detail_dr_running_total" value="" />
                                <input type="hidden" id="h_rec_main_id" value="" />
                        		<button type="submit" name="submit" class="btn btn-primary">Save</button>
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
                            	<th>Acc. Period</th>
                                <th>Narration</th>
                                <th>Transaction Date</th>
                                <th>CR</th>
                                <th>DR</th>
                            </tr>
                          </thead>
                          
                        </table>
                      </div>    
                    </div>
                </div>
                
			</div>
            
            <!-- find ongoing rec. bank-account -->
            <div class="modal fade" id="bankAccListModal" tabindex="-1" role="dialog" aria-labelledby="bankAccListModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl" role="document">
                <form id="" name="" method="post" action="">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="bankAccListModalLabel"><span>Choose an Account</span></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body" style="min-height:350px;">
                        <div class="row">
                            
                            <div class="col-md-12">
                                <div class="datatable table-responsive">
                                    <table class="table table-bordered table-hover dataTable no-footer" id="bankAccs_dataTable" width="100%" cellspacing="0">
                                      <thead>
                                        <tr>
                                          <th width="30%">Bank Account Name</th>
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
            <!-- find ongoing rec. bank-account -->
            
            <!-- find confirmed rec. bank-account -->
            <div class="modal fade" id="bankRecListModal" tabindex="-1" role="dialog" aria-labelledby="bankRecListModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl" role="document">
                <form id="" name="" method="post" action="">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="bankRecListModalLabel"><span>Choose an Account Rec.</span></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body" style="min-height:350px;">
                        <div class="row">
                            
                            <div class="col-md-12">
                                <div class="datatable table-responsive">
                                    <table class="table table-bordered table-hover dataTable no-footer" id="bankRecs_dataTable" width="100%" cellspacing="0">
                                      <thead>
                                        <tr>
                                          <th width="30%">Bank Account Name</th>
                                          <th>Code</th>
                                          <th>Rec. Period</th>
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
            <!-- find confirmed rec. bank-account -->
            
            
		</main>
		<?php include "include/footerbar.php"; ?>
	</div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
$(document).ready(function(){
	var bankAccsTable = $("#bankAccs_dataTable").DataTable({ 
				  "info": false,
				  "processing": true,
				  "serverSide": true,
				  "autoWidth":false,
				  "ajax": {
					  "url":"<?php echo base_url('scripts/bank_accounts_list.php');?>",//AccountPayment/provider_datatable
					  "type":"POST",//GET
				  },
				  "columns":[
						{"data":"bankacc_name"}, {"data":"bankacc_accountno"}, //{"data":"pay_invoice"}, 
						{"data":"bankacc_regno", "className":'text-center', "width":'5%'} //{"data":"branch_regno"}
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
							return '<button type="button" class="btn btn-sm btn-success btn_new_bankrec" data-mainid="" data-bankaccid="'+data+'"><i class="fa fa-edit"></i></button>';//data+'x'+row.parent_company_regno;
						}
					}], 
				  
			  });
	
	var bankRecsTable = $("#bankRecs_dataTable").DataTable({ 
				  "info": false,
				  "processing": true,
				  "serverSide": true,
				  "autoWidth":false,
				  "ajax": {
					  "url":"<?php echo base_url('scripts/confirmed_bank_recs_list.php');?>",//AccountPayment/provider_datatable
					  "type":"POST",//GET
				  },
				  "columns":[
						{"data":"bankacc_name"}, {"data":"bankacc_accountno"}, //{"data":"pay_invoice"}, 
						{"data":"bankrec_batchno", "width":'20%'}, //{"data":"parent_company_regno"}, 
						{"data":"bankrec_regno", "className":'text-center', "width":'5%'} //{"data":"branch_regno"}
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
						"targets":3,
						render: function( data, type, row ){
							return '<button type="button" class="btn btn-sm btn-success btn_edit_bankrec" data-mainid="'+data+'" data-bankaccid="'+row.bankacc_regno+'"><i class="fa fa-edit"></i></button>';//data+'x'+row.parent_company_regno;
						}
					}], 
				  
			  });
	
	var accDetailTable = $("#accDetail_dataTable").DataTable({
				"info":false,
				"searching":false,
				"paging":false,
				"columns":[
						{"data":"transaction_id"}, {"data":"acc_period_txt"}, 
						{"data":"narration_txt"}, 
						{"data":"transaction_date"},
						{"data":"cr_val", "className":'text-right'}, 
						{"data":"dr_val", "className":'text-right'}
					],
				"columnDefs":[{
							"targets":0,
							"className":"text-center",
							"render":function( data, type, row ){
								if(row.opt_render=='chkinput'){
									var check_str = (row.rec_info_status==1)?'checked="checked"':'';
									return '<input type="checkbox" class="chk_accd" value="'+data+'" '+
										'data-recid="'+row.rec_info_id+'" data-revisestatus="'+row.rec_revise_status+'" '+ // 0
										'data-reviseuser="" data-revisetime="" '+
										check_str+'/>';
								}else if(row.opt_render=='btn'){
									return '<button class="btn btn-sm btn-danger btn_del_bank_amount" '+
										'data-revisionid="'+row.rec_revision_id+'">'+'<i class="fa fa-trash"></i></button>';
								}
							}
						}, {
							"targets":4,
							"render":function( data, type, row ){
								var col_value = parseFloat(data);
								return col_value.toFixed(2);
							}
					}, {
							"targets":5,
							"render":function( data, type, row ){
								var col_value = parseFloat(data);
								return col_value.toFixed(2);
							}
					}],
				"createdRow": function( row, data, dataIndex ){
					if(data.transaction_id!=''){
						$( row ).attr('id', data.opt_dtprefix+'-'+data.transaction_id);
					}else if(data.rec_revision_id!=''){
						$( row ).attr('id', 'rec_accd-'+data.rec_revision_id);
					}
				}
			});
	
	function fnum(txtnum, dp){
		var p = parseFloat(txtnum);
		
		if(dp>0){
			return (isNaN(p)?'0.00':p.toFixed(dp));
		}else{
			return (isNaN(p)?0:p);
		}
	}
	
	function calc_statement_closed_bal(){
		var o = fnum($("#txt_statement_open_bal").val(), 0);
		var c = fnum($("#txt_statement_tot_cr").val(), 0);
		var d = fnum($("#txt_statement_tot_dr").val(), 0);
		var r = fnum((o+c)-d, 2);
		
		$("#txt_statement_closed_bal").val(r);
		
		calc_acc_diff_val();
	}
	
	function calc_statement_open_bal(){
		var i = fnum($("#txt_statement_closed_bal").val(), 0);
		var c = fnum($("#txt_statement_tot_cr").val(), 0);
		var d = fnum($("#txt_statement_tot_dr").val(), 0);
		var r = fnum((i+d)-c, 2);
		
		$("#txt_statement_open_bal").val(r);
		
		calc_acc_diff_val();
	}
	
	function calc_account_closed_bal(){
		var o = fnum($("#txt_account_open_bal").val(), 0);
		var c = fnum($("#h_rec_detail_cr_running_total").val(), 0);//fnum($("#txt_account_tot_cr").val(), 0);
		var d = fnum($("#h_rec_detail_dr_running_total").val(), 0);//fnum($("#txt_account_tot_dr").val(), 0);
		var r = fnum((o+c)-d, 2);
		
		$("#txt_account_tot_cr").val(fnum(c, 2));
		$("#txt_account_tot_dr").val(fnum(d, 2));
		$("#txt_account_closed_bal").val(r);
		
		calc_acc_diff_val();
	}
	
	function update_acc_detail_running_total(c, d){
		var hc = fnum($("#h_rec_detail_cr_running_total").val(), 0);
		var hd = fnum($("#h_rec_detail_dr_running_total").val(), 0);
		
		var nc = hc+c;
		var nd = hd+d;
		
		$("#h_rec_detail_cr_running_total").val(nc);
		$("#h_rec_detail_dr_running_total").val(nd);
		
		calc_account_closed_bal();
	}
	
	function calc_acc_diff_val(){
		var cs = fnum($("#txt_statement_tot_cr").val(), 0);
		var ds = fnum($("#txt_statement_tot_dr").val(), 0);
		var rs = fnum($("#txt_statement_closed_bal").val(), 0);
		
		var ca = fnum($("#txt_account_tot_cr").val(), 0);
		var da = fnum($("#txt_account_tot_dr").val(), 0);
		var ra = fnum($("#txt_account_closed_bal").val(), 0);
		
		var cf = fnum(cs-ca, 2);
		var df = fnum(ds-da, 2);
		var rf = fnum(rs-ra, 2);
		
		$("#txt_diff_cr").val(cf);
		$("#txt_diff_dr").val(df);
		$("#txt_diff_closed_bal").val(rf);
	}
	
	function prep_accperiod_optlist(optlist){
		if(optlist.length>0){
			$("#drp_company_branch_list").empty();
			$.each(optlist, function(index, obj){
				var str='<option value="'+obj.form_key;
				//str+='" data-parentacc="';//+obj.parent_account_id;
				//str+='" '+obj.cust_attr;
				//str+=' data-nestcode="'+obj.style_refno;
				//str+='" data-upcrefno="'+obj.upc_regno;
				
				str+='" >';
				str+=obj.form_val+'</option>';//escapeTags(obj.form_val)
				$("#drp_company_branch_list").append(str);
				
				
			});
		}else{
			$("#drp_company_branch_list").html('<option value="">Select Branch</option>');
		}
	}
	
	$(".stmt_bal_input").on("keyup change", function(){
		calc_statement_closed_bal();
	});
	
	$(".stmt_bal_output").on("keyup change", function(){
		calc_statement_open_bal();
	});
	
	$(document).on("click", ".btn_new_bankrec, .btn_edit_bankrec", function(){
		var mainid = $(this).data('mainid');
		var bankaccid = $(this).data('bankaccid');;
		
		$.ajax({
			method:"POST",
			url:"<?php echo base_url('BankReconciliation/create'); ?>",
			data:{main_id:mainid, bankacc_id:bankaccid},
			dataType:"JSON"
		}).done(function(data){
			$("#drp_bank_accounts").val(bankaccid);
			$("#txt_bank_rec_period").val(data.bank_rec_period_txt);
			$("#txt_statement_open_bal").val(fnum(data.view_header_data.statement_open_bal, 2));
			$("#txt_statement_tot_cr").val(fnum(data.view_header_data.statement_tot_cr, 2));
			$("#txt_statement_tot_dr").val(fnum(data.view_header_data.statement_tot_dr, 2));
			$("#txt_statement_closed_bal").val(fnum(data.view_header_data.statement_closed_bal, 2));
			
			$("#txt_account_open_bal").val(data.view_header_data.acc_open_bal);
			$("#txt_account_tot_cr").val(data.acc_tot_cr);
			$("#txt_account_tot_dr").val(data.acc_tot_dr);
			
			$("#txt_bank_rec_date").val(data.view_header_data.bank_rec_date);
			
			prep_accperiod_optlist(data.acc_periods);
			
			$("#h_rec_period_year").val(data.view_header_data.tbl_finacial_year_idtbl_finacial_year);
			$("#h_rec_period_month").val(data.view_header_data.tbl_finacial_month_idtbl_finacial_month);
			$("#h_rec_detail_cr_running_total").val(data.acc_tot_cr);
			$("#h_rec_detail_dr_running_total").val(data.acc_tot_dr);
			$("#h_rec_main_id").val(data.view_header_data.idtbl_bank_rec_list);
			
			//datatable
			accDetailTable.clear();
			accDetailTable.rows.add(data.view_detail_data);
			accDetailTable.draw();
			
			calc_statement_closed_bal();
			calc_account_closed_bal();
			
			$("#lbl_warnmsg").html('&nbsp;');
			
			//if($("#h_rec_main_id").val()=='')
			if(mainid==""){
				$("#bankAccListModal").modal('hide');
			}else{
				$("#bankRecListModal").modal('hide');
			}
		});
	});
	
	$("#frmRecInfo").submit(function(event){
		event.preventDefault();
		/**/
		var rec_info_cancel = 2; // new status
		//get revisestatus changed options
		var revisedRows = $('.chk_accd[data-revisestatus="1"]').map(function(i, el) {
							var selected_tr = accDetailTable.row($(this).parent().closest('tr'));
							var d = selected_tr.data();
							//console.log(d.acc_payable_amount);
							
							
							var newstatus = $(this).is(":checked")?1:rec_info_cancel;//1:0
							var origin_name = d.opt_origin;
							return {
								idtbl_bank_rec_info:$(el).attr('data-recid'), 
								tbl_account_transaction_idtbl_account_transaction:$(el).val(),
								rec_info_origin_name:origin_name,
								status:newstatus,
								updateuser:$(el).attr('data-reviseuser'), 
								updatedatetime:$(el).attr('data-revisetime')
							};
							
						}).get();
		//console.log(revisedRows);
		
		var bankRecDate = $("#txt_bank_rec_date").val();
		var statementOpenBal = $("#txt_statement_open_bal").val();
		var statementTotCr = $("#txt_statement_tot_cr").val();
		var statementTotDr = $("#txt_statement_tot_dr").val();
		var statementClosedBal = $("#txt_statement_closed_bal").val();
		var recAccId = $("#drp_bank_accounts").find(":selected").val();
		var recPeriodYear = $("#h_rec_period_year").val();
		var recPeriodMonth = $("#h_rec_period_month").val();
		var recMainId = $("#h_rec_main_id").val();
		
		var mainAccountId = $("#drp_account_list").find(":selected").val();
		var crkey = $('input[name="drp_rec_value_group"]:checked').data('crkey');
		//console.log(crkey);
		var drkey = $('input[name="drp_rec_value_group"]:checked').data('drkey');
		var accPeriod = $("#drp_company_branch_list").find(":selected").val();
		var mainAccountNarration = $("#txt_bank_narration").val();
		var bankAmount = $("#txt_bank_amount").val();
		
		//dr==1
		var accountCr = recAccId;
		var accountDr = mainAccountId;
		//bank-acc-val
		var amountCr = bankAmount;
		var amountDr = 0;
		//dr==0
		if(drkey=='0'){
			accountCr = mainAccountId;
			accountDr = recAccId;
			amountCr = 0;
			amountDr = bankAmount;
		}
		
		$.ajax({
			method: "POST",
			url: "<?php echo base_url('BankReconciliation/store'); ?>",
			data:{bank_rec_date:bankRecDate, statement_open_bal:statementOpenBal, 
				statement_tot_cr:statementTotCr, statement_tot_dr:statementTotDr, 
				statement_closed_bal:statementClosedBal,
				rec_acc_id:recAccId, rec_period_year:recPeriodYear, rec_period_month:recPeriodMonth, 
				rec_main_id:recMainId, 
				main_account_id:mainAccountId, account_cr:accountCr, account_dr:accountDr, 
				acc_period:accPeriod, main_account_narration:mainAccountNarration, bank_amount:bankAmount, 
				revised_rows:revisedRows},
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
				if(recMainId==""){
					$("#h_rec_main_id").val(data.head_k);
				}
				
				$("#lbl_warnmsg").html('Changes saved.');//'&nbsp;'
				
				
				//go through revisestatus changed options to 
				//update acc-payable-status to 0 or 1 based on particular .chk-accd checked attr and
				//reset data-revisestatus to 0
				if(data.view_detail_data=='-1'){
					$('.chk_accd[data-revisestatus="1"]').each(function(index, value){
						var newstatus = $(this).is(":checked")?1:2;//1:0
						var selected_tr = accDetailTable.row($(this).parent().closest('tr'));
						var d = selected_tr.data();
						d.rec_info_status = newstatus;
						d.rec_revise_status = '0';
						accDetailTable.row(selected_tr).data(d).draw();
						
						//$(this).attr('data-revisestatus', '0');
					});
				}else{
					accDetailTable.clear();
					accDetailTable.rows.add(data.view_detail_data);
					accDetailTable.draw();
				}
				
				var cr_val = fnum(amountCr, 0);//0;
				var dr_val = fnum(amountDr, 0);//0;
				update_acc_detail_running_total(cr_val, dr_val);
				calc_account_closed_bal();
				
				if(data.sub_k>0){
					var rowNode = accDetailTable.row.add( {
							"rec_info_id":'', "rec_revision_id":data.sub_k,
							"transaction_id":'',
							"narration_txt":mainAccountNarration,
							"transaction_date":'-', "acc_period_txt":'-',
							"cr_val":amountCr, 
							"dr_val":amountDr,
							"rec_info_status":1,
							"rec_revise_status":0,
							"opt_render":"btn",
							"opt_origin":"origin_blank"
						}).draw( false ).node();
				
				//$("#lbl_warnmsg").html("&nbsp;");
				$("#drp_account_list").val('-1');
				$("#txt_bank_narration").val("");
				$("#txt_bank_amount").val("");
				
				}
			}
		});
		
	});
	
	$(document).on('click', '.chk_accd', function(){
		var revisestatus = $(this).data('revisestatus');
		var selected_tr = accDetailTable.row($(this).parent().closest('tr'));
		var d = selected_tr.data();
		//console.log(d.acc_payable_amount);
		
		var exp_reg_info_status = (d.rec_info_id=='')?1:0;
		var old_rec_info_status = parseInt(d.rec_info_status)+parseInt(exp_reg_info_status);
		
		var new_rec_info_status = $(this).is(":checked")?1:2;//1:0
		var CHK_REC_INFO_STATUS = $(this).is(":checked")?1:0
		/*
		console.log((old_rec_info_status)+'x'+new_rec_info_status);
		*/
		var new_revisestatus = (old_rec_info_status!=new_rec_info_status)?1:0;
		
		var obj_chk_accd = $(this);
		
		$.ajax({
			method:"POST", 
			url:"<?php echo base_url('AccountPayment/toggle'); ?>",
			dataType:"JSON"
		}).done(function(data){
			if(data.toggle_status=='success'){
				$(obj_chk_accd).attr('data-revisestatus', new_revisestatus);//this
				$(obj_chk_accd).attr('data-reviseuser', data.revise_user);//this
				$(obj_chk_accd).attr('data-revisetime', data.revise_time);//this
				
				var rec_detail_cr_val = (d.cr_val>0)?(parseFloat(d.cr_val)*(CHK_REC_INFO_STATUS+(CHK_REC_INFO_STATUS-1))):0;
				var rec_detail_dr_val = (d.dr_val>0)?(parseFloat(d.dr_val)*(CHK_REC_INFO_STATUS+(CHK_REC_INFO_STATUS-1))):0;
				
				update_acc_detail_running_total(rec_detail_cr_val, rec_detail_dr_val);
			}else{
				$("#lbl_warnmsg").html('Something wrong');
				$(obj_chk_accd).prop("checked", !($(obj_chk_accd).is(":checked")));
			}
		});
	});
	
	$(document).on("click", ".btn_del_bank_amount", function(){
		var refno = $(this).data('revisionid');
		
		var selected_tr=accDetailTable.row('#rec_accd-'+refno);//$('tr.row_high');//#btn_delitem
		
		if(selected_tr.length>0){
			var d = selected_tr.data();
			var revision_cr = fnum(d.cr_val, 0)*(-1);
			var revision_dr = fnum(d.dr_val, 0)*(-1);
			var item_desc=d.narration_txt+' CR'+d.cr_val+" DR"+d.dr_val;
			var del_res=confirm("Delete "+item_desc);
			if(del_res){
				//var itemref=($(".chkAct:checked").val());//#btn_delitem
				var itemref=refno;
				//console.log(">>"+itemref);
				
				$.ajax({
					method: "POST",
					url: "<?php echo base_url('BankReconciliation/destroy'); ?>",
					data: {item_ref:itemref},
					dataType: 'JSON', 
					beforeSend: function(){
						$("#lbl_warnmsg").html('<i class="fas fa fa-spinner fa-spin"></i>&nbsp;Please wait...').fadeIn();
					}
				}).done(function(data){
					var rres = data.resMsg;//.split(","); 
					if(data.msgErr){
						$("#lbl_warnmsg").html(rres);//alert(rres);//
						
					}else{
						accDetailTable.row(selected_tr).remove().draw();
						update_acc_detail_running_total(revision_cr, revision_dr);
						$("#lbl_warnmsg").html(item_desc+' deleted').delay(3000).fadeOut();
					}
				});
			}
		}else{
			$("#lbl_warnmsg").html("Select the bank record you want to delete").fadeIn();
		}
	});
	
	$("#btnApproveRecs").on("click", function(){
		if($("#h_rec_main_id").val()!=''){
			var opt_approve = confirm('Are you sure you want to approve this reconciliation?');
			
			if(opt_approve){
				var revisedRows = ($('.chk_accd[data-revisestatus="1"]').length == 0)?0:1;
				var mainid = $("#h_rec_main_id").val();
				var statementOpenBal = $("#txt_statement_open_bal").val();
				var statementCr = $("#txt_statement_tot_cr").val();
				
				var recPeriodYear = $("#h_rec_period_year").val();
				var recPeriodMonth = $("#h_rec_period_month").val();
				
				var accCr = $("#txt_account_tot_cr").val();//statementCr;//
				var statementDr = $("#txt_statement_tot_dr").val();
				var accDr = $("#txt_account_tot_dr").val();//statementDr;//
				var statementClose = $("#txt_statement_closed_bal").val();
				var accClose = $("#txt_account_closed_bal").val();//statementClose;//
				
				$.ajax({
					method: "POST",
					url: "<?php echo base_url('BankReconciliation/freeze'); ?>",
					data:{revised_rows:revisedRows, selected_opt:mainid, exp_rows:0, 
						statement_open_bal:statementOpenBal,
						statement_cr:statementCr, acc_cr:accCr, 
						statement_dr:statementDr, acc_dr:accDr, 
						rec_period_year:recPeriodYear, rec_period_month:recPeriodMonth, 
						statement_close:statementClose, acc_close:accClose },
					dataType:"JSON",
					beforeSend:function(){
						$("#lbl_warnmsg").html('<i class="fa fa-spinner fa-spin"></i>Please wait.');
					}
				}).done(function(data){
					resData = data;//JSON.parse(data.action);
					$("#lbl_warnmsg").html(resData.resMsg);//resMsg
				});
				
			}
		}else{
			alert("You need to create or open ongoing reconciliation first");
		}
	});
	
	
});
</script>
<?php include "include/footer.php"; ?>
