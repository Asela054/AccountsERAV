<?php
class Currentperiodinfo extends CI_Model{
    public function Currentperiodinsertupdate(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];

        $finacialyear=$this->input->post('finacialyear');
        $finacialmonth=$this->input->post('finacialmonth');
        $companyid=$this->input->post('companyid');
        $branchid=$this->input->post('branchid');
		
		//20240314--
		$expired_account_info = get_account_period($companyid, $branchid);
        $expired_account_master_period = !empty($expired_account_info)?$expired_account_info->idtbl_master:'';
		//--20240314

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
		
		//20240314--
		$activated_account_master_period = $this->db->insert_id();
		//--20240314

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

        //20240314--
		//opening-balances-of-activated-account-master-period
		$sql_open_bal = "select DATE(NOW()) AS applydate, (drv_open.openbal+(IFNULL(drv_crdr.dr_accamount, 0)*IFNULL(NULLIF(tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype-2, 0), 1)+IFNULL(drv_crdr.cr_accamount, 0)*IFNULL(NULLIF(1-tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype, 0), 1))) AS openbal, 1 AS status, NOW() AS insertdatetime, ? AS tbl_user_idtbl_user, drv_open.tbl_account_idtbl_account, ? AS tbl_master_idtbl_master, ? AS tbl_company_idtbl_company, ? AS tbl_company_branch_idtbl_company_branch from (";
		$sql_open_bal .= "select tbl_account_idtbl_account, openbal from tbl_account_open_bal where tbl_master_idtbl_master=? and status=1";
		$sql_open_bal .= ") as drv_open ";
		$sql_open_bal .= "INNER JOIN tbl_account ON drv_open.tbl_account_idtbl_account=tbl_account.idtbl_account ";
		$sql_open_bal .= "INNER JOIN tbl_account_category ON tbl_account.tbl_account_category_idtbl_account_category=tbl_account_category.idtbl_account_category ";
		$sql_open_bal .= "LEFT OUTER JOIN (SELECT tbl_account_idtbl_account, SUM(accamount*(crdr='D')) AS dr_accamount, SUM(accamount*(crdr='C')) AS cr_accamount FROM tbl_account_transaction WHERE tbl_master_idtbl_master=? GROUP BY tbl_account_idtbl_account) AS drv_crdr ON drv_open.tbl_account_idtbl_account=drv_crdr.tbl_account_idtbl_account ";
		$sql_open_bal .= "WHERE tbl_account.status=1";
		//$sql_open_bal .= " ";
		//$sql_open_bal .= "AND tbl_account_category.code IN ('AS', 'LI')";
		
		$qry_open_bal = $this->db->query($sql_open_bal, array($userID, $activated_account_master_period, $companyid, $branchid, $expired_account_master_period, $expired_account_master_period));
		$rows_open_bal = $qry_open_bal->result_array();//array
		
        if(!empty($rows_open_bal)){
		    $this->db->insert_batch('tbl_account_open_bal', $rows_open_bal);
        }
		//--20240314
		
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