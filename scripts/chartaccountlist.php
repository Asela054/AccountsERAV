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
$table = 'tbl_account';

// Table's primary key
$primaryKey = 'idtbl_account';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`dmain`.`idtbl_account`', 'dt' => 'idtbl_account', 'field' => 'idtbl_account' ),
	array( 'db' => '`dmain`.`code`', 'dt' => 'code', 'field' => 'code' ),
	array( 'db' => '`dmain`.`accountno`', 'dt' => 'accountno', 'field' => 'accountno' ),
	array( 'db' => '`dmain`.`accountname`', 'dt' => 'accountname', 'field' => 'accountname' ),
	array( 'db' => '`dmain`.`category`', 'dt' => 'category', 'field' => 'category' ),
	array( 'db' => '`dmain`.`subcategory`', 'dt' => 'subcategory', 'field' => 'subcategory' ),
	array( 'db' => '`dmain`.`accounttype`', 'dt' => 'accounttype', 'field' => 'accounttype' ),
	array( 'db' => '`dmain`.`nestcategory`', 'dt' => 'nestcategory', 'field' => 'nestcategory' ),
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

$joinQuery = "FROM (SELECT `u`.`idtbl_account`, `u`.`code`, `u`.`accountno`, `u`.`accountname`, `ua`.`category`, `ub`.`subcategory`, `uc`.`accounttype`, `ud`.`nestcategory`, `u`.`status` FROM `tbl_account` AS `u` LEFT JOIN `tbl_account_category` AS `ua` ON (`ua`.`idtbl_account_category` = `u`.`tbl_account_category_idtbl_account_category`) LEFT JOIN `tbl_account_subcategory` AS `ub` ON (`ub`.`idtbl_account_subcategory` = `u`.`tbl_account_subcategory_idtbl_account_subcategory`) LEFT JOIN `tbl_account_type` AS `uc` ON (`uc`.`idtbl_account_type` = `u`.`tbl_account_type_idtbl_account_type`) LEFT JOIN `tbl_account_nestcategory` AS `ud` ON (`ud`.`idtbl_account_nestcategory` = `u`.`tbl_account_nestcategory_idtbl_account_nestcategory`) LEFT JOIN `tbl_account_allocation` AS `uf` ON (`uf`.`tbl_account_idtbl_account`=`u`.`idtbl_account`) WHERE `u`.`status` IN (1, 2) AND `uf`.`companybank`='$companyid' AND `uf`.`branchcompanybank`='$branchid'
UNION ALL
SELECT `u`.`idtbl_account`, `u`.`code`, `u`.`accountno`, `u`.`accountname`, `ua`.`category`, `ub`.`subcategory`, `uc`.`accounttype`, `ud`.`nestcategory`, `u`.`status` FROM `tbl_account` AS `u` LEFT JOIN `tbl_account_category` AS `ua` ON (`ua`.`idtbl_account_category` = `u`.`tbl_account_category_idtbl_account_category`) LEFT JOIN `tbl_account_subcategory` AS `ub` ON (`ub`.`idtbl_account_subcategory` = `u`.`tbl_account_subcategory_idtbl_account_subcategory`) LEFT JOIN `tbl_account_type` AS `uc` ON (`uc`.`idtbl_account_type` = `u`.`tbl_account_type_idtbl_account_type`) LEFT JOIN `tbl_account_nestcategory` AS `ud` ON (`ud`.`idtbl_account_nestcategory` = `u`.`tbl_account_nestcategory_idtbl_account_nestcategory`) LEFT JOIN `tbl_account_allocation` AS `uf` ON (`uf`.`tbl_account_idtbl_account`=`u`.`idtbl_account`) WHERE `u`.`status` IN (1, 2) AND `uf`.`tbl_account_idtbl_account` IS NULL) AS `dmain`";

$extraWhere = "";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
