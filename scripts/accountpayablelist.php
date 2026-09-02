<?php
require_once '../external.php';

$CI =& get_instance();
$CI->load->library('session');

$configdata = getconfigdata('payable_search');
$tablename = $configdata->row(0)->tbl_name;
$column1   = $configdata->row(0)->col_name;
$column2   = $configdata->row(1)->col_name;

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
$table = 'tbl_account_payable_main';

// Table's primary key
$primaryKey = 'idtbl_account_payable_main';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
// $columns = array(
// 	array( 'db' => '`u`.`idtbl_account_payable_main`', 'dt' => 'idtbl_account_payable_main', 'field' => 'idtbl_account_payable_main' ),
// 	array( 'db' => '`u`.`tradate`', 'dt' => 'tradate', 'field' => 'tradate' ),
// 	array( 'db' => '`u`.`batchno`', 'dt' => 'batchno', 'field' => 'batchno' ),
// 	array( 'db' => '`u`.`amount`', 'dt' => 'amount', 'field' => 'amount' ),
// 	array( 'db' => '`u`.`poststatus`', 'dt' => 'poststatus', 'field' => 'poststatus' ),
// 	array( 'db' => '`ua`.`company`', 'dt' => 'company', 'field' => 'company' ),
// 	array( 'db' => '`ub`.`branch`', 'dt' => 'branch', 'field' => 'branch' ),
// 	array( 'db' => '`ud`.`desc`', 'dt' => 'desc', 'field' => 'desc' ),
// 	array( 'db' => '`ue`.`monthname`', 'dt' => 'monthname', 'field' => 'monthname' ),
// 	array( 'db' => '`uf`.`grnno`', 'dt' => 'grnno', 'field' => 'grnno' ),
// 	array( 'db' => "`ug`.`$column2` AS `suppliername`", 'dt' => 'suppliername', 'field' => 'suppliername' ),
// 	array( 'db' => '`u`.`status`', 'dt' => 'status', 'field' => 'status' )
// );
$columns = array(
    array( 'db' => '`u`.`idtbl_account_payable_main`', 'dt' => 'idtbl_account_payable_main', 'field' => 'idtbl_account_payable_main' ),
    array( 'db' => '`u`.`tradate`',                    'dt' => 'tradate',                    'field' => 'tradate' ),
    array( 'db' => '`u`.`batchno`',                    'dt' => 'batchno',                    'field' => 'batchno' ),
    array( 'db' => '`u`.`amount`',                     'dt' => 'amount',                     'field' => 'amount' ),
    array( 'db' => '`u`.`poststatus`',                 'dt' => 'poststatus',                 'field' => 'poststatus' ),
    array( 'db' => '`u`.`company`',                    'dt' => 'company',                    'field' => 'company' ),
    array( 'db' => '`u`.`branch`',                     'dt' => 'branch',                     'field' => 'branch' ),
    array( 'db' => '`u`.`desc`',                       'dt' => 'desc',                       'field' => 'desc' ),
    array( 'db' => '`u`.`monthname`',                  'dt' => 'monthname',                  'field' => 'monthname' ),
    array( 'db' => '`u`.`grnno`',                      'dt' => 'grnno',                      'field' => 'grnno' ),
    array( 'db' => '`u`.`suppliername`',               'dt' => 'suppliername',               'field' => 'suppliername' ),
    array( 'db' => '`u`.`status`',                     'dt' => 'status',                     'field' => 'status' ),
    array( 'db' => '`u`.`idtbl_expence_info`',                     'dt' => 'idtbl_expence_info',                     'field' => 'idtbl_expence_info' ),
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

// $joinQuery = "FROM `tbl_account_payable_main` AS `u` LEFT JOIN `tbl_company` AS `ua` ON (`ua`.`idtbl_company` = `u`.`tbl_company_idtbl_company`) LEFT JOIN `tbl_company_branch` AS `ub` ON (`ub`.`idtbl_company_branch` = `u`.`tbl_company_branch_idtbl_company_branch`) LEFT JOIN `tbl_master` AS `uc` ON (`uc`.`idtbl_master` = `u`.`tbl_master_idtbl_master`) LEFT JOIN `tbl_finacial_year` AS `ud` ON (`ud`.`idtbl_finacial_year` = `uc`.`tbl_finacial_year_idtbl_finacial_year`) LEFT JOIN `tbl_finacial_month` AS `ue` ON (`ue`.`idtbl_finacial_month` = `uc`.`tbl_finacial_month_idtbl_finacial_month`) LEFT JOIN `tbl_expence_info` AS `uf` ON (`uf`.`grnno` = `u`.`invoiceno`) LEFT JOIN $tablename AS `ug` ON (`ug`.$column1 = `uf`.`tbl_supplier_idtbl_supplier`)";

$joinQuery = "FROM (
    SELECT
        `u`.`idtbl_account_payable_main`,
        `u`.`tradate`,
        `u`.`batchno`,
        `u`.`amount`,
        `u`.`poststatus`,
        `u`.`status`,
        `u`.`paytype`,
        `u`.`tbl_company_idtbl_company`,
        `u`.`tbl_company_branch_idtbl_company_branch`,
        `u`.`invoiceno`,
        `ua`.`company`,
        `ub`.`branch`,
        `ud`.`desc`,
        `ue`.`monthname`,
        `uf`.`grnno`,
        `uf`.`idtbl_expence_info`,
        `uf`.`tbl_company_idtbl_company`        AS `grn_companyid`,
        `uf`.`tbl_company_branch_idtbl_company_branch` AS `grn_branchid`,
        `ug`.`$column2`                          AS `suppliername`
    FROM `tbl_account_payable_main` AS `u`
    LEFT JOIN `tbl_company` AS `ua`
        ON (`ua`.`idtbl_company` = `u`.`tbl_company_idtbl_company`)
    LEFT JOIN `tbl_company_branch` AS `ub`
        ON (`ub`.`idtbl_company_branch` = `u`.`tbl_company_branch_idtbl_company_branch`)
    LEFT JOIN `tbl_master` AS `uc`
        ON (`uc`.`idtbl_master` = `u`.`tbl_master_idtbl_master`)
    LEFT JOIN `tbl_finacial_year` AS `ud`
        ON (`ud`.`idtbl_finacial_year` = `uc`.`tbl_finacial_year_idtbl_finacial_year`)
    LEFT JOIN `tbl_finacial_month` AS `ue`
        ON (`ue`.`idtbl_finacial_month` = `uc`.`tbl_finacial_month_idtbl_finacial_month`)
    LEFT JOIN `tbl_expence_info` AS `uf`
        ON (`uf`.`grnno` = `u`.`invoiceno` AND `uf`.`status`=1 AND `uf`.`tbl_company_idtbl_company` = `u`.`tbl_company_idtbl_company` AND `uf`.`tbl_company_branch_idtbl_company_branch` = `u`.`tbl_company_branch_idtbl_company_branch`)
    LEFT JOIN `$tablename` AS `ug`
        ON (`ug`.$column1 = `uf`.`tbl_supplier_idtbl_supplier`)
    WHERE
        `u`.`status` IN (1,2)
    GROUP BY
        `uf`.`grnno`, `uf`.`tbl_company_idtbl_company`, `uf`.`tbl_company_branch_idtbl_company_branch`
) AS `u`";

// $extraWhere = "`u`.`status` IN (1, 2) AND `u`.`paytype`=0 AND `u`.`tbl_company_idtbl_company`='$companyid' AND `u`.`tbl_company_branch_idtbl_company_branch`='$branchid' AND `uf`.`tbl_company_idtbl_company`='$companyid' AND `uf`.`tbl_company_branch_idtbl_company_branch`='$branchid'";
$extraWhere = "`u`.`status` IN (1, 2) AND `u`.`paytype`=0 AND `u`.`tbl_company_idtbl_company`='$companyid' AND `u`.`tbl_company_branch_idtbl_company_branch`='$branchid' AND `u`.`grn_companyid`='$companyid' AND `u`.`grn_branchid`='$branchid'";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
