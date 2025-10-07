<?php
class BatchTransactionTypeinfo extends CI_Model{
    public function BatchTransactionTypeinsertupdate(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];
        $compantID=$_SESSION['companyid'];
        $branchID=$_SESSION['branchid'];

        $batchcategory=$this->input->post('batchcategory');
        $code=$this->input->post('code');
        $description=$this->input->post('description');

        $debitcredit=$this->input->post('debitcredit');
        if($debitcredit==1){$crdr='C';}else{$crdr='D';}

        $checktaxinfo=$this->input->post('checktaxinfo');
        $taxaccount=$this->input->post('taxaccount');
        $taxaccounttype=$this->input->post('taxaccounttype');

        $debitaccount=$this->input->post('debitaccount');
        $debitaccounttype=$this->input->post('debitaccounttype');

        $creditaccount=$this->input->post('creditaccount');
        $creditaccounttype=$this->input->post('creditaccounttype');

        $accountsarray = array();
        $obj=new stdClass();
        $obj->accountid=$debitaccount;
        $obj->accounttype=$debitaccounttype;
        $obj->accountcrdr='D';

        array_push($accountsarray, $obj);
        
        $obj=new stdClass();
        $obj->accountid=$creditaccount;
        $obj->accounttype=$creditaccounttype;
        $obj->accountcrdr='C';

        array_push($accountsarray, $obj);

        $recordOption=$this->input->post('recordOption');
        if(!empty($this->input->post('recordID'))){$recordID=$this->input->post('recordID');}

        $updatedatetime=date('Y-m-d H:i:s');

