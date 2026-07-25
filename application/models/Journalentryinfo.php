<?php
class Journalentryinfo extends CI_Model{
    // public function Journalentryinsertupdate(){
    //     $userID=$_SESSION['userid'];

    //     $company=$this->input->post('company');
    //     $branch=$this->input->post('branch');
    //     $tradate=$this->input->post('tradate');
    //     $traamount=$this->input->post('traamount');
    //     $accountcrno=$this->input->post('accountcrno');
    //     $narrationcr=$this->input->post('narrationcr');
    //     $accountdrno=$this->input->post('accountdrno');
    //     $narrationdr=$this->input->post('narrationdr');
    //     $accounttypecr=$this->input->post('accounttypecr');
    //     $accounttypedr=$this->input->post('accounttypedr');
    //     $payableentry=$this->input->post('payableentry');

    //     $fullnarration=$narrationcr.' & '.$narrationdr;
        
    //     $recordOption=$this->input->post('recordOption');
    //     if(!empty($this->input->post('recordID'))){$recordID=$this->input->post('recordID');}

    //     $creditchartaccount=0;
    //     $debitchartaccount=0;
    //     $creditdetailaccount=0;
    //     $debitdetailaccount=0;

    //     if($accounttypecr==1){$creditchartaccount=$accountcrno;}
    //     else{$creditdetailaccount=$accountcrno;}

    //     if($accounttypedr==1){$debitchartaccount=$accountdrno;}
    //     else{$debitdetailaccount=$accountdrno;}

    //     if($recordOption==1){
    //         $prefix=journal_prefix($company, $branch);
    //         $masterdata=get_account_period($company, $branch);
    //         $batchno=tr_batch_num($prefix, $branch);
    //         $masterID=$masterdata->idtbl_master;
    //     }


    //     $updatedatetime=date('Y-m-d H:i:s');
    //     $today=date('Y-m-d');

    //     if($recordOption==1){
    //         if(!empty($batchno)){
    //             $this->db->trans_begin();

    //             $data = array(
    //                 'tradate'=> $tradate, 
    //                 'batchno'=> $batchno, 
    //                 'amount'=> $traamount, 
    //                 'narration'=> $fullnarration, 
    //                 'poststatus'=> '0', 
    //                 'status'=> '1', 
    //                 'insertdatetime'=> $updatedatetime, 
    //                 'tbl_user_idtbl_user'=> $userID,
    //                 'tbl_master_idtbl_master'=> $masterID,
    //                 'tbl_company_idtbl_company'=> $company,
    //                 'tbl_company_branch_idtbl_company_branch'=> $branch
    //             );

    //             $this->db->insert('tbl_account_transaction_manual_main', $data);

    //             $journalmainID=$this->db->insert_id();

    //             //Credit Entry
    //             $data1 = array(
    //                 'tradate'=> $tradate, 
    //                 'batchno'=> $batchno, 
    //                 'tratype'=> 'J', 
    //                 'seqno'=> '1', 
    //                 'crdr'=> 'C', 
    //                 'amount'=> $traamount, 
    //                 'narration'=> $narrationcr, 
    //                 'payablestatus'=> $payableentry, 
    //                 'status'=> '1', 
    //                 'insertdatetime'=> $updatedatetime, 
    //                 'tbl_user_idtbl_user'=> $userID,
    //                 'tbl_account_idtbl_account'=> $creditchartaccount,
    //                 'tbl_account_detail_idtbl_account_detail'=> $creditdetailaccount,
    //                 'tbl_master_idtbl_master'=> $masterID,
    //                 'tbl_company_idtbl_company'=> $company,
    //                 'tbl_company_branch_idtbl_company_branch'=> $branch,
    //                 'manualtrans_main_id'=> $journalmainID
    //             );

    //             $this->db->insert('tbl_account_transaction_manual', $data1);

    //             //Debit Entry
    //             $data2 = array(
    //                 'tradate'=> $tradate, 
    //                 'batchno'=> $batchno, 
    //                 'tratype'=> 'J', 
    //                 'seqno'=> '2', 
    //                 'crdr'=> 'D', 
    //                 'amount'=> $traamount, 
    //                 'narration'=> $narrationdr, 
    //                 'status'=> '1', 
    //                 'insertdatetime'=> $updatedatetime, 
    //                 'tbl_user_idtbl_user'=> $userID,
    //                 'tbl_account_idtbl_account'=> $debitchartaccount,
    //                 'tbl_account_detail_idtbl_account_detail'=> $debitdetailaccount,
    //                 'tbl_master_idtbl_master'=> $masterID,
    //                 'tbl_company_idtbl_company'=> $company,
    //                 'tbl_company_branch_idtbl_company_branch'=> $branch,
    //                 'manualtrans_main_id'=> $journalmainID
    //             );

    //             $this->db->insert('tbl_account_transaction_manual', $data2);

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
    //         $this->db->trans_begin();

    //         $this->db->select('batchno, tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch, tbl_master_idtbl_master, poststatus');
    //         $this->db->from('tbl_account_transaction_manual_main');
    //         $this->db->where('idtbl_account_transaction_manual_main', $recordID);
    //         $this->db->where('status', 1);

    //         $respond=$this->db->get();

    //         if($respond->row(0)->poststatus==0){
    //             $this->db->where('manualtrans_main_id', $recordID);
    //             $this->db->delete('tbl_account_transaction_manual');

    //             $data = array(
    //                 'tradate' => $tradate,
    //                 'amount' => $traamount,
    //                 'narration' => $fullnarration,
    //                 'editstatus' => '0',
    //                 'updateuser'=> $userID, 
    //                 'updatedatetime'=> $updatedatetime
    //             );
        
    //             $this->db->where('idtbl_account_transaction_manual_main', $recordID);
    //             $this->db->update('tbl_account_transaction_manual_main', $data);

    //             //Credit Entry
    //             $data1 = array(
    //                 'tradate'=> $tradate, 
    //                 'batchno'=> $respond->row(0)->batchno, 
    //                 'tratype'=> 'J', 
    //                 'seqno'=> '1', 
    //                 'crdr'=> 'C', 
    //                 'amount'=> $traamount, 
    //                 'narration'=> $narrationcr, 
    //                 'payablestatus'=> $payableentry, 
    //                 'status'=> '1', 
    //                 'insertdatetime'=> $updatedatetime, 
    //                 'tbl_user_idtbl_user'=> $userID,
    //                 'tbl_account_idtbl_account'=> $creditchartaccount,
    //                 'tbl_account_detail_idtbl_account_detail'=> $creditdetailaccount,
    //                 'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
    //                 'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
    //                 'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch,
    //                 'manualtrans_main_id'=> $recordID
    //             );

    //             $this->db->insert('tbl_account_transaction_manual', $data1);

    //             //Debit Entry
    //             $data2 = array(
    //                 'tradate'=> $tradate, 
    //                 'batchno'=> $respond->row(0)->batchno, 
    //                 'tratype'=> 'J', 
    //                 'seqno'=> '2', 
    //                 'crdr'=> 'D', 
    //                 'amount'=> $traamount, 
    //                 'narration'=> $narrationdr, 
    //                 'status'=> '1', 
    //                 'insertdatetime'=> $updatedatetime, 
    //                 'tbl_user_idtbl_user'=> $userID,
    //                 'tbl_account_idtbl_account'=> $debitchartaccount,
    //                 'tbl_account_detail_idtbl_account_detail'=> $debitdetailaccount,
    //                 'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
    //                 'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
    //                 'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch,
    //                 'manualtrans_main_id'=> $recordID
    //             );

    //             $this->db->insert('tbl_account_transaction_manual', $data2);

    //             $this->db->trans_complete();
    //             if ($this->db->trans_status() === TRUE) {
    //                 $this->db->trans_commit();
                    
    //                 $actionObj=new stdClass();
    //                 $actionObj->icon='fas fa-save';
    //                 $actionObj->title='';
    //                 $actionObj->message='Record Update Successfully';
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
    //             $this->db->trans_commit();

