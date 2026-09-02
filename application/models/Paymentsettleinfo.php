<?php
class Paymentsettleinfo extends CI_Model{
    public function Getreceivabletype(){
        $this->db->select('idtbl_receivable_type, receivabletype');
        $this->db->from('tbl_receivable_type');
        $this->db->where('status', 1);

        $respond=$this->db->get();

        return $respond;
    }
    public function Getinvoiceaccosupplier(){
        $recordID=$this->input->post('recordID');
        $payablefilter=$this->input->post('payablefilter');
        $accounttype=$this->input->post('accounttype');

        $configdata = getconfigdata('payable_search');
        
        $tablename = $configdata->row(0)->tbl_name;
        $column1   = $configdata->row(0)->col_name;
        $column2   = $configdata->row(1)->col_name;

        $has_table = !empty($tablename) ? 1 : 0;

        if($payablefilter==1):
            // $this->db->select('`tbl_sales_info`.`idtbl_sales_info`, `tbl_sales_info`.`invno`, `tbl_sales_info`.`amount`, IFNULL(SUM(`tbl_receivable_info`.`amount`), 0) AS `sumpay`, (`tbl_sales_info`.`amount`-IFNULL(SUM(`tbl_receivable_info`.`amount`), 0)) AS `balpay`, `tbl_sales_info`.`tbl_customer_idtbl_customer`, `tbl_customer`.`customer`');
            $this->db->select("`tbl_expence_info`.`idtbl_expence_info`, `tbl_expence_info`.`grnno`, `tbl_expence_info`.`supinvno`, `tbl_expence_info`.`amount`, `tbl_expence_info`.`invamount`, IFNULL(SUM(CASE WHEN `tbl_account_paysettle_info`.`status` = 1 THEN `tbl_account_paysettle_info`.`amount` ELSE 0 END), 0) AS `sumpay`, (`tbl_expence_info`.`invamount`-IFNULL(SUM(CASE WHEN `tbl_account_paysettle_info`.`status` = 1 THEN `tbl_account_paysettle_info`.`amount` ELSE 0 END), 0)) AS `balpay`, `tbl_expence_info`.`tbl_supplier_idtbl_supplier`, IF($has_table = 0, '', $tablename.$column2) AS suppliername");
            $this->db->from('tbl_expence_info');
            $this->db->join('tbl_account_paysettle_info', 'tbl_account_paysettle_info.invoiceno = tbl_expence_info.grnno', 'left');
            if(!empty($tablename)):
                $this->db->join("$tablename", "$tablename.$column1 = tbl_expence_info.tbl_supplier_idtbl_supplier", 'left');
            endif;
            $this->db->where('tbl_expence_info.status', 1);
            $this->db->where('tbl_expence_info.paystatus', 0);
            $this->db->where('tbl_expence_info.poststatus', 1);
            $this->db->where('tbl_expence_info.tbl_supplier_idtbl_supplier', $recordID);
            $this->db->group_by('`tbl_expence_info`.`idtbl_expence_info`');

            $respond=$this->db->get();

            $html='';
            $i=1;
            foreach($respond->result() as $rowdatalist){
                $this->db->select('IFNULL(SUM(`amount`), 0) AS `returnsum`');
                $this->db->from('tbl_account_paysettle_info');
                $this->db->where('status >', 1);
                $this->db->where('invoiceno', $rowdatalist->grnno);

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
                        <td class="d-none">'.$rowdatalist->tbl_supplier_idtbl_supplier.'</td>
                        <td>'.$rowdatalist->suppliername.'</td>
                        <td class="d-none">'.$rowdatalist->grnno.'</td>
                        <td>'.$rowdatalist->grnno.'</td>
                        <td>'.$rowdatalist->supinvno.'</td>
                        <td class="text-right">'.number_format($rowdatalist->invamount, 2).'</td>
                        <td class="text-right invbalamount">'.number_format($netbalpay, 2).'</td>
                    </tr>
                    ';
                    $i++;
                }
            }
        else:
            $this->db->select('`tbl_account_transaction_manual`.`idtbl_account_transaction_manual`, `tbl_account_transaction_manual`.`batchno`, `tbl_account_transaction_manual`.`amount`, IFNULL(SUM(CASE WHEN `tbl_account_paysettle_entry`.`status` = 1 THEN `tbl_account_paysettle_entry`.`amount` ELSE 0 END), 0) AS `sumpay`, (`tbl_account_transaction_manual`.`amount`-IFNULL(SUM(CASE WHEN `tbl_account_paysettle_entry`.`status` = 1 THEN `tbl_account_paysettle_entry`.`amount` ELSE 0 END), 0)) AS `balpay`, `tbl_account_transaction_manual`.`narration`, `tbl_account_transaction_manual`.`tbl_account_idtbl_account`, `tbl_account_transaction_manual`.`tbl_account_detail_idtbl_account_detail`, `tbl_account`.`accountno` AS `chartaccountno`, `tbl_account.accountname` AS `chartaccountname`, `tbl_account_detail.accountno` AS `detailaccountno`, `tbl_account_detail.accountname` AS `detailaccountname`');
            $this->db->from('tbl_account_transaction_manual');
            if($accounttype == 1){
                $this->db->join('tbl_account_paysettle_entry', '`tbl_account_paysettle_entry`.`batchno` = `tbl_account_transaction_manual`.`batchno` AND tbl_account_paysettle_entry.tbl_account_idtbl_account = tbl_account_transaction_manual.tbl_account_idtbl_account', 'left');
            } else if($accounttype == 2){
                $this->db->join('tbl_account_paysettle_entry', '`tbl_account_paysettle_entry`.`batchno` = `tbl_account_transaction_manual`.`batchno` AND tbl_account_paysettle_entry.tbl_account_detail_idtbl_account_detail = tbl_account_transaction_manual.tbl_account_detail_idtbl_account_detail', 'left');
            }
            $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_account_transaction_manual.tbl_account_idtbl_account', 'left');
            $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_account_transaction_manual.tbl_account_detail_idtbl_account_detail', 'left');
            $this->db->where('tbl_account_transaction_manual.status', 1);
            $this->db->where('tbl_account_transaction_manual.payablestatus', 1);
            $this->db->where('tbl_account_transaction_manual.payablesettle', 0);
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
                $this->db->from('tbl_account_paysettle_info');
                $this->db->where('status >', 1);
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
    // public function Paymentsettleinsertupdate(){
    //     $userID=$_SESSION['userid'];
    //     $detailaccount=0;
    //     $chartaccount=0;
    //     $chequeissuestatus=0;
    //     $issuechequeID=0;

    //     $company=$this->input->post('company');
    //     $branch=$this->input->post('branch');
    //     $supplier=$this->input->post('supplier');
    //     $payabletype=$this->input->post('payabletype');
    //     $accounttype=$this->input->post('accounttype');
    //     if(!empty($this->input->post('chequedate'))){$chequedate=$this->input->post('chequedate');}else{$chequedate='';}
    //     $chartofdetailaccount=$this->input->post('chartofdetailaccount');
    //     $narration=$this->input->post('narration');
    //     $invoicepayamount= str_replace(',', '', $this->input->post('invoicepayamount'));
    //     $paiddate= $this->input->post('paiddate');
    //     $paidamount=str_replace(',', '', $this->input->post('paidamount'));
    //     $invoicedata=json_decode($this->input->post('tableData'));
    //     $postdated= $this->input->post('postdated');
    //     $payablefilter=$this->input->post('payablefilter');
    //     $supplierAccountType = $this->input->post('supplierAccountType');
        
    //     $chequecashamount=$paidamount;

    //     if($accounttype==1){$chartaccount=$chartofdetailaccount;}
    //     else if($accounttype==2){$detailaccount=$chartofdetailaccount;}

    //     $recordOption=$this->input->post('recordOption');
    //     if(!empty($this->input->post('recordID'))){$recordID=$this->input->post('recordID');}
        
    //     if($recordOption==1){
    //         $masterdata=get_account_period_acco_date($company, $branch, $paiddate);
    //         $prefix=generate_prefix($company, $branch, $paiddate, 'PS');
    //         $batchno=tr_batch_num($prefix, $branch);
    //         $masterID=$masterdata->idtbl_master;

    //         $this->db->select('tbl_finacial_year.year');
    //         $this->db->from('tbl_master');
    //         $this->db->join('tbl_finacial_year', 'tbl_finacial_year.idtbl_finacial_year = tbl_master.tbl_finacial_year_idtbl_finacial_year', 'left');
    //         $this->db->where('tbl_master.idtbl_master', $masterID);

    //         $respond = $this->db->get();
    //         $financialYear = substr($respond->row(0)->year, -2);
            
    //         $payreceiptno = tr_batch_num('PAY'.$financialYear, $branch);
    //         $payreceiptno = preg_replace('/^(.{5})00/', '$1', $payreceiptno);
    //     }

    //     $updatedatetime=date('Y-m-d H:i:s');
    //     $today=date('Y-m-d');

    //     $this->db->trans_begin();

    //     //Choose cheque no start   
    //     if($payabletype==2){
    //         $this->db->select('`idtbl_cheque_issue`');
    //         $this->db->from('tbl_cheque_issue');
    //         $this->db->join('tbl_cheque_info', 'tbl_cheque_info.idtbl_cheque_info = tbl_cheque_issue.tbl_cheque_info_idtbl_cheque_info', 'left');
    //         $this->db->where('tbl_cheque_info.tbl_account_idtbl_account', $chartofdetailaccount);
    //         $this->db->where('tbl_cheque_info.status', 1);
    //         $this->db->where('tbl_cheque_issue.chequeallocate', 0);
    //         $respondchequeissue=$this->db->get();

    //         if($respondchequeissue->num_rows()>0){
    //             $issuechequeID=$respondchequeissue->row(0)->idtbl_cheque_issue;

    //             $datachequeissue = array(
    //                 'chedate'=> $chequedate, 
    //                 'narration'=> $narration, 
    //                 'amount'=> $chequecashamount, 
    //                 'chequeallocate'=> '1', 
    //                 'updatedatetime'=> $updatedatetime, 
    //                 'updateuser'=> $userID
    //             );
    //             $this->db->where('idtbl_cheque_issue', $respondchequeissue->row(0)->idtbl_cheque_issue);
    //             $this->db->update('tbl_cheque_issue', $datachequeissue);

    //             $this->db->where('tbl_cheque_issue_idtbl_cheque_issue', $issuechequeID);
    //             $this->db->delete('tbl_account_paysettle_has_tbl_cheque_issue');

    //             $chequeissuestatus=1;
    //         }
    //         else{
    //             $this->db->select('tbl_bank_idtbl_bank, tbl_bank_branch_idtbl_bank_branch');
    //             $this->db->from('tbl_cheque_info');
    //             $this->db->where('tbl_account_idtbl_account', $chartofdetailaccount);
    //             $this->db->where('status', 1);
    //             $this->db->group_by("tbl_bank_idtbl_bank");
    //             $this->db->limit(1);

    //             $respondbank=$this->db->get();

    //             if ($respondbank->num_rows() > 0) {
    //                 $bankID=$respondbank->row(0)->tbl_bank_idtbl_bank;
    //                 $branchID=$respondbank->row(0)->tbl_bank_branch_idtbl_bank_branch;

    //                 $sqlcheque = "SELECT tbl_cheque_info.idtbl_cheque_info, IFNULL(LPAD(drv.chno+1, 6, '0'), LPAD(tbl_cheque_info.startno, 6, '0')) AS chno FROM tbl_cheque_info LEFT OUTER JOIN (SELECT tbl_cheque_info_idtbl_cheque_info, max(CAST(chequeno AS UNSIGNED)) AS chno FROM tbl_cheque_issue GROUP BY tbl_cheque_info_idtbl_cheque_info) AS drv ON tbl_cheque_info.idtbl_cheque_info=drv.tbl_cheque_info_idtbl_cheque_info WHERE tbl_cheque_info.tbl_bank_idtbl_bank=? AND tbl_cheque_info.tbl_bank_branch_idtbl_bank_branch=? AND tbl_account_idtbl_account=? AND IFNULL(drv.chno, 0)<CAST(tbl_cheque_info.endno AS UNSIGNED) AND tbl_cheque_info.status=? LIMIT 1";
    //                 $respondcheque=$this->db->query($sqlcheque, array($bankID, $branchID, $chartofdetailaccount, 1));

    //                 if(!empty($respondcheque->result())){
    //                     $chequeissuestatus=1;
    //                     $chequeno=$respondcheque->row(0)->chno;
    //                     $chequeinfoID=$respondcheque->row(0)->idtbl_cheque_info;

