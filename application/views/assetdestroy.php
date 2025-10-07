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
                            <div class="page-header-icon"><i class="fas fa-user-tie"></i></div>
                            <span>Asset Destroy </span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-4">
                                <form action="<?php echo base_url() ?>Assetdestroy/Assetdestroyinsertupdate" method="post" autocomplete="off">
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Destroy Item*</label>
                                            <input type="text" class="form-control form-control-sm" name="destroy_item"
                                                id="destroy_item" required>
                                        </div>                        
                                    </div>

                                    <div class="form-row mb-1">
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Destroy Date*</label>
                                            <input type="date" class="form-control form-control-sm" name="destroy_date"
                                                id="destroy_date">
                                        </div>
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Destroy Amount*</label>
                                            <input type="text" class="form-control form-control-sm" name="destroy_amount"
                                                id="destroy_amount">
                                        </div>

                                   
                                </div>  

                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Destroy Reason*</label>
                                            <textarea class="form-control form-control-sm" name="destroy_reason" id="destroy_reason" rows="4" required></textarea>
                                        </div>                        
                                    </div>


                                    <div class="form-group mt-3 text-right">
                                    <button type="submit" id="submitBtn" class="btn btn-primary btn-sm px-4" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Submit</button>
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
                                                <th>Destroy Item</th>
                                                <th>Destroy Date</th>
                                                <th>Destroy Amount</th>
                                                <th>Destroy Reason</th>

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
            dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            "buttons": [
                { 
					extend: 'csv', 
					className: 'btn btn-success btn-sm', 
					title: 'Long Life Laboratory', 
					text: '<i class="fas fa-file-csv mr-2"></i> CSV', 
					exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    },
                    customize: function (csv) {
                        return "Long Life Laboratory - Long Life\n"+  csv;
                    }  },
                { 
					extend: 'pdf', 
					className: 'btn btn-danger btn-sm', 
					title: 'Long Life Laboratory', 
					text: '<i class="fas fa-file-pdf mr-2"></i> PDF', 
					exportOptions: {
                        columns: [  0, 1, 2, 3, 4, 5, 6]
                    },
                    customize: function (csv) {
                        return "Long Life Laboratory - Long Life\n"+  csv;
                    }  },
                { 
                    extend: 'print', 
                    title: 'Long Life Laboratory',
                    className: 'btn btn-primary btn-sm', 
                    text: '<i class="fas fa-print mr-2"></i> Print',
                    customize: function ( win ) {
                        $(win.document.body).find( 'table' )
                            .addClass( 'compact' )
                            .css( 'font-size', 'inherit' );
                    }, 
					exportOptions: {
                        columns: [  0, 1, 2, 3, 4, 5, 6 ]
                    },
                },
                // 'csv', 'pdf', 'print'
            ],
            ajax: {
                url: "<?php echo base_url() ?>scripts/assetdestroylist.php",
                type: "POST", // you can use GET
                // data: function(d) {}
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_asset_destroy"
                },
                {
                    "data": "destroy_item"
                },
                {
                    "data": "destroy_date"
                },
                {
                    "data": "destroy_amount"
                },
                {
                    "data": "destroy_reason"
                },

                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        button+='<button class="btn btn-primary btn-sm btnEdit mr-1 ';if(editcheck!=1){button+='d-none';}button+='" id="'+full['idtbl_asset_destroy']+'"><i class="fas fa-pen"></i></button>';
                        if(full['status']==1){
                            button+='<a href="<?php echo base_url() ?>Assetdestroy/Assetdestroystatus/'+full['idtbl_asset_destroy']+'/2" onclick="return deactive_confirm()" target="_self" class="btn btn-success btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='"><i class="fas fa-check"></i></a>';
                        }else{
                            button+='<a href="<?php echo base_url() ?>Assetdestroy/Assetdestroystatus/'+full['idtbl_asset_destroy']+'/1" onclick="return active_confirm()" target="_self" class="btn btn-warning btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='"><i class="fas fa-times"></i></a>';
                        }
                        button+='<a href="<?php echo base_url() ?>Assetdestroy/Assetdestroystatus/'+full['idtbl_asset_destroy']+'/3" onclick="return delete_confirm()" target="_self" class="btn btn-danger btn-sm ';if(deletecheck!=1){button+='d-none';}button+='"><i class="fas fa-trash-alt"></i></a>';
                        
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
                    url: '<?php echo base_url() ?>Assetdestroy/Assetdestroyedit',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#destroy_item').val(obj.destroy_item);
                        $('#destroy_date').val(obj.destroy_date);
                        $('#destroy_amount').val(obj.destroy_amount);
                        $('#destroy_reason').val(obj.destroy_reason);                 
                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
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
