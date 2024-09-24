<?php

namespace Bitrix\Ammina\SMTP\Helpers\Admin\Blocks;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\UserTable;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class DomainLimit
{
	public static function getEdit($arItem)
	{
		$result = '
			<table border="0" cellspacing="0" cellpadding="0" width="100%" class="adm-detail-content-table edit-table">
				<tbody>
					<tr>
						<td class="adm-detail-content-cell-l" width="40%">' . Loc::getMessage("AMMINA_SMTP_FIELD_LIMIT_DAY") . ':</td>
						<td class="adm-detail-content-cell-r">
							<input type="text" class="adm-bus-input" name="FIELDS[LIMIT_DAY]" id="FIELD_LIMIT_DAY" value="' . htmlspecialcharsbx($arItem['LIMIT_DAY']) . '" />
						</td>
					</tr>
					<tr>
						<td class="adm-detail-content-cell-l">' . Loc::getMessage("AMMINA_SMTP_FIELD_LIMIT_HOUR") . ':</td>
						<td class="adm-detail-content-cell-r">
							<input type="text" class="adm-bus-input" name="FIELDS[LIMIT_HOUR]" id="FIELD_LIMIT_HOUR" value="' . htmlspecialcharsbx($arItem['LIMIT_HOUR']) . '" />
						</td>
					</tr>
					<tr>
						<td class="adm-detail-content-cell-l">' . Loc::getMessage("AMMINA_SMTP_FIELD_LIMIT_MINUTE") . ':</td>
						<td class="adm-detail-content-cell-r">
							<input type="text" class="adm-bus-input" name="FIELDS[LIMIT_MINUTE]" id="FIELD_LIMIT_MINUTE" value="' . htmlspecialcharsbx($arItem['LIMIT_MINUTE']) . '" />
						</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align:center;"><small>' . Loc::getMessage("AMMINA_SMTP_FIELD_LIMIT_NOTE") . '</small></td>							
					</tr>
				</tbody>
			</table>';
		return $result;
	}
}