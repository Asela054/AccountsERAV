<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Accountnestcategory extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Accountnestcategoryinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['accountcategory']=$this->Accountnestcategoryinfo->Getaccountcategory();
		$this->load->view('accountnestcategory', $result);
	}
    public function Accountnestcategoryinsertupdate(){
        $result=$this->Accountnestcategoryinfo->Accountnestcategoryinsertupdate();
	}
    public function Accountnestcategorystatus($x, $y){
        $result=$this->Accountnestcategoryinfo->Accountnestcategorystatus($x, $y);
	}
    public function Accountnestcategoryedit(){
        $result=$this->Accountnestcategoryinfo->Accountnestcategoryedit();
	}
    public function Getsubcateaccoaccountcate(){
        $result=$this->Accountnestcategoryinfo->Getsubcateaccoaccountcate();
	}
}