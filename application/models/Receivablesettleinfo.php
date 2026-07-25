<?php
class Receivablesettleinfo extends CI_Model{
    public function Getreceivabletype(){
        $this->db->select('idtbl_receivable_type, receivabletype');
        $this->db->from('tbl_receivable_type');
        $this->db->where('status', 1);
        $this->db->where('idtbl_receivable_type !=', 5);

        $respond=$this->db->get();

        return $respond;
    }
    public function Getinvoiceaccocustomer(){
        $recordID=$this->input->post('recordID');

        $configdata = getconfigdata('receivable_search');

		$tablename = $configdata->row(0)->tbl_name;
		$column1   = $configdata->row(0)->col_name;
		$column2   = $configdata->row(1)->col_name;

        $has_table = !empty($tablename) ? 1 : 0;

        // $this->db->select('`tbl_sales_info`.`idtbl_sales_info`, `tbl_sales_info`.`invno`, `tbl_sales_info`.`amount`, IFNULL(SUM(`tbl_receivable_info`.`amount`), 0) AS `sumpay`, (`tbl_sales_info`.`amount`-IFNULL(SUM(`tbl_receivable_info`.`amount`), 0)) AS `balpay`, `tbl_sales_info`.`tbl_customer_idtbl_customer`, `tbl_customer`.`customer`');
        $this->db->select("`tbl_sales_info`.`idtbl_sales_info`, `tbl_sales_info`.`invno`, `tbl_sales_info`.`invamount`, IFNULL(SUM(CASE WHEN `tbl_receivable_info`.`status` = 1 THEN `tbl_receivable_info`.`amount` ELSE 0 END), 0) AS `sumpay`, (`tbl_sales_info`.`invamount`-IFNULL(SUM(CASE WHEN `tbl_receivable_info`.`status` = 1 THEN `tbl_receivable_info`.`amount` ELSE 0 END), 0)) AS `balpay`, `tbl_sales_info`.`tbl_customer_idtbl_customer`, IF($has_table = 0, '', $tablename.$column2) AS `customer`");
        $this->db->from('tbl_sales_info');
        $this->db->join('tbl_receivable_info', 'tbl_receivable_info.invoiceno = tbl_sales_info.invno', 'left');
        if(!empty($tablename)):
            $this->db->join("$tablename", "$tablename.$column1 = tbl_sales_info.tbl_customer_idtbl_customer", 'left');
        endif;
        $this->db->where('tbl_sales_info.status', 1);
        $this->db->where('tbl_sales_info.paystatus', 0);
        $this->db->where('tbl_sales_info.poststatus', 1);
        $this->db->where('tbl_sales_info.tbl_customer_idtbl_customer', $recordID);
        $this->db->group_by('`tbl_sales_info`.`idtbl_sales_info`');

        $respond=$this->db->get();
        // print_r($this->db->last_query());

        $html='';
        $i=1;
        foreach($respond->result() as $rowdatalist){
            $this->db->select('IFNULL(SUM(`amount`), 0) AS `returnsum`');
            $this->db->from('tbl_receivable_info');
            $this->db->where('status', 2);
            $this->db->where('invoiceno', $rowdatalist->invno);

            $respondreturn=$this->db->get();
            
            $netbalpay=$rowdatalist->balpay+$respondreturn->row(0)->returnsum;
            if($rowdatalist->invno=='INV251044'):echo $rowdatalist->balpay.'=='.$respondreturn->row(0)->returnsum; endif;
            
            if($netbalpay>0){
                $html.='
                <tr>
                    <td class="text-center" width="5%">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input checkclick" id="customCheck'.$i.'">
                            <label class="custom-control-label m-0" for="customCheck'.$i.'"></label>
                        </div>
                    </td>
                    <td class="d-none">'.$rowdatalist->tbl_customer_idtbl_customer.'</td>
                    <td>'.$rowdatalist->customer.'</td>
                    <td class="d-none">'.$rowdatalist->invno.'</td>
                    <td>'.$rowdatalist->invno.'</td>
                    <td class="text-right">'.number_format($rowdatalist->invamount, 2).'</td>
                    <td class="text-right invbalamount">'.number_format($netbalpay, 2).'</td>
                </tr>
                ';
                $i++;
            }
        }
        echo $html;
    }
    // public function Receivablesettleinsertupdate(){
    //     $userID=$_SESSION['userid'];
    //     $detailaccount=0;
    //     $chartaccount=0;

    //     $company=$this->input->post('company');
    //     $branch=$this->input->post('branch');
    //     $customer=$this->input->post('customer');
    //     // $receivabletype=$this->input->post('receivabletype');
    //     // $accounttype=$this->input->post('accounttype');
    //     // if(!empty($this->input->post('chequedate'))){$chequedate=$this->input->post('chequedate');}else{$chequedate='';}
    //     // if(!empty($this->input->post('chequeno'))){$chequeno=$this->input->post('chequeno');}else{$chequeno='';}
    //     // $chartofdetailaccount=$this->input->post('chartofdetailaccount');
    //     // $narration=$this->input->post('narration');
    //     $invoicepayamount=str_replace(',', '', $this->input->post('invoicepayamount'));
    //     $paidamount=str_replace(',', '', $this->input->post('paidamount'));
    //     $invoicedata=json_decode($this->input->post('tableData'));
    //     $paymentdata=json_decode($this->input->post('tableReceData'));
    //     print_r($invoicedata);
    //     print_r($paymentdata);
    //     die();
        
    //     $chequecashamount=$paidamount;

    //     $recordOption=$this->input->post('recordOption');
    //     if(!empty($this->input->post('recordID'))){$recordID=$this->input->post('recordID');}
        
    //     if($recordOption==1){
    //         $prefix=receiv_prefix($company, $branch);
    //         $masterdata=get_account_period($company, $branch);
    //         $batchno=tr_batch_num($prefix, $branch);
    //         $masterID=$masterdata->idtbl_master;
    //     }

    //     $updatedatetime=date('Y-m-d H:i:s');
    //     $today=date('Y-m-d');
    
    //     if($recordOption==1){
    //         if(!empty($batchno)){
    //             $this->db->trans_begin();

    //             $paymentnettotal=0;
    //             foreach($paymentdata as $rowpaymentdata):
    //                 if($rowpaymentdata->accounttype==1){$chartaccount=$rowpaymentdata->chartofaccount; $detailaccount=0;}
    //                 else if($rowpaymentdata->accounttype==2){$detailaccount=$rowpaymentdata->chartofaccount; $chartaccount=0;}

    //                 $data = array(
    //                     'recdate'=> $today, 
    //                     'batchno'=> $batchno, 
    //                     'payer'=> $customer, 
    //                     'amount'=> str_replace(',', '', $rowpaymentdata->amount), 
    //                     'narration'=> $rowpaymentdata->narration, 
    //                     'chequedate'=> $rowpaymentdata->chequedate, 
    //                     'chequeno'=> $rowpaymentdata->chequeno, 
    //                     'poststatus'=> '0', 
    //                     'status'=> '1', 
    //                     'insertdatetime'=> $updatedatetime, 
    //                     'tbl_user_idtbl_user'=> $userID,
    //                     'tbl_receivable_type_idtbl_receivable_type'=> $rowpaymentdata->receivabletypeid,
    //                     'tbl_company_idtbl_company'=> $company,
    //                     'tbl_company_branch_idtbl_company_branch'=> $branch,
    //                     'tbl_master_idtbl_master'=> $masterID,
    //                     'tbl_account_idtbl_account'=> $chartaccount,
    //                     'tbl_account_detail_idtbl_account_detail'=> $detailaccount
    //                 );

    //                 $this->db->insert('tbl_receivable', $data);

    //                 $receivableID=$this->db->insert_id();

    //                 foreach($invoicedata as $rowinvoicedata){
    //                     $narration=$rowinvoicedata->customer.' - '.$rowinvoicedata->invoiceno;
    //                     $invoicetotal=str_replace(',', '', $rowinvoicedata->amount);

    //                     if($chequecashamount>=$invoicetotal){
    //                         $invoicepayamount=$invoicetotal;
    //                         $chequecashamount=$chequecashamount-$invoicetotal;
    //                     }
    //                     else{
    //                         $invoicepayamount=$chequecashamount;
    //                         $chequecashamount=0;
    //                     }

    //                     $datasub = array(
    //                         'invoiceno'=> $rowinvoicedata->invid, 
    //                         'narration'=> $narration, 
    //                         'amount'=> $invoicepayamount, 
    //                         'status'=> '1', 
    //                         'insertdatetime'=> $updatedatetime, 
    //                         'tbl_user_idtbl_user'=> $userID,
    //                         'tbl_receivable_main_idtbl_receivable_main'=> $receivableID,
    //                     );

    //                     $this->db->insert('tbl_receivable_info', $datasub);
    //                 }

    //                 $paymentnettotal=$paymentnettotal+str_replace(',', '', $rowpaymentdata->amount);
    //             endforeach;

    //             $this->db->trans_complete();
    //             if ($this->db->trans_status() === TRUE) {
    //                 if($paymentnettotal==$paidamount){
    //                     $this->db->trans_commit();

    //                     $actionObj=new stdClass();
    //                     $actionObj->icon='fas fa-save';
    //                     $actionObj->title='';
    //                     $actionObj->message='Record Added Successfully';
    //                     $actionObj->url='';
    //                     $actionObj->target='_blank';
    //                     $actionObj->type='success';

    //                     $actionJSON=json_encode($actionObj);
                        
    //                     $obj=new stdClass();
    //                     $obj->status=1;
    //                     $obj->action=$actionJSON;

    //                     echo json_encode($obj);
    //                 }
    //                 else{
    //                     $this->db->trans_rollback();

    //                     $actionObj=new stdClass();
    //                     $actionObj->icon='fas fa-warning';
    //                     $actionObj->title='';
    //                     $actionObj->message='Record Error, Payment amount not equal payment type amount.';
    //                     $actionObj->url='';
    //                     $actionObj->target='_blank';
    //                     $actionObj->type='danger';

    //                     $actionJSON=json_encode($actionObj);
                        
    //                     $obj=new stdClass();
    //                     $obj->status=0;
    //                     $obj->action=$actionJSON;

    //                     echo json_encode($obj);
    //                 }                                       
    //             } else {
    //                 $this->db->trans_rollback();

    //                 $actionObj=new stdClass();
    //                 $actionObj->icon='fas fa-warning';
    //                 $actionObj->title='';
    //                 $actionObj->message='Record Error';
    //                 $actionObj->url='';
    //                 $actionObj->target='_blank';
    //                 $actionObj->type='danger';

    //                 $actionJSON=json_encode($actionObj);
                    
    //                 $obj=new stdClass();
    //                 $obj->status=0;
    //                 $obj->action=$actionJSON;

    //                 echo json_encode($obj);
    //             }
    //         }
    //         else{
    //             $actionObj=new stdClass();
    //             $actionObj->icon='fas fa-warning';
    //             $actionObj->title='';
    //             $actionObj->message='Record Error, Batch no defind by system';
    //             $actionObj->url='';
    //             $actionObj->target='_blank';
    //             $actionObj->type='danger';

    //             $actionJSON=json_encode($actionObj);
                
    //             $obj=new stdClass();
    //             $obj->status=0;
    //             $obj->action=$actionJSON;

    //             echo json_encode($obj);
    //         }
    //     }
    //     else{
    //         $this->db->trans_begin();

    //         $this->db->select('batchno, tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch, tbl_master_idtbl_master, poststatus');
    //         $this->db->from('tbl_receivable');
    //         $this->db->where('idtbl_receivable', $recordID);
    //         $this->db->where('status', 1);

    //         $respond=$this->db->get();
            
    //         $this->db->where('tbl_receivable_idtbl_receivable', $recordID);
    //         $this->db->delete('tbl_receivable_info');

    //         $data = array(
    //             'amount'=> $invoicepayamount, 
    //             'narration'=> $narration, 
    //             'chequedate'=> $chequedate, 
    //             'chequeno'=> $chequeno,  
    //             'editstatus' => '0',
    //             'status'=> '1', 
    //             'updateuser'=> $userID, 
    //             'updatedatetime'=> $updatedatetime,
    //             'tbl_receivable_type_idtbl_receivable_type'=> $receivabletype,
    //             'tbl_account_idtbl_account'=> $chartaccount,
    //             'tbl_account_detail_idtbl_account_detail'=> $detailaccount
    //         );
    
    //         $this->db->where('idtbl_receivable', $recordID);
    //         $this->db->update('tbl_receivable', $data);

    //         if($respond->row(0)->poststatus==0){
    //             foreach($invoicedata as $rowinvoicedata){
    //                 $narration=$rowinvoicedata['col_2'].' - '.$rowinvoicedata['col_4'];
    //                 $datasub = array(
    //                     'invoiceno'=> $rowinvoicedata['col_4'], 
    //                     'narration'=> $narration, 
    //                     'amount'=> $rowinvoicedata['col_5'], 
    //                     'status'=> '1', 
    //                     'insertdatetime'=> $updatedatetime, 
    //                     'tbl_user_idtbl_user'=> $userID,
    //                     'tbl_receivable_idtbl_receivable'=> $recordID,
    //                 );

    //                 $this->db->insert('tbl_receivable_info', $datasub);
    //             }

    //             $this->db->trans_complete();
    //             if ($this->db->trans_status() === TRUE) {
    //                 $this->db->trans_commit();
                    
    //                 $actionObj=new stdClass();
    //                 $actionObj->icon='fas fa-save';
    //                 $actionObj->title='';
    //                 $actionObj->message='Record Added Successfully';
    //                 $actionObj->url='';
    //                 $actionObj->target='_blank';
    //                 $actionObj->type='success';

    //                 $actionJSON=json_encode($actionObj);
                    
    //                 $obj=new stdClass();
    //                 $obj->status=1;
    //                 $obj->action=$actionJSON;

    //                 echo json_encode($obj);
    //             } else {
    //                 $this->db->trans_rollback();

    //                 $actionObj=new stdClass();
    //                 $actionObj->icon='fas fa-warning';
    //                 $actionObj->title='';
    //                 $actionObj->message='Record Error';
    //                 $actionObj->url='';
    //                 $actionObj->target='_blank';
    //                 $actionObj->type='danger';

    //                 $actionJSON=json_encode($actionObj);
                    
    //                 $obj=new stdClass();
    //                 $obj->status=0;
    //                 $obj->action=$actionJSON;

    //                 echo json_encode($obj);
    //             }
    //         }
    //         else{
    //             $this->db->trans_commit();

    //             $actionObj=new stdClass();
    //             $actionObj->icon='fas fa-warning';
    //             $actionObj->title='';
    //             $actionObj->message='Record Error. This record already posted.';
    //             $actionObj->url='';
    //             $actionObj->target='_blank';
    //             $actionObj->type='danger';

    //             $actionJSON=json_encode($actionObj);
                
    //             $obj=new stdClass();
    //             $obj->status=0;
    //             $obj->action=$actionJSON;

    //             echo json_encode($obj);
    //         }
    //     }
    // }
    // public function Receivablesettleinsertupdate() {
    //     try {
    //         $userID = $_SESSION['userid'];
    //         $detailaccount = 0;
    //         $chartaccount = 0;

    //         $company = $this->input->post('company');
    //         $branch = $this->input->post('branch');
    //         $recsettdate = $this->input->post('recsettdate');
    //         $customer = $this->input->post('customer');
    //         $invoicepayamount = str_replace(',', '', $this->input->post('invoicepayamount'));
    //         $paidamount = str_replace(',', '', $this->input->post('paidamount'));
    //         $unappliedamount = str_replace(',', '', $this->input->post('unappliedamount'));
    //         $invoicedata = json_decode($this->input->post('tableData'));
    //         $paymentdata = json_decode($this->input->post('tableReceData'));
    //         $unapplydata = json_decode($this->input->post('tableUnapplyData'));
            
    //         $chequecashamount = $paidamount;

    //         $recordOption = $this->input->post('recordOption');
    //         if (!empty($this->input->post('recordID'))) {
    //             $recordID = $this->input->post('recordID');
    //         }
            
    //         $this->db->trans_begin();

    //         if ($recordOption == 1) {
    //             $prefix = receiv_prefix($company, $branch);
    //             $masterdata = get_account_period($company, $branch);
    //             $batchno = tr_batch_num($prefix, $branch);
    //             $masterID = $masterdata->idtbl_master;

    //             $this->db->select('tbl_finacial_year.year');
    //             $this->db->from('tbl_master');
    //             $this->db->join('tbl_finacial_year', 'tbl_finacial_year.idtbl_finacial_year = tbl_master.tbl_finacial_year_idtbl_finacial_year', 'left');
    //             $this->db->where('tbl_master.idtbl_master', $masterID);

    //             $respond = $this->db->get();
    //             $financialYear = substr($respond->row(0)->year, -2);
                
    //             $receiptno = tr_batch_num('REC'.$financialYear, $branch);
    //             $receiptno = preg_replace('/^(.{5})00/', '$1', $receiptno);
    //         }

    //         $updatedatetime = date('Y-m-d H:i:s');
    //         $today = date('Y-m-d');

    //         if ($recordOption == 1) {
    //             if (!empty($batchno)) {
    //                 $paymentnettotal = 0;
                    
    //                 // Create a working copy of invoice data with remaining amounts
    //                 $invoicePayments = [];
    //                 foreach ($invoicedata as $invoice) {
    //                     $invoicePayments[] = [
    //                         'invoice' => $invoice,
    //                         'remaining' => floatval(str_replace(',', '', $invoice->amount))
    //                     ];
    //                 }
                    
    //                 $i = 1;
    //                 foreach ($paymentdata as $rowpaymentdata):
    //                     if ($rowpaymentdata->accounttype == 1) {
    //                         $chartaccount = $rowpaymentdata->chartofaccount;
    //                         $detailaccount = 0;
    //                     } else if ($rowpaymentdata->accounttype == 2) {
    //                         $detailaccount = $rowpaymentdata->chartofaccount;
    //                         $chartaccount = 0;
    //                     }

    //                     $data = array(
    //                         'recdate' => $recsettdate,
    //                         'receiptno' => $receiptno,
    //                         'batchno' => $batchno,
    //                         'payer' => $customer,
    //                         'amount' => str_replace(',', '', $rowpaymentdata->amount),
    //                         'narration' => $rowpaymentdata->narration,
    //                         'chequedate' => $rowpaymentdata->chequedate,
    //                         'chequeno' => $rowpaymentdata->chequeno,
    //                         'postdatedstatus' => $rowpaymentdata->postdatedstatus,
    //                         'poststatus' => '0',
    //                         'status' => '1',
    //                         'insertdatetime' => $updatedatetime,
    //                         'tbl_user_idtbl_user' => $userID,
    //                         'tbl_receivable_type_idtbl_receivable_type' => $rowpaymentdata->receivabletypeid,
    //                         'tbl_company_idtbl_company' => $company,
    //                         'tbl_company_branch_idtbl_company_branch' => $branch,
    //                         'tbl_master_idtbl_master' => $masterID,
    //                         'tbl_account_idtbl_account' => $chartaccount,
    //                         'tbl_account_detail_idtbl_account_detail' => $detailaccount
    //                     );

    //                     $this->db->insert('tbl_receivable', $data);
    //                     $receivableID = $this->db->insert_id();

    //                     // Process invoices for this payment
    //                     $paymentAmount = floatval(str_replace(',', '', $rowpaymentdata->amount));
                        
