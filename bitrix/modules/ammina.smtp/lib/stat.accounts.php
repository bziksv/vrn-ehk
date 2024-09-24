<?

namespace Ammina\SMTP;

use Bitrix\Main\Entity\DataManager;

class StatAccountsTable extends DataManager
{
	public static function getTableName()
	{
		return 'am_smtp_stat_accounts';
	}

	public static function getMap()
	{
		$fieldsMap = array(
			'ID' => array(
				'data_type' => 'integer',
				'primary' => true,
				'autocomplete' => true,
			),
			'ACCOUNT_ID' => array(
				'data_type' => 'integer',
			),
			'ACCOUNT' => array(
				'data_type' => '\Ammina\SMTP\Accounts',
				'reference' => array('=this.ACCOUNT_ID' => 'ref.ID'),
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