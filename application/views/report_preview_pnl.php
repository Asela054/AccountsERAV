<?php
function format_currency($amount) {
    return number_format($amount, 2);
}

function get_amount_class($amount) {
    return $amount < 0 ? 'negative-amount' : 'positive-amount';
}

function display_amount($amount) {
    if ($amount < 0) {
        return '('.format_currency(abs($amount)).')';
    } else {
        return format_currency($amount);
    }
}

?>

<div class="col-12 text-right">
    <button type="button" id="btnpdfconvert" class="btn btn-danger btn-sm px-4 mb-3"><i class="fas fa-file-pdf mr-2"></i>PDF</button>
</div>
<div class="col-12 pnl-report">
    <h6 class="title-style small font-weight-bold mt-2">
        <span><?php echo $_SESSION['company'] ?> - Profit & Loss Statement <?php echo $rpt_from.' / '.$rpt_to; ?></span>
    </h6>
    
    <!-- Main Report Table -->
    <table class="table table-striped table-bordered table-sm small" id="tablereport">
        <!-- SALES REVENUE SECTION -->
        <tr class="section-header">
            <th colspan="3">REVENUE</th>
        </tr>
        
        <?php if(!empty($pnl_trlist[1])): ?>
        <tr>
            <th><u>Sales Revenue</u></th>
            <td></td>
            <td></td>
        </tr>
        <?php 
        $sales_total = 0;
        foreach($pnl_trlist[1] as $index => $tr_sale): 
            if(!empty($tr_sale) && isset($tr_sale[0]['tdtext']) && strpos($tr_sale[0]['tdtext'], 'Total') === false):
                $amount = isset($tr_sale[1]['tdtext']) ? floatval(str_replace(',', '', $tr_sale[1]['tdtext'])) : 0;
                $sales_total += $amount;

                if($amount != 0):
        ?>
        <tr>
            <td><?php echo $tr_sale[0]['tdtext']; $accountdetail = explode('-', $tr_sale[0]['tdtext']); ?><a href="<?php echo base_url().'ReportModule/ledger_folio?refno='.str_replace(' ', '', $accountdetail[0]).'&periodfrom='.$from_id.'&periodto='.$to_id; ?>" target="_blank"><i class="far fa-question-circle ml-2"></i></a></td>
            <td class="text-right <?php echo get_amount_class($amount); ?>"><?php echo display_amount($amount); ?></td>
            <td></td>
        </tr>
        <?php 
                endif;
            endif;
        endforeach; 
        ?>
        <tr>
            <td>Total Sales Revenue</td>
            <td></td>
            <th class="text-right"><?php echo display_amount($sales_total); ?></th>
        </tr>
        <?php endif; ?>

        <tr>
            <th colspan="3">&nbsp;</th>
        </tr>
        
        <!-- COST OF SALES SECTION -->
        <tr class="section-header">
            <th colspan="3">COST OF SALES</th>
        </tr>
        
        <?php if(!empty($pnl_trlist[2])): ?>
        <tr>
            <th><u>Material Costs and Direct Expenses</u></th>
            <td></td>
            <td></td>
        </tr>
        <?php 
        $cost_of_sales_total = 0;
        foreach($pnl_trlist[2] as $index => $tr_cost): 
            if(!empty($tr_cost) && isset($tr_cost[0]['tdtext']) && strpos($tr_cost[0]['tdtext'], 'Total') === false):
                $amount = isset($tr_cost[1]['tdtext']) ? floatval(str_replace(',', '', $tr_cost[1]['tdtext'])) : 0;
                $cost_of_sales_total += $amount;

                if($amount != 0):
        ?>
        <tr>
            <td><?php echo $tr_cost[0]['tdtext']; $accountdetail = explode('-', $tr_cost[0]['tdtext']); ?><a href="<?php echo base_url().'ReportModule/ledger_folio?refno='.str_replace(' ', '', $accountdetail[0]).'&periodfrom='.$from_id.'&periodto='.$to_id; ?>" target="_blank"><i class="far fa-question-circle ml-2"></i></a></td>
            <td class="text-right"><?php echo display_amount($amount); ?></td>
            <td></td>
        </tr>
        <?php 
                endif;
            endif;
        endforeach; 
        ?>
        
        <!-- COST OF SALES Total -->
        <tr class="total-row">
            <th>COST OF SALES</th>
            <td></td>
            <th class="text-right negative-amount">(<?php echo format_currency($cost_of_sales_total); ?>)</th>
        </tr>
        <?php endif; ?>

        <!-- GROSS PROFIT -->
        <tr class="total-row">
            <th>GROSS PROFIT</th>
            <td></td>
            <th class="text-right <?php echo get_amount_class($gross_profit); ?>">
                <?php echo display_amount($gross_profit); ?>
            </th>
        </tr>

        <!-- OTHER INCOME SECTION -->
        <?php if(!empty($pnl_trlist[4])): ?>
        <tr>
            <th colspan="3">&nbsp;</th>
        </tr>
        <tr class="section-header">
            <th colspan="3">OTHER INCOME</th>
        </tr>
        
        <?php 
        $other_income_total = 0;
        foreach($pnl_trlist[4] as $index => $tr_income): 
            if(!empty($tr_income) && isset($tr_income[0]['tdtext']) && strpos($tr_income[0]['tdtext'], 'Total') === false):
                $amount = isset($tr_income[1]['tdtext']) ? floatval(str_replace(',', '', $tr_income[1]['tdtext'])) : 0;
                $other_income_total += $amount;

                if($amount != 0):
        ?>
        <tr>
            <td><?php echo $tr_income[0]['tdtext']; $accountdetail = explode('-', $tr_income[0]['tdtext']); ?><a href="<?php echo base_url().'ReportModule/ledger_folio?refno='.str_replace(' ', '', $accountdetail[0]).'&periodfrom='.$from_id.'&periodto='.$to_id; ?>" target="_blank"><i class="far fa-question-circle ml-2"></i></a></td>
            <td class="text-right"><?php echo display_amount($amount); ?></td>
            <td></td>
        </tr>
        <?php 
                endif;
            endif;
        endforeach; 
        ?>
        <tr>
            <td>Total Other Income</td>
            <td></td>
            <th class="text-right"><?php echo display_amount($other_income_total); ?></th>
        </tr>
        <?php endif; ?>

        <!-- TOTAL INCOME -->
        <tr>
            <th>TOTAL INCOME</th>
            <td></td>
            <th class="text-right <?php echo get_amount_class($tot_income); ?>">
                <?php echo display_amount($tot_income); ?>
            </th>
        </tr>

        <!-- EXPENSES SECTION -->
        <tr>
            <th colspan="3">&nbsp;</th>
        </tr>
        <tr class="section-header">
            <th colspan="3">EXPENSES</th>
        </tr>
        
        <?php if(!empty($pnl_trlist[3])): ?>
        <?php 
        $expenses_total = 0;
        foreach($pnl_trlist[3] as $index => $tr_expense): 
            if(!empty($tr_expense) && isset($tr_expense[0]['tdtext']) && strpos($tr_expense[0]['tdtext'], 'Total') === false):
                $amount = isset($tr_expense[1]['tdtext']) ? floatval(str_replace(',', '', $tr_expense[1]['tdtext'])) : 0;
                $expenses_total += $amount;

                if($amount != 0):
        ?>
        <tr>
            <td><?php echo $tr_expense[0]['tdtext']; $accountdetail = explode('-', $tr_expense[0]['tdtext']); ?><a href="<?php echo base_url().'ReportModule/ledger_folio?refno='.str_replace(' ', '', $accountdetail[0]).'&periodfrom='.$from_id.'&periodto='.$to_id; ?>" target="_blank"><i class="far fa-question-circle ml-2"></i></a></td>
            <td class="text-right negative-amount">(<?php echo format_currency($amount); ?>)</td>
            <td></td>
        </tr>
        <?php 
                endif;
            endif;
        endforeach; 
        ?>
        <tr class="total-row">
            <th>TOTAL EXPENSES</th>
            <td></td>
            <th class="text-right negative-amount border-dark border-left-0 border-right-0">(<?php echo format_currency($expenses_total); ?>)</th>
        </tr>
        <?php endif; ?>

        <!-- NET PROFIT -->
        <tr class="total-row">
            <th>NET PROFIT/(LOSS) FOR THE PERIOD</th>
            <td></td>
            <th class="text-right <?php echo get_amount_class($tot_transfer); ?>" style="border-bottom: #1f2d41 4px double">
                <?php echo display_amount($tot_transfer); ?>
            </th>
        </tr>

    </table>
</div>

<!-- Hidden fields for reporting -->
<input type="hidden" id="periodtitle" value="<?php echo $rpt_from.' / '.$rpt_to; ?>">
<input type="hidden" id="reporttitle" value="Profit and Loss Statement">
<input type="hidden" id="filetitle" value="PNL_Report_">
<input type="hidden" id="reporttype" value="2">

<!-- Hidden fields for reporting -->
<input type="hidden" id="periodtitle" value="<?php echo $rpt_from.' / '.$rpt_to; ?>">
<input type="hidden" id="reporttitle" value="Profit and Loss Statement">
<input type="hidden" id="filetitle" value="PNL_Report_">
<input type="hidden" id="reporttype" value="2">

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