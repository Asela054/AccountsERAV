<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class BankReconciliation extends CI_Controller {
    
	private $cr_running_total, $dr_running_total;
	
	public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("BankReconciliationinfo");
    }
    
	private function getProfile($bankacc_id, $bankrec_id=''){
		$result = ($bankrec_id=='')?$this->BankReconciliationinfo->getAccountHeader($bankacc_id):
												$this->BankReconciliationinfo->getOrderHeader($bankrec_id);
		
		$data = array('idtbl_bank_rec_list'=>'', 'tbl_account_idtbl_account'=>$bankacc_id, 
					  'tbl_finacial_year_idtbl_finacial_year'=>'', 'tbl_finacial_month_idtbl_finacial_month'=>'',
					  'bank_rec_date'=>'', 'statement_open_bal'=>'0.00', 'statement_closed_bal'=>'0.00', 
					  'statement_tot_cr'=>'0.00', 'statement_tot_dr'=>'0.00',
					  'acc_open_bal'=>'0.00',
					  'status'=>1, 'rec_approved'=>0,
					  'acc_rec_batchno'=>'');
		
		if(!empty($result)){
			$data['idtbl_bank_rec_list'] = $result->idtbl_bank_rec_list;
			$data['tbl_finacial_year_idtbl_finacial_year'] = $result->tbl_finacial_year_idtbl_finacial_year;
			$data['tbl_finacial_month_idtbl_finacial_month'] = $result->tbl_finacial_month_idtbl_finacial_month;
			$data['bank_rec_date'] = $result->bank_rec_date;
			$data['statement_open_bal'] = $result->statement_open_bal;
			$data['statement_closed_bal'] = $result->statement_closed_bal;
			$data['statement_tot_cr'] = $result->statement_tot_cr;
			$data['statement_tot_dr'] = $result->statement_tot_dr;
			$data['acc_open_bal'] = $result->acc_open_bal;
			$data['status'] = $result->status;
			$data['rec_approved'] = $result->rec_approved;
			$data['acc_rec_batchno'] = $result->rec_batchno;
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
		//echo $prevOrderProfile['acc_rec_batchno'];die;
		//setting-batchno-to-verify-on-modal.reg_update_order
		$orderProfileChanges['acc_rec_batchno'] = $prevOrderProfile['acc_rec_batchno'];
		
		return $orderProfileChanges;
	}
	
	private function getRecTransactions($account_id, $bankrec_id, $batch_id, $bankrec_year, $bankrec_month, $matched_flag, $checked_flag=NULL){
		$result = $this->BankReconciliationinfo->getOrderDetail($account_id, $bankrec_id, $batch_id, $bankrec_year, $bankrec_month, $matched_flag, $checked_flag);
		//$result = $this->BankReconciliationinfo->getOrderDetail(2, $bankrec_id, $batch_id, $bankrec_year, 15);
		
		$data = array();
		
		$this->cr_running_total = 0;
		$this->dr_running_total = 0;
		
		foreach($result as $r){
			$rec_revise_status = ($r->rec_info_status!=$r->rec_revise_status)?1:0;
			
			$data[] = array('transaction_id'=>$r->transaction_id, 'rec_info_id'=>$r->rec_info_id, 
							'rec_revision_id'=>'',
							'rec_info_status'=>$r->rec_info_status,
							'acc_period_txt'=>$r->acc_period_txt, 'narration_txt'=>$r->narration_txt, 
							'transaction_date'=>$r->transaction_date, 
							'cr_val'=>$r->cr_val, 'dr_val'=>$r->dr_val, 
							'rec_revise_status'=>$rec_revise_status,
							'opt_render'=>'chkinput', 
							'opt_origin'=>$r->rec_info_origin_name,
							'opt_dtprefix'=>'chk_accd');
			
			if($r->rec_info_status==1){
				$this->cr_running_total += $r->cr_val;
				$this->dr_running_total += $r->dr_val;
			}
		}
		
		$revision = $this->BankReconciliationinfo->getBankRevisionDetail($bankrec_id, $account_id);
		//$revision = $this->BankReconciliationinfo->getBankRevisionDetail($bankrec_id, 10);
		
		foreach($revision as $o){
			$rec_revise_status = 0;
			
			$data[] = array('transaction_id'=>'', 'rec_info_id'=>'', 
							'rec_revision_id'=>$o->idtbl_bank_rec_revision,
							'rec_info_status'=>1,
							'acc_period_txt'=>'-', 'narration_txt'=>$o->bank_narration, 
							'transaction_date'=>'-', 
							'cr_val'=>$o->cr_val, 'dr_val'=>$o->dr_val, 
							'rec_revise_status'=>0,
							'opt_render'=>'btn', 
							'opt_origin'=>'origin_blank',
							'opt_dtprefix'=>'rec_accd');
			
			
			$this->cr_running_total += $o->cr_val;
			$this->dr_running_total += $o->dr_val;
			
		}
		
		$receivable_details = $this->BankReconciliationinfo->getBankDepositDetails($bankrec_id, $bankrec_year, $bankrec_month, $matched_flag);
		
		foreach($receivable_details as $d){
			$rec_revise_status = ($d->rec_info_status!=$d->rec_revise_status)?1:0;
			
			$data[] = array('transaction_id'=>$d->idtbl_receivable, 'rec_info_id'=>$d->rec_info_id, 
							'rec_revision_id'=>'',
							'rec_info_status'=>$d->rec_info_status,
							'acc_period_txt'=>$d->acc_period_txt, 'narration_txt'=>$d->narration, 
							'transaction_date'=>$d->recdate, 
							'cr_val'=>$d->cr_val, 'dr_val'=>$d->dr_val, 
							'rec_revise_status'=>$rec_revise_status,
							'opt_render'=>'chkinput', 
							'opt_origin'=>$d->rec_info_origin_name,
							'opt_dtprefix'=>'dep_accd');
			
			if($d->rec_info_status==1){
				$this->cr_running_total += $d->cr_val;
				$this->dr_running_total += $d->dr_val;
			}
		}
		
		return $data;
	}
	
	public function view(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		
		$result['company_bank_account_list']=get_bank_account_list();
		$result['main_accounts']=$this->BankReconciliationinfo->getNonBankAccounts();
		$this->load->view('bank_reconciliation_view', $result);
	}
	
	public function create(){
		$bankrec_id = $this->input->post('main_id');
		$bankacc_id = $this->input->post('bankacc_id');
		
		$bank_rec_header = $this->getProfile($bankacc_id, $bankrec_id);
		$bankrec_id = $bank_rec_header['idtbl_bank_rec_list'];//var_dump($bank_rec_header);die;
		$id_y = $bank_rec_header['tbl_finacial_year_idtbl_finacial_year'];
		$id_m = $bank_rec_header['tbl_finacial_month_idtbl_finacial_month'];
		$bank_rec_period_txt = $this->BankReconciliationinfo->getRecPeriodDescription($id_y, $id_m);
		$rec_batchno = $bank_rec_header['acc_rec_batchno'];
		
		$bank_rec_master_ids = $this->BankReconciliationinfo->getCompanyBranchAccountPeriods($id_y, $id_m);
		
		$checked_flag = empty($bankrec_id)?NULL:0;
		
		$view_detail_data = $this->getRecTransactions($bankacc_id, $bankrec_id, $rec_batchno, $id_y, $id_m, $bank_rec_header['rec_approved'], $checked_flag);
		
		echo json_encode(array('bank_rec_period_txt'=>$bank_rec_period_txt, 
							   'view_header_data'=>$bank_rec_header, 
							   'acc_tot_cr'=>$this->cr_running_total, 'acc_tot_dr'=>$this->dr_running_total,
							   'acc_periods'=>$bank_rec_master_ids, 'view_detail_data'=>$view_detail_data)
						 );
	}
	
	public function store(){
		$this->form_validation->set_rules('bank_rec_date', 'Reconciliation date', 'required');
		$this->form_validation->set_rules('rec_acc_id', 'Account details', 'required|callback_opt_check');
		$this->form_validation->set_rules('statement_open_bal', 'Statement opening balance', 'required');
		$this->form_validation->set_rules('statement_closed_bal', 'Statement closing balance', 'required');
		
		if($this->input->post('rec_main_id')==''){
			$this->form_validation->set_rules('rec_period_year', 'Accounting year', 'required');
			$this->form_validation->set_rules('rec_period_month', 'Accounting month', 'required|callback_statement_check');
		}else{
			$this->form_validation->set_rules('rec_main_id', 'Statement', 'callback_approved_check');
		}
		
		if($this->input->post('bank_amount')>0){
			$this->form_validation->set_rules('main_account_id', 'Account detail', 'required|callback_opt_check');
			$this->form_validation->set_rules('main_account_narration', 'Narration', 'required');
		}
		
		if($this->input->post('main_account_id')!='-1'){
			$this->form_validation->set_rules('bank_amount', 'Accountable value', 'required');
		}
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == false) {
			$validationErrors = form_error('bank_rec_date', '<div class="errormsg">', '</div>');
			
			if($validationErrors == ''){
				$validationErrors = form_error('rec_acc_id', '<div class="errormsg">', '</div>');
			}
			
			if($validationErrors == ''){
				$validationErrors = form_error('statement_open_bal', '<div class="errormsg">', '</div>');
			}
			if($validationErrors == ''){
				$validationErrors = form_error('statement_closed_bal', '<div class="errormsg">', '</div>');
			}
			if($validationErrors == ''){
				$validationErrors = form_error('rec_period_year', '<div class="errormsg">', '</div>');
			}
			if($validationErrors == ''){
				$validationErrors = form_error('rec_period_month', '<div class="errormsg">', '</div>');
			}
			if($validationErrors == ''){
				$validationErrors = form_error('rec_main_id', '<div class="errormsg">', '</div>');
			}
			
			if($validationErrors == ''){
				$validationErrors = form_error('main_account_id', '<div class="errormsg">', '</div>');
			}
			if($validationErrors == ''){
				$validationErrors = form_error('main_account_narration', '<div class="errormsg">', '</div>');
			}
			if($validationErrors == ''){
				$validationErrors = form_error('bank_amount', '<div class="errormsg">', '</div>');
			}
			
			$data = array();
			$data['resMsg'] = $validationErrors;
			$data['resTheme'] = 'bg-info text-white';
		}else{
			$bankRecDate = $this->input->post('bank_rec_date');
			$statementOpenBal = $this->input->post('statement_open_bal');
			$statementTotCr = $this->input->post('statement_tot_cr');
			$statementTotDr = $this->input->post('statement_tot_dr');
			$statementClosedBal = $this->input->post('statement_closed_bal');
			$recAccId = $this->input->post('rec_acc_id');
			$recPeriodYear = $this->input->post('rec_period_year');
			$recPeriodMonth = $this->input->post('rec_period_month');
			$recMainId = $this->input->post('rec_main_id');
			
			$mainAccountId = $this->input->post('main_account_id');
			$accountCr = $this->input->post('account_cr');
			$accountDr = $this->input->post('account_dr');
			$accPeriod = $this->input->post('acc_period');
			$mainAccountNarration = $this->input->post('main_account_narration');
			$bankAmount = $this->input->post('bank_amount');
			
			$userName = $_SESSION['userid'];
			
			$revisedStatusData = $this->input->post('revised_rows');
			
			$headerData = array('tbl_account_idtbl_account'=>$recAccId, 'tbl_finacial_year_idtbl_finacial_year'=>$recPeriodYear, 
								'tbl_finacial_month_idtbl_finacial_month'=>$recPeriodMonth, 
								'bank_rec_date'=>$bankRecDate, 'statement_open_bal'=>$statementOpenBal, 
								'statement_tot_cr'=>$statementTotCr, 'statement_tot_dr'=>$statementTotDr, 
								'statement_closed_bal'=>$statementClosedBal, 'status'=>1);
			
			$recApproved = 0;
			
			if($recMainId!=''){
				$prevAccRecProfile = $this->getProfile($recAccId, $recMainId);
				$recApproved = $prevAccRecProfile['rec_approved'];
				$headerData = $this->trackChanges($headerData, $prevAccRecProfile);
				$headerData['updateuser'] = $userName;
			}else{
				$recPrefix = bankrec_prefix($recPeriodYear, $recPeriodMonth);
				$generate_batchnum = tr_batch_num($recPrefix, 1);//tr_batch_num('AP202311', '1');
				$headerData['acc_rec_batchno'] = $generate_batchnum;
				$headerData['tbl_user_idtbl_user'] = $userName;
			}
			
			
			$bankDetailData = array();/*array('tbl_account_idtbl_account'=>'-1');*/
			
			if($mainAccountId!='-1'){
				$bankDetailData = array('tbl_bank_rec_list_idtbl_bank_rec_list'=>'',
										'tbl_account_idtbl_account_cr'=>$accountCr, 'tbl_account_idtbl_account_dr'=>$accountDr,
										'bank_narration'=>$mainAccountNarration, 
										'bank_amount'=>$bankAmount, 'status'=>'1',
										'tbl_user_idtbl_user'=>$userName, 'tbl_master_idtbl_master'=>$accPeriod);
			}
			
			
			$res_info = $this->BankReconciliationinfo->regUpdateOrder($headerData, $recMainId, $revisedStatusData, $bankDetailData);
			
			$data = array();
			
			$data['resMsg'] = $res_info['importMsg'];
			$data['resTheme'] = $res_info['toastType'];
			
			$data['head_k'] = $res_info['head_k'];
			$data['sub_k'] = $res_info['sub_k'];
			
			$recMainId = $res_info['head_k'];//assign new id
			
			$data['view_detail_data'] = ($res_info['rd_cnt']==0)?'-1':$this->getRecTransactions($recAccId, 
														$recMainId, $headerData['acc_rec_batchno'], $recPeriodYear, $recPeriodMonth, 
														$recApproved, 0
													);
		}
		
		echo json_encode($data);
			
	}
	
	public function approved_check($field_val){
		$result=$this->BankReconciliationinfo->getOrderHeader($field_val, '');
		
		$approved_status = false;
		
		if(!empty($result)){
			$approved_status = ($result->rec_approved=='1');
		}
		
		if($approved_status){
			$this->form_validation->set_message('approved_check', 'This statement already approved');
			return false;
		}
		
		return true;
	}
	
	public function statement_check($field_val){
		$rec_year=$this->input->post('rec_period_year');//
		$rec_accno=$this->input->post('rec_acc_id');
		
		$result=$this->BankReconciliationinfo->getRecByYearMonth($rec_accno, $rec_year, $field_val);
		
		$duplicate_code = false;
		
		if(!empty($result)){
			$duplicate_code = true;//($result->idtbl_account_payable_main!=$order_id);
		}
		
		if($duplicate_code){
			$this->form_validation->set_message('statement_check', 'This account already has another reconciliation for this period');
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
	
	public function diff_check($field_val){
		$rec_ready = false;
		
		$s_cr = $this->input->post('statement_cr');
		$a_cr = $this->input->post('acc_cr');
		
		if(($s_cr-$a_cr)!=0){
			$this->form_validation->set_message('diff_check', 'CR diffenerence must be 0');
			return false;
		}
		
		$s_dr = $this->input->post('statement_dr');
		$a_dr = $this->input->post('acc_dr');
		
		if(($s_dr-$a_dr)!=0){
			$this->form_validation->set_message('diff_check', 'DR diffenerence must be 0');
			return false;
		}
		
		$s_close = $this->input->post('statement_close');
		$a_close = $this->input->post('acc_close');
		
		if(($s_close-$a_close)!=0){
			$this->form_validation->set_message('diff_check', 'Closed balance diffenerence must be 0');
			return false;
		}
		
		return true;
	}
	
	public function doc_check($field_val){
		$order_id = $this->input->post('selected_opt');
		
		$result=$this->BankReconciliationinfo->getOrderHeader($order_id);
		
		$head_status = false;
		$doc_msg = '';
		
		if(!empty($result)){
			$sOpen = $this->input->post('statement_open_bal');
			$sCrval = $this->input->post('statement_cr');
			$sDrval = $this->input->post('statement_dr');
			$sClose = $this->input->post('statement_close');
			
			if($result->statement_open_bal!=$sOpen){
				$head_status = true;
				$doc_msg = "Changes of statement open balance must be updated";
			}
			
			
			else if($result->statement_tot_cr!=$sCrval){
				$head_status = true;
				$doc_msg = "Changes of statement CR total must be updated";
			}
			
			
			else if($result->statement_tot_dr!=$sDrval){
				$head_status = true;
				$doc_msg = "Changes of statement DR total must be updated";
			}
			
			
			else if($result->statement_closed_bal!=$sClose){
				$head_status = true;
				$doc_msg = "Changes of statement closed balance must be updated";
			}
		}
		
		if($head_status){
			$this->form_validation->set_message('doc_check', $doc_msg);
			return false;
		}
		
		return true;
	}
	
	public function freeze(){
		$this->form_validation->set_rules('exp_rows', 'Rec info', 'required');
		
		$this->form_validation->set_rules('revised_rows', 'Rec info', 'matches[exp_rows]', 
												array('matches'=>'Please save changes before posting.')
											);
		
		$this->form_validation->set_rules('selected_opt', 
										  'Invoice payments', 
										  'callback_approved_check|callback_doc_check|callback_diff_check'
										  );
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == false) {
			$validationErrors = form_error('revised_rows', '<div class="errormsg">', '</div>');
			
			if($validationErrors == ''){
				$validationErrors = form_error('selected_opt', '<div class="errormsg">', '</div>');
			}
			
			$data = array();
			$data['action'] = json_encode(array('message'=>$validationErrors));//resMsg
			$data['resTheme'] = 'bg-info text-white';
		}else{
			$recMainId = $this->input->post('selected_opt');//x
			$userName = $_SESSION['userid'];
			$transactionTime = date('Y-m-d H:i:s');
			$orderData = array('rec_approved'=>1, 'updateuser'=>$userName);
			
			$transaction_details = $this->BankReconciliationinfo->generateBankTransactions($recMainId);
			
			$bankRecDate = '';
			$bankRecBatchno = '';
			
			if(!empty($transaction_details)){
				$bankRecDate = $transaction_details[0]->bank_rec_date;
				$bankRecBatchno = $transaction_details[0]->acc_rec_batchno;
			}
			
			$bankData = array('tradate'=>$bankRecDate, 'batchno'=>$bankRecBatchno, 
									   'trabatchotherno'=>'', //*
									   'tratype'=>'D', 
									   'seqno'=>'', //*
									   'crdr'=>'', 'accamount'=>'', 'narration'=>'', 
									   'totamount'=>'', 
									   'ismatched'=>'1', //'0', //*
									   'reversstatus'=>'0', //*
									   'status'=>'1', 
									   'insertdatetime'=>$transactionTime, 'tbl_user_idtbl_user'=>$userName, 
									   'tbl_account_idtbl_account'=>'', 
									   'tbl_master_idtbl_master'=>'', 
									   'tbl_company_idtbl_company'=>'', 
									   'tbl_company_branch_idtbl_company_branch'=>''
							  );
			$bankFull = array('tradate'=>$bankRecDate, 'batchno'=>$bankRecBatchno, 
									   'tratype'=>'D', 'crdr'=>'', 'accamount'=>'', 'narration'=>'', 
									   'totamount'=>'', 
									   'ismatch'=>'1', //'0', //*
									   'status'=>'1', 
									   'insertdatetime'=>$transactionTime, 'tbl_user_idtbl_user'=>$userName, 
									   'tbl_account_idtbl_account'=>'', 
									   'tbl_master_idtbl_master'=>'', 
									   'tbl_company_idtbl_company'=>'', 
									   'tbl_company_branch_idtbl_company_branch'=>''
							  );
			
			$crdrData = array();
			$crdrFull = array();
			
			$crdrOpts = array('C'=>array('p'=>1), 'D'=>array('p'=>-1));
			
			$accOpenDiff = array();
			
			foreach($transaction_details as $bt){
				$bankData['trabatchotherno'] = $bt->b_prefix.str_pad($bt->idtbl_bank_rec_revision, 14, '0', STR_PAD_LEFT);
				$bankData['seqno'] = $bt->seqno;
				$bankData['crdr'] = $bt->fig_crdr;
				$bankData['accamount'] = $bt->bank_amount;
				$bankData['narration'] = $bt->bank_narration;
				$bankData['totamount'] = $bt->totamount;
				$bankData['tbl_account_idtbl_account'] = $bt->tbl_account_idtbl_account;
				$bankData['tbl_master_idtbl_master'] = $bt->tbl_master_idtbl_master;
				$bankData['tbl_company_idtbl_company'] = $bt->tbl_company_idtbl_company;
				$bankData['tbl_company_branch_idtbl_company_branch'] = $bt->tbl_company_branch_idtbl_company_branch;
				
				$crdrData[] = $bankData;
				
				$newAmount = $bt->bank_amount*$crdrOpts[$bt->fig_crdr]['p'];
				$optAccount = md5($bt->tbl_account_idtbl_account.'_'.$bt->tbl_company_branch_idtbl_company_branch);
				
				if(isset($accOpenDiff[$optAccount])){
					$preAmount = $accOpenDiff[$optAccount];
					$accOpenDiff[$optAccount] = $preAmount+$newAmount;
				}else{
					$accOpenDiff[$optAccount] = $newAmount;
				}
				
				//$bankFull[''] = str_pad($bt->idtbl_bank_rec_revision, 15, '0', STR_PAD_LEFT);
				$bankFull['crdr'] = $bt->fig_crdr;
				$bankFull['accamount'] = $bt->bank_amount;
				$bankFull['narration'] = $bt->bank_narration;
				$bankFull['totamount'] = $bt->totamount;
				$bankFull['tbl_account_idtbl_account'] = $bt->tbl_account_idtbl_account;
				$bankFull['tbl_master_idtbl_master'] = $bt->tbl_master_idtbl_master;
				$bankFull['tbl_company_idtbl_company'] = $bt->tbl_company_idtbl_company;
				$bankFull['tbl_company_branch_idtbl_company_branch'] = $bt->tbl_company_branch_idtbl_company_branch;
				
				$crdrFull[] = $bankFull;
			}
			
			$recPeriodYear = $this->input->post('rec_period_year');
			$recPeriodMonth = $this->input->post('rec_period_month');
			$acc_unconfirmed_details = $this->BankReconciliationinfo->getUnconfirmedAccountBalance($recPeriodYear, $recPeriodMonth);
			$accOpenDiffUpdateData = array();
			
			foreach($acc_unconfirmed_details as $au){
				if(isset($accOpenDiff[$au->m_key])){
					$newAccBal = $au->openbal+$accOpenDiff[$au->m_key];
					$accOpenDiffUpdateData[] = array('idtbl_account_open_bal'=>$au->idtbl_account_open_bal, 
												   'openbal'=>$newAccBal, 'updateuser'=>$userName, 
												   'updatedatetime'=>$transactionTime);
				}
			}
			
			//var_dump($accOpenDiffUpdateData);
			//die;
			
			/**/
			$res_info = $this->BankReconciliationinfo->regFreezeOrder($recMainId, $orderData, $crdrData, $crdrFull, $accOpenDiffUpdateData);//
			
			
			
			$data = array();
			$data['resMsg'] = $res_info['importMsg'];//'Successfully approved';
			$data['resTheme'] = $res_info['toastType'];//'bg-info text-white';
		}
		
		echo json_encode($data);
	}
    
	public function destroy(){
		$data = array('msgErr'=>true);
		
		//if(canSave(self::UI_CODE)){
			$revisionNo=$this->input->post('item_ref');
			
			
			$res_info=$this->BankReconciliationinfo->regAlterOrder($revisionNo);
			
			$data['resMsg'] = $res_info['importMsg'];
			$data['resTheme'] = $res_info['toastType'];
			
			$data['msgErr'] = $res_info['msgErr'];
		/*
		}else{
			$data['resMsg'] = "You are not authorized to alter purchase order details";
			$data['resTheme'] = 'bg-info text-white';
			//$this->session->set_userdata($data);
		}
		*/
		
		echo json_encode($data);
	}
	
}