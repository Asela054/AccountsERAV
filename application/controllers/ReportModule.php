<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class ReportModule extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("ReportModuleinfo");
        $this->load->model("PnlSetupModuleinfo");
    }
	
	// private function add_pnl_sect($sect_code, $fig_value_col, $fig_grp_sum=false, $cnt_rev=false, $idtbl_master='2'){
	// 	$section_data = $this->ReportModuleinfo->pnlSectionDetails($sect_code, $idtbl_master);
	// 	$total_rsFigs=count($section_data);//$section_data->num_rows();
	// 	//echo $total_rsFigs.'<br />';var_dump($section_data);die;
		
	// 	$row_pos=0;
	// 	$sub_sect_ref='-1';
		
	// 	//$fig_value=0;//10;
	// 	$fig_total=0;
	// 	$sect_total=0;
		
	// 	$col_values = array('l'=>array('', ''), 'm'=>array('', ''), 'r'=>array('', ''));
	// 	$col_grpsum = array('l'=>'m', 'm'=>'r');
		
	// 	$pnl_sect_trdata = array('sect_total'=>0, 'sect_trlist'=>array());
		
	// 	//$doc_sect_ref='-1';
	// 	$tot_sect_ref=false; // keep-track-of-group-total-allocation-to-be-cleared
		
	// 	if($total_rsFigs>0){
	// 		while($row_pos<$total_rsFigs){
	// 			$section_row = $section_data[$row_pos];
	// 			$col_values[$fig_value_col][0]=number_format($section_row->fig_value, 2);
	// 			$col_values[$fig_value_col][1]='text-right';//' class="text-right"';
		
				
	// 			if($tot_sect_ref){
	// 				$col_values[$col_grpsum[$fig_value_col]][0]='';
	// 				$col_values[$col_grpsum[$fig_value_col]][1]='';
	// 				$tot_sect_ref=false;
	// 			}
				
	// 			$fig_grp_name=''; // keep-section-name
				
	// 			$fig_disp_name=$section_row->fig_name; // keep-particulars-name-to-be-presented-even-after-fetching-next-record
				
	// 			$fig_bottom_border = NULL;//'';
				
	// 			$sect_total+=$section_row->fig_value;
				
	// 			if($sub_sect_ref!=$section_row->fig_sect_ref){
	// 				$fig_grp_name=''.$section_row->sect_name.'&nbsp;';//echo '<tr><td colspan="5">'.$sect_name.'</td></tr>';
	// 				$sub_sect_ref=$section_row->fig_sect_ref;
	// 				$fig_total=$section_row->fig_value;
	// 			}else{
	// 				$fig_total+=$section_row->fig_value;
	// 			}
				
	// 			$row_pos++;
	// 			//$stmt->fetch();
	// 			//considering $section_data[$row_pos]->attr as next fetch of $section_row->attr data
				
	// 			$col_lm=(($fig_value_col=='l') || (($fig_value_col=='m') && $fig_grp_sum));
	// 			$col_xm=(($fig_value_col=='l') || ($fig_value_col=='m'));
				
	// 			$grp_summary_format='text-right';
	// 			$acc_summary_format='text-right';
				
	// 			if($col_lm || $col_xm){
	// 				if($col_xm){
	// 					if($row_pos==$total_rsFigs){
	// 						$grp_summary_format='text-right sect_col';
	// 						$acc_summary_format='text-right sect_col';
	// 					}
						
	// 				}
					
	// 				$sub_section_to_fetch = isset($section_data[$row_pos])?$section_data[$row_pos]:$section_row;
					
	// 				if($col_lm){
	// 					//echo $row_pos.'``'.$sub_sect_ref.'``'.$sub_section_to_fetch->fig_sect_ref.'<br />';
	// 					//if(($sub_sect_ref!=$section_row->fig_sect_ref)||($row_pos==$total_rsFigs))
	// 					if(($sub_sect_ref!=$sub_section_to_fetch->fig_sect_ref)||($row_pos==$total_rsFigs)){
	// 						$tot_sect_ref=true;
							
	// 						$grp_summary_format='text-right sect_col';
							
							
	// 						if(($fig_value_col=='l') && ($row_pos==$total_rsFigs)){
	// 							$col_values['r'][0]=number_format($sect_total, 2);
	// 							$col_values['r'][1]='text-right sect_col';//' class="text-right sect_col"';
								
	// 						}
							
							
	// 						$col_values[$col_grpsum[$fig_value_col]][0]=number_format($fig_total, 2);
	// 						$col_values[$col_grpsum[$fig_value_col]][1]=$acc_summary_format;//' class="'.$acc_summary_format.'"';
	// 						//echo $row_pos.'x'.$total_rsFigs.'<br />';
	// 						if($row_pos<$total_rsFigs){
	// 							//$fig_bottom_border='<tr><td colspan=5>&nbsp;</td></tr>';
	// 							$fig_bottom_border=array(array('colspan'=>5, 'class'=>'', 'tdtext'=>'&nbsp;'));
	// 						}
	// 					}
						
	// 					if($fig_grp_name!=''){
	// 						//echo '<tr><td colspan="5">'.$fig_grp_name.'</td></tr>';
	// 						$pnl_sect_trdata['sect_trlist'][] = array(array('colspan'=>5, 'class'=>'', 'tdtext'=>$fig_grp_name));
	// 						$fig_grp_name='';
	// 					}
	// 				}
					
	// 				$col_values[$fig_value_col][1]=$grp_summary_format;//' class="'.$grp_summary_format.'"';//' class="text-right sect_col"';
					
	// 			}
				
	// 			//echo '<tr><td colspan="2">'.$fig_grp_name.$fig_disp_name.'</td><td'.$col_values['l'][1].'>'.$col_values['l'][0].'</td><td'.$col_values['m'][1].'>'.$col_values['m'][0].'</td><td'.$col_values['r'][1].'>'.$col_values['r'][0].'</td></tr>';
	// 			$pnl_sect_trdata['sect_trlist'][] = array(
	// 													array('colspan'=>2, 'class'=>'', 'tdtext'=>$fig_grp_name.$fig_disp_name),
	// 													array('colspan'=>'', 'class'=>$col_values['l'][1], 'tdtext'=>$col_values['l'][0]),
	// 													array('colspan'=>'', 'class'=>$col_values['m'][1], 'tdtext'=>$col_values['m'][0]),
	// 													array('colspan'=>'', 'class'=>$col_values['r'][1], 'tdtext'=>$col_values['r'][0])
	// 												);
				
	// 			//echo $fig_bottom_border;
	// 			if(!empty($fig_bottom_border)){
	// 				$pnl_sect_trdata['sect_trlist'][] = $fig_bottom_border;
	// 			}
	// 		}
	// 	}else{
	// 		if($cnt_rev){
	// 			$sect_total = -1;
	// 		}
	// 	}
		
	// 	$pnl_sect_trdata['sect_total'] = $sect_total;//return $sect_total;
		
	// 	return $pnl_sect_trdata;
	// }
	
	private function add_pnl_custom_sect($branch_id, $sect_code, $fig_value_col, $idtbl_master_fr, $idtbl_master_to, $fig_grp_sum=false, $cnt_rev=false){
		$section_data = $this->ReportModuleinfo->pnlCustomSectionDetails($branch_id, $sect_code, $idtbl_master_fr, $idtbl_master_to);
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
				$col_values[$fig_value_col][0]=number_format($section_row->fig_value, 2);
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
								$col_values['r'][0]=number_format($sect_total, 2);
								$col_values['r'][1]='text-right sect_col';//' class="text-right sect_col"';
								
							}
							
							
							$col_values[$col_grpsum[$fig_value_col]][0]=number_format($fig_total, 2);
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
	
	// private function refine_value($sect_value){
	// 	return (($sect_value==-1)?0:$sect_value);
	// }
	
    public function periodic_pnl(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['companylist']=get_company_list();
		$result['branch_period_list_filter']=get_all_company_branch_list();
		$result['all_account_periods']=get_all_account_periods();
		
		$result['report_gen_url'] = 'ReportModule/preview_pnl';
		$result['report_title'] = 'Profit & Loss';
		
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
		$result['companylist']=get_company_list();

		$result['report_gen_url'] = 'ReportModule/preview_balancesheet';
		$result['report_title'] = 'Balance Sheet';
		
		$this->load->view('periodic_reports_view', $result);
	}

	public function Getbranchaccocompany(){
        $recordID=$this->input->post('recordID');
        $result=get_company_branch_list($recordID);
	}

	public function Getperiodlist(){
        $company=$this->input->post('company');
        $branch=$this->input->post('branch');
        $result=get_account_periods($company, $branch);
	}
	
	public function ledger_folio(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['companylist']=get_company_list();
		$result['branch_period_list_filter']=get_all_company_branch_list();
		$result['all_account_periods']=get_all_account_periods();
		// $result['all_chart_of_acc']=$this->ReportModuleinfo->getChartOfAccounts();
		if(!empty($_GET['refno'])){
			$result['selectaccount']=$this->ReportModuleinfo->Getselectedaccount($_GET['refno']);
		}
		
		$result['report_gen_url'] = 'ReportModule/preview_ledgerfolio';
		$result['report_title'] = 'Ledger Folio';
		
		$this->load->view('periodic_reports_view', $result);
	}
	
	public function trial_balance(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['companylist']=get_company_list();
		$result['branch_period_list_filter']=get_all_company_branch_list();
		$result['all_account_periods']=get_all_account_periods();
		
		$result['report_gen_url'] = 'ReportModule/preview_trialbalance';
		$result['report_title'] = 'Trial Balance';
		
		$this->load->view('periodic_reports_view', $result);
	}
	
	public function period_trial_balance(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['companylist']=get_company_list();
		$result['branch_period_list_filter']=get_all_company_branch_list();
		$result['all_account_periods']=get_all_account_periods();
		
		$result['report_gen_url'] = 'ReportModule/preview_periodtrialbalance';
		$result['report_title'] = 'Period Trial Balance';
		
		$this->load->view('periodic_reports_view', $result);
	}
	
	// public function preview_pnl(){
	// 	$params['a'] = $this->input->post('company_id');
	// 	$params['b'] = $this->input->post('company_branch_id');
	// 	$params['c'] = $this->input->post('period_from');
	// 	$params['d'] = $this->input->post('period_upto');
		
	// 	$tot_sale_data = $this->add_pnl_sect('4', 'r');//'1'
	// 	$tot_sale = $tot_sale_data['sect_total'];
	// 	$sect_trlist[1] = $tot_sale_data['sect_trlist'];
		
	// 	$acc_period_from = $this->input->post('period_from');//master-id
	// 	$acc_period_upto = $this->input->post('period_upto');
		
	// 	$open_stock = $this->ReportModuleinfo->calc_stock(true, $acc_period_from);
		
	// 	$sale_cost_acc_data = $this->add_pnl_sect('1', 'm');//'2'
	// 	$sale_cost_acc = $sale_cost_acc_data['sect_total'];
	// 	$sect_trlist[2] = $sale_cost_acc_data['sect_trlist'];
	// 	$tot_sect = $open_stock+$this->refine_value($sale_cost_acc); 
		
	// 	$tot_stock = $this->ReportModuleinfo->calc_stock();
	// 	$cost_of_sale = $tot_sect-$tot_stock;
	// 	$gross_profit = $this->refine_value($tot_sale)-$cost_of_sale;
		
	// 	$tot_other_income_data = $this->add_pnl_sect('40', 'm', true, true);
	// 	$tot_other_income = $tot_other_income_data['sect_total'];
	// 	$sect_trlist[4] = $tot_other_income_data['sect_trlist'];
	// 	$tot_income = $gross_profit+$this->refine_value($tot_other_income);
		
	// 	$tot_expenses_data = $this->add_pnl_sect('2', 'l');//'3'
	// 	$tot_expenses = $tot_expenses_data['sect_total'];
	// 	$sect_trlist[3] = $tot_expenses_data['sect_trlist'];
	// 	$tot_transfer = $tot_income-$this->refine_value($tot_expenses);
		
	// 	$params['tot_sale'] = $tot_sale;
	// 	$params['open_stock'] = $open_stock;
	// 	$params['sale_cost_acc'] = $sale_cost_acc;
	// 	$params['tot_sect'] = $tot_sect;
	// 	$params['tot_stock'] = $tot_stock;
	// 	$params['cost_of_sale'] = $cost_of_sale;
	// 	$params['gross_profit'] = $gross_profit;
	// 	$params['tot_other_income'] = $tot_other_income;
	// 	$params['tot_income'] = $tot_income;
	// 	$params['tot_expenses'] = $tot_expenses;
	// 	$params['tot_transfer'] = $tot_transfer;
	// 	$params['pnl_trlist'] = $sect_trlist;
		
	// 	$params['rpt_from'] = $this->ReportModuleinfo->printDate($acc_period_from, 1);
	// 	$params['rpt_to'] = $this->ReportModuleinfo->printDate($acc_period_upto);
		
	// 	$this->load->view('report_preview_pnl', $params);
	// }
	
	public function preview_pnl_custom(){
		$params['a'] = $this->input->post('company_id');
		$params['b'] = $this->input->post('company_branch_id');
		$params['c'] = $this->input->post('period_from');
		$params['d'] = $this->input->post('period_upto');
		
		$param_branch_id = $this->input->post('company_branch_id');
		$acc_period_from = $this->input->post('period_from');//master-id
		$acc_period_upto = $this->input->post('period_upto');
		
		$tot_sale_data = $this->add_pnl_custom_sect($param_branch_id, '1', 'r', $acc_period_from, $acc_period_upto);//'1'
		$tot_sale = $tot_sale_data['sect_total'];
		$sect_trlist[1] = $tot_sale_data['sect_trlist'];
		
		$open_stock = $this->ReportModuleinfo->calc_custom_stock($param_branch_id, true, $acc_period_from);
		
		$sale_cost_acc_data = $this->add_pnl_custom_sect($param_branch_id, '2', 'm', $acc_period_from, $acc_period_upto);//'2'
		$sale_cost_acc = $sale_cost_acc_data['sect_total'];
		$sect_trlist[2] = $sale_cost_acc_data['sect_trlist'];
		$tot_sect = $open_stock+$this->refine_value($sale_cost_acc); 
		
		$tot_stock = $this->ReportModuleinfo->calc_custom_stock($param_branch_id, false, $acc_period_upto);
		$cost_of_sale = $tot_sect-$tot_stock;
		$gross_profit = $this->refine_value($tot_sale)-$cost_of_sale;
		
		$tot_other_income_data = $this->add_pnl_custom_sect($param_branch_id, '4', 'm', $acc_period_from, $acc_period_upto, true, true);
		$tot_other_income = $tot_other_income_data['sect_total'];
		$sect_trlist[4] = $tot_other_income_data['sect_trlist'];
		$tot_income = $gross_profit+$this->refine_value($tot_other_income);
		
		$tot_expenses_data = $this->add_pnl_custom_sect($param_branch_id, '3', 'l', $acc_period_from, $acc_period_upto);//'3'
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
		
		$params['rpt_from'] = $this->ReportModuleinfo->printDate($acc_period_from, 1);
		$params['rpt_to'] = $this->ReportModuleinfo->printDate($acc_period_upto);
		
		$this->load->view('report_preview_pnl_custom', $params);
	}
	
	// public function preview_balancesheet(){
	// 	$params['a'] = $this->input->post('company_id');
	// 	$params['b'] = $this->input->post('company_branch_id');
	// 	$params['c'] = $this->input->post('period_from');
	// 	$params['d'] = $this->input->post('period_upto');
	// 	$params['rpt_from'] = $this->ReportModuleinfo->printDate($this->input->post('period_from'), 1);
	// 	$params['rpt_to'] = $this->ReportModuleinfo->printDate($this->input->post('period_upto'));
	// 	$params['balanceinfo']=$this->ReportModuleinfo->Getbalancesheetinfo();

	// 	// Calculate net profit/loss for display
	// 	$company_id = $this->input->post('company_id');
	// 	$branch_id = $this->input->post('company_branch_id');
	// 	$period_from = $this->input->post('period_from');
	// 	$period_to = $this->input->post('period_upto');
		
	// 	$params['net_profit_loss'] = $this->ReportModuleinfo->calculateNetProfitLoss(
	// 		$company_id, $branch_id, $period_from, $period_to
	// 	);
		
	// 	$this->load->view('report_preview_balancesheet', $params);
	// }
	public function preview_balancesheet(){
		$companyid   = $this->input->post('company_id');
		$branchid    = $this->input->post('company_branch_id');
		$period_from = $this->input->post('period_from');  // master id
		$period_upto = $this->input->post('period_upto');  // master id

		// ── Get from/to master period details ─────────────────────────────────
		$from_master = $this->db->get_where('tbl_master', [
			'idtbl_master' => $period_from,
			'status'       => 1
		])->row();

		$to_master = $this->db->get_where('tbl_master', [
			'idtbl_master' => $period_upto,
			'status'       => 1
		])->row();

		// ── Build period range ─────────────────────────────────────────────────
		if(!empty($from_master) && !empty($to_master)){
			$range = $this->ReportModuleinfo->getPeriodRangeMasterIds(
				$from_master->tbl_finacial_month_idtbl_finacial_month,
				$from_master->tbl_finacial_year_idtbl_finacial_year,
				$to_master->tbl_finacial_month_idtbl_finacial_month,
				$to_master->tbl_finacial_year_idtbl_finacial_year,
				$companyid,
				$branchid
			);

			if(!empty($range)){
				$master_ids      = $range['master_ids'];
				$open_bal_master = $range['from_master_id'];
				$is_cross_year   = $range['is_cross_year'];
			} else {
				$master_ids      = [$period_from];
				$open_bal_master = $period_from;
				$is_cross_year   = false;
			}
		} else {
			$master_ids      = [$period_from];
			$open_bal_master = $period_from;
			$is_cross_year   = false;
		}

		// ── Balance sheet data ────────────────────────────────────────────────
		$balanceinfo = $this->ReportModuleinfo->Getbalancesheetinfo(
			$companyid, $branchid,
			$master_ids,
			$open_bal_master
		);

		// ── Net Profit/Loss ───────────────────────────────────────────────────
		$net_profit_loss = $this->ReportModuleinfo->calculateNetProfitLoss(
			$companyid, $branchid,
			$master_ids,
			$open_bal_master
		);

		$params = [
			'a'              => $companyid,
			'b'              => $branchid,
			'c'              => $period_from,
			'd'              => $period_upto,
			'rpt_from'       => $this->ReportModuleinfo->printDate($period_from, 1),
			'rpt_to'         => $this->ReportModuleinfo->printDate($period_upto),
			'balanceinfo'    => $balanceinfo,
			'net_profit_loss'=> $net_profit_loss,
			'is_cross_year'  => $is_cross_year,
			'period_count'   => count($master_ids),
			'from_id'        => $period_from,
			'to_id'          => $period_upto
		];

		$this->load->view('report_preview_balancesheet', $params);
	}

	// public function preview_ledgerfolio(){
	// 	$companyid      = $this->input->post('company_id');
	// 	$branchid       = $this->input->post('company_branch_id');
	// 	$period_from    = $this->input->post('period_from');  // master id
	// 	$period_upto    = $this->input->post('period_upto');  // master id
	// 	$chartAccId     = $this->input->post('chart_acc_id');
	// 	$chartAccType   = $this->input->post('chart_acc_type');

	// 	// ── Get from/to master period details ─────────────────────────────────
	// 	$from_master = $this->db->get_where('tbl_master', [
	// 		'idtbl_master' => $period_from,
	// 		'status'       => 1
	// 	])->row();

	// 	$to_master = $this->db->get_where('tbl_master', [
	// 		'idtbl_master' => $period_upto,
	// 		'status'       => 1
	// 	])->row();
		
	// 	// ── Build period range ────────────────────────────────────────────────
	// 	if(!empty($from_master) && !empty($to_master)){
	// 		$range = $this->ReportModuleinfo->getPeriodRangeMasterIds(
	// 			$from_master->tbl_finacial_month_idtbl_finacial_month,
	// 			$from_master->tbl_finacial_year_idtbl_finacial_year,
	// 			$to_master->tbl_finacial_month_idtbl_finacial_month,
	// 			$to_master->tbl_finacial_year_idtbl_finacial_year,
	// 			$companyid,
	// 			$branchid
	// 		);
			
	// 		if(!empty($range)){
	// 			$master_ids      = $range['master_ids'];
	// 			$open_bal_master = $range['from_master_id'];
	// 			$is_cross_year   = $range['is_cross_year'];
	// 		} else {
	// 			$master_ids      = [$period_from];
	// 			$open_bal_master = $period_from;
	// 			$is_cross_year   = false;
	// 		}
	// 	} else {
	// 		$master_ids      = [$period_from];
	// 		$open_bal_master = $period_from;
	// 		$is_cross_year   = false;
	// 	}
		
	// 	// ── Opening stock/balance for this account ────────────────────────────
	// 	$open_stock = $this->ReportModuleinfo->ledgerFolioOpenStockValue(
	// 		$branchid,
	// 		$chartAccId,
	// 		$open_bal_master   // first period opening balance only
	// 	);

	// 	// ── Ledger folio transactions — ALL periods ───────────────────────────
	// 	$ledger_folio_data = $this->ReportModuleinfo->ledgerFolioDetails(
	// 		$branchid,
	// 		$chartAccId,
	// 		$chartAccType,
	// 		$master_ids,       // array — single or multi
	// 		$open_bal_master
	// 	);
		
	// 	$params = [
	// 		'a'                      => $companyid,
	// 		'b'                      => $branchid,
	// 		'c'                      => $period_from,
	// 		'd'                      => $period_upto,
	// 		'account_code'           => $open_stock->accountno ?? '',
	// 		'report_duration'        => $this->ReportModuleinfo->printDate($period_from, 1)
	// 									. ' / '
	// 									. $this->ReportModuleinfo->printDate($period_upto),
	// 		'rpt_from'               => $this->ReportModuleinfo->printDate($period_from, 1),
	// 		'rpt_to'                 => $this->ReportModuleinfo->printDate($period_upto),
	// 		'open_stock'             => $open_stock->ac_open_balance ?? 0,
	// 		'open_stock_crdr'        => $open_stock->creditdebit ?? 'D',
	// 		'ledger_folio_data'      => $ledger_folio_data,
	// 		'total_rows_ledger_folio'=> count($ledger_folio_data),
	// 		'is_cross_year'          => $is_cross_year,
	// 		'period_count'           => count($master_ids)
	// 	];

	// 	$this->load->view('report_preview_ledgerfolio', $params);
	// }

	//Join with PNL & Balance sheet on 17/08/2026
	// public function preview_ledgerfolio(){
	// 	$companyid      = $this->input->post('company_id');
	// 	$branchid       = $this->input->post('company_branch_id');
	// 	$period_from    = $this->input->post('period_from');
	// 	$period_upto    = $this->input->post('period_upto');
	// 	$selectedAccId  = $this->input->post('chart_acc_id');   // chart ID or detail ID (acctype අනුව)
	// 	$chartAccType   = $this->input->post('chart_acc_type'); // '1' = chart, '2' = detail
	// 	$ledgerfiltertype   = $this->input->post('ledgerfiltertype'); // '1' = Normal Report, '2' = Link Report

	// 	// ★ NEW: acctype = 2 (detail account) නම්, parent chart account ID එක resolve කරගන්නවා
	// 	$chartAccId  = $selectedAccId;
	// 	$detailAccId = null;

	// 	if($ledgerfiltertype == 1 && $chartAccType == 1){
	// 		$this->db->select('`idtbl_account_detail`, `accountno`, `accountname`');
	// 		$this->db->from('tbl_account_detail');
	// 		$this->db->where('status', 1);
	// 		$this->db->where('tbl_account_idtbl_account', $selectedAccId);
	// 		$responddetailaccounts = $this->db->get();
	// 	}

	// 	if($chartAccType == 2){
	// 		$detailAccId = $selectedAccId;

	// 		$parent = $this->db->select('tbl_account_idtbl_account, accountno, accountname')
	// 							->get_where('tbl_account_detail', [
	// 								'idtbl_account_detail' => $detailAccId
	// 							])
	// 							->row();

	// 		$chartAccId = $parent->tbl_account_idtbl_account ?? null;
	// 		$detailaccountinfo = ($parent->accountname.' - '.$parent->accountno) ?? '';
	// 	}		

	// 	// ── Get from/to master period details ─────────────────────────────────
	// 	$from_master = $this->db->get_where('tbl_master', [
	// 		'idtbl_master' => $period_from,
	// 		'status'       => 1
	// 	])->row();

	// 	$to_master = $this->db->get_where('tbl_master', [
	// 		'idtbl_master' => $period_upto,
	// 		'status'       => 1
	// 	])->row();

	// 	// ── Build period range ────────────────────────────────────────────────
	// 	if(!empty($from_master) && !empty($to_master)){
	// 		$range = $this->ReportModuleinfo->getPeriodRangeMasterIds(
	// 			$from_master->tbl_finacial_month_idtbl_finacial_month,
	// 			$from_master->tbl_finacial_year_idtbl_finacial_year,
	// 			$to_master->tbl_finacial_month_idtbl_finacial_month,
	// 			$to_master->tbl_finacial_year_idtbl_finacial_year,
	// 			$companyid,
	// 			$branchid
	// 		);

	// 		if(!empty($range)){
	// 			$master_ids      = $range['master_ids'];
	// 			$open_bal_master = $range['from_master_id'];
	// 			$is_cross_year   = $range['is_cross_year'];
	// 		} else {
	// 			$master_ids      = [$period_from];
	// 			$open_bal_master = $period_from;
	// 			$is_cross_year   = false;
	// 		}
	// 	} else {
	// 		$master_ids      = [$period_from];
	// 		$open_bal_master = $period_from;
	// 		$is_cross_year   = false;
	// 	}

	// 	// ── Opening stock/balance — always chart-level ──────────────────────────

	// 	$period_start_date = $this->ReportModuleinfo->printDate($period_from, 1);

	// 	$open_stock = $this->ReportModuleinfo->ledgerFolioOpenStockValue(
	// 		$branchid,
	// 		$chartAccId,        // ★ resolved chart account ID (detail select වුනත් chart ID එකම)
	// 		$open_bal_master,
    // 		$detailAccId,
	// 		$period_start_date 
	// 	);

	// 	// ── Ledger folio transactions ──────────────────────────────────────────
	// 	$ledger_folio_data = $this->ReportModuleinfo->ledgerFolioDetails(
	// 		$branchid,
	// 		$chartAccId,        // ★ resolved chart account ID
	// 		$chartAccType,
	// 		$master_ids,
	// 		$open_bal_master,
	// 		$detailAccId        // ★ NEW — detail filter (type=1 නම් null)
	// 	);

	// 	$params = [
	// 		'a'                      => $companyid,
	// 		'b'                      => $branchid,
	// 		'c'                      => $period_from,
	// 		'd'                      => $period_upto,
	// 		'account_code'           => $detailaccountinfo ?? $open_stock->accountno ?? '',
	// 		'report_duration'        => $this->ReportModuleinfo->printDate($period_from, 1)
	// 									. ' / '
	// 									. $this->ReportModuleinfo->printDate($period_upto),
	// 		'rpt_from'               => $this->ReportModuleinfo->printDate($period_from, 1),
	// 		'rpt_to'                 => $this->ReportModuleinfo->printDate($period_upto),
	// 		'open_stock'             => $open_stock->ac_open_balance ?? 0,
	// 		'open_stock_crdr'        => $open_stock->creditdebit ?? 'D',
	// 		'ledger_folio_data'      => $ledger_folio_data,
	// 		'total_rows_ledger_folio'=> count($ledger_folio_data),
	// 		'is_cross_year'          => $is_cross_year,
	// 		'period_count'           => count($master_ids)
	// 	];

	// 	$this->load->view('report_preview_ledgerfolio', $params);
	// }
	public function preview_ledgerfolio(){
		$companyid      = $this->input->post('company_id');
		$branchid       = $this->input->post('company_branch_id');
		$period_from    = $this->input->post('period_from');
		$period_upto    = $this->input->post('period_upto');
		$selectedAccId  = $this->input->post('chart_acc_id');   // chart ID or detail ID (acctype අනුව)
		$chartAccType   = $this->input->post('chart_acc_type'); // '1' = chart, '2' = detail
		$ledgerfiltertype   = $this->input->post('ledgerfiltertype'); // '1' = Normal Report, '2' = Link Report

		// acctype = 2 (detail account) නම්, parent chart account ID එක resolve කරගන්නවා
		$chartAccId        = $selectedAccId;
		$detailAccId       = null;
		$detailaccountinfo = '';

		// ★ Normal Report + chart account select කළොත් — ඒ chart එකට අයිති detail accounts ලැයිස්තුව ගන්නවා
		$responddetailaccounts = [];
		if($ledgerfiltertype == 1 && $chartAccType == 1){
			$responddetailaccounts = $this->db
				->select('idtbl_account_detail, accountno, accountname')
				->from('tbl_account_detail')
				->where('status', 1)
				->where('tbl_account_idtbl_account', $selectedAccId)
				->get()
				->result();   // ★ FIX: ->result() එකතු කළා (ඉස්සර object row set එකක් විතරයි return වුනේ)
		}

		if($chartAccType == 2){
			$detailAccId = $selectedAccId;

			$parent = $this->db->select('tbl_account_idtbl_account, accountno, accountname')
								->get_where('tbl_account_detail', [
									'idtbl_account_detail' => $detailAccId
								])
								->row();

			$chartAccId        = $parent->tbl_account_idtbl_account ?? null;
			$detailaccountinfo = ($parent->accountname.' - '.$parent->accountno) ?? '';
		}

		// ── Get from/to master period details ─────────────────────────────────
		$from_master = $this->db->get_where('tbl_master', [
			'idtbl_master' => $period_from,
			'status'       => 1
		])->row();

		$to_master = $this->db->get_where('tbl_master', [
			'idtbl_master' => $period_upto,
			'status'       => 1
		])->row();

		// ── Build period range ────────────────────────────────────────────────
		if(!empty($from_master) && !empty($to_master)){
			$range = $this->ReportModuleinfo->getPeriodRangeMasterIds(
				$from_master->tbl_finacial_month_idtbl_finacial_month,
				$from_master->tbl_finacial_year_idtbl_finacial_year,
				$to_master->tbl_finacial_month_idtbl_finacial_month,
				$to_master->tbl_finacial_year_idtbl_finacial_year,
				$companyid,
				$branchid
			);

			if(!empty($range)){
				$master_ids      = $range['master_ids'];
				$open_bal_master = $range['from_master_id'];
				$is_cross_year   = $range['is_cross_year'];
			} else {
				$master_ids      = [$period_from];
				$open_bal_master = $period_from;
				$is_cross_year   = false;
			}
		} else {
			$master_ids      = [$period_from];
			$open_bal_master = $period_from;
			$is_cross_year   = false;
		}

		$period_start_date = $this->ReportModuleinfo->printDate($period_from, 1);

		// ★ NEW: Normal Report + chart account + detail accounts තිබ්බොත් —
		// detail accounts ලැයිස්තුව loop කරලා, එකින් එකට open_stock + ledger transactions වෙන වෙනම ගන්නවා
		$detail_accounts_data = [];

		if($ledgerfiltertype == 1 && $chartAccType == 1 && !empty($responddetailaccounts)){

			foreach($responddetailaccounts as $detail){

				$d_open_stock = $this->ReportModuleinfo->ledgerFolioOpenStockValue(
					$branchid,
					$chartAccId,
					$open_bal_master,
					$detail->idtbl_account_detail,
					$period_start_date
				);

				$d_ledger_folio_data = $this->ReportModuleinfo->ledgerFolioDetails(
					$branchid,
					$chartAccId,
					2,                                   // ★ union branch (AR/AP/JE/RE/PS) force කරන්න
					$master_ids,
					$open_bal_master,
					$detail->idtbl_account_detail
				);

				$detail_accounts_data[] = [
					'idtbl_account_detail' => $detail->idtbl_account_detail,
					'accountno'            => $detail->accountno,
					'accountname'          => $detail->accountname,
					'open_stock'           => $d_open_stock->ac_open_balance ?? 0,
					'open_stock_crdr'      => $d_open_stock->creditdebit ?? 'D',
					'ledger_folio_data'    => $d_ledger_folio_data,
					'total_rows'           => count($d_ledger_folio_data)
				];
			}

			// chart-level opening balance (report header එකේ පෙන්නන්න) — detail_acc_id null = chart level
			$open_stock = $this->ReportModuleinfo->ledgerFolioOpenStockValue(
				$branchid,
				$chartAccId,
				$open_bal_master,
				null,
				$period_start_date
			);

			// Normal Report මේකේදී flat list එක පාවිච්චි නොකර, detail_accounts_data breakdown එකෙන් view එකේ පෙන්නනවා
			$ledger_folio_data = [];
		}
		else{
			// ── existing single-account flow (Link Report, හෝ detail account එකක් directly select කළොත්) ──
			$open_stock = $this->ReportModuleinfo->ledgerFolioOpenStockValue(
				$branchid,
				$chartAccId,
				$open_bal_master,
				$detailAccId,
				$period_start_date
			);

			$ledger_folio_data = $this->ReportModuleinfo->ledgerFolioDetails(
				$branchid,
				$chartAccId,
				$chartAccType,
				$master_ids,
				$open_bal_master,
				$detailAccId
			);
		}

		$params = [
			'a'                      => $companyid,
			'b'                      => $branchid,
			'c'                      => $period_from,
			'd'                      => $period_upto,
			'account_code'           => $detailaccountinfo ?? $open_stock->accountno ?? '',
			'report_duration'        => $this->ReportModuleinfo->printDate($period_from, 1)
										. ' / '
										. $this->ReportModuleinfo->printDate($period_upto),
			'rpt_from'               => $this->ReportModuleinfo->printDate($period_from, 1),
			'rpt_to'                 => $this->ReportModuleinfo->printDate($period_upto),
			'open_stock'             => $open_stock->ac_open_balance ?? 0,
			'open_stock_crdr'        => $open_stock->creditdebit ?? 'D',
			'ledger_folio_data'      => $ledger_folio_data,
			'total_rows_ledger_folio'=> count($ledger_folio_data),
			'detail_accounts_data'   => $detail_accounts_data,   // ★ NEW — Normal Report: detail-wise breakdown
			'is_cross_year'          => $is_cross_year,
			'period_count'           => count($master_ids)
		];

		$this->load->view('report_preview_ledgerfolio', $params);
	}
	
	// public function preview_trialbalance(){
	// 	$params['a'] = $this->input->post('company_id');
	// 	$params['b'] = $this->input->post('company_branch_id');
	// 	$params['c'] = $this->input->post('period_from');
	// 	$params['d'] = $this->input->post('period_upto');
		
	// 	$acc_period_from = $this->input->post('period_from');//master-id
	// 	$acc_period_upto = $this->input->post('period_upto');
		
	// 	$open_stock = $this->ReportModuleinfo->calc_stock(true, $acc_period_from);
	// 	$rpt_from_str = $this->ReportModuleinfo->printDate($acc_period_from, 1);
	// 	$stock_opening_date = new DateTime($rpt_from_str);
	// 	$stock_closing_date = $stock_opening_date->modify("-1 days")->format('Y-m-d');//display previous date as closing-date
		
	// 	$reportPeriod = $this->input->post('period_from');
	// 	$trial_balance_data = $this->ReportModuleinfo->trialBalanceDetails($this->input->post('company_branch_id'), $reportPeriod);
		
	// 	$params['open_stock'] = $open_stock;
	// 	$params['trial_balance_data'] = $trial_balance_data;
		
	// 	$params['rpt_from'] = $rpt_from_str;
	// 	$params['rpt_to'] = $this->ReportModuleinfo->printDate($acc_period_upto);
	// 	$params['stock_date'] = $stock_closing_date;
		
	// 	$this->load->view('report_preview_trialbalance', $params);
	// }

	public function preview_trialbalance(){
		$companyid    = $this->input->post('company_id');
		$branchid     = $this->input->post('company_branch_id');
		$period_from  = $this->input->post('period_from');  // master id (from)
		$period_upto  = $this->input->post('period_upto');  // master id (to)

		// ── Stock calculation (uses from period — unchanged) ──────────────────
		$open_stock        = $this->ReportModuleinfo->calc_stock(true, $period_from);
		$rpt_from_str      = $this->ReportModuleinfo->printDate($period_from, 1);
		$stock_opening_date = new DateTime($rpt_from_str);
		$stock_closing_date = $stock_opening_date->modify('-1 days')->format('Y-m-d');

		// ── Get from/to period year & month IDs from master IDs ───────────────
		$from_master = $this->db->get_where('tbl_master', [
			'idtbl_master' => $period_from,
			'status'       => 1
		])->row();

		$to_master = $this->db->get_where('tbl_master', [
			'idtbl_master' => $period_upto,
			'status'       => 1
		])->row();

		// ── Build period range ─────────────────────────────────────────────────
		if(!empty($from_master) && !empty($to_master)){

			$range = $this->ReportModuleinfo->getPeriodRangeMasterIds(
				$from_master->tbl_finacial_month_idtbl_finacial_month,
				$from_master->tbl_finacial_year_idtbl_finacial_year,
				$to_master->tbl_finacial_month_idtbl_finacial_month,
				$to_master->tbl_finacial_year_idtbl_finacial_year,
				$companyid,
				$branchid
			);

			if(!empty($range)){
				$master_ids      = $range['master_ids'];
				$open_bal_master = $range['from_master_id'];  // first period opening bal
				$is_cross_year   = $range['is_cross_year'];
			} else {
				// Fallback — single period
				$master_ids      = [$period_from];
				$open_bal_master = $period_from;
				$is_cross_year   = false;
			}

		} else {
			// Fallback — single period (from only)
			$master_ids      = [$period_from];
			$open_bal_master = $period_from;
			$is_cross_year   = false;
		}

		// ── Trial balance — multi period ───────────────────────────────────────
		$trial_balance_data = $this->ReportModuleinfo->trialBalanceDetails(
			$branchid,
			$master_ids,      // array — single or multi
			$open_bal_master  // first period opening balance
		);

		// ── Pass to view ───────────────────────────────────────────────────────
		$params = [
			'a'                  => $companyid,
			'b'                  => $branchid,
			'c'                  => $period_from,
			'd'                  => $period_upto,
			'open_stock'         => $open_stock,
			'trial_balance_data' => $trial_balance_data,
			'rpt_from'           => $rpt_from_str,
			'rpt_to'             => $this->ReportModuleinfo->printDate($period_upto),
			'stock_date'         => $stock_closing_date,
			'is_cross_year'      => $is_cross_year,        // view එකේ use කරන්න පුළුවන්
			'period_count'       => count($master_ids),    // view එකේ show කරන්න
			'period_range'       => $range ?? null         // full range detail
		];

		$this->load->view('report_preview_trialbalance', $params);
	}
	
	public function preview_periodtrialbalance(){
		$params['a'] = $this->input->post('company_id');
		$params['b'] = $this->input->post('company_branch_id');
		$params['c'] = $this->input->post('period_from');
		$params['d'] = $this->input->post('period_upto');
		
		$this->load->view('report_preview_periodtrialbalance', $params);
	}
    
	public function DebtorReport(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['companylist']=get_company_list();

		$result['report_gen_url'] = 'ReportModule/DebtorReportPreview';
		$result['report_title'] = 'Debtor Report';
		
		$this->load->view('periodic_reports_view', $result);
	}
	public function Getcustomerlist(){
        $searchTerm=$this->input->post('searchTerm');
        $companyid=$this->input->post('companyid');
        $branchid=$this->input->post('branchid');
        $result=get_customer_search_list($searchTerm, $companyid, $branchid);
	}
	public function DebtorReportPreview(){
		$params['companyid'] = $this->input->post('company_id');
		$params['branchid'] = $this->input->post('company_branch_id');
		$params['periodfrom'] = $this->input->post('period_from');
		$params['periodto'] = $this->input->post('period_upto');
		$params['debtorid'] = $this->input->post('customer');
		
		$acc_period_from = $this->input->post('period_from');//master-id
		$acc_period_upto = $this->input->post('period_upto');

		$rpt_from_str = $this->ReportModuleinfo->printDate($acc_period_from, 1);
		$rpt_to_str = $this->ReportModuleinfo->printDate($acc_period_upto);
		$params['report_duration'] = $rpt_from_str.' / '.$rpt_to_str;

		$this->db->select('idtbl_customer, customer');
		$this->db->from('tbl_customer');
		$this->db->where('status', 1);
		$this->db->where('idtbl_customer', $this->input->post('customer'));
		$respond=$this->db->get();

		$params['debtorname'] = $respond->row(0)->customer;
		$params['reportdata'] = $this->ReportModuleinfo->DebtorReportData($rpt_from_str, $rpt_to_str, $this->input->post('customer'));
		$params['reportopenbalance'] = $this->ReportModuleinfo->DebtorOpenBalance($rpt_from_str, $this->input->post('customer'));
		// $params['reportdata'] = $this->ReportModuleinfo->DebtorReportData('2023-01-01', '2024-12-31', $this->input->post('customer'));

		$this->load->view('report_preview_debtor', $params);
	}

	public function CreditorReport(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['companylist']=get_company_list();

		$result['report_gen_url'] = 'ReportModule/CreditorReportPreview';
		$result['report_title'] = 'Creditor Report';
		
		$this->load->view('periodic_reports_view', $result);
	}
	public function Getsupplierlist(){
        $searchTerm=$this->input->post('searchTerm');
        $companyid=$this->input->post('companyid');
        $branchid=$this->input->post('branchid');
        $result=get_supplier_search_list($searchTerm, $companyid, $branchid);
	}
	public function CreditorReportPreview(){
		$params['companyid'] = $this->input->post('company_id');
		$params['branchid'] = $this->input->post('company_branch_id');
		$params['periodfrom'] = $this->input->post('period_from');
		$params['periodto'] = $this->input->post('period_upto');
		$params['creditorid'] = $this->input->post('supplier');
		
		$acc_period_from = $this->input->post('period_from');//master-id
		$acc_period_upto = $this->input->post('period_upto');

		$rpt_from_str = $this->ReportModuleinfo->printDate($acc_period_from, 1);
		$rpt_to_str = $this->ReportModuleinfo->printDate($acc_period_upto);
		$params['report_duration'] = $rpt_from_str.' / '.$rpt_to_str;

		$this->db->select('idtbl_supplier, suppliername');
		$this->db->from('tbl_supplier');
		$this->db->where('status', 1);
		$this->db->where('idtbl_supplier', $this->input->post('supplier'));
		$respond=$this->db->get();

		$params['creditorname'] = $respond->row(0)->suppliername;
		$params['reportdata'] = $this->ReportModuleinfo->CreditorReportData($rpt_from_str, $rpt_to_str, $this->input->post('supplier'));
		// $params['reportdata'] = $this->ReportModuleinfo->CreditorReportData('2023-01-01', '2024-12-31', $this->input->post('supplier'));
		$params['reportopenbalance'] = $this->ReportModuleinfo->CreditorrOpenBalance($rpt_from_str, $this->input->post('supplier'));

		$this->load->view('report_preview_creditor', $params);
	}

	//============================================================================================================================

	public function preview_pnl() {
        // Get POST parameters
        $company_id = $_SESSION['companyid'];
        $branch_id =  $_SESSION['branchid'];
        $from_master_id = $this->input->post('period_from');
        $to_master_id = $this->input->post('period_upto');
        
        // Get period range details
        $period_range = $this->ReportModuleinfo->getPeriodRange($from_master_id, $to_master_id);
        $from_date = $period_range['from_date'];
        $to_date = $period_range['to_date'];
        
        // Get individual master details for display
        $from_master = $this->ReportModuleinfo->getMasterDetails($from_master_id);
        $to_master = $this->ReportModuleinfo->getMasterDetails($to_master_id);
        
        // Get PNL sections data for the master ID range
        $sect_trlist = array();
        
        // 1. Sales Revenue (Category 4)
        $sales_data = $this->add_pnl_sect('4', 'r', $from_master_id, $to_master_id, $company_id, $branch_id);
        $tot_sale = $sales_data['sect_total'];
        $sect_trlist[1] = $sales_data['sect_trlist'];
        
        // 2. Calculate stock values - use from period start date for opening stock
        $open_stock = $this->ReportModuleinfo->calc_stock(true, $from_date);
        
        // 3. Cost of Sales (Category 1)
        $cost_data = $this->add_pnl_sect('1', 'm', $from_master_id, $to_master_id, $company_id, $branch_id);
        $sale_cost_acc = $cost_data['sect_total'];
        $sect_trlist[2] = $cost_data['sect_trlist'];
        
        $tot_sect = $open_stock + $this->refine_value($sale_cost_acc);
        
        // 4. Closing stock - use to period end date
        $tot_stock = $this->ReportModuleinfo->calc_stock(false, $to_date);
        $cost_of_sale = $tot_sect - $tot_stock;
        
        // 5. Gross Profit
        $gross_profit = $this->refine_value($tot_sale) - $cost_of_sale;
        
        // 6. Other Income (Category 40)
        $other_income_data = $this->add_pnl_sect('40', 'm', $from_master_id, $to_master_id, $company_id, $branch_id, true, true);
        $tot_other_income = $other_income_data['sect_total'];
        $sect_trlist[4] = $other_income_data['sect_trlist'];
        $tot_income = $gross_profit + $this->refine_value($tot_other_income);
        
        // 7. Expenses (Category 2)
        $expenses_data = $this->add_pnl_sect('2', 'l', $from_master_id, $to_master_id, $company_id, $branch_id);
        $tot_expenses = $expenses_data['sect_total'];
        $sect_trlist[3] = $expenses_data['sect_trlist'];
        $tot_transfer = $tot_income - $this->refine_value($tot_expenses);
        
        // Prepare data for view
        $data = array(
            'tot_sale' => $tot_sale,
            'open_stock' => $open_stock,
            'sale_cost_acc' => $sale_cost_acc,
            'tot_sect' => $tot_sect,
            'tot_stock' => $tot_stock,
            'cost_of_sale' => $cost_of_sale,
            'gross_profit' => $gross_profit,
            'tot_other_income' => $tot_other_income,
            'tot_income' => $tot_income,
            'tot_expenses' => $tot_expenses,
            'tot_transfer' => $tot_transfer,
            'pnl_trlist' => $sect_trlist,
            'rpt_from' => $this->formatPeriodDisplay($from_master),
            'rpt_to' => $this->formatPeriodDisplay($to_master),
			'from_id' => $from_master_id,
			'to_id' => $to_master_id
        );
        
        $this->load->view('report_preview_pnl', $data);
    }
    
    private function add_pnl_sect($category_id, $align_type, $from_master_id, $to_master_id, $company_id, $branch_id, $show_negative = false, $is_income = false) {
        $section_data = $this->ReportModuleinfo->pnlSectionDetails($category_id, $from_master_id, $to_master_id, $company_id, $branch_id);
        
        $sect_total = 0;
        $sect_trlist = array();
        
        foreach ($section_data as $row) {
            $fig_value = $row['fig_value'];
            $sect_total += $fig_value;
            
            // Prepare table row data
            $tr_data = array(
                array(
                    'class' => '',
                    'colspan' => 3,
                    'tdtext' => $row['fig_name']
                ),
                array(
                    'class' => 'text-right ' . ($align_type == 'r' ? 'sect_col' : ''),
                    'colspan' => 1,
                    'tdtext' => number_format($fig_value, 2)
                ),
                array(
                    'class' => '',
                    'colspan' => 1,
                    'tdtext' => '&nbsp;'
                )
            );
            
            $sect_trlist[] = $tr_data;
        }
        
        // Add total row for the section
        $total_tr = array(
            array(
                'class' => 'font-weight-bold',
                'colspan' => 3,
                'tdtext' => 'Total'
            ),
            array(
                'class' => 'text-right font-weight-bold sect_col',
                'colspan' => 1,
                'tdtext' => number_format($sect_total, 2)
            ),
            array(
                'class' => '',
                'colspan' => 1,
                'tdtext' => '&nbsp;'
            )
        );
        
        $sect_trlist[] = $total_tr;
        
        return array(
            'sect_total' => $sect_total,
            'sect_trlist' => $sect_trlist
        );
    }
    
    private function refine_value($value) {
        return ($value == -1) ? 0 : $value;
    }
    
    private function formatPeriodDisplay($master_data) {
        return $master_data['monthname'] . ' ' . $master_data['year'];
    }

	public function Getaccountlist(){
        $searchTerm=$this->input->post('searchTerm');
        $companyid=$this->input->post('companyid');
        $branchid=$this->input->post('branchid');

        // $result=get_child_account_list($companyid, $branchid);
        $result=get_accounts_list($searchTerm, $companyid, $branchid);
	}

	public function cash_flow(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['companylist']=get_company_list();
		$result['branch_period_list_filter']=get_all_company_branch_list();
		$result['all_account_periods']=get_all_account_periods();
		// $result['all_chart_of_acc']=$this->ReportModuleinfo->getChartOfAccounts();
		
		$result['report_gen_url'] = 'ReportModule/preview_cashflow';
		$result['report_title'] = 'Cash Flow Report';
		
		$this->load->view('periodic_reports_view', $result);
	}

	// public function preview_cashflow(){
	// 	$companyid    = $this->input->post('company_id');
	// 	$branchid     = $this->input->post('company_branch_id');
	// 	$period_from  = $this->input->post('period_from');  // master id (from)
	// 	$period_upto  = $this->input->post('period_upto');  // master id (to)

	// 	// ── Get from/to master period details ─────────────────────────────────
	// 	$from_master = $this->db->get_where('tbl_master', [
	// 		'idtbl_master' => $period_from,
	// 		'status'       => 1
	// 	])->row();

	// 	$to_master = $this->db->get_where('tbl_master', [
	// 		'idtbl_master' => $period_upto,
	// 		'status'       => 1
	// 	])->row();

	// 	// ── Build period range ────────────────────────────────────────────────
	// 	if(!empty($from_master) && !empty($to_master)){
	// 		$range = $this->ReportModuleinfo->getPeriodRangeMasterIds(
	// 			$from_master->tbl_finacial_month_idtbl_finacial_month,
	// 			$from_master->tbl_finacial_year_idtbl_finacial_year,
	// 			$to_master->tbl_finacial_month_idtbl_finacial_month,
	// 			$to_master->tbl_finacial_year_idtbl_finacial_year,
	// 			$companyid,
	// 			$branchid
	// 		);

	// 		if(!empty($range)){
	// 			$master_ids      = $range['master_ids'];
	// 			$open_bal_master = $range['from_master_id'];
	// 			$is_cross_year   = $range['is_cross_year'];
	// 		} else {
	// 			$master_ids      = [$period_from];
	// 			$open_bal_master = $period_from;
	// 			$is_cross_year   = false;
	// 		}
	// 	} else {
	// 		$master_ids      = [$period_from];
	// 		$open_bal_master = $period_from;
	// 		$is_cross_year   = false;
	// 	}

	// 	// ── Get Cash Flow Report ───────────────────────────────────────────────
	// 	$cash_flow = $this->ReportModuleinfo->getCashFlowReport(
	// 		$companyid,
	// 		$branchid,
	// 		$master_ids,
	// 		$open_bal_master
	// 	);

	// 	// ── Get Company & Branch Info ──────────────────────────────────────────
	// 	$company_info = $this->db->get_where('tbl_company', [
	// 		'idtbl_company' => $companyid,
	// 		'status'        => 1
	// 	])->row();

	// 	$branch_info = $this->db->get_where('tbl_company_branch', [
	// 		'idtbl_company_branch' => $branchid,
	// 		'status'               => 1
	// 	])->row();

	// 	// ── Build View Parameters ──────────────────────────────────────────────
	// 	$params = [

	// 		// ── Company & Branch Info ──────────────────────────────────────
	// 		'company_info'      => $company_info,
	// 		'branch_info'       => $branch_info,

	// 		// ── Period Info ────────────────────────────────────────────────
	// 		'report_duration'        => $this->ReportModuleinfo->printDate($period_from, 1)
	// 									. ' / '
	// 									. $this->ReportModuleinfo->printDate($period_upto),
	// 		'rpt_from'               => $this->ReportModuleinfo->printDate($period_from, 1),
	// 		'rpt_to'                 => $this->ReportModuleinfo->printDate($period_upto),
	// 		'from_master'       => $from_master,
	// 		'to_master'         => $to_master,
	// 		'master_ids'        => $master_ids,
	// 		'open_bal_master'   => $open_bal_master,
	// 		'is_cross_year'     => $is_cross_year,
	// 		'period_from'       => $period_from,
	// 		'period_upto'       => $period_upto,

	// 		// ── Operating Activities (IN=4, EX=2) ─────────────────────────
	// 		'income_items'      => $cash_flow->operating->income->items,
	// 		'income_total'      => $cash_flow->operating->income->total,
	// 		'expense_items'     => $cash_flow->operating->expense->items,
	// 		'expense_total'     => $cash_flow->operating->expense->total,
	// 		'net_operating'     => $cash_flow->operating->net_operating,

	// 		// ── Investing Activities (AS=1) ────────────────────────────────
	// 		'investing_items'   => $cash_flow->investing->items,
	// 		'net_investing'     => $cash_flow->investing->net_investing,

	// 		// ── Financing Activities (LI=3, EQ=5) ─────────────────────────
	// 		'liability_items'   => $cash_flow->financing->liabilities->items,
	// 		'equity_items'      => $cash_flow->financing->equity->items,
	// 		'net_financing'     => $cash_flow->financing->net_financing,

	// 		// ── Cash & Bank Balances ───────────────────────────────────────
	// 		'cash_bank_items'   => $cash_flow->cash_bank->items,
	// 		'opening_cash'      => $cash_flow->cash_bank->opening_cash,
	// 		'closing_cash'      => $cash_flow->cash_bank->closing_cash,

	// 		// ── Summary ────────────────────────────────────────────────────
	// 		'net_cash_change'   => $cash_flow->summary->net_cash_change,
	// 		'is_verified'       => $cash_flow->summary->verified,

	// 		// ── Report Meta ────────────────────────────────────────────────
	// 		'report_title'      => 'Cash Flow Statement',
	// 		'generated_date'    => date('Y-m-d H:i:s'),
	// 		'generated_by'      => $this->session->userdata('username'),
	// 	];

	// 	// ── Load View ──────────────────────────────────────────────────────────
	// 	$this->load->view('report_preview_cashflow', $params);
	// }

	/**
	 * DROP-IN REPLACEMENT for the existing preview_cashflow() controller method.
	 * Period-range logic is unchanged; only the $params passed to the view
	 * are updated to match the new indirect-method structure returned by
	 * getCashFlowReport().
	 */
	public function preview_cashflow(){
		$companyid    = $this->input->post('company_id');
		$branchid     = $this->input->post('company_branch_id');
		$period_from  = $this->input->post('period_from');  // master id (from)
		$period_upto  = $this->input->post('period_upto');  // master id (to)

		// ── Get from/to master period details ─────────────────────────────────
		$from_master = $this->db->get_where('tbl_master', [
			'idtbl_master' => $period_from,
			'status'       => 1
		])->row();

		$to_master = $this->db->get_where('tbl_master', [
			'idtbl_master' => $period_upto,
			'status'       => 1
		])->row();

		// ── Build period range ────────────────────────────────────────────────
		if(!empty($from_master) && !empty($to_master)){
			$range = $this->ReportModuleinfo->getPeriodRangeMasterIds(
				$from_master->tbl_finacial_month_idtbl_finacial_month,
				$from_master->tbl_finacial_year_idtbl_finacial_year,
				$to_master->tbl_finacial_month_idtbl_finacial_month,
				$to_master->tbl_finacial_year_idtbl_finacial_year,
				$companyid,
				$branchid
			);

			if(!empty($range)){
				$master_ids      = $range['master_ids'];
				$open_bal_master = $range['from_master_id'];
				$is_cross_year   = $range['is_cross_year'];
			} else {
				$master_ids      = [$period_from];
				$open_bal_master = $period_from;
				$is_cross_year   = false;
			}
		} else {
			$master_ids      = [$period_from];
			$open_bal_master = $period_from;
			$is_cross_year   = false;
		}

		// ── Get Cash Flow Report ───────────────────────────────────────────────
		$cash_flow = $this->ReportModuleinfo->getCashFlowReport(
			$companyid,
			$branchid,
			$master_ids,
			$open_bal_master
		);

		// ── Get Company & Branch Info ──────────────────────────────────────────
		$company_info = $this->db->get_where('tbl_company', [
			'idtbl_company' => $companyid,
			'status'        => 1
		])->row();

		$branch_info = $this->db->get_where('tbl_company_branch', [
			'idtbl_company_branch' => $branchid,
			'status'               => 1
		])->row();

		// ── Build View Parameters ──────────────────────────────────────────────
		$params = [

			// ── Company & Branch Info ──────────────────────────────────────
			'company_info'      => $company_info,
			'branch_info'       => $branch_info,

			// ── Period Info ────────────────────────────────────────────────
			'report_duration'   => $this->ReportModuleinfo->printDate($period_from, 1)
										. ' / '
										. $this->ReportModuleinfo->printDate($period_upto),
			'rpt_from'          => $this->ReportModuleinfo->printDate($period_from, 1),
			'rpt_to'            => $this->ReportModuleinfo->printDate($period_upto),
			'from_master'       => $from_master,
			'to_master'         => $to_master,
			'master_ids'        => $master_ids,
			'open_bal_master'   => $open_bal_master,
			'is_cross_year'     => $is_cross_year,
			'period_from'       => $period_from,
			'period_upto'       => $period_upto,

			// ── Operating Activities: Net Income + Adjustments ─────────────
			'income_items'      => $cash_flow->operating->income_items,
			'expense_items'     => $cash_flow->operating->expense_items,
			'net_income'        => $cash_flow->operating->net_income,
			'adjustment_items'  => $cash_flow->operating->adjustment_items,
			'total_adjustments' => $cash_flow->operating->total_adjustments,
			'net_operating'     => $cash_flow->operating->net_operating,

			// ── Investing Activities ────────────────────────────────────────
			'investing_items'   => $cash_flow->investing->items,
			'net_investing'     => $cash_flow->investing->net_investing,

			// ── Financing Activities ────────────────────────────────────────
			'financing_items'   => $cash_flow->financing->items,
			'net_financing'     => $cash_flow->financing->net_financing,

			// ── Cash & Bank Balances ─────────────────────────────────────────
			'cash_bank_items'   => $cash_flow->cash_bank->items,
			'opening_cash'      => $cash_flow->cash_bank->opening_cash,
			'closing_cash'      => $cash_flow->cash_bank->closing_cash,

			// ── Summary ──────────────────────────────────────────────────────
			'net_cash_change'   => $cash_flow->summary->net_cash_change,
			'is_verified'       => $cash_flow->summary->verified,

			// ── Report Meta ──────────────────────────────────────────────────
			'report_title'      => 'Cash Flow Statement',
			'generated_date'    => date('Y-m-d H:i:s'),
			'generated_by'      => $this->session->userdata('username'),
		];

		// ── Load View ──────────────────────────────────────────────────────────
		$this->load->view('report_preview_cashflow', $params);
	}

	public function audit_purchase(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['companylist']=get_company_list();
		$result['branch_period_list_filter']=get_all_company_branch_list();
		$result['all_account_periods']=get_all_account_periods();
		// $result['all_chart_of_acc']=$this->ReportModuleinfo->getChartOfAccounts();
		
		$result['report_gen_url'] = 'ReportModule/preview_purchase_audit';
		$result['report_title'] = 'Audit Purchase Report';
		
		$this->load->view('periodic_reports_view', $result);
	}

	public function preview_purchase_audit(){
		$companyid = $this->input->post('company_id');
		$branchid  = $this->input->post('company_branch_id');
		$period_from = $this->input->post('period_from');
		$period_upto = $this->input->post('period_upto');

		// ── Get from/to master period details ─────────────────────────────────
		$from_master = $this->db->get_where('tbl_master', [
			'idtbl_master' => $period_from,
			'status'       => 1
		])->row();

		$to_master = $this->db->get_where('tbl_master', [
			'idtbl_master' => $period_upto,
			'status'       => 1
		])->row();

		// ── Build period range ────────────────────────────────────────────────
		if(!empty($from_master) && !empty($to_master)){
			$range = $this->ReportModuleinfo->getPeriodRangeMasterIds(
				$from_master->tbl_finacial_month_idtbl_finacial_month,
				$from_master->tbl_finacial_year_idtbl_finacial_year,
				$to_master->tbl_finacial_month_idtbl_finacial_month,
				$to_master->tbl_finacial_year_idtbl_finacial_year,
				$companyid,
				$branchid
			);

			if(!empty($range)){
				$master_ids      = $range['master_ids'];
				$open_bal_master = $range['from_master_id'];
				$is_cross_year   = $range['is_cross_year'];
			} else {
				$master_ids      = [$period_from];
				$open_bal_master = $period_from;
				$is_cross_year   = false;
			}
		} else {
			$master_ids      = [$period_from];
			$open_bal_master = $period_from;
			$is_cross_year   = false;
		}

		// ── Get Company & Branch Info ──────────────────────────────────────────
		$company_info = $this->db->get_where('tbl_company', [
			'idtbl_company' => $companyid,
			'status'        => 1
		])->row();

		$branch_info = $this->db->get_where('tbl_company_branch', [
			'idtbl_company_branch' => $branchid,
			'status'               => 1
		])->row();

		$purchase_data = $this->ReportModuleinfo->getPurchaseAuditReport(
			$companyid,
			$branchid,
			$master_ids,
			$open_bal_master
		);

		$params = [
			'company_info'      => $company_info,
			'branch_info'       => $branch_info,
			'report_duration'   => $this->ReportModuleinfo->printDate($period_from, 1)
									. ' / '
									. $this->ReportModuleinfo->printDate($period_upto),
			'rpt_from'          => $this->ReportModuleinfo->printDate($period_from, 1),
			'rpt_to'            => $this->ReportModuleinfo->printDate($period_upto),
			'purchase_items' => $purchase_data->items,
			'total_purchase' => $purchase_data->summary->total_purchase,
			'transaction_count' => $purchase_data->summary->transaction_count
		];

		$this->load->view('report_preview_purchase_audit', $params);
	}

	public function audit_sales(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['companylist']=get_company_list();
		$result['branch_period_list_filter']=get_all_company_branch_list();
		$result['all_account_periods']=get_all_account_periods();
		// $result['all_chart_of_acc']=$this->ReportModuleinfo->getChartOfAccounts();
		
		$result['report_gen_url'] = 'ReportModule/preview_sales_audit';
		$result['report_title'] = 'Audit Sales Report';
		
		$this->load->view('periodic_reports_view', $result);
	}

	public function preview_sales_audit(){

		$companyid = $this->input->post('company_id');
		$branchid  = $this->input->post('company_branch_id');
		$period_from = $this->input->post('period_from');
		$period_upto = $this->input->post('period_upto');

		// ── Get from/to master period details ─────────────────────────────────
		$from_master = $this->db->get_where('tbl_master', [
			'idtbl_master' => $period_from,
			'status'       => 1
		])->row();

		$to_master = $this->db->get_where('tbl_master', [
			'idtbl_master' => $period_upto,
			'status'       => 1
		])->row();

		// ── Build period range ────────────────────────────────────────────────
		if(!empty($from_master) && !empty($to_master)){
			$range = $this->ReportModuleinfo->getPeriodRangeMasterIds(
				$from_master->tbl_finacial_month_idtbl_finacial_month,
				$from_master->tbl_finacial_year_idtbl_finacial_year,
				$to_master->tbl_finacial_month_idtbl_finacial_month,
				$to_master->tbl_finacial_year_idtbl_finacial_year,
				$companyid,
				$branchid
			);

			if(!empty($range)){
				$master_ids      = $range['master_ids'];
				$open_bal_master = $range['from_master_id'];
				$is_cross_year   = $range['is_cross_year'];
			} else {
				$master_ids      = [$period_from];
				$open_bal_master = $period_from;
				$is_cross_year   = false;
			}
		} else {
			$master_ids      = [$period_from];
			$open_bal_master = $period_from;
			$is_cross_year   = false;
		}

		// ── Get Company & Branch Info ──────────────────────────────────────────
		$company_info = $this->db->get_where('tbl_company', [
			'idtbl_company' => $companyid,
			'status'        => 1
		])->row();

		$branch_info = $this->db->get_where('tbl_company_branch', [
			'idtbl_company_branch' => $branchid,
			'status'               => 1
		])->row();

		$sales_data = $this->ReportModuleinfo->getSalesAuditReport(
			$companyid,
			$branchid,
			$master_ids,
			$open_bal_master
		);

		$params = [
			'company_info'      => $company_info,
			'branch_info'       => $branch_info,
			'report_duration'   => $this->ReportModuleinfo->printDate($period_from, 1)
									. ' / '
									. $this->ReportModuleinfo->printDate($period_upto),
			'rpt_from'          => $this->ReportModuleinfo->printDate($period_from, 1),
			'rpt_to'            => $this->ReportModuleinfo->printDate($period_upto),
			'sales_items'       => $sales_data->items,
			'total_sales'       => $sales_data->summary->total_sales,
			'transaction_count' => $sales_data->summary->transaction_count
		];

		$this->load->view('report_preview_sales_audit', $params);
	}

	public function internal_audit_control(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['companylist']=get_company_list();
		$result['branch_period_list_filter']=get_all_company_branch_list();
		$result['all_account_periods']=get_all_account_periods();
		// $result['all_chart_of_acc']=$this->ReportModuleinfo->getChartOfAccounts();
		
		$result['report_gen_url'] = 'ReportModule/preview_internal_control_audit';
		$result['report_title'] = 'Internal Control Audit Report';
		
		$this->load->view('periodic_reports_view', $result);
	}

	public function preview_internal_control_audit(){
		$companyid   = $this->input->post('company_id');
		$branchid    = $this->input->post('company_branch_id');
		$period_from = $this->input->post('period_from');
		$period_upto = $this->input->post('period_upto');

		// ── Get from/to master period details ─────────────────────────────
		$from_master = $this->db->get_where('tbl_master', [
			'idtbl_master' => $period_from,
			'status'       => 1
		])->row();

		$to_master = $this->db->get_where('tbl_master', [
			'idtbl_master' => $period_upto,
			'status'       => 1
		])->row();

		// ── Build period range ────────────────────────────────────────────
		if(!empty($from_master) && !empty($to_master)){

			$range = $this->ReportModuleinfo->getPeriodRangeMasterIds(
				$from_master->tbl_finacial_month_idtbl_finacial_month,
				$from_master->tbl_finacial_year_idtbl_finacial_year,
				$to_master->tbl_finacial_month_idtbl_finacial_month,
				$to_master->tbl_finacial_year_idtbl_finacial_year,
				$companyid,
				$branchid
			);

			if(!empty($range)){
				$master_ids    = $range['master_ids'];
				$is_cross_year = $range['is_cross_year'];
			} else {
				$master_ids    = [$period_from];
				$is_cross_year = false;
			}

		} else {
			$master_ids    = [$period_from];
			$is_cross_year = false;
		}

		// ── Get Company & Branch Info ─────────────────────────────────────
		$company_info = $this->db->get_where('tbl_company', [
			'idtbl_company' => $companyid,
			'status'        => 1
		])->row();

		$branch_info = $this->db->get_where('tbl_company_branch', [
			'idtbl_company_branch' => $branchid,
			'status'               => 1
		])->row();

		// ── Call Fixed Internal Control Model ─────────────────────────────
		$audit_data = $this->ReportModuleinfo->getInternalControlAuditReport(
			$companyid,
			$branchid,
			$master_ids   // ✅ only master_ids required
		);

		// ── Prepare View Parameters ───────────────────────────────────────
		$params = [
			'company_info'      => $company_info,
			'branch_info'       => $branch_info,
			'report_duration'   => $this->ReportModuleinfo->printDate($period_from, 1)
									. ' / '
									. $this->ReportModuleinfo->printDate($period_upto),
			'rpt_from'          => $this->ReportModuleinfo->printDate($period_from, 1),
			'rpt_to'            => $this->ReportModuleinfo->printDate($period_upto),

			'zero_transactions'     => $audit_data->zero_transactions,
			'negative_transactions' => $audit_data->negative_transactions,
			'invalid_crdr'          => $audit_data->invalid_crdr,
			'future_transactions'   => $audit_data->future_transactions,
			'duplicate_entries'     => $audit_data->duplicate_entries,
			'invalid_account'       => $audit_data->invalid_account,

			'is_cross_year'   => $is_cross_year,
			'period_from'     => $period_from,
			'period_upto'     => $period_upto
		];

		$this->load->view('report_preview_internal_control_audit', $params);
	}

	public function complete_audit_summary(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['companylist']=get_company_list();
		$result['branch_period_list_filter']=get_all_company_branch_list();
		$result['all_account_periods']=get_all_account_periods();
		// $result['all_chart_of_acc']=$this->ReportModuleinfo->getChartOfAccounts();
		
		$result['report_gen_url'] = 'ReportModule/preview_complete_audit_summary';
		$result['report_title'] = 'Complete Audit Summary Report';
		
		$this->load->view('periodic_reports_view', $result);
	}

	public function preview_complete_audit_summary(){
		$companyid   = $this->input->post('company_id');
		$branchid    = $this->input->post('company_branch_id');
		$period_from = $this->input->post('period_from');
		$period_upto = $this->input->post('period_upto');

		// ── Get from/to master period details ─────────────────────────────
		$from_master = $this->db->get_where('tbl_master', [
			'idtbl_master' => $period_from,
			'status'       => 1
		])->row();

		$to_master = $this->db->get_where('tbl_master', [
			'idtbl_master' => $period_upto,
			'status'       => 1
		])->row();

		// ── Build period range ────────────────────────────────────────────
		if(!empty($from_master) && !empty($to_master)){

			$range = $this->ReportModuleinfo->getPeriodRangeMasterIds(
				$from_master->tbl_finacial_month_idtbl_finacial_month,
				$from_master->tbl_finacial_year_idtbl_finacial_year,
				$to_master->tbl_finacial_month_idtbl_finacial_month,
				$to_master->tbl_finacial_year_idtbl_finacial_year,
				$companyid,
				$branchid
			);

			if(!empty($range)){
				$master_ids    = $range['master_ids'];
				$is_cross_year = $range['is_cross_year'];
			} else {
				$master_ids    = [$period_from];
				$is_cross_year = false;
			}

		} else {
			$master_ids    = [$period_from];
			$is_cross_year = false;
		}

		// ── Get Company & Branch Info ─────────────────────────────────────
		$company_info = $this->db->get_where('tbl_company', [
			'idtbl_company' => $companyid,
			'status'        => 1
		])->row();

		$branch_info = $this->db->get_where('tbl_company_branch', [
			'idtbl_company_branch' => $branchid,
			'status'               => 1
		])->row();

		$summary = $this->ReportModuleinfo->getCompleteAuditSummaryReport(
			$companyid,
			$branchid,
			$master_ids
		);

		$params = [
			'company_info'      => $company_info,
			'branch_info'       => $branch_info,
			'report_duration'   => $this->ReportModuleinfo->printDate($period_from, 1)
									. ' / '
									. $this->ReportModuleinfo->printDate($period_upto),
			'rpt_from'          => $this->ReportModuleinfo->printDate($period_from, 1),
			'rpt_to'            => $this->ReportModuleinfo->printDate($period_upto),
			'summary'         => $summary
		];

		$this->load->view('report_preview_complete_audit_summary', $params);
	}

	public function bank_reconciliation_report(){
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['companylist']=get_company_list();
		$result['branch_period_list_filter']=get_all_company_branch_list();
		$result['all_account_periods']=get_all_account_periods();
		$result['all_chart_of_acc']=$this->ReportModuleinfo->getChartOfAccounts();
		
		$result['report_gen_url'] = 'ReportModule/preview_bank_reconciliation';
		$result['report_title'] = 'Bank Reconciliation Report';
		
		$this->load->view('periodic_reports_view', $result);
	}

	// public function preview_bank_reconciliation(){
	// 	$companyid   = $this->input->post('company_id');
	// 	$branchid    = $this->input->post('company_branch_id');
	// 	$account_id  = $this->input->post('chart_acc_id');
	// 	$period_from = $this->input->post('period_from');
	// 	$period_upto = $this->input->post('period_upto');

	// 	// ── Period Range ──────────────────────────────────────────────────
	// 	$from_master = $this->db->get_where('tbl_master', [
	// 		'idtbl_master' => $period_from,
	// 		'status'       => 1
	// 	])->row();

	// 	$to_master = $this->db->get_where('tbl_master', [
	// 		'idtbl_master' => $period_upto,
	// 		'status'       => 1
	// 	])->row();

	// 	if(!empty($from_master) && !empty($to_master)){
	// 		$range = $this->ReportModuleinfo->getPeriodRangeMasterIds(
	// 			$from_master->tbl_finacial_month_idtbl_finacial_month,
	// 			$from_master->tbl_finacial_year_idtbl_finacial_year,
	// 			$to_master->tbl_finacial_month_idtbl_finacial_month,
	// 			$to_master->tbl_finacial_year_idtbl_finacial_year,
	// 			$companyid,
	// 			$branchid
	// 		);

	// 		if(!empty($range)){
	// 			$master_ids      = $range['master_ids'];
	// 			$open_bal_master = $range['from_master_id'];
	// 		} else {
	// 			$master_ids      = [$period_from];
	// 			$open_bal_master = $period_from;
	// 		}
	// 	} else {
	// 		$master_ids      = [$period_from];
	// 		$open_bal_master = $period_from;
	// 	}

	// 	// ── Get Company & Branch Info ─────────────────────────────────────
	// 	$company_info = $this->db->get_where('tbl_company', [
	// 		'idtbl_company' => $companyid,
	// 		'status'        => 1
	// 	])->row();

	// 	$branch_info = $this->db->get_where('tbl_company_branch', [
	// 		'idtbl_company_branch' => $branchid,
	// 		'status'               => 1
	// 	])->row();

	// 	// ── Get Report ────────────────────────────────────────────────────
	// 	$report = $this->ReportModuleinfo->getBankReconciliationReport(
	// 		$companyid,
	// 		$branchid,
	// 		$account_id,
	// 		$master_ids,
	// 		$open_bal_master
	// 	);

	// 	// ── Build Params ──────────────────────────────────────────────────
	// 	$params = [
	// 		'company_info'      => $company_info,
	// 		'branch_info'       => $branch_info,
	// 		'report_duration'   => $this->ReportModuleinfo->printDate($period_from, 1)
	// 								. ' / '
	// 								. $this->ReportModuleinfo->printDate($period_upto),
	// 		'rpt_from'          => $this->ReportModuleinfo->printDate($period_from, 1),
	// 		'rpt_to'            => $this->ReportModuleinfo->printDate($period_upto),
	// 		'statement'          => $report->statement,
	// 		'reconciled_items'   => $report->reconciled_items,
	// 		'unreconciled_items' => $report->unreconciled_items,
	// 		'bank_adjustments'   => $report->bank_adjustments,
	// 		'book_balance'       => $report->book_balance,
	// 		'summary'            => $report->summary
	// 	];

	// 	$this->load->view('report_preview_bank_reconciliation', $params);
	// }

	// public function preview_bank_reconciliation(){
	// 	$companyid   = $this->input->post('company_id');
	// 	$branchid    = $this->input->post('company_branch_id');
	// 	$account_id  = $this->input->post('chart_acc_id');
	// 	$period_from = $this->input->post('period_from');
	// 	$period_upto = $this->input->post('period_upto');

	// 	// ── Period Range ──────────────────────────────────────────────────
	// 	$from_master = $this->db->get_where('tbl_master', [
	// 		'idtbl_master' => $period_from,
	// 		'status'       => 1
	// 	])->row();

	// 	$to_master = $this->db->get_where('tbl_master', [
	// 		'idtbl_master' => $period_upto,
	// 		'status'       => 1
	// 	])->row();

	// 	if(!empty($from_master) && !empty($to_master)){
	// 		$range = $this->ReportModuleinfo->getPeriodRangeMasterIds(
	// 			$from_master->tbl_finacial_month_idtbl_finacial_month,
	// 			$from_master->tbl_finacial_year_idtbl_finacial_year,
	// 			$to_master->tbl_finacial_month_idtbl_finacial_month,
	// 			$to_master->tbl_finacial_year_idtbl_finacial_year,
	// 			$companyid,
	// 			$branchid
	// 		);

	// 		if(!empty($range)){
	// 			$master_ids      = $range['master_ids'];
	// 			$open_bal_master = $range['from_master_id'];
	// 		} else {
	// 			$master_ids      = [$period_from];
	// 			$open_bal_master = $period_from;
	// 		}
	// 	} else {
	// 		$master_ids      = [$period_from];
	// 		$open_bal_master = $period_from;
	// 	}

	// 	// ── Get Company & Branch Info ─────────────────────────────────────
	// 	$company_info = $this->db->get_where('tbl_company', [
	// 		'idtbl_company' => $companyid,
	// 		'status'        => 1
	// 	])->row();

	// 	$branch_info = $this->db->get_where('tbl_company_branch', [
	// 		'idtbl_company_branch' => $branchid,
	// 		'status'               => 1
	// 	])->row();

	// 	// ── Get Report ────────────────────────────────────────────────────
	// 	// Pass the "to" period's fiscal year/month so the model can build a
	// 	// CUMULATIVE (<=) range for Book Balance / Unreconciled items —
	// 	// not just the narrow $master_ids for the report's own From/To
	// 	// selection. This matters because Bank Reconciliation now carries
	// 	// forward outstanding items across months, so a transaction dated
	// 	// in an earlier month can legitimately be reconciled as part of
	// 	// THIS month's bank rec session.
	// 	$report = $this->ReportModuleinfo->getBankReconciliationReport(
	// 		$companyid,
	// 		$branchid,
	// 		$account_id,
	// 		$master_ids,
	// 		$open_bal_master,
	// 		$to_master->tbl_finacial_year_idtbl_finacial_year,
	// 		$to_master->tbl_finacial_month_idtbl_finacial_month
	// 	);

	// 	// ── Build Params ──────────────────────────────────────────────────
	// 	$params = [
	// 		'company_info'      => $company_info,
	// 		'branch_info'       => $branch_info,
	// 		'report_duration'   => $this->ReportModuleinfo->printDate($period_from, 1)
	// 								. ' / '
	// 								. $this->ReportModuleinfo->printDate($period_upto),
	// 		'rpt_from'          => $this->ReportModuleinfo->printDate($period_from, 1),
	// 		'rpt_to'            => $this->ReportModuleinfo->printDate($period_upto),
	// 		'statement'          => $report->statement,
	// 		'reconciled_items'   => $report->reconciled_items,
	// 		'unreconciled_items' => $report->unreconciled_items,
	// 		'bank_adjustments'   => $report->bank_adjustments,
	// 		'book_balance'       => $report->book_balance,
	// 		'summary'            => $report->summary
	// 	];

	// 	$this->load->view('report_preview_bank_reconciliation', $params);
	// }

	// public function preview_bank_reconciliation(){
	// 	$companyid   = $this->input->post('company_id');
	// 	$branchid    = $this->input->post('company_branch_id');
	// 	$account_id  = $this->input->post('chart_acc_id');
	// 	$period_from = $this->input->post('period_from');
	// 	$period_upto = $this->input->post('period_upto');

	// 	$from_master = $this->db->get_where('tbl_master', [
	// 		'idtbl_master' => $period_from, 'status' => 1
	// 	])->row();

	// 	$to_master = $this->db->get_where('tbl_master', [
	// 		'idtbl_master' => $period_upto, 'status' => 1
	// 	])->row();

	// 	if(!empty($from_master) && !empty($to_master)){
	// 		$range = $this->ReportModuleinfo->getPeriodRangeMasterIds(
	// 			$from_master->tbl_finacial_month_idtbl_finacial_month,
	// 			$from_master->tbl_finacial_year_idtbl_finacial_year,
	// 			$to_master->tbl_finacial_month_idtbl_finacial_month,
	// 			$to_master->tbl_finacial_year_idtbl_finacial_year,
	// 			$companyid,
	// 			$branchid
	// 		);
	// 		$master_ids = !empty($range) ? $range['master_ids'] : [$period_from];
	// 	} else {
	// 		$master_ids = [$period_from];
	// 	}

	// 	$company_info = $this->db->get_where('tbl_company', [
	// 		'idtbl_company' => $companyid, 'status' => 1
	// 	])->row();

	// 	$branch_info = $this->db->get_where('tbl_company_branch', [
	// 		'idtbl_company_branch' => $branchid, 'status' => 1
	// 	])->row();

	// 	if($this->config->item('bank_reconciliation_report') === 'getBankReconciliationReportTwoStage'){
	// 		$report = $this->ReportModuleinfo->getBankReconciliationReportTwoStage(
	// 			$companyid, $branchid, $account_id,
	// 			$master_ids, 
	// 			null, // open_bal_master no longer used for book balance
	// 			$to_master->tbl_finacial_year_idtbl_finacial_year,
	// 			$to_master->tbl_finacial_month_idtbl_finacial_month
	// 		);

	// 		$params = [
	// 			'company_info'    => $company_info,
	// 			'branch_info'     => $branch_info,
	// 			'report_duration' => $this->ReportModuleinfo->printDate($period_from, 1)
	// 								. ' / '
	// 								. $this->ReportModuleinfo->printDate($period_upto),
	// 			'rpt_from'        => $this->ReportModuleinfo->printDate($period_from, 1),
	// 			'rpt_to'          => $this->ReportModuleinfo->printDate($period_upto),
	// 			'statement'       => $report->statement,
	// 			'report'          => $report
	// 		];

	// 		$this->load->view('report_preview_bank_reconciliation_two_stage', $params);
	// 	}
	// 	else{
	// 		$report = $this->ReportModuleinfo->getBankReconciliationReport(
	// 			$companyid,
	// 			$branchid,
	// 			$account_id,
	// 			$master_ids,
	// 			null, // open_bal_master no longer used for book balance
	// 			$to_master->tbl_finacial_year_idtbl_finacial_year,
	// 			$to_master->tbl_finacial_month_idtbl_finacial_month
	// 		);

	// 		$params = [
	// 			'company_info'       => $company_info,
	// 			'branch_info'        => $branch_info,
	// 			'report_duration'    => $this->ReportModuleinfo->printDate($period_from, 1)
	// 									. ' / '
	// 									. $this->ReportModuleinfo->printDate($period_upto),
	// 			'rpt_from'           => $this->ReportModuleinfo->printDate($period_from, 1),
	// 			'rpt_to'             => $this->ReportModuleinfo->printDate($period_upto),
	// 			'statement'          => $report->statement,
	// 			'reconciled_items'   => $report->reconciled_items,
	// 			'unreconciled_items' => $report->unreconciled_items,
	// 			'bank_adjustments'   => $report->bank_adjustments,
	// 			'book_balance'       => $report->book_balance,
	// 			'summary'            => $report->summary
	// 		];

	// 		$this->load->view('report_preview_bank_reconciliation', $params);
	// 	}

	// }

	public function preview_bank_reconciliation(){
		$companyid   = $this->input->post('company_id');
		$branchid    = $this->input->post('company_branch_id');
		$account_id  = $this->input->post('chart_acc_id');
		$period_from = $this->input->post('period_from');
		$period_upto = $this->input->post('period_upto');

		$from_master = $this->db->get_where('tbl_master', [
			'idtbl_master' => $period_from, 'status' => 1
		])->row();

		$to_master = $this->db->get_where('tbl_master', [
			'idtbl_master' => $period_upto, 'status' => 1
		])->row();

		if(!empty($from_master) && !empty($to_master)){
			$range = $this->ReportModuleinfo->getPeriodRangeMasterIds(
				$from_master->tbl_finacial_month_idtbl_finacial_month,
				$from_master->tbl_finacial_year_idtbl_finacial_year,
				$to_master->tbl_finacial_month_idtbl_finacial_month,
				$to_master->tbl_finacial_year_idtbl_finacial_year,
				$companyid,
				$branchid
			);
			$master_ids = !empty($range) ? $range['master_ids'] : [$period_from];
		} else {
			$master_ids = [$period_from];
		}

		$company_info = $this->db->get_where('tbl_company', [
			'idtbl_company' => $companyid, 'status' => 1
		])->row();

		$branch_info = $this->db->get_where('tbl_company_branch', [
			'idtbl_company_branch' => $branchid, 'status' => 1
		])->row();

		// QuickBooks-style report: Cleared Balance is derived from
		// statement_open_bal + matched transactions, so it does NOT
		// use tbl_account_open_bal — null is correct here.
		$report = $this->ReportModuleinfo->getBankReconciliationReport(
			$companyid,
			$branchid,
			$account_id,
			$master_ids,
			null,
			$to_master->tbl_finacial_year_idtbl_finacial_year,
			$to_master->tbl_finacial_month_idtbl_finacial_month
		);

		$params = [
			'company_info'       => $company_info,
			'branch_info'        => $branch_info,
			'report_duration'    => $this->ReportModuleinfo->printDate($period_from, 1)
									. ' / '
									. $this->ReportModuleinfo->printDate($period_upto),
			'rpt_from'           => $this->ReportModuleinfo->printDate($period_from, 1),
			'rpt_to'             => $this->ReportModuleinfo->printDate($period_upto),
			'statement'          => $report->statement,
			'reconciled_items'   => $report->reconciled_items,
			'unreconciled_items' => $report->unreconciled_items,
			'bank_adjustments'   => $report->bank_adjustments,
			'book_balance'       => $report->book_balance,
			'summary'            => $report->summary
		];
		
		$this->load->view('report_preview_bank_reconciliation', $params);
	}

	/* ==========================================================================
	PATCH FOR ReportModule.php
	==========================================================================
	1) In the constructor, add:

			$this->load->model("PnlSetupModuleinfo");

		right after:

			$this->load->model("ReportModuleinfo");

	2) REPLACE the existing preview_pnl() method (around line 878) AND the
		existing add_pnl_sect() method (around line 952) with the two
		methods below. refine_value() and formatPeriodDisplay() stay as-is.
	========================================================================== */


	public function preview_pnl_cus() {
		$company_id = $_SESSION['companyid'];
		$branch_id  = $_SESSION['branchid'];
		$from_master_id = $this->input->post('period_from');
		$to_master_id   = $this->input->post('period_upto');

		// Period range / display labels (unchanged)
		$period_range = $this->ReportModuleinfo->getPeriodRange($from_master_id, $to_master_id);
		$from_date = $period_range['from_date'];
		$to_date   = $period_range['to_date'];

		$from_master = $this->ReportModuleinfo->getMasterDetails($from_master_id);
		$to_master   = $this->ReportModuleinfo->getMasterDetails($to_master_id);

		$sect_trlist = array();

		// 1. REVENUE
		$sales_data = $this->add_pnl_heading_sect('revenue', 'r', $from_master_id, $to_master_id, $company_id, $branch_id);
		$tot_sale = $sales_data['sect_total'];
		$sect_trlist['revenue'] = $sales_data['sect_trlist'];

		// 2. COST OF SALES (still stock-adjusted, same logic as before —
		//    only the account list feeding it now comes from the
		//    "Cost of Sales" heading mapping instead of subcategory '1')
		$open_stock = $this->ReportModuleinfo->calc_stock(true, $from_date);
		$cost_data  = $this->add_pnl_heading_sect('cost_of_sales', 'm', $from_master_id, $to_master_id, $company_id, $branch_id);
		$sale_cost_acc = $cost_data['sect_total'];
		$sect_trlist['cost_of_sales'] = $cost_data['sect_trlist'];

		$tot_sect  = $open_stock + $this->refine_value($sale_cost_acc);
		$tot_stock = $this->ReportModuleinfo->calc_stock(false, $to_date);
		$cost_of_sale = $tot_sect - $tot_stock;

		// 3. GROSS PROFIT
		$gross_profit = $this->refine_value($tot_sale) - $cost_of_sale;

		// 4. OPERATING EXPENSES (INDIRECT)
		$opex_data = $this->add_pnl_heading_sect('operating_expenses', 'l', $from_master_id, $to_master_id, $company_id, $branch_id);
		$tot_operating_expenses = $opex_data['sect_total'];
		$sect_trlist['operating_expenses'] = $opex_data['sect_trlist'];

		// 5. OTHER INCOME
		$other_income_data = $this->add_pnl_heading_sect('other_income', 'm', $from_master_id, $to_master_id, $company_id, $branch_id);
		$tot_other_income = $other_income_data['sect_total'];
		$sect_trlist['other_income'] = $other_income_data['sect_trlist'];

		// 6. OPERATING PROFIT
		$operating_profit = $gross_profit - $this->refine_value($tot_operating_expenses) + $this->refine_value($tot_other_income);

		// 7. SELLING & DISTRIBUTION COSTS
        $sd_data = $this->add_pnl_heading_sect('selling_distribution_costs', 'l', $from_master_id, $to_master_id, $company_id, $branch_id);
        $tot_selling_distribution = $sd_data['sect_total'];
        $sect_trlist['selling_distribution_costs'] = $sd_data['sect_trlist'];
 
        $profit_after_sd = $operating_profit - $this->refine_value($tot_selling_distribution);

		// 8. FINANCE COSTS
		$finance_data = $this->add_pnl_heading_sect('finance_costs', 'l', $from_master_id, $to_master_id, $company_id, $branch_id);
		$tot_finance_costs = $finance_data['sect_total'];
		$sect_trlist['finance_costs'] = $finance_data['sect_trlist'];

		// 9. PROFIT BEFORE TAX
		$profit_before_tax = $profit_after_sd - $this->refine_value($tot_finance_costs);

		// 10. TAXES
		$tax_data = $this->add_pnl_heading_sect('taxes', 'l', $from_master_id, $to_master_id, $company_id, $branch_id);
		$tot_taxes = $tax_data['sect_total'];
		$sect_trlist['taxes'] = $tax_data['sect_trlist'];

		// 11. NET PROFIT AFTER TAX
		$net_profit_after_tax = $profit_before_tax - $this->refine_value($tot_taxes);

		// 12. EARNINGS ALLOCATION
		//     Only "Dividends" type accounts should ever be mapped to this
		//     heading. "Transfer to Retained Earnings" is always the
		//     remainder — it is never account-mapped, always computed.
		$earnings_data = $this->add_pnl_heading_sect('earnings_allocation', 'l', $from_master_id, $to_master_id, $company_id, $branch_id);
		$tot_dividends = $earnings_data['sect_total'];
		$transfer_to_retained_earnings = $net_profit_after_tax - $this->refine_value($tot_dividends);

		$data = array(
			'tot_sale'                      => $tot_sale,
			'cost_of_sale'                  => $cost_of_sale,
			'gross_profit'                  => $gross_profit,
			'tot_operating_expenses'        => $tot_operating_expenses,
			'tot_other_income'              => $tot_other_income,
			'operating_profit'              => $operating_profit,
			'tot_selling_distribution'      => $tot_selling_distribution,
			'tot_finance_costs'             => $tot_finance_costs,
			'profit_before_tax'             => $profit_before_tax,
			'tot_taxes'                     => $tot_taxes,
			'net_profit_after_tax'          => $net_profit_after_tax,
			'tot_dividends'                 => $tot_dividends,
			'transfer_to_retained_earnings' => $transfer_to_retained_earnings,
			'pnl_trlist'                    => $sect_trlist,
			'rpt_from'                      => $this->formatPeriodDisplay($from_master),
			'rpt_to'                        => $this->formatPeriodDisplay($to_master),
			'from_id' 						=> $from_master_id,
			'to_id' 						=> $to_master_id
		);

		$this->load->view('report_preview_pnl_new', $data);
	}

	/**
	 * Builds one PNL section (Revenue, Cost of Sales, Operating Expenses,
	 * Other Income, Finance Costs, Taxes, Earnings Allocation) from the
	 * heading/sub-heading account mapping instead of the fixed
	 * tbl_account_subcategory structure.
	 *
	 * $pnl_section must match a `pnl_section` value in tbl_pnl_heading
	 * (see sql/pnl_heading_schema.sql).
	 */
	private function add_pnl_heading_sect($pnl_section, $align_type, $from_master_id, $to_master_id, $company_id, $branch_id) {
		$heading_id = $this->PnlSetupModuleinfo->getHeadingIdBySection($pnl_section, $company_id);

		if (empty($heading_id)) {
			return array('sect_total' => 0, 'sect_trlist' => array());
		}

		$heading_ids  = $this->PnlSetupModuleinfo->getHeadingAndChildIds($heading_id);
		$section_data = $this->PnlSetupModuleinfo->pnlHeadingSectionDetails($heading_ids, $from_master_id, $to_master_id, $company_id, $branch_id);

		$sect_total  = 0;
		$sect_trlist = array();

		foreach ($section_data as $row) {
			$fig_value = $row['fig_value'];
			$sect_total += $fig_value;

			$sect_trlist[] = array(
				array('class' => '', 'colspan' => 3, 'tdtext' => $row['fig_name']),
				array('class' => 'text-right ' . ($align_type == 'r' ? 'sect_col' : ''), 'colspan' => 1, 'tdtext' => number_format($fig_value, 2)),
				array('class' => '', 'colspan' => 1, 'tdtext' => '&nbsp;')
			);
		}

		// Trailing "Total" row kept for backward compatibility with any
		// other view that still reads the old sect_trlist shape directly;
		// report_preview_pnl.php's render_section_items() skips it and
		// prints its own labeled total row per section instead.
		$sect_trlist[] = array(
			array('class' => 'font-weight-bold', 'colspan' => 3, 'tdtext' => 'Total'),
			array('class' => 'text-right font-weight-bold sect_col', 'colspan' => 1, 'tdtext' => number_format($sect_total, 2)),
			array('class' => '', 'colspan' => 1, 'tdtext' => '&nbsp;')
		);

		return array('sect_total' => $sect_total, 'sect_trlist' => $sect_trlist);
	}
}