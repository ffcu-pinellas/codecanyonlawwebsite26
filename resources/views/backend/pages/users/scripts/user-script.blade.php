<script>
    (function($) {
        "use strict";
        // Author code here
        $(document.body).on('click', '.user_btn', function() {
            const user_id = $(this).data('id');
            $('#user_html').html('{{ __('Loading...') }}');
            $.ajax({
                type: 'GET',
                url: '{{ route("admin.user.index") }}',
                data: {
                    id: user_id
                },
                success: function(data) {
                    $('#user_html').html(data.data);
                }
            })
        });

    })(jQuery);
</script>
