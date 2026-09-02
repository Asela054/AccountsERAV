<?php
// ── Pre-calculate outside loops ──────────────────────────────────────────
$balancedata = $balanceinfo->result();

// Last EQ account idtbl_account find කරනවා — P&L row ඒකෙන් පස්සේ ONCE show
$last_eq_account_id = null;
foreach($balancedata as $r){
    if($r->idtbl_account_category == 5){
        $last_eq_account_id = $r->idtbl_account;
    }
}
// If no EQ accounts found, we'll show P&L at very end of EQ&LI section
?>
<div class="col-12 text-right">
    <button type="button" id="btnexcelconvert" class="btn btn-success btn-sm px-4 mb-3 mr-2"><i class="fas fa-file-excel mr-2"></i>Excel</button>
    <button type="button" id="btnpdfconvert"
            class="btn btn-danger btn-sm px-4 mb-3">
        <i class="fas fa-file-pdf mr-2"></i>PDF
    </button>
</div>
<div class="col-12">
<h6 class="title-style small font-weight-bold mt-2">
    <span><?php echo $_SESSION['company']; ?> - Balance Sheet Statement <?php echo $rpt_from.' / '.$rpt_to; ?></span>
</h6>
<table class="table table-bordered table-sm table-striped small" id="tablereport">
    <thead>
        <tr>
            <th>Account Type</th>
            <th>Account No</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>

    <!-- ═══════════════════════════════════════
         ASSETS SECTION
    ═══════════════════════════════════════ -->
    <tr><th colspan='4'><u>ASSETS</u></th></tr>

    <?php
    $subcategory    = 0;
    $nestcategory   = 0;
    $subtotal       = 0;
    $nesttotal      = 0;
    $asset_nettotal = 0;

    foreach($balancedata as $rowdatalist){
        if($rowdatalist->idtbl_account_category == 1){

            if($subcategory != $rowdatalist->tbl_account_subcategory_idtbl_account_subcategory){
                $i = 1; $subtotal = 0;
                $subcategory = $rowdatalist->tbl_account_subcategory_idtbl_account_subcategory;
                $showsub = $rowdatalist->subcategory;
                if($nestcategory != $rowdatalist->idtbl_account_nestcategory){
                    $j = 1; $nesttotal = 0;
                    $nestcategory = $rowdatalist->idtbl_account_nestcategory;
                    $shownest = $rowdatalist->nestcategory;
                } else { $shownest = ''; }
            } else {
                $showsub = '';
                if($nestcategory != $rowdatalist->idtbl_account_nestcategory){
                    $j = 1; $nesttotal = 0;
                    $nestcategory = $rowdatalist->idtbl_account_nestcategory;
                    $shownest = $rowdatalist->nestcategory;
                } else { $shownest = ''; }
            }

            if(!empty($showsub)): ?>
            <tr><th colspan='4'><?php echo $showsub; ?></th></tr>
            <?php endif; ?>
            <?php if($rowdatalist->nettrabalreal != 0): ?>
            <tr>
                <td><?php echo $shownest; ?></td>
                <td><?php echo $rowdatalist->accountno.' - '.$rowdatalist->accountname; ?><a href="<?php echo base_url().'ReportModule/ledger_folio?refno='.str_replace(' ', '', $rowdatalist->accountno).'&periodfrom='.$from_id.'&periodto='.$to_id; ?>" target="_blank"><i class="far fa-question-circle ml-2"></i></a></td>
                <td class="text-right">
                    <?php
                    if($rowdatalist->nettrabalreal < 0)
                        echo '('.number_format($rowdatalist->nettrabal, 2).')';
                    else
                        echo number_format($rowdatalist->nettrabal, 2);
                    $subtotal  += $rowdatalist->nettrabalreal;
                    $nesttotal += $rowdatalist->nettrabalreal;
                    ?>
                </td>
                <td>&nbsp;</td>
            </tr>
            <?php endif; ?>
            <?php
            // Count accounts in this nest group (AS only)
            $nest_count = count(array_filter($balancedata, function($r) use($nestcategory){
                return $r->idtbl_account_nestcategory == $nestcategory
                    && $r->idtbl_account_category == 1;
            }));
            $sub_count = count(array_filter($balancedata, function($r) use($subcategory){
                return $r->tbl_account_subcategory_idtbl_account_subcategory == $subcategory
                    && $r->idtbl_account_category == 1;
            }));

            if($nest_count == $j): ?>
            <tr>
                <td>&nbsp;</td><td>&nbsp;</td>
                <th class="text-right">
                    <?php
                    if($nesttotal < 0) echo '('.number_format(abs($nesttotal), 2).')';
                    else echo number_format($nesttotal, 2);
                    ?>
                </th>
                <th class="text-right">
                    <?php
                    if($sub_count == $i){
                        if($subtotal < 0) echo '('.number_format(abs($subtotal), 2).')';
                        else echo number_format($subtotal, 2);
                        $asset_nettotal += $subtotal;
                    }
                    ?>
                </th>
            </tr>
            <?php endif;
            $i++; $j++;
        }
    } ?>

    <tr>
        <th colspan='3' class="border border-right-0">&nbsp;</th>
        <th class="text-right border-dark border-right-0 border-left-0">&nbsp;</th>
    </tr>
    <tr>
        <th colspan='3'>TOTAL ASSETS</th>
        <th class="text-right" style="border-bottom:#1f2d41 4px double">
            <?php
            if($asset_nettotal < 0) echo '('.number_format(abs($asset_nettotal), 2).')';
            else echo number_format($asset_nettotal, 2);
            ?>
        </th>
    </tr>
    <tr><th colspan='4'>&nbsp;</th></tr>

    <!-- ═══════════════════════════════════════
         EQUITY & LIABILITIES SECTION
    ═══════════════════════════════════════ -->
    <tr><th colspan='4'><u>EQUITY &amp; LIABILITIES</u></th></tr>

    <?php
    $subcategory    = 0;
    $nestcategory   = 0;
    $subtotal       = 0;
    $nesttotal      = 0;
    $eq_li_nettotal = 0;
    $pnl_added      = false;  // ← KEY: P&L row ONCE only flag

    foreach($balancedata as $rowdatalist){
        if($rowdatalist->idtbl_account_category > 1){

            if($subcategory != $rowdatalist->tbl_account_subcategory_idtbl_account_subcategory){
                $i = 1; $subtotal = 0;
                $subcategory = $rowdatalist->tbl_account_subcategory_idtbl_account_subcategory;
                $showsub = $rowdatalist->subcategory;
                if($nestcategory != $rowdatalist->idtbl_account_nestcategory){
                    $j = 1; $nesttotal = 0;
                    $nestcategory = $rowdatalist->idtbl_account_nestcategory;
                    $shownest = $rowdatalist->nestcategory;
                } else { $shownest = ''; }
            } else {
                $showsub = '';
                if($nestcategory != $rowdatalist->idtbl_account_nestcategory){
                    $j = 1; $nesttotal = 0;
                    $nestcategory = $rowdatalist->idtbl_account_nestcategory;
                    $shownest = $rowdatalist->nestcategory;
                } else { $shownest = ''; }
            }

            if(!empty($showsub)): ?>
            <tr><th colspan='4'><?php echo $showsub; ?></th></tr>
            <?php endif; ?>
            <?php if($rowdatalist->nettrabalreal != 0): ?>
            <tr>
                <td><?php echo $shownest; ?></td>
                <td><?php echo $rowdatalist->accountno.' - '.$rowdatalist->accountname; ?><a href="<?php echo base_url().'ReportModule/ledger_folio?refno='.str_replace(' ', '', $rowdatalist->accountno).'&periodfrom='.$from_id.'&periodto='.$to_id; ?>" target="_blank"><i class="far fa-question-circle ml-2"></i></a></td>
                <td class="text-right">
                    <?php
                    if($rowdatalist->nettrabalreal > 0)
                        echo '('.number_format($rowdatalist->nettrabal, 2).')';
                    else
                        echo number_format($rowdatalist->nettrabal, 2);
                    $subtotal  += ($rowdatalist->nettrabalreal * -1);
                    $nesttotal += ($rowdatalist->nettrabalreal * -1);
                    ?>
                </td>
                <th class="text-right">&nbsp;</th>
            </tr>
            <?php endif; ?>
            <?php
            $nest_count = count(array_filter($balancedata, function($r) use($nestcategory){
                return $r->idtbl_account_nestcategory == $nestcategory
                    && $r->idtbl_account_category > 1;
            }));
            $sub_count = count(array_filter($balancedata, function($r) use($subcategory){
                return $r->tbl_account_subcategory_idtbl_account_subcategory == $subcategory
                    && $r->idtbl_account_category > 1;
            }));

            if($nest_count == $j): ?>
            <tr>
                <td>&nbsp;</td><td>&nbsp;</td>
                <th class="text-right">
                    <?php
                    if($nesttotal < 0) echo '('.number_format(abs($nesttotal), 2).')';
                    else echo number_format($nesttotal, 2);
                    ?>
                </th>
                <th class="text-right">
                    <?php
                    if($sub_count == $i){
                        if($subtotal < 0) echo '('.number_format(abs($subtotal), 2).')';
                        else echo number_format($subtotal, 2);
                        $eq_li_nettotal += $subtotal;
                    }
                    ?>
                </th>
            </tr>

            <?php
            // ── Net P&L: show ONCE after LAST EQ account's nest group ──────
            // $last_eq_account_id = last EQ account in data (pre-calculated above)
            if(
                !$pnl_added &&
                $rowdatalist->idtbl_account == $last_eq_account_id
            ):
                $pnl_added   = true;
                $pnl_display = abs($net_profit_loss);
                $pnl_label   = $net_profit_loss >= 0 ? 'Net Profit' : 'Net Loss';
                // Add to totals ONCE
                $eq_li_nettotal += $net_profit_loss;
            ?>
            <tr>
                <td><em>Current Period</em></td>
                <td><em><?php echo $pnl_label; ?> for the Period</em></td>
                <td class="text-right">
                    <?php echo number_format($pnl_display, 2); ?>
                </td>
                <th class="text-right">
                    <?php echo number_format($pnl_display, 2); ?>
                </th>
            </tr>
            <?php endif; ?>

            <?php endif;
            $i++; $j++;
        }
    }

    // ── Fallback: if no EQ accounts exist, show P&L at end ───────────────
    if(!$pnl_added && $net_profit_loss != 0):
        $pnl_display     = abs($net_profit_loss);
        $pnl_label       = $net_profit_loss >= 0 ? 'Net Profit' : 'Net Loss';
        $eq_li_nettotal += $net_profit_loss;
    ?>
    <tr>
        <td><em>Current Period</em></td>
        <td><em><?php echo $pnl_label; ?> for the Period</em></td>
        <td class="text-right"><?php echo number_format($pnl_display, 2); ?></td>
        <th class="text-right"><?php echo number_format($pnl_display, 2); ?></th>
    </tr>
    <?php endif; ?>

    <tr>
        <th colspan='3' class="border border-right-0">&nbsp;</th>
        <th class="text-right border-dark border-right-0 border-left-0">&nbsp;</th>
    </tr>
    <tr>
        <th colspan='3'>TOTAL EQUITY &amp; LIABILITIES</th>
        <th class="text-right" style="border-bottom:#1f2d41 4px double">
            <?php echo number_format(abs($eq_li_nettotal), 2); ?>
        </th>
    </tr>
    <tr><th colspan='4'>&nbsp;</th></tr>

    <?php
    // ── Balance check ─────────────────────────────────────────────────────
    $balance_diff = abs($asset_nettotal) - abs($eq_li_nettotal);
    if(abs($balance_diff) > 0.01): ?>
    <tr style="background:#fff3cd;">
        <th colspan='3' style="color:#856404;">⚠ Balance Difference</th>
        <th class="text-right" style="color:#856404;">
            <?php echo number_format(abs($balance_diff), 2); ?>
        </th>
    </tr>
    <?php endif; ?>

    </tbody>
</table>
</div>

<input type="hidden" id="periodtitle"  value="<?php echo $rpt_from.' / '.$rpt_to; ?>">
<input type="hidden" id="reporttitle"  value="Balance Sheet Statement">
<input type="hidden" id="filetitle"    value="balance_sheet_">
<input type="hidden" id="reporttype"   value="1">

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