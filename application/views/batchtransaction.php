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
                            <div class="page-header-icon"><i class="fas fa-clipboard-list"></i></div>
                            <span>Batch Transaction</span>
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
                                <button type="button" class="btn btn-primary btn-sm px-3" id="btncreatebatchtransaction"><i class="fas fa-plus mr-2"></i> Create Transaction</button>
                                <hr>
                                <?php endif; ?>
                            </div>
                            <div class="col-12">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-striped table-bordered table-sm small nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Batch Category</th>
                                                <th>Batch Type</th>
                                                <th>Year</th>
                                                <th>Month</th>
                                                <th>Trans Date</th>
                                                <th>Trans Batch</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div id="viewtypehtml"></div>
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
	<div class="modal-dialog modal-dialog-centered modal-sm">
		<div class="modal-content">
			<div class="modal-header p-2">
				<h6 class="modal-title" id="batchTypeModalLabel">Batch Type</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" autocomplete="off" id="formbatchtype">
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
                        <div class="col">
                            <label class="small font-weight-bold">Batch Transaction Type*</label><br>
                            <select name="batchtranstype" id="batchtranstype" class="form-control form-control-sm" required>
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                </form>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="batchCreateModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="batchCreateModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl">
		<div class="modal-content">
			<div class="modal-header p-2">
				<h6 class="modal-title" id="batchCreateModalLabel">Batch Create</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="collapse" id="inventoryCollapse">
                    <div class="card card-body p-0 shadow-none border-0">
                        <form id="inventoryform">
                            <div class="form-row mb-1">
                                <div class="col-2">
                                    <label class="small font-weight-bold">Date*</label>
                                    <input type="date" name="inventorydate" id="inventorydate" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                <div class="col-2">
                                    <label class="small font-weight-bold">Transaction Code*</label>
                                    <input type="text" name="inventorytranscode" id="inventorytranscode" class="form-control form-control-sm" readonly>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Material*</label> 
                                    <select name="inventorymaterial" id="inventorymaterial" class="form-control form-control-sm" style="width: 100%;" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>   
                                <div class="col-3">
                                    <label class="small font-weight-bold">Batch No</label>
                                    <select name="inventorybatchno" id="inventorybatchno" class="form-control form-control-sm">
                                        <option value="">Select</option>
                                    </select>
                                </div>   
                            </div>  
                            <div class="form-row mb-1">
                                <div class="col-2">
                                    <label class="small font-weight-bold">UOM*</label>
                                    <select name="inventoryuom" id="inventoryuom" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php foreach($uomlist->result() as $rowuom){ ?>
                                        <option value="<?php echo $rowuom->idtbl_mesurements ?>"><?php echo $rowuom->measure_type ?></option>
                                        <?php } ?>
                                    </select>
                                </div> 
                                <div class="col-4">
                                    <label class="small font-weight-bold">Reference</label>
                                    <input type="text" name="inventoryreference" id="inventoryreference" class="form-control form-control-sm">
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Description</label>
                                    <input type="text" name="inventorydescription" id="inventorydescription" class="form-control form-control-sm">
                                </div>
                            </div>    
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold">Qty On Hand</label>
                                    <input type="number" name="inventoryqtyonhand" id="inventoryqtyonhand" class="form-control form-control-sm" value="0" readonly>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Qty In</label>
                                    <input type="number" name="inventoryqtyin" id="inventoryqtyin" class="form-control form-control-sm" step="any" value="0">
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Qty Out</label>
                                    <input type="number" name="inventoryqtyout" id="inventoryqtyout" class="form-control form-control-sm" step="any" value="0">
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Unit Cost</label>
                                    <input type="number" name="inventoryunitcost" id="inventoryunitcost" class="form-control form-control-sm" step="any" value="0">
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">New Unit Cost</label>
                                    <input type="number" name="inventorynewunitcost" id="inventorynewunitcost" class="form-control form-control-sm" step="any" value="0">
                                </div>
                            </div>  
                            <?php if($addcheck==1): ?>
                            <div class="form-row mb-1">
                                <div class="col text-right">
                                    <hr>
                                    <button type="button" id="btnaddtolist" class="btn btn-secondary btn-sm px-4">Add to list</button>
                                    <input type="submit" class="d-none" id="hidesubmitinventory">
                                </div>
                            </div>
                            <?php endif; ?>
                        </form>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-striped table-bordered table-sm small w-100" id="batchtransinventorytable">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th nowrap class="d-none">#</th>
                                                <th nowrap>Item</th>
                                                <th nowrap>Date</th>
                                                <th nowrap>Tr Code</th>
                                                <th nowrap>Referance</th>
                                                <th nowrap>Description</th>
                                                <th nowrap>Qty On Hand</th>
                                                <th nowrap>Qty In</th>
                                                <th nowrap>Qty Out</th>
                                                <th nowrap>Unit Cost</th>
                                                <th nowrap>New Unit Cost</th>
                                                <th nowrap>UOM</th>
                                                <th nowrap>Batch No</th>
                                                <th nowrap class="d-none">UOMID</th>
                                                <th nowrap class="d-none">materialID</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-right">
                                <?php if($addcheck==1): ?>
                                <hr>
                                <button type="button" class="btn btn-primary btn-sm px-3" id="btnsavebatchtrans"><i class="fas fa-save mr-2"></i> Create Transaction</button>
                                <?php endif; ?>
                            </div>
                            <div class="col-12">
                                <div id="alertdivcreate"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="collapse" id="receiptCollapse">
                    <div class="card card-body p-0 shadow-none border-0">
                        <form id="receiptform">
                            <div class="form-row mb-1">
                                <div class="col-4">
                                    <label class="small font-weight-bold">Vendor/Customer*</label><br>
                                    <select name="receiptcustomer" id="receiptcustomer" class="form-control form-control-sm" style="width: 100%;" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Transaction Code*</label>
                                    <input type="text" name="receipttranscode" id="receipttranscode" class="form-control form-control-sm" readonly>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Invoice Date*</label>
                                    <input type="date" name="receiptinvoicedate" id="receiptinvoicedate" class="form-control form-control-sm" max="<?php echo date('Y-m-d') ?>" required>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Invoice/Bill no*</label>
                                    <input type="text" name="receiptinvoice" id="receiptinvoice" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-2">
                                    <label class="small font-weight-bold">Credit/ Debit*</label>
                                    <select name="receiptcreditdebit" id="receiptcreditdebit" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php foreach($accounttranstypelist->result() as $rowaccounttranstypelist){ ?>
                                        <option value="<?php echo $rowaccounttranstypelist->idtbl_account_transactiontype ?>"><?php echo $rowaccounttranstypelist->transactiontype ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label class="small font-weight-bold">Invoice Amount*</label>
                                    <input type="text" name="receiptinvoiceamount" id="receiptinvoiceamount" class="form-control form-control-sm text-right input-integer" required>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Narration*</label>
                                    <input type="text" name="receiptnarration" id="receiptnarration" class="form-control form-control-sm">
                                </div>
                            </div>
                            <?php if($addcheck==1): ?>
                            <div class="row">
                                <div class="col-12 text-right">
                                    <hr>
                                    <button type="button" class="btn btn-primary btn-sm px-4" id="btnreceiptsubmit"><i class="fas fa-list mr-2"></i>Add to list</button>
                                    <input type="submit" id="hidesubmitreceipt" class="d-none">
                                </div>
                            </div>
                            <?php endif; ?>
                        </form>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-striped table-bordered table-sm small w-100" id="batchtransreceipttable">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th nowrap class="d-none">#</th>
                                                <th nowrap>Customer</th>
                                                <th nowrap>Date</th>
                                                <th nowrap>Tr Code</th>
                                                <th nowrap>Invoice | Bill No</th>
                                                <th nowrap>Narration</th>
                                                <th nowrap class="text-center">C/D</th>
                                                <th nowrap class="text-right">Debit</th>
                                                <th nowrap class="text-right">Credit</th>
                                                <th nowrap class="d-none">cdID</th>
                                                <th nowrap class="d-none">customerID</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-right">
                                <?php if($addcheck==1): ?>
                                <hr>
                                <button type="button" class="btn btn-primary btn-sm px-3" id="btnsavereceipttrans"><i class="fas fa-save mr-2"></i> Create Transaction</button>
                                <?php endif; ?>
                            </div>
                            <div class="col-12">
                                <div id="alertdivreceiptcreate"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="collapse" id="paymentCollapse">
                    <div class="card card-body p-0 shadow-none border-0">
                        <form id="paymentform">
                            <div class="form-row mb-1">
                                <div class="col-4">
                                    <label class="small font-weight-bold">Provider/Supplier*</label><br>
                                    <select name="paymentsupplier" id="paymentsupplier" class="form-control form-control-sm" style="width: 100%;" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Transaction Code*</label>
                                    <input type="text" name="paymenttranscode" id="paymenttranscode" class="form-control form-control-sm" readonly>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Invoice Date*</label>
                                    <input type="date" name="paymentinvoicedate" id="paymentinvoicedate" class="form-control form-control-sm" max="<?php echo date('Y-m-d') ?>" required>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Invoice/Bill no*</label>
                                    <input type="text" name="paymentinvoice" id="paymentinvoice" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-2">
                                    <label class="small font-weight-bold">Credit/ Debit*</label>
                                    <select name="paymentcreditdebit" id="paymentcreditdebit" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php foreach($accounttranstypelist->result() as $rowaccounttranstypelist){ ?>
                                        <option value="<?php echo $rowaccounttranstypelist->idtbl_account_transactiontype ?>"><?php echo $rowaccounttranstypelist->transactiontype ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label class="small font-weight-bold">Invoice Amount*</label>
                                    <input type="text" name="paymentinvoiceamount" id="paymentinvoiceamount" class="form-control form-control-sm text-right input-integer" required>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Narration*</label>
                                    <input type="text" name="paymentnarration" id="paymentnarration" class="form-control form-control-sm">
                                </div>
                            </div>
                            <?php if($addcheck==1): ?>
                            <div class="row">
                                <div class="col-12 text-right">
                                    <hr>
                                    <button type="button" class="btn btn-primary btn-sm px-4" id="btnpaymentsubmit"><i class="fas fa-list mr-2"></i>Add to list</button>
                                    <input type="submit" id="hidesubmitpayment" class="d-none">
                                </div>
                            </div>
                            <?php endif; ?>
                        </form>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-striped table-bordered table-sm small w-100" id="batchtranspaymenttable">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th nowrap class="d-none">#</th>
                                                <th nowrap>Supplier</th>
                                                <th nowrap>Date</th>
                                                <th nowrap>Tr Code</th>
                                                <th nowrap>Invoice | Bill No</th>
                                                <th nowrap>Narration</th>
                                                <th nowrap class="text-center">C/D</th>
                                                <th nowrap class="text-right">Debit</th>
                                                <th nowrap class="text-right">Credit</th>
                                                <th nowrap class="d-none">cdID</th>
                                                <th nowrap class="d-none">supplierID</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-right">
                                <?php if($addcheck==1): ?>
                                <hr>
                                <button type="button" class="btn btn-primary btn-sm px-3" id="btnsavepaymenttrans"><i class="fas fa-save mr-2"></i> Create Transaction</button>
                                <?php endif; ?>
                            </div>
                            <div class="col-12">
                                <div id="alertdivpaymentcreate"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="batchTransID" id="batchTransID" value="">
                <input type="hidden" name="batchTransCategoryID" id="batchTransCategoryID" value="">
                <input type="hidden" name="batchTransTypeID" id="batchTransTypeID" value="">
                <input type="hidden" name="batchTransBatchNo" id="batchTransBatchNo" value="">
                <input type="hidden" name="batchTransMaster" id="batchTransMaster" value="">
			</div>
		</div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="viewtransactioninfoModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="viewtransactioninfoModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl">
		<div class="modal-content">
			<div class="modal-header p-2">
				<h6 class="modal-title" id="viewtransactioninfoModalLabel">Transaction view & approve</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="showdata"></div>
                <div class="row">
                    <div class="col-12 text-right">
                        <?php if($approvecheck==1){ ?>
                        <button id="btnapprovereject" class="btn btn-primary btn-sm px-3"><i class="fas fa-check mr-2"></i>Approve or Reject</button>
                        <?php } ?>
                        <input type="hidden" name="batchtransapproveID" id="batchtransapproveID">
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

        $('.input-integer').inputNumber({
			maxDecimalDigits: 4
		});
        // $('#batchTypeModal').modal('show');

        $('#btncreatebatchtransaction').click(function() {
            $('#batchTypeModal').modal('show');
        });

        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "<?php echo base_url() ?>scripts/batchtransactionlist.php",
                type: "POST", // you can use GET
                data: function(d) {
                    d.userID = '<?php echo $_SESSION['userid']; ?>';
                }
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_batch_transaction_main"
                },
                {
                    "data": "batch_category"
                },
                {
                    "targets": -1,
                    "className": '',
                    "data": "batctranstypecode",
                    "render": function(data, type, full) {
                        return full['batctranstypecode'] + ' - ' + full['batctranstype'];
                    }
                },
                {
                    "data": "desc"
                },
                {
                    "data": "monthname"
                },
                {
                    "data": "transdate"
                },
                {
                    "data": "batchno"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        if(full['approvestatus']==1) {
                            return '<span class="text-success font-weight-bold"><i class="fas fa-check-circle"></i> Approved</span>';
                        } else if(full['approvestatus']==0) {
                            return '<span class="text-warning font-weight-bold"><i class="fas fa-redo"></i> Pending</span>';
                        }
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';

                        if(full['status']==1){
                            button += '<button class="btn btn-dark btn-sm btnview mr-1" id="' + full['idtbl_batch_transaction_main'] + '" data-transcate="'+full['idtbl_batch_category']+'" data-completestatus="'+full['completestatus']+'" data-toggle="tooltip" title="View & Approve" data-approvestatus="'+full['approvestatus']+'"><i class="fas fa-eye"></i></button>';
                        }

                        if(editcheck==1 && full['completestatus']==0 && full['status']==1){
                            button+='<button type="button" class="btn btn-primary btn-sm btnEdit mr-1" id="'+full['idtbl_batch_transaction_main']+'"><i class="fas fa-pen"></i></button>';
                        }
                        if(full['status']==1 && statuscheck==1 && full['approvestatus']==0){
                            button+='<button type="button" data-url="BatchTransaction/BatchTransactionstatus/'+full['idtbl_batch_transaction_main']+'/2" data-actiontype="2" class="btn btn-success btn-sm mr-1 btntableaction"><i class="fas fa-check"></i></button>';
                        }else if(full['status']==2 && statuscheck==1 && full['approvestatus']==0){
                            button+='<button type="button" data-url="BatchTransaction/BatchTransactionstatus/'+full['idtbl_batch_transaction_main']+'/1" data-actiontype="1" class="btn btn-warning btn-sm mr-1 text-light btntableaction"><i class="fas fa-times"></i></button>';
                        }
                        if(deletecheck==1 && full['approvestatus']==0){
                            button+='<button type="button" data-url="BatchTransaction/BatchTransactionstatus/'+full['idtbl_batch_transaction_main']+'/3" data-actiontype="3" class="btn btn-danger btn-sm text-light btntableaction"><i class="fas fa-trash-alt"></i></button>';
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
                                recordID: id
                            },
                            url: '<?php echo base_url() ?>BatchTransaction/BatchTransactionedit',
                            success: function(result) { //alert(result);                            
                                Swal.close();
                                document.body.style.overflow = 'auto';

                                $('#batchCreateModal').modal('show');
                                var obj = JSON.parse(result);
                                var selectedCategory = obj.batchcategory;
                                if(selectedCategory==1) {
                                    $('#inventoryCollapse').collapse('show');

                                    $('#inventorydate').val(obj.transdate);
                                    $('#inventorytranscode').val(obj.batctranstypecode);
                                    $('#batchTransID').val(obj.id);
                                    $('#batchTransCategoryID').val(obj.batchcategory);
                                    $('#batchTransTypeID').val(obj.batchtype);
                                    $('#batchTransBatchNo').val(obj.batchno);
                                    $('#batchTransMaster').val(obj.masterid);
                                    $('#batchtransinventorytable tbody').empty().append(obj.batchinfo);

                                    if(obj.plusminus==1){
                                        $('#inventoryqtyout').prop('readonly', true);
                                        $('#inventoryunitcost').prop('readonly', true);
                                        $('#inventoryqtyin').prop('required', true);
                                    }
                                    else if(obj.plusminus==2){
                                        $('#inventoryqtyin').prop('readonly', true);
                                        $('#inventoryunitcost').prop('readonly', true);
                                        $('#inventoryqtyout').prop('required', true);
                                    }

                                    if(obj.approvestatus>0){
                                        $('#btnaddtolist').addClass('d-none').prop('disabled', true);
                                        $('#btnsavebatchtrans').addClass('d-none').prop('disabled', true);
                                        if(approvestatus==1){$('#alertdivcreate').html('<div class="alert alert-success" role="alert"><i class="fas fa-check-circle mr-2"></i> Batch Transaction approved</div>');}
                                        else if(approvestatus==2){$('#alertdivcreate').html('<div class="alert alert-danger" role="alert"><i class="fas fa-times-circle mr-2"></i> Batch Transaction rejected</div>');}
                                    }
                                    else if(obj.completestatus==1){
                                        $('#btnaddtolist').addClass('d-none').prop('disabled', true);
                                        $('#btnsavebatchtrans').addClass('d-none').prop('disabled', true);
                                        $('#alertdivcreate').html('<div class="alert alert-danger" role="alert"><i class="fas fa-check-circle mr-2"></i> Your can`t modify this batch transaction. This batch transaction already completed.</div>');
                                    }
                                }
                                else if(selectedCategory==2) {
                                    $('#receiptCollapse').collapse('show');

                                    $('#receiptinvoicedate').val(obj.transdate);
                                    $('#receipttranscode').val(obj.batctranstypecode);
                                    $('#batchTransID').val(obj.id);
                                    $('#batchTransCategoryID').val(obj.batchcategory);
                                    $('#batchTransTypeID').val(obj.batchtype);
                                    $('#batchTransBatchNo').val(obj.batchno);
                                    $('#batchTransMaster').val(obj.masterid);
                                    $('#batchtransreceipttable tbody').empty().append(obj.batchinfo);
                                }
                                else if(selectedCategory==3) {
                                    $('#paymentCollapse').collapse('show');

                                    $('#paymentinvoicedate').val(obj.transdate);
                                    $('#paymenttranscode').val(obj.batctranstypecode);
                                    $('#batchTransID').val(obj.id);
                                    $('#batchTransCategoryID').val(obj.batchcategory);
                                    $('#batchTransTypeID').val(obj.batchtype);
                                    $('#batchTransBatchNo').val(obj.batchno);
                                    $('#batchTransMaster').val(obj.masterid);
                                    $('#batchtranspaymenttable tbody').empty().append(obj.batchinfo);
                                }
                            },
                            error: function(error) {
                                // Close the SweetAlert on error
                                Swal.close();
                                document.body.style.overflow = 'auto';
                                
                                // Show an error alert
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Something went wrong. Please try again later.'
                                });
                            }
                        });
                    }
                }); 
            }
        });
        $('#dataTable tbody').on('click', '.btnview', function() {
            var id = $(this).attr('id');
            var transcate = $(this).data('transcate');
            var approvestatus = $(this).attr('data-approvestatus');
            var completestatus = $(this).attr('data-completestatus');

            $('#batchtransapproveID').val(id);

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
                            transcate: transcate,
                        },
                        url: '<?php echo base_url() ?>BatchTransaction/BatchTransactionview',
                        success: function(result) { //alert(result);
                            Swal.close();
                            document.body.style.overflow = 'auto';

                            $('#viewtransactioninfoModal').modal('show');
                            $('#showdata').html(result);
                            
                            if(approvestatus>0 || completestatus==0){
                                $('#btnapprovereject').addClass('d-none').prop('disabled', true);
                            }
                        },
                        error: function(error) {
                            // Close the SweetAlert on error
                            Swal.close();
                            document.body.style.overflow = 'auto';
                            
                            // Show an error alert
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong. Please try again later.'
                            });
                        }
                    });
                }
            }); 
        });
        $('#btnapprovereject').click(function(){
            Swal.fire({
                title: "Do you want to approve this inqury?",
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "Approve",
                denyButtonText: `Reject`
            }).then((result) => {
                if (result.isConfirmed) {
                    var confirmnot = 1;
                    approvetransaction(confirmnot);
                } else if (result.isDenied) {
                    var confirmnot = 2;
                    approvetransaction(confirmnot);
                } 
            });
        });

        //Inventory Type Start
        $('#batchcategory').change(function() {
            var selectedCategory = $(this).val();
            $.ajax({
                type: "POST",
                data: {
                    recordID: selectedCategory
                },
                url: '<?php echo base_url() ?>BatchTransaction/GetBatchTransType',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);
                    var html = '';
                    html += '<option value="">Select</option>';
                    $.each(obj, function (i, item) {
                        html += '<option value="' + obj[i].idtbl_batch_trans_type + '" data-transcode="' + obj[i].batctranstypecode + '" data-plusminus="' + obj[i].plusminus + '">';
                        html += obj[i].batctranstypecode + ' - '+ obj[i].batctranstype ;
                        html += '</option>';
                    });
                    $('#batchtranstype').empty().append(html);   
                }
            });
        });
        $('#batchtranstype').change(function() {
            var selectedCategory = $('#batchcategory').val();
            var selectedTransType = $(this).find(':selected').data('transcode');
            $('#inventorytranscode').val(selectedTransType);
            $('#receipttranscode').val(selectedTransType);
            $('#paymenttranscode').val(selectedTransType);
            var selectedplusminus = $(this).find(':selected').data('plusminus');
            if(selectedplusminus>0){
                $('#inventorybatchno').prop('required', true);
                if(selectedplusminus==1){
                    $('#inventoryqtyout').prop('readonly', true);
                    $('#inventoryunitcost').prop('readonly', true);
                    $('#inventoryqtyin').prop('required', true);
                }
                else if(selectedplusminus==2){
                    $('#inventoryqtyin').prop('readonly', true);
                    $('#inventoryunitcost').prop('readonly', true);
                    $('#inventoryqtyout').prop('required', true);
                }
            }

            $('#batchTransCategoryID').val(selectedCategory);
            $('#batchTransTypeID').val($(this).val());

            $('#batchTypeModal').modal('hide');
            $('#batchCreateModal').modal('show');
            if(selectedCategory==1) {
                $('#inventoryCollapse').collapse('show');
            }
            else if(selectedCategory==2) {
                $('#receiptCollapse').collapse('show');
            }
            else if(selectedCategory==3) {
                $('#paymentCollapse').collapse('show');
            }
        });
        $('#inventorymaterial').select2({
            placeholder: 'Select',
            allowClear: true,
            dropdownParent: $('#batchCreateModal'),
            ajax: {
                url: '<?php echo base_url("BatchTransaction/GetInventoryMaterial") ?>',
                type: 'POST',
                dataType: 'json',
                data: function (params) {
                    return {
                        searchTerm: params.term,
                        compantID: compantID,
                        branchID: branchID
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.text
                            };
                        })
                    };
                },
                cache: true
            }
        });
        $('#inventorymaterial').change(function() {
            var selectedMaterial = $(this).val();
            $.ajax({   
                type: "POST",
                data: {
                    materialID: selectedMaterial,
                },
                url: '<?php echo base_url() ?>BatchTransaction/GetMaterialDetails',
                success: function(result) {
                    var obj = JSON.parse(result);
                    if(obj.length > 0) {
                        var batchOptions = '<option value="">Select</option>';
                       $.each(obj, function (i, item) {
                            batchOptions += '<option value="' + obj[i].batchno + '" data-uom="' + obj[i].measure_type_id + '" data-avaqty="' + obj[i].qty + '" data-unitprice="' + obj[i].unitprice + '">' + obj[i].batchno + '</option>';
                        });
                        $('#inventorybatchno').empty().append(batchOptions);
                    } else {
                        $('#inventorybatchno').empty().append('<option value="">No Batch No</option>');
                    }
                }
            });
        });
        $('#inventorybatchno').change(function() {
            var selectedBatch = $(this).find(':selected');
            var uom = selectedBatch.data('uom');
            var availableQty = selectedBatch.data('avaqty');
            var unitPrice = selectedBatch.data('unitprice');    
            $('#inventoryuom').val(uom).trigger('change');
            $('#inventoryqtyonhand').val(availableQty);
            $('#inventoryunitcost').val(unitPrice);
        });

        $('#btnaddtolist').click(function() {
            if (!$("#inventoryform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmitinventory").click();
            } else {
                var inventoryDate = $('#inventorydate').val();
                var inventoryTransCode = $('#inventorytranscode').val();
                var inventoryMaterial = $('#inventorymaterial').val(); 
                var inventoryMaterialText = $('#inventorymaterial').select2('data')[0].text; 
                var inventoryUOMID = $('#inventoryuom').val();
                var inventoryUOM = $("#inventoryuom option:selected").text();
                var inventoryReference = $('#inventoryreference').val();
                var inventoryDescription = $('#inventorydescription').val();
                var inventoryQtyOnHand = $('#inventoryqtyonhand').val();
                var inventoryQtyIn = $('#inventoryqtyin').val();
                var inventoryQtyOut = $('#inventoryqtyout').val();
                var inventoryUnitCost = $('#inventoryunitcost').val();
                var inventoryNewUnitCost = $('#inventorynewunitcost').val();
                var inventoryBatchNo = $('#inventorybatchno').val();

                var batchcategory = $('#batchTransCategoryID').val();
                var batchtranstype = $('#batchTransTypeID').val();
                var batchTransID = $('#batchTransID').val();
                var batchTransBatchNo = $('#batchTransBatchNo').val();
                var batchTransMaster = $('#batchTransMaster').val();

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
                                inventoryDate: inventoryDate,
                                inventoryTransCode: inventoryTransCode,
                                inventoryMaterial: inventoryMaterial,
                                inventoryMaterialText: inventoryMaterialText,
                                inventoryUOMID: inventoryUOMID,
                                inventoryUOM: inventoryUOM,
                                inventoryReference: inventoryReference,
                                inventoryDescription: inventoryDescription,
                                inventoryQtyOnHand: inventoryQtyOnHand,
                                inventoryQtyIn: inventoryQtyIn,
                                inventoryQtyOut: inventoryQtyOut,
                                inventoryUnitCost: inventoryUnitCost,
                                inventoryNewUnitCost: inventoryNewUnitCost,
                                inventoryBatchNo: inventoryBatchNo,
                                batchTransID: batchTransID,
                                batchcategory: batchcategory,
                                batchtranstype: batchtranstype,
                                batchTransBatchNo: batchTransBatchNo,
                                batchTransMaster: batchTransMaster
                            },
                            url: 'BatchTransaction/BatchTransactioninsertupdate',
                            success: function (result) {
                                Swal.close();
                                var obj = JSON.parse(result);
                                if (obj.status == 1) {
                                    $('#batchtransinventorytable tbody').append('<tr>' +
                                        '<td nowrap class="d-none">' + obj.batchtransID + '</td>' +
                                        '<td nowrap>' + inventoryMaterialText + '</td>' +
                                        '<td nowrap>' + inventoryDate + '</td>' + 
                                        '<td nowrap>' + inventoryTransCode + '</td>' +
                                        '<td nowrap>' + inventoryReference + '</td>' +
                                        '<td nowrap>' + inventoryDescription + '</td>' +
                                        '<td nowrap>' + inventoryQtyOnHand + '</td>' +
                                        '<td nowrap>' + inventoryQtyIn + '</td>' +
                                        '<td nowrap>' + inventoryQtyOut + '</td>' +
                                        '<td nowrap>' + inventoryUnitCost + '</td>' +
                                        '<td nowrap>' + inventoryNewUnitCost + '</td>' +
                                        '<td nowrap>' + inventoryUOM + '</td>' +
                                        '<td nowrap>' + inventoryBatchNo + '</td>' +
                                        '<td nowrap class="d-none">' + inventoryUOMID + '</td>' +
                                        '<td nowrap class="d-none">' + inventoryMaterial + '</td>' +
                                        '</tr>');
                                        
                                    $('#inventorymaterial').val('').trigger('change');
                                    $('#inventorybatchno').val('');
                                    $('#inventoryuom').val('');
                                    $('#inventoryreference').val('');
                                    $('#inventorydescription').val('');
                                    $('#inventoryqtyonhand').val('0');    
                                    $('#inventoryqtyin').val('0');
                                    $('#inventoryqtyout').val('0');
                                    $('#inventoryunitcost').val('0');
                                    $('#batchTransID').val(obj.batchtransmainID);
                                    $('#batchTransBatchNo').val(obj.batchno);
                                    $('#batchTransMaster').val(obj.masterID);
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
        $('#batchtransinventorytable tbody').on('click', 'td', async function() {
            var r = await Otherconfirmation("You want to remove this record ? ");
    		if (r == true) {
                var row = $(this).closest('tr');
                var transID = row.find('td:nth-child(1)').text().trim();
                var batchID = $('#batchTransID').val();
                
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
                                batchtransID: batchID
                            },
                            url: '<?php echo base_url() ?>BatchTransaction/BatchTransactioninfostatus',
                            success: function (result) {
                                Swal.close();
                                var obj = JSON.parse(result);
                                if (obj.status == 1) {
                                    action(obj.action);
                                    row.closest('tr').remove();
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
        $('#btnsavebatchtrans').click(async function() {
            var r = await Otherconfirmation("You want to complete this ? ");
            if (r == true) {
                $('#btnsavebatchtrans').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i> Create Transaction');
                var tbody = $("#batchtransinventorytable tbody");

                if (tbody.children().length > 0) {
                    jsonObj = [];
                    $("#batchtransinventorytable tbody tr").each(function () {
                        item = {}
                        $(this).find('td').each(function (col_idx) {
                            item["col_" + (col_idx + 1)] = $(this).text();
                        });
                        jsonObj.push(item);
                    });
                    // console.log(jsonObj);
                    var tabledata = JSON.stringify(jsonObj);

                    var batchcategory = $('#batchTransCategoryID').val();
                    var batchtranstype = $('#batchTransTypeID').val();
                    var batchTransID = $('#batchTransID').val();

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
                                    recordID: batchTransID
                                },
                                url: 'BatchTransaction/BatchTransactioncomplete',
                                success: function (result) { //alert(result);
                                    // console.log(result);
                                    Swal.close();
                                    var obj = JSON.parse(result);
                                    if (obj.status == 1) {
                                        // $('#hidesegreset').click();
                                        $('#batchtransinventorytable> tbody').empty();
                                        $('#btnsavebatchtrans').prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Create Transaction');
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
                } else {
                    Swal.fire({
                        icon: 'warning',    
                        title: 'No Records',
                        text: 'Please add records to the table before saving.'
                    });
                    $('#btnsavebatchtrans').prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Create Transaction');
                }
            }
        });  
        $('#viewtransactioninfoModal').on('hidden.bs.modal', function (event) {
            $('#btnapprovereject').removeClass('d-none').prop('disabled', false);
        })         
        // Inventory Type End

        // Receipt Type Start
        $("#receiptcustomer").select2({
            dropdownParent: $('#batchCreateModal'),
            ajax: {
                url: "<?php echo base_url() ?>BatchTransaction/Getcustomerlist",
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
        $('#btnreceiptsubmit').click(function() {
            if (!$("#receiptform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmitreceipt").click();
            } else {
                var receiptcustomerID = $('#receiptcustomer').val();
                var receiptcustomer = $('#receiptcustomer').select2('data')[0].text; 
                var receipttranscode = $('#receipttranscode').val();
                var receiptinvoicedate = $('#receiptinvoicedate').val();
                var receiptinvoice = $('#receiptinvoice').val(); 
                var receiptcreditdebit = $('#receiptcreditdebit').val();
                var receiptinvoiceamount = $('#receiptinvoiceamount').val();
                var receiptnarration = $('#receiptnarration').val();

                if(receiptcreditdebit==1){
                    var creditamount = $('#receiptinvoiceamount').val();
                    var debitamount = '0';
                    var crdr = 'C';
                }
                else{
                    var creditamount = '0';
                    var debitamount = $('#receiptinvoiceamount').val();
                    var crdr = 'D';
                }

                var batchcategory = $('#batchTransCategoryID').val();
                var batchtranstype = $('#batchTransTypeID').val();
                var batchTransID = $('#batchTransID').val();
                var batchTransBatchNo = $('#batchTransBatchNo').val();
                var batchTransMaster = $('#batchTransMaster').val();

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
                                receiptcustomerID: receiptcustomerID,
                                receiptcustomer: receiptcustomer,
                                receipttranscode: receipttranscode,
                                receiptinvoicedate: receiptinvoicedate,
                                receiptinvoice: receiptinvoice,
                                receiptcreditdebit: receiptcreditdebit,
                                creditamount: creditamount,
                                debitamount: debitamount,
                                receiptnarration: receiptnarration,
                                batchTransID: batchTransID,
                                batchcategory: batchcategory,
                                batchtranstype: batchtranstype,
                                batchTransBatchNo: batchTransBatchNo,
                                batchTransMaster: batchTransMaster,
                                crdr: crdr
                            },
                            url: 'BatchTransaction/BatchTransactioninsertupdate',
                            success: function (result) {
                                Swal.close();
                                var obj = JSON.parse(result);
                                if (obj.status == 1) {
                                    $('#batchtransreceipttable tbody').append('<tr>' +
                                        '<td nowrap class="d-none">' + obj.batchtransID + '</td>' +
                                        '<td nowrap>' + receiptcustomer + '</td>' +
                                        '<td nowrap>' + receiptinvoicedate + '</td>' + 
                                        '<td nowrap>' + receipttranscode + '</td>' +
                                        '<td nowrap>' + receiptinvoice + '</td>' +
                                        '<td nowrap>' + receiptnarration + '</td>' +
                                        '<td nowrap class="text-center">' + crdr + '</td>' +
                                        '<td nowrap class="text-right">' + debitamount + '</td>' +
                                        '<td nowrap class="text-right">' + creditamount + '</td>' +
                                        '<td nowrap class="d-none">' + receiptcreditdebit + '</td>' +
                                        '<td nowrap class="d-none">' + receiptcustomerID + '</td>' +
                                        '</tr>');
                                        
                                    $('#receiptcustomer').val('').trigger('change');
                                    $('#receiptinvoicedate').val('');
                                    $('#receiptinvoice').val('');
                                    $('#receiptcreditdebit').val('');
                                    $('#receiptinvoiceamount').val('');
                                    $('#receiptnarration').val('');   
                                    $('#batchTransID').val(obj.batchtransmainID);
                                    $('#batchTransBatchNo').val(obj.batchno);
                                    $('#batchTransMaster').val(obj.masterID);
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
        $('#batchtransreceipttable tbody').on('click', 'td', async function() {
            var r = await Otherconfirmation("You want to remove this record ? ");
    		if (r == true) {
                var row = $(this).closest('tr');
                var transID = row.find('td:nth-child(1)').text().trim();
                var batchID = $('#batchTransID').val();
                
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
                                batchtransID: batchID
                            },
                            url: '<?php echo base_url() ?>BatchTransaction/BatchTransactioninfostatus',
                            success: function (result) {
                                Swal.close();
                                var obj = JSON.parse(result);
                                if (obj.status == 1) {
                                    action(obj.action);
                                    row.closest('tr').remove();
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
        $('#btnsavereceipttrans').click(async function() {
            var r = await Otherconfirmation("You want to complete this ? ");
            if (r == true) {
                $('#btnsavereceipttrans').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i> Create Transaction');
                var tbody = $("#batchtransreceipttable tbody");

                if (tbody.children().length > 0) {
                    jsonObj = [];
                    $("#batchtransreceipttable tbody tr").each(function () {
                        item = {}
                        $(this).find('td').each(function (col_idx) {
                            item["col_" + (col_idx + 1)] = $(this).text();
                        });
                        jsonObj.push(item);
                    });
                    // console.log(jsonObj);
                    var tabledata = JSON.stringify(jsonObj);

                    var batchcategory = $('#batchTransCategoryID').val();
                    var batchtranstype = $('#batchTransTypeID').val();
                    var batchTransID = $('#batchTransID').val();

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
                                    recordID: batchTransID
                                },
                                url: 'BatchTransaction/BatchTransactioncomplete',
                                success: function (result) { //alert(result);
                                    // console.log(result);
                                    Swal.close();
                                    var obj = JSON.parse(result);
                                    if (obj.status == 1) {
                                        // $('#hidesegreset').click();
                                        $('#batchtransreceipttable> tbody').empty();
                                        $('#btnsavereceipttrans').prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Create Transaction');
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
                } else {
                    Swal.fire({
                        icon: 'warning',    
                        title: 'No Records',
                        text: 'Please add records to the table before saving.'
                    });
                    $('#btnsavereceipttrans').prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Create Transaction');
                }
            }
        });  
        // Receipt Type End

        // Payment Type Start
        $("#paymentsupplier").select2({
            dropdownParent: $('#batchCreateModal'),
            ajax: {
                url: "<?php echo base_url() ?>BatchTransaction/Getsupplierlist",
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
        $('#btnpaymentsubmit').click(function() {
            if (!$("#paymentform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmitpayment").click();
            } else {
                var paymentsupplierID = $('#paymentsupplier').val();
                var paymentsupplier = $('#paymentsupplier').select2('data')[0].text; 
                var paymenttranscode = $('#paymenttranscode').val();
                var paymentinvoicedate = $('#paymentinvoicedate').val();
                var paymentinvoice = $('#paymentinvoice').val(); 
                var paymentcreditdebit = $('#paymentcreditdebit').val();
                var paymentinvoiceamount = $('#paymentinvoiceamount').val();
                var paymentnarration = $('#paymentnarration').val();

                if(paymentcreditdebit==1){
                    var creditamount = $('#paymentinvoiceamount').val();
                    var debitamount = '0';
                    var crdr = 'C';
                }
                else{
                    var creditamount = '0';
                    var debitamount = $('#paymentinvoiceamount').val();
                    var crdr = 'D';
                }

                var batchcategory = $('#batchTransCategoryID').val();
                var batchtranstype = $('#batchTransTypeID').val();
                var batchTransID = $('#batchTransID').val();
                var batchTransBatchNo = $('#batchTransBatchNo').val();
                var batchTransMaster = $('#batchTransMaster').val();

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
                                paymentsupplierID: paymentsupplierID,
                                paymentsupplier: paymentsupplier,
                                paymenttranscode: paymenttranscode,
                                paymentinvoicedate: paymentinvoicedate,
                                paymentinvoice: paymentinvoice,
                                paymentcreditdebit: paymentcreditdebit,
                                creditamount: creditamount,
                                debitamount: debitamount,
                                paymentnarration: paymentnarration,
                                batchTransID: batchTransID,
                                batchcategory: batchcategory,
                                batchtranstype: batchtranstype,
                                batchTransBatchNo: batchTransBatchNo,
                                batchTransMaster: batchTransMaster,
                                crdr: crdr
                            },
                            url: 'BatchTransaction/BatchTransactioninsertupdate',
                            success: function (result) {
                                Swal.close();
                                var obj = JSON.parse(result);
                                if (obj.status == 1) {
                                    $('#batchtranspaymenttable tbody').append('<tr>' +
                                        '<td nowrap class="d-none">' + obj.batchtransID + '</td>' +
                                        '<td nowrap>' + paymentsupplier + '</td>' +
                                        '<td nowrap>' + paymentinvoicedate + '</td>' + 
                                        '<td nowrap>' + paymenttranscode + '</td>' +
                                        '<td nowrap>' + paymentinvoice + '</td>' +
                                        '<td nowrap>' + paymentnarration + '</td>' +
                                        '<td nowrap class="text-center">' + crdr + '</td>' +
                                        '<td nowrap class="text-right">' + debitamount + '</td>' +
                                        '<td nowrap class="text-right">' + creditamount + '</td>' +
                                        '<td nowrap class="d-none">' + paymentcreditdebit + '</td>' +
                                        '<td nowrap class="d-none">' + paymentsupplierID + '</td>' +
                                        '</tr>');
                                        
                                    $('#paymentsupplier').val('').trigger('change');
                                    $('#paymentinvoicedate').val('');
                                    $('#paymentinvoice').val('');
                                    $('#paymentcreditdebit').val('');
                                    $('#paymentinvoiceamount').val('');
                                    $('#paymentnarration').val('');   
                                    $('#batchTransID').val(obj.batchtransmainID);
                                    $('#batchTransBatchNo').val(obj.batchno);
                                    $('#batchTransMaster').val(obj.masterID);
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
        $('#batchtranspaymenttable tbody').on('click', 'td', async function() {
            var r = await Otherconfirmation("You want to remove this record ? ");
    		if (r == true) {
                var row = $(this).closest('tr');
                var transID = row.find('td:nth-child(1)').text().trim();
                var batchID = $('#batchTransID').val();
                
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
                                batchtransID: batchID
                            },
                            url: '<?php echo base_url() ?>BatchTransaction/BatchTransactioninfostatus',
                            success: function (result) {
                                Swal.close();
                                var obj = JSON.parse(result);
                                if (obj.status == 1) {
                                    action(obj.action);
                                    row.closest('tr').remove();
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
        $('#btnsavepaymenttrans').click(async function() {
            var r = await Otherconfirmation("You want to complete this ? ");
            if (r == true) {
                $('#btnsavepaymenttrans').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i> Create Transaction');
                var tbody = $("#batchtranspaymenttable tbody");

                if (tbody.children().length > 0) {
                    jsonObj = [];
                    $("#batchtranspaymenttable tbody tr").each(function () {
                        item = {}
                        $(this).find('td').each(function (col_idx) {
                            item["col_" + (col_idx + 1)] = $(this).text();
                        });
                        jsonObj.push(item);
                    });
                    // console.log(jsonObj);
                    var tabledata = JSON.stringify(jsonObj);

                    var batchcategory = $('#batchTransCategoryID').val();
                    var batchtranstype = $('#batchTransTypeID').val();
                    var batchTransID = $('#batchTransID').val();

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
                                    recordID: batchTransID
                                },
                                url: 'BatchTransaction/BatchTransactioncomplete',
                                success: function (result) { //alert(result);
                                    // console.log(result);
                                    Swal.close();
                                    var obj = JSON.parse(result);
                                    if (obj.status == 1) {
                                        // $('#hidesegreset').click();
                                        $('#batchtranspaymenttable> tbody').empty();
                                        $('#btnsavepaymenttrans').prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Create Transaction');
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
                } else {
                    Swal.fire({
                        icon: 'warning',    
                        title: 'No Records',
                        text: 'Please add records to the table before saving.'
                    });
                    $('#btnsavepaymenttrans').prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Create Transaction');
                }
            }
        });  
        // Payment Type End
    });

    function approvetransaction (confirmnot) {
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
                        recordID: $('#batchtransapproveID').val(),
                        confirmnot: confirmnot
                    },
                    url: '<?php echo base_url() ?>BatchTransaction/BatchTransactionapprove',
                    success: function(result) {
                        Swal.close();
                        document.body.style.overflow = 'auto';
                        var obj = JSON.parse(result);
                        if(obj.status==1){
                            actionreload(obj.action);
                        }
                        else{
                            action(obj.action);
                        }
                    },
                    error: function(error) {
                        // Close the SweetAlert on error
                        Swal.close();
                        document.body.style.overflow = 'auto';
                        
                        // Show an error alert
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong. Please try again later.'
                        });
                    }
                });
            }
        });
    }
</script>
<?php include "include/footer.php"; ?>
