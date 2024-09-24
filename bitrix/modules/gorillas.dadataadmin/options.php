<?
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/gorillas.dadataadmin/classes/general/settingsclass.php');
$module_id = CDadataSuggestionsAdminSettings::$module_id;
$POST_RIGHT = $APPLICATION->GetGroupRight('main');

if (!function_exists('htmlspecialcharsbx')) {
    function htmlspecialcharsbx($string, $flags = ENT_COMPAT)
    {
        return htmlspecialchars($string, $flags, (defined('BX_UTF') ? 'UTF-8' : 'ISO-8859-1'));
    }
}


/**
 * @return array ���������� ������ ������
 */
function getSiteList()
{
    $arSites = array();
    $rsSites = CSite::GetList($by = 'sort', $order = 'asc', array());
    while ($arRes = $rsSites->GetNext()) {
        $arSites[] = array('ID' => $arRes['ID'], 'NAME' => $arRes['NAME']);
        break;
    }
    return $arSites;
}

/**
 * @param $module_id �� ������
 * @param $arSite ����
 * @param $optionName �������� �����
 */
function setSimpleOption($module_id,  $optionName)
{
    $nameUpper = strtoupper($optionName);
    $nameLower = strtolower($optionName);
    COption::SetOptionString($module_id, $nameLower, $_POST[$nameUpper], GetMessage('OPT_' . $nameUpper));
}

function displayYesNoOption($module_id,  $optionName, $default)
{
    $nameUpper = strtoupper($optionName);
    $nameLower = strtolower($optionName);
    ?>
    <tr>
        <td align="right" width="300"><label for="<?= $nameUpper  ?>"><?= GetMessage('OPT_' . $nameUpper) ?></label></td>
        <td><? $value = COption::GetOptionString($module_id, $nameLower, ($default == "Y" ? "Y" : "N")); ?>
            <input type="hidden" name="<?= $nameUpper  ?>" value="N">
            <input type="checkbox" name="<?= $nameUpper  ?>" id="<?= $nameUpper  ?>"
                   value="Y"<?= ($value == 'Y' ? ' checked="checked"' : '') ?>>
        </td>
    </tr>
<?
}

function displayStringOption($module_id, $optionName, $default, $size)
{
    $nameUpper = strtoupper($optionName);
    $nameLower = strtolower($optionName);
    ?>
    <tr>
        <td align="right"><label for="<?= $nameUpper  ?>"><?= GetMessage('OPT_' . $nameUpper) ?></label></td>
        <td>
            <input type="text" name="<?= $nameUpper  ?>" size="<?= $size ?>" id="<?= $nameUpper  ?>"
                   value="<?= htmlspecialcharsbx(COption::GetOptionString($module_id, $nameLower, $default)) ?>">
        </td>
    </tr>
<?
}


