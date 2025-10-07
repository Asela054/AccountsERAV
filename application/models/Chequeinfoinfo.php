<?php
class Chequeinfoinfo extends CI_Model{
    public function Chequeinfoinsertupdate(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];

        $bank=$this->input->post('bank');
        $bankbranch=$this->input->post('bankbranch');
        $startno=$this->input->post('startno');
        $endno=$this->input->post('endno');
        $chartaccount=$this->input->post('chartaccount');

        $recordOption=$this->input->post('recordOption');
        if(!empty($this->input->post('recordID'))){$recordID=$this->input->post('recordID');}

        $updatedatetime=date('Y-m-d H:i:s');

        if($recordOption==1){
            $data = array(
                'startno'=> $startno, 
                'endno'=> $endno, 
                'status'=> '1', 
                'insertdatetime'=> $updatedatetime, 
                'tbl_user_idtbl_user'=> $userID,
                'tbl_account_idtbl_account'=> $chartaccount,
                'tbl_bank_idtbl_bank'=> $bank,
                'tbl_bank_branch_idtbl_bank_branch'=> $bankbranch
            );

            $this->db->insert('tbl_cheque_info', $data);

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
                redirect('Chequeinfo');                
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
                redirect('Chequeinfo');
            }
        }
        else{
            $data = array(
                'startno'=> $startno, 
                'endno'=> $endno, 
                'updateuser'=> $userID, 
                'updatedatetime' => $updatedatetime,
                'tbl_account_idtbl_account'=> $chartaccount,
                'tbl_bank_idtbl_bank'=> $bank,
                'tbl_bank_branch_idtbl_bank_branch'=> $bankbranch
            );

            $this->db->where('idtbl_cheque_info', $recordID);
            $this->db->update('tbl_cheque_info', $data);

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
                redirect('Chequeinfo');                
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
                redirect('Chequeinfo');
            }
        }
    }
    public function Chequeinfostatus($x, $y){
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

            $this->db->where('idtbl_cheque_info', $recordID);
            $this->db->update('tbl_cheque_info', $data);

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
                redirect('Chequeinfo');                
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
                redirect('Chequeinfo');
            }
        }
        else if($type==2){
            $data = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_cheque_info', $recordID);
            $this->db->update('tbl_cheque_info', $data);

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
                redirect('Chequeinfo');                
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
                redirect('Chequeinfo');
            }
        }
        else if($type==3){
            $data = array(
                'status' => '3',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_cheque_info', $recordID);
            $this->db->update('tbl_cheque_info', $data);

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
                redirect('Chequeinfo');                
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
                redirect('Chequeinfo');
            }
        }
    }
    public function Chequeinfoedit(){
        $recordID=$this->input->post('recordID');

        $this->db->select('*');
        $this->db->from('tbl_cheque_info');
        $this->db->where('idtbl_cheque_info', $recordID);
        $this->db->where('status', 1);

        $respond=$this->db->get();

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_cheque_info;
        $obj->startno=$respond->row(0)->startno;
        $obj->endno=$respond->row(0)->endno;
        $obj->chartaccount=$respond->row(0)->tbl_account_idtbl_account;
        $obj->bank=$respond->row(0)->tbl_bank_idtbl_bank;
        $obj->branch=$respond->row(0)->tbl_bank_branch_idtbl_bank_branch;

        echo json_encode($obj);
    }
    public function Getbanklist(){
        $this->db->select('`idtbl_bank`, `bankname`, `code`');
        $this->db->from('tbl_bank');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
    public function Getbankbranchaccbank(){
        $recordID=$this->input->post('recordID');

        $this->db->select('`idtbl_bank_branch`, `branchname`, `code`');
        $this->db->from('tbl_bank_branch');
        $this->db->where('tbl_bank_idtbl_bank', $recordID);
        $this->db->where('status', 1);

        $respond=$this->db->get();

        echo json_encode($respond->result());
    }
    public function Getbankchartofaccount(){
        $companyid=$_SESSION['companyid'];
        $branchid=$_SESSION['branchid'];

        $this->db->select('`idtbl_account`, `accountname`, `accountno`');
        $this->db->from('tbl_account');
        $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');
        $this->db->where('tbl_account.status', 1);
        // $this->db->where('tbl_account.tbl_account_type_idtbl_account_type', 1);
        $this->db->where('tbl_account_allocation.companybank', $companyid);
        $this->db->where('tbl_account_allocation.branchcompanybank', $branchid);

        return $respond=$this->db->get();
    }
}