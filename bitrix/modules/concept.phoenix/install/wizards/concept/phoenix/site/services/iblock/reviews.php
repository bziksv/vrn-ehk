<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
set_time_limit(0);

global $DB;

$strSql = "SHOW TABLES LIKE 'phoenix_reviews_".WIZARD_SITE_ID."'";
$res = $DB->Query($strSql, false, $err_mess.__LINE__);

if($res->SelectedRowsCount() <= 0)
{
    $strSql = 
    "
        CREATE TABLE
            `phoenix_reviews_".WIZARD_SITE_ID."` (
                `ID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `ACTIVE` CHAR(1) NOT NULL DEFAULT 'N',
                `DATE` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `PRODUCT_ID` INT(11) NULL DEFAULT NULL,
                `ACCOUNT_NUMBER` CHAR(255) NULL DEFAULT NULL,
                `USER_ID` INT(11) NULL DEFAULT NULL,
                `USER_NAME` CHAR(100) NULL DEFAULT NULL,
                `USER_EMAIL` CHAR(250) NULL DEFAULT NULL,
                `VOTE` INT(1) NULL DEFAULT NULL,
                `RECOMMEND` CHAR(1) NULL DEFAULT NULL,
                `EXPERIENCE` CHAR(1) NULL DEFAULT NULL,
                `LIKE_COUNT` INT(11) NULL DEFAULT 0,
                `IMAGES_ID` TEXT NULL DEFAULT NULL,
                `TEXT` TEXT NULL DEFAULT NULL,
                `POSITIVE` TEXT NULL DEFAULT NULL,
                `NEGATIVE` TEXT NULL DEFAULT NULL,
                `STORE_RESPONSE` TEXT NULL DEFAULT NULL
            )            
    ";
    
    $DB->Query($strSql, false, $err_mess.__LINE__);

}    


?>