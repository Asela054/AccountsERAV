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
$table = 'tbl_pettycash';

// Table's primary key
$primaryKey = 'idtbl_pettycash';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`dmain`.`idtbl_pettycash`', 'dt' => 'idtbl_pettycash', 'field' => 'idtbl_pettycash' ),
	array( 'db' => '`dmain`.`date`', 'dt' => 'date', 'field' => 'date' ),
	array( 'db' => '`dmain`.`pettycashcode`', 'dt' => 'pettycashcode', 'field' => 'pettycashcode' ),
	array( 'db' => '`dmain`.`desc`', 'dt' => 'desc', 'field' => 'desc' ),
	array( 'db' => '`dmain`.`amount`', 'dt' => 'amount', 'field' => 'amount' ),
	array( 'db' => '`dmain`.`poststatus_text`', 'dt' => 'poststatus_text', 'field' => 'poststatus_text' ),
	array( 'db' => '`dmain`.`account_name`', 'dt' => 'account_name', 'field' => 'account_name' ),
	array( 'db' => '`dmain`.`petty_account_no`', 'dt' => 'petty_account_no', 'field' => 'petty_account_no' ),
	array( 'db' => '`dmain`.`account_detail_name`', 'dt' => 'account_detail_name', 'field' => 'account_detail_name' ),
	array( 'db' => '`dmain`.`detail_account_no`', 'dt' => 'detail_account_no', 'field' => 'detail_account_no' ),
	array( 'db' => '`dmain`.`expense_account_name`', 'dt' => 'expense_account_name', 'field' => 'expense_account_name' ),
	array( 'db' => '`dmain`.`expense_account_no`', 'dt' => 'expense_account_no', 'field' => 'expense_account_no' )
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
    pc.`idtbl_pettycash`,
    pc.`date`,
    pc.`pettycashcode`,
    pc.`desc`,
    pc.`amount`,
    CASE 
        WHEN pc.`poststatus` = 1 THEN 'Posted' 
        ELSE 'Pending' 
    END AS poststatus_text,
    pc.`postuser`,
    pc.`postviewtime`,
    pc.`reimbursestatus`,
    pc.`status`,
    a.`accountname` AS account_name,
    a.`accountno` AS petty_account_no,
    ad.`accountname` AS account_detail_name,
    ad.`accountno` AS detail_account_no,
    a_exp.`accountname` AS expense_account_name,
    a_exp.`accountno` AS expense_account_no
FROM 
    `tbl_pettycash` pc
LEFT JOIN 
    `tbl_account` a ON pc.`tbl_account_idtbl_account` = a.`idtbl_account`
LEFT JOIN 
    `tbl_account_detail` ad ON pc.`tbl_account_detail_idtbl_account_detail_exp` = ad.`idtbl_account_detail`
LEFT JOIN 
    `tbl_account` a_exp ON pc.`tbl_account_idtbl_account_exp` = a_exp.`idtbl_account`
WHERE 
    pc.`date` BETWEEN '$fromdate' AND '$todate'
    AND pc.`tbl_company_idtbl_company` = '$companyid'
    AND pc.`tbl_company_branch_idtbl_company_branch` = '$branchid'
    AND pc.`status` = 1
ORDER BY 
    pc.`date` DESC, pc.`pettycashcode` ASC) AS `dmain`";

$extraWhere = "";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