    //                     $datachequeissue = array(
    //                         'chedate'=> $chequedate, 
    //                         'chequeno'=> $chequeno, 
    //                         'narration'=> $narration, 
    //                         'amount'=> $chequecashamount, 
    //                         'chequeallocate'=> '1', 
    //                         'chequereturn'=> '0', 
    //                         'status'=> '1', 
    //                         'insertdatetime'=> $updatedatetime, 
    //                         'tbl_user_idtbl_user'=> $userID, 
    //                         'tbl_cheque_info_idtbl_cheque_info'=> $chequeinfoID
    //                     );

    //                     $this->db->insert('tbl_cheque_issue', $datachequeissue);

    //                     $issuechequeID=$this->db->insert_id();
    //                 }
    //             }
    //         }
    //     }
    //     else{
    //         $chequeissuestatus=1;
    //     }
    //     //Choose cheque no end

    //     //Get Creditor Account
    //     $this->db->where('tbl_account_allocation.companybank', $company);
    //     $this->db->where('tbl_account_allocation.branchcompanybank', $branch);
    //     // $this->db->where('tbl_account.tbl_account_type_idtbl_account_type', 2);
    //     $this->db->where('tbl_account.specialcate', 34);
    //     $this->db->where('tbl_account.status', 1);
    //     $this->db->where('tbl_account_allocation.status', 1);
    //     $this->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
    //     $this->db->select('`tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`');
    //     $this->db->from('tbl_account');
    //     $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');

    //     $respondcreditor=$this->db->get();

    //     if($payablefilter == 1){
    //         $this->db->select('tbl_account_detail_idtbl_account_detail as accountid');
    //         $this->db->from('tbl_account_detail_other');
    //         $this->db->where('tbl_company_idtbl_company', $company);
    //         $this->db->where('tbl_company_branch_idtbl_company_branch', $branch);
    //         $this->db->where('otheroptiontype', 1);
    //         $this->db->where('otheroption', $supplier);
    //         $respondcreditacc = $this->db->get();
    //     }
    //     else if($payablefilter == 2 || $payablefilter == 3){
    //         if($supplierAccountType == 1){
    //             $this->db->select('idtbl_account as accountid');
    //             $this->db->from('tbl_account');
    //             $this->db->where('idtbl_account', $supplier);
    //             $this->db->where('status', 1);
    //             $respondcreditacc = $this->db->get();
    //         }
    //         else if($supplierAccountType == 2){
    //             $this->db->select('idtbl_account_detail as accountid');
    //             $this->db->from('tbl_account_detail');
    //             $this->db->where('idtbl_account_detail', $supplier);
    //             $this->db->where('status', 1);
    //             $respondcreditacc = $this->db->get();
    //         }
    //     }
        
    //     if($chequeissuestatus==1){
    //         if(!empty($batchno)){
    //             $data = array(
    //                 'date'=> $paiddate, 
    //                 'paymentno'=> $payreceiptno, 
    //                 'batchno'=> $batchno, 
    //                 'supplier'=> $payablefilter == 1 ? $supplier : '', 
    //                 'totalpayment'=> $paidamount, 
    //                 'remark'=> $narration, 
    //                 'postdatedstatus'=> $postdated, 
    //                 'poststatus'=> '0', 
    //                 'paysettlefiltertype'=> $payablefilter, 
    //                 'status'=> '1', 
    //                 'insertdatetime'=> $updatedatetime, 
    //                 'tbl_user_idtbl_user'=> $userID,
    //                 'tbl_receivable_type_idtbl_receivable_type'=> $payabletype,
    //                 'tbl_company_idtbl_company'=> $company,
    //                 'tbl_company_branch_idtbl_company_branch'=> $branch,
    //                 'tbl_master_idtbl_master'=> $masterID,
    //                 'tbl_account_idtbl_account'=> $chartaccount,
    //                 'tbl_account_detail_idtbl_account_detail'=> $detailaccount,
    //             );

    //             $this->db->insert('tbl_account_paysettle', $data);

    //             $payableID=$this->db->insert_id();

    //             // Debit entry start
    //             $dataentrylist[] = [
    //                 'tratype' => 'C',
    //                 'amount' => $paidamount,
    //                 'narration' => $narration,
    //                 'chartaccount' => $chartaccount,
    //                 'detailaccount' => $detailaccount
    //             ];
    //             // Debit entry end

    //             // Credit entry start                       
    //             if(empty($respondcreditacc->result())):
    //                 if(empty($respondcreditor->result())){
    //                     throw new Exception("You don't have trade creditor account or creditor account");
    //                 }

    //                 $dataentrylist[] = [
    //                     'tratype' => 'D',
    //                     'amount' => $paidamount,
    //                     'narration' => $narration,
    //                     'chartaccount' => $respondcreditor->row(0)->idtbl_account,
    //                     'detailaccount' => 0
    //                 ];
    //             else:
    //                 if($supplierAccountType == 1){
    //                     $chartaccount=$respondcreditacc->row(0)->accountid;
    //                     $detailaccount=0;
    //                 }
    //                 else if($supplierAccountType == 2){
    //                     $chartaccount=0;
    //                     $detailaccount=$respondcreditacc->row(0)->accountid;
    //                 }   
    //                 $dataentrylist[] = [
    //                     'tratype' => 'D',
    //                     'amount' => $paidamount,
    //                     'narration' => $narration,
    //                     'chartaccount' => $chartaccount,
    //                     'detailaccount' => $detailaccount
    //                 ];
    //             endif;
    //             // Credit entry end

    //             foreach($dataentrylist as $rowdataentrylist){
    //                 $datalist = [
    //                     'transdate' => $paiddate, 
    //                     'batchno' => $batchno, 
    //                     'tratype' => $rowdataentrylist['tratype'], 
    //                     'amount' => $rowdataentrylist['amount'], 
    //                     'narration' => $rowdataentrylist['narration'], 
    //                     'poststatus' => '0', 
    //                     'status' => '1', 
    //                     'insertdatetime' => $updatedatetime, 
    //                     'tbl_user_idtbl_user' => $userID, 
    //                     'tbl_company_idtbl_company' => $company, 
    //                     'tbl_company_branch_idtbl_company_branch' => $branch, 
    //                     'tbl_master_idtbl_master' => $masterID, 
    //                     'tbl_account_paysettle_idtbl_account_paysettle' => $payableID, 
    //                     'tbl_account_idtbl_account' => $rowdataentrylist['chartaccount'], 
    //                     'tbl_account_detail_idtbl_account_detail' => $rowdataentrylist['detailaccount']
    //                 ];
                    
    //                 $this->db->insert('tbl_account_paysettle_entry', $datalist);
    //             }

    //             if(!empty($invoicedata)){
    //                 foreach($invoicedata as $rowinvoicedata){
    //                     $narration=$rowinvoicedata->supplier.' - '.$rowinvoicedata->invoiceno;
    //                     $invoicetotal=str_replace(',', '', $rowinvoicedata->amount);

    //                     if($chequecashamount>=$invoicetotal){
    //                         $invoicepayamount=$invoicetotal;
    //                         $chequecashamount=$chequecashamount-$invoicetotal;
    //                     }
    //                     else{
    //                         $invoicepayamount=$chequecashamount;
    //                         $chequecashamount=0;
    //                     }

    //                     $datasub = array(
    //                         'batchno'=> $batchno, 
    //                         'narration'=> $narration, 
    //                         'amount'=> $invoicepayamount, 
    //                         'invoiceno'=> $rowinvoicedata->invid, 
    //                         'status'=> '1', 
    //                         'insertdatetime'=> $updatedatetime, 
    //                         'tbl_user_idtbl_user'=> $userID,
    //                         'tbl_account_paysettle_idtbl_account_paysettle'=> $payableID,
    //                     );

    //                     $this->db->insert('tbl_account_paysettle_info', $datasub);
    //                 }
    //             }

    //             if($issuechequeID != 0):
    //                 $datachequehas = array(
    //                     'tbl_account_paysettle_idtbl_account_paysettle'=> $payableID, 
    //                     'tbl_cheque_issue_idtbl_cheque_issue'=> $issuechequeID,
    //                 );
    //                 $this->db->insert('tbl_account_paysettle_has_tbl_cheque_issue', $datachequehas);
    //             endif;

    //             $this->db->trans_complete();

    //             if ($this->db->trans_status() === TRUE) {
    //                 $this->db->trans_commit();
                    
    //                 $actionObj=new stdClass();
    //                 $actionObj->icon='fas fa-save';
    //                 $actionObj->title='';
    //                 $actionObj->message='Record Added Successfully';
    //                 $actionObj->url='';
    //                 $actionObj->target='_blank';
    //                 $actionObj->type='success';

    //                 $actionJSON=json_encode($actionObj);
                    
    //                 $obj=new stdClass();
    //                 $obj->status=1;
    //                 $obj->action=$actionJSON;

    //                 echo json_encode($obj);
    //             } else {
    //                 $this->db->trans_rollback();

    //                 $actionObj=new stdClass();
    //                 $actionObj->icon='fas fa-warning';
    //                 $actionObj->title='';
    //                 $actionObj->message='Record Error';
    //                 $actionObj->url='';
    //                 $actionObj->target='_blank';
    //                 $actionObj->type='danger';

    //                 $actionJSON=json_encode($actionObj);
                    
    //                 $obj=new stdClass();
    //                 $obj->status=0;
    //                 $obj->action=$actionJSON;

    //                 echo json_encode($obj);
    //             }
    //         }
    //         else{
    //             $this->db->trans_rollback();

    //             $actionObj=new stdClass();
    //             $actionObj->icon='fas fa-warning';
    //             $actionObj->title='';
    //             $actionObj->message='Record Error, Batch no defind by system';
    //             $actionObj->url='';
    //             $actionObj->target='_blank';
    //             $actionObj->type='danger';

    //             $actionJSON=json_encode($actionObj);
                
    //             $obj=new stdClass();
    //             $obj->status=0;
    //             $obj->action=$actionJSON;

    //             echo json_encode($obj);
    //         }
    //     }
    //     else{
    //         $this->db->trans_rollback();

    //         $actionObj=new stdClass();
    //         $actionObj->icon='fas fa-warning';
    //         $actionObj->title='';
    //         $actionObj->message='Record Error, Something wrong. Cheque detail not available';
    //         $actionObj->url='';
    //         $actionObj->target='_blank';
    //         $actionObj->type='danger';

    //         $actionJSON=json_encode($actionObj);
            
    //         $obj=new stdClass();
    //         $obj->status=0;
    //         $obj->action=$actionJSON;

