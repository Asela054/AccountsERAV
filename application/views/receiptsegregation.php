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
                            <div class="page-header-icon"><i class="fas fa-tasks"></i></div>
                            <span>Receivable Segregation</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12 text-right">
                                <!-- <button class="btn btn-primary btn-sm px-4" id="btncreatesegregation" <?php // if($addcheck==0){echo 'disabled';} ?> data-toggle="modal" data-target="#modalcompanychoose"><i class="fas fa-plus mr-2"></i>Create Segregation</button> -->
                                <button class="btn btn-primary btn-sm px-4" id="btncreatesegregation" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus mr-2"></i>Create Segregation</button>
                                <hr>
                            </div>
                            <div class="col-12">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap small" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Company</th>
                                                <th>Branch</th>
                                                <th>Year</th>
                                                <th>Month</th>
                                                <th>Date</th>
                                                <th>Batch No</th>
                                                <th>Customer</th>
                                                <th>Invoice No</th>
                                                <th>Amount</th>
                                                <th class="text-right">Actions</th>
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
<!-- Modal Payment Segregation -->
<div class="modal fade" id="modalsegregation" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalsegregationLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modalsegregationLabel">Create Segregation</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form id="segregationform">
                            <div class="form-row">
                                <div class="col">
                                    <label class="small font-weight-bold">Customer*</label><br>
                                    <select name="customer" id="customer" class="form-control form-control-sm" style="width:100%;" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Invoice*</label>
                                    <select name="invoice" id="invoice" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Invoice Amount*</label>
                                    <input type="text" name="invoiceamount" id="invoiceamount" class="form-control form-control-sm text-right" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label class="small font-weight-bold">Company*</label>
                                    <input type="text" name="showcompany" id="showcompany" class="form-control form-control-sm" readonly>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Branch*</label>
                                    <input type="text" name="showbranch" id="showbranch" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="small title-style"><span>Segregation Account</span></h6>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-4">
                                    <label class="small font-weight-bold">Account No*</label>
                                    <select name="chartofdetailaccount" id="chartofdetailaccount" class="form-control form-control-sm" style="width: 100%;" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Cr/Dr*</label>
                                    <select name="tratype" id="tratype" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <option value="D">Debit</option>
                                        <option value="C">Credit</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label class="small font-weight-bold">Narration</label>
                                    <input type="text" name="narration" id="narration" class="form-control form-control-sm">
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Amount*</label>
                                    <input type="text" name="seperateamount" id="seperateamount" class="form-control form-control-sm input-integer" step="0.01" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12 text-right">
                                    <?php if($addcheck==1){ ?>
                                    <button type="button" class="btn btn-primary btn-sm px-4" id="btnaddtolist"><i class="fas fa-list mr-2"></i>Add to list</button>
                                    <?php } ?>
                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">

                                    <input type="submit" id="hidesegsubmit" class="d-none">
                                    <input type="reset" id="hidesegreset" class="d-none">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-12">
                        <h6 class="small title-style"><span>Segregation Information</span></h6>
                        <table class="table table-striped table-bordered table-sm small" id="tablesegregate">
                            <thead>
                                <tr>
                                    <th class="d-none">Chart of detail account ID</th>
                                    <th>Account</th>
                                    <th>Narration</th>
                                    <th class="text-center">C/D</th>
                                    <th class="text-right">Debit</th>
                                    <th class="text-right">Credit</th>
                                    <th class="d-none">Account Type</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <hr class="border-dark">
                    </div>
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary btn-sm px-4" id="btnfullsegregation" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-save mr-2"></i>Complete</button>
                    </div>
                    <div class="col-12">
                        <div class="alert alert-danger mt-2">
                            If you want to remove any segregation amount, please click the table row, then you can choose "<strong>Yes</strong>" or "<strong>No</strong>" to remove the segregation amount.
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->
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
                        <input type="hidden" name="payablemainid" id="payablemainid">
                        <button type="button" class="btn btn-danger btn-sm px-4" id="btnposttransaction" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-exchange-alt mr-2"></i>Post Transaction</button>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        // $('#chartofdetailaccount').select2({dropdownParent: $('#modalsegregation')});
        $("#customer").select2({
            dropdownParent: $('#modalsegregation'),
            ajax: {
                url: "<?php echo base_url() ?>Receiptsegregation/Getcustomerlist",
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

        $('.input-integer').inputNumber({
            allowDecimals: true, allowNegative: false, thousandSep: '', maxDecimalDigits: 2
        });

        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "<?php echo base_url() ?>scripts/accountreceablelist.php",
                type: "POST", // you can use GET
                data: function(d) {
                    d.userID = '<?php echo $_SESSION['userid']; ?>';
                }
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_account_receivable_main"
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
                    "data": "tradate"
                },
                {
                    "data": "batchno"
                },
                {
                    "data": "customer"
                },
                {
                    "data": "invno"
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
                        button+='<button class="btn btn-dark btn-sm btnview mr-1" id="'+full['idtbl_account_receivable_main']+'" data-toggle="tooltip" data-placement="bottom" title="View and post" data-poststatus="'+full['poststatus']+'" data-recordstatus="'+full['status']+'">';
                        if(full['poststatus']==0){
                            button+='<i class="fas fa-exchange-alt"></i>';
                        }
                        else{
                            button+='<i class="fas fa-eye"></i>';
                        }
                        button+='</button>';
                        if(full['poststatus']==0){
                            if(editcheck==1){
                                button+='<button type="button" class="btn btn-primary btn-sm btnEdit mr-1" id="'+full['idtbl_account_receivable_main']+'"><i class="fas fa-pen"></i></button>';
                            }
                            if(full['status']==1 && statuscheck==1){
                                button+='<button type="button" data-url="Receiptsegregation/Receiptsegregationstatus/'+full['idtbl_account_receivable_main']+'/2" data-actiontype="2" class="btn btn-success btn-sm mr-1 btntableaction"><i class="fas fa-check"></i></button>';
                            }else if(full['status']==2 && statuscheck==1){
                                button+='<button type="button" data-url="Receiptsegregation/Receiptsegregationstatus/'+full['idtbl_account_receivable_main']+'/1" data-actiontype="1" class="btn btn-warning btn-sm mr-1 text-light btntableaction"><i class="fas fa-times"></i></button>';
                            }
                            if(deletecheck==1){
                                button+='<button type="button" data-url="Receiptsegregation/Receiptsegregationstatus/'+full['idtbl_account_receivable_main']+'/3" data-actiontype="3" class="btn btn-danger btn-sm text-light btntableaction"><i class="fas fa-trash-alt"></i></button>';
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
            },
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
                    url: '<?php echo base_url() ?>Receiptsegregation/Receiptsegregationedit',
                    success: function(result) { //alert(result);
                        console.log(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#company').val(obj.companyid); 
                        getbranchlist(obj.companyid, obj.branchid);  
                        getaccountlist(obj.companyid, obj.branchid); 
                        getinvoicelist(obj.customer, obj.receiptno);                      

                        var html;
                        html += '<option value="' + obj.customer + '">';
                        html += obj.customername;
                        html += '</option>';

                        $('#customer').empty().append(html);
				        $('#customer').prop('disabled', true);
                        
                        $('#invoiceamount').val(obj.amount);                       
                        $('#showcompany').val(obj.company);                       
                        $('#showbranch').val(obj.branch);   
                        $('#tablesegregate > tbody').append(obj.tabledata);                    
                        checksegregationcomplete()                     
                        
                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                        $('#modalsegregation').modal('show');                   
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
            if(poststatus==1){$('#btnposttransaction').prop('disabled', true);}
            else{$('#btnposttransaction').prop('disabled', false);}

            $('#payablemainid').val(id);

            $('#modalviewpost').modal('show');
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: '<?php echo base_url() ?>Receiptsegregation/Getviewpostinfo',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);
                    $('#viewdiv').html(obj.html);
                    if(obj.editablestatus==1){$('#btnposttransaction').addClass('d-none');}
                    else{$('#btnposttransaction').removeClass('d-none');}
                }
            });
        });

        $('#customer').change(function(){
            var id = $(this).val();
            getinvoicelist(id, '');
        });
        $('#invoice').change(function(){
            var invoiceamount = $(this).find(':selected').attr("data-amount");
            $('#invoiceamount').val(parseFloat(invoiceamount).toFixed(2));
            $('#seperateamount').attr('max', invoiceamount);
        });
        // $('#company').change(function(){
        //     var id = $(this).val();
        //     getbranchlist(id, '');
        // });
        // $('#branch').change(function(){
        //     var companyid = $('#company').val();
        //     var branchid = $(this).val();

        //     getaccountlist(companyid, branchid);
        // });
        $('#btncreatesegregation').click(function() {
            getbranchlist('<?php echo $_SESSION['companyid'] ?>', '');
            getaccountlist('<?php echo $_SESSION['companyid'] ?>', '<?php echo $_SESSION['branchid'] ?>');
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
                $('#modalsegregation').modal('show');
            }
        });     
        $('#modalsegregation').on('hidden.bs.modal', function (event) {
            window.location.reload();
        });  
        
        $("#seperateamount").keydown(function(){
            $(this).attr('type', 'text');
        });
        $('#btnaddtolist').click(function(){
            $('#seperateamount').attr('type', 'number');
            if (!$("#segregationform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesegsubmit").click();
            } else {
                var chartdetailaccountID = $('#chartofdetailaccount').val();
                var chartdetailaccount = $('#chartofdetailaccount option:selected').text();
                // var accounttype = $('#chartofdetailaccount').find(':selected').attr('data-type'); //1 ==> Chart of accounts, 2 ==> Detail Accounts
                var selectedData = $('#chartofdetailaccount').select2('data')[0];
                var accounttype = selectedData ? selectedData.data.type : null;
                var tratype = $('#tratype').val();
                var narration = $('#narration').val();
                if(tratype=='D'){
                    var debitamount = $('#seperateamount').val();
                    var creditamount = '';
                }
                else{
                    var debitamount = '';
                    var creditamount = $('#seperateamount').val();
                }
                // var segregationamount = $('#seperateamount').val();

                $('#tablesegregate> tbody:last').append('<tr><td class="d-none">' + chartdetailaccountID + '</td><td>' + chartdetailaccount + '</td><td>' + narration + '</td><td class="transtype text-center">' + tratype + '</td><td class="debitamount text-right">' + debitamount + '</td><td class="creditamount text-right">' + creditamount + '</td></i></button></td><td class="d-none">' + accounttype + '</td></tr>');
                $('#chartofdetailaccount').val('').trigger('change');
                $('#narration').val('');
                $('#tratype').val('');
                $('#seperateamount').val('').attr('type', 'text');

                checksegregationcomplete();
            }
        });
        $('#tablesegregate tbody').on('click', 'td', async function() {
            var r = await Otherconfirmation("You want to remove this segregation ? ");
    		if (r == true) {
    			$(this).closest('tr').remove();
                checksegregationcomplete();
    		}
    	});
        $('#btnfullsegregation').click(function(){
            $('#btnfullsegregation').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i> Complete');
    		var tbody = $("#tablesegregate tbody");

            if (tbody.children().length > 0) {
                jsonObj = [];
    			$("#tablesegregate tbody tr").each(function () {
    				item = {}
    				$(this).find('td').each(function (col_idx) {
    					item["col_" + (col_idx + 1)] = $(this).text();
    				});
    				jsonObj.push(item);
    			});

                var recordID = $('#recordID').val();
                var recordOption = $('#recordOption').val();
                var company = $('#company').val();
                var branch = $('#branch').val();
                var customer = $('#customer').val();
                var invoice = $('#invoice').val();
                var invoiceamount = $('#invoiceamount').val();

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
                                tableData: jsonObj,
                                company: company,
                                branch: branch,
                                customer: customer,
                                invoice: invoice,
                                invoiceamount: invoiceamount,
                                recordOption: recordOption,
                                recordID: recordID
                            },
                            url: 'Receiptsegregation/Receiptsegregationinsertupdate',
                            success: function (result) { //alert(result);
                                // console.log(result);
                                Swal.close();
                                var obj = JSON.parse(result);
                                if (obj.status == 1) {
                                    // $('#hidesegreset').click();
                                    $('#tablesegregate> tbody').empty();
                                    $('#btnaddtolist').prop('disabled', false);
                                    $('#customer').val('');
                                    $('#invoice').val('');
                                    $('#invoiceamount').val('');
                                    $('#btnfullsegregation').prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Complete');

                                    if(recordOption==2){
                                        setTimeout( function(){ 
                                            $('#modalsegregation').modal('hide');
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
        }); 

        $('#btnposttransaction').click(async function() {
            var r = await Otherconfirmation("You want to post this transaction ? ");
            if (r == true) {
                $('#btnposttransaction').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i> Post Transaction');
                var mainpayID = $('#payablemainid').val();

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
                                recordID: mainpayID
                            },
                            url: 'Receiptsegregation/Receiptsegregationposting',
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
        })
    });

    function getaccountlist(companyid, branchid){
        // $.ajax({
        //     type: "POST",
        //     data: {
        //         companyid: companyid,
        //         branchid: branchid
        //     },
        //     url: '<?php echo base_url() ?>Receiptsegregation/Getaccountlist',
        //     success: function(result) { //alert(result);
        //         var obj = JSON.parse(result);
        //         var html = '';
        //         html += '<option value="">Select</option>';
        //         $.each(obj, function (i, item) {
        //             html += '<option value="' + obj[i].accountid + '" data-type="'+obj[i].acctype+'">';
        //             html += obj[i].accountname+' - '+obj[i].accountno ;
        //             html += '</option>';
        //         });
        //         $('#chartofdetailaccount').empty().append(html);   
        //     }
        // });
        $("#chartofdetailaccount").select2({
            dropdownParent: $('#modalsegregation'),
            ajax: {
                url: "<?php echo base_url() ?>Receiptsegregation/Getaccountlist",
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

    function getbranchlist(id, value){
        $.ajax({
            type: "POST",
            data: {
                recordID: id
            },
            url: '<?php echo base_url() ?>Receiptsegregation/Getbranchaccocompany',
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
    
    function checksegregationcomplete(){
        var debitsum = 0;
        var creditsum = 0;
    	$(".debitamount").each(function () {
    		debitsum += parseFloat($(this).text() || 0);
    	});
        
    	$(".creditamount").each(function () {
    		creditsum += parseFloat($(this).text() || 0);
    	});

        var invamount = parseFloat($('#invoiceamount').val());
        
        if(creditsum==debitsum && invamount==creditsum && invamount==debitsum){
            $('#btnaddtolist').prop('disabled', true);
            $('#btnfullsegregation').prop('disabled', false);
        }
        else{
            var balance = invamount;
            $('#seperateamount').attr('max', balance);
            $('#btnaddtolist').prop('disabled', false);
            $('#btnfullsegregation').prop('disabled', true);
            $('#chartofdetailaccount').focus();
        }
    }

    function getinvoicelist(id, value){ // alert(id+value);
        $.ajax({
            type: "POST",
            data: {
                recordID: id
            },
            url: '<?php echo base_url() ?>Receiptsegregation/Getinvoiceaccocustomer',
            success: function(result) { //alert(result);
                var obj = JSON.parse(result);
                var html = '';
                html += '<option value="">Select</option>';
                $.each(obj, function (i, item) {
                    html += '<option value="' + obj[i].invno + '" data-amount="' + obj[i].invamount + '">';
                    html += obj[i].invno ;
                    html += '</option>';
                });
                $('#invoice').empty().append(html);   
                
                if(value!=''){  
                    $('#invoice').val(value).prop('disabled', true);
                }
            }
        });
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
