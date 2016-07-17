$.noConflict();
jQuery( document ).ready(function( $ ) {
    $(function() {
        $( "#gallery, #carousel" ).sortable({
            connectWith: ".connectedSortable"
        }).disableSelection();
    });

    $(function() {
        $( "#draggable" ).draggable();
    });

    $( "#droppable" ).droppable({
        over: function() {
            $(".icon").css("box-shadow", "0 0 7px rgba(10,10,10,.3)");
            //$(".icon .lid .lidcap").css("transform", "rotate(10deg)");
            $(".lid ").css("transform", "rotate(10deg)");
            $(".lidcap").css("transform", "rotate(10deg)");
            $(".icon .lid .lidcap").css("margin-bottom", "10.5px");
            $(".lid").css("margin-bottom", "10.5px");
            $(".lidcap").css("margin-bottom", "10.5px");

        }
    });

    $( "#droppable" ).droppable({
        out: function() {
            $(".icon").css("box-shadow", "0");
            //$(".icon .lid .lidcap").css("transform", "rotate(0deg)");
            $(".lid ").css("transform", "rotate(0deg)");
            $(".lidcap").css("transform", "rotate(0deg)");
            $(".icon .lid .lidcap").css("margin-bottom", "0px");
            $(".lid").css("margin-bottom", "0px");
            $(".lidcap").css("margin-bottom", "0px");

        }
    });

    $( "#droppable" ).droppable({
        drop: function(event, ui) {
            //alert( "dropped" );
            //ui.draggable.remove();
            //var info = .;
            alert($( ui.draggable).attr("id"));
        }
    });

});





//$('.droppable').droppable({
//    over: function() {
//        alert('working!');
//        //$('.box').remove();
//    }
//});