if ($POST_RIGHT >= 'R'):

		// ��������� �����������
    IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/options.php');
    IncludeModuleLangFile(__FILE__);

    $arAllOptions = array();

		// �������� ������ ������
    $arSites = getSiteList();

		// �������� �������� ������������
    $arSaleProps = CDadataSuggestionsAdminSettings::GetSettingsArray();

    $tabControl = new CAdmintabControl('tabControl', array(
        array('DIV' => 'edit1', 'TAB' => GetMessage('MAIN_TAB_SET'), 'ICON' => ''),
    ));


    if (ToUpper($REQUEST_METHOD) == 'POST' &&
        strlen($Update . $Apply . $RestoreDefaults) > 0 &&
        ($POST_RIGHT == 'W' || $POST_RIGHT == 'X') &&
        check_bitrix_sessid()
    ) {
        if (strlen($RestoreDefaults) > 0) {
            COption::RemoveOption($module_id);
        } else {
            $arMapping=array();
            foreach ($arSites as $arSite) {
                $arMapping=$arMapping+CDadataSuggestionsAdminSettings::GetMappingFromPost($arSite['ID']);
            }

            COption::SetOptionString($module_id, 'mapping', serialize($arMapping), GetMessage('OPT_MAPPING'));
            setSimpleOption($module_id, 'APIKEY');
            setSimpleOption($module_id, 'ENABLED');

        }
        $Update = $Update . $Apply;
        if (strlen($Update) > 0 && strlen($_REQUEST['back_url_settings']) > 0) {
            LocalRedirect($_REQUEST['back_url_settings']);
        } else {
            LocalRedirect($APPLICATION->GetCurPage() . '?mid=' . urlencode($mid) . '&lang=' . urlencode(LANGUAGE_ID) . '&back_url_settings=' . urlencode($_REQUEST['back_url_settings']) . '&' . $tabControl->ActiveTabParam());
        }
    }


    ?>


    <form method="post" action="<? echo $APPLICATION->GetCurPage() ?>?mid=<?= urlencode($mid) ?>&amp;lang=<?= LANGUAGE_ID ?>">
        <?
        $tabControl->Begin();
        $tabControl->BeginNextTab();
        ?>

        <tr>
            <td colspan="2"><br/><?
                $aTabs2 = array();
                foreach ($arSites as $arSite) {
                    $aTabs2[] = Array('DIV' => 'stetab' . $arSite['ID'], 'TAB' => '[' . $arSite['ID'] . '] ' . ($arSite['NAME']), 'TITLE' => '[' . $arSite['ID'] . '] ' . ($arSite['NAME']));
                }

                $tabControl2 = new CAdminViewTabControl('tabControl2', $aTabs2);
                $tabControl2->Begin();

                foreach ($arSites as $arSite) {
                    $tabControl2->BeginNextTab();
                    $arFieldDescription = CDadataSuggestionsAdminSettings::GetFieldNames($arSite['ID']);
                    $arFieldValues = CDadataSuggestionsAdminSettings::GetFieldsFromMapping($arSite['ID'], unserialize(COption::GetOptionString($module_id, 'mapping', "")));
                    ?>
                    <table cellspacing="5" cellpadding="0" border="0" width="100%" align="center">

                        <?displayYesNoOption($module_id,  "ENABLED", "N"); ?>
                        <?displayStringOption($module_id,  "APIKEY", "", 32); ?>

                        <?
                        // ��� ������� ���� �����������
                        foreach ($arSaleProps as $arSaleType)
                            if (in_array($arSite['ID'], $arSaleType['LIDS'])): ?>
                                <tr class="heading">
                                    <td colspan="2"><?= $arSaleType['NAME'] ?></td>
                                </tr>
                                <?
	                            // ��� ������� �������� ����
                                foreach ($arSaleType['PROPERTIES'] as $arSaleProp): ?>
                                    <? if ($arSaleProp['TYPE'] != 'LOCATION') { ?>

                                    <tr>

                                        <? $fieldName = CDadataSuggestionsAdminSettings::GetFieldName($arSite['ID'], $arSaleProp['ID']); ?>
                                        <td><label for="<?= $fieldName ?>">
                                                <?= $arFieldDescription[$fieldName] ?></label></td>
                                        <td>

                                                <select name="<?= $fieldName ?>" id="<?= $fieldName ?>">
                                                    <option value=""><?= GetMessage('OPT_PROPS_NO_USE') ?></option>
                                                    <? foreach (CDadataSuggestionsAdminSettings::GetSuggestionsFields() as $arSugGroup => $arSugNames): ?>
                                                        <optgroup label="<?= GetMessage('GORILLAS_ADMIN_SUGGESTIONS_GROUP_' . $arSugGroup . '_NAME') ?>">
                                                            <? if (!empty($arSugNames)): ?>
                                                                <? foreach ($arSugNames as $sugName => $sugComment): ?>
                                                                    <option
                                                                        value="<?= $arSugGroup ?>_<?= $sugName ?>"<? if ($arSugGroup . '_' . $sugName == $arFieldValues[$fieldName]) { ?> selected="selected"<? } ?>><?= $sugComment ?></option>
                                                                <? endforeach; ?>
                                                            <? else: ?>
                                                                <option value="">&lt;<?= GetMessage('OPT_PROPS_NO') ?>&gt;</option>
                                                            <? endif; ?>
                                                        </optgroup>
                                                    <? endforeach; ?>
                                                </select>

                                        </td>
                                    </tr>
                                    <? } ?>

                                <? endforeach; ?>

                            <? endif;
                        ?>
                    </table>
                <?
                }
                $tabControl2->End();
                ?></td>
        </tr><?

        $tabControl->Buttons();
        ?>
        <input <? if ($POST_RIGHT < 'W') echo 'disabled="disabled"' ?> type="submit" class="adm-btn-save" name="Update"
                                                                       value="<?= GetMessage('MAIN_SAVE') ?>"
                                                                       title="<?= GetMessage('MAIN_OPT_SAVE_TITLE') ?>"/>
        <input <? if ($POST_RIGHT < 'W') echo 'disabled="disabled"' ?> type="submit" name="Apply" value="<?= GetMessage('MAIN_OPT_APPLY') ?>"
                                                                       title="<?= GetMessage('MAIN_OPT_APPLY_TITLE') ?>"/>
        <? if (strlen($_REQUEST["back_url_settings"]) > 0): ?>
            <input <? if ($POST_RIGHT < 'W') echo 'disabled="disabled"' ?> type="button" name="Cancel" value="<?= GetMessage('MAIN_OPT_CANCEL') ?>"
                                                                           title="<?= GetMessage('MAIN_OPT_CANCEL_TITLE') ?>"
                                                                           onclick="window.location='<? echo htmlspecialcharsbx(CUtil::addslashes($_REQUEST['back_url_settings'])) ?>'"/>
            <input type="hidden" name="back_url_settings" value="<?= htmlspecialcharsbx($_REQUEST["back_url_settings"]) ?>"/>
        <? endif ?>
        <input <? if ($POST_RIGHT < 'W') echo 'disabled="disabled"' ?> type="submit" name="RestoreDefaults"
                                                                       title="<? echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS") ?>"
                                                                       onclick="confirm('<? echo AddSlashes(GetMessage('MAIN_HINT_RESTORE_DEFAULTS_WARNING')) ?>')"
                                                                       value="<? echo GetMessage('MAIN_RESTORE_DEFAULTS') ?>"/>
        <?= bitrix_sessid_post(); ?>
        <? $tabControl->End(); ?>
    </form>

<? endif; ?>