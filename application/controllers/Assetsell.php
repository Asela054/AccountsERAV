<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Assetsell extends CI_Controller 
{
    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('Assetsellinfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
        $result['asset_name']=$this->Assetsellinfo->Getasset_name();
		$this->load->view('Assetsell', $result);
	}
    public function Assetsellinsertupdate(){
	    $this->load->model('Assetsellinfo');
        $result=$this->Assetsellinfo->Assetsellinsertupdate();
	 }
     public function Assetsellstatus($x, $y){
		$this->load->model('Assetsellinfo');
        $result=$this->Assetsellinfo->Assetsellstatus($x, $y);
	}
    public function Assetselledit(){
		$this->load->model('Assetsellinfo');
        $result=$this->Assetsellinfo->Assetselledit();
	}

    public function Assetsellsale(){
		$this->load->model('Assetsellinfo');
        $result=$this->Assetsellinfo->Assetsellsale();
	}
}
?>