import Dropzone from 'dropzone';
import 'jquery-ui-dist/jquery-ui.js';

require('jquery-ui-touch-punch');

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

    let $btnReorder = $('.btn-reorder');

    $btnReorder.on('click', function () {

        $(this).html('Save reordering');
        $(".reorder-ul").sortable({tolerance: 'pointer'});
        $('.reorder-help').slideDown('slow');
        $('.single-img').css('cursor', 'move');

        $btnReorder.click(function () {
            if (!$('i', this).length) {

                $('.reorder-ul').sortable('destroy');
                $(this).html('<i class="fas fa-spin fa-spinner"></i> Please wait...').prop('disabled', true);

                let ids = [];
                $('.reorder-ul li').each(function () {
                    ids.push($(this).attr('id').substr(6));
                });

                $.ajax({
                    type: 'POST',
                    url: '/admin/photo/sort',
                    data: {ids: ids}
                }).done(function () {
                    window.location.reload();
                });
            }
        });

    });

});
