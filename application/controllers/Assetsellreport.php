<?php
defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Assetsellreport extends CI_Controller
{

  public function index()
  {
    $this->load->model('Commeninfo');
    $this->load->model('Assetsellreportinfo');
    $result['menuaccess'] = $this->Commeninfo->Getmenuprivilege();
    $result['asset_name']=$this->Assetsellreportinfo->Getasset_name();
    $this->load->view('assetsellreport', $result);
  }

  public function Getasset_name()
  {
    $this->load->model('Assetsellreportinfo');
    $result=$this->Assetsellreportinfo->Getasset_name();
  }
  
  public function Getdate()
  {
    $this->load->model('Assetsellreportinfo');
    $result=$this->Assetsellreportinfo->Getdate();
  }

  public function Getreason()
  {
    $this->load->model('Assetsellreportinfo');
    $result=$this->Assetsellreportinfo->Getreason();
  }

  public function Getamount()
  {
    $this->load->model('Assetsellreportinfo');
    $result=$this->Assetsellreportinfo->Getamount();
  }

  // public function selldetailreport()
	// {
	// 	$this->load->model('Assetsellreportinfo');
  //       $result=$this->Assetsellreportinfo->selldetailreport();
	// }


  public function selldetailreport()
  {
    $this->load->model('Assetsellreportinfo');
    $data = $this->Assetsellreportinfo->selldetailreport();
    echo $data;
  }
}
