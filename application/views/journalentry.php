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
                            <div class="page-header-icon"><i class="fas fa-exchange-alt"></i></div>
                            <span>Journal Entry</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12 text-right">    
                                <?php if($addcheck==1): ?>
                                <button class="btn btn-secondary btn-sm px-4" id="btnjurnalbatches"><i class="fas fa-list mr-2"></i>Jurnal Batches</button>
                                <button class="btn btn-orange btn-sm px-4" id="btnparseentry"><i class="fas fa-exchange-alt mr-2"></i>Pass To GL Acounts</button>
                                <button class="btn btn-primary btn-sm px-4" id="btncreateentry"><i class="fas fa-plus mr-2"></i>Create Journal Entry</button>
                                <?php endif; ?>
                                <hr>
                            </div>
                            <div class="col-12">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Company</th>
                                                <th>Branch</th>
                                                <th>Year</th>
                                                <th>Month</th>
                                                <th>Tra. Date</th>
                                                <th>Batch No</th>
                                                <th>Narration</th>
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
<div class="modal fade" id="modaljournal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modaljournalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modaljournalLabel">Create Journal Entry</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form id="journalform">
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
                                    <label class="small font-weight-bold">Date*</label>
                                    <input type="date" name="tradate" id="tradate" class="form-control form-control-sm" max="<?php echo date('Y-m-d') ?>" required>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Amount*</label>
                                    <input type="text" name="traamount" id="traamount" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <h6 class="title-style small"><span>Credit Account Information</span></h6>
                                    <div class="form-row mb-1">
                                        <!-- <div class="col">
                                            <label class="small font-weight-bold">Account Type*</label>
                                            <select name="accountcrtype" id="accountcrtype" class="form-control form-control-sm" required>
                                                <option value="">Select</option>
                                                <?php //foreach($accounttypelist->result() as $rowaccounttypelist){ ?>
                                                <option value="<?php //echo $rowaccounttypelist->idtbl_account_type ?>"><?php //echo $rowaccounttypelist->accounttype ?></option>
                                                <?php //} ?>
                                            </select>
                                        </div> -->
                                        <div class="col-5">
                                            <label class="small font-weight-bold">Account No*</label><br>
                                            <select name="accountcrno" id="accountcrno" class="form-control form-control-sm" style="width: 100%;" required>
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold">Narration*</label>
                                            <input type="text" name="narrationcr" id="narrationcr" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h6 class="title-style small"><span>Debit Account Information</span></h6>
                                    <div class="form-row mb-1">
                                        <!-- <div class="col">
                                            <label class="small font-weight-bold">Account Type*</label>
                                            <select name="accountdrtype" id="accountdrtype" class="form-control form-control-sm" required>
                                                <option value="">Select</option>
                                                <?php // foreach($accounttypelist->result() as $rowaccounttypelist){ ?>
                                                <option value="<?php // echo $rowaccounttypelist->idtbl_account_type ?>"><?php // echo $rowaccounttypelist->accounttype ?></option>
                                                <?php //} ?>
                                            </select>
                                        </div> -->
                                        <div class="col-5">
                                            <label class="small font-weight-bold">Account No*</label><br>
                                            <select name="accountdrno" id="accountdrno" class="form-control form-control-sm" style="width: 100%;" required>
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold">Narration*</label>
                                            <input type="text" name="narrationdr" id="narrationdr" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="recordOption" id="recordOption" value="1">
                            <input type="hidden" name="recordID" id="recordID" value="">
                            <input type="submit" id="hidesubmit" class="d-none">
                        </form>
                    </div>
                    <div class="col-12 text-right">
                        <hr class="border-dark">
                        <button type="button" class="btn btn-primary btn-sm px-4" id="btncreatejournal" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-save mr-2"></i>Complete</button>
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
                        <input type="hidden" name="journalid" id="journalid">
                        <button type="button" class="btn btn-danger btn-sm px-4" id="btnposttransaction" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-exchange-alt mr-2"></i>Post Transaction</button>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
