<?php
// Safe defaults
$total_net_income    = (float)($net_income        ?? 0);
$total_adjustments   = (float)($total_adjustments  ?? 0);
$total_net_operating = (float)($net_operating      ?? 0);
$total_net_investing = (float)($net_investing      ?? 0);
$total_net_financing = (float)($net_financing      ?? 0);
$total_net_change    = (float)($net_cash_change    ?? 0);
$total_opening_cash  = (float)($opening_cash       ?? 0);
$total_closing_cash  = (float)($closing_cash       ?? 0);
?>

<div class="col-12 text-right">
    <button type="button" id="btnexcelconvert" class="btn btn-success btn-sm px-4 mb-3 mr-2">
        <i class="fas fa-file-excel mr-2"></i>Excel
    </button>
    <button type="button" id="btnpdfconvert" class="btn btn-danger btn-sm px-4 mb-3">
        <i class="fas fa-file-pdf mr-2"></i>PDF
    </button>
</div>

<div class="col-12">
    <h6 class="title-style font-weight-bold mt-2 small"><span><?php echo $_SESSION['company']; ?> Statement Of Cash Flows Report <?php echo $report_duration; ?></span></h6>

    <table class="table table-bordered table-striped table-sm small" id="tablereport">
        <thead>
            <tr>
                <th class="text-left" style="width: 70%;">&nbsp;</th>
                <th class="text-right" style="width: 30%;"><?php echo $report_duration; ?></th>
            </tr>
        </thead>
        <tbody>

        <!-- ============================================================= -->
        <!-- OPERATING ACTIVITIES -->
        <!-- ============================================================= -->
        <tr>
            <th colspan="2">OPERATING ACTIVITIES</th>
        </tr>

        <tr>
            <th style="padding-left:20px;">Net Income</th>
            <td class="text-right">
                <?php
                echo ($total_net_income < 0 ? '(' : '');
                echo number_format(abs($total_net_income), 2);
                echo ($total_net_income < 0 ? ')' : '');
                ?>
            </td>
        </tr>

        <?php if(!empty($adjustment_items)): ?>
        <tr>
            <td style="padding-left:20px;" colspan="2">
                Adjustments to reconcile Net Income to net cash provided by operations:
            </td>
        </tr>
            <?php foreach($adjustment_items as $row): ?>
                <?php if((float)$row->cash_flow_effect != 0): ?>
                <tr>
                    <td style="padding-left:40px;">
                        <?php echo htmlspecialchars($row->accname); ?>
                    </td>
                    <td class="text-right">
                        <?php
                        $val = $row->cash_flow_effect;
                        echo ($val < 0 ? '(' : '');
                        echo number_format(abs($val), 2);
                        echo ($val < 0 ? ')' : '');
                        ?>
                    </td>
                </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <tr>
            <th>Net cash provided by Operating Activities</th>
            <th class="text-right">
                
                    <?php
                    echo ($total_net_operating < 0 ? '(' : '');
                    echo number_format(abs($total_net_operating), 2);
                    echo ($total_net_operating < 0 ? ')' : '');
                    ?>
                
            </th>
        </tr>


        <!-- ============================================================= -->
        <!-- INVESTING ACTIVITIES -->
        <!-- ============================================================= -->
        <tr>
            <th colspan="2">INVESTING ACTIVITIES</th>
        </tr>

            <?php foreach($investing_items as $row): ?>
                <?php if((float)$row->cash_flow_effect != 0): ?>
                <tr>
                    <td style="padding-left:20px;">
                        <?php echo htmlspecialchars($row->accname); ?>
                    </td>
                    <td class="text-right">
                        <?php
                        $val = $row->cash_flow_effect;
                        echo ($val < 0 ? '(' : '');
                        echo number_format(abs($val), 2);
                        echo ($val < 0 ? ')' : '');
                        ?>
                    </td>
                </tr>
                <?php endif; ?>
            <?php endforeach; ?>

        <tr>
            <th>Net cash provided by Investing Activities</th>
            <th class="text-right">
                
                    <?php
                    echo ($total_net_investing < 0 ? '(' : '');
                    echo number_format(abs($total_net_investing), 2);
                    echo ($total_net_investing < 0 ? ')' : '');
                    ?>
                
            </th>
        </tr>


        <!-- ============================================================= -->
        <!-- FINANCING ACTIVITIES -->
        <!-- ============================================================= -->
        <tr>
            <th colspan="2">FINANCING ACTIVITIES</th>
        </tr>

            <?php foreach($financing_items as $row): ?>
                <?php if((float)$row->cash_flow_effect != 0): ?>
                <tr>
                    <td style="padding-left:20px;">
                        <?php echo htmlspecialchars($row->accname); ?>
                    </td>
                    <td class="text-right">
                        <?php
                        $val = $row->cash_flow_effect;
                        echo ($val < 0 ? '(' : '');
                        echo number_format(abs($val), 2);
                        echo ($val < 0 ? ')' : '');
                        ?>
                    </td>
                </tr>
                <?php endif; ?>
            <?php endforeach; ?>

        <tr>
            <th>Net cash provided by Financing Activities</th>
            <th class="text-right">
                
                    <?php
                    echo ($total_net_financing < 0 ? '(' : '');
                    echo number_format(abs($total_net_financing), 2);
                    echo ($total_net_financing < 0 ? ')' : '');
                    ?>
                
            </th>
        </tr>


        <!-- ============================================================= -->
        <!-- SUMMARY -->
        <!-- ============================================================= -->
        <tr>
            <th>Net cash increase for period</th>
            <th class="text-right">
                
                    <?php
                    echo ($total_net_change < 0 ? '(' : '');
                    echo number_format(abs($total_net_change), 2);
                    echo ($total_net_change < 0 ? ')' : '');
                    ?>
                
            </th>
        </tr>

        <tr>
            <td>Cash at beginning of period</td>
            <td class="text-right">
                <?php echo number_format($total_opening_cash, 2); ?>
            </td>
        </tr>

        <tr>
            <td>Cash at end of period</td>
            <th class="text-right">
                <?php echo number_format($total_closing_cash, 2); ?>
            </th>
        </tr>

        </tbody>
    </table>

    <?php if(!$is_verified): ?>
    <div class="text-danger small mt-1">
        ⚠ Opening cash + net change does not tie to closing cash — check entries.
    </div>
    <?php endif; ?>
</div>

<input type="hidden" id="periodtitle" value="<?php echo $report_duration; ?>">
<input type="hidden" id="reporttitle" value="Cash Flow Statement">
<input type="hidden" id="filetitle" value="cash_flow_statement_">
<input type="hidden" id="reporttype" value="7">

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