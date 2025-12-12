<?php
class Creditorreportinfo extends CI_Model{
    public function CreditorStatementReport(){
        $fromdate = $this->input->post('fromdate');
        $todate = $this->input->post('todate');
        $creditor = $this->input->post('supplier');
        $companyID = $_SESSION['companyid'];
        $branchID = $_SESSION['branchid'];

        $html = '';

        // If no creditor selected, show summary for all creditors
        if(empty($creditor)) {
            // Get all creditors with balances
            $sqlCreditors = "
                SELECT 
                    s.idtbl_supplier,
                    s.suppliername,
                    s.bus_reg_no,
                    (SELECT COALESCE(SUM(amount), 0) 
                    FROM tbl_expence_info e 
                    WHERE e.tbl_supplier_idtbl_supplier = s.idtbl_supplier 
                    AND e.status = 1 
                    AND e.grndate BETWEEN ? AND ?
                    AND e.tbl_company_idtbl_company = ? 
                    AND e.tbl_company_branch_idtbl_company_branch = ?) as total_expenses,
                    
                    (SELECT COALESCE(SUM(api.amount), 0) 
                    FROM tbl_account_paysettle_info api 
                    JOIN tbl_account_paysettle ap ON ap.idtbl_account_paysettle = api.tbl_account_paysettle_idtbl_account_paysettle
                    WHERE ap.supplier = s.idtbl_supplier 
                    AND ap.status = 1 
                    AND ap.date BETWEEN ? AND ?
                    AND ap.tbl_company_idtbl_company = ? 
                    AND ap.tbl_company_branch_idtbl_company_branch = ?) as total_payments
                FROM tbl_supplier s
                WHERE s.status = 1
                AND EXISTS (
                    SELECT 1 FROM tbl_expence_info e 
                    WHERE e.tbl_supplier_idtbl_supplier = s.idtbl_supplier 
                    AND e.status = 1 
                    AND e.grndate BETWEEN ? AND ?
                    AND e.tbl_company_idtbl_company = ? 
                    AND e.tbl_company_branch_idtbl_company_branch = ?
                )
                ORDER BY s.suppliername ASC
            ";

            $creditors = $this->db->query($sqlCreditors, [
                $fromdate, $todate, $companyID, $branchID,
                $fromdate, $todate, $companyID, $branchID,
                $fromdate, $todate, $companyID, $branchID
            ])->result();

            $html .= '<div class="table-responsive">';
            // Start creditor section            
            $html .= '<table class="table table-bordered table-striped table-sm small" id="creditorStatementsTable">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th>#</th>';
            $html .= '<th>Date</th>';
            $html .= '<th>Document No</th>';
            $html .= '<th>Description</th>';
            $html .= '<th>Type</th>';
            $html .= '<th class="text-right">Credit</th>';
            $html .= '<th class="text-right">Debit</th>';
            $html .= '<th class="text-right">Balance</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            
            $grandTotalExpenses = 0;
            $grandTotalPayments = 0;
            $grandTotalBalance = 0;

            foreach($creditors as $sup) {
                // Get Opening Balance for this creditor
                $sqlOpenBalance = "SELECT ((SELECT COALESCE(SUM(`amount`), 0) FROM `tbl_expence_info` WHERE `status`=? AND `grndate`<? AND `tbl_supplier_idtbl_supplier`=? AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=?)-(SELECT COALESCE(SUM(`totalpayment`), 0) FROM `tbl_account_paysettle` WHERE `status`=? AND `date`<? AND `supplier`=? AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=?)) AS `openbalance`";
                
                $params = [1, $fromdate, $sup->idtbl_supplier, $companyID, $branchID, 1, $fromdate, $sup->idtbl_supplier, $companyID, $branchID];
                $respondopenbalance = $this->db->query($sqlOpenBalance, $params);
                $openingBalance = $respondopenbalance->row()->openbalance;

                // Get Transactions for this creditor
                $sql = "SELECT * FROM (SELECT `tbl_account_paysettle`.`date` AS `repaydate`, `tbl_account_paysettle_info`.`invoiceno` AS `regrnno`, '' AS `expcode`, `tbl_account_paysettle_info`.`amount`, `tbl_account_paysettle_info`.`narration`, 'D' AS `tratype`, `tbl_cheque_issue`.`chedate`, `tbl_cheque_issue`.`chequeno` FROM `tbl_account_paysettle_info` LEFT JOIN `tbl_account_paysettle` ON `tbl_account_paysettle`.`idtbl_account_paysettle`=`tbl_account_paysettle_info`.`tbl_account_paysettle_idtbl_account_paysettle` LEFT JOIN `tbl_account_paysettle_has_tbl_cheque_issue` ON `tbl_account_paysettle_has_tbl_cheque_issue`.`tbl_account_paysettle_idtbl_account_paysettle`=`tbl_account_paysettle`.`idtbl_account_paysettle` LEFT JOIN `tbl_cheque_issue` ON `tbl_cheque_issue`.`idtbl_cheque_issue`=`tbl_account_paysettle_has_tbl_cheque_issue`.`tbl_cheque_issue_idtbl_cheque_issue` WHERE `tbl_account_paysettle_info`.`status`=? AND `tbl_account_paysettle`.`date` BETWEEN ? AND ? AND `tbl_account_paysettle`.`status`=? AND `tbl_account_paysettle`.`supplier`=? AND `tbl_account_paysettle`.`tbl_company_idtbl_company`=? AND `tbl_account_paysettle`.`tbl_company_branch_idtbl_company_branch`=? UNION ALL SELECT `grndate` AS `repaydate`, `grnno` AS `regrnno`, `expcode`, `amount`, '' AS `narration`, 'C' AS `tratype`, '' AS `chedate`, '' AS `chequeno` FROM `tbl_expence_info` WHERE `tbl_supplier_idtbl_supplier`=? AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=? AND `grndate` BETWEEN ? AND ?) AS `u` ORDER BY `u`.`repaydate` ASC";
                
                $transactions = $this->db->query($sql, [1, $fromdate, $todate, 1, $sup->idtbl_supplier, $companyID, $branchID, $sup->idtbl_supplier, $companyID, $branchID, $fromdate, $todate])->result();

                

                $runningBalance = $openingBalance;
                $counter = 1;
                $creditorTotalCredit = 0;
                $creditorTotalDebit = 0;
                
                $html .= '<tr><th colspan="8" class="text-left">' . $sup->suppliername . ' (' . $sup->bus_reg_no . ')</th></tr>';
                // Opening Balance Row
                $html .= '<tr>';
                $html .= '<th>&nbsp;</th>';
                $html .= '<th>' . date('d/m/Y', strtotime($fromdate)) . '</th>';
                $html .= '<th>-</th>';
                $html .= '<th>Opening Balance</th>';
                $html .= '<th>Balance B/F</th>';
                $html .= '<th class="text-right">-</th>';
                $html .= '<th class="text-right">-</th>';
                $html .= '<th class="text-right">' . number_format($runningBalance, 2) . '</th>';
                $html .= '</tr>';

                foreach($transactions as $transaction) {
                    if($transaction->tratype == 'C') {
                        // Expense/Credit (GRN)
                        $runningBalance += $transaction->amount;
                        $creditorTotalCredit += $transaction->amount;
                        $creditAmount = number_format($transaction->amount, 2);
                        $debitAmount = '-';
                        $typeBadge = 'GRN';
                    } else {
                        // Payment/Debit
                        $runningBalance -= $transaction->amount;
                        $creditorTotalDebit += $transaction->amount;
                        $creditAmount = '-';
                        $debitAmount = number_format($transaction->amount, 2);
                        $typeBadge = 'Payment';
                    }

                    $html .= '<tr>';
                    $html .= '<td>' . $counter . '</td>';
                    $html .= '<td>' . date('d/m/Y', strtotime($transaction->repaydate)) . '</td>';
                    $html .= '<td>' . $transaction->regrnno . '</td>';
                    $html .= '<td>' . $transaction->narration . '</td>';
                    $html .= '<td>' . $typeBadge . '</td>';
                    $html .= '<td class="text-right">' . $creditAmount . '</td>';
                    $html .= '<td class="text-right">' . $debitAmount . '</td>';
                    $html .= '<td class="text-right">' . number_format($runningBalance, 2) . '</td>';
                    $html .= '</tr>';

                    $counter++;
                }

                // Creditor Closing Balance Row
                $html .= '<tr>';
                $html .= '<th colspan="5" class="text-right">' . $sup->suppliername . ' Closing Balance</th>';
                $html .= '<th class="text-right">' . number_format($creditorTotalCredit, 2) . '</th>';
                $html .= '<th class="text-right">' . number_format($creditorTotalDebit, 2) . '</th>';
                $html .= '<th class="text-right">' . number_format($runningBalance, 2) . '</th>';
                $html .= '</tr>';

                // Add to grand totals
                $grandTotalExpenses += $creditorTotalCredit;
                $grandTotalPayments += $creditorTotalDebit;
                $grandTotalBalance += $runningBalance;
            }
            $html .= '<tr><td colspan="8">&nbsp;</td></tr>';
            $html .= '<tr>';
            $html .= '<th colspan="5" class="text-right">GRAND TOTALS - ALL CREDITORS</th>';
            $html .= '<th class="text-right">Total Credit</th>';
            $html .= '<th class="text-right">Total Debit</th>';
            $html .= '<th class="text-right">Closing Balance</th>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<th colspan="5" class="text-right">Totals</th>';
            $html .= '<th class="text-right">' . number_format($grandTotalExpenses, 2) . '</th>';
            $html .= '<th class="text-right">' . number_format($grandTotalPayments, 2) . '</th>';
            $html .= '<th class="text-right">' . number_format($grandTotalBalance, 2) . '</th>';
            $html .= '</tr>';
            $html .= '</tbody>';
            $html .= '</table>';

            $html .= '</div>'; // Close table-responsive

        } else {
            // Specific creditor selected - show detailed statement
            // Get Opening Balance
            $sqlOpenBalance = "SELECT ((SELECT COALESCE(SUM(`amount`), 0) FROM `tbl_expence_info` WHERE `status`=? AND `grndate`<? AND `tbl_supplier_idtbl_supplier`=? AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=?)-(SELECT COALESCE(SUM(`totalpayment`), 0) FROM `tbl_account_paysettle` WHERE `status`=? AND `date`<? AND `supplier`=? AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=?)) AS `openbalance`";
            
            $params = [1, $fromdate, $creditor, $companyID, $branchID, 1, $fromdate, $creditor, $companyID, $branchID];
            $respondopenbalance = $this->db->query($sqlOpenBalance, $params);
            $openingBalance = $respondopenbalance->row()->openbalance;

            // Get Transactions
            $sql = "SELECT * FROM (SELECT `tbl_account_paysettle`.`date` AS `repaydate`, `tbl_account_paysettle_info`.`invoiceno` AS `regrnno`, '' AS `expcode`, `tbl_account_paysettle_info`.`amount`, `tbl_account_paysettle_info`.`narration`, 'D' AS `tratype`, `tbl_cheque_issue`.`chedate`, `tbl_cheque_issue`.`chequeno` FROM `tbl_account_paysettle_info` LEFT JOIN `tbl_account_paysettle` ON `tbl_account_paysettle`.`idtbl_account_paysettle`=`tbl_account_paysettle_info`.`tbl_account_paysettle_idtbl_account_paysettle` LEFT JOIN `tbl_account_paysettle_has_tbl_cheque_issue` ON `tbl_account_paysettle_has_tbl_cheque_issue`.`tbl_account_paysettle_idtbl_account_paysettle`=`tbl_account_paysettle`.`idtbl_account_paysettle` LEFT JOIN `tbl_cheque_issue` ON `tbl_cheque_issue`.`idtbl_cheque_issue`=`tbl_account_paysettle_has_tbl_cheque_issue`.`tbl_cheque_issue_idtbl_cheque_issue` WHERE `tbl_account_paysettle_info`.`status`=? AND `tbl_account_paysettle`.`date` BETWEEN ? AND ? AND `tbl_account_paysettle`.`status`=? AND `tbl_account_paysettle`.`supplier`=? AND `tbl_account_paysettle`.`tbl_company_idtbl_company`=? AND `tbl_account_paysettle`.`tbl_company_branch_idtbl_company_branch`=? UNION ALL SELECT `grndate` AS `repaydate`, `grnno` AS `regrnno`, `expcode`, `amount`, '' AS `narration`, 'C' AS `tratype`, '' AS `chedate`, '' AS `chequeno` FROM `tbl_expence_info` WHERE `tbl_supplier_idtbl_supplier`=? AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=? AND `grndate` BETWEEN ? AND ?) AS `u` ORDER BY `u`.`repaydate` ASC";
            
            $respond = $this->db->query($sql, array(1, $fromdate, $todate, 1, $creditor, $companyID, $branchID, $creditor, $companyID, $branchID, $fromdate, $todate));
            $transactions = $respond->result();

            // Get Creditor Info
            $creditorInfo = [];
            if(!empty($creditor)) {
                $supQuery = $this->db->query("SELECT * FROM tbl_supplier WHERE idtbl_supplier = ?", [$creditor]);
                $creditorInfo = $supQuery->row();
            }

            // Transaction Table
            $html .= '<div class="table-responsive">';
            $html .= '<table class="table table-bordered table-striped table-sm small" id="creditorStatementsTable">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th>#</th>';
            $html .= '<th>Date</th>';
            $html .= '<th>Document No</th>';
            $html .= '<th>Description</th>';
            $html .= '<th>Type</th>';
            $html .= '<th class="text-right">Credit</th>';
            $html .= '<th class="text-right">Debit</th>';
            $html .= '<th class="text-right">Balance</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';

            $runningBalance = $openingBalance;
            $counter = 1;
            $totalCredit = 0;
            $totalDebit = 0;

            // Opening Balance Row
            $html .= '<tr>';
            $html .= '<th>&nbsp;</th>';
            $html .= '<th>' . date('d/m/Y', strtotime($fromdate)) . '</th>';
            $html .= '<th>-</th>';
            $html .= '<th>Opening Balance</th>';
            $html .= '<th>Balance B/F</th>';
            $html .= '<th class="text-right">-</th>';
            $html .= '<th class="text-right">-</th>';
            $html .= '<th class="text-right">' . number_format($runningBalance, 2) . '</th>';
            $html .= '</tr>';

            foreach($transactions as $transaction) {
                if($transaction->tratype == 'C') {
                    // Expense/Credit (GRN)
                    $runningBalance += $transaction->amount;
                    $totalCredit += $transaction->amount;
                    $creditAmount = number_format($transaction->amount, 2);
                    $debitAmount = '-';
                    $typeBadge = 'GRN';
                } else {
                    // Payment/Debit
                    $runningBalance -= $transaction->amount;
                    $totalDebit += $transaction->amount;
                    $creditAmount = '-';
                    $debitAmount = number_format($transaction->amount, 2);
                    $typeBadge = 'Payment';
                }

                $html .= '<tr>';
                $html .= '<td>' . $counter . '</td>';
                $html .= '<td>' . date('d/m/Y', strtotime($transaction->repaydate)) . '</td>';
                $html .= '<td>' . $transaction->regrnno . '</td>';
                $html .= '<td>' . $transaction->narration . '</td>';
                $html .= '<td>' . $typeBadge . '</td>';
                $html .= '<td class="text-right">' . $creditAmount . '</td>';
                $html .= '<td class="text-right">' . $debitAmount . '</td>';
                $html .= '<td class="text-right">' . number_format($runningBalance, 2) . '</td>';
                $html .= '</tr>';

                $counter++;
            }

            // Closing Balance Row
            $html .= '<tr>';
            $html .= '<th colspan="5" class="text-right">Closing Balance</th>';
            $html .= '<th class="text-right">' . number_format($totalCredit, 2) . '</th>';
            $html .= '<th class="text-right">' . number_format($totalDebit, 2) . '</th>';
            $html .= '<th class="text-right">' . number_format($runningBalance, 2) . '</th>';
            $html .= '</tr>';

            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '</div>';
        }

        echo $html;
    }
    // public function CreditorAgeAnalysisReport(){
    //     $asofdate = $this->input->post('asofdate') ? $this->input->post('asofdate') : date('Y-m-d');
    //     $creditor = $this->input->post('supplier');
    //     $companyID = $_SESSION['companyid'];
    //     $branchID = $_SESSION['branchid'];

