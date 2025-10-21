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
                            <span>Petty Cash Summery Report</span>
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
                                                <th>Month</th>
                                                <th>Account</th>
                                                <!-- <th>Date</th> -->
                                                <!-- <th>Description</th> -->
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
                                                <!-- <th></th> -->
                                                <!-- <th></th>  -->
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

                // Swal.fire({
                //     title: '',
                //     html: '<div class="div-spinner"><div class="custom-loader"></div></div>',
                //     allowOutsideClick: false,
                //     showConfirmButton: false, // Hide the OK button
                //     backdrop: `
                //         rgba(255, 255, 255, 0.5) 
                //     `,
                //     customClass: {
                //         popup: 'fullscreen-swal'
                //     },
                //     didOpen: () => {
                //         document.body.style.overflow = 'hidden';

                //         $.ajax({
                //             type: "POST",
                //             data: {
                //                 frommonth: frommonth,
                //                 tomonth: tomonth
                //             },
                //             url: 'PettyCashSummeryReport/GetPettyCashSummeryReport',
                //             success: function (result) { //alert(result);
                                
                //             },
                //             error: function(error) {
                //                 // Close the SweetAlert on error
                //                 Swal.close();
                                
                //                 // Show an error alert
                //                 Swal.fire({
                //                     icon: 'error',
                //                     title: 'Error',
                //                     text: 'Something went wrong. Please try again later.'
                //                 });
                //             }
                //         });

                //         document.body.style.overflow = 'visible';
                //     }
                // });

                let lastCompany = null;
                let lastBranch = null;
                let lastMonth = null;
                let lastAccount = null;

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
                            title: 'Petty Cash Summery Report',
                            text: '<i class="fas fa-file-csv mr-2"></i> CSV',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            className: 'btn btn-danger btn-sm',
                            title: 'Petty Cash Summery Report',
                            text: '<i class="fas fa-file-pdf mr-2"></i> PDF',
                            footer: true,
                            orientation: 'landscape',
                            pageSize: 'A4',
                            customize: function(doc) {
                                // Set the table to 100% width in the PDF
                                doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                            }
                        },
                        {
                            extend: 'print',
                            title: 'Petty Cash Summery Report',
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
                        "url": "<?php echo base_url(); ?>scripts/getpettycashsummeryreport.php",
                        "type": "POST",
                        "data": {
                            fromdate: fromdate,
                            todate: todate
                        }
                    },
                    "columns": [
                        { "data": "idtbl_pettycash" },
                        { "data": "company_name" }, 
                        { "data": "branch_name" }, 
                        { "data": "month_name" }, 
                        { "data": "expense_account_full" }, 
                        // { "data": "date" }, 
                        // { "data": "desc" }, 
                        { 
                            "data": "amount", 
                            "className": 'text-right',
                            "render": function(data) {
                                return addCommas(data ? parseFloat(data).toFixed(2) : '0.00');
                            }
                        },
                    ],
                    "columnDefs": [
                        {
                            "targets": 0,
                            "render": function (data, type, full, meta) {
                                return meta.row + 1;
                            }
                        }
                    ],
                    "createdRow": function (row, data, dataIndex) {
                        const $row = $(row);
                        
                        // Grouping keys
                        const currentCompany = data.company_name;
                        const currentBranch = data.branch_name;
                        const currentMonth = data.month_name;
                        const currentAccount = data.expense_account_full;

                        // Check if a new group starts
                        const newGroup = 
                            currentCompany !== lastCompany || 
                            currentBranch !== lastBranch || 
                            currentMonth !== lastMonth || 
                            currentAccount === lastAccount;

                        if (!newGroup) {
                            // If it's NOT a new group, clear the content of the grouping columns
                            $row.find('td:eq(1)').html(''); // Company
                            $row.find('td:eq(2)').html(''); // Branch
                            $row.find('td:eq(3)').html(''); // Month
                            // $row.find('td:eq(4)').html(''); // Account
                        } else {
                            // Optional: Add a class to the first row of a group for styling
                            $row.addClass('group-start-row');
                        }
                        
                        // Update the last group variables for the next iteration
                        lastCompany = currentCompany;
                        lastBranch = currentBranch;
                        lastMonth = currentMonth;
                        lastAccount = currentAccount;
                    },
                    "initComplete": function(settings, json) {
                        // Reset last group variables after the table draws (e.g., on page change)
                        this.api().on('draw.dt', function () {
                            lastCompany = null;
                            lastBranch = null;
                            lastMonth = null;
                            lastAccount = null;
                        });
                    },
                    // --- END CRITICAL SECTION ---
                    
                    "footerCallback": function (row, data, start, end, display) {
                        var api = this.api();
                        
                        // Remove the helper function and calculate directly
                        var expenceTotal = 0;
                        
                        // Loop through the current page data
                        api.rows({ page: 'current' }).every(function () {
                            var rowData = this.data();
                            var amount = parseFloat(rowData.amount) || 0;
                            expenceTotal += amount;
                        });
                        
                        // Update footer - amount column is index 5 (0-indexed)
                        $(api.column(4).footer()).html('Total'); 
                        $(api.column(5).footer()).html(addCommas(expenceTotal.toFixed(2)));
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
