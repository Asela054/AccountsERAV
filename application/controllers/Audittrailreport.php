<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Audittrailreport extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Audittrailreportinfo");
    }
    public function index(){
        $result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
        $this->load->view('audittrailreport', $result);
    }
    public function Audittrailreportview(){
        $data['audittrailreportdata']=$this->Audittrailreportinfo->Audittrailreportview();
    }
}