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
$table = 'tbl_batch_transaction_main';

// Table's primary key
$primaryKey = 'idtbl_batch_transaction_main';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`idtbl_batch_transaction_main`', 'dt' => 'idtbl_batch_transaction_main', 'field' => 'idtbl_batch_transaction_main' ),
	array( 'db' => '`u`.`transdate`', 'dt' => 'transdate', 'field' => 'transdate' ),
	array( 'db' => '`u`.`batchno`', 'dt' => 'batchno', 'field' => 'batchno' ),
	array( 'db' => '`u`.`approvestatus`', 'dt' => 'approvestatus', 'field' => 'approvestatus' ),
	array( 'db' => '`u`.`completestatus`', 'dt' => 'completestatus', 'field' => 'completestatus' ),
	array( 'db' => '`ua`.`batch_category`', 'dt' => 'batch_category', 'field' => 'batch_category' ),
	array( 'db' => '`ua`.`idtbl_batch_category`', 'dt' => 'idtbl_batch_category', 'field' => 'idtbl_batch_category' ),
	array( 'db' => '`ub`.`batctranstypecode`', 'dt' => 'batctranstypecode', 'field' => 'batctranstypecode' ),
	array( 'db' => '`ub`.`idtbl_batch_trans_type`', 'dt' => 'idtbl_batch_trans_type', 'field' => 'idtbl_batch_trans_type' ),
	array( 'db' => '`ub`.`batctranstype`', 'dt' => 'batctranstype', 'field' => 'batctranstype' ),
    array( 'db' => '`ud`.`desc`', 'dt' => 'desc', 'field' => 'desc' ),
	array( 'db' => '`ue`.`monthname`', 'dt' => 'monthname', 'field' => 'monthname' ),
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

$joinQuery = "FROM `tbl_batch_transaction_main` AS `u` LEFT JOIN `tbl_batch_category` AS `ua` ON (`ua`.`idtbl_batch_category` = `u`.`tbl_batch_category_idtbl_batch_category`) LEFT JOIN `tbl_batch_trans_type` AS `ub` ON (`ub`.`idtbl_batch_trans_type` = `u`.`tbl_batch_trans_type_idtbl_batch_trans_type`) LEFT JOIN `tbl_master` AS `uc` ON (`uc`.`idtbl_master` = `u`.`tbl_master_idtbl_master`) LEFT JOIN `tbl_finacial_year` AS `ud` ON (`ud`.`idtbl_finacial_year` = `uc`.`tbl_finacial_year_idtbl_finacial_year`) LEFT JOIN `tbl_finacial_month` AS `ue` ON (`ue`.`idtbl_finacial_month` = `uc`.`tbl_finacial_month_idtbl_finacial_month`)";

$extraWhere = "`u`.`status` IN (1, 2) AND `u`.`tbl_company_idtbl_company`='$companyid' AND `u`.`tbl_company_branch_idtbl_company_branch`='$branchid'";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
