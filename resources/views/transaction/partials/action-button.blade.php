<div class="btn-group">
    <a href="{{ route('transaction.show', compact('transaction')) }}" class="btn btn-info">
        Detail
    </a>
    <a href="{{ route('transaction.edit', compact('transaction')) }}" class="btn btn-primary">
        Edit
    </a>
    <button type="button" data-url="{{ route('transaction.destroy', compact('transaction')) }}" class="btn btn-danger btn-delete">
        Hapus
    </button>
</div>
