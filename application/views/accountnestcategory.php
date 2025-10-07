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
                            <span>Account Sub Category</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form action="<?php echo base_url() ?>Accountnestcategory/Accountnestcategoryinsertupdate" method="post" autocomplete="off">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Account Prime Category*</label>
                                        <select name="accountcategory" id="accountcategory" class="form-control form-control-sm" required>
                                            <option value="">Select</option>
                                            <?php foreach($accountcategory->result() as $rowaccountcategory){ ?>
                                            <option value="<?php echo $rowaccountcategory->idtbl_account_category ?>"><?php echo $rowaccountcategory->category.' - '.$rowaccountcategory->code  ?></option>
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
                                        <input type="text" class="form-control form-control-sm" name="nestaccountcategory" id="nestaccountcategory" required>
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
                                                <th>Account Prime Category</th>
                                                <th>Account Category</th>
                                                <th>Account Sub Category</th>
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
        var usernameInput = document.getElementById('subaccountcatecode');

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

        $('#accountcategory').change(function(){
            var id = $(this).val();            
            getsubcategory(id, '');
        });

        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "<?php echo base_url() ?>scripts/accountnestcategorylist.php",
                type: "POST", // you can use GET
                data: function(d) {
                    d.userID = '<?php echo $_SESSION['userid']; ?>';
                }
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_account_nestcategory"
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
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';

                        if(editcheck==1){
                            button+='<button type="button" class="btn btn-primary btn-sm btnEdit mr-1" id="'+full['idtbl_account_nestcategory']+'"><i class="fas fa-pen"></i></button>';
                        }
                        if(full['status']==1 && statuscheck==1){
                            button+='<button type="button" data-url="Accountnestcategory/Accountnestcategorystatus/'+full['idtbl_account_nestcategory']+'/2" data-actiontype="2" class="btn btn-success btn-sm mr-1 btntableaction"><i class="fas fa-check"></i></button>';
                        }else if(full['status']==2 && statuscheck==1){
                            button+='<button type="button" data-url="Accountnestcategory/Accountnestcategorystatus/'+full['idtbl_account_nestcategory']+'/1" data-actiontype="1" class="btn btn-warning btn-sm mr-1 text-light btntableaction"><i class="fas fa-times"></i></button>';
                        }
                        if(deletecheck==1){
                            button+='<button type="button" data-url="Accountnestcategory/Accountnestcategorystatus/'+full['idtbl_account_nestcategory']+'/3" data-actiontype="3" class="btn btn-danger btn-sm text-light btntableaction"><i class="fas fa-trash-alt"></i></button>';
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
                    url: '<?php echo base_url() ?>Accountnestcategory/Accountnestcategoryedit',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#accountcategory').val(obj.accountcategory);                       
                        getsubcategory(obj.accountcategory, obj.accountsubcategory)                     
                        $('#nestaccountcategory').val(obj.nestcategory);                     

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });
    });

    function getsubcategory(id, value){
        $.ajax({
            type: "POST",
            data: {
                recordID: id
            },
            url: '<?php echo base_url() ?>Accountnestcategory/Getsubcateaccoaccountcate',
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
    }
</script>
<?php include "include/footer.php"; ?>
