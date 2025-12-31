<?php
class Reportprintinfo extends CI_Model{
    public function Receivereceipt($invoicereceipt, $printtype){
        $this->db->select('`tbl_receivable_info`.`invoiceno`, `tbl_receivable`.`receiptno`, `tbl_receivable_info`.`narration`, `tbl_receivable_info`.`amount`,`tbl_receivable`.`idtbl_receivable`, `tbl_receivable`.`recdate`');
        $this->db->from('tbl_receivable_info');
        $this->db->join('tbl_receivable', 'tbl_receivable.idtbl_receivable = tbl_receivable_info.tbl_receivable_idtbl_receivable', 'left');
        if($printtype==1){
            $this->db->where('`tbl_receivable_info`.`invoiceno`', $invoicereceipt);
        }
        else{
            $this->db->where('tbl_receivable.receiptno', $invoicereceipt);
        }
        $this->db->where('tbl_receivable_info.status', '1');
        $this->db->where('tbl_receivable.status', '1');
        $respondinvoiceinfo=$this->db->get();

        $this->db->select('`tbl_customer`.`customer` AS `customer`, `tbl_customer`.`address_line1`, `tbl_customer`.`address_line2`, `tbl_customer`.`city`, `tbl_customer`.`state`, SUM(tbl_receivable.amount) AS `receipttotal`, `tbl_receivable`.`idtbl_receivable` AS `receipts`, `tbl_receivable`.`recdate` AS `receiptdates`');
        $this->db->from('tbl_receivable');
        $this->db->join('tbl_customer', 'tbl_customer.idtbl_customer = tbl_receivable.payer', 'left');
        if($printtype==1){
            $this->db->join('tbl_receivable_info', 'tbl_receivable_info.tbl_receivable_idtbl_receivable = tbl_receivable.idtbl_receivable', 'left');
            $this->db->where('`tbl_receivable_info`.`invoiceno`', $invoicereceipt);
            $this->db->where('tbl_receivable_info.status', '1');
        }
        else{
            $this->db->where('tbl_receivable.receiptno', $invoicereceipt);
        }
        $this->db->where('tbl_receivable.status', '1');
        // $this->db->group_by('`tbl_receivable_info`.`tbl_receivable_idtbl_receivable`');
        $respondreceipt=$this->db->get();   

        $this->db->select('tbl_receivable.chequedate, tbl_receivable.chequeno, tbl_receivable.tbl_company_idtbl_company, tbl_receivable.tbl_company_branch_idtbl_company_branch');
        $this->db->from('tbl_receivable');
        $this->db->join('tbl_customer', 'tbl_customer.idtbl_customer = tbl_receivable.payer', 'left');
        if($printtype==1){
            $this->db->join('tbl_receivable_info', 'tbl_receivable_info.tbl_receivable_idtbl_receivable = tbl_receivable.idtbl_receivable', 'left');
            $this->db->where('`tbl_receivable_info`.`invoiceno`', $invoicereceipt);
            $this->db->where('tbl_receivable_info.status', '1');
        }
        else{
            $this->db->where('tbl_receivable.receiptno', $invoicereceipt);
        }
        $this->db->where('tbl_receivable.status', '1');
        $respondcheque=$this->db->get(); 
        
        $this->db->select('tbl_company.company AS companyname,tbl_company.address1 As companyaddress,tbl_company.mobile AS companymobile,
                                tbl_company.phone companyphone,tbl_company.email AS companyemail,
                                tbl_company_branch.branch AS branchname');
		$this->db->from('tbl_receivable');
		$this->db->join('tbl_company', 'tbl_company.idtbl_company = tbl_receivable.tbl_company_idtbl_company', 'left');
        $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_receivable.tbl_company_branch_idtbl_company_branch', 'left');
		if($printtype==1){
            $this->db->join('tbl_receivable_info', 'tbl_receivable_info.tbl_receivable_idtbl_receivable = tbl_receivable.idtbl_receivable', 'left');
            $this->db->where('`tbl_receivable_info`.`invoiceno`', $invoicereceipt);
            $this->db->where('tbl_receivable_info.status', '1');
        }
        else{
            $this->db->where('tbl_receivable.receiptno', $invoicereceipt);
        }
        $this->db->where('tbl_receivable.status', '1');
		$companydetails = $this->db->get();

        $obj = new stdClass();
        $obj->invoicedata=$respondinvoiceinfo->result();
        $obj->chequedata=$respondcheque->result();
        $obj->customer=$respondreceipt->row(0)->customer;
        $obj->address_line1=$respondreceipt->row(0)->address_line1;
        $obj->address_line2=$respondreceipt->row(0)->address_line2;
        $obj->city=$respondreceipt->row(0)->city;
        $obj->state=$respondreceipt->row(0)->state;
        $obj->receipttotal=$respondreceipt->row(0)->receipttotal;
        $obj->receipts=$respondreceipt->row(0)->receipts;
        $obj->receiptdates=$respondreceipt->row(0)->receiptdates;
        // print_r($obj);
        // return $obj;

        $dataArray = [];
        $count = 0;
        $section = 1;
        $i = 1;
        foreach ($respondinvoiceinfo->result() as $rowlist) {        
            if ($count % 5 == 0) {
                $dataArray[$section] = [];
            }
        
            $dataArray[$section][] = [
                'orderno' => $i,
                'invoiceno' => $rowlist->invoiceno,
                'narration' => $rowlist->narration,
                'amount' => $rowlist->amount
            ];
        
            $count++;
        
            if ($count % 5 == 0) {
                $section++;
            }

            $i++;
        }        

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Multi Offset Printers</title>
            <style>
                @page {
                    size: 220mm 140mm;
                    margin: 5mm 5mm 5mm 5mm; /* top right bottom left */
                    font-family: Arial, sans-serif;
                }
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.5;
                    text-align:left;
                    margin-top: 160px;
                }

                /** Define the header rules **/
                header {
                    position: fixed;
                    top: 0px;
                    left: 0px;
                    right: 0px;
                    height: 250px;
                }

                /** Define the footer rules **/
                footer {
                    position: fixed; 
                    bottom: 0px; 
                    left: 0px; 
                    right: 0px;
                    height: 20px;
                }

                /** Page break for sections **/
                .page-break {
                    page-break-after: always;
                    break-after: page;
                }
                
                /** No page break for last section **/
                .no-page-break {
                    page-break-after: avoid;
                    break-after: avoid;
                }
            </style>
        </head>
        <body>
            <header>
                <table style="width:100%;border-collapse: collapse;">
                    <tr>
                        <td width="55%" style="vertical-align: top;padding:0px;">
                            <p style="margin:0px;font-size:16px;font-weight: bold;">RECEIPT</p>
                            <p style="margin:0px;font-size:13px;font-weight: bold;">To: '.$respondreceipt->row(0)->customer.'</p>
                            <p style="margin:0px;font-size:13px;padding-left: 24px;"> '.$respondreceipt->row(0)->address_line1.',</p>
                            <p style="margin:0px;font-size:13px;padding-left: 24px;"> '.$respondreceipt->row(0)->address_line2.',</p>
                            <p style="margin:0px;font-size:13px;padding-left: 24px;"> '.$respondreceipt->row(0)->city.'.</p>
                        </td>
                        <td style="vertical-align: top;padding:0px;">
                            <p style="margin:0px;font-size:18px;font-weight:bold;text-transform: uppercase;">'.$companydetails->row()->companyname.'</p>
                            <p style="margin:0px;font-size:13px;font-weight:normal;text-transform: uppercase;">'.$companydetails->row()->companyaddress.'</p>
                            <p style="margin:0px;font-size:13px;font-weight:normal;">Phone : '.$companydetails->row()->companymobile.'/'.$companydetails->row()->companyphone.'</p>
                            <p style="margin:0px;font-size:13px;font-weight:normal;"><u>E-Mail : '.$companydetails->row()->companyemail.'</u></p>
                            <p style="margin:0px;font-size:13px;font-weight:normal;">Receipt No : '.$respondinvoiceinfo->row(0)->receiptno.'</p>
                            <p style="margin:0px;font-size:13px;font-weight:normal;">Date : '.$respondinvoiceinfo->row(0)->recdate.'</p>
                            <p style="margin:0px;font-size:13px;font-weight:normal;">Our Vat No : &nbsp; 103305667-7000</p>
                        </td>
                    </tr>
                </table>
            </header>
            <footer>
                <table style="width:100%;">
                    <tr>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Manager</td>
                        <td width="10%">&nbsp;</td>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Asst. Accountant</td>
                        <td width="10%">&nbsp;</td>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Cashier</td>
                    </tr>
                </table>
            </footer>';

            // Get total number of sections
            $totalSections = count($dataArray);
            $currentSection = 1;

            foreach ($dataArray as $index => $section) {
                // Add page-break class to all sections except the last one
                $pageBreakClass = ($currentSection < $totalSections) ? 'page-break' : 'no-page-break';

                $html.='<main class="' . $pageBreakClass . '">
                    <table style="width:100%;border-collapse: collapse;font-size: 13px;">
                        <tr>
                            <th width="10%" style="text-align: center; border:1px solid black;">No</th>
                            <th width="15%" style="border:1px solid black;padding-left: 5px;">Invoice No</th>
                            <th style="text-align: left; border:1px solid black;padding-left: 5px;">Description</th>
                            <th width="25%" style="text-align: right; border:1px solid black;padding-right: 10px;">Amount</th>
                        </tr>';
                        foreach ($section as $row) {
                            $html .= '<tr>
                                <td width="10%" style="text-align: center; border:1px solid black;">'.$row['orderno'].'</td>
                                <td width="15%" style="border:1px solid black;padding-left: 5px;">'.$row['invoiceno'].'</td>
                                <td style="text-align: left; border:1px solid black;padding-left: 5px;">'.$row['narration'].'</td>
                                <td width="25%" style="text-align: right; border:1px solid black;padding-right: 10px;">'.number_format($row['amount'], 2).'</td>
                            </tr>';
                        }
                    $html .= '</table>';
                    
                    if ($currentSection === $totalSections) {
                        $html.='<p style="font-size:13px;">Cheque information</p>
                        <table style="width:50%;border-collapse: collapse;">
                            <tr>
                                <th style="text-align: center; font-size:13px;border:1px solid black;">#</th>
                                <th style="text-align: left; font-size:13px;border:1px solid black;padding-left:5px;">Cheque No</th>
                                <th style="text-align: left; font-size:13px;border:1px solid black;padding-left:5px;">Cheque Date</th>
                            </tr>';
                            $j=1; foreach($respondcheque->result() as $rowcheque){if(!empty($rowcheque->chequeno)){
                            $html.='<tr>
                                <td style="text-align: center; font-size:13px;border:1px solid black;">'.$j.'</td>
                                <td style="text-align: left; font-size:13px;border:1px solid black;padding-left:5px;">'.$rowcheque->chequeno.'</td>
                                <td style="text-align: left; font-size:13px;border:1px solid black;padding-left:5px;">'.$rowcheque->chequedate.'</td>
                            </tr>
                            ';
                            }}
                        $html .= '</table>';
                    }
                $html.='</main>';
                $currentSection++;
            }
        $html.='</body>
        </html>';
        // echo $html;
        $this->load->library('pdf');
        $this->pdf->loadHtml($html);
        $this->pdf->render();
        $this->pdf->stream( "receiptvoucher.pdf", array("Attachment"=>0));
    }
    public function Paymentreceipt($invoicereceipt){
        $recordID=$invoicereceipt;
        $updatedatetime=date('Y-m-d H:i:s');

        $this->db->select('`tbl_expence_info`.*, `tbl_company`.`company`, `tbl_company`.`address1`, `tbl_company`.`address2`, `tbl_company`.`mobile`, `tbl_company`.`phone`, `tbl_company`.`email`, `tbl_supplier`.`suppliername`, `tbl_supplier`.`telephone_no`, , CONCAT(`address_line1`, " ", `address_line2`, " ", `city`) AS `address`, `tbl_supplier`.`email`');
        $this->db->from('tbl_expence_info');
        $this->db->join('tbl_company', 'tbl_company.idtbl_company=tbl_expence_info.tbl_company_idtbl_company', 'left');
        $this->db->join('tbl_supplier', 'tbl_supplier.idtbl_supplier=tbl_expence_info.tbl_supplier_idtbl_supplier', 'left');
        $this->db->where('tbl_expence_info.idtbl_expence_info', $recordID);
        // $this->db->where_in('tbl_pettycash.status', array(1, 2));

        $respond=$this->db->get();

        $rupeetext=$this->Reportprintinfo->ConvertRupeeToText(round($respond->row(0)->amount, 2));

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Multi Offset Printers</title>
            <style>
                @page {
                    size: 220mm 140mm;
                    margin: 5mm 5mm 5mm 5mm; /* top right bottom left */
                    font-family: Arial, sans-serif;
                }
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.5;
                    text-align:left;
                    margin-top: 160px;
                }

