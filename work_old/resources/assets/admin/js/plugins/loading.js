(function($) {
    $.loading = function(options){
        const defaults = {
            selector: '#loading',
            timeout: 180000,
            excludeUrl: ['/export', '/download']
        };
        const settings = $.extend({}, defaults, options);

        const obj = $(settings.selector);
        if (!obj[0]) {
            // Loadingが無ければ追加する
            $(`<div id="loading" class="position-fixed top left w-100 h-100" style="top: 0; left: 0; z-index: 99999;display: none">
                    <div class="spinner-border text-black position-absolute" style="width: 50px; height: 50px;bottom: 50px; right: 50px; " role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>`).appendTo('body');
        }

        let timerId;
        // ローディング表示
        $(document).ajaxStart(function() {
            $(settings.selector).show()
            timerId = setTimeout(function(){$(settings.selector).hide()},settings.timeout);
        });
        $(document).ajaxStop(function() {
            $(settings.selector).hide();
            clearTimeout(timerId);
        });
        $(document).submit(function(e) {
            let hasExcludeUrl = false;
            settings.excludeUrl.forEach((url) => {
                if(0 <= e.target.action.indexOf(url)) {
                    hasExcludeUrl = true;
                }
            })
            if (!hasExcludeUrl) {
                $(settings.selector).show();
                timerId = setTimeout(function(){$(settings.selector).hide()},settings.timeout);
            }
        });
    };
})(jQuery);
