<?php
class Chartofaccountinfo extends CI_Model{
    public function Chartofaccountinsertupdate(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];
        $companyid=$_SESSION['companyid'];
        $branchid=$_SESSION['branchid'];

        // $accounttype=$this->input->post('accounttype');
        $accounttype=0;
        $accountcategory=$this->input->post('accountcategory');
        $subaccountcategory=$this->input->post('subaccountcategory');
        $nestaccountcategory=$this->input->post('nestaccountcategory');
        $chartaccountcode=$this->input->post('chartaccountcode');
        $chartaccount=$this->input->post('chartaccount');
        $accountcatecode=$this->input->post('accountcatecode');
        $detailaccountstatus=$this->input->post('detailaccount');

        $recordOption=$this->input->post('recordOption');
        if(!empty($this->input->post('recordID'))){$recordID=$this->input->post('recordID');}

        $updatedatetime=date('Y-m-d H:i:s');

        $chartofaccount='000000'.$chartaccountcode;
        $chartofaccountno=substr($chartofaccount, -6);
        $chartofaccountno=$accountcatecode.$chartofaccountno;

        if($recordOption==1){
            $data = array(
                'code'=> $chartaccountcode, 
                'accountno'=> $chartofaccountno, 
                'accountname'=> $chartaccount, 
                'status'=> '1', 
                'insertdatetime'=> $updatedatetime, 
                'tbl_user_idtbl_user'=> $userID,
                'tbl_account_category_idtbl_account_category'=> $accountcategory,
                'tbl_account_subcategory_idtbl_account_subcategory'=> $subaccountcategory,
                'tbl_account_nestcategory_idtbl_account_nestcategory'=> $nestaccountcategory,
                'tbl_account_type_idtbl_account_type'=> $accounttype
            );

            $this->db->insert('tbl_account', $data);

            $chartofaccountID=$this->db->insert_id();

            $dataallocate = array(
                'companybank'=> $companyid, 
                'branchcompanybank'=> $branchid, 
                'status'=> '1', 
                'insertdatetime'=> $updatedatetime, 
                'tbl_user_idtbl_user'=> $userID,
                'tbl_account_idtbl_account'=> $chartofaccountID
            );

            $this->db->insert('tbl_account_allocation', $dataallocate);

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

                $chartofaccountOption='<option value="'.$chartofaccountID.'" data-accno="'.$chartofaccountno.'">'.$chartofaccountno.' - '.$chartaccount.'</option>';
                
                if($detailaccountstatus==1){
                    $this->session->set_flashdata('chartaccountoption', $chartofaccountOption);
                    redirect('Chartofaccountdetail');
                }
                else{
                    redirect('Chartofaccount');                
                }
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
                redirect('Chartofaccount');
            }
        }
        else{
            $data = array(
                'code'=> $chartaccountcode, 
                'accountno'=> $chartofaccountno, 
                'accountname'=> $chartaccount, 
                'updateuser'=> $userID, 
                'updatedatetime' => $updatedatetime,
                'tbl_account_category_idtbl_account_category'=> $accountcategory,
                'tbl_account_subcategory_idtbl_account_subcategory'=> $subaccountcategory,
                'tbl_account_nestcategory_idtbl_account_nestcategory'=> $nestaccountcategory,
                'tbl_account_type_idtbl_account_type'=> $accounttype
            );

            $this->db->where('idtbl_account', $recordID);
            $this->db->update('tbl_account', $data);

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
                redirect('Chartofaccount');                
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
                redirect('Chartofaccount');
            }
        }
    }
    public function Chartofaccountstatus($x, $y){
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

            $this->db->where('idtbl_account', $recordID);
            $this->db->update('tbl_account', $data);

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
                redirect('Chartofaccount');                
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
                redirect('Chartofaccount');
            }
        }
        else if($type==2){
            $data = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_account', $recordID);
            $this->db->update('tbl_account', $data);

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
                redirect('Chartofaccount');                
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
                redirect('Chartofaccount');
            }
        }
        else if($type==3){
            // Check if account ID is referenced in any table
            $this->db->select('COUNT(*) as count');
            $this->db->from('tbl_account_payable');
            $this->db->where('tbl_account_idtbl_account', $recordID);
            $count1 = $this->db->count_all_results();
            
            $this->db->select('COUNT(*) as count');
            $this->db->from('tbl_account_transaction');
            $this->db->where('tbl_account_idtbl_account', $recordID);
            $count2 = $this->db->count_all_results();
            
            $this->db->select('COUNT(*) as count');
            $this->db->from('tbl_account_open_bal');
            $this->db->where('tbl_account_idtbl_account', $recordID);
            $count3 = $this->db->count_all_results();
            
            $this->db->select('COUNT(*) as count');
            $this->db->from('tbl_account_receivable');
            $this->db->where('tbl_account_idtbl_account', $recordID);
            $count4 = $this->db->count_all_results();

            $this->db->select('COUNT(*) as count');
            $this->db->from('tbl_pettycash');
            $this->db->where('tbl_account_idtbl_account', $recordID);
            $this->db->or_where('tbl_account_idtbl_account_exp', $recordID);
            $count5 = $this->db->count_all_results();
            
            $total = $count1 + $count2 + $count3 + $count4 + $count5;

            if($total == 0){
                $data = array(
                    'status' => '3',
                    'updateuser'=> $userID, 
                    'updatedatetime'=> $updatedatetime
                );

                $this->db->where('idtbl_account', $recordID);
                $this->db->update('tbl_account', $data);

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
                    redirect('Chartofaccount');                
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
                    redirect('Chartofaccount');
                }
            } else {
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Chartofaccount');
            }
        }
    }
    public function Chartofaccountedit(){
        $recordID=$this->input->post('recordID');

        $this->db->select('*');
        $this->db->from('tbl_account');
        $this->db->where('idtbl_account', $recordID);
        $this->db->where('status', 1);

        $respond=$this->db->get();

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_account;
        $obj->code=$respond->row(0)->code;
        $obj->accountname=$respond->row(0)->accountname;
        $obj->accountcategory=$respond->row(0)->tbl_account_category_idtbl_account_category;
        $obj->accountsubcate=$respond->row(0)->tbl_account_subcategory_idtbl_account_subcategory;
        $obj->accountnestcate=$respond->row(0)->tbl_account_nestcategory_idtbl_account_nestcategory;
        $obj->accounttype=$respond->row(0)->tbl_account_type_idtbl_account_type;

        echo json_encode($obj);
    }
    public function Getaccounttype(){
        $this->db->select('`idtbl_account_type`, `accounttype`');
        $this->db->from('tbl_account_type');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
    public function Getaccountcategory(){
        $this->db->select('`idtbl_account_category`, `category`, `code`');
        $this->db->from('tbl_account_category');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
    public function Getsubcateaccoaccountcate(){
        $recordID=$this->input->post('recordID');

        $this->db->select('`idtbl_account_subcategory`, `subcategory`');
        $this->db->from('tbl_account_subcategory');
        $this->db->where('tbl_account_category_idtbl_account_category', $recordID);
        $this->db->where('status', 1);

        $respond=$this->db->get();

        echo json_encode($respond->result());
    }
    public function Getsnestcateaccoaccountsubcate(){
        $recordID=$this->input->post('recordID');
        $categoryID=$this->input->post('category');

        $this->db->select('`idtbl_account_nestcategory`, `nestcategory`');
        $this->db->from('tbl_account_nestcategory');
        $this->db->where('tbl_account_category_idtbl_account_category', $categoryID);
        $this->db->where('tbl_account_subcategory_idtbl_account_subcategory', $recordID);
        $this->db->where('status', 1);

        $respond=$this->db->get();

        echo json_encode($respond->result());
    }
    public function Getaccountspecialcategory(){
        $this->db->select('`idtbl_account_special_category`, `specialcategory`');
        $this->db->from('tbl_account_special_category');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
    public function Chartofaccountspecialcateupdate(){
        $userID=$_SESSION['userid'];
        $companyID = $_SESSION['companyid'];
        $branchID = $_SESSION['branchid']; 
        $accountspecialcategory=$this->input->post('accountspecialcategory');
        $chartaccountspecialid=$this->input->post('chartaccountspecialid');
        $updatedatetime=date('Y-m-d H:i:s');

        $this->db->select('COUNT(idtbl_account) AS `count`');
        $this->db->from('tbl_account');
        $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');
        $this->db->where('tbl_account.status', 1);
        $this->db->where('tbl_account_allocation.companybank', $companyID);
        $this->db->where('tbl_account_allocation.branchcompanybank', $branchID);
        $this->db->where('tbl_account.specialcate', $accountspecialcategory);
        $respond=$this->db->get();

        if($respond->row(0)->count==0){
            $this->db->trans_begin();

            $data = array(
                'specialcate'=> $accountspecialcategory, 
                'updateuser'=> $userID, 
                'updatedatetime' => $updatedatetime
            );

            $this->db->where('idtbl_account', $chartaccountspecialid);
            $this->db->update('tbl_account', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-save';
                $actionObj->title='';
                $actionObj->message='Record set special category successfully.';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='primary';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Chartofaccount');                
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
                redirect('Chartofaccount');
            }
        }
        else{
            $actionObj=new stdClass();
            $actionObj->icon='fas fa-exclamation-triangle';
            $actionObj->title='';
            $actionObj->message='Record Error, You cannot apply to this special category because you have already set it for another account.';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='danger';

            $actionJSON=json_encode($actionObj);
            
            $this->session->set_flashdata('msg', $actionJSON);
            redirect('Chartofaccount');
        }
    }
    public function Getaccountspecialcategorydata(){
        $companyID = $_SESSION['companyid'];
        $branchID = $_SESSION['branchid']; 

        $this->db->select('tbl_account.idtbl_account, tbl_account.accountno, tbl_account.accountname, tbl_account_special_category.specialcategory');
        $this->db->from('tbl_account');
        $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');
        $this->db->join('tbl_account_special_category', 'tbl_account_special_category.idtbl_account_special_category = tbl_account.specialcate', 'left');
        $this->db->where('tbl_account.status', 1);
        $this->db->where('tbl_account.specialcate>', 0);
        $this->db->where('tbl_account_allocation.companybank', $companyID);
        $this->db->where('tbl_account_allocation.branchcompanybank', $branchID);
        return $respond=$this->db->get();
    }
    public function Chartofaccountspecialcategorystatus($x, $y){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];
        $recordID=$x;
        $type=$y;
        $updatedatetime=date('Y-m-d H:i:s');

        if($type==3){
            $data = array(
                'specialcate' => '0',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_account', $recordID);
            $this->db->update('tbl_account', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-check';
                $actionObj->title='';
                $actionObj->message='Record Special Category Remove Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='success';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Chartofaccount');                
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
                redirect('Chartofaccount');
            }
        }
    }
    public function Getnextaccountno(){
        $recordID=$this->input->post('recordID');

        $this->db->select('IFNULL((MAX(`tbl_account`.`code`) + 1), 1) AS `nextaccouontno`');
        $this->db->from('tbl_account');
        $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');
        $this->db->where('tbl_account_allocation.companybank', $_SESSION['companyid']);
        $this->db->where('tbl_account_allocation.branchcompanybank', $_SESSION['branchid']);
        $this->db->where('tbl_account.status', 1);
        $this->db->where('tbl_account_allocation.status', 1);
        $this->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
        $this->db->where('tbl_account.tbl_account_category_idtbl_account_category', $recordID);
        $respond=$this->db->get();

        echo $respond->row(0)->nextaccouontno;
    }
    public function Checkaccountnoalready(){
        $accountcode=$this->input->post('accountcode');
        $recordID=$this->input->post('recordID');
        $recordOption=$this->input->post('recordOption');

        $companyid=$_SESSION['companyid'];
        $branchid=$_SESSION['branchid'];

        if($recordOption==1){
            $this->db->select('COUNT(*) as `count`');
            $this->db->from('tbl_account');
            $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');
            $this->db->where('tbl_account.code', $accountcode);
            $this->db->where('tbl_account_allocation.companybank', $companyid);
            $this->db->where('tbl_account_allocation.branchcompanybank', $branchid);
            $this->db->where('tbl_account.status', 1);

            $respond=$this->db->get();

            if($respond->row(0)->count>0){
                $obj=new stdClass();
                $obj->status=1;
                $obj->message="You can't enter this account code, because this account number already exists. Please click the refresh button. then save again. Thank you.";
                echo json_encode($obj);
            } else {
                $obj = new stdClass();
                $obj->status = 0;
                $obj->message = "";
                echo json_encode($obj);
            }
        }
        else{
            // Check if account ID is referenced in any table
            $this->db->select('COUNT(*) as count');
            $this->db->from('tbl_account_payable');
            $this->db->where('tbl_account_idtbl_account', $recordID);
            $this->db->where('tbl_company_idtbl_company', $companyid);
            $this->db->where('tbl_company_branch_idtbl_company_branch', $branchid);
            $count1 = $this->db->count_all_results();
            
            $this->db->select('COUNT(*) as count');
            $this->db->from('tbl_account_transaction');
            $this->db->where('tbl_account_idtbl_account', $recordID);
            $this->db->where('tbl_company_idtbl_company', $companyid);
            $this->db->where('tbl_company_branch_idtbl_company_branch', $branchid);
            $count2 = $this->db->count_all_results();
            
            $this->db->select('COUNT(*) as count');
            $this->db->from('tbl_account_open_bal');
            $this->db->where('tbl_account_idtbl_account', $recordID);
            $this->db->where('tbl_company_idtbl_company', $companyid);
            $this->db->where('tbl_company_branch_idtbl_company_branch', $branchid);
            $count3 = $this->db->count_all_results();
            
            $this->db->select('COUNT(*) as count');
            $this->db->from('tbl_account_receivable');
            $this->db->where('tbl_account_idtbl_account', $recordID);
            $this->db->where('tbl_company_idtbl_company', $companyid);
            $this->db->where('tbl_company_branch_idtbl_company_branch', $branchid);
            $count4 = $this->db->count_all_results();

            $this->db->select('COUNT(*) as count');
            $this->db->from('tbl_pettycash');
            $this->db->where('tbl_account_idtbl_account', $recordID);
            $this->db->or_where('tbl_account_idtbl_account_exp', $recordID);
            $this->db->where('tbl_company_idtbl_company', $companyid);
            $this->db->where('tbl_company_branch_idtbl_company_branch', $branchid);
            $count5 = $this->db->count_all_results();
            
            $total = $count1 + $count2 + $count3 + $count4 + $count5;
            
            if($total > 0) {
                $obj = new stdClass();
                $obj->status = 1;
                $obj->message = "You can't delete or modify this account because it has $total reference(s) in other tables.";
                echo json_encode($obj);
            } else {
                $obj = new stdClass();
                $obj->status = 0;
                $obj->message = "";
                echo json_encode($obj);
            }
        }
    }
}