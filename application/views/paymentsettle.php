<?php 
include "include/header.php";  
include "include/topnavbar.php"; 
?>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="page-header page-header-light bg-white shadow">
                <div class="container-fluid">
                    <div class="page-header-content py-3">
                        <h1 class="page-header-title font-weight-light">
                            <div class="page-header-icon"><i class="fas fa-wallet"></i></div>
                            <span>Payment Settle</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="filterpostdated">
                                    <label class="custom-control-label" for="filterpostdated">View post-dated settlements</label>
                                </div>
                            </div>
                            <div class="col-6 text-right">
                                <button class="btn btn-orange btn-sm px-4 mr-1" id="btnreceiptprint"><i class="fas fa-print mr-2"></i>Receipt Print</button>
                                <button class="btn btn-primary btn-sm px-4" id="btncreatesegregation" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus mr-2"></i>Payment Settle Create</button>
                            </div>
                            <div class="col-12">
                                <hr>
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Payment Filter Type</th>
                                                <th>Payment No</th>
                                                <th>Pay Type</th>
                                                <th>Company</th>
                                                <th>Branch</th>
                                                <th>Year</th>
                                                <th>Month</th>
                                                <th>Tra. Date</th>
                                                <th>Batch No</th>
                                                <th>Supplier</th>
                                                <th>Cheque No</th>
                                                <th>Bank</th>
                                                <th>Account No</th>
                                                <th>Account Name</th>
                                                <th>Amount</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<!-- Modal Company Choose -->
