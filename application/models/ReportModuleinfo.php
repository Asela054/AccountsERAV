<?php
class ReportModuleinfo extends CI_Model{
	private function getLastDateOfYearMonth($acc_period, $pref_date=''){
		if(!empty($acc_period)){
			$this->db->select('tbl_finacial_year.year as f_year, tbl_finacial_month.month as f_month');
			$this->db->from('tbl_master');
			$this->db->join('tbl_finacial_year', 'tbl_master.tbl_finacial_year_idtbl_finacial_year=tbl_finacial_year.idtbl_finacial_year');
			$this->db->join('tbl_finacial_month', 'tbl_master.tbl_finacial_month_idtbl_finacial_month=tbl_finacial_month.idtbl_finacial_month');
			$this->db->where('tbl_master.idtbl_master', $acc_period);
			
			$acc_ym = $this->db->get()->row();
			
			$act_year = str_pad($acc_ym->f_year, 4, '0000');
			$act_month = str_pad($acc_ym->f_month, 2, '00', STR_PAD_LEFT);
			$act_date = str_pad($pref_date, 2, '00', STR_PAD_LEFT);
			$date_str = '';
			
			if($pref_date!=''){
				$date_str = $act_year.'-'.$act_month.'-'.$act_date;
			}else{
				$act_date = '01';
				$d = new DateTime($act_year.'-'.$act_month.'-'.$act_date);
				$date_str = $d->format('Y-m-t');
			}
			
			return $date_str;
			
		}else{
			return '';
		}
	}
	
	public function printDate($acc_period, $txt_date=''){
		return $this->getLastDateOfYearMonth($acc_period, $txt_date);
	}
	
	public function getChartOfAccounts(){
		$this->db->select('idtbl_account, accountno, accountname');
		$this->db->from('tbl_account');
		$this->db->where('status', 1);
		return $this->db->get()->result();
	}
	
	// public function calc_stock($opening_stock=false, $stock_opening_period=''){
	// 	$sql = "";
		
	// 	if($opening_stock){
	// 		$stock_opening_date = new DateTime($this->getLastDateOfYearMonth($stock_opening_period, 1));
	// 		$stock_closing_date = $stock_opening_date->modify("-1 days")->format('Y-m-d');
	// 		$sql = "SELECT closingstock AS stock_close_value FROM tbl_stock_closing WHERE `date`='$stock_closing_date'";
	// 		//echo $sql;die;
	// 	}else{
	// 		$sql = "SELECT SUM(tbl_stock.fullqty*tbl_product.unitprice) AS stock_close_value FROM tbl_stock INNER JOIN tbl_product ON tbl_stock.tbl_product_idtbl_product=tbl_product.idtbl_product WHERE tbl_stock.status=1 AND tbl_stock.fullqty>0";
	// 	}
	// 	/*
	// 	$stock_result = $this->db->query($sql);
		
	// 	$row = $stock_result->row();
	// 	return $row->stock_close_value;
	// 	*/
	// 	return 0;
	// }
	
	public function calc_custom_stock($branch_id, $opening_stock=false, $stock_opening_period=''){
		if($opening_stock){
			$sql = "select IFNULL(sum(tbl_account_open_bal.openbal), 0) as custom_stock_open_value from (select tbl_account_idtbl_account, openbal from tbl_account_open_bal where tbl_master_idtbl_master=? and tbl_company_branch_idtbl_company_branch=? AND status=1) AS tbl_account_open_bal inner join tbl_account ON tbl_account_open_bal.tbl_account_idtbl_account=tbl_account.idtbl_account WHERE tbl_account.tbl_account_nestcategory_idtbl_account_nestcategory=3";//inventories
			$stock_result = $this->db->query($sql, array($stock_opening_period, $branch_id));
			$stock_row = $stock_result->row();
			return $stock_row->custom_stock_open_value;
		}else{
			$sql = "select IFNULL(SUM(drv_open.openbal+(IFNULL(drv_crdr.dr_accamount, 0)*IFNULL(NULLIF(tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype-2, 0), 1)+IFNULL(drv_crdr.cr_accamount, 0)*IFNULL(NULLIF(1-tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype, 0), 1))), 0) AS custom_stock_close_value from (";
			$sql .= "select tbl_account_idtbl_account, openbal from tbl_account_open_bal where tbl_master_idtbl_master=? and tbl_company_branch_idtbl_company_branch=? and status=1";
			$sql .= ") as drv_open ";
			$sql .= "INNER JOIN tbl_account ON drv_open.tbl_account_idtbl_account=tbl_account.idtbl_account ";
			$sql .= "INNER JOIN tbl_account_category ON tbl_account.tbl_account_category_idtbl_account_category=tbl_account_category.idtbl_account_category ";
			$sql .= "LEFT OUTER JOIN (SELECT tbl_account_idtbl_account, SUM(accamount*(crdr='D')) AS dr_accamount, SUM(accamount*(crdr='C')) AS cr_accamount FROM tbl_account_transaction_full WHERE 'reversstatus'='reversstatus' AND tbl_master_idtbl_master=? GROUP BY tbl_account_idtbl_account) AS drv_crdr ON drv_open.tbl_account_idtbl_account=drv_crdr.tbl_account_idtbl_account ";
			$sql .= "WHERE tbl_account.tbl_account_nestcategory_idtbl_account_nestcategory=3";
			
			$stock_result = $this->db->query($sql, array($stock_opening_period, $branch_id, $stock_opening_period));
			$stock_row = $stock_result->row();
			return $stock_row->custom_stock_close_value;
		}
	}
	
