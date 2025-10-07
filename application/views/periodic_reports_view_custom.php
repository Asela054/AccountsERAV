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
							<span>Reports-<?php echo $report_title; ?></span>
                            
                            <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right dropdown-toggle" id="btnorderacts" style="position:absolute; right:10px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-cog"></i>&nbsp;Setup</button>
                            <div class="dropdown-menu" aria-labelledby="btnorderacts">
                            	<a class="dropdown-item" id="btnordercreate" data-refid="-1" href="javascript:void(0);">Sections</a>
                            	<a class="dropdown-item" id="btnOtherCreate" data-refid="-1" href="javascript:void(0);">Accounts</a>
                            </div>
						</h1>
					</div>
				</div>
			</div>
			<div class="container-fluid mt-2 p-0 p-2">
				<div class="card rounded-0">
					<div class="card-body p-0 p-2">
						<div class="row">
                            <div class="col-md-3">
                                <form id="frmParams" method="post">
                                    <div class="form-group">
                                        <label for="">Company</label>
                                        <select class="form-control form-control-sm nest_head" id="drp_filter_company_period_list" data-findnest="orgnest">
                                            <option value="">Select</option>
                                            <?php 
                                            foreach($company_period_list_filter as $cf){
                                            ?>
                                            <option value="<?php echo $cf->idtbl_company; ?>"><?php echo $cf->company; ?></option>
                                            <?php 	
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Branch</label>
                                        <select class="form-control form-control-sm" id="drp_filter_branch_period_list" data-nestname="orgnest">
                                            <option value="">Select</option>
                                            <?php 
                                            foreach($branch_period_list_filter as $df){
                                            ?>
                                            <option class="nestopt d-none" value="<?php echo $df->idtbl_company_branch; ?>" data-nestcode="<?php echo $df->tbl_company_idtbl_company; ?>" disabled="disabled"><?php echo $df->branch; ?></option>
                                            <?php 	
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    
                                    <?php if(isset($all_chart_of_acc)){ ?>
                                    
                                    <div class="form-group">
                                        <label for="">Account</label>
                                        <select class="form-control form-control-sm" id="drp_filter_chart_of_acc">
                                            <option value="">Select</option>
                                            <?php 
                                            foreach($all_chart_of_acc as $ch){
                                            ?>
                                            <option class="" value="<?php echo $ch->idtbl_account; ?>" ><?php echo $ch->accountno.' '.$ch->accountname; ?></option>
                                            <?php 	
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    
                                    <?php } ?>
                                    
                                    <div class="form-group">
                                    	<label>Period from</label>
                                        <select class="form-control form-control-sm" id="drp_filter_all_period_list" data-nestname="periodfrom">
                                        	<option value="">Select</option>
                                            <?php 
                                            foreach($all_account_periods as $ef){
                                            ?>
                                            <option class="nestopt d-none" value="<?php echo $ef->idtbl_master; ?>" data-nestcode="<?php echo $ef->tbl_company_branch_idtbl_company_branch; ?>" disabled="disabled"><?php echo $ef->desc.' '.$ef->monthname; ?></option>
                                            <?php 	
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                    	<label>To</label>
                                        <select class="form-control form-control-sm" id="drp_filter_period_to_limit" data-nestname="periodupto">
                                        	<option value="">Select</option>
                                            
                                        </select>
                                    </div>
                                    <div class="form-group d-flex align-items-center justify-content-between" style="margin-top:30px;">
                                    	<div class="small text-muted">
                                        	<button type="submit" name="submit" class="btn btn-primary">Show</button>
                                        </div>
                                        <div class="small" style="font-weight:bold;">
                                        	<span id="lbl_warnmsg" style="text-align:right; font-weight:bold;">&nbsp;</span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="report_preview" class="col-md-9">
                            	
                            </div>
                        </div>
					</div>
				</div>
			</div>
            
            <!-- Modal Create Order -->
            <div class="modal fade" id="modalcreateorder" data-backdrop="static" data-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header p-2">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <form id="createorderform" autocomplete="off">
                                        <div class="row">
                                            <div class="col">
                                            <!-- detail -->
                                                <div class="form-group mb-2">
                                                    <label class="small font-weight-bold text-dark">Main Section</label>
                                                    <select class="form-control form-control-sm nest_head" data-findnest="debitnest" id="drp_main" name="drp_main">
                                                        <option value="-1">Select</option>
                                                        <?php if(count($rpthead)>0){
                                                            foreach($rpthead as $rowmain){?>
                                                        
                                                        <option value="<?php echo $rowmain['id']; ?>"><?php echo $rowmain['name']; ?></option>
                                                        <?php }
                                                        } ?>
                                                        
                                                        
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group mb-2">
                                                    <label class="small font-weight-bold text-dark">Sub Section*</label>
                                                    <input type="text" id="txt_sub" name="txt_sub" class="form-control form-control-sm" value="" required>
                                                </div>
                                                
                                                
                                                
                                                
                                            <!-- detail -->
                                            </div>
                                            
                                            
                                        </div>
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        <div class="form-group">
                                            <span id="sect_notes" style="line-height:28px;"></span>
                                            <button type="submit" id="sect_submit" class="btn btn-outline-primary btn-sm fa-pull-right" <?php // if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus"></i>&nbsp;Add</button>
                                            <!--input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none"-->
                                        </div>
                                        
                                        <input type="hidden" name="hrefid" id="hrefid" value="">
                                        
                                    </form>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <table class="table table-striped table-bordered table-sm small" id="tableDetails" width="100%">
                                        <thead>
                                            <tr>
                                                <!--th>Main Section</th-->
                                                <th>Sub Section</th>
                                                <th class="text-center">Show/Hide</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                    
                                    <!--hr-->
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Create Other -->
            <div class="modal fade" id="modalCreateOther" data-backdrop="static" data-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header p-2">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <!--div class="col"-->
                                <!-- detail -->
                                    <div class="form-group col mb-2">
                                        <label class="small font-weight-bold text-dark">Main Section*</label>
                                        <select class="form-control form-control-sm nest_head" data-findnest="op_debitnest" id="drp_grp" name="drp_grp">
                                            <option value="">Select</option>
                                            <?php if(count($rpthead)>0){
                                                foreach($rpthead as $rowmain){?>
                                            
                                            <option value="<?php echo $rowmain['id']; ?>"><?php echo $rowmain['name']; ?></option>
                                            <?php }
                                            } ?>
                                            
                                            
                                        </select>
                                    </div>
                                    <div class="form-group col mb-2">
                                        <label class="small font-weight-bold text-dark">Sub Section*</label>
                                        <select class="form-control form-control-sm" data-nestname="op_debitnest" id="drp_sub" name="drp_sub">
                                            <option value="-1">Select</option>
                                            <?php if(count($rptsub)>0){
                                                foreach($rptsub as $rowsub){?>
                                            
                                            <option class="nestopt d-none" value="<?php echo $rowsub['id']; ?>" data-nestcode="<?php echo $rowsub['group_id']; ?>" disabled="disabled"><?php echo $rowsub['code']; ?></option>
                                            <?php }
                                            } ?>
                                            
                                            
                                        </select>
                                    </div>
                                    
                                    
                                    
                                    
                                <!-- detail -->
                                <!--/div-->
                                
                                
                            </div>
                            <div class="row">
                                <!--div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                    <form id="frmOtherPayment" autocomplete="off">
                                        
                                        
                                        <input type="hidden" name="hrefop" id="hrefop" value=""><!-- referring-detail-id-for-other-particulars -//->
                                        
                                    </form>
                                </div-->
                                <div class="col" style="margin-top:15px;">
                                    <table class="table table-striped table-bordered table-sm small" id="optableDetails" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Account</th>
                                                <th class="text-center" style="width:100px;">Select</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                    
                                    <!--hr-->
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

		</main>
		<?php include "include/footerbar.php"; ?>
	</div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
$(document).ready(function(){
	var main_table=$('#tableDetails').DataTable( {
			"searching":false,
			"info":false,
			"destroy": true,
			"processing": true,
			"serverSide": true,
			ajax: {
				url: "<?php echo base_url('scripts/report_conf_subsectionlist.php'); ?>",
				type: "POST", // you can use GET
				"data":function(data){
					data.filter_val=$('#drp_main').find(":selected").val();
				}
			},
			"columns": [
				{
					"data": "sub_section_name"
				},
				{
					"data": "header_id",
					"className": 'text-center',
					"orderable":false,
					"render": function(data, type, full) {
						var button='';
						var check_str = (full.detail_cancel==0)?'checked="checked"':'';
						
						var block_str = '';//($('#drp_sub').find(':selected').val()=='-1')?'disabled="disabled"':'';
						button+='<input type="checkbox" class="form-control-custom chk_view" data-toggle="tooltip" data-placement="right" title="" ';button+=check_str+block_str+' value="'+data+'" />'; 
						
						return button;
					}
				}
			]
		} );
	
	$("#drp_main").on("change", function(){
		main_table.draw();
	});
	
	var conf_table=$('#optableDetails').DataTable( {
			"searching":false,
			"info":false,
			"destroy": true,
			"processing": true,
			"serverSide": true,
			ajax: {
				url: "<?php echo base_url('scripts/report_conf_subacclist.php'); ?>",
				type: "POST", // you can use GET
				"data":function(data){
					data.filter_val=$('#drp_sub').find(":selected").val();
				}
			},
			"columns": [
				{
					"data": "subaccountname"
				},
				{
					"data": "header_id",
					"className": 'text-center',
					//"orderable":false,
					"render": function(data, type, full) {
						var button='';
						var check_str = (full.report_part_cancel==0)?'checked="checked"':'';
						var confid=full.conf_id;
						
						if(confid==null){
							check_str = '';
							confid = '';
						}
						
						var block_str = ($('#drp_sub').find(':selected').val()=='-1')?'disabled="disabled"':'';
						button+='<input type="checkbox" class="form-control-custom chk_sign" data-toggle="tooltip" data-placement="right" title="" data-refid="'+confid+'" data-refacc="'+full.subaccount+'" ';button+=check_str+block_str+' value="'+data+'" />'; 
						
						return button;
					}
				}
			]
		} );
	
	/**/
	$("#drp_grp").on("change", function(){
		$('.chk_sign').prop('disabled', true);
	});
	
	$("#drp_sub").on("change", function(){
		conf_table.draw();
	});
	
	$('#drp_main, #drp_grp').change(function(){
		prep_nest($(this).data('findnest'), $(this).find(":selected").val(), 0);
	});
	
	// Create order part
	$('#btnordercreate').click(function(){
		$('#hrefid').val(''); // load-empty-record-number
		//$('#hrefop').val('');
		$('#modalcreateorder').modal('show');
		
	});
	
	// Create other cheque payments part
	$('#btnOtherCreate').click(function(){
		$('#hrefid').val(''); // load-empty-record-number
		//$('#hrefop').val('');
		$('#modalCreateOther').modal('show');
		
	});
	
	$('#modalcreateorder').on('hidden.bs.modal', function () {
		$('#txt_sub').val('');
		$('#hrefid').val('');
		$('sect_notes').html('');
		//$('#hrefop').val('');
		//$('#tableDetails > tbody').html('');
		
		//book_table.clear().draw();
	});
	
	$('#createorderform').on('submit', function(event){
		event.preventDefault();
		
		var grpid=$("#drp_main").find(":selected").val();
		var sectname=$("#txt_sub").val();
		
		$.ajax({
			type:"POST",
			data:{
				grp_id: grpid,
				sect_name: sectname
			},
			dataType: 'JSON',
			url: "<?php echo base_url('ReportSettingsModule/store_sub_section'); ?>",
			success: function(data) {
				if(data.resType=="success"){
					main_table.row.add( {
						"header_id":data.head_k,
						"sub_section_name":sectname, 
						"sect_cancel":0
					}).draw( false ).node();
					
					$('#drp_sub').append('<option class="nestopt d-none" value="'+data.head_k+'" data-nestcode="'+grpid+'" disabled="disabled">'+sectname+'</option>');
				}
				
				$("#sect_notes").html(data.resMsg);
				
			}
		});
	});
	
	$(document).on('click', '.chk_view', function(event){
		//event.preventDefault();
		
		var confrefid=$(this).val();
		var detailcancel=$(this).is(":checked")?0:1;
		var objchkconf=$(this);
		
		$.ajax({
			type:"POST",
			data:{
				conf_refid: confrefid,
				detail_cancel: detailcancel
			},
			dataType: 'JSON',
			url: "<?php echo base_url('ReportSettingsModule/toggle_sub_section_view'); ?>",
			success: function(data) {
				if(data.resType=="success"){
					if(confrefid==''){
						$(objchkconf).data('refid', data.sub_k);
					}
				}else{
					//$(objchkconf).prop("disabled", true);
					$(objchkconf).prop("checked", !$(objchkconf).prop("checked"));
				}
				
				//action(data.msgdesc);
				
			}
		});
	});
	
	$(document).on('click', '.chk_sign', function(event){
		//event.preventDefault();
		
		var grpid=$("#drp_grp").find(":selected").val();
		var sectid=$("#drp_sub").find(":selected").val();
		var confrefid=$(this).data('refid');
		var accid=$(this).val();
		var acccode=$(this).data('refacc');
		var detailcancel=$(this).is(":checked")?0:1;
		var objchkconf=$(this);
		
		$.ajax({
			type:"POST",
			data:{
				grp_id: grpid,
				sect_id: sectid,
				conf_refid: confrefid,
				acc_id: accid,
				acc_code: acccode,
				detail_cancel: detailcancel, 
				rpt_scope:'PNL'
			},
			dataType: 'JSON',
			url: "<?php echo base_url('ReportSettingsModule/toggle_report_detail_view'); ?>",
			success: function(data) {
				if(data.resType=="success"){
					if(confrefid==''){
						$(objchkconf).data('refid', data.sub_k);
					}
				}else{
					//$(objchkconf).prop("disabled", true);
					$(objchkconf).prop("checked", !$(objchkconf).prop("checked"));
				}
				
				//action(data.msgdesc);
				
			}
		});
	});
	
	
	
	
	
	
	$('#drp_filter_company_period_list').change(function(){
		prep_nest($(this).data('findnest'), $(this).find(":selected").val(), 0);
		//prep_nest($(this).data('findnest'), $(this).find(":selected").data('colcode'), 0);
		prep_nest('periodfrom', 0, 0);
		prep_nest('periodupto', 0, 0);
	});
	
	$('#drp_filter_branch_period_list').change(function(){
		prep_nest('periodfrom', $(this).find(":selected").val(), 0);
		prep_nest('periodupto', 0, 0);
	});
	
	$('#drp_filter_all_period_list').change(function(){
		//#drp_filter_period_to_limit
		//console.log($(this).find(":selected").index());
		$("#drp_filter_period_to_limit option.nestopt").remove();
		
		var opt_i = $(this).find(":selected").index();
		
		if(opt_i>0){
			//option:visible - option:not(.d-none)
			var $options = $("#drp_filter_all_period_list option:not(.d-none)").clone();
			$options.each(function(index, obj){
				if(index>=opt_i){
					$("#drp_filter_period_to_limit").append(obj);
				}
			});
		}
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
	
	$("#frmParams").submit(function(event){
		event.preventDefault();
		
		var companyId = $("#drp_filter_company_period_list").find(":selected").val();
		var companyBranchId = $("#drp_filter_branch_period_list").find(":selected").val();
		var periodFrom = $("#drp_filter_all_period_list").find(":selected").val();
		var periodUpto = $("#drp_filter_period_to_limit").find(":selected").val();
		
		$.ajax({
			method: "POST",
			url: "<?php echo base_url($report_gen_url);//base_url('ReportModule/preview'); ?>",
			data:{company_id:companyId, company_branch_id:companyBranchId, 
				period_from:periodFrom, period_upto:periodUpto}
		}).done(function(data){
			$("#report_preview").html(data);
		});
		
	});
});
</script>
<?php include "include/footer.php"; ?>
