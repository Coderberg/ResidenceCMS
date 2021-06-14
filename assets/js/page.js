'use strict';

$(document).ready(function () {
    let $checkbox = $('#page_add_contact_form');
    let $emailContainer = $('#email-address');
    let $emailField = $('#page_contact_email_address');

    if ($checkbox.attr('checked') === 'checked') {
        $emailContainer.slideDown();
        $emailField.prop('required', true);
    }

    // Event Handler
    $checkbox.on('change', function () {
        updateDisplay();
    });

    // Action

    function updateDisplay() {
        let isChecked = $checkbox.is(':checked');

        if (isChecked) {
            $emailContainer.slideDown();
            $emailField.focus().prop('required', true);
        } else {
            $emailContainer.slideUp();
            $emailField.prop('required', false);
        }
    }
});
