<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
set_time_limit(0);

global $DB;

$strSql = "SHOW TABLES LIKE 'phoenix_comments_".WIZARD_SITE_ID."'";
$res = $DB->Query($strSql, false, $err_mess.__LINE__);

if($res->SelectedRowsCount() <= 0)
{
    $strSql = 
    "
        CREATE TABLE
            `phoenix_comments_".WIZARD_SITE_ID."` (
                `ID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `ACTIVE` CHAR(1) NOT NULL DEFAULT 'N',
                `DATE` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `ELEMENT_ID` INT(11) NULL DEFAULT NULL,
                `USER_ID` INT(11) NULL DEFAULT NULL,
                `USER_NAME` CHAR(100) NULL DEFAULT NULL,
                `USER_EMAIL` CHAR(250) NULL DEFAULT NULL,
                `TEXT` TEXT NULL DEFAULT NULL,
                `RESPONSE` TEXT NULL DEFAULT NULL
            )            
    ";
    
    $DB->Query($strSql, false, $err_mess.__LINE__);

}    


?>