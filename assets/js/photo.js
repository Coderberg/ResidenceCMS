import Dropzone from 'dropzone';

Dropzone.autoDiscover = false;

$(document).ready(function() {

    let formElement = $('.js-photo-dropzone');

    if (formElement.length) {
        formElement.dropzone({
            url: formElement.attr('action'),
            acceptedFiles: 'image/*',
            queuecomplete: function() {
                setTimeout(function() {
                    window.location.reload();
                }, 200);
            }
        });
    }

});
