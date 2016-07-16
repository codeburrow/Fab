$.noConflict();
jQuery( document ).ready(function( $ ) {
    $(function() {
        $( "#gallery, #carousel" ).sortable({
            connectWith: ".connectedSortable"
        }).disableSelection();
    });
});