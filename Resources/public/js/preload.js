function isFunction(functionToCheck) {
    var getType = {};
    return functionToCheck && getType.toString.call(functionToCheck) === '[object Function]';
}

(function ( $ ) {

    $.fn.preload = function( options ) {

        if(isFunction(options))
        {
            var options = {callback: options};
        }

        // This is the easiest way to have default options.
        var settings = $.extend({
            btn: null,
            route: null,
            routingData: {},
            ajaxData: {},
            btnContent: null,
            data: null,
            callback: null,
            loaded: false,
            compiledRoute: null,
            $btn: null,
            isBtnModified: false
        }, options);

        route(this);
        handleBtn(this);
        launchAjax();

        function handleBtn(btn)
        {
            btn.on('click', function(e)
            {
                e.preventDefault();

                if(!settings.loaded)
                {
                    settings.isBtnModified = true;
                    settings.btnContent = $(this).html();
                    $(this).find('i').attr('class', 'fa fa-spinner fa-spin');
                }

                settings.$btn = $(this);

                var checker = setInterval(function(){
                    if(settings.loaded) {
                        clearInterval(checker);
                        if(settings.btnContent && settings.isBtnModified)
                        {
                            settings.isBtnModified = false;
                            settings.$btn.html(settings.btnContent);
                        }
                        if(settings.callback)
                            settings.callback(settings.data, settings);
                        else {
                            console.log('[Preload][Error] No callback defined for btn');
                            console.log('button', btn);
                        }
                    }
                }, 500);
            });
        }

        function launchAjax()
        {
            console.log('[preload] launch ajax');
            $.post({
                url: settings.compiledRoute,
                data: settings.ajaxData,
                success: function(loadedData)
                {
                    try
                    {
                        settings.data = JSON.parse(loadedData);
                    }
                    catch(e)
                    {
                        settings.data = loadedData;
                    }

                    settings.loaded = true;
                    console.log('success', settings);
                }
            });
        }

        function route(btn)
        {
            console.log('href', btn, btn.attr('href'));
            if(btn.attr('href') != '#' && btn.attr('href') != '')
                settings.compiledRoute = btn.attr('href');
            if(settings.route != null)
            {
                if($.isEmptyObject(settings.routingData))
                    settings.compiledRoute = Routing.generate(settings.route);
                else
                    settings.compiledRoute = Routing.generate(settings.route, settings.routingData);
            }
            console.log('route', settings.compiledRoute);
        }

    };

}( jQuery ));