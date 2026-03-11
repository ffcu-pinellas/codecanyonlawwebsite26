<script !src="">
    (function($)
    {
        "use strict";
        $('.site-logo').imageUploader({
            imagesInputName: 'logo',
            maxFiles: 1,
        });

        $('.site-favicon').imageUploader({
            imagesInputName: 'favicon',
            maxFiles: 1,
        });

        $('.site_tag_image').imageUploader({
            imagesInputName: 'site_tag_image',
            maxFiles: 1,
        });
    })(jQuery);



</script>
