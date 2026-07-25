<?php
class Pettycashreimburseinfo extends CI_Model{
    public function Getreceivabletype(){
        $this->db->select('idtbl_receivable_type, receivabletype');
        $this->db->from('tbl_receivable_type');
        $this->db->where('status', 1);

        $respond=$this->db->get();

        return $respond;
    }
    // public function Pettycashreimburseinsertupdate(){
    //     $this->db->trans_begin();

    //     $userID=$_SESSION['userid'];

    //     $companyid=$this->input->post('companyid');
    //     $branchid=$this->input->post('branchid');
    //     $bankaccount=$this->input->post('bankaccount');
    //     $reimbursebal=str_replace(',', '', $this->input->post('reimbursebal'));
    //     $tabledata=json_decode($this->input->post('tabledata'));

    //     $updatedatetime=date('Y-m-d H:i:s');
    //     $today=date('Y-m-d');

    //     $this->db->select('`closebal`, `tbl_account_idtbl_account`');
    //     $this->db->from('tbl_pettycash_summary');
    //     $this->db->where('tbl_company_idtbl_company', $companyid);
    //     $this->db->where('tbl_company_branch_idtbl_company_branch', $branchid);
    //     $this->db->where('status', 1);
    //     $this->db->order_by('idtbl_pettycash_summary', 'DESC');
    //     $this->db->limit(1);

    //     $respond=$this->db->get();

    //     $newclosebalance=$respond->row(0)->closebal+$reimbursebal;

    //     $masterdata = get_account_period_acco_date($companyid, $branchid, $today);
    //     $prefix   = generate_prefix($companyid, $branchid, $today, 'PR');
    //     $masterID=$masterdata->idtbl_master;

    //     $data = array(
    //         'date'=> $today, 
    //         'openbal'=> $respond->row(0)->closebal, 
    //         'reimursebal'=> $reimbursebal, 
    //         'closebal'=> $newclosebalance, 
    //         'status'=> '1', 
    //         'insertdatetime'=> $updatedatetime, 
    //         'tbl_user_idtbl_user'=> $userID, 
    //         'tbl_account_idtbl_account'=> $bankaccount, 
    //         'tbl_company_idtbl_company'=> $companyid, 
    //         'tbl_company_branch_idtbl_company_branch'=> $branchid, 
    //         'tbl_master_idtbl_master'=> $masterID
    //     );

    //     $this->db->insert('tbl_pettycash_reimburse', $data);

    //     $reimburseID=$this->db->insert_id();

    //     foreach($tabledata AS $rowdatalist){
    //         $pettycashID=$rowdatalist->pettycashid;

    //         $datamany = array(
    //             'tbl_pettycash_reimburse_idtbl_pettycash_reimburse'=> $reimburseID, 
    //             'tbl_pettycash_idtbl_pettycash'=> $pettycashID
    //         );
    
    //         $this->db->insert('tbl_pettycash_reimburse_has_tbl_pettycash', $datamany);

    //         $data = array(
    //             'reimbursestatus'=> '1'
    //         );
    
    //         $this->db->where('idtbl_pettycash', $pettycashID);
    //         $this->db->update('tbl_pettycash', $data);
    //     }

    //     $code='000000'.$reimburseID;
    //     $newstring = substr($code, -6);
    //     $reimbursecode=$prefix.$newstring;

    //     $dataupdate = array(
    //         'reimbursecode'=> $reimbursecode
    //     );
    //     $this->db->where('idtbl_pettycash_reimburse', $reimburseID);
    //     $this->db->update('tbl_pettycash_reimburse', $dataupdate);

    //     $this->db->trans_complete();

    //     if ($this->db->trans_status() === TRUE) {
    //         $this->db->trans_commit();
            
    //         $actionObj=new stdClass();
    //         $actionObj->icon='fas fa-save';
    //         $actionObj->title='';
    //         $actionObj->message='Record Added Successfully';
    //         $actionObj->url='';
    //         $actionObj->target='_blank';
    //         $actionObj->type='success';

    //         $actionJSON=json_encode($actionObj);
            
    //         $obj=new stdClass();
    //         $obj->status=1;
    //         $obj->action=$actionJSON;

    //         echo json_encode($obj);
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
            
    //         $obj=new stdClass();
    //         $obj->status=0;
    //         $obj->action=$actionJSON;

