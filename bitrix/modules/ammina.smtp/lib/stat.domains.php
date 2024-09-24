<?

namespace Ammina\SMTP;

use Bitrix\Main\Entity\DataManager;

class StatDomainsTable extends DataManager
{
	public static function getTableName()
	{
		return 'am_smtp_stat_domains';
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
			'SEND_DATE' => array(
				'data_type' => 'date'
			),
			'HOUR' => array(
				'data_type' => 'integer',
			),
			'MINUTE' => array(
				'data_type' => 'integer',
			),
			'CNT_SEND' => array(
				'data_type' => 'integer'
			),
			'CNT_ERROR' => array(
				'data_type' => 'integer'
			)
		);

		return $fieldsMap;
	}
}