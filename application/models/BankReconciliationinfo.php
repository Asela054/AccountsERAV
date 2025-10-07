<?php
class BankReconciliationinfo extends CI_Model{
    public function getRecByYearMonth($rec_accno, $rec_year, $rec_month){
		$this->db->where('tbl_account_idtbl_account', $rec_accno);
		$this->db->where('tbl_finacial_year_idtbl_finacial_year', $rec_year);
		$this->db->where('tbl_finacial_month_idtbl_finacial_month', $rec_month);
		//$this->db->or_where('salesorder_ref', $orderRef);
		$row = $this->db->get('tbl_bank_rec_list')->row();
		return $row;
	}
	
	public function getNonBankAccounts(){
		$this->db->where_not_in('tbl_account_type_idtbl_account_type', array(1));
		$this->db->where('status', 1);
		$this->db->select('idtbl_account as form_key, concat(accountno, " ", accountname) as form_val');
		$this->db->from('tbl_account');
		$revAcc = $this->db->get();
		return rs_to_kv($revAcc->result());
	}
	
	public function getCompanyBranchAccountPeriods($rec_year, $rec_month){
		$sql = "select tbl_master.idtbl_master as form_key, tbl_company_branch.branch as form_val from tbl_master ";
		$sql .= "inner join tbl_company_branch on tbl_master.tbl_company_branch_idtbl_company_branch=tbl_company_branch.idtbl_company_branch where tbl_master.status=1 and tbl_master.tbl_finacial_year_idtbl_finacial_year=? and tbl_finacial_month_idtbl_finacial_month=?";
		
		$branch_period = $this->db->query($sql, array($rec_year, $rec_month));
		
		return $branch_period->result();
	}
	
	public function getUnconfirmedAccountBalance($rec_year, $rec_month){
		$sql = "select tbl_account_open_bal.idtbl_account_open_bal, tbl_account_open_bal.openbal, md5(CONCAT(tbl_account_open_bal.tbl_account_idtbl_account, '_', tbl_account_open_bal.tbl_company_branch_idtbl_company_branch)) as m_key from tbl_account_open_bal inner join tbl_master on tbl_account_open_bal.tbl_master_idtbl_master=tbl_master.idtbl_master WHERE tbl_master.tbl_finacial_year_idtbl_finacial_year>=? and tbl_master.tbl_finacial_month_idtbl_finacial_month>? and tbl_account_open_bal.status=1 and tbl_master.status=1";
		
		$unconf_bal = $this->db->query($sql, array($rec_year, $rec_month));
		
		return $unconf_bal->result();
	}
	/*
	SELECT *, max(idtbl_bank_rec_list*rec_approved) as last_approved_rec from tbl_bank_rec_list where rec_approved=0 and tbl_account_idtbl_account=10
	*/
	
