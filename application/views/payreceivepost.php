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
                            <div class="page-header-icon"><i class="fas fa-exchange-alt"></i></div>
                            <span>Post All Data</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <form id="filtorform">
                                    <div class="form-row">
                                        <div class="col-2">
                                            <label class="small font-weight-bold">Month*</label>
                                            <input type="month" class="form-control form-control-sm" name="periodmonth" id="periodmonth" required>
                                        </div>
                                        <div class="col-2">
                                            <label class="small font-weight-bold">Company*</label>
                                            <select name="company" id="company" class="form-control form-control-sm" required>
                                                <!-- <option value="">Select</option> -->
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
                                            <label class="small font-weight-bold">Filtor Type*</label>
                                            <select name="filtortype" id="filtortype" class="form-control form-control-sm" required>
                                                <option value="">Select</option>
                                                <option value="1">Payable Transaction</option>
                                                <option value="2">Receivable Transaction</option>
                                                <option value="3">Petty Cash Transaction</option>
                                                <option value="4">Receivable Settle Transaction</option>
                                            </select>
                                        </div>
                                        <div class="col pt-2">
                                            <button type="button" id="submitBtn" class="btn btn-primary btn-sm px-4 mt-4"><i class="fas fa-search mr-2"></i>&nbsp;Seacrh</button>
                                        </div>
                                    </div>
                                    <input type="submit" id="hidebtnsubmit" class="d-none">
                                </form>
                                <hr>
                            </div>
                            <div class="col-12">
                                <div class="scrollbar pb-3" id="style-2">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="custom-control custom-checkbox ml-2 mb-2">
                                                <input type="checkbox" class="custom-control-input checkallocate" id="selectAll">
                                                <label class="custom-control-label" for="selectAll">Select All Records</label>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-striped table-sm nowrap small" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th width="5%" class="text-center">#</th>
                                                <th>Company</th>
                                                <th>Branch</th>
                                                <th>Trans Date</th>
                                                <th>Batch No</th>
                                                <th class="text-right" colspan="2">Amount</th>
                                                <th class="d-none">&nbsp;</th>
                                            </tr>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th colspan="2">Account</th>
                                                <th colspan="2">Narration</th>
                                                <th class="text-right">&nbsp;</th>
                                                <th class="text-right">&nbsp;</th>
                                                <th class="d-none">&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>    
                                                <th class="text-right" colspan="5">Total</th>
                                                <th class="text-right" id="segtotalset">0.00</th>
                                                <th class="text-right" id="tratotalset">0.00</th>
                                                <th class="d-none">&nbsp;</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <div class="row">
                                        <div class="col-12 text-right">
                                            <hr> 
                                            <?php if($addcheck==1){ ?>
                                            <button type="button" id="postingcompletebtn" class="btn btn-primary btn-sm" disabled><i class="fas fa-exchange-alt mr-2"></i>Post Complete</button>
                                            <?php } ?>
                                        </div>
                                    </div>
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

        // $('#company').change(function(){
        //     var id = $(this).val();
        //     getbranchlist(id, '');
        // });
        getbranchlist('<?php echo $_SESSION['companyid'] ?>', '');

        $('#submitBtn').click(function(){
            if (!$("#filtorform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidebtnsubmit").click();
            } else {
                $('#submitBtn').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i> Seacrh');
                var periodmonth = $('#periodmonth').val();
                var filtortype = $('#filtortype').val();
                var company = $('#company').val();
                var branch = $('#branch').val();
                
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
                                periodmonth: periodmonth, 
                                filtortype: filtortype, 
                                company: company, 
                                branch: branch
                            },
                            url: 'Payreceivepost/Getpayreceivelist',
                            success: function (result) { //alert(result);
                                Swal.close();
                                $('#dataTable > tbody').empty().append(result);
                                $('#submitBtn').prop('disabled', false).html('<i class="fas fa-search mr-2"></i> Seacrh');
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

        $('#selectAll').click(function (e) {
            $('#dataTable').closest('table').find('td input:checkbox').prop('checked', this.checked);
            gettotal();
        });
        $('#dataTable tbody').on('change', '.checkboxclick', function() {
            var mainID=$(this).attr("data-record");
            $('#dataTable').closest('table').find('td .subcheck'+mainID).prop('checked', this.checked);
            gettotal();
        });

        $('#postingcompletebtn').click(function(){
            var tablelist = $("#dataTable tbody input[type=checkbox]:checked");
            
            if(tablelist.length>0){
                $('#postingcompletebtn').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i>Post Complete');;
                jsonObj = [];
                tablelist.each(function() {
                    item = {}
                    var row = $(this).closest("tr");
                    if(row.find('.recordid').text()!=''){
                        item["recordid"] = row.find('.recordid').text();
                        jsonObj.push(item);
                    }
                });
                var myJSON = JSON.stringify(jsonObj);

                var filtortype= $('#filtortype').val();

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
                                filtortype: filtortype,
                                tabledata: myJSON
                            },
                            url: '<?php echo base_url() ?>Payreceivepost/Payreceivepostposting',
                            success: function(result) { //alert(result);
                                // console.log(result);
                                Swal.close();
                                var obj = JSON.parse(result);

                                if(obj.status==1){
                                    action(obj.action);
                                    setTimeout(function(){location.reload();}, 3000);
                                }
                                else{
                                    action(obj.action);
                                }
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
            else{
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Kindly verify the post of one or more account numbers.'
                });
            }
        });
    });
    function gettotal(){
        var tablelist = $("#dataTable tbody input[type=checkbox]:checked");

        var segratetotal=0;
        var tratotal=0;

        var intVal = function (i) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '') * 1 :
                typeof i === 'number' ?
                i : 0;
        };
            
        if(tablelist.length>0){
            tablelist.each(function() {
                var row = $(this).closest("tr");
                var segvalue = parseFloat(intVal(row.find('.segtotal').text())) || 0;
                segratetotal += segvalue;

                var netvalue = parseFloat(intVal(row.find('.tratotal').text())) || 0;
                tratotal += netvalue;                
            });

            if(tratotal==segratetotal){
                $('#postingcompletebtn').prop('disabled', false);
                $('#dataTable tfoot tr').removeClass('table-danger');
            }
            else{
                $('#postingcompletebtn').prop('disabled', true);
                $('#dataTable tfoot tr').addClass('table-danger');
            }

            $('#segtotalset').html(addCommas(parseFloat(segratetotal).toFixed(2)));
            $('#tratotalset').html(addCommas(parseFloat(tratotal).toFixed(2)));
        }
        else{
            $('#postingcompletebtn').prop('disabled', true);
            $('#segtotalset').html(addCommas(parseFloat('0').toFixed(2)));
            $('#tratotalset').html(addCommas(parseFloat('0').toFixed(2)));
            $('#dataTable tfoot tr').removeClass('table-danger');
        }
    }

    function getbranchlist(id, value){
        $.ajax({
            type: "POST",
            data: {
                recordID: id
            },
            url: '<?php echo base_url() ?>Payreceivepost/Getbranchaccocompany',
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
