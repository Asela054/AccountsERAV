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
$table = 'tbl_account_detail';

// Table's primary key
$primaryKey = 'idtbl_account_detail';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`dmain`.`idtbl_account_detail`', 'dt' => 'idtbl_account_detail', 'field' => 'idtbl_account_detail' ),
	array( 'db' => '`dmain`.`code`', 'dt' => 'code', 'field' => 'code' ),
	array( 'db' => '`dmain`.`accountno`', 'dt' => 'accountno', 'field' => 'accountno' ),
	array( 'db' => '`dmain`.`accountname`', 'dt' => 'accountname', 'field' => 'accountname' ),
    array( 'db' => '`dmain`.`chartaccountno`', 'dt' => 'chartaccountno', 'field' => 'chartaccountno', 'as' => 'chartaccountno' ),
    array( 'db' => '`dmain`.`chartaccountname`', 'dt' => 'chartaccountname', 'field' => 'chartaccountname', 'as' => 'chartaccountname' ),
	array( 'db' => '`dmain`.`status`', 'dt' => 'status', 'field' => 'status' )
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

$joinQuery = "FROM (SELECT `u`.`idtbl_account_detail`, `u`.`code`, `u`.`accountno`, `u`.`accountname`, `ua`.`accountno` AS `chartaccountno`, `ua`.`accountname` AS `chartaccountname`, `u`.`status` FROM `tbl_account_detail` AS `u` LEFT JOIN `tbl_account` AS `ua` ON (`ua`.`idtbl_account` = `u`.`tbl_account_idtbl_account`) LEFT JOIN `tbl_account_allocation` AS `ub` ON (`ub`.`tbl_account_detail_idtbl_account_detail`=`u`.`idtbl_account_detail`) WHERE `u`.`status` IN (1, 2) AND `ub`.`companybank`='$companyid' AND `ub`.`branchcompanybank`='$branchid'
UNION ALL
SELECT `u`.`idtbl_account_detail`, `u`.`code`, `u`.`accountno`, `u`.`accountname`, `ua`.`accountno` AS `chartaccountno`, `ua`.`accountname` AS `chartaccountname`, `u`.`status` FROM `tbl_account_detail` AS `u` LEFT JOIN `tbl_account` AS `ua` ON (`ua`.`idtbl_account` = `u`.`tbl_account_idtbl_account`) LEFT JOIN `tbl_account_allocation` AS `ub` ON (`ub`.`tbl_account_detail_idtbl_account_detail`=`u`.`idtbl_account_detail`) WHERE `u`.`status` IN (1, 2) AND `ub`.`tbl_account_detail_idtbl_account_detail` IS NULL) AS `dmain`";

$extraWhere = "";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
