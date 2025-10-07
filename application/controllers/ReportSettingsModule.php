<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class ReportSettingsModule extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("ReportSettingsModuleinfo");
    }
	
	public function store_sub_section(){
		$this->form_validation->set_rules('sect_name', 'Report sub section', 'required');
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == false) {
			$validationErrors = form_error('sect_name', '<div class="errormsg">', '</div>');
			$data = array();
			$data['resType'] = 'invalid-input';
			$data['resMsg'] = $validationErrors;
			$data['resTheme'] = 'bg-info text-white';
		}else{
			$headSectionId = $this->input->post('grp_id');
			$subSectionName = $this->input->post('sect_name');
			$userName = $_SESSION['userid'];
			
			$headerData = array('tbl_gl_report_head_section_id'=>$headSectionId, 'sub_section_name'=>$subSectionName, 
								'created_by'=>$userName);
			
			$res_info = $this->ReportSettingsModuleinfo->regUpdateSubSection($headerData);
			
			$data = array();
			
			$data['resType'] = ($res_info['head_k']!='')?'success':'unsuccess';
			$data['resMsg'] = $res_info['importMsg'];
			$data['resTheme'] = $res_info['toastType'];
			
			$data['head_k'] = $res_info['head_k'];
			
		}
		
		/*
		display-notifications
		*/
		echo json_encode($data);
	}
	
	public function toggle_sub_section_view(){
		$sectionId = $this->input->post('conf_refid');
		$sectionStatus = $this->input->post('detail_cancel');
		$userName = $_SESSION['userid'];
		
		$headerData = array('sect_cancel'=>$sectionStatus, 'updated_by'=>$userName);
		
		$res_info=$this->ReportSettingsModuleinfo->regAlterSubSection($sectionId, $headerData);
		
		$data['resMsg'] = $res_info['importMsg'];
		$data['resTheme'] = $res_info['toastType'];
		
		$data['msgErr'] = $res_info['msgErr'];
		$data['resType'] = $res_info['msgErr'];
		
		echo json_encode($data);
	}
	
	public function toggle_report_detail_view(){
		$mainSectionId = $this->input->post('grp_id');
		$sectionId = $this->input->post('sect_id');
		$subscriptionId = $this->input->post('conf_refid');
		$accId = $this->input->post('acc_id');
		$accCode = $this->input->post('acc_code');
		$reportName = $this->input->post('rpt_scope');
		
		$sectionStatus = $this->input->post('detail_cancel');
		$userName = $_SESSION['userid'];
		
		$headerData = array('tbl_gl_report_sub_section_id'=>$sectionId, 
							'tbl_gl_report_head_section_id'=>$mainSectionId, 
							'tbl_account_idtbl_account'=>$accId, //'idtbl_subaccount'=>$accId, 
							'tbl_account_accountno'=>$accCode, //'subaccount'=>$accCode, 
							'report_part_cancel'=>$sectionStatus);
		
		$res_info=$this->ReportSettingsModuleinfo->regAlterSubAccSubscription($accId, $reportName, $headerData);
		
		$data['resMsg'] = $res_info['importMsg'];
		$data['resTheme'] = $res_info['toastType'];
		
		$data['msgErr'] = $res_info['msgErr'];
		$data['resType'] = $res_info['msgErr'];
		
		$data['sub_k'] = $res_info['sub_k'];
		
		echo json_encode($data);
	}
    
}