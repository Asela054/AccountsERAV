<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Api extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Apiinfo");
    }
    public function Receiptsegregationinsertupdate(){
        $result=$this->Apiinfo->Receiptsegregationinsertupdate();
	}
    public function Receiptsegregationstatus(){
        $result=$this->Apiinfo->Receiptsegregationstatus();
	}
    public function Payablesegregationinsertupdate(){
        $result=$this->Apiinfo->Payablesegregationinsertupdate();
	}
    public function Issuematerialprocess(){
        $result=$this->Apiinfo->Issuematerialprocess();
	}
    public function Payrollsalaryprocess(){
        $result=$this->Apiinfo->Payrollsalaryprocess();
	}
    public function Costmaterialprocess(){
        $result=$this->Apiinfo->Costmaterialprocess();
	}
}