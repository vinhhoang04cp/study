<span class="badge {{ $status ?? '' }}">
    @if($status === 'published')
        <i class="fas fa-check-circle"></i> Đã Xuất Bản
    @elseif($status === 'draft')
        <i class="fas fa-edit"></i> Bản Nháp
    @else
        {{ $status }}
    @endif
</span>

