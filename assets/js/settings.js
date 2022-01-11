import Dropzone from 'dropzone';

Dropzone.autoDiscover = false;

$(document).ready(function () {
    let $form = $('.js-photo-dropzone');
    let token = $form.attr('data-token');
    if ($form.length) {
        $form.dropzone({
            url: $form.attr('action'),
            acceptedFiles: 'image/*',
            sending: function(file, xhr, formData){
                formData.append('csrf_token', token);
            },
            queuecomplete: function () {
                setTimeout(function () {
                    window.location.reload();
                }, 200);
            }
        });
    }
});
