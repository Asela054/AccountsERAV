<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Creditorreport extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Creditorreportinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('creditorreport', $result);
	}
    public function Getsupplierlist(){
        $searchTerm=$this->input->post('searchTerm');
        $companyid=$this->input->post('companyid');
        $branchid=$this->input->post('branchid');
        $result=get_supplier_search_list($searchTerm);
	}
    public function Creditorreportview(){
        // $result=$this->Creditorreportinfo->Creditorreportview();
        $reportType = $this->input->post('reporttype');
        if($reportType==1): 
            $data = $this->Creditorreportinfo->CreditorStatementReport();
        elseif($reportType==2): 
            $data = $this->Creditorreportinfo->CreditorAgeAnalysisReport();
        endif;
    }
}