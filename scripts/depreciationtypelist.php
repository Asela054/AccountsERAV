<?php


$table = 'tbl_depreciation_type';


$primaryKey = 'idtbl_depreciation_type';


$columns = array(
	array( 'db' => '`u`.`idtbl_depreciation_type`', 'dt' => 'idtbl_depreciation_type', 'field' => 'idtbl_depreciation_type' ),
	array( 'db' => '`u`.`depreciation_type`', 'dt' => 'depreciation_type', 'field' => 'depreciation_type' ),
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

$joinQuery = "FROM `tbl_depreciation_type` AS `u`";

$extraWhere = "`u`.`status` IN (1,2)";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
