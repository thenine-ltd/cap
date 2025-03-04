(function ($) {
    $(document).ready(function () {

        let content = '',
            alertPopup = $("[data-id='assignment_submit_alert']");

        // open|close requirements
        $.each( $('.masterstudy-course-player-assignments__accordion-button'), function(i, accordion){
            $(accordion).click(function() {
                $(this).parent().find('.masterstudy-course-player-assignments__accordion-content').slideToggle();
                $(this).toggleClass('masterstudy-course-player-assignments__accordion-button_rotate');
            });
        });

        // submit assignment
        $('.masterstudy-course-player-navigation__send-assignment a').on('click', function (e) {
            e.preventDefault();

            const $buttonElement = $(this);

            if ( content.length < 1 && $('.masterstudy-attachment-media__materials').children().length < 1) {
                alertPopup.addClass('masterstudy-alert_open');
                return;
            }

            let formData = new FormData();
            formData.append('content', content);
            formData.append('action', 'stm_lms_accept_draft_assignment');
            formData.append('nonce', assignments_data.submit_nonce);
            formData.append('draft_id', assignments_data.draft_id);
            formData.append('course_id', assignments_data.course_id);
            formData.append('is_draft', $buttonElement.is('[data-id="masterstudy-course-player-assignments-save-draft-button"]'));
            $.ajax({
                url: assignments_data.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $buttonElement.addClass('masterstudy-button_loading');
                },
                success: function () {
                    location.reload();
                    $buttonElement.removeClass('masterstudy-button_loading');
                }
            });
        });
        // close alert
        alertPopup.find("[data-id='cancel']").click(closeAlertPopup);
        alertPopup.find('.masterstudy-alert__header-close').click(closeAlertPopup);

        function closeAlertPopup(e) {
            e.preventDefault();
            alertPopup.removeClass('masterstudy-alert_open');
        }

        if (typeof tinyMCE !== 'undefined') {
            getEditor();
        }

        MasterstudyAudioPlayer.init({
            selector: '.masterstudy-audio-player', 
            showDeleteButton: false
        });

        // watch wp-editor changes, disable "submit" button if wp-editor is empty
        function getEditor() {
            let editor = tinyMCE.get(assignments_data.editor_id);
            if ( editor ) {
                if (editor.iframeElement === undefined) {
                    setTimeout(function () {
                        getEditor();
                    }, 500);
                } else {
                    content = editor.getContent();
                    editor.on('keyup', function (e) {
                        content = editor.getContent();
                    });
                }
            }
        }
    });
})(jQuery);