@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl'
])

@php
$maxWidthClass = match ($maxWidth) {
    'sm' => 'modal-sm',
    'md' => '',
    'lg' => 'modal-lg',
    'xl' => 'modal-xl',
    '2xl' => 'modal-xl',
    default => 'modal-lg',
};
@endphp

<div class="modal fade" id="modal-{{ $name }}" tabindex="-1" aria-hidden="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered {{ $maxWidthClass }}">
        <div class="modal-content bg-white rounded-2xl shadow-xl overflow-hidden border-0">
            {{ $slot }}
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('modal-{{ $name }}');
    if (!el) return;

    let modal = null;
    function getModal() {
        if (!modal) modal = new bootstrap.Modal(el, { keyboard: true });
        return modal;
    }

    window.addEventListener('open-modal', function (e) {
        if (String(e.detail) === '{{ $name }}') getModal().show();
    });

    window.addEventListener('close-modal', function (e) {
        if (String(e.detail) === '{{ $name }}') getModal().hide();
    });

    @if ($show)
    getModal().show();
    @endif
});
</script>
@endpush
