<?php


$table = 'tbl_asset_destroy';


$primaryKey = 'idtbl_asset_destroy';


$columns = array(
	array( 'db' => '`u`.`idtbl_asset_destroy`', 'dt' => 'idtbl_asset_destroy', 'field' => 'idtbl_asset_destroy' ),
	array( 'db' => '`u`.`destroy_item`', 'dt' => 'destroy_item', 'field' => 'destroy_item' ),
	array( 'db' => '`u`.`destroy_date`', 'dt' => 'destroy_date', 'field' => 'destroy_date' ),
	array( 'db' => '`u`.`destroy_amount`', 'dt' => 'destroy_amount', 'field' => 'destroy_amount' ),
	array( 'db' => '`u`.`destroy_reason`', 'dt' => 'destroy_reason', 'field' => 'destroy_reason' ),
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

$joinQuery = "FROM `tbl_asset_destroy` AS `u`";

$extraWhere = "`u`.`status` IN (1,2)";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
