<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Accountallocation extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Accountallocationinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		// $result['companylist']=$this->Accountallocationinfo->Getcompany();
        $result['companylist']=get_company_list();
		$result['accounttype']=$this->Accountallocationinfo->Getaccounttype();
		$this->load->view('accountallocation', $result);
	}
    public function Accountallocationinsertupdate(){
        $result=$this->Accountallocationinfo->Accountallocationinsertupdate();
	}
    public function Getbranchaccocompany(){
        $result=$this->Accountallocationinfo->Getbranchaccocompany();
	}
    public function Getaccountlist(){
        $result=$this->Accountallocationinfo->Getaccountlist();
	}
}