<div class="col-12 text-right">
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
			<tr>
				<th><?php echo $stock_date; ?> Closing</th>
				<?php 
                    $stock_val = $open_stock;//calc_stock(true);
                    $crdr_total['D']+=$stock_val;
                ?>
				<th class="text-right"><?php echo number_format($stock_val, 2); ?></th>
				<td class="text-right">&nbsp;</td>
			</tr>
			<?php 
                foreach($trial_balance_data as $tr){ 
                    $dr_accamount='';
                    $cr_accamount='';
                    
                    if($tr->crdr==2){
						if($tr->accamount<0){
							$cr_accamount=number_format(($tr->accamount*-1), 2);
                        	$crdr_total['C']+=($tr->accamount*-1);
						}
						else{
							$dr_accamount=number_format($tr->accamount, 2);
							$crdr_total['D']+=$tr->accamount;
						}
                    }else if($tr->crdr==1){
						if($tr->accamount<0){
							$dr_accamount=number_format(($tr->accamount*-1), 2);
							$crdr_total['D']+=($tr->accamount*-1);
						}
						else{
							$cr_accamount=number_format($tr->accamount, 2);
							$crdr_total['C']+=$tr->accamount;
						}
                    }
            ?>
			<tr>
				<td><?php echo $tr->accname; ?></td>
				<td class="text-right"><?php echo $dr_accamount; ?></td>
				<td class="text-right"><?php echo $cr_accamount; ?></td>
			</tr>
			<?php } //}?>
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