    //         echo json_encode($obj);
    //     }
    // }
    public function Paymentsettleinsertupdate(){
        try {
            $userID              = $_SESSION['userid'];
            $detailaccount       = 0;
            $chartaccount        = 0;
            $chequeissuestatus   = 0;
            $issuechequeID       = 0;

            $company             = $this->input->post('company');
            $branch              = $this->input->post('branch');
            $supplier            = $this->input->post('supplier');
            $payabletype         = $this->input->post('payabletype');
            $accounttype         = $this->input->post('accounttype');
            $chequedate          = !empty($this->input->post('chequedate')) ? $this->input->post('chequedate') : '';
            $chartofdetailaccount = $this->input->post('chartofdetailaccount');
            $narration           = $this->input->post('narration');
            $invoicepayamount    = str_replace(',', '', $this->input->post('invoicepayamount'));
            $paiddate            = $this->input->post('paiddate');
            $paidamount          = str_replace(',', '', $this->input->post('paidamount'));
            $invoicedata         = json_decode($this->input->post('tableData'));
            $advanceedata        = json_decode($this->input->post('tableAdvData'));
            $voucherdata         = json_decode($this->input->post('tableVoucherData'));
            $postdated           = $this->input->post('postdated');
            $payablefilter       = $this->input->post('payablefilter');
            $supplierAccountType = $this->input->post('supplierAccountType');
            $checkaccountpay     = $this->input->post('checkaccountpay');
            $chequepayee         = $this->input->post('chequepayee');
            $checkadvanced       = $this->input->post('checkadvanced');
            $advancesupplierID         = $this->input->post('advancesupplierID');
            $recordOption        = $this->input->post('recordOption');
            $recordID            = !empty($this->input->post('recordID')) ? $this->input->post('recordID') : '';

            $chequecashamount = $paidamount;
            $updatedatetime   = date('Y-m-d H:i:s');
            $today            = date('Y-m-d');

            if ($accounttype == 1)      { $chartaccount  = $chartofdetailaccount; }
            else if ($accounttype == 2) { $detailaccount = $chartofdetailaccount; }

            // ── Insert only: resolve master, batch, payment receipt no ────────────
            if ($recordOption == 1) {
                $masterdata = get_account_period_acco_date($company, $branch, $paiddate);

                if (empty($masterdata) || empty($masterdata->idtbl_master)) {
                    throw new Exception('Record Error, Account period not found for the given date');
                }

                $prefix    = generate_prefix($company, $branch, $paiddate, 'PS');
                $batchno   = tr_batch_num($prefix, $branch);
                $masterID  = $masterdata->idtbl_master ? $masterdata->idtbl_master : '';

                if (empty($batchno)) {
                    throw new Exception('Record Error, Batch no could not be defined by system');
                }

                $this->db->select('tbl_finacial_year.year');
                $this->db->from('tbl_master');
                $this->db->join('tbl_finacial_year', 'tbl_finacial_year.idtbl_finacial_year = tbl_master.tbl_finacial_year_idtbl_finacial_year', 'left');
                $this->db->where('tbl_master.idtbl_master', $masterID);

                $respondyear = $this->db->get();

                if (!$respondyear || $respondyear->num_rows() == 0) {
                    throw new Exception('Record Error, Financial year not found');
                }

                $financialYear = substr($respondyear->row(0)->year, -2);
                $payreceiptno  = tr_batch_num('PAY' . $financialYear, $branch);
                $payreceiptno  = preg_replace('/^(.{5})00/', '$1', $payreceiptno);
            }

            $this->db->trans_begin();

            // ── Cheque number resolution ──────────────────────────────────────────
            if ($payabletype == 2) {
                $this->db->select('`idtbl_cheque_issue`');
                $this->db->from('tbl_cheque_issue');
                $this->db->join('tbl_cheque_info', 'tbl_cheque_info.idtbl_cheque_info = tbl_cheque_issue.tbl_cheque_info_idtbl_cheque_info', 'left');
                $this->db->where('tbl_cheque_info.tbl_account_idtbl_account', $chartofdetailaccount);
                $this->db->where('tbl_cheque_info.status', 1);
                $this->db->where('tbl_cheque_issue.chequeallocate', 0);
                $respondchequeissue = $this->db->get();

                if ($respondchequeissue->num_rows() > 0) {
                    $issuechequeID = $respondchequeissue->row(0)->idtbl_cheque_issue;

                    $datachequeissue = array(
                        'chepaytype'     => '1',
                        'chedate'        => $chequedate,
                        'narration'      => $narration,
                        'amount'         => $chequecashamount,
                        'chequeallocate' => '1',
                        'chequecross'    => $checkaccountpay,
                        'chequepay'      => $chequepayee,
                        'updatedatetime' => $updatedatetime,
                        'updateuser'     => $userID
                    );

                    $this->db->where('idtbl_cheque_issue', $respondchequeissue->row(0)->idtbl_cheque_issue);
                    $this->db->update('tbl_cheque_issue', $datachequeissue);

                    $this->db->where('tbl_cheque_issue_idtbl_cheque_issue', $issuechequeID);
                    $this->db->delete('tbl_account_paysettle_has_tbl_cheque_issue');

                    $chequeissuestatus = 1;
                } else {
                    $this->db->select('tbl_bank_idtbl_bank, tbl_bank_branch_idtbl_bank_branch');
                    $this->db->from('tbl_cheque_info');
                    $this->db->where('tbl_account_idtbl_account', $chartofdetailaccount);
                    $this->db->where('status', 1);
                    $this->db->group_by('tbl_bank_idtbl_bank');
                    $this->db->limit(1);

                    $respondbank = $this->db->get();

                    if ($respondbank->num_rows() > 0) {
                        $bankID   = $respondbank->row(0)->tbl_bank_idtbl_bank;
                        $branchID = $respondbank->row(0)->tbl_bank_branch_idtbl_bank_branch;

                        $sqlcheque = "SELECT tbl_cheque_info.idtbl_cheque_info, IFNULL(LPAD(drv.chno+1, 6, '0'), LPAD(tbl_cheque_info.startno, 6, '0')) AS chno FROM tbl_cheque_info LEFT OUTER JOIN (SELECT tbl_cheque_info_idtbl_cheque_info, max(CAST(chequeno AS UNSIGNED)) AS chno FROM tbl_cheque_issue GROUP BY tbl_cheque_info_idtbl_cheque_info) AS drv ON tbl_cheque_info.idtbl_cheque_info=drv.tbl_cheque_info_idtbl_cheque_info WHERE tbl_cheque_info.tbl_bank_idtbl_bank=? AND tbl_cheque_info.tbl_bank_branch_idtbl_bank_branch=? AND tbl_account_idtbl_account=? AND IFNULL(drv.chno, 0)<CAST(tbl_cheque_info.endno AS UNSIGNED) AND tbl_cheque_info.status=? LIMIT 1";
                        $respondcheque = $this->db->query($sqlcheque, array($bankID, $branchID, $chartofdetailaccount, 1));

                        if (!empty($respondcheque->result())) {
                            $chequeissuestatus = 1;
                            $chequeno          = $respondcheque->row(0)->chno;
                            $chequeinfoID      = $respondcheque->row(0)->idtbl_cheque_info;

                            $datachequeissue = array(
                                'chepaytype'                       => '1',
                                'chedate'                          => $chequedate,
                                'chequeno'                         => $chequeno,
                                'narration'                        => $narration,
                                'amount'                           => $chequecashamount,
                                'chequeallocate'                   => '1',
                                'chequereturn'                     => '0',
                                'chequecross'                      => $checkaccountpay,
                                'chequepay'                        => $chequepayee,
                                'status'                           => '1',
                                'insertdatetime'                   => $updatedatetime,
                                'tbl_user_idtbl_user'              => $userID,
                                'tbl_cheque_info_idtbl_cheque_info' => $chequeinfoID
                            );

                            $this->db->insert('tbl_cheque_issue', $datachequeissue);
                            $issuechequeID = $this->db->insert_id();

                            if (!$issuechequeID) {
                                throw new Exception('Failed to insert cheque issue record');
                            }
                        }
                    }
                }
            } else {
                $chequeissuestatus = 1;
            }

            if ($chequeissuestatus != 1) {
                throw new Exception('Record Error, Something wrong. Cheque detail not available');
            }

            // ── Get Creditor Account ──────────────────────────────────────────────
            $this->db->where('tbl_account_allocation.companybank', $company);
            $this->db->where('tbl_account_allocation.branchcompanybank', $branch);
            $this->db->where('tbl_account.specialcate', 34);
            $this->db->where('tbl_account.status', 1);
            $this->db->where('tbl_account_allocation.status', 1);
            $this->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
            $this->db->select('`tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`');
            $this->db->from('tbl_account');
            $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');

            $respondcreditor = $this->db->get();

            // ── Resolve supplier credit account ───────────────────────────────────
            if ($payablefilter == 1) {
                $this->db->select('tbl_account_detail_idtbl_account_detail as accountid');
                $this->db->from('tbl_account_detail_other');
                $this->db->where('tbl_company_idtbl_company', $company);
                $this->db->where('tbl_company_branch_idtbl_company_branch', $branch);
                $this->db->where('otheroptiontype', 1);
                $this->db->where('otheroption', $supplier);
                $respondcreditacc = $this->db->get();
            } else if ($payablefilter == 2 || $payablefilter == 3) {
                if ($supplierAccountType == 1) {
                    $this->db->select('idtbl_account as accountid');
                    $this->db->from('tbl_account');
                    $this->db->where('idtbl_account', $supplier);
                    $this->db->where('status', 1);
                    $respondcreditacc = $this->db->get();
                } else if ($supplierAccountType == 2) {
                    $this->db->select('idtbl_account_detail as accountid');
                    $this->db->from('tbl_account_detail');
                    $this->db->where('idtbl_account_detail', $supplier);
                    $this->db->where('status', 1);
                    $respondcreditacc = $this->db->get();
                }
            }

            // ── Insert main paysettle record ──────────────────────────────────────
            $data = array(
                'date'                                    => $paiddate,
                'paymentno'                               => $payreceiptno,
                'batchno'                                 => $batchno,
                'supplier'                                => $payablefilter == 1 ? $supplier : '',
                'totalpayment'                            => $paidamount,
                'remark'                                  => $narration,
                'postdatedstatus'                         => $postdated,
                'poststatus'                              => '0',
                'paysettlefiltertype'                     => $payablefilter,
                'status'                                  => '1',
                'insertdatetime'                          => $updatedatetime,
                'tbl_user_idtbl_user'                     => $userID,
                'tbl_receivable_type_idtbl_receivable_type' => $payabletype,
                'tbl_company_idtbl_company'               => $company,
                'tbl_company_branch_idtbl_company_branch' => $branch,
                'tbl_master_idtbl_master'                 => $masterID,
                'tbl_account_idtbl_account'               => $chartaccount,
                'tbl_account_detail_idtbl_account_detail' => $detailaccount,
            );

            $this->db->insert('tbl_account_paysettle', $data);
            $payableID = $this->db->insert_id();

            if (!$payableID) {
                throw new Exception('Failed to insert main paysettle record');
            }

            // ── Build entry list ──────────────────────────────────────────────────
            $dataentrylist = [];

            // Credit entry
            $dataentrylist[] = [
                'tratype'       => 'C',
                'amount'        => $paidamount,
                'narration'     => $narration,
                'chartaccount'  => $chartaccount,
                'detailaccount' => $detailaccount
            ];
            
            // Debit entry
            if ($payablefilter == 3) {
                foreach($voucherdata as $rowvoucherdata){
                    $dataentrylist[] = [
                        'tratype'       => 'D',
                        'amount'        => str_replace(',', '', $rowvoucherdata->amount),
                        'narration'     => $rowvoucherdata->desc,
                        'chartaccount'  => $rowvoucherdata->accounttype == 1 ? $rowvoucherdata->accountid : 0,
                        'detailaccount' => $rowvoucherdata->accounttype == 2 ? $rowvoucherdata->accountid : 0
                    ];

                    if($rowvoucherdata->advancestatus == 1){
                        $checkadvanced = 1;
                        $advancesupplierID = $rowvoucherdata->advancesupplierid;
                    }
                }
            }
            else{
                if (empty($respondcreditacc->result())) {
                    if (empty($respondcreditor->result())) {
                        throw new Exception("You don't have trade creditor account or creditor account");
                    }

                    $dataentrylist[] = [
                        'tratype'       => 'D',
                        'amount'        => $paidamount,
                        'narration'     => $narration,
                        'chartaccount'  => $respondcreditor->row(0)->idtbl_account,
                        'detailaccount' => 0
                    ];
                } else {
                    if ($supplierAccountType == 1) {
                        $chartaccount  = $respondcreditacc->row(0)->accountid;
                        $detailaccount = 0;
                    } else if ($supplierAccountType == 2) {
                        $chartaccount  = 0;
                        $detailaccount = $respondcreditacc->row(0)->accountid;
                    }
                    else{
                        $chartaccount  = 0;
                        $detailaccount = $respondcreditacc->row(0)->accountid;
                    }

                    $dataentrylist[] = [
                        'tratype'       => 'D',
                        'amount'        => $paidamount,
                        'narration'     => $narration,
                        'chartaccount'  => $chartaccount,
                        'detailaccount' => $detailaccount
                    ];
                }
            }

            // ── Insert entry lines ────────────────────────────────────────────────
            foreach ($dataentrylist as $rowdataentrylist) {
                $datalist = [
                    'transdate'                                  => $paiddate,
                    'batchno'                                    => $batchno,
                    'tratype'                                    => $rowdataentrylist['tratype'],
                    'amount'                                     => $rowdataentrylist['amount'],
                    'narration'                                  => $rowdataentrylist['narration'],
                    'poststatus'                                 => '0',
                    'status'                                     => '1',
                    'insertdatetime'                             => $updatedatetime,
                    'tbl_user_idtbl_user'                        => $userID,
                    'tbl_company_idtbl_company'                  => $company,
                    'tbl_company_branch_idtbl_company_branch'    => $branch,
                    'tbl_master_idtbl_master'                    => $masterID,
                    'tbl_account_paysettle_idtbl_account_paysettle' => $payableID,
                    'tbl_account_idtbl_account'                  => $rowdataentrylist['chartaccount'],
                    'tbl_account_detail_idtbl_account_detail'    => $rowdataentrylist['detailaccount']
                ];

                $this->db->insert('tbl_account_paysettle_entry', $datalist);
            }

            // ── Insert invoice settlement lines ───────────────────────────────────
            if (!empty($invoicedata)) {
                foreach ($invoicedata as $rowinvoicedata) {
                    $narration    = $rowinvoicedata->supplier . ' - ' . $rowinvoicedata->invoiceno;
                    $invoicetotal = str_replace(',', '', $rowinvoicedata->amount);

                    if ($chequecashamount >= $invoicetotal) {
                        $invoicepayamount  = $invoicetotal;
                        $chequecashamount  = $chequecashamount - $invoicetotal;
                        $invoicecomplete  = 1;
                    } else {
                        $invoicepayamount = $chequecashamount;
                        $chequecashamount = 0;
                        $invoicecomplete  = 0;
                    }

                    $datasub = array(
                        'batchno'                                        => $batchno,
                        'narration'                                      => $narration,
                        'amount'                                         => $invoicepayamount,
                        'invoiceno'                                      => $rowinvoicedata->invid,
                        'status'                                         => '1',
                        'insertdatetime'                                 => $updatedatetime,
                        'tbl_user_idtbl_user'                            => $userID,
                        'tbl_account_paysettle_idtbl_account_paysettle'  => $payableID,
                        'tbl_account_transaction_manual_idtbl_account_transaction_manual'  => $payablefilter == 2 ? $rowinvoicedata->supid : 0,
                    );

                    $this->db->insert('tbl_account_paysettle_info', $datasub);

                    if($payablefilter == 2 && $chequecashamount == 0){
                        // Update jurnal payment paysettle status
                        $datadetail = array(
                            'payablesettle' => '1',
                            'updateuser'   => $userID,
                            'updatedatetime'   => $updatedatetime
                        );

                        $this->db->where('idtbl_account_transaction_manual', $rowinvoicedata->supid);
                        $this->db->update('tbl_account_transaction_manual', $datadetail);
                    }

                    if($payablefilter == 1 && $invoicecomplete == 1){
                        // Update jurnal payment paysettle status
                        $datadetail = array(
                            'paystatus' => '1',
                            'updateuser'   => $userID,
                            'updatedatetime'   => $updatedatetime
                        );

                        $this->db->where('grnno', $rowinvoicedata->invoiceno);
                        $this->db->update('tbl_expence_info', $datadetail);
                    }
                }
            }

            // ── Link cheque issue if applicable ───────────────────────────────────
            if ($issuechequeID != 0) {
                $datachequehas = array(
                    'tbl_account_paysettle_idtbl_account_paysettle' => $payableID,
                    'tbl_cheque_issue_idtbl_cheque_issue'           => $issuechequeID,
                );
                $this->db->insert('tbl_account_paysettle_has_tbl_cheque_issue', $datachequehas);
            }

            // ── Insert advance payment ────────────────────────────────────────────
            if($checkadvanced == 1){
                foreach ($dataentrylist as $rowdataentrylist) {
                    if($rowdataentrylist['tratype'] == 'D'){
                        $advancechartaccount = $rowdataentrylist['chartaccount'];
                        $advancedetailaccount = $rowdataentrylist['detailaccount'];
                    }
                }

                $dataadvance = array(
                    'date'                                          => $paiddate,
                    'batchno'                                       => $batchno,
                    'supplier'                                      => $advancesupplierID,
                    'amount'                                        => $paidamount,
                    'setoffstatus'                                  => '0',
                    'status'                                        => '1',
                    'insertdatetime'                                => $updatedatetime,
                    'tbl_user_idtbl_user'                           => $userID,
                    'tbl_account_paysettle_idtbl_account_paysettle' => $payableID,
                    'tbl_master_idtbl_master'                       => $masterID,
                    'tbl_company_idtbl_company'                     => $company,
                    'tbl_company_branch_idtbl_company_branch'       => $branch,
                    'tbl_account_idtbl_account'                     => $advancechartaccount,
                    'tbl_account_detail_idtbl_account_detail'       => $advancedetailaccount,
                );

                $this->db->insert('tbl_account_paysettle_advance', $dataadvance);
            }

            // ── Set off advance payment ──────────────────────────────────────────── 
            if(!empty($advanceedata)){
                
                $remainingInvoices = [];
                foreach($invoicedata as $rowinvoicedata){
                    $remainingInvoices[] = [
                        'invoiceno'      => $rowinvoicedata->invid,
                        'invoicetotal'   => str_replace(',', '', $rowinvoicedata->amount),
                        'remaining'      => str_replace(',', '', $rowinvoicedata->amount), // remaining amount to be set off
                    ];
                }

                foreach($advanceedata as $rowadvanceedata):
                    $advrecordID    = $rowadvanceedata->advid;
                    $paysetno       = $rowadvanceedata->paysetno;
                    $advAmount      = str_replace(',', '', $rowadvanceedata->amount);
                    $remainingAdv   = $advAmount; // remaining advance amount to be set off

                    foreach($remainingInvoices as &$invoice){

                        // Skip if invoice already fully set off
                        if($invoice['remaining'] <= 0) continue;

                        // Skip if advance already fully used
                        if($remainingAdv <= 0) break;

                        // Get tbl_expence_info id
                        $this->db->select('idtbl_expence_info');
                        $this->db->from('tbl_expence_info');
                        $this->db->where('grnno', $invoice['invoiceno']);
                        $respondex      = $this->db->get();
                        $expenceinfo    = $respondex->row();

                        if(!$expenceinfo) continue;

                        $expenceInfoId = $expenceinfo->idtbl_expence_info;

                        // ── Calculate set off amount ──────────────────────────────
                        // Case 1: Advance >= Invoice remaining  → set off full invoice remaining
                        // Case 2: Advance <  Invoice remaining  → set off full advance remaining
                        $setoffAmount = min($remainingAdv, $invoice['remaining']);

                        // ── Determine set off status ──────────────────────────────
                        // Advance fully used
                        $advSetoffStatus = ($remainingAdv - $setoffAmount <= 0) ? '1' : '0'; // 1 = fully setoff, 0 = partial

                        // ── INSERT tbl_account_paysettle_advance_has_tbl_expence_info ──
                        $insertData = [
                            'tbl_account_paysettle_advance_idtbl_account_paysettle_advance'  => $advrecordID,
                            'tbl_expence_info_idtbl_expence_info'                            => $expenceInfoId,
                            'setoffamount'                                                   => $setoffAmount,
                            'status'                                                         => '1',
                            'setoffdate'                                                     => $paiddate,
                            'insertdatetime'                                                 => $updatedatetime,
                            'tbl_user_idtbl_user'                                            => $userID,
                            'tbl_account_paysettle_idtbl_account_paysettle'                  => $payableID,
                        ];
                        $this->db->insert('tbl_account_paysettle_advance_has_tbl_expence_info', $insertData);

                        // ── Deduct amounts ────────────────────────────────────────
                        $remainingAdv          -= $setoffAmount;
                        $invoice['remaining']  -= $setoffAmount;
                    }
                    unset($invoice); // unset reference

                    // ── UPDATE tbl_account_paysettle_advance setoffstatus ─────────
                    // Check advance fully used or partial
                    if($remainingAdv <= 0){
                        $setoffstatus = '1'; // fully set off
                    } else if($remainingAdv < $advAmount){
                        $setoffstatus = '1'; // partial set off
                    } else {
                        $setoffstatus = '0'; // not set off
                    }

                    $updateData = [
                        'setoffstatus'   => $setoffstatus,
                        'updateuser'     => $userID,
                        'updatedatetime' => $updatedatetime,
                    ];
                    $this->db->where('idtbl_account_paysettle_advance', $advrecordID);
                    $this->db->update('tbl_account_paysettle_advance', $updateData);

                endforeach;
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                $this->_jsonResponse(1, 'fas fa-save', 'Record Added Successfully', 'success');
            } else {
                $this->db->trans_rollback();
                throw new Exception('Record Error');
            }

        } catch (Exception $e) {
            if ($this->db->trans_enabled) {
                $this->db->trans_rollback();
            }
            $this->_jsonResponse(0, 'fas fa-warning', $e->getMessage(), 'danger');
        }
    }
    public function Getviewpostinfo(){
        $recordID=$this->input->post('recordID');
        $updatedatetime=date('Y-m-d H:i:s');

        $configdata = getconfigdata('payable_search');
        
        $tablename = $configdata->row(0)->tbl_name;
        $column1   = $configdata->row(0)->col_name;
        $column2   = $configdata->row(1)->col_name;

        $has_table = !empty($tablename) ? 1 : 0;

        $data = array(
            'postviewtime'=> $updatedatetime
        );

        $this->db->where('idtbl_account_paysettle', $recordID);
        $this->db->where('poststatus', 0);
        $this->db->update('tbl_account_paysettle', $data);

        $this->db->select("tbl_account_paysettle.*, tbl_company.company, tbl_company_branch.branch, IF($has_table = 0, '', $tablename.$column2) AS suppliername, tbl_account.accountno AS `chartaccount`, tbl_account.accountname AS `chartaccountname`, tbl_account_detail.accountno AS `detailaccount`, tbl_account_detail.accountname AS `detailaccountname`, tbl_cheque_issue.chedate, tbl_cheque_issue.chequeno");
        $this->db->from('tbl_account_paysettle');
        $this->db->join('tbl_company', 'tbl_company.idtbl_company = tbl_account_paysettle.tbl_company_idtbl_company', 'left');
        $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_account_paysettle.tbl_company_branch_idtbl_company_branch', 'left');
        if(!empty($tablename)):
            $this->db->join("$tablename", "$tablename.$column1 = tbl_account_paysettle.supplier", 'left');
        endif;
        $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_account_paysettle.tbl_account_idtbl_account', 'left');
        $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_account_paysettle.tbl_account_detail_idtbl_account_detail', 'left');
        $this->db->join('tbl_account_paysettle_has_tbl_cheque_issue', 'tbl_account_paysettle_has_tbl_cheque_issue.tbl_account_paysettle_idtbl_account_paysettle = tbl_account_paysettle.idtbl_account_paysettle', 'left');
        $this->db->join('tbl_cheque_issue', 'tbl_cheque_issue.idtbl_cheque_issue = tbl_account_paysettle_has_tbl_cheque_issue.tbl_cheque_issue_idtbl_cheque_issue', 'left');
        $this->db->where('tbl_account_paysettle.idtbl_account_paysettle', $recordID);
        // $this->db->where('tbl_account_payable_main.status', 1);

        $respond=$this->db->get();

        $this->db->select('tbl_account_paysettle_entry.*, tbl_account_detail.accountno, tbl_account_detail.accountname, tbl_account.accountno AS chartaccountno, tbl_account.accountname AS chartaccountname');
        $this->db->from('tbl_account_paysettle_entry');
        $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_account_paysettle_entry.tbl_account_detail_idtbl_account_detail', 'left');
        $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_account_paysettle_entry.tbl_account_idtbl_account', 'left');
        $this->db->where('tbl_account_paysettle_entry.tbl_account_paysettle_idtbl_account_paysettle', $recordID);
        // $this->db->where('tbl_account_receivable.status', 1);

        $respondpayinfo=$this->db->get();

        $this->db->select('`invoiceno`, `narration`, `amount`');
        $this->db->from('tbl_account_paysettle_info');
        $this->db->where('tbl_account_paysettle_idtbl_account_paysettle', $recordID);
        // $this->db->where('tbl_account_payable.status', 1);

        $respondinvoiceinfo=$this->db->get();

        if($respond->row(0)->tbl_receivable_type_idtbl_receivable_type!=2){
            if($respond->row(0)->tbl_account_detail_idtbl_account_detail>0){
                $accountno=$respond->row(0)->detailaccount; 
                $accountname=$respond->row(0)->detailaccountname;
            }
            else{
                $accountno=$respond->row(0)->chartaccount; 
                $accountname=$respond->row(0)->chartaccountname;
            }
            $chequedate='';
            $chequeno='';
        }
        else if($respond->row(0)->tbl_receivable_type_idtbl_receivable_type==2){
            if($respond->row(0)->tbl_account_detail_idtbl_account_detail>0){
                $accountno=$respond->row(0)->detailaccount; 
                $accountname=$respond->row(0)->detailaccountname;
            }
            else{
                $accountno=$respond->row(0)->chartaccount; 
                $accountname=$respond->row(0)->chartaccountname;
            }
            $chequedate=$respond->row(0)->chedate;
            $chequeno=$respond->row(0)->chequeno;
        }

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
        }
        // if($respond->row(0)->editstatus==1){
        // $html.='
        // <div class="row">
        //     <div class="col">
        //         <div class="alert alert-danger" role="alert">
        //         <i class="fas fa-exclamation-triangle mr-2"></i> Record in editable mode. You cannot change anything about the record.
        //         </div> 
        //     </div>
        // </div>';
        // }
        if($respond->row(0)->postdatedstatus==1 && $respond->row(0)->chedate > date('Y-m-d')){
        $html.='
        <div class="row">
            <div class="col">
                <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i> Post-dated Cheque. You cannot post this transaction until '.$respond->row(0)->chedate.'.
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
                <label class="small my-0">'.$respond->row(0)->date.'</label><br>
                <label class="small font-weight-bold my-0">Company/Branch: </label>
                <label class="small my-0">'.$respond->row(0)->company.'-'.$respond->row(0)->branch.'</label><br>
                <label class="small font-weight-bold my-0">Account No: </label>
                <label class="small my-0">'.$accountno.' - '.$accountname.'</label>
            </div>
            <div class="col">
                <label class="small font-weight-bold my-0">Supplier/ Account: </label>
                <label class="small my-0">' . ($respond->row(0)->paysettlefiltertype == 2 ? 'Journal payment' : ($respond->row(0)->paysettlefiltertype == 3 ? 'Voucher payment' : $respond->row(0)->suppliername)) . '</label><br>
                <label class="small font-weight-bold my-0">Cheque Date: </label>
                <label class="small my-0">'.$chequedate.'</label><br>
                <label class="small font-weight-bold my-0">Cheque No: </label>
                <label class="small my-0">'.$chequeno.'</label><br>
                <label class="small font-weight-bold my-0">Amount: </label>
                <label class="small my-0">'.number_format($respond->row(0)->totalpayment, 2).'</label>
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
        </div>';
        if(!empty($respondinvoiceinfo->result())){
            $html.='<div class="row">
                <div class="col">
                    <h6 class="small title-style my-3"><span>Payable Invoice Information</span></h6>
                    <table class="table  table-striped table-sm nowrap small">
                        <thead>
                            <tr>
                                <th>Supplier | Journal</th>
                                <th>Invoice No</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>';
                        foreach($respondinvoiceinfo->result() as $rowdatainfo){
                            $html.='
                            <tr>
                                <td>' . ($respond->row(0)->paysettlefiltertype == 2 ? 'Journal payment' : $respond->row(0)->suppliername) . '</td>
                                <td>'.$rowdatainfo->invoiceno.'</td>
                                <td class="text-right">'.number_format($rowdatainfo->amount, 2).'</td>
                            </tr>
                            ';
                        }
                        $html.='</tbody>
                    </table>
                </div>
            </div>';
        }
        if($respond->row(0)->poststatus==1){
            $html.='<div class="alert alert-success" role="alert">
                <h4 class="alert-heading">Posted!</h4>
                <p>The journal entry you are attempting to save has already been posted to the system. Please check your records or contact your administrator for assistance.</p>
            </div>';
        }

        $obj=new stdClass();
        $obj->html=$html;
        $obj->editablestatus=0;

        echo json_encode($obj);
    }
    // public function Paymentsettleposting(){
    //     $recordID=$this->input->post('recordID');
    //     $updatedatetime=date('Y-m-d H:i:s');
    //     $userID=$_SESSION['userid'];

