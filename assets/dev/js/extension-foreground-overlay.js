;(function( $ ) {
	$( window ).on( 'elementor/frontend/init', function() {
		var ForegroundOverlayHandler = elementorModules.frontend.handlers.Base.extend( {
			bindEvents: function() {
				this.retries = 0;
				this.applyOverlayVars();
			},

			onElementChange: function() {
				this.retries = 0;
				this.applyOverlayVars();
			},

			getSetting: function( key ) {
				var value;

				try {
					value = this.getElementSettings( key );
				} catch ( e ) {}

				if ( undefined === value || null === value ) {
					value = this.getItems( this.$element.data( 'settings' ) || {}, key );
				}

				return value;
			},

			getStyleTag: function() {
				var id = this.$element.data( 'id' );

				if ( ! id ) {
					return null;
				}

				var tagId = 'ha-foreground-overlay-' + id,
					tag = document.getElementById( tagId );

				if ( ! tag ) {
					tag = document.createElement( 'style' );
					tag.id = tagId;
					document.head.appendChild( tag );
				}

				return tag;
			},

			applyOverlayVars: function() {
				var areas = {
						left: [ 'start', 0, 5 ],
						center: [ 'center', 5, 95 ],
						right: [ 'end', 95, 100 ]
					},
					self = this,
					resolved = true,
					css = '';

				$.each( areas, function( position, area ) {
					var from = self.getSetting( '_ha_foreground_overlay_' + area[ 0 ] + '_area.sizes.from' ),
						to = self.getSetting( '_ha_foreground_overlay_' + area[ 0 ] + '_area.sizes.to' );

					if ( isNaN( from ) || isNaN( to ) ) {
						resolved = false;
						return;
					}

					css += '--fg-' + position + '-start: ' + from + '%; --fg-' + position + '-end: ' + to + '%;';
				} );

			if ( ! resolved ) {
				if ( this.retries < 20 ) {
					this.retries++;
					setTimeout( function() {
						self.applyOverlayVars();
					}, 500 );
				}
				return;
			}

			this.$element.addClass( 'ha-foreground-overlay-active' );

			var tag = this.getStyleTag(),
				selector = '.elementor-element-' + this.$element.data( 'id' );

			if ( tag ) {
				tag.textContent = selector + ' { ' + css + ' } ' + selector + ' > .elementor-element-overlay { z-index: 10000; }';
			}
			}
		} );

		elementorFrontend.hooks.addAction( 'frontend/element_ready/container', function( $scope ) {
			elementorFrontend.elementsHandler.addHandler( ForegroundOverlayHandler, { $element: $scope } );
		} );
	} );
}( jQuery ) );
