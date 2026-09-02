<?php
class Receivedchequeinfo extends CI_Model{
    // public function Receivedchequestatus(){
    //     $this->db->trans_begin();

    //     $userID=$_SESSION['userid'];
    //     $recordID=$this->input->post('recordID');;
    //     $updatedatetime=date('Y-m-d H:i:s');

    //     // Update tbl_receivable
    //     $data = array(
    //         'chequereturn' => '1',
    //         'status'=> '1', 
    //         'updateuser'=> $userID, 
    //         'updatedatetime'=> $updatedatetime
    //     );

    //     $this->db->where('idtbl_receivable', $recordID);
    //     $this->db->update('tbl_receivable', $data);

    //     // Update tbl_receivable_info
    //     $data = array(
    //         'status'=> '2', 
    //         'updateuser'=> $userID, 
    //         'updatedatetime'=> $updatedatetime
    //     );

    //     $this->db->where('tbl_receivable_idtbl_receivable', $recordID);
    //     $this->db->update('tbl_receivable_info', $data);

    //     //Check Company info
    //     $this->db->select('tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch, postbatchno, amount, narration, chequeno');
    //     $this->db->from('tbl_receivable');
    //     $this->db->where('idtbl_receivable', $recordID);

    //     $respond=$this->db->get();

    //     // Check Journal Entry
    //     // $this->db->select('tbl_account_transaction.*');
    //     // $this->db->from('tbl_account_transaction');
    //     // $this->db->join('tbl_receivable', 'tbl_receivable.batchno = tbl_account_transaction.trabatchotherno', 'left');
    //     // $this->db->where('tbl_receivable.idtbl_receivable', $recordID);
    //     // $this->db->where('tbl_account_transaction.batchno', $respond->row(0)->postbatchno);

    //     // $respondtra=$this->db->get();

    //     // $today=date('Y-m-d');

    //     // $i=1;
    //     // foreach($respondtra->result() as $rowdatalist){
    //     //     if($rowdatalist->crdr=='C'){$crdr='D';}
    //     //     else{$crdr='C';}

    //     //     $data = array(
    //     //         'tradate'=> $today, 
    //     //         'batchno'=> $batchno, 
    //     //         'trabatchotherno'=> $rowdatalist->trabatchotherno, 
    //     //         'tratype'=> 'R', 
    //     //         'seqno'=> $i, 
    //     //         'crdr'=> $crdr, 
    //     //         'accamount'=> $rowdatalist->accamount, 
    //     //         'narration'=> $rowdatalist->narration, 
    //     //         'totamount'=> $rowdatalist->totamount, 
    //     //         'reversstatus'=> '1', 
    //     //         'status'=> '1', 
    //     //         'insertdatetime'=> $updatedatetime, 
    //     //         'tbl_user_idtbl_user'=> $userID,
    //     //         'tbl_account_idtbl_account'=> $rowdatalist->tbl_account_idtbl_account,
    //     //         'tbl_master_idtbl_master'=> $masterID,
    //     //         'tbl_company_idtbl_company'=> $rowdatalist->tbl_company_idtbl_company,
    //     //         'tbl_company_branch_idtbl_company_branch'=> $rowdatalist->tbl_company_branch_idtbl_company_branch
    //     //     );
    //     //     $this->db->insert('tbl_account_transaction', $data);
    
    //     //     $datafull = array(
    //     //         'tradate'=> $today, 
    //     //         'batchno'=> $batchno, 
    //     //         'tratype'=> 'R', 
    //     //         'crdr'=> $crdr, 
    //     //         'accamount'=> $rowdatalist->accamount, 
    //     //         'narration'=> $rowdatalist->narration, 
    //     //         'totamount'=> $rowdatalist->totamount, 
    //     //         'status'=> '1', 
    //     //         'insertdatetime'=> $updatedatetime, 
    //     //         'tbl_user_idtbl_user'=> $userID,
    //     //         'tbl_account_idtbl_account'=> $rowdatalist->tbl_account_idtbl_account,
    //     //         'tbl_master_idtbl_master'=> $masterID,
    //     //         'tbl_company_idtbl_company'=> $rowdatalist->tbl_company_idtbl_company,
    //     //         'tbl_company_branch_idtbl_company_branch'=> $rowdatalist->tbl_company_branch_idtbl_company_branch
    //     //     );
    //     //     $this->db->insert('tbl_account_transaction_full', $datafull);

    //     //     $i++;
    //     // }

