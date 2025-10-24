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
    // Keep this for unique row identification, though it will be MIN/MAX if grouping
    array( 'db' => '`dmain`.`idtbl_pettycash`', 'dt' => 'idtbl_pettycash', 'field' => 'idtbl_pettycash' ),
    // Grouping Columns
    array( 'db' => '`dmain`.`company_name`', 'dt' => 'company_name', 'field' => 'company_name' ),
    array( 'db' => '`dmain`.`branch_name`', 'dt' => 'branch_name', 'field' => 'branch_name' ),
    array( 'db' => '`dmain`.`month_name`', 'dt' => 'month_name', 'field' => 'month_name' ),
    array( 'db' => '`dmain`.`expense_account_full`', 'dt' => 'expense_account_full', 'field' => 'expense_account_full' ),

    // Detail/Aggregate Columns (Note: date and desc will use MIN/MAX or be NULL)
    array( 'db' => '`dmain`.`date`', 'dt' => 'date', 'field' => 'date' ), // This will be the MIN date of the group
    array( 'db' => '`dmain`.`desc`', 'dt' => 'desc', 'field' => 'desc' ), // This will be the MIN desc of the group
    array( 'db' => '`dmain`.`total_amount`', 'dt' => 'amount', 'field' => 'total_amount' ), // This is the aggregated amount

    // Other fields (mostly NULL/MIN/MAX in a grouped query)
    array( 'db' => '`dmain`.`pettycashcode`', 'dt' => 'pettycashcode', 'field' => 'pettycashcode' ),
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

// --- MODIFIED JOIN QUERY ---
$joinQuery = "FROM (
    SELECT
        MIN(pc.`idtbl_pettycash`) AS idtbl_pettycash,
        c.`company` AS company_name,
        b.`branch` AS branch_name,
        DATE_FORMAT(pc.`date`, '%M') AS month_name,
        a_exp.`accountno` AS expense_account_no,
        CONCAT(a_exp.`accountno`, ' - ', a_exp.`accountname`) AS expense_account_full,

        MIN(pc.`date`) AS date, 
        MIN(pc.`pettycashcode`) AS pettycashcode,
        MIN(pc.`desc`) AS `desc`, 
        SUM(pc.`amount`) AS total_amount, 

        MIN(CASE WHEN pc.`poststatus` = 1 THEN 'Posted' ELSE 'Pending' END) AS poststatus_text,
        MIN(a.`accountname`) AS account_name,
        MIN(a.`accountno`) AS petty_account_no,
        MIN(ad.`accountname`) AS account_detail_name,
        MIN(ad.`accountno`) AS detail_account_no,
        MIN(a_exp.`accountname`) AS expense_account_name

    FROM
        `tbl_pettycash` pc
    LEFT JOIN
        `tbl_account` a ON pc.`tbl_account_idtbl_account` = a.`idtbl_account`
    LEFT JOIN
        `tbl_account_detail` ad ON pc.`tbl_account_detail_idtbl_account_detail_exp` = ad.`idtbl_account_detail`
    LEFT JOIN
        `tbl_account` a_exp ON pc.`tbl_account_idtbl_account_exp` = a_exp.`idtbl_account`
    LEFT JOIN
        `tbl_company` c ON pc.`tbl_company_idtbl_company` = c.`idtbl_company`
    LEFT JOIN
        `tbl_company_branch` b ON pc.`tbl_company_branch_idtbl_company_branch` = b.`idtbl_company_branch`
    WHERE
        pc.`date` BETWEEN '$fromdate' AND '$todate'
        AND pc.`tbl_company_idtbl_company` = '$companyid'
        AND pc.`tbl_company_branch_idtbl_company_branch` = '$branchid'
        AND pc.`status` = 1
    GROUP BY
        c.`company`,
        b.`branch`,
        month_name,
        pc.`tbl_account_idtbl_account_exp` -- Group by the expense account ID

    ORDER BY
        month_name DESC, pc.`tbl_account_idtbl_account_exp` ASC
) AS `dmain`";
// --- END MODIFIED JOIN QUERY ---

$extraWhere = "";

echo json_encode(
    SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);