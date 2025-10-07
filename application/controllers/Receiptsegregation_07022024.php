<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Receiptsegregation extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Receiptsegregationinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['companylist']=get_company_list();
        $result['customerlist']=get_customer_list();
		$this->load->view('receiptsegregation', $result);
	}
    public function Receiptsegregationinsertupdate(){
        $result=$this->Receiptsegregationinfo->Receiptsegregationinsertupdate();
	}
    public function Receiptsegregationstatus($x, $y){
        $result=$this->Receiptsegregationinfo->Receiptsegregationstatus($x, $y);
	}
    public function Receiptsegregationedit(){
        $result=$this->Receiptsegregationinfo->Receiptsegregationedit();
	}
    public function Getbranchaccocompany(){
        $recordID=$this->input->post('recordID');
        $result=get_company_branch_list($recordID);
	}
    public function Getaccountlist(){
        $companyid=$this->input->post('companyid');
        $branchid=$this->input->post('branchid');

        $result=get_child_account_list($companyid, $branchid);
	}
    public function Getviewpostinfo(){
        $result=$this->Receiptsegregationinfo->Getviewpostinfo();
	}
    public function Receiptsegregationposting(){
        $result=$this->Receiptsegregationinfo->Receiptsegregationposting();
	}
    public function Getinvoiceaccocustomer(){
        $result=$this->Receiptsegregationinfo->Getinvoiceaccocustomer();
	}
}