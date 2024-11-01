(function () {
	var shippingTypeSelectField = document.querySelector(
		".js-wc-dpd-shipping-type-field"
	);

	var notificationField = document.querySelector(
		".js-wc-dpd-notification-field"
	);

	var notificationFieldParent = parents(
		notificationField,
		"tr, .js-wc-dpd-notification-field-row"
	);

	notificationFieldParent =
		notificationFieldParent[0] !== undefined
			? notificationFieldParent[0]
			: undefined;

	var originalDisplayValue = notificationFieldParent.style.display;

	// Add data attribue if the notification is required for the shipment type
	if (
		typeof shippingTypeSelectField != "undefined" &&
		shippingTypeSelectField != null
	) {
		for (
			let index = 0;
			index < shippingTypeSelectField.options.length;
			index++
		) {
			var option = shippingTypeSelectField.options[index];

			if (
				wc_dpd_settings.required_notifications_shipping_keys.indexOf(
					option.value
				) >= 0
			) {
				option.setAttribute("data-notification-required", true);
			} else {
				option.setAttribute("data-notification-required", false);
			}
		}
	}

	/**
	 * Toggle notification field on load
	 */
	toggleNotificationField();

	/**
	 * On shipment type change toggle notification option
	 */
	shippingTypeSelectField.addEventListener("change", function () {
		toggleNotificationField(true);
	});

	/**
	 * Hide/Display notification field
	 */
	function toggleNotificationField(reset) {
		if (getSelectedOptionNotificationSetting()) {
			hideNotificationField();
		} else {
			showNotificationField(reset);
		}
	}

	/**
	 * Show notification field
	 */
	function showNotificationField(reset = false) {
		if (reset) {
			notificationField.checked = false;
		}
		notificationFieldParent.style.display = originalDisplayValue;
	}

	/**
	 * Hide notification field
	 */
	function hideNotificationField() {
		notificationField.checked = true;
		notificationFieldParent.style.display = "none";
	}

	/**
	 * Get the notification data attribue value from selected option
	 */
	function getSelectedOptionNotificationSetting() {
		if (
			typeof shippingTypeSelectField == "undefined" ||
			shippingTypeSelectField == null
		) {
			return false;
		}

		var selected = shippingTypeSelectField.options[
			shippingTypeSelectField.selectedIndex
		].getAttribute("data-notification-required");

		// Convert to boolean
		return selected === "true";
	}

	/**
	 * Get element parents
	 */
	function parents(el, selector) {
		const parents = [];

		while ((el = el.parentNode) && el !== document) {
			if (!selector || el.matches(selector)) parents.unshift(el);
		}

		return parents;
	}
})();
