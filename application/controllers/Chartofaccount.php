<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Chartofaccount extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Chartofaccountinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['accountcategory']=$this->Chartofaccountinfo->Getaccountcategory();
		$result['accounttype']=$this->Chartofaccountinfo->Getaccounttype();
		$result['accountspecailcategory']=$this->Chartofaccountinfo->Getaccountspecialcategory();
		$result['accountspecailcategorydata']=$this->Chartofaccountinfo->Getaccountspecialcategorydata();
		$this->load->view('chartofaccount', $result);
	}
    public function Chartofaccountinsertupdate(){
        $result=$this->Chartofaccountinfo->Chartofaccountinsertupdate();
	}
    public function Chartofaccountstatus($x, $y){
        $result=$this->Chartofaccountinfo->Chartofaccountstatus($x, $y);
	}
    public function Chartofaccountedit(){
        $result=$this->Chartofaccountinfo->Chartofaccountedit();
	}
    public function Getsubcateaccoaccountcate(){
        $result=$this->Chartofaccountinfo->Getsubcateaccoaccountcate();
	}
    public function Getsnestcateaccoaccountsubcate(){
        $result=$this->Chartofaccountinfo->Getsnestcateaccoaccountsubcate();
	}
    public function Chartofaccountspecialcateupdate(){
        $result=$this->Chartofaccountinfo->Chartofaccountspecialcateupdate();
	}
    public function Chartofaccountspecialcategorystatus($x, $y){
        $result=$this->Chartofaccountinfo->Chartofaccountspecialcategorystatus($x, $y);
	}
    public function Getnextaccountno(){
        $result=$this->Chartofaccountinfo->Getnextaccountno();
	}
    public function Checkaccountnoalready(){
        $result=$this->Chartofaccountinfo->Checkaccountnoalready();
	}
}