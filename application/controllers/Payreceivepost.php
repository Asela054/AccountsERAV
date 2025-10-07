<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Payreceivepost extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Payreceivepostinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['companylist']=get_company_list();
		$this->load->view('payreceivepost', $result);
	}
    public function Getpayreceivelist(){
        $result=$this->Payreceivepostinfo->Getpayreceivelist();
	}
    public function Payreceivepostposting(){
        $result=$this->Payreceivepostinfo->Payreceivepostposting();
	}
    public function Getbranchaccocompany(){
        $recordID=$this->input->post('recordID');
        $result=get_company_branch_list($recordID);
	}
}