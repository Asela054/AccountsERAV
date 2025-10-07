<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Fira+Mono&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css"> -->
    <title>Payment Receipt</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <style media="print">
        * {
            font-family: "Roboto", sans-serif;
            font-weight: 400;
        }
        table,tr,th,td{
            font-family: "Roboto", sans-serif;
            font-weight: 400;
        }
        img{
            width:200px;
            height:100px;
        }
    </style>
    <style>
        * {
            font-family: "Roboto", sans-serif;
            font-weight: 400;
        }
        table,tr,th,td{
            font-family: "Roboto", sans-serif;
            font-weight: 400;
        }
        img{
            width:100px;
            height:100px;
        }
    </style>
</head>

<body>
    <?php //print_r($invoiceproduct->result()); ?>
    <div id='DivIdToPrint'>
        <table style="width:100%;">
            <tr>
                <td width="50%" style="text-align: left; vertical-align: top;">
                    <h3 style="margin-bottom: 0px; font-weight: bold;">PAYMENT RECEIPT</h3>
                    <p style="margin-bottom: 2px;margin-top:2px;font-size: 15px;"><?php echo $printinfo->customer ?></p>
                    <p style="margin-bottom: 2px;margin-top:2px;font-size: 15px;"><?php echo $printinfo->address_line1.' '.$printinfo->address_line2.' '.$printinfo->city.''.$printinfo->state ?></p>
                    <p style="margin-bottom: 2px;margin-top:2px;font-size: 15px;">Date : <?php echo $printinfo->receiptdates ?></p>
                    <p style="margin-bottom: 2px;margin-top:2px;font-size: 15px;">Receipt No: <?php echo $printinfo->receipts ?></p>
                </td>
                <td width="50%" style="text-align: left; vertical-align: top;">
                    <h3 style="margin-bottom: 0px;">MULTI OFFSET PRINTERS (PVT) LTD</h3>
                    <p style="margin-bottom: 2px;margin-top:2px;font-size: 15px;">345, NEGOMBO ROAD MUKALANGAMUWA, SEEDUWA</p>
                    <p style="margin-bottom: 2px;margin-top:2px;font-size: 15px;">Phone: +94-11-2253505, 2253876, 2256615</p>
                    <p style="margin-bottom: 2px;margin-top:2px;font-size: 15px;">Email: multioffsetprinters@gmail.com.</p>
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2">
                    <table style="width:100%;border-collapse: collapse;">
                        <tr>
                            <th width="10%" style="text-align: center; font-size:14px;border:1px solid black;padding:5px;">No</th>
                            <th width="15%" style="font-size:14px;border:1px solid black;padding:5px;">Invoice No</th>
                            <th style="text-align: left; font-size:14px;border:1px solid black;padding:5px;">Description</th>
                            <th width="25%" style="text-align: right; font-size:14px;border:1px solid black;padding:5px;">Amount</th>
                        </tr>
                        <?php $i=1;foreach($printinfo->invoicedata as $rowdatalist){ ?>
                        <tr>
                            <th width="10%" style="text-align: center; font-size:14px;border:1px solid black;padding:5px;"><?php echo $i; ?></th>
                            <th width="15%" style="font-size:14px;border:1px solid black;padding:5px;"><?php echo $rowdatalist->invoiceno ?></th>
                            <th style="text-align: left; font-size:14px;border:1px solid black;padding:5px;"><?php echo $rowdatalist->narration ?></th>
                            <th width="25%" style="text-align: right; font-size:14px;border:1px solid black;padding:5px;"><?php echo number_format($rowdatalist->amount, 2) ?></th>
                        </tr>
                        <?php $i++;} ?>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="50%">
                    <p style="font-size:14px;">Cheque information</p>
                    <table style="width:100%;border-collapse: collapse;">
                        <tr>
                            <th style="text-align: center; font-size:14px;border:1px solid black;padding:5px;">#</th>
                            <th style="text-align: left; font-size:14px;border:1px solid black;padding:5px;">Cheque No</th>
                            <th style="text-align: left; font-size:14px;border:1px solid black;padding:5px;">Cheque Date</th>
                        </tr>
                        <?php $j=1; foreach($printinfo->chequedata as $rowcheque){if(!empty($rowcheque->chequeno)){ ?>
                        <tr>
                            <th style="text-align: center; font-size:14px;border:1px solid black;padding:5px;"><?php echo $j; ?></th>
                            <th style="text-align: left; font-size:14px;border:1px solid black;padding:5px;"><?php echo $rowcheque->chequeno ?></th>
                            <th style="text-align: left; font-size:14px;border:1px solid black;padding:5px;"><?php echo $rowcheque->chequedate ?></th>
                        </tr>
                        <?php $j++;}} ?>
                    </table>
                </td>
                <td width="50%" style="text-align: right;vertical-align: top;"><h3 style="margin-top: 3px; margin-bottom: 0px;"><?php echo number_format($printinfo->receipttotal, 2) ?></h3></td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2">
                    <table width="100%">
                        <tr>
                            <td width="26.66%" style="text-align: center;border-top:1px dotted black;">Manager</td>
                            <td width="10%">&nbsp;</td>
                            <td width="26.66%" style="text-align: center;border-top:1px dotted black;">Asst. Accountant</td>
                            <td width="10%">&nbsp;</td>
                            <td width="26.66%" style="text-align: center;border-top:1px dotted black;">Cashier</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <!-- <p>Do not print.</p>
    <button type='button' id='btn'>Print</button> -->

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    <script>
        window.print();
        setTimeout(() => {
            window.close();
        }, 5000);
    </script>
</body>

</html>
