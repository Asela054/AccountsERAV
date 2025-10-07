<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class ReportModule extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("Commeninfo");
        $this->load->model("ReportModuleinfo");
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
	
	public function preview_balancesheet(){
		$params['a'] = $this->input->post('company_id');
		$params['b'] = $this->input->post('company_branch_id');
		$params['c'] = $this->input->post('period_from');
		$params['d'] = $this->input->post('period_upto');
		$params['rpt_from'] = $this->ReportModuleinfo->printDate($this->input->post('period_from'), 1);
		$params['rpt_to'] = $this->ReportModuleinfo->printDate($this->input->post('period_upto'));
		$params['balanceinfo']=$this->ReportModuleinfo->Getbalancesheetinfo();

		// Calculate net profit/loss for display
		$company_id = $this->input->post('company_id');
		$branch_id = $this->input->post('company_branch_id');
		$period_from = $this->input->post('period_from');
		$period_to = $this->input->post('period_upto');
		
		$params['net_profit_loss'] = $this->ReportModuleinfo->calculateNetProfitLoss(
			$company_id, $branch_id, $period_from, $period_to
		);
		
		$this->load->view('report_preview_balancesheet', $params);
	}
	
	public function preview_ledgerfolio(){
		$params['a'] = $this->input->post('company_id');
		$params['b'] = $this->input->post('company_branch_id');
		$params['c'] = $this->input->post('period_from');
		$params['d'] = $this->input->post('period_upto');
		
		$companyBranchId = $this->input->post('company_branch_id');
		$chartAccId = $this->input->post('chart_acc_id');
		$acc_period_from = $this->input->post('period_from');//master-id
		$acc_period_upto = $this->input->post('period_upto');
		
		$open_stock = $this->ReportModuleinfo->ledgerFolioOpenStockValue($companyBranchId, $chartAccId);
		
		$ledger_folio_data = $this->ReportModuleinfo->ledgerFolioDetails($companyBranchId, $chartAccId, $acc_period_from);
		$total_detail_rows = count($ledger_folio_data);
		
		$params['account_code'] = $open_stock->accountno;
		$acc_period_from = $this->input->post('period_from');
		$acc_period_upto = $this->input->post('period_upto');
		$rpt_from_str = $this->ReportModuleinfo->printDate($acc_period_from, 1);
		$rpt_to_str = $this->ReportModuleinfo->printDate($acc_period_upto);
		$params['report_duration'] = $rpt_from_str.' / '.$rpt_to_str;
		$params['open_stock'] = $open_stock->ac_open_balance;
		
		$params['ledger_folio_data'] = $ledger_folio_data;
		$params['total_rows_ledger_folio'] = $total_detail_rows;
		
		$this->load->view('report_preview_ledgerfolio', $params);
	}
	
	public function preview_trialbalance(){
		$params['a'] = $this->input->post('company_id');
		$params['b'] = $this->input->post('company_branch_id');
		$params['c'] = $this->input->post('period_from');
		$params['d'] = $this->input->post('period_upto');
		
		$acc_period_from = $this->input->post('period_from');//master-id
		$acc_period_upto = $this->input->post('period_upto');
		
		$open_stock = $this->ReportModuleinfo->calc_stock(true, $acc_period_from);
		$rpt_from_str = $this->ReportModuleinfo->printDate($acc_period_from, 1);
		$stock_opening_date = new DateTime($rpt_from_str);
		$stock_closing_date = $stock_opening_date->modify("-1 days")->format('Y-m-d');//display previous date as closing-date
		
		$reportPeriod = $this->input->post('period_from');
		$trial_balance_data = $this->ReportModuleinfo->trialBalanceDetails($this->input->post('company_branch_id'), $reportPeriod);
		
		$params['open_stock'] = $open_stock;
		$params['trial_balance_data'] = $trial_balance_data;
		
		$params['rpt_from'] = $rpt_from_str;
		$params['rpt_to'] = $this->ReportModuleinfo->printDate($acc_period_upto);
		$params['stock_date'] = $stock_closing_date;
		
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
            'rpt_to' => $this->formatPeriodDisplay($to_master)
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
        $result=get_chart_acount_select2($searchTerm, $companyid, $branchid);
	}
}