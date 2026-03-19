<?php 
$controllermenu=$this->router->fetch_class();
$functionmenu=uri_string();
$functionmenu2=$this->router->fetch_method();

$menuprivilegearray = $menuaccess;
$permissionallowed = array();
$addcheck = 0;
$editcheck = 0;
$statuscheck = 0;
$deletecheck = 0;
$approvecheck = 0;
$checkstatus = 0;

foreach($menuprivilegearray as $row){
    if($row->module==$functionmenu2){
        if($row->permission_type==1){$addcheck=1;}
        if($row->permission_type==2){$editcheck=1;}
        if($row->permission_type==3){$statuscheck=1;}
        if($row->permission_type==4){$deletecheck=1;}
        if($row->permission_type==5){$approvecheck=1;}
        if($row->permission_type==6){$checkstatus=1;}
    }

    if($row->module==$functionmenu){
        if($row->permission_type==1){$addcheck=1;}
        if($row->permission_type==2){$editcheck=1;}
        if($row->permission_type==3){$statuscheck=1;}
        if($row->permission_type==4){$deletecheck=1;}
        if($row->permission_type==5){$approvecheck=1;}
        if($row->permission_type==6){$checkstatus=1;}
    }
    
    array_push($permissionallowed, $row->module);
}

$permissionallowed = array_unique($permissionallowed);

