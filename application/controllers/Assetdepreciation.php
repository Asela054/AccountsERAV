<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Assetdepreciation extends CI_Controller 
{
    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('Assetdepreciationinfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
        $result['companylist']=get_company_list();
        // $result['assettype']=$this->Assetinfo->Getassettype();
		// $result['depreciationtype']=$this->Assetinfo->Getdepreciationtype();
		// $result['depreciationcategory']=$this->Assetinfo->Getdepreciationcategory();
		// $result['depreciationmethod']=$this->Assetinfo->Getdepreciationmethod();
		$this->load->view('assetdepreciation', $result);
	}
    public function Assetdepreciationinsertupdate(){
		$this->load->model('Assetdepreciationinfo');
        $result=$this->Assetdepreciationinfo->Assetdepreciationinsertupdate();
	}
    public function Assetstatus($x, $y){
		$this->load->model('Assetinfo');
        $result=$this->Assetinfo->Assetstatus($x, $y);
	}
    public function Assetedit(){
		$this->load->model('Assetinfo');
        $result=$this->Assetinfo->Assetedit();
    }
    public function Getassetsdepreciationinfo(){
		$this->load->model('Assetdepreciationinfo');
        $result=$this->Assetdepreciationinfo->Getassetsdepreciationinfo();
    }
    public function Getbranchaccocompany(){
        $recordID=$this->input->post('recordID');
        $result=get_company_branch_list($recordID);
	}
}