	// public function pnlSectionDetails($report_section, $report_period_id){
	// 	$companyid = $_SESSION['companyid'];
    //     $branchid = $_SESSION['branchid'];
	// 	/*
	// 	$sql = "SELECT tbl_gl_report_sub_sections.id AS fig_sect_ref, tbl_gl_report_sub_sections.sub_section_name AS sect_name, CONCAT(tbl_gl_report_sub_section_particulars.subaccount, ' ', tbl_subaccount.subaccountname) AS fig_name, ((IFNULL(drv_open.ac_open_balance, 0)*tbl_gl_report_sub_section_particulars.value_ac_open_bal)+((IFNULL(drv_crdr.dr_accamount, 0)*IFNULL(NULLIF(tbl_mainclass.transactiontype-2, 0), 1)+IFNULL(drv_crdr.cr_accamount, 0)*IFNULL(NULLIF(1-tbl_mainclass.transactiontype, 0), 1))*tbl_gl_report_sub_section_particulars.value_ac_cr_dr)) AS fig_value FROM tbl_gl_report_sub_sections ";
	// 	*/
	// 	$sql = "SELECT tbl_account_subcategory.idtbl_account_subcategory AS fig_sect_ref, tbl_account_subcategory.subcategory AS sect_name, CONCAT(tbl_account.accountno, ' ', tbl_account.accountname) AS fig_name, ((IFNULL(drv_open.ac_open_balance, 0)*0)+((IFNULL(drv_crdr.dr_accamount, 0)*IFNULL(NULLIF(tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype-2, 0), 1)+IFNULL(drv_crdr.cr_accamount, 0)*IFNULL(NULLIF(1-tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype, 0), 1))*1)) AS fig_value FROM tbl_account_subcategory ";
		
	// 	/*
	// 	$sql .= "INNER JOIN tbl_gl_report_sub_section_particulars ON tbl_gl_report_sub_sections.id=tbl_gl_report_sub_section_particulars.tbl_gl_report_sub_section_id INNER JOIN tbl_subaccount ON tbl_gl_report_sub_section_particulars.subaccount=tbl_subaccount.subaccount INNER JOIN tbl_mainclass ON tbl_subaccount.mainclasscode=tbl_mainclass.code ";
	// 	*/
	// 	/*$sql .= "INNER JOIN tbl_account ON tbl_account_subcategory.idtbl_account_subcategory=tbl_account.tbl_account_subcategory_idtbl_account_subcategory INNER JOIN tbl_account_category ON tbl_account.tbl_account_category_idtbl_account_category=tbl_account_category.idtbl_account_category ";*/
	// 	$sql .= "INNER JOIN tbl_account_category ON tbl_account_subcategory.tbl_account_category_idtbl_account_category=tbl_account_category.idtbl_account_category INNER JOIN tbl_account ON tbl_account_subcategory.idtbl_account_subcategory=tbl_account.tbl_account_subcategory_idtbl_account_subcategory INNER JOIN tbl_account_allocation ON tbl_account.idtbl_account = tbl_account_allocation.tbl_account_idtbl_account ";
	// 	/*
	// 	$sql .= "LEFT OUTER JOIN (SELECT subaccount, SUM(ac_open_balance) AS ac_open_balance FROM tbl_gl_account_balance_details WHERE tbl_master_idtbl_master=? GROUP BY subaccount) AS drv_open ON tbl_gl_report_sub_section_particulars.subaccount=drv_open.subaccount ";
	// 	*/
	// 	$sql .= "LEFT OUTER JOIN (SELECT '-1' as tbl_account_idtbl_account, 0 AS ac_open_balance) AS drv_open ON tbl_account.idtbl_account=drv_open.tbl_account_idtbl_account ";
		
	// 	/*
	// 	$sql .= "LEFT OUTER JOIN (SELECT acccode, SUM(accamount*(crdr='D')) AS dr_accamount, SUM(accamount*(crdr='C')) AS cr_accamount FROM tbl_account_transaction WHERE reversstatus=0 AND tbl_master_idtbl_master=? GROUP BY acccode) AS drv_crdr ON tbl_gl_report_sub_section_particulars.subaccount=drv_crdr.acccode ";
	// 	*/
	// 	$sql .= "LEFT OUTER JOIN (SELECT tbl_account_idtbl_account, SUM(accamount*(crdr='D')) AS dr_accamount, SUM(accamount*(crdr='C')) AS cr_accamount FROM tbl_account_transaction WHERE reversstatus=0 AND tbl_master_idtbl_master=? GROUP BY tbl_account_idtbl_account) AS drv_crdr ON tbl_account.idtbl_account=drv_crdr.tbl_account_idtbl_account ";
		
	// 	/*
	// 	$sql .= "WHERE tbl_gl_report_sub_sections.tbl_gl_report_head_section_id=? AND tbl_gl_report_sub_sections.sect_cancel=0 AND tbl_gl_report_sub_section_particulars.report_part_cancel=0 ORDER BY tbl_gl_report_sub_section_particulars.fig_seq_no, tbl_gl_report_sub_section_particulars.tbl_gl_report_sub_section_id";
	// 	*/
	// 	$sql .= "WHERE tbl_account_subcategory.tbl_account_category_idtbl_account_category=? AND tbl_account_subcategory.status=1 AND tbl_account_allocation.companybank = ? AND tbl_account_allocation.branchcompanybank = ? AND tbl_account.status=1 ORDER BY tbl_account.code, tbl_account.tbl_account_subcategory_idtbl_account_subcategory";
		
	// 	$section_result = $this->db->query($sql, array($report_period_id, $report_section, $companyid, $branchid));
	// 	print_r($this->db->last_query());
	// 	return $section_result->result();
	// }
	
