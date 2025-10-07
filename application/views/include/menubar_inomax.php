<?php 
$controllermenu=$this->router->fetch_class();
$functionmenu=$this->router->fetch_method();

$menuprivilegearray=$menuaccess;

if($functionmenu=='Userprivilege'){
    $addcheck=checkprivilege($menuprivilegearray, 1, 1);
    $editcheck=checkprivilege($menuprivilegearray, 1, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 1, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 1, 4);
}
else if($controllermenu=='Accounttype'){
    $addcheck=checkprivilege($menuprivilegearray, 66, 1);
    $editcheck=checkprivilege($menuprivilegearray, 66, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 66, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 66, 4);
}
else if($controllermenu=='Accountcategory'){
    $addcheck=checkprivilege($menuprivilegearray, 67, 1);
    $editcheck=checkprivilege($menuprivilegearray, 67, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 67, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 67, 4);
}
else if($controllermenu=='Accountsubcategory'){
    $addcheck=checkprivilege($menuprivilegearray, 68, 1);
    $editcheck=checkprivilege($menuprivilegearray, 68, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 68, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 68, 4);
}
else if($controllermenu=='Chartofaccount'){
    $addcheck=checkprivilege($menuprivilegearray, 69, 1);
    $editcheck=checkprivilege($menuprivilegearray, 69, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 69, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 69, 4);
}
else if($controllermenu=='Accountingperiod'){
    $addcheck=checkprivilege($menuprivilegearray, 70, 1);
    $editcheck=checkprivilege($menuprivilegearray, 70, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 70, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 70, 4);
}
else if($controllermenu=='Currentperiod'){
    $addcheck=checkprivilege($menuprivilegearray, 71, 1);
    $editcheck=checkprivilege($menuprivilegearray, 71, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 71, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 71, 4);
}
else if($controllermenu=='Accountallocation'){
    $addcheck=checkprivilege($menuprivilegearray, 72, 1);
    $editcheck=checkprivilege($menuprivilegearray, 72, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 72, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 72, 4);
}
else if($controllermenu=='Chartofaccountdetail'){
    $addcheck=checkprivilege($menuprivilegearray, 73, 1);
    $editcheck=checkprivilege($menuprivilegearray, 73, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 73, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 73, 4);
}
else if($controllermenu=='Payablesegregation'){
    $addcheck=checkprivilege($menuprivilegearray, 74, 1);
    $editcheck=checkprivilege($menuprivilegearray, 74, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 74, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 74, 4);
}
else if($controllermenu=='Receiptsegregation'){
    $addcheck=checkprivilege($menuprivilegearray, 75, 1);
    $editcheck=checkprivilege($menuprivilegearray, 75, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 75, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 75, 4);
}
else if($controllermenu=='Payreceivepost'){
    $addcheck=checkprivilege($menuprivilegearray, 76, 1);
    $editcheck=checkprivilege($menuprivilegearray, 76, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 76, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 76, 4);
}
else if($controllermenu=='Chequeinfo'){
    $addcheck=checkprivilege($menuprivilegearray, 77, 1);
    $editcheck=checkprivilege($menuprivilegearray, 77, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 77, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 77, 4);
}
else if($controllermenu=='Pettycashreimburse'){
    $addcheck=checkprivilege($menuprivilegearray, 78, 1);
    $editcheck=checkprivilege($menuprivilegearray, 78, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 78, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 78, 4);
}
else if($controllermenu=='Pettycashexpense'){
    $addcheck=checkprivilege($menuprivilegearray, 79, 1);
    $editcheck=checkprivilege($menuprivilegearray, 79, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 79, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 79, 4);
}
else if($controllermenu=='Openbalance'){
    $addcheck=checkprivilege($menuprivilegearray, 80, 1);
    $editcheck=checkprivilege($menuprivilegearray, 80, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 80, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 80, 4);
}
else if($controllermenu=='Receivablesettle'){
    $addcheck=checkprivilege($menuprivilegearray, 81, 1);
    $editcheck=checkprivilege($menuprivilegearray, 81, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 81, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 81, 4);
}
else if($controllermenu=='Journalentry'){
    $addcheck=checkprivilege($menuprivilegearray, 82, 1);
    $editcheck=checkprivilege($menuprivilegearray, 82, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 82, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 82, 4);
}
else if($controllermenu=='Journalentrylist'){
    $addcheck=checkprivilege($menuprivilegearray, 83, 1);
    $editcheck=checkprivilege($menuprivilegearray, 83, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 83, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 83, 4);
}
else if($controllermenu=='Receivedcheque'){
    $addcheck=checkprivilege($menuprivilegearray, 84, 1);
    $editcheck=checkprivilege($menuprivilegearray, 84, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 84, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 84, 4);
}
else if($controllermenu=='Issuecheque'){
    $addcheck=checkprivilege($menuprivilegearray, 85, 1);
    $editcheck=checkprivilege($menuprivilegearray, 85, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 85, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 85, 4);
}
// else if($controllermenu=='Asset'){
//     $addcheck=checkprivilege($menuprivilegearray, 24, 1);
//     $editcheck=checkprivilege($menuprivilegearray, 24, 2);
//     $statuscheck=checkprivilege($menuprivilegearray, 24, 3);
//     $deletecheck=checkprivilege($menuprivilegearray, 24, 4);
// }
// else if($controllermenu=='Assettype'){
//     $addcheck=checkprivilege($menuprivilegearray, 25, 1);
//     $editcheck=checkprivilege($menuprivilegearray, 25, 2);
//     $statuscheck=checkprivilege($menuprivilegearray, 25, 3);
//     $deletecheck=checkprivilege($menuprivilegearray, 25, 4);
// }
// else if($controllermenu=='Depreciationtype'){
//     $addcheck=checkprivilege($menuprivilegearray, 26, 1);
//     $editcheck=checkprivilege($menuprivilegearray, 26, 2);
//     $statuscheck=checkprivilege($menuprivilegearray, 26, 3);
//     $deletecheck=checkprivilege($menuprivilegearray, 26, 4);
// }
// else if($controllermenu=='Depreciationcategory'){
//     $addcheck=checkprivilege($menuprivilegearray, 27, 1);
//     $editcheck=checkprivilege($menuprivilegearray, 27, 2);
//     $statuscheck=checkprivilege($menuprivilegearray, 27, 3);
//     $deletecheck=checkprivilege($menuprivilegearray, 27, 4);
// }
// else if($controllermenu=='Depreciationmethod'){
//     $addcheck=checkprivilege($menuprivilegearray, 28, 1);
//     $editcheck=checkprivilege($menuprivilegearray, 28, 2);
//     $statuscheck=checkprivilege($menuprivilegearray, 28, 3);
//     $deletecheck=checkprivilege($menuprivilegearray, 28, 4);
// }
// else if($controllermenu=='Assetdestroy'){
//     $addcheck=checkprivilege($menuprivilegearray, 29, 1);
//     $editcheck=checkprivilege($menuprivilegearray, 29, 2);
//     $statuscheck=checkprivilege($menuprivilegearray, 29, 3);
//     $deletecheck=checkprivilege($menuprivilegearray, 29, 4);
// }
// else if($controllermenu=='Upgradedipreciation'){
//     $addcheck=checkprivilege($menuprivilegearray, 30, 1);
//     $editcheck=checkprivilege($menuprivilegearray, 30, 2);
//     $statuscheck=checkprivilege($menuprivilegearray, 30, 3);
//     $deletecheck=checkprivilege($menuprivilegearray, 30, 4);
// }
// else if($controllermenu=='Assetsell'){
//     $addcheck=checkprivilege($menuprivilegearray, 31, 1);
//     $editcheck=checkprivilege($menuprivilegearray, 31, 2);
//     $statuscheck=checkprivilege($menuprivilegearray, 31, 3);
//     $deletecheck=checkprivilege($menuprivilegearray, 31, 4);
// }
// else if($controllermenu=='Assetsellreport'){
//     $addcheck=checkprivilege($menuprivilegearray, 32, 1);
//     $editcheck=checkprivilege($menuprivilegearray, 32, 2);
//     $statuscheck=checkprivilege($menuprivilegearray, 32, 3);
//     $deletecheck=checkprivilege($menuprivilegearray, 32, 4);
// }
else if($controllermenu=='Accountnestcategory'){
    $addcheck=checkprivilege($menuprivilegearray, 95, 1);
    $editcheck=checkprivilege($menuprivilegearray, 95, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 95, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 95, 4);
}
// else if($controllermenu=='Assetdepreciation'){
//     $addcheck=checkprivilege($menuprivilegearray, 34, 1);
//     $editcheck=checkprivilege($menuprivilegearray, 34, 2);
//     $statuscheck=checkprivilege($menuprivilegearray, 34, 3);
//     $deletecheck=checkprivilege($menuprivilegearray, 34, 4);
// }
else if($controllermenu=='Paymentcreate'){
    $addcheck=checkprivilege($menuprivilegearray, 97, 1);
    $editcheck=checkprivilege($menuprivilegearray, 97, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 97, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 97, 4);
}
else if($controllermenu=='Paymentsettle'){
    $addcheck=checkprivilege($menuprivilegearray, 98, 1);
    $editcheck=checkprivilege($menuprivilegearray, 98, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 98, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 98, 4);
}
else if($functionmenu=='periodic_pnl'){
    $addcheck=checkprivilege($menuprivilegearray, 99, 1);
    $editcheck=checkprivilege($menuprivilegearray, 99, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 99, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 99, 4);
}
else if($functionmenu=='periodic_balancesheet'){
    $addcheck=checkprivilege($menuprivilegearray, 100, 1);
    $editcheck=checkprivilege($menuprivilegearray, 100, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 100, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 100, 4);
}
else if($functionmenu=='trial_balance'){
    $addcheck=checkprivilege($menuprivilegearray, 101, 1);
    $editcheck=checkprivilege($menuprivilegearray, 101, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 101, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 101, 4);
}
else if($functionmenu=='period_trial_balance'){
    $addcheck=checkprivilege($menuprivilegearray, 102, 1);
    $editcheck=checkprivilege($menuprivilegearray, 102, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 102, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 102, 4);
}
else if($functionmenu=='DebtorReport'){
    $addcheck=checkprivilege($menuprivilegearray, 103, 1);
    $editcheck=checkprivilege($menuprivilegearray, 103, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 103, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 103, 4);
}
else if($functionmenu=='CreditorReport'){
    $addcheck=checkprivilege($menuprivilegearray, 104, 1);
    $editcheck=checkprivilege($menuprivilegearray, 104, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 104, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 104, 4);
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
            <?php if(menucheck($menuprivilegearray, 66)==1 | menucheck($menuprivilegearray, 67)==1 | menucheck($menuprivilegearray, 68)==1 | menucheck($menuprivilegearray, 95)==1 | menucheck($menuprivilegearray, 70)==1 | menucheck($menuprivilegearray, 71)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseMaster" aria-expanded="false" aria-controls="collapseMaster">
                <div class="nav-link-icon"><i class="fas fa-list"></i></div>
                Master Data
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Accounttype" | $controllermenu=="Accountcategory" | $controllermenu=="Accountsubcategory" | $controllermenu=="Accountingperiod" | $controllermenu=="Currentperiod" | $controllermenu=="Accountnestcategory"){echo 'show';} ?>" id="collapseMaster" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 66)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Accounttype'; ?>">Account Type</a>
                    <?php } if(menucheck($menuprivilegearray, 67)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Accountcategory'; ?>">Account Prime Category</a>
                    <?php } if(menucheck($menuprivilegearray, 68)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Accountsubcategory'; ?>">Account Category</a>
                    <?php } if(menucheck($menuprivilegearray, 95)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Accountnestcategory'; ?>">Account Sub Category</a>
                    <?php } if(menucheck($menuprivilegearray, 70)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Accountingperiod'; ?>">Accounting Period</a>
                    <?php } if(menucheck($menuprivilegearray, 71)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Currentperiod'; ?>">Current Period</a>                    
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 69)==1 | menucheck($menuprivilegearray, 73)==1 | menucheck($menuprivilegearray, 72)==1 | menucheck($menuprivilegearray, 80)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsechartofaccount" aria-expanded="false" aria-controls="collapsechartofaccount">
                <div class="nav-link-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                Chart Of Account Info
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Chartofaccount" | $controllermenu=="Chartofaccountdetail" | $controllermenu=="Accountallocation" | $controllermenu=="Openbalance"){echo 'show';} ?>" id="collapsechartofaccount" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 69)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Chartofaccount'; ?>">Chart Of Account</a>
                    <?php } if(menucheck($menuprivilegearray, 73)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Chartofaccountdetail'; ?>">Detail Account</a>
                    <?php } if(menucheck($menuprivilegearray, 72)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Accountallocation'; ?>">Account Allocation</a>
                    <?php } if(menucheck($menuprivilegearray, 80)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Openbalance'; ?>">Opening Balance</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 75)==1 | menucheck($menuprivilegearray, 81)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsesegregation" aria-expanded="false" aria-controls="collapsesegregation">
                <div class="nav-link-icon"><i class="fas fa-tasks"></i></div>
                Receivable
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Receiptsegregation" | $controllermenu=="Receivablesettle"){echo 'show';} ?>" id="collapsesegregation" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 75)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Receiptsegregation'; ?>">Receivable Segregation</a>
                    <?php } if(menucheck($menuprivilegearray, 81)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Receivablesettle'; ?>">Receivable Settle</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 97)==1 | menucheck($menuprivilegearray, 74)==1 | menucheck($menuprivilegearray, 98)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsePayments" aria-expanded="false" aria-controls="collapsePayments">
                <div class="nav-link-icon"><i class="fas fa-wallet"></i></div>
                Payments
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Paymentcreate" | $controllermenu=="Payablesegregation" | $controllermenu=="Paymentsettle"){echo 'show';} ?>" id="collapsePayments" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 97)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Paymentcreate'; ?>">Payment Create</a>
                    <?php } if(menucheck($menuprivilegearray, 74)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Payablesegregation'; ?>">Payment Segregation</a>
                    <?php } if(menucheck($menuprivilegearray, 98)==1){ ?>
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
            <?php } if(menucheck($menuprivilegearray, 82)==1 | menucheck($menuprivilegearray, 83)==1){ ?> 
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsejournalentry" aria-expanded="false" aria-controls="collapsejournalentry">
                <div class="nav-link-icon"><i class="fas fa-exchange-alt"></i></div>
                Journal Entry
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Journalentry" | $controllermenu=="Journalentrylist"){echo 'show';} ?>" id="collapsejournalentry" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 82)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Journalentry'; ?>">Journal Entry</a>
                    <?php } if(menucheck($menuprivilegearray, 83)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Journalentrylist'; ?>">Journal Entry List</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 79)==1 | menucheck($menuprivilegearray, 78)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsepettycash" aria-expanded="false" aria-controls="collapsepettycash">
                <div class="nav-link-icon"><i class="fas fa-coins"></i></div>
                Petty Cash
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Pettycashreimburse" | $controllermenu=="Pettycashexpense"){echo 'show';} ?>" id="collapsepettycash" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 79)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Pettycashexpense'; ?>">Petty Cash Expenses</a>
                    <?php } if(menucheck($menuprivilegearray, 78)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Pettycashreimburse'; ?>">Petty Cash Reimburse</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 76)==1){ ?> 
            <a class="nav-link p-0 px-3 py-2" href="<?php echo base_url().'Payreceivepost'; ?>">
                <div class="nav-link-icon"><i class="fas fa-exchange-alt"></i></div>
                Post All Data
            </a>
            <?php } if(menucheck($menuprivilegearray, 77)==1 | menucheck($menuprivilegearray, 84)==1 | menucheck($menuprivilegearray, 85)==1){ ?> 
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsechequeinfo" aria-expanded="false" aria-controls="collapsechequeinfo">
                <div class="nav-link-icon"><i class="fas fa-money-check-alt"></i></div>
                Cheque Info
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Chequeinfo" | $controllermenu=="Receivedcheque" | $controllermenu=="Issuecheque"){echo 'show';} ?>" id="collapsechequeinfo" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 77)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Chequeinfo'; ?>">Cheque Information</a>    
                    <?php } if(menucheck($menuprivilegearray, 84)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Receivedcheque'; ?>">Received Cheque</a>    
                    <?php } if(menucheck($menuprivilegearray, 85)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Issuecheque'; ?>">Issue Cheque</a>    
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 99)==1 | menucheck($menuprivilegearray, 100)==1 | menucheck($menuprivilegearray, 101)==1 | menucheck($menuprivilegearray, 102)==1 | menucheck($menuprivilegearray, 103)==1 | menucheck($menuprivilegearray, 104)==1){ ?> 
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseReport" aria-expanded="false" aria-controls="collapseReport">
                <div class="nav-link-icon"><i class="far fa-file-pdf"></i></div>
                Reports
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($functionmenu=="periodic_pnl" | $functionmenu=="periodic_balancesheet" | $functionmenu=="ledger_folio" | $functionmenu=="trial_balance" | $functionmenu=="DebtorReport" | $functionmenu=="CreditorReport"){echo 'show';} ?>" id="collapseReport" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 99)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ReportModule/periodic_pnl'; ?>">Profit & Lost</a> 
                    <?php } if(menucheck($menuprivilegearray, 100)==1){ ?>                   
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ReportModule/periodic_balancesheet'; ?>">Balance sheet</a>
                    <?php } if(menucheck($menuprivilegearray, 101)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ReportModule/ledger_folio'; ?>">Ledger Folio</a>
                    <?php } if(menucheck($menuprivilegearray, 102)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ReportModule/trial_balance'; ?>">Trial Balance</a>
                    <?php } if(menucheck($menuprivilegearray, 103)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ReportModule/DebtorReport'; ?>">Debtor Report</a>
                    <?php } if(menucheck($menuprivilegearray, 104)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ReportModule/CreditorReport'; ?>">Creditor Report</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 3)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseUser" aria-expanded="false" aria-controls="collapseUser">
                <div class="nav-link-icon"><i class="fas fa-user"></i></div>
                User Account
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($functionmenu=="Userprivilege"){echo 'show';} ?>" id="collapseUser" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 3)==1){ ?>
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