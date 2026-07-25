<?php
class Issuechequeinfo extends CI_Model{
    // public function Issuechequestatus(){
    //     $userID=$_SESSION['userid'];
    //     $recordID=$this->input->post('recordID');
    //     $actionType=$this->input->post('actionType');
    //     $returndate=$this->input->post('date');
    //     $remarks=$this->input->post('remarks');
    //     $updatedatetime=date('Y-m-d H:i:s');
    //     $companyid=$_SESSION['companyid'];        
    //     $branchid=$_SESSION['branchid'];        

    //     $this->db->select('chepaytype, amount, chequeno');
    //     $this->db->from('tbl_cheque_issue');
    //     $this->db->where('idtbl_cheque_issue', $recordID);
    //     $this->db->where('status', 1);
    //     $respondcheckissue=$this->db->get();

    //     if($respondcheckissue->row(0)->chepaytype==1){
    //         if($actionType==1){
    //             $masterdata = get_account_period_acco_date($companyid, $branchid, $returndate);
    //             $prefix  = generate_prefix($companyid, $branchid, $returndate, 'CR');
    //             $batchno = tr_batch_num($prefix, $branchid);

    //             $masterID = $masterdata->idtbl_master;

    //             $this->db->trans_begin();

    //             // 1. Update cheque status → returned
    //             $this->db->where('idtbl_cheque_issue', $recordID);
    //             $this->db->update('tbl_cheque_issue', [
    //                 'status'        => 3,
    //                 'return_date'   => $actionType == 1 ? $returndate : NULL,
    //                 'return_reason' => $actionType == 1 ? $remarks : NULL,
    //                 'cancel_date' => $actionType == 2 ? $returndate : NULL,
    //                 'cancel_reason' => $actionType == 2 ? $remarks : NULL,
    //                 'updateuser'    => $userID,
    //                 'updatedatetime'=> $updatedatetime
    //             ]);

    //             // 2. Log cheque action
    //             $this->db->insert('tbl_cheque_action', [
    //                 'action_type'                        => $actionType,
    //                 'action_date'                        => $returndate,
    //                 'reason'                             => $remarks,
    //                 'bank_charge'                        => '0',
    //                 'reversal_batchno'                   => $batchno,
    //                 'status'                             => 1,
    //                 'insertdatetime'                     => $updatedatetime,
    //                 'tbl_user_idtbl_user'                => $userID,
    //                 'tbl_cheque_issue_idtbl_cheque_issue'=> $recordID,
    //                 'tbl_company_idtbl_company'          => $companyid,
    //                 'tbl_company_branch_idtbl_company_branch' => $branchid
    //             ]);

    //             $this->db->select('tbl_account_paysettle_entry.tratype, tbl_account_paysettle_entry.amount, tbl_account_paysettle_entry.narration, tbl_account_paysettle_entry.poststatus');
    //             $this->db->from('tbl_account_paysettle_entry');
    //             $this->db->join('tbl_account_paysettle', 'tbl_account_paysettle.idtbl_account_paysettle = tbl_account_paysettle_entry.tbl_account_paysettle_idtbl_account_paysettle', 'left');
    //             $this->db->join('tbl_account_paysettle_has_tbl_cheque_issue', 'tbl_account_paysettle_has_tbl_cheque_issue.tbl_account_paysettle_idtbl_account_paysettle = tbl_account_paysettle.idtbl_account_paysettle', 'left');
    //             $this->db->where('tbl_account_paysettle_has_tbl_cheque_issue.tbl_cheque_issue_idtbl_cheque_issue', $recordID);
    //             $recordpaysettle=$this->db->get();

    //             if($recordpaysettle->row(0)->poststatus==0){
    //                 return 'Not posting yet';
    //             }

    //             // Journal main
    //             $this->db->insert('tbl_account_transaction_manual_main', [
    //                 'tradate'                                 => $returndate,
    //                 'batchno'                                 => $batchno,
    //                 'amount'                                  => $respondcheckissue->row(0)->amount,
    //                 'narration'                               => 'Cheque Return: ' . $respondcheckissue->row(0)->chequeno . ' - ' . $remarks,
    //                 'poststatus'                              => '0',
    //                 'status'                                  => '1',
    //                 'insertdatetime'                          => $updatedatetime,
    //                 'tbl_user_idtbl_user'                     => $userID,
    //                 'tbl_master_idtbl_master'                 => $masterID,
    //                 'tbl_company_idtbl_company'               => $companyid,
    //                 'tbl_company_branch_idtbl_company_branch' => $branchid
    //             ]);
    //             $journalmainID = $this->db->insert_id();
                
    //             $i = 1;
    //             foreach($recordpaysettle->result() as $rowpaysettleinfo){
    //                 $crdr = $rowpaysettleinfo->tratype == 'D' ? 'C' : 'D';

    //                 $this->db->insert('tbl_account_transaction_manual', [
    //                     'tradate'                                 => $returndate,
    //                     'batchno'                                 => $batchno,
    //                     'tratype'                                 => 'J',
    //                     'seqno'                                   => $i++,
    //                     'crdr'                                    => $crdr,
    //                     'amount'                                  => $rowpaysettleinfo->amount,
    //                     'narration'                               => 'Cheque Return - Reinstate Payable: ' . $respondcheckissue->row(0)->chequeno,
    //                     'status'                                  => '1',
    //                     'insertdatetime'                          => $updatedatetime,
    //                     'tbl_user_idtbl_user'                     => $userID,
    //                     'tbl_account_idtbl_account'               => $paysettle->tbl_account_idtbl_account ?? 0,
    //                     'tbl_master_idtbl_master'                 => $masterID,
    //                     'tbl_company_idtbl_company'               => $companyid,
    //                     'tbl_company_branch_idtbl_company_branch' => $branchid,
    //                     'manualtrans_main_id'                     => $journalmainID
    //                 ]);
    //             }

    //             $this->db->trans_complete();
    //         }
    //         else if($actionType==2){
    //             $prefix  = generate_prefix($companyid, $branchid, $returndate, 'CC');
    //             $batchno = tr_batch_num($prefix, $branchid);
                
