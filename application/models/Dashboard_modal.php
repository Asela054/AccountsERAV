<?php
class Dashboard_modal extends CI_Model{
    // public function getCashFlowData(){
    //     $company=$this->session->userdata('companyid');
    //     $branch=$this->session->userdata('branchid');
    
    //     $sql = "SELECT 
    //         DATE_FORMAT(t.tradate, '%b') AS month_label, -- e.g., 'JAN', 'FEB'
    //         SUM(CASE WHEN t.crdr = 'C' THEN t.accamount ELSE 0 END) AS money_in,
    //         SUM(CASE WHEN t.crdr = 'D' THEN t.accamount ELSE 0 END) AS money_out
    //     FROM tbl_account_transaction_full t
    //     INNER JOIN tbl_account a ON t.tbl_account_idtbl_account = a.idtbl_account
    //     INNER JOIN tbl_account_allocation al ON a.idtbl_account = al.tbl_account_idtbl_account
    //     WHERE a.specialcate = ? 
    //     AND al.companybank = ? 
    //     AND al.branchcompanybank = ?
    //     AND t.status = ?
    //     GROUP BY MONTH(t.tradate), month_label
    //     ORDER BY MONTH(t.tradate) ASC";
    //     $respond = $this->db->query($sql, array(1, $company, $branch, 1))->result();

    //     // Assuming $results contains the output of the query above
    //     $labels = [];
    //     $moneyIn = [];
    //     $moneyOut = [];
    //     $totalMoneyIn = 0;
    //     $totalMoneyOut = 0;

    //     foreach ($respond as $row) {
    //         $labels[]   = strtoupper($row->month_label);
    //         $moneyIn[]  = (float)$row->money_in;
    //         $moneyOut[] = (float)$row->money_out;
    //         $totalMoneyIn += (float)$row->money_in;
    //         $totalMoneyOut += (float)$row->money_out;
    //     }

    //     $totalCurrenBal = $totalMoneyIn - $totalMoneyOut;
    //     $totalCurrenBal = $totalCurrenBal < 0 ? '(' . number_format(abs($totalCurrenBal), 2) . ')' : number_format($totalCurrenBal, 2);
    //     // Pass these variables to your view
    //     $dataForChart = [
    //         'labels'   => $labels,
    //         'moneyIn'  => $moneyIn,
    //         'moneyOut' => $moneyOut,
    //         'totalCurrenBal' => $totalCurrenBal
    //     ];

