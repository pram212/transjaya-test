<div class="btn-group">
    <a href="{{ route('master.chartofaccount.edit', compact('chartofaccount')) }}" class="btn btn-info">
        Edit
    </a>
    <button type="button" data-url="{{ route('master.chartofaccount.destroy', compact('chartofaccount')) }}" class="btn btn-danger btn-delete">
        Hapus
    </button>
</div>
