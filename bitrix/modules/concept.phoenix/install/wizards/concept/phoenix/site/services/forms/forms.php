<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

global $DB, $DBType, $APPLICATION;

function CONCEPT_addEV($arEventFields=array()) {
	global $DB;
	$EventTypeID = 0;
	$et = new CEventType;
	$EventTypeID = $et->Add($arEventFields);
	return $EventTypeID;
}


$arData = array(
	'PHOENIX_USER_INFO',
	'PHOENIX_FOR_USER',
	'PHOENIX_ONECLICK_SEND',
	'PHOENIX_SEND_REG_SUCCESS',
	'PHOENIX_USER_PASS_REQUEST',
	'PHOENIX_CHANGE_PASSWORD_SUCCESS',
	'PHOENIX_NEW_REVIEW_PRODUCT',
	'PHOENIX_INFO_ORDER_ITEMS_FOR_USER_AFTER_PAYED',
	'PHOENIX_NEW_COMMENTS'
);

if(is_array($arData) && count($arData)>0) 
{

	$ev = new CEventMessage;

	foreach($arData as $EVENT_TYPE) {
		$EventTypeID = 0;
		$arEventFields = array(
			'LID'           => 'ru',
			'EVENT_NAME'    => $EVENT_TYPE."_".WIZARD_SITE_ID,
			'NAME'          => GetMessage('EVENT_NAME.'.$EVENT_TYPE)." (".WIZARD_SITE_ID.")",
			'DESCRIPTION'   => GetMessage('EVENT_DESCRIPTION.'.$EVENT_TYPE),
		);
		$EventTypeID = CONCEPT_addEV($arEventFields);
		if($EventTypeID>0) {
			$arTemplate = array(
				'ACTIVE' 		=> 'Y',
				'EVENT_NAME' 	=> $EVENT_TYPE."_".WIZARD_SITE_ID,
				'LID'			=> WIZARD_SITE_ID,
				'EMAIL_FROM'	=> '#EMAIL_FROM#',
				'EMAIL_TO'		=> '#EMAIL_TO#',
				'BCC'			=> '',
				'REPLY_TO'      => '#EMAIL#',
				'SUBJECT'		=> GetMessage('TEMPLATE_SUBJECT.'.$EVENT_TYPE),
				'BODY_TYPE'		=> 'html',
				'MESSAGE'		=> GetMessage('TEMPLATE_MESSAGE.'.$EVENT_TYPE),
			);
			$EventTemplateID = $ev->Add($arTemplate);
		}
	}

}