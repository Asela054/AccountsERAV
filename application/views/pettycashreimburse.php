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
                            <div class="page-header-icon"><i class="fas fa-coins"></i></div>
                            <span>Petty Cash Reimburse</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12 text-right">
                                <?php if($addcheck==1){ ?>
                                <button class="btn btn-primary btn-sm px-4" id="btncreateexpenses" data-toggle="modal" data-target="#modalcompanychoose"><i class="fas fa-plus mr-2"></i>Create Reimbursement</button>
                                <hr>
                                <?php } ?>
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
                                                <th>Date</th>
                                                <th>Account No</th>
                                                <th>Cheque No</th>
                                                <th>Cheque Date</th>
                                                <th>Open Bal</th>
                                                <th>Reimburse Bal</th>
                                                <th>Close Bal</th>
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
                        <div class="col-12">
                            <label class="small font-weight-bold">Bank Account No*</label>
                            <select name="bankaccount" id="bankaccount" class="form-control form-control-sm" style="width: 100%;" required>
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <?php if($addcheck==1){ ?>
                    <div class="row mt-3">
                        <div class="col-12 text-right">
                            <button type="button" class="btn btn-primary btn-sm px-4" id="btnchoosecominfo"><i class="fas fa-check mr-2"></i>Submit</button>
                            <input type="submit" class="d-none" id="hidecomchoosesubmit">
                        </div>
                    </div>
                    <?php } ?>
                </form>
			</div>
		</div>
	</div>
