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
                            <div class="page-header-icon"><i class="fas fa-cash-register"></i></div>
                            <span>Asset Sell</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-4">
                                <form action="<?php echo base_url() ?>Assetsell/Assetsellinsertupdate" method="post" autocomplete="off">
                                   
                                <div class="form-row mb-1">
                                    <label class="small font-weight-bold text-dark">Asset Name*</label>
                                    <select class="form-control form-control-sm" name="asset_name" id="asset_name" required >
                                        <option value="">Select</option>
                                        <?php foreach ($asset_name->result() as $rowasset_name){?>
                                        <option value="<?php echo $rowasset_name->idtbl_asset ?>"><?php echo $rowasset_name->asset_name ?></option>
                                        <?php }?>
                                    </select>
                                </div>

                                
                                  
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Date*</label>
                                            <input type="date" class="form-control form-control-sm" name="date"
                                                id="date" required>
                                        </div>                        
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Amount*</label>
                                            <input type="text" class="form-control form-control-sm" name="amount"
                                                id="amount" required>
                                        </div>                        
                                    </div>
                                  
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Reason*</label>
                                            <textarea name="reason" id="reason" class="form-control form-control-sm" rows="5"></textarea>
                                        </div>                        
                                    </div>
                                    
                                    <div class="form-group mt-3 text-right">
                                        <button type="submit" id="submitBtn" class="btn btn-primary btn-sm px-4" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                    <input type="hidden" name="recordOption" id="recordOption" value="1" required>
                                    <input type="hidden" name="recordID" id="recordID" value="">
                                </form>
                            </div>
                            <div class="col-8">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Asset Name</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Reason</th>
                                                <th>Sell</th>
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

        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,

            
            ajax: {
                url: "<?php echo base_url() ?>scripts/assetselllist.php",
                type: "POST", // you can use GET
                // data: function(d) {}
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_asset_sell"
                },
                {
                    "data": "asset_name"
                },
                {
                    "data": "date"
                },
                {  
                     "data": "amount"
                },
                {
                    "data": "reason"
                },
                {
                    
                    
                    "targets": -1,
                    "className": 'text-center',
                    "data": null,
                    "render": function(data, type, full) {
                        if(full['sell']==1){
                            return '<i class="fas fa-check text-success mt-2"></i>';
                        }
                        else{
                            return '<i class="fas fa-times text-danger mt-2"></i>';
                        }
                    }
                },
                {
              
                
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        
                        button+='<button class="btn btn-secondary btn-sm btnSell mr-1 ';if(editcheck!=1){button+='d-none';}button+='" id="'+full['idtbl_asset_sell']+'"><i class="fa fa-cart-arrow-down"></i></button>';
                        button+='<button class="btn btn-primary btn-sm btnEdit mr-1 ';if(editcheck!=1){button+='d-none';}button+='" id="'+full['idtbl_asset_sell']+'"><i class="fas fa-pen"></i></button>';
                        if(full['status']==1){
                            button+='<a href="<?php echo base_url() ?>Assetsell/Assetsellstatus/'+full['idtbl_asset_sell']+'/2" onclick="return deactive_confirm()" target="_self" class="btn btn-success btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='"><i class="fas fa-check"></i></a>';
                        }else{
                            button+='<a href="<?php echo base_url() ?>Assetsell/Assetsellstatus/'+full['idtbl_asset_sell']+'/1" onclick="return active_confirm()" target="_self" class="btn btn-warning btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='"><i class="fas fa-times"></i></a>';
                        }
                        button+='<a href="<?php echo base_url() ?>Assetsell/Assetsellstatus/'+full['idtbl_asset_sell']+'/3" onclick="return delete_confirm()" target="_self" class="btn btn-danger btn-sm ';if(deletecheck!=1){button+='d-none';}button+='"><i class="fas fa-trash-alt"></i></a>';
                        
                        return button;


               
                    

    
                    }
                }
            ],
            drawCallback: function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
        $('#dataTable tbody').on('click', '.btnEdit', function() {
            var r = confirm("Are you sure, You want to Edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: '<?php echo base_url() ?>Assetsell/Assetselledit',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id); 
                        $('#asset_name').val(obj.asset_name);                   
                        $('#date').val(obj.date);
                        $('#amount').val(obj.amount); 
                        $('#reason').val(obj.reason);                                             


                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });

        $('#dataTable tbody').on('click', '.btnSell', function() {
            var r = confirm("Are you sure, You want to Sell this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: '<?php echo base_url() ?>Assetsell/Assetsellsale',
                    success: function(result) { 
                        $('#dataTable').DataTable().ajax.reload();
                    }
                });
            }
        });
    });

    function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to active this?");
    }

    function delete_confirm() {
        return confirm("Are you sure you want to remove this?");
    }
</script>
<?php include "include/footer.php"; ?>