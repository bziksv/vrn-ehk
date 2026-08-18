<?php

namespace Prime\Alerts;

class ProfileBanner
{
	public const SNOOZE_SECONDS = 1209600; // 14 суток
	public const SNOOZE_OPTION = 'profile_modal_snooze';

	public static function shouldShow(): bool
	{
		if (!Config::isEnabled() || !Config::isYes('profile_banner', 'Y')) {
			return false;
		}

		if (self::isSnoozed()) {
			return false;
		}

		try {
			$path = (string)\Bitrix\Main\Context::getCurrent()->getRequest()->getRequestedPageDirectory();
			if ($path === '/personal' || strpos($path, '/personal/') === 0) {
				return false;
			}
		} catch (\Throwable $e) {
			// ignore
		}

		$data = self::profileData();
		$email = $data['email'];
		if ($email === '') {
			return false;
		}

		return !EmailPolicy::isAllowed($email);
	}

	public static function snoozeUntil(): int
	{
		global $USER;
		if (!is_object($USER) || !$USER->IsAuthorized()) {
			return 0;
		}

		return (int)\CUserOptions::GetOption(Config::MODULE_ID, self::SNOOZE_OPTION, '0');
	}

	public static function isSnoozed(): bool
	{
		return self::snoozeUntil() > time();
	}

	public static function snooze(int $seconds = self::SNOOZE_SECONDS): int
	{
		$until = time() + max(1, $seconds);
		\CUserOptions::SetOption(Config::MODULE_ID, self::SNOOZE_OPTION, (string)$until);

		return $until;
	}

	/** @return array{email:string,phone:string,profileUrl:string} */
	public static function profileData(): array
	{
		global $USER;
		$email = '';
		$phone = '';
		if (is_object($USER) && $USER->IsAuthorized()) {
			$email = trim((string)$USER->GetEmail());
			$login = trim((string)$USER->GetLogin());
			$rs = \CUser::GetByID((int)$USER->GetID());
			if ($row = $rs->Fetch()) {
				$phone = trim((string)($row['PERSONAL_PHONE'] ?? ''));
				if ($email === '') {
					$email = trim((string)($row['EMAIL'] ?? ''));
				}
				if ($login === '') {
					$login = trim((string)($row['LOGIN'] ?? ''));
				}
			}
			if ($email === '' && strpos($login, '@') !== false) {
				$email = $login;
			}
		}

		return [
			'email' => $email,
			'phone' => $phone,
			'profileUrl' => '/personal/#personal-contacts',
		];
	}

	public static function getDefaultTitle(): string
	{
		return 'Проверьте почту и телефон в профиле';
	}

	public static function getDefaultText(): string
	{
		return '<p>В вашем профиле указан иностранный почтовый адрес или зарубежная почтовая служба. '
			. 'Рекомендуем сменить его на ящик в зоне <strong>.ru</strong> / <strong>.su</strong> '
			. 'либо на российский сервис (Яндекс, Mail.ru).</p>'
			. "\n"
			. '<p>Также просим проверить телефон, указанный в профиле. '
			. 'Позже на сайте появится вход по номеру — важно, чтобы он был актуальным.</p>';
	}

	public static function getTitle(): string
	{
		$custom = trim(Config::get('profile_banner_title', ''));
		$title = $custom !== '' ? $custom : self::getDefaultTitle();

		return EmailPolicy::applyMacrosPlain(self::applyProfileMacrosPlain($title));
	}

	public static function getBodyHtml(): string
	{
		$custom = trim(Config::get('profile_banner_text', ''));
		$body = $custom !== '' ? $custom : self::getDefaultText();

		return EmailPolicy::applyMacrosHtml(self::applyProfileMacrosHtml($body));
	}

	public static function render(): string
	{
		if (!self::shouldShow()) {
			return '';
		}

		$data = self::profileData();
		$email = $data['email'] !== '' ? $data['email'] : 'не указан';
		$phone = $data['phone'] !== '' ? $data['phone'] : 'не указан';
		$emailEsc = htmlspecialcharsbx($email);
		$phoneEsc = htmlspecialcharsbx($phone);
		$urlEsc = htmlspecialcharsbx($data['profileUrl']);
		$title = htmlspecialcharsbx(self::getTitle());
		$body = self::getBodyHtml();

		return '<div class="prime-alerts-profile-modal" role="dialog" aria-modal="true" aria-labelledby="prime-alerts-profile-title">'
			. '<div class="prime-alerts-profile-modal__overlay"></div>'
			. '<div class="prime-alerts-profile-modal__box">'
			. '<button type="button" class="prime-alerts-profile-modal__close" data-prime-alerts-close="1" aria-label="Закрыть">&times;</button>'
			. '<div class="prime-alerts-profile-modal__icon" aria-hidden="true">!</div>'
			. '<div id="prime-alerts-profile-title" class="prime-alerts-profile-modal__title">' . $title . '</div>'
			. '<div class="prime-alerts-profile-modal__text">' . $body . '</div>'
			. '<ul class="prime-alerts-profile-modal__facts">'
			. '<li><span>Почта в профиле</span><strong>' . $emailEsc . '</strong></li>'
			. '<li><span>Телефон в профиле</span><strong>' . $phoneEsc . '</strong></li>'
			. '</ul>'
			. '<div class="prime-alerts-profile-modal__actions">'
			. '<a class="prime-alerts-profile-modal__btn" href="' . $urlEsc . '">Изменить данные в профиле</a>'
			. '<button type="button" class="prime-alerts-profile-modal__snooze" data-prime-alerts-snooze="1">Отложить на 2 недели</button>'
			. '</div>'
			. '</div></div>';
	}

	protected static function applyProfileMacrosPlain(string $text): string
	{
		$data = self::profileData();
		$email = $data['email'] !== '' ? $data['email'] : 'не указан';
		$phone = $data['phone'] !== '' ? $data['phone'] : 'не указан';

		return str_replace(
			['#PROFILE_EMAIL#', '#PROFILE_PHONE#', '#PROFILE_EMAIL_RAW#', '#PROFILE_PHONE_RAW#', '#PROFILE_URL#'],
			[$email, $phone, $email, $phone, $data['profileUrl']],
			$text
		);
	}

	protected static function applyProfileMacrosHtml(string $text): string
	{
		$data = self::profileData();
		$email = $data['email'] !== '' ? $data['email'] : 'не указан';
		$phone = $data['phone'] !== '' ? $data['phone'] : 'не указан';
		$emailEsc = htmlspecialcharsbx($email);
		$phoneEsc = htmlspecialcharsbx($phone);
		$urlEsc = htmlspecialcharsbx($data['profileUrl']);

		return str_replace(
			['#PROFILE_EMAIL#', '#PROFILE_PHONE#', '#PROFILE_EMAIL_RAW#', '#PROFILE_PHONE_RAW#', '#PROFILE_URL#'],
			[$emailEsc, $phoneEsc, $emailEsc, $phoneEsc, $urlEsc],
			$text
		);
	}
}
