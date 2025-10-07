<?php
class BatchTransactioninfo extends CI_Model{
    public function Getbatchcategory(){
        $this->db->select('`idtbl_batch_category`, `batch_category`');
        $this->db->from('tbl_batch_category');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
    public function GetBatchTransType(){
        $recordID = $this->input->post('recordID');
        $companyid = $_SESSION['companyid'];
        $branchid = $_SESSION['branchid'];

        $this->db->select('`idtbl_batch_trans_type`, `batctranstype`, `batctranstypecode`, `plusminus`');
        $this->db->from('tbl_batch_trans_type');
        $this->db->where('status', 1);
        $this->db->where('tbl_batch_category_idtbl_batch_category', $recordID);
        $this->db->where('tbl_company_idtbl_company', $companyid);
        $this->db->where('tbl_company_branch_idtbl_company_branch', $branchid);
        
        $respond = $this->db->get();

        echo json_encode($respond->result());
    }
    public function Getuomlist(){
        $this->db->select('`idtbl_mesurements`, `measure_type`');
        $this->db->from('tbl_measurements');
        $this->db->where('status', 1);

        return $respond = $this->db->get();
    }
    public function GetMaterialDetails(){
        $materialID = $this->input->post('materialID');
        $companyid = $_SESSION['companyid'];
        $branchid = $_SESSION['branchid'];

        $this->db->select('`batchno`, `qty`, `measure_type_id`, `unitprice`');
        $this->db->from('tbl_print_stock');
        $this->db->where('tbl_print_material_info_idtbl_print_material_info', $materialID);
        $this->db->where('tbl_company_idtbl_company', $companyid);
        $this->db->where('tbl_company_branch_idtbl_company_branch', $branchid);
        $this->db->where('status', 1);
        
        $respond = $this->db->get();
        echo json_encode($respond->result());
    }
    public function BatchTransactioninsertupdate(){
        $userID=$_SESSION['userid'];
        $batchcategory = $this->input->post('batchcategory');
        $batchtranstype = $this->input->post('batchtranstype');
        if(!empty($this->input->post('batchTransID'))):$batchTransID = $this->input->post('batchTransID');endif;
        if(!empty($this->input->post('batchTransBatchNo'))):$batchTransBatchNo = $this->input->post('batchTransBatchNo');endif;
        if(!empty($this->input->post('batchTransMaster'))):$batchTransMaster = $this->input->post('batchTransMaster');endif;

        if($batchcategory==1):
            $inventoryDate = $this->input->post('inventoryDate');
            $inventoryTransCode = $this->input->post('inventoryTransCode');
            $inventoryMaterial = $this->input->post('inventoryMaterial');
            $inventoryMaterialText = $this->input->post('inventoryMaterialText');
            $inventoryUOMID = $this->input->post('inventoryUOMID');
            $inventoryUOM = $this->input->post('inventoryUOM');
            $inventoryReference = $this->input->post('inventoryReference');
            $inventoryDescription = $this->input->post('inventoryDescription');
            $inventoryQtyOnHand = $this->input->post('inventoryQtyOnHand');
            $inventoryQtyIn = $this->input->post('inventoryQtyIn');
            $inventoryQtyOut = $this->input->post('inventoryQtyOut');
            $inventoryUnitCost = $this->input->post('inventoryUnitCost');
            $inventoryNewUnitCost = $this->input->post('inventoryNewUnitCost');
            $inventoryBatchNo = $this->input->post('inventoryBatchNo');
        elseif($batchcategory==2):
            $receiptcustomerID = $this->input->post('receiptcustomerID');
            $receiptcustomer = $this->input->post('receiptcustomer');
            $receiptinvoicedate = $this->input->post('receiptinvoicedate');
            $receiptinvoice = $this->input->post('receiptinvoice');
            $receiptcreditdebit = $this->input->post('receiptcreditdebit');
            $creditamount = str_replace([',', ' '], '', $this->input->post('creditamount'));
            $debitamount = str_replace([',', ' '], '', $this->input->post('debitamount'));
            $receiptnarration = $this->input->post('receiptnarration');
            $crdr = $this->input->post('crdr');
        elseif($batchcategory==3):
            $paymentsupplierID = $this->input->post('paymentsupplierID');
            $paymentsupplier = $this->input->post('paymentsupplier');
            $paymentinvoicedate = $this->input->post('paymentinvoicedate');
            $paymentinvoice = $this->input->post('paymentinvoice');
            $paymentcreditdebit = $this->input->post('paymentcreditdebit');
            $creditamount = str_replace([',', ' '], '', $this->input->post('creditamount'));
            $debitamount = str_replace([',', ' '], '', $this->input->post('debitamount'));
            $paymentnarration = $this->input->post('paymentnarration');
            $crdr = $this->input->post('crdr');
        endif;
        $completestatus = 0;
        
        $company = $_SESSION['companyid'];
        $branch = $_SESSION['branchid'];
        $today=date('Y-m-d');
        $updatedatetime=date('Y-m-d H:i:s');

        if(empty($batchTransID)):
            $prefix=btrans_prefix($company, $branch);
            $masterdata=get_account_period($company, $branch);
            $batchno=tr_batch_num($prefix, $branch);
            $masterID=$masterdata->idtbl_master;
        else:
            $batchno=$batchTransBatchNo;
            $masterID=$batchTransMaster;

            $this->db->select('`completestatus`');
            $this->db->from('tbl_batch_transaction_main');
            $this->db->where('idtbl_batch_transaction_main', $batchTransID);
    
            $respondcheck = $this->db->get();
            $completestatus = $respondcheck->row(0)->completestatus;
        endif;

        if($completestatus==0){
            if(!empty($batchno)){
                $this->db->trans_begin();

                if(empty($batchTransID)):
                    $data = array(
                        'transdate'=> $today, 
                        'batchno'=> $batchno, 
                        'status'=> '1', 
                        'insertdatetime'=> $updatedatetime, 
                        'tbl_user_idtbl_user'=> $userID,
                        'tbl_company_idtbl_company'=> $company,
                        'tbl_company_branch_idtbl_company_branch'=> $branch,
                        'tbl_batch_category_idtbl_batch_category'=> $batchcategory,
                        'tbl_batch_trans_type_idtbl_batch_trans_type'=> $batchtranstype,
                        'tbl_master_idtbl_master'=> $masterID
                    );

                    $this->db->insert('tbl_batch_transaction_main', $data);
                    $batchtransmainID=$this->db->insert_id();
                else:
                    $batchtransmainID=$batchTransID;
                endif;

                if($batchcategory==1):
                    $databatchtransdata = array(
                        'transdate'=> $inventoryDate, 
                        'batchno'=> $batchno, 
                        'narration'=> $inventoryReference, 
                        'desc'=> $inventoryDescription, 
                        'qtyhand'=> $inventoryQtyOnHand, 
                        'qtyin'=> $inventoryQtyIn, 
                        'qtyout'=> $inventoryQtyOut, 
                        'unitcost'=> $inventoryUnitCost, 
                        'newunitcost'=> $inventoryNewUnitCost, 
                        'uom_id'=> $inventoryUOMID, 
                        'materialbatch'=> $inventoryBatchNo, 
                        'status'=> 1,
                        'insertdatetime'=> $updatedatetime,
                        'tbl_user_idtbl_user'=> $userID,
                        'tbl_company_idtbl_company'=> $company,
                        'tbl_company_branch_idtbl_company_branch'=> $branch,
                        'tbl_batch_trans_type_idtbl_batch_trans_type'=> $batchtranstype, 
                        'tbl_master_idtbl_master'=> $masterID, 
                        'tbl_print_material_info_idtbl_print_material_info'=> $inventoryMaterial,
                        'tbl_batch_transaction_main_idtbl_batch_transaction_main'=> $batchtransmainID
                    );
                elseif($batchcategory==2):
                    $databatchtransdata = array(
                        'transdate'=> $receiptinvoicedate, 
                        'batchno'=> $batchno, 
                        'narration'=> $receiptnarration, 
                        'desc'=> $receiptnarration, 
                        'creditamount'=> $creditamount, 
                        'debitamount'=> $debitamount, 
                        'invoiceno'=> $receiptinvoice, 
                        'crdr'=> $crdr, 
                        'status'=> 1,
                        'insertdatetime'=> $updatedatetime,
                        'tbl_user_idtbl_user'=> $userID,
                        'tbl_company_idtbl_company'=> $company,
                        'tbl_company_branch_idtbl_company_branch'=> $branch,
                        'tbl_batch_trans_type_idtbl_batch_trans_type'=> $batchtranstype, 
                        'tbl_master_idtbl_master'=> $masterID, 
                        'tbl_batch_transaction_main_idtbl_batch_transaction_main'=> $batchtransmainID,
                        'tbl_customer_idtbl_customer'=> $receiptcustomerID
                    );
                elseif($batchcategory==3):
                    $databatchtransdata = array(
                        'transdate'=> $paymentinvoicedate, 
                        'batchno'=> $batchno, 
                        'narration'=> $paymentnarration, 
                        'desc'=> $paymentnarration, 
                        'creditamount'=> $creditamount, 
                        'debitamount'=> $debitamount, 
                        'invoiceno'=> $paymentinvoice, 
                        'crdr'=> $crdr, 
                        'status'=> 1,
                        'insertdatetime'=> $updatedatetime,
                        'tbl_user_idtbl_user'=> $userID,
                        'tbl_company_idtbl_company'=> $company,
                        'tbl_company_branch_idtbl_company_branch'=> $branch,
                        'tbl_batch_trans_type_idtbl_batch_trans_type'=> $batchtranstype, 
                        'tbl_master_idtbl_master'=> $masterID, 
                        'tbl_batch_transaction_main_idtbl_batch_transaction_main'=> $batchtransmainID,
                        'tbl_supplier_idtbl_supplier'=> $paymentsupplierID
                    );
                endif;
                $this->db->insert('tbl_batch_transaction', $databatchtransdata);
                $batchtransID=$this->db->insert_id();

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
                    $obj->batchno=$batchno;
                    $obj->batchtransmainID=$batchtransmainID;
                    $obj->batchtransID=$batchtransID;
                    $obj->masterID=$masterID;
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
                    $obj->batchno='';
                    $obj->batchtransmainID='';
                    $obj->batchtransID='';
                    $obj->masterID='';
                    $obj->action=$actionJSON;

                    echo json_encode($obj);
                }
            }
            else{
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
                $obj->batchno='';
                $obj->batchtransmainID='';
                $obj->masterID='';
                $obj->action=$actionJSON;

                echo json_encode($obj);
            }
        }
        else{
            $actionObj=new stdClass();
            $actionObj->icon='fas fa-warning';
            $actionObj->title='';
            $actionObj->message='Record Error, Already completed this batch.';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='danger';

            $actionJSON=json_encode($actionObj);
            
            $obj=new stdClass();
            $obj->status=0;
            $obj->batchno='';
            $obj->batchtransmainID='';
            $obj->batchtransID='';
            $obj->action=$actionJSON;

            echo json_encode($obj);
        }
    }
    public function BatchTransactionview(){
        $recordID = $this->input->post('recordID');
        $transcate = $this->input->post('transcate');

        if($transcate==1){
            $this->db->select('tbl_batch_transaction.*, tbl_batch_transaction_main.approvestatus, tbl_batch_transaction_main.completestatus, tbl_batch_category.batch_category, tbl_batch_trans_type.batctranstype, tbl_print_material_info.materialname,  tbl_measurements.measure_type');
            $this->db->from('tbl_batch_transaction');
            $this->db->join('tbl_batch_transaction_main', 'tbl_batch_transaction_main.idtbl_batch_transaction_main = tbl_batch_transaction.tbl_batch_transaction_main_idtbl_batch_transaction_main', 'left');
            $this->db->join('tbl_batch_category', 'tbl_batch_category.idtbl_batch_category = tbl_batch_transaction_main.tbl_batch_category_idtbl_batch_category', 'left');
            $this->db->join('tbl_batch_trans_type', 'tbl_batch_trans_type.idtbl_batch_trans_type=tbl_batch_transaction.tbl_batch_trans_type_idtbl_batch_trans_type', 'left');
            $this->db->join('tbl_print_material_info', 'tbl_print_material_info.idtbl_print_material_info=tbl_batch_transaction.tbl_print_material_info_idtbl_print_material_info', 'left');
            $this->db->join('tbl_measurements', 'tbl_measurements.idtbl_mesurements=tbl_batch_transaction.uom_id', 'left');
            $this->db->where('tbl_batch_transaction.tbl_batch_transaction_main_idtbl_batch_transaction_main', $recordID);
            $this->db->where('tbl_batch_transaction.status', 1);

            $respond = $this->db->get();
            
            $html='';
            $html.='
            <div class="row">
                <div class="col">
                    <label class="small font-weight-bold my-0">Batch No: </label>
                    <label class="small my-0">'.$respond->row(0)->batchno.'</label><br>
                    <label class="small font-weight-bold my-0">Date: </label>
                    <label class="small my-0">'.$respond->row(0)->transdate.'</label><br>
                    <label class="small font-weight-bold my-0">Company/Branch: </label>
                    <label class="small my-0">'.$_SESSION['company'].'-'.$_SESSION['branch'].'</label>
                </div>
                <div class="col">
                    <label class="small font-weight-bold my-0">Category: </label>
                    <label class="small my-0">'.$respond->row(0)->batch_category.'</label><br>
                    <label class="small font-weight-bold my-0">Type: </label>
                    <label class="small my-0">'.$respond->row(0)->batctranstype.'</label><br>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <hr>
                    <table class="table table-bordered table-striped table-sm small">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="">Material</th>
                                <th class="">Material Batch</th>
                                <th class="">Narration</th>
                                <th class="">Desc</th>
                                <th class="">Qty in hand</th>
                                <th class="">Qty In</th>
                                <th class="">Qty Out</th>
                                <th class="">Unit Price</th>
                                <th class="">New Unit Price</th>
                                <th class="">UOM</th>
                            </tr>
                        </thead> 
                        <tbody>';
                        foreach($respond->result() as $row){
                            $html.='
                            <tr>
                                <td class="text-center">'.$row->idtbl_batch_transaction.'</td>
                                <td class="">'.$row->materialname.'</td>
                                <td class="">'.$row->materialbatch.'</td>     
                                <td class="">'.$row->narration.'</td>
                                <td class="">'.$row->desc.'</td>
                                <td class="">'.$row->qtyhand.'</td>
                                <td class="">'.$row->qtyin.'</td>
                                <td class="">'.$row->qtyout.'</td>
                                <td class="text-right">'.number_format($row->unitcost, 2).'</td>
                                <td class="text-right">'.number_format($row->newunitcost, 2).'</td>
                                <td class="">'.$row->measure_type.'</td>
                            </tr>';
                        }
                        $html.='</tbody>
                    </table>
                </div>
            </div>
            ';
            if($respond->row(0)->completestatus==0){
                $html.='
                <div class="row">
                    <div class="col">
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i> This batch transaction not complete yet. Firstly complete this batch transaction.
                        </div> 
                    </div>
                </div>';
            }
            else{
                if($respond->row(0)->approvestatus==1){
                    $html.='
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle mr-2"></i> Batch Transaction approved
                            </div> 
                        </div>
                    </div>';
                } else if($respond->row(0)->approvestatus==2){
                    $html.='
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-times-circle mr-2"></i> Batch Transaction rejected
                            </div> 
                        </div>
                    </div>';
                }
            }
            echo $html;
        }
        else if($transcate==2){
            $this->db->select('tbl_batch_transaction.*, tbl_batch_transaction_main.approvestatus, tbl_batch_transaction_main.completestatus, tbl_batch_category.batch_category, tbl_batch_trans_type.batctranstype, tbl_customer.customer');
            $this->db->from('tbl_batch_transaction');
            $this->db->join('tbl_batch_transaction_main', 'tbl_batch_transaction_main.idtbl_batch_transaction_main = tbl_batch_transaction.tbl_batch_transaction_main_idtbl_batch_transaction_main', 'left');
            $this->db->join('tbl_batch_category', 'tbl_batch_category.idtbl_batch_category = tbl_batch_transaction_main.tbl_batch_category_idtbl_batch_category', 'left');
            $this->db->join('tbl_batch_trans_type', 'tbl_batch_trans_type.idtbl_batch_trans_type=tbl_batch_transaction.tbl_batch_trans_type_idtbl_batch_trans_type', 'left');
            $this->db->join('tbl_customer', 'tbl_customer.idtbl_customer=tbl_batch_transaction.tbl_customer_idtbl_customer', 'left');
            $this->db->where('tbl_batch_transaction.tbl_batch_transaction_main_idtbl_batch_transaction_main', $recordID);
            $this->db->where('tbl_batch_transaction.status', 1);

            $respond = $this->db->get();
            
            $html='';
            $html.='
            <div class="row">
                <div class="col">
                    <label class="small font-weight-bold my-0">Batch No: </label>
                    <label class="small my-0">'.$respond->row(0)->batchno.'</label><br>
                    <label class="small font-weight-bold my-0">Date: </label>
                    <label class="small my-0">'.$respond->row(0)->transdate.'</label><br>
                    <label class="small font-weight-bold my-0">Company/Branch: </label>
                    <label class="small my-0">'.$_SESSION['company'].'-'.$_SESSION['branch'].'</label>
                </div>
                <div class="col">
                    <label class="small font-weight-bold my-0">Category: </label>
                    <label class="small my-0">'.$respond->row(0)->batch_category.'</label><br>
                    <label class="small font-weight-bold my-0">Type: </label>
                    <label class="small my-0">'.$respond->row(0)->batctranstype.'</label><br>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <hr>
                    <table class="table table-bordered table-striped table-sm small">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="">Customer</th>
                                <th class="">Date</th>
                                <th class="">Invoice | Bill No</th>
                                <th class="">Narration</th>
                                <th class="text-center">C/D</th>
                                <th class="text-right">Debit</th>
                                <th class="text-right">Credit</th>
                            </tr>
                        </thead> 
                        <tbody>';
                        foreach($respond->result() as $row){
                            $html.='
                            <tr>
                                <td class="text-center">'.$row->idtbl_batch_transaction.'</td>
                                <td class="">'.$row->customer.'</td>
                                <td class="">'.$row->transdate.'</td>     
                                <td class="">'.$row->invoiceno.'</td>
                                <td class="">'.$row->narration.'</td>
                                <td class="text-center">'.$row->crdr.'</td>
                                <td class="text-right">'.number_format($row->debitamount, 2).'</td>
                                <td class="text-right">'.number_format($row->creditamount, 2).'</td>
                            </tr>';
                        }
                        $html.='</tbody>
                    </table>
                </div>
            </div>
            ';
            if($respond->row(0)->completestatus==0){
                $html.='
                <div class="row">
                    <div class="col">
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i> This batch transaction not complete yet. Firstly complete this batch transaction.
                        </div> 
                    </div>
                </div>';
            }
            else{
                if($respond->row(0)->approvestatus==1){
                    $html.='
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle mr-2"></i> Batch Transaction approved
                            </div> 
                        </div>
                    </div>';
                } else if($respond->row(0)->approvestatus==2){
                    $html.='
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-times-circle mr-2"></i> Batch Transaction rejected
                            </div> 
                        </div>
                    </div>';
                }
            }
            echo $html;
        }
        else if($transcate==3){
            $this->db->select('tbl_batch_transaction.*, tbl_batch_transaction_main.approvestatus, tbl_batch_transaction_main.completestatus, tbl_batch_category.batch_category, tbl_batch_trans_type.batctranstype, tbl_supplier.suppliername');
            $this->db->from('tbl_batch_transaction');
            $this->db->join('tbl_batch_transaction_main', 'tbl_batch_transaction_main.idtbl_batch_transaction_main = tbl_batch_transaction.tbl_batch_transaction_main_idtbl_batch_transaction_main', 'left');
            $this->db->join('tbl_batch_category', 'tbl_batch_category.idtbl_batch_category = tbl_batch_transaction_main.tbl_batch_category_idtbl_batch_category', 'left');
            $this->db->join('tbl_batch_trans_type', 'tbl_batch_trans_type.idtbl_batch_trans_type=tbl_batch_transaction.tbl_batch_trans_type_idtbl_batch_trans_type', 'left');
            $this->db->join('tbl_supplier', 'tbl_supplier.idtbl_supplier=tbl_batch_transaction.tbl_supplier_idtbl_supplier', 'left');
            $this->db->where('tbl_batch_transaction.tbl_batch_transaction_main_idtbl_batch_transaction_main', $recordID);
            $this->db->where('tbl_batch_transaction.status', 1);

            $respond = $this->db->get();
            
            $html='';
            $html.='
            <div class="row">
                <div class="col">
                    <label class="small font-weight-bold my-0">Batch No: </label>
                    <label class="small my-0">'.$respond->row(0)->batchno.'</label><br>
                    <label class="small font-weight-bold my-0">Date: </label>
                    <label class="small my-0">'.$respond->row(0)->transdate.'</label><br>
                    <label class="small font-weight-bold my-0">Company/Branch: </label>
                    <label class="small my-0">'.$_SESSION['company'].'-'.$_SESSION['branch'].'</label>
                </div>
                <div class="col">
                    <label class="small font-weight-bold my-0">Category: </label>
                    <label class="small my-0">'.$respond->row(0)->batch_category.'</label><br>
                    <label class="small font-weight-bold my-0">Type: </label>
                    <label class="small my-0">'.$respond->row(0)->batctranstype.'</label><br>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <hr>
                    <table class="table table-bordered table-striped table-sm small">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="">Supplier</th>
                                <th class="">Date</th>
                                <th class="">Invoice | Bill No</th>
                                <th class="">Narration</th>
                                <th class="text-center">C/D</th>
                                <th class="text-right">Debit</th>
                                <th class="text-right">Credit</th>
                            </tr>
                        </thead> 
                        <tbody>';
                        foreach($respond->result() as $row){
                            $html.='
                            <tr>
                                <td class="text-center">'.$row->idtbl_batch_transaction.'</td>
                                <td class="">'.$row->suppliername.'</td>
                                <td class="">'.$row->transdate.'</td>     
                                <td class="">'.$row->invoiceno.'</td>
                                <td class="">'.$row->narration.'</td>
                                <td class="text-center">'.$row->crdr.'</td>
                                <td class="text-right">'.number_format($row->debitamount, 2).'</td>
                                <td class="text-right">'.number_format($row->creditamount, 2).'</td>
                            </tr>';
                        }
                        $html.='</tbody>
                    </table>
                </div>
            </div>
            ';
            if($respond->row(0)->completestatus==0){
                $html.='
                <div class="row">
                    <div class="col">
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i> This batch transaction not complete yet. Firstly complete this batch transaction.
                        </div> 
                    </div>
                </div>';
            }
            else{
                if($respond->row(0)->approvestatus==1){
                    $html.='
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle mr-2"></i> Batch Transaction approved
                            </div> 
                        </div>
                    </div>';
                } else if($respond->row(0)->approvestatus==2){
                    $html.='
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-times-circle mr-2"></i> Batch Transaction rejected
                            </div> 
                        </div>
                    </div>';
                }
            }
            echo $html;
        }
    }
    public function BatchTransactionapprove(){
        $recordID = $this->input->post('recordID');
        $confirmnot = $this->input->post('confirmnot');
        $userID=$_SESSION['userid'];
        $updatedatetime=date('Y-m-d H:i:s');
        $companyID = $_SESSION['companyid'];
        $branchID = $_SESSION['branchid']; 
        $today = date('Y-m-d');   
        $journalmainID = 0;

        $this->db->select('tbl_batch_transaction.*, tbl_batch_transaction_main.approvestatus, tbl_batch_transaction_main.completestatus, tbl_batch_category.batch_category, tbl_batch_category.idtbl_batch_category, tbl_batch_trans_type.batctranstype, tbl_batch_trans_type.idtbl_batch_trans_type, tbl_batch_trans_type.crdr as `batchtypecrdr`, tbl_batch_trans_type.plusminus, tbl_print_material_info.materialname, tbl_print_material_info.tbl_supplier_idtbl_supplier as `grnsupplierid`, tbl_measurements.measure_type');
        $this->db->from('tbl_batch_transaction');
        $this->db->join('tbl_batch_transaction_main', 'tbl_batch_transaction_main.idtbl_batch_transaction_main = tbl_batch_transaction.tbl_batch_transaction_main_idtbl_batch_transaction_main', 'left');
        $this->db->join('tbl_batch_category', 'tbl_batch_category.idtbl_batch_category = tbl_batch_transaction_main.tbl_batch_category_idtbl_batch_category', 'left');
        $this->db->join('tbl_batch_trans_type', 'tbl_batch_trans_type.idtbl_batch_trans_type=tbl_batch_transaction.tbl_batch_trans_type_idtbl_batch_trans_type', 'left');
        $this->db->join('tbl_print_material_info', 'tbl_print_material_info.idtbl_print_material_info=tbl_batch_transaction.tbl_print_material_info_idtbl_print_material_info', 'left');
        $this->db->join('tbl_measurements', 'tbl_measurements.idtbl_mesurements=tbl_batch_transaction.uom_id', 'left');
        $this->db->where('tbl_batch_transaction.tbl_batch_transaction_main_idtbl_batch_transaction_main', $recordID);
        $this->db->where('tbl_batch_transaction.status', 1);

        $respond = $this->db->get();

        if($respond->row(0)->approvestatus==1){
            $actionObj=new stdClass();
            $actionObj->icon='fas fa-exclamation-triangle';
            $actionObj->title='';
            $actionObj->message='Record already approved. Kindly review the status of the record.';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='warning';

            $actionJSON=json_encode($actionObj);
            $obj=new stdClass();
            $obj->status=0;
            $obj->action=$actionJSON;
            echo json_encode($obj);
        }
        else if($respond->row(0)->completestatus==0){
            $actionObj=new stdClass();
            $actionObj->icon='fas fa-exclamation-triangle';
            $actionObj->title='';
            $actionObj->message='This batch transaction not complete yet. Firstly complete this batch transaction.';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='warning';

            $actionJSON=json_encode($actionObj);
            $obj=new stdClass();
            $obj->status=0;
            $obj->action=$actionJSON;
            echo json_encode($obj);
        }
        else{
            try {
                $this->db->trans_begin();
        
                if ($confirmnot == 1) {
                    $totaltransactionamount = 0;
                    // APPROVE PROCESS
                    $data = array(
                        'approvestatus' => $confirmnot,
                        'approveuser' => $userID,
                        'updatedatetime' => $updatedatetime
                    );
        
                    $this->db->where('idtbl_batch_transaction_main', $recordID);
                    $this->db->update('tbl_batch_transaction_main', $data);
                    
                    if($respond->row(0)->idtbl_batch_category==1){
                        foreach($respond->result() as $rowtransactiondata){
                            if(!empty($rowtransactiondata->plusminus>0)){
                                //Update already in stock
                                $materialBatchno = $rowtransactiondata->materialbatch;
                                
                                if($rowtransactiondata->plusminus==1):
                                    $stocktotal = $rowtransactiondata->qtyin * $rowtransactiondata->unitcost;
                                    $this->db->set('qty', "(`qty` + '$rowtransactiondata->qtyin')", FALSE);
                                elseif($rowtransactiondata->plusminus==2):
                                    $stocktotal = $rowtransactiondata->qtyout * $rowtransactiondata->unitcost;
                                    $this->db->set('qty', "(`qty` - '$rowtransactiondata->qtyout')", FALSE);
                                endif;
                                $this->db->where('tbl_print_material_info_idtbl_print_material_info', $rowtransactiondata->tbl_print_material_info_idtbl_print_material_info);
                                $this->db->where('tbl_company_idtbl_company', $companyID);
                                $this->db->where('tbl_company_branch_idtbl_company_branch', $branchID);
                                $this->db->update('tbl_print_stock');

                                $totaltransactionamount += $stocktotal;
                            }
                            else{
                                //New stock insert
                                $materialBatchno = generate_batch_no($rowtransactiondata->tbl_print_material_info_idtbl_print_material_info);
                                $stocktotal = $rowtransactiondata->qtyin * $rowtransactiondata->unitcost;
                                $totaltransactionamount += $stocktotal;

                                // Insert the stock in the transaction data
                                $datastock = array(
                                    'batchno' => $materialBatchno, 
                                    'grndate' => $today, 
                                    'supplier_id' => $rowtransactiondata->grnsupplierid, 
                                    'qty' => $rowtransactiondata->qtyin, 
                                    'measure_type_id' => $rowtransactiondata->uom_id, 
                                    'unitprice' => $rowtransactiondata->unitcost, 
                                    'total' => $stocktotal, 
                                    'status' => '1', 
                                    'insertdatetime' => $updatedatetime,
                                    'tbl_user_idtbl_user' => $userID, 
                                    'tbl_print_material_info_idtbl_print_material_info' => $rowtransactiondata->tbl_print_material_info_idtbl_print_material_info, 
                                    'tbl_company_idtbl_company' => $companyID, 
                                    'tbl_company_branch_idtbl_company_branch' => $branchID,
                                );
                                $this->db->insert('tbl_print_stock', $datastock);
                            }
                        }
                        
                        // Prepare the journal entry
                        $batchtype = $respond->row(0)->idtbl_batch_trans_type;
                        $narration = $respond->row(0)->batch_category.' - '.$respond->row(0)->batctranstype.' on '.$today;

                        $this->db->select('`crdr`, `tbl_account_idtbl_account`, `tbl_account_detail_idtbl_account_detail`');
                        $this->db->from('tbl_batch_trans_type_info');
                        $this->db->where('tbl_batch_trans_type_idtbl_batch_trans_type', $batchtype);
                        $this->db->where('status', 1);
                        $respondtranstypeinfo = $this->db->get();

                        if($respondtranstypeinfo->num_rows() > 0){
                            $prefix=journal_prefix($companyID, $branchID);
                            $masterdata=get_account_period($companyID, $branchID);
                            $batchno=tr_batch_num($prefix, $branchID);
                            $masterID=$masterdata->idtbl_master;

                            $data = array(
                                'tradate'=> $today, 
                                'batchno'=> $batchno, 
                                'amount'=> $totaltransactionamount, 
                                'narration'=> $narration, 
                                'poststatus'=> '0', 
                                'status'=> '1', 
                                'insertdatetime'=> $updatedatetime, 
                                'tbl_user_idtbl_user'=> $userID,
                                'tbl_master_idtbl_master'=> $masterID,
                                'tbl_company_idtbl_company'=> $companyID,
                                'tbl_company_branch_idtbl_company_branch'=> $branchID
                            );

                            $this->db->insert('tbl_account_transaction_manual_main', $data);

                            $journalmainID=$this->db->insert_id();

                            $i=1;
                            foreach($respondtranstypeinfo->result() as $rowtranstypeinfo){
                                $chartofaccountID = $rowtranstypeinfo->tbl_account_idtbl_account;
                                $chartofdetailaccountID = $rowtranstypeinfo->tbl_account_detail_idtbl_account_detail;
                                $crdr = $rowtranstypeinfo->crdr;    
                                $transactionAmount = $totaltransactionamount;

                                $datacrdr = array(
                                    'tradate'=> $today, 
                                    'batchno'=> $batchno, 
                                    'tratype'=> 'J', 
                                    'seqno'=> $i, 
                                    'crdr'=> $crdr, 
                                    'amount'=> $transactionAmount, 
                                    'narration'=> $narration, 
                                    'status'=> '1', 
                                    'insertdatetime'=> $updatedatetime, 
                                    'tbl_user_idtbl_user'=> $userID,
                                    'tbl_account_idtbl_account'=> $chartofaccountID,
                                    'tbl_account_detail_idtbl_account_detail'=> $chartofdetailaccountID,
                                    'tbl_master_idtbl_master'=> $masterID,
                                    'tbl_company_idtbl_company'=> $companyID,
                                    'tbl_company_branch_idtbl_company_branch'=> $branchID,
                                    'manualtrans_main_id'=> $journalmainID
                                );

                                $this->db->insert('tbl_account_transaction_manual', $datacrdr);
                                $i++;
                            }

                            // Call Journalentryposting here
                            $postingSuccess = $this->Journalentryposting($journalmainID);
                            if (!$postingSuccess) {
                                throw new Exception("Journal entry posting failed");
                            }
                        }
                    } else if($respond->row(0)->idtbl_batch_category==2){
                        // Prepare the journal entry
                        $batchtype = $respond->row(0)->idtbl_batch_trans_type;
                        $narration = $respond->row(0)->batch_category.' - '.$respond->row(0)->batctranstype.' on '.$today;

                        $this->db->select('`crdr`, `tbl_account_idtbl_account`, `tbl_account_detail_idtbl_account_detail`');
                        $this->db->from('tbl_batch_trans_type_info');
                        $this->db->where('tbl_batch_trans_type_idtbl_batch_trans_type', $batchtype);
                        $this->db->where('status', 1);
                        $respondtranstypeinfo = $this->db->get();

                        if($respondtranstypeinfo->num_rows() > 0){
                            foreach($respond->result() as $rowtransactiondata){
                                if(!empty($rowtransactiondata->creditamount)):
                                    $incoiceamount = $rowtransactiondata->creditamount;
                                elseif(!empty($rowtransactiondata->debitamount)):
                                    $incoiceamount = $rowtransactiondata->debitamount;
                                endif;

                                $datasale = array(
                                    'saletype'=>'2', 
                                    'salecode'=>'OTH', 
                                    'invno'=>$rowtransactiondata->invoiceno, 
                                    'invdate'=>$rowtransactiondata->transdate, 
                                    'amount'=>$incoiceamount, 
                                    'invamount'=>$incoiceamount, 
                                    'paystatus'=>'0', 
                                    'poststatus'=>'1', 
                                    'remark'=>$rowtransactiondata->narration, 
                                    'status'=>'1', 
                                    'insertdatetime'=>$updatedatetime, 
                                    'tbl_user_idtbl_user'=>$userID, 
                                    'tbl_customer_idtbl_customer'=>$rowtransactiondata->tbl_customer_idtbl_customer,
                                    'tbl_company_idtbl_company'=>$companyID,
                                    'tbl_company_branch_idtbl_company_branch'=>$branchID
                                );

                                $this->db->insert('tbl_sales_info', $datasale);

                                $prefix=journal_prefix($companyID, $branchID);
                                $masterdata=get_account_period($companyID, $branchID);
                                $batchno=tr_batch_num($prefix, $branchID);
                                $masterID=$masterdata->idtbl_master;
                                
                                $data = array(
                                    'tradate'=> $today, 
                                    'batchno'=> $batchno, 
                                    'amount'=> $incoiceamount, 
                                    'narration'=> $narration, 
                                    'poststatus'=> '0', 
                                    'status'=> '1', 
                                    'insertdatetime'=> $updatedatetime, 
                                    'tbl_user_idtbl_user'=> $userID,
                                    'tbl_master_idtbl_master'=> $masterID,
                                    'tbl_company_idtbl_company'=> $companyID,
                                    'tbl_company_branch_idtbl_company_branch'=> $branchID
                                );

                                $this->db->insert('tbl_account_transaction_manual_main', $data);

                                $journalmainID=$this->db->insert_id();
                                
                                $i=1;
                                foreach($respondtranstypeinfo->result() as $rowtranstypeinfo){
                                    $chartofaccountID = $rowtranstypeinfo->tbl_account_idtbl_account;
                                    $chartofdetailaccountID = $rowtranstypeinfo->tbl_account_detail_idtbl_account_detail;
                                    $transactionAmount = $incoiceamount;

                                    if($rowtransactiondata->crdr==$rowtransactiondata->batchtypecrdr):
                                        $crdr = $rowtranstypeinfo->crdr;    
                                    else:
                                        if($rowtranstypeinfo->crdr=='C'):$crdr = 'D';else: $crdr = 'C';endif;
                                    endif;

                                    $datacrdr = array(
                                        'tradate'=> $today, 
                                        'batchno'=> $batchno, 
                                        'tratype'=> 'J', 
                                        'seqno'=> $i, 
                                        'crdr'=> $crdr, 
                                        'amount'=> $transactionAmount, 
                                        'narration'=> $narration, 
                                        'status'=> '1', 
                                        'insertdatetime'=> $updatedatetime, 
                                        'tbl_user_idtbl_user'=> $userID,
                                        'tbl_account_idtbl_account'=> $chartofaccountID,
                                        'tbl_account_detail_idtbl_account_detail'=> $chartofdetailaccountID,
                                        'tbl_master_idtbl_master'=> $masterID,
                                        'tbl_company_idtbl_company'=> $companyID,
                                        'tbl_company_branch_idtbl_company_branch'=> $branchID,
                                        'manualtrans_main_id'=> $journalmainID
                                    );

                                    $this->db->insert('tbl_account_transaction_manual', $datacrdr);
                                    $i++;
                                }

                                // Call Journalentryposting here
                                $postingSuccess = $this->Journalentryposting($journalmainID);
                                if (!$postingSuccess) {
                                    throw new Exception("Journal entry posting failed");
                                }
                            }
                        }
                    } else if($respond->row(0)->idtbl_batch_category==3){
                        // Prepare the journal entry
                        $batchtype = $respond->row(0)->idtbl_batch_trans_type;
                        $narration = $respond->row(0)->batch_category.' - '.$respond->row(0)->batctranstype.' on '.$today;

                        $this->db->select('`crdr`, `tbl_account_idtbl_account`, `tbl_account_detail_idtbl_account_detail`');
                        $this->db->from('tbl_batch_trans_type_info');
                        $this->db->where('tbl_batch_trans_type_idtbl_batch_trans_type', $batchtype);
                        $this->db->where('status', 1);
                        $respondtranstypeinfo = $this->db->get();

                        if($respondtranstypeinfo->num_rows() > 0){
                            foreach($respond->result() as $rowtransactiondata){
                                if(!empty($rowtransactiondata->creditamount)):
                                    $incoiceamount = $rowtransactiondata->creditamount;
                                elseif(!empty($rowtransactiondata->debitamount)):
                                    $incoiceamount = $rowtransactiondata->debitamount;
                                endif;

                                $datapayment = array(
                                    'exptype'=>'4', 
                                    'expcode'=>'OTH', 
                                    'grnno'=>$rowtransactiondata->invoiceno, 
                                    'grndate'=>$rowtransactiondata->transdate, 
                                    'amount'=>$incoiceamount, 
                                    'invamount'=>$incoiceamount, 
                                    'paystatus'=>'0', 
                                    'poststatus'=>'1', 
                                    'remark'=>$rowtransactiondata->narration, 
                                    'status'=>'1', 
                                    'insertdatetime'=>$updatedatetime, 
                                    'tbl_user_idtbl_user'=>$userID, 
                                    'tbl_supplier_idtbl_supplier'=>$rowtransactiondata->tbl_supplier_idtbl_supplier,
                                    'tbl_company_idtbl_company'=>$companyID,
                                    'tbl_company_branch_idtbl_company_branch'=>$branchID
                                );

                                $this->db->insert('tbl_expence_info', $datapayment);

                                $prefix=journal_prefix($companyID, $branchID);
                                $masterdata=get_account_period($companyID, $branchID);
                                $batchno=tr_batch_num($prefix, $branchID);
                                $masterID=$masterdata->idtbl_master;
                                
                                $data = array(
                                    'tradate'=> $today, 
                                    'batchno'=> $batchno, 
                                    'amount'=> $incoiceamount, 
                                    'narration'=> $narration, 
                                    'poststatus'=> '0', 
                                    'status'=> '1', 
                                    'insertdatetime'=> $updatedatetime, 
                                    'tbl_user_idtbl_user'=> $userID,
                                    'tbl_master_idtbl_master'=> $masterID,
                                    'tbl_company_idtbl_company'=> $companyID,
                                    'tbl_company_branch_idtbl_company_branch'=> $branchID
                                );

                                $this->db->insert('tbl_account_transaction_manual_main', $data);

                                $journalmainID=$this->db->insert_id();
                                
                                $i=1;
                                foreach($respondtranstypeinfo->result() as $rowtranstypeinfo){
                                    $chartofaccountID = $rowtranstypeinfo->tbl_account_idtbl_account;
                                    $chartofdetailaccountID = $rowtranstypeinfo->tbl_account_detail_idtbl_account_detail;
                                    $transactionAmount = $incoiceamount;

                                    if($rowtransactiondata->crdr==$rowtransactiondata->batchtypecrdr):
                                        $crdr = $rowtranstypeinfo->crdr;    
                                    else:
                                        if($rowtranstypeinfo->crdr=='C'):$crdr = 'D';else: $crdr = 'C';endif;
                                    endif;

                                    $datacrdr = array(
                                        'tradate'=> $today, 
                                        'batchno'=> $batchno, 
                                        'tratype'=> 'J', 
                                        'seqno'=> $i, 
                                        'crdr'=> $crdr, 
                                        'amount'=> $transactionAmount, 
                                        'narration'=> $narration, 
                                        'status'=> '1', 
                                        'insertdatetime'=> $updatedatetime, 
                                        'tbl_user_idtbl_user'=> $userID,
                                        'tbl_account_idtbl_account'=> $chartofaccountID,
                                        'tbl_account_detail_idtbl_account_detail'=> $chartofdetailaccountID,
                                        'tbl_master_idtbl_master'=> $masterID,
                                        'tbl_company_idtbl_company'=> $companyID,
                                        'tbl_company_branch_idtbl_company_branch'=> $branchID,
                                        'manualtrans_main_id'=> $journalmainID
                                    );

                                    $this->db->insert('tbl_account_transaction_manual', $datacrdr);
                                    $i++;
                                }

                                // Call Journalentryposting here
                                $postingSuccess = $this->Journalentryposting($journalmainID);
                                if (!$postingSuccess) {
                                    throw new Exception("Journal entry posting failed");
                                }
                            }
                        }
                    }
                } else {
                    // REJECT PROCESS
                    $data = array(
                        'approvestatus' => $confirmnot,
                        'approveuser' => $userID,
                        'updatedatetime' => $updatedatetime
                    );
        
                    $this->db->where('idtbl_batch_transaction_main', $recordID);
                    $this->db->update('tbl_batch_transaction_main', $data);
                }
        
                $this->db->trans_commit();
        
                $actionObj=new stdClass();
                $actionObj->icon = 'fas fa-check-circle';
                $actionObj->title = '';
                $actionObj->message = ($confirmnot == 1) ? 'Transaction Approved Successfully' : 'Record Rejected Successfully';
                $actionObj->url = '';
                $actionObj->target = '_blank';
                $actionObj->type = 'success';
        
                $obj=new stdClass();
                $obj->status = 1;
                $obj->action = json_encode($actionObj);
        
            } catch (Exception $e) {
                $this->db->trans_rollback();
                
                error_log("Transactionapprove Error: " . $e->getMessage());
                
                $actionObj=new stdClass();
                $actionObj->icon = 'fas fa-exclamation-triangle';
                $actionObj->title = '';
                $actionObj->message = 'Operation Failed: ' . $e->getMessage();
                $actionObj->url = '';
                $actionObj->target = '_blank';
                $actionObj->type = 'danger';
        
                $obj=new stdClass();
                $obj->status = 0;
                $obj->action = json_encode($actionObj);
            }   

            echo json_encode($obj);
        }
    }
    public function BatchTransactionedit(){
        $recordID=$this->input->post('recordID');

        $this->db->select('`tbl_batch_transaction_main`.`idtbl_batch_transaction_main`, `tbl_batch_transaction_main`.`transdate`, `tbl_batch_transaction_main`.`batchno`, `tbl_batch_transaction_main`.`approvestatus`, `tbl_batch_transaction_main`.`completestatus`, `tbl_batch_transaction_main`.`approveuser`, `tbl_batch_transaction_main`.`tbl_batch_category_idtbl_batch_category`, `tbl_batch_transaction_main`.`tbl_batch_trans_type_idtbl_batch_trans_type`, `tbl_batch_transaction_main`.`tbl_master_idtbl_master`, `tbl_batch_category`.`batch_category`, `tbl_batch_trans_type`.`batctranstypecode`, `tbl_batch_trans_type`.`plusminus`');
        $this->db->from('tbl_batch_transaction_main');
        $this->db->join('tbl_batch_category', 'tbl_batch_category.idtbl_batch_category = tbl_batch_transaction_main.tbl_batch_category_idtbl_batch_category', 'left');
        $this->db->join('tbl_batch_trans_type', 'tbl_batch_trans_type.idtbl_batch_trans_type=tbl_batch_transaction_main.tbl_batch_trans_type_idtbl_batch_trans_type', 'left');
        $this->db->where('idtbl_batch_transaction_main', $recordID);
        $this->db->where('tbl_batch_transaction_main.status', 1);

        $respond=$this->db->get();

        if($respond->row(0)->tbl_batch_category_idtbl_batch_category==1):
            $this->db->select('tbl_batch_transaction.*, tbl_print_material_info.materialname,  tbl_measurements.measure_type');
            $this->db->from('tbl_batch_transaction');
            $this->db->join('tbl_print_material_info', 'tbl_print_material_info.idtbl_print_material_info=tbl_batch_transaction.tbl_print_material_info_idtbl_print_material_info', 'left');
            $this->db->join('tbl_measurements', 'tbl_measurements.idtbl_mesurements=tbl_batch_transaction.uom_id', 'left');
            $this->db->where('tbl_batch_transaction_main_idtbl_batch_transaction_main', $recordID);
            $this->db->where('tbl_batch_transaction.status', 1);
        elseif($respond->row(0)->tbl_batch_category_idtbl_batch_category==2):
            $this->db->select('tbl_batch_transaction.*, tbl_customer.customer');
            $this->db->from('tbl_batch_transaction');
            $this->db->join('tbl_customer', 'tbl_customer.idtbl_customer=tbl_batch_transaction.tbl_customer_idtbl_customer', 'left');
            $this->db->where('tbl_batch_transaction_main_idtbl_batch_transaction_main', $recordID);
            $this->db->where('tbl_batch_transaction.status', 1);
        elseif($respond->row(0)->tbl_batch_category_idtbl_batch_category==3):
            $this->db->select('tbl_batch_transaction.*, tbl_supplier.suppliername');
            $this->db->from('tbl_batch_transaction');
            $this->db->join('tbl_supplier', 'tbl_supplier.idtbl_supplier=tbl_batch_transaction.tbl_supplier_idtbl_supplier', 'left');
            $this->db->where('tbl_batch_transaction_main_idtbl_batch_transaction_main', $recordID);
            $this->db->where('tbl_batch_transaction.status', 1);
        endif;

        $responddetail=$this->db->get();

        $html='';
        if($respond->row(0)->tbl_batch_category_idtbl_batch_category==1):
            foreach($responddetail->result() as $rowresponddetail):
                $html.='<tr>
                    <td class="d-none">'.$rowresponddetail->idtbl_batch_transaction.'</td>
                    <td nowrap>'.$rowresponddetail->materialname.'</td>
                    <td nowrap>'.$rowresponddetail->transdate.'</td>
                    <td nowrap>'.$respond->row(0)->batctranstypecode.'</td>
                    <td nowrap>'.$rowresponddetail->narration.'</td>
                    <td nowrap>'.$rowresponddetail->desc.'</td>
                    <td nowrap>'.$rowresponddetail->qtyhand.'</td>
                    <td nowrap>'.$rowresponddetail->qtyin.'</td>
                    <td nowrap>'.$rowresponddetail->qtyout.'</td>
                    <td nowrap>'.$rowresponddetail->unitcost.'</td>
                    <td nowrap>'.$rowresponddetail->newunitcost.'</td>
                    <td nowrap>'.$rowresponddetail->measure_type.'</td>
                    <td nowrap>'.$rowresponddetail->materialbatch.'</td>
                    <td class="d-none">'.$rowresponddetail->uom_id.'</td>
                    <td class="d-none">'.$rowresponddetail->tbl_print_material_info_idtbl_print_material_info.'</td>
                </tr>';
            endforeach;
        elseif($respond->row(0)->tbl_batch_category_idtbl_batch_category==2):
            foreach($responddetail->result() as $rowresponddetail):
                if($rowresponddetail->crdr=='C'):$crdrtype = 1;elseif($rowresponddetail->crdr=='D'):$crdrtype = 2;endif;
                $html.='<tr>
                    <td class="d-none">'.$rowresponddetail->idtbl_batch_transaction.'</td>
                    <td nowrap>'.$rowresponddetail->customer.'</td>
                    <td nowrap>'.$rowresponddetail->transdate.'</td>
                    <td nowrap>'.$respond->row(0)->batctranstypecode.'</td>
                    <td nowrap>'.$rowresponddetail->invoiceno.'</td>
                    <td nowrap>'.$rowresponddetail->narration.'</td>
                    <td nowrap class="text-center">'.$rowresponddetail->crdr.'</td>
                    <td nowrap class="text-right">'.number_format($rowresponddetail->debitamount, 2).'</td>
                    <td nowrap class="text-right">'.number_format($rowresponddetail->creditamount, 2).'</td>
                    <td nowrap class="d-none">'.$crdrtype.'</td>
                    <td nowrap class="d-none">'.$rowresponddetail->tbl_customer_idtbl_customer.'</td>
                </tr>';
            endforeach;
        elseif($respond->row(0)->tbl_batch_category_idtbl_batch_category==3):
            foreach($responddetail->result() as $rowresponddetail):
                if($rowresponddetail->crdr=='C'):$crdrtype = 1;elseif($rowresponddetail->crdr=='D'):$crdrtype = 2;endif;
                $html.='<tr>
                    <td class="d-none">'.$rowresponddetail->idtbl_batch_transaction.'</td>
                    <td nowrap>'.$rowresponddetail->suppliername.'</td>
                    <td nowrap>'.$rowresponddetail->transdate.'</td>
                    <td nowrap>'.$respond->row(0)->batctranstypecode.'</td>
                    <td nowrap>'.$rowresponddetail->invoiceno.'</td>
                    <td nowrap>'.$rowresponddetail->narration.'</td>
                    <td nowrap class="text-center">'.$rowresponddetail->crdr.'</td>
                    <td nowrap class="text-right">'.number_format($rowresponddetail->debitamount, 2).'</td>
                    <td nowrap class="text-right">'.number_format($rowresponddetail->creditamount, 2).'</td>
                    <td nowrap class="d-none">'.$crdrtype.'</td>
                    <td nowrap class="d-none">'.$rowresponddetail->tbl_supplier_idtbl_supplier.'</td>
                </tr>';
            endforeach;
        endif;

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_batch_transaction_main;
        $obj->transdate=$respond->row(0)->transdate;
        $obj->batchno=$respond->row(0)->batchno;
        $obj->approvestatus=$respond->row(0)->approvestatus;
        $obj->completestatus=$respond->row(0)->completestatus;
        $obj->batchcategory=$respond->row(0)->tbl_batch_category_idtbl_batch_category;
        $obj->batchtype=$respond->row(0)->tbl_batch_trans_type_idtbl_batch_trans_type;
        $obj->batctranstypecode=$respond->row(0)->batctranstypecode;
        $obj->plusminus=$respond->row(0)->plusminus;
        $obj->masterid=$respond->row(0)->tbl_master_idtbl_master;
        $obj->batchinfo=$html;

        echo json_encode($obj);
    }
    public function BatchTransactioncomplete(){
        $this->db->trans_begin();

        $recordID = $this->input->post('recordID');
        $userID=$_SESSION['userid'];
        $updatedatetime=date('Y-m-d H:i:s');
        $companyID = $_SESSION['companyid'];
        $branchID = $_SESSION['branchid']; 
        $today = date('Y-m-d');   

        $this->db->select('`completestatus`');
        $this->db->from('tbl_batch_transaction_main');
        $this->db->where('idtbl_batch_transaction_main', $recordID);

        $respondcheck = $this->db->get();

        if($respondcheck->row(0)->completestatus==0):
            $data = array(
                'completestatus' => '1',
                'updateuser' => $userID,
                'updatedatetime' => $updatedatetime
            );

            $this->db->where('idtbl_batch_transaction_main', $recordID);
            $this->db->update('tbl_batch_transaction_main', $data);

            $this->db->trans_complete();
            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-save';
                $actionObj->title='';
                $actionObj->message='Record Complete Successfully';
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
        else:
            $actionObj=new stdClass();
            $actionObj->icon='fas fa-warning';
            $actionObj->title='';
            $actionObj->message='Record Error, Already completed this batch.';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='danger';

            $actionJSON=json_encode($actionObj);
            
            $obj=new stdClass();
            $obj->status=0;
            $obj->batchno='';
            $obj->batchtransmainID='';
            $obj->action=$actionJSON;

            echo json_encode($obj);
        endif;
    }
    public function BatchTransactioninfostatus(){
        $this->db->trans_begin();

        $batchtransinfoID = $this->input->post('batchtransinfoID');
        $batchtransID = $this->input->post('batchtransID');
        $userID=$_SESSION['userid'];
        $updatedatetime=date('Y-m-d H:i:s');
        $companyID = $_SESSION['companyid'];
        $branchID = $_SESSION['branchid']; 
        $today = date('Y-m-d');   

        $this->db->select('`completestatus`');
        $this->db->from('tbl_batch_transaction_main');
        $this->db->where('idtbl_batch_transaction_main', $batchtransID);

        $respondcheck = $this->db->get();

        if($respondcheck->row(0)->completestatus==0):
            $data = array(
                'status' => '3',
                'updateuser' => $userID,
                'updatedatetime' => $updatedatetime
            );

            $this->db->where('idtbl_batch_transaction', $batchtransinfoID);
            $this->db->update('tbl_batch_transaction', $data);

            $this->db->trans_complete();
            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-save';
                $actionObj->title='';
                $actionObj->message='Record Remove Successfully';
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
        else:
            $actionObj=new stdClass();
            $actionObj->icon='fas fa-warning';
            $actionObj->title='';
            $actionObj->message='Record Error, Already completed this batch.';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='danger';

            $actionJSON=json_encode($actionObj);
            
            $obj=new stdClass();
            $obj->status=0;
            $obj->batchno='';
            $obj->batchtransmainID='';
            $obj->action=$actionJSON;

            echo json_encode($obj);
        endif;
    }
    public function BatchTransactionstatus($x, $y){
        $userID=$_SESSION['userid'];
        $recordID=$x;
        $type=$y;
        $updatedatetime=date('Y-m-d H:i:s');

        $this->db->select('`approvestatus`');
        $this->db->from('tbl_batch_transaction_main');
        $this->db->where('idtbl_batch_transaction_main', $recordID);

        $respondcheck = $this->db->get();
        
        if($respondcheck->row(0)->approvestatus==0){
            $this->db->trans_begin();

            if($type==1){
                $data = array(
                    'status' => '1',
                    'updateuser'=> $userID, 
                    'updatedatetime'=> $updatedatetime
                );

                $this->db->where('idtbl_batch_transaction_main', $recordID);
                $this->db->update('tbl_batch_transaction_main', $data);

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
                    redirect('BatchTransaction');                
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
                    redirect('BatchTransaction');
                }
            }
            else if($type==2){
                $data = array(
                    'status' => '2',
                    'updateuser'=> $userID, 
                    'updatedatetime'=> $updatedatetime
                );

                $this->db->where('idtbl_batch_transaction_main', $recordID);
                $this->db->update('tbl_batch_transaction_main', $data);

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
                    redirect('BatchTransaction');                
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
                    redirect('BatchTransaction');
                }
            }
            else if($type==3){
                $data = array(
                    'status' => '3',
                    'updateuser'=> $userID, 
                    'updatedatetime'=> $updatedatetime
                );

                $this->db->where('idtbl_batch_transaction_main', $recordID);
                $this->db->update('tbl_batch_transaction_main', $data);

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
                    redirect('BatchTransaction');                
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
                    redirect('BatchTransaction');
                }
            }
        }
        else{
            $actionObj=new stdClass();
            $actionObj->icon='fas fa-warning';
            $actionObj->title='';
            $actionObj->message='Record Error, Already approve this batch.';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='danger';

            $actionJSON=json_encode($actionObj);
            
            $obj=new stdClass();
            $obj->status=0;
            $obj->batchno='';
            $obj->batchtransmainID='';
            $obj->batchtransID='';
            $obj->action=$actionJSON;

            echo json_encode($obj);
        }
    }
    public function Gettransactiontypelist(){
        $this->db->select('`idtbl_account_transactiontype`, `transactiontype`');
        $this->db->from('tbl_account_transactiontype');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
    public function Journalentryposting($recordID) {
        $updatedatetime = date('Y-m-d H:i:s');
        $today = date('Y-m-d');
        $userID = $_SESSION['userid'];
        $i = 0;

        try {
            // Get main journal entry
            $this->db->select('poststatus, status, editstatus, postviewtime, completestatus, updatedatetime, tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch');
            $this->db->from('tbl_account_transaction_manual_main');
            $this->db->where('idtbl_account_transaction_manual_main', $recordID);
            $this->db->where('status', 1);
            $respond = $this->db->get();

            if ($respond->num_rows() == 0) {
                return false;
            }

            // Get journal details
            $this->db->select('*');
            $this->db->from('tbl_account_transaction_manual');
            $this->db->where('manualtrans_main_id', $recordID);
            $this->db->where('status', 1);
            $responddetail = $this->db->get();

            // Update main journal status
            $data = array(
                'poststatus'=> '1',
                'postuser'=> $userID,
                'postviewtime'=> NULL
            );
            $this->db->where('idtbl_account_transaction_manual_main', $recordID);
            if (!$this->db->update('tbl_account_transaction_manual_main', $data)) {
                return false;
            }

            $prefix = trans_prefix($respond->row(0)->tbl_company_idtbl_company, $respond->row(0)->tbl_company_branch_idtbl_company_branch);
            $batchno = tr_batch_num($prefix, $respond->row(0)->tbl_company_branch_idtbl_company_branch);

            foreach ($responddetail->result() as $rowdatalist) {
                // Determine chart of account
                if ($rowdatalist->tbl_account_detail_idtbl_account_detail > 0) {
                    $this->load->model('Journalentryinfo');
                    $chartofaccount = $this->Journalentryinfo->Chartofaccountaccodetail($rowdatalist->tbl_account_detail_idtbl_account_detail);
                } else {
                    $chartofaccount = $rowdatalist->tbl_account_idtbl_account;
                }

                // Insert transaction
                $datacredit = array(
                    'tradate'=> $rowdatalist->tradate, 
                    'batchno'=> $batchno, 
                    'trabatchotherno'=> $rowdatalist->batchno, 
                    'tratype'=> 'J', 
                    'seqno'=> $i+1, 
                    'crdr'=> $rowdatalist->crdr, 
                    'accamount'=> $rowdatalist->amount, 
                    'narration'=> $rowdatalist->narration, 
                    'totamount'=> $rowdatalist->amount, 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_account_idtbl_account'=> $chartofaccount,
                    'tbl_master_idtbl_master'=> $rowdatalist->tbl_master_idtbl_master,
                    'tbl_company_idtbl_company'=> $rowdatalist->tbl_company_idtbl_company,
                    'tbl_company_branch_idtbl_company_branch'=> $rowdatalist->tbl_company_branch_idtbl_company_branch
                );
                if (!$this->db->insert('tbl_account_transaction', $datacredit)) {
                    return false;
                }

                // Insert full transaction
                $datacreditfull = array(
                    'tradate'=> $rowdatalist->tradate, 
                    'batchno'=> $batchno, 
                    'tratype'=> 'J', 
                    'crdr'=> $rowdatalist->crdr, 
                    'accamount'=> $rowdatalist->amount, 
                    'narration'=> $rowdatalist->narration, 
                    'totamount'=> $rowdatalist->amount, 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_account_idtbl_account'=> $chartofaccount,
                    'tbl_master_idtbl_master'=> $rowdatalist->tbl_master_idtbl_master,
                    'tbl_company_idtbl_company'=> $rowdatalist->tbl_company_idtbl_company,
                    'tbl_company_branch_idtbl_company_branch'=> $rowdatalist->tbl_company_branch_idtbl_company_branch
                );
                if (!$this->db->insert('tbl_account_transaction_full', $datacreditfull)) {
                    return false;
                }

                // Handle petty cash if applicable
                $this->db->select('specialcate');
                $this->db->from('tbl_account');
                $this->db->where('idtbl_account', $chartofaccount);
                $this->db->where('status', 1);
                $respondspecat = $this->db->get();

                if ($respondspecat->num_rows() > 0 && $rowdatalist->crdr == 'D' && $respondspecat->row(0)->specialcate == 36) {
                    $datapettysummery = array(
                        'date'=> $today, 
                        'openbal'=> '0', 
                        'postbal'=> '0', 
                        'reimbal'=> $rowdatalist->amount, 
                        'closebal'=> $rowdatalist->amount, 
                        'status'=> 1, 
                        'insertdatetime'=> $updatedatetime,
                        'tbl_user_idtbl_user'=> $userID, 
                        'tbl_account_idtbl_account'=> $rowdatalist->tbl_account_idtbl_account, 
                        'tbl_company_idtbl_company'=> $rowdatalist->tbl_company_idtbl_company, 
                        'tbl_company_branch_idtbl_company_branch'=> $rowdatalist->tbl_company_branch_idtbl_company_branch, 
                        'tbl_master_idtbl_master'=> $rowdatalist->tbl_master_idtbl_master
                    );
                    if (!$this->db->insert('tbl_pettycash_summary', $datapettysummery)) {
                        return false;
                    }
                }

                $i++;
            }

            return true;
        } catch (Exception $e) {
            error_log("Journalentryposting Error: " . $e->getMessage());
            return false;
        }
    }
}