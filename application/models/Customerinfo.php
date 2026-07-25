<?php class Customerinfo extends CI_Model {
	public function Customerinsertupdate() {
		$userID = $_SESSION['userid'];

		$customer_name = $this->input->post('customer_name');
		$business_regno = $this->input->post('business_regno');
		$nbtno = $this->input->post('nbtno');
		$ref_no = $this->input->post('ref_no');
		$svatno = $this->input->post('svatno');
		$telephoneno = $this->input->post('telephoneno');
		$faxno = $this->input->post('faxno');
		$vatno = $this->input->post('vatno');
		$vat_customer = $this->input->post('vat_customer');
		$line1 = $this->input->post('line1');
		$line2 = $this->input->post('line2');
		$city = $this->input->post('city');
		$state = $this->input->post('state');
		$dline1 = $this->input->post('dline1');
		$dline2 = $this->input->post('dline2');
		$dcity = $this->input->post('dcity');
		$dstate = $this->input->post('dstate');
		$business_status = $this->input->post('bstatus');
		$payementmethod = $this->input->post('payementmethod');
		$company_id=$_SESSION['companyid'];
		$branch_id=$_SESSION['branchid'];

		$recordOption = $this->input->post('recordOption');

		if (!empty($this->input->post('recordID'))) {
			$recordID = $this->input->post('recordID');
		}

		$insertdatetime = date('Y-m-d H:i:s');

		if ($recordOption == 1) {
			try {
				$this->db->trans_begin();

				$data = array(
					'customer' => $customer_name,
					'ref_no' => $ref_no,
					'bus_reg_no' => $business_regno,
					'nbt_no' => $nbtno,
					'svat_no' => $svatno,
					'vat_customer' => $vat_customer,
					'telephone_no' => $telephoneno,
					'fax_no' => $faxno,
					'address_line1' => $line1,
					'delivery_address_line1' => $dline1,
					'address_line2' => $line2,
					'delivery_address_line2' => $dline2,
					'city' => $city,
					'delivery_city' => $dcity,
					'state' => $state,
					'delivery_state' => $dstate,
					'vat_no' => $vatno,
					'business_status' => $business_status,
					'payment_method' => $payementmethod,
					'tbl_company_idtbl_company'=> $company_id, 
					'tbl_company_branch_idtbl_company_branch'=> $branch_id, 
					'status' => '1',
					'insertdatetime' => $insertdatetime,
					'tbl_user_idtbl_user' => $userID,
				);
				$this->db->insert('tbl_customer', $data);

				$insertId = $this->db->insert_id();

				$this->db->select('COUNT(*) as totalrecord');
				$this->db->from('tbl_account_detail_other');
				$this->db->where('tbl_company_idtbl_company', $company_id);
				$this->db->where('tbl_company_branch_idtbl_company_branch', $branch_id);
				$this->db->where('otheroptiontype', 2);
				$this->db->where('otheroption', $insertId);
				$respondcount = $this->db->get();

				if($respondcount->row(0)->totalrecord == 0):
					$chartspecialcate = array('35');

					$this->db->where('tbl_account_allocation.companybank', $company_id);
					$this->db->where('tbl_account_allocation.branchcompanybank', $branch_id);
					$this->db->where_in('tbl_account.specialcate', $chartspecialcate);
					$this->db->where('tbl_account.status', 1);
					$this->db->where('tbl_account_allocation.status', 1);
					$this->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
					$this->db->select('`tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`, `tbl_account`.`specialcate`');
					$this->db->from('tbl_account');
					$this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');

					$respondchart=$this->db->get();

					if($respondchart->num_rows() == 0){
						throw new Exception("No chart of account found for the specified option");
					}

					foreach($respondchart->result() as $rowchart){
						$chartofaccount=$rowchart->idtbl_account;
						$accountcatecode=$rowchart->accountno;

						$this->db->select('IFNULL((MAX(CAST(`tbl_account_detail`.`code` AS UNSIGNED)) + 1), 1) AS `nextaccouontno`');
						$this->db->from('tbl_account_detail');
						$this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_detail_idtbl_account_detail = tbl_account_detail.idtbl_account_detail', 'left');
						$this->db->where('tbl_account_allocation.companybank', $company_id);
						$this->db->where('tbl_account_allocation.branchcompanybank', $branch_id);
						$this->db->where('tbl_account_detail.status', 1);
						$this->db->where('tbl_account_allocation.status', 1);
						$this->db->where('tbl_account_allocation.tbl_account_detail_idtbl_account_detail is NOT NULL', NULL, FALSE);
						$this->db->where('tbl_account_detail.tbl_account_idtbl_account', $chartofaccount);
						$respond=$this->db->get();

						$detailaccountcode = $respond->row(0)->nextaccouontno;
						$detailaccountno='0000'.$detailaccountcode;
						$detailaccountno=substr($detailaccountno, -4);
						$detailaccountno=$accountcatecode.$detailaccountno;

						$detailaccount=$customer_name;
						
						$data = array(
							'code'=> $detailaccountcode, 
							'accountno'=> $detailaccountno, 
							'accountname'=> $detailaccount, 
							'status'=> '1', 
							'insertdatetime'=> $insertdatetime, 
							'tbl_user_idtbl_user'=> $userID,
							'tbl_account_idtbl_account'=> $chartofaccount
						);

						$this->db->insert('tbl_account_detail', $data);

						$chartofdetailaccountID=$this->db->insert_id();

						$datadetail = array(
							'companybank'=> $company_id, 
							'branchcompanybank'=> $branch_id, 
							'status'=> '1', 
							'insertdatetime'=> $insertdatetime, 
							'tbl_user_idtbl_user'=> $userID,
							'tbl_account_detail_idtbl_account_detail'=> $chartofdetailaccountID
						);
						$this->db->insert('tbl_account_allocation', $datadetail);
						
						$datadetailother = array(
							'otheroptiontype' => 2,
							'otheroption' => $insertId,
							'tbl_user_idtbl_user' => $userID,
							'tbl_account_detail_idtbl_account_detail ' => $chartofdetailaccountID,
							'tbl_company_idtbl_company' => $company_id,
							'tbl_company_branch_idtbl_company_branch' => $branch_id
						);

						$this->db->insert('tbl_account_detail_other', $datadetailother);
					}
				endif;

				$this->db->trans_commit();

				$actionObj=new stdClass();
				$actionObj->icon='fas fa-save';
				$actionObj->title='';
				$actionObj->message='Record Added Successfully';
				$actionObj->url='';
				$actionObj->target='_blank';
				$actionObj->type='success';

				$actionJSON=json_encode($actionObj);

				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Customer');
			}  
			catch (Exception $e) {
				$this->db->trans_rollback();

				$actionObj=new stdClass();
				$actionObj->icon='fas fa-warning';
				$actionObj->title='';
				$actionObj->message='Record Error';
				$actionObj->url='';
				$actionObj->target='_blank';
				$actionObj->type='danger';

				$actionJSON=json_encode($actionObj);

				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Customer');
			}			
		}
		else {
			try {
				$this->db->trans_begin();

				$data=array('customer'=> $customer_name,
					'ref_no' => $ref_no,
					'bus_reg_no'=> $business_regno,
					'nbt_no'=> $nbtno,
					'svat_no'=> $svatno,
					'vat_customer' => $vat_customer,
					'telephone_no'=> $telephoneno,
					'fax_no'=> $faxno,
					'address_line1'=> $line1,
					'delivery_address_line1'=> $dline1,
					'address_line2'=> $line2,
					'delivery_address_line2'=> $dline2,
					'city'=> $city,
					'delivery_city'=> $dcity,
					'state'=> $state,
					'delivery_state'=> $dstate,
					'vat_no'=> $vatno,
					'business_status'=> $business_status,
					'payment_method'=> $payementmethod,
					'tbl_company_idtbl_company'=> $company_id, 
					'tbl_company_branch_idtbl_company_branch'=> $branch_id, 
					'status'=> '1',
					'updatedatetime'=> $insertdatetime,
					'tbl_user_idtbl_user'=> $userID,
				);
				$this->db->where('idtbl_customer', $recordID);
				$this->db->update('tbl_customer', $data);

				$this->db->select('COUNT(*) as totalrecord');
				$this->db->from('tbl_account_detail_other');
				$this->db->where('tbl_company_idtbl_company', $company_id);
				$this->db->where('tbl_company_branch_idtbl_company_branch', $branch_id);
				$this->db->where('otheroptiontype', 2);
				$this->db->where('otheroption', $recordID);
				$respondcount = $this->db->get();

				if($respondcount->row(0)->totalrecord == 0):
					$chartspecialcate = array('35');

					$this->db->where('tbl_account_allocation.companybank', $company_id);
					$this->db->where('tbl_account_allocation.branchcompanybank', $branch_id);
					$this->db->where_in('tbl_account.specialcate', $chartspecialcate);
					$this->db->where('tbl_account.status', 1);
					$this->db->where('tbl_account_allocation.status', 1);
					$this->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
					$this->db->select('`tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`, `tbl_account`.`specialcate`');
					$this->db->from('tbl_account');
					$this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');

					$respondchart=$this->db->get();

					if($respondchart->num_rows() == 0){
						throw new Exception("No chart of account found for the specified option");
					}

					foreach($respondchart->result() as $rowchart){
						$chartofaccount=$rowchart->idtbl_account;
						$accountcatecode=$rowchart->accountno;

						$this->db->select('IFNULL((MAX(CAST(`tbl_account_detail`.`code` AS UNSIGNED)) + 1), 1) AS `nextaccouontno`');
						$this->db->from('tbl_account_detail');
						$this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_detail_idtbl_account_detail = tbl_account_detail.idtbl_account_detail', 'left');
						$this->db->where('tbl_account_allocation.companybank', $company_id);
						$this->db->where('tbl_account_allocation.branchcompanybank', $branch_id);
						$this->db->where('tbl_account_detail.status', 1);
						$this->db->where('tbl_account_allocation.status', 1);
						$this->db->where('tbl_account_allocation.tbl_account_detail_idtbl_account_detail is NOT NULL', NULL, FALSE);
						$this->db->where('tbl_account_detail.tbl_account_idtbl_account', $chartofaccount);
						$respond=$this->db->get();

						$detailaccountcode = $respond->row(0)->nextaccouontno;
						$detailaccountno='0000'.$detailaccountcode;
						$detailaccountno=substr($detailaccountno, -4);
						$detailaccountno=$accountcatecode.$detailaccountno;

						$detailaccount=$customer_name;
						
						$data = array(
							'code'=> $detailaccountcode, 
							'accountno'=> $detailaccountno, 
							'accountname'=> $detailaccount, 
							'status'=> '1', 
							'insertdatetime'=> $insertdatetime, 
							'tbl_user_idtbl_user'=> $userID,
							'tbl_account_idtbl_account'=> $chartofaccount
						);

						$this->db->insert('tbl_account_detail', $data);

						$chartofdetailaccountID=$this->db->insert_id();

						$datadetail = array(
							'companybank'=> $company_id, 
							'branchcompanybank'=> $branch_id, 
							'status'=> '1', 
							'insertdatetime'=> $insertdatetime, 
							'tbl_user_idtbl_user'=> $userID,
							'tbl_account_detail_idtbl_account_detail'=> $chartofdetailaccountID
						);
						$this->db->insert('tbl_account_allocation', $datadetail);
						
						$datadetailother = array(
							'otheroptiontype' => 2,
							'otheroption' => $recordID,
							'tbl_user_idtbl_user' => $userID,
							'tbl_account_detail_idtbl_account_detail ' => $chartofdetailaccountID,
							'tbl_company_idtbl_company' => $company_id,
							'tbl_company_branch_idtbl_company_branch' => $branch_id
						);

						$this->db->insert('tbl_account_detail_other', $datadetailother);
					}
				endif;

				$this->db->trans_commit();

				$actionObj=new stdClass();
				$actionObj->icon='fas fa-save';
				$actionObj->title='';
				$actionObj->message='Record Update Successfully';
				$actionObj->url='';
				$actionObj->target='_blank';
				$actionObj->type='primary';

				$actionJSON=json_encode($actionObj);

				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Customer');
			}
			catch (Exception $e) {
				$this->db->trans_rollback();

				$actionObj=new stdClass();
				$actionObj->icon='fas fa-warning';
				$actionObj->title='';
				$actionObj->message='Record Error';
				$actionObj->url='';
				$actionObj->target='_blank';
				$actionObj->type='danger';

				$actionJSON=json_encode($actionObj);

				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Customer');
			}
		}
	}
	public function Customerstatus($x, $y) {
		$this->db->trans_begin();

		$userID=$_SESSION['userid'];
		$recordID=$x;
		$type=$y;
		$updatedatetime=date('Y-m-d H:i:s');

		if($type==1) {
			$data=array(
				'status'=> '1',
				'tbl_user_idtbl_user'=> $userID,
				'updatedatetime'=> $updatedatetime
			);
			$this->db->where('idtbl_customer', $recordID);
			$this->db->update('tbl_customer', $data);

			$this->db->trans_complete();

			if ($this->db->trans_status()===TRUE) {
				$this->db->trans_commit();

				$actionObj=new stdClass();
				$actionObj->icon='fas fa-check';
				$actionObj->title='';
				$actionObj->message='Record Activate Successfully';
				$actionObj->url='';
				$actionObj->target='_blank';
				$actionObj->type='success';

				$actionJSON=json_encode($actionObj);

				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Customer');
			}
			else {
				$this->db->trans_rollback();

				$actionObj=new stdClass();
				$actionObj->icon='fas fa-warning';
				$actionObj->title='';
				$actionObj->message='Record Error';
				$actionObj->url='';
				$actionObj->target='_blank';
				$actionObj->type='danger';

				$actionJSON=json_encode($actionObj);

				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Customer');
			}
		}
		else if($type==2) {
			$data=array(
				'status'=> '2',
				'tbl_user_idtbl_user'=> $userID,
				'updatedatetime'=> $updatedatetime
			);
			$this->db->where('idtbl_customer', $recordID);
			$this->db->update('tbl_customer', $data);

			$this->db->trans_complete();

			if ($this->db->trans_status()===TRUE) {
				$this->db->trans_commit();

				$actionObj=new stdClass();
				$actionObj->icon='fas fa-times';
				$actionObj->title='';
				$actionObj->message='Record Deactivate Successfully';
				$actionObj->url='';
				$actionObj->target='_blank';
				$actionObj->type='warning';

				$actionJSON=json_encode($actionObj);

				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Customer');
			}
			else {
				$this->db->trans_rollback();

				$actionObj=new stdClass();
				$actionObj->icon='fas fa-warning';
				$actionObj->title='';
				$actionObj->message='Record Error';
				$actionObj->url='';
				$actionObj->target='_blank';
				$actionObj->type='danger';

				$actionJSON=json_encode($actionObj);

				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Customer');
			}
		}
		else if($type==3) {
			$data=array(
				'status'=> '3',
				'tbl_user_idtbl_user'=> $userID,
				'updatedatetime'=> $updatedatetime
			);
			$this->db->where('idtbl_customer', $recordID);
			$this->db->update('tbl_customer', $data);

			$this->db->trans_complete();

			if ($this->db->trans_status()===TRUE) {
				$this->db->trans_commit();

				$actionObj=new stdClass();
				$actionObj->icon='fas fa-trash-alt';
				$actionObj->title='';
				$actionObj->message='Record Remove Successfully';
				$actionObj->url='';
				$actionObj->target='_blank';
				$actionObj->type='danger';

				$actionJSON=json_encode($actionObj);

				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Customer');
			}
			else {
				$this->db->trans_rollback();

				$actionObj=new stdClass();
				$actionObj->icon='fas fa-warning';
				$actionObj->title='';
				$actionObj->message='Record Error';
				$actionObj->url='';
				$actionObj->target='_blank';
				$actionObj->type='danger';

				$actionJSON=json_encode($actionObj);

				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Customer');
			}
		}
	}
	public function Customeredit() {
		$recordID=$this->input->post('recordID');

		$this->db->select('*');
		$this->db->from('tbl_customer');
		$this->db->where('idtbl_customer', $recordID);
		$this->db->where('status', 1);

		$respond=$this->db->get();

		$obj=new stdClass();
		$obj->id=$respond->row(0)->idtbl_customer;
		$obj->name=$respond->row(0)->customer;
		$obj->business_regno=$respond->row(0)->bus_reg_no;
		$obj->nbtno=$respond->row(0)->nbt_no;
		$obj->vat_customer=$respond->row(0)->vat_customer;
		$obj->svatno=$respond->row(0)->svat_no;
		$obj->telephoneno=$respond->row(0)->telephone_no;
		$obj->faxno=$respond->row(0)->fax_no;
		$obj->ref_no=$respond->row(0)->ref_no;
		$obj->line1=$respond->row(0)->address_line1;
		$obj->line2=$respond->row(0)->address_line2;
		$obj->city=$respond->row(0)->city;
		$obj->state=$respond->row(0)->state;
		$obj->dline1=$respond->row(0)->delivery_address_line1;
		$obj->dline2=$respond->row(0)->delivery_address_line2;
		$obj->dcity=$respond->row(0)->delivery_city;
		$obj->dstate=$respond->row(0)->delivery_state;

		$obj->business_status=$respond->row(0)->business_status;
		$obj->payementmethod=$respond->row(0)->payment_method;
		// $obj->postal_code=$respond->row(0)->postal_code;
		// $obj->country=$respond->row(0)->country;
		$obj->vat_no=$respond->row(0)->vat_no;

		echo json_encode($obj);
	}
	public function Expenseseimageview() {
		$recordID=$this->input->post('recordID');

		$this->db->select('*');
		$this->db->from('tbl_customer_cetificate_bill');
		$this->db->where('tbl_customer_idtbl_customer', $recordID);
		$this->db->where('status', 1);

		$respond=$this->db->get(); // Get all the records
		if($respond->num_rows()>0){
			echo '<img class="bill-image" src="images/bills/'.$respond->row(0)->imagename.'" width="100%" height="100%"/>';
		}
	}
}