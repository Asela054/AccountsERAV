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
                            <span>PNL Headings Setup</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-primary btn-sm" id="btnAddHeading">
                                    <i class="fas fa-plus mr-1"></i>Add Heading
                                </button>
                                <hr>
                            </div>
                            <div class="col-12">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm small" id="tableHeadings">
                                        <thead>
                                            <tr class="section-header">
                                                <th style="width:40%">Heading / Sub-heading</th>
                                                <th>Section</th>
                                                <th style="width:10%">Order</th>
                                                <th class="text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($headings as $heading): ?>
                                            <tr data-id="<?php echo $heading['idtbl_pnl_heading']; ?>">
                                                <td class="font-weight-bold"><?php echo htmlspecialchars($heading['heading_name']); ?></td>
                                                <td><?php echo htmlspecialchars($heading['pnl_section']); ?></td>
                                                <td><?php echo $heading['display_order']; ?></td>
                                                <td class="text-right">
                                                    <button type="button" class="btn btn-primary btn-sm btn-edit-heading"
                                                            data-id="<?php echo $heading['idtbl_pnl_heading']; ?>"
                                                            data-name="<?php echo htmlspecialchars($heading['heading_name']); ?>"
                                                            data-order="<?php echo $heading['display_order']; ?>"><i class="fas fa-pen"></i></button>
                                                    <button type="button" class="btn btn-orange btn-sm btn-add-subheading"
                                                            data-parent="<?php echo $heading['idtbl_pnl_heading']; ?>"
                                                            data-section="<?php echo $heading['pnl_section']; ?>"><i class="fas fa-list"></i></button>
                                                    <button type="button" class="btn btn-danger btn-sm btn-delete-heading"
                                                            data-id="<?php echo $heading['idtbl_pnl_heading']; ?>"><i class="fas fa-trash-alt"></i></button>
                                                </td>
                                            </tr>
                                            <?php foreach ($heading['children'] as $child): ?>
                                            <tr data-id="<?php echo $child['idtbl_pnl_heading']; ?>">
                                                <td class="pl-4">&mdash; <?php echo htmlspecialchars($child['heading_name']); ?></td>
                                                <td><?php echo htmlspecialchars($child['pnl_section']); ?></td>
                                                <td><?php echo $child['display_order']; ?></td>
                                                <td class="text-right">
                                                    <button type="button" class="btn btn-primary btn-sm btn-edit-heading"
                                                            data-id="<?php echo $child['idtbl_pnl_heading']; ?>"
                                                            data-name="<?php echo htmlspecialchars($child['heading_name']); ?>"
                                                            data-order="<?php echo $child['display_order']; ?>"><i class="fas fa-pen"></i></button>
                                                    <button type="button" class="btn btn-danger btn-sm btn-delete-heading"
                                                            data-id="<?php echo $child['idtbl_pnl_heading']; ?>"><i class="fas fa-trash-alt"></i></button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php endforeach; ?>
                                        </tbody>
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
<!-- Add / Edit Heading Modal -->
<div class="modal fade" id="headingModal" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Heading</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idtbl_pnl_heading" value="">
                <input type="hidden" id="parent_id" value="">
                <div class="form-group mb-1">
                    <label class="small font-weight-bold">Heading Name</label>
                    <input type="text" id="heading_name" class="form-control form-control-sm">
                </div>
                <div class="form-group mb-1" id="sectionGroup">
                    <label class="small font-weight-bold">PNL Section</label>
                    <select id="pnl_section" class="form-control form-control-sm">
                        <option value="revenue">Revenue</option>
                        <option value="cost_of_sales">Cost of Sales</option>
                        <option value="operating_expenses">Operating Expenses</option>
                        <option value="other_income">Other Income</option>
                        <option value="finance_costs">Finance Costs</option>
                        <option value="taxes">Taxes</option>
                        <option value="earnings_allocation">Earnings Allocation</option>
                    </select>
                </div>
                <div class="form-group mb-1">
                    <label class="small font-weight-bold">Display Order</label>
                    <input type="number" id="display_order" class="form-control form-control-sm" value="0">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm px-3" id="btnSaveHeading"><i class="fas fa-save mr-2"></i>Save</button>
            </div>
        </div>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        $('#tableHeadings').DataTable();
    });
    $(function(){
        function resetModal(){
            $('#idtbl_pnl_heading').val('');
            $('#parent_id').val('');
            $('#heading_name').val('');
            $('#display_order').val(0);
            $('#sectionGroup').show();
        }

        $('#btnAddHeading').on('click', function(){
            resetModal();
            $('#headingModal').modal('show');
        });

        $('.btn-add-subheading').on('click', function(){
            resetModal();
            $('#parent_id').val($(this).data('parent'));
            $('#pnl_section').val($(this).data('section'));
            $('#sectionGroup').hide(); // sub-heading inherits the parent's section
            $('#headingModal').modal('show');
        });

        $('.btn-edit-heading').on('click', function(){
            resetModal();
            $('#idtbl_pnl_heading').val($(this).data('id'));
            $('#heading_name').val($(this).data('name'));
            $('#display_order').val($(this).data('order'));
            $('#sectionGroup').hide(); // section can't change on edit
            $('#headingModal').modal('show');
        });

        $('#btnSaveHeading').on('click', function(){
            $.post('<?php echo base_url("PnlSetupModule/save_heading"); ?>', {
                idtbl_pnl_heading: $('#idtbl_pnl_heading').val(),
                parent_id: $('#parent_id').val(),
                heading_name: $('#heading_name').val(),
                pnl_section: $('#pnl_section').val(),
                display_order: $('#display_order').val()
            }, function(resp){
                if (resp.success) { location.reload(); }
                else { alert(resp.message || 'Save failed.'); }
            }, 'json');
        });

        $('.btn-delete-heading').on('click', function(){
            if (!confirm('Delete this heading?')) return;
            var id = $(this).data('id');
            $.post('<?php echo base_url("PnlSetupModule/delete_heading"); ?>', { idtbl_pnl_heading: id }, function(resp){
                if (resp.success) { location.reload(); }
                else { alert(resp.message || 'Delete failed.'); }
            }, 'json');
        });
    });
</script>
<?php include "include/footer.php"; ?>
