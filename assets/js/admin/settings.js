import Dropzone from 'dropzone';

Dropzone.autoDiscover = false;

$(document).ready(function () {
    const configureDropzone = ($form) => {
        const token = $form.data('token');
        if ($form.length) {
            $form.dropzone({
                url: $form.attr('action'),
                acceptedFiles: 'image/*',
                sending: function (file, xhr, formData) {
                    formData.append('csrf_token', token);
                },
                queuecomplete: function () {
                    setTimeout(function () {
                        window.location.reload();
                    }, 200);
                }
            });
        }
    };

    configureDropzone($('form[action$="upload_logo_image"]'));
    configureDropzone($('form[action$="upload_header_image"]'));
});
