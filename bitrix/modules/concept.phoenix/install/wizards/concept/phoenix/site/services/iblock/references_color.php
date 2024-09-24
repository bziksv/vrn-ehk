<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)	die();?>

<?

set_time_limit(0);


if (!CModule::IncludeModule("highloadblock"))
	return;

$languageID = "ru";
WizardServices::IncludeServiceLang("lang_references_color.php", $languageID);


use Bitrix\Highloadblock as HL;
global $USER_FIELD_MANAGER;


$dbHblock = HL\HighloadBlockTable::getList(array("filter" => array("NAME" => "ConceptPhoenixColorReference")));

if(!$dbHblock->Fetch())
{
	$data = array('NAME' => 'ConceptPhoenixColorReference', 'TABLE_NAME' => 'concept_phoenix_color_reference');
	$result = HL\HighloadBlockTable::add($data);
	$HL_ID = $result->getId();
	
	if($HL_ID)
	{
		$hldata = HL\HighloadBlockTable::getById($HL_ID)->fetch();
		$hlentity = HL\HighloadBlockTable::compileEntity($hldata);

		$entity_data_class = $hlentity->getDataClass();

		//adding user fields
		$arUserFields = array(
			array(
				'ENTITY_ID' => 'HLBLOCK_'.$HL_ID,
				'FIELD_NAME' => 'UF_NAME',
				'USER_TYPE_ID' => 'string',
				'XML_ID' => 'UF_NAME',
				'SORT' => '100',
				'MULTIPLE' => 'N',
				'MANDATORY' => 'Y',
				'SHOW_FILTER' => 'N',
				'SHOW_IN_LIST' => 'Y',
				'EDIT_IN_LIST' => 'Y',
				'IS_SEARCHABLE' => 'N',
			),
			array(
				'ENTITY_ID' => 'HLBLOCK_'.$HL_ID,
				'FIELD_NAME' => 'UF_XML_ID',
				'USER_TYPE_ID' => 'string',
				'XML_ID' => 'UF_XML_ID',
				'SORT' => '100',
				'MULTIPLE' => 'N',
				'MANDATORY' => 'Y',
				'SHOW_FILTER' => 'N',
				'SHOW_IN_LIST' => 'Y',
				'EDIT_IN_LIST' => 'Y',
				'IS_SEARCHABLE' => 'N',
			),
			array(
				'ENTITY_ID' => 'HLBLOCK_'.$HL_ID,
				'FIELD_NAME' => 'UF_SORT',
				'USER_TYPE_ID' => 'double',
				'XML_ID' => 'UF_SORT',
				'SORT' => '100',
				'MULTIPLE' => 'N',
				'MANDATORY' => 'N',
				'SHOW_FILTER' => 'N',
				'SHOW_IN_LIST' => 'Y',
				'EDIT_IN_LIST' => 'Y',
				'IS_SEARCHABLE' => 'N',
			),
			array(
				'ENTITY_ID' => 'HLBLOCK_'.$HL_ID,
				'FIELD_NAME' => 'UF_COLOR',
				'USER_TYPE_ID' => 'string',
				'XML_ID' => 'UF_COLOR',
				'SORT' => '100',
				'MULTIPLE' => 'N',
				'MANDATORY' => 'N',
				'SHOW_FILTER' => 'N',
				'SHOW_IN_LIST' => 'Y',
				'EDIT_IN_LIST' => 'Y',
				'IS_SEARCHABLE' => 'N',
			),			
			array(
				'ENTITY_ID' => 'HLBLOCK_'.$HL_ID,
				'FIELD_NAME' => 'UF_PICT',
				'USER_TYPE_ID' => 'file',
				'XML_ID' => 'UF_PICT',
				'SORT' => '100',
				'MULTIPLE' => 'N',
				'MANDATORY' => 'N',
				'SHOW_FILTER' => 'N',
				'SHOW_IN_LIST' => 'Y',
				'EDIT_IN_LIST' => 'Y',
				'IS_SEARCHABLE' => 'N',
			),
			array(
				'ENTITY_ID' => 'HLBLOCK_'.$HL_ID,
				'FIELD_NAME' => 'UF_PICT_SEC',
				'USER_TYPE_ID' => 'file',
				'XML_ID' => 'UF_PICT_SEC',
				'SORT' => '100',
				'MULTIPLE' => 'N',
				'MANDATORY' => 'N',
				'SHOW_FILTER' => 'N',
				'SHOW_IN_LIST' => 'Y',
				'EDIT_IN_LIST' => 'Y',
				'IS_SEARCHABLE' => 'N',
			),
		);
		

		
		$obUserField  = new CUserTypeEntity;

		foreach($arUserFields as $arFields)
		{
			$dbRes = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID" => $arFields["ENTITY_ID"], "FIELD_NAME" => $arFields["FIELD_NAME"]));
			
			if($dbRes->Fetch())
				continue;

			$arLabelNames = Array();	
			$arLabelNames[$languageID] = GetMessage($arFields["FIELD_NAME"]);

			$arFields["EDIT_FORM_LABEL"] = $arLabelNames;
			$arFields["LIST_COLUMN_LABEL"] = $arLabelNames;
			$arFields["LIST_FILTER_LABEL"] = $arLabelNames;

			$ID_USER_FIELD = $obUserField->Add($arFields);
		}

		if(WIZARD_INSTALL_DEMO_DATA)
		{

			$hldata = HL\HighloadBlockTable::getById($HL_ID)->fetch();
			$hlentity = HL\HighloadBlockTable::compileEntity($hldata);

			$entity_data_class = $hlentity->getDataClass();

			$arColors = array(
				"gold_con" => Array("UF_COLOR" => "#fadac1", "UF_PICT" => "", "UF_PICT_TYPE" => ""),
				"spacegray_con" => Array("UF_COLOR" => "#2e2f31", "UF_PICT" => "", "UF_PICT_TYPE" => ""),
				"silver_con" => Array("UF_COLOR" => "#e5e5e5", "UF_PICT" => "", "UF_PICT_TYPE" => ""),
				"yellow_con" => Array("UF_COLOR" => "#FFFF00", "UF_PICT" => "", "UF_PICT_TYPE" => ""),
				"blue_con" => Array("UF_COLOR" => "#0000FF", "UF_PICT" => "", "UF_PICT_TYPE" => ""),
				"black_con" => Array("UF_COLOR" => "#1C1C1C", "UF_PICT" => "", "UF_PICT_TYPE" => ""),
				"kolbaska_con" => Array("UF_COLOR" => "", "UF_PICT" => "588/12.jpg", "UF_PICT_TYPE" => "jpg"),
				"green_con" => Array("UF_COLOR" => "#009900", "UF_PICT" => "", "UF_PICT_TYPE" => ""),
				"lblue_con" => Array("UF_COLOR" => "#42AAFF", "UF_PICT" => "", "UF_PICT_TYPE" => ""),
				"red_con" => Array("UF_COLOR" => "#CC0605", "UF_PICT" => "", "UF_PICT_TYPE" => ""),
				"orange_con" => Array("UF_COLOR" => "#FFA420", "UF_PICT" => "", "UF_PICT_TYPE" => ""),
				"white_con" => Array("UF_COLOR" => "#F5F5F5", "UF_PICT" => "", "UF_PICT_TYPE" => ""),
				"differ_con" => Array("UF_COLOR" => "", "UF_PICT" => "800/12.jpg", "UF_PICT_TYPE" => "jpg"),
				"grey_con" => Array("UF_COLOR" => "#BBBBBB", "UF_PICT" => "", "UF_PICT_TYPE" => ""),
				"lime_con" => Array("UF_COLOR" => "#ADFF2F", "UF_PICT" => "", "UF_PICT_TYPE" => ""),
				"red-black_con" => Array("UF_COLOR" => "", "UF_PICT" => "6f1/2.png", "UF_PICT_TYPE" => "png"),
				"red-yell_con" => Array("UF_COLOR" => "", "UF_PICT" => "af3/1.png", "UF_PICT_TYPE" => "png")
			);

			foreach($arColors as $colorName => $colorArr)
			{
				$arData = array(
					'UF_NAME' => GetMessage("WZD_REF_COLOR_".$colorName),
					'UF_XML_ID' => $colorName,
					'UF_SORT' => "100",
					'UF_COLOR' => $colorArr["UF_COLOR"]
				);

				if(strlen($colorArr["UF_PICT"]) > 0)
				{
					$arData['UF_PICT'] = array(
						'name' => ToLower($colorName).".".$colorArr["UF_PICT_TYPE"],
						'type' => 'image/'.$colorArr["UF_PICT_TYPE"],
						'tmp_name' => WIZARD_ABSOLUTE_PATH."/site/services/iblock/xml/ru/color_files/uf/".$colorArr["UF_PICT"]
					);
				}

				$USER_FIELD_MANAGER->EditFormAddFields('HLBLOCK_'.$HL_ID, $arData);
				$USER_FIELD_MANAGER->checkFields('HLBLOCK_'.$HL_ID, null, $arData);
				$result = $entity_data_class::add($arData);
			}

		}

	}
}

?>