    //     $i=0;

    //     $this->db->select('tbl_account_paysettle.date, tbl_account_paysettle.batchno, tbl_account_paysettle.totalpayment, tbl_account_paysettle.poststatus, tbl_account_paysettle.status, tbl_account_paysettle.postviewtime, tbl_account_paysettle.postviewtime, tbl_account_paysettle.updatedatetime, tbl_account_paysettle.tbl_company_idtbl_company, tbl_account_paysettle.tbl_company_branch_idtbl_company_branch, tbl_account_paysettle.tbl_master_idtbl_master, tbl_account_paysettle.supplier, tbl_account_paysettle.tbl_account_idtbl_account, tbl_account_paysettle.tbl_account_detail_idtbl_account_detail, tbl_account_paysettle.remark, tbl_account_paysettle.postdatedstatus, tbl_cheque_issue.chedate, tbl_cheque_issue.chequeno');
    //     $this->db->from('tbl_account_paysettle');
    //     $this->db->join('tbl_account_paysettle_has_tbl_cheque_issue', 'tbl_account_paysettle_has_tbl_cheque_issue.tbl_account_paysettle_idtbl_account_paysettle = tbl_account_paysettle.idtbl_account_paysettle', 'left');
    //     $this->db->join('tbl_cheque_issue', 'tbl_cheque_issue.idtbl_cheque_issue = tbl_account_paysettle_has_tbl_cheque_issue.tbl_cheque_issue_idtbl_cheque_issue', 'left');
    //     $this->db->where('tbl_account_paysettle.idtbl_account_paysettle', $recordID);
    //     $this->db->where('tbl_account_paysettle.status', 1);