    //                     foreach ($invoicePayments as &$invoicePayment) {
    //                         // Skip if this invoice is already fully paid
    //                         if ($invoicePayment['remaining'] <= 0) {
    //                             continue;
    //                         }
                            
    //                         $balanceamount = 0;
    //                         $invoiceRemaining = $invoicePayment['remaining'];
    //                         $narration = $invoicePayment['invoice']->customer . ' - ' . $invoicePayment['invoice']->invoiceno;
                            
    //                         if ($paymentAmount > 0) {
    //                             if ($paymentAmount >= $invoiceRemaining) {
    //                                 // Full payment for this invoice
    //                                 $invoicepayamount = $invoiceRemaining;
    //                                 $paymentAmount = $paymentAmount - $invoiceRemaining;
    //                                 $invoicePayment['remaining'] = 0;
                                    
    //                                 // Update the original invoice amount for consistency
    //                                 $invoicePayment['invoice']->amount = '0.00';

    //                                 if(count($paymentdata) == $i && $paymentAmount > 0):
    //                                     $balanceamount = $paymentAmount;
    //                                 endif;
    //                             } else {
    //                                 // Partial payment
    //                                 $invoicepayamount = $paymentAmount;
    //                                 $invoicePayment['remaining'] = $invoiceRemaining - $paymentAmount;
                                    
    //                                 // Update the original invoice amount for consistency
    //                                 $invoicePayment['invoice']->amount = number_format($invoicePayment['remaining'], 2);
                                    
    //                                 $paymentAmount = 0;
    //                             }
                                
    //                             // Insert payment record for this invoice
    //                             $datasub = array(
    //                                 'invoiceno' => $invoicePayment['invoice']->invid,
    //                                 'narration' => $narration,
    //                                 'amount' => $invoicepayamount,
    //                                 'overpayment' => $balanceamount,
    //                                 'overpaysetoff' => '0',
    //                                 'status' => '1',
    //                                 'insertdatetime' => $updatedatetime,
    //                                 'tbl_user_idtbl_user' => $userID,
    //                                 'tbl_receivable_idtbl_receivable' => $receivableID,
    //                             );
                                
    //                             $this->db->insert('tbl_receivable_info', $datasub);

    //                             $receivabledetailID = $this->db->insert_id();
    //                         }
                            
    //                         // Break if payment amount is exhausted
    //                         if ($paymentAmount <= 0) {
    //                             break;
    //                         }

    //                     }
    //                     $i++;

    //                     $paymentnettotal = $paymentnettotal + str_replace(',', '', $rowpaymentdata->amount);
    //                 endforeach;

    //                 $paymentnettotal = $paymentnettotal + $unappliedamount;

    //                 if ($paymentnettotal != $paidamount) {
    //                     throw new Exception('Payment amount not equal payment paid nettotal.');
    //                 }

    //                 if ($this->db->trans_status() === FALSE) {
    //                     throw new Exception('Transaction failed');
    //                 }

    //                 // $this->db->trans_commit();

    //                 // $actionObj = new stdClass();
    //                 // $actionObj->icon = 'fas fa-save';
    //                 // $actionObj->title = '';
    //                 // $actionObj->message = 'Record Added Successfully';
    //                 // $actionObj->url = '';
    //                 // $actionObj->target = '_blank';
    //                 // $actionObj->type = 'success';

    //                 // $actionJSON = json_encode($actionObj);
                    
    //                 // $obj = new stdClass();
    //                 // $obj->status = 1;
    //                 // $obj->action = $actionJSON;

    //                 // echo json_encode($obj);
    //             } else {
    //                 throw new Exception("Batch no defined by system");                 
    //             }
    //         } 
    //     }
    //     catch (Exception $e) {
    //         $this->db->trans_rollback();
            
    //         error_log("Record Error: " . $e->getMessage());
            
    //         $actionObj = new stdClass();
    //         $actionObj->icon = 'fas fa-exclamation-triangle';
    //         $actionObj->title = '';
    //         $actionObj->message = 'Record Error: ' . $e->getMessage();
    //         $actionObj->url = '';
    //         $actionObj->target = '_blank';
    //         $actionObj->type = 'danger';

    //         $obj = new stdClass();
    //         $obj->status = 0;
    //         $obj->action = $actionJSON;

    //         echo json_encode($obj);
    //     }
    // }
    
    // public function Receivablesettleinsertupdate() {
    //     try {
    //         $userID = $_SESSION['userid'];
    //         $detailaccount = 0;
    //         $chartaccount  = 0;

    //         $company          = $this->input->post('company');
    //         $branch           = $this->input->post('branch');
    //         $recsettdate      = $this->input->post('recsettdate');
    //         $customer         = $this->input->post('customer');
    //         $invoicepayamount = str_replace(',', '', $this->input->post('invoicepayamount'));
    //         $paidamount       = str_replace(',', '', $this->input->post('paidamount'));
    //         $unappliedamount  = str_replace(',', '', $this->input->post('unappliedamount'));
    //         $creditnotetotal  = str_replace(',', '', $this->input->post('creditnotetotal'));
    //         $invoicedata      = json_decode($this->input->post('tableData'));
    //         $paymentdata      = json_decode($this->input->post('tableReceData'));
    //         $unapplydata      = json_decode($this->input->post('tableUnapplyData'));
    //         $creditnotedata   = json_decode($this->input->post('tableCreditNoteData')); 

    //         $recordOption = $this->input->post('recordOption');
    //         if (!empty($this->input->post('recordID'))) {
    //             $recordID = $this->input->post('recordID');
    //         }

    //         $this->db->trans_begin();

    //         if ($recordOption == 1) {
    //             $prefix     = receiv_prefix($company, $branch);
    //             $masterdata = get_account_period($company, $branch);
    //             $batchno    = tr_batch_num($prefix, $branch);
    //             $masterID   = $masterdata->idtbl_master;

    //             $this->db->select('tbl_finacial_year.year');
    //             $this->db->from('tbl_master');
    //             $this->db->join('tbl_finacial_year', 'tbl_finacial_year.idtbl_finacial_year = tbl_master.tbl_finacial_year_idtbl_finacial_year', 'left');
    //             $this->db->where('tbl_master.idtbl_master', $masterID);
    //             $respond       = $this->db->get();
    //             $financialYear = substr($respond->row(0)->year, -2);

    //             $receiptno = tr_batch_num('REC' . $financialYear, $branch);
    //             $receiptno = preg_replace('/^(.{5})00/', '$1', $receiptno);
    //         }

    //         $updatedatetime = date('Y-m-d H:i:s');

    //         if ($recordOption == 1) {
    //             if (empty($batchno)) {
    //                 throw new Exception("Batch no defined by system");
    //             }

    //             // STEP 1: First, insert all receivable records (payments)
    //             $receivableIDs = [];
    //             $paymentnettotal = 0;

    //             //Get Creditor Account
    //             $this->db->where('tbl_account_allocation.companybank', $company);
    //             $this->db->where('tbl_account_allocation.branchcompanybank', $branch);
    //             // $this->db->where('tbl_account.tbl_account_type_idtbl_account_type', 2);
    //             $this->db->where('tbl_account.specialcate', 35);
    //             $this->db->where('tbl_account.status', 1);
    //             $this->db->where('tbl_account_allocation.status', 1);
    //             $this->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
    //             $this->db->select('`tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`');
    //             $this->db->from('tbl_account');
    //             $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');
    //             $respondcreditor=$this->db->get();

    //             $this->db->select('tbl_account_detail_idtbl_account_detail');
    //             $this->db->from('tbl_account_detail_other');
    //             $this->db->where('tbl_company_idtbl_company', $company);
    //             $this->db->where('tbl_company_branch_idtbl_company_branch', $branch);
    //             $this->db->where('otheroptiontype', 2);
    //             $this->db->where('otheroption', $customer);
    //             $respondcreditacc = $this->db->get();
                
    //             if(!empty($paymentdata)){
    //                 foreach ($paymentdata as $rowpaymentdata) {
    //                     if ($rowpaymentdata->accounttype == 1) {
    //                         $chartaccount  = $rowpaymentdata->chartofaccount;
    //                         $detailaccount = 0;
    //                     } elseif ($rowpaymentdata->accounttype == 2) {
    //                         $detailaccount = $rowpaymentdata->chartofaccount;
    //                         $chartaccount  = 0;
    //                     }
                        
    //                     $paymentAmount = floatval(str_replace(',', '', $rowpaymentdata->amount));
                        
    //                     // Insert tbl_receivable
    //                     $data = [
    //                         'recdate'                                   => $recsettdate,
    //                         'receiptno'                                 => $receiptno,
    //                         'batchno'                                   => $batchno,
    //                         'payer'                                     => $customer,
    //                         'amount'                                    => $paymentAmount,
    //                         'narration'                                 => $rowpaymentdata->narration,
    //                         'chequedate'                                => $rowpaymentdata->chequedate,
    //                         'chequeno'                                  => $rowpaymentdata->chequeno,
    //                         'postdatedstatus'                           => $rowpaymentdata->postdatedstatus,
    //                         'poststatus'                                => '0',
    //                         'status'                                    => '1',
    //                         'insertdatetime'                            => $updatedatetime,
    //                         'tbl_user_idtbl_user'                       => $userID,
    //                         'tbl_receivable_type_idtbl_receivable_type' => $rowpaymentdata->receivabletypeid,
    //                         'tbl_company_idtbl_company'                 => $company,
    //                         'tbl_company_branch_idtbl_company_branch'   => $branch,
    //                         'tbl_master_idtbl_master'                   => $masterID,
    //                         'tbl_account_idtbl_account'                 => $chartaccount,
    //                         'tbl_account_detail_idtbl_account_detail'   => $detailaccount,
    //                     ];
    //                     $this->db->insert('tbl_receivable', $data);
    //                     $receivableID = $this->db->insert_id();

    //                     // Debit entry start
    //                     $dataentrylist[] = [
    //                         'tratype' => 'D',
    //                         'amount' => $paymentAmount,
    //                         'narration' => $rowpaymentdata->narration,
    //                         'chartaccount' => $chartaccount,
    //                         'detailaccount' => $detailaccount
    //                     ];
    //                     // Debit entry end

    //                     // Credit entry start                       
    //                     if(empty($respondcreditacc->result())):
    //                         if(empty($respondcreditor->result())){
    //                             throw new Exception("You don't have trade debtor account or debtor account");
    //                         }

    //                         $dataentrylist[] = [
    //                             'tratype' => 'C',
    //                             'amount' => $paymentAmount,
    //                             'narration' => $rowpaymentdata->narration,
    //                             'chartaccount' => $respondcreditor->row(0)->idtbl_account,
    //                             'detailaccount' => 0
    //                         ];
    //                     else:
    //                         $dataentrylist[] = [
    //                             'tratype' => 'C',
    //                             'amount' => $paymentAmount,
    //                             'narration' => $rowpaymentdata->narration,
    //                             'chartaccount' => 0,
    //                             'detailaccount' => $respondcreditacc->row(0)->tbl_account_detail_idtbl_account_detail
    //                         ];
    //                     endif;
    //                     // Credit entry end
                        
    //                     $receivableIDs[] = [
    //                         'id' => $receivableID,
    //                         'amount' => $paymentAmount,
    //                         'data' => $rowpaymentdata
    //                     ];
                        
    //                     $paymentnettotal += $paymentAmount;
    //                 }
    //             }
    //             else{
    //                 $paymentAmount = floatval(str_replace(',', '', $unappliedamount));

    //                 // Insert tbl_receivable
    //                 $data = [
    //                     'recdate'                                   => $recsettdate,
    //                     'receiptno'                                 => $receiptno,
    //                     'batchno'                                   => $batchno,
    //                     'payer'                                     => $customer,
    //                     'amount'                                    => $paymentAmount,
    //                     'narration'                                 => 'Unpaid amount from previous overpayment',
    //                     'postdatedstatus'                           => '0',
    //                     'poststatus'                                => '1',
    //                     'status'                                    => '1',
    //                     'insertdatetime'                            => $updatedatetime,
    //                     'tbl_user_idtbl_user'                       => $userID,
    //                     'tbl_receivable_type_idtbl_receivable_type' => '5', // Assuming 5 is the ID for "Unapplied Amount" type
    //                     'tbl_company_idtbl_company'                 => $company,
    //                     'tbl_company_branch_idtbl_company_branch'   => $branch,
    //                     'tbl_master_idtbl_master'                   => $masterID,
    //                     'tbl_account_idtbl_account'                 => $chartaccount,
    //                     'tbl_account_detail_idtbl_account_detail'   => $detailaccount,
    //                 ];
    //                 $this->db->insert('tbl_receivable', $data);
    //                 $receivableID = $this->db->insert_id();

    //                 // Debit entry start
    //                 $dataentrylist[] = [
    //                     'tratype' => 'D',
    //                     'amount' => $paymentAmount,
    //                     'narration' => 'Unpaid amount from previous overpayment',
    //                     'chartaccount' => $chartaccount,
    //                     'detailaccount' => $detailaccount
    //                 ];
    //                 // Debit entry end

    //                 // Credit entry start
    //                 if(empty($respondcreditacc->result())):
    //                     if(empty($respondcreditor->result())){
    //                         throw new Exception("You don't have trade debtor account or debtor account");
    //                     }

    //                     $dataentrylist[] = [
    //                         'tratype' => 'C',
    //                         'amount' => $paymentAmount,
    //                         'narration' => 'Unpaid amount from previous overpayment',
    //                         'chartaccount' => $respondcreditor->row(0)->idtbl_account,
    //                         'detailaccount' => 0
    //                     ];
    //                 else:
    //                     $dataentrylist[] = [
    //                         'tratype' => 'C',
    //                         'amount' => $paymentAmount,
    //                         'narration' => 'Unpaid amount from previous overpayment',
    //                         'chartaccount' => 0,
    //                         'detailaccount' => $respondcreditacc->row(0)->tbl_account_detail_idtbl_account_detail
    //                     ];
    //                 endif;
    //                 // Debit entry end
                    
    //                 $receivableIDs[] = [
    //                     'id' => $receivableID,
    //                     'amount' => $paymentAmount,
    //                     'data' => ''
    //                 ];
                    
    //                 $paymentnettotal += $paymentAmount;
    //             }

    //             foreach($dataentrylist as $rowdataentrylist){
    //                 $datalist = [
    //                     'transdate' => $recsettdate, 
    //                     'batchno' => $batchno, 
    //                     'tratype' => $rowdataentrylist['tratype'], 
    //                     'amount' => $rowdataentrylist['amount'], 
    //                     'narration' => $rowdataentrylist['narration'], 
    //                     'poststatus' => '0', 
    //                     'status' => '1', 
    //                     'insertdatetime' => $updatedatetime, 
    //                     'tbl_user_idtbl_user' => $userID, 
    //                     'tbl_company_idtbl_company' => $company, 
    //                     'tbl_company_branch_idtbl_company_branch' => $branch, 
    //                     'tbl_master_idtbl_master' => $masterID, 
    //                     'tbl_receivable_idtbl_receivable' => $receivableID, 
    //                     'tbl_account_idtbl_account' => $rowdataentrylist['chartaccount'], 
    //                     'tbl_account_detail_idtbl_account_detail' => $rowdataentrylist['detailaccount']
    //                 ];
                    
    //                 $this->db->insert('tbl_receivable_entry', $datalist);
    //             }

    //             // Build working invoice list with remaining balances
    //             $invoicePayments = [];
    //             foreach ($invoicedata as $invoice) {
    //                 $invoicePayments[] = [
    //                     'invoice'   => $invoice,
    //                     'remaining' => floatval(str_replace(',', '', $invoice->amount)),
    //                     'invoice_no' => $invoice->invoiceno,
    //                     'invoice_id' => $invoice->invid,
    //                     'paid_amount' => 0
    //                 ];
    //             }
                
    //             $totalUnappliedUsed = 0;
    //             $totalOverpaymentFromCurrent = 0;
                
    //             // STEP 2: Apply unapplied amounts from previous overpayments to current invoices
    //             // This creates receivable_info records linked to the FIRST receivable record
    //             // (or you can distribute across multiple receivables based on your logic)
    //             if (!empty($unapplydata) && !empty($receivableIDs)) {
    //                 $targetReceivableID = $receivableIDs[0]['id']; // Link to first payment record
                    
    //                 foreach ($unapplydata as $unapplied) {
    //                     $sourceInfoID  = intval($unapplied->receivableinfoid);
    //                     $unappliedPool = floatval(str_replace(',', '', $unapplied->unappliedamount));
                        
    //                     if ($unappliedPool <= 0 || $sourceInfoID <= 0) {
    //                         continue;
    //                     }
                        
    //                     // Fetch source overpayment row
    //                     $this->db->select('idtbl_receivable_info, overpayment, overpaysetoff');
    //                     $this->db->from('tbl_receivable_info');
    //                     $this->db->where('idtbl_receivable_info', $sourceInfoID);
    //                     $sourceRow = $this->db->get()->row();
                        
    //                     if (!$sourceRow) {
    //                         throw new Exception("Unapplied record ID {$sourceInfoID} not found.");
    //                     }
                        
    //                     $availableBalance = floatval($sourceRow->overpayment);
                        
    //                     if (round($unappliedPool, 2) > round($availableBalance, 2)) {
    //                         throw new Exception("Unapplied amount ({$unappliedPool}) exceeds available balance ({$availableBalance}) for record ID {$sourceInfoID}.");
    //                     }
                        
    //                     // Apply to invoices
    //                     $remainingPool = $unappliedPool;
    //                     $totalAppliedToInvoices = 0;
    //                     $lastInvoiceProcessed = null;
    //                     $lastApplyAmount = 0;
                        
    //                     foreach ($invoicePayments as $index => &$invoicePayment) {
    //                         if ($invoicePayment['remaining'] <= 0 || $remainingPool <= 0) {
    //                             continue;
    //                         }
                            
    //                         // Check if this is the last invoice that will receive this unapplied amount
    //                         $isLastInvoice = false;
    //                         $remainingInvoicesAfterThis = 0;
                            
    //                         for ($i = $index + 1; $i < count($invoicePayments); $i++) {
    //                             if ($invoicePayments[$i]['remaining'] > 0) {
    //                                 $remainingInvoicesAfterThis++;
    //                             }
    //                         }
                            
    //                         if ($remainingInvoicesAfterThis == 0) {
    //                             $isLastInvoice = true;
    //                         }
                            
    //                         $applyAmount = min($remainingPool, $invoicePayment['remaining']);
                            
    //                         if ($isLastInvoice && $remainingPool > $invoicePayment['remaining']) {
    //                             // This is the last invoice and we have more unapplied amount than invoice balance
    //                             // The excess will become a NEW overpayment on this invoice
    //                             $actualApplyAmount = $invoicePayment['remaining'];
    //                             $newOverpaymentAmount = $remainingPool - $actualApplyAmount;
                                
    //                             // Insert receivable_info for unapplied amount usage with overpayment
    //                             $this->db->insert('tbl_receivable_info', [
    //                                 'invoiceno'                       => $invoicePayment['invoice_id'],
    //                                 'narration'                       => "Unapplied amount used from previous overpayment",
    //                                 'amount'                          => $actualApplyAmount,
    //                                 'overpayment'                     => $newOverpaymentAmount,  // New overpayment created
    //                                 'overpaysetoff'                   => 0,
    //                                 'setoff_receivable_info_id'       => $sourceInfoID,
    //                                 'status'                          => '1',
    //                                 'insertdatetime'                  => $updatedatetime,
    //                                 'tbl_user_idtbl_user'             => $userID,
    //                                 'tbl_receivable_idtbl_receivable' => $targetReceivableID,
    //                             ]);
                                
