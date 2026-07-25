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

// Renders the item rows for one heading section (built by add_pnl_heading_sect()).
// Skips the auto-appended "Total" row from the array since each section prints
// its own labeled total row explicitly below.
function render_section_items($sect_trlist) {
    if (empty($sect_trlist)) return;
    $count = count($sect_trlist);
    foreach ($sect_trlist as $i => $tr) {
        if ($i == $count - 1) continue; // last row is the generic "Total" row, skip it
        if (!isset($tr[0]['tdtext'])) continue;
        $amount = isset($tr[1]['tdtext']) ? floatval(str_replace(',', '', $tr[1]['tdtext'])) : 0;
        ?>
        <tr>
            <td><?php echo $tr[0]['tdtext']; ?></td>
            <td class="text-right <?php echo get_amount_class($amount); ?>"><?php echo display_amount($amount); ?></td>
            <td></td>
        </tr>
        <?php
    }
}
?>

<div class="col-12 text-right">
    <button type="button" id="btnexcelconvert" class="btn btn-success btn-sm px-4 mb-3 mr-2"><i class="fas fa-file-excel mr-2"></i>Excel</button>
    <button type="button" id="btnpdfconvert" class="btn btn-danger btn-sm px-4 mb-3"><i class="fas fa-file-pdf mr-2"></i>PDF</button>
</div>
<div class="col-12 pnl-report">
    <h6 class="title-style small font-weight-bold mt-2">
        <span><?php echo $_SESSION['company'] ?> - Profit & Loss Statement <?php echo $rpt_from.' / '.$rpt_to; ?></span>
    </h6>

    <table class="table table-striped table-bordered table-sm small" id="tablereport">
        <tr>
            <th>Section | Account No</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
        <!-- REVENUE -->
        <tr class="section-header"><th colspan="3">REVENUE</th></tr>
        <?php render_section_items($pnl_trlist['revenue']); ?>
        <tr class="total-row">
            <th>Total Revenue</th>
            <td></td>
            <th class="text-right"><?php echo display_amount($tot_sale); ?></th>
        </tr>

        <tr><th colspan="3">&nbsp;</th></tr>

        <!-- COST OF SALES -->
        <tr class="section-header"><th colspan="3">COST OF SALES</th></tr>
        <?php render_section_items($pnl_trlist['cost_of_sales']); ?>
        <tr class="total-row">
            <th>Cost of Sales</th>
            <td></td>
            <th class="text-right negative-amount">(<?php echo format_currency($cost_of_sale); ?>)</th>
        </tr>

        <!-- GROSS PROFIT -->
        <tr class="total-row">
            <th>GROSS PROFIT (Revenue - Cost of Sales)</th>
            <td></td>
            <th class="text-right <?php echo get_amount_class($gross_profit); ?>"><?php echo display_amount($gross_profit); ?></th>
        </tr>

        <tr><th colspan="3">&nbsp;</th></tr>

        <!-- OTHER INCOME -->
        <?php if (!empty($pnl_trlist['other_income'])): ?>
        <tr class="section-header"><th colspan="3">OTHER INCOME</th></tr>
        <?php render_section_items($pnl_trlist['other_income']); ?>
        <tr class="total-row">
            <th>Total Other Income</th>
            <td></td>
            <th class="text-right"><?php echo display_amount($tot_other_income); ?></th>
        </tr>
        <?php endif; ?>

        <tr><th colspan="3">&nbsp;</th></tr>

        <!-- OPERATING EXPENSES -->
        <tr class="section-header"><th colspan="3">OPERATING EXPENSES (INDIRECT)</th></tr>
        <?php render_section_items($pnl_trlist['operating_expenses']); ?>
        <tr class="total-row">
            <th>Total Operating Expenses</th>
            <td></td>
            <th class="text-right negative-amount">(<?php echo format_currency($tot_operating_expenses); ?>)</th>
        </tr>

        <tr class="total-row">
            <th>OPERATING PROFIT</th>
            <td></td>
            <th class="text-right <?php echo get_amount_class($operating_profit); ?>"><?php echo display_amount($operating_profit); ?></th>
        </tr>

        <tr><th colspan="3">&nbsp;</th></tr>

        <!-- FINANCE COSTS -->
        <tr class="section-header"><th colspan="3">FINANCE COSTS</th></tr>
        <?php render_section_items($pnl_trlist['finance_costs']); ?>
        <tr class="total-row">
            <th>Total Finance Costs</th>
            <td></td>
            <th class="text-right negative-amount">(<?php echo format_currency($tot_finance_costs); ?>)</th>
        </tr>

        <!-- PROFIT BEFORE TAX -->
        <tr class="total-row">
            <th>PROFIT BEFORE TAX (PBT)</th>
            <td></td>
            <th class="text-right <?php echo get_amount_class($profit_before_tax); ?>"><?php echo display_amount($profit_before_tax); ?></th>
        </tr>

        <tr><th colspan="3">&nbsp;</th></tr>

        <!-- TAXES -->
        <tr class="section-header"><th colspan="3">TAXES</th></tr>
        <?php render_section_items($pnl_trlist['taxes']); ?>
        <tr class="total-row">
            <th>Total Taxes</th>
            <td></td>
            <th class="text-right negative-amount">(<?php echo format_currency($tot_taxes); ?>)</th>
        </tr>

        <!-- NET PROFIT AFTER TAX -->
        <tr class="total-row">
            <th>NET PROFIT AFTER TAX (NPAT)</th>
            <td></td>
            <th class="text-right <?php echo get_amount_class($net_profit_after_tax); ?>" style="border-bottom: #1f2d41 4px double">
                <?php echo display_amount($net_profit_after_tax); ?>
            </th>
        </tr>

        <tr><th colspan="3">&nbsp;</th></tr>

        <!-- EARNINGS ALLOCATION -->
        <tr class="section-header"><th colspan="3">EARNINGS ALLOCATION</th></tr>
        <tr>
            <td>Transfer to Retained Earnings</td>
            <td class="text-right <?php echo get_amount_class($transfer_to_retained_earnings); ?>"><?php echo display_amount($transfer_to_retained_earnings); ?></td>
            <td></td>
        </tr>
        <?php if ($tot_dividends != 0): ?>
        <tr>
            <td>Dividends (if declared)</td>
            <td class="text-right negative-amount">(<?php echo format_currency($tot_dividends); ?>)</td>
            <td></td>
        </tr>
        <?php endif; ?>

    </table>
</div>

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