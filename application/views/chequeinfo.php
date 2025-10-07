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
                            <div class="page-header-icon"><i class="fas fa-money-check-alt"></i></div>
                            <span>Cheque Information</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form action="<?php echo base_url() ?>Chequeinfo/Chequeinfoinsertupdate" method="post" autocomplete="off">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Bank*</label>
                                        <select name="bank" id="bank" class="form-control form-control-sm" required>
                                            <option value="">Select</option>
                                            <?php foreach($banklist->result() as $rowbanklist){ ?>
                                            <option value="<?php echo $rowbanklist->idtbl_bank ?>"><?php echo $rowbanklist->bankname.' - '.$rowbanklist->code  ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Branch*</label>
                                        <select name="bankbranch" id="bankbranch" class="form-control form-control-sm" required>
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="form-row">
                                        <div class="col">
                                            <label class="small font-weight-bold">Start No*</label>
                                            <input type="text" class="form-control form-control-sm input-integer" name="startno" id="startno" required>
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold">End No*</label>
                                            <input type="text" class="form-control form-control-sm input-integer" name="endno" id="endno" required>
                                        </div>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Chart of bank account*</label>
                                        <select name="chartaccount" id="chartaccount" class="form-control form-control-sm" required>
                                            <option value="">Select</option>
                                            <?php foreach($accountlist->result() as $rowaccountlist){ ?>
                                            <option value="<?php echo $rowaccountlist->idtbl_account ?>"><?php echo $rowaccountlist->accountname.' - '.$rowaccountlist->accountno  ?></option>
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
                                                <th>Bank</th>
                                                <th>Branch</th>
                                                <th>Chart of Account</th>
                                                <th>Start No</th>
                                                <th>End No</th>
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
    $(document).ready(function() {
        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        $('.input-integer').inputNumber({
            allowDecimals: false, allowNegative: false, thousandSep: ''
        });

        $('#bank').select2();
        $('#bankbranch').select2();
        $('#chartaccount').select2();

        $('#bank').change(function(){
            var id = $(this).val();
            getbankbranch(id, '');
        });

        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "<?php echo base_url() ?>scripts/chequeinfolist.php",
                type: "POST", // you can use GET
                data: function(d) {
                    d.userID = '<?php echo $_SESSION['userid']; ?>';
                }
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_cheque_info"
                },
                {
                    "data": "bankname"
                },
                {
                    "data": "branchname"
                },
                {
                    "data": "accountno"
                },
                {
                    "data": "startno"
                },
                {
                    "data": "endno"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';

                        if(editcheck==1){
                            button+='<button type="button" class="btn btn-primary btn-sm btnEdit mr-1" id="'+full['idtbl_cheque_info']+'"><i class="fas fa-pen"></i></button>';
                        }
                        if(full['status']==1 && statuscheck==1){
                            button+='<button type="button" data-url="Chequeinfo/Chequeinfostatus/'+full['idtbl_cheque_info']+'/2" data-actiontype="2" class="btn btn-success btn-sm mr-1 btntableaction"><i class="fas fa-check"></i></button>';
                        }else if(full['status']==2 && statuscheck==1){
                            button+='<button type="button" data-url="Chequeinfo/Chequeinfostatus/'+full['idtbl_cheque_info']+'/1" data-actiontype="1" class="btn btn-warning btn-sm mr-1 text-light btntableaction"><i class="fas fa-times"></i></button>';
                        }
                        if(deletecheck==1){
                            button+='<button type="button" data-url="Chequeinfo/Chequeinfostatus/'+full['idtbl_cheque_info']+'/3" data-actiontype="3" class="btn btn-danger btn-sm text-light btntableaction"><i class="fas fa-trash-alt"></i></button>';
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
                var id = $(this).attr('id');endno
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: '<?php echo base_url() ?>Chequeinfo/Chequeinfoedit',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#bank').val(obj.bank).trigger('change');;    
                        getbankbranch(obj.bank, obj.branch);         
                        $('#startno').val(obj.startno);                       
                        $('#endno').val(obj.endno);                       
                        $('#chartaccount').val(obj.chartaccount);                       

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });
    });

    function getbankbranch(id, value){
        $.ajax({
            type: "POST",
            data: {
                recordID: id
            },
            url: '<?php echo base_url() ?>Chequeinfo/Getbankbranchaccbank',
            success: function(result) { //alert(result);
                var obj = JSON.parse(result);
                var html = '';
                html += '<option value="">Select</option>';
                $.each(obj, function (i, item) {
                    html += '<option value="' + obj[i].idtbl_bank_branch + '">';
                    html += obj[i].branchname+' - '+obj[i].code;
                    html += '</option>';
                });
                $('#bankbranch').empty().append(html);    
                
                if(value!=''){
                    $('#bankbranch').val(value).trigger('change');;
                }
            }
        });
    }
</script>
<?php include "include/footer.php"; ?>
