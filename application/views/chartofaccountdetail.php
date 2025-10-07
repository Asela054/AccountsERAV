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
                            <div class="page-header-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                            <span>Detail Account</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#viewmodalotherspecial"><i class="fas fa-cogs mr-2"></i>Other Option</button>
                                <hr>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <form action="<?php echo base_url() ?>Chartofaccountdetail/Chartofaccountdetailinsertupdate" method="post" autocomplete="off" id="formchartdetail">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Chart of Account*</label>
                                        <select name="chartofaccount" id="chartofaccount" class="form-control form-control-sm" required>
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Detail Account Code*</label>
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="htmlacccate">&nbsp;</span>
                                            </div>
                                            <input type="text" class="form-control input-integer" name="detailaccountcode" id="detailaccountcode" minlength="2" maxlength="2" required>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary-soft" type="button" id="btnrefresh"><i class="fas fa-sync-alt"></i></button>
                                            </div>
                                        </div>
                                        <!-- <p class="text-muted small"></p> -->
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Detail Account*</label>
                                        <input type="text" class="form-control form-control-sm" name="detailaccount" id="detailaccount" required>
                                    </div>
                                    <div class="form-group mt-3 text-right">
                                        <button type="button" id="submitBtn" class="btn btn-primary btn-sm px-4" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                    <input type="submit" class="d-none" id="hidesubmitbtn">
                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">
                                    <input type="hidden" name="accountcatecode" id="accountcatecode" value="">
                                </form>
                            </div>
                            <div class="col-9">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Chart Of Account</th>
                                                <th>Chart Of Account Name</th>
                                                <th>Code</th>
                                                <th>Detail Account No</th>
                                                <th>Detail Account Name</th>
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
<div class="modal fade" id="modaladdspecialcategory" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modaladdspecialcategoryLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modaladdspecialcategoryLabel">Special Account Type</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <form action="<?php echo base_url() ?>Chartofaccountdetail/Chartofaccountdetailspecialcateupdate" method="post">
                    <div class="form-group mb-1">
                        <label class="small font-weight-bold">Chart Of Account*</label>
                        <input type="text" class="form-control form-control-sm" name="chartaccountspecial" id="chartaccountspecial" readonly>
                        <input type="hidden" class="form-control form-control-sm" name="chartaccountspecialid" id="chartaccountspecialid">
                    </div>
                    <div class="form-group mb-1">
                        <label class="small font-weight-bold">Account Category*</label>
                        <select name="accountspecialcategory" id="accountspecialcategory" class="form-control form-control-sm" style="width: 100%;" required>
                            <option value="">Select</option>
                        </select>
                    </div>
                    <div class="form-group mt-3 text-right">
                        <button type="submit" id="submitspecialBtn" class="btn btn-primary btn-sm px-4" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Set Account</button>
                    </div>
                    <input type="hidden" name="typecategory" id="typecategory">
                </form>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="viewmodalotherspecial" data-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="viewmodalotherspecialLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header p-2">
				<h6 class="modal-title" id="viewmodalotherspecialLabel">View Special Category Accounts</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php //print_r($accountspecailcategorydata->result()) ?>
                <div class="alert alert-danger" role="alert">
                    Before clicking the remove button, please check the account transaction information. Because if you click the button, we can't recall your action.
                </div>
                <table class="table table-striped table-bordered table-sm small" id="datatablespeciallist">
                    <thead>
                        <tr>
                            <th>Account</th>
                            <th>Special Category</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($accountspecailcategorydata->result() as $rowdatalist){ ?>
                        <tr>
                            <td><?php echo $rowdatalist->accountno.' - '.$rowdatalist->accountname ?></td>
                            <td><?php echo $rowdatalist->special_item ?></td>
                            <td class="text-right">
                                <?php if($deletecheck==1){ ?>
                                <button type="button" data-url="Chartofaccountdetail/Chartofaccountdetailspecialcategorystatus/<?php echo $rowdatalist->idtbl_account_detail ?>/3" data-actiontype="3" class="btn btn-danger btn-sm text-light btntableaction"><i class="fas fa-trash-alt"></i></button>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
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

        $('#chartofaccount').select2({
            ajax: {
                url: "<?php echo base_url() ?>Chartofaccountdetail/Getchartaccountlist",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response.map(function (item) {
                            return {
                                id: item.id,
                                text: item.text,
                                data: {
                                    accno: item.accno
                                }
                            };
                        })
                    }
                },
                cache: true
            },
        });
        var setchartaccount = '<?php echo $this->session->flashdata('chartaccountoption'); ?>';
        if(setchartaccount!=''){
            $('#chartofaccount').empty().append(setchartaccount);
            var id = $('#chartofaccount').val();
            var code = $('#chartofaccount').find(':selected').attr("data-accno");
            $('#htmlacccate').html(code);
            $('#accountcatecode').val(code);
        }

        $('.input-integer').inputNumber({ allowLeadingZero: true, numericOnly: true });

        // $('#chartofaccount').change(function(){
        //     var id = $(this).val();
        //     var code = $(this).find(':selected').attr("data-accno");
        //     $('#htmlacccate').html(code);
        //     $('#accountcatecode').val(code);

        //     checknextaccountcode();
        // });
        $('#chartofaccount').on('select2:select', function (e) {
            var selectedData = e.params.data;
            var code = selectedData.data.accno;
            $('#htmlacccate').html(code);
            $('#accountcatecode').val(code);
            checknextaccountcode();
        });
        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "<?php echo base_url() ?>scripts/chartdetailaccountlist.php",
                type: "POST", // you can use GET
                data: function(d) {
                    d.userID = '<?php echo $_SESSION['userid']; ?>';
                }
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_account_detail"
                },
                {
                    "data": "chartaccountno"
                },
                {
                    "data": "chartaccountname"
                },
                {
                    "data": "code"
                },
                {
                    "data": "accountno"
                },
                {
                    "data": "accountname"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';

                        if(addcheck==1){
                            button+='<button class="btn btn-dark btn-sm btnSpecial mr-1" id="'+full['idtbl_account_detail']+'" data-account="'+full['accountno']+' - '+full['accountname']+'"><i class="fas fa-tasks"></i></button>';
                        }
                        if(editcheck==1){
                            button+='<button type="button" class="btn btn-primary btn-sm btnEdit mr-1" id="'+full['idtbl_account_detail']+'"><i class="fas fa-pen"></i></button>';
                        }
                        if(full['status']==1 && statuscheck==1){
                            button+='<button type="button" data-url="Chartofaccountdetail/Chartofaccountdetailstatus/'+full['idtbl_account_detail']+'/2" data-actiontype="2" class="btn btn-success btn-sm mr-1 btntableaction"><i class="fas fa-check"></i></button>';
                        }else if(full['status']==2 && statuscheck==1){
                            button+='<button type="button" data-url="Chartofaccountdetail/Chartofaccountdetailstatus/'+full['idtbl_account_detail']+'/1" data-actiontype="1" class="btn btn-warning btn-sm mr-1 text-light btntableaction"><i class="fas fa-times"></i></button>';
                        }
                        if(deletecheck==1){
                            button+='<button type="button" data-url="Chartofaccountdetail/Chartofaccountdetailstatus/'+full['idtbl_account_detail']+'/3" data-actiontype="3" class="btn btn-danger btn-sm text-light btntableaction"><i class="fas fa-trash-alt"></i></button>';
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
                    url: '<?php echo base_url() ?>Chartofaccountdetail/Chartofaccountdetailedit',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#detailaccountcode').val(obj.code);    
                        $('#detailaccount').val(obj.accountname);    
                        
                        var html;
                        html += '<option value="' + obj.chartaccountid + '" data-accno="' + obj.chartaccountno + '">';
                        html += obj.chartaccountname + ' - ' + obj.chartaccountno;
                        html += '</option>';

                        $('#chartofaccount').empty().append(html);
                                            
                        var code = $('#chartofaccount').find(':selected').attr("data-accno");
                        $('#htmlacccate').html(code);
                        $('#accountcatecode').val(code);

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });
        $('#submitBtn').click(function(){
            if (!$("#formchartdetail")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmitbtn").click();
            } else {
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
                                accountcode: $('#detailaccountcode').val(),
                                chartAccountID: $('#chartofaccount').val(),
                                recordID: $('#recordID').val(),
                                recordOption: $('#recordOption').val()
                            },
                            url: '<?php echo base_url() ?>Chartofaccountdetail/Checkaccountnoalready',
                            success: function(result) { //alert(result);
                                Swal.close();
                                var obj = JSON.parse(result);
                                if(obj.status==1){
                                    Swal.fire({text: obj.message});
                                }
                                else{$('#formchartdetail').submit();}
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
                    }
                });
                
            }
        });
        $('#btnrefresh').click(function(){
            checknextaccountcode();
        });

        $('#accountspecialcategory').select2({
            dropdownParent: $('#modaladdspecialcategory'),
            ajax: {
                url: "<?php echo base_url() ?>Chartofaccountdetail/getSpecialCateDetailAccount",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response.map(function (item) {
                            return {
                                id: item.id,
                                text: item.text,
                                data: {
                                    catetype: item.catetype
                                }
                            };
                        })
                    }
                },
                cache: true
            },
        });
        $('#accountspecialcategory').change(function(){
            var selectedData = $(this).select2('data')[0];
            var catetype = selectedData ? selectedData.data.catetype : null;
            $('#typecategory').val(catetype);
        });
        $('#dataTable tbody').on('click', '.btnSpecial', function() {
            $('#chartaccountspecialid').val($(this).attr("id"));
            $('#chartaccountspecial').val($(this).data("account"));            
            $('#modaladdspecialcategory').modal('show');
        });
    });

    function checknextaccountcode(){
        var id = $('#chartofaccount').val();
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
                    url: '<?php echo base_url() ?>Chartofaccountdetail/Getnextdetailaccountno',
                    success: function(result) { //alert(result);
                        $('#detailaccountcode').val(result);
                        Swal.close();
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
</script>
<?php include "include/footer.php"; ?>