	public function pnlCustomSectionDetails($branch_id, $report_section, $report_period_id_fr, $report_period_id_to){
		/*
		$sql = "SELECT tbl_gl_report_sub_sections.id AS fig_sect_ref, tbl_gl_report_sub_sections.sub_section_name AS sect_name, CONCAT(tbl_gl_report_sub_section_particulars.subaccount, ' ', tbl_subaccount.subaccountname) AS fig_name, ((IFNULL(drv_open.ac_open_balance, 0)*tbl_gl_report_sub_section_particulars.value_ac_open_bal)+((IFNULL(drv_crdr.dr_accamount, 0)*IFNULL(NULLIF(tbl_mainclass.transactiontype-2, 0), 1)+IFNULL(drv_crdr.cr_accamount, 0)*IFNULL(NULLIF(1-tbl_mainclass.transactiontype, 0), 1))*tbl_gl_report_sub_section_particulars.value_ac_cr_dr)) AS fig_value FROM tbl_gl_report_sub_sections ";
		*/
		/*$sql = "SELECT tbl_account_subcategory.idtbl_account_subcategory AS fig_sect_ref, tbl_account_subcategory.subcategory AS sect_name, CONCAT(tbl_account.accountno, ' ', tbl_account.accountname) AS fig_name, ((IFNULL(drv_open.ac_open_balance, 0)*0)+((IFNULL(drv_crdr.dr_accamount, 0)*IFNULL(NULLIF(tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype-2, 0), 1)+IFNULL(drv_crdr.cr_accamount, 0)*IFNULL(NULLIF(1-tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype, 0), 1))*1)) AS fig_value FROM tbl_account_subcategory ";*/
		$sql = "SELECT tbl_gl_report_sub_sections.id AS fig_sect_ref, tbl_gl_report_sub_sections.sub_section_name AS sect_name, CONCAT(tbl_account.accountno, ' ', tbl_account.accountname) AS fig_name, ((IFNULL(drv_open.ac_open_balance, 0)*tbl_gl_report_sub_section_particulars.value_ac_open_bal)+((IFNULL(drv_crdr.dr_accamount, 0)*IFNULL(NULLIF(tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype-2, 0), 1)+IFNULL(drv_crdr.cr_accamount, 0)*IFNULL(NULLIF(1-tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype, 0), 1))*tbl_gl_report_sub_section_particulars.value_ac_cr_dr)) AS fig_value FROM tbl_gl_report_sub_sections ";
		
		
		/*
		$sql .= "INNER JOIN tbl_gl_report_sub_section_particulars ON tbl_gl_report_sub_sections.id=tbl_gl_report_sub_section_particulars.tbl_gl_report_sub_section_id INNER JOIN tbl_subaccount ON tbl_gl_report_sub_section_particulars.subaccount=tbl_subaccount.subaccount INNER JOIN tbl_mainclass ON tbl_subaccount.mainclasscode=tbl_mainclass.code ";
		*/
		/*$sql .= "INNER JOIN tbl_account_category ON tbl_account_subcategory.tbl_account_category_idtbl_account_category=tbl_account_category.idtbl_account_category INNER JOIN tbl_account ON tbl_account_subcategory.idtbl_account_subcategory=tbl_account.tbl_account_subcategory_idtbl_account_subcategory ";*/
		$sql .= "INNER JOIN tbl_gl_report_sub_section_particulars ON tbl_gl_report_sub_sections.id=tbl_gl_report_sub_section_particulars.tbl_gl_report_sub_section_id INNER JOIN tbl_account ON tbl_gl_report_sub_section_particulars.tbl_account_idtbl_account=tbl_account.idtbl_account ";
		$sql .= "INNER JOIN tbl_account_category ON tbl_account.tbl_account_category_idtbl_account_category=tbl_account_category.idtbl_account_category ";
		
		/*
		$sql .= "LEFT OUTER JOIN (SELECT subaccount, SUM(ac_open_balance) AS ac_open_balance FROM tbl_gl_account_balance_details WHERE tbl_master_idtbl_master=? GROUP BY subaccount) AS drv_open ON tbl_gl_report_sub_section_particulars.subaccount=drv_open.subaccount ";
		*/
		/*$sql .= "LEFT OUTER JOIN (SELECT '-1' as tbl_account_idtbl_account, 0 AS ac_open_balance) AS drv_open ON tbl_account.idtbl_account=drv_open.tbl_account_idtbl_account ";*/
		$sql .= "LEFT OUTER JOIN (SELECT tbl_account_idtbl_account, openbal AS ac_open_balance FROM tbl_account_open_bal WHERE status=1 AND tbl_company_branch_idtbl_company_branch=? AND tbl_master_idtbl_master=?) AS drv_open ON tbl_account.idtbl_account=drv_open.tbl_account_idtbl_account ";
		
		
		
		/*
		$sql .= "LEFT OUTER JOIN (SELECT acccode, SUM(accamount*(crdr='D')) AS dr_accamount, SUM(accamount*(crdr='C')) AS cr_accamount FROM tbl_account_transaction WHERE reversstatus=0 AND tbl_master_idtbl_master=? GROUP BY acccode) AS drv_crdr ON tbl_gl_report_sub_section_particulars.subaccount=drv_crdr.acccode ";
		*/
		/*$sql .= "LEFT OUTER JOIN (SELECT tbl_account_idtbl_account, SUM(accamount*(crdr='D')) AS dr_accamount, SUM(accamount*(crdr='C')) AS cr_accamount FROM tbl_account_transaction WHERE reversstatus=0 AND tbl_master_idtbl_master=? GROUP BY tbl_account_idtbl_account) AS drv_crdr ON tbl_account.idtbl_account=drv_crdr.tbl_account_idtbl_account ";*/
		$sql .= "LEFT OUTER JOIN (SELECT tbl_account_idtbl_account, SUM(accamount*(crdr='D')) AS dr_accamount, SUM(accamount*(crdr='C')) AS cr_accamount FROM tbl_account_transaction_full WHERE (tbl_master_idtbl_master BETWEEN ? AND ?) AND tbl_company_branch_idtbl_company_branch=? GROUP BY tbl_account_idtbl_account) AS drv_crdr ON tbl_account.idtbl_account=drv_crdr.tbl_account_idtbl_account ";
		
		
		
		/*
		$sql .= "WHERE tbl_gl_report_sub_sections.tbl_gl_report_head_section_id=? AND tbl_gl_report_sub_sections.sect_cancel=0 AND tbl_gl_report_sub_section_particulars.report_part_cancel=0 ORDER BY tbl_gl_report_sub_section_particulars.fig_seq_no, tbl_gl_report_sub_section_particulars.tbl_gl_report_sub_section_id";
		*/
		/*$sql .= "WHERE tbl_account_subcategory.tbl_account_category_idtbl_account_category=? AND tbl_account_subcategory.status=1 AND tbl_account.status=1 ORDER BY tbl_account.code, tbl_account.tbl_account_subcategory_idtbl_account_subcategory";*/
		$sql .= "WHERE tbl_gl_report_sub_sections.tbl_gl_report_head_section_id=? AND tbl_gl_report_sub_sections.sect_cancel=0 AND tbl_gl_report_sub_section_particulars.report_part_cancel=0 ORDER BY tbl_gl_report_sub_section_particulars.fig_seq_no, tbl_gl_report_sub_section_particulars.tbl_gl_report_sub_section_id";
		
		
		/*
		$section_result = $this->db->query($sql, array($report_period_id, $report_section));
		*/
		$section_result = $this->db->query($sql, array($branch_id, $report_period_id_fr, $report_period_id_fr, $report_period_id_to, $branch_id, $report_section));
		
		return $section_result->result();
	}
	
