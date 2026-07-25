<?php
class Pettycashexpenseinfo extends CI_Model{
    // public function Pettycashexpenseinsertupdate(){
    //     $this->db->trans_begin();

    //     $userID=$_SESSION['userid'];

    //     $pettycashexpenses=$this->input->post('tableData');
    //     $company=$this->input->post('company');
    //     $branch=$this->input->post('branch');
    //     $pettycashacccount=$this->input->post('pettycashacccount');

    //     $recordOption=$this->input->post('recordOption');
    //     if(!empty($this->input->post('recordID'))){$recordID=$this->input->post('recordID');}

    //     $updatedatetime=date('Y-m-d H:i:s');
    //     $today=date('Y-m-d');

    //     if($recordOption==1){
    //         $masterdata = get_account_period_acco_date($company, $branch, $today);
    //         $prefix   = generate_prefix($company, $branch, $today, 'PV');
    //         $masterID=$masterdata->idtbl_master;
    //     }

    //     if($recordOption==1){
    //         foreach($pettycashexpenses as $pettycashexpensesdata){
    //             $chartofaccount='';
    //             $chartofdetailaccount='';
    //             if($pettycashexpensesdata['col_6']==1){$chartofaccount=$pettycashexpensesdata['col_1'];}
    //             else if($pettycashexpensesdata['col_6']==2){$chartofdetailaccount=$pettycashexpensesdata['col_1'];}

    //             $data = array(
    //                 'date'=> $today, 
    //                 'desc'=> $pettycashexpensesdata['col_3'], 
    //                 'amount'=> str_replace(',', '', $pettycashexpensesdata['col_4']), 
    //                 'status'=> '1', 
    //                 'insertdatetime'=> $updatedatetime, 
    //                 'tbl_user_idtbl_user'=> $userID,
    //                 'tbl_company_idtbl_company'=> $company,
    //                 'tbl_company_branch_idtbl_company_branch'=> $branch,
    //                 'tbl_account_idtbl_account'=> $pettycashacccount,
    //                 'tbl_account_detail_idtbl_account_detail_exp'=> $chartofdetailaccount,
    //                 'tbl_account_idtbl_account_exp'=> $chartofaccount,
    //                 'tbl_master_idtbl_master'=> $masterID
    //             );

    //             $this->db->insert('tbl_pettycash', $data);

    //             $pettycashexid=$this->db->insert_id();

    //             $code='000000'.$pettycashexid;
    //             $newstring = substr($code, -6);
    //             $pettycashcode=$prefix.$newstring;

    //             $dataupdate = array(
    //                 'pettycashcode'=> $pettycashcode
    //             );
    //             $this->db->where('idtbl_pettycash', $pettycashexid);
    //             $this->db->update('tbl_pettycash', $dataupdate);
    //         }

    //         $this->db->trans_complete();

    //         if ($this->db->trans_status() === TRUE) {
    //             $this->db->trans_commit();
                
    //             $actionObj=new stdClass();
    //             $actionObj->icon='fas fa-save';
    //             $actionObj->title='';
    //             $actionObj->message='Record Added Successfully';
    //             $actionObj->url='';
    //             $actionObj->target='_blank';
    //             $actionObj->type='success';

    //             $actionJSON=json_encode($actionObj);
                
    //             $obj=new stdClass();
    //             $obj->status=1;
    //             $obj->action=$actionJSON;

    //             echo json_encode($obj);
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
                
    //             $obj=new stdClass();
    //             $obj->status=0;
    //             $obj->action=$actionJSON;

    //             echo json_encode($obj);
    //         }
    //     }
    //     else{
            
