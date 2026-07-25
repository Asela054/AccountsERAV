<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Paymentsettle extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Paymentsettleinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
        $result['companylist']=get_company_list();
        $result['payabletype']=$this->Paymentsettleinfo->Getreceivabletype();
		$this->load->view('paymentsettle', $result);
	}
    public function Paymentsettleinsertupdate(){
        $result=$this->Paymentsettleinfo->Paymentsettleinsertupdate();
	}
    public function Paymentsettlestatus($x, $y){
        $result=$this->Paymentsettleinfo->Paymentsettlestatus($x, $y);
	}
    public function Paymentsettleedit(){
        $result=$this->Paymentsettleinfo->Paymentsettleedit();
	}
    public function Getbranchaccocompany(){
        $recordID=$this->input->post('recordID');
        $result=get_company_branch_list($recordID);
	}
    public function Getaccountlist(){
        $companyid=$this->input->post('companyid');
        $branchid=$this->input->post('branchid');
        
        $result=get_all_accounts($companyid, $branchid);
	}
    public function Getinvoiceaccosupplier(){
        $result=$this->Paymentsettleinfo->Getinvoiceaccosupplier();
    }
    public function Getviewpostinfo(){
        $result=$this->Paymentsettleinfo->Getviewpostinfo();
    }
    public function Paymentsettleposting(){
        $result=$this->Paymentsettleinfo->Paymentsettleposting();
    }
    public function Getinvrecno(){
        $result=$this->Paymentsettleinfo->Getinvrecno();
    }
    public function Getsupplierlist(){
        $searchTerm=$this->input->post('searchTerm');
        $payablefilter=$this->input->post('payablefilter');
        $companyid=$_SESSION['companyid'];
		$branchid=$_SESSION['branchid'];

        if($payablefilter == '1'){
            $result=get_supplier_search_list($searchTerm);
        } else if($payablefilter == '2'){
            $result=get_payable_account_list($searchTerm);
        }else if($payablefilter == '3'){
            $result=get_all_accounts($searchTerm, $companyid, $branchid);
        }
	}
    public function Paymentsettlecancel(){
        $result=$this->Paymentsettleinfo->Paymentsettlecancel();
    }
    public function Getadvanceaccosupplier(){
        $result=$this->Paymentsettleinfo->Getadvanceaccosupplier();
    }
    public function Getaccountinfoaccoaccountlist(){
        $result=$this->Paymentsettleinfo->Getaccountinfoaccoaccountlist();
    }
}