</div>
<!-- Modal Reimbursement -->
<div class="modal fade" id="modalpettycash" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalpettycashLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modalpettycashLabel">Create Petty Cash Reimbursement</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form id="segregationform">
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
                                    <label class="small font-weight-bold">Account*</label>
                                    <input type="text" name="showaccount" id="showaccount" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label class="small font-weight-bold">Narration</label>
                                    <input type="text" name="narration" id="narration" class="form-control form-control-sm">
                                </div>
                                <div class="col-3">
                                    <label class="small font-weight-bold">Reimburse Balance</label>
                                    <input type="text" name="reimbursebal" id="reimbursebal" class="form-control form-control-sm" value="0.00" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="small title-style mt-3"><span>Posted Petty Cash Information</span></h6>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="custom-control custom-checkbox ml-2 mb-2">
                                                <input type="checkbox" class="custom-control-input checkallocate" id="selectAll">
                                                <label class="custom-control-label" for="selectAll">Select All Records</label>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table table-striped table-bordered table-sm small" id="tablepostpettycash">
                                        <thead>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>pettycashID</th>
                                                <th>Date</th>
                                                <th>Chart of detail account</th>
                                                <th>Narration</th>
                                                <th class="text-right">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                    <hr class="border-dark">
                                </div>
                                <?php if($addcheck==1){ ?>
                                <div class="col-12 text-right">
                                    <button type="button" class="btn btn-primary btn-sm px-4" id="btnReimbursement"><i class="fas fa-save mr-2"></i>Create Reimbursement</button>
                                </div>
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
<!-- Modal View Reimbursement -->
<div class="modal fade" id="modalviewreimburse" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalviewreimburseLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modalviewreimburseLabel">View Petty Cash Reimbursement</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div id="viewreimburse"></div>
                        <input type="hidden" id="hidereimburseid">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-right">
                        <hr>
                        <button type="button" class="btn btn-primary btn-sm px-3" id="btnapprovereject"><i class="fas fa-check mr-2"></i>Approve or reject</button>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
<!-- Modal View Reimbursement -->
<div class="modal fade" id="modalcreatecheque" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalcreatechequeLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modalcreatechequeLabel">Print Reimbursement Cheque</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <form id="formchequeissue" method="post">
                    <div class="form-group mb-1">
                        <label class="small font-weight-bold">Cheque Date*</label>
                        <input type="date" name="chequedate" id="chequedate" class="form-control form-control-sm" required>
                    </div>
                    <div class="form-group mb-1">
                        <label class="small font-weight-bold">Narration*</label>
                        <textarea name="chequedesc" id="chequedesc" class="form-control form-control-sm" required></textarea>
                    </div>
                    <?php if($addcheck==1){ ?>
                    <div class="form-group mt-3 text-right">
                        <button type="button" id="btnchequecreate" class="btn btn-primary btn-sm px-2"><i class="fas fa-print mr-2"></i>Create & Print</button>
                        <input type="submit" class="d-none" id="hidesubmitchequecreate">
                    </div>
                    <?php } ?>
                    <input type="hidden" name="hidechequereimburseid" id="hidechequereimburseid">
                </form>
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

        // $('#bankaccount').select2({dropdownParent: $('#modalcompanychoose')});

        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "<?php echo base_url() ?>scripts/pettycashreimburselist.php",
                type: "POST", // you can use GET
                data: function(d) {
                    d.userID = '<?php echo $_SESSION['userid']; ?>';
                }
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_pettycash_reimburse"
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
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        if(full['chequeno']!=null){
                            return full['accountno'];
                        }
                        else{
                            return '';
                        }
                    }
                },    
                {
                    "data": "chequeno"
                },
                {
                    "data": "chequedate"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        return addCommas(parseFloat(full['openbal']).toFixed(2));
                    }
                },    
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        return addCommas(parseFloat(full['reimursebal']).toFixed(2));
                    }
                },    
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        return addCommas(parseFloat(full['closebal']).toFixed(2));
                    }
                }, 
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        // button+='<button class="btn btn-primary btn-sm btnEdit mr-1 ';if(editcheck!=1){button+='d-none';}button+='" id="'+full['idtbl_pettycash_reimburse']+'"><i class="fas fa-pen"></i></button>';
                        if(full['approvestatus']==0){
                            button+='<button class="btn btn-warning btn-sm btnApprove mr-1 ';if(statuscheck!=1){button+='d-none';}button+='" id="'+full['idtbl_pettycash_reimburse']+'"><i class="fas fa-redo"></i></button>';
                            // if(full['status']==1 && statuscheck==1){
                            //     button+='<button type="button" data-url="Accounttype/Accounttypestatus/'+full['idtbl_pettycash_reimburse']+'/2" data-actiontype="2" class="btn btn-success btn-sm mr-1 btntableaction"><i class="fas fa-check"></i></button>';
                            // }else if(full['status']==2 && statuscheck==1){
                            //     button+='<button type="button" data-url="Accounttype/Accounttypestatus/'+full['idtbl_pettycash_reimburse']+'/1" data-actiontype="1" class="btn btn-warning btn-sm mr-1 text-light btntableaction"><i class="fas fa-times"></i></button>';
                            // }
                            // if(deletecheck==1){
                            //     button+='<button type="button" data-url="Accounttype/Accounttypestatus/'+full['idtbl_pettycash_reimburse']+'/3" data-actiontype="3" class="btn btn-danger btn-sm text-light btntableaction"><i class="fas fa-trash-alt"></i></button>';
                            // }
                        }
                        if(full['approvestatus']==1){
                            button+='<button class="btn btn-dark btn-sm btnView mr-1" id="'+full['idtbl_pettycash_reimburse']+'"><i class="fas fa-eye"></i></button>';
                            if(full['chequecreate']==0){
                                button+='<button class="btn btn-primary btn-sm btnIssueCheque mr-1" id="'+full['idtbl_pettycash_reimburse']+'"><i class="fas fa-money-check"></i></button>';
                            }
                            else{
                                button+='<button class="btn btn-primary btn-sm mr-1 btnprint" id="'+full['idtbl_pettycash_reimburse']+'"><i class="fas fa-print"></i></button>';
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
                if ( data['approvestatus'] == 1 ) {
                    if(data['chequecreate']==0){
                        $(row).addClass('table-primary');
                    }
                    else{
                        $(row).addClass('table-success');
                    }
                }  
                else if ( data['approvestatus'] == 2 ) {
                    $(row).addClass('table-danger');
                }         
            }
        });
        $('#dataTable tbody').on('click', '.btnApprove', function() {
            var id = $(this).attr('id');
            $('#hidereimburseid').val(id);
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: '<?php echo base_url() ?>Pettycashreimburse/Getreimbursementinfo',
                success: function(result) { //alert(result);
                    $('#viewreimburse').html(result);
                    $('#modalviewreimburse').modal('show');
                    $('#btnreject').removeClass('d-none');
                    $('#btnapprove').removeClass('d-none');
                    $('#modalviewreimburse .modal-footer').removeClass('d-none');
                }
            });
        });
        $('#dataTable tbody').on('click', '.btnView', function() {
            var id = $(this).attr('id');
            $('#hidereimburseid').val(id);
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: '<?php echo base_url() ?>Pettycashreimburse/Getreimbursementinfo',
                success: function(result) { //alert(result);
                    $('#viewreimburse').html(result);
                    $('#modalviewreimburse').modal('show');
                    $('#btnapprovereject').prop('disabled', true).addClass('d-none');
                }
            });
        });
        $('#dataTable tbody').on('click', '.btnIssueCheque', function() {
            var id = $(this).attr('id');
            $('#hidechequereimburseid').val(id);
            $('#modalcreatecheque').modal('show');
        });
        $('#dataTable tbody').on('click', '.btnprint', function() {
            var id = $(this).attr('id');
            window.open("<?php echo base_url() ?>Reportprint/PettyCashReibursePrint/"+id, "_blank");
        });

        getbranchlist('<?php echo $_SESSION['companyid'] ?>', '');
        getaccountlist('<?php echo $_SESSION['companyid'] ?>', '<?php echo $_SESSION['branchid'] ?>');
        getpostpettycash('<?php echo $_SESSION['companyid'] ?>', '<?php echo $_SESSION['branchid'] ?>');
        // $('#company').change(function(){
        //     var id = $(this).val();
        //     getbranchlist(id, '');
        // });
        // $('#branch').change(function(){
        //     var companyid = $('#company').val();
        //     var branchid = $(this).val();

        //     getaccountlist(companyid, branchid);
        //     getpostpettycash(companyid, branchid);
        // });
        $('#btnchoosecominfo').click(function(){
            if (!$("#choosecompanyinfoform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidecomchoosesubmit").click();
            } else {
                $('#showcompany').val($("#company option:selected").text());
                $('#showbranch').val($("#branch option:selected").text());
                $('#showaccount').val($("#bankaccount option:selected").text());

                $('#modalcompanychoose').modal('hide');
                $('#modalpettycash').modal('show');
            }
        }); 
        $('#selectAll').click(function (e) {
            $('#tablepostpettycash').closest('table').find('td input:checkbox').prop('checked', this.checked);
            getnetreimbursebal();
        });
        $('#tablepostpettycash').on('click', 'input[type="checkbox"]', function () {
            getnetreimbursebal();
        });
        $('#btnReimbursement').click(function(){
            var tablelist = $("#tablepostpettycash tbody input[type=checkbox]:checked");
            
            if(tablelist.length>0){
                $('#btnReimbursement').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i>Create Reimbursement');;
                jsonObj = [];
                tablelist.each(function() {
                    item = {}
                    var row = $(this).closest("tr");
                    item["pettycashid"] = row.find('td:eq(1)').text();
                    jsonObj.push(item);
                });
                var myJSON = JSON.stringify(jsonObj);
                // console.log(myJSON);

                var companyid = $('#company').val();
                var branchid = $('#branch').val();
                var bankaccount = $('#bankaccount').val();
                var reimbursebal = $('#reimbursebal').val();

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
                                companyid: companyid,
                                branchid: branchid,
                                bankaccount: bankaccount,
                                reimbursebal: reimbursebal,
                                tabledata: myJSON
                            },
                            url: '<?php echo base_url() ?>Pettycashreimburse/Pettycashreimburseinsertupdate',
                            success: function(result) { //alert(result);
                                // console.log(result);
                                Swal.close();
                                var obj = JSON.parse(result);

                                if(obj.status==1){
                                    setTimeout(function(){
                                        window.location.reload();
                                    }, 3000);
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
            else{
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Kindly verify the allocation of one or more account numbers.'
                });
            }
        });

        $('#btnapprovereject').click(function(){
            Swal.fire({
                title: "Do you want to approve this reimbursement?",
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

        $('#btnchequecreate').click(function(){
            if (!$("#formchequeissue")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmitchequecreate").click();
            } else {
                var chequedate = $('#chequedate').val();
                var chequedesc = $('#chequedesc').val();
                var hidechequereimburseid = $('#hidechequereimburseid').val();

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
                                chequedate: chequedate,
                                chequedesc: chequedesc,
                                hidechequereimburseid: hidechequereimburseid
                            },
                            url: '<?php echo base_url() ?>Pettycashreimburse/Pettycashreimbursechequecreate',
                            success: function(result) { //alert(result);
                                // console.log(result);
                                Swal.close();
                                var obj = JSON.parse(result);

                                if(obj.status==1){
                                    setTimeout(function(){
                                        window.location.reload();
                                    }, 3000);
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
    });

    function getbranchlist(id, value){
        $.ajax({
            type: "POST",
            data: {
                recordID: id
            },
            url: '<?php echo base_url() ?>Pettycashreimburse/Getbranchaccocompany',
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

                if(value!=''){
                    $('#branch').val(value);
                }
            }
        });
    }

    function getaccountlist(companyid, branchid){
        // $.ajax({
        //     type: "POST",
        //     data: {
        //         companyid: companyid,
        //         branchid: branchid
        //     },
        //     url: '<?php echo base_url() ?>Pettycashreimburse/Getaccountlist',
        //     success: function(result) { //alert(result);
        //         var obj = JSON.parse(result);
        //         var html = '';
        //         html += '<option value="">Select</option>';
        //         $.each(obj, function (i, item) {
        //             html += '<option value="' + obj[i].idtbl_account + '">';
        //             html += obj[i].accountname+' - '+obj[i].accountno ;
        //             html += '</option>';
        //         });
        //         $('#bankaccount').empty().append(html);   
        //     }
        // });
        $("#bankaccount").select2({
            dropdownParent: $('#modalcompanychoose'),
            ajax: {
                url: "<?php echo base_url() ?>Pettycashreimburse/Getaccountlist",
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
                                text: item.text
                            };
                        })
                    }
                },
                cache: true
            },
        });
    }

    function getpostpettycash(companyid, branchid){
        $.ajax({
            type: "POST",
            data: {
                companyid: companyid,
                branchid: branchid
            },
            url: '<?php echo base_url() ?>Pettycashreimburse/Getpostpettycashlist',
            success: function(result) { //alert(result);
                $('#tablepostpettycash tbody').empty().append(result);
            }
        });
    }

    function getnetreimbursebal(){
        var intVal = function (i) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '') * 1 :
                typeof i === 'number' ?
                i : 0;
        };

        var tablelist = $("#tablepostpettycash tbody input[type=checkbox]:checked");
        var sum = 0;
        tablelist.each(function() {
            var row = $(this).closest("tr");
            sum += parseFloat(intVal(row.find('td:eq(5)').text()));
        });
        $('#reimbursebal').val(addCommas(parseFloat(sum).toFixed(2)))
    }

    function approvetransaction(confirmnot){
        // $.ajax({
        //     type: "POST",
        //     data: {
        //         recordID: id,
        //         type: type
        //     },
        //     url: '<?php // echo base_url() ?>Pettycashreimburse/Approvereimbursement',
        //     success: function(result) { //alert(result);
        //         $('#modalviewreimburse').modal('hide');
        //         $('#dataTable').DataTable().ajax.reload( null, false );
        //     }
        // });

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
                        recordID: $('#hidereimburseid').val(),
                        type: confirmnot
                    },
                    url: '<?php echo base_url() ?>Pettycashreimburse/Approvereimbursement',
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