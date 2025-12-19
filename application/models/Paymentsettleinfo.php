<?php
class Paymentsettleinfo extends CI_Model{
    public function Getreceivabletype(){
        $this->db->select('idtbl_receivable_type, receivabletype');
        $this->db->from('tbl_receivable_type');
        $this->db->where('status', 1);

        $respond=$this->db->get();

        return $respond;
    }
    public function Getinvoiceaccosupplier(){
        $recordID=$this->input->post('recordID');

        // $this->db->select('`tbl_sales_info`.`idtbl_sales_info`, `tbl_sales_info`.`invno`, `tbl_sales_info`.`amount`, IFNULL(SUM(`tbl_receivable_info`.`amount`), 0) AS `sumpay`, (`tbl_sales_info`.`amount`-IFNULL(SUM(`tbl_receivable_info`.`amount`), 0)) AS `balpay`, `tbl_sales_info`.`tbl_customer_idtbl_customer`, `tbl_customer`.`customer`');
        $this->db->select('`tbl_expence_info`.`idtbl_expence_info`, `tbl_expence_info`.`grnno`, `tbl_expence_info`.`amount`, `tbl_expence_info`.`invamount`, IFNULL(SUM(CASE WHEN `tbl_account_paysettle_info`.`status` = 1 THEN `tbl_account_paysettle_info`.`amount` ELSE 0 END), 0) AS `sumpay`, (`tbl_expence_info`.`invamount`-IFNULL(SUM(CASE WHEN `tbl_account_paysettle_info`.`status` = 1 THEN `tbl_account_paysettle_info`.`amount` ELSE 0 END), 0)) AS `balpay`, `tbl_expence_info`.`tbl_supplier_idtbl_supplier`, `tbl_supplier`.`suppliername`');
        $this->db->from('tbl_expence_info');
        $this->db->join('tbl_account_paysettle_info', 'tbl_account_paysettle_info.invoiceno = tbl_expence_info.grnno', 'left');
        $this->db->join('tbl_supplier', 'tbl_supplier.idtbl_supplier = tbl_expence_info.tbl_supplier_idtbl_supplier', 'left');
        $this->db->where('tbl_expence_info.status', 1);
        $this->db->where('tbl_expence_info.paystatus', 0);
        $this->db->where('tbl_expence_info.poststatus', 1);
        $this->db->where('tbl_expence_info.tbl_supplier_idtbl_supplier', $recordID);
        $this->db->group_by('`tbl_expence_info`.`idtbl_expence_info`');

        $respond=$this->db->get();
        // print_r($this->db->last_query());

        $html='';
        $i=1;
        foreach($respond->result() as $rowdatalist){
            $this->db->select('IFNULL(SUM(`amount`), 0) AS `returnsum`');
            $this->db->from('tbl_account_paysettle_info');
            $this->db->where('status', 2);
            $this->db->where('invoiceno', $rowdatalist->grnno);

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
                    <td class="d-none">'.$rowdatalist->tbl_supplier_idtbl_supplier.'</td>
                    <td>'.$rowdatalist->suppliername.'</td>
                    <td class="d-none">'.$rowdatalist->grnno.'</td>
                    <td>'.$rowdatalist->grnno.'</td>
                    <td class="text-right">'.number_format($rowdatalist->invamount, 2).'</td>
                    <td class="text-right invbalamount">'.number_format($netbalpay, 2).'</td>
                </tr>
                ';
                $i++;
            }
        }
        echo $html;
    }
    public function Paymentsettleinsertupdate(){
        $userID=$_SESSION['userid'];
        $detailaccount=0;
        $chartaccount=0;
        $chequeissuestatus=0;
        $issuechequeID=0;

        $company=$this->input->post('company');
        $branch=$this->input->post('branch');
        $supplier=$this->input->post('supplier');
        $payabletype=$this->input->post('payabletype');
        $accounttype=$this->input->post('accounttype');
        if(!empty($this->input->post('chequedate'))){$chequedate=$this->input->post('chequedate');}else{$chequedate='';}
        $chartofdetailaccount=$this->input->post('chartofdetailaccount');
        $narration=$this->input->post('narration');
        $invoicepayamount= str_replace(',', '', $this->input->post('invoicepayamount'));
        $paiddate= $this->input->post('paiddate');
        $paidamount=str_replace(',', '', $this->input->post('paidamount'));
        $invoicedata=json_decode($this->input->post('tableData'));
        $postdated= $this->input->post('postdated');
        
        $chequecashamount=$paidamount;

        if($accounttype==1){$chartaccount=$chartofdetailaccount;}
        else if($accounttype==2){$detailaccount=$chartofdetailaccount;}

        $recordOption=$this->input->post('recordOption');
        if(!empty($this->input->post('recordID'))){$recordID=$this->input->post('recordID');}
        
        if($recordOption==1){
            $prefix=pay_prefix($company, $branch);
            $masterdata=get_account_period($company, $branch);
            $batchno=tr_batch_num($prefix, $branch);
            $masterID=$masterdata->idtbl_master;
            $payreceiptno = tr_batch_num('PAY'.date('y'), $branch);
            $payreceiptno = preg_replace('/^(.{5})00/', '$1', $payreceiptno);
        }

        $updatedatetime=date('Y-m-d H:i:s');
        $today=date('Y-m-d');

        $this->db->trans_begin();

        //Choose cheque no start   
        if($payabletype==2){
            $this->db->select('`idtbl_cheque_issue`');
            $this->db->from('tbl_cheque_issue');
            $this->db->join('tbl_cheque_info', 'tbl_cheque_info.idtbl_cheque_info = tbl_cheque_issue.tbl_cheque_info_idtbl_cheque_info', 'left');
            $this->db->where('tbl_cheque_info.tbl_account_idtbl_account', $chartofdetailaccount);
            $this->db->where('tbl_cheque_info.status', 1);
            $this->db->where('tbl_cheque_issue.chequeallocate', 0);
            $respondchequeissue=$this->db->get();

            if($respondchequeissue->num_rows()>0){
                $issuechequeID=$respondchequeissue->row(0)->idtbl_cheque_issue;

                $datachequeissue = array(
                    'chedate'=> $chequedate, 
                    'narration'=> $narration, 
                    'amount'=> $chequecashamount, 
                    'chequeallocate'=> '1', 
                    'updatedatetime'=> $updatedatetime, 
                    'updateuser'=> $userID
                );
                $this->db->where('idtbl_cheque_issue', $respondchequeissue->row(0)->idtbl_cheque_issue);
                $this->db->update('tbl_cheque_issue', $datachequeissue);

                $this->db->where('tbl_cheque_issue_idtbl_cheque_issue', $issuechequeID);
                $this->db->delete('tbl_account_paysettle_has_tbl_cheque_issue');

                $chequeissuestatus=1;
            }
            else{
                $this->db->select('tbl_bank_idtbl_bank, tbl_bank_branch_idtbl_bank_branch');
                $this->db->from('tbl_cheque_info');
                $this->db->where('tbl_account_idtbl_account', $chartofdetailaccount);
                $this->db->where('status', 1);
                $this->db->group_by("tbl_bank_idtbl_bank");
                $this->db->limit(1);

                $respondbank=$this->db->get();

                if ($respondbank->num_rows() > 0) {
                    $bankID=$respondbank->row(0)->tbl_bank_idtbl_bank;
                    $branchID=$respondbank->row(0)->tbl_bank_branch_idtbl_bank_branch;

                    $sqlcheque = "SELECT tbl_cheque_info.idtbl_cheque_info, IFNULL(LPAD(drv.chno+1, 6, '0'), tbl_cheque_info.startno) AS chno FROM tbl_cheque_info LEFT OUTER JOIN (SELECT tbl_cheque_info_idtbl_cheque_info, max(CAST(chequeno AS UNSIGNED)) AS chno FROM tbl_cheque_issue GROUP BY tbl_cheque_info_idtbl_cheque_info) AS drv ON tbl_cheque_info.idtbl_cheque_info=drv.tbl_cheque_info_idtbl_cheque_info WHERE tbl_cheque_info.tbl_bank_idtbl_bank=? AND tbl_cheque_info.tbl_bank_branch_idtbl_bank_branch=? AND tbl_account_idtbl_account=? AND IFNULL(drv.chno, 0)<CAST(tbl_cheque_info.endno AS UNSIGNED) AND tbl_cheque_info.status=? LIMIT 1";
                    $respondcheque=$this->db->query($sqlcheque, array($bankID, $branchID, $chartofdetailaccount, 1));

                    if(!empty($respondcheque->result())){
                        $chequeissuestatus=1;
                        $chequeno=$respondcheque->row(0)->chno;
                        $chequeinfoID=$respondcheque->row(0)->idtbl_cheque_info;

                        $datachequeissue = array(
                            'chedate'=> $chequedate, 
                            'chequeno'=> $chequeno, 
                            'narration'=> $narration, 
                            'amount'=> $chequecashamount, 
                            'chequeallocate'=> '1', 
                            'chequereturn'=> '0', 
                            'status'=> '1', 
                            'insertdatetime'=> $updatedatetime, 
                            'tbl_user_idtbl_user'=> $userID, 
                            'tbl_cheque_info_idtbl_cheque_info'=> $chequeinfoID
                        );

                        $this->db->insert('tbl_cheque_issue', $datachequeissue);

                        $issuechequeID=$this->db->insert_id();
                    }
                }
            }
        }
        else{
            $chequeissuestatus=1;
        }
        //Choose cheque no end
    
        if($chequeissuestatus==1){
            if(!empty($batchno)){
                $data = array(
                    'date'=> $paiddate, 
                    'paymentno'=> $payreceiptno, 
                    'batchno'=> $batchno, 
                    'supplier'=> $supplier, 
                    'totalpayment'=> $paidamount, 
                    'remark'=> $narration, 
                    'postdatedstatus'=> $postdated, 
                    'poststatus'=> '0', 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_receivable_type_idtbl_receivable_type'=> $payabletype,
                    'tbl_company_idtbl_company'=> $company,
                    'tbl_company_branch_idtbl_company_branch'=> $branch,
                    'tbl_master_idtbl_master'=> $masterID,
                    'tbl_account_idtbl_account'=> $chartaccount,
                    'tbl_account_detail_idtbl_account_detail'=> $detailaccount,
                );

                $this->db->insert('tbl_account_paysettle', $data);

                $payableID=$this->db->insert_id();

                foreach($invoicedata as $rowinvoicedata){
                    $narration=$rowinvoicedata->supplier.' - '.$rowinvoicedata->invoiceno;
                    $invoicetotal=str_replace(',', '', $rowinvoicedata->amount);

                    if($chequecashamount>=$invoicetotal){
                        $invoicepayamount=$invoicetotal;
                        $chequecashamount=$chequecashamount-$invoicetotal;
                    }
                    else{
                        $invoicepayamount=$chequecashamount;
                        $chequecashamount=0;
                    }

                    $datasub = array(
                        'batchno'=> $batchno, 
                        'narration'=> $narration, 
                        'amount'=> $invoicepayamount, 
                        'invoiceno'=> $rowinvoicedata->invid, 
                        'status'=> '1', 
                        'insertdatetime'=> $updatedatetime, 
                        'tbl_user_idtbl_user'=> $userID,
                        'tbl_account_paysettle_idtbl_account_paysettle'=> $payableID,
                    );

                    $this->db->insert('tbl_account_paysettle_info', $datasub);
                }

                $datachequehas = array(
                    'tbl_account_paysettle_idtbl_account_paysettle'=> $payableID, 
                    'tbl_cheque_issue_idtbl_cheque_issue'=> $issuechequeID,
                );

                $this->db->insert('tbl_account_paysettle_has_tbl_cheque_issue', $datachequehas);

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
                $this->db->trans_rollback();

                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error, Batch no defind by system';
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
            $this->db->trans_rollback();

            $actionObj=new stdClass();
            $actionObj->icon='fas fa-warning';
            $actionObj->title='';
            $actionObj->message='Record Error, Something wrong. Cheque detail not available';
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
    public function Getviewpostinfo(){
        $recordID=$this->input->post('recordID');
        $updatedatetime=date('Y-m-d H:i:s');

        $data = array(
            'postviewtime'=> $updatedatetime
        );

        $this->db->where('idtbl_account_paysettle', $recordID);
        $this->db->where('poststatus', 0);
        $this->db->update('tbl_account_paysettle', $data);

        $this->db->select('tbl_account_paysettle.*, tbl_company.company, tbl_company_branch.branch, tbl_supplier.suppliername, tbl_account.accountno AS `chartaccount`, tbl_account.accountname AS `chartaccountname`, tbl_account_detail.accountno AS `detailaccount`, tbl_account_detail.accountname AS `detailaccountname, tbl_cheque_issue.chedate, tbl_cheque_issue.chequeno');
        $this->db->from('tbl_account_paysettle');
        $this->db->join('tbl_company', 'tbl_company.idtbl_company = tbl_account_paysettle.tbl_company_idtbl_company', 'left');
        $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_account_paysettle.tbl_company_branch_idtbl_company_branch', 'left');
        $this->db->join('tbl_supplier', 'tbl_supplier.idtbl_supplier = tbl_account_paysettle.supplier', 'left');
        $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_account_paysettle.tbl_account_idtbl_account', 'left');
        $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_account_paysettle.tbl_account_detail_idtbl_account_detail', 'left');
        $this->db->join('tbl_account_paysettle_has_tbl_cheque_issue', 'tbl_account_paysettle_has_tbl_cheque_issue.tbl_account_paysettle_idtbl_account_paysettle = tbl_account_paysettle.idtbl_account_paysettle', 'left');
        $this->db->join('tbl_cheque_issue', 'tbl_cheque_issue.idtbl_cheque_issue = tbl_account_paysettle_has_tbl_cheque_issue.tbl_cheque_issue_idtbl_cheque_issue', 'left');
        $this->db->where('tbl_account_paysettle.idtbl_account_paysettle', $recordID);
        // $this->db->where('tbl_account_payable_main.status', 1);

        $respond=$this->db->get();

        $this->db->select('`invoiceno`, `narration`, `amount`');
        $this->db->from('tbl_account_paysettle_info');
        $this->db->where('tbl_account_paysettle_idtbl_account_paysettle', $recordID);
        // $this->db->where('tbl_account_payable.status', 1);

        $respondinvoiceinfo=$this->db->get();

        if($respond->row(0)->tbl_receivable_type_idtbl_receivable_type==1){
            if($respond->row(0)->tbl_account_detail_idtbl_account_detail>0){
                $accountno=$respond->row(0)->detailaccount; 
                $accountname=$respond->row(0)->detailaccountname;
            }
            else{
                $accountno=$respond->row(0)->chartaccount; 
                $accountname=$respond->row(0)->chartaccountname;
            }
            $chequedate='';
            $chequeno='';
        }
        else if($respond->row(0)->tbl_receivable_type_idtbl_receivable_type==2){
            if($respond->row(0)->tbl_account_detail_idtbl_account_detail>0){
                $accountno=$respond->row(0)->detailaccount; 
                $accountname=$respond->row(0)->detailaccountname;
            }
            else{
                $accountno=$respond->row(0)->chartaccount; 
                $accountname=$respond->row(0)->chartaccountname;
            }
            $chequedate=$respond->row(0)->chedate;
            $chequeno=$respond->row(0)->chequeno;
        }

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
        }
        // if($respond->row(0)->editstatus==1){
        // $html.='
        // <div class="row">
        //     <div class="col">
        //         <div class="alert alert-danger" role="alert">
        //         <i class="fas fa-exclamation-triangle mr-2"></i> Record in editable mode. You cannot change anything about the record.
        //         </div> 
        //     </div>
        // </div>';
        // }
        if($respond->row(0)->postdatedstatus==1 && $respond->row(0)->chedate > date('Y-m-d')){
        $html.='
        <div class="row">
            <div class="col">
                <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i> Post-dated Cheque. You cannot post this transaction until '.$respond->row(0)->chedate.'.
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
                <label class="small my-0">'.$respond->row(0)->date.'</label><br>
                <label class="small font-weight-bold my-0">Company/Branch: </label>
                <label class="small my-0">'.$respond->row(0)->company.'-'.$respond->row(0)->branch.'</label><br>
                <label class="small font-weight-bold my-0">Account No: </label>
                <label class="small my-0">'.$accountno.' - '.$accountname.'</label>
            </div>
            <div class="col">
                <label class="small font-weight-bold my-0">Supplier: </label>
                <label class="small my-0">'.$respond->row(0)->suppliername.'</label><br>
                <label class="small font-weight-bold my-0">Cheque Date: </label>
                <label class="small my-0">'.$chequedate.'</label><br>
                <label class="small font-weight-bold my-0">Cheque No: </label>
                <label class="small my-0">'.$chequeno.'</label><br>
                <label class="small font-weight-bold my-0">Amount: </label>
                <label class="small my-0">'.number_format($respond->row(0)->totalpayment, 2).'</label>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h6 class="small title-style my-3"><span>Receivable Invoice Information</span></h6>
                <table class="table  table-striped table-sm nowrap small">
                    <thead>
                        <tr>
                            <th>Supplier</th>
                            <th>Invoice No</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>';
                    foreach($respondinvoiceinfo->result() as $rowdatainfo){
                        $html.='
                        <tr>
                            <td>'.$respond->row(0)->suppliername.'</td>
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
        $obj->editablestatus=0;

        echo json_encode($obj);
    }
    public function Paymentsettleposting(){
        $recordID=$this->input->post('recordID');
        $updatedatetime=date('Y-m-d H:i:s');
        $userID=$_SESSION['userid'];

        $i=0;

        $this->db->select('tbl_account_paysettle.date, tbl_account_paysettle.batchno, tbl_account_paysettle.totalpayment, tbl_account_paysettle.poststatus, tbl_account_paysettle.status, tbl_account_paysettle.postviewtime, tbl_account_paysettle.postviewtime, tbl_account_paysettle.updatedatetime, tbl_account_paysettle.tbl_company_idtbl_company, tbl_account_paysettle.tbl_company_branch_idtbl_company_branch, tbl_account_paysettle.tbl_master_idtbl_master, tbl_account_paysettle.supplier, tbl_account_paysettle.tbl_account_idtbl_account, tbl_account_paysettle.tbl_account_detail_idtbl_account_detail, tbl_account_paysettle.remark, tbl_account_paysettle.postdatedstatus, tbl_cheque_issue.chedate, tbl_cheque_issue.chequeno');
        $this->db->from('tbl_account_paysettle');
        $this->db->join('tbl_account_paysettle_has_tbl_cheque_issue', 'tbl_account_paysettle_has_tbl_cheque_issue.tbl_account_paysettle_idtbl_account_paysettle = tbl_account_paysettle.idtbl_account_paysettle', 'left');
        $this->db->join('tbl_cheque_issue', 'tbl_cheque_issue.idtbl_cheque_issue = tbl_account_paysettle_has_tbl_cheque_issue.tbl_cheque_issue_idtbl_cheque_issue', 'left');
        $this->db->where('tbl_account_paysettle.idtbl_account_paysettle', $recordID);
        $this->db->where('tbl_account_paysettle.status', 1);

        $respond=$this->db->get();

        if($respond->row(0)->postdatedstatus==1 && $respond->row(0)->chedate > date('Y-m-d')){
            $actionObj=new stdClass();
            $actionObj->icon='fas fa-warning';
            $actionObj->title='';
            $actionObj->message='Record Error, You cannot post a post-dated Payment Settle.';
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
            if($respond->row(0)->poststatus==0 && $respond->row(0)->status==1){
                if($respond->row(0)->postviewtime>$respond->row(0)->updatedatetime){
                    $this->db->trans_begin();
                    
                    $data = array(
                        'completestatus'=> '1',
                        'poststatus'=> '1',
                        'postuser'=> $userID,
                        'postviewtime'=> NULL
                    );
            
                    $this->db->where('idtbl_account_paysettle', $recordID);
                    $this->db->update('tbl_account_paysettle', $data);

                    $i=1;
                    //Creditor account Transaction
                    $prefix=trans_prefix($respond->row(0)->tbl_company_idtbl_company, $respond->row(0)->tbl_company_branch_idtbl_company_branch);
                    $batchno=tr_batch_num($prefix, $respond->row(0)->tbl_company_branch_idtbl_company_branch);

                    //Get Creditor Account
                    $this->db->where('tbl_account_allocation.companybank', $respond->row(0)->tbl_company_idtbl_company);
                    $this->db->where('tbl_account_allocation.branchcompanybank', $respond->row(0)->tbl_company_branch_idtbl_company_branch);
                    // $this->db->where('tbl_account.tbl_account_type_idtbl_account_type', 2);
                    $this->db->where('tbl_account.specialcate', 34);
                    $this->db->where('tbl_account.status', 1);
                    $this->db->where('tbl_account_allocation.status', 1);
                    $this->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
                    $this->db->select('`tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`');
                    $this->db->from('tbl_account');
                    $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');

                    $respondcreditor=$this->db->get();

                    $datacredit = array(
                        'tradate'=> $respond->row(0)->date, 
                        'batchno'=> $batchno, 
                        'trabatchotherno'=> $respond->row(0)->batchno, 
                        'tratype'=> 'I', 
                        'seqno'=> $i, 
                        'crdr'=> 'D', 
                        'accamount'=> $respond->row(0)->totalpayment, 
                        'narration'=> $respond->row(0)->remark, 
                        'totamount'=> $respond->row(0)->totalpayment, 
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
                        'tradate'=> $respond->row(0)->date, 
                        'batchno'=> $batchno, 
                        'tratype'=> 'I', 
                        'crdr'=> 'D', 
                        'accamount'=> $respond->row(0)->totalpayment, 
                        'narration'=> $respond->row(0)->remark, 
                        'totamount'=> $respond->row(0)->totalpayment, 
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
                        'tradate'=> $respond->row(0)->date, 
                        'batchno'=> $batchno, 
                        'trabatchotherno'=> $respond->row(0)->batchno, 
                        'tratype'=> 'I', 
                        'seqno'=> $i, 
                        'crdr'=> 'C', 
                        'accamount'=> $respond->row(0)->totalpayment, 
                        'narration'=> $respond->row(0)->remark, 
                        'totamount'=> $respond->row(0)->totalpayment,
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
                        'tradate'=> $respond->row(0)->date, 
                        'batchno'=> $batchno, 
                        'tratype'=> 'I', 
                        'crdr'=> 'C', 
                        'accamount'=> $respond->row(0)->totalpayment, 
                        'narration'=> $respond->row(0)->remark, 
                        'totamount'=> $respond->row(0)->totalpayment,
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
            // else if($respond->row(0)->editstatus==1){
            //     $actionObj=new stdClass();
            //     $actionObj->icon='fas fa-warning';
            //     $actionObj->title='';
            //     $actionObj->message='Record Error, Record in editable mode. You cannot change anything about the record.';
            //     $actionObj->url='';
            //     $actionObj->target='_blank';
            //     $actionObj->type='danger';

            //     $actionJSON=json_encode($actionObj);
                
            //     $obj=new stdClass();
            //     $obj->status=0;
            //     $obj->action=$actionJSON;

            //     echo json_encode($obj);
            // }
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
    public function Paymentsettlestatus($x, $y){
        $userID=$_SESSION['userid'];
        $recordID=$x;
        $type=$y;
        $updatedatetime=date('Y-m-d H:i:s');

        if($type==1){
            $this->db->trans_begin();
            $data = array(
                'status' => '1',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_account_paysettle', $recordID);
            $this->db->update('tbl_account_paysettle', $data);

            $datapay = array(
                'status' => '1',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('tbl_account_paysettle_idtbl_account_paysettle', $recordID);
            $this->db->update('tbl_account_paysettle_info', $datapay);

            $this->db->select('tbl_account_paysettle_has_tbl_cheque_issue.tbl_cheque_issue_idtbl_cheque_issue');
            $this->db->from('tbl_account_paysettle_has_tbl_cheque_issue');
            $this->db->where('tbl_account_paysettle_has_tbl_cheque_issue.tbl_account_paysettle_idtbl_account_paysettle', $recordID);

            $respondcheque=$this->db->get();

            if($respondcheque->num_rows()>0){
                $datacheque = array(
                    'status' => '1',
                    'updateuser'=> $userID, 
                    'updatedatetime'=> $updatedatetime
                );

                $this->db->where('idtbl_cheque_issue', $respondcheque->row(0)->tbl_cheque_issue_idtbl_cheque_issue);
                $this->db->update('tbl_cheque_issue', $datacheque);
            }

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
                redirect('Paymentsettle');                
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
                redirect('Paymentsettle');
            }
        }
        else if($type==2){
            $this->db->trans_begin();

            $data = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_account_paysettle', $recordID);
            $this->db->update('tbl_account_paysettle', $data);

            $datapay = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('tbl_account_paysettle_idtbl_account_paysettle', $recordID);
            $this->db->update('tbl_account_paysettle_info', $datapay);

            $this->db->select('tbl_account_paysettle_has_tbl_cheque_issue.tbl_cheque_issue_idtbl_cheque_issue');
            $this->db->from('tbl_account_paysettle_has_tbl_cheque_issue');
            $this->db->where('tbl_account_paysettle_has_tbl_cheque_issue.tbl_account_paysettle_idtbl_account_paysettle', $recordID);

            $respondcheque=$this->db->get();

            if($respondcheque->num_rows()>0){
                $datacheque = array(
                    'status' => '2',
                    'updateuser'=> $userID, 
                    'updatedatetime'=> $updatedatetime
                );

                $this->db->where('idtbl_cheque_issue', $respondcheque->row(0)->tbl_cheque_issue_idtbl_cheque_issue);
                $this->db->update('tbl_cheque_issue', $datacheque);
            }

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
                redirect('Paymentsettle');                
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
                redirect('Paymentsettle');
            }
        }
        // else if($type==3){
        //     $data = array(
        //         'status' => '3',
        //         'updateuser'=> $userID, 
        //         'updatedatetime'=> $updatedatetime
        //     );

        //     $this->db->where('idtbl_account_paysettle', $recordID);
        //     $this->db->update('tbl_account_paysettle', $data);

        //     $datapay = array(
        //         'status' => '3',
        //         'updateuser'=> $userID, 
        //         'updatedatetime'=> $updatedatetime
        //     );

        //     $this->db->where('tbl_account_paysettle_idtbl_account_paysettle', $recordID);
        //     $this->db->update('tbl_account_paysettle_info', $datapay);

        //     $this->db->trans_complete();

        //     if ($this->db->trans_status() === TRUE) {
        //         $this->db->trans_commit();
                
        //         $actionObj=new stdClass();
        //         $actionObj->icon='fas fa-trash-alt';
        //         $actionObj->title='';
        //         $actionObj->message='Record Remove Successfully';
        //         $actionObj->url='';
        //         $actionObj->target='_blank';
        //         $actionObj->type='danger';

        //         $actionJSON=json_encode($actionObj);
                
        //         $this->session->set_flashdata('msg', $actionJSON);
        //         redirect('Receivablesettle');                
        //     } else {
        //         $this->db->trans_rollback();

        //         $actionObj=new stdClass();
        //         $actionObj->icon='fas fa-warning';
        //         $actionObj->title='';
        //         $actionObj->message='Record Error';
        //         $actionObj->url='';
        //         $actionObj->target='_blank';
        //         $actionObj->type='danger';

        //         $actionJSON=json_encode($actionObj);
                
        //         $this->session->set_flashdata('msg', $actionJSON);
        //         redirect('Receivablesettle');
        //     }
        // }
    }
    public function Getinvrecno(){
        $printtype=$this->input->post('printtype');
        $printsupplier=$this->input->post('printsupplier');
        $printdate=$this->input->post('printdate');

        if($printtype==1){
            $this->db->select('`tbl_account_paysettle_info`.`invoiceno` AS `invoicereceiptno`');
            $this->db->from('tbl_account_paysettle_info');
            $this->db->join('tbl_account_paysettle', 'tbl_account_paysettle.idtbl_account_paysettle = tbl_account_paysettle_info.tbl_account_paysettle_idtbl_account_paysettle', 'left');
            $this->db->where('tbl_account_paysettle_info.status', '1');
            $this->db->where('tbl_account_paysettle.status', '1');
            if(!empty($printsupplier)){$this->db->where('tbl_account_paysettle.supplier', $printsupplier);}
            if(!empty($printdate)){$this->db->where('tbl_account_paysettle.date', $printdate);}
            $this->db->group_by('`tbl_account_paysettle_info`.`invoiceno`');

            $respond=$this->db->get();

            echo json_encode($respond->result());
        }
        else{
            $this->db->select('`paymentno` AS `invoicereceiptno`');
            $this->db->from('tbl_account_paysettle');
            $this->db->where('tbl_account_paysettle.status', '1');
            if(!empty($printsupplier)){$this->db->where('tbl_account_paysettle.supplier', $printsupplier);}
            if(!empty($printdate)){$this->db->where('tbl_account_paysettle.date', $printdate);}

            $respond=$this->db->get();

            echo json_encode($respond->result());
        }
    }
    public function Paymentsettlecancel(){
        $recordID=$this->input->post('recordID');
        $chequecancel=$this->input->post('chequecancel');
        $updatedatetime=date('Y-m-d H:i:s');
        $userID=$_SESSION['userid'];

        $this->db->trans_begin();

        $data = array(
            'status' => '3',
            'updateuser'=> $userID, 
            'updatedatetime'=> $updatedatetime
        );

        $this->db->where('idtbl_account_paysettle', $recordID);
        $this->db->update('tbl_account_paysettle', $data);

        $datapay = array(
            'status' => '3',
            'updateuser'=> $userID, 
            'updatedatetime'=> $updatedatetime
        );

        $this->db->where('tbl_account_paysettle_idtbl_account_paysettle', $recordID);
        $this->db->update('tbl_account_paysettle_info', $datapay);

        if($chequecancel==1){
            //Cancel Cheque Issue
            $this->db->select('tbl_account_paysettle_has_tbl_cheque_issue.tbl_cheque_issue_idtbl_cheque_issue');
            $this->db->from('tbl_account_paysettle_has_tbl_cheque_issue');
            $this->db->where('tbl_account_paysettle_has_tbl_cheque_issue.tbl_account_paysettle_idtbl_account_paysettle', $recordID);

            $respondcheque=$this->db->get();

            if($respondcheque->num_rows()>0){
                $datacheque = array(
                    'status' => '3',
                    'updateuser'=> $userID, 
                    'updatedatetime'=> $updatedatetime
                );

                $this->db->where('idtbl_cheque_issue', $respondcheque->row(0)->tbl_cheque_issue_idtbl_cheque_issue);
                $this->db->update('tbl_cheque_issue', $datacheque);
            }
        }
        else{
            $this->db->select('tbl_account_paysettle_has_tbl_cheque_issue.tbl_cheque_issue_idtbl_cheque_issue');
            $this->db->from('tbl_account_paysettle_has_tbl_cheque_issue');
            $this->db->where('tbl_account_paysettle_has_tbl_cheque_issue.tbl_account_paysettle_idtbl_account_paysettle', $recordID);

            $respondcheque=$this->db->get();

            if($respondcheque->num_rows()>0){
                $datacheque = array(
                    'chequeallocate' => '0',
                    'updateuser'=> $userID, 
                    'updatedatetime'=> $updatedatetime
                );

                $this->db->where('idtbl_cheque_issue', $respondcheque->row(0)->tbl_cheque_issue_idtbl_cheque_issue);
                $this->db->update('tbl_cheque_issue', $datacheque);
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            
            $actionObj=new stdClass();
            $actionObj->icon='fas fa-trash-alt';
            $actionObj->title='';
            $actionObj->message='Record cancel Successfully';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='danger';

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
}