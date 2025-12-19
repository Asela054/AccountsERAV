<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Issuecheque extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Issuechequeinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['companylist']=get_company_list();
		$this->load->view('issuecheque', $result);
	}
    public function Issuechequestatus(){
        $result=$this->Issuechequeinfo->Issuechequestatus();
	}
    public function Chequeprint($x){
        $result=$this->Issuechequeinfo->Chequeprint($x);
    }
}