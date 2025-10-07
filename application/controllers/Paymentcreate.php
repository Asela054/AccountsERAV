<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Paymentcreate extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Paymentcreateinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['companylist']=get_company_list();
		// $result['supplierlist']=get_supplier_search_list();
		$this->load->view('paymentcreate', $result);
	}
    public function Paymentcreateinsertupdate(){
        $result=$this->Paymentcreateinfo->Paymentcreateinsertupdate();
	}
    public function Paymentcreatestatus($x, $y){
        $result=$this->Paymentcreateinfo->Paymentcreatestatus($x, $y);
	}
    public function Paymentcreateedit(){
        $result=$this->Paymentcreateinfo->Paymentcreateedit();
	}
    public function Getbranchaccocompany(){
        $recordID=$this->input->post('recordID');
        $result=get_company_branch_list($recordID);
	}
    public function Getaccountlist(){
        $searchTerm=$this->input->post('searchTerm');
        $companyid=$this->input->post('companyid');
        $branchid=$this->input->post('branchid');

        // $result=get_child_account_list($companyid, $branchid);
        $result=get_all_accounts($searchTerm, $companyid, $branchid);
	}
    public function Getviewprintinfo(){
        $result=$this->Paymentcreateinfo->Getviewprintinfo();
	}
    public function Getsupplierlist(){
        $searchTerm=$this->input->post('searchTerm');
        $result=get_supplier_search_list($searchTerm);
	}
}