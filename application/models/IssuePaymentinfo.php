<?php
class IssuePaymentinfo extends CI_Model{
    public function getCreditorAccDetail(){
		$this->db->select('idtbl_account, code, accountno, accountname');
		$this->db->from('tbl_account');
		$this->db->where('specialcate', 34);//1
		return $this->db->get()->row();
	}
	
	public function getOrderHeader($orderRef, $accDetailRef){//x
		$sql = "select drvm.idtbl_account_paysettle, drvm.date as issuedate, drvm.batchno, drvm.supplier, drvm.totalpayment as m_amount, ";
		$sql .= "drvm.remark, ";
		$sql .= "drvm.tbl_company_idtbl_company, drvm.tbl_company_branch_idtbl_company_branch, drvm.tbl_master_idtbl_master, ";
		$sql .= "drvm.status as m_status, drvm.completestatus as m_approvestatus, drvm.poststatus as m_poststatus, ";
		$sql .= "drvo.idtbl_account_paysettle_info, drvo.amount as o_amount, drvo.narration as o_narration, drvo.status as o_status, ";
		$sql .= "drvo.invoiceno ";
		$sql .= "from tbl_account_paysettle AS drvm ";
		$sql .= "left outer join (select idtbl_account_paysettle_info, amount, narration, status, invoiceno, tbl_account_paysettle_idtbl_account_paysettle from tbl_account_paysettle_info where tbl_account_paysettle_idtbl_account_paysettle=? and invoiceno=?) as drvo on ";
		$sql .= "drvm.idtbl_account_paysettle=drvo.tbl_account_paysettle_idtbl_account_paysettle ";
		$sql .= "where drvm.idtbl_account_paysettle=?";
    	$result = $this->db->query($sql, array($orderRef, $accDetailRef, $orderRef));
		$row = $result->row();
		return $row;
	}
	
	public function verifyPaymentTotal($orderRef){
		$sql = "SELECT count(*) as c_match FROM `tbl_account_paysettle` cross join (select SUM(amount) as d_amount from tbl_account_paysettle_info where tbl_account_paysettle_idtbl_account_paysettle=? AND status=1) as drv_inv WHERE `tbl_account_paysettle`.`idtbl_account_paysettle`=? AND `tbl_account_paysettle`.`totalpayment`=drv_inv.d_amount";
		$result = $this->db->query($sql, array($orderRef, $orderRef));
		$row = $result->row();
		return $row->c_match;
	}
	
	public function verifyCrDrTotal($orderRef){
		$sql = "SELECT count(*) as c_match FROM `tbl_account_paysettle` cross join (select SUM(tbl_cheque_issue.amount) as d_amount from (select tbl_cheque_issue_idtbl_cheque_issue as idtbl_cheque_issue from tbl_account_paysettle_has_tbl_cheque_issue where tbl_account_paysettle_idtbl_account_paysettle=?) as drv_i inner join tbl_cheque_issue ON drv_i.idtbl_cheque_issue=tbl_cheque_issue.idtbl_cheque_issue where tbl_cheque_issue.status=1) as drv_inv WHERE `tbl_account_paysettle`.`idtbl_account_paysettle`=? AND `tbl_account_paysettle`.`totalpayment`=drv_inv.d_amount";
		$result = $this->db->query($sql, array($orderRef, $orderRef));
		$row = $result->row();
		return $row->c_match;
	}
	
