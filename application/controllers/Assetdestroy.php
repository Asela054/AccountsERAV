<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Assetdestroy extends CI_Controller {
    
    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('Assetdestroyinfo');
        $result['menuaccess'] = $this->Commeninfo->Getmenuprivilege();
        $this->load->view('assetdestroy', $result);
    }
    
    public function Assetdestroyinsertupdate(){
        $this->load->model('Assetdestroyinfo');
        $result = $this->Assetdestroyinfo->Assetdestroyinsertupdate();
       
    }
    
    public function Assetdestroystatus($x, $y){
        $this->load->model('Assetdestroyinfo'); 
        $result = $this->Assetdestroyinfo->Assetdestroystatus($x, $y);
       
    }
    
    public function Assetdestroyedit(){
        $this->load->model('Assetdestroyinfo');
        $result = $this->Assetdestroyinfo->Assetdestroyedit();
       
    }
}
?>