    //             $this->db->trans_begin();

    //             // 1. Update cheque status → cancelled
    //             $this->db->where('idtbl_cheque_issue', $recordID);
    //             $this->db->update('tbl_cheque_issue', [
    //                 'status'         => 4,
    //                 'cancel_date'    => $returndate,
    //                 'cancel_reason'  => $remarks,
    //                 'updateuser'     => $userID,
    //                 'updatedatetime' => $updatedatetime
    //             ]);

    //             // 2. Log action
    //             $this->db->insert('tbl_cheque_action', [
    //                 'action_type'                        => 2,
    //                 'action_date'                        => $returndate,
    //                 'reason'                             => $remarks,
    //                 'bank_charge'                        => 0,
    //                 'reversal_batchno'                   => $batchno,
    //                 'status'                             => 1,
    //                 'insertdatetime'                     => $updatedatetime,
    //                 'tbl_user_idtbl_user'                => $userID,
    //                 'tbl_cheque_issue_idtbl_cheque_issue'=> $recordID,
    //                 'tbl_company_idtbl_company'          => $companyid,
    //                 'tbl_company_branch_idtbl_company_branch' => $branchid
    //             ]);

    //             // 3. Simple reversal — DR Bank, CR AP
    //             // (no bank charges for cancellation)
    //             // Journal entries same pattern as ChequeReturn() but without bank charge entry

    //             $this->db->trans_complete();
    //         }
    //     }
    //     else if($respondcheckissue->row(0)->chepaytype==2){
    //         //Check Petty Cash Reimburse
    //         $this->db->select('COUNT(*) AS `checkcount`, `tbl_pettycash_reimburse`.`idtbl_pettycash_reimburse`, `tbl_pettycash_reimburse`.`tbl_company_idtbl_company`, `tbl_pettycash_reimburse`.`tbl_company_branch_idtbl_company_branch`, `tbl_pettycash_reimburse`.`reimursebal`');
    //         $this->db->from('tbl_cheque_issue');
    //         $this->db->join('tbl_pettycash_reimburse', 'tbl_pettycash_reimburse.chequeno = tbl_cheque_issue.chequeno', 'left');
    //         $this->db->where('idtbl_cheque_issue', $recordID);
    //         $respondcheck=$this->db->get();

    //         $this->db->select('COUNT(*) AS `checkcountabove`');
    //         $this->db->from('tbl_pettycash_reimburse');
    //         $this->db->where('tbl_cheque_issue_idtbl_cheque_issue>', $recordID);
    //         $respondcheckabove=$this->db->get();

    //         if($respondcheck->row(0)->checkcount>0 && $respondcheckabove->row(0)->checkcountabove==0){
    //             $this->db->trans_begin();

    //             $reimburseID=$respondcheck->row(0)->idtbl_pettycash_reimburse;

    //             //Issue check mark as return
    //             $dataissuecheque = array(
    //                 'chequereturn'=> '1', 
    //                 'updateuser'=> $userID, 
    //                 'updatedatetime'=> $updatedatetime
    //             );
        
    //             $this->db->where('idtbl_cheque_issue', $recordID);
    //             $this->db->update('tbl_cheque_issue', $dataissuecheque);

    //             //Reimbursement set deactivate
    //             $datareimburse = array(
    //                 'status'=> '2', 
    //                 'updateuser'=> $userID, 
    //                 'updatedatetime'=> $updatedatetime
    //             );
        
    //             $this->db->where('idtbl_pettycash_reimburse', $reimburseID);
    //             $this->db->update('tbl_pettycash_reimburse', $datareimburse);
                
    //             //Change reimbursement status
    //             $this->db->select('tbl_pettycash_idtbl_pettycash');
    //             $this->db->from('tbl_pettycash_reimburse_has_tbl_pettycash');
    //             $this->db->where('tbl_pettycash_reimburse_idtbl_pettycash_reimburse', $reimburseID);
    //             $respondrepetty=$this->db->get();

    //             foreach($respondrepetty->result() as $rowrepetty){
    //                 $datapettycash = array(
    //                     'reimbursestatus'=> '0', 
    //                     'updateuser'=> $userID, 
    //                     'updatedatetime'=> $updatedatetime
    //                 );
            
    //                 $this->db->where('idtbl_pettycash', $rowrepetty->tbl_pettycash_idtbl_pettycash);
    //                 $this->db->update('tbl_pettycash', $datapettycash);
    //             }

    //             // Check Journal Entry
    //             $this->db->select('tbl_account_transaction.*');
    //             $this->db->from('tbl_account_transaction');
    //             $this->db->join('tbl_pettycash_reimburse', 'tbl_pettycash_reimburse.reimbursecode = tbl_account_transaction.trabatchotherno', 'left');
    //             $this->db->where('tbl_pettycash_reimburse.idtbl_pettycash_reimburse', $reimburseID);

    //             $respondtra=$this->db->get();

    //             $prefix=trans_prefix($respondcheck->row(0)->tbl_company_idtbl_company, $respondcheck->row(0)->tbl_company_branch_idtbl_company_branch);
    //             $batchno=tr_batch_num($prefix, $respondcheck->row(0)->tbl_company_branch_idtbl_company_branch);
    //             $masterdata=get_account_period($respondcheck->row(0)->tbl_company_idtbl_company, $respondcheck->row(0)->tbl_company_branch_idtbl_company_branch);
    //             $masterID=$masterdata->idtbl_master;

    //             $today=date('Y-m-d');

    //             $i=1;
    //             foreach($respondtra->result() as $rowdatalist){
    //                 if($rowdatalist->crdr=='C'){$crdr='D';}
    //                 else{$crdr='C';}

