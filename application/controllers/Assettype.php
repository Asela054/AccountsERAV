<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Assettype extends CI_Controller {
    
    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('Assettypeinfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('assettype', $result);
	}
    public function Asset_typeinsertupdate(){
		$this->load->model('Assettypeinfo');
        $result=$this->Assettypeinfo->Asset_typeinsertupdate();
	}
    public function Asset_typestatus($x, $y){
		$this->load->model('Assettypeinfo');
        $result=$this->Assettypeinfo->Asset_typestatus($x, $y);
	}
    public function Asset_typeedit(){
		$this->load->model('Assettypeinfo');
        $result=$this->Assettypeinfo->Asset_typeedit();
	}
}