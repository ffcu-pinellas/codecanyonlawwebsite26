<script>
    (function($)
    {
        "use strict";
        $('.deleteRow').on('click', deleteASingleRow);

        function deleteASingleRow(){
            swal({
                title: "{{__('Are you sure?')}}",
                text: "{{__('Once you delete, You can not recover this data and related files.')}}",
                icon: "warning",
                dangerMode: true,
                buttons: {
                    cancel: true,
                    confirm: {
                        text: "Delete",
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
