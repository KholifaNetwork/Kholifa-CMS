$(document).ready(function () {
    "use strict";

    if (kn.isManagementState) {
        return;
    }
    $('a[rel*=lightbox]').colorbox({
        rel: 'knwImage',
        maxWidth: '90%',
        maxHeight: '90%',
        title: function(){return $(this).attr('title') + ($(this).data('description') ? '. ' + $(this).data('description') : '');}
    });
    $('a[rel=standaloneLightbox]').colorbox({
        maxWidth: '90%',
        maxHeight: '90%'
    });
});