	/*
	select coalesce(drv.closed_bal, tbl_account_open_bal.openbal) as acc_bal, drv.tbl_finacial_year_idtbl_finacial_year, drv.idtbl_finacial_month from tbl_account_open_bal inner join tbl_master on tbl_account_open_bal.tbl_master_idtbl_master=tbl_master.idtbl_master inner join (
	select b.closed_bal as closed_bal, a.tbl_finacial_year_idtbl_finacial_year, a.idtbl_finacial_month from (
		SELECT tbl_finacial_year_idtbl_finacial_year, idtbl_finacial_month from tbl_finacial_month where activestatus=1 order by idtbl_finacial_month) as a 
		left outer join (
		select ((statement_open_bal+statement_tot_cr)-statement_tot_dr) as closed_bal, tbl_finacial_year_idtbl_finacial_year, tbl_finacial_month_idtbl_finacial_month from tbl_bank_rec_list where idtbl_bank_rec_list=1) as b 
		ON (a.tbl_finacial_year_idtbl_finacial_year>=ifnull(b.tbl_finacial_year_idtbl_finacial_year, 0) and a.idtbl_finacial_month>=ifnull(b.tbl_finacial_month_idtbl_finacial_month, 0)) 
		where ((a.tbl_finacial_year_idtbl_finacial_year-ifnull(b.tbl_finacial_year_idtbl_finacial_year, 0))+(a.idtbl_finacial_month-ifnull(b.tbl_finacial_month_idtbl_finacial_month, 0)))>0 limit 1) as drv 
	ON (tbl_master.tbl_finacial_year_idtbl_finacial_year=drv.tbl_finacial_year_idtbl_finacial_year AND tbl_master.tbl_finacial_month_idtbl_finacial_month=drv.idtbl_finacial_month)
	*/
	public function getAccountHeader($bankAcc){
		$sql_acc = "SELECT *, count(*) AS ucnt, acc_rec_batchno as rec_batchno from tbl_bank_rec_list where rec_approved=0 and tbl_account_idtbl_account=?";
		$result_acc = $this->db->query($sql_acc, array($bankAcc));//echo $this->db->last_query();die;
		$row_acc = $result_acc->row();
		
		$acc_info = new stdClass();
		
		$acc_info->idtbl_bank_rec_list = $row_acc->idtbl_bank_rec_list;
		$acc_info->bank_rec_date = $row_acc->bank_rec_date;
		$acc_info->statement_open_bal = $row_acc->statement_open_bal;
		$acc_info->statement_closed_bal = $row_acc->statement_closed_bal;
		$acc_info->statement_tot_cr = $row_acc->statement_tot_cr;
		$acc_info->statement_tot_dr = $row_acc->statement_tot_dr;
		$acc_info->status = 1;
		$acc_info->rec_approved = 0;
		$acc_info->rec_batchno = $row_acc->rec_batchno;
		
		$acc_info->tbl_finacial_year_idtbl_finacial_year = 0;
		$acc_info->tbl_finacial_month_idtbl_finacial_month = 0;
		$acc_info->acc_open_bal = 0;
		
		$sql_approved = "select max(idtbl_bank_rec_list) as last_approved_rec from tbl_bank_rec_list where rec_approved=1 and tbl_account_idtbl_account=?";
		$result_approved = $this->db->query($sql_approved, array($bankAcc));
		$row_approved = $result_approved->row();
		$lastApprovedRec = $row_approved->last_approved_rec;
		
		$sql_bal = "select coalesce(drv.closed_bal, tbl_account_open_bal.openbal) as acc_bal, drv.tbl_finacial_year_idtbl_finacial_year, drv.idtbl_finacial_month from (select openbal, tbl_master_idtbl_master from tbl_account_open_bal where tbl_account_idtbl_account=?) as tbl_account_open_bal inner join tbl_master on tbl_account_open_bal.tbl_master_idtbl_master=tbl_master.idtbl_master inner join (";
		$sql_bal .= "select b.closed_bal as closed_bal, a.tbl_finacial_year_idtbl_finacial_year, a.idtbl_finacial_month from (";
		$sql_bal .= "SELECT tbl_finacial_year_idtbl_finacial_year, idtbl_finacial_month from tbl_finacial_month where activestatus=1 order by idtbl_finacial_month) as a ";
		$sql_bal .= "left outer join (";
		$sql_bal .= "select ((statement_open_bal+statement_tot_cr)-statement_tot_dr) as closed_bal, tbl_finacial_year_idtbl_finacial_year, tbl_finacial_month_idtbl_finacial_month from tbl_bank_rec_list where idtbl_bank_rec_list=?) as b ";
		$sql_bal .= "ON (a.tbl_finacial_year_idtbl_finacial_year>=ifnull(b.tbl_finacial_year_idtbl_finacial_year, 0) and a.idtbl_finacial_month>=ifnull(b.tbl_finacial_month_idtbl_finacial_month, 0)) ";
		$sql_bal .= "where ((a.tbl_finacial_year_idtbl_finacial_year-ifnull(b.tbl_finacial_year_idtbl_finacial_year, 0))+(a.idtbl_finacial_month-ifnull(b.tbl_finacial_month_idtbl_finacial_month, 0)))>0 limit 1) as drv ";
		$sql_bal .= "ON (tbl_master.tbl_finacial_year_idtbl_finacial_year=drv.tbl_finacial_year_idtbl_finacial_year AND tbl_master.tbl_finacial_month_idtbl_finacial_month=drv.idtbl_finacial_month)";
		$result_bal = $this->db->query($sql_bal, array($bankAcc, $lastApprovedRec));
		$row_bal = $result_bal->row();
		
		if(!empty($row_bal)){
			$acc_info->tbl_finacial_year_idtbl_finacial_year = $row_bal->tbl_finacial_year_idtbl_finacial_year;
			$acc_info->tbl_finacial_month_idtbl_finacial_month = $row_bal->idtbl_finacial_month;
			$acc_info->acc_open_bal = $row_bal->acc_bal;
		}
		
		return $acc_info;
	}
	