    //     $html = '';

    //     // If no creditor selected, show all creditors in a single table
    //     if(empty($creditor)) {
    //         // Get Age Analysis Data for all creditors
    //         $sql = "
    //             SELECT 
    //                 s.idtbl_supplier,
    //                 s.suppliername,
    //                 s.bus_reg_no,
    //                 SUM(CASE WHEN DATEDIFF(?, e.grndate) <= 30 AND e.status = 1 THEN e.amount ELSE 0 END) AS current_30,
    //                 SUM(CASE WHEN DATEDIFF(?, e.grndate) BETWEEN 31 AND 60 AND e.status = 1 THEN e.amount ELSE 0 END) AS days_31_60,
    //                 SUM(CASE WHEN DATEDIFF(?, e.grndate) BETWEEN 61 AND 90 AND e.status = 1 THEN e.amount ELSE 0 END) AS days_61_90,
    //                 SUM(CASE WHEN DATEDIFF(?, e.grndate) BETWEEN 91 AND 120 AND e.status = 1 THEN e.amount ELSE 0 END) AS days_91_120,
    //                 SUM(CASE WHEN DATEDIFF(?, e.grndate) > 120 AND e.status = 1 THEN e.amount ELSE 0 END) AS over_120,
    //                 SUM(CASE WHEN e.status = 1 THEN e.amount ELSE 0 END) AS total_expenses,
    //                 SUM(CASE WHEN ap.status = 1 THEN ap.totalpayment ELSE 0 END) AS total_payments,
    //                 (SUM(CASE WHEN e.status = 1 THEN e.amount ELSE 0 END) - 
    //                 SUM(CASE WHEN ap.status = 1 THEN ap.totalpayment ELSE 0 END)) AS net_balance
    //             FROM tbl_supplier s
    //             LEFT JOIN tbl_expence_info e ON s.idtbl_supplier = e.tbl_supplier_idtbl_supplier 
    //                 AND e.tbl_company_idtbl_company = ? 
    //                 AND e.tbl_company_branch_idtbl_company_branch = ?
    //                 AND e.grndate <= ?
    //                 AND e.status = 1
    //             LEFT JOIN tbl_account_paysettle ap ON s.idtbl_supplier = ap.supplier 
    //                 AND ap.tbl_company_idtbl_company = ? 
    //                 AND ap.tbl_company_branch_idtbl_company_branch = ?
    //                 AND ap.date <= ?
    //                 AND ap.status = 1
    //             WHERE s.status = 1
    //             GROUP BY s.idtbl_supplier
    //             HAVING net_balance > 0
    //             ORDER BY s.suppliername ASC
    //         ";

