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
                            <span>Chart Of Account</span>
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
                                <form action="<?php echo base_url() ?>Chartofaccount/Chartofaccountinsertupdate" method="post" autocomplete="off" id="formchartofaccount">
                                    <!-- <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Account Type*</label>
                                        <select name="accounttype" id="accounttype" class="form-control form-control-sm" required>
                                            <option value="">Select</option>
                                            <?php // foreach($accounttype->result() as $rowaccounttype){ ?>
                                            <option value="<?php // echo $rowaccounttype->idtbl_account_type ?>"><?php // echo $rowaccounttype->accounttype  ?></option>
                                            <?php // } ?>
                                        </select>
                                    </div> -->
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Account Prime Category*</label>
                                        <select name="accountcategory" id="accountcategory" class="form-control form-control-sm" required>
                                            <option value="">Select</option>
                                            <?php foreach($accountcategory->result() as $rowaccountcategory){ ?>
                                            <option value="<?php echo $rowaccountcategory->idtbl_account_category ?>" data-code="<?php echo $rowaccountcategory->code  ?>"><?php echo $rowaccountcategory->category.' - '.$rowaccountcategory->code  ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Account Category*</label>
                                        <select name="subaccountcategory" id="subaccountcategory" class="form-control form-control-sm" required>
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Account Sub Category*</label>
                                        <select name="nestaccountcategory" id="nestaccountcategory" class="form-control form-control-sm" required>
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Chart Of Account Code*</label>
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="htmlacccate">&nbsp;</span>
                                            </div>
                                            <input type="text" class="form-control input-integer" name="chartaccountcode" id="chartaccountcode" minlength="4" maxlength="4" required>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary-soft" type="button" id="btnrefresh"><i class="fas fa-sync-alt"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Chart Of Account*</label>
                                        <input type="text" class="form-control form-control-sm" name="chartaccount" id="chartaccount" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Have a detailed accounts*</label><br>
                                        <div class="custom-control custom-radio custom-control-inline">
                                        	<input type="radio" id="detailaccount1" name="detailaccount" class="custom-control-input" value="1">
                                        	<label class="custom-control-label font-weight-bold" for="detailaccount1">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                        	<input type="radio" id="detailaccount2" name="detailaccount" class="custom-control-input" value="0" checked>
                                        	<label class="custom-control-label font-weight-bold" for="detailaccount2">No</label>
                                        </div>
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
                                                <!-- <th>Account Type</th> -->
                                                <th>Account Prime Category</th>
                                                <th>Account Category</th>
                                                <th>Account Sub Category</th>
                                                <th>Code</th>
                                                <th>Account No</th>
                                                <th>Account Name</th>
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
				<h6 class="modal-title" id="modaladdspecialcategoryLabel">Set Main Account Type</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <form action="<?php echo base_url() ?>Chartofaccount/Chartofaccountspecialcateupdate" method="post">
                    <div class="form-group mb-1">
                        <label class="small font-weight-bold">Chart Of Account*</label>
                        <input type="text" class="form-control form-control-sm" name="chartaccountspecial" id="chartaccountspecial" readonly>
                        <input type="hidden" class="form-control form-control-sm" name="chartaccountspecialid" id="chartaccountspecialid">
                    </div>
                    <div class="form-group mb-1">
                        <label class="small font-weight-bold">Account Category*</label>
                        <select name="accountspecialcategory" id="accountspecialcategory" class="form-control form-control-sm" required>
                            <option value="">Select</option>
                            <?php foreach($accountspecailcategory->result() as $rowaccountspecailcategory){ ?>
                            <option value="<?php echo $rowaccountspecailcategory->idtbl_account_special_category ?>"><?php echo $rowaccountspecailcategory->specialcategory  ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group mt-3 text-right">
                        <button type="submit" id="submitspecialBtn" class="btn btn-primary btn-sm px-4" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Set Account</button>
                    </div>
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
                            <td><?php echo $rowdatalist->specialcategory ?></td>
                            <td class="text-right">
                                <?php if($deletecheck==1){ ?>
                                <button type="button" data-url="Chartofaccount/Chartofaccountspecialcategorystatus/<?php echo $rowdatalist->idtbl_account ?>/3" data-actiontype="3" class="btn btn-danger btn-sm text-light btntableaction"><i class="fas fa-trash-alt"></i></button>
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
    // document.addEventListener('DOMContentLoaded', function() {
    //     var usernameInput = document.getElementById('subaccountcatecode');

    //     usernameInput.addEventListener('invalid', function() {
    //         if (this.validity.patternMismatch) {
    //             this.setCustomValidity('Code should not contain numbers or special characters.');
    //         } else {
    //             this.setCustomValidity('');
    //         }
    //     });

    //     // Reset custom validity when the input becomes valid
    //     usernameInput.addEventListener('input', function() {
    //         this.setCustomValidity('');
    //     });
    // });
    $(document).ready(function() {
        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        $('#datatablespeciallist').DataTable();

        $('.input-integer').inputNumber({ allowLeadingZero: true, numericOnly: true });

        $('#accountcategory').change(function(){
            var id = $(this).val();
            var code = $(this).find(':selected').attr("data-code");
            $('#htmlacccate').html(code);
            $('#accountcatecode').val(code);
            
            getsubcategory(id, '');
        });
        $('#subaccountcategory').change(function(){
            var id = $(this).val();            
            var category = $('#accountcategory').val();            
            getnestcategory(id, category, '');
        });
        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "<?php echo base_url() ?>scripts/chartaccountlist.php",
                type: "POST", // you can use GET
                data: function(d) {
                    d.userID = '<?php echo $_SESSION['userid']; ?>';
                }
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_account"
                },
                {
                    "data": "category"
                },
                {
                    "data": "subcategory"
                },
                {
                    "data": "nestcategory"
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
                            button+='<button class="btn btn-dark btn-sm btnSpecial mr-1" id="'+full['idtbl_account']+'" data-account="'+full['accountno']+' - '+full['accountname']+'"><i class="fas fa-tasks"></i></button>';
                        }
                        if(editcheck==1){
                            button+='<button type="button" class="btn btn-primary btn-sm btnEdit mr-1" id="'+full['idtbl_account']+'"><i class="fas fa-pen"></i></button>';
                        }
                        if(full['status']==1 && statuscheck==1){
                            button+='<button type="button" data-url="Chartofaccount/Chartofaccountstatus/'+full['idtbl_account']+'/2" data-actiontype="2" class="btn btn-success btn-sm mr-1 btntableaction"><i class="fas fa-check"></i></button>';
                        }else if(full['status']==2 && statuscheck==1){
                            button+='<button type="button" data-url="Chartofaccount/Chartofaccountstatus/'+full['idtbl_account']+'/1" data-actiontype="1" class="btn btn-warning btn-sm mr-1 text-light btntableaction"><i class="fas fa-times"></i></button>';
                        }
                        if(deletecheck==1){
                            button+='<button type="button" data-url="Chartofaccount/Chartofaccountstatus/'+full['idtbl_account']+'/3" data-actiontype="3" class="btn btn-danger btn-sm text-light btntableaction"><i class="fas fa-trash-alt"></i></button>';
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
                    url: '<?php echo base_url() ?>Chartofaccount/Chartofaccountedit',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#accounttype').val(obj.accounttype);                       
                        $('#accountcategory').val(obj.accountcategory);  
                        getsubcategory(obj.accountcategory, obj.accountsubcate);        
                        getnestcategory(obj.accountsubcate, obj.accountcategory, obj.accountnestcate);        
                        $('#chartaccountcode').val(obj.code);                     
                        $('#chartaccount').val(obj.accountname);   
                        
                        var code = $('#accountcategory').find(':selected').attr("data-code");
                        $('#htmlacccate').html(code);
                        $('#accountcatecode').val(code);

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });
        $('#dataTable tbody').on('click', '.btnSpecial', function() {
            $('#chartaccountspecialid').val($(this).attr("id"));
            $('#chartaccountspecial').val($(this).data("account"));            
            $('#modaladdspecialcategory').modal('show');
        });
        $('#submitBtn').click(function(){
            if (!$("#formchartofaccount")[0].checkValidity()) {
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
                                accountcode: $('#chartaccountcode').val(),
                                recordID: $('#recordID').val(),
                                recordOption: $('#recordOption').val()
                            },
                            url: '<?php echo base_url() ?>Chartofaccount/Checkaccountnoalready',
                            success: function(result) { //alert(result);
                                Swal.close();
                                var obj = JSON.parse(result);
                                if(obj.status==1){
                                    Swal.fire({text: obj.message});
                                }
                                else{$('#formchartofaccount').submit();}
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
    });

    function getsubcategory(id, value){
        $.ajax({
            type: "POST",
            data: {
                recordID: id
            },
            url: '<?php echo base_url() ?>Chartofaccount/Getsubcateaccoaccountcate',
            success: function(result) { //alert(result);
                var obj = JSON.parse(result);
                var html = '';
                html += '<option value="">Select</option>';
                $.each(obj, function (i, item) {
                    html += '<option value="' + obj[i].idtbl_account_subcategory + '">';
                    html += obj[i].subcategory ;
                    html += '</option>';
                });
                $('#subaccountcategory').empty().append(html);    
                
                if(value!=''){
                    $('#subaccountcategory').val(value);
                }
            }
        });

        if(value==''){
            checknextaccountcode();
        }
    }

    function getnestcategory(id, category, value){
        $.ajax({
            type: "POST",
            data: {
                recordID: id,
                category: category
            },
            url: '<?php echo base_url() ?>Chartofaccount/Getsnestcateaccoaccountsubcate',
            success: function(result) { //alert(result);
                var obj = JSON.parse(result);
                var html = '';
                html += '<option value="">Select</option>';
                $.each(obj, function (i, item) {
                    html += '<option value="' + obj[i].idtbl_account_nestcategory + '">';
                    html += obj[i].nestcategory ;
                    html += '</option>';
                });
                $('#nestaccountcategory').empty().append(html);    
                
                if(value!=''){
                    $('#nestaccountcategory').val(value);
                }
            }
        });
    }

    function checknextaccountcode(){
        var id = $('#accountcategory').val();
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
                    url: '<?php echo base_url() ?>Chartofaccount/Getnextaccountno',
                    success: function(result) { //alert(result);
                        $('#chartaccountcode').val(result);
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
