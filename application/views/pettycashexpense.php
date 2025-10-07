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
                            <span>Petty Cash Expenses</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <?php if($addcheck==1){ ?>
                            <div class="col-12 text-right">
                                <button class="btn btn-primary btn-sm px-4" id="btncreateexpenses" data-toggle="modal" data-target="#modalcompanychoose"><i class="fas fa-plus mr-2"></i>Create Expenses</button>
                                <hr>
                            </div>
                            <?php } ?>
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
<div class="modal fade" id="modalpettycash" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalpettycashLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modalpettycashLabel">Create Petty Cash Expenses</h6>
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
                                    <label class="small font-weight-bold">Opening Balance*</label>
                                    <input type="text" name="openingbal" id="openingbal" class="form-control form-control-sm text-right" readonly>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Pending Post*</label>
                                    <input type="text" name="nonpostbal" id="nonpostbal" class="form-control form-control-sm text-right" readonly>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Close Amount*</label>
                                    <input type="text" name="closebal" id="closebal" class="form-control form-control-sm text-right" readonly>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="small title-style"><span>Expenses Account</span></h6>
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
                                    <input type="text" name="narration" id="narration" class="form-control form-control-sm">
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold">Amount*</label>
                                    <input type="text" name="expenseamount" id="expenseamount" class="form-control form-control-sm input-integer" required>
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
                        <h6 class="small title-style"><span>Expenses Information</span></h6>
                        <table class="table table-striped table-bordered table-sm small" id="tablepettycashexpenses">
                            <thead>
                                <tr>
                                    <th class="d-none">Chart of detail account ID</th>
                                    <th>Account</th>
                                    <th>Narration</th>
                                    <th class="text-right">Amount</th>
                                    <th class="text-right">&nbsp;</th>
                                    <th class="d-none">Account Type</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <hr class="border-dark">
                    </div>
                    <?php if($addcheck==1){ ?>
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary btn-sm px-4" id="btnfullexpenses"><i class="fas fa-save mr-2"></i>Complete</button>
                    </div>
                    <?php } ?>
                </div>
			</div>
		</div>
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
                            <label class="small font-weight-bold">Petty Cash Account No*</label>
                            <select name="pettycashacccount" id="pettycashacccount" class="form-control form-control-sm" style="width: 100%;" required>
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
                        <hr class="mt-0">
                        <button type="button" class="btn btn-danger btn-sm px-4 d-none" id="btnposttransaction" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-exchange-alt mr-2"></i>Post Petty Cash</button>
                    </div>
                </div>
                <input type="hidden" name="pettycashid" id="pettycashid">
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

        $('.input-integer').inputNumber({
			maxDecimalDigits: 4
		});

        $('#pettycashacccount').select2({dropdownParent: $('#modalcompanychoose')});
        // $('#chartofdetailaccount').select2({dropdownParent: $('#modalpettycash')});

        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "<?php echo base_url() ?>scripts/pettycashexpenselist.php",
                type: "POST", // you can use GET
                data: function(d) {
                    d.userID = '<?php echo $_SESSION['userid']; ?>';
                }
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_pettycash"
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
                    "data": "pettycashcode"
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
                        button+='<button class="btn btn-dark btn-sm btnview mr-1" id="'+full['idtbl_pettycash']+'" data-toggle="tooltip" data-placement="bottom" title="View and post" data-poststatus="'+full['poststatus']+'" data-recordstatus="'+full['status']+'">';
                        if(full['poststatus']==0){
                            button+='<i class="fas fa-exchange-alt"></i>';
                        }
                        else{
                            button+='<i class="fas fa-eye"></i>';
                        }
                        if(full['poststatus']==1){
                            button+='<button class="btn btn-orange btn-sm btnPrint mr-1" id="'+full['idtbl_pettycash']+'"><i class="fas fa-print"></i></button>';
                        }
                        button+='</button>';
                        if(full['poststatus']==0){
                            if(full['status']==1 && statuscheck==1){
                                button+='<button type="button" data-url="Pettycashexpense/Pettycashexpensestatus/'+full['idtbl_pettycash']+'/2" data-actiontype="2" class="btn btn-success btn-sm mr-1 btntableaction"><i class="fas fa-check"></i></button>';
                            }else if(full['status']==2 && statuscheck==1){
                                button+='<button type="button" data-url="Pettycashexpense/Pettycashexpensestatus/'+full['idtbl_pettycash']+'/1" data-actiontype="1" class="btn btn-warning btn-sm mr-1 text-light btntableaction"><i class="fas fa-times"></i></button>';
                            }
                            if(deletecheck==1){
                                button+='<button type="button" data-url="Pettycashexpense/Pettycashexpensestatus/'+full['idtbl_pettycash']+'/3" data-actiontype="3" class="btn btn-danger btn-sm text-light btntableaction"><i class="fas fa-trash-alt"></i></button>';
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
        $('#dataTable tbody').on('click', '.btnview', function() {
            var id = $(this).attr('id');
        
            var recordstatus = $(this).attr("data-recordstatus");
            if(recordstatus==1){
                // $('#btnposttransaction').removeClass('d-none');
                var poststatus = $(this).attr("data-poststatus");
                if(poststatus==1){
                    $('#btnposttransaction').prop('disabled', true).addClass('d-none');
                }
                else{
                    $('#btnposttransaction').prop('disabled', false).removeClass('d-none');
                }
            }
            else{
                $('#btnposttransaction').addClass('d-none');
            }

            $('#pettycashid').val(id);

            $('#modalviewpost').modal('show');
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: '<?php echo base_url() ?>Pettycashexpense/Getviewpostinfo',
                success: function(result) { //alert(result);
                    // var obj = JSON.parse(result);
                    $('#viewdiv').html(result);
                }
            });
        });
        $('#dataTable tbody').on('click', '.btnPrint', function() {
            var id = $(this).attr('id');
            window.open("<?php echo base_url() ?>Reportprint/PettycashVoucher/"+id, "_blank");
        });

        getbranchlist('<?php echo $_SESSION['companyid'] ?>', '');
        getaccountlist('<?php echo $_SESSION['companyid'] ?>', '<?php echo $_SESSION['branchid'] ?>');
        getdetailaccountlist('<?php echo $_SESSION['companyid'] ?>', '<?php echo $_SESSION['branchid'] ?>');
        // $('#company').change(function(){
        //     var id = $(this).val();
        //     getbranchlist(id, '');
        // });
        // $('#branch').change(function(){
        //     var companyid = $('#company').val();
        //     var branchid = $(this).val();

        //     getaccountlist(companyid, branchid);
        //     getdetailaccountlist(companyid, branchid);
        // });

        $('#pettycashacccount').change(function(){
            var companyid = $('#company').val();
            var branchid = $('#branch').val();
            var accountid = $(this).val();

            getaccountbalance(companyid, branchid, accountid);
        });
        $('#btnchoosecominfo').click(function(){
            if (!$("#choosecompanyinfoform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidecomchoosesubmit").click();
            } else {
                $('#showcompany').val($("#company option:selected").text());
                $('#showbranch').val($("#branch option:selected").text());
                $('#showaccount').val($("#pettycashacccount option:selected").text());

                $('#modalcompanychoose').modal('hide');
                $('#modalpettycash').modal('show');
            }
        });     
        $('#modalpettycash').on('hidden.bs.modal', function (event) {
            window.location.reload();
        });  
        
        $('#btnaddtolist').click(function(){
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
                var narration = $('#narration').val();
                var expenseamount = $('#expenseamount').val();

                $('#tablepettycashexpenses> tbody:last').append('<tr><td class="d-none">' + chartdetailaccountID + '</td><td>' + chartdetailaccount + '</td><td>' + narration + '</td><td class="expamount text-right">' + expenseamount + '</td><td class="text-right"><button type="button" class="btn btn-danger btn-sm btnremoverow"><i class="fas fa-times"></i></button></td><td class="d-none">' + accounttype + '</td></tr>');
                $('#chartofdetailaccount').val('').trigger('change');
                $('#narration').val('');
                $('#expenseamount').val('');

                checksegregationcomplete();
            }
        });
        $('#tablepettycashexpenses tbody').on('click', '.btnremoverow', async function() {
            var r = await Otherconfirmation("You want to remove this expense ? ");
    		if (r == true) {
    			$(this).closest('tr').remove();
                checksegregationcomplete();
    		}
    	});
        $('#btnfullexpenses').click(function(){
    		var tbody = $("#tablepettycashexpenses tbody");

            if (tbody.children().length > 0) {
                jsonObj = [];
    			$("#tablepettycashexpenses tbody tr").each(function () {
    				item = {}
    				$(this).find('td').each(function (col_idx) {
    					item["col_" + (col_idx + 1)] = $(this).text();
    				});
    				jsonObj.push(item);
    			});

                // console.log(jsonObj);                

                var recordID = $('#recordID').val();
                var recordOption = $('#recordOption').val();
                var company = $('#company').val();
                var branch = $('#branch').val();
                var pettycashacccount = $('#pettycashacccount').val();

                var sum = 0;
                $(".expamount").each(function () {
                    var textvalue = $(this).text();
                    var textWithoutCommas = textvalue.replace(/,/g, '');
                    sum += parseFloat(textWithoutCommas);
                });
                
                var inputText = $('#closebal').val();
                var stringWithoutCommas = inputText.replace(/,/g, '');
                var closebal = parseFloat(stringWithoutCommas);
                
                if(sum<=closebal){
                    $('#btnfullexpenses').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i> Complete');
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
                                    pettycashacccount: pettycashacccount,
                                    recordOption: recordOption,
                                    recordID: recordID
                                },
                                url: 'Pettycashexpense/Pettycashexpenseinsertupdate',
                                success: function (result) { //alert(result);
                                    // console.log(result);
                                    Swal.close();
                                    var obj = JSON.parse(result);
                                    if (obj.status == 1) {
                                        // $('#hidesegreset').click();
                                        $('#tablepettycashexpenses> tbody').empty();
                                        $('#btnaddtolist').prop('disabled', false);
                                        $('#btnfullexpenses').prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Complete');
                                        getaccountbalance(company, branch, pettycashacccount);

                                        if(recordOption==2){
                                            actionreload(obj.action);
                                        }
                                        else{
                                            action(obj.action);
                                        }
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
                        text: 'Closing balance not enough for issue this petty cash payment.'
                    });
                }
            }
        }); 

        $('#btnposttransaction').click(async function() {
            var r = await Otherconfirmation("You want to post this transaction ? ");
            if (r == true) {
                $('#btnposttransaction').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i> Post Transaction');
                var pettycashid = $('#pettycashid').val();

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
                                recordID: pettycashid
                            },
                            url: 'Pettycashexpense/Pettycashexpenseposting',
                            success: function (result) { //alert(result);
                                // console.log(result);
                                Swal.close();
                                var obj = JSON.parse(result);
                                if (obj.status == 1) {
                                    setTimeout( function(){ 
                                        $('#modalviewpost').modal('hide');
                                        $('#dataTable').DataTable().ajax.reload( null, false );
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
        $.ajax({
            type: "POST",
            data: {
                companyid: companyid,
                branchid: branchid
            },
            url: '<?php echo base_url() ?>Pettycashexpense/Getaccountlist',
            success: function(result) { //alert(result);
                var obj = JSON.parse(result);
                var html = '';
                html += '<option value="">Select</option>';
                $.each(obj, function (i, item) {
                    html += '<option value="' + obj[i].idtbl_account + '">';
                    html += obj[i].accountname+' - '+obj[i].accountno ;
                    html += '</option>';
                });
                $('#pettycashacccount').empty().append(html);   
            }
        });
    }

    function getaccountbalance(companyid, branchid, accountid){
        $.ajax({
            type: "POST",
            data: {
                companyid: companyid,
                branchid: branchid,
                accountid: accountid
            },
            url: '<?php echo base_url() ?>Pettycashexpense/Getaccountbalance',
            success: function(result) { //alert(result);
                var obj = JSON.parse(result);
                
                $('#openingbal').val(obj.openbal);   
                $('#nonpostbal').val(obj.nopostbal);   
                $('#closebal').val(obj.closebal);  
                var inputText = obj.closebal;
                var stringWithoutCommas = inputText.replace(/,/g, '');
                $('#expenseamount').attr('max', stringWithoutCommas); 
            }
        });
    }

    function getdetailaccountlist(companyid, branchid){
        // $.ajax({
        //     type: "POST",
        //     data: {
        //         companyid: companyid,
        //         branchid: branchid
        //     },
        //     url: '<?php echo base_url() ?>Pettycashexpense/Getdetailaccountlist',
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
            dropdownParent: $('#modalpettycash'),
            ajax: {
                url: "<?php echo base_url() ?>Pettycashexpense/Getdetailaccountlist",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term, // search term
                        companyid: companyid,
                        branchid: branchid,
                        accountcategory: '2'
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
            url: '<?php echo base_url() ?>Pettycashexpense/Getbranchaccocompany',
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
    
    function checksegregationcomplete(){
        var sum = 0;
    	$(".expamount").each(function () {
            var textvalue = $(this).text();
            var textWithoutCommas = textvalue.replace(/,/g, '');
    		sum += parseFloat(textWithoutCommas);
    	});
        
        var inputText = $('#closebal').val();
        var stringWithoutCommas = inputText.replace(/,/g, '');
        var closebal = parseFloat(stringWithoutCommas);
        
        if(sum<=closebal){
            var balance = closebal-sum;
            $('#expenseamount').attr('max', balance);
            $('#btnaddtolist').prop('disabled', false);
            $('#btnfullexpenses').prop('disabled', false);
            $('#chartofdetailaccount').focus();
        }
        else{
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Closing balance not enough for issue this petty cash payment.'
            });
            $('#btnfullexpenses').prop('disabled', true);
        }
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
