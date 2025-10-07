<?php
class Assetdepreciationinfo extends CI_Model
{
    public function Getassetsdepreciationinfo(){
        $company=$this->input->post('company');
        $branch=$this->input->post('branch');
        $monthyear=$this->input->post('month');
        $month=date("n", strtotime($monthyear));
        $year=date("Y", strtotime($monthyear));

        $this->db->select('tbl_asset.*, tbl_company.company, tbl_company_branch.branch');
        $this->db->from('tbl_asset');
        $this->db->join('tbl_asset_sell','tbl_asset_sell.tbl_asset_idtbl_asset = tbl_asset.idtbl_asset','left');
        $this->db->join('tbl_asset_destroy','tbl_asset_destroy.tbl_asset_idtbl_asset = tbl_asset.idtbl_asset','left');
        $this->db->join('tbl_company','tbl_company.idtbl_company = tbl_asset.tbl_company_idtbl_company','left');
        $this->db->join('tbl_company_branch','tbl_company_branch.idtbl_company_branch = tbl_asset.tbl_company_branch_idtbl_company_branch','left');
        $this->db->where('tbl_asset.status', 1);
        $this->db->where('tbl_asset.tbl_company_idtbl_company', $company);
        $this->db->where('tbl_asset.tbl_company_branch_idtbl_company_branch', $branch);

        $respond=$this->db->get();

        // print_r($respond->result());

        // print_r($this->db->last_query());    

        $dataarraylist=array();
        foreach($respond->result() as $rowdata){
            $depreciationlastdate=date('Y-m-d', strtotime($rowdata->depreciationstartdate . ' +'.$rowdata->depreciationyear .' year'));
            $deplastyear=date("Y", strtotime($depreciationlastdate));
            $deplastmonth=date("n", strtotime($depreciationlastdate));

            $obj=new stdClass();
            $obj->company=$rowdata->company;
            $obj->branch=$rowdata->branch;
            $obj->month=$monthyear;
            $obj->idtbl_asset=$rowdata->idtbl_asset;
            $obj->asset_name=$rowdata->asset_name;
            $obj->asset_code=$rowdata->asset_code;
            $obj->depreciationrate=$rowdata->depreciationrate;
            $obj->depreciationstartdate=$rowdata->depreciationstartdate;
            $obj->depreciationyear=$rowdata->depreciationyear;
            $obj->assetsvalue=$rowdata->assetsvalue;

            if($rowdata->tbl_depreciation_type_idtbl_depreciation_type==1){
                // Straight-line depreciation
                if($deplastyear>=$year && $deplastmonth>=$month){
                    $depreciationyear=($rowdata->assetsvalue*$rowdata->depreciationrate)/100;
                    $monthdepreciation=round(($depreciationyear/12), 2);

                    $obj->monthdepreciation=$monthdepreciation;
                }
                else{
                    $obj->monthdepreciation=0;
                }
            }
            else if($rowdata->tbl_depreciation_type_idtbl_depreciation_type==2){
                if($deplastyear>=$year && $deplastmonth>=$month){
                    $depreciationyear=($rowdata->assetsvalue*$rowdata->depreciationrate)/100;
                    $monthdepreciation=round(($depreciationyear/12), 2);

                    $obj->monthdepreciation=$monthdepreciation;
                }
                else{
                    $obj->monthdepreciation=0;
                }
            }

            array_push($dataarraylist, $obj);
        }

        $mainarray=array("data" => $dataarraylist);

        echo json_encode($mainarray);
    }  
    public function Assetdepreciationinsertupdate(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];

        $company=$this->input->post('company');
        $branch=$this->input->post('branch');
        $month=$this->input->post('month');
        $depreciationdatalist=$this->input->post('tableData');

        $updatedatetime=date('Y-m-d H:i:s');
        $today=date('Y-m-d');

        $masterdata=get_account_period($company, $branch);
        $masterID=$masterdata->idtbl_master;

        // foreach($depreciationdatalist as $rowdatalist){
        //     $data = array(
        //         'date'=> $today, 
        //         'depreciationmonth'=> $rowdatalist['col_7'], 
        //         'depreciationrate'=> str_replace('%', '', $rowdatalist['col_6']), 
        //         'depreciationamount'=> $rowdatalist['col_8'], 
        //         'status'=> '1', 
        //         'insertdatetime'=> $updatedatetime, 
        //         'tbl_user_idtbl_user'=> $userID, 
        //         'tbl_asset_idtbl_asset'=> $rowdatalist['col_1'], 
        //         'tbl_master_idtbl_master'=> $masterID, 
        //         'tbl_company_idtbl_company'=> $company, 
        //         'tbl_company_branch_idtbl_company_branch'=> $branch
        //     );

        //     $this->db->insert('tbl_depreciation_info', $data);
        // }

        // $this->db->trans_complete();
        // if ($this->db->trans_status() === TRUE) {
        //     $this->db->trans_commit();
            
        //     $actionObj=new stdClass();
        //     $actionObj->icon='fas fa-save';
        //     $actionObj->title='';
        //     $actionObj->message='Record Added Successfully';
        //     $actionObj->url='';
        //     $actionObj->target='_blank';
        //     $actionObj->type='success';

        //     $actionJSON=json_encode($actionObj);
            
        //     $obj=new stdClass();
        //     $obj->status=1;
        //     $obj->action=$actionJSON;

        //     echo json_encode($obj);
        // } else {
        //     $this->db->trans_rollback();

        //     $actionObj=new stdClass();
        //     $actionObj->icon='fas fa-warning';
        //     $actionObj->title='';
        //     $actionObj->message='Record Error';
        //     $actionObj->url='';
        //     $actionObj->target='_blank';
        //     $actionObj->type='danger';

        //     $actionJSON=json_encode($actionObj);
            
        //     $obj=new stdClass();
        //     $obj->status=0;
        //     $obj->action=$actionJSON;

        //     echo json_encode($obj);
        // }
    } 
}