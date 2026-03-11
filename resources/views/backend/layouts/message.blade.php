@if($message = Session::get('success'))
    <script>
        (function($) {
            "use strict";
            $.notify("{{$message}}", {type:"success"})
        })(jQuery);
    </script>
@endif
@if($message = Session::get('error'))
    <script>
        (function($) {
            "use strict";
            $.notify("{{$message}}", {type:"danger"})
        })(jQuery);
    </script>
@endif
