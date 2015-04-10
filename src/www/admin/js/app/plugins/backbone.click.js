
(function(factory) {
    if (typeof define === 'function' && define.amd) {
        define(['ntdst'], factory);
    } else if (typeof exports === 'object') {
        module.exports = factory(require('ntdst'));
    } else {
        factory(window.ntdst);
    }
})(function(ntdst) {

    $(document).on('click', 'a[href^="/"]', function(e)
    {
        var href=$(this).attr('href');
        var protocol = this.protocol + '//';
        if( href && href.slice(0, protocol.length) !== protocol && href.indexOf('javascript:' !== 0) )
        {
            e.preventDefault();
            ntdst.api.navigate( href );
        }
    });

});