<?php
define('STOP_STATISTICS', true);
define('NO_AGENT_CHECK', true);
define('NOT_CHECK_PERMISSIONS', true);

use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Wbs24\Ozonexport;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (!Loader::includeModule('wbs24.ozonexport')) return;

$request = Application::getInstance()->getContext()->getRequest();
$action = $request->getQuery("ACTION");
$profileId = $request->getQuery("PROFILE_ID");

if ($action == 'clean' && $profileId) {
    global $USER;

    if (!$USER->IsAdmin()) return;
    $ozon = new Ozonexport();
    $param = [
        'offersLogOn' => true,
        'profileId' => $profileId,
    ];
    $ozonOffersLog = $ozon->getOffersLogObject($param);
    $ozonOffersLog->clearOffersLog();
    echo 'success';
}

if ($action == 'getManualCallWarning') {
    Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/wbs24.ozonexport/load/ozon_setup.php');
    echo "<div class='adm-info-message-wrap' align='left'><div class='adm-info-message'>"
        .Loc::getMessage("WBS24.OZONEXPORT.MANUAL_CALL_NOTE")
    ."</div></div>";
}
