
jQuery(document).ready(function ($) {
    //change icon
    $('body').on('click', '.select-icon', function (event) {
        //start output
        var html = $('#icon_edit_html').html();

        var thisIcon = $(this);

        Swal.fire({
        title: $('#icon_edit_html').attr('data-icon-popup-title'),
        html: html,
        customClass: 'swal-wide',
        showCancelButton: true,
        cancelButtonText: $('#icon_edit_html').attr('data-cancel-button'),
        showConfirmButton: false,
        didOpen: function () {

            //remove focus on the upload field
            $('input').blur();

            //on click of an icon
            //when selecting a new icon replace the existing icon and close the dialog
            $('.swal-wide').on('click', ".icon-for-selection", function () { 
                // console.log('HELLO WORLD');
                var newIcon = $(this).attr('data');

                // console.log(newIcon);

                //determine whether icon is dashicon
                if($(this).hasClass('dashicons')){
                    //its a dash icon

                    //remove existing
                    thisIcon.removeClass().css('background-image','');

                    //we need to do update the icon displayed
                    thisIcon.removeClass().addClass('select-icon dashicons '+newIcon);

                    //we need to update the data attribute
                    thisIcon.next().val(newIcon);

                } else {

                    //remove existing
                    thisIcon.removeClass().css('background-image','');

                    thisIcon.addClass('select-icon svg-menu-icon');

                    //add svg
                    thisIcon.css('background-image','url('+newIcon+')');

                    thisIcon.next().val(newIcon);
   
                }

                //close the popup
                Swal.close();

            });

            //when uploading a custom icon
            $('.swal-wide').on("click","#upload-icon-button", function(e){

                e.preventDefault();

                var image = wp.media({ 
                    title: 'Upload Image',
                    // mutiple: true if you want to upload multiple files at once
                    multiple: false
                }).open()
                .on('select', function(e){
                    // This will return the selected image from the Media Uploader, the result is an object
                    var uploaded_image = image.state().get('selection').first();
                    // We convert uploaded_image to a JSON object to make accessing it easier
                    var newIcon = uploaded_image.toJSON().url;
                    // Let's assign the url value to the input field

                    //remove existing
                    thisIcon.removeClass().css('background-image','');

                    thisIcon.addClass('select-icon svg-menu-icon');

                    //add svg
                    thisIcon.css('background-image','url('+newIcon+')');

                    thisIcon.next().val(newIcon);

                    Swal.close();
                        

                });
            });

        }
       
        }).then(function (result) {


        }).catch(Swal.noop)
    });
});