    //                 $data = array(
    //                     'tradate'=> $today, 
    //                     'batchno'=> $batchno, 
    //                     'trabatchotherno'=> $rowdatalist->trabatchotherno, 
    //                     'tratype'=> 'R', 
    //                     'seqno'=> $i, 
    //                     'crdr'=> $crdr, 
    //                     'accamount'=> $rowdatalist->accamount, 
    //                     'narration'=> $rowdatalist->narration, 
    //                     'totamount'=> $rowdatalist->totamount, 
    //                     'reversstatus'=> '1', 
    //                     'status'=> '1', 
    //                     'insertdatetime'=> $updatedatetime, 
    //                     'tbl_user_idtbl_user'=> $userID,
    //                     'tbl_account_idtbl_account'=> $rowdatalist->tbl_account_idtbl_account,
    //                     'tbl_master_idtbl_master'=> $masterID,
    //                     'tbl_company_idtbl_company'=> $rowdatalist->tbl_company_idtbl_company,
    //                     'tbl_company_branch_idtbl_company_branch'=> $rowdatalist->tbl_company_branch_idtbl_company_branch
    //                 );
    //                 $this->db->insert('tbl_account_transaction', $data);
            
    //                 $datafull = array(
    //                     'tradate'=> $today, 
    //                     'batchno'=> $batchno, 
    //                     'tratype'=> 'R', 
    //                     'crdr'=> $crdr, 
    //                     'accamount'=> $rowdatalist->accamount, 
    //                     'narration'=> $rowdatalist->narration, 
    //                     'totamount'=> $rowdatalist->totamount, 
    //                     'status'=> '1', 
    //                     'insertdatetime'=> $updatedatetime, 
    //                     'tbl_user_idtbl_user'=> $userID,
    //                     'tbl_account_idtbl_account'=> $rowdatalist->tbl_account_idtbl_account,
    //                     'tbl_master_idtbl_master'=> $masterID,
    //                     'tbl_company_idtbl_company'=> $rowdatalist->tbl_company_idtbl_company,
    //                     'tbl_company_branch_idtbl_company_branch'=> $rowdatalist->tbl_company_branch_idtbl_company_branch
    //                 );
    //                 $this->db->insert('tbl_account_transaction_full', $datafull);

    //                 $i++;
    //             }

    //             //Set delete petty cash summery record
    //             $datareimburse = array(
    //                 'status'=> '3', 
    //                 'updateuser'=> $userID, 
    //                 'updatedatetime'=> $updatedatetime
    //             );
        
    //             $this->db->where('tbl_pettycash_reimburse_idtbl_pettycash_reimburse', $reimburseID);
    //             $this->db->update('tbl_pettycash_summary', $datareimburse);

    //             $this->db->trans_complete();

    //             if ($this->db->trans_status() === TRUE) {
    //                 $this->db->trans_commit();
                    
    //                 $actionObj=new stdClass();
    //                 $actionObj->icon='fas fa-save';
    //                 $actionObj->title='';
    //                 $actionObj->message='Record Successfully';
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
    //         else if($respondcheck->row(0)->checkcount>0 && $respondcheckabove->row(0)->checkcountabove>0){
    //             $actionObj=new stdClass();
    //             $actionObj->icon='fas fa-exclamation-triangle';
    //             $actionObj->title='';
    //             $actionObj->message='Record Error, You can`t return this cheque because reimbursement entered after this cheque.';
    //             $actionObj->url='';
    //             $actionObj->target='_blank';
    //             $actionObj->type='danger';

    //             $actionJSON=json_encode($actionObj);
                
    //             $obj=new stdClass();
    //             $obj->status=0;
    //             $obj->action=$actionJSON;

    //             echo json_encode($obj);
    //         }
    //         else{
    //             $this->db->trans_begin();

    //             // Update tbl_receivable
    //             $data = array(
    //                 'chequereturn' => '1',
    //                 'updateuser'=> $userID, 
    //                 'updatedatetime'=> $updatedatetime
    //             );

    //             $this->db->where('idtbl_cheque_issue', $recordID);
    //             $this->db->update('tbl_cheque_issue', $data);

    //             //Check payment settle info
    //             $this->db->select('tbl_account_paysettle_idtbl_account_paysettle');
    //             $this->db->from('tbl_account_paysettle_has_tbl_cheque_issue');
    //             $this->db->where('tbl_cheque_issue_idtbl_cheque_issue', $recordID);

    //             $respondcheckpay=$this->db->get();

    //             $paymentsettleID=$respondcheckpay->row(0)->tbl_account_paysettle_idtbl_account_paysettle;

    //             // Update tbl_account_paysettle
    //             $datapay = array(
    //                 'status' => '2',
    //                 'updateuser'=> $userID, 
    //                 'updatedatetime'=> $updatedatetime
    //             );

    //             $this->db->where('idtbl_account_paysettle', $paymentsettleID);
    //             $this->db->update('tbl_account_paysettle', $datapay);

    //             // Update tbl_account_paysettle_info
    //             $datapaydetail = array(
    //                 'status' => '2',
    //                 'updateuser'=> $userID, 
    //                 'updatedatetime'=> $updatedatetime
    //             );

    //             $this->db->where('tbl_account_paysettle_idtbl_account_paysettle', $paymentsettleID);
    //             $this->db->update('tbl_account_paysettle_info', $datapaydetail);

    //             //Check Company info
    //             $this->db->select('tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch');
    //             $this->db->from('tbl_account_paysettle');
    //             $this->db->where('idtbl_account_paysettle', $paymentsettleID);

    //             $respondcombra=$this->db->get();

    //             // Check Journal Entry
    //             $this->db->select('tbl_account_transaction.*');
    //             $this->db->from('tbl_account_transaction');
    //             $this->db->join('tbl_receivable', 'tbl_receivable.batchno = tbl_account_transaction.trabatchotherno', 'left');
    //             $this->db->where('tbl_receivable.idtbl_receivable', $recordID);

    //             $respondtra=$this->db->get();

    //             $prefix=trans_prefix($respondcombra->row(0)->tbl_company_idtbl_company, $respondcombra->row(0)->tbl_company_branch_idtbl_company_branch);
    //             $batchno=tr_batch_num($prefix, $respondcombra->row(0)->tbl_company_branch_idtbl_company_branch);
    //             $masterdata=get_account_period($respondcombra->row(0)->tbl_company_idtbl_company, $respondcombra->row(0)->tbl_company_branch_idtbl_company_branch);
    //             $masterID=$masterdata->idtbl_master;

