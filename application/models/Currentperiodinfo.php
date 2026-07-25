<?php
class Currentperiodinfo extends CI_Model{
    // public function Currentperiodinsertupdate(){
    //     $this->db->trans_begin();

    //     $userID=$_SESSION['userid'];

    //     $finacialyear=$this->input->post('finacialyear');
    //     $finacialmonth=$this->input->post('finacialmonth');
    //     $status=$this->input->post('status');
    //     $monthstatus=$this->input->post('monthstatus');
    //     $companyid=$_SESSION['companyid'];
    //     $branchid=$_SESSION['branchid'];
    //     $updatedatetime=date('Y-m-d H:i:s');

    //     try{
    //         if($status==1){
    //             if($monthstatus==0){
    //                 $expired_account_info = get_account_period($companyid, $branchid);
    //                 $expired_account_master_period = !empty($expired_account_info)?$expired_account_info->idtbl_master:'';

    //                 if(!empty($expired_account_info)){
    //                     $datamaster = array(
    //                         'period_status'=> '3'
    //                     );

    //                     $this->db->where('idtbl_master', $expired_account_info->idtbl_master);
    //                     $this->db->update('tbl_master', $datamaster);

    //                     $datayear = array(
    //                         'activestatus'=> '3'
    //                     );

    //                     $this->db->where('idtbl_finacial_month', $expired_account_info->tbl_finacial_month_idtbl_finacial_month);
    //                     $this->db->where('tbl_finacial_year_idtbl_finacial_year', $expired_account_info->tbl_finacial_year_idtbl_finacial_year);
    //                     $this->db->update('tbl_finacial_month', $datayear);
    //                 }

    //                 $data = array(
    //                     'status'=> '1', 
    //                     'insertdatetime'=> $updatedatetime, 
    //                     'tbl_user_idtbl_user'=> $userID,
    //                     'tbl_company_idtbl_company'=> $companyid,
    //                     'tbl_company_branch_idtbl_company_branch'=> $branchid,
    //                     'tbl_finacial_year_idtbl_finacial_year'=> $finacialyear,
    //                     'tbl_finacial_month_idtbl_finacial_month'=> $finacialmonth
    //                 );

    //                 $this->db->insert('tbl_master', $data);
                    
    //                 $activated_account_master_period = $this->db->insert_id();

    //                 $datayear = array(
    //                     'actstatus'=> '1',
    //                     'updateuser'=> $userID, 
    //                     'updatedatetime' => $updatedatetime
    //                 );

    //                 $this->db->where('idtbl_finacial_year', $finacialyear);
    //                 $this->db->update('tbl_finacial_year', $datayear);

    //                 $datamonth = array(
    //                     'activestatus'=> '1',
    //                     'updateuser'=> $userID, 
    //                     'updatedatetime' => $updatedatetime
    //                 );

    //                 $this->db->where('tbl_finacial_year_idtbl_finacial_year', $finacialyear);
    //                 $this->db->where('idtbl_finacial_month', $finacialmonth);
    //                 $this->db->update('tbl_finacial_month', $datamonth);
    //             }
    //         }
    //         else if($status==2){
                
    //         }

    //         if ($this->db->trans_status() === FALSE) {
    //             throw new Exception('Database Error');
    //         }

    //         $this->db->trans_commit();
            
    //         $actionObj          = new stdClass();
    //         $actionObj->icon    = 'fas fa-save';
    //         $actionObj->title   = '';
    //         $actionObj->message = 'Record Added Successfully';
    //         $actionObj->url     = '';
    //         $actionObj->target  = '_blank';
    //         $actionObj->type    = 'success';

    //         $obj         = new stdClass();
    //         $obj->status = 1;
    //         $obj->action = json_encode($actionObj);

    //         echo json_encode($obj); 
    //     }
    //     catch(Exception $e){
    //         $this->db->trans_rollback();

    //         $actionObj          = new stdClass();
    //         $actionObj->icon    = 'fas fa-exclamation-triangle';
    //         $actionObj->title   = '';
    //         $actionObj->message = 'Record Error: ' . $e->getMessage();
    //         $actionObj->url     = '';
    //         $actionObj->target  = '_blank';
    //         $actionObj->type    = 'danger';

    //         $obj         = new stdClass();
    //         $obj->status = 0;
    //         $obj->action = json_encode($actionObj);

    //         echo json_encode($obj);
    //     }
		
	// 	// //20240314--
	// 	// $expired_account_info = get_account_period($companyid, $branchid);
    //     // $expired_account_master_period = !empty($expired_account_info)?$expired_account_info->idtbl_master:'';
	// 	// //--20240314

    //     // $updatedatetime=date('Y-m-d H:i:s');

    //     // $data = array(
    //     //     'status'=> '1', 
    //     //     'insertdatetime'=> $updatedatetime, 
    //     //     'tbl_user_idtbl_user'=> $userID,
    //     //     'tbl_company_idtbl_company'=> $companyid,
    //     //     'tbl_company_branch_idtbl_company_branch'=> $branchid,
    //     //     'tbl_finacial_year_idtbl_finacial_year'=> $finacialyear,
    //     //     'tbl_finacial_month_idtbl_finacial_month'=> $finacialmonth
    //     // );

    //     // $this->db->insert('tbl_master', $data);
		
	// 	// //20240314--
	// 	// $activated_account_master_period = $this->db->insert_id();
	// 	// //--20240314

    //     // $datayear = array(
    //     //     'actstatus'=> '1',
    //     //     'updateuser'=> $userID, 
    //     //     'updatedatetime' => $updatedatetime
    //     // );

    //     // $this->db->where('idtbl_finacial_year', $finacialyear);
    //     // $this->db->update('tbl_finacial_year', $datayear);

    //     // $datamonth = array(
    //     //     'activestatus'=> '1',
    //     //     'updateuser'=> $userID, 
    //     //     'updatedatetime' => $updatedatetime
    //     // );

    //     // $this->db->where('tbl_finacial_year_idtbl_finacial_year', $finacialyear);
    //     // $this->db->where('idtbl_finacial_month', $finacialmonth);
    //     // $this->db->update('tbl_finacial_month', $datamonth);