<!-- Modal Pass GL -->
<div class="modal fade" id="modalparsemodal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalparsemodalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modalparsemodalLabel">Pass To GL Accounts</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form id="journalformtogl">
                            <div class="row">
                                <div class="col">
                                    <label class="small font-weight-bold">Company*</label>
                                    <input type="text" name="glshowcompany" id="glshowcompany" class="form-control form-control-sm" readonly>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Branch*</label>
                                    <input type="text" name="glshowbranch" id="glshowbranch" class="form-control form-control-sm" readonly>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Date*</label>
                                    <input type="date" name="gltradate" id="gltradate" class="form-control form-control-sm" max="<?php echo date('Y-m-d') ?>" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <h6 class="title-style small"><span>Credit Account Information</span></h6>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold">Account No*</label><br>
                                            <select name="glaccountcrno" id="glaccountcrno" class="form-control form-control-sm" style="width: 100%;" required>
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h6 class="title-style small"><span>Debit Account Information</span></h6>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold">Account No*</label><br>
                                            <select name="glaccountdrno" id="glaccountdrno" class="form-control form-control-sm" style="width: 100%;" required>
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="submit" id="hidesubmitgl" class="d-none">
                        </form>
                    </div>
                    <div class="col-12">
                        <hr class="border-dark">
                        <table class="table table-striped table-bordered table-sm small" id="tabledatalist">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Date</th>
                                    <th>Supplier | Customer</th>
                                    <th>Inv. Rece. No</th>
                                    <th>Narration</th>
                                    <th class="text-right">Amount</th>
                                    <th class="d-none">RecordID</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="col-12 text-right">
                        <hr class="border-dark">
                        <button type="button" class="btn btn-primary btn-sm px-4" id="btnpasstoglaccount" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-save mr-2"></i>Pass to GL</button>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
