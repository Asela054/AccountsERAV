<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Chartofaccountdetail extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Chartofaccountdetailinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['chartofaccount']=$this->Chartofaccountdetailinfo->Getchartofaccount();
        $result['accountspecailcategorydata']=$this->Chartofaccountdetailinfo->Getaccountspecialcategorydata();
		$this->load->view('chartofaccountdetail', $result);
	}
    public function Chartofaccountdetailinsertupdate(){
        $result=$this->Chartofaccountdetailinfo->Chartofaccountdetailinsertupdate();
	}
    public function Chartofaccountdetailstatus($x, $y){
        $result=$this->Chartofaccountdetailinfo->Chartofaccountdetailstatus($x, $y);
	}
    public function Chartofaccountdetailedit(){
        $result=$this->Chartofaccountdetailinfo->Chartofaccountdetailedit();
	}
    public function Getbranchaccocompany(){
        $result=$this->Chartofaccountdetailinfo->Getbranchaccocompany();
	}
    public function Getaccountlist(){
        $result=$this->Chartofaccountdetailinfo->Getaccountlist();
	}
    public function Getnextdetailaccountno(){
        $result=$this->Chartofaccountdetailinfo->Getnextdetailaccountno();
	}
    public function Checkaccountnoalready(){
        $result=$this->Chartofaccountdetailinfo->Checkaccountnoalready();
	}
    public function Getchartaccountlist(){
        $searchTerm=$this->input->post('searchTerm');
        $companyid=$_SESSION['companyid'];
        $branchid=$_SESSION['branchid'];
        
        $result=get_chart_acount_select2($searchTerm, $companyid, $branchid);
	}
    public function getSpecialCateDetailAccount(){
        $searchTerm=$this->input->post('searchTerm');
        $result=getSpecialCateDetailAccount($searchTerm);
    }
    public function Chartofaccountdetailspecialcateupdate(){
        $result=$this->Chartofaccountdetailinfo->Chartofaccountdetailspecialcateupdate();
    }
    public function Chartofaccountdetailspecialcategorystatus($x, $y){
        $result=$this->Chartofaccountdetailinfo->Chartofaccountdetailspecialcategorystatus($x, $y);
	}
}