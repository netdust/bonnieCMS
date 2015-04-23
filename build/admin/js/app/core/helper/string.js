define(function (require) {

    "use strict";

    return {
        lowercase: function(str){
            return str.toLowerCase();
        },

        uppercase: function(str){
            return str.toUpperCase();
        },

        hash: function(s){
            if( !isNaN(s) ) return s;
            return "hash"+s.split("").reduce(function(a,b){a=((a<<5)-a)+b.charCodeAt(0);return a&a},0);
        },

        random: function() {
            return Math.random().toString(36).replace(/[^a-z]+/g, '');
        }
    }

});