<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Accountingperiod extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Accountingperiodinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('accountingperiod', $result);
	}
    public function Accountingperiodinsertupdate(){
        $result=$this->Accountingperiodinfo->Accountingperiodinsertupdate();
	}
    public function Accountingperiodstatus($x, $y){
        $result=$this->Accountingperiodinfo->Accountingperiodstatus($x, $y);
	}
    public function Accountingperiodedit(){
        $result=$this->Accountingperiodinfo->Accountingperiodedit();
	}
}