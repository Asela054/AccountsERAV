<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Pettycashreimburse extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Pettycashreimburseinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['companylist']=get_company_list();
		$this->load->view('pettycashreimburse', $result);
	}
    public function Pettycashreimburseinsertupdate(){
        $result=$this->Pettycashreimburseinfo->Pettycashreimburseinsertupdate();
	}
    public function Getbranchaccocompany(){
        $recordID=$this->input->post('recordID');
        $result=get_company_branch_list($recordID);
	}
    public function Getaccountlist(){
        $companyid=$this->input->post('companyid');
        $branchid=$this->input->post('branchid');
        $searchTerm=$this->input->post('searchTerm');

        $result=get_chart_acount_select2($searchTerm, $companyid, $branchid);
	}
    public function Getpostpettycashlist(){
        $result=$this->Pettycashreimburseinfo->Getpostpettycashlist();
	}
    public function Getreimbursementinfo(){
        $result=$this->Pettycashreimburseinfo->Getreimbursementinfo();
	}
    public function Approvereimbursement(){
        $result=$this->Pettycashreimburseinfo->Approvereimbursement();
	}
    public function Pettycashreimbursechequecreate(){
        $result=$this->Pettycashreimburseinfo->Pettycashreimbursechequecreate();
	}
}