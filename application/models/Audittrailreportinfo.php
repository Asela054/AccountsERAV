<?php
class Audittrailreportinfo extends CI_Model{
    public function Audittrailreportview() {
        $companyID = $_SESSION['companyid'];
        $branchID = $_SESSION['branchid'];
        $fromdate = $this->input->post('fromdate');
        $todate = $this->input->post('todate');

        // ── Get from/to master period details ─────────────────────────────────
		$from_master = $this->db->get_where('tbl_master', [
			'idtbl_master' => $fromdate,
			'status'       => 1
		])->row();
        
		$to_master = $this->db->get_where('tbl_master', [
			'idtbl_master' => $todate,
			'status'       => 1
		])->row();
        
		// ── Build period range ────────────────────────────────────────────────
		if(!empty($from_master) && !empty($to_master)){
			$range = $this->Audittrailreportinfo->getPeriodRangeMasterIds(
				$from_master->tbl_finacial_month_idtbl_finacial_month,
				$from_master->tbl_finacial_year_idtbl_finacial_year,
				$to_master->tbl_finacial_month_idtbl_finacial_month,
				$to_master->tbl_finacial_year_idtbl_finacial_year,
				$companyID,
				$branchID
			);
            
			if(!empty($range)){
				$master_ids      = $range['master_ids'];
				$open_bal_master = $range['from_master_id'];
				$is_cross_year   = $range['is_cross_year'];
			} else {
				$master_ids      = [$fromdate];
				$open_bal_master = $fromdate;
				$is_cross_year   = false;
			}
		} else {
			$master_ids      = [$fromdate];
			$open_bal_master = $fromdate;
			$is_cross_year   = false;
		}
        
        if(is_array($master_ids)){
			$master_ids      = $master_ids;
			$open_bal_master = !empty($open_bal_master) ? $open_bal_master : $master_ids[0];
		} else {
			$master_ids      = [$master_ids];
			$open_bal_master = $master_ids;
		}
        
        $in_placeholders = implode(',', $master_ids);

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
            CASE
                WHEN a.trabatchotherno LIKE 'AP%' AND (
                    SELECT MIN(e2.grndate)
                    FROM tbl_account_payable ap2
                    JOIN tbl_account_payable_main apm2 
                        ON apm2.idtbl_account_payable_main = ap2.tbl_account_payable_main_idtbl_account_payable_main
                    JOIN tbl_expence_info e2 
                        ON e2.grnno = apm2.invoiceno
                    WHERE ap2.batchno = a.trabatchotherno
                ) IS NOT NULL 
                THEN (
                    SELECT MIN(e2.grndate)
                    FROM tbl_account_payable ap2
                    JOIN tbl_account_payable_main apm2 
                        ON apm2.idtbl_account_payable_main = ap2.tbl_account_payable_main_idtbl_account_payable_main
                    JOIN tbl_expence_info e2 
                        ON e2.grnno = apm2.invoiceno
                    WHERE ap2.batchno = a.trabatchotherno
                )
                WHEN a.trabatchotherno LIKE 'AR%' AND (
                    SELECT MIN(s2.invdate)
                    FROM tbl_account_receivable ar2
                    JOIN tbl_account_receivable_main arm2 
                        ON arm2.idtbl_account_receivable_main = ar2.tbl_account_receivable_main_idtbl_account_receivable_main
                    JOIN tbl_sales_info s2 
                        ON s2.invno = arm2.receiptno
                    WHERE ar2.batchno = a.trabatchotherno
                ) IS NOT NULL
                THEN (
                    SELECT MIN(s2.invdate)
                    FROM tbl_account_receivable ar2
                    JOIN tbl_account_receivable_main arm2 
                        ON arm2.idtbl_account_receivable_main = ar2.tbl_account_receivable_main_idtbl_account_receivable_main
                    JOIN tbl_sales_info s2 
                        ON s2.invno = arm2.receiptno
                    WHERE ar2.batchno = a.trabatchotherno
                )
                ELSE DATE(a.tradate)
            END AS 'Date',   
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
            JOIN tbl_account_payable_main apm ON apm.idtbl_account_payable_main = ap.tbl_account_payable_main_idtbl_account_payable_main
            JOIN tbl_expence_info e ON e.grnno = apm.invoiceno
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
            JOIN tbl_account_receivable_main arm ON arm.idtbl_account_receivable_main = ar.tbl_account_receivable_main_idtbl_account_receivable_main
            JOIN tbl_sales_info s ON s.invno = arm.receiptno
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
        -- LEFT JOIN tbl_receivable re 
        --     ON a.trabatchotherno = re.batchno AND a.accamount = re.amount AND a.trabatchotherno LIKE 'RE%'
        -- LEFT JOIN tbl_account_detail det_re 
        --     ON re.tbl_account_detail_idtbl_account_detail = det_re.idtbl_account_detail

        LEFT JOIN (
            SELECT 
                re.batchno, 
                re.amount,
                ree.tratype,
                ree.tbl_account_detail_idtbl_account_detail AS entry_account_detail_id,
                d.accountno, 
                d.accountname
            FROM tbl_receivable re
            LEFT JOIN tbl_receivable_entry ree 
                ON ree.tbl_receivable_idtbl_receivable = re.idtbl_receivable
            JOIN tbl_account_detail d 
                ON ree.tbl_account_detail_idtbl_account_detail = d.idtbl_account_detail
            GROUP BY 
                re.batchno, 
                re.amount, 
                ree.tratype,
                ree.tbl_account_detail_idtbl_account_detail
        ) det_re ON a.trabatchotherno = det_re.batchno 
                AND a.accamount = det_re.amount 
                AND a.crdr = det_re.tratype
                AND a.trabatchotherno LIKE 'RE%'

        -- PS Detail
        -- LEFT JOIN tbl_account_paysettle ps 
        --     ON a.trabatchotherno = ps.batchno AND a.accamount = ps.totalpayment AND a.trabatchotherno LIKE 'PS%'
        -- LEFT JOIN tbl_account_detail det_ps 
        --     ON ps.tbl_account_detail_idtbl_account_detail = det_ps.idtbl_account_detail
        -- PS Detail
        LEFT JOIN (
            SELECT 
                ps.batchno, 
                ps.totalpayment,
                pse.tratype,
                pse.tbl_account_detail_idtbl_account_detail AS entry_account_detail_id,
                d.accountno, 
                d.accountname
            FROM tbl_account_paysettle ps
            LEFT JOIN tbl_account_paysettle_entry pse 
                ON pse.tbl_account_paysettle_idtbl_account_paysettle = ps.idtbl_account_paysettle
            JOIN tbl_account_detail d 
                ON pse.tbl_account_detail_idtbl_account_detail = d.idtbl_account_detail
            GROUP BY 
                ps.batchno, 
                ps.totalpayment, 
                pse.tratype,
                pse.tbl_account_detail_idtbl_account_detail
        ) det_ps ON a.trabatchotherno = det_ps.batchno 
                AND a.accamount = det_ps.totalpayment 
                AND a.crdr = det_ps.tratype
                AND a.trabatchotherno LIKE 'PS%'

        WHERE a.trabatchotherno IS NOT NULL 
            AND a.trabatchotherno != ''
            AND a.tbl_master_idtbl_master IN ($in_placeholders)
            AND a.tradate <= DATE(NOW())
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

    public function getPeriodRangeMasterIds(
		$from_month_id, $from_year_id,
		$to_month_id,   $to_year_id,
		$companyid,     $branchid
	){
		// idtbl_finacial_month id eka thamai fiscal order eka (1=April...10=January...12=March)
		// 'month' column eka calendar month number ekak (April=4, Jan=1) - ehema fiscal
		// comparison ekakata use karanna baha, cross-calendar-year wrap wenawa (Jan=1 < April=4)
		$sql = "SELECT 
					m.idtbl_master,
					m.tbl_finacial_year_idtbl_finacial_year,
					m.tbl_finacial_month_idtbl_finacial_month,
					fy.startdate AS year_startdate,
					fm.idtbl_finacial_month AS fiscal_order,
					CONCAT(fy.year, '-', fm.monthname) AS period_label
				FROM tbl_master m
				INNER JOIN tbl_finacial_year fy
					ON fy.idtbl_finacial_year = m.tbl_finacial_year_idtbl_finacial_year
				INNER JOIN tbl_finacial_month fm
					ON fm.idtbl_finacial_month = m.tbl_finacial_month_idtbl_finacial_month
				INNER JOIN tbl_finacial_year fy_from
					ON fy_from.idtbl_finacial_year = ?
				INNER JOIN tbl_finacial_month fm_from
					ON fm_from.idtbl_finacial_month = ?
				INNER JOIN tbl_finacial_year fy_to
					ON fy_to.idtbl_finacial_year = ?
				INNER JOIN tbl_finacial_month fm_to
					ON fm_to.idtbl_finacial_month = ?
				WHERE m.tbl_company_idtbl_company = ?
				AND m.tbl_company_branch_idtbl_company_branch = ?
				AND m.status = 1
				-- Lower bound: >= from period (fiscal_order id use karanna, month column epa)
				AND (
					fy.startdate > fy_from.startdate
					OR (
						fy.startdate = fy_from.startdate
						AND fm.idtbl_finacial_month >= fm_from.idtbl_finacial_month
					)
				)
				-- Upper bound: <= to period
				AND (
					fy.startdate < fy_to.startdate
					OR (
						fy.startdate = fy_to.startdate
						AND fm.idtbl_finacial_month <= fm_to.idtbl_finacial_month
					)
				)
				ORDER BY fy.startdate ASC, fm.idtbl_finacial_month ASC";

		$periods = $this->db->query($sql, [
			$from_year_id, $from_month_id,
			$to_year_id,   $to_month_id,
			$companyid,    $branchid
		])->result();
		
		if(empty($periods)) return null;

		$master_ids    = array_column((array)$periods, 'idtbl_master');
		$is_cross_year = ($from_year_id != $to_year_id);

		return [
			'master_ids'     => $master_ids,
			'from_master_id' => $master_ids[0],
			'to_master_id'   => end($master_ids),
			'is_cross_year'  => $is_cross_year,
			'periods'        => $periods,
			'period_count'   => count($master_ids)
		];
	}
}