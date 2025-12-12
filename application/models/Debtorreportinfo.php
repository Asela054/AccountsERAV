<?php
class Debtorreportinfo extends CI_Model{
    public function DebtorStatementReport(){
        $fromdate = $this->input->post('fromdate');
        $todate = $this->input->post('todate');
        $customer = $this->input->post('customer');
        $companyID = $_SESSION['companyid'];
        $branchID = $_SESSION['branchid'];

        $html = '';

        // If no customer selected, show all customers with their transactions
        if(empty($customer)) {
            // Get all customers with transactions in the period
            $sqlCustomers = "
                SELECT DISTINCT c.idtbl_customer, c.customer, c.ref_no
                FROM tbl_customer c
                WHERE c.status = 1
                AND (
                    EXISTS (
                        SELECT 1 FROM tbl_sales_info s 
                        WHERE s.tbl_customer_idtbl_customer = c.idtbl_customer 
                        AND s.status = 1 
                        AND s.invdate BETWEEN ? AND ?
                        AND s.tbl_company_idtbl_company = ? 
                        AND s.tbl_company_branch_idtbl_company_branch = ?
                    )
                    OR EXISTS (
                        SELECT 1 FROM tbl_receivable r 
                        WHERE r.payer = c.idtbl_customer 
                        AND r.status = 1 
                        AND r.recdate BETWEEN ? AND ?
                        AND r.tbl_company_idtbl_company = ? 
                        AND r.tbl_company_branch_idtbl_company_branch = ?
                    )
                )
                ORDER BY c.customer ASC
            ";

            $customers = $this->db->query($sqlCustomers, [
                $fromdate, $todate, $companyID, $branchID,
                $fromdate, $todate, $companyID, $branchID
            ])->result();

            $html .= '<div class="table-responsive">';
            $html .= '<table class="table table-bordered table-striped table-sm small" id="debtorStatementTable">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th>#</th>';
            $html .= '<th>Date</th>';
            $html .= '<th>Document No</th>';
            $html .= '<th>Description</th>';
            $html .= '<th>Type</th>';
            $html .= '<th class="text-right">Debit</th>';
            $html .= '<th class="text-right">Credit</th>';
            $html .= '<th class="text-right">Balance</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            
            $grandTotalOpening = 0;
            $grandTotalDebit = 0;
            $grandTotalCredit = 0;
            $grandTotalClosing = 0;

            foreach($customers as $cust) {
                // Get Opening Balance for this customer
                $sqlOpenBalance = "SELECT ((SELECT COALESCE(SUM(`amount`), 0) FROM `tbl_sales_info` WHERE `status`=? AND `invdate`<? AND `tbl_customer_idtbl_customer`=? AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=?)-(SELECT COALESCE(SUM(`amount`), 0) FROM `tbl_receivable` WHERE `status`=? AND `recdate`<? AND `payer`=? AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=?)) AS `openbalance`";
                
                $params = [1, $fromdate, $cust->idtbl_customer, $companyID, $branchID, 1, $fromdate, $cust->idtbl_customer, $companyID, $branchID];
                $respondopenbalance = $this->db->query($sqlOpenBalance, $params);
                $openingBalance = $respondopenbalance->row()->openbalance;

                // Get Transactions for this customer
                $sql = "SELECT * FROM (SELECT `invno` AS `receiptno`, `invdate` AS `invpaydate`, `amount`, '' AS `narration`, 'D' AS `tratype`, '' AS `chequedate`, '' AS `chequeno` FROM `tbl_sales_info` WHERE `tbl_customer_idtbl_customer`=? AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=? AND `invdate` BETWEEN ? AND ? AND `status`=? UNION ALL 
                        SELECT `tbl_receivable_info`.`invoiceno` AS `receiptno`, `tbl_receivable`.`recdate` AS `invpaydate`, `tbl_receivable_info`.`amount`, `tbl_receivable_info`.`narration` AS `narration`, 'C' AS `tratype`, `tbl_receivable`.`chequedate`, `tbl_receivable`.`chequeno` FROM `tbl_receivable_info` LEFT JOIN `tbl_receivable` ON `tbl_receivable`.`idtbl_receivable`=`tbl_receivable_info`.`tbl_receivable_idtbl_receivable` WHERE `tbl_receivable`.`recdate` BETWEEN ? AND ? AND `tbl_receivable`.`status`=? AND `tbl_receivable`.`payer`=? AND `tbl_receivable`.`tbl_company_idtbl_company`=? AND `tbl_receivable`.`tbl_company_branch_idtbl_company_branch`=?) AS `u` ORDER BY `u`.`invpaydate` ASC";
                
                $transactions = $this->db->query($sql, [$cust->idtbl_customer, $companyID, $branchID, $fromdate, $todate, 1, $fromdate, $todate, 1, $cust->idtbl_customer, $companyID, $branchID])->result();

                // Start customer section  
                $html .= '<tr>';
                $html .= '<th colspan="8">' . $cust->customer . ' (' . $cust->ref_no . ')</th>';
                $html .= '</tr>';

                $runningBalance = $openingBalance;
                $counter = 1;
                $customerTotalDebit = 0;
                $customerTotalCredit = 0;

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
                    if($transaction->tratype == 'D') {
                        // Invoice/Debit
                        $runningBalance += $transaction->amount;
                        $customerTotalDebit += $transaction->amount;
                        $debitAmount = number_format($transaction->amount, 2);
                        $creditAmount = '-';
                        $typeBadge = 'Invoice';
                    } else {
                        // Receipt/Credit
                        $runningBalance -= $transaction->amount;
                        $customerTotalCredit += $transaction->amount;
                        $debitAmount = '-';
                        $creditAmount = number_format($transaction->amount, 2);
                        $typeBadge = 'Receipt';
                    }

                    $html .= '<tr>';
                    $html .= '<td>' . $counter . '</td>';
                    $html .= '<td>' . date('d/m/Y', strtotime($transaction->invpaydate)) . '</td>';
                    $html .= '<td>' . $transaction->receiptno . '</td>';
                    $html .= '<td>' . $transaction->narration . '</td>';
                    $html .= '<td>' . $typeBadge . '</td>';
                    $html .= '<td class="text-right">' . $debitAmount . '</td>';
                    $html .= '<td class="text-right">' . $creditAmount . '</td>';
                    $html .= '<td class="text-right">' . number_format($runningBalance, 2) . '</td>';
                    $html .= '</tr>';

                    $counter++;
                }

                // Customer Closing Balance Row
                $html .= '<tr>';
                $html .= '<th colspan="5" class="text-right">' . $cust->customer . ' Closing Balance</th>';
                $html .= '<th class="text-right">' . number_format($customerTotalDebit, 2) . '</th>';
                $html .= '<th class="text-right">' . number_format($customerTotalCredit, 2) . '</th>';
                $html .= '<th class="text-right">' . number_format($runningBalance, 2) . '</th>';
                $html .= '</tr>';

                // Add to grand totals
                $grandTotalOpening += $openingBalance;
                $grandTotalDebit += $customerTotalDebit;
                $grandTotalCredit += $customerTotalCredit;
                $grandTotalClosing += $runningBalance;
            }
            $html .= '</tr>';
            $html .= '<td colspan="8">&nbsp;</td>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<th colspan="5" class="text-right">GRAND TOTALS - ALL CUSTOMERS</th>';
            $html .= '<th class="text-right">' . number_format($grandTotalDebit, 2) . '</th>';
            $html .= '<th class="text-right">' . number_format($grandTotalCredit, 2) . '</th>';
            $html .= '<th class="text-right">' . number_format($grandTotalClosing, 2) . '</th>';
            $html .= '</tr>';
            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '</div>'; 

        } else {
            // Specific customer selected - show detailed statement
            // Get Opening Balance
            $sqlOpenBalance = "SELECT ((SELECT COALESCE(SUM(`amount`), 0) FROM `tbl_sales_info` WHERE `status`=? AND `invdate`<?";
            if(!empty($customer)): $sqlOpenBalance.=" AND `tbl_customer_idtbl_customer`='$customer'"; endif; 
            $sqlOpenBalance.=" AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=?)-(SELECT COALESCE(SUM(`amount`), 0) FROM `tbl_receivable` WHERE `status`=? AND `recdate`<?";
            if(!empty($customer)): $sqlOpenBalance.=" AND `payer`='$customer'"; endif;
            $sqlOpenBalance.=" AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=?)) AS `openbalance`";
            
            $params = [1, $fromdate, $companyID, $branchID, 1, $fromdate, $companyID, $branchID];
            $respondopenbalance = $this->db->query($sqlOpenBalance, $params);
            $openingBalance = $respondopenbalance->row()->openbalance;

            // Get Transactions
            $sql="SELECT * FROM (SELECT `invno` AS `receiptno`, `invdate` AS `invpaydate`, `amount`, '' AS `narration`, 'D' AS `tratype`, '' AS `chequedate`, '' AS `chequeno` FROM `tbl_sales_info` WHERE 1=1"; 
            if(!empty($customer)): $sql.=" AND `tbl_customer_idtbl_customer`='$customer'";endif; 
            $sql.=" AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=? AND `invdate` BETWEEN ? AND ? AND `status`=? UNION ALL 
            SELECT `tbl_receivable_info`.`invoiceno` AS `receiptno`, `tbl_receivable`.`recdate` AS `invpaydate`, `tbl_receivable_info`.`amount`, `tbl_receivable_info`.`narration` AS `narration`, 'C' AS `tratype`, `tbl_receivable`.`chequedate`, `tbl_receivable`.`chequeno` FROM `tbl_receivable_info` LEFT JOIN `tbl_receivable` ON `tbl_receivable`.`idtbl_receivable`=`tbl_receivable_info`.`tbl_receivable_idtbl_receivable` WHERE `tbl_receivable`.`recdate` BETWEEN ? AND ? AND `tbl_receivable`.`status`=? AND `tbl_receivable`.`tbl_company_idtbl_company`=? AND `tbl_receivable`.`tbl_company_branch_idtbl_company_branch`=?";
            if(!empty($customer)): $sql.=" AND `tbl_receivable`.`payer`='$customer'"; endif;
            $sql.=") AS `u` ORDER BY `u`.`invpaydate` ASC";
            
            $respond = $this->db->query($sql, array($companyID, $branchID, $fromdate, $todate, 1, $fromdate, $todate, 1, $companyID, $branchID));
            $transactions = $respond->result();

            // Get Customer Info
            $customerInfo = [];
            if(!empty($customer)) {
                $custQuery = $this->db->query("SELECT * FROM tbl_customer WHERE idtbl_customer = ?", [$customer]);
                $customerInfo = $custQuery->row();
            }

            // Transaction Table
            $html .= '<div class="table-responsive">';
            $html .= '<table class="table table-bordered table-striped table-sm small" id="debtorStatementTable">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th>#</th>';
            $html .= '<th>Date</th>';
            $html .= '<th>Document No</th>';
            $html .= '<th>Description</th>';
            $html .= '<th>Type</th>';
            $html .= '<th class="text-right">Debit</th>';
            $html .= '<th class="text-right">Credit</th>';
            $html .= '<th class="text-right">Balance</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';

            $runningBalance = $openingBalance;
            $counter = 1;
            $totalDebit = 0;
            $totalCredit = 0;

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
                if($transaction->tratype == 'D') {
                    // Invoice/Debit
                    $runningBalance += $transaction->amount;
                    $totalDebit += $transaction->amount;
                    $debitAmount = number_format($transaction->amount, 2);
                    $creditAmount = '-';
                    $typeBadge = 'Invoice';
                } else {
                    // Receipt/Credit
                    $runningBalance -= $transaction->amount;
                    $totalCredit += $transaction->amount;
                    $debitAmount = '-';
                    $creditAmount = number_format($transaction->amount, 2);
                    $typeBadge = 'Receipt';
                }

                $html .= '<tr>';
                $html .= '<td>' . $counter . '</td>';
                $html .= '<td>' . date('d/m/Y', strtotime($transaction->invpaydate)) . '</td>';
                $html .= '<td>' . $transaction->receiptno . '</td>';
                $html .= '<td>' . $transaction->narration . '</td>';
                $html .= '<td>' . $typeBadge . '</td>';
                $html .= '<td class="text-right">' . $debitAmount . '</td>';
                $html .= '<td class="text-right">' . $creditAmount . '</td>';
                $html .= '<td class="text-right">' . number_format($runningBalance, 2) . '</td>';
                $html .= '</tr>';

                $counter++;
            }

            // Closing Balance Row
            $html .= '<tr>';
            $html .= '<th colspan="5" class="text-right">Closing Balance</th>';
            $html .= '<th class="text-right">' . number_format($totalDebit, 2) . '</th>';
            $html .= '<th class="text-right">' . number_format($totalCredit, 2) . '</th>';
            $html .= '<th class="text-right">' . number_format($runningBalance, 2) . '</th>';
            $html .= '</tr>';

            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '</div>';
        }

        echo $html;
    }
    public function DebtorAgeAnalysisReport(){
        $asofdate = $this->input->post('asofdate') ? $this->input->post('asofdate') : date('Y-m-d');
        $customer = $this->input->post('customer');
        $companyID = $_SESSION['companyid'];
        $branchID = $_SESSION['branchid'];

        $html = '';

        // Get company name from session or database
        $companyName = isset($_SESSION['companyname']) ? $_SESSION['companyname'] : 'Company';
        
        // Get Customer Info if selected
        $customerInfo = null;
        if(!empty($customer)) {
            $custQuery = $this->db->query("SELECT * FROM tbl_customer WHERE idtbl_customer = ?", [$customer]);
            $customerInfo = $custQuery->row();
        }

        if(!empty($customer) && !empty($customerInfo)) {            
            // Main Table Header
            $html .= '<table class="table table-striped table-bordered table-sm small" id="debtorStatementTable">';
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
            
            // Get all invoices for this customer with aging calculation
            $sqlInvoices = "
                SELECT 
                    s.invno,
                    s.invdate,
                    s.amount,
                    DATEDIFF(?, s.invdate) as days_aged,
                    CASE 
                        WHEN DATEDIFF(?, s.invdate) > 89 THEN '90 & Above'
                        WHEN DATEDIFF(?, s.invdate) BETWEEN 60 AND 89 THEN '60 to 89 Days'
                        WHEN DATEDIFF(?, s.invdate) BETWEEN 30 AND 59 THEN '30 to 59 Days'
                        ELSE 'Less than 30 Days'
                    END as age_category
                FROM tbl_sales_info s
                WHERE s.tbl_customer_idtbl_customer = ?
                AND s.status = 1
                AND s.invdate <= ?
                AND s.tbl_company_idtbl_company = ?
                AND s.tbl_company_branch_idtbl_company_branch = ?
                ORDER BY s.invdate ASC
            ";

            $invoices = $this->db->query($sqlInvoices, [
                $asofdate, $asofdate, $asofdate, $asofdate,
                $customer, $asofdate, $companyID, $branchID
            ])->result();

            // Get all receipts for this customer
            $sqlReceipts = "
                SELECT 
                    SUM(ri.amount) as total_receipts
                FROM tbl_receivable r
                JOIN tbl_receivable_info ri ON r.idtbl_receivable = ri.tbl_receivable_idtbl_receivable
                WHERE r.payer = ?
                AND r.status = 1
                AND r.recdate <= ?
                AND r.tbl_company_idtbl_company = ?
                AND r.tbl_company_branch_idtbl_company_branch = ?
            ";

            $receiptsResult = $this->db->query($sqlReceipts, [
                $customer, $asofdate, $companyID, $branchID
            ])->row();
            $totalReceipts = $receiptsResult ? $receiptsResult->total_receipts : 0;

            $html .= '<tbody>';
            $html .= '<tr><th colspan="7">Group By Group: TD (TRADE DEBTORS)</th></tr>';
            $html .= '<tr><th colspan="7">' . $customerInfo->ref_no . ' (' . $customerInfo->customer . ')</th></tr>';
            
            // Initialize totals
            $total90Above = 0;
            $total60to89 = 0;
            $total30to59 = 0;
            $totalLess30 = 0;
            $runningBalance = 0;
            $totalInvoices = 0;
            
            // Add invoice rows
            foreach($invoices as $inv) {
                $totalInvoices += $inv->amount;
                $runningBalance += $inv->amount;
                
                $amount90Above = '';
                $amount60to89 = '';
                $amount30to59 = '';
                $amountLess30 = '';
                
                // Place amount in correct aging column
                switch($inv->age_category) {
                    case '90 & Above':
                        $amount90Above = number_format($inv->amount, 2);
                        $total90Above += $inv->amount;
                        break;
                    case '60 to 89 Days':
                        $amount60to89 = number_format($inv->amount, 2);
                        $total60to89 += $inv->amount;
                        break;
                    case '30 to 59 Days':
                        $amount30to59 = number_format($inv->amount, 2);
                        $total30to59 += $inv->amount;
                        break;
                    case 'Less than 30 Days':
                        $amountLess30 = number_format($inv->amount, 2);
                        $totalLess30 += $inv->amount;
                        break;
                }
                
                $html .= '<tr>';
                $html .= '<td>' . date('m/d/Y', strtotime($inv->invdate)) . '</td>';
                $html .= '<td>' . $inv->invno . '</td>';
                $html .= '<td class="text-right">' . $amount90Above . '</td>';
                $html .= '<td class="text-right">' . $amount60to89 . '</td>';
                $html .= '<td class="text-right">' . $amount30to59 . '</td>';
                $html .= '<td class="text-right">' . $amountLess30 . '</td>';
                $html .= '<th class="text-right">' . number_format($runningBalance, 2) . '</th>';
                $html .= '</tr>';
            }
            
            // Calculate net balance after receipts
            $netBalance = $runningBalance - $totalReceipts;
            
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
            
            // Net Balance after receipts row (if there are receipts)
            if($totalReceipts > 0) {
                $html .= '<tr>';
                $html .= '<td><em>Less: Receipts</em></td>';
                $html .= '<td></td>';
                $html .= '<td class="text-right"></td>';
                $html .= '<td class="text-right"></td>';
                $html .= '<td class="text-right"></td>';
                $html .= '<td class="text-right"></td>';
                $html .= '<td class="text-right"><em>' . number_format(-$totalReceipts, 2) . '</em></td>';
                $html .= '</tr>';
                
                $html .= '<tr>';
                $html .= '<th>Net Balance</td>';
                $html .= '<td></td>';
                $html .= '<th class="text-right">' . number_format($total90Above, 2) . '</td>';
                $html .= '<th class="text-right">' . number_format($total60to89, 2) . '</td>';
                $html .= '<th class="text-right">' . number_format($total30to59, 2) . '</td>';
                $html .= '<th class="text-right">' . number_format($totalLess30, 2) . '</td>';
                $html .= '<th class="text-right">' . number_format($netBalance, 2) . '</td>';
                $html .= '</tr>';
            }
            
            // Distribution row (optional - shows distribution across aging buckets)
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
            // No customer selected - show all customers in summary table
            $sql = "
                SELECT 
                    c.idtbl_customer,
                    c.customer,
                    c.ref_no,
                    SUM(CASE WHEN DATEDIFF(?, s.invdate) > 89 AND s.status = 1 THEN s.amount ELSE 0 END) AS over_90,
                    SUM(CASE WHEN DATEDIFF(?, s.invdate) BETWEEN 60 AND 89 AND s.status = 1 THEN s.amount ELSE 0 END) AS days_60_89,
                    SUM(CASE WHEN DATEDIFF(?, s.invdate) BETWEEN 30 AND 59 AND s.status = 1 THEN s.amount ELSE 0 END) AS days_30_59,
                    SUM(CASE WHEN DATEDIFF(?, s.invdate) < 30 AND s.status = 1 THEN s.amount ELSE 0 END) AS less_30,
                    SUM(CASE WHEN s.status = 1 THEN s.amount ELSE 0 END) AS total_invoice,
                    SUM(CASE WHEN r.status = 1 THEN r.amount ELSE 0 END) AS total_receipt,
                    (SUM(CASE WHEN s.status = 1 THEN s.amount ELSE 0 END) - 
                    SUM(CASE WHEN r.status = 1 THEN r.amount ELSE 0 END)) AS net_balance
                FROM tbl_customer c
                LEFT JOIN tbl_sales_info s ON c.idtbl_customer = s.tbl_customer_idtbl_customer 
                    AND s.tbl_company_idtbl_company = ? 
                    AND s.tbl_company_branch_idtbl_company_branch = ?
                    AND s.invdate <= ?
                    AND s.status = 1
                LEFT JOIN tbl_receivable r ON c.idtbl_customer = r.payer 
                    AND r.tbl_company_idtbl_company = ? 
                    AND r.tbl_company_branch_idtbl_company_branch = ?
                    AND r.recdate <= ?
                    AND r.status = 1
                WHERE c.status = 1
                GROUP BY c.idtbl_customer
                HAVING net_balance > 0
                ORDER BY c.customer ASC
            ";

            $debtors = $this->db->query($sql, [
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

            foreach($debtors as $debtor) {
                $totalOver90 += $debtor->over_90;
                $total60to89 += $debtor->days_60_89;
                $total30to59 += $debtor->days_30_59;
                $totalLess30 += $debtor->less_30;
                $grandTotal += $debtor->net_balance;
            }

            // Main Table for all customers
            $html .= '<div class="table-responsive">';
            $html .= '<table class="table table-bordered table-striped table-sm small" id="debtorStatementTable">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th>#</th>';
            $html .= '<th>Customer Code</th>';
            $html .= '<th>Customer Name</th>';
            $html .= '<th class="text-right">90 & Above</th>';
            $html .= '<th class="text-right">60 to 89 Days</th>';
            $html .= '<th class="text-right">30 to 59 Days</th>';
            $html .= '<th class="text-right">Less than 30 Days</th>';
            $html .= '<th class="text-right">Total Balance</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';

            $counter = 1;
            foreach($debtors as $debtor) {
                $totalRow = $debtor->over_90 + $debtor->days_60_89 + $debtor->days_30_59 + $debtor->less_30;
                
                $html .= '<tr>';
                $html .= '<td>' . $counter . '</td>';
                $html .= '<td>' . $debtor->ref_no . '</td>';
                $html .= '<td>' . $debtor->customer . '</td>';
                $html .= '<td class="text-right">' . number_format($debtor->over_90, 2) . '</td>';
                $html .= '<td class="text-right">' . number_format($debtor->days_60_89, 2) . '</td>';
                $html .= '<td class="text-right">' . number_format($debtor->days_30_59, 2) . '</td>';
                $html .= '<td class="text-right">' . number_format($debtor->less_30, 2) . '</td>';
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
        }

        echo $html;
    }
}