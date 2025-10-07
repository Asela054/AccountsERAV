<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Openbalance extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Openbalanceinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
        $result['companylist']=get_company_list();
		$this->load->view('openbalance', $result);
	}
    public function Openbalanceinsertupdate(){
        $result=$this->Openbalanceinfo->Openbalanceinsertupdate();
	}
    public function Openbalancestatus($x, $y){
        $result=$this->Openbalanceinfo->Openbalancestatus($x, $y);
	}
    public function Openbalanceedit(){
        $result=$this->Openbalanceinfo->Openbalanceedit();
	}
    public function Getbranchaccocompany(){
        $recordID=$this->input->post('recordID');
        $result=get_company_branch_list($recordID);
	}
    public function Getaccountlist(){
        // $result=$this->Openbalanceinfo->Getaccountlist();
        $searchTerm=$this->input->post('searchTerm');
        $companyid=$this->input->post('companyid');
        $branchid=$this->input->post('branchid');
        
        $result=get_all_accounts($searchTerm, $companyid, $branchid);
	}
}