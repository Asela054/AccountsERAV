<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Asset extends CI_Controller 
{
    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('Assetinfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
        $result['assettype']=$this->Assetinfo->Getassettype();
		$result['depreciationtype']=$this->Assetinfo->Getdepreciationtype();
		$result['depreciationcategory']=$this->Assetinfo->Getdepreciationcategory();
		$result['depreciationmethod']=$this->Assetinfo->Getdepreciationmethod();
		$this->load->view('asset', $result);
	}
    public function Assetinsertupdate(){
		$this->load->model('Assetinfo');
        $result=$this->Assetinfo->Assetinsertupdate();
	}
    public function Assetstatus($x, $y){
		$this->load->model('Assetinfo');
        $result=$this->Assetinfo->Assetstatus($x, $y);
	}
    public function Assetedit(){
		$this->load->model('Assetinfo');
        $result=$this->Assetinfo->Assetedit();
    }
}