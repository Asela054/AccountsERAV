<?php

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = 'tbl_account_category';

// Table's primary key
$primaryKey = 'idtbl_account_category';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`idtbl_account_category`', 'dt' => 'idtbl_account_category', 'field' => 'idtbl_account_category' ),
	array( 'db' => '`u`.`code`', 'dt' => 'code', 'field' => 'code' ),
	array( 'db' => '`u`.`category`', 'dt' => 'category', 'field' => 'category' ),
	array( 'db' => '`ua`.`finacialtype`', 'dt' => 'finacialtype', 'field' => 'finacialtype' ),
	array( 'db' => '`ub`.`transactiontype`', 'dt' => 'transactiontype', 'field' => 'transactiontype' ),
	array( 'db' => '`u`.`status`', 'dt' => 'status', 'field' => 'status' )
);

// SQL server connection information
require('config.php');
$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_host
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

// require( 'ssp.class.php' );
require('ssp.customized.class.php' );

$joinQuery = "FROM `tbl_account_category` AS `u` LEFT JOIN `tbl_account_finacialtype` AS `ua` ON (`ua`.`idtbl_account_finacialtype` = `u`.`tbl_account_finacialtype_idtbl_account_finacialtype`) LEFT JOIN `tbl_account_transactiontype` AS `ub` ON (`ub`.`idtbl_account_transactiontype` = `u`.`tbl_account_transactiontype_idtbl_account_transactiontype`)";

$extraWhere = "`u`.`status` IN (1, 2)";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
