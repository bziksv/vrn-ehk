<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
set_time_limit(0);

if(!CModule::IncludeModule("iblock"))
    return;

//IBLOCKS

$arIBlock = Array();

$res = CIBlock::GetList(
    Array(), 
    Array(
        'TYPE'=>'concept_phoenix_'.WIZARD_SITE_ID, 
        'ACTIVE'=>'Y', 
    ), true
);

while($ar_res = $res->Fetch())
    $arIBlock[$ar_res["CODE"]] = $ar_res["ID"];





//banners
$arUF = Array();
$rsData = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID"=>"IBLOCK_".$arIBlock["concept_phoenix_banners_".WIZARD_SITE_ID]."_SECTION"));
while($arRes = $rsData->Fetch())
    $arUF[$arRes["FIELD_NAME"]] = $arRes["ID"];


$arProperty = array();
$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $arIBlock["concept_phoenix_banners_".WIZARD_SITE_ID]));

while($arProp = $dbProperty->Fetch())
    $arProperty[$arProp["CODE"]] = $arProp["ID"];


if($arProperty["BANNER_BTN_FORM"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_forms_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BANNER_BTN_FORM"], $arFields); 
} 

if($arProperty["BANNER_BTN_MODAL"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_modals_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BANNER_BTN_MODAL"], $arFields); 
} 

if($arProperty["BANNER_BTN_LAND"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_site_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BANNER_BTN_LAND"], $arFields); 
} 



//employee
$arUF = Array();
$rsData = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID"=>"IBLOCK_".$arIBlock["concept_phoenix_employee_".WIZARD_SITE_ID]."_SECTION"));
while($arRes = $rsData->Fetch())
    $arUF[$arRes["FIELD_NAME"]] = $arRes["ID"];


$arProperty = array();
$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $arIBlock["concept_phoenix_employee_".WIZARD_SITE_ID]));

while($arProp = $dbProperty->Fetch())
    $arProperty[$arProp["CODE"]] = $arProp["ID"];


if($arProperty["EMPL_FORMS"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_forms_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["EMPL_FORMS"], $arFields); 
} 



//news
$arUF = Array();
$rsData = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID"=>"IBLOCK_".$arIBlock["concept_phoenix_news_".WIZARD_SITE_ID]."_SECTION"));
while($arRes = $rsData->Fetch())
    $arUF[$arRes["FIELD_NAME"]] = $arRes["ID"];


$arProperty = array();
$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $arIBlock["concept_phoenix_news_".WIZARD_SITE_ID]));

while($arProp = $dbProperty->Fetch())
    $arProperty[$arProp["CODE"]] = $arProp["ID"];


if($arUF["UF_PHX_NW_T_ID"] > 0)
{
    $oUserTypeEntity = new CUserTypeEntity();
    $oUserTypeEntity->Update($arUF["UF_PHX_NW_T_ID"], 
        array(
            "SETTINGS" => Array
            (
                "IBLOCK_ID" => $arIBlock["concept_phoenix_site_".WIZARD_SITE_ID],
            )
        ) 
    );
}

if($arUF["UF_PHX_NW_BNNRS"] > 0)
{
    $oUserTypeEntity = new CUserTypeEntity();
    $oUserTypeEntity->Update($arUF["UF_PHX_NW_BNNRS"], 
        array(
            "SETTINGS" => Array
            (
                "DISPLAY" => "CHECKBOX",
                "IBLOCK_ID" => $arIBlock["concept_phoenix_banners_".WIZARD_SITE_ID],
            )
        ) 
    );
}


if($arProperty["NEWS_ELEMENTS_BLOG"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_blog_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["NEWS_ELEMENTS_BLOG"], $arFields); 
} 

if($arProperty["NEWS_ELEMENTS_NEWS"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_news_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["NEWS_ELEMENTS_NEWS"], $arFields); 
} 

if($arProperty["NEWS_ELEMENTS_ACTION"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_action_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["NEWS_ELEMENTS_ACTION"], $arFields); 
} 

if($arProperty["CATALOG"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_catalog_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["CATALOG"], $arFields); 
} 

