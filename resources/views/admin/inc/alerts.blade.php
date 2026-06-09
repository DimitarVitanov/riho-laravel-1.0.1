<script>
    $(document).ready(function() {
        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif
        @if (session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif
        @if (session('info'))
            toastr.info("{{ session('info') }}");
        @endif
    });
</script>