    //     }
    // }
    public function Pettycashexpenseinsertupdate(){
        try {
            $userID         = $_SESSION['userid'];
            $updatedatetime = date('Y-m-d H:i:s');
            $today          = date('Y-m-d');
    
            // ── Input ─────────────────────────────────────────────────────────
            $pettycashexpenses = $this->input->post('tableData');
            $company           = $this->input->post('company');
            $branch            = $this->input->post('branch');
            $pettycashacccount = $this->input->post('pettycashacccount');
            $pettycashdate     = $this->input->post('pettycashdate');
            $recordOption      = $this->input->post('recordOption');
            $recordID          = $this->input->post('recordID');
    
            // ── Validate inputs ───────────────────────────────────────────────
            if(empty($company) || empty($branch)){
                throw new Exception('Company and Branch are required');
            }
            if(empty($pettycashacccount)){
                throw new Exception('Petty cash account is required');
            }
            if(empty($pettycashexpenses) || !is_array($pettycashexpenses)){
                throw new Exception('No petty cash expense records provided');
            }
    
            // ══════════════════════════════════════════════════════════════════
            // RECORD OPTION 1 — INSERT
            // ══════════════════════════════════════════════════════════════════
            if($recordOption == 1){
    
                $masterdata = get_account_period_acco_date($company, $branch, $pettycashdate);
    
                if(empty($masterdata) || empty($masterdata->idtbl_master)){
                    throw new Exception('Record Error, Active account period not found for enter date');
                }
    
                $prefix   = generate_prefix($company, $branch, $pettycashdate, 'PV');
                $masterID = $masterdata->idtbl_master;
    
                if(empty($prefix)){
                    throw new Exception('Record Error, Prefix could not be defined by system');
                }
    
                // ── Begin Transaction ─────────────────────────────────────────
                $this->db->trans_begin();
    
                foreach($pettycashexpenses as $pettycashexpensesdata){
    
                    // Resolve chart / detail account
                    $chartofaccount       = '';
                    $chartofdetailaccount = '';
    
                    if($pettycashexpensesdata['col_6'] == 1){
                        $chartofaccount       = $pettycashexpensesdata['col_1'];
                    } elseif($pettycashexpensesdata['col_6'] == 2){
                        $chartofdetailaccount = $pettycashexpensesdata['col_1'];
                    }
    
                    // Insert petty cash expense record
                    $data = array(
                        'date'                                       => $pettycashdate,
                        'desc'                                       => $pettycashexpensesdata['col_3'],
                        'amount'                                     => str_replace(',', '', $pettycashexpensesdata['col_4']),
                        'status'                                     => '1',
                        'insertdatetime'                             => $updatedatetime,
                        'tbl_user_idtbl_user'                        => $userID,
                        'tbl_company_idtbl_company'                  => $company,
                        'tbl_company_branch_idtbl_company_branch'    => $branch,
                        'tbl_account_idtbl_account'                  => $pettycashacccount,
                        'tbl_account_detail_idtbl_account_detail_exp'=> $chartofdetailaccount,
                        'tbl_account_idtbl_account_exp'              => $chartofaccount,
                        'tbl_master_idtbl_master'                    => $masterID
                    );
    
                    $this->db->insert('tbl_pettycash', $data);
                    $pettycashexid = $this->db->insert_id();
    
                    if(empty($pettycashexid)){
                        throw new Exception('Record Error, Failed to insert petty cash expense record');
                    }
    
                    // Generate petty cash code — zero-padded suffix
                    $code          = '000000' . $pettycashexid;
                    $newstring     = substr($code, -6);
                    $pettycashcode = $prefix . $newstring;
    
                    $this->db->where('idtbl_pettycash', $pettycashexid);
                    $this->db->update('tbl_pettycash', [
                        'pettycashcode' => $pettycashcode
                    ]);
                }
    
                // ── Complete Transaction ──────────────────────────────────────
                $this->db->trans_complete();
    
                if($this->db->trans_status() === TRUE){
                    $this->db->trans_commit();
                    $this->_jsonResponse(1, 'fas fa-save', 'Record Added Successfully', 'success');
                } else {
                    $this->db->trans_rollback();
                    throw new Exception('Record Error, Transaction failed');
                }
    
            }
            // ══════════════════════════════════════════════════════════════════
            // RECORD OPTION 2 — UPDATE (implement when needed)
            // ══════════════════════════════════════════════════════════════════
            else {
                if(empty($recordID)){
                    throw new Exception('Record ID is required for update');
                }
                // Update logic implementation goes here when needed. For now, we throw an exception to indicate it's not implemented.
                throw new Exception('Update operation not yet implemented');
            }
    
        } catch(Exception $e){
            if($this->db->trans_enabled){
                $this->db->trans_rollback();
            }
            $this->_jsonResponse(0, 'fas fa-warning', $e->getMessage(), 'danger');
        }
    }
    // public function Pettycashexpensestatus($x, $y){
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

