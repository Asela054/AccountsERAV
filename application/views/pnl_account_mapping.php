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
                            <span>Assign Accounts to PNL Headings</span>
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
                                    <table class="table table-bordered table-striped table-sm small" id="tableMapping">
                                        <thead>
                                            <tr class="section-header">
                                                <th>Account</th>
                                                <th>Chart Category</th>
                                                <th style="width:30%">PNL Heading</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($accounts as $acc): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($acc['account_display']); ?></td>
                                                <td><?php echo htmlspecialchars($acc['category']); ?></td>
                                                <td>
                                                    <select class="form-control form-control-sm select-heading" data-account="<?php echo $acc['idtbl_account']; ?>">
                                                        <option value="">-- Not mapped --</option>
                                                        <?php foreach ($heading_options as $hid => $hname): ?>
                                                        <option value="<?php echo $hid; ?>" <?php echo ($acc['mapped_heading_id'] == $hid) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($hname); ?>
                                                        </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                            </tr>
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
<?php include "include/footerscripts.php"; ?>
<script>
$(function(){
    // $('#tableMapping').DataTable();
    $('.select-heading').on('change', function(){
        var $row = $(this);
        console.log($row);
        
        $.post('<?php echo base_url("PnlSetupModule/save_mapping"); ?>', {
            account_id: $row.data('account'),
            heading_id: $row.val()
        }, function(resp){
            if (!resp.success) { alert(resp.message || 'Save failed.'); }
        }, 'json');
    });
});
</script>
