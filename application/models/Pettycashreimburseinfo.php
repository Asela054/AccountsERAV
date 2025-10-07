<?php
class Pettycashreimburseinfo extends CI_Model{
    public function Pettycashreimburseinsertupdate(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];

        $companyid=$this->input->post('companyid');
        $branchid=$this->input->post('branchid');
        $bankaccount=$this->input->post('bankaccount');
        $reimbursebal=str_replace(',', '', $this->input->post('reimbursebal'));
        $tabledata=json_decode($this->input->post('tabledata'));

        $updatedatetime=date('Y-m-d H:i:s');
        $today=date('Y-m-d');

        $prefix=reimburse_prefix($companyid, $branchid);

        $this->db->select('`closebal`, `tbl_account_idtbl_account`');
        $this->db->from('tbl_pettycash_summary');
        $this->db->where('tbl_company_idtbl_company', $companyid);
        $this->db->where('tbl_company_branch_idtbl_company_branch', $branchid);
        $this->db->where('status', 1);
        $this->db->order_by('idtbl_pettycash_summary', 'DESC');
        $this->db->limit(1);

        $respond=$this->db->get();

        $newclosebalance=$respond->row(0)->closebal+$reimbursebal;

        $masterdata=get_account_period($companyid, $branchid);
        $masterID=$masterdata->idtbl_master;

        $data = array(
            'date'=> $today, 
            'openbal'=> $respond->row(0)->closebal, 
            'reimursebal'=> $reimbursebal, 
            'closebal'=> $newclosebalance, 
            'status'=> '1', 
            'insertdatetime'=> $updatedatetime, 
            'tbl_user_idtbl_user'=> $userID, 
            'tbl_account_idtbl_account'=> $bankaccount, 
            'tbl_company_idtbl_company'=> $companyid, 
            'tbl_company_branch_idtbl_company_branch'=> $branchid, 
            'tbl_master_idtbl_master'=> $masterID
        );

        $this->db->insert('tbl_pettycash_reimburse', $data);

        $reimburseID=$this->db->insert_id();

        foreach($tabledata AS $rowdatalist){
            $pettycashID=$rowdatalist->pettycashid;

            $datamany = array(
                'tbl_pettycash_reimburse_idtbl_pettycash_reimburse'=> $reimburseID, 
                'tbl_pettycash_idtbl_pettycash'=> $pettycashID
            );
    
            $this->db->insert('tbl_pettycash_reimburse_has_tbl_pettycash', $datamany);

            $data = array(
                'reimbursestatus'=> '1'
            );
    
            $this->db->where('idtbl_pettycash', $pettycashID);
            $this->db->update('tbl_pettycash', $data);
        }

        $code='000000'.$reimburseID;
        $newstring = substr($code, -6);
        $reimbursecode=$prefix.$newstring;

