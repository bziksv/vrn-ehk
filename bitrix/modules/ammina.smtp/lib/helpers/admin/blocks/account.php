<?php

namespace Bitrix\Ammina\SMTP\Helpers\Admin\Blocks;

use Ammina\SMTP\DomainsTable;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\UserTable;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Account
{
	public static function getEdit($arItem)
	{
		$strSelectDomainId = '';
		$rDomains = DomainsTable::getList(array(
			"order" => array("DOMAIN" => "ASC"),
			"select" => array("ID", "DOMAIN"),
		));
		while ($arDomain = $rDomains->fetch()) {
			$strSelectDomainId .= '<option value="' . $arDomain['ID'] . '"' . ($arItem['DOMAIN_ID'] == $arDomain['ID'] ? ' selected="selected"' : '') . '>' . htmlspecialcharsbx($arDomain['DOMAIN']) . '</option>';
		}
		$result = '
			<table border="0" cellspacing="0" cellpadding="0" width="100%" class="adm-detail-content-table edit-table">
				<tbody>
					<tr>
						<td class="adm-detail-content-cell-l fwb" width="40%">' . Loc::getMessage("AMMINA_SMTP_FIELD_DOMAIN_ID") . ':</td>
						<td class="adm-detail-content-cell-r">
							<select class="adm-bus-select" name="FIELDS[DOMAIN_ID]" id="FIELD_DOMAIN_ID">' . $strSelectDomainId . '</select>
						</td>
					</tr>
					<tr>
						<td class="adm-detail-content-cell-l">' . Loc::getMessage("AMMINA_SMTP_FIELD_IS_DEFAULT_DOMAIN") . ':</td>
						<td class="adm-detail-content-cell-r">
							<input type="hidden" name="FIELDS[IS_DEFAULT_DOMAIN]" value="N"/>
							<input type="checkbox" class="adm-bus-input" name="FIELDS[IS_DEFAULT_DOMAIN]" id="FIELD_IS_DEFAULT_DOMAIN" value="Y"' . ($arItem['IS_DEFAULT_DOMAIN'] === "Y" ? ' checked="checked"' : '') . ' />
						</td>
					</tr>
					<tr>
						<td class="adm-detail-content-cell-l">' . Loc::getMessage("AMMINA_SMTP_FIELD_ACTIVE") . ':</td>
						<td class="adm-detail-content-cell-r">
							<input type="hidden" name="FIELDS[ACTIVE]" value="N"/>
							<input type="checkbox" class="adm-bus-input" name="FIELDS[ACTIVE]" id="FIELD_ACTIVE" value="Y"' . ($arItem['ACTIVE'] === "Y" ? ' checked="checked"' : '') . ' />
						</td>
					</tr>
					<tr>
						<td class="adm-detail-content-cell-l">' . Loc::getMessage("AMMINA_SMTP_FIELD_IS_DEFAULT") . ':</td>
						<td class="adm-detail-content-cell-r">
							<input type="hidden" name="FIELDS[IS_DEFAULT]" value="N"/>
							<input type="checkbox" class="adm-bus-input" name="FIELDS[IS_DEFAULT]" id="FIELD_IS_DEFAULT" value="Y"' . ($arItem['IS_DEFAULT'] === "Y" ? ' checked="checked"' : '') . ' />
						</td>
					</tr>
					<tr>
						<td class="adm-detail-content-cell-l">' . Loc::getMessage("AMMINA_SMTP_FIELD_EMAIL") . ':</td>
						<td class="adm-detail-content-cell-r">
							<input type="text" class="adm-bus-input" name="FIELDS[EMAIL]" id="FIELD_EMAIL" value="' . htmlspecialcharsbx($arItem['EMAIL']) . '" />
						</td>
					</tr>
					<tr>
						<td class="adm-detail-content-cell-l">' . Loc::getMessage("AMMINA_SMTP_FIELD_SENDER_NAME") . ':</td>
						<td class="adm-detail-content-cell-r">
							<input type="text" class="adm-bus-input" name="FIELDS[SENDER_NAME]" id="FIELD_SENDER_NAME" value="' . htmlspecialcharsbx($arItem['SENDER_NAME']) . '" />
						</td>
					</tr>
					<tr>
						<td class="adm-detail-content-cell-l">' . Loc::getMessage("AMMINA_SMTP_FIELD_IS_IMPORT") . ':</td>
						<td class="adm-detail-content-cell-r">
							<input type="hidden" name="FIELDS[IS_IMPORT]" value="N"/>
							<input type="checkbox" class="adm-bus-input" name="FIELDS[IS_IMPORT]" id="FIELD_IS_IMPORT" value="Y"' . ($arItem['IS_IMPORT'] === "Y" ? ' checked="checked"' : '') . ' />
						</td>
					</tr>
				</tbody>
			</table>';
		return $result;
	}
}