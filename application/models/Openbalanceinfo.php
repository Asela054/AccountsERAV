<?php
class Openbalanceinfo extends CI_Model{
    public function Openbalanceinsertupdate(){
        $userID=$_SESSION['userid'];

        $company=$this->input->post('company');
        $branch=$this->input->post('branch');
        $openbal=$this->input->post('openbal');
        $openbal = str_replace(',', '', $openbal);
        $creditdebitbal=$this->input->post('creditdebitbal');
        $accounttype=$this->input->post('accounttype');

        $chartofaccount=0;
        $chartofdetailaccount=0;
        if($accounttype==1){$chartofaccount=$this->input->post('chartofaccount');}
        else{$chartofdetailaccount=$this->input->post('chartofaccount');}

        $masterdata=get_account_period($company, $branch);
        $masterID=$masterdata->idtbl_master;

        $recordOption=$this->input->post('recordOption');
        if(!empty($this->input->post('recordID'))){$recordID=$this->input->post('recordID');}

        $updatedatetime=date('Y-m-d H:i:s');
        $today=date('Y-m-d');

        if($recordOption==1){
            $this->db->trans_begin();
            if($accounttype==2){
                $this->db->select('tbl_account_idtbl_account');
                $this->db->from('tbl_account_detail');
                $this->db->where('idtbl_account_detail', $chartofdetailaccount);
                $this->db->where('status', 1);

                $respondchart=$this->db->get();
                // print_r($this->db->last_query());

                $this->db->select('tbl_account_open_bal.openbal, tbl_account_open_bal.idtbl_account_open_bal');
                $this->db->from('tbl_account_detail');
                $this->db->join('tbl_account_open_bal', 'tbl_account_open_bal.tbl_account_idtbl_account=tbl_account_detail.tbl_account_idtbl_account', 'left');
                $this->db->where('tbl_account_detail.idtbl_account_detail', $chartofdetailaccount);
                $this->db->where('tbl_account_detail.status', 1);
                $this->db->where('tbl_account_open_bal.status', 1);
                $this->db->order_by('tbl_account_open_bal.idtbl_account_open_bal', 'DESC');
                $this->db->limit(1);

                $respondinfo=$this->db->get();
                // print_r($this->db->last_query());

                if($respondinfo->num_rows()>0){
                    $main_account_balance = $respondinfo->row(0)->openbal;
                    $main_creditdebit = $respondinfo->row(0)->creditdebit;
                    
                    $current_balance = ($main_creditdebit == 'D') ? $main_account_balance : -$main_account_balance;
                    
                    $amount = ($creditdebitbal == 'D') ? $openbal : -$openbal;
                    $new_balance = $current_balance + $amount;
                    
                    if($new_balance >= 0) {
                        $new_creditdebit = 'D';
                        $new_openbal = $new_balance;
                    } else {
                        $new_creditdebit = 'C';
                        $new_openbal = abs($new_balance);
                    }

                    $datachart = array(
                        'applydate' => $today, 
                        'openbal' => $new_openbal, 
                        'creditdebit' => $new_creditdebit, 
                        'updateuser' => $userID, 
                        'updatedatetime' => $updatedatetime,
                        'tbl_master_idtbl_master' => $masterID,
                        'tbl_company_idtbl_company' => $company,
                        'tbl_company_branch_idtbl_company_branch' => $branch
                    );
                    
                    $this->db->where('idtbl_account_open_bal', $respondinfo->row(0)->idtbl_account_open_bal);
                    $this->db->update('tbl_account_open_bal', $datachart);
                }
                else{
                    $newopenbal=$openbal;
                    $newcreditdebit=$creditdebitbal;

                    $datachart = array(
                        'applydate'=> $today, 
                        'openbal'=> $newopenbal, 
                        'creditdebit'=> $newcreditdebit, 
                        'status'=> '1', 
                        'insertdatetime'=> $updatedatetime, 
                        'tbl_user_idtbl_user'=> $userID,
                        'tbl_account_idtbl_account'=> $respondchart->row(0)->tbl_account_idtbl_account,
                        'tbl_account_detail_idtbl_account_detail'=> '0',
                        'tbl_master_idtbl_master'=> $masterID,
                        'tbl_company_idtbl_company'=> $company,
                        'tbl_company_branch_idtbl_company_branch'=> $branch
                    );
        
                    $this->db->insert('tbl_account_open_bal', $datachart);
                }
            }

            $data = array(
                'applydate'=> $today, 
                'openbal'=> $openbal, 
                'creditdebit'=> $creditdebitbal, 
                'status'=> '1', 
                'insertdatetime'=> $updatedatetime, 
                'tbl_user_idtbl_user'=> $userID,
                'tbl_account_idtbl_account'=> $chartofaccount,
                'tbl_account_detail_idtbl_account_detail'=> $chartofdetailaccount,
                'tbl_master_idtbl_master'=> $masterID,
                'tbl_company_idtbl_company'=> $company,
                'tbl_company_branch_idtbl_company_branch'=> $branch
            );
            $this->db->insert('tbl_account_open_bal', $data);

            if($accounttype==1):
                $this->db->select('specialcate');
                $this->db->from('tbl_account');
                $this->db->where('idtbl_account', $chartofaccount);
                $this->db->where('status', 1);

                $respondchartspecial=$this->db->get();

                if($respondchartspecial->row(0)->specialcate==36):
                    $datapetty = array(
                        'date'=> $today, 
                        'openbal'=> $openbal, 
                        'closebal'=> $openbal, 
                        'status'=> '1', 
                        'insertdatetime'=> $updatedatetime, 
                        'tbl_user_idtbl_user'=> $userID,
                        'tbl_account_idtbl_account'=> $chartofaccount,
                        'tbl_company_idtbl_company'=> $company,
                        'tbl_company_branch_idtbl_company_branch'=> $branch,
                        'tbl_master_idtbl_master'=> $masterID
                    );
                    $this->db->insert('tbl_pettycash_summary', $datapetty);
                endif;
            endif;            

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
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Openbalance');                
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
                redirect('Openbalance');
            }
        }
        else{
            $this->db->trans_begin();

            $data = array(
                'openbal'=> $openbal,
                'creditdebit'=> $creditdebitbal, 
                'updateuser'=> $userID, 
                'updatedatetime' => $updatedatetime,
                'tbl_account_idtbl_account'=> $chartofaccount,
                'tbl_company_idtbl_company'=> $company,
                'tbl_company_branch_idtbl_company_branch'=> $branch
            );

            $this->db->where('idtbl_account_open_bal', $recordID);
            $this->db->update('tbl_account_open_bal', $data);

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
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Openbalance');                
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
                redirect('Openbalance');
            }
        }
    }
    public function Openbalancestatus($x, $y){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];
        $recordID=$x;
        $type=$y;
        $updatedatetime=date('Y-m-d H:i:s');
        $today=date('Y-m-d');

        if($type==1){
            $data = array(
                'status' => '1',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_account_open_bal', $recordID);
            $this->db->update('tbl_account_open_bal', $data);

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
                redirect('Openbalance');                
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
                redirect('Openbalance');
            }
        }
        else if($type==2){
            $data = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_account_open_bal', $recordID);
            $this->db->update('tbl_account_open_bal', $data);

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
                redirect('Openbalance');                
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
                redirect('Openbalance');
            }
        }
        else if($type==3){
            $this->db->select('`openbal`, `creditdebit`, `tbl_account_detail_idtbl_account_detail`');
            $this->db->from('tbl_account_open_bal');
            $this->db->where('idtbl_account_open_bal', $recordID);
            $respond=$this->db->get();

            if($respond->row(0)->tbl_account_detail_idtbl_account_detail>0){
                $this->db->select('tbl_account_open_bal.openbal, tbl_account_open_bal.idtbl_account_open_bal');
                $this->db->from('tbl_account_detail');
                $this->db->join('tbl_account_open_bal', 'tbl_account_open_bal.tbl_account_idtbl_account=tbl_account_detail.tbl_account_idtbl_account', 'left');
                $this->db->where('tbl_account_detail.idtbl_account_detail', $respond->row(0)->tbl_account_detail_idtbl_account_detail);
                $this->db->where('tbl_account_detail.status', 1);
                $this->db->where('tbl_account_open_bal.status', 1);
                $this->db->order_by('tbl_account_open_bal.idtbl_account_open_bal', 'DESC');
                $this->db->limit(1);
                $respondinfo=$this->db->get();

                // if($respond->row(0)->creditdebit=='D'){
                //     $newopenbal=$respondinfo->row(0)->openbal-$respond->row(0)->openbal;
                //     if($newopenbal>0){$newcreditdebit='D';}
                //     else if($newopenbal<0){$newcreditdebit='C';}
                // }
                // else{
                //     $newopenbal=$respondinfo->row(0)->openbal+$respond->row(0)->openbal;
                //     if($newopenbal>0){$newcreditdebit='D';}
                //     else if($newopenbal<0){$newcreditdebit='C';}
                // }

                // $datachart = array(
                //     'applydate'=> $today, 
                //     'openbal'=> $newopenbal, 
                //     'creditdebit'=> $newcreditdebit, 
                //     'updateuser'=> $userID, 
                //     'updatedatetime' => $updatedatetime,
                // );
                // $this->db->where('idtbl_account_open_bal', $respondinfo->row(0)->idtbl_account_open_bal);
                // $this->db->update('tbl_account_open_bal', $datachart);

                if($respondinfo->num_rows() > 0){
                    // Convert to common format for calculation
                    $current_balance = ($respondinfo->row(0)->creditdebit == 'D') 
                        ? $respondinfo->row(0)->openbal 
                        : -$respondinfo->row(0)->openbal;
                        
                    $deleted_amount = ($respond->row(0)->creditdebit == 'D') 
                        ? $respond->row(0)->openbal 
                        : -$respond->row(0)->openbal;
                    
                    $new_balance = $current_balance - $deleted_amount;
                    
                    // Determine new credit/debit
                    if($new_balance >= 0){
                        $newcreditdebit = 'D';
                        $newopenbal = $new_balance;
                    } else {
                        $newcreditdebit = 'C';
                        $newopenbal = abs($new_balance);
                    }

                    $datachart = array(
                        'applydate' => $today, 
                        'openbal' => $newopenbal, 
                        'creditdebit' => $newcreditdebit, 
                        'updateuser' => $userID, 
                        'updatedatetime' => $updatedatetime,
                    );
                    $this->db->where('idtbl_account_open_bal', $respondinfo->row(0)->idtbl_account_open_bal);
                    $this->db->update('tbl_account_open_bal', $datachart);
                }
            }

            $data = array(
                'status' => '3',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_account_open_bal', $recordID);
            $this->db->update('tbl_account_open_bal', $data);

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
                redirect('Openbalance');                
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
                redirect('Openbalance');
            }
        }
    }
    public function Openbalanceedit(){
        $recordID=$this->input->post('recordID');

        $this->db->select('*');
        $this->db->from('tbl_account_open_bal');
        $this->db->where('idtbl_account_open_bal', $recordID);
        $this->db->where('status', 1);

        $respond=$this->db->get();

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_account_open_bal;
        $obj->openbal=$respond->row(0)->openbal;
        $obj->creditdebit=$respond->row(0)->creditdebit;
        $obj->account=$respond->row(0)->tbl_account_idtbl_account;
        $obj->company=$respond->row(0)->tbl_company_idtbl_company;
        $obj->branch=$respond->row(0)->tbl_company_branch_idtbl_company_branch;

        echo json_encode($obj);
    }
    public function Getaccountlist(){
        $companyid=$this->input->post('companyid');
        $branchid=$this->input->post('branchid');
        
        $this->db->where('tbl_account_allocation.companybank', $companyid);
        $this->db->where('tbl_account_allocation.branchcompanybank', $branchid);
        $this->db->where('tbl_account.status', 1);
        $this->db->where('tbl_account_allocation.status', 1);
        $this->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
		$this->db->select('`tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`');
		$this->db->from('tbl_account');
		$this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');

        $respond=$this->db->get();
		
		echo json_encode($respond->result()); 
    }
}