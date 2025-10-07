<?php


$table = 'tbl_asset_sell';


$primaryKey = 'asset_name';


$columns = array(
	array( 'db' => '`ua`.`asset_name`', 'dt' => 'asset_name', 'field' => 'asset_name' ),
	array( 'db' => '`ua`.`date`', 'dt' => 'date', 'field' => 'date' ),
	array( 'db' => '`ua`.`reason`', 'dt' => 'reason', 'field' => 'reason' ),
	array( 'db' => '`ua`.`amount`', 'dt' => 'amount', 'field' => 'amount' ),
    array( 'db' => '`u`.`idtbl_assetsell_report`', 'dt' => 'idtbl_assetsell_report', 'field' => 'idtbl_assetsell_report' ),
	array( 'db' => '`u`.`tbl_asset_sell_idtbl_asset_sell`', 'dt' => 'tbl_asset_sell_idtbl_asset_sell', 'field' => 'tbl_asset_sell_idtbl_asset_sell' ),
	array( 'db' => '`u`.`status`', 'dt' => 'status', 'field' => 'status' ),
);

require('config.php');
$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_host
);



require('ssp.customized.class.php' );

$joinQuery = "FROM `tbl_assetsell_report` AS `u` LEFT JOIN `tbl_asset_sell` AS `ua` ON (ua.idtbl_asset_sell = u.tbl_asset_sell_idtbl_asset_sell)";

if(!empty($_POST['asset_name'])){
    $asset_nameID = $_POST['asset_name'];

$extraWhere = "`u`.`status` IN (1,2) AND u.tbl_asset_sell_idtbl_asset_sell = $asset_nameID";
}


echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
