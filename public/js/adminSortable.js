// noConflict is used to avoid errors that occur when using anonymous functions in jquery
$.noConflict();
jQuery( document ).ready(function( $ ) {
    $(function() {
        //both ul (gallery and carousel) are assigned the sortable functionality
        $( "#gallery, #carousel" ).sortable({
            //connectWith allows both ul to connected to be able to drag li from one to the other
            //if they are not connected you would only be able to sort li within their parent ul and not added to a different ul
            connectWith: ".connectedSortable",

            dropOnEmpty: false
        }).disableSelection();
    });

    var image = "";

    // .droppable makes the element accept other draggable elements

    $( ".icon" ).droppable({
        // the code below defines what happens to any element when its placed over the droppable
        //example animation
        over: function(event, ui) {

            image = ui.draggable;
            $(".icon").css("box-shadow", "0 0 7px rgba(10,10,10,.3)");
            $(".lid ").css("transform", "rotate(10deg)");
            $(".lidcap").css("transform", "rotate(10deg)");
            $(".icon .lid .lidcap").css("margin-bottom", "10.5px");
            $(".lid").css("margin-bottom", "10.5px");
            $(".lidcap").css("margin-bottom", "10.5px");
            $(image).css("opacity", "0.2");

        }
    });

    $( ".icon" ).droppable({
        // the code below defines what happens to any element when its dragged out of the droppable
        //example reverse-animation
        out: function() {
            $(".icon").css("box-shadow", "0");
            $(".lid ").css("transform", "rotate(0deg)");
            $(".lidcap").css("transform", "rotate(0deg)");
            $(".icon .lid .lidcap").css("margin-bottom", "0px");
            $(".lid").css("margin-bottom", "0px");
            $(".lidcap").css("margin-bottom", "0px");
            $(image).css("opacity", "1");

        }
    });

    $( ".icon" ).droppable({

        // the code below defines what happens to any element when its dropped on the droppable
        //example execution of actions and animation

        drop: function(event, ui) {
            //alert( "dropped" );
            //ui.draggable.remove();
            //var info = .;
            var id = $( ui.draggable).children().attr("id");
            var path = $( ui.draggable).children().attr("src");
            console.log(id);

            // detecting whether the user chose to proceed or not
            if(confirm("Are you sure you want to delete this image permanently?")) {

                $.ajax({
                    type: "GET",
                    url: '/deleteFromCarouselDB',
                    //this is the data to be sent in the GET request. it includes the id -> included and the position -> position
                    data: {
                        //this is the data to be sent in the GET request. it includes the id of the picture to be deleted
                        ID: id,
                        path: path
                    },
                    //a success message that appears when the jqeury is successful
                    success: function(msg){
                        console.log('WOW' + msg);
                    }
                })


                $( ui.draggable).children().addClass("removed-item");


                $(".icon").css("box-shadow", "0");
                $(".lid ").css("transform", "rotate(0deg)");
                $(".lidcap").css("transform", "rotate(0deg)");
                $(".icon .lid .lidcap").css("margin-bottom", "0px");
                $(".lid").css("margin-bottom", "0px");
                $(".lidcap").css("margin-bottom", "0px");
                $(ui.draggable).css("opacity", "1");
                $(ui.draggable).cancel();


            }
            else {

                $(".icon").css("box-shadow", "0");
                $(".lid ").css("transform", "rotate(0deg)");
                $(".lidcap").css("transform", "rotate(0deg)");
                $(".icon .lid .lidcap").css("margin-bottom", "0px");
                $(".lid").css("margin-bottom", "0px");
                $(".lidcap").css("margin-bottom", "0px");
                $(ui.draggable).css("opacity", "1");

            }


        }
    });

});