    //     // //20240314--
	// 	// //opening-balances-of-activated-account-master-period
	// 	// $sql_open_bal = "select DATE(NOW()) AS applydate, (drv_open.openbal+(IFNULL(drv_crdr.dr_accamount, 0)*IFNULL(NULLIF(tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype-2, 0), 1)+IFNULL(drv_crdr.cr_accamount, 0)*IFNULL(NULLIF(1-tbl_account_category.tbl_account_transactiontype_idtbl_account_transactiontype, 0), 1))) AS openbal, 1 AS status, NOW() AS insertdatetime, ? AS tbl_user_idtbl_user, drv_open.tbl_account_idtbl_account, ? AS tbl_master_idtbl_master, ? AS tbl_company_idtbl_company, ? AS tbl_company_branch_idtbl_company_branch from (";
	// 	// $sql_open_bal .= "select tbl_account_idtbl_account, openbal from tbl_account_open_bal where tbl_master_idtbl_master=? and status=1";
	// 	// $sql_open_bal .= ") as drv_open ";
	// 	// $sql_open_bal .= "INNER JOIN tbl_account ON drv_open.tbl_account_idtbl_account=tbl_account.idtbl_account ";
	// 	// $sql_open_bal .= "INNER JOIN tbl_account_category ON tbl_account.tbl_account_category_idtbl_account_category=tbl_account_category.idtbl_account_category ";
	// 	// $sql_open_bal .= "LEFT OUTER JOIN (SELECT tbl_account_idtbl_account, SUM(accamount*(crdr='D')) AS dr_accamount, SUM(accamount*(crdr='C')) AS cr_accamount FROM tbl_account_transaction WHERE tbl_master_idtbl_master=? GROUP BY tbl_account_idtbl_account) AS drv_crdr ON drv_open.tbl_account_idtbl_account=drv_crdr.tbl_account_idtbl_account ";
	// 	// $sql_open_bal .= "WHERE tbl_account.status=1";
	// 	// //$sql_open_bal .= " ";
	// 	// //$sql_open_bal .= "AND tbl_account_category.code IN ('AS', 'LI')";
		
	// 	// $qry_open_bal = $this->db->query($sql_open_bal, array($userID, $activated_account_master_period, $companyid, $branchid, $expired_account_master_period, $expired_account_master_period));
	// 	// $rows_open_bal = $qry_open_bal->result_array();//array
		
    //     // if(!empty($rows_open_bal)){
	// 	//     $this->db->insert_batch('tbl_account_open_bal', $rows_open_bal);
    //     // }
	// 	// //--20240314
		
	// 	// $this->db->trans_complete();

    //     // if ($this->db->trans_status() === TRUE) {
    //     //     $this->db->trans_commit();
            
    //     //     $actionObj=new stdClass();
    //     //     $actionObj->icon='fas fa-save';
    //     //     $actionObj->title='';
    //     //     $actionObj->message='Record Added Successfully';
    //     //     $actionObj->url='';
    //     //     $actionObj->target='_blank';
    //     //     $actionObj->type='success';

    //     //     $actionJSON=json_encode($actionObj);
            
    //     //     $this->session->set_flashdata('msg', $actionJSON);
    //     //     redirect('Currentperiod');                
    //     // } else {
    //     //     $this->db->trans_rollback();

    //     //     $actionObj=new stdClass();
    //     //     $actionObj->icon='fas fa-warning';
    //     //     $actionObj->title='';
    //     //     $actionObj->message='Record Error';
    //     //     $actionObj->url='';
    //     //     $actionObj->target='_blank';
    //     //     $actionObj->type='danger';

    //     //     $actionJSON=json_encode($actionObj);
            
    //     //     $this->session->set_flashdata('msg', $actionJSON);
    //     //     redirect('Currentperiod');
    //     // }
    // }

    // public function Currentperiodinsertupdate(){
    //     $this->db->trans_begin();

    //     $userID      = $_SESSION['userid'];
    //     $finacialyear  = $this->input->post('finacialyear');
    //     $finacialmonth = $this->input->post('finacialmonth');
    //     $status        = $this->input->post('status');      // 1=activate new, 2=close period
    //     $monthstatus   = $this->input->post('monthstatus');
    //     $companyid     = $_SESSION['companyid'];
    //     $branchid      = $_SESSION['branchid'];
    //     $updatedatetime = date('Y-m-d H:i:s');
    //     $userID         = $_SESSION['userid'];

    //     try {

    //         // ══════════════════════════════════════════
    //         // STATUS 1 — Activate New Period
    //         // ══════════════════════════════════════════
    //         if($status == 1){
    //             if($monthstatus == 0){

    //                 // Previous active period → period_status = 3 (parked/suspended)
    //                 $expired_account_info = get_account_period($companyid, $branchid);
    //                 $expired_account_master_period = !empty($expired_account_info)
    //                     ? $expired_account_info->idtbl_master : '';

    //                 if(!empty($expired_account_info)){
    //                     $this->db->where('idtbl_master', $expired_account_info->idtbl_master);
    //                     $this->db->update('tbl_master', ['period_status' => 3]);

    //                     $this->db->where('idtbl_finacial_month', $expired_account_info->tbl_finacial_month_idtbl_finacial_month);
    //                     $this->db->where('tbl_finacial_year_idtbl_finacial_year', $expired_account_info->tbl_finacial_year_idtbl_finacial_year);
    //                     $this->db->update('tbl_finacial_month', ['activestatus' => 3]);
    //                 }

    //                 // Insert new master period
    //                 $this->db->insert('tbl_master', [
    //                     'status'        => 1,
    //                     'period_status' => 1,
    //                     'insertdatetime'=> $updatedatetime,
    //                     'tbl_user_idtbl_user'  => $userID,
    //                     'tbl_company_idtbl_company' => $companyid,
    //                     'tbl_company_branch_idtbl_company_branch' => $branchid,
    //                     'tbl_finacial_year_idtbl_finacial_year'   => $finacialyear,
    //                     'tbl_finacial_month_idtbl_finacial_month' => $finacialmonth
    //                 ]);
    //                 $activated_account_master_period = $this->db->insert_id();