	public function trialBalanceDetails($branch_id, $report_period_id){
		$sql = "SELECT accname, (ac_open+dr_accamount+cr_accamount) AS accamount, crdr FROM ";
		
		/*
		$rpt_sql .= "(SELECT CONCAT(drv_acc.subaccountno, ' ', tbl_subaccount.subaccountname) AS accname, IFNULL(drv_open.ac_open_balance, 0) AS ac_open, IFNULL(drv_reg.dr_accamount, 0)*IFNULL(NULLIF(tbl_mainclass.transactiontype-2, 0), 1) AS dr_accamount, IFNULL(drv_reg.cr_accamount, 0)*IFNULL(NULLIF(1-tbl_mainclass.transactiontype, 0), 1) AS cr_accamount, tbl_mainclass.transactiontype AS crdr FROM ";
		*/
		$sql .= "(SELECT CONCAT(tbl_account.accountno, ' ', tbl_account.accountname) AS accname, IFNULL(drv_open.ac_open_balance, 0) AS ac_open, IFNULL(drv_reg.dr_accamount, 0)*IFNULL(NULLIF(tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype-2, 0), 1) AS dr_accamount, IFNULL(drv_reg.cr_accamount, 0)*IFNULL(NULLIF(1-tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype, 0), 1) AS cr_accamount, tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype AS crdr FROM ";
		
		/*
		$rpt_sql .= "(SELECT DISTINCT subaccountno FROM tbl_account_allocation WHERE tbl_company_branch_idtbl_company_branch=?) AS drv_acc INNER JOIN tbl_subaccount ON drv_acc.subaccountno=tbl_subaccount.subaccount INNER JOIN tbl_mainclass ON tbl_subaccount.mainclasscode=tbl_mainclass.code LEFT OUTER JOIN (SELECT subaccount, SUM(ac_open_balance) AS ac_open_balance FROM tbl_gl_account_balance_details WHERE tbl_master_idtbl_master=? GROUP BY subaccount) AS drv_open ON drv_acc.subaccountno=drv_open.subaccount LEFT OUTER JOIN ";
		*/
		$sql .= "(SELECT DISTINCT tbl_account_idtbl_account FROM tbl_account_allocation WHERE branchcompanybank=?) AS drv_acc INNER JOIN tbl_account ON drv_acc.tbl_account_idtbl_account=tbl_account.idtbl_account INNER JOIN tbl_account_category ON tbl_account.tbl_account_category_idtbl_account_category=tbl_account_category.idtbl_account_category LEFT OUTER JOIN (SELECT '' as tbl_account_idtbl_account, 0 AS ac_open_balance) AS drv_open ON drv_acc.tbl_account_idtbl_account=drv_open.tbl_account_idtbl_account LEFT OUTER JOIN ";
		
		/*
		$rpt_sql .= "(SELECT acccode, SUM(accamount*(crdr='D')) AS dr_accamount, SUM(accamount*(crdr='C')) AS cr_accamount FROM `tbl_account_transaction` WHERE `reversstatus`=0 AND `tbl_master_idtbl_master`=? AND `tradate`<=DATE(NOW()) GROUP BY acccode) AS drv_reg ON drv_acc.subaccountno=drv_reg.acccode WHERE tbl_subaccount.status=1 ORDER BY crdr DESC, drv_acc.subaccountno) AS drv_rpt ";
		*/
		$sql .= "(SELECT tbl_account_idtbl_account, SUM(accamount*(crdr='D')) AS dr_accamount, SUM(accamount*(crdr='C')) AS cr_accamount FROM `tbl_account_transaction` WHERE `reversstatus`=0 AND `tbl_master_idtbl_master`=? AND `tradate`<=DATE(NOW()) GROUP BY tbl_account_idtbl_account) AS drv_reg ON drv_acc.tbl_account_idtbl_account=drv_reg.tbl_account_idtbl_account WHERE tbl_account.status=1 ORDER BY crdr DESC, drv_acc.tbl_account_idtbl_account) AS drv_rpt ";
		
		// $sql .= "HAVING accamount>0";
		
		$trialbalance_result = $this->db->query($sql, array($branch_id, $report_period_id));
		
		return $trialbalance_result->result();
	}
	
