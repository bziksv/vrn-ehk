<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

    use Bitrix\Main\Loader;
    use Bitrix\Main\Localization\Loc as Loc;

    \Bitrix\Main\Localization\Loc::loadMessages(__FILE__);


    class BXmakerAuthUserPhoneEditComponent extends CBitrixComponent
    {

        public function onPrepareComponentParams($arParams)
        {
            //для ajax
            $this->arResult['_ORIGINAL_PARAMS'] = $arParams;

            $arParams['RAND_STRING'] = $this->getParamStr($arParams, 'RAND_STRING', $this->randString());
            $arParams['IS_AJAX'] = $this->getParamBool($arParams, 'IS_AJAX', 'N');

            return parent::onPrepareComponentParams($arParams);
        }

        /**
         * Подготовка парамтра int
         *
         * @param     $arParams
         * @param     $name
         * @param int $defaultValue
         *
         * @return int
         */
        private function getParamInt($arParams, $name, $defaultValue = 0)
        {
            return (isset($arParams[ $name ]) && intval($arParams[ $name ]) > 0 ? intval($arParams[ $name ]) : $defaultValue);
        }

        /**
         * Подготовка паартра типа строка
         *
         * @param        $arParams
         * @param        $name
         * @param string $defaultValue
         *
         * @return string
         */
        private function getParamStr($arParams, $name, $defaultValue = '')
        {
            return (isset($arParams[ $name ]) ? $arParams[ $name ] : $defaultValue);
        }

        /**
         * Подготовка параметра типа флаг
         *
         * @param        $arParams
         * @param        $name
         * @param string $defaultValue
         *
         * @return string
         */
        private function getParamBool($arParams, $name, $defaultValue = 'N')
        {
            return (isset($arParams[ $name ]) && in_array($arParams[ $name ], array(
                'N',
                'Y'
            )) > 0 ? $arParams[ $name ] : $defaultValue);
        }


        /**
         * Вернет языкозависимое сообщение
         * @param       $name
         * @param array $arReplace
         *
         * @return string
         */
        public function getMessage($name, $arReplace = array())
        {
            return Loc::getMessage('BXMAKER.AUTHUSERPHONE.EDIT.'.$name, $arReplace);
        }
       
    
        public function executeComponent()
        {
            $this->setFrameMode(true);

            try {
    
                // подключаем модуль
                if (!Loader::includeModule('bxmaker.authuserphone')) {
                    throw new \Bitrix\Main\LoaderException($this->getMessage('MODULE_NOT_INSTALLED'));
                }
    
                $oManager = \Bxmaker\AuthUserPhone\Manager::getInstance();

                $this->arResult['USER_IS_AUTHORIZED'] = 'N';
                if(isset($GLOBALS['USER']) && $GLOBALS['USER']->IsAuthorized())
                {
                    $this->arResult['USER_IS_AUTHORIZED'] = 'Y';
                    $this->arResult['PHONE'] = $oManager->getPhone($GLOBALS['USER']->GetID());
                }

                //обработка ajax запросов --
                $this->ajaxHandler();

                // подклчюаем js
                if (\Bxmaker\AuthUserPhone\Manager::getInstance()->isEnabledComponentJQuery()) {
                    CJSCore::Init();
                    \Bxmaker\AuthUserPhone\Manager::getInstance()->initComponentJQuery();
                }

                $this->arResult['TEMPLATE'] = $this->getTemplateName();

                $this->includeComponentTemplate();

            } catch (Exception $e) {
                ShowError($e->getMessage());
            }

            return parent::executeComponent();
        }

        public function ajaxHandler()
        {
            global $USER;

            // AJAX
            $app = \Bitrix\Main\Application::getInstance();
            $req = $app->getContext()->getRequest();

            //обработка только ajax  запросов
            if (!$req->isAjaxRequest() || $this->arParams['IS_AJAX'] != 'Y') {
                return true;
            }

            $oUser = new CUser();
            $oManager = \Bxmaker\AuthUserPhone\Manager::getInstance();


            $arAnswer = array(
                'response' => array(),
                'error'    => array()
            );


            do {
                
                if (!$req->getPost('method')) {
                    $arAnswer['error'] = array(
                        'msg'  => $this->getMessage('AJAX.NEED_METHOD'),
                        'code' => 'NEED_METHOD',
                        'more' => array()
                    );
                    break;
                }
    
                if (!check_bitrix_sessid()) {
                    $arAnswer['error'] = array(
                        'msg'  => $this->getMessage('AJAX.INVALID_SESSID'),
                        'code' => 'INVALID_SESSID',
                        'more' => array()
                    );
                    break;
                }
    
                if (!$USER->IsAuthorized()) {
                    $arAnswer['error'] = array(
                        'msg'  => $this->getMessage('AJAX.NEED_AUTH'),
                        'code' => 'NEED_AUTH',
                        'more' => array()
                    );
                    break;
                }


                switch ($req->getPost('method')) {
                    case 'setPhone': {


                        if (!$req->getPost('phone')) {
                            $arAnswer['error'] = array(
                                'CODE' => '0',
                                'MSG'  => $this->getMessage('AJAX.METHOD_AUTH.EMPTY_PHONE'),
                                'MORE' => ''
                            );
                            break;
                        }

                        if (!$req->getPost('code')) {
                            $arAnswer['error'] = array(
                                'CODE' => '1',
                                'MSG'  => $this->getMessage('AJAX.METHOD_AUTH.EMPTY_CODE'),
                                'MORE' => ''
                            );
                            break 2;
                        }


                        $phone = $oManager->getPreparedPhone($req->getPost('phone'));
                        if (!$oManager->isValidPhone($phone)) {
                            $arAnswer['error'] = array(
                                'CODE' => '2',
                                'MSG'  => $this->getMessage('AJAX.METHOD_AUTH.ERROR_VALID_PHONE'),
                                'MORE' => ''
                            );
                            break 2;
                        }

                        if ($phone == $oManager->getPhone($USER->GetID())) {
                            $arAnswer['error'] = array(
                                'CODE' => '3',
                                'MSG'  => $this->getMessage('AJAX.METHOD_AUTH.ERROR_PHONE_EQUAL'),
                                'MORE' => ''
                            );
                            break 2;
                        }


                        if ($oManager->isValidCode($req->getPost('phone'), $req->getPost('code'))) {
                            $result = $oManager->setPhone($USER->GetID(), $req->getPost('phone'));

                            if ($result->isSuccess()) {
                                $arAnswer['response'] = $result->getMore();

                                $oManager->finishAction($req->getPost('phone'));

                            } else {
                                $arError = $result->getErrors();
                                foreach ($arError as $obError) {
                                    $arAnswer['error'] = array(
                                        'CODE' => $obError->getCode(),
                                        'MSG'  => $obError->getMessage(),
                                        'MORE' => $obError->getMore()
                                    );
                                }
                            }

                        } else {
                            $arAnswer['error'] = array(
                                'CODE' => '4',
                                'MSG'  => $this->getMessage('AJAX.METHOD_AUTH.ERROR_VALID_CODE'),
                                'MORE' => ''
                            );
                        }


                        break;
                    }
                    case 'sendCode': {

                        if (!$req->getPost('phone')) {
                            $arAnswer['error'] = array(
                                'CODE' => '0',
                                'MSG'  => $this->getMessage('AJAX.METHOD_AUTH.EMPTY_PHONE'),
                                'MORE' => ''
                            );
                            break;
                        }

                        $phone = $oManager->getPreparedPhone($req->getPost('phone'));
                        if (!$oManager->isValidPhone($phone)) {
                            $arAnswer['error'] = array(
                                'CODE' => '2',
                                'MSG'  => $this->getMessage('AJAX.METHOD_AUTH.ERROR_VALID_PHONE'),
                                'MORE' => ''
                            );
                            break 2;
                        }

                        if ($phone == $oManager->getPhone($USER->GetID())) {
                            $arAnswer['error'] = array(
                                'CODE' => '3',
                                'MSG'  => $this->getMessage('AJAX.METHOD_AUTH.ERROR_PHONE_EQUAL'),
                                'MORE' => ''
                            );
                            break 2;
                        }
    
//                        $oManager->limitIP()->setAtempt();
//                        $resultLimit = $oManager->limitIP()->check();
//                        if(!$resultLimit->isSuccess())
//                        {
//                            /**
//                             * @var \Bxmaker\AuthUserPhone\Error $error
//                             */
//                            foreach ($resultLimit->getErrors() as $obError) {
//                                $arAnswer['error'] = array(
//                                    'CODE' => $obError->getCode(),
//                                    'MSG' => $obError->getMessage(),
//                                    'MORE' => $obError->getMore()
//                                );
//                                break 2;
//                            }
//                        }
                        

                        $result = $oManager->sendCode($req->getPost('phone'));
                        if ($result->isSuccess()) {
                            $arAnswer['response'] = $result->getMore();
                        } else {
                            /**
                             * @var \Bxmaker\AuthUserPhone\Error $error
                             */
                            foreach ($result->getErrors() as $obError) {
                                $arAnswer['error'] = array(
                                    'CODE' => $obError->getCode(),
                                    'MSG'  => $obError->getMessage(),
                                    'MORE' => $obError->getMore()
                                );
                                break;
                            }
                        }
                        
                        break;
                    }
                    case 'getCaptcha': {
                        $arAnswer['response'] = $oManager->getCaptchaForErrorMore();
                        break;
                    }
                    default: {
                        $arAnswer['error'] = array(
                            'CODE' => '',
                            'MSG'  => $this->getMessage('AJAX.UNDEFINED_METHOD'),
                            'MORE' => ''
                        );
                        break;
                    }
                }

            } while (false);


            if(!empty($arAnswer['error']))
            {
                if(isset($arAnswer['error']['more']))
                {
                    $arAnswer['error']['more']['sessid'] = bitrix_sessid();
                }
            }
            else {
                $arAnswer['response']['sessid'] = bitrix_sessid();
            }


            $oManager->getBase()->showJson($arAnswer);
        }

    }