    //                 // Financial year/month status update
    //                 $this->db->where('idtbl_finacial_year', $finacialyear);
    //                 $this->db->update('tbl_finacial_year', [
    //                     'actstatus'     => 1,
    //                     'updateuser'    => $userID,
    //                     'updatedatetime'=> $updatedatetime
    //                 ]);

    //                 $this->db->where('tbl_finacial_year_idtbl_finacial_year', $finacialyear);
    //                 $this->db->where('idtbl_finacial_month', $finacialmonth);
    //                 $this->db->update('tbl_finacial_month', [
    //                     'activestatus'  => 1,
    //                     'updateuser'    => $userID,
    //                     'updatedatetime'=> $updatedatetime
    //                 ]);

    //                 // Opening balance — check exist previous period → if exist insert/update opening balance for new period
    //                 if(!empty($expired_account_master_period)){
    //                     $this->_insertOrUpdateOpeningBalance(
    //                         $expired_account_master_period,  // from (previous)
    //                         $activated_account_master_period, // to (new)
    //                         $companyid, $branchid, $userID
    //                     );
    //                 }
    //             }
    //         }

    //         // ══════════════════════════════════════════
    //         // STATUS 2 — Close a Period
    //         // ══════════════════════════════════════════
    //         else if($status == 2){

    //             // Get Close period master record
    //             $closing_master = $this->db->get_where('tbl_master', [
    //                 'tbl_finacial_year_idtbl_finacial_year'   => $finacialyear,
    //                 'tbl_finacial_month_idtbl_finacial_month' => $finacialmonth,
    //                 'tbl_company_idtbl_company'               => $companyid,
    //                 'tbl_company_branch_idtbl_company_branch' => $branchid,
    //                 'status' => 1
    //             ])->row();

    //             if(empty($closing_master)){
    //                 throw new Exception('Period not found');
    //             }

    //             // period_status = 2 (closed)
    //             $this->db->where('idtbl_master', $closing_master->idtbl_master);
    //             $this->db->update('tbl_master', [
    //                 'period_status'  => 2,
    //                 'updateuser'     => $userID,
    //                 'updatedatetime' => $updatedatetime
    //             ]);

    //             $this->db->where('tbl_finacial_year_idtbl_finacial_year', $finacialyear);
    //             $this->db->where('idtbl_finacial_month', $finacialmonth);
    //             $this->db->update('tbl_finacial_month', [
    //                 'activestatus'   => 2,
    //                 'updateuser'     => $userID,
    //                 'updatedatetime' => $updatedatetime
    //             ]);

    //             // ═══════════════════════════════════════
    //             // KEY PART — Chain Recalculate
    //             // If December close January, February, March
    //             // open balances chain updated
    //             // ═══════════════════════════════════════
    //             $this->_chainRecalculateOpeningBalances(
    //                 $closing_master->idtbl_master,
    //                 $companyid, $branchid, $userID
    //             );
    //         }

    //         if($this->db->trans_status() === FALSE){
    //             throw new Exception('Database Error');
    //         }

    //         $this->db->trans_commit();

    //         $actionObj          = new stdClass();
    //         $actionObj->icon    = 'fas fa-save';
    //         $actionObj->title   = '';
    //         $actionObj->message = 'Record Updated Successfully';
    //         $actionObj->url     = '';
    //         $actionObj->target  = '_blank';
    //         $actionObj->type    = 'success';

    //         echo json_encode(['status' => 1, 'action' => json_encode($actionObj)]);

    //     } catch(Exception $e){
    //         $this->db->trans_rollback();

    //         $actionObj          = new stdClass();
    //         $actionObj->icon    = 'fas fa-exclamation-triangle';
    //         $actionObj->title   = '';
    //         $actionObj->message = 'Record Error: ' . $e->getMessage();
    //         $actionObj->url     = '';
    //         $actionObj->target  = '_blank';
    //         $actionObj->type    = 'danger';

    //         echo json_encode(['status' => 0, 'action' => json_encode($actionObj)]);
    //     }
    // }

