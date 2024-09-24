<?
use Bitrix\Main\UserTable;

class My
{
	static function Resize($image_id,$width,$height,$small=false,$watermark=false)
	{
		$result="";
		if(!$image_id)
		{
			if(CModule::IncludeModule("iblock"))
			{
				$ar_item=CIBlockElement::GetList(
					array(),
					array("IBLOCK_ID"=>9,"ACTIVE"=>"Y"),
					false,
					false,
					array("ID","NAME","PREVIEW_PICTURE")
				);
				if($item=$ar_item->GetNext())
				{
					$image_id=$item["PREVIEW_PICTURE"];
					$watermark=false;
				}
			}
		}
		$arFile = CFile::MakeFileArray($image_id);
		if($small)
		{
			$size_image=getimagesize($arFile["tmp_name"]);
			$koef_width=$size_image[0]/$width;
			$koef_height=$size_image[1]/$height;
			if($koef_width>$koef_height)
			{
				$new_size=round($size_image[0]/$koef_height+0.5);
				$size_array=array("width" => $new_size, "height" => $height);
			}
			else
			{
				$new_size=round($size_image[1]/$koef_width+0.5);
				$size_array=array("width" =>$width, "height" => $new_size);
			}
		}
		else
		{
			$size_array=array("width" =>$width, "height" => $height);
		}
		$file=CFile::ResizeImageGet($image_id, $size_array, BX_RESIZE_IMAGE_PROPORTIONAL, false);
		$result=array(
			"SRC"=>$file["src"],
			"W"=>$file["width"],
			"H"=>$file["height"]
		);
		if($watermark)
		{
			$ar_watermark_pic_item=CIBlockElement::GetList(
				array(),
				array("IBLOCK_ID"=>$watermark,"ACTIVE"=>"Y","!PREVIEW_PICTURE"=>false),
				false,
				false,
				array("ID","NAME","PREVIEW_PICTURE","DETAIL_PICTURE")
			);
			if($watermark_pic_item=$ar_watermark_pic_item->GetNext())
			{
				$opacity=20;
				$watermark_pic=My::Resize($watermark_pic_item["DETAIL_PICTURE"],round($size_array["width"]/2,0),round($size_array["height"]/2,0),false);
				if($size_array["width"]<=200 || $size_array["height"]<=200)
				{
					$watermark_pic=My::Resize($watermark_pic_item["PREVIEW_PICTURE"],round($size_array["width"]/3.5,0),round($size_array["height"]/3.5,0),false);
					$opacity=15;
				}
				$path_with_watermark=$_SERVER["DOCUMENT_ROOT"].str_replace(".","_w.",$result["SRC"]);
				$result_water=CFile::ResizeImageFile(
					$_SERVER["DOCUMENT_ROOT"].$result["SRC"],
					$path_with_watermark,
					false,
					BX_RESIZE_IMAGE_PROPORTIONAL,
					array(
						"position" => "center",
						"file"=>$_SERVER["DOCUMENT_ROOT"].$watermark_pic["SRC"],
						"alpha_level"=>$opacity
					),
					false,
					false
				);
				if($result_water)
				{
					$path_with_watermark=substr($path_with_watermark,strpos($path_with_watermark,"/upload/"));
					$result=array(
						"SRC"=>$path_with_watermark
					);
				}
			}
		}
		return $result;
	}
	static function NewResize($image_id,$width,$height,$small=false,$watermark=false)
	{
		$result=false;
		if(!$image_id)
		{
			if(CModule::IncludeModule("iblock"))
			{
				$ar_item=CIBlockElement::GetList(
					array(),
					array("IBLOCK_ID"=>9,"ACTIVE"=>"Y"),
					false,
					false,
					array("ID","NAME","PREVIEW_PICTURE")
				);
				if($item=$ar_item->GetNext())
				{
					$image_id=$item["PREVIEW_PICTURE"];
					$watermark=false;
				}
			}
		}
		$arFile = CFile::MakeFileArray($image_id);
		if($small)
		{
			$size_image=getimagesize($arFile["tmp_name"]);
			$koef_width=$size_image[0]/$width;
			$koef_height=$size_image[1]/$height;
			if($koef_width>$koef_height)
			{
				$new_size=round($size_image[0]/$koef_height+0.5);
				$size_array=array("width" => $new_size, "height" => $height);
			}
			else
			{
				$new_size=round($size_image[1]/$koef_width+0.5);
				$size_array=array("width" =>$width, "height" => $new_size);
			}
		}
		else
		{
			$size_array=array("width" =>$width, "height" => $height);
		}
		$file=CFile::ResizeImageGet($image_id, $size_array, BX_RESIZE_IMAGE_PROPORTIONAL, false);
		if($file["src"])
		{
			$result='src="'.$file["src"].'"';
			if($file["width"])
			{
				$result.=' width="'.$file["width"].'"';
			}
			if($file["height"])
			{
				$result.=' height="'.$file["height"].'"';
			}
			if($watermark)
			{
				$ar_watermark_pic_item=CIBlockElement::GetList(
					array(),
					array("IBLOCK_ID"=>$watermark,"ACTIVE"=>"Y","!PREVIEW_PICTURE"=>false),
					false,
					false,
					array("ID","NAME","PREVIEW_PICTURE","DETAIL_PICTURE")
				);
				if($watermark_pic_item=$ar_watermark_pic_item->GetNext())
				{
					$opacity=20;
					$watermark_pic=My::Resize($watermark_pic_item["DETAIL_PICTURE"],round($size_array["width"]/2,0),round($size_array["height"]/2,0),false);
					if($size_array["width"]<=200 || $size_array["height"]<=200)
					{
						$watermark_pic=My::Resize($watermark_pic_item["PREVIEW_PICTURE"],round($size_array["width"]/3.5,0),round($size_array["height"]/3.5,0),false);
						$opacity=15;
					}
					$path_with_watermark=$_SERVER["DOCUMENT_ROOT"].str_replace(".","_w.",$file["src"]);
					$result_water=CFile::ResizeImageFile(
						$_SERVER["DOCUMENT_ROOT"].$file["src"],
						$path_with_watermark,
						false,
						BX_RESIZE_IMAGE_PROPORTIONAL,
						array(
							"position" => "center",
							"file"=>$_SERVER["DOCUMENT_ROOT"].$watermark_pic["SRC"],
							"alpha_level"=>$opacity
						),
						false,
						false
					);
					if($result_water)
					{
						$path_with_watermark=substr($path_with_watermark,strpos($path_with_watermark,"/upload/"));
						$result='src="'.$path_with_watermark.'"';
						if($file["width"])
						{
							$result.=' width="'.$file["width"].'"';
						}
						if($file["height"])
						{
							$result.=' height="'.$file["height"].'"';
						}
					}
				}
			}
		}
		return $result;
	}
	function SectionPicture($section_id)
	{
		$picture_id=false;
		if(CModule::IncludeModule("iblock"))
		{
			$ar_nav = CIBlockSection::GetNavChain(false, $section_id);
			while($nav=$ar_nav->Fetch())
			{
				if(isset($nav["PICTURE"]) && !empty($nav["PICTURE"]))
				{
					$picture_id=$nav["PICTURE"];
				}
			}
		}
		return $picture_id;
	}
	function GetDateStr($date)
	{
		$months=array("пьянварь","января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря");
		$cur_date=explode(".",$date);
		$cool_date=$cur_date[0]." ".$months[(int)$cur_date[1]]." ".$cur_date[2];
		return $cool_date;
	}
	function GetDate($date,$year=true,$full=true)
	{
		$months=array("пьянварь","января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря");
		$cur_date=explode(".",$date);
		if($full)
		{
			$cool_date=$cur_date[0]." ".$months[(int)$cur_date[1]];
		}
		else
		{
			$cool_date=IntVal($cur_date[0])." ".$months[(int)$cur_date[1]];
		}
		if($year)
		{
			$cool_date.=" ".$cur_date[2];
		}
		return $cool_date;
	}
	function GetRusWeek($date,$shirt=true,$format="normal")
	{
		$what_show="SHIRT";
		if(!$shirt)
		{
			$what_show="FOOL";
		}
		$weekdays=array(
			"FOOL"=>array(
				"Воскресенье",
				"Понедельник",
				"Вторник",
				"Среда",
				"Четверг",
				"Пятница",
				"Суббота"
			),
			"SHIRT"=>array(
				"Вс",
				"Пн",
				"Вт",
				"Ср",
				"Чт",
				"Пт",
				"Сб"
			)
		);
		$cur_date=explode(".",$date);
		$cur_week_day=date("w",mktime(0,0,0,$cur_date[1],$cur_date[0],$cur_date[2]));
		$temp_result=$weekdays[$what_show][$cur_week_day];
		$result=$temp_result;
		if($format=="lower")
		{
			$result=strtolower($temp_result);
		}
		if($format=="upper")
		{
			$result=strtoupper($temp_result);
		}
		return $result;
	}
	function GetWordBySum($num)
	{

		# Все варианты написания чисел прописью от 0 до 999 скомпануем в один небольшой массив
		$m=array(
		 	array('ноль'),
	  		array('-','один','два','три','четыре','пять','шесть','семь','восемь','девять'),
		  	array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать','пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать'),
		  	array('-','-','двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят','восемьдесят','девяносто'),
		  	array('-','сто','двести','триста','четыреста','пятьсот','шестьсот','семьсот','восемьсот','девятьсот'),
		  	array('-','одна','две')
		);

		# Все варианты написания разрядов прописью скомпануем в один небольшой массив
	 	$r=array(
		  	array('...ллион','','а','ов'), // используется для всех неизвестно больших разрядов
		  	array('тысяч','а','и',''),
		  	array('миллион','','а','ов'),
		  	array('миллиард','','а','ов'),
		  	array('триллион','','а','ов'),
		  	array('квадриллион','','а','ов'),
		  	array('квинтиллион','','а','ов')
		  	// ,array(... список можно продолжить
	 	);

	 	if($num==0)return$m[0][0]; # Если число ноль, сразу сообщить об этом и выйти
		 	$o=array(); # Сюда записываем все получаемые результаты преобразования

		# Разложим исходное число на несколько трехзначных чисел и каждое полученное такое число обработаем отдельно
		foreach(array_reverse(str_split(str_pad($num,ceil(strlen($num)/3)*3,'0',STR_PAD_LEFT),3))as$k=>$p)
	 	{
		  	$o[$k]=array();

			# Алгоритм, преобразующий трехзначное число в строку прописью
		  	foreach($n=str_split($p)as$kk=>$pp)
		  		if(!$pp)continue;else
		   	switch($kk){
		    	case 0:$o[$k][]=$m[4][$pp];break;
		    	case 1:if($pp==1){$o[$k][]=$m[2][$n[2]];break 2;}else$o[$k][]=$m[3][$pp];break;
		    	case 2:if(($k==1)&&($pp<=2))$o[$k][]=$m[5][$pp];else$o[$k][]=$m[1][$pp];break;
		   	}
		   	$p*=1;
		   	if(!$r[$k])
	   			$r[$k]=reset($r);

			# Алгоритм, добавляющий разряд, учитывающий окончание руского языка
		  	if($p&&$k)switch(true)
		  	{
			   case preg_match("/^[1]$|^\\d*[0,2-9][1]$/",$p):$o[$k][]=$r[$k][0].$r[$k][1];break;
			   case preg_match("/^[2-4]$|\\d*[0,2-9][2-4]$/",$p):$o[$k][]=$r[$k][0].$r[$k][2];break;
			   default:$o[$k][]=$r[$k][0].$r[$k][3];break;
		  	}
		  	$o[$k]=implode(' ',$o[$k]);
	 	}
		return implode(' ',array_reverse($o));
	}
	function GetWordByNumber($number,$words)
	{
		$needed=substr($number,strlen($number)-1);
		if($needed==0 || $needed>=5 || $number==11)
		{
			$word=$words[0];
		}
		elseif($needed==1)
		{
			$word=$words[1];
		}
		else
		{
			$word=$words[2];
		}
		return $word;
	}
	static function GetMinPrice($productID,$quantity=1)
	{
		global $USER;
		if (!is_object($USER)) $USER = new CUser;
		$price=false;
		if(CModule::IncludeModule("iblock") && CModule::IncludeModule("catalog"))
		{
			$arPrice = CCatalogProduct::GetOptimalPrice($productID, $quantity, $USER->GetUserGroupArray(), "N");
			if (!$arPrice || count($arPrice) <= 0)
			{
			    if ($nearestQuantity = CCatalogProduct::GetNearestQuantityPrice($productID, $quantity, $USER->GetUserGroupArray()))
			    {
			        $quantity = $nearestQuantity;
			        $arPrice = CCatalogProduct::GetOptimalPrice($productID, $quantity, $USER->GetUserGroupArray(), "N");
			    }
			}
			if($arPrice["DISCOUNT_PRICE"]==0)
			{
				$price=false;
			}
			else
			{
				$price=array(
					"FULL"=>$arPrice["PRICE"]["PRICE"],
					"PRICE"=>$arPrice["DISCOUNT_PRICE"],
					"DISCOUNT_TYPE"=>$arPrice["DISCOUNT"]["VALUE_TYPE"],
					"DATA"=>$arPrice
				);
			}
		}
		if(!$price)
		{
			$price=-1;
			$ar_cur_item=CIBlockElement::GetByID($productID);
			if($cur_item=$ar_cur_item->GetNext())
			{
				$mxResult = CCatalogSKU::GetInfoByProductIBlock($cur_item["IBLOCK_ID"]);
				if(is_array($mxResult))
				{
					$ar_offers=CIBlockElement::GetList(
						array(),
						array("IBLOCK_ID"=>$mxResult["IBLOCK_ID"],"ACTIVE"=>"Y","PROPERTY_CML2_LINK"=>$productID),
						false,
						false,
						array("ID","NAME","PROPERTY_CML2_LINK","CATALOG_GROUP_1")
					);
					while($offer=$ar_offers->GetNext())
					{
						$temp_price=My::GetMinPrice($offer["ID"],1);
						if(($price==-1 || $temp_price["PRICE"]<$price["PRICE"]) && $temp_price["PRICE"]>0)
						{
							$price=$temp_price;
						}
					}
				}
			}
		}
		return $price;
	}
	function GetPictureID($item_id)
	{
		$result=false;
		if(CModule::IncludeModule("iblock") && CModule::IncludeModule("catalog"))
		{
			$ar_cur_item=CIBlockElement::GetByID($item_id);
			if($cur_item=$ar_cur_item->GetNext())
			{
				$result=$cur_item["PREVIEW_PICTURE"];
				if(!$result || $result===NULL)
				{
					$mxResult = CCatalogSKU::GetInfoByOfferIBlock($cur_item["IBLOCK_ID"]);
					if(is_array($mxResult))
					{
						$db_props = CIBlockElement::GetProperty($cur_item["IBLOCK_ID"], $cur_item["ID"], array("sort" => "asc"), Array("CODE"=>"CML2_LINK"));
						if($ar_props = $db_props->Fetch())
						{
							$ar_parent_item=CIBlockElement::GetByID($ar_props["VALUE"]);
							if($parent_item=$ar_parent_item->GetNext())
							{
								$result=$parent_item["PREVIEW_PICTURE"];
							}
						}
					}
				}
			}
		}
		return $result;
	}
	static function Money($price)
	{
		$digits=0;
		$more=$price-intval($price);
		if($more>0)
		{
			$digits=1;
		}
		return number_format($price,$digits,"."," ");
	}
	static function GetSize($number)
	{
		$label=array("б","Кб","Мб","Гб");
		$step=0;
		while($number/1024>1024)
		{
			$step++;
			$number/=1024;
		}
		$step++;
		$number/=1024;
		return round($number,1)." ".$label[$step];
	}
}

