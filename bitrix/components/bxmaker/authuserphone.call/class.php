<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

    use Bitrix\Main\Loader;
    use Bitrix\Main\Localization\Loc as Loc;
    
    \Bitrix\Main\Localization\Loc::loadMessages(__FILE__);

    class BXmakerAuthUserPhoneCallComponent extends CBitrixComponent
    {

        public function onPrepareComponentParams($arParams)
        {
            global $USER;
            
            // подключаем модуль
            if (!Loader::includeModule('bxmaker.authuserphone')) {
                return parent::onPrepareComponentParams($arParams);
            }
            
            $oManager = \Bxmaker\AuthUserPhone\Manager::getInstance();

            //для ajax
            $this->arResult['_ORIGINAL_PARAMS'] = $arParams;

            $arParams['CONSENT_SHOW'] = $this->getParamBool($arParams, 'CONSENT_SHOW', ($oManager->isNeedConsent() && !$USER->IsAuthorized() ? 'Y' : 'N'));
            $arParams['CONSENT_ID'] = $this->getParamInt($arParams, 'CONSENT_ID', $oManager->getConsentId());
            $arParams['RAND_STRING'] = $this->getParamStr($arParams, 'RAND_STRING', $this->randString());
            $arParams['IS_AJAX'] = $this->getParamBool($arParams, 'IS_AJAX', 'N');
            $arParams['CONFIRM_QUEUE'] = $this->getParamStr($arParams, 'CONFIRM_QUEUE', $oManager->getConfirmQueue());
            $arParams['CONFIRM_SMSCODE'] = $this->getParamBool($arParams, 'CONFIRM_SMSCODE', $oManager->isEnableConfirmSmsCode() ? 'Y' : 'N');
            $arParams['CONFIRM_USERCALL'] = $this->getParamBool($arParams, 'CONFIRM_USERCALL', $oManager->isEnableConfirmUserCall() ? 'Y' : 'N');
            $arParams['CONFIRM_BOTCALL'] = $this->getParamBool($arParams, 'CONFIRM_BOTCALL', $oManager->isEnableConfirmBotCall() ? 'Y' : 'N');

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
            return Loc::getMessage('BXMAKER.AUTHUSERPHONE.CALL.'.$name, $arReplace);
        }

        public function executeComponent()
        {
            $this->setFrameMode(true);

            try {

                $this->arResult['USER_IS_AUTHORIZED'] = (isset($GLOBALS['USER']) && $GLOBALS['USER']->IsAuthorized() ? 'Y' : 'N');
    
                // подключаем модуль
                if (!Loader::includeModule('bxmaker.authuserphone')) {
                    throw new \Bitrix\Main\LoaderException($this->getMessage('MODULE_NOT_INSTALLED'));
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
                    case 'auth':
                        {
                            $confirmResult = $oManager->checkConfirm($req->getPost('phone'), $req->getPost('confirmType'), $req->getPost('confirmValue'));

                            $result = $oManager->authByPhonePassword($req->getPost('phone'), $req->getPost('password'), $confirmResult);
                            if ($result->isSuccess()) {
                                $arAnswer['response'] = $result->getMore();
                            } else {
                                $arAnswer = $result->getJsonAnswerError();
                            }

                            break;
                        }
                    case 'getCaptcha':
                        {
                            $arAnswer['response'] = $oManager->getCaptchaForErrorMore();
                            break;
                        }
                    case 'register':
                        {

                            $arUserFields = array();

                            $arUserConsent = array();

                            if ($req->getPost('consent')) {
                                $arUserConsent['id'] = $req->getPost('consent_id');
                                $arUserConsent['sec'] = $req->getPost('consent_sec');
                                $arUserConsent['url'] = $req->getPost('consent_url');
                            }

                            $confirmResult = $oManager->checkConfirm($req->getPost('phone'), $req->getPost('confirmType'), $req->getPost('confirmValue'));

                            $result = $oManager->register($req->getPost('phone'), $req->getPost('password'), $req->getPost('login'), $req->getPost('email'), $arUserFields, $arUserConsent, $confirmResult);

                            if ($result->isSuccess()) {
                                $arAnswer['response'] = $result->getMore();
                            } else {
                                $arAnswer = $result->getJsonAnswerError();
                            }

                            break;
                        }
                    case 'forgot':
                        {
                            $confirmResult = $oManager->checkConfirm($req->getPost('phone'), $req->getPost('confirmType'), $req->getPost('confirmValue'));

                            $result = $oManager->forgot($req->getPost('phone'), $req->getPost('email'), $confirmResult);
                            if ($result->isSuccess()) {
                                $arAnswer['response'] = $result->getMore();
                            } else {
                                $arAnswer = $result->getJsonAnswerError();
                            }

                            break;
                        }
                    case 'sendCode':
                        {
    
                            $oManager->limitIP()->setAtempt();
                            $resultLimit = $oManager->limitIP()->check();
                            if(!$resultLimit->isSuccess())
                            {
                                $arAnswer = $resultLimit->getJsonAnswerError();
                                break;
                            }
                            
                            $result = $oManager->sendCode($req->getPost('phone'));
                            if ($result->isSuccess()) {
                                $arAnswer['response'] = $result->getMore();
                            } else {
                                $arAnswer = $result->getJsonAnswerError();
                            }
                            break;
                        }
                    case 'userCall':
                        {
                            $oManager->limitIP()->setAtempt();
                            $resultLimit = $oManager->limitIP()->check();
                            if(!$resultLimit->isSuccess())
                            {
                                $arAnswer = $resultLimit->getJsonAnswerError();
                                break;
                            }
                            
                            $result = $oManager->getService()->startUserCall($req->getPost('phone'));
                            if ($result->isSuccess()) {
                                $arAnswer['response']['phone'] = $oManager->getFormater()->getFormatedPhone($result->getMore('PHONE'), false, true);
                                $arAnswer['response']['phone'] = preg_replace('/^7/', '8', $arAnswer['response']['phone']);
                            } else {
                                $arAnswer = $result->getJsonAnswerError();
                            }
                            break;
                        }
                    case 'botCall':
                        {
                            $oManager->limitIP()->setAtempt();
                            $resultLimit = $oManager->limitIP()->check();
                            if(!$resultLimit->isSuccess())
                            {
                                $arAnswer = $resultLimit->getJsonAnswerError();
                                break;
                            }
                            
                            $result = $oManager->getService()->startBotCall($req->getPost('phone'));
                            if ($result->isSuccess()) {
                                $arAnswer['response']['msg'] = 'ok';
                                $arAnswer['response']['length'] = 6;
                            } else {
                                $arAnswer = $result->getJsonAnswerError();
                            }
                            break;
                        }
                    default:
                        {
                            $arAnswer['error'] = array(
                                'msg'  => $this->getMessage('AJAX.UNDEFINED_METHOD'),
                                'code' => 'UNDEFINED_METHOD',
                                'more' => array()
                            );
                            break;
                        }
                }

            } while (false);


            if(!empty($arAnswer['error']))
            {
                if(isset($arAnswer['error']['more']))
                $arAnswer['error']['more']['sessid'] = bitrix_sessid();
            }
            else {
                $arAnswer['response']['sessid'] = bitrix_sessid();
            }


            $oManager->getBase()->showJson($arAnswer);
        }


    }