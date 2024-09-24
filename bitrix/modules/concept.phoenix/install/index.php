<?
global $MESS;
$strPath2Lang = str_replace("\\", "/", __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install/index.php"));
include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));

use \Bitrix\Main\Config\Option as Option;


Class concept_phoenix extends CModule
{


	var $MODULE_ID = "concept.phoenix";
	
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $MODULE_GROUP_RIGHTS = "Y";

    function concept_phoenix()
    {

        $arModuleVersion = array();

		$path = str_replace("\\", "/", __FILE__);

		$path = substr($path, 0, strlen($path) - strlen("/index.php"));

		include($path."/version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

		$this->PARTNER_NAME = GetMessage("PHOENIX_INSTALL_PARTNER_NAME");
		$this->PARTNER_URI = "http://phoenix360.ru/";

		$this->MODULE_NAME = GetMessage("PHOENIX_INSTALL_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("PHOENIX_INSTALL_DESCRIPTION");

		return true;

    }


	function InstallDB($install_wizard = true)
	{
		global $DB, $DBType, $APPLICATION;
		RegisterModule($this->MODULE_ID);

		Option::set("concept.phoenix", "phoenix_hide_adv", "Y");

		return true;
	}

	function UnInstallDB($arParams = Array())
	{
		global $DB, $DBType, $APPLICATION; 

		CModule::IncludeModule("iblock");

		$arSites = Array();

		$rsSites = CSite::GetList($by="sort", $order="desc", Array());

		while ($arSite = $rsSites->GetNext())
		  	$arSites[] = $arSite["LID"];


		/**/
		UnRegisterModule($this->MODULE_ID);



		$arData = array(
    	   	'PHOENIX_USER_INFO',
			'PHOENIX_FOR_USER',
			'PHOENIX_ONECLICK_SEND',
			'PHOENIX_SEND_REG_SUCCESS',
			'PHOENIX_USER_PASS_REQUEST',
			'PHOENIX_CHANGE_PASSWORD_SUCCESS',
			'PHOENIX_NEW_REVIEW_PRODUCT',
			'PHOENIX_INFO_ORDER_ITEMS_FOR_USER_AFTER_PAYED',
			'PHOENIX_NEW_COMMENTS'
        );
        
        if(!empty($arSites))
        {
        	foreach($arSites as $key => $site_id) 
        	{
        		foreach($arData as $EVENT_TYPE) 
		        {
		        	$EVENT_TYPE = $EVENT_TYPE."_".$site_id;

		            $arFilter = Array(
		                "TYPE_ID" => array($EVENT_TYPE),
		            );
		                
		            $rsMess = CEventMessage::GetList($by="site_id", $order="asc", $arFilter);
		            
		            while($arMess = $rsMess->Fetch())
		            {
		                $em = new CEventMessage;
		    
		                $DB->StartTransaction();
		                
		                if(!$em->Delete(intval($arMess["ID"])))
		                    $DB->Rollback();
		                else 
		                    $DB->Commit();
		            }
		            
		            $et = new CEventType;
		            $et->Delete($EVENT_TYPE);
		        
		        }

		        $strSql = "SHOW TABLES LIKE 'phoenix_reviews_".$site_id."'";
	    		$res = $DB->Query($strSql, false, $err_mess.__LINE__);
	    		
	    		if($res->SelectedRowsCount() > 0)
	            {
	                $query = "DROP TABLE phoenix_reviews_".$site_id;
	                $DB->Query($query, false, $err_mess.__LINE__);
	            }


	            $strSql = "SHOW TABLES LIKE 'phoenix_reviews_info_".$site_id."'";
	    		$res = $DB->Query($strSql, false, $err_mess.__LINE__);
	    		
	    		if($res->SelectedRowsCount() > 0)
	            {
	                $query = "DROP TABLE phoenix_reviews_info_".$site_id;
	                $DB->Query($query, false, $err_mess.__LINE__);
	            }

				$strSql = "SHOW TABLES LIKE 'phoenix_comments_".$site_id."'";
	    		$res = $DB->Query($strSql, false, $err_mess.__LINE__);
	    		
	    		if($res->SelectedRowsCount() > 0)
	            {
	                $query = "DROP TABLE phoenix_comments_".$site_id;
	                $DB->Query($query, false, $err_mess.__LINE__);
	            }
		        
		    }
		    
        }
        
        if(CModule::IncludeModule("highloadblock"))
        {
			
			$dbHblock = Bitrix\Highloadblock\HighloadBlockTable::getList(array("filter" => array("NAME" => "ConceptPhoenixColorReference")));

			if($HLBlock = $dbHblock->Fetch())
				Bitrix\Highloadblock\HighloadBlockTable::delete($HLBlock["ID"]);
        }

        $strSql = "SHOW TABLES LIKE 'phoenix_seo'";
		$res = $DB->Query($strSql, false, $err_mess.__LINE__);
		
		if($res->SelectedRowsCount() > 0)
        {
            $query = "DROP TABLE phoenix_seo";
            $DB->Query($query, false, $err_mess.__LINE__);
        }
        

        
		\Bitrix\Main\Config\Option::delete($this->MODULE_ID);
		
		return true;
	}

	function InstallEvents()
	{

		global $DB, $DBType, $APPLICATION;  

		$eventManager = \Bitrix\Main\EventManager::getInstance();
		
		$eventManager->registerEventHandler("iblock", "OnIBlockPropertyBuildList", $this->MODULE_ID, "CPhoenixUserTypeSectionLink", "PhoenixGetUserTypeDescription");

		$eventManager->registerEventHandler("main", "OnEpilog", $this->MODULE_ID, "CPhoenixMainHandlers", "PhoenixOnEpilogHandler");
		$eventManager->registerEventHandler('main', 'OnEpilog',$this->MODULE_ID, 'CPhoenixSelectTab', 'PhoenixSelectTab');  

		$eventManager->registerEventHandler("catalog", "OnPriceAdd", $this->MODULE_ID, "CPhoenixShop", "GetSortPrice2");
		$eventManager->registerEventHandler("catalog", "OnPriceUpdate", $this->MODULE_ID, "CPhoenixShop", "GetSortPrice2");
		$eventManager->registerEventHandler("iblock", "OnAfterIBlockElementUpdate", $this->MODULE_ID, "CPhoenixShop", "GetSortPrice1");
		$eventManager->registerEventHandler("iblock", "OnAfterIBlockElementAdd", $this->MODULE_ID, "CPhoenixShop", "GetSortPrice1");

		$eventManager->registerEventHandler("catalog", "OnGetOptimalPrice", $this->MODULE_ID, "CPhoenixShop", "getOptimalPrice");


		$eventManager->registerEventHandler("iblock", "OnBeforeIBlockElementUpdate", $this->MODULE_ID, "CPhoenixShop", "setPreviewPicture");
		$eventManager->registerEventHandler("iblock", "OnBeforeIBlockElementAdd", $this->MODULE_ID, "CPhoenixShop", "setPreviewPicture");

		$eventManager->registerEventHandler("sale", "OnOrderNewSendEmail", $this->MODULE_ID, "CPhoenixSendOrderTable", "OnOrderNewSendEmailHandler");
		$eventManager->registerEventHandler('search', 'BeforeIndex',$this->MODULE_ID, 'CPhoenixSearch', 'onBeforeIndexHandler');  

		$eventManager->registerEventHandler("main", "OnEndBufferContent", $this->MODULE_ID, 'CPhoenixSpeed', "ChangePhoenixContent"); 
		$eventManager->registerEventHandler("main", "OnEndBufferContent", $this->MODULE_ID, 'CPhoenixSpeed', "CompressPhoenixCssJS");
		$eventManager->registerEventHandler("main", "OnEndBufferContent", $this->MODULE_ID, 'CPhoenixSpeed', "deleteKernelCss");
		$eventManager->registerEventHandler("main", "OnEndBufferContent", $this->MODULE_ID, 'CPhoenixSpeed', "includeCssInline");


		$eventManager->registerEventHandler("iblock", "OnIBlockPropertyBuildList", $this->MODULE_ID, 'CPhoenixUsersGroupsIblockProperty', "GetUserTypeDescription");
		$eventManager->registerEventHandler('iblock', 'OnIBlockPropertyBuildList', $this->MODULE_ID, 'CPhoenixIblockPropSection', 'GetUserTypeDescription');

		$eventManager->registerEventHandler("main", "OnUserTypeBuildList", $this->MODULE_ID, 'CPhoenixUsersGroupsUserType', "GetUserTypeDescription");
		$eventManager->registerEventHandler("main", "OnBeforeUserAdd", $this->MODULE_ID, "CPhoenix", "OnBeforeUserAddHandler");
		$eventManager->registerEventHandler("main", "OnBeforeUserAdd", $this->MODULE_ID, "CPhoenixMainHandlers", "OnBeforeUserAddHandler");
		$eventManager->registerEventHandler("main", "OnAfterUserAdd", $this->MODULE_ID, "CPhoenixMainHandlers", "OnAfterUserAddHandler");

		$eventManager->registerEventHandler("iblock", "OnIBlockPropertyBuildList", $this->MODULE_ID, 'Concept\Phoenix\Property\ListPrices', "OnIBlockPropertyBuildList");
		$eventManager->registerEventHandler("iblock", "OnIBlockPropertyBuildList", $this->MODULE_ID, 'Concept\Phoenix\Property\ListStores', "OnIBlockPropertyBuildList");
		$eventManager->registerEventHandler("iblock", "OnIBlockPropertyBuildList", $this->MODULE_ID, 'Concept\Phoenix\Property\ListLocations', "OnIBlockPropertyBuildList");
		$eventManager->registerEventHandler("iblock", "OnIBlockPropertyBuildList", $this->MODULE_ID, 'Concept\Phoenix\Property\ListLocationsSubregion', "OnIBlockPropertyBuildList");

		$eventManager->registerEventHandler("sale", "OnSaleComponentOrderProperties", $this->MODULE_ID, "CPhoenixRegionality", "OnSaleComponentOrderPropertiesHandler");
		$eventManager->registerEventHandler("iblock", "OnAfterIBlockElementUpdate", $this->MODULE_ID, "CPhoenixRegionality", "UpdateRegionSeoFiles");

		$eventManager->registerEventHandler("sale", "OnSalePaymentEntitySaved", $this->MODULE_ID, "CPhoenixSendMail", "OnSalePaymentEntitySaved");

		$eventManager->registerEventHandler("iblock", "OnAfterIBlockElementUpdate", $this->MODULE_ID, "CPhoenixRegionality", "UpdateRegionSeoFiles");
		$eventManager->registerEventHandler("main", "OnAfterEpilog", $this->MODULE_ID, "CPhoenixTemplate", "afterEpilogClearComposite");




		return true;
	}

	function UnInstallEvents($arParams = array())
	{
		global $DB, $DBType, $APPLICATION; 

		$eventManager = \Bitrix\Main\EventManager::getInstance();
		$eventManager->unRegisterEventHandler("iblock", "OnIBlockPropertyBuildList", $this->MODULE_ID, "CPhoenixUserTypeSectionLink", "PhoenixGetUserTypeDescription");
		$eventManager->unRegisterEventHandler("main", "OnEpilog", $this->MODULE_ID, "CPhoenixMainHandlers", "PhoenixOnEpilogHandler");
		$eventManager->unRegisterEventHandler('main', 'OnEpilog',$this->MODULE_ID, 'CPhoenixSelectTab', 'PhoenixSelectTab');  

		$eventManager->unRegisterEventHandler("catalog", "OnPriceAdd", $this->MODULE_ID, "CPhoenixShop", "GetSortPrice2");
		$eventManager->unRegisterEventHandler("catalog", "OnPriceUpdate", $this->MODULE_ID, "CPhoenixShop", "GetSortPrice2");
		$eventManager->unRegisterEventHandler("iblock", "OnAfterIBlockElementUpdate", $this->MODULE_ID, "CPhoenixShop", "GetSortPrice1");
		$eventManager->unRegisterEventHandler("iblock", "OnAfterIBlockElementAdd", $this->MODULE_ID, "CPhoenixShop", "GetSortPrice1");

		$eventManager->unRegisterEventHandler("catalog", "OnGetOptimalPrice", $this->MODULE_ID, "CPhoenixShop", "getOptimalPrice");

		$eventManager->unRegisterEventHandler("iblock", "OnBeforeIBlockElementUpdate", $this->MODULE_ID, "CPhoenixShop", "setPreviewPicture");
		$eventManager->unRegisterEventHandler("iblock", "OnBeforeIBlockElementAdd", $this->MODULE_ID, "CPhoenixShop", "setPreviewPicture");

		$eventManager->unRegisterEventHandler("sale", "OnOrderNewSendEmail", $this->MODULE_ID, "CPhoenixSendOrderTable", "OnOrderNewSendEmailHandler");
		$eventManager->unRegisterEventHandler('search', 'BeforeIndex',$this->MODULE_ID, 'CPhoenixSearch', 'onBeforeIndexHandler');  

		$eventManager->unRegisterEventHandler("main", "OnEndBufferContent", $this->MODULE_ID, 'CPhoenixSpeed', "ChangePhoenixContent"); 
		$eventManager->unRegisterEventHandler("main", "OnEndBufferContent", $this->MODULE_ID, 'CPhoenixSpeed', "CompressPhoenixCssJS");
		$eventManager->unRegisterEventHandler("main", "OnEndBufferContent", $this->MODULE_ID, 'CPhoenixSpeed', "deleteKernelCss");
		$eventManager->unRegisterEventHandler("main", "OnEndBufferContent", $this->MODULE_ID, 'CPhoenixSpeed', "includeCssInline");

		$eventManager->unRegisterEventHandler("iblock", "OnIBlockPropertyBuildList", $this->MODULE_ID, 'CPhoenixUsersGroupsIblockProperty', "GetUserTypeDescription");
		$eventManager->unRegisterEventHandler('iblock', 'OnIBlockPropertyBuildList', $this->MODULE_ID, 'CPhoenixIblockPropSection', 'GetUserTypeDescription');

		$eventManager->unRegisterEventHandler("main", "OnUserTypeBuildList", $this->MODULE_ID, 'CPhoenixUsersGroupsUserType', "GetUserTypeDescription");
		$eventManager->unRegisterEventHandler("main", "OnBeforeUserAdd", $this->MODULE_ID, "CPhoenix", "OnBeforeUserAddHandler");
		$eventManager->unRegisterEventHandler("main", "OnBeforeUserAdd", $this->MODULE_ID, "CPhoenixMainHandlers", "OnBeforeUserAddHandler");
		$eventManager->unRegisterEventHandler("main", "OnAfterUserAdd", $this->MODULE_ID, "CPhoenixMainHandlers", "OnAfterUserAddHandler");

		$eventManager->unRegisterEventHandler("iblock", "OnIBlockPropertyBuildList", $this->MODULE_ID, 'Concept\Phoenix\Property\ListPrices', "OnIBlockPropertyBuildList");
		$eventManager->unRegisterEventHandler("iblock", "OnIBlockPropertyBuildList", $this->MODULE_ID, 'Concept\Phoenix\Property\ListStores', "OnIBlockPropertyBuildList");
		$eventManager->unRegisterEventHandler("iblock", "OnIBlockPropertyBuildList", $this->MODULE_ID, 'Concept\Phoenix\Property\ListLocations', "OnIBlockPropertyBuildList");
		$eventManager->unRegisterEventHandler("iblock", "OnIBlockPropertyBuildList", $this->MODULE_ID, 'Concept\Phoenix\Property\ListLocationsSubregion', "OnIBlockPropertyBuildList");

		$eventManager->unRegisterEventHandler("sale", "OnSaleComponentOrderProperties", $this->MODULE_ID, "CPhoenixRegionality", "OnSaleComponentOrderPropertiesHandler");
		$eventManager->unRegisterEventHandler("iblock", "OnAfterIBlockElementUpdate", $this->MODULE_ID, "CPhoenixRegionality", "UpdateRegionSeoFiles");
		$eventManager->unRegisterEventHandler("sale", "OnSalePaymentEntitySaved", $this->MODULE_ID, "CPhoenixSendMail", "OnSalePaymentEntitySaved");

		$eventManager->unRegisterEventHandler("iblock", "OnAfterIBlockElementUpdate", $this->MODULE_ID, "CPhoenixRegionality", "UpdateRegionSeoFiles");
		$eventManager->unRegisterEventHandler("main", "OnAfterEpilog", $this->MODULE_ID, "CPhoenixTemplate", "afterEpilogClearComposite");



		return true;
	}
    
    function InstallIBlocks()
	{
		return true;
	}

	function UnInstallIBlocks($arParams = array())
	{

		set_time_limit(0);
		
        global $DB, $DBType, $APPLICATION; 

		CModule::IncludeModule("iblock");
		CModule::IncludeModule("catalog");

		$arSites = Array();

		$rsSites = CSite::GetList($by="sort", $order="desc", Array());

		while ($arSite = $rsSites->GetNext())
		  	$arSites[] = $arSite["LID"];



        if(!empty($arSites))
        {
        	foreach($arSites as $key => $site_id) 
        	{
        		$rsIBlock = CIBlock::GetList(array(), array("CODE" => "concept_phoenix_catalog_offers_".$site_id, "TYPE" => "concept_phoenix_".$site_id));
		        if($arIBlock = $rsIBlock->Fetch())
		        	CCatalog::Delete($arIBlock["ID"]);

		        $rsIBlock = CIBlock::GetList(array(), array("CODE" => "concept_phoenix_catalog_".$site_id, "TYPE" => "concept_phoenix_".$site_id));
		        if($arIBlock = $rsIBlock->Fetch())
		        	CCatalog::Delete($arIBlock["ID"]);
        	}

        	foreach($arSites as $key => $site_id) 
        	{

		        $DB->StartTransaction();
        
		        if(!CIBlockType::Delete('concept_phoenix_'.$site_id))
		            $DB->Commit();
		        else
		        	$DB->Rollback();


        	}

        }

		return true;
	}

	function InstallFiles()
	{
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/wizards", $_SERVER["DOCUMENT_ROOT"]."/bitrix/wizards", true, true);
        
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/css", $_SERVER["DOCUMENT_ROOT"]."/bitrix/css", true, true);
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/js", $_SERVER["DOCUMENT_ROOT"]."/bitrix/js", true, true);
        
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/images", $_SERVER["DOCUMENT_ROOT"]."/bitrix/images", true, true);
        
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/admin_files", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin", true, true);
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/tools", $_SERVER["DOCUMENT_ROOT"]."/bitrix/tools", true, true, false);

		return true;
	}
    
   	function UnInstallFiles($arParams = array())
	{   
		global $DB, $DBType, $APPLICATION;

		CModule::IncludeModule("iblock");

		$arSites = Array();

		$rsSites = CSite::GetList($by="sort", $order="desc", Array());

		while ($arSite = $rsSites->GetNext())
		  	$arSites[] = $arSite["LID"];

		DeleteDirFilesEx('/bitrix/css/concept.phoenix/');
        DeleteDirFilesEx('/bitrix/js/concept.phoenix/');
        DeleteDirFilesEx('/bitrix/tools/concept.phoenix/');
        
        DeleteDirFilesEx('/bitrix/wizards/concept/phoenix/');
        
        DeleteDirFilesEx('/bitrix/admin/concept_phoenix.php');
        DeleteDirFilesEx('/bitrix/admin/concept_phoenix_help.php');

        DeleteDirFilesEx('/bitrix/admin/concept_phoenix_admin_help.php');
        DeleteDirFilesEx('/bitrix/admin/concept_phoenix_admin_index.php');

        DeleteDirFilesEx('/bitrix/admin/concept_phoenix_admin_reviews_edit.php');
        DeleteDirFilesEx('/bitrix/admin/concept_phoenix_admin_reviews_list.php');

        DeleteDirFilesEx('/upload/phoenix/');

        DeleteDirFilesEx('/bitrix/components/concept/phoenix.basket/');
        DeleteDirFilesEx('/bitrix/components/concept/phoenix.catalog.smart.filter/');
        DeleteDirFilesEx('/bitrix/components/concept/phoenix.compare.page/');
        DeleteDirFilesEx('/bitrix/components/concept/phoenix.form/');
        DeleteDirFilesEx('/bitrix/components/concept/phoenix.menu/');
        DeleteDirFilesEx('/bitrix/components/concept/phoenix.news-blogs-actions-list/');
        DeleteDirFilesEx('/bitrix/components/concept/phoenix.news-list/');
        DeleteDirFilesEx('/bitrix/components/concept/phoenix.next-last-element/');
        DeleteDirFilesEx('/bitrix/components/concept/phoenix.one.page/');
        DeleteDirFilesEx('/bitrix/components/concept/phoenix.pages/');
        DeleteDirFilesEx('/bitrix/components/concept/phoenix.pages.list/');
        DeleteDirFilesEx('/bitrix/components/concept/phoenix.personal/');
        DeleteDirFilesEx('/bitrix/components/concept/phoenix.personal.menu/');
        DeleteDirFilesEx('/bitrix/components/concept/phoenix.search/');
        DeleteDirFilesEx('/bitrix/components/concept/phoenix.search.line/');
        DeleteDirFilesEx('/bitrix/components/concept/phoenix.search.result/');
        DeleteDirFilesEx('/bitrix/components/concept/phoenix.seo/');
        DeleteDirFilesEx('/bitrix/components/concept/phoenix.reviews.list/');
        DeleteDirFilesEx('/bitrix/components/concept/phoenix.reviews.info/');



        if(!empty($arSites))
        {
        	foreach($arSites as $key => $site_id) 
        		DeleteDirFilesEx('/bitrix/templates/concept_phoenix_'.$site_id.'/');
        }


		return true;
	}
    

	function InstallPublic()
	{
	   return true;
	}
    
    function UnInstallPublic()
	{
	   return true;
	}


	
	
	function DoInstall()
	{
		global $APPLICATION, $step;


		if(CModule::IncludeModule("iblock") && CModule::IncludeModule("catalog") && CModule::IncludeModule("sale") && CModule::IncludeModule("currency") && CModule::IncludeModule("search"))
		{
			$this->InstallDB(false);
			$this->InstallFiles();	
			$this->InstallEvents();
			$this->InstallPublic();
	        $this->InstallIBlocks();

			$APPLICATION->IncludeAdminFile(GetMessage("PHOENIX_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/step1.php");
			
		}
		else
			$APPLICATION->IncludeAdminFile(GetMessage("PHOENIX_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/step1_errors.php");
    
        
	
	}

	function DoUninstall()
	{
		global $APPLICATION, $step;

		$step = intval($step);

		if($step < 1)
			$step = 1;

		if($step == 1)
		{
			$APPLICATION->IncludeAdminFile(GetMessage("PHOENIX_UNINSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/unstep1.php");
		}

		if($step == 2)
		{

			if($_REQUEST['uninstall'] == "Y")
			{
				$this->UnInstallDB();
				$this->UnInstallFiles();
				$this->UnInstallEvents();
		        $this->UnInstallPublic();
		        $this->UnInstallIBlocks();

		        $APPLICATION->IncludeAdminFile(GetMessage("PHOENIX_UNINSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/unstep2.php");
			}
			

	        
		}

		
		
		
		
	}
}
?>
