<?php
class Receivablecreateinfo extends CI_Model{
    public function Receivablecreateinsertupdate(){
        $userID=$_SESSION['userid'];

        $company=$this->input->post('company');
        $branch=$this->input->post('branch');
        $customer=$this->input->post('customer');
        $invoicedate=$this->input->post('invoicedate');
        $invoice=$this->input->post('invoice');
        $invoiceamount=$this->input->post('invoiceamount');
        $receremark=$this->input->post('receremark');

        $recordOption=$this->input->post('recordOption');
        if(!empty($this->input->post('recordID'))){$recordID=$this->input->post('recordID');}

        $updatedatetime=date('Y-m-d H:i:s');

        if($recordOption==1){
            $this->db->trans_begin();

            $data = array(
                'saletype'=>'2', 
                'salecode'=>'OTH', 
                'invno'=>$invoice, 
                'invdate'=>$invoicedate, 
                'amount'=>$invoiceamount, 
                'invamount'=>$invoiceamount, 
                'paystatus'=>'0', 
                'poststatus'=>'0', 
                'remark'=>$receremark, 
                'status'=>'1', 
                'insertdatetime'=>$updatedatetime, 
                'tbl_user_idtbl_user'=>$userID, 
                'tbl_customer_idtbl_customer'=>$customer,
                'tbl_company_idtbl_company'=>$company,
                'tbl_company_branch_idtbl_company_branch'=>$branch
            );

            $this->db->insert('tbl_sales_info', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-save';
                $actionObj->title='';
                $actionObj->message='Record Added Successfully';
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
        else{
            $this->db->trans_begin();

            $this->db->select('poststatus');
            $this->db->from('tbl_sales_info');
            $this->db->where('idtbl_sales_info', $recordID);
            $this->db->where('status', 1);

            $respond=$this->db->get();

            $data = array(
                'editstatus' => '0',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );
    
            $this->db->where('idtbl_sales_info', $recordID);
            $this->db->update('tbl_sales_info', $data);

            if($respond->row(0)->poststatus==0){
                $data = array(
                    'saletype'=>'2', 
                    'salecode'=>'OTH', 
                    'invno'=>$invoice, 
                    'invdate'=>$invoicedate, 
                    'amount'=>$invoiceamount, 
                    'invamount'=>$invoiceamount, 
                    'paystatus'=>'0', 
                    'poststatus'=>'0', 
                    'remark'=>$receremark, 
                    'status'=>'1', 
                    'insertdatetime'=>$updatedatetime, 
                    'tbl_user_idtbl_user'=>$userID, 
                    'tbl_customer_idtbl_customer'=>$customer,
                    'tbl_company_idtbl_company'=>$company,
                    'tbl_company_branch_idtbl_company_branch'=>$branch
                );

                $this->db->where('idtbl_sales_info', $recordID);
                $this->db->update('tbl_sales_info', $data);

                $this->db->trans_complete();

                if ($this->db->trans_status() === TRUE) {
                    $this->db->trans_commit();
                    
                    $actionObj=new stdClass();
                    $actionObj->icon='fas fa-save';
                    $actionObj->title='';
                    $actionObj->message='Record Update Successfully';
                    $actionObj->url='';
                    $actionObj->target='_blank';
                    $actionObj->type='primary';

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
            else{
                $this->db->trans_commit();

                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error. This record already posted.';
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
    public function Receivablecreatestatus($x, $y){
        $userID=$_SESSION['userid'];
        $recordID=$x;
        $type=$y;
        $updatedatetime=date('Y-m-d H:i:s');

        if($type==1){
            $this->db->trans_begin();
            $data = array(
                'status' => '1',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_sales_info', $recordID);
            $this->db->update('tbl_sales_info', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-check';
                $actionObj->title='';
                $actionObj->message='Record Activate Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='success';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Receivablecreate');                
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
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Receivablecreate');
            }
        }
        else if($type==2){
            $this->db->trans_begin();
            $data = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_sales_info', $recordID);
            $this->db->update('tbl_sales_info', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-times';
                $actionObj->title='';
                $actionObj->message='Record Deactivate Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='warning';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Receivablecreate');                
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
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Receivablecreate');
            }
        }
        else if($type==3){
            $this->db->trans_begin();
            $data = array(
                'status' => '3',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_sales_info', $recordID);
            $this->db->update('tbl_sales_info', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-trash-alt';
                $actionObj->title='';
                $actionObj->message='Record Remove Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Receivablecreate');                
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
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Receivablecreate');
            }
        }
    }
    public function Receivablecreateedit(){
        $recordID=$this->input->post('recordID');
        $userID=$_SESSION['userid'];
        $updatedatetime=date('Y-m-d H:i:s');

        $data = array(
            'editstatus' => '1',
            'updateuser'=> $userID, 
            'updatedatetime'=> $updatedatetime
        );

        $this->db->where('idtbl_sales_info', $recordID);
        $this->db->update('tbl_sales_info', $data);

        $this->db->select('tbl_sales_info.*, tbl_company.company, tbl_company_branch.branch, tbl_customer.customer');
        $this->db->from('tbl_sales_info');
        $this->db->join('tbl_company', 'tbl_company.idtbl_company = tbl_sales_info.tbl_company_idtbl_company', 'left');
        $this->db->join('tbl_company_branch', 'tbl_company_branch.idtbl_company_branch = tbl_sales_info.tbl_company_branch_idtbl_company_branch', 'left');
        $this->db->join('tbl_customer', 'tbl_customer.idtbl_customer = tbl_sales_info.tbl_customer_idtbl_customer', 'left');
        $this->db->where('tbl_sales_info.idtbl_sales_info', $recordID);
        $this->db->where('tbl_sales_info.status', 1);

        $respond=$this->db->get();

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_sales_info;
        $obj->customer=$respond->row(0)->tbl_customer_idtbl_customer;
        $obj->customername=$respond->row(0)->customer;
        $obj->invno=$respond->row(0)->invno;
        $obj->invdate=$respond->row(0)->invdate;
        $obj->amount=$respond->row(0)->amount;
        $obj->remark=$respond->row(0)->remark;
        $obj->company=$respond->row(0)->company;
        $obj->companyid=$respond->row(0)->tbl_company_idtbl_company;
        $obj->branch=$respond->row(0)->branch;
        $obj->branchid=$respond->row(0)->tbl_company_branch_idtbl_company_branch;

        echo json_encode($obj);
    }
    public function Getviewprintinfo(){
        $recordID=$this->input->post('recordID');
        $updatedatetime=date('Y-m-d H:i:s');

        $this->db->select('`tbl_expence_info`.*, `tbl_company`.`company`, `tbl_company`.`address1`, `tbl_company`.`address2`, `tbl_company`.`mobile`, `tbl_company`.`phone`, `tbl_company`.`email`, `tbl_supplier`.`suppliername`, `tbl_supplier`.`telephone_no`, CONCAT(`address_line1`, " ", `address_line2`, " ", `city`) AS `address`, `tbl_supplier`.`email`');
        $this->db->from('tbl_expence_info');
        $this->db->join('tbl_company', 'tbl_company.idtbl_company=tbl_expence_info.tbl_company_idtbl_company', 'left');
        $this->db->join('tbl_supplier', 'tbl_supplier.idtbl_supplier=tbl_expence_info.tbl_supplier_idtbl_supplier', 'left');
        $this->db->where('tbl_expence_info.idtbl_expence_info', $recordID);
        // $this->db->where_in('tbl_pettycash.status', array(1, 2));

        $respond=$this->db->get();

        $rupeetext=$this->Paymentcreateinfo->ConvertRupeeToText(round($respond->row(0)->amount, 2));

        $html='';
        $html.='
        <h2 class="font-weight-bold text-dark text-center mb-3"><u>Payment Voucher</u></h2>
        <div class="row">
            <div class="col-6">
                <h5 class="font-weight-bold text-darkm my-0">'.$respond->row(0)->company.'</h5>
                <p class="my-0 small font-weight-normal text-dark">'.$respond->row(0)->address1.' '.$respond->row(0)->address2.'</p>
                <p class="my-0 small font-weight-normal text-dark">Tel: '.$respond->row(0)->mobile.'</p>
                <p class="my-0 small font-weight-normal text-dark">Email - '.$respond->row(0)->email.'</p>
            </div>
            <div class="col-6">
                <h5 class="font-weight-bold text-darkm my-0">'.$respond->row(0)->suppliername.'</h5>
                <p class="my-0 small font-weight-normal text-dark">'.$respond->row(0)->address.'</p>
                <p class="my-0 small font-weight-normal text-dark">Tel: '.$respond->row(0)->telephone_no.'</p>
                <p class="my-0 small font-weight-normal text-dark">Email - '.$respond->row(0)->email.'</p>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <table class="table table-bordered table-striped table-sm small">
                    <thead>
                        <tr>
                            <th class="border border-dark">Invoice No</th>
                            <th class="border border-dark">Date</th>
                            <th class="border border-dark text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-dark">'.$respond->row(0)->grnno.'</td>
                            <td class="border border-dark">'.$respond->row(0)->grndate.'</td>
                            <td class="border border-dark text-right">'.number_format($respond->row(0)->amount, 2).'</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="border border-dark" colspan="2">Total Amount</th>
                            <th class="border border-dark text-right">'.number_format($respond->row(0)->amount, 2).'</th>
                        </tr>
                        <tr>
                            <td class="border border-dark" colspan="3">Rupees: '.$rupeetext.'</td>
                        </tr>
                    </tfoot>
                </table>
                <div class="card border border-dark shadow-none bg-transparent">
                    <div class="card-body p-2 small">
                        <div class="row">
                            <div class="col-6">
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <th width="30%">Prepared By</th>
                                        <th width="5%">:</th>
                                        <td class="border border-top-0 border-left-0 border-right-0 border-dark"></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Checked By</th>
                                        <th width="5%">:</th>
                                        <td class="border border-top-0 border-left-0 border-right-0 border-dark"></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Authorized By</th>
                                        <th width="5%">:</th>
                                        <td class="border border-top-0 border-left-0 border-right-0 border-dark"></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Approved By</th>
                                        <th width="5%">:</th>
                                        <td class="border border-top-0 border-left-0 border-right-0 border-dark"></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-6">
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <th width="30%">Received By</th>
                                        <th width="5%">:</th>
                                        <td class="border border-top-0 border-left-0 border-right-0 border-dark"></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Date</th>
                                        <th width="5%">:</th>
                                        <td class="border border-top-0 border-left-0 border-right-0 border-dark"></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Signature</th>
                                        <th width="5%">:</th>
                                        <td class="border border-top-0 border-left-0 border-right-0 border-dark"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ';

        echo $html;
    }
    public function ConvertRupeeToText($amount) {
        $ones = array(
            0 => '',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine',
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen'
        );
    
        $tens = array(
            0 => '',
            2 => 'Twenty',
            3 => 'Thirty',
            4 => 'Forty',
            5 => 'Fifty',
            6 => 'Sixty',
            7 => 'Seventy',
            8 => 'Eighty',
            9 => 'Ninety'
        );
    
        $amount = number_format($amount, 2, '.', '');
        $rupees = intval($amount);
        $paisa = intval(($amount - $rupees) * 100);
    
        $words = '';
    
        if ($rupees > 0) {
            if ($rupees >= 10000000) {
                $crore = intval($rupees / 10000000);
                $words .= $ones[$crore] . ' Crore ';
                $rupees %= 10000000;
            }
    
            if ($rupees >= 100000) {
                $lakh = intval($rupees / 100000);
                $words .= $ones[$lakh] . ' Lakh ';
                $rupees %= 100000;
            }
    
            if ($rupees >= 1000) {
                $thousand = intval($rupees / 1000);
                $words .= $ones[$thousand] . ' Thousand ';
                $rupees %= 1000;
            }
    
            if ($rupees >= 100) {
                $hundred = intval($rupees / 100);
                $words .= $ones[$hundred] . ' Hundred ';
                $rupees %= 100;
            }
    
            if ($rupees > 0) {
                if ($rupees < 20) {
                    $words .= $ones[$rupees];
                } else {
                    $words .= $tens[intval($rupees / 10)];
                    $words .= ' ' . $ones[$rupees % 10];
                }
            }
    
            $words .= ' Rupees ';
        }
    
        if ($paisa > 0) {
            if ($paisa < 20) {
                $words .= $ones[$paisa];
            } else {
                $words .= $tens[intval($paisa / 10)];
                $words .= ' ' . $ones[$paisa % 10];
            }
    
            $words .= ' Paisa';
        } else {
            $words .= 'Only';
        }
    
        return ucwords(strtolower(trim($words)));
    }  
}