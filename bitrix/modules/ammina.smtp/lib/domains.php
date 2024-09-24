<?

namespace Ammina\SMTP;

use Bitrix\Main\Entity\DataManager;

class DomainsTable extends DataManager
{
	public static function getTableName()
	{
		return 'am_smtp_domains';
	}

	public static function getMap()
	{
		$fieldsMap = array(
			'ID' => array(
				'data_type' => 'integer',
				'primary' => true,
				'autocomplete' => true,
			),
			'DOMAIN' => array(
				'data_type' => 'string',
			),
			'DKIM_PRIVATE' => array(
				'data_type' => 'text',
			),
			'DKIM_PUBLIC' => array(
				'data_type' => 'text',
			),
			'DKIM_SELECTOR' => array(
				'data_type' => 'string',
			),
			'DKIM_PASSPHRASE' => array(
				'data_type' => 'string',
			),
			'LIMIT_MINUTE' => array(
				'data_type' => 'integer',
			),
			'LIMIT_HOUR' => array(
				'data_type' => 'integer',
			),
			'LIMIT_DAY' => array(
				'data_type' => 'integer',
			)
		);

		return $fieldsMap;
	}

	public static function getDomainInfo($domainId)
	{
		static $arCacheDomain = array();
		if (!isset($arCacheDomain[$domainId])) {
			$arDomain = DomainsTable::getList(array(
				"filter" => array("ID" => $domainId)
			))->fetch();
			$arCacheDomain[$domainId] = $arDomain;
		}
		return isset($arCacheDomain[$domainId]) ? $arCacheDomain[$domainId] : null;
	}
}