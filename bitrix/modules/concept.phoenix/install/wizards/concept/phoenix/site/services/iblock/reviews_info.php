<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
set_time_limit(0);

global $DB;

$strSql = "SHOW TABLES LIKE 'phoenix_reviews_info_".WIZARD_SITE_ID."'";
$res = $DB->Query($strSql, false, $err_mess.__LINE__);

if($res->SelectedRowsCount() <= 0)
{
    $strSql = 
    "
        CREATE TABLE
            `phoenix_reviews_info_".WIZARD_SITE_ID."` (
                `ID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `PRODUCT_ID` INT(11) NULL DEFAULT NULL,
                `VOTE_SUM` INT(11) NULL DEFAULT NULL,
                `VOTE_COUNT` INT(11) NULL DEFAULT NULL,
                `RECOMMEND_COUNT_Y` INT(11) NULL DEFAULT NULL,
                `RECOMMEND_COUNT_N` INT(11) NULL DEFAULT NULL,
                `REVIEWS_COUNT` INT(11) NULL DEFAULT NULL,
                `VOTE_COUNT_1_2` INT(11) NULL DEFAULT NULL,
                `VOTE_COUNT_3` INT(11) NULL DEFAULT NULL,
                `VOTE_COUNT_4` INT(11) NULL DEFAULT NULL,
                `VOTE_COUNT_5` INT(11) NULL DEFAULT NULL
            )            
    ";
    
    $DB->Query($strSql, false, $err_mess.__LINE__);

}    


?>