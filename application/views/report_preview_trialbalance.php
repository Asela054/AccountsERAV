<div class="col-12 text-right">
	<button type="button" id="btnexcelconvert" class="btn btn-success btn-sm px-4 mb-3 mr-2"><i class="fas fa-file-excel mr-2"></i>Excel</button>
    <button type="button" id="btnpdfconvert" class="btn btn-danger btn-sm px-4 mb-3"><i class="fas fa-file-pdf mr-2"></i>PDF</button>
</div>
<div class="col-12">
    <h6 class="title-style small font-weight-bold mt-2">
		<span><?php echo $_SESSION['company'] ?> - Trial Balance Statement <?php echo $rpt_from.' / '.$rpt_to; ?></span>
	</h6>
	<!--h6 class="title-style small font-weight-bold mt-2"><span>More Info</span></h6-->
	<table class="table table-bordered table-sm table-striped small" id="tablereport" style="margin-top:5px; margin-bottom:25px;">
		<thead>
			<tr>
				<th>Account</th>
				<th class="text-right">Debit</th>
				<th class="text-right">Credit</th>
			</tr>
		</thead>
		<tbody id="">
			<?php 
                $financial_year='-';
                $crdr_total = array('D'=>0, 'C'=>0, 'RptSectCnt'=>0);
                //if($totalRows_rsInfo>0){
            ?>
			<!-- <tr>
				<th><?php // echo $stock_date; ?> Closing</th>
				<?php 
                    // $stock_val = $open_stock;//calc_stock(true);
                    // $crdr_total['D']+=$stock_val;
                ?>
				<th class="text-right"><?php // echo number_format($stock_val, 2); ?></th>
				<td class="text-right">&nbsp;</td>
			</tr> -->
			<?php 
				// print_r($trial_balance_data);
                foreach($trial_balance_data as $tr){ 
                    $dr_accamount='';
                    $cr_accamount='';
                    
                    // if($tr->crdr==2){
					// 	if($tr->accamount<0){
					// 		$cr_accamount=number_format(($tr->accamount*-1), 2);
                    //     	$crdr_total['C']+=($tr->accamount*-1);
					// 	}
					// 	else{
					// 		$dr_accamount=number_format($tr->accamount, 2);
					// 		$crdr_total['D']+=$tr->accamount;
					// 	}
                    // }else if($tr->crdr==1){
					// 	if($tr->accamount<0){
					// 		$dr_accamount=number_format(($tr->accamount*-1), 2);
					// 		$crdr_total['D']+=($tr->accamount*-1);
					// 	}
					// 	else{
					// 		$cr_accamount=number_format($tr->accamount, 2);
					// 		$crdr_total['C']+=$tr->accamount;
					// 	}
                    // }

					if($tr->crdr=='C'){
						$crdr_total['C']+=($tr->accamount*1);
					}
					else if($tr->crdr=='D'){
						$crdr_total['D']+=($tr->accamount*1);
					}

					$dr_accamount=($tr->crdr=='D' ? number_format($tr->accamount, 2) : '');
					$cr_accamount=($tr->crdr=='C' ? number_format($tr->accamount, 2) : '');

					if($tr->accamount!=0){
            ?>
			<tr>
				<td><?php echo $tr->accname; ?></td>
				<td class="text-right"><?php echo $dr_accamount; ?></td>
				<td class="text-right"><?php echo $cr_accamount; ?></td>
			</tr>
			<?php }}?>
			<tr>
				<td>&nbsp;</td>
				<th class="text-right">
					<?php echo number_format($crdr_total['D'], 2); ?></th>
				<th class="text-right">
					<?php echo number_format($crdr_total['C'], 2); ?></th>
			</tr>
		</tbody>
	</table>
</div>
<input type="hidden" id="periodtitle" value="<?php echo $rpt_from.' / '.$rpt_to; ?>">
<input type="hidden" id="reporttitle" value="Trial Balance Statement">
<input type="hidden" id="filetitle" value="trial_statement_">
<input type="hidden" id="reporttype" value="4">

<script>
// Self-contained Excel export — no external library needed.
// Wraps #tablereport in a minimal HTML document and downloads it as .xls,
// which Excel opens natively (it reads HTML tables inside an .xls container).
document.getElementById('btnexcelconvert').addEventListener('click', function () {
    var table       = document.getElementById('tablereport');
    var periodTitle = document.getElementById('periodtitle').value;
    var reportTitle = document.getElementById('reporttitle').value;
    var fileTitle    = document.getElementById('filetitle').value;
 
    var companyName = <?php echo json_encode($_SESSION['company']); ?>;
 
    var html = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">'
        + '<head><meta charset="UTF-8">'
        + '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>'
        + '<x:Name>Cash Flow</x:Name>'
        + '<x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions>'
        + '</x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->'
        + '</head><body>'
        + '<h3>' + companyName + '</h3>'
        + '<h4>' + reportTitle + '</h4>'
        + '<p>' + periodTitle + '</p>'
        + table.outerHTML
        + '</body></html>';
 
    var blob = new Blob(['\ufeff' + html], { type: 'application/vnd.ms-excel' });
    var url  = URL.createObjectURL(blob);
 
    var a = document.createElement('a');
    a.href     = url;
    a.download = fileTitle + periodTitle.replace(/[^a-z0-9]+/gi, '_') + '.xls';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
});
</script>