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
$table = 'tbl_pettycash_summary';

// Table's primary key
$primaryKey = 'idtbl_pettycash_summary';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`dmain`.`idtbl_pettycash_summary`', 'dt' => 'idtbl_pettycash_summary', 'field' => 'idtbl_pettycash_summary' ),
	array( 'db' => '`dmain`.`date`', 'dt' => 'date', 'field' => 'date' ),
	array( 'db' => '`dmain`.`openbal`', 'dt' => 'openbal', 'field' => 'openbal' ),
	array( 'db' => '`dmain`.`postbal`', 'dt' => 'postbal', 'field' => 'postbal' ),
	array( 'db' => '`dmain`.`reimbal`', 'dt' => 'reimbal', 'field' => 'reimbal' ),
	array( 'db' => '`dmain`.`closebal`', 'dt' => 'closebal', 'field' => 'closebal' ),
	array( 'db' => '`dmain`.`accountno`', 'dt' => 'accountno', 'field' => 'accountno' ),
	array( 'db' => '`dmain`.`accountname`', 'dt' => 'accountname', 'field' => 'accountname' )
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
$fromdate=$_POST['fromdate'];
$todate=$_POST['todate'];

$joinQuery = "FROM (SELECT 
    ps.`idtbl_pettycash_summary`,
    ps.`date`,
    ps.`openbal`,
    ps.`postbal`,
    ps.`reimbal`,
    ps.`closebal`,
    a.`accountno`,
    a.`accountname`,
    a.`code` AS account_code
FROM 
    `tbl_pettycash_summary` ps
LEFT JOIN 
    `tbl_account` a ON ps.`tbl_account_idtbl_account` = a.`idtbl_account`
WHERE 
    ps.`date` BETWEEN '$fromdate' AND '$todate'
    AND ps.`tbl_company_idtbl_company` = '$companyid'
    AND ps.`tbl_company_branch_idtbl_company_branch` = '$branchid'
    AND ps.`status` = 1
ORDER BY 
    ps.`idtbl_pettycash_summary` DESC) AS `dmain`";

$extraWhere = "";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
