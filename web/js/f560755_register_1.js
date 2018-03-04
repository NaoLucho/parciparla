// keep track of how many owned structures have been rendered
var structureCount = 0;

jQuery(document).ready(function() {
    $('#add_structure').click(function (e) {

        $('#add_structure').hide();
        $('#structure_list_container').hide();
        $('.structure_list').prop('required', false);
        $('.cancel_add_structure').show();

        e.preventDefault();

        var structureList = $('#fos_user_registration_form_ownedStructures');

        // grab the prototype template
        var newWidget = structureList.attr('data-prototype');
        // replace the "__name__" used in the id and name of the prototype
        // with a number that's unique to your emails
        // end name attribute looks like name="contact[emails][2]"
        newWidget = newWidget.replace(/__name__/g, structureCount);
        structureCount++;

        // create a new list element and add it to the list
        var newLi = $('<li></li>').html(newWidget);
        newLi.appendTo(structureList);
        structureList.find('li').each(function() {
            addDeleteLink($(this));
        });
    });

    function addDeleteLink(form) {
        var removeForm = $('<a href="#" class="cancel_add_structure btn btn-default">Annuler et revenir à la liste des structures</a>');
        form.append(removeForm);


        $('.cancel_add_structure').click(function(e) {
            e.preventDefault();
    
            $('#add_structure').show();
            $('#structure_list_container').show();
            $('.structure_list').prop('required', 'required');
            $('.cancel_add_structure').hide();
            
            structureCount--;

            form.remove();
        });
    }

    // $(".structure_list").chosen();
    // $(".theme-field").chosen({ max_selected_options: 5 });
    // //$(".theme-field").trigger("chosen:updated");

    $(".taxon-field").select2({
        tags: true,
        createTag: function (params) {
            var term = $.trim(params.term);

            if (term === '') {
                return null;
            }
            console.log(term);

            return {
                id: term,
                text: term,
                newTag: true // add additional parameters
            }
        }
    });

    $(".theme-field").select2({
        tags: true,
        createTag: function (params) {
            var term = $.trim(params.term);

            if (term === '') {
                return null;
            }
            console.log(term);

            return {
                id: term,
                text: term,
                newTag: true // add additional parameters
            }
        }
    });

    $(".structure_list").select2();


    $("#cgu_button").on('click', function(e){

        e.preventDefault();

        $(".modal-header").empty();
        $(".modal-body").empty();


        var cguPath = $('.js-register').data('path-cgu');

        $.ajax({
            type: "GET",
            url: cguPath,
    
            success: function (data) {
                
                html = $.parseHTML( data );

                //Get the child with the title
                var titleIndex = checkHTMLchildren(html, 'popuptitle');
                
                //Split it from the array

                var title, content;

                if (titleIndex > -1) {
                    title = html[titleIndex];
                    content = html;
                    content.splice(titleIndex, 1);
                } else {
                    title = "Information";
                }

                title = $(title).html();

                //Render the content in both areas of the popup
                $(".modal-header").append(title);
                $(".modal-header").append('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');

                $(".modal-body").append(content);

    
            }
        });
    });

    //Récupère l'indice de l'enfant html qui détient le titre
    function checkHTMLchildren(html, searchedClass) {
        var index;
        $.each( html, function(i, e) {

            $.each(e.classList, function(j, elClass) {
                if (elClass === searchedClass) {
                    index = i;
                    return false;
                }
            });
            if (index) {
                return false;
            }
        });
        return index;
    }
});