    //         echo json_encode($obj);
    //     }
    // }
    public function Pettycashreimburseinsertupdate(){
        try {
            $userID         = $_SESSION['userid'];
            $updatedatetime = date('Y-m-d H:i:s');
            $today          = date('Y-m-d');

            // ── Input ─────────────────────────────────────────────────────────
            $companyid   = $this->input->post('companyid');
            $branchid    = $this->input->post('branchid');
            $bankaccount = $this->input->post('bankaccount');
            $reimbursedate = $this->input->post('reimbursedate');
            $transactiontype = $this->input->post('transactiontype');
            $reimbursebal= str_replace(',', '', $this->input->post('reimbursebal'));
            $tabledata   = json_decode($this->input->post('tabledata'));

            // ── Validate inputs ───────────────────────────────────────────────
            if(empty($companyid) || empty($branchid)){
                throw new Exception('Company and Branch are required');
            }
            if(empty($bankaccount)){
                throw new Exception('Bank account is required');
            }
            if(empty($reimbursebal) || $reimbursebal <= 0){
                throw new Exception('Reimburse amount is required');
            }
            if(empty($tabledata) || !is_array($tabledata)){
                throw new Exception('No petty cash records selected for reimbursement');
            }

            // ── Fetch latest petty cash summary ───────────────────────────────
            $this->db->select('closebal, tbl_account_idtbl_account');
            $this->db->from('tbl_pettycash_summary');
            $this->db->where('tbl_company_idtbl_company', $companyid);
            $this->db->where('tbl_company_branch_idtbl_company_branch', $branchid);
            $this->db->where('status', 1);
            $this->db->order_by('idtbl_pettycash_summary', 'DESC');
            $this->db->limit(1);

            $respond = $this->db->get();

            if(!$respond || $respond->num_rows() == 0){
                throw new Exception('Petty cash summary record not found');
            }

            $currentClosebal = $respond->row(0)->closebal;
            $newclosebalance = $currentClosebal + $reimbursebal;

            // ── Resolve period master ─────────────────────────────────────────
            $masterdata = get_account_period_acco_date($companyid, $branchid, $reimbursedate);

            if(empty($masterdata) || empty($masterdata->idtbl_master)){
                throw new Exception('Record Error, Active account period not found for your reimbursement date.');
            }

            $prefix   = generate_prefix($companyid, $branchid, $reimbursedate, 'PR');
            $masterID = $masterdata->idtbl_master;

            if(empty($prefix)){
                throw new Exception('Record Error, Prefix could not be defined by system');
            }

            // ── Begin Transaction ─────────────────────────────────────────────
            $this->db->trans_begin();

            // Insert reimbursement header
            $this->db->insert('tbl_pettycash_reimburse', [
                'date'                                      => $reimbursedate,
                'openbal'                                   => $currentClosebal,
                'reimursebal'                               => $reimbursebal,
                'closebal'                                  => $newclosebalance,
                'status'                                    => '1',
                'insertdatetime'                            => $updatedatetime,
                'tbl_user_idtbl_user'                       => $userID,
                'tbl_account_idtbl_account'                 => $bankaccount,
                'tbl_company_idtbl_company'                 => $companyid,
                'tbl_company_branch_idtbl_company_branch'   => $branchid,
                'tbl_master_idtbl_master'                   => $masterID,
                'tbl_receivable_type_idtbl_receivable_type' => $transactiontype
            ]);

            $reimburseID = $this->db->insert_id();

            if(empty($reimburseID)){
                throw new Exception('Record Error, Failed to insert reimbursement record');
            }

            // ── Link petty cash records to reimbursement ──────────────────────
            foreach($tabledata as $rowdatalist){
                $pettycashID = $rowdatalist->pettycashid;

                if(empty($pettycashID)){
                    throw new Exception('Invalid petty cash ID in selected records');
                }

                // Insert many-to-many link
                $this->db->insert('tbl_pettycash_reimburse_has_tbl_pettycash', [
                    'tbl_pettycash_reimburse_idtbl_pettycash_reimburse' => $reimburseID,
                    'tbl_pettycash_idtbl_pettycash'                     => $pettycashID
                ]);

                // Mark petty cash record as reimbursed
                $this->db->where('idtbl_pettycash', $pettycashID);
                $this->db->update('tbl_pettycash', [
                    'reimbursestatus' => '1'
                ]);
            }

            // ── Update reimbursement code — zero-padded suffix ────────────────
            $code          = '000000' . $reimburseID;
            $newstring     = substr($code, -6);
            $reimbursecode = $prefix . $newstring;

            $this->db->where('idtbl_pettycash_reimburse', $reimburseID);
            $this->db->update('tbl_pettycash_reimburse', [
                'reimbursecode' => $reimbursecode
            ]);

            // ── Complete Transaction ──────────────────────────────────────────
            $this->db->trans_complete();

            if($this->db->trans_status() === TRUE){
                $this->db->trans_commit();
                $this->_jsonResponse(1, 'fas fa-save', 'Record Added Successfully', 'success');
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
    // public function Accountsubcategorystatus($x, $y){
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

    //         $this->db->where('idtbl_account_subcategory', $recordID);
    //         $this->db->update('tbl_account_subcategory', $data);

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
    //             redirect('Accountsubcategory');                
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
    //             redirect('Accountsubcategory');
    //         }
    //     }
    //     else if($type==2){
    //         $data = array(
    //             'status' => '2',
    //             'updateuser'=> $userID, 
    //             'updatedatetime'=> $updatedatetime
    //         );

    //         $this->db->where('idtbl_account_subcategory', $recordID);
    //         $this->db->update('tbl_account_subcategory', $data);

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
    //             redirect('Accountsubcategory');                
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
    //             redirect('Accountsubcategory');
    //         }
    //     }
    //     else if($type==3){
    //         $data = array(
    //             'status' => '3',
    //             'updateuser'=> $userID, 
    //             'updatedatetime'=> $updatedatetime
    //         );

    //         $this->db->where('idtbl_account_subcategory', $recordID);
    //         $this->db->update('tbl_account_subcategory', $data);

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
    //             redirect('Accountsubcategory');                
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
    //             redirect('Accountsubcategory');
    //         }
    //     }
    // }
    public function Accountsubcategorystatus($x, $y){
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
    
            $this->db->where('idtbl_account_subcategory', $recordID);
            $this->db->update('tbl_account_subcategory', [
                'status'         => $config['status'],
                'updateuser'     => $userID,
                'updatedatetime' => $updatedatetime
            ]);
    
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
                redirect('Accountsubcategory');
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
            redirect('Accountsubcategory');
        }
    }
    public function Accountsubcategoryedit(){
        $recordID=$this->input->post('recordID');

        $this->db->select('*');
        $this->db->from('tbl_account_subcategory');
        $this->db->where('idtbl_account_subcategory', $recordID);
        $this->db->where('status', 1);

        $respond=$this->db->get();

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_account_subcategory;
        // $obj->code=$respond->row(0)->code;
        $obj->subcategory=$respond->row(0)->subcategory;
        $obj->accountcategory=$respond->row(0)->tbl_account_category_idtbl_account_category;

        echo json_encode($obj);
    }
    public function Getaccountcategory(){
        $this->db->select('`idtbl_account_category`, `category`, `code`');
        $this->db->from('tbl_account_category');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
    public function Getpostpettycashlist(){
        $companyid=$this->input->post('companyid');
        $branchid=$this->input->post('branchid');
        
        $this->db->select('`tbl_pettycash`.`idtbl_pettycash`, `tbl_pettycash`.`date`, `tbl_pettycash`.`pettycashcode`, `tbl_pettycash`.`desc`, `tbl_pettycash`.`amount`, `tbl_account_detail`.`accountno`, `tbl_account`.`accountno` AS `chartaccountno`');
        $this->db->from('tbl_pettycash');
        $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail=tbl_pettycash.tbl_account_detail_idtbl_account_detail_exp', 'left');
        $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_pettycash.tbl_account_idtbl_account_exp', 'left');
        $this->db->where('tbl_pettycash.status', 1);
        $this->db->where('tbl_pettycash.poststatus', 1);
        $this->db->where('tbl_pettycash.reimbursestatus', 0);
        $this->db->where('tbl_pettycash.tbl_company_idtbl_company', $companyid);
        $this->db->where('tbl_pettycash.tbl_company_branch_idtbl_company_branch', $branchid);

        $respond=$this->db->get();

        $html='';
        foreach($respond->result() as $rowpostlist){
            $html.='
            <tr>
                <td width="5%" class="text-center">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheck'.$rowpostlist->idtbl_pettycash.'" value="'.$rowpostlist->idtbl_pettycash.'">
                        <label class="custom-control-label m-0" for="customCheck'.$rowpostlist->idtbl_pettycash.'"></label>
                    </div>
                </td>
                <td>'.$rowpostlist->idtbl_pettycash.'</td>
                <td>'.$rowpostlist->date.'</td>
                <td>';
                if(!empty($rowpostlist->accountno)){
                    $html.=$rowpostlist->accountno;
                }
                else{
                    $html.=$rowpostlist->chartaccountno;
                } 
                $html.='</td>
                <td>'.$rowpostlist->desc.'</td>
                <td class="text-right pettycashamount">'.number_format($rowpostlist->amount, 2).'</td>
            </tr>
            ';
        }

        echo $html;
    }
    public function Getreimbursementinfo(){
        $recordID=$this->input->post('recordID');
        
        $this->db->select('`tbl_pettycash`.`idtbl_pettycash`, `tbl_pettycash`.`date`, `tbl_pettycash`.`pettycashcode`, `tbl_pettycash`.`desc`, `tbl_pettycash`.`amount`, `tbl_account_detail`.`accountno`, `tbl_account`.`accountno` AS `chartaccountno`');
        $this->db->from('tbl_pettycash_reimburse_has_tbl_pettycash');
        $this->db->join('tbl_pettycash', 'tbl_pettycash.idtbl_pettycash=tbl_pettycash_reimburse_has_tbl_pettycash.tbl_pettycash_idtbl_pettycash', 'left');
        $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail=tbl_pettycash.tbl_account_detail_idtbl_account_detail_exp', 'left');
        $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_pettycash.tbl_account_idtbl_account_exp', 'left');
        $this->db->where('tbl_pettycash.status', 1);
        $this->db->where('tbl_pettycash.poststatus', 1);
        $this->db->where('tbl_pettycash.reimbursestatus', 1);
        $this->db->where('tbl_pettycash_reimburse_has_tbl_pettycash.tbl_pettycash_reimburse_idtbl_pettycash_reimburse', $recordID);

        $respond=$this->db->get();

        $netamount=0;
        $html='';
        $html.='
        <table class="table table-striped table-bordered table-sm small">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Chart of detail account</th>
                    <th>Narration</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>';
            foreach($respond->result() as $rowdata){
                $netamount+=$rowdata->amount;
                $html.='
                <tr>
                    <td>'.$rowdata->date.'</td>
                    <td>';
                    if(!empty($rowdata->accountno)){
                        $html.=$rowdata->accountno;
                    }
                    else{
                        $html.=$rowdata->chartaccountno;
                    } 
                    $html.='</td>
                    <td>'.$rowdata->desc.'</td>
                    <td class="text-right">'.number_format($rowdata->amount, 2).'</td>
                </tr>
                ';
            }
            $html.='</tbody>
        </table>
        <div class="row">
            <div class="col-12 text-right">
                <h2>Rs. '.number_format($netamount, 2).'</h2>
            </div>
        </div>
        ';

        echo $html;
    }
    public function Approvereimbursement(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];

        $recordID=$this->input->post('recordID');
        $type=$this->input->post('type');

        $updatedatetime=date('Y-m-d H:i:s');

        if($type==1){
            $data = array(
                'approvestatus'=> '1',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );
    
            $this->db->where('idtbl_pettycash_reimburse', $recordID);
            $this->db->update('tbl_pettycash_reimburse', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-check';
                $actionObj->title='';
                $actionObj->message='Record Approve Successfully';
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
            $data = array(
                'approvestatus'=> '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );
    
            $this->db->where('idtbl_pettycash_reimburse', $recordID);
            $this->db->update('tbl_pettycash_reimburse', $data);

            $this->db->where('tbl_pettycash_reimburse_idtbl_pettycash_reimburse', $recordID);
            $this->db->delete('tbl_pettycash_reimburse_has_tbl_pettycash');

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-check';
                $actionObj->title='';
                $actionObj->message='Record Reject Successfully';
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
    }
    // public function Pettycashreimbursechequecreate(){
    //     $this->db->trans_begin();

    //     $userID=$_SESSION['userid'];

    //     $chequedate=$this->input->post('chequedate');
    //     $chequedesc=$this->input->post('chequedesc');
    //     $recordID=$this->input->post('hidechequereimburseid');

    //     $updatedatetime=date('Y-m-d H:i:s');
    //     $today=date('Y-m-d');

    //     $this->db->select('*');
    //     $this->db->from('tbl_pettycash_reimburse');
    //     $this->db->where('status', 1);
    //     $this->db->where('idtbl_pettycash_reimburse', $recordID);

    //     $respond=$this->db->get();

    //     $reimburseID=$respond->row(0)->idtbl_pettycash_reimburse;
    //     $bankaccountID=$respond->row(0)->tbl_account_idtbl_account;
    //     $companyid=$respond->row(0)->tbl_company_idtbl_company;
    //     $branchid=$respond->row(0)->tbl_company_branch_idtbl_company_branch;

    //     //Get Next Cheque No
    //     $sql="SELECT tbl_cheque_info.idtbl_cheque_info, IFNULL(LPAD(drv.chno+1, 6, '0'), tbl_cheque_info.startno) AS chno FROM tbl_cheque_info LEFT OUTER JOIN (SELECT tbl_cheque_info_idtbl_cheque_info, max(CAST(chequeno AS UNSIGNED)) AS chno FROM tbl_cheque_issue GROUP BY tbl_cheque_info_idtbl_cheque_info) AS drv ON tbl_cheque_info.idtbl_cheque_info=drv.tbl_cheque_info_idtbl_cheque_info WHERE tbl_account_idtbl_account=? AND IFNULL(drv.chno, 0)<CAST(tbl_cheque_info.endno AS UNSIGNED) AND tbl_cheque_info.status=? limit 1";
    //     $respondcheque=$this->db->query($sql, array($bankaccountID, 1));

    //     $chequeinfoID=$respondcheque->row(0)->idtbl_cheque_info;
    //     $chequeno=$respondcheque->row(0)->chno;

    //     // Get Petty Cash Account
    //     $this->db->where('tbl_account_allocation.companybank', $companyid);
    //     $this->db->where('tbl_account_allocation.branchcompanybank', $branchid);
    //     $this->db->where('tbl_account.specialcate', 36);
    //     $this->db->where('tbl_account.status', 1);
    //     $this->db->where('tbl_account_allocation.status', 1);
    //     $this->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
	// 	$this->db->select('`tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`');
	// 	$this->db->from('tbl_account');
	// 	$this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');

    //     $respondpettyaccount=$this->db->get();

    //     //Petty Cash Summery
    //     $datapettysummery = array(
    //         'date'=> $today, 
    //         'openbal'=> $respond->row(0)->openbal, 
    //         'postbal'=> '0', 
    //         'reimbal'=> $respond->row(0)->reimursebal, 
    //         'closebal'=> $respond->row(0)->closebal, 
    //         'status'=> 1, 
    //         'insertdatetime'=> $updatedatetime,
    //         'tbl_user_idtbl_user'=> $userID, 
    //         'tbl_account_idtbl_account'=> $respondpettyaccount->row(0)->idtbl_account, 
    //         'tbl_company_idtbl_company'=> $companyid, 
    //         'tbl_company_branch_idtbl_company_branch'=> $branchid, 
    //         'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
    //         'tbl_pettycash_reimburse_idtbl_pettycash_reimburse'=> $reimburseID
    //     );
    //     $this->db->insert('tbl_pettycash_summary', $datapettysummery);

    //     $i=1; 

    //     //Bank Account Credit
    //     $prefix  = generate_prefix($companyid, $branchid, $$respond->row(0)->date, 'AT');
    //     $batchno = tr_batch_num($prefix, $branchid);

    //     $datacredit = array(
    //         'tradate'=> $respond->row(0)->date, 
    //         'batchno'=> $batchno, 
    //         'trabatchotherno'=> $respond->row(0)->reimbursecode, 
    //         'tratype'=> 'P', 
    //         'seqno'=> $i, 
    //         'crdr'=> 'C', 
    //         'accamount'=> $respond->row(0)->reimursebal, 
    //         'narration'=> $chequedesc, 
    //         'totamount'=> $respond->row(0)->reimursebal, 
    //         'status'=> '1', 
    //         'insertdatetime'=> $updatedatetime, 
    //         'tbl_user_idtbl_user'=> $userID,
    //         'tbl_account_idtbl_account'=> $respond->row(0)->tbl_account_idtbl_account,
    //         'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
    //         'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
    //         'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
    //     );
    //     $this->db->insert('tbl_account_transaction', $datacredit);

    //     $datacreditfull = array(
    //         'tradate'=> $respond->row(0)->date, 
    //         'batchno'=> $batchno, 
    //         'tratype'=> 'P', 
    //         'crdr'=> 'C', 
    //         'accamount'=> $respond->row(0)->reimursebal, 
    //         'narration'=> $chequedesc, 
    //         'totamount'=> $respond->row(0)->reimursebal, 
    //         'status'=> '1', 
    //         'insertdatetime'=> $updatedatetime, 
    //         'tbl_user_idtbl_user'=> $userID,
    //         'tbl_account_idtbl_account'=> $respond->row(0)->tbl_account_idtbl_account,
    //         'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
    //         'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
    //         'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
    //     );
    //     $this->db->insert('tbl_account_transaction_full', $datacreditfull);

    //     $i++;

    //     //Petty Cash Account Debit
    //     $datadebit = array(
    //         'tradate'=> $respond->row(0)->date, 
    //         'batchno'=> $batchno, 
    //         'trabatchotherno'=> $respond->row(0)->reimbursecode, 
    //         'tratype'=> 'P', 
    //         'seqno'=> $i, 
    //         'crdr'=> 'D', 
    //         'accamount'=> $respond->row(0)->reimursebal, 
    //         'narration'=> $chequedesc, 
    //         'totamount'=> $respond->row(0)->reimursebal, 
    //         'status'=> '1', 
    //         'insertdatetime'=> $updatedatetime, 
    //         'tbl_user_idtbl_user'=> $userID,
    //         'tbl_account_idtbl_account'=> $respondpettyaccount->row(0)->idtbl_account,
    //         'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
    //         'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
    //         'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
    //     );
    //     $this->db->insert('tbl_account_transaction', $datadebit);

    //     $datadebitfull = array(
    //         'tradate'=> $respond->row(0)->date, 
    //         'batchno'=> $batchno, 
    //         'tratype'=> 'P', 
    //         'crdr'=> 'D', 
    //         'accamount'=> $respond->row(0)->reimursebal, 
    //         'narration'=> $chequedesc, 
    //         'totamount'=> $respond->row(0)->reimursebal, 
    //         'status'=> '1', 
    //         'insertdatetime'=> $updatedatetime, 
    //         'tbl_user_idtbl_user'=> $userID,
    //         'tbl_account_idtbl_account'=> $respondpettyaccount->row(0)->idtbl_account,
    //         'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
    //         'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
    //         'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
    //     );
    //     $this->db->insert('tbl_account_transaction_full', $datadebitfull);

    //     //Issue Cheque
    //     $datachequeissue = array(
    //         'chedate'=> $chequedate, 
    //         'chequeno'=> $chequeno, 
    //         'narration'=> $chequedesc, 
    //         'amount'=> $respond->row(0)->reimursebal, 
    //         'status'=> '1', 
    //         'insertdatetime'=> $updatedatetime, 
    //         'tbl_user_idtbl_user'=> $userID, 
    //         'tbl_cheque_info_idtbl_cheque_info'=> $chequeinfoID
    //     );
    //     $this->db->insert('tbl_cheque_issue', $datachequeissue);

    //     $chequeissueID=$this->db->insert_id();

    //     //Update Cheque Info
    //     $dataupdatereimburse = array(
    //         'chequeno'=> $chequeno,
    //         'chequedate'=> $chequedate,
    //         'chequecreate'=> '1',
    //         'tbl_cheque_issue_idtbl_cheque_issue'=> $chequeissueID,
    //     );
    //     $this->db->where('idtbl_pettycash_reimburse', $recordID);
    //     $this->db->update('tbl_pettycash_reimburse', $dataupdatereimburse);

    //     $this->db->trans_complete();

    //     if ($this->db->trans_status() === TRUE) {
    //         $this->db->trans_commit();
            
    //         $actionObj=new stdClass();
    //         $actionObj->icon='fas fa-save';
    //         $actionObj->title='';
    //         $actionObj->message='Cheque Create Successfully';
    //         $actionObj->url='';
    //         $actionObj->target='_blank';
    //         $actionObj->type='success';

    //         $actionJSON=json_encode($actionObj);
            
    //         $obj=new stdClass();
    //         $obj->status=1;
    //         $obj->action=$actionJSON;

    //         echo json_encode($obj);
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
            
    //         $obj=new stdClass();
    //         $obj->status=0;
    //         $obj->action=$actionJSON;

    //         echo json_encode($obj);
    //     }
    // }

    //Add transacrion type for petty cash reimbursement cheque creation on 2026-07-01
    // public function Pettycashreimbursechequecreate(){
    //     try {
    //         $userID         = $_SESSION['userid'];
    //         $updatedatetime = date('Y-m-d H:i:s');
    //         $today          = date('Y-m-d');
    
    //         // ── Input ─────────────────────────────────────────────────────────
    //         $chequedate = $this->input->post('chequedate');
    //         $chequedesc = $this->input->post('chequedesc');
    //         $recordID   = $this->input->post('hidechequereimburseid');
    
    //         // ── Validate inputs ───────────────────────────────────────────────
    //         if(empty($recordID)){
    //             throw new Exception('Reimburse record ID is required');
    //         }
    //         if(empty($chequedate)){
    //             throw new Exception('Cheque date is required');
    //         }
    //         if(empty($chequedesc)){
    //             throw new Exception('Cheque description is required');
    //         }
    
    //         // ── Fetch reimburse record ────────────────────────────────────────
    //         $this->db->select('*');
    //         $this->db->from('tbl_pettycash_reimburse');
    //         $this->db->where('status', 1);
    //         $this->db->where('idtbl_pettycash_reimburse', $recordID);
    
    //         $respond = $this->db->get();
    
    //         if(!$respond || $respond->num_rows() == 0){
    //             throw new Exception('Reimburse record not found');
    //         }
    
    //         $record       = $respond->row(0);
    //         $reimburseID  = $record->idtbl_pettycash_reimburse;
    //         $bankaccountID= $record->tbl_account_idtbl_account;
    //         $companyid    = $record->tbl_company_idtbl_company;
    //         $branchid     = $record->tbl_company_branch_idtbl_company_branch;
    
    //         // ── Get next cheque number ────────────────────────────────────────
    //         $sql = "SELECT tbl_cheque_info.idtbl_cheque_info,
    //                     IFNULL(LPAD(drv.chno+1, 6, '0'), tbl_cheque_info.startno) AS chno
    //                 FROM tbl_cheque_info
    //                 LEFT OUTER JOIN (
    //                     SELECT tbl_cheque_info_idtbl_cheque_info,
    //                         MAX(CAST(chequeno AS UNSIGNED)) AS chno
    //                     FROM tbl_cheque_issue
    //                     GROUP BY tbl_cheque_info_idtbl_cheque_info
    //                 ) AS drv ON tbl_cheque_info.idtbl_cheque_info = drv.tbl_cheque_info_idtbl_cheque_info
    //                 WHERE tbl_account_idtbl_account = ?
    //                 AND IFNULL(drv.chno, 0) < CAST(tbl_cheque_info.endno AS UNSIGNED)
    //                 AND tbl_cheque_info.status = ?
    //                 LIMIT 1";
    
    //         $respondcheque = $this->db->query($sql, [$bankaccountID, 1]);
    
    //         if(!$respondcheque || $respondcheque->num_rows() == 0){
    //             throw new Exception('No available cheque found for this bank account');
    //         }
    
    //         $chequeinfoID = $respondcheque->row(0)->idtbl_cheque_info;
    //         $chequeno     = $respondcheque->row(0)->chno;
    
    //         // ── Get petty cash account (specialcate = 36) ─────────────────────
    //         $this->db->select('tbl_account.idtbl_account, tbl_account.accountno, tbl_account.accountname');
    //         $this->db->from('tbl_account');
    //         $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');
    //         $this->db->where('tbl_account_allocation.companybank', $companyid);
    //         $this->db->where('tbl_account_allocation.branchcompanybank', $branchid);
    //         $this->db->where('tbl_account.specialcate', 36);
    //         $this->db->where('tbl_account.status', 1);
    //         $this->db->where('tbl_account_allocation.status', 1);
    //         $this->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
    
    //         $respondpettyaccount = $this->db->get();
    
    //         if(!$respondpettyaccount || $respondpettyaccount->num_rows() == 0){
    //             throw new Exception('Petty cash account not found for this company/branch');
    //         }
    
    //         $pettyAccountID = $respondpettyaccount->row(0)->idtbl_account;
    
    //         // ── Generate batch number ─────────────────────────────────────────
    //         // BUG FIX: was $$respond->row(0)->date (double $) → fixed to $record->date
    //         $prefix  = generate_prefix($companyid, $branchid, $record->date, 'AT');
    //         $batchno = tr_batch_num($prefix, $branchid);
    
    //         if(empty($batchno)){
    //             throw new Exception('Record Error, Batch no could not be defined by system');
    //         }
    
    //         // ── Begin Transaction ─────────────────────────────────────────────
    //         $this->db->trans_begin();
    
    //         // Insert petty cash summary — reimbursement
    //         $this->db->insert('tbl_pettycash_summary', [
    //             'date'                                              => $today,
    //             'openbal'                                           => $record->openbal,
    //             'postbal'                                           => '0',
    //             'reimbal'                                           => $record->reimursebal,
    //             'closebal'                                          => $record->closebal,
    //             'status'                                            => 1,
    //             'insertdatetime'                                    => $updatedatetime,
    //             'tbl_user_idtbl_user'                               => $userID,
    //             'tbl_account_idtbl_account'                         => $pettyAccountID,
    //             'tbl_company_idtbl_company'                         => $companyid,
    //             'tbl_company_branch_idtbl_company_branch'           => $branchid,
    //             'tbl_master_idtbl_master'                           => $record->tbl_master_idtbl_master,
    //             'tbl_pettycash_reimburse_idtbl_pettycash_reimburse' => $reimburseID
    //         ]);
    
    //         // ── Bank Account — Credit Entry ───────────────────────────────────
    //         $this->db->insert('tbl_account_transaction', [
    //             'tradate'                                 => $record->date,
    //             'batchno'                                 => $batchno,
    //             'trabatchotherno'                         => $record->reimbursecode,
    //             'tratype'                                 => 'P',
    //             'seqno'                                   => 1,
    //             'crdr'                                    => 'C',
    //             'accamount'                               => $record->reimursebal,
    //             'narration'                               => $chequedesc,
    //             'totamount'                               => $record->reimursebal,
    //             'status'                                  => '1',
    //             'insertdatetime'                          => $updatedatetime,
    //             'tbl_user_idtbl_user'                     => $userID,
    //             'tbl_account_idtbl_account'               => $record->tbl_account_idtbl_account,
    //             'tbl_master_idtbl_master'                 => $record->tbl_master_idtbl_master,
    //             'tbl_company_idtbl_company'               => $companyid,
    //             'tbl_company_branch_idtbl_company_branch' => $branchid
    //         ]);
    
    //         $this->db->insert('tbl_account_transaction_full', [
    //             'tradate'                                 => $record->date,
    //             'batchno'                                 => $batchno,
    //             'tratype'                                 => 'P',
    //             'crdr'                                    => 'C',
    //             'accamount'                               => $record->reimursebal,
    //             'narration'                               => $chequedesc,
    //             'totamount'                               => $record->reimursebal,
    //             'status'                                  => '1',
    //             'insertdatetime'                          => $updatedatetime,
    //             'tbl_user_idtbl_user'                     => $userID,
    //             'tbl_account_idtbl_account'               => $record->tbl_account_idtbl_account,
    //             'tbl_master_idtbl_master'                 => $record->tbl_master_idtbl_master,
    //             'tbl_company_idtbl_company'               => $companyid,
    //             'tbl_company_branch_idtbl_company_branch' => $branchid
    //         ]);
    
    //         // ── Petty Cash Account — Debit Entry ──────────────────────────────
    //         $this->db->insert('tbl_account_transaction', [
    //             'tradate'                                 => $record->date,
    //             'batchno'                                 => $batchno,
    //             'trabatchotherno'                         => $record->reimbursecode,
    //             'tratype'                                 => 'P',
    //             'seqno'                                   => 2,
    //             'crdr'                                    => 'D',
    //             'accamount'                               => $record->reimursebal,
    //             'narration'                               => $chequedesc,
    //             'totamount'                               => $record->reimursebal,
    //             'status'                                  => '1',
    //             'insertdatetime'                          => $updatedatetime,
    //             'tbl_user_idtbl_user'                     => $userID,
    //             'tbl_account_idtbl_account'               => $pettyAccountID,
    //             'tbl_master_idtbl_master'                 => $record->tbl_master_idtbl_master,
    //             'tbl_company_idtbl_company'               => $companyid,
    //             'tbl_company_branch_idtbl_company_branch' => $branchid
    //         ]);
    
    //         $this->db->insert('tbl_account_transaction_full', [
    //             'tradate'                                 => $record->date,
    //             'batchno'                                 => $batchno,
    //             'tratype'                                 => 'P',
    //             'crdr'                                    => 'D',
    //             'accamount'                               => $record->reimursebal,
    //             'narration'                               => $chequedesc,
    //             'totamount'                               => $record->reimursebal,
    //             'status'                                  => '1',
    //             'insertdatetime'                          => $updatedatetime,
    //             'tbl_user_idtbl_user'                     => $userID,
    //             'tbl_account_idtbl_account'               => $pettyAccountID,
    //             'tbl_master_idtbl_master'                 => $record->tbl_master_idtbl_master,
    //             'tbl_company_idtbl_company'               => $companyid,
    //             'tbl_company_branch_idtbl_company_branch' => $branchid
    //         ]);
    
    //         // ── Issue Cheque ──────────────────────────────────────────────────
    //         $this->db->insert('tbl_cheque_issue', [
    //             'chedate'                          => $chequedate,
    //             'chequeno'                         => $chequeno,
    //             'narration'                        => $chequedesc,
    //             'amount'                           => $record->reimursebal,
    //             'status'                           => '1',
    //             'insertdatetime'                   => $updatedatetime,
    //             'tbl_user_idtbl_user'              => $userID,
    //             'tbl_cheque_info_idtbl_cheque_info'=> $chequeinfoID
    //         ]);
    
    //         $chequeissueID = $this->db->insert_id();
    
    //         if(empty($chequeissueID)){
    //             throw new Exception('Record Error, Failed to insert cheque issue record');
    //         }
    
    //         // ── Update reimburse record with cheque info ──────────────────────
    //         $this->db->where('idtbl_pettycash_reimburse', $recordID);
    //         $this->db->update('tbl_pettycash_reimburse', [
    //             'chequeno'                        => $chequeno,
    //             'chequedate'                      => $chequedate,
    //             'chequecreate'                    => '1',
    //             'tbl_cheque_issue_idtbl_cheque_issue' => $chequeissueID
    //         ]);
    
    //         // ── Complete Transaction ──────────────────────────────────────────
    //         $this->db->trans_complete();
    
    //         if($this->db->trans_status() === TRUE){
    //             $this->db->trans_commit();
    //             $this->_jsonResponse(1, 'fas fa-save', 'Cheque Create Successfully', 'success');
    //         } else {
    //             $this->db->trans_rollback();
    //             throw new Exception('Record Error, Transaction failed');
    //         }
    
    //     } catch(Exception $e){
    //         if($this->db->trans_enabled){
    //             $this->db->trans_rollback();
    //         }
    //         $this->_jsonResponse(0, 'fas fa-warning', $e->getMessage(), 'danger');
    //     }
    // }
    
    public function Pettycashreimbursechequecreate(){
        try {
            $userID         = $_SESSION['userid'];
            $updatedatetime = date('Y-m-d H:i:s');
            $today          = date('Y-m-d');

            // ── Input ─────────────────────────────────────────────────────────
            $chequedate   = $this->input->post('chequedate');
            $chequedesc   = $this->input->post('chequedesc');
            $recordID     = $this->input->post('hidechequereimburseid');

            // ── Validate inputs ───────────────────────────────────────────────
            if(empty($recordID)){
                throw new Exception('Reimburse record ID is required');
            }
            if(empty($chequedesc)){
                throw new Exception('Description is required');
            }
            

            // ── Fetch reimburse record ────────────────────────────────────────
            $this->db->select('*');
            $this->db->from('tbl_pettycash_reimburse');
            $this->db->where('status', 1);
            $this->db->where('idtbl_pettycash_reimburse', $recordID);

            $respond = $this->db->get();

            if(!$respond || $respond->num_rows() == 0){
                throw new Exception('Reimburse record not found');
            }

            $record        = $respond->row(0);
            $reimburseID   = $record->idtbl_pettycash_reimburse;
            $bankaccountID = $record->tbl_account_idtbl_account;
            $companyid     = $record->tbl_company_idtbl_company;
            $branchid      = $record->tbl_company_branch_idtbl_company_branch;
            $transfertype  = $record->tbl_receivable_type_idtbl_receivable_type;

            // Cheque date only required for cheque payment
            if($transfertype == 2 && empty($chequedate)){
                throw new Exception('Cheque date is required');
            }

            // ── Cheque: get next cheque number — skip for online transfer ─────
            $chequeinfoID = null;
            $chequeno     = null;

            if($transfertype == 2){ // Cheque payment
                $sql = "SELECT tbl_cheque_info.idtbl_cheque_info,
                            IFNULL(LPAD(drv.chno+1, 6, '0'), tbl_cheque_info.startno) AS chno
                        FROM tbl_cheque_info
                        LEFT OUTER JOIN (
                            SELECT tbl_cheque_info_idtbl_cheque_info,
                                MAX(CAST(chequeno AS UNSIGNED)) AS chno
                            FROM tbl_cheque_issue
                            GROUP BY tbl_cheque_info_idtbl_cheque_info
                        ) AS drv ON tbl_cheque_info.idtbl_cheque_info = drv.tbl_cheque_info_idtbl_cheque_info
                        WHERE tbl_account_idtbl_account = ?
                        AND IFNULL(drv.chno, 0) < CAST(tbl_cheque_info.endno AS UNSIGNED)
                        AND tbl_cheque_info.status = ?
                        LIMIT 1";

                $respondcheque = $this->db->query($sql, [$bankaccountID, 1]);

                if(!$respondcheque || $respondcheque->num_rows() == 0){
                    throw new Exception('No available cheque found for this bank account');
                }

                $chequeinfoID = $respondcheque->row(0)->idtbl_cheque_info;
                $chequeno     = $respondcheque->row(0)->chno;
            }

            // ── Get petty cash account (specialcate = 36) ─────────────────────
            $this->db->select('tbl_account.idtbl_account, tbl_account.accountno, tbl_account.accountname');
            $this->db->from('tbl_account');
            $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');
            $this->db->where('tbl_account_allocation.companybank', $companyid);
            $this->db->where('tbl_account_allocation.branchcompanybank', $branchid);
            $this->db->where('tbl_account.specialcate', 36);
            $this->db->where('tbl_account.status', 1);
            $this->db->where('tbl_account_allocation.status', 1);
            $this->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);

            $respondpettyaccount = $this->db->get();

            if(!$respondpettyaccount || $respondpettyaccount->num_rows() == 0){
                throw new Exception('Petty cash account not found for this company/branch');
            }

            $pettyAccountID = $respondpettyaccount->row(0)->idtbl_account;

            // ── Generate batch number ─────────────────────────────────────────
            $prefix  = generate_prefix($companyid, $branchid, $record->date, 'AT');
            $batchno = tr_batch_num($prefix, $branchid);

            if(empty($batchno)){
                throw new Exception('Record Error, Batch no could not be defined by system');
            }

            // ── Begin Transaction ─────────────────────────────────────────────
            $this->db->trans_begin();

            // Insert petty cash summary — reimbursement
            $this->db->insert('tbl_pettycash_summary', [
                'date'                                              => $today,
                'openbal'                                           => $record->openbal,
                'postbal'                                           => '0',
                'reimbal'                                           => $record->reimursebal,
                'closebal'                                          => $record->closebal,
                'status'                                            => 1,
                'insertdatetime'                                    => $updatedatetime,
                'tbl_user_idtbl_user'                               => $userID,
                'tbl_account_idtbl_account'                         => $pettyAccountID,
                'tbl_company_idtbl_company'                         => $companyid,
                'tbl_company_branch_idtbl_company_branch'           => $branchid,
                'tbl_master_idtbl_master'                           => $record->tbl_master_idtbl_master,
                'tbl_pettycash_reimburse_idtbl_pettycash_reimburse' => $reimburseID
            ]);

            // ── Bank Account — Credit Entry (same for both transfer types) ────
            $this->db->insert('tbl_account_transaction', [
                'tradate'                                 => $record->date,
                'batchno'                                 => $batchno,
                'trabatchotherno'                         => $record->reimbursecode,
                'tratype'                                 => 'P',
                'seqno'                                   => 1,
                'crdr'                                    => 'C',
                'accamount'                               => $record->reimursebal,
                'narration'                               => $chequedesc,
                'totamount'                               => $record->reimursebal,
                'status'                                  => '1',
                'insertdatetime'                          => $updatedatetime,
                'tbl_user_idtbl_user'                     => $userID,
                'tbl_account_idtbl_account'               => $record->tbl_account_idtbl_account,
                'tbl_master_idtbl_master'                 => $record->tbl_master_idtbl_master,
                'tbl_company_idtbl_company'               => $companyid,
                'tbl_company_branch_idtbl_company_branch' => $branchid
            ]);

            $this->db->insert('tbl_account_transaction_full', [
                'tradate'                                 => $record->date,
                'batchno'                                 => $batchno,
                'tratype'                                 => 'P',
                'crdr'                                    => 'C',
                'accamount'                               => $record->reimursebal,
                'narration'                               => $chequedesc,
                'totamount'                               => $record->reimursebal,
                'status'                                  => '1',
                'insertdatetime'                          => $updatedatetime,
                'tbl_user_idtbl_user'                     => $userID,
                'tbl_account_idtbl_account'               => $record->tbl_account_idtbl_account,
                'tbl_master_idtbl_master'                 => $record->tbl_master_idtbl_master,
                'tbl_company_idtbl_company'               => $companyid,
                'tbl_company_branch_idtbl_company_branch' => $branchid
            ]);

            // ── Petty Cash Account — Debit Entry (same for both transfer types) ─
            $this->db->insert('tbl_account_transaction', [
                'tradate'                                 => $record->date,
                'batchno'                                 => $batchno,
                'trabatchotherno'                         => $record->reimbursecode,
                'tratype'                                 => 'P',
                'seqno'                                   => 2,
                'crdr'                                    => 'D',
                'accamount'                               => $record->reimursebal,
                'narration'                               => $chequedesc,
                'totamount'                               => $record->reimursebal,
                'status'                                  => '1',
                'insertdatetime'                          => $updatedatetime,
                'tbl_user_idtbl_user'                     => $userID,
                'tbl_account_idtbl_account'               => $pettyAccountID,
                'tbl_master_idtbl_master'                 => $record->tbl_master_idtbl_master,
                'tbl_company_idtbl_company'               => $companyid,
                'tbl_company_branch_idtbl_company_branch' => $branchid
            ]);

            $this->db->insert('tbl_account_transaction_full', [
                'tradate'                                 => $record->date,
                'batchno'                                 => $batchno,
                'tratype'                                 => 'P',
                'crdr'                                    => 'D',
                'accamount'                               => $record->reimursebal,
                'narration'                               => $chequedesc,
                'totamount'                               => $record->reimursebal,
                'status'                                  => '1',
                'insertdatetime'                          => $updatedatetime,
                'tbl_user_idtbl_user'                     => $userID,
                'tbl_account_idtbl_account'               => $pettyAccountID,
                'tbl_master_idtbl_master'                 => $record->tbl_master_idtbl_master,
                'tbl_company_idtbl_company'               => $companyid,
                'tbl_company_branch_idtbl_company_branch' => $branchid
            ]);

            // ── Cheque Issue — only for cheque payment (transfertype=1) ──────
            $chequeissueID = null;

            if($transfertype == 2){ // Cheque payment
                $this->db->insert('tbl_cheque_issue', [
                    'chedate'                          => $chequedate,
                    'chequeno'                         => $chequeno,
                    'narration'                        => $chequedesc,
                    'amount'                           => $record->reimursebal,
                    'chepaytype'                       => 2,  // petty cash reimburse type
                    'status'                           => '1',
                    'insertdatetime'                   => $updatedatetime,
                    'tbl_user_idtbl_user'              => $userID,
                    'tbl_cheque_info_idtbl_cheque_info'=> $chequeinfoID
                ]);

                $chequeissueID = $this->db->insert_id();

                if(empty($chequeissueID)){
                    throw new Exception('Record Error, Failed to insert cheque issue record');
                }
            }

            // ── Update reimburse record ───────────────────────────────────────
            // Cheque: store cheque details
            // Online: mark as completed without cheque info
            $reimburseUpdate = [
                'chequecreate'   => '1',
                'updateuser'     => $userID,
                'updatedatetime' => $updatedatetime
            ];

            if($transfertype == 2){ // Cheque payment
                // Cheque — store cheque number, date and issue ID
                $reimburseUpdate['chequeno']                         = $chequeno;
                $reimburseUpdate['chequedate']                       = $chequedate;
                $reimburseUpdate['tbl_cheque_issue_idtbl_cheque_issue'] = $chequeissueID;
            } else {
                // Online transfer — store transfer description as reference
                $reimburseUpdate['chequeno']   = null;
                $reimburseUpdate['chequedate'] = $chequedate;
            }

            $this->db->where('idtbl_pettycash_reimburse', $recordID);
            $this->db->update('tbl_pettycash_reimburse', $reimburseUpdate);

            // ── Complete Transaction ──────────────────────────────────────────
            $this->db->trans_complete();

            if($this->db->trans_status() === TRUE){
                $this->db->trans_commit();
                $successMsg = ($transfertype == 2)
                    ? 'Cheque Created Successfully'
                    : 'Online Transfer Processed Successfully';
                $this->_jsonResponse(1, 'fas fa-save', $successMsg, 'success');
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