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
$table = 'tbl_account_transaction';

// Table's primary key
$primaryKey = 'idtbl_account_transaction';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`idtbl_account_transaction`', 'dt' => 'idtbl_account_transaction', 'field' => 'idtbl_account_transaction' ),
	array( 'db' => '`u`.`tradate`', 'dt' => 'tradate', 'field' => 'tradate' ),
	array( 'db' => '`u`.`batchno`', 'dt' => 'batchno', 'field' => 'batchno' ),
	array( 'db' => '`u`.`trabatchotherno`', 'dt' => 'trabatchotherno', 'field' => 'trabatchotherno' ),
	array( 'db' => '`u`.`crdr`', 'dt' => 'crdr', 'field' => 'crdr' ),
	array( 'db' => '`u`.`totamount`', 'dt' => 'totamount', 'field' => 'totamount' ),
	array( 'db' => '`u`.`narration`', 'dt' => 'narration', 'field' => 'narration' ),
	array( 'db' => '`ua`.`company`', 'dt' => 'company', 'field' => 'company' ),
	array( 'db' => '`ub`.`branch`', 'dt' => 'branch', 'field' => 'branch' ),
	array( 'db' => '`ud`.`desc`', 'dt' => 'desc', 'field' => 'desc' ),
	array( 'db' => '`ue`.`monthname`', 'dt' => 'monthname', 'field' => 'monthname' ),
	array( 'db' => '`uf`.`accountno`', 'dt' => 'accountno', 'field' => 'accountno' ),
	array( 'db' => '`uf`.`accountname`', 'dt' => 'accountname', 'field' => 'accountname' ),
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

$joinQuery = "FROM `tbl_account_transaction` AS `u` LEFT JOIN `tbl_company` AS `ua` ON (`ua`.`idtbl_company` = `u`.`tbl_company_idtbl_company`) LEFT JOIN `tbl_company_branch` AS `ub` ON (`ub`.`idtbl_company_branch` = `u`.`tbl_company_branch_idtbl_company_branch`) LEFT JOIN `tbl_master` AS `uc` ON (`uc`.`idtbl_master` = `u`.`tbl_master_idtbl_master`) LEFT JOIN `tbl_finacial_year` AS `ud` ON (`ud`.`idtbl_finacial_year` = `uc`.`tbl_finacial_year_idtbl_finacial_year`) LEFT JOIN `tbl_finacial_month` AS `ue` ON (`ue`.`idtbl_finacial_month` = `uc`.`tbl_finacial_month_idtbl_finacial_month`) LEFT JOIN `tbl_account` AS `uf` ON (`uf`.`idtbl_account` = `u`.`tbl_account_idtbl_account`)";

$extraWhere = "`u`.`status` IN (1, 2) AND `u`.`tbl_company_idtbl_company`='$companyid' AND `u`.`tbl_company_branch_idtbl_company_branch`='$branchid'";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
