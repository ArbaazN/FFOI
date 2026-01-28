<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
function showToast(type, message) {
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "timeOut": 3000,
    };

    if (type === 'success') toastr.success(message);
    if (type === 'error') toastr.error(message);
    if (type === 'warning') toastr.warning(message);
    if (type === 'info') toastr.info(message);
}

// Auto-run on Laravel flash messages
@if(session('success'))
    showToast('success', "{{ session('success') }}");
@endif

@if(session('error'))
    showToast('error', "{{ session('error') }}");
@endif

@if($errors->any())
    @foreach($errors->all() as $error)
        showToast('error', "{{ $error }}");
    @endforeach
@endif
</script>
