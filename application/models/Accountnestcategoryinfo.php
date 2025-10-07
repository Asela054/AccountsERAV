<?php
class Accountnestcategoryinfo extends CI_Model{
    public function Accountnestcategoryinsertupdate(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];

        $accountcategory=$this->input->post('accountcategory');
        $subaccountcategory=$this->input->post('subaccountcategory');
        $nestaccountcategory=$this->input->post('nestaccountcategory');

        $recordOption=$this->input->post('recordOption');
        if(!empty($this->input->post('recordID'))){$recordID=$this->input->post('recordID');}

        $updatedatetime=date('Y-m-d H:i:s');

        if($recordOption==1){
            $data = array(
                'nestcategory'=> $nestaccountcategory, 
                'status'=> '1', 
                'insertdatetime'=> $updatedatetime, 
                'tbl_user_idtbl_user'=> $userID,
                'tbl_account_category_idtbl_account_category'=> $accountcategory,
                'tbl_account_subcategory_idtbl_account_subcategory'=> $subaccountcategory
            );

            $this->db->insert('tbl_account_nestcategory', $data);

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
                redirect('Accountnestcategory');                
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
                redirect('Accountnestcategory');
            }
        }
        else{
            $data = array( 
                'nestcategory'=> $nestaccountcategory, 
                'updateuser'=> $userID, 
                'updatedatetime' => $updatedatetime,
                'tbl_account_category_idtbl_account_category'=> $accountcategory,
                'tbl_account_subcategory_idtbl_account_subcategory'=> $subaccountcategory
            );

            $this->db->where('idtbl_account_nestcategory', $recordID);
            $this->db->update('tbl_account_nestcategory', $data);

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
                redirect('Accountnestcategory');                
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
                redirect('Accountnestcategory');
            }
        }
    }
    public function Accountnestcategorystatus($x, $y){
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

            $this->db->where('idtbl_account_nestcategory', $recordID);
            $this->db->update('tbl_account_nestcategory', $data);

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
                redirect('Accountnestcategory');                
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
                redirect('Accountnestcategory');
            }
        }
        else if($type==2){
            $data = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_account_nestcategory', $recordID);
            $this->db->update('tbl_account_nestcategory', $data);

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
                redirect('Accountnestcategory');                
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
                redirect('Accountnestcategory');
            }
        }
        else if($type==3){
            $data = array(
                'status' => '3',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_account_nestcategory', $recordID);
            $this->db->update('tbl_account_nestcategory', $data);

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
                redirect('Accountnestcategory');                
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
                redirect('Accountnestcategory');
            }
        }
    }
    public function Accountnestcategoryedit(){
        $recordID=$this->input->post('recordID');

        $this->db->select('*');
        $this->db->from('tbl_account_nestcategory');
        $this->db->where('idtbl_account_nestcategory', $recordID);
        $this->db->where('status', 1);

        $respond=$this->db->get();

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_account_nestcategory;
        $obj->nestcategory=$respond->row(0)->nestcategory;
        $obj->accountcategory=$respond->row(0)->tbl_account_category_idtbl_account_category;
        $obj->accountsubcategory=$respond->row(0)->tbl_account_subcategory_idtbl_account_subcategory;

        echo json_encode($obj);
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
}