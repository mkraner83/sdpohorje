(function ($) {
    function initTrainingDocumentUpload() {
        $(document).on('click', '.sdp-training-doc-upload', function (event) {
            event.preventDefault();

            var button = $(this);
            var input = button.closest('.sdp-training-doc-file-field').find('.sdp-training-doc-file-url');

            if (!input.length || typeof wp === 'undefined' || !wp.media) {
                return;
            }

            var frame = wp.media({
                title: 'Select training document',
                button: {
                    text: 'Use this file',
                },
                multiple: false,
            });

            frame.on('select', function () {
                var attachment = frame.state().get('selection').first().toJSON();

                if (attachment && attachment.url) {
                    input.val(attachment.url).trigger('change');
                }
            });

            frame.open();
        });
    }

    $(document).ready(function () {
        initTrainingDocumentUpload();
    });
})(jQuery);
