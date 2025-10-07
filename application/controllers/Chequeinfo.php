<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Chequeinfo extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Chequeinfoinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['banklist']=$this->Chequeinfoinfo->Getbanklist();
		$result['accountlist']=$this->Chequeinfoinfo->Getbankchartofaccount();
		$this->load->view('chequeinfo', $result);
	}
    public function Chequeinfoinsertupdate(){
        $result=$this->Chequeinfoinfo->Chequeinfoinsertupdate();
	}
    public function Chequeinfostatus($x, $y){
        $result=$this->Chequeinfoinfo->Chequeinfostatus($x, $y);
	}
    public function Chequeinfoedit(){
        $result=$this->Chequeinfoinfo->Chequeinfoedit();
	}
    public function Getbankbranchaccbank(){
        $result=$this->Chequeinfoinfo->Getbankbranchaccbank();
	}
}