    public function Currentperiodinsertupdate(){
        $this->db->trans_begin();
    
        $userID        = $_SESSION['userid'];
        $finacialyear  = $this->input->post('finacialyear');
        $finacialmonth = $this->input->post('finacialmonth');
        $status        = $this->input->post('status');      // 1=activate new, 2=close period
        $monthstatus   = $this->input->post('monthstatus');
        $companyid     = $_SESSION['companyid'];
        $branchid      = $_SESSION['branchid'];
        $updatedatetime = date('Y-m-d H:i:s');
        
        try {
    
            // ══════════════════════════════════════════
            // STATUS 1 — Activate New Period
            // ══════════════════════════════════════════
            if($status == 1){
                if($monthstatus == 0){
    
                    // ── Step 1: Get ALL currently active periods ──
                    // (period_status=1) for this company/branch
                    $this->db->where('tbl_company_idtbl_company', $companyid);
                    $this->db->where('tbl_company_branch_idtbl_company_branch', $branchid);
                    $this->db->where('status', 1);
                    $this->db->where('period_status', 1);
                    $active_periods = $this->db->get('tbl_master')->result();
                    
                    // Keep track of all currently active financial year IDs
                    // so we can suspend them if they differ from new year
                    $active_year_ids = [];
                    foreach($active_periods as $ap){
                        $active_year_ids[] = $ap->tbl_finacial_year_idtbl_finacial_year;
                    }
                    $active_year_ids = array_unique($active_year_ids);
    
                    // Most recent active period — used as opening balance source
                    // get_account_period() must return ORDER BY year startdate DESC, month DESC LIMIT 1
                    $expired_account_info = get_account_period($companyid, $branchid);
                    $expired_account_master_period = !empty($expired_account_info)
                        ? $expired_account_info->idtbl_master : '';
    
                    // ── Step 2: Suspend ALL active periods (period_status → 3) ──
                    foreach($active_periods as $ap){
                        $this->db->where('idtbl_master', $ap->idtbl_master);
                        $this->db->update('tbl_master', [
                            'period_status'  => 3,
                            'updateuser'     => $userID,
                            'updatedatetime' => $updatedatetime
                        ]);
    
                        // tbl_finacial_month activestatus → 3
                        $this->db->where('idtbl_finacial_month', $ap->tbl_finacial_month_idtbl_finacial_month);
                        $this->db->where('tbl_finacial_year_idtbl_finacial_year', $ap->tbl_finacial_year_idtbl_finacial_year);
                        $this->db->update('tbl_finacial_month', [
                            'activestatus'   => 3,
                            'updateuser'     => $userID,
                            'updatedatetime' => $updatedatetime
                        ]);
                    }
    
                    // ── Step 3: Suspend previous financial YEAR(s) actstatus → 3 ──
                    // Only years that are NOT the new activating year
                    foreach($active_year_ids as $old_year_id){
                        if($old_year_id != $finacialyear){
                            // This year belongs to old financial year → suspend it
                            $this->db->where('idtbl_finacial_year', $old_year_id);
                            $this->db->where('tbl_company_idtbl_company', $companyid);
                            $this->db->where('tbl_company_branch_idtbl_company_branch', $branchid);
                            $this->db->update('tbl_finacial_year', [
                                'actstatus'      => 3,  // suspended — was active last year
                                'updateuser'     => $userID,
                                'updatedatetime' => $updatedatetime
                            ]);
                        }
                    }
    
                    // ── Step 4: Insert new master period ──
                    $this->db->insert('tbl_master', [
                        'status'                                 => 1,
                        'period_status'                          => 1,
                        'insertdatetime'                         => $updatedatetime,
                        'tbl_user_idtbl_user'                    => $userID,
                        'tbl_company_idtbl_company'              => $companyid,
                        'tbl_company_branch_idtbl_company_branch'=> $branchid,
                        'tbl_finacial_year_idtbl_finacial_year'  => $finacialyear,
                        'tbl_finacial_month_idtbl_finacial_month'=> $finacialmonth
                    ]);
                    $activated_account_master_period = $this->db->insert_id();
    
                    // ── Step 5: Activate new financial YEAR actstatus → 1 ──
                    $this->db->where('idtbl_finacial_year', $finacialyear);
                    $this->db->where('tbl_company_idtbl_company', $companyid);
                    $this->db->where('tbl_company_branch_idtbl_company_branch', $branchid);
                    $this->db->update('tbl_finacial_year', [
                        'actstatus'      => 1,
                        'updateuser'     => $userID,
                        'updatedatetime' => $updatedatetime
                    ]);
    
                    // ── Step 6: Activate new financial MONTH activestatus → 1 ──
                    $this->db->where('tbl_finacial_year_idtbl_finacial_year', $finacialyear);
                    $this->db->where('idtbl_finacial_month', $finacialmonth);
                    $this->db->update('tbl_finacial_month', [
                        'activestatus'   => 1,
                        'updateuser'     => $userID,
                        'updatedatetime' => $updatedatetime
                    ]);
    
                    // ── Step 7: Opening balance carry forward ──
                    // Previous active period closing balance → new period opening balance
                    if(!empty($expired_account_master_period)){
                        $this->_insertOrUpdateOpeningBalance(
                            $expired_account_master_period,   // from (last active period)
                            $activated_account_master_period, // to   (new period)
                            $companyid, $branchid, $userID
                        );
                    }
                }
            }
    
            // ══════════════════════════════════════════
            // STATUS 2 — Close a Period
            // ══════════════════════════════════════════
            else if($status == 2){
    
                // Get closing period master record
                $closing_master = $this->db->get_where('tbl_master', [
                    'tbl_finacial_year_idtbl_finacial_year'   => $finacialyear,
                    'tbl_finacial_month_idtbl_finacial_month' => $finacialmonth,
                    'tbl_company_idtbl_company'               => $companyid,
                    'tbl_company_branch_idtbl_company_branch' => $branchid,
                    'status'                                  => 1
                ])->row();
                
                if(empty($closing_master)){
                    throw new Exception('Period not found');
                }
    
                // period_status = 2 (closed)
                $this->db->where('idtbl_master', $closing_master->idtbl_master);
                $this->db->update('tbl_master', [
                    'period_status'  => 2,
                    'updateuser'     => $userID,
                    'updatedatetime' => $updatedatetime
                ]);
    
                // tbl_finacial_month activestatus = 2
                $this->db->where('tbl_finacial_year_idtbl_finacial_year', $finacialyear);
                $this->db->where('idtbl_finacial_month', $finacialmonth);
                $this->db->update('tbl_finacial_month', [
                    'activestatus'   => 2,
                    'updateuser'     => $userID,
                    'updatedatetime' => $updatedatetime
                ]);
    
                // Check: all months in this financial year are now closed?
                // If yes → set tbl_finacial_year actstatus = 2 (year fully closed)
                $sql_year_check = "SELECT COUNT(*) AS open_count
                                FROM tbl_master m
                                WHERE m.tbl_company_idtbl_company = ?
                                AND m.tbl_company_branch_idtbl_company_branch = ?
                                AND m.tbl_finacial_year_idtbl_finacial_year = ?
                                AND m.status = 1
                                AND m.period_status IN (1, 3)"; // still active or suspended
    
                $open_count = $this->db->query($sql_year_check, [
                    $companyid,
                    $branchid,
                    $finacialyear
                ])->row()->open_count;
                
                if($open_count == 0){
                    // All months in this year are closed → close the year too
                    $this->db->where('idtbl_finacial_year', $finacialyear);
                    $this->db->where('tbl_company_idtbl_company', $companyid);
                    $this->db->where('tbl_company_branch_idtbl_company_branch', $branchid);
                    $this->db->update('tbl_finacial_year', [
                        'actstatus'      => 2,  // year fully closed
                        'updateuser'     => $userID,
                        'updatedatetime' => $updatedatetime
                    ]);
                }
    
                // Chain recalculate opening balances for all subsequent periods
                // Handles cross-year boundary (2025/2026 March → 2026/2027 April)
                $this->_chainRecalculateOpeningBalances(
                    $closing_master->idtbl_master,
                    $companyid, $branchid, $userID
                );
            }
            
            if($this->db->trans_status() === FALSE){
                throw new Exception('Database Error');
            }
    
            $this->db->trans_commit();
    
            $actionObj          = new stdClass();
            $actionObj->icon    = 'fas fa-save';
            $actionObj->title   = '';
            $actionObj->message = 'Record Updated Successfully';
            $actionObj->url     = '';
            $actionObj->target  = '_blank';
            $actionObj->type    = 'success';
    
            echo json_encode(['status' => 1, 'action' => json_encode($actionObj)]);
    
        } catch(Exception $e){
            $this->db->trans_rollback();
    
            $actionObj          = new stdClass();
            $actionObj->icon    = 'fas fa-exclamation-triangle';
            $actionObj->title   = '';
            $actionObj->message = 'Record Error: ' . $e->getMessage();
            $actionObj->url     = '';
            $actionObj->target  = '_blank';
            $actionObj->type    = 'danger';
    
            echo json_encode(['status' => 0, 'action' => json_encode($actionObj)]);
        }
    }