	public function ledgerFolioOpenStockValue($branch_id, $acc_id){
		/*
		$pre_sql = "SELECT tbl_account_allocation.subaccountno, tbl_finacial_year.`desc` AS financial_year, tbl_master.idtbl_master, IFNULL(drv_open.ac_open_balance, 0) AS ac_open_balance FROM `tbl_account_allocation` INNER JOIN tbl_master ON tbl_account_allocation.`tbl_company_branch_idtbl_company_branch`=tbl_master.`tbl_company_branch_idtbl_company_branch` INNER JOIN tbl_finacial_year ON tbl_master.tbl_finacial_year_idtbl_finacial_year=tbl_finacial_year.idtbl_finacial_year LEFT OUTER JOIN (SELECT tbl_master_idtbl_master, subaccount, SUM(ac_open_balance) AS ac_open_balance FROM tbl_gl_account_balance_details GROUP BY tbl_master_idtbl_master, subaccount) AS drv_open ON (tbl_master.idtbl_master=drv_open.tbl_master_idtbl_master AND tbl_account_allocation.subaccountno=drv_open.subaccount) WHERE tbl_account_allocation.`tbl_company_branch_idtbl_company_branch`=? AND tbl_account_allocation.idtbl_account_allocation=? AND tbl_master.status=1 LIMIT 1";
		*/
		$sql = "SELECT tbl_account.accountno, tbl_finacial_year.`desc` AS financial_year, tbl_master.idtbl_master, IFNULL(drv_open.ac_open_balance, 0) AS ac_open_balance FROM `tbl_account_allocation` INNER JOIN ";
		
		$sql .= "tbl_account ON tbl_account_allocation.tbl_account_idtbl_account=tbl_account.idtbl_account ";
		
		$sql .= "INNER JOIN tbl_master ON tbl_account_allocation.`branchcompanybank`=tbl_master.`tbl_company_branch_idtbl_company_branch` INNER JOIN tbl_finacial_year ON tbl_master.tbl_finacial_year_idtbl_finacial_year=tbl_finacial_year.idtbl_finacial_year LEFT OUTER JOIN (SELECT '' AS tbl_master_idtbl_master, '' AS tbl_account_idtbl_account, 0 AS ac_open_balance) AS drv_open ON (tbl_master.idtbl_master=drv_open.tbl_master_idtbl_master AND tbl_account_allocation.tbl_account_idtbl_account=drv_open.tbl_account_idtbl_account) WHERE tbl_account_allocation.`branchcompanybank`=? AND tbl_account_allocation.tbl_account_idtbl_account=? AND tbl_master.status=1 LIMIT 1";
		
		$open_val = $this->db->query($sql, array($branch_id, $acc_id));//echo $this->db->last_query();die;
		
		return $open_val->row();
	}
	
	public function ledgerFolioDetails($branch_id, $acc_id, $report_period_id){
		/*
		$rpt_sql = "SELECT drv_reg.tradate, drv_reg.narration, drv_reg.accamount*((drv_reg.crdr='C')*IFNULL(NULLIF(1-tbl_mainclass.transactiontype, 0), 1)+(drv_reg.crdr='D')*IFNULL(NULLIF(tbl_mainclass.transactiontype-2, 0), 1)) AS accamount, drv_reg.crdr FROM (SELECT acccode, tradate, narration, accamount, crdr FROM `tbl_account_transaction` WHERE `acccode`=? AND `tbl_master_idtbl_master`=? AND `tradate`<=DATE(NOW())) AS drv_reg INNER JOIN tbl_subaccount ON drv_reg.acccode=tbl_subaccount.subaccount INNER JOIN tbl_mainclass ON tbl_subaccount.mainclasscode=tbl_mainclass.code ORDER BY drv_reg.crdr DESC, drv_reg.tradate ASC";
		*/
		$sql = "SELECT drv_reg.tradate, drv_reg.narration, drv_reg.accamount*((drv_reg.crdr='C')*IFNULL(NULLIF(1-tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype, 0), 1)+(drv_reg.crdr='D')*IFNULL(NULLIF(tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype-2, 0), 1)) AS accamount, drv_reg.crdr FROM (SELECT tbl_account_idtbl_account, tradate, narration, accamount, crdr FROM `tbl_account_transaction_full` WHERE tbl_company_branch_idtbl_company_branch=? AND `tbl_account_idtbl_account`=? AND `tbl_master_idtbl_master`=? AND `tradate`<=DATE(NOW())) AS drv_reg INNER JOIN tbl_account ON drv_reg.tbl_account_idtbl_account=tbl_account.idtbl_account INNER JOIN tbl_account_category ON tbl_account.tbl_account_category_idtbl_account_category=tbl_account_category.idtbl_account_category ORDER BY drv_reg.crdr DESC, drv_reg.tradate ASC";
		
		$ledger_folio = $this->db->query($sql, array($branch_id, $acc_id, $report_period_id));
		
		return $ledger_folio->result();
	}