    //                             $invoicePayment['remaining'] = 0;
    //                             $invoicePayment['paid_amount'] += $actualApplyAmount;
    //                             $remainingPool = 0;
    //                             $totalAppliedToInvoices += $actualApplyAmount;
    //                             $totalUnappliedUsed += $actualApplyAmount;
                                
    //                             // Track that we created a new overpayment
    //                             if ($newOverpaymentAmount > 0) {
    //                                 $totalOverpaymentFromCurrent += $newOverpaymentAmount;
    //                             }
                                
    //                         } else {
    //                             // Normal application without creating new overpayment
    //                             $this->db->insert('tbl_receivable_info', [
    //                                 'invoiceno'                       => $invoicePayment['invoice_id'],
    //                                 'narration'                       => "Unapplied amount used from previous overpayment",
    //                                 'amount'                          => $applyAmount,
    //                                 'overpayment'                     => 0,
    //                                 'overpaysetoff'                   => 0,
    //                                 'setoff_receivable_info_id'       => $sourceInfoID,
    //                                 'status'                          => '1',
    //                                 'insertdatetime'                  => $updatedatetime,
    //                                 'tbl_user_idtbl_user'             => $userID,
    //                                 'tbl_receivable_idtbl_receivable' => $targetReceivableID,
    //                             ]);
                                
    //                             $invoicePayment['remaining'] -= $applyAmount;
    //                             $invoicePayment['paid_amount'] += $applyAmount;
    //                             $remainingPool -= $applyAmount;
    //                             $totalAppliedToInvoices += $applyAmount;
    //                             $totalUnappliedUsed += $applyAmount;
    //                         }
                            
    //                         if ($remainingPool <= 0) {
    //                             break;
    //                         }
    //                     }
    //                     unset($invoicePayment);
                        
    //                     // Calculate new balance after applying to invoices
    //                     $amountConsumed = $unappliedPool - $remainingPool;
    //                     $newBalance = round($availableBalance - $amountConsumed, 2);
                        
    //                     // Update the source overpayment row
    //                     $updateData = [
    //                         'overpayment' => $newBalance,
    //                         'overpaysetoff' => ($newBalance <= 0) ? 1 : 0
    //                     ];
                        
    //                     $this->db->where('idtbl_receivable_info', $sourceInfoID);
    //                     $this->db->update('tbl_receivable_info', $updateData);
                        
    //                     // If there's remaining overpayment that wasn't used (partial usage),
    //                     // it will stay in the source record for future use
    //                     if ($newBalance > 0) {
    //                         error_log("Remaining overpayment of {$newBalance} for record ID {$sourceInfoID}");
    //                     }
    //                 }
    //             }
                
    //             // STEP 3: Apply current payments to invoices
    //             $receivableIndex = 0;
    //             $totalOverpaymentFromCurrent = 0;

    //             foreach ($receivableIDs as $receivable) {
    //                 $receivableID = $receivable['id'];
    //                 $paymentAmount = $receivable['amount'];
    //                 $remainingPayment = $paymentAmount;
                    
    //                 // Apply to invoices that still have remaining balance
    //                 $invoiceProcessed = false;
                    
    //                 foreach ($invoicePayments as $index => &$invoicePayment) {
    //                     if ($invoicePayment['remaining'] <= 0) {
    //                         continue;
    //                     }
                        
    //                     if ($remainingPayment <= 0) {
    //                         break;
    //                     }
                        
    //                     $invoiceRemaining = $invoicePayment['remaining'];
    //                     $narration = $invoicePayment['invoice_no'];
                        
    //                     // Check if this is the last invoice with balance
    //                     $isLastInvoice = true;
    //                     foreach ($invoicePayments as $checkIndex => $checkInvoice) {
    //                         if ($checkIndex > $index && $checkInvoice['remaining'] > 0) {
    //                             $isLastInvoice = false;
    //                             break;
    //                         }
    //                     }
                        
    //                     if ($remainingPayment >= $invoiceRemaining) {
    //                         // Can cover full invoice
    //                         if ($isLastInvoice) {
    //                             // Last invoice - any excess becomes overpayment
    //                             $overpaymentAmount = $remainingPayment - $invoiceRemaining;
                                
    //                             $receivableInfoData = [
    //                                 'invoiceno'                       => $invoicePayment['invoice_id'],
    //                                 'narration'                       => $narration,
    //                                 'amount'                          => $invoiceRemaining,
    //                                 'overpayment'                     => $overpaymentAmount,
    //                                 'overpaysetoff'                   => 0,
    //                                 'setoff_receivable_info_id'       => 0,
    //                                 'status'                          => '1',
    //                                 'insertdatetime'                  => $updatedatetime,
    //                                 'tbl_user_idtbl_user'             => $userID,
    //                                 'tbl_receivable_idtbl_receivable' => $receivableID,
    //                             ];
                                
    //                             $this->db->insert('tbl_receivable_info', $receivableInfoData);
                                
    //                             if ($overpaymentAmount > 0) {
    //                                 $totalOverpaymentFromCurrent += $overpaymentAmount;
    //                             }
                                
    //                             $invoicePayment['remaining'] = 0;
    //                             $invoicePayment['paid_amount'] += $invoiceRemaining;
    //                             $remainingPayment = 0;
                                
    //                         } else {
    //                             // Not last invoice, no overpayment
    //                             $this->db->insert('tbl_receivable_info', [
    //                                 'invoiceno'                       => $invoicePayment['invoice_id'],
    //                                 'narration'                       => $narration,
    //                                 'amount'                          => $invoiceRemaining,
    //                                 'overpayment'                     => 0,
    //                                 'overpaysetoff'                   => 0,
    //                                 'setoff_receivable_info_id'       => 0,
    //                                 'status'                          => '1',
    //                                 'insertdatetime'                  => $updatedatetime,
    //                                 'tbl_user_idtbl_user'             => $userID,
    //                                 'tbl_receivable_idtbl_receivable' => $receivableID,
    //                             ]);
                                
    //                             $invoicePayment['remaining'] = 0;
    //                             $invoicePayment['paid_amount'] += $invoiceRemaining;
    //                             $remainingPayment -= $invoiceRemaining;
    //                         }
                            
    //                     } else {
    //                         // Partial payment
    //                         $this->db->insert('tbl_receivable_info', [
    //                             'invoiceno'                       => $invoicePayment['invoice_id'],
    //                             'narration'                       => $narration,
    //                             'amount'                          => $remainingPayment,
    //                             'overpayment'                     => 0,
    //                             'overpaysetoff'                   => 0,
    //                             'setoff_receivable_info_id'       => 0,
    //                             'status'                          => '1',
    //                             'insertdatetime'                  => $updatedatetime,
    //                             'tbl_user_idtbl_user'             => $userID,
    //                             'tbl_receivable_idtbl_receivable' => $receivableID,
    //                         ]);
                            
    //                         $invoicePayment['remaining'] -= $remainingPayment;
    //                         $invoicePayment['paid_amount'] += $remainingPayment;
    //                         $remainingPayment = 0;
    //                     }
    //                 }
    //                 unset($invoicePayment);
                    
    //                 $receivableIndex++;
    //             }
                
    //             // STEP 4: Validation
    //             if(empty($paymentdata)){$totalUnappliedUsed=0;}
    //             $grandTotal = round($paymentnettotal + $totalUnappliedUsed, 2);
    //             $expectedTotal = round($paidamount, 2);
                
    //             if ($grandTotal != $expectedTotal) {
    //                 throw new Exception("Payment total mismatch. Expected: {$expectedTotal}, Got: {$grandTotal}");
    //             }
                
    //             // STEP 5: Check if any invoices are fully paid and update status if needed
    //             foreach ($invoicePayments as $invoicePayment) {
    //                 if ($invoicePayment['remaining'] <= 0) {
    //                     // Optional: Update invoice status in your invoice table
    //                     // $this->db->where('idtbl_invoice', $invoicePayment['invoice_id']);
    //                     // $this->db->update('tbl_invoice', ['payment_status' => 'PAID', 'paid_amount' => $invoicePayment['paid_amount']]);
    //                 }
    //             }
                
    //             if ($this->db->trans_status() === FALSE) {
    //                 throw new Exception('Transaction failed');
    //             }
                
    //             $this->db->trans_commit();
                
    //             $actionObj = new stdClass();
    //             $actionObj->icon = 'fas fa-save';
    //             $actionObj->title = '';
    //             $actionObj->message = 'Record Added Successfully';
    //             $actionObj->url = '';
    //             $actionObj->target = '_blank';
    //             $actionObj->type = 'success';
                
    //             $obj = new stdClass();
    //             $obj->status = 1;
    //             $obj->action = json_encode($actionObj);
                
    //             echo json_encode($obj);
    //         }
            
    //     } catch (Exception $e) {
    //         $this->db->trans_rollback();
    //         error_log("Record Error: " . $e->getMessage());
            
    //         $actionObj = new stdClass();
    //         $actionObj->icon = 'fas fa-exclamation-triangle';
    //         $actionObj->title = '';
    //         $actionObj->message = 'Record Error: ' . $e->getMessage();
    //         $actionObj->url = '';
    //         $actionObj->target = '_blank';
    //         $actionObj->type = 'danger';
            
    //         $obj = new stdClass();
    //         $obj->status = 0;
    //         $obj->action = json_encode($actionObj);
            
    //         echo json_encode($obj);
    //     }
    // }
    // public function Receivablesettleinsertupdate() {
    //     try {
    //         $userID        = $_SESSION['userid'];
    //         $detailaccount = 0;
    //         $chartaccount  = 0;

    //         $company          = $this->input->post('company');
    //         $branch           = $this->input->post('branch');
    //         $recsettdate      = $this->input->post('recsettdate');
    //         $customer         = $this->input->post('customer');
    //         $invoicepayamount = str_replace(',', '', $this->input->post('invoicepayamount'));
    //         $paidamount       = str_replace(',', '', $this->input->post('paidamount'));
    //         $unappliedamount  = str_replace(',', '', $this->input->post('unappliedamount'));
    //         $creditnotetotal  = str_replace(',', '', $this->input->post('creditnotetotal')); // NEW
    //         $invoicedata      = json_decode($this->input->post('tableData'));
    //         $paymentdata      = json_decode($this->input->post('tableReceData'));
    //         $unapplydata      = json_decode($this->input->post('tableUnapplyData'));
    //         $creditnotedata   = json_decode($this->input->post('tableCreditNoteData'));       // NEW

    //         $recordOption = $this->input->post('recordOption');
    //         if (!empty($this->input->post('recordID'))) {
    //             $recordID = $this->input->post('recordID');
    //         }

    //         $this->db->trans_begin();

    //         if ($recordOption == 1) {
    //             $masterdata = get_account_period_acco_date($company, $branch, $recsettdate);
    //             $prefix     = generate_prefix($company, $branch, $recsettdate, 'RE');
    //             $batchno    = tr_batch_num($prefix, $branch);
    //             $masterID   = $masterdata->idtbl_master;

    //             $this->db->select('tbl_finacial_year.year');
    //             $this->db->from('tbl_master');
    //             $this->db->join('tbl_finacial_year', 'tbl_finacial_year.idtbl_finacial_year = tbl_master.tbl_finacial_year_idtbl_finacial_year', 'left');
    //             $this->db->where('tbl_master.idtbl_master', $masterID);
    //             $respond       = $this->db->get();
    //             $financialYear = substr($respond->row(0)->year, -2);

    //             $receiptno = tr_batch_num('REC' . $financialYear, $branch);
    //             $receiptno = preg_replace('/^(.{5})00/', '$1', $receiptno);
    //         }

    //         $updatedatetime = date('Y-m-d H:i:s');

    //         if ($recordOption == 1) {
    //             if (empty($batchno)) {
    //                 throw new Exception("Batch no defined by system");
    //             }

    //             // ═══════════════════════════════════════════════════════
    //             // Get Creditor (Trade Debtor) Account
    //             // ═══════════════════════════════════════════════════════
    //             $this->db->where('tbl_account_allocation.companybank', $company);
    //             $this->db->where('tbl_account_allocation.branchcompanybank', $branch);
    //             $this->db->where('tbl_account.specialcate', 35);
    //             $this->db->where('tbl_account.status', 1);
    //             $this->db->where('tbl_account_allocation.status', 1);
    //             $this->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
    //             $this->db->select('`tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`');
    //             $this->db->from('tbl_account');
    //             $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');
    //             $respondcreditor = $this->db->get();

    //             $this->db->select('tbl_account_detail_idtbl_account_detail');
    //             $this->db->from('tbl_account_detail_other');
    //             $this->db->where('tbl_company_idtbl_company', $company);
    //             $this->db->where('tbl_company_branch_idtbl_company_branch', $branch);
    //             $this->db->where('otheroptiontype', 2);
    //             $this->db->where('otheroption', $customer);
    //             $respondcreditacc = $this->db->get();



    //             // ─────────────────────────────────────────────────────────
    //             // STEP 1: Insert receivable payment records
    //             // ─────────────────────────────────────────────────────────
    //             $receivableIDs   = [];
    //             $paymentnettotal = 0;
    //             $dataentrylist   = [];

    //             if (!empty($paymentdata)) {
    //                 foreach ($paymentdata as $rowpaymentdata) {
    //                     if ($rowpaymentdata->accounttype == 1) {
    //                         $chartaccount  = $rowpaymentdata->chartofaccount;
    //                         $detailaccount = 0;
    //                     } elseif ($rowpaymentdata->accounttype == 2) {
    //                         $detailaccount = $rowpaymentdata->chartofaccount;
    //                         $chartaccount  = 0;
    //                     }

    //                     $paymentAmount = floatval(str_replace(',', '', $rowpaymentdata->amount));

    //                     $data = [
    //                         'recdate'                                   => $recsettdate,
    //                         'receiptno'                                 => $receiptno,
    //                         'batchno'                                   => $batchno,
    //                         'payer'                                     => $customer,
    //                         'amount'                                    => $paymentAmount,
    //                         'narration'                                 => $rowpaymentdata->narration,
    //                         'chequedate'                                => $rowpaymentdata->chequedate,
    //                         'chequeno'                                  => $rowpaymentdata->chequeno,
    //                         'postdatedstatus'                           => $rowpaymentdata->postdatedstatus,
    //                         'poststatus'                                => '0',
    //                         'status'                                    => '1',
    //                         'insertdatetime'                            => $updatedatetime,
    //                         'tbl_user_idtbl_user'                       => $userID,
    //                         'tbl_receivable_type_idtbl_receivable_type' => $rowpaymentdata->receivabletypeid,
    //                         'tbl_company_idtbl_company'                 => $company,
    //                         'tbl_company_branch_idtbl_company_branch'   => $branch,
    //                         'tbl_master_idtbl_master'                   => $masterID,
    //                         'tbl_account_idtbl_account'                 => $chartaccount,
    //                         'tbl_account_detail_idtbl_account_detail'   => $detailaccount,
    //                     ];
    //                     $this->db->insert('tbl_receivable', $data);
    //                     $receivableID = $this->db->insert_id();

    //                     // Debit: Bank/Cash account
    //                     $dataentrylist[] = [
    //                         'tratype'       => 'D',
    //                         'amount'        => $paymentAmount,
    //                         'narration'     => $rowpaymentdata->narration,
    //                         'chartaccount'  => $chartaccount,
    //                         'detailaccount' => $detailaccount
    //                     ];

    //                     // Credit: Trade Debtor account
    //                     if (empty($respondcreditacc->result())) {
    //                         if (empty($respondcreditor->result())) {
    //                             throw new Exception("You don't have trade debtor account or debtor account");
    //                         }
    //                         $dataentrylist[] = [
    //                             'tratype'       => 'C',
    //                             'amount'        => $paymentAmount,
    //                             'narration'     => $rowpaymentdata->narration,
    //                             'chartaccount'  => $respondcreditor->row(0)->idtbl_account,
    //                             'detailaccount' => 0
    //                         ];
    //                     } else {
    //                         $dataentrylist[] = [
    //                             'tratype'       => 'C',
    //                             'amount'        => $paymentAmount,
    //                             'narration'     => $rowpaymentdata->narration,
    //                             'chartaccount'  => 0,
    //                             'detailaccount' => $respondcreditacc->row(0)->tbl_account_detail_idtbl_account_detail
    //                         ];
    //                     }

    //                     $receivableIDs[] = [
    //                         'id'     => $receivableID,
    //                         'amount' => $paymentAmount,
    //                         'data'   => $rowpaymentdata
    //                     ];

    //                     $paymentnettotal += $paymentAmount;
    //                 }
    //             } else {
    //                 // ── No cash/bank payment — settle via Unapplied / Credit Note / Both ──
    //                 //
    //                 // Scenario 1: Credit Note only      (creditnotedata exists, unappliedamount = 0)
    //                 // Scenario 2: Unapplied only         (unapplydata exists,    creditnotedata empty)
    //                 // Scenario 3: Credit Note + Unapplied (both exist)
    //                 //
    //                 // In all cases we still need ONE tbl_receivable record as a
    //                 // settlement header (poststatus=1, amount = combined total).
    //                 // tbl_receivable_entry: no Dr/Cr cash entry — mark only.
    //                 // ──────────────────────────────────────────────────────────────────────

    //                 $cnOnlyTotal       = floatval(str_replace(',', '', $creditnotetotal));   // gross total of all selected CNs
    //                 $unappliedOnlyTotal = floatval(str_replace(',', '', $unappliedamount));  // unapplied pool total

    //                 // Combined settlement amount (no real cash involved)
    //                 $paymentAmount = round($cnOnlyTotal + $unappliedOnlyTotal, 2);

    //                 // Determine narration based on what is being used
    //                 if ($cnOnlyTotal > 0 && $unappliedOnlyTotal > 0) {
    //                     $settleNarration = 'Settlement via Credit Note and Unapplied Amount';
    //                 } elseif ($cnOnlyTotal > 0) {
    //                     $settleNarration = 'Settlement via Credit Note';
    //                 } else {
    //                     $settleNarration = 'Settlement via Unapplied Amount';
    //                 }

    //                 if ($paymentAmount <= 0) {
    //                     throw new Exception("No valid settlement amount found (credit note or unapplied amount required).");
    //                 }

    //                 // Insert one tbl_receivable record as settlement header
    //                 $this->db->insert('tbl_receivable', [
    //                     'recdate'                                   => $recsettdate,
    //                     'receiptno'                                 => $receiptno,
    //                     'batchno'                                   => $batchno,
    //                     'payer'                                     => $customer,
    //                     'amount'                                    => $paymentAmount,
    //                     'narration'                                 => $settleNarration,
    //                     'postdatedstatus'                           => '0',
    //                     'poststatus'                                => '1',   // mark-only, no actual cash posting
    //                     'status'                                    => '1',
    //                     'insertdatetime'                            => $updatedatetime,
    //                     'tbl_user_idtbl_user'                       => $userID,
    //                     'tbl_receivable_type_idtbl_receivable_type' => '5',   // type 5 = non-cash settlement
    //                     'tbl_company_idtbl_company'                 => $company,
    //                     'tbl_company_branch_idtbl_company_branch'   => $branch,
    //                     'tbl_master_idtbl_master'                   => $masterID,
    //                     'tbl_account_idtbl_account'                 => 0,
    //                     'tbl_account_detail_idtbl_account_detail'   => 0,
    //                 ]);
    //                 $receivableID = $this->db->insert_id();