    /**
     * Previous period closing balance → Next period opening balance
     * If Already exists UPDATE, Or INSERT
     */
    // private function _insertOrUpdateOpeningBalance($from_master_id, $to_master_id, $companyid, $branchid, $userID){
    //     // $sql = "SELECT 
    //     //             drv_open.tbl_account_idtbl_account,
    //     //             (drv_open.openbal +
    //     //                 CASE 
    //     //                     WHEN cat.tbl_account_transactiontype_idtbl_account_transactiontype = 1 
    //     //                     THEN  IFNULL(drv_crdr.dr_accamount, 0) - IFNULL(drv_crdr.cr_accamount, 0)
    //     //                     WHEN cat.tbl_account_transactiontype_idtbl_account_transactiontype = 2 
    //     //                     THEN  IFNULL(drv_crdr.cr_accamount, 0) - IFNULL(drv_crdr.dr_accamount, 0)
    //     //                     ELSE 0
    //     //                 END
    //     //             ) AS new_openbal
    //     //         FROM tbl_account_open_bal drv_open
    //     //         INNER JOIN tbl_account acc 
    //     //             ON acc.idtbl_account = drv_open.tbl_account_idtbl_account
    //     //             AND acc.status = 1
    //     //         INNER JOIN tbl_account_category cat 
    //     //             ON cat.idtbl_account_category = acc.tbl_account_category_idtbl_account_category
    //     //         LEFT JOIN (
    //     //             SELECT 
    //     //                 tbl_account_idtbl_account,
    //     //                 SUM(accamount * (crdr = 'D')) AS dr_accamount,
    //     //                 SUM(accamount * (crdr = 'C')) AS cr_accamount
    //     //             FROM tbl_account_transaction
    //     //             WHERE tbl_master_idtbl_master = ?
    //     //             GROUP BY tbl_account_idtbl_account
    //     //         ) AS drv_crdr 
    //     //             ON drv_open.tbl_account_idtbl_account = drv_crdr.tbl_account_idtbl_account
    //     //         WHERE drv_open.tbl_master_idtbl_master = ?
    //     //         AND drv_open.status = 1";
    //     $sql = "SELECT 
    //         acc.idtbl_account AS tbl_account_idtbl_account,
    //         cat.tbl_account_transactiontype_idtbl_account_transactiontype AS account_type,
    //         (IFNULL(drv_open.openbal, 0) +
    //             CASE 
    //                 WHEN cat.tbl_account_transactiontype_idtbl_account_transactiontype = 1 
    //                 THEN IFNULL(drv_crdr.dr_accamount, 0) - IFNULL(drv_crdr.cr_accamount, 0)
    //                 WHEN cat.tbl_account_transactiontype_idtbl_account_transactiontype = 2 
    //                 THEN IFNULL(drv_crdr.cr_accamount, 0) - IFNULL(drv_crdr.dr_accamount, 0)
    //                 ELSE 0
    //             END
    //         ) AS new_openbal
    //     FROM tbl_account acc
    //     INNER JOIN tbl_account_category cat 
    //         ON cat.idtbl_account_category = acc.tbl_account_category_idtbl_account_category
    //     LEFT JOIN tbl_account_open_bal drv_open 
    //         ON acc.idtbl_account = drv_open.tbl_account_idtbl_account
    //         AND drv_open.tbl_master_idtbl_master = ?
    //         AND drv_open.status = 1
    //     LEFT JOIN (
    //         SELECT 
    //             tbl_account_idtbl_account,
    //             SUM(accamount * (crdr = 'D')) AS dr_accamount,
    //             SUM(accamount * (crdr = 'C')) AS cr_accamount
    //         FROM tbl_account_transaction
    //         WHERE tbl_master_idtbl_master = ?
    //         GROUP BY tbl_account_idtbl_account
    //     ) AS drv_crdr 
    //         ON acc.idtbl_account = drv_crdr.tbl_account_idtbl_account
    //     WHERE acc.status = 1";

    //     $new_bals = $this->db->query($sql, [
    //         $from_master_id,
    //         $from_master_id
    //     ])->result();
        
    //     if(empty($new_bals)) return;

    //     $now = date('Y-m-d H:i:s');

    //     foreach($new_bals as $bal){
    //         $final_bal = $bal->new_openbal;
    //         $cr_dr_status = 'D';

    //         if ($bal->account_type == 1) { // Debit Type Accounts
    //             if ($final_bal < 0) {
    //                 $cr_dr_status = 'C'; 
    //                 $final_bal = abs($final_bal); 
    //             } else {
    //                 $cr_dr_status = 'D';
    //             }
    //         } 
    //         else if ($bal->account_type == 2) { // Credit Type Accounts
    //             if ($final_bal < 0) {
    //                 $cr_dr_status = 'D';
    //                 $final_bal = abs($final_bal); 
    //             } else {
    //                 $cr_dr_status = 'C';
    //             }
    //         }

