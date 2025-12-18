<?php
class Receivablesettleinfo extends CI_Model{
    public function Getreceivabletype(){
        $this->db->select('idtbl_receivable_type, receivabletype');
        $this->db->from('tbl_receivable_type');
        $this->db->where('status', 1);

        $respond=$this->db->get();

        return $respond;
    }
    public function Getinvoiceaccocustomer(){
        $recordID=$this->input->post('recordID');

        // $this->db->select('`tbl_sales_info`.`idtbl_sales_info`, `tbl_sales_info`.`invno`, `tbl_sales_info`.`amount`, IFNULL(SUM(`tbl_receivable_info`.`amount`), 0) AS `sumpay`, (`tbl_sales_info`.`amount`-IFNULL(SUM(`tbl_receivable_info`.`amount`), 0)) AS `balpay`, `tbl_sales_info`.`tbl_customer_idtbl_customer`, `tbl_customer`.`customer`');
        $this->db->select('`tbl_sales_info`.`idtbl_sales_info`, `tbl_sales_info`.`invno`, `tbl_sales_info`.`amount`, IFNULL(SUM(CASE WHEN `tbl_receivable_info`.`status` = 1 THEN `tbl_receivable_info`.`amount` ELSE 0 END), 0) AS `sumpay`, (`tbl_sales_info`.`amount`-IFNULL(SUM(CASE WHEN `tbl_receivable_info`.`status` = 1 THEN `tbl_receivable_info`.`amount` ELSE 0 END), 0)) AS `balpay`, `tbl_sales_info`.`tbl_customer_idtbl_customer`, `tbl_customer`.`customer`');
        $this->db->from('tbl_sales_info');
        $this->db->join('tbl_receivable_info', 'tbl_receivable_info.invoiceno = tbl_sales_info.invno', 'left');
        $this->db->join('tbl_customer', 'tbl_customer.idtbl_customer = tbl_sales_info.tbl_customer_idtbl_customer', 'left');
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
                    <td class="text-right">'.number_format($rowdatalist->amount, 2).'</td>
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
    public function Receivablesettleinsertupdate() {
        $userID = $_SESSION['userid'];
        $detailaccount = 0;
        $chartaccount = 0;

        $company = $this->input->post('company');
        $branch = $this->input->post('branch');
        $recsettdate = $this->input->post('recsettdate');
        $customer = $this->input->post('customer');
        $invoicepayamount = str_replace(',', '', $this->input->post('invoicepayamount'));
        $paidamount = str_replace(',', '', $this->input->post('paidamount'));
        $invoicedata = json_decode($this->input->post('tableData'));
        $paymentdata = json_decode($this->input->post('tableReceData'));
        
        $chequecashamount = $paidamount;

        $recordOption = $this->input->post('recordOption');
        if (!empty($this->input->post('recordID'))) {
            $recordID = $this->input->post('recordID');
        }
        
        if ($recordOption == 1) {
            $prefix = receiv_prefix($company, $branch);
            $masterdata = get_account_period($company, $branch);
            $batchno = tr_batch_num($prefix, $branch);
            $masterID = $masterdata->idtbl_master;
            $receiptno = tr_batch_num('REC'.date('y'), $branch);
            $receiptno = preg_replace('/^(.{5})00/', '$1', $receiptno);
        }

        $updatedatetime = date('Y-m-d H:i:s');
        $today = date('Y-m-d');
        
        if ($recordOption == 1) {
            if (!empty($batchno)) {
                $this->db->trans_begin();

                $paymentnettotal = 0;
                
                // Create a working copy of invoice data with remaining amounts
                $invoicePayments = [];
                foreach ($invoicedata as $invoice) {
                    $invoicePayments[] = [
                        'invoice' => $invoice,
                        'remaining' => floatval(str_replace(',', '', $invoice->amount))
                    ];
                }
                
                foreach ($paymentdata as $rowpaymentdata):
                    if ($rowpaymentdata->accounttype == 1) {
                        $chartaccount = $rowpaymentdata->chartofaccount;
                        $detailaccount = 0;
                    } else if ($rowpaymentdata->accounttype == 2) {
                        $detailaccount = $rowpaymentdata->chartofaccount;
                        $chartaccount = 0;
                    }

                    $data = array(
                        'recdate' => $recsettdate,
                        'receiptno' => $receiptno,
                        'batchno' => $batchno,
                        'payer' => $customer,
                        'amount' => str_replace(',', '', $rowpaymentdata->amount),
                        'narration' => $rowpaymentdata->narration,
                        'chequedate' => $rowpaymentdata->chequedate,
                        'chequeno' => $rowpaymentdata->chequeno,
                        'postdatedstatus' => $rowpaymentdata->postdatedstatus,
                        'poststatus' => '0',
                        'status' => '1',
                        'insertdatetime' => $updatedatetime,
                        'tbl_user_idtbl_user' => $userID,
                        'tbl_receivable_type_idtbl_receivable_type' => $rowpaymentdata->receivabletypeid,
                        'tbl_company_idtbl_company' => $company,
                        'tbl_company_branch_idtbl_company_branch' => $branch,
                        'tbl_master_idtbl_master' => $masterID,
                        'tbl_account_idtbl_account' => $chartaccount,
                        'tbl_account_detail_idtbl_account_detail' => $detailaccount
                    );

                    $this->db->insert('tbl_receivable', $data);
                    $receivableID = $this->db->insert_id();

                    // Process invoices for this payment
                    $paymentAmount = floatval(str_replace(',', '', $rowpaymentdata->amount));
                    
                    foreach ($invoicePayments as &$invoicePayment) {
                        // Skip if this invoice is already fully paid
                        if ($invoicePayment['remaining'] <= 0) {
                            continue;
                        }
                        
                        $invoiceRemaining = $invoicePayment['remaining'];
                        $narration = $invoicePayment['invoice']->customer . ' - ' . $invoicePayment['invoice']->invoiceno;
                        
                        if ($paymentAmount > 0) {
                            if ($paymentAmount >= $invoiceRemaining) {
                                // Full payment for this invoice
                                $invoicepayamount = $invoiceRemaining;
                                $paymentAmount = $paymentAmount - $invoiceRemaining;
                                $invoicePayment['remaining'] = 0;
                                
                                // Update the original invoice amount for consistency
                                $invoicePayment['invoice']->amount = '0.00';
                            } else {
                                // Partial payment
                                $invoicepayamount = $paymentAmount;
                                $invoicePayment['remaining'] = $invoiceRemaining - $paymentAmount;
                                
                                // Update the original invoice amount for consistency
                                $invoicePayment['invoice']->amount = number_format($invoicePayment['remaining'], 2);
                                
                                $paymentAmount = 0;
                            }
                            
                            // Insert payment record for this invoice
                            $datasub = array(
                                'invoiceno' => $invoicePayment['invoice']->invid,
                                'narration' => $narration,
                                'amount' => $invoicepayamount,
                                'status' => '1',
                                'insertdatetime' => $updatedatetime,
                                'tbl_user_idtbl_user' => $userID,
                                'tbl_receivable_idtbl_receivable' => $receivableID,
                            );

                            $this->db->insert('tbl_receivable_info', $datasub);
                        }
                        
                        // Break if payment amount is exhausted
                        if ($paymentAmount <= 0) {
                            break;
                        }
                    }

                    $paymentnettotal = $paymentnettotal + str_replace(',', '', $rowpaymentdata->amount);
                endforeach;

                $this->db->trans_complete();
                if ($this->db->trans_status() === TRUE) {
                    if ($paymentnettotal == $paidamount) {
                        $this->db->trans_commit();

                        $actionObj = new stdClass();
                        $actionObj->icon = 'fas fa-save';
                        $actionObj->title = '';
                        $actionObj->message = 'Record Added Successfully';
                        $actionObj->url = '';
                        $actionObj->target = '_blank';
                        $actionObj->type = 'success';

                        $actionJSON = json_encode($actionObj);
                        
                        $obj = new stdClass();
                        $obj->status = 1;
                        $obj->action = $actionJSON;

                        echo json_encode($obj);
                    } else {
                        $this->db->trans_rollback();

                        $actionObj = new stdClass();
                        $actionObj->icon = 'fas fa-warning';
                        $actionObj->title = '';
                        $actionObj->message = 'Record Error, Payment amount not equal payment paid nettotal.';
                        $actionObj->url = '';
                        $actionObj->target = '_blank';
                        $actionObj->type = 'danger';

                        $actionJSON = json_encode($actionObj);
                        
                        $obj = new stdClass();
                        $obj->status = 0;
                        $obj->action = $actionJSON;

                        echo json_encode($obj);
                    }
                } else {
                    $this->db->trans_rollback();

                    $actionObj = new stdClass();
                    $actionObj->icon = 'fas fa-warning';
                    $actionObj->title = '';
                    $actionObj->message = 'Record Error';
                    $actionObj->url = '';
                    $actionObj->target = '_blank';
                    $actionObj->type = 'danger';

                    $actionJSON = json_encode($actionObj);
                    
                    $obj = new stdClass();
                    $obj->status = 0;
                    $obj->action = $actionJSON;

                    echo json_encode($obj);
                }
            } else {
                $actionObj = new stdClass();
                $actionObj->icon = 'fas fa-warning';
                $actionObj->title = '';
                $actionObj->message = 'Record Error, Batch no defined by system';
                $actionObj->url = '';
                $actionObj->target = '_blank';
                $actionObj->type = 'danger';

                $actionJSON = json_encode($actionObj);
                
                $obj = new stdClass();
                $obj->status = 0;
                $obj->action = $actionJSON;

                echo json_encode($obj);
            }
        } 
    }
    // **********************No Use These Function on 2024-02-21*********************
    public function Receivablesettleedit(){
        $recordID=$this->input->post('recordID');
        $userID=$_SESSION['userid'];
        $updatedatetime=date('Y-m-d H:i:s');

        $data = array(
            'editstatus' => '1',
            'updateuser'=> $userID, 
            'updatedatetime'=> $updatedatetime
        );

        $this->db->where('idtbl_receivable', $recordID);
        $this->db->update('tbl_receivable', $data);

        $this->db->select('tbl_receivable.*, tbl_company.company, tbl_company_branch.branch, tbl_customer.customer');
        $this->db->from('tbl_receivable');
        $this->db->join('tbl_company', 'tbl_company.idtbl_company = tbl_receivable.tbl_company_idtbl_company', 'left');
        $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_receivable.tbl_company_branch_idtbl_company_branch', 'left');
        $this->db->join('tbl_customer', 'tbl_customer.idtbl_customer = tbl_receivable.payer', 'left');
        $this->db->where('tbl_receivable.idtbl_receivable', $recordID);
        $this->db->where('tbl_receivable.status', 1);

        $respond=$this->db->get();

        $this->db->select('amount, narration, invoiceno');
        $this->db->from('tbl_receivable_info');
        $this->db->where('tbl_receivable_idtbl_receivable', $recordID);
        $this->db->where('status', 1);

        $respondinfo=$this->db->get();

        $html='';
        foreach($respondinfo->result() as $rowdatalist){
            $html.='
            <tr>
                <td class="d-none">'.$respond->row(0)->payer.'</td>
                <td>'.$respond->row(0)->customer.'</td>
                <td class="d-none">'.$rowdatalist->invoiceno.'</td>
                <td>'.$rowdatalist->invoiceno.'</td>
                <td class="text-right invbalamount">'.$rowdatalist->amount.'</td>
                <td class="text-right"><button type="button" class="btn btn-danger btn-sm btnremoverow"><i class="fas fa-times"></i></button></td>
            </tr>
            ';
        }

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_receivable;
        $obj->customerid=$respond->row(0)->payer;
        $obj->customer=$respond->row(0)->customer;
        $obj->amount=$respond->row(0)->amount;
        $obj->receivetype=$respond->row(0)->tbl_receivable_type_idtbl_receivable_type;
        if($respond->row(0)->tbl_receivable_type_idtbl_receivable_type==1){$obj->account=$respond->row(0)->tbl_account_detail_idtbl_account_detail;}
        else if($respond->row(0)->tbl_receivable_type_idtbl_receivable_type==2){$obj->account=$respond->row(0)->tbl_account_idtbl_account;}
        $obj->amount=$respond->row(0)->amount;
        $obj->narration=$respond->row(0)->narration;
        $obj->chequedate=$respond->row(0)->chequedate;
        $obj->chequeno=$respond->row(0)->chequeno;
        $obj->company=$respond->row(0)->company;
        $obj->companyid=$respond->row(0)->tbl_company_idtbl_company;
        $obj->branch=$respond->row(0)->branch;
        $obj->branchid=$respond->row(0)->tbl_company_branch_idtbl_company_branch;
        $obj->tabledata=$html;

        echo json_encode($obj);
    }
    // **********************No Use These Function on 2024-02-21*********************
    public function Getviewpostinfo(){
        $recordID=$this->input->post('recordID');
        $updatedatetime=date('Y-m-d H:i:s');

        $data = array(
            'postviewtime'=> $updatedatetime
        );

        $this->db->where('idtbl_receivable', $recordID);
        $this->db->where('poststatus', 0);
        $this->db->update('tbl_receivable', $data);

        $this->db->select('tbl_receivable.*, tbl_company.company, tbl_company_branch.branch, tbl_customer.customer, tbl_account.accountno AS `chartaccount`, tbl_account.accountname AS `chartaccountname`, tbl_account_detail.accountno AS `detailaccount`, tbl_account_detail.accountname AS `detailaccountname');
        $this->db->from('tbl_receivable');
        $this->db->join('tbl_company', 'tbl_company.idtbl_company = tbl_receivable.tbl_company_idtbl_company', 'left');
        $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_receivable.tbl_company_branch_idtbl_company_branch', 'left');
        $this->db->join('tbl_customer', 'tbl_customer.idtbl_customer = tbl_receivable.payer', 'left');
        $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_receivable.tbl_account_idtbl_account', 'left');
        $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_receivable.tbl_account_detail_idtbl_account_detail', 'left');
        $this->db->where('tbl_receivable.idtbl_receivable', $recordID);
        // $this->db->where('tbl_account_payable_main.status', 1);

        $respond=$this->db->get();

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
                <h6 class="small title-style my-3"><span>Receivable Invoice Information</span></h6>
                <table class="table  table-striped table-sm nowrap small">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Invoice No</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>';
                    foreach($respondinvoiceinfo->result() as $rowdatainfo){
                        $html.='
                        <tr>
                            <td>'.$respond->row(0)->customer.'</td>
                            <td>'.$rowdatainfo->invoiceno.'</td>
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
    public function Receivablesettleposting(){
        $recordID=$this->input->post('recordID');
        $updatedatetime=date('Y-m-d H:i:s');
        $userID=$_SESSION['userid'];

        $i=0;

        $this->db->select('recdate, batchno, amount, poststatus, status, editstatus, postviewtime, updatedatetime, tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch, tbl_master_idtbl_master, payer, tbl_account_idtbl_account, tbl_account_detail_idtbl_account_detail, narration, postdatedstatus, chequedate');
        $this->db->from('tbl_receivable');
        $this->db->where('idtbl_receivable', $recordID);
        $this->db->where('status', 1);

        $respond=$this->db->get();

        if($respond->row(0)->postdatedstatus==1 && $respond->row(0)->chequedate>date('Y-m-d')){
            $actionObj=new stdClass();
            $actionObj->icon='fas fa-warning';
            $actionObj->title='';
            $actionObj->message='Record Error, You cannot post a post-dated receivable.';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='danger';

            $actionJSON=json_encode($actionObj);
            
            $obj=new stdClass();
            $obj->status=0;
            $obj->action=$actionJSON;

            echo json_encode($obj);
        }
        else{
            if($respond->row(0)->poststatus==0 && $respond->row(0)->status==1 && $respond->row(0)->editstatus==0){
                if($respond->row(0)->postviewtime>$respond->row(0)->updatedatetime){
                    $this->db->trans_begin();
                    
                    $data = array(
                        'depositstatus'=> '1',
                        'poststatus'=> '1',
                        'postuser'=> $userID,
                        'postviewtime'=> NULL
                    );
            
                    $this->db->where('idtbl_receivable', $recordID);
                    $this->db->update('tbl_receivable', $data);

                    $i=1;
                    //Creditor account Transaction
                    $prefix=trans_prefix($respond->row(0)->tbl_company_idtbl_company, $respond->row(0)->tbl_company_branch_idtbl_company_branch);
                    $batchno=tr_batch_num($prefix, $respond->row(0)->tbl_company_branch_idtbl_company_branch);

                    //Get Creditor Account
                    $this->db->where('tbl_account_allocation.companybank', $respond->row(0)->tbl_company_idtbl_company);
                    $this->db->where('tbl_account_allocation.branchcompanybank', $respond->row(0)->tbl_company_branch_idtbl_company_branch);
                    // $this->db->where('tbl_account.tbl_account_type_idtbl_account_type', 2);
                    $this->db->where('tbl_account.specialcate', 35);
                    $this->db->where('tbl_account.status', 1);
                    $this->db->where('tbl_account_allocation.status', 1);
                    $this->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
                    $this->db->select('`tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`');
                    $this->db->from('tbl_account');
                    $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');

                    $respondcreditor=$this->db->get();

                    $datacredit = array(
                        'tradate'=> $respond->row(0)->recdate, 
                        'batchno'=> $batchno, 
                        'trabatchotherno'=> $respond->row(0)->batchno, 
                        'tratype'=> 'R', 
                        'seqno'=> $i, 
                        'crdr'=> 'C', 
                        'accamount'=> $respond->row(0)->amount, 
                        'narration'=> $respond->row(0)->narration, 
                        'totamount'=> $respond->row(0)->amount, 
                        'status'=> '1', 
                        'insertdatetime'=> $updatedatetime, 
                        'tbl_user_idtbl_user'=> $userID,
                        'tbl_account_idtbl_account'=> $respondcreditor->row(0)->idtbl_account,
                        'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
                        'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
                        'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
                    );
                    $this->db->insert('tbl_account_transaction', $datacredit);
            
                    $datacreditfull = array(
                        'tradate'=> $respond->row(0)->recdate, 
                        'batchno'=> $batchno, 
                        'tratype'=> 'R', 
                        'crdr'=> 'C', 
                        'accamount'=> $respond->row(0)->amount, 
                        'narration'=> $respond->row(0)->narration, 
                        'totamount'=> $respond->row(0)->amount, 
                        'status'=> '1', 
                        'insertdatetime'=> $updatedatetime, 
                        'tbl_user_idtbl_user'=> $userID,
                        'tbl_account_idtbl_account'=> $respondcreditor->row(0)->idtbl_account,
                        'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
                        'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
                        'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
                    );
                    $this->db->insert('tbl_account_transaction_full', $datacreditfull);

                    //Debit account Transaction

                    if(!empty($respond->row(0)->tbl_account_detail_idtbl_account_detail)){
                        $chartofaccountinfo=get_chart_account_acco_child_account($respond->row(0)->tbl_company_idtbl_company, $respond->row(0)->tbl_company_branch_idtbl_company_branch, $respond->row(0)->tbl_account_detail_idtbl_account_detail);
                        $chartofaccountID=$chartofaccountinfo->row(0)->idtbl_account;
                    }
                    else{
                        $chartofaccountID=$respond->row(0)->tbl_account_idtbl_account;
                    }

                    $i++;
                    $data = array(
                        'tradate'=> $respond->row(0)->recdate, 
                        'batchno'=> $batchno, 
                        'trabatchotherno'=> $respond->row(0)->batchno, 
                        'tratype'=> 'R', 
                        'seqno'=> $i, 
                        'crdr'=> 'D', 
                        'accamount'=> $respond->row(0)->amount, 
                        'narration'=> $respond->row(0)->narration, 
                        'totamount'=> $respond->row(0)->amount,
                        'status'=> '1', 
                        'insertdatetime'=> $updatedatetime, 
                        'tbl_user_idtbl_user'=> $userID,
                        'tbl_account_idtbl_account'=> $chartofaccountID,
                        'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
                        'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
                        'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
                    );

                    $this->db->insert('tbl_account_transaction', $data);

                    $datafull = array(
                        'tradate'=> $respond->row(0)->recdate, 
                        'batchno'=> $batchno, 
                        'tratype'=> 'R', 
                        'crdr'=> 'D', 
                        'accamount'=> $respond->row(0)->amount, 
                        'narration'=> $respond->row(0)->narration, 
                        'totamount'=> $respond->row(0)->amount,
                        'status'=> '1', 
                        'insertdatetime'=> $updatedatetime, 
                        'tbl_user_idtbl_user'=> $userID,
                        'tbl_account_idtbl_account'=> $respond->row(0)->tbl_account_idtbl_account,
                        'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
                        'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
                        'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
                    );

                    $this->db->insert('tbl_account_transaction_full', $datafull);

                    $this->db->trans_complete();

                    if ($this->db->trans_status() === TRUE) {
                        $this->db->trans_commit();
                        
                        $actionObj=new stdClass();
                        $actionObj->icon='fas fa-save';
                        $actionObj->title='';
                        $actionObj->message='Record Added Successfully';
                        $actionObj->url='';
                        $actionObj->target='_blank';
                        $actionObj->type='success';

                        $actionJSON=json_encode($actionObj);
                        
                        $obj=new stdClass();
                        $obj->status=1;
                        $obj->action=$actionJSON;

                        echo json_encode($obj);
                    } else {
                        $this->db->trans_rollback();

                        $actionObj=new stdClass();
                        $actionObj->icon='fas fa-warning';
                        $actionObj->title='';
                        $actionObj->message='Record Error';
                        $actionObj->url='';
                        $actionObj->target='_blank';
                        $actionObj->type='danger';

                        $actionJSON=json_encode($actionObj);
                        
                        $obj=new stdClass();
                        $obj->status=0;
                        $obj->action=$actionJSON;

                        echo json_encode($obj);
                    }
                }
                else{
                    $actionObj=new stdClass();
                    $actionObj->icon='fas fa-warning';
                    $actionObj->title='';
                    $actionObj->message='Record Error, Please check this record for information. Because this record was edited before you posted.';
                    $actionObj->url='';
                    $actionObj->target='_blank';
                    $actionObj->type='danger';

                    $actionJSON=json_encode($actionObj);
                    
                    $obj=new stdClass();
                    $obj->status=0;
                    $obj->action=$actionJSON;

                    echo json_encode($obj);
                }
            }
            else if($respond->row(0)->status==2){
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error, Record Deactivated. Kindly review the status of the record.';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='warning';

                $actionJSON=json_encode($actionObj);
                
                $obj=new stdClass();
                $obj->status=0;
                $obj->action=$actionJSON;

                echo json_encode($obj);
            }
            else if($respond->row(0)->editstatus==1){
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error, Record in editable mode. You cannot change anything about the record.';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);
                
                $obj=new stdClass();
                $obj->status=0;
                $obj->action=$actionJSON;

                echo json_encode($obj);
            }
            else if($respond->row(0)->poststatus==1){
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error, Record already posted.';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);
                
                $obj=new stdClass();
                $obj->status=0;
                $obj->action=$actionJSON;

                echo json_encode($obj);
            }
        }
    }
    public function Receivablesettlestatus($x, $y){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];
        $recordID=$x;
        $type=$y;
        $updatedatetime=date('Y-m-d H:i:s');

        if($type==1){
            $data = array(
                'status' => '1',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_receivable', $recordID);
            $this->db->update('tbl_receivable', $data);

            $datapay = array(
                'status' => '1',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('tbl_receivable_idtbl_receivable', $recordID);
            $this->db->update('tbl_receivable_info', $datapay);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
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
                redirect('Receivablesettle');                
            } else {
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
                redirect('Receivablesettle');
            }
        }
        else if($type==2){
            $data = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_receivable', $recordID);
            $this->db->update('tbl_receivable', $data);

            $datapay = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('tbl_receivable_idtbl_receivable', $recordID);
            $this->db->update('tbl_receivable_info', $datapay);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
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
                redirect('Receivablesettle');                
            } else {
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
                redirect('Receivablesettle');
            }
        }
        else if($type==3){
            $data = array(
                'status' => '3',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_receivable', $recordID);
            $this->db->update('tbl_receivable', $data);

            $datapay = array(
                'status' => '3',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('tbl_receivable_idtbl_receivable', $recordID);
            $this->db->update('tbl_receivable_info', $datapay);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
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
                redirect('Receivablesettle');                
            } else {
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
                redirect('Receivablesettle');
            }
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
            $this->db->select('`idtbl_receivable` AS `invoicereceiptno`');
            $this->db->from('tbl_receivable');
            $this->db->where('tbl_receivable.status', '1');
            if(!empty($printcustomer)){$this->db->where('tbl_receivable.payer', $printcustomer);}
            if(!empty($printdate)){$this->db->where('tbl_receivable.recdate', $printdate);}

            $respond=$this->db->get();

            echo json_encode($respond->result());
        }
    }
}