;(function ($) {
    'use strict';

    var $window = $(window);

    var HappyImageComparisionInitialize = function($scope, $) {
        $scope
            .find('.hajs-image-comparison')
            .each(function() {
                var $currentItem = $(this)
                $currentItem.twentytwenty( $currentItem.data('settings') );
            });
    }

    $window.on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/ha-image-comparison.default',
            HappyImageComparisionInitialize
        );
    });
} ( jQuery ) );