    //         // Check Already exists
    //         $exists = $this->db->get_where('tbl_account_open_bal', [
    //             'tbl_master_idtbl_master'  => $to_master_id,
    //             'tbl_account_idtbl_account'=> $bal->tbl_account_idtbl_account,
    //             'status' => 1
    //         ])->row();

    //         if(!empty($exists)){
    //             // UPDATE — If already inserted (multi-open periods case)
    //             $this->db->where('tbl_master_idtbl_master',   $to_master_id);
    //             $this->db->where('tbl_account_idtbl_account', $bal->tbl_account_idtbl_account);
    //             $this->db->update('tbl_account_open_bal', [
    //                 'openbal'        => $bal->new_openbal,
    //                 'creditdebit'    => $cr_dr_status,
    //                 'updateuser'     => $userID,
    //                 'updatedatetime' => $now
    //             ]);
    //         } else {
    //             // INSERT — first time
    //             $this->db->insert('tbl_account_open_bal', [
    //                 'applydate'          => date('Y-m-d'),
    //                 'openbal'            => $bal->new_openbal,
    //                 'creditdebit'        => $cr_dr_status,
    //                 'status'             => 1,
    //                 'insertdatetime'     => $now,
    //                 'tbl_user_idtbl_user'=> $userID,
    //                 'tbl_account_idtbl_account' => $bal->tbl_account_idtbl_account,
    //                 'tbl_master_idtbl_master'   => $to_master_id,
    //                 'tbl_company_idtbl_company' => $companyid,
    //                 'tbl_company_branch_idtbl_company_branch' => $branchid
    //             ]);
    //         }
    //     }
    // }
    private function _insertOrUpdateOpeningBalance(
        $from_master_id, $to_master_id,
        $companyid, $branchid, $userID,
        $is_year_end = false
    ){
        // Year end නම් IN(4) EX(2) skip — category ID direct use
        // Monthly නම් ALL accounts
        $year_end_filter = $is_year_end
            ? "AND cat.idtbl_account_category NOT IN (2, 4)"
            : "";

        $sql = "SELECT 
                    acc.idtbl_account AS tbl_account_idtbl_account,
                    cat.tbl_account_transactiontype_idtbl_account_transactiontype AS account_type,
                    (IFNULL(drv_open.openbal, 0) +
                        CASE 
                            WHEN cat.tbl_account_transactiontype_idtbl_account_transactiontype = 1 
                            THEN IFNULL(drv_crdr.dr_accamount, 0) - IFNULL(drv_crdr.cr_accamount, 0)
                            WHEN cat.tbl_account_transactiontype_idtbl_account_transactiontype = 2 
                            THEN IFNULL(drv_crdr.cr_accamount, 0) - IFNULL(drv_crdr.dr_accamount, 0)
                            ELSE 0
                        END
                    ) AS new_openbal
                FROM tbl_account acc
                INNER JOIN tbl_account_category cat 
                    ON cat.idtbl_account_category = acc.tbl_account_category_idtbl_account_category
                LEFT JOIN tbl_account_open_bal drv_open 
                    ON acc.idtbl_account = drv_open.tbl_account_idtbl_account
                    AND drv_open.tbl_master_idtbl_master = ?
                    AND drv_open.status = 1
                LEFT JOIN (
                    SELECT 
                        tbl_account_idtbl_account,
                        SUM(accamount * (crdr = 'D')) AS dr_accamount,
                        SUM(accamount * (crdr = 'C')) AS cr_accamount
                    FROM tbl_account_transaction
                    WHERE tbl_master_idtbl_master = ?
                    GROUP BY tbl_account_idtbl_account
                ) AS drv_crdr 
                    ON acc.idtbl_account = drv_crdr.tbl_account_idtbl_account
                WHERE acc.status = 1
                $year_end_filter";

        $new_bals = $this->db->query($sql, [
            $from_master_id,
            $from_master_id
        ])->result();

        if(empty($new_bals)) return;

        $now = date('Y-m-d H:i:s');

        foreach($new_bals as $bal){
            $final_bal    = $bal->new_openbal;
            $cr_dr_status = 'D';

            if($bal->account_type == 1){          // AS — Debit normal
                $cr_dr_status = ($final_bal < 0) ? 'C' : 'D';
                $final_bal    = abs($final_bal);
            } else if($bal->account_type == 2){   // LI, EQ — Credit normal
                $cr_dr_status = ($final_bal < 0) ? 'D' : 'C';
                $final_bal    = abs($final_bal);
            }

            $exists = $this->db->get_where('tbl_account_open_bal', [
                'tbl_master_idtbl_master'   => $to_master_id,
                'tbl_account_idtbl_account' => $bal->tbl_account_idtbl_account,
                'status'                    => 1
            ])->row();

            if(!empty($exists)){
                $this->db->where('tbl_master_idtbl_master',   $to_master_id);
                $this->db->where('tbl_account_idtbl_account', $bal->tbl_account_idtbl_account);
                $this->db->update('tbl_account_open_bal', [
                    'openbal'        => $final_bal,
                    'creditdebit'    => $cr_dr_status,
                    'updateuser'     => $userID,
                    'updatedatetime' => $now
                ]);
            } else {
                if($final_bal>0){
                    $this->db->insert('tbl_account_open_bal', [
                        'applydate'                              => date('Y-m-d'),
                        'openbal'                                => $final_bal,
                        'creditdebit'                            => $cr_dr_status,
                        'status'                                 => 1,
                        'insertdatetime'                         => $now,
                        'tbl_user_idtbl_user'                    => $userID,
                        'tbl_account_idtbl_account'              => $bal->tbl_account_idtbl_account,
                        'tbl_master_idtbl_master'                => $to_master_id,
                        'tbl_company_idtbl_company'              => $companyid,
                        'tbl_company_branch_idtbl_company_branch'=> $branchid
                    ]);
                }
            }
        }
    }
    /**
     * December closed → January, February, March
     * opening balances chain recalculate
     * 
     * Dec(closed) → Jan(open) → Feb(open) → Mar(active)
     *                ↑ update    ↑ update    ↑ update
     */
    // private function _chainRecalculateOpeningBalances($closed_master_id, $companyid, $branchid, $userID){
    //     // Step 1: Get closed period detail
    //     $closed = $this->db->get_where('tbl_master', [
    //         'idtbl_master' => $closed_master_id
    //     ])->row();

