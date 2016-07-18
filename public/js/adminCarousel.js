var included = [];
var notIncluded = [];
var removePictures = [];
var position = 1;


$( "#updateCarousel" ).click(function() {
    //a task for when the button is clicked

    $( "#carousel" ).children().children().each(function( index ) {
        console.log( index + " I : " + $( this).attr("id") )
        //get the id attribute of each image in the li in the carousel list
        included.push($( this).attr("id"));
        // add the id of the image to the array that contains the ids of the images to be included in the carousel
        console.log("POSITION: " + position);
        //this is the jquery code to execute php code to update the database with the changes to be made to the carousel
        console.log("POSITION: " + position);
    });

    // this is the jquery code to collect all the images that are to not be included in the carousel but remain in the database
    $( "#gallery" ).children().children().each(function( index ) {
        console.log( index + " NI : " + $( this).attr("id") );
        //get the id attribute of each image in the li in the sortable2 list
        notIncluded.push($( this).attr("id"));
        // add the id of the image to the array that contains the ids of the images to not be included in the carousel
    });

    $.ajax({
        type: "GET",
        url: '/updateCarousel',
        //this is the data to be sent in the GET request. it includes the id -> included and the position -> position
        data: {
            //this is the data to be sent in the GET request. it includes the id -> included and the position -> position
            included: included,
            //this is the data to be sent in the GET request. it includes the id -> included and no position is specified because it will not be in the carousel
            notIncluded: notIncluded
        },
        //a success message that appears when the jqeury is successful
        success: function(msg){
            console.log('WOW' + msg);
        }
    })

    console.log(included);
});

