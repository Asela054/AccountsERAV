<?php
class Issuechequeinfo extends CI_Model{
    public function Issuechequestatus(){
        $userID=$_SESSION['userid'];
        $recordID=$this->input->post('recordID');;
        $updatedatetime=date('Y-m-d H:i:s');

        //Check Petty Cash Reimburse
        $this->db->select('COUNT(*) AS `checkcount`, `tbl_pettycash_reimburse`.`idtbl_pettycash_reimburse`, `tbl_pettycash_reimburse`.`tbl_company_idtbl_company`, `tbl_pettycash_reimburse`.`tbl_company_branch_idtbl_company_branch`, `tbl_pettycash_reimburse`.`reimursebal`');
        $this->db->from('tbl_cheque_issue');
        $this->db->join('tbl_pettycash_reimburse', 'tbl_pettycash_reimburse.chequeno = tbl_cheque_issue.chequeno', 'left');
        $this->db->where('idtbl_cheque_issue', $recordID);
        $respondcheck=$this->db->get();

        $this->db->select('COUNT(*) AS `checkcountabove`');
        $this->db->from('tbl_pettycash_reimburse');
        $this->db->where('tbl_cheque_issue_idtbl_cheque_issue>', $recordID);
        $respondcheckabove=$this->db->get();

        if($respondcheck->row(0)->checkcount>0 && $respondcheckabove->row(0)->checkcountabove==0){
            $this->db->trans_begin();

            $reimburseID=$respondcheck->row(0)->idtbl_pettycash_reimburse;

            //Issue check mark as return
            $dataissuecheque = array(
                'chequereturn'=> '1', 
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );
    
            $this->db->where('idtbl_cheque_issue', $recordID);
            $this->db->update('tbl_cheque_issue', $dataissuecheque);

            //Reimbursement set deactivate
            $datareimburse = array(
                'status'=> '2', 
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );
    
            $this->db->where('idtbl_pettycash_reimburse', $reimburseID);
            $this->db->update('tbl_pettycash_reimburse', $datareimburse);
            
            //Change reimbursement status
            $this->db->select('tbl_pettycash_idtbl_pettycash');
            $this->db->from('tbl_pettycash_reimburse_has_tbl_pettycash');
            $this->db->where('tbl_pettycash_reimburse_idtbl_pettycash_reimburse', $reimburseID);
            $respondrepetty=$this->db->get();

            foreach($respondrepetty->result() as $rowrepetty){
                $datapettycash = array(
                    'reimbursestatus'=> '0', 
                    'updateuser'=> $userID, 
                    'updatedatetime'=> $updatedatetime
                );
        
                $this->db->where('idtbl_pettycash', $rowrepetty->tbl_pettycash_idtbl_pettycash);
                $this->db->update('tbl_pettycash', $datapettycash);
            }

            // Check Journal Entry
            $this->db->select('tbl_account_transaction.*');
            $this->db->from('tbl_account_transaction');
            $this->db->join('tbl_pettycash_reimburse', 'tbl_pettycash_reimburse.reimbursecode = tbl_account_transaction.trabatchotherno', 'left');
            $this->db->where('tbl_pettycash_reimburse.idtbl_pettycash_reimburse', $reimburseID);

            $respondtra=$this->db->get();

            $prefix=trans_prefix($respondcheck->row(0)->tbl_company_idtbl_company, $respondcheck->row(0)->tbl_company_branch_idtbl_company_branch);
            $batchno=tr_batch_num($prefix, $respondcheck->row(0)->tbl_company_branch_idtbl_company_branch);
            $masterdata=get_account_period($respondcheck->row(0)->tbl_company_idtbl_company, $respondcheck->row(0)->tbl_company_branch_idtbl_company_branch);
            $masterID=$masterdata->idtbl_master;

            $today=date('Y-m-d');

            $i=1;
            foreach($respondtra->result() as $rowdatalist){
                if($rowdatalist->crdr=='C'){$crdr='D';}
                else{$crdr='C';}

                $data = array(
                    'tradate'=> $today, 
                    'batchno'=> $batchno, 
                    'trabatchotherno'=> $rowdatalist->trabatchotherno, 
                    'tratype'=> 'R', 
                    'seqno'=> $i, 
                    'crdr'=> $crdr, 
                    'accamount'=> $rowdatalist->accamount, 
                    'narration'=> $rowdatalist->narration, 
                    'totamount'=> $rowdatalist->totamount, 
                    'reversstatus'=> '1', 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_account_idtbl_account'=> $rowdatalist->tbl_account_idtbl_account,
                    'tbl_master_idtbl_master'=> $masterID,
                    'tbl_company_idtbl_company'=> $rowdatalist->tbl_company_idtbl_company,
                    'tbl_company_branch_idtbl_company_branch'=> $rowdatalist->tbl_company_branch_idtbl_company_branch
                );
                $this->db->insert('tbl_account_transaction', $data);
        
                $datafull = array(
                    'tradate'=> $today, 
                    'batchno'=> $batchno, 
                    'tratype'=> 'R', 
                    'crdr'=> $crdr, 
                    'accamount'=> $rowdatalist->accamount, 
                    'narration'=> $rowdatalist->narration, 
                    'totamount'=> $rowdatalist->totamount, 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_account_idtbl_account'=> $rowdatalist->tbl_account_idtbl_account,
                    'tbl_master_idtbl_master'=> $masterID,
                    'tbl_company_idtbl_company'=> $rowdatalist->tbl_company_idtbl_company,
                    'tbl_company_branch_idtbl_company_branch'=> $rowdatalist->tbl_company_branch_idtbl_company_branch
                );
                $this->db->insert('tbl_account_transaction_full', $datafull);

                $i++;
            }

            //Set delete petty cash summery record
            $datareimburse = array(
                'status'=> '3', 
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );
    
            $this->db->where('tbl_pettycash_reimburse_idtbl_pettycash_reimburse', $reimburseID);
            $this->db->update('tbl_pettycash_summary', $datareimburse);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-save';
                $actionObj->title='';
                $actionObj->message='Record Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='success';

                $actionJSON=json_encode($actionObj);
                
                $obj=new stdClass();
                $obj->status=1;
                $obj->action=$actionJSON;

                echo json_encode($obj);
            } else {
                $this->db->trans_rollback();

                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);
                
                $obj=new stdClass();
                $obj->status=0;
                $obj->action=$actionJSON;

                echo json_encode($obj);
            }
        }
        else if($respondcheck->row(0)->checkcount>0 && $respondcheckabove->row(0)->checkcountabove>0){
            $actionObj=new stdClass();
            $actionObj->icon='fas fa-exclamation-triangle';
            $actionObj->title='';
            $actionObj->message='Record Error, You can`t return this cheque because reimbursement entered after this cheque.';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='danger';

            $actionJSON=json_encode($actionObj);
            
            $obj=new stdClass();
            $obj->status=0;
            $obj->action=$actionJSON;

            echo json_encode($obj);
        }
        else{
            $this->db->trans_begin();

            // Update tbl_receivable
            $data = array(
                'chequereturn' => '1',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_cheque_issue', $recordID);
            $this->db->update('tbl_cheque_issue', $data);

            //Check payment settle info
            $this->db->select('tbl_account_paysettle_idtbl_account_paysettle');
            $this->db->from('tbl_account_paysettle_has_tbl_cheque_issue');
            $this->db->where('tbl_cheque_issue_idtbl_cheque_issue', $recordID);

            $respondcheckpay=$this->db->get();

            $paymentsettleID=$respondcheckpay->row(0)->tbl_account_paysettle_idtbl_account_paysettle;

            // Update tbl_account_paysettle
            $datapay = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_account_paysettle', $paymentsettleID);
            $this->db->update('tbl_account_paysettle', $datapay);

            // Update tbl_account_paysettle_info
            $datapaydetail = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('tbl_account_paysettle_idtbl_account_paysettle', $paymentsettleID);
            $this->db->update('tbl_account_paysettle_info', $datapaydetail);

            //Check Company info
            $this->db->select('tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch');
            $this->db->from('tbl_account_paysettle');
            $this->db->where('idtbl_account_paysettle', $paymentsettleID);

            $respondcombra=$this->db->get();

            // Check Journal Entry
            $this->db->select('tbl_account_transaction.*');
            $this->db->from('tbl_account_transaction');
            $this->db->join('tbl_receivable', 'tbl_receivable.batchno = tbl_account_transaction.trabatchotherno', 'left');
            $this->db->where('tbl_receivable.idtbl_receivable', $recordID);

            $respondtra=$this->db->get();

            $prefix=trans_prefix($respondcombra->row(0)->tbl_company_idtbl_company, $respondcombra->row(0)->tbl_company_branch_idtbl_company_branch);
            $batchno=tr_batch_num($prefix, $respondcombra->row(0)->tbl_company_branch_idtbl_company_branch);
            $masterdata=get_account_period($respondcombra->row(0)->tbl_company_idtbl_company, $respondcombra->row(0)->tbl_company_branch_idtbl_company_branch);
            $masterID=$masterdata->idtbl_master;

            $today=date('Y-m-d');

            $i=1;
            foreach($respondtra->result() as $rowdatalist){
                if($rowdatalist->crdr=='C'){$crdr='D';}
                else{$crdr='C';}

                $data = array(
                    'tradate'=> $today, 
                    'batchno'=> $batchno, 
                    'trabatchotherno'=> $rowdatalist->trabatchotherno, 
                    'tratype'=> 'R', 
                    'seqno'=> $i, 
                    'crdr'=> $crdr, 
                    'accamount'=> $rowdatalist->accamount, 
                    'narration'=> $rowdatalist->narration, 
                    'totamount'=> $rowdatalist->totamount, 
                    'reversstatus'=> '1', 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_account_idtbl_account'=> $rowdatalist->tbl_account_idtbl_account,
                    'tbl_master_idtbl_master'=> $masterID,
                    'tbl_company_idtbl_company'=> $rowdatalist->tbl_company_idtbl_company,
                    'tbl_company_branch_idtbl_company_branch'=> $rowdatalist->tbl_company_branch_idtbl_company_branch
                );
                $this->db->insert('tbl_account_transaction', $data);
        
                $datafull = array(
                    'tradate'=> $today, 
                    'batchno'=> $batchno, 
                    'tratype'=> 'R', 
                    'crdr'=> $crdr, 
                    'accamount'=> $rowdatalist->accamount, 
                    'narration'=> $rowdatalist->narration, 
                    'totamount'=> $rowdatalist->totamount, 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_user_idtbl_user'=> $userID,
                    'tbl_account_idtbl_account'=> $rowdatalist->tbl_account_idtbl_account,
                    'tbl_master_idtbl_master'=> $masterID,
                    'tbl_company_idtbl_company'=> $rowdatalist->tbl_company_idtbl_company,
                    'tbl_company_branch_idtbl_company_branch'=> $rowdatalist->tbl_company_branch_idtbl_company_branch
                );
                $this->db->insert('tbl_account_transaction_full', $datafull);

                $i++;
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-save';
                $actionObj->title='';
                $actionObj->message='Record Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='success';

                $actionJSON=json_encode($actionObj);
                
                $obj=new stdClass();
                $obj->status=1;
                $obj->action=$actionJSON;

                echo json_encode($obj);
            } else {
                $this->db->trans_rollback();

                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);
                
                $obj=new stdClass();
                $obj->status=0;
                $obj->action=$actionJSON;

                echo json_encode($obj);
            }
        }    
    }
    public function Chequeprint($x) {
        $issuechequeID = $x;
        $this->db->select('*');
        $this->db->from('tbl_cheque_issue');
        $this->db->where('idtbl_cheque_issue', $issuechequeID);
        $respond=$this->db->get();

        $rupeetext=$this->Issuechequeinfo->ConvertRupeeToText(round($respond->row(0)->amount, 2));
        
        // Default data
        $default = array(
            'date' => $respond->row(0)->chedate,
            'payee_name' => 'Cash',
            'amount_words' => $rupeetext,
            'amount_figures' => round($respond->row(0)->amount, 2),
            'is_crossed' => true // A/C Payee Only
        );

        $data = array_merge($default);

        // Exact Sri Lankan Cheque Dimensions (7.25in x 3.5in)
        $w = '184.15mm';
        $h = '88.9mm';

        // // Date formatting with non-breaking spaces to prevent wrapping
        // $dateStr = date('dmY', strtotime($data['date']));
        // $formattedDate = implode('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', str_split($dateStr));

        // 1. Get date parts: Day (2), Month (2), Century (2), Year (2)
        $day   = date('dm', strtotime($data['date'])); // e.g., "1812"
        $year2 = date('y', strtotime($data['date']));  // e.g., "25" for 2025

        // 2. Format with standard spacing for the first 4 digits
        $part1 = implode('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', str_split($day));

        // 3. Create a larger gap to skip the pre-printed "2 0"
        // Increase the number of &nbsp; to physically move the last two digits past the "20"
        $gap = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

        // 4. Format the last two digits of the year
        $part2 = implode('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', str_split($year2));

        // Combine them
        $formattedDate = $part1 . $gap . $part2;

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                /* 1. Force PDF Engine to strict dimensions */
                @page { 
                    size: 184.15mm 88.9mm; 
                    margin: 0; 
                }
                html, body {
                    margin: 0;
                    padding: 0;
                    width: '.$w.';
                    height: '.$h.';
                    overflow: hidden;
                    font-family: Arial, sans-serif;
                    background: #fff;
                }

                /* 2. Absolute positioning container */
                .cheque-container {
                    position: absolute;
                    top: 0; left: 0;
                    width: '.$w.';
                    height: '.$h.';
                }

                .field {
                    position: absolute;
                    font-weight: bold;
                    color: #000;
                    white-space: nowrap; /* Prevents text from breaking into lines */
                }

                /* Date - Top Right */
                .date { 
                    top: 8mm; 
                    right: 6mm; 
                    font-size: 14pt; 
                    white-space: nowrap; /* Critical for the DDMM  YY format */
                }

                /* Container shifted to the absolute top-left corner */
                .crossing-container {
                    position: absolute;
                    top: -5mm;    /* Negative value pulls it higher towards the top edge */
                    left: -20mm;  /* Negative value pulls it further left */
                    width: 100mm; /* Wide enough to prevent text clipping during rotation */
                    height: 15mm;
                    transform: rotate(-35deg); 
                    z-index: 10;
                }

                /* Parallel crossing lines */
                .crossing-line {
                    border-top: 1.2pt solid #000;
                    width: 100%;
                    display: block;
                }

                /* Left-aligned text inside the lines */
                .crossing-text {
                    font-size: 8pt;
                    font-weight: bold;
                    font-family: Arial, sans-serif;
                    letter-spacing: 0.5pt;
                    text-transform: uppercase;
                    line-height: 5mm; 
                    white-space: nowrap; 
                    text-align: left;    /* Keeps text to the left */
                    padding-left: 19mm;  /* Adjust this value to move text along the line */
                }

                /* Payee - Top Middle */
                .payee { 
                    top: 24mm; 
                    left: 25mm; 
                    font-size: 11pt; 
                }

                /* Amount in Words - Middle (Allows 2 lines) */
                .amt-words { 
                    top: 35mm; 
                    left: 18mm; 
                    width: 115mm; 
                    line-height: 8mm; 
                    font-size: 10pt;
                    white-space: normal; /* Allow wrapping only here */
                }

                /* Amount in Figures - Right Middle */
                .amt-figures { 
                    top: 42mm; 
                    right: 10mm; 
                    font-size: 13pt; 
                }
            </style>
        </head>
        <body>
            <div class="cheque-container">';
                if($data['is_crossed']):
                $html.='<div class="crossing-container">
                    <div class="crossing-line"></div>
                    <div class="crossing-text">A/C PAYEE ONLY</div>
                    <div class="crossing-line"></div>
                </div>';
                endif;
                $html.='<div class="field date">'.$formattedDate.'</div>
                <div class="field payee">**' . strtoupper($data['payee_name']) . '**</div>
                <div class="field amt-words">**' . strtoupper($data['amount_words']) . ' ONLY**</div>
                <div class="field amt-figures">**' . number_format($data['amount_figures'], 2) . '**</div>
            </div>
        </body>
        </html>';

        // echo $html;
        $this->load->library('pdf');
        $this->pdf->loadHtml($html);
        $this->pdf->render();
        $this->pdf->stream( "paymentvoucher.pdf", array("Attachment"=>0));
    }
    public function ConvertRupeeToText($amount) {
        $ones = array(
            0 => '',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen'
        );
    
        $tens = array(
            2 => 'twenty',
            3 => 'thirty',
            4 => 'forty',
            5 => 'fifty',
            6 => 'sixty',
            7 => 'seventy',
            8 => 'eighty',
            9 => 'ninety'
        );
    
        $amount = str_replace(',', '', $amount);
        $rupees = intval($amount);
        $cents = intval(round(($amount - $rupees) * 100));
    
        $words = '';
    
        $numberToWords = function($num) use (&$numberToWords, $ones, $tens) {
            $str = '';
    
            if ($num >= 1000000000) {
                $str .= $numberToWords(intval($num / 1000000000)) . ' billion ';
                $num %= 1000000000;
            }
    
            if ($num >= 1000000) {
                $str .= $numberToWords(intval($num / 1000000)) . ' million ';
                $num %= 1000000;
            }
    
            if ($num >= 1000) {
                $str .= $numberToWords(intval($num / 1000)) . ' thousand ';
                $num %= 1000;
            }
    
            if ($num >= 100) {
                $str .= $ones[intval($num / 100)] . ' hundred ';
                $num %= 100;
            }
    
            if ($num > 0) {
                if ($str !== '') {
                    $str .= ' ';
                }
    
                if ($num < 20) {
                    $str .= $ones[$num];
                } else {
                    $str .= $tens[intval($num / 10)];
                    if ($num % 10 > 0) {
                        $str .= '-' . $ones[$num % 10];
                    }
                }
            }
    
            return trim($str);
        };
    
        if ($rupees > 0) {
            $words .= $numberToWords($rupees);
        }
    
        if ($cents > 0) {
            if ($rupees > 0) {
                $words .= ' and ';
            }
            $words .= $numberToWords($cents) . ' cents';
        }
    
        if ($words === '') {
            $words = 'zero';
        }
    
        return ucfirst(trim($words));
    } 
}