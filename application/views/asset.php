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
                            <div class="page-header-icon"><i class="fas fa-wallet"></i></div>
                            <span>Asset</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-4">
                                <form action="<?php echo base_url() ?>Asset/Assetinsertupdate" method="post" autocomplete="off">
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Asset Name*</label>
                                            <input type="text" class="form-control form-control-sm" name="assetname"
                                                id="assetname" required>
                                        </div>                        
                                    </div>
                                
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Code*</label>
                                            <input type="code" class="form-control form-control-sm" name="code" id="code">
                                        </div>
                                    </div>

                                    <div class="form-group mb-1">
                        				<label class="small font-weight-bold">Asset Type*</label>
                        				<select class="form-control form-control-sm" name="assettype"
                        					id="assettype" required>
                        					<option value="">Select</option>
                                            <?php foreach ($assettype->result() as $rowassettype) { ?>
                                                <option value="<?php echo $rowassettype->idtbl_asset_type ?>"><?php echo $rowassettype->asset_type ?></option>
                                            <?php } ?>
                        				</select>
                        			</div>
                                    
                                    <div class="form-group mb-1">
                        				<label class="small font-weight-bold">Depreciation Type*</label>
                        				<select class="form-control form-control-sm" name="depreciationtype"
                        					id="depreciationtype" required>
                        					<option value="">Select</option>
                                            <?php foreach ($depreciationtype->result() as $rowdepreciationtype) { ?>
                                                <option value="<?php echo $rowdepreciationtype->idtbl_depreciation_type ?>"><?php echo $rowdepreciationtype->depreciation_type ?></option>
                                            <?php } ?>
                        				</select>
                        			</div>

                                    <div class="form-group mb-1">
                        				<label class="small font-weight-bold">Depreciation Category*</label>
                        				<select class="form-control form-control-sm" name="depreciationcategory"
                        					id="depreciationcategory" required>
                        					<option value="">Select</option>
                                            <?php foreach ($depreciationcategory->result() as $rowdepreciationcategory) { ?>
                                                <option value="<?php echo $rowdepreciationcategory->idtbl_depreciation_category ?>"><?php echo $rowdepreciationcategory->depreciation_category ?></option>
                                            <?php } ?>
                        				</select>
                        			</div>

                                    <div class="form-group mb-1">
                        				<label class="small font-weight-bold">Depreciation Method*</label>
                        				<select class="form-control form-control-sm" name="depreciationmethod"
                        					id="depreciationmethod" required>
                        					<option value="">Select</option>
                                            <?php foreach ($depreciationmethod->result() as $rowdepreciationmethod) { ?>
                                                <option value="<?php echo $rowdepreciationmethod->idtbl_depreciation_method ?>"><?php echo $rowdepreciationmethod->method ?></option>
                                            <?php } ?>
                        				</select>
                        			</div>

                                    <div class="form-row mb-1">
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Current Year*</label>
                                            <input type="currentyear" class="form-control form-control-sm" name="currentyear" id="currentyear">
                                        </div>
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Depreciation Year*</label>
                                            <input type="depreciationyear" class="form-control form-control-sm" name="depreciationyear" id="depreciationyear">
                                        </div>
                                    </div>

                                    <div class="form-row mb-1">
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Depreciation Start Date*</label>
                                            <input type="date" class="form-control form-control-sm" name="depreciationstartdate" id="depreciationstartdate">
                                        </div>

                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Depreciation Rate*</label>
                                            <input type="rate" class="form-control form-control-sm" name="depreciationrate" id="depreciationrate">
                                        </div>
                                    </div>
                                    
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Asset Depreciation*</label>
                                            <input type="assetdepreciation" class="form-control form-control-sm" name="assetdepreciation" id="assetdepreciation">
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Purchase Date*</label>
                                            <input type="date" class="form-control form-control-sm" name="purchasedate" id="purchasedate">
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
                                                <th>Code</th>
                                                <th>Asset Type</th>
                                                <th>Depreciation Type</th>
                                                <th>Depreciation Category</th>
                                                <th>Depreciation Method</th>
                                                <th>Current Year</th> 
                                                <th>Depreciation Year</th> 
                                                <th>Depreciation Start Date </th> 
                                                <th>Depreciation Rate</th>
                                                <th>Asset Depreciation</th> 
                                                <th>Purchase Date</th> 
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
            //"processing": true,
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
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
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
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    },
                },
                // 'csv', 'pdf', 'print'
            ],
            ajax: {
                url: "<?php echo base_url() ?>scripts/assetlist.php",
                type: "POST", // you can use GET
                // data: function(d) {}
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_asset"
                },
                {
                    "data": "asset_name"
                },
                {
                    "data": "asset_code"
                },
                {
                    "data": "asset_type"
                },
                {
                    "data": "depreciation_type"
                },
                {
                    "data": "depreciation_category"
                },
                {
                    "data": "method"
                },
                {
                    "data": "currentyear"
                },
                {
                    "data": "depreciationyear"
                },
                {
                    "data": "assetdiscription"
                },
                {
                    "data": "purchasedate"
                },
                {
                    "data": "depreciationstartdate"
                },
                {
                    "data": "depreciationrate"
                },
                
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        button+='<button class="btn btn-primary btn-sm btnEdit mr-1 ';if(editcheck!=1){button+='d-none';}button+='" id="'+full['idtbl_asset']+'"><i class="fas fa-pen"></i></button>';
                        if(full['status']==1){
                            button+='<a href="<?php echo base_url() ?>Asset/Assetstatus/'+full['idtbl_asset']+'/2" onclick="return deactive_confirm()" target="_self" class="btn btn-success btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='"><i class="fas fa-check"></i></a>';
                        }else{
                            button+='<a href="<?php echo base_url() ?>Asset/Assetstatus/'+full['idtbl_asset']+'/1" onclick="return active_confirm()" target="_self" class="btn btn-warning btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='"><i class="fas fa-times"></i></a>';
                        }
                        button+='<a href="<?php echo base_url() ?>Asset/Assetstatus/'+full['idtbl_asset']+'/3" onclick="return delete_confirm()" target="_self" class="btn btn-danger btn-sm ';if(deletecheck!=1){button+='d-none';}button+='"><i class="fas fa-trash-alt"></i></a>';
                        
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
                    url: '<?php echo base_url() ?>Asset/Assetedit',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#assetname').val(obj.assetname);
                        $('#code').val(obj.code);
                        $('#assettype').val(obj.assettype);                      
                        $('#depreciationtype').val(obj.depreciationtype);                    
                        $('#depreciationcategory').val(obj.depreciationcategory);                      
                        $('#depreciationmethod').val(obj.depreciationmethod);                       
                        $('#currentyear').val(obj.currentyear);   
                        $('#depreciationyear').val(obj.depreciationyear); 
                        $('#depreciationstartdate').val(obj.depreciationstartdate); 
                        $('#depreciationrate').val(obj.depreciationrate);
                        $('#assetdepreciation').val(obj.assetdepreciation); 
                        $('#purchasedate').val(obj.purchasedate);
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