    //                 // No Dr/Cr accounting entries needed here:
    //                 //   - Credit note entries were already posted when CN was created
    //                 //   - Unapplied amount entries were already posted in original payment
    //                 // tbl_receivable_entry insert is skipped intentionally for this path.

    //                 $receivableIDs[] = ['id' => $receivableID, 'amount' => $paymentAmount, 'data' => ''];
    //                 $paymentnettotal += $paymentAmount;
    //             }

    //             // Insert accounting entries for cash/bank payments only
    //             // (else path — CN/unapplied only — has no entries, $dataentrylist is empty → loop skips)
    //             foreach ($dataentrylist as $rowdataentrylist) {
    //                 $datalist = [
    //                     'transdate'                                => $recsettdate,
    //                     'batchno'                                  => $batchno,
    //                     'tratype'                                  => $rowdataentrylist['tratype'],
    //                     'amount'                                   => $rowdataentrylist['amount'],
    //                     'narration'                                => $rowdataentrylist['narration'],
    //                     'poststatus'                               => '0',
    //                     'status'                                   => '1',
    //                     'insertdatetime'                           => $updatedatetime,
    //                     'tbl_user_idtbl_user'                      => $userID,
    //                     'tbl_company_idtbl_company'                => $company,
    //                     'tbl_company_branch_idtbl_company_branch'  => $branch,
    //                     'tbl_master_idtbl_master'                  => $masterID,
    //                     'tbl_receivable_idtbl_receivable'          => $receivableID,
    //                     'tbl_account_idtbl_account'                => $rowdataentrylist['chartaccount'],
    //                     'tbl_account_detail_idtbl_account_detail'  => $rowdataentrylist['detailaccount']
    //                 ];
    //                 $this->db->insert('tbl_receivable_entry', $datalist);
    //             }

    //             // Build working invoice list
    //             $invoicePayments = [];
    //             foreach ($invoicedata as $invoice) {
    //                 $invoicePayments[] = [
    //                     'invoice'     => $invoice,
    //                     'remaining'   => floatval(str_replace(',', '', $invoice->amount)),
    //                     'invoice_no'  => $invoice->invoiceno,
    //                     'invoice_id'  => $invoice->invid,
    //                     'paid_amount' => 0
    //                 ];
    //             }

    //             $totalUnappliedUsed = 0;
    //             $totalCreditNoteUsed = 0;  // NEW

    //             // ═══════════════════════════════════════════════════════════════════════
    //             // STEP 2: Apply credit notes to invoices (mark only — no accounting entries)
    //             //
    //             // $creditnotedata already has subtotal, vat, amount from the UI selection.
    //             // We use those values directly — no proportional calculation needed.
    //             //
    //             // Per credit note:
    //             //   1. Validate — exists, status=1, claimstatus != 1
    //             //   2. Apply gross amount to invoice balances → insert tbl_receivable_info
    //             //   3. Insert tbl_account_creditnote_settle
    //             //   4. Update tbl_account_creditnote settle totals + claimstatus
    //             // ═══════════════════════════════════════════════════════════════════════
    //             if (!empty($creditnotedata) && !empty($receivableIDs)) {
    //                 $targetReceivableID = $receivableIDs[0]['id'];

    //                 foreach ($creditnotedata as $rowcn) {
    //                     $creditNoteID  = intval($rowcn->creditnoteid);
    //                     $cnGrossAmount = floatval(str_replace(',', '', $rowcn->amount));
    //                     $cnNarration   = 'Credit Note: ' . $rowcn->creditnoteno;

    //                     if ($cnGrossAmount <= 0 || $creditNoteID <= 0) {
    //                         continue;
    //                     }

    //                     // ── 1. Validate ───────────────────────────────────────────────
    //                     $this->db->select('idtbl_account_creditnote, creditnoteno, nettotal,
    //                                     claimstatus, settleamount');
    //                     $this->db->from('tbl_account_creditnote');
    //                     $this->db->where('idtbl_account_creditnote', $creditNoteID);
    //                     $this->db->where('status', 1);
    //                     $cnRow = $this->db->get()->row();

    //                     if (!$cnRow) {
    //                         throw new Exception("Credit Note ID {$creditNoteID} not found or inactive.");
    //                     }
    //                     if ($cnRow->claimstatus == 1) {
    //                         throw new Exception("Credit Note '{$cnRow->creditnoteno}' is already fully claimed.");
    //                     }

    //                     // ── 2. Apply to invoices + insert tbl_receivable_info ─────────
    //                     $remainingCN = $cnGrossAmount;  // use directly — no min/proportion needed

    //                     foreach ($invoicePayments as $index => &$invoicePayment) {
    //                         if ($invoicePayment['remaining'] <= 0 || $remainingCN <= 0) {
    //                             continue;
    //                         }

    //                         $invoiceRemaining = $invoicePayment['remaining'];

    //                         // Is this the last invoice with a remaining balance?
    //                         $isLastInvoice = true;
    //                         for ($i = $index + 1; $i < count($invoicePayments); $i++) {
    //                             if ($invoicePayments[$i]['remaining'] > 0) {
    //                                 $isLastInvoice = false;
    //                                 break;
    //                             }
    //                         }

    //                         if ($remainingCN >= $invoiceRemaining) {
    //                             if ($isLastInvoice && $remainingCN > $invoiceRemaining) {
    //                                 // Last invoice, CN has excess → overpayment
    //                                 $this->db->insert('tbl_receivable_info', [
    //                                     'invoiceno'                       => $invoicePayment['invoice_id'],
    //                                     'narration'                       => $cnNarration,
    //                                     'amount'                          => $invoiceRemaining,
    //                                     'overpayment'                     => $remainingCN - $invoiceRemaining,
    //                                     'overpaysetoff'                   => 0,
    //                                     'setoff_receivable_info_id'       => 0,
    //                                     'creditnote_id'                   => $creditNoteID,
    //                                     'status'                          => '1',
    //                                     'insertdatetime'                  => $updatedatetime,
    //                                     'tbl_user_idtbl_user'             => $userID,
    //                                     'tbl_receivable_idtbl_receivable' => $targetReceivableID,
    //                                 ]);
    //                                 $invoicePayment['remaining']   = 0;
    //                                 $invoicePayment['paid_amount'] += $invoiceRemaining;
    //                                 $remainingCN = 0;

    //                             } else {
    //                                 // Full settle, no overpayment — or excess moves to next invoice
    //                                 $this->db->insert('tbl_receivable_info', [
    //                                     'invoiceno'                       => $invoicePayment['invoice_id'],
    //                                     'narration'                       => $cnNarration,
    //                                     'amount'                          => $invoiceRemaining,
    //                                     'overpayment'                     => 0,
    //                                     'overpaysetoff'                   => 0,
    //                                     'setoff_receivable_info_id'       => 0,
    //                                     'creditnote_id'                   => $creditNoteID,
    //                                     'status'                          => '1',
    //                                     'insertdatetime'                  => $updatedatetime,
    //                                     'tbl_user_idtbl_user'             => $userID,
    //                                     'tbl_receivable_idtbl_receivable' => $targetReceivableID,
    //                                 ]);
    //                                 $invoicePayment['remaining']   = 0;
    //                                 $invoicePayment['paid_amount'] += $invoiceRemaining;
    //                                 $remainingCN -= $invoiceRemaining;
    //                             }

    //                         } else {
    //                             // CN partially covers this invoice
    //                             $this->db->insert('tbl_receivable_info', [
    //                                 'invoiceno'                       => $invoicePayment['invoice_id'],
    //                                 'narration'                       => $cnNarration,
    //                                 'amount'                          => $remainingCN,
    //                                 'overpayment'                     => 0,
    //                                 'overpaysetoff'                   => 0,
    //                                 'setoff_receivable_info_id'       => 0,
    //                                 'creditnote_id'                   => $creditNoteID,
    //                                 'status'                          => '1',
    //                                 'insertdatetime'                  => $updatedatetime,
    //                                 'tbl_user_idtbl_user'             => $userID,
    //                                 'tbl_receivable_idtbl_receivable' => $targetReceivableID,
    //                             ]);
    //                             $invoicePayment['remaining']   -= $remainingCN;
    //                             $invoicePayment['paid_amount'] += $remainingCN;
    //                             $remainingCN = 0;
    //                         }

    //                         if ($remainingCN <= 0) break;
    //                     }
    //                     unset($invoicePayment);

    //                     $totalCreditNoteUsed += $cnGrossAmount;

    //                     // ── 3. Insert tbl_account_creditnote_settle ───────────────────
    //                     $this->db->insert('tbl_account_creditnote_settle', [
    //                         'date'                                            => $recsettdate,
    //                         'settlenettotal'                                  => $cnGrossAmount,
    //                         'status'                                          => '1',
    //                         'insertdatetime'                                  => $updatedatetime,
    //                         'updateuser'                                      => $userID,
    //                         'updatedatetime'                                  => $updatedatetime,
    //                         'tbl_user_idtbl_user'                             => $userID,
    //                         'tbl_account_creditnote_idtbl_account_creditnote' => $creditNoteID,
    //                         'tbl_receivable_idtbl_receivable'                 => $targetReceivableID,
    //                     ]);

    //                     // ── 4. Update tbl_account_creditnote ─────────────────────────
    //                     $newSettleGross = round(floatval($cnRow->settleamount) + $cnGrossAmount, 2);

    //                     // claimstatus=1 when fully settled
    //                     $newClaimStatus = ($newSettleGross >= floatval($cnRow->nettotal)) ? 1 : 0;

    //                     $updateCN = [
    //                         'settleamount'                    => $newSettleGross,
    //                         'claimstatus'                     => $newClaimStatus,
    //                         'updateuser'                      => $userID,
    //                         'updatedatetime'                  => $updatedatetime,
    //                         'tbl_receivable_idtbl_receivable' => $targetReceivableID,
    //                     ];

    //                     if ($newClaimStatus == 1) {
    //                         $updateCN['claimdate'] = $updatedatetime;
    //                     }

    //                     $this->db->where('idtbl_account_creditnote', $creditNoteID);
    //                     $this->db->update('tbl_account_creditnote', $updateCN);
    //                 }
    //             }
    //             // ═══════════════════════════════ END STEP 2 ══════════════════════════

    //             // ─────────────────────────────────────────────────────────────────────
    //             // STEP 3 (was STEP 2): Apply unapplied amounts from previous overpayments
    //             // ─────────────────────────────────────────────────────────────────────
    //             $totalOverpaymentFromCurrent = 0;

    //             if (!empty($unapplydata) && !empty($receivableIDs)) {
    //                 $targetReceivableID = $receivableIDs[0]['id'];

    //                 foreach ($unapplydata as $unapplied) {
    //                     $sourceInfoID  = intval($unapplied->receivableinfoid);
    //                     $unappliedPool = floatval(str_replace(',', '', $unapplied->unappliedamount));

    //                     if ($unappliedPool <= 0 || $sourceInfoID <= 0) continue;

    //                     $this->db->select('idtbl_receivable_info, overpayment, overpaysetoff');
    //                     $this->db->from('tbl_receivable_info');
    //                     $this->db->where('idtbl_receivable_info', $sourceInfoID);
    //                     $sourceRow = $this->db->get()->row();

    //                     if (!$sourceRow) {
    //                         throw new Exception("Unapplied record ID {$sourceInfoID} not found.");
    //                     }

    //                     $availableBalance = floatval($sourceRow->overpayment);

    //                     if (round($unappliedPool, 2) > round($availableBalance, 2)) {
    //                         throw new Exception("Unapplied amount ({$unappliedPool}) exceeds available balance ({$availableBalance}) for record ID {$sourceInfoID}.");
    //                     }

    //                     $remainingPool = $unappliedPool;

    //                     foreach ($invoicePayments as $index => &$invoicePayment) {
    //                         if ($invoicePayment['remaining'] <= 0 || $remainingPool <= 0) continue;

    //                         $isLastInvoice = true;
    //                         for ($i = $index + 1; $i < count($invoicePayments); $i++) {
    //                             if ($invoicePayments[$i]['remaining'] > 0) { $isLastInvoice = false; break; }
    //                         }

    //                         $applyAmount = min($remainingPool, $invoicePayment['remaining']);

    //                         if ($isLastInvoice && $remainingPool > $invoicePayment['remaining']) {
    //                             $actualApplyAmount    = $invoicePayment['remaining'];
    //                             $newOverpaymentAmount = $remainingPool - $actualApplyAmount;

    //                             $this->db->insert('tbl_receivable_info', [
    //                                 'invoiceno'                       => $invoicePayment['invoice_id'],
    //                                 'narration'                       => "Unapplied amount used from previous overpayment",
    //                                 'amount'                          => $actualApplyAmount,
    //                                 'overpayment'                     => $newOverpaymentAmount,
    //                                 'overpaysetoff'                   => 0,
    //                                 'setoff_receivable_info_id'       => $sourceInfoID,
    //                                 'creditnote_id'                   => 0,
    //                                 'status'                          => '1',
    //                                 'insertdatetime'                  => $updatedatetime,
    //                                 'tbl_user_idtbl_user'             => $userID,
    //                                 'tbl_receivable_idtbl_receivable' => $targetReceivableID,
    //                             ]);

    //                             $invoicePayment['remaining'] = 0;
    //                             $invoicePayment['paid_amount'] += $actualApplyAmount;
    //                             $remainingPool = 0;
    //                             $totalUnappliedUsed += $actualApplyAmount;
    //                             if ($newOverpaymentAmount > 0) $totalOverpaymentFromCurrent += $newOverpaymentAmount;

    //                         } else {
    //                             $this->db->insert('tbl_receivable_info', [
    //                                 'invoiceno'                       => $invoicePayment['invoice_id'],
    //                                 'narration'                       => "Unapplied amount used from previous overpayment",
    //                                 'amount'                          => $applyAmount,
    //                                 'overpayment'                     => 0,
    //                                 'overpaysetoff'                   => 0,
    //                                 'setoff_receivable_info_id'       => $sourceInfoID,
    //                                 'creditnote_id'                   => 0,
    //                                 'status'                          => '1',
    //                                 'insertdatetime'                  => $updatedatetime,
    //                                 'tbl_user_idtbl_user'             => $userID,
    //                                 'tbl_receivable_idtbl_receivable' => $targetReceivableID,
    //                             ]);

    //                             $invoicePayment['remaining'] -= $applyAmount;
    //                             $invoicePayment['paid_amount'] += $applyAmount;
    //                             $remainingPool -= $applyAmount;
    //                             $totalUnappliedUsed += $applyAmount;
    //                         }

    //                         if ($remainingPool <= 0) break;
    //                     }
    //                     unset($invoicePayment);

    //                     $amountConsumed = $unappliedPool - $remainingPool;
    //                     $newBalance     = round($availableBalance - $amountConsumed, 2);

    //                     $this->db->where('idtbl_receivable_info', $sourceInfoID);
    //                     $this->db->update('tbl_receivable_info', [
    //                         'overpayment'  => $newBalance,
    //                         'overpaysetoff' => ($newBalance <= 0) ? 1 : 0
    //                     ]);
    //                 }
    //             }

    //             // ─────────────────────────────────────────────────────────────────────
    //             // STEP 4 (was STEP 3): Apply current cash/bank payments to invoices
    //             // ─────────────────────────────────────────────────────────────────────
    //             if(!empty($paymentdata) && !empty($receivableIDs)) {
    //                 foreach ($receivableIDs as $receivable) {
    //                     $receivableID    = $receivable['id'];
    //                     $paymentAmount   = $receivable['amount'];
    //                     $remainingPayment = $paymentAmount;

    //                     foreach ($invoicePayments as $index => &$invoicePayment) {
    //                         if ($invoicePayment['remaining'] <= 0 || $remainingPayment <= 0) continue;

    //                         $invoiceRemaining = $invoicePayment['remaining'];
    //                         $narration        = $invoicePayment['invoice_no'];

    //                         $isLastInvoice = true;
    //                         foreach ($invoicePayments as $checkIndex => $checkInvoice) {
    //                             if ($checkIndex > $index && $checkInvoice['remaining'] > 0) { $isLastInvoice = false; break; }
    //                         }

    //                         if ($remainingPayment >= $invoiceRemaining) {
    //                             if ($isLastInvoice) {
    //                                 $overpaymentAmount = $remainingPayment - $invoiceRemaining;
    //                                 $this->db->insert('tbl_receivable_info', [
    //                                     'invoiceno'                       => $invoicePayment['invoice_id'],
    //                                     'narration'                       => $narration,
    //                                     'amount'                          => $invoiceRemaining,
    //                                     'overpayment'                     => $overpaymentAmount,
    //                                     'overpaysetoff'                   => 0,
    //                                     'setoff_receivable_info_id'       => 0,
    //                                     'creditnote_id'                   => 0,
    //                                     'status'                          => '1',
    //                                     'insertdatetime'                  => $updatedatetime,
    //                                     'tbl_user_idtbl_user'             => $userID,
    //                                     'tbl_receivable_idtbl_receivable' => $receivableID,
    //                                 ]);
    //                                 if ($overpaymentAmount > 0) $totalOverpaymentFromCurrent += $overpaymentAmount;
    //                                 $invoicePayment['remaining'] = 0;
    //                                 $invoicePayment['paid_amount'] += $invoiceRemaining;
    //                                 $remainingPayment = 0;

    //                             } else {
    //                                 $this->db->insert('tbl_receivable_info', [
    //                                     'invoiceno'                       => $invoicePayment['invoice_id'],
    //                                     'narration'                       => $narration,
    //                                     'amount'                          => $invoiceRemaining,
    //                                     'overpayment'                     => 0,
    //                                     'overpaysetoff'                   => 0,
    //                                     'setoff_receivable_info_id'       => 0,
    //                                     'creditnote_id'                   => 0,
    //                                     'status'                          => '1',
    //                                     'insertdatetime'                  => $updatedatetime,
    //                                     'tbl_user_idtbl_user'             => $userID,
    //                                     'tbl_receivable_idtbl_receivable' => $receivableID,
    //                                 ]);
    //                                 $invoicePayment['remaining'] = 0;
    //                                 $invoicePayment['paid_amount'] += $invoiceRemaining;
    //                                 $remainingPayment -= $invoiceRemaining;
    //                             }

    //                         } else {
    //                             $this->db->insert('tbl_receivable_info', [
    //                                 'invoiceno'                       => $invoicePayment['invoice_id'],
    //                                 'narration'                       => $narration,
    //                                 'amount'                          => $remainingPayment,
    //                                 'overpayment'                     => 0,
    //                                 'overpaysetoff'                   => 0,
    //                                 'setoff_receivable_info_id'       => 0,
    //                                 'creditnote_id'                   => 0,
    //                                 'status'                          => '1',
    //                                 'insertdatetime'                  => $updatedatetime,
    //                                 'tbl_user_idtbl_user'             => $userID,
    //                                 'tbl_receivable_idtbl_receivable' => $receivableID,
    //                             ]);
    //                             $invoicePayment['remaining'] -= $remainingPayment;
    //                             $invoicePayment['paid_amount'] += $remainingPayment;
    //                             $remainingPayment = 0;
    //                         }
    //                     }
    //                     unset($invoicePayment);
    //                 }
    //             }

