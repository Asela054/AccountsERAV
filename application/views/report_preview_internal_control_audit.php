<div class="col-12 text-right">
    <button type="button" id="btnexcelconvert" class="btn btn-success btn-sm px-4 mb-3 mr-2"><i class="fas fa-file-excel mr-2"></i>Excel</button>
    <button type="button" id="btnpdfconvert"
            class="btn btn-danger btn-sm px-4 mb-3">
        <i class="fas fa-file-pdf mr-2"></i>PDF
    </button>
</div>

<div class="col-12">

    <h6 class="title-style font-weight-bold mt-2 small"><span><?php echo $_SESSION['company']; ?> Internal Control Audit Report <?php echo $report_duration; ?></span></h6>

    <table class="table table-striped table-bordered table-sm small" id="tablereport">

        <tbody>

        <!-- Duplicate Documents -->
        <tr>
            <th colspan="2">Duplicate Document Numbers</th>
        </tr>
        <?php if(!empty($duplicate_documents)): ?>
            <?php foreach($duplicate_documents as $row): ?>
                <tr>
                    <td>Document No: <?php echo $row->documentno; ?></td>
                    <td>Count: <?php echo $row->duplicate_count; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="2">No Duplicate Documents Found</td></tr>
        <?php endif; ?>

        <!-- Zero Transactions -->
        <tr>
            <th colspan="2">Zero Amount Transactions</th>
        </tr>
        <?php if(!empty($zero_transactions)): ?>
            <?php foreach($zero_transactions as $row): ?>
                <tr>
                    <td>Transaction ID: <?php echo $row->idtbl_account_transaction; ?></td>
                    <td>Master ID: <?php echo $row->tbl_master_idtbl_master; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="2">No Zero Amount Transactions</td></tr>
        <?php endif; ?>

        <!-- Unbalanced Entries -->
        <tr>
            <th colspan="3">Unbalanced Entries (Debit ≠ Credit)</th>
        </tr>
        <?php if(!empty($unbalanced_entries)): ?>
            <?php foreach($unbalanced_entries as $row): ?>
                <tr>
                    <td>Master ID: <?php echo $row->tbl_master_idtbl_master; ?></td>
                    <td>Debit: <?php echo number_format($row->total_debit,2); ?></td>
                    <td>Credit: <?php echo number_format($row->total_credit,2); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="3">All Transactions Balanced</td></tr>
        <?php endif; ?>

        <!-- Missing Documents -->
        <tr>
            <th colspan="2">Missing Document Numbers</th>
        </tr>
        <?php if(!empty($missing_documents)): ?>
            <?php foreach($missing_documents as $row): ?>
                <tr>
                    <td>Master ID: <?php echo $row->idtbl_master; ?></td>
                    <td>Document No Missing</td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="2">No Missing Document Numbers</td></tr>
        <?php endif; ?>

        </tbody>
    </table>

</div>
<input type="hidden" id="periodtitle" value="<?php echo $rpt_from.' / '.$rpt_to; ?>">
<input type="hidden" id="reporttitle" value="Internal Control Audit Report">
<input type="hidden" id="filetitle" value="internal_control_audit_">
<input type="hidden" id="reporttype" value="10">

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