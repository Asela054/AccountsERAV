<?php

$table = 'tbl_asset';


$primaryKey = 'idtbl_asset';


$columns = array(
	array( 'db' => '`u`.`idtbl_asset`', 'dt' => 'idtbl_asset', 'field' => 'idtbl_asset' ),
	array( 'db' => '`u`.`asset_name`', 'dt' => 'asset_name', 'field' => 'asset_name' ),
	array( 'db' => '`u`.`asset_code`', 'dt' => 'asset_code', 'field' => 'asset_code' ),
	array( 'db' => '`u`.`currentyear`', 'dt' => 'currentyear', 'field' => 'currentyear' ),
	array( 'db' => '`u`.`depreciationyear`', 'dt' => 'depreciationyear', 'field' => 'depreciationyear' ),
	array( 'db' => 'u.depreciationstartdate', 'dt' => 'depreciationstartdate', 'field' => 'depreciationstartdate' ),
	array( 'db' => 'u.depreciationrate', 'dt' => 'depreciationrate', 'field' => 'depreciationrate' ),
	array( 'db' => '`u`.`assetdiscription`', 'dt' => 'assetdiscription', 'field' => 'assetdiscription' ),
	array( 'db' => '`u`.`purchasedate`', 'dt' => 'purchasedate', 'field' => 'purchasedate' ),

	array( 'db' => '`u`.`tbl_asset_type_idtbl_asset_type`', 'dt' => 'tbl_asset_type_idtbl_asset_type', 'field' => 'tbl_asset_type_idtbl_asset_type' ),
	array( 'db' => '`ua`.`asset_type`', 'dt' => 'asset_type', 'field' => 'asset_type' ),

    array( 'db' => '`u`.`tbl_depreciation_type_idtbl_depreciation_type`', 'dt' => 'tbl_depreciation_type_idtbl_depreciation_type', 'field' => 'tbl_depreciation_type_idtbl_depreciation_type' ),
	array( 'db' => '`ub`.`depreciation_type`', 'dt' => 'depreciation_type', 'field' => 'depreciation_type' ),

    array( 'db' => '`u`.`tbl_depreciation_category_idtbl_depreciation_category`', 'dt' => 'tbl_depreciation_category_idtbl_depreciation_category', 'field' => 'tbl_depreciation_category_idtbl_depreciation_category' ),
	array( 'db' => '`uc`.`depreciation_category`', 'dt' => 'depreciation_category', 'field' => 'depreciation_category' ),

    array( 'db' => '`u`.`tbl_depreciation_method_idtbl_depreciation_method`', 'dt' => 'tbl_depreciation_method_idtbl_depreciation_method', 'field' => 'tbl_depreciation_method_idtbl_depreciation_method' ),
	array( 'db' => '`ud`.`method`', 'dt' => 'method', 'field' => 'method' ),
    
    

	array( 'db' => '`u`.`status`', 'dt' => 'status', 'field' => 'status' )
);

require('config.php');
$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_host
);


require('ssp.customized.class.php' );

$joinQuery = "FROM `tbl_asset` AS `u` 
        LEFT JOIN `tbl_asset_type` AS `ua` ON(`u`.`tbl_asset_type_idtbl_asset_type` = `ua`.`idtbl_asset_type`)
        LEFT JOIN `tbl_depreciation_type` AS `ub` ON(`u`.`tbl_depreciation_type_idtbl_depreciation_type` = `ub`.`idtbl_depreciation_type`)
        LEFT JOIN `tbl_depreciation_category` AS `uc` ON(`u`.`tbl_depreciation_category_idtbl_depreciation_category` = `uc`.`idtbl_depreciation_category`)
        LEFT JOIN `tbl_depreciation_method` AS `ud` ON(`u`.`tbl_depreciation_method_idtbl_depreciation_method` = `ud`.`idtbl_depreciation_method`)";



$extraWhere = "`u`.`status` IN (1,2)";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
