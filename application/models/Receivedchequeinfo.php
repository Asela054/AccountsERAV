<?php
class Receivedchequeinfo extends CI_Model{
    public function Receivedchequestatus(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];
        $recordID=$this->input->post('recordID');;
        $updatedatetime=date('Y-m-d H:i:s');

        // Update tbl_receivable
        $data = array(
            'chequereturn' => '1',
            'status'=> '1', 
            'updateuser'=> $userID, 
            'updatedatetime'=> $updatedatetime
        );

        $this->db->where('idtbl_receivable', $recordID);
        $this->db->update('tbl_receivable', $data);

        // Update tbl_receivable_info
        $data = array(
            'status'=> '2', 
            'updateuser'=> $userID, 
            'updatedatetime'=> $updatedatetime
        );

        $this->db->where('tbl_receivable_idtbl_receivable', $recordID);
        $this->db->update('tbl_receivable_info', $data);

        //Check Company info
        $this->db->select('tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch');
        $this->db->from('tbl_receivable');
        $this->db->where('idtbl_receivable', $recordID);

        $respond=$this->db->get();

        // Check Journal Entry
        $this->db->select('tbl_account_transaction.*');
        $this->db->from('tbl_account_transaction');
        $this->db->join('tbl_receivable', 'tbl_receivable.batchno = tbl_account_transaction.trabatchotherno', 'left');
        $this->db->where('tbl_receivable.idtbl_receivable', $recordID);

        $respondtra=$this->db->get();

        $prefix=trans_prefix($respond->row(0)->tbl_company_idtbl_company, $respond->row(0)->tbl_company_branch_idtbl_company_branch);
        $batchno=tr_batch_num($prefix, $respond->row(0)->tbl_company_branch_idtbl_company_branch);
        $masterdata=get_account_period($respond->row(0)->tbl_company_idtbl_company, $respond->row(0)->tbl_company_branch_idtbl_company_branch);
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