    //     return $dataForChart;
    // }
    public function getCashFlowData($master_ids = null){
        $company = $this->session->userdata('companyid');
        $branch  = $this->session->userdata('branchid');

        // Period range නොදුන්නොත් — recent 12 periods default කරනවා
        if (empty($master_ids)) {
            $all_masters = $this->getAllMasters($company, $branch);
            $recent      = array_slice($all_masters, -12);
            $master_ids  = array_column($recent, 'idtbl_master');
        }

        if (empty($master_ids)) {
            return [
                'labels' => [], 'moneyIn' => [], 'moneyOut' => [], 'totalCurrenBal' => '0.00'
            ];
        }

        $in_placeholders = implode(',', array_fill(0, count($master_ids), '?'));

        // ══════════════════════════════════════════════════════════════════
        // Income (category 4, CR normal)  → money_in
        // Expense (category 2, DR normal) → money_out
        // Same filter/exclusion logic as getCashFlowReport's operating_sql
        // (reversstatus = 0, no direct company/branch on the transaction
        // table itself — scoping happens via tbl_account_allocation join,
        // matching the reference report exactly)
        // ══════════════════════════════════════════════════════════════════
        $sql = "
            SELECT
                m.idtbl_master,
                fy.startdate,
                fm.idtbl_finacial_month AS fiscal_order,
                fm.monthname            AS month_label,

                -- INCOME (cat=4): CR - DR = net income movement for the period
                SUM(CASE WHEN cat.idtbl_account_category = 4
                        THEN (t.accamount * (t.crdr = 'C')) - (t.accamount * (t.crdr = 'D'))
                        ELSE 0 END) AS money_in,

                -- EXPENSE (cat=2): DR - CR = net expense movement for the period
                SUM(CASE WHEN cat.idtbl_account_category = 2
                        THEN (t.accamount * (t.crdr = 'D')) - (t.accamount * (t.crdr = 'C'))
                        ELSE 0 END) AS money_out

            FROM tbl_account_transaction t
            INNER JOIN tbl_account a
                ON t.tbl_account_idtbl_account = a.idtbl_account
            INNER JOIN tbl_account_category cat
                ON a.tbl_account_category_idtbl_account_category = cat.idtbl_account_category
            INNER JOIN tbl_account_allocation al
                ON a.idtbl_account = al.tbl_account_idtbl_account
            INNER JOIN tbl_master m
                ON t.tbl_master_idtbl_master = m.idtbl_master
            INNER JOIN tbl_finacial_year fy
                ON m.tbl_finacial_year_idtbl_finacial_year = fy.idtbl_finacial_year
            INNER JOIN tbl_finacial_month fm
                ON m.tbl_finacial_month_idtbl_finacial_month = fm.idtbl_finacial_month

            WHERE a.status = 1
            AND   cat.idtbl_account_category IN (2, 4)   -- EX=2, IN=4 only
            AND   al.companybank = ?
            AND   al.branchcompanybank = ?
            AND   t.reversstatus = 0
            AND   t.tbl_master_idtbl_master IN ($in_placeholders)

            GROUP BY m.idtbl_master, fy.startdate, fm.idtbl_finacial_month, fm.monthname
            ORDER BY fy.startdate ASC, fm.idtbl_finacial_month ASC
        ";

        $params = array_merge([$company, $branch], $master_ids);

        $respond = $this->db->query($sql, $params)->result();

        $labels        = [];
        $moneyIn       = [];
        $moneyOut      = [];
        $totalMoneyIn  = 0;
        $totalMoneyOut = 0;

        foreach ($respond as $row) {
            $labels[]   = strtoupper(substr($row->month_label, 0, 3)); // JAN, FEB, MAR...
            $moneyIn[]  = (float)$row->money_in;
            $moneyOut[] = (float)$row->money_out;
            $totalMoneyIn  += (float)$row->money_in;
            $totalMoneyOut += (float)$row->money_out;
        }

        $totalCurrenBal = $totalMoneyIn - $totalMoneyOut;
        $totalCurrenBal = $totalCurrenBal < 0
            ? '(' . number_format(abs($totalCurrenBal), 2) . ')'
            : number_format($totalCurrenBal, 2);

        return [
            'labels'         => $labels,
            'moneyIn'        => $moneyIn,
            'moneyOut'       => $moneyOut,
            'totalCurrenBal' => $totalCurrenBal
        ];
    }
    public function getExpencesData(){
        $company=$this->session->userdata('companyid');
        $branch=$this->session->userdata('branchid');

        $sql = "SELECT 
            a.accountname AS expense_label,
            SUM(t.accamount) AS total_amount
        FROM tbl_account_transaction_full t
        INNER JOIN tbl_account a ON t.tbl_account_idtbl_account = a.idtbl_account
        INNER JOIN tbl_account_allocation al ON a.idtbl_account = al.tbl_account_idtbl_account
        WHERE a.tbl_account_category_idtbl_account_category = 2  -- Your Expense Category
        AND al.companybank = ? 
        AND al.branchcompanybank = ?
        AND t.crdr = ?       -- Filtering for Debits (Expenses/Money Out)
        AND t.status = ?         -- Active transactions only
        GROUP BY a.idtbl_account, a.accountname
        ORDER BY total_amount DESC LIMIT 10";
        $respond = $this->db->query($sql, array($company, $branch, 'D', 1))->result();
        
        $labels = [];
        $amounts = [];
        $totalExpenses = 0;

        foreach ($respond as $row) {
            $labels[] = $row->expense_label;
            $amounts[] = (float)$row->total_amount;

            $totalExpenses += (float)$row->total_amount;
        }

        $dataForExpencesChart = [
            'labels' => $labels,
            'amounts' => $amounts,
            'totalExpenses' => $totalExpenses
        ];

        return $dataForExpencesChart;
    }
    public function getSalesIncomeData(){
        $company=$this->session->userdata('companyid');
        $branch=$this->session->userdata('branchid');

        $sql = "SELECT 
            -- 1 for Income (specialcate=1), 2 for Expenses (category=2)
            a.tbl_account_category_idtbl_account_category AS cat_id,
            SUM(CASE WHEN t.ismatch = 1 THEN t.accamount ELSE 0 END) AS reconciled_amt,
            SUM(CASE WHEN t.ismatch = 0 THEN t.accamount ELSE 0 END) AS pending_amt,
            COUNT(CASE WHEN t.ismatch = 0 THEN 1 END) AS pending_count
        FROM tbl_account_transaction_full t
        INNER JOIN tbl_account a ON t.tbl_account_idtbl_account = a.idtbl_account
        INNER JOIN tbl_account_allocation al ON a.idtbl_account = al.tbl_account_idtbl_account
        WHERE al.companybank = ? 
        AND al.branchcompanybank = ?
        AND t.status = ?
        AND a.tbl_account_category_idtbl_account_category IN (4, 2) -- Income & Expense
        GROUP BY a.tbl_account_category_idtbl_account_category;";
        $respond = $this->db->query($sql, array($company, $branch, 1))->result();
        // print_r($this->db->last_query());   
        // die();
        // Initialize defaults
        $incomeData = ['total' => 0, 'solid' => 0, 'hatched' => 0, 'count' => 0];
        $expenseData = ['total' => 0, 'solid' => 0, 'hatched' => 0, 'count' => 0];

        foreach ($respond as $row) {
            $total = $row->reconciled_amt + $row->pending_amt;
            if ($total == 0) continue;

            // Calculate percentages for the bar widths
            $solid_pct = ($row->reconciled_amt / $total) * 100;
            $hatched_pct = ($row->pending_amt / $total) * 100;

            if ($row->cat_id == 1) { // Income
                $incomeData = [
                    'total' => number_format($total, 2),
                    'solid' => $solid_pct,
                    'hatched' => $hatched_pct,
                    'count' => $row->pending_count
                ];
            } else { // Expense
                $expenseData = [
                    'total' => number_format($total, 2),
                    'solid' => $solid_pct,
                    'hatched' => $hatched_pct,
                    'count' => $row->pending_count
                ];
            }
        }

        $dataForSalesIncomeChart = [
            'income' => $incomeData,
            'expense' => $expenseData
        ];

        return $dataForSalesIncomeChart;
    }
    public function getInvoiceSalesData(){
        $company=$this->session->userdata('companyid');
        $branch=$this->session->userdata('branchid');

        $sql = "SELECT 
            DATE_FORMAT(invdate, '%b') AS month_label, 
            SUM(invamount) AS total_invoice_amount
        FROM tbl_sales_info
        WHERE saletype = 'INV' 
        AND status = ?
        AND tbl_company_idtbl_company = ?
        AND tbl_company_branch_idtbl_company_branch = ?
        AND invdate >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) -- Gets the last 6 months
        GROUP BY MONTH(invdate), month_label
        ORDER BY MONTH(invdate) ASC";
        $respond = $this->db->query($sql, array(1, $company, $branch))->result();

