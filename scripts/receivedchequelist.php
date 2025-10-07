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
$table = 'tbl_receivable';

// Table's primary key
$primaryKey = 'idtbl_receivable';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`idtbl_receivable`', 'dt' => 'idtbl_receivable', 'field' => 'idtbl_receivable' ),
	array( 'db' => '`u`.`batchno`', 'dt' => 'batchno', 'field' => 'batchno' ),
	array( 'db' => '`u`.`amount`', 'dt' => 'amount', 'field' => 'amount' ),
	array( 'db' => '`u`.`depositstatus`', 'dt' => 'depositstatus', 'field' => 'depositstatus' ),
	array( 'db' => '`u`.`chequedate`', 'dt' => 'chequedate', 'field' => 'chequedate' ),
	array( 'db' => '`u`.`chequeno`', 'dt' => 'chequeno', 'field' => 'chequeno' ),
	array( 'db' => '`u`.`chequereturn`', 'dt' => 'chequereturn', 'field' => 'chequereturn' ),
	array( 'db' => '`ui`.`customer`', 'dt' => 'customer', 'field' => 'customer' ),
	array( 'db' => '`ua`.`company`', 'dt' => 'company', 'field' => 'company' ),
	array( 'db' => '`ub`.`branch`', 'dt' => 'branch', 'field' => 'branch' ),
	array( 'db' => '`ud`.`desc`', 'dt' => 'desc', 'field' => 'desc' ),
	array( 'db' => '`ue`.`monthname`', 'dt' => 'monthname', 'field' => 'monthname' ),
	array( 'db' => '`uf`.`idtbl_receivable_type`', 'dt' => 'idtbl_receivable_type', 'field' => 'idtbl_receivable_type' ),
	array( 'db' => '`uf`.`receivabletype`', 'dt' => 'receivabletype', 'field' => 'receivabletype' ),
	array( 'db' => '`ug`.`accountno`', 'dt' => 'accountno', 'field' => 'accountno' ),
	array( 'db' => '`ug`.`accountname`', 'dt' => 'accountname', 'field' => 'accountname' ),
    array( 'db' => '`uh`.`accountno`', 'dt' => 'detailaccountno', 'field' => 'detailaccountno', 'as' => 'detailaccountno' ),
    array( 'db' => '`uh`.`accountname`', 'dt' => 'detailaccountname', 'field' => 'detailaccountname', 'as' => 'detailaccountname' ),
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

$companyID=$_POST['companyID'];
$branchID=$_POST['branchID'];
$searchmonth=$_POST['searchmonth'];
$month = date("n", strtotime($searchmonth));
$year = date("Y", strtotime($searchmonth));

$joinQuery = "FROM `tbl_receivable` AS `u` LEFT JOIN `tbl_company` AS `ua` ON (`ua`.`idtbl_company` = `u`.`tbl_company_idtbl_company`) LEFT JOIN `tbl_company_branch` AS `ub` ON (`ub`.`idtbl_company_branch` = `u`.`tbl_company_branch_idtbl_company_branch`) LEFT JOIN `tbl_master` AS `uc` ON (`uc`.`idtbl_master` = `u`.`tbl_master_idtbl_master`) LEFT JOIN `tbl_finacial_year` AS `ud` ON (`ud`.`idtbl_finacial_year` = `uc`.`tbl_finacial_year_idtbl_finacial_year`) LEFT JOIN `tbl_finacial_month` AS `ue` ON (`ue`.`idtbl_finacial_month` = `uc`.`tbl_finacial_month_idtbl_finacial_month`) LEFT JOIN `tbl_receivable_type` AS `uf` ON (`uf`.`idtbl_receivable_type` = `u`.`tbl_receivable_type_idtbl_receivable_type`) LEFT JOIN `tbl_account` AS `ug` ON (`ug`.`idtbl_account` = `u`.`tbl_account_idtbl_account`) LEFT JOIN `tbl_account_detail` AS `uh` ON (`uh`.`idtbl_account_detail` = `u`.`tbl_account_detail_idtbl_account_detail`) LEFT JOIN `tbl_customer` AS `ui` ON (`ui`.`idtbl_customer` = `u`.`payer`)";

$extraWhere = "`u`.`status`=1 AND `u`.`tbl_company_idtbl_company`='$companyID' AND `u`.`tbl_company_branch_idtbl_company_branch`='$branchID' AND MONTH(`u`.`chequedate`)='$month' AND YEAR(`u`.`chequedate`)='$year' AND `u`.`tbl_receivable_type_idtbl_receivable_type`=2";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
