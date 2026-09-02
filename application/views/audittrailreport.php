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
                            <span>Audit Trail Report</span>
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
                                    <div class="form-row">
                                        <!-- <div class="col-3 mb-1">
                                            <label class="small font-weight-bold">Creditor</label>
                                            <select class="form-control form-control-sm" id="supplier" name="supplier">
                                                <option value="">Select</option>
                                            </select>
                                        </div> -->
                                        <!-- <div class="col-2 mb-1">
                                            <label class="small font-weight-bold text-dark">From*</label>
                                            <input type="date" class="form-control form-control-sm" name="fromdate" id="fromdate" max="<?php echo date('Y-m-d'); ?>" required>
                                        </div>
                                        <div class="col-2 mb-1">
                                            <label class="small font-weight-bold text-dark">To*</label>
                                            <input type="date" class="form-control form-control-sm" name="todate" id="todate" max="<?php echo date('Y-m-d'); ?>" required>
                                        </div> -->
                                        <div class="col-2">
                                            <label class="small font-weight-bold">Period from</label>
                                            <select class="form-control form-control-sm" id="period_from" name="period_from" data-nestname="periodfrom">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <label class="small font-weight-bold">To</label>
                                            <select class="form-control form-control-sm" id="period_upto" name="period_upto" data-nestname="periodupto">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                        <div class="col mb-1">
                                            <label class="small font-weight-bold text-dark">&nbsp;</label><br>
                                            <button type="button" class="btn btn-primary btn-sm px-4" id="searchbtn"><i class="fas fa-search mr-2"></i> Search</button>
                                            <input type="submit" class="d-none" id="hidesubmit">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-12 text-right">
                                <hr>
                                <button type="button" id="btnexcelconvert" class="btn btn-success btn-sm px-4 mb-3 mr-2" disabled><i class="fas fa-file-excel mr-2"></i>Excel</button>
                                <button type="button" id="btnpdfconvert" class="btn btn-danger btn-sm px-4 mb-3" disabled><i class="fas fa-file-pdf mr-2"></i>PDF</button>
                            </div>
                            <div class="col-12">
                                <div id="reportviewdiv"></div>
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

        // $("#supplier").select2({
        //     ajax: {
        //         url: "<?php echo base_url() ?>Creditorreport/Getsupplierlist",
        //         type: "post",
        //         dataType: 'json',
        //         delay: 250,
        //         data: function (params) {
        //             return {
        //                 companyid: '<?php echo $_SESSION['companyid'] ?>',
        //                 branchid: '<?php echo $_SESSION['branchid'] ?>',
        //                 searchTerm: params.term // search term
        //             };
        //         },
        //         processResults: function (response) {
        //             return {
        //                 results: response
        //             };
        //         },
        //         cache: true
        //     }
        // });

        getperiodlist('<?php echo $_SESSION['companyid'] ?>', '<?php echo $_SESSION['branchid'] ?>');

        $('#searchbtn').click(function() {
            if (!$("#formsearch")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmit").click();
            } else {
                $('#btnpdfconvert').prop('disabled', true);
                var fromdate = $('#period_from').val();
                var todate = $('#period_upto').val();
                var supplier = $('#supplier').val();
                var reporttype = $('#reporttype').val();

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
                                fromdate: fromdate,
                                todate: todate,
                                supplier: supplier,
                                reporttype: reporttype
                            },
                            url: 'Audittrailreport/Audittrailreportview',
                            success: function (result) { //alert(result);
                                // console.log(result);
                                Swal.close();
                                $('#reportviewdiv').html(result);
                                exportoption();
                                $('#btnpdfconvert').prop('disabled', false);
                                $('#btnexcelconvert').prop('disabled', false);
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

    function exportoption(){
        $('#btnpdfconvert').click(function(){
            var { jsPDF } = window.jspdf;
            var doc = new jsPDF('l', 'pt', 'legal');

            // Define table content
            var table = document.getElementById("audittrailreporttable");
            
            var rows = [];
            for (var i = 0, row; row = table.rows[i]; i++) {
                // console.log(row);
                var rowData = [];
                for (var j = 0, col; col = row.cells[j]; j++) {
                    if(row.cells.length==1){					
						if (col.tagName.toLowerCase() === 'th') {						
							rowData.push({content: col.innerText, styles: {halign: 'left', fontStyle: 'bold'}});
						}
					}
					else if (col.tagName.toLowerCase() === 'th') {
                        if(row.cells.length==3){
                            if(j==0){
                                rowData.push({content: col.innerText, colSpan: 5, styles: {halign: 'right', fontStyle: 'bold'}});
                            }
                            else{
                                rowData.push({content: col.innerText, styles: {halign: 'right', fontStyle: 'bold'}});
                            }
                        }
                        else{
						    rowData.push({content: col.innerText, styles: {fontStyle: 'bold'}});
                        }
					}
					else{
						rowData.push(col.innerText);
					}
                }
                rows.push(rowData);
            }

            var headers = [rows[0]];
            var data = rows.slice(1);

            doc.setFontSize(12);

            const titleLine1 = "<?php echo $_SESSION['company'] ?>";
            const titleLine2 = 'Audit Trail Report';
            const titleLine3 = $('#fromdate').val() + ' - ' + $('#todate').val();

            const pageWidth = doc.internal.pageSize.getWidth();
            const pageHeight = doc.internal.pageSize.getHeight();

            const textWidth1 = doc.getTextWidth(titleLine1);
            doc.setFontSize(11);
            const textWidth2 = doc.getTextWidth(titleLine2);
            doc.setFontSize(9);
            const textWidth3 = doc.getTextWidth(titleLine3);

            const xPosition1 = (pageWidth - textWidth1) / 2;
            const xPosition2 = (pageWidth - textWidth2) / 2;
            const xPosition3 = (pageWidth - textWidth3) / 2;

            const yPosition1 = 40; 
            const yPosition2 = yPosition1 + 15; 
            const yPosition3 = yPosition1 + 30; 

            doc.setFontSize(12); 
            doc.text(titleLine1, xPosition1, yPosition1);
            doc.setFontSize(11); 
            doc.text(titleLine2, xPosition2, yPosition2);
            doc.setFontSize(9); 
            doc.text(titleLine3, xPosition3, yPosition3);

            doc.setFontSize(12);

            doc.autoTable({
                head: headers,
                body: data,
                startY: 80,
                theme: 'striped',
                headStyles: { fillColor: [41, 128, 185] }, 
                styles: { cellPadding: 5, halign: 'left', fontSize: 8 }, 
                columnStyles: {
                    5: { halign: 'right' }, 
                    6: { halign: 'right' },
                }
            });

            var filetitle = ''+'<?php echo $_SESSION['company'] ?>_'+titleLine3;
            doc.save(filetitle+".pdf");
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

    function getperiodlist(company, branch, alreadyaccount){
        $.ajax({
            type: "POST",
            data: {
                company: company,
                branch: branch
            },
            url: '<?php echo base_url() ?>ReportModule/Getperiodlist',
            success: function(result) { //alert(result);
                // console.log(result);
                
                var obj = JSON.parse(result);
                var html = '';
                html += '<option value="">Select</option>';
                $.each(obj, function (i, item) {
                    html += '<option value="' + obj[i].idtbl_master + '">';
                    html += obj[i].desc+' '+obj[i].monthname ;
                    html += '</option>';
                });
                $('#period_from').empty().append(html);  
                $('#period_upto').empty().append(html);  

                <?php if(isset($selectaccount) && $selectaccount): ?>
                if(alreadyaccount == 1){
                    $('#period_from').val('<?php echo $_GET['periodfrom'] ?>');
                    $('#period_upto').val('<?php echo $_GET['periodto'] ?>');

                    setTimeout(function() {
                        $("#submit").click();
                    }, 2000);
                }
                <?php endif; ?>
            }
        });
    }
</script>
<script>
// Self-contained Excel export — no external library needed.
// Wraps #tablereport in a minimal HTML document and downloads it as .xls,
// which Excel opens natively (it reads HTML tables inside an .xls container).
document.getElementById('btnexcelconvert').addEventListener('click', function () {
    var table       = document.getElementById('audittrailreporttable');
    var periodTitle = 'Audit Trail Report - ' + document.getElementById('fromdate').value + ' to ' + document.getElementById('todate').value;
    var reportTitle = 'Audit Trail Report';
    var fileTitle    = '<?php echo $_SESSION['company'] ?>_audit_trail_report_';
 
    var companyName = <?php echo json_encode($_SESSION['company']); ?>;
 
    var html = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">'
        + '<head><meta charset="UTF-8">'
        + '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>'
        + '<x:Name>Cash Flow</x:Name>'
        + '<x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions>'
        + '</x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->'
        + '</head><body>'
        + '<h3>' + companyName + '</h3>'
        + '<h4>' + reportTitle + '</h4>'
        + '<p>' + periodTitle + '</p>'
        + table.outerHTML
        + '</body></html>';
 
    var blob = new Blob(['\ufeff' + html], { type: 'application/vnd.ms-excel' });
    var url  = URL.createObjectURL(blob);
 
    var a = document.createElement('a');
    a.href     = url;
    a.download = fileTitle + periodTitle.replace(/[^a-z0-9]+/gi, '_') + '.xls';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
});
</script>
<?php include "include/footer.php"; ?>