	public function getOrderHeader($orderRef){
		$sql_acc = "SELECT *, statement_open_bal as acc_open_bal, acc_rec_batchno as rec_batchno from tbl_bank_rec_list where idtbl_bank_rec_list=?"; // and rec_approved=1
		$result_acc = $this->db->query($sql_acc, array($orderRef));
		$row_acc = $result_acc->row();
		
		return $row_acc;
	}
	
	public function getRecPeriodDescription($id_y, $id_m){
		$sql_y = "select `year` as txt_year from tbl_finacial_year where idtbl_finacial_year=?";
		$result_y = $this->db->query($sql_y, array($id_y));
		$row_y = $result_y->row();
		$str_y = empty($row_y)?'':$row_y->txt_year;
		
		$sql_m = "select `monthname` as txt_month from tbl_finacial_month where idtbl_finacial_month=?";
		$result_m = $this->db->query($sql_m, array($id_m));
		$row_m = $result_m->row();
		$str_m = empty($row_m)?'':$row_m->txt_month;
		
		return $str_y.' '.$str_m;
	}
	
	public function getOrderDetail($accRef, $bankRecId, $bankRecBatchNo, $maxYear, $maxMonth, $matchConfirm, $chkConfirm){
		$sql = "select drv_transacts.idtbl_account_transaction_full as transaction_id, ifnull(drv_recinfo.idtbl_bank_rec_info, '') as rec_info_id, COALESCE(drv_recinfo.rec_info_status, ?, ((?-tbl_finacial_year.idtbl_finacial_year)+(?-tbl_finacial_month.idtbl_finacial_month)=0)) as rec_info_status, ifnull(drv_recinfo.rec_info_status, 0) AS rec_revise_status, CONCAT(tbl_finacial_year.`year`, ' ', tbl_finacial_month.monthname) as acc_period_txt, drv_transacts.narration as narration_txt, drv_transacts.tradate as transaction_date, ((drv_transacts.crdr='C')*drv_transacts.accamount) as cr_val, ((drv_transacts.crdr='D')*drv_transacts.accamount) as dr_val, drv_transacts.rec_info_origin_name from (select idtbl_account_transaction_full, tradate, tbl_master_idtbl_master, crdr, accamount, narration, 'transaction_full' AS rec_info_origin_name from tbl_account_transaction_full where status=1 and ismatch=? and tbl_account_idtbl_account=? AND batchno<>IFNULL(?, '') ";
		/*
		$sql .= "UNION ALL ";
		$sql .= "select idtbl_account_transaction_full, tradate, tbl_master_idtbl_master, crdr, accamount, narration from tbl_account_transaction_full where status=1 and batchno=?";
		*/
		$sql .= ") as drv_transacts ";
		
		$sql .= "inner join (select idtbl_master, tbl_finacial_year_idtbl_finacial_year, tbl_finacial_month_idtbl_finacial_month from tbl_master where status=1 and tbl_finacial_year_idtbl_finacial_year<=? and tbl_finacial_month_idtbl_finacial_month<=?) as drv_master on drv_transacts.tbl_master_idtbl_master=drv_master.idtbl_master ";
		
		$sql .= "inner join tbl_finacial_year ON drv_master.tbl_finacial_year_idtbl_finacial_year=tbl_finacial_year.idtbl_finacial_year ";
		$sql .= "inner join tbl_finacial_month ON drv_master.tbl_finacial_month_idtbl_finacial_month=tbl_finacial_month.idtbl_finacial_month ";
		
		$sql .= "left outer join (select idtbl_bank_rec_info, tbl_account_transaction_idtbl_account_transaction, status as rec_info_status, rec_info_origin_name from tbl_bank_rec_info where tbl_bank_rec_list_idtbl_bank_rec_list=? and rec_info_origin_name='transaction_full') as drv_recinfo ";
		$sql .= "ON (drv_transacts.idtbl_account_transaction_full=drv_recinfo.tbl_account_transaction_idtbl_account_transaction AND BINARY drv_transacts.rec_info_origin_name=drv_recinfo.rec_info_origin_name)";
		
		$qry_transactions = $this->db->query($sql, array($chkConfirm, $maxYear, $maxMonth, $matchConfirm, $accRef, $bankRecBatchNo, $maxYear, $maxMonth, $bankRecId));
		//echo $this->db->last_query();die;
		return $qry_transactions->result();
	}
	
