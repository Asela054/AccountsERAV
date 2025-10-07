<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Receivedcheque extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Receivedchequeinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['companylist']=get_company_list();
		$this->load->view('receivedcheque', $result);
	}
    public function Receivedchequestatus(){
        $result=$this->Receivedchequeinfo->Receivedchequestatus();
	}
}