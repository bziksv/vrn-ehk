<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */


global $APPLICATION;

$arDefaultUrlTemplates404 = array(
	"main" => "",
	"page" => "#SECTION_CODE_PATH#/",
);

$arDefaultVariableAliases404 = array();

$arDefaultVariableAliases = array();

$arComponentVariables = array(
	"SECTION_ID",
	"SECTION_CODE_PATH"
);

if($arParams["SEF_MODE"] == "Y")
{
	$arVariables = array();

	$arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $arParams["SEF_URL_TEMPLATES"]);
	$arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases404, $arParams["VARIABLE_ALIASES"]);

	$engine = new CComponentEngine($this);
	if(CModule::IncludeModule('iblock'))
	{
		$engine->addGreedyPart("#SECTION_CODE_PATH#");
		$engine->setResolveCallback(array("CIBlockFindTools", "resolveComponentEngine"));
	}
	$componentPage = $engine->guessComponentPath(
		$arParams["SEF_FOLDER"],
		$arUrlTemplates,
		$arVariables
	);

    if($arParams["SEF_FOLDER"] == $APPLICATION->GetCurPage(false))
        $componentPage = "main";

	$b404 = false;

	if(!(isset($arVariables["SECTION_CODE"]) || isset($arVariables["SECTION_ID"])) && $arParams["SEF_FOLDER"] != $APPLICATION->GetCurPage(false))
		$b404 = true;
    
	if(!$componentPage)
	{
		$componentPage = "main";
		$b404 = true;
	}
    
    if($componentPage == "page")
	{      
		if(isset($arVariables["SECTION_CODE"]) || isset($arVariables["SECTION_ID"]))
        {
	        $arFilter = Array('IBLOCK_ID'=>$arParams["IBLOCK_ID"], 'ACTIVE'=>"Y", "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"]);
	        
			if(isset($arVariables["SECTION_CODE"]))
	            $arFilter["=CODE"] = $arVariables["SECTION_CODE"];
	            
	        if(isset($arVariables["SECTION_ID"]))
	            $arFilter["ID"] = $arVariables["SECTION_ID"];
	        
	        $db_list = CIBlockSection::GetList(Array(), $arFilter, false);
	        
	        if($db_list->SelectedRowsCount() <= 0)
	            $b404 = true;
	       	else
	       	{
	       		$landing = $db_list->GetNext();

	       		if($landing["CODE"] != $arVariables["SECTION_CODE"])
        			$b404 = true;
	       	}

	    }
        else
        	$b404 = true;
	}


	if($b404)
	{
		$folder404 = str_replace("\\", "/", $arParams["SEF_FOLDER"]);
		if ($folder404 != "/")
			$folder404 = "/".trim($folder404, "/ \t\n\r\0\x0B")."/";
		if (substr($folder404, -1) == "/")
			$folder404 .= "index.php";

		if ($folder404 != $APPLICATION->GetCurPage(true))
		{
			\Bitrix\Iblock\Component\Tools::process404(
				""
				,($arParams["SET_STATUS_404"] === "Y")
				,($arParams["SET_STATUS_404"] === "Y")
				,($arParams["SHOW_404"] === "Y")
				,$arParams["FILE_404"]
			);
		}
	}

	CComponentEngine::InitComponentVariables($componentPage, $arComponentVariables, $arVariableAliases, $arVariables);

	$arResult = array(
		"FOLDER" => $arParams["SEF_FOLDER"],
		"URL_TEMPLATES" => $arUrlTemplates,
		"VARIABLES" => $arVariables,
		"ALIASES" => $arVariableAliases,
		"LANDING" => $landing
	);
}
else
{
	$arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases, $arParams["VARIABLE_ALIASES"]);
	CComponentEngine::InitComponentVariables(false, $arComponentVariables, $arVariableAliases, $arVariables);

	$componentPage = "";
    
	if(isset($arVariables["SECTION_ID"]) && intval($arVariables["SECTION_ID"]) > 0)
	{
	   $componentPage = "page";
	}
	elseif(isset($arVariables["SECTION_CODE"]) && strlen($arVariables["SECTION_CODE"]) > 0)
	{
		$componentPage = "page";
	}
	elseif(isset($arVariables["SECTION_CODE_PATH"]) && strlen($arVariables["SECTION_CODE_PATH"]) > 0)
	{
	   $componentPage = "page";
	}
	else
		$componentPage = "main";

	$arResult = array(
		"FOLDER" => "",
		"URL_TEMPLATES" => Array(
			"main" => htmlspecialcharsbx($APPLICATION->GetCurPage()),
			"page" => htmlspecialcharsbx($APPLICATION->GetCurPage()."?".$arVariableAliases["SECTION_CODE"]."=#SECTION_CODE#"),
		),
		"VARIABLES" => $arVariables,
		"ALIASES" => $arVariableAliases
	);
}

$this->IncludeComponentTemplate($componentPage);
?>