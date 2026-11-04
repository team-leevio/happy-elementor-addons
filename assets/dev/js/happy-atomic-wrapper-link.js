/**
 * Happy Addons — Wrapper Link, Elementor V4 (Atomic).
 *
 * Registers the feature with the Atomic runtime (happy-addons-atomic.js): the
 * runtime hands every element that has a link (frontend config) to WrapperLink.
 * Same behavior as the Classic handler (happy-addons.js): the click opens the
 * link through a temporary anchor, honoring "new window" and nofollow.
 * Frontend only, so the element can still be edited in the editor.
 */
(function (w, d) {
	'use strict';

	if (!w.HappyAtomic || 'function' !== typeof w.HappyAtomic.registerFeature) {
		return;
	}

	function WrapperLink(el, config) {
		var self = this;

		this.el = el;
		this.config = config || {};
		this.previousCursor = el.style.cursor;
		this.active = !!this.config.url && !(w.HappyAtomic.api && w.HappyAtomic.api.isEditor());

		if (!this.active) {
			return;
		}

		this.onClick = function (event) {
			// A link or button inside the element keeps its own action.
			var inner = event.target && event.target.closest ? event.target.closest('a[href], button') : null;

			if (inner && inner !== self.el && self.el.contains(inner)) {
				return;
			}

			self.open(event);
		};

		el.style.cursor = 'pointer';
		el.addEventListener('click', this.onClick);
	}

	WrapperLink.prototype.open = function () {
		var config = this.config;
		var anchor = d.createElement('a');

		anchor.href = config.url;
		anchor.target = config.is_external ? '_blank' : '_self';
		anchor.rel = (config.nofollow ? 'nofollow noreferrer' : '') + (config.is_external ? ' noopener' : '');
		anchor.style.display = 'none';

		d.body.appendChild(anchor);
		anchor.click();
		d.body.removeChild(anchor);
	};

	WrapperLink.prototype.destroy = function () {
		if (!this.active) {
			return;
		}

		this.el.removeEventListener('click', this.onClick);
		this.el.style.cursor = this.previousCursor;
	};

	w.HappyAtomic.registerFeature({
		key: 'wrapper-link',
		type: [],
		Runtime: WrapperLink
	});
}(window, document));