    //     $respond=$this->db->get();

    //     if($respond->row(0)->postdatedstatus==1 && $respond->row(0)->chedate > date('Y-m-d')){
    //         $actionObj=new stdClass();
    //         $actionObj->icon='fas fa-warning';
    //         $actionObj->title='';
    //         $actionObj->message='Record Error, You cannot post a post-dated Payment Settle.';
    //         $actionObj->url='';
    //         $actionObj->target='_blank';
    //         $actionObj->type='danger';

    //         $actionJSON=json_encode($actionObj);
            
    //         $obj=new stdClass();
    //         $obj->status=0;
    //         $obj->action=$actionJSON;

    //         echo json_encode($obj);
    //     }
    //     else{
    //         if($respond->row(0)->poststatus==0 && $respond->row(0)->status==1){
    //             if($respond->row(0)->postviewtime>$respond->row(0)->updatedatetime){
    //                 $this->db->trans_begin();
                    
    //                 $data = array(
    //                     'completestatus'=> '1',
    //                     'poststatus'=> '1',
    //                     'postuser'=> $userID,
    //                     'postviewtime'=> NULL
    //                 );
            
    //                 $this->db->where('idtbl_account_paysettle', $recordID);
    //                 $this->db->update('tbl_account_paysettle', $data);

    //                 $i=1;
    //                 //Creditor account Transaction
    //                 $prefix=generate_prefix($respond->row(0)->tbl_company_idtbl_company, $respond->row(0)->tbl_company_branch_idtbl_company_branch, $respond->row(0)->date, 'AT');
    //                 $batchno=tr_batch_num($prefix, $respond->row(0)->tbl_company_branch_idtbl_company_branch);

    //                 $this->db->select('`idtbl_account_paysettle_entry`, `transdate`, `batchno`, `tratype`, `amount`, `narration`, `tbl_master_idtbl_master`, `tbl_company_idtbl_company`, `tbl_company_branch_idtbl_company_branch`, `tbl_account_idtbl_account`, `tbl_account_detail_idtbl_account_detail`');
    //                 $this->db->from('tbl_account_paysettle_entry');
    //                 $this->db->where('tbl_account_paysettle_idtbl_account_paysettle', $recordID);
    //                 $this->db->where('status', 1);

    //                 $responddetail=$this->db->get();

    //                 foreach($responddetail->result() AS $rowdetail){
    //                     $i++;

    //                     $receivedetailID=$rowdetail->idtbl_account_paysettle_entry;
    //                     $tradate=$rowdetail->transdate;
    //                     $segbatchno=$rowdetail->batchno;
    //                     $detailaccount=$rowdetail->tbl_account_detail_idtbl_account_detail;
    //                     $chartaccount=$rowdetail->tbl_account_idtbl_account;
    //                     $company=$rowdetail->tbl_company_idtbl_company;
    //                     $branch=$rowdetail->tbl_company_branch_idtbl_company_branch;
    //                     $masterID=$rowdetail->tbl_master_idtbl_master;
    //                     $amount=$rowdetail->amount;
    //                     $narration=$rowdetail->narration;
    //                     $tratype=$rowdetail->tratype;
                        
    //                     if(!empty($detailaccount)){
    //                         $chartofaccountinfo=get_chart_account_acco_child_account($company, $branch, $detailaccount);
    //                         $chartofaccountID=$chartofaccountinfo->row(0)->idtbl_account;
    //                     }
    //                     else{
    //                         $chartofaccountID=$chartaccount;
    //                     }

    //                     $data = array(
    //                         'tradate'=> $tradate, 
    //                         'batchno'=> $batchno, 
    //                         'trabatchotherno'=> $segbatchno, 
    //                         'tratype'=> 'R', 
    //                         'seqno'=> $i, 
    //                         'crdr'=> $tratype, 
    //                         'accamount'=> $amount, 
    //                         'narration'=> $narration, 
    //                         'totamount'=> $amount, 
    //                         'status'=> '1', 
    //                         'insertdatetime'=> $updatedatetime, 
    //                         'tbl_user_idtbl_user'=> $userID,
    //                         'tbl_account_idtbl_account'=> $chartofaccountID,
    //                         'tbl_master_idtbl_master'=> $masterID,
    //                         'tbl_company_idtbl_company'=> $company,
    //                         'tbl_company_branch_idtbl_company_branch'=> $branch
    //                     );
        
    //                     $this->db->insert('tbl_account_transaction', $data);                    

    //                     $datafull = array(
    //                         'tradate'=> $tradate, 
    //                         'batchno'=> $batchno, 
    //                         'tratype'=> 'R', 
    //                         'crdr'=> $tratype, 
    //                         'accamount'=> $amount, 
    //                         'narration'=> $narration, 
    //                         'totamount'=> $amount, 
    //                         'status'=> '1', 
    //                         'insertdatetime'=> $updatedatetime, 
    //                         'tbl_user_idtbl_user'=> $userID,
    //                         'tbl_account_idtbl_account'=> $chartofaccountID,
    //                         'tbl_master_idtbl_master'=> $masterID,
    //                         'tbl_company_idtbl_company'=> $company,
    //                         'tbl_company_branch_idtbl_company_branch'=> $branch
    //                     );
        
    //                     $this->db->insert('tbl_account_transaction_full', $datafull);

    //                     //Update POST Status Detail
    //                     $datadetail = array(
    //                         'poststatus'=> '1',
    //                         'postuser'=> $userID
    //                     );
                
    //                     $this->db->where('idtbl_account_paysettle_entry', $receivedetailID);
    //                     $this->db->update('tbl_account_paysettle_entry', $datadetail);
    //                 }


    //                 // //Creditor account Transaction
    //                 // $prefix=trans_prefix($respond->row(0)->tbl_company_idtbl_company, $respond->row(0)->tbl_company_branch_idtbl_company_branch);
    //                 // $batchno=tr_batch_num($prefix, $respond->row(0)->tbl_company_branch_idtbl_company_branch);

    //                 // //Get Creditor Account
    //                 // $this->db->where('tbl_account_allocation.companybank', $respond->row(0)->tbl_company_idtbl_company);
    //                 // $this->db->where('tbl_account_allocation.branchcompanybank', $respond->row(0)->tbl_company_branch_idtbl_company_branch);
    //                 // // $this->db->where('tbl_account.tbl_account_type_idtbl_account_type', 2);
    //                 // $this->db->where('tbl_account.specialcate', 34);
    //                 // $this->db->where('tbl_account.status', 1);
    //                 // $this->db->where('tbl_account_allocation.status', 1);
    //                 // $this->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
    //                 // $this->db->select('`tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`');
    //                 // $this->db->from('tbl_account');
    //                 // $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');

    //                 // $respondcreditor=$this->db->get();

    //                 // $datacredit = array(
    //                 //     'tradate'=> $respond->row(0)->date, 
    //                 //     'batchno'=> $batchno, 
    //                 //     'trabatchotherno'=> $respond->row(0)->batchno, 
    //                 //     'tratype'=> 'I', 
    //                 //     'seqno'=> $i, 
    //                 //     'crdr'=> 'D', 
    //                 //     'accamount'=> $respond->row(0)->totalpayment, 
    //                 //     'narration'=> $respond->row(0)->remark, 
    //                 //     'totamount'=> $respond->row(0)->totalpayment, 
    //                 //     'status'=> '1', 
    //                 //     'insertdatetime'=> $updatedatetime, 
    //                 //     'tbl_user_idtbl_user'=> $userID,
    //                 //     'tbl_account_idtbl_account'=> $respondcreditor->row(0)->idtbl_account,
    //                 //     'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
    //                 //     'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
    //                 //     'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
    //                 // );
    //                 // $this->db->insert('tbl_account_transaction', $datacredit);
            
    //                 // $datacreditfull = array(
    //                 //     'tradate'=> $respond->row(0)->date, 
    //                 //     'batchno'=> $batchno, 
    //                 //     'tratype'=> 'I', 
    //                 //     'crdr'=> 'D', 
    //                 //     'accamount'=> $respond->row(0)->totalpayment, 
    //                 //     'narration'=> $respond->row(0)->remark, 
    //                 //     'totamount'=> $respond->row(0)->totalpayment, 
    //                 //     'status'=> '1', 
    //                 //     'insertdatetime'=> $updatedatetime, 
    //                 //     'tbl_user_idtbl_user'=> $userID,
    //                 //     'tbl_account_idtbl_account'=> $respondcreditor->row(0)->idtbl_account,
    //                 //     'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
    //                 //     'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
    //                 //     'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
    //                 // );
    //                 // $this->db->insert('tbl_account_transaction_full', $datacreditfull);

