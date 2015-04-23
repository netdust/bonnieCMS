define(function (require) {

    "use strict";

    var ntdst               = require('ntdst');

    var $                   = require('jquery'),
        Base                = require('app/module/pages/view/Page'),

        SortList            = require('nestedSortable');

    return Base.extend({

        drop:null,

        initialize:function ()
        {
            this.listenTo( this.model, 'change:page', this.media );
            return Base.prototype.initialize.apply(this, arguments);
        },

        afterRender: function() {

            var self = this;

            var o =  $('.previewsContainer').nestedSortable({
                protectRoot: true,
                forcePlaceholderSize: true,
                listType: 'ul',
                handle: 'div',
                items: 'li',
                opacity: .6,
                placeholder: 'placeholder',
                maxLevels:1,
                relocate: function(){
                    setTimeout(function(){
                        self.model.get('page').updateSort(
                            o.nestedSortable('toHierarchy', {startDepthCount: 0}),
                            self.model.get('id')
                        )
                    }, 300);
                }
            });


            if( this.model.get('page').length > 0 ) {
                this.media();
            }

            return Base.prototype.afterRender.apply(this, arguments);
        },

        media: function( )
        {
            var root = ntdst.options.root.split('/');
            root.pop();
            root = root.join('/');

            var self = this;

            this.stopListening(this.model, 'change:page');
            var assetsModel = this.model.get('page');

            assetsModel.each( function(item){
                var mockFile = { name: item.get('label'), size:0 };
                this.options.addedfile.call(this, mockFile);
                mockFile.previewElement.setAttribute('data-dz-id', item.get('id') );
                mockFile.previewElement.setAttribute('id', 'preview_' + item.get('id') );
                this.options.thumbnail.call(this, mockFile, root+'/'+item.get('template'));
                //this.options.thumbnail.call(this, mockFile, ntdst.options.api +"image?src="+root+'/data/upload'+item.get('template'));
            }, this.drop );

            $('.dz-image-preview').on('click', function()
            {
                ntdst.api.navigate( 'collection/'+ self.model.get('id') +'/'+ $(this).attr('data-dz-id') );

            });

        },

        updatePage: function(e) {
            if(e.currentTarget == e.target ) {
                var errors = this.model.validate();
                if(_.isEmpty(errors))
                {
                    var self = this;
                    self.model.updatePage({
                        success: function (model) {
                            self.drop.options.url = ntdst.options.api+'collection/' + model.get('id') + '/upload';
                            self.drop.options.params = {"dir":"collection_"+model.get('id')+"/"};
                            self.drop.processQueue();
                        }
                    });
                }
                else {
                    Backbone.trigger('notification', { message: 'Not all fields are filled in like they should, have a look', type: 'warning' });
                }
            }
        },

        remove: function()
        {
            this.drop.destroy();
            $(this.drop.previewsContainer).html('');
            return Base.prototype.remove.apply(this, arguments);
        }

    });

});