import Dropzone from 'dropzone';
import 'jquery-ui-dist/jquery-ui.js';

require('jquery-ui-touch-punch');

Dropzone.autoDiscover = false;

$(document).ready(function () {
    let $form = $('.js-photo-dropzone');
    let ajaxUrl = $form.attr('action').replace('upload', 'sort');
    let token = $form.attr('data-token');

    if ($form.length) {
        $form.dropzone({
            url: $form.attr('action'),
            acceptedFiles: 'image/*',
            sending: function(file, xhr, formData){
                formData.append('csrf-token', token);
            },
            queuecomplete: function () {
                setTimeout(function () {
                    window.location.reload();
                }, 200);
            }
        });
    }

    let $btnReorder = $('.btn-reorder');

    $btnReorder.on('click', function () {
        $(this).html($(this).attr('data-save'));
        $('.reorder-ul').sortable({ tolerance: 'pointer' });
        $('.reorder-help').slideDown('slow');
        $('.single-img').css('cursor', 'move');
        $('.btn-finish').fadeOut(100);

        $btnReorder.click(function () {
            if (!$('i', this).length) {
                $('.reorder-ul').sortable('destroy');
                $(this)
                    .html(
                        '<i class="fas fa-spin fa-spinner"></i> ' +
                            $(this).attr('data-processing')
                    )
                    .prop('disabled', true);

                let ids = [];
                $('.reorder-ul li').each(function () {
                    ids.push($(this).attr('id').substr(6));
                });

                $.ajax({
                    type: 'POST',
                    url: ajaxUrl,
                    data: { ids: ids }
                }).done(function () {
                    window.location.reload();
                });
            }
        });
    });
});
