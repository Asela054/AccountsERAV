<?php
class AccountPaymentinfo extends CI_Model{
    public function getOrderHeader($orderRef, $accDetailRef){
		$sql = "select drvm.idtbl_account_payable_main, drvm.tradate, drvm.batchno, drvm.supplier, drvm.invoiceno, drvm.amount as m_amount, ";
		$sql .= "drvm.tbl_company_idtbl_company, drvm.tbl_company_branch_idtbl_company_branch, drvm.tbl_master_idtbl_master, ";
		$sql .= "drvm.status as m_status, drvm.poststatus as m_poststatus, ";
		$sql .= "drvo.idtbl_account_payable, drvo.amount as o_amount, drvo.narration as o_narration, drvo.status as o_status, ";
		$sql .= "drvo.tbl_account_detail_idtbl_account_detail ";
		$sql .= "from tbl_account_payable_main AS drvm ";
		$sql .= "left outer join (select idtbl_account_payable, amount, narration, status, tbl_account_detail_idtbl_account_detail, tbl_account_payable_main_idtbl_account_payable_main from tbl_account_payable where tbl_account_payable_main_idtbl_account_payable_main=? and tbl_account_detail_idtbl_account_detail=?) as drvo on ";
		$sql .= "drvm.idtbl_account_payable_main=drvo.tbl_account_payable_main_idtbl_account_payable_main ";
		$sql .= "where drvm.idtbl_account_payable_main=?";
    	$result = $this->db->query($sql, array($orderRef, $accDetailRef, $orderRef));
		$row = $result->row();
		return $row;
	}
	
	public function getOrderHeaderByInvoice($invoice_no){
		$this->db->where('invoiceno', $invoice_no);
		//$this->db->or_where('salesorder_ref', $orderRef);
		$row = $this->db->get('tbl_account_payable_main')->row();
		return $row;
	}
	
	public function getOrderDetail($orderRef){
		$this->db->where('tbl_account_payable.tbl_account_payable_main_idtbl_account_payable_main', $orderRef);
		$this->db->select('tbl_account_payable.idtbl_account_payable, tbl_account_payable.narration, tbl_account_payable.amount, tbl_account_payable.status');
		$this->db->select('tbl_account_detail.idtbl_account_detail, tbl_account_detail.accountname');
		$this->db->from('tbl_account_payable');
		$this->db->join('tbl_account_detail', 'tbl_account_payable.tbl_account_detail_idtbl_account_detail=tbl_account_detail.idtbl_account_detail');
		return $this->db->get()->result();
	}
	
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
	
	public function regUpdateOrder($headerData, $headerDataExistingId, $detailData, $detailDataExistingId){
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
				$insert = $this->db->insert('tbl_account_payable_main', $headerData);
				
				$detailData['tbl_account_payable_main_idtbl_account_payable_main']=$this->db->insert_id();
				
				$head_k = $detailData['tbl_account_payable_main_idtbl_account_payable_main'];
				
				// Return the status
				//$importmsg = $insert?'Style details added':'Unable to add style';
			}
		}else{//echo 2;
			if(!empty($headerData)){//echo '2.1['.$detailData['tbl_account_payable_main_idtbl_account_payable_main'].']';
				$this->db->where('idtbl_account_payable_main', $headerDataExistingId);
				//$this->db->or_where('br_grpcode', $data['br_grpcode']);
				
				$this->db->where('status', 1);//might throw error desc Transaction error
				
				// set update-time field
				$this->db->set('updatedatetime', 'NOW()', FALSE);
				
				$update = $this->db->update('tbl_account_payable_main', $headerData);
				
				//$importmsg = ($update)?'Style details updated':'Unable to update style';
				
				if(!($this->db->affected_rows()==1)){
					$flag = false;//echo 'n2.1';
				}
			}
		}
		
		$sub_k = 0;
		
		//$head_k = $detailData['tbl_account_payable_main_idtbl_account_payable_main'];
		
		if(($head_k>0)&&(!empty($detailData))){//echo 3;
			if(empty($detailDataExistingId)){//echo '3.1';
				// set qrytime field
				$this->db->set('insertdatetime', 'NOW()', FALSE);
				
				$additem = $this->db->insert('tbl_account_payable', $detailData);
				$sub_k = $additem?$this->db->insert_id():0;
			
				if($sub_k==0){
					$flag = false;//echo 'n3.1';
				}
			}else{//echo '4'.'x'.$detailDataExistingId;
				$this->db->set('updatedatetime', 'NOW()', FALSE);
				
				$this->db->where('idtbl_account_payable', $detailDataExistingId);
				
				$this->db->where('poststatus IS NULL');//might throw error desc Transaction error
				
				$update = $this->db->update('tbl_account_payable', $detailData);
				
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
}