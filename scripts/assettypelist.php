<?php


$table = 'tbl_asset_type';


$primaryKey = 'idtbl_asset_type';


$columns = array(
	array( 'db' => '`u`.`idtbl_asset_type`', 'dt' => 'idtbl_asset_type', 'field' => 'idtbl_asset_type' ),
	array( 'db' => '`u`.`asset_type`', 'dt' => 'asset_type', 'field' => 'asset_type' ),
	array( 'db' => '`u`.`status`', 'dt' => 'status', 'field' => 'status' )
);


require('config.php');
$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_host
);


require('ssp.customized.class.php' );

$joinQuery = "FROM `tbl_asset_type` AS `u`";

$extraWhere = "`u`.`status` IN (1,2)";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
