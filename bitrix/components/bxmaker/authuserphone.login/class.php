<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

    use Bitrix\Main\Loader;
    use Bitrix\Main\Localization\Loc as Loc;

    \Bitrix\Main\Localization\Loc::loadMessages(__FILE__);


    class BXmakerAuthUserPhoneLoginComponent extends CBitrixComponent
    {

        public function onPrepareComponentParams($arParams)
        {
            global $USER;

            if(!Loader::includeModule('bxmaker.authuserphone'))
            {
                return parent::onPrepareComponentParams($arParams);
            }
            
            $oManager = \Bxmaker\AuthUserPhone\Manager::getInstance();

            //для ajax
            $this->arResult['_ORIGINAL_PARAMS'] = $arParams;

            $arParams['CONSENT_SHOW'] = $this->getParamBool($arParams, 'CONSENT_SHOW', ($oManager->isNeedConsent() && !$USER->IsAuthorized() ? 'Y' : 'N'));
            $arParams['CONSENT_ID'] = $this->getParamInt($arParams, 'CONSENT_ID', $oManager->getConsentId());
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
            return Loc::getMessage('BXMAKER.AUTHUSERPHONE.LOGIN.'.$name, $arReplace);
        }


        public function executeComponent()
        {
            $this->setFrameMode(true);

            try {

                $this->arResult['USER_IS_AUTHORIZED'] = (isset($GLOBALS['USER']) && $GLOBALS['USER']->IsAuthorized() ? 'Y' : 'N');
    
                if(!Loader::includeModule('bxmaker.authuserphone'))
                {
                    throw new \Bitrix\Main\LoaderException($this->getMessage("MODULE_NOT_INSTALLED"));
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


                switch ($req->getPost('method')) {
                    case 'auth': {

                        if ($USER->IsAuthorized()) {
                            $arAnswer['response'] = array(
                                'MSG' => $this->getMessage('AJAX.METHOD_AUTH.OK'),
                            );
                            break 2;
                        }

                        if (!$req->getPost('phone')) {
                            $arAnswer['error'] = array(
                                'CODE' => '0',
                                'MSG' => $this->getMessage('AJAX.METHOD_AUTH.EMPTY_PHONE'),
                                'MORE' => ''
                            );
                            break;
                        }

                        if (!$req->getPost('password')) {
                            $arAnswer['error'] = array(
                                'CODE' => '1',
                                'MSG' => $this->getMessage('AJAX.METHOD_AUTH.EMPTY_PASSWORD'),
                                'MORE' => ''
                            );
                            break 2;
                        }

                        $arUserConsent = array();
                        if($req->getPost('consent'))
                        {
                            $arUserConsent['id']  = $req->getPost('consent_id');
                            $arUserConsent['sec']  = $req->getPost('consent_sec');
                            $arUserConsent['url']  = $req->getPost('consent_url');
                        }


                        // пробуем авторизовать
                        $result = $oManager->login($req->getPost('phone'), $req->getPost('password'),
                            ($req->getPost('remember') && $req->getPost('remember') == 'Y' ? true : false),
                            ($oManager->getParam('REGISTRATION_LOGIN', 'N') == 'Y' && $req->getPost('login')  ? $req->getPost('login') : null),
                            ($oManager->getParam('REGISTRATION_EMAIL', 'N') == 'Y' && $req->getPost('email')  ? $req->getPost('email') : null),
                            $arUserConsent
                        );
                        if ($result->isSuccess()) {
                            $arAnswer['response'] = $result->getMore();
                        } else {
                            $arError = $result->getErrors();
                            foreach ($arError as $obError) {
                                $arAnswer['error'] = array(
                                    'CODE' => $obError->getCode(),
                                    'MSG' => $obError->getMessage(),
                                    'MORE' => $obError->getMore()
                                );
                            }
                        }


                        break;
                    }
                    case 'register': {

                        if ($USER->IsAuthorized()) {
                            $arAnswer['response'] = array(
                                'MSG' => $this->getMessage('AJAX.METHOD_AUTH.OK'),
                            );
                            break 2;
                        }

                        if (!$req->getPost('phone')) {
                            $arAnswer['error'] = array(
                                'CODE' => '0',
                                'MSG' => $this->getMessage('AJAX.METHOD_AUTH.EMPTY_PHONE'),
                                'MORE' => ''
                            );
                            break;
                        }

                        if (!$req->getPost('password')) {
                            $arAnswer['error'] = array(
                                'CODE' => '1',
                                'MSG' => $this->getMessage('AJAX.METHOD_AUTH.EMPTY_PASSWORD'),
                                'MORE' => ''
                            );
                            break 2;
                        }

                        $arUserConsent = array();
                        if($req->getPost('consent'))
                        {
                            $arUserConsent['id']  = $req->getPost('consent_id');
                            $arUserConsent['sec']  = $req->getPost('consent_sec');
                            $arUserConsent['url']  = $req->getPost('consent_url');
                        }


                        // пробуем авторизвоать или зарегистрировать
                        $result = $oManager->login($req->getPost('phone'), $req->getPost('password'),
                            ($req->getPost('remember') && $req->getPost('remember') == 'Y' ? true : false),
                            ($oManager->getParam('REGISTRATION_LOGIN', 'N') == 'Y' && $req->getPost('login')  ? $req->getPost('login') : null),
                            ($oManager->getParam('REGISTRATION_EMAIL', 'N') == 'Y' && $req->getPost('email')  ? $req->getPost('email') : null),
                            $arUserConsent, true
                        );
                        if ($result->isSuccess()) {
                            $arAnswer['response'] = $result->getMore();
                        } else {
                            $arError = $result->getErrors();
                            foreach ($arError as $obError) {
                                $arAnswer['error'] = array(
                                    'CODE' => $obError->getCode(),
                                    'MSG' => $obError->getMessage(),
                                    'MORE' => $obError->getMore()
                                );
                            }
                        }


                        break;
                    }
                    case 'sendCode': {

                        if (!$req->getPost('phone')) {
                            $arAnswer['error'] = array(
                                'CODE' => '0',
                                'MSG' =>  ($req->getPost('registration') == 'Y' ? $this->getMessage('AJAX.METHOD_AUTH.EMPTY_PHONE') : $this->getMessage('AJAX.METHOD_AUTH.EMPTY_PHONE_LOGIN_EMAIL')),
                                'MORE' => ''
                            );
                            break;
                        }
                        
                        //если отключена авторегистрация,и пользвоатель пытается авторизоваться, проверяем наличие такого номера пред отправкой
                        if($req->getPost('registration') != 'Y' && !$oManager->isEnableAutoRegister())
                        {
                            $userResult  = $oManager->getUserIdByPhone($req->getPost('phone'));
                            if(!$userResult->isSuccess())
                            {
                                $arAnswer['error'] = array(
                                    'CODE' => '0',
                                    'MSG' =>  $this->getMessage('AJAX.METHOD_AUTH.PHONE_NOT_FOUND'),
                                    'MORE' => ''
                                );
                                break;
                            }
                        }
                        
                        $oManager->limitIP()->setAtempt();
                        $resultLimit = $oManager->limitIP()->check();
                        if(!$resultLimit->isSuccess())
                        {
                            /**
                             * @var \Bxmaker\AuthUserPhone\Error $error
                             */
                            foreach ($resultLimit->getErrors() as $obError) {
                                $arAnswer['error'] = array(
                                    'CODE' => $obError->getCode(),
                                    'MSG' => $obError->getMessage(),
                                    'MORE' => $obError->getMore()
                                );
                                break 2;
                            }
                        }
                        

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
                                    'MSG' => $obError->getMessage(),
                                    'MORE' => $obError->getMore()
                                );
                                break;
                            }
                        }
                        break;
                    }
                    case 'sendEmail': {

                        if (!$req->getPost('phone')) {
                            $arAnswer['error'] = array(
                                'CODE' => '0',
                                'MSG' => $this->getMessage('AJAX.METHOD_AUTH.EMPTY_PHONE_LOGIN_EMAIL'),
                                'MORE' => ''
                            );
                            break;
                        }
                        

                        $result = $oManager->sendEmail($req->getPost('phone'));
                        if ($result->isSuccess()) {
                            $arAnswer['response'] = $result->getMore();
                        } else {
                            /**
                             * @var \Bxmaker\AuthUserPhone\Error $error
                             */
                            foreach ($result->getErrors() as $obError) {
                                $arAnswer['error'] = array(
                                    'CODE' => $obError->getCode(),
                                    'MSG' => $obError->getMessage(),
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
                            'MSG' => $this->getMessage('AJAX.UNDEFINED_METHOD'),
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