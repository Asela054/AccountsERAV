<?php
class Receivablesettleinfo extends CI_Model{
    public function Getreceivabletype(){
        $this->db->select('idtbl_receivable_type, receivabletype');
        $this->db->from('tbl_receivable_type');
        $this->db->where('status', 1);
        $this->db->where('idtbl_receivable_type !=', 5);

        $respond=$this->db->get();

        return $respond;
    }
    public function Getinvoiceaccocustomer(){
        $recordID=$this->input->post('recordID');
        $receivablefilter=$this->input->post('receivablefilter');
        $accounttype=$this->input->post('accounttype');

        $configdata = getconfigdata('receivable_search');

		$tablename = $configdata->row(0)->tbl_name;
		$column1   = $configdata->row(0)->col_name;
		$column2   = $configdata->row(1)->col_name;

        $has_table = !empty($tablename) ? 1 : 0;

        if($receivablefilter == 1):
            // $this->db->select('`tbl_sales_info`.`idtbl_sales_info`, `tbl_sales_info`.`invno`, `tbl_sales_info`.`amount`, IFNULL(SUM(`tbl_receivable_info`.`amount`), 0) AS `sumpay`, (`tbl_sales_info`.`amount`-IFNULL(SUM(`tbl_receivable_info`.`amount`), 0)) AS `balpay`, `tbl_sales_info`.`tbl_customer_idtbl_customer`, `tbl_customer`.`customer`');
            $this->db->select("`tbl_sales_info`.`idtbl_sales_info`, CASE WHEN `tbl_sales_info`.`invdate` > '2026-06-30' THEN `tbl_sales_info`.`tax_invno` ELSE `tbl_sales_info`.`invno` END AS `invno`, `tbl_sales_info`.`invamount`, IFNULL(SUM(CASE WHEN `tbl_receivable_info`.`status` = 1 THEN `tbl_receivable_info`.`amount` ELSE 0 END), 0) AS `sumpay`, (`tbl_sales_info`.`invamount`-IFNULL(SUM(CASE WHEN `tbl_receivable_info`.`status` = 1 THEN `tbl_receivable_info`.`amount` ELSE 0 END), 0)) AS `balpay`, `tbl_sales_info`.`tbl_customer_idtbl_customer`, IF($has_table = 0, '', $tablename.$column2) AS `customer`");
            $this->db->from('tbl_sales_info');
            $this->db->join('tbl_receivable', 'tbl_receivable.payer = tbl_sales_info.tbl_customer_idtbl_customer', 'left');
            $this->db->join('tbl_receivable_info', 'tbl_receivable_info.invoiceno = tbl_sales_info.invno AND tbl_receivable_info.tbl_receivable_idtbl_receivable = tbl_receivable.idtbl_receivable', 'left');
            if(!empty($tablename)):
                $this->db->join("$tablename", "$tablename.$column1 = tbl_sales_info.tbl_customer_idtbl_customer", 'left');
            endif;
            $this->db->where('tbl_sales_info.status', 1);
            $this->db->where('tbl_sales_info.paystatus', 0);
            $this->db->where('tbl_sales_info.poststatus', 1);
            $this->db->where('tbl_sales_info.tbl_customer_idtbl_customer', $recordID);
            $this->db->group_by('`tbl_sales_info`.`idtbl_sales_info`');

            $respond=$this->db->get();
            // print_r($this->db->last_query());

            $html='';
            $i=1;
            foreach($respond->result() as $rowdatalist){
                $this->db->select('IFNULL(SUM(`amount`), 0) AS `returnsum`');
                $this->db->from('tbl_receivable_info');
                $this->db->where('status', 2);
                $this->db->where('invoiceno', $rowdatalist->invno);

                $respondreturn=$this->db->get();
                
                // $netbalpay=$rowdatalist->balpay+$respondreturn->row(0)->returnsum;
                $netbalpay=$rowdatalist->balpay;
                
                if($netbalpay>0){
                    $html.='
                    <tr>
                        <td class="text-center" width="5%">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input checkclick" id="customCheck'.$i.'">
                                <label class="custom-control-label m-0" for="customCheck'.$i.'"></label>
                            </div>
                        </td>
                        <td class="d-none">'.$rowdatalist->tbl_customer_idtbl_customer.'</td>
                        <td>'.$rowdatalist->customer.'</td>
                        <td class="d-none">'.$rowdatalist->invno.'</td>
                        <td>'.$rowdatalist->invno.'</td>
                        <td class="text-right">'.number_format($rowdatalist->invamount, 2).'</td>
                        <td class="text-right invbalamount">'.number_format($netbalpay, 2).'</td>
                    </tr>
                    ';
                    $i++;
                }
            }
        else:
            $this->db->select('`tbl_account_transaction_manual`.`idtbl_account_transaction_manual`, `tbl_account_transaction_manual`.`batchno`, `tbl_account_transaction_manual`.`amount`, IFNULL(SUM(CASE WHEN `tbl_receivable_entry`.`status` = 1 THEN `tbl_receivable_entry`.`amount` ELSE 0 END), 0) AS `sumpay`, (`tbl_account_transaction_manual`.`amount`-IFNULL(SUM(CASE WHEN `tbl_receivable_entry`.`status` = 1 THEN `tbl_receivable_entry`.`amount` ELSE 0 END), 0)) AS `balpay`, `tbl_account_transaction_manual`.`narration`, `tbl_account_transaction_manual`.`tbl_account_idtbl_account`, `tbl_account_transaction_manual`.`tbl_account_detail_idtbl_account_detail`, `tbl_account`.`accountno` AS `chartaccountno`, `tbl_account.accountname` AS `chartaccountname`, `tbl_account_detail.accountno` AS `detailaccountno`, `tbl_account_detail.accountname` AS `detailaccountname`');
            $this->db->from('tbl_account_transaction_manual');
            if($accounttype == 1){
                $this->db->join('tbl_receivable_entry', '`tbl_receivable_entry`.`batchno` = `tbl_account_transaction_manual`.`batchno` AND tbl_receivable_entry.tbl_account_idtbl_account = tbl_account_transaction_manual.tbl_account_idtbl_account', 'left');
            } else if($accounttype == 2){
                $this->db->join('tbl_receivable_entry', '`tbl_receivable_entry`.`batchno` = `tbl_account_transaction_manual`.`batchno` AND tbl_receivable_entry.tbl_account_detail_idtbl_account_detail = tbl_account_transaction_manual.tbl_account_detail_idtbl_account_detail', 'left');
            }
            $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_account_transaction_manual.tbl_account_idtbl_account', 'left');
            $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_account_transaction_manual.tbl_account_detail_idtbl_account_detail', 'left');
            $this->db->where('tbl_account_transaction_manual.status', 1);
            $this->db->where('tbl_account_transaction_manual.recestatus', 1);
            $this->db->where('tbl_account_transaction_manual.recesettle', 0);
            $this->db->where('tbl_account_transaction_manual.poststatus', 1);
            if($accounttype == 1){
                $this->db->where('tbl_account_transaction_manual.tbl_account_idtbl_account', $recordID);
            }
            else if($accounttype == 2){
                $this->db->where('tbl_account_transaction_manual.tbl_account_detail_idtbl_account_detail', $recordID);
            }
            $this->db->group_by('`tbl_account_transaction_manual`.`idtbl_account_transaction_manual`');

            if($accounttype == 1){
                $this->db->group_by('`tbl_account_transaction_manual`.`tbl_account_idtbl_account`');
            }
            else if($accounttype == 2){
                $this->db->group_by('`tbl_account_transaction_manual`.`tbl_account_detail_idtbl_account_detail`');
            }

            $respond=$this->db->get();
            
            if($respond->row(0)->tbl_account_detail_idtbl_account_detail>0){
                $accountno=$respond->row(0)->detailaccountno; 
                $accountname=$respond->row(0)->detailaccountname;
            }
            else{
                $accountno=$respond->row(0)->chartaccountno; 
                $accountname=$respond->row(0)->chartaccountname;
            }

            $html='';
            $i=1;
            foreach($respond->result() as $rowdatalist){
                $this->db->select('IFNULL(SUM(`amount`), 0) AS `returnsum`');
                $this->db->from('tbl_receivable_info');
                $this->db->where('status', 1);
                $this->db->where('invoiceno', $rowdatalist->batchno);
                $this->db->where('tbl_account_transaction_manual_idtbl_account_transaction_manual', $rowdatalist->idtbl_account_transaction_manual);

                $respondreturn=$this->db->get();
                
                $netbalpay=$rowdatalist->balpay+$respondreturn->row(0)->returnsum;

                if($netbalpay>0){
                    $html.='
                    <tr>
                        <td class="text-center" width="5%">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input checkclick" id="customCheck'.$i.'">
                                <label class="custom-control-label m-0" for="customCheck'.$i.'"></label>
                            </div>
                        </td>
                        <td class="d-none">'.$rowdatalist->idtbl_account_transaction_manual.'</td>
                        <td>'.$accountno.' - '.$accountname.'</td>
                        <td class="d-none">'.$rowdatalist->batchno.'</td>
                        <td>'.$rowdatalist->batchno.'</td>
                        <td class="text-right">'.number_format($rowdatalist->amount, 2).'</td>
                        <td class="text-right invbalamount">'.number_format($netbalpay, 2).'</td>
                    </tr>
                    ';
                    $i++;
                }
            }   
        endif;

        echo $html;
    }
    public function Receivablesettleinsertupdate() {
        try {
            $userID        = $_SESSION['userid'];
            $detailaccount = 0;
            $chartaccount  = 0;

            $company                = $this->input->post('company');
            $branch                 = $this->input->post('branch');
            $recsettdate            = $this->input->post('recsettdate');
            $customer               = $this->input->post('customer');
            $invoicepayamount       = str_replace(',', '', $this->input->post('invoicepayamount'));
            $paidamount             = str_replace(',', '', $this->input->post('paidamount'));
            $unappliedamount        = str_replace(',', '', $this->input->post('unappliedamount'));
            $creditnotetotal        = str_replace(',', '', $this->input->post('creditnotetotal'));
            $invoicedata            = json_decode($this->input->post('tableData'));
            $paymentdata            = json_decode($this->input->post('tableReceData'));
            $unapplydata            = json_decode($this->input->post('tableUnapplyData'));
            $creditnotedata         = json_decode($this->input->post('tableCreditNoteData'));
            $voucherdata            = json_decode($this->input->post('tableVoucherData'));
            $receivablefilter       = $this->input->post('receivablefilter');
            $customerAccountType    = $this->input->post('customerAccountType');
            $recordOption           = $this->input->post('recordOption');
            $recordID               = !empty($this->input->post('recordID')) ? $this->input->post('recordID') : '';

            $updatedatetime = date('Y-m-d H:i:s');

            if ($recordOption != 1) {
                throw new Exception('Invalid record option');
            }

            // ── Resolve master, batch, receipt no ────────────────────────────────
            $masterdata = get_account_period_acco_date($company, $branch, $recsettdate);

            if (empty($masterdata) || empty($masterdata->idtbl_master)) {
                throw new Exception('Record Error, Account period not found for the given date');
            }
            
            $masterID = $masterdata->idtbl_master ? $masterdata->idtbl_master : '';

            $this->db->select('tbl_finacial_year.year');
            $this->db->from('tbl_master');
            $this->db->join('tbl_finacial_year', 'tbl_finacial_year.idtbl_finacial_year = tbl_master.tbl_finacial_year_idtbl_finacial_year', 'left');
            $this->db->where('tbl_master.idtbl_master', $masterID);
            $respondyear = $this->db->get();

            if (!$respondyear || $respondyear->num_rows() == 0) {
                throw new Exception('Record Error, Financial year not found');
            }

            $financialYear = substr($respondyear->row(0)->year, -2);
            $receiptno     = tr_batch_num('REC' . $financialYear, $branch);
            $receiptno     = preg_replace('/^(.{5})00/', '$1', $receiptno);

            $this->db->trans_begin();

            // ── Get Trade Debtor Account ──────────────────────────────────────────
            $this->db->where('tbl_account_allocation.companybank', $company);
            $this->db->where('tbl_account_allocation.branchcompanybank', $branch);
            $this->db->where('tbl_account.specialcate', 35);
            $this->db->where('tbl_account.status', 1);
            $this->db->where('tbl_account_allocation.status', 1);
            $this->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
            $this->db->select('`tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`');
            $this->db->from('tbl_account');
            $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');
            $respondcreditor = $this->db->get();

            if($receivablefilter == 1){
                $this->db->select('tbl_account_detail_idtbl_account_detail as accountid');
                $this->db->from('tbl_account_detail_other');
                $this->db->where('tbl_company_idtbl_company', $company);
                $this->db->where('tbl_company_branch_idtbl_company_branch', $branch);
                $this->db->where('otheroptiontype', 2);
                $this->db->where('otheroption', $customer);
                $respondcreditacc = $this->db->get();
            }
            else if($receivablefilter == 2 || $receivablefilter == 3){
                if ($customerAccountType == 1) {
                    $this->db->select('idtbl_account as accountid');
                    $this->db->from('tbl_account');
                    $this->db->where('idtbl_account', $customer);
                    $this->db->where('status', 1);
                    $respondcreditacc = $this->db->get();
                } else if ($customerAccountType == 2) {
                    $this->db->select('idtbl_account_detail as accountid');
                    $this->db->from('tbl_account_detail');
                    $this->db->where('idtbl_account_detail', $customer);
                    $this->db->where('status', 1);
                    $respondcreditacc = $this->db->get();
                }
            }

            // ─────────────────────────────────────────────────────────────────────
            // STEP 1: Insert receivable payment records
            // ─────────────────────────────────────────────────────────────────────
            $receivableIDs   = [];
            $paymentnettotal = 0;
            $dataentrylist   = [];

            if (!empty($paymentdata)) {
                foreach ($paymentdata as $rowpaymentdata) {
                    if ($rowpaymentdata->accounttype == 1) {
                        $chartaccount  = $rowpaymentdata->chartofaccount;
                        $detailaccount = 0;
                    } elseif ($rowpaymentdata->accounttype == 2) {
                        $detailaccount = $rowpaymentdata->chartofaccount;
                        $chartaccount  = 0;
                    }

                    if($rowpaymentdata->postdatedstatus == 1 && !empty($rowpaymentdata->chequedate)){
                        $recsettdate = $rowpaymentdata->chequedate;

                        $masterdata = get_account_period_acco_date($company, $branch, $recsettdate);

                        if (empty($masterdata) || empty($masterdata->idtbl_master)) {
                            throw new Exception('Record Error, Account period not found for the given date in some postdated cheque');
                        }

                        $masterID = $masterdata->idtbl_master ? $masterdata->idtbl_master : '';
                    }

                    $prefix   = generate_prefix($company, $branch, $recsettdate, 'RE');
                    $batchno  = tr_batch_num($prefix, $branch);

                    if (empty($batchno)) {
                        throw new Exception('Batch no could not be defined by system');
                    }

                    $paymentAmount = floatval(str_replace(',', '', $rowpaymentdata->amount));

                    $data = [
                        'recdate'                                   => $recsettdate,
                        'receiptno'                                 => $receiptno,
                        'batchno'                                   => $batchno,
                        'payer'                                     => $receivablefilter == 1 ? $customer : '',
                        'amount'                                    => $paymentAmount,
                        'narration'                                 => $rowpaymentdata->narration,
                        'chequedate'                                => $rowpaymentdata->chequedate,
                        'chequeno'                                  => $rowpaymentdata->chequeno,
                        'postdatedstatus'                           => $rowpaymentdata->postdatedstatus,
                        'poststatus'                                => '0',
                        'recsettlefiltertype'                       => $receivablefilter,
                        'status'                                    => '1',
                        'insertdatetime'                            => $updatedatetime,
                        'tbl_user_idtbl_user'                       => $userID,
                        'tbl_receivable_type_idtbl_receivable_type' => $rowpaymentdata->receivabletypeid,
                        'tbl_company_idtbl_company'                 => $company,
                        'tbl_company_branch_idtbl_company_branch'   => $branch,
                        'tbl_master_idtbl_master'                   => $masterID,
                        'tbl_account_idtbl_account'                 => $chartaccount,
                        'tbl_account_detail_idtbl_account_detail'   => $detailaccount,
                    ];

                    $this->db->insert('tbl_receivable', $data);
                    $receivableID = $this->db->insert_id();

                    if (!$receivableID) {
                        throw new Exception('Failed to insert receivable record');
                    }

                    // Debit: Bank/Cash account
                    $dataentrylist[] = [
                        'tratype'       => 'D',
                        'amount'        => $paymentAmount,
                        'narration'     => $rowpaymentdata->narration,
                        'chartaccount'  => $chartaccount,
                        'detailaccount' => $detailaccount,
                        'batchno'       => $batchno,
                        'recdate'       => $recsettdate,
                    ];

                    // Credit: Trade Debtor account
                    if ($receivablefilter == 3) {
                        foreach($voucherdata as $rowvoucherdata){
                            $dataentrylist[] = [
                                'tratype'       => 'C',
                                'amount'        => str_replace(',', '', $rowvoucherdata->amount),
                                'narration'     => $rowvoucherdata->desc,
                                'chartaccount'  => $rowvoucherdata->accounttype == 1 ? $rowvoucherdata->accountid : 0,
                                'detailaccount' => $rowvoucherdata->accounttype == 2 ? $rowvoucherdata->accountid : 0,
                                'batchno'       => $batchno,
                                'recdate'       => $recsettdate,
                            ];
                        }
                    }
                    else{
                        if (empty($respondcreditacc->result())) {
                            if (empty($respondcreditor->result())) {
                                throw new Exception("You don't have trade debtor account or debtor account");
                            }
                            $dataentrylist[] = [
                                'tratype'       => 'C',
                                'amount'        => $paymentAmount,
                                'narration'     => $rowpaymentdata->narration,
                                'chartaccount'  => $respondcreditor->row(0)->idtbl_account,
                                'detailaccount' => 0,
                                'batchno'       => $batchno,
                                'recdate'       => $recsettdate,
                            ];
                        } else {
                            $dataentrylist[] = [
                                'tratype'       => 'C',
                                'amount'        => $paymentAmount,
                                'narration'     => $rowpaymentdata->narration,
                                'chartaccount'  => 0,
                                'detailaccount' => $respondcreditacc->row(0)->accountid,
                                'batchno'       => $batchno,
                                'recdate'       => $recsettdate,
                            ];
                        }
                    }

                    $receivableIDs[] = [
                        'id'     => $receivableID,
                        'amount' => $paymentAmount,
                        'data'   => $rowpaymentdata
                    ];

                    $paymentnettotal += $paymentAmount;
                }

            } else {
                $prefix   = generate_prefix($company, $branch, $recsettdate, 'RE');
                $batchno  = tr_batch_num($prefix, $branch);

                if (empty($batchno)) {
                    throw new Exception('Batch no could not be defined by system');
                }
                // ── No cash/bank payment — settle via Unapplied / Credit Note / Both ──
                $cnOnlyTotal        = floatval(str_replace(',', '', $creditnotetotal));
                $unappliedOnlyTotal = floatval(str_replace(',', '', $unappliedamount));
                $paymentAmount      = round($cnOnlyTotal + $unappliedOnlyTotal, 2);

                if ($cnOnlyTotal > 0 && $unappliedOnlyTotal > 0) {
                    $settleNarration = 'Settlement via Credit Note and Unapplied Amount';
                } elseif ($cnOnlyTotal > 0) {
                    $settleNarration = 'Settlement via Credit Note';
                } else {
                    $settleNarration = 'Settlement via Unapplied Amount';
                }

                if ($paymentAmount <= 0) {
                    throw new Exception('No valid settlement amount found (credit note or unapplied amount required).');
                }

                $this->db->insert('tbl_receivable', [
                    'recdate'                                   => $recsettdate,
                    'receiptno'                                 => $receiptno,
                    'batchno'                                   => $batchno,
                    'payer'                                     => $customer,
                    'amount'                                    => $paymentAmount,
                    'narration'                                 => $settleNarration,
                    'postdatedstatus'                           => '0',
                    'poststatus'                                => '1',
                    'recsettlefiltertype'                       => $receivablefilter,
                    'status'                                    => '1',
                    'insertdatetime'                            => $updatedatetime,
                    'tbl_user_idtbl_user'                       => $userID,
                    'tbl_receivable_type_idtbl_receivable_type' => '5',
                    'tbl_company_idtbl_company'                 => $company,
                    'tbl_company_branch_idtbl_company_branch'   => $branch,
                    'tbl_master_idtbl_master'                   => $masterID,
                    'tbl_account_idtbl_account'                 => 0,
                    'tbl_account_detail_idtbl_account_detail'   => 0,
                ]);

                $receivableID = $this->db->insert_id();

                if (!$receivableID) {
                    throw new Exception('Failed to insert receivable settlement header record');
                }

                $receivableIDs[] = ['id' => $receivableID, 'amount' => $paymentAmount, 'data' => ''];
                $paymentnettotal += $paymentAmount;
            }
            
            // // ── Insert accounting entry lines ─────────────────────────────────────
            // foreach ($dataentrylist as $rowdataentrylist) {
            //     $datalist = [
            //         'transdate'                               => $recsettdate,
            //         'batchno'                                 => $batchno,
            //         'tratype'                                 => $rowdataentrylist['tratype'],
            //         'amount'                                  => $rowdataentrylist['amount'],
            //         'narration'                               => $rowdataentrylist['narration'],
            //         'poststatus'                              => '0',
            //         'status'                                  => '1',
            //         'insertdatetime'                          => $updatedatetime,
            //         'tbl_user_idtbl_user'                     => $userID,
            //         'tbl_company_idtbl_company'               => $company,
            //         'tbl_company_branch_idtbl_company_branch' => $branch,
            //         'tbl_master_idtbl_master'                 => $masterID,
            //         'tbl_receivable_idtbl_receivable'         => $receivableID,
            //         'tbl_account_idtbl_account'               => $rowdataentrylist['chartaccount'],
            //         'tbl_account_detail_idtbl_account_detail' => $rowdataentrylist['detailaccount']
            //     ];
            //     $this->db->insert('tbl_receivable_entry', $datalist);
            // }

            // ── Insert accounting entry lines ─────────────────────────────────────
            // dataentrylist has pairs: [Debit, Credit] per receivable (2 entries per payment)
            // receivableIDs has one entry per payment — match them by index pair

            $entryIndex = 0;
            foreach ($receivableIDs as $receivable) {
                $currentReceivableID = $receivable['id'];

                // Each payment generates exactly 2 entries (Debit + Credit)
                for ($i = 0; $i < 2; $i++) {
                    if (!isset($dataentrylist[$entryIndex])) break;

                    $rowdataentrylist = $dataentrylist[$entryIndex];

                    $datalist = [
                        'transdate'                               => $rowdataentrylist['recdate'],
                        'batchno'                                 => $rowdataentrylist['batchno'],
                        'tratype'                                 => $rowdataentrylist['tratype'],
                        'amount'                                  => $rowdataentrylist['amount'],
                        'narration'                               => $rowdataentrylist['narration'],
                        'poststatus'                              => '0',
                        'status'                                  => '1',
                        'insertdatetime'                          => $updatedatetime,
                        'tbl_user_idtbl_user'                     => $userID,
                        'tbl_company_idtbl_company'               => $company,
                        'tbl_company_branch_idtbl_company_branch' => $branch,
                        'tbl_master_idtbl_master'                 => $masterID,
                        'tbl_receivable_idtbl_receivable'         => $currentReceivableID,
                        'tbl_account_idtbl_account'               => $rowdataentrylist['chartaccount'],
                        'tbl_account_detail_idtbl_account_detail' => $rowdataentrylist['detailaccount']
                    ];

                    $this->db->insert('tbl_receivable_entry', $datalist);
                    $entryIndex++;
                }
            }

            // ── Build working invoice list ────────────────────────────────────────
            $invoicePayments = [];
            foreach ($invoicedata as $invoice) {
                $invoicePayments[] = [
                    'invoice'     => $invoice,
                    'remaining'   => floatval(str_replace(',', '', $invoice->amount)),
                    'invoice_no'  => $invoice->invoiceno,
                    'invoice_id'  => $invoice->invid,
                    'journal_id'  => ($receivablefilter == 2 && !empty($invoice->cusid)) ? intval($invoice->cusid) : 0,
                    'paid_amount' => 0
                ];
            }

            $totalUnappliedUsed  = 0;
            $totalCreditNoteUsed = 0;

            // ─────────────────────────────────────────────────────────────────────
            // STEP 2: Apply credit notes to invoices
            // ─────────────────────────────────────────────────────────────────────
            if (!empty($creditnotedata) && !empty($receivableIDs)) {
                $targetReceivableID = $receivableIDs[0]['id'];

                foreach ($creditnotedata as $rowcn) {
                    $creditNoteID  = intval($rowcn->creditnoteid);
                    $cnGrossAmount = floatval(str_replace(',', '', $rowcn->amount));
                    $cnNarration   = 'Credit Note: ' . $rowcn->creditnoteno;

                    if ($cnGrossAmount <= 0 || $creditNoteID <= 0) {
                        continue;
                    }

                    // ── Validate credit note ──────────────────────────────────────
                    $this->db->select('idtbl_account_creditnote, creditnoteno, nettotal, claimstatus, settleamount');
                    $this->db->from('tbl_account_creditnote');
                    $this->db->where('idtbl_account_creditnote', $creditNoteID);
                    $this->db->where('status', 1);
                    $cnRow = $this->db->get()->row();

                    if (!$cnRow) {
                        throw new Exception("Credit Note ID {$creditNoteID} not found or inactive.");
                    }
                    if ($cnRow->claimstatus == 1) {
                        throw new Exception("Credit Note '{$cnRow->creditnoteno}' is already fully claimed.");
                    }

                    // ── Apply to invoices ─────────────────────────────────────────
                    $remainingCN = $cnGrossAmount;

                    foreach ($invoicePayments as $index => &$invoicePayment) {
                        if ($invoicePayment['remaining'] <= 0 || $remainingCN <= 0) {
                            continue;
                        }

                        $invoiceRemaining = $invoicePayment['remaining'];
                        $isLastInvoice    = true;

                        for ($i = $index + 1; $i < count($invoicePayments); $i++) {
                            if ($invoicePayments[$i]['remaining'] > 0) { $isLastInvoice = false; break; }
                        }

                        if ($remainingCN >= $invoiceRemaining) {
                            if ($isLastInvoice && $remainingCN > $invoiceRemaining) {
                                $this->db->insert('tbl_receivable_info', [
                                    'invoiceno'                       => $invoicePayment['invoice_id'],
                                    'narration'                       => $cnNarration,
                                    'amount'                          => $invoiceRemaining,
                                    'overpayment'                     => $remainingCN - $invoiceRemaining,
                                    'overpaysetoff'                   => 0,
                                    'setoff_receivable_info_id'       => 0,
                                    'creditnote_id'                   => $creditNoteID,
                                    'status'                          => '1',
                                    'insertdatetime'                  => $updatedatetime,
                                    'tbl_user_idtbl_user'             => $userID,
                                    'tbl_receivable_idtbl_receivable' => $targetReceivableID,
                                ]);
                                $invoicePayment['remaining']   = 0;
                                $invoicePayment['paid_amount'] += $invoiceRemaining;
                                $remainingCN = 0;
                            } else {
                                $this->db->insert('tbl_receivable_info', [
                                    'invoiceno'                       => $invoicePayment['invoice_id'],
                                    'narration'                       => $cnNarration,
                                    'amount'                          => $invoiceRemaining,
                                    'overpayment'                     => 0,
                                    'overpaysetoff'                   => 0,
                                    'setoff_receivable_info_id'       => 0,
                                    'creditnote_id'                   => $creditNoteID,
                                    'status'                          => '1',
                                    'insertdatetime'                  => $updatedatetime,
                                    'tbl_user_idtbl_user'             => $userID,
                                    'tbl_receivable_idtbl_receivable' => $targetReceivableID,
                                ]);
                                $invoicePayment['remaining']   = 0;
                                $invoicePayment['paid_amount'] += $invoiceRemaining;
                                $remainingCN -= $invoiceRemaining;
                            }
                        } else {
                            $this->db->insert('tbl_receivable_info', [
                                'invoiceno'                       => $invoicePayment['invoice_id'],
                                'narration'                       => $cnNarration,
                                'amount'                          => $remainingCN,
                                'overpayment'                     => 0,
                                'overpaysetoff'                   => 0,
                                'setoff_receivable_info_id'       => 0,
                                'creditnote_id'                   => $creditNoteID,
                                'status'                          => '1',
                                'insertdatetime'                  => $updatedatetime,
                                'tbl_user_idtbl_user'             => $userID,
                                'tbl_receivable_idtbl_receivable' => $targetReceivableID,
                            ]);
                            $invoicePayment['remaining']   -= $remainingCN;
                            $invoicePayment['paid_amount'] += $remainingCN;
                            $remainingCN = 0;
                        }

                        if ($remainingCN <= 0) break;
                    }
                    unset($invoicePayment);

                    $totalCreditNoteUsed += $cnGrossAmount;

                    // ── Insert credit note settle record ──────────────────────────
                    $this->db->insert('tbl_account_creditnote_settle', [
                        'date'                                            => $recsettdate,
                        'settlenettotal'                                  => $cnGrossAmount,
                        'status'                                          => '1',
                        'insertdatetime'                                  => $updatedatetime,
                        'updateuser'                                      => $userID,
                        'updatedatetime'                                  => $updatedatetime,
                        'tbl_user_idtbl_user'                             => $userID,
                        'tbl_account_creditnote_idtbl_account_creditnote' => $creditNoteID,
                        'tbl_receivable_idtbl_receivable'                 => $targetReceivableID,
                    ]);

                    // ── Update credit note settle totals ──────────────────────────
                    $newSettleGross = round(floatval($cnRow->settleamount) + $cnGrossAmount, 2);
                    $newClaimStatus = ($newSettleGross >= floatval($cnRow->nettotal)) ? 1 : 0;

                    $updateCN = [
                        'settleamount'                    => $newSettleGross,
                        'claimstatus'                     => $newClaimStatus,
                        'updateuser'                      => $userID,
                        'updatedatetime'                  => $updatedatetime,
                        'tbl_receivable_idtbl_receivable' => $targetReceivableID,
                    ];

                    if ($newClaimStatus == 1) {
                        $updateCN['claimdate'] = $updatedatetime;
                    }

                    $this->db->where('idtbl_account_creditnote', $creditNoteID);
                    $this->db->update('tbl_account_creditnote', $updateCN);
                }
            }

            // ─────────────────────────────────────────────────────────────────────
            // STEP 3: Apply unapplied amounts from previous overpayments
            // ─────────────────────────────────────────────────────────────────────
            $totalOverpaymentFromCurrent = 0;

            if (!empty($unapplydata) && !empty($receivableIDs)) {
                $targetReceivableID = $receivableIDs[0]['id'];

                foreach ($unapplydata as $unapplied) {
                    $sourceInfoID  = intval($unapplied->receivableinfoid);
                    $unappliedPool = floatval(str_replace(',', '', $unapplied->unappliedamount));

                    if ($unappliedPool <= 0 || $sourceInfoID <= 0) continue;

                    $this->db->select('idtbl_receivable_info, overpayment, overpaysetoff');
                    $this->db->from('tbl_receivable_info');
                    $this->db->where('idtbl_receivable_info', $sourceInfoID);
                    $sourceRow = $this->db->get()->row();

                    if (!$sourceRow) {
                        throw new Exception("Unapplied record ID {$sourceInfoID} not found.");
                    }

                    $availableBalance = floatval($sourceRow->overpayment);

                    if (round($unappliedPool, 2) > round($availableBalance, 2)) {
                        throw new Exception("Unapplied amount ({$unappliedPool}) exceeds available balance ({$availableBalance}) for record ID {$sourceInfoID}.");
                    }

                    $remainingPool = $unappliedPool;

                    foreach ($invoicePayments as $index => &$invoicePayment) {
                        if ($invoicePayment['remaining'] <= 0 || $remainingPool <= 0) continue;

                        $isLastInvoice = true;
                        for ($i = $index + 1; $i < count($invoicePayments); $i++) {
                            if ($invoicePayments[$i]['remaining'] > 0) { $isLastInvoice = false; break; }
                        }

                        $applyAmount = min($remainingPool, $invoicePayment['remaining']);

                        if ($isLastInvoice && $remainingPool > $invoicePayment['remaining']) {
                            $actualApplyAmount    = $invoicePayment['remaining'];
                            $newOverpaymentAmount = $remainingPool - $actualApplyAmount;

                            $this->db->insert('tbl_receivable_info', [
                                'invoiceno'                       => $invoicePayment['invoice_id'],
                                'narration'                       => 'Unapplied amount used from previous overpayment',
                                'amount'                          => $actualApplyAmount,
                                'overpayment'                     => $newOverpaymentAmount,
                                'overpaysetoff'                   => 0,
                                'setoff_receivable_info_id'       => $sourceInfoID,
                                'creditnote_id'                   => 0,
                                'status'                          => '1',
                                'insertdatetime'                  => $updatedatetime,
                                'tbl_user_idtbl_user'             => $userID,
                                'tbl_receivable_idtbl_receivable' => $targetReceivableID,
                            ]);

                            $invoicePayment['remaining']    = 0;
                            $invoicePayment['paid_amount'] += $actualApplyAmount;
                            $remainingPool                  = 0;
                            $totalUnappliedUsed            += $actualApplyAmount;

                            if ($newOverpaymentAmount > 0) {
                                $totalOverpaymentFromCurrent += $newOverpaymentAmount;
                            }
                        } else {
                            $this->db->insert('tbl_receivable_info', [
                                'invoiceno'                       => $invoicePayment['invoice_id'],
                                'narration'                       => 'Unapplied amount used from previous overpayment',
                                'amount'                          => $applyAmount,
                                'overpayment'                     => 0,
                                'overpaysetoff'                   => 0,
                                'setoff_receivable_info_id'       => $sourceInfoID,
                                'creditnote_id'                   => 0,
                                'status'                          => '1',
                                'insertdatetime'                  => $updatedatetime,
                                'tbl_user_idtbl_user'             => $userID,
                                'tbl_receivable_idtbl_receivable' => $targetReceivableID,
                            ]);

                            $invoicePayment['remaining']    -= $applyAmount;
                            $invoicePayment['paid_amount']  += $applyAmount;
                            $remainingPool                  -= $applyAmount;
                            $totalUnappliedUsed             += $applyAmount;
                        }

                        if ($remainingPool <= 0) break;
                    }
                    unset($invoicePayment);

                    $amountConsumed = $unappliedPool - $remainingPool;
                    $newBalance     = round($availableBalance - $amountConsumed, 2);

                    $this->db->where('idtbl_receivable_info', $sourceInfoID);
                    $this->db->update('tbl_receivable_info', [
                        'overpayment'   => $newBalance,
                        'overpaysetoff' => ($newBalance <= 0) ? 1 : 0
                    ]);
                }
            }

            // ─────────────────────────────────────────────────────────────────────
            // STEP 4: Apply current cash/bank payments to invoices
            // ─────────────────────────────────────────────────────────────────────

            if (!empty($paymentdata) && !empty($receivableIDs)) {
                foreach ($receivableIDs as $receivable) {
                    $receivableID     = $receivable['id'];
                    $paymentAmount    = $receivable['amount'];
                    $remainingPayment = $paymentAmount;

                    foreach ($invoicePayments as $index => &$invoicePayment) {
                        if ($invoicePayment['remaining'] <= 0 || $remainingPayment <= 0) continue;

                        $invoiceRemaining = $invoicePayment['remaining'];
                        $narration        = $invoicePayment['invoice_no'];
                        $isLastInvoice    = true;

                        foreach ($invoicePayments as $checkIndex => $checkInvoice) {
                            if ($checkIndex > $index && $checkInvoice['remaining'] > 0) { $isLastInvoice = false; break; }
                        }
                        
                        if ($remainingPayment >= $invoiceRemaining) {
                            if ($isLastInvoice) {
                                $overpaymentAmount = $remainingPayment - $invoiceRemaining;
                                $this->db->insert('tbl_receivable_info', [
                                    'invoiceno'                       => $invoicePayment['invoice_id'],
                                    'narration'                       => $narration,
                                    'amount'                          => $invoiceRemaining,
                                    'overpayment'                     => $overpaymentAmount,
                                    'overpaysetoff'                   => 0,
                                    'setoff_receivable_info_id'       => 0,
                                    'creditnote_id'                   => 0,
                                    'status'                          => '1',
                                    'insertdatetime'                  => $updatedatetime,
                                    'tbl_user_idtbl_user'             => $userID,
                                    'tbl_receivable_idtbl_receivable' => $receivableID,
                                    'tbl_account_transaction_manual_idtbl_account_transaction_manual' => $invoicePayment['journal_id'],
                                ]);
                                if ($overpaymentAmount > 0) {
                                    $totalOverpaymentFromCurrent += $overpaymentAmount;
                                }
                                $invoicePayment['remaining']    = 0;
                                $invoicePayment['paid_amount'] += $invoiceRemaining;
                                $remainingPayment               = 0;
                            } else {
                                $this->db->insert('tbl_receivable_info', [
                                    'invoiceno'                       => $invoicePayment['invoice_id'],
                                    'narration'                       => $narration,
                                    'amount'                          => $invoiceRemaining,
                                    'overpayment'                     => 0,
                                    'overpaysetoff'                   => 0,
                                    'setoff_receivable_info_id'       => 0,
                                    'creditnote_id'                   => 0,
                                    'status'                          => '1',
                                    'insertdatetime'                  => $updatedatetime,
                                    'tbl_user_idtbl_user'             => $userID,
                                    'tbl_receivable_idtbl_receivable' => $receivableID,
                                    'tbl_account_transaction_manual_idtbl_account_transaction_manual' => $invoicePayment['journal_id'],
                                ]);
                                $invoicePayment['remaining']    = 0;
                                $invoicePayment['paid_amount'] += $invoiceRemaining;
                                $remainingPayment              -= $invoiceRemaining;
                            }
                        } else {
                            $this->db->insert('tbl_receivable_info', [
                                'invoiceno'                       => $invoicePayment['invoice_id'],
                                'narration'                       => $narration,
                                'amount'                          => $remainingPayment,
                                'overpayment'                     => 0,
                                'overpaysetoff'                   => 0,
                                'setoff_receivable_info_id'       => 0,
                                'creditnote_id'                   => 0,
                                'status'                          => '1',
                                'insertdatetime'                  => $updatedatetime,
                                'tbl_user_idtbl_user'             => $userID,
                                'tbl_receivable_idtbl_receivable' => $receivableID,
                                'tbl_account_transaction_manual_idtbl_account_transaction_manual' => $invoicePayment['journal_id'],
                            ]);
                            $invoicePayment['remaining']    -= $remainingPayment;
                            $invoicePayment['paid_amount']  += $remainingPayment;
                            $remainingPayment                = 0;
                        }
                    }
                    unset($invoicePayment);
                }
            }

            // ─────────────────────────────────────────────────────────────────────
            // STEP 4.5: Update journal settle status for Receivable Journal filter
            // ─────────────────────────────────────────────────────────────────────
            if ($receivablefilter == 2) {
                foreach ($invoicePayments as $invoicePayment) {
                    if (!empty($invoicePayment['journal_id']) && $invoicePayment['remaining'] <= 0) {
                        $this->db->where('idtbl_account_transaction_manual', $invoicePayment['journal_id']);
                        $this->db->update('tbl_account_transaction_manual', [
                            'recesettle'     => '1',
                            'updateuser'     => $userID,
                            'updatedatetime' => $updatedatetime,
                        ]);
                    }
                }
            }

            // ─────────────────────────────────────────────────────────────────────
            // STEP 5: Payment total validation
            // ─────────────────────────────────────────────────────────────────────
            if (!empty($paymentdata)) {
                // Path A — cash payment
                $grandTotal    = round($paymentnettotal + $totalUnappliedUsed + $totalCreditNoteUsed, 2);
                $expectedTotal = round(floatval($paidamount) + floatval($creditnotetotal), 2);
            } else {
                // Path B — CN only / unapplied only / CN + unapplied
                $grandTotal    = round($totalCreditNoteUsed + $totalUnappliedUsed, 2);
                $expectedTotal = round(floatval($creditnotetotal) + floatval($unappliedamount), 2);
            }

            if ($grandTotal != $expectedTotal) {
                throw new Exception("Payment total mismatch. Expected: {$expectedTotal}, Got: {$grandTotal}");
            }

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Transaction failed');
            }
            // die();
            $this->db->trans_commit();
            $this->_jsonResponse(1, 'fas fa-save', 'Record Added Successfully', 'success');

        } catch (Exception $e) {
            if ($this->db->trans_enabled) {
                $this->db->trans_rollback();
            }
            error_log('Receivablesettleinsertupdate Error: ' . $e->getMessage());
            $this->_jsonResponse(0, 'fas fa-exclamation-triangle', 'Record Error: ' . $e->getMessage(), 'danger');
        }
    }
    public function Getviewpostinfo(){
        $recordID=$this->input->post('recordID');
        $updatedatetime=date('Y-m-d H:i:s');

        $configdata = getconfigdata('receivable_search');

		$tablename = $configdata->row(0)->tbl_name;
		$column1   = $configdata->row(0)->col_name;
		$column2   = $configdata->row(1)->col_name;

        $has_table = !empty($tablename) ? 1 : 0;

        $data = array(
            'postviewtime'=> $updatedatetime
        );

        $this->db->where('idtbl_receivable', $recordID);
        $this->db->where('poststatus', 0);
        $this->db->update('tbl_receivable', $data);

        $this->db->select("tbl_receivable.*, tbl_company.company, tbl_company_branch.branch, IF($has_table = 0, '', $tablename.$column2) AS customer, tbl_account.accountno AS `chartaccount`, tbl_account.accountname AS `chartaccountname`, tbl_account_detail.accountno AS `detailaccount`, tbl_account_detail.accountname AS `detailaccountname`");
        $this->db->from('tbl_receivable');
        $this->db->join('tbl_company', 'tbl_company.idtbl_company = tbl_receivable.tbl_company_idtbl_company', 'left');
        $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_receivable.tbl_company_branch_idtbl_company_branch', 'left');
        if(!empty($tablename)){
            $this->db->join("$tablename", "$tablename.$column1 = tbl_receivable.payer", 'left');
        }
        $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_receivable.tbl_account_idtbl_account', 'left');
        $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_receivable.tbl_account_detail_idtbl_account_detail', 'left');
        $this->db->where('tbl_receivable.idtbl_receivable', $recordID);
        // $this->db->where('tbl_account_payable_main.status', 1);

        $respond=$this->db->get();

        $this->db->select('tbl_receivable_entry.*, tbl_account_detail.accountno, tbl_account_detail.accountname, tbl_account.accountno AS chartaccountno, tbl_account.accountname AS chartaccountname');
        $this->db->from('tbl_receivable_entry');
        $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_receivable_entry.tbl_account_detail_idtbl_account_detail', 'left');
        $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_receivable_entry.tbl_account_idtbl_account', 'left');
        $this->db->where('tbl_receivable_entry.tbl_receivable_idtbl_receivable', $recordID);
        // $this->db->where('tbl_account_receivable.status', 1);

        $respondpayinfo=$this->db->get();

        $this->db->select('`invoiceno`, `narration`, `amount`');
        $this->db->from('tbl_receivable_info');
        $this->db->where('tbl_receivable_idtbl_receivable', $recordID);
        // $this->db->where('tbl_account_payable.status', 1);

        $respondinvoiceinfo=$this->db->get();

        // if($respond->row(0)->tbl_receivable_type_idtbl_receivable_type==1){
        //     if($respond->row(0)->tbl_account_detail_idtbl_account_detail>0){
        //         $accountno=$respond->row(0)->detailaccount; 
        //         $accountname=$respond->row(0)->detailaccountname;
        //     }
        //     else{
        //         $accountno=$respond->row(0)->chartaccount; 
        //         $accountname=$respond->row(0)->chartaccountname;
        //     }
        //     $chequedate='';
        //     $chequeno='';
        // }
        // else if($respond->row(0)->tbl_receivable_type_idtbl_receivable_type==2){
            if($respond->row(0)->tbl_account_detail_idtbl_account_detail>0){
                $accountno=$respond->row(0)->detailaccount; 
                $accountname=$respond->row(0)->detailaccountname;
            }
            else{
                $accountno=$respond->row(0)->chartaccount; 
                $accountname=$respond->row(0)->chartaccountname;
            }
            $chequedate=$respond->row(0)->chequedate;
            $chequeno=$respond->row(0)->chequeno;
        // }

        $html='';
        if($respond->row(0)->status==2){
        $html.='
        <div class="row">
            <div class="col">
                <div class="alert alert-warning" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i> Record Deactivated. Kindly review the status of the record.
                </div> 
            </div>
        </div>';
        }if($respond->row(0)->editstatus==1){
        $html.='
        <div class="row">
            <div class="col">
                <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i> Record in editable mode. You cannot change anything about the record.
                </div> 
            </div>
        </div>';
        }if($respond->row(0)->postdatedstatus==1 && $respond->row(0)->chequedate>date('Y-m-d')){
        $html.='
        <div class="row">
            <div class="col">
                <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i> You are viewing a post-dated receivable settlement. Please take note that this transaction will only be posted on the cheque date.
                </div> 
            </div>
        </div>';
        }
        $html.='
        <div class="row">
            <div class="col">
                <label class="small font-weight-bold my-0">Batch No: </label>
                <label class="small my-0">'.$respond->row(0)->batchno.'</label><br>
                <label class="small font-weight-bold my-0">Date: </label>
                <label class="small my-0">'.$respond->row(0)->recdate.'</label><br>
                <label class="small font-weight-bold my-0">Company/Branch: </label>
                <label class="small my-0">'.$respond->row(0)->company.'-'.$respond->row(0)->branch.'</label><br>
                <label class="small font-weight-bold my-0">Account No: </label>
                <label class="small my-0">'.$accountno.' - '.$accountname.'</label>
            </div>
            <div class="col">
                <label class="small font-weight-bold my-0">Supplier: </label>
                <label class="small my-0">' . ($respond->row(0)->recsettlefiltertype == 2 ? 'Journal receivable' : ($respond->row(0)->recsettlefiltertype == 3 ? 'Voucher receivable' : $respond->row(0)->customer)) .'</label><br>
                <label class="small font-weight-bold my-0">Cheque Date: </label>
                <label class="small my-0">'.$chequedate.'</label><br>
                <label class="small font-weight-bold my-0">Cheque No: </label>
                <label class="small my-0">'.$chequeno.'</label><br>
                <label class="small font-weight-bold my-0">Amount: </label>
                <label class="small my-0">'.number_format($respond->row(0)->amount, 2).'</label>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h6 class="small title-style my-3"><span>Segregation Information</span></h6>
                <table class="table  table-striped table-sm nowrap small">
                    <thead>
                        <tr>
                            <th>Account</th>
                            <th>Narration</th>
                            <th class="text-center">C/D</th>
                            <th class="text-right">Debit</th>
                            <th class="text-right">Credit</th>
                        </tr>
                    </thead>
                    <tbody>';
                    foreach($respondpayinfo->result() as $rowdatainfo){
                        if($rowdatainfo->tratype=='D'){
                            $debitamount=$rowdatainfo->amount;
                            $creditamount=0;
                        }
                        else if($rowdatainfo->tratype=='C'){
                            $creditamount=$rowdatainfo->amount;
                            $debitamount=0;
                        }

                        $html.='
                        <tr>
                            <td>';
                            if(!empty($rowdatainfo->tbl_account_detail_idtbl_account_detail)){
                                $html.=$rowdatainfo->accountname.' - '.$rowdatainfo->accountno;
                            }
                            else{
                                $html.=$rowdatainfo->chartaccountname.' - '.$rowdatainfo->chartaccountno;
                            }
                            $html.='</td>
                            <td>'.$rowdatainfo->narration.'</td>
                            <td class="text-center">'.$rowdatainfo->tratype.'</td>
                            <td class="text-right">'.($debitamount != 0 ? number_format($debitamount, 2) : '').'</td>
                            <td class="text-right">'.($creditamount != 0 ? number_format($creditamount, 2) : '').'</td>
                        </tr>
                        ';
                    }
                    $html.='</tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h6 class="small title-style my-3"><span>Receivable Invoice Information</span></h6>
                <table class="table  table-striped table-sm nowrap small">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Invoice No</th>
                            <th>Narration</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>';
                    foreach($respondinvoiceinfo->result() as $rowdatainfo){
                        $html.='
                        <tr>
                            <td>'.($respond->row(0)->recsettlefiltertype == 2 ? 'Journal receivable' : $respond->row(0)->customer).'</td>
                            <td>'.$rowdatainfo->invoiceno.'</td>
                            <td>'.$rowdatainfo->narration.'</td>
                            <td class="text-right">'.number_format($rowdatainfo->amount, 2).'</td>
                        </tr>
                        ';
                    }
                    $html.='</tbody>
                </table>
            </div>
        </div>';
        if($respond->row(0)->poststatus==1){
            $html.='<div class="alert alert-success" role="alert">
                <h4 class="alert-heading">Posted!</h4>
                <p>The journal entry you are attempting to save has already been posted to the system. Please check your records or contact your administrator for assistance.</p>
            </div>';
        }

        $obj=new stdClass();
        $obj->html=$html;
        $obj->editablestatus=$respond->row(0)->editstatus;

        echo json_encode($obj);
    }
    public function Receivablesettleposting(){
        try {
            $recordID       = $this->input->post('recordID');
            $updatedatetime = date('Y-m-d H:i:s');
            $userID         = $_SESSION['userid'];
            $companyid      = $_SESSION['companyid'];
		    $branchid       = $_SESSION['branchid'];
            $i              = 0;
    
            if(empty($recordID)){
                throw new Exception('Record ID is required');
            }
    
            // ── Fetch main receivable record ──────────────────────────────────
            $this->db->select('recdate, batchno, amount, poststatus, status, editstatus, postviewtime, updatedatetime, tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch, tbl_master_idtbl_master, payer, tbl_account_idtbl_account, tbl_account_detail_idtbl_account_detail, narration, postdatedstatus, chequedate');
            $this->db->from('tbl_receivable');
            $this->db->where('idtbl_receivable', $recordID);
            $this->db->where('status', 1);
    
            $respond = $this->db->get();
    
            if(!$respond || $respond->num_rows() == 0){
                throw new Exception('Record not found');
            }
    
            $record = $respond->row(0);
    
            // ── Status validation checks ──────────────────────────────────────
            if($record->postdatedstatus == 1 && $record->chequedate > date('Y-m-d')){
                throw new Exception('Record Error, You cannot post a post-dated receivable.');
            }
    
            if($record->status == 2){
                throw new Exception('Record Error, Record Deactivated. Kindly review the status of the record.');
            }
    
            if($record->poststatus == 1){
                throw new Exception('Record Error, Record already posted.');
            }
    
            if($record->editstatus == 1){
                throw new Exception('Record Error, Record in editable mode. You cannot change anything about the record.');
            }
    
            if(!($record->poststatus == 0 && $record->status == 1 && $record->editstatus == 0)){
                throw new Exception('Record Error, Invalid record state for posting.');
            }
    
            if($record->postviewtime <= $record->updatedatetime){
                throw new Exception('Record Error, Please check this record for information. Because this record was edited before you posted.');
            }
    
            // ── Begin Transaction ─────────────────────────────────────────────
            $this->db->trans_begin();
    
            $i = 1;

            if($record->postdatedstatus == 1 && $record->chequedate <= date('Y-m-d')){
                $recdate = $record->chequedate;
            }
            else{
                $recdate = $record->recdate;
            }
    
            // Generate batch number for account transaction
            $prefix  = generate_prefix($record->tbl_company_idtbl_company, $record->tbl_company_branch_idtbl_company_branch, $recdate, 'AT');
            $batchno = tr_batch_num($prefix, $record->tbl_company_branch_idtbl_company_branch);
            $masterdata = get_account_period_acco_date($companyid, $branchid, $recdate);

            if (empty($masterdata) || empty($masterdata->idtbl_master)) {
                throw new Exception('Record Error, Account period not found for the given date');
            }

            $masterID = $masterdata->idtbl_master ? $masterdata->idtbl_master : '';
    
            if(empty($batchno)){
                throw new Exception('Record Error, Batch no could not be defined by system');
            }

            // Update main receivable post status
            $data = array(
                'depositstatus' => '1',
                'poststatus'    => '1',
                'postuser'      => $userID,
                'postviewtime'  => NULL
            );
    
            $this->db->where('idtbl_receivable', $recordID);
            $this->db->update('tbl_receivable', $data);
    
            // ── Fetch receivable entry lines ──────────────────────────────────
            $this->db->select('`idtbl_receivable_entry`, `transdate`, `batchno`, `tratype`, `amount`, `narration`, `tbl_master_idtbl_master`, `tbl_company_idtbl_company`, `tbl_company_branch_idtbl_company_branch`, `tbl_account_idtbl_account`, `tbl_account_detail_idtbl_account_detail`');
            $this->db->from('tbl_receivable_entry');
            $this->db->where('tbl_receivable_idtbl_receivable', $recordID);
            $this->db->where('status', 1);
    
            $responddetail = $this->db->get();
    
            if(!$responddetail || $responddetail->num_rows() == 0){
                throw new Exception('No receivable entry lines found for this record');
            }
    
            // ── Process each entry line ───────────────────────────────────────
            foreach($responddetail->result() as $rowdetail){
                $i++;
    
                $receivedetailID = $rowdetail->idtbl_receivable_entry;
                $tradate         = $rowdetail->transdate;
                $segbatchno      = $rowdetail->batchno;
                $detailaccount   = $rowdetail->tbl_account_detail_idtbl_account_detail;
                $chartaccount    = $rowdetail->tbl_account_idtbl_account;
                $company         = $rowdetail->tbl_company_idtbl_company;
                $branch          = $rowdetail->tbl_company_branch_idtbl_company_branch;
                $masterID        = $masterID; // Use the masterID determined earlier
                $amount          = $rowdetail->amount;
                $narration       = $rowdetail->narration;
                $tratype         = $rowdetail->tratype;
    
                // Resolve chart of account ID
                if(!empty($detailaccount)){
                    $chartofaccountinfo = get_chart_account_acco_child_account($company, $branch, $detailaccount);
                    if(!$chartofaccountinfo || $chartofaccountinfo->num_rows() == 0){
                        throw new Exception('Chart of account not found for detail account: ' . $detailaccount);
                    }
                    $chartofaccountID = $chartofaccountinfo->row(0)->idtbl_account;
                } else {
                    $chartofaccountID = $chartaccount;
                }
    
                // Insert into tbl_account_transaction
                $data = array(
                    'tradate'                                 => $tradate,
                    'batchno'                                 => $batchno,
                    'trabatchotherno'                         => $segbatchno,
                    'tratype'                                 => 'R',
                    'seqno'                                   => $i,
                    'crdr'                                    => $tratype,
                    'accamount'                               => $amount,
                    'narration'                               => $narration,
                    'totamount'                               => $amount,
                    'status'                                  => '1',
                    'insertdatetime'                          => $updatedatetime,
                    'tbl_user_idtbl_user'                     => $userID,
                    'tbl_account_idtbl_account'               => $chartofaccountID,
                    'tbl_master_idtbl_master'                 => $masterID,
                    'tbl_company_idtbl_company'               => $company,
                    'tbl_company_branch_idtbl_company_branch' => $branch
                );
    
                $this->db->insert('tbl_account_transaction', $data);
    
                // Insert into tbl_account_transaction_full
                $datafull = array(
                    'tradate'                                 => $tradate,
                    'batchno'                                 => $batchno,
                    'tratype'                                 => 'R',
                    'crdr'                                    => $tratype,
                    'accamount'                               => $amount,
                    'narration'                               => $narration,
                    'totamount'                               => $amount,
                    'status'                                  => '1',
                    'insertdatetime'                          => $updatedatetime,
                    'tbl_user_idtbl_user'                     => $userID,
                    'tbl_account_idtbl_account'               => $chartofaccountID,
                    'tbl_master_idtbl_master'                 => $masterID,
                    'tbl_company_idtbl_company'               => $company,
                    'tbl_company_branch_idtbl_company_branch' => $branch
                );
    
                $this->db->insert('tbl_account_transaction_full', $datafull);


    
                // Update post status on entry line
                $datadetail = array(
                    'poststatus' => '1',
                    'postuser'   => $userID
                );
    
                $this->db->where('idtbl_receivable_entry', $receivedetailID);
                $this->db->update('tbl_receivable_entry', $datadetail);
            }
    
            // ── Complete Transaction ──────────────────────────────────────────
            $this->db->trans_complete();
    
            if($this->db->trans_status() === TRUE){
                $this->db->trans_commit();
                $this->_jsonResponse(1, 'fas fa-save', 'Record Posted Successfully', 'success');
            } else {
                $this->db->trans_rollback();
                throw new Exception('Record Error, Transaction failed');
            }
    
        } catch(Exception $e){
            if($this->db->trans_enabled){
                $this->db->trans_rollback();
            }
            $this->_jsonResponse(0, 'fas fa-warning', $e->getMessage(), 'danger');
        }
    }
    public function Receivablesettlestatus($x, $y){
        $userID         = $_SESSION['userid'];
        $recordID       = $x;
        $type           = $y;
        $updatedatetime = date('Y-m-d H:i:s');
    
        // ── Type config map ───────────────────────────────────────────────────
        // type 1 = Activate, type 2 = Deactivate, type 3 = Remove
        $typeConfig = array(
            1 => array(
                'status'  => '1',
                'icon'    => 'fas fa-check',
                'message' => 'Record Activate Successfully',
                'type'    => 'success'
            ),
            2 => array(
                'status'  => '2',
                'icon'    => 'fas fa-times',
                'message' => 'Record Deactivate Successfully',
                'type'    => 'warning'
            ),
            3 => array(
                'status'  => '3',
                'icon'    => 'fas fa-trash-alt',
                'message' => 'Record Remove Successfully',
                'type'    => 'danger'
            ),
        );
    
        try {
    
            if(empty($recordID)){
                throw new Exception('Record ID is required');
            }
    
            if(!array_key_exists($type, $typeConfig)){
                throw new Exception('Invalid status type provided');
            }
    
            $config = $typeConfig[$type];
    
            // ── Begin Transaction ─────────────────────────────────────────────
            $this->db->trans_begin();
    
            // Update main receivable status
            $data = array(
                'status'         => $config['status'],
                'updateuser'     => $userID,
                'updatedatetime' => $updatedatetime
            );
    
            $this->db->where('idtbl_receivable', $recordID);
            $this->db->update('tbl_receivable', $data);
    
            // Update receivable info status
            $datapay = array(
                'status'         => $config['status'],
                'updateuser'     => $userID,
                'updatedatetime' => $updatedatetime
            );
    
            $this->db->where('tbl_receivable_idtbl_receivable', $recordID);
            $this->db->update('tbl_receivable_info', $datapay);

            // Update tbl_receivable_entry info status
            $datapay = array(
                'status'         => $config['status'],
                'updateuser'     => $userID,
                'updatedatetime' => $updatedatetime
            );
    
            $this->db->where('tbl_receivable_idtbl_receivable', $recordID);
            $this->db->update('tbl_receivable_entry', $datapay);
    
            // ── Complete Transaction ──────────────────────────────────────────
            $this->db->trans_complete();
    
            if($this->db->trans_status() === TRUE){
                $this->db->trans_commit();
    
                $actionObj          = new stdClass();
                $actionObj->icon    = $config['icon'];
                $actionObj->title   = '';
                $actionObj->message = $config['message'];
                $actionObj->url     = '';
                $actionObj->target  = '_blank';
                $actionObj->type    = $config['type'];
    
                $this->session->set_flashdata('msg', json_encode($actionObj));
                redirect('Receivablesettle');
            } else {
                $this->db->trans_rollback();
                throw new Exception('Record Error');
            }
    
        } catch(Exception $e){
            if($this->db->trans_enabled){
                $this->db->trans_rollback();
            }
    
            $actionObj          = new stdClass();
            $actionObj->icon    = 'fas fa-warning';
            $actionObj->title   = '';
            $actionObj->message = $e->getMessage();
            $actionObj->url     = '';
            $actionObj->target  = '_blank';
            $actionObj->type    = 'danger';
    
            $this->session->set_flashdata('msg', json_encode($actionObj));
            redirect('Receivablesettle');
        }
    }
    public function Getinvrecno(){
        $printtype=$this->input->post('printtype');
        $printcustomer=$this->input->post('printcustomer');
        $printdate=$this->input->post('printdate');

        if($printtype==1){
            $this->db->select('`tbl_receivable_info`.`invoiceno` AS `invoicereceiptno`');
            $this->db->from('tbl_receivable_info');
            $this->db->join('tbl_receivable', 'tbl_receivable.idtbl_receivable = tbl_receivable_info.tbl_receivable_idtbl_receivable', 'left');
            $this->db->where('tbl_receivable_info.status', '1');
            $this->db->where('tbl_receivable.status', '1');
            if(!empty($printcustomer)){$this->db->where('tbl_receivable.payer', $printcustomer);}
            if(!empty($printdate)){$this->db->where('tbl_receivable.recdate', $printdate);}
            $this->db->group_by('`tbl_receivable_info`.`invoiceno`');

            $respond=$this->db->get();

            echo json_encode($respond->result());
        }
        else{
            $this->db->select('`receiptno` AS `invoicereceiptno`');
            $this->db->from('tbl_receivable');
            $this->db->where('tbl_receivable.status', '1');
            if(!empty($printcustomer)){$this->db->where('tbl_receivable.payer', $printcustomer);}
            if(!empty($printdate)){$this->db->where('tbl_receivable.recdate', $printdate);}
            $this->db->group_by('`receiptno`');

            $respond=$this->db->get();

            echo json_encode($respond->result());
        }
    }
    public function Getunappliedpaymentaccocustomer(){
        $recordID=$this->input->post('recordID');
        
        $configdata = getconfigdata('receivable_search');

		$tablename = $configdata->row(0)->tbl_name;
		$column1   = $configdata->row(0)->col_name;
		$column2   = $configdata->row(1)->col_name;

        $has_table = !empty($tablename) ? 1 : 0;

        // $this->db->select('`tbl_sales_info`.`idtbl_sales_info`, `tbl_sales_info`.`invno`, `tbl_sales_info`.`amount`, IFNULL(SUM(`tbl_receivable_info`.`amount`), 0) AS `sumpay`, (`tbl_sales_info`.`amount`-IFNULL(SUM(`tbl_receivable_info`.`amount`), 0)) AS `balpay`, `tbl_sales_info`.`tbl_customer_idtbl_customer`, `tbl_customer`.`customer`');
        $this->db->select("`tbl_receivable_info`.`idtbl_receivable_info`, `tbl_receivable_info`.`invoiceno`, `tbl_receivable_info`.`overpayment`, IF($has_table = 0, '', $tablename.$column1) AS idtbl_customer, IF($has_table = 0, '', $tablename.$column2) AS customer");
        $this->db->from('tbl_receivable');
        $this->db->join('tbl_receivable_info', 'tbl_receivable_info.tbl_receivable_idtbl_receivable = tbl_receivable.idtbl_receivable', 'left');
        if(!empty($tablename)){
            $this->db->join("$tablename", "$tablename.$column1 = tbl_receivable.payer", 'left');
        }
        $this->db->where('tbl_receivable.status', 1);
        $this->db->where('tbl_receivable.poststatus', 1);
        $this->db->where('tbl_receivable_info.overpaysetoff', 0);
        $this->db->where('tbl_receivable_info.overpayment > ', 0);
        $this->db->where('tbl_receivable.payer', $recordID);

        $respond=$this->db->get();
        // print_r($this->db->last_query());

        $html='';
        $i=1;
        if(!empty($respond->result())){
            foreach($respond->result() as $rowdatalist){
                $html.='
                <tr>
                    <td class="text-center" width="5%">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input checkclickunapplied" id="customUnapplied'.$i.'">
                            <label class="custom-control-label m-0" for="customUnapplied'.$i.'"></label>
                        </div>
                    </td>
                    <td class="d-none">'.$rowdatalist->idtbl_receivable_info.'</td>
                    <td>'.$rowdatalist->customer.'</td>
                    <td class="d-none">'.$rowdatalist->invoiceno.'</td>
                    <td>'.$rowdatalist->invoiceno.'</td>
                    <td class="text-right">'.number_format($rowdatalist->overpayment, 2).'</td>
                </tr>
                ';
                $i++;
            }
        }
        echo $html;
    }
    public function Getcreditnoteaccocustomer(){
        $recordID=$this->input->post('recordID');
        $companyID=$_SESSION['companyid'];
        $branchID=$_SESSION['branchid'];
        
        $configdata = getconfigdata('receivable_search');

		$tablename = $configdata->row(0)->tbl_name;
		$column1   = $configdata->row(0)->col_name;
		$column2   = $configdata->row(1)->col_name;

        $has_table = !empty($tablename) ? 1 : 0;

        $this->db->select("`tbl_account_creditnote`.`idtbl_account_creditnote`, `tbl_account_creditnote`.`creditnoteno`, (`tbl_account_creditnote`.`nettotal` - `tbl_account_creditnote`.`settleamount`) AS remainingamount, `tbl_account_creditnote`.`vatstatus`, IF($has_table = 0, '', $tablename.$column1) AS idtbl_customer, IF($has_table = 0, '', $tablename.$column2) AS customer");
        $this->db->from('tbl_account_creditnote');
        if(!empty($tablename)){
            $this->db->join("$tablename", "$tablename.$column1 = tbl_account_creditnote.tbl_customer_idtbl_customer", 'left');
        }
        $this->db->where('tbl_account_creditnote.status', 1);
        $this->db->where('tbl_account_creditnote.claimstatus', 0);
        $this->db->where('tbl_account_creditnote.tbl_customer_idtbl_customer', $recordID);
        $this->db->where('tbl_account_creditnote.tbl_company_idtbl_company', $companyID);
        $this->db->where('tbl_account_creditnote.tbl_company_branch_idtbl_company_branch', $branchID);

        $respond=$this->db->get();
        // print_r($this->db->last_query());        

        $html='';
        $i=1;
        if(!empty($respond->result())){
            foreach($respond->result() as $rowdatalist){
                $html.='
                <tr>
                    <td class="text-center" width="5%">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input checkclickcreditnote" id="customcreditnote'.$i.'">
                            <label class="custom-control-label m-0" for="customcreditnote'.$i.'"></label>
                        </div>
                    </td>
                    <td class="d-none">'.$rowdatalist->idtbl_account_creditnote.'</td>
                    <td>'.$rowdatalist->creditnoteno.'</td>
                    <td>'.$rowdatalist->customer.'</td>
                    <td class="text-right">'.number_format($rowdatalist->remainingamount, 2).'</td>
                </tr>
                ';
                $i++;
            }
        }
        echo $html;
    }

    // ── Private helper ────────────────────────────────────────────────────────────
    private function _jsonResponse($status, $icon, $message, $type) {
        $actionObj          = new stdClass();
        $actionObj->icon    = $icon;
        $actionObj->title   = '';
        $actionObj->message = $message;
        $actionObj->url     = '';
        $actionObj->target  = '_blank';
        $actionObj->type    = $type;

        $obj         = new stdClass();
        $obj->status = $status;
        $obj->action = json_encode($actionObj);

        echo json_encode($obj);
    }
}