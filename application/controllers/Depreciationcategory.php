<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Depreciationcategory extends CI_Controller {
    
    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('Depreciationcategoryinfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('depreciationcategory', $result);
	}

    public function Depreciationcategoryinsertupdate(){
		$this->load->model('Depreciationcategoryinfo');
        $result = $this->Depreciationcategoryinfo->Depreciationcategoryinsertupdate();
	}
    public function Depreciationcategorystatus($x, $y){
		$this->load->model('Depreciationcategoryinfo');
        $result = $this->Depreciationcategoryinfo->Depreciationcategorystatus($x, $y);
	}
    public function Depreciationcategoryedit(){
		$this->load->model('Depreciationcategoryinfo');
        $result = $this->Depreciationcategoryinfo->Depreciationcategoryedit();
    }
}