	public function verifyPaymentLimit($revisedRows, $revisedInvoiceId, $revisedInvoiceVal, $paysettleId){
		$revisedRowsAll = array_merge((array)$revisedRows, array($revisedInvoiceId));
		$this->db->select('d.invoiceno as form_key');
		
		//$this->db->select_sum('d.amount', 'form_val');
		$this->db->select_sum('(d.amount*(d.status=1))+(d.amount*(d.status=2)*(h.completestatus is null))', 'form_val');
		
		$this->db->from('tbl_account_paysettle_info as d');
		$this->db->join('tbl_account_paysettle as h', 'd.tbl_account_paysettle_idtbl_account_paysettle=h.idtbl_account_paysettle');
		
		//exclude existing record from sum of invoice group
		$this->db->where('d.idtbl_account_paysettle_info <>', $paysettleId);
		
		$this->db->where_in('d.invoiceno', $revisedRowsAll);
		$this->db->where_in('d.status', array(1,2));
		$this->db->where_in('h.status', array(1,2));
		
		$this->db->group_by('d.invoiceno');
		$pay_settlements = rs_to_kv($this->db->get()->result(), NULL, NULL);
		
		if(!isset($pay_settlements[$revisedInvoiceId])){
			$pay_settlements[$revisedInvoiceId] = $revisedInvoiceVal;
		}else{
			//ignore -1
			$revisedInvoiceTotal = $pay_settlements[$revisedInvoiceId]+$revisedInvoiceVal;
			$pay_settlements[$revisedInvoiceId] = $revisedInvoiceTotal;
		}
		
		//var_dump($pay_settlements);
		//echo $this->db->last_query();
		
		
		$this->db->select('idtbl_account_payable_main as form_key, amount as form_val');//invoiceno,
		$this->db->from('tbl_account_payable_main');
		$this->db->where_in('idtbl_account_payable_main', $revisedRowsAll);//invoiceno
		$supplier_invoices = rs_to_kv($this->db->get()->result(), NULL, NULL);
		//var_dump($supplier_invoices);
		//echo $this->db->last_query();
		
		$invoice_checklist = array();
		
		foreach($pay_settlements as $k=>$v){
			if($supplier_invoices[$k]<$v){
				$invoice_checklist[$k] = array('pay_limit'=>$supplier_invoices[$k], 'pay_update'=>$v);
			}
		}
		
		//var_dump(count($invoice_checklist));
		
		return $invoice_checklist;
		
	}
	
	/*
	public function getOrderHeaderByInvoice($invoice_no){
		$this->db->where('invoiceno', $invoice_no);
		//$this->db->or_where('salesorder_ref', $orderRef);
		$row = $this->db->get('tbl_account_payable_main')->row();
		return $row;
	}
	*/
	public function getOrderDetail($orderRef){//x
		$this->db->where('tbl_account_paysettle_info.tbl_account_paysettle_idtbl_account_paysettle', $orderRef);
		$this->db->where_in('tbl_account_paysettle_info.status', array(1,2));
		$this->db->select('tbl_account_paysettle_info.idtbl_account_paysettle_info, tbl_account_paysettle_info.narration, tbl_account_paysettle_info.amount, tbl_account_paysettle_info.status');
		
		//$this->db->select('tbl_account_detail.idtbl_account_detail, tbl_account_detail.accountname');
		$this->db->select('tbl_account_paysettle_info.invoiceno, tbl_account_payable_main.invoiceno as invoicetxt');
		
		$this->db->from('tbl_account_paysettle_info');
		
		$this->db->join('tbl_account_payable_main', 'tbl_account_paysettle_info.invoiceno=tbl_account_payable_main.idtbl_account_payable_main');
		return $this->db->get()->result();
	}
	/*
	public function getOrderHeaders($orderRef=''){
		$query_mval = 'SET @mval:=?';
		$this->db->query($query_mval, array($orderRef));
		$this->db->where('status', '1');
		//$this->db->set('mval', $orderRef, FALSE);//, array('mval'=>$orderRef), FALSE
		$this->db->select('`idtbl_account_payable_main`=@mval as opt_select', FALSE);
		$this->db->select('idtbl_account_payable_main as pay_regid, supplier as supplier_name, insertdatetime as pay_remarks, invoiceno as pay_invoice, amount as pay_invoice_total');
		$this->db->select('tbl_company_idtbl_company as parent_company_regno, tbl_company_branch_idtbl_company_branch as branch_regno');
		$this->db->select('concat(tbl_company_idtbl_company, "_", tbl_company_branch_idtbl_company_branch) as sect_key');
		$this->db->from('tbl_account_payable_main');
		//$this->db->join('select mval from (select 1 as mval) as drvb', 'inner');
		//$this->db->join('', '');
		return $this->db->get()->result();
	}
	*/
	public function getPayableSupplierInvoices($companyId, $branchId, $supplierId){
		$this->db->select('idtbl_account_payable_main as form_key, invoiceno as form_val');
		$this->db->from('tbl_account_payable_main');
		$this->db->where('status', 1);
		$this->db->where('paytype', 1);
		$this->db->where('tbl_company_idtbl_company', $companyId);
		$this->db->where('tbl_company_branch_idtbl_company_branch', $branchId);
		$this->db->where('supplier', $supplierId);
		return $this->db->get()->result();
	}
	