    //     $this->db->select('*');
    //     $this->db->from('tbl_receivable_entry');
    //     $this->db->where('tbl_receivable_idtbl_receivable', $recordID);
    //     $respondtra=$this->db->get();

    //     $prefix=trans_prefix($respond->row(0)->tbl_company_idtbl_company, $respond->row(0)->tbl_company_branch_idtbl_company_branch);
    //     $batchno=tr_batch_num($prefix, $respond->row(0)->tbl_company_branch_idtbl_company_branch);
    //     $masterdata=get_account_period($respond->row(0)->tbl_company_idtbl_company, $respond->row(0)->tbl_company_branch_idtbl_company_branch);
    //     $masterID=$masterdata->idtbl_master;

    //     $today=date('Y-m-d');

    //     $i=1;
    //     foreach($respondtra->result() as $rowdatalist){
    //         if($rowdatalist->tratype=='C'){$crdr='D';}else{$crdr='C';}
    //         $narration = $respond->row(0)->narration .' - cheque return no '.$respond->row(0)->chequeno;

    //         if($i==1){
    //             // Insert main journal header
    //             $data = array(
    //                 'tradate'                                 => $today,
    //                 'batchno'                                 => $batchno,
    //                 'amount'                                  => $respond->row(0)->amount,
    //                 'narration'                               => $narration,
    //                 'poststatus'                              => '0',
    //                 'status'                                  => '1',
    //                 'insertdatetime'                          => $updatedatetime,
    //                 'tbl_user_idtbl_user'                     => $userID,
    //                 'tbl_master_idtbl_master'                 => $masterID,
    //                 'tbl_company_idtbl_company'               => $respond->row(0)->tbl_company_idtbl_company,
    //                 'tbl_company_branch_idtbl_company_branch' => $respond->row(0)->tbl_company_branch_idtbl_company_branch
    //             );

    //             $this->db->insert('tbl_account_transaction_manual_main', $data);
    //             $journalmainID = $this->db->insert_id();
    //         }

    //         $data1 = array(
    //             'tradate'                                 => $today,
    //             'batchno'                                 => $batchno,
    //             'tratype'                                 => 'J',
    //             'seqno'                                   => $i,
    //             'crdr'                                    => $crdr,
    //             'amount'                                  => $rowdatalist->amount,
    //             'narration'                               => $narration,
    //             'payablestatus'                           => '0',
    //             'status'                                  => '1',
    //             'insertdatetime'                          => $updatedatetime,
    //             'tbl_user_idtbl_user'                     => $userID,
    //             'tbl_account_idtbl_account'               => $rowdatalist->tbl_account_idtbl_account,
    //             'tbl_account_detail_idtbl_account_detail' => $rowdatalist->tbl_account_detail_idtbl_account_detail,
    //             'tbl_master_idtbl_master'                 => $masterID,
    //             'tbl_company_idtbl_company'               => $respond->row(0)->tbl_company_idtbl_company,
    //             'tbl_company_branch_idtbl_company_branch' => $respond->row(0)->tbl_company_branch_idtbl_company_branch,
    //             'manualtrans_main_id'                     => $journalmainID
    //         );

    //         $this->db->insert('tbl_account_transaction_manual', $data1);

    //         $i++;
    //     }

    //     $this->db->trans_complete();

    //     if ($this->db->trans_status() === TRUE) {
    //         $this->db->trans_commit();
            
    //         $actionObj=new stdClass();
    //         $actionObj->icon='fas fa-save';
    //         $actionObj->title='';
    //         $actionObj->message='Record Successfully';
    //         $actionObj->url='';
    //         $actionObj->target='_blank';
    //         $actionObj->type='success';

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

