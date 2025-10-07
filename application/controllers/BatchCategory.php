<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class BatchCategory extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("BatchCategoryinfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('batchcategory', $result);
	}
    public function BatchCategoryinsertupdate(){
        $result=$this->BatchCategoryinfo->BatchCategoryinsertupdate();
	}
    public function BatchCategorystatus($x, $y){
        $result=$this->BatchCategoryinfo->BatchCategorystatus($x, $y);
	}
    public function BatchCategoryedit(){
        $result=$this->BatchCategoryinfo->BatchCategoryedit();
	}
}