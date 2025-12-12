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
                            <span>Creditor Report</span>
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
                                        <div class="col-2 mb-1">
                                            <label class="small font-weight-bold">Report Type</label>
                                            <select class="form-control form-control-sm" name="reporttype" id="reporttype" required>
                                                <option value="">Select</option>
                                                <option value="1">Creditor Statement</option>
                                                <option value="2">Creditor Age Analysis</option>
                                            </select>
                                        </div>
                                        <div class="col-3 mb-1">
                                            <label class="small font-weight-bold">Creditor</label>
                                            <select class="form-control form-control-sm" id="supplier" name="supplier">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                        <div class="col-2 mb-1">
                                            <label class="small font-weight-bold text-dark">From*</label>
                                            <input type="date" class="form-control form-control-sm" name="fromdate" id="fromdate" max="<?php echo date('Y-m-d'); ?>" required>
                                        </div>
                                        <div class="col-2 mb-1">
                                            <label class="small font-weight-bold text-dark">To*</label>
                                            <input type="date" class="form-control form-control-sm" name="todate" id="todate" max="<?php echo date('Y-m-d'); ?>" required>
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

        $('#reporttype').change(function(){
            var reporttype=$(this).val();
            if(reporttype==2){
                $('#fromdate').prop('readonly', true);
                $('#fromdate').prop('required', false);
                $('#todate').val('<?php echo date('Y-m-d'); ?>');
            }else{
                $('#fromdate').prop('readonly', false);
                $('#fromdate').prop('required', true);
                $('#todate').val('');
            }
        });

        $("#supplier").select2({
            ajax: {
                url: "<?php echo base_url() ?>Creditorreport/Getsupplierlist",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        companyid: '<?php echo $_SESSION['companyid'] ?>',
                        branchid: '<?php echo $_SESSION['branchid'] ?>',
                        searchTerm: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $('#searchbtn').click(function() {
            if (!$("#formsearch")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmit").click();
            } else {
                $('#btnpdfconvert').prop('disabled', true);
                var fromdate = $('#fromdate').val();
                var todate = $('#todate').val();
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
                            url: 'Creditorreport/Creditorreportview',
                            success: function (result) { //alert(result);
                                // console.log(result);
                                Swal.close();
                                $('#reportviewdiv').html(result);
                                exportoption();
                                $('#btnpdfconvert').prop('disabled', false);
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
            var table = document.getElementById("creditorStatementsTable");
            
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
                        if(row.cells.length==6){
                            if(j==0){
                                rowData.push({content: col.innerText, colSpan: 3, styles: {halign: 'right', fontStyle: 'bold'}});
                            }
                            else{
                                rowData.push({content: col.innerText, styles: {halign: 'right', fontStyle: 'bold'}});
                            }
                        }
                        else if(row.cells.length==4){
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

            var reporttype = $('#reporttype').val();
            const titleLine1 = "<?php echo $_SESSION['company'] ?>";
            const titleLine2 = $("#reporttype option:selected").text();
            var titleLine3 = $("#supplier option:selected").text();
            if($("#supplier").val()=='' && reporttype==1){
                titleLine3 = 'All Creditor';
            }
            else if($("#supplier").val()=='' && reporttype==2){
                titleLine3 = 'All Creditor Age Analysis';
            }

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

            if(reporttype==1){
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
                        7: { halign: 'right' },
                    }
                });
            }
            else if(reporttype==2){
                if($("#supplier").val()==''){
                    doc.autoTable({
                        head: headers,
                        body: data,
                        startY: 80,
                        theme: 'striped',
                        headStyles: { fillColor: [41, 128, 185] }, 
                        styles: { cellPadding: 5, halign: 'left', fontSize: 8 }, 
                        columnStyles: {
                            3: { halign: 'right' }, 
                            4: { halign: 'right' },
                            5: { halign: 'right' },
                            6: { halign: 'right' },
                            7: { halign: 'right' },
                        }
                    });
                }
                else{
                    doc.autoTable({
                        head: headers,
                        body: data,
                        startY: 80,
                        theme: 'striped',
                        headStyles: { fillColor: [41, 128, 185] }, 
                        styles: { cellPadding: 5, halign: 'left', fontSize: 8 }, 
                        columnStyles: {
                            2: { halign: 'right' }, 
                            3: { halign: 'right' },
                            4: { halign: 'right' },
                            5: { halign: 'right' },
                            6: { halign: 'right' },
                        }
                    });
                }
            }

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
</script>
<?php include "include/footer.php"; ?>
