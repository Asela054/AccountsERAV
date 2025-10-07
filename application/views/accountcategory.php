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
                            <span>Account Prime Category</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form action="<?php echo base_url() ?>Accountcategory/Accountcategoryinsertupdate" method="post" autocomplete="off">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Code*</label>
                                        <input type="text" class="form-control form-control-sm" name="accountcatecode" id="accountcatecode" minlength="2" maxlength="2" pattern="[A-Za-z ]+" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Account Prime Category*</label>
                                        <input type="text" class="form-control form-control-sm" name="accountcategory" id="accountcategory" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Finacial Type*</label>
                                        <select name="finacialtype" id="finacialtype" class="form-control form-control-sm" required>
                                            <option value="">Select</option>
                                            <?php foreach($finacialtype->result() as $rowfinacialtype){ ?>
                                            <option value="<?php echo $rowfinacialtype->idtbl_account_finacialtype ?>"><?php echo $rowfinacialtype->finacialtype ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Transaction Type*</label>
                                        <select name="transactiontype" id="transactiontype" class="form-control form-control-sm" required>
                                            <option value="">Select</option>
                                            <?php foreach($transactiontype->result() as $rowtransactiontype){ ?>
                                            <option value="<?php echo $rowtransactiontype->idtbl_account_transactiontype ?>"><?php echo $rowtransactiontype->transactiontype ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group mt-3 text-right">
                                        <button type="submit" id="submitBtn" class="btn btn-primary btn-sm px-4" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">
                                </form>
                            </div>
                            <div class="col-9">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Code</th>
                                                <th>Account Category</th>
                                                <th>Finacial Type</th>
                                                <th>Transaction Type</th>
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
<?php include "include/footerscripts.php"; ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var usernameInput = document.getElementById('accountcatecode');

        usernameInput.addEventListener('invalid', function() {
            if (this.validity.patternMismatch) {
                this.setCustomValidity('Code should not contain numbers or special characters.');
            } else {
                this.setCustomValidity('');
            }
        });

        // Reset custom validity when the input becomes valid
        usernameInput.addEventListener('input', function() {
            this.setCustomValidity('');
        });
    });
    $(document).ready(function() {
        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "<?php echo base_url() ?>scripts/accountcategorylist.php",
                type: "POST", // you can use GET
                data: function(d) {
                    d.userID = '<?php echo $_SESSION['userid']; ?>';
                }
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_account_category"
                },
                {
                    "data": "code"
                },
                {
                    "data": "category"
                },
                {
                    "data": "finacialtype"
                },
                {
                    "data": "transactiontype"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';

                        if(editcheck==1){
                            button+='<button type="button" class="btn btn-primary btn-sm btnEdit mr-1" id="'+full['idtbl_account_category']+'"><i class="fas fa-pen"></i></button>';
                        }
                        if(full['status']==1 && statuscheck==1){
                            button+='<button type="button" data-url="Accountcategory/Accountcategorystatus/'+full['idtbl_account_category']+'/2" data-actiontype="2" class="btn btn-success btn-sm mr-1 btntableaction"><i class="fas fa-check"></i></button>';
                        }else if(full['status']==2 && statuscheck==1){
                            button+='<button type="button" data-url="Accountcategory/Accountcategorystatus/'+full['idtbl_account_category']+'/1" data-actiontype="1" class="btn btn-warning btn-sm mr-1 text-light btntableaction"><i class="fas fa-times"></i></button>';
                        }
                        if(deletecheck==1){
                            button+='<button type="button" data-url="Accountcategory/Accountcategorystatus/'+full['idtbl_account_category']+'/3" data-actiontype="3" class="btn btn-danger btn-sm text-light btntableaction"><i class="fas fa-trash-alt"></i></button>';
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
                    url: '<?php echo base_url() ?>Accountcategory/Accountcategoryedit',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#accountcatecode').val(obj.code);                       
                        $('#accountcategory').val(obj.category);                       
                        $('#finacialtype').val(obj.finacialtype);                       
                        $('#transactiontype').val(obj.transactiontype);                       

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });
    });
</script>
<?php include "include/footer.php"; ?>
