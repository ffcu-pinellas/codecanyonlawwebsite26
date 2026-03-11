<script>
    (function($)
    {
        "use strict";
        $(document).ready(function () {
            /* =============== DEMO =============== */
            // menu items
            var arrayjson;
            $.ajax({
                type: 'get',
                url: '{{ route('admin.menu.item.create') }}',
                data:{
                    'menu': {{ $category->id }},
                },
                success:function (data){
                    arrayjson = data;
                    memuBulder(arrayjson)
                }
            })

            function memuBulder(arrayjson) {
                // icon picker options
                var iconPickerOptions = {searchText: "Buscar...", labelHeader: "{0}/{1}"};
                // sortable list options
                var sortableListOptions = {
                    placeholderCss: {'background-color': "#cccccc"}
                };

                var editor = new MenuEditor('myEditor', {listOptions: sortableListOptions, iconPicker: iconPickerOptions});
                editor.setForm($('#frmEdit'));
                editor.setUpdateButton($('#btnUpdate'));
                editor.setData(arrayjson);
                $('#btnReload').on('click', function () {
                    editor.setData(arrayjson);
                });

                $('#btnOutput').on('click', function () {
                    var str = editor.getString();
                    // $("#out").text(str);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'post',
                        url: '{{ route('admin.menu.item.store') }}',
                        data: {
                            'menu': {{ $category->id }},
                            'items': str
                        },
                        success: function (data) {
                            if (data.alert_type == 'success'){
                                toastr.success(data.message);
                            }
                        }
                    })

                });

                $("#btnUpdate").on("click", function () {
                    editor.update();
                });

                $('#btnAdd').on("click", function () {
                    editor.add();
                });
            }
        });
    })(jQuery);

</script>
