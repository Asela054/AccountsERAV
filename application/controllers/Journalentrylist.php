<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Journalentrylist extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('journalentrylist', $result);
	}
}