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
$table = 'tbl_cheque_issue';

// Table's primary key
$primaryKey = 'idtbl_cheque_issue';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`dmain`.`idtbl_cheque_issue`', 'dt' => 'idtbl_cheque_issue', 'field' => 'idtbl_cheque_issue' ),
	array( 'db' => '`dmain`.`amount`', 'dt' => 'amount', 'field' => 'amount' ),
	array( 'db' => '`dmain`.`chedate`', 'dt' => 'chedate', 'field' => 'chedate' ),
	array( 'db' => '`dmain`.`chequeno`', 'dt' => 'chequeno', 'field' => 'chequeno' ),
	array( 'db' => '`dmain`.`chequereturn`', 'dt' => 'chequereturn', 'field' => 'chequereturn' ),
	array( 'db' => '`dmain`.`batchno`', 'dt' => 'batchno', 'field' => 'batchno' ),
	array( 'db' => '`dmain`.`suppliername`', 'dt' => 'suppliername', 'field' => 'suppliername' ),
	array( 'db' => '`dmain`.`company`', 'dt' => 'company', 'field' => 'company' ),
	array( 'db' => '`dmain`.`branch`', 'dt' => 'branch', 'field' => 'branch' ),
	array( 'db' => '`dmain`.`desc`', 'dt' => 'desc', 'field' => 'desc' ),
	array( 'db' => '`dmain`.`monthname`', 'dt' => 'monthname', 'field' => 'monthname' ),
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

$companyID=$_POST['companyID'];
$branchID=$_POST['branchID'];
$searchmonth=$_POST['searchmonth'];
$month = date("n", strtotime($searchmonth));
$year = date("Y", strtotime($searchmonth));

$joinQuery = "FROM (SELECT `u`.`idtbl_cheque_issue`, `u`.`amount`, `u`.`chedate`, `u`.`chequeno`, `u`.`chequereturn`, `ub`.`batchno`, `uh`.`suppliername`, `uc`.`company`, `ud`.`branch`, `uf`.`desc`, `ug`.`monthname`, `u`.`status` FROM `tbl_cheque_issue` AS `u` LEFT JOIN `tbl_account_paysettle_has_tbl_cheque_issue` AS `ua` ON (`ua`.`tbl_cheque_issue_idtbl_cheque_issue` = `u`.`idtbl_cheque_issue`) LEFT JOIN `tbl_account_paysettle` AS `ub` ON (`ub`.`idtbl_account_paysettle` = `ua`.`tbl_account_paysettle_idtbl_account_paysettle`) LEFT JOIN `tbl_company` AS `uc` ON (`uc`.`idtbl_company` = `ub`.`tbl_company_idtbl_company`) LEFT JOIN `tbl_company_branch` AS `ud` ON (`ud`.`idtbl_company_branch` = `ub`.`tbl_company_branch_idtbl_company_branch`) LEFT JOIN `tbl_master` AS `ue` ON (`ue`.`idtbl_master` = `ub`.`tbl_master_idtbl_master`) LEFT JOIN `tbl_finacial_year` AS `uf` ON (`uf`.`idtbl_finacial_year` = `ue`.`tbl_finacial_year_idtbl_finacial_year`) LEFT JOIN `tbl_finacial_month` AS `ug` ON (`ug`.`idtbl_finacial_month` = `ue`.`tbl_finacial_month_idtbl_finacial_month`) LEFT JOIN `tbl_supplier` AS `uh` ON (`uh`.`idtbl_supplier` = `ub`.`supplier`) WHERE `u`.`status`=1 AND `ub`.`tbl_company_idtbl_company`='$companyID' AND `ub`.`tbl_company_branch_idtbl_company_branch`='$branchID' AND MONTH(`u`.`chedate`)='$month' AND YEAR(`u`.`chedate`)='$year'
UNION ALL
SELECT `tbl_cheque_issue`.`idtbl_cheque_issue`, `tbl_cheque_issue`.`amount`, `tbl_cheque_issue`.`chedate`, `tbl_cheque_issue`.`chequeno`, `tbl_cheque_issue`.`chequereturn`, `tbl_pettycash_reimburse`.`reimbursecode` AS `batchno`, '' AS `suppliername`, `tbl_company`.`company`, `tbl_company_branch`.`branch`, `tbl_finacial_year`.`desc`, `tbl_finacial_month`.`monthname`, `tbl_cheque_issue`.`status` FROM `tbl_cheque_issue` LEFT JOIN `tbl_pettycash_reimburse` ON `tbl_pettycash_reimburse`.`chequeno`=`tbl_cheque_issue`.`chequeno` LEFT JOIN `tbl_company` ON `tbl_company`.`idtbl_company`=`tbl_pettycash_reimburse`.`tbl_company_idtbl_company` LEFT JOIN `tbl_company_branch` ON `tbl_company_branch`.`idtbl_company_branch`=`tbl_pettycash_reimburse`.`tbl_company_branch_idtbl_company_branch` LEFT JOIN `tbl_master` ON `tbl_master`.`idtbl_master`=`tbl_pettycash_reimburse`.`tbl_master_idtbl_master` LEFT JOIN `tbl_finacial_year` ON `tbl_finacial_year`.`idtbl_finacial_year`=`tbl_master`.`tbl_finacial_year_idtbl_finacial_year` LEFT JOIN `tbl_finacial_month` ON `tbl_finacial_month`.`idtbl_finacial_month`=`tbl_master`.`tbl_finacial_month_idtbl_finacial_month` WHERE  `tbl_cheque_issue`.`status`=1 AND `tbl_pettycash_reimburse`.`tbl_company_idtbl_company`='$companyID' AND `tbl_pettycash_reimburse`.`tbl_company_branch_idtbl_company_branch`='$branchID' AND MONTH(`tbl_cheque_issue`.`chedate`)='$month' AND YEAR(`tbl_cheque_issue`.`chedate`)='$year') AS `dmain`";

$extraWhere = "";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
