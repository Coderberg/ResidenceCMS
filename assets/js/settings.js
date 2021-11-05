import Dropzone from 'dropzone';

Dropzone.autoDiscover = false;

$(document).ready(function () {
    let $form = $('.js-photo-dropzone');
    if ($form.length) {
        $form.dropzone({
            url: $form.attr('action'),
            acceptedFiles: 'image/*',
            queuecomplete: function () {
                setTimeout(function () {
                    window.location.reload();
                }, 200);
            }
        });
    }
});
