<?IncludeModuleLangFile(__FILE__);
AddEventHandler('main', 'OnBuildGlobalMenu', 'OnBuildGlobalMenuHandlerphoenix');


$moduleID = 'concept.phoenix';
$GLOBALS['APPLICATION']->SetAdditionalCss("/bitrix/css/".$moduleID."/menu.css");
$GLOBALS['APPLICATION']->SetAdditionalCss("/bitrix/css/".$moduleID."/admin.css");
if($GLOBALS['APPLICATION']->GetGroupRight($moduleID) >= 'R'){

function OnBuildGlobalMenuHandlerphoenix(&$arGlobalMenu, &$arModuleMenu){

			$arMenu = array(
				'menu_id' => 'phoenix',
				'text' => GetMessage('CONCEPT_PHOENIX_SERVICES_MENU'),
				'title' => GetMessage('CONCEPT_PHOENIX_SERVICES_MENU'),
				'sort' => 1000,
				'items_id' => 'global_menu_phoenix_items',
				'items' => array(

					array(
						'text' => GetMessage('CONCEPT_PHOENIX_SERVICES_MENU_IN1'),
						'title' => GetMessage('CONCEPT_PHOENIX_SERVICES_MENU_IN1'),
						'sort' => 30,
						'icon' => 'imi_control_center',
						'page_icon' => 'pi_control_center',
						'items_id' => 'control_center',
						'url' => "concept_phoenix_admin_index.php",
					),
					array(
						'text' => GetMessage('CONCEPT_PHOENIX_SERVICES_MENU_IN4'),
						'title' => GetMessage('CONCEPT_PHOENIX_SERVICES_MENU_IN4'),
						'sort' => 30,
						'icon' => 'imi_marketing',
						'page_icon' => 'pi_control_center',
						'items_id' => 'marketing',
						"items" => array(
							array(
								"text" => GetMessage("CONCEPT_PHOENIX_SERVICES_MENU_IN4_1"),
								"url" => "concept_phoenix_admin_generate_robots.php",
							)
						)
					),
					
					array(
						'text' => GetMessage('CONCEPT_PHOENIX_SERVICES_MENU_IN2'),
						'title' => GetMessage('CONCEPT_PHOENIX_SERVICES_MENU_IN2'),
						'sort' => 40,
						'url' => 'concept_phoenix_admin_reviews_list.php',
						'more_url' => Array('concept_phoenix_admin_reviews_edit.php'),
						'icon' => 'sale_menu_icon_buyers',
						'page_icon' => 'pi_reviews',
						'items_id' => 'reviews'
					),

					array(
						'text' => GetMessage('CONCEPT_PHOENIX_SERVICES_MENU_IN6'),
						'title' => GetMessage('CONCEPT_PHOENIX_SERVICES_MENU_IN6'),
						'sort' => 40,
						'url' => 'concept_phoenix_admin_comments_list.php',
						'more_url' => Array('concept_phoenix_admin_comments_edit.php'),
						'icon' => 'sale_menu_icon_buyers',
						'page_icon' => 'pi_reviews',
						'items_id' => 'comments'
					),
					

					/*array(
						'text' => GetMessage('CONCEPT_PHOENIX_SERVICES_MENU_IN5'),
						'title' => GetMessage('CONCEPT_PHOENIX_SERVICES_MENU_IN5'),
						'sort' => 50,
						'url' => 'concept_phoenix_admin_generate_amocrm.php',
						'icon' => 'imi_marketing',
						'page_icon' => 'marketing',
						'items_id' => 'contacts'
					),*/

					array(
						'text' => GetMessage('CONCEPT_PHOENIX_SERVICES_MENU_IN3'),
						'title' => GetMessage('CONCEPT_PHOENIX_SERVICES_MENU_IN3'),
						'sort' => 60,
						'url' => 'concept_phoenix_admin_help.php',
						'icon' => 'imi_contacts',
						'page_icon' => 'pi_contacts',
						'items_id' => 'contacts'
					)
				),
			);

			$arGlobalMenu[] = $arMenu;
		}
	}

?>