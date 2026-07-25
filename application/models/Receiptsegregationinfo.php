<?php
class Receiptsegregationinfo extends CI_Model{
    public function Receiptsegregationinsertupdate(){
        $userID          = $_SESSION['userid'];
        $company         = $this->input->post('company');
        $branch          = $this->input->post('branch');
        $customer        = !empty($this->input->post('customer')) ? $this->input->post('customer') : '';
        $invoice         = !empty($this->input->post('invoice'))  ? $this->input->post('invoice')  : '';
        $invoiceamount   = $this->input->post('invoiceamount');
        $invoicedate     = $this->input->post('invoicedate');
        $receremark     = $this->input->post('receremark');
        $segregationdata = $this->input->post('tableData');
        $recordOption    = $this->input->post('recordOption');
        $recordID        = !empty($this->input->post('recordID')) ? $this->input->post('recordID') : '';
        $salesrecordID        = !empty($this->input->post('salesrecordID')) ? $this->input->post('salesrecordID') : '';

        $updatedatetime = date('Y-m-d H:i:s');
        $today          = date('Y-m-d');

        if ($recordOption == 1) {

            // ── INSERT ────────────────────────────────────────────────────────────
            try {
                $masterdata = get_account_period_acco_date($company, $branch, $invoicedate);

                if (empty($masterdata) || empty($masterdata->idtbl_master)) {
                    throw new Exception('Record Error, Account period not found for the given date');
                }

                $prefix   = generate_prefix($company, $branch, $invoicedate, 'AR');
                $batchno  = tr_batch_num($prefix, $branch);
                $masterID = $masterdata->idtbl_master ? $masterdata->idtbl_master : '';

                if (empty($batchno)) {
                    throw new Exception('Record Error, Batch no defined by system');
                }

                $this->db->trans_begin();

                $datasales = array(
                    'saletype'=>'2', 
                    'salecode'=>'OTH', 
                    'invno'=>$invoice, 
                    'invdate'=>$invoicedate, 
                    'amount'=>$invoiceamount, 
                    'invamount'=>$invoiceamount, 
                    'paystatus'=>'0', 
                    'poststatus'=>'0', 
                    'remark'=>$receremark, 
                    'status'=>'1', 
                    'insertdatetime'=>$updatedatetime, 
                    'tbl_user_idtbl_user'=>$userID, 
                    'tbl_customer_idtbl_customer'=>$customer,
                    'tbl_company_idtbl_company'=>$company,
                    'tbl_company_branch_idtbl_company_branch'=>$branch
                );

                $this->db->insert('tbl_sales_info', $datasales);

                $data = array(
                    'tradate'                                 => $today,
                    'batchno'                                 => $batchno,
                    'customer'                                => $customer,
                    'receiptno'                               => $invoice,
                    'amount'                                  => $invoiceamount,
                    'poststatus'                              => '0',
                    'status'                                  => '1',
                    'insertdatetime'                          => $updatedatetime,
                    'tbl_user_idtbl_user'                     => $userID,
                    'tbl_company_idtbl_company'               => $company,
                    'tbl_company_branch_idtbl_company_branch' => $branch,
                    'tbl_master_idtbl_master'                 => $masterID
                );

                $this->db->insert('tbl_account_receivable_main', $data);
                $receivablemainID = $this->db->insert_id();

                if (!$receivablemainID) {
                    throw new Exception('Failed to insert main receivable record');
                }

                foreach ($segregationdata as $rowsegregationdata) {
                    $chartofaccount       = '';
                    $chartofdetailaccount = '';

                    if ($rowsegregationdata['col_7'] == 1)      { $chartofaccount       = $rowsegregationdata['col_1']; }
                    else if ($rowsegregationdata['col_7'] == 2) { $chartofdetailaccount = $rowsegregationdata['col_1']; }

                    if ($rowsegregationdata['col_4'] == 'D')      { $amount = $rowsegregationdata['col_5']; }
                    else if ($rowsegregationdata['col_4'] == 'C') { $amount = $rowsegregationdata['col_6']; }

                    $datasub = array(
                        'tradate'                                                   => $today,
                        'batchno'                                                   => $batchno,
                        'tratype'                                                   => $rowsegregationdata['col_4'],
                        'amount'                                                    => $amount,
                        'narration'                                                 => $rowsegregationdata['col_3'],
                        'status'                                                    => '1',
                        'insertdatetime'                                            => $updatedatetime,
                        'tbl_user_idtbl_user'                                       => $userID,
                        'tbl_master_idtbl_master'                                   => $masterID,
                        'tbl_company_idtbl_company'                                 => $company,
                        'tbl_company_branch_idtbl_company_branch'                   => $branch,
                        'tbl_account_receivable_main_idtbl_account_receivable_main' => $receivablemainID,
                        'tbl_account_idtbl_account'                                 => $chartofaccount,
                        'tbl_account_detail_idtbl_account_detail'                   => $chartofdetailaccount
                    );

                    $this->db->insert('tbl_account_receivable', $datasub);
                }

                $this->db->trans_complete();

                if ($this->db->trans_status() === TRUE) {
                    $this->db->trans_commit();
                    $this->_jsonResponse(1, 'fas fa-save', 'Record Added Successfully', 'success');
                } else {
                    $this->db->trans_rollback();
                    throw new Exception('Record Error');
                }

            } catch (Exception $e) {
                if ($this->db->trans_enabled) {
                    $this->db->trans_rollback();
                }
                $this->_jsonResponse(0, 'fas fa-warning', $e->getMessage(), 'danger');
            }

        } else {

            // ── UPDATE ────────────────────────────────────────────────────────────
            try {
                if (empty($recordID)) {
                    throw new Exception('Record ID is required for update');
                }

                $this->db->trans_begin();

                $this->db->select('poststatus');
                $this->db->from('tbl_sales_info');
                $this->db->where('idtbl_sales_info', $salesrecordID);
                $this->db->where('status', 1);

                $respondsalesinfo=$this->db->get();

                if ($respondsalesinfo->row(0)->poststatus != 0) {
                    $this->db->trans_commit();
                    throw new Exception('Record Error. This record already posted.');
                }

                $datasales = array(
                    'invno'=>$invoice, 
                    'amount'=>$invoiceamount, 
                    'invamount'=>$invoiceamount, 
                    'remark'=>$receremark, 
                    'editstatus' => '0',
                    'updateuser'=> $userID, 
                    'updatedatetime'=> $updatedatetime,
                );

                $this->db->where('idtbl_sales_info', $salesrecordID);
                $this->db->update('tbl_sales_info', $datasales);

                $this->db->select('batchno, tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch, tbl_master_idtbl_master, poststatus');
                $this->db->from('tbl_account_receivable_main');
                $this->db->where('idtbl_account_receivable_main', $recordID);
                $this->db->where('status', 1);
                $respond = $this->db->get();

                if (!$respond || $respond->num_rows() == 0) {
                    throw new Exception('Record not found');
                }

                $existingRecord = $respond->row(0);

                if ($existingRecord->poststatus != 0) {
                    $this->db->trans_commit();
                    throw new Exception('Record Error. This record already posted.');
                }

                $this->db->where('tbl_account_receivable_main_idtbl_account_receivable_main', $recordID);
                $this->db->delete('tbl_account_receivable');

                $data = array(
                    'editstatus'     => '0',
                    'updateuser'     => $userID,
                    'updatedatetime' => $updatedatetime
                );

                $this->db->where('idtbl_account_receivable_main', $recordID);
                $this->db->update('tbl_account_receivable_main', $data);

                foreach ($segregationdata as $rowsegregationdata) {
                    $chartofaccount       = '';
                    $chartofdetailaccount = '';

                    if ($rowsegregationdata['col_7'] == 1)      { $chartofaccount       = $rowsegregationdata['col_1']; }
                    else if ($rowsegregationdata['col_7'] == 2) { $chartofdetailaccount = $rowsegregationdata['col_1']; }

                    if ($rowsegregationdata['col_4'] == 'D')      { $amount = $rowsegregationdata['col_5']; }
                    else if ($rowsegregationdata['col_4'] == 'C') { $amount = $rowsegregationdata['col_6']; }

                    $datasub = array(
                        'tradate'                                                   => $today,
                        'batchno'                                                   => $existingRecord->batchno,
                        'tratype'                                                   => $rowsegregationdata['col_4'],
                        'amount'                                                    => $amount,
                        'narration'                                                 => $rowsegregationdata['col_3'],
                        'editstatus'                                                => '0',
                        'status'                                                    => '1',
                        'insertdatetime'                                            => $updatedatetime,
                        'tbl_user_idtbl_user'                                       => $userID,
                        'tbl_master_idtbl_master'                                   => $existingRecord->tbl_master_idtbl_master,
                        'tbl_company_idtbl_company'                                 => $company,
                        'tbl_company_branch_idtbl_company_branch'                   => $branch,
                        'tbl_account_receivable_main_idtbl_account_receivable_main' => $recordID,
                        'tbl_account_idtbl_account'                                 => $chartofaccount,
                        'tbl_account_detail_idtbl_account_detail'                   => $chartofdetailaccount
                    );

                    $this->db->insert('tbl_account_receivable', $datasub);
                }

                $this->db->trans_complete();

                if ($this->db->trans_status() === TRUE) {
                    $this->db->trans_commit();
                    $this->_jsonResponse(1, 'fas fa-save', 'Record Updated Successfully', 'success');
                } else {
                    $this->db->trans_rollback();
                    throw new Exception('Record Error');
                }

            } catch (Exception $e) {
                if ($this->db->trans_enabled) {
                    $this->db->trans_rollback();
                }
                $this->_jsonResponse(0, 'fas fa-warning', $e->getMessage(), 'danger');
            }
        }
    }
    public function Receiptsegregationstatus($x, $y){
        $userID         = $_SESSION['userid'];
        $recordID       = $x;
        $type           = $y;
        $updatedatetime = date('Y-m-d H:i:s');

        // ── Type config map ───────────────────────────────────────────────────────
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
            if (empty($recordID)) {
                throw new Exception('Record ID is required');
            }

            if (!array_key_exists($type, $typeConfig)) {
                throw new Exception('Invalid status type provided');
            }

            $config = $typeConfig[$type];

            $this->db->trans_begin();

            $data = array(
                'status'         => $config['status'],
                'updateuser'     => $userID,
                'updatedatetime' => $updatedatetime
            );

            $this->db->where('idtbl_account_receivable_main', $recordID);
            $this->db->update('tbl_account_receivable_main', $data);

            $datareceive = array(
                'status'         => $config['status'],
                'updateuser'     => $userID,
                'updatedatetime' => $updatedatetime
            );

            $this->db->where('tbl_account_receivable_main_idtbl_account_receivable_main', $recordID);
            $this->db->update('tbl_account_receivable', $datareceive);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();

                $actionObj          = new stdClass();
                $actionObj->icon    = $config['icon'];
                $actionObj->title   = '';
                $actionObj->message = $config['message'];
                $actionObj->url     = '';
                $actionObj->target  = '_blank';
                $actionObj->type    = $config['type'];

                $this->session->set_flashdata('msg', json_encode($actionObj));
                redirect('Receiptsegregation');
            } else {
                $this->db->trans_rollback();
                throw new Exception('Record Error');
            }

        } catch (Exception $e) {
            if ($this->db->trans_enabled) {
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
            redirect('Receiptsegregation');
        }
    }
    public function Receiptsegregationedit(){
        $recordID=$this->input->post('recordID');
        $userID=$_SESSION['userid'];
        $updatedatetime=date('Y-m-d H:i:s');

        $configdata = getconfigdata('receivable_search');

		$tablename = $configdata->row(0)->tbl_name;
		$column1   = $configdata->row(0)->col_name;
		$column2   = $configdata->row(1)->col_name;

        $has_table = !empty($tablename) ? 1 : 0;

        $data = array(
            'editstatus' => '1',
            'updateuser'=> $userID, 
            'updatedatetime'=> $updatedatetime
        );

        $this->db->where('idtbl_account_receivable_main', $recordID);
        $this->db->update('tbl_account_receivable_main', $data);

        $datapay = array(
            'editstatus' => '1',
            'updateuser'=> $userID, 
            'updatedatetime'=> $updatedatetime
        );

        $this->db->where('tbl_account_receivable_main_idtbl_account_receivable_main', $recordID);
        $this->db->update('tbl_account_receivable', $datapay);

        $this->db->select("tbl_account_receivable_main.*, tbl_company.company, tbl_company_branch.branch, IF($has_table = 0, '', $tablename.$column2) AS customername");
        $this->db->from('tbl_account_receivable_main');
        $this->db->join('tbl_company', 'tbl_company.idtbl_company = tbl_account_receivable_main.tbl_company_idtbl_company', 'left');
        $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_account_receivable_main.tbl_company_branch_idtbl_company_branch', 'left');
        if(!empty($tablename)){
            $this->db->join("$tablename", "$tablename.$column1 = tbl_account_receivable_main.customer", 'left');
        }
        $this->db->where('tbl_account_receivable_main.idtbl_account_receivable_main', $recordID);
        $this->db->where('tbl_account_receivable_main.status', 1);

        $respond=$this->db->get();

        $this->db->select('tbl_account_receivable.amount, tbl_account_receivable.narration, tbl_account_receivable.tbl_account_idtbl_account, tbl_account_receivable.tbl_account_detail_idtbl_account_detail, tbl_account_receivable.tratype, tbl_account_detail.accountno, tbl_account_detail.accountname, tbl_account.accountno AS chartaccountno, tbl_account.accountname AS chartaccountname');
        $this->db->from('tbl_account_receivable');
        $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_account_receivable.tbl_account_detail_idtbl_account_detail', 'left');
        $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_account_receivable.tbl_account_idtbl_account', 'left');
        $this->db->where('tbl_account_receivable.tbl_account_receivable_main_idtbl_account_receivable_main', $recordID);
        $this->db->where('tbl_account_receivable.status', 1);

        $respondinfo=$this->db->get();

        $this->db->select('idtbl_sales_info');
        $this->db->from('tbl_sales_info');
        $this->db->where('status', 1);
        $this->db->where('invno', $respond->row(0)->receiptno);
        $respondsales = $this->db->get();

        $datasales = array(
            'editstatus' => '1',
            'updateuser'=> $userID, 
            'updatedatetime'=> $updatedatetime
        );

        $this->db->where('idtbl_sales_info', $respondsales->row(0)->idtbl_sales_info);
        $this->db->update('tbl_sales_info', $datasales);

        $this->db->select("tbl_sales_info.*, tbl_company.company, tbl_company_branch.branch, IF($has_table = 0, '', $tablename.$column2) AS customer");
        $this->db->from('tbl_sales_info');
        $this->db->join('tbl_company', 'tbl_company.idtbl_company = tbl_sales_info.tbl_company_idtbl_company', 'left');
        $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_sales_info.tbl_company_branch_idtbl_company_branch', 'left');
        if(!empty($tablename)){
            $this->db->join("$tablename", "$tablename.$column1 = tbl_sales_info.tbl_customer_idtbl_customer", 'left');
        }
        $this->db->where('tbl_sales_info.idtbl_sales_info', $respondsales->row(0)->idtbl_sales_info);
        $this->db->where('tbl_sales_info.status', 1);

        $respondsales=$this->db->get();

        $html='';
        foreach($respondinfo->result() as $rowdatalist){
            if($rowdatalist->tratype=='D'){$debitamount=$rowdatalist->amount;$creditamount='';}
            else if($rowdatalist->tratype=='C'){$creditamount=$rowdatalist->amount;$debitamount='';}
            
            $html.='
            <tr>
                <td class="d-none">';
                if(!empty($rowdatalist->tbl_account_detail_idtbl_account_detail)){
                    $html.=$rowdatalist->tbl_account_detail_idtbl_account_detail;
                    $accounttype=2;
                }
                else{
                    $html.=$rowdatalist->tbl_account_idtbl_account;
                    $accounttype=1;
                }
                $html.='</td>
                <td>';
                if(!empty($rowdatalist->tbl_account_detail_idtbl_account_detail)){
                    $html.=$rowdatalist->accountname.' - '.$rowdatalist->accountno;
                }
                else{
                    $html.=$rowdatalist->chartaccountname.' - '.$rowdatalist->chartaccountno;
                }
                $html.='</td>
                <td>'.$rowdatalist->narration.'</td>
                <td>'.$rowdatalist->tratype.'</td>
                <td class="text-right debitamount">'.$debitamount.'</td>
                <td class="text-right creditamount">'.$creditamount.'</td>
                <td class="d-none">'.$accounttype.'</td>
            </tr>
            ';
        }

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_account_receivable_main;
        $obj->customer=$respond->row(0)->customer;
        $obj->customername=$respond->row(0)->customername;
        $obj->receiptno=$respond->row(0)->receiptno;
        $obj->amount=$respond->row(0)->amount;

        $obj->invoicedate=$respondsales->row(0)->invdate;
        $obj->remark=$respondsales->row(0)->remark;
        $obj->salesinfoID=$respondsales->row(0)->idtbl_sales_info;

        $obj->company=$respond->row(0)->company;
        $obj->companyid=$respond->row(0)->tbl_company_idtbl_company;
        $obj->branch=$respond->row(0)->branch;
        $obj->branchid=$respond->row(0)->tbl_company_branch_idtbl_company_branch;
        $obj->tabledata=$html;

        echo json_encode($obj);
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

        $this->db->where('idtbl_account_receivable_main', $recordID);
        $this->db->where('poststatus', 0);
        $this->db->update('tbl_account_receivable_main', $data);

        $this->db->select("tbl_account_receivable_main.*, tbl_company.company, tbl_company_branch.branch, IF($has_table = 0, '', $tablename.$column2) AS customer");
        $this->db->from('tbl_account_receivable_main');
        $this->db->join('tbl_company', 'tbl_company.idtbl_company = tbl_account_receivable_main.tbl_company_idtbl_company', 'left');
        $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_account_receivable_main.tbl_company_branch_idtbl_company_branch', 'left');
        if(!empty($tablename)){
            $this->db->join("$tablename", "$tablename.$column1 = tbl_account_receivable_main.customer", 'left');
        }
        $this->db->where('tbl_account_receivable_main.idtbl_account_receivable_main', $recordID);
        // $this->db->where('tbl_account_receivable_main.status', 1);

        $respond=$this->db->get();

        $this->db->select('tbl_account_receivable.*, tbl_account_detail.accountno, tbl_account_detail.accountname, tbl_account.accountno AS chartaccountno, tbl_account.accountname AS chartaccountname');
        $this->db->from('tbl_account_receivable');
        $this->db->join('tbl_account_receivable_main', 'tbl_account_receivable_main.idtbl_account_receivable_main = tbl_account_receivable.tbl_account_receivable_main_idtbl_account_receivable_main', 'left');
        $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_account_receivable.tbl_account_detail_idtbl_account_detail', 'left');
        $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_account_receivable.tbl_account_idtbl_account', 'left');
        $this->db->where('tbl_account_receivable_main.idtbl_account_receivable_main', $recordID);
        // $this->db->where('tbl_account_receivable.status', 1);

        $respondpayinfo=$this->db->get();

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
        }
        $html.='
        <div class="row">
            <div class="col">
                <label class="small font-weight-bold my-0">Batch No: </label>
                <label class="small my-0">'.$respond->row(0)->batchno.'</label><br>
                <label class="small font-weight-bold my-0">Date: </label>
                <label class="small my-0">'.$respond->row(0)->tradate.'</label><br>
                <label class="small font-weight-bold my-0">Company/Branch: </label>
                <label class="small my-0">'.$respond->row(0)->company.'-'.$respond->row(0)->branch.'</label>
            </div>
            <div class="col">
                <label class="small font-weight-bold my-0">customer: </label>
                <label class="small my-0">'.$respond->row(0)->customer.'</label><br>
                <label class="small font-weight-bold my-0">Invoice No: </label>
                <label class="small my-0">'.$respond->row(0)->receiptno.'</label><br>
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
    public function Receiptsegregationposting(){
        try {
            $recordID       = $this->input->post('recordID');
            $updatedatetime = date('Y-m-d H:i:s');
            $userID         = $_SESSION['userid'];
            $i              = 0;

            if (empty($recordID)) {
                throw new Exception('Record ID is required');
            }

            $this->db->select('tbl_account_receivable_main.tradate, tbl_account_receivable_main.batchno, tbl_account_receivable_main.amount, tbl_account_receivable_main.poststatus, tbl_account_receivable_main.status, tbl_account_receivable_main.editstatus, tbl_account_receivable_main.postviewtime, tbl_account_receivable_main.updatedatetime, tbl_account_receivable_main.tbl_company_idtbl_company, tbl_account_receivable_main.tbl_company_branch_idtbl_company_branch, tbl_account_receivable_main.tbl_master_idtbl_master, tbl_account_receivable_main.`customer`, tbl_account_receivable_main.`receiptno`, tbl_sales_info.invdate');
            $this->db->from('tbl_account_receivable_main');
            $this->db->join('tbl_sales_info', 'tbl_sales_info.invno = tbl_account_receivable_main.receiptno', 'left');
            $this->db->where('tbl_account_receivable_main.idtbl_account_receivable_main', $recordID);
            $this->db->where('tbl_account_receivable_main.status', 1);

            $respond = $this->db->get();

            if (!$respond || $respond->num_rows() == 0) {
                throw new Exception('Record not found');
            }

            $record = $respond->row(0);

            // ── Status validation checks ──────────────────────────────────────────
            if ($record->status == 2) {
                throw new Exception('Record Error, Record Deactivated. Kindly review the status of the record.');
            }

            if ($record->editstatus == 1) {
                throw new Exception('Record Error, Record in editable mode. You cannot change anything about the record.');
            }

            if ($record->poststatus == 1) {
                throw new Exception('Record Error, Record already posted.');
            }

            if (!($record->poststatus == 0 && $record->status == 1 && $record->editstatus == 0)) {
                throw new Exception('Record Error, Invalid record state for posting.');
            }

            if ($record->postviewtime <= $record->updatedatetime) {
                throw new Exception('Record Error, Please check this record for information. Because this record was edited before you posted.');
            }

            // ── Begin Transaction ─────────────────────────────────────────────────
            $this->db->trans_begin();

            $company = $record->tbl_company_idtbl_company;
            $branch  = $record->tbl_company_branch_idtbl_company_branch;

            $prefix  = generate_prefix($company, $branch, $record->invdate, 'AT');
            $batchno = tr_batch_num($prefix, $branch);

            if (empty($batchno)) {
                throw new Exception('Record Error, Batch no could not be defined by system');
            }

            // Update main receivable post status
            $data = array(
                'poststatus'   => '1',
                'postuser'     => $userID,
                'postviewtime' => NULL
            );

            $this->db->where('idtbl_account_receivable_main', $recordID);
            $this->db->update('tbl_account_receivable_main', $data);

            // Update sales info
            $datasale = array(
                'poststatus'    => '1',
                'updateuser'    => $userID,
                'updatedatetime' => $updatedatetime
            );

            $this->db->where('invno', $record->receiptno);
            $this->db->where('tbl_customer_idtbl_customer', $record->customer);
            $this->db->update('tbl_sales_info', $datasale);

            $i = 1;

            // Fetch receivable detail lines
            $this->db->select('`idtbl_account_receivable`, `tradate`, `batchno`, `tratype`, `amount`, `narration`, `tbl_master_idtbl_master`, `tbl_company_idtbl_company`, `tbl_company_branch_idtbl_company_branch`, `tbl_account_idtbl_account`, `tbl_account_detail_idtbl_account_detail`');
            $this->db->from('tbl_account_receivable');
            $this->db->where('tbl_account_receivable_main_idtbl_account_receivable_main', $recordID);
            $this->db->where('status', 1);

            $responddetail = $this->db->get();

            if (!$responddetail || $responddetail->num_rows() == 0) {
                throw new Exception('No receivable detail lines found for this record');
            }

            foreach ($responddetail->result() as $rowdetail) {
                $i++;

                $receivedetailID = $rowdetail->idtbl_account_receivable;
                $tradate         = $rowdetail->tradate;
                $segbatchno      = $rowdetail->batchno;
                $detailaccount   = $rowdetail->tbl_account_detail_idtbl_account_detail;
                $chartaccount    = $rowdetail->tbl_account_idtbl_account;
                $company         = $rowdetail->tbl_company_idtbl_company;
                $branch          = $rowdetail->tbl_company_branch_idtbl_company_branch;
                $masterID        = $rowdetail->tbl_master_idtbl_master;
                $amount          = $rowdetail->amount;
                $narration       = $rowdetail->narration;
                $tratype         = $rowdetail->tratype;

                if (!empty($detailaccount)) {
                    $chartofaccountinfo = get_chart_account_acco_child_account($company, $branch, $detailaccount);
                    if (!$chartofaccountinfo || $chartofaccountinfo->num_rows() == 0) {
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

                // Update post status on detail line
                $datadetail = array(
                    'poststatus' => '1',
                    'postuser'   => $userID
                );

                $this->db->where('idtbl_account_receivable', $receivedetailID);
                $this->db->update('tbl_account_receivable', $datadetail);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                $this->_jsonResponse(1, 'fas fa-save', 'Record Posted Successfully', 'success');
            } else {
                $this->db->trans_rollback();
                throw new Exception('Record Error, Transaction failed');
            }

        } catch (Exception $e) {
            if ($this->db->trans_enabled) {
                $this->db->trans_rollback();
            }
            $this->_jsonResponse(0, 'fas fa-warning', $e->getMessage(), 'danger');
        }
    }
    public function Getinvoiceaccocustomer(){
        $recordID=$this->input->post('recordID');

        $this->db->select('idtbl_sales_info, invno, amount, invamount, invdate');
        $this->db->from('tbl_sales_info');
        $this->db->where('status', 1);
        $this->db->where('paystatus', 0);
        $this->db->where('poststatus', 0);
        $this->db->where('tbl_customer_idtbl_customer', $recordID);

        $respond=$this->db->get();

        echo json_encode($respond->result());
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