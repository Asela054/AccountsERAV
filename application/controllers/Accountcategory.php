<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Accountcategory extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Accountcategoryinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['finacialtype']=$this->Accountcategoryinfo->Getfinacialtype();
		$result['transactiontype']=$this->Accountcategoryinfo->Gettransactiontype();
		$this->load->view('accountcategory', $result);
	}
    public function Accountcategoryinsertupdate(){
        $result=$this->Accountcategoryinfo->Accountcategoryinsertupdate();
	}
    public function Accountcategorystatus($x, $y){
        $result=$this->Accountcategoryinfo->Accountcategorystatus($x, $y);
	}
    public function Accountcategoryedit(){
        $result=$this->Accountcategoryinfo->Accountcategoryedit();
	}
}