    //             $actionObj=new stdClass();
    //             $actionObj->icon='fas fa-warning';
    //             $actionObj->title='';
    //             $actionObj->message='Record Error. This record already posted.';
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
    public function Journalentryinsertupdate(){
        try {
            $userID         = $_SESSION['userid'];
            $updatedatetime = date('Y-m-d H:i:s');

            // ── Input ─────────────────────────────────────────────────────────
            $company        = $this->input->post('company');
            $branch         = $this->input->post('branch');
            $tradate        = $this->input->post('tradate');
            $traamount      = $this->input->post('traamount');
            $accountcrno    = $this->input->post('accountcrno');
            $narrationcr    = $this->input->post('narrationcr');
            $accountdrno    = $this->input->post('accountdrno');
            $narrationdr    = $this->input->post('narrationdr');
            $accounttypecr  = $this->input->post('accounttypecr');
            $accounttypedr  = $this->input->post('accounttypedr');
            $payableentry   = $this->input->post('payableentry');
            $recordOption   = $this->input->post('recordOption');
            $recordID       = $this->input->post('recordID');

            $fullnarration  = $narrationcr . ' & ' . $narrationdr;

            // ── Resolve credit/debit account IDs ─────────────────────────────
            $creditchartaccount  = 0;
            $debitchartaccount   = 0;
            $creditdetailaccount = 0;
            $debitdetailaccount  = 0;

            if($accounttypecr == 1) { $creditchartaccount  = $accountcrno; }
            else                    { $creditdetailaccount = $accountcrno; }

            if($accounttypedr == 1) { $debitchartaccount   = $accountdrno; }
            else                    { $debitdetailaccount  = $accountdrno; }

            // ── Validate common inputs ────────────────────────────────────────
            if(empty($company) || empty($branch)){
                throw new Exception('Company and Branch are required');
            }
            if(empty($tradate)){
                throw new Exception('Transaction date is required');
            }
            if(empty($traamount) || $traamount <= 0){
                throw new Exception('Transaction amount is required');
            }

            // ══════════════════════════════════════════════════════════════════
            // RECORD OPTION 1 — INSERT
            // ══════════════════════════════════════════════════════════════════
            if($recordOption == 1){
                // ── Resolve master, batch, receipt no ────────────────────────────────
                $masterdata = get_account_period_acco_date($company, $branch, $tradate);

                if (empty($masterdata) || empty($masterdata->idtbl_master)) {
                    throw new Exception('Record Error, Account period not found for the given date');
                }

                $prefix   = generate_prefix($company, $branch, $tradate, 'JE');
                $batchno   = tr_batch_num($prefix, $branch);

                if(empty($batchno)){
                    throw new Exception('Record Error, Batch no could not be defined by system');
                }

                $masterID = $masterdata->idtbl_master;

                // ── Begin Transaction ─────────────────────────────────────────
                $this->db->trans_begin();

                // Insert main journal header
                $data = array(
                    'tradate'                                 => $tradate,
                    'batchno'                                 => $batchno,
                    'amount'                                  => $traamount,
                    'narration'                               => $fullnarration,
                    'poststatus'                              => '0',
                    'status'                                  => '1',
                    'insertdatetime'                          => $updatedatetime,
                    'tbl_user_idtbl_user'                     => $userID,
                    'tbl_master_idtbl_master'                 => $masterID,
                    'tbl_company_idtbl_company'               => $company,
                    'tbl_company_branch_idtbl_company_branch' => $branch
                );

                $this->db->insert('tbl_account_transaction_manual_main', $data);
                $journalmainID = $this->db->insert_id();

                if(empty($journalmainID)){
                    throw new Exception('Record Error, Failed to insert journal main record');
                }

                // Credit Entry
                $data1 = array(
                    'tradate'                                 => $tradate,
                    'batchno'                                 => $batchno,
                    'tratype'                                 => 'J',
                    'seqno'                                   => '1',
                    'crdr'                                    => 'C',
                    'amount'                                  => $traamount,
                    'narration'                               => $narrationcr,
                    'payablestatus'                           => $payableentry,
                    'status'                                  => '1',
                    'insertdatetime'                          => $updatedatetime,
                    'tbl_user_idtbl_user'                     => $userID,
                    'tbl_account_idtbl_account'               => $creditchartaccount,
                    'tbl_account_detail_idtbl_account_detail' => $creditdetailaccount,
                    'tbl_master_idtbl_master'                 => $masterID,
                    'tbl_company_idtbl_company'               => $company,
                    'tbl_company_branch_idtbl_company_branch' => $branch,
                    'manualtrans_main_id'                     => $journalmainID
                );

                $this->db->insert('tbl_account_transaction_manual', $data1);

                // Debit Entry
                $data2 = array(
                    'tradate'                                 => $tradate,
                    'batchno'                                 => $batchno,
                    'tratype'                                 => 'J',
                    'seqno'                                   => '2',
                    'crdr'                                    => 'D',
                    'amount'                                  => $traamount,
                    'narration'                               => $narrationdr,
                    'status'                                  => '1',
                    'insertdatetime'                          => $updatedatetime,
                    'tbl_user_idtbl_user'                     => $userID,
                    'tbl_account_idtbl_account'               => $debitchartaccount,
                    'tbl_account_detail_idtbl_account_detail' => $debitdetailaccount,
                    'tbl_master_idtbl_master'                 => $masterID,
                    'tbl_company_idtbl_company'               => $company,
                    'tbl_company_branch_idtbl_company_branch' => $branch,
                    'manualtrans_main_id'                     => $journalmainID
                );

                $this->db->insert('tbl_account_transaction_manual', $data2);

                // ── Complete Transaction ──────────────────────────────────────
                $this->db->trans_complete();

                if($this->db->trans_status() === TRUE){
                    $this->db->trans_commit();
                    $this->_jsonResponse(1, 'fas fa-save', 'Record Added Successfully', 'success');
                } else {
                    $this->db->trans_rollback();
                    throw new Exception('Record Error, Transaction failed');
                }

            }
            // ══════════════════════════════════════════════════════════════════
            // RECORD OPTION 2 — UPDATE
            // ══════════════════════════════════════════════════════════════════
            else {

                if(empty($recordID)){
                    throw new Exception('Record ID is required for update');
                }

                // Fetch existing journal main record
                $this->db->select('batchno, tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch, tbl_master_idtbl_master, poststatus');
                $this->db->from('tbl_account_transaction_manual_main');
                $this->db->where('idtbl_account_transaction_manual_main', $recordID);
                $this->db->where('status', 1);

                $respond = $this->db->get();

                if(!$respond || $respond->num_rows() == 0){
                    throw new Exception('Record not found');
                }

                $record = $respond->row(0);

                if($record->poststatus == 1){
                    throw new Exception('Record Error. This record already posted.');
                }

                // ── Begin Transaction ─────────────────────────────────────────
                $this->db->trans_begin();

                // Delete existing journal detail lines — re-insert fresh
                $this->db->where('manualtrans_main_id', $recordID);
                $this->db->delete('tbl_account_transaction_manual');

                // Update journal main header
                $data = array(
                    'tradate'        => $tradate,
                    'amount'         => $traamount,
                    'narration'      => $fullnarration,
                    'editstatus'     => '0',
                    'updateuser'     => $userID,
                    'updatedatetime' => $updatedatetime
                );

                $this->db->where('idtbl_account_transaction_manual_main', $recordID);
                $this->db->update('tbl_account_transaction_manual_main', $data);

                // Credit Entry
                $data1 = array(
                    'tradate'                                 => $tradate,
                    'batchno'                                 => $record->batchno,
                    'tratype'                                 => 'J',
                    'seqno'                                   => '1',
                    'crdr'                                    => 'C',
                    'amount'                                  => $traamount,
                    'narration'                               => $narrationcr,
                    'payablestatus'                           => $payableentry,
                    'status'                                  => '1',
                    'insertdatetime'                          => $updatedatetime,
                    'tbl_user_idtbl_user'                     => $userID,
                    'tbl_account_idtbl_account'               => $creditchartaccount,
                    'tbl_account_detail_idtbl_account_detail' => $creditdetailaccount,
                    'tbl_master_idtbl_master'                 => $record->tbl_master_idtbl_master,
                    'tbl_company_idtbl_company'               => $record->tbl_company_idtbl_company,
                    'tbl_company_branch_idtbl_company_branch' => $record->tbl_company_branch_idtbl_company_branch,
                    'manualtrans_main_id'                     => $recordID
                );

                $this->db->insert('tbl_account_transaction_manual', $data1);

                // Debit Entry
                $data2 = array(
                    'tradate'                                 => $tradate,
                    'batchno'                                 => $record->batchno,
                    'tratype'                                 => 'J',
                    'seqno'                                   => '2',
                    'crdr'                                    => 'D',
                    'amount'                                  => $traamount,
                    'narration'                               => $narrationdr,
                    'status'                                  => '1',
                    'insertdatetime'                          => $updatedatetime,
                    'tbl_user_idtbl_user'                     => $userID,
                    'tbl_account_idtbl_account'               => $debitchartaccount,
                    'tbl_account_detail_idtbl_account_detail' => $debitdetailaccount,
                    'tbl_master_idtbl_master'                 => $record->tbl_master_idtbl_master,
                    'tbl_company_idtbl_company'               => $record->tbl_company_idtbl_company,
                    'tbl_company_branch_idtbl_company_branch' => $record->tbl_company_branch_idtbl_company_branch,
                    'manualtrans_main_id'                     => $recordID
                );

                $this->db->insert('tbl_account_transaction_manual', $data2);

                // ── Complete Transaction ──────────────────────────────────────
                $this->db->trans_complete();

                if($this->db->trans_status() === TRUE){
                    $this->db->trans_commit();
                    $this->_jsonResponse(1, 'fas fa-save', 'Record Update Successfully', 'success');
                } else {
                    $this->db->trans_rollback();
                    throw new Exception('Record Error, Transaction failed');
                }
            }

        } catch(Exception $e){
            if($this->db->trans_enabled){
                $this->db->trans_rollback();
            }
            $this->_jsonResponse(0, 'fas fa-warning', $e->getMessage(), 'danger');
        }
    }
    // public function Journalentrystatus($x, $y){
    //     $this->db->trans_begin();

    //     $userID=$_SESSION['userid'];
    //     $recordID=$x;
    //     $type=$y;
    //     $updatedatetime=date('Y-m-d H:i:s');

    //     if($type==1){
    //         $data = array(
    //             'status' => '1',
    //             'updateuser'=> $userID, 
    //             'updatedatetime'=> $updatedatetime
    //         );

    //         $this->db->where('idtbl_account_transaction_manual_main', $recordID);
    //         $this->db->update('tbl_account_transaction_manual_main', $data);

    //         $datapay = array(
    //             'status' => '1',
    //             'updateuser'=> $userID, 
    //             'updatedatetime'=> $updatedatetime
    //         );

    //         $this->db->where('manualtrans_main_id', $recordID);
    //         $this->db->update('tbl_account_transaction_manual', $datapay);

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
    //             redirect('Journalentry');                
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
    //             redirect('Journalentry');
    //         }
    //     }
    //     else if($type==2){
    //         $data = array(
    //             'status' => '2',
    //             'updateuser'=> $userID, 
    //             'updatedatetime'=> $updatedatetime
    //         );

    //         $this->db->where('idtbl_account_transaction_manual_main', $recordID);
    //         $this->db->update('tbl_account_transaction_manual_main', $data);

    //         $datapay = array(
    //             'status' => '2',
    //             'updateuser'=> $userID, 
    //             'updatedatetime'=> $updatedatetime
    //         );

    //         $this->db->where('manualtrans_main_id', $recordID);
    //         $this->db->update('tbl_account_transaction_manual', $datapay);

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
    //             redirect('Journalentry');                
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
    //             redirect('Journalentry');
    //         }
    //     }
    //     else if($type==3){
    //         $data = array(
    //             'status' => '3',
    //             'updateuser'=> $userID, 
    //             'updatedatetime'=> $updatedatetime
    //         );

    //         $this->db->where('idtbl_account_transaction_manual_main', $recordID);
    //         $this->db->update('tbl_account_transaction_manual_main', $data);

    //         $datapay = array(
    //             'status' => '3',
    //             'updateuser'=> $userID, 
    //             'updatedatetime'=> $updatedatetime
    //         );

    //         $this->db->where('manualtrans_main_id', $recordID);
    //         $this->db->update('tbl_account_transaction_manual', $datapay);

