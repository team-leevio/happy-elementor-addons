;(function($) {
    $(function() {
        var $clearCache = $('.hajs-clear-cache'),
            $haMenu = $('#toplevel_page_happy-addons .toplevel_page_happy-addons .wp-menu-name'),
            menuText = $haMenu.text();

        $haMenu.text(menuText.replace(/\s/, ''));

        $clearCache.on('click', 'a', function(e) {
            e.preventDefault();

            var type = 'all',
                $m = $(e.delegateTarget);

            if ($m.hasClass('ha-clear-page-cache')) {
                type = 'page';
            }

            $m.addClass('ha-clear-cache--init');

            $.post(
                HappyAdmin.ajax_url,
                {
                    action: 'ha_clear_cache',
                    type: type,
                    nonce: HappyAdmin.nonce,
                    post_id: HappyAdmin.post_id
                }
            ).done(function(res) {
                $m.removeClass('ha-clear-cache--init').addClass('ha-clear-cache--done');
            });
        });
    })
}(jQuery));
