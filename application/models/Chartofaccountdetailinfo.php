<?php
class Chartofaccountdetailinfo extends CI_Model{
    public function Chartofaccountdetailinsertupdate(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];
        $companyid=$_SESSION['companyid'];
        $branchid=$_SESSION['branchid'];

        $chartofaccount=$this->input->post('chartofaccount');
        $detailaccountcode=$this->input->post('detailaccountcode');
        $detailaccount=$this->input->post('detailaccount');
        $accountcatecode=$this->input->post('accountcatecode');

        $recordOption=$this->input->post('recordOption');
        if(!empty($this->input->post('recordID'))){$recordID=$this->input->post('recordID');}

        $updatedatetime=date('Y-m-d H:i:s');

        $detailaccountno='0000'.$detailaccountcode;
        $detailaccountno=substr($detailaccountno, -4);
        $detailaccountno=$accountcatecode.$detailaccountno;

        if($recordOption==1){
            $data = array(
                'code'=> $detailaccountcode, 
                'accountno'=> $detailaccountno, 
                'accountname'=> $detailaccount, 
                'status'=> '1', 
                'insertdatetime'=> $updatedatetime, 
                'tbl_user_idtbl_user'=> $userID,
                'tbl_account_idtbl_account'=> $chartofaccount
            );

            $this->db->insert('tbl_account_detail', $data);

            $chartofdetailaccountID=$this->db->insert_id();

            $datadetail = array(
                'companybank'=> $companyid, 
                'branchcompanybank'=> $branchid, 
                'status'=> '1', 
                'insertdatetime'=> $updatedatetime, 
                'tbl_user_idtbl_user'=> $userID,
                'tbl_account_detail_idtbl_account_detail'=> $chartofdetailaccountID
            );
            $this->db->insert('tbl_account_allocation', $datadetail);

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
                redirect('Chartofaccountdetail');                
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
                redirect('Chartofaccountdetail');
            }
        }
        else{
            $data = array(
                'code'=> $detailaccountcode, 
                'accountno'=> $detailaccountno, 
                'accountname'=> $detailaccount, 
                'updateuser'=> $userID, 
                'updatedatetime' => $updatedatetime,
                'tbl_account_idtbl_account'=> $chartofaccount
            );

            $this->db->where('idtbl_account_detail', $recordID);
            $this->db->update('tbl_account_detail', $data);

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
                redirect('Chartofaccountdetail');                
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
                redirect('Chartofaccountdetail');
            }
        }
    }
    public function Chartofaccountdetailstatus($x, $y){
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

            $this->db->where('idtbl_account_detail', $recordID);
            $this->db->update('tbl_account_detail', $data);

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
                redirect('Chartofaccountdetail');                
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
                redirect('Chartofaccountdetail');
            }
        }
        else if($type==2){
            $data = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_account_detail', $recordID);
            $this->db->update('tbl_account_detail', $data);

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
                redirect('Chartofaccountdetail');                
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
                redirect('Chartofaccountdetail');
            }
        }
        else if($type==3){
            // Check if account ID is referenced in any table
            $this->db->select('COUNT(*) as count');
            $this->db->from('tbl_account_payable');
            $this->db->where('tbl_account_detail_idtbl_account_detail', $recordID);
            $count1 = $this->db->count_all_results();
            
            $this->db->select('COUNT(*) as count');
            $this->db->from('tbl_account_open_bal');
            $this->db->where('tbl_account_detail_idtbl_account_detail', $recordID);
            $count2 = $this->db->count_all_results();
            
            $this->db->select('COUNT(*) as count');
            $this->db->from('tbl_account_receivable');
            $this->db->where('tbl_account_detail_idtbl_account_detail', $recordID);
            $count3 = $this->db->count_all_results();

            $this->db->select('COUNT(*) as count');
            $this->db->from('tbl_pettycash');
            $this->db->where('tbl_account_detail_idtbl_account_detail_exp', $recordID);
            $count4 = $this->db->count_all_results();
            
            $total = $count1 + $count2 + $count3 + $count4;

            if($total == 0) {
                $data = array(
                    'status' => '3',
                    'updateuser'=> $userID, 
                    'updatedatetime'=> $updatedatetime
                );

                $this->db->where('idtbl_account_detail', $recordID);
                $this->db->update('tbl_account_detail', $data);

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
                    redirect('Chartofaccountdetail');                
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
                    redirect('Chartofaccountdetail');
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
                redirect('Chartofaccountdetail');
            }
        }
    }
    public function Chartofaccountdetailedit(){
        $recordID=$this->input->post('recordID');

        $this->db->select('tbl_account_detail.*, tbl_account.accountno AS chartofaccountno, tbl_account.accountname AS chartaccountname');
        $this->db->from('tbl_account_detail');
        $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_account_detail.tbl_account_idtbl_account', 'left');
        $this->db->where('tbl_account_detail.idtbl_account_detail', $recordID);
        $this->db->where('tbl_account_detail.status', 1);

        $respond=$this->db->get();

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_account_detail;
        $obj->code=$respond->row(0)->code;
        $obj->accountname=$respond->row(0)->accountname;
        $obj->chartaccountid=$respond->row(0)->tbl_account_idtbl_account;
        $obj->chartaccountno=$respond->row(0)->chartofaccountno;
        $obj->chartaccountname=$respond->row(0)->chartaccountname;

        echo json_encode($obj);
    }
    public function Getchartofaccount(){
        $this->db->select('`idtbl_account`, `accountno`, `accountname`');
        $this->db->from('tbl_account');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
    public function Getnextdetailaccountno(){
        $recordID=$this->input->post('recordID');

        $this->db->select('IFNULL((MAX(`tbl_account_detail`.`code`) + 1), 1) AS `nextaccouontno`');
        $this->db->from('tbl_account_detail');
        $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_detail_idtbl_account_detail = tbl_account_detail.idtbl_account_detail', 'left');
        $this->db->where('tbl_account_allocation.companybank', $_SESSION['companyid']);
        $this->db->where('tbl_account_allocation.branchcompanybank', $_SESSION['branchid']);
        $this->db->where('tbl_account_detail.status', 1);
        $this->db->where('tbl_account_allocation.status', 1);
        $this->db->where('tbl_account_allocation.tbl_account_detail_idtbl_account_detail is NOT NULL', NULL, FALSE);
        $this->db->where('tbl_account_detail.tbl_account_idtbl_account', $recordID);
        $respond=$this->db->get();

        echo $respond->row(0)->nextaccouontno;
    }
    public function Checkaccountnoalready(){
        $accountcode=$this->input->post('accountcode');
        $recordID=$this->input->post('recordID');
        $chartAccountID=$this->input->post('chartAccountID');
        $recordOption=$this->input->post('recordOption');

        $companyid=$_SESSION['companyid'];
        $branchid=$_SESSION['branchid'];

        if($recordOption==1){
            $this->db->select('COUNT(*) as `count`');
            $this->db->from('tbl_account_detail');
            $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_detail_idtbl_account_detail = tbl_account_detail.idtbl_account_detail', 'left');
            $this->db->where('tbl_account_detail.code', $accountcode);
            $this->db->where('tbl_account_detail.tbl_account_idtbl_account', $chartAccountID);
            $this->db->where('tbl_account_allocation.companybank', $companyid);
            $this->db->where('tbl_account_allocation.branchcompanybank', $branchid);
            $this->db->where('tbl_account_detail.status', 1);

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
            $this->db->where('tbl_account_detail_idtbl_account_detail', $recordID);
            $this->db->where('tbl_company_idtbl_company', $companyid);
            $this->db->where('tbl_company_branch_idtbl_company_branch', $branchid);
            $count1 = $this->db->count_all_results();
            
            $this->db->select('COUNT(*) as count');
            $this->db->from('tbl_account_open_bal');
            $this->db->where('tbl_account_detail_idtbl_account_detail', $recordID);
            $this->db->where('tbl_company_idtbl_company', $companyid);
            $this->db->where('tbl_company_branch_idtbl_company_branch', $branchid);
            $count2 = $this->db->count_all_results();
            
            $this->db->select('COUNT(*) as count');
            $this->db->from('tbl_account_receivable');
            $this->db->where('tbl_account_detail_idtbl_account_detail', $recordID);
            $this->db->where('tbl_company_idtbl_company', $companyid);
            $this->db->where('tbl_company_branch_idtbl_company_branch', $branchid);
            $count3 = $this->db->count_all_results();

            $this->db->select('COUNT(*) as count');
            $this->db->from('tbl_pettycash');
            $this->db->where('tbl_account_detail_idtbl_account_detail_exp', $recordID);
            $this->db->where('tbl_company_idtbl_company', $companyid);
            $this->db->where('tbl_company_branch_idtbl_company_branch', $branchid);
            $count4 = $this->db->count_all_results();
            
            $total = $count1 + $count2 + $count3 + $count4;
            
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
    public function Chartofaccountdetailspecialcateupdate(){
        $userID=$_SESSION['userid'];
        $companyID = $_SESSION['companyid'];
        $branchID = $_SESSION['branchid']; 
        $accountspecialcategory=$this->input->post('accountspecialcategory');
        $chartaccountspecialid=$this->input->post('chartaccountspecialid');
        $typecategory=$this->input->post('typecategory');
        $updatedatetime=date('Y-m-d H:i:s');

        $this->db->select('COUNT(idtbl_account_detail) AS `count`');
        $this->db->from('tbl_account_detail');
        $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_detail_idtbl_account_detail = tbl_account_detail.idtbl_account_detail', 'left');
        $this->db->where('tbl_account_detail.status', 1);
        $this->db->where('tbl_account_allocation.companybank', $companyID);
        $this->db->where('tbl_account_allocation.branchcompanybank', $branchID);
        $this->db->where('tbl_account_detail.special_cate_detail', $typecategory);
        $this->db->where('tbl_account_detail.special_cate_sub', $accountspecialcategory);
        $respond=$this->db->get();

        if($respond->row(0)->count==0){
            $this->db->trans_begin();

            $data = array(
                'special_cate_detail'=> $typecategory, 
                'special_cate_sub'=> $accountspecialcategory, 
                'updateuser'=> $userID, 
                'updatedatetime' => $updatedatetime
            );

            $this->db->where('idtbl_account_detail', $chartaccountspecialid);
            $this->db->update('tbl_account_detail', $data);

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
                redirect('Chartofaccountdetail');                
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
                redirect('Chartofaccountdetail');
            }
        }
        else{
            $actionObj=new stdClass();
            $actionObj->icon='fas fa-exclamation-triangle';
            $actionObj->title='';
            $actionObj->message='Record Error, You cannot apply to this special category because you have already set it for another detail account.';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='danger';

            $actionJSON=json_encode($actionObj);
            
            $this->session->set_flashdata('msg', $actionJSON);
            redirect('Chartofaccountdetail');
        }
    }
    public function Getaccountspecialcategorydata(){
        $companyID = $_SESSION['companyid'];
        $branchID = $_SESSION['branchid']; 

        $this->db->select('tbl_account_detail.idtbl_account_detail, tbl_account_detail.accountno, tbl_account_detail.accountname, tbl_account_detail.special_cate_detail, tbl_account_detail.special_cate_sub');
        $this->db->from('tbl_account_detail');
        $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_detail_idtbl_account_detail = tbl_account_detail.idtbl_account_detail', 'left');
        $this->db->join('tbl_material_group', 'tbl_account_detail.special_cate_sub = tbl_material_group.idtbl_material_group AND tbl_account_detail.special_cate_detail = 1', 'left');
        $this->db->join('tbl_material_type', 'tbl_account_detail.special_cate_sub = tbl_material_type.idtbl_material_type AND tbl_account_detail.special_cate_detail = 2', 'left');
        $this->db->select('CASE 
            WHEN tbl_account_detail.special_cate_detail = 1 THEN tbl_material_group.group
            WHEN tbl_account_detail.special_cate_detail = 2 THEN tbl_material_type.paper
            ELSE NULL
        END AS special_item', FALSE);
        $this->db->where('tbl_account_detail.status', 1);
        $this->db->where('tbl_account_allocation.companybank', $companyID);
        $this->db->where('tbl_account_allocation.branchcompanybank', $branchID);
        $this->db->where('tbl_account_detail.special_cate_detail>', 0);
        $this->db->where('tbl_account_detail.special_cate_sub>', 0);

        return $respond = $this->db->get();
    }
    public function Chartofaccountdetailspecialcategorystatus($x, $y){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];
        $recordID=$x;
        $type=$y;
        $updatedatetime=date('Y-m-d H:i:s');

        if($type==3){
            $data = array(
                'special_cate_detail' => '0',
                'special_cate_sub' => '0',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_account_detail', $recordID);
            $this->db->update('tbl_account_detail', $data);

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
                redirect('Chartofaccountdetail');                
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
                redirect('Chartofaccountdetail');
            }
        }
    }
}