	public function getBankRevisionDetail($bankRecId, $accRef){
		$sql = "select (bank_amount*(tbl_account_idtbl_account_cr=?)) as cr_val, (bank_amount*(tbl_account_idtbl_account_dr=?)) as dr_val, idtbl_bank_rec_revision, bank_narration from tbl_bank_rec_revision where tbl_bank_rec_list_idtbl_bank_rec_list=? and status=1";
		
		$qry_revisions = $this->db->query($sql, array($accRef, $accRef, $bankRecId));
		
		return $qry_revisions->result();
	}
	
	public function getBankDepositDetails($bankRecId, $maxYear, $maxMonth, $depositConfirm){
		$sql = "select tbl_receivable.idtbl_receivable, ifnull(drv_recinfo.idtbl_bank_rec_info, '') as rec_info_id, ifnull(drv_recinfo.rec_info_status, ((?-tbl_finacial_year.idtbl_finacial_year)+(?-tbl_finacial_month.idtbl_finacial_month)=0)) as rec_info_status, ifnull(drv_recinfo.rec_info_status, 0) AS rec_revise_status, CONCAT(tbl_finacial_year.`year`, ' ', tbl_finacial_month.monthname) as acc_period_txt, tbl_receivable.narration, tbl_receivable.recdate, tbl_receivable.amount as cr_val, 0 as dr_val, 'receivable_deposit' as rec_info_origin_name from tbl_receivable ";
		
		/*
		$sql .= "inner join tbl_master on tbl_receivable.tbl_master_idtbl_master=tbl_master.idtbl_master ";
		*/
		$sql .= "inner join (select idtbl_master, tbl_finacial_year_idtbl_finacial_year, tbl_finacial_month_idtbl_finacial_month from tbl_master where status=1 and tbl_finacial_year_idtbl_finacial_year<=? and tbl_finacial_month_idtbl_finacial_month<=?) as drv_master on tbl_receivable.tbl_master_idtbl_master=drv_master.idtbl_master ";
		
		$sql .= "inner join tbl_finacial_year ON drv_master.tbl_finacial_year_idtbl_finacial_year=tbl_finacial_year.idtbl_finacial_year ";
		$sql .= "inner join tbl_finacial_month ON drv_master.tbl_finacial_month_idtbl_finacial_month=tbl_finacial_month.idtbl_finacial_month ";
		
		$sql .= "left outer join (select idtbl_bank_rec_info, tbl_account_transaction_idtbl_account_transaction, status as rec_info_status from tbl_bank_rec_info where tbl_bank_rec_list_idtbl_bank_rec_list=? and rec_info_origin_name='receivable_deposit') as drv_recinfo on tbl_receivable.idtbl_receivable=drv_recinfo.tbl_account_transaction_idtbl_account_transaction ";
		
		$sql .= "where tbl_receivable.status=1 and tbl_receivable.tbl_receivable_type_idtbl_receivable_type=1 and tbl_receivable.depositstatus=? and  ((tbl_receivable.idtbl_receivable*tbl_receivable.depositstatus)=ifnull(drv_recinfo.tbl_account_transaction_idtbl_account_transaction, 0)*tbl_receivable.depositstatus)";
		
		$qry_deposit = $this->db->query($sql, array($maxYear, $maxMonth, $maxYear, $maxMonth, $bankRecId, $depositConfirm));
		
		return $qry_deposit->result();
	}
	
