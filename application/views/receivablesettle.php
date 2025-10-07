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
                            <div class="page-header-icon"><i class="far fa-money-bill-alt"></i></div>
                            <span>Receivable Settle</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12 text-right">
                                <button class="btn btn-orange btn-sm px-4 mr-1" id="btnreceiptprint"><i class="fas fa-print mr-2"></i>Receipt Print</button>
                                <button class="btn btn-primary btn-sm px-4" id="btncreatesegregation" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus mr-2"></i>Receivable Create</button>
                                <hr>
                            </div>
                            <div class="col-12">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Rec. Type</th>
                                                <th>Company</th>
                                                <th>Branch</th>
                                                <th>Year</th>
                                                <th>Month</th>
                                                <th>Tra. Date</th>
                                                <th>Batch No</th>
                                                <th>Customer</th>
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
				<h6 class="modal-title" id="modalreceivableLabel">Create Receivable</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form id="invoicepaymentform">
                            <div class="row">
                                <div class="col">
                                    <label class="small font-weight-bold">Company*</label>
                                    <input type="text" name="showcompany" id="showcompany" class="form-control form-control-sm" readonly>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Branch*</label>
                                    <input type="text" name="showbranch" id="showbranch" class="form-control form-control-sm" readonly>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Customer*</label><br>
                                    <select name="customer" id="customer" class="form-control form-control-sm" style="width:100%;" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>                        
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="small title-style"><span>Invoice Information</span></h6>
                                    <table class="table table-striped table-bordered table-sm small" id="tableinvoicepayment">
                                        <thead>
                                            <tr>
                                                <th class="text-center">&nbsp;</th>
                                                <th class="d-none">Customer ID</th>
                                                <th>Customer</th>
                                                <th class="d-none">Invoice ID</th>
                                                <th>Invoice No</th>
                                                <th class="text-right">Total</th>
                                                <th class="text-right">Balance Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="small title-style"><span>Receviable Account</span></h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label class="small font-weight-bold">Invoice Total*</label>
                                    <input type="text" name="invoicepayamount" id="invoicepayamount" class="form-control form-control-sm text-right" value="0" readonly>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Receivable Type*</label><br>
                                    <select name="receivabletype" id="receivabletype" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php foreach($receivabletype->result() as $rowreceivabletype){ ?>
                                        <option value="<?php echo $rowreceivabletype->idtbl_receivable_type ?>"><?php echo $rowreceivabletype->receivabletype ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Cheque Date</label>
                                    <input type="date" name="chequedate" id="chequedate" min="<?php // echo date('Y-m-d'); ?>" class="form-control form-control-sm">
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Cheque No*</label>
                                    <input type="text" name="chequeno" id="chequeno" class="form-control form-control-sm input-integer">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-5">
                                    <label class="small font-weight-bold">Account No*</label>
                                    <select name="chartofdetailaccount" id="chartofdetailaccount" class="form-control form-control-sm" style="width: 100%;" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="col-5">
                                    <label class="small font-weight-bold">Narration</label>
                                    <input type="text" name="narration" id="narration" class="form-control form-control-sm" required>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Paid Amount*</label>
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
                                <option value="1">Invoice No</option>
                                <option value="2">Payment Receipt</option>
                            </select>
                        </div>
                        <div class="col">
                            <label class="small font-weight-bold">Customer*</label><br>
                            <select name="printcustomer" id="printcustomer" class="form-control form-control-sm" style="width: 100%;">
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
            allowDecimals: true, allowNegative: false, thousandSep: '', maxDecimalDigits: 2
        });

        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "<?php echo base_url() ?>scripts/receivablelist.php",
                type: "POST", // you can use GET
                data: function(d) {
                    d.userID = '<?php echo $_SESSION['userid']; ?>';
                }
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_receivable"
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
                    "data": "recdate"
                },
                {
                    "data": "batchno"
                },
                {
                    "data": "customer"
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
                        return addCommas(parseFloat(full['amount']).toFixed(2));
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        button+='<button class="btn btn-dark btn-sm btnview mr-1" id="'+full['idtbl_receivable']+'" data-toggle="tooltip" data-placement="bottom" title="View and post" data-poststatus="'+full['poststatus']+'" data-recordstatus="'+full['status']+'" data-recordtype="'+full['idtbl_receivable_type']+'">';
                        if(full['poststatus']==0){
                            button+='<i class="fas fa-exchange-alt"></i>';
                        }
                        else{
                            button+='<i class="fas fa-eye"></i>';
                        }
                        button+='</button>';
                        if(full['poststatus']==0){
                            if(full['status']==1 && statuscheck==1){
                                button+='<button type="button" data-url="Receivablesettle/Receivablesettlestatus/'+full['idtbl_receivable']+'/2" data-actiontype="2" class="btn btn-success btn-sm mr-1 btntableaction"><i class="fas fa-check"></i></button>';
                            }else if(full['status']==2 && statuscheck==1){
                                button+='<button type="button" data-url="Receivablesettle/Receivablesettlestatus/'+full['idtbl_receivable']+'/1" data-actiontype="1" class="btn btn-warning btn-sm mr-1 text-light btntableaction"><i class="fas fa-times"></i></button>';
                            }
                            if(deletecheck==1){
                                button+='<button type="button" data-url="Receivablesettle/Receivablesettlestatus/'+full['idtbl_receivable']+'/3" data-actiontype="3" class="btn btn-danger btn-sm text-light btntableaction"><i class="fas fa-trash-alt"></i></button>';
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
                if ( data['poststatus'] == 1 ) {
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
            if(poststatus==1){$('#btnposttransaction').prop('disabled', true);}
            else if(recordtype==1){$('#btnposttransaction').prop('disabled', true);}
            else{$('#btnposttransaction').prop('disabled', false);}

            $('#receiableid').val(id);

            $('#modalviewpost').modal('show');
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: '<?php echo base_url() ?>Receivablesettle/Getviewpostinfo',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);
                    $('#viewdiv').html(obj.html);
                    if(obj.editablestatus==1){$('#btnposttransaction').addClass('d-none');}
                    else{$('#btnposttransaction').removeClass('d-none');}
                }
            });
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
        $("#customer").select2({
            dropdownParent: $('#modalreceivable'),
            ajax: {
                url: "<?php echo base_url() ?>Receivablesettle/Getcustomerlist",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
        $('#customer').change(function(){
            var id = $(this).val();
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: '<?php echo base_url() ?>Receivablesettle/Getinvoiceaccocustomer',
                success: function(result) { // alert(result);
                    $('#tableinvoicepayment tbody').empty().append(result);
                }
            });
        });
        // $('#invoice').change(function(){
        //     var invoiceamount = $(this).find(':selected').attr("data-amount");
        //     $('#invoiceamount').val(parseFloat(invoiceamount).toFixed(2)).attr('max', parseFloat(invoiceamount).toFixed(2));
        //     checkinvoicecomplete();
        // });
        // $("#invoiceamount").keydown(function(){
        //     $(this).attr('type', 'text');
        // });
        // $('#btnaddtolist').click(function(){
        //     $('#invoiceamount').attr('type', 'number');
        //     $('#customer').attr('disabled', false);
        //     if (!$("#invoicepaymentform")[0].checkValidity()) {
        //         // If the form is invalid, submit it. The form won't actually submit;
        //         // this will just cause the browser to display the native HTML5 error messages.
        //         $("#hidesegsubmit").click();
        //     } else {
        //         var customerID = $('#customer').val();
        //         var customer = $('#customer option:selected').text();
        //         var invoiceID = $('#invoice').val();
        //         var invoice = $('#invoice option:selected').text();
        //         var invoiceamount = $('#invoiceamount').val();

        //         $('#tableinvoicepayment> tbody:last').append('<tr><td class="d-none">' + customerID + '</td><td>' + customer + '</td><td class="d-none">' + invoiceID + '</td><td>' + invoice + '</td><td class="invbalamount text-right">' + invoiceamount + '</td><td class="text-right"><button type="button" class="btn btn-danger btn-sm btnremoverow"><i class="fas fa-times"></i></button></td></tr>');
        //         $('#invoice').val('');
        //         $('#invoiceamount').val('').attr('type', 'text');
        //         $('#customer').attr('disabled', true);

        //         checkinvoicecomplete();
        //     }
        // });
        // $('#tableinvoicepayment tbody').on('click', '.btnremoverow', function () {
    	// 	var r = confirm("Are you sure, You want to remove this invoice payment? ");
    	// 	if (r == true) {
    	// 		$(this).closest('tr').remove();
        //         checkinvoicecomplete();
    	// 	}
    	// });
        $('#tableinvoicepayment tbody').on('click', '.checkclick', function() {
            if ($(this).is(':checked')) {
                checkinvoicecomplete();
            } else {
                checkinvoicecomplete();
            }
        });

        $('#receivabletype').change(function(){
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

                if (tablelist.length > 0) {
                    $('#customer').attr('disabled', false);
                    jsonObj = [];
                    tablelist.each(function() {
                        item = {}
                        var row = $(this).closest("tr");
                        item["cusid"] = row.find('td:eq(1)').text();
                        item["customer"] = row.find('td:eq(2)').text();
                        item["invid"] = row.find('td:eq(3)').text();
                        item["invoiceno"] = row.find('td:eq(4)').text();
                        item["amount"] = row.find('td:eq(6)').text();
                        jsonObj.push(item);
                    });
                    var myJSON = JSON.stringify(jsonObj);

                    var recordID = $('#recordID').val();
                    var recordOption = $('#recordOption').val();
                    var company = $('#company').val();
                    var branch = $('#branch').val();
                    var customerID = $('#customer').val();
                    var receivabletype = $('#receivabletype').val();
                    var chequedate = $('#chequedate').val();
                    var chequeno = $('#chequeno').val();
                    var chartofdetailaccount = $('#chartofdetailaccount').val();
                    // var accounttype = $('#chartofdetailaccount').find(':selected').attr('data-type');;
                    var selectedData = $('#chartofdetailaccount').select2('data')[0];
                    var accounttype = selectedData ? selectedData.data.type : null;
                    var narration = $('#narration').val();
                    var invoicepayamount = $('#invoicepayamount').val();
                    var paidamount = $('#paidamount').val();

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
                                    company: company,
                                    branch: branch,
                                    customer: customerID,
                                    receivabletype: receivabletype,
                                    chequedate: chequedate,
                                    chequeno: chequeno,
                                    chartofdetailaccount: chartofdetailaccount,
                                    narration: narration,
                                    invoicepayamount: invoicepayamount,
                                    paidamount: paidamount,
                                    accounttype: accounttype,
                                    recordOption: recordOption,
                                    recordID: recordID
                                },
                                url: 'Receivablesettle/Receivablesettleinsertupdate',
                                success: function (result) { //alert(result);
                                    // console.log(result);
                                    var obj = JSON.parse(result);
                                    if (obj.status == 1) {
                                        Swal.close();
                                        // $('#hidesegreset').click();
                                        $('#tableinvoicepayment> tbody').empty();
                                        $('#customer').val('').trigger('change');
                                        $('#receivabletype').val('');
                                        $('#chequedate').val('');
                                        $('#chequeno').val('');
                                        $('#chartofdetailaccount').val('').trigger('change');
                                        $('#narration').val('');
                                        $('#invoicepayamount').val('0');
                                        $('#paidamount').val('0');
                                        $('#btnfullinvoicepayment').prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Complete');

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
                            url: 'Receivablesettle/Receivablesettleposting',
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
        $('#dataTable tbody').on('click', '.btnprint', function() {
            var id = $(this).attr('id');
            window.open("<?php echo base_url() ?>Reportprint/Paymentreceipt/"+id, "_blank");
        });

        $('#btnreceiptprint').click(function(){
            $('#modalreceiptprint').modal('show');
        });

        $("#printcustomer").select2({
            dropdownParent: $('#modalreceiptprint'),
            ajax: {
                url: "<?php echo base_url() ?>Receivablesettle/Getcustomerlist",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function (response) {
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
        $('#printcustomer').change(function(){
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
                window.open("<?php echo base_url() ?>Reportprint/Receivereceipt/"+printinvoicereceipt+"/"+printtype, "_blank");
            }
        })
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
        // $.ajax({
        //     type: "POST",
        //     data: {
        //         companyid: companyid,
        //         branchid: branchid
        //         // receivetype: receivetype
        //     },
        //     url: '<?php echo base_url() ?>Receivablesettle/Getaccountlist',
        //     success: function(result) { //alert(result);
        //         var obj = JSON.parse(result);
        //         var html = '';
        //         html += '<option value="">Select</option>';
        //         $.each(obj, function (i, item) {
        //             // if(receivetype==1){
        //             //     html += '<option value="' + obj[i].idtbl_account_detail + '">';
        //             // }
        //             // else{
        //             //     html += '<option value="' + obj[i].idtbl_account + '">';
        //             // }
        //             html += '<option value="' + obj[i].accountid + '" data-type="'+obj[i].acctype+'">';
        //             html += obj[i].accountname+' - '+obj[i].accountno ;
        //             html += '</option>';
        //         });
        //         $('#chartofdetailaccount').empty().append(html);   

        //         // if(value!=''){
        //         //     $('#chartofdetailaccount').val(value).trigger('change');
        //         // }
        //     }
        // });
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
                sum += parseFloat(intVal(row.find('td:eq(6)').text()));
            });
        }

        $('#invoicepayamount').val(parseFloat(sum).toFixed(2));

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
        var printcustomer = $('#printcustomer').val();
        var printdate = $('#printdate').val();

        if(printcustomer!='' | printdate!=''){
            $.ajax({
                type: "POST",
                data: {
                    printtype: printtype,
                    printcustomer: printcustomer,
                    printdate: printdate
                },
                url: '<?php echo base_url() ?>Receivablesettle/Getinvrecno',
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
