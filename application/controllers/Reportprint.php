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
        $functionname = $this->config->item('receivable_settle_receipt_print');
        $result['printinfo']=$this->Reportprintinfo->$functionname($x, $y);
    }
    public function Paymentsettlereceipt($x, $y){
        $functionname = $this->config->item('payment_settle_receipt_print');
        $result['printinfo']=$this->Reportprintinfo->$functionname($x, $y);
    }
    public function Paymentreceipt($x){
        $functionname = $this->config->item('payment_receipt_print');
        $result['printinfo']=$this->Reportprintinfo->$functionname($x);
    }
    public function PettyCashReibursePrint($x){
        $functionname = $this->config->item('pettycash_reimburse_print');
        $result['printinfo']=$this->Reportprintinfo->$functionname($x);
    }
    public function Receivablereceipt($x){
        $functionname = $this->config->item('receivable_receipt_print');
        $result['printinfo']=$this->Reportprintinfo->$functionname($x);
    }
    public function PettycashVoucher($x){
        $functionname = $this->config->item('pettycash_receipt_print');
        $result['printinfo']=$this->Reportprintinfo->$functionname($x);
    }
}