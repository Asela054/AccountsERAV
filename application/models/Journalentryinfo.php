<?php
class Journalentryinfo extends CI_Model{
    public function Journalentryinsertupdate(){
        $userID=$_SESSION['userid'];

        $company=$this->input->post('company');
        $branch=$this->input->post('branch');
        $tradate=$this->input->post('tradate');
        $traamount=$this->input->post('traamount');
        $accountcrno=$this->input->post('accountcrno');
        $narrationcr=$this->input->post('narrationcr');
        $accountdrno=$this->input->post('accountdrno');
        $narrationdr=$this->input->post('narrationdr');
        $accounttypecr=$this->input->post('accounttypecr');
        $accounttypedr=$this->input->post('accounttypedr');

        $fullnarration=$narrationcr.' & '.$narrationdr;
        
        $recordOption=$this->input->post('recordOption');
        if(!empty($this->input->post('recordID'))){$recordID=$this->input->post('recordID');}

        $creditchartaccount=0;
        $debitchartaccount=0;
        $creditdetailaccount=0;
        $debitdetailaccount=0;

        if($accounttypecr==1){$creditchartaccount=$accountcrno;}
        else{$creditdetailaccount=$accountcrno;}

        if($accounttypedr==1){$debitchartaccount=$accountdrno;}
        else{$debitdetailaccount=$accountdrno;}

        if($recordOption==1){
            $prefix=journal_prefix($company, $branch);
            $masterdata=get_account_period($company, $branch);
            $batchno=tr_batch_num($prefix, $branch);
            $masterID=$masterdata->idtbl_master;
        }


        $updatedatetime=date('Y-m-d H:i:s');
        $today=date('Y-m-d');

        if($recordOption==1){
            if(!empty($batchno)){
                $this->db->trans_begin();

                $data = array(
                    'tradate'=> $tradate, 
                    'batchno'=> $batchno, 
                    'amount'=> $traamount, 
                    'narration'=> $fullnarration, 
                    'poststatus'=> '0', 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_master_idtbl_master'=> $masterID,
                    'tbl_company_idtbl_company'=> $company,
                    'tbl_company_branch_idtbl_company_branch'=> $branch
                );

                $this->db->insert('tbl_account_transaction_manual_main', $data);

                $journalmainID=$this->db->insert_id();

                //Credit Entry
                $data1 = array(
                    'tradate'=> $tradate, 
                    'batchno'=> $batchno, 
                    'tratype'=> 'J', 
                    'seqno'=> '1', 
                    'crdr'=> 'C', 
                    'amount'=> $traamount, 
                    'narration'=> $narrationcr, 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_account_idtbl_account'=> $creditchartaccount,
                    'tbl_account_detail_idtbl_account_detail'=> $creditdetailaccount,
                    'tbl_master_idtbl_master'=> $masterID,
                    'tbl_company_idtbl_company'=> $company,
                    'tbl_company_branch_idtbl_company_branch'=> $branch,
                    'manualtrans_main_id'=> $journalmainID
                );

                $this->db->insert('tbl_account_transaction_manual', $data1);

                //Debit Entry
                $data2 = array(
                    'tradate'=> $tradate, 
                    'batchno'=> $batchno, 
                    'tratype'=> 'J', 
                    'seqno'=> '2', 
                    'crdr'=> 'D', 
                    'amount'=> $traamount, 
                    'narration'=> $narrationdr, 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_account_idtbl_account'=> $debitchartaccount,
                    'tbl_account_detail_idtbl_account_detail'=> $debitdetailaccount,
                    'tbl_master_idtbl_master'=> $masterID,
                    'tbl_company_idtbl_company'=> $company,
                    'tbl_company_branch_idtbl_company_branch'=> $branch,
                    'manualtrans_main_id'=> $journalmainID
                );

                $this->db->insert('tbl_account_transaction_manual', $data2);

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
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error, Batch no defind by system';
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
            $this->db->trans_begin();

            $this->db->select('batchno, tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch, tbl_master_idtbl_master, poststatus');
            $this->db->from('tbl_account_transaction_manual_main');
            $this->db->where('idtbl_account_transaction_manual_main', $recordID);
            $this->db->where('status', 1);

            $respond=$this->db->get();

            if($respond->row(0)->poststatus==0){
                $this->db->where('manualtrans_main_id', $recordID);
                $this->db->delete('tbl_account_transaction_manual');

                $data = array(
                    'tradate' => $tradate,
                    'amount' => $traamount,
                    'narration' => $fullnarration,
                    'editstatus' => '0',
                    'updateuser'=> $userID, 
                    'updatedatetime'=> $updatedatetime
                );
        
                $this->db->where('idtbl_account_transaction_manual_main', $recordID);
                $this->db->update('tbl_account_transaction_manual_main', $data);

                //Credit Entry
                $data1 = array(
                    'tradate'=> $tradate, 
                    'batchno'=> $respond->row(0)->batchno, 
                    'tratype'=> 'J', 
                    'seqno'=> '1', 
                    'crdr'=> 'C', 
                    'amount'=> $traamount, 
                    'narration'=> $narrationcr, 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_account_idtbl_account'=> $creditchartaccount,
                    'tbl_account_detail_idtbl_account_detail'=> $creditdetailaccount,
                    'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
                    'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
                    'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch,
                    'manualtrans_main_id'=> $recordID
                );

                $this->db->insert('tbl_account_transaction_manual', $data1);

                //Debit Entry
                $data2 = array(
                    'tradate'=> $tradate, 
                    'batchno'=> $respond->row(0)->batchno, 
                    'tratype'=> 'J', 
                    'seqno'=> '2', 
                    'crdr'=> 'D', 
                    'amount'=> $traamount, 
                    'narration'=> $narrationdr, 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_account_idtbl_account'=> $debitchartaccount,
                    'tbl_account_detail_idtbl_account_detail'=> $debitdetailaccount,
                    'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
                    'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
                    'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch,
                    'manualtrans_main_id'=> $recordID
                );

                $this->db->insert('tbl_account_transaction_manual', $data2);

                $this->db->trans_complete();
                if ($this->db->trans_status() === TRUE) {
                    $this->db->trans_commit();
                    
                    $actionObj=new stdClass();
                    $actionObj->icon='fas fa-save';
                    $actionObj->title='';
                    $actionObj->message='Record Update Successfully';
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
                $this->db->trans_commit();

                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error. This record already posted.';
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
    public function Journalentrystatus($x, $y){
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

            $this->db->where('idtbl_account_transaction_manual_main', $recordID);
            $this->db->update('tbl_account_transaction_manual_main', $data);

            $datapay = array(
                'status' => '1',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('manualtrans_main_id', $recordID);
            $this->db->update('tbl_account_transaction_manual', $datapay);

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
                redirect('Journalentry');                
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
                redirect('Journalentry');
            }
        }
        else if($type==2){
            $data = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_account_transaction_manual_main', $recordID);
            $this->db->update('tbl_account_transaction_manual_main', $data);

            $datapay = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('manualtrans_main_id', $recordID);
            $this->db->update('tbl_account_transaction_manual', $datapay);

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
                redirect('Journalentry');                
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
                redirect('Journalentry');
            }
        }
        else if($type==3){
            $data = array(
                'status' => '3',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_account_transaction_manual_main', $recordID);
            $this->db->update('tbl_account_transaction_manual_main', $data);

            $datapay = array(
                'status' => '3',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('manualtrans_main_id', $recordID);
            $this->db->update('tbl_account_transaction_manual', $datapay);

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
                redirect('Journalentry');                
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
                redirect('Journalentry');
            }
        }
    }
    public function Journalentryedit(){
        $recordID=$this->input->post('recordID');
        $userID=$_SESSION['userid'];
        $updatedatetime=date('Y-m-d H:i:s');

        $this->db->select('`transactiontype`');
        $this->db->from('tbl_account_transaction_manual_main');
        $this->db->where('idtbl_account_transaction_manual_main', $recordID);
        $this->db->where('status', 1);

        $respondcheck=$this->db->get();

        if($respondcheck->row(0)->transactiontype==0):
            $data = array(
                'editstatus' => '1',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_account_transaction_manual_main', $recordID);
            $this->db->update('tbl_account_transaction_manual_main', $data);

            $datapay = array(
                'editstatus' => '1',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('manualtrans_main_id', $recordID);
            $this->db->update('tbl_account_transaction_manual', $datapay);

            $this->db->select('tbl_account_transaction_manual_main.*, tbl_company.company, tbl_company_branch.branch');
            $this->db->from('tbl_account_transaction_manual_main');
            $this->db->join('tbl_company', 'tbl_company.idtbl_company = tbl_account_transaction_manual_main.tbl_company_idtbl_company', 'left');
            $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_account_transaction_manual_main.tbl_company_branch_idtbl_company_branch', 'left');
            $this->db->where('tbl_account_transaction_manual_main.idtbl_account_transaction_manual_main', $recordID);
            $this->db->where('tbl_account_transaction_manual_main.status', 1);
            
            $respond=$this->db->get();
            
            $this->db->select('tbl_account_transaction_manual.narration, tbl_account_transaction_manual.crdr, tbl_account_detail.idtbl_account_detail, tbl_account_detail.accountno, tbl_account_detail.accountname, tbl_account.idtbl_account, tbl_account.accountno AS chartaccountno, tbl_account.accountname AS chartaccountname');
            $this->db->from('tbl_account_transaction_manual');
            $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_account_transaction_manual.tbl_account_idtbl_account', 'left');
            $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_account_transaction_manual.tbl_account_detail_idtbl_account_detail', 'left');
            $this->db->where('tbl_account_transaction_manual.manualtrans_main_id', $recordID);
            $this->db->where('tbl_account_transaction_manual.status', 1);

            $responddetail=$this->db->get();

            $obj=new stdClass();
            $obj->id=$respond->row(0)->idtbl_account_transaction_manual_main;
            $obj->tradate=$respond->row(0)->tradate;
            $obj->amount=$respond->row(0)->amount;
            $obj->companyid=$respond->row(0)->tbl_company_idtbl_company;
            $obj->branchid=$respond->row(0)->tbl_company_branch_idtbl_company_branch;
            $obj->company=$respond->row(0)->company;
            $obj->branch=$respond->row(0)->branch;
            $obj->transactiontype=$respond->row(0)->transactiontype;

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
                    $obj->narrationcr=$rowdetail->narration;
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
                    $obj->narrationdr=$rowdetail->narration;
                }
            }
        else:
            $this->db->select('*');
            $this->db->from('tbl_account_transaction_manual_main');
            $this->db->where('tbl_account_transaction_manual_main.idtbl_account_transaction_manual_main', $recordID);
            $this->db->where('tbl_account_transaction_manual_main.status', 1);
            
            $respond=$this->db->get();

            $obj=new stdClass();
            $obj->id=$respond->row(0)->idtbl_account_transaction_manual_main;
            $obj->tradate=$respond->row(0)->tradate;
            $obj->batchno=$respond->row(0)->batchno;
            $obj->masterID=$respond->row(0)->tbl_master_idtbl_master;
            $obj->amount=$respond->row(0)->amount;
            $obj->transactiontype=$respond->row(0)->transactiontype;

            $this->db->select('tbl_account_transaction_manual.idtbl_account_transaction_manual, tbl_account_transaction_manual.tradate, tbl_account_transaction_manual.narration, tbl_account_transaction_manual.crdr, tbl_account_transaction_manual.batchno, tbl_account_transaction_manual.narration, tbl_account_transaction_manual.amount, tbl_account_detail.idtbl_account_detail, tbl_account_detail.accountno, tbl_account_detail.accountname, tbl_account.idtbl_account, tbl_account.accountno AS chartaccountno, tbl_account.accountname AS chartaccountname');
            $this->db->from('tbl_account_transaction_manual');
            $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_account_transaction_manual.tbl_account_idtbl_account', 'left');
            $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_account_transaction_manual.tbl_account_detail_idtbl_account_detail', 'left');
            $this->db->where('tbl_account_transaction_manual.manualtrans_main_id', $recordID);
            $this->db->where('tbl_account_transaction_manual.status', 1);

            $responddetail=$this->db->get();

            $html='';
            foreach($responddetail->result() as $rowdatalist):
                if(!empty($rowdatalist->idtbl_account)):
                    $accountID=$rowdatalist->idtbl_account;
                    $accountno=$rowdatalist->chartaccountname.' - '.$rowdatalist->chartaccountno;
                else:
                    $accountID=$rowdatalist->idtbl_account_detail;
                    $accountno=$rowdatalist->accountname.' - '.$rowdatalist->accountno;
                endif;

                if($rowdatalist->crdr=='C'):
                    $crdrtype=1;
                    $creditamount=$rowdatalist->amount;
                    $debitamount=0;
                else:
                    $crdrtype=2;
                    $creditamount=0;
                    $debitamount=$rowdatalist->amount;
                endif;

                $html.='
                <tr>
                    <td class="d-none">'.$rowdatalist->idtbl_account_transaction_manual.'</td>
                    <td>'.$rowdatalist->tradate.'</td>
                    <td class="d-none">'.$accountID.'</td>
                    <td>'.$accountno.'</td>
                    <td class="d-none">'.$crdrtype.'</td>
                    <td class="text-center">'.$rowdatalist->crdr.'</td>
                    <td>'.$rowdatalist->batchno.'</td>
                    <td>'.$rowdatalist->narration.'</td>
                    <td class="text-right">'.number_format($debitamount, 2).'</td>
                    <td class="text-right">'.number_format($creditamount, 2).'</td>
                </tr>
                ';
            endforeach;

            $obj->tablecontent=$html;
        endif;
        echo json_encode($obj);
    }
    public function Gettypelist(){
        $this->db->select('`idtbl_account_type`, `accounttype`');
        $this->db->from('tbl_account_type');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
    public function Gettransactiontypelist(){
        $this->db->select('`idtbl_account_transactiontype`, `transactiontype`');
        $this->db->from('tbl_account_transactiontype');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
    public function Getviewpostinfo(){
        $recordID=$this->input->post('recordID');
        $updatedatetime=date('Y-m-d H:i:s');

        $data = array(
            'postviewtime'=> $updatedatetime
        );

        $this->db->where('idtbl_account_transaction_manual_main', $recordID);
        $this->db->where('poststatus', 0);
        $this->db->update('tbl_account_transaction_manual_main', $data);

        $this->db->select('tbl_account_transaction_manual_main.*, tbl_company.company, tbl_company_branch.branch');
        $this->db->from('tbl_account_transaction_manual_main');
        $this->db->join('tbl_company', 'tbl_company.idtbl_company = tbl_account_transaction_manual_main.tbl_company_idtbl_company', 'left');
        $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_account_transaction_manual_main.tbl_company_branch_idtbl_company_branch', 'left');
        $this->db->where('tbl_account_transaction_manual_main.idtbl_account_transaction_manual_main', $recordID);
        // $this->db->where('tbl_account_payable_main.status', 1);

        $respond=$this->db->get();

        $this->db->select('tbl_account_transaction_manual.*, tbl_account_transaction_manual.tbl_account_detail_idtbl_account_detail, tbl_account_detail.accountno, tbl_account_detail.accountname, tbl_account_transaction_manual.tbl_account_idtbl_account, tbl_account.accountno AS chartaccountno, tbl_account.accountname AS chartaccountname');
        $this->db->from('tbl_account_transaction_manual');
        $this->db->join('tbl_account_transaction_manual_main', 'tbl_account_transaction_manual_main.idtbl_account_transaction_manual_main = tbl_account_transaction_manual.manualtrans_main_id', 'left');
        $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_account_transaction_manual.tbl_account_idtbl_account', 'left');
        $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_account_transaction_manual.tbl_account_detail_idtbl_account_detail', 'left');
        $this->db->where('tbl_account_transaction_manual.manualtrans_main_id', $recordID);
        $this->db->where('tbl_account_transaction_manual.status', 1);

        $respondpayinfo=$this->db->get();

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
        }
        $html.='
        <div class="row">
            <div class="col">
                <label class="small font-weight-bold my-0">Batch No: </label>
                <label class="small my-0">'.$respond->row(0)->batchno.'</label><br>
                <label class="small font-weight-bold my-0">Date: </label>
                <label class="small my-0">'.$respond->row(0)->tradate.'</label><br>
                <label class="small font-weight-bold my-0">Company/Branch: </label>
                <label class="small my-0">'.$respond->row(0)->company.'-'.$respond->row(0)->branch.'</label>
            </div>
            <div class="col">
                <label class="small font-weight-bold my-0">Narration: </label>
                <label class="small my-0">'.$respond->row(0)->narration.'</label><br>
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
                            <th>C/D</th>
                            <th>Batch No</th>
                            <th>Narration</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>';
                    foreach($respondpayinfo->result() as $rowdatainfo){
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
                            <td>'.$rowdatainfo->crdr.'</td>
                            <td>'.$rowdatainfo->batchno.'</td>
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
    public function Journalentryposting(){
        $recordID=$this->input->post('recordID');
        $updatedatetime=date('Y-m-d H:i:s');
        $today=date('Y-m-d');
        $userID=$_SESSION['userid'];

        $i=0;

        $this->db->select('poststatus, status, editstatus, postviewtime, completestatus, updatedatetime, tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch');
        $this->db->from('tbl_account_transaction_manual_main');
        $this->db->where('idtbl_account_transaction_manual_main', $recordID);
        $this->db->where('status', 1);

        $respond=$this->db->get();

        $this->db->select('*');
        $this->db->from('tbl_account_transaction_manual');
        $this->db->where('manualtrans_main_id', $recordID);
        $this->db->where('status', 1);

        $responddetail=$this->db->get();

        if($respond->row(0)->poststatus==0 && $respond->row(0)->status==1 && $respond->row(0)->editstatus==0 && $respond->row(0)->completestatus==1){
            if($respond->row(0)->postviewtime>$respond->row(0)->updatedatetime){
                $this->db->trans_start();
                
                $data = array(
                    'poststatus'=> '1',
                    'postuser'=> $userID,
                    'postviewtime'=> NULL
                );
        
                $this->db->where('idtbl_account_transaction_manual_main', $recordID);
                $this->db->update('tbl_account_transaction_manual_main', $data);

                $i=1;
                
                $prefix=trans_prefix($respond->row(0)->tbl_company_idtbl_company, $respond->row(0)->tbl_company_branch_idtbl_company_branch);
                $batchno=tr_batch_num($prefix, $respond->row(0)->tbl_company_branch_idtbl_company_branch);

                foreach($responddetail->result() as $rowdatalist){
                    if($rowdatalist->tbl_account_detail_idtbl_account_detail>0){
                        $this->load->model('Journalentryinfo');
                        $chartofaccount=$this->Journalentryinfo->Chartofaccountaccodetail($rowdatalist->tbl_account_detail_idtbl_account_detail);
                    }
                    else{
                        $chartofaccount=$rowdatalist->tbl_account_idtbl_account;
                    }

                    $datacredit = array(
                        'tradate'=> $rowdatalist->tradate, 
                        'batchno'=> $batchno, 
                        'trabatchotherno'=> $rowdatalist->batchno, 
                        'tratype'=> 'J', 
                        'seqno'=> $i, 
                        'crdr'=> $rowdatalist->crdr, 
                        'accamount'=> $rowdatalist->amount, 
                        'narration'=> $rowdatalist->narration, 
                        'totamount'=> $rowdatalist->amount, 
                        'status'=> '1', 
                        'insertdatetime'=> $updatedatetime, 
                        'tbl_user_idtbl_user'=> $userID,
                        'tbl_account_idtbl_account'=> $chartofaccount,
                        'tbl_master_idtbl_master'=> $rowdatalist->tbl_master_idtbl_master,
                        'tbl_company_idtbl_company'=> $rowdatalist->tbl_company_idtbl_company,
                        'tbl_company_branch_idtbl_company_branch'=> $rowdatalist->tbl_company_branch_idtbl_company_branch
                    );
                    $this->db->insert('tbl_account_transaction', $datacredit);
            
                    $datacreditfull = array(
                        'tradate'=> $rowdatalist->tradate, 
                        'batchno'=> $batchno, 
                        'tratype'=> 'J', 
                        'crdr'=> $rowdatalist->crdr, 
                        'accamount'=> $rowdatalist->amount, 
                        'narration'=> $rowdatalist->narration, 
                        'totamount'=> $rowdatalist->amount, 
                        'status'=> '1', 
                        'insertdatetime'=> $updatedatetime, 
                        'tbl_user_idtbl_user'=> $userID,
                        'tbl_account_idtbl_account'=> $chartofaccount,
                        'tbl_master_idtbl_master'=> $rowdatalist->tbl_master_idtbl_master,
                        'tbl_company_idtbl_company'=> $rowdatalist->tbl_company_idtbl_company,
                        'tbl_company_branch_idtbl_company_branch'=> $rowdatalist->tbl_company_branch_idtbl_company_branch
                    );
                    $this->db->insert('tbl_account_transaction_full', $datacreditfull);

                    //Pety cash float increase via jurnal entry
                    $this->db->select('specialcate');
                    $this->db->from('tbl_account');
                    $this->db->where('idtbl_account', $chartofaccount);
                    $this->db->where('status', 1);

                    $respondspecat=$this->db->get();

                    if($rowdatalist->crdr=='D' && $respondspecat->row(0)->specialcate==36){
                        $this->db->select('count(*) AS countdata');
                        $this->db->from('tbl_pettycash_summary');
                        $this->db->where('tbl_account_idtbl_account', $rowdatalist->tbl_account_idtbl_account);
                        $this->db->where('status', 1);

                        $respondpettycashsummery=$this->db->get();

                        if($respondpettycashsummery->row(0)->countdata==0){
                            //Petty Cash Summery
                            $datapettysummery = array(
                                'date'=> $today, 
                                'openbal'=> '0', 
                                'postbal'=> '0', 
                                'reimbal'=> $rowdatalist->amount, 
                                'closebal'=> $rowdatalist->amount, 
                                'status'=> 1, 
                                'insertdatetime'=> $updatedatetime,
                                'tbl_user_idtbl_user'=> $userID, 
                                'tbl_account_idtbl_account'=> $rowdatalist->tbl_account_idtbl_account, 
                                'tbl_company_idtbl_company'=> $rowdatalist->tbl_company_idtbl_company, 
                                'tbl_company_branch_idtbl_company_branch'=> $rowdatalist->tbl_company_branch_idtbl_company_branch, 
                                'tbl_master_idtbl_master'=> $rowdatalist->tbl_master_idtbl_master
                            );
                            $this->db->insert('tbl_pettycash_summary', $datapettysummery);
                        }
                        else{
                            $this->db->select('`closebal`, `tbl_account_idtbl_account`');
                            $this->db->from('tbl_pettycash_summary');
                            $this->db->where('tbl_company_idtbl_company', $rowdatalist->tbl_company_idtbl_company);
                            $this->db->where('tbl_company_branch_idtbl_company_branch', $rowdatalist->tbl_company_branch_idtbl_company_branch);
                            $this->db->where('status', 1);
                            $this->db->order_by('idtbl_pettycash_summary', 'DESC');
                            $this->db->limit(1);

                            $respond=$this->db->get();

                            $newclosebalance=$respond->row(0)->closebal+$rowdatalist->amount;

                            //Petty Cash Summery
                            $datapettysummery = array(
                                'date'=> $today, 
                                'openbal'=> $respond->row(0)->closebal, 
                                'postbal'=> '0', 
                                'reimbal'=> $rowdatalist->amount, 
                                'closebal'=> $newclosebalance, 
                                'status'=> 1, 
                                'insertdatetime'=> $updatedatetime,
                                'tbl_user_idtbl_user'=> $userID, 
                                'tbl_account_idtbl_account'=> $rowdatalist->tbl_account_idtbl_account, 
                                'tbl_company_idtbl_company'=> $rowdatalist->tbl_company_idtbl_company, 
                                'tbl_company_branch_idtbl_company_branch'=> $rowdatalist->tbl_company_branch_idtbl_company_branch, 
                                'tbl_master_idtbl_master'=> $rowdatalist->tbl_master_idtbl_master
                            );  
                            $this->db->insert('tbl_pettycash_summary', $datapettysummery);
                        }
                    }

                    $i++;
                }

                $this->db->trans_complete();

                if ($this->db->trans_status() === TRUE) {
                    $this->db->trans_commit();
                    
                    $actionObj=new stdClass();
                    $actionObj->icon='fas fa-save';
                    $actionObj->title='';
                    $actionObj->message='Record Post Successfully';
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
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error, Please check this record for information. Because this record was edited before you posted.';
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
        else if($respond->row(0)->status==2){
            $actionObj=new stdClass();
            $actionObj->icon='fas fa-warning';
            $actionObj->title='';
            $actionObj->message='Record Error, Record Deactivated. Kindly review the status of the record.';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='warning';

            $actionJSON=json_encode($actionObj);
            
            $obj=new stdClass();
            $obj->status=0;
            $obj->action=$actionJSON;

            echo json_encode($obj);
        }
        else if($respond->row(0)->editstatus==1){
            $actionObj=new stdClass();
            $actionObj->icon='fas fa-warning';
            $actionObj->title='';
            $actionObj->message='Record Error, Record in editable mode. You cannot change anything about the record.';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='danger';

            $actionJSON=json_encode($actionObj);
            
            $obj=new stdClass();
            $obj->status=0;
            $obj->action=$actionJSON;

            echo json_encode($obj);
        }
        else if($respond->row(0)->completestatus==0){
            $actionObj=new stdClass();
            $actionObj->icon='fas fa-warning';
            $actionObj->title='';
            $actionObj->message='This journal batch transaction not complete yet. Firstly complete this batch transaction.';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='danger';

            $actionJSON=json_encode($actionObj);
            
            $obj=new stdClass();
            $obj->status=0;
            $obj->action=$actionJSON;

            echo json_encode($obj);
        }
        else if($respond->row(0)->poststatus==1){
            $actionObj=new stdClass();
            $actionObj->icon='fas fa-warning';
            $actionObj->title='';
            $actionObj->message='Record Error, Record already posted.';
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
    public function Getglpassdatalist(){
        $updatedatetime=date('Y-m-d H:i:s');

        $this->db->select('tbl_other_payincome.*, tbl_customer.customer, tbl_supplier.suppliername');
        $this->db->from('tbl_other_payincome');
        $this->db->join('tbl_customer', 'tbl_customer.idtbl_customer = tbl_other_payincome.customer', 'left');
        $this->db->join('tbl_supplier', 'tbl_supplier.idtbl_supplier = tbl_other_payincome.supplier', 'left');
        $this->db->where('tbl_other_payincome.status', 1);
        $this->db->where('tbl_other_payincome.glapply', 0);
        $respond=$this->db->get();

        $html='';
        foreach($respond->result() as $rowdatainfo){
            $suppcus='';
            if(!empty($rowdatainfo->customer)){$suppcus=$rowdatainfo->customer;}
            else if(!empty($rowdatainfo->suppliername)){$suppcus=$rowdatainfo->suppliername;}
            $html.='<tr>
                <td width="5%" class="text-center">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheck'.$rowdatainfo->idtbl_other_payincome.'">
                        <label class="custom-control-label" for="customCheck'.$rowdatainfo->idtbl_other_payincome.'"></label>
                    </div>
                </td>
                <td>'.$rowdatainfo->date.'</td>
                <td>'.$suppcus.'</td>
                <td>'.$rowdatainfo->invreceno.'</td>
                <td>'.$rowdatainfo->narration.'</td>
                <td class="text-right">'.number_format($rowdatainfo->amount, 2).'</td>
                <th class="d-none recordid">'.$rowdatainfo->idtbl_other_payincome.'</th>
            </tr>';
        }

        echo $html;
    }
    public function Passtoglentry(){
        $this->db->trans_begin();
        $updatedatetime=date('Y-m-d H:i:s');
        $today=date('Y-m-d');
        $userID=$_SESSION['userid'];

        $creditchartaccount=0;
        $debitchartaccount=0;
        $creditdetailaccount=0;
        $debitdetailaccount=0;

        $gltradate=$this->input->post('gltradate');
        $glaccountcrno=$this->input->post('glaccountcrno');
        $glaccountdrno=$this->input->post('glaccountdrno');
        $company=$this->input->post('company');
        $branch=$this->input->post('branch');
        $glaccounttypecr=$this->input->post('glaccounttypecr');
        $glaccounttypedr=$this->input->post('glaccounttypedr');
        $datalist=json_decode($this->input->post('tabledata'));

        if($glaccounttypecr==1){$creditchartaccount=$glaccountcrno;}
        else{$creditdetailaccount=$glaccountcrno;}

        if($glaccounttypedr==1){$debitchartaccount=$glaccountdrno;}
        else{$debitdetailaccount=$glaccountdrno;}

        foreach($datalist as $rowdatalist){
            $recordID=$rowdatalist->recordid;

            $this->db->select('`date`, `invreceno`, `amount`, `narration`');
            $this->db->from('tbl_other_payincome');
            $this->db->where('idtbl_other_payincome', $recordID);
            $this->db->where('status', 1);

            $respond=$this->db->get();

            $prefix=journal_prefix($company, $branch);
            $masterdata=get_account_period($company, $branch);
            $batchno=tr_batch_num($prefix, $branch);
            $masterID=$masterdata->idtbl_master;

            if(!empty($batchno)){
                $this->db->trans_begin();

                $tradate=$gltradate;
                $traamount=$respond->row(0)->amount;
                $fullnarration=$respond->row(0)->narration.' - ('.$respond->row(0)->date.','.$respond->row(0)->invreceno.')';

                $data = array(
                    'glapply' => '1',
                    'updateuser'=> $userID, 
                    'updatedatetime'=> $updatedatetime
                );
        
                $this->db->where('idtbl_other_payincome', $recordID);
                $this->db->update('tbl_other_payincome', $data);

                $data = array(
                    'tradate'=> $tradate, 
                    'batchno'=> $batchno, 
                    'amount'=> $traamount, 
                    'narration'=> $fullnarration, 
                    'poststatus'=> '0', 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_master_idtbl_master'=> $masterID,
                    'tbl_company_idtbl_company'=> $company,
                    'tbl_company_branch_idtbl_company_branch'=> $branch
                );

                $this->db->insert('tbl_account_transaction_manual_main', $data);

                $journalmainID=$this->db->insert_id();

                //Credit Entry
                $data1 = array(
                    'tradate'=> $tradate, 
                    'batchno'=> $batchno, 
                    'tratype'=> 'J', 
                    'seqno'=> '1', 
                    'crdr'=> 'C', 
                    'amount'=> $traamount, 
                    'narration'=> $fullnarration, 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_account_idtbl_account'=> $creditchartaccount,
                    'tbl_account_detail_idtbl_account_detail'=> $creditdetailaccount,
                    'tbl_master_idtbl_master'=> $masterID,
                    'tbl_company_idtbl_company'=> $company,
                    'tbl_company_branch_idtbl_company_branch'=> $branch,
                    'manualtrans_main_id'=> $journalmainID
                );

                $this->db->insert('tbl_account_transaction_manual', $data1);

                //Debit Entry
                $data2 = array(
                    'tradate'=> $tradate, 
                    'batchno'=> $batchno, 
                    'tratype'=> 'J', 
                    'seqno'=> '2', 
                    'crdr'=> 'D', 
                    'amount'=> $traamount, 
                    'narration'=> $fullnarration, 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_account_idtbl_account'=> $debitchartaccount,
                    'tbl_account_detail_idtbl_account_detail'=> $debitdetailaccount,
                    'tbl_master_idtbl_master'=> $masterID,
                    'tbl_company_idtbl_company'=> $company,
                    'tbl_company_branch_idtbl_company_branch'=> $branch,
                    'manualtrans_main_id'=> $journalmainID
                );

                $this->db->insert('tbl_account_transaction_manual', $data2);

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

                    break;
                }
            }
            else{               
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error, Batch no defind by system';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);
                
                $obj=new stdClass();
                $obj->status=0;
                $obj->action=$actionJSON;

                echo json_encode($obj);

                break;
            }
        }
    }
    public function Chartofaccountaccodetail($detailaccount){
        $this->db->select('tbl_account_idtbl_account');
        $this->db->from('tbl_account_detail');
        $this->db->where('idtbl_account_detail', $detailaccount);
        $this->db->where('status', 1);

        $respondchart=$this->db->get();

        return $respondchart->row(0)->tbl_account_idtbl_account;
    }
    public function Journalentrybatchinsertupdate(){
        $userID=$_SESSION['userid'];
        if(!empty($this->input->post('batchMainTransID'))):$batchMainTransID = $this->input->post('batchMainTransID');endif;
        if(!empty($this->input->post('batchMainTransBatchNo'))):$batchMainTransBatchNo = $this->input->post('batchMainTransBatchNo');endif;
        if(!empty($this->input->post('batchMainTransMaster'))):$batchMainTransMaster = $this->input->post('batchMainTransMaster');endif;

        $glbatchtradate = $this->input->post('glbatchtradate');
        $glbatchcreditdebit = $this->input->post('glbatchcreditdebit');
        $cdtype = $this->input->post('cdtype');
        $glbatchaccountID = $this->input->post('glbatchaccountID');
        $accounttype = $this->input->post('accounttype');
        $glbatchnarration = $this->input->post('glbatchnarration');
        $glbatchamount = str_replace([',', ' '], '', $this->input->post('glbatchamount'));

        $company = $_SESSION['companyid'];
        $branch = $_SESSION['branchid'];
        $today=date('Y-m-d');
        $updatedatetime=date('Y-m-d H:i:s');

        $poststatus = 0;
        $chartaccount=0;
        $detailaccount=0;

        if($accounttype==1){$chartaccount=$glbatchaccountID;}
        else{$detailaccount=$glbatchaccountID;}

        if(empty($batchMainTransID)):
            $prefix=journal_prefix($company, $branch);
            $masterdata=get_account_period($company, $branch);
            $batchno=tr_batch_num($prefix, $branch);
            $masterID=$masterdata->idtbl_master;
        else:
            $batchno=$batchMainTransBatchNo;
            $masterID=$batchMainTransMaster;

            $this->db->select('`poststatus`');
            $this->db->from('tbl_account_transaction_manual_main');
            $this->db->where('idtbl_account_transaction_manual_main', $batchMainTransID);
    
            $respondcheck = $this->db->get();
            $poststatus = $respondcheck->row(0)->poststatus;
        endif;

        if($poststatus==0){
            if(!empty($batchno)){
                $this->db->trans_begin();

                if(empty($batchMainTransID)):
                    $data = array(
                        'tradate'=> $glbatchtradate, 
                        'batchno'=> $batchno, 
                        'amount'=> '0', 
                        'narration'=> $glbatchnarration, 
                        'transactiontype'=> '1', 
                        'poststatus'=> '0', 
                        'completestatus'=> '0', 
                        'status'=> '1', 
                        'insertdatetime'=> $updatedatetime, 
                        'tbl_user_idtbl_user'=> $userID,
                        'tbl_master_idtbl_master'=> $masterID,
                        'tbl_company_idtbl_company'=> $company,
                        'tbl_company_branch_idtbl_company_branch'=> $branch
                    );

                    $this->db->insert('tbl_account_transaction_manual_main', $data);
                    $batchtransmainID=$this->db->insert_id();
                else:
                    $batchtransmainID=$batchMainTransID;
                endif;

                $data1 = array(
                    'tradate'=> $glbatchtradate, 
                    'batchno'=> $batchno, 
                    'tratype'=> 'J', 
                    'seqno'=> '1', 
                    'crdr'=> $cdtype, 
                    'amount'=> $glbatchamount, 
                    'narration'=> $glbatchnarration, 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_account_idtbl_account'=> $chartaccount,
                    'tbl_account_detail_idtbl_account_detail'=> $detailaccount,
                    'tbl_master_idtbl_master'=> $masterID,
                    'tbl_company_idtbl_company'=> $company,
                    'tbl_company_branch_idtbl_company_branch'=> $branch,
                    'manualtrans_main_id'=> $batchtransmainID
                );

                $this->db->insert('tbl_account_transaction_manual', $data1);
                $batchtransID=$this->db->insert_id();

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
                    $obj->batchno=$batchno;
                    $obj->batchtransmainID=$batchtransmainID;
                    $obj->batchtransID=$batchtransID;
                    $obj->masterID=$masterID;
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
                    $obj->batchno='';
                    $obj->batchtransmainID='';
                    $obj->batchtransID='';
                    $obj->masterID='';
                    $obj->action=$actionJSON;

                    echo json_encode($obj);
                }
            }
            else{
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error, Batch no defind by system';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);
                
                $obj=new stdClass();
                $obj->status=0;
                $obj->batchno='';
                $obj->batchtransmainID='';
                $obj->masterID='';
                $obj->action=$actionJSON;

                echo json_encode($obj);
            }
        }
        else{
            $actionObj=new stdClass();
            $actionObj->icon='fas fa-warning';
            $actionObj->title='';
            $actionObj->message='Record Error, Already post in this batch journals.';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='danger';

            $actionJSON=json_encode($actionObj);
            
            $obj=new stdClass();
            $obj->status=0;
            $obj->batchno='';
            $obj->batchtransmainID='';
            $obj->batchtransID='';
            $obj->action=$actionJSON;

            echo json_encode($obj);
        }
    }
    public function Journalentrybatchcomplete(){
        $this->db->trans_begin();

        $recordID = $this->input->post('recordID');
        $netamount = $this->input->post('netamount');
        $userID=$_SESSION['userid'];
        $updatedatetime=date('Y-m-d H:i:s');
        $companyID = $_SESSION['companyid'];
        $branchID = $_SESSION['branchid']; 
        $today = date('Y-m-d');   

        $this->db->select('`completestatus`');
        $this->db->from('tbl_account_transaction_manual_main');
        $this->db->where('idtbl_account_transaction_manual_main', $recordID);

        $respondcheck = $this->db->get();

        if($respondcheck->row(0)->completestatus==0):
            $data = array(
                'amount' => $netamount,
                'completestatus' => '1',
                'updateuser' => $userID,
                'updatedatetime' => $updatedatetime
            );

            $this->db->where('idtbl_account_transaction_manual_main', $recordID);
            $this->db->update('tbl_account_transaction_manual_main', $data);

            $this->db->trans_complete();
            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-save';
                $actionObj->title='';
                $actionObj->message='Record Complete Successfully';
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
        else:
            $actionObj=new stdClass();
            $actionObj->icon='fas fa-warning';
            $actionObj->title='';
            $actionObj->message='Record Error, Already completed this batch.';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='danger';

            $actionJSON=json_encode($actionObj);
            
            $obj=new stdClass();
            $obj->status=0;
            $obj->batchno='';
            $obj->batchtransmainID='';
            $obj->action=$actionJSON;

            echo json_encode($obj);
        endif;
    }
    public function Journalentryinfostatus(){
        $this->db->trans_begin();

        $batchtransinfoID = $this->input->post('batchtransinfoID');
        $batchMainTransID = $this->input->post('batchMainTransID');
        $userID=$_SESSION['userid'];
        $updatedatetime=date('Y-m-d H:i:s');
        $companyID = $_SESSION['companyid'];
        $branchID = $_SESSION['branchid']; 
        $today = date('Y-m-d');   

        $this->db->select('`completestatus`');
        $this->db->from('tbl_account_transaction_manual_main');
        $this->db->where('idtbl_account_transaction_manual_main', $batchMainTransID);

        $respondcheck = $this->db->get();

        if($respondcheck->row(0)->completestatus==0):
            $data = array(
                'status' => '3',
                'updateuser' => $userID,
                'updatedatetime' => $updatedatetime
            );

            $this->db->where('idtbl_account_transaction_manual', $batchtransinfoID);
            $this->db->update('tbl_account_transaction_manual', $data);

            $this->db->trans_complete();
            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-save';
                $actionObj->title='';
                $actionObj->message='Record Remove Successfully';
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
        else:
            $actionObj=new stdClass();
            $actionObj->icon='fas fa-warning';
            $actionObj->title='';
            $actionObj->message='Record Error, Already completed this batch.';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='danger';

            $actionJSON=json_encode($actionObj);
            
            $obj=new stdClass();
            $obj->status=0;
            $obj->batchno='';
            $obj->batchtransmainID='';
            $obj->action=$actionJSON;

            echo json_encode($obj);
        endif;
    }
}