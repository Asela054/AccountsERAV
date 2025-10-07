<?php
class Receiptsegregationinfo extends CI_Model{
    public function Receiptsegregationinsertupdate(){
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

                $payablemainID=$this->db->insert_id();

                foreach($segregationdata as $rowsegregationdata){
                    $chartofaccount='';
                    $chartofdetailaccount='';
                    if($rowsegregationdata['col_7']==1){$chartofaccount=$rowsegregationdata['col_1'];}
                    else if($rowsegregationdata['col_7']==2){$chartofdetailaccount=$rowsegregationdata['col_1'];}

                    if($rowsegregationdata['col_4']=='D'){$amount=$rowsegregationdata['col_5'];}
                    else if($rowsegregationdata['col_4']=='C'){$amount=$rowsegregationdata['col_6'];}
                    
                    $datasub = array(
                        'tradate'=> $today, 
                        'batchno'=> $batchno, 
                        'tratype'=> $rowsegregationdata['col_4'], 
                        'amount'=> $amount, 
                        'narration'=> $rowsegregationdata['col_3'], 
                        'status'=> '1', 
                        'insertdatetime'=> $updatedatetime, 
                        'tbl_user_idtbl_user'=> $userID,
                        'tbl_master_idtbl_master'=> $masterID,
                        'tbl_company_idtbl_company'=> $company,
                        'tbl_company_branch_idtbl_company_branch'=> $branch,
                        'tbl_account_receivable_main_idtbl_account_receivable_main'=> $payablemainID,
                        'tbl_account_idtbl_account'=> $chartofaccount,
                        'tbl_account_detail_idtbl_account_detail'=> $chartofdetailaccount
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
            $this->db->trans_begin();

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
                    $chartofaccount='';
                    $chartofdetailaccount='';
                    if($rowsegregationdata['col_7']==1){$chartofaccount=$rowsegregationdata['col_1'];}
                    else if($rowsegregationdata['col_7']==2){$chartofdetailaccount=$rowsegregationdata['col_1'];}

                    if($rowsegregationdata['col_4']=='D'){$amount=$rowsegregationdata['col_5'];}
                    else if($rowsegregationdata['col_4']=='C'){$amount=$rowsegregationdata['col_6'];}

                    $datasub = array(
                        'tradate'=> $today, 
                        'batchno'=> $respond->row(0)->batchno, 
                        'tratype'=> $rowsegregationdata['col_4'], 
                        'amount'=> $amount, 
                        'narration'=> $rowsegregationdata['col_3'], 
                        'editstatus'=> '0', 
                        'status'=> '1', 
                        'insertdatetime'=> $updatedatetime, 
                        'tbl_user_idtbl_user'=> $userID,
                        'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
                        'tbl_company_idtbl_company'=> $company,
                        'tbl_company_branch_idtbl_company_branch'=> $branch,
                        'tbl_account_receivable_main_idtbl_account_receivable_main'=> $recordID,
                        'tbl_account_idtbl_account'=> $chartofaccount,
                        'tbl_account_detail_idtbl_account_detail'=> $chartofdetailaccount
                    );

                    $this->db->insert('tbl_account_receivable', $datasub);
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

        $this->db->select('tbl_account_receivable_main.*, tbl_company.company, tbl_company_branch.branch, tbl_customer.customer AS customername');
        $this->db->from('tbl_account_receivable_main');
        $this->db->join('tbl_company', 'tbl_company.idtbl_company = tbl_account_receivable_main.tbl_company_idtbl_company', 'left');
        $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_account_receivable_main.tbl_company_branch_idtbl_company_branch', 'left');
        $this->db->join('tbl_customer', 'tbl_customer.idtbl_customer = tbl_account_receivable_main.customer', 'left');
        $this->db->where('tbl_account_receivable_main.idtbl_account_receivable_main', $recordID);
        $this->db->where('tbl_account_receivable_main.status', 1);

        $respond=$this->db->get();

        $this->db->select('tbl_account_receivable.amount, tbl_account_receivable.narration, tbl_account_receivable.tbl_account_idtbl_account, tbl_account_receivable.tbl_account_detail_idtbl_account_detail, tbl_account_receivable.tratype, tbl_account_detail.accountno, tbl_account_detail.accountname, tbl_account.accountno AS chartaccountno, tbl_account.accountname AS chartaccountname');
        $this->db->from('tbl_account_receivable');
        $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_account_receivable.tbl_account_detail_idtbl_account_detail', 'left');
        $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_account_receivable.tbl_account_idtbl_account', 'left');
        $this->db->where('tbl_account_receivable.tbl_account_receivable_main_idtbl_account_receivable_main', $recordID);
        $this->db->where('tbl_account_receivable.status', 1);

        $respondinfo=$this->db->get();

        $html='';
        foreach($respondinfo->result() as $rowdatalist){
            if($rowdatalist->tratype=='D'){$debitamount=$rowdatalist->amount;$creditamount='';}
            else if($rowdatalist->tratype=='C'){$creditamount=$rowdatalist->amount;$debitamount='';}
            
            $html.='
            <tr>
                <td class="d-none">';
                if(!empty($rowdatalist->tbl_account_detail_idtbl_account_detail)){
                    $html.=$rowdatalist->tbl_account_detail_idtbl_account_detail;
                    $accounttype=2;
                }
                else{
                    $html.=$rowdatalist->tbl_account_idtbl_account;
                    $accounttype=1;
                }
                $html.='</td>
                <td>';
                if(!empty($rowdatalist->tbl_account_detail_idtbl_account_detail)){
                    $html.=$rowdatalist->accountname.' - '.$rowdatalist->accountno;
                }
                else{
                    $html.=$rowdatalist->chartaccountname.' - '.$rowdatalist->chartaccountno;
                }
                $html.='</td>
                <td>'.$rowdatalist->narration.'</td>
                <td>'.$rowdatalist->tratype.'</td>
                <td class="text-right debitamount">'.$debitamount.'</td>
                <td class="text-right creditamount">'.$creditamount.'</td>
                <td class="d-none">'.$accounttype.'</td>
            </tr>
            ';
        }

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_account_receivable_main;
        $obj->customer=$respond->row(0)->customer;
        $obj->customername=$respond->row(0)->customername;
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

        $this->db->select('tbl_account_receivable.*, tbl_account_detail.accountno, tbl_account_detail.accountname, tbl_account.accountno AS chartaccountno, tbl_account.accountname AS chartaccountname');
        $this->db->from('tbl_account_receivable');
        $this->db->join('tbl_account_receivable_main', 'tbl_account_receivable_main.idtbl_account_receivable_main = tbl_account_receivable.tbl_account_receivable_main_idtbl_account_receivable_main', 'left');
        $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_account_receivable.tbl_account_detail_idtbl_account_detail', 'left');
        $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_account_receivable.tbl_account_idtbl_account', 'left');
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

                //Other account Transaction
                $this->db->select('`idtbl_account_receivable`, `tradate`, `batchno`, `tratype`, `amount`, `narration`, `tbl_master_idtbl_master`, `tbl_company_idtbl_company`, `tbl_company_branch_idtbl_company_branch`, `tbl_account_idtbl_account`, `tbl_account_detail_idtbl_account_detail`');
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
                    $chartaccount=$rowdetail->tbl_account_idtbl_account;
                    $company=$rowdetail->tbl_company_idtbl_company;
                    $branch=$rowdetail->tbl_company_branch_idtbl_company_branch;
                    $masterID=$rowdetail->tbl_master_idtbl_master;
                    $amount=$rowdetail->amount;
                    $narration=$rowdetail->narration;
                    $tratype=$rowdetail->tratype;
                    
                    if(!empty($detailaccount)){
                        $chartofaccountinfo=get_chart_account_acco_child_account($company, $branch, $detailaccount);
                        $chartofaccountID=$chartofaccountinfo->row(0)->idtbl_account;
                    }
                    else{
                        $chartofaccountID=$chartaccount;
                    }

                    $data = array(
                        'tradate'=> $tradate, 
                        'batchno'=> $batchno, 
                        'trabatchotherno'=> $segbatchno, 
                        'tratype'=> 'R', 
                        'seqno'=> $i, 
                        'crdr'=> $tratype, 
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
                        'crdr'=> $tratype, 
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
    // public function Allinsert(){
    //     $this->db->select('`invno`, `invdate`, `amount`, `tbl_customer_idtbl_customer`');
    //     $this->db->from('tbl_sales_info');
    //     $this->db->where('status', 1);

    //     $respond=$this->db->get();

    //     $userID=$_SESSION['userid'];
    //     $updatedatetime=date('Y-m-d H:i:s');
    //     $today=date('Y-m-d');

    //     $company=1;
    //     $branch=1;

    //     foreach($respond->result() as $rowdatalist){
    //         $customer=$rowdatalist->tbl_customer_idtbl_customer;
    //         $invoice=$rowdatalist->invno;
    //         $invoiceamount=$rowdatalist->amount;
            
    //         $prefix=rece_prefix($company, $branch);
    //         $masterdata=get_account_period($company, $branch);
    //         $batchno=tr_batch_num($prefix, $branch);
    //         $masterID=$masterdata->idtbl_master;

    //         $data = array(
    //             'tradate'=> $today, 
    //             'batchno'=> $batchno, 
    //             'customer'=> $customer, 
    //             'receiptno'=> $invoice, 
    //             'amount'=> $invoiceamount, 
    //             'poststatus'=> '0', 
    //             'status'=> '1', 
    //             'insertdatetime'=> $updatedatetime, 
    //             'tbl_user_idtbl_user'=> $userID,
    //             'tbl_company_idtbl_company'=> $company,
    //             'tbl_company_branch_idtbl_company_branch'=> $branch,
    //             'tbl_master_idtbl_master'=> $masterID
    //         );

    //         $this->db->insert('tbl_account_receivable_main', $data);

    //         $payablemainID=$this->db->insert_id();

    //         $datasub = array(
    //             'tradate'=> $today, 
    //             'batchno'=> $batchno, 
    //             'tratype'=> 'D', 
    //             'amount'=> $invoiceamount, 
    //             'narration'=> $invoice, 
    //             'status'=> '1', 
    //             'insertdatetime'=> $updatedatetime, 
    //             'tbl_user_idtbl_user'=> $userID,
    //             'tbl_master_idtbl_master'=> $masterID,
    //             'tbl_company_idtbl_company'=> $company,
    //             'tbl_company_branch_idtbl_company_branch'=> $branch,
    //             'tbl_account_receivable_main_idtbl_account_receivable_main'=> $payablemainID,
    //             'tbl_account_detail_idtbl_account_detail'=> '1'
    //         );

    //         $this->db->insert('tbl_account_receivable', $datasub);
    //     }     
    // }
}