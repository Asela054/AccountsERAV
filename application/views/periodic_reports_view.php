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
							<div class="page-header-icon"><i class="far fa-file-pdf"></i></div>
							<span><?php echo $report_title; ?></span>
						</h1>
					</div>
				</div>
			</div>
			<div class="container-fluid mt-2 p-0 p-2">
				<div class="card rounded-0">
					<div class="card-body p-0 p-2">
						<form id="frmParams" method="post">
							<div class="form-row mb-1">
								<div class="col">
									<label class="small font-weight-bold">Company</label>
									<select name="company_id" id="company_id" class="form-control form-control-sm" required>
										<?php foreach($companylist as $rowcompanylist){ ?>
										<option value="<?php echo $rowcompanylist->idtbl_company ?>"><?php echo $rowcompanylist->company ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col">
									<label class="small font-weight-bold">Branch</label>
									<select name="company_branch_id" id="company_branch_id" class="form-control form-control-sm" required>
										<option value="">Select</option>
									</select>
								</div>
								<?php if($functionmenu=='ledger_folio'){ ?>
								<div class="col">
									<label class="small font-weight-bold">Account</label>
									<select class="form-control form-control-sm" id="drp_filter_chart_of_acc" name="chart_acc_id">
										<option value="">Select</option>
									</select>
								</div>
								<?php } if($functionmenu=='DebtorReport'){ ?>
								<div class="col-3">
									<label class="small font-weight-bold">Debtor</label>
									<select class="form-control form-control-sm" id="customer" name="customer">
										<option value="">Select</option>
									</select>
								</div>
								<?php } if($functionmenu=='CreditorReport'){ ?>
								<div class="col-3">
									<label class="small font-weight-bold">Creditor</label>
									<select class="form-control form-control-sm" id="supplier" name="supplier">
										<option value="">Select</option>
									</select>
								</div>
								<?php } ?>
								<div class="col">
									<label class="small font-weight-bold">Period from</label>
									<select class="form-control form-control-sm" id="period_from" name="period_from" data-nestname="periodfrom">
										<option value="">Select</option>
									</select>
								</div>
								<div class="col">
									<label class="small font-weight-bold">To</label>
									<select class="form-control form-control-sm" id="period_upto" name="period_upto" data-nestname="periodupto">
										<option value="">Select</option>
									</select>
								</div>
								<div class="col">
									<label class="small font-weight-bold">&nbsp;</label><br>
									<button type="submit" id="submit" class="btn btn-primary btn-sm px-4"><i class="far fa-file"></i>&nbsp;Show Report</button>
								</div>
							</div>
						</form>
						<div class="row">
							<div class="col-12"><hr></div>
                            <div id="report_preview" class="col-md-12"></div>
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
	getbranchlist('<?php echo $_SESSION['companyid'] ?>', '<?php echo $_SESSION['branchid'] ?>');
	getperiodlist('<?php echo $_SESSION['companyid'] ?>', '<?php echo $_SESSION['branchid'] ?>');
	var functionmenu = '<?php echo $functionmenu ?>';

	$("#drp_filter_chart_of_acc").select2({
		// dropdownParent: $('#modalsegregation'),
		ajax: {
			url: "<?php echo base_url() ?>ReportModule/Getaccountlist",
			type: "post",
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					searchTerm: params.term, // search term
					companyid: <?php echo $_SESSION['companyid'] ?>,
					branchid: <?php echo $_SESSION['branchid'] ?>
				};
			},
			processResults: function (response) {
				return {
					results: response.map(function (item) {
						return {
							id: item.id,
							text: item.text,
							data: {
								type: item.acctype
							}
						};
					})
				}
			},
			cache: true
		},
	});

	if(functionmenu=='DebtorReport'){
		$("#customer").select2({
            ajax: {
                url: "<?php echo base_url() ?>ReportModule/Getcustomerlist",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        companyid: '<?php echo $_SESSION['companyid'] ?>',
                        branchid: '<?php echo $_SESSION['branchid'] ?>',
                        searchTerm: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
	}

	if(functionmenu=='CreditorReport'){
		$("#supplier").select2({
            ajax: {
                url: "<?php echo base_url() ?>ReportModule/Getsupplierlist",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        companyid: '<?php echo $_SESSION['companyid'] ?>',
                        branchid: '<?php echo $_SESSION['branchid'] ?>',
                        searchTerm: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
	}
	
	$("#frmParams").submit(function(event){
		event.preventDefault();

		Swal.fire({
			title: '',
			html: '<div class="div-spinner"><div class="custom-loader"></div></div>',
			allowOutsideClick: false,
			showConfirmButton: false, // Hide the OK button
			backdrop: `
				rgba(255, 255, 255, 0.5) 
			`,
			customClass: {
				popup: 'fullscreen-swal'
			},
			didOpen: () => {
				document.body.style.overflow = 'hidden';

				$.ajax({
					method: "POST",
					url: "<?php echo base_url($report_gen_url);//base_url('ReportModule/preview'); ?>",
					data:$(this).serialize()
				}).done(function(data){
					Swal.close();
					$("#report_preview").html(data);
					exportoption();
				});

				document.body.style.overflow = 'visible';
			}
		});		
	});
});

function exportoption(){
	$('#btnpdfconvert').click(function(){
		var formtext = $("#period_from option:selected").text();
		var totext = $("#period_upto option:selected").text();
		var reporttype = $('#reporttype').val();

		var { jsPDF } = window.jspdf;
		var doc = new jsPDF('p', 'pt', 'legal');

		// Define table content
		var table = document.getElementById("tablereport");
		
		var rows = [];
		for (var i = 0, row; row = table.rows[i]; i++) {
			// console.log(row);
			var rowData = [];
			for (var j = 0, col; col = row.cells[j]; j++) {
				if(reporttype==1){
					if(row.cells.length==1){					
						if (col.tagName.toLowerCase() === 'th') {						
							rowData.push({content: col.innerText, styles: {halign: 'left', fontStyle: 'bold'}});
						}
					}
					else if(row.cells.length==2){
						if(j==1 && reporttype==2){
							rowData.push({content: col.innerText, colSpan: 4, styles: {halign: 'right', fontStyle: 'bold'}});
						}
						else if(j==1){
							rowData.push({content: col.innerText, colSpan: 3, styles: {halign: 'right', fontStyle: 'bold'}});
						}
						else{
							rowData.push({content: col.innerText, styles: {fontStyle: 'bold'}});
						}
					}
					else if (col.tagName.toLowerCase() === 'th') {			
						if(row.cells.length==3){
							if(j==0){
								rowData.push({content: col.innerText, colSpan: 3, styles: {fontStyle: 'bold'}});
							}
							else{
								rowData.push({content: col.innerText, styles: {fontStyle: 'bold'}});
							}
						}
						else{
							rowData.push({content: col.innerText, styles: {fontStyle: 'bold'}});
						}
					}
					else{
						if(row.cells.length==3 && reporttype==2){
							if(j==0){
								rowData.push({content: col.innerText, colSpan: 3});
							}
							else{
								rowData.push(col.innerText);
							}
						}
						else if(row.cells.length==4 && reporttype==2){
							if(j==0){
								rowData.push({content: col.innerText, colSpan: 2});
							}
							else if(j==3){
								rowData.push({content: col.innerText, styles: {halign: 'right'}});
							}
							else{
								rowData.push(col.innerText);
							}
						}
						else{
							rowData.push(col.innerText);
						}
					}
				}
				else if(reporttype==2){
					if(row.cells.length==1){					
						if (col.tagName.toLowerCase() === 'th') {						
							rowData.push({content: col.innerText, colSpan: 3, styles: {halign: 'left', fontStyle: 'bold'}});
						}
					}
					else{
						if(row.cells.length==3 && j==0){
							if (col.tagName.toLowerCase() === 'th') {						
								rowData.push({content: col.innerText, styles: {halign: 'left', fontStyle: 'bold'}});
							}
							else{
								rowData.push(col.innerText);
							}
						}
						else if(row.cells.length==3 && j==1){
							if (col.tagName.toLowerCase() === 'th') {						
								rowData.push({content: col.innerText, styles: {halign: 'right', fontStyle: 'bold'}});
							}
							else{
								rowData.push({content: col.innerText, styles: {halign: 'right'}});
							}
						}
						else if(row.cells.length==3 && j==2){
							if (col.tagName.toLowerCase() === 'th') {						
								rowData.push({content: col.innerText, styles: {halign: 'right', fontStyle: 'bold'}});
							}
							else{
								rowData.push({content: col.innerText, styles: {halign: 'right'}});
							}
						}
					}
				}
				else if(reporttype==3){
					if(row.cells.length==1){					
						if (col.tagName.toLowerCase() === 'th') {						
							rowData.push({content: col.innerText, styles: {halign: 'left', fontStyle: 'bold'}});
						}
					}
					else if (col.tagName.toLowerCase() === 'th') {			
						if(row.cells.length==2){
							if(j==0){
								rowData.push({content: col.innerText, colSpan: 5, styles: {halign: 'left', fontStyle: 'bold'}});
							}
							else{
								rowData.push({content: col.innerText, styles: {halign: 'right', fontStyle: 'bold'}});
							}
						}
						else if(row.cells.length==5){
							if(j==0){
								rowData.push({content: col.innerText, colSpan: 2, styles: {halign: 'left', fontStyle: 'bold'}});
							}
							else{
								rowData.push({content: col.innerText, styles: {halign: 'right', fontStyle: 'bold'}});
							}
						}
						else{
							rowData.push({content: col.innerText, styles: {fontStyle: 'bold'}});
						}
					}
					else{
						if(j>1){
							rowData.push({content: col.innerText, styles: {halign: 'right'}});
						}
						else{
							rowData.push(col.innerText);
						}
					}
				}
				else if(reporttype==4){
					if(row.cells.length==1){					
						if (col.tagName.toLowerCase() === 'th') {						
							rowData.push({content: col.innerText, styles: {halign: 'left', fontStyle: 'bold'}});
						}
					}
					else if (col.tagName.toLowerCase() === 'th') {
						rowData.push({content: col.innerText, styles: {fontStyle: 'bold'}});
					}
					else{
						rowData.push(col.innerText);
					}
				}
				else if(reporttype==5){
					if(row.cells.length==1){					
						if (col.tagName.toLowerCase() === 'th') {						
							rowData.push({content: col.innerText, styles: {halign: 'left', fontStyle: 'bold'}});
						}
					}
					else if(row.cells.length==2){
						if(j==1){
							if (col.tagName.toLowerCase() === 'th') {						
								rowData.push({content: col.innerText, styles: {halign: 'right', fontStyle: 'bold'}});
							}
						}
						else{
							rowData.push({content: col.innerText, colSpan: 5, styles: {halign: 'left', fontStyle: 'bold'}});
						}
					}
					else if (col.tagName.toLowerCase() === 'th') {
						rowData.push({content: col.innerText, styles: {fontStyle: 'bold'}});
					}
					else{
						if(row.cells.length==6){
							if(j<3){
								rowData.push({content: col.innerText, styles: {halign: 'left'}});
							}
							else{
								rowData.push({content: col.innerText, styles: {halign: 'right'}});
							}
						}
						else{
							rowData.push(col.innerText);
						}
					}
				}
				else if(reporttype==6){
					if(row.cells.length==1){					
						if (col.tagName.toLowerCase() === 'th') {						
							rowData.push({content: col.innerText, styles: {halign: 'left', fontStyle: 'bold'}});
						}
					}
					else if(row.cells.length==2){
						if(j==1){
							if (col.tagName.toLowerCase() === 'th') {						
								rowData.push({content: col.innerText, styles: {halign: 'right', fontStyle: 'bold'}});
							}
						}
						else{
							rowData.push({content: col.innerText, colSpan: 5, styles: {halign: 'left', fontStyle: 'bold'}});
						}
					}
					else if (col.tagName.toLowerCase() === 'th') {
						rowData.push({content: col.innerText, styles: {fontStyle: 'bold'}});
					}
					else{
						if(row.cells.length==6){
							if(j<3){
								rowData.push({content: col.innerText, styles: {halign: 'left'}});
							}
							else{
								rowData.push({content: col.innerText, styles: {halign: 'right'}});
							}
						}
						else{
							rowData.push(col.innerText);
						}
					}
				}
			}
			rows.push(rowData);
		}

		var headers = [rows[0]];
		var data = rows.slice(1);

		doc.setFontSize(12);

		const titleLine1 = "<?php echo $_SESSION['company'] ?>";
		const titleLine2 = $('#reporttitle').val();
		const titleLine3 = $('#periodtitle').val();

		const pageWidth = doc.internal.pageSize.getWidth();
		const pageHeight = doc.internal.pageSize.getHeight();

		const textWidth1 = doc.getTextWidth(titleLine1);
		doc.setFontSize(11);
		const textWidth2 = doc.getTextWidth(titleLine2);
		doc.setFontSize(9);
		const textWidth3 = doc.getTextWidth(titleLine3);

		const xPosition1 = (pageWidth - textWidth1) / 2;
		const xPosition2 = (pageWidth - textWidth2) / 2;
		const xPosition3 = (pageWidth - textWidth3) / 2;

		const yPosition1 = 40; 
		const yPosition2 = yPosition1 + 15; 
		const yPosition3 = yPosition1 + 30; 

		doc.setFontSize(12); 
		doc.text(titleLine1, xPosition1, yPosition1);
		doc.setFontSize(11); 
		doc.text(titleLine2, xPosition2, yPosition2);
		doc.setFontSize(9); 
		doc.text(titleLine3, xPosition3, yPosition3);

		doc.setFontSize(12);

		doc.autoTable({
			head: headers,
			body: data,
			startY: 80,
			theme: 'striped',
			headStyles: { fillColor: [41, 128, 185] }, 
			styles: { cellPadding: 5, halign: 'left', fontSize: 8 }, 
			columnStyles: {
				2: { halign: 'right' }, 
				3: { halign: 'right' },
			}
		});

		var filetitle = $('#filetitle').val()+'<?php echo $_SESSION['company'] ?>_'+titleLine3;
		doc.save(filetitle+".pdf");
	});
}

function getbranchlist(id, value){
	$.ajax({
		type: "POST",
		data: {
			recordID: id
		},
		url: '<?php echo base_url() ?>ReportModule/Getbranchaccocompany',
		success: function(result) { //alert(result);
			var obj = JSON.parse(result);
			var html = '';
			html += '<option value="">Select</option>';
			$.each(obj, function (i, item) {
				html += '<option value="' + obj[i].idtbl_company_branch + '">';
				html += obj[i].branch ;
				html += '</option>';
			});
			$('#company_branch_id').empty().append(html);  

			if(value!=''){
				$('#company_branch_id').val(value);
			}
		}
	});
}

function getperiodlist(company, branch){
	$.ajax({
		type: "POST",
		data: {
			company: company,
			branch: branch
		},
		url: '<?php echo base_url() ?>ReportModule/Getperiodlist',
		success: function(result) { //alert(result);
			// console.log(result);
			
			var obj = JSON.parse(result);
			var html = '';
			html += '<option value="">Select</option>';
			$.each(obj, function (i, item) {
				html += '<option value="' + obj[i].idtbl_master + '">';
				html += obj[i].desc+' '+obj[i].monthname ;
				html += '</option>';
			});
			$('#period_from').empty().append(html);  
			$('#period_upto').empty().append(html);  
		}
	});
}
</script>
<?php include "include/footer.php"; ?>
