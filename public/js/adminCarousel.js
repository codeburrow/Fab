var included = [];
var notIncluded = [];
var position = 1;


$("#updateCarousel").click(function () {

    /**
     * this is the jquery code to execute php code to update the database
     * with the changes to be made to the carousel
     */
    $("#carousel").children().children().each(function (index) {

        //get id of every image in every li in the #carousel ul
        console.log(index + "Inclulded ID: " + $(this).attr("id"));

        // add the id of the image to the array that contains the ids of the images to be included in the carousel
        included.push($(this).attr("id"));

        console.log("POSITION: " + position);
    });

    /**
     * this is the jquery code to collect all the images that
     * are to not be included in the carousel but remain in the database
     */
    $("#gallery").children().children().each(function (index) {

        //get id of every image in every li in the #gallery ul
        console.log(index + "Not Included ID: " + $(this).attr("id"));

        // add the id of the image to the array that contains the ids of the images to NOT be included in the carousel
        notIncluded.push($(this).attr("id"));
    });


    $.ajax({
        type: "GET",
        url: '/admin/dashboard/postEditCarousel',
        data: {
            included: included,
            notIncluded: notIncluded
        },
        success: function (msg) {
            console.log('Success Message: ' + msg);
        }
    });

    console.log(included);
});

