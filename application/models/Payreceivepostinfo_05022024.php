<?php
class Payreceivepostinfo extends CI_Model{
    public function Getpayreceivelist(){
        $updatedatetime=date('Y-m-d H:i:s');
        $periodmonth=$this->input->post('periodmonth');
        $filtortype=$this->input->post('filtortype');
        $company=$this->input->post('company');
        $branch=$this->input->post('branch');

        $year=date("Y", strtotime($periodmonth));
        $month=date("n", strtotime($periodmonth));

        $listarray=array();

        if($filtortype==1){
            $this->db->select('tbl_account_payable_main.idtbl_account_payable_main, tbl_account_payable_main.tradate, tbl_account_payable_main.batchno, tbl_account_payable_main.amount, tbl_account_payable_main.poststatus, tbl_company.company, tbl_company_branch.branch');
            $this->db->from('tbl_account_payable_main');
            $this->db->join('tbl_company', 'tbl_company.idtbl_company = tbl_account_payable_main.tbl_company_idtbl_company', 'left');
            $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_account_payable_main.tbl_company_branch_idtbl_company_branch', 'left');
            $this->db->where('MONTH(tbl_account_payable_main.tradate)', $month);
            $this->db->where('YEAR(tbl_account_payable_main.tradate)', $year);
            $this->db->where('tbl_account_payable_main.tbl_company_idtbl_company', $company);
            $this->db->where('tbl_account_payable_main.tbl_company_branch_idtbl_company_branch', $branch);

            $respond=$this->db->get();

            foreach($respond->result() as $rowrespond){
                $recordID=$rowrespond->idtbl_account_payable_main;

                $data = array(
                    'postviewtime'=> $updatedatetime
                );
        
                $this->db->where('idtbl_account_payable_main', $recordID);
                $this->db->where('poststatus', 0);
                $this->db->update('tbl_account_payable_main', $data);

                $this->db->select('tbl_account_payable.idtbl_account_payable as `detailid`,tbl_account_payable.narration, tbl_account_payable.amount, tbl_account_detail.accountno, tbl_account_detail.accountname');
                $this->db->from('tbl_account_payable');
                $this->db->join('tbl_account_payable_main', 'tbl_account_payable_main.idtbl_account_payable_main = tbl_account_payable.tbl_account_payable_main_idtbl_account_payable_main', 'left');
                $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_account_payable.tbl_account_detail_idtbl_account_detail', 'left');
                $this->db->where('tbl_account_payable_main.idtbl_account_payable_main', $recordID);
                // $this->db->where('tbl_account_payable.status', 1);

                $respondpayinfo=$this->db->get();

                $obj=new stdClass();
                $obj->id=$rowrespond->idtbl_account_payable_main;
                $obj->tradate=$rowrespond->tradate;
                $obj->batchno=$rowrespond->batchno;
                $obj->amount=$rowrespond->amount;
                $obj->poststatus=$rowrespond->poststatus;
                $obj->company=$rowrespond->company;
                $obj->branch=$rowrespond->branch;
                $obj->detailinfo=$respondpayinfo->result();

                array_push($listarray, $obj);
            }
        }
        else if($filtortype==2){
            $this->db->select('tbl_account_receivable_main.idtbl_account_receivable_main, tbl_account_receivable_main.tradate, tbl_account_receivable_main.batchno, tbl_account_receivable_main.amount, tbl_account_receivable_main.poststatus, tbl_company.company, tbl_company_branch.branch');
            $this->db->from('tbl_account_receivable_main');
            $this->db->join('tbl_company', 'tbl_company.idtbl_company = tbl_account_receivable_main.tbl_company_idtbl_company', 'left');
            $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_account_receivable_main.tbl_company_branch_idtbl_company_branch', 'left');
            $this->db->where('MONTH(tbl_account_receivable_main.tradate)', $month);
            $this->db->where('YEAR(tbl_account_receivable_main.tradate)', $year);
            $this->db->where('tbl_account_receivable_main.tbl_company_idtbl_company', $company);
            $this->db->where('tbl_account_receivable_main.tbl_company_branch_idtbl_company_branch', $branch);

            $respond=$this->db->get();

            foreach($respond->result() as $rowrespond){
                $recordID=$rowrespond->idtbl_account_receivable_main;

                $data = array(
                    'postviewtime'=> $updatedatetime
                );
        
                $this->db->where('idtbl_account_receivable_main', $recordID);
                $this->db->where('poststatus', 0);
                $this->db->update('tbl_account_receivable_main', $data);

                $this->db->select('tbl_account_receivable.idtbl_account_receivable as `detailid`, tbl_account_receivable.narration, tbl_account_receivable.amount, tbl_account_detail.accountno, tbl_account_detail.accountname');
                $this->db->from('tbl_account_receivable');
                $this->db->join('tbl_account_receivable_main', 'tbl_account_receivable_main.idtbl_account_receivable_main = tbl_account_receivable.tbl_account_receivable_main_idtbl_account_receivable_main', 'left');
                $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_account_receivable.tbl_account_detail_idtbl_account_detail', 'left');
                $this->db->where('tbl_account_receivable_main.idtbl_account_receivable_main', $recordID);
                // $this->db->where('tbl_account_payable.status', 1);

                $respondpayinfo=$this->db->get();

                $obj=new stdClass();
                $obj->id=$rowrespond->idtbl_account_receivable_main;
                $obj->tradate=$rowrespond->tradate;
                $obj->batchno=$rowrespond->batchno;
                $obj->amount=$rowrespond->amount;
                $obj->poststatus=$rowrespond->poststatus;
                $obj->company=$rowrespond->company;
                $obj->branch=$rowrespond->branch;
                $obj->detailinfo=$respondpayinfo->result();

                array_push($listarray, $obj);
            }
        }
        else if($filtortype==3){
            $this->db->select('tbl_pettycash.idtbl_pettycash, tbl_pettycash.date, tbl_pettycash.pettycashcode, tbl_pettycash.amount, tbl_pettycash.desc, tbl_pettycash.poststatus, tbl_company.company, tbl_company_branch.branch, tbl_account_detail.accountno, tbl_account_detail.accountname, tbl_pettycash.tbl_company_idtbl_company');
            $this->db->from('tbl_pettycash');
            $this->db->join('tbl_company', 'tbl_company.idtbl_company = tbl_pettycash.tbl_company_idtbl_company', 'left');
            $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_pettycash.tbl_company_branch_idtbl_company_branch', 'left');
            $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_pettycash.tbl_account_detail_idtbl_account_detail', 'left');
            $this->db->where('MONTH(tbl_pettycash.date)', $month);
            $this->db->where('YEAR(tbl_pettycash.date)', $year);
            $this->db->where('tbl_pettycash.tbl_company_idtbl_company', $company);
            $this->db->where('tbl_pettycash.tbl_company_branch_idtbl_company_branch', $branch);

            $respond=$this->db->get();

            foreach($respond->result() as $rowrespond){
                $data = array(
                    'postviewtime'=> $updatedatetime
                );
        
                $this->db->where('idtbl_pettycash', $rowrespond->idtbl_pettycash);
                $this->db->where('poststatus', 0);
                $this->db->update('tbl_pettycash', $data);

                $obj=new stdClass();
                $obj->id=$rowrespond->idtbl_pettycash;
                $obj->tradate=$rowrespond->date;
                $obj->batchno=$rowrespond->pettycashcode;
                $obj->amount=$rowrespond->amount;
                $obj->poststatus=$rowrespond->poststatus;
                $obj->company=$rowrespond->company;
                $obj->branch=$rowrespond->branch;
                $obj->accountno=$rowrespond->accountno;
                $obj->accountname=$rowrespond->accountname;
                $obj->desc=$rowrespond->desc;

                array_push($listarray, $obj);
            }
        }

        $html='';
        if($filtortype==3){
            foreach($listarray AS $rowlist){
                $html.='
                <tr class="'; if($rowlist->poststatus==1){$html.='table-success';} $html.='">
                    <td width="5%" class="text-center">';
                    if($rowlist->poststatus==1){$html.='<i class="fas fa-check-circle"></i>';}
                    else{
                        $html.='<div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input checkboxclick" id="customCheck'.$rowlist->id.'" data-record="'.$rowlist->id.'">
                            <label class="custom-control-label m-0" for="customCheck'.$rowlist->id.'"></label>
                        </div>';
                    }
                    $html.='</td>
                    <td>'.$rowlist->company.'</td>
                    <td>'.$rowlist->branch.'</td>
                    <td>'.$rowlist->tradate.'</td>
                    <td>'.$rowlist->batchno.'</td>
                    <td>&nbsp;</td>
                    <th class="text-right tratotal">'.number_format($rowlist->amount, 2).'</th>
                    <td class="d-none">&nbsp;</td>
                </tr>
                <tr class="'; if($rowlist->poststatus==1){$html.='table-success';} $html.='">
                    <td width="5%" class="text-center">';
                    if($rowlist->poststatus==0){
                        $html.='<div class="custom-control custom-checkbox d-none">
                            <input type="checkbox" class="custom-control-input subcheck'.$rowlist->id.'" id="customCheck'.$rowlist->id.'">
                            <label class="custom-control-label m-0" for="customCheck'.$rowlist->id.'"></label>
                        </div>';
                    }
                    $html.='</td>
                    <td colspan="2">'.$rowlist->accountno.'</td>
                    <td colspan="2">'.$rowlist->desc.'</td>
                    <td class="text-right segtotal">'.number_format($rowlist->amount, 2).'</td>
                    <td>&nbsp;</td>
                    <th class="d-none recordid">'.$rowlist->id.'</th>                
                </tr>
                ';
            }
        }
        else{
            $companyID=0;
            foreach($listarray AS $rowlist){
                $html.='
                <tr class="'; if($rowlist->poststatus==1){$html.='table-success';} $html.='">
                    <td width="5%" class="text-center">';
                    if($rowlist->poststatus==1){$html.='<i class="fas fa-check-circle"></i>';}
                    else{
                        $html.='<div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input checkboxclick" id="customCheck'.$rowlist->id.'" data-record="'.$rowlist->id.'">
                            <label class="custom-control-label m-0" for="customCheck'.$rowlist->id.'"></label>
                        </div>';
                    }
                    $html.='</td>
                    <td>'.$rowlist->company.'</td>
                    <td>'.$rowlist->branch.'</td>
                    <td>'.$rowlist->tradate.'</td>
                    <td>'.$rowlist->batchno.'</td>
                    <td>&nbsp;</td>
                    <th class="text-right tratotal">'.number_format($rowlist->amount, 2).'</th>
                    <th class="d-none recordid">'.$rowlist->id.'</th>
                </tr>
                ';
                foreach($rowlist->detailinfo AS $rowdetaillist){
                    $html.='
                    <tr class="'; if($rowlist->poststatus==1){$html.='table-success';} $html.='">
                        <td width="5%" class="text-center">';
                        if($rowlist->poststatus==0){
                            $html.='<div class="custom-control custom-checkbox d-none">
                                <input type="checkbox" class="custom-control-input subcheck'.$rowlist->id.'" id="customCheck'.$rowdetaillist->detailid.'">
                                <label class="custom-control-label m-0" for="customCheck'.$rowdetaillist->detailid.'"></label>
                            </div>';
                        }
                        $html.='</td>
                        <td colspan="2">'.$rowdetaillist->accountno.'</td>
                        <td colspan="2">'.$rowdetaillist->narration.'</td>
                        <td class="text-right segtotal">'.number_format($rowdetaillist->amount, 2).'</td>
                        <td>&nbsp;</td>
                        <td class="d-none">&nbsp;</td>
                    </tr>
                    ';
                }
            }
        }

        echo $html;
    }
    public function Payreceivepostposting(){
        $this->db->trans_begin();
        $updatedatetime=date('Y-m-d H:i:s');
        $today=date('Y-m-d');
        $userID=$_SESSION['userid'];

        $filtortype=$this->input->post('filtortype');
        $datalist=json_decode($this->input->post('tabledata'));

        if($filtortype==1){
            foreach($datalist as $rowdatalist){
                $recordID=$rowdatalist->recordid;

                $i=0;

                $this->db->select('tradate, batchno, invoiceno, amount, poststatus, status, editstatus, postviewtime, updatedatetime, tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch, tbl_master_idtbl_master, supplier');
                $this->db->from('tbl_account_payable_main');
                $this->db->where('idtbl_account_payable_main', $recordID);
                $this->db->where('status', 1);

                $respond=$this->db->get();

                if($respond->row(0)->poststatus==0 && $respond->row(0)->status==1 && $respond->row(0)->editstatus==0){
                    if($respond->row(0)->postviewtime>$respond->row(0)->updatedatetime){
                        $data = array(
                            'poststatus'=> '1',
                            'postuser'=> $userID,
                            'postviewtime'=> NULL
                        );
                
                        $this->db->where('idtbl_account_payable_main', $recordID);
                        $this->db->update('tbl_account_payable_main', $data);

                        //Expences info update
                        $dataexpences = array(
                            'poststatus'=> '1',
                            'updateuser'=> $userID,
                            'updatedatetime'=> $updatedatetime
                        );
                
                        $this->db->where('grnno', $respond->row(0)->invoiceno);
                        $this->db->where('tbl_supplier_idtbl_supplier', $respond->row(0)->supplier);
                        $this->db->update('tbl_expence_info', $dataexpences);

                        $i=1;
                        //Creditor account Transaction
                        $prefix=trans_prefix($respond->row(0)->tbl_company_idtbl_company, $respond->row(0)->tbl_company_branch_idtbl_company_branch);
                        $batchno=tr_batch_num($prefix, $respond->row(0)->tbl_company_branch_idtbl_company_branch);

                        //Get Creditor Account
                        $this->db->where('tbl_account_allocation.companybank', $respond->row(0)->tbl_company_idtbl_company);
                        $this->db->where('tbl_account_allocation.branchcompanybank', $respond->row(0)->tbl_company_branch_idtbl_company_branch);
                        $this->db->where('tbl_account.tbl_account_type_idtbl_account_type', 2);
                        $this->db->where('tbl_account.specialcate', 1);
                        $this->db->where('tbl_account.status', 1);
                        $this->db->where('tbl_account_allocation.status', 1);
                        $this->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
                        $this->db->select('`tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`');
                        $this->db->from('tbl_account');
                        $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');

                        $respondcreditor=$this->db->get();

                        $datacredit = array(
                            'tradate'=> $respond->row(0)->tradate, 
                            'batchno'=> $batchno, 
                            'trabatchotherno'=> $respond->row(0)->batchno, 
                            'tratype'=> 'P', 
                            'seqno'=> $i, 
                            'crdr'=> 'C', 
                            'accamount'=> $respond->row(0)->amount, 
                            'narration'=> $respond->row(0)->invoiceno, 
                            'totamount'=> $respond->row(0)->amount, 
                            'status'=> '1', 
                            'insertdatetime'=> $updatedatetime, 
                            'tbl_user_idtbl_user'=> $userID,
                            'tbl_account_idtbl_account'=> $respondcreditor->row(0)->idtbl_account,
                            'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
                            'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
                            'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
                        );
                        $this->db->insert('tbl_account_transaction', $datacredit);
                
                        $datacreditfull = array(
                            'tradate'=> $respond->row(0)->tradate, 
                            'batchno'=> $batchno, 
                            'tratype'=> 'P', 
                            'crdr'=> 'C', 
                            'accamount'=> $respond->row(0)->amount, 
                            'narration'=> $respond->row(0)->invoiceno, 
                            'totamount'=> $respond->row(0)->amount, 
                            'status'=> '1', 
                            'insertdatetime'=> $updatedatetime, 
                            'tbl_user_idtbl_user'=> $userID,
                            'tbl_account_idtbl_account'=> $respondcreditor->row(0)->idtbl_account,
                            'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
                            'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
                            'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
                        );
                        $this->db->insert('tbl_account_transaction_full', $datacreditfull);

                        //Other account Transaction
                        $this->db->select('`idtbl_account_payable`, `tradate`, `batchno`, `amount`, `narration`, `tbl_master_idtbl_master`, `tbl_company_idtbl_company`, `tbl_company_branch_idtbl_company_branch`, `tbl_account_detail_idtbl_account_detail`');
                        $this->db->from('tbl_account_payable');
                        $this->db->where('tbl_account_payable_main_idtbl_account_payable_main', $recordID);
                        $this->db->where('status', 1);

                        $responddetail=$this->db->get();

                        foreach($responddetail->result() AS $rowdetail){
                            $i++;

                            $paydetailID=$rowdetail->idtbl_account_payable;
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
                                'tratype'=> 'P', 
                                'seqno'=> $i, 
                                'crdr'=> 'D', 
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
                                'tratype'=> 'P', 
                                'crdr'=> 'D', 
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
                    
                            $this->db->where('idtbl_account_payable', $paydetailID);
                            $this->db->update('tbl_account_payable', $datadetail);
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

                            break;
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

                        break;
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

                    break;
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

                    break;
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

                    break;
                }
            }

            $data = array(
                'postviewtime'=> NULL
            );
    
            $this->db->where('poststatus', 0);
            $this->db->update('tbl_account_payable_main', $data);
        }
        else if($filtortype==2){
            foreach($datalist as $rowdatalist){
                $recordID=$rowdatalist->recordid;

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
                            'crdr'=> 'C', 
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
                            'crdr'=> 'C', 
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

                            break;
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

                        break;
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

                    break;
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

                    break;
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

                    break;
                }
            }

            $data = array(
                'postviewtime'=> NULL
            );
    
            $this->db->where('poststatus', 0);
            $this->db->update('tbl_account_receivable_main', $data);
        }
        else if($filtortype==3){
            foreach($datalist as $rowdatalist){
                $recordID=$rowdatalist->recordid;

                $i=1;

                $this->db->select('`date`, `pettycashcode`, `desc`, `amount`, `poststatus`, `postuser`, `postviewtime`, `status`, `updatedatetime`, `tbl_company_idtbl_company`, `tbl_company_branch_idtbl_company_branch`, `tbl_account_idtbl_account`, `tbl_account_detail_idtbl_account_detail`, `tbl_master_idtbl_master`');
                $this->db->from('tbl_pettycash');
                $this->db->where('idtbl_pettycash', $recordID);
                $this->db->where('status', 1);

                $respond=$this->db->get(); 

                if($respond->row(0)->poststatus==0 && $respond->row(0)->status==1){
                    if($respond->row(0)->postviewtime>$respond->row(0)->updatedatetime){
                        $data = array(
                            'poststatus'=> '1',
                            'postuser'=> $userID,
                            'postviewtime'=> NULL
                        );
                
                        $this->db->where('idtbl_pettycash', $recordID);
                        $this->db->update('tbl_pettycash', $data);

                        $company=$respond->row(0)->tbl_company_idtbl_company;
                        $branch=$respond->row(0)->tbl_company_branch_idtbl_company_branch;
                        $accountid=$respond->row(0)->tbl_account_idtbl_account;

                        //Pety Cash Account Credit
                        $prefix=trans_prefix($company, $branch);
                        $batchno=tr_batch_num($prefix, $branch);

                        $datacredit = array(
                            'tradate'=> $respond->row(0)->date, 
                            'batchno'=> $batchno, 
                            'trabatchotherno'=> $respond->row(0)->pettycashcode, 
                            'tratype'=> 'P', 
                            'seqno'=> $i, 
                            'crdr'=> 'C', 
                            'accamount'=> $respond->row(0)->amount, 
                            'narration'=> $respond->row(0)->desc, 
                            'totamount'=> $respond->row(0)->amount, 
                            'status'=> '1', 
                            'insertdatetime'=> $updatedatetime, 
                            'tbl_user_idtbl_user'=> $userID,
                            'tbl_account_idtbl_account'=> $respond->row(0)->tbl_account_idtbl_account,
                            'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
                            'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
                            'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
                        );
                        $this->db->insert('tbl_account_transaction', $datacredit);

                        $datacreditfull = array(
                            'tradate'=> $respond->row(0)->date, 
                            'batchno'=> $batchno, 
                            'tratype'=> 'P', 
                            'crdr'=> 'C', 
                            'accamount'=> $respond->row(0)->amount, 
                            'narration'=> $respond->row(0)->desc, 
                            'totamount'=> $respond->row(0)->amount, 
                            'status'=> '1', 
                            'insertdatetime'=> $updatedatetime, 
                            'tbl_user_idtbl_user'=> $userID,
                            'tbl_account_idtbl_account'=> $respond->row(0)->tbl_account_idtbl_account,
                            'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
                            'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
                            'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
                        );
                        $this->db->insert('tbl_account_transaction_full', $datacreditfull);

                        //Detail Account Debit
                        $i++;

                        $this->db->select('`tbl_account_idtbl_account`');
                        $this->db->from('tbl_account_detail');
                        $this->db->where('idtbl_account_detail', $respond->row(0)->tbl_account_detail_idtbl_account_detail);
                        $this->db->where('status', 1);
                        $responddetailchart=$this->db->get();

                        $datadebit = array(
                            'tradate'=> $respond->row(0)->date, 
                            'batchno'=> $batchno, 
                            'trabatchotherno'=> $respond->row(0)->pettycashcode, 
                            'tratype'=> 'P', 
                            'seqno'=> $i, 
                            'crdr'=> 'D', 
                            'accamount'=> $respond->row(0)->amount, 
                            'narration'=> $respond->row(0)->desc, 
                            'totamount'=> $respond->row(0)->amount, 
                            'status'=> '1', 
                            'insertdatetime'=> $updatedatetime, 
                            'tbl_user_idtbl_user'=> $userID,
                            'tbl_account_idtbl_account'=> $responddetailchart->row(0)->tbl_account_idtbl_account,
                            'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
                            'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
                            'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
                        );
                        $this->db->insert('tbl_account_transaction', $datadebit);

                        $datadebitfull = array(
                            'tradate'=> $respond->row(0)->date, 
                            'batchno'=> $batchno, 
                            'tratype'=> 'P', 
                            'crdr'=> 'D', 
                            'accamount'=> $respond->row(0)->amount, 
                            'narration'=> $respond->row(0)->desc, 
                            'totamount'=> $respond->row(0)->amount, 
                            'status'=> '1', 
                            'insertdatetime'=> $updatedatetime, 
                            'tbl_user_idtbl_user'=> $userID,
                            'tbl_account_idtbl_account'=> $responddetailchart->row(0)->tbl_account_idtbl_account,
                            'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
                            'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
                            'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
                        );
                        $this->db->insert('tbl_account_transaction_full', $datadebitfull);

                        //Petty Cash Summery
                        $this->db->select('`closebal`');
                        $this->db->from('tbl_pettycash_summary');
                        $this->db->where('tbl_account_idtbl_account', $accountid);
                        $this->db->where('tbl_company_idtbl_company', $company);
                        $this->db->where('tbl_company_branch_idtbl_company_branch', $branch);
                        $this->db->where('status', 1);
                        $this->db->order_by('idtbl_pettycash_summary', 'DESC');
                        $this->db->limit(1);

                        $respondpettysummery=$this->db->get();

                        $newclosebalance=$respondpettysummery->row(0)->closebal-$respond->row(0)->amount;

                        $datapetty = array(
                            'date'=> $today, 
                            'openbal'=> $respondpettysummery->row(0)->closebal, 
                            'postbal'=> $respond->row(0)->amount, 
                            'reimbal'=> '0', 
                            'closebal'=> $newclosebalance, 
                            'status'=> 1, 
                            'insertdatetime'=> $updatedatetime,
                            'tbl_user_idtbl_user'=> $userID, 
                            'tbl_account_idtbl_account'=> $accountid, 
                            'tbl_company_idtbl_company'=> $company, 
                            'tbl_company_branch_idtbl_company_branch'=> $branch, 
                            'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master
                        );
                        $this->db->insert('tbl_pettycash_summary', $datapetty);

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
                        $actionObj->message='Record Error, Please check this record for information. Because this record was updated before you posted.';
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

                    break;
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

                    break;
                }
            }

            $data = array(
                'postviewtime'=> NULL
            );
            
            $this->db->where('poststatus', 0);
            $this->db->update('tbl_pettycash', $data);
        }
    }
}