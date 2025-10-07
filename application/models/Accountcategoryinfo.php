<?php
class Accountcategoryinfo extends CI_Model{
    public function Accountcategoryinsertupdate(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];

        $accountcatecode=$this->input->post('accountcatecode');
        $accountcategory=$this->input->post('accountcategory');
        $finacialtype=$this->input->post('finacialtype');
        $transactiontype=$this->input->post('transactiontype');

        $recordOption=$this->input->post('recordOption');
        if(!empty($this->input->post('recordID'))){$recordID=$this->input->post('recordID');}

        $updatedatetime=date('Y-m-d H:i:s');

        if($recordOption==1){
            $data = array(
                'code'=> $accountcatecode, 
                'category'=> $accountcategory, 
                'status'=> '1', 
                'insertdatetime'=> $updatedatetime, 
                'tbl_user_idtbl_user'=> $userID,
                'tbl_account_finacialtype_idtbl_account_finacialtype'=> $finacialtype,
                'tbl_account_transactiontype_idtbl_account_transactiontype'=> $transactiontype
            );

            $this->db->insert('tbl_account_category', $data);

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
                redirect('Accountcategory');                
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
                redirect('Accountcategory');
            }
        }
        else{
            $data = array(
                'code'=> $accountcatecode, 
                'category'=> $accountcategory, 
                'updateuser'=> $userID, 
                'updatedatetime' => $updatedatetime,
                'tbl_account_finacialtype_idtbl_account_finacialtype'=> $finacialtype,
                'tbl_account_transactiontype_idtbl_account_transactiontype'=> $transactiontype
            );

            $this->db->where('idtbl_account_category', $recordID);
            $this->db->update('tbl_account_category', $data);

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
                redirect('Accountcategory');                
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
                redirect('Accountcategory');
            }
        }
    }
    public function Accountcategorystatus($x, $y){
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

            $this->db->where('idtbl_account_category', $recordID);
            $this->db->update('tbl_account_category', $data);

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
                redirect('Accountcategory');                
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
                redirect('Accountcategory');
            }
        }
        else if($type==2){
            $data = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_account_category', $recordID);
            $this->db->update('tbl_account_category', $data);

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
                redirect('Accountcategory');                
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
                redirect('Accountcategory');
            }
        }
        else if($type==3){
            $data = array(
                'status' => '3',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_account_category', $recordID);
            $this->db->update('tbl_account_category', $data);

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
                redirect('Accountcategory');                
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
                redirect('Accountcategory');
            }
        }
    }
    public function Accountcategoryedit(){
        $recordID=$this->input->post('recordID');

        $this->db->select('*');
        $this->db->from('tbl_account_category');
        $this->db->where('idtbl_account_category', $recordID);
        $this->db->where('status', 1);

        $respond=$this->db->get();

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_account_category;
        $obj->code=$respond->row(0)->code;
        $obj->category=$respond->row(0)->category;
        $obj->finacialtype=$respond->row(0)->tbl_account_finacialtype_idtbl_account_finacialtype;
        $obj->transactiontype=$respond->row(0)->tbl_account_transactiontype_idtbl_account_transactiontype;

        echo json_encode($obj);
    }
    public function Getfinacialtype(){
        $this->db->select('`idtbl_account_finacialtype`, `finacialtype`');
        $this->db->from('tbl_account_finacialtype');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
    public function Gettransactiontype(){
        $this->db->select('`idtbl_account_transactiontype`, `transactiontype`');
        $this->db->from('tbl_account_transactiontype');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
}