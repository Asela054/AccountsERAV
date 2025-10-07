<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class BatchTransactionType extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("BatchTransactionTypeinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['batchcategory']=$this->BatchTransactionTypeinfo->Getbatchcategory();
		$result['transactiontype']=$this->BatchTransactionTypeinfo->Gettransactiontype();
		$this->load->view('batchtransactiontype', $result);
	}
    public function BatchTransactionTypeinsertupdate(){
        $result=$this->BatchTransactionTypeinfo->BatchTransactionTypeinsertupdate();
	}
    public function BatchTransactionTypestatus($x, $y){
        $result=$this->BatchTransactionTypeinfo->BatchTransactionTypestatus($x, $y);
	}
    public function BatchTransactionTypeedit(){
        $result=$this->BatchTransactionTypeinfo->BatchTransactionTypeedit();
	}
    public function Getaccountlist(){
        $searchTerm=$this->input->post('searchTerm');
        $companyid=$_SESSION['companyid'];
        $branchid=$_SESSION['branchid'];

        $result=get_all_accounts($searchTerm, $companyid, $branchid);
	}
}