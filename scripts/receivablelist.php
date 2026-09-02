<?php
require_once '../external.php';

$CI =& get_instance();
$CI->load->library('session');

$configdata = getconfigdata('receivable_search');
$tablename = $configdata->row(0)->tbl_name;
$column1   = $configdata->row(0)->col_name;
$column2   = $configdata->row(1)->col_name;

$table = 'tbl_receivable';
$primaryKey = 'idtbl_receivable';

$columns = array(
	array( 'db' => '`u`.`idtbl_receivable`', 'dt' => 'idtbl_receivable', 'field' => 'idtbl_receivable' ),
	array( 'db' => '`u`.`recdate`', 'dt' => 'recdate', 'field' => 'recdate' ),
	array( 'db' => '`u`.`batchno`', 'dt' => 'batchno', 'field' => 'batchno' ),
	array( 'db' => '`u`.`amount`', 'dt' => 'amount', 'field' => 'amount' ),
	array( 'db' => '`u`.`poststatus`', 'dt' => 'poststatus', 'field' => 'poststatus' ),
	array( 'db' => '`u`.`customer`', 'dt' => 'customer', 'field' => 'customer' ),
	array( 'db' => '`u`.`company`', 'dt' => 'company', 'field' => 'company' ),
	array( 'db' => '`u`.`branch`', 'dt' => 'branch', 'field' => 'branch' ),
	array( 'db' => '`u`.`desc`', 'dt' => 'desc', 'field' => 'desc' ),
	array( 'db' => '`u`.`monthname`', 'dt' => 'monthname', 'field' => 'monthname' ),
	array( 'db' => '`u`.`idtbl_receivable_type`', 'dt' => 'idtbl_receivable_type', 'field' => 'idtbl_receivable_type' ),
	array( 'db' => '`u`.`receivabletype`', 'dt' => 'receivabletype', 'field' => 'receivabletype' ),
	array( 'db' => '`u`.`accountno`', 'dt' => 'accountno', 'field' => 'accountno' ),
	array( 'db' => '`u`.`accountname`', 'dt' => 'accountname', 'field' => 'accountname' ),
	array( 'db' => '`u`.`detailaccountno`', 'dt' => 'detailaccountno', 'field' => 'detailaccountno' ),
	array( 'db' => '`u`.`detailaccountname`', 'dt' => 'detailaccountname', 'field' => 'detailaccountname' ),
	array( 'db' => '`u`.`postdatedstatus`', 'dt' => 'postdatedstatus', 'field' => 'postdatedstatus' ),
	array( 'db' => '`u`.`chequedate`', 'dt' => 'chequedate', 'field' => 'chequedate' ),
	array( 'db' => '`u`.`receiptno`', 'dt' => 'receiptno', 'field' => 'receiptno' ),
	array( 'db' => '`u`.`status`', 'dt' => 'status', 'field' => 'status' ),
	array( 'db' => '`u`.`recsettlefiltertype`', 'dt' => 'recsettlefiltertype', 'field' => 'recsettlefiltertype' ), // NEW
);

require('config.php');
$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_host
);

require('ssp.customized.class.php' );

$companyid=$_SESSION['companyid'];
$branchid=$_SESSION['branchid'];

$joinQuery = "FROM (
    SELECT
        `u`.`idtbl_receivable`,
        `u`.`recdate`,
        `u`.`batchno`,
        `u`.`amount`,
        `u`.`poststatus`,
        `u`.`postdatedstatus`,
        `u`.`chequedate`,
        `u`.`receiptno`,
        `u`.`status`,
        `u`.`tbl_company_idtbl_company`,
        `u`.`tbl_company_branch_idtbl_company_branch`,
        `ui`.`$column2` AS `customer`,
        `ua`.`company`,
        `ub`.`branch`,
        `ud`.`desc`,
        `ue`.`monthname`,
        `uf`.`idtbl_receivable_type`,
        `uf`.`receivabletype`,
        `ug`.`accountno`,
        `ug`.`accountname`,
        `uh`.`accountno`   AS `detailaccountno`,
        `uh`.`accountname` AS `detailaccountname`,
        (CASE
            WHEN `u`.`recsettlefiltertype` = 1 THEN 'Receivable Customer'
            WHEN `u`.`recsettlefiltertype` = 2 THEN 'Receivable Journal'
            WHEN `u`.`recsettlefiltertype` = 3 THEN 'Receivable Voucher'
            ELSE 'Unknown'
        END) AS `recsettlefiltertype`
    FROM `tbl_receivable` AS `u`
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
    LEFT JOIN `tbl_receivable_type` AS `uf`
        ON (`uf`.`idtbl_receivable_type` = `u`.`tbl_receivable_type_idtbl_receivable_type`)
    LEFT JOIN `tbl_account` AS `ug`
        ON (`ug`.`idtbl_account` = `u`.`tbl_account_idtbl_account`)
    LEFT JOIN `tbl_account_detail` AS `uh`
        ON (`uh`.`idtbl_account_detail` = `u`.`tbl_account_detail_idtbl_account_detail`)
    LEFT JOIN `$tablename` AS `ui`
        ON (`ui`.`$column1` = `u`.`payer`)
) AS `u`";

if($_POST['filterpost']==1){$extraWhere = "`u`.`status` IN (1, 2) AND `u`.`tbl_company_idtbl_company`='$companyid' AND `u`.`tbl_company_branch_idtbl_company_branch`='$branchid' AND `u`.`postdatedstatus`=1 AND `u`.`poststatus`=0";}
if(!empty($_POST['filterdate'])){$extraWhere = "`u`.`status` IN (1, 2) AND `u`.`tbl_company_idtbl_company`='$companyid' AND `u`.`tbl_company_branch_idtbl_company_branch`='$branchid' AND `u`.`postdatedstatus`=1 AND `u`.`poststatus`=0 AND `u`.`chequedate` = '".$_POST['filterdate']."'";}
else{$extraWhere = "`u`.`status` IN (1, 2, 3) AND `u`.`tbl_company_idtbl_company`='$companyid' AND `u`.`tbl_company_branch_idtbl_company_branch`='$branchid'";}

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);