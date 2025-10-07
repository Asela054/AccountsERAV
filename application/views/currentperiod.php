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
	<div class="modal-dialog modal-sm modal-dialog-centered">
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
                    <div class="form-group mb-1">
                        <label class="small font-weight-bold">Finacial Month*</label>
                        <select name="finacialmonth" id="finacialmonth" class="form-control form-control-sm" required>
                            <option value="">Select</option>
                        </select>
                    </div>
                    <div class="form-group mt-3 text-right">
                        <button type="submit" id="submitBtn" class="btn btn-primary btn-sm px-4" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
                    </div>
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

            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: '<?php echo base_url() ?>Currentperiod/Getmonthlistaccoyear',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);
                    var html = '';
                    html += '<option value="">Select</option>';
                    $.each(obj, function (i, item) {
                        html += '<option value="' + obj[i].idtbl_finacial_month + '">';
                        html += obj[i].monthname ;
                        html += '</option>';
                    });
                    $('#finacialmonth').empty().append(html);   
                }
            });
        })
    });
</script>
<?php include "include/footer.php"; ?>
