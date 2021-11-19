<script src="{{ asset('plugins/alertifyjs/alertify.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Include the Quill library -->
<script src="{{ asset('js/quill.js') }}"></script>
<script src="{{ asset('plugins/dropify/dist/js/dropify.min.js') }}"></script>
<!-- Select 2 js -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

<script>
    $(document).ready(function(){
        $(window).scrollTop(0);

        $('.dropify').dropify();

        $(".flatpickr").flatpickr({
            enableTime: false
        });

        $(".flatpickr-pick-time").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true
        });

        $(".today-flatpickr").flatpickr({
            enableTime: false,
            defaultDate: "today"
        });

        $(".dateTime-flatpickr").flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });

        $(".select2").select2();
    });

    function selectChange(val) {
        $('#myForm').submit();
    }
</script>
