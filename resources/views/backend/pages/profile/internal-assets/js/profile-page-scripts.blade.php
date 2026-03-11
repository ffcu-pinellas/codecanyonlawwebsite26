<script>

    (function ($) {

        "use strict";

        $('.admin-image').imageUploader({
            imagesInputName: 'photo',
            maxFiles: 1,
        });
        $('#deleteAccountBtn').on('click', function(){
            deleteAccountAlert();
        });

        function deleteAccountAlert() {
            swal({
                title: "{{__('Are you sure?')}}",
                text: "{{__('Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.')}}",
                icon: "warning",
                dangerMode: true,
                content: {
                    element: "input",
                    attributes: {
                        placeholder: "{{__('Type your password')}}",
                        type: "password",
                        required: true,
                    },
                },
                buttons: {
                    cancel: true,
                    confirm: {
                        text: "{{__('OK')}}",
                        value: true,
                        visible: true,
                        closeModal: true
                    },
                },
            }).then((value) => {
                if(value == ''){
                    swal(`You don\'t input your password.`,{
                        icon: "error",
                        dangerMode: true,
                    }).then(() => {
                        deleteAccountAlert();
                    });
                }else if(value){
                    document.getElementById('deleteForm').querySelector('input[name="password"]').value = value;
                    swal(`Your request has been processing.`,{
                        icon: "success",
                        dangerMode: true,
                    });
                    document.getElementById('deleteForm').submit();
                }
            });
        }

    })(jQuery);


</script>
