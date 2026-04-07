<div class="alert alert-{{ $type ?? 'info' }}" role="alert">
    @if($title ?? false)
        <strong>{{ $title }}</strong>
    @endif
    {{ $slot }}
</div>