    //         $creditors = $this->db->query($sql, [
    //             $asofdate, $asofdate, $asofdate, $asofdate, $asofdate,
    //             $companyID, $branchID, $asofdate,
    //             $companyID, $branchID, $asofdate
    //         ])->result();

    //         // Calculate totals
    //         $totalCurrent = 0;
    //         $total31_60 = 0;
    //         $total61_90 = 0;
    //         $total91_120 = 0;
    //         $totalOver120 = 0;
    //         $grandTotal = 0;

    //         foreach($creditors as $cred) {
    //             $totalCurrent += $cred->current_30;
    //             $total31_60 += $cred->days_31_60;
    //             $total61_90 += $cred->days_61_90;
    //             $total91_120 += $cred->days_91_120;
    //             $totalOver120 += $cred->over_120;
    //             $grandTotal += $cred->net_balance;
    //         }

    //         // Main Table
    //         $html .= '<div class="table-responsive">';
    //         $html .= '<table class="table table-bordered table-striped table-sm small" id="creditorAgeAnalysisTable">';
    //         $html .= '<thead>';
    //         $html .= '<tr>';
    //         $html .= '<th>#</th>';
    //         $html .= '<th>Supplier Code</th>';
    //         $html .= '<th>Supplier Name</th>';
    //         $html .= '<th class="text-right">Current (0-30 Days)</th>';
    //         $html .= '<th class="text-right">31-60 Days</th>';
    //         $html .= '<th class="text-right">61-90 Days</th>';
    //         $html .= '<th class="text-right">91-120 Days</th>';
    //         $html .= '<th class="text-right">Over 120 Days</th>';
    //         $html .= '<th class="text-right">Total Outstanding</th>';
    //         $html .= '</tr>';
    //         $html .= '</thead>';
    //         $html .= '<tbody>';

