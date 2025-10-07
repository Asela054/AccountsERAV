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
                            <div class="page-header-icon"><i class="fas fa-cash-register"></i></div>
                            <span>Asset Sell Report</span>
                        </h1>
                    </div>
                </div>
            </div>

            <div class="container-fluid mt-3 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <form id="assetsellreportform">
                            <div class="">

                                <div class="col-12 d-flex">


                                <div class="form-group p-1 col-2">
                                    <label class="small font-weight-bold text-dark">Asset Name*</label>
                                    <select class="form-control form-control-sm" name="asset_name" id="asset_name" required >
                                        <option value="">Select</option>
                                        <?php foreach ($asset_name->result() as $rowasset_name){?>
                                        <option value="<?php echo $rowasset_name->idtbl_asset ?>"><?php echo $rowasset_name->asset_name ?></option>
                                        <?php }?>
                                    </select>
                                </div>

                            
                                    <div class="form-group p-1 col-2">
                                    <label class="small font-weight-bold text-dark">From*</label>
                                        <input type="date" placeholder="dtd" class="form-control form-control-sm" name="from_date" id="from_date">

                                    </div>
                                    <div class="form-group p-1 col-2">
                                    <label class="small font-weight-bold text-dark">To*</label>
                                        <input type="date" placeholder="dtd" class="form-control form-control-sm" name="to_date" id="to_date">


                                    </div>
                                    <div class="form-group p-1 col-2 pt-4">
                                        <button type="button" id="assetsellreportbtn" class="btn btn-primary btn-sm px-4 py-2 mt-2 ml-2 " <?php if ($addcheck == 0) {echo 'disabled';} ?>><i class="fas fa-search"></i>&nbsp;Search</button>
                                    </div>

                                </div>

                            </div>
                        </form>
                        <div class="row mt-2">
                            <table class="table table-striped m-5">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Asset Name</th>
                                        <th scope="col">Date</th>
										<th scope="col">Reason</th>
                                        <th scope="col" class="text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="assetsellreporttbodytbl">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </main>
        <?php include "include/footerbar.php"; ?>
    </div>

    <?php include "include/footerscripts.php"; ?>

<!-- Modal -->
    <!-- <div class="modal fade" id="sellDetailsReportModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="sellDetailsReportModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="sellDetailsReportModalLabel">Asset Sell Report</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="showSellDetails">
				
			</div>
		</div>
	</div>
</div> -->

    <script>
        $(document).ready(function() {
            var addcheck = '<?php echo $addcheck; ?>';
            var editcheck = '<?php echo $editcheck; ?>';
            var statuscheck = '<?php echo $statuscheck; ?>';
            var deletecheck = '<?php echo $deletecheck; ?>';


            $('#assetsellreportform').on('click', '#assetsellreportbtn', function() {

               

                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                var asset_name = $('#asset_name').val();
                
                $.ajax({

                    type: "POST",
                    data: {
                        from_date: from_date,
                        to_date: to_date,
                        asset_name: asset_name
                    },
                    url: '<?php echo base_url() ?>Assetsellreport/selldetailreport',
                    success: function(data) {
                        console.log(data);
                       
                        $('#assetsellreporttbodytbl').html(data);
                    }

                })


            });

        });

        // function action(data) { //alert(data);
        //     var obj = JSON.parse(data);
        //     $.notify({
        //         // options
        //         icon: obj.icon,
        //         title: obj.title,
        //         message: obj.message,
        //         url: obj.url,
        //         target: obj.target
        //     }, {
        //         // settings
        //         element: 'body',
        //         position: null,
        //         type: obj.type,
        //         allow_dismiss: true,
        //         newest_on_top: false,
        //         showProgressbar: false,
        //         placement: {
        //             from: "top",
        //             align: "center"
        //         },
        //         offset: 100,
        //         spacing: 10,
        //         z_index: 1031,
        //         delay: 5000,
        //         timer: 1000,
        //         url_target: '_blank',
        //         mouse_over: null,
        //         animate: {
        //             enter: 'animated fadeInDown',
        //             exit: 'animated fadeOutUp'
        //         },
        //         onShow: null,
        //         onShown: null,
        //         onClose: null,
        //         onClosed: null,
        //         icon_type: 'class',
        //         template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
        //             '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
        //             '<span data-notify="icon"></span> ' +
        //             '<span data-notify="title">{1}</span> ' +
        //             '<span data-notify="message">{2}</span>' +
        //             '<div class="progress" data-notify="progressbar">' +
        //             '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
        //             '</div>' +
        //             '<a href="{3}" target="{4}" data-notify="url"></a>' +
        //             '</div>'
        //     });
        // }

        function deactive_confirm() {
            return confirm("Are you sure you want to deactive this?");
        }

        function active_confirm() {
            return confirm("Are you sure you want to active this?");
        }

        function delete_confirm() {
            return confirm("Are you sure you want to remove this?");
        }
    </script>
    <?php include "include/footer.php"; ?>