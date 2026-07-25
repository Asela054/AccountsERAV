<div class="col-12 text-right">
    <button type="button" id="btnpdfconvert"
            class="btn btn-danger btn-sm px-4 mb-3">
        <i class="fas fa-file-pdf mr-2"></i>PDF
    </button>
</div>

<div class="col-12">

    <h6 class="title-style font-weight-bold mt-2 small"><span><?php echo $_SESSION['company']; ?> Bank Reconciliation Statement <?php echo $report_duration; ?></span></h6>

    <?php if(!empty($statement)): ?>

    <!-- Bank Account Info -->
    <table class="table table-bordered table-sm small" id="tablereport">
        <tbody>
            <tr>
                <th colspan="2">Bank Account</th>
                <td colspan="3"><?php echo $statement->accountno.' - '.$statement->accountname; ?></td>
            </tr>
            <tr>
                <th colspan="2">Reconciliation Date</th>
                <td colspan="3"><?php echo $statement->bank_rec_date; ?></td>
            </tr>
            <tr>
                <th colspan="2">Batch No</th>
                <td colspan="3"><?php echo $statement->acc_rec_batchno; ?></td>
            </tr>
            <tr>
                <th colspan="2">Approved</th>
                <td colspan="3"><?php echo $statement->rec_approved ? 'Yes' : 'No'; ?></td>
            </tr>
            <!-- Bank Statement Summary -->
            <tr><th colspan="5">Bank Statement</th></tr>

            <?php if($summary->statement_open_bal != 0): ?>
            <tr>
                <td colspan="3">Statement Opening Balance</td>
                <td colspan="2" class="text-right">
                    <?php echo number_format($summary->statement_open_bal, 2); ?>
                </td>
            </tr>
            <?php endif; ?>

            <?php if($summary->statement_tot_dr != 0): ?>
            <tr>
                <td colspan="3" style="padding-left:20px;">Add: Total Deposits (DR)</td>
                <td colspan="2" class="text-right">
                    <?php echo number_format($summary->statement_tot_dr, 2); ?>
                </td>
            </tr>
            <?php endif; ?>

            <?php if($summary->statement_tot_cr != 0): ?>
            <tr>
                <td colspan="3" style="padding-left:20px;">Less: Total Withdrawals (CR)</td>
                <td colspan="2" class="text-right">
                    (<?php echo number_format($summary->statement_tot_cr, 2); ?>)
                </td>
            </tr>
            <?php endif; ?>

            <tr>
                <th colspan="3">Statement Closing Balance</th>
                <th colspan="2" class="text-right">
                    <?php echo number_format($summary->statement_balance, 2); ?>
                </th>
            </tr>
            
            <?php if(!empty($reconciled_items)): ?>
            <!-- Reconciled Transactions -->
            <tr><th colspan="5">&nbsp;</th></tr>

            <tr><th colspan="5">Reconciled Transactions (<?php echo $summary->reconciled_count; ?>)</th></tr>
            <tr>
                <th style="width:15%;">Date</th>
                <th style="width:35%;">Narration</th>
                <th style="width:15%;">Origin</th>
                <th class="text-right" style="width:15%;">Debit</th>
                <th class="text-right" style="width:15%;">Credit</th>
            </tr>

            <?php foreach($reconciled_items as $row): ?>
                <?php if((float)$row->accamount != 0): ?>
                <tr>
                    <td><?php echo $row->tradate; ?></td>
                    <td><?php echo htmlspecialchars($row->narration); ?></td>
                    <td><?php echo $row->rec_info_origin_name; ?></td>
                    <td class="text-right">
                        <?php echo $row->crdr == 'D'
                            ? number_format($row->accamount, 2)
                            : ''; ?>
                    </td>
                    <td class="text-right">
                        <?php echo $row->crdr == 'C'
                            ? number_format($row->accamount, 2)
                            : ''; ?>
                    </td>
                </tr>
                <?php endif; ?>
            <?php endforeach; ?>

            <tr>
                <th colspan="3" class="text-right">Total Reconciled</th>
                <th class="text-right">
                    <?php echo $summary->reconciled_dr != 0
                        ? number_format($summary->reconciled_dr, 2)
                        : ''; ?>
                </th>
                <th class="text-right">
                    <?php echo $summary->reconciled_cr != 0
                        ? number_format($summary->reconciled_cr, 2)
                        : ''; ?>
                </th>
            </tr>
            <?php endif; ?>

            <!-- Unreconciled / Outstanding Transactions -->
            <?php if(!empty($unreconciled_items)): ?>
            
            <tr><th colspan="4">Outstanding Transactions (<?php echo $summary->unreconciled_count; ?>)</th></tr>
            <tr>
                <th style="width:15%;">Date</th>
                <th colspan="2" style="width:45%;">Narration</th>
                <th class="text-right" style="width:20%;">Debit</th>
                <th class="text-right" style="width:20%;">Credit</th>
            </tr>
            <?php foreach($unreconciled_items as $row): ?>
                <?php if((float)$row->accamount != 0): ?>
                <tr>
                    <td><?php echo $row->tradate; ?></td>
                    <td colspan="2"><?php echo htmlspecialchars($row->narration); ?></td>
                    <td class="text-right">
                        <?php echo $row->crdr == 'D'
                            ? number_format($row->accamount, 2)
                            : ''; ?>
                    </td>
                    <td class="text-right">
                        <?php echo $row->crdr == 'C'
                            ? number_format($row->accamount, 2)
                            : ''; ?>
                    </td>
                </tr>
                <?php endif; ?>
            <?php endforeach; ?>

            <tr>
                <th colspan="3" class="text-right">Total Outstanding</th>
                <th class="text-right">
                    <?php echo $summary->unreconciled_dr != 0
                        ? number_format($summary->unreconciled_dr, 2)
                        : ''; ?>
                </th>
                <th class="text-right">
                    <?php echo $summary->unreconciled_cr != 0
                        ? number_format($summary->unreconciled_cr, 2)
                        : ''; ?>
                </th>
            </tr>
            <?php endif; ?>

            <!-- Bank Adjustments -->
            <?php if(!empty($bank_adjustments)): ?>
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
                
            <!-- Reconciliation Summary -->
            <tr><th colspan="5">&nbsp;</th></tr>
            <tr><th colspan="5">Reconciliation Summary</th></tr>

            <tr>
                <td colspan="3">Bank Statement Balance</td>
                <td colspan="2" class="text-right">
                    <?php echo number_format($summary->statement_balance, 2); ?>
                </td>
            </tr>

            <?php if($summary->unreconciled_dr != 0): ?>
            <tr>
                <td colspan="3" style="padding-left:20px;">
                    Add: Outstanding Deposits
                </td>
                <td colspan="2" class="text-right">
                    <?php echo number_format($summary->unreconciled_dr, 2); ?>
                </td>
            </tr>
            <?php endif; ?>

            <?php if($summary->unreconciled_cr != 0): ?>
            <tr>
                <td colspan="3" colspan="3" style="padding-left:20px;">
                    Less: Outstanding Withdrawals
                </td>
                <td colspan="2" class="text-right">
                    (<?php echo number_format($summary->unreconciled_cr, 2); ?>)
                </td>
            </tr>

            <?php if($summary->total_adjustments != 0): ?>
            <tr>
                <td colspan="3" style="padding-left:20px;">
                    Add/Less: Adjustments
                </td>
                <td colspan="2" class="text-right">
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
                <th colspan="2" class="text-right">
                    
                        <?php echo number_format($summary->adjusted_bank_balance, 2); ?>
                    
                </th>
            </tr>

            <tr>
                <th colspan="3">Book Balance</th>
                <th colspan="2" class="text-right">
                    
                        <?php echo number_format($summary->book_balance, 2); ?>
                    
                </th>
            </tr>

            <?php if($summary->difference != 0): ?>
            <tr>
                <td colspan="3">Difference</td>
                <td colspan="2" class="text-right">
                    <?php echo number_format($summary->difference, 2); ?>
                </td>
            </tr>
            <?php endif; ?>

            <tr>
                <td colspan="3">Reconciliation Status</td>
                <td colspan="2"class="text-right">
                    <?php echo $summary->is_reconciled
                        ? 'Reconciled'
                        : 'Not Reconciled'; ?>
                </td>
            </tr>
            <?php endif; ?>
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