    //         $this->db->where('idtbl_pettycash', $recordID);
    //         $this->db->update('tbl_pettycash', $data);

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
    //             redirect('Pettycashexpense');                
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
    //             redirect('Pettycashexpense');
    //         }
    //     }
    //     else if($type==2){
    //         $data = array(
    //             'status' => '2',
    //             'updateuser'=> $userID, 
    //             'updatedatetime'=> $updatedatetime
    //         );

    //         $this->db->where('idtbl_pettycash', $recordID);
    //         $this->db->update('tbl_pettycash', $data);

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
    //             redirect('Pettycashexpense');                
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
    //             redirect('Pettycashexpense');
    //         }
    //     }
    //     else if($type==3){
    //         $data = array(
    //             'status' => '3',
    //             'updateuser'=> $userID, 
    //             'updatedatetime'=> $updatedatetime
    //         );

    //         $this->db->where('idtbl_pettycash', $recordID);
    //         $this->db->update('tbl_pettycash', $data);

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
    //             redirect('Pettycashexpense');                
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
    //             redirect('Pettycashexpense');
    //         }
    //     }
    // }
    public function Pettycashexpensestatus($x, $y){
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

            $this->db->where('idtbl_pettycash', $recordID);
            $this->db->update('tbl_pettycash', [
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
                redirect('Pettycashexpense');
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
            redirect('Pettycashexpense');
        }
    }
    public function Getaccountbalance(){
        $companyid=$this->input->post('companyid');
        $branchid=$this->input->post('branchid');
        $accountid=$this->input->post('accountid');

        $this->db->select('`closebal`');
        $this->db->from('tbl_pettycash_summary');
        $this->db->where('tbl_account_idtbl_account', $accountid);
        $this->db->where('tbl_company_idtbl_company', $companyid);
        $this->db->where('tbl_company_branch_idtbl_company_branch', $branchid);
        $this->db->where('status', 1);
        $this->db->order_by('idtbl_pettycash_summary', 'DESC');
        $this->db->limit(1);

        $respond=$this->db->get();

        $this->db->select('SUM(`amount`) AS `pendingpostamount`');
        $this->db->from('tbl_pettycash');
        $this->db->where('tbl_account_idtbl_account', $accountid);
        $this->db->where('tbl_company_idtbl_company', $companyid);
        $this->db->where('tbl_company_branch_idtbl_company_branch', $branchid);
        $this->db->where('status', 1);
        $this->db->where('poststatus', 0);

        $respondpending=$this->db->get();

        $obj=new stdClass();
        $obj->openbal=number_format($respond->row(0)->closebal, 2);
        $obj->nopostbal=number_format($respondpending->row(0)->pendingpostamount, 2);
        $obj->closebal=number_format(($respond->row(0)->closebal-$respondpending->row(0)->pendingpostamount), 2);

        echo json_encode($obj);
    }
    public function Getviewpostinfo(){
        $recordID=$this->input->post('recordID');
        $updatedatetime=date('Y-m-d H:i:s');

        $data = array(
            'postviewtime'=> $updatedatetime
        );

        $this->db->where('idtbl_pettycash', $recordID);
        $this->db->where('poststatus', 0);
        $this->db->update('tbl_pettycash', $data);

        $this->db->select('`tbl_pettycash`.*, `tbl_account_detail`.`accountname`, `tbl_account_detail`.`accountno`, `tbl_company`.`company`, `tbl_company`.`address1`, `tbl_company`.`address2`, `tbl_company`.`mobile`, `tbl_company`.`phone`, `tbl_company`.`email`,tbl_company_branch.branch, tbl_account.accountno AS chartaccountno, tbl_account.accountname AS chartaccountname');
        $this->db->from('tbl_pettycash');
        $this->db->join('tbl_company', 'tbl_company.idtbl_company=tbl_pettycash.tbl_company_idtbl_company', 'left');
        $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_pettycash.tbl_company_branch_idtbl_company_branch', 'left');
        $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail=tbl_pettycash.tbl_account_detail_idtbl_account_detail_exp', 'left');
        $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_pettycash.tbl_account_idtbl_account_exp', 'left');
        $this->db->where('tbl_pettycash.idtbl_pettycash', $recordID);
        // $this->db->where_in('tbl_pettycash.status', array(1, 2));

        $respond=$this->db->get();

        $rupeetext=$this->Pettycashexpenseinfo->ConvertRupeeToText(round($respond->row(0)->amount, 2));

        if(!empty($respond->row(0)->tbl_account_detail_idtbl_account_detail_exp)){
            $accountname=$respond->row(0)->accountname;
        }
        else{
            $accountname=$respond->row(0)->chartaccountname;
        } 
        
        $html='';
        $html.='
        <div class="row">
            <div class="col">
                <label class="small font-weight-bold my-0">PV No: </label>
                <label class="small my-0">'.$respond->row(0)->pettycashcode.'</label><br>
                <label class="small font-weight-bold my-0">Date: </label>
                <label class="small my-0">'.$respond->row(0)->date.'</label><br>
                <label class="small font-weight-bold my-0">Company/Branch: </label>
                <label class="small my-0">'.$respond->row(0)->company.'-'.$respond->row(0)->branch.'</label>
            </div>
            <div class="col">
                <label class="small font-weight-bold my-0">Float A/C: </label>
                <label class="small my-0">'.$accountname.'</label><br>
                <label class="small font-weight-bold my-0">Please Pay: </label>
                <label class="small my-0">Cash</label><br>
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
                            <th>A/C Name</th>
                            <th>Description</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>';
                            if(!empty($respond->row(0)->tbl_account_detail_idtbl_account_detail_exp)){
                                $html.=$respond->row(0)->accountname.' - '.$respond->row(0)->accountno;
                            }
                            else{
                                $html.=$respond->row(0)->chartaccountname.' - '.$respond->row(0)->chartaccountno;
                            }
                            $html.='</td>
                            <td>'.$respond->row(0)->desc.'</td>
                            <td class="text-right">'.number_format($respond->row(0)->amount, 2).'</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2">Total Amount</th>
                            <th class="text-right">'.number_format($respond->row(0)->amount, 2).'</th>
                        </tr>
                        <tr>
                            <th colspan="3">Rupees: '.$rupeetext.'</th>
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

        echo $html;
    }
    public function ConvertRupeeToText($amount) {
        $ones = array(
            0 => '',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine',
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen'
        );

        $tens = array(
            0 => '',
            2 => 'Twenty',
            3 => 'Thirty',
            4 => 'Forty',
            5 => 'Fifty',
            6 => 'Sixty',
            7 => 'Seventy',
            8 => 'Eighty',
            9 => 'Ninety'
        );

        // Remove commas and format properly
        $amount = str_replace(',', '', $amount);
        $amount = number_format($amount, 2, '.', '');
        
        $rupees = intval($amount);
        $paisa = intval(round(($amount - $rupees) * 100));

        $words = '';

        if ($rupees > 0) {
            // Handle Crores
            if ($rupees >= 10000000) {
                $crore = intval($rupees / 10000000);
                $words .= $this->convertTwoDigit($crore, $ones, $tens) . ' Crore ';
                $rupees %= 10000000;
            }

            // Handle Lakhs
            if ($rupees >= 100000) {
                $lakh = intval($rupees / 100000);
                $words .= $this->convertTwoDigit($lakh, $ones, $tens) . ' Lakh ';
                $rupees %= 100000;
            }

            // Handle Thousands
            if ($rupees >= 1000) {
                $thousand = intval($rupees / 1000);
                $words .= $this->convertTwoDigit($thousand, $ones, $tens) . ' Thousand ';
                $rupees %= 1000;
            }

            // Handle Hundreds
            if ($rupees >= 100) {
                $hundred = intval($rupees / 100);
                $words .= $ones[$hundred] . ' Hundred ';
                $rupees %= 100;
            }

            // Handle remaining rupees (1-99)
            if ($rupees > 0) {
                $words .= $this->convertTwoDigit($rupees, $ones, $tens);
            }

            $words .= ' Rupees ';
        }

        if ($paisa > 0) {
            $words .= ' and ' . $this->convertTwoDigit($paisa, $ones, $tens) . ' Paisa';
        }

        if (trim($words) == '') {
            $words = 'Zero Rupees';
        } else {
            $words .= ' Only';
        }

        return ucwords(strtolower(trim($words)));
    }
    private function convertTwoDigit($number, $ones, $tens) {
        if ($number == 0) {
            return '';
        }
        
        if ($number < 20) {
            return $ones[$number];
        } else {
            $tenDigit = intval($number / 10);
            $oneDigit = $number % 10;
            return $tens[$tenDigit] . ($oneDigit > 0 ? ' ' . $ones[$oneDigit] : '');
        }
    } 
    // public function Pettycashexpenseposting(){
    //     $this->db->trans_begin();
    //     $recordID=$this->input->post('recordID');
    //     $updatedatetime=date('Y-m-d H:i:s');
    //     $today=date('Y-m-d');
    //     $userID=$_SESSION['userid'];

    //     $i=1;

    //     $this->db->select('`date`, `pettycashcode`, `desc`, `amount`, `poststatus`, `postuser`, `postviewtime`, `status`, `updatedatetime`, `tbl_company_idtbl_company`, `tbl_company_branch_idtbl_company_branch`, `tbl_account_idtbl_account`, `tbl_account_detail_idtbl_account_detail_exp`, `tbl_account_idtbl_account_exp`, `tbl_master_idtbl_master`');
    //     $this->db->from('tbl_pettycash');
    //     $this->db->where('idtbl_pettycash', $recordID);
    //     $this->db->where('status', 1);

    //     $respond=$this->db->get();

    //     if($respond->row(0)->poststatus==0 && $respond->row(0)->status==1){
    //         if($respond->row(0)->postviewtime>$respond->row(0)->updatedatetime){
    //             $data = array(
    //                 'poststatus'=> '1',
    //                 'postuser'=> $userID,
    //                 'postviewtime'=> NULL
    //             );
        
    //             $this->db->where('idtbl_pettycash', $recordID);
    //             $this->db->update('tbl_pettycash', $data);

    //             $company=$respond->row(0)->tbl_company_idtbl_company;
    //             $branch=$respond->row(0)->tbl_company_branch_idtbl_company_branch;
    //             $accountid=$respond->row(0)->tbl_account_idtbl_account;

    //             //Pety Cash Account Credit
    //             $prefix  = generate_prefix($company, $branch, $respond->row(0)->date, 'AT');
    //             $batchno=tr_batch_num($prefix, $branch);

    //             $datacredit = array(
    //                 'tradate'=> $respond->row(0)->date, 
    //                 'batchno'=> $batchno, 
    //                 'trabatchotherno'=> $respond->row(0)->pettycashcode, 
    //                 'tratype'=> 'P', 
    //                 'seqno'=> $i, 
    //                 'crdr'=> 'C', 
    //                 'accamount'=> $respond->row(0)->amount, 
    //                 'narration'=> $respond->row(0)->desc, 
    //                 'totamount'=> $respond->row(0)->amount, 
    //                 'status'=> '1', 
    //                 'insertdatetime'=> $updatedatetime, 
    //                 'tbl_user_idtbl_user'=> $userID,
    //                 'tbl_account_idtbl_account'=> $respond->row(0)->tbl_account_idtbl_account,
    //                 'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
    //                 'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
    //                 'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
    //             );
    //             $this->db->insert('tbl_account_transaction', $datacredit);

    //             $datacreditfull = array(
    //                 'tradate'=> $respond->row(0)->date, 
    //                 'batchno'=> $batchno, 
    //                 'tratype'=> 'P', 
    //                 'crdr'=> 'C', 
    //                 'accamount'=> $respond->row(0)->amount, 
    //                 'narration'=> $respond->row(0)->desc, 
    //                 'totamount'=> $respond->row(0)->amount, 
    //                 'status'=> '1', 
    //                 'insertdatetime'=> $updatedatetime, 
    //                 'tbl_user_idtbl_user'=> $userID,
    //                 'tbl_account_idtbl_account'=> $respond->row(0)->tbl_account_idtbl_account,
    //                 'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
    //                 'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
    //                 'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
    //             );
    //             $this->db->insert('tbl_account_transaction_full', $datacreditfull);

    //             //Detail Account Debit
    //             $i++;

    //             // $this->db->select('`tbl_account_idtbl_account`');
    //             // $this->db->from('tbl_account_detail');
    //             // $this->db->where('idtbl_account_detail', $respond->row(0)->tbl_account_detail_idtbl_account_detail);
    //             // $this->db->where('status', 1);
    //             // $responddetailchart=$this->db->get();

    //             if(!empty($respond->row(0)->tbl_account_detail_idtbl_account_detail_exp)){
    //                 $chartofaccountinfo=get_chart_account_acco_child_account($company, $branch, $respond->row(0)->tbl_account_detail_idtbl_account_detail_exp);
    //                 $chartofaccountID=$chartofaccountinfo->row(0)->idtbl_account;
    //             }
    //             else{
    //                 $chartofaccountID=$respond->row(0)->tbl_account_idtbl_account_exp;
    //             }

    //             $datadebit = array(
    //                 'tradate'=> $respond->row(0)->date, 
    //                 'batchno'=> $batchno, 
    //                 'trabatchotherno'=> $respond->row(0)->pettycashcode, 
    //                 'tratype'=> 'P', 
    //                 'seqno'=> $i, 
    //                 'crdr'=> 'D', 
    //                 'accamount'=> $respond->row(0)->amount, 
    //                 'narration'=> $respond->row(0)->desc, 
    //                 'totamount'=> $respond->row(0)->amount, 
    //                 'status'=> '1', 
    //                 'insertdatetime'=> $updatedatetime, 
    //                 'tbl_user_idtbl_user'=> $userID,
    //                 'tbl_account_idtbl_account'=> $chartofaccountID,
    //                 'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
    //                 'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
    //                 'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
    //             );
    //             $this->db->insert('tbl_account_transaction', $datadebit);

    //             $datadebitfull = array(
    //                 'tradate'=> $respond->row(0)->date, 
    //                 'batchno'=> $batchno, 
    //                 'tratype'=> 'P', 
    //                 'crdr'=> 'D', 
    //                 'accamount'=> $respond->row(0)->amount, 
    //                 'narration'=> $respond->row(0)->desc, 
    //                 'totamount'=> $respond->row(0)->amount, 
    //                 'status'=> '1', 
    //                 'insertdatetime'=> $updatedatetime, 
    //                 'tbl_user_idtbl_user'=> $userID,
    //                 'tbl_account_idtbl_account'=> $chartofaccountID,
    //                 'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
    //                 'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
    //                 'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
    //             );
    //             $this->db->insert('tbl_account_transaction_full', $datadebitfull);

    //             //Petty Cash Summery
    //             $this->db->select('`closebal`');
    //             $this->db->from('tbl_pettycash_summary');
    //             $this->db->where('tbl_account_idtbl_account', $accountid);
    //             $this->db->where('tbl_company_idtbl_company', $company);
    //             $this->db->where('tbl_company_branch_idtbl_company_branch', $branch);
    //             $this->db->where('status', 1);
    //             $this->db->order_by('idtbl_pettycash_summary', 'DESC');
    //             $this->db->limit(1);

    //             $respondpettysummery=$this->db->get();

    //             $newclosebalance=$respondpettysummery->row(0)->closebal-$respond->row(0)->amount;

    //             $datapetty = array(
    //                 'date'=> $today, 
    //                 'openbal'=> $respondpettysummery->row(0)->closebal, 
    //                 'postbal'=> $respond->row(0)->amount, 
    //                 'reimbal'=> '0', 
    //                 'closebal'=> $newclosebalance, 
    //                 'status'=> 1, 
    //                 'insertdatetime'=> $updatedatetime,
    //                 'tbl_user_idtbl_user'=> $userID, 
    //                 'tbl_account_idtbl_account'=> $accountid, 
    //                 'tbl_company_idtbl_company'=> $company, 
    //                 'tbl_company_branch_idtbl_company_branch'=> $branch, 
    //                 'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master
    //             );
    //             $this->db->insert('tbl_pettycash_summary', $datapetty);

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
    //             $actionObj=new stdClass();
    //             $actionObj->icon='fas fa-warning';
    //             $actionObj->title='';
    //             $actionObj->message='Record Error, Please check this record for information. Because this record was updated before you posted.';
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
    //     else if($respond->row(0)->status==2){
    //         $actionObj=new stdClass();
    //         $actionObj->icon='fas fa-warning';
    //         $actionObj->title='';
    //         $actionObj->message='Record Error, Record Deactivated. Kindly review the status of the record.';
    //         $actionObj->url='';
    //         $actionObj->target='_blank';
    //         $actionObj->type='warning';

    //         $actionJSON=json_encode($actionObj);
            
    //         $obj=new stdClass();
    //         $obj->status=0;
    //         $obj->action=$actionJSON;

    //         echo json_encode($obj);
    //     }
    //     else if($respond->row(0)->poststatus==1){
    //         $actionObj=new stdClass();
    //         $actionObj->icon='fas fa-warning';
    //         $actionObj->title='';
    //         $actionObj->message='Record Error, Record already posted.';
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
    public function Pettycashexpenseposting(){
        try {
            $recordID       = $this->input->post('recordID');
            $updatedatetime = date('Y-m-d H:i:s');
            $today          = date('Y-m-d');
            $userID         = $_SESSION['userid'];
            $i              = 1;

            if(empty($recordID)){
                throw new Exception('Record ID is required');
            }

            // ── Fetch petty cash record ───────────────────────────────────────
            $this->db->select('`date`, `pettycashcode`, `desc`, `amount`, `poststatus`, `postuser`, `postviewtime`, `status`, `updatedatetime`, `tbl_company_idtbl_company`, `tbl_company_branch_idtbl_company_branch`, `tbl_account_idtbl_account`, `tbl_account_detail_idtbl_account_detail_exp`, `tbl_account_idtbl_account_exp`, `tbl_master_idtbl_master`');
            $this->db->from('tbl_pettycash');
            $this->db->where('idtbl_pettycash', $recordID);
            $this->db->where('status', 1);

            $respond = $this->db->get();

            if(!$respond || $respond->num_rows() == 0){
                throw new Exception('Record not found');
            }

            $record = $respond->row(0);

            // ── Status validation checks ──────────────────────────────────────
            if($record->status == 2){
                throw new Exception('Record Error, Record Deactivated. Kindly review the status of the record.');
            }

            if($record->poststatus == 1){
                throw new Exception('Record Error, Record already posted.');
            }

            if(!($record->poststatus == 0 && $record->status == 1)){
                throw new Exception('Record Error, Invalid record state for posting.');
            }

            if($record->postviewtime <= $record->updatedatetime){
                throw new Exception('Record Error, Please check this record for information. Because this record was updated before you posted.');
            }

            // ── Resolve company/branch/account ────────────────────────────────
            $company   = $record->tbl_company_idtbl_company;
            $branch    = $record->tbl_company_branch_idtbl_company_branch;
            $accountid = $record->tbl_account_idtbl_account;

            // ── Generate batch number ─────────────────────────────────────────
            $prefix  = generate_prefix($company, $branch, $record->date, 'AT');
            $batchno = tr_batch_num($prefix, $branch);

            if(empty($batchno)){
                throw new Exception('Record Error, Batch no could not be defined by system');
            }

            // ── Begin Transaction ─────────────────────────────────────────────
            $this->db->trans_begin();

            // Update petty cash post status
            $this->db->where('idtbl_pettycash', $recordID);
            $this->db->update('tbl_pettycash', [
                'poststatus'  => '1',
                'postuser'    => $userID,
                'postviewtime'=> NULL
            ]);

            // ── Petty Cash Account — Credit Entry ─────────────────────────────
            $this->db->insert('tbl_account_transaction', [
                'tradate'                                 => $record->date,
                'batchno'                                 => $batchno,
                'trabatchotherno'                         => $record->pettycashcode,
                'tratype'                                 => 'P',
                'seqno'                                   => $i,
                'crdr'                                    => 'C',
                'accamount'                               => $record->amount,
                'narration'                               => $record->desc,
                'totamount'                               => $record->amount,
                'status'                                  => '1',
                'insertdatetime'                          => $updatedatetime,
                'tbl_user_idtbl_user'                     => $userID,
                'tbl_account_idtbl_account'               => $record->tbl_account_idtbl_account,
                'tbl_master_idtbl_master'                 => $record->tbl_master_idtbl_master,
                'tbl_company_idtbl_company'               => $company,
                'tbl_company_branch_idtbl_company_branch' => $branch
            ]);

            $this->db->insert('tbl_account_transaction_full', [
                'tradate'                                 => $record->date,
                'batchno'                                 => $batchno,
                'tratype'                                 => 'P',
                'crdr'                                    => 'C',
                'accamount'                               => $record->amount,
                'narration'                               => $record->desc,
                'totamount'                               => $record->amount,
                'status'                                  => '1',
                'insertdatetime'                          => $updatedatetime,
                'tbl_user_idtbl_user'                     => $userID,
                'tbl_account_idtbl_account'               => $record->tbl_account_idtbl_account,
                'tbl_master_idtbl_master'                 => $record->tbl_master_idtbl_master,
                'tbl_company_idtbl_company'               => $company,
                'tbl_company_branch_idtbl_company_branch' => $branch
            ]);

            // ── Expense Account — Debit Entry ─────────────────────────────────
            $i++;

            // Resolve expense chart of account
            if(!empty($record->tbl_account_detail_idtbl_account_detail_exp)){
                $chartofaccountinfo = get_chart_account_acco_child_account(
                    $company, $branch,
                    $record->tbl_account_detail_idtbl_account_detail_exp
                );
                if(!$chartofaccountinfo || $chartofaccountinfo->num_rows() == 0){
                    throw new Exception('Chart of account not found for expense detail account');
                }
                $chartofaccountID = $chartofaccountinfo->row(0)->idtbl_account;
            } else {
                $chartofaccountID = $record->tbl_account_idtbl_account_exp;
            }

            $this->db->insert('tbl_account_transaction', [
                'tradate'                                 => $record->date,
                'batchno'                                 => $batchno,
                'trabatchotherno'                         => $record->pettycashcode,
                'tratype'                                 => 'P',
                'seqno'                                   => $i,
                'crdr'                                    => 'D',
                'accamount'                               => $record->amount,
                'narration'                               => $record->desc,
                'totamount'                               => $record->amount,
                'status'                                  => '1',
                'insertdatetime'                          => $updatedatetime,
                'tbl_user_idtbl_user'                     => $userID,
                'tbl_account_idtbl_account'               => $chartofaccountID,
                'tbl_master_idtbl_master'                 => $record->tbl_master_idtbl_master,
                'tbl_company_idtbl_company'               => $company,
                'tbl_company_branch_idtbl_company_branch' => $branch
            ]);

            $this->db->insert('tbl_account_transaction_full', [
                'tradate'                                 => $record->date,
                'batchno'                                 => $batchno,
                'tratype'                                 => 'P',
                'crdr'                                    => 'D',
                'accamount'                               => $record->amount,
                'narration'                               => $record->desc,
                'totamount'                               => $record->amount,
                'status'                                  => '1',
                'insertdatetime'                          => $updatedatetime,
                'tbl_user_idtbl_user'                     => $userID,
                'tbl_account_idtbl_account'               => $chartofaccountID,
                'tbl_master_idtbl_master'                 => $record->tbl_master_idtbl_master,
                'tbl_company_idtbl_company'               => $company,
                'tbl_company_branch_idtbl_company_branch' => $branch
            ]);

            // ── Petty Cash Summary — reduce closing balance ───────────────────
            $this->db->select('closebal');
            $this->db->from('tbl_pettycash_summary');
            $this->db->where('tbl_account_idtbl_account', $accountid);
            $this->db->where('tbl_company_idtbl_company', $company);
            $this->db->where('tbl_company_branch_idtbl_company_branch', $branch);
            $this->db->where('status', 1);
            $this->db->order_by('idtbl_pettycash_summary', 'DESC');
            $this->db->limit(1);

            $respondpettysummery = $this->db->get();

            if(!$respondpettysummery || $respondpettysummery->num_rows() == 0){
                throw new Exception('Petty cash summary record not found');
            }

            $newclosebalance = $respondpettysummery->row(0)->closebal - $record->amount;

            $this->db->insert('tbl_pettycash_summary', [
                'date'                                    => $today,
                'openbal'                                 => $respondpettysummery->row(0)->closebal,
                'postbal'                                 => $record->amount,
                'reimbal'                                 => '0',
                'closebal'                                => $newclosebalance,
                'status'                                  => 1,
                'insertdatetime'                          => $updatedatetime,
                'tbl_user_idtbl_user'                     => $userID,
                'tbl_account_idtbl_account'               => $accountid,
                'tbl_company_idtbl_company'               => $company,
                'tbl_company_branch_idtbl_company_branch' => $branch,
                'tbl_master_idtbl_master'                 => $record->tbl_master_idtbl_master
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