var $collectionHolder;

// setup an "add a tag" link
var $addTagButton = $('<button type="button" class="add_tag_link btn btn-warning " >Ajouter une image <i class="far fa-image"></i></button>');
var $newLinkLi = $('<li class="list-unstyled text-center "></li>').append($addTagButton);
// var $newFormLi= $('<button type="button" class="add_tag_link">Ajouter une image</button>');

jQuery(document).ready(function() {
    // Get the ul that holds the collection of images
    $collectionHolder = $('ul.images');

    $collectionHolder.find('li').each(function() {
        addTagFormDeleteLink($(this));
    });



    function addTagFormDeleteLink($tagFormLi) {
        var $removeFormButton = $('<button type="button" class="btn btn-danger mt-0 ">Supprimer l\'image</button>');
        $tagFormLi.append($removeFormButton);

        $removeFormButton.on('click', function(e) {
            // remove the li for the tag form
            $tagFormLi.remove();
        });
    }


    // add the "add a tag" anchor and li to the images ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find('input').length);

    $addTagButton.on('click', function(e) {
        // add a new tag form (see next code block)
        addTagForm($collectionHolder, $newLinkLi);
    });
});

function addTagForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li class=" mt-3"></li>').append(newForm);
    $newLinkLi.before($newFormLi);

    addTagFormDeleteLink($newFormLi);
}

function addTagFormDeleteLink($tagFormLi) {
    var $removeFormButton = $('<button type="button" class="btn btn-danger mt-0 ">Supprimer l\'image</button>');
    $tagFormLi.append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        // remove the li for the tag form
        $tagFormLi.remove();
    });
}


// //**********video***********//
// var $collectionHolderVideo;
//
// // setup an "add a tag" link
// var $addVideoButton = $('<button type="button" class="add_tag_link btn btn-warning mb-5" >Ajouter une video <i class="fab fa-youtube"></i></button>');
// var $newVideoLinkLi = $('<li class="list-unstyled text-center mt-1"></li>').append($addVideoButton);
// // var $newFormLi= $('<button type="button" class="add_tag_link">Ajouter une image</button>');
//
// jQuery(document).ready(function() {
//     // Get the ul that holds the collection of images
//     $collectionHolderVideo = $('ul.videos');
//
//     $collectionHolderVideo.find('li').each(function() {
//         addTagFormDeleteVideoLink($(this));
//     });
//
//
//
//     function addTagFormDeleteVideoLink($videoFormLi) {
//         var $removeFormButton = $('<button type="button">Delete this tag</button>');
//         $videoFormLi.append($removeFormButton);
//
//         $removeFormButton.on('click', function(e) {
//             // remove the li for the tag form
//             $videoFormLi.remove();
//         });
//     }
//
//
//     // add the "add a tag" anchor and li to the images ul
//     $collectionHolderVideo.append($newVideoLinkLi);
//
//     // count the current form inputs we have (e.g. 2), use that as the new
//     // index when inserting a new item (e.g. 2)
//     $collectionHolderVideo.data('index', $collectionHolderVideo.find('input').length);
//
//     $addVideoButton.on('click', function(e) {
//         // add a new tag form (see next code block)
//         addVideoForm($collectionHolderVideo, $newVideoLinkLi);
//     });
// });
//
// function addVideoForm($collectionHolderVideo, $newVideoLinkLi) {
//     // Get the data-prototype explained earlier
//     var prototype = $collectionHolderVideo.data('prototype');
//
//     // get the new index
//     var index = $collectionHolderVideo.data('index');
//
//     var newForm = prototype;
//     // You need this only if you didn't set 'label' => false in your tags field in TaskType
//     // Replace '__name__label__' in the prototype's HTML to
//     // instead be a number based on how many items we have
//     // newForm = newForm.replace(/__name__label__/g, index);
//
//     // Replace '__name__' in the prototype's HTML to
//     // instead be a number based on how many items we have
//     newForm = newForm.replace(/__name__/g, index);
//
//     // increase the index with one for the next item
//     $collectionHolderVideo.data('index', index + 1);
//
//     // Display the form in the page in an li, before the "Add a tag" link li
//     var $newFormLi = $('<li class="mt-3 "></li>').append(newForm);
//     $newVideoLinkLi.before($newFormLi);
//
//     addTagFormDeleteVideoLink($newFormLi);
// }
//
// function addTagFormDeleteVideoLink($videoFormLi) {
//     var $removeFormVideoButton = $('<button type="button" class="mb-2 mt-0 btn btn-danger">Supprimer la video</button>');
//     $videoFormLi.append($removeFormVideoButton);
//
//     $removeFormVideoButton.on('click', function(e) {
//         // remove the li for the tag form
//         $videoFormLi.remove();
//     });
// }