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
                            <div class="page-header-icon"><i class="fas fa-chart-line"></i></div>
                            <span>Assets Depreciation</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <form id="formsearch">
                            <div class="form-row">
                                <div class="col-2">
                                    <label class="small font-weight-bold">Company*</label>
                                    <select name="company" id="company" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php foreach($companylist as $rowcompanylist){ ?>
                                        <option value="<?php echo $rowcompanylist->idtbl_company ?>"><?php echo $rowcompanylist->company ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <label class="small font-weight-bold">Branch*</label>
                                    <select name="branch" id="branch" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <label class="small font-weight-bold">Month*</label>
                                    <input type="month" class="form-control form-control-sm" name="month" id="month" required>
                                </div>
                                <div class="col-2">
                                    <label class="small font-weight-bold">&nbsp;</label><br>
                                    <button type="button" class="btn btn-primary btn-sm px-4" id="btnsearch"><i class="fas fa-search mr-2"></i>Search</button>
                                    <input type="submit" class="d-none" id="hidesubmit">
                                </div>
                            </div>
                        </form>
                        <div class="row mt-3">
                            <div class="col-12">
                                <hr>
                                <table class="table table-bordered table-striped table-sm small" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="5%">
                                                <div class="custom-control custom-checkbox ml-3">
                                                    <input type="checkbox" class="custom-control-input checkallocate" id="selectAll">
                                                    <label class="custom-control-label mt-0" for="selectAll"></label>
                                                </div>
                                            </th>
                                            <th class="text-center">#</th>
                                            <th>Company</th>
                                            <th>Branch</th>
                                            <th>Assets</th>
                                            <th>Code</th>
                                            <th>Rate (Yearly)</th>
                                            <th>Depreciation Month</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-right">
                                <hr>
                                <button type="submit" class="btn btn-primary btn-sm" <?php if($addcheck==0){echo 'disabled';} ?> id="btncreatedep"><i class="fas fa-plus mr-2"></i>Create Depreciation</button>
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

        $('#dataTable').DataTable({info: false, ordering: false, paging: false,});

        $('#btnsearch').click(function(){
            if (!$("#formsearch")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmit").click();
            } else {
                var company = $('#company').val();
                var branch = $('#branch').val();
                var month = $('#month').val();

                $('#dataTable').DataTable({
                    "destroy": true,
                    "processing": true,
                    "serverSide": true,
                    info: false,
                    ordering: false,
                    paging: false,
                    ajax: {
                        url: "<?php echo base_url() ?>Assetdepreciation/Getassetsdepreciationinfo",
                        type: "POST", // you can use GET
                        data: function(d) {
                            d.company= company,
                            d.branch= branch,
                            d.month= month
                        }
                    },
                    "order": [[ 0, "desc" ]],
                    "columns": [
                        {
                            "targets": -1,
                            "className": 'text-center',
                            "data": null,
                            "render": function(data, type, full) {
                                return '<div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="checkbox'+full['idtbl_asset']+'"><label class="custom-control-label mt-0" for="checkbox'+full['idtbl_asset']+'"></label></div>';
                            }
                        },
                        {
                            "data": "idtbl_asset"
                        },
                        {
                            "data": "company"
                        },
                        {
                            "data": "branch"
                        },
                        {
                            "data": "asset_name"
                        },
                        {
                            "data": "asset_code"
                        },
                        {
                            "targets": -1,
                            "className": '',
                            "data": null,
                            "render": function(data, type, full) {
                                return full['depreciationrate']+'%';
                            }
                        },
                        {
                            "data": "month"
                        },                        
                        {
                            "targets": -1,
                            "className": 'text-right',
                            "data": null,
                            "render": function(data, type, full) {
                                return addCommas(parseFloat(full['monthdepreciation']).toFixed(2));
                            }
                        }
                    ],
                    drawCallback: function(settings) {
                        $('[data-toggle="tooltip"]').tooltip();
                    },
                });
            }
        });
        $('#company').change(function(){
            var id = $(this).val();
            getbranchlist(id, '');
        });

        $('#selectAll').click(function (e) {
            $('#dataTable').closest('table').find('td input:checkbox').prop('checked', this.checked);
        });

        $('#btncreatedep').click(function(){
            var tbody = $("#dataTable tbody input[type=checkbox]:checked");
            
            if(tbody.length>0){
                $('#btncreatedep').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i> Create Depreciation');

                jsonObj = [];
    			$("#tablesegregate tbody tr input[type=checkbox]:checked").each(function () {
    				item = {}
    				$(this).find('td').each(function (col_idx) {
    					item["col_" + (col_idx + 1)] = $(this).text();
    				});
    				jsonObj.push(item);
    			});

                var company = $('#company').val();
                var branch = $('#branch').val();
                var month = $('#month').val();

                $.ajax({
    				type: "POST",
    				data: {
    					tableData: jsonObj,
    					company: company,
    					branch: branch,
    					month: month
    				},
    				url: 'Assetdepreciation/Assetdepreciationinsertupdate',
    				success: function (result) { //alert(result);
    					console.log(result);
    					// var obj = JSON.parse(result);
    					// if (obj.status == 1) {
                        //     $('#dataTable> tbody').empty();
                        //     $('#btncreatedep').prop('disabled', false).html('<i class="fas fa-plus mr-2"></i> Complete');
                        //     setTimeout( function(){ 
                        //         window.location.reload();
                        //     } ,3000 );
    					// }
    					// action(obj.action);
    				}
    			});
            }
        });
    });

    function getbranchlist(id, value){
        $.ajax({
            type: "POST",
            data: {
                recordID: id
            },
            url: '<?php echo base_url() ?>Assetdepreciation/Getbranchaccocompany',
            success: function(result) { //alert(result);
                var obj = JSON.parse(result);
                var html = '';
                html += '<option value="">Select</option>';
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

    function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to active this?");
    }

    function delete_confirm() {
        return confirm("Are you sure you want to remove this?");
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

    function action(data) { 
        var obj = JSON.parse(data);
        $.notify({
            // options
            icon: obj.icon,
            title: obj.title,
            message: obj.message,
            url: obj.url,
            target: obj.target
        }, {
            // settings
            element: 'body',
            position: null,
            type: obj.type,
            allow_dismiss: true,
            newest_on_top: false,
            showProgressbar: false,
            placement: {
                from: "top",
                align: "center"
            },
            offset: 100,
            spacing: 10,
            z_index: 1031,
            delay: 5000,
            timer: 1000,
            url_target: '_blank',
            mouse_over: null,
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            },
            onShow: null,
            onShown: null,
            onClose: null,
            onClosed: null,
            icon_type: 'class',
            template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                '<span data-notify="icon"></span> ' +
                '<span data-notify="title">{1}</span> ' +
                '<span data-notify="message">{2}</span>' +
                '<div class="progress" data-notify="progressbar">' +
                '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                '</div>' +
                '<a href="{3}" target="{4}" data-notify="url"></a>' +
                '</div>'
        });
    }
</script>
<?php include "include/footer.php"; ?>