    //                 // //Debit account Transaction

    //                 // if(!empty($respond->row(0)->tbl_account_detail_idtbl_account_detail)){
    //                 //     $chartofaccountinfo=get_chart_account_acco_child_account($respond->row(0)->tbl_company_idtbl_company, $respond->row(0)->tbl_company_branch_idtbl_company_branch, $respond->row(0)->tbl_account_detail_idtbl_account_detail);
    //                 //     $chartofaccountID=$chartofaccountinfo->row(0)->idtbl_account;
    //                 // }
    //                 // else{
    //                 //     $chartofaccountID=$respond->row(0)->tbl_account_idtbl_account;
    //                 // }

    //                 // $i++;
    //                 // $data = array(
    //                 //     'tradate'=> $respond->row(0)->date, 
    //                 //     'batchno'=> $batchno, 
    //                 //     'trabatchotherno'=> $respond->row(0)->batchno, 
    //                 //     'tratype'=> 'I', 
    //                 //     'seqno'=> $i, 
    //                 //     'crdr'=> 'C', 
    //                 //     'accamount'=> $respond->row(0)->totalpayment, 
    //                 //     'narration'=> $respond->row(0)->remark, 
    //                 //     'totamount'=> $respond->row(0)->totalpayment,
    //                 //     'status'=> '1', 
    //                 //     'insertdatetime'=> $updatedatetime, 
    //                 //     'tbl_user_idtbl_user'=> $userID,
    //                 //     'tbl_account_idtbl_account'=> $chartofaccountID,
    //                 //     'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
    //                 //     'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
    //                 //     'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
    //                 // );

    //                 // $this->db->insert('tbl_account_transaction', $data);

    //                 // $datafull = array(
    //                 //     'tradate'=> $respond->row(0)->date, 
    //                 //     'batchno'=> $batchno, 
    //                 //     'tratype'=> 'I', 
    //                 //     'crdr'=> 'C', 
    //                 //     'accamount'=> $respond->row(0)->totalpayment, 
    //                 //     'narration'=> $respond->row(0)->remark, 
    //                 //     'totamount'=> $respond->row(0)->totalpayment,
    //                 //     'status'=> '1', 
    //                 //     'insertdatetime'=> $updatedatetime, 
    //                 //     'tbl_user_idtbl_user'=> $userID,
    //                 //     'tbl_account_idtbl_account'=> $respond->row(0)->tbl_account_idtbl_account,
    //                 //     'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
    //                 //     'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
    //                 //     'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
    //                 // );

    //                 // $this->db->insert('tbl_account_transaction_full', $datafull);

    //                 $this->db->trans_complete();

    //                 if ($this->db->trans_status() === TRUE) {
    //                     $this->db->trans_commit();
                        
    //                     $actionObj=new stdClass();
    //                     $actionObj->icon='fas fa-save';
    //                     $actionObj->title='';
    //                     $actionObj->message='Record Added Successfully';
    //                     $actionObj->url='';
    //                     $actionObj->target='_blank';
    //                     $actionObj->type='success';

    //                     $actionJSON=json_encode($actionObj);
                        
    //                     $obj=new stdClass();
    //                     $obj->status=1;
    //                     $obj->action=$actionJSON;

    //                     echo json_encode($obj);
    //                 } else {
    //                     $this->db->trans_rollback();

    //                     $actionObj=new stdClass();
    //                     $actionObj->icon='fas fa-warning';
    //                     $actionObj->title='';
    //                     $actionObj->message='Record Error';
    //                     $actionObj->url='';
    //                     $actionObj->target='_blank';
    //                     $actionObj->type='danger';

    //                     $actionJSON=json_encode($actionObj);
                        
    //                     $obj=new stdClass();
    //                     $obj->status=0;
    //                     $obj->action=$actionJSON;

    //                     echo json_encode($obj);
    //                 }
    //             }
    //             else{
    //                 $actionObj=new stdClass();
    //                 $actionObj->icon='fas fa-warning';
    //                 $actionObj->title='';
    //                 $actionObj->message='Record Error, Please check this record for information. Because this record was edited before you posted.';
    //                 $actionObj->url='';
    //                 $actionObj->target='_blank';
    //                 $actionObj->type='danger';

    //                 $actionJSON=json_encode($actionObj);
                    
    //                 $obj=new stdClass();
    //                 $obj->status=0;
    //                 $obj->action=$actionJSON;

    //                 echo json_encode($obj);
    //             }
    //         }
    //         else if($respond->row(0)->status==2){
    //             $actionObj=new stdClass();
    //             $actionObj->icon='fas fa-warning';
    //             $actionObj->title='';
    //             $actionObj->message='Record Error, Record Deactivated. Kindly review the status of the record.';
    //             $actionObj->url='';
    //             $actionObj->target='_blank';
    //             $actionObj->type='warning';

    //             $actionJSON=json_encode($actionObj);
                
    //             $obj=new stdClass();
    //             $obj->status=0;
    //             $obj->action=$actionJSON;

    //             echo json_encode($obj);
    //         }
    //         // else if($respond->row(0)->editstatus==1){
    //         //     $actionObj=new stdClass();
    //         //     $actionObj->icon='fas fa-warning';
    //         //     $actionObj->title='';
    //         //     $actionObj->message='Record Error, Record in editable mode. You cannot change anything about the record.';
    //         //     $actionObj->url='';
    //         //     $actionObj->target='_blank';
    //         //     $actionObj->type='danger';

    //         //     $actionJSON=json_encode($actionObj);
                
    //         //     $obj=new stdClass();
    //         //     $obj->status=0;
    //         //     $obj->action=$actionJSON;

    //         //     echo json_encode($obj);
    //         // }
    //         else if($respond->row(0)->poststatus==1){
    //             $actionObj=new stdClass();
    //             $actionObj->icon='fas fa-warning';
    //             $actionObj->title='';
    //             $actionObj->message='Record Error, Record already posted.';
    //             $actionObj->url='';
    //             $actionObj->target='_blank';
    //             $actionObj->type='danger';

    //             $actionJSON=json_encode($actionObj);
                
    //             $obj=new stdClass();
    //             $obj->status=0;
    //             $obj->action=$actionJSON;

