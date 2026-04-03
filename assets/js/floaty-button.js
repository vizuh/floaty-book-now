(function () {
	if (!window.VZFLTY_SETTINGS) {
		return;
	}

	const settings = window.VZFLTY_SETTINGS;
	const containerID = 'vzflty-button-container';
	const i18n = settings.i18n || {};

	// Mode Resolution
	const buttonMode = settings.mode === 'lead_capture' ? 'lead_capture' : (settings.mode === 'whatsapp' ? 'whatsapp' : (settings.buttonTemplate === 'whatsapp' ? 'whatsapp' : 'custom'));
	const isWhatsApp = buttonMode === 'whatsapp';
	const isLeadCapture = buttonMode === 'lead_capture';
	const isIframeModal = !isWhatsApp && !isLeadCapture && settings.actionType === 'iframe_modal';
	const shouldRenderModal = isLeadCapture || isIframeModal;

	// Labels
	const whatsappLabel = i18n.whatsappLabel || 'WhatsApp';
	const defaultButtonLabel = i18n.defaultButtonLabel || 'Book now';
	const buttonLabel = settings.buttonLabel || (isWhatsApp ? whatsappLabel : defaultButtonLabel);
	const positionClass = settings.position ? `vzflty-position-${settings.position}` : 'vzflty-position-bottom_right';

	let container = document.getElementById(containerID);

	if (!container) {
		container = document.createElement('div');
		container.id = containerID;
		document.body.appendChild(container);
	}

	// ---------------------------------------------------------
	// Phone Masker (Brazil)
	// ---------------------------------------------------------
	const PhoneMasker = {
		mask: function (value) {
			value = value.replace(/\D/g, '');
			if (value.length > 11) {
				value = value.slice(0, 11);
			}
			if (value.length > 2) {
				const ddd = value.substring(0, 2);
				const restOfNumber = value.substring(2);
				value = ddd + "9" + restOfNumber.substring(restOfNumber.length > 0 ? 1 : 0);
			}
			if (value.length > 0) {
				// Format: (XX) X XXXX-XXXX
				if (value.length <= 2) {
					value = `(${value}`;
				} else if (value.length <= 6) {
					value = `(${value.substring(0, 2)}) ${value.substring(2)}`;
				} else if (value.length <= 10) {
					value = `(${value.substring(0, 2)}) ${value.substring(2, 6)}-${value.substring(6)}`; // (XX) XXXX-XXXX (Landline mostly)
				} else {
					value = `(${value.substring(0, 2)}) ${value.substring(2, 3)} ${value.substring(3, 7)}-${value.substring(7)}`; // Mobile
				}
			}
			return value;
		}
	};

	// ---------------------------------------------------------
	// Render Button
	// ---------------------------------------------------------
	function getTriggerAttributes() {
		const attributes = ['data-clicktrail-cta="1"'];

		if (isWhatsApp) {
			attributes.push('data-chat-trigger="1"');
		} else {
			attributes.push('data-booking-trigger="1"');
		}

		return attributes.join(' ');
	}

	function renderButton() {
		const triggerAttributes = getTriggerAttributes();

		if (isWhatsApp && settings.buttonTemplate === 'whatsapp') {
			container.innerHTML = `
				<a href="#" class="vzflty-whatsapp-btn ${positionClass}" ${triggerAttributes} aria-label="${buttonLabel}">
					<span class="vzflty-whatsapp-icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" preserveAspectRatio="xMidYMid meet" aria-hidden="true" focusable="false"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-28.4l-6.7-4.6-69.8 18.3 18.6-68.1-4.4-6.9c-19.7-31.3-30.2-68-30.2-106.1 0-101.9 82.9-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7 .9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>
					</span>
				</a>
			`;
		} else {
			container.innerHTML = `
				<button class="vzflty-button ${positionClass}" ${triggerAttributes} type="button">
					${buttonLabel}
				</button>
			`;
		}

		if (shouldRenderModal) {
			const modalHTML = `
				<div class="vzflty-modal-backdrop" hidden></div>
				<div class="vzflty-modal" hidden>
					<button class="vzflty-modal-close" type="button" aria-label="${i18n.modalCloseLabel || 'Close'}" title="${i18n.modalCloseLabel || 'Close'}">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
					</button>
					<div class="vzflty-modal-content"></div>
				</div>
			`;
			container.insertAdjacentHTML('beforeend', modalHTML);
		}
	}

	renderButton();

	// ---------------------------------------------------------
	// Logic
	// ---------------------------------------------------------
	const button = container.querySelector('.vzflty-button, .vzflty-whatsapp-btn');
	const backdrop = container.querySelector('.vzflty-modal-backdrop');
	const modal = container.querySelector('.vzflty-modal');
	const modalContent = container.querySelector('.vzflty-modal-content');
	const closeBtn = container.querySelector('.vzflty-modal-close');

	function getValuesFromSettings() {
		const cleanPhone = (settings.whatsappPhone || '').replace(/[^0-9]/g, '');

		return {
			phone: cleanPhone,
			message: settings.whatsappMessage || '',
		};
	}

	function openWhatsApp() {
		const result = getValuesFromSettings();
		if (!result.phone) return;

		const message = encodeURIComponent(result.message);
		const url = `https://wa.me/${result.phone}?text=${message}`;

		// Update lead if just submitted? 
		// We handle redirection logic separately in Lead Capture flow.
		// Direct click logic:
		window.open(url, '_blank');
	}

	function openLink() {
		if (!settings.linkUrl) return;
		const target = settings.linkTarget || '_blank';
		window.open(settings.linkUrl, target);
	}

	function openIframeModal() {
		if (!modalContent || !settings.iframeUrl) return;
		modalContent.innerHTML = `<iframe class="vzflty-modal-iframe" src="${settings.iframeUrl}" frameborder="0"></iframe>`;
		showModal();
	}

	function renderLeadForm() {
		if (!modalContent) return;
		const fields = settings.leadCapture.fields;
		let formHTML = `<form class="vzflty-lead-form">`;

		if (i18n.formTitle) {
			formHTML += `<h3>${i18n.formTitle}</h3>`;
		}

		if (fields.name) {
			formHTML += `<div class="vzflty-field"><input type="text" name="name" placeholder="${i18n.formNamePlaceholder}" required></div>`;
		}

		if (fields.email) {
			formHTML += `<div class="vzflty-field"><input type="email" name="email" placeholder="${i18n.formEmailPlaceholder}"></div>`;
		}

		if (fields.phone) {
			formHTML += `<div class="vzflty-field"><input type="tel" name="phone" placeholder="${i18n.formPhonePlaceholder}" required></div>`;
		}

		formHTML += `<button type="submit" class="vzflty-submit-btn">${i18n.formSubmitLabel}</button>`;
		formHTML += `<div class="vzflty-form-message"></div>`;
		formHTML += `</form>`;

		modalContent.innerHTML = formHTML;

		// Attach listeners
		const form = modalContent.querySelector('form');
		const phoneInput = form.querySelector('input[name="phone"]');

		if (phoneInput) {
			phoneInput.addEventListener('input', (e) => {
				e.target.value = PhoneMasker.mask(e.target.value);
			});
		}

		form.addEventListener('submit', handleFormSubmit);

		showModal();
	}

	async function handleFormSubmit(e) {
		e.preventDefault();
		const form = e.target;
		const submitBtn = form.querySelector('button[type="submit"]');
		const messageDiv = form.querySelector('.vzflty-form-message');

		submitBtn.disabled = true;
		submitBtn.innerText = '...';

		const formData = new FormData(form);
		const data = {
			name: formData.get('name'),
			email: formData.get('email'),
			phone: formData.get('phone'),
			wpp_number: (getValuesFromSettings().phone || '')
		};

		try {
			const response = await fetch(settings.apiUrl, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-WP-Nonce': settings.nonce
				},
				body: JSON.stringify(data)
			});

			const result = await response.json();

			if (response.ok) {
				messageDiv.innerText = i18n.formSuccessMessage;
				messageDiv.classList.add('success');

				setTimeout(() => {
					closeModal();
					// Redirect Logic
					const redirectType = settings.leadCapture.redirectType;
					if (redirectType === 'whatsapp') {
						openWhatsApp();
					} else if (redirectType === 'link') {
						openLink();
					}
					// If 'none', we stay closed.
				}, 1500);
			} else {
				throw new Error(result.message || 'Error');
			}
		} catch (err) {
			console.error(err);
			messageDiv.innerText = i18n.formErrorMessage;
			messageDiv.classList.add('error');
			submitBtn.disabled = false;
			submitBtn.innerText = i18n.formSubmitLabel;
		}
	}

	function showModal() {
		if (!backdrop || !modal) return;
		backdrop.hidden = false;
		modal.hidden = false;

		// Apply positioning class
		if (settings.position && settings.position.includes('left')) {
			modal.classList.add('vzflty-modal-left');
		} else {
			modal.classList.remove('vzflty-modal-left');
		}

		document.body.style.overflow = 'hidden';
	}

	function closeModal() {
		if (!backdrop || !modal || !modalContent) return;
		backdrop.hidden = true;
		modal.hidden = true;
		modalContent.innerHTML = ''; // Clear content (iframe/form)
		document.body.style.overflow = '';
	}

	// ---------------------------------------------------------
	// Main Click Handler
	// ---------------------------------------------------------
	if (button) {
		button.addEventListener('click', function (event) {
			event.preventDefault(); // Prevent default link behavior initially

			if (isLeadCapture) {
				renderLeadForm();
			} else if (isWhatsApp) {
				openWhatsApp();
			} else {
				if (settings.actionType === 'iframe_modal') {
					openIframeModal();
				} else {
					openLink();
				}
			}
		});
	}

	if (backdrop) backdrop.addEventListener('click', closeModal);
	if (closeBtn) closeBtn.addEventListener('click', closeModal);

	window.VZFLTY_showButton = function () { if (button) button.style.display = ''; };
	window.VZFLTY_hideButton = function () { if (button) button.style.display = 'none'; };
})();
