let justConnectedKey = 'TRV_PLUGIN_JUST_CONNECTED';

(function ($) {
	'use strict';

	var handlers = {
		'refresh': function () {
			if (localStorage) {
				localStorage.setItem(justConnectedKey, '1');
			}
			setLoading(true, true);
			renderAdminPanel();
		},
		'reset-authentication': function () {
			return $.ajax({
				url: trv_config.apiUrl + 'truvisibility/v1/reset-auth',
				method: 'POST',
				beforeSend: function (xhr) {
					xhr.setRequestHeader('X-WP-Nonce', trv_config.apiNonce);
				}
			})
				.then(function () {
					trv_config.accessToken = null;
				});
		}
	};

	window.addEventListener(
		'message',
		function (event) {
			if (event.data && event.data.command) {
				var command = event.data.command;
				if (handlers[command]) {
					var handler = handlers[command];
					handler(event.data);
				}
			}
		}
	);	
})(jQuery);

jQuery(document).ready(function() {
    jQuery("body").tooltip({ selector: '[data-bs-toggle=tooltip]' });
});

function redirectTo(url) {
	setLoading(true, false);
	window.open(url, '', 'popup');
}

function startConnectionFlow() {
	redirectTo(trv_config.authLink)
}

function copyToClipboard(value) {
	if (!navigator.clipboard) {
		this.fallbackCopy(value);
		return;
	}

	navigator.clipboard.writeText(value).then(() => {
		// do nothing
	}, err => {
		this.fallbackCopy(value);
	});
}

function fallbackCopy(value) {
	const textArea = document.createElement("textarea");
	textArea.innerHTML = value;
	textArea.style.position = "fixed";
	document.body.appendChild(textArea);
	textArea.focus();
	textArea.select();
	document.execCommand("copy");

	document.body.removeChild(textArea);
}

function copyClick(event, text) {
	copyToClipboard(text);

	const el = event.currentTarget;
	var done = el.getElementsByClassName('done')[0];
	done.classList.add('show');
	setTimeout(function () {
		done.classList.remove('show');
	}, 700);
	event.stopPropagation();
}

function chatPopupGenerator(id) {
	copyClick(event, `[truvisibility type="chat" id="${id}"]`);
}

function chatEmbeddedGenerator(id) {
	copyClick(event, `[truvisibility type="chat" id="${id}" embedded="true"]`);
}

function formsGenerator(id) {
	copyClick(event, `[truvisibility type="form" id="${id}"]`);
}

function closeModal(id) {
	jQuery('#' + id).modal('hide');
}

function renderAdminPanel() {
	setLoading(true, true);
	jQuery.ajax({
		url: ajaxurl,
		type: 'GET',
		data: {
			action: 'render_admin_panel'
		}
	}).then(function (data) {
		jQuery("#wpbody-content").html(data);
		var justConnected = localStorage && (!!localStorage.getItem(justConnectedKey));

		if (justConnected) {
			localStorage.removeItem(justConnectedKey);

			jQuery("#trv-just-connected-message").css("display", "flex");;
			setTimeout(function () {
				jQuery("#trv-just-connected-message").css("display", "none");;
			}, 3000);
		}
	});
}

function disconnect() {
	setLoading(true, false);
	closeModal('disconnect-modal');
	jQuery.ajax({
		url: trv_config.apiUrl + 'truvisibility/v1/reset-auth',
		method: 'POST',
		beforeSend: function (xhr) {
			xhr.setRequestHeader('X-WP-Nonce', trv_config.apiNonce);
		}
	}).then(function () {
		trv_config.accessToken = null;
		renderAdminPanel();
	});
}

function setLoading(state, isGlobal) {
	jQuery("#trv-spinner").toggleClass("hiding", !state);
	jQuery("#trv-spinner").toggleClass("global", isGlobal);
}

function toggleGdpr(e) {
	document.getElementById('privacyInput').disabled = !e.target.checked;
	enableSaveGdpr();
}

function saveGdprSettings() {
	setLoading(true, false);
	
	trv_config.gdprEnabled = jQuery('#chkGdprEnabled').prop('checked');
	trv_config.gdprPrivacyUrl = jQuery('#privacyInput').val();

	closeModal('settings-modal');
	jQuery.ajax({
		url: trv_config.apiUrl + 'truvisibility/v1/save-gdpr-settings',
		method: 'POST',
		contentType: 'application/json; charset=utf-8',
		dataType: "json",
		data: JSON.stringify({ 
			'gdpr_enabled': trv_config.gdprEnabled, 
			'gdpr_privacy_url': trv_config.gdprPrivacyUrl
		}),		
		beforeSend: function (xhr) {
			xhr.setRequestHeader('X-WP-Nonce', trv_config.apiNonce);
		}
	}).always(function() {
		setLoading(false, false);
	});
}

function enableSaveGdpr() {
	jQuery('#saveGdprBtn').removeAttr('disabled');
}

jQuery('#settings-modal').on('hidden.bs.modal', function (e) {
	jQuery('#chkGdprEnabled').prop('checked', trv_config.gdprEnabled);
	jQuery('#privacyInput').val(trv_config.gdprPrivacyUrl);
	jQuery('#saveGdprBtn').prop('disabled', 'disabled');
})