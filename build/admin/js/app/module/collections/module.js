define(function (require) {

    "use strict";

    var ntdst           = require('ntdst');
    
    var SubRoute        = require('backbone.subroute'),

        Model           = require("app/module/collections/model"),
        Collections     = require("app/module/collections/view/CollectionList"),
        Collection      = require("app/module/collections/view/Collection"),
        Image           = require("app/module/collections/view/Image"),


        router = SubRoute.extend({

            assettpl : $('<div class="row zonecontainer"><div class="columns small-12"><div class="zone text-center"><strong>Drop files</strong> to upload<br/>(or click)</div></div></div><ul class="previewsContainer"></ul>'),

            routes: {
                "" : "list",
                "create" : "create",
                ":id" : "page",
                ":id/:img" : "image"
            },

            list: function( )
            {
                var _models = ntdst.api.modelFactory( 'collections' );
                var view = ntdst.api.viewFactory( 'collectionlist', Collections, {model:_models} );
                ntdst.api.show( '#app', view );

                _models.fetch({reset: true});
            },

            page: function( id )
            {
                var _collection = ntdst.api.modelFactory( 'collections' ).getPage({'id':id});

                if( _collection == null ) {
                    ntdst.api.navigate('/collection');
                    return;
                }

                var view  = ntdst.api.viewFactory( 'collection'+id, Collection, {model:_collection});

                this.listenTo(view, 'afterrender', function() {
                    this.stopListening(view, 'afterrender');
                    $('.assetsrow .assets').append(this.assettpl);
                    view.drop = getDrop( _collection, _collection.get('id') );
                });


                ntdst.api.show( '#app', view );
                _collection.fetch({reset: true});
            },

            image: function( _collection, _id )
            {
                var _collection = ntdst.api.modelFactory( 'collections' ).getPage({'id':_collection});
                var _asset = _collection.getSub(_id);

                var view = ntdst.api.viewFactory( 'image'+_id, Image, {model:_asset} );
                ntdst.api.show( '#app', view );
            },

            create: function ( )
            {

                var _m = new Model.collection( {date: new Date().getTime(), user:ntdst.options.user, type:'collection'} );
                var view = new Collection({model:_m});

                this.listenTo(view, 'afterrender', function() {

                    this.stopListening(view, 'afterrender');
                    $('#main').removeClass('closed');

                    $('.assetsrow .assets').append(this.assettpl);

                    view.drop = getDrop(_m, 0);
                });

                this.listenTo(_m, 'sync', function() {
                    this.stopListening(_m, 'sync');
                    ntdst.api.navigate('/collection');
                });


                ntdst.api.show( '#app', view );
            }
        }),

        collections = {
            init: function ( options )
            {
                this.data( options['name'], options['data'] );
                new router( options['path'].toLowerCase() );
                return this;
            },

            data:function( name, _data ) {
                var n = name.toLowerCase();
                return ntdst.api.modelFactory( n, Model['pagesCollection'], _data );
            }
        },

        getDrop = function( model, id ) {
            return ntdst.api.createDropzone(
                '.zone',
                {
                    url:ntdst.options.api+"attachment/" + id,
                    previewTemplate:'<li class="file square-box" draggable="true"><div id="template" class="file-container square-content"><div><span class="preview"><img data-dz-thumbnail /></span></div></div></li>',
                    previewsContainer:'.previewsContainer',
                    success: function( file, response ) {
                        var _m = new Model.attachment( response );
                        model.get('page').add( _m );
                    },
                    init: function() {
                        this.on("addedfile", function(file) {
                        });
                    }
                }
            );

        };

    return collections;
});