    //             $today=date('Y-m-d');

    //             $i=1;
    //             foreach($respondtra->result() as $rowdatalist){
    //                 if($rowdatalist->crdr=='C'){$crdr='D';}
    //                 else{$crdr='C';}

    //                 $data = array(
    //                     'tradate'=> $today, 
    //                     'batchno'=> $batchno, 
    //                     'trabatchotherno'=> $rowdatalist->trabatchotherno, 
    //                     'tratype'=> 'R', 
    //                     'seqno'=> $i, 
    //                     'crdr'=> $crdr, 
    //                     'accamount'=> $rowdatalist->accamount, 
    //                     'narration'=> $rowdatalist->narration, 
    //                     'totamount'=> $rowdatalist->totamount, 
    //                     'reversstatus'=> '1', 
    //                     'status'=> '1', 
    //                     'insertdatetime'=> $updatedatetime, 
    //                     'tbl_user_idtbl_user'=> $userID,
    //                     'tbl_account_idtbl_account'=> $rowdatalist->tbl_account_idtbl_account,
    //                     'tbl_master_idtbl_master'=> $masterID,
    //                     'tbl_company_idtbl_company'=> $rowdatalist->tbl_company_idtbl_company,
    //                     'tbl_company_branch_idtbl_company_branch'=> $rowdatalist->tbl_company_branch_idtbl_company_branch
    //                 );
    //                 $this->db->insert('tbl_account_transaction', $data);
            
    //                 $datafull = array(
    //                     'tradate'=> $today, 
    //                     'batchno'=> $batchno, 
    //                     'tratype'=> 'R', 
    //                     'crdr'=> $crdr, 
    //                     'accamount'=> $rowdatalist->accamount, 
    //                     'narration'=> $rowdatalist->narration, 
    //                     'totamount'=> $rowdatalist->totamount, 
    //                     'status'=> '1', 
    //                     'insertdatetime'=> $updatedatetime, 
    //                     'tbl_user_idtbl_user'=> $userID,
    //                     'tbl_account_idtbl_account'=> $rowdatalist->tbl_account_idtbl_account,
    //                     'tbl_master_idtbl_master'=> $masterID,
    //                     'tbl_company_idtbl_company'=> $rowdatalist->tbl_company_idtbl_company,
    //                     'tbl_company_branch_idtbl_company_branch'=> $rowdatalist->tbl_company_branch_idtbl_company_branch
    //                 );
    //                 $this->db->insert('tbl_account_transaction_full', $datafull);

    //                 $i++;
    //             }

    //             $this->db->trans_complete();

    //             if ($this->db->trans_status() === TRUE) {
    //                 $this->db->trans_commit();
                    
    //                 $actionObj=new stdClass();
    //                 $actionObj->icon='fas fa-save';
    //                 $actionObj->title='';
    //                 $actionObj->message='Record Successfully';
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
    //     }
    // }

