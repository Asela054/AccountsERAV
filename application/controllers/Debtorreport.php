<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Debtorreport extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Debtorreportinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('debtorreport', $result);
	}
    public function Getcustomerlist(){
        $searchTerm=$this->input->post('searchTerm');
        $companyid=$this->input->post('companyid');
        $branchid=$this->input->post('branchid');
        $result=get_customer_search_list($searchTerm, $companyid, $branchid);
	}
    public function Debtorreportview(){
        // $result=$this->Debtorreportinfo->Debtorreportview();
        $reportType = $this->input->post('reporttype');
        if($reportType==1): 
            $data = $this->Debtorreportinfo->DebtorStatementReport();
        elseif($reportType==2): 
            $data = $this->Debtorreportinfo->DebtorAgeAnalysisReport();
        endif;
    }
}