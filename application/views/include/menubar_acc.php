<?php 
$controllermenu=$this->router->fetch_class();
$functionmenu=$this->router->fetch_method();

$menuprivilegearray=$menuaccess;

if($functionmenu=='Useraccount'){
    $addcheck=checkprivilege($menuprivilegearray, 1, 1);
    $editcheck=checkprivilege($menuprivilegearray, 1, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 1, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 1, 4);
}
else if($functionmenu=='Usertype'){
    $addcheck=checkprivilege($menuprivilegearray, 2, 1);
    $editcheck=checkprivilege($menuprivilegearray, 2, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 2, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 2, 4);
}
else if($functionmenu=='Userprivilege'){
    $addcheck=checkprivilege($menuprivilegearray, 3, 1);
    $editcheck=checkprivilege($menuprivilegearray, 3, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 3, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 3, 4);
}
else if($controllermenu=='Accounttype'){
    $addcheck=checkprivilege($menuprivilegearray, 4, 1);
    $editcheck=checkprivilege($menuprivilegearray, 4, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 4, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 4, 4);
}
else if($controllermenu=='Accountcategory'){
    $addcheck=checkprivilege($menuprivilegearray, 5, 1);
    $editcheck=checkprivilege($menuprivilegearray, 5, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 5, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 5, 4);
}
else if($controllermenu=='Accountsubcategory'){
    $addcheck=checkprivilege($menuprivilegearray, 6, 1);
    $editcheck=checkprivilege($menuprivilegearray, 6, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 6, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 6, 4);
}
else if($controllermenu=='Chartofaccount'){
    $addcheck=checkprivilege($menuprivilegearray, 6, 1);
    $editcheck=checkprivilege($menuprivilegearray, 6, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 6, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 6, 4);
}
else if($controllermenu=='Accountingperiod'){
    $addcheck=checkprivilege($menuprivilegearray, 8, 1);
    $editcheck=checkprivilege($menuprivilegearray, 8, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 8, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 8, 4);
}
else if($controllermenu=='Currentperiod'){
    $addcheck=checkprivilege($menuprivilegearray, 9, 1);
    $editcheck=checkprivilege($menuprivilegearray, 9, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 9, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 9, 4);
}
else if($controllermenu=='Accountallocation'){
    $addcheck=checkprivilege($menuprivilegearray, 10, 1);
    $editcheck=checkprivilege($menuprivilegearray, 10, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 10, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 10, 4);
}
else if($controllermenu=='Chartofaccountdetail'){
    $addcheck=checkprivilege($menuprivilegearray, 11, 1);
    $editcheck=checkprivilege($menuprivilegearray, 11, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 11, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 11, 4);
}
else if($controllermenu=='Payablesegregation'){
    $addcheck=checkprivilege($menuprivilegearray, 12, 1);
    $editcheck=checkprivilege($menuprivilegearray, 12, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 12, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 12, 4);
}
else if($controllermenu=='Receiptsegregation'){
    $addcheck=checkprivilege($menuprivilegearray, 13, 1);
    $editcheck=checkprivilege($menuprivilegearray, 13, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 13, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 13, 4);
}
else if($controllermenu=='Payreceivepost'){
    $addcheck=checkprivilege($menuprivilegearray, 14, 1);
    $editcheck=checkprivilege($menuprivilegearray, 14, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 14, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 14, 4);
}
else if($controllermenu=='Chequeinfo'){
    $addcheck=checkprivilege($menuprivilegearray, 15, 1);
    $editcheck=checkprivilege($menuprivilegearray, 15, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 15, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 15, 4);
}
else if($controllermenu=='Pettycashreimburse'){
    $addcheck=checkprivilege($menuprivilegearray, 16, 1);
    $editcheck=checkprivilege($menuprivilegearray, 16, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 16, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 16, 4);
}
else if($controllermenu=='Pettycashexpense'){
    $addcheck=checkprivilege($menuprivilegearray, 17, 1);
    $editcheck=checkprivilege($menuprivilegearray, 17, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 17, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 17, 4);
}
else if($controllermenu=='Openbalance'){
    $addcheck=checkprivilege($menuprivilegearray, 18, 1);
    $editcheck=checkprivilege($menuprivilegearray, 18, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 18, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 18, 4);
}
else if($controllermenu=='Receivablesettle'){
    $addcheck=checkprivilege($menuprivilegearray, 19, 1);
    $editcheck=checkprivilege($menuprivilegearray, 19, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 19, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 19, 4);
}
else if($controllermenu=='Journalentry'){
    $addcheck=checkprivilege($menuprivilegearray, 20, 1);
    $editcheck=checkprivilege($menuprivilegearray, 20, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 20, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 20, 4);
}
else if($controllermenu=='Journalentrylist'){
    $addcheck=checkprivilege($menuprivilegearray, 21, 1);
    $editcheck=checkprivilege($menuprivilegearray, 21, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 21, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 21, 4);
}
else if($controllermenu=='Receivedcheque'){
    $addcheck=checkprivilege($menuprivilegearray, 22, 1);
    $editcheck=checkprivilege($menuprivilegearray, 22, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 22, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 22, 4);
}
else if($controllermenu=='Issuecheque'){
    $addcheck=checkprivilege($menuprivilegearray, 23, 1);
    $editcheck=checkprivilege($menuprivilegearray, 23, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 23, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 23, 4);
}
else if($controllermenu=='Asset'){
    $addcheck=checkprivilege($menuprivilegearray, 24, 1);
    $editcheck=checkprivilege($menuprivilegearray, 24, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 24, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 24, 4);
}
else if($controllermenu=='Assettype'){
    $addcheck=checkprivilege($menuprivilegearray, 25, 1);
    $editcheck=checkprivilege($menuprivilegearray, 25, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 25, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 25, 4);
}
else if($controllermenu=='Depreciationtype'){
    $addcheck=checkprivilege($menuprivilegearray, 26, 1);
    $editcheck=checkprivilege($menuprivilegearray, 26, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 26, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 26, 4);
}
else if($controllermenu=='Depreciationcategory'){
    $addcheck=checkprivilege($menuprivilegearray, 27, 1);
    $editcheck=checkprivilege($menuprivilegearray, 27, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 27, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 27, 4);
}
else if($controllermenu=='Depreciationmethod'){
    $addcheck=checkprivilege($menuprivilegearray, 28, 1);
    $editcheck=checkprivilege($menuprivilegearray, 28, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 28, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 28, 4);
}
else if($controllermenu=='Assetdestroy'){
    $addcheck=checkprivilege($menuprivilegearray, 29, 1);
    $editcheck=checkprivilege($menuprivilegearray, 29, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 29, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 29, 4);
}
else if($controllermenu=='Upgradedipreciation'){
    $addcheck=checkprivilege($menuprivilegearray, 30, 1);
    $editcheck=checkprivilege($menuprivilegearray, 30, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 30, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 30, 4);
}
else if($controllermenu=='Assetsell'){
    $addcheck=checkprivilege($menuprivilegearray, 31, 1);
    $editcheck=checkprivilege($menuprivilegearray, 31, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 31, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 31, 4);
}
else if($controllermenu=='Assetsellreport'){
    $addcheck=checkprivilege($menuprivilegearray, 32, 1);
    $editcheck=checkprivilege($menuprivilegearray, 32, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 32, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 32, 4);
}
else if($controllermenu=='Accountnestcategory'){
    $addcheck=checkprivilege($menuprivilegearray, 33, 1);
    $editcheck=checkprivilege($menuprivilegearray, 33, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 33, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 33, 4);
}
else if($controllermenu=='Assetdepreciation'){
    $addcheck=checkprivilege($menuprivilegearray, 34, 1);
    $editcheck=checkprivilege($menuprivilegearray, 34, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 34, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 34, 4);
}
else if($controllermenu=='Paymentcreate'){
    $addcheck=checkprivilege($menuprivilegearray, 35, 1);
    $editcheck=checkprivilege($menuprivilegearray, 35, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 35, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 35, 4);
}
else if($controllermenu=='Paymentsettle'){
    $addcheck=checkprivilege($menuprivilegearray, 36, 1);
    $editcheck=checkprivilege($menuprivilegearray, 36, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 36, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 36, 4);
}
else if($functionmenu=='periodic_pnl'){
    $addcheck=checkprivilege($menuprivilegearray, 37, 1);
    $editcheck=checkprivilege($menuprivilegearray, 37, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 37, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 37, 4);
}
else if($functionmenu=='periodic_balancesheet'){
    $addcheck=checkprivilege($menuprivilegearray, 38, 1);
    $editcheck=checkprivilege($menuprivilegearray, 38, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 38, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 38, 4);
}
else if($functionmenu=='trial_balance'){
    $addcheck=checkprivilege($menuprivilegearray, 39, 1);
    $editcheck=checkprivilege($menuprivilegearray, 39, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 39, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 39, 4);
}
else if($functionmenu=='period_trial_balance'){
    $addcheck=checkprivilege($menuprivilegearray, 40, 1);
    $editcheck=checkprivilege($menuprivilegearray, 40, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 40, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 40, 4);
}
else if($functionmenu=='DebtorReport'){
    $addcheck=checkprivilege($menuprivilegearray, 41, 1);
    $editcheck=checkprivilege($menuprivilegearray, 41, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 41, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 41, 4);
}
else if($functionmenu=='CreditorReport'){
    $addcheck=checkprivilege($menuprivilegearray, 42, 1);
    $editcheck=checkprivilege($menuprivilegearray, 42, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 42, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 42, 4);
}
else if($controllermenu=='Receivablecreate'){
    $addcheck=checkprivilege($menuprivilegearray, 43, 1);
    $editcheck=checkprivilege($menuprivilegearray, 43, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 43, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 43, 4);
}

