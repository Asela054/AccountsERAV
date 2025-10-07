<?php
class Accountingperiodinfo extends CI_Model{
    public function Accountingperiodinsertupdate(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];

        $frommonth=$this->input->post('frommonth');
        $tomonth=$this->input->post('tomonth');

        $recordOption=$this->input->post('recordOption');
        if(!empty($this->input->post('recordID'))){$recordID=$this->input->post('recordID');}

        $updatedatetime=date('Y-m-d H:i:s');
        
        $periodmonths=$this->Accountingperiodinfo->Getmonthlist($frommonth, $tomonth);
        
        $mainyear=date("Y", strtotime($frommonth));
        $lastyear=date("Y", strtotime($tomonth));
        $startdate=date("Y-m-d", strtotime($frommonth.'-01'));
        $lastdate=date("Y-m-t", strtotime($tomonth));

        $desc=$mainyear.'/'.$lastyear;

        if($recordOption==1){
            $data = array(
                'year'=> $mainyear, 
                'startdate'=> $startdate, 
                'enddate'=> $lastdate, 
                'desc'=> $desc, 
                'actstatus'=> '0', 
                'status'=> '1', 
                'insertdatetime'=> $updatedatetime, 
                'tbl_user_idtbl_user'=> $userID
            );

            $this->db->insert('tbl_finacial_year', $data);

            $finacialyearID=$this->db->insert_id();

            foreach($periodmonths as $rowperiodmonths){
                $monthno=date("n", strtotime($rowperiodmonths));
                $monthname=date("Y-F", strtotime($rowperiodmonths));

                $datamonth = array(
                    'month'=> $monthno, 
                    'monthname'=> $monthname, 
                    'activestatus'=> '0', 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_finacial_year_idtbl_finacial_year'=> $finacialyearID
                );
    
                $this->db->insert('tbl_finacial_month', $datamonth);
            }

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
                redirect('Accountingperiod');                
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
                redirect('Accountingperiod');
            }
        }
        else{
            $data = array(
                'year'=> $mainyear, 
                'startdate'=> $startdate, 
                'enddate'=> $lastdate, 
                'desc'=> $desc, 
                'status'=> '1', 
                'updateuser'=> $userID, 
                'updatedatetime' => $updatedatetime
            );

            $this->db->where('idtbl_finacial_year', $recordID);
            $this->db->update('tbl_finacial_year', $data);

            $this->db->where('tbl_finacial_year_idtbl_finacial_year', $recordID);
            $this->db->delete('tbl_finacial_month');

            foreach($periodmonths as $rowperiodmonths){
                $monthno=date("n", strtotime($rowperiodmonths));
                $monthname=date("Y-F", strtotime($rowperiodmonths));

                $datamonth = array(
                    'month'=> $monthno, 
                    'monthname'=> $monthname, 
                    'activestatus'=> '0', 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_finacial_year_idtbl_finacial_year'=> $recordID
                );
    
                $this->db->insert('tbl_finacial_month', $datamonth);
            }

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
                redirect('Accountingperiod');                
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
                redirect('Accountingperiod');
            }
        }
    }
    public function Accountingperiodstatus($x, $y){
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

            $this->db->where('idtbl_finacial_year', $recordID);
            $this->db->update('tbl_finacial_year', $data);

            $data = array(
                'status' => '1',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('tbl_finacial_year_idtbl_finacial_year', $recordID);
            $this->db->update('tbl_finacial_month', $data);

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
                redirect('Accountingperiod');                
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
                redirect('Accountingperiod');
            }
        }
        else if($type==2){
            $data = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_finacial_year', $recordID);
            $this->db->update('tbl_finacial_year', $data);

            $data = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('tbl_finacial_year_idtbl_finacial_year', $recordID);
            $this->db->update('tbl_finacial_month', $data);

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
                redirect('Accountingperiod');                
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
                redirect('Accountingperiod');
            }
        }
        else if($type==3){
            $data = array(
                'status' => '3',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_finacial_year', $recordID);
            $this->db->update('tbl_finacial_year', $data);

            $data = array(
                'status' => '3',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('tbl_finacial_year_idtbl_finacial_year', $recordID);
            $this->db->update('tbl_finacial_month', $data);

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
                redirect('Accountingperiod');                
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
                redirect('Accountingperiod');
            }
        }
    }
    public function Accountingperiodedit(){
        $recordID=$this->input->post('recordID');

        $this->db->select('*');
        $this->db->from('tbl_finacial_year');
        $this->db->where('idtbl_finacial_year', $recordID);
        $this->db->where('status', 1);

        $respond=$this->db->get();

        $frommonth=date("Y-m", strtotime($respond->row(0)->startdate));
        $tomonth=date("Y-m", strtotime($respond->row(0)->enddate));

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_finacial_year;
        $obj->frommonth=$frommonth;
        $obj->tomonth=$tomonth;

        echo json_encode($obj);
    }
    public function Getmonthlist($startMonth, $endMonth){
        $start = new DateTime($startMonth);
        $end = new DateTime($endMonth);

        $months = array();

        while ($start <= $end) {
            $months[] = $start->format('F Y'); // Format to display month name and year
            $start->modify('+1 month'); // Move to the next month
        }

        return $months;
    }
}