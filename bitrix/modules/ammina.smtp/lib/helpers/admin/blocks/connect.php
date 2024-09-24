<?php

namespace Bitrix\Ammina\SMTP\Helpers\Admin\Blocks;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\UserTable;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Connect
{

	public static function getEdit($arItem)
	{
		$strSelectSecureType = '';
		$arSecureTypes = array(
			"N" => Loc::getMessage("AMMINA_SMTP_FIELD_SECURE_TYPE_N"),
			"S" => Loc::getMessage("AMMINA_SMTP_FIELD_SECURE_TYPE_S"),
			"T" => Loc::getMessage("AMMINA_SMTP_FIELD_SECURE_TYPE_T"),
		);
		foreach ($arSecureTypes as $k => $v) {
			$strSelectSecureType .= '<option value="' . $k . '"' . ($arItem['SECURE_TYPE'] == $k ? ' selected="selected"' : '') . '>' . htmlspecialcharsbx($v) . '</option>';
		}
		$result = '
			<table border="0" cellspacing="0" cellpadding="0" width="100%" class="adm-detail-content-table edit-table">
				<tbody>
					<tr>
						<td class="adm-detail-content-cell-l" width="40%">' . Loc::getMessage("AMMINA_SMTP_FIELD_SMTP_HOST") . ':</td>
						<td class="adm-detail-content-cell-r">
							<input type="text" class="adm-bus-input" name="FIELDS[SMTP_HOST]" id="FIELD_SMTP_HOST" value="' . htmlspecialcharsbx($arItem['SMTP_HOST']) . '" />
						</td>
					</tr>
					<tr>
						<td class="adm-detail-content-cell-l">' . Loc::getMessage("AMMINA_SMTP_FIELD_SMTP_PORT") . ':</td>
						<td class="adm-detail-content-cell-r">
							<input type="text" class="adm-bus-input" name="FIELDS[SMTP_PORT]" id="FIELD_SMTP_PORT" value="' . htmlspecialcharsbx($arItem['SMTP_PORT']) . '" />
						</td>
					</tr>
					<tr>
						<td class="adm-detail-content-cell-l">' . Loc::getMessage("AMMINA_SMTP_FIELD_SECURE_TYPE") . ':</td>
						<td class="adm-detail-content-cell-r">
							<select class="adm-bus-select" name="FIELDS[SECURE_TYPE]" id="FIELD_SECURE_TYPE">' . $strSelectSecureType . '</select>
						</td>
					</tr>
					<tr>
						<td class="adm-detail-content-cell-l">' . Loc::getMessage("AMMINA_SMTP_FIELD_ACCOUNT_LOGIN") . ':</td>
						<td class="adm-detail-content-cell-r">
							<input type="text" class="adm-bus-input" name="FIELDS[ACCOUNT_LOGIN]" id="FIELD_ACCOUNT_LOGIN" value="' . htmlspecialcharsbx($arItem['ACCOUNT_LOGIN']) . '" />
						</td>
					</tr>
					<tr>
						<td class="adm-detail-content-cell-l">' . Loc::getMessage("AMMINA_SMTP_FIELD_ACCOUNT_PASSWORD") . ':</td>
						<td class="adm-detail-content-cell-r">
							<input type="text" class="adm-bus-input" name="FIELDS[ACCOUNT_PASSWORD]" id="FIELD_ACCOUNT_PASSWORD" value="' . (amsmtp_strlen($arItem['ACCOUNT_PASSWORD']) > 0 ? (\COption::GetOptionString("ammina.smtp", "not_hide_password", "N") === "Y" ? $arItem['ACCOUNT_PASSWORD'] : '*****') : '') . '" />
						</td>
					</tr>
				</tbody>
			</table>';
		return $result;
	}
}