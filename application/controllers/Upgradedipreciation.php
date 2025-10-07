<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Upgradedipreciation extends CI_Controller 
{
    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('Upgradedipreciationinfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('upgradedipreciation', $result);
	}
    public function Upgradedipreciationinsertupdate(){
	    $this->load->model('Upgradedipreciationinfo');
        $result=$this->Upgradedipreciationinfo->Upgradedipreciationinsertupdate();
	 }
     public function Upgradedipreciationstatus($x, $y){
		$this->load->model('Upgradedipreciationinfo');
        $result=$this->Upgradedipreciationinfo->Upgradedipreciationstatus($x, $y);
	}
    public function Upgradedipreciationedit(){
		$this->load->model('Upgradedipreciationinfo');
        $result=$this->Upgradedipreciationinfo->Upgradedipreciationedit();
	}
}
?>