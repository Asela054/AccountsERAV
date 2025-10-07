<?php
class Receiptsegregationinfo extends CI_Model{
    public function Receiptsegregationinsertupdate(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];

        $company=$this->input->post('company');
        $branch=$this->input->post('branch');
        if(!empty($this->input->post('customer'))){$customer=$this->input->post('customer');}
        if(!empty($this->input->post('invoice'))){$invoice=$this->input->post('invoice');}
        $invoiceamount=$this->input->post('invoiceamount');
        $segregationdata=$this->input->post('tableData');

        $recordOption=$this->input->post('recordOption');
        if(!empty($this->input->post('recordID'))){$recordID=$this->input->post('recordID');}
        
        if($recordOption==1){
            $prefix=rece_prefix($company, $branch);
            $masterdata=get_account_period($company, $branch);
            $batchno=tr_batch_num($prefix, $branch);
            $masterID=$masterdata->idtbl_master;
        }


        $updatedatetime=date('Y-m-d H:i:s');
        $today=date('Y-m-d');
    
        if($recordOption==1){
            if(!empty($batchno)){
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

                $payablemainID=$this->db->insert_id();

                foreach($segregationdata as $rowsegregationdata){
                    $datasub = array(
                        'tradate'=> $today, 
                        'batchno'=> $batchno, 
                        'tratype'=> 'D', 
                        'amount'=> $rowsegregationdata['col_4'], 
                        'narration'=> $rowsegregationdata['col_3'], 
                        'status'=> '1', 
                        'insertdatetime'=> $updatedatetime, 
                        'tbl_user_idtbl_user'=> $userID,
                        'tbl_master_idtbl_master'=> $masterID,
                        'tbl_company_idtbl_company'=> $company,
                        'tbl_company_branch_idtbl_company_branch'=> $branch,
                        'tbl_account_receivable_main_idtbl_account_receivable_main'=> $payablemainID,
                        'tbl_account_detail_idtbl_account_detail'=> $rowsegregationdata['col_1']
                    );

                    $this->db->insert('tbl_account_receivable', $datasub);
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
            $this->db->select('batchno, tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch, tbl_master_idtbl_master, poststatus');
            $this->db->from('tbl_account_receivable_main');
            $this->db->where('idtbl_account_receivable_main', $recordID);
            $this->db->where('status', 1);

            $respond=$this->db->get();
            
            $this->db->where('tbl_account_receivable_main_idtbl_account_receivable_main', $recordID);
            $this->db->delete('tbl_account_receivable');

            $data = array(
                'editstatus' => '0',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );
    
            $this->db->where('idtbl_account_receivable_main', $recordID);
            $this->db->update('tbl_account_receivable_main', $data);

            if($respond->row(0)->poststatus==0){
                foreach($segregationdata as $rowsegregationdata){
                    $datasub = array(
                        'tradate'=> $today, 
                        'batchno'=> $respond->row(0)->batchno, 
                        'tratype'=> 'D', 
                        'amount'=> $rowsegregationdata['col_4'], 
                        'narration'=> $rowsegregationdata['col_3'], 
                        'editstatus'=> '0', 
                        'status'=> '1', 
                        'insertdatetime'=> $updatedatetime, 
                        'tbl_user_idtbl_user'=> $userID,
                        'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
                        'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
                        'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch,
                        'tbl_account_receivable_main_idtbl_account_receivable_main'=> $recordID,
                        'tbl_account_detail_idtbl_account_detail'=> $rowsegregationdata['col_1']
                    );

                    $this->db->insert('tbl_account_receivable', $datasub);
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
    public function Receiptsegregationstatus($x, $y){
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

            $this->db->where('idtbl_account_receivable_main', $recordID);
            $this->db->update('tbl_account_receivable_main', $data);

            $datapay = array(
                'status' => '1',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('tbl_account_receivable_main_idtbl_account_receivable_main', $recordID);
            $this->db->update('tbl_account_receivable', $datapay);

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
                redirect('Receiptsegregation');                
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
                redirect('Receiptsegregation');
            }
        }
        else if($type==2){
            $data = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_account_receivable_main', $recordID);
            $this->db->update('tbl_account_receivable_main', $data);

            $datapay = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('tbl_account_receivable_main_idtbl_account_receivable_main', $recordID);
            $this->db->update('tbl_account_receivable', $datapay);

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
                redirect('Receiptsegregation');                
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
                redirect('Receiptsegregation');
            }
        }
        else if($type==3){
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
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-trash-alt';
                $actionObj->title='';
                $actionObj->message='Record Remove Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Receiptsegregation');                
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
                redirect('Receiptsegregation');
            }
        }
    }
    public function Receiptsegregationedit(){
        $recordID=$this->input->post('recordID');
        $userID=$_SESSION['userid'];
        $updatedatetime=date('Y-m-d H:i:s');

        $data = array(
            'editstatus' => '1',
            'updateuser'=> $userID, 
            'updatedatetime'=> $updatedatetime
        );

        $this->db->where('idtbl_account_receivable_main', $recordID);
        $this->db->update('tbl_account_receivable_main', $data);

        $datapay = array(
            'editstatus' => '1',
            'updateuser'=> $userID, 
            'updatedatetime'=> $updatedatetime
        );

        $this->db->where('tbl_account_receivable_main_idtbl_account_receivable_main', $recordID);
        $this->db->update('tbl_account_receivable', $datapay);

        $this->db->select('tbl_account_receivable_main.*, tbl_company.company, tbl_company_branch.branch');
        $this->db->from('tbl_account_receivable_main');
        $this->db->join('tbl_company', 'tbl_company.idtbl_company = tbl_account_receivable_main.tbl_company_idtbl_company', 'left');
        $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_account_receivable_main.tbl_company_branch_idtbl_company_branch', 'left');
        $this->db->where('tbl_account_receivable_main.idtbl_account_receivable_main', $recordID);
        $this->db->where('tbl_account_receivable_main.status', 1);

        $respond=$this->db->get();

        $this->db->select('tbl_account_receivable.amount, tbl_account_receivable.narration, tbl_account_receivable.tbl_account_detail_idtbl_account_detail, tbl_account_detail.accountno, tbl_account_detail.accountname');
        $this->db->from('tbl_account_receivable');
        $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_account_receivable.tbl_account_detail_idtbl_account_detail', 'left');
        $this->db->where('tbl_account_receivable.tbl_account_receivable_main_idtbl_account_receivable_main', $recordID);
        $this->db->where('tbl_account_receivable.status', 1);

        $respondinfo=$this->db->get();

        $html='';
        foreach($respondinfo->result() as $rowdatalist){
            $html.='
            <tr>
                <td class="d-none">'.$rowdatalist->tbl_account_detail_idtbl_account_detail.'</td>
                <td>'.$rowdatalist->accountname.' - '.$rowdatalist->accountno.'</td>
                <td>'.$rowdatalist->narration.'</td>
                <td class="text-right segamount">'.$rowdatalist->amount.'</td>
                <td class="text-right"><button type="button" class="btn btn-danger btn-sm btnremoverow"><i class="fas fa-times"></i></button></td>
            </tr>
            ';
        }

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_account_receivable_main;
        $obj->customer=$respond->row(0)->customer;
        $obj->receiptno=$respond->row(0)->receiptno;
        $obj->amount=$respond->row(0)->amount;
        $obj->company=$respond->row(0)->company;
        $obj->companyid=$respond->row(0)->tbl_company_idtbl_company;
        $obj->branch=$respond->row(0)->branch;
        $obj->branchid=$respond->row(0)->tbl_company_branch_idtbl_company_branch;
        $obj->tabledata=$html;

        echo json_encode($obj);
    }
    public function Getviewpostinfo(){
        $recordID=$this->input->post('recordID');
        $updatedatetime=date('Y-m-d H:i:s');

        $data = array(
            'postviewtime'=> $updatedatetime
        );

        $this->db->where('idtbl_account_receivable_main', $recordID);
        $this->db->where('poststatus', 0);
        $this->db->update('tbl_account_receivable_main', $data);

        $this->db->select('tbl_account_receivable_main.*, tbl_company.company, tbl_company_branch.branch, tbl_customer.customer');
        $this->db->from('tbl_account_receivable_main');
        $this->db->join('tbl_company', 'tbl_company.idtbl_company = tbl_account_receivable_main.tbl_company_idtbl_company', 'left');
        $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_account_receivable_main.tbl_company_branch_idtbl_company_branch', 'left');
        $this->db->join('tbl_customer', 'tbl_customer.idtbl_customer = tbl_account_receivable_main.customer', 'left');
        $this->db->where('tbl_account_receivable_main.idtbl_account_receivable_main', $recordID);
        // $this->db->where('tbl_account_receivable_main.status', 1);

        $respond=$this->db->get();

        $this->db->select('tbl_account_receivable.*, tbl_account_detail.accountno, tbl_account_detail.accountname');
        $this->db->from('tbl_account_receivable');
        $this->db->join('tbl_account_receivable_main', 'tbl_account_receivable_main.idtbl_account_receivable_main = tbl_account_receivable.tbl_account_receivable_main_idtbl_account_receivable_main', 'left');
        $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_account_receivable.tbl_account_detail_idtbl_account_detail', 'left');
        $this->db->where('tbl_account_receivable_main.idtbl_account_receivable_main', $recordID);
        // $this->db->where('tbl_account_receivable.status', 1);

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
                <label class="small font-weight-bold my-0">customer: </label>
                <label class="small my-0">'.$respond->row(0)->customer.'</label><br>
                <label class="small font-weight-bold my-0">Invoice No: </label>
                <label class="small my-0">'.$respond->row(0)->receiptno.'</label><br>
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
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>';
                    foreach($respondpayinfo->result() as $rowdatainfo){
                        $html.='
                        <tr>
                            <td>'.$rowdatainfo->accountname.' - '.$rowdatainfo->accountno.'</td>
                            <td>'.$rowdatainfo->narration.'</td>
                            <td class="text-right">'.number_format($rowdatainfo->amount, 2).'</td>
                        </tr>
                        ';
                    }
                    $html.='</tbody>
                </table>
            </div>
        </div>
        ';

        $obj=new stdClass();
        $obj->html=$html;
        $obj->editablestatus=$respond->row(0)->editstatus;

        echo json_encode($obj);
    }
    public function Receiptsegregationposting(){
        $this->db->trans_begin();
        $recordID=$this->input->post('recordID');
        $updatedatetime=date('Y-m-d H:i:s');
        $userID=$_SESSION['userid'];

        $i=0;

        $this->db->select('tradate, batchno, amount, poststatus, status, editstatus, postviewtime, updatedatetime, tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch, tbl_master_idtbl_master, `customer`, `receiptno`');
        $this->db->from('tbl_account_receivable_main');
        $this->db->where('idtbl_account_receivable_main', $recordID);
        $this->db->where('status', 1);

        $respond=$this->db->get();

        if($respond->row(0)->poststatus==0 && $respond->row(0)->status==1 && $respond->row(0)->editstatus==0){
            if($respond->row(0)->postviewtime>$respond->row(0)->updatedatetime){
                $company=$respond->row(0)->tbl_company_idtbl_company;
                $branch=$respond->row(0)->tbl_company_branch_idtbl_company_branch;
                
                $prefix=trans_prefix($company, $branch);
                $batchno=tr_batch_num($prefix, $branch);
                    
                $data = array(
                    'poststatus'=> '1',
                    'postuser'=> $userID,
                    'postviewtime'=> NULL
                );
        
                $this->db->where('idtbl_account_receivable_main', $recordID);
                $this->db->update('tbl_account_receivable_main', $data);

                //Sales info update
                $datasale = array(
                    'poststatus'=> '1',
                    'updateuser'=> $userID,
                    'updatedatetime'=> $updatedatetime
                );
        
                $this->db->where('invno', $respond->row(0)->receiptno);
                $this->db->where('tbl_customer_idtbl_customer', $respond->row(0)->customer);
                $this->db->update('tbl_sales_info', $datasale);

                $i=1;

                //Debtors account Transaction
                $this->db->where('tbl_account_allocation.companybank', $company);
                $this->db->where('tbl_account_allocation.branchcompanybank', $branch);
                $this->db->where('tbl_account.tbl_account_type_idtbl_account_type', 2);
                $this->db->where('tbl_account.specialcate', 2);
                $this->db->where('tbl_account.status', 1);
                $this->db->where('tbl_account_allocation.status', 1);
                $this->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
                $this->db->select('`tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`');
                $this->db->from('tbl_account');
                $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');

                $responddebtor=$this->db->get();

                $datacredit = array(
                    'tradate'=> $respond->row(0)->tradate, 
                    'batchno'=> $batchno, 
                    'trabatchotherno'=> $respond->row(0)->batchno, 
                    'tratype'=> 'R', 
                    'seqno'=> $i, 
                    'crdr'=> 'D', 
                    'accamount'=> $respond->row(0)->amount, 
                    'narration'=> $respond->row(0)->receiptno, 
                    'totamount'=> $respond->row(0)->amount, 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_account_idtbl_account'=> $responddebtor->row(0)->idtbl_account,
                    'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
                    'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
                    'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
                );
                $this->db->insert('tbl_account_transaction', $datacredit);
        
                $datacreditfull = array(
                    'tradate'=> $respond->row(0)->tradate, 
                    'batchno'=> $batchno, 
                    'tratype'=> 'R', 
                    'crdr'=> 'D', 
                    'accamount'=> $respond->row(0)->amount, 
                    'narration'=> $respond->row(0)->receiptno, 
                    'totamount'=> $respond->row(0)->amount, 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_account_idtbl_account'=> $responddebtor->row(0)->idtbl_account,
                    'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
                    'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
                    'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
                );
                $this->db->insert('tbl_account_transaction_full', $datacreditfull);

                //Other account Transaction
                $this->db->select('`idtbl_account_receivable`, `tradate`, `batchno`, `amount`, `narration`, `tbl_master_idtbl_master`, `tbl_company_idtbl_company`, `tbl_company_branch_idtbl_company_branch`, `tbl_account_detail_idtbl_account_detail`');
                $this->db->from('tbl_account_receivable');
                $this->db->where('tbl_account_receivable_main_idtbl_account_receivable_main', $recordID);
                $this->db->where('status', 1);

                $responddetail=$this->db->get();

                foreach($responddetail->result() AS $rowdetail){
                    $i++;

                    $receivedetailID=$rowdetail->idtbl_account_receivable;
                    $tradate=$rowdetail->tradate;
                    $segbatchno=$rowdetail->batchno;
                    $detailaccount=$rowdetail->tbl_account_detail_idtbl_account_detail;
                    $company=$rowdetail->tbl_company_idtbl_company;
                    $branch=$rowdetail->tbl_company_branch_idtbl_company_branch;
                    $masterID=$rowdetail->tbl_master_idtbl_master;
                    $amount=$rowdetail->amount;
                    $narration=$rowdetail->narration;
                    
                    $chartofaccountinfo=get_chart_account_acco_child_account($company, $branch, $detailaccount);

                    $chartofaccountID=$chartofaccountinfo->row(0)->idtbl_account;

                    $data = array(
                        'tradate'=> $tradate, 
                        'batchno'=> $batchno, 
                        'trabatchotherno'=> $segbatchno, 
                        'tratype'=> 'R', 
                        'seqno'=> $i, 
                        'crdr'=> 'C', 
                        'accamount'=> $amount, 
                        'narration'=> $narration, 
                        'totamount'=> $amount, 
                        'status'=> '1', 
                        'insertdatetime'=> $updatedatetime, 
                        'tbl_user_idtbl_user'=> $userID,
                        'tbl_account_idtbl_account'=> $chartofaccountID,
                        'tbl_master_idtbl_master'=> $masterID,
                        'tbl_company_idtbl_company'=> $company,
                        'tbl_company_branch_idtbl_company_branch'=> $branch
                    );
    
                    $this->db->insert('tbl_account_transaction', $data);

                    $datafull = array(
                        'tradate'=> $tradate, 
                        'batchno'=> $batchno, 
                        'tratype'=> 'R', 
                        'crdr'=> 'C', 
                        'accamount'=> $amount, 
                        'narration'=> $narration, 
                        'totamount'=> $amount, 
                        'status'=> '1', 
                        'insertdatetime'=> $updatedatetime, 
                        'tbl_user_idtbl_user'=> $userID,
                        'tbl_account_idtbl_account'=> $chartofaccountID,
                        'tbl_master_idtbl_master'=> $masterID,
                        'tbl_company_idtbl_company'=> $company,
                        'tbl_company_branch_idtbl_company_branch'=> $branch
                    );
    
                    $this->db->insert('tbl_account_transaction_full', $datafull);

                    //Update POST Status Detail
                    $datadetail = array(
                        'poststatus'=> '1',
                        'postuser'=> $userID
                    );
            
                    $this->db->where('idtbl_account_receivable', $receivedetailID);
                    $this->db->update('tbl_account_receivable', $datadetail);
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
    public function Getinvoiceaccocustomer(){
        $recordID=$this->input->post('recordID');

        $this->db->select('idtbl_sales_info, invno, amount');
        $this->db->from('tbl_sales_info');
        $this->db->where('status', 1);
        $this->db->where('paystatus', 0);
        $this->db->where('poststatus', 0);
        $this->db->where('tbl_customer_idtbl_customer', $recordID);

        $respond=$this->db->get();

        echo json_encode($respond->result());
    }
}