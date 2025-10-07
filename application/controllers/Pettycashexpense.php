<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Pettycashexpense extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Pettycashexpenseinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
        $result['companylist']=get_company_list();
		$this->load->view('pettycashexpense', $result);
	}
    public function Pettycashexpenseinsertupdate(){
        $result=$this->Pettycashexpenseinfo->Pettycashexpenseinsertupdate();
	}
    public function Pettycashexpensestatus($x, $y){
        $result=$this->Pettycashexpenseinfo->Pettycashexpensestatus($x, $y);
	}
    public function Pettycashexpenseedit(){
        $result=$this->Pettycashexpenseinfo->Pettycashexpenseedit();
	}
    public function Pettycashexpenseposting(){
        $result=$this->Pettycashexpenseinfo->Pettycashexpenseposting();
	}
    public function Getbranchaccocompany(){
        $recordID=$this->input->post('recordID');
        $result=get_company_branch_list($recordID);
	}
    public function Getaccountlist(){
        $companyid=$this->input->post('companyid');
        $branchid=$this->input->post('branchid');

        $result=get_petty_account_list($companyid, $branchid);
	}
    public function Getdetailaccountlist(){
        $searchTerm=$this->input->post('searchTerm');
        $companyid=$this->input->post('companyid');
        $branchid=$this->input->post('branchid');

        // $result=get_child_account_list($companyid, $branchid);
        $result=get_all_accounts($searchTerm, $companyid, $branchid);
	}
    public function Getaccountbalance(){
        $result=$this->Pettycashexpenseinfo->Getaccountbalance();
	}
    public function Getviewpostinfo(){
        $result=$this->Pettycashexpenseinfo->Getviewpostinfo();
	}
}