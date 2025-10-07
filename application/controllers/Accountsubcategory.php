<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Accountsubcategory extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Accountsubcategoryinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['accountcategory']=$this->Accountsubcategoryinfo->Getaccountcategory();
		$this->load->view('accountsubcategory', $result);
	}
    public function Accountsubcategoryinsertupdate(){
        $result=$this->Accountsubcategoryinfo->Accountsubcategoryinsertupdate();
	}
    public function Accountsubcategorystatus($x, $y){
        $result=$this->Accountsubcategoryinfo->Accountsubcategorystatus($x, $y);
	}
    public function Accountsubcategoryedit(){
        $result=$this->Accountsubcategoryinfo->Accountsubcategoryedit();
	}
}