    //     if(empty($closed)) return;

    //     // Step 2: Get open/active periods after the closed period (if any) — order by financial year & month ASC
    //     // (chain order — oldest first)
    //     $sql_next = "SELECT m.idtbl_master,
    //                         m.tbl_finacial_year_idtbl_finacial_year,
    //                         m.tbl_finacial_month_idtbl_finacial_month
    //                 FROM tbl_master m
    //                 WHERE m.tbl_company_idtbl_company = ?
    //                 AND m.tbl_company_branch_idtbl_company_branch = ?
    //                 AND m.status = 1
    //                 AND m.period_status IN (1, 2, 3)
    //                 AND (
    //                     m.tbl_finacial_year_idtbl_finacial_year > ?
    //                     OR (
    //                         m.tbl_finacial_year_idtbl_finacial_year = ?
    //                         AND m.tbl_finacial_month_idtbl_finacial_month > ?
    //                     )
    //                 )
    //                 ORDER BY m.tbl_finacial_year_idtbl_finacial_year ASC,
    //                         m.tbl_finacial_month_idtbl_finacial_month ASC";

    //     $next_periods = $this->db->query($sql_next, [
    //         $companyid,
    //         $branchid,
    //         $closed->tbl_finacial_year_idtbl_finacial_year,
    //         $closed->tbl_finacial_year_idtbl_finacial_year,
    //         $closed->tbl_finacial_month_idtbl_finacial_month
    //     ])->result();
        
    //     if(empty($next_periods)) return;

    //     // Step 3: Chain — from = closed, to = next, then to becomes from
    //     $from_master_id = $closed_master_id;

    //     foreach($next_periods as $next){
    //         $this->_insertOrUpdateOpeningBalance(
    //             $from_master_id,       // previous period
    //             $next->idtbl_master,   // next period
    //             $companyid, $branchid, $userID
    //         );
    //         // Chain — next period becomes previous for iteration
    //         $from_master_id = $next->idtbl_master;
    //     }
    // }
    /**
     * _chainRecalculateOpeningBalances() — modified
     * Detects year boundary and applies correct logic
     */
    private function _chainRecalculateOpeningBalances(
        $closed_master_id, $companyid, $branchid, $userID
    ){
        $closed = $this->db->get_where('tbl_master', [
            'idtbl_master' => $closed_master_id
        ])->row();

        if(empty($closed)) return;

        // ── Detect: is this a year-end close? ──
        // Check if any next period belongs to a DIFFERENT financial year
        // Use startdate compare (cross-year safe)
        $sql_next = "SELECT 
                        m.idtbl_master,
                        m.tbl_finacial_year_idtbl_finacial_year,
                        m.tbl_finacial_month_idtbl_finacial_month,
                        fy.startdate AS year_startdate,
                        fy_closed.startdate AS closed_year_startdate
                    FROM tbl_master m
                    INNER JOIN tbl_finacial_year fy 
                        ON fy.idtbl_finacial_year = m.tbl_finacial_year_idtbl_finacial_year
                    INNER JOIN tbl_finacial_year fy_closed 
                        ON fy_closed.idtbl_finacial_year = ?
                    INNER JOIN tbl_finacial_month fm_closed 
                        ON fm_closed.idtbl_finacial_month = ?
                    WHERE m.tbl_company_idtbl_company = ?
                    AND m.tbl_company_branch_idtbl_company_branch = ?
                    AND m.status = 1
                    AND m.period_status IN (1, 2, 3)
                    AND (
                        fy.startdate > fy_closed.startdate
                        OR (
                            fy.startdate = fy_closed.startdate
                            AND m.tbl_finacial_month_idtbl_finacial_month > ?
                        )
                    )
                    ORDER BY fy.startdate ASC, m.tbl_finacial_month_idtbl_finacial_month ASC";

        $next_periods = $this->db->query($sql_next, [
            $closed->tbl_finacial_year_idtbl_finacial_year,
            $closed->tbl_finacial_month_idtbl_finacial_month,
            $companyid,
            $branchid,
            $closed->tbl_finacial_month_idtbl_finacial_month
        ])->result();

        if(empty($next_periods)) return;

        $from_master_id = $closed_master_id;

        foreach($next_periods as $next){

            // ── Year boundary check ──
            $is_year_end = ($next->year_startdate != $next->closed_year_startdate);

            if($is_year_end){
                // Year end: Balance Sheet only carry forward
                $this->_insertOrUpdateOpeningBalance(
                    $from_master_id,
                    $next->idtbl_master,
                    $companyid, $branchid, $userID,
                    true   // ← is_year_end = true
                );

                // Net Profit/Loss → Equity account
                $this->_closeYearEndPnL(
                    $from_master_id,
                    $next->idtbl_master,
                    $companyid, $branchid, $userID
                );

            } else {
                // Normal monthly: ALL accounts carry forward
                $this->_insertOrUpdateOpeningBalance(
                    $from_master_id,
                    $next->idtbl_master,
                    $companyid, $branchid, $userID,
                    false  // ← is_year_end = false
                );
            }

            $from_master_id = $next->idtbl_master;
        }
    }

