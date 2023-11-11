import $ from 'jquery';
window.jQuery = $;
window.$ = $;
import 'popper.js';
import 'bootstrap';
import 'lazysizes';
import bootbox from 'bootbox';
window.bootbox = bootbox;

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
