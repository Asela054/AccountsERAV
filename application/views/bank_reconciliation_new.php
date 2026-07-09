<?php 
include "include/header.php";  
include "include/topnavbar.php"; 
?>

<style>
/* ── Variables ── */
:root {
    --bg:        #f0f2f5;
    --surface:   #ffffff;
    --surface2:  #f7f8fa;
    --border:    #e2e6ea;
    --primary:   #1a73e8;
    --primary-d: #1557b0;
    --success:   #1e8c45;
    --success-bg:#e8f5e9;
    --danger:    #d93025;
    --danger-bg: #fce8e6;
    --warning:   #f29900;
    --warning-bg:#fef7e0;
    --text:      #1f2937;
    --text-2:    #5f6b7a;
    --text-3:    #9aa3ad;
    --matched:   #e8f5e9;
    --unmatched: #fff8e1;
    --font:      'DM Sans', sans-serif;
    --font-mono: 'DM Mono', monospace;
    --radius:    8px;
    --shadow:    0 1px 4px rgba(0,0,0,.08), 0 4px 16px rgba(0,0,0,.06);
    --shadow-sm: 0 1px 3px rgba(0,0,0,.07);
}

@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap');

* { box-sizing: border-box; margin: 0; padding: 0; }

/* body { background: var(--bg); font-family: var(--font); color: var(--text); font-size: 14px; } */

/* ── Page header ── */
/* .page-header {
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    padding: 14px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: var(--shadow-sm);
}
.page-header h1 {
    font-size: 18px;
    font-weight: 600;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 10px;
}
.page-header h1 i { color: var(--primary); font-size: 20px; } */
.header-actions { display: flex; gap: 8px; align-items: center; }

