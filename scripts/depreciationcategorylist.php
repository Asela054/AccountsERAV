<?php


$table = 'tbl_depreciation_category';

$primaryKey = 'idtbl_depreciation_category';


$columns = array(
	array( 'db' => 'u.idtbl_depreciation_category', 'dt' => 'idtbl_depreciation_category', 'field' => 'idtbl_depreciation_category' ),
	array( 'db' => 'u.depreciation_category', 'dt' => 'depreciation_category', 'field' => 'depreciation_category' ),
	array( 'db' => 'u.status', 'dt' => 'status', 'field' => 'status' )

	 
);


require('config.php');
$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_host
);


require('ssp.customized.class.php' );

$joinQuery = "FROM tbl_depreciation_category AS u";

$extraWhere = "u.status IN (1,2)";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);