	public function getChequeDetail($orderRef){
		$sql = "select tbl_cheque_issue.chedate, tbl_cheque_issue.chequeno, tbl_cheque_issue.narration, tbl_cheque_issue.amount, tbl_bank.bankname, tbl_bank_branch.branchname, tbl_account.idtbl_account, tbl_account.accountname from ";
		$sql .= "(select tbl_cheque_issue_idtbl_cheque_issue as id from tbl_account_paysettle_has_tbl_cheque_issue where tbl_account_paysettle_idtbl_account_paysettle=?) as drv_cl inner join tbl_cheque_issue on drv_cl.id=tbl_cheque_issue.idtbl_cheque_issue ";
		$sql .= "inner join tbl_cheque_info on tbl_cheque_issue.tbl_cheque_info_idtbl_cheque_info=tbl_cheque_info.idtbl_cheque_info ";
		$sql .= "inner join tbl_bank on tbl_cheque_info.tbl_bank_idtbl_bank=tbl_bank.idtbl_bank inner join tbl_bank_branch on tbl_cheque_info.tbl_bank_branch_idtbl_bank_branch=tbl_bank_branch.idtbl_bank_branch ";
		$sql .= "inner join tbl_account on tbl_cheque_info.tbl_account_idtbl_account=tbl_account.idtbl_account";
		$qry = $this->db->query($sql, array($orderRef));
		
		return $qry->result();
	}
	
	public function getTotalDrawValue($orderRef){
		$sql = "select ifnull(sum(tbl_cheque_issue.amount), 0) as total_draw from ";
		$sql .= "(select tbl_cheque_issue_idtbl_cheque_issue as id from tbl_account_paysettle_has_tbl_cheque_issue where tbl_account_paysettle_idtbl_account_paysettle=?) as drv_cl inner join tbl_cheque_issue on drv_cl.id=tbl_cheque_issue.idtbl_cheque_issue ";
		$sql .= "inner join tbl_cheque_info on tbl_cheque_issue.tbl_cheque_info_idtbl_cheque_info=tbl_cheque_info.idtbl_cheque_info ";
		$sql .= "";//group by
		
		$qry = $this->db->query($sql, array($orderRef));
		
		return $qry->row();//result();
	}
	
	public function regApproveOrder($approveData, $headerDataExistingId){
		$importmsg = '';
		$msgclass = "bg-info text-white";
		
		$msgerr = false;
		
		$alterData=$approveData;//array('qryuser'=>$this->session->userdata('admin_id'), 'qrycancel'=>1);
		
		//$this->db->set('qrytime', 'NOW()', FALSE);
		
		$this->db->where(array('idtbl_account_paysettle'=>$headerDataExistingId));
		$update=$this->db->update('tbl_account_paysettle', $approveData);
		
		if(!($this->db->affected_rows()==1)){
			$msgErr=true;
			$importmsg="Something wrong";
		}
		
		return array('importMsg'=>$importmsg, 'msgErr'=>$msgerr, 'toastType'=>$msgclass);
	}
	
