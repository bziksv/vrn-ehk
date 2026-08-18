/**
 * prime.phoneauth — login by phone / confirm by inbound call
 */
(function () {
	var cfg = window.PRIME_PHONEAUTH;
	if (!cfg || cfg.enabled === false) return;

	function postForm(url, data) {
		var body = [];
		data.sessid = data.sessid || cfg.sessid || '';
		Object.keys(data).forEach(function (k) {
			body.push(encodeURIComponent(k) + '=' + encodeURIComponent(data[k]));
		});
		return fetch(url, {
			method: 'POST',
			credentials: 'same-origin',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
				'X-Requested-With': 'XMLHttpRequest'
			},
			body: body.join('&')
		}).then(function (r) { return r.json(); });
	}

	function showDuplicate(message, accounts, title) {
		var old = document.querySelector('.prime-phoneauth-modal');
		if (old && old.parentNode) {
			old.parentNode.removeChild(old);
		}
		var wrap = document.createElement('div');
		wrap.className = 'prime-phoneauth-modal';
		wrap.innerHTML = '<div class="prime-phoneauth-modal__overlay" data-close="1"></div>'
			+ '<div class="prime-phoneauth-modal__box">'
			+ '<div class="prime-phoneauth-modal__title"></div>'
			+ '<p class="prime-phoneauth-modal__text"></p>'
			+ '<ul class="prime-phoneauth-modal__accounts"></ul>'
			+ '<button type="button" class="prime-phoneauth-modal__btn" data-close="1">Понятно</button>'
			+ '</div>';
		wrap.querySelector('.prime-phoneauth-modal__title').textContent = title || 'Несколько аккаунтов';
		wrap.querySelector('.prime-phoneauth-modal__text').textContent = message || cfg.duplicateMessage;
		var list = wrap.querySelector('.prime-phoneauth-modal__accounts');
		(accounts || []).forEach(function (email) {
			if (!email) return;
			var li = document.createElement('li');
			li.textContent = email;
			list.appendChild(li);
		});
		if (!list.children.length) {
			list.style.display = 'none';
		}
		document.body.appendChild(wrap);
		wrap.addEventListener('click', function (e) {
			if (e.target && e.target.getAttribute('data-close') === '1') {
				wrap.parentNode && wrap.parentNode.removeChild(wrap);
			}
		});
	}

	function initTabs(root) {
		var tabs = root.querySelectorAll('.prime-phoneauth-tabs button');
		if (!tabs.length) return;
		tabs.forEach(function (btn) {
			btn.addEventListener('click', function () {
				var name = btn.getAttribute('data-tab');
				tabs.forEach(function (b) { b.classList.toggle('is-active', b === btn); });
				root.querySelectorAll('.prime-phoneauth-panel').forEach(function (p) {
					p.classList.toggle('is-active', p.getAttribute('data-panel') === name);
				});
			});
		});
	}

	function bindPhoneForm(root) {
		var form = root.querySelector('.prime-phoneauth-phone-form');
		var wait = root.querySelector('.prime-phoneauth-wait');
		var err = root.querySelector('.prime-phoneauth-error');
		if (!form) return;

		var pollTimer = null;
		function stopPoll() {
			if (pollTimer) {
				clearInterval(pollTimer);
				pollTimer = null;
			}
		}

		function setError(text) {
			if (!err) return;
			err.textContent = text || '';
			err.style.display = text ? '' : 'none';
		}

		function showWait(data) {
			form.style.display = 'none';
			if (!wait) return;
			wait.style.display = '';
			var msg = wait.querySelector('[data-role="message"]');
			var num = wait.querySelector('[data-role="call-number"]');
			var from = wait.querySelector('[data-role="from-phone"]');
			var testBtn = wait.querySelector('[data-role="test"]');
			if (msg) msg.textContent = data.message || '';
			if (num) {
				var n = data.callNumber || cfg.callNumber || '';
				num.textContent = n || 'номер для звонка не настроен';
				if (n) num.href = 'tel:+' + String(n).replace(/\D/g, '');
			}
			if (from) from.textContent = data.phone || '';
			if (testBtn) testBtn.style.display = data.testConfirm ? '' : 'none';
			wait.setAttribute('data-token', data.token || '');
			startPoll(data.token);
		}

		function resetWait() {
			stopPoll();
			if (wait) wait.style.display = 'none';
			form.style.display = '';
			setError('');
		}

		function startPoll(token) {
			stopPoll();
			if (!token) return;
			pollTimer = setInterval(function () {
				fetch(cfg.statusUrl + '&token=' + encodeURIComponent(token), { credentials: 'same-origin' })
					.then(function (r) { return r.json(); })
					.then(function (data) {
						if (data && data.status === 'confirmed') {
							stopPoll();
							window.location.href = data.redirect || '/personal/';
						} else if (data && (data.status === 'expired' || data.status === 'cancelled' || data.status === 'missing')) {
							stopPoll();
							resetWait();
							setError(data.error || 'Время истекло');
						}
					})
					.catch(function () {});
			}, 2000);
		}

		form.addEventListener('submit', function (e) {
			e.preventDefault();
			setError('');
			var phoneInp = form.querySelector('input[name="PHONE"]');
			var btn = form.querySelector('button[type="submit"], input[type="submit"]');
			if (btn) btn.disabled = true;
			postForm(cfg.startUrl, { phone: phoneInp ? phoneInp.value : '' })
				.then(function (data) {
					if (btn) btn.disabled = false;
					if (!data || !data.ok) {
						if (data && data.status === 'duplicate') {
							showDuplicate(data.error, data.accounts);
							return;
						}
						setError((data && data.error) || 'Не удалось начать вход');
						return;
					}
					showWait(data);
				})
				.catch(function () {
					if (btn) btn.disabled = false;
					setError('Ошибка сети');
				});
		});

		if (wait) {
			var back = wait.querySelector('[data-role="back"]');
			if (back) back.addEventListener('click', resetWait);
			var testBtn = wait.querySelector('[data-role="test"]');
			if (testBtn) {
				testBtn.addEventListener('click', function () {
					var token = wait.getAttribute('data-token') || '';
					testBtn.disabled = true;
					postForm(cfg.testUrl, { token: token })
						.then(function (data) {
							if (data && data.status === 'confirmed') {
								window.location.href = data.redirect || '/personal/';
								return;
							}
							testBtn.disabled = false;
							setError((data && data.error) || 'Не подтвердилось');
						})
						.catch(function () { testBtn.disabled = false; });
				});
			}
		}
	}

	function initProfile() {
		if (!cfg.authorized) return;
		var phoneInput = document.querySelector('#personal-contacts input[name="PERSONAL_PHONE"]');
		if (!phoneInput) return;
		var line = phoneInput.closest('.line');
		if (!line || line.querySelector('.prime-phoneauth-status')) return;

		var status = document.createElement('div');
		status.className = 'prime-phoneauth-status';
		if (cfg.confirmed) {
			status.className += ' is-ok';
			status.textContent = 'Номер подтверждён — можно входить по телефону';
			line.appendChild(status);
			return;
		}
		if (cfg.duplicate) {
			status.className += ' is-wait';
			status.textContent = 'Этот номер указан ещё в других аккаунтах.';
			line.appendChild(status);
			showDuplicate(cfg.duplicateMessage, cfg.duplicateAccounts);
			return;
		}

		status.className += ' is-wait';
		status.innerHTML = 'Номер не подтверждён. <button type="button" class="prime-phoneauth-back" data-role="verify">Подтвердить звонком</button>';
		line.appendChild(status);

		var waitBox = document.createElement('div');
		waitBox.className = 'prime-phoneauth-wait';
		waitBox.style.display = 'none';
		waitBox.innerHTML = '<p data-role="message"></p>'
			+ '<p>Звоните с номера <strong data-role="from-phone"></strong></p>'
			+ '<a class="prime-phoneauth-number" data-role="call-number"></a>'
			+ '<button type="button" class="prime-phoneauth-test" data-role="test">Я позвонил (тест)</button>'
			+ '<button type="button" class="prime-phoneauth-back" data-role="back">Отмена</button>';
		line.appendChild(waitBox);

		var pollTimer = null;
		function stopPoll() {
			if (pollTimer) { clearInterval(pollTimer); pollTimer = null; }
		}
		function startPoll(token) {
			stopPoll();
			pollTimer = setInterval(function () {
				fetch(cfg.statusUrl + '&token=' + encodeURIComponent(token), { credentials: 'same-origin' })
					.then(function (r) { return r.json(); })
					.then(function (data) {
						if (data && data.status === 'confirmed') {
							stopPoll();
							window.location.reload();
						}
					})
					.catch(function () {});
			}, 2000);
		}

		status.querySelector('[data-role="verify"]').addEventListener('click', function () {
			postForm(cfg.startUrl, { phone: phoneInput.value, verify: 'Y' })
				.then(function (data) {
					if (!data || !data.ok) {
						if (data && data.status === 'duplicate') {
							showDuplicate(data.error, data.accounts);
							return;
						}
						alert((data && data.error) || 'Не удалось начать подтверждение');
						return;
					}
					waitBox.style.display = '';
					waitBox.querySelector('[data-role="message"]').textContent = data.message || '';
					waitBox.querySelector('[data-role="from-phone"]').textContent = data.phone || cfg.phone || '';
					var num = waitBox.querySelector('[data-role="call-number"]');
					var n = data.callNumber || cfg.callNumber || '';
					num.textContent = n || 'номер для звонка не настроен';
					if (n) num.href = 'tel:+' + String(n).replace(/\D/g, '');
					waitBox.querySelector('[data-role="test"]').style.display = data.testConfirm ? '' : 'none';
					waitBox.setAttribute('data-token', data.token || '');
					startPoll(data.token);
				});
		});
		waitBox.querySelector('[data-role="back"]').addEventListener('click', function () {
			stopPoll();
			waitBox.style.display = 'none';
		});
		waitBox.querySelector('[data-role="test"]').addEventListener('click', function () {
			var token = waitBox.getAttribute('data-token') || '';
			postForm(cfg.testUrl, { token: token }).then(function (data) {
				if (data && data.status === 'confirmed') {
					window.location.reload();
				}
			});
		});
	}

	function phoneReady(value) {
		var d = String(value || '').replace(/\D/g, '');
		if (d.length === 11 && (d.charAt(0) === '7' || d.charAt(0) === '8')) return true;
		return d.length === 10;
	}

	function initRegister() {
		if (cfg.authorized) return;
		var form = document.querySelector('.personal_enter .reg form[name="regform"]');
		if (!form) return;
		var phoneInput = form.querySelector('input[name="REGISTER[PERSONAL_PHONE]"]');
		if (!phoneInput) return;
		var line = phoneInput.closest('.line');
		if (!line || line.parentNode.querySelector('.prime-phoneauth-reg')) return;

		var tokenInp = document.createElement('input');
		tokenInp.type = 'hidden';
		tokenInp.name = 'prime_phoneauth_token';
		form.appendChild(tokenInp);

		var box = document.createElement('div');
		box.className = 'prime-phoneauth-reg';
		box.innerHTML = '<div class="prime-phoneauth-reg__status" data-role="status"></div>'
			+ '<ul class="prime-phoneauth-reg__accounts" data-role="accounts"></ul>'
			+ '<button type="button" class="prime-phoneauth-back" data-role="verify" style="display:none">Подтвердить звонком</button>'
			+ '<div class="prime-phoneauth-wait" data-role="wait" style="display:none">'
			+ '<p data-role="message"></p>'
			+ '<p>Звоните с номера <strong data-role="from-phone"></strong></p>'
			+ '<a class="prime-phoneauth-number" data-role="call-number"></a>'
			+ '<button type="button" class="prime-phoneauth-test" data-role="test">Я позвонил (тест)</button>'
			+ '<button type="button" class="prime-phoneauth-back" data-role="back">Отмена</button>'
			+ '</div>';
		line.parentNode.insertBefore(box, line.nextSibling);

		var statusEl = box.querySelector('[data-role="status"]');
		var accountsEl = box.querySelector('[data-role="accounts"]');
		var verifyBtn = box.querySelector('[data-role="verify"]');
		var wait = box.querySelector('[data-role="wait"]');
		var lastLookupPhone = '';
		var lastModalPhone = '';
		var pollTimer = null;

		function stopPoll() {
			if (pollTimer) {
				clearInterval(pollTimer);
				pollTimer = null;
			}
		}

		function setAccounts(list) {
			accountsEl.innerHTML = '';
			(list || []).forEach(function (email) {
				if (!email) return;
				var li = document.createElement('li');
				li.textContent = email;
				accountsEl.appendChild(li);
			});
			accountsEl.style.display = accountsEl.children.length ? '' : 'none';
		}

		function resetConfirm() {
			stopPoll();
			tokenInp.value = '';
			wait.style.display = 'none';
			verifyBtn.disabled = false;
		}

		function markConfirmed() {
			stopPoll();
			wait.style.display = 'none';
			verifyBtn.style.display = 'none';
			statusEl.className = 'prime-phoneauth-reg__status is-ok';
			statusEl.textContent = 'Номер подтверждён. Завершите регистрацию.';
		}

		function startPoll(token) {
			stopPoll();
			if (!token) return;
			pollTimer = setInterval(function () {
				fetch(cfg.statusUrl + '&token=' + encodeURIComponent(token), { credentials: 'same-origin' })
					.then(function (r) { return r.json(); })
					.then(function (data) {
						if (data && data.status === 'confirmed') {
							tokenInp.value = token;
							markConfirmed();
						} else if (data && (data.status === 'expired' || data.status === 'cancelled' || data.status === 'missing')) {
							resetConfirm();
							statusEl.className = 'prime-phoneauth-reg__status is-bad';
							statusEl.textContent = data.error || 'Время истекло. Запросите подтверждение ещё раз.';
							verifyBtn.style.display = '';
						}
					})
					.catch(function () {});
			}, 2000);
		}

		function showWait(data) {
			verifyBtn.style.display = 'none';
			wait.style.display = '';
			var msg = wait.querySelector('[data-role="message"]');
			var num = wait.querySelector('[data-role="call-number"]');
			var from = wait.querySelector('[data-role="from-phone"]');
			var testBtn = wait.querySelector('[data-role="test"]');
			if (msg) msg.textContent = data.message || '';
			if (from) from.textContent = data.phone || '';
			if (num) {
				var n = data.callNumber || cfg.callNumber || '';
				num.textContent = n || 'номер для звонка не настроен';
				if (n) num.href = 'tel:+' + String(n).replace(/\D/g, '');
			}
			if (testBtn) testBtn.style.display = data.testConfirm ? '' : 'none';
			wait.setAttribute('data-token', data.token || '');
			startPoll(data.token);
		}

		function applyLookup(data, phone) {
			if (!data || !data.ok) {
				statusEl.className = 'prime-phoneauth-reg__status';
				statusEl.textContent = '';
				setAccounts([]);
				verifyBtn.style.display = 'none';
				return;
			}
			statusEl.className = 'prime-phoneauth-reg__status' + (data.status === 'taken' ? ' is-bad' : '');
			statusEl.textContent = data.message || '';
			setAccounts(data.accounts);
			if (tokenInp.value) {
				verifyBtn.style.display = 'none';
				return;
			}
			verifyBtn.style.display = data.canConfirm ? '' : 'none';
			if ((data.accounts || []).length && lastModalPhone !== phone) {
				lastModalPhone = phone;
				var title = data.status === 'taken' || (data.accounts.length === 1)
					? 'Номер уже используется'
					: 'Несколько аккаунтов';
				showDuplicate(data.message, data.accounts, title);
			}
		}

		function lookupNow() {
			var phone = phoneInput.value;
			if (!phoneReady(phone)) {
				resetConfirm();
				statusEl.textContent = '';
				statusEl.className = 'prime-phoneauth-reg__status';
				setAccounts([]);
				verifyBtn.style.display = 'none';
				lastLookupPhone = '';
				return;
			}
			if (phone === lastLookupPhone && tokenInp.value) return;
			lastLookupPhone = phone;
			postForm(cfg.lookupUrl, { phone: phone }).then(function (data) {
				if (phoneInput.value !== phone) return;
				applyLookup(data, phone);
			});
		}

		phoneInput.addEventListener('blur', lookupNow);
		phoneInput.addEventListener('change', function () {
			resetConfirm();
			statusEl.className = 'prime-phoneauth-reg__status';
			statusEl.textContent = '';
			setAccounts([]);
			verifyBtn.style.display = 'none';
			lastLookupPhone = '';
			lookupNow();
		});

		verifyBtn.addEventListener('click', function () {
			verifyBtn.disabled = true;
			postForm(cfg.startUrl, { phone: phoneInput.value, register: 'Y' })
				.then(function (data) {
					verifyBtn.disabled = false;
					if (!data || !data.ok) {
						if (data && data.status === 'duplicate') {
							showDuplicate(data.error, data.accounts, 'Номер уже используется');
						}
						statusEl.className = 'prime-phoneauth-reg__status is-bad';
						statusEl.textContent = (data && (data.error || data.message)) || 'Не удалось начать подтверждение';
						setAccounts((data && data.accounts) || []);
						return;
					}
					showWait(data);
				})
				.catch(function () {
					verifyBtn.disabled = false;
					statusEl.className = 'prime-phoneauth-reg__status is-bad';
					statusEl.textContent = 'Ошибка сети';
				});
		});

		wait.querySelector('[data-role="back"]').addEventListener('click', function () {
			resetConfirm();
			verifyBtn.style.display = '';
		});
		wait.querySelector('[data-role="test"]').addEventListener('click', function () {
			var token = wait.getAttribute('data-token') || '';
			var testBtn = wait.querySelector('[data-role="test"]');
			testBtn.disabled = true;
			postForm(cfg.testUrl, { token: token }).then(function (data) {
				testBtn.disabled = false;
				if (data && data.status === 'confirmed') {
					tokenInp.value = token;
					markConfirmed();
					return;
				}
				statusEl.className = 'prime-phoneauth-reg__status is-bad';
				statusEl.textContent = (data && data.error) || 'Не подтвердилось';
			}).catch(function () { testBtn.disabled = false; });
		});

		if (phoneReady(phoneInput.value)) {
			lookupNow();
		}
	}

	function onReady() {
		document.querySelectorAll('.personal_enter .auth').forEach(function (root) {
			initTabs(root);
			bindPhoneForm(root);
		});
		initRegister();
		initProfile();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', onReady);
	} else {
		onReady();
	}
})();
