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
$table = 'tbl_cheque_info';

// Table's primary key
$primaryKey = 'idtbl_cheque_info';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`idtbl_cheque_info`', 'dt' => 'idtbl_cheque_info', 'field' => 'idtbl_cheque_info' ),
	array( 'db' => '`u`.`startno`', 'dt' => 'startno', 'field' => 'startno' ),
	array( 'db' => '`u`.`endno`', 'dt' => 'endno', 'field' => 'endno' ),
	array( 'db' => '`ua`.`accountno`', 'dt' => 'accountno', 'field' => 'accountno' ),
	array( 'db' => '`ub`.`bankname`', 'dt' => 'bankname', 'field' => 'bankname' ),
	array( 'db' => '`uc`.`branchname`', 'dt' => 'branchname', 'field' => 'branchname' ),
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

$joinQuery = "FROM `tbl_cheque_info` AS `u` LEFT JOIN `tbl_account` AS `ua` ON (`ua`.`idtbl_account` = `u`.`tbl_account_idtbl_account`) LEFT JOIN `tbl_bank` AS `ub` ON (`ub`.`idtbl_bank` = `u`.`tbl_bank_idtbl_bank`) LEFT JOIN `tbl_bank_branch` AS `uc` ON (`uc`.`idtbl_bank_branch` = `u`.`tbl_bank_branch_idtbl_bank_branch`)";

$extraWhere = "`u`.`status` IN (1, 2)";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
