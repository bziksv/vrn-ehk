<?php


class CDadataAdminSuggestions
	{

		private static $module_id = 'gorillas.dadataadmin';
		private static $url = "https://dadata.ru/api/v2";
		private static $url_static_css = "https://cdn.jsdelivr.net/npm/suggestions-jquery@latest/dist/css/suggestions.min.css";
		private static $url_static_js = "https://cdn.jsdelivr.net/npm/suggestions-jquery@latest/dist/js/jquery.suggestions.min.js";


		public static function request($code)
			{
				return array_key_exists($code, $_REQUEST) ? $_REQUEST[$code] : false;
			}

		public static function request_full($code)
			{
				return array_key_exists($code, $_REQUEST) && strlen($_REQUEST[$code]) ? $_REQUEST[$code] : false;
			}

		public static function application()
			{
				return $GLOBALS['APPLICATION'];
			}

		public static function user()
			{
				return $GLOBALS['USER'];
			}

		public static function fieldSelector($fieldNo)
			{
				return "'[name=ORDER_PROP_" . $fieldNo . "]'";

			}

		private static function isNewLocationModule()
			{
				if (!method_exists(CSaleLocation, "isLocationProEnabled"))
					{
						return false;
					}
				return CSaleLocation::isLocationProEnabled();
			}

		public static function GetPartParameterString($SuggestionType, $ParamName)
			{
				if ($SuggestionType == 'NAME')
					{
						if ($ParamName == 'data.name')
							{
								return "params: { parts:['NAME']},";
							}
						if ($ParamName == 'data.surname')
							{
								return "params: { parts:['SURNAME']},";
							}
						if ($ParamName == 'data.patronymic')
							{
								return "params: { parts:['PATRONYMIC']},";
							}
					}
                elseif ($SuggestionType == 'ADDRESS')
					{
						if (in_array($ParamName, array(
							'data.region',
							'data.country',
							'data.area'
						)))
							{
								return "bounds: 'region-area',";
							}
						if (in_array($ParamName, array(
							'data.city',
							'data.settlement'
						)))
							{
								return "bounds: 'city-settlement',";
							}
						if (in_array($ParamName, array('data.street')))
							{
								return "bounds: 'street',";
							}
						if (in_array($ParamName, array(
							'data.house',
							'data.block'
						)))
							{
								return "bounds: 'house',";
							}

					}

			}

		private function getMappingObject()
			{
				$arFieldValues = unserialize(COption::GetOptionString(self::$module_id, 'mapping', "", SITE_ID));
				$arGroupMapping = unserialize(COption::GetOptionString(self::$module_id, 'typemapping', "", SITE_ID));
				$newArray = array();
				$boundFields = array();
				foreach ($arFieldValues as $fieldNo => $fieldVal)
					if ($fieldVal)
						{
							$suggestionType = strstr($fieldVal, '_', true);
							$suggestionVar = substr($fieldVal, strpos($fieldVal, '_') + 1);
							$newArray[$fieldNo]['type'] = $suggestionType;
							foreach ($arGroupMapping as $groupId => $groupFields)
								if (in_array($fieldNo, $groupFields))
									{
										$newArray[$fieldNo]['group'] = $groupId;
									}


							$newArray[$fieldNo]['var'] = $suggestionVar;
							if ($suggestionType == 'NAME')
								{
									if ($suggestionVar == 'data.name')
										{
											$newArray[$fieldNo]['params'] = "{ parts:['NAME']}";
										}
									if ($suggestionVar == 'data.surname')
										{
											$newArray[$fieldNo]['params'] = "{ parts:['SURNAME']}";
										}
									if ($suggestionVar == 'data.patronymic')
										{
											$newArray[$fieldNo]['params'] = "{ parts:['PATRONYMIC']}";
										}
								}
                            elseif ($suggestionType == 'ADDRESS')
								{
									if (in_array($suggestionVar, array(
										'data.region',
										'data.country',
										'data.area'
									)))
										{
											$newArray[$fieldNo]['bounds'] = "region-area";
											if (isset($newArray[$fieldNo]['group']))
												{
													$boundFields[$newArray[$fieldNo]['group']]['city-settlement'] =
														$fieldNo;
												}
										}
									if (in_array($suggestionVar, array(
										'data.city',
										'data.settlement'
									)))
										{
											$newArray[$fieldNo]['bounds'] = "city-settlement";
											if (isset($newArray[$fieldNo]['group']))
												{
													$boundFields[$newArray[$fieldNo]['group']]['street'] = $fieldNo;
												}
										}

									if (in_array($suggestionVar, array('data.street')))
										{
											$newArray[$fieldNo]['bounds'] = "street";
											if (isset($newArray[$fieldNo]['group']))
												{
													$boundFields[$newArray[$fieldNo]['group']]['house'] = $fieldNo;
												}

										}
									if (in_array($suggestionVar, array(
										'data.house',
										'data.block'
									)))
										{

											$newArray[$fieldNo]['bounds'] = "house";
										}
								}

						}
				foreach ($newArray as $fieldNo => $fieldProps)
					{
						$fieldBounds = $fieldProps['bounds'];
						$fieldGroup = $fieldProps['group'];
						if (isset($fieldBounds) && isset($fieldGroup))
							{
								$fieldConstraint = $boundFields[$fieldGroup][$fieldBounds];
								if (isset($fieldConstraint))
									{
										$newArray[$fieldNo]['constraint'] = $fieldConstraint;
									}
							}
					}

				return CUtil::PhpToJSObject($newArray);
			}

	/*
	 * ������� ����������� javascript.
	*/
		public static function setJS($arErrors = array())
			{
				$arFieldValues = unserialize(COption::GetOptionString(self::$module_id, 'mapping', "", SITE_ID));
				$arFieldsTypes = array();
				$arMainType = array();
				$arLocations = array();
				foreach ($arFieldValues as $fieldNo => $fieldVal)
					{
						if ($fieldVal != "")
							{
								if ($fieldVal != "ADDRESS_LOCATION")
									{
										$type = explode("_", $fieldVal, 2);

										if ($type[1] == "value")
											{
												$arMainType[$fieldNo] = $type[0];
											}
										else
											{
												$arFieldsTypes[$type[0]][$fieldNo] = $type[1];
											}
									}
								else
									{
										$arLocations[$fieldNo] = $fieldNo;
									}
							}
					}
				ob_start();
				?>

                <script type="text/javascript">
									var dadataSuggestions = {
										fieldSelector: function (id) {
											return '[name=\'PROPERTIES[' + id + ']\']';
										},
										getConf: function (type, callback) {
											return {
												serviceUrl: "<?=self::$url?>",
												token: "<?=COption::GetOptionString(self::$module_id, 'apikey', '', SITE_ID)?>",
												partner: "BITRIX.GORILLAS",
												type: type,
												count: 5,
												onSelect: callback,
											}
										},
										initSuggestionFields: function () {
						<? foreach($arMainType as $id => $type):?>
											$(dadataSuggestions.fieldSelector(<?=$id?>)).suggestions(dadataSuggestions.getConf('<?=$type?>',
												function (suggestion) {
							<? if(!empty($arFieldsTypes[$type])): ?>
							<? foreach($arFieldsTypes[$type] as $prop=>$code): ?>
                                if (typeof suggestion.<?=$code?> != "undefined" && suggestion.<?=$code?> !=  null) {
                                    var stringValue='';

                                <? if ($code == "data.metro"): ?>
                                    stringValue = suggestion.<?=$code?>.map(e => e.name).join(",");
                                <? else: ?>
                                    stringValue = suggestion.<?=$code?>;
                                <? endif; ?>

                                    $(dadataSuggestions.fieldSelector(<?=$prop?>)).val(stringValue);
                                }
                                <? if($code=='data.geo'): ?>
                                        if(suggestion.data.geo_lat && suggestion.data.geo_lon)
                                            stringValue = suggestion.data.geo_lat+","+suggestion.data.geo_lon;
                                        $(dadataSuggestions.fieldSelector(<?=$prop?>)).val(stringValue);
                                <? endif; ?>

                            <? endforeach ?>
							<? endif ?>

												}));
						<? endforeach ?>
										}
									};

									BX.bind(window, "load", function () {
										dadataSuggestions.initSuggestionFields();
									});
									BX.ready(function () {
										BX.addCustomEvent("onAjaxSuccess", BX.delegate(function (command, params) {
											dadataSuggestions.initSuggestionFields();
										}, this));
									});

                </script>
				<?
				$script = ob_get_contents();
				ob_end_clean();
				return $script;
			}

	/*
	 * ����� ������ �� �������� ��� ����������.
	 * stdClass Object ([detail] => Zero balance)
	 */
		public static function magicFunction($response)
			{
				if (is_array($response->data))
					{
						if (is_array($response->data[0]) && is_object($response->data[0][0]))
							{
								return (array)$response->data[0][0];
							}
					}
				return array();
			}

		public static function OnPrologHandler()
			{
				global $APPLICATION;
				$currPage = $APPLICATION->GetCurPage(false);
				if (($currPage == "/bitrix/admin/sale_order_edit.php" || $currPage == "/bitrix/admin/sale_order_create.php") && COption::GetOptionString(self::$module_id, 'enabled', 'N') == 'Y')
					{
						CJSCore::Init(array('jquery2'));
						$APPLICATION->SetAdditionalCSS(self::$url_static_css);
						$APPLICATION->SetAdditionalCSS("/bitrix/modules/gorillas.dadataadmin/css/style.css");
						$APPLICATION->AddHeadString('<script type="text/javascript" src="' . self::$url_static_js . '"></script>');
						$APPLICATION->AddHeadString(CDadataAdminSuggestions::setJS());
					}

			}
	}