    //         $counter = 1;
    //         foreach($creditors as $cred) {
    //             $totalRow = $cred->current_30 + $cred->days_31_60 + $cred->days_61_90 + 
    //                     $cred->days_91_120 + $cred->over_120;
                
    //             $html .= '<tr>';
    //             $html .= '<td>' . $counter . '</td>';
    //             $html .= '<td>' . $cred->bus_reg_no . '</td>';
    //             $html .= '<td>' . $cred->suppliername . '</td>';
    //             $html .= '<td class="text-right">' . number_format($cred->current_30, 2) . '</td>';
    //             $html .= '<td class="text-right">' . number_format($cred->days_31_60, 2) . '</td>';
    //             $html .= '<td class="text-right">' . number_format($cred->days_61_90, 2) . '</td>';
    //             $html .= '<td class="text-right">' . number_format($cred->days_91_120, 2) . '</td>';
    //             $html .= '<td class="text-right">' . number_format($cred->over_120, 2) . '</td>';
    //             $html .= '<th class="text-right">' . number_format($totalRow, 2) . '</th>';
    //             $html .= '</tr>';

    //             $counter++;
    //         }

    //         // Totals Row
    //         $html .= '<tr>';
    //         $html .= '<th colspan="3" class="text-right">GRAND TOTALS:</th>';
    //         $html .= '<th class="text-right">' . number_format($totalCurrent, 2) . '</th>';
    //         $html .= '<th class="text-right">' . number_format($total31_60, 2) . '</th>';
    //         $html .= '<th class="text-right">' . number_format($total61_90, 2) . '</th>';
    //         $html .= '<th class="text-right">' . number_format($total91_120, 2) . '</th>';
    //         $html .= '<th class="text-right">' . number_format($totalOver120, 2) . '</th>';
    //         $html .= '<th class="text-right">' . number_format($grandTotal, 2) . '</th>';
    //         $html .= '</tr>';

