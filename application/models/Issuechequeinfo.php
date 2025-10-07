<?php
class Issuechequeinfo extends CI_Model{
    public function Issuechequestatus(){
        $userID=$_SESSION['userid'];
        $recordID=$this->input->post('recordID');;
        $updatedatetime=date('Y-m-d H:i:s');

        //Check Petty Cash Reimburse
        $this->db->select('COUNT(*) AS `checkcount`, `tbl_pettycash_reimburse`.`idtbl_pettycash_reimburse`, `tbl_pettycash_reimburse`.`tbl_company_idtbl_company`, `tbl_pettycash_reimburse`.`tbl_company_branch_idtbl_company_branch`, `tbl_pettycash_reimburse`.`reimursebal`');
        $this->db->from('tbl_cheque_issue');
        $this->db->join('tbl_pettycash_reimburse', 'tbl_pettycash_reimburse.chequeno = tbl_cheque_issue.chequeno', 'left');
        $this->db->where('idtbl_cheque_issue', $recordID);
        $respondcheck=$this->db->get();

        $this->db->select('COUNT(*) AS `checkcountabove`');
        $this->db->from('tbl_pettycash_reimburse');
        $this->db->where('tbl_cheque_issue_idtbl_cheque_issue>', $recordID);
        $respondcheckabove=$this->db->get();

        if($respondcheck->row(0)->checkcount>0 && $respondcheckabove->row(0)->checkcountabove==0){
            $this->db->trans_begin();

            $reimburseID=$respondcheck->row(0)->idtbl_pettycash_reimburse;

            //Issue check mark as return
            $dataissuecheque = array(
                'chequereturn'=> '1', 
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );
    
            $this->db->where('idtbl_cheque_issue', $recordID);
            $this->db->update('tbl_cheque_issue', $dataissuecheque);

            //Reimbursement set deactivate
            $datareimburse = array(
                'status'=> '2', 
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );
    
            $this->db->where('idtbl_pettycash_reimburse', $reimburseID);
            $this->db->update('tbl_pettycash_reimburse', $datareimburse);
            
            //Change reimbursement status
            $this->db->select('tbl_pettycash_idtbl_pettycash');
            $this->db->from('tbl_pettycash_reimburse_has_tbl_pettycash');
            $this->db->where('tbl_pettycash_reimburse_idtbl_pettycash_reimburse', $reimburseID);
            $respondrepetty=$this->db->get();

            foreach($respondrepetty->result() as $rowrepetty){
                $datapettycash = array(
                    'reimbursestatus'=> '0', 
                    'updateuser'=> $userID, 
                    'updatedatetime'=> $updatedatetime
                );
        
                $this->db->where('idtbl_pettycash', $rowrepetty->tbl_pettycash_idtbl_pettycash);
                $this->db->update('tbl_pettycash', $datapettycash);
            }

            // Check Journal Entry
            $this->db->select('tbl_account_transaction.*');
            $this->db->from('tbl_account_transaction');
            $this->db->join('tbl_pettycash_reimburse', 'tbl_pettycash_reimburse.reimbursecode = tbl_account_transaction.trabatchotherno', 'left');
            $this->db->where('tbl_pettycash_reimburse.idtbl_pettycash_reimburse', $reimburseID);

            $respondtra=$this->db->get();

            $prefix=trans_prefix($respondcheck->row(0)->tbl_company_idtbl_company, $respondcheck->row(0)->tbl_company_branch_idtbl_company_branch);
            $batchno=tr_batch_num($prefix, $respondcheck->row(0)->tbl_company_branch_idtbl_company_branch);
            $masterdata=get_account_period($respondcheck->row(0)->tbl_company_idtbl_company, $respondcheck->row(0)->tbl_company_branch_idtbl_company_branch);
            $masterID=$masterdata->idtbl_master;

            $today=date('Y-m-d');

            $i=1;
            foreach($respondtra->result() as $rowdatalist){
                if($rowdatalist->crdr=='C'){$crdr='D';}
                else{$crdr='C';}

                $data = array(
                    'tradate'=> $today, 
                    'batchno'=> $batchno, 
                    'trabatchotherno'=> $rowdatalist->trabatchotherno, 
                    'tratype'=> 'R', 
                    'seqno'=> $i, 
                    'crdr'=> $crdr, 
                    'accamount'=> $rowdatalist->accamount, 
                    'narration'=> $rowdatalist->narration, 
                    'totamount'=> $rowdatalist->totamount, 
                    'reversstatus'=> '1', 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_account_idtbl_account'=> $rowdatalist->tbl_account_idtbl_account,
                    'tbl_master_idtbl_master'=> $masterID,
                    'tbl_company_idtbl_company'=> $rowdatalist->tbl_company_idtbl_company,
                    'tbl_company_branch_idtbl_company_branch'=> $rowdatalist->tbl_company_branch_idtbl_company_branch
                );
                $this->db->insert('tbl_account_transaction', $data);
        
                $datafull = array(
                    'tradate'=> $today, 
                    'batchno'=> $batchno, 
                    'tratype'=> 'R', 
                    'crdr'=> $crdr, 
                    'accamount'=> $rowdatalist->accamount, 
                    'narration'=> $rowdatalist->narration, 
                    'totamount'=> $rowdatalist->totamount, 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_account_idtbl_account'=> $rowdatalist->tbl_account_idtbl_account,
                    'tbl_master_idtbl_master'=> $masterID,
                    'tbl_company_idtbl_company'=> $rowdatalist->tbl_company_idtbl_company,
                    'tbl_company_branch_idtbl_company_branch'=> $rowdatalist->tbl_company_branch_idtbl_company_branch
                );
                $this->db->insert('tbl_account_transaction_full', $datafull);

                $i++;
            }

            //Set delete petty cash summery record
            $datareimburse = array(
                'status'=> '3', 
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );
    
            $this->db->where('tbl_pettycash_reimburse_idtbl_pettycash_reimburse', $reimburseID);
            $this->db->update('tbl_pettycash_summary', $datareimburse);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-save';
                $actionObj->title='';
                $actionObj->message='Record Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='success';

                $actionJSON=json_encode($actionObj);
                
                $obj=new stdClass();
                $obj->status=1;
                $obj->action=$actionJSON;

                echo json_encode($obj);
            } else {
                $this->db->trans_rollback();

                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);
                
                $obj=new stdClass();
                $obj->status=0;
                $obj->action=$actionJSON;

                echo json_encode($obj);
            }
        }
        else if($respondcheck->row(0)->checkcount>0 && $respondcheckabove->row(0)->checkcountabove>0){
            $actionObj=new stdClass();
            $actionObj->icon='fas fa-exclamation-triangle';
            $actionObj->title='';
            $actionObj->message='Record Error, You can`t return this cheque because reimbursement entered after this cheque.';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='danger';

            $actionJSON=json_encode($actionObj);
            
            $obj=new stdClass();
            $obj->status=0;
            $obj->action=$actionJSON;

            echo json_encode($obj);
        }
        else{
            $this->db->trans_begin();

            // Update tbl_receivable
            $data = array(
                'chequereturn' => '1',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_cheque_issue', $recordID);
            $this->db->update('tbl_cheque_issue', $data);

            //Check payment settle info
            $this->db->select('tbl_account_paysettle_idtbl_account_paysettle');
            $this->db->from('tbl_account_paysettle_has_tbl_cheque_issue');
            $this->db->where('tbl_cheque_issue_idtbl_cheque_issue', $recordID);

            $respondcheckpay=$this->db->get();

            $paymentsettleID=$respondcheckpay->row(0)->tbl_account_paysettle_idtbl_account_paysettle;

            // Update tbl_account_paysettle
            $datapay = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_account_paysettle', $paymentsettleID);
            $this->db->update('tbl_account_paysettle', $datapay);

            // Update tbl_account_paysettle_info
            $datapaydetail = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('tbl_account_paysettle_idtbl_account_paysettle', $paymentsettleID);
            $this->db->update('tbl_account_paysettle_info', $datapaydetail);

            //Check Company info
            $this->db->select('tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch');
            $this->db->from('tbl_account_paysettle');
            $this->db->where('idtbl_account_paysettle', $paymentsettleID);

            $respondcombra=$this->db->get();

            // Check Journal Entry
            $this->db->select('tbl_account_transaction.*');
            $this->db->from('tbl_account_transaction');
            $this->db->join('tbl_receivable', 'tbl_receivable.batchno = tbl_account_transaction.trabatchotherno', 'left');
            $this->db->where('tbl_receivable.idtbl_receivable', $recordID);

            $respondtra=$this->db->get();

            $prefix=trans_prefix($respondcombra->row(0)->tbl_company_idtbl_company, $respondcombra->row(0)->tbl_company_branch_idtbl_company_branch);
            $batchno=tr_batch_num($prefix, $respondcombra->row(0)->tbl_company_branch_idtbl_company_branch);
            $masterdata=get_account_period($respondcombra->row(0)->tbl_company_idtbl_company, $respondcombra->row(0)->tbl_company_branch_idtbl_company_branch);
            $masterID=$masterdata->idtbl_master;

            $today=date('Y-m-d');

            $i=1;
            foreach($respondtra->result() as $rowdatalist){
                if($rowdatalist->crdr=='C'){$crdr='D';}
                else{$crdr='C';}

                $data = array(
                    'tradate'=> $today, 
                    'batchno'=> $batchno, 
                    'trabatchotherno'=> $rowdatalist->trabatchotherno, 
                    'tratype'=> 'R', 
                    'seqno'=> $i, 
                    'crdr'=> $crdr, 
                    'accamount'=> $rowdatalist->accamount, 
                    'narration'=> $rowdatalist->narration, 
                    'totamount'=> $rowdatalist->totamount, 
                    'reversstatus'=> '1', 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_account_idtbl_account'=> $rowdatalist->tbl_account_idtbl_account,
                    'tbl_master_idtbl_master'=> $masterID,
                    'tbl_company_idtbl_company'=> $rowdatalist->tbl_company_idtbl_company,
                    'tbl_company_branch_idtbl_company_branch'=> $rowdatalist->tbl_company_branch_idtbl_company_branch
                );
                $this->db->insert('tbl_account_transaction', $data);
        
                $datafull = array(
                    'tradate'=> $today, 
                    'batchno'=> $batchno, 
                    'tratype'=> 'R', 
                    'crdr'=> $crdr, 
                    'accamount'=> $rowdatalist->accamount, 
                    'narration'=> $rowdatalist->narration, 
                    'totamount'=> $rowdatalist->totamount, 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_account_idtbl_account'=> $rowdatalist->tbl_account_idtbl_account,
                    'tbl_master_idtbl_master'=> $masterID,
                    'tbl_company_idtbl_company'=> $rowdatalist->tbl_company_idtbl_company,
                    'tbl_company_branch_idtbl_company_branch'=> $rowdatalist->tbl_company_branch_idtbl_company_branch
                );
                $this->db->insert('tbl_account_transaction_full', $datafull);

                $i++;
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-save';
                $actionObj->title='';
                $actionObj->message='Record Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='success';

                $actionJSON=json_encode($actionObj);
                
                $obj=new stdClass();
                $obj->status=1;
                $obj->action=$actionJSON;

                echo json_encode($obj);
            } else {
                $this->db->trans_rollback();

                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);
                
                $obj=new stdClass();
                $obj->status=0;
                $obj->action=$actionJSON;

                echo json_encode($obj);
            }
        }    
    }
}