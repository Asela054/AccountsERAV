<?php 
$balancetotal=0;
?>
<div class="col-12 text-right">
    <button type="button" id="btnpdfconvert" class="btn btn-danger btn-sm px-4 mb-3"><i class="fas fa-file-pdf mr-2"></i>PDF</button>
</div>
<div class="col-12">
    <h6 class="title-style small font-weight-bold mt-2"><span><?php echo $_SESSION['company'] ?> - <?php echo $debtorname; ?> Statement <?php echo $report_duration; ?></span></h6>
    <table class="table table-striped table-bordered table-sm small" id="tablereport">
        <thead>
            <tr>
                <th>DATE</th>
                <th>REF NO</th>
                <th>DESCRIPTION</th>
                <th class="text-right">DR</th>
                <th class="text-right">CR</th>
                <th class="text-right">BALANCE</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th colspan="5">Opening Balnace</th>
                <th class="text-right"><?php $balancetotal=$reportopenbalance->row(0)->openbalance; echo number_format($reportopenbalance->row(0)->openbalance, 2); ?></th>
            </tr>
            <?php foreach($reportdata->result() as $rowdata){ ?>
            <tr>
                <td><?php echo $rowdata->invpaydate ?></td>
                <td><?php echo $rowdata->receiptno ?></td>
                <td><?php echo $rowdata->narration; if($rowdata->tratype=='C'){echo ' ('.$rowdata->chequeno.' - '.$rowdata->chequedate.')';} ?></td>
                <?php if($rowdata->tratype=='D'){ ?>
                <td class="text-right"><?php $balancetotal+=$rowdata->amount; echo number_format($rowdata->amount, 2); ?></td>
                <td>&nbsp;</td>
                <?php } if($rowdata->tratype=='C'){ ?>
                <td>&nbsp;</td>
                <td class="text-right"><?php $balancetotal-=$rowdata->amount; echo number_format($rowdata->amount, 2); ?></td>
                <?php } ?>
                <td class="text-right"><?php echo number_format($balancetotal, 2); ?></td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5">Closing Balance</th>
                <th class="text-right"><?php echo number_format($balancetotal, 2); ?></th>
            </tr>
        </tfoot>
    </table>
</div>
<input type="hidden" id="periodtitle" value="<?php echo $report_duration; ?>">
<input type="hidden" id="reporttitle" value="<?php echo $debtorname; ?> Statement">
<input type="hidden" id="filetitle" value="<?php echo $debtorname; ?>_sheet_">
<input type="hidden" id="reporttype" value="5">