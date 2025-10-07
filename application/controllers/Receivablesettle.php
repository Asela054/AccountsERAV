<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Receivablesettle extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Receivablesettleinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
        $result['companylist']=get_company_list();
        $result['receivabletype']=$this->Receivablesettleinfo->Getreceivabletype();
		$this->load->view('receivablesettle', $result);
	}
    public function Receivablesettleinsertupdate(){
        $result=$this->Receivablesettleinfo->Receivablesettleinsertupdate();
	}
    public function Receivablesettlestatus($x, $y){
        $result=$this->Receivablesettleinfo->Receivablesettlestatus($x, $y);
	}
    public function Receivablesettleedit(){
        $result=$this->Receivablesettleinfo->Receivablesettleedit();
	}
    public function Getbranchaccocompany(){
        $recordID=$this->input->post('recordID');
        $result=get_company_branch_list($recordID);
	}
    public function Getaccountlist(){
        $companyid=$this->input->post('companyid');
        $branchid=$this->input->post('branchid');
        // $receivetype=$this->input->post('receivetype');

        // if($receivetype==1){
        //     $result=get_child_account_list($companyid, $branchid);
        // }
        // else{
        //     $result=get_bank_acount_list($companyid, $branchid);
        // }
        $result=get_all_accounts($companyid, $branchid);
	}
    public function Getcustomerlist(){
        $searchTerm=$this->input->post('searchTerm');
        $result=get_customer_search_list($searchTerm);
	}
    public function Getinvoiceaccocustomer(){
        $result=$this->Receivablesettleinfo->Getinvoiceaccocustomer();
    }
    public function Getviewpostinfo(){
        $result=$this->Receivablesettleinfo->Getviewpostinfo();
    }
    public function Receivablesettleposting(){
        $result=$this->Receivablesettleinfo->Receivablesettleposting();
    }
    public function Getinvrecno(){
        $result=$this->Receivablesettleinfo->Getinvrecno();
    }
}