    public function Receivedchequestatus(){
        $this->db->trans_begin();

        try {
            $userID = $_SESSION['userid'];
            $recordID = $this->input->post('recordID');
            $updatedatetime = date('Y-m-d H:i:s');
            $today = date('Y-m-d');

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
            $this->db->select('tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch, amount, narration, chequeno');
            $this->db->from('tbl_receivable');
            $this->db->where('idtbl_receivable', $recordID);

            $respond = $this->db->get();

            if ($respond->num_rows() == 0) {
                throw new Exception('Receivable record not found for recordID: ' . $recordID);
            }

            $this->db->select('*');
            $this->db->from('tbl_receivable_entry');
            $this->db->where('tbl_receivable_idtbl_receivable', $recordID);
            $respondtra = $this->db->get();

            $masterdata = get_account_period_acco_date($respond->row(0)->tbl_company_idtbl_company, $respond->row(0)->tbl_company_branch_idtbl_company_branch, $today);

            if (empty($masterdata) || empty($masterdata->idtbl_master)) {
                throw new Exception('Record Error, Account period not found for the given date');
            }

            $prefix   = generate_prefix($respond->row(0)->tbl_company_idtbl_company, $respond->row(0)->tbl_company_branch_idtbl_company_branch, $today, 'JE');
            $batchno   = tr_batch_num($prefix, $respond->row(0)->tbl_company_branch_idtbl_company_branch);

            if(empty($batchno)){
                throw new Exception('Record Error, Batch no could not be defined by system');
            }

            $masterID = $masterdata->idtbl_master;

            $i = 1;
            $journalmainID = null;

            foreach ($respondtra->result() as $rowdatalist) {
                if ($rowdatalist->tratype == 'C') { $crdr = 'D'; } else { $crdr = 'C'; }
                $narration = $respond->row(0)->narration . ' - cheque return no ' . $respond->row(0)->chequeno;

                if ($i == 1) {
                    // Insert main journal header
                    $data = array(
                        'tradate'                                 => $today,
                        'batchno'                                 => $batchno,
                        'amount'                                  => $respond->row(0)->amount,
                        'narration'                               => $narration,
                        'poststatus'                              => '0',
                        'status'                                  => '1',
                        'insertdatetime'                          => $updatedatetime,
                        'tbl_user_idtbl_user'                     => $userID,
                        'tbl_master_idtbl_master'                 => $masterID,
                        'tbl_company_idtbl_company'               => $respond->row(0)->tbl_company_idtbl_company,
                        'tbl_company_branch_idtbl_company_branch' => $respond->row(0)->tbl_company_branch_idtbl_company_branch
                    );

                    $this->db->insert('tbl_account_transaction_manual_main', $data);
                    $journalmainID = $this->db->insert_id();

                    if (!$journalmainID) {
                        throw new Exception('Failed to insert journal main header.');
                    }
                }

                $data1 = array(
                    'tradate'                                 => $today,
                    'batchno'                                 => $batchno,
                    'tratype'                                 => 'J',
                    'seqno'                                   => $i,
                    'crdr'                                    => $crdr,
                    'amount'                                  => $rowdatalist->amount,
                    'narration'                               => $narration,
                    'payablestatus'                           => '0',
                    'status'                                  => '1',
                    'insertdatetime'                          => $updatedatetime,
                    'tbl_user_idtbl_user'                     => $userID,
                    'tbl_account_idtbl_account'               => $rowdatalist->tbl_account_idtbl_account,
                    'tbl_account_detail_idtbl_account_detail' => $rowdatalist->tbl_account_detail_idtbl_account_detail,
                    'tbl_master_idtbl_master'                 => $masterID,
                    'tbl_company_idtbl_company'               => $respond->row(0)->tbl_company_idtbl_company,
                    'tbl_company_branch_idtbl_company_branch' => $respond->row(0)->tbl_company_branch_idtbl_company_branch,
                    'manualtrans_main_id'                     => $journalmainID
                );

                $insertResult = $this->db->insert('tbl_account_transaction_manual', $data1);

                if (!$insertResult) {
                    throw new Exception('Failed to insert journal detail row (seqno: ' . $i . ').');
                }

                $i++;
            }

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Transaction failed during DB operations.');
            }

            $this->db->trans_commit();

            $actionObj = new stdClass();
            $actionObj->icon = 'fas fa-save';
            $actionObj->title = '';
            $actionObj->message = 'Record Successfully';
            $actionObj->url = '';
            $actionObj->target = '_blank';
            $actionObj->type = 'success';

            $actionJSON = json_encode($actionObj);

            $obj = new stdClass();
            $obj->status = 1;
            $obj->action = $actionJSON;

            echo json_encode($obj);

        } catch (Exception $e) {
            $this->db->trans_rollback();

            log_message('error', 'Receivedchequestatus Error: ' . $e->getMessage());

            $actionObj = new stdClass();
            $actionObj->icon = 'fas fa-warning';
            $actionObj->title = '';
            $actionObj->message = 'Record Error: ' . $e->getMessage();
            $actionObj->url = '';
            $actionObj->target = '_blank';
            $actionObj->type = 'danger';

            $actionJSON = json_encode($actionObj);

            $obj = new stdClass();
            $obj->status = 0;
            $obj->action = $actionJSON;

            echo json_encode($obj);
        }
    }
}