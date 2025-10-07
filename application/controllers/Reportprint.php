<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Reportprint extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("Reportprintinfo");
    }
    public function Receivereceipt($x, $y){
        $result['printinfo']=$this->Reportprintinfo->Receivereceipt($x, $y);
    }
    public function Paymentsettlereceipt($x, $y){
        $result['printinfo']=$this->Reportprintinfo->Paymentsettlereceipt($x, $y);
    }
    public function Paymentreceipt($x){
        $result['printinfo']=$this->Reportprintinfo->Paymentreceipt($x);
    }
    public function PettyCashReibursePrint($x){
        $result['printinfo']=$this->Reportprintinfo->PettyCashReibursePrint($x);
    }
    public function Receivablereceipt($x){
        $result['printinfo']=$this->Reportprintinfo->Receivablereceipt($x);
    }
    public function PettycashVoucher($x){
        $result['printinfo']=$this->Reportprintinfo->PettycashVoucher($x);
    }
}