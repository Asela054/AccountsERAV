<?php
class Accountallocationinfo extends CI_Model{
    public function Accountallocationinsertupdate(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];

        $companyid=$this->input->post('companyid');
        $branchid=$this->input->post('branchid');
        // $accounttypeid=$this->input->post('accounttypeid');
        $accountlistcate=$this->input->post('accountlistcate');
        $tabledata=json_decode($this->input->post('tabledata'));
        
        $updatedatetime=date('Y-m-d H:i:s');    

        // print_r($tabledata);

        if($accountlistcate==1){
            $this->db->where('tbl_account_idtbl_account>', '0');
            $this->db->where('companybank', $companyid);
            $this->db->where('branchcompanybank', $branchid);
            // $this->db->where('type', $accounttypeid);
            $this->db->delete('tbl_account_allocation'); 
        }
        else{
            $this->db->where('tbl_account_detail_idtbl_account_detail>', '0');
            $this->db->where('companybank', $companyid);
            $this->db->where('branchcompanybank', $branchid);
            // $this->db->where('type', $accounttypeid);
            $this->db->delete('tbl_account_allocation');
        }

        foreach($tabledata as $rowtabledata){
            $accountID=$rowtabledata->accountid;

            if($accountlistcate==1){
                $data = array(
                    // 'type'=> $accounttypeid, 
                    'companybank'=> $companyid, 
                    'branchcompanybank'=> $branchid, 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_account_idtbl_account'=> $accountID
                );
            }
            else{              
                $data = array(
                    // 'type'=> $accounttypeid, 
                    'companybank'=> $companyid, 
                    'branchcompanybank'=> $branchid, 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_account_detail_idtbl_account_detail'=> $accountID
                );
            }
            $this->db->insert('tbl_account_allocation', $data);
        }
        // print_r($this->db->last_query());
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
            $obj->status='1';
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
            $obj->status='0';
            $obj->action=$actionJSON;

            echo json_encode($obj);
        }
    }
    public function Getcompany(){
        $this->db->select('`idtbl_company`, `company`');
        $this->db->from('tbl_company');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
    public function Getaccounttype(){
        $this->db->select('`idtbl_account_type`, `accounttype`');
        $this->db->from('tbl_account_type');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
    public function Getbranchaccocompany(){
        $recordID=$this->input->post('recordID');

        $this->db->select('`idtbl_company_branch`, `branch`');
        $this->db->from('tbl_company_branch');
        $this->db->where('tbl_company_idtbl_company', $recordID);
        $this->db->where('status', 1);

        $respond=$this->db->get();

        echo json_encode($respond->result());
    }
    public function Getaccountlist(){
        $companyid=$this->input->post('companyid');
        $branchid=$this->input->post('branchid');
        // $accounttypeid=$this->input->post('accounttypeid');
        $accountlistcate=$this->input->post('accountlistcate');

        if($accountlistcate==1){
            $sql="SELECT * FROM (SELECT `idtbl_account` AS `accountid`, `accountno`, `accountname` FROM `tbl_account` LEFT JOIN `tbl_account_allocation` ON `tbl_account_allocation`.`tbl_account_idtbl_account`=`tbl_account`.`idtbl_account` WHERE `tbl_account`.`status`=? AND `tbl_account_allocation`.`tbl_account_idtbl_account` IS NULL UNION ALL SELECT `idtbl_account` AS `accountid`, `accountno`, `accountname` FROM `tbl_account` LEFT JOIN `tbl_account_allocation` ON `tbl_account_allocation`.`tbl_account_idtbl_account`=`tbl_account`.`idtbl_account` WHERE `tbl_account`.`status`=? AND `tbl_account_allocation`.`companybank`=? AND `tbl_account_allocation`.`branchcompanybank`=?) AS `daccount` LEFT JOIN (SELECT `idtbl_account_allocation`, `tbl_account_idtbl_account` FROM `tbl_account_allocation` WHERE `status`=? AND `companybank`=? AND `branchcompanybank`=? AND `tbl_account_idtbl_account`>?) AS `dallocate` ON `dallocate`.`tbl_account_idtbl_account`=`daccount`.`accountid`";
            $respond=$this->db->query($sql, array(1, 1, $companyid, $branchid, 1, $companyid, $branchid, 0));
        }
        else{
            $sql="SELECT * FROM (SELECT `tbl_account_detail`.`idtbl_account_detail` AS `accountid`, `tbl_account_detail`.`accountno`, `tbl_account_detail`.`accountname` FROM `tbl_account_detail` LEFT JOIN `tbl_account` ON `tbl_account`.`idtbl_account`=`tbl_account_detail`.`tbl_account_idtbl_account` LEFT JOIN `tbl_account_allocation` ON `tbl_account_allocation`.`tbl_account_detail_idtbl_account_detail`=`tbl_account_detail`.`idtbl_account_detail` WHERE `tbl_account`.`status`=? AND `tbl_account_detail`.`status`=? AND `tbl_account_allocation`.`tbl_account_detail_idtbl_account_detail` IS NULL UNION ALL SELECT `tbl_account_detail`.`idtbl_account_detail` AS `accountid`, `tbl_account_detail`.`accountno`, `tbl_account_detail`.`accountname` FROM `tbl_account_detail` LEFT JOIN `tbl_account` ON `tbl_account`.`idtbl_account`=`tbl_account_detail`.`tbl_account_idtbl_account` LEFT JOIN `tbl_account_allocation` ON `tbl_account_allocation`.`tbl_account_detail_idtbl_account_detail`=`tbl_account_detail`.`idtbl_account_detail` WHERE `tbl_account`.`status`=? AND `tbl_account_detail`.`status`=? AND `tbl_account_allocation`.`companybank`=? AND `tbl_account_allocation`.`branchcompanybank`=?) AS `daccount` LEFT JOIN (SELECT `idtbl_account_allocation`, `tbl_account_detail_idtbl_account_detail` FROM `tbl_account_allocation` WHERE `status`=? AND `companybank`=? AND `branchcompanybank`=? AND `tbl_account_detail_idtbl_account_detail`>?) AS `dallocate` ON `dallocate`.`tbl_account_detail_idtbl_account_detail`=`daccount`.`accountid`";
            $respond=$this->db->query($sql, array(1, 1, 1, 1, $companyid, $branchid, 1, $companyid, $branchid, 0));
        }

        // print_r($this->db->last_query());    

        $html='';
        foreach($respond->result() as $rowlistaccount){
            $html.='
            <tr>
                <td width="5%" class="text-center">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheck'.$rowlistaccount->accountid.'" value="'.$rowlistaccount->accountid.'" ';
                        if($rowlistaccount->idtbl_account_allocation!=null){$html.='checked';}
                        $html.='>
                        <label class="custom-control-label m-0" for="customCheck'.$rowlistaccount->accountid.'"></label>
                    </div>
                </td>
                <td class="d-none">'.$rowlistaccount->accountid.'</td>
                <td>'.$rowlistaccount->accountno.'</td>
                <td>'.$rowlistaccount->accountname.'</td>
            </tr>
            ';
        }
        echo $html;
    }
}