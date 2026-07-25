<?php 
$balancetotal=0;
?>
<div class="col-12 text-right">
    <button type="button" id="btnexcelconvert" class="btn btn-success btn-sm px-4 mb-3 mr-2"><i class="fas fa-file-excel mr-2"></i>Excel</button>
    <button type="button" id="btnpdfconvert" class="btn btn-danger btn-sm px-4 mb-3"><i class="fas fa-file-pdf mr-2"></i>PDF</button>
</div>
<div class="col-12">
    <h6 class="title-style small font-weight-bold mt-2"><span><?php echo $_SESSION['company'] ?> - <?php echo $debtorname; ?> Statement <?php echo $report_duration; ?></span></h6>
    <table class="table table-striped table-bordered table-sm small" id="tablereport">
        <thead>
            <tr>
                <th>DATE</th>
                <th>REF NO</th>
                <th>DESCRIPTION</th>
                <th class="text-right">DR</th>
                <th class="text-right">CR</th>
                <th class="text-right">BALANCE</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th colspan="5">Opening Balnace</th>
                <th class="text-right"><?php $balancetotal=$reportopenbalance->row(0)->openbalance; echo number_format($reportopenbalance->row(0)->openbalance, 2); ?></th>
            </tr>
            <?php foreach($reportdata->result() as $rowdata){ ?>
            <tr>
                <td><?php echo $rowdata->invpaydate ?></td>
                <td><?php echo $rowdata->receiptno ?></td>
                <td><?php echo $rowdata->narration; if($rowdata->tratype=='C'){echo ' ('.$rowdata->chequeno.' - '.$rowdata->chequedate.')';} ?></td>
                <?php if($rowdata->tratype=='D'){ ?>
                <td class="text-right"><?php $balancetotal+=$rowdata->amount; echo number_format($rowdata->amount, 2); ?></td>
                <td>&nbsp;</td>
                <?php } if($rowdata->tratype=='C'){ ?>
                <td>&nbsp;</td>
                <td class="text-right"><?php $balancetotal-=$rowdata->amount; echo number_format($rowdata->amount, 2); ?></td>
                <?php } ?>
                <td class="text-right"><?php echo number_format($balancetotal, 2); ?></td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5">Closing Balance</th>
                <th class="text-right"><?php echo number_format($balancetotal, 2); ?></th>
            </tr>
        </tfoot>
    </table>
</div>
<input type="hidden" id="periodtitle" value="<?php echo $report_duration; ?>">
<input type="hidden" id="reporttitle" value="<?php echo $debtorname; ?> Statement">
<input type="hidden" id="filetitle" value="<?php echo $debtorname; ?>_sheet_">
<input type="hidden" id="reporttype" value="5">

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