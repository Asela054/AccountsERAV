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
                            <div class="page-header-icon"><i class="far fa-file-pdf"></i></div>
                            <span>Expence Report</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <form id="formsearch">
                                    <div class="row">
                                        <div class="col-2">
                                            <label class="small font-weight-bold text-dark">From*</label>
                                            <input type="date" class="form-control form-control-sm" name="fromdate" id="fromdate">
                                        </div>
                                        <div class="col-2">
                                            <label class="small font-weight-bold text-dark">To*</label>
                                            <input type="date" class="form-control form-control-sm" name="todate" id="todate">
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">&nbsp;</label><br>
                                            <button type="button" class="btn btn-primary btn-sm px-4" id="searchbtn"><i class="fas fa-search mr-2"></i> Search</button>
                                            <input type="submit" class="d-none" id="hidesubmit">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-12">
                                <hr>
                                <div class="scrollbar pb-3" id="style-2">
                                    <table id="dataTable" class="table table-striped table-bordered table-sm small" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Company</th>
                                                <th>Branch</th>
                                                <th>Date</th>
                                                <th>Petty Cash Code</th>
                                                <th>Description</th>
                                                <th>Account</th>
                                                <th>Expense Account</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
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

        $('#searchbtn').click(function() {
            if (!$("#formsearch")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmit").click();
            } else {
                var fromdate = $('#fromdate').val();
                var todate = $('#todate').val();

                $('#dataTable').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "lengthMenu": [
                        [10, 25, 50, -1],
                        [10, 25, 50, 'All'],
                    ],
                    "dom": "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                    "buttons": [{
                            extend: 'csv',
                            className: 'btn btn-success btn-sm',
                            title: 'Expence Report',
                            text: '<i class="fas fa-file-csv mr-2"></i> CSV',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            className: 'btn btn-danger btn-sm',
                            title: 'Expence Report',
                            text: '<i class="fas fa-file-pdf mr-2"></i> PDF',
                            footer: true,
                            orientation: 'landscape',
                            pageSize: 'A4'
                        },
                        {
                            extend: 'print',
                            title: 'Expence Report',
                            className: 'btn btn-primary btn-sm',
                            text: '<i class="fas fa-print mr-2"></i> Print',
                            customize: function(win) {
                                $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                            },
                            footer: true
                        },
                        // 'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                    "ajax": {
                        "url": "<?php echo base_url(); ?>scripts/getexpencereport.php",
                        "type": "POST",
                        "data": {
                            fromdate: fromdate,
                            todate: todate
                        }
                    },
                    "columns": [
                        {
                            "data": "idtbl_pettycash"
                        },
                        {
                            "targets": -1,
                            "className": '',
                            "data": null,
                            "render": function(data, type, full) {
                                return '<?php echo $_SESSION['company'] ?>';
                            }
                        },
                        {
                            "targets": -1,
                            "className": '',
                            "data": null,
                            "render": function(data, type, full) {
                                return '<?php echo $_SESSION['branch'] ?>';
                            }
                        },
                        {
                            "data": "date"
                        },
                        {
                            "data": "pettycashcode"
                        },
                        {
                            "data": "desc"
                        },
                        {
                            "targets": -1,
                            "className": '',
                            "data": null,
                            "render": function(data, type, full) {
                                return full['petty_account_no'] + ' - ' + full['account_name'];
                            }
                        },
                        {
                            "targets": -1,
                            "className": '',
                            "data": null,
                            "render": function(data, type, full) {
                                if (full['expense_account_no'] != null) {
                                    return full['expense_account_no'] + ' - ' + full['expense_account_name'];
                                } else {
                                    return full['detail_account_no'] + ' - ' + full['account_detail_name'];
                                }
                            }
                        },
                        {
                            "targets": -1,
                            "className": 'text-right',
                            "data": null,
                            "render": function(data, type, full) {
                                return addCommas(full['amount'] ? parseFloat(full['amount']).toFixed(2) : '0.00');
                            }
                        }
                    ],
                    "footerCallback": function (row, data, start, end, display) {
                        var api = this.api();

                        // Helper function to calculate the total for a column
                        var total = function (column, dataProperty) {
                            return api
                                .column(column, { page: 'current' }) // 'current' for visible data, remove for all data
                                .data()
                                .reduce(function (a, b) {
                                    var value = parseFloat(b[dataProperty]) || 0;
                                    return a + value;
                                }, 0);
                        };

                        // Total over all filtered pages
                        var expenceTotal = total(8, 'amount'); // Index 6 is the 'Expence' column

                        // Update footer
                        $(api.column(7).footer()).html('Total'); // Set 'Total' text on the first column's footer cell
                        
                        // Column 6: Post Balance
                        $(api.column(8).footer()).html(
                            addCommas(expenceTotal.toFixed(2))
                        );
                    },
                    "destroy": true
                });
            }
        });
    });

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
