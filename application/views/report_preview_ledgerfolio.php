<?php 
$crdr_cnt = 0;
$crdr_total = array('D'=>0, 'C'=>0, 'RptSectCnt'=>0);
?>
<div class="col-12 text-right">
    <button type="button" id="btnpdfconvert" class="btn btn-danger btn-sm px-4 mb-3"><i class="fas fa-file-pdf mr-2"></i>PDF</button>
</div>
<div class="col-12">
	<h6 class="title-style small font-weight-bold mt-2">
		<span><?php echo $_SESSION['company'] ?> - <?php echo $account_code;//'---';//$subaccountno; ?> Account Statement <?php echo $report_duration;//'---';//$financial_year; ?></span>
	</h6>
	<!--h6 class="title-style small font-weight-bold mt-2"><span>More Info</span></h6-->
	<table class="table table-bordered table-sm table-striped small" id="tablereport" style="margin-top:5px; margin-bottom:25px;">
		<thead>
			<tr>
				<th>Date</th>
				<th>Particulars</th>
				<th class="text-right">Debit</th>
				<th class="text-right">Credit</th>
				<th class="text-right">Sub Total</th>
				<th class="text-right">Net Total</th>
			</tr>
		</thead>
		<tbody id="">
			<tr>
				<th colspan="5">Opening Balance</th>
				<th class="text-right">
					<?php echo (($open_stock==0)?'0.00':number_format($open_stock, 2));//'---';// ?>
				</th>
			</tr>
			<?php 
			foreach($ledger_folio_data as $tr){ 
				$debit_amt = '&nbsp;';
				$credit_amt = '&nbsp;';
				
				$dr_close_in_html = '';
				$dr_close_out_html = '';
				$cr_close_html = '';
				$crdr_cnt++;
				
				if($tr->crdr=='D'){
					$debit_amt = number_format($tr->accamount, 2);
					$crdr_total['D'] +=  $tr->accamount;
				}else if($tr->crdr=='C'){
					if($crdr_total['C']==0){
						$crdr_total['RptSectCnt']=1;
					}
					
					$credit_amt = number_format($tr->accamount, 2);
					$crdr_total['C'] +=  $tr->accamount;
					
					
				}
				
				if($crdr_cnt==$total_rows_ledger_folio){
					if($crdr_total['C']==0){
						$crdr_total['RptSectCnt']=2;
					}
					
					$col_txt = number_format($crdr_total['C'], 2);
					$cr_close_html = '<tr><th colspan="2"><strong>Total Credit</strong></th>'.
						'<td>&nbsp;</td>'.
						'<th class="text-right"><strong>'.$col_txt.'</strong></th>'.
						'<th class="text-right"><strong>'.$col_txt.'</strong></th>'.
						'<td>&nbsp;</td></tr>';
				}
				
				if($crdr_total['RptSectCnt']>0){
					
					$col_txt=number_format($crdr_total['D'], 2);
					
					$dr_close_html = '<tr><th colspan="2"><strong>Total Debit</strong></th>'.
						'<th class="text-right"><strong>'.$col_txt.'</strong></th>'.
						'<td>&nbsp;</td>'.
						'<th class="text-right"><strong>'.$col_txt.'</strong></th>'.
						'<td>&nbsp;</td></tr>';
						
					if($crdr_total['RptSectCnt']==1){
						$dr_close_in_html=$dr_close_html;
					}else if($crdr_total['RptSectCnt']==2){
						$dr_close_out_html=$dr_close_html;
					}
					
					$crdr_total['RptSectCnt']=3;//prevent-repeating-of-debit-total-rows
					
				}
				
				echo $dr_close_in_html;
			?>
			<tr>
				<td><?php echo $tr->tradate; ?></td>
				<td><?php echo $tr->narration; ?></td>
				<td class="text-right"><?php echo $debit_amt; ?></td>
				<td class="text-right"><?php echo $credit_amt; ?></td>
				<td class="text-right"><?php echo '&nbsp;'; ?></td>
				<td class="text-right"><?php echo '&nbsp;'; ?></td>
			</tr>
			<?php 
					echo $dr_close_out_html;
					echo $cr_close_html;
					
				}
			?>
			<tr>
				<th colspan="5">Closing Balance</th>
				<th class="text-right">
					<?php echo number_format(($open_stock+($crdr_total['D']+$crdr_total['C'])), 2); ?>
				</th>
			</tr>
		</tbody>
	</table>
</div>
<input type="hidden" id="periodtitle" value="<?php echo $report_duration; ?>">
<input type="hidden" id="reporttitle" value="<?php echo $account_code; ?> Account Statement Statement">
<input type="hidden" id="filetitle" value="<?php echo $account_code; ?>_account_statement_">
<input type="hidden" id="reporttype" value="3">