<?php
namespace Bitrix\CPhoenixReviewsInfo;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
 
class CPhoenixReviewsInfoTable extends Main\Entity\DataManager
{

	public static function getTableName()
	{
        $siteID = isset($_REQUEST['site_id']) ? $_REQUEST['site_id'] : '';

        if($siteID == '')
        	$siteID = SITE_ID;

        if(empty($siteID))
        	return false;
        
		return 'phoenix_reviews_info_'.$siteID;
	}

	public static function getMap()
	{
       
	   return array(
            'ID' => array(
                'data_type' => 'integer',
				'primary' => true,
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_INFO_ID_TITLE')
			),

			'PRODUCT_ID' => array(
                'data_type' => 'integer',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_INFO_PRODUCT_ID_TITLE')
			),


			'VOTE_SUM' => array(
                'data_type' => 'integer',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_INFO_VOTE_SUM_TITLE')
			),

			'VOTE_COUNT' => array(
                'data_type' => 'integer',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_INFO_VOTE_COUNT_TITLE')
			),

			'RECOMMEND_COUNT_Y' => array(
                'data_type' => 'integer',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_INFO_RECOMMEND_COUNT_Y_TITLE')
			),

			'RECOMMEND_COUNT_N' => array(
                'data_type' => 'integer',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_INFO_RECOMMEND_COUNT_N_TITLE')
			),

			'REVIEWS_COUNT' => array(
                'data_type' => 'integer',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_INFO_REVIEWS_COUNT_TITLE')
			),

			'VOTE_COUNT_1_2' => array(
                'data_type' => 'integer',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_INFO_VOTE_COUNT_1_2_TITLE')
			),

			'VOTE_COUNT_3' => array(
                'data_type' => 'integer',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_INFO_VOTE_COUNT_3_TITLE')
			),

			'VOTE_COUNT_4' => array(
                'data_type' => 'integer',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_INFO_VOTE_COUNT_4_TITLE')
			),

			'VOTE_COUNT_5' => array(
                'data_type' => 'integer',
				'title' => Loc::getMessage('CPHOENIX_REVIEWS_INFO_VOTE_COUNT_5_TITLE')
			),
			
		);

	}
}