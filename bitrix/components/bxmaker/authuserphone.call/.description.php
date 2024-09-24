<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$PARTNER_ID = "bxmaker";

$arComponentDescription = array(
    "NAME" => GetMessage("BXMAKER.AUTHUSERPHONE.CALL.COMPONENT_NAME"),
    "DESCRIPTION" => GetMessage("BXMAKER.AUTHUSERPHONE.CALL.COMPONENT_DESCRIPTION"),
    "ICON" => "",
    "PATH" => array(
        "ID" => $PARTNER_ID,
        "NAME" => GetMessage("BXMAKER.AUTHUSERPHONE.CALL.DEVELOP_GROUP"),
        "CHILD" => array(
            "ID" => "user",
            "NAME" => GetMessage("BXMAKER.AUTHUSERPHONE.CALL.USER_COMPONENT_GROUP")
        )
    ),
);
unset($PARTNER_ID,$PARTNER_COMPONENT_ID);
