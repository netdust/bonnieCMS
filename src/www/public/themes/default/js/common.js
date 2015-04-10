
require.config({

    baseUrl: app_root + '/www/default/js',

    paths: {
        app: './plugins',

        jquery: './vendor/jquery',
        underscore: './vendor/underscore'
    },

    shim: {
        'underscore': { exports: '_' },
        'jquery.validate': {
            deps:['jquery']
        }
    }

});

require(
    ['bootstrap', 'jquery', 'underscore'], function (o, $, _)
    {
        var ntdst = window.ntdst = {};

        ntdst.settings = _.extend({}, o.settings);

        require(
            ['app/syncHeight.jquery','app/ajaxform.jquery', 'app/jquery.validate'], function (nav)
            {

                if (window.matchMedia("only screen and (min-width: 48.063em)").matches) {
                    $('.step1 .drop-shadow').syncHeight({ 'updateOnResize': true});
                    $('.step2 .drop-shadow').syncHeight({ 'updateOnResize': true});
                    $('.step3 .drop-shadow').syncHeight({ 'updateOnResize': true});
                }

                $('.preview a').click(function(){
                    $('.balloon').show().click(function(e){$(this).hide()});
                    return false;
                });

                $('#friendform')
                    .ajaxForm({
                        beforeSubmit: function(arr, $form, options) { // don't submit, store values
                            if( $form.valid() ) {
                                ntdst.data = arr;
                                $('.step1').hide();
                                $('.step2').show();
                            }
                            return false;
                        }
                    })
                    .validate({
                        ignore: ':hidden:not(:checkbox)',
                        errorPlacement: function(error, element) {
                            error.hide();
                        }
                    });

                $('#gsmnumber').rules(  "add", {
                        required: true,
                        number: true,
                        minlength: 10
                    });

                $('#userform')
                    .ajaxForm({
                        type:'POST',
                        url: './form',

                        beforeSubmit: function(arr, $form) {
                            if( $form.valid() ) {
                                $.merge( arr, ntdst.data );
                                return true;
                            }

                            return false;
                        },

                        success: function(json, status)
                        {
                            json =  $.parseJSON( json );
                            //if( json.status=='error' ){ }
                            //else {
                                $('.step2').hide();
                                $('.step3').show();
                            //}
                        },
                        error: function() {
                            console.log( arguments );
                        }
                    })
                    .validate({
                        ignore: ':hidden:not(:checkbox)',
                        errorPlacement: function(error, element) {
                            error.hide();
                        }
                    });
                $('#code').rules(  "add", {
                    required: true,
                    number: true,
                    minlength: 4
                });

            });

        return this;
    });

