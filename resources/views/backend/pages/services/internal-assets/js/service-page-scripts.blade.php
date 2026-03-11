<script !src="">
    (function($)
    {
        "use strict";
        $('.service_icon').imageUploader({
            imagesInputName: 'icon',
            maxFiles: 1,
        });

        $('.featured_image').imageUploader({
            imagesInputName: 'featured_image',
            maxFiles: 1,
        });
    })(jQuery);



</script>
