<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Payablesegregation extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Payablesegregationinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['companylist']=get_company_list();
		$this->load->view('payablesegregation', $result);
	}
    public function Payablesegregationinsertupdate(){
        $result=$this->Payablesegregationinfo->Payablesegregationinsertupdate();
	}
    public function Payablesegregationstatus($x, $y){
        $result=$this->Payablesegregationinfo->Payablesegregationstatus($x, $y);
	}
    public function Payablesegregationedit(){
        $result=$this->Payablesegregationinfo->Payablesegregationedit();
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
    public function Getviewpostinfo(){
        $result=$this->Payablesegregationinfo->Getviewpostinfo();
	}
    public function Payablesegregationposting(){
        $result=$this->Payablesegregationinfo->Payablesegregationposting();
	}
    public function Getinvoiceaccosupplier(){
        $result=$this->Payablesegregationinfo->Getinvoiceaccosupplier();
	}
    public function Getsupplierlist(){
        $searchTerm=$this->input->post('searchTerm');
        $result=get_supplier_search_list($searchTerm);
	}
}