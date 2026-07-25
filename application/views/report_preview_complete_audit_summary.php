<div class="col-12 text-right">
    <button type="button" id="btnexcelconvert" class="btn btn-success btn-sm px-4 mb-3 mr-2"><i class="fas fa-file-excel mr-2"></i>Excel</button>
    <button type="button" id="btnpdfconvert"
            class="btn btn-danger btn-sm px-4 mb-3">
        <i class="fas fa-file-pdf mr-2"></i>PDF
    </button>
</div>

<div class="col-12">

    <h6 class="title-style font-weight-bold mt-2 small"><span><?php echo $_SESSION['company']; ?> Complete Audit Summary Report <?php echo $report_duration; ?></span></h6>

    <table class="table table-striped table-bordered table-sm small" id="tablereport">

        <tbody>

        <tr>
            <th>Total Transactions</th>
            <td class="text-right">
                <?php echo number_format($summary->total_transactions); ?>
            </td>
        </tr>

        <tr>
            <th>Total Debit</th>
            <td class="text-right">
                <?php echo number_format($summary->total_debit,2); ?>
            </td>
        </tr>

        <tr>
            <th>Total Credit</th>
            <td class="text-right">
                <?php echo number_format($summary->total_credit,2); ?>
            </td>
        </tr>

        <tr>
            <th>Balanced Status</th>
            <td class="text-right">
                <?php echo $summary->is_balanced ? 'Balanced' : 'Not Balanced'; ?>
            </td>
        </tr>

        <tr><th colspan="2">Internal Control Findings</th></tr>

        <tr><td>Zero Amount Entries</td><td class="text-right"><?php echo $summary->zero_count; ?></td></tr>
        <tr><td>Negative Amount Entries</td><td class="text-right"><?php echo $summary->negative_count; ?></td></tr>
        <tr><td>Invalid DR/CR</td><td class="text-right"><?php echo $summary->invalid_crdr; ?></td></tr>
        <tr><td>Future Date Entries</td><td class="text-right"><?php echo $summary->future_count; ?></td></tr>
        <tr><td>Duplicate Suspicious Entries</td><td class="text-right"><?php echo $summary->duplicate_count; ?></td></tr>
        <tr><td>Invalid Account References</td><td class="text-right"><?php echo $summary->invalid_account; ?></td></tr>

        <tr>
            <th>Overall Audit Score (%)</th>
            <th class="text-right">
                <?php echo $summary->audit_score; ?> %
            </th>
        </tr>

        </tbody>
    </table>

</div>
<input type="hidden" id="periodtitle" value="<?php echo $rpt_from.' / '.$rpt_to; ?>">
<input type="hidden" id="reporttitle" value="Complete Audit Summary Report">
<input type="hidden" id="filetitle" value="complete_audit_summary_">
<input type="hidden" id="reporttype" value="11">

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