    //         $this->db->trans_complete();

    //         if ($this->db->trans_status() === TRUE) {
    //             $this->db->trans_commit();
                
    //             $actionObj=new stdClass();
    //             $actionObj->icon='fas fa-trash-alt';
    //             $actionObj->title='';
    //             $actionObj->message='Record Remove Successfully';
    //             $actionObj->url='';
    //             $actionObj->target='_blank';
    //             $actionObj->type='danger';

    //             $actionJSON=json_encode($actionObj);
                
    //             $this->session->set_flashdata('msg', $actionJSON);
    //             redirect('Journalentry');                
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
    //             redirect('Journalentry');
    //         }
    //     }
    // }
    public function Journalentrystatus($x, $y){
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
    
            // Update main journal header status
            $data = array(
                'status'         => $config['status'],
                'updateuser'     => $userID,
                'updatedatetime' => $updatedatetime
            );
    
            $this->db->where('idtbl_account_transaction_manual_main', $recordID);
            $this->db->update('tbl_account_transaction_manual_main', $data);
    
            // Update journal detail lines status
            $datapay = array(
                'status'         => $config['status'],
                'updateuser'     => $userID,
                'updatedatetime' => $updatedatetime
            );
    
            $this->db->where('manualtrans_main_id', $recordID);
            $this->db->update('tbl_account_transaction_manual', $datapay);
    
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
                redirect('Journalentry');
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
            redirect('Journalentry');
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
            
            $this->db->select('tbl_account_transaction_manual.narration, tbl_account_transaction_manual.crdr, tbl_account_detail.idtbl_account_detail, tbl_account_detail.accountno, tbl_account_detail.accountname, tbl_account.idtbl_account, tbl_account.accountno AS chartaccountno, tbl_account.accountname AS chartaccountname, tbl_account_transaction_manual.payablestatus');
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
                    $obj->payablestatus=$rowdetail->payablestatus;
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
                            <th class="text-right">Credit</th>
                            <th class="text-right">Debit</th>
                        </tr>
                    </thead>
                    <tbody>';
                    $credittotal=0;
                    $debittotal=0;
                    foreach($respondpayinfo->result() as $rowdatainfo){
                        $credittotal += ($rowdatainfo->crdr == 'C' ? $rowdatainfo->amount : 0);
                        $debittotal += ($rowdatainfo->crdr == 'D' ? $rowdatainfo->amount : 0);

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
                            <td class="text-right">'.($rowdatainfo->crdr == 'C' ? number_format($rowdatainfo->amount, 2) : '').'</td>
                            <td class="text-right">'.($rowdatainfo->crdr == 'D' ? number_format($rowdatainfo->amount, 2) : '').'</td>
                        </tr>
                        ';
                    }
                    $html.='</tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-right">Total</th>
                            <th class="text-right">'.number_format($credittotal, 2).'</th>
                            <th class="text-right">'.number_format($debittotal, 2).'</th>
                        </tr>
                    </tfoot>
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
    // public function Journalentryposting(){
    //     $recordID=$this->input->post('recordID');
    //     $updatedatetime=date('Y-m-d H:i:s');
    //     $today=date('Y-m-d');
    //     $userID=$_SESSION['userid'];

    //     $i=0;

    //     $this->db->select('tradate, poststatus, status, editstatus, postviewtime, completestatus, updatedatetime, tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch');
    //     $this->db->from('tbl_account_transaction_manual_main');
    //     $this->db->where('idtbl_account_transaction_manual_main', $recordID);
    //     $this->db->where('status', 1);

    //     $respond=$this->db->get();

    //     $this->db->select('*');
    //     $this->db->from('tbl_account_transaction_manual');
    //     $this->db->where('manualtrans_main_id', $recordID);
    //     $this->db->where('status', 1);

    //     $responddetail=$this->db->get();

    //     if($respond->row(0)->poststatus==0 && $respond->row(0)->status==1 && $respond->row(0)->editstatus==0 && $respond->row(0)->completestatus==1){
    //         if($respond->row(0)->postviewtime>$respond->row(0)->updatedatetime){
    //             $this->db->trans_start();
                
    //             $data = array(
    //                 'poststatus'=> '1',
    //                 'postuser'=> $userID,
    //                 'postviewtime'=> NULL
    //             );
        
    //             $this->db->where('idtbl_account_transaction_manual_main', $recordID);
    //             $this->db->update('tbl_account_transaction_manual_main', $data);

    //             $data = array(
    //                 'poststatus'=> '1',
    //                 'postuser'=> $userID,
    //                 'postviewtime'=> NULL
    //             );
        
    //             $this->db->where('manualtrans_main_id', $recordID);
    //             $this->db->update('tbl_account_transaction_manual', $data);

    //             $i=1;
                
    //             $prefix  = generate_prefix($respond->row(0)->tbl_company_idtbl_company, $respond->row(0)->tbl_company_branch_idtbl_company_branch, $respond->row(0)->tradate, 'AT');
    //             $batchno=tr_batch_num($prefix, $respond->row(0)->tbl_company_branch_idtbl_company_branch);

    //             foreach($responddetail->result() as $rowdatalist){
    //                 if($rowdatalist->tbl_account_detail_idtbl_account_detail>0){
    //                     $this->load->model('Journalentryinfo');
    //                     $chartofaccount=$this->Journalentryinfo->Chartofaccountaccodetail($rowdatalist->tbl_account_detail_idtbl_account_detail);
    //                 }
    //                 else{
    //                     $chartofaccount=$rowdatalist->tbl_account_idtbl_account;
    //                 }

    //                 $datacredit = array(
    //                     'tradate'=> $rowdatalist->tradate, 
    //                     'batchno'=> $batchno, 
    //                     'trabatchotherno'=> $rowdatalist->batchno, 
    //                     'tratype'=> 'J', 
    //                     'seqno'=> $i, 
    //                     'crdr'=> $rowdatalist->crdr, 
    //                     'accamount'=> $rowdatalist->amount, 
    //                     'narration'=> $rowdatalist->narration, 
    //                     'totamount'=> $rowdatalist->amount, 
    //                     'status'=> '1', 
    //                     'insertdatetime'=> $updatedatetime, 
    //                     'tbl_user_idtbl_user'=> $userID,
    //                     'tbl_account_idtbl_account'=> $chartofaccount,
    //                     'tbl_master_idtbl_master'=> $rowdatalist->tbl_master_idtbl_master,
    //                     'tbl_company_idtbl_company'=> $rowdatalist->tbl_company_idtbl_company,
    //                     'tbl_company_branch_idtbl_company_branch'=> $rowdatalist->tbl_company_branch_idtbl_company_branch
    //                 );
    //                 $this->db->insert('tbl_account_transaction', $datacredit);
            
    //                 $datacreditfull = array(
    //                     'tradate'=> $rowdatalist->tradate, 
    //                     'batchno'=> $batchno, 
    //                     'tratype'=> 'J', 
    //                     'crdr'=> $rowdatalist->crdr, 
    //                     'accamount'=> $rowdatalist->amount, 
    //                     'narration'=> $rowdatalist->narration, 
    //                     'totamount'=> $rowdatalist->amount, 
    //                     'status'=> '1', 
    //                     'insertdatetime'=> $updatedatetime, 
    //                     'tbl_user_idtbl_user'=> $userID,
    //                     'tbl_account_idtbl_account'=> $chartofaccount,
    //                     'tbl_master_idtbl_master'=> $rowdatalist->tbl_master_idtbl_master,
    //                     'tbl_company_idtbl_company'=> $rowdatalist->tbl_company_idtbl_company,
    //                     'tbl_company_branch_idtbl_company_branch'=> $rowdatalist->tbl_company_branch_idtbl_company_branch
    //                 );
    //                 $this->db->insert('tbl_account_transaction_full', $datacreditfull);

    //                 //Pety cash float increase via jurnal entry
    //                 $this->db->select('specialcate');
    //                 $this->db->from('tbl_account');
    //                 $this->db->where('idtbl_account', $chartofaccount);
    //                 $this->db->where('status', 1);

    //                 $respondspecat=$this->db->get();

    //                 if($rowdatalist->crdr=='D' && $respondspecat->row(0)->specialcate==36){
    //                     $this->db->select('count(*) AS countdata');
    //                     $this->db->from('tbl_pettycash_summary');
    //                     $this->db->where('tbl_account_idtbl_account', $rowdatalist->tbl_account_idtbl_account);
    //                     $this->db->where('status', 1);

    //                     $respondpettycashsummery=$this->db->get();

    //                     if($respondpettycashsummery->row(0)->countdata==0){
    //                         //Petty Cash Summery
    //                         $datapettysummery = array(
    //                             'date'=> $today, 
    //                             'openbal'=> '0', 
    //                             'postbal'=> '0', 
    //                             'reimbal'=> $rowdatalist->amount, 
    //                             'closebal'=> $rowdatalist->amount, 
    //                             'status'=> 1, 
    //                             'insertdatetime'=> $updatedatetime,
    //                             'tbl_user_idtbl_user'=> $userID, 
    //                             'tbl_account_idtbl_account'=> $rowdatalist->tbl_account_idtbl_account, 
    //                             'tbl_company_idtbl_company'=> $rowdatalist->tbl_company_idtbl_company, 
    //                             'tbl_company_branch_idtbl_company_branch'=> $rowdatalist->tbl_company_branch_idtbl_company_branch, 
    //                             'tbl_master_idtbl_master'=> $rowdatalist->tbl_master_idtbl_master
    //                         );
    //                         $this->db->insert('tbl_pettycash_summary', $datapettysummery);
    //                     }
    //                     else{
    //                         $this->db->select('`closebal`, `tbl_account_idtbl_account`');
    //                         $this->db->from('tbl_pettycash_summary');
    //                         $this->db->where('tbl_company_idtbl_company', $rowdatalist->tbl_company_idtbl_company);
    //                         $this->db->where('tbl_company_branch_idtbl_company_branch', $rowdatalist->tbl_company_branch_idtbl_company_branch);
    //                         $this->db->where('status', 1);
    //                         $this->db->order_by('idtbl_pettycash_summary', 'DESC');
    //                         $this->db->limit(1);

    //                         $respond=$this->db->get();

    //                         $newclosebalance=$respond->row(0)->closebal+$rowdatalist->amount;

    //                         //Petty Cash Summery
    //                         $datapettysummery = array(
    //                             'date'=> $today, 
    //                             'openbal'=> $respond->row(0)->closebal, 
    //                             'postbal'=> '0', 
    //                             'reimbal'=> $rowdatalist->amount, 
    //                             'closebal'=> $newclosebalance, 
    //                             'status'=> 1, 
    //                             'insertdatetime'=> $updatedatetime,
    //                             'tbl_user_idtbl_user'=> $userID, 
    //                             'tbl_account_idtbl_account'=> $rowdatalist->tbl_account_idtbl_account, 
    //                             'tbl_company_idtbl_company'=> $rowdatalist->tbl_company_idtbl_company, 
    //                             'tbl_company_branch_idtbl_company_branch'=> $rowdatalist->tbl_company_branch_idtbl_company_branch, 
    //                             'tbl_master_idtbl_master'=> $rowdatalist->tbl_master_idtbl_master
    //                         );  
    //                         $this->db->insert('tbl_pettycash_summary', $datapettysummery);
    //                     }
    //                 }

    //                 $i++;
    //             }

    //             $this->db->trans_complete();

    //             if ($this->db->trans_status() === TRUE) {
    //                 $this->db->trans_commit();
                    
    //                 $actionObj=new stdClass();
    //                 $actionObj->icon='fas fa-save';
    //                 $actionObj->title='';
    //                 $actionObj->message='Record Post Successfully';
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
    //             $actionObj=new stdClass();
    //             $actionObj->icon='fas fa-warning';
    //             $actionObj->title='';
    //             $actionObj->message='Record Error, Please check this record for information. Because this record was edited before you posted.';
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
    //     else if($respond->row(0)->status==2){
    //         $actionObj=new stdClass();
    //         $actionObj->icon='fas fa-warning';
    //         $actionObj->title='';
    //         $actionObj->message='Record Error, Record Deactivated. Kindly review the status of the record.';
    //         $actionObj->url='';
    //         $actionObj->target='_blank';
    //         $actionObj->type='warning';

    //         $actionJSON=json_encode($actionObj);
            
    //         $obj=new stdClass();
    //         $obj->status=0;
    //         $obj->action=$actionJSON;

    //         echo json_encode($obj);
    //     }
    //     else if($respond->row(0)->editstatus==1){
    //         $actionObj=new stdClass();
    //         $actionObj->icon='fas fa-warning';
    //         $actionObj->title='';
    //         $actionObj->message='Record Error, Record in editable mode. You cannot change anything about the record.';
    //         $actionObj->url='';
    //         $actionObj->target='_blank';
    //         $actionObj->type='danger';

    //         $actionJSON=json_encode($actionObj);
            
    //         $obj=new stdClass();
    //         $obj->status=0;
    //         $obj->action=$actionJSON;

    //         echo json_encode($obj);
    //     }
    //     else if($respond->row(0)->completestatus==0){
    //         $actionObj=new stdClass();
    //         $actionObj->icon='fas fa-warning';
    //         $actionObj->title='';
    //         $actionObj->message='This journal batch transaction not complete yet. Firstly complete this batch transaction.';
    //         $actionObj->url='';
    //         $actionObj->target='_blank';
    //         $actionObj->type='danger';

    //         $actionJSON=json_encode($actionObj);
            
    //         $obj=new stdClass();
    //         $obj->status=0;
    //         $obj->action=$actionJSON;

    //         echo json_encode($obj);
    //     }
    //     else if($respond->row(0)->poststatus==1){
    //         $actionObj=new stdClass();
    //         $actionObj->icon='fas fa-warning';
    //         $actionObj->title='';
    //         $actionObj->message='Record Error, Record already posted.';
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
    public function Journalentryposting(){
        try {
            $recordID       = $this->input->post('recordID');
            $updatedatetime = date('Y-m-d H:i:s');
            $today          = date('Y-m-d');
            $userID         = $_SESSION['userid'];
            $i              = 0;
    
            if(empty($recordID)){
                throw new Exception('Record ID is required');
            }
    
            // ── Fetch main journal record ─────────────────────────────────────
            $this->db->select('tradate, poststatus, status, editstatus, postviewtime, completestatus, updatedatetime, tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch');
            $this->db->from('tbl_account_transaction_manual_main');
            $this->db->where('idtbl_account_transaction_manual_main', $recordID);
            $this->db->where('status', 1);
    
            $respond = $this->db->get();
    
            if(!$respond || $respond->num_rows() == 0){
                throw new Exception('Record not found');
            }
    
            $record = $respond->row(0);
    
            // ── Status validation checks ──────────────────────────────────────
            if($record->status == 2){
                throw new Exception('Record Error, Record Deactivated. Kindly review the status of the record.');
            }
    
            if($record->editstatus == 1){
                throw new Exception('Record Error, Record in editable mode. You cannot change anything about the record.');
            }
    
            if($record->completestatus == 0){
                throw new Exception('This journal batch transaction not complete yet. Firstly complete this batch transaction.');
            }
    
            if($record->poststatus == 1){
                throw new Exception('Record Error, Record already posted.');
            }
    
            if(!($record->poststatus == 0 && $record->status == 1 && $record->editstatus == 0 && $record->completestatus == 1)){
                throw new Exception('Record Error, Invalid record state for posting.');
            }
    
            if($record->postviewtime <= $record->updatedatetime){
                throw new Exception('Record Error, Please check this record for information. Because this record was edited before you posted.');
            }
    
            // ── Fetch journal detail lines ────────────────────────────────────
            $this->db->select('*');
            $this->db->from('tbl_account_transaction_manual');
            $this->db->where('manualtrans_main_id', $recordID);
            $this->db->where('status', 1);
    
            $responddetail = $this->db->get();
    
            if(!$responddetail || $responddetail->num_rows() == 0){
                throw new Exception('No journal entry lines found for this record');
            }
    
            // ── Generate batch number ─────────────────────────────────────────
            $prefix  = generate_prefix($record->tbl_company_idtbl_company, $record->tbl_company_branch_idtbl_company_branch, $record->tradate, 'AT');
            $batchno = tr_batch_num($prefix, $record->tbl_company_branch_idtbl_company_branch);
    
            if(empty($batchno)){
                throw new Exception('Record Error, Batch no could not be defined by system');
            }
    
            // ── Begin Transaction ─────────────────────────────────────────────
            $this->db->trans_begin();
    
            // Update main journal post status
            $data = array(
                'poststatus'  => '1',
                'postuser'    => $userID,
                'postviewtime'=> NULL
            );
    
            $this->db->where('idtbl_account_transaction_manual_main', $recordID);
            $this->db->update('tbl_account_transaction_manual_main', $data);
    
            // Update detail lines post status
            $data = array(
                'poststatus'  => '1',
                'postuser'    => $userID,
                'postviewtime'=> NULL
            );
    
            $this->db->where('manualtrans_main_id', $recordID);
            $this->db->update('tbl_account_transaction_manual', $data);
    
            $i = 1;
    
            // ── Process each journal entry line ───────────────────────────────
            foreach($responddetail->result() as $rowdatalist){
    
                // Resolve chart of account
                if($rowdatalist->tbl_account_detail_idtbl_account_detail > 0){
                    $this->load->model('Journalentryinfo');
                    $chartofaccount = $this->Journalentryinfo->Chartofaccountaccodetail(
                        $rowdatalist->tbl_account_detail_idtbl_account_detail
                    );
                } else {
                    $chartofaccount = $rowdatalist->tbl_account_idtbl_account;
                }
    
                // Insert into tbl_account_transaction
                $datacredit = array(
                    'tradate'                                 => $rowdatalist->tradate,
                    'batchno'                                 => $batchno,
                    'trabatchotherno'                         => $rowdatalist->batchno,
                    'tratype'                                 => 'J',
                    'seqno'                                   => $i,
                    'crdr'                                    => $rowdatalist->crdr,
                    'accamount'                               => $rowdatalist->amount,
                    'narration'                               => $rowdatalist->narration,
                    'totamount'                               => $rowdatalist->amount,
                    'status'                                  => '1',
                    'insertdatetime'                          => $updatedatetime,
                    'tbl_user_idtbl_user'                     => $userID,
                    'tbl_account_idtbl_account'               => $chartofaccount,
                    'tbl_master_idtbl_master'                 => $rowdatalist->tbl_master_idtbl_master,
                    'tbl_company_idtbl_company'               => $rowdatalist->tbl_company_idtbl_company,
                    'tbl_company_branch_idtbl_company_branch' => $rowdatalist->tbl_company_branch_idtbl_company_branch
                );
    
                $this->db->insert('tbl_account_transaction', $datacredit);
    
                // Insert into tbl_account_transaction_full
                $datacreditfull = array(
                    'tradate'                                 => $rowdatalist->tradate,
                    'batchno'                                 => $batchno,
                    'tratype'                                 => 'J',
                    'crdr'                                    => $rowdatalist->crdr,
                    'accamount'                               => $rowdatalist->amount,
                    'narration'                               => $rowdatalist->narration,
                    'totamount'                               => $rowdatalist->amount,
                    'status'                                  => '1',
                    'insertdatetime'                          => $updatedatetime,
                    'tbl_user_idtbl_user'                     => $userID,
                    'tbl_account_idtbl_account'               => $chartofaccount,
                    'tbl_master_idtbl_master'                 => $rowdatalist->tbl_master_idtbl_master,
                    'tbl_company_idtbl_company'               => $rowdatalist->tbl_company_idtbl_company,
                    'tbl_company_branch_idtbl_company_branch' => $rowdatalist->tbl_company_branch_idtbl_company_branch
                );
    
                $this->db->insert('tbl_account_transaction_full', $datacreditfull);
    
                // ── Petty Cash float increase via journal entry ───────────────
                // specialcate = 36 → Petty Cash account
                $this->db->select('specialcate');
                $this->db->from('tbl_account');
                $this->db->where('idtbl_account', $chartofaccount);
                $this->db->where('status', 1);
    
                $respondspecat = $this->db->get();
    
                if($respondspecat && $respondspecat->num_rows() > 0
                    && $rowdatalist->crdr == 'D'
                    && $respondspecat->row(0)->specialcate == 36
                ){
                    // Check existing petty cash summary
                    $this->db->select('count(*) AS countdata');
                    $this->db->from('tbl_pettycash_summary');
                    $this->db->where('tbl_account_idtbl_account', $rowdatalist->tbl_account_idtbl_account);
                    $this->db->where('status', 1);
    
                    $respondpettycashsummery = $this->db->get();
    
                    if($respondpettycashsummery->row(0)->countdata == 0){
                        // First petty cash summary record
                        $datapettysummery = array(
                            'date'                                    => $today,
                            'openbal'                                 => '0',
                            'postbal'                                 => '0',
                            'reimbal'                                 => $rowdatalist->amount,
                            'closebal'                                => $rowdatalist->amount,
                            'status'                                  => 1,
                            'insertdatetime'                          => $updatedatetime,
                            'tbl_user_idtbl_user'                     => $userID,
                            'tbl_account_idtbl_account'               => $rowdatalist->tbl_account_idtbl_account,
                            'tbl_company_idtbl_company'               => $rowdatalist->tbl_company_idtbl_company,
                            'tbl_company_branch_idtbl_company_branch' => $rowdatalist->tbl_company_branch_idtbl_company_branch,
                            'tbl_master_idtbl_master'                 => $rowdatalist->tbl_master_idtbl_master
                        );
    
                        $this->db->insert('tbl_pettycash_summary', $datapettysummery);
                    } else {
                        // Get latest petty cash closing balance
                        $this->db->select('closebal, tbl_account_idtbl_account');
                        $this->db->from('tbl_pettycash_summary');
                        $this->db->where('tbl_company_idtbl_company', $rowdatalist->tbl_company_idtbl_company);
                        $this->db->where('tbl_company_branch_idtbl_company_branch', $rowdatalist->tbl_company_branch_idtbl_company_branch);
                        $this->db->where('status', 1);
                        $this->db->order_by('idtbl_pettycash_summary', 'DESC');
                        $this->db->limit(1);
    
                        $respondpettycash = $this->db->get();
    
                        $newclosebalance = $respondpettycash->row(0)->closebal + $rowdatalist->amount;
    
                        $datapettysummery = array(
                            'date'                                    => $today,
                            'openbal'                                 => $respondpettycash->row(0)->closebal,
                            'postbal'                                 => '0',
                            'reimbal'                                 => $rowdatalist->amount,
                            'closebal'                                => $newclosebalance,
                            'status'                                  => 1,
                            'insertdatetime'                          => $updatedatetime,
                            'tbl_user_idtbl_user'                     => $userID,
                            'tbl_account_idtbl_account'               => $rowdatalist->tbl_account_idtbl_account,
                            'tbl_company_idtbl_company'               => $rowdatalist->tbl_company_idtbl_company,
                            'tbl_company_branch_idtbl_company_branch' => $rowdatalist->tbl_company_branch_idtbl_company_branch,
                            'tbl_master_idtbl_master'                 => $rowdatalist->tbl_master_idtbl_master
                        );
    
                        $this->db->insert('tbl_pettycash_summary', $datapettysummery);
                    }
                }
    
                $i++;
            }
    
            // ── Complete Transaction ──────────────────────────────────────────
            $this->db->trans_complete();
    
            if($this->db->trans_status() === TRUE){
                $this->db->trans_commit();
                $this->_jsonResponse(1, 'fas fa-save', 'Record Post Successfully', 'success');
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
    public function Getglpassdatalist(){
        $updatedatetime=date('Y-m-d H:i:s');

        $configdata = getconfigdata('journal_passgl');
        $rows = $configdata->result();

        $this->db->select('tbl_other_payincome.*', FALSE);
        $this->db->from('tbl_other_payincome');

        $joinedTables = []; 
        $selectFields = [];

        for ($i = 0; $i < count($rows); $i += 2) {
            $matchRow   = $rows[$i];       
            $displayRow = $rows[$i + 1];   

            $tablename  = $matchRow->tbl_name;   
            $idCol      = $matchRow->col_name;   
            $displayCol = $displayRow->col_name; 

            $fkCol = str_replace('tbl_', '', $tablename); 

            if (!in_array($tablename, $joinedTables)) {
                $this->db->join(
                    $tablename,
                    "$tablename.$idCol = tbl_other_payincome.$fkCol",
                    'left'
                );
                $joinedTables[] = $tablename;
            }

            $selectFields[] = "$tablename.$displayCol";
        }

        $this->db->select(implode(', ', $selectFields), FALSE);

        $this->db->where('tbl_other_payincome.status', 1);
        $this->db->where('tbl_other_payincome.glapply', 0);

        $respond = $this->db->get();

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
    // public function Passtoglentry(){
    //     $this->db->trans_begin();
    //     $updatedatetime=date('Y-m-d H:i:s');
    //     $today=date('Y-m-d');
    //     $userID=$_SESSION['userid'];

    //     $creditchartaccount=0;
    //     $debitchartaccount=0;
    //     $creditdetailaccount=0;
    //     $debitdetailaccount=0;

    //     $gltradate=$this->input->post('gltradate');
    //     $glaccountcrno=$this->input->post('glaccountcrno');
    //     $glaccountdrno=$this->input->post('glaccountdrno');
    //     $company=$this->input->post('company');
    //     $branch=$this->input->post('branch');
    //     $glaccounttypecr=$this->input->post('glaccounttypecr');
    //     $glaccounttypedr=$this->input->post('glaccounttypedr');
    //     $datalist=json_decode($this->input->post('tabledata'));

    //     if($glaccounttypecr==1){$creditchartaccount=$glaccountcrno;}
    //     else{$creditdetailaccount=$glaccountcrno;}

    //     if($glaccounttypedr==1){$debitchartaccount=$glaccountdrno;}
    //     else{$debitdetailaccount=$glaccountdrno;}

    //     foreach($datalist as $rowdatalist){
    //         $recordID=$rowdatalist->recordid;

    //         $this->db->select('`date`, `invreceno`, `amount`, `narration`');
    //         $this->db->from('tbl_other_payincome');
    //         $this->db->where('idtbl_other_payincome', $recordID);
    //         $this->db->where('status', 1);

    //         $respond=$this->db->get();

    //         $masterdata = get_account_period_acco_date($company, $branch, $gltradate);
    //         $prefix   = generate_prefix($company, $branch, $gltradate, 'JE');
    //         $batchno=tr_batch_num($prefix, $branch);
    //         $masterID=$masterdata->idtbl_master;

    //         if(!empty($batchno)){
    //             $this->db->trans_begin();

    //             $tradate=$gltradate;
    //             $traamount=$respond->row(0)->amount;
    //             $fullnarration=$respond->row(0)->narration.' - ('.$respond->row(0)->date.','.$respond->row(0)->invreceno.')';

    //             $data = array(
    //                 'glapply' => '1',
    //                 'updateuser'=> $userID, 
    //                 'updatedatetime'=> $updatedatetime
    //             );
        
    //             $this->db->where('idtbl_other_payincome', $recordID);
    //             $this->db->update('tbl_other_payincome', $data);

    //             $data = array(
    //                 'tradate'=> $tradate, 
    //                 'batchno'=> $batchno, 
    //                 'amount'=> $traamount, 
    //                 'narration'=> $fullnarration, 
    //                 'poststatus'=> '0', 
    //                 'status'=> '1', 
    //                 'insertdatetime'=> $updatedatetime, 
    //                 'tbl_user_idtbl_user'=> $userID,
    //                 'tbl_master_idtbl_master'=> $masterID,
    //                 'tbl_company_idtbl_company'=> $company,
    //                 'tbl_company_branch_idtbl_company_branch'=> $branch
    //             );

    //             $this->db->insert('tbl_account_transaction_manual_main', $data);

    //             $journalmainID=$this->db->insert_id();

    //             //Credit Entry
    //             $data1 = array(
    //                 'tradate'=> $tradate, 
    //                 'batchno'=> $batchno, 
    //                 'tratype'=> 'J', 
    //                 'seqno'=> '1', 
    //                 'crdr'=> 'C', 
    //                 'amount'=> $traamount, 
    //                 'narration'=> $fullnarration, 
    //                 'status'=> '1', 
    //                 'insertdatetime'=> $updatedatetime, 
    //                 'tbl_user_idtbl_user'=> $userID,
    //                 'tbl_account_idtbl_account'=> $creditchartaccount,
    //                 'tbl_account_detail_idtbl_account_detail'=> $creditdetailaccount,
    //                 'tbl_master_idtbl_master'=> $masterID,
    //                 'tbl_company_idtbl_company'=> $company,
    //                 'tbl_company_branch_idtbl_company_branch'=> $branch,
    //                 'manualtrans_main_id'=> $journalmainID
    //             );

    //             $this->db->insert('tbl_account_transaction_manual', $data1);

    //             //Debit Entry
    //             $data2 = array(
    //                 'tradate'=> $tradate, 
    //                 'batchno'=> $batchno, 
    //                 'tratype'=> 'J', 
    //                 'seqno'=> '2', 
    //                 'crdr'=> 'D', 
    //                 'amount'=> $traamount, 
    //                 'narration'=> $fullnarration, 
    //                 'status'=> '1', 
    //                 'insertdatetime'=> $updatedatetime, 
    //                 'tbl_user_idtbl_user'=> $userID,
    //                 'tbl_account_idtbl_account'=> $debitchartaccount,
    //                 'tbl_account_detail_idtbl_account_detail'=> $debitdetailaccount,
    //                 'tbl_master_idtbl_master'=> $masterID,
    //                 'tbl_company_idtbl_company'=> $company,
    //                 'tbl_company_branch_idtbl_company_branch'=> $branch,
    //                 'manualtrans_main_id'=> $journalmainID
    //             );

    //             $this->db->insert('tbl_account_transaction_manual', $data2);

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

    //                 break;
    //             }
    //         }
    //         else{               
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

    //             break;
    //         }
    //     }
    // }
    public function Passtoglentry(){
        try {
            $updatedatetime = date('Y-m-d H:i:s');
            $userID         = $_SESSION['userid'];
    
            // ── Input ─────────────────────────────────────────────────────────
            $gltradate      = $this->input->post('gltradate');
            $glaccountcrno  = $this->input->post('glaccountcrno');
            $glaccountdrno  = $this->input->post('glaccountdrno');
            $company        = $this->input->post('company');
            $branch         = $this->input->post('branch');
            $glaccounttypecr= $this->input->post('glaccounttypecr');
            $glaccounttypedr= $this->input->post('glaccounttypedr');
            $datalist       = json_decode($this->input->post('tabledata'));
    
            // ── Validate inputs ───────────────────────────────────────────────
            if(empty($gltradate)){
                throw new Exception('Transaction date is required');
            }
            if(empty($company) || empty($branch)){
                throw new Exception('Company and Branch are required');
            }
            if(empty($datalist) || !is_array($datalist)){
                throw new Exception('No records selected for GL entry');
            }
    
            // ── Resolve credit/debit account IDs ─────────────────────────────
            $creditchartaccount  = 0;
            $debitchartaccount   = 0;
            $creditdetailaccount = 0;
            $debitdetailaccount  = 0;
    
            if($glaccounttypecr == 1) { $creditchartaccount  = $glaccountcrno; }
            else                      { $creditdetailaccount = $glaccountcrno; }
    
            if($glaccounttypedr == 1) { $debitchartaccount   = $glaccountdrno; }
            else                      { $debitdetailaccount  = $glaccountdrno; }
    
            // ── Resolve period master — once outside loop (same date for all) ─
            $masterdata = get_account_period_acco_date($company, $branch, $gltradate);
    
            if(empty($masterdata) || empty($masterdata->idtbl_master)){
                throw new Exception('Record Error, Active account period not found for selected date');
            }
    
            $prefix   = generate_prefix($company, $branch, $gltradate, 'JE');
            $masterID = $masterdata->idtbl_master;
    
            // ── Begin single transaction for ALL records ──────────────────────
            $this->db->trans_begin();
    
            foreach($datalist as $rowdatalist){
                $recordID = $rowdatalist->recordid;
    
                if(empty($recordID)){
                    throw new Exception('Record ID is missing in selected data');
                }
    
                // Fetch other pay/income record
                $this->db->select('`date`, `invreceno`, `amount`, `narration`');
                $this->db->from('tbl_other_payincome');
                $this->db->where('idtbl_other_payincome', $recordID);
                $this->db->where('status', 1);
    
                $respond = $this->db->get();
    
                if(!$respond || $respond->num_rows() == 0){
                    throw new Exception('Record not found for ID: ' . $recordID);
                }
    
                $record = $respond->row(0);
    
                // Generate batchno per record
                $batchno = tr_batch_num($prefix, $branch);
    
                if(empty($batchno)){
                    throw new Exception('Record Error, Batch no could not be defined by system');
                }
    
                $tradate      = $gltradate;
                $traamount    = $record->amount;
                $fullnarration= $record->narration . ' - (' . $record->date . ',' . $record->invreceno . ')';
    
                // Mark source record as GL applied
                $this->db->where('idtbl_other_payincome', $recordID);
                $this->db->update('tbl_other_payincome', [
                    'glapply'        => '1',
                    'updateuser'     => $userID,
                    'updatedatetime' => $updatedatetime
                ]);
    
                // Insert journal main header
                $data = array(
                    'tradate'                                 => $tradate,
                    'batchno'                                 => $batchno,
                    'amount'                                  => $traamount,
                    'narration'                               => $fullnarration,
                    'poststatus'                              => '0',
                    'status'                                  => '1',
                    'insertdatetime'                          => $updatedatetime,
                    'tbl_user_idtbl_user'                     => $userID,
                    'tbl_master_idtbl_master'                 => $masterID,
                    'tbl_company_idtbl_company'               => $company,
                    'tbl_company_branch_idtbl_company_branch' => $branch
                );
    
                $this->db->insert('tbl_account_transaction_manual_main', $data);
                $journalmainID = $this->db->insert_id();
    
                if(empty($journalmainID)){
                    throw new Exception('Record Error, Failed to insert journal main record for ID: ' . $recordID);
                }
    
                // Credit Entry
                $data1 = array(
                    'tradate'                                 => $tradate,
                    'batchno'                                 => $batchno,
                    'tratype'                                 => 'J',
                    'seqno'                                   => '1',
                    'crdr'                                    => 'C',
                    'amount'                                  => $traamount,
                    'narration'                               => $fullnarration,
                    'status'                                  => '1',
                    'insertdatetime'                          => $updatedatetime,
                    'tbl_user_idtbl_user'                     => $userID,
                    'tbl_account_idtbl_account'               => $creditchartaccount,
                    'tbl_account_detail_idtbl_account_detail' => $creditdetailaccount,
                    'tbl_master_idtbl_master'                 => $masterID,
                    'tbl_company_idtbl_company'               => $company,
                    'tbl_company_branch_idtbl_company_branch' => $branch,
                    'manualtrans_main_id'                     => $journalmainID
                );
    
                $this->db->insert('tbl_account_transaction_manual', $data1);
    
                // Debit Entry
                $data2 = array(
                    'tradate'                                 => $tradate,
                    'batchno'                                 => $batchno,
                    'tratype'                                 => 'J',
                    'seqno'                                   => '2',
                    'crdr'                                    => 'D',
                    'amount'                                  => $traamount,
                    'narration'                               => $fullnarration,
                    'status'                                  => '1',
                    'insertdatetime'                          => $updatedatetime,
                    'tbl_user_idtbl_user'                     => $userID,
                    'tbl_account_idtbl_account'               => $debitchartaccount,
                    'tbl_account_detail_idtbl_account_detail' => $debitdetailaccount,
                    'tbl_master_idtbl_master'                 => $masterID,
                    'tbl_company_idtbl_company'               => $company,
                    'tbl_company_branch_idtbl_company_branch' => $branch,
                    'manualtrans_main_id'                     => $journalmainID
                );
    
                $this->db->insert('tbl_account_transaction_manual', $data2);
            }
    
            // ── Complete single transaction for ALL records ───────────────────
            $this->db->trans_complete();
    
            if($this->db->trans_status() === TRUE){
                $this->db->trans_commit();
                $this->_jsonResponse(1, 'fas fa-save', 'Record Added Successfully', 'success');
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
    public function Chartofaccountaccodetail($detailaccount){
        $this->db->select('tbl_account_idtbl_account');
        $this->db->from('tbl_account_detail');
        $this->db->where('idtbl_account_detail', $detailaccount);
        $this->db->where('status', 1);

        $respondchart=$this->db->get();

        return $respondchart->row(0)->tbl_account_idtbl_account;
    }
    // public function Journalentrybatchinsertupdate(){
    //     $userID=$_SESSION['userid'];
    //     if(!empty($this->input->post('batchMainTransID'))):$batchMainTransID = $this->input->post('batchMainTransID');endif;
    //     if(!empty($this->input->post('batchMainTransBatchNo'))):$batchMainTransBatchNo = $this->input->post('batchMainTransBatchNo');endif;
    //     if(!empty($this->input->post('batchMainTransMaster'))):$batchMainTransMaster = $this->input->post('batchMainTransMaster');endif;

    //     $glbatchtradate = $this->input->post('glbatchtradate');
    //     $glbatchcreditdebit = $this->input->post('glbatchcreditdebit');
    //     $cdtype = $this->input->post('cdtype');
    //     $glbatchaccountID = $this->input->post('glbatchaccountID');
    //     $accounttype = $this->input->post('accounttype');
    //     $glbatchnarration = $this->input->post('glbatchnarration');
    //     $glbatchamount = str_replace([',', ' '], '', $this->input->post('glbatchamount'));

    //     $company = $_SESSION['companyid'];
    //     $branch = $_SESSION['branchid'];
    //     $today=date('Y-m-d');
    //     $updatedatetime=date('Y-m-d H:i:s');

    //     $poststatus = 0;
    //     $chartaccount=0;
    //     $detailaccount=0;

    //     if($accounttype==1){$chartaccount=$glbatchaccountID;}
    //     else{$detailaccount=$glbatchaccountID;}

    //     if(empty($batchMainTransID)):
    //         $masterdata = get_account_period_acco_date($company, $branch, $glbatchtradate);
    //         $prefix   = generate_prefix($company, $branch, $glbatchtradate, 'JE');

    //         $batchno=tr_batch_num($prefix, $branch);
    //         $masterID=$masterdata->idtbl_master;
    //     else:
    //         $batchno=$batchMainTransBatchNo;
    //         $masterID=$batchMainTransMaster;

    //         $this->db->select('`poststatus`');
    //         $this->db->from('tbl_account_transaction_manual_main');
    //         $this->db->where('idtbl_account_transaction_manual_main', $batchMainTransID);
    
    //         $respondcheck = $this->db->get();
    //         $poststatus = $respondcheck->row(0)->poststatus;
    //     endif;

    //     if($poststatus==0){
    //         if(!empty($batchno)){
    //             $this->db->trans_begin();

    //             if(empty($batchMainTransID)):
    //                 $data = array(
    //                     'tradate'=> $glbatchtradate, 
    //                     'batchno'=> $batchno, 
    //                     'amount'=> '0', 
    //                     'narration'=> $glbatchnarration, 
    //                     'transactiontype'=> '1', 
    //                     'poststatus'=> '0', 
    //                     'completestatus'=> '0', 
    //                     'status'=> '1', 
    //                     'insertdatetime'=> $updatedatetime, 
    //                     'tbl_user_idtbl_user'=> $userID,
    //                     'tbl_master_idtbl_master'=> $masterID,
    //                     'tbl_company_idtbl_company'=> $company,
    //                     'tbl_company_branch_idtbl_company_branch'=> $branch
    //                 );

    //                 $this->db->insert('tbl_account_transaction_manual_main', $data);
    //                 $batchtransmainID=$this->db->insert_id();
    //             else:
    //                 $batchtransmainID=$batchMainTransID;
    //             endif;

    //             $data1 = array(
    //                 'tradate'=> $glbatchtradate, 
    //                 'batchno'=> $batchno, 
    //                 'tratype'=> 'J', 
    //                 'seqno'=> '1', 
    //                 'crdr'=> $cdtype, 
    //                 'amount'=> $glbatchamount, 
    //                 'narration'=> $glbatchnarration, 
    //                 'status'=> '1', 
    //                 'insertdatetime'=> $updatedatetime, 
    //                 'tbl_user_idtbl_user'=> $userID,
    //                 'tbl_account_idtbl_account'=> $chartaccount,
    //                 'tbl_account_detail_idtbl_account_detail'=> $detailaccount,
    //                 'tbl_master_idtbl_master'=> $masterID,
    //                 'tbl_company_idtbl_company'=> $company,
    //                 'tbl_company_branch_idtbl_company_branch'=> $branch,
    //                 'manualtrans_main_id'=> $batchtransmainID
    //             );

    //             $this->db->insert('tbl_account_transaction_manual', $data1);
    //             $batchtransID=$this->db->insert_id();

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
    //                 $obj->batchno=$batchno;
    //                 $obj->batchtransmainID=$batchtransmainID;
    //                 $obj->batchtransID=$batchtransID;
    //                 $obj->masterID=$masterID;
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
    //                 $obj->batchno='';
    //                 $obj->batchtransmainID='';
    //                 $obj->batchtransID='';
    //                 $obj->masterID='';
    //                 $obj->action=$actionJSON;

    //                 echo json_encode($obj);
    //             }
    //         }
    //         else{
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
    //             $obj->batchno='';
    //             $obj->batchtransmainID='';
    //             $obj->masterID='';
    //             $obj->action=$actionJSON;

    //             echo json_encode($obj);
    //         }
    //     }
    //     else{
    //         $actionObj=new stdClass();
    //         $actionObj->icon='fas fa-warning';
    //         $actionObj->title='';
    //         $actionObj->message='Record Error, Already post in this batch journals.';
    //         $actionObj->url='';
    //         $actionObj->target='_blank';
    //         $actionObj->type='danger';

    //         $actionJSON=json_encode($actionObj);
            
    //         $obj=new stdClass();
    //         $obj->status=0;
    //         $obj->batchno='';
    //         $obj->batchtransmainID='';
    //         $obj->batchtransID='';
    //         $obj->action=$actionJSON;

    //         echo json_encode($obj);
    //     }
    // }
    public function Journalentrybatchinsertupdate(){
        try {
            $userID         = $_SESSION['userid'];
            $company        = $_SESSION['companyid'];
            $branch         = $_SESSION['branchid'];
            $updatedatetime = date('Y-m-d H:i:s');
    
            // ── Input ─────────────────────────────────────────────────────────
            $batchMainTransID     = $this->input->post('batchMainTransID');
            $batchMainTransBatchNo= $this->input->post('batchMainTransBatchNo');
            $batchMainTransMaster = $this->input->post('batchMainTransMaster');
            $glbatchtradate       = $this->input->post('glbatchtradate');
            $glbatchcreditdebit   = $this->input->post('glbatchcreditdebit');
            $cdtype               = $this->input->post('cdtype');
            $glbatchaccountID     = $this->input->post('glbatchaccountID');
            $accounttype          = $this->input->post('accounttype');
            $payablebatch          = $this->input->post('payablebatch');
            $glbatchnarration     = $this->input->post('glbatchnarration');
            $glbatchamount        = str_replace([',', ' '], '', $this->input->post('glbatchamount'));
    
            // ── Validate inputs ───────────────────────────────────────────────
            if(empty($glbatchtradate)){
                throw new Exception('Transaction date is required');
            }
            if(empty($glbatchamount) || $glbatchamount <= 0){
                throw new Exception('Transaction amount is required');
            }
            if(empty($cdtype)){
                throw new Exception('Credit/Debit type is required');
            }
    
            // ── Resolve chart / detail account ───────────────────────────────
            $chartaccount  = 0;
            $detailaccount = 0;
    
            if($accounttype == 1) { $chartaccount  = $glbatchaccountID; }
            else                  { $detailaccount = $glbatchaccountID; }
    
            // ── Resolve batch & master ID ─────────────────────────────────────
            $poststatus = 0;
            $batchno    = '';
            $masterID   = '';
    
            if(empty($batchMainTransID)){
                // New batch — generate batchno from date-based period
                $masterdata = get_account_period_acco_date($company, $branch, $glbatchtradate);
    
                if(empty($masterdata) || empty($masterdata->idtbl_master)){
                    throw new Exception('Record Error, Active account period not found for selected date');
                }
    
                $prefix  = generate_prefix($company, $branch, $glbatchtradate, 'JE');
                $batchno = tr_batch_num($prefix, $branch);
                $masterID= $masterdata->idtbl_master;
    
            } else {
                // Existing batch — use provided batchno and master
                $batchno = $batchMainTransBatchNo;
                $masterID= $batchMainTransMaster;
    
                // Check post status of existing batch
                $this->db->select('poststatus');
                $this->db->from('tbl_account_transaction_manual_main');
                $this->db->where('idtbl_account_transaction_manual_main', $batchMainTransID);
    
                $respondcheck = $this->db->get();
    
                if(!$respondcheck || $respondcheck->num_rows() == 0){
                    throw new Exception('Batch main record not found');
                }
    
                $poststatus = $respondcheck->row(0)->poststatus;
            }
    
            // ── Validate post status ──────────────────────────────────────────
            if($poststatus == 1){
                throw new Exception('Record Error, Already post in this batch journals.');
            }
    
            if(empty($batchno)){
                throw new Exception('Record Error, Batch no could not be defined by system');
            }
    
            // ── Begin Transaction ─────────────────────────────────────────────
            $this->db->trans_begin();
    
            $batchtransmainID = '';
    
            if(empty($batchMainTransID)){
                // Insert new batch main header
                $data = array(
                    'tradate'                                 => $glbatchtradate,
                    'batchno'                                 => $batchno,
                    'amount'                                  => '0',
                    'narration'                               => $glbatchnarration,
                    'transactiontype'                         => '1',
                    'poststatus'                              => '0',
                    'completestatus'                          => '0',
                    'status'                                  => '1',
                    'insertdatetime'                          => $updatedatetime,
                    'tbl_user_idtbl_user'                     => $userID,
                    'tbl_master_idtbl_master'                 => $masterID,
                    'tbl_company_idtbl_company'               => $company,
                    'tbl_company_branch_idtbl_company_branch' => $branch
                );
    
                $this->db->insert('tbl_account_transaction_manual_main', $data);
                $batchtransmainID = $this->db->insert_id();
    
                if(empty($batchtransmainID)){
                    throw new Exception('Record Error, Failed to insert batch main record');
                }
            } else {
                $batchtransmainID = $batchMainTransID;
            }
    
            // Insert batch transaction detail line
            $data1 = array(
                'tradate'                                 => $glbatchtradate,
                'batchno'                                 => $batchno,
                'tratype'                                 => 'J',
                'seqno'                                   => '1',
                'crdr'                                    => $cdtype,
                'amount'                                  => $glbatchamount,
                'narration'                               => $glbatchnarration,
                'payablestatus'                           => $payablebatch,
                'status'                                  => '1',
                'insertdatetime'                          => $updatedatetime,
                'tbl_user_idtbl_user'                     => $userID,
                'tbl_account_idtbl_account'               => $chartaccount,
                'tbl_account_detail_idtbl_account_detail' => $detailaccount,
                'tbl_master_idtbl_master'                 => $masterID,
                'tbl_company_idtbl_company'               => $company,
                'tbl_company_branch_idtbl_company_branch' => $branch,
                'manualtrans_main_id'                     => $batchtransmainID
            );
    
            $this->db->insert('tbl_account_transaction_manual', $data1);
            $batchtransID = $this->db->insert_id();
    
            if(empty($batchtransID)){
                throw new Exception('Record Error, Failed to insert batch detail record');
            }
    
            // ── Complete Transaction ──────────────────────────────────────────
            $this->db->trans_complete();
    
            if($this->db->trans_status() === TRUE){
                $this->db->trans_commit();
    
                $actionObj          = new stdClass();
                $actionObj->icon    = 'fas fa-save';
                $actionObj->title   = '';
                $actionObj->message = 'Record Added Successfully';
                $actionObj->url     = '';
                $actionObj->target  = '_blank';
                $actionObj->type    = 'success';
    
                $obj                  = new stdClass();
                $obj->status          = 1;
                $obj->batchno         = $batchno;
                $obj->batchtransmainID= $batchtransmainID;
                $obj->batchtransID    = $batchtransID;
                $obj->masterID        = $masterID;
                $obj->action          = json_encode($actionObj);
    
                echo json_encode($obj);
    
            } else {
                $this->db->trans_rollback();
                throw new Exception('Record Error, Transaction failed');
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
    
            $obj                  = new stdClass();
            $obj->status          = 0;
            $obj->batchno         = '';
            $obj->batchtransmainID= '';
            $obj->batchtransID    = '';
            $obj->masterID        = '';
            $obj->action          = json_encode($actionObj);
    
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

    public function Printjournalentry($recordID){
        $updatedatetime = date('Y-m-d H:i:s');

        $this->db->select('tbl_account_transaction_manual_main.*, tbl_company.company, tbl_company_branch.branch');
        $this->db->from('tbl_account_transaction_manual_main');
        $this->db->join('tbl_company', 'tbl_company.idtbl_company = tbl_account_transaction_manual_main.tbl_company_idtbl_company', 'left');
        $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_account_transaction_manual_main.tbl_company_branch_idtbl_company_branch', 'left');
        $this->db->where('tbl_account_transaction_manual_main.idtbl_account_transaction_manual_main', $recordID);

        $respond = $this->db->get();

        // Fixed: guard against invalid recordID (no such journal entry)
        if ($respond->num_rows() === 0) {
            show_error('Journal entry record not found.', 404);
            return;
        }

        $this->db->select('tbl_account_transaction_manual.*, tbl_account_transaction_manual.tbl_account_detail_idtbl_account_detail, tbl_account_detail.accountno, tbl_account_detail.accountname, tbl_account_transaction_manual.tbl_account_idtbl_account, tbl_account.accountno AS chartaccountno, tbl_account.accountname AS chartaccountname');
        $this->db->from('tbl_account_transaction_manual');
        $this->db->join('tbl_account_transaction_manual_main', 'tbl_account_transaction_manual_main.idtbl_account_transaction_manual_main = tbl_account_transaction_manual.manualtrans_main_id', 'left');
        $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_account_transaction_manual.tbl_account_idtbl_account', 'left');
        $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_account_transaction_manual.tbl_account_detail_idtbl_account_detail', 'left');
        $this->db->where('tbl_account_transaction_manual.manualtrans_main_id', $recordID);
        $this->db->where('tbl_account_transaction_manual.status', 1);

        $respondpayinfo = $this->db->get();

        $html = '';

        // Fixed: wrap in a proper HTML document with inline styles
        // (Dompdf can't reliably load external Bootstrap CSS)
        $html .= '
        <html>
        <head>
            <style>
                body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; }
                .row { width: 100%; display: table; margin-bottom: 8px; }
                .col { display: table-cell; width: 50%; vertical-align: top; padding-right: 15px; }
                label { margin: 0; display: inline; }
                .font-weight-bold { font-weight: bold; }
                .small { font-size: 11px; }
                .title-style { border-bottom: 1px solid #999; padding-bottom: 4px; margin-top: 15px; }
                table { width: 100%; border-collapse: collapse; margin-top: 5px; }
                table th, table td { border: 1px solid #ccc; padding: 4px 6px; font-size: 10px; }
                table thead th { background-color: #f2f2f2; }
                .text-right { text-align: right; }
                .alert { padding: 8px; border-radius: 3px; margin-bottom: 10px; }
                .alert-warning { background-color: #fff3cd; border: 1px solid #ffeeba; color: #856404; }
                .alert-danger { background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
            </style>
        </head>
        <body>';

        if ($respond->row(0)->status == 2) {
            $html .= '
            <div class="row">
                <div class="col">
                    <div class="alert alert-warning" role="alert">
                        Record Deactivated. Kindly review the status of the record.
                    </div>
                </div>
            </div>';
        }

        if ($respond->row(0)->editstatus == 1) {
            $html .= '
            <div class="row">
                <div class="col">
                    <div class="alert alert-danger" role="alert">
                        Record in editable mode. You cannot change anything about the record.
                    </div>
                </div>
            </div>';
        }

        $html .= '
        <div class="row">
            <div class="col">
                <label class="small font-weight-bold my-0">Batch No: </label>
                <label class="small my-0">' . $respond->row(0)->batchno . '</label><br>
                <label class="small font-weight-bold my-0">Date: </label>
                <label class="small my-0">' . $respond->row(0)->tradate . '</label><br>
                <label class="small font-weight-bold my-0">Company/Branch: </label>
                <label class="small my-0">' . $respond->row(0)->company . '-' . $respond->row(0)->branch . '</label>
            </div>
            <div class="col">
                <label class="small font-weight-bold my-0">Narration: </label>
                <label class="small my-0">' . $respond->row(0)->narration . '</label><br>
                <label class="small font-weight-bold my-0">Amount: </label>
                <label class="small my-0">' . number_format($respond->row(0)->amount, 2) . '</label>
            </div>
        </div>
        <div class="row">
            <div class="col" style="width: 100%;">
                <h6 class="small title-style my-3"><span>Segregation Information</span></h6>
                <table class="table table-striped table-sm nowrap small">
                    <thead>
                        <tr>
                            <th>Account</th>
                            <th>C/D</th>
                            <th>Batch No</th>
                            <th>Narration</th>
                            <th class="text-right">Credit</th>
                            <th class="text-right">Debit</th>
                        </tr>
                    </thead>
                    <tbody>';

        $credittotal = 0;
        $debittotal  = 0;

        foreach ($respondpayinfo->result() as $rowdatainfo) {
            $credittotal += ($rowdatainfo->crdr == 'C' ? $rowdatainfo->amount : 0);
            $debittotal  += ($rowdatainfo->crdr == 'D' ? $rowdatainfo->amount : 0);

            $html .= '
            <tr>
                <td>';
                if (!empty($rowdatainfo->tbl_account_detail_idtbl_account_detail)) {
                    $html .= $rowdatainfo->accountname . ' - ' . $rowdatainfo->accountno;
                } else {
                    $html .= $rowdatainfo->chartaccountname . ' - ' . $rowdatainfo->chartaccountno;
                }
                $html .= '</td>
                <td>' . $rowdatainfo->crdr . '</td>
                <td>' . $rowdatainfo->batchno . '</td>
                <td>' . $rowdatainfo->narration . '</td>
                <td class="text-right">' . ($rowdatainfo->crdr == 'C' ? number_format($rowdatainfo->amount, 2) : '') . '</td>
                <td class="text-right">' . ($rowdatainfo->crdr == 'D' ? number_format($rowdatainfo->amount, 2) : '') . '</td>
            </tr>
            ';
        }

        $html .= '</tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-right">Total</th>
                            <th class="text-right">' . number_format($credittotal, 2) . '</th>
                            <th class="text-right">' . number_format($debittotal, 2) . '</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        </body>
        </html>';

        // ---- PDF rendering via your existing Pdf library wrapper ----
        // echo $html;
        $this->load->library('pdf');
        $this->pdf->loadHtml($html);
        $this->pdf->render();
        $this->pdf->stream('JournalEntry_' . $respond->row(0)->batchno . '.pdf', array("Attachment" => 0));
    }

    public function Journalentryreverse($recordID){
        try {
            $userID         = $_SESSION['userid'];
            $updatedatetime = date('Y-m-d H:i:s');
            $today          = date('Y-m-d');
    
            if(empty($recordID)){
                throw new Exception('Record ID is required');
            }
    
            // ── Fetch original journal header ───────────────────────────────
            $this->db->select('*');
            $this->db->from('tbl_account_transaction_manual_main');
            $this->db->where('idtbl_account_transaction_manual_main', $recordID);
            $this->db->where('status', 1);
    
            $respond = $this->db->get();
    
            if(!$respond || $respond->num_rows() == 0){
                throw new Exception('Original journal entry not found or inactive');
            }
    
            $original = $respond->row(0);
    
            if($original->poststatus == 0){
                throw new Exception('Only posted entries can be reversed. Post this entry first, or edit/delete it directly instead.');
            }
    
            // ── Fetch original detail lines ─────────────────────────────────
            $this->db->select('*');
            $this->db->from('tbl_account_transaction_manual');
            $this->db->where('manualtrans_main_id', $recordID);
            $this->db->where('status', 1);
    
            $linesResult = $this->db->get();
    
            if(!$linesResult || $linesResult->num_rows() == 0){
                throw new Exception('No active detail lines found for this entry');
            }
    
            $lines = $linesResult->result();
    
            // ── Resolve a new period/master, batch no, for the reversal ──────
            // Dated TODAY (not the original tradate), since the original
            // period may already be closed. Change $today to $original->tradate
            // below if you'd rather the reversal fall in the same period.
            $masterdata = get_account_period_acco_date(
                $original->tbl_company_idtbl_company,
                $original->tbl_company_branch_idtbl_company_branch,
                $today
            );
    
            if(empty($masterdata) || empty($masterdata->idtbl_master)){
                throw new Exception('Record Error, Account period not found for today\'s date');
            }
    
            $prefix  = generate_prefix(
                $original->tbl_company_idtbl_company,
                $original->tbl_company_branch_idtbl_company_branch,
                $today,
                'JE'
            );
            $batchno = tr_batch_num($prefix, $original->tbl_company_branch_idtbl_company_branch);
    
            if(empty($batchno)){
                throw new Exception('Record Error, Batch no could not be defined by system');
            }
    
            $masterID = $masterdata->idtbl_master;
    
            $reversalNarration = 'Reversal of Batch ' . $original->batchno . ' - ' . $original->narration;
    
            // ── Begin Transaction ─────────────────────────────────────────────
            $this->db->trans_begin();
    
            // New journal header
            $data = array(
                'tradate'                                 => $today,
                'batchno'                                 => $batchno,
                'amount'                                  => $original->amount,
                'narration'                               => $reversalNarration,
                'poststatus'                              => '0',
                'status'                                  => '1',
                'insertdatetime'                          => $updatedatetime,
                'tbl_user_idtbl_user'                     => $userID,
                'tbl_master_idtbl_master'                 => $masterID,
                'tbl_company_idtbl_company'               => $original->tbl_company_idtbl_company,
                'tbl_company_branch_idtbl_company_branch' => $original->tbl_company_branch_idtbl_company_branch
            );
    
            $this->db->insert('tbl_account_transaction_manual_main', $data);
            $reversalMainID = $this->db->insert_id();
    
            if(empty($reversalMainID)){
                throw new Exception('Record Error, Failed to insert reversal header');
            }
    
            // Reversed detail lines — CR becomes DR and vice-versa, same accounts/amounts
            $seq = 1;
            foreach($lines as $line){
                $swappedCrDr = ($line->crdr == 'C') ? 'D' : 'C';
    
                $lineData = array(
                    'tradate'                                 => $today,
                    'batchno'                                 => $batchno,
                    'tratype'                                 => 'J',
                    'seqno'                                   => $seq,
                    'crdr'                                    => $swappedCrDr,
                    'amount'                                  => $line->amount,
                    'narration'                               => 'Reversal - ' . $line->narration,
                    'payablestatus'                           => isset($line->payablestatus) ? $line->payablestatus : 0,
                    'status'                                  => '1',
                    'insertdatetime'                          => $updatedatetime,
                    'tbl_user_idtbl_user'                     => $userID,
                    'tbl_account_idtbl_account'               => $line->tbl_account_idtbl_account,
                    'tbl_account_detail_idtbl_account_detail' => $line->tbl_account_detail_idtbl_account_detail,
                    'tbl_master_idtbl_master'                 => $masterID,
                    'tbl_company_idtbl_company'               => $original->tbl_company_idtbl_company,
                    'tbl_company_branch_idtbl_company_branch' => $original->tbl_company_branch_idtbl_company_branch,
                    'manualtrans_main_id'                     => $reversalMainID
                );
    
                $this->db->insert('tbl_account_transaction_manual', $lineData);
                $seq++;
            }
    
            // ── Complete Transaction ──────────────────────────────────────────
            $this->db->trans_complete();
    
            if($this->db->trans_status() === TRUE){
                $this->db->trans_commit();
                $this->_jsonResponse(1, 'fas fa-undo', 'Reversal entry created — Batch ' . $batchno, 'success');
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