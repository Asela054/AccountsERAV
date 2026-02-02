<?php
class Audittrailreportinfo extends CI_Model{
    public function Audittrailreportview() {
        $companyID = $_SESSION['companyid'];
        $branchID = $_SESSION['branchid'];
        $fromdate = $this->input->post('fromdate');
        $todate = $this->input->post('todate');

        // SELECT 
        //     a.batchno AS 'Audit Trail Number',
        //     DATE(a.tradate) AS 'Date',
        //     SUBSTRING(a.trabatchotherno, 3) AS 'Reference',
        //     SUBSTRING(a.trabatchotherno, 1, 2) AS 'TrCode',
        //     CASE 
        //         WHEN acc.accountno IS NOT NULL THEN CONCAT(acc.accountno, ' - ', acc.accountname)
        //         WHEN acc_detail.accountno IS NOT NULL THEN CONCAT(acc_detail.accountno, ' - ', acc_detail.accountname)
        //         ELSE 'N/A'
        //     END AS 'LedgerAccount',
        //     a.narration AS 'Description',
        //     CASE 
        //         WHEN a.crdr = 'D' THEN a.accamount 
        //         ELSE NULL 
        //     END AS 'Debit',
        //     CASE 
        //         WHEN a.crdr = 'C' THEN a.accamount 
        //         ELSE NULL 
        //     END AS 'Credit'
        // FROM tbl_account_transaction a
        // LEFT JOIN tbl_account_receivable ar 
        //     ON a.trabatchotherno = ar.batchno 
        //     AND a.accamount = ar.amount
        //     AND a.tbl_company_idtbl_company = ar.tbl_company_idtbl_company
        //     AND a.tbl_company_branch_idtbl_company_branch = ar.tbl_company_branch_idtbl_company_branch
        // LEFT JOIN tbl_account acc 
        //     ON ar.tbl_account_idtbl_account = acc.idtbl_account
        // LEFT JOIN tbl_account_detail acc_detail 
        //     ON ar.tbl_account_detail_idtbl_account_detail = acc_detail.idtbl_account_detail
        // WHERE a.trabatchotherno IS NOT NULL 
        //     AND a.trabatchotherno != ''
        //     AND a.trabatchotherno LIKE 'AR%'
        //     AND DATE(a.tradate) BETWEEN '2025-01-01' AND '2025-12-31'
        //     AND a.tbl_company_idtbl_company = '1'
        //     AND a.tbl_company_branch_idtbl_company_branch = '1'
        // ORDER BY a.batchno, a.tradate, a.seqno

        // SELECT 
        //     a.batchno AS 'Audit Trail Number',
        //     DATE(a.tradate) AS 'Date',
        //     SUBSTRING(a.trabatchotherno, 3) AS 'Reference',
        //     SUBSTRING(a.trabatchotherno, 1, 2) AS 'TrCode',
        //     /* Consolidating Account Info from all possible joined tables */
        //     CASE 
        //         WHEN acc_ar.accountno IS NOT NULL THEN CONCAT(acc_ar.accountno, ' - ', acc_ar.accountname)
        //         WHEN det_ar.accountno IS NOT NULL THEN CONCAT(det_ar.accountno, ' - ', det_ar.accountname)
                
        //         WHEN acc_ap.accountno IS NOT NULL THEN CONCAT(acc_ap.accountno, ' - ', acc_ap.accountname)
        //         WHEN det_ap.accountno IS NOT NULL THEN CONCAT(det_ap.accountno, ' - ', det_ap.accountname)
                
        //         WHEN acc_je.accountno IS NOT NULL THEN CONCAT(acc_je.accountno, ' - ', acc_je.accountname)
        //         WHEN det_je.accountno IS NOT NULL THEN CONCAT(det_je.accountno, ' - ', det_je.accountname)
                
        //         WHEN acc_re.accountno IS NOT NULL THEN CONCAT(acc_re.accountno, ' - ', acc_re.accountname)
        //         WHEN det_re.accountno IS NOT NULL THEN CONCAT(det_re.accountno, ' - ', det_re.accountname)
                
        //         WHEN acc_ps.accountno IS NOT NULL THEN CONCAT(acc_ps.accountno, ' - ', acc_ps.accountname)
        //         WHEN det_ps.accountno IS NOT NULL THEN CONCAT(det_ps.accountno, ' - ', det_ps.accountname)
                
        //         ELSE 'N/A'
        //     END AS 'LedgerAccount',
        //     a.narration AS 'Description',
        //     CASE WHEN a.crdr = 'D' THEN a.accamount ELSE NULL END AS 'Debit',
        //     CASE WHEN a.crdr = 'C' THEN a.accamount ELSE NULL END AS 'Credit'

        // FROM tbl_account_transaction a

        // -- 1. JOIN for AR (Account Receivable)
        // LEFT JOIN tbl_account_receivable ar 
        //     ON a.trabatchotherno = ar.batchno AND a.accamount = ar.amount AND a.trabatchotherno LIKE 'AR%'
        // LEFT JOIN tbl_account acc_ar ON ar.tbl_account_idtbl_account = acc_ar.idtbl_account
        // LEFT JOIN tbl_account_detail det_ar ON ar.tbl_account_detail_idtbl_account_detail = det_ar.idtbl_account_detail

        // -- 2. JOIN for AP (Account Payable)
        // LEFT JOIN tbl_account_payable ap 
        //     ON a.trabatchotherno = ap.batchno AND a.accamount = ap.amount AND a.trabatchotherno LIKE 'AP%'
        // LEFT JOIN tbl_account acc_ap ON ap.tbl_account_idtbl_account = acc_ap.idtbl_account
        // LEFT JOIN tbl_account_detail det_ap ON ap.tbl_account_detail_idtbl_account_detail = det_ap.idtbl_account_detail

        // -- 3. JOIN for JE (Journal Entry / Manual)
        // LEFT JOIN tbl_account_transaction_manual je 
        //     ON a.trabatchotherno = je.batchno AND a.accamount = je.amount AND a.trabatchotherno LIKE 'JE%'
        // LEFT JOIN tbl_account acc_je ON je.tbl_account_idtbl_account = acc_je.idtbl_account
        // LEFT JOIN tbl_account_detail det_je ON je.tbl_account_detail_idtbl_account_detail = det_je.idtbl_account_detail

        // -- 4. JOIN for RE (Receipts)
        // LEFT JOIN tbl_receivable re 
        //     ON a.trabatchotherno = re.batchno AND a.accamount = re.amount AND a.trabatchotherno LIKE 'RE%'
        // LEFT JOIN tbl_account acc_re ON re.tbl_account_idtbl_account = acc_re.idtbl_account
        // LEFT JOIN tbl_account_detail det_re ON re.tbl_account_detail_idtbl_account_detail = det_re.idtbl_account_detail

        // -- 5. JOIN for PS (Pay Settlement)
        // -- Note: Matching against 'totalpayment' column as per your PS table structure
        // LEFT JOIN tbl_account_paysettle ps 
        //     ON a.trabatchotherno = ps.batchno AND a.accamount = ps.totalpayment AND a.trabatchotherno LIKE 'PS%'
        // LEFT JOIN tbl_account acc_ps ON ps.tbl_account_idtbl_account = acc_ps.idtbl_account
        // LEFT JOIN tbl_account_detail det_ps ON ps.tbl_account_detail_idtbl_account_detail = det_ps.idtbl_account_detail

        // WHERE a.trabatchotherno IS NOT NULL 
        //     AND a.trabatchotherno != ''
        //     AND DATE(a.tradate) BETWEEN '2025-01-01' AND '2025-12-31'
        //     AND a.tbl_company_idtbl_company = '1'
        //     AND a.tbl_company_branch_idtbl_company_branch = '1'
        // ORDER BY a.batchno, a.tradate, a.seqno;

        // $sql = "SELECT 
        //     a.batchno AS 'Audit Trail Number',
        //     DATE(a.tradate) AS 'Date',
        //     SUBSTRING(a.trabatchotherno, 3) AS 'Reference',
        //     SUBSTRING(a.trabatchotherno, 1, 2) AS 'TrCode',
        //     acc.accountname AS 'LedgerAccount',
        //     a.narration AS 'Description',
        //     CASE 
        //         WHEN a.crdr = 'D' THEN a.accamount 
        //         ELSE NULL 
        //     END AS 'Debit',
        //     CASE 
        //         WHEN a.crdr = 'C' THEN a.accamount 
        //         ELSE NULL 
        //     END AS 'Credit'
        // FROM tbl_account_transaction a
        // INNER JOIN tbl_account acc ON a.tbl_account_idtbl_account = acc.idtbl_account
        // WHERE a.trabatchotherno IS NOT NULL 
        //     AND a.trabatchotherno != ''
        //     AND DATE(a.tradate) BETWEEN '$fromdate' AND '$todate'
        //     AND a.tbl_company_idtbl_company = '$companyID'
        //     AND a.tbl_company_branch_idtbl_company_branch = '$branchID'
        // ORDER BY a.batchno, a.tradate, a.seqno";
        // $sql = "SELECT 
        //             a.batchno AS 'Audit Trail Number',
        //             DATE(a.tradate) AS 'Date',
        //             SUBSTRING(a.trabatchotherno, 3) AS 'Reference',
        //             SUBSTRING(a.trabatchotherno, 1, 2) AS 'TrCode',
        //             /* Logic: If a detail/sub-account exists in the specific module tables, show it.
        //             Otherwise, fall back to the main account name from the transaction table.
        //             */
        //             CASE 
        //                 WHEN det_ar.accountno IS NOT NULL THEN CONCAT(det_ar.accountno, ' - ', det_ar.accountname)
        //                 WHEN det_ap.accountno IS NOT NULL THEN CONCAT(det_ap.accountno, ' - ', det_ap.accountname)
        //                 WHEN det_je.accountno IS NOT NULL THEN CONCAT(det_je.accountno, ' - ', det_je.accountname)
        //                 WHEN det_re.accountno IS NOT NULL THEN CONCAT(det_re.accountno, ' - ', det_re.accountname)
        //                 WHEN det_ps.accountno IS NOT NULL THEN CONCAT(det_ps.accountno, ' - ', det_ps.accountname)
        //                 ELSE CONCAT(acc_main.accountno, ' - ', acc_main.accountname)
        //             END AS 'LedgerAccount',
        //             a.narration AS 'Description',
        //             CASE WHEN a.crdr = 'D' THEN a.accamount ELSE NULL END AS 'Debit',
        //             CASE WHEN a.crdr = 'C' THEN a.accamount ELSE NULL END AS 'Credit'

        //         FROM tbl_account_transaction a
        //         /* 1. Primary Join: Get the main account info directly from the transaction table */
        //         INNER JOIN tbl_account acc_main 
        //             ON a.tbl_account_idtbl_account = acc_main.idtbl_account

        //         /* 2. Optional Joins: Get detail account info from specific modules ONLY if they match */
        //         -- AR Detail
        //         LEFT JOIN tbl_account_receivable ar 
        //             ON a.trabatchotherno = ar.batchno AND a.accamount = ar.amount AND a.crdr = ar.tratype AND a.trabatchotherno LIKE 'AR%' AND a.tratype ='R'
        //         LEFT JOIN tbl_account_detail det_ar 
        //             ON ar.tbl_account_detail_idtbl_account_detail = det_ar.idtbl_account_detail

        //         -- AP Detail
        //         LEFT JOIN tbl_account_payable ap 
        //             ON a.trabatchotherno = ap.batchno AND a.accamount = ap.amount AND a.crdr = ap.tratype AND a.trabatchotherno LIKE 'AP%' AND a.tratype ='P'
        //         LEFT JOIN tbl_account_detail det_ap 
        //             ON ap.tbl_account_detail_idtbl_account_detail = det_ap.idtbl_account_detail

        //         -- JE Detail
        //         LEFT JOIN tbl_account_transaction_manual je 
        //             ON a.trabatchotherno = je.batchno AND a.accamount = je.amount AND a.crdr = je.crdr AND a.trabatchotherno LIKE 'JE%' AND a.tratype ='J'
        //         LEFT JOIN tbl_account_detail det_je 
        //             ON je.tbl_account_detail_idtbl_account_detail = det_je.idtbl_account_detail

        //         -- RE Detail
        //         LEFT JOIN tbl_receivable re 
        //             ON a.trabatchotherno = re.batchno AND a.accamount = re.amount AND a.trabatchotherno LIKE 'RE%'
        //         LEFT JOIN tbl_account_detail det_re 
        //             ON re.tbl_account_detail_idtbl_account_detail = det_re.idtbl_account_detail

        //         -- PS Detail
        //         LEFT JOIN tbl_account_paysettle ps 
        //             ON a.trabatchotherno = ps.batchno AND a.accamount = ps.totalpayment AND a.trabatchotherno LIKE 'PS%'
        //         LEFT JOIN tbl_account_detail det_ps 
        //             ON ps.tbl_account_detail_idtbl_account_detail = det_ps.idtbl_account_detail

        //         WHERE a.trabatchotherno IS NOT NULL 
        //             AND a.trabatchotherno != ''
        //             AND DATE(a.tradate) BETWEEN '$fromdate' AND '$todate'
        //             AND a.tbl_company_idtbl_company = '$companyID'
        //             AND a.tbl_company_branch_idtbl_company_branch = '$branchID'
        //         ORDER BY a.batchno, a.tradate, a.seqno;";
        $sql = "SELECT 
            a.batchno AS 'Audit Trail Number',
            DATE(a.tradate) AS 'Date',
            SUBSTRING(a.trabatchotherno, 3) AS 'Reference',
            SUBSTRING(a.trabatchotherno, 1, 2) AS 'TrCode',
            /* Priority Logic: Uses grouped detail accounts first, then falls back to main account */
            CASE 
                WHEN det_ar.accountno IS NOT NULL THEN CONCAT(det_ar.accountno, ' - ', det_ar.accountname)
                WHEN det_ap.accountno IS NOT NULL THEN CONCAT(det_ap.accountno, ' - ', det_ap.accountname)
                WHEN det_je.accountno IS NOT NULL THEN CONCAT(det_je.accountno, ' - ', det_je.accountname)
                WHEN det_re.accountno IS NOT NULL THEN CONCAT(det_re.accountno, ' - ', det_re.accountname)
                WHEN det_ps.accountno IS NOT NULL THEN CONCAT(det_ps.accountno, ' - ', det_ps.accountname)
                ELSE CONCAT(acc_main.accountno, ' - ', acc_main.accountname)
            END AS 'LedgerAccount',
            a.narration AS 'Description',
            CASE WHEN a.crdr = 'D' THEN a.accamount ELSE NULL END AS 'Debit',
            CASE WHEN a.crdr = 'C' THEN a.accamount ELSE NULL END AS 'Credit'

        FROM tbl_account_transaction a
        INNER JOIN tbl_account acc_main 
            ON a.tbl_account_idtbl_account = acc_main.idtbl_account

        -- AP Detail: Grouped to prevent duplication when multiple items share a batch/amount
        LEFT JOIN (
            SELECT ap.batchno, ap.amount, ap.tratype, d.accountno, d.accountname
            FROM tbl_account_payable ap
            JOIN tbl_account_detail d ON ap.tbl_account_detail_idtbl_account_detail = d.idtbl_account_detail
            GROUP BY ap.batchno, ap.amount, ap.tratype
        ) det_ap ON a.trabatchotherno = det_ap.batchno 
                AND a.accamount = det_ap.amount 
                AND a.crdr = det_ap.tratype 
                AND a.trabatchotherno LIKE 'AP%'

        -- AR Detail: Grouped to prevent duplication
        LEFT JOIN (
            SELECT ar.batchno, ar.amount, ar.tratype, d.accountno, d.accountname
            FROM tbl_account_receivable ar
            JOIN tbl_account_detail d ON ar.tbl_account_detail_idtbl_account_detail = d.idtbl_account_detail
            GROUP BY ar.batchno, ar.amount, ar.tratype
        ) det_ar ON a.trabatchotherno = det_ar.batchno 
                AND a.accamount = det_ar.amount 
                AND a.crdr = det_ar.tratype 
                AND a.trabatchotherno LIKE 'AR%'

        -- JE Detail: Grouped to prevent duplication
        LEFT JOIN (
            SELECT je.batchno, je.amount, je.crdr, d.accountno, d.accountname
            FROM tbl_account_transaction_manual je
            JOIN tbl_account_detail d ON je.tbl_account_detail_idtbl_account_detail = d.idtbl_account_detail
            GROUP BY je.batchno, je.amount, je.crdr
        ) det_je ON a.trabatchotherno = det_je.batchno 
                AND a.accamount = det_je.amount 
                AND a.crdr = det_je.crdr 
                AND a.trabatchotherno LIKE 'JE%'

        -- RE Detail
        LEFT JOIN tbl_receivable re 
            ON a.trabatchotherno = re.batchno AND a.accamount = re.amount AND a.trabatchotherno LIKE 'RE%'
        LEFT JOIN tbl_account_detail det_re 
            ON re.tbl_account_detail_idtbl_account_detail = det_re.idtbl_account_detail

        -- PS Detail
        LEFT JOIN tbl_account_paysettle ps 
            ON a.trabatchotherno = ps.batchno AND a.accamount = ps.totalpayment AND a.trabatchotherno LIKE 'PS%'
        LEFT JOIN tbl_account_detail det_ps 
            ON ps.tbl_account_detail_idtbl_account_detail = det_ps.idtbl_account_detail

        WHERE a.trabatchotherno IS NOT NULL 
            AND a.trabatchotherno != ''
            AND DATE(a.tradate) BETWEEN '$fromdate' AND '$todate'
            AND a.tbl_company_idtbl_company = '$companyID'
            AND a.tbl_company_branch_idtbl_company_branch = '$branchID'
        ORDER BY a.batchno, a.tradate, a.seqno";
        
        $respond = $this->db->query($sql);
        
        $html = '<table class="table table-bordered table-striped table-sm small" id="audittrailreporttable">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Date</th>';
        $html .= '<th>Reference</th>';
        $html .= '<th>Tr Code</th>';
        $html .= '<th>Ledger Account</th>';
        $html .= '<th>Description</th>';
        $html .= '<th class="text-right">Debit</th>';
        $html .= '<th class="text-right">Credit</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        $currentBatch = '';
        $batchTotalDebit = 0;
        $batchTotalCredit = 0;
        
        foreach($respond->result() as $row) {
            $batchNo = $row->{'Audit Trail Number'};
            
            // Start new batch group
            if ($currentBatch != $batchNo) {
                // Close previous batch if exists
                if ($currentBatch != '') {
                    // Add batch total row
                    $html .= '<tr>';
                    $html .= '<th colspan="5" class="text-right">Totals for Audit Trail Number : '.$currentBatch.'</th>';
                    $html .= '<th class="text-right">'.number_format($batchTotalDebit, 2).'</th>';
                    $html .= '<th class="text-right">'.number_format($batchTotalCredit, 2).'</th>';
                    $html .= '</tr>';
                    
                    // Add spacing between batches
                    $html .= '<tr><td colspan="7">&nbsp;</td></tr>';
                }
                
                // Reset batch totals
                $batchTotalDebit = 0;
                $batchTotalCredit = 0;
                
                // Add batch header row
                $html .= '<tr>';
                $html .= '<th colspan="7">Audit Trail Number : '.$batchNo.'</th>';
                $html .= '</tr>';
                
                $currentBatch = $batchNo;
            }
            
            // Add transaction row
            $debitAmount = $row->Debit ? floatval($row->Debit) : 0;
            $creditAmount = $row->Credit ? floatval($row->Credit) : 0;
            
            $batchTotalDebit += $debitAmount;
            $batchTotalCredit += $creditAmount;
            
            $html .= '<tr>';
            $html .= '<td>'.($row->Date ? date('m/d/Y', strtotime($row->Date)) : '').'</td>';
            $html .= '<td>'.$row->Reference.'</td>';
            $html .= '<td>'.$row->TrCode.'</td>';
            $html .= '<td>'.$row->LedgerAccount.'</td>';
            $html .= '<td>'.$row->Description.'</td>';
            $html .= '<td class="text-right">'.($row->Debit ? number_format($row->Debit, 2) : '').'</td>';
            $html .= '<td class="text-right">'.($row->Credit ? number_format($row->Credit, 2) : '').'</td>';
            $html .= '</tr>';
        }
        
        // Add final batch total if there are results
        if ($currentBatch != '') {
            $html .= '<tr>';
            $html .= '<th class="text-right" colspan="5">Totals for Audit Trail Number : '.$currentBatch.'</th>';
            $html .= '<th class="text-right">'.number_format($batchTotalDebit, 2).'</th>';
            $html .= '<th class="text-right">'.number_format($batchTotalCredit, 2).'</th>';
            $html .= '</tr>';
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        
        echo $html;
    }
}