    public function Issuechequestatus(){
        try {
            $userID         = $_SESSION['userid'];
            $companyid      = $_SESSION['companyid'];
            $branchid       = $_SESSION['branchid'];
            $recordID       = $this->input->post('recordID');
            $actionType     = $this->input->post('actionType');
            $returndate     = $this->input->post('date');
            $remarks        = $this->input->post('remarks');
            $updatedatetime = date('Y-m-d H:i:s');
            $today          = date('Y-m-d');

            // ── Validate inputs ───────────────────────────────────────────────
            if(empty($recordID))   throw new Exception('Record ID is required');
            if(empty($actionType)) throw new Exception('Action type is required');
            if(empty($returndate)) throw new Exception('Date is required');

            // ── Fetch cheque issue record ─────────────────────────────────────
            $this->db->select('chepaytype, amount, chequeno, status');
            $this->db->from('tbl_cheque_issue');
            $this->db->where('idtbl_cheque_issue', $recordID);
            $this->db->where('status', 1);

            $respondcheckissue = $this->db->get();

            if(!$respondcheckissue || $respondcheckissue->num_rows() == 0){
                throw new Exception('Cheque record not found');
            }

            $cheque = $respondcheckissue->row(0);

            if($cheque->status == 3) throw new Exception('Cheque already returned');
            if($cheque->status == 4) throw new Exception('Cheque already cancelled');

            // ══════════════════════════════════════════════════════════════════
            // CHEQUE PAY TYPE 1 — Payment Settle Cheque
            // ══════════════════════════════════════════════════════════════════
            if($cheque->chepaytype == 1){
                // ── Resolve period master ─────────────────────────────────────────────
                $masterdata = get_account_period_acco_date($companyid, $branchid, $returndate);
            
                if(empty($masterdata) || empty($masterdata->idtbl_master)){
                    throw new Exception('Active account period not found for selected date');
                }
            
                $masterID = $masterdata->idtbl_master;
            
                // ── Action config — only differences between return and cancel ────────
                $actionConfig = [
                    1 => [
                        'prefix'         => 'CR',
                        'cheque_status'  => 3,
                        'return_date'    => $returndate,
                        'return_reason'  => $remarks,
                        'cancel_date'    => NULL,
                        'cancel_reason'  => NULL,
                        'narration_pfx'  => 'Cheque Return',
                        'detail_narr'    => 'Cheque Return - Reinstate Payable',
                        'success_msg'    => 'Cheque Return Processed Successfully'
                    ],
                    2 => [
                        'prefix'         => 'CC',
                        'cheque_status'  => 4,
                        'return_date'    => NULL,
                        'return_reason'  => NULL,
                        'cancel_date'    => $returndate,
                        'cancel_reason'  => $remarks,
                        'narration_pfx'  => 'Cheque Cancel',
                        'detail_narr'    => 'Cheque Cancel - Reinstate Payable',
                        'success_msg'    => 'Cheque Cancelled Successfully'
                    ]
                ];
            
                if(!array_key_exists($actionType, $actionConfig)){
                    throw new Exception('Invalid action type provided');
                }
            
                $cfg = $actionConfig[$actionType];
            
                // ── Generate batch number ─────────────────────────────────────────────
                $prefix  = generate_prefix($companyid, $branchid, $returndate, $cfg['prefix']);
                $batchno = tr_batch_num($prefix, $branchid);
            
                if(empty($batchno)) throw new Exception('Batch no could not be defined by system');
            
                // ── Fetch paysettle entry via cheque link (same for both action types) ─
                $this->db->select('
                    tbl_account_paysettle_entry.tratype,
                    tbl_account_paysettle_entry.amount,
                    tbl_account_paysettle_entry.narration,
                    tbl_account_paysettle_entry.poststatus,
                    tbl_account_paysettle_entry.tbl_account_idtbl_account,
                    tbl_account_paysettle_entry.tbl_account_detail_idtbl_account_detail
                ');
                $this->db->from('tbl_account_paysettle_entry');
                $this->db->join(
                    'tbl_account_paysettle',
                    'tbl_account_paysettle.idtbl_account_paysettle = tbl_account_paysettle_entry.tbl_account_paysettle_idtbl_account_paysettle',
                    'left'
                );
                $this->db->join(
                    'tbl_account_paysettle_has_tbl_cheque_issue',
                    'tbl_account_paysettle_has_tbl_cheque_issue.tbl_account_paysettle_idtbl_account_paysettle = tbl_account_paysettle.idtbl_account_paysettle',
                    'left'
                );
                $this->db->where('tbl_account_paysettle_has_tbl_cheque_issue.tbl_cheque_issue_idtbl_cheque_issue', $recordID);
                $this->db->where('tbl_account_paysettle_entry.status', 1);
            
                $recordpaysettle = $this->db->get();
            
                if(!$recordpaysettle || $recordpaysettle->num_rows() == 0){
                    throw new Exception('Paysettle entry not found for this cheque');
                }
            
                if($recordpaysettle->row(0)->poststatus == 0){
                    throw new Exception('Paysettle not posted yet. Cannot process unposted cheque.');
                }

                // ── Check this cheque setoff another payments ─────────────────────────

                $this->db->select('COUNT(*) as count', FALSE);
                $this->db->from('tbl_account_paysettle_advance_has_tbl_expence_info');
                $this->db->join(
                    'tbl_account_paysettle_advance',
                    'tbl_account_paysettle_advance.idtbl_account_paysettle_advance = tbl_account_paysettle_advance_has_tbl_expence_info.tbl_account_paysettle_advance_idtbl_account_paysettle_advance',
                    'left'
                );
                $this->db->join(
                    'tbl_account_paysettle_has_tbl_cheque_issue',
                    'tbl_account_paysettle_has_tbl_cheque_issue.tbl_account_paysettle_idtbl_account_paysettle = tbl_account_paysettle_advance.tbl_account_paysettle_idtbl_account_paysettle',
                    'left'
                );
                $this->db->where('tbl_account_paysettle_has_tbl_cheque_issue.tbl_cheque_issue_idtbl_cheque_issue', $recordID);
                $this->db->where('tbl_account_paysettle_advance_has_tbl_expence_info.status', 1);

                $respondadvance = $this->db->get();

                if($respondadvance->row(0)->count > 0){
                    throw new Exception('This cheque can`t return or cancel. Because it`s already setoff some payments');
                }
            
                // ── Begin Transaction ─────────────────────────────────────────────────
                $this->db->trans_begin();
            
                // ── Update cheque status ──────────────────────────────────────────────
                $this->db->where('idtbl_cheque_issue', $recordID);
                $this->db->update('tbl_cheque_issue', [
                    'chequereturn'   => ($actionType == 1) ? 1 : 0,
                    'status'         => $cfg['cheque_status'],
                    'return_date'    => $cfg['return_date'],
                    'return_reason'  => $cfg['return_reason'],
                    'cancel_date'    => $cfg['cancel_date'],
                    'cancel_reason'  => $cfg['cancel_reason'],
                    'updateuser'     => $userID,
                    'updatedatetime' => $updatedatetime
                ]);
            
                // ── Log cheque action ─────────────────────────────────────────────────
                $this->db->insert('tbl_cheque_action', [
                    'action_type'                            => $actionType,
                    'action_date'                            => $returndate,
                    'reason'                                 => $remarks,
                    'bank_charge'                            => 0,
                    'reversal_batchno'                       => $batchno,
                    'status'                                 => 1,
                    'insertdatetime'                         => $updatedatetime,
                    'tbl_user_idtbl_user'                    => $userID,
                    'tbl_cheque_issue_idtbl_cheque_issue'    => $recordID,
                    'tbl_company_idtbl_company'              => $companyid,
                    'tbl_company_branch_idtbl_company_branch'=> $branchid
                ]);
            
                // ── Insert journal main header ────────────────────────────────────────
                $this->db->insert('tbl_account_transaction_manual_main', [
                    'tradate'                                 => $returndate,
                    'batchno'                                 => $batchno,
                    'amount'                                  => $cheque->amount,
                    'narration'                               => $cfg['narration_pfx'] . ': ' . $cheque->chequeno . ' - ' . $remarks,
                    'poststatus'                              => '0',
                    'status'                                  => '1',
                    'insertdatetime'                          => $updatedatetime,
                    'tbl_user_idtbl_user'                     => $userID,
                    'tbl_master_idtbl_master'                 => $masterID,
                    'tbl_company_idtbl_company'               => $companyid,
                    'tbl_company_branch_idtbl_company_branch' => $branchid
                ]);
            
                $journalmainID = $this->db->insert_id();
            
                if(empty($journalmainID)) throw new Exception('Failed to insert journal main record');
            
                // ── Insert reversal journal detail lines ──────────────────────────────
                $i = 1;
                foreach($recordpaysettle->result() as $rowpaysettleinfo){
                    $crdr = ($rowpaysettleinfo->tratype == 'D') ? 'C' : 'D';
            
                    $this->db->insert('tbl_account_transaction_manual', [
                        'tradate'                                 => $returndate,
                        'batchno'                                 => $batchno,
                        'tratype'                                 => 'J',
                        'seqno'                                   => $i++,
                        'crdr'                                    => $crdr,
                        'amount'                                  => $rowpaysettleinfo->amount,
                        'narration'                               => $cfg['detail_narr'] . ': ' . $cheque->chequeno,
                        'status'                                  => '1',
                        'insertdatetime'                          => $updatedatetime,
                        'tbl_user_idtbl_user'                     => $userID,
                        'tbl_account_idtbl_account'               => $rowpaysettleinfo->tbl_account_idtbl_account,
                        'tbl_account_detail_idtbl_account_detail' => $rowpaysettleinfo->tbl_account_detail_idtbl_account_detail,
                        'tbl_master_idtbl_master'                 => $masterID,
                        'tbl_company_idtbl_company'               => $companyid,
                        'tbl_company_branch_idtbl_company_branch' => $branchid,
                        'manualtrans_main_id'                     => $journalmainID
                    ]);
                }
            
                // ── Update paysettle info status via JOIN ─────────────────────────────
                $updatepaysettle = "UPDATE tbl_account_paysettle_info
                        INNER JOIN tbl_account_paysettle_has_tbl_cheque_issue
                            ON tbl_account_paysettle_info.tbl_account_paysettle_idtbl_account_paysettle
                            = tbl_account_paysettle_has_tbl_cheque_issue.tbl_account_paysettle_idtbl_account_paysettle
                        SET tbl_account_paysettle_info.status = ?,
                            tbl_account_paysettle_info.updateuser = ?,
                            tbl_account_paysettle_info.updatedatetime = ?
                        WHERE tbl_account_paysettle_has_tbl_cheque_issue.tbl_cheque_issue_idtbl_cheque_issue = ?";
            
                $this->db->query($updatepaysettle, ['3', $userID, $updatedatetime, $recordID]);

                // ── Update it is jurnal payable record not settle ─────────────────────────────
                $this->db->select('tbl_account_paysettle_info.tbl_account_transaction_manual_idtbl_account_transaction_manual, tbl_account_paysettle_info.invoiceno');
                $this->db->from('tbl_account_paysettle_info');
                $this->db->join('tbl_account_paysettle_has_tbl_cheque_issue', 'tbl_account_paysettle_has_tbl_cheque_issue.tbl_account_paysettle_idtbl_account_paysettle = tbl_account_paysettle_info.tbl_account_paysettle_idtbl_account_paysettle');
                $this->db->where('tbl_account_paysettle_has_tbl_cheque_issue.tbl_cheque_issue_idtbl_cheque_issue', $recordID);
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
            
                // ── Complete Transaction ──────────────────────────────────────────────
                $this->db->trans_complete();
            
                if($this->db->trans_status() === TRUE){
                    $this->db->trans_commit();
                    $this->_jsonResponse(1, 'fas fa-save', $cfg['success_msg'], 'success');
                } else {
                    $this->db->trans_rollback();
                    throw new Exception('Record Error, Transaction failed');
                }
            }
            // ══════════════════════════════════════════════════════════════════
            // CHEQUE PAY TYPE 2 — Petty Cash Reimburse Cheque
            // ══════════════════════════════════════════════════════════════════
            else if($cheque->chepaytype == 2){

                // Check petty cash reimburse linked to this cheque
                $this->db->select('COUNT(*) AS checkcount, tbl_pettycash_reimburse.idtbl_pettycash_reimburse, tbl_pettycash_reimburse.tbl_company_idtbl_company, tbl_pettycash_reimburse.tbl_company_branch_idtbl_company_branch, tbl_pettycash_reimburse.reimursebal');
                $this->db->from('tbl_cheque_issue');
                $this->db->join('tbl_pettycash_reimburse', 'tbl_pettycash_reimburse.chequeno = tbl_cheque_issue.chequeno', 'left');
                $this->db->where('idtbl_cheque_issue', $recordID);

                $respondcheck = $this->db->get();

                if(!$respondcheck || $respondcheck->num_rows() == 0){
                    throw new Exception('Petty cash reimburse record not found');
                }

                $reimburseRec = $respondcheck->row(0);

                // Check if later reimbursements exist
                $this->db->select('COUNT(*) AS checkcountabove');
                $this->db->from('tbl_pettycash_reimburse');
                $this->db->where('tbl_cheque_issue_idtbl_cheque_issue >', $recordID);

                $respondcheckabove = $this->db->get();
                $aboveCount = $respondcheckabove->row(0)->checkcountabove;

                // Validate: cannot return if later reimbursements exist
                if($reimburseRec->checkcount > 0 && $aboveCount > 0){
                    throw new Exception("Record Error, You can't return this cheque because reimbursement entered after this cheque.");
                }

                // ── Process petty cash cheque return ──────────────────────────
                $reimburseID = $reimburseRec->idtbl_pettycash_reimburse;

                $prefix     = trans_prefix($reimburseRec->tbl_company_idtbl_company, $reimburseRec->tbl_company_branch_idtbl_company_branch);
                $batchno    = tr_batch_num($prefix, $reimburseRec->tbl_company_branch_idtbl_company_branch);
                $masterdata = get_account_period($reimburseRec->tbl_company_idtbl_company, $reimburseRec->tbl_company_branch_idtbl_company_branch);

                if(empty($masterdata) || empty($masterdata->idtbl_master)){
                    throw new Exception('Active account period not found');
                }

                $masterID = $masterdata->idtbl_master;

                // Fetch original account transactions via reimburse code
                $this->db->select('tbl_account_transaction.*');
                $this->db->from('tbl_account_transaction');
                $this->db->join('tbl_pettycash_reimburse', 'tbl_pettycash_reimburse.reimbursecode = tbl_account_transaction.trabatchotherno', 'left');
                $this->db->where('tbl_pettycash_reimburse.idtbl_pettycash_reimburse', $reimburseID);

                $respondtra = $this->db->get();

                if(!$respondtra || $respondtra->num_rows() == 0){
                    throw new Exception('Account transaction records not found for this reimburse');
                }

                // ── Begin Transaction ─────────────────────────────────────────
                $this->db->trans_begin();

                // Mark cheque as returned
                $this->db->where('idtbl_cheque_issue', $recordID);
                $this->db->update('tbl_cheque_issue', [
                    'chequereturn'   => '1',
                    'updateuser'     => $userID,
                    'updatedatetime' => $updatedatetime
                ]);

                // Deactivate reimbursement
                $this->db->where('idtbl_pettycash_reimburse', $reimburseID);
                $this->db->update('tbl_pettycash_reimburse', [
                    'status'         => '2',
                    'updateuser'     => $userID,
                    'updatedatetime' => $updatedatetime
                ]);

                // Reset petty cash reimbursement status
                $this->db->select('tbl_pettycash_idtbl_pettycash');
                $this->db->from('tbl_pettycash_reimburse_has_tbl_pettycash');
                $this->db->where('tbl_pettycash_reimburse_idtbl_pettycash_reimburse', $reimburseID);

                $respondrepetty = $this->db->get();

                foreach($respondrepetty->result() as $rowrepetty){
                    $this->db->where('idtbl_pettycash', $rowrepetty->tbl_pettycash_idtbl_pettycash);
                    $this->db->update('tbl_pettycash', [
                        'reimbursestatus' => '0',
                        'updateuser'      => $userID,
                        'updatedatetime'  => $updatedatetime
                    ]);
                }

                // Insert reversal transactions
                $i = 1;
                foreach($respondtra->result() as $rowdatalist){
                    $crdr = ($rowdatalist->crdr == 'C') ? 'D' : 'C';

                    $this->db->insert('tbl_account_transaction', [
                        'tradate'                                 => $today,
                        'batchno'                                 => $batchno,
                        'trabatchotherno'                         => $rowdatalist->trabatchotherno,
                        'tratype'                                 => 'R',
                        'seqno'                                   => $i,
                        'crdr'                                    => $crdr,
                        'accamount'                               => $rowdatalist->accamount,
                        'narration'                               => $rowdatalist->narration,
                        'totamount'                               => $rowdatalist->totamount,
                        'reversstatus'                            => '1',
                        'status'                                  => '1',
                        'insertdatetime'                          => $updatedatetime,
                        'tbl_user_idtbl_user'                     => $userID,
                        'tbl_account_idtbl_account'               => $rowdatalist->tbl_account_idtbl_account,
                        'tbl_master_idtbl_master'                 => $masterID,
                        'tbl_company_idtbl_company'               => $rowdatalist->tbl_company_idtbl_company,
                        'tbl_company_branch_idtbl_company_branch' => $rowdatalist->tbl_company_branch_idtbl_company_branch
                    ]);

                    $this->db->insert('tbl_account_transaction_full', [
                        'tradate'                                 => $today,
                        'batchno'                                 => $batchno,
                        'tratype'                                 => 'R',
                        'crdr'                                    => $crdr,
                        'accamount'                               => $rowdatalist->accamount,
                        'narration'                               => $rowdatalist->narration,
                        'totamount'                               => $rowdatalist->totamount,
                        'status'                                  => '1',
                        'insertdatetime'                          => $updatedatetime,
                        'tbl_user_idtbl_user'                     => $userID,
                        'tbl_account_idtbl_account'               => $rowdatalist->tbl_account_idtbl_account,
                        'tbl_master_idtbl_master'                 => $masterID,
                        'tbl_company_idtbl_company'               => $rowdatalist->tbl_company_idtbl_company,
                        'tbl_company_branch_idtbl_company_branch' => $rowdatalist->tbl_company_branch_idtbl_company_branch
                    ]);

                    $i++;
                }

                // Deactivate petty cash summary linked to this reimburse
                $this->db->where('tbl_pettycash_reimburse_idtbl_pettycash_reimburse', $reimburseID);
                $this->db->update('tbl_pettycash_summary', [
                    'status'         => '3',
                    'updateuser'     => $userID,
                    'updatedatetime' => $updatedatetime
                ]);

                $this->db->trans_complete();

                if($this->db->trans_status() === TRUE){
                    $this->db->trans_commit();
                    $this->_jsonResponse(1, 'fas fa-save', 'Cheque Return Processed Successfully', 'success');
                } else {
                    $this->db->trans_rollback();
                    throw new Exception('Record Error, Transaction failed');
                }

            } else {
                throw new Exception('Invalid cheque pay type');
            }

        } catch(Exception $e){
            if($this->db->trans_enabled){
                $this->db->trans_rollback();
            }
            $this->_jsonResponse(0, 'fas fa-warning', $e->getMessage(), 'danger');
        }
    }
    public function Chequeprint($x) {
        $issuechequeID = $x;
        $this->db->select('*');
        $this->db->from('tbl_cheque_issue');
        $this->db->where('idtbl_cheque_issue', $issuechequeID);
        $respond=$this->db->get();

        $rupeetext=$this->Issuechequeinfo->ConvertRupeeToText(round($respond->row(0)->amount, 2));
        
        // Default data
        $default = array(
            'date' => $respond->row(0)->chedate,
            'payee_name' => $respond->row(0)->chequepay,
            'amount_words' => $rupeetext,
            'amount_figures' => round($respond->row(0)->amount, 2),
            'is_crossed' => $respond->row(0)->chequecross // A/C Payee Only
        );

        $data = array_merge($default);

        $this->db->set('chequeprintcount', 'chequeprintcount+1', FALSE);
        $this->db->where('idtbl_cheque_issue', $issuechequeID);
        $this->db->update('tbl_cheque_issue');

        // Exact Sri Lankan Cheque Dimensions (7.25in x 3.5in)
        $w = '184.15mm';
        $h = '88.9mm';

        // // Date formatting with non-breaking spaces to prevent wrapping
        // $dateStr = date('dmY', strtotime($data['date']));
        // $formattedDate = implode('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', str_split($dateStr));

        // 1. Get date parts: Day (2), Month (2), Century (2), Year (2)
        $day   = date('dm', strtotime($data['date'])); // e.g., "1812"
        $year2 = date('y', strtotime($data['date']));  // e.g., "25" for 2025

        // 2. Format with standard spacing for the first 4 digits
        $part1 = implode('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', str_split($day));

        // 3. Create a larger gap to skip the pre-printed "2 0"
        // Increase the number of &nbsp; to physically move the last two digits past the "20"
        $gap = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

        // 4. Format the last two digits of the year
        $part2 = implode('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', str_split($year2));

        // Combine them
        $formattedDate = $part1 . $gap . $part2;

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                /* 1. Force PDF Engine to strict dimensions */
                @page { 
                    size: 184.15mm 88.9mm; 
                    margin: 0; 
                }
                html, body {
                    margin: 0;
                    padding: 0;
                    width: '.$w.';
                    height: '.$h.';
                    overflow: hidden;
                    font-family: Arial, sans-serif;
                    background: #fff;
                }

                /* 2. Absolute positioning container */
                .cheque-container {
                    position: absolute;
                    top: 0; left: 0;
                    width: '.$w.';
                    height: '.$h.';
                }

                .field {
                    position: absolute;
                    font-weight: bold;
                    color: #000;
                    white-space: nowrap; /* Prevents text from breaking into lines */
                }

                /* Date - Top Right */
                .date { 
                    top: 8mm; 
                    right: 6mm; 
                    font-size: 14pt; 
                    white-space: nowrap; /* Critical for the DDMM  YY format */
                }

                /* Container shifted to the absolute top-left corner */
                .crossing-container {
                    position: absolute;
                    top: -5mm;    /* Negative value pulls it higher towards the top edge */
                    left: -20mm;  /* Negative value pulls it further left */
                    width: 100mm; /* Wide enough to prevent text clipping during rotation */
                    height: 15mm;
                    transform: rotate(-35deg); 
                    z-index: 10;
                }

                /* Parallel crossing lines */
                .crossing-line {
                    border-top: 1.2pt solid #000;
                    width: 100%;
                    display: block;
                }

                /* Left-aligned text inside the lines */
                .crossing-text {
                    font-size: 8pt;
                    font-weight: bold;
                    font-family: Arial, sans-serif;
                    letter-spacing: 0.5pt;
                    text-transform: uppercase;
                    line-height: 5mm; 
                    white-space: nowrap; 
                    text-align: left;    /* Keeps text to the left */
                    padding-left: 19mm;  /* Adjust this value to move text along the line */
                }

                /* Payee - Top Middle */
                .payee { 
                    top: 24mm; 
                    left: 25mm; 
                    font-size: 11pt; 
                }

                /* Amount in Words - Middle (Allows 2 lines) */
                .amt-words { 
                    top: 35mm; 
                    left: 18mm; 
                    width: 115mm; 
                    line-height: 8mm; 
                    font-size: 10pt;
                    white-space: normal; /* Allow wrapping only here */
                }

                /* Amount in Figures - Right Middle */
                .amt-figures { 
                    top: 42mm; 
                    right: 10mm; 
                    font-size: 13pt; 
                }
            </style>
        </head>
        <body>
            <div class="cheque-container">';
                if($data['is_crossed']):
                $html.='<div class="crossing-container">
                    <div class="crossing-line"></div>
                    <div class="crossing-text">A/C PAYEE ONLY</div>
                    <div class="crossing-line"></div>
                </div>';
                endif;
                $html.='<div class="field date">'.$formattedDate.'</div>
                <div class="field payee">**' . strtoupper($data['payee_name']) . '**</div>
                <div class="field amt-words">**' . strtoupper($data['amount_words']) . ' ONLY**</div>
                <div class="field amt-figures">**' . number_format($data['amount_figures'], 2) . '**</div>
            </div>
        </body>
        </html>';

        // echo $html;
        $this->load->library('pdf');
        $this->pdf->loadHtml($html);
        $this->pdf->render();
        $this->pdf->stream( "paymentvoucher.pdf", array("Attachment"=>0));
    }
    public function ConvertRupeeToText($amount) {
        $ones = array(
            0 => '',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen'
        );
    
        $tens = array(
            2 => 'twenty',
            3 => 'thirty',
            4 => 'forty',
            5 => 'fifty',
            6 => 'sixty',
            7 => 'seventy',
            8 => 'eighty',
            9 => 'ninety'
        );
    
        $amount = str_replace(',', '', $amount);
        $rupees = intval($amount);
        $cents = intval(round(($amount - $rupees) * 100));
    
        $words = '';
    
        $numberToWords = function($num) use (&$numberToWords, $ones, $tens) {
            $str = '';
    
            if ($num >= 1000000000) {
                $str .= $numberToWords(intval($num / 1000000000)) . ' billion ';
                $num %= 1000000000;
            }
    
            if ($num >= 1000000) {
                $str .= $numberToWords(intval($num / 1000000)) . ' million ';
                $num %= 1000000;
            }
    
            if ($num >= 1000) {
                $str .= $numberToWords(intval($num / 1000)) . ' thousand ';
                $num %= 1000;
            }
    
            if ($num >= 100) {
                $str .= $ones[intval($num / 100)] . ' hundred ';
                $num %= 100;
            }
    
            if ($num > 0) {
                if ($str !== '') {
                    $str .= ' ';
                }
    
                if ($num < 20) {
                    $str .= $ones[$num];
                } else {
                    $str .= $tens[intval($num / 10)];
                    if ($num % 10 > 0) {
                        $str .= '-' . $ones[$num % 10];
                    }
                }
            }
    
            return trim($str);
        };
    
        if ($rupees > 0) {
            $words .= $numberToWords($rupees);
        }
    
        if ($cents > 0) {
            if ($rupees > 0) {
                $words .= ' and ';
            }
            $words .= $numberToWords($cents) . ' cents';
        }
    
        if ($words === '') {
            $words = 'zero';
        }
    
        return ucfirst(trim($words));
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