if($arProperty["BANNERS_RIGHT"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_banners_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BANNERS_RIGHT"], $arFields); 
}

if($arProperty["CHOOSE_LANDING"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_site_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["CHOOSE_LANDING"], $arFields); 
}

if($arProperty["BUTTON_FORM"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_forms_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BUTTON_FORM"], $arFields); 
}

if($arProperty["BUTTON_MODAL"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_modals_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BUTTON_MODAL"], $arFields); 
}

if($arProperty["BUTTON_LAND"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_site_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BUTTON_LAND"], $arFields); 
}

if($arProperty["REGION"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_regions_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["REGION"], $arFields); 
}


//action
$arUF = Array();
$rsData = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID"=>"IBLOCK_".$arIBlock["concept_phoenix_action_".WIZARD_SITE_ID]."_SECTION"));
while($arRes = $rsData->Fetch())
    $arUF[$arRes["FIELD_NAME"]] = $arRes["ID"];


$arProperty = array();
$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $arIBlock["concept_phoenix_action_".WIZARD_SITE_ID]));

while($arProp = $dbProperty->Fetch())
    $arProperty[$arProp["CODE"]] = $arProp["ID"];


if($arProperty["NEWS_ELEMENTS_BLOG"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_blog_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["NEWS_ELEMENTS_BLOG"], $arFields); 
} 

if($arProperty["NEWS_ELEMENTS_NEWS"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_news_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["NEWS_ELEMENTS_NEWS"], $arFields); 
} 

if($arProperty["NEWS_ELEMENTS_ACTION"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_action_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["NEWS_ELEMENTS_ACTION"], $arFields); 
} 

if($arProperty["CATALOG"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_catalog_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["CATALOG"], $arFields); 
} 

if($arProperty["BANNERS_RIGHT"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_banners_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BANNERS_RIGHT"], $arFields); 
}

if($arProperty["CHOOSE_LANDING"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_site_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["CHOOSE_LANDING"], $arFields); 
}

if($arProperty["BUTTON_FORM"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_forms_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BUTTON_FORM"], $arFields); 
}

if($arProperty["BUTTON_MODAL"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_modals_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BUTTON_MODAL"], $arFields); 
}

if($arProperty["BUTTON_LAND"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_site_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BUTTON_LAND"], $arFields); 
}

if($arProperty["REGION"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_regions_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["REGION"], $arFields); 
}


//blog
$arUF = Array();
$rsData = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID"=>"IBLOCK_".$arIBlock["concept_phoenix_blog_".WIZARD_SITE_ID]."_SECTION"));
while($arRes = $rsData->Fetch())
    $arUF[$arRes["FIELD_NAME"]] = $arRes["ID"];


$arProperty = array();
$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $arIBlock["concept_phoenix_blog_".WIZARD_SITE_ID]));

while($arProp = $dbProperty->Fetch())
    $arProperty[$arProp["CODE"]] = $arProp["ID"];


if($arUF["UF_PHX_BLG_T_ID"] > 0)
{
    $oUserTypeEntity = new CUserTypeEntity();
    $oUserTypeEntity->Update($arUF["UF_PHX_BLG_T_ID"], 
        array(
            "SETTINGS" => Array
            (
                "IBLOCK_ID" => $arIBlock["concept_phoenix_site_".WIZARD_SITE_ID],
            )
        ) 
    );
}

if($arUF["UF_PHX_BLG_BNNRS"] > 0)
{
    $oUserTypeEntity = new CUserTypeEntity();
    $oUserTypeEntity->Update($arUF["UF_PHX_BLG_BNNRS"], 
        array(
            "SETTINGS" => Array
            (
                "DISPLAY" => "CHECKBOX",
                "IBLOCK_ID" => $arIBlock["concept_phoenix_banners_".WIZARD_SITE_ID],
            )
        ) 
    );
}


if($arProperty["NEWS_ELEMENTS_BLOG"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_blog_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["NEWS_ELEMENTS_BLOG"], $arFields); 
} 

if($arProperty["NEWS_ELEMENTS_NEWS"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_news_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["NEWS_ELEMENTS_NEWS"], $arFields); 
} 

if($arProperty["NEWS_ELEMENTS_ACTION"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_action_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["NEWS_ELEMENTS_ACTION"], $arFields); 
} 

if($arProperty["CATALOG"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_catalog_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["CATALOG"], $arFields); 
} 

if($arProperty["BANNERS_RIGHT"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_banners_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BANNERS_RIGHT"], $arFields); 
}

if($arProperty["CHOOSE_LANDING"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_site_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["CHOOSE_LANDING"], $arFields); 
}

if($arProperty["BUTTON_FORM"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_forms_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BUTTON_FORM"], $arFields); 
}

if($arProperty["BUTTON_MODAL"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_modals_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BUTTON_MODAL"], $arFields); 
}

if($arProperty["BUTTON_LAND"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_site_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BUTTON_LAND"], $arFields); 
}

if($arProperty["REGION"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_regions_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["REGION"], $arFields); 
}




//menu
$arUF = Array();
$rsData = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID"=>"IBLOCK_".$arIBlock["concept_phoenix_menu_".WIZARD_SITE_ID]."_SECTION"));
while($arRes = $rsData->Fetch())
    $arUF[$arRes["FIELD_NAME"]] = $arRes["ID"];


$arProperty = array();
$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $arIBlock["concept_phoenix_menu_".WIZARD_SITE_ID]));

while($arProp = $dbProperty->Fetch())
    $arProperty[$arProp["CODE"]] = $arProp["ID"];

if($arUF["UF_PHX_LAND"] > 0)
{
    $oUserTypeEntity = new CUserTypeEntity();
    $oUserTypeEntity->Update($arUF["UF_PHX_LAND"], 
        array(
            "SETTINGS" => Array
            (
                "IBLOCK_ID" => $arIBlock["concept_phoenix_site_".WIZARD_SITE_ID],
            )
        ) 
    );
}

if($arUF["UF_PHX_M_FORM"] > 0)
{
    $oUserTypeEntity = new CUserTypeEntity();
    $oUserTypeEntity->Update($arUF["UF_PHX_M_FORM"], 
        array(
            "SETTINGS" => Array
            (
                "IBLOCK_ID" => $arIBlock["concept_phoenix_forms_".WIZARD_SITE_ID],
            )
        ) 
    );
}

if($arUF["UF_PHX_M_MODAL"] > 0)
{
    $oUserTypeEntity = new CUserTypeEntity();
    $oUserTypeEntity->Update($arUF["UF_PHX_M_MODAL"], 
        array(
            "SETTINGS" => Array
            (
                "IBLOCK_ID" => $arIBlock["concept_phoenix_modals_".WIZARD_SITE_ID],
            )
        ) 
    );
}

if($arUF["UF_PHX_MENU_REGION"] > 0)
{
    $oUserTypeEntity = new CUserTypeEntity();
    $oUserTypeEntity->Update($arUF["UF_PHX_MENU_REGION"], 
        array(
            "SETTINGS" => Array
            (
                "DISPLAY" => "CHECKBOX",
                "IBLOCK_ID" => $arIBlock["concept_phoenix_regions_".WIZARD_SITE_ID],
            )
        ) 
    );
}

if($arUF["UF_BANNERS"] > 0)
{
    $oUserTypeEntity = new CUserTypeEntity();
    $oUserTypeEntity->Update($arUF["UF_BANNERS"], 
        array(
            "SETTINGS" => Array
            (
                "DISPLAY" => "CHECKBOX",
                "IBLOCK_ID" => $arIBlock["concept_phoenix_banners_".WIZARD_SITE_ID],
            )
        ) 
    );
}




//footer menu
$arUF = Array();
$rsData = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID"=>"IBLOCK_".$arIBlock["concept_phoenix_footer_menu_".WIZARD_SITE_ID]."_SECTION"));
while($arRes = $rsData->Fetch())
    $arUF[$arRes["FIELD_NAME"]] = $arRes["ID"];


$arProperty = array();
$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $arIBlock["concept_phoenix_footer_menu_".WIZARD_SITE_ID]));

while($arProp = $dbProperty->Fetch())
    $arProperty[$arProp["CODE"]] = $arProp["ID"];

if($arUF["UF_PHX_LAND"] > 0)
{
    $oUserTypeEntity = new CUserTypeEntity();
    $oUserTypeEntity->Update($arUF["UF_PHX_LAND"], 
        array(
            "SETTINGS" => Array
            (
                "IBLOCK_ID" => $arIBlock["concept_phoenix_site_".WIZARD_SITE_ID],
            )
        ) 
    );
}

if($arUF["UF_PHX_M_FORM"] > 0)
{
    $oUserTypeEntity = new CUserTypeEntity();
    $oUserTypeEntity->Update($arUF["UF_PHX_M_FORM"], 
        array(
            "SETTINGS" => Array
            (
                "IBLOCK_ID" => $arIBlock["concept_phoenix_forms_".WIZARD_SITE_ID],
            )
        ) 
    );
}

if($arUF["UF_PHX_M_MODAL"] > 0)
{
    $oUserTypeEntity = new CUserTypeEntity();
    $oUserTypeEntity->Update($arUF["UF_PHX_M_MODAL"], 
        array(
            "SETTINGS" => Array
            (
                "IBLOCK_ID" => $arIBlock["concept_phoenix_modals_".WIZARD_SITE_ID],
            )
        ) 
    );
}

if($arUF["UF_PHX_MENU_REGION"] > 0)
{
    $oUserTypeEntity = new CUserTypeEntity();
    $oUserTypeEntity->Update($arUF["UF_PHX_MENU_REGION"], 
        array(
            "SETTINGS" => Array
            (
                "DISPLAY" => "CHECKBOX",
                "IBLOCK_ID" => $arIBlock["concept_phoenix_regions_".WIZARD_SITE_ID],
            )
        ) 
    );
}


//catalog
$arUF = Array();
$rsData = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID"=>"IBLOCK_".$arIBlock["concept_phoenix_catalog_".WIZARD_SITE_ID]."_SECTION"));
while($arRes = $rsData->Fetch())
    $arUF[$arRes["FIELD_NAME"]] = $arRes["ID"];


$arProperty = array();
$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $arIBlock["concept_phoenix_catalog_".WIZARD_SITE_ID]));

while($arProp = $dbProperty->Fetch())
    $arProperty[$arProp["CODE"]] = $arProp["ID"];

if($arUF["UF_PHX_CTLG_T_ID"] > 0)
{
    $oUserTypeEntity = new CUserTypeEntity();
    $oUserTypeEntity->Update($arUF["UF_PHX_CTLG_T_ID"], 
        array(
            "SETTINGS" => Array
            (
                "IBLOCK_ID" => $arIBlock["concept_phoenix_site_".WIZARD_SITE_ID],
            )
        ) 
    );
}

if($arUF["UF_PHX_CTLG_ADV"] > 0)
{
    $oUserTypeEntity = new CUserTypeEntity();
    $oUserTypeEntity->Update($arUF["UF_PHX_CTLG_ADV"], 
        array(
            "SETTINGS" => Array
            (
                "DISPLAY" => "CHECKBOX",
                "IBLOCK_ID" => $arIBlock["concept_phoenix_advantages_".WIZARD_SITE_ID],
            )
        ) 
    );
}

if($arUF["UF_PHX_CTLG_BNNRS"] > 0)
{
    $oUserTypeEntity = new CUserTypeEntity();
    $oUserTypeEntity->Update($arUF["UF_PHX_CTLG_BNNRS"], 
        array(
            "SETTINGS" => Array
            (
                "DISPLAY" => "CHECKBOX",
                "IBLOCK_ID" => $arIBlock["concept_phoenix_banners_".WIZARD_SITE_ID],
            )
        ) 
    );
}

if($arUF["UF_SIMILAR_CATEGORY"] > 0)
{
    $oUserTypeEntity = new CUserTypeEntity();
    $oUserTypeEntity->Update($arUF["UF_SIMILAR_CATEGORY"], 
        array(
            "SETTINGS" => Array
            (
                "DISPLAY" => "CHECKBOX",
                "IBLOCK_ID" => $arIBlock["concept_phoenix_catalog_".WIZARD_SITE_ID],
            )
        ) 
    );
}

if($arUF["UF_EMPL_BANNER"] > 0)
{
    $oUserTypeEntity = new CUserTypeEntity();
    $oUserTypeEntity->Update($arUF["UF_EMPL_BANNER"], 
        array(
            "SETTINGS" => Array
            (
                "DISPLAY" => "CHECKBOX",
                "IBLOCK_ID" => $arIBlock["concept_phoenix_employee_".WIZARD_SITE_ID],
            )
        ) 
    );
}

if($arProperty["CHOOSE_LANDING"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_site_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["CHOOSE_LANDING"], $arFields); 
}

if($arProperty["BANNERS_LEFT"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_banners_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BANNERS_LEFT"], $arFields); 
}

if($arProperty["MODAL_WINDOWS"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_modals_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["MODAL_WINDOWS"], $arFields); 
}

if($arProperty["FORMS"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_forms_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["FORMS"], $arFields); 
}

if($arProperty["BRAND"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_brand_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BRAND"], $arFields); 
}

if($arProperty["ADVANTAGES"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_advantages_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["ADVANTAGES"], $arFields); 
}

if($arProperty["SIMILAR"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_catalog_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["SIMILAR"], $arFields); 
}

if($arProperty["STUFF"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_employee_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["STUFF"], $arFields); 
}

if($arProperty["BLOGS"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_blog_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BLOGS"], $arFields); 
}

if($arProperty["NEWS"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_news_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["NEWS"], $arFields); 
}

if($arProperty["SPECIALS"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_action_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["SPECIALS"], $arFields); 
}

if($arProperty["FAQ_FORM"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_forms_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["FAQ_FORM"], $arFields); 
}

if($arProperty["FAQ_ELEMENTS"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_faq_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["FAQ_ELEMENTS"], $arFields); 
}

if($arProperty["ACCESSORY"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_catalog_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["ACCESSORY"], $arFields); 
}

if($arProperty["BTN_FORM"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_forms_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BTN_FORM"], $arFields); 
}

if($arProperty["BTN_POPUP"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_modals_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BTN_POPUP"], $arFields); 
}

if($arProperty["BTN_LAND"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_site_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BTN_LAND"], $arFields); 
}

if($arProperty["BTN_PRODUCT_ID"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_catalog_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BTN_PRODUCT_ID"], $arFields); 
}

if($arProperty["BTN_OFFER_ID"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_catalog_offers_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BTN_OFFER_ID"], $arFields); 
}

if($arProperty["REGION"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_regions_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["REGION"], $arFields); 
}


//lands
$arUF = Array();
$rsData = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID"=>"IBLOCK_".$arIBlock["concept_phoenix_site_".WIZARD_SITE_ID]."_SECTION"));
while($arRes = $rsData->Fetch())
    $arUF[$arRes["FIELD_NAME"]] = $arRes["ID"];


$arProperty = array();
$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $arIBlock["concept_phoenix_site_".WIZARD_SITE_ID]));

while($arProp = $dbProperty->Fetch())
    $arProperty[$arProp["CODE"]] = $arProp["ID"];



if($arUF["UF_PHX_BANNERS"] > 0)
{
    $oUserTypeEntity = new CUserTypeEntity();
    $oUserTypeEntity->Update($arUF["UF_PHX_BANNERS"], 
        array(
            "SETTINGS" => Array
            (
                "DISPLAY" => "CHECKBOX",
                "IBLOCK_ID" => $arIBlock["concept_phoenix_banners_".WIZARD_SITE_ID],
            )
        ) 
    );
}

if($arProperty["BUTTON_FORM"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_forms_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BUTTON_FORM"], $arFields); 
}

if($arProperty["BUTTON_MODAL"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_modals_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BUTTON_MODAL"], $arFields); 
}

if($arProperty["BUTTON_FORM_2"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_forms_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BUTTON_FORM_2"], $arFields); 
}

if($arProperty["BUTTON_MODAL_2"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_modals_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BUTTON_MODAL_2"], $arFields); 
}

if($arProperty["BUTTON_CATALOG_ID"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_catalog_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BUTTON_CATALOG_ID"], $arFields); 
}

if($arProperty["BUTTON_2_CATALOG_ID"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_catalog_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BUTTON_2_CATALOG_ID"], $arFields); 
}

if($arProperty["BUTTON_OFFER_ID"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_catalog_offers_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BUTTON_OFFER_ID"], $arFields); 
}

if($arProperty["BUTTON_2_OFFER_ID"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_catalog_offers_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BUTTON_2_OFFER_ID"], $arFields); 
}

if($arProperty["FB_LB_FORM"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_forms_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["FB_LB_FORM"], $arFields); 
}

if($arProperty["FB_LB_MODAL"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_modals_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["FB_LB_MODAL"], $arFields); 
}

if($arProperty["FB_RB_FORM"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_forms_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["FB_RB_FORM"], $arFields); 
}

if($arProperty["FB_RB_MODAL"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_modals_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["FB_RB_MODAL"], $arFields); 
}

if($arProperty["FB_LB_CATALOG_ID"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_catalog_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["FB_LB_CATALOG_ID"], $arFields); 
}

if($arProperty["FB_RB_CATALOG_ID"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_catalog_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["FB_RB_CATALOG_ID"], $arFields); 
}

if($arProperty["FB_RB_OFFER_ID"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_catalog_offers_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["FB_RB_OFFER_ID"], $arFields); 
}

if($arProperty["FB_LB_OFFER_ID"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_catalog_offers_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["FB_LB_OFFER_ID"], $arFields); 
}

if($arProperty["CATALOG"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_catalog_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["CATALOG"], $arFields); 
}

if($arProperty["TARIFF_BUTTON_FORM"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_forms_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["TARIFF_BUTTON_FORM"], $arFields); 
}

if($arProperty["TARIFF_MODAL"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_modals_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["TARIFF_MODAL"], $arFields); 
}

if($arProperty["NEWS_ELEMENTS_BLOG"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_blog_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["NEWS_ELEMENTS_BLOG"], $arFields); 
}

if($arProperty["NEWS_ELEMENTS_NEWS"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_news_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["NEWS_ELEMENTS_NEWS"], $arFields); 
}

if($arProperty["NEWS_ELEMENTS_ACTION"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_action_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["NEWS_ELEMENTS_ACTION"], $arFields); 
}

if($arProperty["FAQ_ELEMENTS"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_faq_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["FAQ_ELEMENTS"], $arFields); 
}

if($arProperty["MAP_ADVS"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_advantages_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["MAP_ADVS"], $arFields); 
}

if($arProperty["BLINK_BUTTON_FORM"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_forms_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BLINK_BUTTON_FORM"], $arFields); 
}

if($arProperty["BLINK_BUTTON_MODAL"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_modals_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BLINK_BUTTON_MODAL"], $arFields); 
}

if($arProperty["BLINK_CATALOG_ID"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_catalog_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BLINK_CATALOG_ID"], $arFields); 
}

if($arProperty["BLINK_OFFER_ID"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_catalog_offers_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["BLINK_OFFER_ID"], $arFields); 
}

if($arProperty["EMPL"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_employee_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["EMPL"], $arFields); 
}

if($arProperty["CATALOG_LABELS_SECTIONS"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_catalog_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["CATALOG_LABELS_SECTIONS"], $arFields); 
}

if($arProperty["CATALOG_BIG_ITEMS"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_catalog_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["CATALOG_BIG_ITEMS"], $arFields); 
}

if($arProperty["RETRANSLATOR"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_site_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["RETRANSLATOR"], $arFields); 
}

if($arProperty["REGION"] > 0)
{
    $arFields = Array("LINK_IBLOCK_ID" => $arIBlock["concept_phoenix_regions_".WIZARD_SITE_ID]);

    $ibp = new CIBlockProperty;
    $ibp->Update($arProperty["REGION"], $arFields); 
}
?>