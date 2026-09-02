<?php
class ReportModuleinfo extends CI_Model{
	private function getLastDateOfYearMonth($acc_period, $pref_date=''){
		if(!empty($acc_period)){
			$this->db->select('tbl_finacial_year.year as f_year, tbl_finacial_month.month as f_month, tbl_finacial_month.monthname as f_monthname');
			$this->db->from('tbl_master');
			$this->db->join('tbl_finacial_year', 'tbl_master.tbl_finacial_year_idtbl_finacial_year=tbl_finacial_year.idtbl_finacial_year');
			$this->db->join('tbl_finacial_month', 'tbl_master.tbl_finacial_month_idtbl_finacial_month=tbl_finacial_month.idtbl_finacial_month');
			$this->db->where('tbl_master.idtbl_master', $acc_period);
			
			$acc_ym = $this->db->get()->row();
			
			$act_year = str_pad($acc_ym->f_year, 4, '0000');
			$act_month = str_pad($acc_ym->f_month, 2, '00', STR_PAD_LEFT);
			$act_monthname = $acc_ym->f_monthname;
			$act_date = str_pad($pref_date, 2, '00', STR_PAD_LEFT);
			$date_str = '';
			
			if($pref_date!=''){
				// $date_str = $act_year.'-'.$act_month.'-'.$act_date;
				$date_str = $act_monthname.'-'.$act_date;
				$d = new DateTime($act_monthname.'-'.$act_date);
				$date_str = $d->format('Y-m-d');
			}else{
				$act_date = '01';
				// $d = new DateTime($act_year.'-'.$act_month.'-'.$act_date);
				$d = new DateTime($act_monthname.'-'.$act_date);
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
	
	// public function trialBalanceDetails($branch_id, $report_period_id){
	// 	$sql = "SELECT accname, (ac_open+dr_accamount+cr_accamount) AS accamount, crdr FROM ";
		
	// 	/*
	// 	$rpt_sql .= "(SELECT CONCAT(drv_acc.subaccountno, ' ', tbl_subaccount.subaccountname) AS accname, IFNULL(drv_open.ac_open_balance, 0) AS ac_open, IFNULL(drv_reg.dr_accamount, 0)*IFNULL(NULLIF(tbl_mainclass.transactiontype-2, 0), 1) AS dr_accamount, IFNULL(drv_reg.cr_accamount, 0)*IFNULL(NULLIF(1-tbl_mainclass.transactiontype, 0), 1) AS cr_accamount, tbl_mainclass.transactiontype AS crdr FROM ";
	// 	*/
	// 	$sql .= "(SELECT CONCAT(tbl_account.accountno, ' ', tbl_account.accountname) AS accname, IFNULL(drv_open.ac_open_balance, 0) AS ac_open, IFNULL(drv_reg.dr_accamount, 0)*IFNULL(NULLIF(tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype-2, 0), 1) AS dr_accamount, IFNULL(drv_reg.cr_accamount, 0)*IFNULL(NULLIF(1-tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype, 0), 1) AS cr_accamount, tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype AS crdr FROM ";
		
	// 	/*
	// 	$rpt_sql .= "(SELECT DISTINCT subaccountno FROM tbl_account_allocation WHERE tbl_company_branch_idtbl_company_branch=?) AS drv_acc INNER JOIN tbl_subaccount ON drv_acc.subaccountno=tbl_subaccount.subaccount INNER JOIN tbl_mainclass ON tbl_subaccount.mainclasscode=tbl_mainclass.code LEFT OUTER JOIN (SELECT subaccount, SUM(ac_open_balance) AS ac_open_balance FROM tbl_gl_account_balance_details WHERE tbl_master_idtbl_master=? GROUP BY subaccount) AS drv_open ON drv_acc.subaccountno=drv_open.subaccount LEFT OUTER JOIN ";
	// 	*/
	// 	$sql .= "(SELECT DISTINCT tbl_account_idtbl_account FROM tbl_account_allocation WHERE branchcompanybank=?) AS drv_acc INNER JOIN tbl_account ON drv_acc.tbl_account_idtbl_account=tbl_account.idtbl_account INNER JOIN tbl_account_category ON tbl_account.tbl_account_category_idtbl_account_category=tbl_account_category.idtbl_account_category LEFT OUTER JOIN (SELECT '' as tbl_account_idtbl_account, 0 AS ac_open_balance) AS drv_open ON drv_acc.tbl_account_idtbl_account=drv_open.tbl_account_idtbl_account LEFT OUTER JOIN ";
		
	// 	/*
	// 	$rpt_sql .= "(SELECT acccode, SUM(accamount*(crdr='D')) AS dr_accamount, SUM(accamount*(crdr='C')) AS cr_accamount FROM `tbl_account_transaction` WHERE `reversstatus`=0 AND `tbl_master_idtbl_master`=? AND `tradate`<=DATE(NOW()) GROUP BY acccode) AS drv_reg ON drv_acc.subaccountno=drv_reg.acccode WHERE tbl_subaccount.status=1 ORDER BY crdr DESC, drv_acc.subaccountno) AS drv_rpt ";
	// 	*/
	// 	$sql .= "(SELECT tbl_account_idtbl_account, SUM(accamount*(crdr='D')) AS dr_accamount, SUM(accamount*(crdr='C')) AS cr_accamount FROM `tbl_account_transaction` WHERE `reversstatus`=0 AND `tbl_master_idtbl_master`=? AND `tradate`<=DATE(NOW()) GROUP BY tbl_account_idtbl_account) AS drv_reg ON drv_acc.tbl_account_idtbl_account=drv_reg.tbl_account_idtbl_account WHERE tbl_account.status=1 ORDER BY crdr DESC, drv_acc.tbl_account_idtbl_account) AS drv_rpt ";
		
	// 	// $sql .= "HAVING accamount>0";
		
	// 	$trialbalance_result = $this->db->query($sql, array($branch_id, $report_period_id));
		
	// 	return $trialbalance_result->result();
	// }

	// Change on 03/06/2026
	// public function trialBalanceDetails($branch_id, $report_period_id){
	// 	$sql = "SELECT accname, (ac_open+dr_accamount+cr_accamount) AS accamount, crdr FROM ";
		
	// 	/*
	// 	$rpt_sql .= "(SELECT CONCAT(drv_acc.subaccountno, ' ', tbl_subaccount.subaccountname) AS accname, IFNULL(drv_open.ac_open_balance, 0) AS ac_open, IFNULL(drv_reg.dr_accamount, 0)*IFNULL(NULLIF(tbl_mainclass.transactiontype-2, 0), 1) AS dr_accamount, IFNULL(drv_reg.cr_accamount, 0)*IFNULL(NULLIF(1-tbl_mainclass.transactiontype, 0), 1) AS cr_accamount, tbl_mainclass.transactiontype AS crdr FROM ";
	// 	*/
	// 	$sql .= "(SELECT CONCAT(tbl_account.accountno, ' ', tbl_account.accountname) AS accname, IFNULL(drv_open.ac_open_balance, 0) AS ac_open, IFNULL(drv_reg.dr_accamount, 0)*IFNULL(NULLIF(tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype-2, 0), 1) AS dr_accamount, IFNULL(drv_reg.cr_accamount, 0)*IFNULL(NULLIF(1-tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype, 0), 1) AS cr_accamount, tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype AS crdr FROM ";
		
	// 	/*
	// 	$rpt_sql .= "(SELECT DISTINCT subaccountno FROM tbl_account_allocation WHERE tbl_company_branch_idtbl_company_branch=?) AS drv_acc INNER JOIN tbl_subaccount ON drv_acc.subaccountno=tbl_subaccount.subaccount INNER JOIN tbl_mainclass ON tbl_subaccount.mainclasscode=tbl_mainclass.code LEFT OUTER JOIN (SELECT subaccount, SUM(ac_open_balance) AS ac_open_balance FROM tbl_gl_account_balance_details WHERE tbl_master_idtbl_master=? GROUP BY subaccount) AS drv_open ON drv_acc.subaccountno=drv_open.subaccount LEFT OUTER JOIN ";
	// 	*/
	// 	// FIX: drv_open now reads real opening balances from tbl_account_open_bal
	// 	$sql .= "(SELECT DISTINCT tbl_account_idtbl_account FROM tbl_account_allocation WHERE branchcompanybank=?) AS drv_acc INNER JOIN tbl_account ON drv_acc.tbl_account_idtbl_account=tbl_account.idtbl_account INNER JOIN tbl_account_category ON tbl_account.tbl_account_category_idtbl_account_category=tbl_account_category.idtbl_account_category LEFT OUTER JOIN (SELECT tbl_account_idtbl_account, IFNULL(SUM(openbal),0) AS ac_open_balance FROM tbl_account_open_bal WHERE status=1 AND tbl_master_idtbl_master=? GROUP BY tbl_account_idtbl_account) AS drv_open ON drv_acc.tbl_account_idtbl_account=drv_open.tbl_account_idtbl_account LEFT OUTER JOIN ";
		
	// 	/*
	// 	$rpt_sql .= "(SELECT acccode, SUM(accamount*(crdr='D')) AS dr_accamount, SUM(accamount*(crdr='C')) AS cr_accamount FROM `tbl_account_transaction` WHERE `reversstatus`=0 AND `tbl_master_idtbl_master`=? AND `tradate`<=DATE(NOW()) GROUP BY acccode) AS drv_reg ON drv_acc.subaccountno=drv_reg.acccode WHERE tbl_subaccount.status=1 ORDER BY crdr DESC, drv_acc.subaccountno) AS drv_rpt ";
	// 	*/
	// 	$sql .= "(SELECT tbl_account_idtbl_account, SUM(accamount*(crdr='D')) AS dr_accamount, SUM(accamount*(crdr='C')) AS cr_accamount FROM `tbl_account_transaction` WHERE `reversstatus`=0 AND `tbl_master_idtbl_master`=? AND `tradate`<=DATE(NOW()) GROUP BY tbl_account_idtbl_account) AS drv_reg ON drv_acc.tbl_account_idtbl_account=drv_reg.tbl_account_idtbl_account WHERE tbl_account.status=1 ORDER BY crdr DESC, drv_acc.tbl_account_idtbl_account) AS drv_rpt ";
		
	// 	// $sql .= "HAVING accamount>0";
		
	// 	// FIX: pass branch_id, period_id for drv_open, then period_id for drv_reg transactions
	// 	$trialbalance_result = $this->db->query($sql, array($branch_id, $report_period_id, $report_period_id));
		
	// 	return $trialbalance_result->result();
	// }
	// Change on 04/06/2026
	// public function trialBalanceDetails($branch_id, $report_period_id){
	// 	$sql = "SELECT
	// 				drv_rpt.accname,
	// 				drv_rpt.accamount,
	// 				drv_rpt.crdr
	// 			FROM (
	// 				SELECT
	// 					CONCAT(tbl_account.accountno, ' ', tbl_account.accountname) AS accname,

	// 					-- Opening balance: use actual creditdebit column
	// 					-- C = add as credit side, D = add as debit side
	// 					-- Final amount = opening + transactions net movement
	// 					ABS(
	// 						-- Opening balance signed value
	// 						CASE WHEN IFNULL(drv_open.creditdebit, 'D') = 'D'
	// 							THEN  IFNULL(drv_open.ac_open_balance, 0)
	// 							ELSE -IFNULL(drv_open.ac_open_balance, 0)
	// 						END
	// 						+
	// 						-- Transaction net movement
	// 						-- DR transactions positive, CR transactions negative
	// 						IFNULL(drv_reg.dr_accamount, 0) - IFNULL(drv_reg.cr_accamount, 0)
	// 					) AS accamount,

	// 					-- Final crdr: determine from net signed value
	// 					CASE
	// 						WHEN (
	// 							CASE WHEN IFNULL(drv_open.creditdebit, 'D') = 'D'
	// 								THEN  IFNULL(drv_open.ac_open_balance, 0)
	// 								ELSE -IFNULL(drv_open.ac_open_balance, 0)
	// 							END
	// 							+ IFNULL(drv_reg.dr_accamount, 0) - IFNULL(drv_reg.cr_accamount, 0)
	// 						) >= 0 THEN 'D'
	// 						ELSE 'C'
	// 					END AS crdr,

	// 					tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype AS acc_type,
	// 					drv_acc.tbl_account_idtbl_account

	// 				FROM (
	// 					SELECT DISTINCT tbl_account_idtbl_account
	// 					FROM tbl_account_allocation
	// 					WHERE branchcompanybank = ?
	// 				) AS drv_acc

	// 				INNER JOIN tbl_account
	// 					ON drv_acc.tbl_account_idtbl_account = tbl_account.idtbl_account

	// 				INNER JOIN tbl_account_category
	// 					ON tbl_account.tbl_account_category_idtbl_account_category = tbl_account_category.idtbl_account_category

	// 				-- Opening balance with creditdebit column
	// 				LEFT OUTER JOIN (
	// 					SELECT
	// 						tbl_account_idtbl_account,
	// 						IFNULL(SUM(openbal), 0) AS ac_open_balance,
	// 						-- Get creditdebit from most recent open bal entry
	// 						MAX(creditdebit) AS creditdebit
	// 					FROM tbl_account_open_bal
	// 					WHERE status = 1
	// 					AND tbl_master_idtbl_master = ?
	// 					GROUP BY tbl_account_idtbl_account
	// 				) AS drv_open
	// 					ON drv_acc.tbl_account_idtbl_account = drv_open.tbl_account_idtbl_account

	// 				-- Transactions: DR and CR amounts
	// 				LEFT OUTER JOIN (
	// 					SELECT
	// 						tbl_account_idtbl_account,
	// 						SUM(accamount * (crdr = 'D')) AS dr_accamount,
	// 						SUM(accamount * (crdr = 'C')) AS cr_accamount
	// 					FROM tbl_account_transaction
	// 					WHERE reversstatus = 0
	// 					AND tbl_master_idtbl_master = ?
	// 					AND tradate <= DATE(NOW())
	// 					GROUP BY tbl_account_idtbl_account
	// 				) AS drv_reg
	// 					ON drv_acc.tbl_account_idtbl_account = drv_reg.tbl_account_idtbl_account

	// 				WHERE tbl_account.status = 1

	// 			) AS drv_rpt
	// 			ORDER BY drv_rpt.acc_type DESC, drv_rpt.tbl_account_idtbl_account ASC";

	// 	$trialbalance_result = $this->db->query($sql, [
	// 		$branch_id,           // drv_acc — allocation branch
	// 		$report_period_id,    // drv_open — opening balance period
	// 		$report_period_id     // drv_reg  — transactions period
	// 	]);

	// 	return $trialbalance_result->result();
	// }
	
	/**
	 * @param int   $branch_id
	 * @param mixed $report_period_id   single master ID  OR  array of master IDs
	 * @param int   $open_bal_period_id opening balance period (first period in range)
	*/

	public function trialBalanceDetails(
		$branch_id,
		$report_period_id,
		$open_bal_period_id = null   // ← new: first period opening balance
	){
		// Single period backward compatible
		if(is_array($report_period_id)){
			$master_ids      = $report_period_id;
			$open_bal_master = !empty($open_bal_period_id)
							? $open_bal_period_id
							: $master_ids[0];  // default = first period
		} else {
			$master_ids      = [$report_period_id];
			$open_bal_master = $report_period_id;
		}

		// Build IN clause safely
		$in_placeholders = implode(',', array_fill(0, count($master_ids), '?'));

		$sql  = "SELECT accname, accamount, crdr FROM ( ";
		$sql .= "SELECT ";
		$sql .= "    CONCAT(tbl_account.accountno, ' ', tbl_account.accountname) AS accname, ";

		// Opening balance signed value
		$sql .= "    ABS( ";
		$sql .= "        CASE WHEN IFNULL(drv_open.creditdebit, 'D') = 'D' ";
		$sql .= "             THEN  IFNULL(drv_open.ac_open_balance, 0) ";
		$sql .= "             ELSE -IFNULL(drv_open.ac_open_balance, 0) ";
		$sql .= "        END ";
		$sql .= "        + IFNULL(drv_reg.dr_accamount, 0) ";
		$sql .= "        - IFNULL(drv_reg.cr_accamount, 0) ";
		$sql .= "    ) AS accamount, ";

		// Final crdr from net signed total
		$sql .= "    CASE ";
		$sql .= "        WHEN ( ";
		$sql .= "            CASE WHEN IFNULL(drv_open.creditdebit, 'D') = 'D' ";
		$sql .= "                 THEN  IFNULL(drv_open.ac_open_balance, 0) ";
		$sql .= "                 ELSE -IFNULL(drv_open.ac_open_balance, 0) ";
		$sql .= "            END ";
		$sql .= "            + IFNULL(drv_reg.dr_accamount, 0) ";
		$sql .= "            - IFNULL(drv_reg.cr_accamount, 0) ";
		$sql .= "        ) >= 0 THEN 'D' ELSE 'C' ";
		$sql .= "    END AS crdr, ";
		$sql .= "    tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype AS acc_type, ";
		$sql .= "    drv_acc.tbl_account_idtbl_account ";

		// Allocated accounts
		$sql .= "FROM (SELECT DISTINCT tbl_account_idtbl_account FROM tbl_account_allocation WHERE branchcompanybank=?) AS drv_acc ";
		$sql .= "INNER JOIN tbl_account ON drv_acc.tbl_account_idtbl_account = tbl_account.idtbl_account ";
		$sql .= "INNER JOIN tbl_account_category ON tbl_account.tbl_account_category_idtbl_account_category = tbl_account_category.idtbl_account_category ";

		// Opening balance — FIRST period in range only
		$sql .= "LEFT OUTER JOIN ( ";
		$sql .= "    SELECT tbl_account_idtbl_account, ";
		$sql .= "           IFNULL(SUM(openbal), 0) AS ac_open_balance, ";
		$sql .= "           MAX(creditdebit) AS creditdebit ";
		$sql .= "    FROM tbl_account_open_bal ";
		$sql .= "    WHERE status = 1 ";
		$sql .= "    AND tbl_master_idtbl_master = ? ";  // ← first period only
		$sql .= "    GROUP BY tbl_account_idtbl_account ";
		$sql .= ") AS drv_open ON drv_acc.tbl_account_idtbl_account = drv_open.tbl_account_idtbl_account ";

		// Transactions — ALL periods in range (IN clause)
		$sql .= "LEFT OUTER JOIN ( ";
		$sql .= "    SELECT tbl_account_idtbl_account, ";
		$sql .= "           SUM(accamount * (crdr = 'D')) AS dr_accamount, ";
		$sql .= "           SUM(accamount * (crdr = 'C')) AS cr_accamount ";
		$sql .= "    FROM tbl_account_transaction ";
		$sql .= "    WHERE reversstatus = 0 ";
		$sql .= "    AND tbl_master_idtbl_master IN ($in_placeholders) "; // ← ALL periods
		$sql .= "    AND tradate <= DATE(NOW()) ";
		$sql .= "    GROUP BY tbl_account_idtbl_account ";
		$sql .= ") AS drv_reg ON drv_acc.tbl_account_idtbl_account = drv_reg.tbl_account_idtbl_account ";

		$sql .= "WHERE tbl_account.status = 1 ";
		$sql .= "ORDER BY acc_type DESC, drv_acc.tbl_account_idtbl_account ";
		$sql .= ") AS drv_rpt ";

		// Build params array
		$params = array_merge(
			[$branch_id],       // drv_acc
			[$open_bal_master], // drv_open — first period opening balance
			$master_ids         // drv_reg  — all period transactions
		);

		return $this->db->query($sql, $params)->result();
	}

	// public function ledgerFolioOpenStockValue($branch_id, $acc_id){
	// 	/*
	// 	$pre_sql = "SELECT tbl_account_allocation.subaccountno, tbl_finacial_year.`desc` AS financial_year, tbl_master.idtbl_master, IFNULL(drv_open.ac_open_balance, 0) AS ac_open_balance FROM `tbl_account_allocation` INNER JOIN tbl_master ON tbl_account_allocation.`tbl_company_branch_idtbl_company_branch`=tbl_master.`tbl_company_branch_idtbl_company_branch` INNER JOIN tbl_finacial_year ON tbl_master.tbl_finacial_year_idtbl_finacial_year=tbl_finacial_year.idtbl_finacial_year LEFT OUTER JOIN (SELECT tbl_master_idtbl_master, subaccount, SUM(ac_open_balance) AS ac_open_balance FROM tbl_gl_account_balance_details GROUP BY tbl_master_idtbl_master, subaccount) AS drv_open ON (tbl_master.idtbl_master=drv_open.tbl_master_idtbl_master AND tbl_account_allocation.subaccountno=drv_open.subaccount) WHERE tbl_account_allocation.`tbl_company_branch_idtbl_company_branch`=? AND tbl_account_allocation.idtbl_account_allocation=? AND tbl_master.status=1 LIMIT 1";
	// 	*/
	// 	$sql = "SELECT tbl_account.accountno, tbl_finacial_year.`desc` AS financial_year, tbl_master.idtbl_master, IFNULL(drv_open.ac_open_balance, 0) AS ac_open_balance FROM `tbl_account_allocation` INNER JOIN ";
		
	// 	$sql .= "tbl_account ON tbl_account_allocation.tbl_account_idtbl_account=tbl_account.idtbl_account ";
		
	// 	$sql .= "INNER JOIN tbl_master ON tbl_account_allocation.`branchcompanybank`=tbl_master.`tbl_company_branch_idtbl_company_branch` INNER JOIN tbl_finacial_year ON tbl_master.tbl_finacial_year_idtbl_finacial_year=tbl_finacial_year.idtbl_finacial_year LEFT OUTER JOIN (SELECT '' AS tbl_master_idtbl_master, '' AS tbl_account_idtbl_account, 0 AS ac_open_balance) AS drv_open ON (tbl_master.idtbl_master=drv_open.tbl_master_idtbl_master AND tbl_account_allocation.tbl_account_idtbl_account=drv_open.tbl_account_idtbl_account) WHERE tbl_account_allocation.`branchcompanybank`=? AND tbl_account_allocation.tbl_account_idtbl_account=? AND tbl_master.status=1 LIMIT 1";
		
	// 	$open_val = $this->db->query($sql, array($branch_id, $acc_id));//echo $this->db->last_query();die;
		
	// 	return $open_val->row();
	// }
	
	/**
	 * Get ledger folio opening balance for a specific account and period
	 *
	 * @param int $branch_id       branchcompanybank
	 * @param int $acc_id          tbl_account_idtbl_account
	 * @param int $open_bal_master tbl_master_idtbl_master (first period in range)
	 * @return object|null
	 */

	// public function ledgerFolioOpenStockValue($branch_id, $acc_id, $open_bal_master = null){

	// 	$sql = "SELECT
	// 				tbl_account.accountno,
	// 				tbl_finacial_year.`desc`         AS financial_year,
	// 				tbl_master.idtbl_master,

	// 				-- Opening balance: real value from tbl_account_open_bal
	// 				-- OLD: always 0 (empty subquery) ❌
	// 				-- NEW: actual openbal for the first period ✅
	// 				IFNULL(drv_open.ac_open_balance, 0) AS ac_open_balance,
	// 				IFNULL(drv_open.creditdebit, 'D')    AS creditdebit

	// 			FROM tbl_account_allocation

	// 			INNER JOIN tbl_account
	// 				ON tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account

	// 			INNER JOIN tbl_master
	// 				ON tbl_account_allocation.branchcompanybank = tbl_master.tbl_company_branch_idtbl_company_branch

	// 			INNER JOIN tbl_finacial_year
	// 				ON tbl_master.tbl_finacial_year_idtbl_finacial_year = tbl_finacial_year.idtbl_finacial_year

	// 			-- Real opening balance from tbl_account_open_bal ─────────────
	// 			LEFT OUTER JOIN (
	// 				SELECT
	// 					tbl_master_idtbl_master,
	// 					tbl_account_idtbl_account,
	// 					IFNULL(SUM(openbal), 0)  AS ac_open_balance,
	// 					MAX(creditdebit)          AS creditdebit
	// 				FROM tbl_account_open_bal
	// 				WHERE status = 1
	// 				AND tbl_master_idtbl_master = ?
	// 				GROUP BY tbl_account_idtbl_account
	// 			) AS drv_open
	// 				ON tbl_master.idtbl_master = drv_open.tbl_master_idtbl_master
	// 				AND tbl_account_allocation.tbl_account_idtbl_account = drv_open.tbl_account_idtbl_account

	// 			WHERE tbl_account_allocation.branchcompanybank = ?
	// 			AND tbl_account_allocation.tbl_account_idtbl_account = ?
	// 			AND tbl_master.idtbl_master = ?
	// 			AND tbl_master.status = 1
	// 			LIMIT 1";

	// 	$open_val = $this->db->query($sql, [
	// 		$open_bal_master,   // drv_open subquery period filter
	// 		$branch_id,         // allocation branch
	// 		$acc_id,            // account ID
	// 		$open_bal_master    // master period filter
	// 	]);

	// 	return $open_val->row();
	// }
	public function ledgerFolioOpenStockValue($branch_id, $acc_id, $open_bal_master = null, $detail_acc_id = null, $period_start_date = null){

		// ══════════════════════════════════════════════════════════════════
		// CASE 1: Chart Account — ORIGINAL logic, unchanged
		// ══════════════════════════════════════════════════════════════════
		if(empty($detail_acc_id)){

			$sql = "SELECT
						tbl_account.accountno,
						tbl_finacial_year.`desc`         AS financial_year,
						tbl_master.idtbl_master,
						IFNULL(drv_open.ac_open_balance, 0) AS ac_open_balance,
						IFNULL(drv_open.creditdebit, 'D')    AS creditdebit
					FROM tbl_account_allocation
					INNER JOIN tbl_account
						ON tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account
					INNER JOIN tbl_master
						ON tbl_account_allocation.branchcompanybank = tbl_master.tbl_company_branch_idtbl_company_branch
					INNER JOIN tbl_finacial_year
						ON tbl_master.tbl_finacial_year_idtbl_finacial_year = tbl_finacial_year.idtbl_finacial_year
					LEFT OUTER JOIN (
						SELECT
							tbl_master_idtbl_master,
							tbl_account_idtbl_account,
							IFNULL(SUM(openbal), 0)  AS ac_open_balance,
							MAX(creditdebit)          AS creditdebit
						FROM tbl_account_open_bal
						WHERE status = 1
						AND tbl_master_idtbl_master = ?
						GROUP BY tbl_account_idtbl_account
					) AS drv_open
						ON tbl_master.idtbl_master = drv_open.tbl_master_idtbl_master
						AND tbl_account_allocation.tbl_account_idtbl_account = drv_open.tbl_account_idtbl_account
					WHERE tbl_account_allocation.branchcompanybank = ?
					AND tbl_account_allocation.tbl_account_idtbl_account = ?
					AND tbl_master.idtbl_master = ?
					AND tbl_master.status = 1
					LIMIT 1";

			$open_val = $this->db->query($sql, [
				$open_bal_master,
				$branch_id,
				$acc_id,
				$open_bal_master
			]);

			return $open_val->row();
		}

		// ══════════════════════════════════════════════════════════════════
		// CASE 2: Detail Account — NEW: sum all transactions BEFORE period start
		// ══════════════════════════════════════════════════════════════════
		$sql = "SELECT
					SUM(CASE WHEN combined.crdr = 'D' THEN combined.accamount ELSE 0 END) AS total_debit,
					SUM(CASE WHEN combined.crdr = 'C' THEN combined.accamount ELSE 0 END) AS total_credit
				FROM (

					-- AR
					SELECT t.accamount, t.crdr
					FROM tbl_account_receivable ar
					JOIN tbl_account_transaction t
						ON t.trabatchotherno = ar.batchno
						AND t.accamount     = ar.amount
						AND t.crdr          = ar.tratype
						AND t.trabatchotherno LIKE 'AR%'
					WHERE ar.tbl_account_detail_idtbl_account_detail = ?
						AND t.tbl_company_branch_idtbl_company_branch = ?
						AND t.tbl_account_idtbl_account = ?
						AND t.tradate < ?

					UNION ALL

					-- AP
					SELECT t.accamount, t.crdr
					FROM tbl_account_payable ap
					JOIN tbl_account_transaction t
						ON t.trabatchotherno = ap.batchno
						AND t.accamount     = ap.amount
						AND t.crdr          = ap.tratype
						AND t.trabatchotherno LIKE 'AP%'
					WHERE ap.tbl_account_detail_idtbl_account_detail = ?
						AND t.tbl_company_branch_idtbl_company_branch = ?
						AND t.tbl_account_idtbl_account = ?
						AND t.tradate < ?

					UNION ALL

					-- JE
					SELECT t.accamount, t.crdr
					FROM tbl_account_transaction_manual je
					JOIN tbl_account_transaction t
						ON t.trabatchotherno = je.batchno
						AND t.accamount     = je.amount
						AND t.crdr          = je.crdr
						AND t.trabatchotherno LIKE 'JE%'
					WHERE je.tbl_account_detail_idtbl_account_detail = ?
						AND t.tbl_company_branch_idtbl_company_branch = ?
						AND t.tbl_account_idtbl_account = ?
						AND t.tradate < ?

					UNION ALL

					-- RE (via entry table)
					SELECT t.accamount, t.crdr
					FROM tbl_receivable re
					JOIN tbl_receivable_entry ree
						ON ree.tbl_receivable_idtbl_receivable = re.idtbl_receivable
					JOIN tbl_account_transaction t
						ON t.trabatchotherno = re.batchno
						AND t.accamount     = re.amount
						AND t.crdr          = ree.tratype
						AND t.trabatchotherno LIKE 'RE%'
					WHERE ree.tbl_account_detail_idtbl_account_detail = ?
						AND t.tbl_company_branch_idtbl_company_branch = ?
						AND t.tbl_account_idtbl_account = ?
						AND t.tradate < ?

					UNION ALL

					-- PS (via entry table)
					SELECT t.accamount, t.crdr
					FROM tbl_account_paysettle ps
					JOIN tbl_account_paysettle_entry pse
						ON pse.tbl_account_paysettle_idtbl_account_paysettle = ps.idtbl_account_paysettle
					JOIN tbl_account_transaction t
						ON t.trabatchotherno = ps.batchno
						AND t.accamount     = ps.totalpayment
						AND t.crdr          = pse.tratype
						AND t.trabatchotherno LIKE 'PS%'
					WHERE pse.tbl_account_detail_idtbl_account_detail = ?
						AND t.tbl_company_branch_idtbl_company_branch = ?
						AND t.tbl_account_idtbl_account = ?
						AND t.tradate < ?

				) AS combined";

		$branch_params = [$detail_acc_id, $branch_id, $acc_id, $period_start_date];
		$params = array_merge(
			$branch_params,   // AR
			$branch_params,   // AP
			$branch_params,   // JE
			$branch_params,   // RE
			$branch_params    // PS
		);

		$sum_row = $this->db->query($sql, $params)->row();

		// ── Net balance calculate: Debit - Credit ──────────────────────────
		$total_debit  = $sum_row->total_debit  ?? 0;
		$total_credit = $sum_row->total_credit ?? 0;
		$net          = $total_debit - $total_credit;

		// ── Account number (for display) — pull from tbl_account_detail ────
		$acc_no = $this->db->select('accountno')
							->get_where('tbl_account_detail', [
								'idtbl_account_detail' => $detail_acc_id
							])
							->row();

		$result = new stdClass();
		$result->accountno       = $acc_no->accountno ?? '';
		$result->ac_open_balance = abs($net);
		$result->creditdebit     = ($net >= 0) ? 'D' : 'C';

		return $result;
	}

	// public function ledgerFolioDetails($branch_id, $acc_id, $chartAccType, $report_period_id, $open_bal_master = null){
	// 	// Single period backward compatible — array or single ID handle
	// 	if(is_array($report_period_id)){
	// 		$master_ids      = $report_period_id;
	// 		$open_bal_master = !empty($open_bal_master) ? $open_bal_master : $master_ids[0];
	// 	} else {
	// 		$master_ids      = [$report_period_id];
	// 		$open_bal_master = $report_period_id;
	// 	}

	// 	$in_placeholders = implode(',', array_fill(0, count($master_ids), '?'));

	// 	if($chartAccType == 1){
	// 		$sql = "SELECT
	// 			drv_reg.tradate,
	// 			drv_reg.narration,
	// 			drv_reg.accamount,   -- ← simply use raw amount, always positive
	// 			drv_reg.crdr
	// 		FROM (
	// 			SELECT
	// 				tbl_account_idtbl_account,
	// 				tradate,
	// 				narration,
	// 				accamount,
	// 				crdr
	// 			FROM tbl_account_transaction_full
	// 			WHERE tbl_company_branch_idtbl_company_branch = ?
	// 			AND tbl_account_idtbl_account = ?
	// 			AND tbl_master_idtbl_master IN ($in_placeholders)
	// 			AND tradate <= DATE(NOW())
	// 		) AS drv_reg
	// 		INNER JOIN tbl_account
	// 			ON drv_reg.tbl_account_idtbl_account = tbl_account.idtbl_account
	// 		INNER JOIN tbl_account_category cat
	// 			ON tbl_account.tbl_account_category_idtbl_account_category = cat.idtbl_account_category
	// 		ORDER BY drv_reg.tradate ASC, drv_reg.crdr DESC";

	// 		// params: branch_id, acc_id, then all master_ids
	// 		$params = array_merge([$branch_id, $acc_id], $master_ids);

	// 		$ledger_folio = $this->db->query($sql, $params);
	// 	}
	// 	else{
	// 		$sql = "SELECT tradate, narration, accamount, crdr FROM (

	// 			-- AR
	// 			SELECT t.tradate, t.narration, t.accamount, t.crdr
	// 			FROM tbl_account_receivable ar
	// 			JOIN tbl_account_transaction_full t
	// 				ON t.trabatchotherno = ar.batchno
	// 				AND t.accamount     = ar.amount
	// 				AND t.crdr          = ar.tratype
	// 				AND t.trabatchotherno LIKE 'AR%'
	// 			WHERE ar.tbl_account_detail_idtbl_account_detail = ?
	// 				AND t.tbl_company_branch_idtbl_company_branch = ?
	// 				AND t.tbl_account_idtbl_account = ?
	// 				AND t.tbl_master_idtbl_master IN ($in_placeholders)
	// 				AND t.tradate <= DATE(NOW())

	// 			UNION ALL

	// 			-- AP
	// 			SELECT t.tradate, t.narration, t.accamount, t.crdr
	// 			FROM tbl_account_payable ap
	// 			JOIN tbl_account_transaction_full t
	// 				ON t.trabatchotherno = ap.batchno
	// 				AND t.accamount     = ap.amount
	// 				AND t.crdr          = ap.tratype
	// 				AND t.trabatchotherno LIKE 'AP%'
	// 			WHERE ap.tbl_account_detail_idtbl_account_detail = ?
	// 				AND t.tbl_company_branch_idtbl_company_branch = ?
	// 				AND t.tbl_account_idtbl_account = ?
	// 				AND t.tbl_master_idtbl_master IN ($in_placeholders)
	// 				AND t.tradate <= DATE(NOW())

	// 			UNION ALL

	// 			-- JE
	// 			SELECT t.tradate, t.narration, t.accamount, t.crdr
	// 			FROM tbl_account_transaction_manual je
	// 			JOIN tbl_account_transaction_full t
	// 				ON t.trabatchotherno = je.batchno
	// 				AND t.accamount     = je.amount
	// 				AND t.crdr          = je.crdr
	// 				AND t.trabatchotherno LIKE 'JE%'
	// 			WHERE je.tbl_account_detail_idtbl_account_detail = ?
	// 				AND t.tbl_company_branch_idtbl_company_branch = ?
	// 				AND t.tbl_account_idtbl_account = ?
	// 				AND t.tbl_master_idtbl_master IN ($in_placeholders)
	// 				AND t.tradate <= DATE(NOW())

	// 			UNION ALL

	// 			-- RE (via entry table)
	// 			SELECT t.tradate, t.narration, t.accamount, t.crdr
	// 			FROM tbl_receivable re
	// 			JOIN tbl_receivable_entry ree
	// 				ON ree.tbl_receivable_idtbl_receivable = re.idtbl_receivable
	// 			JOIN tbl_account_transaction_full t
	// 				ON t.trabatchotherno = re.batchno
	// 				AND t.accamount     = re.amount
	// 				AND t.crdr          = ree.tratype
	// 				AND t.trabatchotherno LIKE 'RE%'
	// 			WHERE ree.tbl_account_detail_idtbl_account_detail = ?
	// 				AND t.tbl_company_branch_idtbl_company_branch = ?
	// 				AND t.tbl_account_idtbl_account = ?
	// 				AND t.tbl_master_idtbl_master IN ($in_placeholders)
	// 				AND t.tradate <= DATE(NOW())

	// 			UNION ALL

	// 			-- PS (via entry table)
	// 			SELECT t.tradate, t.narration, t.accamount, t.crdr
	// 			FROM tbl_account_paysettle ps
	// 			JOIN tbl_account_paysettle_entry pse
	// 				ON pse.tbl_account_paysettle_idtbl_account_paysettle = ps.idtbl_account_paysettle
	// 			JOIN tbl_account_transaction_full t
	// 				ON t.trabatchotherno = ps.batchno
	// 				AND t.accamount     = ps.totalpayment
	// 				AND t.crdr          = pse.tratype
	// 				AND t.trabatchotherno LIKE 'PS%'
	// 			WHERE pse.tbl_account_detail_idtbl_account_detail = ?
	// 				AND t.tbl_company_branch_idtbl_company_branch = ?
	// 				AND t.tbl_account_idtbl_account = ?
	// 				AND t.tbl_master_idtbl_master IN ($in_placeholders)
	// 				AND t.tradate <= DATE(NOW())

	// 		) AS combined
	// 		ORDER BY tradate ASC, crdr DESC";

	// 		// Each UNION branch needs: detail_acc_id, branch_id, acc_id, master_ids...
	// 		$branch_params = [$acc_id, $branch_id, $acc_id];
	// 		$params = array_merge(
	// 			$branch_params, $master_ids,   // AR
	// 			$branch_params, $master_ids,   // AP
	// 			$branch_params, $master_ids,   // JE
	// 			$branch_params, $master_ids,   // RE
	// 			$branch_params, $master_ids    // PS
	// 		);

	// 		$ledger_folio = $this->db->query($sql, $params);
	// 	}

	// 	return $ledger_folio->result();
	// }

	public function ledgerFolioDetails($branch_id, $acc_id, $chartAccType, $report_period_id, $open_bal_master = null, $detail_acc_id = null){
		// Single period backward compatible — array or single ID handle
		if(is_array($report_period_id)){
			$master_ids      = $report_period_id;
			$open_bal_master = !empty($open_bal_master) ? $open_bal_master : $master_ids[0];
		} else {
			$master_ids      = [$report_period_id];
			$open_bal_master = $report_period_id;
		}

		$in_placeholders = implode(',', array_fill(0, count($master_ids), '?'));

		if($chartAccType == 1){
			$sql = "SELECT
				drv_reg.tradate,
				drv_reg.narration,
				drv_reg.accamount,
				drv_reg.crdr
			FROM (
				SELECT
					tbl_account_idtbl_account,
					tradate,
					narration,
					accamount,
					crdr
				FROM tbl_account_transaction_full
				WHERE tbl_company_branch_idtbl_company_branch = ?
				AND tbl_account_idtbl_account = ?
				AND tbl_master_idtbl_master IN ($in_placeholders)
				AND tradate <= DATE(NOW())
				AND `status` = 1
			) AS drv_reg
			INNER JOIN tbl_account
				ON drv_reg.tbl_account_idtbl_account = tbl_account.idtbl_account
			INNER JOIN tbl_account_category cat
				ON tbl_account.tbl_account_category_idtbl_account_category = cat.idtbl_account_category
			ORDER BY drv_reg.tradate ASC, drv_reg.crdr DESC";

			$params = array_merge([$branch_id, $acc_id], $master_ids);

			$ledger_folio = $this->db->query($sql, $params);
		}
		else{
			$sql = "SELECT tradate, narration, accamount, crdr FROM (

				-- AR
				SELECT t.tradate, t.narration, t.accamount, t.crdr
				FROM tbl_account_receivable ar
				JOIN tbl_account_transaction t
					ON t.trabatchotherno = ar.batchno
					AND t.accamount     = ar.amount
					AND t.crdr          = ar.tratype
					AND t.trabatchotherno LIKE 'AR%'
				WHERE ar.tbl_account_detail_idtbl_account_detail = ?
					AND t.tbl_company_branch_idtbl_company_branch = ?
					AND t.tbl_account_idtbl_account = ?
					AND t.tbl_master_idtbl_master IN ($in_placeholders)
					AND t.tradate <= DATE(NOW())
					AND t.`status` = 1

				UNION ALL

				-- AP
				SELECT t.tradate, t.narration, t.accamount, t.crdr
				FROM tbl_account_payable ap
				JOIN tbl_account_transaction t
					ON t.trabatchotherno = ap.batchno
					AND t.accamount     = ap.amount
					AND t.crdr          = ap.tratype
					AND t.trabatchotherno LIKE 'AP%'
				WHERE ap.tbl_account_detail_idtbl_account_detail = ?
					AND t.tbl_company_branch_idtbl_company_branch = ?
					AND t.tbl_account_idtbl_account = ?
					AND t.tbl_master_idtbl_master IN ($in_placeholders)
					AND t.tradate <= DATE(NOW())
					AND t.`status` = 1

				UNION ALL

				-- JE
				SELECT t.tradate, t.narration, t.accamount, t.crdr
				FROM tbl_account_transaction_manual je
				JOIN tbl_account_transaction t
					ON t.trabatchotherno = je.batchno
					AND t.accamount     = je.amount
					AND t.crdr          = je.crdr
					AND t.trabatchotherno LIKE 'JE%'
				WHERE je.tbl_account_detail_idtbl_account_detail = ?
					AND t.tbl_company_branch_idtbl_company_branch = ?
					AND t.tbl_account_idtbl_account = ?
					AND t.tbl_master_idtbl_master IN ($in_placeholders)
					AND t.tradate <= DATE(NOW())
					AND t.`status` = 1

				UNION ALL

				-- RE (via entry table)
				SELECT t.tradate, t.narration, t.accamount, t.crdr
				FROM tbl_receivable re
				JOIN tbl_receivable_entry ree
					ON ree.tbl_receivable_idtbl_receivable = re.idtbl_receivable
				JOIN tbl_account_transaction t
					ON t.trabatchotherno = re.batchno
					AND t.accamount     = re.amount
					AND t.crdr          = ree.tratype
					AND t.trabatchotherno LIKE 'RE%'
				WHERE ree.tbl_account_detail_idtbl_account_detail = ?
					AND t.tbl_company_branch_idtbl_company_branch = ?
					AND t.tbl_account_idtbl_account = ?
					AND t.tbl_master_idtbl_master IN ($in_placeholders)
					AND t.tradate <= DATE(NOW())
					AND t.`status` = 1

				UNION ALL

				-- PS (via entry table)
				SELECT t.tradate, t.narration, t.accamount, t.crdr
				FROM tbl_account_paysettle ps
				JOIN tbl_account_paysettle_entry pse
					ON pse.tbl_account_paysettle_idtbl_account_paysettle = ps.idtbl_account_paysettle
				JOIN tbl_account_transaction t
					ON t.trabatchotherno = ps.batchno
					AND t.accamount     = ps.totalpayment
					AND t.crdr          = pse.tratype
					AND t.trabatchotherno LIKE 'PS%'
				WHERE pse.tbl_account_detail_idtbl_account_detail = ?
					AND t.tbl_company_branch_idtbl_company_branch = ?
					AND t.tbl_account_idtbl_account = ?
					AND t.tbl_master_idtbl_master IN ($in_placeholders)
					AND t.tradate <= DATE(NOW())
					AND t.`status` = 1

			) AS combined
			ORDER BY tradate ASC, crdr DESC";

			// ★ FIX: detail_acc_id (filter) සහ acc_id (chart, JOIN safety) වෙනස් values
			$branch_params = [$detail_acc_id, $branch_id, $acc_id];
			$params = array_merge(
				$branch_params, $master_ids,   // AR
				$branch_params, $master_ids,   // AP
				$branch_params, $master_ids,   // JE
				$branch_params, $master_ids,   // RE
				$branch_params, $master_ids    // PS
			);

			$ledger_folio = $this->db->query($sql, $params);
		}

		return $ledger_folio->result();
	}

	// public function Getbalancesheetinfo(){
	// 	$company = $this->input->post('company_id');
	// 	$branch = $this->input->post('company_branch_id');
	// 	$periodfrom = $this->input->post('period_from');
	// 	$periodto = $this->input->post('period_upto');

	// 	$sql="SELECT `daccount`.`idtbl_account`, `daccount`.`accountno`, `daccount`.`accountname`, `daccount`.`category`, `daccount`.`code`, `daccount`.`idtbl_account_category`, `daccount`.`tbl_account_subcategory_idtbl_account_subcategory`, `daccount`.`subcategory`, `daccount`.`idtbl_account_nestcategory`, `daccount`.`nestcategory`, IFNULL(`daccbal`.`openbal`, 0) AS `openbal`, ABS(IFNULL(`ddebit`.`debitamount`, 0)-IFNULL(`dcredit`.`creditamount`, 0)) AS `trabal`, ABS(IFNULL(`daccbal`.`openbal`, 0)+(IFNULL(`ddebit`.`debitamount`, 0)-IFNULL(`dcredit`.`creditamount`, 0))) AS `nettrabal`, IFNULL(`daccbal`.`openbal`, 0)+(IFNULL(`ddebit`.`debitamount`, 0)-IFNULL(`dcredit`.`creditamount`, 0)) AS `nettrabalreal` FROM (SELECT `tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`, `tbl_account`.`tbl_account_subcategory_idtbl_account_subcategory`, `tbl_account_category`.`category`, `tbl_account_category`.`code`, `tbl_account_category`.`idtbl_account_category`, `tbl_account_subcategory`.`subcategory`, `tbl_account_nestcategory`.`nestcategory`, `tbl_account_nestcategory`.`idtbl_account_nestcategory` FROM `tbl_account` LEFT JOIN `tbl_account_category` ON `tbl_account_category`.`idtbl_account_category`=`tbl_account`.`tbl_account_category_idtbl_account_category` LEFT JOIN `tbl_account_subcategory` ON `tbl_account_subcategory`.`idtbl_account_subcategory`=`tbl_account`.`tbl_account_subcategory_idtbl_account_subcategory` LEFT JOIN `tbl_account_open_bal` ON `tbl_account_open_bal`.`tbl_account_idtbl_account`=`tbl_account`.`idtbl_account` LEFT JOIN `tbl_account_nestcategory` ON `tbl_account_nestcategory`.`idtbl_account_nestcategory`=`tbl_account`.`tbl_account_nestcategory_idtbl_account_nestcategory` LEFT JOIN `tbl_account_allocation` ON `tbl_account_allocation`.`tbl_account_idtbl_account`=`tbl_account`.`idtbl_account` WHERE `tbl_account`.`status`=? AND `tbl_account_category`.`tbl_account_finacialtype_idtbl_account_finacialtype`=? AND `tbl_account`.`tbl_account_nestcategory_idtbl_account_nestcategory`>? AND `tbl_account_allocation`.`companybank`=? AND `tbl_account_allocation`.`branchcompanybank`=?) AS `daccount` LEFT JOIN (SELECT `openbal`, `tbl_account_idtbl_account` FROM `tbl_account_open_bal` WHERE `status`=? AND `tbl_master_idtbl_master` BETWEEN ? AND ? AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=?) AS `daccbal` ON `daccbal`.`tbl_account_idtbl_account`=`daccount`.`idtbl_account` LEFT JOIN (SELECT SUM(`totamount`) AS `debitamount`, `tbl_account_idtbl_account`, `crdr` FROM `tbl_account_transaction` WHERE `status`=? AND `tbl_master_idtbl_master` BETWEEN ? AND ? AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=? AND `crdr`='D' GROUP BY `tbl_account_idtbl_account`) AS `ddebit` ON `ddebit`.`tbl_account_idtbl_account`=`daccount`.`idtbl_account` LEFT JOIN (SELECT SUM(`totamount`) AS `creditamount`, `tbl_account_idtbl_account`, `crdr` FROM `tbl_account_transaction` WHERE `status`=? AND `tbl_master_idtbl_master` BETWEEN ? AND ? AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=? AND `crdr`='C' GROUP BY `tbl_account_idtbl_account`) AS `dcredit` ON `dcredit`.`tbl_account_idtbl_account`=`daccount`.`idtbl_account` ORDER BY `daccount`.`idtbl_account_category`, `daccount`.`tbl_account_subcategory_idtbl_account_subcategory`, `daccount`.`idtbl_account_nestcategory` ASC";
	// 	$respond = $this->db->query($sql, array(1, 2, 0, $company, $branch, 1, $periodfrom, $periodto, $company, $branch, 1, $periodfrom, $periodto, $company, $branch, 1, $periodfrom, $periodto, $company, $branch));

	// 	return $respond;
	// 	// print_r($this->db->last_query()); 
	// 	// foreach($respond->result() AS $rowdatalist){

	// 	// }
	// }

	// public function calculateNetProfitLoss($company_id, $branch_id, $period_from, $period_to) {
	// 	// Calculate revenue (income accounts)
	// 	$revenue_sql = "SELECT SUM(IFNULL(drv_crdr.dr_accamount, 0) - IFNULL(drv_crdr.cr_accamount, 0)) as revenue
	// 					FROM tbl_account 
	// 					INNER JOIN tbl_account_category ON tbl_account.tbl_account_category_idtbl_account_category = tbl_account_category.idtbl_account_category
	// 					INNER JOIN tbl_account_allocation ON tbl_account.idtbl_account = tbl_account_allocation.tbl_account_idtbl_account
	// 					LEFT JOIN (
	// 						SELECT tbl_account_idtbl_account, 
	// 							SUM(accamount*(crdr='D')) AS dr_accamount, 
	// 							SUM(accamount*(crdr='C')) AS cr_accamount 
	// 						FROM tbl_account_transaction 
	// 						WHERE reversstatus = 0 
	// 						AND tbl_master_idtbl_master BETWEEN ? AND ?
	// 						GROUP BY tbl_account_idtbl_account
	// 					) AS drv_crdr ON tbl_account.idtbl_account = drv_crdr.tbl_account_idtbl_account
	// 					WHERE tbl_account_category.tbl_account_finacialtype_idtbl_account_finacialtype = 1 
	// 					AND tbl_account_category.idtbl_account_category = 4 
	// 					AND tbl_account_allocation.companybank = ? 
	// 					AND tbl_account_allocation.branchcompanybank = ?";
		
	// 	$revenue_result = $this->db->query($revenue_sql, array($period_from, $period_to, $company_id, $branch_id));
	// 	$revenue = $revenue_result->row()->revenue ?? 0;

	// 	// Calculate expenses (expense accounts)
	// 	$expenses_sql = "SELECT SUM(IFNULL(drv_crdr.dr_accamount, 0) - IFNULL(drv_crdr.cr_accamount, 0)) as expenses
	// 					FROM tbl_account 
	// 					INNER JOIN tbl_account_category ON tbl_account.tbl_account_category_idtbl_account_category = tbl_account_category.idtbl_account_category
	// 					INNER JOIN tbl_account_allocation ON tbl_account.idtbl_account = tbl_account_allocation.tbl_account_idtbl_account
	// 					LEFT JOIN (
	// 						SELECT tbl_account_idtbl_account, 
	// 								SUM(accamount*(crdr='D')) AS dr_accamount, 
	// 								SUM(accamount*(crdr='C')) AS cr_accamount 
	// 						FROM tbl_account_transaction 
	// 						WHERE reversstatus = 0 
	// 						AND tbl_master_idtbl_master BETWEEN ? AND ?
	// 						GROUP BY tbl_account_idtbl_account
	// 					) AS drv_crdr ON tbl_account.idtbl_account = drv_crdr.tbl_account_idtbl_account
	// 					WHERE tbl_account_category.tbl_account_finacialtype_idtbl_account_finacialtype = 1 
	// 					AND tbl_account_category.idtbl_account_category = 2
	// 					AND tbl_account_allocation.companybank = ? 
	// 					AND tbl_account_allocation.branchcompanybank = ?";
		
	// 	$expenses_result = $this->db->query($expenses_sql, array($period_from, $period_to, $company_id, $branch_id));
	// 	$expenses = $expenses_result->row()->expenses ?? 0;
		
	// 	return $revenue - $expenses;
	// }

	public function Getbalancesheetinfo(
		$company, $branch,
		$master_ids,        // array — single or multi period
		$open_bal_master    // first period master ID only
	){
		// Build IN placeholder for transaction periods
		$in_placeholders = implode(',', array_fill(0, count($master_ids), '?'));

		$sql = "SELECT
					daccount.idtbl_account,
					daccount.accountno,
					daccount.accountname,
					daccount.category,
					daccount.code,
					daccount.idtbl_account_category,
					daccount.tbl_account_subcategory_idtbl_account_subcategory,
					daccount.subcategory,
					daccount.idtbl_account_nestcategory,
					daccount.nestcategory,

					-- Opening balance: first period ONLY
					CASE
						WHEN IFNULL(daccbal.creditdebit, 'D') = 'D'
						THEN  IFNULL(daccbal.openbal, 0)
						ELSE -IFNULL(daccbal.openbal, 0)
					END AS openbal,

					-- Transaction balance: ALL periods
					IFNULL(ddebit.debitamount, 0) - IFNULL(dcredit.creditamount, 0) AS trabal,

					-- Net balance: opening + transactions
					ABS(
						CASE
							WHEN IFNULL(daccbal.creditdebit, 'D') = 'D'
							THEN  IFNULL(daccbal.openbal, 0)
							ELSE -IFNULL(daccbal.openbal, 0)
						END
						+ (IFNULL(ddebit.debitamount, 0) - IFNULL(dcredit.creditamount, 0))
					) AS nettrabal,

					-- Net balance signed (for P&L calculation)
					CASE
						WHEN IFNULL(daccbal.creditdebit, 'D') = 'D'
						THEN  IFNULL(daccbal.openbal, 0)
						ELSE -IFNULL(daccbal.openbal, 0)
					END
					+ (IFNULL(ddebit.debitamount, 0) - IFNULL(dcredit.creditamount, 0))
					AS nettrabalreal

				FROM (
					-- Account master info
					SELECT
						tbl_account.idtbl_account,
						tbl_account.accountno,
						tbl_account.accountname,
						tbl_account.tbl_account_subcategory_idtbl_account_subcategory,
						tbl_account_category.category,
						tbl_account_category.code,
						tbl_account_category.idtbl_account_category,
						tbl_account_subcategory.subcategory,
						tbl_account_nestcategory.nestcategory,
						tbl_account_nestcategory.idtbl_account_nestcategory
					FROM tbl_account
					LEFT JOIN tbl_account_category
						ON tbl_account_category.idtbl_account_category = tbl_account.tbl_account_category_idtbl_account_category
					LEFT JOIN tbl_account_subcategory
						ON tbl_account_subcategory.idtbl_account_subcategory = tbl_account.tbl_account_subcategory_idtbl_account_subcategory
					LEFT JOIN tbl_account_nestcategory
						ON tbl_account_nestcategory.idtbl_account_nestcategory = tbl_account.tbl_account_nestcategory_idtbl_account_nestcategory
					INNER JOIN tbl_account_allocation
						ON tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account
					WHERE tbl_account.status = ?
					AND tbl_account_category.tbl_account_finacialtype_idtbl_account_finacialtype = ?  -- BAL type (2)
					AND tbl_account_category.idtbl_account_category NOT IN (2, 4)  -- exclude EX, IN
					AND tbl_account.tbl_account_nestcategory_idtbl_account_nestcategory > ?
					AND tbl_account_allocation.companybank = ?
					AND tbl_account_allocation.branchcompanybank = ?
				) AS daccount

				-- Opening balance: first period ONLY ← fixed
				LEFT JOIN (
					SELECT
						tbl_account_idtbl_account,
						IFNULL(SUM(openbal), 0) AS openbal,
						MAX(creditdebit) AS creditdebit
					FROM tbl_account_open_bal
					WHERE status = ?
					AND tbl_master_idtbl_master = ?        -- first period only
					AND tbl_company_idtbl_company = ?
					AND tbl_company_branch_idtbl_company_branch = ?
					GROUP BY tbl_account_idtbl_account
				) AS daccbal ON daccbal.tbl_account_idtbl_account = daccount.idtbl_account

				-- Debit transactions: ALL periods IN clause ← fixed
				LEFT JOIN (
					SELECT
						tbl_account_idtbl_account,
						SUM(totamount) AS debitamount
					FROM tbl_account_transaction
					WHERE status = ?
					AND tbl_master_idtbl_master IN ($in_placeholders)
					AND tbl_company_idtbl_company = ?
					AND tbl_company_branch_idtbl_company_branch = ?
					AND crdr = 'D'
					GROUP BY tbl_account_idtbl_account
				) AS ddebit ON ddebit.tbl_account_idtbl_account = daccount.idtbl_account

				-- Credit transactions: ALL periods IN clause ← fixed
				LEFT JOIN (
					SELECT
						tbl_account_idtbl_account,
						SUM(totamount) AS creditamount
					FROM tbl_account_transaction
					WHERE status = ?
					AND tbl_master_idtbl_master IN ($in_placeholders)
					AND tbl_company_idtbl_company = ?
					AND tbl_company_branch_idtbl_company_branch = ?
					AND crdr = 'C'
					GROUP BY tbl_account_idtbl_account
				) AS dcredit ON dcredit.tbl_account_idtbl_account = daccount.idtbl_account

				ORDER BY
					daccount.idtbl_account_category,
					daccount.tbl_account_subcategory_idtbl_account_subcategory,
					daccount.idtbl_account_nestcategory ASC";

		$params = array_merge(
			[
				1,          // tbl_account.status
				2,          // finacialtype = BAL (2)
				0,          // nestcategory > 0
				$company,
				$branch,
				1,          // open_bal status
				$open_bal_master,  // ← first period only
				$company,
				$branch
			],
			[1],            // ddebit status
			$master_ids,    // ddebit IN periods
			[$company, $branch],
			[1],            // dcredit status
			$master_ids,    // dcredit IN periods
			[$company, $branch]
		);

		return $this->db->query($sql, $params);
	}

	public function calculateNetProfitLoss(
		$company_id, $branch_id,
		$master_ids,        // array
		$open_bal_master    // first period opening balance
	){
		$in_placeholders = implode(',', array_fill(0, count($master_ids), '?'));

		// ── Income (IN = category 4, CR normal) ───────────────────────────────
		$revenue_sql = "SELECT
							-- Opening balance signed
							SUM(
								CASE WHEN IFNULL(drv_open.creditdebit, 'C') = 'C'
									THEN  IFNULL(drv_open.openbal, 0)
									ELSE -IFNULL(drv_open.openbal, 0)
								END
							) AS open_income,
							-- Transaction net (CR - DR for income)
							SUM(
								IFNULL(drv_crdr.cr_accamount, 0) -
								IFNULL(drv_crdr.dr_accamount, 0)
							) AS tra_income
						FROM tbl_account
						INNER JOIN tbl_account_category
							ON tbl_account.tbl_account_category_idtbl_account_category = tbl_account_category.idtbl_account_category
						INNER JOIN tbl_account_allocation
							ON tbl_account.idtbl_account = tbl_account_allocation.tbl_account_idtbl_account
						LEFT JOIN (
							SELECT tbl_account_idtbl_account,
								IFNULL(SUM(openbal), 0) AS openbal,
								MAX(creditdebit) AS creditdebit
							FROM tbl_account_open_bal
							WHERE status = 1
							AND tbl_master_idtbl_master = ?
							GROUP BY tbl_account_idtbl_account
						) AS drv_open ON tbl_account.idtbl_account = drv_open.tbl_account_idtbl_account
						LEFT JOIN (
							SELECT tbl_account_idtbl_account,
								SUM(accamount * (crdr = 'D')) AS dr_accamount,
								SUM(accamount * (crdr = 'C')) AS cr_accamount
							FROM tbl_account_transaction
							WHERE reversstatus = 0
							AND tbl_master_idtbl_master IN ($in_placeholders)
							GROUP BY tbl_account_idtbl_account
						) AS drv_crdr ON tbl_account.idtbl_account = drv_crdr.tbl_account_idtbl_account
						WHERE tbl_account_category.idtbl_account_category = 4  -- IN
						AND tbl_account_allocation.companybank = ?
						AND tbl_account_allocation.branchcompanybank = ?";

		$revenue_row = $this->db->query($revenue_sql, array_merge(
			[$open_bal_master],
			$master_ids,
			[$company_id, $branch_id]
		))->row();

		$total_income = ($revenue_row->open_income ?? 0) + ($revenue_row->tra_income ?? 0);

		// ── Expenses (EX = category 2, DR normal) ─────────────────────────────
		$expenses_sql = "SELECT
							-- Opening balance signed
							SUM(
								CASE WHEN IFNULL(drv_open.creditdebit, 'D') = 'D'
									THEN  IFNULL(drv_open.openbal, 0)
									ELSE -IFNULL(drv_open.openbal, 0)
								END
							) AS open_expense,
							-- Transaction net (DR - CR for expense)
							SUM(
								IFNULL(drv_crdr.dr_accamount, 0) -
								IFNULL(drv_crdr.cr_accamount, 0)
							) AS tra_expense
						FROM tbl_account
						INNER JOIN tbl_account_category
							ON tbl_account.tbl_account_category_idtbl_account_category = tbl_account_category.idtbl_account_category
						INNER JOIN tbl_account_allocation
							ON tbl_account.idtbl_account = tbl_account_allocation.tbl_account_idtbl_account
						LEFT JOIN (
							SELECT tbl_account_idtbl_account,
								IFNULL(SUM(openbal), 0) AS openbal,
								MAX(creditdebit) AS creditdebit
							FROM tbl_account_open_bal
							WHERE status = 1
							AND tbl_master_idtbl_master = ?
							GROUP BY tbl_account_idtbl_account
						) AS drv_open ON tbl_account.idtbl_account = drv_open.tbl_account_idtbl_account
						LEFT JOIN (
							SELECT tbl_account_idtbl_account,
								SUM(accamount * (crdr = 'D')) AS dr_accamount,
								SUM(accamount * (crdr = 'C')) AS cr_accamount
							FROM tbl_account_transaction
							WHERE reversstatus = 0
							AND tbl_master_idtbl_master IN ($in_placeholders)
							GROUP BY tbl_account_idtbl_account
						) AS drv_crdr ON tbl_account.idtbl_account = drv_crdr.tbl_account_idtbl_account
						WHERE tbl_account_category.idtbl_account_category = 2  -- EX
						AND tbl_account_allocation.companybank = ?
						AND tbl_account_allocation.branchcompanybank = ?";

		$expenses_row = $this->db->query($expenses_sql, array_merge(
			[$open_bal_master],
			$master_ids,
			[$company_id, $branch_id]
		))->row();

		$total_expense = ($expenses_row->open_expense ?? 0) + ($expenses_row->tra_expense ?? 0);

		return $total_income - $total_expense;
		// Positive = Net Profit
		// Negative = Net Loss
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

	/**
	 * Get all tbl_master IDs between from_period and to_period
	 * Handles same year and cross-year ranges
	 *
	 * @param int $from_month_id  tbl_finacial_month idtbl_finacial_month (from)
	 * @param int $from_year_id   tbl_finacial_year idtbl_finacial_year (from)
	 * @param int $to_month_id    tbl_finacial_month idtbl_finacial_month (to)
	 * @param int $to_year_id     tbl_finacial_year idtbl_finacial_year (to)
	 * @param int $companyid
	 * @param int $branchid
	 * @return array [master_ids[], from_master_id, to_master_id, is_cross_year]
	 */

	// public function getPeriodRangeMasterIds(
	// 	$from_month_id, $from_year_id,
	// 	$to_month_id,   $to_year_id,
	// 	$companyid,     $branchid
	// ){
	// 	// Use fy.startdate for cross-year safe ordering
	// 	// month number alone cannot be compared across years
	// 	// 2025/2026: April=1, March=12  vs  2026/2027: April=1
	// 	$sql = "SELECT 
	// 				m.idtbl_master,
	// 				m.tbl_finacial_year_idtbl_finacial_year,
	// 				m.tbl_finacial_month_idtbl_finacial_month,
	// 				fy.startdate AS year_startdate,
	// 				fm.month AS month_num,
	// 				CONCAT(fy.year, '-', fm.monthname) AS period_label
	// 			FROM tbl_master m
	// 			INNER JOIN tbl_finacial_year fy
	// 				ON fy.idtbl_finacial_year = m.tbl_finacial_year_idtbl_finacial_year
	// 			INNER JOIN tbl_finacial_month fm
	// 				ON fm.idtbl_finacial_month = m.tbl_finacial_month_idtbl_finacial_month
	// 			-- from_year startdate and from_month num for lower bound
	// 			INNER JOIN tbl_finacial_year fy_from
	// 				ON fy_from.idtbl_finacial_year = ?
	// 			INNER JOIN tbl_finacial_month fm_from
	// 				ON fm_from.idtbl_finacial_month = ?
	// 			-- to_year startdate and to_month num for upper bound
	// 			INNER JOIN tbl_finacial_year fy_to
	// 				ON fy_to.idtbl_finacial_year = ?
	// 			INNER JOIN tbl_finacial_month fm_to
	// 				ON fm_to.idtbl_finacial_month = ?
	// 			WHERE m.tbl_company_idtbl_company = ?
	// 			AND m.tbl_company_branch_idtbl_company_branch = ?
	// 			AND m.status = 1
	// 			-- Lower bound: >= from period
	// 			AND (
	// 				fy.startdate > fy_from.startdate
	// 				OR (
	// 					fy.startdate = fy_from.startdate
	// 					AND fm.month >= fm_from.month
	// 				)
	// 			)
	// 			-- Upper bound: <= to period
	// 			AND (
	// 				fy.startdate < fy_to.startdate
	// 				OR (
	// 					fy.startdate = fy_to.startdate
	// 					AND fm.month <= fm_to.month
	// 				)
	// 			)
	// 			ORDER BY fy.startdate ASC, fm.month ASC";

	// 	$periods = $this->db->query($sql, [
	// 		$from_year_id, $from_month_id,
	// 		$to_year_id,   $to_month_id,
	// 		$companyid,    $branchid
	// 	])->result();
		
	// 	if(empty($periods)) return null;

	// 	$master_ids    = array_column((array)$periods, 'idtbl_master');
	// 	$is_cross_year = ($from_year_id != $to_year_id);

	// 	return [
	// 		'master_ids'     => $master_ids,           // [9, 10, 11]
	// 		'from_master_id' => $master_ids[0],         // first period
	// 		'to_master_id'   => end($master_ids),       // last period
	// 		'is_cross_year'  => $is_cross_year,         // true/false
	// 		'periods'        => $periods,               // full detail
	// 		'period_count'   => count($master_ids)
	// 	];
	// }

	public function getPeriodRangeMasterIds(
		$from_month_id, $from_year_id,
		$to_month_id,   $to_year_id,
		$companyid,     $branchid
	){
		// idtbl_finacial_month id eka thamai fiscal order eka (1=April...10=January...12=March)
		// 'month' column eka calendar month number ekak (April=4, Jan=1) - ehema fiscal
		// comparison ekakata use karanna baha, cross-calendar-year wrap wenawa (Jan=1 < April=4)
		$sql = "SELECT 
					m.idtbl_master,
					m.tbl_finacial_year_idtbl_finacial_year,
					m.tbl_finacial_month_idtbl_finacial_month,
					fy.startdate AS year_startdate,
					fm.idtbl_finacial_month AS fiscal_order,
					CONCAT(fy.year, '-', fm.monthname) AS period_label
				FROM tbl_master m
				INNER JOIN tbl_finacial_year fy
					ON fy.idtbl_finacial_year = m.tbl_finacial_year_idtbl_finacial_year
				INNER JOIN tbl_finacial_month fm
					ON fm.idtbl_finacial_month = m.tbl_finacial_month_idtbl_finacial_month
				INNER JOIN tbl_finacial_year fy_from
					ON fy_from.idtbl_finacial_year = ?
				INNER JOIN tbl_finacial_month fm_from
					ON fm_from.idtbl_finacial_month = ?
				INNER JOIN tbl_finacial_year fy_to
					ON fy_to.idtbl_finacial_year = ?
				INNER JOIN tbl_finacial_month fm_to
					ON fm_to.idtbl_finacial_month = ?
				WHERE m.tbl_company_idtbl_company = ?
				AND m.tbl_company_branch_idtbl_company_branch = ?
				AND m.status = 1
				-- Lower bound: >= from period (fiscal_order id use karanna, month column epa)
				AND (
					fy.startdate > fy_from.startdate
					OR (
						fy.startdate = fy_from.startdate
						AND fm.idtbl_finacial_month >= fm_from.idtbl_finacial_month
					)
				)
				-- Upper bound: <= to period
				AND (
					fy.startdate < fy_to.startdate
					OR (
						fy.startdate = fy_to.startdate
						AND fm.idtbl_finacial_month <= fm_to.idtbl_finacial_month
					)
				)
				ORDER BY fy.startdate ASC, fm.idtbl_finacial_month ASC";

		$periods = $this->db->query($sql, [
			$from_year_id, $from_month_id,
			$to_year_id,   $to_month_id,
			$companyid,    $branchid
		])->result();
		
		if(empty($periods)) return null;

		$master_ids    = array_column((array)$periods, 'idtbl_master');
		$is_cross_year = ($from_year_id != $to_year_id);

		return [
			'master_ids'     => $master_ids,
			'from_master_id' => $master_ids[0],
			'to_master_id'   => end($master_ids),
			'is_cross_year'  => $is_cross_year,
			'periods'        => $periods,
			'period_count'   => count($master_ids)
		];
	}

	/**
	 * Cash Flow Report
	 *
	 * @param int        $company_id
	 * @param int        $branch_id
	 * @param array|int  $report_period_id   — single or multi period master IDs
	 * @param int|null   $open_bal_period_id — first period opening balance master ID
	 * @return object
	 */

	// public function getCashFlowReport(
	// 	$company_id,
	// 	$branch_id,
	// 	$report_period_id,
	// 	$open_bal_period_id = null
	// ) {
	// 	// ── Period handling (backward compatible) ─────────────────────────────
	// 	if (is_array($report_period_id)) {
	// 		$master_ids      = $report_period_id;
	// 		$open_bal_master = !empty($open_bal_period_id)
	// 						? $open_bal_period_id
	// 						: $master_ids[0];
	// 	} else {
	// 		$master_ids      = [$report_period_id];
	// 		$open_bal_master = $open_bal_period_id ?? $report_period_id;
	// 	}

	// 	$in_placeholders = implode(',', array_fill(0, count($master_ids), '?'));

	// 	// ══════════════════════════════════════════════════════════════════════
	// 	// SECTION 1 — OPERATING ACTIVITIES
	// 	// INCOME      → idtbl_account_category = 4 (IN)  → CR normal
	// 	// EXPENDITURE → idtbl_account_category = 2 (EX)  → DR normal
	// 	// ══════════════════════════════════════════════════════════════════════
	// 	$operating_sql = "
	// 		SELECT
	// 			tbl_account.idtbl_account,
	// 			CONCAT(tbl_account.accountno, ' ', tbl_account.accountname) AS accname,
	// 			tbl_account_category.category,
	// 			tbl_account_category.idtbl_account_category,
	// 			tbl_account_category.code,
	// 			tbl_account_subcategory.subcategory,
	// 			tbl_account_nestcategory.nestcategory,

	// 			-- Opening balance signed
	// 			CASE
	// 				WHEN tbl_account_category.idtbl_account_category = 4
	// 				THEN
	// 					-- INCOME: CR normal
	// 					CASE WHEN IFNULL(drv_open.creditdebit, 'C') = 'C'
	// 						THEN  IFNULL(drv_open.openbal, 0)
	// 						ELSE -IFNULL(drv_open.openbal, 0)
	// 					END
	// 				ELSE
	// 					-- EXPENDITURE: DR normal
	// 					CASE WHEN IFNULL(drv_open.creditdebit, 'D') = 'D'
	// 						THEN  IFNULL(drv_open.openbal, 0)
	// 						ELSE -IFNULL(drv_open.openbal, 0)
	// 					END
	// 			END AS open_balance,

	// 			-- Transaction balance signed
	// 			CASE
	// 				WHEN tbl_account_category.idtbl_account_category = 4
	// 				THEN
	// 					-- INCOME: CR - DR
	// 					IFNULL(drv_reg.cr_accamount, 0) - IFNULL(drv_reg.dr_accamount, 0)
	// 				ELSE
	// 					-- EXPENDITURE: DR - CR
	// 					IFNULL(drv_reg.dr_accamount, 0) - IFNULL(drv_reg.cr_accamount, 0)
	// 			END AS tra_balance,

	// 			-- Net balance (opening + transaction)
	// 			CASE
	// 				WHEN tbl_account_category.idtbl_account_category = 4
	// 				THEN
	// 					(
	// 						CASE WHEN IFNULL(drv_open.creditdebit, 'C') = 'C'
	// 							THEN  IFNULL(drv_open.openbal, 0)
	// 							ELSE -IFNULL(drv_open.openbal, 0)
	// 						END
	// 					)
	// 					+ (IFNULL(drv_reg.cr_accamount, 0) - IFNULL(drv_reg.dr_accamount, 0))
	// 				ELSE
	// 					(
	// 						CASE WHEN IFNULL(drv_open.creditdebit, 'D') = 'D'
	// 							THEN  IFNULL(drv_open.openbal, 0)
	// 							ELSE -IFNULL(drv_open.openbal, 0)
	// 						END
	// 					)
	// 					+ (IFNULL(drv_reg.dr_accamount, 0) - IFNULL(drv_reg.cr_accamount, 0))
	// 			END AS net_balance

	// 		FROM tbl_account
	// 		INNER JOIN tbl_account_category
	// 			ON tbl_account.tbl_account_category_idtbl_account_category
	// 			= tbl_account_category.idtbl_account_category
	// 		LEFT JOIN tbl_account_subcategory
	// 			ON tbl_account_subcategory.idtbl_account_subcategory
	// 			= tbl_account.tbl_account_subcategory_idtbl_account_subcategory
	// 		LEFT JOIN tbl_account_nestcategory
	// 			ON tbl_account_nestcategory.idtbl_account_nestcategory
	// 			= tbl_account.tbl_account_nestcategory_idtbl_account_nestcategory
	// 		INNER JOIN tbl_account_allocation
	// 			ON tbl_account_allocation.tbl_account_idtbl_account
	// 			= tbl_account.idtbl_account

	// 		-- Opening balance: FIRST period ONLY
	// 		LEFT JOIN (
	// 			SELECT
	// 				tbl_account_idtbl_account,
	// 				IFNULL(SUM(openbal), 0) AS openbal,
	// 				MAX(creditdebit)        AS creditdebit
	// 			FROM tbl_account_open_bal
	// 			WHERE status                              = 1
	// 			AND   tbl_master_idtbl_master             = ?
	// 			AND   tbl_company_idtbl_company           = ?
	// 			AND   tbl_company_branch_idtbl_company_branch = ?
	// 			GROUP BY tbl_account_idtbl_account
	// 		) AS drv_open
	// 			ON drv_open.tbl_account_idtbl_account = tbl_account.idtbl_account

	// 		-- Transactions: ALL periods
	// 		LEFT JOIN (
	// 			SELECT
	// 				tbl_account_idtbl_account,
	// 				SUM(accamount * (crdr = 'D')) AS dr_accamount,
	// 				SUM(accamount * (crdr = 'C')) AS cr_accamount
	// 			FROM tbl_account_transaction
	// 			WHERE reversstatus             = 0
	// 			AND   tbl_master_idtbl_master IN ($in_placeholders)
	// 			GROUP BY tbl_account_idtbl_account
	// 		) AS drv_reg
	// 			ON drv_reg.tbl_account_idtbl_account = tbl_account.idtbl_account

	// 		WHERE tbl_account.status = 1
	// 		AND   tbl_account_category.idtbl_account_category IN (2, 4)  -- EX=2, IN=4
	// 		AND   tbl_account_allocation.companybank          = ?
	// 		AND   tbl_account_allocation.branchcompanybank    = ?

	// 		ORDER BY
	// 			tbl_account_category.idtbl_account_category DESC,   -- IN(4) first, EX(2) second
	// 			tbl_account.tbl_account_subcategory_idtbl_account_subcategory,
	// 			tbl_account.idtbl_account
	// 	";

	// 	$operating_params = array_merge(
	// 		[$open_bal_master, $company_id, $branch_id],  // drv_open
	// 		$master_ids,                                   // drv_reg IN
	// 		[$company_id, $branch_id]                      // WHERE allocation
	// 	);
		  
	// 	$operating_result = $this->db->query($operating_sql, $operating_params)->result();

	// 	// ══════════════════════════════════════════════════════════════════════
	// 	// SECTION 2 — INVESTING ACTIVITIES
	// 	// ASSETS → idtbl_account_category = 1 (AS) → DR normal
	// 	// DR increase = cash outflow (asset purchase)
	// 	// CR increase = cash inflow  (asset disposal)
	// 	// ══════════════════════════════════════════════════════════════════════
	// 	$investing_sql = "
	// 		SELECT
	// 			tbl_account.idtbl_account,
	// 			CONCAT(tbl_account.accountno, ' ', tbl_account.accountname) AS accname,
	// 			tbl_account_category.category,
	// 			tbl_account_category.code,
	// 			tbl_account_subcategory.subcategory,
	// 			tbl_account_nestcategory.nestcategory,

	// 			-- Opening balance signed (ASSET: DR normal)
	// 			CASE WHEN IFNULL(drv_open.creditdebit, 'D') = 'D'
	// 				THEN  IFNULL(drv_open.openbal, 0)
	// 				ELSE -IFNULL(drv_open.openbal, 0)
	// 			END AS open_balance,

	// 			-- Transaction net: DR - CR
	// 			IFNULL(drv_reg.dr_accamount, 0) - IFNULL(drv_reg.cr_accamount, 0)
	// 			AS tra_balance,

	// 			-- Net balance: opening + transaction
	// 			(
	// 				CASE WHEN IFNULL(drv_open.creditdebit, 'D') = 'D'
	// 					THEN  IFNULL(drv_open.openbal, 0)
	// 					ELSE -IFNULL(drv_open.openbal, 0)
	// 				END
	// 			)
	// 			+ (IFNULL(drv_reg.dr_accamount, 0) - IFNULL(drv_reg.cr_accamount, 0))
	// 			AS net_balance

	// 		FROM tbl_account
	// 		INNER JOIN tbl_account_category
	// 			ON tbl_account.tbl_account_category_idtbl_account_category
	// 			= tbl_account_category.idtbl_account_category
	// 		LEFT JOIN tbl_account_subcategory
	// 			ON tbl_account_subcategory.idtbl_account_subcategory
	// 			= tbl_account.tbl_account_subcategory_idtbl_account_subcategory
	// 		LEFT JOIN tbl_account_nestcategory
	// 			ON tbl_account_nestcategory.idtbl_account_nestcategory
	// 			= tbl_account.tbl_account_nestcategory_idtbl_account_nestcategory
	// 		INNER JOIN tbl_account_allocation
	// 			ON tbl_account_allocation.tbl_account_idtbl_account
	// 			= tbl_account.idtbl_account

	// 		-- Opening balance: FIRST period ONLY
	// 		LEFT JOIN (
	// 			SELECT
	// 				tbl_account_idtbl_account,
	// 				IFNULL(SUM(openbal), 0) AS openbal,
	// 				MAX(creditdebit)        AS creditdebit
	// 			FROM tbl_account_open_bal
	// 			WHERE status                              = 1
	// 			AND   tbl_master_idtbl_master             = ?
	// 			AND   tbl_company_idtbl_company           = ?
	// 			AND   tbl_company_branch_idtbl_company_branch = ?
	// 			GROUP BY tbl_account_idtbl_account
	// 		) AS drv_open
	// 			ON drv_open.tbl_account_idtbl_account = tbl_account.idtbl_account

	// 		-- Transactions: ALL periods
	// 		LEFT JOIN (
	// 			SELECT
	// 				tbl_account_idtbl_account,
	// 				SUM(totamount * (crdr = 'D')) AS dr_accamount,
	// 				SUM(totamount * (crdr = 'C')) AS cr_accamount
	// 			FROM tbl_account_transaction
	// 			WHERE status                   = 1
	// 			AND   tbl_master_idtbl_master IN ($in_placeholders)
	// 			AND   tbl_company_idtbl_company = ?
	// 			AND   tbl_company_branch_idtbl_company_branch = ?
	// 			AND   tradate                 <= DATE(NOW())
	// 			GROUP BY tbl_account_idtbl_account
	// 		) AS drv_reg
	// 			ON drv_reg.tbl_account_idtbl_account = tbl_account.idtbl_account

	// 		WHERE tbl_account.status = 1
	// 		AND   tbl_account_category.idtbl_account_category = 1   -- AS = ASSETS
	// 		AND   tbl_account.tbl_account_nestcategory_idtbl_account_nestcategory > 0
	// 		AND   tbl_account_allocation.companybank           = ?
	// 		AND   tbl_account_allocation.branchcompanybank     = ?

	// 		ORDER BY
	// 			tbl_account.tbl_account_subcategory_idtbl_account_subcategory,
	// 			tbl_account.idtbl_account
	// 	";

	// 	$investing_params = array_merge(
	// 		[$open_bal_master, $company_id, $branch_id],   // drv_open
	// 		$master_ids,                                    // drv_reg IN
	// 		[$company_id, $branch_id],                      // drv_reg WHERE
	// 		[$company_id, $branch_id]                       // WHERE allocation
	// 	);

	// 	$investing_result = $this->db->query($investing_sql, $investing_params)->result();

	// 	// ══════════════════════════════════════════════════════════════════════
	// 	// SECTION 3 — FINANCING ACTIVITIES
	// 	// LIABILITIES → idtbl_account_category = 3 (LI) → CR normal
	// 	// EQUITY      → idtbl_account_category = 5 (EQ) → CR normal
	// 	// CR increase = cash inflow  (new borrowing / equity raised)
	// 	// DR increase = cash outflow (loan repayment / dividend paid)
	// 	// ══════════════════════════════════════════════════════════════════════
	// 	$financing_sql = "
	// 		SELECT
	// 			tbl_account.idtbl_account,
	// 			CONCAT(tbl_account.accountno, ' ', tbl_account.accountname) AS accname,
	// 			tbl_account_category.category,
	// 			tbl_account_category.idtbl_account_category,
	// 			tbl_account_category.code,
	// 			tbl_account_subcategory.subcategory,
	// 			tbl_account_nestcategory.nestcategory,

	// 			-- Opening balance signed (LIABILITY/EQUITY: CR normal)
	// 			CASE WHEN IFNULL(drv_open.creditdebit, 'C') = 'C'
	// 				THEN  IFNULL(drv_open.openbal, 0)
	// 				ELSE -IFNULL(drv_open.openbal, 0)
	// 			END AS open_balance,

	// 			-- Transaction net: CR - DR
	// 			IFNULL(drv_reg.cr_accamount, 0) - IFNULL(drv_reg.dr_accamount, 0)
	// 			AS tra_balance,

	// 			-- Net balance: opening + transaction
	// 			(
	// 				CASE WHEN IFNULL(drv_open.creditdebit, 'C') = 'C'
	// 					THEN  IFNULL(drv_open.openbal, 0)
	// 					ELSE -IFNULL(drv_open.openbal, 0)
	// 				END
	// 			)
	// 			+ (IFNULL(drv_reg.cr_accamount, 0) - IFNULL(drv_reg.dr_accamount, 0))
	// 			AS net_balance

	// 		FROM tbl_account
	// 		INNER JOIN tbl_account_category
	// 			ON tbl_account.tbl_account_category_idtbl_account_category
	// 			= tbl_account_category.idtbl_account_category
	// 		LEFT JOIN tbl_account_subcategory
	// 			ON tbl_account_subcategory.idtbl_account_subcategory
	// 			= tbl_account.tbl_account_subcategory_idtbl_account_subcategory
	// 		LEFT JOIN tbl_account_nestcategory
	// 			ON tbl_account_nestcategory.idtbl_account_nestcategory
	// 			= tbl_account.tbl_account_nestcategory_idtbl_account_nestcategory
	// 		INNER JOIN tbl_account_allocation
	// 			ON tbl_account_allocation.tbl_account_idtbl_account
	// 			= tbl_account.idtbl_account

	// 		-- Opening balance: FIRST period ONLY
	// 		LEFT JOIN (
	// 			SELECT
	// 				tbl_account_idtbl_account,
	// 				IFNULL(SUM(openbal), 0) AS openbal,
	// 				MAX(creditdebit)        AS creditdebit
	// 			FROM tbl_account_open_bal
	// 			WHERE status                              = 1
	// 			AND   tbl_master_idtbl_master             = ?
	// 			AND   tbl_company_idtbl_company           = ?
	// 			AND   tbl_company_branch_idtbl_company_branch = ?
	// 			GROUP BY tbl_account_idtbl_account
	// 		) AS drv_open
	// 			ON drv_open.tbl_account_idtbl_account = tbl_account.idtbl_account

	// 		-- Transactions: ALL periods
	// 		LEFT JOIN (
	// 			SELECT
	// 				tbl_account_idtbl_account,
	// 				SUM(totamount * (crdr = 'D')) AS dr_accamount,
	// 				SUM(totamount * (crdr = 'C')) AS cr_accamount
	// 			FROM tbl_account_transaction
	// 			WHERE status                   = 1
	// 			AND   tbl_master_idtbl_master IN ($in_placeholders)
	// 			AND   tbl_company_idtbl_company = ?
	// 			AND   tbl_company_branch_idtbl_company_branch = ?
	// 			AND   tradate                 <= DATE(NOW())
	// 			GROUP BY tbl_account_idtbl_account
	// 		) AS drv_reg
	// 			ON drv_reg.tbl_account_idtbl_account = tbl_account.idtbl_account

	// 		WHERE tbl_account.status = 1
	// 		AND   tbl_account_category.idtbl_account_category IN (3, 5)  -- LI=3, EQ=5
	// 		AND   tbl_account.tbl_account_nestcategory_idtbl_account_nestcategory > 0
	// 		AND   tbl_account_allocation.companybank           = ?
	// 		AND   tbl_account_allocation.branchcompanybank     = ?

	// 		ORDER BY
	// 			tbl_account_category.idtbl_account_category,
	// 			tbl_account.tbl_account_subcategory_idtbl_account_subcategory,
	// 			tbl_account.idtbl_account
	// 	";

	// 	$financing_params = array_merge(
	// 		[$open_bal_master, $company_id, $branch_id],   // drv_open
	// 		$master_ids,                                    // drv_reg IN
	// 		[$company_id, $branch_id],                      // drv_reg WHERE
	// 		[$company_id, $branch_id]                       // WHERE allocation
	// 	);

	// 	$financing_result = $this->db->query($financing_sql, $financing_params)->result();

	// 	// ══════════════════════════════════════════════════════════════════════
	// 	// SECTION 4 — CASH & BANK OPENING / CLOSING BALANCE
	// 	// These are ASSET accounts (AS=1) that represent cash/bank
	// 	// Identified by subcategory — adjust subcategory IDs to match yours
	// 	// ══════════════════════════════════════════════════════════════════════
	// 	$cash_bank_sql = "
	// 		SELECT
	// 			tbl_account.idtbl_account,
	// 			CONCAT(tbl_account.accountno, ' ', tbl_account.accountname) AS accname,
	// 			tbl_account_category.category,
	// 			tbl_account_category.code,
	// 			tbl_account_subcategory.subcategory,
	// 			tbl_account_nestcategory.nestcategory,

	// 			-- Opening balance (ASSET: DR normal)
	// 			CASE WHEN IFNULL(drv_open.creditdebit, 'D') = 'D'
	// 				THEN  IFNULL(drv_open.openbal, 0)
	// 				ELSE -IFNULL(drv_open.openbal, 0)
	// 			END AS open_balance,

	// 			-- Cash inflow: DR transactions
	// 			IFNULL(drv_reg.dr_accamount, 0) AS cash_in,

	// 			-- Cash outflow: CR transactions
	// 			IFNULL(drv_reg.cr_accamount, 0) AS cash_out,

	// 			-- Closing balance: opening + DR - CR
	// 			(
	// 				CASE WHEN IFNULL(drv_open.creditdebit, 'D') = 'D'
	// 					THEN  IFNULL(drv_open.openbal, 0)
	// 					ELSE -IFNULL(drv_open.openbal, 0)
	// 				END
	// 			)
	// 			+ IFNULL(drv_reg.dr_accamount, 0)
	// 			- IFNULL(drv_reg.cr_accamount, 0)
	// 			AS closing_balance

	// 		FROM tbl_account
	// 		INNER JOIN tbl_account_category
	// 			ON tbl_account.tbl_account_category_idtbl_account_category
	// 			= tbl_account_category.idtbl_account_category
	// 		LEFT JOIN tbl_account_subcategory
	// 			ON tbl_account_subcategory.idtbl_account_subcategory
	// 			= tbl_account.tbl_account_subcategory_idtbl_account_subcategory
	// 		LEFT JOIN tbl_account_nestcategory
	// 			ON tbl_account_nestcategory.idtbl_account_nestcategory
	// 			= tbl_account.tbl_account_nestcategory_idtbl_account_nestcategory
	// 		INNER JOIN tbl_account_allocation
	// 			ON tbl_account_allocation.tbl_account_idtbl_account
	// 			= tbl_account.idtbl_account

	// 		-- Opening balance: FIRST period ONLY
	// 		LEFT JOIN (
	// 			SELECT
	// 				tbl_account_idtbl_account,
	// 				IFNULL(SUM(openbal), 0) AS openbal,
	// 				MAX(creditdebit)        AS creditdebit
	// 			FROM tbl_account_open_bal
	// 			WHERE status                              = 1
	// 			AND   tbl_master_idtbl_master             = ?
	// 			AND   tbl_company_idtbl_company           = ?
	// 			AND   tbl_company_branch_idtbl_company_branch = ?
	// 			GROUP BY tbl_account_idtbl_account
	// 		) AS drv_open
	// 			ON drv_open.tbl_account_idtbl_account = tbl_account.idtbl_account

	// 		-- Transactions: ALL periods
	// 		LEFT JOIN (
	// 			SELECT
	// 				tbl_account_idtbl_account,
	// 				SUM(totamount * (crdr = 'D')) AS dr_accamount,
	// 				SUM(totamount * (crdr = 'C')) AS cr_accamount
	// 			FROM tbl_account_transaction
	// 			WHERE status                   = 1
	// 			AND   tbl_master_idtbl_master IN ($in_placeholders)
	// 			AND   tbl_company_idtbl_company = ?
	// 			AND   tbl_company_branch_idtbl_company_branch = ?
	// 			AND   tradate                 <= DATE(NOW())
	// 			GROUP BY tbl_account_idtbl_account
	// 		) AS drv_reg
	// 			ON drv_reg.tbl_account_idtbl_account = tbl_account.idtbl_account

	// 		WHERE tbl_account.status = 1
	// 		AND   tbl_account_category.idtbl_account_category = 1       -- AS = ASSETS
	// 		AND   tbl_account.tbl_account_nestcategory_idtbl_account_nestcategory = 0
	// 		-- ↑ nestcategory = 0 means top-level cash/bank accounts
	// 		-- Adjust this condition to match your cash/bank account structure
	// 		AND   tbl_account_allocation.companybank           = ?
	// 		AND   tbl_account_allocation.branchcompanybank     = ?

	// 		ORDER BY tbl_account.idtbl_account
	// 	";

	// 	$cash_bank_params = array_merge(
	// 		[$open_bal_master, $company_id, $branch_id],   // drv_open
	// 		$master_ids,                                    // drv_reg IN
	// 		[$company_id, $branch_id],                      // drv_reg WHERE
	// 		[$company_id, $branch_id]                       // WHERE allocation
	// 	);

	// 	$cash_bank_result = $this->db->query($cash_bank_sql, $cash_bank_params)->result();

	// 	// ══════════════════════════════════════════════════════════════════════
	// 	// SECTION 5 — CALCULATE TOTALS & BUILD STRUCTURED RESPONSE
	// 	// ══════════════════════════════════════════════════════════════════════

	// 	// --- Operating: separate INCOME(4) and EXPENDITURE(2) ---
	// 	$income_items   = [];
	// 	$expense_items  = [];
	// 	$total_income   = 0;
	// 	$total_expense  = 0;

	// 	foreach ($operating_result as $row) {
	// 		if ($row->idtbl_account_category == 4) {
	// 			// IN = INCOME
	// 			$total_income    += $row->net_balance;
	// 			$income_items[]   = $row;
	// 		} else {
	// 			// EX = EXPENDITURE
	// 			$total_expense   += $row->net_balance;
	// 			$expense_items[]  = $row;
	// 		}
	// 	}

	// 	// Net cash from operations = Income - Expense
	// 	$net_operating = $total_income - $total_expense;

	// 	// --- Investing: ASSETS (AS=1) ---
	// 	// Asset increase = outflow → negate
	// 	$total_investing   = 0;
	// 	$investing_items   = [];

	// 	foreach ($investing_result as $row) {
	// 		// Positive net_balance = asset increased = cash outflow (negative)
	// 		$cash_flow_effect   = -$row->net_balance;
	// 		$total_investing   += $cash_flow_effect;

	// 		// Attach cash flow effect to row for view use
	// 		$row->cash_flow_effect = $cash_flow_effect;
	// 		$investing_items[]     = $row;
	// 	}

	// 	// --- Financing: LIABILITIES (LI=3) + EQUITY (EQ=5) ---
	// 	$total_financing    = 0;
	// 	$liability_items    = [];
	// 	$equity_items       = [];

	// 	foreach ($financing_result as $row) {
	// 		$total_financing += $row->net_balance;

	// 		if ($row->idtbl_account_category == 3) {
	// 			$liability_items[] = $row;  // LI
	// 		} else {
	// 			$equity_items[]    = $row;  // EQ
	// 		}
	// 	}

	// 	// --- Cash & Bank ---
	// 	$opening_cash = 0;
	// 	$closing_cash = 0;

	// 	foreach ($cash_bank_result as $row) {
	// 		$opening_cash += $row->open_balance;
	// 		$closing_cash += $row->closing_balance;
	// 	}

	// 	// --- Net Cash Change ---
	// 	$net_cash_change = $net_operating + $total_investing + $total_financing;

	// 	// --- Verification ---
	// 	// opening_cash + net_cash_change ≈ closing_cash
	// 	$verified = abs(($opening_cash + $net_cash_change) - $closing_cash) < 0.01;

	// 	// ══════════════════════════════════════════════════════════════════════
	// 	// RETURN STRUCTURED RESULT
	// 	// ══════════════════════════════════════════════════════════════════════
	// 	return (object)[

	// 		// ── Section 1: Operating Activities (IN=4, EX=2) ──────────────
	// 		'operating' => (object)[
	// 			'income'  => (object)[
	// 				'items' => $income_items,
	// 				'total' => $total_income,
	// 			],
	// 			'expense' => (object)[
	// 				'items' => $expense_items,
	// 				'total' => $total_expense,
	// 			],
	// 			'net_operating' => $net_operating,
	// 		],

	// 		// ── Section 2: Investing Activities (AS=1) ────────────────────
	// 		'investing' => (object)[
	// 			'items'         => $investing_items,
	// 			'net_investing' => $total_investing,
	// 			// Negative = cash used for asset purchase (outflow)
	// 			// Positive = cash received from asset disposal (inflow)
	// 		],

	// 		// ── Section 3: Financing Activities (LI=3, EQ=5) ─────────────
	// 		'financing' => (object)[
	// 			'liabilities' => (object)[
	// 				'items' => $liability_items,
	// 			],
	// 			'equity' => (object)[
	// 				'items' => $equity_items,
	// 			],
	// 			'net_financing' => $total_financing,
	// 			// Positive = new borrowing / equity raised (inflow)
	// 			// Negative = loan repayment / dividend paid (outflow)
	// 		],

	// 		// ── Section 4: Cash & Bank (AS=1, nestcategory=0) ────────────
	// 		'cash_bank' => (object)[
	// 			'items'        => $cash_bank_result,
	// 			'opening_cash' => $opening_cash,
	// 			'closing_cash' => $closing_cash,
	// 		],

	// 		// ── Section 5: Summary ─────────────────────────────────────────
	// 		'summary' => (object)[
	// 			'net_operating'   => $net_operating,
	// 			'net_investing'   => $total_investing,
	// 			'net_financing'   => $total_financing,
	// 			'net_cash_change' => $net_cash_change,
	// 			'opening_cash'    => $opening_cash,
	// 			'closing_cash'    => $closing_cash,
	// 			'verified'        => $verified,
	// 			// TRUE  = Balanced ✅
	// 			// FALSE = Check entries ⚠️
	// 		],
	// 	];
	// }

	
	/**
	 * DROP-IN REPLACEMENT for the existing getCashFlowReport() method
	 * in ReportModuleinfo.php.
	 *
	 * Switches the report from the old "direct method" (every income/expense
	 * account listed, every asset/liability listed) to the "indirect method"
	 * used in the target Excel template (QuickBooks-style):
	 *
	 *   OPERATING ACTIVITIES
	 *     Net Income                                   (Income total − Expense total)
	 *     Adjustments to reconcile Net Income
	 *     to net cash provided by operations:           (current asset/liability movements)
	 *   INVESTING ACTIVITIES                            (fixed asset / long-term investment movements)
	 *   FINANCING ACTIVITIES                            (loans / equity movements)
	 *   Net cash increase for period
	 *   Cash at beginning of period
	 *   Cash at end of period
	 *
	 * ─────────────────────────────────────────────────────────────────────
	 * KEY INSIGHT USED BELOW:
	 * For every non-cash balance-sheet account (asset OR liability/equity),
	 * the cash-flow effect of that account's movement during the period is
	 * always:
	 *
	 *      cash_effect = SUM(CR transactions) − SUM(DR transactions)
	 *
	 * This is true for BOTH sides because of double-entry symmetry:
	 *   - Asset increase (DR) uses cash      → effect = -(DR-CR) = CR-DR
	 *   - Liability/Equity increase (CR) provides cash → effect = CR-DR
	 * So one formula, one helper query, reused for Adjustments / Investing /
	 * Financing — only the nestcategory ID list changes per section.
	 * ─────────────────────────────────────────────────────────────────────
	 */

	public function getCashFlowReport(
		$company_id,
		$branch_id,
		$report_period_id,
		$open_bal_period_id = null
	) {
		// ── Period handling (unchanged, backward compatible) ──────────────────
		if (is_array($report_period_id)) {
			$master_ids      = $report_period_id;
			$open_bal_master = !empty($open_bal_period_id)
							? $open_bal_period_id
							: $master_ids[0];
		} else {
			$master_ids      = [$report_period_id];
			$open_bal_master = $open_bal_period_id ?? $report_period_id;
		}

		// ══════════════════════════════════════════════════════════════════════
		// NEST-CATEGORY CLASSIFICATION MAP
		// Update these arrays if a new nestcategory is added later, or if the
		// two flagged assumptions below turn out to be wrong.
		// ══════════════════════════════════════════════════════════════════════
		$investing_nestcat_ids = [1, 2, 18];
			// 1 Property Plant & Equipment, 2 Fixed Deposit, 18 Intangible Assets

		$operating_adj_nestcat_ids = [5, 6, 19, 20, 21, 24, 25, 30, 35, 36];
			// 5 Utility Payables, 6 Advance Received & Other Payables,
			// 19 Saleable Land Stock, 20 Trade Debtors, 21 Prepayments & Advance Paid,
			// 24 Salary Related Payables, 25 Customer Advance for Lots,
			// 30 Stock Transit Account, 35 Trade Creditors,
			// 36 Bank Related Payable  ← ASSUMPTION: treated as an operating payable.
			//    Move to $financing_nestcat_ids below if this is actually a bank
			//    loan / overdraft facility rather than a payable.

		$financing_nestcat_ids = [3, 4, 23, 33, 34];
			// 3 Long-Term Loans, 4 Inter-Company Liabilities,
			// 23 Short Term Loans  ← ASSUMPTION: treated as financing (it's a
			//    borrowing) even though its subcategory is "Current Liabilities".
			//    Move to $operating_adj_nestcat_ids above if it should instead
			//    behave like a trade payable.
			// 33 Stated Capital, 34 Retained Earnings

		$cash_nestcat_id = 22; // Cash & Cash Equivalents

		$in_placeholders = implode(',', array_fill(0, count($master_ids), '?'));

		// ══════════════════════════════════════════════════════════════════════
		// SECTION 1 — NET INCOME
		// INCOME      → idtbl_account_category = 4 (IN)  → CR normal
		// EXPENDITURE → idtbl_account_category = 2 (EX)  → DR normal
		// (Same query as the old operating_sql — the statement only needs the
		// totals now, not the itemised rows, but items are still returned in
		// case the view wants a drill-down/tooltip later.)
		// ══════════════════════════════════════════════════════════════════════
		$income_expense_sql = "
			SELECT
				tbl_account.idtbl_account,
				CONCAT(tbl_account.accountno, ' ', tbl_account.accountname) AS accname,
				tbl_account_category.idtbl_account_category,

				CASE
					WHEN tbl_account_category.idtbl_account_category = 4
					THEN IFNULL(drv_reg.cr_accamount, 0) - IFNULL(drv_reg.dr_accamount, 0)
					ELSE IFNULL(drv_reg.dr_accamount, 0) - IFNULL(drv_reg.cr_accamount, 0)
				END AS tra_balance

			FROM tbl_account
			INNER JOIN tbl_account_category
				ON tbl_account.tbl_account_category_idtbl_account_category
				= tbl_account_category.idtbl_account_category
			INNER JOIN tbl_account_allocation
				ON tbl_account_allocation.tbl_account_idtbl_account
				= tbl_account.idtbl_account

			LEFT JOIN (
				SELECT
					tbl_account_idtbl_account,
					SUM(accamount * (crdr = 'D')) AS dr_accamount,
					SUM(accamount * (crdr = 'C')) AS cr_accamount
				FROM tbl_account_transaction
				WHERE reversstatus             = 0
				AND   tbl_master_idtbl_master IN ($in_placeholders)
				GROUP BY tbl_account_idtbl_account
			) AS drv_reg
				ON drv_reg.tbl_account_idtbl_account = tbl_account.idtbl_account

			WHERE tbl_account.status = 1
			AND   tbl_account_category.idtbl_account_category IN (2, 4)
			AND   tbl_account_allocation.companybank          = ?
			AND   tbl_account_allocation.branchcompanybank    = ?

			ORDER BY tbl_account_category.idtbl_account_category DESC, tbl_account.idtbl_account
		";

		$income_expense_params = array_merge($master_ids, [$company_id, $branch_id]);
		$income_expense_result = $this->db->query($income_expense_sql, $income_expense_params)->result();

		$income_items  = [];
		$expense_items = [];
		$total_income  = 0;
		$total_expense = 0;

		foreach ($income_expense_result as $row) {
			if ($row->idtbl_account_category == 4) {
				$total_income  += $row->tra_balance;
				$income_items[] = $row;
			} else {
				$total_expense  += $row->tra_balance;
				$expense_items[] = $row;
			}
		}

		$net_income = $total_income - $total_expense;

		// ══════════════════════════════════════════════════════════════════════
		// SHARED HELPER QUERY — account movement (CR − DR) for a given
		// nestcategory ID list. Used for Adjustments / Investing / Financing.
		// ══════════════════════════════════════════════════════════════════════
		$movement_sql_template = "
			SELECT
				tbl_account.idtbl_account,
				CONCAT(tbl_account.accountno, ' ', tbl_account.accountname) AS accname,
				tbl_account_category.idtbl_account_category,
				tbl_account_nestcategory.nestcategory,

				IFNULL(drv_reg.cr_accamount, 0) - IFNULL(drv_reg.dr_accamount, 0)
				AS cash_flow_effect

			FROM tbl_account
			INNER JOIN tbl_account_category
				ON tbl_account.tbl_account_category_idtbl_account_category
				= tbl_account_category.idtbl_account_category
			INNER JOIN tbl_account_nestcategory
				ON tbl_account_nestcategory.idtbl_account_nestcategory
				= tbl_account.tbl_account_nestcategory_idtbl_account_nestcategory
			INNER JOIN tbl_account_allocation
				ON tbl_account_allocation.tbl_account_idtbl_account
				= tbl_account.idtbl_account

			LEFT JOIN (
				SELECT
					tbl_account_idtbl_account,
					SUM(totamount * (crdr = 'D')) AS dr_accamount,
					SUM(totamount * (crdr = 'C')) AS cr_accamount
				FROM tbl_account_transaction
				WHERE status                   = 1
				AND   tbl_master_idtbl_master IN ($in_placeholders)
				AND   tbl_company_idtbl_company = ?
				AND   tbl_company_branch_idtbl_company_branch = ?
				AND   tradate                 <= DATE(NOW())
				GROUP BY tbl_account_idtbl_account
			) AS drv_reg
				ON drv_reg.tbl_account_idtbl_account = tbl_account.idtbl_account

			WHERE tbl_account.status = 1
			AND   tbl_account.tbl_account_nestcategory_idtbl_account_nestcategory IN (%s)
			AND   tbl_account_allocation.companybank           = ?
			AND   tbl_account_allocation.branchcompanybank     = ?

			ORDER BY tbl_account.idtbl_account
		";

		// ── SECTION 2 — Operating Adjustments ──────────────────────────────────
		$adjustment_result = $this->_runMovementQuery(
			$movement_sql_template,
			$operating_adj_nestcat_ids,
			$master_ids,
			$company_id,
			$branch_id
		);

		$total_adjustments = 0;
		foreach ($adjustment_result as $row) {
			$total_adjustments += $row->cash_flow_effect;
		}

		// ── SECTION 3 — Investing Activities ───────────────────────────────────
		$investing_result = $this->_runMovementQuery(
			$movement_sql_template,
			$investing_nestcat_ids,
			$master_ids,
			$company_id,
			$branch_id
		);

		$total_investing = 0;
		foreach ($investing_result as $row) {
			$total_investing += $row->cash_flow_effect;
		}

		// ── SECTION 4 — Financing Activities ───────────────────────────────────
		$financing_result = $this->_runMovementQuery(
			$movement_sql_template,
			$financing_nestcat_ids,
			$master_ids,
			$company_id,
			$branch_id
		);

		$total_financing = 0;
		foreach ($financing_result as $row) {
			$total_financing += $row->cash_flow_effect;
		}

		// ══════════════════════════════════════════════════════════════════════
		// SECTION 5 — CASH & BANK OPENING / CLOSING BALANCE
		// nestcategory = 22 (Cash & Cash Equivalents)
		// ══════════════════════════════════════════════════════════════════════
		$cash_bank_sql = "
			SELECT
				tbl_account.idtbl_account,
				CONCAT(tbl_account.accountno, ' ', tbl_account.accountname) AS accname,

				CASE WHEN IFNULL(drv_open.creditdebit, 'D') = 'D'
					THEN  IFNULL(drv_open.openbal, 0)
					ELSE -IFNULL(drv_open.openbal, 0)
				END AS open_balance,

				IFNULL(drv_reg.dr_accamount, 0) - IFNULL(drv_reg.cr_accamount, 0)
				AS period_movement,

				(
					CASE WHEN IFNULL(drv_open.creditdebit, 'D') = 'D'
						THEN  IFNULL(drv_open.openbal, 0)
						ELSE -IFNULL(drv_open.openbal, 0)
					END
				)
				+ IFNULL(drv_reg.dr_accamount, 0)
				- IFNULL(drv_reg.cr_accamount, 0)
				AS closing_balance

			FROM tbl_account
			INNER JOIN tbl_account_allocation
				ON tbl_account_allocation.tbl_account_idtbl_account
				= tbl_account.idtbl_account

			LEFT JOIN (
				SELECT
					tbl_account_idtbl_account,
					IFNULL(SUM(openbal), 0) AS openbal,
					MAX(creditdebit)        AS creditdebit
				FROM tbl_account_open_bal
				WHERE status                              = 1
				AND   tbl_master_idtbl_master             = ?
				AND   tbl_company_idtbl_company           = ?
				AND   tbl_company_branch_idtbl_company_branch = ?
				GROUP BY tbl_account_idtbl_account
			) AS drv_open
				ON drv_open.tbl_account_idtbl_account = tbl_account.idtbl_account

			LEFT JOIN (
				SELECT
					tbl_account_idtbl_account,
					SUM(totamount * (crdr = 'D')) AS dr_accamount,
					SUM(totamount * (crdr = 'C')) AS cr_accamount
				FROM tbl_account_transaction
				WHERE status                   = 1
				AND   tbl_master_idtbl_master IN ($in_placeholders)
				AND   tbl_company_idtbl_company = ?
				AND   tbl_company_branch_idtbl_company_branch = ?
				AND   tradate                 <= DATE(NOW())
				GROUP BY tbl_account_idtbl_account
			) AS drv_reg
				ON drv_reg.tbl_account_idtbl_account = tbl_account.idtbl_account

			WHERE tbl_account.status = 1
			AND   tbl_account.tbl_account_nestcategory_idtbl_account_nestcategory = ?
			AND   tbl_account_allocation.companybank           = ?
			AND   tbl_account_allocation.branchcompanybank     = ?

			ORDER BY tbl_account.idtbl_account
		";

		$cash_bank_params = array_merge(
			[$open_bal_master, $company_id, $branch_id],   // drv_open
			$master_ids,                                    // drv_reg IN
			[$company_id, $branch_id],                      // drv_reg WHERE
			[$cash_nestcat_id],                             // nestcategory filter
			[$company_id, $branch_id]                       // WHERE allocation
		);

		$cash_bank_result = $this->db->query($cash_bank_sql, $cash_bank_params)->result();

		$opening_cash = 0;
		$closing_cash = 0;
		foreach ($cash_bank_result as $row) {
			$opening_cash += $row->open_balance;
			$closing_cash += $row->closing_balance;
		}

		// ── Net Cash Change & Verification ─────────────────────────────────────
		$net_operating   = $net_income + $total_adjustments;
		$net_cash_change = $net_operating + $total_investing + $total_financing;
		$verified        = abs(($opening_cash + $net_cash_change) - $closing_cash) < 0.01;

		// ══════════════════════════════════════════════════════════════════════
		// RETURN STRUCTURED RESULT
		// ══════════════════════════════════════════════════════════════════════
		return (object)[
			'operating' => (object)[
				'income_items'      => $income_items,
				'expense_items'     => $expense_items,
				'net_income'        => $net_income,
				'adjustment_items'  => $adjustment_result,
				'total_adjustments' => $total_adjustments,
				'net_operating'     => $net_operating,
			],
			'investing' => (object)[
				'items'         => $investing_result,
				'net_investing' => $total_investing,
			],
			'financing' => (object)[
				'items'         => $financing_result,
				'net_financing' => $total_financing,
			],
			'cash_bank' => (object)[
				'items'        => $cash_bank_result,
				'opening_cash' => $opening_cash,
				'closing_cash' => $closing_cash,
			],
			'summary' => (object)[
				'net_operating'   => $net_operating,
				'net_investing'   => $total_investing,
				'net_financing'   => $total_financing,
				'net_cash_change' => $net_cash_change,
				'opening_cash'    => $opening_cash,
				'closing_cash'    => $closing_cash,
				'verified'        => $verified,
			],
		];
	}

	/**
	 * Private helper — runs the shared movement query for a given
	 * nestcategory ID list. Add this method to ReportModuleinfo.php
	 * alongside getCashFlowReport().
	 */
	private function _runMovementQuery(
		$sql_template,
		array $nestcat_ids,
		array $master_ids,
		$company_id,
		$branch_id
	) {
		$nestcat_placeholders = implode(',', array_fill(0, count($nestcat_ids), '?'));
		$sql = sprintf($sql_template, $nestcat_placeholders);

		// Placeholder order must match the SQL text order exactly:
		// 1) drv_reg master_ids IN (...)
		// 2) drv_reg company_id, branch_id
		// 3) WHERE nestcategory IN (...)
		// 4) WHERE allocation company_id, branch_id
		$params = array_merge(
			$master_ids,
			[$company_id, $branch_id],
			$nestcat_ids,
			[$company_id, $branch_id]
		);

		return $this->db->query($sql, $params)->result();
	}

	// public function getPurchaseAuditReport(
	// 	$company_id,
	// 	$branch_id,
	// 	$report_period_id,
	// 	$open_bal_period_id = null
	// ){
	// 	// ── Period handling (single / multi) ───────────────────────────────
	// 	if (is_array($report_period_id)) {
	// 		$master_ids      = $report_period_id;
	// 		$open_bal_master = !empty($open_bal_period_id)
	// 						? $open_bal_period_id
	// 						: $master_ids[0];
	// 	} else {
	// 		$master_ids      = [$report_period_id];
	// 		$open_bal_master = $open_bal_period_id ?? $report_period_id;
	// 	}

	// 	$in_placeholders = implode(',', array_fill(0, count($master_ids), '?'));

	// 	// ════════════════════════════════════════════════════════════════════
	// 	// Purchase Transactions (EX category only)
	// 	// ════════════════════════════════════════════════════════════════════
	// 	$sql = "
	// 		SELECT
	// 			t.idtbl_account_transaction,
	// 			t.tradate,
	// 			t.narration,
	// 			t.accamount,
	// 			t.crdr,
	// 			'' as documentno,
	// 			a.accountno,
	// 			a.accountname,
	// 			cat.category,
	// 			cat.code
	// 		FROM tbl_account_transaction t
	// 		INNER JOIN tbl_master m
	// 			ON m.idtbl_master = t.tbl_master_idtbl_master
	// 		INNER JOIN tbl_account a
	// 			ON a.idtbl_account = t.tbl_account_idtbl_account
	// 		INNER JOIN tbl_account_category cat
	// 			ON cat.idtbl_account_category =
	// 			a.tbl_account_category_idtbl_account_category
	// 		INNER JOIN tbl_account_allocation al
	// 			ON al.tbl_account_idtbl_account = a.idtbl_account
	// 		WHERE t.status = 1
	// 		AND t.tbl_company_idtbl_company = ?
	// 		AND t.tbl_company_branch_idtbl_company_branch = ?
	// 		AND t.tbl_master_idtbl_master IN ($in_placeholders)
	// 		AND cat.idtbl_account_category = 2     -- EX (Purchases)
	// 		AND t.crdr = 'D'                      -- Purchases normally Debit
	// 		AND al.companybank = ?
	// 		AND al.branchcompanybank = ?
	// 		ORDER BY t.tradate ASC
	// 	";

	// 	$params = array_merge(
	// 		[$company_id, $branch_id],
	// 		$master_ids,
	// 		[$company_id, $branch_id]
	// 	);

	// 	$result = $this->db->query($sql, $params)->result();

	// 	// ── Calculate Summary ───────────────────────────────────────────────
	// 	$total_purchase = 0;
	// 	$transaction_count = 0;

	// 	foreach ($result as $row) {
	// 		$total_purchase += (float)$row->accamount;
	// 		$transaction_count++;
	// 	}

	// 	return (object)[
	// 		'items' => $result,
	// 		'summary' => (object)[
	// 			'total_purchase'    => $total_purchase,
	// 			'transaction_count' => $transaction_count
	// 		]
	// 	];
	// }

	public function getPurchaseAuditReport(
		$company_id,
		$branch_id,
		$report_period_id,
		$open_bal_period_id = null
	){
		if (is_array($report_period_id)) {
			$master_ids      = $report_period_id;
			$open_bal_master = !empty($open_bal_period_id)
							? $open_bal_period_id
							: $master_ids[0];
		} else {
			$master_ids      = [$report_period_id];
			$open_bal_master = $open_bal_period_id ?? $report_period_id;
		}

		$in_placeholders = implode(',', array_fill(0, count($master_ids), '?'));

		// ════════════════════════════════════════════════════════════════════
		// Purchase Transactions (EX category only)
		// ════════════════════════════════════════════════════════════════════
		$sql = "
			SELECT
				t.idtbl_account_transaction,
				t.tradate,
				t.narration,
				t.accamount,
				t.crdr,
				'' as documentno,
				a.accountno,
				a.accountname,
				cat.category,
				cat.code
			FROM tbl_account_transaction t
			INNER JOIN tbl_master m
				ON m.idtbl_master = t.tbl_master_idtbl_master
			INNER JOIN tbl_account a
				ON a.idtbl_account = t.tbl_account_idtbl_account
			INNER JOIN tbl_account_category cat
				ON cat.idtbl_account_category =
				a.tbl_account_category_idtbl_account_category
			INNER JOIN tbl_account_allocation al
				ON al.tbl_account_idtbl_account = a.idtbl_account
			WHERE t.status = 1
			AND t.reversstatus = 0                -- ★ FIX: reversed entries excluded
			AND t.tbl_company_idtbl_company = ?
			AND t.tbl_company_branch_idtbl_company_branch = ?
			AND t.tbl_master_idtbl_master IN ($in_placeholders)
			AND cat.idtbl_account_category = 2     -- EX (Purchases)
			AND t.crdr = 'D'                      -- Purchases normally Debit
			AND al.companybank = ?
			AND al.branchcompanybank = ?
			ORDER BY t.tradate ASC
		";

		$params = array_merge(
			[$company_id, $branch_id],
			$master_ids,
			[$company_id, $branch_id]
		);

		$result = $this->db->query($sql, $params)->result();

		$total_purchase = 0;
		$transaction_count = 0;

		foreach ($result as $row) {
			$total_purchase += (float)$row->accamount;
			$transaction_count++;
		}

		return (object)[
			'items' => $result,
			'summary' => (object)[
				'total_purchase'    => $total_purchase,
				'transaction_count' => $transaction_count
			]
		];
	}

	// public function getSalesAuditReport(
	// 	$company_id,
	// 	$branch_id,
	// 	$report_period_id,
	// 	$open_bal_period_id = null
	// ){
	// 	// ── Period Handling (Single / Multi) ──────────────────────────────
	// 	if (is_array($report_period_id)) {
	// 		$master_ids      = $report_period_id;
	// 		$open_bal_master = !empty($open_bal_period_id)
	// 						? $open_bal_period_id
	// 						: $master_ids[0];
	// 	} else {
	// 		$master_ids      = [$report_period_id];
	// 		$open_bal_master = $open_bal_period_id ?? $report_period_id;
	// 	}

	// 	$in_placeholders = implode(',', array_fill(0, count($master_ids), '?'));

	// 	// ════════════════════════════════════════════════════════════════════
	// 	// Sales Transactions (IN category, Credit side)
	// 	// ════════════════════════════════════════════════════════════════════
	// 	$sql = "
	// 		SELECT
	// 			t.idtbl_account_transaction,
	// 			t.tradate,
	// 			t.narration,
	// 			t.accamount,
	// 			t.crdr,
	// 			'' as documentno,
	// 			a.accountno,
	// 			a.accountname,
	// 			cat.category,
	// 			cat.code
	// 		FROM tbl_account_transaction t
	// 		INNER JOIN tbl_master m
	// 			ON m.idtbl_master = t.tbl_master_idtbl_master
	// 		INNER JOIN tbl_account a
	// 			ON a.idtbl_account = t.tbl_account_idtbl_account
	// 		INNER JOIN tbl_account_category cat
	// 			ON cat.idtbl_account_category =
	// 			a.tbl_account_category_idtbl_account_category
	// 		INNER JOIN tbl_account_allocation al
	// 			ON al.tbl_account_idtbl_account = a.idtbl_account
	// 		WHERE t.status = 1
	// 		AND t.tbl_company_idtbl_company = ?
	// 		AND t.tbl_company_branch_idtbl_company_branch = ?
	// 		AND t.tbl_master_idtbl_master IN ($in_placeholders)
	// 		AND cat.idtbl_account_category = 4   -- INCOME (Sales)
	// 		AND t.crdr = 'C'                    -- Sales normally Credit
	// 		AND al.companybank = ?
	// 		AND al.branchcompanybank = ?
	// 		ORDER BY t.tradate ASC
	// 	";

	// 	$params = array_merge(
	// 		[$company_id, $branch_id],
	// 		$master_ids,
	// 		[$company_id, $branch_id]
	// 	);

	// 	$result = $this->db->query($sql, $params)->result();

	// 	// ── Summary Calculation ────────────────────────────────────────────
	// 	$total_sales = 0;
	// 	$transaction_count = 0;

	// 	foreach ($result as $row) {
	// 		$total_sales += (float)$row->accamount;
	// 		$transaction_count++;
	// 	}

	// 	return (object)[
	// 		'items' => $result,
	// 		'summary' => (object)[
	// 			'total_sales'       => $total_sales,
	// 			'transaction_count' => $transaction_count
	// 		]
	// 	];
	// }

	public function getSalesAuditReport(
		$company_id,
		$branch_id,
		$report_period_id,
		$open_bal_period_id = null
	){
		if (is_array($report_period_id)) {
			$master_ids      = $report_period_id;
			$open_bal_master = !empty($open_bal_period_id)
							? $open_bal_period_id
							: $master_ids[0];
		} else {
			$master_ids      = [$report_period_id];
			$open_bal_master = $open_bal_period_id ?? $report_period_id;
		}

		$in_placeholders = implode(',', array_fill(0, count($master_ids), '?'));

		// ════════════════════════════════════════════════════════════════════
		// Sales Transactions (IN category, Credit side)
		// ════════════════════════════════════════════════════════════════════
		$sql = "
			SELECT
				t.idtbl_account_transaction,
				t.tradate,
				t.narration,
				t.accamount,
				t.crdr,
				'' as documentno,
				a.accountno,
				a.accountname,
				cat.category,
				cat.code
			FROM tbl_account_transaction t
			INNER JOIN tbl_master m
				ON m.idtbl_master = t.tbl_master_idtbl_master
			INNER JOIN tbl_account a
				ON a.idtbl_account = t.tbl_account_idtbl_account
			INNER JOIN tbl_account_category cat
				ON cat.idtbl_account_category =
				a.tbl_account_category_idtbl_account_category
			INNER JOIN tbl_account_allocation al
				ON al.tbl_account_idtbl_account = a.idtbl_account
			WHERE t.status = 1
			AND t.reversstatus = 0                -- ★ FIX: reversed entries excluded
			AND t.tbl_company_idtbl_company = ?
			AND t.tbl_company_branch_idtbl_company_branch = ?
			AND t.tbl_master_idtbl_master IN ($in_placeholders)
			AND cat.idtbl_account_category = 4   -- INCOME (Sales)
			AND t.crdr = 'C'                    -- Sales normally Credit
			AND al.companybank = ?
			AND al.branchcompanybank = ?
			ORDER BY t.tradate ASC
		";

		$params = array_merge(
			[$company_id, $branch_id],
			$master_ids,
			[$company_id, $branch_id]
		);

		$result = $this->db->query($sql, $params)->result();

		$total_sales = 0;
		$transaction_count = 0;

		foreach ($result as $row) {
			$total_sales += (float)$row->accamount;
			$transaction_count++;
		}

		return (object)[
			'items' => $result,
			'summary' => (object)[
				'total_sales'       => $total_sales,
				'transaction_count' => $transaction_count
			]
		];
	}

	public function getInternalControlAuditReport(
		$company_id,
		$branch_id,
		$report_period_id,
		$open_bal_period_id = null
	){
		// ── Period Handling (Single / Multi) ──────────────────────────────
		if (is_array($report_period_id)) {
			$master_ids      = $report_period_id;
			$open_bal_master = !empty($open_bal_period_id)
							? $open_bal_period_id
							: $master_ids[0];
		} else {
			$master_ids      = [$report_period_id];
			$open_bal_master = $open_bal_period_id ?? $report_period_id;
		}

		$in_placeholders = implode(',', array_fill(0, count($master_ids), '?'));

		// ===============================================================
		// 1️⃣ Zero Amount Transactions
		// ===============================================================
		$zero_sql = "
			SELECT idtbl_account_transaction, tradate
			FROM tbl_account_transaction
			WHERE status = 1
			AND accamount = 0
			AND tbl_company_idtbl_company = ?
			AND tbl_company_branch_idtbl_company_branch = ?
			AND tbl_master_idtbl_master IN ($in_placeholders)
		";

		$zero_transactions = $this->db->query(
			$zero_sql,
			array_merge([$company_id, $branch_id], $master_ids)
		)->result();


		// ===============================================================
		// 2️⃣ Negative Amount Transactions (if not allowed)
		// ===============================================================
		$negative_sql = "
			SELECT idtbl_account_transaction, tradate, accamount
			FROM tbl_account_transaction
			WHERE status = 1
			AND accamount < 0
			AND tbl_company_idtbl_company = ?
			AND tbl_company_branch_idtbl_company_branch = ?
			AND tbl_master_idtbl_master IN ($in_placeholders)
		";

		$negative_transactions = $this->db->query(
			$negative_sql,
			array_merge([$company_id, $branch_id], $master_ids)
		)->result();


		// ===============================================================
		// 3️⃣ Invalid DR/CR values
		// ===============================================================
		$invalid_crdr_sql = "
			SELECT idtbl_account_transaction, crdr
			FROM tbl_account_transaction
			WHERE status = 1
			AND crdr NOT IN ('D','C')
			AND tbl_company_idtbl_company = ?
			AND tbl_company_branch_idtbl_company_branch = ?
			AND tbl_master_idtbl_master IN ($in_placeholders)
		";

		$invalid_crdr = $this->db->query(
			$invalid_crdr_sql,
			array_merge([$company_id, $branch_id], $master_ids)
		)->result();


		// ===============================================================
		// 4️⃣ Future Date Transactions
		// ===============================================================
		$future_sql = "
			SELECT idtbl_account_transaction, tradate
			FROM tbl_account_transaction
			WHERE status = 1
			AND tradate > CURDATE()
			AND tbl_company_idtbl_company = ?
			AND tbl_company_branch_idtbl_company_branch = ?
			AND tbl_master_idtbl_master IN ($in_placeholders)
		";

		$future_transactions = $this->db->query(
			$future_sql,
			array_merge([$company_id, $branch_id], $master_ids)
		)->result();


		// ===============================================================
		// 5️⃣ Duplicate Suspicious Entries
		// Same Date + Same Account + Same Amount
		// ===============================================================
		$duplicate_sql = "
			SELECT 
				tradate,
				tbl_account_idtbl_account,
				accamount,
				COUNT(*) as duplicate_count
			FROM tbl_account_transaction
			WHERE status = 1
			AND tbl_company_idtbl_company = ?
			AND tbl_company_branch_idtbl_company_branch = ?
			AND tbl_master_idtbl_master IN ($in_placeholders)
			GROUP BY tradate, tbl_account_idtbl_account, accamount
			HAVING COUNT(*) > 1
		";

		$duplicate_entries = $this->db->query(
			$duplicate_sql,
			array_merge([$company_id, $branch_id], $master_ids)
		)->result();


		// ===============================================================
		// 6️⃣ Transactions Without Valid Account
		// ===============================================================
		$invalid_account_sql = "
			SELECT t.idtbl_account_transaction
			FROM tbl_account_transaction t
			LEFT JOIN tbl_account a
				ON a.idtbl_account = t.tbl_account_idtbl_account
			WHERE t.status = 1
			AND a.idtbl_account IS NULL
			AND t.tbl_company_idtbl_company = ?
			AND t.tbl_company_branch_idtbl_company_branch = ?
			AND t.tbl_master_idtbl_master IN ($in_placeholders)
		";

		$invalid_account = $this->db->query(
			$invalid_account_sql,
			array_merge([$company_id, $branch_id], $master_ids)
		)->result();


		return (object)[
			'zero_transactions'     => $zero_transactions,
			'negative_transactions' => $negative_transactions,
			'invalid_crdr'          => $invalid_crdr,
			'future_transactions'   => $future_transactions,
			'duplicate_entries'     => $duplicate_entries,
			'invalid_account'       => $invalid_account
		];
	}

	public function getCompleteAuditSummaryReport(
		$company_id,
		$branch_id,
		$master_ids
	){

		$in_placeholders = implode(',', array_fill(0, count($master_ids), '?'));

		// ===============================================================
		// TOTAL TRANSACTIONS
		// ===============================================================
		$total_sql = "
			SELECT COUNT(*) as total_transactions
			FROM tbl_account_transaction
			WHERE status = 1
			AND tbl_company_idtbl_company = ?
			AND tbl_company_branch_idtbl_company_branch = ?
			AND tbl_master_idtbl_master IN ($in_placeholders)
		";

		$total_row = $this->db->query(
			$total_sql,
			array_merge([$company_id, $branch_id], $master_ids)
		)->row();

		// ===============================================================
		// TOTAL DEBIT & CREDIT
		// ===============================================================
		$balance_sql = "
			SELECT
				SUM(accamount * (crdr = 'D')) as total_debit,
				SUM(accamount * (crdr = 'C')) as total_credit
			FROM tbl_account_transaction
			WHERE status = 1
			AND tbl_company_idtbl_company = ?
			AND tbl_company_branch_idtbl_company_branch = ?
			AND tbl_master_idtbl_master IN ($in_placeholders)
		";

		$balance_row = $this->db->query(
			$balance_sql,
			array_merge([$company_id, $branch_id], $master_ids)
		)->row();

		// ===============================================================
		// Get Internal Control Details
		// ===============================================================
		$internal = $this->getInternalControlAuditReport(
			$company_id,
			$branch_id,
			$master_ids
		);

		$zero_count      = count($internal->zero_transactions);
		$negative_count  = count($internal->negative_transactions);
		$invalid_crdr    = count($internal->invalid_crdr);
		$future_count    = count($internal->future_transactions);
		$duplicate_count = count($internal->duplicate_entries);
		$invalid_account = count($internal->invalid_account);

		// ===============================================================
		// BALANCE CHECK
		// ===============================================================
		$total_debit  = (float)($balance_row->total_debit ?? 0);
		$total_credit = (float)($balance_row->total_credit ?? 0);

		$is_balanced = abs($total_debit - $total_credit) < 0.01;

		// ===============================================================
		// AUDIT SCORE CALCULATION
		// ===============================================================
		$issues = $zero_count
				+ $negative_count
				+ $invalid_crdr
				+ $future_count
				+ $duplicate_count
				+ $invalid_account;

		$total_transactions = (int)($total_row->total_transactions ?? 0);

		if($total_transactions > 0){
			$score = 100 - (($issues / $total_transactions) * 100);
			$score = max(0, round($score,2));
		} else {
			$score = 100;
		}

		return (object)[
			'total_transactions' => $total_transactions,
			'total_debit'        => $total_debit,
			'total_credit'       => $total_credit,
			'is_balanced'        => $is_balanced,

			'zero_count'         => $zero_count,
			'negative_count'     => $negative_count,
			'invalid_crdr'       => $invalid_crdr,
			'future_count'       => $future_count,
			'duplicate_count'    => $duplicate_count,
			'invalid_account'    => $invalid_account,

			'audit_score'        => $score
		];
	}

	// public function getBankReconciliationReport(
	// 	$company_id,
	// 	$branch_id,
	// 	$account_id,
	// 	$master_ids,
	// 	$open_bal_master
	// ){
	// 	$in_placeholders = implode(',', array_fill(0, count($master_ids), '?'));

	// 	// ===============================================================
	// 	// 1️⃣ Bank Statement Details (from tbl_bank_rec_list)
	// 	// ===============================================================
	// 	$statement_sql = "
	// 		SELECT
	// 			brl.idtbl_bank_rec_list,
	// 			brl.bank_rec_date,
	// 			brl.acc_rec_batchno,
	// 			brl.statement_open_bal,
	// 			brl.statement_tot_cr,
	// 			brl.statement_tot_dr,
	// 			brl.statement_closed_bal,
	// 			brl.rec_approved,
	// 			a.accountno,
	// 			a.accountname,
	// 			fy.year as financialyear,
	// 			fm.monthname as financialmonth
	// 		FROM tbl_bank_rec_list brl
	// 		INNER JOIN tbl_account a
	// 			ON a.idtbl_account = brl.tbl_account_idtbl_account
	// 		LEFT JOIN tbl_finacial_year fy
	// 			ON fy.idtbl_finacial_year = brl.tbl_finacial_year_idtbl_finacial_year
	// 		LEFT JOIN tbl_finacial_month fm
	// 			ON fm.idtbl_finacial_month = brl.tbl_finacial_month_idtbl_finacial_month
	// 		WHERE brl.status = 1
	// 		AND brl.tbl_account_idtbl_account = ?
	// 		AND brl.tbl_finacial_year_idtbl_finacial_year IN (
	// 			SELECT DISTINCT tbl_finacial_year_idtbl_finacial_year
	// 			FROM tbl_master
	// 			WHERE idtbl_master IN ($in_placeholders)
	// 		)
	// 		AND brl.tbl_finacial_month_idtbl_finacial_month IN (
	// 			SELECT DISTINCT tbl_finacial_month_idtbl_finacial_month
	// 			FROM tbl_master
	// 			WHERE idtbl_master IN ($in_placeholders)
	// 		)
	// 		ORDER BY brl.bank_rec_date DESC
	// 		LIMIT 1
	// 	";

	// 	$statement_params = array_merge(
	// 		[$account_id],
	// 		$master_ids,
	// 		$master_ids
	// 	);

	// 	$statement = $this->db->query($statement_sql, $statement_params)->row();

	// 	// If no reconciliation found return empty
	// 	if(empty($statement)){
	// 		return (object)[
	// 			'statement'               => null,
	// 			'reconciled_items'        => [],
	// 			'unreconciled_items'      => [],
	// 			'bank_adjustments'        => [],
	// 			'book_balance'            => 0,
	// 			'adjusted_bank_balance'   => 0,
	// 			'difference'              => 0,
	// 			'summary'                 => (object)[
	// 				'statement_balance'       => 0,
	// 				'total_reconciled'        => 0,
	// 				'total_unreconciled'      => 0,
	// 				'total_adjustments'       => 0,
	// 				'book_balance'            => 0,
	// 				'adjusted_bank_balance'   => 0,
	// 				'difference'              => 0,
	// 				'is_reconciled'           => false
	// 			]
	// 		];
	// 	}

	// 	$rec_list_id = $statement->idtbl_bank_rec_list;

	// 	// ===============================================================
	// 	// 2️⃣ Reconciled Transactions (matched in tbl_bank_rec_info)
	// 	// ===============================================================
	// 	$reconciled_sql = "
	// 		SELECT
	// 			bri.idtbl_bank_rec_info,
	// 			bri.rec_info_origin_name,
	// 			t.tradate,
	// 			t.narration,
	// 			t.accamount,
	// 			t.crdr
	// 		FROM tbl_bank_rec_info bri
	// 		INNER JOIN tbl_account_transaction t
	// 			ON t.idtbl_account_transaction
	// 			= bri.tbl_account_transaction_idtbl_account_transaction
	// 		WHERE bri.status = 1
	// 		AND bri.tbl_bank_rec_list_idtbl_bank_rec_list = ?
	// 		ORDER BY t.tradate ASC
	// 	";

	// 	$reconciled_items = $this->db->query(
	// 		$reconciled_sql,
	// 		[$rec_list_id]
	// 	)->result();

	// 	// ===============================================================
	// 	// 3️⃣ Unreconciled Transactions (in book but NOT in rec_info)
	// 	// ===============================================================
	// 	$unreconciled_sql = "
	// 		SELECT
	// 			t.idtbl_account_transaction,
	// 			t.tradate,
	// 			t.narration,
	// 			t.accamount,
	// 			t.crdr
	// 		FROM tbl_account_transaction t
	// 		WHERE t.status = 1
	// 		AND t.tbl_account_idtbl_account = ?
	// 		AND t.tbl_company_idtbl_company = ?
	// 		AND t.tbl_company_branch_idtbl_company_branch = ?
	// 		AND t.tbl_master_idtbl_master IN ($in_placeholders)
	// 		AND t.idtbl_account_transaction NOT IN (
	// 			SELECT tbl_account_transaction_idtbl_account_transaction
	// 			FROM tbl_bank_rec_info
	// 			WHERE status = 1
	// 			AND tbl_bank_rec_list_idtbl_bank_rec_list = ?
	// 		)
	// 		ORDER BY t.tradate ASC
	// 	";

	// 	$unreconciled_params = array_merge(
	// 		[$account_id, $company_id, $branch_id],
	// 		$master_ids,
	// 		[$rec_list_id]
	// 	);

	// 	$unreconciled_items = $this->db->query(
	// 		$unreconciled_sql,
	// 		$unreconciled_params
	// 	)->result();

	// 	// ===============================================================
	// 	// 4️⃣ Bank Adjustments (from tbl_bank_rec_revision)
	// 	// ===============================================================
	// 	$adjustments_sql = "
	// 		SELECT
	// 			brr.idtbl_bank_rec_revision,
	// 			brr.bank_narration,
	// 			brr.bank_amount,
	// 			brr.tbl_account_idtbl_account_cr,
	// 			brr.tbl_account_idtbl_account_dr,
	// 			a_cr.accountname as cr_account,
	// 			a_dr.accountname as dr_account
	// 		FROM tbl_bank_rec_revision brr
	// 		LEFT JOIN tbl_account a_cr
	// 			ON a_cr.idtbl_account = brr.tbl_account_idtbl_account_cr
	// 		LEFT JOIN tbl_account a_dr
	// 			ON a_dr.idtbl_account = brr.tbl_account_idtbl_account_dr
	// 		WHERE brr.status = 1
	// 		AND brr.tbl_bank_rec_list_idtbl_bank_rec_list = ?
	// 		ORDER BY brr.idtbl_bank_rec_revision ASC
	// 	";

	// 	$bank_adjustments = $this->db->query(
	// 		$adjustments_sql,
	// 		[$rec_list_id]
	// 	)->result();

	// 	// ===============================================================
	// 	// 5️⃣ Book Balance (Opening + Transactions)
	// 	// ===============================================================
	// 	$book_sql = "
	// 		SELECT
	// 			CASE WHEN IFNULL(drv_open.creditdebit, 'D') = 'D'
	// 				THEN  IFNULL(drv_open.openbal, 0)
	// 				ELSE -IFNULL(drv_open.openbal, 0)
	// 			END
	// 			+ IFNULL(drv_reg.dr_amount, 0)
	// 			- IFNULL(drv_reg.cr_amount, 0)
	// 			AS book_balance

	// 		FROM (SELECT 1) AS dummy

	// 		LEFT JOIN (
	// 			SELECT
	// 				IFNULL(SUM(openbal), 0) AS openbal,
	// 				MAX(creditdebit) AS creditdebit
	// 			FROM tbl_account_open_bal
	// 			WHERE status = 1
	// 			AND tbl_account_idtbl_account = ?
	// 			AND tbl_master_idtbl_master = ?
	// 			AND tbl_company_idtbl_company = ?
	// 			AND tbl_company_branch_idtbl_company_branch = ?
	// 		) AS drv_open ON 1=1

	// 		LEFT JOIN (
	// 			SELECT
	// 				SUM(accamount * (crdr = 'D')) AS dr_amount,
	// 				SUM(accamount * (crdr = 'C')) AS cr_amount
	// 			FROM tbl_account_transaction
	// 			WHERE status = 1
	// 			AND tbl_account_idtbl_account = ?
	// 			AND tbl_company_idtbl_company = ?
	// 			AND tbl_company_branch_idtbl_company_branch = ?
	// 			AND tbl_master_idtbl_master IN ($in_placeholders)
	// 		) AS drv_reg ON 1=1
	// 	";

	// 	$book_params = array_merge(
	// 		[$account_id, $open_bal_master, $company_id, $branch_id],
	// 		[$account_id, $company_id, $branch_id],
	// 		$master_ids
	// 	);

	// 	$book_row = $this->db->query($book_sql, $book_params)->row();
	// 	$book_balance = (float)($book_row->book_balance ?? 0);

	// 	// ===============================================================
	// 	// 6️⃣ Calculate Totals
	// 	// ===============================================================

	// 	// Reconciled totals
	// 	$total_reconciled_dr = 0;
	// 	$total_reconciled_cr = 0;

	// 	foreach($reconciled_items as $row){
	// 		if($row->crdr == 'D'){
	// 			$total_reconciled_dr += (float)$row->accamount;
	// 		} else {
	// 			$total_reconciled_cr += (float)$row->accamount;
	// 		}
	// 	}

	// 	// Unreconciled totals
	// 	$total_unreconciled_dr = 0;
	// 	$total_unreconciled_cr = 0;

	// 	foreach($unreconciled_items as $row){
	// 		if($row->crdr == 'D'){
	// 			$total_unreconciled_dr += (float)$row->accamount;
	// 		} else {
	// 			$total_unreconciled_cr += (float)$row->accamount;
	// 		}
	// 	}

	// 	// Adjustment total
	// 	$total_adjustments = 0;

	// 	foreach($bank_adjustments as $row){
	// 		$total_adjustments += (float)$row->bank_amount;
	// 	}

	// 	// Statement balance
	// 	$statement_balance = (float)($statement->statement_closed_bal ?? 0);

	// 	// Adjusted bank balance
	// 	// Bank Statement + Unreconciled DR - Unreconciled CR + Adjustments
	// 	$adjusted_bank_balance = $statement_balance
	// 						+ $total_unreconciled_dr
	// 						- $total_unreconciled_cr
	// 						+ $total_adjustments;

	// 	// Difference
	// 	$difference = abs($book_balance - $adjusted_bank_balance);
	// 	$is_reconciled = $difference < 0.01;

	// 	// ===============================================================
	// 	// RETURN
	// 	// ===============================================================
	// 	return (object)[

	// 		'statement'          => $statement,
	// 		'reconciled_items'   => $reconciled_items,
	// 		'unreconciled_items' => $unreconciled_items,
	// 		'bank_adjustments'   => $bank_adjustments,
	// 		'book_balance'       => $book_balance,

	// 		'summary' => (object)[

	// 			'statement_open_bal'  => (float)($statement->statement_open_bal ?? 0),
	// 			'statement_tot_dr'   => (float)($statement->statement_tot_dr ?? 0),
	// 			'statement_tot_cr'   => (float)($statement->statement_tot_cr ?? 0),
	// 			'statement_balance'  => $statement_balance,

	// 			'reconciled_dr'      => $total_reconciled_dr,
	// 			'reconciled_cr'      => $total_reconciled_cr,
	// 			'reconciled_count'   => count($reconciled_items),

	// 			'unreconciled_dr'    => $total_unreconciled_dr,
	// 			'unreconciled_cr'    => $total_unreconciled_cr,
	// 			'unreconciled_count' => count($unreconciled_items),

	// 			'total_adjustments'       => $total_adjustments,
	// 			'adjustment_count'        => count($bank_adjustments),

	// 			'book_balance'            => $book_balance,
	// 			'adjusted_bank_balance'   => $adjusted_bank_balance,
	// 			'difference'              => $difference,
	// 			'is_reconciled'           => $is_reconciled
	// 		]
	// 	];
	// }

	// public function getBankReconciliationReport(
	// 	$company_id,
	// 	$branch_id,
	// 	$account_id,
	// 	$master_ids,
	// 	$open_bal_master
	// ){
	// 	$in_placeholders = implode(',', array_fill(0, count($master_ids), '?'));

	// 	// ===============================================================
	// 	// 1️⃣ Bank Statement Details (from tbl_bank_rec_list)
	// 	// ===============================================================
	// 	$statement_sql = "
	// 		SELECT
	// 			brl.idtbl_bank_rec_list,
	// 			brl.bank_rec_date,
	// 			brl.acc_rec_batchno,
	// 			brl.statement_open_bal,
	// 			brl.statement_tot_cr,
	// 			brl.statement_tot_dr,
	// 			brl.statement_closed_bal,
	// 			brl.rec_approved,
	// 			a.accountno,
	// 			a.accountname,
	// 			fy.year as financialyear,
	// 			fm.monthname as financialmonth
	// 		FROM tbl_bank_rec_list brl
	// 		INNER JOIN tbl_account a
	// 			ON a.idtbl_account = brl.tbl_account_idtbl_account
	// 		LEFT JOIN tbl_finacial_year fy
	// 			ON fy.idtbl_finacial_year = brl.tbl_finacial_year_idtbl_finacial_year
	// 		LEFT JOIN tbl_finacial_month fm
	// 			ON fm.idtbl_finacial_month = brl.tbl_finacial_month_idtbl_finacial_month
	// 		WHERE brl.status = 1
	// 		AND brl.tbl_account_idtbl_account = ?
	// 		AND brl.tbl_finacial_year_idtbl_finacial_year IN (
	// 			SELECT DISTINCT tbl_finacial_year_idtbl_finacial_year
	// 			FROM tbl_master
	// 			WHERE idtbl_master IN ($in_placeholders)
	// 		)
	// 		AND brl.tbl_finacial_month_idtbl_finacial_month IN (
	// 			SELECT DISTINCT tbl_finacial_month_idtbl_finacial_month
	// 			FROM tbl_master
	// 			WHERE idtbl_master IN ($in_placeholders)
	// 		)
	// 		ORDER BY brl.bank_rec_date DESC
	// 		LIMIT 1
	// 	";

	// 	$statement_params = array_merge(
	// 		[$account_id],
	// 		$master_ids,
	// 		$master_ids
	// 	);

	// 	$statement = $this->db->query($statement_sql, $statement_params)->row();

	// 	// If no reconciliation found return empty
	// 	if(empty($statement)){
	// 		return (object)[
	// 			'statement'               => null,
	// 			'reconciled_items'        => [],
	// 			'unreconciled_items'      => [],
	// 			'bank_adjustments'        => [],
	// 			'book_balance'            => 0,
	// 			'adjusted_bank_balance'   => 0,
	// 			'difference'              => 0,
	// 			'summary'                 => (object)[
	// 				'statement_balance'       => 0,
	// 				'total_reconciled'        => 0,
	// 				'total_unreconciled'      => 0,
	// 				'total_adjustments'       => 0,
	// 				'book_balance'            => 0,
	// 				'adjusted_bank_balance'   => 0,
	// 				'difference'              => 0,
	// 				'is_reconciled'           => false
	// 			]
	// 		];
	// 	}

	// 	$rec_list_id = $statement->idtbl_bank_rec_list;

	// 	// ===============================================================
	// 	// 2️⃣ Reconciled Transactions (matched in tbl_bank_rec_info)
	// 	//    — UNION across both possible sources
	// 	// ===============================================================
	// 	$reconciled_sql = "
	// 		SELECT
	// 			bri.idtbl_bank_rec_info,
	// 			bri.rec_info_origin_name,
	// 			t.tradate,
	// 			t.narration,
	// 			t.accamount,
	// 			t.crdr
	// 		FROM tbl_bank_rec_info bri
	// 		INNER JOIN tbl_account_transaction_full t
	// 			ON t.idtbl_account_transaction_full
	// 			= bri.tbl_account_transaction_idtbl_account_transaction
	// 		WHERE bri.status = 1
	// 		AND bri.rec_info_origin_name = 'transaction_full'
	// 		AND bri.tbl_bank_rec_list_idtbl_bank_rec_list = ?

	// 		UNION ALL

	// 		SELECT
	// 			bri.idtbl_bank_rec_info,
	// 			bri.rec_info_origin_name,
	// 			r.recdate AS tradate,
	// 			r.narration,
	// 			r.amount AS accamount,
	// 			'C' AS crdr
	// 		FROM tbl_bank_rec_info bri
	// 		INNER JOIN tbl_receivable r
	// 			ON r.idtbl_receivable
	// 			= bri.tbl_account_transaction_idtbl_account_transaction
	// 		WHERE bri.status = 1
	// 		AND bri.rec_info_origin_name = 'receivable_deposit'
	// 		AND bri.tbl_bank_rec_list_idtbl_bank_rec_list = ?

	// 		ORDER BY tradate ASC
	// 	";

	// 	$reconciled_items = $this->db->query(
	// 		$reconciled_sql,
	// 		[$rec_list_id, $rec_list_id]
	// 	)->result();

	// 	// ===============================================================
	// 	// 3️⃣ Unreconciled Transactions (in book but NOT in rec_info)
	// 	//    — UNION across both possible sources, restricted to the
	// 	//    selected period range
	// 	// ===============================================================
	// 	$unreconciled_sql = "
	// 		SELECT
	// 			t.idtbl_account_transaction_full AS idtbl_account_transaction,
	// 			t.tradate,
	// 			t.narration,
	// 			t.accamount,
	// 			t.crdr
	// 		FROM tbl_account_transaction_full t
	// 		WHERE t.status = 1
	// 		AND t.tbl_account_idtbl_account = ?
	// 		AND t.tbl_master_idtbl_master IN ($in_placeholders)
	// 		AND t.idtbl_account_transaction_full NOT IN (
	// 			SELECT tbl_account_transaction_idtbl_account_transaction
	// 			FROM tbl_bank_rec_info
	// 			WHERE status = 1
	// 			AND rec_info_origin_name = 'transaction_full'
	// 			AND tbl_bank_rec_list_idtbl_bank_rec_list = ?
	// 		)

	// 		UNION ALL

	// 		SELECT
	// 			r.idtbl_receivable AS idtbl_account_transaction,
	// 			r.recdate AS tradate,
	// 			r.narration,
	// 			r.amount AS accamount,
	// 			'C' AS crdr
	// 		FROM tbl_receivable r
	// 		WHERE r.status = 1
	// 		AND r.tbl_receivable_type_idtbl_receivable_type = 1
	// 		AND r.tbl_master_idtbl_master IN ($in_placeholders)
	// 		AND r.idtbl_receivable NOT IN (
	// 			SELECT tbl_account_transaction_idtbl_account_transaction
	// 			FROM tbl_bank_rec_info
	// 			WHERE status = 1
	// 			AND rec_info_origin_name = 'receivable_deposit'
	// 			AND tbl_bank_rec_list_idtbl_bank_rec_list = ?
	// 		)

	// 		ORDER BY tradate ASC
	// 	";

	// 	$unreconciled_params = array_merge(
	// 		[$account_id],
	// 		$master_ids,
	// 		[$rec_list_id],
	// 		$master_ids,
	// 		[$rec_list_id]
	// 	);

	// 	$unreconciled_items = $this->db->query(
	// 		$unreconciled_sql,
	// 		$unreconciled_params
	// 	)->result();

	// 	// ===============================================================
	// 	// 4️⃣ Bank Adjustments (from tbl_bank_rec_revision)
	// 	// ===============================================================
	// 	$adjustments_sql = "
	// 		SELECT
	// 			brr.idtbl_bank_rec_revision,
	// 			brr.bank_narration,
	// 			brr.bank_amount,
	// 			brr.tbl_account_idtbl_account_cr,
	// 			brr.tbl_account_idtbl_account_dr,
	// 			a_cr.accountname as cr_account,
	// 			a_dr.accountname as dr_account
	// 		FROM tbl_bank_rec_revision brr
	// 		LEFT JOIN tbl_account a_cr
	// 			ON a_cr.idtbl_account = brr.tbl_account_idtbl_account_cr
	// 		LEFT JOIN tbl_account a_dr
	// 			ON a_dr.idtbl_account = brr.tbl_account_idtbl_account_dr
	// 		WHERE brr.status = 1
	// 		AND brr.tbl_bank_rec_list_idtbl_bank_rec_list = ?
	// 		ORDER BY brr.idtbl_bank_rec_revision ASC
	// 	";

	// 	$bank_adjustments = $this->db->query(
	// 		$adjustments_sql,
	// 		[$rec_list_id]
	// 	)->result();

	// 	// ===============================================================
	// 	// 5️⃣ Book Balance (Opening + Transactions)
	// 	//    — tbl_account_transaction_full instead of tbl_account_transaction
	// 	// ===============================================================
	// 	$book_sql = "
	// 		SELECT
	// 			CASE WHEN IFNULL(drv_open.creditdebit, 'D') = 'D'
	// 				THEN  IFNULL(drv_open.openbal, 0)
	// 				ELSE -IFNULL(drv_open.openbal, 0)
	// 			END
	// 			+ IFNULL(drv_reg.dr_amount, 0)
	// 			- IFNULL(drv_reg.cr_amount, 0)
	// 			AS book_balance

	// 		FROM (SELECT 1) AS dummy

	// 		LEFT JOIN (
	// 			SELECT
	// 				IFNULL(SUM(openbal), 0) AS openbal,
	// 				MAX(creditdebit) AS creditdebit
	// 			FROM tbl_account_open_bal
	// 			WHERE status = 1
	// 			AND tbl_account_idtbl_account = ?
	// 			AND tbl_master_idtbl_master = ?
	// 		) AS drv_open ON 1=1

	// 		LEFT JOIN (
	// 			SELECT
	// 				SUM(accamount * (crdr = 'D')) AS dr_amount,
	// 				SUM(accamount * (crdr = 'C')) AS cr_amount
	// 			FROM tbl_account_transaction_full
	// 			WHERE status = 1
	// 			AND tbl_account_idtbl_account = ?
	// 			AND tbl_master_idtbl_master IN ($in_placeholders)
	// 		) AS drv_reg ON 1=1
	// 	";

	// 	$book_params = array_merge(
	// 		[$account_id, $open_bal_master],
	// 		[$account_id],
	// 		$master_ids
	// 	);

	// 	$book_row = $this->db->query($book_sql, $book_params)->row();
	// 	$book_balance = (float)($book_row->book_balance ?? 0);

	// 	// ===============================================================
	// 	// 6️⃣ Calculate Totals
	// 	// ===============================================================

	// 	// Reconciled totals
	// 	$total_reconciled_dr = 0;
	// 	$total_reconciled_cr = 0;

	// 	foreach($reconciled_items as $row){
	// 		if($row->crdr == 'D'){
	// 			$total_reconciled_dr += (float)$row->accamount;
	// 		} else {
	// 			$total_reconciled_cr += (float)$row->accamount;
	// 		}
	// 	}

	// 	// Unreconciled totals
	// 	$total_unreconciled_dr = 0;
	// 	$total_unreconciled_cr = 0;

	// 	foreach($unreconciled_items as $row){
	// 		if($row->crdr == 'D'){
	// 			$total_unreconciled_dr += (float)$row->accamount;
	// 		} else {
	// 			$total_unreconciled_cr += (float)$row->accamount;
	// 		}
	// 	}

	// 	// Adjustment total
	// 	$total_adjustments = 0;

	// 	foreach($bank_adjustments as $row){
	// 		$total_adjustments += (float)$row->bank_amount;
	// 	}

	// 	// Statement balance
	// 	$statement_balance = (float)($statement->statement_closed_bal ?? 0);

	// 	// Adjusted bank balance
	// 	// Bank Statement + Unreconciled DR - Unreconciled CR + Adjustments
	// 	$adjusted_bank_balance = $statement_balance
	// 						+ $total_unreconciled_dr
	// 						- $total_unreconciled_cr
	// 						+ $total_adjustments;

	// 	// Difference
	// 	$difference = abs($book_balance - $adjusted_bank_balance);
	// 	$is_reconciled = $difference < 0.01;

	// 	// ===============================================================
	// 	// RETURN
	// 	// ===============================================================
	// 	return (object)[

	// 		'statement'          => $statement,
	// 		'reconciled_items'   => $reconciled_items,
	// 		'unreconciled_items' => $unreconciled_items,
	// 		'bank_adjustments'   => $bank_adjustments,
	// 		'book_balance'       => $book_balance,

	// 		'summary' => (object)[

	// 			'statement_open_bal'  => (float)($statement->statement_open_bal ?? 0),
	// 			'statement_tot_dr'   => (float)($statement->statement_tot_dr ?? 0),
	// 			'statement_tot_cr'   => (float)($statement->statement_tot_cr ?? 0),
	// 			'statement_balance'  => $statement_balance,

	// 			'reconciled_dr'      => $total_reconciled_dr,
	// 			'reconciled_cr'      => $total_reconciled_cr,
	// 			'reconciled_count'   => count($reconciled_items),

	// 			'unreconciled_dr'    => $total_unreconciled_dr,
	// 			'unreconciled_cr'    => $total_unreconciled_cr,
	// 			'unreconciled_count' => count($unreconciled_items),

	// 			'total_adjustments'       => $total_adjustments,
	// 			'adjustment_count'        => count($bank_adjustments),

	// 			'book_balance'            => $book_balance,
	// 			'adjusted_bank_balance'   => $adjusted_bank_balance,
	// 			'difference'              => $difference,
	// 			'is_reconciled'           => $is_reconciled
	// 		]
	// 	];
	// }


	/**
	 * FIXED VERSION of getBankReconciliationReport()
	 *
	 * New bug found: the Bank Reconciliation module now carries forward
	 * outstanding items across months (an item dated in an earlier month can
	 * legitimately be ticked/matched as part of a LATER month's bank rec
	 * session). But this report's "Book Balance" and "Unreconciled Items"
	 * queries restricted tbl_master_idtbl_master to ONLY the report's own
	 * selected From/To period(s) ($master_ids) — so a transaction reconciled
	 * in September's session, but dated in August, was correctly counted in
	 * "Reconciled Transactions" (no period filter there) but silently
	 * excluded from "Book Balance" (since its own tbl_master = August, not
	 * in a September-only $master_ids list). That mismatch is exactly what
	 * produced the non-zero Difference / "Not Reconciled" status even though
	 * the Bank Reconciliation module itself showed 0.00 / Balanced.
	 *
	 * Fix: Book Balance and Unreconciled Items now use a CUMULATIVE (<=)
	 * period range — same pattern already used in BankReconciliationinfo.php's
	 * getOrderDetail() carry-forward fix — up to the report's own "to" period,
	 * instead of being limited to the report's own $master_ids list.
	 *
	 * Two new parameters added: $to_year, $to_month (the report's "to" period's
	 * fiscal year id / fiscal month id — pass $to_master->tbl_finacial_year_idtbl_finacial_year
	 * and $to_master->tbl_finacial_month_idtbl_finacial_month from the controller).
	 */
	// public function getBankReconciliationReport(
	// 	$company_id,
	// 	$branch_id,
	// 	$account_id,
	// 	$master_ids,
	// 	$open_bal_master,
	// 	$to_year,
	// 	$to_month
	// ){
	// 	$in_placeholders = implode(',', array_fill(0, count($master_ids), '?'));

	// 	// ===============================================================
	// 	// 1️⃣ Bank Statement Details (from tbl_bank_rec_list)
	// 	// ===============================================================
	// 	$statement_sql = "
	// 		SELECT
	// 			brl.idtbl_bank_rec_list,
	// 			brl.bank_rec_date,
	// 			brl.acc_rec_batchno,
	// 			brl.statement_open_bal,
	// 			brl.statement_tot_cr,
	// 			brl.statement_tot_dr,
	// 			brl.statement_closed_bal,
	// 			brl.rec_approved,
	// 			a.accountno,
	// 			a.accountname,
	// 			fy.year as financialyear,
	// 			fm.monthname as financialmonth
	// 		FROM tbl_bank_rec_list brl
	// 		INNER JOIN tbl_account a
	// 			ON a.idtbl_account = brl.tbl_account_idtbl_account
	// 		LEFT JOIN tbl_finacial_year fy
	// 			ON fy.idtbl_finacial_year = brl.tbl_finacial_year_idtbl_finacial_year
	// 		LEFT JOIN tbl_finacial_month fm
	// 			ON fm.idtbl_finacial_month = brl.tbl_finacial_month_idtbl_finacial_month
	// 		WHERE brl.status = 1
	// 		AND brl.tbl_account_idtbl_account = ?
	// 		AND brl.tbl_finacial_year_idtbl_finacial_year IN (
	// 			SELECT DISTINCT tbl_finacial_year_idtbl_finacial_year
	// 			FROM tbl_master
	// 			WHERE idtbl_master IN ($in_placeholders)
	// 		)
	// 		AND brl.tbl_finacial_month_idtbl_finacial_month IN (
	// 			SELECT DISTINCT tbl_finacial_month_idtbl_finacial_month
	// 			FROM tbl_master
	// 			WHERE idtbl_master IN ($in_placeholders)
	// 		)
	// 		ORDER BY brl.bank_rec_date DESC
	// 		LIMIT 1
	// 	";

	// 	$statement_params = array_merge(
	// 		[$account_id],
	// 		$master_ids,
	// 		$master_ids
	// 	);

	// 	$statement = $this->db->query($statement_sql, $statement_params)->row();

	// 	// If no reconciliation found return empty
	// 	if(empty($statement)){
	// 		return (object)[
	// 			'statement'               => null,
	// 			'reconciled_items'        => [],
	// 			'unreconciled_items'      => [],
	// 			'bank_adjustments'        => [],
	// 			'book_balance'            => 0,
	// 			'adjusted_bank_balance'   => 0,
	// 			'difference'              => 0,
	// 			'summary'                 => (object)[
	// 				'statement_balance'       => 0,
	// 				'total_reconciled'        => 0,
	// 				'total_unreconciled'      => 0,
	// 				'total_adjustments'       => 0,
	// 				'book_balance'            => 0,
	// 				'adjusted_bank_balance'   => 0,
	// 				'difference'              => 0,
	// 				'is_reconciled'           => false
	// 			]
	// 		];
	// 	}

	// 	$rec_list_id = $statement->idtbl_bank_rec_list;

	// 	// ===============================================================
	// 	// 2️⃣ Reconciled Transactions (matched in tbl_bank_rec_info)
	// 	//    — UNION across both possible sources. No period restriction
	// 	//    here (correct as-is): a reconciled item may legitimately be
	// 	//    dated in an earlier month than the rec session that matched it.
	// 	// ===============================================================
	// 	$reconciled_sql = "
	// 		SELECT
	// 			bri.idtbl_bank_rec_info,
	// 			bri.rec_info_origin_name,
	// 			t.tradate,
	// 			t.narration,
	// 			t.accamount,
	// 			t.crdr
	// 		FROM tbl_bank_rec_info bri
	// 		INNER JOIN tbl_account_transaction_full t
	// 			ON t.idtbl_account_transaction_full
	// 			= bri.tbl_account_transaction_idtbl_account_transaction
	// 		WHERE bri.status = 1
	// 		AND bri.rec_info_origin_name = 'transaction_full'
	// 		AND bri.tbl_bank_rec_list_idtbl_bank_rec_list = ?

	// 		UNION ALL

	// 		SELECT
	// 			bri.idtbl_bank_rec_info,
	// 			bri.rec_info_origin_name,
	// 			r.recdate AS tradate,
	// 			r.narration,
	// 			r.amount AS accamount,
	// 			'C' AS crdr
	// 		FROM tbl_bank_rec_info bri
	// 		INNER JOIN tbl_receivable r
	// 			ON r.idtbl_receivable
	// 			= bri.tbl_account_transaction_idtbl_account_transaction
	// 		WHERE bri.status = 1
	// 		AND bri.rec_info_origin_name = 'receivable_deposit'
	// 		AND bri.tbl_bank_rec_list_idtbl_bank_rec_list = ?

	// 		ORDER BY tradate ASC
	// 	";

	// 	$reconciled_items = $this->db->query(
	// 		$reconciled_sql,
	// 		[$rec_list_id, $rec_list_id]
	// 	)->result();

	// 	// ===============================================================
	// 	// 3️⃣ Unreconciled Transactions (in book but NOT in rec_info)
	// 	//    — UNION across both possible sources, restricted to a
	// 	//    CUMULATIVE period range (<= the report's "to" period) so
	// 	//    outstanding items carried forward from earlier months are
	// 	//    correctly represented — same range as the report's "to" period.
	// 	// ===============================================================
	// 	$unreconciled_sql = "
	// 		SELECT
	// 			t.idtbl_account_transaction_full AS idtbl_account_transaction,
	// 			t.tradate,
	// 			t.narration,
	// 			t.accamount,
	// 			t.crdr
	// 		FROM tbl_account_transaction_full t
	// 		INNER JOIN tbl_master m1
	// 			ON m1.idtbl_master = t.tbl_master_idtbl_master
	// 		WHERE t.status = 1
	// 		AND t.tbl_account_idtbl_account = ?
	// 		AND t.ismatch = 0
	// 		AND m1.status = 1
	// 		AND (
	// 			m1.tbl_finacial_year_idtbl_finacial_year < ?
	// 			OR (m1.tbl_finacial_year_idtbl_finacial_year = ?
	// 				AND m1.tbl_finacial_month_idtbl_finacial_month <= ?)
	// 		)
		
	// 		UNION ALL
		
	// 		SELECT
	// 			r.idtbl_receivable AS idtbl_account_transaction,
	// 			r.recdate AS tradate,
	// 			r.narration,
	// 			r.amount AS accamount,
	// 			'C' AS crdr
	// 		FROM tbl_receivable r
	// 		INNER JOIN tbl_master m2
	// 			ON m2.idtbl_master = r.tbl_master_idtbl_master
	// 		WHERE r.status = 1
	// 		AND r.tbl_receivable_type_idtbl_receivable_type = 1
	// 		AND r.depositstatus = 0
	// 		AND m2.status = 1
	// 		AND (
	// 			m2.tbl_finacial_year_idtbl_finacial_year < ?
	// 			OR (m2.tbl_finacial_year_idtbl_finacial_year = ?
	// 				AND m2.tbl_finacial_month_idtbl_finacial_month <= ?)
	// 		)
		
	// 		ORDER BY tradate ASC
	// 	";
		
	// 	$unreconciled_params = [
	// 		$account_id, $to_year, $to_year, $to_month,
	// 		$to_year, $to_year, $to_month
	// 	];
		
	// 	$unreconciled_items = $this->db->query(
	// 		$unreconciled_sql,
	// 		$unreconciled_params
	// 	)->result();

	// 	// ===============================================================
	// 	// 4️⃣ Bank Adjustments (from tbl_bank_rec_revision)
	// 	// ===============================================================
	// 	$adjustments_sql = "
	// 		SELECT
	// 			brr.idtbl_bank_rec_revision,
	// 			brr.bank_narration,
	// 			brr.bank_amount,
	// 			brr.tbl_account_idtbl_account_cr,
	// 			brr.tbl_account_idtbl_account_dr,
	// 			a_cr.accountname as cr_account,
	// 			a_dr.accountname as dr_account
	// 		FROM tbl_bank_rec_revision brr
	// 		LEFT JOIN tbl_account a_cr
	// 			ON a_cr.idtbl_account = brr.tbl_account_idtbl_account_cr
	// 		LEFT JOIN tbl_account a_dr
	// 			ON a_dr.idtbl_account = brr.tbl_account_idtbl_account_dr
	// 		WHERE brr.status = 1
	// 		AND brr.tbl_bank_rec_list_idtbl_bank_rec_list = ?
	// 		ORDER BY brr.idtbl_bank_rec_revision ASC
	// 	";

	// 	$bank_adjustments = $this->db->query(
	// 		$adjustments_sql,
	// 		[$rec_list_id]
	// 	)->result();

	// 	// ===============================================================
	// 	// 5️⃣ Book Balance (Opening + Transactions)
	// 	//    — CUMULATIVE (<=) period range up to the report's "to" period,
	// 	//    instead of the report's own $master_ids list, for the same
	// 	//    carry-forward reason as the Unreconciled query above.
	// 	// ===============================================================
	// 	$book_sql = "
	// 		SELECT
	// 			CASE WHEN IFNULL(drv_open.creditdebit, 'D') = 'D'
	// 				THEN  IFNULL(drv_open.openbal, 0)
	// 				ELSE -IFNULL(drv_open.openbal, 0)
	// 			END
	// 			+ IFNULL(drv_reg.dr_amount, 0)
	// 			- IFNULL(drv_reg.cr_amount, 0)
	// 			AS book_balance

	// 		FROM (SELECT 1) AS dummy

	// 		LEFT JOIN (
	// 			SELECT
	// 				IFNULL(SUM(openbal), 0) AS openbal,
	// 				MAX(creditdebit) AS creditdebit
	// 			FROM tbl_account_open_bal
	// 			WHERE status = 1
	// 			AND tbl_account_idtbl_account = ?
	// 			AND tbl_master_idtbl_master = ?
	// 		) AS drv_open ON 1=1

	// 		LEFT JOIN (
	// 			SELECT
	// 				SUM(t.accamount * (t.crdr = 'D')) AS dr_amount,
	// 				SUM(t.accamount * (t.crdr = 'C')) AS cr_amount
	// 			FROM tbl_account_transaction_full t
	// 			INNER JOIN tbl_master m3
	// 				ON m3.idtbl_master = t.tbl_master_idtbl_master
	// 			WHERE t.status = 1
	// 			AND t.tbl_account_idtbl_account = ?
	// 			AND m3.status = 1
	// 			AND (
	// 				m3.tbl_finacial_year_idtbl_finacial_year < ?
	// 				OR (m3.tbl_finacial_year_idtbl_finacial_year = ?
	// 					AND m3.tbl_finacial_month_idtbl_finacial_month <= ?)
	// 			)
	// 		) AS drv_reg ON 1=1
	// 	";

	// 	$book_params = [
	// 		$account_id, $open_bal_master,
	// 		$account_id, $to_year, $to_year, $to_month
	// 	];

	// 	$book_row = $this->db->query($book_sql, $book_params)->row();
	// 	$book_balance = (float)($book_row->book_balance ?? 0);

	// 	// ===============================================================
	// 	// 6️⃣ Calculate Totals
	// 	// ===============================================================

	// 	// Reconciled totals
	// 	$total_reconciled_dr = 0;
	// 	$total_reconciled_cr = 0;

	// 	foreach($reconciled_items as $row){
	// 		if($row->crdr == 'D'){
	// 			$total_reconciled_dr += (float)$row->accamount;
	// 		} else {
	// 			$total_reconciled_cr += (float)$row->accamount;
	// 		}
	// 	}

	// 	// Unreconciled totals
	// 	$total_unreconciled_dr = 0;
	// 	$total_unreconciled_cr = 0;

	// 	foreach($unreconciled_items as $row){
	// 		if($row->crdr == 'D'){
	// 			$total_unreconciled_dr += (float)$row->accamount;
	// 		} else {
	// 			$total_unreconciled_cr += (float)$row->accamount;
	// 		}
	// 	}

	// 	// Adjustment total
	// 	$total_adjustments = 0;

	// 	foreach($bank_adjustments as $row){
	// 		$total_adjustments += (float)$row->bank_amount;
	// 	}

	// 	// Statement balance
	// 	$statement_balance = (float)($statement->statement_closed_bal ?? 0);

	// 	// Adjusted bank balance
	// 	// Bank Statement + Unreconciled DR - Unreconciled CR + Adjustments
	// 	$adjusted_bank_balance = $statement_balance
	// 						+ $total_unreconciled_dr
	// 						- $total_unreconciled_cr
	// 						+ $total_adjustments;

	// 	// Difference
	// 	$difference = abs($book_balance - $adjusted_bank_balance);
	// 	$is_reconciled = $difference < 0.01;

	// 	// ===============================================================
	// 	// RETURN
	// 	// ===============================================================
	// 	return (object)[

	// 		'statement'          => $statement,
	// 		'reconciled_items'   => $reconciled_items,
	// 		'unreconciled_items' => $unreconciled_items,
	// 		'bank_adjustments'   => $bank_adjustments,
	// 		'book_balance'       => $book_balance,

	// 		'summary' => (object)[

	// 			'statement_open_bal'  => (float)($statement->statement_open_bal ?? 0),
	// 			'statement_tot_dr'   => (float)($statement->statement_tot_dr ?? 0),
	// 			'statement_tot_cr'   => (float)($statement->statement_tot_cr ?? 0),
	// 			'statement_balance'  => $statement_balance,

	// 			'reconciled_dr'      => $total_reconciled_dr,
	// 			'reconciled_cr'      => $total_reconciled_cr,
	// 			'reconciled_count'   => count($reconciled_items),

	// 			'unreconciled_dr'    => $total_unreconciled_dr,
	// 			'unreconciled_cr'    => $total_unreconciled_cr,
	// 			'unreconciled_count' => count($unreconciled_items),

	// 			'total_adjustments'       => $total_adjustments,
	// 			'adjustment_count'        => count($bank_adjustments),

	// 			'book_balance'            => $book_balance,
	// 			'adjusted_bank_balance'   => $adjusted_bank_balance,
	// 			'difference'              => $difference,
	// 			'is_reconciled'           => $is_reconciled
	// 		]
	// 	];
	// }

	public function getBankReconciliationReport(
		$company_id,
		$branch_id,
		$account_id,
		$master_ids,
		$open_bal_master,   // kept for signature compatibility, no longer used for book balance
		$to_year,
		$to_month
	){
		$in_placeholders = implode(',', array_fill(0, count($master_ids), '?'));

		// ===============================================================
		// 1️⃣ Bank Statement Details (from tbl_bank_rec_list)
		// ===============================================================
		$statement_sql = "
			SELECT
				brl.idtbl_bank_rec_list,
				brl.bank_rec_date,
				brl.acc_rec_batchno,
				brl.statement_open_bal,
				brl.statement_tot_cr,
				brl.statement_tot_dr,
				brl.statement_closed_bal,
				brl.rec_approved,
				a.accountno,
				a.accountname,
				fy.year as financialyear,
				fm.monthname as financialmonth
			FROM tbl_bank_rec_list brl
			INNER JOIN tbl_account a
				ON a.idtbl_account = brl.tbl_account_idtbl_account
			LEFT JOIN tbl_finacial_year fy
				ON fy.idtbl_finacial_year = brl.tbl_finacial_year_idtbl_finacial_year
			LEFT JOIN tbl_finacial_month fm
				ON fm.idtbl_finacial_month = brl.tbl_finacial_month_idtbl_finacial_month
			WHERE brl.status = 1
			AND brl.tbl_account_idtbl_account = ?
			AND brl.tbl_finacial_year_idtbl_finacial_year IN (
				SELECT DISTINCT tbl_finacial_year_idtbl_finacial_year
				FROM tbl_master
				WHERE idtbl_master IN ($in_placeholders)
			)
			AND brl.tbl_finacial_month_idtbl_finacial_month IN (
				SELECT DISTINCT tbl_finacial_month_idtbl_finacial_month
				FROM tbl_master
				WHERE idtbl_master IN ($in_placeholders)
			)
			ORDER BY brl.bank_rec_date DESC
			LIMIT 1
		";

		$statement_params = array_merge([$account_id], $master_ids, $master_ids);
		$statement = $this->db->query($statement_sql, $statement_params)->row();

		if(empty($statement)){
			return (object)[
				'statement'             => null,
				'reconciled_items'      => [],
				'unreconciled_items'    => [],
				'bank_adjustments'      => [],
				'book_balance'          => 0,
				'adjusted_bank_balance' => 0,
				'difference'            => 0,
				'summary' => (object)[
					'statement_balance'     => 0,
					'total_reconciled'      => 0,
					'total_unreconciled'    => 0,
					'total_adjustments'     => 0,
					'book_balance'          => 0,
					'adjusted_bank_balance' => 0,
					'difference'            => 0,
					'is_reconciled'         => false
				]
			];
		}

		$rec_list_id = $statement->idtbl_bank_rec_list;

		// ===============================================================
		// 2️⃣ Reconciled Transactions (matched in tbl_bank_rec_info)
		// ===============================================================
		// $reconciled_sql = "
		// 	SELECT
		// 		bri.idtbl_bank_rec_info,
		// 		bri.rec_info_origin_name,
		// 		t.tradate,
		// 		t.narration,
		// 		t.accamount,
		// 		t.crdr
		// 	FROM tbl_bank_rec_info bri
		// 	INNER JOIN tbl_account_transaction_full t
		// 		ON t.idtbl_account_transaction_full
		// 		= bri.tbl_account_transaction_idtbl_account_transaction
		// 	WHERE bri.status = 1
		// 	AND bri.rec_info_origin_name = 'transaction_full'
		// 	AND bri.tbl_bank_rec_list_idtbl_bank_rec_list = ?

		// 	UNION ALL

		// 	SELECT
		// 		bri.idtbl_bank_rec_info,
		// 		bri.rec_info_origin_name,
		// 		r.recdate AS tradate,
		// 		r.narration,
		// 		r.amount AS accamount,
		// 		'C' AS crdr
		// 	FROM tbl_bank_rec_info bri
		// 	INNER JOIN tbl_receivable r
		// 		ON r.idtbl_receivable
		// 		= bri.tbl_account_transaction_idtbl_account_transaction
		// 	WHERE bri.status = 1
		// 	AND bri.rec_info_origin_name = 'receivable_deposit'
		// 	AND bri.tbl_bank_rec_list_idtbl_bank_rec_list = ?

		// 	ORDER BY tradate ASC
		// ";
		$reconciled_sql = "
			SELECT
				bri.idtbl_bank_rec_info,
				bri.rec_info_origin_name,
				t.tradate,
				t.narration,
				t.accamount,
				t.crdr,
				ta.trabatchotherno AS bank_transaction_no,
				COALESCE(
					GROUP_CONCAT(DISTINCT ci.chequeno SEPARATOR ', '),
					r_re.chequeno,
					pcr.chequeno
				) AS cheque_no
			FROM tbl_bank_rec_info bri
			INNER JOIN tbl_account_transaction_full t
				ON t.idtbl_account_transaction_full
				= bri.tbl_account_transaction_idtbl_account_transaction
			LEFT JOIN tbl_account_transaction ta
				ON ta.batchno = t.batchno

			-- PS: Payment Settle chain
			LEFT JOIN tbl_account_paysettle aps
				ON aps.batchno = ta.trabatchotherno AND ta.trabatchotherno LIKE 'PS%'
			LEFT JOIN tbl_account_paysettle_has_tbl_cheque_issue psc
				ON psc.tbl_account_paysettle_idtbl_account_paysettle = aps.idtbl_account_paysettle
			LEFT JOIN tbl_cheque_issue ci
				ON ci.idtbl_cheque_issue = psc.tbl_cheque_issue_idtbl_cheque_issue

			-- RE: Receivable Settle chain (direct)
			LEFT JOIN tbl_receivable r_re
				ON r_re.batchno = ta.trabatchotherno AND ta.trabatchotherno LIKE 'RE%'

			-- PR: Petty Cash Reimburse chain (direct)
			LEFT JOIN tbl_pettycash_reimburse pcr
				ON pcr.reimbursecode = ta.trabatchotherno AND ta.trabatchotherno LIKE 'PR%'

			WHERE bri.status = 1
			AND bri.rec_info_origin_name = 'transaction_full'
			AND bri.tbl_bank_rec_list_idtbl_bank_rec_list = ?
			GROUP BY bri.idtbl_bank_rec_info

			UNION ALL

			SELECT
				bri.idtbl_bank_rec_info,
				bri.rec_info_origin_name,
				r.recdate AS tradate,
				r.narration,
				r.amount AS accamount,
				'C' AS crdr,
				r.batchno AS bank_transaction_no,
				r.chequeno AS cheque_no
			FROM tbl_bank_rec_info bri
			INNER JOIN tbl_receivable r
				ON r.idtbl_receivable
				= bri.tbl_account_transaction_idtbl_account_transaction
			WHERE bri.status = 1
			AND bri.rec_info_origin_name = 'receivable_deposit'
			AND bri.tbl_bank_rec_list_idtbl_bank_rec_list = ?

			ORDER BY tradate ASC
		";

		$reconciled_items = $this->db->query(
			$reconciled_sql,
			[$rec_list_id, $rec_list_id]
		)->result();

		// ===============================================================
		// 3️⃣ Unreconciled Transactions (in book but NOT in rec_info)
		//    Cumulative up to the report's "to" period.
		// ===============================================================

		// $unreconciled_sql = "
		// 	SELECT
		// 		t.idtbl_account_transaction_full AS idtbl_account_transaction,
		// 		t.tradate,
		// 		t.narration,
		// 		t.accamount,
		// 		t.crdr
		// 	FROM tbl_account_transaction_full t
		// 	INNER JOIN tbl_master m1
		// 		ON m1.idtbl_master = t.tbl_master_idtbl_master
		// 	WHERE t.status = 1
		// 	AND t.tbl_account_idtbl_account = ?
		// 	AND t.ismatch = 0
		// 	AND m1.status = 1
		// 	AND (
		// 		m1.tbl_finacial_year_idtbl_finacial_year < ?
		// 		OR (m1.tbl_finacial_year_idtbl_finacial_year = ?
		// 			AND m1.tbl_finacial_month_idtbl_finacial_month <= ?)
		// 	)

		// 	UNION ALL

		// 	SELECT
		// 		r.idtbl_receivable AS idtbl_account_transaction,
		// 		r.recdate AS tradate,
		// 		r.narration,
		// 		r.amount AS accamount,
		// 		'C' AS crdr
		// 	FROM tbl_receivable r
		// 	INNER JOIN tbl_master m2
		// 		ON m2.idtbl_master = r.tbl_master_idtbl_master
		// 	WHERE r.status = 1
		// 	AND r.tbl_receivable_type_idtbl_receivable_type = 1
		// 	AND r.depositstatus = 0
		// 	AND m2.status = 1
		// 	AND (
		// 		m2.tbl_finacial_year_idtbl_finacial_year < ?
		// 		OR (m2.tbl_finacial_year_idtbl_finacial_year = ?
		// 			AND m2.tbl_finacial_month_idtbl_finacial_month <= ?)
		// 	)

		// 	ORDER BY tradate ASC
		// ";

		// $unreconciled_params = [
		// 	$account_id, $to_year, $to_year, $to_month,
		// 	$to_year, $to_year, $to_month
		// ];

		// $unreconciled_sql = "
		// 	SELECT
		// 		t.idtbl_account_transaction_full AS idtbl_account_transaction,
		// 		t.tradate,
		// 		t.narration,
		// 		t.accamount,
		// 		t.crdr
		// 	FROM tbl_account_transaction_full t
		// 	INNER JOIN tbl_master m1
		// 		ON m1.idtbl_master = t.tbl_master_idtbl_master
		// 	LEFT JOIN tbl_bank_rec_info bri_chk
		// 		ON bri_chk.tbl_account_transaction_idtbl_account_transaction = t.idtbl_account_transaction_full
		// 		AND bri_chk.rec_info_origin_name = 'transaction_full'
		// 		AND bri_chk.status = 1
		// 	LEFT JOIN tbl_bank_rec_list brl_chk
		// 		ON brl_chk.idtbl_bank_rec_list = bri_chk.tbl_bank_rec_list_idtbl_bank_rec_list
		// 	WHERE t.status = 1
		// 	AND t.tbl_account_idtbl_account = ?
		// 	AND m1.status = 1
		// 	AND (
		// 		m1.tbl_finacial_year_idtbl_finacial_year < ?
		// 		OR (m1.tbl_finacial_year_idtbl_finacial_year = ?
		// 			AND m1.tbl_finacial_month_idtbl_finacial_month <= ?)
		// 	)
		// 	AND (
		// 		bri_chk.idtbl_bank_rec_info IS NULL
		// 		OR brl_chk.tbl_finacial_year_idtbl_finacial_year > ?
		// 		OR (brl_chk.tbl_finacial_year_idtbl_finacial_year = ?
		// 			AND brl_chk.tbl_finacial_month_idtbl_finacial_month > ?)
		// 	)

		// 	UNION ALL

		// 	SELECT
		// 		r.idtbl_receivable AS idtbl_account_transaction,
		// 		r.recdate AS tradate,
		// 		r.narration,
		// 		r.amount AS accamount,
		// 		'C' AS crdr
		// 	FROM tbl_receivable r
		// 	INNER JOIN tbl_master m2
		// 		ON m2.idtbl_master = r.tbl_master_idtbl_master
		// 	LEFT JOIN tbl_bank_rec_info bri_chk2
		// 		ON bri_chk2.tbl_account_transaction_idtbl_account_transaction = r.idtbl_receivable
		// 		AND bri_chk2.rec_info_origin_name = 'receivable_deposit'
		// 		AND bri_chk2.status = 1
		// 	LEFT JOIN tbl_bank_rec_list brl_chk2
		// 		ON brl_chk2.idtbl_bank_rec_list = bri_chk2.tbl_bank_rec_list_idtbl_bank_rec_list
		// 	WHERE r.status = 1
		// 	AND r.tbl_receivable_type_idtbl_receivable_type = 1
		// 	AND m2.status = 1
		// 	AND (
		// 		m2.tbl_finacial_year_idtbl_finacial_year < ?
		// 		OR (m2.tbl_finacial_year_idtbl_finacial_year = ?
		// 			AND m2.tbl_finacial_month_idtbl_finacial_month <= ?)
		// 	)
		// 	AND (
		// 		bri_chk2.idtbl_bank_rec_info IS NULL
		// 		OR brl_chk2.tbl_finacial_year_idtbl_finacial_year > ?
		// 		OR (brl_chk2.tbl_finacial_year_idtbl_finacial_year = ?
		// 			AND brl_chk2.tbl_finacial_month_idtbl_finacial_month > ?)
		// 	)

		// 	ORDER BY tradate ASC
		// ";

		// $unreconciled_params = [
		// 	$account_id, $to_year, $to_year, $to_month, $to_year, $to_year, $to_month,
		// 	$to_year, $to_year, $to_month, $to_year, $to_year, $to_month
		// ];

		$unreconciled_sql = "
			SELECT
				t.idtbl_account_transaction_full AS idtbl_account_transaction,
				t.tradate,
				t.narration,
				t.accamount,
				t.crdr,
				ta.trabatchotherno AS bank_transaction_no,
				COALESCE(
					GROUP_CONCAT(DISTINCT ci.chequeno SEPARATOR ', '),
					r_re.chequeno,
					pcr.chequeno
				) AS cheque_no
			FROM tbl_account_transaction_full t
			INNER JOIN tbl_master m1
				ON m1.idtbl_master = t.tbl_master_idtbl_master
			LEFT JOIN tbl_bank_rec_info bri_chk
				ON bri_chk.tbl_account_transaction_idtbl_account_transaction = t.idtbl_account_transaction_full
				AND bri_chk.rec_info_origin_name = 'transaction_full'
				AND bri_chk.status = 1
			LEFT JOIN tbl_bank_rec_list brl_chk
				ON brl_chk.idtbl_bank_rec_list = bri_chk.tbl_bank_rec_list_idtbl_bank_rec_list
			LEFT JOIN tbl_account_transaction ta
				ON ta.batchno = t.batchno

			-- PS chain
			LEFT JOIN tbl_account_paysettle aps
				ON aps.batchno = ta.trabatchotherno AND ta.trabatchotherno LIKE 'PS%'
			LEFT JOIN tbl_account_paysettle_has_tbl_cheque_issue psc
				ON psc.tbl_account_paysettle_idtbl_account_paysettle = aps.idtbl_account_paysettle
			LEFT JOIN tbl_cheque_issue ci
				ON ci.idtbl_cheque_issue = psc.tbl_cheque_issue_idtbl_cheque_issue

			-- RE chain
			LEFT JOIN tbl_receivable r_re
				ON r_re.batchno = ta.trabatchotherno AND ta.trabatchotherno LIKE 'RE%'

			-- PR chain
			LEFT JOIN tbl_pettycash_reimburse pcr
				ON pcr.reimbursecode = ta.trabatchotherno AND ta.trabatchotherno LIKE 'PR%'

			WHERE t.status = 1
			AND t.tbl_account_idtbl_account = ?
			AND m1.status = 1
			AND (
				m1.tbl_finacial_year_idtbl_finacial_year < ?
				OR (m1.tbl_finacial_year_idtbl_finacial_year = ?
					AND m1.tbl_finacial_month_idtbl_finacial_month <= ?)
			)
			AND (
				bri_chk.idtbl_bank_rec_info IS NULL
				OR brl_chk.tbl_finacial_year_idtbl_finacial_year > ?
				OR (brl_chk.tbl_finacial_year_idtbl_finacial_year = ?
					AND brl_chk.tbl_finacial_month_idtbl_finacial_month > ?)
			)
			GROUP BY t.idtbl_account_transaction_full

			UNION ALL

			SELECT
				r.idtbl_receivable AS idtbl_account_transaction,
				r.recdate AS tradate,
				r.narration,
				r.amount AS accamount,
				'C' AS crdr,
				r.batchno AS bank_transaction_no,
				r.chequeno AS cheque_no
			FROM tbl_receivable r
			INNER JOIN tbl_master m2
				ON m2.idtbl_master = r.tbl_master_idtbl_master
			LEFT JOIN tbl_bank_rec_info bri_chk2
				ON bri_chk2.tbl_account_transaction_idtbl_account_transaction = r.idtbl_receivable
				AND bri_chk2.rec_info_origin_name = 'receivable_deposit'
				AND bri_chk2.status = 1
			LEFT JOIN tbl_bank_rec_list brl_chk2
				ON brl_chk2.idtbl_bank_rec_list = bri_chk2.tbl_bank_rec_list_idtbl_bank_rec_list
			WHERE r.status = 1
			AND r.tbl_receivable_type_idtbl_receivable_type = 1
			AND m2.status = 1
			AND (
				m2.tbl_finacial_year_idtbl_finacial_year < ?
				OR (m2.tbl_finacial_year_idtbl_finacial_year = ?
					AND m2.tbl_finacial_month_idtbl_finacial_month <= ?)
			)
			AND (
				bri_chk2.idtbl_bank_rec_info IS NULL
				OR brl_chk2.tbl_finacial_year_idtbl_finacial_year > ?
				OR (brl_chk2.tbl_finacial_year_idtbl_finacial_year = ?
					AND brl_chk2.tbl_finacial_month_idtbl_finacial_month > ?)
			)

			ORDER BY tradate ASC
		";

		$unreconciled_params = [
			$account_id, $to_year, $to_year, $to_month, $to_year, $to_year, $to_month,
			$to_year, $to_year, $to_month, $to_year, $to_year, $to_month
		];
		  
		$unreconciled_items = $this->db->query(
			$unreconciled_sql,
			$unreconciled_params
		)->result();

		// ===============================================================
		// 4️⃣ Bank Adjustments (from tbl_bank_rec_revision)
		// ===============================================================
		$adjustments_sql = "
			SELECT
				brr.idtbl_bank_rec_revision,
				brr.bank_narration,
				brr.bank_amount,
				brr.tbl_account_idtbl_account_cr,
				brr.tbl_account_idtbl_account_dr,
				a_cr.accountname as cr_account,
				a_dr.accountname as dr_account
			FROM tbl_bank_rec_revision brr
			LEFT JOIN tbl_account a_cr
				ON a_cr.idtbl_account = brr.tbl_account_idtbl_account_cr
			LEFT JOIN tbl_account a_dr
				ON a_dr.idtbl_account = brr.tbl_account_idtbl_account_dr
			WHERE brr.status = 1
			AND brr.tbl_bank_rec_list_idtbl_bank_rec_list = ?
			ORDER BY brr.idtbl_bank_rec_revision ASC
		";

		$bank_adjustments = $this->db->query(
			$adjustments_sql,
			[$rec_list_id]
		)->result();

		// ===============================================================
		// 5️⃣ Totals
		// ===============================================================
		$total_reconciled_dr = 0;
		$total_reconciled_cr = 0;
		foreach($reconciled_items as $row){
			if($row->crdr == 'D'){
				$total_reconciled_dr += (float)$row->accamount;
			} else {
				$total_reconciled_cr += (float)$row->accamount;
			}
		}

		$total_unreconciled_dr = 0;
		$total_unreconciled_cr = 0;
		foreach($unreconciled_items as $row){
			if($row->crdr == 'D'){
				$total_unreconciled_dr += (float)$row->accamount;
			} else {
				$total_unreconciled_cr += (float)$row->accamount;
			}
		}

		$total_adjustments = 0;
		foreach($bank_adjustments as $row){
			$total_adjustments += (float)$row->bank_amount;
		}

		// ===============================================================
		// 6️⃣ Self-consistent Balance Chain (QuickBooks-style)
		//    All three balances derive from the SAME reconciled/unreconciled
		//    data already shown on this report — no separate open-balance
		//    or cumulative-ledger lookup that can silently diverge.
		// ===============================================================
		$statement_open_bal   = (float)($statement->statement_open_bal ?? 0);
		$statement_balance    = (float)($statement->statement_closed_bal ?? 0);

		// Cleared Balance = what the reconciled transactions say the
		// account balance should be. This SHOULD equal statement_balance.
		$cleared_balance = $statement_open_bal
						+ $total_reconciled_dr
						- $total_reconciled_cr;

		// Book Balance = Cleared Balance + everything not yet cleared.
		$book_balance = $cleared_balance
					+ $total_unreconciled_dr
					- $total_unreconciled_cr;

		// Adjusted Bank Balance = Statement Closing + not-yet-cleared + bank adjustments.
		$adjusted_bank_balance = $statement_balance
								+ $total_unreconciled_dr
								- $total_unreconciled_cr
								+ $total_adjustments;

		// Diagnostic: does the matched total actually equal the statement figure?
		$beginning_check = round($cleared_balance - $statement_balance, 2);

		$difference    = abs($book_balance - $adjusted_bank_balance);
		$is_reconciled = $difference < 0.01;

		// ===============================================================
		// RETURN
		// ===============================================================
		return (object)[
			'statement'          => $statement,
			'reconciled_items'   => $reconciled_items,
			'unreconciled_items' => $unreconciled_items,
			'bank_adjustments'   => $bank_adjustments,
			'book_balance'       => $book_balance,

			'summary' => (object)[
				'statement_open_bal'   => $statement_open_bal,
				'statement_tot_dr'     => (float)($statement->statement_tot_dr ?? 0),
				'statement_tot_cr'     => (float)($statement->statement_tot_cr ?? 0),
				'statement_balance'    => $statement_balance,

				'reconciled_dr'        => $total_reconciled_dr,
				'reconciled_cr'        => $total_reconciled_cr,
				'reconciled_count'     => count($reconciled_items),

				'unreconciled_dr'      => $total_unreconciled_dr,
				'unreconciled_cr'      => $total_unreconciled_cr,
				'unreconciled_count'   => count($unreconciled_items),

				'total_adjustments'    => $total_adjustments,
				'adjustment_count'     => count($bank_adjustments),

				'cleared_balance'      => $cleared_balance,
				'beginning_check'      => $beginning_check,   // 0 = matched totals tie to statement

				'book_balance'         => $book_balance,
				'adjusted_bank_balance'=> $adjusted_bank_balance,
				'difference'           => $difference,
				'is_reconciled'        => $is_reconciled
			]
		];
	}

	public function Getselectedaccount($accountno){
		$company_id = $_SESSION['companyid'];
        $branch_id =  $_SESSION['branchid'];

		$this->db->select("tbl_account.idtbl_account, tbl_account.accountno, tbl_account.accountname, '1' AS accounttype");
		$this->db->from('tbl_account');
		$this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');
		$this->db->where('tbl_account.status', 1);
		$this->db->where('tbl_account_allocation.companybank', $company_id);
		$this->db->where('tbl_account_allocation.branchcompanybank', $branch_id);
		$this->db->where('tbl_account.accountno', $accountno);
		$respond=$this->db->get();

		return $respond->row(0);
	}
}