/**
 * Floaty Admin Scripts
 *
 * Handles client-side validation for admin settings.
 *
 * @package FloatyBookNowChat
 */

( function() {
	'use strict';

	/**
	 * Validate WhatsApp phone number format.
	 *
	 * @param {string} phone Phone number to validate.
	 * @return {boolean} True if valid.
	 */
	function isValidWhatsAppPhone( phone ) {
		// Remove any whitespace.
		var cleaned = phone.replace( /\s/g, '' );

		// Must be digits only (after stripping +).
		var digitsOnly = cleaned.replace( /^\+/, '' );

		// Check if only digits remain and length is reasonable (7-15 digits).
		return /^\d{7,15}$/.test( digitsOnly );
	}

	/**
	 * Show or hide validation warning.
	 *
	 * @param {HTMLElement} input Input element.
	 * @param {boolean} isValid Whether input is valid.
	 */
	function toggleValidationWarning( input, isValid ) {
		var warningId = input.id + '-warning';
		var existingWarning = document.getElementById( warningId );

		if ( isValid ) {
			if ( existingWarning ) {
				existingWarning.remove();
			}
			input.style.borderColor = '';
			return;
		}

		input.style.borderColor = '#d63638';

		if ( ! existingWarning ) {
			var warning = document.createElement( 'p' );
			warning.id = warningId;
			warning.className = 'description';
			warning.style.color = '#d63638';
			warning.textContent = 'Please use digits only (e.g., 5511999999999). Do not include +, spaces, or dashes.';
			input.parentNode.insertBefore( warning, input.nextSibling );
		}
	}

	/**
	 * Initialize phone validation on page load.
	 */
	function init() {
		var phoneInput = document.querySelector( 'input[name="vzflty_options[whatsapp_phone]"]' );

		if ( ! phoneInput ) {
			return;
		}

		// Validate on blur (when user leaves the field).
		phoneInput.addEventListener( 'blur', function() {
			var value = this.value.trim();

			// Allow empty values (field is optional in some modes).
			if ( value === '' ) {
				toggleValidationWarning( this, true );
				return;
			}

			toggleValidationWarning( this, isValidWhatsAppPhone( value ) );
		} );

		// Also validate on input for immediate feedback.
		phoneInput.addEventListener( 'input', function() {
			var value = this.value.trim();

			if ( value === '' ) {
				toggleValidationWarning( this, true );
				return;
			}

			// Only show warning if it looks like they're done typing (no recent input).
			if ( value.length >= 7 ) {
				toggleValidationWarning( this, isValidWhatsAppPhone( value ) );
			}
		} );
	}

	// Initialize when DOM is ready.
	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
