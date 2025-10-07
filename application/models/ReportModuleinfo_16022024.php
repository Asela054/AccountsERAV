<?php
class ReportModuleinfo extends CI_Model{
	public function getChartOfAccounts(){
		$this->db->select('idtbl_account, accountno, accountname');
		$this->db->from('tbl_account');
		$this->db->where('status', 1);
		return $this->db->get()->result();
	}	
	public function calc_stock($opening_stock=false, $stock_closing_date='2021-04-30'){
		$sql = "";
		
		if($opening_stock){
			$sql = "SELECT closingstock AS stock_close_value FROM tbl_stock_closing WHERE `date`='$stock_closing_date'";
		}else{
			$sql = "SELECT SUM(tbl_stock.fullqty*tbl_product.unitprice) AS stock_close_value FROM tbl_stock INNER JOIN tbl_product ON tbl_stock.tbl_product_idtbl_product=tbl_product.idtbl_product WHERE tbl_stock.status=1 AND tbl_stock.fullqty>0";
		}
		/*
		$stock_result = $this->db->query($sql);
		
		$row = $stock_result->row();
		return $row->stock_close_value;
		*/
		return 0;
	}
	public function pnlSectionDetails($report_section, $report_period_id){
		/*
		$sql = "SELECT tbl_gl_report_sub_sections.id AS fig_sect_ref, tbl_gl_report_sub_sections.sub_section_name AS sect_name, CONCAT(tbl_gl_report_sub_section_particulars.subaccount, ' ', tbl_subaccount.subaccountname) AS fig_name, ((IFNULL(drv_open.ac_open_balance, 0)*tbl_gl_report_sub_section_particulars.value_ac_open_bal)+((IFNULL(drv_crdr.dr_accamount, 0)*IFNULL(NULLIF(tbl_mainclass.transactiontype-2, 0), 1)+IFNULL(drv_crdr.cr_accamount, 0)*IFNULL(NULLIF(1-tbl_mainclass.transactiontype, 0), 1))*tbl_gl_report_sub_section_particulars.value_ac_cr_dr)) AS fig_value FROM tbl_gl_report_sub_sections ";
		*/
		$sql = "SELECT tbl_account_subcategory.idtbl_account_subcategory AS fig_sect_ref, tbl_account_subcategory.subcategory AS sect_name, CONCAT(tbl_account.accountno, ' ', tbl_account.accountname) AS fig_name, ((IFNULL(drv_open.ac_open_balance, 0)*0)+((IFNULL(drv_crdr.dr_accamount, 0)*IFNULL(NULLIF(tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype-2, 0), 1)+IFNULL(drv_crdr.cr_accamount, 0)*IFNULL(NULLIF(1-tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype, 0), 1))*1)) AS fig_value FROM tbl_account_subcategory ";
		
		/*
		$sql .= "INNER JOIN tbl_gl_report_sub_section_particulars ON tbl_gl_report_sub_sections.id=tbl_gl_report_sub_section_particulars.tbl_gl_report_sub_section_id INNER JOIN tbl_subaccount ON tbl_gl_report_sub_section_particulars.subaccount=tbl_subaccount.subaccount INNER JOIN tbl_mainclass ON tbl_subaccount.mainclasscode=tbl_mainclass.code ";
		*/
		/*$sql .= "INNER JOIN tbl_account ON tbl_account_subcategory.idtbl_account_subcategory=tbl_account.tbl_account_subcategory_idtbl_account_subcategory INNER JOIN tbl_account_category ON tbl_account.tbl_account_category_idtbl_account_category=tbl_account_category.idtbl_account_category ";*/
		$sql .= "INNER JOIN tbl_account_category ON tbl_account_subcategory.tbl_account_category_idtbl_account_category=tbl_account_category.idtbl_account_category INNER JOIN tbl_account ON tbl_account_subcategory.idtbl_account_subcategory=tbl_account.tbl_account_subcategory_idtbl_account_subcategory ";
		/*
		$sql .= "LEFT OUTER JOIN (SELECT subaccount, SUM(ac_open_balance) AS ac_open_balance FROM tbl_gl_account_balance_details WHERE tbl_master_idtbl_master=? GROUP BY subaccount) AS drv_open ON tbl_gl_report_sub_section_particulars.subaccount=drv_open.subaccount ";
		*/
		$sql .= "LEFT OUTER JOIN (SELECT '-1' as tbl_account_idtbl_account, 0 AS ac_open_balance) AS drv_open ON tbl_account.idtbl_account=drv_open.tbl_account_idtbl_account ";
		
		/*
		$sql .= "LEFT OUTER JOIN (SELECT acccode, SUM(accamount*(crdr='D')) AS dr_accamount, SUM(accamount*(crdr='C')) AS cr_accamount FROM tbl_account_transaction WHERE reversstatus=0 AND tbl_master_idtbl_master=? GROUP BY acccode) AS drv_crdr ON tbl_gl_report_sub_section_particulars.subaccount=drv_crdr.acccode ";
		*/
		$sql .= "LEFT OUTER JOIN (SELECT tbl_account_idtbl_account, SUM(accamount*(crdr='D')) AS dr_accamount, SUM(accamount*(crdr='C')) AS cr_accamount FROM tbl_account_transaction WHERE reversstatus=0 AND tbl_master_idtbl_master=? GROUP BY tbl_account_idtbl_account) AS drv_crdr ON tbl_account.idtbl_account=drv_crdr.tbl_account_idtbl_account ";
		
		/*
		$sql .= "WHERE tbl_gl_report_sub_sections.tbl_gl_report_head_section_id=? AND tbl_gl_report_sub_sections.sect_cancel=0 AND tbl_gl_report_sub_section_particulars.report_part_cancel=0 ORDER BY tbl_gl_report_sub_section_particulars.fig_seq_no, tbl_gl_report_sub_section_particulars.tbl_gl_report_sub_section_id";
		*/
		$sql .= "WHERE tbl_account_subcategory.tbl_account_category_idtbl_account_category=? AND tbl_account_subcategory.status=1 AND tbl_account.status=1 ORDER BY tbl_account.code, tbl_account.tbl_account_subcategory_idtbl_account_subcategory";
		
		$section_result = $this->db->query($sql, array($report_period_id, $report_section));
		
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
		
		$sql .= "HAVING accamount>0";
		
		$trialbalance_result = $this->db->query($sql, array($branch_id, $report_period_id));
		
		return $trialbalance_result->result();
	}
	public function Getbalancesheetinfo(){
		$company = $this->input->post('company_id');
		$branch = $this->input->post('company_branch_id');
		$periodfrom = $this->input->post('period_from');
		$periodto = $this->input->post('period_upto');

		$sql="SELECT `daccount`.`idtbl_account`, `daccount`.`accountno`, `daccount`.`accountname`, `daccount`.`category`, `daccount`.`code`, `daccount`.`idtbl_account_category`, `daccount`.`tbl_account_subcategory_idtbl_account_subcategory`, `daccount`.`subcategory`, IFNULL(`daccbal`.`openbal`, 0) AS `openbal`, ABS(IFNULL(`ddebit`.`debitamount`, 0)-IFNULL(`dcredit`.`creditamount`, 0)) AS `trabal`, ABS(IFNULL(`daccbal`.`openbal`, 0)+(IFNULL(`ddebit`.`debitamount`, 0)-IFNULL(`dcredit`.`creditamount`, 0))) AS `nettrabal`, IFNULL(`daccbal`.`openbal`, 0)+(IFNULL(`ddebit`.`debitamount`, 0)-IFNULL(`dcredit`.`creditamount`, 0)) AS `nettrabalreal` FROM (SELECT `tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`, `tbl_account`.`tbl_account_subcategory_idtbl_account_subcategory`, `tbl_account_category`.`category`, `tbl_account_category`.`code`, `tbl_account_category`.`idtbl_account_category`, `tbl_account_subcategory`.`subcategory`  FROM `tbl_account` LEFT JOIN `tbl_account_category` ON `tbl_account_category`.`idtbl_account_category`=`tbl_account`.`tbl_account_category_idtbl_account_category` LEFT JOIN `tbl_account_subcategory` ON `tbl_account_subcategory`.`idtbl_account_subcategory`=`tbl_account`.`tbl_account_subcategory_idtbl_account_subcategory` LEFT JOIN `tbl_account_open_bal` ON `tbl_account_open_bal`.`tbl_account_idtbl_account`=`tbl_account`.`idtbl_account` WHERE `tbl_account`.`status`=? AND `tbl_account_category`.`tbl_account_finacialtype_idtbl_account_finacialtype`=?) AS `daccount` LEFT JOIN (SELECT `openbal`, `tbl_account_idtbl_account` FROM `tbl_account_open_bal` WHERE `status`=? AND `tbl_master_idtbl_master` BETWEEN ? AND ? AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=?) AS `daccbal` ON `daccbal`.`tbl_account_idtbl_account`=`daccount`.`idtbl_account` LEFT JOIN (SELECT SUM(`totamount`) AS `debitamount`, `tbl_account_idtbl_account`, `crdr` FROM `tbl_account_transaction` WHERE `status`=? AND `tbl_master_idtbl_master` BETWEEN ? AND ? AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=? AND `crdr`='D' GROUP BY `tbl_account_idtbl_account`) AS `ddebit` ON `ddebit`.`tbl_account_idtbl_account`=`daccount`.`idtbl_account` LEFT JOIN (SELECT SUM(`totamount`) AS `creditamount`, `tbl_account_idtbl_account`, `crdr` FROM `tbl_account_transaction` WHERE `status`=? AND `tbl_master_idtbl_master` BETWEEN ? AND ? AND `tbl_company_idtbl_company`=? AND `tbl_company_branch_idtbl_company_branch`=? AND `crdr`='C' GROUP BY `tbl_account_idtbl_account`) AS `dcredit` ON `dcredit`.`tbl_account_idtbl_account`=`daccount`.`idtbl_account` ORDER BY `daccount`.`idtbl_account_category`, `daccount`.`tbl_account_subcategory_idtbl_account_subcategory` ASC";
		$respond = $this->db->query($sql, array(1, 2, 1, $periodfrom, $periodto, $company, $branch, 1, $periodfrom, $periodto, $company, $branch, 1, $periodfrom, $periodto, $company, $branch));

		return $respond;
		// print_r($this->db->last_query()); 
		// foreach($respond->result() AS $rowdatalist){

		// }
	}
}
