<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Currentperiod extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Currentperiodinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['fiancialyear']=$this->Currentperiodinfo->Getfinancialyear();
		$this->load->view('currentperiod', $result);
	}
    public function Currentperiodinsertupdate(){
        $result=$this->Currentperiodinfo->Currentperiodinsertupdate();
	}
    public function Getmonthlistaccoyear(){
        $result=$this->Currentperiodinfo->Getmonthlistaccoyear();
	}
}