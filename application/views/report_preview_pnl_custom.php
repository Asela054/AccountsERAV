<?php 
function generate_pnl_section_html($pnl_section_data){
	foreach($pnl_section_data as $tr_sale){
		if(!empty($tr_sale)){
			echo '<tr>';
			foreach($tr_sale as $td_sale){
				//var_dump($td_sale);echo '<br />';
				echo '<td class="'.$td_sale['class'].'" colspan="'.$td_sale['colspan'].'">';
				echo $td_sale['tdtext'];
				echo '</td>';
			}
			echo '</tr>';
		}
	}
}
?>

							<div class="col">
                                <h6 class="title-style small font-weight-bold mt-2">
                                	<span>Company Name - Profit or Loss Statement <?php echo $rpt_from.'/'.$rpt_to; ?></span>
                                </h6>
                                <style type="text/css">
								td.text-right.sect_col{
									border-bottom:1px solid black;
								}
								</style>
                                <table class="table table-bordered table-sm table-striped" id="tableGrnList">
                                    <thead>
                                        <tr>
                                            <th>Account Type</th>
                                            <th>Account No</th>
                                            <th class="text-right">&nbsp;</th>
                                            <th class="text-right">&nbsp;</th>
                                            <th class="text-right">&nbsp;</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody id="">
                                    	<tr><td colspan="5">Sales Revenue</td></tr>
                                        <?php 
										//$tot_sale = add_sect('1', 'r'); 
										/*
										foreach($pnl_trlist[1] as $tr_sale){
											if(!empty($tr_sale)){
												echo '<tr>';
												foreach($tr_sale as $td_sale){
													//var_dump($td_sale);echo '<br />';
													echo '<td class="'.$td_sale['class'].'" colspan="'.$td_sale['colspan'].'">';
													echo $td_sale['tdtext'];
													echo '</td>';
												}
												echo '</tr>';
											}
										}
										*/
										
										generate_pnl_section_html($pnl_trlist[1]);
										
										?>
                                        <!--tr><td colspan="4">Total Sales Revenue</td><td class="text-right"><?php //echo number_format((float) refine_value($tot_sale), 2, '.', ''); ?></td></tr-->
                                        <tr><td colspan="5">&nbsp;</td></tr>
                                        <tr><td colspan="5">Less: Cost of Sales</td></tr>
                                        <?php //$open_stock = calc_stock(true); ?>
                                        <tr><td colspan="3">Opening stock <?php echo $rpt_from; ?></td><td class="text-right"><?php echo number_format((float)$open_stock, 2, '.', ''); ?></td><td>&nbsp;</td></tr>
										<?php 
										//$sale_cost_acc = add_sect('2', 'm');
										//foreach-block
										generate_pnl_section_html($pnl_trlist[2]);
										
										$tot_sect = $open_stock+$sale_cost_acc; // refine_value($sale_cost_acc); 
										?>
                                        <tr><td colspan="3">Cost of Goods to be sold</td><td class="text-right"><?php echo number_format((float)$tot_sect, 2, '.', ''); ?></td><td>&nbsp;</td></tr>
                                        <?php //$tot_stock = calc_stock(); ?>
                                        <tr><td colspan="3">Less: Closing stock</td><td class="text-right sect_col"><?php echo number_format((float)$tot_stock, 2, '.', ''); ?></td><td>&nbsp;</td></tr>
                                        <?php //$cost_of_sale = $tot_sect-$tot_stock; ?>
                                        <tr><td colspan="4">Cost of Sales</td><td class="text-right sect_col"><?php echo number_format((float)$cost_of_sale, 2, '.', ''); ?></td></tr>
                                        <?php //$gross_profit = refine_value($tot_sale)-$cost_of_sale; ?>
                                        <tr><td colspan="4">Gross Profit</td><td class="text-right"><?php echo number_format((float)$gross_profit, 2, '.', ''); ?></td></tr>
                                        <tr><td colspan="5">&nbsp;</td></tr>
                                        <tr><td colspan="5">Add: Other Income</td></tr>
                                        <?php 
										//$tot_other_income = add_sect('4', 'm', true, true); 
										//foreach-block
										generate_pnl_section_html($pnl_trlist[4]);
										
										?>
                                        <?php //$tot_income = $gross_profit+refine_value($tot_other_income); ?>
                                        
                                        <?php if($tot_other_income>=0){ ?>
                                        <tr><td colspan="4">&nbsp;</td><td class="text-right"><?php echo number_format((float)$tot_income, 2, '.', ''); ?></td></tr>
                                        <?php } ?>
                                        
                                        <tr><td colspan="5">&nbsp;</td></tr>
                                        <tr><td colspan="5">Less: Expenses</td></tr>
                                        <?php 
										//$tot_expenses = add_sect('3', 'l'); 
										//foreach-block
										generate_pnl_section_html($pnl_trlist[3]);
										
										?>
                                        <?php //$tot_transfer = $tot_income-refine_value($tot_expenses); ?>
                                        <tr><td colspan="4">Net profit transferred to the capital account</td><td class="text-right"><?php echo number_format((float)$tot_transfer, 2, '.', ''); ?></td></tr>
                                    </tbody>
                                    
                                </table>
                                <!--div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <hr class="border-dark">
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 d-none">
                                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right px-5" id="btnSaveGrn" disabled="disabled"><i class="far fa-save"></i>&nbsp;Save Report</button>
                                    </div>
                                </div-->
                            </div>