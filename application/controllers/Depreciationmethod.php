<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Depreciationmethod extends CI_Controller 
{
    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('Depreciationmethodinfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('depreciationmethod', $result);
	}
    public function Depreciationmethodinsertupdate(){
	    $this->load->model('Depreciationmethodinfo');
        $result=$this->Depreciationmethodinfo->Depreciationmethodinsertupdate();
	 }
     public function Depreciationmethodstatus($x, $y){
		$this->load->model('Depreciationmethodinfo');
        $result=$this->Depreciationmethodinfo->Depreciationmethodstatus($x, $y);
	}
    public function Depreciationmethodedit(){
		$this->load->model('Depreciationmethodinfo');
        $result=$this->Depreciationmethodinfo->Depreciationmethodedit();
	}
}
?>