    //         $html .= '</tbody>';
    //         $html .= '</table>';
    //         $html .= '</div>';

    //     } else {
    //         // No creditor selected - show all creditors in summary table
    //         $sql = "
    //             SELECT 
    //                 s.idtbl_supplier,
    //                 s.suppliername,
    //                 s.bus_reg_no,
    //                 SUM(CASE WHEN DATEDIFF(?, e.grndate) > 89 AND e.status = 1 THEN e.amount ELSE 0 END) AS over_90,
    //                 SUM(CASE WHEN DATEDIFF(?, e.grndate) BETWEEN 60 AND 89 AND e.status = 1 THEN e.amount ELSE 0 END) AS days_60_89,
    //                 SUM(CASE WHEN DATEDIFF(?, e.grndate) BETWEEN 30 AND 59 AND e.status = 1 THEN e.amount ELSE 0 END) AS days_30_59,
    //                 SUM(CASE WHEN DATEDIFF(?, e.grndate) < 30 AND e.status = 1 THEN e.amount ELSE 0 END) AS less_30,
    //                 SUM(CASE WHEN e.status = 1 THEN e.amount ELSE 0 END) AS total_grns,
    //                 SUM(CASE WHEN ap.status = 1 THEN ap.totalpayment ELSE 0 END) AS total_payments,
    //                 (SUM(CASE WHEN e.status = 1 THEN e.amount ELSE 0 END) - 
    //                 SUM(CASE WHEN ap.status = 1 THEN ap.totalpayment ELSE 0 END)) AS net_balance
    //             FROM tbl_supplier s
    //             LEFT JOIN tbl_expence_info e ON s.idtbl_supplier = e.tbl_supplier_idtbl_supplier 
    //                 AND e.tbl_company_idtbl_company = ? 
    //                 AND e.tbl_company_branch_idtbl_company_branch = ?
    //                 AND e.grndate <= ?
    //                 AND e.status = 1
    //             LEFT JOIN tbl_account_paysettle ap ON s.idtbl_supplier = ap.supplier 
    //                 AND ap.tbl_company_idtbl_company = ? 
    //                 AND ap.tbl_company_branch_idtbl_company_branch = ?
    //                 AND ap.date <= ?
    //                 AND ap.status = 1
    //             WHERE s.status = 1
    //             GROUP BY s.idtbl_supplier
    //             HAVING net_balance > 0
    //             ORDER BY s.suppliername ASC
    //         ";

    //         $creditors = $this->db->query($sql, [
    //             $asofdate, $asofdate, $asofdate, $asofdate,
    //             $companyID, $branchID, $asofdate,
    //             $companyID, $branchID, $asofdate
    //         ])->result();

    //         // Calculate totals
    //         $totalOver90 = 0;
    //         $total60to89 = 0;
    //         $total30to59 = 0;
    //         $totalLess30 = 0;
    //         $grandTotal = 0;

    //         foreach($creditors as $cred) {
    //             $totalOver90 += $cred->over_90;
    //             $total60to89 += $cred->days_60_89;
    //             $total30to59 += $cred->days_30_59;
    //             $totalLess30 += $cred->less_30;
    //             $grandTotal += $cred->net_balance;
    //         }
            
    //         // Main Table for all creditors
    //         $html .= '<div class="table-responsive">';
    //         $html .= '<table class="table table-bordered table-striped table-sm small">';
    //         $html .= '<thead>';
    //         $html .= '<tr>';
    //         $html .= '<th colspan="8">Group By Group: TC (TRADE CREDITORS)</th>';
    //         $html .= '<tr>';
    //         $html .= '<th>#</th>';
    //         $html .= '<th>Supplier Code</th>';
    //         $html .= '<th>Supplier Name</th>';
    //         $html .= '<th class="text-right">90 & Above</th>';
    //         $html .= '<th class="text-right">60 to 89 Days</th>';
    //         $html .= '<th class="text-right">30 to 59 Days</th>';
    //         $html .= '<th class="text-right">Less than 30 Days</th>';
    //         $html .= '<th class="text-right">Total Balance</th>';
    //         $html .= '</tr>';
    //         $html .= '</thead>';
    //         $html .= '<tbody>';

