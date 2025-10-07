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
                            <div class="page-header-icon"><i data-feather="list"></i></div>
                            <span>Opening Balance</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form action="<?php echo base_url() ?>Openbalance/Openbalanceinsertupdate" method="post" autocomplete="off">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Company*</label>
                                        <select name="company" id="company" class="form-control form-control-sm" required>
                                            <!-- <option value="">Select</option> -->
                                            <?php foreach($companylist as $rowcompanylist){ ?>
                                            <option value="<?php echo $rowcompanylist->idtbl_company ?>"><?php echo $rowcompanylist->company ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Branch*</label>
                                        <select name="branch" id="branch" class="form-control form-control-sm" required>
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Chart of account*</label>
                                        <select name="chartofaccount" id="chartofaccount" class="form-control form-control-sm" required>
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold">opening Balance*</label>
                                        <input type="text" name="openbal" id="openbal" class="form-control form-control-sm text-right input-integer-decimal">
                                    </div>
                                    <div class="form-group mb-1">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="creditdebitbal1" name="creditdebitbal" class="custom-control-input" value="C" required>
                                            <label class="custom-control-label font-weight-bold small" for="creditdebitbal1">Credit Balance</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="creditdebitbal2" name="creditdebitbal" class="custom-control-input" value="D">
                                            <label class="custom-control-label font-weight-bold small" for="creditdebitbal2">Debit Balance</label>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3 text-right">
                                        <button type="submit" id="submitBtn" class="btn btn-primary btn-sm px-4" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">
                                    <input type="hidden" name="accounttype" id="accounttype" value="">
                                </form>
                            </div>
                            <div class="col-9">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Company</th>
                                                <th>Branch</th>
                                                <th>Year</th>
                                                <th>Month</th>
                                                <th>Date</th>
                                                <th>Account No</th>
                                                <th>C/D</th>
                                                <th>Amount</th>
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

        $('.input-integer-decimal').inputNumber({
			maxDecimalDigits: 2,
			thousandSep: ','
		});

        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "<?php echo base_url() ?>scripts/accountopenballist.php",
                type: "POST", // you can use GET
                data: function(d) {
                    d.userID = '<?php echo $_SESSION['userid']; ?>';
                }
            },
            dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            "buttons": [
                { extend: 'csv', className: 'btn btn-success btn-sm', title: 'Customer Information', text: '<i class="fas fa-file-csv mr-2"></i> CSV', },
                { extend: 'pdf', className: 'btn btn-danger btn-sm', title: 'Customer Information', text: '<i class="fas fa-file-pdf mr-2"></i> PDF', },
                { 
                    extend: 'print', 
                    title: 'Customer Information',
                    className: 'btn btn-primary btn-sm', 
                    text: '<i class="fas fa-print mr-2"></i> Print',
                    customize: function ( win ) {
                        $(win.document.body).find( 'table' )
                            .addClass( 'compact' )
                            .css( 'font-size', 'inherit' );
                    }, 
                },
                // 'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_account_open_bal"
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
                    "data": "applydate"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        if(full['detailaccountno']!=null){
                            return full['detailaccountno'];
                        }
                        else{
                            return full['chartaccountno'];
                        }
                    }
                    
                },
                {
                    "data": "creditdebit"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        return addCommas(parseFloat(full['openbal']).toFixed(2));
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';

                        if(deletecheck==1 && full['account_type']==1){
                            button+='<button type="button" data-url="Openbalance/Openbalancestatus/'+full['idtbl_account_open_bal']+'/3" data-actiontype="3" class="btn btn-danger btn-sm text-light btntableaction"><i class="fas fa-trash-alt"></i></button>';
                        }
                        
                        return button;
                    }
                }
            ],
            drawCallback: function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
        // $('#dataTable tbody').on('click', '.btnEdit', function() {
        //     var r = confirm("Are you sure, You want to Edit this ? ");
        //     if (r == true) {
        //         var id = $(this).attr('id');
        //         $.ajax({
        //             type: "POST",
        //             data: {
        //                 recordID: id
        //             },
        //             url: '<?php echo base_url() ?>Openbalance/Openbalanceedit',
        //             success: function(result) { //alert(result);
        //                 var obj = JSON.parse(result);
        //                 $('#recordID').val(obj.id);
        //                 $('#company').val(obj.company);     
        //                 getbranchlist(obj.company, obj.branch);  
        //                 getaccountlist(obj.company, obj.branch, obj.account);                
        //                 $('#openbal').val(obj.openbal);    
        //                 $('input[name="creditdebitbal"][value="'+obj.creditdebit+'"]').prop('checked', true); 

        //                 $('#recordOption').val('2');
        //                 $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
        //             }
        //         });
        //     }
        // });

        // $('#company').change(function(){
        //     var id = $(this).val();
        //     getbranchlist(id, '');
        // });
        // $('#branch').change(function(){
        //     var companyid = $('#company').val();
        //     var branchid = $(this).val();

        //     getaccountlist(companyid, branchid, '');
        // });

        $('#chartofaccount').on('select2:select', function (e) {
            var selectedData = e.params.data;
            // console.log('Full selected data:', selectedData);
            // console.log('Selected Account ID:', selectedData.data.type);
            $('#accounttype').val(selectedData.data.type);
        });

        getbranchlist('<?php echo $_SESSION['companyid'] ?>', '');
        getaccountlist('<?php echo $_SESSION['companyid'] ?>', '<?php echo $_SESSION['branchid'] ?>', '');
    });

    function getbranchlist(id, value){
        $.ajax({
            type: "POST",
            data: {
                recordID: id
            },
            url: '<?php echo base_url() ?>Openbalance/Getbranchaccocompany',
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

    function getaccountlist(companyid, branchid, value){
        // $.ajax({
        //     type: "POST",
        //     data: {
        //         companyid: companyid,
        //         branchid: branchid
        //     },
        //     url: '<?php echo base_url() ?>Openbalance/Getaccountlist',
        //     success: function(result) { //alert(result);
        //         var obj = JSON.parse(result);
        //         var html = '';
        //         html += '<option value="">Select</option>';
        //         $.each(obj, function (i, item) {
        //             html += '<option value="' + obj[i].accountid + '" data-type="'+obj[i].acctype+'">';
        //             html += obj[i].accountname+' - '+obj[i].accountno ;
        //             html += '</option>';
        //         });
        //         $('#chartofaccount').empty().append(html);  
        //         $('#chartofaccount').select2();

        //         if(value!=''){
        //             $('#chartofaccount').val(value);
        //         }
        //     }
        // });
        $("#chartofaccount").select2({
            // dropdownParent: $('#modalsegregation'),
            ajax: {
                url: "<?php echo base_url() ?>Openbalance/Getaccountlist",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term, // search term
                        companyid: companyid,
                        branchid: branchid
                    };
                },
                processResults: function (response) {
                    return {
                        results: response.map(function (item) {
                            return {
                                id: item.id,
                                text: item.text,
                                data: {
                                    type: item.acctype
                                }
                            };
                        })
                    }
                },
                cache: true
            },
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
