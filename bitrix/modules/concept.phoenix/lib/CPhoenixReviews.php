<?php
namespace Bitrix\CPhoenixReviews;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
 
class CPhoenixReviewsTable extends Main\Entity\DataManager
{

	public static function getTableName()
	{
        $siteID = isset($_REQUEST['site_id']) ? $_REQUEST['site_id'] : '';

        if($siteID == '')
        	$siteID = SITE_ID;

        if(empty($siteID))
        	return false;
        
		return 'phoenix_reviews_'.$siteID;
	}

	public static function getMap()
	{
       
	   return array(
            'ID' => array(
                'data_type' => 'integer',
				'primary' => true,
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_LIST_ID_TITLE')
			),

			'ACTIVE' =>  array(
                'data_type' => 'string',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_LIST_ACTIVE_TITLE')
			),

			'DATE' => array(
                'data_type' => 'datetime',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_LIST_DATE_TITLE')
			),

			'PRODUCT_ID' =>  array(
                'data_type' => 'integer',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_LIST_PRODUCT_ID_TITLE')
			),

			'ACCOUNT_NUMBER' =>  array(
                'data_type' => 'string',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_LIST_ACCOUNT_NUMBER_TITLE')
			),

			'USER_ID' => array(
                'data_type' => 'integer',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_LIST_USER_ID_TITLE')
			),

			'USER_NAME' =>array(
				'data_type' => 'string',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_LIST_USER_NAME_TITLE')
			),

			'USER_EMAIL' =>array(
				'data_type' => 'string',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_LIST_USER_EMAIL_TITLE')
			),

			'VOTE' =>array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_LIST_VOTE_TITLE')
			),

			'RECOMMEND' =>  array(
                'data_type' => 'string',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_LIST_RECOMMEND_TITLE')
			),

			'EXPERIENCE' =>  array(
                'data_type' => 'string',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_LIST_EXPERIENCE_TITLE')
			),

			'LIKE_COUNT' =>  array(
                'data_type' => 'integer',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_LIST_LIKE_COUNT_TITLE')
			),

			'IMAGES_ID' =>  array(
                'data_type' => 'text',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_LIST_IMAGES_ID_TITLE')
			),

			'TEXT' =>  array(
                'data_type' => 'text',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_LIST_TEXT_TITLE')
			),

			'POSITIVE' =>  array(
                'data_type' => 'text',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_LIST_POSITIVE_TITLE')
			),

			'NEGATIVE' =>  array(
                'data_type' => 'text',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_LIST_NEGATIVE_TITLE')
			),

			'STORE_RESPONSE' =>  array(
                'data_type' => 'text',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_LIST_STORE_RESPONSE_TITLE')
			),

			
		);

	}
}