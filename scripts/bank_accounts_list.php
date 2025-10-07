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
$table = 'tbl_account';

// Table's primary key
$primaryKey = 'idtbl_account';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
/*

*/
$columns = array(
			   array( 'db' => 'drv_acc.idtbl_account', 'dt' => 'bankacc_regno', 'field' => 'idtbl_account' ),
			   array( 'db' => 'drv_acc.accountname', 'dt' => 'bankacc_name', 'field' => 'accountname' ),
			   array( 'db' => 'drv_acc.accountno', 'dt' => 'bankacc_accountno', 'field' => 'accountno' )
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

$joinQuery = "FROM (select distinct tbl_account_idtbl_account, tbl_bank_idtbl_bank, tbl_bank_branch_idtbl_bank_branch from tbl_cheque_info where status=1) as drv_doc inner join (select idtbl_account, accountname, accountno from tbl_account where tbl_account_type_idtbl_account_type=1) as drv_acc on drv_doc.tbl_account_idtbl_account=drv_acc.idtbl_account";

$extraWhere = "";//"`u`.`status` IN (1, 2)";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