function myscandir($dir)
{
    $list = scandir($dir);
    unset($list[0],$list[1]);
    return array_values($list);
}

function clear_dir($dir)
{
    $list = myscandir($dir);

    foreach ($list as $file)
    {
        if (is_dir($dir.$file))
        {
            clear_dir($dir.$file.'/');
            rmdir($dir.$file);
        }
        else
        {
            unlink($dir.$file);
        }
    }
}

AddEventHandler("catalog", "OnSuccessCatalogImport1C", "OnSuccessCatalogImport1CHandler");
function OnSuccessCatalogImport1CHandler(&$arFields)
{
	clear_dir($_SERVER['DOCUMENT_ROOT'].'/bitrix/cache/');
}

AddEventHandler("iblock", "OnBeforeIBlockElementAdd", "OnBeforeIBlockElementAddHandler");
function OnBeforeIBlockElementAddHandler(&$arFields)
{
    if($arFields['IBLOCK_ID'] == '21'){

		$property_enums = CIBlockPropertyEnum::GetList([], ["IBLOCK_ID" => $arFields['IBLOCK_ID'], "CODE" => "NEW_STICKER"]);
		if($enum_fields = $property_enums->GetNext()){
			$arFields['PROPERTY_VALUES'][$enum_fields['PROPERTY_ID']] = ['n0' => ['VALUE' => $enum_fields['ID']]];
			$arFields['SORT'] = '50';
		}

    	return;
	}
}

