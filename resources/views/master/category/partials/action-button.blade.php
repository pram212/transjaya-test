<div class="btn-group">
    <a href="{{ route('master.kategori.edit', compact('categoryCoa')) }}" class="btn btn-info">
        Edit
    </a>
    <button type="button" data-url="{{ route('master.kategori.destroy', compact('categoryCoa')) }}" class="btn btn-danger btn-delete">
        Hapus
    </button>
</div>
