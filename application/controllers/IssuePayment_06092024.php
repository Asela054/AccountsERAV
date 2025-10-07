<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class IssuePayment extends CI_Controller {
    
	private $payment_detail_total;
	
	public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("IssuePaymentinfo");
    }
    
	private function getProfile($orderRef, $invoiceRef=''){
		$result=$this->IssuePaymentinfo->getOrderHeader($orderRef, $invoiceRef);
		
		$data_main=array('idtbl_account_paysettle'=>'', 'date'=>'', 
						 'batchno'=>'', 'supplier'=>'-1', 'totalpayment'=>'');
		$data_sub=array('idtbl_account_paysettle_info'=>'');
		
		if(!empty($result)){
			$data_main=array('idtbl_account_paysettle'=>$result->idtbl_account_paysettle, 
							 'date'=>$result->issuedate, 'batchno'=>$result->batchno, 'supplier'=>$result->supplier, 
					'totalpayment'=>$result->m_amount, 'remark'=>$result->remark, 
					'tbl_company_idtbl_company'=>$result->tbl_company_idtbl_company, 
					'tbl_company_branch_idtbl_company_branch'=>$result->tbl_company_branch_idtbl_company_branch,
					'tbl_master_idtbl_master'=>$result->tbl_master_idtbl_master, 
					'status'=>$result->m_status, 'poststatus'=>$result->m_poststatus);
			$data_sub=array('idtbl_account_paysettle_info'=>$result->idtbl_account_paysettle_info, 'batchno'=>$result->batchno, 
					'amount'=>$result->o_amount, 
					'narration'=>$result->o_narration, 'status'=>$result->o_status, 
					'tbl_account_paysettle_idtbl_account_paysettle'=>$result->idtbl_account_paysettle, 
					'invoiceno'=>$result->invoiceno);
		}
		
		//echo json_encode($data);
		
		return array('0'=>$data_main, '1'=>$data_sub);
	}
	
	private function getPaymentFigures($orderRef){
		$result=$this->IssuePaymentinfo->getOrderDetail($orderRef);
		$data=array();
		
		$this->payment_detail_total = 0;
		
		foreach($result as $r){
			$data[] = array('invoice_paysettle_id'=>$r->idtbl_account_paysettle_info, 'invoice_paysettle_narration'=>$r->narration, 
							'invoice_paysettle_amount'=>$r->amount,
							'invoice_paysettle_status'=>$r->status, 
							'paysettle_invoice_id'=>$r->invoiceno, 
							'invoice_paysettle_txt'=>$r->invoicetxt);
			
			if($r->status==1){
				$this->payment_detail_total += $r->amount;
			}
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
		$result['supplier_invoices']=rs_to_kv(new stdClass());
		$result['supplier_list']=rs_to_kv(get_supplier_list());
		
		$all_company_list = get_company_list();
		$result['company_list_filter']=rs_to_kv($all_company_list, '');
		$result['company_payment_list_filter']=$all_company_list;
		$result['branch_payment_list_filter']=get_all_company_branch_list();
		
		$result['company_bank_list']=get_bank_list();
		$result['company_bank_account_list']=get_bank_account_list();//company_bank_branch_list get_bank_branch_list();
		
		$this->load->view('account_payment_settle', $result);
	}
	
	public function create(){
		$companyid = $this->input->post('company_reg');
		$branchid = $this->input->post('branch_reg');
		$supplierid = $this->input->post('supplier_reg');
		$acc_paysettle_id = $this->input->post('acc_paysettle_id');
		$supplier_invoices = $this->IssuePaymentinfo->getPayableSupplierInvoices($companyid, $branchid, $supplierid);
		
		$account_period = get_account_period($companyid, $branchid);
		
		//var_dump($account_period);die;//echo $this->db->last_query();
		
		$account_period_txt = (!empty($account_period))?$account_period->desc.'-'.$account_period->monthname:'-';
		$account_period_master_id = (!empty($account_period))?$account_period->idtbl_master:'-1';
		
		$payment_header = $this->getProfile($acc_paysettle_id);
		$view_header_data = $payment_header[0];
		
		$view_detail_data = $this->getPaymentFigures($acc_paysettle_id);
		$payment_figures_total = $this->payment_detail_total;
		
		echo json_encode(array("account_period_txt"=>$account_period_txt, 
							   "account_period_id"=>$account_period_master_id, 
							   "supplier_invoices"=>$supplier_invoices,
							   "view_header_data"=>$view_header_data,
							   "view_detail_data"=>$view_detail_data, 
							   "payment_figures_total"=>$payment_figures_total)
						 );
	}
	
	public function toggle(){
		//date_default_timezone_set("Asia/Kolkata");
		$toggleTime = date("Y-m-d H:i:s");
		
		$userName = $_SESSION['userid'];//'1';
		
		echo json_encode(array("revise_user"=>$userName, "revise_time"=>$toggleTime, "toggle_status"=>"success"));
	}
	
	public function store(){
		//add-payment
		//validate-data
		$this->form_validation->set_rules('supplier_id', 'Supplier detail', 'required|callback_opt_check');
		//$this->form_validation->set_rules('supp_invoice', 'Invoice', 'required|callback_invoice_check');
		$this->form_validation->set_rules('supp_invoice_total', 'Invoice total value', 'required|greater_than[0]|callback_gt_draw');
		$this->form_validation->set_rules('payment_shortage', 'Invoice remaining value', 'callback_abs_match');//greater_than_equal_to[0]
		$this->form_validation->set_rules('payment_issue_date', 'Payment issue date', 'required');
		$this->form_validation->set_rules('paysettle_main_id', 'Invoice payments', 'callback_approve_check');//callback_post_check
		$this->form_validation->set_rules('acc_period', 'Account period', 'callback_accperiod_check');
		
		if($this->input->post('invoice_settle_amount')>0){
			$this->form_validation->set_rules('supplier_invoice_id', 'Invoice detail', 'required|callback_opt_check');
			$this->form_validation->set_rules('invoice_settle_narration', 'Narration', 'required');
		}
		
		if($this->input->post('supplier_invoice_id')!='-1'){
			$this->form_validation->set_rules('invoice_settle_amount', 'Payable value', 'required|callback_bal_check');
		}
		/*
		if($this->input->post('restored_rows')){
			//var_dump($this->input->post('restored_rows'));
			//$a = array_merge($this->input->post('restored_rows'))
			//die;
			$this->form_validation->set_rules('restored_rows[]', 'Invoice balance', 'callback_bal_check');//[]-pass-array-elements
		}
		*/
		if($this->input->post('restored_invoices')>0){
			$this->form_validation->set_rules('restored_invoices', 'Invoice balance', 'callback_bal_check');
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
				$validationErrors = form_error('supp_invoice_total', '<div class="errormsg">', '</div>');
			}
			if($validationErrors == ''){
				$validationErrors = form_error('payment_shortage', '<div class="errormsg">', '</div>');
			}
			if($validationErrors == ''){
				$validationErrors = form_error('payment_issue_date', '<div class="errormsg">', '</div>');
			}
			if($validationErrors == ''){
				$validationErrors = form_error('paysettle_main_id', '<div class="errormsg">', '</div>');
			}
			if($validationErrors == ''){
				$validationErrors = form_error('supplier_invoice_id', '<div class="errormsg">', '</div>');
			}
			if($validationErrors == ''){
				$validationErrors = form_error('invoice_settle_narration', '<div class="errormsg">', '</div>');
			}
			if($validationErrors == ''){
				$validationErrors = form_error('invoice_settle_amount', '<div class="errormsg">', '</div>');
			}
			if($validationErrors == ''){
				$validationErrors = form_error('restored_invoices', '<div class="errormsg">', '</div>');
			}
			
			$data = array();
			$data['resMsg'] = $validationErrors;
			$data['resTheme'] = 'bg-info text-white';
		}else{
			$companyId = $this->input->post('company_id');
			$companyBranchId = $this->input->post('company_branch_id');
			$supplierId = $this->input->post('supplier_id');
			//$suppInvoice = $this->input->post('supp_invoice');
			$suppInvoiceAmount = $this->input->post('supp_invoice_total');//x
			$suppInvoiceRemarks = $this->input->post('supp_invoice_remarks');//
			$paymentIssueDate = $this->input->post('payment_issue_date');//x
			$supplierInvoiceId = $this->input->post('supplier_invoice_id');//x
			$invoiceSettlelNarration = $this->input->post('invoice_settle_narration');//x
			$invoiceSettleAmount = $this->input->post('invoice_settle_amount');//x
			//$accDetailRunningTotal = $this->input->post('invoice_detail_running_total');//x
			$accPeriod = $this->input->post('acc_period');
			$paysettleMainId = $this->input->post('paysettle_main_id');//x
			$userName = $_SESSION['userid'];//'1';//$_SESSION['userid'];//$this->input->post('');
			
			//$ = $this->input->post('');
			$revisedStatusData = $this->input->post('revised_rows');
			//var_dump($this->input->post('revised_rows'));die;
			
			$paymentShortage = $this->input->post('payment_shortage');
			$payableMainStatus = (abs($paymentShortage)==0)?1:2;
			
			$headerData = array('date'=>$paymentIssueDate, 'supplier'=>$supplierId, 
								'totalpayment'=>$suppInvoiceAmount, 'remark'=>$suppInvoiceRemarks,
								'tbl_company_idtbl_company'=>$companyId, 'tbl_company_branch_idtbl_company_branch'=>$companyBranchId,
								'tbl_master_idtbl_master'=>$accPeriod, 'status'=>$payableMainStatus);
			
			$prevAccPayableDetail = array();
			
			//save-header-detail
			if($paysettleMainId!=''){
				$prevAccPayableProfile = $this->getProfile($paysettleMainId, $supplierInvoiceId);
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
				$payPrefix = pay_prefix($companyId, $companyBranchId);
				$generate_batchnum = tr_batch_num($payPrefix, $companyId);//tr_batch_num('AP202311', '1');
				$headerData['batchno'] = $generate_batchnum;
				$headerData['tbl_user_idtbl_user'] = $userName;
			}
			
			$detailData = array('batchno'=>$headerData['batchno'], 
								'amount'=>$invoiceSettleAmount, 
								'narration'=>$invoiceSettlelNarration, 'status'=>1, 
								'tbl_account_paysettle_idtbl_account_paysettle'=>$paysettleMainId, 
								'invoiceno'=>$supplierInvoiceId);//
							//, 'qrytime'=>'NOW()');
			//echo 'a--'.$supplierInvoiceId;//$prevAccPayableDetail['idtbl_account_paysettle_info'];
			$detailData_idtbl_account_paysettle_info = '';
			
			if(!empty($prevAccPayableDetail) && !empty($prevAccPayableDetail['idtbl_account_paysettle_info'])){
				$detailData = $this->trackChanges($detailData, $prevAccPayableDetail);
				$detailData['updateuser'] = $userName;
				$detailData['status'] = 1;
				$detailData_idtbl_account_paysettle_info = $prevAccPayableDetail['idtbl_account_paysettle_info'];
			}else{
				$detailData['tbl_user_idtbl_user'] = $userName;
			}
			//var_dump($detailData);die;
			$res_info = $this->IssuePaymentinfo->regUpdateOrder($headerData, $paysettleMainId, 
																  $revisedStatusData,
																  $detailData, $detailData_idtbl_account_paysettle_info
																  );
			
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
	
	public function approve(){
		//validation
		//check if revisestatus list is empty
		//chsck approve-status
		//check post-status
		//check header-total with detail-total - db level
		$this->form_validation->set_rules('exp_rows', 'Invoice payments', 'required');
		
		$this->form_validation->set_rules('revised_rows', 'Invoice payments', 'matches[exp_rows]', 
												array('matches'=>'Please save changes before approval.')
											);
		
		$this->form_validation->set_rules('supp_invoice_total', 'Invoice total payment', 'callback_doc_check');
		
		$this->form_validation->set_rules('paysettle_main_id', 
										  'Invoice payments', 
										  'callback_post_check|callback_approve_check|callback_payment_check'
										  );
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == false) {
			$validationErrors = form_error('revised_rows', '<div class="errormsg">', '</div>');
			
			if($validationErrors == ''){
				$validationErrors = form_error('supp_invoice_total', '<div class="errormsg">', '</div>');
			}
			
			if($validationErrors == ''){
				$validationErrors = form_error('paysettle_main_id', '<div class="errormsg">', '</div>');
			}
			
			$data = array();
			$data['resMsg'] = $validationErrors;
			$data['resTheme'] = 'bg-info text-white';
		}else{
			$paysettleMainId = $this->input->post('paysettle_main_id');//x
			$userName = $_SESSION['userid'];
			
			$approveData = array('completestatus'=>1);
			
			$res_info = $this->IssuePaymentinfo->regApproveOrder($approveData, $paysettleMainId);
			
			$data = array();
			$data['resMsg'] = $res_info['importMsg'];//'Successfully approved';
			$data['resTheme'] = $res_info['toastType'];//'bg-info text-white';
		}
		
		echo json_encode($data);
	}
	
	public function review(){
		$cheque_issue_total = 0;
		$orderRef = $this->input->post('paysettle_main_id');
		$paysettleHeader = $this->IssuePaymentinfo->getOrderHeader($orderRef, '');
		$approve_status = ($paysettleHeader->m_approvestatus==1)?1:0;
		$result=$this->IssuePaymentinfo->getChequeDetail($orderRef);
		$cheque_detail_data=array();
		
		
		foreach($result as $r){
			$cheque_detail_data[] = array('bank_name'=>$r->bankname, 'bank_branch_name'=>$r->branchname, 
							'bank_account_name'=>$r->accountname,
							'cheque_date'=>$r->chedate,
							'cheque_no'=>$r->chequeno, 
							'cheque_issue_narration'=>$r->narration, 
							'cheque_value'=>$r->amount);
			
			
			//if($r->status==1){
				$cheque_issue_total += $r->amount;
			//}
			
		}
		
		echo json_encode(array('cheque_issue_total'=>$cheque_issue_total,
							   'cheque_detail_data'=>$cheque_detail_data,
							   'approve_status'=>$approve_status)
						 );
	}
	
	public function draw(){
		$this->form_validation->set_rules('bank_id', 'Bank', 'callback_opt_check');
		$this->form_validation->set_rules('bank_branch_id', 'Bank branch', 'callback_opt_check');
		$this->form_validation->set_rules('cheque_value', 'Cheque value', 'required|greater_than[0]');
		$this->form_validation->set_rules('cheque_balance', 'Payment balance', 'callback_abs_match');//greater_than_equal_to[0]
		$this->form_validation->set_rules('cheque_issue_date', 'Cheque issue date', 'required');
		$this->form_validation->set_rules('cheque_issue_narration', 'Narration', 'required');
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == false) {
			$validationErrors = form_error('bank_id', '<div class="errormsg">', '</div>');
			
			if($validationErrors == ''){
				$validationErrors = form_error('bank_branch_id', '<div class="errormsg">', '</div>');
			}
			
			if($validationErrors == ''){
				$validationErrors = form_error('cheque_value', '<div class="errormsg">', '</div>');
			}
			
			if($validationErrors == ''){
				$validationErrors = form_error('cheque_issue_date', '<div class="errormsg">', '</div>');
			}
			
			if($validationErrors == ''){
				$validationErrors = form_error('cheque_issue_narration', '<div class="errormsg">', '</div>');
			}
			
			if($validationErrors == ''){
				$validationErrors = form_error('cheque_balance', '<div class="errormsg">', '</div>');
			}
			
			$data = array();
			$data['resMsg'] = $validationErrors;
			$data['resTheme'] = 'bg-info text-white';
		}else{
			$paysettleMainId = $this->input->post('paysettle_main_id');//x
			$userName = $_SESSION['userid'];
			$chequeIssueDate = $this->input->post('cheque_issue_date');
			$chequeIssueNarration = $this->input->post('cheque_issue_narration');
			$chequeValue = $this->input->post('cheque_value');
			
			$bankId = $this->input->post('bank_id');
			$bankBranchId = $this->input->post('bank_branch_id');
			$bankAccountId = $this->input->post('bank_account_id');
			
			$chequeData = array('chedate'=>$chequeIssueDate, 'chequeno'=>'-1', 'tbl_cheque_info_idtbl_cheque_info'=>'-1', 
								'narration'=>$chequeIssueNarration, 'amount'=>$chequeValue, 'status'=>'1', 
								'tbl_user_idtbl_user'=>$userName
							);
			
			$res_info = $this->IssuePaymentinfo->regSettleOrder($chequeData, $paysettleMainId, $bankId, $bankBranchId, $bankAccountId);
			
			$data = array();
			$data['resMsg'] = $res_info['importMsg'];//'Successfully approved';
			$data['resTheme'] = $res_info['toastType'];//'bg-info text-white';
			$data['cheque_no'] = $res_info['cheque_no'];
		}
		
		echo json_encode($data);
	}
	
	public function freeze(){
		//validate pay-tot with cheque-tot
		$this->form_validation->set_rules('selected_opt', 
									  'Customer payments', 
									  'callback_do_approve_check|callback_post_check|callback_payment_check|callback_crdr_check' // 
									);
		
		$this->form_validation->set_error_delimiters('', '');//'<div class="error">', '</div>'
		
		if($this->form_validation->run() == false) {
			$validationErrors = form_error('selected_opt', '', '');//'<div class="errormsg">', '</div>'
			
			$data = array();
			$data['resMsg'] = $validationErrors;
			$data['resTheme'] = 'bg-info text-white';
		}else{
			$payableMainId = $this->input->post('selected_opt');//x
			$userName = $_SESSION['userid'];
			
			$payment_header = $this->IssuePaymentinfo->getOrderHeader($payableMainId, '');
			$payment_detail = $this->IssuePaymentinfo->getChequeDetail($payableMainId);
			
			$transactionTime = date("Y-m-d H:i:s");
			$userName = $_SESSION['userid'];//'1';
			$companyId = $payment_header->tbl_company_idtbl_company;
			$branchId = $payment_header->tbl_company_branch_idtbl_company_branch;
			$masterId = $payment_header->tbl_master_idtbl_master;
			$crdrPrefix = trans_prefix($companyId, $branchId);
			$crdrBatchno = tr_batch_num($crdrPrefix, $branchId);
			$creditorAcc = $this->IssuePaymentinfo->getCreditorAccDetail();
			//var_dump($creditorAcc->idtbl_account);die;
			
			$seqNo = 1;
			
			$orderData = array('poststatus'=>1, 'postuser'=>$userName);
			$paymentRemark = $payment_header->remark;
			
			$crdrData = array(0=>array('tradate'=>$payment_header->issuedate, 'batchno'=>$crdrBatchno, 
									   'trabatchotherno'=>$payment_header->batchno, //*
									   'tratype'=>'I', 
									   'seqno'=>$seqNo, //*
									   'crdr'=>'C', 'accamount'=>$payment_header->m_amount, 
									   'narration'=>$paymentRemark,//'', 
									   'totamount'=>$payment_header->m_amount, 
									   'ismatched'=>'0', //*
									   'reversstatus'=>'0', //*
									   'status'=>'1', 
									   'insertdatetime'=>$transactionTime, 'tbl_user_idtbl_user'=>$userName, 
									   'tbl_account_idtbl_account'=>$creditorAcc->idtbl_account, 
									   'tbl_master_idtbl_master'=>$masterId, 
									   'tbl_company_idtbl_company'=>$companyId, 
									   'tbl_company_branch_idtbl_company_branch'=>$branchId)
							  );
			$crdrFull = array(0=>array('tradate'=>$payment_header->issuedate, 'batchno'=>$crdrBatchno, 
									   'tratype'=>'I', 'crdr'=>'C', 'accamount'=>$payment_header->m_amount, 
									   'narration'=>$paymentRemark,//'', 
									   'totamount'=>$payment_header->m_amount, 
									   'ismatch'=>'0', //*
									   'status'=>'1', 
									   'insertdatetime'=>$transactionTime, 'tbl_user_idtbl_user'=>$userName, 
									   'tbl_account_idtbl_account'=>$creditorAcc->idtbl_account, 
									   'tbl_master_idtbl_master'=>$masterId, 
									   'tbl_company_idtbl_company'=>$companyId, 
									   'tbl_company_branch_idtbl_company_branch'=>$branchId)
							  );
			
			$bankData = $crdrData[0];
			$bankData['tratype'] = 'I';
			$bankData['seqno'] = '';
			$bankData['crdr'] = 'D';
			$bankData['accamount'] = '';
			$bankData['narration'] = '';
			//$bankData['totamount'] = '';
			$bankData['tbl_account_idtbl_account'] = '';
			
			$bankFull = $crdrFull[0];
			$bankFull['tratype'] = 'I';
			$bankFull['crdr'] = 'D';
			$bankFull['accamount'] = '';
			$bankFull['narration'] = '';
			//$bankFull['totamount'] = '';
			$bankFull['tbl_account_idtbl_account'] = '';
			
			foreach($payment_detail as $pd){
				$accIndex = $pd->idtbl_account;
				
				if(!isset($crdrData[$pd->idtbl_account])){
					$seqNo++;
					
					$crdrData[$accIndex] = $bankData;
					$crdrData[$accIndex]['seqno'] = $seqNo;
					$crdrData[$accIndex]['accamount'] = $pd->amount;
					$crdrData[$accIndex]['narration'] = $pd->narration;//'';
					//$crdrData[$accIndex]['totamount'] = '';
					$crdrData[$accIndex]['tbl_account_idtbl_account'] = $accIndex;
					
					$crdrFull[$accIndex] = $bankFull;
					$crdrFull[$accIndex]['accamount'] = $pd->amount;
					$crdrFull[$accIndex]['narration'] = $pd->narration;//'';
					//$crdrFull[$accIndex]['totamount'] = '';
					$crdrFull[$accIndex]['tbl_account_idtbl_account'] = $accIndex;
				}else{
					$accAmount = $crdrData[$accIndex]['accamount']+$pd->amount;
					$crdrData[$accIndex]['accamount'] = $accAmount;
					$crdrFull[$accIndex]['accamount'] = $accAmount;
				}
			}
			
			
			$res_info = $this->IssuePaymentinfo->regFreezeOrder($payableMainId, $orderData, $crdrData, $crdrFull);
			
			$data = array();
			$data['resMsg'] = $res_info['importMsg'];//'Successfully approved';
			$data['resTheme'] = $res_info['toastType'];//'bg-info text-white';
		}
		
		echo json_encode($data);
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
	
	public function approve_check($field_val){
		$result=$this->IssuePaymentinfo->getOrderHeader($field_val, '');
		
		$approve_status = false;
		
		if(!empty($result)){
			$approve_status = ($result->m_approvestatus=='1');
		}
		
		if($approve_status){
			$this->form_validation->set_message('approve_check', 'This payment is already approved');
			return false;
		}
		
		return true;
	}
	
	public function do_approve_check($field_val){
		$result=$this->IssuePaymentinfo->getOrderHeader($field_val, '');
		
		$do_approve_status = true;//false;
		
		if(!empty($result)){
			$do_approve_status = ($result->m_approvestatus=='0');
		}
		
		if($do_approve_status){
			$this->form_validation->set_message('do_approve_check', 'This payment must be approved');
			return false;
		}
		
		return true;
	}
	
	public function post_check($field_val){
		$result=$this->IssuePaymentinfo->getOrderHeader($field_val, '');
		
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
	
	public function payment_check($field_val){
		$result=$this->IssuePaymentinfo->verifyPaymentTotal($field_val);
		
		$payment_ready_status = false;
		
		if(!empty($result)){
			$payment_ready_status = ($result=='1');
		}
		
		if(!$payment_ready_status){
			$this->form_validation->set_message('payment_check', 'This payment is incomplete. (not matching total payment)');
			return false;
		}
		
		return true;
	}
	
	public function doc_check($field_val){
		$order_id = $this->input->post('paysettle_main_id');
		
		$result=$this->IssuePaymentinfo->getOrderHeader($order_id, '');
		
		$head_status = false;
		
		if(!empty($result)){
			$head_status = ($result->m_amount!=$field_val);
		}
		
		if($head_status){
			$this->form_validation->set_message('doc_check', 'Changes of invoice total payment has to be updated');
			return false;
		}
		
		return true;
	}
	
	public function crdr_check($field_val){
		$result=$this->IssuePaymentinfo->verifyCrDrTotal($field_val);
		
		$payment_ready_status = false;
		
		if(!empty($result)){
			$payment_ready_status = ($result=='1');
		}
		
		if(!$payment_ready_status){
			$this->form_validation->set_message('crdr_check', 'This payment is incomplete. (not matching cheque payment)');
			return false;
		}
		
		return true;
	}
	
	public function gt_draw($field_val){
		$result=$this->IssuePaymentinfo->getTotalDrawValue($this->input->post('paysettle_main_id'));
		
		$approve_status = false;
		
		if(!empty($result)){
			$approve_status = ($field_val>=$result->total_draw);
		}
		
		if(!$approve_status){
			$this->form_validation->set_message('gt_draw', 'Total payment must be higher/equal to cheque total');
			return false;
		}
		
		return true;
	}
	/*
	public function invoice_check($field_val){
		$order_id=$this->input->post('paysettle_main_id');//order-regno
		
		$result='';//$this->AccountPaymentinfo->getOrderHeaderByInvoice($field_val);
		
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
	*/
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
	
	public function abs_match($field_val){
		$opt_valid = true;
		
		if(!empty($field_val)){
			$opt_valid = ($field_val===number_format((float)abs($field_val), 2, '.', ''));
		}
		
		if(!$opt_valid){
			$this->form_validation->set_message('abs_match', 'Invalid '.$field_val.' for {field}');
			return false;
		}
		
		return true;
	}
	
	public function bal_check($field_val){
		if(($this->input->post('restored_invoices')>0)&&($this->input->post('supplier_invoice_id')!='-1')){
			return true;
		}
		
		//echo $field_val.'-';var_dump($field_val);
		
		$restoredRows = $this->input->post('restored_rows');//invoice-ids-of-selected-checkboxes
		$revisedInvoiceId = $this->input->post('supplier_invoice_id');//drop-down-selected-invoice-id
		$revisedInvoiceVal = (float)$this->input->post('invoice_settle_amount');//new-value-for-drop-down-selected-invoice
		
		$paysettleMainId = $this->input->post('paysettle_main_id');
		$supplierInvoiceId = $this->input->post('supplier_invoice_id');
		$paysettleProfile = $this->getProfile($paysettleMainId, $supplierInvoiceId);
		$paysettleId = ($paysettleProfile[1]['idtbl_account_paysettle_info']=='')?'-1':$paysettleProfile[1]['idtbl_account_paysettle_info'];

		$result=$this->IssuePaymentinfo->verifyPaymentLimit($restoredRows, $revisedInvoiceId, $revisedInvoiceVal, $paysettleId);
		
		$payment_ready_status = (count($result)=='0');//false;
		
		if(!$payment_ready_status){
			$this->form_validation->set_message('bal_check', 'Invoice payment exceeded');
			return false;
		}
		
		return true;
	}
    
}