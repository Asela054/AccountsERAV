<?php
/**
*@if(CheckPermission('crm', 'read'))
**/
	function rs_to_kv($result, $eopt='-1', $etxt='Select'){
		$kv = isset($eopt)?array($eopt=>$etxt):array();
		
		foreach($result as $r){
			$kv[$r->form_key] = $r->form_val;
		}
		
		return $kv;
	}
	
	function get_company_list(){
		if(isset($_SESSION['companyid'])){
			$companyid=$_SESSION['companyid'];
		}

		$CI = get_instance();
		if(isset($_SESSION['companyid'])){
			$CI->db->where('idtbl_company', $companyid);
		}
		$CI->db->where('status', 1);
		$CI->db->select('idtbl_company, company, code');
		$CI->db->select('idtbl_company AS form_key, company AS form_val');
		$CI->db->from('tbl_company');
		return $CI->db->get()->result();
	}

	function get_all_company_branch_list(){
		$companyid=$_SESSION['companyid'];

		$CI = get_instance();
		$CI->db->where('status', 1);
		$CI->db->where('tbl_company_idtbl_company', $companyid);
		$CI->db->select('idtbl_company_branch, branch, code, tbl_company_idtbl_company');
		$CI->db->select('idtbl_company_branch AS form_key, branch AS form_val');
		$CI->db->from('tbl_company_branch');
		return $CI->db->get()->result();
	}
	
	function get_company_branch_list($companyid){
		$CI = get_instance();
		$CI->db->where('status', 1);
		$CI->db->where('tbl_company_idtbl_company', $companyid);
		$CI->db->select('idtbl_company_branch, branch, code, tbl_company_idtbl_company');
		$CI->db->select('idtbl_company_branch AS form_key, branch AS form_val');
		$CI->db->from('tbl_company_branch');
		echo json_encode($CI->db->get()->result());
	}
	
	function get_supplier_list() {
		$CI = get_instance();

		$configdata = getconfigdata('supplier_search');

		$tablename = $configdata->row(0)->tbl_name;
		$column1   = $configdata->row(0)->col_name;
		$column2   = $configdata->row(1)->col_name;
		$column3   = $configdata->row(2)->col_name;
		$column4   = $configdata->row(3)->col_name;

		$CI->db->where('status', 1);
		$CI->db->select("$column1 AS `idtbl_supplier`, $column2 AS `suppliername`, $column3 AS supcode, $column4 AS contactone", FALSE);
		$CI->db->select("$column1 AS form_key, $column2 AS form_val", FALSE);
		$CI->db->from($tablename);

		return $CI->db->get()->result();
	}

	function get_customer_list(){
		$CI = get_instance();

		$configdata = getconfigdata('customer_search');

		$tablename = $configdata->row(0)->tbl_name;
		$column1   = $configdata->row(0)->col_name;
		$column2   = $configdata->row(1)->col_name;

		$CI->db->where('status', 1);
		$CI->db->select("$column1 AS `idtbl_customer`, $column2 AS `customer`", FALSE);
		$CI->db->select("$column1 AS form_key, $column2 AS form_val", FALSE);
		$CI->db->from($tablename);
		return $CI->db->get()->result();
	}
	
	function get_child_account_list($companyid, $branchid, $json_enc=true){
		$CI = get_instance();
		$CI->db->where('tbl_account_allocation.companybank', $companyid);
        $CI->db->where('tbl_account_allocation.branchcompanybank', $branchid);
        $CI->db->where('tbl_account_detail.status', 1);
        $CI->db->where('tbl_account_allocation.status', 1);
        $CI->db->where('tbl_account_allocation.tbl_account_detail_idtbl_account_detail is NOT NULL', NULL, FALSE);
		$CI->db->select('`tbl_account_detail`.`idtbl_account_detail`, `tbl_account_detail`.`accountno`, `tbl_account_detail`.`accountname`');
		$CI->db->select('tbl_account_detail.idtbl_account_detail AS form_key, tbl_account_detail.accountname AS form_val');
		$CI->db->from('tbl_account_detail');
		$CI->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_detail_idtbl_account_detail = tbl_account_detail.idtbl_account_detail', 'left');
		
		if($json_enc){
			echo json_encode($CI->db->get()->result());  
		}else{
			return $CI->db->get()->result();
		}
	}
	
	function get_bank_list(){
		$CI = get_instance();
		//$CI->db->where('status', 0);
		$CI->db->select('idtbl_bank, bankname, code');
		
		$CI->db->from('tbl_bank');
		return $CI->db->get()->result();
	}
	
	function get_bank_branch_list(){
		$CI = get_instance();
		//$CI->db->where('status', 0);
		$CI->db->select('idtbl_bank_branch, branchname, code, tbl_bank_idtbl_bank');
		
		$CI->db->from('tbl_bank_branch');
		return $CI->db->get()->result();
	}

	function get_bank_account_list(){
		// $sql = "select drv_acc.idtbl_account, drv_acc.accountname, drv_doc.tbl_bank_idtbl_bank, drv_doc.tbl_bank_branch_idtbl_bank_branch as idtbl_bank_branch from (select distinct tbl_account_idtbl_account, tbl_bank_idtbl_bank, tbl_bank_branch_idtbl_bank_branch from tbl_cheque_info where status=1) as drv_doc inner join (select idtbl_account, accountname from tbl_account where tbl_account_type_idtbl_account_type=1) as drv_acc on drv_doc.tbl_account_idtbl_account=drv_acc.idtbl_account";
		$sql = "select drv_acc.idtbl_account, drv_acc.accountname, drv_doc.tbl_bank_idtbl_bank, drv_doc.tbl_bank_branch_idtbl_bank_branch as idtbl_bank_branch from (select distinct tbl_account_idtbl_account, tbl_bank_idtbl_bank, tbl_bank_branch_idtbl_bank_branch from tbl_cheque_info where status=1) as drv_doc inner join (select idtbl_account, accountname from tbl_account where 1=1) as drv_acc on drv_doc.tbl_account_idtbl_account=drv_acc.idtbl_account";
		$CI = get_instance();
		return $CI->db->query($sql)->result();
	}
	
	function get_cheque_list(){
		$CI = get_instance();
		//$CI->db->where('status', 0);
		$CI->db->select('idtbl_cheque_issue, chedate, chequeno, narration, tbl_cheque_info_idtbl_cheque_info');
		$CI->db->select('0 as cheque_amt'); // column-to-be-added-if-required
		$CI->db->from('tbl_cheque_issue');
		return $CI->db->get()->result();
	}
	
	function get_account_period($company, $company_branch){
		$CI = get_instance();
		//$CI->db->where('status', 0);
		$CI->db->where('tbl_master.tbl_company_idtbl_company', $company);
		$CI->db->where('tbl_master.tbl_company_branch_idtbl_company_branch', $company_branch);
		$CI->db->select('tbl_master.idtbl_master, tbl_master.tbl_finacial_year_idtbl_finacial_year, tbl_master.tbl_finacial_month_idtbl_finacial_month');
		$CI->db->select('tbl_finacial_year.year, tbl_finacial_year.desc');
		$CI->db->select('tbl_finacial_month.month, tbl_finacial_month.monthname');
		$CI->db->join('tbl_finacial_year', 'tbl_master.tbl_finacial_year_idtbl_finacial_year=tbl_finacial_year.idtbl_finacial_year', 'left');
		$CI->db->join('tbl_finacial_month', 'tbl_master.tbl_finacial_month_idtbl_finacial_month=tbl_finacial_month.idtbl_finacial_month', 'left');
		$CI->db->from('tbl_master');
		$CI->db->order_by('tbl_master.idtbl_master', 'DESC');
		$CI->db->limit(1);
		return $CI->db->get()->row(0);
	}

	function get_all_account_periods($company='', $company_branch=''){
		$CI = get_instance();
		$CI->db->where('tbl_finacial_year.actstatus', 1);
		$CI->db->where('tbl_finacial_month.activestatus', 1);
		
		if($company!=''){
			$CI->db->where('tbl_master.tbl_company_idtbl_company', $company);
		}
		if($company_branch!=''){
			$CI->db->where('tbl_master.tbl_company_branch_idtbl_company_branch', $company_branch);
		}
		
		$CI->db->select('tbl_master.idtbl_master, tbl_master.tbl_finacial_year_idtbl_finacial_year, tbl_master.tbl_finacial_month_idtbl_finacial_month');
		
		$CI->db->select('tbl_master.tbl_company_idtbl_company, tbl_master.tbl_company_branch_idtbl_company_branch');
		
		$CI->db->select('tbl_finacial_year.year, tbl_finacial_year.desc');
		$CI->db->select('tbl_finacial_month.month, tbl_finacial_month.monthname');
		$CI->db->join('tbl_finacial_year', 'tbl_master.tbl_finacial_year_idtbl_finacial_year=tbl_finacial_year.idtbl_finacial_year', 'inner');
		$CI->db->join('tbl_finacial_month', 'tbl_master.tbl_finacial_month_idtbl_finacial_month=tbl_finacial_month.idtbl_finacial_month', 'inner');
		$CI->db->from('tbl_master');
		$CI->db->order_by('tbl_master.idtbl_master', 'ASC');
		
		return $CI->db->get()->result();
	}
	
	function tr_batch_num($prefix, $branch){
		if(empty($prefix)||empty($branch)){
			return '';
		}
		
		$CI = get_instance();
		//start the transaction
		$CI->db->trans_begin();
		$flag = true;
		
		
		/*
		begin-process-to-generate-new-gatepasscode
		*/
		$new_ref = ''; //NULL; // purposely-breaking-gatepass-creation-process-without-valid-refnum
		
		$res_callback = 0; // get-updated-result-of-ref-num
		
		/*
		locking-and-generating-with-update-
		assuming-the-most-frequent-operation
		*/
		$CI->db->where('idtbl_batch_num_register', $prefix);
		$CI->db->where('tbl_company_branch_idtbl_company_branch', $branch);
		$CI->db->where('acq_locked', '0');
		$CI->db->set('ref_no', 'ref_no+1', FALSE);
		
		$update = $CI->db->update('tbl_batch_num_register', array('acq_locked'=>1));
		
		$affectedRowCnt = $CI->db->affected_rows();
		
		if($affectedRowCnt!=1){
			/*
			fallback-generating-with-insert-where-update-is-refused-
			leaving-primary-key-to-prevent-duplicates-as-less-frequent-incident
			
			*/
			$insert = $CI->db->insert('tbl_batch_num_register', 
										array('idtbl_batch_num_register'=>$prefix, 
									'tbl_company_branch_idtbl_company_branch'=>$branch)
									);
			$affectedRowCnt = $CI->db->affected_rows();
			$res_callback = 1; // set-newly-inserted-value
		}	
		
		if($affectedRowCnt==1){
			if($res_callback==0){
				/*read-the-locked-and-generated-number*/
				$resQuery = $CI->db->get_where('tbl_batch_num_register', 
												 array('idtbl_batch_num_register'=>$prefix,
													   'tbl_company_branch_idtbl_company_branch'=>$branch,
													   'acq_locked'=>1)
										)->row();
				
				//var_dump($resQuery);
				
				if(!empty($resQuery)){
					$res_callback = $resQuery->ref_no;
				}
				
				
				/*release-the-locked-number*/
				$CI->db->where('idtbl_batch_num_register', $prefix);
				$CI->db->where('tbl_company_branch_idtbl_company_branch', $branch);
				$CI->db->where('acq_locked', '1');
				$ResultOut = $CI->db->update('tbl_batch_num_register', array('acq_locked'=>0));
				
				if(!$ResultOut){
					$flag = false;
				}
			}
			
			if($res_callback>0){
				$str_callback = '000000'.$res_callback;
				$new_ref = $prefix.substr($str_callback, strlen($str_callback)-6, strlen($str_callback));
			}
		}else{
			$flag = false;
		}
		
		/*end-process-new-ref-number*/
		
		$CI->db->trans_complete();
		//check if transaction status TRUE or FALSE
		if(($CI->db->trans_status()===FALSE)||($flag==FALSE)){
			//if something went wrong, rollback everything
			$CI->db->trans_rollback();
			$importmsg = 'Transaction error';//.$detailData['order_qty']
			$msgclass = 'bg-warning text-white';
		}else{
			//if everything went right, commit the data to the database
			$CI->db->trans_commit();
			$msgclass = 'bg-success text-white';
		}
		
		return $new_ref;
	}
	
	function pay_prefix($companyid, $branchid){
		$CI = get_instance();
		$CI->db->where('tbl_master.status', 1);
		$CI->db->where('tbl_master.period_status', 1);
		$CI->db->where('tbl_master.tbl_company_idtbl_company', $companyid);
		$CI->db->where('tbl_master.tbl_company_branch_idtbl_company_branch', $branchid);
		$CI->db->select('tbl_finacial_year.year, tbl_finacial_month.month');
		$CI->db->from('tbl_master');
		$CI->db->join('tbl_finacial_year', 'tbl_finacial_year.idtbl_finacial_year = tbl_master.tbl_finacial_year_idtbl_finacial_year', 'left');
		$CI->db->join('tbl_finacial_month', 'tbl_finacial_month.idtbl_finacial_month = tbl_master.tbl_finacial_month_idtbl_finacial_month', 'left');
		$respond=$CI->db->get();

		$date = DateTime::createFromFormat('!m', $respond->row(0)->month);
		$monthName = $date->format('M');
		return 'AP'.$respond->row(0)->year.strtoupper($monthName);
	}

	function rece_prefix($companyid, $branchid){
		$CI = get_instance();
		$CI->db->where('tbl_master.status', 1);
		$CI->db->where('tbl_master.period_status', 1);
		$CI->db->where('tbl_master.tbl_company_idtbl_company', $companyid);
		$CI->db->where('tbl_master.tbl_company_branch_idtbl_company_branch', $branchid);
		$CI->db->select('tbl_finacial_year.year, tbl_finacial_month.month');
		$CI->db->from('tbl_master');
		$CI->db->join('tbl_finacial_year', 'tbl_finacial_year.idtbl_finacial_year = tbl_master.tbl_finacial_year_idtbl_finacial_year', 'left');
		$CI->db->join('tbl_finacial_month', 'tbl_finacial_month.idtbl_finacial_month = tbl_master.tbl_finacial_month_idtbl_finacial_month', 'left');
		$respond=$CI->db->get();

		$date = DateTime::createFromFormat('!m', $respond->row(0)->month);
		$monthName = $date->format('M');
		return 'AR'.$respond->row(0)->year.strtoupper($monthName);
	}

	function trans_prefix($companyid, $branchid){
		$CI = get_instance();
		$CI->db->where('tbl_master.status', 1);
		$CI->db->where('tbl_master.period_status', 1);
		$CI->db->where('tbl_master.tbl_company_idtbl_company', $companyid);
		$CI->db->where('tbl_master.tbl_company_branch_idtbl_company_branch', $branchid);
		$CI->db->select('tbl_finacial_year.year, tbl_finacial_month.month');
		$CI->db->from('tbl_master');
		$CI->db->join('tbl_finacial_year', 'tbl_finacial_year.idtbl_finacial_year = tbl_master.tbl_finacial_year_idtbl_finacial_year', 'left');
		$CI->db->join('tbl_finacial_month', 'tbl_finacial_month.idtbl_finacial_month = tbl_master.tbl_finacial_month_idtbl_finacial_month', 'left');
		$respond=$CI->db->get();

		$date = DateTime::createFromFormat('!m', $respond->row(0)->month);
		$monthName = $date->format('M');
		return 'AT'.$respond->row(0)->year.strtoupper($monthName);
	}

	function petty_prefix($companyid, $branchid){
		$CI = get_instance();
		$CI->db->where('tbl_master.status', 1);
		$CI->db->where('tbl_master.period_status', 1);
		$CI->db->where('tbl_master.tbl_company_idtbl_company', $companyid);
		$CI->db->where('tbl_master.tbl_company_branch_idtbl_company_branch', $branchid);
		$CI->db->select('tbl_finacial_year.year, tbl_finacial_month.month');
		$CI->db->from('tbl_master');
		$CI->db->join('tbl_finacial_year', 'tbl_finacial_year.idtbl_finacial_year = tbl_master.tbl_finacial_year_idtbl_finacial_year', 'left');
		$CI->db->join('tbl_finacial_month', 'tbl_finacial_month.idtbl_finacial_month = tbl_master.tbl_finacial_month_idtbl_finacial_month', 'left');
		$respond=$CI->db->get();

		$date = DateTime::createFromFormat('!m', $respond->row(0)->month);
		$monthName = $date->format('M');
		return 'PV'.$respond->row(0)->year.strtoupper($monthName);
	}

	function reimburse_prefix($companyid, $branchid){
		$CI = get_instance();
		$CI->db->where('tbl_master.status', 1);
		$CI->db->where('tbl_master.period_status', 1);
		$CI->db->where('tbl_master.tbl_company_idtbl_company', $companyid);
		$CI->db->where('tbl_master.tbl_company_branch_idtbl_company_branch', $branchid);
		$CI->db->select('tbl_finacial_year.year, tbl_finacial_month.month');
		$CI->db->from('tbl_master');
		$CI->db->join('tbl_finacial_year', 'tbl_finacial_year.idtbl_finacial_year = tbl_master.tbl_finacial_year_idtbl_finacial_year', 'left');
		$CI->db->join('tbl_finacial_month', 'tbl_finacial_month.idtbl_finacial_month = tbl_master.tbl_finacial_month_idtbl_finacial_month', 'left');
		$respond=$CI->db->get();

		$date = DateTime::createFromFormat('!m', $respond->row(0)->month);
		$monthName = $date->format('M');
		return 'PR'.$respond->row(0)->year.strtoupper($monthName);
	}
	
	function get_chart_account_acco_child_account($companyid, $branchid, $detailaccount){
		$CI = get_instance();
		$CI->db->where('tbl_account_allocation.companybank', $companyid);
        $CI->db->where('tbl_account_allocation.branchcompanybank', $branchid);
        $CI->db->where('tbl_account_detail.idtbl_account_detail', $detailaccount);
        $CI->db->where('tbl_account.status', 1);
        $CI->db->where('tbl_account_allocation.status', 1);
        $CI->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
		$CI->db->select('`tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`');
		$CI->db->from('tbl_account');
		$CI->db->join('tbl_account_detail', 'tbl_account_detail.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');
		$CI->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');
		
		return $CI->db->get();
	}

	function get_petty_account_list($companyid, $branchid){
		$CI = get_instance();
		$CI->db->where('tbl_account_allocation.companybank', $companyid);
        $CI->db->where('tbl_account_allocation.branchcompanybank', $branchid);
        // $CI->db->where('tbl_account.tbl_account_type_idtbl_account_type', 3);
        $CI->db->where('tbl_account.specialcate', 36);
        $CI->db->where('tbl_account.status', 1);
        $CI->db->where('tbl_account_allocation.status', 1);
        $CI->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
		$CI->db->select('`tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`');
		$CI->db->from('tbl_account');
		$CI->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');
		
		echo json_encode($CI->db->get()->result());  
	}

	function get_bank_acount_list($companyid, $branchid){
		$CI = get_instance();
		$CI->db->where('tbl_account_allocation.companybank', $companyid);
        $CI->db->where('tbl_account_allocation.branchcompanybank', $branchid);
        // $CI->db->where('tbl_account.tbl_account_type_idtbl_account_type', 1);
        $CI->db->where('tbl_account.status', 1);
        $CI->db->where('tbl_account_allocation.status', 1);
        $CI->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
		$CI->db->select('`tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`');
		$CI->db->from('tbl_account');
		$CI->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');
		
		echo json_encode($CI->db->get()->result());  
	}

	function get_customer_search_list($searchTerm) {
		$companyid = $_SESSION['companyid'];
		$branchid  = $_SESSION['branchid'];

		$configdata = getconfigdata('customer_search');

		$tablename = $configdata->row(0)->tbl_name;
		$column1   = $configdata->row(0)->col_name;
		$column2   = $configdata->row(1)->col_name;

		$CI = get_instance();
		$CI->db->where('status', 1);
		$CI->db->where('tbl_company_idtbl_company', $companyid);
		$CI->db->where('tbl_company_branch_idtbl_company_branch', $branchid);
		$CI->db->select("$column1 AS idtbl_customer, $column2 AS customer", FALSE);
		$CI->db->from($tablename);

		if (isset($searchTerm) && !empty($searchTerm)) {
			$CI->db->like($column2, $searchTerm, 'both');
		} else {
			$CI->db->limit(5);
		}

		$respond = $CI->db->get();

		$data = array();
		foreach ($respond->result() as $row) {
			$data[] = array("id" => $row->idtbl_customer, "text" => $row->customer);
		}

		echo json_encode($data);
	}

	function receiv_prefix($companyid, $branchid){
		$CI = get_instance();
		$CI->db->where('tbl_master.status', 1);
		$CI->db->where('tbl_master.period_status', 1);
		$CI->db->where('tbl_master.tbl_company_idtbl_company', $companyid);
		$CI->db->where('tbl_master.tbl_company_branch_idtbl_company_branch', $branchid);
		$CI->db->select('tbl_finacial_year.year, tbl_finacial_month.month');
		$CI->db->from('tbl_master');
		$CI->db->join('tbl_finacial_year', 'tbl_finacial_year.idtbl_finacial_year = tbl_master.tbl_finacial_year_idtbl_finacial_year', 'left');
		$CI->db->join('tbl_finacial_month', 'tbl_finacial_month.idtbl_finacial_month = tbl_master.tbl_finacial_month_idtbl_finacial_month', 'left');
		$respond=$CI->db->get();

		$date = DateTime::createFromFormat('!m', $respond->row(0)->month);
		$monthName = $date->format('M');
		return 'RE'.$respond->row(0)->year.strtoupper($monthName);
	}

	function bankrec_prefix($acc_year, $acc_month){
		$CI = get_instance();
		$CI->db->where('tbl_master.status', 1);
		$CI->db->where_in('tbl_master.period_status', array(1, 3));
		$CI->db->where('tbl_master.tbl_finacial_year_idtbl_finacial_year', $acc_year);
		$CI->db->where('tbl_master.tbl_finacial_month_idtbl_finacial_month', $acc_month);
		$CI->db->from('tbl_master');
		$CI->db->join('tbl_finacial_year', 'tbl_finacial_year.idtbl_finacial_year = tbl_master.tbl_finacial_year_idtbl_finacial_year', 'left');
		$CI->db->join('tbl_finacial_month', 'tbl_finacial_month.idtbl_finacial_month = tbl_master.tbl_finacial_month_idtbl_finacial_month', 'left');
		$respond=$CI->db->get();

		$date = DateTime::createFromFormat('!m', $respond->row(0)->month);
		$monthName = $date->format('M');
		return 'BR'.$respond->row(0)->year.strtoupper($monthName);
	}

	function get_chart_acount_list($companyid, $branchid){
		$CI = get_instance();
		$CI->db->where('tbl_account_allocation.companybank', $companyid);
        $CI->db->where('tbl_account_allocation.branchcompanybank', $branchid);
        // $CI->db->where('tbl_account.tbl_account_type_idtbl_account_type', 2);
        $CI->db->where('tbl_account.status', 1);
        $CI->db->where('tbl_account_allocation.status', 1);
        $CI->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
		$CI->db->select('`tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`');
		$CI->db->from('tbl_account');
		$CI->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');
		
		echo json_encode($CI->db->get()->result());  
	}

	function journal_prefix($companyid, $branchid){
		$CI = get_instance();
		$CI->db->where('tbl_master.status', 1);
		$CI->db->where('tbl_master.period_status', 1);
		$CI->db->where('tbl_master.tbl_company_idtbl_company', $companyid);
		$CI->db->where('tbl_master.tbl_company_branch_idtbl_company_branch', $branchid);
		$CI->db->select('tbl_finacial_year.year, tbl_finacial_month.month');
		$CI->db->from('tbl_master');
		$CI->db->join('tbl_finacial_year', 'tbl_finacial_year.idtbl_finacial_year = tbl_master.tbl_finacial_year_idtbl_finacial_year', 'left');
		$CI->db->join('tbl_finacial_month', 'tbl_finacial_month.idtbl_finacial_month = tbl_master.tbl_finacial_month_idtbl_finacial_month', 'left');
		$respond=$CI->db->get();

		$date = DateTime::createFromFormat('!m', $respond->row(0)->month);
		$monthName = $date->format('M');
		return 'JE'.$respond->row(0)->year.strtoupper($monthName);
	}

	function get_all_accounts($searchTerm, $companyid, $branchid){
		$CI = get_instance();
		if(!empty($CI->input->post('accountcategory'))){$accountcategory=$CI->input->post('accountcategory');}else{$accountcategory='';}

		if(!isset($searchTerm)){
			$CI = get_instance();
			$sql="SELECT `tbl_account`.`idtbl_account` AS `accountid`, `tbl_account`.`accountno`, `tbl_account`.`accountname`, '1' AS `acctype` FROM `tbl_account` LEFT JOIN `tbl_account_allocation` ON `tbl_account_allocation`.`tbl_account_idtbl_account`=`tbl_account`.`idtbl_account` LEFT JOIN `tbl_account_detail` ON `tbl_account_detail`.`tbl_account_idtbl_account`=`tbl_account`.`idtbl_account` WHERE `tbl_account`.`status`=? AND `tbl_account_allocation`.`status`=? AND `tbl_account_allocation`.`companybank`=? AND `tbl_account_allocation`.`branchcompanybank`=? AND `tbl_account_detail`.`tbl_account_idtbl_account` IS NULL";
			if(!empty($accountcategory)){$sql.=" AND `tbl_account`.`tbl_account_category_idtbl_account_category`='$accountcategory'";}
			$sql.=" UNION ALL
			SELECT `tbl_account_detail`.`idtbl_account_detail` AS `accountid`, `tbl_account_detail`.`accountno`, `tbl_account_detail`.`accountname`, '2' AS `acctype` FROM `tbl_account_detail`"; 
			if(!empty($accountcategory)){$sql.=" LEFT JOIN `tbl_account` ON `tbl_account`.`idtbl_account`=`tbl_account_detail`.`tbl_account_idtbl_account`";}
			$sql.=" LEFT JOIN `tbl_account_allocation` ON `tbl_account_allocation`.`tbl_account_detail_idtbl_account_detail`=`tbl_account_detail`.`idtbl_account_detail` WHERE `tbl_account_detail`.`status`=? AND `tbl_account_allocation`.`status`=? AND `tbl_account_allocation`.`companybank`=? AND `tbl_account_allocation`.`branchcompanybank`=? ";
			if(!empty($accountcategory)){$sql.=" AND `tbl_account`.`tbl_account_category_idtbl_account_category`='$accountcategory'";}
			$sql.="LIMIT 5";
			$respond=$CI->db->query($sql, array(1, 1, $companyid, $branchid, 1, 1, $companyid, $branchid));
		}
		else{            
            if(!empty($searchTerm)){
				$CI = get_instance();
				$sql="SELECT `tbl_account`.`idtbl_account` AS `accountid`, `tbl_account`.`accountno`, `tbl_account`.`accountname`, '1' AS `acctype` FROM `tbl_account` LEFT JOIN `tbl_account_allocation` ON `tbl_account_allocation`.`tbl_account_idtbl_account`=`tbl_account`.`idtbl_account` LEFT JOIN `tbl_account_detail` ON `tbl_account_detail`.`tbl_account_idtbl_account`=`tbl_account`.`idtbl_account` WHERE (`tbl_account`.`accountno` LIKE '%$searchTerm%' OR `tbl_account`.`accountname` LIKE '%$searchTerm%') AND `tbl_account`.`status`=? AND `tbl_account_allocation`.`status`=? AND `tbl_account_allocation`.`companybank`=? AND `tbl_account_allocation`.`branchcompanybank`=? AND `tbl_account_detail`.`tbl_account_idtbl_account` IS NULL";
				if(!empty($accountcategory)){$sql.=" AND `tbl_account`.`tbl_account_category_idtbl_account_category`='$accountcategory'";}
				$sql.=" UNION ALL
				SELECT `tbl_account_detail`.`idtbl_account_detail` AS `accountid`, `tbl_account_detail`.`accountno`, `tbl_account_detail`.`accountname`, '2' AS `acctype` FROM `tbl_account_detail`"; 
				if(!empty($accountcategory)){$sql.=" LEFT JOIN `tbl_account` ON `tbl_account`.`idtbl_account`=`tbl_account_detail`.`tbl_account_idtbl_account`";}
				$sql.=" LEFT JOIN `tbl_account_allocation` ON `tbl_account_allocation`.`tbl_account_detail_idtbl_account_detail`=`tbl_account_detail`.`idtbl_account_detail` WHERE (`tbl_account_detail`.`accountno` LIKE '%$searchTerm%' OR `tbl_account_detail`.`accountname` LIKE '%$searchTerm%') AND `tbl_account_detail`.`status`=? AND `tbl_account_allocation`.`status`=? AND `tbl_account_allocation`.`companybank`=? AND `tbl_account_allocation`.`branchcompanybank`=?";
				if(!empty($accountcategory)){$sql.=" AND `tbl_account`.`tbl_account_category_idtbl_account_category`='$accountcategory'";}
				$respond=$CI->db->query($sql, array(1, 1, $companyid, $branchid, 1, 1, $companyid, $branchid));
			}
			else{
				$CI = get_instance();
				$sql="SELECT `tbl_account`.`idtbl_account` AS `accountid`, `tbl_account`.`accountno`, `tbl_account`.`accountname`, '1' AS `acctype` FROM `tbl_account` LEFT JOIN `tbl_account_allocation` ON `tbl_account_allocation`.`tbl_account_idtbl_account`=`tbl_account`.`idtbl_account` LEFT JOIN `tbl_account_detail` ON `tbl_account_detail`.`tbl_account_idtbl_account`=`tbl_account`.`idtbl_account` WHERE `tbl_account`.`status`=? AND `tbl_account_allocation`.`status`=? AND `tbl_account_allocation`.`companybank`=? AND `tbl_account_allocation`.`branchcompanybank`=? AND `tbl_account_detail`.`tbl_account_idtbl_account` IS NULL";
				if(!empty($accountcategory)){$sql.=" AND `tbl_account`.`tbl_account_category_idtbl_account_category`='$accountcategory'";}
				$sql.=" UNION ALL
				SELECT `tbl_account_detail`.`idtbl_account_detail` AS `accountid`, `tbl_account_detail`.`accountno`, `tbl_account_detail`.`accountname`, '2' AS `acctype` FROM `tbl_account_detail`"; 
				if(!empty($accountcategory)){$sql.=" LEFT JOIN `tbl_account` ON `tbl_account`.`idtbl_account`=`tbl_account_detail`.`tbl_account_idtbl_account`";}
				$sql.=" LEFT JOIN `tbl_account_allocation` ON `tbl_account_allocation`.`tbl_account_detail_idtbl_account_detail`=`tbl_account_detail`.`idtbl_account_detail` WHERE `tbl_account_detail`.`status`=? AND `tbl_account_allocation`.`status`=? AND `tbl_account_allocation`.`companybank`=? AND `tbl_account_allocation`.`branchcompanybank`=? ";
				if(!empty($accountcategory)){$sql.=" AND `tbl_account`.`tbl_account_category_idtbl_account_category`='$accountcategory'";}
				$sql.="LIMIT 5";
				$respond=$CI->db->query($sql, array(1, 1, $companyid, $branchid, 1, 1, $companyid, $branchid));
			}
		}

		// echo json_encode($respond->result()); 

		$data=array();
        
        foreach ($respond->result() as $row) {
            $data[]=array("id"=>$row->accountid, "text"=>$row->accountname.' - '.$row->accountno, "acctype" => $row->acctype);
        }
        
        echo json_encode($data);
	}

	function get_account_periods($company='', $company_branch=''){
		$periodstatus = array(3, 1, 2); 

		$CI = get_instance();
		$CI->db->where_in('tbl_finacial_year.actstatus', $periodstatus);
		$CI->db->where_in('tbl_finacial_month.activestatus', $periodstatus);
		
		if($company!=''){
			$CI->db->where('tbl_master.tbl_company_idtbl_company', $company);
		}
		if($company_branch!=''){
			$CI->db->where('tbl_master.tbl_company_branch_idtbl_company_branch', $company_branch);
		}
		
		$CI->db->select('tbl_master.idtbl_master, tbl_master.tbl_finacial_year_idtbl_finacial_year, tbl_master.tbl_finacial_month_idtbl_finacial_month');
		
		$CI->db->select('tbl_master.tbl_company_idtbl_company, tbl_master.tbl_company_branch_idtbl_company_branch');
		
		$CI->db->select('tbl_finacial_year.year, tbl_finacial_year.desc');
		$CI->db->select('tbl_finacial_month.month, tbl_finacial_month.monthname');
		$CI->db->join('tbl_finacial_year', 'tbl_master.tbl_finacial_year_idtbl_finacial_year=tbl_finacial_year.idtbl_finacial_year', 'inner');
		$CI->db->join('tbl_finacial_month', 'tbl_master.tbl_finacial_month_idtbl_finacial_month=tbl_finacial_month.idtbl_finacial_month', 'inner');
		$CI->db->from('tbl_master');
		$CI->db->order_by('tbl_master.idtbl_master', 'ASC');
		
		echo json_encode($CI->db->get()->result());
	}

	function get_supplier_search_list($searchTerm) {
		$companyid = $_SESSION['companyid'];
		$branchid  = $_SESSION['branchid'];

		$configdata = getconfigdata('supplier_search');

		$tablename = $configdata->row(0)->tbl_name;
		$column1   = $configdata->row(0)->col_name;
		$column2   = $configdata->row(1)->col_name;
		$column3   = $configdata->row(2)->col_name;
		$column4   = $configdata->row(3)->col_name;

		$CI = get_instance();
		$CI->db->where('status', 1);
		$CI->db->where('tbl_company_idtbl_company', $companyid);
		$CI->db->where('tbl_company_branch_idtbl_company_branch', $branchid);
		$CI->db->select("$column1 AS idtbl_supplier, $column2 AS `suppliername`, $column3 AS supcode, $column4 AS contactone", FALSE);
		$CI->db->from($tablename);

		if (isset($searchTerm) && !empty($searchTerm)) {
			$CI->db->like($column2, $searchTerm, 'both'); 
		} else {
			$CI->db->limit(5);
		}

		$respond = $CI->db->get();

		$data = array();
		foreach ($respond->result() as $row) {
			$data[] = array("id" => $row->idtbl_supplier, "text" => $row->suppliername);
		}

		echo json_encode($data);
	}

	function get_chart_acount_select2($searchTerm, $companyid, $branchid){
		$CI = get_instance();
		$CI->db->where('tbl_account_allocation.companybank', $companyid);
        $CI->db->where('tbl_account_allocation.branchcompanybank', $branchid);
        // $CI->db->where('tbl_account.tbl_account_type_idtbl_account_type', 2);
        $CI->db->where('tbl_account.status', 1);
        $CI->db->where('tbl_account_allocation.status', 1);
        $CI->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
		if(!empty($searchTerm)) {
			$CI->db->group_start();
			$CI->db->like('tbl_account.accountno', $searchTerm, 'after');  // LIKE 'searchTerm%'
			$CI->db->or_like('tbl_account.accountname', $searchTerm);      // LIKE '%searchTerm%'
			$CI->db->group_end();
		}
		 else {
			$CI->db->limit(5);  // Add limit when searchTerm is empty
		}
		$CI->db->select('`tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`');
		$CI->db->from('tbl_account');
		$CI->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');
		
		$respond=$CI->db->get();

		$data=array();
        
        foreach ($respond->result() as $row) {
            $data[]=array("id"=>$row->idtbl_account, "text"=>$row->accountname.' - '.$row->accountno, "accno" => $row->accountno);
        }
        
        echo json_encode($data);
	}

	function get_material_search_list($searchTerm, $companyid, $branchid){
		$CI = get_instance();

		$configdata = getconfigdata('material_search');

		$tablename = $configdata->row(0)->tbl_name;
		$column1   = $configdata->row(0)->col_name;
		$column2   = $configdata->row(1)->col_name;
		$column3   = $configdata->row(2)->col_name;

		$CI->db->select("$column1 AS idtbl_print_material_info, $column2 AS materialname, $column3 AS materialinfocode", FALSE);
		$CI->db->from($tablename);
		$CI->db->where('status', 1);
		$CI->db->where('tbl_company_idtbl_company', $companyid);
		$CI->db->where('tbl_company_branch_idtbl_company_branch', $branchid);
		
		if(!empty($searchTerm)) {
			$CI->db->group_start(); 
			$CI->db->like($column2, $searchTerm, 'both');
			$CI->db->or_like($column3, $searchTerm, 'both');
			$CI->db->group_end(); 
		}
		if(empty($searchTerm)) {
			$CI->db->limit(5);
		}

		$respond = $CI->db->get();
		// print_r($CI->db->last_query());

		$data=array();
        
        foreach ($respond->result() as $row) {
            $data[]=array("id"=>$row->idtbl_print_material_info, "text"=>$row->materialname.' - '.$row->materialinfocode);
        }
        
        echo json_encode($data);
	}

	function btrans_prefix($companyid, $branchid){
		$CI = get_instance();
		$CI->db->where('tbl_master.status', 1);
		$CI->db->where('tbl_master.period_status', 1);
		$CI->db->where('tbl_master.tbl_company_idtbl_company', $companyid);
		$CI->db->where('tbl_master.tbl_company_branch_idtbl_company_branch', $branchid);
		$CI->db->select('tbl_finacial_year.year, tbl_finacial_month.month');
		$CI->db->from('tbl_master');
		$CI->db->join('tbl_finacial_year', 'tbl_finacial_year.idtbl_finacial_year = tbl_master.tbl_finacial_year_idtbl_finacial_year', 'left');
		$CI->db->join('tbl_finacial_month', 'tbl_finacial_month.idtbl_finacial_month = tbl_master.tbl_finacial_month_idtbl_finacial_month', 'left');
		$respond=$CI->db->get();

		$date = DateTime::createFromFormat('!m', $respond->row(0)->month);
		$monthName = $date->format('M');
		return 'BT'.$respond->row(0)->year.strtoupper($monthName);
	}

	function generate_batch_no($material_id) {
		$ci =& get_instance();
		
		// Get material info and current month's batch count in a single query
		$query = $ci->db->select('pmi.materialinfocode, pmi.tbl_supplier_idtbl_supplier, 
								COUNT(ps.idtbl_print_stock) as current_count')
					->from('tbl_print_material_info pmi')
					->join('tbl_print_stock ps', 'ps.tbl_print_material_info_idtbl_print_material_info = pmi.idtbl_print_material_info AND 
							YEAR(ps.grndate) = YEAR(CURRENT_DATE()) AND 
							MONTH(ps.grndate) = MONTH(CURRENT_DATE())', 'left')
					->where('pmi.idtbl_print_material_info', $material_id)
					->get();
		
		if ($query->num_rows() > 0) {
			$row = $query->row();
			
			// Generate batch number in format: BB0006JUN20251001
			$batch_no = $row->materialinfocode . 
					strtoupper(date('MY')) . // JUN2025 format
					$row->tbl_supplier_idtbl_supplier . 
					str_pad(($row->current_count + 1), 4, '0', STR_PAD_LEFT);
			
			return $batch_no;
		}
		
		return false;
	}

	function getSpecialCateDetailAccount($searchTerm) {
		$CI = get_instance();

		$configdata = getconfigdata('chartdetail_special');
		$rows = $configdata->result();

		$unionParts = [];
		$bindings   = [];
		$cateID     = 1;

		for ($i = 0; $i < count($rows); $i += 2) {
			$matchRow   = $rows[$i];      
			$displayRow = $rows[$i + 1];  

			$tablename  = $matchRow->tbl_name;   
			$idCol      = $matchRow->col_name;   
			$displayCol = $displayRow->col_name; 

			$sql = "SELECT `$idCol` AS `typeid`, `$displayCol` AS `type`, '$cateID' AS `typecate` 
					FROM `$tablename` 
					WHERE `status` = ?";
			$bindings[] = 1;

			if (!empty($searchTerm)) {
				$sql .= " AND `$displayCol` LIKE ?";
				$bindings[] = '%' . $searchTerm . '%';
			}

			$unionParts[] = $sql;
			$cateID++;
		}

		$fullSql = implode(" UNION ALL ", $unionParts);

		if (empty($searchTerm)) {
			$fullSql .= " LIMIT 5";
		}

		$respond = $CI->db->query($fullSql, $bindings);

		$data = array();
		foreach ($respond->result() as $row) {
			$data[] = array(
				"id"       => $row->typeid,
				"text"     => $row->type,
				"catetype" => $row->typecate
			);
		}

		echo json_encode($data);
	}

	function payset_prefix($companyid, $branchid){
		$CI = get_instance();
		$CI->db->where('tbl_master.status', 1);
		$CI->db->where('tbl_master.period_status', 1);
		$CI->db->where('tbl_master.tbl_company_idtbl_company', $companyid);
		$CI->db->where('tbl_master.tbl_company_branch_idtbl_company_branch', $branchid);
		$CI->db->select('tbl_finacial_year.year, tbl_finacial_month.month');
		$CI->db->from('tbl_master');
		$CI->db->join('tbl_finacial_year', 'tbl_finacial_year.idtbl_finacial_year = tbl_master.tbl_finacial_year_idtbl_finacial_year', 'left');
		$CI->db->join('tbl_finacial_month', 'tbl_finacial_month.idtbl_finacial_month = tbl_master.tbl_finacial_month_idtbl_finacial_month', 'left');
		$respond=$CI->db->get();

		$date = DateTime::createFromFormat('!m', $respond->row(0)->month);
		$monthName = $date->format('M');
		return 'PS'.$respond->row(0)->year.strtoupper($monthName);
	}

	function get_payable_account_list($searchTerm) {
		$companyid=$_SESSION['companyid'];
		$branchid=$_SESSION['branchid'];

		$CI = get_instance();

		// Get distinct account_detail and account IDs where payable is unsettled
		$payable_sql = "SELECT DISTINCT 
							`tbl_account_transaction_manual`.`tbl_account_detail_idtbl_account_detail`,
							`tbl_account_transaction_manual`.`tbl_account_idtbl_account`
						FROM `tbl_account_transaction_manual`
						WHERE `tbl_account_transaction_manual`.`tbl_company_idtbl_company` = ?
						AND `tbl_account_transaction_manual`.`tbl_company_branch_idtbl_company_branch` = ?
						AND `tbl_account_transaction_manual`.`payablestatus` = 1
						AND `tbl_account_transaction_manual`.`payablesettle` = 0";

		$payable_result = $CI->db->query($payable_sql, array($companyid, $branchid))->result();

		// Separate into detail IDs and account IDs
		$detail_ids = array_filter(array_unique(array_column($payable_result, 'tbl_account_detail_idtbl_account_detail')));
		$account_ids = array_filter(array_unique(array_column($payable_result, 'tbl_account_idtbl_account')));

		if (empty($detail_ids) && empty($account_ids)) {
			echo json_encode(array());
			return;
		}

		$detail_ids_str  = implode(',', array_map('intval', $detail_ids));
		$account_ids_str = implode(',', array_map('intval', $account_ids));

		$search_condition_account = !empty($searchTerm)
			? " AND (`tbl_account`.`accountno` LIKE '%$searchTerm%' OR `tbl_account`.`accountname` LIKE '%$searchTerm%')"
			: "";

		$search_condition_detail = !empty($searchTerm)
			? " AND (`tbl_account_detail`.`accountno` LIKE '%$searchTerm%' OR `tbl_account_detail`.`accountname` LIKE '%$searchTerm%')"
			: "";

		$sql = "";

		// Chart of Accounts
		if (!empty($account_ids_str)) {
			$sql .= "SELECT 
						`tbl_account`.`idtbl_account` AS `accountid`,
						`tbl_account`.`accountno`,
						`tbl_account`.`accountname`,
						'1' AS `acctype`
					FROM `tbl_account`
					WHERE `tbl_account`.`status` = 1
					AND `tbl_account`.`idtbl_account` IN ($account_ids_str)
					$search_condition_account";
		}

		// Chart of detail accounts
		if (!empty($detail_ids_str)) {
			if (!empty($sql)) { $sql .= " UNION ALL "; }
			$sql .= "SELECT 
						`tbl_account_detail`.`idtbl_account_detail` AS `accountid`,
						`tbl_account_detail`.`accountno`,
						`tbl_account_detail`.`accountname`,
						'2' AS `acctype`
					FROM `tbl_account_detail`
					WHERE `tbl_account_detail`.`status` = 1
					AND `tbl_account_detail`.`idtbl_account_detail` IN ($detail_ids_str)
					$search_condition_detail";
		}

		if (empty($searchTerm)) {
			$sql .= " LIMIT 5";
		}

		$respond = $CI->db->query($sql);

		$data = array();
		foreach ($respond->result() as $row) {
			$data[] = array(
				"id"      => $row->accountid,
				"text"    => $row->accountname . ' - ' . $row->accountno,
				"acctype" => $row->acctype
			);
		}

		echo json_encode($data);
	}

	function getconfigdata($config_key){
		$CI = get_instance();
		$CI->db->where('config_key', $config_key);
		$CI->db->select('`config_key`, `tbl_name`, `col_name`');
		$CI->db->from('tbl_account_config');
		
		return $CI->db->get();
	}

	function get_account_period_acco_date($company, $company_branch, $invoice_date){
		$invoicemonth = date('n', strtotime($invoice_date));
		$periodstatus = array(3, 1); 

		$CI = get_instance();
		$CI->db->where_in('tbl_finacial_month.activestatus', $periodstatus);
		$CI->db->where_in('tbl_master.period_status', $periodstatus);
		$CI->db->where("'$invoice_date' BETWEEN tbl_finacial_year.startdate AND tbl_finacial_year.enddate");
		$CI->db->where('tbl_finacial_month.month', $invoicemonth);
		$CI->db->where('tbl_master.tbl_company_idtbl_company', $company);
		$CI->db->where('tbl_master.tbl_company_branch_idtbl_company_branch', $company_branch);
		$CI->db->select('tbl_master.idtbl_master, tbl_master.tbl_finacial_year_idtbl_finacial_year, tbl_master.tbl_finacial_month_idtbl_finacial_month');
		$CI->db->select('tbl_finacial_year.year, tbl_finacial_year.desc');
		$CI->db->select('tbl_finacial_month.month, tbl_finacial_month.monthname');
		$CI->db->join('tbl_finacial_year', 'tbl_master.tbl_finacial_year_idtbl_finacial_year=tbl_finacial_year.idtbl_finacial_year', 'left');
		$CI->db->join('tbl_finacial_month', 'tbl_master.tbl_finacial_month_idtbl_finacial_month=tbl_finacial_month.idtbl_finacial_month', 'left');
		$CI->db->from('tbl_master');
		$CI->db->order_by('tbl_master.idtbl_master', 'DESC');
		$CI->db->limit(1);
		 
		return $CI->db->get()->row(0);
	}

	function generate_prefix($companyid, $branchid, $invoice_date, $prefix){
		$invoicemonth = date('n', strtotime($invoice_date));
		$periodstatus = array(3, 1);

		$CI = get_instance();
		$CI->db->where('tbl_master.status', 1);
		$CI->db->where_in('tbl_finacial_month.activestatus', $periodstatus);
		$CI->db->where_in('tbl_master.period_status', $periodstatus);
		$CI->db->where("'$invoice_date' BETWEEN tbl_finacial_year.startdate AND tbl_finacial_year.enddate");
		$CI->db->where('tbl_finacial_month.month', $invoicemonth);
		$CI->db->where('tbl_master.tbl_company_idtbl_company', $companyid);
		$CI->db->where('tbl_master.tbl_company_branch_idtbl_company_branch', $branchid);
		$CI->db->select('tbl_finacial_year.year, tbl_finacial_month.month');
		$CI->db->from('tbl_master');
		$CI->db->join('tbl_finacial_year', 'tbl_finacial_year.idtbl_finacial_year = tbl_master.tbl_finacial_year_idtbl_finacial_year', 'left');
		$CI->db->join('tbl_finacial_month', 'tbl_finacial_month.idtbl_finacial_month = tbl_master.tbl_finacial_month_idtbl_finacial_month', 'left');

		$respond = $CI->db->get();

		// ── FIX: validate before using row(0) ────────────────────────────────
		if(!$respond || $respond->num_rows() == 0){
			// Return empty string — caller checks if(empty($prefix)) → throw Exception
			return '';
		}

		$row = $respond->row(0);

		// Validate year and month values
		if(empty($row->year) || empty($row->month)){
			return '';
		}

		$date = DateTime::createFromFormat('!m', $row->month);

		// FIX: createFromFormat returns false if format fails
		if($date === false){
			return '';
		}

		$monthName = $date->format('M');
		return $prefix . $row->year . strtoupper($monthName);
	}
?>
