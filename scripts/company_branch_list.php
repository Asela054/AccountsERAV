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
$table = 'tbl_company_branch';

// Table's primary key
$primaryKey = 'idtbl_company_branch';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
/*
$columns = array(
	array( 'db' => '`u`.`idtbl_account_subcategory`', 'dt' => 'idtbl_account_subcategory', 'field' => 'idtbl_account_subcategory' ),
	// array( 'db' => '`u`.`code`', 'dt' => 'code', 'field' => 'code' ),
	array( 'db' => '`u`.`subcategory`', 'dt' => 'subcategory', 'field' => 'subcategory' ),
	array( 'db' => '`ua`.`category`', 'dt' => 'category', 'field' => 'category' ),
	array( 'db' => '`u`.`status`', 'dt' => 'status', 'field' => 'status' )
);
*/
$columns = array(
			   array( 'db' => '`u`.idtbl_company_branch', 'dt' => 'branch_regno', 'field' => 'idtbl_company_branch' ),
			   array( 'db' => '`u`.branch', 'dt' => 'branch_name', 'field' => 'branch' ),
			   array( 'db' => '`u`.code', 'dt' => 'branch_code', 'field' => 'code'),
			   array( 'db' => '`u`.tbl_company_idtbl_company', 'dt' => 'parent_company_regno', 'field' => 'tbl_company_idtbl_company' )
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

$joinQuery = "FROM `tbl_company_branch` as u";

$extraWhere = "";//"`u`.`status` IN (1, 2)";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
