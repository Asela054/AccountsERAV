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
                            <div class="page-header-icon"><i data-feather="list"></i></div>
                            <span>Batch Transaction Type</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12 text-right mb-2">
                                <?php if($addcheck==1){ ?>
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#batchTypeModal"><i class="fas fa-plus"></i>&nbsp;Add Batch Transaction Type</button>
                                <hr>
                                <?php } ?>
                            </div>
                            <div class="col-12">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Batch Category</th>
                                                <th>Batch Code</th>
                                                <th>Batch Type</th>
                                                <th>Tax Status</th>
                                                <th>Credit / Debit</th>
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
<!-- Modal -->
<div class="modal fade" id="batchTypeModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="batchTypeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header p-2">
				<h6 class="modal-title" id="batchTypeModalLabel">Batch Transaction Type Information</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" autocomplete="off" id="formbatchtype">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold">Batch Category*</label><br>
                                    <select name="batchcategory" id="batchcategory" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php foreach($batchcategory->result() as $rowbatchcategory){ ?>
                                        <option value="<?php echo $rowbatchcategory->idtbl_batch_category ?>"><?php echo $rowbatchcategory->batch_category ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col-3">
                                    <label class="small font-weight-bold">Code*</label>
                                    <input type="text" class="form-control form-control-sm" name="code" id="code" required>
                                </div>  
                                <div class="col-9">
                                    <label class="small font-weight-bold">Description*</label>
                                    <input type="text" class="form-control form-control-sm" name="description" id="description" required>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col-7">
                                    <label class="small font-weight-bold">Debit Credit*</label><br>
                                    <select name="debitcredit" id="debitcredit" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php foreach($transactiontype->result() as $rowtransactiontype){ ?>
                                        <option value="<?php echo $rowtransactiontype->idtbl_account_transactiontype ?>"><?php echo $rowtransactiontype->transactiontype ?></option>
                                        <?php } ?> 
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Tax Info*</label><br>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checktaxinfo" name="checktaxinfo">
                                        <label class="custom-control-label font-weight-bold small" for="checktaxinfo">Tax apply</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold">Debit Account*</label><br>
                                    <select name="debitaccount" id="debitaccount" style="width: 100%;" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>  
                            </div>  
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold">Credit Account*</label><br>
                                    <select name="creditaccount" id="creditaccount" style="width: 100%;" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold">Tax Account*</label><br>
                                    <select name="taxaccount" id="taxaccount" style="width: 100%;" class="form-control form-control-sm" disabled>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-right">
                            <hr>
                            <?php if($addcheck==1){ ?>
                            <button type="button" id="btnsubmit" class="btn btn-primary btn-sm">Create Batch Transaction</button>
                            <input type="submit" class="d-none" id="hidesubmitbtn">
                            <?php } ?>
                        </div>
                    </div>
                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                    <input type="hidden" name="recordID" id="recordID" value="">
                </form>
                <div class="row">
                    <div class="col-12">
                        <hr>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i> Create a new batch type and inform the system development team. because it has a config in the backend
                        </div>
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

        var compantID='<?php echo $_SESSION['companyid'] ?>';
        var branchID='<?php echo $_SESSION['branchid'] ?>';

        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "<?php echo base_url() ?>scripts/batchtypelist.php",
                type: "POST", // you can use GET
                data: function(d) {
                    d.userID = '<?php echo $_SESSION['userid']; ?>';
                }
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_batch_trans_type"
                },
                {
                    "data": "batch_category"
                },
                {
                    "data": "batctranstypecode"
                },
                {
                    "data": "batctranstype"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        if(full['taxapply']==1){return 'TAX'; }else{ return '';}
                    }
                },
                {
                    "data": "crdr"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';

                        if(editcheck==1){
                            button+='<button type="button" class="btn btn-primary btn-sm btnEdit mr-1" id="'+full['idtbl_batch_trans_type']+'"><i class="fas fa-pen"></i></button>';
                        }
                        if(full['status']==1 && statuscheck==1){
                            button+='<button type="button" data-url="BatchTransactionType/BatchTransactionTypestatus/'+full['idtbl_batch_trans_type']+'/2" data-actiontype="2" class="btn btn-success btn-sm mr-1 btntableaction"><i class="fas fa-check"></i></button>';
                        }else if(full['status']==2 && statuscheck==1){
                            button+='<button type="button" data-url="BatchTransactionType/BatchTransactionTypestatus/'+full['idtbl_batch_trans_type']+'/1" data-actiontype="1" class="btn btn-warning btn-sm mr-1 text-light btntableaction"><i class="fas fa-times"></i></button>';
                        }
                        if(deletecheck==1){
                            button+='<button type="button" data-url="BatchTransactionType/BatchTransactionTypestatus/'+full['idtbl_batch_trans_type']+'/3" data-actiontype="3" class="btn btn-danger btn-sm text-light btntableaction"><i class="fas fa-trash-alt"></i></button>';
                        }
                        
                        return button;
                    }
                }
            ],
            drawCallback: function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
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
                    url: '<?php echo base_url() ?>BatchTransactionType/BatchTransactionTypeedit',
                    success: function(result) { //alert(result);
                        // console.log(result);
                        
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#batchcategory').val(obj.batchcategory);                       
                        $('#code').val(obj.batctranstypecode);                       
                        $('#description').val(obj.batctranstype);                       
                        $('#debitcredit').val(obj.crdr);        

                        if(obj.taxapply==1){$('#checktaxinfo').prop('checked', true);}

                        getaccountlist(obj.accountcr, 'creditaccount', 'batchTypeModal');
                        var newOptioncr = new Option(obj.accountcr, obj.accountcrid, true, true);
                        $('#creditaccount').append(newOptioncr).trigger('change');
                        var optionDatacr = $('#creditaccount').select2('data');
                        var lastOptioncr = optionDatacr[optionDatacr.length - 1]; 
                        lastOptioncr.data = { type: obj.accounttypecr };                        
                        $('#creditaccount').trigger('change');                         

                        getaccountlist(obj.accountdr, 'debitaccount', 'batchTypeModal');
                        var newOptiondr = new Option(obj.accountdr, obj.accountdrid, true, true);
                        $('#debitaccount').append(newOptiondr).trigger('change');
                        var optionDatadr = $('#debitaccount').select2('data');
                        var lastOptiondr = optionDatadr[optionDatadr.length - 1]; 
                        lastOptiondr.data = { type: obj.accounttypedr };
                        $('#debitaccount').trigger('change');
                        
                        if(obj.taxapply==1){
                            getaccountlist(obj.accounttax, 'taxaccount', 'batchTypeModal');
                            var newOptioncr = new Option(obj.accounttax, obj.accounttaxid, true, true);
                            $('#taxaccount').prop('disabled', false);
                            $('#taxaccount').append(newOptioncr).trigger('change');
                        }                   

                        $('#recordOption').val('2');
                        $('#btnsubmit').html('Update Batch Transaction');

                        $('#batchTypeModal').modal('show');          
                    }
                });
            }
        });
        $('#batchTypeModal').on('shown.bs.modal', function (event) {
            if($('#recordOption').val()==1){
                getaccountlist('', 'debitaccount', 'batchTypeModal');
                getaccountlist('', 'creditaccount', 'batchTypeModal');
            }
        });
        $('#checktaxinfo').change(function() {
            if($(this).is(":checked")) {
                $('#taxaccount').prop('disabled', false).prop('required', true);
                getaccountlist('', 'taxaccount', 'batchTypeModal');
            }
            else{
                $('#taxaccount').prop('disabled', true);
            }       
        });

        $('#btnsubmit').click(function(){
            if (!$("#formbatchtype")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmitbtn").click();
            } else {
                var batchcategory = $('#batchcategory').val();
                var code = $('#code').val();
                var description = $('#description').val();
                var debitcredit = $('#debitcredit').val();
                
                if($('#checktaxinfo').is(":checked")) {
                    var checktaxinfo = 1;
                    var taxaccount = $('#taxaccount').val();
                    var selectedData = $('#taxaccount').select2('data')[0];
                    var taxaccounttype = selectedData ? selectedData.data.type : null;
                }else{
                    var checktaxinfo = 0;
                    var taxaccount = '';
                    var taxaccounttype = '';
                }
                
                var creditaccount = $('#creditaccount').val();                
                var selectedData = $('#creditaccount').select2('data')[0];
                var creditaccounttype = selectedData ? selectedData.data.type : null;
                

                var debitaccount = $('#debitaccount').val();
                var selectedData = $('#debitaccount').select2('data')[0];                
                var debitaccounttype = selectedData ? selectedData.data.type : null;     

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
                                batchcategory: batchcategory,
                                code: code,
                                description: description,
                                debitcredit: debitcredit,
                                checktaxinfo: checktaxinfo,
                                taxaccount: taxaccount,
                                taxaccounttype: taxaccounttype,
                                debitaccount: debitaccount,
                                debitaccounttype: debitaccounttype,
                                creditaccount: creditaccount,
                                creditaccounttype: creditaccounttype,
                                recordOption: recordOption,
                                recordID: recordID
                            },
                            url: '<?php echo base_url() ?>BatchTransactionType/BatchTransactionTypeinsertupdate',
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
            }
        });
    });

    function getaccountlist(value, field, modalname){
        $("#"+field).select2({
            dropdownParent: $('#'+modalname),
            ajax: {
                url: "<?php echo base_url() ?>BatchTransactionType/Getaccountlist",
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
</script>
<?php include "include/footer.php"; ?>
