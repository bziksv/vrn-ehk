<?php

namespace Bitrix\Ammina\SMTP\Helpers\Admin\Blocks;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\UserTable;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Dkim
{
	public static function getEdit($arItem)
	{
		$result = '
			<table border="0" cellspacing="0" cellpadding="0" width="100%" class="adm-detail-content-table edit-table">
				<tbody>
					<tr>
						<td class="adm-detail-content-cell-l" width="40%">' . Loc::getMessage("AMMINA_SMTP_FIELD_DKIM_REGENERATE") . ':</td>
						<td class="adm-detail-content-cell-r">
							<input type="checkbox" class="adm-bus-input" name="FIELDS[DKIM_REGENERATE]" id="FIELD_DKIM_REGENERATE" value="Y" />
						</td>
					</tr>
					<tr>
						<td class="adm-detail-content-cell-l">' . Loc::getMessage("AMMINA_SMTP_FIELD_DKIM_PRIVATE") . ':</td>
						<td class="adm-detail-content-cell-r">
							<textarea class="adm-bus-textarea" name="FIELDS[DKIM_PRIVATE]" id="FIELD_DKIM_PRIVATE">' . htmlspecialcharsbx($arItem['DKIM_PRIVATE']) . '</textarea>
						</td>
					</tr>
					<tr>
						<td class="adm-detail-content-cell-l">' . Loc::getMessage("AMMINA_SMTP_FIELD_DKIM_PUBLIC") . ':</td>
						<td class="adm-detail-content-cell-r">
							<textarea class="adm-bus-textarea" name="FIELDS[DKIM_PUBLIC]" id="FIELD_DKIM_PUBLIC">' . htmlspecialcharsbx($arItem['DKIM_PUBLIC']) . '</textarea>
						</td>
					</tr>
					<tr>
						<td class="adm-detail-content-cell-l">' . Loc::getMessage("AMMINA_SMTP_FIELD_DKIM_SELECTOR") . ':</td>
						<td class="adm-detail-content-cell-r">
							<input type="text" class="adm-bus-input" name="FIELDS[DKIM_SELECTOR]" id="FIELD_DKIM_SELECTOR" value="' . htmlspecialcharsbx($arItem['DKIM_SELECTOR']) . '" />
						</td>
					</tr>
					<tr>
						<td class="adm-detail-content-cell-l">' . Loc::getMessage("AMMINA_SMTP_FIELD_DKIM_PASSPHRASE") . ':</td>
						<td class="adm-detail-content-cell-r">
							<input type="text" class="adm-bus-input" name="FIELDS[DKIM_PASSPHRASE]" id="FIELD_DKIM_PASSPHRASE" value="' . htmlspecialcharsbx($arItem['DKIM_PASSPHRASE']) . '" />
						</td>
					</tr>
				</tbody>
			</table>';
		return $result;
	}
}