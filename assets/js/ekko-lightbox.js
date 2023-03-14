import 'ekko-lightbox/dist/ekko-lightbox';

$('body').on('click', '[data-toggle="lightbox"]', function (event) {
    event.preventDefault();
    $(this).ekkoLightbox();
});
