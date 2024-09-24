<?

namespace Ammina\SMTP;

use Bitrix\Main\Entity\DataManager;

class AccountsTable extends DataManager
{
	public static function getTableName()
	{
		return 'am_smtp_accounts';
	}

	public static function getMap()
	{
		$fieldsMap = array(
			'ID' => array(
				'data_type' => 'integer',
				'primary' => true,
				'autocomplete' => true,
			),
			'DOMAIN_ID' => array(
				'data_type' => 'integer',
			),
			'DOMAIN' => array(
				'data_type' => '\Ammina\SMTP\Domains',
				'reference' => array('=this.DOMAIN_ID' => 'ref.ID'),
			),
			'IS_DEFAULT_DOMAIN' => array(
				'data_type' => 'enum',
				'values' => array('N', 'Y'),
			),
			'ACTIVE' => array(
				'data_type' => 'enum',
				'values' => array('N', 'Y'),
			),
			'IS_DEFAULT' => array(
				'data_type' => 'enum',
				'values' => array('N', 'Y'),
			),
			'SMTP_HOST' => array(
				'data_type' => 'string',
			),
			'SMTP_PORT' => array(
				'data_type' => 'integer',
			),
			'SECURE_TYPE' => array(
				'data_type' => 'enum',
				'values' => array('N', 'S', 'T'),
			),
			'EMAIL' => array(
				'data_type' => 'string',
			),
			'SENDER_NAME' => array(
				'data_type' => 'string',
			),
			'ACCOUNT_LOGIN' => array(
				'data_type' => 'string',
			),
			'ACCOUNT_PASSWORD' => array(
				'data_type' => 'string',
			),
			'IS_IMPORT' => array(
				'data_type' => 'enum',
				'values' => array('N', 'Y'),
			),
			'LIMIT_MINUTE' => array(
				'data_type' => 'integer',
			),
			'LIMIT_HOUR' => array(
				'data_type' => 'integer',
			),
			'LIMIT_DAY' => array(
				'data_type' => 'integer',
			),
			'LIMIT_DOMAN_IGNORE' => array(
				'data_type' => 'enum',
				'values' => array('N', 'Y'),
			)
		);

		return $fieldsMap;
	}

	/**
	 * Ищем аккаунт с которого отправлять письмо, либо домен для DKIM подписи при локальной отправке
	 * Порядок поиска:
	 * 1. Совпадение аккаунта  с $strFrom
	 * 2. Аккаунт по умолчанию для домена, совпадающего с $strFrom
	 * 3. Аккаунт по умолчанию
	 * Если не найден аккаунт, то ищем домен
	 *
	 * Если не найден подходящий аккаунт, то ищем домен
	 *
	 * @param string $strFrom Адрес From письма
	 *
	 * @return array|false|mixed
	 * @throws \Bitrix\Main\ArgumentException
	 * @throws \Bitrix\Main\ObjectPropertyException
	 * @throws \Bitrix\Main\SystemException
	 */
	static public function getOptimalAccountDomainByFrom($strFrom)
	{
		static $arCacheAccountByFrom = array();
		if (!isset($arCacheAccountByFrom[amsmtp_strtolower($strFrom)])) {
			$arAccount = AccountsTable::getList(array(
				"filter" => array("ACTIVE" => "Y", "EMAIL" => $strFrom),
				"select" => array("*", "DOMAIN_AREA_" => "DOMAIN"),
			))->fetch();
			if (!$arAccount) {
				$arEmail = explode("@", $strFrom);
				$arDomain = DomainsTable::getList(array(
					"filter" => array("DOMAIN" => $arEmail[1]),
				))->fetch();
				if ($arDomain) {
					$arAccount = AccountsTable::getList(array(
						"filter" => array("ACTIVE" => "Y", "IS_DEFAULT_DOMAIN" => "Y", "DOMAIN_ID" => $arDomain['ID']),
						"select" => array("*", "DOMAIN_AREA_" => "DOMAIN"),
					))->fetch();
					if (!$arAccount) {
						$arAccount = AccountsTable::getList(array(
							"filter" => array("ACTIVE" => "Y", "DOMAIN_ID" => $arDomain['ID']),
							"select" => array("*", "DOMAIN_AREA_" => "DOMAIN"),
						))->fetch();
					}
				}
				if (!$arAccount) {
					$arAccount = AccountsTable::getList(array(
						"filter" => array("ACTIVE" => "Y", "IS_DEFAULT" => "Y"),
						"select" => array("*", "DOMAIN_AREA_" => "DOMAIN"),
					))->fetch();
					if (!$arAccount) {
						if ($arDomain) {
							$arAccount = array(
								"IS_DOMAIN" => true,
								"DOMAIN" => $arDomain,
							);
						}
					}
				}
			}
			if ($arAccount) {
				if (!$arAccount['IS_DOMAIN']) {
					$arAccount = array(
						"IS_ACCOUNT" => true,
						"ACCOUNT" => $arAccount,
						"DOMAIN" => DomainsTable::getList(array(
							"filter" => array("ID" => $arAccount['DOMAIN_ID']),
						))->fetch(),
					);
				}
				$arCacheAccountByFrom[amsmtp_strtolower($strFrom)] = $arAccount;
			}
		}
		$arAccount = $arCacheAccountByFrom[amsmtp_strtolower($strFrom)];
		return $arAccount;
	}

	public static function getAccountDomainInfo($accountId)
	{
		static $arCacheAccountDomain = array();
		if (!isset($arCacheAccountDomain[$accountId])) {
			$arAccount = AccountsTable::getList(array(
				"filter" => array("ID" => $accountId)
			))->fetch();
			$arCacheAccountDomain[$accountId] = array(
				"IS_ACCOUNT" => true,
				"ACCOUNT" => $arAccount,
				"DOMAIN" => DomainsTable::getList(array(
					"filter" => array("ID" => $arAccount['DOMAIN_ID']),
				))->fetch(),
			);
		}
		return isset($arCacheAccountDomain[$accountId]) ? $arCacheAccountDomain[$accountId] : null;
	}

	public static function getAccountInfo($accountId)
	{
		static $arCacheAccount = array();
		if (!isset($arCacheAccount[$accountId])) {
			$arCacheAccount[$accountId] = AccountsTable::getList(array(
				"filter" => array("ID" => $accountId)
			))->fetch();
		}
		return isset($arCacheAccount[$accountId]) ? $arCacheAccount[$accountId] : null;
	}
}