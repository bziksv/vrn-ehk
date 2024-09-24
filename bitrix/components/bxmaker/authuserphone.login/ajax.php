<?
    /** @global \CMain $APPLICATION */

    define("STOP_STATISTICS", true);
    define("NO_KEEP_STATISTIC", "Y");
    define("NO_AGENT_STATISTIC","Y");
    define("DisableEventsCheck", true);
    // define('BX_SECURITY_SESSION_READONLY', true);
    define("PUBLIC_AJAX_MODE", true);


    $siteId = isset($_REQUEST['siteId']) && is_string($_REQUEST['siteId']) ? $_REQUEST['siteId'] : '';
    $siteId = substr(preg_replace('/[^a-z0-9_]/i', '', $siteId), 0, 2);
    if (!empty($siteId) && is_string($siteId)) {
        define('SITE_ID', $siteId);
    }

    require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

    $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
    $request->addFilter(new \Bitrix\Main\Web\PostDecodeFilter);

    $signer = new \Bitrix\Main\Security\Sign\Signer;

    try {
        $template = $signer->unsign($request->get('template'), 'bxmaker.authuserphone.login');
        $paramString = $signer->unsign($request->get('parameters'), 'bxmaker.authuserphone.login');
    } catch (\Bitrix\Main\Security\Sign\BadSignatureException $e) {
        die();
    }

    $parameters = unserialize(base64_decode($paramString));
    $parameters['IS_AJAX'] = 'Y';

    $APPLICATION->IncludeComponent(
        'bxmaker:authuserphone.login',
        $template,
        $parameters,
        false
    );

    require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');