    //             // ─────────────────────────────────────────────────────────────────────
    //             // STEP 5: Validation
    //             //
    //             // Path A: Cash/bank payment (paymentdata exists)
    //             //   grandTotal = paymentnettotal + totalUnappliedUsed + totalCreditNoteUsed
    //             //   expectedTotal = paidamount + creditnotetotal
    //             //
    //             // Path B: CN only / Unapplied only / CN+Unapplied (paymentdata empty)
    //             //   paymentnettotal   = combined header amount (cnOnlyTotal + unappliedOnlyTotal)
    //             //   totalUnappliedUsed = accumulated in STEP 3 loop
    //             //   totalCreditNoteUsed = accumulated in STEP 2 loop
    //             //   expectedTotal     = paidamount(0) + creditnotetotal + unappliedamount
    //             // ─────────────────────────────────────────────────────────────────────
    //             if (!empty($paymentdata)) {
    //                 // Path A — cash payment: unapplied/CN amounts tracked separately
    //                 $grandTotal    = round($paymentnettotal + $totalUnappliedUsed + $totalCreditNoteUsed, 2);
    //                 $expectedTotal = round(floatval($paidamount) + floatval($creditnotetotal), 2);
    //             } else {
    //                 // Path B — no cash: header amount already equals cn + unapplied combined
    //                 // totalUnappliedUsed and totalCreditNoteUsed are tracked in their own steps,
    //                 // so we compare against declared totals directly
    //                 $grandTotal    = round($totalCreditNoteUsed + $totalUnappliedUsed, 2);
    //                 $expectedTotal = round(floatval($creditnotetotal) + floatval($unappliedamount), 2);
    //             }

    //             if ($grandTotal != $expectedTotal) {
    //                 throw new Exception("Payment total mismatch. Expected: {$expectedTotal}, Got: {$grandTotal}");
    //             }

    //             if ($this->db->trans_status() === FALSE) {
    //                 throw new Exception('Transaction failed');
    //             }

    //             $this->db->trans_commit();

    //             $actionObj          = new stdClass();
    //             $actionObj->icon    = 'fas fa-save';
    //             $actionObj->title   = '';
    //             $actionObj->message = 'Record Added Successfully';
    //             $actionObj->url     = '';
    //             $actionObj->target  = '_blank';
    //             $actionObj->type    = 'success';

    //             $obj         = new stdClass();
    //             $obj->status = 1;
    //             $obj->action = json_encode($actionObj);

    //             echo json_encode($obj);

    //         } else {
    //             // recordOption != 1 — no operation defined, rollback and return error
    //             $this->db->trans_rollback();

    //             $actionObj          = new stdClass();
    //             $actionObj->icon    = 'fas fa-exclamation-triangle';
    //             $actionObj->title   = '';
    //             $actionObj->message = 'Invalid record option.';
    //             $actionObj->url     = '';
    //             $actionObj->target  = '_blank';
    //             $actionObj->type    = 'danger';

    //             $obj         = new stdClass();
    //             $obj->status = 0;
    //             $obj->action = json_encode($actionObj);

    //             echo json_encode($obj);
    //         }

    //     } catch (Exception $e) {
    //         $this->db->trans_rollback();
    //         error_log("Record Error: " . $e->getMessage());

    //         $actionObj          = new stdClass();
    //         $actionObj->icon    = 'fas fa-exclamation-triangle';
    //         $actionObj->title   = '';
    //         $actionObj->message = 'Record Error: ' . $e->getMessage();
    //         $actionObj->url     = '';
    //         $actionObj->target  = '_blank';
    //         $actionObj->type    = 'danger';

    //         $obj         = new stdClass();
    //         $obj->status = 0;
    //         $obj->action = json_encode($actionObj);

    //         echo json_encode($obj);
    //     }
    // }

