<?php
// session_start();
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
$table = 'tbl_company_branch';

// Table's primary key
$primaryKey = 'idtbl_company_branch';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`idtbl_company_branch`', 'dt' => 'idtbl_company_branch', 'field' => 'idtbl_company_branch' ),
	array( 'db' => '`u`.`branch`', 'dt' => 'branch', 'field' => 'branch' ),
	array( 'db' => '`u`.`idtbl_company`', 'dt' => 'idtbl_company', 'field' => 'idtbl_company' ),
	array( 'db' => '`u`.`company`', 'dt' => 'company', 'field' => 'company' ),
	array( 'db' => '`u`.`idtbl_finacial_year`', 'dt' => 'idtbl_finacial_year', 'field' => 'idtbl_finacial_year' ),
	array( 'db' => '`u`.`desc`', 'dt' => 'desc', 'field' => 'desc' ),
	array( 'db' => '`u`.`month`', 'dt' => 'month', 'field' => 'month' ),
	array( 'db' => '`u`.`monthname`', 'dt' => 'monthname', 'field' => 'monthname' ),
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

$joinQuery = "FROM (SELECT * FROM (SELECT `tbl_company_branch`.`idtbl_company_branch`, `tbl_company_branch`.`branch`, `tbl_company_branch`.`status`, `tbl_company`.`idtbl_company`, `tbl_company`.`company` FROM `tbl_company_branch` LEFT JOIN `tbl_company` AS `tbl_company` ON (`tbl_company`.`idtbl_company` = `tbl_company_branch`.`tbl_company_idtbl_company`) WHERE `tbl_company_branch`.`status`=1 AND `tbl_company`.`idtbl_company`='$companyid' AND `tbl_company_branch`.`idtbl_company_branch`='$branchid') AS `dmain` LEFT JOIN (SELECT `tbl_master`.`tbl_company_idtbl_company`, `tbl_master`.`tbl_company_branch_idtbl_company_branch`, `tbl_finacial_year`.`desc`, `tbl_finacial_year`.`idtbl_finacial_year`, `tbl_finacial_month`.`idtbl_finacial_month`, `tbl_finacial_month`.`monthname`, `tbl_finacial_month`.`month` FROM `tbl_master` LEFT JOIN `tbl_finacial_year` ON `tbl_finacial_year`.`idtbl_finacial_year`=`tbl_master`.`tbl_finacial_year_idtbl_finacial_year` LEFT JOIN `tbl_finacial_month` ON `tbl_finacial_month`.`idtbl_finacial_month`=`tbl_master`.`tbl_finacial_month_idtbl_finacial_month` WHERE `tbl_master`.`status`=1 AND `tbl_master`.`tbl_company_idtbl_company`='$companyid' AND `tbl_master`.`tbl_company_branch_idtbl_company_branch`='$branchid' ORDER BY `tbl_master`.`idtbl_master` DESC LIMIT 1) AS `dsub` ON `dsub`.`tbl_company_branch_idtbl_company_branch`=`dmain`.`idtbl_company_branch`) AS `u`";

$extraWhere = "";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