if(CModule::IncludeModule("iblock"))
{
    // Отключаем галочку новинка у элементов.
    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_CREATE","PROPERTY_*");
    $arFilter = Array(
        "IBLOCK_ID" => 21,
        "ACTIVE" => "Y",
        "<DATE_CREATE" => date($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT")), strtotime('-90 day')),
		"PROPERTY_NEW_STICKER_VALUE" => "Y"
    );
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNextElement())
    {
        $arFields = $ob->GetFields();
        $arProps = $ob->GetProperties();

        if($arProps['NEW_STICKER']['VALUE'] == "Y")
            CIBlockElement::SetPropertyValuesEx($arFields['ID'], $arFields['IBLOCK_ID'], array($arProps['NEW_STICKER']['CODE'] => ""));
    }
}

AddEventHandler("main", "OnAfterUserRegister", "OnAfterUserRegisterHandler");
function OnAfterUserRegisterHandler(&$arFields)
{
	$dbResult = UserTable::getList(['select' => ['CONFIRM_CODE'], 'filter' => ['=ID' => $arFields["USER_ID"]]]);
	$arResults = $dbResult->fetch();

	$arFields["CONFIRM_CODE"] = $arResults['CONFIRM_CODE'];
	
    $event = new CEvent;
	$event->SendImmediate("USER_WELCOME", SITE_ID, $arFields);
}
?>
