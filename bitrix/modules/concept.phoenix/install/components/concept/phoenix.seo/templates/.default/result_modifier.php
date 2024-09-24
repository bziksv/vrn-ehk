<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
$h1 = $GLOBALS["h1_main"];
$points = 0;

if($arResult["NOINDEX"] == 1)
{
    $arMess = Array();
    
    $arMess["class"] = "bad";
    $arMess["TEXT"] = "PHOENIX_SEO_NOINDEX_BAD";
    
    $arResult["SEO_MESSAGE"][] = $arMess;
}




//h1
if($h1 == 1 || ($GLOBALS["IS_CONSTRUCTOR"] == false && (strlen($arResult["H1"]) > 0 || strlen($arResult["NEW_H1"]) > 0) ))
{
    $points += 15;
    
    $arMess = Array();
    
    $arMess["class"] = "good";
    $arMess["TEXT"] = "PHOENIX_SEO_H1_GOOD";
    
    $arResult["SEO_MESSAGE"][] = $arMess;
}
else
{
    $arMess = Array();
    
    $arMess["class"] = "bad";
    $arMess["TEXT"] = "PHOENIX_SEO_H1_BAD";
    
    $arResult["SEO_MESSAGE"][] = $arMess;
    
    $arResult["SEO_H1_MESSAGE"][] = $arMess;
}


//title
$title = $arResult["TITLE"];
                                                    
if(strlen($arResult["NEW_TITLE"]) > 0)
    $title = $arResult["NEW_TITLE"];
    
    
if(strlen($title) > 0)
{
    $points += 25;
    
    $arMess = Array();
    
    $arMess["class"] = "good";
    $arMess["TEXT"] = "PHOENIX_SEO_TITLE1_GOOD";
    
    $arResult["SEO_MESSAGE"][] = $arMess;

}
else
{
    $arMess = Array();
    
    $arMess["class"] = "bad";
    $arMess["TEXT"] = "PHOENIX_SEO_TITLE1_BAD";
    
    $arResult["SEO_MESSAGE"][] = $arMess;
    
    $arResult["SEO_TITLE_MESSAGE"][] = $arMess;
    
}


if(strlen($title) > 0 && strlen($arResult["NEW_TITLE"]) > 0)
{
    $points += 15;
    
    $arMess = Array();
    
    $arMess["class"] = "good";
    $arMess["TEXT"] = "PHOENIX_SEO_TITLE2_GOOD";
    
    $arResult["SEO_MESSAGE"][] = $arMess;
}
else
{
    $arMess = Array();
    
    $arMess["class"] = "notbad";
    $arMess["TEXT"] = "PHOENIX_SEO_TITLE2_BAD";
    
    $arResult["SEO_MESSAGE"][] = $arMess;
    
    $arResult["SEO_TITLE_MESSAGE"][] = $arMess;
}

if(strlen($title) > 0 && mb_strlen($title) <= 100)
{
    $points += 3;
    
    $arMess = Array();
    
    $arMess["class"] = "good";
    $arMess["TEXT"] = "PHOENIX_SEO_TITLE3_GOOD";
    
    $arResult["SEO_MESSAGE"][] = $arMess;

}
elseif(strlen($title) > 0 && mb_strlen($title) > 100)
{
    $arMess = Array();
    
    $arMess["class"] = "notbad";
    $arMess["TEXT"] = "PHOENIX_SEO_TITLE3_BAD";
    
    $arResult["SEO_MESSAGE"][] = $arMess;
    
    $arResult["SEO_TITLE_MESSAGE"][] = $arMess;
}






//description

$description = $arResult["DESCRIPTION"];
                                                    
if(strlen($arResult["NEW_DESCRIPTION"]) > 0)
    $description = $arResult["NEW_DESCRIPTION"];

if(strlen($description) > 0)
{
    $points += 10;
    
    $arMess = Array();
    
    $arMess["class"] = "good";
    $arMess["TEXT"] = "PHOENIX_SEO_DESCRIPTION1_GOOD";
    
    $arResult["SEO_MESSAGE"][] = $arMess;
    
    
    if(strlen($arResult["NEW_DESCRIPTION"]) > 0)
    {
        $points += 12;
    
        $arMess = Array();
        
        $arMess["class"] = "good";
        $arMess["TEXT"] = "PHOENIX_SEO_DESCRIPTION2_GOOD";
        
        $arResult["SEO_MESSAGE"][] = $arMess;   
    }
    else
    {
        $arMess = Array();
    
        $arMess["class"] = "notbad";
        $arMess["TEXT"] = "PHOENIX_SEO_DESCRIPTION2_BAD";
        
        $arResult["SEO_MESSAGE"][] = $arMess;
        
        $arResult["SEO_DESCRIPTION_MESSAGE"][] = $arMess;
    }
    
    
        
        
    if(mb_strlen($description) <= 200)
    {
        $points += 2;
    
        $arMess = Array();
        
        $arMess["class"] = "good";
        $arMess["TEXT"] = "PHOENIX_SEO_DESCRIPTION3_GOOD";
        
        $arResult["SEO_MESSAGE"][] = $arMess;
    }
    else
    {
        $arMess = Array();
    
        $arMess["class"] = "notbad";
        $arMess["TEXT"] = "PHOENIX_SEO_DESCRIPTION3_BAD";
        
        $arResult["SEO_MESSAGE"][] = $arMess;
        
        $arResult["SEO_DESCRIPTION_MESSAGE"][] = $arMess;
    }
    
}
else
{
    $arMess = Array();
    
    $arMess["class"] = "bad";
    $arMess["TEXT"] = "PHOENIX_SEO_DESCRIPTION1_BAD";
    
    $arResult["SEO_MESSAGE"][] = $arMess;
    
    $arResult["SEO_DESCRIPTION_MESSAGE"][] = $arMess;
}



