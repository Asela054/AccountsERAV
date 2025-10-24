<?php 
$controllermenu=$this->router->fetch_class();
$functionmenu=$this->router->fetch_method();

$menuprivilegearray=$menuaccess;

if($functionmenu=='Userprivilege'){
    $addcheck=checkprivilege($menuprivilegearray, 3, 1);
    $editcheck=checkprivilege($menuprivilegearray, 3, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 3, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 3, 4);
}
else if($controllermenu=='Accounttype'){
    $addcheck=checkprivilege($menuprivilegearray, 107, 1);
    $editcheck=checkprivilege($menuprivilegearray, 107, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 107, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 107, 4);
}
else if($controllermenu=='Accountcategory'){
    $addcheck=checkprivilege($menuprivilegearray, 108, 1);
    $editcheck=checkprivilege($menuprivilegearray, 108, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 108, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 108, 4);
}
else if($controllermenu=='Accountsubcategory'){
    $addcheck=checkprivilege($menuprivilegearray, 109, 1);
    $editcheck=checkprivilege($menuprivilegearray, 109, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 109, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 109, 4);
}
else if($controllermenu=='Chartofaccount'){
    $addcheck=checkprivilege($menuprivilegearray, 110, 1);
    $editcheck=checkprivilege($menuprivilegearray, 110, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 110, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 110, 4);
}
else if($controllermenu=='Accountingperiod'){
    $addcheck=checkprivilege($menuprivilegearray, 111, 1);
    $editcheck=checkprivilege($menuprivilegearray, 111, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 111, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 111, 4);
}
else if($controllermenu=='Currentperiod'){
    $addcheck=checkprivilege($menuprivilegearray, 112, 1);
    $editcheck=checkprivilege($menuprivilegearray, 112, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 112, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 112, 4);
}
else if($controllermenu=='Accountallocation'){
    $addcheck=checkprivilege($menuprivilegearray, 113, 1);
    $editcheck=checkprivilege($menuprivilegearray, 113, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 113, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 113, 4);
}
else if($controllermenu=='Chartofaccountdetail'){
    $addcheck=checkprivilege($menuprivilegearray, 114, 1);
    $editcheck=checkprivilege($menuprivilegearray, 114, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 114, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 114, 4);
}
else if($controllermenu=='Payablesegregation'){
    $addcheck=checkprivilege($menuprivilegearray, 115, 1);
    $editcheck=checkprivilege($menuprivilegearray, 115, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 115, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 115, 4);
}
else if($controllermenu=='Receiptsegregation'){
    $addcheck=checkprivilege($menuprivilegearray, 116, 1);
    $editcheck=checkprivilege($menuprivilegearray, 116, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 116, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 116, 4);
}
else if($controllermenu=='Payreceivepost'){
    $addcheck=checkprivilege($menuprivilegearray, 117, 1);
    $editcheck=checkprivilege($menuprivilegearray, 117, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 117, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 117, 4);
}
else if($controllermenu=='Chequeinfo'){
    $addcheck=checkprivilege($menuprivilegearray, 118, 1);
    $editcheck=checkprivilege($menuprivilegearray, 118, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 118, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 118, 4);
}
else if($controllermenu=='Pettycashreimburse'){
    $addcheck=checkprivilege($menuprivilegearray, 119, 1);
    $editcheck=checkprivilege($menuprivilegearray, 119, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 119, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 119, 4);
}
else if($controllermenu=='Pettycashexpense'){
    $addcheck=checkprivilege($menuprivilegearray, 120, 1);
    $editcheck=checkprivilege($menuprivilegearray, 120, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 120, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 120, 4);
}
else if($controllermenu=='Openbalance'){
    $addcheck=checkprivilege($menuprivilegearray, 121, 1);
    $editcheck=checkprivilege($menuprivilegearray, 121, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 121, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 121, 4);
}
else if($controllermenu=='Receivablesettle'){
    $addcheck=checkprivilege($menuprivilegearray, 122, 1);
    $editcheck=checkprivilege($menuprivilegearray, 122, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 122, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 122, 4);
}
else if($controllermenu=='Journalentry'){
    $addcheck=checkprivilege($menuprivilegearray, 123, 1);
    $editcheck=checkprivilege($menuprivilegearray, 123, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 123, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 123, 4);
}
else if($controllermenu=='Journalentrylist'){
    $addcheck=checkprivilege($menuprivilegearray, 124, 1);
    $editcheck=checkprivilege($menuprivilegearray, 124, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 124, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 124, 4);
}
else if($controllermenu=='Receivedcheque'){
    $addcheck=checkprivilege($menuprivilegearray, 125, 1);
    $editcheck=checkprivilege($menuprivilegearray, 125, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 125, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 125, 4);
}
else if($controllermenu=='Issuecheque'){
    $addcheck=checkprivilege($menuprivilegearray, 126, 1);
    $editcheck=checkprivilege($menuprivilegearray, 126, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 126, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 126, 4);
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
    $addcheck=checkprivilege($menuprivilegearray, 133, 1);
    $editcheck=checkprivilege($menuprivilegearray, 133, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 133, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 133, 4);
}
// else if($controllermenu=='Assetdepreciation'){
//     $addcheck=checkprivilege($menuprivilegearray, 34, 1);
//     $editcheck=checkprivilege($menuprivilegearray, 34, 2);
//     $statuscheck=checkprivilege($menuprivilegearray, 34, 3);
//     $deletecheck=checkprivilege($menuprivilegearray, 34, 4);
// }
else if($controllermenu=='Paymentcreate'){
    $addcheck=checkprivilege($menuprivilegearray, 158, 1);
    $editcheck=checkprivilege($menuprivilegearray, 158, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 158, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 158, 4);
}
else if($controllermenu=='Paymentsettle'){
    $addcheck=checkprivilege($menuprivilegearray, 159, 1);
    $editcheck=checkprivilege($menuprivilegearray, 159, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 159, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 159, 4);
}
else if($functionmenu=='periodic_pnl'){
    $addcheck=checkprivilege($menuprivilegearray, 160, 1);
    $editcheck=checkprivilege($menuprivilegearray, 160, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 160, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 160, 4);
}
else if($functionmenu=='periodic_balancesheet'){
    $addcheck=checkprivilege($menuprivilegearray, 161, 1);
    $editcheck=checkprivilege($menuprivilegearray, 161, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 161, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 161, 4);
}
else if($functionmenu=='trial_balance'){
    $addcheck=checkprivilege($menuprivilegearray, 162, 1);
    $editcheck=checkprivilege($menuprivilegearray, 162, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 162, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 162, 4);
}
else if($functionmenu=='period_trial_balance'){
    $addcheck=checkprivilege($menuprivilegearray, 163, 1);
    $editcheck=checkprivilege($menuprivilegearray, 163, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 163, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 163, 4);
}
else if($functionmenu=='DebtorReport'){
    $addcheck=checkprivilege($menuprivilegearray, 164, 1);
    $editcheck=checkprivilege($menuprivilegearray, 164, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 164, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 164, 4);
}
else if($functionmenu=='CreditorReport'){
    $addcheck=checkprivilege($menuprivilegearray, 165, 1);
    $editcheck=checkprivilege($menuprivilegearray, 165, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 165, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 165, 4);
}
else if($controllermenu=='Receivablecreate'){
    $addcheck=checkprivilege($menuprivilegearray, 166, 1);
    $editcheck=checkprivilege($menuprivilegearray, 166, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 166, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 166, 4);
}
else if($controllermenu=='BatchCategory'){
    $addcheck=checkprivilege($menuprivilegearray, 169, 1);
    $editcheck=checkprivilege($menuprivilegearray, 169, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 169, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 169, 4);
}
else if($controllermenu=='BatchTransactionType'){
    $addcheck=checkprivilege($menuprivilegearray, 170, 1);
    $editcheck=checkprivilege($menuprivilegearray, 170, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 170, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 170, 4);
}
else if($controllermenu=='BatchTransaction'){
    $addcheck=checkprivilege($menuprivilegearray, 171, 1);
    $editcheck=checkprivilege($menuprivilegearray, 171, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 171, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 171, 4);
    $approvecheck=checkprivilege($menuprivilegearray, 171, 5);
}
else if($controllermenu=='Expencereport'){
    $addcheck=checkprivilege($menuprivilegearray, 174, 1);
    $editcheck=checkprivilege($menuprivilegearray, 174, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 174, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 174, 4);
    $approvecheck=checkprivilege($menuprivilegearray, 174, 5);
}
else if($controllermenu=='Pettycashreport'){
    $addcheck=checkprivilege($menuprivilegearray, 175, 1);
    $editcheck=checkprivilege($menuprivilegearray, 175, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 175, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 175, 4);
    $approvecheck=checkprivilege($menuprivilegearray, 175, 5);
}
else if($controllermenu=='PettyCashSummeryReport'){
    $addcheck=checkprivilege($menuprivilegearray, 177, 1);
    $editcheck=checkprivilege($menuprivilegearray, 177, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 177, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 177, 4);
    $approvecheck=checkprivilege($menuprivilegearray, 177, 5);
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
            else if($type==5){
                return $array->approvestatus;
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
            <?php if(menucheck($menuprivilegearray, 107)==1 | menucheck($menuprivilegearray, 108)==1 | menucheck($menuprivilegearray, 109)==1 | menucheck($menuprivilegearray, 133)==1 | menucheck($menuprivilegearray, 111)==1 | menucheck($menuprivilegearray, 112)==1 | menucheck($menuprivilegearray, 169)==1 | menucheck($menuprivilegearray, 170)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseMaster" aria-expanded="false" aria-controls="collapseMaster">
                <div class="nav-link-icon"><i class="fas fa-list"></i></div>
                Master Data
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Accounttype" | $controllermenu=="Accountcategory" | $controllermenu=="Accountsubcategory" | $controllermenu=="Accountingperiod" | $controllermenu=="Currentperiod" | $controllermenu=="Accountnestcategory" | $controllermenu=="BatchCategory" | $controllermenu=="BatchTransactionType"){echo 'show';} ?>" id="collapseMaster" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 107)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Accounttype'; ?>">Account Type</a>
                    <?php } if(menucheck($menuprivilegearray, 108)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Accountcategory'; ?>">Account Prime Category</a>
                    <?php } if(menucheck($menuprivilegearray, 109)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Accountsubcategory'; ?>">Account Category</a>
                    <?php } if(menucheck($menuprivilegearray, 133)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Accountnestcategory'; ?>">Account Sub Category</a>
                    <?php } if(menucheck($menuprivilegearray, 169)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'BatchCategory'; ?>">Batch Category</a>
                    <?php } if(menucheck($menuprivilegearray, 170)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'BatchTransactionType'; ?>">Batch Transaction Type</a>
                    <?php } if(menucheck($menuprivilegearray, 111)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Accountingperiod'; ?>">Accounting Period</a>
                    <?php } if(menucheck($menuprivilegearray, 112)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Currentperiod'; ?>">Current Period</a>                    
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 110)==1 | menucheck($menuprivilegearray, 114)==1 | menucheck($menuprivilegearray, 113)==1 | menucheck($menuprivilegearray, 121)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsechartofaccount" aria-expanded="false" aria-controls="collapsechartofaccount">
                <div class="nav-link-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                Chart Of Account Info
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Chartofaccount" | $controllermenu=="Chartofaccountdetail" | $controllermenu=="Accountallocation" | $controllermenu=="Openbalance"){echo 'show';} ?>" id="collapsechartofaccount" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 110)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Chartofaccount'; ?>">Chart Of Account</a>
                    <?php } if(menucheck($menuprivilegearray, 114)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Chartofaccountdetail'; ?>">Detail Account</a>
                    <?php } if(menucheck($menuprivilegearray, 113)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Accountallocation'; ?>">Account Allocation</a>
                    <?php } if(menucheck($menuprivilegearray, 121)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Openbalance'; ?>">Opening Balance</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 116)==1 | menucheck($menuprivilegearray, 122)==1 | menucheck($menuprivilegearray, 166)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsesegregation" aria-expanded="false" aria-controls="collapsesegregation">
                <div class="nav-link-icon"><i class="fas fa-tasks"></i></div>
                Receivable
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Receiptsegregation" | $controllermenu=="Receivablesettle" | $controllermenu=="Receivablecreate"){echo 'show';} ?>" id="collapsesegregation" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 166)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Receivablecreate'; ?>">Receivable Create</a>
                    <?php } if(menucheck($menuprivilegearray, 116)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Receiptsegregation'; ?>">Receivable Segregation</a>
                    <?php } if(menucheck($menuprivilegearray, 122)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Receivablesettle'; ?>">Receivable Settle</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 158)==1 | menucheck($menuprivilegearray, 115)==1 | menucheck($menuprivilegearray, 159)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsePayments" aria-expanded="false" aria-controls="collapsePayments">
                <div class="nav-link-icon"><i class="fas fa-wallet"></i></div>
                Payments
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Paymentcreate" | $controllermenu=="Payablesegregation" | $controllermenu=="Paymentsettle"){echo 'show';} ?>" id="collapsePayments" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 158)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Paymentcreate'; ?>">Payment Create</a>
                    <?php } if(menucheck($menuprivilegearray, 115)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Payablesegregation'; ?>">Payment Segregation</a>
                    <?php } if(menucheck($menuprivilegearray, 159)==1){ ?>
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
            <?php } if(menucheck($menuprivilegearray, 123)==1 | menucheck($menuprivilegearray, 124)==1){ ?> 
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsejournalentry" aria-expanded="false" aria-controls="collapsejournalentry">
                <div class="nav-link-icon"><i class="fas fa-exchange-alt"></i></div>
                Journal Entry
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Journalentry" | $controllermenu=="Journalentrylist"){echo 'show';} ?>" id="collapsejournalentry" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 123)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Journalentry'; ?>">Journal Entry</a>
                    <?php } if(menucheck($menuprivilegearray, 124)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Journalentrylist'; ?>">Journal Entry List</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 171)==1){ ?> 
            <a class="nav-link p-0 px-3 py-2" href="<?php echo base_url().'BatchTransaction'; ?>">
                <div class="nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                Batch Transaction
            </a>
            <?php } if(menucheck($menuprivilegearray, 120)==1 | menucheck($menuprivilegearray, 119)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsepettycash" aria-expanded="false" aria-controls="collapsepettycash">
                <div class="nav-link-icon"><i class="fas fa-coins"></i></div>
                Petty Cash
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Pettycashreimburse" | $controllermenu=="Pettycashexpense"){echo 'show';} ?>" id="collapsepettycash" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 120)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Pettycashexpense'; ?>">Petty Cash Expenses</a>
                    <?php } if(menucheck($menuprivilegearray, 119)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Pettycashreimburse'; ?>">Petty Cash Reimburse</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 117)==1){ ?> 
            <a class="nav-link p-0 px-3 py-2" href="<?php echo base_url().'Payreceivepost'; ?>">
                <div class="nav-link-icon"><i class="fas fa-exchange-alt"></i></div>
                Post All Data
            </a>
            <?php } if(menucheck($menuprivilegearray, 118)==1 | menucheck($menuprivilegearray, 125)==1 | menucheck($menuprivilegearray, 126)==1){ ?> 
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsechequeinfo" aria-expanded="false" aria-controls="collapsechequeinfo">
                <div class="nav-link-icon"><i class="fas fa-money-check-alt"></i></div>
                Cheque Info
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Chequeinfo" | $controllermenu=="Receivedcheque" | $controllermenu=="Issuecheque"){echo 'show';} ?>" id="collapsechequeinfo" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 118)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Chequeinfo'; ?>">Cheque Information</a>    
                    <?php } if(menucheck($menuprivilegearray, 125)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Receivedcheque'; ?>">Received Cheque</a>    
                    <?php } if(menucheck($menuprivilegearray, 126)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Issuecheque'; ?>">Issue Cheque</a>    
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 160)==1 | menucheck($menuprivilegearray, 161)==1 | menucheck($menuprivilegearray, 162)==1 | menucheck($menuprivilegearray, 163)==1 | menucheck($menuprivilegearray, 164)==1 | menucheck($menuprivilegearray, 165)==1 | menucheck($menuprivilegearray, 174)==1 | menucheck($menuprivilegearray, 175)==1 | menucheck($menuprivilegearray, 177)==1){ ?> 
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseReport" aria-expanded="false" aria-controls="collapseReport">
                <div class="nav-link-icon"><i class="far fa-file-pdf"></i></div>
                Reports
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($functionmenu=="periodic_pnl" | $functionmenu=="periodic_balancesheet" | $functionmenu=="ledger_folio" | $functionmenu=="trial_balance" | $functionmenu=="DebtorReport" | $functionmenu=="CreditorReport" | $controllermenu=="Expencereport" | $controllermenu=="Pettycashreport" | $controllermenu=="PettyCashSummeryReport"){echo 'show';} ?>" id="collapseReport" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 160)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ReportModule/periodic_pnl'; ?>">Profit & Lost</a> 
                    <?php } if(menucheck($menuprivilegearray, 161)==1){ ?>                   
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ReportModule/periodic_balancesheet'; ?>">Balance sheet</a>
                    <?php } if(menucheck($menuprivilegearray, 162)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ReportModule/ledger_folio'; ?>">Ledger Folio</a>
                    <?php } if(menucheck($menuprivilegearray, 163)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ReportModule/trial_balance'; ?>">Trial Balance</a>
                    <?php } if(menucheck($menuprivilegearray, 164)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ReportModule/DebtorReport'; ?>">Debtor Report</a>
                    <?php } if(menucheck($menuprivilegearray, 165)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ReportModule/CreditorReport'; ?>">Creditor Report</a>
                    <?php } if(menucheck($menuprivilegearray, 174)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Expencereport'; ?>">Expence Report</a>
                    <?php } if(menucheck($menuprivilegearray, 175)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Pettycashreport'; ?>">Petty Cash Report</a>
                    <?php } if(menucheck($menuprivilegearray, 177)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'PettyCashSummeryReport'; ?>">Petty Cash Summery Report</a>
                    <?php } ?>
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