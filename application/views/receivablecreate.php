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
                            <span>Receivable Create</span>
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
                                <button class="btn btn-primary btn-sm px-4" id="btncreatesegregation" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus mr-2"></i>Create Receivable</button>
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
                                                <th>Customer</th>
                                                <th>Bill | Invoice Date</th>
                                                <th>Bill | Invoice No</th>
                                                <th>Remark</th>
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
<!-- Modal Receivable Segregation -->
<div class="modal fade" id="modalsegregation" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalsegregationLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modalsegregationLabel">Create Receivable</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form id="segregationform">
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold">Company*</label>
                                    <input type="text" name="showcompany" id="showcompany" class="form-control form-control-sm" readonly>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Branch*</label>
                                    <input type="text" name="showbranch" id="showbranch" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold">Vendor/Customer*</label><br>
                                    <select name="customer" id="customer" class="form-control form-control-sm" style="width: 100%;" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label class="small font-weight-bold">Invoice Date*</label>
                                    <input type="date" name="invoicedate" id="invoicedate" class="form-control form-control-sm" max="<?php echo date('Y-m-d') ?>">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <label class="small font-weight-bold">Invoice/Bill no*</label>
                                    <input type="text" name="invoice" id="invoice" class="form-control form-control-sm">
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Invoice Amount*</label>
                                    <input type="text" name="invoiceamount" id="invoiceamount" class="form-control form-control-sm text-right">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <label class="small font-weight-bold">Remark</label>
                                    <textarea name="receremark" id="receremark" class="form-control form-control-sm"></textarea>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12 text-right">
                                    <hr class="border-dark my-2">
                                    <button type="button" class="btn btn-primary btn-sm px-4" id="submitBtn" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-save mr-2"></i>Save</button>
                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">

                                    <input type="submit" id="hidesegsubmit" class="d-none">
                                    <input type="reset" id="hidesegreset" class="d-none">
                                </div>
                            </div>
                        </form>
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
<!-- Modal Receivable Segregation -->
<div class="modal fade" id="modalviewpost" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalviewpostLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modalviewpostLabel">View & Print Information</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <div id="viewdiv"></div>
                <div class="row">
                    <div class="col-12 text-right">
                        <hr class="border-dark">
                        <button type="button" class="btn btn-primary btn-sm px-4" id="btnposttransaction"><i class="fas fa-print mr-2"></i>Print Payment Voucher</button>
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

        $("#customer").select2({
            dropdownParent: $('#modalsegregation'),
            ajax: {
                url: "<?php echo base_url() ?>Receivablecreate/Getcustomerlist",
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

        // $('#chartofdetailaccount').select2({dropdownParent: $('#modalsegregation')});
        $('.input-integer').inputNumber({
            allowDecimals: true, allowNegative: false, thousandSep: '', maxDecimalDigits: 2
        });

        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "<?php echo base_url() ?>scripts/receivablecreatelist.php",
                type: "POST", // you can use GET
                data: function(d) {
                    d.userID = '<?php echo $_SESSION['userid']; ?>';
                }
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_sales_info"
                },
                {
                    "data": "company"
                },
                {
                    "data": "branch"
                },
                {
                    "data": "customer"
                },
                {
                    "data": "invdate"
                },
                {
                    "data": "invno"
                },
                {
                    "data": "remark"
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
                        button+='<button class="btn btn-dark btn-sm btnview mr-1" id="'+full['idtbl_sales_info']+'"><i class="fas fa-print"></i></button>';
                        if(full['poststatus']==0){
                            if(editcheck==1){
                                button+='<button class="btn btn-primary btn-sm btnEdit mr-1" id="'+full['idtbl_sales_info']+'"><i class="fas fa-pen"></i></button>';
                            }
                            if(statuscheck==1 && full['status']==1){
                                button+='<button type="button" data-url="Receivablecreate/Receivablecreatestatus/'+full['idtbl_sales_info']+'/2" data-actiontype="2" class="btn btn-success btn-sm mr-1 btntableaction"><i class="fas fa-check"></i></button>';
                            }else if(statuscheck==1 && full['status']==2){
                                button+='<button type="button" data-url="Receivablecreate/Receivablecreatestatus/'+full['idtbl_sales_info']+'/1" data-actiontype="1" class="btn btn-warning btn-sm mr-1 btntableaction"><i class="fas fa-times"></i></button>';
                            }
                            if(deletecheck==1){
                                button+='<button type="button" data-url="Receivablecreate/Receivablecreatestatus/'+full['idtbl_sales_info']+'/3" data-actiontype="3" class="btn btn-danger btn-sm btntableaction"><i class="fas fa-trash-alt"></i></button>';
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
            var r = await Otherconfirmation("You want to edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: '<?php echo base_url() ?>Receivablecreate/Receivablecreateedit',
                    success: function(result) { //alert(result);
                        // console.log(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#company').val(obj.companyid); 
                        getbranchlist(obj.companyid, obj.branchid);  

                        var html;
                        html += '<option value="' + obj.customer + '">';
                        html += obj.customername;
                        html += '</option>';

                        $('#customer').empty().append(html);
                                            
                        $('#invoice').val(obj.invno);                       
                        $('#invoicedate').val(obj.invdate);                       
                        $('#invoiceamount').val(obj.amount);                       
                        $('#showcompany').val(obj.company);                       
                        $('#showbranch').val(obj.branch);                    
                        $('#receremark').val(obj.remark);                    
                        
                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                        $('#modalsegregation').modal('show');                   
                    }
                });
            }
        });
        $('#dataTable tbody').on('click', '.btnview', function() {
            var id = $(this).attr('id');
            window.open("<?php echo base_url() ?>Reportprint/Receivablereceipt/"+id, "_blank");
            // $('#modalviewpost').modal('show');
            // $.ajax({
            //     type: "POST",
            //     data: {
            //         recordID: id
            //     },
            //     url: '<?php echo base_url() ?>Paymentcreate/Getviewprintinfo',
            //     success: function(result) { //alert(result);
            //         $('#viewdiv').html(result);
            //     }
            // });
        });

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
        $('#submitBtn').click(function(){
            $('#seperateamount').attr('type', 'number');
            if (!$("#segregationform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesegsubmit").click();
            } else {
                var customer = $('#customer').val();
                var invoicedate = $('#invoicedate').val();
                var invoice = $('#invoice').val();
                var invoiceamount = $('#invoiceamount').val();
                var receremark = $('#receremark').val();
                var company = $('#company').val();
                var branch = $('#branch').val();
                var recordOption = $('#recordOption').val();
                var recordID = $('#recordID').val();

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
                                company: company,
                                branch: branch,
                                customer: customer,
                                invoicedate: invoicedate,
                                invoice: invoice,
                                invoiceamount: invoiceamount,
                                receremark: receremark,
                                recordOption: recordOption,
                                recordID: recordID
                            },
                            url: 'Receivablecreate/Receivablecreateinsertupdate',
                            success: function (result) { //alert(result);
                                // console.log(result);
                                Swal.close();
                                var obj = JSON.parse(result);
                                if (obj.status == 1) {
                                    actionreload(obj.action);
                                }
                                else{
                                    action(obj.action);
                                }    					
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

                $('#chartofdetailaccount').val('').trigger('change');
                $('#narration').val('');
                $('#seperateamount').val('').attr('type', 'text');

                checksegregationcomplete();
            }
        });
    });

    function getbranchlist(id, value){
        $.ajax({
            type: "POST",
            data: {
                recordID: id
            },
            url: '<?php echo base_url() ?>Payablesegregation/Getbranchaccocompany',
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
