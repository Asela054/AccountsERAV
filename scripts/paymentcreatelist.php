<?php
require_once '../external.php';

$CI =& get_instance();
$CI->load->library('session');
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
$table = 'tbl_expence_info';

// Table's primary key
$primaryKey = 'idtbl_expence_info';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`idtbl_expence_info`', 'dt' => 'idtbl_expence_info', 'field' => 'idtbl_expence_info' ),
	array( 'db' => '`u`.`expcode`', 'dt' => 'expcode', 'field' => 'expcode' ),
	array( 'db' => '`u`.`grnno`', 'dt' => 'grnno', 'field' => 'grnno' ),
	array( 'db' => '`u`.`grndate`', 'dt' => 'grndate', 'field' => 'grndate' ),
	array( 'db' => '`u`.`amount`', 'dt' => 'amount', 'field' => 'amount' ),
	array( 'db' => '`u`.`poststatus`', 'dt' => 'poststatus', 'field' => 'poststatus' ),
	array( 'db' => '`u`.`remark`', 'dt' => 'remark', 'field' => 'remark' ),
	array( 'db' => '`ua`.`company`', 'dt' => 'company', 'field' => 'company' ),
	array( 'db' => '`ub`.`branch`', 'dt' => 'branch', 'field' => 'branch' ),
	array( 'db' => '`uc`.`suppliername`', 'dt' => 'suppliername', 'field' => 'suppliername' ),
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

$companyid=$_SESSION['companyid'];
$branchid=$_SESSION['branchid'];

$joinQuery = "FROM `tbl_expence_info` AS `u` LEFT JOIN `tbl_company` AS `ua` ON (`ua`.`idtbl_company` = `u`.`tbl_company_idtbl_company`) LEFT JOIN `tbl_company_branch` AS `ub` ON (`ub`.`idtbl_company_branch` = `u`.`tbl_company_branch_idtbl_company_branch`) LEFT JOIN `tbl_supplier` AS `uc` ON (`uc`.`idtbl_supplier` = `u`.`tbl_supplier_idtbl_supplier`)";

$extraWhere = "`u`.`status` IN (1, 2) AND `u`.`tbl_company_idtbl_company`='$companyid' AND `u`.`tbl_company_branch_idtbl_company_branch`='$branchid' AND `u`.`exptype`=4";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