                /** Define the header rules **/
                header {
                    position: fixed;
                    top: 0px;
                    left: 0px;
                    right: 0px;
                    height: 250px;
                }

                /** Define the footer rules **/
                footer {
                    position: fixed; 
                    bottom: 0px; 
                    left: 0px; 
                    right: 0px;
                    height: 70px;
                }
            </style>
        </head>
        <body>
            <header>
                <table style="width:100%;border-collapse: collapse;">
                    <tr>
                        <td width="55%" style="vertical-align: top;padding:0px;">
                            <p style="margin:0px;font-size:16px;font-weight: bold;">PAYMENT VOUCHER</p>
                            <p style="margin:0px;font-size:13px;font-weight: bold;">To: '.$respond->row(0)->suppliername.'</p>
                            <p style="margin:0px;font-size:13px;padding-left: 24px;"> '.$respond->row(0)->address.'</p>
                            <p style="margin:0px;font-size:13px;padding-left: 24px;"> '.$respond->row(0)->telephone_no.'</p>
                            <p style="margin:0px;font-size:13px;padding-left: 24px;"> '.$respond->row(0)->email.'</p>
                        </td>
                        <td style="vertical-align: top;padding:0px;">
                            <p style="margin:0px;font-size:18px;font-weight:bold;text-transform: uppercase;">'.$respond->row(0)->company.'</p>
                            <p style="margin:0px;font-size:13px;font-weight:normal;text-transform: uppercase;">'.$respond->row(0)->address1.' '.$respond->row(0)->address2.'</p>
                            <p style="margin:0px;font-size:13px;font-weight:normal;">Phone : '.$respond->row(0)->mobile.'</p>
                            <p style="margin:0px;font-size:13px;font-weight:normal;"><u>E-Mail : '.$respond->row(0)->email.'</u></p>
                        </td>
                    </tr>
                </table>
            </header>
            <footer>
                <table style="width:100%;">
                    <tr>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Prepared By</td>
                        <td width="10%">&nbsp;</td>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Checked By</td>
                        <td width="10%">&nbsp;</td>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Approved By</td>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Received By</td>
                        <td width="10%">&nbsp;</td>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Date</td>
                        <td width="10%">&nbsp;</td>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Signature</td>
                    </tr>
                </table>
            </footer>';
            $html.='<main>
                <table style="width:100%;border-collapse: collapse;font-size: 13px;">
                    <tr>
                        <th style="border:1px solid black;padding-left: 5px;">Invoice No</th>
                        <th width="15%" style="text-align: left; border:1px solid black;padding-left: 5px;">Date</th>
                        <th width="25%" style="text-align: right; border:1px solid black;padding-right: 10px;">Amount</th>
                    </tr>
                    <tr>
                        <td style="text-align: left; border:1px solid black;padding-left: 5px;">'.$respond->row(0)->grnno.'</td>
                        <td width="15%" style="border:1px solid black;padding-left: 5px;">'.$respond->row(0)->grndate.'</td>
                        <td width="25%" style="text-align: right; border:1px solid black;padding-right: 10px;">'.number_format($respond->row(0)->amount, 2).'</td>
                    </tr>
                    <tr>
                        <th style="padding-left: 5px;border:1px solid black;" colspan="2">Total Amount</th>
                        <th style="border:1px solid black; text-align: right;padding-right: 10px;">'.number_format($respond->row(0)->amount, 2).'</th>
                    </tr>
                    <tr>
                        <td style="padding-left: 5px; border:1px solid black;" colspan="3">Rupees: '.$rupeetext.'</td>
                    </tr>
                </table>
                <table style="width:100%;border-collapse: collapse;font-size: 13px;">
                    <tr>
                        <td><u><b>Remark :</b></u><br>'.$respond->row(0)->remark.'</td>
                    </tr>
                </table>
            </main>
        </body>
        </html>';

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
    public function Paymentsettlereceipt($invoicereceipt, $printtype){
        $this->db->select('`tbl_account_paysettle_info`.`invoiceno`, `tbl_account_paysettle`.`paymentno`, `tbl_account_paysettle_info`.`narration`, `tbl_account_paysettle_info`.`amount`,`tbl_account_paysettle`.`idtbl_account_paysettle`, `tbl_account_paysettle`.`date`');
        $this->db->from('tbl_account_paysettle_info');
        $this->db->join('tbl_account_paysettle', 'tbl_account_paysettle.idtbl_account_paysettle = tbl_account_paysettle_info.tbl_account_paysettle_idtbl_account_paysettle', 'left');
        if($printtype==1){
            $this->db->where('`tbl_account_paysettle_info`.`invoiceno`', $invoicereceipt);
        }
        else{
            $this->db->where('tbl_account_paysettle.paymentno', $invoicereceipt);
        }
        $this->db->where('tbl_account_paysettle_info.status', '1');
        $this->db->where('tbl_account_paysettle.status', '1');
        $respondinvoiceinfo=$this->db->get();

        $this->db->select('`tbl_supplier`.`suppliername` AS `suppliername`, CONCAT(`address_line1`, " ", `address_line2`, " ", `city`) AS `address`, `tbl_supplier`.`telephone_no`, SUM(tbl_account_paysettle.totalpayment) AS `receipttotal`, `tbl_account_paysettle`.`idtbl_account_paysettle` AS `receipts`, `tbl_account_paysettle`.`date` AS `receiptdates`');
        $this->db->from('tbl_account_paysettle');
        $this->db->join('tbl_supplier', 'tbl_supplier.idtbl_supplier = tbl_account_paysettle.supplier', 'left');
        if($printtype==1){
            $this->db->join('tbl_account_paysettle_info', 'tbl_account_paysettle_info.tbl_account_paysettle_idtbl_account_paysettle = tbl_account_paysettle.idtbl_account_paysettle', 'left');
            $this->db->where('`tbl_account_paysettle_info`.`invoiceno`', $invoicereceipt);
            $this->db->where('tbl_account_paysettle_info.status', '1');
        }
        else{
            $this->db->where('tbl_account_paysettle.paymentno', $invoicereceipt);
        }
        $this->db->where('tbl_account_paysettle.status', '1');
        $respondreceipt=$this->db->get(); 

        $this->db->select('tbl_cheque_issue.chedate, tbl_cheque_issue.chequeno, tbl_account_paysettle.tbl_company_idtbl_company, tbl_account_paysettle.tbl_company_branch_idtbl_company_branch');
        $this->db->from('tbl_cheque_issue');
        $this->db->join('tbl_account_paysettle_has_tbl_cheque_issue', 'tbl_account_paysettle_has_tbl_cheque_issue.tbl_cheque_issue_idtbl_cheque_issue = tbl_cheque_issue.idtbl_cheque_issue', 'left');
        $this->db->join('tbl_account_paysettle', 'tbl_account_paysettle.idtbl_account_paysettle = tbl_account_paysettle_has_tbl_cheque_issue.tbl_account_paysettle_idtbl_account_paysettle', 'left');
        $this->db->join('tbl_supplier', 'tbl_supplier.idtbl_supplier = tbl_account_paysettle.supplier', 'left');
        if($printtype==1){
            $this->db->join('tbl_account_paysettle_info', 'tbl_account_paysettle_info.tbl_account_paysettle_idtbl_account_paysettle = tbl_account_paysettle.idtbl_account_paysettle', 'left');
            $this->db->where('`tbl_account_paysettle_info`.`invoiceno`', $invoicereceipt);
            $this->db->where('tbl_account_paysettle_info.status', '1');
        }
        else{
            $this->db->where('tbl_account_paysettle.paymentno', $invoicereceipt);
        }
        $this->db->where('tbl_account_paysettle.status', '1');
        $respondcheque=$this->db->get(); 
        
        $this->db->select('tbl_company.company AS companyname,tbl_company.address1 As companyaddress,tbl_company.mobile AS companymobile,
                                tbl_company.phone companyphone,tbl_company.email AS companyemail,
                                tbl_company_branch.branch AS branchname');
		$this->db->from('tbl_account_paysettle');
		$this->db->join('tbl_company', 'tbl_company.idtbl_company = tbl_account_paysettle.tbl_company_idtbl_company', 'left');
        $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_account_paysettle.tbl_company_branch_idtbl_company_branch', 'left');
		if($printtype==1){
            $this->db->join('tbl_account_paysettle_info', 'tbl_account_paysettle_info.tbl_account_paysettle_idtbl_account_paysettle = tbl_account_paysettle.idtbl_account_paysettle', 'left');
            $this->db->where('`tbl_account_paysettle_info`.`invoiceno`', $invoicereceipt);
            $this->db->where('tbl_account_paysettle_info.status', '1');
        }
        else{
            $this->db->where('tbl_account_paysettle.paymentno', $invoicereceipt);
        }
        $this->db->where('tbl_account_paysettle.status', '1');
		$companydetails = $this->db->get();

        $obj = new stdClass();
        $obj->invoicedata=$respondinvoiceinfo->result();
        $obj->chequedata=$respondcheque->result();
        $obj->supplier=$respondreceipt->row(0)->suppliername;
        $obj->address=$respondreceipt->row(0)->address;
        $obj->contact=$respondreceipt->row(0)->telephone_no;
        $obj->receipttotal=$respondreceipt->row(0)->receipttotal;
        $obj->receipts=$respondreceipt->row(0)->receipts;
        $obj->receiptdates=$respondreceipt->row(0)->receiptdates;
        // print_r($obj);
        // return $obj;

        $dataArray = [];
        $count = 0;
        $section = 1;
        $i = 1;
        foreach ($respondinvoiceinfo->result() as $rowlist) {        
            if ($count % 5 == 0) {
                $dataArray[$section] = [];
            }
        
            $dataArray[$section][] = [
                'orderno' => $i,
                'invoiceno' => $rowlist->invoiceno,
                'narration' => $rowlist->narration,
                'amount' => $rowlist->amount
            ];
        
            $count++;
        
            if ($count % 5 == 0) {
                $section++;
            }

            $i++;
        }        

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Multi Offset Printers</title>
            <style>
                @page {
                    size: 220mm 140mm;
                    margin: 5mm 5mm 5mm 5mm; /* top right bottom left */
                    font-family: Arial, sans-serif;
                }
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.5;
                    text-align:left;
                    margin-top: 160px;
                }

                /** Define the header rules **/
                header {
                    position: fixed;
                    top: 0px;
                    left: 0px;
                    right: 0px;
                    height: 250px;
                }

                /** Define the footer rules **/
                footer {
                    position: fixed; 
                    bottom: 0px; 
                    left: 0px; 
                    right: 0px;
                    height: 20px;
                }
                /** Page break for sections **/
                .page-break {
                    page-break-after: always;
                    break-after: page;
                }
                
                /** No page break for last section **/
                .no-page-break {
                    page-break-after: avoid;
                    break-after: avoid;
                }
            </style>
        </head>
        <body>
            <header>
                <table style="width:100%;border-collapse: collapse;">
                    <tr>
                        <td width="55%" style="vertical-align: top;padding:0px;">
                            <p style="margin:0px;font-size:16px;font-weight: bold;">PAYMENT RECEIPT</p>
                            <p style="margin:0px;font-size:13px;font-weight: bold;">To: '.$respondreceipt->row(0)->suppliername.'</p>
                            <p style="margin:0px;font-size:13px;padding-left: 24px;"> '.$respondreceipt->row(0)->address.',</p>
                            <p style="margin:0px;font-size:13px;padding-left: 24px;"> '.$respondreceipt->row(0)->telephone_no.'.</p>
                        </td>
                        <td style="vertical-align: top;padding:0px;">
                            <p style="margin:0px;font-size:18px;font-weight:bold;text-transform: uppercase;">'.$companydetails->row()->companyname.'</p>
                            <p style="margin:0px;font-size:13px;font-weight:normal;text-transform: uppercase;">'.$companydetails->row()->companyaddress.'</p>
                            <p style="margin:0px;font-size:13px;font-weight:normal;">Phone : '.$companydetails->row()->companymobile.'/'.$companydetails->row()->companyphone.'</p>
                            <p style="margin:0px;font-size:13px;font-weight:normal;"><u>E-Mail : '.$companydetails->row()->companyemail.'</u></p>
                            <p style="margin:0px;font-size:13px;font-weight:normal;">Receipt No : '.$respondinvoiceinfo->row(0)->paymentno.'</p>
                            <p style="margin:0px;font-size:13px;font-weight:normal;">Date : '.$respondinvoiceinfo->row(0)->date.'</p>
                            <p style="margin:0px;font-size:13px;font-weight:normal;">Our Vat No : &nbsp; 103305667-7000</p>
                        </td>
                    </tr>
                </table>
            </header>
            <footer>
                <table style="width:100%;">
                    <tr>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Manager</td>
                        <td width="10%">&nbsp;</td>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Asst. Accountant</td>
                        <td width="10%">&nbsp;</td>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Cashier</td>
                    </tr>
                </table>
            </footer>';
            // Get total number of sections
            $totalSections = count($dataArray);
            $currentSection = 1;
            foreach ($dataArray as $index => $section) {
                // Add page-break class to all sections except the last one
                $pageBreakClass = ($currentSection < $totalSections) ? 'page-break' : 'no-page-break';

                $html.='<main class="' . $pageBreakClass . '">
                    <table style="width:100%;border-collapse: collapse;font-size: 13px;">
                        <tr>
                            <th width="10%" style="text-align: center; border:1px solid black;">No</th>
                            <th width="15%" style="border:1px solid black;padding-left: 5px;">Invoice No</th>
                            <th style="text-align: left; border:1px solid black;padding-left: 5px;">Description</th>
                            <th width="25%" style="text-align: right; border:1px solid black;padding-right: 10px;">Amount</th>
                        </tr>';
                        foreach ($section as $row) {
                            $html .= '<tr>
                                <td width="10%" style="text-align: center; border:1px solid black;">'.$row['orderno'].'</td>
                                <td width="15%" style="border:1px solid black;padding-left: 5px;">'.$row['invoiceno'].'</td>
                                <td style="text-align: left; border:1px solid black;padding-left: 5px;">'.$row['narration'].'</td>
                                <td width="25%" style="text-align: right; border:1px solid black;padding-right: 10px;">'.number_format($row['amount'], 2).'</td>
                            </tr>';
                        }
                    $html .= '</table>';
                    if ($currentSection === $totalSections) {
                        $html.='
                        <p style="font-size:13px;">Cheque information</p>
                        <table style="width:50%;border-collapse: collapse;">
                            <tr>
                                <th style="text-align: center; font-size:13px;border:1px solid black;">#</th>
                                <th style="text-align: left; font-size:13px;border:1px solid black;padding-left:5px;">Cheque No</th>
                                <th style="text-align: left; font-size:13px;border:1px solid black;padding-left:5px;">Cheque Date</th>
                            </tr>';
                            $j=1; foreach($respondcheque->result() as $rowcheque){if(!empty($rowcheque->chequeno)){
                            $html.='<tr>
                                <td style="text-align: center; font-size:13px;border:1px solid black;">'.$j.'</td>
                                <td style="text-align: left; font-size:13px;border:1px solid black;padding-left:5px;">'.$rowcheque->chequeno.'</td>
                                <td style="text-align: left; font-size:13px;border:1px solid black;padding-left:5px;">'.$rowcheque->chedate.'</td>
                            </tr>
                            ';
                            }}
                        $html .= '</table>';
                    }
                $html.='</main>';
                $currentSection++;
            }
        $html.='</body>
        </html>';
        // echo $html;
        $this->load->library('pdf');
        $this->pdf->loadHtml($html);
        $this->pdf->render();
        $this->pdf->stream( "paymentsettlevoucher.pdf", array("Attachment"=>0));
    }
    public function PettyCashReibursePrint($voucherid){
        $companyid=$_SESSION['companyid'];
        $branchid=$_SESSION['branchid'];

        $this->db->select('tbl_company.company, tbl_company.code, tbl_company.address1, tbl_company.address2, tbl_company.mobile, tbl_company.phone, tbl_company.email, tbl_company_branch.branch');
        $this->db->from('tbl_company');
        $this->db->join('tbl_company_branch', 'tbl_company_branch.tbl_company_idtbl_company=tbl_company.idtbl_company', 'left');
        $this->db->where('tbl_company.idtbl_company', $companyid);
        $this->db->where('tbl_company_branch.idtbl_company_branch', $branchid);
        $respond=$this->db->get();

        $this->db->select('*');
        $this->db->from('tbl_pettycash_reimburse');
        $this->db->where('idtbl_pettycash_reimburse', $voucherid);
        $this->db->where('status', '1');
        $respondreimburse=$this->db->get();

        $this->db->select(' `date`, `pettycashcode`, `desc`, `amount`');
        $this->db->from('tbl_pettycash');
        $this->db->join('tbl_pettycash_reimburse_has_tbl_pettycash', 'tbl_pettycash_reimburse_has_tbl_pettycash.tbl_pettycash_idtbl_pettycash=tbl_pettycash.idtbl_pettycash', 'left');
        $this->db->where('tbl_pettycash_reimburse_has_tbl_pettycash.tbl_pettycash_reimburse_idtbl_pettycash_reimburse', $voucherid);
        $this->db->where('tbl_pettycash.status', '1');
        $respondreimbursedata=$this->db->get();

        $dataArray = [];
        $count = 0;
        $section = 1;
        $i = 1;
        foreach ($respondreimbursedata->result() as $rowlist) {        
            if ($count % 20 == 0) {
                $dataArray[$section] = [];
            }
        
            $dataArray[$section][] = [
                'orderno' => $i,
                'date' => $rowlist->date,
                'pettycashcode' => $rowlist->pettycashcode,
                'desc' => $rowlist->desc,
                'amount' => $rowlist->amount
            ];
        
            $count++;
        
            if ($count % 20 == 0) {
                $section++;
            }

            $i++;
        }     

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Multi Offset Printers</title>
            <style>
                @page {
                    /*size: 220mm 140mm;*/
                    margin: 5mm 5mm 5mm 5mm; /* top right bottom left */
                    font-family: Arial, sans-serif;
                }
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.5;
                    text-align:left;
                    margin-top: 120px;
                }