        $labels = [];
        $amounts = [];
        $totalInvoiceAmount = 0;

        foreach ($respond as $row) {
            $labels[] = strtoupper($row->month_label);
            $amounts[] = (float)$row->total_invoice_amount;
            $totalInvoiceAmount += (float)$row->total_invoice_amount;
        }

        $dataForInvoiceSalesChart = [
            'labels' => $labels,
            'amounts' => $amounts,
            'total' => $totalInvoiceAmount
        ];

        return $dataForInvoiceSalesChart;
    }

    public function getAllMasters($company_id, $branch_id) {
        return $this->db->select('m.idtbl_master, fy.year, fm.monthname, m.insertdatetime')
                        ->from('tbl_master m')
                        ->join('tbl_finacial_year fy', 'm.tbl_finacial_year_idtbl_finacial_year = fy.idtbl_finacial_year')
                        ->join('tbl_finacial_month fm', 'm.tbl_finacial_month_idtbl_finacial_month = fm.idtbl_finacial_month')
                        ->where('m.tbl_company_idtbl_company', $company_id)
                        ->where('m.tbl_company_branch_idtbl_company_branch', $branch_id)
                        ->where('m.status', 1)
                        ->order_by('m.insertdatetime', 'ASC')
                        ->get()
                        ->result_array();
    }
}