<?php
class ReportSettingsModuleinfo extends CI_Model{
	public function getReportHeadSections($report){
		$this->db->select('id, head_section_name AS name');
		$this->db->from('tbl_gl_report_head_sections');
		$this->db->where('report_id', $report);
		return $this->db->get()->result_array();
	}
	
	public function getReportSubSections($report){
		$this->db->select('tbl_gl_report_sub_sections.id, tbl_gl_report_sub_sections.sub_section_name as `code`, tbl_gl_report_sub_sections.tbl_gl_report_head_section_id as group_id');
		$this->db->from('tbl_gl_report_sub_sections');
		$this->db->join('tbl_gl_report_head_sections', 'tbl_gl_report_sub_sections.tbl_gl_report_head_section_id=tbl_gl_report_head_sections.id', 'inner');
		$this->db->where('tbl_gl_report_head_sections.report_id', $report);
		return $this->db->get()->result_array();
	}
	
	public function regAlterSubSection($id, $alterData){
		$importmsg = '';
		$msgclass = "bg-info text-white";
		
		$msgerr = 'success';//false;
		
		//$alterData=array('updated_by'=>$this->session->userdata('admin_id'), 'qrycancel'=>1);
		
		$this->db->set('updated_at', 'NOW()', FALSE);
		$this->db->where(array('id'=>$id));
		$update=$this->db->update('tbl_gl_report_sub_sections', $alterData);
		
		if(!($this->db->affected_rows()==1)){
			$msgerr='unsuccess';//true;
			$importmsg="Something wrong";
		}
		
		return array('importMsg'=>$importmsg, 'msgErr'=>$msgerr, 'toastType'=>$msgclass);
	}
	
	public function regAlterSubAccSubscription($reportAccId, $reportDesc, $alterData){
		$importmsg = '';
		$msgclass = "bg-info text-white";
		
		$msgerr = 'success';//false;
		
		$this->db->select('tbl_gl_report_sub_section_particulars.id');
		$this->db->from('tbl_gl_report_sub_section_particulars');
		$this->db->join('tbl_gl_report_head_sections', 'tbl_gl_report_sub_section_particulars.tbl_gl_report_head_section_id = tbl_gl_report_head_sections.id', 'inner');
		$this->db->where('tbl_gl_report_head_sections.report_id', $reportDesc);
		$this->db->where('tbl_gl_report_sub_section_particulars.idtbl_subaccount', $reportAccId);
		$part_row = $this->db->get()->row();
		
		$id = (!empty($part_row))?$part_row->id:'';
		
		if(!empty($id)){
			$alterData['updated_by']=$_SESSION['userid'];//$userName;
			
			$this->db->set('updated_at', 'NOW()', FALSE);
			$this->db->where(array('id'=>$id));
			$update=$this->db->update('tbl_gl_report_sub_section_particulars', $alterData);
			
			if(!($this->db->affected_rows()==1)){
				$msgerr='unsuccess';//true;
				$importmsg="Something wrong";
			}
		}else{
			$alerData['created_by']=$_SESSION['userid'];//$userName;
			
			$this->db->set('created_at', 'NOW()', FALSE);
			$insert = $this->db->insert('tbl_gl_report_sub_section_particulars', $alterData);
			$id = $this->db->insert_id();
		}
		
		
		
		return array('importMsg'=>$importmsg, 'msgErr'=>$msgerr, 'toastType'=>$msgclass, 'sub_k'=>$id);
	}
	
	public function regUpdateSubSection($sub_section){
		$importmsg = '';
		$msgclass = "bg-info text-white";
		
		$head_k = '';
		
		if(!empty($sub_section)){
			$this->db->set('created_at', 'NOW()', FALSE);
			$insert = $this->db->insert('tbl_gl_report_sub_sections', $sub_section);
			$head_k = $this->db->insert_id();
			$importmsg = 'Sub section created';
		}
		
		return array('importMsg'=>$importmsg, 'toastType'=>$msgclass, 'head_k'=>$head_k);
	}
	
}