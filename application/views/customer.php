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
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i class="fas fa-users"></i></div>
                            <span>Customer</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <form action="<?php echo base_url() ?>Customer/Customerinsertupdate"enctype="multipart/form-data" method="post" autocomplete="off">
                            <div class="form-row mb-1">
                                <div class="col-3">
                                    <label class="small font-weight-bold">Registered Name of the Company*</label>
                                    <input type="text" class="form-control form-control-sm" name="customer_name" id="customer_name" required>
                                </div>
                                <div class="col-3">
                                    <label class="small font-weight-bold">Business registration No *</label>
                                    <input type="text" class="form-control form-control-sm" name="business_regno" id="business_regno">
                                </div>
                                <div class="col-2">
                                    <label class="small font-weight-bold">Reference No</label>
                                    <input type="text" class="form-control form-control-sm" name="ref_no" id="ref_no">
                                </div>
                                <div class="col-2">
                                    <label class="small font-weight-bold">VAT Reg Type*</label>
                                    <select class="form-control form-control-sm  px-0" name="vat_customer"
                                        id="vat_customer" required>
                                        <option value="">Select VAT Type</option>
                                        <option value="0">Non VAT</option>
                                        <option value="1">VAT</option>
                                        <option value="2">SVAT</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <label class="small font-weight-bold">VAT Registration No*</label>
                                    <input type="text" class="form-control form-control-sm" name="vatno" id="vatno" readonly>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col-2">
                                    <label class="small font-weight-bold">NBT Registration No*</label>
                                    <input type="text" class="form-control form-control-sm" name="nbtno" id="nbtno">
                                </div>
                                <div class="col-2">
                                    <label class="small font-weight-bold">SVAT Registration No*</label>
                                    <input type="text" class="form-control form-control-sm" name="svatno" id="svatno" readonly>
                                </div>
                                <div class="col-2">
                                    <label class="small font-weight-bold">Telephone No*</label>
                                    <input type="number" class="form-control form-control-sm" name="telephoneno" id="telephoneno">
                                </div>
                                <div class="col-2">
                                    <label class="small font-weight-bold">FAX No*</label>
                                    <input type="text" class="form-control form-control-sm" name="faxno" id="faxno">
                                </div>
                                <div class="col-2">
                                    <label class="small font-weight-bold ">Company*</label>
                                    <input type="text" id="f_company_name" name="f_company_name" value="<?php echo $_SESSION['company'] ?>" class="form-control form-control-sm" required readonly>
                                </div>
                                <div class="col-2">
                                    <label class="small font-weight-bold ">Company Branch*</label>
                                    <input type="text" id="f_branch_name" name="f_branch_name" value="<?php echo $_SESSION['branch'] ?>" class="form-control form-control-sm" required readonly>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-4">
                                    <h6 class="title-style small"><span>Business Address*</span></h6>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Address Line 1*</label>
                                        <input type="text" class="form-control form-control-sm" name="line1" id="line1" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Address Line 2*</label>
                                        <input type="text" class="form-control form-control-sm" name="line2" id="line2" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">City*</label>
                                        <input type="text" class="form-control form-control-sm" name="city" id="city">
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">State*</label>
                                        <input type="text" class="form-control form-control-sm" name="state" id="state">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <h6 class="small title-style"><span>Delivery Address*</span></h6>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Delivery Address Line 1*</label>
                                        <input type="text" class="form-control form-control-sm" name="dline1" id="dline1" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Delivery Address Line 2*</label>
                                        <input type="text" class="form-control form-control-sm" name="dline2" id="dline2" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">City*</label>
                                        <input type="text" class="form-control form-control-sm" name="dcity" id="dcity">
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">State*</label>
                                        <input type="text" class="form-control form-control-sm" name="dstate" id="dstate">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <!-- <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Submit copy of BR Cetificate</label>
                                        <input type="file" class="form-control form-control-sm" name="image" style="padding-bottom:32px;" />
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Submit copy of VAT,NBT,SVAT Cetificate</label>
                                        <input class="form-control form-control-sm" id="certificates" name="cretificates" type="file" class="file" style="padding-bottom:32px;" />
                                    </div> -->
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Business Status*</label><br>
                                        <input type="radio" id="Proprietorship" name="bstatus" value="Proprietorship" required>
                                        <label for="Proprietorship">Proprietorship</label>
                                        <input type="radio" id="bstatusPartnership" name="bstatus" value="Partnership" required>
                                        <label for="bstatusPartnership">Partnership</label>
                                        <input type="radio" id="bstatusIncorporation" name="bstatus" value="Incorporation" required>
                                        <label for="bstatusIncorporation">Incorporation</label>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Method of Payment*</label><br>
                                        <input type="radio" id="cashpayementmethod" name="payementmethod" value="Cash" required>
                                        <label for="cashpayementmethod">Cash</label>
                                        <input type="radio" id="bankpayementmethod" name="payementmethod" value="Bank" required>
                                        <label for="bankpayementmethod">Bank</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 text-right">
                                    <hr>
                                    <?php if($addcheck==1){ ?>
                                    <button type="submit" id="submitBtn" class="btn btn-primary btn-sm px-5"><i class="far fa-save"></i>&nbsp;Add</button>
                                    <?php } ?>
                                </div>
                            </div>
                            <input type="hidden" name="recordOption" id="recordOption" value="1">
                            <input type="hidden" name="recordID" id="recordID" value="">
                        </form>
                        <div class="row mt-3">
                            <div class="col-12">
                                <hr>
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="tblcustomer">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Reference No</th>
                                                <th>BR No</th>
                                                <th>VAT No</th>
                                                <th>NBT No</th>
                                                <th>SVAT No</th>
                                                <th>Address</th>
                                                <th>City</th>
                                                <th class="text-right"></th>
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
<!-- Modal Image View -->
<div class="modal fade" id="modalimageview" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 text-center">
                        <div id="imagelist" class=""></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Customer Bank Details -->
