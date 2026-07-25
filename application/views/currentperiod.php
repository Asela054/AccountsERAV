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
                            <span>Current Period</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Company</th>
                                                <th>Branch</th>
                                                <th>Current Finacial Year</th>
                                                <th>Current Finacial Month</th>
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
<!-- Modal Financial Year -->
<div class="modal fade" id="financialyearmodal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="financialyearmodalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="financialyearmodalLabel">Set Current Period</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="<?php echo base_url() ?>Currentperiod/Currentperiodinsertupdate" method="post">
                    <div class="form-group mb-1">
                        <label class="small font-weight-bold">Finacial Year*</label>
                        <select name="finacialyear" id="finacialyear" class="form-control form-control-sm" required>
                            <option value="">Select</option>
                            <?php foreach($fiancialyear->result() as $rowfiancialyear){ ?>
                            <option value="<?php echo $rowfiancialyear->idtbl_finacial_year ?>"><?php echo $rowfiancialyear->desc ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <!-- <div class="form-group mb-1">
                        <label class="small font-weight-bold">Finacial Month*</label>
                        <select name="finacialmonth" id="finacialmonth" class="form-control form-control-sm" required>
                            <option value="">Select</option>
                        </select>
                    </div> -->
                    <h6 class="title-style small font-weight-bold mt-2"><span>Month List</span></h6>
                    <table class="table table-bordered table-striped table-sm small nowrap" id="finacialmonthlist">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" class="text-center">No Data</td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- <div class="form-group mt-3 text-right">
                        <button type="submit" id="submitBtn" class="btn btn-primary btn-sm px-4" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
                    </div> -->
                    <input type="hidden" name="companyid" id="companyid">
                    <input type="hidden" name="branchid" id="branchid">
                </form>
			</div>
		</div>
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
            ajax: {
                url: "<?php echo base_url() ?>scripts/currentfinacialyearmonthlist.php",
                type: "POST", // you can use GET
                data: function(d) {
                    d.userID = '<?php echo $_SESSION['userid']; ?>';
                }
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_company_branch"
                },
                {
                    "data": "company"
                },
                {
                    "data": "branch"
                },
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function(data, type, full) {
                        if(full['desc']!=null){return full['desc'];}
                        else{return '';}
                    }
                },
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function(data, type, full) {
                        if(full['monthname']!=null){return full['monthname'];}
                        else{return '';}
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        if(addcheck==1){
                            button+='<button class="btn btn-primary btn-sm btnYear" id="'+full['idtbl_finacial_year']+'" data-conpanyid="'+full['idtbl_company']+'" data-branchid="'+full['idtbl_company_branch']+'"><i class="fas fa-sync-alt"></i></button>';
                        }
                        
                        return button;
                    }
                }
            ],
            drawCallback: function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
        $('#dataTable tbody').on('click', '.btnYear', async function() {
            var r = await Otherconfirmation("You want to change current period ? ");
            if (r == true) {
                var companyid = $(this).attr("data-conpanyid");
                var branchid = $(this).attr("data-branchid");
                
                $('#companyid').val(companyid);
                $('#branchid').val(branchid);

                $('#financialyearmodal').modal('show');
            }
        });
        $('#finacialyear').change(function(){
            var id = $(this).val();

            Getmonthlistaccoyear(id);
        });

        $(document).on('change', '.custom-control-input', async function(){
            var finacialmonth=$(this).attr('id');
            var monthstatus=$(this).val();
            var status=0;
            if($(this).prop('checked')==true){
                status=1;
            }
            else{
                status=2;
            }
            var finacialyear=$('#finacialyear').val();

            if(monthstatus==0){var r = await Otherconfirmation("You want to change current period ? ");}
            else if(monthstatus==2){var r = await Otherconfirmation("You want to open this month ? ");}
            else if(monthstatus==3){var r = await Otherconfirmation("You want to close this month ? ");}
            
            if (r == true) {
                $.ajax({
                    type: "POST",
                    data: {
                        status: status,
                        monthstatus: monthstatus,
                        finacialmonth: finacialmonth,
                        finacialyear: finacialyear
                    },
                    url: '<?php echo base_url() ?>Currentperiod/Currentperiodinsertupdate',
                    success: function(result) { //alert(result);
                        Getmonthlistaccoyear(finacialyear);

                        var obj = JSON.parse(result);
                        if (obj.status == 1) {
                            action(obj.action);
                        }
                        else{
                            action(obj.action);
                        }
                    },
                    error: function(error) {
                        $(this).prop('checked', !$(this).prop('checked'));
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
            }
            else{
                $(this).prop('checked', !$(this).prop('checked'));
            }
        });

        $('#financialyearmodal').on('hidden.bs.modal', function () {
            window.location.reload();
        });
    });

    function Getmonthlistaccoyear(id){
        $.ajax({
            type: "POST",
            data: {
                recordID: id
            },
            url: '<?php echo base_url() ?>Currentperiod/Getmonthlistaccoyear',
            success: function(result) { //alert(result);
                var obj = JSON.parse(result);
                var html = '';
                $.each(obj, function (i, item) {
                    html += '<tr>';
                    html += '<td>'+obj[i].monthname+'</td>';
                    if(obj[i].activestatus==1){
                        html += '<td class="text-center"><span class="badge badge-success px-3">Active</span></td>';
                        // html += '<td class="text-center"><button class="btn btn-sm btn-danger btnDeactive" id="'+obj[i].idtbl_finacial_month+'"><i class="fas fa-times"></i></button></td>';
                        html += '<td class="text-center"><div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" disabled id="'+obj[i].idtbl_finacial_month+'" value="1" checked><label class="custom-control-label" for="'+obj[i].idtbl_finacial_month+'">&nbsp;</label></div></td>';
                    }
                    else if(obj[i].activestatus==2){
                        html += '<td class="text-center"><span class="badge badge-danger px-3">Closed</span></td>';
                        html += '<td class="text-center"><div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="'+obj[i].idtbl_finacial_month+'" value="2"><label class="custom-control-label" for="'+obj[i].idtbl_finacial_month+'">&nbsp;</label></div></td>';
                    }
                    else if(obj[i].activestatus==3){
                        html += '<td class="text-center"><span class="badge badge-warning px-3">Opened</span></td>';
                        html += '<td class="text-center"><div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="'+obj[i].idtbl_finacial_month+'" value="3" checked><label class="custom-control-label" for="'+obj[i].idtbl_finacial_month+'">&nbsp;</label></div></td>';
                    }
                    else{
                        html += '<td class="text-center"><span class="badge badge-secondary px-3">Pending</span></td>';
                        html += '<td class="text-center"><div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="'+obj[i].idtbl_finacial_month+'" value="0"><label class="custom-control-label" for="'+obj[i].idtbl_finacial_month+'">&nbsp;</label></div></td>';
                    }
                    html += '</tr>';   
                });
                $('#finacialmonthlist tbody').empty().append(html);
            }
        });
    }
</script>
<?php include "include/footer.php"; ?>
