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
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Company</label>
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
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Branch</label>
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
                                    
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Account</label>
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
                                    
                                    <div class="form-group mb-1">
                                    	<label class="small font-weight-bold">Period from</label>
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
                                    <div class="form-group mb-1">
                                    	<label class="small font-weight-bold">To</label>
                                        <select class="form-control form-control-sm" id="drp_filter_period_to_limit" data-nestname="periodupto">
                                        	<option value="">Select</option>
                                        </select>
                                    </div>
									<div class="form-group mt-3 text-right">
                                        <button type="submit" id="submit" class="btn btn-primary btn-sm px-4"><i class="far fa-file"></i>&nbsp;Show Report</button>
                                    </div>
                                </form>
                            </div>
                            <div id="report_preview" class="col-md-9">
                            	
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
