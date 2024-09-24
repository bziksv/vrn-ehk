<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

/**
 * @var CBitrixComponentTemplate $this
 * @var array $arResult
 */

$frame = $this->createFrame()->begin('');

if ($arResult['shouldShowDebugger'] === true): ?>

	<script>
		BX.Event.ready(() => {

			const initializeDebugger = () => {
				BX.Runtime.loadExtension('bizproc.debugger').then(
					(exports) => {
						const {Manager} = exports;

						Manager.Instance.initializeDebugger({
							'session': <?= CUtil::PhpToJSObject($arResult['session']) ?>,
							'documentSigned': '<?= CUtil::JSEscape($arResult['documentSigned']) ?>',
						});
					}
				);
			};

			if (BX.Type.isUndefined(window.frameCacheVars))
			{
				BX.Event.ready(initializeDebugger);
			}
			else
			{
				const isCompositeReady = (
					BX.frameCache?.frameDataInserted === true || !BX.Type.isUndefined(window.frameRequestFail)
				);
				if (isCompositeReady)
				{
					initializeDebugger();
				}
				else
				{
					BX.Event.EventEmitter.subscribe('onFrameDataProcessed', initializeDebugger);
					BX.Event.EventEmitter.subscribe('onFrameDataRequestFail', initializeDebugger);
				}
			}
		});
	</script>

<?php endif;

if ($arResult['shouldShowTrackingPopup'] === true):
	\Bitrix\Main\UI\Extension::load(['popup', 'ui.buttons']);
