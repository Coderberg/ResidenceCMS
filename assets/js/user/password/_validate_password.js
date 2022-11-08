(function ($) {
    'use strict';

    const $button = $('#savePassword');

    const changeIcon = ($element, pass) => {
        if (true === pass) {
            $element
                .removeClass('fa-times text-danger')
                .addClass('fa-check text-success');
        } else {
            $element
                .removeClass('fa-check text-success')
                .addClass('fa-times text-danger');
        }
    };

    const isPasswordMatch = (password1, password2) => {
        return (
            '' !== password1.trim() &&
            password1.trim() === password2.trim()
        );
    };

    // Validate input
    $('input[type=password]').keyup(function () {
        $(this).val($(this).val().trim());
        $('.alert', '#changePassword').text('').hide();

        const uppercase = /[A-ZА-Я]+/;
        const lowercase = /[a-zа-я]+/;
        const numbers = /\d+/;
        let $password2 = $('#password2');
        let password1 = $('#password1').val();
        let password2 = $password2.val();

        changeIcon($('#10char'), password1.length >= 10);
        changeIcon($('#uppercase'), uppercase.test(password1));
        changeIcon($('#lowercase'), lowercase.test(password1));
        changeIcon($('#numbers'), numbers.test(password1));
        changeIcon(
            $('#match'),
            isPasswordMatch(password1, password2)
        );

        if (
            password1.length >= 10 &&
            uppercase.test(password1) &&
            lowercase.test(password1) &&
            numbers.test(password1)
        ) {
            $password2.attr('disabled', false);
        }

        $button
            .attr('disabled', !isPasswordMatch(password1, password2))
            .text($button.data('default-text'));
    });
})($);
