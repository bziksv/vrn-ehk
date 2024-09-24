<?
    if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
        die();

    $CPN = 'bxmaker.authuserphone.edit';


    $oManager = \Bxmaker\AuthUserPhone\Manager::getInstance();

    $this->setFrameMode(true);

    $rand = $arParams['RAND_STRING'];

?>

    <div class="c-bxmaker-authuserphone_edit-default-box" id="c-bxmaker-authuserphone_edit-default-box" data-rand="<?= $rand; ?>">

        <?
            if ($oManager->isExpired()): ?>
                <p style="color:red; padding:0;margin:0 ;"><?= GetMessage($CPN . 'DEMO_EXPIRED'); ?></p>
            <? endif; ?>

        <div class="cbaup_title"><?= GetMessage($CPN . 'TITLE'); ?></div>

        <?
            $frame = $this->createFrame()->begin('');
            $frame->setAnimation(true);
        ?>

        <? if ($arResult['USER_IS_AUTHORIZED'] == 'Y'): ?>
            <div class="msg ">

            </div>

            <div class="cbaup_row cur_phone_info">
                <?
                    if (is_null($arResult['PHONE']) || strlen(trim($arResult['PHONE'])) <= 0) {
                        echo GetMessage($CPN . 'PHONE_NOT_SET');
                    } else {
                        echo GetMessage($CPN . 'PHONE_INFO', array( 'PHONE' => $arResult['PHONE'] ));
                    }
                ?>
            </div>


        <? else: ?>
            <div class="msg error">
                <?= GetMessage($CPN . 'NEED_AUTH'); ?>
            </div>
        <? endif; ?>


        <script type="text/javascript" class="bxmaker-authuserphone-jsdata">
            <?

            // component parameters
            $signer = new \Bitrix\Main\Security\Sign\Signer;
            $signedParameters = $signer->sign(base64_encode(serialize($arResult['_ORIGINAL_PARAMS'])), 'bxmaker.authuserphone.edit');
            $signedTemplate = $signer->sign($arResult['TEMPLATE'], 'bxmaker.authuserphone.edit');
            ?>

            window.BxmakerAuthUserPhoneEditData = window.BxmakerAuthUserPhoneEditData || {};
            window.BxmakerAuthUserPhoneEditData["<?=$rand;?>"] = <?= Bitrix\Main\Web\Json::encode(array(
                'parameters' => $signedParameters,
                'template'   => $signedTemplate,
                'siteId'     => SITE_ID,
                'ajaxUrl'    => $this->getComponent()->getPath() . '/ajax.php',
                'rand' => $rand,

                'messages' => array(
                    'UPDATE_CAPTCHA_IMAGE'  => GetMessage($CPN . 'UPDATE_CAPTCHA_IMAGE'),
                    'INPUT_CAPTHCA'         => GetMessage($CPN . 'INPUT_CAPTHCA'),
                    'BTN_SEND_CODE'         => GetMessage($CPN . 'BTN_SEND_CODE'),
                    'BTN_SEND_CODE_TIMEOUT'        => GetMessage($CPN . 'BTN_SEND_CODE_TIMEOUT'),
                    'PHONE_INFO_TEMPLATE' => GetMessage($CPN . 'PHONE_INFO')
                ),

            ));?>;
        </script>

        <?
            $frame->end();
        ?>


        <div class="cbaup_row">
            <input type="text" name="phone" class="phone" placeholder="<?= GetMessage($CPN . 'INPUT_PHONE'); ?>"/>
        </div>
        <div class="cbaup_row">
            <input type="text" name="code" class="password" placeholder="<?= GetMessage($CPN . 'INPUT_CODE'); ?>"/>
        </div>

        <div class="cbaup_row captcha">
            <?
                /*
                <input type="hidden" name="captcha_sid" value="0b853532ea27dba6a71666bb89ab6760"/>
                <img src="/bitrix/tools/captcha.php?captcha_sid=0b853532ea27dba6a71666bb89ab6760" title="<?=GetMessage('UPDATE_CAPTCHA_IMAGE');?>" alt=""/>
                <span class="btn_captcha_reload" title="<?=GetMessage('UPDATE_CAPTCHA_IMAGE');?>"></span>
                <input type="text" name="captcha_word" class="captcha_word" placeholder="<?=GetMessage('INPUT_CAPTHCA');?>"/>
                */
            ?>
        </div>


        <div class="cbaup_row ">
            <span class="cbaup_btn_link"><?= GetMessage($CPN . 'BTN_SEND_CODE'); ?></span>
        </div>


        <div class="cbaup_row btn_box">
            <div class="cbaup_btn "><?= GetMessage($CPN . 'BTN_SAVE'); ?></div>
        </div>

    </div>

<?