?>

	<style>
		.bp_track_warn2024__popup-wrapper {
			color: var(--ui-color-base-default);
			padding-top: 15px;
		}

		.bp_track_warn2024__popup-title {
			text-align: center;
			font-size: 27px;
			line-height: var(--ui-typography-heading-h3-line-height);
			font-weight: var(--ui-typography-text-md-font-weight);
			margin: 0 0 28px;
		}

		.bp_track_warn2024__popup-content {
			border-radius: 6px;
			padding: 24px 18px 80px 215px;
			margin: 0 12px 3px 20px;
			position: relative;
			background-color: #e6f7fc;
			background-position: left top, left 335px top 48px;
			background-repeat: no-repeat;
			background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='314' height='293' viewBox='0 0 314 293' fill='none'%3E%3Cg filter='url(%23filter0_d_4746_150570)'%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M195.143 281.862H-8.71608C-10.4604 281.862 -12.1898 281.796 -13.9018 281.667C-59.9745 280.594 -96.9977 242.057 -97 194.677C-96.9866 171.583 -88.0051 149.441 -72.0313 133.121C-63.8434 124.756 -54.162 118.272 -43.629 113.94C-43.8052 111.59 -43.8949 109.215 -43.8949 106.819C-43.8804 82.4524 -34.4033 59.0895 -17.5484 41.8701C-0.693504 24.6506 22.1585 14.9852 45.9804 15C76.4956 15.0381 103.437 30.6467 119.628 54.4704C127.317 51.6839 135.594 50.1693 144.217 50.174C182.308 50.2209 213.594 79.9222 217.221 117.905C253.719 126.044 281.032 159.303 281 199.069C280.963 244.895 244.624 282.019 199.823 282C198.252 281.999 196.691 281.953 195.143 281.862Z' fill='url(%23paint0_linear_4746_150570)' shape-rendering='crispEdges'/%3E%3C/g%3E%3Crect x='-12.0287' y='88' width='191.659' height='138.155' rx='8.69231' fill='%2321B9E9'/%3E%3Crect x='-2.5799' y='99.5132' width='172.807' height='110.99' rx='2.17308' fill='%23E9FAFF'/%3E%3Crect x='-2.5799' y='99.5132' width='172.807' height='110.99' rx='2.17308' fill='white' fill-opacity='0.2'/%3E%3Cg opacity='0.5'%3E%3Cpath d='M6 116C6 117.105 6.89543 118 8 118H34C35.1046 118 36 117.105 36 116V114C36 112.895 35.1046 112 34 112H8C6.89543 112 6 112.895 6 114V116Z' fill='%2367D7FB'/%3E%3Cpath d='M42 116C42 117.105 42.8954 118 44 118H70C71.1046 118 72 117.105 72 116V114C72 112.895 71.1046 112 70 112H44C42.8954 112 42 112.895 42 114V116Z' fill='%2367D7FB'/%3E%3Cpath d='M78 116C78 117.105 78.8954 118 80 118H158C159.105 118 160 117.105 160 116V114C160 112.895 159.105 112 158 112H80C78.8954 112 78 112.895 78 114V116Z' fill='%2367D7FB'/%3E%3Cpath d='M6 128C6 129.105 6.89543 130 8 130H34C35.1046 130 36 129.105 36 128V126C36 124.895 35.1046 124 34 124H8C6.89543 124 6 124.895 6 126V128Z' fill='%2367D7FB'/%3E%3Cpath d='M42 128C42 129.105 42.8954 130 44 130H70C71.1046 130 72 129.105 72 128V126C72 124.895 71.1046 124 70 124H44C42.8954 124 42 124.895 42 126V128Z' fill='%2367D7FB'/%3E%3Cpath d='M78 128C78 129.105 78.8954 130 80 130H158C159.105 130 160 129.105 160 128V126C160 124.895 159.105 124 158 124H80C78.8954 124 78 124.895 78 126V128Z' fill='%2367D7FB'/%3E%3Cpath d='M6 140C6 141.105 6.89543 142 8 142H34C35.1046 142 36 141.105 36 140V138C36 136.895 35.1046 136 34 136H8C6.89543 136 6 136.895 6 138V140Z' fill='%2367D7FB'/%3E%3Cpath d='M42 140C42 141.105 42.8954 142 44 142H70C71.1046 142 72 141.105 72 140V138C72 136.895 71.1046 136 70 136H44C42.8954 136 42 136.895 42 138V140Z' fill='%2367D7FB'/%3E%3Cpath d='M78 140C78 141.105 78.8954 142 80 142H158C159.105 142 160 141.105 160 140V138C160 136.895 159.105 136 158 136H80C78.8954 136 78 136.895 78 138V140Z' fill='%2367D7FB'/%3E%3Cpath d='M6 152C6 153.105 6.89543 154 8 154H34C35.1046 154 36 153.105 36 152V150C36 148.895 35.1046 148 34 148H8C6.89543 148 6 148.895 6 150V152Z' fill='%2367D7FB'/%3E%3Cpath d='M42 152C42 153.105 42.8954 154 44 154H70C71.1046 154 72 153.105 72 152V150C72 148.895 71.1046 148 70 148H44C42.8954 148 42 148.895 42 150V152Z' fill='%2367D7FB'/%3E%3Cpath d='M78 152C78 153.105 78.8954 154 80 154H158C159.105 154 160 153.105 160 152V150C160 148.895 159.105 148 158 148H80C78.8954 148 78 148.895 78 150V152Z' fill='%2367D7FB'/%3E%3Cpath d='M6 164C6 165.105 6.89543 166 8 166H34C35.1046 166 36 165.105 36 164V162C36 160.895 35.1046 160 34 160H8C6.89543 160 6 160.895 6 162V164Z' fill='%2367D7FB'/%3E%3Cpath d='M42 164C42 165.105 42.8954 166 44 166H70C71.1046 166 72 165.105 72 164V162C72 160.895 71.1046 160 70 160H44C42.8954 160 42 160.895 42 162V164Z' fill='%2367D7FB'/%3E%3Cpath d='M78 164C78 165.105 78.8954 166 80 166H158C159.105 166 160 165.105 160 164V162C160 160.895 159.105 160 158 160H80C78.8954 160 78 160.895 78 162V164Z' fill='%2367D7FB'/%3E%3Cpath d='M6 176C6 177.105 6.89543 178 8 178H34C35.1046 178 36 177.105 36 176V174C36 172.895 35.1046 172 34 172H8C6.89543 172 6 172.895 6 174V176Z' fill='%2367D7FB'/%3E%3Cpath d='M42 176C42 177.105 42.8954 178 44 178H70C71.1046 178 72 177.105 72 176V174C72 172.895 71.1046 172 70 172H44C42.8954 172 42 172.895 42 174V176Z' fill='%2367D7FB'/%3E%3Cpath d='M78 176C78 177.105 78.8954 178 80 178H158C159.105 178 160 177.105 160 176V174C160 172.895 159.105 172 158 172H80C78.8954 172 78 172.895 78 174V176Z' fill='%2367D7FB'/%3E%3Cpath d='M6 188C6 189.105 6.89543 190 8 190H34C35.1046 190 36 189.105 36 188V186C36 184.895 35.1046 184 34 184H8C6.89543 184 6 184.895 6 186V188Z' fill='%2367D7FB'/%3E%3Cpath d='M42 188C42 189.105 42.8954 190 44 190H70C71.1046 190 72 189.105 72 188V186C72 184.895 71.1046 184 70 184H44C42.8954 184 42 184.895 42 186V188Z' fill='%2367D7FB'/%3E%3Cpath d='M78 188C78 189.105 78.8954 190 80 190H158C159.105 190 160 189.105 160 188V186C160 184.895 159.105 184 158 184H80C78.8954 184 78 184.895 78 186V188Z' fill='%2367D7FB'/%3E%3C/g%3E%3Crect opacity='0.2' x='76.1224' y='211.498' width='21.9937' height='3.13988' rx='1.56994' fill='white'/%3E%3Cpath d='M-32.9999 223.014C-32.9999 227.638 -29.2512 231.387 -24.6269 231.387H194.274C198.898 231.387 202.647 227.638 202.647 223.014C202.647 222.436 202.178 221.968 201.6 221.968H-31.9533C-32.5313 221.968 -32.9999 222.436 -32.9999 223.014Z' fill='%231690B8'/%3E%3Cdefs%3E%3Cfilter id='filter0_d_4746_150570' x='-129.596' y='-7.81731' width='443.192' height='332.192' filterUnits='userSpaceOnUse' color-interpolation-filters='sRGB'%3E%3CfeFlood flood-opacity='0' result='BackgroundImageFix'/%3E%3CfeColorMatrix in='SourceAlpha' type='matrix' values='0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0' result='hardAlpha'/%3E%3CfeOffset dy='9.77885'/%3E%3CfeGaussianBlur stdDeviation='16.2981'/%3E%3CfeComposite in2='hardAlpha' operator='out'/%3E%3CfeColorMatrix type='matrix' values='0 0 0 0 0.025382 0 0 0 0 0.275095 0 0 0 0 0.358333 0 0 0 0.06 0'/%3E%3CfeBlend mode='normal' in2='BackgroundImageFix' result='effect1_dropShadow_4746_150570'/%3E%3CfeBlend mode='normal' in='SourceGraphic' in2='effect1_dropShadow_4746_150570' result='shape'/%3E%3C/filter%3E%3ClinearGradient id='paint0_linear_4746_150570' x1='92' y1='15' x2='92' y2='282' gradientUnits='userSpaceOnUse'%3E%3Cstop stop-color='white'/%3E%3Cstop offset='1' stop-color='white' stop-opacity='0.74'/%3E%3C/linearGradient%3E%3C/defs%3E%3C/svg%3E"),
			url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='294' height='180' viewBox='0 0 294 180' fill='none'%3E%3Cpath opacity='0.6' fill-rule='evenodd' clip-rule='evenodd' d='M73.4221 95.966H22.1878C21.7494 95.966 21.3147 95.9497 20.8845 95.9177C9.30535 95.6524 0.000585019 86.1264 0 74.4145C0.00336642 68.706 2.26062 63.2327 6.27519 59.1985C8.33301 57.1307 10.7662 55.5279 13.4134 54.4571C13.3691 53.8762 13.3465 53.2892 13.3465 52.6969C13.3502 46.6736 15.732 40.8985 19.968 36.642C24.204 32.3855 29.9472 29.9963 35.9342 30C43.6034 30.0094 50.3744 33.8677 54.4436 39.7567C56.376 39.0679 58.4561 38.6935 60.6233 38.6947C70.1965 38.7063 78.0594 46.0482 78.9709 55.4372C88.1437 57.4491 95.0081 65.6705 95 75.5002C94.9906 86.828 85.8579 96.0047 74.5983 96C74.2035 95.9998 73.8113 95.9884 73.4221 95.966Z' fill='white'/%3E%3Cpath opacity='0.6' fill-rule='evenodd' clip-rule='evenodd' d='M268.849 179.125H210.339C209.838 179.125 209.342 179.107 208.85 179.07C195.627 178.768 185.001 167.92 185 154.581C185.004 148.08 187.582 141.847 192.166 137.253C194.516 134.898 197.295 133.072 200.318 131.853C200.268 131.191 200.242 130.523 200.242 129.848C200.246 122.989 202.966 116.412 207.804 111.564C212.641 106.717 219.2 103.996 226.037 104C234.796 104.011 242.528 108.405 247.175 115.111C249.382 114.327 251.758 113.901 254.233 113.902C265.166 113.915 274.145 122.276 275.186 132.969C285.662 135.26 293.501 144.623 293.492 155.818C293.481 168.719 283.051 179.169 270.192 179.164C269.742 179.164 269.294 179.151 268.849 179.125Z' fill='white'/%3E%3Cpath opacity='0.6' fill-rule='evenodd' clip-rule='evenodd' d='M262.46 28.985H239.809C239.616 28.985 239.423 28.9779 239.233 28.9638C234.114 28.8473 230 24.6616 230 19.5154C230.001 17.0072 230.999 14.6022 232.774 12.8297C233.684 11.9211 234.76 11.2168 235.93 10.7463C235.911 10.4911 235.901 10.2331 235.901 9.97288C235.902 7.32629 236.955 4.78875 238.828 2.91847C240.701 1.04819 243.24 -0.00161127 245.887 1.8563e-06C249.277 0.00413586 252.271 1.69946 254.07 4.28705C254.924 3.98439 255.844 3.81989 256.802 3.8204C261.034 3.82549 264.51 7.05147 264.913 11.177C268.969 12.061 272.004 15.6734 272 19.9925C271.996 24.9699 267.958 29.0021 262.98 29C262.806 28.9999 262.632 28.9949 262.46 28.985Z' fill='white'/%3E%3C/svg%3E");
		}

		.bp_track_warn2024__popup-content:before {
			content: '';
			width: 102px;
			height: 110px;
			position: absolute;
			top: 48px;
			left: -2px;
			background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='102' height='110' viewBox='0 0 102 110' fill='none'%3E%3Crect opacity='0.1' x='-8' width='110' height='110' rx='55' fill='%2375DF80'/%3E%3Ccircle opacity='0.9' cx='47' cy='55' r='40' fill='%239DCF00'/%3E%3Crect x='42.7279' y='50.3938' width='10.3614' height='26.6585' fill='white'/%3E%3Ccircle cx='47.1733' cy='37.1058' r='6.25268' fill='white'/%3E%3Crect x='38.283' y='72.6089' width='4.44518' height='4.44518' fill='white'/%3E%3Crect x='38.283' y='50.3978' width='4.44518' height='4.23561' fill='white'/%3E%3Crect x='51.6185' y='72.6089' width='4.44518' height='4.44518' fill='white'/%3E%3C/svg%3E");
		}

		.bp_track_warn2024__popup-wrapper .bp_track_warn2024__popup-subtitle {
			font-size: var(--ui-font-size-xl);
			line-height: var(--ui-font-line-height-2xs);
			font-weight: var(--ui-font-weight-semi-bold);
			margin-bottom: 36px;
		}

		.bp_track_warn2024__popup-wrapper p {
			font-size: var(--ui-font-size-sm);
			line-height: var(--ui-font-line-height-md);
			margin: 0;
		}

		.bp_track_warn2024__popup-wrapper p + p {
			margin-top: 20px;
		}

		.bp_track_warn2024__popup-wrapper.--warning .bp_track_warn2024__popup-content {
			background-color: #feecec;
		}

		.bp_track_warn2024__popup-wrapper.--warning .bp_track_warn2024__popup-content:before {
			background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='102' height='110' viewBox='0 0 102 110' fill='none'%3E%3Crect opacity='0.1' x='-8' width='110' height='110' rx='55' fill='%23DF7575'/%3E%3Cg opacity='0.86563' filter='url(%23filter0_d_6302_8084)'%3E%3Cpath d='M46.9784 95.75C24.3348 95.75 5.97852 77.394 5.97852 54.7498C5.97852 32.1063 24.3345 13.75 46.9784 13.75C69.6222 13.75 87.9785 32.1063 87.9785 54.7498C87.9785 77.394 69.6222 95.75 46.9784 95.75Z' fill='%23FF5752'/%3E%3C/g%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M69.9233 65.9336L50.2789 33.2145C48.7653 30.7026 45.1585 30.7026 43.6771 33.2145L24.0328 65.9336C22.487 68.5099 24.3548 71.7625 27.3498 71.7625H66.6385C69.6012 71.7625 71.469 68.5099 69.9233 65.9336ZM44.1602 44.6147C44.1602 43.1655 45.3195 42.0061 46.7687 42.0061H47.1229C48.5721 42.0061 49.7314 43.1655 49.7314 44.6147V54.3724C49.7314 55.8216 48.5721 56.9809 47.1229 56.9809H46.7687C45.3195 56.9809 44.1602 55.8216 44.1602 54.3724V44.6147ZM50.2467 63.5505C50.2467 65.3539 48.7653 66.8353 46.9619 66.8353C45.1585 66.8353 43.6771 65.3539 43.6771 63.5505C43.6771 61.7471 45.1585 60.2657 46.9619 60.2657C48.7653 60.2657 50.2467 61.7471 50.2467 63.5505Z' fill='white'/%3E%3Cdefs%3E%3Cfilter id='filter0_d_6302_8084' x='-2.02148' y='10.75' width='98' height='98' filterUnits='userSpaceOnUse' color-interpolation-filters='sRGB'%3E%3CfeFlood flood-opacity='0' result='BackgroundImageFix'/%3E%3CfeColorMatrix in='SourceAlpha' type='matrix' values='0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0' result='hardAlpha'/%3E%3CfeOffset dy='5'/%3E%3CfeGaussianBlur stdDeviation='4'/%3E%3CfeColorMatrix type='matrix' values='0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.1242 0'/%3E%3CfeBlend mode='normal' in2='BackgroundImageFix' result='effect1_dropShadow_6302_8084'/%3E%3CfeBlend mode='normal' in='SourceGraphic' in2='effect1_dropShadow_6302_8084' result='shape'/%3E%3C/filter%3E%3C/defs%3E%3C/svg%3E");
		}
	</style>
	<script>
		BX.Event.ready(() => {

			const popup = new BX.PopupWindow({
				width: 730,
				overlay: true,
				closeIcon: false,
				content: BX.Tag.render`
					<div class="bp_track_warn2024__popup-wrapper">
						<h3 class="bp_track_warn2024__popup-title"><?= CUtil::JSEscape(\Bitrix\Main\Localization\Loc::getMessage('BP_TRACK_WARN_2024_TITLE')) ?></h3>
						<div class="bp_track_warn2024__popup-content">
							<p class="bp_track_warn2024__popup-subtitle"><?= CUtil::JSEscape(\Bitrix\Main\Localization\Loc::getMessage('BP_TRACK_WARN_2024_SUBTITLE')) ?></p>
							<p><?= CUtil::JSEscape(\Bitrix\Main\Localization\Loc::getMessage('BP_TRACK_WARN_2024_P1')) ?></p>
							<p><?= CUtil::JSEscape(\Bitrix\Main\Localization\Loc::getMessage('BP_TRACK_WARN_2024_P2')) ?></p>
						</div>
					</div>
				`,
				buttons: [
					new BX.UI.Button({
						color: BX.UI.Button.Color.SUCCESS,
						text: '<?= CUtil::JSEscape(\Bitrix\Main\Localization\Loc::getMessage('BP_TRACK_WARN_2024_BTN_READ')) ?>',
						onclick: (button, event) =>
						{
							popup.destroy();
							BX.userOptions.save(
								'bizproc',
								'track_warn_2024',
								'read',
								Math.floor(Date.now() / 1000)
							);
						}
					}),
					new BX.UI.Button({
						color: BX.UI.Button.Color.LINK,
						text: '<?= CUtil::JSEscape(\Bitrix\Main\Localization\Loc::getMessage('BP_TRACK_WARN_2024_BTN_DETAILS')) ?>',
						onclick: (button, event) =>
						{
							top.BX.Helper?.show('redirect=detail&code=21994508');
						}
					}),
				]
			});

			if (BX.Reflection.getClass('BX.UI.BannerDispatcher'))
			{
				BX.UI.BannerDispatcher.normal.toQueue((onDone) => {
					popup.subscribe('onAfterClose', () => {
						onDone();
					});

					popup.show();
				});
			}
			else
			{
				popup.show();
			}
		});
	</script>

<?php endif;

$frame->end();