	public function Getbalancesheetinfo(){
		$company = $this->input->post('company_id');
		$branch = $this->input->post('company_branch_id');
		$periodfrom = $this->input->post('period_from');
		$periodto = $this->input->post('period_upto');

		$sql="SELECT `daccount`.`idtbl_account`, `daccount`.`accountno`, `daccount`.`accountname`, `daccount`.`category`, `daccount`.`code`, `daccount`.`idtbl_account_category`, `daccount`.`tbl_account_subcategory_idtbl_account_subcategory`, `daccount`.`subcategory`, `daccount`.`idtbl_account_nestcategory`, `daccount`.`nestcategory`, IFNULL(`daccbal`.`openbal`, 0) AS `openbal`, ABS(IFNULL(`ddebit`.`debitamount`, 0)-IFNULL(`dcredit`.`creditamount`, 0)) AS `trabal`, ABS(IFNULL(`daccbal`.`openbal`, 0)+(IFNULL(`ddebit`.`debitamount`, 0)-IFNULL(`dcredit`.`creditamount`, 0))) AS `nettrabal`, IFNULL(`daccbal`.`openbal`, 0)+(IFNULL(`ddebit`.`debitamount`, 0)-IFNULL(`dcredit`.`creditamount`, 0)) AS `nettrabalreal` FROM (SELECT `tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`, `tbl_account`.`tbl_account_subcategory_idtbl_account_subcategory`, `tbl_account_category`.`category`, `tbl_account_category`.`code`, `tbl_account_category`.`idtbl_account_category`, `tbl_account_subcategory`.`subcategory`, `tbl_account_nestcategory`.`nestcategory`, `tbl_account_nestcategory`.`idtbl_account_nestcategory` FROM `tbl_account` LEFT JOIN `tbl_account_category` ON `tbl_account_category`.`idtbl_account_category`=`tbl_account`.`tbl_account_category_idtbl_account_category` LEFT JOIN `tbl_account_subcategory` ON `tbl_account_subcategory`.`idtbl_account_subcategory`=`tbl_account`.`tbl_account_subcategory_idtbl_account_subcategory` LEFT JOIN `tbl_account_open_bal` ON `tbl_account_open_bal`.`tbl_account_idtbl_account`=`tbl_account`.`idtbl_account` LEFT JOIN `tbl_account_nestcategory` ON `tbl_account_nestcategory`.`idtbl_account_nestcategory`=`tbl_account`.`tbl_account_nestcategory_idtbl_account_nestcategory` LEFT JOIN `tbl_account_allocation` ON `tbl_account_allocation`.`tbl_account_idtbl_account`=`tbl_account`.`idtbl_account` WHERE `tbl_account`.`status`=? AND `tbl_account_category`.`tbl_account_finacialtype_idtbl_account_finacialtype`=? AND `tbl_account`.`tbl_account_nestcategory_idtbl_account_nestcategory`>? AND `tbl_account_allocation`.`companybank`=? AND `tbl_account_allocation`.`branchcompanybank`=?) AS `daccount` LEFT JOIN (SELECT `openbal`, `tbl_account_idtbl_account` FROM `tbl_account_open_bal` WHERE `status`=? AND `tbl_master_idtbl_master` BETWEEN ? AND ? AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=?) AS `daccbal` ON `daccbal`.`tbl_account_idtbl_account`=`daccount`.`idtbl_account` LEFT JOIN (SELECT SUM(`totamount`) AS `debitamount`, `tbl_account_idtbl_account`, `crdr` FROM `tbl_account_transaction` WHERE `status`=? AND `tbl_master_idtbl_master` BETWEEN ? AND ? AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=? AND `crdr`='D' GROUP BY `tbl_account_idtbl_account`) AS `ddebit` ON `ddebit`.`tbl_account_idtbl_account`=`daccount`.`idtbl_account` LEFT JOIN (SELECT SUM(`totamount`) AS `creditamount`, `tbl_account_idtbl_account`, `crdr` FROM `tbl_account_transaction` WHERE `status`=? AND `tbl_master_idtbl_master` BETWEEN ? AND ? AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=? AND `crdr`='C' GROUP BY `tbl_account_idtbl_account`) AS `dcredit` ON `dcredit`.`tbl_account_idtbl_account`=`daccount`.`idtbl_account` ORDER BY `daccount`.`idtbl_account_category`, `daccount`.`tbl_account_subcategory_idtbl_account_subcategory`, `daccount`.`idtbl_account_nestcategory` ASC";
		$respond = $this->db->query($sql, array(1, 2, 0, $company, $branch, 1, $periodfrom, $periodto, $company, $branch, 1, $periodfrom, $periodto, $company, $branch, 1, $periodfrom, $periodto, $company, $branch));

		return $respond;
		// print_r($this->db->last_query()); 
		// foreach($respond->result() AS $rowdatalist){

		// }
	}

	public function calculateNetProfitLoss($company_id, $branch_id, $period_from, $period_to) {
		// Calculate revenue (income accounts)
		$revenue_sql = "SELECT SUM(IFNULL(drv_crdr.dr_accamount, 0) - IFNULL(drv_crdr.cr_accamount, 0)) as revenue
						FROM tbl_account 
						INNER JOIN tbl_account_category ON tbl_account.tbl_account_category_idtbl_account_category = tbl_account_category.idtbl_account_category
						INNER JOIN tbl_account_allocation ON tbl_account.idtbl_account = tbl_account_allocation.tbl_account_idtbl_account
						LEFT JOIN (
							SELECT tbl_account_idtbl_account, 
								SUM(accamount*(crdr='D')) AS dr_accamount, 
								SUM(accamount*(crdr='C')) AS cr_accamount 
							FROM tbl_account_transaction 
							WHERE reversstatus = 0 
							AND tbl_master_idtbl_master BETWEEN ? AND ?
							GROUP BY tbl_account_idtbl_account
						) AS drv_crdr ON tbl_account.idtbl_account = drv_crdr.tbl_account_idtbl_account
						WHERE tbl_account_category.tbl_account_finacialtype_idtbl_account_finacialtype = 1 
						AND tbl_account_category.idtbl_account_category = 4 
						AND tbl_account_allocation.companybank = ? 
						AND tbl_account_allocation.branchcompanybank = ?";
		
		$revenue_result = $this->db->query($revenue_sql, array($period_from, $period_to, $company_id, $branch_id));
		$revenue = $revenue_result->row()->revenue ?? 0;

		// Calculate expenses (expense accounts)
		$expenses_sql = "SELECT SUM(IFNULL(drv_crdr.dr_accamount, 0) - IFNULL(drv_crdr.cr_accamount, 0)) as expenses
						FROM tbl_account 
						INNER JOIN tbl_account_category ON tbl_account.tbl_account_category_idtbl_account_category = tbl_account_category.idtbl_account_category
						INNER JOIN tbl_account_allocation ON tbl_account.idtbl_account = tbl_account_allocation.tbl_account_idtbl_account
						LEFT JOIN (
							SELECT tbl_account_idtbl_account, 
									SUM(accamount*(crdr='D')) AS dr_accamount, 
									SUM(accamount*(crdr='C')) AS cr_accamount 
							FROM tbl_account_transaction 
							WHERE reversstatus = 0 
							AND tbl_master_idtbl_master BETWEEN ? AND ?
							GROUP BY tbl_account_idtbl_account
						) AS drv_crdr ON tbl_account.idtbl_account = drv_crdr.tbl_account_idtbl_account
						WHERE tbl_account_category.tbl_account_finacialtype_idtbl_account_finacialtype = 1 
						AND tbl_account_category.idtbl_account_category = 2
						AND tbl_account_allocation.companybank = ? 
						AND tbl_account_allocation.branchcompanybank = ?";
		
		$expenses_result = $this->db->query($expenses_sql, array($period_from, $period_to, $company_id, $branch_id));
		$expenses = $expenses_result->row()->expenses ?? 0;
		
		return $revenue - $expenses;
	}

