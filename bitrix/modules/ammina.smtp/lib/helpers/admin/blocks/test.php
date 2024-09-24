<?php

namespace Bitrix\Ammina\SMTP\Helpers\Admin\Blocks;

use Ammina\SMTP\AccountsTable;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Test
{
	public static function getEdit($arItem)
	{
		$rAllAccounts = AccountsTable::getList(
			array(
				"order" => array("EMAIL" => "ASC")
			)
		);
		$strSelect = "";
		while ($arAllAccount = $rAllAccounts->fetch()) {
			$strSelect .= '<option value="' . $arAllAccount['ID'] . '"' . ($arItem['ACCOUNT'] == $arAllAccount['ID'] ? ' selected="selected"' : '') . ($arAllAccount['ACTIVE'] !== "Y" ? ' disabled="disabled"' : '') . '>' . htmlspecialchars($arAllAccount['EMAIL']) . '</option>';
		}
		$result = '
			<table border="0" cellspacing="0" cellpadding="0" width="100%" class="adm-detail-content-table edit-table">
				<tbody>
					<tr>
						<td class="adm-detail-content-cell-l" width="40%">' . Loc::getMessage("AMMINA_SMTP_FIELD_ACCOUNT") . ':</td>
						<td class="adm-detail-content-cell-r">
							<select class="adm-bus-select" name="FIELDS[ACCOUNT]" id="FIELD_ACCOUNT">
								<option value="">' . Loc::getMessage("AMMINA_SMTP_FIELD_ACCOUNT_SELECT") . '</option>			
								' . $strSelect . '				
							</select>						
						</td>
					</tr>
					<tr>
						<td class="adm-detail-content-cell-l">' . Loc::getMessage("AMMINA_SMTP_FIELD_TO") . ':</td>
						<td class="adm-detail-content-cell-r">
							<input type="text" class="adm-bus-input" name="FIELDS[TO]" id="FIELD_TO" value="' . htmlspecialcharsbx($arItem['TO']) . '" />						
						</td>
					</tr>
				</tbody>
			</table>';
		return $result;
	}
}

