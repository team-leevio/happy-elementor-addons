(function ($, w) {
	"use strict";

	var $window = $(w);

	var LiquidGlass = {
		presets: [
			"ha-lg-aqua-warp",
			// "ha-lg-neutral-frost",
			"ha-lg-bubble-glass",
			"ha-lg-bubble-lens",
			
			// "ha-lg-quartz-stream",
			
			"ha-lg-abyss-glass",
			"ha-lg-diamond-dust",
			"ha-lg-distorted-prism",
			"ha-lg-drop-water",
			"ha-lg-fine-crystal",
			"ha-lg-liquid-lens",
			"ha-lg-frozen-glass",
			// "ha-lg-frozen-window",
			"ha-lg-glass-fiber",
			"ha-lg-tempest-glass",
			"ha-lg-thai-frost",
			"ha-lg-thai-frost-2",
			// "ha-lg-goo",
			// "ha-lg-gooey",
			"ha-lg-turbulent-warp",
			"ha-lg-holographic-glass",
			"ha-lg-lateral-surge",
			"ha-lg-ice-crystal",
			"ha-lg-jelly-blob",
			"ha-lg-jelly-surface",
			// "ha-lg-lens-glass",
			// "ha-lg-fog-drift",
			"ha-lg-macos-glass",
			"ha-lg-magnifier-lens",
			"ha-lg-melted-glass",
			// "ha-lg-mercury-liquid",
			"ha-lg-micro-frost",
			"ha-lg-lava-flow",
			"ha-lg-ocean-current",
			"ha-lg-prism-cut",
			"ha-lg-rain-streak",
			"ha-lg-gentle-wave",
			"ha-lg-ultra-lens",
			// "ha-lg-vapor-lens",
			"ha-lg-cascade-ripple",
			"ha-lg-viscous-gel",
			// "ha-lg-visionos-glass",
			// "ha-lg-water-ripple",
			"ha-lg-wavy-water",
			// "ha-lg-wind-flow",
			"ha-lg-custom"
		],

		presetParams: {
			"gentle-wave":      { frequency: "0.018 0.022", scale: 75 },
			"abyss-glass":       { frequency: "0.003 0.005", scale: 135 },
			// "quartz-stream":     { frequency: "0.008 0.006", scale: 105 },
			"turbulent-warp": { frequency: "0.018 0.012", scale: 195 },
			// "fog-drift":        { frequency: "0.012 0.008", scale: 88 },
			"cascade-ripple":   { frequency: "0.002 0.009", scale: 148 },
			"lateral-surge":    { frequency: "0.009 0.002", scale: 125 },
			// "neutral-frost":    { frequency: "0.005 0.007", scale: 110 },
			"tempest-glass":    { frequency: "0.014 0.022", scale: 130 },
			"lava-flow":        { frequency: "0.004 0.018", scale: 170 },
			"magnifier-lens":   { frequency: "0.001 0.001", scale: 220 },
			"micro-frost":      { frequency: "0.025 0.025", scale: 45 },
			"rain-streak":      { frequency: "0.002 0.012", scale: 180 },
			// "wind-flow":        { frequency: "0.012 0.002", scale: 180 },
			"fine-crystal":     { frequency: "0.03 0.03", scale: 25 },
			"bubble-lens":      { frequency: "0.002 0.002", scale: 280 },
			"aqua-warp":        { frequency: "0.009 0.018", scale: 150 },
			"ocean-current":    { frequency: "0.018 0.009", scale: 150 },
			"prism-cut":        { frequency: "0.04 0.01", scale: 70 },
			"glass-fiber":      { frequency: "0.01 0.04", scale: 70 },
			"jelly-blob":       { frequency: "0.002 0.006", scale: 240 },
			"viscous-gel":      { frequency: "0.006 0.002", scale: 240 },
			"diamond-dust":     { frequency: "0.05 0.05", scale: 12 },
			"ultra-lens":       { frequency: "0.0015 0.0015", scale: 320 },
			"frozen-glass":     { frequency: "0.015 0.008", scale: 135 },
			"liquid-lens":      { frequency: "0.0018 0.0018", scale: 220 }
		},

		customFilterMarkup: {
			

			// "goo": '<feGaussianBlur in="SourceGraphic" stdDeviation="2" result="blur"></feGaussianBlur>' +
			// 	'<feColorMatrix in="blur" type="matrix" result="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 16 -10"></feColorMatrix>' +
			// 	'<feComposite in="matrix" operator="atop"></feComposite>',

			

			// "lens-glass": '<feTurbulence type="fractalNoise" baseFrequency="0.005" numOctaves="1" seed="1" result="noise"></feTurbulence>' +
			// 	'<feDisplacementMap in="SourceGraphic" in2="noise" scale="60"></feDisplacementMap>',

			// "gooey": '<feGaussianBlur in="SourceGraphic" stdDeviation="8" result="blur"></feGaussianBlur>' +
			// 	'<feColorMatrix in="blur" type="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 20 -10"></feColorMatrix>' +
			// 	'<feBlend in="SourceGraphic"></feBlend>',

			"macos-glass": '<feTurbulence type="fractalNoise" baseFrequency="0.004" numOctaves="2" seed="10" result="noise"></feTurbulence>' +
				'<feGaussianBlur in="noise" stdDeviation="0.6" result="blur"></feGaussianBlur>' +
				'<feDisplacementMap in="SourceGraphic" in2="blur" scale="12"></feDisplacementMap>' +
				'<feGaussianBlur stdDeviation="0.3"></feGaussianBlur>',

			"melted-glass": '<feGaussianBlur in="SourceGraphic" stdDeviation="4" result="blur"></feGaussianBlur>' +
				'<feColorMatrix in="blur" type="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 25 -12"></feColorMatrix>',

		

			// "water-ripple": '<feTurbulence type="turbulence" baseFrequency="0.015" numOctaves="3" seed="3" result="noise"></feTurbulence>' +
			// 	'<feDisplacementMap in="SourceGraphic" in2="noise" scale="25"></feDisplacementMap>',

			"holographic-glass":
				'<feTurbulence type="fractalNoise" baseFrequency="0.012" numOctaves="3" seed="11" result="noise"></feTurbulence>' +
				'<feDisplacementMap in="SourceGraphic" in2="noise" scale="30"></feDisplacementMap>' +
				'<feColorMatrix type="saturate" values="1.8"></feColorMatrix>',

			"bubble-glass":
				'<feTurbulence type="fractalNoise" baseFrequency="0.002" numOctaves="1" seed="15" result="noise"></feTurbulence>' +
				'<feGaussianBlur in="noise" stdDeviation="4" result="blur"></feGaussianBlur>' +
				'<feDisplacementMap in="SourceGraphic" in2="blur" scale="250"></feDisplacementMap>',

			// "frozen-window":
			// 	'<feTurbulence type="fractalNoise" baseFrequency="0.04" numOctaves="5" seed="21" result="noise"></feTurbulence>' +
			// 	'<feGaussianBlur in="noise" stdDeviation="1"></feGaussianBlur>' +
			// 	'<feDisplacementMap in="SourceGraphic" in2="noise" scale="12"></feDisplacementMap>',

			"jelly-surface":
				'<feTurbulence type="fractalNoise" baseFrequency="0.003" numOctaves="2" seed="4" result="noise"></feTurbulence>' +
				'<feGaussianBlur in="noise" stdDeviation="5" result="goo"></feGaussianBlur>' +
				'<feDisplacementMap in="SourceGraphic" in2="goo" scale="180"></feDisplacementMap>',

			"wavy-water":
				'<feTurbulence type="turbulence" baseFrequency="0.008" numOctaves="4" seed="18" result="noise"></feTurbulence>' +
				'<feDisplacementMap in="SourceGraphic" in2="noise" scale="50"></feDisplacementMap>',

			"ice-crystal":
				'<feTurbulence type="fractalNoise" baseFrequency="0.035" numOctaves="6" seed="9" result="noise"></feTurbulence>' +
				'<feDisplacementMap in="SourceGraphic" in2="noise" scale="18"></feDisplacementMap>' +
				'<feGaussianBlur stdDeviation="0.2"></feGaussianBlur>',

			// "vapor-lens":
			// 	'<feTurbulence type="fractalNoise" baseFrequency="0.004" numOctaves="3" seed="25" result="noise"></feTurbulence>' +
			// 	'<feGaussianBlur in="noise" stdDeviation="2" result="mist"></feGaussianBlur>' +
			// 	'<feDisplacementMap in="SourceGraphic" in2="mist" scale="140"></feDisplacementMap>',

			"distorted-prism":
				'<feTurbulence type="fractalNoise" baseFrequency="0.03" numOctaves="2" seed="13" result="noise"></feTurbulence>' +
				'<feDisplacementMap in="SourceGraphic" in2="noise" scale="40"></feDisplacementMap>' +
				'<feColorMatrix type="saturate" values="2"></feColorMatrix>',

			// "mercury-liquid":
			// 	'<feTurbulence type="fractalNoise" baseFrequency="0.002" numOctaves="1" seed="40" result="noise"></feTurbulence>' +
			// 	'<feGaussianBlur in="noise" stdDeviation="8" result="smooth"></feGaussianBlur>' +
			// 	'<feDisplacementMap in="SourceGraphic" in2="smooth" scale="300"></feDisplacementMap>',

			// "visionos-glass":
			// 	'<feTurbulence type="fractalNoise" baseFrequency="0.0035 0.0035" numOctaves="2" seed="42" result="noise"></feTurbulence>' +
			// 	'<feGaussianBlur in="noise" stdDeviation="1.5" result="blur"></feGaussianBlur>' +
			// 	'<feDisplacementMap in="SourceGraphic" in2="blur" scale="60" xChannelSelector="R" yChannelSelector="G"></feDisplacementMap>' +
			// 	'<feGaussianBlur stdDeviation="0.2"></feGaussianBlur>',

			"thai-frost":
				'<feTurbulence type="fractalNoise" baseFrequency="0.3 0.3" numOctaves="2" seed="92" result="noise"></feTurbulence>' +
				'<feGaussianBlur in="noise" stdDeviation="0.02" result="blur"></feGaussianBlur>' +
				'<feDisplacementMap in="SourceGraphic" in2="blur" scale="50" xChannelSelector="R" yChannelSelector="G"></feDisplacementMap>',

			"thai-frost-2":
				'<feTurbulence type="fractalNoise" baseFrequency="1.012 1.012" numOctaves="1" seed="9000" result="noise"></feTurbulence>' +
				'<feGaussianBlur in="noise" stdDeviation="0.1" result="blurred"></feGaussianBlur>' +
				'<feDisplacementMap in="SourceGraphic" in2="blurred" scale="77" xChannelSelector="R" yChannelSelector="G"></feDisplacementMap>',

			"drop-water":
				'<feTurbulence type="fractalNoise" baseFrequency="0.003 0.007" numOctaves="1" result="turbulence"></feTurbulence>' +
				'<feDisplacementMap in="SourceGraphic" in2="turbulence" scale="200" xChannelSelector="R" yChannelSelector="G"></feDisplacementMap>',
		},

		allPresetClasses: function () {
			return this.presets;
		},

		init: function () {
			elementorFrontend.hooks.addAction("frontend/element_ready/global", this.onElementReady.bind(this));
		},

		onElementReady: function ($scope) {
			if (!$scope.hasClass('ha-lg-yes')) {
				this.cleanupElement($scope);
				return;
			}

			var matchedPresets = this.getLiquidGlassPresets($scope);

			if (!matchedPresets.length) {
				return;
			}

			for (var i = 0; i < matchedPresets.length; i++) {
				var presetKey = matchedPresets[ i ];

				if (presetKey === 'ha-lg-custom') {
					this.renderCustomFilter($scope);
				} else {
					$scope.css('backdrop-filter', '');
					$scope.css('-webkit-backdrop-filter', '');
					this.renderSVGFilter(presetKey);
				}
			}
		},

		cleanupElement: function ($scope) {
			$scope.css('backdrop-filter', '');
			$scope.css('-webkit-backdrop-filter', '');

			var elementId = $scope.data('id') || $scope.attr('id') || $scope.attr('data-ha-lg-uid');
			if (elementId) {
				$('.ha-lg-svg-custom-' + elementId).remove();
			}
		},

		renderCustomFilter: function ($scope) {
			var el = $scope[0];
			var style = window.getComputedStyle(el);

			var freqX = this.trimCSSVar(style.getPropertyValue('--ha-lg-frequency-x')) || '0.006';
			var freqY = this.trimCSSVar(style.getPropertyValue('--ha-lg-frequency-y')) || '0.006';
			var scale = this.trimCSSVar(style.getPropertyValue('--ha-lg-scale')) || '80';
			var octaves = this.trimCSSVar(style.getPropertyValue('--ha-lg-octaves')) || '2';
			var seed = this.trimCSSVar(style.getPropertyValue('--ha-lg-seed')) || '92';
			var xChannel = this.trimCSSVar(style.getPropertyValue('--ha-lg-x-channel')) || 'R';
			var yChannel = this.trimCSSVar(style.getPropertyValue('--ha-lg-y-channel')) || 'G';
			var stdDeviation = this.trimCSSVar(style.getPropertyValue('--ha-lg-std-deviation')) || '2';

			var elementId = $scope.data('id') || $scope.attr('id');
			if (!elementId) {
				elementId = $scope.attr('data-ha-lg-uid');
				if (!elementId) {
					elementId = 'el-' + Math.random().toString(36).substr(2, 9);
					$scope.attr('data-ha-lg-uid', elementId);
				}
			}

			var filterId = 'ha-glass-distortion-custom-' + elementId;
			var svgClass = 'ha-lg-svg-custom-' + elementId;

			$('.' + svgClass).remove();

			var filterInner =
				'<feTurbulence type="fractalNoise" baseFrequency="' + freqX + ' ' + freqY + '" numOctaves="' + octaves + '" seed="' + seed + '" result="noise" />' +
				'<feGaussianBlur in="noise" stdDeviation="' + stdDeviation + '" result="blurred" />' +
				'<feDisplacementMap in="SourceGraphic" in2="blurred" scale="' + scale + '" xChannelSelector="' + xChannel + '" yChannelSelector="' + yChannel + '" />';

			var svgMarkup =
				'<svg class="' + svgClass + '" xmlns="http://www.w3.org/2000/svg" width="0" height="0" style="position:absolute;overflow:hidden">' +
				'<defs>' +
				'<filter id="' + filterId + '" x="0%" y="0%" width="100%" height="100%">' +
				filterInner +
				'</filter>' +
				'</defs>' +
				'</svg>';

			$("body").append(svgMarkup);

			console.log('Applied custom liquid glass filter with ID:', filterId);
			console.log('$scope:', $scope);

			$scope.css('backdrop-filter', 'blur(var(--ha-lg-blur, 8px)) url(#' + filterId + ')');
			$scope.css('-webkit-backdrop-filter', 'blur(var(--ha-lg-blur, 8px)) url(#' + filterId + ')');
		},

		trimCSSVar: function (val) {
			return val ? val.trim() : '';
		},

		getLiquidGlassPresets: function ($scope) {
			var found = [];
			var allClasses = this.allPresetClasses();

			for (var i = 0; i < allClasses.length; i++) {
				var cls = allClasses[ i ];

				if ($scope.hasClass(cls)) {
					found.push(cls);
					continue;
				}

				var $children = $scope.find("." + cls);
				if ($children.length) {
					found.push(cls);
				}
			}

			var unique = [];
			for (var j = 0; j < found.length; j++) {
				if (unique.indexOf(found[ j ]) === -1) {
					unique.push(found[ j ]);
				}
			}

			return unique;
		},

		renderSVGFilter: function (presetKey) {
			var filterId = this.getFilterId(presetKey);
			if (!filterId) {
				return;
			}

			var svgClass = "ha-lg-svg-" + presetKey.replace(/ha-lg-/, "");

			if ($("." + svgClass).length) {
				return;
			}

			var filterInner = this.getFilterInnerMarkup(presetKey);
			if (!filterInner) {
				return;
			}

			var svgMarkup =
				'<svg class="' + svgClass + '" xmlns="http://www.w3.org/2000/svg" width="0" height="0" style="position:absolute;overflow:hidden">' +
				'<defs>' +
				'<filter id="' + filterId + '" x="0%" y="0%" width="100%" height="100%">' +
				filterInner +
				'</filter>' +
				'</defs>' +
				'</svg>';

			$("body").append(svgMarkup);
		},

		getFilterId: function (presetKey) {
			var match = presetKey.match(/^ha-lg-(.+)$/);
			return match ? "ha-glass-distortion-" + match[ 1 ] : null;
		},

		getFilterInnerMarkup: function (presetKey) {
			var match = presetKey.match(/^ha-lg-(.+)$/);
			if (!match) {
				return null;
			}

			var name = match[ 1 ];

			if (this.customFilterMarkup[ name ]) {
				return this.customFilterMarkup[ name ];
			}

			var params = this.presetParams[ name ];
			if (params) {
				return '<feTurbulence type="fractalNoise" baseFrequency="' + params.frequency + '" numOctaves="2" seed="92" result="noise" />' +
					'<feGaussianBlur in="noise" stdDeviation="2" result="blurred" />' +
					'<feDisplacementMap in="SourceGraphic" in2="blurred" scale="' + params.scale + '" xChannelSelector="R" yChannelSelector="G" />';
			}

			return null;
		}
	};

	$window.on("elementor/frontend/init", function () {
		LiquidGlass.init();
	});

})(jQuery, window);
