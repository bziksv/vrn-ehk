<?

namespace Ammina\SMTP;

use Bitrix\Main\Entity\DataManager;

class QueueTable extends DataManager
{
	public static function getTableName()
	{
		return 'am_smtp_queue';
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
			'STATUS' => array(
				'data_type' => 'enum',
				'values' => array('N', 'S', 'E', 'P'),
			),
			'DATE_INSERT' => array(
				'data_type' => 'datetime',
			),
			'DATE_SEND' => array(
				'data_type' => 'datetime',
			),
			'FIELD_TO' => array(
				'data_type' => 'string',
			),
			'FIELD_SUBJECT' => array(
				'data_type' => 'string',
			),
			'MAIL_DATA' => array(
				'data_type' => 'string',
				'serialized' => true
			),
			'LOG_DATA' => array(
				'data_type' => 'string'
			)
		);

		return $fieldsMap;
	}
}