	public function generateBankTransactions($bankRecId){
		$sql = "select tbl_bank_rec_list.acc_rec_batchno, tbl_bank_rec_list.bank_rec_date, drv_rev.tbl_master_idtbl_master, drv_rev.idtbl_bank_rec_revision, drv_rev.b_prefix, drv_rev.bank_amount, drv_rev.totamount, drv_crdr.seqno, drv_crdr.fig_crdr, drv_rev.bank_narration, tbl_master.tbl_company_idtbl_company, tbl_master.tbl_company_branch_idtbl_company_branch, ";
		$sql .= "(((drv_crdr.fig_crdr='C')*drv_rev.tbl_account_idtbl_account_cr)+((drv_crdr.fig_crdr='D')*drv_rev.tbl_account_idtbl_account_dr)) as tbl_account_idtbl_account ";
		$sql .= "from tbl_bank_rec_list inner join (";
		$sql .= "SELECT idtbl_bank_rec_revision, 'P' as b_prefix, tbl_bank_rec_list_idtbl_bank_rec_list, tbl_account_idtbl_account_cr, tbl_account_idtbl_account_dr, bank_narration, bank_amount, bank_amount as totamount, tbl_master_idtbl_master FROM `tbl_bank_rec_revision` WHERE tbl_bank_rec_list_idtbl_bank_rec_list=? and status=1 UNION ALL ";
		$sql .= "select tbl_receivable.idtbl_receivable as idtbl_bank_rec_revision, 'Q' as b_prefix, drv_h.idtbl_bank_rec_list, drv_h.tbl_account_idtbl_account as tbl_account_idtbl_account_cr, tbl_account_detail.tbl_account_idtbl_account as tbl_account_idtbl_account_dr, tbl_receivable.narration as bank_narration, tbl_receivable.amount as bank_amount, tbl_receivable.amount as totamount, tbl_receivable.tbl_master_idtbl_master from (select idtbl_bank_rec_list, tbl_account_idtbl_account from tbl_bank_rec_list where idtbl_bank_rec_list=?) as drv_h inner join (select idtbl_bank_rec_info, tbl_bank_rec_list_idtbl_bank_rec_list, tbl_account_transaction_idtbl_account_transaction from tbl_bank_rec_info where tbl_bank_rec_list_idtbl_bank_rec_list=? and rec_info_origin_name='receivable_deposit' and status=1) as drv_p on drv_h.idtbl_bank_rec_list=drv_p.tbl_bank_rec_list_idtbl_bank_rec_list inner join tbl_receivable on drv_p.tbl_account_transaction_idtbl_account_transaction=tbl_receivable.idtbl_receivable inner join tbl_account_detail on tbl_receivable.tbl_account_detail_idtbl_account_detail=tbl_account_detail.idtbl_account_detail";
		$sql .= ") as drv_rev on tbl_bank_rec_list.idtbl_bank_rec_list=drv_rev.tbl_bank_rec_list_idtbl_bank_rec_list ";
		$sql .= "inner join tbl_master on drv_rev.tbl_master_idtbl_master=tbl_master.idtbl_master ";
		$sql .= "cross join (select 1 as seqno, 'C' as fig_crdr union all select 2 as seqno, 'D' as fig_crdr) as drv_crdr where tbl_bank_rec_list.idtbl_bank_rec_list=? and tbl_bank_rec_list.rec_approved=0 order by drv_rev.idtbl_bank_rec_revision, drv_crdr.fig_crdr asc";
		$qry_bank = $this->db->query($sql, array($bankRecId, $bankRecId, $bankRecId, $bankRecId));
		
		return $qry_bank->result();
	}
	
