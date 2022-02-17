<script>
    "use strict";
    $(document).ready(function() {

        $('.dropify').dropify();

        var quill = new Quill('#input_description', {
            theme: 'snow'
        });
        var address = $("#description").val();
        quill.clipboard.dangerouslyPasteHTML(address);
        quill.root.blur();
        $('#input_description').on('keyup', function(){
            var input_description = quill.container.firstChild.innerHTML;
            $("#description").val(input_description);
        });
        $(".select2").select2();
    });
</script>