	public function DebtorReportData($from, $to, $debtorID){
		$companyID=$_SESSION['companyid'];
		$branchID=$_SESSION['branchid'];

		$sql="SELECT * FROM (SELECT `invno` AS `receiptno`, `invdate` AS `invpaydate`, `amount`, '' AS `narration`, 'D' AS `tratype`, '' AS `chequedate`, '' AS `chequeno` FROM `tbl_sales_info` WHERE `tbl_customer_idtbl_customer`=? AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=? AND `invdate` BETWEEN ? AND ? AND `status`=? UNION ALL SELECT `tbl_receivable_info`.`invoiceno` AS `receiptno`, `tbl_receivable`.`recdate` AS `invpaydate`, `tbl_receivable_info`.`amount`, `tbl_receivable_info`.`narration` AS `narration`, 'C' AS `tratype`, `tbl_receivable`.`chequedate`, `tbl_receivable`.`chequeno` FROM `tbl_receivable_info` LEFT JOIN `tbl_receivable` ON `tbl_receivable`.`idtbl_receivable`=`tbl_receivable_info`.`tbl_receivable_idtbl_receivable` WHERE `tbl_receivable`.`recdate` BETWEEN ? AND ? AND `tbl_receivable`.`status`=? AND `tbl_receivable`.`tbl_company_idtbl_company`=? AND `tbl_receivable`.`tbl_company_branch_idtbl_company_branch`=? AND `tbl_receivable`.`payer`=?) AS `u` ORDER BY `u`.`invpaydate` ASC";
		$respond = $this->db->query($sql, array($debtorID, $companyID, $branchID, $from, $to, 1, $from, $to, 1, $companyID, $branchID, $debtorID));
		
		return $respond;
	}

	public function DebtorOpenBalance($from, $debtorID){
		$companyID=$_SESSION['companyid'];
		$branchID=$_SESSION['branchid'];

		$sql="SELECT ((SELECT COALESCE(SUM(`amount`), 0) FROM `tbl_sales_info` WHERE `status`=? AND `invdate`<? AND `tbl_customer_idtbl_customer`=? AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=?)-(SELECT COALESCE(SUM(`amount`), 0) FROM `tbl_receivable` WHERE `status`=? AND `recdate`<? AND `payer`=? AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=?)) AS `openbalance`";
		$respond = $this->db->query($sql, array(1, $from, $debtorID, $companyID, $branchID, 1, $from, $debtorID, $companyID, $branchID));
		
		return $respond;
	}

	public function CreditorReportData($from, $to, $creditorID){
		$companyID=$_SESSION['companyid'];
		$branchID=$_SESSION['branchid'];

		$sql="SELECT * FROM (SELECT `tbl_account_paysettle`.`date` AS `repaydate`, `tbl_account_paysettle_info`.`invoiceno` AS `regrnno`, '' AS `expcode`, `tbl_account_paysettle_info`.`amount`, `tbl_account_paysettle_info`.`narration`, 'D' AS `tratype`, `tbl_cheque_issue`.`chedate`, `tbl_cheque_issue`.`chequeno` FROM `tbl_account_paysettle_info` LEFT JOIN `tbl_account_paysettle` ON `tbl_account_paysettle`.`idtbl_account_paysettle`=`tbl_account_paysettle_info`.`tbl_account_paysettle_idtbl_account_paysettle` LEFT JOIN `tbl_account_paysettle_has_tbl_cheque_issue` ON `tbl_account_paysettle_has_tbl_cheque_issue`.`tbl_account_paysettle_idtbl_account_paysettle`=`tbl_account_paysettle`.`idtbl_account_paysettle` LEFT JOIN `tbl_cheque_issue` ON `tbl_cheque_issue`.`idtbl_cheque_issue`=`tbl_account_paysettle_has_tbl_cheque_issue`.`tbl_cheque_issue_idtbl_cheque_issue` WHERE `tbl_account_paysettle_info`.`status`=? AND `tbl_account_paysettle`.`date` BETWEEN ? AND ? AND `tbl_account_paysettle`.`status`=? AND `tbl_account_paysettle`.`supplier`=? AND `tbl_account_paysettle`.`tbl_company_idtbl_company`=? AND `tbl_account_paysettle`.`tbl_company_branch_idtbl_company_branch`=? UNION ALL SELECT `grndate` AS `repaydate`, `grnno` AS `regrnno`, `expcode`, `amount`, '' AS `narration`, 'C' AS `tratype`, '' AS `chedate`, '' AS `chequeno` FROM `tbl_expence_info` WHERE `tbl_supplier_idtbl_supplier`=? AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=? AND `grndate` BETWEEN ? AND ?) AS `u` ORDER BY `u`.`repaydate` ASC";
		$respond = $this->db->query($sql, array(1, $from, $to, 1, $creditorID, $companyID, $branchID, $creditorID, $companyID, $branchID, $from, $to));
		
		return $respond;
	}

	public function CreditorrOpenBalance($from, $creditorID){
		$companyID=$_SESSION['companyid'];
		$branchID=$_SESSION['branchid'];

		$sql="SELECT ((SELECT COALESCE(SUM(`amount`), 0) FROM `tbl_expence_info` WHERE `status`=? AND `grndate`<? AND `tbl_supplier_idtbl_supplier`=? AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=?)-(SELECT COALESCE(SUM(`totalpayment`), 0) FROM `tbl_account_paysettle` WHERE `status`=? AND `date`<? AND `supplier`=? AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=?)) AS `openbalance`";
		$respond = $this->db->query($sql, array(1, $from, $creditorID, $companyID, $branchID, 1, $from, $creditorID, $companyID, $branchID));
		
		return $respond;
	}

	//============================================================================================================================================

