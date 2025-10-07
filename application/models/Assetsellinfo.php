<?php
class Assetsellinfo extends CI_Model
{
    public function Getasset_name(){
        $this->db->select('*');
        $this->db->from('tbl_asset');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }



    public function Assetsellinsertupdate(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];

        $asset_name=$this->input->post('asset_name');
        $date=$this->input->post('date');
        $amount=addslashes($this->input->post('amount'));
        $reason=$this->input->post('reason');

     

        $recordOption=$this->input->post('recordOption');
        if(!empty($this->input->post('recordID'))){$recordID=$this->input->post('recordID');}

        $updatedatetime=date('Y-m-d H:i:s');

        if($recordOption==1){
          
            $data = array(
                // 'asset_name' => $asset_name,
                'date'=> $date,
                'amount'=>$amount,
                'reason'=>$reason,
                'sell'=>'0',
                'status'=> '1', 
                'insertdatetime'=> $updatedatetime,
                'tbl_user_idtbl_user'=> $userID,
                'tbl_asset_idtbl_asset'=> $asset_name,
            );

            $this->db->insert('tbl_asset_sell', $data);
        
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
                redirect('Assetsell');                
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
                redirect('Assetsell');
            }
        }
        else{
            $data = array(
                // 'asset_name'=> $asset_name,
                'date'=> $date,
                'amount'=> $amount, 
                'reason'=> $reason,
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime,
                'tbl_user_idtbl_user'=> $userID,
                'tbl_asset_idtbl_asset'=> $asset_name

            );

            $this->db->where('idtbl_asset_sell', $recordID);
            $this->db->update('tbl_asset_sell', $data);

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
                redirect('Assetsell');                
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
                redirect('Assetsell');
            }
        }
    }
    public function Assetsellstatus($x, $y){
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

            $this->db->where('idtbl_asset_sell', $recordID);
            $this->db->update('tbl_asset_sell', $data);

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
                redirect('Assetsell');                
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
                redirect('Assetsell');
            }
        }
        else if($type==2){
            $data = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_asset_sell', $recordID);
            $this->db->update('tbl_asset_sell', $data);

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
                redirect('Assetsell');                
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
                redirect('Assetsell');
            }
        }
        else if($type==3){
            $data = array(
                'status' => '3',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_asset_sell', $recordID);
            $this->db->update('tbl_asset_sell', $data);

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
                redirect('Assetsell');                
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
                redirect('Assetsell');
            }
        }
    }
    public function Assetselledit(){
        $recordID=$this->input->post('recordID');

        $this->db->select('*');
        $this->db->from('tbl_asset_sell');
        $this->db->where('idtbl_asset_sell', $recordID);
        $this->db->where('status', 1);

        $respond=$this->db->get();

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_asset_sell;
        $obj->asset_name=$respond->row(0)->asset_name;
        $obj->date=$respond->row(0)->date;
        $obj->amount=$respond->row(0)->amount;
        $obj->reason=$respond->row(0)->reason;
        $obj->asset_name=$respond->row(0)->tbl_asset_idtbl_asset;

       
        

        echo json_encode($obj);
    }

    public function Assetsellsale()
    {
        $recordID = $this->input->post('recordID');

        $data = array(
            'sell' => '1',

        );

        $this->db->where('idtbl_asset_sell', $recordID);
        $this->db->update('tbl_asset_sell', $data);
    }
}