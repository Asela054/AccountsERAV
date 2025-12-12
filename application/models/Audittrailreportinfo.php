<?php
class Audittrailreportinfo extends CI_Model{
    // public function Audittrailreportview(){
    //     $companyID = $this->session->userdata('companyID');
    //     $branchID = $this->session->userdata('branchID');
    //     $fromdate = $this->input->post('fromdate');
    //     $todate = $this->input->post('todate');

    //     $sql = "SELECT 
    //         a.batchno AS 'Audit Trail Number',
    //         DATE(a.tradate) AS 'Date',
    //         SUBSTRING(a.trabatchotherno, 3) AS 'Reference',
    //         SUBSTRING(a.trabatchotherno, 1, 2) AS 'TrCode',
    //         acc.accountname AS 'LedgerAccount',
    //         a.narration AS 'Description',
    //         CASE 
    //             WHEN a.crdr = 'D' THEN a.accamount 
    //             ELSE NULL 
    //         END AS 'Debit',
    //         CASE 
    //             WHEN a.crdr = 'C' THEN a.accamount 
    //             ELSE NULL 
    //         END AS 'Credit'
    //     FROM tbl_account_transaction a
    //     INNER JOIN tbl_account acc ON a.tbl_account_idtbl_account = acc.idtbl_account
    //     WHERE a.trabatchotherno IS NOT NULL 
    //         AND a.trabatchotherno != ''
    //         AND DATE(a.tradate) BETWEEN '$fromdate' AND '$todate'
    //         AND a.tbl_company_idtbl_company = '$companyID'
    //         AND a.tbl_company_branch_idtbl_company_branch = '$branchID'
    //     ORDER BY a.batchno, a.tradate, a.seqno";
    //     $respond = $this->db->query($sql);
        

    //     $html = '';
    //     $html .= '<table id="audittrailreporttable" class="table table-bordered table-striped table-sm small">';
    //     $html .= '<thead>';
    //     $html .= '<tr>';
    //     $html .= '<th>Date</th>';
    //     $html .= '<th>Referance</th>';
    //     $html .= '<th>Tr Code</th>';
    //     $html .= '<th>Ledger Account</th>';
    //     $html .= '<th>Description</th>';
    //     $html .= '<th class="text-right">Debit</th>';
    //     $html .= '<th class="text-right">Credit</th>';
    //     $html .= '</tr>';
    //     $html .= '</thead>';
    //     $html .= '<tbody>';
    //     foreach($respond->result() as $row){
    //         $html .= '<tr>';
    //         $html .= '<td>'.$row->Date.'</td>';
    //         $html .= '<td>'.$row->Reference.'</td>';
    //         $html .= '<td>'.$row->TrCode.'</td>';
    //         $html .= '<td>'.$row->LedgerAccount.'</td>';
    //         $html .= '<td>'.$row->Description.'</td>';
    //         $html .= '<td class="text-right">'.number_format($row->Debit, 2).'</td>';
    //         $html .= '<td class="text-right">'.number_format($row->Credit, 2).'</td>';
    //         $html .= '</tr>';
    //     }   
    //     $html .= '</tbody>';
    //     $html .= '</table>';

    // }
    public function Audittrailreportview() {
        $companyID = $_SESSION['companyid'];
        $branchID = $_SESSION['branchid'];
        $fromdate = $this->input->post('fromdate');
        $todate = $this->input->post('todate');

        $sql = "SELECT 
            a.batchno AS 'Audit Trail Number',
            DATE(a.tradate) AS 'Date',
            SUBSTRING(a.trabatchotherno, 3) AS 'Reference',
            SUBSTRING(a.trabatchotherno, 1, 2) AS 'TrCode',
            acc.accountname AS 'LedgerAccount',
            a.narration AS 'Description',
            CASE 
                WHEN a.crdr = 'D' THEN a.accamount 
                ELSE NULL 
            END AS 'Debit',
            CASE 
                WHEN a.crdr = 'C' THEN a.accamount 
                ELSE NULL 
            END AS 'Credit'
        FROM tbl_account_transaction a
        INNER JOIN tbl_account acc ON a.tbl_account_idtbl_account = acc.idtbl_account
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