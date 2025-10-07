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
			   array( 'db' => 'drv_doc.idtbl_bank_rec_list', 'dt' => 'bankrec_regno', 'field' => 'idtbl_bank_rec_list' ),
			   array( 'db' => 'drv_acc.accountname', 'dt' => 'bankacc_name', 'field' => 'accountname' ),
			   array( 'db' => 'drv_acc.accountno', 'dt' => 'bankacc_accountno', 'field' => 'accountno' ),
			   array( 'db' => 'drv_doc.acc_rec_batchno', 'dt' => 'bankrec_batchno', 'field' => 'acc_rec_batchno' ),
			   array( 'db' => 'drv_doc.tbl_account_idtbl_account', 'dt' => 'bankacc_regno', 'field' => 'tbl_account_idtbl_account' )
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

$joinQuery = "FROM (select idtbl_bank_rec_list, tbl_account_idtbl_account, acc_rec_batchno from tbl_bank_rec_list where rec_approved=1) as drv_doc inner join (select idtbl_account, accountname, accountno from tbl_account where tbl_account_type_idtbl_account_type=1) as drv_acc on drv_doc.tbl_account_idtbl_account=drv_acc.idtbl_account";

$extraWhere = "";//"`u`.`status` IN (1, 2)";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
