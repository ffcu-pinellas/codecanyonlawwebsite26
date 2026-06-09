<script>
    (function($) {
        "use strict";
        // Author code here
        $(document.body).on('click', '.user_btn', function() {
            const user_id = $(this).data('id');
            const loggedInUserId = {{ auth()->id() }};
            
            // Set dynamic delete route
            const deleteUrl = '{{ route("admin.user.destroy", ":id") }}'.replace(':id', user_id);
            $('#deleteUserForm').attr('action', deleteUrl);
            
            // Hide delete button if editing self
            if (user_id === loggedInUserId) {
                $('#usermodal .btn-danger').hide();
            } else {
                $('#usermodal .btn-danger').show();
            }
            
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
