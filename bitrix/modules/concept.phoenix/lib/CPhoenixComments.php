<?php
namespace Bitrix\CPhoenixComments;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
 
class CPhoenixCommentsTable extends Main\Entity\DataManager
{

	public static function getTableName()
	{
        $siteID = isset($_REQUEST['site_id']) ? $_REQUEST['site_id'] : '';

        if($siteID == '')
        	$siteID = SITE_ID;


        if(empty($siteID))
        	return false;
        
		return 'phoenix_comments_'.$siteID;
	}

	public static function getMap()
	{
       
	   return array(
            'ID' => array(
                'data_type' => 'integer',
				'primary' => true,
				'title' => Loc::getMessage('CPHOENIX_COMMENTS_LIST_ID_TITLE')
			),

			'ACTIVE' =>  array(
                'data_type' => 'string',
				'title' => Loc::getMessage('CPHOENIX_COMMENTS_LIST_ACTIVE_TITLE')
			),

			'DATE' => array(
                'data_type' => 'datetime',
				'title' => Loc::getMessage('CPHOENIX_COMMENTS_LIST_DATE_TITLE')
			),

			'ELEMENT_ID' =>  array(
                'data_type' => 'integer',
				'title' => Loc::getMessage('CPHOENIX_COMMENTS_LIST_ELEMENT_ID_TITLE')
			),

			'USER_ID' => array(
                'data_type' => 'integer',
				'title' => Loc::getMessage('CPHOENIX_COMMENTS_LIST_USER_ID_TITLE')
			),

			'USER_NAME' =>array(
				'data_type' => 'string',
				'title' => Loc::getMessage('CPHOENIX_COMMENTS_LIST_USER_NAME_TITLE')
			),

			'USER_EMAIL' =>array(
				'data_type' => 'string',
				'title' => Loc::getMessage('CPHOENIX_COMMENTS_LIST_USER_EMAIL_TITLE')
			),

			'TEXT' =>  array(
                'data_type' => 'text',
				'title' => Loc::getMessage('CPHOENIX_COMMENTS_LIST_TEXT_TITLE')
			),

			'RESPONSE' =>  array(
                'data_type' => 'text',
				'title' => Loc::getMessage('CPHOENIX_COMMENTS_LIST_RESPONSE_TITLE')
			),

			
		);

	}
}