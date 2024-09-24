<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

class BizprocDebuggerComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$this->arResult['shouldShowDebugger'] = false;
		$this->arResult['shouldShowTrackingPopup'] = false;

		if (!\Bitrix\Main\Loader::includeModule('bizproc'))
		{
			return $this->includeComponentTemplate();
		}

		$this->shouldShowTrackingPopup();

		$cachedSession = \Bitrix\Bizproc\Debugger\Session\Manager::getCachedSession();
		$userId = (int)(\Bitrix\Main\Engine\CurrentUser::get()->getId());
		if (!$cachedSession || !$cachedSession->isStartedByUser($userId))
		{
			return $this->includeComponentTemplate();
		}

		$session = \Bitrix\Bizproc\Debugger\Session\Manager::getActiveSession();
		if (!$session)
		{
			return $this->includeComponentTemplate();
		}

		$fixedDocument = $session->getFixedDocument();
		$documentSigned =
			$fixedDocument
				? $fixedDocument->getSignedDocument()
				: CBPDocument::signParameters([$session->getParameterDocumentType(), $session->getDocumentCategoryId()])
		;

		$this->arResult['shouldShowDebugger'] = true;
		$this->arResult['session'] = $session->toArray();
		$this->arResult['documentSigned'] = $documentSigned;

		return $this->includeComponentTemplate();
	}

	private function shouldShowTrackingPopup(): void
	{
		if (!CBPRuntime::isFeatureEnabled())
		{
			return;
		}

		if (!\Bitrix\Main\Loader::includeModule('bitrix24'))
		{
			return;
		}

		if (!\CBitrix24::isPortalAdmin(\Bitrix\Main\Engine\CurrentUser::get()->getId()))
		{
			return;
		}

		$warnOption = CUserOptions::GetOption('bizproc', 'track_warn_2024');
		if (!empty($warnOption))
		{
			return;
		}

		$this->arResult['shouldShowTrackingPopup'] = true;
	}
}
