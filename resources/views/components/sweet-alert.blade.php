@props([
    'icon' => 'info',
    'title' => '',
    'text' => '',
    'redirect' => null
])

<script>
    Swal.fire({
        icon: '{{ $icon }}',
        title: '{{ $title }}',
        text: '{{ $text }}',
        @if($redirect)
        showConfirmButton: true,
        confirmButtonText: 'OK',
        willClose: () => {
            window.location.href = '{{ $redirect }}';
        }
        @endif
    });
</script> 