<div class="modal fade" id="customerBankModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="customerBankModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h6>Customer bank information</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form autocomplete="off" id="formbank">
                            <div class="form-row">
                                <div class="col-3">
                                    <label class="small font-weight-bold">Bank Name*</label>
                                    <input type="text" class="form-control form-control-sm" name="bank" id="bank" required>
                                </div>
                                <div class="col-3">
                                    <label class="small font-weight-bold">Branch*</label>
                                    <input type="text" class="form-control form-control-sm" name="branch" id="branch" required>
                                </div>
                                <div class="col-3">
                                    <label class="small font-weight-bold">Account No*</label>
                                    <input type="text" class="form-control form-control-sm" name="accno" id="accno" required>
                                </div>
                                <div class="col-3">
                                    <label class="small font-weight-bold">Name*</label>
                                    <input type="text" class="form-control form-control-sm" name="accname" id="accname" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mt-2 text-right">
                                        <button type="button" id="submiBanktBtn" class="btn btn-primary btn-sm px-4"><i class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="recordOption" id="recordOptionBank" value="1">
                            <input type="hidden" name="recordID" id="recordIDBank" value="">
                            <input type="hidden" name="customerid" id="customerid" value="">
                            <input type="submit" id="hidebanksubmit" class="d-none">
                            <input type="reset" id="hidebankreset" class="d-none">
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <hr>
                        <div class="scrollbar pb-3" id="style-2">
                            <table class="table table-bordered table-striped table-sm nowrap w-100" id="tblcustomerbank">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Bank</th>
                                        <th>Branch</th>
                                        <th>Account No</th>
                                        <th>Name</th>
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
</div>
<!-- Modal Customer Contact Details -->
<div class="modal fade" id="customerContactModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="customerContactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h6>Customer contact information</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form autocomplete="off" id="formcontact">
                            <div class="form-row">
                                <div class="col-3">
                                    <label class="small font-weight-bold"> Name*</label>
                                    <input type="text" class="form-control form-control-sm" name="name" id="name" required>
                                </div>
                                <div class="col-3">
                                    <label class="small font-weight-bold">Position*</label>
                                    <input type="text" class="form-control form-control-sm" name="postion" id="postion" required>
                                </div>
                                <div class="col-3">
                                    <label class="small font-weight-bold">Mobile No*</label>
                                    <input type="text" class="form-control form-control-sm" name="mobileno" id="mobileno" required>
                                </div>
                                <div class="col-3">
                                    <label class="small font-weight-bold">Email*</label>
                                    <input type="email" class="form-control form-control-sm" name="email" id="email" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mt-2 text-right">
                                        <button type="button" id="submitContactBtn" class="btn btn-primary btn-sm px-4"><i class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="recordOption" id="recordOptionContact" value="1">
                            <input type="hidden" name="recordID" id="recordIDContact" value="">
                            <input type="hidden" name="customerid" id="customeridContact" value="">
                            <input type="submit" id="hidecontactsubmit" class="d-none">
                            <input type="reset" id="hidecontactreset" class="d-none">
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <hr>
                        <div class="scrollbar pb-3" id="style-2">
                            <table class="table table-bordered table-striped table-sm nowrap w-100" id="tblcustomercontact">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Mobile No</th>
                                        <th>Email</th>
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
</div>
<?php include "include/footerscripts.php"; ?>
<script>
$(document).ready(function() {
    var addcheck = '<?php echo $addcheck; ?>';
    var editcheck = '<?php echo $editcheck; ?>';
    var statuscheck = '<?php echo $statuscheck; ?>';
    var deletecheck = '<?php echo $deletecheck; ?>';

    $('#tblcustomer').DataTable({
        "destroy": true,
        "processing": true,
        "serverSide": true,
        dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        responsive: true,
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, 'All'],
        ],
        "buttons": [{
                extend: 'csv',
                className: 'btn btn-success btn-sm',
                title: 'Customer  Information',
                text: '<i class="fas fa-file-csv mr-2"></i> CSV',
            },
            { 
                extend: 'pdf', 
                className: 'btn btn-danger btn-sm', 
                title: 'Location Information', 
                text: '<i class="fas fa-file-pdf mr-2"></i> PDF',
                orientation: 'landscape', 
                pageSize: 'legal', 
                customize: function(doc) {
                    doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                }
            },
            {
                extend: 'print',
                title: 'Customer  Information',
                className: 'btn btn-primary btn-sm',
                text: '<i class="fas fa-print mr-2"></i> Print',
                customize: function(win) {
                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');
                },
            },
            // 'copy', 'csv', 'excel', 'pdf', 'print'
        ],

        ajax: {
            url: "<?php echo base_url() ?>scripts/customerlist.php",
            type: "POST", // you can use GET
        },
        "order": [
            [0, "desc"]
        ],
        "columns": [
            // {
            //     "data": null,
            //     "render": function(data, type, full, meta) {
            //         return meta.settings._iRecordsDisplay - meta.row;
            //     }
            // },
            {
                "data": null,
                "render": function(data, type, full, meta) {
                    return meta.row + 1 + meta.settings._iDisplayStart;
                }
            },
            {
                "data": "customer"
            },
            {
                "data": "ref_no"
            },
            {
                "data": "bus_reg_no"
            },
            {
                "data": "vat_no"
            },
            {
                "data": "nbt_no"
            },
            {
                "data": "svat_no"
            },
            {
                "targets": [4],
                "render": function(data, type, row) {
                    return row.address_line1 + ',' + row.address_line2 + '';
                }
            },
            {
                "data": "city"
            },
            {
                "targets": -1,
                "className": 'text-right',
                "data": null,
                "render": function(data, type, full) {
                    var button = '';
                    // button += '<button class="btn btn-dark btn-sm btnimg mr-1 '; if (editcheck != 1) { button += 'd-none'; } button += '" id="' + full['idtbl_customer'] + '" data-toggle="tooltip" title="View Image"><i class="fas fa-image"></i></button>';
                    // button += '<button type="button" href="<?php // echo base_url() ?>Customerbank/index/' + full['idtbl_customer'] + '" target="_self" class="btn btn-secondary btn-sm mr-1"><i class="fas fa-university"></i></button>';
                    // button += '<a href="<?php // echo base_url() ?>Newcustomerjobs/index/' + full['idtbl_customer'] + '" target="_self" class="btn btn-info btn-sm mr-1"><i class="fas fa-file"></i></a>';
                    // button += '<button type="button" class="btn btn-secondary btn-sm mr-1 btnbank" id="'+full['idtbl_customer']+'" data-toggle="tooltip" title="Bank Info"><i class="fas fa-university"></i></button>';
                    // button += '<button type="button" class="btn btn-info btn-sm mr-1 btncontact" id="'+full['idtbl_customer']+'" data-toggle="tooltip" title="Contact Info"><i class="fas fa-phone"></i></button>';

                    if(editcheck==1){
                        button+='<button type="button" class="btn btn-primary btn-sm btnEdit mr-1" id="'+full['idtbl_customer']+'" data-toggle="tooltip" title="Edit"><i class="fas fa-pen"></i></button>';
                    }
                    if(full['status']==1 && statuscheck==1){
                        button+='<button type="button" data-url="Customer/Customerstatus/'+full['idtbl_customer']+'/2" data-toggle="tooltip" title="Active" data-actiontype="2" class="btn btn-success btn-sm mr-1 btntableaction"><i class="fas fa-check"></i></button>';
                    }else if(full['status']==2 && statuscheck==1){
                        button+='<button type="button" data-url="Customer/Customerstatus/'+full['idtbl_customer']+'/1" data-toggle="tooltip" title="Deactive" data-actiontype="1" class="btn btn-warning btn-sm mr-1 text-light btntableaction"><i class="fas fa-times"></i></button>';
                    }
                    if(deletecheck==1){
                        button+='<button type="button" data-url="Customer/Customerstatus/'+full['idtbl_customer']+'/3" data-toggle="tooltip" title="Delete" data-actiontype="3" class="btn btn-danger btn-sm text-light btntableaction"><i class="fas fa-trash-alt"></i></button>';
                    }

                    return button;
                }
            }
        ],
        drawCallback: function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        },
    });
    $('#tblcustomer tbody').on('click', '.btnEdit', async function() {
        var r = await Otherconfirmation("You want to Edit this ? ");
        if (r == true) {
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: '<?php echo base_url() ?>Customer/Customeredit',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);
                    $('#recordID').val(obj.id);
                    $('#customer_name').val(obj.name);
                    $('#business_regno').val(obj.business_regno);
                    $('#nbtno').val(obj.nbtno);
                    $('#svatno').val(obj.svatno);
                    $('#vat_customer').val(obj.vat_customer).trigger('change');
                    $('#telephoneno').val(obj.telephoneno);
                    $('#faxno').val(obj.faxno);
                    $('#dline1').val(obj.dline1);
                    $('#dline2').val(obj.dline2);
                    $('#dcity').val(obj.dcity);
                    $('#dstate').val(obj.dstate);
                    $('#line1').val(obj.line1);
                    $('#line2').val(obj.line2);
                    $('#city').val(obj.city);
                    $('#state').val(obj.state);
                    $('#ref_no').val(obj.ref_no);
                    // $('#country').val(obj.country);
                    $('#vatno').val(obj.vat_no);
                    //$('#bstatus').val(obj.business_status);
                    // $('#payementmethod').val(obj.payementmethod);

                    var payementmethod = obj.payementmethod;
                    //alert(busstatus);
                    if (payementmethod == "Cash") {
                        $('#cashpayementmethod').prop('checked', true);

                    } else {
                        $('#bankpayementmethod').prop('checked', true);
                    }
                    // $('#nic').val(obj.nic);
                    var busstatus = obj.business_status;
                    //alert(busstatus);
                    if (busstatus == "Proprietorship") {
                        $('#Proprietorship').prop('checked', true);

                    } else if (busstatus == "Partnership") {
                        $('#bstatusPartnership').prop('checked', true);
                    } else {
                        $('#bstatusIncorporation').prop('checked', true);
                    }
                    $('#recordOption').val('2');
                    $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                }
            });
        }
    });
    $('#tblcustomer tbody').on('click', '.btnimg', function() {
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
                    url: '<?php echo base_url() ?>Customer/Expenseseimageview',
                    success: function(result) {
                        $('#imagelist').html(result);
                        $('#modalimageview').modal('show');
                        Swal.close();
                        document.body.style.overflow = 'auto';
                    },
                    error: function(error) {
                        // Close the SweetAlert on error
                        Swal.close();
                        document.body.style.overflow = 'auto';
                        
                        // Show an error alert
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong. Please try again later.'
                        });
                    }
                });
            }
        });  
    });
    $('#tblcustomer tbody').on('click', '.btnbank', function() {
        var id = $(this).attr('id');
        $('#customerid').val(id);
        loadCustomerBank();
        $('#customerBankModal').modal('show');
    });
    $('#tblcustomer tbody').on('click', '.btncontact', function() {
        var id = $(this).attr('id');
        $('#customeridContact').val(id);
        loadCustomerContact();
        $('#customerContactModal').modal('show');
    });
    $('#submiBanktBtn').click(function(){
        if (!$("#formbank")[0].checkValidity()) {
            // If the form is invalid, submit it. The form won't actually submit;
            // this will just cause the browser to display the native HTML5 error messages.
            $("#hidebanksubmit").click();
        } else {
            var formData = $('#formbank').serialize();
            
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
                        data: formData,
                        url: '<?php echo base_url() ?>Customerbank/Customerbankinsertupdate',
                        success: function(result) {
                            var obj = JSON.parse(result);
                            if(obj.status==1){
                                $('#hidebankreset').click();
                                $('#tblcustomerbank').DataTable().ajax.reload( null, false );
                            }
                            Swal.close();
                            document.body.style.overflow = 'auto';

                            action(obj.action);
                        },
                        error: function(error) {
                            // Close the SweetAlert on error
                            Swal.close();
                            document.body.style.overflow = 'auto';
                            
                            // Show an error alert
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong. Please try again later.'
                            });
                        }
                    });
                }
            }); 
        }
    });
    $('#submitContactBtn').click(function(){
        if (!$("#formcontact")[0].checkValidity()) {
            // If the form is invalid, submit it. The form won't actually submit;
            // this will just cause the browser to display the native HTML5 error messages.
            $("#hidecontactsubmit").click();
        } else {
            var formData = $('#formcontact').serialize();
            
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
                        data: formData,
                        url: '<?php echo base_url() ?>Customercontact/Customercontactinsertupdate',
                        success: function(result) {
                            var obj = JSON.parse(result);
                            if(obj.status==1){
                                $('#hidecontactreset').click();
                                $('#tblcustomercontact').DataTable().ajax.reload( null, false );
                            }
                            Swal.close();
                            document.body.style.overflow = 'auto';

                            action(obj.action);
                        },
                        error: function(error) {
                            // Close the SweetAlert on error
                            Swal.close();
                            document.body.style.overflow = 'auto';
                            
                            // Show an error alert
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong. Please try again later.'
                            });
                        }
                    });
                }
            }); 
        }
    });
    $('#vat_customer').change(function(){
        if($(this).val()==1){
            $('#svatno').prop('readonly', true);
            $('#vatno').prop('readonly', false);
        }
        else if($(this).val()==2){
            $('#svatno').prop('readonly', false);
            $('#vatno').prop('readonly', false);
        }
        else{
            $('#svatno').prop('readonly', true);
            $('#vatno').prop('readonly', true);
        }
    });
});