                /** Define the header rules **/
                header {
                    position: fixed;
                    top: 0px;
                    left: 0px;
                    right: 0px;
                    height: 210px;
                }

                /** Define the footer rules **/
                footer {
                    position: fixed; 
                    bottom: 0px; 
                    left: 0px; 
                    right: 0px;
                    height: 20px;
                }
            </style>
        </head>
        <body>
            <header>
                <table style="width:100%;border-collapse: collapse;">
                    <tr>
                        <td style="text-align: center;">
                            <p style="margin:0px;font-size:16px;font-weight: bold;">'.$respond->row(0)->company.'</p>
                            <p style="margin:0px;font-size:13px;">'.$respond->row(0)->branch.'</p>
                            <p style="margin:0px;font-size:13px;">'.$respond->row(0)->address1.' '.$respond->row(0)->address2.'</p>
                            <p style="margin:0px;font-size:13px;">'.$respond->row(0)->mobile.' '.$respond->row(0)->email.'</p>
                            <p style="margin:0px;font-size:13px;"><u>Petty Cash Reimbursement Information</u></p>
                        </td>
                    </tr>
                </table>
            </header>
            <footer>
                <table style="width:100%;">
                    <tr>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Prepared By</td>
                        <td width="10%">&nbsp;</td>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Checked By</td>
                        <td width="10%">&nbsp;</td>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Approved By</td>
                    </tr>
                </table>
            </footer>';
            foreach ($dataArray as $index => $section) {
                $html.='<main>
                    <table style="width:100%;border-collapse: collapse;font-size: 13px;">
                        <thead>
                            <tr>
                                <th width="10%" style="text-align: center; border:1px solid black;">No</th>
                                <th width="12%" style="border:1px solid black;padding-left: 5px;">Date</th>
                                <th width="18%" style="text-align: left; border:1px solid black;padding-left: 5px;">Code</th>
                                <th style="text-align: left; border:1px solid black;padding-left: 5px;">Description</th>
                                <th width="15%" style="text-align: right; border:1px solid black;padding-right: 10px;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>';
                        foreach ($section as $row) {
                            $html .= '<tr>
                                <td width="10%" style="text-align: center; border:1px solid black;">'.$row['orderno'].'</td>
                                <td width="12%" style="border:1px solid black;padding-left: 5px;">'.$row['date'].'</td>
                                <td width="18%" style="border:1px solid black;padding-left: 5px;">'.$row['pettycashcode'].'</td>
                                <td style="text-align: left; border:1px solid black;padding-left: 5px;">'.$row['desc'].'</td>
                                <td width="15%" style="text-align: right; border:1px solid black;padding-right: 10px;">'.number_format($row['amount'], 2).'</td>
                            </tr>';
                        }
                        $html.='</tbody>';
                        if ($index === count($dataArray) - 1) {
                            $html .= '';
                        } else {
                            $html .= '<tfoot>
                                <tr>
                                    <th colspan="4" style="border-top: 1px solid #000;font-size:12px;">Total</th>
                                    <th style="border-top: 1px solid #000;text-align:right;padding-right:10px;border-bottom: 1px double #000;"><label id="lbltotal">'.number_format($respondreimburse->row(0)->reimursebal,2).'</label></th>
                                </tr>
                            </tfoot>';
                        }
                    $html .= '</table>
                </main>
                ';
            }
        $html.='<table style="width:100%;">
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td width="50%">
                        <p style="margin:0px;font-size:13px;margin-bottom: 10px;"><u>Cheque Information</u></p>
                        <table style="width:100%;border-collapse: collapse;">
                            <tr>
                                <th style="text-align: center; font-size:13px;border:1px solid black;">#</th>
                                <th style="text-align: left; font-size:13px;border:1px solid black;padding-left:5px;">Cheque No</th>
                                <th style="text-align: left; font-size:13px;border:1px solid black;padding-left:5px;">Cheque Date</th>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-size:13px;border:1px solid black;">1</td>
                                <td style="text-align: left; font-size:13px;border:1px solid black;padding-left:5px;">'.$respondreimburse->row(0)->chequeno.'</td>
                                <td style="text-align: left; font-size:13px;border:1px solid black;padding-left:5px;">'.$respondreimburse->row(0)->chequedate.'</td>
                            </tr>
                        </table>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
            </table>
        </body>
        </html>';

