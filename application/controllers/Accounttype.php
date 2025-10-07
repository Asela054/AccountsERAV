<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Accounttype extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Accounttypeinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('accounttype', $result);
	}
    public function Accounttypeinsertupdate(){
        $result=$this->Accounttypeinfo->Accounttypeinsertupdate();
	}
    public function Accounttypestatus($x, $y){
        $result=$this->Accounttypeinfo->Accounttypestatus($x, $y);
	}
    public function Accounttypeedit(){
        $result=$this->Accounttypeinfo->Accounttypeedit();
	}
}