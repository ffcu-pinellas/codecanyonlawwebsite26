<script>
    (function($)
    {
        "use strict";
        $('#add-menu-btn').on('click', showInsertModal);
        $('.edit-menu-btn').on('click', showEditModal);
        $('.row-delete-btn').on('click', deleteMenuCategoryRow);
        $('.view-list-menu-btn').on('click', viewMenuCategorylist);

        function showInsertModal(){
            $('#menuCategoryModal').modal('show');
        }

        function showEditModal(){
            $.ajax({
                type: 'get',
                url: 'category/'+$(this).attr('data-row')+'/edit',
                success:function (data){
                    $('#menuCategoryModal .modal-dialog').empty().append(data);
                    $('#menuCategoryModal').modal('show');
                }
            })
        }

        function deleteMenuCategoryRow(){
            swal({
                title: "{{__('Are you sure?')}}",
                text: "{{__('Once you delete, You can\'t recover this data')}}",
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
                    $(this).find('form').submit();
                }
            });
        }

        function viewMenuCategorylist(){
            $(this).find('form').submit();
        }
    })(jQuery);

</script>
