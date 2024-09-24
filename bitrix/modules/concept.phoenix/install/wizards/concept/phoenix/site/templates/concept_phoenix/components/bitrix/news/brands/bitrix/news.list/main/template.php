<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if(!empty($arResult["ITEMS"])):?>
	<div class="brands-list">
        <div class="row">
            <?foreach($arResult["ITEMS"] as $arItem):?>
                <div class="col-lg-3 col-md-4 col-6">
                    
                    <div class="item row no-gutters align-items-center">
                        <div class="col-12">
                            
                            <img src="<?=$arItem["PREVIEW_PICTURE_SRC"]?>" alt="" class="d-block mx-auto img-fluid">
                             
                        </div>
                        <?CPhoenix::admin_setting($arItem, false)?>
                        <a class = "d-block p-abs-full-area" href="<?=$arItem['DETAIL_PAGE_URL']?>"></a>
                    </div>

                </div>
            <?endforeach;?>
        </div>
    </div>

    <?=$arResult["NAV_STRING"]?>
<?endif?>