    private function _closeYearEndPnL(
        $from_master_id, $to_master_id,
        $companyid, $branchid, $userID
    ){
        // IN = idtbl_account_category 4 (Credit type)
        // EX = idtbl_account_category 2 (Debit type)
        $sql_pnl = "SELECT
                        SUM(CASE 
                            WHEN cat.idtbl_account_category = 4
                            THEN (
                                IFNULL(drv_open.openbal, 0)
                                + IFNULL(drv_crdr.cr_accamount, 0) 
                                - IFNULL(drv_crdr.dr_accamount, 0)
                            )
                            ELSE 0
                        END) AS total_income,
                        SUM(CASE 
                            WHEN cat.idtbl_account_category = 2
                            THEN (
                                IFNULL(drv_open.openbal, 0)
                                + IFNULL(drv_crdr.dr_accamount, 0) 
                                - IFNULL(drv_crdr.cr_accamount, 0)
                            )
                            ELSE 0
                        END) AS total_expense
                    FROM tbl_account acc
                    INNER JOIN tbl_account_category cat 
                        ON cat.idtbl_account_category = acc.tbl_account_category_idtbl_account_category
                    LEFT JOIN tbl_account_open_bal drv_open 
                        ON acc.idtbl_account = drv_open.tbl_account_idtbl_account
                        AND drv_open.tbl_master_idtbl_master = ?
                        AND drv_open.status = 1
                    LEFT JOIN (
                        SELECT 
                            tbl_account_idtbl_account,
                            SUM(accamount * (crdr = 'D')) AS dr_accamount,
                            SUM(accamount * (crdr = 'C')) AS cr_accamount
                        FROM tbl_account_transaction
                        WHERE tbl_master_idtbl_master = ?
                        GROUP BY tbl_account_idtbl_account
                    ) AS drv_crdr 
                        ON acc.idtbl_account = drv_crdr.tbl_account_idtbl_account
                    WHERE acc.status = 1
                    AND cat.idtbl_account_category IN (2, 4)";

        $pnl = $this->db->query($sql_pnl, [
            $from_master_id,
            $from_master_id
        ])->row();

        $net_profit = ($pnl->total_income - $pnl->total_expense);
        // Positive = Profit → CR to equity
        // Negative = Loss   → DR to equity

        if($net_profit == 0) return;

        // ── Get Profit & Loss Account ──────────────────────────────────────────────
        $this->db->where('tbl_account_allocation.companybank', $companyid);
        $this->db->where('tbl_account_allocation.branchcompanybank', $branchid);
        $this->db->where('tbl_account.specialcate', 40); // P&L Special Category
        $this->db->where('tbl_account.status', 1);
        $this->db->where('tbl_account_allocation.status', 1);
        $this->db->where('tbl_account_allocation.tbl_account_idtbl_account is NOT NULL', NULL, FALSE);
        $this->db->select('`tbl_account`.`idtbl_account`, `tbl_account`.`accountno`, `tbl_account`.`accountname`');
        $this->db->from('tbl_account');
        $this->db->join('tbl_account_allocation', 'tbl_account_allocation.tbl_account_idtbl_account = tbl_account.idtbl_account', 'left');

        $equity_account = $this->db->get()->row();
        // $sql_equity = "SELECT 
        //                     acc.idtbl_account,
        //                     cat.tbl_account_transactiontype_idtbl_account_transactiontype AS account_type
        //             FROM tbl_account acc
        //             INNER JOIN tbl_account_category cat 
        //                 ON cat.idtbl_account_category = acc.tbl_account_category_idtbl_account_category
        //             WHERE acc.status = 1
        //             AND cat.idtbl_account_category = 5      -- EQ = 5
        //             AND acc.tbl_company_idtbl_company = ?
        //             AND acc.tbl_company_branch_idtbl_company_branch = ?
        //             ORDER BY acc.idtbl_account ASC
        //             LIMIT 1";

        // $equity_account = $this->db->query($sql_equity, [
        //     $companyid, $branchid
        // ])->row();

        if(empty($equity_account)) return;

        $cr_dr_status = ($net_profit >= 0) ? 'C' : 'D';
        $final_bal    = abs($net_profit);
        $now          = date('Y-m-d H:i:s');

        $exists = $this->db->get_where('tbl_account_open_bal', [
            'tbl_master_idtbl_master'   => $to_master_id,
            'tbl_account_idtbl_account' => $equity_account->idtbl_account,
            'status'                    => 1
        ])->row();

        if(!empty($exists)){
            $new_equity_bal = $exists->openbal + $final_bal;
            $this->db->where('tbl_master_idtbl_master',   $to_master_id);
            $this->db->where('tbl_account_idtbl_account', $equity_account->idtbl_account);
            $this->db->update('tbl_account_open_bal', [
                'openbal'        => $new_equity_bal,
                'creditdebit'    => $cr_dr_status,
                'updateuser'     => $userID,
                'updatedatetime' => $now
            ]);
        } else {
            if($final_bal>0){
                $this->db->insert('tbl_account_open_bal', [
                    'applydate'                              => date('Y-m-d'),
                    'openbal'                                => $final_bal,
                    'creditdebit'                            => $cr_dr_status,
                    'status'                                 => 1,
                    'insertdatetime'                         => $now,
                    'tbl_user_idtbl_user'                    => $userID,
                    'tbl_account_idtbl_account'              => $equity_account->idtbl_account,
                    'tbl_master_idtbl_master'                => $to_master_id,
                    'tbl_company_idtbl_company'              => $companyid,
                    'tbl_company_branch_idtbl_company_branch'=> $branchid
                ]);
            }
        }
    }
    public function Getfinancialyear(){
        $companyid=$_SESSION['companyid'];
        $branchid=$_SESSION['branchid'];    

        $this->db->select('`idtbl_finacial_year`, `desc`');
        $this->db->from('tbl_finacial_year');
        $this->db->where('status', 1);
        $this->db->where('tbl_company_idtbl_company', $companyid);
        $this->db->where('tbl_company_branch_idtbl_company_branch', $branchid);

        return $respond=$this->db->get();
    }
    public function Getmonthlistaccoyear(){
        $recordID=$this->input->post('recordID');

        $this->db->select('`idtbl_finacial_month`, `month`, `monthname`, `activestatus`');
        $this->db->from('tbl_finacial_month');
        $this->db->where('tbl_finacial_year_idtbl_finacial_year', $recordID);
        $this->db->where('status', 1);

        $respond=$this->db->get();

        echo json_encode($respond->result());
    }
}