        if($recordOption==1){
            $data = array(
                'batctranstypecode'=> $code, 
                'batctranstype'=> $description, 
                'taxapply'=> $checktaxinfo, 
                'crdr'=> $crdr, 
                'status'=> '1', 
                'insertdatetime'=> $updatedatetime, 
                'tbl_user_idtbl_user'=> $userID,
                'tbl_batch_category_idtbl_batch_category'=> $batchcategory,
                'tbl_company_idtbl_company'=> $compantID,
                'tbl_company_branch_idtbl_company_branch'=> $branchID
            );

            $this->db->insert('tbl_batch_trans_type', $data);

            $batchtypeID=$this->db->insert_id();

            foreach($accountsarray as $listaccountsarray){
                $data = array(
                    'crdr'=> $listaccountsarray->accountcrdr,
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_batch_trans_type_idtbl_batch_trans_type'=> $batchtypeID
                );

                if ($listaccountsarray->accounttype == 1) {
                    $data['tbl_account_idtbl_account'] = $listaccountsarray->accountid;
                } else {
                    $data['tbl_account_detail_idtbl_account_detail'] = $listaccountsarray->accountid;
                }

                $this->db->insert('tbl_batch_trans_type_info', $data);
            }

            if($checktaxinfo==1){
                $data = array(
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_batch_trans_type_idtbl_batch_trans_type'=> $batchtypeID
                );

                if ($taxaccounttype == 1) {
                    $data['tbl_account_idtbl_account'] = $taxaccount;
                } else {
                    $data['tbl_account_detail_idtbl_account_detail'] = $taxaccount;
                }

                $this->db->insert('tbl_batch_trans_type_tax', $data);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-save';
                $actionObj->title='';
                $actionObj->message='Record Added Successfully';
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
        else{
            $data = array(
                'batctranstypecode'=> $code, 
                'batctranstype'=> $description, 
                'taxapply'=> $checktaxinfo, 
                'crdr'=> $crdr, 
                'status'=> '1', 
                'insertdatetime'=> $updatedatetime, 
                'tbl_user_idtbl_user'=> $userID,
                'tbl_batch_category_idtbl_batch_category'=> $batchcategory,
                'tbl_company_idtbl_company'=> $compantID,
                'tbl_company_branch_idtbl_company_branch'=> $branchID
            );

            $this->db->where('idtbl_batch_trans_type', $recordID);
            $this->db->update('tbl_batch_trans_type', $data);

            $this->db->delete('tbl_batch_trans_type_info', array('tbl_batch_trans_type_idtbl_batch_trans_type' => $recordID));

            foreach($accountsarray as $listaccountsarray){
                $data = array(
                    'crdr'=> $listaccountsarray->accountcrdr,
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_batch_trans_type_idtbl_batch_trans_type'=> $recordID
                );

                if ($listaccountsarray->accounttype == 1) {
                    $data['tbl_account_idtbl_account'] = $listaccountsarray->accountid;
                } else {
                    $data['tbl_account_detail_idtbl_account_detail'] = $listaccountsarray->accountid;
                }

                $this->db->insert('tbl_batch_trans_type_info', $data);
            }

            $this->db->delete('tbl_batch_trans_type_tax', array('tbl_batch_trans_type_idtbl_batch_trans_type' => $recordID));

            if($checktaxinfo==1){
                $data = array(
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_batch_trans_type_idtbl_batch_trans_type'=> $recordID
                );

                if ($taxaccounttype == 1) {
                    $data['tbl_account_idtbl_account'] = $taxaccount;
                } else {
                    $data['tbl_account_detail_idtbl_account_detail'] = $taxaccount;
                }

                $this->db->insert('tbl_batch_trans_type_tax', $data);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-save';
                $actionObj->title='';
                $actionObj->message='Record Update Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='primary';

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
    public function BatchTransactionTypestatus($x, $y){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];
        $recordID=$x;
        $type=$y;
        $updatedatetime=date('Y-m-d H:i:s');

        if($type==1){
            $data = array(
                'status' => '1',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_batch_trans_type', $recordID);
            $this->db->update('tbl_batch_trans_type', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-check';
                $actionObj->title='';
                $actionObj->message='Record Activate Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='success';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('BatchTransactionType');                
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
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('BatchTransactionType');
            }
        }
        else if($type==2){
            $data = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_batch_trans_type', $recordID);
            $this->db->update('tbl_batch_trans_type', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-times';
                $actionObj->title='';
                $actionObj->message='Record Deactivate Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='warning';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('BatchTransactionType');                
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
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('BatchTransactionType');
            }
        }
        else if($type==3){
            $data = array(
                'status' => '3',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_batch_trans_type', $recordID);
            $this->db->update('tbl_batch_trans_type', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-trash-alt';
                $actionObj->title='';
                $actionObj->message='Record Remove Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('BatchTransactionType');                
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
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('BatchTransactionType');
            }
        }
    }
    public function BatchTransactionTypeedit(){
        $recordID=$this->input->post('recordID');

        $this->db->select('*');
        $this->db->from('tbl_batch_trans_type');
        $this->db->where('idtbl_batch_trans_type', $recordID);
        $this->db->where('status', 1);

        $respond=$this->db->get();

        $this->db->select('tbl_batch_trans_type_info.*, tbl_account_detail.idtbl_account_detail, tbl_account_detail.accountno, tbl_account_detail.accountname, tbl_account.idtbl_account, tbl_account.accountno AS chartaccountno, tbl_account.accountname AS chartaccountname');
        $this->db->from('tbl_batch_trans_type_info');
        $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_batch_trans_type_info.tbl_account_idtbl_account', 'left');
        $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_batch_trans_type_info.tbl_account_detail_idtbl_account_detail', 'left');
        $this->db->where('tbl_batch_trans_type_info.tbl_batch_trans_type_idtbl_batch_trans_type', $recordID);
        $this->db->where('tbl_batch_trans_type_info.status', 1);

        $responddetail=$this->db->get();

        $this->db->select('tbl_account_detail.idtbl_account_detail, tbl_account_detail.accountno, tbl_account_detail.accountname, tbl_account.idtbl_account, tbl_account.accountno AS chartaccountno, tbl_account.accountname AS chartaccountname');
        $this->db->from('tbl_batch_trans_type_tax');
        $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_batch_trans_type_tax.tbl_account_idtbl_account', 'left');
        $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_batch_trans_type_tax.tbl_account_detail_idtbl_account_detail', 'left');
        $this->db->where('tbl_batch_trans_type_tax.tbl_batch_trans_type_idtbl_batch_trans_type', $recordID);
        $this->db->where('tbl_batch_trans_type_tax.status', 1);

        $respondtax=$this->db->get();

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_batch_trans_type;
        $obj->batctranstypecode=$respond->row(0)->batctranstypecode;
        $obj->batctranstype=$respond->row(0)->batctranstype;
        $obj->taxapply=$respond->row(0)->taxapply;
        $obj->batchcategory=$respond->row(0)->tbl_batch_category_idtbl_batch_category;
        if($respond->row(0)->crdr=='C'){$obj->crdr=1;}
        if($respond->row(0)->crdr=='D'){$obj->crdr=2;}
        

        foreach($responddetail->result() as $rowdetail){
            if($rowdetail->crdr=='C'){
                // $obj->accounttypecr=$rowdetail->tbl_account_type_idtbl_account_type;
                if(!empty($rowdetail->idtbl_account_detail)){
                    $obj->accountcrid=$rowdetail->idtbl_account_detail;
                    $obj->accountcr=$rowdetail->accountname.' - '.$rowdetail->accountno;
                    $obj->accounttypecr=2;
                }
                else{
                    $obj->accountcrid=$rowdetail->idtbl_account;
                    $obj->accountcr=$rowdetail->chartaccountname.' - '.$rowdetail->chartaccountno;
                    $obj->accounttypecr=1;
                }
            }
            else if($rowdetail->crdr=='D'){
                // $obj->accounttypedr=$rowdetail->tbl_account_type_idtbl_account_type;
                if(!empty($rowdetail->idtbl_account_detail)){
                    $obj->accountdrid=$rowdetail->idtbl_account_detail;
                    $obj->accountdr=$rowdetail->accountname.' - '.$rowdetail->accountno;
                    $obj->accounttypedr=2;
                }
                else{
                    $obj->accountdrid=$rowdetail->idtbl_account;
                    $obj->accountdr=$rowdetail->chartaccountname.' - '.$rowdetail->chartaccountno;
                    $obj->accounttypedr=1;
                }
            }
        }

        if(!empty($respondtax->result())){
            foreach($respondtax->result() as $rowtaxdetail){
                if(!empty($rowtaxdetail->idtbl_account_detail)){
                    $obj->accounttaxid=$rowtaxdetail->idtbl_account_detail;
                    $obj->accounttax=$rowtaxdetail->accountname.' - '.$rowtaxdetail->accountno;
                    $obj->accounttypetax=2;
                }
                else{
                    $obj->accounttaxid=$rowtaxdetail->idtbl_account;
                    $obj->accounttax=$rowtaxdetail->chartaccountname.' - '.$rowtaxdetail->chartaccountno;
                    $obj->accounttypetax=1;
                }
            }
        }

        echo json_encode($obj);
    }
    public function Getbatchcategory(){
        $this->db->select('`idtbl_batch_category`, `batch_category`');
        $this->db->from('tbl_batch_category');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
    public function Gettransactiontype(){
        $this->db->select('`idtbl_account_transactiontype`, `transactiontype`');
        $this->db->from('tbl_account_transactiontype');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
}