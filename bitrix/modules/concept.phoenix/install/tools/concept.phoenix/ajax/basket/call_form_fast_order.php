<?
$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if(CModule::IncludeModule("concept.phoenix"))
{
    $fromBasketPage = ($_POST["fromBasket"]=="Y") ? true : false;

    $product_one = isset($_POST["productItem"]) && !empty($_POST["productItem"]);




    if(!$fromBasketPage && $product_one)
    {
        $nameProduct = htmlspecialchars(strip_tags($_POST["productItem"]["NAME"]));

        if(SITE_CHARSET == "windows-1251")
            $nameProduct = utf8win1251($nameProduct);


        $articleProduct = "";

        if(isset($_POST["productItem"]["ARTICLE"]) && strlen($_POST["productItem"]["ARTICLE"]))
        {
            $articleProduct = htmlspecialchars($_POST["productItem"]["ARTICLE"]);

            if(SITE_CHARSET == "windows-1251")
                $articleProduct = utf8win1251($articleProduct);
            
            $articleProduct = strip_tags($articleProduct);
        }
        


        if(isset($_POST["productItem"]["element_item_offers"]))
        {
            if(!empty($_POST["productItem"]["element_item_offers"]))
            {
                if(SITE_CHARSET == "windows-1251")
                {
                    foreach ($_POST["productItem"]["element_item_offers"] as $k => $arItem)
                    {

                        if(!strlen($arItem["VALUE"]))
                        {
                            unset($_POST["productItem"]["element_item_offers"][$k]);
                            continue;
                        }

                        $_POST["productItem"]["element_item_offers"][$k]["NAME"] = utf8win1251($arItem["NAME"]);
                        $_POST["productItem"]["element_item_offers"][$k]["SKU_NAME"] = utf8win1251($arItem["SKU_NAME"]);
                    }
                }
                else
                {
                    foreach($_POST["productItem"]["element_item_offers"] as $k => $arItem)
                    {
                        if(!strlen($arItem["VALUE"]))
                        {
                            unset($_POST["productItem"]["element_item_offers"][$k]);
                            continue;
                        }

                        $_POST["productItem"]["element_item_offers"][$k]["NAME"] = $arItem["NAME"];
                    }
                } 
            }

            
        }

        if(isset($_POST["productItem"]["PHOTO"]))
            $photo = htmlspecialchars($_POST["productItem"]["PHOTO"]);
    }
    
    $ymWizard = "ym-record-keys";

    global $PHOENIX_TEMPLATE_ARRAY;
    CPhoenix::phoenixOptionsValues($site_id, array(
            "design",
            "politic",
            "catalog",
            "shop"

        ));
    ?>

    <div class="shadow-modal"></div>

    <div class="phoenix-modal form-modal">

        <div class="phoenix-modal-dialog">
            
            <div class="dialog-content">
                <a class="close-modal"></a>

                <div class="form-modal-table">

                    <?if(isset($photo)):?>

                        <div class="form-modal-cell part-more hidden-xs">
                            <img src="<?=$photo?>" alt="" class="d-block mx-auto offer-img-dialog-modal">

                            <?if(!$fromBasketPage && $product_one):?>
                                <div class="offer-text-dialog-modal">
                                    <div class="main_name bold"><?=$nameProduct?></div>

                                    <?if(strlen($articleProduct)):?>

                                        <div class="article italic"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ARTICLE_SHORT"].$articleProduct?></div>

                                    <?endif;?>



                                    <?if(!empty($_POST["productItem"]["element_item_offers"])):?>
                                        <?$i = 0;?>

                                        <?foreach ($_POST["productItem"]["element_item_offers"] as $arItem):?>
                                     
                                            <?if($arItem['NA'] == 'true') continue;?>

                                            <?=($i==0)? "":"<br/>";?>

                                            <span class="first_name"><?=$arItem["SKU_NAME"]?>:&nbsp;</span><span class="second_name italic"><?=$arItem["NAME"]?></span>

                                            <?$i++;?>
                                        <?endforeach;?>

                                    <?endif;?>
                                </div>

                            <?endif;?>
                        </div>

                    <?endif;?>


                    <div class="form-modal-cell part-form">

                        <form id = "form-fast-order" action="/" class="form-fast-order form send dark" method="post" role="form">
                            <?if(!$fromBasketPage && $product_one):?>
                                <input type="hidden" name="productID" value = "<?=htmlspecialchars($_POST["productItem"]["PRODUCT_ID"])?>">
                                <input type="hidden" name="productQuantity" value = "<?=htmlspecialchars($_POST["productItem"]["QUANTITY"])?>">

                                <input type="hidden" name="productName" value = "<?=$nameProduct?>">
                            <?endif;?>

                            <input name="header" type="hidden" value="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FAST_ORDER_FORM_TITLE"]?>">
                            

                            <table class="wrap-act">
                                <tr>
                                    <td>
                                        <div class="col-12 questions active">


                                            <div class="row">

                                                <div class="col-12 title-form main1">
                                                    <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FAST_ORDER_FORM_TITLE"]?>
                                                </div>

                                                <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['FAST_ORDER_FORM_SUBTITLE']["VALUE"])):?>

                                                    <div class="col-12 subtitle-form">
                                                        <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['FAST_ORDER_FORM_SUBTITLE']["VALUE"]?>
                                                    </div>

                                                <?endif;?>


                                                <div class="main-inuts">
                                                    <div class="col-12">
                                                        <div class="input">
                                                            <div class="bg"></div>
                                                            <span class="desc">Name</span>
                                                            <input class='focus-anim input-name' name="name" type="text">
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="input">
                                                            <div class="bg"></div>
                                                            <span class="desc">Email</span>
                                                            <input class='focus-anim input-name' name="email" type="email">
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="input">
                                                            <div class="bg"></div>
                                                            <span class="desc">Phone</span>
                                                            <input class='focus-anim input-name' name="phone" type="tel">
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <?$showBtn = false;?>

                                                <?if(isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PERSON_TYPE"]["CUR_VALUE"]]["VALUE"])):?>

                                                    <?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PERSON_TYPE"]["CUR_VALUE"]]["VALUE"])):?>




                                                        <?foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PERSON_TYPE"]["CUR_VALUE"]]["VALUE"] as $key => $value)
                                                            {
                                                                $curField = array();
                                                                $require = "";



                                                                if($value == "Y")
                                                                {

                                                                    $showBtn = true;
                                                                    
                                                                    $curField = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PERSON_TYPE"]["CUR_VALUE"]]["VALUES"][$key];


                                                                    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS_REQ']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PERSON_TYPE"]["CUR_VALUE"]]["VALUE"][$key] == "Y")
                                                                        $require = "require";

                                                                 

                                                                    ?>

                                                                    <div class="col-12">

                                                                        <?if($curField["PROPS"]["TYPE"] == "TEXT" || $curField["PROPS"]["TYPE"] == "NUMBER"):?>


                                                                            <div class="input <?=($curField["DEFAULT_VALUE"])?'in-focus':'';?>">
                                                                                <div class="bg"></div>
                                                                                <span class="desc"><?=$curField["DESCRIPTION"]?></span>
                                                                                <input 

                                                                                    <?$class = "";?>

                                                                                    name="<?=$curField["PROPS"]["CODE"]?>"


                                                                                    <?if($curField["PROPS"]["IS_EMAIL"] == "Y"):?>

                                                                                        <?$class = "email";?>
                                                                                        type="email"

                                                                                    <?elseif($curField["PROPS"]["IS_PHONE"] == "Y"):?>

                                                                                        <?$class = "phone";?>
                                                                                        type="text"

                                                                                    <?else:?>

                                                                                        type="text"

                                                                                    <?endif;?>
                                                                                    
                                                                                    class='

                                                                                        focus-anim 

                                                                                        <?=$class?>
                                                                                        <?=$require?>
                                                                                        input_<?=$curField["PROPS"]["CODE"]?>
                                                                                        <?=$ymWizard?>
                                                                                    '

                                                                                    <?if($curField["DEFAULT_VALUE"]):?>value="<?=$curField["DEFAULT_VALUE"]?>"<?endif;?>
                                                                                >
                                                                                
                                                                            </div>

                                                                        
                                                                        <?elseif($curField["PROPS"]["TYPE"] == "TEXTAREA"):?>

                                                                            <div class="input input-textarea input_textarea_<?=$curField["PROPS"]["CODE"]?> <?=($curField["DEFAULT_VALUE"])?'in-focus':'';?>">
                                                                                <div class="bg"></div>
                                                                                <span class="desc"><?=$curField["DESCRIPTION"]?></span>

                                                                                <textarea class='focus-anim <?=$require?> <?=$ymWizard?>' name="<?=$curField["PROPS"]["CODE"]?>"><?if($curField["DEFAULT_VALUE"]):?><?=$curField["DEFAULT_VALUE"]?><?endif;?></textarea>
                                                                            </div>

                                                                        <?elseif($curField["PROPS"]["TYPE"] == "DATE"):?>

                                                                            <div class="input date-wrap <?=$require?> <?=($curField["DEFAULT_VALUE"])?'in-focus':'';?>">
                                                                                <div class="bg"></div>
                                                                                <span class="desc"><?=$curField["DESCRIPTION"]?></span>
                                                                                <input class="date focus-anim <?=$require?> <?=$ymWizard?> input-date input_<?=$curField["PROPS"]["CODE"]?>" name="<?=$curField["PROPS"]["CODE"]?>" type="text" <?if($curField["DEFAULT_VALUE"]):?>value="<?=str_replace('.','/',$curField["DEFAULT_VALUE"])?>"<?endif;?>>
                                                                            </div>


                                                                        <?elseif($curField["PROPS"]["TYPE"] == "CHECKBOX"):?>

                                                                            <ul class="input-checkbox-css">

                                                                                <li>

                                                                                    <label class="input-checkbox-css">

                                                                                        <input class='check-<?=$require?>' name='<?=$curField["PROPS"]["CODE"]?>' type="checkbox" value="<?=htmlspecialcharsEx($curField["VALUE"])?>"><span></span><span class="text"><?=$curField['DESCRIPTION']?></span>

                                                                                    </label>
                                                                                </li>

                                                                            </ul>


                                                                        <?elseif($curField["PROPS"]["TYPE"] == "SELECT"):?>

                                                                            <?if(!empty($curField["VARIANTS"])):?>

                                                                                <div class="name-tit bold"><?=$curField["DESCRIPTION"]?></div>

                                                                                <div class="input">
                                                                    
                                                                                    <div class="form-select select-<?=$require?>">
                                                                                        <div class="ar-down"></div>
                                                                                        
                                                                                        <div class="select-list-choose first"><span class="list-area"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FORM_TEMPL_SELECT"];?></span></div>

                                                                                        <div class="select-list input_<?=$curField["PROPS"]["ID"]?>_<?=$key?>">
                                                                                          
                                                                                            <?foreach($curField["VARIANTS"] as $li):?>
                                                                                                <label>
                                                                                                    <span class="name">
                                                                                                        
                                                                                                        <input class="opinion" type="radio" name='<?=$curField["PROPS"]["CODE"]?>' value="<?=htmlspecialcharsEx($li['VALUE'])?>">
                                                                                                        <span class="text"><?=$li['NAME']?></span>
                                                                                                        
                                                                                                    </span>
                                                                                                </label>
                                                                                            <?endforeach;?>
                                                                                        </div>
                                                                                    </div>

                                                                                </div>

                                                                            <?endif;?>

                                                                        
                                                                        <?elseif($curField["PROPS"]["TYPE"] == "FILE"):?>

                                                                            <div class="load-file">
                                                                                <label class="area-file">

                                                                                    <div class="area-files-name">

                                                                                        <span><?=$curField["DESCRIPTION"]?></span>

                                                                                    </div>

                                                                                <input class="hidden <?=$require?>" name="<?=$curField["PROPS"]["CODE"]?>" type="file">

                                                                                <?if(strlen($require)>0):?><span class="star-req"></span><?endif;?>

                                                                                </label>
                                                                            </div>

                                                                        <?endif;?>


                                                                    </div>


                                                                    <?
                                                                }
                                                                
                                                            }

                                                            unset($curField);
                                                        ?>

                                                    <?endif;?>

                                                <?endif;?>



                                                <?if($showBtn):?>

                                                    <div class="col-12">
                                                        <div class="input-btn">
                                                            <div class="load">
                                                                <div class="xLoader form-preload">
                                                                    <div class="audio-wave">
                                                                        <span></span>
                                                                        <span></span>
                                                                        <span></span>
                                                                        <span></span>
                                                                        <span></span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <button 
                                                                class="

                                                                button-def main-color big active <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['BTN_VIEW']['VALUE']?> btn-submit 

                                                                <?if($fromBasketPage):?>

                                                                    fast-order-basket

                                                                <?else:?>

                                                                    fast-order

                                                                <?endif;?>

                                                                "

                                                                name="form-submit"
                                                                type="button"

                                                                >
                                                                <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FAST_ORDER_FORM_BUTTON"]?>
                                                            </button>
                                                        </div>
                                                    </div>

                                                <?endif;?>


                                            </div>

                                            <?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENT_FORM'])):?>

                                                <div class="wrap-agree">

                                                    <label class="input-checkbox-css">
                                                        <input type="checkbox" class="agreecheck" name="checkboxAgree" value="agree" <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]["POLITIC_CHECKED"]['VALUE']["ACTIVE"] == 'Y'):?> checked<?endif;?>>
                                                        <span></span>   
                                                    </label>    

                                                    <div class="wrap-desc">                                                                    
                                                        <span class="text"><?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]["POLITIC_DESC"]['VALUE'])>0):?><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]["POLITIC_DESC"]['~VALUE']?><?else:?><?=GetMessage('PHOENIX_MODAL_FORM_AGREEMENT')?><?endif;?></span>


                                                        <?$agrCount = count($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENT_FORM']);?>
                                                        <?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENT_FORM'] as $k => $arAgr):?>

                                                            <a class="call-modal callagreement from-modal from-modalform" data-call-modal="agreement<?=$arAgr['ID']?>"><?if(strlen($arAgr['PROPERTIES']['CASE_TEXT']['VALUE'])>0):?><?=$arAgr['PROPERTIES']['CASE_TEXT']['VALUE']?><?else:?><?=$arAgr['NAME']?><?endif;?></a><?if($k+1 != $agrCount):?><span>, </span><?endif;?>

                                                            
                                                        <?endforeach;?>
                                                     
                                                    </div>

                                                </div>
                                            <?endif;?>
                                        </div>
                                        
                                        <div class="col-12 thank"></div>
                                    </td>
                                </tr>
                            </table>

                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>


    <?
}

//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");