    //         $counter = 1;
    //         foreach($creditors as $cred) {
    //             $totalRow = $cred->over_90 + $cred->days_60_89 + $cred->days_30_59 + $cred->less_30;
                
    //             $html .= '<tr>';
    //             $html .= '<td>' . $counter . '</td>';
    //             $html .= '<td>' . $cred->bus_reg_no . '</td>';
    //             $html .= '<td>' . $cred->suppliername . '</td>';
    //             $html .= '<td class="text-right">' . number_format($cred->over_90, 2) . '</td>';
    //             $html .= '<td class="text-right">' . number_format($cred->days_60_89, 2) . '</td>';
    //             $html .= '<td class="text-right">' . number_format($cred->days_30_59, 2) . '</td>';
    //             $html .= '<td class="text-right">' . number_format($cred->less_30, 2) . '</td>';
    //             $html .= '<td class="text-right"><strong>' . number_format($totalRow, 2) . '</strong></td>';
    //             $html .= '</tr>';

    //             $counter++;
    //         }

    //         // Totals Row
    //         $html .= '<tr style="border-top: 2px solid #000; font-weight: bold;">';
    //         $html .= '<td colspan="3" class="text-right"><strong>GRAND TOTALS:</strong></td>';
    //         $html .= '<td class="text-right"><strong>' . number_format($totalOver90, 2) . '</strong></td>';
    //         $html .= '<td class="text-right"><strong>' . number_format($total60to89, 2) . '</strong></td>';
    //         $html .= '<td class="text-right"><strong>' . number_format($total30to59, 2) . '</strong></td>';
    //         $html .= '<td class="text-right"><strong>' . number_format($totalLess30, 2) . '</strong></td>';
    //         $html .= '<td class="text-right"><strong>' . number_format($grandTotal, 2) . '</strong></td>';
    //         $html .= '</tr>';

    //         $html .= '</tbody>';
    //         $html .= '</table>';
    //         $html .= '</div>';
            
    //         $html .= '</div>';
    //     }