        $dataupdate = array(
            'reimbursecode'=> $reimbursecode
        );
        $this->db->where('idtbl_pettycash_reimburse', $reimburseID);
        $this->db->update('tbl_pettycash_reimburse', $dataupdate);

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
    public function Accountsubcategorystatus($x, $y){
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

            $this->db->where('idtbl_account_subcategory', $recordID);
            $this->db->update('tbl_account_subcategory', $data);

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
                redirect('Accountsubcategory');                
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
                redirect('Accountsubcategory');
            }
        }
        else if($type==2){
            $data = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_account_subcategory', $recordID);
            $this->db->update('tbl_account_subcategory', $data);

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
                redirect('Accountsubcategory');                
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
                redirect('Accountsubcategory');
            }
        }
        else if($type==3){
            $data = array(
                'status' => '3',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_account_subcategory', $recordID);
            $this->db->update('tbl_account_subcategory', $data);

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
                redirect('Accountsubcategory');                
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
                redirect('Accountsubcategory');
            }
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
    public function Pettycashreimbursechequecreate(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];

        $chequedate=$this->input->post('chequedate');
        $chequedesc=$this->input->post('chequedesc');
        $recordID=$this->input->post('hidechequereimburseid');

        $updatedatetime=date('Y-m-d H:i:s');
        $today=date('Y-m-d');

        $this->db->select('*');
        $this->db->from('tbl_pettycash_reimburse');
        $this->db->where('status', 1);
        $this->db->where('idtbl_pettycash_reimburse', $recordID);

        $respond=$this->db->get();

        $reimburseID=$respond->row(0)->idtbl_pettycash_reimburse;
        $bankaccountID=$respond->row(0)->tbl_account_idtbl_account;
        $companyid=$respond->row(0)->tbl_company_idtbl_company;
        $branchid=$respond->row(0)->tbl_company_branch_idtbl_company_branch;

        //Get Next Cheque No
        $sql="SELECT tbl_cheque_info.idtbl_cheque_info, IFNULL(LPAD(drv.chno+1, 6, '0'), tbl_cheque_info.startno) AS chno FROM tbl_cheque_info LEFT OUTER JOIN (SELECT tbl_cheque_info_idtbl_cheque_info, max(CAST(chequeno AS UNSIGNED)) AS chno FROM tbl_cheque_issue GROUP BY tbl_cheque_info_idtbl_cheque_info) AS drv ON tbl_cheque_info.idtbl_cheque_info=drv.tbl_cheque_info_idtbl_cheque_info WHERE tbl_account_idtbl_account=? AND IFNULL(drv.chno, 0)<CAST(tbl_cheque_info.endno AS UNSIGNED) AND tbl_cheque_info.status=? limit 1";
        $respondcheque=$this->db->query($sql, array($bankaccountID, 1));

        $chequeinfoID=$respondcheque->row(0)->idtbl_cheque_info;
        $chequeno=$respondcheque->row(0)->chno;

        // Get Petty Cash Account
        $this->db->where('tbl_account_allocation.companybank', $companyid);
        $this->db->where('tbl_account_allocation.branchcompanybank', $branchid);
        $this->db->where('tbl_account.specialcate', 36);
        $this->db->where('tbl_account.status', 1);
        $this->db->where('tbl_account_allocation.status', 1);
        $this->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
		$this->db->select('`tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`');
		$this->db->from('tbl_account');
		$this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');

        $respondpettyaccount=$this->db->get();

        //Petty Cash Summery
        $datapettysummery = array(
            'date'=> $today, 
            'openbal'=> $respond->row(0)->openbal, 
            'postbal'=> '0', 
            'reimbal'=> $respond->row(0)->reimursebal, 
            'closebal'=> $respond->row(0)->closebal, 
            'status'=> 1, 
            'insertdatetime'=> $updatedatetime,
            'tbl_user_idtbl_user'=> $userID, 
            'tbl_account_idtbl_account'=> $respondpettyaccount->row(0)->idtbl_account, 
            'tbl_company_idtbl_company'=> $companyid, 
            'tbl_company_branch_idtbl_company_branch'=> $branchid, 
            'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
            'tbl_pettycash_reimburse_idtbl_pettycash_reimburse'=> $reimburseID
        );
        $this->db->insert('tbl_pettycash_summary', $datapettysummery);

        $i=1; 

        //Bank Account Credit
        $prefix=trans_prefix($companyid, $branchid);
        $batchno=tr_batch_num($prefix, $branchid);

        $datacredit = array(
            'tradate'=> $respond->row(0)->date, 
            'batchno'=> $batchno, 
            'trabatchotherno'=> $respond->row(0)->reimbursecode, 
            'tratype'=> 'P', 
            'seqno'=> $i, 
            'crdr'=> 'C', 
            'accamount'=> $respond->row(0)->reimursebal, 
            'narration'=> $chequedesc, 
            'totamount'=> $respond->row(0)->reimursebal, 
            'status'=> '1', 
            'insertdatetime'=> $updatedatetime, 
            'tbl_user_idtbl_user'=> $userID,
            'tbl_account_idtbl_account'=> $respond->row(0)->tbl_account_idtbl_account,
            'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
            'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
            'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
        );
        $this->db->insert('tbl_account_transaction', $datacredit);

        $datacreditfull = array(
            'tradate'=> $respond->row(0)->date, 
            'batchno'=> $batchno, 
            'tratype'=> 'P', 
            'crdr'=> 'C', 
            'accamount'=> $respond->row(0)->reimursebal, 
            'narration'=> $chequedesc, 
            'totamount'=> $respond->row(0)->reimursebal, 
            'status'=> '1', 
            'insertdatetime'=> $updatedatetime, 
            'tbl_user_idtbl_user'=> $userID,
            'tbl_account_idtbl_account'=> $respond->row(0)->tbl_account_idtbl_account,
            'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
            'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
            'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
        );
        $this->db->insert('tbl_account_transaction_full', $datacreditfull);

        $i++;

        //Petty Cash Account Debit
        $datadebit = array(
            'tradate'=> $respond->row(0)->date, 
            'batchno'=> $batchno, 
            'trabatchotherno'=> $respond->row(0)->reimbursecode, 
            'tratype'=> 'P', 
            'seqno'=> $i, 
            'crdr'=> 'D', 
            'accamount'=> $respond->row(0)->reimursebal, 
            'narration'=> $chequedesc, 
            'totamount'=> $respond->row(0)->reimursebal, 
            'status'=> '1', 
            'insertdatetime'=> $updatedatetime, 
            'tbl_user_idtbl_user'=> $userID,
            'tbl_account_idtbl_account'=> $respondpettyaccount->row(0)->idtbl_account,
            'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
            'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
            'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
        );
        $this->db->insert('tbl_account_transaction', $datadebit);

        $datadebitfull = array(
            'tradate'=> $respond->row(0)->date, 
            'batchno'=> $batchno, 
            'tratype'=> 'P', 
            'crdr'=> 'D', 
            'accamount'=> $respond->row(0)->reimursebal, 
            'narration'=> $chequedesc, 
            'totamount'=> $respond->row(0)->reimursebal, 
            'status'=> '1', 
            'insertdatetime'=> $updatedatetime, 
            'tbl_user_idtbl_user'=> $userID,
            'tbl_account_idtbl_account'=> $respondpettyaccount->row(0)->idtbl_account,
            'tbl_master_idtbl_master'=> $respond->row(0)->tbl_master_idtbl_master,
            'tbl_company_idtbl_company'=> $respond->row(0)->tbl_company_idtbl_company,
            'tbl_company_branch_idtbl_company_branch'=> $respond->row(0)->tbl_company_branch_idtbl_company_branch
        );
        $this->db->insert('tbl_account_transaction_full', $datadebitfull);

        //Issue Cheque
        $datachequeissue = array(
            'chedate'=> $chequedate, 
            'chequeno'=> $chequeno, 
            'narration'=> $chequedesc, 
            'amount'=> $respond->row(0)->reimursebal, 
            'status'=> '1', 
            'insertdatetime'=> $updatedatetime, 
            'tbl_user_idtbl_user'=> $userID, 
            'tbl_cheque_info_idtbl_cheque_info'=> $chequeinfoID
        );
        $this->db->insert('tbl_cheque_issue', $datachequeissue);

        $chequeissueID=$this->db->insert_id();

        //Update Cheque Info
        $dataupdatereimburse = array(
            'chequeno'=> $chequeno,
            'chequedate'=> $chequedate,
            'chequecreate'=> '1',
            'tbl_cheque_issue_idtbl_cheque_issue'=> $chequeissueID,
        );
        $this->db->where('idtbl_pettycash_reimburse', $recordID);
        $this->db->update('tbl_pettycash_reimburse', $dataupdatereimburse);

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            
            $actionObj=new stdClass();
            $actionObj->icon='fas fa-save';
            $actionObj->title='';
            $actionObj->message='Cheque Create Successfully';
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