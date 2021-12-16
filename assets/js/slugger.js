'use strict';

const slugify = require('slugify');

$(document).ready(function () {
    $('#name input').keyup(function () {
        $('#slug input').val(
            slugify($('#name input').val(), {
                lower: true,
                strict: true
            })
        );
    });
});
