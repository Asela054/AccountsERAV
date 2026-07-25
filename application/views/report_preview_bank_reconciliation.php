<div class="col-12 text-right">
    <button type="button" id="btnexcelconvert" class="btn btn-success btn-sm px-4 mb-3 mr-2"><i class="fas fa-file-excel mr-2"></i>Excel</button>
    <button type="button" id="btnpdfconvert"
            class="btn btn-danger btn-sm px-4 mb-3">
        <i class="fas fa-file-pdf mr-2"></i>PDF
    </button>
</div>

<div class="col-12">

    <h6 class="title-style font-weight-bold mt-2 small">
        <span><?php echo $_SESSION['company']; ?> Bank Reconciliation Statement <?php echo $report_duration; ?></span>
    </h6>

    <?php if(!empty($statement)): ?>

    <table class="table table-bordered table-sm small" id="tablereport">
        <tbody>
            <tr>
                <th colspan="2">Bank Account</th>
                <td colspan="4"><?php echo $statement->accountno.' - '.$statement->accountname; ?></td>
            </tr>
            <tr>
                <th colspan="2">Reconciliation Date</th>
                <td colspan="4"><?php echo $statement->bank_rec_date; ?></td>
            </tr>
            <tr>
                <th colspan="2">Batch No</th>
                <td colspan="4"><?php echo $statement->acc_rec_batchno; ?></td>
            </tr>
            <tr>
                <th colspan="2">Approved</th>
                <td colspan="4"><?php echo $statement->rec_approved ? 'Yes' : 'No'; ?></td>
            </tr>

            <!-- ============================================================
                 SECTION 1: BANK STATEMENT (as entered on the rec session)
            ============================================================= -->
            <tr><th colspan="6">Bank Statement</th></tr>

            <?php if($summary->statement_open_bal != 0): ?>
            <tr>
                <td colspan="2">Statement Opening Balance</td>
                <td colspan="4" class="text-right">
                    <?php echo number_format($summary->statement_open_bal, 2); ?>
                </td>
            </tr>
            <?php endif; ?>

            <?php if($summary->statement_tot_dr != 0): ?>
            <tr>
                <td colspan="2" style="padding-left:20px;">Add: Total Deposits (DR)</td>
                <td colspan="4" class="text-right">
                    <?php echo number_format($summary->statement_tot_dr, 2); ?>
                </td>
            </tr>
            <?php endif; ?>

            <?php if($summary->statement_tot_cr != 0): ?>
            <tr>
                <td colspan="2" style="padding-left:20px;">Less: Total Withdrawals (CR)</td>
                <td colspan="4" class="text-right">
                    (<?php echo number_format($summary->statement_tot_cr, 2); ?>)
                </td>
            </tr>
            <?php endif; ?>

            <tr>
                <th colspan="2">Statement Closing Balance</th>
                <th colspan="4" class="text-right">
                    <?php echo number_format($summary->statement_balance, 2); ?>
                </th>
            </tr>

            <!-- ============================================================
                SECTION 2: RECONCILED (CLEARED) TRANSACTIONS
            ============================================================= -->
            <?php if(!empty($reconciled_items)): ?>
            <tr><th colspan="6">&nbsp;</th></tr>
            <tr><th colspan="6">Cleared Transactions (<?php echo $summary->reconciled_count; ?>)</th></tr>
            <tr>
                <th style="width:12%;">Date</th>
                <th style="width:28%;">Narration</th>
                <th style="width:15%;">Bank Transaction No</th>
                <th style="width:15%;">Cheque No</th>
                <th class="text-right" style="width:15%;">Debit</th>
                <th class="text-right" style="width:15%;">Credit</th>
            </tr>

            <?php foreach($reconciled_items as $row): ?>
                <?php if((float)$row->accamount != 0): ?>
                <tr>
                    <td><?php echo $row->tradate; ?></td>
                    <td><?php echo htmlspecialchars($row->narration); ?></td>
                    <td><?= $row->bank_transaction_no ?></td>
                    <td><?= $row->cheque_no ?: '-' ?></td>
                    <td class="text-right">
                        <?php echo $row->crdr == 'D' ? number_format($row->accamount, 2) : ''; ?>
                    </td>
                    <td class="text-right">
                        <?php echo $row->crdr == 'C' ? number_format($row->accamount, 2) : ''; ?>
                    </td>
                </tr>
                <?php endif; ?>
            <?php endforeach; ?>

            <tr>
                <th colspan="4" class="text-right">Total Cleared</th>
                <th class="text-right">
                    <?php echo $summary->reconciled_dr != 0 ? number_format($summary->reconciled_dr, 2) : ''; ?>
                </th>
                <th class="text-right">
                    <?php echo $summary->reconciled_cr != 0 ? number_format($summary->reconciled_cr, 2) : ''; ?>
                </th>
            </tr>

            <tr>
                <td colspan="4">Cleared Balance (Opening + Cleared Dr - Cleared Cr)</td>
                <td colspan="2" class="text-right">
                    <?php echo number_format($summary->cleared_balance, 2); ?>
                </td>
            </tr>
            <tr class="<?php echo abs($summary->beginning_check) < 0.01 ? '' : 'table-danger'; ?>">
                <td colspan="4">
                    <?php echo abs($summary->beginning_check) < 0.01
                        ? 'Cleared Balance matches Statement Closing Balance'
                        : 'Cleared Balance does NOT match Statement Closing Balance'; ?>
                </td>
                <td colspan="2" class="text-right">
                    <?php echo number_format($summary->beginning_check, 2); ?>
                </td>
            </tr>
            <?php endif; ?>

            <!-- ============================================================
                SECTION 3: OUTSTANDING (UNCLEARED) TRANSACTIONS
            ============================================================= -->
            <?php if(!empty($unreconciled_items)): ?>
            <tr><th colspan="6">&nbsp;</th></tr>
            <tr><th colspan="6">Uncleared Deposits & Payment (<?php echo $summary->unreconciled_count; ?>)</th></tr>
            <tr>
                <th style="width:12%;">Date</th>
                <th style="width:28%;">Narration</th>
                <th style="width:15%;">Bank Transaction No</th>
                <th style="width:15%;">Cheque No</th>
                <th class="text-right" style="width:15%;">Debit</th>
                <th class="text-right" style="width:15%;">Credit</th>
            </tr>
            <?php foreach($unreconciled_items as $row): ?>
                <?php if((float)$row->accamount != 0): ?>
                <tr>
                    <td><?php echo $row->tradate; ?></td>
                    <td><?php echo htmlspecialchars($row->narration); ?></td>
                    <td><?= $row->bank_transaction_no ?></td>
                    <td><?= $row->cheque_no ?: '-' ?></td>
                    <td class="text-right">
                        <?php echo $row->crdr == 'D' ? number_format($row->accamount, 2) : ''; ?>
                    </td>
                    <td class="text-right">
                        <?php echo $row->crdr == 'C' ? number_format($row->accamount, 2) : ''; ?>
                    </td>
                </tr>
                <?php endif; ?>
            <?php endforeach; ?>

            <tr>
                <th colspan="4" class="text-right">Total Outstanding</th>
                <th class="text-right">
                    <?php echo $summary->unreconciled_dr != 0 ? number_format($summary->unreconciled_dr, 2) : ''; ?>
                </th>
                <th class="text-right">
                    <?php echo $summary->unreconciled_cr != 0 ? number_format($summary->unreconciled_cr, 2) : ''; ?>
                </th>
            </tr>
            <?php endif; ?>

            <!-- ============================================================
                 SECTION 4: BANK ADJUSTMENTS
            ============================================================= -->
            <?php if(!empty($bank_adjustments)): ?>
            <tr><th colspan="5">&nbsp;</th></tr>
            <tr><th colspan="5">Bank Adjustments (<?php echo $summary->adjustment_count; ?>)</th></tr>
            <tr>
                <th colspan="2" style="width:35%;">Narration</th>
                <th style="width:20%;">DR Account</th>
                <th style="width:20%;">CR Account</th>
                <th class="text-right" style="width:25%;">Amount</th>
            </tr>

            <?php foreach($bank_adjustments as $row): ?>
                <?php if((float)$row->bank_amount != 0): ?>
                <tr>
                    <td colspan="2"><?php echo htmlspecialchars($row->bank_narration); ?></td>
                    <td><?php echo $row->dr_account; ?></td>
                    <td><?php echo $row->cr_account; ?></td>
                    <td class="text-right">
                        <?php echo number_format($row->bank_amount, 2); ?>
                    </td>
                </tr>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if($summary->total_adjustments != 0): ?>
            <tr>
                <th colspan="3" class="text-right">Total Adjustments</th>
                <th class="text-right">
                    <?php echo number_format($summary->total_adjustments, 2); ?>
                </th>
            </tr>
            <?php endif; ?>
            <?php endif; ?>

            <!-- ============================================================
                 SECTION 5: RECONCILIATION SUMMARY
            ============================================================= -->
            <tr><th colspan="6">&nbsp;</th></tr>
            <tr><th colspan="6">Reconciliation Summary</th></tr>

            <tr>
                <td colspan="3">Bank Statement Balance</td>
                <td colspan="3" class="text-right">
                    <?php echo number_format($summary->statement_balance, 2); ?>
                </td>
            </tr>

            <?php if($summary->unreconciled_dr != 0): ?>
            <tr>
                <td colspan="3" style="padding-left:20px;">Add: Outstanding Deposits</td>
                <td colspan="3" class="text-right">
                    <?php echo number_format($summary->unreconciled_dr, 2); ?>
                </td>
            </tr>
            <?php endif; ?>

            <?php if($summary->unreconciled_cr != 0): ?>
            <tr>
                <td colspan="3" style="padding-left:20px;">Less: Outstanding Withdrawals</td>
                <td colspan="3" class="text-right">
                    (<?php echo number_format($summary->unreconciled_cr, 2); ?>)
                </td>
            </tr>
            <?php endif; ?>

            <?php if($summary->total_adjustments != 0): ?>
            <tr>
                <td colspan="3" style="padding-left:20px;">Add/Less: Adjustments</td>
                <td colspan="3" class="text-right">
                    <?php
                    $adj = $summary->total_adjustments;
                    echo ($adj < 0 ? '(' : '');
                    echo number_format(abs($adj), 2);
                    echo ($adj < 0 ? ')' : '');
                    ?>
                </td>
            </tr>
            <?php endif; ?>

            <tr>
                <th colspan="3">Adjusted Bank Balance</th>
                <th colspan="3" class="text-right">
                    <?php echo number_format($summary->adjusted_bank_balance, 2); ?>
                </th>
            </tr>

            <tr>
                <th colspan="3">Book Balance</th>
                <th colspan="3" class="text-right">
                    <?php echo number_format($summary->book_balance, 2); ?>
                </th>
            </tr>

            <?php if($summary->difference != 0): ?>
            <tr class="table-danger">
                <td colspan="3">Difference</td>
                <td colspan="3" class="text-right">
                    <?php echo number_format($summary->difference, 2); ?>
                </td>
            </tr>
            <?php endif; ?>

            <tr>
                <td colspan="3"><strong>Reconciliation Status</strong></td>
                <td colspan="3" class="text-right">
                    <strong>
                        <?php echo $summary->is_reconciled ? 'Reconciled' : 'Not Reconciled'; ?>
                    </strong>
                </td>
            </tr>

        </tbody>
    </table>
    <?php else: ?>

    <table class="table table-bordered table-sm small" id="tablereport">
        <tbody>
            <tr>
                <td class="text-center">
                    No Bank Reconciliation Found For Selected Period
                </td>
            </tr>
        </tbody>
    </table>

    <?php endif; ?>

</div>
<input type="hidden" id="periodtitle" value="<?php echo $rpt_from.' / '.$rpt_to; ?>">
<input type="hidden" id="reporttitle" value="Bank Reconciliation Statement">
<input type="hidden" id="filetitle" value="bank_reconciliation_">
<input type="hidden" id="reporttype" value="12">

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