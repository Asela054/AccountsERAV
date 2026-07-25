<div class="col-12 text-right">
    <button type="button" id="btnexcelconvert" class="btn btn-success btn-sm px-4 mb-3 mr-2"><i class="fas fa-file-excel mr-2"></i>Excel</button>
    <button type="button" id="btnpdfconvert"
            class="btn btn-danger btn-sm px-4 mb-3">
        <i class="fas fa-file-pdf mr-2"></i>PDF
    </button>
</div>

<div class="col-12">

    <h6 class="title-style font-weight-bold small mt-2"><span><?php echo $_SESSION['company']; ?> Purchase Audit Report <?php echo $report_duration; ?></span></h6>

    <table class="table table-bordered table-striped table-sm small" id="tablereport">     
        <thead>
            <tr>
                <th style="width:10%;">Date</th>
                <th style="width:25%;">Account</th>
                <th style="width:30%;">Description</th>
                <th class="text-right" style="width:10%;">Amount</th>
                <th class="text-center" style="width:10%;">Dr/Cr</th>
            </tr>
        </thead>

        <tbody>

            <?php if(!empty($purchase_items)): ?>
                <?php foreach($purchase_items as $row): ?>
                    <?php if((float)$row->accamount != 0): ?>
                    <tr>
                        <td><?php echo $row->tradate; ?></td>
                        <td>
                            <?php echo $row->accountno . ' - ' . $row->accountname; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row->narration); ?></td>
                        <td class="text-right">
                            <?php echo number_format($row->accamount, 2); ?>
                        </td>
                        <td class="text-center"><?php echo $row->crdr; ?></td>
                    </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">
                        No Purchase Transactions Found
                    </td>
                </tr>
            <?php endif; ?>

            <!-- Summary -->
            <tr>
                <th colspan="3" class="text-right">Total Purchase Amount</th>
                <th class="text-right">
                    <?php echo number_format($total_purchase, 2); ?>
                </th>
                <th></th>
            </tr>

            <tr>
                <th colspan="3" class="text-right">Number of Transactions</th>
                <th class="text-right">
                    <?php echo $transaction_count; ?>
                </th>
                <th></th>
            </tr>

        </tbody>
    </table>

</div>
<input type="hidden" id="periodtitle" value="<?php echo $rpt_from.' / '.$rpt_to; ?>">
<input type="hidden" id="reporttitle" value="Purchase Audit Report">
<input type="hidden" id="filetitle" value="purchase_audit_report_">
<input type="hidden" id="reporttype" value="8">

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