	public function regAlterOrder($partNo){
		$importmsg = '';
		$msgclass = "bg-info text-white";
		
		$msgerr = false;
		
		$alterData=array('updateuser'=>$_SESSION['userid'], 'status'=>3);
		
		$this->db->set('updatedatetime', 'NOW()', FALSE);
		$this->db->where(array('idtbl_bank_rec_revision'=>$partNo, 'status'=>1));
		$update=$this->db->update('tbl_bank_rec_revision', $alterData);
		
		if(!($this->db->affected_rows()==1)){
			$msgErr=true;
			$importmsg="Something wrong";
		}
		
		return array('importMsg'=>$importmsg, 'msgErr'=>$msgerr, 'toastType'=>$msgclass);
	}
	
	public function regUpdateOrder($headerData, $headerDataExistingId, $revisedData, $bankDetailData){
		$importmsg = '';
		$msgclass = "bg-info text-white";
		//echo 'p--'.$detailDataExistingId;die;
		if(empty($headerData['acc_rec_batchno'])){
			return array('importMsg'=>'Something wrong-1', 'toastType'=>$msgclass, 'head_k'=>'', 'sub_k'=>'-1');
		}
		
		$head_k = $headerDataExistingId;
		
		//start the transaction
		$this->db->trans_begin();
		$flag = true;
		
		if(empty($headerDataExistingId)){//echo 1;
			// check unique fields
			$preQuery = "";//$this->db->get_where('salesorder_list', array('salesorder_ref'=>$headerData['salesorder_ref']));
			
			$count = 0;//$preQuery->num_rows(); 
			
			if($count==0){
				// set prepare-time field
				$this->db->set('insertdatetime', 'NOW()', FALSE);
				
				// Insert member data
				$insert = $this->db->insert('tbl_bank_rec_list', $headerData);
				
				$detailData['tbl_bank_rec_list_idtbl_bank_rec_list']=$this->db->insert_id();
				
				$head_k = $detailData['tbl_bank_rec_list_idtbl_bank_rec_list'];
				
				// Return the status
				//$importmsg = $insert?'Style details added':'Unable to add style';
			}
		}else{//echo 2;
			if(!empty($headerData)){//
				$this->db->where('idtbl_bank_rec_list', $headerDataExistingId);
				//$this->db->or_where('br_grpcode', $data['br_grpcode']);
				
				$this->db->where_in('status', array(1,2));//where('status', 1);//might throw error desc Transaction error
				
				// set update-time field
				$this->db->set('updatedatetime', 'NOW()', FALSE);
				
				$update = $this->db->update('tbl_bank_rec_list', $headerData);
				
				//$importmsg = ($update)?'Style details updated':'Unable to update style';
				
				if(!($this->db->affected_rows()==1)){
					$flag = false;//echo 'n2.1';
				}
			}
		}
		
		$rdInsert = array();
		$rdUpdate = array();
		
		if(!empty($revisedData)){
			
			$toggleTime = date("Y-m-d H:i:s");
			$userName = $_SESSION['userid'];//'1';
			
			foreach($revisedData as $rd){
				if(empty($rd['idtbl_bank_rec_info'])){
					$rdInsert[] = array('tbl_bank_rec_list_idtbl_bank_rec_list'=>$head_k, 
									'tbl_account_transaction_idtbl_account_transaction'=>$rd['tbl_account_transaction_idtbl_account_transaction'], 
									'rec_info_origin_name'=>$rd['rec_info_origin_name'],
									'status'=>$rd['status'],
									'tbl_user_idtbl_user'=>$userName,
									'insertdatetime'=>$toggleTime
								);
				}else{
					$rdUpdate[] = array('idtbl_bank_rec_info'=>$rd['idtbl_bank_rec_info'], 
											   'status'=>$rd['status'], 
											   'updateuser'=>$userName, 
											   'updatedatetime'=>$toggleTime
										);
				}
			}
			
			
			
			//$this->db->set('updateuser', $headerData['updateuser']);
			//$this->db->set('updatedatetime', 'NOW()', FALSE);
			if(!empty($rdInsert)){
				$this->db->insert_batch('tbl_bank_rec_info', $rdInsert);
			}
			if(!empty($rdUpdate)){
				$this->db->update_batch('tbl_bank_rec_info', $rdUpdate, 'idtbl_bank_rec_info'); 
			}
		}
		
		$rdInsertCnt = count($rdInsert);//to-reload-rec-detail-with-new-ids
		$sub_k = 0;
		
		if(($head_k>0)&&(!empty($bankDetailData))){
			//if($bankDetailData['tbl_account_idtbl_account']!='-1'){
				// set qrytime field
				$bankDetailData['tbl_bank_rec_list_idtbl_bank_rec_list'] = $head_k;
				$this->db->set('insertdatetime', 'NOW()', FALSE);
				
				$additem = $this->db->insert('tbl_bank_rec_revision', $bankDetailData);
				$sub_k = $additem?$this->db->insert_id():0;
			
				if($sub_k==0){
					$flag = false;//echo 'n3.1';
				}
			//}
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
		
		
		return array('importMsg'=>$importmsg, 'toastType'=>$msgclass, 'head_k'=>$head_k, 'sub_k'=>$sub_k, 'rd_cnt'=>$rdInsertCnt);
	}
	
	public function regFreezeOrder($orderRef, $orderData, $crdrData, $crdrFull, $accOpenBalUpdateData){
		$importmsg = '';
		$msgclass = "bg-info text-white";
		
		//start the transaction
		$this->db->trans_begin();
		$flag = true;
		
		//mark-as-posted
		$this->db->set('updatedatetime', 'NOW()', FALSE);
		
		$this->db->where('idtbl_bank_rec_list', $orderRef);
		
		//$this->db->where('poststatus IS NULL');//might throw error desc Transaction error
		
		$update = $this->db->update('tbl_bank_rec_list', $orderData);
		
		if(!($this->db->affected_rows()==1)){
			$flag = false;
		}
		
		
		if(!empty($crdrData)){
			$resultOut = $this->db->insert_batch('tbl_account_transaction', $crdrData);
			
			//var_dump($resultOut); // false on error
			if($resultOut===false){
				$flag = false;
			}
		}
		
		if(!empty($crdrFull)){
			$resultFull = $this->db->insert_batch('tbl_account_transaction_full', $crdrFull);
			
			if($resultFull===false){
				$flag = false;
			}
		}
		
		if(!empty($accOpenBalUpdateData)){
			$this->db->update_batch('tbl_account_open_bal', $accOpenBalUpdateData, 'idtbl_account_open_bal'); 
		}
		
		
		//update-matched-status-transaction_full
		$this->db->select('tbl_account_transaction_idtbl_account_transaction as idtbl_account_transaction_full, 1 as ismatch');
		$this->db->from('tbl_bank_rec_info');
		$this->db->where('tbl_bank_rec_list_idtbl_bank_rec_list', $orderRef);
		$this->db->where('status', 1);
		$this->db->where('rec_info_origin_name', 'transaction_full');
		$match_recs = $this->db->get()->result_array();
		
		if(!empty($match_recs)){
			$this->db->update_batch('tbl_account_transaction_full', $match_recs, 'idtbl_account_transaction_full');
			//echo 'cnt-full '.count($match_recs).'<br />';
			//echo 'db-update '.$this->db->affected_rows();die;
			if(!(count($match_recs==$this->db->affected_rows()))){
				$flag = false;
			}
		}
		
		//update-matched-status-others-tbl_receivable
		$this->db->select('tbl_account_transaction_idtbl_account_transaction as idtbl_receivable, 1 as depositstatus, 1 as poststatus');
		$this->db->from('tbl_bank_rec_info');
		$this->db->where('tbl_bank_rec_list_idtbl_bank_rec_list', $orderRef);
		$this->db->where('status', 1);
		$this->db->where('rec_info_origin_name', 'receivable_deposit');
		$deposit_recs = $this->db->get()->result_array();
		
		if(!empty($deposit_recs)){
			$this->db->update_batch('tbl_receivable', $deposit_recs, 'idtbl_receivable');
			//echo 'cnt-full '.count($match_recs).'<br />';
			//echo 'db-update '.$this->db->affected_rows();die;
			if(!(count($match_recs==$this->db->affected_rows()))){
				$flag = false;
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
		
		return array('importMsg'=>$importmsg, 'toastType'=>$msgclass);
	}
	
}