<div class="modal fade" id="modalcompanychoose" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalcompanychooseLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modalcompanychooseLabel">Company Information</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="choosecompanyinfoform">
                    <div class="row">
                        <div class="col-12">
                            <label class="small font-weight-bold">Company*</label>
                            <select name="company" id="company" class="form-control form-control-sm" required>
                                <!-- <option value="">Select</option> -->
                                <?php foreach($companylist as $rowcompanylist){ ?>
                                <option value="<?php echo $rowcompanylist->idtbl_company ?>"><?php echo $rowcompanylist->company ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="small font-weight-bold">Branch*</label>
                            <select name="branch" id="branch" class="form-control form-control-sm" required>
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 text-right">
                            <button type="button" class="btn btn-primary btn-sm px-4" id="btnchoosecominfo" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-check mr-2"></i>Submit</button>
                            <input type="submit" class="d-none" id="hidecomchoosesubmit">
                        </div>
                    </div>
                </form>
			</div>
		</div>
	</div>
</div>
<!-- Modal Receivable -->
<div class="modal fade" id="modalreceivable" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalreceivableLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modalreceivableLabel">Create Payment Settle</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form id="invoicepaymentform">
                            <div class="form-row">
                                <div class="col">
                                    <label class="small font-weight-bold">Company*</label>
                                    <input type="text" name="showcompany" id="showcompany" class="form-control form-control-sm" readonly>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Branch*</label>
                                    <input type="text" name="showbranch" id="showbranch" class="form-control form-control-sm" readonly>
                                </div>
                                <div class="col-3">
                                    <label class="small font-weight-bold">Paid Date*</label>
                                    <input type="date" name="paiddate" id="paiddate" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                            <div class="form-row mt-2">
                                <div class="col">
                                    <label class="small font-weight-bold">Payble Filter</label>
                                    <div class="input-group input-group-sm">
                                        <select name="payablefilter" id="payablefilter" class="form-control form-control-sm" style="width:100%;" required>
                                            <option value="">Select</option>
                                            <option value="1">Payment Supplier</option>
                                            <option value="2">Payment Journal</option>
                                            <option value="3">Payment Voucher</option>
                                        </select>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <input type="checkbox" class="mr-2" id="checkadvanced" name="checkadvanced" value="1" aria-label="Checkbox for following text input" disabled><label class="form-check-label" for="checkadvanced">As advance payment</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Advance Payment Supplier</label>
                                    <select name="advancesupplier" id="advancesupplier" style="width: 100%;" class="form-control form-control-sm" disabled>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Supplier | Account no*</label><br>
                                    <select name="supplier" id="supplier" class="form-control form-control-sm" style="width:100%;" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>  
                            <div class="form-row mt-2 d-none" id="divpaymentlist">
                                <div class="col">
                                    <label class="small font-weight-bold">Description*</label>
                                    <input type="text" name="settledesc" id="settledesc" class="form-control form-control-sm" value="" readonly>
                                </div>
                                <div class="col-3">
                                    <label class="small font-weight-bold">Amount*</label>
                                    <input type="text" name="settleamount" id="settleamount" class="form-control form-control-sm text-right input-integer" value="" readonly>
                                </div>
                                <div class="col-2 text-right">
                                    <label class="small font-weight-bold">&nbsp;</label><br>
                                    <button type="button" class="btn btn-primary btn-sm w-100" id="btnaddlist" disabled><i class="fas fa-list mr-2"></i>Add List</button>
                                </div>
                            </div>                      
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="collapse" id="collapsevoucherlist">
                                        <div class="card card-body shadow-none p-0 border-0 rounded-0">
                                            <h6 class="small title-style"><span>Voucher Payment Information</span></h6>
                                            <table class="table table-striped table-bordered table-sm small mb-0" id="tablevoucherlist">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Advance Payment</th>
                                                        <th class="d-none">AdvanceType</th>
                                                        <th>Advance Pay Supplier</th>
                                                        <th class="d-none">AdvancePaySuppID</th>
                                                        <th>Account | Supplier</th>
                                                        <th class="d-none">AccountID</th>
                                                        <th class="d-none">AccountType</th>
                                                        <th>Description</th>
                                                        <th class="text-right">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="collapse" id="collapseAdvancePayment">
                                        <div class="card card-body shadow-none p-0 border-0 rounded-0">
                                            <h6 class="small title-style"><span>Advance Payment Information</span></h6>
                                            <table class="table table-striped table-bordered table-sm small mb-0" id="tableAdvancepayment">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">&nbsp;</th>
                                                        <th class="d-none">Advancepay ID</th>
                                                        <th>Receipt Date</th>
                                                        <th class="d-none">Supplier ID</th>
                                                        <th>Supplier</th>
                                                        <th class="d-none">Payment Receipt No</th>
                                                        <th>Payment Receipt</th>
                                                        <th class="text-right">Advance Payment</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div> 
                                    <div class="collapse mt-3" id="collapseInvoiceInfo">
                                        <div class="card card-body shadow-none border-0 p-0">
                                            <h6 class="small title-style"><span>Invoice Information</span></h6>
                                            <table class="table table-striped table-bordered table-sm small" id="tableinvoicepayment">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">&nbsp;</th>
                                                        <th class="d-none">Supplier ID</th>
                                                        <th>Supplier | Account no</th>
                                                        <th class="d-none">Invoice ID</th>
                                                        <th>Bill / Invoice No</th>
                                                        <th>Supplier Invoice No</th>
                                                        <th class="text-right">Total</th>
                                                        <th class="text-right">Balance Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="small title-style"><span>Payable Account</span></h6>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-2">
                                    <label class="small font-weight-bold">Invoice Total*</label>
                                    <input type="text" name="invoicepayamount" id="invoicepayamount" class="form-control form-control-sm text-right" value="0" readonly>
                                </div>
                                <div class="col-3">
                                    <label class="small font-weight-bold">Payable Type*</label><br>
                                    <select name="payabletype" id="payabletype" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php foreach($payabletype->result() as $rowpayabletype){ ?>
                                        <option value="<?php echo $rowpayabletype->idtbl_receivable_type ?>"><?php echo $rowpayabletype->receivabletype ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label class="small font-weight-bold">Account No*</label>
                                    <select name="chartofdetailaccount" id="chartofdetailaccount" class="form-control form-control-sm" style="width: 100%;" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Cheq. | Dep. | Trans. | Pay. Date</label>
                                    <div class="input-group input-group-sm">
                                        <input type="date" name="chequedate" id="chequedate" min="<?php // echo date('Y-m-d'); ?>" class="form-control">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <input type="checkbox" class="mr-2" id="checkpostdated" name="checkpostdated" value="1" aria-label="Checkbox for following text input"><label class="form-check-label" for="checkpostdated">Post-dated</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-4">
                                    <label class="small font-weight-bold">Payee info</label>
                                    <div class="input-group input-group-sm">
                                        <input type="test" name="chequepayee" id="chequepayee" class="form-control" readonly>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <input type="checkbox" class="mr-2" id="checkaccountpay" name="checkaccountpay" value="1" aria-label="Checkbox for following text input" disabled><label class="form-check-label" for="checkaccountpay">A/C pay only</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <label class="small font-weight-bold">Narration</label>
                                    <input type="text" name="narration" id="narration" class="form-control form-control-sm" required>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold mr-2">Paid Amount* </label>                                   
                                    <input type="text" name="paidamount" id="paidamount" class="form-control form-control-sm text-right input-integer" required>
                                </div>
                            </div>
                            <input type="hidden" name="recordOption" id="recordOption" value="1">
                            <input type="hidden" name="recordID" id="recordID" value="">

                            <input type="submit" id="hidesegsubmit" class="d-none">
                            <input type="reset" id="hidesegreset" class="d-none">
                        </form>
                    </div>
                    <div class="col-12">
                        
                        <hr class="border-dark">
                    </div>
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary btn-sm px-4" id="btnfullinvoicepayment" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-save mr-2"></i>Complete</button>
                    </div>
                    <div class="col-12 d-none" id="divadvalert">
                        <div class="alert alert-primary mt-2" role="alert">
                            If you select an advance payment, please issue the payment voucher before entering any other payment methods.
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
<!-- Modal Payment Segregation -->
<div class="modal fade" id="modalviewpost" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalviewpostLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modalviewpostLabel">View & Post Information</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <div id="viewdiv"></div>
                <div class="row">
                    <div class="col-12 text-right">
                        <hr class="border-dark">
                        <input type="hidden" name="receiableid" id="receiableid">
                        <button type="button" class="btn btn-danger btn-sm px-4" id="btnposttransaction" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-exchange-alt mr-2"></i>Post Transaction</button>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
<!-- Modal Payment Segregation -->
<div class="modal fade" id="modalreceiptprint" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalreceiptprintLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modalreceiptprintLabel">Print Receipt Information</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <form id="formprint">
                    <div class="form-row mb-1">
                        <div class="col">
                            <label class="small font-weight-bold">Print Type*</label>
                            <select name="printtype" id="printtype" class="form-control form-control-sm" required>
                                <option value="">Select</option>
                                <option value="1">Invoice | Payment No</option>
                                <option value="2">Payment Receipt</option>
                            </select>
                        </div>
                        <div class="col">
                            <label class="small font-weight-bold">Supplier*</label><br>
                            <select name="printsupplier" id="printsupplier" class="form-control form-control-sm" style="width: 100%;">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <div class="col">
                            <label class="small font-weight-bold">Date*</label>
                            <input type="date" name="printdate" id="printdate" class="form-control form-control-sm">
                        </div>
                        <div class="col">
                            <label class="small font-weight-bold">Invoice | Receipt*</label><br>
                            <select name="printinvoicereceipt" id="printinvoicereceipt" class="form-control form-control-sm" style="width: 100%;" required>
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col text-right">
                            <hr>
                            <button type="button" class="btn btn-primary btn-sm px-4" id="printbtnshow">Print Receipt</button>
                            <input type="submit" id="hideprintsubmit" class="d-none">
                        </div>
                    </div>
                </form>
			</div>
		</div>
	</div>
</div>
<script>
    $(document).ready(function() {
        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        // $('#chartofdetailaccount').select2({dropdownParent: $('#modalreceivable')});
        $('.input-integer').inputNumber({
            allowDecimals: true, allowNegative: false, thousandSep: ',', maxDecimalDigits: 2
        });
        $('#payabletype').change(function(){
            if($(this).val()==2){
                $('#checkpostdated').prop('checked', false);
                $('#checkpostdated').prop('disabled', false);
                $('#checkaccountpay').prop('checked', false);
                $('#checkaccountpay').prop('disabled', false);
                $('#chequepayee').prop('readonly', false);
                $('#chequepayee').prop('required', true);
                $('#chequedate').prop('required', true);
                if($('#payablefilter').val() < 3){
                    var payeetext = $('#supplier option:selected').text();
                    $('#chequepayee').val(payeetext);
                }
            }
            else{
                $('#checkpostdated').prop('checked', false);
                $('#checkpostdated').prop('disabled', true);
                $('#checkaccountpay').prop('checked', false);
                $('#checkaccountpay').prop('disabled', true);
                $('#chequepayee').val('').prop('readonly', true);
                $('#chequepayee').prop('required', false);
                $('#chequedate').prop('required', false);
            }
        });

        $('#filterpostdated').change(function() {
            $('#dataTable').DataTable().ajax.reload(null, false);
        });

        $('#payablefilter').change(function() {
            var payablefilter = $(this).val();

            if(payablefilter == 1 || payablefilter == 2){ 
                $('#collapseInvoiceInfo').collapse('show');
                if(payablefilter == 1){
                    $('#collapseAdvancePayment').collapse('show');
                }
                $('#invoicepayamount').prop('readonly', true).val('0');

                $('#checkadvanced').prop('checked', false);
                $('#checkadvanced').prop('disabled', true);
                $("#advancesupplier").val('').trigger('change');
                $("#advancesupplier").prop('disabled', true);

                $('#collapsevoucherlist').collapse('hide');
                $('#divpaymentlist').addClass('d-none');
                $('#btnaddlist').prop('disabled', true);
                $('#settleamount').prop('readonly', true);
                $('#settledesc').prop('readonly', true);
                $('#supplier').prop('required', true);

                $('#tablevoucherlist tbody').empty();
            }
            else if(payablefilter == 3){
                $('#collapseInvoiceInfo').collapse('hide');
                $('#collapseAdvancePayment').collapse('hide');
                // $('#invoicepayamount').prop('readonly', false).val('');
                $('#checkadvanced').prop('disabled', false);

                $('#collapsevoucherlist').collapse('show');
                $('#divpaymentlist').removeClass('d-none');
                $('#btnaddlist').prop('disabled', false);
                $('#settleamount').prop('readonly', false);
                $('#settledesc').prop('readonly', false);
                $('#supplier').prop('required', false);
            }
            else{
                $('#collapseInvoiceInfo').collapse('hide');
                $('#collapseAdvancePayment').collapse('hide');
                $('#invoicepayamount').prop('readonly', true).val('0');

                $('#checkadvanced').prop('checked', false);
                $('#checkadvanced').prop('disabled', true);
                $("#advancesupplier").val('').trigger('change');
                $("#advancesupplier").prop('disabled', true);

                $('#collapsevoucherlist').collapse('hide');
                $('#divpaymentlist').addClass('d-none');
                $('#btnaddlist').prop('disabled', true);
                $('#settleamount').prop('readonly', true);
                $('#settledesc').prop('readonly', true);
                $('#supplier').prop('required', true);
                $('#tablevoucherlist tbody').empty();
            }
        });

        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "<?php echo base_url() ?>scripts/payablelist.php",
                type: "POST", // you can use GET
                data: function(d) {
                    d.userID = '<?php echo $_SESSION['userid']; ?>';
                    d.filterpost = $('#filterpostdated').is(':checked') ? 1 : 0;
                }
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_account_paysettle"
                },
                {
                    "data": "paymentfiltertype"
                },
                {
                    "data": "paymentno"
                },
                {
                    "data": "receivabletype"
                },
                {
                    "data": "company"
                },
                {
                    "data": "branch"
                },
                {
                    "data": "desc"
                },
                {
                    "data": "monthname"
                },
                {
                    "data": "date"
                },
                {
                    "data": "batchno"
                },
                {
                    "data": "suppliername"
                },
                {
                    "data": "chequeno"
                },
                {
                    "data": "bankname"
                },
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function(data, type, full) {
                        if(full['detailaccountno']!=null){
                            return full['detailaccountno'];
                        }
                        else{
                            return full['accountno'];
                        }
                    }
                },
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function(data, type, full) {
                        if(full['detailaccountname']!=null){
                            return full['detailaccountname'];
                        }
                        else{
                            return full['accountname'];
                        }
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        return addCommas(parseFloat(full['totalpayment']).toFixed(2));
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        if(full['status']==3){
                            button='<span class="text-danger">Voucher cancelled</span>';
                        }
                        else{
                            button+='<button class="btn btn-dark btn-sm btnview mr-1" id="'+full['idtbl_account_paysettle']+'" data-toggle="tooltip" data-placement="bottom" title="View and post" data-poststatus="'+full['poststatus']+'" data-recordstatus="'+full['status']+'" data-recordtype="'+full['idtbl_account_paysettle_type']+'" data-postdatedstatus="'+full['postdatedstatus']+'" data-chequedate="'+full['chedate']+'">';
                            if(full['poststatus']==0){
                                button+='<i class="fas fa-exchange-alt"></i>';
                            }
                            else{
                                button+='<i class="fas fa-eye"></i>';
                            }
                            button+='</button>';
                            if(full['poststatus']==0){
                                if(full['status']==1 && statuscheck==1){
                                    button+='<button type="button" data-url="Paymentsettle/Paymentsettlestatus/'+full['idtbl_account_paysettle']+'/2" data-actiontype="2" class="btn btn-success btn-sm mr-1 btntableaction"><i class="fas fa-check"></i></button>';
                                }else if(full['status']==2 && statuscheck==1){
                                    button+='<button type="button" data-url="Paymentsettle/Paymentsettlestatus/'+full['idtbl_account_paysettle']+'/1" data-actiontype="1" class="btn btn-warning btn-sm mr-1 text-light btntableaction"><i class="fas fa-times"></i></button>';
                                }
                                if(deletecheck==1){
                                    button+='<button type="button" id="'+full['idtbl_account_paysettle']+'" class="btn btn-danger btn-sm text-light btnpaycancel"><i class="fas fa-trash-alt"></i></button>';
                                }
                            }
                        }
                        
                        return button;
                    }
                }
            ],
            drawCallback: function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            },
            createdRow: function( row, data, dataIndex){
                if( data['status'] == 3 ) {
                    $(row).addClass('table-danger');
                }
                else if ( data['poststatus'] == 1 ) {
                    $(row).addClass('table-primary');
                }           
            }
        });
        $('#dataTable tbody').on('click', '.btnEdit', async function() {
            var r = await Otherconfirmation("You want to Edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: '<?php echo base_url() ?>Receivablesettle/Receivablesettleedit',
                    success: function(result) { //alert(result);
                        console.log(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#company').val(obj.companyid); 
                        getbranchlist(obj.companyid, obj.branchid);  

                        $('#customer').append('<option value="'+obj.customerid+'" selected>'+obj.customer+'</option>').trigger('change').prop('disabled', true); 

                        $('#showcompany').val(obj.company);                       
                        $('#showbranch').val(obj.branch);   
                        $('#tableinvoicepayment > tbody').append(obj.tabledata);  
                                        
                        $('#receivabletype').val(obj.receivetype);                       
                        $('#chequedate').val(obj.chequedate);                       
                        $('#chequeno').val(obj.chequeno);                         
                        $('#narration').val(obj.narration);                       
                        $('#invoicepayamount').val(obj.amount);                       

                        getaccountlist(obj.companyid, obj.branchid, obj.receivetype, obj.account);
                        checkinvoicecomplete()                     
                        
                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                        $('#modalreceivable').modal('show');                   
                    }
                });
            }
        });
        $('#dataTable tbody').on('click', '.btnview', function() {
            var id = $(this).attr('id');
            var recordstatus = $(this).attr("data-recordstatus");
            if(recordstatus==1){$('#btnposttransaction').removeClass('d-none');}
            else{$('#btnposttransaction').addClass('d-none');}

            var poststatus = $(this).attr("data-poststatus");
            var recordtype = $(this).attr("data-recordtype");
            var postdatedstatus = $(this).attr("data-postdatedstatus");
            var chequedate = $(this).attr("data-chequedate");
            if(poststatus==1){$('#btnposttransaction').prop('disabled', true);}
            else if (postdatedstatus == 1 && chequedate > new Date().toISOString().split('T')[0]){$('#btnposttransaction').prop('disabled', true);}
            else{$('#btnposttransaction').prop('disabled', false);}

            $('#receiableid').val(id);

            $('#modalviewpost').modal('show');
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: '<?php echo base_url() ?>Paymentsettle/Getviewpostinfo',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);
                    $('#viewdiv').html(obj.html);
                    if(obj.editablestatus==1){$('#btnposttransaction').addClass('d-none');}
                    else{$('#btnposttransaction').removeClass('d-none');}
                }
            });
        });
        $('#dataTable tbody').on('click', '.btnpaycancel', async function() {
            var id = $(this).attr('id');

            const { value: accept } = await Swal.fire({
                title: "Are you sure?",
                text: 'You want to cancel this payment settlement!',
                icon: "warning",
                html: `
                    <div class="swal-text mb-3">
                        You want to cancel this payment settlement!
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="chequecancel" name="chequecancel" value="1">
                        <label class="custom-control-label text-danger font-weight-bold" for="chequecancel">Do you want cancel issued cheques.</label>
                    </div>
                `,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirm",
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-danger'
                },
                showCancelButton: true,
                cancelButtonText: "Cancel"
            });

            if (accept) {
                var chequecancel = $('#chequecancel').is(':checked') ? 1 : 0;
                
                Swal.fire({
                    title: '',
                    html: '<div class="div-spinner"><div class="custom-loader"></div></div>',
                    allowOutsideClick: false,
                    showConfirmButton: false, // Hide the OK button
                    backdrop: `
                        rgba(255, 255, 255, 0.5) 
                    `,
                    customClass: {
                        popup: 'fullscreen-swal'
                    },
                    didOpen: () => {
                        document.body.style.overflow = 'hidden';
                        $.ajax({
                            type: "POST",
                            data: {
                                recordID: id,
                                chequecancel: chequecancel
                            },
                            url: '<?php echo base_url() ?>Paymentsettle/Paymentsettlecancel',
                            success: function(result) { // alert(result);
                                var obj = JSON.parse(result);
                                Swal.close();
                                if (obj.status == 1) {
                                    $('#dataTable').DataTable().ajax.reload(null, false);
                                }
                                action(obj.action);
                            },
                            error: function(error) {
                                // Close the SweetAlert on error
                                Swal.close();
                                
                                // Show an error alert
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Something went wrong. Please try again later.'
                                });
                            }
                        });
                        document.body.style.overflow = 'visible';
                    }
                });
            }
        });

        // $('#company').change(function(){
        //     var id = $(this).val();
        //     getbranchlist(id, '');
        // });
        $('#btncreatesegregation').click(function() {
            getbranchlist('<?php echo $_SESSION['companyid'] ?>', '');
        });
        $('#btnchoosecominfo').click(function(){
            if (!$("#choosecompanyinfoform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidecomchoosesubmit").click();
            } else {
                $('#showcompany').val($("#company option:selected").text());
                $('#showbranch').val($("#branch option:selected").text());

                $('#modalcompanychoose').modal('hide');
                $('#modalreceivable').modal('show');
            }
        });
        $("#supplier").select2({
            dropdownParent: $('#modalreceivable'),
            ajax: {
                url: "<?php echo base_url() ?>Paymentsettle/Getsupplierlist",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term, // search term
                        payablefilter : $('#payablefilter').val()
                    };
                },
                processResults: function (response) {
                    var payablefilter = $('#payablefilter').val();

                    if (payablefilter == 2 || payablefilter == 3) {
                        return {
                            results: response.map(function (item) {
                                return {
                                    id: item.id,
                                    text: item.text,
                                    data: {
                                        type: item.acctype
                                    }
                                };
                            })
                        };
                    }

                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
        $('#supplier').change(function(){
            var id = $(this).val();
            var payablefilter = $('#payablefilter').val();
            var accounttype = '';
            if(payablefilter == 2){
                var selectedData = $('#supplier').select2('data')[0];
                // var accounttype = selectedData ? selectedData.data.type : null;
                var accounttype = (selectedData && selectedData.data) ? selectedData.data.type : null;
            }

            if(payablefilter < 3){
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id,
                        payablefilter : payablefilter,
                        accounttype : accounttype
                    },
                    url: '<?php echo base_url() ?>Paymentsettle/Getinvoiceaccosupplier',
                    success: function(result) { // alert(result);
                        $('#tableinvoicepayment tbody').empty().append(result);
                    }
                });
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id,
                        payablefilter : payablefilter,
                        accounttype : accounttype
                    },
                    url: '<?php echo base_url() ?>Paymentsettle/Getadvanceaccosupplier',
                    success: function(result) { // alert(result);
                        $('#tableAdvancepayment tbody').empty().append(result);
                    }
                });
            }
        });
        
        $('#tableinvoicepayment tbody').on('click', '.checkclick', function() {
            if ($(this).is(':checked')) {
                checkinvoicecomplete();
            } else {
                checkinvoicecomplete();
            }
        });

        $('#payabletype').change(function(){
            var receivetype = $(this).val();
            var companyid = $('#company').val();
            var branchid = $('#branch').val();

            getaccountlist(companyid, branchid, receivetype, '');
        });

        $('#btnfullinvoicepayment').click(function(){
            if (!$("#invoicepaymentform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesegsubmit").click();
            } else {
                $('#btnfullinvoicepayment').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i> Complete');
                var tablelist = $("#tableinvoicepayment tbody input[type=checkbox]:checked");
                var payablefilter = $('#payablefilter').val();
                var invoicePayAmount = parseFloat($('#invoicepayamount').val().replace(/,/g, '')) || 0;
                
                if (payablefilter == 3 && invoicePayAmount > 0 || tablelist.length > 0) {
                    $('#supplier').attr('disabled', false);
                    jsonObj = [];
                    tablelist.each(function() {
                        item = {}
                        var row = $(this).closest("tr");
                        item["supid"] = row.find('td:eq(1)').text();
                        item["supplier"] = row.find('td:eq(2)').text();
                        item["invid"] = row.find('td:eq(3)').text();
                        item["invoiceno"] = row.find('td:eq(4)').text();
                        item["amount"] = row.find('td:eq(7)').text();
                        jsonObj.push(item);
                    });
                    var myJSON = JSON.stringify(jsonObj);

                    jsonObjAdvance = [];
                    $('#tableAdvancepayment tbody tr').each(function() {
                        item = {}
                        var row = $(this).closest("tr");
                        item["advid"] = row.find('td:eq(1)').text();
                        item["paysetno"] = row.find('td:eq(5)').text();
                        item["amount"] = row.find('td:eq(7)').text();
                        jsonObjAdvance.push(item);
                    });
                    var myJSONAdvance = JSON.stringify(jsonObjAdvance);

                    jsonObjVoucher = [];
                    $('#tablevoucherlist tbody tr').each(function() {
                        item = {}
                        var row = $(this).closest("tr");
                        item["voucherdate"] = row.find('td:eq(0)').text();
                        item["advancetext"] = row.find('td:eq(1)').text();
                        item["advancestatus"] = row.find('td:eq(2)').text();
                        item["advancesupplier"] = row.find('td:eq(3)').text();
                        item["advancesupplierid"] = row.find('td:eq(4)').text();
                        item["accountname"] = row.find('td:eq(5)').text();
                        item["accountid"] = row.find('td:eq(6)').text();
                        item["accounttype"] = row.find('td:eq(7)').text();
                        item["desc"] = row.find('td:eq(8)').text();
                        item["amount"] = row.find('td:eq(9)').text();
                        jsonObjVoucher.push(item);
                    });
                    var myJSONVoucher = JSON.stringify(jsonObjVoucher);


                    var recordID = $('#recordID').val();
                    var recordOption = $('#recordOption').val();
                    var company = $('#company').val();
                    var branch = $('#branch').val();
                    var supplierID = $('#supplier').val();
                    if($('#payablefilter').val() == 2){
                        var selectedSupplierData = $('#supplier').select2('data')[0];
                        var supplierAccountType = selectedSupplierData ? selectedSupplierData.data.type : null;
                    }
                    else{
                        var supplierAccountType = '';
                    }
                    var payabletype = $('#payabletype').val();
                    var chequedate = $('#chequedate').val();
                    // var chequeno = $('#chequeno').val();
                    var chartofdetailaccount = $('#chartofdetailaccount').val();
                    // var accounttype = $('#chartofdetailaccount').find(':selected').attr('data-type');;
                    var selectedData = $('#chartofdetailaccount').select2('data')[0];
                    var accounttype = selectedData ? selectedData.data.type : null;
                    var narration = $('#narration').val();
                    var invoicepayamount = $('#invoicepayamount').val();
                    var paidamount = $('#paidamount').val();
                    var paiddate = $('#paiddate').val();
                    if ($('#checkpostdated').is(':checked')) {var postdated = '1';}
                    else{var postdated = '0';}

                    if ($('#checkaccountpay').is(':checked')) {var checkaccountpay = '1';}
                    else{var checkaccountpay = '0';}
                    var chequepayee = $('#chequepayee').val();

                    if ($('#checkadvanced').is(':checked')) {
                        var checkadvanced = '1';
                        var advancesupplierID = $('#advancesupplier').val();
                    }
                    else{
                        var checkadvanced = '0';
                        var advancesupplierID = '0';
                    }

                    Swal.fire({
                        title: '',
                        html: '<div class="div-spinner"><div class="custom-loader"></div></div>',
                        allowOutsideClick: false,
                        showConfirmButton: false, // Hide the OK button
                        backdrop: `
                            rgba(255, 255, 255, 0.5) 
                        `,
                        customClass: {
                            popup: 'fullscreen-swal'
                        },
                        didOpen: () => {
                            document.body.style.overflow = 'hidden';

                            $.ajax({
                                type: "POST",
                                data: {
                                    tableData: myJSON,
                                    tableAdvData: myJSONAdvance,
                                    tableVoucherData: myJSONVoucher,
                                    company: company,
                                    branch: branch,
                                    supplier: supplierID,
                                    payabletype: payabletype,
                                    chequedate: chequedate,
                                    chartofdetailaccount: chartofdetailaccount,
                                    narration: narration,
                                    invoicepayamount: invoicepayamount,
                                    paidamount: paidamount,
                                    accounttype: accounttype,
                                    paiddate: paiddate,
                                    postdated: postdated,
                                    payablefilter: payablefilter,
                                    supplierAccountType: supplierAccountType,
                                    checkaccountpay: checkaccountpay,
                                    chequepayee: chequepayee,
                                    checkadvanced: checkadvanced,
                                    advancesupplierID: advancesupplierID,
                                    recordOption: recordOption,
                                    recordID: recordID
                                },
                                url: 'Paymentsettle/Paymentsettleinsertupdate',
                                success: function (result) { //alert(result);
                                    // console.log(result);
                                    Swal.close();
                                    var obj = JSON.parse(result);
                                    if (obj.status == 1) {
                                        // $('#hidesegreset').click();
                                        $('#tableinvoicepayment> tbody').empty();
                                        $('#supplier').val('').trigger('change');
                                        $('#payabletype').val('').trigger('change');
                                        $('#chequedate').val('');
                                        // $('#chequeno').val('');
                                        $('#chartofdetailaccount').val('').trigger('change');
                                        $('#narration').val('');
                                        $('#invoicepayamount').val('0');
                                        $('#paidamount').val('0');
                                        $('#btnfullinvoicepayment').prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Complete');
                                        $('#payablefilter').val('').trigger('change');

                                        if(recordOption==2){
                                            setTimeout( function(){ 
                                                $('#modalreceivable').modal('hide');
                                            } ,3000 );
                                        }
                                    }
                                    action(obj.action);
                                },
                                error: function(error) {
                                    // Close the SweetAlert on error
                                    Swal.close();
                                    
                                    // Show an error alert
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Something went wrong. Please try again later.'
                                    });
                                }
                            });

                            document.body.style.overflow = 'visible';
                        }
                    });
                }
                else {                    
                    if($('#invoicepayamount').val() == 0 && payablefilter == 3){
                        Swal.fire({
                            icon: 'warning',
                            title: 'No entry',
                            text: 'Please add at least one payment voucher entry to list.'
                        });
                    }
                    else if(tablelist.length == 0){
                        Swal.fire({
                            icon: 'warning',
                            title: 'No invoice selected',
                            text: 'Please select at least one invoice to proceed.'
                        });
                    }
                    
                    $('#btnfullinvoicepayment').prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Complete');
                }
            }
        }); 

        $('#modalreceivable').on('hidden.bs.modal', function (event) {
            window.location.reload();
        }); 

        $('#btnposttransaction').click(async function() {
            var r = await Otherconfirmation("You want to post this transaction ? ");
            if (r == true) {
                $('#btnposttransaction').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i> Post Transaction');
                var mainpreceiveID = $('#receiableid').val();

                Swal.fire({
                    title: '',
                    html: '<div class="div-spinner"><div class="custom-loader"></div></div>',
                    allowOutsideClick: false,
                    showConfirmButton: false, // Hide the OK button
                    backdrop: `
                        rgba(255, 255, 255, 0.5) 
                    `,
                    customClass: {
                        popup: 'fullscreen-swal'
                    },
                    didOpen: () => {
                        document.body.style.overflow = 'hidden';

                        $.ajax({
                            type: "POST",
                            data: {
                                recordID: mainpreceiveID
                            },
                            url: 'Paymentsettle/Paymentsettleposting',
                            success: function (result) { //alert(result);
                                Swal.close();
                                var obj = JSON.parse(result);
                                if (obj.status == 1) {
                                    setTimeout( function(){ 
                                        $('#modalviewpost').modal('hide');
                                        $('#dataTable').DataTable().ajax.reload( null, false );
                                        $('#btnposttransaction').prop('disabled', true).html('<i class="fas fa-exchange-alt mr-2"></i> Post Transaction');
                                    } ,3000 );
                                }
                                action(obj.action);
                            },
                            error: function(error) {
                                // Close the SweetAlert on error
                                Swal.close();
                                
                                // Show an error alert
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Something went wrong. Please try again later.'
                                });
                            }
                        });

                        document.body.style.overflow = 'visible';
                    }
                });
            }
        });
        $('#btnreceiptprint').click(function(){
            $('#modalreceiptprint').modal('show');
        });

        $("#printsupplier").select2({
            dropdownParent: $('#modalreceiptprint'),
            ajax: {
                url: "<?php echo base_url() ?>Paymentsettle/Getsupplierlist",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term, // search term
                        payablefilter : '1'
                    };
                },
                processResults: function (response) {
                    var payablefilter = '1';

                    if (payablefilter == 2 || payablefilter == 3) {
                        return {
                            results: response.map(function (item) {
                                return {
                                    id: item.id,
                                    text: item.text,
                                    data: {
                                        type: item.acctype
                                    }
                                };
                            })
                        };
                    }

                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $('#printtype').change(function(){
            getinvoicereceiptno();
        });
        $('#printsupplier').change(function(){
            getinvoicereceiptno();
        });
        $('#printdate').change(function(){
            getinvoicereceiptno();
        });
        $('#printbtnshow').click(function(){
            if (!$("#formprint")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hideprintsubmit").click();
            } else {
                var printtype = $('#printtype').val();
                var printinvoicereceipt = $('#printinvoicereceipt').val();
                window.open("<?php echo base_url() ?>Reportprint/Paymentsettlereceipt/"+printinvoicereceipt+"/"+printtype, "_blank");
            }
        });

        $('#checkadvanced').change(function(){
            if ($('#checkadvanced').is(':checked')) {
                $("#advancesupplier").prop('disabled', false);
            }
            else{
                $("#advancesupplier").val('').trigger('change');
                $("#advancesupplier").prop('disabled', true);
            }
        });
        $("#advancesupplier").select2({
            dropdownParent: $('#modalreceivable'),
            ajax: {
                url: "<?php echo base_url() ?>Paymentsettle/Getsupplierlist",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term, // search term
                        payablefilter : '1'
                    };
                },
                processResults: function (response) {
                    var payablefilter = '1';

                    if (payablefilter == 2 || payablefilter == 3) {
                        return {
                            results: response.map(function (item) {
                                return {
                                    id: item.id,
                                    text: item.text,
                                    data: {
                                        type: item.acctype
                                    }
                                };
                            })
                        };
                    }

                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
        $('#tableAdvancepayment tbody').on('click', '.checkadvanceclick', function() {
            if ($(this).is(':checked')) {
                checkunapplied();
            } else {
                checkunapplied();
            }
        });

        $('#btnaddlist').click(function(){
            var payablefilter = $('#payablefilter').val();
            var advancechecked = $('#checkadvanced').is(':checked') ? 1 : 0;
            var advancecheckedtext = $('#checkadvanced').is(':checked') ? 'Advance' : '';
            var advancesupplier = $('#advancesupplier').val();
            var advancesupplierText = $('#checkadvanced').is(':checked') ? $('#advancesupplier').find(':selected').text() : '';
            var accountid = $('#supplier').val();
            var selectedSupplierData = $('#supplier').select2('data')[0];
            var accounttype = selectedSupplierData ? selectedSupplierData.data.type : null;
            
            var accounttext = $('#supplier').find(':selected').text();
            var description = $('#settledesc').val();
            var settleamount = $('#settleamount').val();
            var paiddate = $('#paiddate').val();

            if(payablefilter != '3'){
                $swl.fire({
                    title: 'Warning',
                    text: 'This method only use for payment voucher. Please select payment voucher in payable filter.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
            }
            else if(advancechecked == 1 && advancesupplier == ''){
                var el = document.getElementById('advancesupplier');
                el.setCustomValidity('Please select an advance payment supplier.');
                el.reportValidity();
                el.oninput = function(){ this.setCustomValidity(''); };
            }
            else if(accountid == ''){
                var el = document.getElementById('supplier');
                el.setCustomValidity('Please select a supplier / account no.');
                el.reportValidity();
                el.onchange = function(){ this.setCustomValidity(''); };
            }
            else if(description == ''){
                var el = document.getElementById('settledesc');
                el.setCustomValidity('Please enter a description.');
                el.reportValidity();
                el.oninput = function(){ this.setCustomValidity(''); };
            }
            else if(settleamount == '' || settleamount <= 0){
                var el = document.getElementById('settleamount');
                el.setCustomValidity(settleamount == '' ? 'Please enter an amount.' : 'Amount must be greater than 0.');
                el.reportValidity();
                el.oninput = function(){ this.setCustomValidity(''); };
            }
            else{
                var rowCount = $("#tablevoucherlist tbody tr").length;
                if(advancechecked == 1 && rowCount > 0){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Error',
                        text: "Advance payments must be processed separately from other transactions."
                    });
                }
                else{
                    $('#tablevoucherlist tbody').append('<tr><td>'+paiddate+'</td><td>'+advancecheckedtext+'</td><td class="d-none">'+advancechecked+'</td><td>'+advancesupplierText+'</td><td class="d-none">'+advancesupplier+'</td><td>'+accounttext+'</td><td class="d-none">'+accountid+'</td><td class="d-none">'+accounttype+'</td><td>'+description+'</td><td class="text-right">'+settleamount+'</td></tr>');

                    setTotalAmount();
                }

                $('#checkadvanced').prop('checked', false);
                $('#advancesupplier').val('').trigger('change');
                $("#advancesupplier").prop('disabled', true);
                $('#supplier').val('').trigger('change');
                $('#settledesc').val('');
                $('#settleamount').val('');
            }
        });
        $('#tablevoucherlist tbody').on('click', 'td', async function() {
            var r = await Otherconfirmation("You want to remove this record ? ");
    		if (r == true) {
    			$(this).closest('tr').remove();
                setTotalAmount();
    		}
    	});
    });

    function getbranchlist(id, value){
        $.ajax({
            type: "POST",
            data: {
                recordID: id
            },
            url: '<?php echo base_url() ?>Receivablesettle/Getbranchaccocompany',
            success: function(result) { //alert(result);
                var obj = JSON.parse(result);
                var html = '';
                // html += '<option value="">Select</option>';
                $.each(obj, function (i, item) {
                    html += '<option value="' + obj[i].idtbl_company_branch + '">';
                    html += obj[i].branch ;
                    html += '</option>';
                });
                $('#branch').empty().append(html);   
                $('#btnchoosecominfo').click();

                if(value!=''){
                    $('#branch').val(value);
                }
            }
        });
    }

    function getaccountlist(companyid, branchid, receivetype, value){
        $("#chartofdetailaccount").select2({
            dropdownParent: $('#modalreceivable'),
            ajax: {
                url: "<?php echo base_url() ?>Payablesegregation/Getaccountlist",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term, // search term
                        companyid: companyid,
                        branchid: branchid
                    };
                },
                processResults: function (response) {
                    return {
                        results: response.map(function (item) {
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
    }

    function checkinvoicecomplete(){
        var intVal = function (i) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '') * 1 :
                typeof i === 'number' ?
                i : 0;
        };
        
        var sum = 0;
        var tablelist = $("#tableinvoicepayment tbody input[type=checkbox]:checked");
           
        if(tablelist.length>0){
            tablelist.each(function() {
                item = {}
                var row = $(this).closest("tr");
                sum += parseFloat(intVal(row.find('td:eq(7)').text()));
            });
        }

        $('#invoicepayamount').val(addCommas(parseFloat(sum).toFixed(2)));

        var invamount = parseFloat($('#invoicepayamount').val());
        
        if(invamount>0){
            $('#btnfullinvoicepayment').prop('disabled', false);
        }
        else{
            $('#btnfullinvoicepayment').prop('disabled', true);
        }
    }

    function getinvoicereceiptno(){
        var printtype = $('#printtype').val();
        var printsupplier = $('#printsupplier').val();
        var printdate = $('#printdate').val();

        if(printsupplier!='' | printdate!=''){
            $.ajax({
                type: "POST",
                data: {
                    printtype: printtype,
                    printsupplier: printsupplier,
                    printdate: printdate
                },
                url: '<?php echo base_url() ?>Paymentsettle/Getinvrecno',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);
                    var html = '';
                    html += '<option value="">Select</option>';
                    $.each(obj, function (i, item) {
                        html += '<option value="' + obj[i].invoicereceiptno + '">';
                        html += obj[i].invoicereceiptno ;
                        html += '</option>';
                    });
                    $('#printinvoicereceipt').empty().append(html);   
                    $('#printinvoicereceipt').select2({dropdownParent: $('#modalreceiptprint')});
                }
            });
        }
    }

    function createprint(){
        var printtype = $('#printtype').val();
        var printinvoicereceipt = $('#printinvoicereceipt').val();
    }

    function checkunapplied(){
        var intVal = function (i) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '') * 1 :
                typeof i === 'number' ?
                i : 0;
        };
        
        var sum = 0;
        var tablelist = $("#tableAdvancepayment tbody input[type=checkbox]:checked");
        accountlist = [];
                
        if(tablelist.length>0){
            tablelist.each(function() {
                item = {}
                var row = $(this).closest("tr");
                sum += parseFloat(intVal(row.find('td:eq(7)').text()));

                // Account type 1 for td:eq(8)
                if(row.find('td:eq(8)').text() > '0'){
                    var accId8 = row.find('td:eq(8)').text().trim();
                    // Check duplicate before push
                    var isDuplicate = accountlist.some(function(acc){
                        return acc.account_id === accId8 && acc.account_type === 1;
                    });
                    if(!isDuplicate){
                        accountlist.push({
                            account_id   : accId8,
                            account_type : 1
                        });
                    }
                }

                // Account type 2 for td:eq(9)
                if(row.find('td:eq(9)').text() > '0'){
                    var accId9 = row.find('td:eq(9)').text().trim();
                    // Check duplicate before push
                    var isDuplicate = accountlist.some(function(acc){
                        return acc.account_id === accId9 && acc.account_type === 2;
                    });
                    if(!isDuplicate){
                        accountlist.push({
                            account_id   : accId9,
                            account_type : 2
                        });
                    }
                }
            });

            $('#divadvalert').removeClass('d-none');
        }
        else{
            $('#divadvalert').addClass('d-none');
        }

        // Get unique account IDs to check if different accounts selected
        var uniqueAccountIds = accountlist.map(function(acc){
            return acc.account_id;
        }).filter(function(value, index, self){
            return self.indexOf(value) === index;
        });


        if(uniqueAccountIds.length > 1){
            // Show SweetAlert warning
            Swal.fire({
                icon: 'warning',
                title: 'Warning!',
                text: "You can't create this payment settlement. Because you selected different account advance payment.",
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            }).then((result) => {
                if(result.isConfirmed){
                    // Uncheck all checkboxes
                    $("#tableAdvancepayment tbody input[type=checkbox]:checked").prop('checked', false);
                    // Reset sum and accountlist
                    sum = 0;
                    accountlist = [];
                    $('#paidamount').val('0.00');
                }
            });
            return; // Stop further execution
        }
        else{
            $('#payabletype').val('5').trigger('change');

            $.ajax({
                type: "POST",
                data: {
                    accountlist: accountlist,
                },
                url: '<?php echo base_url() ?>Paymentsettle/Getaccountinfoaccoaccountlist',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);

                    var newOption = new Option(obj.account, obj.accountid, true, true);
                    $('#chartofdetailaccount').append(newOption).trigger('change');
                    var optionData = $('#chartofdetailaccount').select2('data');
                    var lastOption = optionData[optionData.length - 1]; 
                    lastOption.data = { type: obj.accounttype };
                    $('#chartofdetailaccount').trigger('change'); 
                }
            });
        }

        $('#paidamount').val(addCommas(parseFloat(sum).toFixed(2)));
    }

    function setTotalAmount(){
        var intVal = function (i) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '') * 1 :
                typeof i === 'number' ?
                i : 0;
        };
        
        var sum = 0;
        $('#tablevoucherlist tbody tr').each(function() {
            var row = $(this).closest("tr");
            sum += parseFloat(intVal(row.find('td:eq(9)').text()));
        });

        $('#invoicepayamount').val(addCommas(parseFloat(sum).toFixed(2)));
    }

    function addCommas(nStr){
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }
</script>
<?php include "include/footer.php"; ?>