    //             echo json_encode($obj);
    //         }
    //     }
    // }
    public function Paymentsettleposting(){
        try {
            $recordID       = $this->input->post('recordID');
            $updatedatetime = date('Y-m-d H:i:s');
            $userID         = $_SESSION['userid'];
            $companyid      = $_SESSION['companyid'];
		    $branchid       = $_SESSION['branchid'];
            $i              = 0;

            if (empty($recordID)) {
                throw new Exception('Record ID is required');
            }

            $this->db->select('tbl_account_paysettle.date, tbl_account_paysettle.batchno, tbl_account_paysettle.totalpayment, tbl_account_paysettle.poststatus, tbl_account_paysettle.status, tbl_account_paysettle.postviewtime, tbl_account_paysettle.updatedatetime, tbl_account_paysettle.tbl_company_idtbl_company, tbl_account_paysettle.tbl_company_branch_idtbl_company_branch, tbl_account_paysettle.tbl_master_idtbl_master, tbl_account_paysettle.supplier, tbl_account_paysettle.tbl_account_idtbl_account, tbl_account_paysettle.tbl_account_detail_idtbl_account_detail, tbl_account_paysettle.remark, tbl_account_paysettle.postdatedstatus, tbl_cheque_issue.chedate, tbl_cheque_issue.chequeno');
            $this->db->from('tbl_account_paysettle');
            $this->db->join('tbl_account_paysettle_has_tbl_cheque_issue', 'tbl_account_paysettle_has_tbl_cheque_issue.tbl_account_paysettle_idtbl_account_paysettle = tbl_account_paysettle.idtbl_account_paysettle', 'left');
            $this->db->join('tbl_cheque_issue', 'tbl_cheque_issue.idtbl_cheque_issue = tbl_account_paysettle_has_tbl_cheque_issue.tbl_cheque_issue_idtbl_cheque_issue', 'left');
            $this->db->where('tbl_account_paysettle.idtbl_account_paysettle', $recordID);
            $this->db->where('tbl_account_paysettle.status', 1);

            $respond = $this->db->get();

            if (!$respond || $respond->num_rows() == 0) {
                throw new Exception('Record not found');
            }

            $record = $respond->row(0);

            // ── Status validation checks ──────────────────────────────────────────
            if ($record->postdatedstatus == 1 && $record->chedate > date('Y-m-d')) {
                throw new Exception('Record Error, You cannot post a post-dated Payment Settle.');
            }

            if ($record->status == 2) {
                throw new Exception('Record Error, Record Deactivated. Kindly review the status of the record.');
            }

            if ($record->poststatus == 1) {
                throw new Exception('Record Error, Record already posted.');
            }

            if (!($record->poststatus == 0 && $record->status == 1)) {
                throw new Exception('Record Error, Invalid record state for posting.');
            }

            if ($record->postviewtime <= $record->updatedatetime) {
                throw new Exception('Record Error, Please check this record for information. Because this record was edited before you posted.');
            }

            // ── Begin Transaction ─────────────────────────────────────────────────
            $this->db->trans_begin();

            // Update main paysettle post status
            $data = array(
                'completestatus' => '1',
                'poststatus'     => '1',
                'postuser'       => $userID,
                'postviewtime'   => NULL
            );

            $this->db->where('idtbl_account_paysettle', $recordID);
            $this->db->update('tbl_account_paysettle', $data);

            // Update main paysettle post status
            $data = array(
                'poststatus'     => '1',
                'postuser'       => $userID
            );

            $this->db->where('tbl_account_paysettle_idtbl_account_paysettle', $recordID);
            $this->db->update('tbl_account_paysettle_advance', $data);

            $i = 1;

            if ($record->postdatedstatus == 1 && $record->chedate <= date('Y-m-d')) {
                $recdate = $record->chedate;
            } else {
                $recdate = $record->date;
            }

            // Generate batch number for account transaction
            $prefix  = generate_prefix($record->tbl_company_idtbl_company, $record->tbl_company_branch_idtbl_company_branch, $recdate, 'AT');
            $batchno = tr_batch_num($prefix, $record->tbl_company_branch_idtbl_company_branch);
            $masterdata = get_account_period_acco_date($companyid, $branchid, $recdate);

            if (empty($masterdata) || empty($masterdata->idtbl_master)) {
                throw new Exception('Record Error, Account period not found for the given date');
            }

            $masterID = $masterdata->idtbl_master ? $masterdata->idtbl_master : '';

            if (empty($batchno)) {
                throw new Exception('Record Error, Batch no could not be defined by system');
            }

            // Fetch paysettle entry lines
            $this->db->select('`idtbl_account_paysettle_entry`, `transdate`, `batchno`, `tratype`, `amount`, `narration`, `tbl_master_idtbl_master`, `tbl_company_idtbl_company`, `tbl_company_branch_idtbl_company_branch`, `tbl_account_idtbl_account`, `tbl_account_detail_idtbl_account_detail`');
            $this->db->from('tbl_account_paysettle_entry');
            $this->db->where('tbl_account_paysettle_idtbl_account_paysettle', $recordID);
            $this->db->where('status', 1);

            $responddetail = $this->db->get();

            if (!$responddetail || $responddetail->num_rows() == 0) {
                throw new Exception('No paysettle entry lines found for this record');
            }

            foreach ($responddetail->result() as $rowdetail) {
                $i++;

                $receivedetailID = $rowdetail->idtbl_account_paysettle_entry;
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

                if (!empty($detailaccount)) {
                    $chartofaccountinfo = get_chart_account_acco_child_account($company, $branch, $detailaccount);
                    if (!$chartofaccountinfo || $chartofaccountinfo->num_rows() == 0) {
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
                    'tratype'                                 => 'P',
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
                    'tratype'                                 => 'P',
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

                $this->db->where('idtbl_account_paysettle_entry', $receivedetailID);
                $this->db->update('tbl_account_paysettle_entry', $datadetail);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                $this->_jsonResponse(1, 'fas fa-save', 'Record Posted Successfully', 'success');
            } else {
                $this->db->trans_rollback();
                throw new Exception('Record Error, Transaction failed');
            }

        } catch (Exception $e) {
            if ($this->db->trans_enabled) {
                $this->db->trans_rollback();
            }
            $this->_jsonResponse(0, 'fas fa-warning', $e->getMessage(), 'danger');
        }
    }
    // public function Paymentsettlestatus($x, $y){
    //     $userID=$_SESSION['userid'];
    //     $recordID=$x;
    //     $type=$y;
    //     $updatedatetime=date('Y-m-d H:i:s');

    //     if($type==1){
    //         $this->db->trans_begin();
    //         $data = array(
    //             'status' => '1',
    //             'updateuser'=> $userID, 
    //             'updatedatetime'=> $updatedatetime
    //         );

    //         $this->db->where('idtbl_account_paysettle', $recordID);
    //         $this->db->update('tbl_account_paysettle', $data);

    //         $datapay = array(
    //             'status' => '1',
    //             'updateuser'=> $userID, 
    //             'updatedatetime'=> $updatedatetime
    //         );

    //         $this->db->where('tbl_account_paysettle_idtbl_account_paysettle', $recordID);
    //         $this->db->update('tbl_account_paysettle_info', $datapay);

    //         $this->db->select('tbl_account_paysettle_has_tbl_cheque_issue.tbl_cheque_issue_idtbl_cheque_issue');
    //         $this->db->from('tbl_account_paysettle_has_tbl_cheque_issue');
    //         $this->db->where('tbl_account_paysettle_has_tbl_cheque_issue.tbl_account_paysettle_idtbl_account_paysettle', $recordID);

    //         $respondcheque=$this->db->get();

    //         if($respondcheque->num_rows()>0){
    //             $datacheque = array(
    //                 'status' => '1',
    //                 'updateuser'=> $userID, 
    //                 'updatedatetime'=> $updatedatetime
    //             );

    //             $this->db->where('idtbl_cheque_issue', $respondcheque->row(0)->tbl_cheque_issue_idtbl_cheque_issue);
    //             $this->db->update('tbl_cheque_issue', $datacheque);
    //         }

    //         $this->db->trans_complete();

    //         if ($this->db->trans_status() === TRUE) {
    //             $this->db->trans_commit();
                
    //             $actionObj=new stdClass();
    //             $actionObj->icon='fas fa-check';
    //             $actionObj->title='';
    //             $actionObj->message='Record Activate Successfully';
    //             $actionObj->url='';
    //             $actionObj->target='_blank';
    //             $actionObj->type='success';

    //             $actionJSON=json_encode($actionObj);
                
    //             $this->session->set_flashdata('msg', $actionJSON);
    //             redirect('Paymentsettle');                
    //         } else {
    //             $this->db->trans_rollback();

    //             $actionObj=new stdClass();
    //             $actionObj->icon='fas fa-warning';
    //             $actionObj->title='';
    //             $actionObj->message='Record Error';
    //             $actionObj->url='';
    //             $actionObj->target='_blank';
    //             $actionObj->type='danger';

    //             $actionJSON=json_encode($actionObj);
                
    //             $this->session->set_flashdata('msg', $actionJSON);
    //             redirect('Paymentsettle');
    //         }
    //     }
    //     else if($type==2){
    //         $this->db->trans_begin();

    //         $data = array(
    //             'status' => '2',
    //             'updateuser'=> $userID, 
    //             'updatedatetime'=> $updatedatetime
    //         );

    //         $this->db->where('idtbl_account_paysettle', $recordID);
    //         $this->db->update('tbl_account_paysettle', $data);

    //         $datapay = array(
    //             'status' => '2',
    //             'updateuser'=> $userID, 
    //             'updatedatetime'=> $updatedatetime
    //         );

    //         $this->db->where('tbl_account_paysettle_idtbl_account_paysettle', $recordID);
    //         $this->db->update('tbl_account_paysettle_info', $datapay);

    //         $this->db->select('tbl_account_paysettle_has_tbl_cheque_issue.tbl_cheque_issue_idtbl_cheque_issue');
    //         $this->db->from('tbl_account_paysettle_has_tbl_cheque_issue');
    //         $this->db->where('tbl_account_paysettle_has_tbl_cheque_issue.tbl_account_paysettle_idtbl_account_paysettle', $recordID);

    //         $respondcheque=$this->db->get();

    //         if($respondcheque->num_rows()>0){
    //             $datacheque = array(
    //                 'status' => '2',
    //                 'updateuser'=> $userID, 
    //                 'updatedatetime'=> $updatedatetime
    //             );

    //             $this->db->where('idtbl_cheque_issue', $respondcheque->row(0)->tbl_cheque_issue_idtbl_cheque_issue);
    //             $this->db->update('tbl_cheque_issue', $datacheque);
    //         }

    //         $this->db->trans_complete();

    //         if ($this->db->trans_status() === TRUE) {
    //             $this->db->trans_commit();
                
    //             $actionObj=new stdClass();
    //             $actionObj->icon='fas fa-times';
    //             $actionObj->title='';
    //             $actionObj->message='Record Deactivate Successfully';
    //             $actionObj->url='';
    //             $actionObj->target='_blank';
    //             $actionObj->type='warning';

    //             $actionJSON=json_encode($actionObj);
                
    //             $this->session->set_flashdata('msg', $actionJSON);
    //             redirect('Paymentsettle');                
    //         } else {
    //             $this->db->trans_rollback();

    //             $actionObj=new stdClass();
    //             $actionObj->icon='fas fa-warning';
    //             $actionObj->title='';
    //             $actionObj->message='Record Error';
    //             $actionObj->url='';
    //             $actionObj->target='_blank';
    //             $actionObj->type='danger';

    //             $actionJSON=json_encode($actionObj);
                
    //             $this->session->set_flashdata('msg', $actionJSON);
    //             redirect('Paymentsettle');
    //         }
    //     }
    //     // else if($type==3){
    //     //     $data = array(
    //     //         'status' => '3',
    //     //         'updateuser'=> $userID, 
    //     //         'updatedatetime'=> $updatedatetime
    //     //     );

    //     //     $this->db->where('idtbl_account_paysettle', $recordID);
    //     //     $this->db->update('tbl_account_paysettle', $data);

    //     //     $datapay = array(
    //     //         'status' => '3',
    //     //         'updateuser'=> $userID, 
    //     //         'updatedatetime'=> $updatedatetime
    //     //     );

    //     //     $this->db->where('tbl_account_paysettle_idtbl_account_paysettle', $recordID);
    //     //     $this->db->update('tbl_account_paysettle_info', $datapay);

    //     //     $this->db->trans_complete();

    //     //     if ($this->db->trans_status() === TRUE) {
    //     //         $this->db->trans_commit();
                
    //     //         $actionObj=new stdClass();
    //     //         $actionObj->icon='fas fa-trash-alt';
    //     //         $actionObj->title='';
    //     //         $actionObj->message='Record Remove Successfully';
    //     //         $actionObj->url='';
    //     //         $actionObj->target='_blank';
    //     //         $actionObj->type='danger';

    //     //         $actionJSON=json_encode($actionObj);
                
    //     //         $this->session->set_flashdata('msg', $actionJSON);
    //     //         redirect('Receivablesettle');                
    //     //     } else {
    //     //         $this->db->trans_rollback();

    //     //         $actionObj=new stdClass();
    //     //         $actionObj->icon='fas fa-warning';
    //     //         $actionObj->title='';
    //     //         $actionObj->message='Record Error';
    //     //         $actionObj->url='';
    //     //         $actionObj->target='_blank';
    //     //         $actionObj->type='danger';

    //     //         $actionJSON=json_encode($actionObj);
                
    //     //         $this->session->set_flashdata('msg', $actionJSON);
    //     //         redirect('Receivablesettle');
    //     //     }
    //     // }
    // }
    public function Paymentsettlestatus($x, $y){
        $userID         = $_SESSION['userid'];
        $recordID       = $x;
        $type           = $y;
        $updatedatetime = date('Y-m-d H:i:s');

        // ── Type config map ───────────────────────────────────────────────────────
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
        );

        try {
            if (empty($recordID)) {
                throw new Exception('Record ID is required');
            }

            if (!array_key_exists($type, $typeConfig)) {
                throw new Exception('Invalid status type provided');
            }

            $config = $typeConfig[$type];

            $this->db->trans_begin();

            // Update main paysettle status
            $data = array(
                'status'         => $config['status'],
                'updateuser'     => $userID,
                'updatedatetime' => $updatedatetime
            );

            $this->db->where('idtbl_account_paysettle', $recordID);
            $this->db->update('tbl_account_paysettle', $data);

            // Update paysettle info status
            $datapay = array(
                'status'         => $config['status'],
                'updateuser'     => $userID,
                'updatedatetime' => $updatedatetime
            );

            $this->db->where('tbl_account_paysettle_idtbl_account_paysettle', $recordID);
            $this->db->update('tbl_account_paysettle_info', $datapay);

            // Update linked cheque issue status if exists
            $this->db->select('tbl_account_paysettle_has_tbl_cheque_issue.tbl_cheque_issue_idtbl_cheque_issue');
            $this->db->from('tbl_account_paysettle_has_tbl_cheque_issue');
            $this->db->where('tbl_account_paysettle_has_tbl_cheque_issue.tbl_account_paysettle_idtbl_account_paysettle', $recordID);

            $respondcheque = $this->db->get();

            if (!$respondcheque) {
                throw new Exception('Failed to fetch linked cheque record');
            }

            if ($respondcheque->num_rows() > 0) {
                $datacheque = array(
                    'status'         => $config['status'],
                    'updateuser'     => $userID,
                    'updatedatetime' => $updatedatetime
                );

                $this->db->where('idtbl_cheque_issue', $respondcheque->row(0)->tbl_cheque_issue_idtbl_cheque_issue);
                $this->db->update('tbl_cheque_issue', $datacheque);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();

                $actionObj          = new stdClass();
                $actionObj->icon    = $config['icon'];
                $actionObj->title   = '';
                $actionObj->message = $config['message'];
                $actionObj->url     = '';
                $actionObj->target  = '_blank';
                $actionObj->type    = $config['type'];

                $this->session->set_flashdata('msg', json_encode($actionObj));
                redirect('Paymentsettle');
            } else {
                $this->db->trans_rollback();
                throw new Exception('Record Error');
            }

        } catch (Exception $e) {
            if ($this->db->trans_enabled) {
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
            redirect('Paymentsettle');
        }
    }
    public function Getinvrecno(){
        $printtype=$this->input->post('printtype');
        $printsupplier=$this->input->post('printsupplier');
        $printdate=$this->input->post('printdate');

        if($printtype==1){
            $this->db->select('`tbl_account_paysettle_info`.`invoiceno` AS `invoicereceiptno`');
            $this->db->from('tbl_account_paysettle_info');
            $this->db->join('tbl_account_paysettle', 'tbl_account_paysettle.idtbl_account_paysettle = tbl_account_paysettle_info.tbl_account_paysettle_idtbl_account_paysettle', 'left');
            $this->db->where('tbl_account_paysettle_info.status', '1');
            $this->db->where('tbl_account_paysettle.status', '1');
            if(!empty($printsupplier)){$this->db->where('tbl_account_paysettle.supplier', $printsupplier);}
            if(!empty($printdate)){$this->db->where('tbl_account_paysettle.date', $printdate);}
            $this->db->group_by('`tbl_account_paysettle_info`.`invoiceno`');

            $respond=$this->db->get();

            echo json_encode($respond->result());
        }
        else{
            $this->db->select('`paymentno` AS `invoicereceiptno`');
            $this->db->from('tbl_account_paysettle');
            $this->db->where('tbl_account_paysettle.status', '1');
            if(!empty($printsupplier)){$this->db->where('tbl_account_paysettle.supplier', $printsupplier);}
            if(!empty($printdate)){$this->db->where('tbl_account_paysettle.date', $printdate);}

            $respond=$this->db->get();

            echo json_encode($respond->result());
        }
    }
    // public function Paymentsettlecancel(){
    //     $recordID=$this->input->post('recordID');
    //     $chequecancel=$this->input->post('chequecancel');
    //     $updatedatetime=date('Y-m-d H:i:s');
    //     $userID=$_SESSION['userid'];

    //     $this->db->trans_begin();

    //     $data = array(
    //         'status' => '3',
    //         'updateuser'=> $userID, 
    //         'updatedatetime'=> $updatedatetime
    //     );

    //     $this->db->where('idtbl_account_paysettle', $recordID);
    //     $this->db->update('tbl_account_paysettle', $data);

    //     $datapay = array(
    //         'status' => '3',
    //         'updateuser'=> $userID, 
    //         'updatedatetime'=> $updatedatetime
    //     );

    //     $this->db->where('tbl_account_paysettle_idtbl_account_paysettle', $recordID);
    //     $this->db->update('tbl_account_paysettle_info', $datapay);

    //     if($chequecancel==1){
    //         //Cancel Cheque Issue
    //         $this->db->select('tbl_account_paysettle_has_tbl_cheque_issue.tbl_cheque_issue_idtbl_cheque_issue');
    //         $this->db->from('tbl_account_paysettle_has_tbl_cheque_issue');
    //         $this->db->where('tbl_account_paysettle_has_tbl_cheque_issue.tbl_account_paysettle_idtbl_account_paysettle', $recordID);

    //         $respondcheque=$this->db->get();

    //         if($respondcheque->num_rows()>0){
    //             $datacheque = array(
    //                 'status' => '3',
    //                 'updateuser'=> $userID, 
    //                 'updatedatetime'=> $updatedatetime
    //             );

    //             $this->db->where('idtbl_cheque_issue', $respondcheque->row(0)->tbl_cheque_issue_idtbl_cheque_issue);
    //             $this->db->update('tbl_cheque_issue', $datacheque);
    //         }
    //     }
    //     else{
    //         $this->db->select('tbl_account_paysettle_has_tbl_cheque_issue.tbl_cheque_issue_idtbl_cheque_issue');
    //         $this->db->from('tbl_account_paysettle_has_tbl_cheque_issue');
    //         $this->db->where('tbl_account_paysettle_has_tbl_cheque_issue.tbl_account_paysettle_idtbl_account_paysettle', $recordID);

    //         $respondcheque=$this->db->get();

    //         if($respondcheque->num_rows()>0){
    //             $datacheque = array(
    //                 'chequeallocate' => '0',
    //                 'updateuser'=> $userID, 
    //                 'updatedatetime'=> $updatedatetime
    //             );

    //             $this->db->where('idtbl_cheque_issue', $respondcheque->row(0)->tbl_cheque_issue_idtbl_cheque_issue);
    //             $this->db->update('tbl_cheque_issue', $datacheque);
    //         }
    //     }

    //     $this->db->trans_complete();

    //     if ($this->db->trans_status() === TRUE) {
    //         $this->db->trans_commit();
            
    //         $actionObj=new stdClass();
    //         $actionObj->icon='fas fa-trash-alt';
    //         $actionObj->title='';
    //         $actionObj->message='Record cancel Successfully';
    //         $actionObj->url='';
    //         $actionObj->target='_blank';
    //         $actionObj->type='danger';

    //         $actionJSON=json_encode($actionObj);
            
    //         $obj=new stdClass();
    //         $obj->status=1;
    //         $obj->action=$actionJSON;

    //         echo json_encode($obj);
    //     } else {
    //         $this->db->trans_rollback();

    //         $actionObj=new stdClass();
    //         $actionObj->icon='fas fa-warning';
    //         $actionObj->title='';
    //         $actionObj->message='Record Error';
    //         $actionObj->url='';
    //         $actionObj->target='_blank';
    //         $actionObj->type='danger';

    //         $actionJSON=json_encode($actionObj);
            
    //         $obj=new stdClass();
    //         $obj->status=0;
    //         $obj->action=$actionJSON;

    //         echo json_encode($obj);
    //     }
    // }
    public function Paymentsettlecancel(){
        try {
            $recordID       = $this->input->post('recordID');
            $chequecancel   = $this->input->post('chequecancel');
            $updatedatetime = date('Y-m-d H:i:s');
            $userID         = $_SESSION['userid'];

            if (empty($recordID)) {
                throw new Exception('Record ID is required');
            }

            $this->db->select('poststatus');
            $this->db->from('tbl_account_paysettle');
            $this->db->where('status', 1);
            $this->db->where('idtbl_account_paysettle', $recordID);
            $respondcheckpost = $this->db->get();

            if($respondcheckpost->row(0)->poststatus == 1){
                throw new Exception('Record Error, You can`t cancel this record. Because it`s allready posted.');
            }

            $this->db->trans_begin();

            // Update main paysettle status to cancelled
            $data = array(
                'status'         => '3',
                'updateuser'     => $userID,
                'updatedatetime' => $updatedatetime
            );

            $this->db->where('idtbl_account_paysettle', $recordID);
            $this->db->update('tbl_account_paysettle', $data);

            // Update paysettle info info status to cancelled
            $datapay = array(
                'status'         => '3',
                'updateuser'     => $userID,
                'updatedatetime' => $updatedatetime
            );

            $this->db->where('tbl_account_paysettle_idtbl_account_paysettle', $recordID);
            $this->db->update('tbl_account_paysettle_info', $datapay);

            // Update paysettle advance status to cancelled
            $datapay = array(
                'status'         => '3',
                'updateuser'     => $userID,
                'updatedatetime' => $updatedatetime
            );

            $this->db->where('tbl_account_paysettle_idtbl_account_paysettle', $recordID);
            $this->db->update('tbl_account_paysettle_advance', $datapay);

            //Update it is jurnal payable record not settle
            $this->db->select('tbl_account_transaction_manual_idtbl_account_transaction_manual, invoiceno');
            $this->db->from('tbl_account_paysettle_info');
            $this->db->where('tbl_account_paysettle_idtbl_account_paysettle', $recordID);
            $respondpaysettleinfo = $this->db->get();

            foreach($respondpaysettleinfo->result() as $rowsettleinfo):
                if($rowsettleinfo->tbl_account_transaction_manual_idtbl_account_transaction_manual > 0):
                    $datadetail = array(
                        'payablesettle' => '0',
                        'updateuser'   => $userID,
                        'updatedatetime'   => $updatedatetime
                    );

                    $this->db->where('idtbl_account_transaction_manual', $rowsettleinfo->tbl_account_transaction_manual_idtbl_account_transaction_manual);
                    $this->db->update('tbl_account_transaction_manual', $datadetail);
                elseif(!empty($rowsettleinfo->invoiceno)):
                    $datadetail = array(
                        'paystatus' => '0',
                        'updateuser'   => $userID,
                        'updatedatetime'   => $updatedatetime
                    );

                    $this->db->where('grnno', $rowsettleinfo->invoiceno);
                    $this->db->update('tbl_expence_info', $datadetail);
                endif;
            endforeach;

            // Fetch linked cheque issue
            $this->db->select('tbl_account_paysettle_has_tbl_cheque_issue.tbl_cheque_issue_idtbl_cheque_issue');
            $this->db->from('tbl_account_paysettle_has_tbl_cheque_issue');
            $this->db->where('tbl_account_paysettle_has_tbl_cheque_issue.tbl_account_paysettle_idtbl_account_paysettle', $recordID);

            $respondcheque = $this->db->get();

            if (!$respondcheque) {
                throw new Exception('Failed to fetch linked cheque record');
            }

            if ($respondcheque->num_rows() > 0) {
                if ($chequecancel == 1) {
                    // ── Cancel the cheque issue ───────────────────────────────────
                    $datacheque = array(
                        'status'         => '3',
                        'updateuser'     => $userID,
                        'updatedatetime' => $updatedatetime
                    );
                } else {
                    // ── Release the cheque back to unallocated ────────────────────
                    $datacheque = array(
                        'chequeallocate' => '0',
                        'updateuser'     => $userID,
                        'updatedatetime' => $updatedatetime
                    );
                }

                $this->db->where('idtbl_cheque_issue', $respondcheque->row(0)->tbl_cheque_issue_idtbl_cheque_issue);
                $this->db->update('tbl_cheque_issue', $datacheque);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                $this->_jsonResponse(1, 'fas fa-trash-alt', 'Record cancel Successfully', 'danger');
            } else {
                $this->db->trans_rollback();
                throw new Exception('Record Error');
            }

        } catch (Exception $e) {
            if ($this->db->trans_enabled) {
                $this->db->trans_rollback();
            }
            $this->_jsonResponse(0, 'fas fa-warning', $e->getMessage(), 'danger');
        }
    }
    public function Getadvanceaccosupplier(){
        $recordID=$this->input->post('recordID');
        $payablefilter=$this->input->post('payablefilter');
        $accounttype=$this->input->post('accounttype');

        $configdata = getconfigdata('payable_search');
        
        $tablename = $configdata->row(0)->tbl_name;
        $column1   = $configdata->row(0)->col_name;
        $column2   = $configdata->row(1)->col_name;

        $has_table = !empty($tablename) ? 1 : 0;

        $this->db->select("tbl_account_paysettle_advance.idtbl_account_paysettle_advance,
            tbl_account_paysettle_advance.date,
            tbl_account_paysettle_advance.amount,
            tbl_account_paysettle_advance.tbl_account_idtbl_account,
            tbl_account_paysettle_advance.tbl_account_detail_idtbl_account_detail,
            IF($has_table = 0, '', $tablename.$column1) AS idtbl_supplier, 
            IF($has_table = 0, '', $tablename.$column2) AS suppliername,
            tbl_account_paysettle.paymentno", FALSE);

        $this->db->from('tbl_account_paysettle_advance');

        if(!empty($tablename)):
            $this->db->join("$tablename", "$tablename.$column1 = tbl_account_paysettle_advance.supplier", 'left');
        endif;

        $this->db->join(
            'tbl_account_paysettle',
            'tbl_account_paysettle.idtbl_account_paysettle = tbl_account_paysettle_advance.tbl_account_paysettle_idtbl_account_paysettle',
            'left'
        );

        $this->db->where('tbl_account_paysettle_advance.supplier',      $recordID);
        $this->db->where('tbl_account_paysettle_advance.setoffstatus',  0);
        $this->db->where('tbl_account_paysettle_advance.status',        1);
        $this->db->where('tbl_account_paysettle_advance.poststatus',    1);

        $respond = $this->db->get();

        $html='';
        $i=1;
        foreach($respond->result() as $rowdatalist){
            $html.='
            <tr>
                <td class="text-center" width="5%">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input checkadvanceclick" id="customCheckAdvance'.$i.'">
                        <label class="custom-control-label m-0" for="customCheckAdvance'.$i.'"></label>
                    </div>
                </td>
                <td class="d-none">'.$rowdatalist->idtbl_account_paysettle_advance.'</td>
                <td>'.$rowdatalist->date.'</td>
                <td class="d-none">'.$rowdatalist->idtbl_supplier.'</td>
                <td>'.$rowdatalist->suppliername.'</td>
                <td class="d-none">'.$rowdatalist->paymentno.'</td>
                <td>'.$rowdatalist->paymentno.'</td>
                <td class="text-right">'.number_format($rowdatalist->amount, 2).'</td>
                <td class="d-none">'.$rowdatalist->tbl_account_idtbl_account.'</td>
                <td class="d-none">'.$rowdatalist->tbl_account_detail_idtbl_account_detail.'</td>
            </tr>
            ';
            $i++;
        }

        echo $html;
    }
    public function Getaccountinfoaccoaccountlist(){
        $accountlist=$this->input->post('accountlist');
        $companyid = $_SESSION['companyid'];
        $branchid = $_SESSION['branchid'];

        $obj=new stdClass();

        foreach($accountlist as $rowaccountlist){
            if($rowaccountlist['account_type'] == 1){
                $this->db->where('tbl_account_allocation.companybank', $companyid);
                $this->db->where('tbl_account_allocation.branchcompanybank', $branchid);
                $this->db->where('tbl_account.idtbl_account', $rowaccountlist['account_id']);
                $this->db->where('tbl_account.status', 1);
                $this->db->where('tbl_account_allocation.status', 1);
                $this->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
                $this->db->select('`tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`');
                $this->db->from('tbl_account');
                $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');

                $respond = $this->db->get();

                $obj->accountid=$respond->row(0)->idtbl_account;
                $obj->account=$respond->row(0)->accountname.' - '.$respond->row(0)->accountno;
                $obj->accounttype=1;
            }
            if($rowaccountlist['account_type'] == 2){
                $this->db->where('tbl_account_allocation.companybank', $companyid);
                $this->db->where('tbl_account_allocation.branchcompanybank', $branchid);
                $this->db->where('tbl_account_detail.idtbl_account_detail', $rowaccountlist['account_id']);
                $this->db->where('tbl_account_detail.status', 1);
                $this->db->where('tbl_account_allocation.status', 1);
                $this->db->where('tbl_account_allocation.tbl_account_detail_idtbl_account_detail is NOT NULL', NULL, FALSE);
                $this->db->select('`tbl_account_detail`.`idtbl_account_detail`, `tbl_account_detail`.`accountno`, `tbl_account_detail`.`accountname`');
                $this->db->from('tbl_account_detail');
                $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_detail_idtbl_account_detail = tbl_account_detail.idtbl_account_detail', 'left');

                $respond = $this->db->get();

                $obj->accountid=$respond->row(0)->idtbl_account_detail;
                $obj->account=$respond->row(0)->accountname.' - '.$respond->row(0)->accountno;
                $obj->accounttype=2;
            }
        }

        echo json_encode($obj);
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