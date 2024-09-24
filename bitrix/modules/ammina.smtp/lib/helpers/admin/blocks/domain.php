<?php

namespace Bitrix\Ammina\SMTP\Helpers\Admin\Blocks;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Domain
{
	public static function getEdit($arItem)
	{
		$result = '
			<table border="0" cellspacing="0" cellpadding="0" width="100%" class="adm-detail-content-table edit-table">
				<tbody>
					<tr>
						<td class="adm-detail-content-cell-l" width="40%">' . Loc::getMessage("AMMINA_SMTP_FIELD_DOMAIN") . ':</td>
						<td class="adm-detail-content-cell-r">
							<input type="text" class="adm-bus-input" name="FIELDS[DOMAIN]" id="FIELD_DOMAIN" value="' . htmlspecialcharsbx($arItem['DOMAIN']) . '" />
						</td>
					</tr>
				</tbody>
			</table>';
		return $result;
	}
}