	public function regUpdateOrder($headerData, $headerDataExistingId, $revisedData, $detailData, $detailDataExistingId){
		$importmsg = '';
		$msgclass = "bg-info text-white";
		//echo 'p--'.$detailDataExistingId;die;
		if(empty($headerData['batchno'])){
			return array('importMsg'=>'Something wrong', 'toastType'=>$msgclass, 'head_k'=>'', 'sub_k'=>'-1');
		}
		
		$head_k = $headerDataExistingId;
		
		//start the transaction
		$this->db->trans_begin();
		$flag = true;
		/*
		$userName = $this->session->userdata('userName');
		*/
		if(empty($headerDataExistingId)){//echo 1;
			// check unique fields
			$preQuery = "";//$this->db->get_where('salesorder_list', array('salesorder_ref'=>$headerData['salesorder_ref']));
			
			$count = 0;//$preQuery->num_rows(); 
			
			if($count==0){
				// set prepare-time field
				$this->db->set('insertdatetime', 'NOW()', FALSE);
				
				// Insert member data
				$insert = $this->db->insert('tbl_account_paysettle', $headerData);
				
				$detailData['tbl_account_paysettle_idtbl_account_paysettle']=$this->db->insert_id();
				
				$head_k = $detailData['tbl_account_paysettle_idtbl_account_paysettle'];
				
				// Return the status
				//$importmsg = $insert?'Style details added':'Unable to add style';
			}
		}else{//echo 2;
			if(!empty($headerData)){//echo '2.1['.$detailData['tbl_account_payable_main_idtbl_account_payable_main'].']';
				$this->db->where('idtbl_account_paysettle', $headerDataExistingId);
				//$this->db->or_where('br_grpcode', $data['br_grpcode']);
				
				$this->db->where_in('status', array(1,2));//where('status', 1);//might throw error desc Transaction error
				
				// set update-time field
				$this->db->set('updatedatetime', 'NOW()', FALSE);
				
				$update = $this->db->update('tbl_account_paysettle', $headerData);
				
				//$importmsg = ($update)?'Style details updated':'Unable to update style';
				
				if(!($this->db->affected_rows()==1)){
					$flag = false;//echo 'n2.1';
				}
			}
		}
		
		if(!empty($revisedData)){
			//$this->db->set('updateuser', $headerData['updateuser']);
			//$this->db->set('updatedatetime', 'NOW()', FALSE);
			$this->db->update_batch('tbl_account_paysettle_info', $revisedData, 'idtbl_account_paysettle_info'); 
		}
		
		$sub_k = 0;
		
		//$head_k = $detailData['tbl_account_payable_main_idtbl_account_payable_main'];
		
		if(($head_k>0)&&(!empty($detailData))){//echo 3;
			if(empty($detailDataExistingId)){//echo '3.1';
				if($detailData['invoiceno']!='-1'){
					// set qrytime field
					$this->db->set('insertdatetime', 'NOW()', FALSE);
					
					$additem = $this->db->insert('tbl_account_paysettle_info', $detailData);
					$sub_k = $additem?$this->db->insert_id():0;
				
					if($sub_k==0){
						$flag = false;//echo 'n3.1';
					}
				}
			}else{//echo '4'.'x'.$detailDataExistingId;//.'c'.$detailData['tbl_account_detail_idtbl_account_detail'];
				$this->db->set('updatedatetime', 'NOW()', FALSE);
				
				$this->db->where('idtbl_account_paysettle_info', $detailDataExistingId);
				
				//$this->db->where('poststatus IS NULL');//might throw error desc Transaction error
				
				$update = $this->db->update('tbl_account_paysettle_info', $detailData);
				
				$sub_k = $detailDataExistingId;
				
				//$importmsg = ($update)?'Style details updated':'Unable to update style';
				
				if(!($this->db->affected_rows()==1)){
					$flag = false;
				}
			}
		}
		
		
		
		//check if transaction status TRUE or FALSE
		if(($this->db->trans_status()===FALSE)||($flag==FALSE)){
			//if something went wrong, rollback everything
			$this->db->trans_rollback();
			$importmsg = 'Transaction error';//.$detailData['order_qty']
			$msgclass = 'bg-warning text-white';
		}else{
			//if everything went right, commit the data to the database
			$this->db->trans_commit();
			$msgclass = 'bg-success text-white';
		}
		
		
		return array('importMsg'=>$importmsg, 'toastType'=>$msgclass, 'head_k'=>$head_k, 'sub_k'=>$sub_k);
	}
	