<!-- Modal Batch Transaction -->
<div class="modal fade" id="batchtransactionmodal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="batchtransactionmodalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="batchtransactionmodalLabel">Batch Journal Transaction</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form id="journalformbatch">
                            <div class="row">
                                <div class="col">
                                    <label class="small font-weight-bold">Company*</label>
                                    <input type="text" name="glbatchcompany" id="glbatchcompany" class="form-control form-control-sm" readonly>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Branch*</label>
                                    <input type="text" name="glbatchbranch" id="glbatchbranch" class="form-control form-control-sm" readonly>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Date*</label>
                                    <input type="date" name="glbatchtradate" id="glbatchtradate" class="form-control form-control-sm" max="<?php echo date('Y-m-d') ?>" required>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Credit/ Debit*</label>
                                    <select name="glbatchcreditdebit" id="glbatchcreditdebit" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php foreach($accounttranstypelist->result() as $rowaccounttranstypelist){ ?>
                                        <option value="<?php echo $rowaccounttranstypelist->idtbl_account_transactiontype ?>"><?php echo $rowaccounttranstypelist->transactiontype ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold">Account No*</label><br>
                                            <select name="glbatchaccountno" id="glbatchaccountno" class="form-control form-control-sm" style="width: 100%;" required>
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="small font-weight-bold">Narration*</label>
                                    <input type="text" name="glbatchnarration" id="glbatchnarration" class="form-control form-control-sm" required>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Amount*</label>
                                    <input type="text" name="glbatchamount" id="glbatchamount" class="form-control form-control-sm input-integer" required>
                                </div>
                            </div>
                            <?php if($addcheck==1): ?>
                            <div class="row">
                                <div class="col-12 text-right">
                                    <hr>
                                    <button type="button" class="btn btn-primary btn-sm px-4" id="btnbatchsubmit"><i class="fas fa-list mr-2"></i>Add to list</button>
                                    <input type="submit" id="hidesubmitglbatch" class="d-none">
                                </div>
                            </div>
                            <?php endif; ?>
                            <input type="hidden" name="batchMainTransID" id="batchMainTransID">
                            <input type="hidden" name="batchMainTransBatchNo" id="batchMainTransBatchNo">
                            <input type="hidden" name="batchMainTransMaster" id="batchMainTransMaster">
                        </form>
                    </div>
                    <div class="col-12">
                        <table class="table table-striped table-bordered table-sm small mt-3" id="batchtablelist">
                            <thead>
                                <tr>
                                    <th class="d-none">#</th>
                                    <th>Date</th>
                                    <th class="d-none">AccountID</th>
                                    <th>Account</th>
                                    <th class="d-none">cdID</th>
                                    <th class="text-center">C/D</th>
                                    <th>Batch No</th>
                                    <th>Narration</th>
                                    <th class="text-right">Debit</th>
                                    <th class="text-right">Credit</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5">Total</th>
                                    <th class="text-right debittotal">0.00</th>
                                    <th class="text-right credittotal">0.00</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="col-12 text-right">
                        <?php if($addcheck==1): ?>
                        <hr>
                        <button type="button" class="btn btn-primary btn-sm px-3" id="btnsavebatchjournal"><i class="fas fa-save mr-2"></i> Create Batch Journal</button>
                        <?php endif; ?>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
<input type="hidden" name="glpassstatus" id="glpassstatus" value="0">
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        $('.input-integer').inputNumber({
			maxDecimalDigits: 4
		});

        $('#glaccountcrno').select2({dropdownParent: $('#modalparsemodal')});
        $('#glaccountdrno').select2({dropdownParent: $('#modalparsemodal')});

        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "<?php echo base_url() ?>scripts/journalentrylist.php",
                type: "POST", // you can use GET
                data: function(d) {
                    d.userID = '<?php echo $_SESSION['userid']; ?>';
                }
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_account_transaction_manual_main"
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
                    "data": "narration"
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
                        button+='<button class="btn btn-dark btn-sm btnview mr-1" id="'+full['idtbl_account_transaction_manual_main']+'" data-toggle="tooltip" data-placement="bottom" title="View and post" data-poststatus="'+full['poststatus']+'" data-recordstatus="'+full['status']+'">';
                        if(full['poststatus']==0){
                            button+='<i class="fas fa-exchange-alt"></i>';
                        }
                        else{
                            button+='<i class="fas fa-eye"></i>';
                        }
                        button+='</button>';
                        if(full['poststatus']==0){
                            if(editcheck==1){
                                if(full['completestatus']==0 && full['transactiontype']==1){
                                    button+='<button type="button" class="btn btn-primary btn-sm btnEdit mr-1" id="'+full['idtbl_account_transaction_manual_main']+'" data-transtype="'+full['transactiontype']+'"><i class="fas fa-pen"></i></button>';
                                }
                                else if(full['transactiontype']==0){
                                    button+='<button type="button" class="btn btn-primary btn-sm btnEdit mr-1" id="'+full['idtbl_account_transaction_manual_main']+'" data-transtype="'+full['transactiontype']+'"><i class="fas fa-pen"></i></button>';
                                }
                            }
                            if(full['status']==1 && statuscheck==1){
                                button+='<button type="button" data-url="Journalentry/Journalentrystatus/'+full['idtbl_account_transaction_manual_main']+'/2" data-actiontype="2" class="btn btn-success btn-sm mr-1 btntableaction"><i class="fas fa-check"></i></button>';
                            }else if(full['status']==2 && statuscheck==1){
                                button+='<button type="button" data-url="Journalentry/Journalentrystatus/'+full['idtbl_account_transaction_manual_main']+'/1" data-actiontype="1" class="btn btn-warning btn-sm mr-1 text-light btntableaction"><i class="fas fa-times"></i></button>';
                            }
                            if(deletecheck==1){
                                button+='<button type="button" data-url="Journalentry/Journalentrystatus/'+full['idtbl_account_transaction_manual_main']+'/3" data-actiontype="3" class="btn btn-danger btn-sm text-light btntableaction"><i class="fas fa-trash-alt"></i></button>';
                            }
                        }
                        
                        return button;
                    }
                }
            ],
            drawCallback: function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
        $('#dataTable tbody').on('click', '.btnview', function() {
            var id = $(this).attr('id');
            var recordstatus = $(this).attr("data-recordstatus");
            if(recordstatus==1){$('#btnposttransaction').removeClass('d-none');}
            else{$('#btnposttransaction').addClass('d-none');}

            var poststatus = $(this).attr("data-poststatus");
            var recordtype = $(this).attr("data-recordtype");

            $('#journalid').val(id);

            $('#modalviewpost').modal('show');
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: '<?php echo base_url() ?>Journalentry/Getviewpostinfo',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);
                    $('#viewdiv').html(obj.html);
                    if(obj.editablestatus==1){$('#btnposttransaction').addClass('d-none');}
                    else if(poststatus==1){$('#btnposttransaction').prop('disabled', true).addClass('d-none');}
                    else{$('#btnposttransaction').prop('disabled', false).removeClass('d-none');}
                }
            });
        });
        $('#dataTable tbody').on('click', '.btnEdit', async function() {
            var r = await Otherconfirmation("You want to Edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                var transtype = $(this).data("transtype");
                
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: '<?php echo base_url() ?>Journalentry/Journalentryedit',
                    success: function(result) { //alert(result);
                        // console.log(result);
                        var obj = JSON.parse(result);
                        if(transtype==0){
                            $('#recordID').val(obj.id);
                            $('#company').val(obj.companyid); 
                            getbranchlist(obj.companyid, obj.branchid);

                            $('#showcompany').val(obj.company);                       
                            $('#showbranch').val(obj.branch); 

                            $('#tradate').val(obj.tradate);   
                            $('#traamount').val(obj.amount);   

                            getaccountlist(obj.accountcr, 'accountcrno', obj.companyid, obj.branchid);
                            var newOptioncr = new Option(obj.accountcr, obj.accountcrid, true, true);
                            $('#accountcrno').append(newOptioncr).trigger('change');
                            var optionDatacr = $('#accountcrno').select2('data');
                            var lastOptioncr = optionDatacr[optionDatacr.length - 1]; 
                            lastOptioncr.data = { type: obj.accounttypecr };
                            $('#accountcrno').trigger('change');      
                            $('#narrationcr').val(obj.narrationcr);  
    
                            getaccountlist(obj.accountdr, 'accountdrno', obj.companyid, obj.branchid);     
                            var newOptiondr = new Option(obj.accountdr, obj.accountdrid, true, true);
                            $('#accountdrno').append(newOptiondr).trigger('change');
                            var optionDatadr = $('#accountdrno').select2('data');
                            var lastOptiondr = optionDatadr[optionDatadr.length - 1]; 
                            lastOptiondr.data = { type: obj.accounttypedr };
                            $('#accountdrno').trigger('change');
                            $('#narrationdr').val(obj.narrationdr);  
                            
                            $('#recordOption').val('2');
                            $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                            $('#modaljournal').modal('show'); 
                        } 
                        else{
                            $('#glbatchcompany').val('<?php echo $_SESSION['company'] ?>');
                            $('#glbatchbranch').val('<?php echo $_SESSION['branch'] ?>');
                            
                            var company='<?php echo $_SESSION['companyid'] ?>';
                            var branch='<?php echo $_SESSION['branchid'] ?>';
                            getaccountlist('', 'glbatchaccountno', company, branch, 'batchtransactionmodal');

                            $('#batchMainTransID').val(obj.id);
                            $('#batchMainTransBatchNo').val(obj.batchno);
                            $('#batchMainTransMaster').val(obj.masterID);
                            $('#batchtablelist tbody').empty().append(obj.tablecontent);
                            updateTotals();

                            $('#batchtransactionmodal').modal('show');
                        }                 
                    }
                });
            }
        });

        $('#btncreateentry').click(function() {
            getbranchlist('<?php echo $_SESSION['companyid'] ?>', '');
        });
        $('#btnchoosecominfo').click(function(){
            if (!$("#choosecompanyinfoform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidecomchoosesubmit").click();
            } else {
                if($('#glpassstatus').val()==0){
                    $('#showcompany').val($("#company option:selected").text());
                    $('#showbranch').val($("#branch option:selected").text());

                    $('#modalcompanychoose').modal('hide');
                    $('#modaljournal').modal('show');

                    if($('#recordOption').val()==1){
                        var company=$('#company').val();
                        var branch=$('#branch').val();
                        getaccountlist('', 'accountcrno', company, branch, 'modaljournal');
                        getaccountlist('', 'accountdrno', company, branch, 'modaljournal');
                    }
                }
                else{
                    $('#glshowcompany').val($("#company option:selected").text());
                    $('#glshowbranch').val($("#branch option:selected").text());

                    $('#modalcompanychoose').modal('hide');
                    $('#modalparsemodal').modal('show');

                    var company=$('#company').val();
                    var branch=$('#branch').val();
                    getaccountlist('', 'glaccountcrno', company, branch, 'modalparsemodal');
                    getaccountlist('', 'glaccountdrno', company, branch, 'modalparsemodal');
                }
            }
        });
        
        $('#btncreatejournal').click(function(){
            if (!$("#journalform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmit").click();
            } else {
                $('#btncreatejournal').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i> Complete');

                var recordID = $('#recordID').val();
                var recordOption = $('#recordOption').val();
                var company = $('#company').val();
                var branch = $('#branch').val();

                var tradate = $('#tradate').val();
                var traamount = $('#traamount').val();
                var accountcrno = $('#accountcrno').val();
                var selectedData = $('#accountcrno').select2('data')[0];
                var accounttypecr = selectedData ? selectedData.data.type : null;
                var narrationcr = $('#narrationcr').val();
                var accountdrno = $('#accountdrno').val();
                var selectedData = $('#accountdrno').select2('data')[0];
                var accounttypedr = selectedData ? selectedData.data.type : null;
                var narrationdr = $('#narrationdr').val();

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
                                tradate: tradate,
                                traamount: traamount,
                                accountcrno: accountcrno,
                                narrationcr: narrationcr,
                                accountdrno: accountdrno,
                                narrationdr: narrationdr,
                                accounttypecr: accounttypecr,
                                accounttypedr: accounttypedr,
                                recordOption: recordOption,
                                recordID: recordID
                            },
                            url: 'Journalentry/Journalentryinsertupdate',
                            success: function (result) { //alert(result);
                                // console.log(result);
                                Swal.close();
                                var obj = JSON.parse(result);
                                if (obj.status == 1) {
                                    // $('#hidesegreset').click();
                                    $('#tradate').val('');
                                    $('#traamount').val('');
                                    $('#accountcrtype').val('');
                                    $('#accountcrno').val('').trigger('change');
                                    $('#narrationcr').val('');
                                    $('#accountdrtype').val('');
                                    $('#accountdrno').val('').trigger('change');
                                    $('#narrationdr').val('');
                                    $('#btncreatejournal').prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Complete');

                                    if(recordOption==2){
                                        setTimeout( function(){ 
                                            $('#modaljournal').modal('hide');
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

        $('#modaljournal').on('hidden.bs.modal', function (event) {
            window.location.reload();
        }); 
        $('#modalparsemodal').on('hidden.bs.modal', function (event) {
            $('#glpassstatus').val('0');
        }); 

        $('#btnposttransaction').click(async function() {
            var r = await Otherconfirmation("You want to post this transaction ? ");
            if (r == true) {
                $('#btnposttransaction').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i> Post Transaction');
                var journalID = $('#journalid').val();

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
                                recordID: journalID
                            },
                            url: '<?php echo base_url() ?>Journalentry/Journalentryposting',
                            success: function (result) { //alert(result);
                                Swal.close();
                                var obj = JSON.parse(result);
                                if (obj.status == 1) {
                                    $('#btnposttransaction').prop('disabled', false).html('<i class="fas fa-exchange-alt mr-2"></i> Post Transaction');
                                    actionreload(obj.action);
                                }
                                else{
                                    $('#btnposttransaction').prop('disabled', false).html('<i class="fas fa-exchange-alt mr-2"></i> Post Transaction');
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

        $('#btnparseentry').click(function(){
            $('#glpassstatus').val('1');
            getbranchlist('<?php echo $_SESSION['companyid'] ?>', '');

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
                        data: {},
                        url: '<?php echo base_url() ?>Journalentry/Getglpassdatalist',
                        success: function(result) { //alert(result);
                            Swal.close();
                            $('#tabledatalist > tbody').empty().html(result);
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
        });

        $('#btnpasstoglaccount').click(function(){
            if (!$("#journalformtogl")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmitgl").click();
            } else {
                var tablelist = $("#tabledatalist tbody input[type=checkbox]:checked");
                
                if(tablelist.length>0){
                    $('#btnpasstoglaccount').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i>Pass to GL');;
                    jsonObj = [];
                    tablelist.each(function() {
                        item = {}
                        var row = $(this).closest("tr");
                        if(row.find('.recordid').text()!=''){
                            item["recordid"] = row.find('.recordid').text();
                            jsonObj.push(item);
                        }
                    });
                    var myJSON = JSON.stringify(jsonObj);
                    
                    var gltradate= $('#gltradate').val();
                    var glaccountcrno= $('#glaccountcrno').val();
                    var selectedData = $('#glaccountcrno').select2('data')[0];
                    var glaccounttypecr = selectedData ? selectedData.data.type : null;

                    var glaccountdrno= $('#glaccountdrno').val();
                    var selectedData = $('#glaccountdrno').select2('data')[0];
                    var glaccounttypedr = selectedData ? selectedData.data.type : null;

                    var company = $('#company').val();
                    var branch = $('#branch').val();

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
                                    gltradate: gltradate,
                                    glaccountcrno: glaccountcrno,
                                    glaccountdrno: glaccountdrno,
                                    company: company,
                                    branch: branch,
                                    glaccounttypecr: glaccounttypecr,
                                    glaccounttypedr: glaccounttypedr,
                                    tabledata: myJSON
                                },
                                url: '<?php echo base_url() ?>Journalentry/Passtoglentry',
                                success: function(result) { //alert(result);
                                    // console.log(result);
                                    Swal.close();
                                    var obj = JSON.parse(result);

                                    if(obj.status==1){
                                        action(obj.action);
                                        setTimeout(function(){location.reload();}, 3000);
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
                else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Kindly verify the pass to GL of one or more account numbers.'
                    });
                }
            }
        });

        //Batch Transaction
        $('#btnjurnalbatches').click(function(){
            $('#glbatchcompany').val('<?php echo $_SESSION['company'] ?>');
            $('#glbatchbranch').val('<?php echo $_SESSION['branch'] ?>');

            var company='<?php echo $_SESSION['companyid'] ?>';
            var branch='<?php echo $_SESSION['branchid'] ?>';
            getaccountlist('', 'glbatchaccountno', company, branch, 'batchtransactionmodal');
            
            $('#batchtransactionmodal').modal('show');
        });
        $('#btnbatchsubmit').click(function(){
            if (!$("#journalformbatch")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmitglbatch").click();
            } else {
                var glbatchtradate = $('#glbatchtradate').val();
                var glbatchcreditdebit = $('#glbatchcreditdebit').val();
                var glbatchaccountID = $('#glbatchaccountno').val();
                var glbatchaccountno = $('#glbatchaccountno').select2('data')[0].text; 
                var selectedData = $('#glbatchaccountno').select2('data')[0];
                var accounttype = selectedData ? selectedData.data.type : null;
                var glbatchnarration = $('#glbatchnarration').val();
                var glbatchamount = $('#glbatchamount').val();

                var batchMainTransID = $('#batchMainTransID').val();
                var batchMainTransBatchNo = $('#batchMainTransBatchNo').val();
                var batchMainTransMaster = $('#batchMainTransMaster').val();

                if(glbatchcreditdebit==1){
                    var cdtype = 'C';
                    var creditamount = glbatchamount;
                    var debitamount = '0';
                }else{
                    var cdtype = 'D';
                    var debitamount = glbatchamount;
                    var creditamount = '0';
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
                                glbatchtradate: glbatchtradate,
                                glbatchcreditdebit: glbatchcreditdebit,
                                cdtype: cdtype,
                                glbatchaccountID: glbatchaccountID,
                                glbatchnarration: glbatchnarration,
                                accounttype: accounttype,
                                glbatchamount: glbatchamount,
                                batchMainTransID: batchMainTransID,
                                batchMainTransBatchNo: batchMainTransBatchNo,
                                batchMainTransMaster: batchMainTransMaster
                            },
                            url: '<?php echo base_url() ?>Journalentry/Journalentrybatchinsertupdate',
                            success: function (result) {
                                Swal.close();
                                var obj = JSON.parse(result);
                                if (obj.status == 1) {
                                    $('#batchtablelist tbody').append('<tr>' +
                                        '<td nowrap class="d-none">'+obj.batchtransmainID+'</td>' +
                                        '<td nowrap>' + glbatchtradate + '</td>' +
                                        '<td nowrap class="d-none">'+glbatchaccountID+'</td>' +
                                        '<td nowrap>' + glbatchaccountno + '</td>' +
                                        '<td nowrap class="d-none">'+glbatchcreditdebit+'</td>' +
                                        '<td nowrap class="text-center">' + cdtype + '</td>' + 
                                        '<td nowrap>'+obj.batchno+'</td>' +
                                        '<td nowrap>' + glbatchnarration + '</td>' +
                                        '<td nowrap class="text-right">' + debitamount + '</td>' +
                                        '<td nowrap class="text-right">' + creditamount + '</td>' +
                                        '</tr>');
                                        
                                    $('#glbatchtradate').val('');
                                    $('#glbatchcreditdebit').val('');
                                    $('#glbatchaccountno').val('').trigger('change');
                                    $('#glbatchnarration').val('');
                                    $('#glbatchamount').val('');
                                    
                                    $('#batchMainTransID').val(obj.batchtransmainID);
                                    $('#batchMainTransBatchNo').val(obj.batchno);
                                    $('#batchMainTransMaster').val(obj.masterID);

                                    updateTotals();
                                    
                                    action(obj.action);
                                }
                                else{
                                    action(obj.action);
                                }
                            },
                            error: function(error) {
                                Swal.close();
                                
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
        $('#btnsavebatchjournal').click(async function(){
            var r = await Otherconfirmation("You want to complate this transaction ? ");
            if (r == true) {
                var batchMainTransID = $('#batchMainTransID').val();
                var batchMainTransBatchNo = $('#batchMainTransBatchNo').val();
                var batchMainTransMaster = $('#batchMainTransMaster').val();
                
                let creditTotal = 0;
                let debitTotal = 0;
                
                $('#batchtablelist tbody tr').each(function() {
                    const creditText = $(this).find('td:nth-child(9)').text().replace(/,/g, '');
                    const debitText = $(this).find('td:nth-child(10)').text().replace(/,/g, '');
                    
                    const credit = parseFloat(creditText) || 0;
                    const debit = parseFloat(debitText) || 0;
                    
                    creditTotal += credit;
                    debitTotal += debit;
                });
                
                creditTotal = creditTotal.toFixed(2);
                debitTotal = debitTotal.toFixed(2);
                
                if(creditTotal==debitTotal){
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
                                    recordID: batchMainTransID,
                                    netamount: creditTotal
                                },
                                url: '<?php echo base_url() ?>Journalentry/Journalentrybatchcomplete',
                                success: function (result) {
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
                                    Swal.close();
                                    
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
                else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'You can`t complete this transaction. Because of not tallying credit and debit values.'
                    });
                }
            }
        });
        $('#batchtablelist tbody').on('click', 'td', async function() {
            var r = await Otherconfirmation("You want to remove this record ? ");
    		if (r == true) {
                var row = $(this).closest('tr');
                var transID = row.find('td:nth-child(1)').text().trim();
                var batchMainTransID = $('#batchMainTransID').val();
                
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
                                batchtransinfoID: transID,
                                batchMainTransID: batchMainTransID
                            },
                            url: '<?php echo base_url() ?>Journalentry/Journalentryinfostatus',
                            success: function (result) {
                                Swal.close();
                                var obj = JSON.parse(result);
                                if (obj.status == 1) {
                                    action(obj.action);
                                    row.closest('tr').remove();
                                    updateTotals();
                                }
                                else{
                                    action(obj.action);
                                }
                            },
                            error: function(error) {
                                Swal.close();
                                
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

    function getbranchlist(id, value){
        $.ajax({
            type: "POST",
            data: {
                recordID: id
            },
            url: '<?php echo base_url() ?>Journalentry/Getbranchaccocompany',
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

    function getaccountlist(value, field, company, branch, modalname){
        $("#"+field).select2({
            dropdownParent: $('#'+modalname),
            ajax: {
                url: "<?php echo base_url() ?>Payablesegregation/Getaccountlist",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term, // search term
                        companyid: company,
                        branchid: branch
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

    function formatNumber(num) {
        return parseFloat(num).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    function updateTotals() {
        let creditTotal = 0;
        let debitTotal = 0;
        
        // Loop through each row in the table body
        $('#batchtablelist tbody tr').each(function() {
            const debitText = $(this).find('td:nth-child(9)').text().replace(/,/g, '');
            const creditText = $(this).find('td:nth-child(10)').text().replace(/,/g, '');
            
            const credit = parseFloat(creditText) || 0;
            const debit = parseFloat(debitText) || 0;
            
            creditTotal += credit;
            debitTotal += debit;
        });
        
        // Update the footer with formatted numbers
        $('.credittotal').text(formatNumber(creditTotal));
        $('.debittotal').text(formatNumber(debitTotal));
    }
</script>
<?php include "include/footer.php"; ?>