        $this->load->library('pdf');
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->loadHtml($html);
        $this->pdf->render();
        $this->pdf->stream( "paymentvoucher.pdf", array("Attachment"=>0));
    }
    public function Receivablereceipt($invoicereceipt){
        $recordID=$invoicereceipt;
        $updatedatetime=date('Y-m-d H:i:s');

        $this->db->select('`tbl_sales_info`.*, `tbl_company`.`company`, `tbl_company`.`address1`, `tbl_company`.`address2`, `tbl_company`.`mobile`, `tbl_company`.`phone`, `tbl_company`.`email`, `tbl_customer`.`customer`, CONCAT(`address_line1`, " ", `address_line2`, " ", `city`) AS `address`');
        $this->db->from('tbl_sales_info');
        $this->db->join('tbl_company', 'tbl_company.idtbl_company=tbl_sales_info.tbl_company_idtbl_company', 'left');
        $this->db->join('tbl_customer', 'tbl_customer.idtbl_customer=tbl_sales_info.tbl_customer_idtbl_customer', 'left');
        $this->db->where('tbl_sales_info.idtbl_sales_info', $recordID);
        // $this->db->where_in('tbl_pettycash.status', array(1, 2));

        $respond=$this->db->get();
        
        $rupeetext=$this->Reportprintinfo->ConvertRupeeToText(round($respond->row(0)->amount, 2));

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Multi Offset Printers</title>
            <style>
                @page {
                    size: 220mm 140mm;
                    margin: 5mm 5mm 5mm 5mm; /* top right bottom left */
                    font-family: Arial, sans-serif;
                }
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.5;
                    text-align:left;
                    margin-top: 160px;
                }

                /** Define the header rules **/
                header {
                    position: fixed;
                    top: 0px;
                    left: 0px;
                    right: 0px;
                    height: 250px;
                }

                /** Define the footer rules **/
                footer {
                    position: fixed; 
                    bottom: 0px; 
                    left: 0px; 
                    right: 0px;
                    height: 70px;
                }
            </style>
        </head>
        <body>
            <header>
                <table style="width:100%;border-collapse: collapse;">
                    <tr>
                        <td width="55%" style="vertical-align: top;padding:0px;">
                            <p style="margin:0px;font-size:16px;font-weight: bold;">RECEIVABLE VOUCHER</p>
                            <p style="margin:0px;font-size:13px;font-weight: bold;">To: '.$respond->row(0)->customer.'</p>
                            <p style="margin:0px;font-size:13px;padding-left: 24px;"> '.$respond->row(0)->address.'</p>
                        </td>
                        <td style="vertical-align: top;padding:0px;">
                            <p style="margin:0px;font-size:18px;font-weight:bold;text-transform: uppercase;">'.$respond->row(0)->company.'</p>
                            <p style="margin:0px;font-size:13px;font-weight:normal;text-transform: uppercase;">'.$respond->row(0)->address1.' '.$respond->row(0)->address2.'</p>
                            <p style="margin:0px;font-size:13px;font-weight:normal;">Phone : '.$respond->row(0)->mobile.'</p>
                            <p style="margin:0px;font-size:13px;font-weight:normal;"><u>E-Mail : '.$respond->row(0)->email.'</u></p>
                        </td>
                    </tr>
                </table>
            </header>
            <footer>
                <table style="width:100%;">
                    <tr>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Prepared By</td>
                        <td width="10%">&nbsp;</td>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Checked By</td>
                        <td width="10%">&nbsp;</td>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Approved By</td>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Received By</td>
                        <td width="10%">&nbsp;</td>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Date</td>
                        <td width="10%">&nbsp;</td>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Signature</td>
                    </tr>
                </table>
            </footer>';
            $html.='<main>
                <table style="width:100%;border-collapse: collapse;font-size: 13px;">
                    <tr>
                        <th style="border:1px solid black;padding-left: 5px;">Invoice No</th>
                        <th width="15%" style="text-align: left; border:1px solid black;padding-left: 5px;">Date</th>
                        <th width="25%" style="text-align: right; border:1px solid black;padding-right: 10px;">Amount</th>
                    </tr>
                    <tr>
                        <td style="text-align: left; border:1px solid black;padding-left: 5px;">'.$respond->row(0)->invno.'</td>
                        <td width="15%" style="border:1px solid black;padding-left: 5px;">'.$respond->row(0)->invdate.'</td>
                        <td width="25%" style="text-align: right; border:1px solid black;padding-right: 10px;">'.number_format($respond->row(0)->amount, 2).'</td>
                    </tr>
                    <tr>
                        <th style="padding-left: 5px;border:1px solid black;" colspan="2">Total Amount</th>
                        <th style="border:1px solid black; text-align: right;padding-right: 10px;">'.number_format($respond->row(0)->amount, 2).'</th>
                    </tr>
                    <tr>
                        <td style="padding-left: 5px; border:1px solid black;" colspan="3">Rupees: '.$rupeetext.'</td>
                    </tr>
                </table>
                <table style="width:100%;border-collapse: collapse;font-size: 13px;">
                    <tr>
                        <td><u><b>Remark :</b></u><br>'.$respond->row(0)->remark.'</td>
                    </tr>
                </table>
            </main>
        </body>
        </html>';
        
        $this->load->library('pdf');
        $this->pdf->loadHtml($html);
        $this->pdf->render();
        $this->pdf->stream( "paymentvoucher.pdf", array("Attachment"=>0));
    }
    public function PettycashVoucher($invoicereceipt){
        $recordID=$invoicereceipt;
        $updatedatetime=date('Y-m-d H:i:s');

        $this->db->select('`tbl_pettycash`.*, `tbl_account_detail`.`accountname`, `tbl_account_detail`.`accountno`, `tbl_company`.`company`, `tbl_company`.`address1`, `tbl_company`.`address2`, `tbl_company`.`mobile`, `tbl_company`.`phone`, `tbl_company`.`email`,tbl_company_branch.branch, tbl_account.accountno AS chartaccountno, tbl_account.accountname AS chartaccountname');
        $this->db->from('tbl_pettycash');
        $this->db->join('tbl_company', 'tbl_company.idtbl_company=tbl_pettycash.tbl_company_idtbl_company', 'left');
        $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_pettycash.tbl_company_branch_idtbl_company_branch', 'left');
        $this->db->join('tbl_account_detail', 'tbl_account_detail.idtbl_account_detail=tbl_pettycash.tbl_account_detail_idtbl_account_detail_exp', 'left');
        $this->db->join('tbl_account', 'tbl_account.idtbl_account = tbl_pettycash.tbl_account_idtbl_account_exp', 'left');
        $this->db->where('tbl_pettycash.idtbl_pettycash', $recordID);
        $respond=$this->db->get();

        $rupeetext=$this->Reportprintinfo->ConvertRupeeToText(round($respond->row(0)->amount, 2));

        if(!empty($respond->row(0)->tbl_account_detail_idtbl_account_detail_exp)){
            $accountname=$respond->row(0)->accountname;
        }
        else{
            $accountname=$respond->row(0)->chartaccountname;
        } 

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Multi Offset Printers</title>
            <style>
                @page {
                    size: 220mm 140mm;
                    margin: 5mm 5mm 5mm 5mm; /* top right bottom left */
                    font-family: Arial, sans-serif;
                }
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.5;
                    text-align:left;
                    margin-top: 160px;
                }

                /** Define the header rules **/
                header {
                    position: fixed;
                    top: 0px;
                    left: 0px;
                    right: 0px;
                    height: 250px;
                }

                /** Define the footer rules **/
                footer {
                    position: fixed; 
                    bottom: 0px; 
                    left: 0px; 
                    right: 0px;
                    height: 70px;
                }
            </style>
        </head>
        <body>
            <header>
                <table style="width:100%;border-collapse: collapse;">
                    <tr>
                        <td width="55%" style="vertical-align: top;padding:0px;">
                            <p style="margin:0px;font-size:16px;font-weight: bold;">PETTY CASH VOUCHER</p>
                            <p style="margin:0px;font-size:13px;">PV No: '.$respond->row(0)->pettycashcode.'</p>
                            <p style="margin:0px;font-size:13px;">Date: '.$respond->row(0)->date.'</p>
                            <p style="margin:0px;font-size:13px;">Float A/C: '.$accountname.'</p>
                            <p style="margin:0px;font-size:13px;">Please Pay: Cash</p>
                        </td>
                        <td style="vertical-align: top;padding:0px;">
                            <p style="margin:0px;font-size:18px;font-weight:bold;text-transform: uppercase;">'.$respond->row(0)->company.'</p>
                            <p style="margin:0px;font-size:13px;font-weight:normal;text-transform: uppercase;">'.$respond->row(0)->address1.' '.$respond->row(0)->address2.'</p>
                            <p style="margin:0px;font-size:13px;font-weight:normal;">Phone : '.$respond->row(0)->mobile.'</p>
                            <p style="margin:0px;font-size:13px;font-weight:normal;"><u>E-Mail : '.$respond->row(0)->email.'</u></p>
                        </td>
                    </tr>
                </table>
            </header>
            <footer>
                <table style="width:100%;">
                    <tr>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Prepared By</td>
                        <td width="10%">&nbsp;</td>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Checked By</td>
                        <td width="10%">&nbsp;</td>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Approved By</td>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Received By</td>
                        <td width="10%">&nbsp;</td>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Date</td>
                        <td width="10%">&nbsp;</td>
                        <td width="26.66%" style="text-align: center;border-top:1px dotted black;font-size:13px;">Signature</td>
                    </tr>
                </table>
            </footer>';
            $html.='<main>
                <table style="width:100%;border-collapse: collapse;font-size: 13px;">
                    <tr>
                        <th style="border:1px solid black;padding-left: 5px;">A/C Name</th>
                        <th width="15%" style="text-align: left; border:1px solid black;padding-left: 5px;">Description</th>
                        <th width="25%" style="text-align: right; border:1px solid black;padding-right: 10px;">Amount</th>
                    </tr>
                    <tr>
                        <td style="text-align: left; border:1px solid black;padding-left: 5px;">';
                        if(!empty($respond->row(0)->tbl_account_detail_idtbl_account_detail_exp)){
                            $html.=$respond->row(0)->accountname.' - '.$respond->row(0)->accountno;
                        }
                        else{
                            $html.=$respond->row(0)->chartaccountname.' - '.$respond->row(0)->chartaccountno;
                        }
                        $html.='</td>
                        <td width="15%" style="border:1px solid black;padding-left: 5px;">'.$respond->row(0)->desc.'</td>
                        <td width="25%" style="text-align: right; border:1px solid black;padding-right: 10px;">'.number_format($respond->row(0)->amount, 2).'</td>
                    </tr>
                    <tr>
                        <th style="padding-left: 5px;border:1px solid black;" colspan="2">Total Amount</th>
                        <th style="border:1px solid black; text-align: right;padding-right: 10px;">'.number_format($respond->row(0)->amount, 2).'</th>
                    </tr>
                    <tr>
                        <td style="padding-left: 5px; border:1px solid black;" colspan="3">Rupees: '.$rupeetext.'</td>
                    </tr>
                </table>
            </main>
        </body>
        </html>';

        $this->load->library('pdf');
        $this->pdf->loadHtml($html);
        $this->pdf->render();
        $this->pdf->stream( "pettycashvoucher.pdf", array("Attachment"=>0));
    }
}