function loadCustomerBank(){
    var addcheck = '<?php echo $addcheck; ?>';
    var editcheck = '<?php echo $editcheck; ?>';
    var statuscheck = '<?php echo $statuscheck; ?>';
    var deletecheck = '<?php echo $deletecheck; ?>';

    $('#tblcustomerbank').DataTable({
        "destroy": true,
        "processing": true,
        "serverSide": true,
        dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        responsive: true,
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, 'All'],
        ],
        "buttons": [
            { extend: 'csv', className: 'btn btn-success btn-sm', title: 'Customer Bank Information', text: '<i class="fas fa-file-csv mr-2"></i> CSV', },
            { extend: 'pdf', className: 'btn btn-danger btn-sm', title: 'Customer Bank Information', text: '<i class="fas fa-file-pdf mr-2"></i> PDF', },
            { 
                extend: 'print', 
                title: 'Customer Bank Information',
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
        
        ajax: {
            url: "<?php echo base_url() ?>scripts/customerbanklist.php",
            type: "POST", // you can use GET
            data: function ( d ) {
                return $.extend( {}, d, {
                    "customer": $("#customerid").val()
                } );
            }
        },
        "order": [[ 0, "desc" ]],
        "columns": [
            // {
            //     "data": "idtbl_customer_bank_details"
            // },
            {
                "data": null,
                "render": function(data, type, full, meta) {
                    return meta.row + 1 + meta.settings._iDisplayStart;
                }
            },
            {
                "data": "bank_name"
            },
            {
                "data": "branch"
            },
            {
                "data": "account_no"
            },
            {
                "data": "account_name"
            },
            {
                "targets": -1,
                "className": 'text-right',
                "data": null,
                "render": function(data, type, full) {
                    var button='';

                    if(editcheck==1){
                        button+='<button type="button" class="btn btn-primary btn-sm btnEdit mr-1" id="'+full['idtbl_customer_bank_details']+'" data-toggle="tooltip" title="Edit"><i class="fas fa-pen"></i></button>';
                    }
                    if(full['status']==1 && statuscheck==1){
                        button+='<button type="button" data-url="Customerbank/Customerbankstatus/'+full['idtbl_customer_bank_details']+'/2" data-toggle="tooltip" title="Active" data-actiontype="2" class="btn btn-success btn-sm mr-1 btntableactionnoreload"><i class="fas fa-check"></i></button>';
                    }else if(full['status']==2 && statuscheck==1){
                        button+='<button type="button" data-url="Customerbank/Customerbankstatus/'+full['idtbl_customer_bank_details']+'/1" data-toggle="tooltip" title="Deactive" data-actiontype="1" class="btn btn-warning btn-sm mr-1 text-light btntableactionnoreload"><i class="fas fa-times"></i></button>';
                    }
                    if(deletecheck==1){
                        button+='<button type="button" data-url="Customerbank/Customerbankstatus/'+full['idtbl_customer_bank_details']+'/3" data-toggle="tooltip" title="Delete" data-actiontype="3" class="btn btn-danger btn-sm text-light btntableactionnoreload"><i class="fas fa-trash-alt"></i></button>';
                    }
                    
                    return button;
                }
            }
        ],
        drawCallback: function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
    $('#tblcustomerbank tbody').on('click', '.btnEdit', async function() {
        var r = await Otherconfirmation("You want to Edit this ? ");
        if (r == true) {
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: '<?php echo base_url() ?>Customerbank/Customerbankedit',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);
                    $('#recordIDBank').val(obj.id);
                    $('#bank').val(obj.name); 
                    $('#branch').val(obj.branch); 
                    $('#accno').val(obj.account_no); 
                    $('#accname').val(obj.account_name); 

                    $('#recordOptionBank').val('2');
                    $('#submiBanktBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                }
            });
        }
    });
}