function checkprivilege($arraymenu, $menuID, $type){
    foreach($arraymenu as $array){
        if($array->menuid==$menuID){
            if($type==1){
                return $array->add;
            }
            else if($type==2){
                return $array->edit;
            }
            else if($type==3){
                return $array->statuschange;
            }
            else if($type==4){
                return $array->remove;
            }
        }
    }
}
?>
<textarea class="d-none" id="actiontext"><?php if($this->session->flashdata('msg')) {echo $this->session->flashdata('msg');} ?></textarea>

<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav accordion" id="accordionSidenav">
            <div class="sidenav-menu-heading">Core</div>
            <a class="nav-link p-0 px-3 py-2 text-dark" href="<?php echo base_url().'Welcome/Dashboard'; ?>">
                <div class="nav-link-icon"><i class="fas fa-desktop"></i></div>
                Dashboard
            </a>
            <?php if(menucheck($menuprivilegearray, 3)==1 | menucheck($menuprivilegearray, 4)==1 | menucheck($menuprivilegearray, 5)==1 | menucheck($menuprivilegearray, 8)==1 | menucheck($menuprivilegearray, 9)==1 | menucheck($menuprivilegearray, 33)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseMaster" aria-expanded="false" aria-controls="collapseMaster">
                <div class="nav-link-icon"><i class="fas fa-list"></i></div>
                Master Data
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Accounttype" | $controllermenu=="Accountcategory" | $controllermenu=="Accountsubcategory" | $controllermenu=="Accountingperiod" | $controllermenu=="Currentperiod" | $controllermenu=="Accountnestcategory"){echo 'show';} ?>" id="collapseMaster" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 4)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Accounttype'; ?>">Account Type</a>
                    <?php } if(menucheck($menuprivilegearray, 5)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Accountcategory'; ?>">Account Prime Category</a>
                    <?php } if(menucheck($menuprivilegearray, 6)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Accountsubcategory'; ?>">Account Category</a>
                    <?php } if(menucheck($menuprivilegearray, 33)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Accountnestcategory'; ?>">Account Sub Category</a>
                    <?php } if(menucheck($menuprivilegearray, 8)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Accountingperiod'; ?>">Accounting Period</a>
                    <?php } if(menucheck($menuprivilegearray, 9)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Currentperiod'; ?>">Current Period</a>                    
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 6)==1 | menucheck($menuprivilegearray, 11)==1 | menucheck($menuprivilegearray, 10)==1 | menucheck($menuprivilegearray, 18)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsechartofaccount" aria-expanded="false" aria-controls="collapsechartofaccount">
                <div class="nav-link-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                Chart Of Account Info
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Chartofaccount" | $controllermenu=="Chartofaccountdetail" | $controllermenu=="Accountallocation" | $controllermenu=="Openbalance"){echo 'show';} ?>" id="collapsechartofaccount" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 4)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Chartofaccount'; ?>">Chart Of Account</a>
                    <?php } if(menucheck($menuprivilegearray, 5)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Chartofaccountdetail'; ?>">Detail Account</a>
                    <?php } if(menucheck($menuprivilegearray, 10)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Accountallocation'; ?>">Account Allocation</a>
                    <?php } if(menucheck($menuprivilegearray, 18)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Openbalance'; ?>">Opening Balance</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 13)==1 | menucheck($menuprivilegearray, 19)==1 | menucheck($menuprivilegearray, 43)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsesegregation" aria-expanded="false" aria-controls="collapsesegregation">
                <div class="nav-link-icon"><i class="fas fa-tasks"></i></div>
                Receivable
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Receiptsegregation" | $controllermenu=="Receivablesettle" | $controllermenu=="Receivablecreate"){echo 'show';} ?>" id="collapsesegregation" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 43)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Receivablecreate'; ?>">Receivable Create</a>
                    <?php } if(menucheck($menuprivilegearray, 13)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Receiptsegregation'; ?>">Receivable Segregation</a>
                    <?php } if(menucheck($menuprivilegearray, 19)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Receivablesettle'; ?>">Receivable Settle</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 35)==1 | menucheck($menuprivilegearray, 12)==1 | menucheck($menuprivilegearray, 36)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsePayments" aria-expanded="false" aria-controls="collapsePayments">
                <div class="nav-link-icon"><i class="fas fa-wallet"></i></div>
                Payments
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Paymentcreate" | $controllermenu=="Payablesegregation" | $controllermenu=="Paymentsettle"){echo 'show';} ?>" id="collapsePayments" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 35)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Paymentcreate'; ?>">Payment Create</a>
                    <?php } if(menucheck($menuprivilegearray, 12)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Payablesegregation'; ?>">Payment Segregation</a>
                    <?php } if(menucheck($menuprivilegearray, 36)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Paymentsettle'; ?>">Payment Settle</a>
                    <?php } ?>
                    <!-- <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php // echo base_url().'AccountPayment/view'; ?>">Utility Bills</a> -->
                    <!-- <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php // echo base_url().'IssuePayment/view'; ?>">Bill Settlement</a> -->
                </nav>
            </div>
            <?php // } if(menucheck($menuprivilegearray, 19)==1){ ?> 
            <!-- <a class="nav-link p-0 px-3 py-2" href="<?php // echo base_url().'Receivablesettle'; ?>">
                <div class="nav-link-icon"><i class="far fa-money-bill-alt"></i></div>
                Receivable Settle
            </a> -->
            <?php } if(menucheck($menuprivilegearray, 20)==1 | menucheck($menuprivilegearray, 20)==1){ ?> 
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsejournalentry" aria-expanded="false" aria-controls="collapsejournalentry">
                <div class="nav-link-icon"><i class="fas fa-exchange-alt"></i></div>
                Journal Entry
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Journalentry" | $controllermenu=="Journalentrylist"){echo 'show';} ?>" id="collapsejournalentry" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 20)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Journalentry'; ?>">Journal Entry</a>
                    <?php } if(menucheck($menuprivilegearray, 21)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Journalentrylist'; ?>">Journal Entry List</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 16)==1 | menucheck($menuprivilegearray, 17)==1 | menucheck($menuprivilegearray, 18)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsepettycash" aria-expanded="false" aria-controls="collapsepettycash">
                <div class="nav-link-icon"><i class="fas fa-coins"></i></div>
                Petty Cash
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Pettycashreimburse" | $controllermenu=="Pettycashexpense"){echo 'show';} ?>" id="collapsepettycash" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 17)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Pettycashexpense'; ?>">Petty Cash Expenses</a>
                    <?php } if(menucheck($menuprivilegearray, 16)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Pettycashreimburse'; ?>">Petty Cash Reimburse</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 14)==1){ ?> 
            <a class="nav-link p-0 px-3 py-2" href="<?php echo base_url().'Payreceivepost'; ?>">
                <div class="nav-link-icon"><i class="fas fa-exchange-alt"></i></div>
                Post All Data
            </a>
            <?php } if(menucheck($menuprivilegearray, 15)==1 | menucheck($menuprivilegearray, 22)==1 | menucheck($menuprivilegearray, 23)==1){ ?> 
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsechequeinfo" aria-expanded="false" aria-controls="collapsechequeinfo">
                <div class="nav-link-icon"><i class="fas fa-money-check-alt"></i></div>
                Cheque Info
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Chequeinfo" | $controllermenu=="Receivedcheque" | $controllermenu=="Issuecheque"){echo 'show';} ?>" id="collapsechequeinfo" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 15)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Chequeinfo'; ?>">Cheque Information</a>    
                    <?php } if(menucheck($menuprivilegearray, 22)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Receivedcheque'; ?>">Received Cheque</a>    
                    <?php } if(menucheck($menuprivilegearray, 23)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Issuecheque'; ?>">Issue Cheque</a>    
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 37)==1 | menucheck($menuprivilegearray, 38)==1 | menucheck($menuprivilegearray, 39)==1 | menucheck($menuprivilegearray, 40)==1 | menucheck($menuprivilegearray, 41)==1 | menucheck($menuprivilegearray, 42)==1){ ?> 
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseReport" aria-expanded="false" aria-controls="collapseReport">
                <div class="nav-link-icon"><i class="far fa-file-pdf"></i></div>
                Reports
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($functionmenu=="periodic_pnl" | $functionmenu=="periodic_balancesheet" | $functionmenu=="ledger_folio" | $functionmenu=="trial_balance" | $functionmenu=="DebtorReport" | $functionmenu=="CreditorReport"){echo 'show';} ?>" id="collapseReport" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 37)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ReportModule/periodic_pnl'; ?>">Profit & Lost</a> 
                    <?php } if(menucheck($menuprivilegearray, 38)==1){ ?>                   
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ReportModule/periodic_balancesheet'; ?>">Balance sheet</a>
                    <?php } if(menucheck($menuprivilegearray, 39)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ReportModule/ledger_folio'; ?>">Ledger Folio</a>
                    <?php } if(menucheck($menuprivilegearray, 40)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ReportModule/trial_balance'; ?>">Trial Balance</a>
                    <?php } if(menucheck($menuprivilegearray, 41)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ReportModule/DebtorReport'; ?>">Debtor Report</a>
                    <?php } if(menucheck($menuprivilegearray, 42)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ReportModule/CreditorReport'; ?>">Creditor Report</a>
                    <?php } ?>

                    <!-- <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'BankReconciliation/view'; ?>">Bank Reconciliation</a>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ReportModule/periodic_pnl_custom'; ?>">Periodic Reports-Custom PNL</a>                    
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ReportModule/period_trial_balance'; ?>">Period Trial Balance</a> -->
                </nav>
            </div>
            <?php } ?>
            <?php if(menucheck($menuprivilegearray, 25)==1 | menucheck($menuprivilegearray, 26)==1 | menucheck($menuprivilegearray, 27)==1 | menucheck($menuprivilegearray, 28)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseAssetMaster" aria-expanded="false" aria-controls="collapseAssetMaster">
                <div class="nav-link-icon"><i class="fas fa-wallet"></i></div>
                Asset Master Data
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Assettype" | $controllermenu=="Depreciationtype" | $controllermenu=="Depreciationcategory" | $controllermenu=="Depreciationmethod"){echo 'show';} ?>" id="collapseAssetMaster" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 25)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Assettype'; ?>">Asset Type</a>
                    <?php } if(menucheck($menuprivilegearray, 26)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Depreciationtype'; ?>">Depreciation Type</a>
                    <?php } if(menucheck($menuprivilegearray, 27)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Depreciationcategory'; ?>">Depreciation Category</a>
                    <?php } if(menucheck($menuprivilegearray, 28)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Depreciationmethod'; ?>">Depreciation Method</a>
                    <?php }?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 24)==1 | menucheck($menuprivilegearray, 29 )==1 | menucheck($menuprivilegearray, 30 )==1 | menucheck($menuprivilegearray, 31 )==1 | menucheck($menuprivilegearray, 32 )==1 | menucheck($menuprivilegearray, 34 )==1){ ?> 
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="<?php echo base_url().'Depreciation'; ?>" data-toggle="collapse" data-target="#collapseDepreciation" aria-expanded="false" aria-controls="collapseDepreciation">
                <div class="nav-link-icon"><i class="fas fa-chart-line"></i></div>
                Assets
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Asset" | $controllermenu=="Assetdestroy" | $controllermenu=="Upgradedipreciation" | $controllermenu=="Assetsell" | $controllermenu=="Assetsellreport" | $controllermenu=="Assetdepreciation"){echo 'show';} ?>" id="collapseDepreciation" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 24)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Asset'; ?>">Assets</a>
                    <?php } if(menucheck($menuprivilegearray, 29)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Assetdestroy'; ?>">Assets Destroy</a>
                    <?php } if(menucheck($menuprivilegearray, 30)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Upgradedipreciation'; ?>">Assets Improvement</a>
                    <?php } if(menucheck($menuprivilegearray, 31)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Assetsell'; ?>">Assets Sale</a>
                    <?php } if(menucheck($menuprivilegearray, 34)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Assetdepreciation'; ?>">Assets Depreciation</a>
                    <!-- <?php // } if(menucheck($menuprivilegearray, 32)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php // echo base_url().'Assetsellreport'; ?>">Assets Sale Report</a> -->
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 1)==1 | menucheck($menuprivilegearray, 2)==1 | menucheck($menuprivilegearray, 3)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseUser" aria-expanded="false" aria-controls="collapseUser">
                <div class="nav-link-icon"><i class="fas fa-user"></i></div>
                User Account
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($functionmenu=="Useraccount" | $functionmenu=="Usertype" | $functionmenu=="Userprivilege"){echo 'show';} ?>" id="collapseUser" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 1)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'User/Useraccount'; ?>">User Account</a>
                    <?php } if(menucheck($menuprivilegearray, 2)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'User/Usertype'; ?>">Type</a>
                    <?php } if(menucheck($menuprivilegearray, 3)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'User/Userprivilege'; ?>">Privilege</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>
        </div>
    </div>
    <div class="sidenav-footer">
        <div class="sidenav-footer-content">
            <div class="sidenav-footer-subtitle">Logged in as:</div>
            <div class="sidenav-footer-title"><?php echo ucfirst($_SESSION['name']); ?></div>
        </div>
    </div>
</nav>