<?
    if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
        die();
    $this->setFrameMode(true);

    /**
     * @var array $arParams
     * @var \CMain $APPLICATION
     * @var \CBitrixComponent $component
     */

    if (\Bitrix\Main\Loader::includeModule('bxmaker.authuserphone')) {

        $oManager = \Bxmaker\AuthUserPhone\Manager::getInstance();

        //если модуль для текущего сайта включен
        if ($oManager->isEnabled()) {
            $APPLICATION->IncludeComponent($oManager->getDefaultComponent(), '.default', array(), $component);
        }
        //иначе стандартный компонент
        else
        {
            $APPLICATION->IncludeComponent('bitrix:system.auth.form', '.default', $arParams, $component);
        }
    }
?>