<?php
class Apiinfo extends CI_Model{
    public function Receiptsegregationinsertupdate(){
        header('Content-Type: application/json');

        try {
            $userID=$this->input->post('userid');
            $company=$this->input->post('company');
            $branch=$this->input->post('branch');
            if(!empty($this->input->post('customer'))){$customer=$this->input->post('customer');}
            if(!empty($this->input->post('invoice'))){$invoice=$this->input->post('invoice');}
            $invoiceamount=$this->input->post('invoiceamount');
            $segregationdata=json_decode($this->input->post('segregationdata'));

            $prefix=rece_prefix($company, $branch);
            $masterdata=get_account_period($company, $branch);
            $batchno=tr_batch_num($prefix, $branch);

            if (empty($batchno)) {
                throw new Exception("Failed to generate batch number");
            }

            $masterID=$masterdata->idtbl_master;
            $updatedatetime=date('Y-m-d H:i:s');
            $today=date('Y-m-d');

            $this->db->trans_begin();

            $data = array(
                'tradate'=> $today, 
                'batchno'=> $batchno, 
                'customer'=> $customer, 
                'receiptno'=> $invoice, 
                'amount'=> $invoiceamount, 
                'poststatus'=> '0', 
                'status'=> '1', 
                'insertdatetime'=> $updatedatetime, 
                'tbl_user_idtbl_user'=> $userID,
                'tbl_company_idtbl_company'=> $company,
                'tbl_company_branch_idtbl_company_branch'=> $branch,
                'tbl_master_idtbl_master'=> $masterID
            );

            $this->db->insert('tbl_account_receivable_main', $data);
            $receivablemainID=$this->db->insert_id();

            foreach($segregationdata as $rowsegregationdata){
                $datasub = array(
                    'tradate'=> $today, 
                    'batchno'=> $batchno, 
                    'tratype'=> $rowsegregationdata->crder, 
                    'amount'=> $rowsegregationdata->amount, 
                    'narration'=> $rowsegregationdata->narration, 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_master_idtbl_master'=> $masterID,
                    'tbl_company_idtbl_company'=> $company,
                    'tbl_company_branch_idtbl_company_branch'=> $branch,
                    'tbl_account_receivable_main_idtbl_account_receivable_main'=> $receivablemainID,
                    'tbl_account_idtbl_account'=> $rowsegregationdata->chartaccount,
                    'tbl_account_detail_idtbl_account_detail'=> $rowsegregationdata->detailaccount
                );

                $this->db->insert('tbl_account_receivable', $datasub);
            }

            $this->db->trans_complete();
            
            // Return success response
            echo json_encode([
                'status' => 'success',
                'code' => 200,
                'message' => 'Transaction completed successfully',
                'data' => [
                    'batch_no' => $batchno,
                    'receivable_main_id' => $receivablemainID
                ]
            ]);
        } catch (Exception $e) {
            $this->db->trans_rollback();
            
            // Return error response
            http_response_code(500); // Set proper HTTP status code
            echo json_encode([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => null
            ]);
        }
    }
    public function Receiptsegregationstatus(){
        $this->db->trans_begin();
        $userID=$this->input->post('userid');
        $invoiceID=$this->input->post('invoiceid');
        $updatedatetime=date('Y-m-d H:i:s');

        $this->db->select('idtbl_account_receivable_main');
        $this->db->from('tbl_account_receivable_main');
        $this->db->where('status', 1);
        $this->db->where('poststatus', 0);
        $this->db->where('receiptno', $invoiceID);

        $respond=$this->db->get();

        $recordID=$respond->row(0)->idtbl_account_receivable_main;

        $data = array(
            'status' => '3',
            'updateuser'=> $userID, 
            'updatedatetime'=> $updatedatetime
        );

        $this->db->where('idtbl_account_receivable_main', $recordID);
        $this->db->update('tbl_account_receivable_main', $data);

        $datapay = array(
            'status' => '3',
            'updateuser'=> $userID, 
            'updatedatetime'=> $updatedatetime
        );

        $this->db->where('tbl_account_receivable_main_idtbl_account_receivable_main', $recordID);
        $this->db->update('tbl_account_receivable', $datapay);

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            
            $obj=new stdClass();
            $obj->status=200;

            echo json_encode($obj);
        } else {
            $this->db->trans_rollback();

            $obj=new stdClass();
            $obj->status=500;

            echo json_encode($obj);
        }
    }
    public function Payablesegregationinsertupdate(){
        header('Content-Type: application/json');

        try {
            $userID=$this->input->post('userid');
            $company=$this->input->post('company');
            $branch=$this->input->post('branch');
            if(!empty($this->input->post('supplier'))){$supplier=$this->input->post('supplier');}
            if(!empty($this->input->post('invoice'))){$invoice=$this->input->post('invoice');}
            $invoiceamount=$this->input->post('invoiceamount');
            $segregationdata=json_decode($this->input->post('segregationdata'));
            
            $prefix=pay_prefix($company, $branch);
            $masterdata=get_account_period($company, $branch);
            $batchno=tr_batch_num($prefix, $branch);

            if (empty($batchno)) {
                throw new Exception("Failed to generate batch number");
            }
            
            $masterID=$masterdata->idtbl_master;
            $updatedatetime=date('Y-m-d H:i:s');
            $today=date('Y-m-d');

            $this->db->trans_begin();

            $data = array(
                'tradate'=> $today, 
                'batchno'=> $batchno, 
                'supplier'=> $supplier, 
                'invoiceno'=> $invoice, 
                'amount'=> $invoiceamount, 
                'poststatus'=> '0', 
                'status'=> '1', 
                'insertdatetime'=> $updatedatetime, 
                'tbl_user_idtbl_user'=> $userID,
                'tbl_company_idtbl_company'=> $company,
                'tbl_company_branch_idtbl_company_branch'=> $branch,
                'tbl_master_idtbl_master'=> $masterID
            );

            $this->db->insert('tbl_account_payable_main', $data);
            $payablemainID=$this->db->insert_id();

            foreach($segregationdata as $rowsegregationdata){
                $datasub = array(
                    'tradate'=> $today, 
                    'batchno'=> $batchno, 
                    'tratype'=> $rowsegregationdata->crder, 
                    'amount'=> $rowsegregationdata->amount, 
                    'narration'=> $rowsegregationdata->narration, 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_master_idtbl_master'=> $masterID,
                    'tbl_company_idtbl_company'=> $company,
                    'tbl_company_branch_idtbl_company_branch'=> $branch,
                    'tbl_account_payable_main_idtbl_account_payable_main'=> $payablemainID,
                    'tbl_account_idtbl_account'=> $rowsegregationdata->chartaccount,
                    'tbl_account_detail_idtbl_account_detail'=> $rowsegregationdata->detailaccount
                );

                $this->db->insert('tbl_account_payable', $datasub);
            }

            if ($this->db->trans_status() === FALSE) {
                throw new Exception("Database error occurred");
            }

            $this->db->trans_commit();

            // Return success response
            echo json_encode([
                'status' => 'success',
                'code' => 200,
                'message' => 'Transaction completed successfully',
                'data' => [
                    'batch_no' => $batchno,
                    'payable_main_id' => $payablemainID
                ]
            ]);
        } catch (Exception $e) {
            $this->db->trans_rollback();
            
            // Return error response
            http_response_code(500); // Set proper HTTP status code
            echo json_encode([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => null
            ]);
        }
    }
    public function Payablesegregationstatus(){
        $this->db->trans_begin();
        $userID=$this->input->post('userid');
        $invoiceID=$this->input->post('invoiceid');
        $updatedatetime=date('Y-m-d H:i:s');

        $this->db->select('idtbl_account_payable_main');
        $this->db->from('tbl_account_payable_main');
        $this->db->where('status', 1);
        $this->db->where('poststatus', 0);
        $this->db->where('invoiceno', $invoiceID);

        $respond=$this->db->get();

        $recordID=$respond->row(0)->idtbl_account_payable_main;

        $data = array(
            'status' => '3',
            'updateuser'=> $userID, 
            'updatedatetime'=> $updatedatetime
        );

        $this->db->where('idtbl_account_payable_main', $recordID);
        $this->db->update('tbl_account_payable_main', $data);

        $datapay = array(
            'status' => '3',
            'updateuser'=> $userID, 
            'updatedatetime'=> $updatedatetime
        );

        $this->db->where('tbl_account_payable_main_idtbl_account_payable_main', $recordID);
        $this->db->update('tbl_account_payable', $datapay);

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            
            $obj=new stdClass();
            $obj->status=200;

            echo json_encode($obj);
        } else {
            $this->db->trans_rollback();

            $obj=new stdClass();
            $obj->status=500;

            echo json_encode($obj);
        }
    }
    public function Issuematerialprocess(){
        header('Content-Type: application/json');

        try {
            $userID=$this->input->post('userid');
            $company=$this->input->post('company');
            $branch=$this->input->post('branch');
            $tradate=$this->input->post('tradate');
            $traamount=$this->input->post('traamount');
            $accountcrno=$this->input->post('accountcrno');
            $narrationcr=$this->input->post('narrationcr');
            $accountdrno=$this->input->post('accountdrno');
            $narrationdr=$this->input->post('narrationdr');

            $updatedatetime=date('Y-m-d H:i:s');

            $fullnarration=$narrationcr.' & '.$narrationdr;

            $prefix=journal_prefix($company, $branch);
            $masterdata=get_account_period($company, $branch);
            $batchno=tr_batch_num($prefix, $branch);

            if (empty($batchno)) {
                throw new Exception("Failed to generate batch number");
            }

            $masterID=$masterdata->idtbl_master;

            // $this->db->trans_begin();

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
                'tbl_account_idtbl_account'=> $accountcrno,
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
                'tbl_account_idtbl_account'=> $accountdrno,
                'tbl_master_idtbl_master'=> $masterID,
                'tbl_company_idtbl_company'=> $company,
                'tbl_company_branch_idtbl_company_branch'=> $branch,
                'manualtrans_main_id'=> $journalmainID
            );

            $this->db->insert('tbl_account_transaction_manual', $data2);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception("Database error occurred");
            }

            $this->db->trans_commit();

            // Return success response
            echo json_encode([
                'status' => 'success',
                'code' => 200,
                'message' => 'Transaction completed successfully',
                'data' => [
                    'batch_no' => $batchno,
                    'journal_main_id' => $journalmainID
                ]
            ]);
        } catch (Exception $e) {
            $this->db->trans_rollback();
            
            // Return error response
            http_response_code(500); // Set proper HTTP status code
            echo json_encode([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => null
            ]);
        }
            //     $this->db->trans_complete();
            //     if ($this->db->trans_status() === TRUE) {
            //         $this->db->trans_commit();
                    
            //         $obj=new stdClass();
            //         $obj->status=200;

            //         echo json_encode($obj);
            //     } else {
            //         $this->db->trans_rollback();

            //         $obj=new stdClass();
            //         $obj->status=500;

            //         echo json_encode($obj);
            //     }
            // }
            // else{
            //     $obj=new stdClass();
            //     $obj->status=500;

            //     echo json_encode($obj);
            // }
    }
    public function Payrollsalaryprocess(){
        $userID=$this->input->post('userid');
        $company=$this->input->post('company');
        $branch=$this->input->post('branch');
        $tradate=$this->input->post('tradate');
        $traamount=$this->input->post('traamount');
        $accountcrno=$this->input->post('accountcrno');
        $narrationcr=$this->input->post('narrationcr');
        $accountdrno=$this->input->post('accountdrno');
        $narrationdr=$this->input->post('narrationdr');

        $updatedatetime=date('Y-m-d H:i:s');

        $fullnarration=$narrationcr.' & '.$narrationdr;

        $prefix=journal_prefix($company, $branch);
        $masterdata=get_account_period($company, $branch);
        $batchno=tr_batch_num($prefix, $branch);
        $masterID=$masterdata->idtbl_master;

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
                'tbl_account_idtbl_account'=> $accountcrno,
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
                'tbl_account_idtbl_account'=> $accountdrno,
                'tbl_master_idtbl_master'=> $masterID,
                'tbl_company_idtbl_company'=> $company,
                'tbl_company_branch_idtbl_company_branch'=> $branch,
                'manualtrans_main_id'=> $journalmainID
            );

            $this->db->insert('tbl_account_transaction_manual', $data2);

            $this->db->trans_complete();
            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $obj=new stdClass();
                $obj->status=200;

                echo json_encode($obj);
            } else {
                $this->db->trans_rollback();

                $obj=new stdClass();
                $obj->status=500;

                echo json_encode($obj);
            }
        }
        else{
            $obj=new stdClass();
            $obj->status=500;

            echo json_encode($obj);
        }
    }
    public function Costmaterialprocess(){
        header('Content-Type: application/json');

        try {
            $userID=$this->input->post('userid');
            $company=$this->input->post('company');
            $branch=$this->input->post('branch');
            $customer=$this->input->post('customer');
            $jobdetailid=$this->input->post('jobid');
            $segregationdata=json_decode($this->input->post('jobfinishdata'));

            $prefix=pay_prefix($company, $branch);
            $masterdata=get_account_period($company, $branch);
            $batchno=tr_batch_num($prefix, $branch);

            if (empty($batchno)) {
                throw new Exception("Failed to generate batch number");
            }
            
            $masterID=$masterdata->idtbl_master;
            $updatedatetime=date('Y-m-d H:i:s');
            $today=date('Y-m-d');

            $this->db->select('job_no, tbl_customerinquiry_idtbl_customerinquiry');
            $this->db->from('tbl_customerinquiry_detail');
            $this->db->where('idtbl_customerinquiry_detail', $jobdetailid); 
            $respondinquery = $this->db->get();

            $fullnarration = 'Cost Material for Job No: '.$respondinquery->row(0)->job_no;

            $this->db->trans_begin();

            $data = array(
                'tradate'=> $today, 
                'batchno'=> $batchno, 
                'amount'=> 0, 
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

            $i=1;
            $jurnalnettotal=0;
            foreach($segregationdata as $rowsegregationdata){
                $jurnalnettotal += $rowsegregationdata->amount;

                $data1 = array(
                    'tradate'=> $today, 
                    'batchno'=> $batchno, 
                    'tratype'=> 'J', 
                    'seqno'=> $i, 
                    'crdr'=> $rowsegregationdata->crder, 
                    'amount'=> $rowsegregationdata->amount, 
                    'narration'=> $rowsegregationdata->narration, 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_account_idtbl_account'=> $rowsegregationdata->chartaccount,
                    'tbl_account_detail_idtbl_account_detail'=> $rowsegregationdata->detailaccount,
                    'tbl_master_idtbl_master'=> $masterID,
                    'tbl_company_idtbl_company'=> $company,
                    'tbl_company_branch_idtbl_company_branch'=> $branch,
                    'manualtrans_main_id'=> $journalmainID
                );

                $this->db->insert('tbl_account_transaction_manual', $data1);
            }

            // Update the main journal entry with the total amount
            $this->db->where('idtbl_account_transaction_manual_main', $journalmainID);
            $this->db->update('tbl_account_transaction_manual_main', array('amount' => $jurnalnettotal));

            if ($this->db->trans_status() === FALSE) {
                throw new Exception("Database error occurred");
            }

            $this->db->trans_commit();

            // Return success response
            echo json_encode([
                'status' => 'success',
                'code' => 200,
                'message' => 'Transaction completed successfully',
                'data' => [
                    'batch_no' => $batchno,
                    'journal_main_id' => $journalmainID
                ]
            ]);
        } catch (Exception $e) {
            $this->db->trans_rollback();
            
            // Return error response
            http_response_code(500); // Set proper HTTP status code
            echo json_encode([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => null
            ]);
        }
    }
    public function JurnalEntryProcess(){
        header('Content-Type: application/json');

        try {
            $userID=$this->input->post('userid');
            $company=$this->input->post('company');
            $branch=$this->input->post('branch');
            $fullnarration=$this->input->post('fullnarration');
            $segregationdata=json_decode($this->input->post('jurnalentrydata'));

            $prefix=pay_prefix($company, $branch);
            $masterdata=get_account_period($company, $branch);
            $batchno=tr_batch_num($prefix, $branch);

            if (empty($batchno)) {
                throw new Exception("Failed to generate batch number");
            }
            
            $masterID=$masterdata->idtbl_master;
            $updatedatetime=date('Y-m-d H:i:s');
            $today=date('Y-m-d');

            $this->db->trans_begin();

            $data = array(
                'tradate'=> $today, 
                'batchno'=> $batchno, 
                'amount'=> 0, 
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

            $i=1;
            $jurnalnettotal=0;
            foreach($segregationdata as $rowsegregationdata){
                $jurnalnettotal += $rowsegregationdata->amount;

                $data1 = array(
                    'tradate'=> $today, 
                    'batchno'=> $batchno, 
                    'tratype'=> 'J', 
                    'seqno'=> $i, 
                    'crdr'=> $rowsegregationdata->crder, 
                    'amount'=> $rowsegregationdata->amount, 
                    'narration'=> $rowsegregationdata->narration, 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_account_idtbl_account'=> $rowsegregationdata->chartaccount,
                    'tbl_account_detail_idtbl_account_detail'=> $rowsegregationdata->detailaccount,
                    'tbl_master_idtbl_master'=> $masterID,
                    'tbl_company_idtbl_company'=> $company,
                    'tbl_company_branch_idtbl_company_branch'=> $branch,
                    'manualtrans_main_id'=> $journalmainID
                );

                $this->db->insert('tbl_account_transaction_manual', $data1);
            }

            // Update the main journal entry with the total amount
            $this->db->where('idtbl_account_transaction_manual_main', $journalmainID);
            $this->db->update('tbl_account_transaction_manual_main', array('amount' => $jurnalnettotal));

            if ($this->db->trans_status() === FALSE) {
                throw new Exception("Database error occurred");
            }

            $this->db->trans_commit();

            // Return success response
            echo json_encode([
                'status' => 'success',
                'code' => 200,
                'message' => 'Transaction completed successfully',
                'data' => [
                    'batch_no' => $batchno,
                    'journal_main_id' => $journalmainID
                ]
            ]);
        } catch (Exception $e) {
            $this->db->trans_rollback();
            
            // Return error response
            http_response_code(500); // Set proper HTTP status code
            echo json_encode([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => null
            ]);
        }
    }
}