/* ── Buttons ── */
.btn-rec {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 16px; border-radius: var(--radius);
    font-size: 13px; font-weight: 500; cursor: pointer;
    border: none; transition: all .15s ease;
    font-family: var(--font);
}
.btn-rec:disabled { opacity: .5; cursor: not-allowed; }
.btn-primary-rec  { background: var(--primary); color: #fff; }
.btn-primary-rec:hover:not(:disabled) { background: var(--primary-d); }
.btn-success-rec  { background: var(--success); color: #fff; }
.btn-success-rec:hover:not(:disabled) { background: #16703a; }
.btn-outline-rec  { background: transparent; color: var(--primary); border: 1px solid var(--primary); }
.btn-outline-rec:hover { background: #e8f0fe; }
.btn-danger-rec   { background: var(--danger); color: #fff; }
.btn-danger-rec:hover { background: #b5261d; }
.btn-ghost        { background: transparent; color: var(--text-2); border: 1px solid var(--border); }
.btn-ghost:hover  { background: var(--surface2); }

/* ── Main layout ── */
/* .rec-body { max-width: 1400px; margin: 0 auto; padding: 20px 24px; } */

/* ── Step wizard ── */
.step-bar {
    display: flex; gap: 0; margin-bottom: 20px;
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--radius); overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.step-item {
    flex: 1; padding: 12px 20px; display: flex; align-items: center; gap: 10px;
    cursor: pointer; border-right: 1px solid var(--border);
    transition: background .15s;
    position: relative;
}
.step-item:last-child { border-right: none; }
.step-item.active { background: #e8f0fe; }
.step-item.done   { background: var(--success-bg); }
.step-item.active .step-num  { background: var(--primary); color: #fff; }
.step-item.done .step-num    { background: var(--success); color: #fff; }
.step-num {
    width: 26px; height: 26px; border-radius: 50%;
    background: var(--border); color: var(--text-2);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 700; flex-shrink: 0;
}
.step-label { font-size: 13px; font-weight: 500; }
.step-sub   { font-size: 11px; color: var(--text-3); }

/* ── Card ── */
.rec-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    margin-bottom: 16px;
    overflow: hidden;
}
.rec-card-header {
    padding: 14px 20px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    background: var(--surface2);
}
.rec-card-header h3 { font-size: 14px; font-weight: 600; color: var(--text); display: flex; align-items: center; gap: 8px; }
.rec-card-body { padding: 20px; }

/* ── Form grid ── */
.form-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; }
.form-group label {
    display: block; font-size: 11px; font-weight: 600;
    color: var(--text-2); text-transform: uppercase;
    letter-spacing: .4px; margin-bottom: 5px;
}
.form-control-rec {
    width: 100%; padding: 8px 11px;
    border: 1px solid var(--border); border-radius: var(--radius);
    font-size: 13px; font-family: var(--font); color: var(--text);
    background: var(--surface);
    transition: border-color .15s, box-shadow .15s;
}
.form-control-rec:focus {
    outline: none; border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(26,115,232,.12);
}
.form-control-rec[readonly] { background: var(--surface2); color: var(--text-2); }
.form-control-rec.amount-field { font-family: var(--font-mono); text-align: right; }

/* ── Balance summary bar ── */
.balance-bar {
    display: grid; grid-template-columns: repeat(4, 1fr);
    gap: 1px; background: var(--border);
    border: 1px solid var(--border); border-radius: var(--radius);
    overflow: hidden; margin-bottom: 16px;
}
.balance-cell {
    background: var(--surface); padding: 14px 18px;
    display: flex; flex-direction: column; gap: 4px;
}
.balance-cell.highlight { background: #e8f0fe; }
.balance-cell.ok   { background: var(--success-bg); }
.balance-cell.warn { background: var(--warning-bg); }
.balance-cell.err  { background: var(--danger-bg);  }
.balance-label { font-size: 11px; font-weight: 600; color: var(--text-2); text-transform: uppercase; letter-spacing: .4px; }
.balance-val   { font-size: 18px; font-weight: 600; font-family: var(--font-mono); color: var(--text); }
.balance-val.cr { color: var(--success); }
.balance-val.dr { color: var(--danger); }
.balance-diff  { font-size: 11px; font-weight: 500; }
.balance-diff.zero   { color: var(--success); }
.balance-diff.nonzero{ color: var(--danger); }

/* ── Diff banner ── */
.diff-banner {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 18px; border-radius: var(--radius);
    margin-bottom: 16px; font-size: 13px; font-weight: 500;
    border: 1px solid;
}
.diff-banner.ok   { background: var(--success-bg); border-color: #a8d5b5; color: var(--success); }
.diff-banner.warn { background: var(--warning-bg); border-color: #f5c842; color: #7a5000; }
.diff-banner.err  { background: var(--danger-bg);  border-color: #f5a7a2; color: var(--danger);  }

/* ── Transaction table ── */
.rec-table-wrap { overflow-x: auto; }
.rec-table {
    width: 100%; border-collapse: collapse; font-size: 13px;
}
.rec-table th {
    background: var(--surface2); padding: 10px 14px;
    text-align: left; font-size: 11px; font-weight: 600;
    color: var(--text-2); text-transform: uppercase;
    letter-spacing: .4px; border-bottom: 2px solid var(--border);
    white-space: nowrap;
    position: sticky; top: 0;
}
.rec-table th.text-right, .rec-table td.text-right { text-align: right; }
.rec-table th.text-center, .rec-table td.text-center { text-align: center; }
.rec-table td {
    padding: 9px 14px; border-bottom: 1px solid var(--border);
    vertical-align: middle;
    transition: background .1s;
}
.rec-table tr:last-child td { border-bottom: none; }
.rec-table tr.matched td     { background: var(--matched); }
.rec-table tr.unmatched td   { background: var(--unmatched); }
.rec-table tr:hover td       { background: #f0f6ff; }
.rec-table tr.matched:hover td { background: #d7f0db; }

.amount-mono { font-family: var(--font-mono); font-size: 13px; }
.cr-amount   { color: var(--success); font-weight: 500; }
.dr-amount   { color: var(--danger);  font-weight: 500; }

/* ── Checkbox ── */
.chk-match {
    width: 17px; height: 17px; cursor: pointer;
    accent-color: var(--primary);
}

/* ── Period badge ── */
.period-badge {
    display: inline-flex; align-items: center;
    background: #e8f0fe; color: var(--primary);
    border-radius: 12px; padding: 2px 10px;
    font-size: 11px; font-weight: 600;
}

/* ── Status chip ── */
.chip {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 2px 9px; border-radius: 12px;
    font-size: 11px; font-weight: 600;
}
.chip-matched   { background: var(--success-bg); color: var(--success); }
.chip-unmatched { background: var(--warning-bg); color: var(--warning); }
.chip-revision  { background: #ede7f6; color: #6200ea; }

/* ── Filter tabs ── */
.filter-tabs {
    display: flex; gap: 4px; margin-bottom: 12px;
}
.filter-tab {
    padding: 6px 14px; border-radius: 20px; font-size: 12px;
    font-weight: 500; cursor: pointer; border: 1px solid var(--border);
    background: var(--surface); color: var(--text-2);
    transition: all .15s;
}
.filter-tab.active { background: var(--primary); color: #fff; border-color: var(--primary); }
.filter-tab .cnt   { background: rgba(0,0,0,.12); border-radius: 10px; padding: 0 6px; font-size: 10px; margin-left: 4px; }

/* ── Toast ── */
.toast-rec {
    position: fixed; bottom: 24px; right: 24px;
    padding: 12px 20px; border-radius: var(--radius);
    font-size: 13px; font-weight: 500;
    display: flex; align-items: center; gap: 10px;
    z-index: 9999; box-shadow: 0 4px 20px rgba(0,0,0,.2);
    transition: all .3s ease;
    transform: translateY(100px); opacity: 0;
}
.toast-rec.show { transform: translateY(0); opacity: 1; }
.toast-rec.success { background: var(--success); color: #fff; }
.toast-rec.error   { background: var(--danger);  color: #fff; }
.toast-rec.info    { background: var(--primary); color: #fff; }

/* ── Spinner ── */
.spinner { display: inline-block; width: 16px; height: 16px; border: 2px solid rgba(255,255,255,.4); border-top-color: #fff; border-radius: 50%; animation: spin .6s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }

/* ── Empty state ── */
.empty-state { text-align: center; padding: 40px 20px; color: var(--text-3); }
.empty-state i { font-size: 40px; margin-bottom: 10px; display: block; }
.empty-state p  { font-size: 13px; }

/* ── Loading overlay ── */
.table-loading { position: relative; }
.table-loading::after {
    content: ''; position: absolute; inset: 0;
    background: rgba(255,255,255,.7); display: flex;
    align-items: center; justify-content: center;
    border-radius: var(--radius);
}

/* ── Revision row ── */
.rec-table tr.revision-row td { background: #ede7f6; }
.rec-table tr.revision-row:hover td { background: #d1c4e9; }

/* ── Section label ── */
.section-divider {
    background: var(--surface2); padding: 7px 14px;
    font-size: 11px; font-weight: 700; color: var(--text-2);
    text-transform: uppercase; letter-spacing: .6px;
    border-bottom: 1px solid var(--border);
}

/* ── Responsive ── */
@media(max-width:768px){
    .balance-bar { grid-template-columns: repeat(2,1fr); }
    .form-grid   { grid-template-columns: 1fr 1fr; }
    .step-bar    { display: none; }
}
</style>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <!-- Page Header -->
            <div class="page-header page-header-light bg-white shadow">
                <div class="container-fluid">
                    <div class="page-header-content py-3 d-flex">
                        <h1 class="page-header-title font-weight-light mr-auto">
                            <div class="page-header-icon"><i class="fas fa-university"></i></div>
                            <span>Bank Reconciliation</span>
                        </h1>
                        <div class="header-actions">
                            <button class="btn-rec btn-ghost" id="btnOpenOngoing">
                                <i class="fas fa-folder-open"></i> Open Ongoing
                            </button>
                            <button class="btn-rec btn-ghost" id="btnOpenCompleted">
                                <i class="fas fa-history"></i> Completed
                            </button>
                            <button class="btn-rec btn-primary-rec" id="btnNewRec">
                                <i class="fas fa-plus"></i> New Reconciliation
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <!-- Step Bar -->
                                <div class="step-bar" id="stepBar">
                                    <div class="step-item active" data-step="1">
                                        <div class="step-num">1</div>
                                        <div><div class="step-label">Select Account</div><div class="step-sub">Choose bank &amp; period</div></div>
                                    </div>
                                    <div class="step-item" data-step="2">
                                        <div class="step-num">2</div>
                                        <div><div class="step-label">Enter Statement</div><div class="step-sub">Bank statement figures</div></div>
                                    </div>
                                    <div class="step-item" data-step="3">
                                        <div class="step-num">3</div>
                                        <div><div class="step-label">Match Transactions</div><div class="step-sub">Tick off cleared items</div></div>
                                    </div>
                                    <div class="step-item" data-step="4">
                                        <div class="step-num">4</div>
                                        <div><div class="step-label">Approve</div><div class="step-sub">Finalise reconciliation</div></div>
                                    </div>
                                </div>

                                <!-- Step 1: Account & Period Setup -->
                                <div id="panel_setup" class="rec-card">
                                    <div class="rec-card-header">
                                        <h3><i class="fas fa-cog" style="color:var(--primary)"></i> Reconciliation Setup</h3>
                                        <div style="display:flex;gap:8px;align-items:center;">
                                            <span id="lbl_rec_batchno" class="period-badge" style="display:none;"></span>
                                            <span id="lbl_approved_badge" class="chip" style="display:none;"></span>
                                        </div>
                                    </div>
                                    <div class="rec-card-body">
                                        <div class="form-grid">
                                            <div class="form-group">
                                                <label>Bank Account</label>
                                                <select class="form-control-rec" id="drp_bank_accounts">
                                                    <option value="-1">— Select Bank Account —</option>
                                                    <?php if(!empty($company_bank_account_list)): foreach($company_bank_account_list as $ba): ?>
                                                    <option value="<?php echo $ba->idtbl_account; ?>"><?php echo htmlspecialchars($ba->accountname); ?></option>
                                                    <?php endforeach; endif; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Financial Year</label>
                                                <select class="form-control-rec" id="drp_rec_year">
                                                    <option value="">— Select Year —</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Financial Month</label>
                                                <select class="form-control-rec" id="drp_rec_month">
                                                    <option value="">— Select Month —</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Reconciliation Date</label>
                                                <input type="date" class="form-control-rec" id="txt_bank_rec_date" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2: Statement Figures -->
                                <div id="panel_statement" class="rec-card" style="display:none;">
                                    <div class="rec-card-header">
                                        <h3><i class="fas fa-file-invoice-dollar" style="color:var(--primary)"></i> Bank Statement Figures</h3>
                                        <button class="btn-rec btn-primary-rec btn-sm" id="btnSaveHeader" style="padding:5px 14px;font-size:12px;">
                                            <i class="fas fa-save"></i> Save
                                        </button>
                                    </div>
                                    <div class="rec-card-body">
                                        <div class="form-grid">
                                            <div class="form-group">
                                                <label>Statement Opening Balance</label>
                                                <input type="number" step="0.01" class="form-control-rec amount-field stmt-input" id="txt_statement_open_bal" value="0.00" placeholder="0.00" />
                                            </div>
                                            <div class="form-group">
                                                <label>Total Credits (CR)</label>
                                                <input type="number" step="0.01" class="form-control-rec amount-field stmt-input" id="txt_statement_tot_cr" value="0.00" placeholder="0.00" />
                                            </div>
                                            <div class="form-group">
                                                <label>Total Debits (DR)</label>
                                                <input type="number" step="0.01" class="form-control-rec amount-field stmt-input" id="txt_statement_tot_dr" value="0.00" placeholder="0.00" />
                                            </div>
                                            <div class="form-group">
                                                <label>Statement Closing Balance</label>
                                                <input type="number" step="0.01" class="form-control-rec amount-field" id="txt_statement_closed_bal" value="0.00" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Balance Summary Bar -->
                                <div class="balance-bar" id="panel_balance" style="display:none;">
                                    <div class="balance-cell">
                                        <div class="balance-label">Statement Opening</div>
                                        <div class="balance-val" id="bal_stmt_open">0.00</div>
                                    </div>
                                    <div class="balance-cell">
                                        <div class="balance-label">Statement Closing</div>
                                        <div class="balance-val" id="bal_stmt_close">0.00</div>
                                    </div>
                                    <div class="balance-cell">
                                        <div class="balance-label">Account Cleared</div>
                                        <div class="balance-val" id="bal_acc_close">0.00</div>
                                        <div class="balance-diff" id="bal_acc_open_lbl" style="color:var(--text-3);font-size:11px;">Opening: 0.00</div>
                                    </div>
                                    <div class="balance-cell highlight" id="diff_cell">
                                        <div class="balance-label">Difference</div>
                                        <div class="balance-val" id="bal_diff">0.00</div>
                                        <div class="balance-diff" id="bal_diff_lbl">&nbsp;</div>
                                    </div>
                                </div>

                                <!-- Diff Banner -->
                                <div class="diff-banner warn" id="diff_banner" style="display:none;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span id="diff_banner_msg">Difference must be 0.00 to approve.</span>
                                </div>

                                <!-- Step 3: Adjustments -->
                                <div id="panel_adjustments" class="rec-card" style="display:none;">
                                    <div class="rec-card-header">
                                        <h3><i class="fas fa-plus-circle" style="color:#6200ea"></i> Bank Adjustments</h3>
                                        <button class="btn-rec btn-ghost" id="btnToggleAdjust" style="font-size:12px;padding:5px 12px;">
                                            <i class="fas fa-chevron-down"></i> Add Adjustment
                                        </button>
                                    </div>
                                    <div id="panel_adjust_form" style="display:none; padding:16px 20px; border-top:1px solid var(--border); background:var(--surface2);">
                                        <div class="form-grid">
                                            <div class="form-group">
                                                <label>Branch / Period</label>
                                                <select class="form-control-rec" id="drp_adj_period">
                                                    <option value="">— Select Branch —</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Account</label>
                                                <select class="form-control-rec" id="drp_adj_account" style="width: 100%;">
                                                    <option value="-1">— Select Account —</option>
                                                    <?php // if(!empty($main_accounts)): foreach($main_accounts as $ma): ?>
                                                    <!-- <option value="<?php // echo $ma->form_key; ?>"><?php // echo htmlspecialchars($ma->form_val); ?></option> -->
                                                    <?php // endforeach; endif; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Tra. Date</label>
                                                <input type="date" class="form-control-rec" id="txt_adj_date" />
                                            </div>
                                            <div class="form-group">
                                                <label>Type</label>
                                                <div style="display:flex;gap:12px;padding-top:8px;">
                                                    <label style="text-transform:none;font-size:13px;font-weight:500;display:flex;align-items:center;gap:6px;cursor:pointer;">
                                                        <input type="radio" name="adj_type" id="adj_cr" value="cr" checked /> CR (Credit)
                                                    </label>
                                                    <label style="text-transform:none;font-size:13px;font-weight:500;display:flex;align-items:center;gap:6px;cursor:pointer;">
                                                        <input type="radio" name="adj_type" id="adj_dr" value="dr" /> DR (Debit)
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Narration</label>
                                                <input type="text" class="form-control-rec" id="txt_adj_narration" placeholder="Description..." />
                                            </div>
                                            <div class="form-group">
                                                <label>Amount</label>
                                                <input type="number" step="0.01" class="form-control-rec amount-field" id="txt_adj_amount" placeholder="0.00" />
                                            </div>
                                            <div class="form-group" style="display:flex;align-items:flex-end;">
                                                <button class="btn-rec btn-primary-rec" id="btnAddAdjustment" style="width:100%;">
                                                    <i class="fas fa-plus"></i> Add
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 3: Transactions -->
                                <div id="panel_transactions" class="rec-card" style="display:none;">
                                    <div class="rec-card-header">
                                        <h3><i class="fas fa-list-alt" style="color:var(--primary)"></i> Transactions
                                            <span id="lbl_match_count" class="chip chip-matched" style="display:none;"></span>
                                        </h3>
                                        <div style="display:flex;gap:8px;align-items:center;">
                                            <div class="filter-tabs" style="margin-bottom:0;">
                                                <div class="filter-tab active" data-filter="all">All <span class="cnt" id="cnt_all">0</span></div>
                                                <div class="filter-tab" data-filter="matched">Matched <span class="cnt" id="cnt_matched">0</span></div>
                                                <div class="filter-tab" data-filter="unmatched">Unmatched <span class="cnt" id="cnt_unmatched">0</span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rec-card-body" style="padding:0;">
                                        <div class="rec-table-wrap" id="tbl_wrap">
                                            <table class="rec-table" id="rec_transactions_table">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" width="40">
                                                            <input type="checkbox" id="chk_all" class="chk-match" title="Select all" />
                                                        </th>
                                                        <th>Period</th>
                                                        <th>Date</th>
                                                        <th>Narration</th>
                                                        <th class="text-right">CR</th>
                                                        <th class="text-right">DR</th>
                                                        <th class="text-center" width="90">Status</th>
                                                        <th class="text-center" width="50">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbl_rec_body">
                                                    <tr><td colspan="8" class="empty-state">
                                                        <i class="fas fa-search"></i>
                                                        <p>Select a bank account to load transactions</p>
                                                    </td></tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr style="background:var(--surface2);font-weight:600;">
                                                        <td colspan="4" style="padding:10px 14px;font-size:12px;color:var(--text-2);">
                                                            TOTAL MATCHED
                                                        </td>
                                                        <td class="text-right" style="padding:10px 14px;">
                                                            <span class="amount-mono cr-amount" id="tbl_tot_cr">0.00</span>
                                                        </td>
                                                        <td class="text-right" style="padding:10px 14px;">
                                                            <span class="amount-mono dr-amount" id="tbl_tot_dr">0.00</span>
                                                        </td>
                                                        <td colspan="2"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <div class="empty-state" id="tbl_empty" style="display:none;">
                                                <i class="fas fa-check-circle" style="color:var(--success);"></i>
                                                <p>No transactions match this filter.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Approve Button -->
                                <div id="panel_approve" style="display:none; text-align:right; margin-bottom:30px;">
                                    <button class="btn-rec btn-success-rec" id="btnApprove" style="font-size:14px;padding:10px 28px;">
                                        <i class="fas fa-check-double"></i> Approve Reconciliation
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /rec-body -->
        </main>

        <!-- ═══════════════ MODALS ═══════════════ -->

        <!-- Ongoing Recs Modal -->
        <div class="modal fade" id="modalOngoing" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" style="border-radius:var(--radius);border:1px solid var(--border);">
                    <div class="modal-header" style="border-bottom:1px solid var(--border);padding:14px 20px;">
                        <h5 class="modal-title" style="font-size:15px;font-weight:600;">
                            <i class="fas fa-folder-open" style="color:var(--primary);margin-right:8px;"></i>Ongoing Reconciliations
                        </h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body" style="padding:0;">
                        <table class="rec-table" id="tbl_ongoing">
                            <thead>
                                <tr>
                                    <th>Bank Account</th>
                                    <th>Account No.</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbl_ongoing_body">
                                <tr><td colspan="3" class="empty-state"><i class="fas fa-spinner fa-spin"></i><p>Loading...</p></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Recs Modal -->
        <div class="modal fade" id="modalCompleted" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" style="border-radius:var(--radius);border:1px solid var(--border);">
                    <div class="modal-header" style="border-bottom:1px solid var(--border);padding:14px 20px;">
                        <h5 class="modal-title" style="font-size:15px;font-weight:600;">
                            <i class="fas fa-history" style="color:var(--primary);margin-right:8px;"></i>Completed Reconciliations
                        </h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body" style="padding:0;">
                        <table class="rec-table" id="tbl_completed">
                            <thead>
                                <tr>
                                    <th>Bank Account</th>
                                    <th>Batch No.</th>
                                    <th>Period</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbl_completed_body">
                                <tr><td colspan="4" class="empty-state"><i class="fas fa-spinner fa-spin"></i><p>Loading...</p></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast -->
        <div class="toast-rec" id="toast_rec">
            <i class="fas fa-check-circle"></i>
            <span id="toast_msg"></span>
        </div>

        <?php include "include/footerbar.php"; ?>
    </div>
</div>

<?php include "include/footerscripts.php"; ?>

<script>
// ═══════════════════════════════════════════
// FIXED JavaScript - bank_reconciliation_new.php
// වෙනස් කළ කොටස් පමණයි පෙන්වා ඇත
// ═══════════════════════════════════════════

$(document).ready(function(){

    var STATE = {
        rec_main_id      : '',
        bankacc_id       : '',
        rec_year         : '',
        rec_month        : '',
        rec_approved     : 0,
        acc_open_bal     : 0,
        transactions     : [],
        cr_running       : 0,
        dr_running       : 0,
        filter           : 'all',
        financial_years  : [],   // <-- NEW: year data store කරන්න
        financial_months : []    // <-- NEW: month data store කරන්න
    };

    // ── Toast ──────────────────────────────
    function showToast(msg, type){
        type = type || 'info';
        $('#toast_rec').removeClass('success error info').addClass(type).find('#toast_msg').text(msg);
        $('#toast_rec').addClass('show');
        setTimeout(function(){ $('#toast_rec').removeClass('show'); }, 3500);
    }

    // ── Step Bar ───────────────────────────
    function setStep(n){
        $('.step-item').each(function(){
            var s = parseInt($(this).data('step'));
            $(this).removeClass('active done');
            if(s < n)  $(this).addClass('done');
            if(s == n) $(this).addClass('active');
        });
    }

    // ── Number Format ──────────────────────
    function fnum(v, dp){
        var n = parseFloat(v);
        return isNaN(n) ? (dp > 0 ? '0.00' : 0) : n.toFixed(dp || 2);
    }
    function numVal(id){ return parseFloat($(id).val()) || 0; }

    // ── Statement Close Calc ───────────────
    function calcStatementClose(){
        var o = numVal('#txt_statement_open_bal');
        var c = numVal('#txt_statement_tot_cr');
        var d = numVal('#txt_statement_tot_dr');
        $('#txt_statement_closed_bal').val(fnum((o + c) - d));
        updateBalanceSummary();
    }

    // ── Balance Summary ────────────────────
    function updateBalanceSummary(){
        var stmtOpen  = numVal('#txt_statement_open_bal');
        var stmtClose = numVal('#txt_statement_closed_bal');
        var accOpen   = STATE.acc_open_bal;
        // FIX: bank statement CR (deposit) = company book DR (asset increase), and vice versa.
        // accClose must use dr_running (deposits) minus cr_running (withdrawals), not the reverse.
        var accClose  = (accOpen + STATE.dr_running) - STATE.cr_running;
        var diff      = stmtClose - accClose;

        $('#bal_stmt_open').text(fnum(stmtOpen));
        $('#bal_stmt_close').text(fnum(stmtClose));
        $('#bal_acc_close').text(fnum(accClose));
        $('#bal_acc_open_lbl').text('Opening: ' + fnum(accOpen));
        $('#bal_diff').text(fnum(Math.abs(diff)));

        $('#diff_cell').removeClass('ok warn err highlight');
        $('#bal_diff_lbl').removeClass('zero nonzero');
        if(diff == 0){
            $('#diff_cell').addClass('ok');
            $('#bal_diff').addClass('cr-amount').removeClass('dr-amount');
            $('#bal_diff_lbl').addClass('zero').text('✓ Balanced');
        } else {
            $('#diff_cell').addClass('err');
            $('#bal_diff').addClass('dr-amount').removeClass('cr-amount');
            $('#bal_diff_lbl').addClass('nonzero').text(diff > 0 ? '▲ Over' : '▼ Under');
        }

        $('#diff_banner').show().removeClass('ok warn err');
        if(diff == 0){
            $('#diff_banner').addClass('ok');
            $('#diff_banner_msg').html('<i class="fas fa-check-circle"></i> &nbsp;Reconciliation is balanced. Ready to approve.');
        } else {
            $('#diff_banner').addClass('err');
            $('#diff_banner_msg').html('<i class="fas fa-exclamation-triangle"></i> &nbsp;Difference of <strong>' + fnum(Math.abs(diff)) + '</strong> — tick more transactions to balance.');
        }

        $('#tbl_tot_cr').text(fnum(STATE.cr_running));
        $('#tbl_tot_dr').text(fnum(STATE.dr_running));
    }

    // ── Render Table ───────────────────────
    function renderTable(){
        var filter = STATE.filter;
        var rows   = STATE.transactions;
        var matchedCnt = 0, unmatchedCnt = 0;

        $.each(rows, function(i, r){
            if(r.rec_info_status == 1) matchedCnt++;
            else unmatchedCnt++;
        });

        $('#cnt_all').text(rows.length);
        $('#cnt_matched').text(matchedCnt);
        $('#cnt_unmatched').text(unmatchedCnt);
        $('#lbl_match_count').text(matchedCnt + '/' + rows.length + ' matched').show();

        var visible = rows.filter(function(r){
            if(filter == 'matched')   return r.rec_info_status == 1;
            if(filter == 'unmatched') return r.rec_info_status != 1;
            return true;
        });

        var $tbody = $('#tbl_rec_body').empty();

        if(visible.length == 0){
            $tbody.append('<tr><td colspan="8" class="empty-state"><i class="fas fa-check-circle" style="color:var(--success);"></i><p>No transactions in this filter.</p></td></tr>');
            return;
        }

        $.each(visible, function(i, r){
            var isMatched  = (r.rec_info_status == 1);
            var isRevision = (r.opt_render == 'btn');
            var crVal = parseFloat(r.cr_val) || 0;
            var drVal = parseFloat(r.dr_val) || 0;
            var rowClass = isRevision ? 'revision-row' : (isMatched ? 'matched' : 'unmatched');

            var chkCol = '';
            if(r.opt_render == 'chkinput'){
                var chkd = isMatched ? 'checked' : '';
                var reviseAttr = 'data-revisestatus="' + r.rec_revise_status + '" data-recid="' + r.rec_info_id + '"';
                chkCol = '<input type="checkbox" class="chk-match chk_accd" value="' + r.transaction_id + '" ' + chkd + ' ' + reviseAttr + ' data-origin="' + r.opt_origin + '" data-dtprefix="' + r.opt_dtprefix + '" />';
            } else {
                chkCol = '<button class="btn-rec btn-danger-rec btn_del_bank_amount" data-revisionid="' + r.rec_revision_id + '" style="padding:3px 8px;font-size:11px;"><i class="fas fa-trash"></i></button>';
            }

            var statusChip = isRevision
                ? '<span class="chip chip-revision">Adjustment</span>'
                : (isMatched
                    ? '<span class="chip chip-matched"><i class="fas fa-check"></i> Matched</span>'
                    : '<span class="chip chip-unmatched"><i class="fas fa-clock"></i> Pending</span>'
                  );

            var crDisplay = crVal > 0 ? '<span class="amount-mono cr-amount">' + fnum(crVal) + '</span>' : '<span style="color:var(--text-3);">—</span>';
            var drDisplay = drVal > 0 ? '<span class="amount-mono dr-amount">' + fnum(drVal) + '</span>' : '<span style="color:var(--text-3);">—</span>';

            var rowId = '';
            if(r.opt_dtprefix && r.transaction_id) rowId = 'id="' + r.opt_dtprefix + '-' + r.transaction_id + '"';
            else if(r.rec_revision_id) rowId = 'id="rec_accd-' + r.rec_revision_id + '"';

            $tbody.append(
                '<tr class="' + rowClass + '" ' + rowId + '>' +
                '<td class="text-center">' + chkCol + '</td>' +
                '<td><span class="period-badge">' + (r.acc_period_txt || '—') + '</span></td>' +
                '<td style="white-space:nowrap;">' + (r.transaction_date || '—') + '</td>' +
                '<td>' + (r.narration_txt || '') + '</td>' +
                '<td class="text-right">' + crDisplay + '</td>' +
                '<td class="text-right">' + drDisplay + '</td>' +
                '<td class="text-center">' + statusChip + '</td>' +
                '<td class="text-center">—</td>' +
                '</tr>'
            );
        });
    }

    // ═══════════════════════════════════════════════════════════════════
    // FIX 1: Financial Years - Controller create() response ලෙස load කරමු
    // External script file ඕනෙ නෑ.
    // Year list එක controller ලෙස fetch කරන්නේ නෑ, ඒ වෙනුවට
    // loadReconciliation response ලෙස server ලැබෙන data use කරමු.
    // නමුත් initial page load එකෙදී year list ගන්න controller
    // get_financial_years action එකක් ඕනෑ. 
    // විකල්පය: bank account select කළාම years load කරන්න.
    // ═══════════════════════════════════════════════════════════════════

    function loadFinancialYears(bankaccId, callback){
        // Bank account select වුනාම controller ලෙස financial years ගනිමු
        $.ajax({
            method  : 'POST',
            url     : '<?php echo base_url("BankReconciliation/getYears"); ?>', // controller method add ඕනෑ
            data    : { bankacc_id: bankaccId },
            dataType: 'json'
        }).done(function(data){
            var $drp = $('#drp_rec_year').empty().append('<option value="">— Select Year —</option>');
            STATE.financial_years = data;
            $.each(data, function(i, y){
                $drp.append('<option value="' + y.id + '" data-year="' + y.year + '">' + y.label + '</option>');
            });
            if(typeof callback === 'function') callback();
        }).fail(function(){
            showToast('Financial years load failed', 'error');
        });
    }

    function loadFinancialMonths(yearId, callback){
        var $drp = $('#drp_rec_month').empty().append('<option value="">— Select Month —</option>');
        if(!yearId) return;

        $.ajax({
            method  : 'POST',
            url     : '<?php echo base_url("BankReconciliation/getMonths"); ?>', // controller method add ඕනෑ
            data    : { year_id: yearId },
            dataType: 'json'
        }).done(function(data){
            STATE.financial_months = data;
            $.each(data, function(i, m){
                $drp.append('<option value="' + m.id + '">' + m.label + '</option>');
            });
            if(typeof callback === 'function') callback();
        }).fail(function(){
            showToast('Financial months load failed', 'error');
        });
    }

    // ═══════════════════════════════════════════════════════════════════
    // FIX 2: loadReconciliation - year & month සමඟ data load කරනවා
    // ═══════════════════════════════════════════════════════════════════
    function loadReconciliation(bankaccId, mainId, yearId, monthId){
        showToast('Loading...', 'info');
        $('#tbl_rec_body').html('<tr><td colspan="8" class="text-center" style="padding:30px;color:var(--text-3);"><i class="fas fa-spinner fa-spin fa-2x"></i><br><br>Loading transactions...</td></tr>');

        $.ajax({
            method  : 'POST',
            url     : '<?php echo base_url("BankReconciliation/create"); ?>',
            data    : { 
                main_id     : mainId || '', 
                bankacc_id  : bankaccId,
                year_id     : yearId  || '',   // <-- NEW: year pass කරනවා
                month_id    : monthId || ''    // <-- NEW: month pass කරනවා
            },
            dataType: 'json'
        }).done(function(data){
            var h = data.view_header_data;
            STATE.rec_main_id  = h.idtbl_bank_rec_list || '';
            STATE.bankacc_id   = bankaccId;
            STATE.rec_year     = h.tbl_finacial_year_idtbl_finacial_year;
            STATE.rec_month    = h.tbl_finacial_month_idtbl_finacial_month;
            STATE.rec_approved = parseInt(h.rec_approved) || 0;
            STATE.acc_open_bal = parseFloat(h.acc_open_bal) || 0;
            STATE.cr_running   = parseFloat(data.acc_tot_cr) || 0;
            STATE.dr_running   = parseFloat(data.acc_tot_dr) || 0;
            STATE.transactions = data.view_detail_data || [];

            // Form fill
            $('#txt_bank_rec_date').val(h.bank_rec_date || '');
            $('#txt_statement_open_bal').val(fnum(h.statement_open_bal));
            $('#txt_statement_tot_cr').val(fnum(h.statement_tot_cr));
            $('#txt_statement_tot_dr').val(fnum(h.statement_tot_dr));
            calcStatementClose();
            $('#txt_statement_closed_bal').val(fnum(h.statement_closed_bal));
            $('#txt_account_open_bal').val(fnum(h.acc_open_bal));
            $('#h_rec_period_year').val(STATE.rec_year);
            $('#h_rec_period_month').val(STATE.rec_month);
            $('#h_rec_main_id').val(STATE.rec_main_id);

            // ── FIX: Dropdowns ── server ලෙස ලැබුණු year/month set කරනවා
            if(STATE.rec_year)  $('#drp_rec_year').val(STATE.rec_year);
            if(STATE.rec_month) {
                // Month dropdown populate වෙලා නැත්නම් populate කරලා set කරනවා
                if($('#drp_rec_month option[value="' + STATE.rec_month + '"]').length){
                    $('#drp_rec_month').val(STATE.rec_month);
                } else {
                    loadFinancialMonths(STATE.rec_year, function(){
                        $('#drp_rec_month').val(STATE.rec_month);
                    });
                }
            }

            if(data.bank_rec_period_txt){
                $('#lbl_rec_batchno').text(data.bank_rec_period_txt).show();
            }

            if(STATE.rec_approved == 1){
                $('#lbl_approved_badge').removeClass().addClass('chip chip-matched').html('<i class="fas fa-lock"></i> Approved').show();
            } else {
                $('#lbl_approved_badge').hide();
            }

            if(data.acc_periods && data.acc_periods.length){
                var $dp = $('#drp_adj_period').empty().append('<option value="">— Select Branch —</option>');
                $.each(data.acc_periods, function(i, p){
                    $dp.append('<option value="' + p.form_key + '">' + p.form_val + '</option>');
                });
            }

            $('#panel_statement').show();
            $('#panel_balance').css('display','grid');
            $('#panel_adjustments').show();
            $('#panel_transactions').show();
            $('#panel_approve').show();
            $('#diff_banner').show();

            setStep(3);
            renderTable();
            updateBalanceSummary();

            if(STATE.rec_approved == 1){
                $('#btnApprove').prop('disabled', true).html('<i class="fas fa-lock"></i> Already Approved');
                $('.chk_accd').prop('disabled', true);
                $('#btnSaveHeader').prop('disabled', true);
                $('#btnAddAdjustment').prop('disabled', true);
            } else {
                $('#btnApprove').prop('disabled', false).html('<i class="fas fa-check-double"></i> Approve Reconciliation');
                $('.chk_accd').prop('disabled', false);
                $('#btnSaveHeader').prop('disabled', false);
                $('#btnAddAdjustment').prop('disabled', false);
            }

            showToast('Loaded ' + STATE.transactions.length + ' transactions', 'success');

        }).fail(function(){
            showToast('Failed to load reconciliation', 'error');
        });
    }

    // ═══════════════════════════════════════════════════════════════════
    // FIX 3: Events - Account, Year, Month change handlers
    // ═══════════════════════════════════════════════════════════════════

    // Bank Account change → Years load කරනවා
    $('#drp_bank_accounts').on('change', function(){
        var id = $(this).val();
        if(id == '-1' || id == '') return;
        STATE.bankacc_id = id;

        // Reset year/month dropdowns
        $('#drp_rec_year').empty().append('<option value="">— Select Year —</option>');
        $('#drp_rec_month').empty().append('<option value="">— Select Month —</option>');

        setStep(2);
        $('#panel_statement').show();

        // Years load කරනවා - load වූ පස්සේ existing rec ලෙස auto-select කරනවා
        loadFinancialYears(id, function(){
            // Existing ongoing rec ලෙස auto-load - year/month controller ලෙස ඇත
            loadReconciliation(id, '', '', '');
        });
    });

    // Year change → Months load + Transactions reload
    $('#drp_rec_year').on('change', function(){
        var yearId = $(this).val();
        $('#drp_rec_month').empty().append('<option value="">— Select Month —</option>');
        if(!yearId) return;

        loadFinancialMonths(yearId, function(){
            // Month select කරන්නට user ට දෙනවා - auto reload කරන්නේ නෑ
        });
    });

    // Month change → Transactions reload
    $('#drp_rec_month').on('change', function(){
        var monthId = $(this).val();
        var yearId  = $('#drp_rec_year').val();
        var bankId  = STATE.bankacc_id;

        if(!monthId || !yearId || !bankId) return;

        loadReconciliation(bankId, '', yearId, monthId);
    });

    // ── Statement inputs ──────────────────
    $('.stmt-input').on('input change', function(){
        calcStatementClose();
    });

    // ── Save Header ───────────────────────
    $('#btnSaveHeader').on('click', function(){
        var $btn = $(this);
        if(STATE.bankacc_id == '') { showToast('Select a bank account first', 'error'); return; }

        // Year & Month validate
        var selYear  = $('#drp_rec_year').val();
        var selMonth = $('#drp_rec_month').val();
        if(!selYear)  { showToast('Please select Financial Year', 'error'); return; }
        if(!selMonth) { showToast('Please select Financial Month', 'error'); return; }

        // STATE update
        STATE.rec_year  = selYear;
        STATE.rec_month = selMonth;

        $btn.prop('disabled', true).html('<span class="spinner"></span> Saving...');

        var revisedRows = getRevisedRows();

        $.ajax({
            method  : 'POST',
            url     : '<?php echo base_url("BankReconciliation/store"); ?>',
            data    : {
                bank_rec_date        : $('#txt_bank_rec_date').val(),
                statement_open_bal   : $('#txt_statement_open_bal').val(),
                statement_tot_cr     : $('#txt_statement_tot_cr').val(),
                statement_tot_dr     : $('#txt_statement_tot_dr').val(),
                statement_closed_bal : $('#txt_statement_closed_bal').val(),
                rec_acc_id           : STATE.bankacc_id,
                rec_period_year      : STATE.rec_year,
                rec_period_month     : STATE.rec_month,
                rec_main_id          : STATE.rec_main_id,
                main_account_id      : '-1',
                bank_amount          : 0,
                revised_rows         : revisedRows
            },
            dataType: 'json'
        }).done(function(data){
            if(data.resMsg && data.resMsg != ''){
                showToast(data.resMsg, 'error');
            } else {
                if(STATE.rec_main_id == '') STATE.rec_main_id = data.head_k;
                $('#h_rec_main_id').val(STATE.rec_main_id);
                setStep(3);
                showToast('Saved successfully', 'success');

                if(data.view_detail_data && data.view_detail_data != '-1'){
                    STATE.transactions = data.view_detail_data;
                    renderTable();
                } else {
                    $('.chk_accd[data-revisestatus="1"]').attr('data-revisestatus','0');
                }
            }
        }).fail(function(){
            showToast('Save failed', 'error');
        }).always(function(){
            $btn.prop('disabled', false).html('<i class="fas fa-save"></i> Save');
        });
    });

    // ── Checkbox toggle ───────────────────
    $(document).on('change', '.chk_accd', function(){
        if(STATE.rec_approved == 1) { $(this).prop('checked', !$(this).is(':checked')); return; }

        var $chk     = $(this);
        var isChecked= $chk.is(':checked');
        var tranId   = $chk.val();

        $.each(STATE.transactions, function(i, r){
            if(String(r.transaction_id) === String(tranId)){
                var crVal = parseFloat(r.cr_val) || 0;
                var drVal = parseFloat(r.dr_val) || 0;
                var multiplier = isChecked ? 1 : -1;
                STATE.cr_running += crVal * multiplier;
                STATE.dr_running += drVal * multiplier;
                r.rec_info_status = isChecked ? 1 : 2;
                r.rec_revise_status = 1;
                return false;
            }
        });

        $chk.attr('data-revisestatus', 1);
        updateBalanceSummary();

        var $row = $chk.closest('tr');
        $row.removeClass('matched unmatched');
        $row.addClass(isChecked ? 'matched' : 'unmatched');
    });

    // ── Select All ────────────────────────
    $('#chk_all').on('change', function(){
        var checked = $(this).is(':checked');
        $('.chk_accd:not(:disabled)').each(function(){
            var wasChecked = $(this).is(':checked');
            if(wasChecked !== checked){
                $(this).prop('checked', checked).trigger('change');
            }
        });
    });

    // ── Filter Tabs ───────────────────────
    $(document).on('click', '.filter-tab', function(){
        $('.filter-tab').removeClass('active');
        $(this).addClass('active');
        STATE.filter = $(this).data('filter');
        renderTable();
    });

    // ── Add Adjustment ────────────────────
    $('#btnToggleAdjust').on('click', function(){
        $('#panel_adjust_form').slideToggle(200);
        $(this).find('i').toggleClass('fa-chevron-down fa-chevron-up');
    });

    $('#btnAddAdjustment').on('click', function(){
        if(STATE.rec_main_id == ''){ showToast('Save header first', 'error'); return; }

        var adjAccId  = $('#drp_adj_account').val();
        var adjDate   = $('#txt_adj_date').val();
        var adjPeriod = $('#drp_adj_period').val();
        var adjNarr   = $('#txt_adj_narration').val().trim();
        var adjAmt    = parseFloat($('#txt_adj_amount').val()) || 0;
        var adjType   = $('input[name="adj_type"]:checked').val();

        if(adjAccId == '-1' || adjAccId == ''){ showToast('Select account', 'error'); return; }
        if(!adjNarr){ showToast('Enter narration', 'error'); return; }
        if(adjAmt <= 0){ showToast('Enter a valid amount', 'error'); return; }
        if(!adjPeriod){ showToast('Select branch/period', 'error'); return; }

        var bankAccId = STATE.bankacc_id;
        var accountCr = (adjType == 'cr') ? adjAccId : bankAccId;
        var accountDr = (adjType == 'cr') ? bankAccId : adjAccId;
        var amountCr  = (adjType == 'cr') ? adjAmt : 0;
        var amountDr  = (adjType == 'cr') ? 0 : adjAmt;

        var $btn = $(this).prop('disabled', true).html('<span class="spinner"></span>');

        var periodlabletext = $('#drp_rec_year option:selected').data('year') + ' ' + $('#drp_rec_month option:selected').text();

        $.ajax({
            method  : 'POST',
            url     : '<?php echo base_url("BankReconciliation/store"); ?>',
            data    : {
                bank_rec_date        : $('#txt_bank_rec_date').val(),
                statement_open_bal   : $('#txt_statement_open_bal').val(),
                statement_tot_cr     : $('#txt_statement_tot_cr').val(),
                statement_tot_dr     : $('#txt_statement_tot_dr').val(),
                statement_closed_bal : $('#txt_statement_closed_bal').val(),
                rec_acc_id           : bankAccId,
                rec_period_year      : STATE.rec_year,
                rec_period_month     : STATE.rec_month,
                rec_main_id          : STATE.rec_main_id,
                main_account_id      : adjAccId,
                transaction_date     : adjDate,
                account_cr           : accountCr,
                account_dr           : accountDr,
                acc_period           : adjPeriod,
                main_account_narration: adjNarr,
                bank_amount          : adjAmt,
                revised_rows         : []
            },
            dataType: 'json'
        }).done(function(data){
            if(data.resMsg && data.resMsg != ''){
                showToast(data.resMsg, 'error');
            } else {
                if(data.sub_k > 0){
                    STATE.cr_running += amountCr;
                    STATE.dr_running += amountDr;

                    STATE.transactions.push({
                        transaction_id   : '',
                        rec_info_id      : '',
                        rec_revision_id  : data.sub_k,
                        rec_info_status  : 1,
                        // FIX: dropdown එකේ selected branch/period text එකම දාන්නවා,
                        // refresh කරන්නම බලාගෙන ඉන්නෙ නැතුව add කරන ගමන්ම Period column එක පේනවා
                        // acc_period_txt   : $('#drp_adj_period option:selected').text() || '—',
                        acc_period_txt   : periodlabletext || '—',
                        narration_txt    : adjNarr,
                        transaction_date : adjDate,
                        cr_val           : amountCr,
                        dr_val           : amountDr,
                        rec_revise_status: 0,
                        opt_render       : 'btn',
                        opt_origin       : 'origin_blank',
                        opt_dtprefix     : 'rec_accd'
                    });

                    renderTable();
                    updateBalanceSummary();

                    $('#drp_adj_account').val('-1');
                    $('#txt_adj_narration').val('');
                    $('#txt_adj_date').val('');
                    $('#txt_adj_amount').val('');
                    $('#panel_adjust_form').slideUp(200);
                    $('#btnToggleAdjust i').removeClass('fa-chevron-up').addClass('fa-chevron-down');

                    showToast('Adjustment added', 'success');
                }
            }
        }).fail(function(){
            showToast('Failed to add adjustment', 'error');
        }).always(function(){
            $btn.prop('disabled', false).html('<i class="fas fa-plus"></i> Add');
        });
    });

    // ── Delete Adjustment ─────────────────
    $(document).on('click', '.btn_del_bank_amount', function(){
        var revId = $(this).data('revisionid');
        if(!confirm('Delete this adjustment?')) return;

        var $btn = $(this).prop('disabled', true).html('<span class="spinner"></span>');

        $.ajax({
            method  : 'POST',
            url     : '<?php echo base_url("BankReconciliation/destroy"); ?>',
            data    : { item_ref: revId },
            dataType: 'json'
        }).done(function(data){
            if(data.msgErr){
                showToast(data.resMsg || 'Error', 'error');
            } else {
                STATE.transactions = STATE.transactions.filter(function(r){ return r.rec_revision_id != revId; });
                recalcRunningTotals();
                renderTable();
                updateBalanceSummary();
                showToast('Adjustment deleted', 'success');
            }
        }).fail(function(){
            showToast('Delete failed', 'error');
            $btn.prop('disabled', false).html('<i class="fas fa-trash"></i>');
        });
    });

    function recalcRunningTotals(){
        STATE.cr_running = 0;
        STATE.dr_running = 0;
        $.each(STATE.transactions, function(i, r){
            if(r.rec_info_status == 1){
                STATE.cr_running += parseFloat(r.cr_val) || 0;
                STATE.dr_running += parseFloat(r.dr_val) || 0;
            }
        });
    }

    // ── Approve ───────────────────────────
    $('#btnApprove').on('click', function(){
        if(STATE.rec_main_id == ''){ showToast('No active reconciliation', 'error'); return; }
        if(STATE.rec_approved == 1){ showToast('Already approved', 'error'); return; }

        var stmtClose = parseFloat($('#txt_statement_closed_bal').val()) || 0;
        // FIX: same CR/DR correction as updateBalanceSummary()
        var accClose  = (STATE.acc_open_bal + STATE.dr_running) - STATE.cr_running;
        var diff      = stmtClose - accClose;

        if(Math.abs(diff) > 0.005){
            showToast('Difference must be 0 before approving', 'error');
            return;
        }

        if(!confirm('Approve this reconciliation? This cannot be undone.')) return;

        var $btn = $(this).prop('disabled', true).html('<span class="spinner"></span> Approving...');
        var revisedRows = getRevisedRows();

        $.ajax({
            method  : 'POST',
            url     : '<?php echo base_url("BankReconciliation/store"); ?>',
            data    : {
                bank_rec_date        : $('#txt_bank_rec_date').val(),
                statement_open_bal   : $('#txt_statement_open_bal').val(),
                statement_tot_cr     : $('#txt_statement_tot_cr').val(),
                statement_tot_dr     : $('#txt_statement_tot_dr').val(),
                statement_closed_bal : $('#txt_statement_closed_bal').val(),
                rec_acc_id           : STATE.bankacc_id,
                rec_period_year      : STATE.rec_year,
                rec_period_month     : STATE.rec_month,
                rec_main_id          : STATE.rec_main_id,
                main_account_id      : '-1',
                bank_amount          : 0,
                revised_rows         : revisedRows
            },
            dataType: 'json'
        }).done(function(saveData){
            if(saveData.resMsg && saveData.resMsg != ''){
                showToast(saveData.resMsg, 'error');
                $btn.prop('disabled', false).html('<i class="fas fa-check-double"></i> Approve Reconciliation');
                return;
            }
            $.ajax({
                method  : 'POST',
                url     : '<?php echo base_url("BankReconciliation/freeze"); ?>',
                data    : {
                    revised_rows        : ($('.chk_accd[data-revisestatus="1"]').length == 0) ? 0 : 1,
                    selected_opt        : STATE.rec_main_id,
                    exp_rows            : 0,
                    statement_open_bal  : $('#txt_statement_open_bal').val(),
                    statement_cr        : $('#txt_statement_tot_cr').val(),
                    acc_cr              : fnum(STATE.cr_running),
                    statement_dr        : $('#txt_statement_tot_dr').val(),
                    acc_dr              : fnum(STATE.dr_running),
                    rec_period_year     : STATE.rec_year,
                    rec_period_month    : STATE.rec_month,
                    statement_close     : $('#txt_statement_closed_bal').val(),
                    acc_close           : fnum(accClose)
                },
                dataType: 'json'
            }).done(function(data){
                if(data.resMsg && data.resMsg != ''){
                    showToast(data.resMsg, 'error');
                    $btn.prop('disabled', false).html('<i class="fas fa-check-double"></i> Approve Reconciliation');
                } else {
                    STATE.rec_approved = 1;
                    showToast('Reconciliation approved!', 'success');
                    setStep(4);
                    $btn.html('<i class="fas fa-lock"></i> Approved').prop('disabled', true);
                    $('#lbl_approved_badge').removeClass().addClass('chip chip-matched').html('<i class="fas fa-lock"></i> Approved').show();
                    $('.chk_accd').prop('disabled', true);
                    $('#btnSaveHeader').prop('disabled', true);
                    $('#btnAddAdjustment').prop('disabled', true);
                }
            }).fail(function(){
                showToast('Approve failed', 'error');
                $btn.prop('disabled', false).html('<i class="fas fa-check-double"></i> Approve Reconciliation');
            });
        });
    });

    // ── Revised Rows Helper ───────────────
    function getRevisedRows(){
        return $('.chk_accd[data-revisestatus="1"]').map(function(){
            var $el = $(this);
            return {
                idtbl_bank_rec_info : $el.attr('data-recid'),
                tbl_account_transaction_idtbl_account_transaction : $el.val(),
                rec_info_origin_name : $el.data('origin'),
                status : $el.is(':checked') ? 1 : 2,
                updateuser : '',
                updatedatetime : ''
            };
        }).get();
    }

    // ── Open Ongoing / Completed ──────────
    $('#btnOpenOngoing').on('click', function(){
        $('#modalOngoing').modal('show');
        loadOngoingList();
    });

    $('#btnOpenCompleted').on('click', function(){
        $('#modalCompleted').modal('show');
        loadCompletedList();
    });

    function loadOngoingList(){
        $('#tbl_ongoing_body').html('<tr><td colspan="3" class="text-center" style="padding:20px;color:var(--text-3);"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>');
        $.ajax({
            url: '<?php echo base_url("scripts/bank_accounts_list.php"); ?>',
            method: 'POST',
            data: { draw:1, start:0, length:100 },
            dataType: 'json'
        }).done(function(data){
            var rows = data.data || [];
            var $tbody = $('#tbl_ongoing_body').empty();
            if(!rows.length){
                $tbody.html('<tr><td colspan="3" class="empty-state"><i class="fas fa-inbox"></i><p>No ongoing reconciliations</p></td></tr>');
                return;
            }
            $.each(rows, function(i, r){
                $tbody.append(
                    '<tr><td>' + r.bankacc_name + '</td><td>' + r.bankacc_accountno + '</td>' +
                    '<td class="text-center"><button class="btn-rec btn-primary-rec btn_open_ongoing" style="padding:4px 12px;font-size:12px;" data-bankaccid="' + r.bankacc_regno + '"><i class="fas fa-folder-open"></i> Open</button></td></tr>'
                );
            });
        });
    }

    function loadCompletedList(){
        $('#tbl_completed_body').html('<tr><td colspan="4" class="text-center" style="padding:20px;color:var(--text-3);"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>');
        $.ajax({
            url: '<?php echo base_url("scripts/confirmed_bank_recs_list.php"); ?>',
            method: 'POST',
            data: { draw:1, start:0, length:100 },
            dataType: 'json'
        }).done(function(data){
            var rows = data.data || [];
            var $tbody = $('#tbl_completed_body').empty();
            if(!rows.length){
                $tbody.html('<tr><td colspan="4" class="empty-state"><i class="fas fa-inbox"></i><p>No completed reconciliations</p></td></tr>');
                return;
            }
            $.each(rows, function(i, r){
                $tbody.append(
                    '<tr><td>' + r.bankacc_name + '</td><td>' + r.bankacc_accountno + '</td>' +
                    '<td><span class="period-badge">' + (r.bankrec_batchno || '—') + '</span></td>' +
                    '<td class="text-center"><button class="btn-rec btn-outline-rec btn_open_completed" style="padding:4px 12px;font-size:12px;" data-mainid="' + r.bankrec_regno + '" data-bankaccid="' + r.bankacc_regno + '"><i class="fas fa-eye"></i> View</button></td></tr>'
                );
            });
        });
    }

    $(document).on('click', '.btn_open_ongoing', function(){
        var bankaccid = $(this).data('bankaccid');
        $('#modalOngoing').modal('hide');
        $('#drp_bank_accounts').val(bankaccid).trigger('change');
    });

    $(document).on('click', '.btn_open_completed', function(){
        var mainid    = $(this).data('mainid');
        var bankaccid = $(this).data('bankaccid');
        $('#modalCompleted').modal('hide');
        $('#drp_bank_accounts').val(bankaccid);
        loadReconciliation(bankaccid, mainid, '', '');
    });

    // ── New Rec ───────────────────────────
    $('#btnNewRec').on('click', function(){
        STATE.rec_main_id = '';
        STATE.rec_approved = 0;
        STATE.transactions = [];
        STATE.cr_running = 0;
        STATE.dr_running = 0;

        $('#drp_bank_accounts').val('-1');
        $('#drp_rec_year').empty().append('<option value="">— Select Year —</option>');
        $('#drp_rec_month').empty().append('<option value="">— Select Month —</option>');
        $('#txt_bank_rec_date').val('');
        $('#txt_statement_open_bal, #txt_statement_tot_cr, #txt_statement_tot_dr, #txt_statement_closed_bal').val('0.00');
        $('#tbl_rec_body').html('<tr><td colspan="8" class="empty-state"><i class="fas fa-search"></i><p>Select a bank account to load transactions</p></td></tr>');
        $('#panel_statement, #panel_adjustments, #panel_transactions, #panel_approve, #diff_banner').hide();
        $('#panel_balance').css('display','none');
        $('#lbl_rec_batchno, #lbl_approved_badge').hide();
        setStep(1);
        showToast('Ready for new reconciliation', 'info');
    });

    // ── INIT ──────────────────────────────
    $('#txt_bank_rec_date').val(new Date().toISOString().split('T')[0]);

    $('body').append(
        '<input type="hidden" id="h_rec_main_id" />' +
        '<input type="hidden" id="h_rec_period_year" />' +
        '<input type="hidden" id="h_rec_period_month" />' +
        '<input type="hidden" id="h_rec_detail_cr_running_total" />' +
        '<input type="hidden" id="h_rec_detail_dr_running_total" />' +
        '<input type="hidden" id="txt_account_open_bal" />'
    );

    $("#drp_adj_account").select2({
        // dropdownParent: $('#modalsegregation'),
        ajax: {
            url: "<?php echo base_url() ?>BankReconciliation/Getaccountlist",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    searchTerm: params.term
                };
            },
            processResults: function (response) {
                return {
                    // FIX: reconcile කරන bank account එකම adjustment account එක ලෙස select
                    // වෙන එක menu එකෙන්ම ඉවත් කරනවා. (එහෙම වුනොත් cr/dr account දෙකම
                    // bank account එකම වෙලා ඒ adjustment එකෙන් balance එක change නොවෙනවා)
                    results: response
                        .filter(function(item){ return item.id != STATE.bankacc_id; })
                        .map(function (item) {
                        return {
                            id: item.id,
                            text: item.text,
                            data: {
                                type: item.acctype
                            }
                        };
                    })
                }
            },
            cache: true
        },
    });
});
</script>

<?php include "include/footer.php"; ?>