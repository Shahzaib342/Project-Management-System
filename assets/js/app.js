/**
 *
 * @type {{Created by Shahzaib 14 August,2020}}
 */
var app = {};

(function ($) {
    'use strict';

    $(document).ready(function () {
       //
    });

    //get random comment from DB
    app.sampleFunction = function () {
        $.ajax({
            url: "lib/all.php?action=sampleFunction",
            type: "get",
            dataType: "json",
            success: function (response) {
            },
            error: function (jqXHR, textStatus, errorThrown) {
            }
        });
    };
}(jQuery));