	public function pnlSectionDetails($report_section, $from_master_id, $to_master_id, $companyid, $branchid) {
        // Get the date range from the master periods
        $period_range = $this->getPeriodRange($from_master_id, $to_master_id);
        $from_date = $period_range['from_date'];
        $to_date = $period_range['to_date'];
        
        $sql = "SELECT 
                    tbl_account_subcategory.idtbl_account_subcategory AS fig_sect_ref, 
                    tbl_account_subcategory.subcategory AS sect_name, 
                    CONCAT(tbl_account.accountno, ' - ', tbl_account.accountname) AS fig_name, 
                    ((IFNULL(drv_open.ac_open_balance, 0)*0) + 
                    ((IFNULL(drv_crdr.dr_accamount, 0) * 
                    IFNULL(NULLIF(tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype-2, 0), 1) + 
                    IFNULL(drv_crdr.cr_accamount, 0) * 
                    IFNULL(NULLIF(1-tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype, 0), 1)) * 1)) AS fig_value 
                FROM tbl_account_subcategory 
                INNER JOIN tbl_account_category ON tbl_account_subcategory.tbl_account_category_idtbl_account_category = tbl_account_category.idtbl_account_category 
                INNER JOIN tbl_account ON tbl_account_subcategory.idtbl_account_subcategory = tbl_account.tbl_account_subcategory_idtbl_account_subcategory 
                INNER JOIN tbl_account_allocation ON tbl_account.idtbl_account = tbl_account_allocation.tbl_account_idtbl_account 
                LEFT OUTER JOIN (SELECT '-1' as tbl_account_idtbl_account, 0 AS ac_open_balance) AS drv_open ON tbl_account.idtbl_account = drv_open.tbl_account_idtbl_account 
                LEFT OUTER JOIN (
                    SELECT at.tbl_account_idtbl_account, 
                           SUM(at.accamount*(at.crdr='D')) AS dr_accamount, 
                           SUM(at.accamount*(at.crdr='C')) AS cr_accamount 
                    FROM tbl_account_transaction at
                    INNER JOIN tbl_master m ON at.tbl_master_idtbl_master = m.idtbl_master
                    WHERE at.reversstatus = 0 
                    AND m.tbl_company_idtbl_company = ?
                    AND m.tbl_company_branch_idtbl_company_branch = ?
                    AND m.idtbl_master BETWEEN ? AND ?
                    GROUP BY at.tbl_account_idtbl_account
                ) AS drv_crdr ON tbl_account.idtbl_account = drv_crdr.tbl_account_idtbl_account 
                WHERE tbl_account_subcategory.tbl_account_category_idtbl_account_category = ? 
                AND tbl_account_subcategory.status = 1 
                AND tbl_account_allocation.companybank = ? 
                AND tbl_account_allocation.branchcompanybank = ? 
                AND tbl_account_allocation.status = 1
                AND tbl_account_category.tbl_account_finacialtype_idtbl_account_finacialtype = 1 
                AND tbl_account.status = 1 
                ORDER BY tbl_account.code, tbl_account.tbl_account_subcategory_idtbl_account_subcategory";
        
        $query = $this->db->query($sql, array(
            $companyid, $branchid, $from_master_id, $to_master_id, 
            $report_section, $companyid, $branchid
        ));
		// print_r($this->db->last_query()); 
        return $query->result_array();
    }
    
    public function getPeriodRange($from_master_id, $to_master_id) {
        $sql = "SELECT 
                    MIN(m.insertdatetime) as from_date,
                    MAX(m.insertdatetime) as to_date
                FROM tbl_master m
                WHERE m.idtbl_master BETWEEN ? AND ?";
        
        $query = $this->db->query($sql, array($from_master_id, $to_master_id));
        return $query->row_array();
    }
    
    public function getMasterDetails($master_id) {
        $sql = "SELECT 
                    m.idtbl_master, fy.year, fm.month, fm.monthname,
                    m.insertdatetime
                FROM tbl_master m
                INNER JOIN tbl_finacial_year fy ON m.tbl_finacial_year_idtbl_finacial_year = fy.idtbl_finacial_year
                INNER JOIN tbl_finacial_month fm ON m.tbl_finacial_month_idtbl_finacial_month = fm.idtbl_finacial_month
                WHERE m.idtbl_master = ?";
        
        $query = $this->db->query($sql, array($master_id));
        return $query->row_array();
    }
    
    public function getAllMasters($company_id, $branch_id) {
        return $this->db->select('m.idtbl_master, fy.year, fm.monthname, m.insertdatetime')
                        ->from('tbl_master m')
                        ->join('tbl_finacial_year fy', 'm.tbl_finacial_year_idtbl_finacial_year = fy.idtbl_finacial_year')
                        ->join('tbl_finacial_month fm', 'm.tbl_finacial_month_idtbl_finacial_month = fm.idtbl_finacial_month')
                        ->where('m.tbl_company_idtbl_company', $company_id)
                        ->where('m.tbl_company_branch_idtbl_company_branch', $branch_id)
                        ->where('m.status', 1)
                        ->order_by('m.insertdatetime', 'ASC')
                        ->get()
                        ->result_array();
    }
    
    public function calc_stock($opening_stock = false, $stock_date = '') {
        // if ($opening_stock) {
        //     // Calculate opening stock up to the specified date
        //     $sql = "SELECT SUM(tbl_print_stock.fullqty * tbl_print_stock.unitprice) AS stock_value 
        //             FROM tbl_print_stock 
        //             INNER JOIN tbl_product ON tbl_print_stock.tbl_product_idtbl_product = tbl_product.idtbl_product 
        //             WHERE tbl_print_stock.status = 1 
        //             AND tbl_print_stock.date <= ?";
        //     $query = $this->db->query($sql, array($stock_date));
        // } else {
        //     // Calculate closing stock (current stock)
        //     $sql = "SELECT SUM(tbl_print_stock.fullqty * tbl_print_stock.unitprice) AS stock_value 
        //             FROM tbl_print_stock 
        //             INNER JOIN tbl_product ON tbl_print_stock.tbl_product_idtbl_product = tbl_product.idtbl_product 
        //             WHERE tbl_print_stock.status = 1 
        //             AND tbl_print_stock.fullqty > 0";
        //     $query = $this->db->query($sql);
        // }
        
        // $result = $query->row_array();
        // return $result['stock_value'] ?? 0;
        return 0;
    }
}