	public function regSettleOrder($chequeData, $orderRef, $bankId, $bankBranchId, $bankAccountId){
		$importmsg = '';
		$msgclass = "bg-info text-white";
		
		$sql_cheque = "select tbl_cheque_info.idtbl_cheque_info, ifnull(LPAD(drv.chno+1, 6, '0'), tbl_cheque_info.startno) as chno from tbl_cheque_info left outer join (select tbl_cheque_info_idtbl_cheque_info, max(CAST(chequeno AS UNSIGNED)) as chno from tbl_cheque_issue group by tbl_cheque_info_idtbl_cheque_info) as drv on tbl_cheque_info.idtbl_cheque_info=drv.tbl_cheque_info_idtbl_cheque_info ";
		$sql_cheque .= "where tbl_cheque_info.tbl_bank_idtbl_bank=? and tbl_cheque_info.tbl_bank_branch_idtbl_bank_branch=? AND tbl_account_idtbl_account=? and IFNULL(drv.chno, 0)<CAST(tbl_cheque_info.endno AS UNSIGNED) and tbl_cheque_info.status=1 limit 1";
		$result = $this->db->query($sql_cheque, array($bankId, $bankBranchId, $bankAccountId));
		$row = $result->row();
		
		if(empty($row)){
			return array('importMsg'=>'Something wrong. Cheque detail not available', 'toastType'=>$msgclass, 'cheque_no'=>'');
		}
		
		$cheque_no_txt = $row->chno;//'000001';
		$chequeData['tbl_cheque_info_idtbl_cheque_info'] = $row->idtbl_cheque_info;//1;
		$chequeData['chequeno'] = $cheque_no_txt;
		
		//start the transaction
		$this->db->trans_begin();
		$flag = true;
		
		
		// set prepare-time field
		$this->db->set('insertdatetime', 'NOW()', FALSE);
		
		// Insert member data
		$insert_cheque = $this->db->insert('tbl_cheque_issue', $chequeData);
		
		$paysettleHasChequeData = array('tbl_account_paysettle_idtbl_account_paysettle'=>$orderRef,
										'tbl_cheque_issue_idtbl_cheque_issue'=>$this->db->insert_id()
									);
		$assign_cheque = $this->db->insert('tbl_account_paysettle_has_tbl_cheque_issue', $paysettleHasChequeData);
		
		if(!($this->db->affected_rows()==1)){
			$flag = false;
		}
		
		//check if transaction status TRUE or FALSE
		if(($this->db->trans_status()===FALSE)||($flag==FALSE)){
			//if something went wrong, rollback everything
			$this->db->trans_rollback();
			$importmsg = 'Transaction error';//.$detailData['order_qty']
			$msgclass = 'bg-warning text-white';
		}else{
			//if everything went right, commit the data to the database
			$this->db->trans_commit();
			$msgclass = 'bg-success text-white';
		}
		
		
		return array('importMsg'=>$importmsg, 'toastType'=>$msgclass, 'cheque_no'=>$cheque_no_txt);
	}
	
	public function regFreezeOrder($orderRef, $orderData, $crdrData, $crdrFull){
		$importmsg = '';
		$msgclass = "bg-info text-white";
		
		//start the transaction
		$this->db->trans_begin();
		$flag = true;
		
		//mark-as-posted
		$this->db->set('updatedatetime', 'NOW()', FALSE);
		
		$this->db->where('idtbl_account_paysettle', $orderRef);
		
		//$this->db->where('poststatus IS NULL');//might throw error desc Transaction error
		
		$update = $this->db->update('tbl_account_paysettle', $orderData);
		
		if(!($this->db->affected_rows()==1)){
			$flag = false;
		}
		
		
		$resultOut = $this->db->insert_batch('tbl_account_transaction', $crdrData);
		
		//var_dump($resultOut); // false on error
		if($resultOut===false){
			$flag = false;
		}
		
		$resultFull = $this->db->insert_batch('tbl_account_transaction_full', $crdrFull);
		
		if($resultFull===false){
			$flag = false;
		}
		
		
		//check if transaction status TRUE or FALSE
		if(($this->db->trans_status()===FALSE)||($flag==FALSE)){
			//if something went wrong, rollback everything
			$this->db->trans_rollback();
			$importmsg = 'Transaction error';//.$detailData['order_qty']
			$msgclass = 'bg-warning text-white';
		}else{
			//if everything went right, commit the data to the database
			$this->db->trans_commit();
			$msgclass = 'bg-success text-white';
		}
		
		return array('importMsg'=>$importmsg, 'toastType'=>$msgclass);
	}
}