if(strlen($description) > 0 && $description != $title)
{
    $points += 10;
    
    $arMess = Array();
    
    $arMess["class"] = "good";
    $arMess["TEXT"] = "PHOENIX_SEO_DESCRIPTION4_GOOD";
    
    $arResult["SEO_MESSAGE"][] = $arMess;
}
else
{
    $arMess = Array();
    
    $arMess["class"] = "bad";
    $arMess["TEXT"] = "PHOENIX_SEO_DESCRIPTION4_BAD";
    
    $arResult["SEO_MESSAGE"][] = $arMess;
    
    $arResult["SEO_DESCRIPTION_MESSAGE"][] = $arMess;
}

//keywords

$keywords = $arResult["KEYWORDS"];
                                                    
if(strlen($arResult["NEW_KEYWORDS"]) > 0)
    $keywords = $arResult["NEW_KEYWORDS"];

if(strlen($keywords) > 0)
{
    $points += 2;
    
    $arMess = Array();
    
    $arMess["class"] = "good";
    $arMess["TEXT"] = "PHOENIX_SEO_KEYWORDS1_GOOD";
    
    $arResult["SEO_MESSAGE"][] = $arMess;
}
else
{
    $arMess = Array();
    
    $arMess["class"] = "notbad";
    $arMess["TEXT"] = "PHOENIX_SEO_KEYWORDS1_BAD";
    
    $arResult["SEO_MESSAGE"][] = $arMess;
    
    $arResult["SEO_KEYWORDS_MESSAGE"][] = $arMess;
}




//og
if(strlen($arResult["OG_TITLE"]) > 0)
{
    $points += 2;
    
    $arMess = Array();
    
    $arMess["class"] = "good";
    $arMess["TEXT"] = "PHOENIX_SEO_OG_TITLE_GOOD";
    
    $arResult["SEO_MESSAGE"][] = $arMess;
}
else
{
    $arMess = Array();
    
    $arMess["class"] = "notbad";
    $arMess["TEXT"] = "PHOENIX_SEO_OG_TITLE_BAD";
    
    $arResult["SEO_MESSAGE"][] = $arMess;
}

if(strlen($arResult["OG_DESCRIPTION"]) > 0)
{
    $points += 2;
    
    $arMess = Array();
    
    $arMess["class"] = "good";
    $arMess["TEXT"] = "PHOENIX_SEO_OG_DESCRIPTION_GOOD";
    
    $arResult["SEO_MESSAGE"][] = $arMess;
}
else
{
    $arMess = Array();
    
    $arMess["class"] = "notbad";
    $arMess["TEXT"] = "PHOENIX_SEO_OG_DESCRIPTION_BAD";
    
    $arResult["SEO_MESSAGE"][] = $arMess;
}

if($arResult["OG_IMAGE"] > 0)
{
    $points += 2;
    
    $arMess = Array();
    
    $arMess["class"] = "good";
    $arMess["TEXT"] = "PHOENIX_SEO_OG_IMAGE_GOOD";
    
    $arResult["SEO_MESSAGE"][] = $arMess;
}
else
{
    $arMess = Array();
    
    $arMess["class"] = "notbad";
    $arMess["TEXT"] = "PHOENIX_SEO_OG_IMAGE_BAD";
    
    $arResult["SEO_MESSAGE"][] = $arMess;
}

if($arResult["NOINDEX"] == 1)
    $points = 0;


$arResult["SEO_POINTS"] = $points;

if($points <= 50)
{
    $arResult["SEO_CLASS"] = "bad";
    $arResult["SEO_STATUS"] = "BAD";
}  
elseif($points > 50 && $points <= 85)
{
    $arResult["SEO_CLASS"] = "notbad";
    $arResult["SEO_STATUS"] = "NOTBAD";
} 
elseif($points > 85)
{
    $arResult["SEO_CLASS"] = "good";
    $arResult["SEO_STATUS"] = "GOOD";
}     
?>
<script type="text/javascript">

$(document).ready(
    function()
    {
        $("a.phoenix-sets-list-item.seo span.status-seo").addClass("seo-<?=$arResult["SEO_CLASS"]?>");
    }
);

</script>