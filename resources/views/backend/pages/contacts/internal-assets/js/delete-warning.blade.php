<script>
    (function($)
    {
        "use strict";
        $('.sliderDestroyBtn').on('click', deleteslider);

        function deleteslider(){
            swal({
                title: "{{__('Are you sure?')}}",
                text: "{{__('Once you delete, You can not recover this contact message and related files.')}}",
                icon: "warning",
                dangerMode: true,
                buttons: {
                    cancel: true,
                    confirm: {
                        text: "{{__('Delete')}}",
                        value: true,
                        visible: true,
                        closeModal: true
                    },
                },
            }).then((value) => {
                if(value){
                    $(this).find('.deleteForm').submit();
                }
            });
        }
    })(jQuery);

</script>
