<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class BatchTransaction extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("BatchTransactioninfo");
    }
    public function index(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['batchcategory']=$this->BatchTransactioninfo->Getbatchcategory();
		$result['uomlist']=$this->BatchTransactioninfo->Getuomlist();
        $result['accounttranstypelist']=$this->BatchTransactioninfo->Gettransactiontypelist();
		$this->load->view('batchtransaction', $result);
	}
    public function GetInventoryMaterial(){
        $companyid=$_SESSION['companyid'];
        $branchid=$_SESSION['branchid'];
        $searchTerm=$this->input->post('searchTerm');
        
        $result['loaddata']=get_material_search_list($searchTerm, $companyid, $branchid);
    }
    public function GetBatchTransType(){
        $result['loaddata']=$this->BatchTransactioninfo->GetBatchTransType();
    }
    public function GetMaterialDetails(){
        $result['loaddata']=$this->BatchTransactioninfo->GetMaterialDetails();
    }
    public function BatchTransactioninsertupdate(){
        $result['loaddata']=$this->BatchTransactioninfo->BatchTransactioninsertupdate();
    }
    public function BatchTransactionview(){
        $result['loaddata']=$this->BatchTransactioninfo->BatchTransactionview();
    }
    public function BatchTransactionapprove(){
        $result['loaddata']=$this->BatchTransactioninfo->BatchTransactionapprove();
    }
    public function BatchTransactionedit(){
        $result['loaddata']=$this->BatchTransactioninfo->BatchTransactionedit();
    }
    public function BatchTransactioncomplete(){
        $result['loaddata']=$this->BatchTransactioninfo->BatchTransactioncomplete();
    }
    public function BatchTransactioninfostatus(){
        $result['loaddata']=$this->BatchTransactioninfo->BatchTransactioninfostatus();
    }
    public function BatchTransactionstatus($x, $y){
        $result['loaddata']=$this->BatchTransactioninfo->BatchTransactionstatus($x, $y);
    }
    public function Getcustomerlist(){
        $searchTerm=$this->input->post('searchTerm');
        $result=get_customer_search_list($searchTerm);
	}
    public function Getsupplierlist(){
        $searchTerm=$this->input->post('searchTerm');
        $result=get_supplier_search_list($searchTerm);
	}
}