function loadCustomerContact(){
    var addcheck = '<?php echo $addcheck; ?>';
    var editcheck = '<?php echo $editcheck; ?>';
    var statuscheck = '<?php echo $statuscheck; ?>';
    var deletecheck = '<?php echo $deletecheck; ?>';

    $('#tblcustomercontact').DataTable({
        "destroy": true,
        "processing": true,
        "serverSide": true,
        dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        responsive: true,
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, 'All'],
        ],
        "buttons": [
            { extend: 'csv', className: 'btn btn-success btn-sm', title: 'Customer Contact Information', text: '<i class="fas fa-file-csv mr-2"></i> CSV', },
            { extend: 'pdf', className: 'btn btn-danger btn-sm', title: 'Customer Contact Information', text: '<i class="fas fa-file-pdf mr-2"></i> PDF', },
            { 
                extend: 'print', 
                title: 'Customer Contact Information',
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
        ajax: {
            url: "<?php echo base_url() ?>scripts/customercontactlist.php",
            type: "POST", // you can use GET
            data: function ( d ) {
                return $.extend( {}, d, {
                    "customer": $("#customeridContact").val()
                });
            }
        },
        "order": [[ 0, "desc" ]],
        "columns": [
            // {
            //     "data": "idtbl_customer_contact_details"
            // },
            {
                "data": null,
                "render": function(data, type, full, meta) {
                    return meta.row + 1 + meta.settings._iDisplayStart;
                }
            },
            {
                "data": "name"
            },
            {
                "data": "position"
            },
            {
                "data": "mobile_no"
            },
            {
                "data": "email"
            },
            {
                "targets": -1,
                "className": 'text-right',
                "data": null,
                "render": function(data, type, full) {
                    var button='';

                    if(editcheck==1){
                        button+='<button type="button" class="btn btn-primary btn-sm btnEdit mr-1" id="'+full['idtbl_customer_contact_details']+'" data-toggle="tooltip" title="Edit"><i class="fas fa-pen"></i></button>';
                    }
                    if(full['status']==1 && statuscheck==1){
                        button+='<button type="button" data-url="Customercontact/Customercontactstatus/'+full['idtbl_customer_contact_details']+'/2" data-toggle="tooltip" title="Active" data-actiontype="2" class="btn btn-success btn-sm mr-1 btntableactionnoreload"><i class="fas fa-check"></i></button>';
                    }else if(full['status']==2 && statuscheck==1){
                        button+='<button type="button" data-url="Customercontact/Customercontactstatus/'+full['idtbl_customer_contact_details']+'/1" data-toggle="tooltip" title="Deactive" data-actiontype="1" class="btn btn-warning btn-sm mr-1 text-light btntableactionnoreload"><i class="fas fa-times"></i></button>';
                    }
                    if(deletecheck==1){
                        button+='<button type="button" data-url="Customercontact/Customercontactstatus/'+full['idtbl_customer_contact_details']+'/3" data-toggle="tooltip" title="Delete" data-actiontype="3" class="btn btn-danger btn-sm text-light btntableactionnoreload"><i class="fas fa-trash-alt"></i></button>';
                    }
                    
                    return button;
                }
            }
        ],
        drawCallback: function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
    $('#tblcustomercontact tbody').on('click', '.btnEdit', async function() {
        var r = await Otherconfirmation("You want to Edit this ? ");
        if (r == true) {
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: '<?php echo base_url() ?>Customercontact/Customercontactedit',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);
                    $('#recordIDContact').val(obj.id);
                    $('#name').val(obj.name); 
                    $('#postion').val(obj.position); 
                    $('#mobileno').val(obj.mobile); 
                    $('#email').val(obj.email); 

                    $('#recordOptionContact').val('2');
                    $('#submitContactBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                }
            });
        }
    });
}

$(document).on("click", ".btntableactionnoreload", async function () {
    var url = '<?php echo base_url() ?>'+$(this).attr("data-url");
    var actiontype = $(this).attr("data-actiontype");
    var datareturn = await noReloadAjaxControl(url, actiontype);
    var tableId = $(this).closest("table").attr("id");

    var obj = JSON.parse(datareturn);
    if(obj.status==1){
        $('#hidebankreset').click();
        $('#'+tableId).DataTable().ajax.reload( null, false );
    }

    action(obj.action);
});
</script>
<?php include "include/footer.php"; ?>