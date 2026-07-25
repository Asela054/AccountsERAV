<?php 
$total_debit = 0;
$total_credit = 0;

if (!isset($open_stock_crdr)) {
    $open_stock_crdr = 'D';
}

$running_balance = $open_stock;
$balance_dir = $open_stock_crdr; 
?>

<div class="col-12 text-right">
    <button type="button" id="btnexcelconvert" class="btn btn-success btn-sm px-4 mb-3 mr-2"><i class="fas fa-file-excel mr-2"></i>Excel</button>
    <button type="button" id="btnpdfconvert" class="btn btn-danger btn-sm px-4 mb-3">
        <i class="fas fa-file-pdf mr-2"></i>PDF
    </button>
</div>

<div class="col-12">
    <h6 class="title-style small font-weight-bold mt-2">
        <span><?php echo $_SESSION['company']; ?> - <?php echo $account_code; ?> Account Statement <?php echo $report_duration; ?></span>
    </h6>
    
    <table class="table table-bordered table-sm table-striped small" id="tablereport" style="margin-top:5px; margin-bottom:25px;">
        <thead>
            <tr>
                <th style="width: 12%;">Date</th>
                <th style="width: 40%;">Particulars / Narration</th>
                <th class="text-right" style="width: 12%;">Debit</th>
                <th class="text-right" style="width: 12%;">Credit</th>
                <th class="text-right" style="width: 16%;">Running Balance</th>
                <th class="text-center" style="width: 8%;">Dr/Cr</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $rpt_from; ?></td>
                <td><strong>Opening Balance</strong></td>
                <td class="text-right"><?php echo ($open_stock_crdr == 'D') ? number_format($open_stock, 2) : '&nbsp;'; ?></td>
                <td class="text-right"><?php echo ($open_stock_crdr == 'C') ? number_format($open_stock, 2) : '&nbsp;'; ?></td>
                <td class="text-right"><strong><?php echo number_format($open_stock, 2); ?></strong></td>
                <td class="text-center"><strong><?php echo $open_stock_crdr; ?></strong></td>
            </tr>

            <?php 
            foreach($ledger_folio_data as $tr) { 
                $debit_amt = '&nbsp;';
                $credit_amt = '&nbsp;';
                $amt = (float)$tr->accamount;

                if ($tr->crdr == 'D') {
                    $debit_amt = number_format($amt, 2);
                    $total_debit += $amt;

                    if ($balance_dir == 'D') {
                        $running_balance += $amt;
                    } else {
                        $running_balance -= $amt;
                    }
                } else if ($tr->crdr == 'C') {
                    $credit_amt = number_format($amt, 2);
                    $total_credit += $amt;

                    if ($balance_dir == 'D') {
                        $running_balance -= $amt;
                    } else {
                        $running_balance += $amt;
                    }
                }

                // If balance crossed zero, flip the side and re-normalize to positive
                if ($running_balance < 0) {
                    $balance_dir = ($balance_dir == 'D') ? 'C' : 'D';
                    $running_balance = abs($running_balance);
                }

                $display_balance = $running_balance;
                $current_dir = $balance_dir;
            ?>
                <tr>
                    <td><?php echo $tr->tradate; ?></td>
                    <td><?php echo $tr->narration; ?></td>
                    <td class="text-right text-success"><?php echo $debit_amt; ?></td>
                    <td class="text-right text-danger"><?php echo $credit_amt; ?></td>
                    <td class="text-right"><?php echo number_format($display_balance, 2); ?></td>
                    <td class="text-center"><?php echo $display_balance == 0 ? '-' : $tr->crdr; ?></td>
                </tr>
            <?php } ?>

            <tr style="background-color: #f8f9fa;">
                <th colspan="2" class="text-right">Total Period Movements:</th>
                <th class="text-right"><?php echo number_format($total_debit, 2); ?></th>
                <th class="text-right"><?php echo number_format($total_credit, 2); ?></th>
                <td colspan="2">&nbsp;</td>
            </tr>
            
            <tr style="background-color: #e9ecef;">
                <th colspan="4" class="text-right">Closing Balance:</th>
                <th class="text-right" style="font-size: 1.05em;">
                    <?php
                    // Calculate absolute closing balance matching your framework system logic
                    if ($open_stock_crdr == 'D') {
                        $closing = $open_stock + $total_debit - $total_credit;
                        $closing_dir = ($closing >= 0) ? 'D' : 'C';
                    } else {
                        $closing = $open_stock - $total_debit + $total_credit;
                        $closing_dir = ($closing >= 0) ? 'C' : 'D';
                    }
                    
                    echo number_format(abs($closing), 2);
                    ?>
                </th>
                <th class="text-center" style="font-size: 1.05em;">
                    <?php echo $closing == 0 ? '-' : $closing_dir; ?>
                </th>
            </tr>
        </tbody>
    </table>
</div>

<input type="hidden" id="periodtitle" value="<?php echo $report_duration; ?>">
<input type="hidden" id="reporttitle" value="<?php echo $account_code; ?> Account Statement Statement">
<input type="hidden" id="filetitle" value="<?php echo $account_code; ?>_account_statement_">
<input type="hidden" id="reporttype" value="3">

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