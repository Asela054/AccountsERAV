<?php
class Assetinfo extends CI_Model
{
    public function Getassettype(){
        $this->db->select('*');
        $this->db->from('tbl_asset_type');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
	public function Getdepreciationtype(){
        $this->db->select('*');
        $this->db->from('tbl_depreciation_type');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
	public function Getdepreciationcategory(){
        $this->db->select('*');
        $this->db->from('tbl_depreciation_category');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
	public function Getdepreciationmethod(){
        $this->db->select('*');
        $this->db->from('tbl_depreciation_method');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }

    public function Assetinsertupdate(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];

        $assetname=$this->input->post('assetname');
        $code=($this->input->post('code'));
        $assettype=$this->input->post('assettype');
        $depreciationtype=$this->input->post('depreciationtype');
        $depreciationcategory=$this->input->post('depreciationcategory');
		$depreciationmethod=$this->input->post('depreciationmethod');
        $currentyear=$this->input->post('currentyear');
        $depreciationyear = $this->input->post('depreciationyear');
        $depreciationstartdate = $this->input->post('sdate');
        $depreciationrate= $this->input->post('rate');
		$assetdepreciation = $this->input->post('assetdepreciation');
		$purchasedate = $this->input->post('purchasedate');
        $recordOption=$this->input->post('recordOption');
        if(!empty($this->input->post('recordID'))){$recordID=$this->input->post('recordID');}

        $updatedatetime=date('Y-m-d H:i:s');

        if($recordOption==1){
            $data = array(
                'asset_name'=> $assetname, 
                'asset_code'=> $code, 
				'currentyear'=> $currentyear, 
				'depreciationyear'=> $depreciationyear, 
                'depreciationstartdate'=>$depreciationstartdate,
                'depreciationrate'=>$depreciationrate,
				'assetdiscription'=> $assetdepreciation, 
				'purchasedate'=> $purchasedate, 
                'status'=> '1', 
                'insertdatetime'=> $updatedatetime,
                'tbl_user_idtbl_user'=> $userID,
                'tbl_asset_type_idtbl_asset_type' => $assettype,
				'tbl_depreciation_type_idtbl_depreciation_type' => $depreciationtype,
				'tbl_depreciation_category_idtbl_depreciation_category' => $depreciationcategory,
				'tbl_depreciation_method_idtbl_depreciation_method' => $depreciationmethod, 
            );

            $this->db->insert('tbl_asset', $data);

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
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Asset');                
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
                redirect('Asset');
            }
        }
        else{
            $data = array(
               
				'asset_name'=> $assetname, 
                'asset_code'=> $code, 
				'currentyear'=> $currentyear, 
				'depreciationyear'=> $depreciationyear, 
                'depreciationstartdate'=>$depreciationstartdate,
                'depreciationrate'=>$depreciationrate,
				'assetdiscription'=> $assetdepreciation, 
				'purchasedate'=> $purchasedate, 
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime,
                'tbl_user_idtbl_user'=> $userID,
                'tbl_asset_type_idtbl_asset_type' => $assettype,
				'tbl_depreciation_type_idtbl_depreciation_type' => $depreciationtype,
				'tbl_depreciation_category_idtbl_depreciation_category' => $depreciationcategory,
				'tbl_depreciation_method_idtbl_depreciation_method' => $depreciationmethod,
            );

            $this->db->where('idtbl_asset', $recordID);
            $this->db->update('tbl_asset', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-save';
                $actionObj->title='';
                $actionObj->message='Record Update Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='primary';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Asset');                
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
                redirect('Asset');
            }
        }
    }
    public function Assetstatus($x, $y){
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

            $this->db->where('idtbl_asset', $recordID);
            $this->db->update('tbl_asset', $data);

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
                redirect('Asset');                
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
                redirect('Asset');
            }
        }
        else if($type==2){
            $data = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_asset', $recordID);
            $this->db->update('tbl_asset', $data);

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
                redirect('Asset');                
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
                redirect('Asset');
            }
        }
        else if($type==3){
            $data = array(
                'status' => '3',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_asset', $recordID);
            $this->db->update('tbl_asset', $data);

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
                redirect('Asset');                
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
                redirect('Asset');
            }
        }
    }
    public function Assetedit(){
        $recordID=$this->input->post('recordID');

        $this->db->select('*');
        $this->db->from('tbl_asset');
        $this->db->where('idtbl_asset', $recordID);
        $this->db->where('status', 1);

        $respond=$this->db->get();

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_asset;
        $obj->assetname =$respond->row(0)->asset_name;
        $obj->code=$respond->row(0)->asset_code ;
        $obj->assettype=$respond->row(0)->tbl_asset_type_idtbl_asset_type;
        $obj->depreciationtype=$respond->row(0)->tbl_depreciation_type_idtbl_depreciation_type;
        $obj->depreciationcategory=$respond->row(0)->tbl_depreciation_category_idtbl_depreciation_category;
        $obj->depreciationmethod=$respond->row(0)->tbl_depreciation_method_idtbl_depreciation_method;
        $obj->currentyear=stripslashes($respond->row(0)->currentyear);
		$obj->depreciationyear=stripslashes($respond->row(0)->depreciationyear);
        $obj->depreciationstartdate=stripslashes($respond->row(0)->depreciationstartdate);
        $obj->depreciationrate=stripslashes($respond->row(0)->depreciationrate);
		$obj->assetdepreciation=stripslashes($respond->row(0)->assetdiscription);
		$obj->purchasedate=stripslashes($respond->row(0)->purchasedate);
       
        echo json_encode($obj);
    }
}