    public function Receivablesettleinsertupdate() {
        try {
            $userID        = $_SESSION['userid'];
            $detailaccount = 0;
            $chartaccount  = 0;

            $company          = $this->input->post('company');
            $branch           = $this->input->post('branch');
            $recsettdate      = $this->input->post('recsettdate');
            $customer         = $this->input->post('customer');
            $invoicepayamount = str_replace(',', '', $this->input->post('invoicepayamount'));
            $paidamount       = str_replace(',', '', $this->input->post('paidamount'));
            $unappliedamount  = str_replace(',', '', $this->input->post('unappliedamount'));
            $creditnotetotal  = str_replace(',', '', $this->input->post('creditnotetotal'));
            $invoicedata      = json_decode($this->input->post('tableData'));
            $paymentdata      = json_decode($this->input->post('tableReceData'));
            $unapplydata      = json_decode($this->input->post('tableUnapplyData'));
            $creditnotedata   = json_decode($this->input->post('tableCreditNoteData'));
            $recordOption     = $this->input->post('recordOption');
            $recordID         = !empty($this->input->post('recordID')) ? $this->input->post('recordID') : '';

            $updatedatetime = date('Y-m-d H:i:s');

            if ($recordOption != 1) {
                throw new Exception('Invalid record option');
            }

            // ── Resolve master, batch, receipt no ────────────────────────────────
            $masterdata = get_account_period_acco_date($company, $branch, $recsettdate);

            if (empty($masterdata) || empty($masterdata->idtbl_master)) {
                throw new Exception('Record Error, Account period not found for the given date');
            }

            $prefix   = generate_prefix($company, $branch, $recsettdate, 'RE');
            $batchno  = tr_batch_num($prefix, $branch);
            $masterID = $masterdata->idtbl_master ? $masterdata->idtbl_master : '';

            if (empty($batchno)) {
                throw new Exception('Batch no could not be defined by system');
            }

            $this->db->select('tbl_finacial_year.year');
            $this->db->from('tbl_master');
            $this->db->join('tbl_finacial_year', 'tbl_finacial_year.idtbl_finacial_year = tbl_master.tbl_finacial_year_idtbl_finacial_year', 'left');
            $this->db->where('tbl_master.idtbl_master', $masterID);
            $respondyear = $this->db->get();

            if (!$respondyear || $respondyear->num_rows() == 0) {
                throw new Exception('Record Error, Financial year not found');
            }

            $financialYear = substr($respondyear->row(0)->year, -2);
            $receiptno     = tr_batch_num('REC' . $financialYear, $branch);
            $receiptno     = preg_replace('/^(.{5})00/', '$1', $receiptno);

            $this->db->trans_begin();

            // ── Get Trade Debtor Account ──────────────────────────────────────────
            $this->db->where('tbl_account_allocation.companybank', $company);
            $this->db->where('tbl_account_allocation.branchcompanybank', $branch);
            $this->db->where('tbl_account.specialcate', 35);
            $this->db->where('tbl_account.status', 1);
            $this->db->where('tbl_account_allocation.status', 1);
            $this->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
            $this->db->select('`tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`');
            $this->db->from('tbl_account');
            $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');
            $respondcreditor = $this->db->get();

            $this->db->select('tbl_account_detail_idtbl_account_detail');
            $this->db->from('tbl_account_detail_other');
            $this->db->where('tbl_company_idtbl_company', $company);
            $this->db->where('tbl_company_branch_idtbl_company_branch', $branch);
            $this->db->where('otheroptiontype', 2);
            $this->db->where('otheroption', $customer);
            $respondcreditacc = $this->db->get();

            // ─────────────────────────────────────────────────────────────────────
            // STEP 1: Insert receivable payment records
            // ─────────────────────────────────────────────────────────────────────
            $receivableIDs   = [];
            $paymentnettotal = 0;
            $dataentrylist   = [];

            if (!empty($paymentdata)) {
                foreach ($paymentdata as $rowpaymentdata) {
                    if ($rowpaymentdata->accounttype == 1) {
                        $chartaccount  = $rowpaymentdata->chartofaccount;
                        $detailaccount = 0;
                    } elseif ($rowpaymentdata->accounttype == 2) {
                        $detailaccount = $rowpaymentdata->chartofaccount;
                        $chartaccount  = 0;
                    }

                    $paymentAmount = floatval(str_replace(',', '', $rowpaymentdata->amount));

                    $data = [
                        'recdate'                                   => $recsettdate,
                        'receiptno'                                 => $receiptno,
                        'batchno'                                   => $batchno,
                        'payer'                                     => $customer,
                        'amount'                                    => $paymentAmount,
                        'narration'                                 => $rowpaymentdata->narration,
                        'chequedate'                                => $rowpaymentdata->chequedate,
                        'chequeno'                                  => $rowpaymentdata->chequeno,
                        'postdatedstatus'                           => $rowpaymentdata->postdatedstatus,
                        'poststatus'                                => '0',
                        'status'                                    => '1',
                        'insertdatetime'                            => $updatedatetime,
                        'tbl_user_idtbl_user'                       => $userID,
                        'tbl_receivable_type_idtbl_receivable_type' => $rowpaymentdata->receivabletypeid,
                        'tbl_company_idtbl_company'                 => $company,
                        'tbl_company_branch_idtbl_company_branch'   => $branch,
                        'tbl_master_idtbl_master'                   => $masterID,
                        'tbl_account_idtbl_account'                 => $chartaccount,
                        'tbl_account_detail_idtbl_account_detail'   => $detailaccount,
                    ];

                    $this->db->insert('tbl_receivable', $data);
                    $receivableID = $this->db->insert_id();

                    if (!$receivableID) {
                        throw new Exception('Failed to insert receivable record');
                    }

                    // Debit: Bank/Cash account
                    $dataentrylist[] = [
                        'tratype'       => 'D',
                        'amount'        => $paymentAmount,
                        'narration'     => $rowpaymentdata->narration,
                        'chartaccount'  => $chartaccount,
                        'detailaccount' => $detailaccount
                    ];

                    // Credit: Trade Debtor account
                    if (empty($respondcreditacc->result())) {
                        if (empty($respondcreditor->result())) {
                            throw new Exception("You don't have trade debtor account or debtor account");
                        }
                        $dataentrylist[] = [
                            'tratype'       => 'C',
                            'amount'        => $paymentAmount,
                            'narration'     => $rowpaymentdata->narration,
                            'chartaccount'  => $respondcreditor->row(0)->idtbl_account,
                            'detailaccount' => 0
                        ];
                    } else {
                        $dataentrylist[] = [
                            'tratype'       => 'C',
                            'amount'        => $paymentAmount,
                            'narration'     => $rowpaymentdata->narration,
                            'chartaccount'  => 0,
                            'detailaccount' => $respondcreditacc->row(0)->tbl_account_detail_idtbl_account_detail
                        ];
                    }

                    $receivableIDs[] = [
                        'id'     => $receivableID,
                        'amount' => $paymentAmount,
                        'data'   => $rowpaymentdata
                    ];

                    $paymentnettotal += $paymentAmount;
                }

            } else {
                // ── No cash/bank payment — settle via Unapplied / Credit Note / Both ──
                $cnOnlyTotal        = floatval(str_replace(',', '', $creditnotetotal));
                $unappliedOnlyTotal = floatval(str_replace(',', '', $unappliedamount));
                $paymentAmount      = round($cnOnlyTotal + $unappliedOnlyTotal, 2);

                if ($cnOnlyTotal > 0 && $unappliedOnlyTotal > 0) {
                    $settleNarration = 'Settlement via Credit Note and Unapplied Amount';
                } elseif ($cnOnlyTotal > 0) {
                    $settleNarration = 'Settlement via Credit Note';
                } else {
                    $settleNarration = 'Settlement via Unapplied Amount';
                }

                if ($paymentAmount <= 0) {
                    throw new Exception('No valid settlement amount found (credit note or unapplied amount required).');
                }

                $this->db->insert('tbl_receivable', [
                    'recdate'                                   => $recsettdate,
                    'receiptno'                                 => $receiptno,
                    'batchno'                                   => $batchno,
                    'payer'                                     => $customer,
                    'amount'                                    => $paymentAmount,
                    'narration'                                 => $settleNarration,
                    'postdatedstatus'                           => '0',
                    'poststatus'                                => '1',
                    'status'                                    => '1',
                    'insertdatetime'                            => $updatedatetime,
                    'tbl_user_idtbl_user'                       => $userID,
                    'tbl_receivable_type_idtbl_receivable_type' => '5',
                    'tbl_company_idtbl_company'                 => $company,
                    'tbl_company_branch_idtbl_company_branch'   => $branch,
                    'tbl_master_idtbl_master'                   => $masterID,
                    'tbl_account_idtbl_account'                 => 0,
                    'tbl_account_detail_idtbl_account_detail'   => 0,
                ]);

                $receivableID = $this->db->insert_id();

                if (!$receivableID) {
                    throw new Exception('Failed to insert receivable settlement header record');
                }

                $receivableIDs[] = ['id' => $receivableID, 'amount' => $paymentAmount, 'data' => ''];
                $paymentnettotal += $paymentAmount;
            }

            // // ── Insert accounting entry lines ─────────────────────────────────────
            // foreach ($dataentrylist as $rowdataentrylist) {
            //     $datalist = [
            //         'transdate'                               => $recsettdate,
            //         'batchno'                                 => $batchno,
            //         'tratype'                                 => $rowdataentrylist['tratype'],
            //         'amount'                                  => $rowdataentrylist['amount'],
            //         'narration'                               => $rowdataentrylist['narration'],
            //         'poststatus'                              => '0',
            //         'status'                                  => '1',
            //         'insertdatetime'                          => $updatedatetime,
            //         'tbl_user_idtbl_user'                     => $userID,
            //         'tbl_company_idtbl_company'               => $company,
            //         'tbl_company_branch_idtbl_company_branch' => $branch,
            //         'tbl_master_idtbl_master'                 => $masterID,
            //         'tbl_receivable_idtbl_receivable'         => $receivableID,
            //         'tbl_account_idtbl_account'               => $rowdataentrylist['chartaccount'],
            //         'tbl_account_detail_idtbl_account_detail' => $rowdataentrylist['detailaccount']
            //     ];
            //     $this->db->insert('tbl_receivable_entry', $datalist);
            // }

            // ── Insert accounting entry lines ─────────────────────────────────────
            // dataentrylist has pairs: [Debit, Credit] per receivable (2 entries per payment)
            // receivableIDs has one entry per payment — match them by index pair

            $entryIndex = 0;
            foreach ($receivableIDs as $receivable) {
                $currentReceivableID = $receivable['id'];

                // Each payment generates exactly 2 entries (Debit + Credit)
                for ($i = 0; $i < 2; $i++) {
                    if (!isset($dataentrylist[$entryIndex])) break;

                    $rowdataentrylist = $dataentrylist[$entryIndex];

                    $datalist = [
                        'transdate'                               => $recsettdate,
                        'batchno'                                 => $batchno,
                        'tratype'                                 => $rowdataentrylist['tratype'],
                        'amount'                                  => $rowdataentrylist['amount'],
                        'narration'                               => $rowdataentrylist['narration'],
                        'poststatus'                              => '0',
                        'status'                                  => '1',
                        'insertdatetime'                          => $updatedatetime,
                        'tbl_user_idtbl_user'                     => $userID,
                        'tbl_company_idtbl_company'               => $company,
                        'tbl_company_branch_idtbl_company_branch' => $branch,
                        'tbl_master_idtbl_master'                 => $masterID,
                        'tbl_receivable_idtbl_receivable'         => $currentReceivableID,
                        'tbl_account_idtbl_account'               => $rowdataentrylist['chartaccount'],
                        'tbl_account_detail_idtbl_account_detail' => $rowdataentrylist['detailaccount']
                    ];

                    $this->db->insert('tbl_receivable_entry', $datalist);
                    $entryIndex++;
                }
            }

            // ── Build working invoice list ────────────────────────────────────────
            $invoicePayments = [];
            foreach ($invoicedata as $invoice) {
                $invoicePayments[] = [
                    'invoice'     => $invoice,
                    'remaining'   => floatval(str_replace(',', '', $invoice->amount)),
                    'invoice_no'  => $invoice->invoiceno,
                    'invoice_id'  => $invoice->invid,
                    'paid_amount' => 0
                ];
            }

            $totalUnappliedUsed  = 0;
            $totalCreditNoteUsed = 0;

            // ─────────────────────────────────────────────────────────────────────
            // STEP 2: Apply credit notes to invoices
            // ─────────────────────────────────────────────────────────────────────
            if (!empty($creditnotedata) && !empty($receivableIDs)) {
                $targetReceivableID = $receivableIDs[0]['id'];

                foreach ($creditnotedata as $rowcn) {
                    $creditNoteID  = intval($rowcn->creditnoteid);
                    $cnGrossAmount = floatval(str_replace(',', '', $rowcn->amount));
                    $cnNarration   = 'Credit Note: ' . $rowcn->creditnoteno;

                    if ($cnGrossAmount <= 0 || $creditNoteID <= 0) {
                        continue;
                    }

                    // ── Validate credit note ──────────────────────────────────────
                    $this->db->select('idtbl_account_creditnote, creditnoteno, nettotal, claimstatus, settleamount');
                    $this->db->from('tbl_account_creditnote');
                    $this->db->where('idtbl_account_creditnote', $creditNoteID);
                    $this->db->where('status', 1);
                    $cnRow = $this->db->get()->row();

                    if (!$cnRow) {
                        throw new Exception("Credit Note ID {$creditNoteID} not found or inactive.");
                    }
                    if ($cnRow->claimstatus == 1) {
                        throw new Exception("Credit Note '{$cnRow->creditnoteno}' is already fully claimed.");
                    }

                    // ── Apply to invoices ─────────────────────────────────────────
                    $remainingCN = $cnGrossAmount;

                    foreach ($invoicePayments as $index => &$invoicePayment) {
                        if ($invoicePayment['remaining'] <= 0 || $remainingCN <= 0) {
                            continue;
                        }

                        $invoiceRemaining = $invoicePayment['remaining'];
                        $isLastInvoice    = true;

                        for ($i = $index + 1; $i < count($invoicePayments); $i++) {
                            if ($invoicePayments[$i]['remaining'] > 0) { $isLastInvoice = false; break; }
                        }

                        if ($remainingCN >= $invoiceRemaining) {
                            if ($isLastInvoice && $remainingCN > $invoiceRemaining) {
                                $this->db->insert('tbl_receivable_info', [
                                    'invoiceno'                       => $invoicePayment['invoice_id'],
                                    'narration'                       => $cnNarration,
                                    'amount'                          => $invoiceRemaining,
                                    'overpayment'                     => $remainingCN - $invoiceRemaining,
                                    'overpaysetoff'                   => 0,
                                    'setoff_receivable_info_id'       => 0,
                                    'creditnote_id'                   => $creditNoteID,
                                    'status'                          => '1',
                                    'insertdatetime'                  => $updatedatetime,
                                    'tbl_user_idtbl_user'             => $userID,
                                    'tbl_receivable_idtbl_receivable' => $targetReceivableID,
                                ]);
                                $invoicePayment['remaining']   = 0;
                                $invoicePayment['paid_amount'] += $invoiceRemaining;
                                $remainingCN = 0;
                            } else {
                                $this->db->insert('tbl_receivable_info', [
                                    'invoiceno'                       => $invoicePayment['invoice_id'],
                                    'narration'                       => $cnNarration,
                                    'amount'                          => $invoiceRemaining,
                                    'overpayment'                     => 0,
                                    'overpaysetoff'                   => 0,
                                    'setoff_receivable_info_id'       => 0,
                                    'creditnote_id'                   => $creditNoteID,
                                    'status'                          => '1',
                                    'insertdatetime'                  => $updatedatetime,
                                    'tbl_user_idtbl_user'             => $userID,
                                    'tbl_receivable_idtbl_receivable' => $targetReceivableID,
                                ]);
                                $invoicePayment['remaining']   = 0;
                                $invoicePayment['paid_amount'] += $invoiceRemaining;
                                $remainingCN -= $invoiceRemaining;
                            }
                        } else {
                            $this->db->insert('tbl_receivable_info', [
                                'invoiceno'                       => $invoicePayment['invoice_id'],
                                'narration'                       => $cnNarration,
                                'amount'                          => $remainingCN,
                                'overpayment'                     => 0,
                                'overpaysetoff'                   => 0,
                                'setoff_receivable_info_id'       => 0,
                                'creditnote_id'                   => $creditNoteID,
                                'status'                          => '1',
                                'insertdatetime'                  => $updatedatetime,
                                'tbl_user_idtbl_user'             => $userID,
                                'tbl_receivable_idtbl_receivable' => $targetReceivableID,
                            ]);
                            $invoicePayment['remaining']   -= $remainingCN;
                            $invoicePayment['paid_amount'] += $remainingCN;
                            $remainingCN = 0;
                        }

                        if ($remainingCN <= 0) break;
                    }
                    unset($invoicePayment);

                    $totalCreditNoteUsed += $cnGrossAmount;

                    // ── Insert credit note settle record ──────────────────────────
                    $this->db->insert('tbl_account_creditnote_settle', [
                        'date'                                            => $recsettdate,
                        'settlenettotal'                                  => $cnGrossAmount,
                        'status'                                          => '1',
                        'insertdatetime'                                  => $updatedatetime,
                        'updateuser'                                      => $userID,
                        'updatedatetime'                                  => $updatedatetime,
                        'tbl_user_idtbl_user'                             => $userID,
                        'tbl_account_creditnote_idtbl_account_creditnote' => $creditNoteID,
                        'tbl_receivable_idtbl_receivable'                 => $targetReceivableID,
                    ]);

                    // ── Update credit note settle totals ──────────────────────────
                    $newSettleGross = round(floatval($cnRow->settleamount) + $cnGrossAmount, 2);
                    $newClaimStatus = ($newSettleGross >= floatval($cnRow->nettotal)) ? 1 : 0;

                    $updateCN = [
                        'settleamount'                    => $newSettleGross,
                        'claimstatus'                     => $newClaimStatus,
                        'updateuser'                      => $userID,
                        'updatedatetime'                  => $updatedatetime,
                        'tbl_receivable_idtbl_receivable' => $targetReceivableID,
                    ];

                    if ($newClaimStatus == 1) {
                        $updateCN['claimdate'] = $updatedatetime;
                    }

                    $this->db->where('idtbl_account_creditnote', $creditNoteID);
                    $this->db->update('tbl_account_creditnote', $updateCN);
                }
            }

            // ─────────────────────────────────────────────────────────────────────
            // STEP 3: Apply unapplied amounts from previous overpayments
            // ─────────────────────────────────────────────────────────────────────
            $totalOverpaymentFromCurrent = 0;

            if (!empty($unapplydata) && !empty($receivableIDs)) {
                $targetReceivableID = $receivableIDs[0]['id'];

                foreach ($unapplydata as $unapplied) {
                    $sourceInfoID  = intval($unapplied->receivableinfoid);
                    $unappliedPool = floatval(str_replace(',', '', $unapplied->unappliedamount));

                    if ($unappliedPool <= 0 || $sourceInfoID <= 0) continue;

                    $this->db->select('idtbl_receivable_info, overpayment, overpaysetoff');
                    $this->db->from('tbl_receivable_info');
                    $this->db->where('idtbl_receivable_info', $sourceInfoID);
                    $sourceRow = $this->db->get()->row();

                    if (!$sourceRow) {
                        throw new Exception("Unapplied record ID {$sourceInfoID} not found.");
                    }

                    $availableBalance = floatval($sourceRow->overpayment);

                    if (round($unappliedPool, 2) > round($availableBalance, 2)) {
                        throw new Exception("Unapplied amount ({$unappliedPool}) exceeds available balance ({$availableBalance}) for record ID {$sourceInfoID}.");
                    }

                    $remainingPool = $unappliedPool;

                    foreach ($invoicePayments as $index => &$invoicePayment) {
                        if ($invoicePayment['remaining'] <= 0 || $remainingPool <= 0) continue;

                        $isLastInvoice = true;
                        for ($i = $index + 1; $i < count($invoicePayments); $i++) {
                            if ($invoicePayments[$i]['remaining'] > 0) { $isLastInvoice = false; break; }
                        }

                        $applyAmount = min($remainingPool, $invoicePayment['remaining']);

                        if ($isLastInvoice && $remainingPool > $invoicePayment['remaining']) {
                            $actualApplyAmount    = $invoicePayment['remaining'];
                            $newOverpaymentAmount = $remainingPool - $actualApplyAmount;

                            $this->db->insert('tbl_receivable_info', [
                                'invoiceno'                       => $invoicePayment['invoice_id'],
                                'narration'                       => 'Unapplied amount used from previous overpayment',
                                'amount'                          => $actualApplyAmount,
                                'overpayment'                     => $newOverpaymentAmount,
                                'overpaysetoff'                   => 0,
                                'setoff_receivable_info_id'       => $sourceInfoID,
                                'creditnote_id'                   => 0,
                                'status'                          => '1',
                                'insertdatetime'                  => $updatedatetime,
                                'tbl_user_idtbl_user'             => $userID,
                                'tbl_receivable_idtbl_receivable' => $targetReceivableID,
                            ]);

                            $invoicePayment['remaining']    = 0;
                            $invoicePayment['paid_amount'] += $actualApplyAmount;
                            $remainingPool                  = 0;
                            $totalUnappliedUsed            += $actualApplyAmount;

                            if ($newOverpaymentAmount > 0) {
                                $totalOverpaymentFromCurrent += $newOverpaymentAmount;
                            }
                        } else {
                            $this->db->insert('tbl_receivable_info', [
                                'invoiceno'                       => $invoicePayment['invoice_id'],
                                'narration'                       => 'Unapplied amount used from previous overpayment',
                                'amount'                          => $applyAmount,
                                'overpayment'                     => 0,
                                'overpaysetoff'                   => 0,
                                'setoff_receivable_info_id'       => $sourceInfoID,
                                'creditnote_id'                   => 0,
                                'status'                          => '1',
                                'insertdatetime'                  => $updatedatetime,
                                'tbl_user_idtbl_user'             => $userID,
                                'tbl_receivable_idtbl_receivable' => $targetReceivableID,
                            ]);

                            $invoicePayment['remaining']    -= $applyAmount;
                            $invoicePayment['paid_amount']  += $applyAmount;
                            $remainingPool                  -= $applyAmount;
                            $totalUnappliedUsed             += $applyAmount;
                        }

                        if ($remainingPool <= 0) break;
                    }
                    unset($invoicePayment);

                    $amountConsumed = $unappliedPool - $remainingPool;
                    $newBalance     = round($availableBalance - $amountConsumed, 2);

                    $this->db->where('idtbl_receivable_info', $sourceInfoID);
                    $this->db->update('tbl_receivable_info', [
                        'overpayment'   => $newBalance,
                        'overpaysetoff' => ($newBalance <= 0) ? 1 : 0
                    ]);
                }
            }

            // ─────────────────────────────────────────────────────────────────────
            // STEP 4: Apply current cash/bank payments to invoices
            // ─────────────────────────────────────────────────────────────────────

            if (!empty($paymentdata) && !empty($receivableIDs)) {
                foreach ($receivableIDs as $receivable) {
                    $receivableID     = $receivable['id'];
                    $paymentAmount    = $receivable['amount'];
                    $remainingPayment = $paymentAmount;

                    foreach ($invoicePayments as $index => &$invoicePayment) {
                        if ($invoicePayment['remaining'] <= 0 || $remainingPayment <= 0) continue;

                        $invoiceRemaining = $invoicePayment['remaining'];
                        $narration        = $invoicePayment['invoice_no'];
                        $isLastInvoice    = true;

                        foreach ($invoicePayments as $checkIndex => $checkInvoice) {
                            if ($checkIndex > $index && $checkInvoice['remaining'] > 0) { $isLastInvoice = false; break; }
                        }
                        
                        if ($remainingPayment >= $invoiceRemaining) {
                            if ($isLastInvoice) {
                                $overpaymentAmount = $remainingPayment - $invoiceRemaining;
                                $this->db->insert('tbl_receivable_info', [
                                    'invoiceno'                       => $invoicePayment['invoice_id'],
                                    'narration'                       => $narration,
                                    'amount'                          => $invoiceRemaining,
                                    'overpayment'                     => $overpaymentAmount,
                                    'overpaysetoff'                   => 0,
                                    'setoff_receivable_info_id'       => 0,
                                    'creditnote_id'                   => 0,
                                    'status'                          => '1',
                                    'insertdatetime'                  => $updatedatetime,
                                    'tbl_user_idtbl_user'             => $userID,
                                    'tbl_receivable_idtbl_receivable' => $receivableID,
                                ]);
                                if ($overpaymentAmount > 0) {
                                    $totalOverpaymentFromCurrent += $overpaymentAmount;
                                }
                                $invoicePayment['remaining']    = 0;
                                $invoicePayment['paid_amount'] += $invoiceRemaining;
                                $remainingPayment               = 0;
                            } else {
                                $this->db->insert('tbl_receivable_info', [
                                    'invoiceno'                       => $invoicePayment['invoice_id'],
                                    'narration'                       => $narration,
                                    'amount'                          => $invoiceRemaining,
                                    'overpayment'                     => 0,
                                    'overpaysetoff'                   => 0,
                                    'setoff_receivable_info_id'       => 0,
                                    'creditnote_id'                   => 0,
                                    'status'                          => '1',
                                    'insertdatetime'                  => $updatedatetime,
                                    'tbl_user_idtbl_user'             => $userID,
                                    'tbl_receivable_idtbl_receivable' => $receivableID,
                                ]);
                                $invoicePayment['remaining']    = 0;
                                $invoicePayment['paid_amount'] += $invoiceRemaining;
                                $remainingPayment              -= $invoiceRemaining;
                            }
                        } else {
                            $this->db->insert('tbl_receivable_info', [
                                'invoiceno'                       => $invoicePayment['invoice_id'],
                                'narration'                       => $narration,
                                'amount'                          => $remainingPayment,
                                'overpayment'                     => 0,
                                'overpaysetoff'                   => 0,
                                'setoff_receivable_info_id'       => 0,
                                'creditnote_id'                   => 0,
                                'status'                          => '1',
                                'insertdatetime'                  => $updatedatetime,
                                'tbl_user_idtbl_user'             => $userID,
                                'tbl_receivable_idtbl_receivable' => $receivableID,
                            ]);
                            $invoicePayment['remaining']    -= $remainingPayment;
                            $invoicePayment['paid_amount']  += $remainingPayment;
                            $remainingPayment                = 0;
                        }
                    }
                    unset($invoicePayment);
                }
            }

            // ─────────────────────────────────────────────────────────────────────
            // STEP 5: Payment total validation
            // ─────────────────────────────────────────────────────────────────────
            if (!empty($paymentdata)) {
                // Path A — cash payment
                $grandTotal    = round($paymentnettotal + $totalUnappliedUsed + $totalCreditNoteUsed, 2);
                $expectedTotal = round(floatval($paidamount) + floatval($creditnotetotal), 2);
            } else {
                // Path B — CN only / unapplied only / CN + unapplied
                $grandTotal    = round($totalCreditNoteUsed + $totalUnappliedUsed, 2);
                $expectedTotal = round(floatval($creditnotetotal) + floatval($unappliedamount), 2);
            }

            if ($grandTotal != $expectedTotal) {
                throw new Exception("Payment total mismatch. Expected: {$expectedTotal}, Got: {$grandTotal}");
            }

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Transaction failed');
            }
            // die();
            $this->db->trans_commit();
            $this->_jsonResponse(1, 'fas fa-save', 'Record Added Successfully', 'success');

        } catch (Exception $e) {
            if ($this->db->trans_enabled) {
                $this->db->trans_rollback();
            }
            error_log('Receivablesettleinsertupdate Error: ' . $e->getMessage());
            $this->_jsonResponse(0, 'fas fa-exclamation-triangle', 'Record Error: ' . $e->getMessage(), 'danger');
        }
    }
    public function Getviewpostinfo(){
        $recordID=$this->input->post('recordID');
        $updatedatetime=date('Y-m-d H:i:s');

        $configdata = getconfigdata('receivable_search');

		$tablename = $configdata->row(0)->tbl_name;
		$column1   = $configdata->row(0)->col_name;
		$column2   = $configdata->row(1)->col_name;

        $has_table = !empty($tablename) ? 1 : 0;

        $data = array(
            'postviewtime'=> $updatedatetime
        );

        $this->db->where('idtbl_receivable', $recordID);
        $this->db->where('poststatus', 0);
        $this->db->update('tbl_receivable', $data);

        $this->db->select("tbl_receivable.*, tbl_company.company, tbl_company_branch.branch, IF($has_table = 0, '', $tablename.$column2) AS customer, tbl_account.accountno AS `chartaccount`, tbl_account.accountname AS `chartaccountname`, tbl_account_detail.accountno AS `detailaccount`, tbl_account_detail.accountname AS `detailaccountname`");
        $this->db->from('tbl_receivable');
        $this->db->join('tbl_company', 'tbl_company.idtbl_company = tbl_receivable.tbl_company_idtbl_company', 'left');
        $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_receivable.tbl_company_branch_idtbl_company_branch', 'left');
        if(!empty($tablename)){
            $this->db->join("$tablename", "$tablename.$column1 = tbl_receivable.payer", 'left');
        }
        $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_receivable.tbl_account_idtbl_account', 'left');
        $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_receivable.tbl_account_detail_idtbl_account_detail', 'left');
        $this->db->where('tbl_receivable.idtbl_receivable', $recordID);
        // $this->db->where('tbl_account_payable_main.status', 1);

        $respond=$this->db->get();

        $this->db->select('tbl_receivable_entry.*, tbl_account_detail.accountno, tbl_account_detail.accountname, tbl_account.accountno AS chartaccountno, tbl_account.accountname AS chartaccountname');
        $this->db->from('tbl_receivable_entry');
        $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_receivable_entry.tbl_account_detail_idtbl_account_detail', 'left');
        $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_receivable_entry.tbl_account_idtbl_account', 'left');
        $this->db->where('tbl_receivable_entry.tbl_receivable_idtbl_receivable', $recordID);
        // $this->db->where('tbl_account_receivable.status', 1);

        $respondpayinfo=$this->db->get();

        $this->db->select('`invoiceno`, `narration`, `amount`');
        $this->db->from('tbl_receivable_info');
        $this->db->where('tbl_receivable_idtbl_receivable', $recordID);
        // $this->db->where('tbl_account_payable.status', 1);

        $respondinvoiceinfo=$this->db->get();

        // if($respond->row(0)->tbl_receivable_type_idtbl_receivable_type==1){
        //     if($respond->row(0)->tbl_account_detail_idtbl_account_detail>0){
        //         $accountno=$respond->row(0)->detailaccount; 
        //         $accountname=$respond->row(0)->detailaccountname;
        //     }
        //     else{
        //         $accountno=$respond->row(0)->chartaccount; 
        //         $accountname=$respond->row(0)->chartaccountname;
        //     }
        //     $chequedate='';
        //     $chequeno='';
        // }
        // else if($respond->row(0)->tbl_receivable_type_idtbl_receivable_type==2){
            if($respond->row(0)->tbl_account_detail_idtbl_account_detail>0){
                $accountno=$respond->row(0)->detailaccount; 
                $accountname=$respond->row(0)->detailaccountname;
            }
            else{
                $accountno=$respond->row(0)->chartaccount; 
                $accountname=$respond->row(0)->chartaccountname;
            }
            $chequedate=$respond->row(0)->chequedate;
            $chequeno=$respond->row(0)->chequeno;
        // }

        $html='';
        if($respond->row(0)->status==2){
        $html.='
        <div class="row">
            <div class="col">
                <div class="alert alert-warning" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i> Record Deactivated. Kindly review the status of the record.
                </div> 
            </div>
        </div>';
        }if($respond->row(0)->editstatus==1){
        $html.='
        <div class="row">
            <div class="col">
                <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i> Record in editable mode. You cannot change anything about the record.
                </div> 
            </div>
        </div>';
        }if($respond->row(0)->postdatedstatus==1 && $respond->row(0)->chequedate>date('Y-m-d')){
        $html.='
        <div class="row">
            <div class="col">
                <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i> You are viewing a post-dated receivable settlement. Please take note that this transaction will only be posted on the cheque date.
                </div> 
            </div>
        </div>';
        }
        $html.='
        <div class="row">
            <div class="col">
                <label class="small font-weight-bold my-0">Batch No: </label>
                <label class="small my-0">'.$respond->row(0)->batchno.'</label><br>
                <label class="small font-weight-bold my-0">Date: </label>
                <label class="small my-0">'.$respond->row(0)->recdate.'</label><br>
                <label class="small font-weight-bold my-0">Company/Branch: </label>
                <label class="small my-0">'.$respond->row(0)->company.'-'.$respond->row(0)->branch.'</label><br>
                <label class="small font-weight-bold my-0">Account No: </label>
                <label class="small my-0">'.$accountno.' - '.$accountname.'</label>
            </div>
            <div class="col">
                <label class="small font-weight-bold my-0">Supplier: </label>
                <label class="small my-0">'.$respond->row(0)->customer.'</label><br>
                <label class="small font-weight-bold my-0">Cheque Date: </label>
                <label class="small my-0">'.$chequedate.'</label><br>
                <label class="small font-weight-bold my-0">Cheque No: </label>
                <label class="small my-0">'.$chequeno.'</label><br>
                <label class="small font-weight-bold my-0">Amount: </label>
                <label class="small my-0">'.number_format($respond->row(0)->amount, 2).'</label>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h6 class="small title-style my-3"><span>Segregation Information</span></h6>
                <table class="table  table-striped table-sm nowrap small">
                    <thead>
                        <tr>
                            <th>Account</th>
                            <th>Narration</th>
                            <th class="text-center">C/D</th>
                            <th class="text-right">Debit</th>
                            <th class="text-right">Credit</th>
                        </tr>
                    </thead>
                    <tbody>';
                    foreach($respondpayinfo->result() as $rowdatainfo){
                        if($rowdatainfo->tratype=='D'){
                            $debitamount=$rowdatainfo->amount;
                            $creditamount=0;
                        }
                        else if($rowdatainfo->tratype=='C'){
                            $creditamount=$rowdatainfo->amount;
                            $debitamount=0;
                        }

                        $html.='
                        <tr>
                            <td>';
                            if(!empty($rowdatainfo->tbl_account_detail_idtbl_account_detail)){
                                $html.=$rowdatainfo->accountname.' - '.$rowdatainfo->accountno;
                            }
                            else{
                                $html.=$rowdatainfo->chartaccountname.' - '.$rowdatainfo->chartaccountno;
                            }
                            $html.='</td>
                            <td>'.$rowdatainfo->narration.'</td>
                            <td class="text-center">'.$rowdatainfo->tratype.'</td>
                            <td class="text-right">'.($debitamount != 0 ? number_format($debitamount, 2) : '').'</td>
                            <td class="text-right">'.($creditamount != 0 ? number_format($creditamount, 2) : '').'</td>
                        </tr>
                        ';
                    }
                    $html.='</tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h6 class="small title-style my-3"><span>Receivable Invoice Information</span></h6>
                <table class="table  table-striped table-sm nowrap small">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Invoice No</th>
                            <th>Narration</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>';
                    foreach($respondinvoiceinfo->result() as $rowdatainfo){
                        $html.='
                        <tr>
                            <td>'.$respond->row(0)->customer.'</td>
                            <td>'.$rowdatainfo->invoiceno.'</td>
                            <td>'.$rowdatainfo->narration.'</td>
                            <td class="text-right">'.number_format($rowdatainfo->amount, 2).'</td>
                        </tr>
                        ';
                    }
                    $html.='</tbody>
                </table>
            </div>
        </div>';
        if($respond->row(0)->poststatus==1){
            $html.='<div class="alert alert-success" role="alert">
                <h4 class="alert-heading">Posted!</h4>
                <p>The journal entry you are attempting to save has already been posted to the system. Please check your records or contact your administrator for assistance.</p>
            </div>';
        }

        $obj=new stdClass();
        $obj->html=$html;
        $obj->editablestatus=$respond->row(0)->editstatus;

        echo json_encode($obj);
    }
    // public function Receivablesettleposting(){
    //     $recordID=$this->input->post('recordID');
    //     $updatedatetime=date('Y-m-d H:i:s');
    //     $userID=$_SESSION['userid'];

    //     $i=0;

    //     $this->db->select('recdate, batchno, amount, poststatus, status, editstatus, postviewtime, updatedatetime, tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch, tbl_master_idtbl_master, payer, tbl_account_idtbl_account, tbl_account_detail_idtbl_account_detail, narration, postdatedstatus, chequedate');
    //     $this->db->from('tbl_receivable');
    //     $this->db->where('idtbl_receivable', $recordID);
    //     $this->db->where('status', 1);

    //     $respond=$this->db->get();

    //     if($respond->row(0)->postdatedstatus==1 && $respond->row(0)->chequedate>date('Y-m-d')){
    //         $actionObj=new stdClass();
    //         $actionObj->icon='fas fa-warning';
    //         $actionObj->title='';
    //         $actionObj->message='Record Error, You cannot post a post-dated receivable.';
    //         $actionObj->url='';
    //         $actionObj->target='_blank';
    //         $actionObj->type='danger';

    //         $actionJSON=json_encode($actionObj);
            
    //         $obj=new stdClass();
    //         $obj->status=0;
    //         $obj->action=$actionJSON;

    //         echo json_encode($obj);
    //     }
    //     else{
    //         if($respond->row(0)->poststatus==0 && $respond->row(0)->status==1 && $respond->row(0)->editstatus==0){
    //             if($respond->row(0)->postviewtime>$respond->row(0)->updatedatetime){
    //                 $this->db->trans_begin();
                    
    //                 $data = array(
    //                     'depositstatus'=> '1',
    //                     'poststatus'=> '1',
    //                     'postuser'=> $userID,
    //                     'postviewtime'=> NULL
    //                 );
            
    //                 $this->db->where('idtbl_receivable', $recordID);
    //                 $this->db->update('tbl_receivable', $data);

    //                 $i=1;
    //                 //Creditor account Transaction
    //                 $prefix=generate_prefix($respond->row(0)->tbl_company_idtbl_company, $respond->row(0)->tbl_company_branch_idtbl_company_branch, $respond->row(0)->recdate, 'AT');
    //                 $batchno=tr_batch_num($prefix, $respond->row(0)->tbl_company_branch_idtbl_company_branch);

    //                 $this->db->select('`idtbl_receivable_entry`, `transdate`, `batchno`, `tratype`, `amount`, `narration`, `tbl_master_idtbl_master`, `tbl_company_idtbl_company`, `tbl_company_branch_idtbl_company_branch`, `tbl_account_idtbl_account`, `tbl_account_detail_idtbl_account_detail`');
    //                 $this->db->from('tbl_receivable_entry');
    //                 $this->db->where('tbl_receivable_idtbl_receivable', $recordID);
    //                 $this->db->where('status', 1);

    //                 $responddetail=$this->db->get();

    //                 foreach($responddetail->result() AS $rowdetail){
    //                     $i++;

    //                     $receivedetailID=$rowdetail->idtbl_receivable_entry;
    //                     $tradate=$rowdetail->transdate;
    //                     $segbatchno=$rowdetail->batchno;
    //                     $detailaccount=$rowdetail->tbl_account_detail_idtbl_account_detail;
    //                     $chartaccount=$rowdetail->tbl_account_idtbl_account;
    //                     $company=$rowdetail->tbl_company_idtbl_company;
    //                     $branch=$rowdetail->tbl_company_branch_idtbl_company_branch;
    //                     $masterID=$rowdetail->tbl_master_idtbl_master;
    //                     $amount=$rowdetail->amount;
    //                     $narration=$rowdetail->narration;
    //                     $tratype=$rowdetail->tratype;
                        
    //                     if(!empty($detailaccount)){
    //                         $chartofaccountinfo=get_chart_account_acco_child_account($company, $branch, $detailaccount);
    //                         $chartofaccountID=$chartofaccountinfo->row(0)->idtbl_account;
    //                     }
    //                     else{
    //                         $chartofaccountID=$chartaccount;
    //                     }

    //                     $data = array(
    //                         'tradate'=> $tradate, 
    //                         'batchno'=> $batchno, 
    //                         'trabatchotherno'=> $segbatchno, 
    //                         'tratype'=> 'R', 
    //                         'seqno'=> $i, 
    //                         'crdr'=> $tratype, 
    //                         'accamount'=> $amount, 
    //                         'narration'=> $narration, 
    //                         'totamount'=> $amount, 
    //                         'status'=> '1', 
    //                         'insertdatetime'=> $updatedatetime, 
    //                         'tbl_user_idtbl_user'=> $userID,
    //                         'tbl_account_idtbl_account'=> $chartofaccountID,
    //                         'tbl_master_idtbl_master'=> $masterID,
    //                         'tbl_company_idtbl_company'=> $company,
    //                         'tbl_company_branch_idtbl_company_branch'=> $branch
    //                     );
        
    //                     $this->db->insert('tbl_account_transaction', $data);                    

    //                     $datafull = array(
    //                         'tradate'=> $tradate, 
    //                         'batchno'=> $batchno, 
    //                         'tratype'=> 'R', 
    //                         'crdr'=> $tratype, 
    //                         'accamount'=> $amount, 
    //                         'narration'=> $narration, 
    //                         'totamount'=> $amount, 
    //                         'status'=> '1', 
    //                         'insertdatetime'=> $updatedatetime, 
    //                         'tbl_user_idtbl_user'=> $userID,
    //                         'tbl_account_idtbl_account'=> $chartofaccountID,
    //                         'tbl_master_idtbl_master'=> $masterID,
    //                         'tbl_company_idtbl_company'=> $company,
    //                         'tbl_company_branch_idtbl_company_branch'=> $branch
    //                     );
        
    //                     $this->db->insert('tbl_account_transaction_full', $datafull);

    //                     //Update POST Status Detail
    //                     $datadetail = array(
    //                         'poststatus'=> '1',
    //                         'postuser'=> $userID
    //                     );
                
    //                     $this->db->where('idtbl_receivable_entry', $receivedetailID);
    //                     $this->db->update('tbl_receivable_entry', $datadetail);
    //                 }











    //                 // //Get Creditor Account
    //                 // $this->db->where('tbl_account_allocation.companybank', $respond->row(0)->tbl_company_idtbl_company);
    //                 // $this->db->where('tbl_account_allocation.branchcompanybank', $respond->row(0)->tbl_company_branch_idtbl_company_branch);
    //                 // // $this->db->where('tbl_account.tbl_account_type_idtbl_account_type', 2);
    //                 // $this->db->where('tbl_account.specialcate', 35);
    //                 // $this->db->where('tbl_account.status', 1);
    //                 // $this->db->where('tbl_account_allocation.status', 1);
    //                 // $this->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
    //                 // $this->db->select('`tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`');
    //                 // $this->db->from('tbl_account');
    //                 // $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');

    //                 // $respondcreditor=$this->db->get();

    //                 // $datacredit = array(
    //                 //     'tradate'=> $respond->row(0)->recdate, 
    //                 //     'batchno'=> $batchno, 
    //                 //     'trabatchotherno'=> $respond->row(0)->batchno, 
    //                 //     'tratype'=> 'R', 
    //                 //     'seqno'=> $i, 
    //                 //     'crdr'=> 'C', 
    //                 //     'accamount'=> $respond->row(0)->amount, 
    //                 //     'narration'=> $respond->row(0)->narration, 
    //                 //     'totamount'=> $respond->row(0)->amount, 
    //                 //     'status'=> '1', 
    //                 //     'insertdatetime'=> $updatedatetime, 
    //                 //     'tbl_user_idtbl_user'=> $userID,
    //                 //     'tbl_account_idtbl_account'=> $respondcreditor->row(0)->idtbl_account,
    //                 //     'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
    //                 //     'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
    //                 //     'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
    //                 // );
    //                 // $this->db->insert('tbl_account_transaction', $datacredit);
            
    //                 // $datacreditfull = array(
    //                 //     'tradate'=> $respond->row(0)->recdate, 
    //                 //     'batchno'=> $batchno, 
    //                 //     'tratype'=> 'R', 
    //                 //     'crdr'=> 'C', 
    //                 //     'accamount'=> $respond->row(0)->amount, 
    //                 //     'narration'=> $respond->row(0)->narration, 
    //                 //     'totamount'=> $respond->row(0)->amount, 
    //                 //     'status'=> '1', 
    //                 //     'insertdatetime'=> $updatedatetime, 
    //                 //     'tbl_user_idtbl_user'=> $userID,
    //                 //     'tbl_account_idtbl_account'=> $respondcreditor->row(0)->idtbl_account,
    //                 //     'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
    //                 //     'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
    //                 //     'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
    //                 // );
    //                 // $this->db->insert('tbl_account_transaction_full', $datacreditfull);

    //                 // //Debit account Transaction

    //                 // if(!empty($respond->row(0)->tbl_account_detail_idtbl_account_detail)){
    //                 //     $chartofaccountinfo=get_chart_account_acco_child_account($respond->row(0)->tbl_company_idtbl_company, $respond->row(0)->tbl_company_branch_idtbl_company_branch, $respond->row(0)->tbl_account_detail_idtbl_account_detail);
    //                 //     $chartofaccountID=$chartofaccountinfo->row(0)->idtbl_account;
    //                 // }
    //                 // else{
    //                 //     $chartofaccountID=$respond->row(0)->tbl_account_idtbl_account;
    //                 // }

    //                 // $i++;
    //                 // $data = array(
    //                 //     'tradate'=> $respond->row(0)->recdate, 
    //                 //     'batchno'=> $batchno, 
    //                 //     'trabatchotherno'=> $respond->row(0)->batchno, 
    //                 //     'tratype'=> 'R', 
    //                 //     'seqno'=> $i, 
    //                 //     'crdr'=> 'D', 
    //                 //     'accamount'=> $respond->row(0)->amount, 
    //                 //     'narration'=> $respond->row(0)->narration, 
    //                 //     'totamount'=> $respond->row(0)->amount,
    //                 //     'status'=> '1', 
    //                 //     'insertdatetime'=> $updatedatetime, 
    //                 //     'tbl_user_idtbl_user'=> $userID,
    //                 //     'tbl_account_idtbl_account'=> $chartofaccountID,
    //                 //     'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
    //                 //     'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
    //                 //     'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
    //                 // );

    //                 // $this->db->insert('tbl_account_transaction', $data);

    //                 // $datafull = array(
    //                 //     'tradate'=> $respond->row(0)->recdate, 
    //                 //     'batchno'=> $batchno, 
    //                 //     'tratype'=> 'R', 
    //                 //     'crdr'=> 'D', 
    //                 //     'accamount'=> $respond->row(0)->amount, 
    //                 //     'narration'=> $respond->row(0)->narration, 
    //                 //     'totamount'=> $respond->row(0)->amount,
    //                 //     'status'=> '1', 
    //                 //     'insertdatetime'=> $updatedatetime, 
    //                 //     'tbl_user_idtbl_user'=> $userID,
    //                 //     'tbl_account_idtbl_account'=> $respond->row(0)->tbl_account_idtbl_account,
    //                 //     'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
    //                 //     'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
    //                 //     'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
    //                 // );

    //                 // $this->db->insert('tbl_account_transaction_full', $datafull);







                    

    //                 $this->db->trans_complete();

    //                 if ($this->db->trans_status() === TRUE) {
    //                     $this->db->trans_commit();
                        
    //                     $actionObj=new stdClass();
    //                     $actionObj->icon='fas fa-save';
    //                     $actionObj->title='';
    //                     $actionObj->message='Record Added Successfully';
    //                     $actionObj->url='';
    //                     $actionObj->target='_blank';
    //                     $actionObj->type='success';

    //                     $actionJSON=json_encode($actionObj);
                        
    //                     $obj=new stdClass();
    //                     $obj->status=1;
    //                     $obj->action=$actionJSON;

    //                     echo json_encode($obj);
    //                 } else {
    //                     $this->db->trans_rollback();

    //                     $actionObj=new stdClass();
    //                     $actionObj->icon='fas fa-warning';
    //                     $actionObj->title='';
    //                     $actionObj->message='Record Error';
    //                     $actionObj->url='';
    //                     $actionObj->target='_blank';
    //                     $actionObj->type='danger';

    //                     $actionJSON=json_encode($actionObj);
                        
    //                     $obj=new stdClass();
    //                     $obj->status=0;
    //                     $obj->action=$actionJSON;

    //                     echo json_encode($obj);
    //                 }
    //             }
    //             else{
    //                 $actionObj=new stdClass();
    //                 $actionObj->icon='fas fa-warning';
    //                 $actionObj->title='';
    //                 $actionObj->message='Record Error, Please check this record for information. Because this record was edited before you posted.';
    //                 $actionObj->url='';
    //                 $actionObj->target='_blank';
    //                 $actionObj->type='danger';

    //                 $actionJSON=json_encode($actionObj);
                    
    //                 $obj=new stdClass();
    //                 $obj->status=0;
    //                 $obj->action=$actionJSON;

    //                 echo json_encode($obj);
    //             }
    //         }
    //         else if($respond->row(0)->status==2){
    //             $actionObj=new stdClass();
    //             $actionObj->icon='fas fa-warning';
    //             $actionObj->title='';
    //             $actionObj->message='Record Error, Record Deactivated. Kindly review the status of the record.';
    //             $actionObj->url='';
    //             $actionObj->target='_blank';
    //             $actionObj->type='warning';

    //             $actionJSON=json_encode($actionObj);
                
    //             $obj=new stdClass();
    //             $obj->status=0;
    //             $obj->action=$actionJSON;

    //             echo json_encode($obj);
    //         }
    //         else if($respond->row(0)->editstatus==1){
    //             $actionObj=new stdClass();
    //             $actionObj->icon='fas fa-warning';
    //             $actionObj->title='';
    //             $actionObj->message='Record Error, Record in editable mode. You cannot change anything about the record.';
    //             $actionObj->url='';
    //             $actionObj->target='_blank';
    //             $actionObj->type='danger';

    //             $actionJSON=json_encode($actionObj);
                
    //             $obj=new stdClass();
    //             $obj->status=0;
    //             $obj->action=$actionJSON;

    //             echo json_encode($obj);
    //         }
    //         else if($respond->row(0)->poststatus==1){
    //             $actionObj=new stdClass();
    //             $actionObj->icon='fas fa-warning';
    //             $actionObj->title='';
    //             $actionObj->message='Record Error, Record already posted.';
    //             $actionObj->url='';
    //             $actionObj->target='_blank';
    //             $actionObj->type='danger';

    //             $actionJSON=json_encode($actionObj);
                
    //             $obj=new stdClass();
    //             $obj->status=0;
    //             $obj->action=$actionJSON;

    //             echo json_encode($obj);
    //         }
    //     }
    // }
    public function Receivablesettleposting(){
        try {
            $recordID       = $this->input->post('recordID');
            $updatedatetime = date('Y-m-d H:i:s');
            $userID         = $_SESSION['userid'];
            $i              = 0;
    
            if(empty($recordID)){
                throw new Exception('Record ID is required');
            }
    
            // ── Fetch main receivable record ──────────────────────────────────
            $this->db->select('recdate, batchno, amount, poststatus, status, editstatus, postviewtime, updatedatetime, tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch, tbl_master_idtbl_master, payer, tbl_account_idtbl_account, tbl_account_detail_idtbl_account_detail, narration, postdatedstatus, chequedate');
            $this->db->from('tbl_receivable');
            $this->db->where('idtbl_receivable', $recordID);
            $this->db->where('status', 1);
    
            $respond = $this->db->get();
    
            if(!$respond || $respond->num_rows() == 0){
                throw new Exception('Record not found');
            }
    
            $record = $respond->row(0);
    
            // ── Status validation checks ──────────────────────────────────────
            if($record->postdatedstatus == 1 && $record->chequedate > date('Y-m-d')){
                throw new Exception('Record Error, You cannot post a post-dated receivable.');
            }
    
            if($record->status == 2){
                throw new Exception('Record Error, Record Deactivated. Kindly review the status of the record.');
            }
    
            if($record->poststatus == 1){
                throw new Exception('Record Error, Record already posted.');
            }
    
            if($record->editstatus == 1){
                throw new Exception('Record Error, Record in editable mode. You cannot change anything about the record.');
            }
    
            if(!($record->poststatus == 0 && $record->status == 1 && $record->editstatus == 0)){
                throw new Exception('Record Error, Invalid record state for posting.');
            }
    
            if($record->postviewtime <= $record->updatedatetime){
                throw new Exception('Record Error, Please check this record for information. Because this record was edited before you posted.');
            }
    
            // ── Begin Transaction ─────────────────────────────────────────────
            $this->db->trans_begin();
    
            // Update main receivable post status
            $data = array(
                'depositstatus' => '1',
                'poststatus'    => '1',
                'postuser'      => $userID,
                'postviewtime'  => NULL
            );
    
            $this->db->where('idtbl_receivable', $recordID);
            $this->db->update('tbl_receivable', $data);
    
            $i = 1;

            if($record->postdatedstatus == 1 && $record->chequedate <= date('Y-m-d')){
                $recdate = $record->chequedate;
            }
            else{
                $recdate = $record->recdate;
            }
    
            // Generate batch number for account transaction
            $prefix  = generate_prefix($record->tbl_company_idtbl_company, $record->tbl_company_branch_idtbl_company_branch, $recdate, 'AT');
            $batchno = tr_batch_num($prefix, $record->tbl_company_branch_idtbl_company_branch);
    
            if(empty($batchno)){
                throw new Exception('Record Error, Batch no could not be defined by system');
            }
    
            // ── Fetch receivable entry lines ──────────────────────────────────
            $this->db->select('`idtbl_receivable_entry`, `transdate`, `batchno`, `tratype`, `amount`, `narration`, `tbl_master_idtbl_master`, `tbl_company_idtbl_company`, `tbl_company_branch_idtbl_company_branch`, `tbl_account_idtbl_account`, `tbl_account_detail_idtbl_account_detail`');
            $this->db->from('tbl_receivable_entry');
            $this->db->where('tbl_receivable_idtbl_receivable', $recordID);
            $this->db->where('status', 1);
    
            $responddetail = $this->db->get();
    
            if(!$responddetail || $responddetail->num_rows() == 0){
                throw new Exception('No receivable entry lines found for this record');
            }
    
            // ── Process each entry line ───────────────────────────────────────
            foreach($responddetail->result() as $rowdetail){
                $i++;
    
                $receivedetailID = $rowdetail->idtbl_receivable_entry;
                $tradate         = $rowdetail->transdate;
                $segbatchno      = $rowdetail->batchno;
                $detailaccount   = $rowdetail->tbl_account_detail_idtbl_account_detail;
                $chartaccount    = $rowdetail->tbl_account_idtbl_account;
                $company         = $rowdetail->tbl_company_idtbl_company;
                $branch          = $rowdetail->tbl_company_branch_idtbl_company_branch;
                $masterID        = $rowdetail->tbl_master_idtbl_master;
                $amount          = $rowdetail->amount;
                $narration       = $rowdetail->narration;
                $tratype         = $rowdetail->tratype;
    
                // Resolve chart of account ID
                if(!empty($detailaccount)){
                    $chartofaccountinfo = get_chart_account_acco_child_account($company, $branch, $detailaccount);
                    if(!$chartofaccountinfo || $chartofaccountinfo->num_rows() == 0){
                        throw new Exception('Chart of account not found for detail account: ' . $detailaccount);
                    }
                    $chartofaccountID = $chartofaccountinfo->row(0)->idtbl_account;
                } else {
                    $chartofaccountID = $chartaccount;
                }
    
                // Insert into tbl_account_transaction
                $data = array(
                    'tradate'                                 => $tradate,
                    'batchno'                                 => $batchno,
                    'trabatchotherno'                         => $segbatchno,
                    'tratype'                                 => 'R',
                    'seqno'                                   => $i,
                    'crdr'                                    => $tratype,
                    'accamount'                               => $amount,
                    'narration'                               => $narration,
                    'totamount'                               => $amount,
                    'status'                                  => '1',
                    'insertdatetime'                          => $updatedatetime,
                    'tbl_user_idtbl_user'                     => $userID,
                    'tbl_account_idtbl_account'               => $chartofaccountID,
                    'tbl_master_idtbl_master'                 => $masterID,
                    'tbl_company_idtbl_company'               => $company,
                    'tbl_company_branch_idtbl_company_branch' => $branch
                );
    
                $this->db->insert('tbl_account_transaction', $data);
    
                // Insert into tbl_account_transaction_full
                $datafull = array(
                    'tradate'                                 => $tradate,
                    'batchno'                                 => $batchno,
                    'tratype'                                 => 'R',
                    'crdr'                                    => $tratype,
                    'accamount'                               => $amount,
                    'narration'                               => $narration,
                    'totamount'                               => $amount,
                    'status'                                  => '1',
                    'insertdatetime'                          => $updatedatetime,
                    'tbl_user_idtbl_user'                     => $userID,
                    'tbl_account_idtbl_account'               => $chartofaccountID,
                    'tbl_master_idtbl_master'                 => $masterID,
                    'tbl_company_idtbl_company'               => $company,
                    'tbl_company_branch_idtbl_company_branch' => $branch
                );
    
                $this->db->insert('tbl_account_transaction_full', $datafull);
    
                // Update post status on entry line
                $datadetail = array(
                    'poststatus' => '1',
                    'postuser'   => $userID
                );
    
                $this->db->where('idtbl_receivable_entry', $receivedetailID);
                $this->db->update('tbl_receivable_entry', $datadetail);
            }
    
            // ── Complete Transaction ──────────────────────────────────────────
            $this->db->trans_complete();
    
            if($this->db->trans_status() === TRUE){
                $this->db->trans_commit();
                $this->_jsonResponse(1, 'fas fa-save', 'Record Posted Successfully', 'success');
            } else {
                $this->db->trans_rollback();
                throw new Exception('Record Error, Transaction failed');
            }
    
        } catch(Exception $e){
            if($this->db->trans_enabled){
                $this->db->trans_rollback();
            }
            $this->_jsonResponse(0, 'fas fa-warning', $e->getMessage(), 'danger');
        }
    }
    // public function Receivablesettlestatus($x, $y){
    //     $this->db->trans_begin();

    //     $userID=$_SESSION['userid'];
    //     $recordID=$x;
    //     $type=$y;
    //     $updatedatetime=date('Y-m-d H:i:s');

    //     if($type==1){
    //         $data = array(
    //             'status' => '1',
    //             'updateuser'=> $userID, 
    //             'updatedatetime'=> $updatedatetime
    //         );

    //         $this->db->where('idtbl_receivable', $recordID);
    //         $this->db->update('tbl_receivable', $data);

    //         $datapay = array(
    //             'status' => '1',
    //             'updateuser'=> $userID, 
    //             'updatedatetime'=> $updatedatetime
    //         );

    //         $this->db->where('tbl_receivable_idtbl_receivable', $recordID);
    //         $this->db->update('tbl_receivable_info', $datapay);

    //         $this->db->trans_complete();

    //         if ($this->db->trans_status() === TRUE) {
    //             $this->db->trans_commit();
                
    //             $actionObj=new stdClass();
    //             $actionObj->icon='fas fa-check';
    //             $actionObj->title='';
    //             $actionObj->message='Record Activate Successfully';
    //             $actionObj->url='';
    //             $actionObj->target='_blank';
    //             $actionObj->type='success';

    //             $actionJSON=json_encode($actionObj);
                
    //             $this->session->set_flashdata('msg', $actionJSON);
    //             redirect('Receivablesettle');                
    //         } else {
    //             $this->db->trans_rollback();

    //             $actionObj=new stdClass();
    //             $actionObj->icon='fas fa-warning';
    //             $actionObj->title='';
    //             $actionObj->message='Record Error';
    //             $actionObj->url='';
    //             $actionObj->target='_blank';
    //             $actionObj->type='danger';

    //             $actionJSON=json_encode($actionObj);
                
    //             $this->session->set_flashdata('msg', $actionJSON);
    //             redirect('Receivablesettle');
    //         }
    //     }
    //     else if($type==2){
    //         $data = array(
    //             'status' => '2',
    //             'updateuser'=> $userID, 
    //             'updatedatetime'=> $updatedatetime
    //         );

    //         $this->db->where('idtbl_receivable', $recordID);
    //         $this->db->update('tbl_receivable', $data);

    //         $datapay = array(
    //             'status' => '2',
    //             'updateuser'=> $userID, 
    //             'updatedatetime'=> $updatedatetime
    //         );

    //         $this->db->where('tbl_receivable_idtbl_receivable', $recordID);
    //         $this->db->update('tbl_receivable_info', $datapay);

    //         $this->db->trans_complete();

    //         if ($this->db->trans_status() === TRUE) {
    //             $this->db->trans_commit();
                
    //             $actionObj=new stdClass();
    //             $actionObj->icon='fas fa-times';
    //             $actionObj->title='';
    //             $actionObj->message='Record Deactivate Successfully';
    //             $actionObj->url='';
    //             $actionObj->target='_blank';
    //             $actionObj->type='warning';

    //             $actionJSON=json_encode($actionObj);
                
    //             $this->session->set_flashdata('msg', $actionJSON);
    //             redirect('Receivablesettle');                
    //         } else {
    //             $this->db->trans_rollback();

    //             $actionObj=new stdClass();
    //             $actionObj->icon='fas fa-warning';
    //             $actionObj->title='';
    //             $actionObj->message='Record Error';
    //             $actionObj->url='';
    //             $actionObj->target='_blank';
    //             $actionObj->type='danger';

    //             $actionJSON=json_encode($actionObj);
                
    //             $this->session->set_flashdata('msg', $actionJSON);
    //             redirect('Receivablesettle');
    //         }
    //     }
    //     else if($type==3){
    //         $data = array(
    //             'status' => '3',
    //             'updateuser'=> $userID, 
    //             'updatedatetime'=> $updatedatetime
    //         );

    //         $this->db->where('idtbl_receivable', $recordID);
    //         $this->db->update('tbl_receivable', $data);

    //         $datapay = array(
    //             'status' => '3',
    //             'updateuser'=> $userID, 
    //             'updatedatetime'=> $updatedatetime
    //         );

    //         $this->db->where('tbl_receivable_idtbl_receivable', $recordID);
    //         $this->db->update('tbl_receivable_info', $datapay);

    //         $this->db->trans_complete();

    //         if ($this->db->trans_status() === TRUE) {
    //             $this->db->trans_commit();
                
    //             $actionObj=new stdClass();
    //             $actionObj->icon='fas fa-trash-alt';
    //             $actionObj->title='';
    //             $actionObj->message='Record Remove Successfully';
    //             $actionObj->url='';
    //             $actionObj->target='_blank';
    //             $actionObj->type='danger';

    //             $actionJSON=json_encode($actionObj);
                
    //             $this->session->set_flashdata('msg', $actionJSON);
    //             redirect('Receivablesettle');                
    //         } else {
    //             $this->db->trans_rollback();

    //             $actionObj=new stdClass();
    //             $actionObj->icon='fas fa-warning';
    //             $actionObj->title='';
    //             $actionObj->message='Record Error';
    //             $actionObj->url='';
    //             $actionObj->target='_blank';
    //             $actionObj->type='danger';

    //             $actionJSON=json_encode($actionObj);
                
    //             $this->session->set_flashdata('msg', $actionJSON);
    //             redirect('Receivablesettle');
    //         }
    //     }
    // }
    public function Receivablesettlestatus($x, $y){
        $userID         = $_SESSION['userid'];
        $recordID       = $x;
        $type           = $y;
        $updatedatetime = date('Y-m-d H:i:s');
    
        // ── Type config map ───────────────────────────────────────────────────
        // type 1 = Activate, type 2 = Deactivate, type 3 = Remove
        $typeConfig = array(
            1 => array(
                'status'  => '1',
                'icon'    => 'fas fa-check',
                'message' => 'Record Activate Successfully',
                'type'    => 'success'
            ),
            2 => array(
                'status'  => '2',
                'icon'    => 'fas fa-times',
                'message' => 'Record Deactivate Successfully',
                'type'    => 'warning'
            ),
            3 => array(
                'status'  => '3',
                'icon'    => 'fas fa-trash-alt',
                'message' => 'Record Remove Successfully',
                'type'    => 'danger'
            ),
        );
    
        try {
    
            if(empty($recordID)){
                throw new Exception('Record ID is required');
            }
    
            if(!array_key_exists($type, $typeConfig)){
                throw new Exception('Invalid status type provided');
            }
    
            $config = $typeConfig[$type];
    
            // ── Begin Transaction ─────────────────────────────────────────────
            $this->db->trans_begin();
    
            // Update main receivable status
            $data = array(
                'status'         => $config['status'],
                'updateuser'     => $userID,
                'updatedatetime' => $updatedatetime
            );
    
            $this->db->where('idtbl_receivable', $recordID);
            $this->db->update('tbl_receivable', $data);
    
            // Update receivable info status
            $datapay = array(
                'status'         => $config['status'],
                'updateuser'     => $userID,
                'updatedatetime' => $updatedatetime
            );
    
            $this->db->where('tbl_receivable_idtbl_receivable', $recordID);
            $this->db->update('tbl_receivable_info', $datapay);

            // Update tbl_receivable_entry info status
            $datapay = array(
                'status'         => $config['status'],
                'updateuser'     => $userID,
                'updatedatetime' => $updatedatetime
            );
    
            $this->db->where('tbl_receivable_idtbl_receivable', $recordID);
            $this->db->update('tbl_receivable_entry', $datapay);
    
            // ── Complete Transaction ──────────────────────────────────────────
            $this->db->trans_complete();
    
            if($this->db->trans_status() === TRUE){
                $this->db->trans_commit();
    
                $actionObj          = new stdClass();
                $actionObj->icon    = $config['icon'];
                $actionObj->title   = '';
                $actionObj->message = $config['message'];
                $actionObj->url     = '';
                $actionObj->target  = '_blank';
                $actionObj->type    = $config['type'];
    
                $this->session->set_flashdata('msg', json_encode($actionObj));
                redirect('Receivablesettle');
            } else {
                $this->db->trans_rollback();
                throw new Exception('Record Error');
            }
    
        } catch(Exception $e){
            if($this->db->trans_enabled){
                $this->db->trans_rollback();
            }
    
            $actionObj          = new stdClass();
            $actionObj->icon    = 'fas fa-warning';
            $actionObj->title   = '';
            $actionObj->message = $e->getMessage();
            $actionObj->url     = '';
            $actionObj->target  = '_blank';
            $actionObj->type    = 'danger';
    
            $this->session->set_flashdata('msg', json_encode($actionObj));
            redirect('Receivablesettle');
        }
    }
    public function Getinvrecno(){
        $printtype=$this->input->post('printtype');
        $printcustomer=$this->input->post('printcustomer');
        $printdate=$this->input->post('printdate');

        if($printtype==1){
            $this->db->select('`tbl_receivable_info`.`invoiceno` AS `invoicereceiptno`');
            $this->db->from('tbl_receivable_info');
            $this->db->join('tbl_receivable', 'tbl_receivable.idtbl_receivable = tbl_receivable_info.tbl_receivable_idtbl_receivable', 'left');
            $this->db->where('tbl_receivable_info.status', '1');
            $this->db->where('tbl_receivable.status', '1');
            if(!empty($printcustomer)){$this->db->where('tbl_receivable.payer', $printcustomer);}
            if(!empty($printdate)){$this->db->where('tbl_receivable.recdate', $printdate);}
            $this->db->group_by('`tbl_receivable_info`.`invoiceno`');

            $respond=$this->db->get();

            echo json_encode($respond->result());
        }
        else{
            $this->db->select('`receiptno` AS `invoicereceiptno`');
            $this->db->from('tbl_receivable');
            $this->db->where('tbl_receivable.status', '1');
            if(!empty($printcustomer)){$this->db->where('tbl_receivable.payer', $printcustomer);}
            if(!empty($printdate)){$this->db->where('tbl_receivable.recdate', $printdate);}
            $this->db->group_by('`receiptno`');

            $respond=$this->db->get();

            echo json_encode($respond->result());
        }
    }
    public function Getunappliedpaymentaccocustomer(){
        $recordID=$this->input->post('recordID');
        
        $configdata = getconfigdata('receivable_search');

		$tablename = $configdata->row(0)->tbl_name;
		$column1   = $configdata->row(0)->col_name;
		$column2   = $configdata->row(1)->col_name;

        $has_table = !empty($tablename) ? 1 : 0;

        // $this->db->select('`tbl_sales_info`.`idtbl_sales_info`, `tbl_sales_info`.`invno`, `tbl_sales_info`.`amount`, IFNULL(SUM(`tbl_receivable_info`.`amount`), 0) AS `sumpay`, (`tbl_sales_info`.`amount`-IFNULL(SUM(`tbl_receivable_info`.`amount`), 0)) AS `balpay`, `tbl_sales_info`.`tbl_customer_idtbl_customer`, `tbl_customer`.`customer`');
        $this->db->select("`tbl_receivable_info`.`idtbl_receivable_info`, `tbl_receivable_info`.`invoiceno`, `tbl_receivable_info`.`overpayment`, IF($has_table = 0, '', $tablename.$column1) AS idtbl_customer, IF($has_table = 0, '', $tablename.$column2) AS customer");
        $this->db->from('tbl_receivable');
        $this->db->join('tbl_receivable_info', 'tbl_receivable_info.tbl_receivable_idtbl_receivable = tbl_receivable.idtbl_receivable', 'left');
        if(!empty($tablename)){
            $this->db->join("$tablename", "$tablename.$column1 = tbl_receivable.payer", 'left');
        }
        $this->db->where('tbl_receivable.status', 1);
        $this->db->where('tbl_receivable.poststatus', 1);
        $this->db->where('tbl_receivable_info.overpaysetoff', 0);
        $this->db->where('tbl_receivable_info.overpayment > ', 0);
        $this->db->where('tbl_receivable.payer', $recordID);

        $respond=$this->db->get();
        // print_r($this->db->last_query());

        $html='';
        $i=1;
        if(!empty($respond->result())){
            foreach($respond->result() as $rowdatalist){
                $html.='
                <tr>
                    <td class="text-center" width="5%">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input checkclickunapplied" id="customUnapplied'.$i.'">
                            <label class="custom-control-label m-0" for="customUnapplied'.$i.'"></label>
                        </div>
                    </td>
                    <td class="d-none">'.$rowdatalist->idtbl_receivable_info.'</td>
                    <td>'.$rowdatalist->customer.'</td>
                    <td class="d-none">'.$rowdatalist->invoiceno.'</td>
                    <td>'.$rowdatalist->invoiceno.'</td>
                    <td class="text-right">'.number_format($rowdatalist->overpayment, 2).'</td>
                </tr>
                ';
                $i++;
            }
        }
        echo $html;
    }
    public function Getcreditnoteaccocustomer(){
        $recordID=$this->input->post('recordID');
        $companyID=$_SESSION['companyid'];
        $branchID=$_SESSION['branchid'];
        
        $configdata = getconfigdata('receivable_search');

		$tablename = $configdata->row(0)->tbl_name;
		$column1   = $configdata->row(0)->col_name;
		$column2   = $configdata->row(1)->col_name;

        $has_table = !empty($tablename) ? 1 : 0;

        $this->db->select("`tbl_account_creditnote`.`idtbl_account_creditnote`, `tbl_account_creditnote`.`creditnoteno`, (`tbl_account_creditnote`.`nettotal` - `tbl_account_creditnote`.`settleamount`) AS remainingamount, `tbl_account_creditnote`.`vatstatus`, IF($has_table = 0, '', $tablename.$column1) AS idtbl_customer, IF($has_table = 0, '', $tablename.$column2) AS customer");
        $this->db->from('tbl_account_creditnote');
        if(!empty($tablename)){
            $this->db->join("$tablename", "$tablename.$column1 = tbl_account_creditnote.tbl_customer_idtbl_customer", 'left');
        }
        $this->db->where('tbl_account_creditnote.status', 1);
        $this->db->where('tbl_account_creditnote.claimstatus', 0);
        $this->db->where('tbl_account_creditnote.tbl_customer_idtbl_customer', $recordID);
        $this->db->where('tbl_account_creditnote.tbl_company_idtbl_company', $companyID);
        $this->db->where('tbl_account_creditnote.tbl_company_branch_idtbl_company_branch', $branchID);

        $respond=$this->db->get();
        // print_r($this->db->last_query());        

        $html='';
        $i=1;
        if(!empty($respond->result())){
            foreach($respond->result() as $rowdatalist){
                $html.='
                <tr>
                    <td class="text-center" width="5%">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input checkclickcreditnote" id="customcreditnote'.$i.'">
                            <label class="custom-control-label m-0" for="customcreditnote'.$i.'"></label>
                        </div>
                    </td>
                    <td class="d-none">'.$rowdatalist->idtbl_account_creditnote.'</td>
                    <td>'.$rowdatalist->creditnoteno.'</td>
                    <td>'.$rowdatalist->customer.'</td>
                    <td class="text-right">'.number_format($rowdatalist->remainingamount, 2).'</td>
                </tr>
                ';
                $i++;
            }
        }
        echo $html;
    }

    // ── Private helper ────────────────────────────────────────────────────────────
    private function _jsonResponse($status, $icon, $message, $type) {
        $actionObj          = new stdClass();
        $actionObj->icon    = $icon;
        $actionObj->title   = '';
        $actionObj->message = $message;
        $actionObj->url     = '';
        $actionObj->target  = '_blank';
        $actionObj->type    = $type;

        $obj         = new stdClass();
        $obj->status = $status;
        $obj->action = json_encode($actionObj);

        echo json_encode($obj);
    }
}