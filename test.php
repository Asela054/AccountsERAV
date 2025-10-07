<?php
// $servername = "localhost";
// $username = "root";
// $password = "asela123";
// $dbname = "erav_account";
// $conn = mysqli_connect($servername, $username, $password, $dbname);
// if (!$conn) {
//     die("Connection failed: " . mysqli_connect_error());
// }

// // $userID=$_SESSION['userid'];
// $filename = "tbl_sales_info1.csv";
// $updatedatetime=date('Y-m-d h:i:s');

// $file = fopen($filename, 'r');
// $i=0;
// while (($line = fgetcsv($file)) !== FALSE) {
//     // print_r($line);
//     $invoice=$line[0];
//     $amount=$line[2];
//     if(is_numeric($invoice)){      
//         $update="UPDATE `tbl_sales_info` SET `amount`='$amount',`poststatus`='0' WHERE `invno`='$invoice'";
//         $conn->query($update);
//     }
// }
// fclose($file);



// Here’s a classification of the accounts based on the provided categories:

//     ### 1 - Payroll Expenses
//     - EX002200 Salaries & Wages
//     - EX002201 EPF - Production
//     - EX002202 ETF - Production
//     - EX002203 Employees Travelling Expenses 
//     - EX002204 Employees Incentive
//     - EX002205 Sundry Wages 
//     - EX004009 Salaries & Wages - Administrative 
//     - EX004010 EPF-Administration
//     - EX004011 ETF-Administration
//     - LI009500 Salary Payment Suspense
//     - LI009501 Employees Provident Fund Reserve
//     - LI009502 Employees Trust Fund Reserve
//     - LI009503 Employees Travelling Expenses Reserve
//     - LI009504 Employees Incentive Reserve
    
//     ### 2 - Property, Plant & Equipment
//     - AS007000 Land & Building
//     - AS007001 Land & Building - Accumulated Depreciation
//     - AS007002 Motor Vehicles
//     - AS007003 Motor Vehicles - Accumulated Depreciation
//     - AS007004 Plant & Machinery
//     - AS007005 Plant & Machinery - Accumulated Depreciation
//     - AS007006 Factory Equipment
//     - AS007007 Factory Equipment - Accumulated Depreciation
//     - AS007008 Office Equipment
//     - AS007009 Office Equipment - Accumulated Depreciation
//     - AS007010 Tools & Implements
//     - AS007011 Tools & Implements - Accumulated Depreciation
//     - AS007012 Kuk Dong Goods Lift
//     - AS007013 Kuk Dong Goods Lift - Accumulated Depreciation
//     - AS007014 Furniture Fittings
//     - AS007015 Furniture Fittings - Accumulated Depreciation
//     - AS007016 Building
//     - AS007017 Building - Accumulated Depreciation
//     - AS007018 Software System
//     - AS007019 Software System - Accumulated Depreciation
//     - AS007020 Fire Extinguisher
//     - AS007021 Fire Extinguisher - Accumulated Depreciation
//     - AS007100 Capital Work-In-Progress
    
//     ### 3 - Inventories
//     - AS008000 Material Stock
//     - AS008001 Sundry Stock
//     - AS008603 Multi Offset Printers
//     - AS008604 Goods in Transit
    
//     ### 4 - Trade Receivables
//     - AS008300 Trade Debtors
//     - AS008400 Staff Debtors
//     - AS008401 Other Debtors
    
//     ### 5 - Income Tax Recoverable
//     - LI009506 Income Tax Payable
    
//     ### 6 - Stated Capital
//     - EQ009900 Share Capital Account
    
//     ### 7 - Retained Profit
//     - EQ009950 Profit & Loss Reserve Account
    
//     ### 8 - Directors Investment
//     - LI009700 Directors Current Account
//     - LI009800 Directors Loan Account
    
//     ### 9 - Trade Payable
//     - LI009000  Trade Creditors
//     - LI009001 Other Creditor
//     - LI009600 Rajah Multi Industries
//     - LI009650 Other Payable Loan Accounts
//     - LI009601 Fair Trading House (Pvt) Ltd
    
//     ### 10 - Interest Bearing Loans & Borrowings
//     - LI009802 Hattan National Bank Loan
//     - LI009801 H.N.B. Leasing
//     - LI009657 H.N.B. Short Term Loan
    
//     ### 11 - Bank Overdraft
//     - EX006000 Interest on Bank Overdraft
    
//     ### 12 - Cash on Hand
//     - AS008900 Petty Cash
//     - AS008950 Cash in Hand
    
//     This classification assigns the accounts to the respective types as per your provided categories.

An uncaught Exception was encountered
Type: Error

Message: Using $this when not in object context

Filename: D:\xampp\htdocs\accountscode\application\helpers\useracc_helper.php

Line Number: 513

Backtrace:

File: D:\xampp\htdocs\accountscode\application\controllers\Openbalance.php
Line: 36
Function: get_all_accounts

File: D:\xampp\htdocs\accountscode\index.php
Line: 315
Function: require_once

?>