// Rest of your permission check code remains the same...
// [Keep all your existing checkprivilege function and specific menu checks]

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
            
            <!-- Master Data -->
            <?php if(in_array("Accounttype", $permissionallowed) || in_array("Accountcategory", $permissionallowed) || in_array("Accountsubcategory", $permissionallowed) || in_array("Accountnestcategory", $permissionallowed) || in_array("Accountingperiod", $permissionallowed) || in_array("Currentperiod", $permissionallowed) || in_array("BatchCategory", $permissionallowed) || in_array("BatchTransactionType", $permissionallowed)){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseMaster" aria-expanded="false" aria-controls="collapseMaster">
                <div class="nav-link-icon"><i class="fas fa-list"></i></div>
                Master Data
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if(in_array($controllermenu, ["Accounttype", "Accountcategory", "Accountsubcategory", "Accountingperiod", "Currentperiod", "Accountnestcategory", "BatchCategory", "BatchTransactionType"])){echo 'show';} ?>" id="collapseMaster" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(in_array("Accounttype", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Accounttype'; ?>">Account Type</a>
                    <?php } if(in_array("Accountcategory", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Accountcategory'; ?>">Account Prime Category</a>
                    <?php } if(in_array("Accountsubcategory", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Accountsubcategory'; ?>">Account Category</a>
                    <?php } if(in_array("Accountnestcategory", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Accountnestcategory'; ?>">Account Sub Category</a>
                    <?php } if(in_array("BatchCategory", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'BatchCategory'; ?>">Batch Category</a>
                    <?php } if(in_array("BatchTransactionType", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'BatchTransactionType'; ?>">Batch Transaction Type</a>
                    <?php } if(in_array("Accountingperiod", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Accountingperiod'; ?>">Accounting Period</a>
                    <?php } if(in_array("Currentperiod", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Currentperiod'; ?>">Current Period</a>                    
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>
            
            <!-- Chart Of Account Info -->
            <?php if(in_array("Chartofaccount", $permissionallowed) || in_array("Chartofaccountdetail", $permissionallowed) || in_array("Accountallocation", $permissionallowed) || in_array("Openbalance", $permissionallowed)){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsechartofaccount" aria-expanded="false" aria-controls="collapsechartofaccount">
                <div class="nav-link-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                Chart Of Account Info
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if(in_array($controllermenu, ["Chartofaccount", "Chartofaccountdetail", "Accountallocation", "Openbalance"])){echo 'show';} ?>" id="collapsechartofaccount" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(in_array("Chartofaccount", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Chartofaccount'; ?>">Chart Of Account</a>
                    <?php } if(in_array("Chartofaccountdetail", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Chartofaccountdetail'; ?>">Detail Account</a>
                    <?php } if(in_array("Accountallocation", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Accountallocation'; ?>">Account Allocation</a>
                    <?php } if(in_array("Openbalance", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Openbalance'; ?>">Opening Balance</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>
            
            <!-- Receivable -->
            <?php if(in_array("Receivablecreate", $permissionallowed) || in_array("Receiptsegregation", $permissionallowed) || in_array("Receivablesettle", $permissionallowed)){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsesegregation" aria-expanded="false" aria-controls="collapsesegregation">
                <div class="nav-link-icon"><i class="fas fa-tasks"></i></div>
                Receivable
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if(in_array($controllermenu, ["Receivablecreate", "Receiptsegregation", "Receivablesettle"])){echo 'show';} ?>" id="collapsesegregation" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(in_array("Receivablecreate", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Receivablecreate'; ?>">Receivable Create</a>
                    <?php } if(in_array("Receiptsegregation", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Receiptsegregation'; ?>">Receivable Segregation</a>
                    <?php } if(in_array("Receivablesettle", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Receivablesettle'; ?>">Receivable Settle</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>
            
            <!-- Payments -->
            <?php if(in_array("Paymentcreate", $permissionallowed) || in_array("Payablesegregation", $permissionallowed) || in_array("Paymentsettle", $permissionallowed)){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsePayments" aria-expanded="false" aria-controls="collapsePayments">
                <div class="nav-link-icon"><i class="fas fa-wallet"></i></div>
                Payments
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if(in_array($controllermenu, ["Paymentcreate", "Payablesegregation", "Paymentsettle"])){echo 'show';} ?>" id="collapsePayments" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(in_array("Paymentcreate", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Paymentcreate'; ?>">Payment Create</a>
                    <?php } if(in_array("Payablesegregation", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Payablesegregation'; ?>">Payment Segregation</a>
                    <?php } if(in_array("Paymentsettle", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Paymentsettle'; ?>">Payment Settle</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>
            
            <!-- Journal Entry -->
            <?php if(in_array("Journalentry", $permissionallowed) || in_array("Journalentrylist", $permissionallowed)){ ?> 
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsejournalentry" aria-expanded="false" aria-controls="collapsejournalentry">
                <div class="nav-link-icon"><i class="fas fa-exchange-alt"></i></div>
                Journal Entry
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if(in_array($controllermenu, ["Journalentry", "Journalentrylist"])){echo 'show';} ?>" id="collapsejournalentry" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(in_array("Journalentry", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Journalentry'; ?>">Journal Entry</a>
                    <?php } if(in_array("Journalentrylist", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Journalentrylist'; ?>">Journal Entry List</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>
            
            <!-- Batch Transaction -->
            <?php if(in_array("BatchTransaction", $permissionallowed)){ ?> 
            <a class="nav-link p-0 px-3 py-2" href="<?php echo base_url().'BatchTransaction'; ?>">
                <div class="nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                Batch Transaction
            </a>
            <?php } ?>
            
            <!-- Petty Cash -->
            <?php if(in_array("Pettycashexpense", $permissionallowed) || in_array("Pettycashreimburse", $permissionallowed)){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsepettycash" aria-expanded="false" aria-controls="collapsepettycash">
                <div class="nav-link-icon"><i class="fas fa-coins"></i></div>
                Petty Cash
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if(in_array($controllermenu, ["Pettycashexpense", "Pettycashreimburse"])){echo 'show';} ?>" id="collapsepettycash" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(in_array("Pettycashexpense", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Pettycashexpense'; ?>">Petty Cash Expenses</a>
                    <?php } if(in_array("Pettycashreimburse", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Pettycashreimburse'; ?>">Petty Cash Reimburse</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>
            
            <!-- Post All Data -->
            <?php if(in_array("Payreceivepost", $permissionallowed)){ ?> 
            <a class="nav-link p-0 px-3 py-2" href="<?php echo base_url().'Payreceivepost'; ?>">
                <div class="nav-link-icon"><i class="fas fa-exchange-alt"></i></div>
                Post All Data
            </a>
            <?php } ?>
            
            <!-- Cheque Info -->
            <?php if(in_array("Chequeinfo", $permissionallowed) || in_array("Receivedcheque", $permissionallowed) || in_array("Issuecheque", $permissionallowed)){ ?> 
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsechequeinfo" aria-expanded="false" aria-controls="collapsechequeinfo">
                <div class="nav-link-icon"><i class="fas fa-money-check-alt"></i></div>
                Cheque Info
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if(in_array($controllermenu, ["Chequeinfo", "Receivedcheque", "Issuecheque"])){echo 'show';} ?>" id="collapsechequeinfo" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(in_array("Chequeinfo", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Chequeinfo'; ?>">Cheque Information</a>    
                    <?php } if(in_array("Receivedcheque", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Receivedcheque'; ?>">Received Cheque</a>    
                    <?php } if(in_array("Issuecheque", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Issuecheque'; ?>">Issue Cheque</a>    
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>
            
            <!-- Reports -->
            <?php if(in_array("periodic_pnl", $permissionallowed) || in_array("periodic_balancesheet", $permissionallowed) || in_array("trial_balance", $permissionallowed) || in_array("period_trial_balance", $permissionallowed) || in_array("Debtorreport", $permissionallowed) || in_array("Creditorreport", $permissionallowed) || in_array("Expencereport", $permissionallowed) || in_array("Pettycashreport", $permissionallowed) || in_array("PettyCashSummeryReport", $permissionallowed) || in_array("Audittrailreport", $permissionallowed)){ ?> 
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseReport" aria-expanded="false" aria-controls="collapseReport">
                <div class="nav-link-icon"><i class="far fa-file-pdf"></i></div>
                Reports
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if(in_array($functionmenu2, ["periodic_pnl", "periodic_balancesheet", "ledger_folio", "trial_balance", "period_trial_balance"]) || in_array($controllermenu, ["Debtorreport", "Creditorreport", "Expencereport", "Pettycashreport", "PettyCashSummeryReport", "Audittrailreport"])){echo 'show';} ?>" id="collapseReport" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(in_array("periodic_pnl", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ReportModule/periodic_pnl'; ?>">Profit & Lost</a> 
                    <?php } if(in_array("periodic_balancesheet", $permissionallowed)){ ?>                   
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ReportModule/periodic_balancesheet'; ?>">Balance sheet</a>
                    <?php } if(in_array("trial_balance", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ReportModule/ledger_folio'; ?>">Ledger Folio</a>
                    <?php } if(in_array("period_trial_balance", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'ReportModule/trial_balance'; ?>">Trial Balance</a>
                    <?php } if(in_array("Audittrailreport", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Audittrailreport'; ?>">Audit Trial</a>
                    <?php } if(in_array("Debtorreport", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Debtorreport'; ?>">Debtor Report</a>
                    <?php } if(in_array("Creditorreport", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Creditorreport'; ?>">Creditor Report</a>
                    <?php } if(in_array("Expencereport", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Expencereport'; ?>">Expence Report</a>
                    <?php } if(in_array("Pettycashreport", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Pettycashreport'; ?>">Petty Cash Report</a>
                    <?php } if(in_array("PettyCashSummeryReport", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'PettyCashSummeryReport'; ?>">Petty Cash Summery Report</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>
            
            <!-- Asset Master Data -->
            <?php if(in_array("Assettype", $permissionallowed) || in_array("Depreciationtype", $permissionallowed) || in_array("Depreciationcategory", $permissionallowed) || in_array("Depreciationmethod", $permissionallowed)){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseAssetMaster" aria-expanded="false" aria-controls="collapseAssetMaster">
                <div class="nav-link-icon"><i class="fas fa-wallet"></i></div>
                Asset Master Data
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if(in_array($controllermenu, ["Assettype", "Depreciationtype", "Depreciationcategory", "Depreciationmethod"])){echo 'show';} ?>" id="collapseAssetMaster" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(in_array("Assettype", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Assettype'; ?>">Asset Type</a>
                    <?php } if(in_array("Depreciationtype", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Depreciationtype'; ?>">Depreciation Type</a>
                    <?php } if(in_array("Depreciationcategory", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Depreciationcategory'; ?>">Depreciation Category</a>
                    <?php } if(in_array("Depreciationmethod", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Depreciationmethod'; ?>">Depreciation Method</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>
            
            <!-- Assets -->
            <?php if(in_array("Asset", $permissionallowed) || in_array("Assetdestroy", $permissionallowed) || in_array("Upgradedipreciation", $permissionallowed) || in_array("Assetsell", $permissionallowed) || in_array("Assetdepreciation", $permissionallowed)){ ?> 
            <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseDepreciation" aria-expanded="false" aria-controls="collapseDepreciation">
                <div class="nav-link-icon"><i class="fas fa-chart-line"></i></div>
                Assets
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if(in_array($controllermenu, ["Asset", "Assetdestroy", "Upgradedipreciation", "Assetsell", "Assetdepreciation"])){echo 'show';} ?>" id="collapseDepreciation" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(in_array("Asset", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Asset'; ?>">Assets</a>
                    <?php } if(in_array("Assetdestroy", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Assetdestroy'; ?>">Assets Destroy</a>
                    <?php } if(in_array("Upgradedipreciation", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Upgradedipreciation'; ?>">Assets Improvement</a>
                    <?php } if(in_array("Assetsell", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Assetsell'; ?>">Assets Sale</a>
                    <?php } if(in_array("Assetdepreciation", $permissionallowed)){ ?>
                    <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'Assetdepreciation'; ?>">Assets Depreciation</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>
            
            <!-- User Account Menu (already updated with your example) -->
			<?php if(in_array("Useraccount", $permissionallowed) || in_array("Usertype", $permissionallowed) || in_array("Userprivilege", $permissionallowed) || in_array("Userpermissions", $permissionallowed) || in_array("Userroles", $permissionallowed)){ ?>
			<a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse"
				data-target="#collapseUser" aria-expanded="false" aria-controls="collapseUser">
				<div class="nav-link-icon"><i class="fas fa-user"></i></div>
				User Account
				<div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
			</a>
			<div class="collapse <?php if(in_array($functionmenu2, ["Useraccount", "Usertype", "Userprivilege", "Userpermissions", "Userroles"])){echo 'show';} ?>"
				id="collapseUser" data-parent="#accordionSidenav">
				<nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
					<?php if(in_array("Useraccount", $permissionallowed)){ ?>
					<a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'User/Useraccount'; ?>">User
						Account</a>
					<?php } if(in_array("Usertype", $permissionallowed)){ ?>
					<a class="nav-link p-0 px-3 py-1 text-dark"
						href="<?php echo base_url().'User/Usertype'; ?>">Type</a>
					<?php } if(in_array("Userprivilege", $permissionallowed)){ ?>
					<a class="nav-link p-0 px-3 py-1 text-dark"
						href="<?php echo base_url().'User/Userprivilege'; ?>">Privilege</a>
					<?php } if(in_array("Userpermissions", $permissionallowed)){ ?>
					<a class="nav-link p-0 px-3 py-1 text-dark"
						href="<?php echo base_url().'User/Userpermissions'; ?>">User Permissions</a>
					<?php } if(in_array("Userroles", $permissionallowed)){ ?>
					<a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url().'User/Userroles'; ?>">User
						Roles</a>
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