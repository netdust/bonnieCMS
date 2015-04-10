define(function (require) {

    "use strict";

    var ntdst           = require('ntdst');

    var Backbone        = require('backbone'),
        list            = require('listeditor');

        Backbone.Form.Field.template = _.template('\
            <div class="row collapse field-<%= key %>" >\
                <div class="small-4 columns">\
                <span class="prefix <%= editorId %>"><%= title %></span>\
                </div>\
                <div class="small-8 columns">\
                    <span data-editor></span>\
                </div>\
            </div>\
        ');

        Backbone.Form.editors.List.template = _.template('\
          <div>\
            <div class="list" data-items ></div>\
            <a class="button add-list" data-action="add">Add</a>\
          </div>\
        ', null, Backbone.Form.templateSettings);


        Backbone.Form.editors.List.Item.template =  _.template('\
          <div class="row collapse">\
            <div class="columns small-11"><span data-editor></span></div>\
            <div class="columns small-1"><a class="button item close" data-action="remove">&times;</a></div>\
          </div>\
        ', null, Backbone.Form.templateSettings);



        Backbone.Form.editors.Date.template = _.template('\
            <div class="row collapse">\
              <div class="columns small-3"><select data-type="date"><%= dates %></select></div>\
              <div class="columns small-5"><select data-type="month"><%= months %></select></div>\
              <div class="columns small-4"><select data-type="year"><%= years %></select></div>\
            </div>\
      ', null, Backbone.Form.templateSettings);

});
