-- MySQL Workbench Synchronization
-- Generated: 2026-05-18 16:33
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: asela

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER SCHEMA `erav_multioffset`  DEFAULT CHARACTER SET utf8mb4  DEFAULT COLLATE utf8mb4_general_ci ;

ALTER TABLE `erav_multioffset`.`tbl_user` 
DROP FOREIGN KEY `fk_tbl_user_tbl_user_type1`;

ALTER TABLE `erav_multioffset`.`tbl_supplier` 
DROP FOREIGN KEY `fk_tbl_supplier_tbl_tbl_company_branch1`,
DROP FOREIGN KEY `fk_tbl_supplier_tbl_tbl_company1`;

ALTER TABLE `erav_multioffset`.`tbl_customer` 
DROP FOREIGN KEY `fk_tbl_print_material_info_tbl_company_branch1`,
DROP FOREIGN KEY `fk_tbl_print_material_info_tbl_company1`;

ALTER TABLE `erav_multioffset`.`tbl_menu_list` 
CHANGE COLUMN `menu` `menu` VARCHAR(450) NOT NULL ;

ALTER TABLE `erav_multioffset`.`tbl_user` 
CHARACTER SET = utf8 , COLLATE = utf8_general_ci ,
CHANGE COLUMN `name` `name` VARCHAR(150) NOT NULL ,
ADD INDEX `fk_tbl_user_tbl_user_type_idx` (`tbl_user_type_idtbl_user_type` ASC),
DROP INDEX `fk_tbl_user_tbl_user_type1_idx` ;

ALTER TABLE `erav_multioffset`.`tbl_user_type` 
CHANGE COLUMN `type` `type` VARCHAR(450) NOT NULL ;

ALTER TABLE `erav_multioffset`.`tbl_bank` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_bank_branch` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_account_category` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_account_subcategory` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_account` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_account_transaction` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_finacial_year` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_finacial_month` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_master` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_company` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_company_branch` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_account_allocation` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_cheque_info` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_pettycash` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_pettycash_reimburse` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_account_finacialtype` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_account_transactiontype` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_account_type` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_account_receivable` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_account_payable` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_account_transaction_full` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_account_receivable_main` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_account_payable_main` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_account_detail` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ,
CHANGE COLUMN `accountname` `accountname` VARCHAR(45) NOT NULL ;

ALTER TABLE `erav_multioffset`.`tbl_batch_num_register` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_account_paysettle` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_account_paysettle_info` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_cheque_issue` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_account_paysettle_has_tbl_cheque_issue` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_pettycash_summary` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_pettycash_reimburse_has_tbl_pettycash` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_supplier` 
CHANGE COLUMN `supcode` `supcode` VARCHAR(45) NOT NULL ,
ADD INDEX `fk_tbl_supplier_tbl_company1_idx` (`tbl_company_idtbl_company` ASC),
ADD INDEX `fk_tbl_supplier_tbl_company_branch1_idx` (`tbl_company_branch_idtbl_company_branch` ASC),
DROP INDEX `fk_tbl_supplier_tbl_tbl_company_branch1_idx` ,
DROP INDEX `fk_tbl_supplier_tbl_tbl_company1_idx` ;

ALTER TABLE `erav_multioffset`.`tbl_customer` 
CHANGE COLUMN `vat_customer` `vat_customer` INT(11) NOT NULL COMMENT '0-non vat\\r\\n1-vat customer	' ,
CHANGE COLUMN `imagepath` `imagepath` MEDIUMTEXT NOT NULL ,
ADD INDEX `fk_tbl_customer_tbl_company1_idx` (`tbl_company_idtbl_company` ASC),
ADD INDEX `fk_tbl_customer_tbl_company_branch1_idx` (`tbl_company_branch_idtbl_company_branch` ASC),
DROP INDEX `fk_tbl_print_material_info_tbl_company_branch1_idx` ,
DROP INDEX `fk_tbl_print_material_info_tbl_company1_idx` ;

ALTER TABLE `erav_multioffset`.`tbl_sales_info` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_expence_info` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_account_open_bal` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_receivable` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_receivable_info` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_receivable_type` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_bank_rec_info` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_bank_rec_list` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_bank_rec_revision` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_gl_report_head_sections` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_gl_report_sub_section_particulars` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_gl_report_sub_sections` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_account_transaction_manual` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_account_transaction_manual_main` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_account_special_category` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_account_nestcategory` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_asset` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_asset_type` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_asset_sell` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_asset_destroy` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_upgrade_dipreciation` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_depreciation_category` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_depreciation_method` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_depreciation_type` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_depreciation_info` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_asset_has_tbl_account_detail` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_other_payincome` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_batch_trans_type` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_batch_trans_type_info` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_batch_trans_type_tax` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_batch_category` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_batch_transaction` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_batch_transaction_main` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_account_detail_other` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_receivable_entry` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_account_paysettle_entry` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_supplier_type` 
CHANGE COLUMN `type` `type` VARCHAR(45) NULL DEFAULT NULL ;

ALTER TABLE `erav_multioffset`.`tbl_material_type` 
CHARACTER SET = utf8mb4 , COLLATE = DEFAULT ;

ALTER TABLE `erav_multioffset`.`tbl_user` 
ADD CONSTRAINT `fk_tbl_user_tbl_user_type`
  FOREIGN KEY (`tbl_user_type_idtbl_user_type`)
  REFERENCES `erav_multioffset`.`tbl_user_type` (`idtbl_user_type`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `erav_multioffset`.`tbl_supplier` 
ADD CONSTRAINT `fk_tbl_supplier_tbl_company1`
  FOREIGN KEY (`tbl_company_idtbl_company`)
  REFERENCES `erav_multioffset`.`tbl_company` (`idtbl_company`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_tbl_supplier_tbl_company_branch1`
  FOREIGN KEY (`tbl_company_branch_idtbl_company_branch`)
  REFERENCES `erav_multioffset`.`tbl_company_branch` (`idtbl_company_branch`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `erav_multioffset`.`tbl_customer` 
ADD CONSTRAINT `fk_tbl_customer_tbl_company1`
  FOREIGN KEY (`tbl_company_idtbl_company`)
  REFERENCES `erav_multioffset`.`tbl_company` (`idtbl_company`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_tbl_customer_tbl_company_branch1`
  FOREIGN KEY (`tbl_company_branch_idtbl_company_branch`)
  REFERENCES `erav_multioffset`.`tbl_company_branch` (`idtbl_company_branch`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
