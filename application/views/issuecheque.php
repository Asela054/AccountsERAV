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
                            <span>Issue Cheque</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <form id="searchform">
                                    <div class="form-row">
                                        <div class="col-3">
                                            <label class="small font-weight-bold">Company*</label>
                                            <select name="company" id="company" class="form-control form-control-sm" required>
                                                <!-- <option value="">Select</option> -->
                                                <?php foreach($companylist as $rowcompanylist){ ?>
                                                <option value="<?php echo $rowcompanylist->idtbl_company ?>"><?php echo $rowcompanylist->company ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <label class="small font-weight-bold">Branch*</label>
                                            <select name="branch" id="branch" class="form-control form-control-sm" required>
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <label class="small font-weight-bold">Month*</label>
                                            <input type="month" name="searchmonth" id="searchmonth" class="form-control form-control-sm" required>
                                        </div>
                                        <div class="col-3">
                                            <label class="small font-weight-bold">&nbsp;</label><br>
                                            <button type="button" class="btn btn-primary btn-sm px-4" id="btnchoosecominfo" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-search mr-2"></i>View Cheque</button>
                                            <input type="submit" class="d-none" id="hidesubmit">
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Company</th>
                                                <th>Branch</th>
                                                <th>Year</th>
                                                <th>Month</th>
                                                <th>Batch No</th>
                                                <th>Supplier</th>
                                                <th>Cheque Date</th>
                                                <th>Cheque No</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>&nbsp;</th>
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

        $('#dataTable').DataTable();

        // $('#company').change(function(){
        //     var id = $(this).val();
        //     getbranchlist(id, '');
        // });
        getbranchlist('<?php echo $_SESSION['companyid'] ?>', '');

        $('#btnchoosecominfo').click(function(){
            if (!$("#searchform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmit").click();
            } else {
                var companyID = $('#company').val();
                var branchID = $('#branch').val();
                var searchmonth = $('#searchmonth').val();

                $('#dataTable').DataTable({
                    "destroy": true,
                    "processing": true,
                    "serverSide": true,
                    ajax: {
                        url: "<?php echo base_url() ?>scripts/issuechequelist.php",
                        type: "POST", // you can use GET
                        data: function(d) {
                            d.companyID = companyID,
                            d.branchID = branchID,
                            d.searchmonth = searchmonth
                        }
                    },
                    "order": [[ 0, "desc" ]],
                    "columns": [
                        {
                            "data": "idtbl_cheque_issue"
                        },
                        {
                            "data": "company"
                        },
                        {
                            "data": "branch"
                        },
                        {
                            "data": "desc"
                        },
                        {
                            "data": "monthname"
                        },                        
                        {
                            "data": "batchno"
                        },
                        {
                            "data": "suppliername"
                        },
                        {
                            "data": "chedate"
                        },
                        {
                            "data": "chequeno"
                        },
                        {
                            "targets": -1,
                            "className": 'text-right',
                            "data": null,
                            "render": function(data, type, full) {
                                return addCommas(parseFloat(full['amount']).toFixed(2));
                            }
                        },
                        {
                            "targets": -1,
                            "className": 'text-right',
                            "data": null,
                            "render": function(data, type, full) {
                                if(full['chequereturn']==1){
                                    return '<i class="fas fa-times mr-2"></i> Return';
                                }
                                else{
                                    return '';
                                }
                            }
                        },
                        {
                            "targets": -1,
                            "className": 'text-right',
                            "data": null,
                            "render": function(data, type, full) {
                                var button='';
                                if(full['chequereturn']==0){
                                    button+='<button type="button" class="btn btn-danger btn-sm mr-1 btnReturn" id="'+full['idtbl_cheque_issue']+'"><i class="fas fa-undo-alt"></i></button>';
                                    button+='<a href="<?php echo base_url() ?>Issuecheque/Chequeprint/'+full['idtbl_cheque_issue']+'" target="_blank" class="btn btn-sm btn-primary"><i class="fas fa-print"></i></a>'
                                }
                                
                                return button;
                            }
                        }
                    ],
                    drawCallback: function(settings) {
                        $('[data-toggle="tooltip"]').tooltip();
                    },
                    createdRow: function( row, data, dataIndex){
                        if ( data['chequereturn'] == 1 ) {
                            $(row).addClass('table-danger');
                        }           
                    }
                });
            }
        });
        $('#dataTable tbody').on('click', '.btnReturn', async function() {
            var r = await Otherconfirmation("You want to return this cheque ? ");
            if (r == true) {
                var id = $(this).attr('id');

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
                            url: '<?php echo base_url() ?>Issuecheque/Issuechequestatus',
                            success: function(result) { //alert(result);
                                Swal.close();
                                var obj = JSON.parse(result);
                                if (obj.status == 1) {
                                    $('#dataTable').DataTable().ajax.reload( null, false );
                                }
                                action(obj.action);
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
    });

    function getbranchlist(id, value){
        $.ajax({
            type: "POST",
            data: {
                recordID: id
            },
            url: '<?php echo base_url() ?>Journalentry/Getbranchaccocompany',
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

                if(value!=''){
                    $('#branch').val(value);
                }
            }
        });
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
</script>
<?php include "include/footer.php"; ?>
