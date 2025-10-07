<div class="col-12 text-right">
    <button type="button" id="btnpdfconvert" class="btn btn-danger btn-sm px-4 mb-3"><i class="fas fa-file-pdf mr-2"></i>PDF</button>
</div>
<div class="col-12">
    <h6 class="title-style small font-weight-bold mt-2"><span><?php echo $_SESSION['company'] ?> - Balance Sheet Statement <?php echo $rpt_from.' / '.$rpt_to; ?></span></h6>
    <table class="table table-bordered table-sm table-striped small" id="tablereport">
        <thead>
            <tr>
                <th>Account Type</th>
                <th>Account No</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th colspan='4'><u>ASSETS</u></th>
            </tr>
            <?php            
            // print_r($balanceinfo->result()); 
            $subcategory=0;
            $nestcategory=0;
            $subtotal=0;
            $nesttotal=0;
            $nettotal=0;
            foreach($balanceinfo->result() as $rowdatalist){ 
                if($rowdatalist->idtbl_account_category==1){
                    if($subcategory!=$rowdatalist->tbl_account_subcategory_idtbl_account_subcategory){
                        $i=1;
                        $subtotal=0;
                        $subcategory=$rowdatalist->tbl_account_subcategory_idtbl_account_subcategory;
                        $showsub=$rowdatalist->subcategory;

                        if($nestcategory!=$rowdatalist->idtbl_account_nestcategory){
                            $j=1;
                            $nesttotal=0;
                            $nestcategory=$rowdatalist->idtbl_account_nestcategory;
                            $shownest=$rowdatalist->nestcategory;
                        }
                        else{
                            $shownest='';
                        }
                    }
                    else{
                        $showsub='';
                        if($nestcategory!=$rowdatalist->idtbl_account_nestcategory){
                            $j=1;
                            $nesttotal=0;
                            $nestcategory=$rowdatalist->idtbl_account_nestcategory;
                            $shownest=$rowdatalist->nestcategory;
                        }
                        else{
                            $shownest='';
                        }
                    }

                    if(!empty($showsub)){
            ?>
            <tr>
                <th colspan='4'><?php echo $showsub; ?></th>
            </tr>
            <?php 
                    }  
            ?>
            <tr>
                <td><?php echo $shownest; ?></td>
                <td><?php echo $rowdatalist->accountno.' - '.$rowdatalist->accountname; ?></td>
                <td class="text-right">
                    <?php 
                        if($rowdatalist->nettrabalreal<0){
                            echo '('.number_format($rowdatalist->nettrabal, 2).')'; 
                        }
                        else{
                            echo number_format($rowdatalist->nettrabal, 2); 
                        }
                        $subtotal+=$rowdatalist->nettrabalreal;
                        $nesttotal+=$rowdatalist->nettrabalreal; 
                    ?>
                </td>
                <td>&nbsp;</td>
            </tr>
            <?php if(array_count_values(array_column($balanceinfo->result(), 'idtbl_account_nestcategory'))[$nestcategory]==$j){ ?>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <th class="text-right">
                    <?php 
                    if($nesttotal<0){
                        echo '('.number_format(($nesttotal*-1), 2).')';  
                    }
                    else{
                        echo number_format($nesttotal, 2); 
                    }
                    ?>
                </th>
                <th class="text-right">
                <?php 
                    if(array_count_values(array_column($balanceinfo->result(), 'tbl_account_subcategory_idtbl_account_subcategory'))[$subcategory]==$i){
                        if($rowdatalist->nettrabalreal<0){
                            echo '('.number_format($subtotal, 2).')'; 
                        }
                        else{
                            echo number_format($subtotal, 2); 
                        }
                        $nettotal+=$subtotal; 
                    }
                ?>
                </th>
            </tr>
            <?php 
                    } 
                    $i++;
                    $j++;
                }  
            ?>
            <?php 
            } 
            ?>
            <tr>
                <th colspan='3' class="border border-right-0">&nbsp;</th>
                <th class="text-right border-dark border-left-0 border-right-0">&nbsp;</th>
            </tr>
            <tr>
                <th colspan='3'>TOTAL ASSETS</th>
                <th class="text-right" style="border-bottom: #1f2d41 4px double">
                    <?php 
                    if($nettotal<0){echo '('.number_format($nettotal, 2).')';}
                    else{echo number_format($nettotal, 2);}
                    ?>
                </th>
            </tr>
            <tr>
                <th colspan='4'>&nbsp;</th>
            </tr>
            <tr>
                <th colspan='4'><u>EQUITY & LIABILITIES</u></th>
            </tr>
            <!-- Add Profit/Loss row in the equity section -->
            <tr>
                <td>Retained Earnings</td>
                <td>Net Profit/Loss for the Period</td>
                <td class="text-right">
                    <?php 
                    if($net_profit_loss > 0){
                        echo '('.number_format($net_profit_loss, 2).')'; 
                    } else {
                        echo number_format(abs($net_profit_loss), 2); 
                    }
                    ?>
                </td>
                <th class="text-right">&nbsp;</th>
            </tr>
            <?php             
            $subcategory=0;
            $nestcategory=0;
            $subtotal=0;
            $nesttotal=0;
            $nettotal=0;
            $nettotal=$net_profit_loss;
            foreach($balanceinfo->result() as $rowdatalist){ 
                if($rowdatalist->idtbl_account_category>1){
                    // echo $rowdatalist->accountno.'-->'.$rowdatalist->tbl_account_subcategory_idtbl_account_subcategory.'-->'.$rowdatalist->idtbl_account_nestcategory.'<br>';
                    if($subcategory!=$rowdatalist->tbl_account_subcategory_idtbl_account_subcategory){
                        $i=1;
                        $subtotal=0;
                        $subcategory=$rowdatalist->tbl_account_subcategory_idtbl_account_subcategory;
                        $showsub=$rowdatalist->subcategory;
                        
                        if($nestcategory!=$rowdatalist->idtbl_account_nestcategory){
                            $j=1;
                            $nesttotal=0;
                            $nestcategory=$rowdatalist->idtbl_account_nestcategory;
                            $shownest=$rowdatalist->nestcategory;
                        }
                        else{
                            $shownest='';
                        }
                    }
                    else{
                        $showsub='';
                        if($nestcategory!=$rowdatalist->idtbl_account_nestcategory){
                            $j=1;
                            $nesttotal=0;
                            $nestcategory=$rowdatalist->idtbl_account_nestcategory;
                            $shownest=$rowdatalist->nestcategory;
                        }
                        else{
                            $shownest='';
                        }
                    }

                    if(!empty($showsub)){
            ?>
            <tr>
                <th colspan='4'><?php echo $showsub; ?></th>
            </tr>
            <?php 
                    }  
            ?>
            <tr>
                <td><?php echo $shownest; ?></td>
                <td><?php echo $rowdatalist->accountno.' - '.$rowdatalist->accountname;; ?></td>
                <td class="text-right">
                    <?php 
                        if($rowdatalist->nettrabalreal>0){
                            echo '('.number_format($rowdatalist->nettrabal, 2).')'; 
                        }
                        else{
                            echo number_format($rowdatalist->nettrabal, 2); 
                        }
                        // echo number_format($rowdatalist->nettrabal, 2); 
                        $subtotal+=($rowdatalist->nettrabalreal*-1); 
                        $nesttotal+=($rowdatalist->nettrabalreal*-1); 
                    ?>
                </td>
                <th class="text-right">&nbsp;</th>
            </tr>
            <?php if(array_count_values(array_column($balanceinfo->result(), 'idtbl_account_nestcategory'))[$nestcategory]==$j){ ?>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <th class="text-right"><?php echo number_format($nesttotal, 2); ?></th>
                <th class="text-right">
                <?php 
                    if(array_count_values(array_column($balanceinfo->result(), 'tbl_account_subcategory_idtbl_account_subcategory'))[$subcategory]==$i){
                        if($rowdatalist->nettrabalreal>0){
                            echo '('.number_format($subtotal, 2).')'; 
                        }
                        else{
                            echo number_format($subtotal, 2); 
                        }
                        $nettotal+=($subtotal*-1); 
                    }
                ?>
                </th>
            </tr>
            <?php 
                    } 
                    $i++;
                    $j++;
                }  
            ?>
            <?php 
            } 
            ?>
            <tr>
                <th colspan='3' class="border border-right-0">&nbsp;</th>
                <th class="text-right border-dark border-left-0 border-right-0">&nbsp;</th>
            </tr>
            <tr>
                <th colspan='3'>TOTAL EQUITY & LIABILITIES</th>
                <th class="text-right" style="border-bottom: #1f2d41 4px double">
                    <?php 
                        if($nettotal>0){echo '('.number_format($nettotal, 2).')';}
                        else{echo number_format(($nettotal*-1), 2);}
                    ?>    
                </th>
            </tr>
            <tr>
                <th colspan='4'>&nbsp;</th>
            </tr>
        </tbody>
    </table>    
</div>
<input type="hidden" id="periodtitle" value="<?php echo $rpt_from.' / '.$rpt_to; ?>">
<input type="hidden" id="reporttitle" value="Balance Sheet Statement">
<input type="hidden" id="filetitle" value="balance_sheet_">
<input type="hidden" id="reporttype" value="1">