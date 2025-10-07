<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class AccountPayment extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("AccountPaymentinfo");
    }
    
	private function getProfile($orderRef, $accChildRef=''){
		$result=$this->AccountPaymentinfo->getOrderHeader($orderRef, $accChildRef);
		
		$data_main=array('idtbl_account_payable_main'=>'', 'tradate'=>'', 
						 'batchno'=>'', 'supplier'=>'-1', 'invoiceno'=>'', 'amount'=>'');
		$data_sub=array('idtbl_account_payable'=>'');
		
		if(!empty($result)){
			$data_main=array('idtbl_account_payable_main'=>$result->idtbl_account_payable_main, 
							 'tradate'=>$result->tradate, 'batchno'=>$result->batchno, 'supplier'=>$result->supplier, 
					'invoiceno'=>$result->invoiceno, 'amount'=>$result->m_amount,
					'tbl_company_idtbl_company'=>$result->tbl_company_idtbl_company, 
					'tbl_company_branch_idtbl_company_branch'=>$result->tbl_company_branch_idtbl_company_branch,
					'tbl_master_idtbl_master'=>$result->tbl_master_idtbl_master, 
					'status'=>$result->m_status, 'poststatus'=>$result->m_poststatus);
			$data_sub=array('idtbl_account_payable'=>$result->idtbl_account_payable, 'tradate'=>$result->tradate, 'batchno'=>$result->batchno, 
					'amount'=>$result->o_amount, 
					'narration'=>$result->o_narration, 'status'=>$result->o_status, 
					'tbl_master_idtbl_master'=>$result->tbl_master_idtbl_master,
					'tbl_company_idtbl_company'=>$result->tbl_company_idtbl_company, 
					'tbl_company_branch_idtbl_company_branch'=>$result->tbl_company_branch_idtbl_company_branch, 
					'tbl_account_payable_main_idtbl_account_payable_main'=>$result->idtbl_account_payable_main, 
					'tbl_account_detail_idtbl_account_detail'=>$result->tbl_account_detail_idtbl_account_detail);
		}
		
		//echo json_encode($data);
		
		return array('0'=>$data_main, '1'=>$data_sub);
	}
	
	private function getPaymentFigures($orderRef){
		$result=$this->AccountPaymentinfo->getOrderDetail($orderRef);
		$data=array();
		
		foreach($result as $r){
			$data[] = array('acc_payable_id'=>$r->idtbl_account_payable, 'acc_payable_narration'=>$r->narration, 
							'acc_payable_amount'=>$r->amount,
							'acc_payable_status'=>$r->status, 
							'acc_detail_id'=>$r->idtbl_account_detail, 
							'acc_detail_txt'=>$r->accountname);
		}
		
		return $data;
	}
	
	private function trackChanges($headerData, $prevOrderProfile){
		$orderProfileChanges = array();
		
		foreach($headerData as $dataKey=>$dataVal){//echo $dataKey.'---'.$prevOrderProfile[$dataKey].'vs'.$dataVal.'<br />';
			if($prevOrderProfile[$dataKey]!=$dataVal){
				$orderProfileChanges[$dataKey] = $dataVal;
			}
		}
		
		//setting-batchno-to-verify-on-modal.reg_update_order
		$orderProfileChanges['batchno'] = $prevOrderProfile['batchno'];
		
		return $orderProfileChanges;
	}
	
	public function view(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		
		$result['company_list']=rs_to_kv(get_company_list());
		$result['company_branch_list']=rs_to_kv(get_all_company_branch_list());
		$result['account_period']='';
		$result['child_account_list']=rs_to_kv(new stdClass());//get_child_account_list()
		$result['supplier_list']=rs_to_kv(get_all_company_branch_list());
		
		$all_company_list = get_company_list();
		$result['company_list_filter']=rs_to_kv($all_company_list, '');
		$result['company_payment_list_filter']=$all_company_list;
		$result['branch_payment_list_filter']=get_all_company_branch_list();
		
		$this->load->view('account_payment_view', $result);
	}
	
	public function create(){
		$companyid = $this->input->post('company_reg');
		$branchid = $this->input->post('branch_reg');
		$acc_payable_main_id = $this->input->post('acc_payable_main_id');
		$child_account_list = get_child_account_list($companyid, $branchid, false);
		
		$account_period = get_account_period($companyid, $branchid);
		
		//var_dump($account_period);die;//echo $this->db->last_query();
		
		$account_period_txt = (!empty($account_period))?$account_period->desc.'-'.$account_period->monthname:'-';
		$account_period_master_id = (!empty($account_period))?$account_period->idtbl_master:'-1';
		
		$payment_header = $this->getProfile($acc_payable_main_id);
		$view_header_data = $payment_header[0];
		$view_detail_data = $this->getPaymentFigures($acc_payable_main_id);
		
		echo json_encode(array("account_period_txt"=>$account_period_txt, 
							   "account_period_id"=>$account_period_master_id, 
							   "child_accounts"=>$child_account_list,
							   "view_header_data"=>$view_header_data,
							   "view_detail_data"=>$view_detail_data)
						 );
	}
	
	public function store(){
		//add-payment
		//validate-data
		$this->form_validation->set_rules('supplier_id', 'Supplier detail', 'required|callback_opt_check');
		$this->form_validation->set_rules('supp_invoice', 'Invoice', 'required|callback_invoice_check');
		$this->form_validation->set_rules('supp_invoice_amount', 'Invoice total value', 'required|greater_than[0]');
		$this->form_validation->set_rules('supp_invoice_date', 'Payment date', 'required');
		$this->form_validation->set_rules('acc_payable_main_id', 'Invoice', 'callback_post_check');
		$this->form_validation->set_rules('acc_period', 'Account period', 'callback_accperiod_check');
		
		if($this->input->post('acc_detail_amount')>0){
			$this->form_validation->set_rules('child_account_id', 'Account detail', 'required|callback_opt_check');
			$this->form_validation->set_rules('acc_detail_narration', 'Narration', 'required');
		}
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == false) {
			$validationErrors = form_error('supplier_id', '<div class="errormsg">', '</div>');
			
			if($validationErrors == ''){
				$validationErrors = form_error('supp_invoice', '<div class="errormsg">', '</div>');
			}
			
			if($validationErrors == ''){
				$validationErrors = form_error('acc_period', '<div class="errormsg">', '</div>');
			}
			
			//other-error-msgs
			if($validationErrors == ''){
				$validationErrors = form_error('supp_invoice_amount', '<div class="errormsg">', '</div>');
			}
			if($validationErrors == ''){
				$validationErrors = form_error('supp_invoice_date', '<div class="errormsg">', '</div>');
			}
			if($validationErrors == ''){
				$validationErrors = form_error('acc_payable_main_id', '<div class="errormsg">', '</div>');
			}
			if($validationErrors == ''){
				$validationErrors = form_error('child_account_id', '<div class="errormsg">', '</div>');
			}
			if($validationErrors == ''){
				$validationErrors = form_error('acc_detail_narration', '<div class="errormsg">', '</div>');
			}
			
			
			$data = array();
			$data['resMsg'] = $validationErrors;
			$data['resTheme'] = 'bg-info text-white';
		}else{
			$companyId = $this->input->post('company_id');
			$companyBranchId = $this->input->post('company_branch_id');
			$supplierId = $this->input->post('supplier_id');
			$suppInvoice = $this->input->post('supp_invoice');
			$suppInvoiceAmount = $this->input->post('supp_invoice_amount');
			$suppInvoiceRemarks = $this->input->post('supp_invoice_remarks');//
			$suppInvoiceDate = $this->input->post('supp_invoice_date');
			$childAccountId = $this->input->post('child_account_id');
			$accDetailNarration = $this->input->post('acc_detail_narration');
			$accDetailAmount = $this->input->post('acc_detail_amount');
			$accDetailRunningTotal = $this->input->post('acc_detail_running_total');
			$accPeriod = $this->input->post('acc_period');
			$accPayableMainId = $this->input->post('acc_payable_main_id');
			$userName = '1';//$_SESSION['userid'];//$this->input->post('');
			
			//$ = $this->input->post('');
			
			
			
			$headerData = array('tradate'=>$suppInvoiceDate, 'supplier'=>$supplierId, 
								'invoiceno'=>$suppInvoice, 'amount'=>$suppInvoiceAmount,
								'tbl_company_idtbl_company'=>$companyId, 'tbl_company_branch_idtbl_company_branch'=>$companyBranchId,
								'tbl_master_idtbl_master'=>$accPeriod, 'status'=>1);
			
			$prevAccPayableDetail = array();
			
			//save-header-detail
			if($accPayableMainId!=''){
				$prevAccPayableProfile = $this->getProfile($accPayableMainId, $childAccountId);
				$prevOrderProfile = $prevAccPayableProfile[0];
				$prevAccPayableDetail = $prevAccPayableProfile[1];//echo $this->db->last_query();die;
				/*
				$orderProfileChanges = array();
				
				foreach($headerData as $dataKey=>$dataVal){
					if($prevOrderProfile[$dataKey]!=$dataVal){
						$orderProfileChanges[$dataKey] = $dataVal;
					}
				}
				*/
				$headerData = $this->trackChanges($headerData, $prevOrderProfile);
				$headerData['updateuser'] = $userName;
			}else{
				/*
				generate-batch-num-after-validation
				*/
				$generate_batchnum = tr_batch_num('AP202311', '1');
				$headerData['batchno'] = $generate_batchnum;
				$headerData['tbl_user_idtbl_user'] = $userName;
			}
			
			$detailData = array('tradate'=>$suppInvoiceDate, 'batchno'=>$headerData['batchno'], 
								'amount'=>$accDetailAmount, 
								'narration'=>$accDetailNarration, 'status'=>1, 
								'tbl_master_idtbl_master'=>$accPeriod,
								'tbl_company_idtbl_company'=>$companyId, 
								'tbl_company_branch_idtbl_company_branch'=>$companyBranchId, 
								'tbl_account_payable_main_idtbl_account_payable_main'=>$accPayableMainId, 
								'tbl_account_detail_idtbl_account_detail'=>$childAccountId);//
							//, 'qrytime'=>'NOW()');
			//echo 'a--'.$childAccountId;//$prevAccPayableDetail['idtbl_account_payable'];
			$detailData_idtbl_account_payable = '';
			
			if(!empty($prevAccPayableDetail) && !empty($prevAccPayableDetail['idtbl_account_payable'])){
				$detailData = $this->trackChanges($detailData, $prevAccPayableDetail);
				$detailData['updateuser'] = $userName;
				$detailData['status'] = 1;
				$detailData_idtbl_account_payable = $prevAccPayableDetail['idtbl_account_payable'];
			}else{
				$detailData['tbl_user_idtbl_user'] = $userName;
			}
			//var_dump($detailData);die;
			$res_info = $this->AccountPaymentinfo->regUpdateOrder($headerData, $accPayableMainId, $detailData, $detailData_idtbl_account_payable);
			
			$data = array();
			
			$data['resMsg'] = $res_info['importMsg'];
			$data['resTheme'] = $res_info['toastType'];
			
			$data['head_k'] = $res_info['head_k'];
			$data['sub_k'] = $res_info['sub_k'];
		}
		
		/*
		display-notifications
		*/
		echo json_encode($data);//echo $generate_batchnum;
		
	}
	
	public function accperiod_check($field_val){
		$companyid = $this->input->post('company_id');
		$branchid = $this->input->post('company_branch_id');
		
		$result = get_account_period($companyid, $branchid);
		
		$account_status = false;
		//echo $result->idtbl_master.'vs'.$field_val;
		if(!empty($result)){
			$account_status = ($result->idtbl_master==$field_val);
		}
		
		if(!$account_status){
			$this->form_validation->set_message('accperiod_check', 'This account period is expired');
			return false;
		}
		
		return true;
	}
	
	public function post_check($field_val){
		$result=$this->AccountPaymentinfo->getOrderHeader($field_val, '');
		
		$post_status = false;
		
		if(!empty($result)){
			$post_status = ($result->m_poststatus=='1');
		}
		
		if($post_status){
			$this->form_validation->set_message('post_check', 'This payment is posted');
			return false;
		}
		
		return true;
	}
	
	public function invoice_check($field_val){
		$order_id=$this->input->post('acc_payable_main_id');//order-regno
		
		$result=$this->AccountPaymentinfo->getOrderHeaderByInvoice($field_val);
		
		$duplicate_code = false;
		
		if(!empty($result)){
			$duplicate_code = ($result->idtbl_account_payable_main!=$order_id);
		}
		
		if($duplicate_code){
			$this->form_validation->set_message('invoice_check', 'Duplicate value for the {field}');
			return false;
		}
		
		return true;
	}
	
	public function opt_check($field_val){
		$opt_valid = true;
		
		if(!empty($field_val)){
			$opt_valid = ($field_val!='-1');
		}
		
		if(!$opt_valid){
			$this->form_validation->set_message('opt_check', 'Select the {field}');
			return false;
		}
		
		return true;
	}
	
	public function invoices(){
		$selected_invoice = $this->input->post('selected_opt');
		echo json_encode(array('view_post_data'=>$this->AccountPaymentinfo->getOrderHeaders($selected_invoice)));
	}
	
	public function review(){
		$selected_invoice = $this->input->post('selected_opt');
		echo json_encode(array('view_detail_data'=>$this->getPaymentFigures($selected_invoice)));
	}
	
    public function approve(){
		//post-payments
	}
	
	public function settle(){
		//settle-posted-payments
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('account_payment_settle', $result);
	}
	
	
	
	
	public function provider_datatable(){
		$table = 'tbl_company_branch';
    	$primaryKey = 'idtbl_company_branch';
		
		$columns = array(
           array( 'db' => 'idtbl_company_branch', 'dt' => 'branch_regno' ),array( 'db' => 'branch', 'dt' => 'branch_name' ),
					array( 'db' => 'code', 'dt' => 'branch_code'),
					array( 'db' => 'tbl_company_idtbl_company', 'dt' => 'parent_company_regno' , 'alias_name' => 'parent_org' )
		);

        $sql_details = array(
			'user' => $this->db->username,
			'pass' => $this->db->password,
			'db'   => $this->db->database,
			'host' => $this->db->hostname
		);
		
		
		
		$where = '';//array("sponser != ''");
		
		
		
		$output_arr = SSP::complex( $_GET, $sql_details, $table, $primaryKey, 
								   $columns, $where);
		
		echo json_encode($output_arr);
	}
}