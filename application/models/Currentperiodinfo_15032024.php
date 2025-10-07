<?php
class Currentperiodinfo extends CI_Model{
    public function Currentperiodinsertupdate(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];

        $finacialyear=$this->input->post('finacialyear');
        $finacialmonth=$this->input->post('finacialmonth');
        $companyid=$this->input->post('companyid');
        $branchid=$this->input->post('branchid');

        $updatedatetime=date('Y-m-d H:i:s');

        $data = array(
            'status'=> '1', 
            'insertdatetime'=> $updatedatetime, 
            'tbl_user_idtbl_user'=> $userID,
            'tbl_company_idtbl_company'=> $companyid,
            'tbl_company_branch_idtbl_company_branch'=> $branchid,
            'tbl_finacial_year_idtbl_finacial_year'=> $finacialyear,
            'tbl_finacial_month_idtbl_finacial_month'=> $finacialmonth
        );

        $this->db->insert('tbl_master', $data);

        $datayear = array(
            'actstatus'=> '1',
            'updateuser'=> $userID, 
            'updatedatetime' => $updatedatetime
        );

        $this->db->where('idtbl_finacial_year', $finacialyear);
        $this->db->update('tbl_finacial_year', $datayear);

        $datamonth = array(
            'activestatus'=> '1',
            'updateuser'=> $userID, 
            'updatedatetime' => $updatedatetime
        );

        $this->db->where('tbl_finacial_year_idtbl_finacial_year', $finacialyear);
        $this->db->where('idtbl_finacial_month', $finacialmonth);
        $this->db->update('tbl_finacial_month', $datamonth);

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
            redirect('Currentperiod');                
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
            redirect('Currentperiod');
        }
    }
    public function Getfinancialyear(){
        $this->db->select('`idtbl_finacial_year`, `desc`');
        $this->db->from('tbl_finacial_year');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
    public function Getmonthlistaccoyear(){
        $recordID=$this->input->post('recordID');

        $this->db->select('`idtbl_finacial_month`, `monthname`');
        $this->db->from('tbl_finacial_month');
        $this->db->where('tbl_finacial_year_idtbl_finacial_year', $recordID);
        $this->db->where('status', 1);

        $respond=$this->db->get();

        echo json_encode($respond->result());
    }
}