    //     echo $html;
    // }
    public function CreditorAgeAnalysisReport(){
        $asofdate = $this->input->post('asofdate') ? $this->input->post('asofdate') : date('Y-m-d');
        $creditor = $this->input->post('supplier');
        $companyID = $_SESSION['companyid'];
        $branchID = $_SESSION['branchid'];

        $html = '';

        // Get company name from session or database
        $companyName = isset($_SESSION['companyname']) ? $_SESSION['companyname'] : 'Company';
        
        // Get Creditor Info if selected
        $creditorInfo = null;
        if(!empty($creditor)) {
            $supQuery = $this->db->query("SELECT * FROM tbl_supplier WHERE idtbl_supplier = ?", [$creditor]);
            $creditorInfo = $supQuery->row();
        }

        if(!empty($creditor) && !empty($creditorInfo)) {
            // Specific creditor selected - show PDF-style layout
            
            // Main Table Header
            $html .= '<table class="table table-striped table-bordered table-sm small" id="creditorStatementsTable">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th>Date</th>';
            $html .= '<th>Reference</th>';
            $html .= '<th class="text-right">90 & Above</th>';
            $html .= '<th class="text-right">60 to 89 Days</th>';
            $html .= '<th class="text-right">30 to 59 Days</th>';
            $html .= '<th class="text-right">Less than 30 Days</th>';
            $html .= '<th class="text-right">Balance</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            
            // Get all GRNs for this creditor with aging calculation
            $sqlGRNs = "
                SELECT 
                    e.grndate,
                    e.grnno,
                    e.amount,
                    DATEDIFF(?, e.grndate) as days_aged,
                    CASE 
                        WHEN DATEDIFF(?, e.grndate) > 89 THEN '90 & Above'
                        WHEN DATEDIFF(?, e.grndate) BETWEEN 60 AND 89 THEN '60 to 89 Days'
                        WHEN DATEDIFF(?, e.grndate) BETWEEN 30 AND 59 THEN '30 to 59 Days'
                        ELSE 'Less than 30 Days'
                    END as age_category
                FROM tbl_expence_info e
                WHERE e.tbl_supplier_idtbl_supplier = ?
                AND e.status = 1
                AND e.grndate <= ?
                AND e.tbl_company_idtbl_company = ?
                AND e.tbl_company_branch_idtbl_company_branch = ?
                ORDER BY e.grndate ASC
            ";

            $grns = $this->db->query($sqlGRNs, [
                $asofdate, $asofdate, $asofdate, $asofdate,
                $creditor, $asofdate, $companyID, $branchID
            ])->result();

            // Get all payments for this creditor
            $sqlPayments = "
                SELECT 
                    SUM(api.amount) as total_payments
                FROM tbl_account_paysettle ap
                JOIN tbl_account_paysettle_info api ON ap.idtbl_account_paysettle = api.tbl_account_paysettle_idtbl_account_paysettle
                WHERE ap.supplier = ?
                AND ap.status = 1
                AND ap.date <= ?
                AND ap.tbl_company_idtbl_company = ?
                AND ap.tbl_company_branch_idtbl_company_branch = ?
            ";

            $paymentsResult = $this->db->query($sqlPayments, [
                $creditor, $asofdate, $companyID, $branchID
            ])->row();
            $totalPayments = $paymentsResult ? $paymentsResult->total_payments : 0;

            $html .= '<tbody>';
            $html .= '<tr><th colspan="7">Group By Group: TC (TRADE CREDITORS)</th></tr>';
            $html .= '<tr><th colspan="7">' . $creditorInfo->bus_reg_no . ' (' . $creditorInfo->suppliername . ')</th></tr>';
            
            // Initialize totals
            $total90Above = 0;
            $total60to89 = 0;
            $total30to59 = 0;
            $totalLess30 = 0;
            $runningBalance = 0;
            $totalGRNs = 0;
            
            // Add GRN rows
            foreach($grns as $grn) {
                $totalGRNs += $grn->amount;
                $runningBalance += $grn->amount;
                
                $amount90Above = '';
                $amount60to89 = '';
                $amount30to59 = '';
                $amountLess30 = '';
                
                // Place amount in correct aging column
                switch($grn->age_category) {
                    case '90 & Above':
                        $amount90Above = number_format($grn->amount, 2);
                        $total90Above += $grn->amount;
                        break;
                    case '60 to 89 Days':
                        $amount60to89 = number_format($grn->amount, 2);
                        $total60to89 += $grn->amount;
                        break;
                    case '30 to 59 Days':
                        $amount30to59 = number_format($grn->amount, 2);
                        $total30to59 += $grn->amount;
                        break;
                    case 'Less than 30 Days':
                        $amountLess30 = number_format($grn->amount, 2);
                        $totalLess30 += $grn->amount;
                        break;
                }
                
                $html .= '<tr>';
                $html .= '<td>' . date('m/d/Y', strtotime($grn->grndate)) . '</td>';
                $html .= '<td>' . $grn->grnno . '</td>';
                $html .= '<td class="text-right">' . $amount90Above . '</td>';
                $html .= '<td class="text-right">' . $amount60to89 . '</td>';
                $html .= '<td class="text-right">' . $amount30to59 . '</td>';
                $html .= '<td class="text-right">' . $amountLess30 . '</td>';
                $html .= '<th class="text-right">' . number_format($runningBalance, 2) . '</th>';
                $html .= '</tr>';
            }
            
            // Calculate net balance after payments
            $netBalance = $runningBalance - $totalPayments;
            
            // Total row
            $html .= '<tr>';
            $html .= '<th>Total</th>';
            $html .= '<td></td>';
            $html .= '<th class="text-right">' . number_format($total90Above, 2) . '</th>';
            $html .= '<th class="text-right">' . number_format($total60to89, 2) . '</th>';
            $html .= '<th class="text-right">' . number_format($total30to59, 2) . '</th>';
            $html .= '<th class="text-right">' . number_format($totalLess30, 2) . '</th>';
            $html .= '<th class="text-right">' . number_format($runningBalance, 2) . '</th>';
            $html .= '</tr>';
            
            // Net Balance after payments row (if there are payments)
            if($totalPayments > 0) {
                $html .= '<tr>';
                $html .= '<td><em>Less: Payments</em></td>';
                $html .= '<td></td>';
                $html .= '<td class="text-right"></td>';
                $html .= '<td class="text-right"></td>';
                $html .= '<td class="text-right"></td>';
                $html .= '<td class="text-right"></td>';
                $html .= '<td class="text-right"><em>' . number_format(-$totalPayments, 2) . '</em></td>';
                $html .= '</tr>';
                
                $html .= '<tr>';
                $html .= '<th>Net Balance</th>';
                $html .= '<td></td>';
                $html .= '<th class="text-right">' . number_format($total90Above, 2) . '</th>';
                $html .= '<th class="text-right">' . number_format($total60to89, 2) . '</th>';
                $html .= '<th class="text-right">' . number_format($total30to59, 2) . '</th>';
                $html .= '<th class="text-right">' . number_format($totalLess30, 2) . '</th>';
                $html .= '<th class="text-right">' . number_format($netBalance, 2) . '</th>';
                $html .= '</tr>';
            }
            
            // Distribution row
            $html .= '<tr><th colspan="7">&nbsp;</th></tr>';
            $html .= '<tr>';
            $html .= '<td></td>';
            $html .= '<td></td>';
            $html .= '<th class="text-right">90 & Above</th>';
            $html .= '<th class="text-right">60 to 89 Days</th>';
            $html .= '<th class="text-right">30 to 59 Days</th>';
            $html .= '<th class="text-right">Less than 30 Days</th>';
            $html .= '<th class="text-right">Balance</th>';
            $html .= '</tr>';
            
            $html .= '<tr>';
            $html .= '<td></td>';
            $html .= '<td></td>';
            $html .= '<th class="text-right">' . number_format($total90Above, 2) . '</th>';
            $html .= '<th class="text-right">' . number_format($total60to89, 2) . '</th>';
            $html .= '<th class="text-right">' . number_format($total30to59, 2) . '</th>';
            $html .= '<th class="text-right">' . number_format($totalLess30, 2) . '</th>';
            $html .= '<th class="text-right">' . number_format($netBalance, 2) . '</th>';
            $html .= '</tr>';
            
            $html .= '</tbody>';
            $html .= '</table>';

        } else {
            // No creditor selected - show all creditors in summary table
            $sql = "
                SELECT 
                    s.idtbl_supplier,
                    s.suppliername,
                    s.bus_reg_no,
                    SUM(CASE WHEN DATEDIFF(?, e.grndate) > 89 AND e.status = 1 THEN e.amount ELSE 0 END) AS over_90,
                    SUM(CASE WHEN DATEDIFF(?, e.grndate) BETWEEN 60 AND 89 AND e.status = 1 THEN e.amount ELSE 0 END) AS days_60_89,
                    SUM(CASE WHEN DATEDIFF(?, e.grndate) BETWEEN 30 AND 59 AND e.status = 1 THEN e.amount ELSE 0 END) AS days_30_59,
                    SUM(CASE WHEN DATEDIFF(?, e.grndate) < 30 AND e.status = 1 THEN e.amount ELSE 0 END) AS less_30,
                    SUM(CASE WHEN e.status = 1 THEN e.amount ELSE 0 END) AS total_grns,
                    SUM(CASE WHEN ap.status = 1 THEN ap.totalpayment ELSE 0 END) AS total_payments,
                    (SUM(CASE WHEN e.status = 1 THEN e.amount ELSE 0 END) - 
                    SUM(CASE WHEN ap.status = 1 THEN ap.totalpayment ELSE 0 END)) AS net_balance
                FROM tbl_supplier s
                LEFT JOIN tbl_expence_info e ON s.idtbl_supplier = e.tbl_supplier_idtbl_supplier 
                    AND e.tbl_company_idtbl_company = ? 
                    AND e.tbl_company_branch_idtbl_company_branch = ?
                    AND e.grndate <= ?
                    AND e.status = 1
                LEFT JOIN tbl_account_paysettle ap ON s.idtbl_supplier = ap.supplier 
                    AND ap.tbl_company_idtbl_company = ? 
                    AND ap.tbl_company_branch_idtbl_company_branch = ?
                    AND ap.date <= ?
                    AND ap.status = 1
                WHERE s.status = 1
                GROUP BY s.idtbl_supplier
                HAVING net_balance > 0
                ORDER BY s.suppliername ASC
            ";

            $creditors = $this->db->query($sql, [
                $asofdate, $asofdate, $asofdate, $asofdate,
                $companyID, $branchID, $asofdate,
                $companyID, $branchID, $asofdate
            ])->result();

            // Calculate totals
            $totalOver90 = 0;
            $total60to89 = 0;
            $total30to59 = 0;
            $totalLess30 = 0;
            $grandTotal = 0;

            foreach($creditors as $cred) {
                $totalOver90 += $cred->over_90;
                $total60to89 += $cred->days_60_89;
                $total30to59 += $cred->days_30_59;
                $totalLess30 += $cred->less_30;
                $grandTotal += $cred->net_balance;
            }

            // Main Table for all creditors
            $html .= '<div class="table-responsive">';
            $html .= '<table class="table table-bordered table-striped table-sm small" style="width: 100%; border-collapse: collapse;" id="creditorStatementsTable">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th>#</th>';
            $html .= '<th>Supplier Code</th>';
            $html .= '<th>Supplier Name</th>';
            $html .= '<th class="text-right">90 & Above</th>';
            $html .= '<th class="text-right">60 to 89 Days</th>';
            $html .= '<th class="text-right">30 to 59 Days</th>';
            $html .= '<th class="text-right">Less than 30 Days</th>';
            $html .= '<th class="text-right">Total Balance</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';

            $counter = 1;
            foreach($creditors as $cred) {
                $totalRow = $cred->over_90 + $cred->days_60_89 + $cred->days_30_59 + $cred->less_30;
                
                $html .= '<tr>';
                $html .= '<td>' . $counter . '</td>';
                $html .= '<td>' . $cred->bus_reg_no . '</td>';
                $html .= '<td>' . $cred->suppliername . '</td>';
                $html .= '<td class="text-right">' . number_format($cred->over_90, 2) . '</td>';
                $html .= '<td class="text-right">' . number_format($cred->days_60_89, 2) . '</td>';
                $html .= '<td class="text-right">' . number_format($cred->days_30_59, 2) . '</td>';
                $html .= '<td class="text-right">' . number_format($cred->less_30, 2) . '</td>';
                $html .= '<th class="text-right">' . number_format($totalRow, 2) . '</th>';
                $html .= '</tr>';

                $counter++;
            }

            // Totals Row
            $html .= '<tr>';
            $html .= '<th colspan="3" class="text-right">GRAND TOTALS:</th>';
            $html .= '<th class="text-right">' . number_format($totalOver90, 2) . '</th>';
            $html .= '<th class="text-right">' . number_format($total60to89, 2) . '</th>';
            $html .= '<th class="text-right">' . number_format($total30to59, 2) . '</th>';
            $html .= '<th class="text-right">' . number_format($totalLess30, 2) . '</th>';
            $html .= '<th class="text-right">' . number_format($grandTotal, 2) . '</th>';
            $html .= '</tr>';

            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '</div>';
            
            $html .= '</div>'; // Close main div
        }

        echo $html;
    }
}