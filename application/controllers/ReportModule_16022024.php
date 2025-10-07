<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class ReportModule extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("ReportModuleinfo");
    }
	
	private function add_pnl_sect($sect_code, $fig_value_col, $fig_grp_sum=false, $cnt_rev=false, $idtbl_master='1'){
		$section_data = $this->ReportModuleinfo->pnlSectionDetails($sect_code, $idtbl_master);
		$total_rsFigs=count($section_data);//$section_data->num_rows();
		//echo $total_rsFigs.'<br />';var_dump($section_data);die;
		
		$row_pos=0;
		$sub_sect_ref='-1';
		
		//$fig_value=0;//10;
		$fig_total=0;
		$sect_total=0;
		
		$col_values = array('l'=>array('', ''), 'm'=>array('', ''), 'r'=>array('', ''));
		$col_grpsum = array('l'=>'m', 'm'=>'r');
		
		$pnl_sect_trdata = array('sect_total'=>0, 'sect_trlist'=>array());
		
		//$doc_sect_ref='-1';
		$tot_sect_ref=false; // keep-track-of-group-total-allocation-to-be-cleared
		
		if($total_rsFigs>0){
			while($row_pos<$total_rsFigs){
				$section_row = $section_data[$row_pos];
				$col_values[$fig_value_col][0]=number_format((float)$section_row->fig_value, 2, '.', '');
				$col_values[$fig_value_col][1]='text-right';//' class="text-right"';
		
				
				if($tot_sect_ref){
					$col_values[$col_grpsum[$fig_value_col]][0]='';
					$col_values[$col_grpsum[$fig_value_col]][1]='';
					$tot_sect_ref=false;
				}
				
				$fig_grp_name=''; // keep-section-name
				
				$fig_disp_name=$section_row->fig_name; // keep-particulars-name-to-be-presented-even-after-fetching-next-record
				
				$fig_bottom_border = NULL;//'';
				
				$sect_total+=$section_row->fig_value;
				
				if($sub_sect_ref!=$section_row->fig_sect_ref){
					$fig_grp_name=''.$section_row->sect_name.'&nbsp;';//echo '<tr><td colspan="5">'.$sect_name.'</td></tr>';
					$sub_sect_ref=$section_row->fig_sect_ref;
					$fig_total=$section_row->fig_value;
				}else{
					$fig_total+=$section_row->fig_value;
				}
				
				$row_pos++;
				//$stmt->fetch();
				//considering $section_data[$row_pos]->attr as next fetch of $section_row->attr data
				
				$col_lm=(($fig_value_col=='l') || (($fig_value_col=='m') && $fig_grp_sum));
				$col_xm=(($fig_value_col=='l') || ($fig_value_col=='m'));
				
				$grp_summary_format='text-right';
				$acc_summary_format='text-right';
				
				if($col_lm || $col_xm){
					if($col_xm){
						if($row_pos==$total_rsFigs){
							$grp_summary_format='text-right sect_col';
							$acc_summary_format='text-right sect_col';
						}
						
					}
					
					$sub_section_to_fetch = isset($section_data[$row_pos])?$section_data[$row_pos]:$section_row;
					
					if($col_lm){
						//echo $row_pos.'``'.$sub_sect_ref.'``'.$sub_section_to_fetch->fig_sect_ref.'<br />';
						//if(($sub_sect_ref!=$section_row->fig_sect_ref)||($row_pos==$total_rsFigs))
						if(($sub_sect_ref!=$sub_section_to_fetch->fig_sect_ref)||($row_pos==$total_rsFigs)){
							$tot_sect_ref=true;
							
							$grp_summary_format='text-right sect_col';
							
							
							if(($fig_value_col=='l') && ($row_pos==$total_rsFigs)){
								$col_values['r'][0]=number_format((float)$sect_total, 2, '.', '');
								$col_values['r'][1]='text-right sect_col';//' class="text-right sect_col"';
								
							}
							
							
							$col_values[$col_grpsum[$fig_value_col]][0]=number_format((float)$fig_total, 2, '.', '');
							$col_values[$col_grpsum[$fig_value_col]][1]=$acc_summary_format;//' class="'.$acc_summary_format.'"';
							//echo $row_pos.'x'.$total_rsFigs.'<br />';
							if($row_pos<$total_rsFigs){
								//$fig_bottom_border='<tr><td colspan=5>&nbsp;</td></tr>';
								$fig_bottom_border=array(array('colspan'=>5, 'class'=>'', 'tdtext'=>'&nbsp;'));
							}
						}
						
						if($fig_grp_name!=''){
							//echo '<tr><td colspan="5">'.$fig_grp_name.'</td></tr>';
							$pnl_sect_trdata['sect_trlist'][] = array(array('colspan'=>5, 'class'=>'', 'tdtext'=>$fig_grp_name));
							$fig_grp_name='';
						}
					}
					
					$col_values[$fig_value_col][1]=$grp_summary_format;//' class="'.$grp_summary_format.'"';//' class="text-right sect_col"';
					
				}
				
				//echo '<tr><td colspan="2">'.$fig_grp_name.$fig_disp_name.'</td><td'.$col_values['l'][1].'>'.$col_values['l'][0].'</td><td'.$col_values['m'][1].'>'.$col_values['m'][0].'</td><td'.$col_values['r'][1].'>'.$col_values['r'][0].'</td></tr>';
				$pnl_sect_trdata['sect_trlist'][] = array(
														array('colspan'=>2, 'class'=>'', 'tdtext'=>$fig_grp_name.$fig_disp_name),
														array('colspan'=>'', 'class'=>$col_values['l'][1], 'tdtext'=>$col_values['l'][0]),
														array('colspan'=>'', 'class'=>$col_values['m'][1], 'tdtext'=>$col_values['m'][0]),
														array('colspan'=>'', 'class'=>$col_values['r'][1], 'tdtext'=>$col_values['r'][0])
													);
				
				//echo $fig_bottom_border;
				if(!empty($fig_bottom_border)){
					$pnl_sect_trdata['sect_trlist'][] = $fig_bottom_border;
				}
			}
		}else{
			if($cnt_rev){
				$sect_total = -1;
			}
		}
		
		$pnl_sect_trdata['sect_total'] = $sect_total;//return $sect_total;
		
		return $pnl_sect_trdata;
	}
	
	private function refine_value($sect_value){
		return (($sect_value==-1)?0:$sect_value);
	}
	
    public function periodic_pnl(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['company_period_list_filter']=get_company_list();
		$result['branch_period_list_filter']=get_all_company_branch_list();
		$result['all_account_periods']=get_all_account_periods();
		
		$result['report_gen_url'] = 'ReportModule/preview_pnl';
		$result['report_title'] = 'P & L';
		
		$this->load->view('periodic_reports_view', $result);
	}
	
	public function periodic_pnl_custom(){
		$this->load->model('ReportSettingsModuleinfo');
		
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['company_period_list_filter']=get_company_list();
		$result['branch_period_list_filter']=get_all_company_branch_list();
		$result['all_account_periods']=get_all_account_periods();
		
		$result['report_gen_url'] = 'ReportModule/preview_pnl_custom';
		$result['report_title'] = 'P & L';
		
		$result['rpthead'] = $this->ReportSettingsModuleinfo->getReportHeadSections('PNL');
		$result['rptsub'] = $this->ReportSettingsModuleinfo->getReportSubSections('PNL');
		
		$this->load->view('periodic_reports_view_custom', $result);
	}
	
	public function periodic_balancesheet(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['company_period_list_filter']=get_company_list();
		$result['branch_period_list_filter']=get_all_company_branch_list();
		$result['all_account_periods']=get_all_account_periods();
		
		$result['report_gen_url'] = 'ReportModule/preview_balancesheet';
		$result['report_title'] = 'Balance Sheet';
		
		$this->load->view('periodic_reports_view', $result);
	}
	
	public function ledger_folio(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['company_period_list_filter']=get_company_list();
		$result['branch_period_list_filter']=get_all_company_branch_list();
		$result['all_account_periods']=get_all_account_periods();
		$result['all_chart_of_acc']=$this->ReportModuleinfo->getChartOfAccounts();
		
		$result['report_gen_url'] = 'ReportModule/preview_ledgerfolio';
		$result['report_title'] = 'Ledger Folio';
		
		$this->load->view('periodic_reports_view', $result);
	}
	
	public function trial_balance(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['company_period_list_filter']=get_company_list();
		$result['branch_period_list_filter']=get_all_company_branch_list();
		$result['all_account_periods']=get_all_account_periods();
		
		$result['report_gen_url'] = 'ReportModule/preview_trialbalance';
		$result['report_title'] = 'Trial Balance';
		
		$this->load->view('periodic_reports_view', $result);
	}
	
	public function period_trial_balance(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['company_period_list_filter']=get_company_list();
		$result['branch_period_list_filter']=get_all_company_branch_list();
		$result['all_account_periods']=get_all_account_periods();
		
		$result['report_gen_url'] = 'ReportModule/preview_periodtrialbalance';
		$result['report_title'] = 'Period Trial Balance';
		
		$this->load->view('periodic_reports_view', $result);
	}
	
	public function preview_pnl(){
		$params['a'] = $this->input->post('company_id');
		$params['b'] = $this->input->post('company_branch_id');
		$params['c'] = $this->input->post('period_from');
		$params['d'] = $this->input->post('period_upto');
		
		$tot_sale_data = $this->add_pnl_sect('4', 'r');//'1'
		$tot_sale = $tot_sale_data['sect_total'];
		$sect_trlist[1] = $tot_sale_data['sect_trlist'];
		
		$open_stock = $this->ReportModuleinfo->calc_stock(true);
		
		$sale_cost_acc_data = $this->add_pnl_sect('1', 'm');//'2'
		$sale_cost_acc = $sale_cost_acc_data['sect_total'];
		$sect_trlist[2] = $sale_cost_acc_data['sect_trlist'];
		$tot_sect = $open_stock+$this->refine_value($sale_cost_acc); 
		
		$tot_stock = $this->ReportModuleinfo->calc_stock();
		$cost_of_sale = $tot_sect-$tot_stock;
		$gross_profit = $this->refine_value($tot_sale)-$cost_of_sale;
		
		$tot_other_income_data = $this->add_pnl_sect('40', 'm', true, true);
		$tot_other_income = $tot_other_income_data['sect_total'];
		$sect_trlist[4] = $tot_other_income_data['sect_trlist'];
		$tot_income = $gross_profit+$this->refine_value($tot_other_income);
		
		$tot_expenses_data = $this->add_pnl_sect('2', 'l');//'3'
		$tot_expenses = $tot_expenses_data['sect_total'];
		$sect_trlist[3] = $tot_expenses_data['sect_trlist'];
		$tot_transfer = $tot_income-$this->refine_value($tot_expenses);
		
		$params['tot_sale'] = $tot_sale;
		$params['open_stock'] = $open_stock;
		$params['sale_cost_acc'] = $sale_cost_acc;
		$params['tot_sect'] = $tot_sect;
		$params['tot_stock'] = $tot_stock;
		$params['cost_of_sale'] = $cost_of_sale;
		$params['gross_profit'] = $gross_profit;
		$params['tot_other_income'] = $tot_other_income;
		$params['tot_income'] = $tot_income;
		$params['tot_expenses'] = $tot_expenses;
		$params['tot_transfer'] = $tot_transfer;
		$params['pnl_trlist'] = $sect_trlist;
		
		$this->load->view('report_preview_pnl', $params);
	}
	
	public function preview_pnl_custom(){
		$params['a'] = $this->input->post('company_id');
		$params['b'] = $this->input->post('company_branch_id');
		$params['c'] = $this->input->post('period_from');
		$params['d'] = $this->input->post('period_upto');
		
		$this->load->view('report_preview_pnl_custom', $params);
	}
	
	public function preview_balancesheet(){
		$params['a'] = $this->input->post('company_id');
		$params['b'] = $this->input->post('company_branch_id');
		$params['c'] = $this->input->post('period_from');
		$params['d'] = $this->input->post('period_upto');
		$params['balanceinfo']=$this->ReportModuleinfo->Getbalancesheetinfo();
		$this->load->view('report_preview_balancesheet', $params);
	}
	
	public function preview_ledgerfolio(){
		$params['a'] = $this->input->post('company_id');
		$params['b'] = $this->input->post('company_branch_id');
		$params['c'] = $this->input->post('period_from');
		$params['d'] = $this->input->post('period_upto');
		
		$this->load->view('report_preview_ledgerfolio', $params);
	}
	
	public function preview_trialbalance(){
		$params['a'] = $this->input->post('company_id');
		$params['b'] = $this->input->post('company_branch_id');
		$params['c'] = $this->input->post('period_from');
		$params['d'] = $this->input->post('period_upto');
		
		$open_stock = $this->ReportModuleinfo->calc_stock(true);
		$trial_balance_data = $this->ReportModuleinfo->trialBalanceDetails($this->input->post('company_branch_id'), '1');
		
		$params['open_stock'] = $open_stock;
		$params['trial_balance_data'] = $trial_balance_data;
		
		$this->load->view('report_preview_trialbalance', $params);
	}
	
	public function preview_periodtrialbalance(){
		$params['a'] = $this->input->post('company_id');
		$params['b'] = $this->input->post('company_branch_id');
		$params['c'] = $this->input->post('period_from');
		$params['d'] = $this->input->post('period_upto');
		
		$this->load->view('report_preview_periodtrialbalance', $params);
	}
    
}