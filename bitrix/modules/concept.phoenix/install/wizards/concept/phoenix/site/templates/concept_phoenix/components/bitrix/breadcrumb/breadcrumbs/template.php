<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
global $APPLICATION;
global $PHOENIX_TEMPLATE_ARRAY;

//delayed function must return a string
if(empty($arResult))
	return "";



$strReturn = '';


$strReturn .= '<div class="inner-breadcrumb-wrap">';
$strReturn .= '<ol class="inner-breadcrumb clearfix" itemscope itemtype="http://schema.org/BreadcrumbList">';

$itemSize = count($arResult);

for($index = 0; $index < $itemSize; $index++)
{
	$title = htmlspecialcharsex(strip_tags(html_entity_decode($arResult[$index]["TITLE"])));


	if($arResult[$index]["LINK"] <> "" && $index != $itemSize-1)
	{
		$strReturn .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"';

			if($index == 0)
				$strReturn .= ' title="'.$PHOENIX_TEMPLATE_ARRAY["MESS"]["BREADCRUMB_TOMAIN_TITLE"].'"';

		$strReturn .= '>				
                <a href="'.$arResult[$index]["LINK"].'" itemprop="item"><span itemprop="name">'.$title.'</span></a>';
	}
	else
	{
		$strReturn .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="active"><span itemprop="name">'.$title.'</span>';
	}

	$strReturn .= '<meta itemprop="position" content="'.($index + 1).'"></li>';
}


$strReturn .= "</ol>";
$strReturn .= "</div>";

return $strReturn;
