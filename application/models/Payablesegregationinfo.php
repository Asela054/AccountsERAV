<?php
class Payablesegregationinfo extends CI_Model{
    public function Payablesegregationinsertupdate(){
        $userID = $_SESSION['userid'];

        $company          = $this->input->post('company');
        $branch           = $this->input->post('branch');
        $supplier         = !empty($this->input->post('supplier')) ? $this->input->post('supplier') : '';
        $invoice          = !empty($this->input->post('invoice'))  ? $this->input->post('invoice')  : '';
        $invoiceamount    = $this->input->post('invoiceamount');
        $invoicedate      = $this->input->post('invoicedate');
        $payremark        = $this->input->post('payremark');
        $segregationdata  = $this->input->post('tableData');
        $recordOption     = $this->input->post('recordOption');
        $recordID         = !empty($this->input->post('recordID')) ? $this->input->post('recordID') : '';
        $expencesrecordID = !empty($this->input->post('expencesrecordID')) ? $this->input->post('expencesrecordID') : '';

        $updatedatetime = date('Y-m-d H:i:s');
        $today          = date('Y-m-d');

        if ($recordOption == 1) {

            // ── INSERT ────────────────────────────────────────────────────────────
            try {
                $masterdata = get_account_period_acco_date($company, $branch, $invoicedate);

                if (empty($masterdata) || empty($masterdata->idtbl_master)) {
                    throw new Exception('Record Error, Account period not found for the given date');
                }

                $prefix     = generate_prefix($company, $branch, $invoicedate, 'AP');
                $batchno    = tr_batch_num($prefix, $branch);
                $masterID   = $masterdata->idtbl_master ? $masterdata->idtbl_master : '';

                $this->db->trans_begin();

                $this->db->select('grnno');
                $this->db->from('tbl_expence_info');
                $this->db->where('status', 1);
                $this->db->where('grnno', $invoice);
                $respondexpence = $this->db->get();

                if(!empty($respondexpence->result())){
                    throw new Exception('Record Error, Your invoice no already inserted please check and insert again.');
                }

                $data = array(
                    'exptype'=>'4', 
                    'expcode'=>'OTH', 
                    'grnno'=>$invoice, 
                    'grndate'=>$invoicedate, 
                    'amount'=>$invoiceamount, 
                    'invamount'=>$invoiceamount, 
                    'paystatus'=>'0', 
                    'poststatus'=>'0', 
                    'remark'=>$payremark, 
                    'status'=>'1', 
                    'insertdatetime'=>$updatedatetime, 
                    'tbl_user_idtbl_user'=>$userID, 
                    'tbl_supplier_idtbl_supplier'=>$supplier,
                    'tbl_company_idtbl_company'=>$company,
                    'tbl_company_branch_idtbl_company_branch'=>$branch
                );

                $this->db->insert('tbl_expence_info', $data);

                $data = array(
                    'tradate'                                 => $today,
                    'batchno'                                 => $batchno,
                    'supplier'                                => $supplier,
                    'invoiceno'                               => $invoice,
                    'amount'                                  => $invoiceamount,
                    'poststatus'                              => '0',
                    'status'                                  => '1',
                    'insertdatetime'                          => $updatedatetime,
                    'tbl_user_idtbl_user'                     => $userID,
                    'tbl_company_idtbl_company'               => $company,
                    'tbl_company_branch_idtbl_company_branch' => $branch,
                    'tbl_master_idtbl_master'                 => $masterID
                );

                $this->db->insert('tbl_account_payable_main', $data);
                $payablemainID = $this->db->insert_id();

                if (!$payablemainID) {
                    throw new Exception('Failed to insert main payable record');
                }

                foreach ($segregationdata as $rowsegregationdata) {
                    $chartofaccount       = '';
                    $chartofdetailaccount = '';

                    if ($rowsegregationdata['col_7'] == 1)      { $chartofaccount       = $rowsegregationdata['col_1']; }
                    else if ($rowsegregationdata['col_7'] == 2) { $chartofdetailaccount = $rowsegregationdata['col_1']; }

                    if ($rowsegregationdata['col_4'] == 'D')      { $amount = $rowsegregationdata['col_5']; }
                    else if ($rowsegregationdata['col_4'] == 'C') { $amount = $rowsegregationdata['col_6']; }

                    $datasub = array(
                        'tradate'                                               => $today,
                        'batchno'                                               => $batchno,
                        'tratype'                                               => $rowsegregationdata['col_4'],
                        'amount'                                                => $amount,
                        'narration'                                             => $rowsegregationdata['col_3'],
                        'status'                                                => '1',
                        'insertdatetime'                                        => $updatedatetime,
                        'tbl_user_idtbl_user'                                   => $userID,
                        'tbl_master_idtbl_master'                               => $masterID,
                        'tbl_company_idtbl_company'                             => $company,
                        'tbl_company_branch_idtbl_company_branch'               => $branch,
                        'tbl_account_payable_main_idtbl_account_payable_main'   => $payablemainID,
                        'tbl_account_idtbl_account'                             => $chartofaccount,
                        'tbl_account_detail_idtbl_account_detail'               => $chartofdetailaccount
                    );

                    $this->db->insert('tbl_account_payable', $datasub);
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
                $this->db->from('tbl_expence_info');
                $this->db->where('idtbl_expence_info', $expencesrecordID);
                $this->db->where('status', 1);

                $respondexpences=$this->db->get();  

                if ($respondexpences->row(0)->poststatus != 0) {
                    $this->db->trans_commit();
                    throw new Exception('Record Error. This record already posted.');
                }

                $dataexpences = array(
                    'grnno'=>$invoice, 
                    'amount'=>$invoiceamount, 
                    'invamount'=>$invoiceamount,
                    'remark'=>$payremark, 
                    'editstatus' => '0',
                    'status'=>'1', 
                    'updateuser'=> $userID, 
                    'updatedatetime'=> $updatedatetime,
                );

                $this->db->where('idtbl_expence_info', $expencesrecordID);
                $this->db->update('tbl_expence_info', $dataexpences);

                $this->db->select('batchno, tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch, tbl_master_idtbl_master, poststatus');
                $this->db->from('tbl_account_payable_main');
                $this->db->where('idtbl_account_payable_main', $recordID);
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

                $this->db->where('tbl_account_payable_main_idtbl_account_payable_main', $recordID);
                $this->db->delete('tbl_account_payable');

                $data = array(
                    'editstatus'     => '0',
                    'updateuser'     => $userID,
                    'updatedatetime' => $updatedatetime
                );

                $this->db->where('idtbl_account_payable_main', $recordID);
                $this->db->update('tbl_account_payable_main', $data);

                foreach ($segregationdata as $rowsegregationdata) {
                    $chartofaccount       = '';
                    $chartofdetailaccount = '';

                    if ($rowsegregationdata['col_7'] == 1)      { $chartofaccount       = $rowsegregationdata['col_1']; }
                    else if ($rowsegregationdata['col_7'] == 2) { $chartofdetailaccount = $rowsegregationdata['col_1']; }

                    if ($rowsegregationdata['col_4'] == 'D')      { $amount = $rowsegregationdata['col_5']; }
                    else if ($rowsegregationdata['col_4'] == 'C') { $amount = $rowsegregationdata['col_6']; }

                    $datasub = array(
                        'tradate'                                               => $today,
                        'batchno'                                               => $existingRecord->batchno,
                        'tratype'                                               => $rowsegregationdata['col_4'],
                        'amount'                                                => $amount,
                        'narration'                                             => $rowsegregationdata['col_3'],
                        'editstatus'                                            => '0',
                        'status'                                                => '1',
                        'insertdatetime'                                        => $updatedatetime,
                        'tbl_user_idtbl_user'                                   => $userID,
                        'tbl_master_idtbl_master'                               => $existingRecord->tbl_master_idtbl_master,
                        'tbl_company_idtbl_company'                             => $existingRecord->tbl_company_idtbl_company,
                        'tbl_company_branch_idtbl_company_branch'               => $existingRecord->tbl_company_branch_idtbl_company_branch,
                        'tbl_account_payable_main_idtbl_account_payable_main'   => $recordID,
                        'tbl_account_idtbl_account'                             => $chartofaccount,
                        'tbl_account_detail_idtbl_account_detail'               => $chartofdetailaccount
                    );

                    $this->db->insert('tbl_account_payable', $datasub);
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
    public function Payablesegregationstatus($x, $y){
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

            $this->db->where('idtbl_account_payable_main', $recordID);
            $this->db->update('tbl_account_payable_main', $data);

            $datapay = array(
                'status'         => $config['status'],
                'updateuser'     => $userID,
                'updatedatetime' => $updatedatetime
            );

            $this->db->where('tbl_account_payable_main_idtbl_account_payable_main', $recordID);
            $this->db->update('tbl_account_payable', $datapay);

            $this->db->select('invoiceno');
            $this->db->from('tbl_account_payable_main');
            $this->db->where('idtbl_account_payable_main', $recordID);
            $respondpaymain = $this->db->get();

            $dataexpences = array(
                'status' => $config['status'],
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('grnno', $respondpaymain->row(0)->invoiceno);
            $this->db->update('tbl_expence_info', $dataexpences);

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
                redirect('Payablesegregation');
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
            redirect('Payablesegregation');
        }
    }
    public function Payablesegregationedit(){
        $recordID=$this->input->post('recordID');
        $userID=$_SESSION['userid'];
        $updatedatetime=date('Y-m-d H:i:s');

        $configdata = getconfigdata('payable_search');
        
        $tablename = $configdata->row(0)->tbl_name;
        $column1   = $configdata->row(0)->col_name;
        $column2   = $configdata->row(1)->col_name;

        $has_table = !empty($tablename) ? 1 : 0;

        $data = array(
            'editstatus' => '1',
            'updateuser'=> $userID, 
            'updatedatetime'=> $updatedatetime
        );

        $this->db->where('idtbl_account_payable_main', $recordID);
        $this->db->update('tbl_account_payable_main', $data);

        $datapay = array(
            'editstatus' => '1',
            'updateuser'=> $userID, 
            'updatedatetime'=> $updatedatetime
        );

        $this->db->where('tbl_account_payable_main_idtbl_account_payable_main', $recordID);
        $this->db->update('tbl_account_payable', $datapay);

        $this->db->select("tbl_account_payable_main.*, tbl_company.company, tbl_company_branch.branch, IF($has_table = 0, '', $tablename.$column2) AS suppliername");
        $this->db->from('tbl_account_payable_main');
        $this->db->join('tbl_company', 'tbl_company.idtbl_company = tbl_account_payable_main.tbl_company_idtbl_company', 'left');
        $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_account_payable_main.tbl_company_branch_idtbl_company_branch', 'left');
        if(!empty($tablename)):
            $this->db->join("$tablename", "$tablename.$column1 = tbl_account_payable_main.supplier", 'left');
        endif;
        $this->db->where('tbl_account_payable_main.idtbl_account_payable_main', $recordID);
        $this->db->where('tbl_account_payable_main.status', 1);

        $respond=$this->db->get();

        $this->db->select('tbl_account_payable.amount, tbl_account_payable.narration, tbl_account_payable.tbl_account_idtbl_account, tbl_account_payable.tbl_account_detail_idtbl_account_detail, tbl_account_payable.tratype, tbl_account_detail.accountno, tbl_account_detail.accountname, tbl_account.accountno AS chartaccountno, tbl_account.accountname AS chartaccountname');
        $this->db->from('tbl_account_payable');
        $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_account_payable.tbl_account_detail_idtbl_account_detail', 'left');
        $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_account_payable.tbl_account_idtbl_account', 'left');
        $this->db->where('tbl_account_payable.tbl_account_payable_main_idtbl_account_payable_main', $recordID);
        $this->db->where('tbl_account_payable.status', 1);

        $respondinfo=$this->db->get();

        $this->db->select('idtbl_expence_info');
        $this->db->from('tbl_expence_info');
        $this->db->where('status', 1);
        $this->db->where('grnno', $respond->row(0)->invoiceno);
        $respondexpence = $this->db->get();

        $dataexpences = array(
            'editstatus' => '1',
            'updateuser'=> $userID, 
            'updatedatetime'=> $updatedatetime
        );

        $this->db->where('idtbl_expence_info', $respondexpence->row(0)->idtbl_expence_info);
        $this->db->update('tbl_expence_info', $dataexpences);

        $this->db->select("tbl_expence_info.*, tbl_company.company, tbl_company_branch.branch, IF($has_table = 0, '', $tablename.$column2) AS suppliername");
        $this->db->from('tbl_expence_info');
        $this->db->join('tbl_company', 'tbl_company.idtbl_company = tbl_expence_info.tbl_company_idtbl_company', 'left');
        $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_expence_info.tbl_company_branch_idtbl_company_branch', 'left');
        if(!empty($tablename)):
            $this->db->join("$tablename", "$tablename.$column1 = tbl_expence_info.tbl_supplier_idtbl_supplier", 'left');
        endif;
        $this->db->where('tbl_expence_info.idtbl_expence_info', $respondexpence->row(0)->idtbl_expence_info);
        $this->db->where('tbl_expence_info.status', 1);
        $respondexpences=$this->db->get();

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
        $obj->id=$respond->row(0)->idtbl_account_payable_main;
        $obj->supplier=$respond->row(0)->supplier;
        $obj->suppliername=$respond->row(0)->suppliername;
        $obj->invoiceno=$respond->row(0)->invoiceno;
        $obj->amount=number_format($respond->row(0)->amount, 2, '.', '');

        $obj->invoicedate=$respondexpences->row(0)->grndate;
        $obj->remark=$respondexpences->row(0)->remark;
        $obj->expencesID=$respondexpences->row(0)->idtbl_expence_info;

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

        $configdata = getconfigdata('payable_search');
        
        $tablename = $configdata->row(0)->tbl_name;
        $column1   = $configdata->row(0)->col_name;
        $column2   = $configdata->row(1)->col_name;

        $has_table = !empty($tablename) ? 1 : 0;

        $data = array(
            'postviewtime'=> $updatedatetime
        );

        $this->db->where('idtbl_account_payable_main', $recordID);
        $this->db->where('poststatus', 0);
        $this->db->update('tbl_account_payable_main', $data);

        $this->db->select("tbl_account_payable_main.*, tbl_company.company, tbl_company_branch.branch, IF($has_table = 0, '', $tablename.$column2) AS suppliername");
        $this->db->from('tbl_account_payable_main');
        $this->db->join('tbl_company', 'tbl_company.idtbl_company = tbl_account_payable_main.tbl_company_idtbl_company', 'left');
        $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_account_payable_main.tbl_company_branch_idtbl_company_branch', 'left');
        if(!empty($tablename)):
            $this->db->join("$tablename", "$tablename.$column1 = tbl_account_payable_main.supplier", 'left');
        endif;
        $this->db->where('tbl_account_payable_main.idtbl_account_payable_main', $recordID);
        // $this->db->where('tbl_account_payable_main.status', 1);

        $respond=$this->db->get();

        $this->db->select('tbl_account_payable.*, tbl_account_detail.accountno, tbl_account_detail.accountname, tbl_account.accountno AS chartaccountno, tbl_account.accountname AS chartaccountname');
        $this->db->from('tbl_account_payable');
        $this->db->join('tbl_account_payable_main', 'tbl_account_payable_main.idtbl_account_payable_main = tbl_account_payable.tbl_account_payable_main_idtbl_account_payable_main', 'left');
        $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail = tbl_account_payable.tbl_account_detail_idtbl_account_detail', 'left');
        $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_account_payable.tbl_account_idtbl_account', 'left');
        $this->db->where('tbl_account_payable_main.idtbl_account_payable_main', $recordID);
        // $this->db->where('tbl_account_payable.status', 1);

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
                <label class="small font-weight-bold my-0">Supplier: </label>
                <label class="small my-0">'.$respond->row(0)->suppliername.'</label><br>
                <label class="small font-weight-bold my-0">Invoice No: </label>
                <label class="small my-0">'.$respond->row(0)->invoiceno.'</label><br>
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
                    $credittotal=0;
                    $debittotal=0;
                    foreach($respondpayinfo->result() as $rowdatainfo){
                        $credittotal += ($rowdatainfo->tratype == 'C' ? $rowdatainfo->amount : 0);
                        $debittotal += ($rowdatainfo->tratype == 'D' ? $rowdatainfo->amount : 0);

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
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-right">Total</th>
                            <th class="text-right">'.number_format($credittotal, 2).'</th>
                            <th class="text-right">'.number_format($debittotal, 2).'</th>
                        </tr>
                    </tfoot>
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
    public function Payablesegregationposting(){
        try {
            $recordID       = $this->input->post('recordID');
            $updatedatetime = date('Y-m-d H:i:s');
            $userID         = $_SESSION['userid'];
            $i              = 0;

            if (empty($recordID)) {
                throw new Exception('Record ID is required');
            }

            $this->db->select('tbl_account_payable_main.tradate, tbl_account_payable_main.batchno, tbl_account_payable_main.invoiceno, tbl_account_payable_main.amount, tbl_account_payable_main.poststatus, tbl_account_payable_main.status, tbl_account_payable_main.editstatus, tbl_account_payable_main.postviewtime, tbl_account_payable_main.updatedatetime, tbl_account_payable_main.tbl_company_idtbl_company, tbl_account_payable_main.tbl_company_branch_idtbl_company_branch, tbl_account_payable_main.tbl_master_idtbl_master, tbl_account_payable_main.supplier, tbl_expence_info.grndate');
            $this->db->from('tbl_account_payable_main');
            $this->db->join('tbl_expence_info', 'tbl_expence_info.grnno = tbl_account_payable_main.invoiceno', 'left');
            $this->db->where('tbl_account_payable_main.idtbl_account_payable_main', $recordID);
            $this->db->where('tbl_account_payable_main.status', 1);

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

            // Update main payable post status
            $data = array(
                'poststatus'  => '1',
                'postuser'    => $userID,
                'postviewtime' => NULL
            );

            $this->db->where('idtbl_account_payable_main', $recordID);
            $this->db->update('tbl_account_payable_main', $data);

            // Update expenses info
            $dataexpences = array(
                'poststatus'    => '1',
                'updateuser'    => $userID,
                'updatedatetime' => $updatedatetime
            );

            $this->db->where('grnno', $record->invoiceno);
            $this->db->update('tbl_expence_info', $dataexpences);

            $i = 1;

            // Generate batch number for account transaction
            $prefix  = generate_prefix($record->tbl_company_idtbl_company, $record->tbl_company_branch_idtbl_company_branch, $record->grndate, 'AT');
            $batchno = tr_batch_num($prefix, $record->tbl_company_branch_idtbl_company_branch);

            if (empty($batchno)) {
                throw new Exception('Record Error, Batch no could not be defined by system');
            }

            // Fetch payable detail lines
            $this->db->select('`idtbl_account_payable`, `tradate`, `batchno`, `tratype`, `amount`, `narration`, `tbl_master_idtbl_master`, `tbl_company_idtbl_company`, `tbl_company_branch_idtbl_company_branch`, `tbl_account_idtbl_account`, `tbl_account_detail_idtbl_account_detail`');
            $this->db->from('tbl_account_payable');
            $this->db->where('tbl_account_payable_main_idtbl_account_payable_main', $recordID);
            $this->db->where('status', 1);

            $responddetail = $this->db->get();

            if (!$responddetail || $responddetail->num_rows() == 0) {
                throw new Exception('No payable detail lines found for this record');
            }

            foreach ($responddetail->result() as $rowdetail) {
                $i++;

                $paydetailID   = $rowdetail->idtbl_account_payable;
                $tradate       = $rowdetail->tradate;
                $segbatchno    = $rowdetail->batchno;
                $detailaccount = $rowdetail->tbl_account_detail_idtbl_account_detail;
                $chartaccount  = $rowdetail->tbl_account_idtbl_account;
                $company       = $rowdetail->tbl_company_idtbl_company;
                $branch        = $rowdetail->tbl_company_branch_idtbl_company_branch;
                $masterID      = $rowdetail->tbl_master_idtbl_master;
                $amount        = $rowdetail->amount;
                $narration     = $rowdetail->narration;
                $tratype       = $rowdetail->tratype;

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
                    'tratype'                                 => 'P',
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
                    'tratype'                                 => 'P',
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

                $this->db->where('idtbl_account_payable', $paydetailID);
                $this->db->update('tbl_account_payable', $datadetail);
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
    public function Getinvoiceaccosupplier(){
        $recordID=$this->input->post('recordID');

        $this->db->select('idtbl_expence_info, grnno, amount, grndate');
        $this->db->from('tbl_expence_info');
        $this->db->where('status', 1);
        $this->db->where('paystatus', 0);
        $this->db->where('poststatus', 0);
        $this->db->where('tbl_supplier_idtbl_supplier', $recordID);

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