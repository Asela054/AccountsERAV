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
                            <span>Account Allocation</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form id="formallocation">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Company*</label>
                                        <select name="company" id="company" class="form-control form-control-sm" required>
                                            <!-- <option value="">Select</option> -->
                                            <?php foreach($companylist as $rowcompanylist){ ?>
                                            <option value="<?php echo $rowcompanylist->idtbl_company ?>"><?php echo $rowcompanylist->company ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Company Branch*</label>
                                        <select name="branch" id="branch" class="form-control form-control-sm" required>
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <!-- <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Account Type*</label>
                                        <select name="accounttype" id="accounttype" class="form-control form-control-sm" required>
                                            <option value="">Select</option>
                                            <?php // foreach($accounttype->result() as $rowaccounttype){ ?>
                                            <option value="<?php // echo $rowaccounttype->idtbl_account_type ?>"><?php // echo $rowaccounttype->accounttype ?></option>
                                            <?php // } ?>
                                        </select>
                                    </div> -->
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Account List*</label><br>
                                        <div class="custom-control custom-radio custom-control-inline">
                                        	<input type="radio" id="accountlistcate1" name="accountlistcate" class="custom-control-input" value="1" required>
                                        	<label class="custom-control-label small font-weight-bold" for="accountlistcate1">Chart of Account</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                        	<input type="radio" id="accountlistcate2" name="accountlistcate" class="custom-control-input" value="0">
                                        	<label class="custom-control-label small font-weight-bold" for="accountlistcate2">Detail Account</label>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3 text-right">
                                        <button type="button" id="submitBtn" class="btn btn-primary btn-sm px-4" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-search"></i>&nbsp;Search</button>
                                    </div>
                                    <input type="submit" id="hidebtnsubmit" class="d-none">
                                </form>
                            </div>
                            <div class="col-9">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="custom-control custom-checkbox ml-2 mb-2">
                                            <input type="checkbox" class="custom-control-input checkallocate" id="selectAll">
                                            <label class="custom-control-label" for="selectAll">Select All Records</label>
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-bordered table-striped table-sm nowrap" id="tableaccountlist">
                                    <thead>
                                        <tr>
                                            <th width="5%" class="text-center">#</th>
                                            <th class="d-none">AccountID</th>
                                            <th>Account No</th>
                                            <th>Account Name</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <hr>
                                <div class="row">
                                    <div class="col-12 text-right">
                                        <button type="button" id="fullsubmitBtn" class="btn btn-primary btn-sm px-4" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-save mr-2"></i>&nbsp;Allocate</button>
                                    </div>
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
<script>
    $(document).ready(function() {
        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        $('#submitBtn').click(function(){
            if (!$("#formallocation")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidebtnsubmit").click();
            } else {
                var companyid = $('#company').val();
                var branchid = $('#branch').val();
                // var accounttypeid = $('#accounttype').val();
                var accountlistcate = $("input[name='accountlistcate']:checked").val();
                
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
                                // accounttypeid: accounttypeid,
                                accountlistcate: accountlistcate
                            },
                            url: '<?php echo base_url() ?>Accountallocation/Getaccountlist',
                            success: function(result) { //alert(result);
                                $('#tableaccountlist > tbody').html(result);
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
        });

        // $('#company').change(function(){
        // var id = $(this).val();
        var id = '<?php echo $_SESSION['companyid'] ?>';

        $.ajax({
            type: "POST",
            data: {
                recordID: id
            },
            url: '<?php echo base_url() ?>Accountallocation/Getbranchaccocompany',
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
            }
        });
        // });

        $('#selectAll').click(function (e) {
            $('#tableaccountlist').closest('table').find('td input:checkbox').prop('checked', this.checked);
        });

        $('#fullsubmitBtn').click(function(){
            var tablelist = $("#tableaccountlist tbody input[type=checkbox]:checked");
            
            if(tablelist.length>0){
                $('#fullsubmitBtn').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i>Allocate');;
                jsonObj = [];
                tablelist.each(function() {
                    item = {}
                    var row = $(this).closest("tr");
                    item["accountid"] = row.find('td:eq(1)').text();
                    jsonObj.push(item);
                });
                var myJSON = JSON.stringify(jsonObj);
                // console.log(myJSON);

                var companyid = $('#company').val();
                var branchid = $('#branch').val();
                // var accounttypeid = $('#accounttype').val();
                var accountlistcate = $("input[name='accountlistcate']:checked").val();
                
                $.ajax({
                    type: "POST",
                    data: {
                        companyid: companyid,
                        branchid: branchid,
                        // accounttypeid: accounttypeid,
                        accountlistcate: accountlistcate,
                        tabledata: myJSON
                    },
                    url: '<?php echo base_url() ?>Accountallocation/Accountallocationinsertupdate',
                    success: function(result) { //alert(result);
                        // console.log(result);
                        var obj = JSON.parse(result);

                        if(obj.status==1){
                            actionreload(obj.action);
                        }
                        else{
                            action(obj.action);
                        }
                    }
                });
            }
            else{
                alert('Kindly verify the allocation of one or more account numbers.')
            }
        });
    });
</script>
<?php include "include/footer.php"; ?>
