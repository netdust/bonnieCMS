define(function (require) {

    "use strict";

    var ntdst           = require('ntdst');

    var $               = require('jquery'),
        _               = require('underscore'),

        Backbone        = require('backbone'),
        ListEditors     = require('listeditor'),

        sstring         = require('app/core/helper/string'),
        templates       = require('app/core/helper/template'),

        editors         = Backbone.Form.editors;


    editors.Disabled = editors.Text.extend({

        initialize: function(options) {
            editors.Text.prototype.initialize.call(this, options);
            this.$el.attr('disabled', true);
        }

    });

    editors.Text = editors.Text.extend({

        initialize: function(options) {
            // Call parent constructor
            editors.Base.prototype.initialize.call(this, options);

            var schema = this.schema;

            //Allow customising text type (email, phone etc.) for HTML5 browsers
            var type = 'text';

            if (schema && schema.editorAttrs && schema.editorAttrs.type) type = schema.editorAttrs.type;
            if (schema && schema.dataType) type = schema.dataType;

            this.$el.attr('type', type);

            var placeHolder='';
            if (schema && schema.placeHolder) placeHolder = schema.placeHolder;

            this.$el.attr('placeHolder', placeHolder);
        }

    });

    editors.TextArea = editors.Text.extend({

        tagName:'TextArea'

    });


    // like the 'Select' editor, except will always return a list of pages
    editors.PageCollection = editors.Select.extend({
        initialize: function(options) {
            options.schema.options = function(callback, editor) {
                callback(  ntdst.api.modelFactory('pages').getOptionList() );
            };
            editors.Select.prototype.initialize.call(this, options);
        },

        getValue: function() {
            return editors.Select.prototype.getValue.call(this);
        },

        setValue: function(value) {
            if( value != null ) value = value.get('id');
            editors.Select.prototype.setValue.call(this, value);
        }
    });


    // like the 'Select' editor, except will always return a boolean (true or false)
    editors.Boolean = editors.Select.extend({
        initialize: function(options) {
            options.schema.options = [
                { val: '1', label: 'Yes' },
                { val: '', label: 'No' }
            ];
            editors.Select.prototype.initialize.call(this, options);
        },
        getValue: function() {
            return !!editors.Select.prototype.getValue.call(this);
        },
        setValue: function(value) {
            value = value ? '1' : '';
            editors.Select.prototype.setValue.call(this, value);
        }
    });

    // like the 'Select' editor, except will always return a number (int or float)
    editors.NumberSelect = editors.Select.extend({
        initialize: function(options) {
            editors.Select.prototype.initialize.call(this, options);
        },
        getValue: function() {
            return parseFloat(editors.Select.prototype.getValue.call(this));
        },
        setValue: function(value) {
            editors.Select.prototype.setValue.call(this, parseFloat(value));
        }
    });

    // https://github.com/eternicode/bootstrap-datepicker/
    editors.DatePicker = editors.Text.extend({
        initialize: function(options) {
            editors.Text.prototype.initialize.call(this, options);
            //Set default date
            if (!options.value) {
                options.value = '00-00-0000';
            }
            this.$el.attr('id', options.id );
            this.$el.attr('value', options.value.split(' ')[0] );
        },
        getValue: function() {
            return this;
        },
        setValue: function(value) {
            //this.$el.fdatepicker('setValue', value);
            return this;
        },
        render: function() {
            editors.Text.prototype.render.apply(this, arguments);
            this.$el.fdatepicker(
                this.schema
            );
            return this;
        }
    });

    // https://github.com/jonthornton/jquery-timepicker
    editors.TimePicker = editors.Text.extend({
        initialize: function(options) {
            editors.Text.prototype.initialize.call(this, options);
            this.$el.addClass('timepicker-input');
        },

        render: function() {
            editors.Text.prototype.render.apply(this, arguments);
            this.$el.timepicker({
                minTime: this.options.schema.minTime,
                maxTime: this.options.schema.maxTime
            });
            return this;
        },

        setValue: function(value) {
            if (!value) value = '';
            this.value = value;
            var ret = editors.Text.prototype.setValue.apply(this, arguments);
            return ret;
        }
    });

    editors.Range = editors.Text.extend({
        events: _.extend({}, editors.Text.prototype.events, {
            'change': function(event) {
                this.trigger('change', this);
            }
        }),

        initialize: function(options) {
            editors.Text.prototype.initialize.call(this, options);

            this.$el.attr('type', 'range');

            if (this.schema.appendToLabel) {
                this.updateLabel();
                this.on('change', this.updateLabel, this);
            }
        },
        getValue: function() {
            var val = editors.Text.prototype.getValue.call(this);
            return parseInt(val, 10);
        },

        updateLabel: function() {
            _(_(function() {
                var $label = this.$el.parents('.bbf-field').find('label');
                $label.text(this.schema.title + ': ' + this.getValue() + (this.schema.valueSuffix || ''));
            }).bind(this)).defer();
        }
    });


    editors.Epic = editors.Base.extend({
        tagName: 'div',

        initialize: function(options) {
            editors.Base.prototype.initialize.call(this, options);
            var self = this;

            this.model = options.model;
            this.epic_id = 'epic_'+this.model.get('language_id');

            this.$el.attr('id', 'epiceditor');

            _.defer(function() {

                self.editor = ntdst.api.createEpic(self.epic_id, options.schema)
                    .on('load', function (e) {
                        self.setValue(self.value);
                    })
                    .on('update', function (e) {
                        self.trigger('change', self);
                    });
            });
        },

        getValue: function() {
            return this.editor.exportFile( this.epic_id );
        },

        setValue: function(value) {
            this.editor.importFile( this.epic_id, value );
        },

        focus: function() {
            if (this.hasFocus) return;
            this.editor.focus();
        },

        render: function() {
            var self = this;
            _.defer(function()
            {
                if( self.editor != null )
                    self.editor.load();
            });

            return this;
        },

        remove: function() {
            if( this.editor != null ) {
                if( !this.editor.is('unloaded') )
                    //this.editor.unload();
                this.editor = null;
            }
        }
    });

    editors.File = editors.Base.extend({

        tagName: 'div',

        events: {
            'change input[name="file"]': function(event) {
                this.trigger('change', this);
            },
            'focus input[name="file"]':  function(event) {
                this.trigger('focus', this);
            },
            'blur input[name="file"]':   function(event) {
                this.trigger('blur', this);
            }
        },

        initialize: function(options) {
            editors.Base.prototype.initialize.call(this, options);

            this.path = 'attachment'; // 0 makes sure file is saved as meta, not as asset in the DB

            this.$input = _.template('\
                <div class="row upload">\
                    <div class="columns small-8 editor">\
                        <div class="columns small-12 editor uploadzone">\
                            <input placeholder="filename" type="text" class="dz-error-message"name="file"/>\
                            <div class="dz-default dz-message"></div>\
                            <div class="preview"><i class="fa fa-eye"/></div>\
                        </div>\
                    </div>\
                    <div class="columns small-4 editor">\
                        <a href="#" class="button file">Select</a>\
                    </div>\
                </div>'
            );

            this.editor = null;
            this.random = Math.random().toString(36).replace(/[^a-z]+/g, '');
        },


        getValue: function() {
            return this.$('input[name="file"]').val();
        },

        setValue: function(value) {
            this.$('input[name="file"]').val([value]).change().blur();
            $('.preview').addClass('visible');
        },

        focus: function() {
            if (this.hasFocus) return;
            this.$('input[name="file"]').focus();
        },

        blur: function() {
            if (!this.hasFocus) return;
            this.$('input[name="file"]').blur();
        },

        render: function() {
            var _editor = this;

            this.$el.append( $($.trim(this.$input())) );
            this.$('.upload').addClass(this.random);

            this.setValue(this.value);

            _.defer(function()
            {
                if( document.querySelector('.'+_editor.random) != null ) {

                    _editor.editor = ntdst.api.createDropzone
                     (
                        '.'+_editor.random+' .uploadzone',
                        {
                            url:ntdst.options.api+_editor.path,
                            clickable:'.'+_editor.random +' .button.file',
                            previewTemplate:'<div class="row dz-preview"><div class="dz-progress-spinner" role="progressbar"></div><div class="dz-error-message"><span data-dz-errormessage></span></div></div>',
                            maxFiles:1,
                            init:function() {

                                $('.'+_editor.random +' .preview').click(function() {
                                    window.open(base + '/data/upload/' + _editor.value,"resizeable,scrollbar");
                                });
                                if( _editor.value!= '') {
                                    _editor.setValue(_editor.value);
                                }

                            }
                        }
                    )
                    .on("addedfile", function() {
                        if (this.files[1]!=null){
                            this.removeFile(this.files[0]);
                        }
                    })
                    .on("success", function( resp ) {
                         _editor.setValue( resp.name );
                    });

                }

            });
            return this;
        },

        remove: function() {
            //this.editor.disable();
        }

    });

    editors.List.InlineNestedModel = editors.List.NestedModel.extend({

        events: {},

        /**
         * @param {Object} options
         */
        initialize: function(options) {
            editors.Base.prototype.initialize.call(this, options);

            // Reverse the effect of the "feature" of pressing enter adding new item
            // https://github.com/powmedia/backbone-forms/commit/6201a6f44984087b71c216dd637b280dab9b757d
            delete this.options.item.events['keydown input[type=text]'];

            var schema = this.schema;

            //Get nested schema if Object
            if (schema.itemType === 'Object') {
                if (!schema.subSchema) throw 'Missing required option "schema.subSchema"';

                this.nestedSchema = schema.subSchema;
            }
            var list = options.list;
            list.on('add', this.onUserAdd, this);
        },

        /**
         * Render the list item representation
         */
        render: function() {
            var self = this;

            this.$el.html(this.getFormEl());

            setTimeout(function() {
                self.trigger('readyToAdd');
            }, 0);

            return this;
        },

        getFormEl: function() {
            var schema = this.schema,
                value = this.getValue();

            this.form = new Form({

                schema: this.schema.subSchema,
                data: this.value,

                template: 'nestedForm'
                //model: value
            });

            return this.form.render().el;
        },

        getValue: function() {
            if (this.form) {
                this.value = this.form.getValue();
                //console.log('nested form value', this.value);
                // see https://github.com/powmedia/backbone-forms/issues/81
            }
            return this.value;
        },

        onUserAdd: function() {
            this.form.$('input, textarea, select').first().focus();
        }

    }, {

        template: _.template('<div class="bbf-nested-form"><%= fieldsets %></div>', null, Backbone.Form.templateSettings)

    });

});
