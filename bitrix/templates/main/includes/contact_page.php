

<div class="info_col">
        <div class="ttitle">Регионы продаж</div>
        <? if(CModule::IncludeModule("iblock")):?>
            <div class="info_content">
                <div id="tabs">
                    <ul>
                        <li><a href="#tabs-1">Филиал 1</a></li>
                        <li><a href="#tabs-2">Филиал 2</a></li>
                        <li><a href="#tabs-3">Филиал 3</a></li>
                    </ul>
                    <div id="tabs-1">
                        <?
                        $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH . "/includes/tabs/branch_1.php", Array(), Array(
                            "MODE"      => "html",
                            "NAME"      => "Редактирование включаемой области раздела",
                            "TEMPLATE"  => ""
                        ));
                        ?>
                    </div>
                    <div id="tabs-2">
                        <?
                        $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH . "/includes/tabs/branch_2.php", Array(), Array(
                            "MODE"      => "html",
                            "NAME"      => "Редактирование включаемой области раздела",
                            "TEMPLATE"  => ""
                        ));
                        ?>
                    </div>
                    <div id="tabs-3">
                        <?
                        $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH . "/includes/tabs/branch_3.php", Array(), Array(
                            "MODE"      => "html",
                            "NAME"      => "Редактирование включаемой области раздела",
                            "TEMPLATE"  => ""
                        ));
                        ?>
                    </div>
                </div>
            </div>
        <? endif; ?>

</div>

<div class="feedback_col">
    <div class="title">Обратная связь</div>
    <form action="auto_load" class="callback_form" method="POST"></form>
</div>

<div class="clear"></div>

