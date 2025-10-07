<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Depreciationtype extends CI_Controller {
    
    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('Depreciationtypeinfo');
		$result['menuaccess'] = $this->Commeninfo->Getmenuprivilege();
		$this->load->view('depreciationtype', $result);
	}
    
    public function Depreciationtypeinsertupdate(){
		$this->load->model('Depreciationtypeinfo');
        $result = $this->Depreciationtypeinfo->Depreciationtypeinsertupdate();
        
	}
    
    public function Depreciationtypestatus($x, $y){
		$this->load->model('Depreciationtypeinfo');
        $result = $this->Depreciationtypeinfo->Depreciationtypestatus($x, $y);
    
	}
    
    public function Depreciationtypeedit(){
		$this->load->model('Depreciationtypeinfo');
        $result = $this->Depreciationtypeinfo->Depreciationtypeedit();
        
	}
}
?>
