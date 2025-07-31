@extends('layouts.app')

@section('title', 'Transaksi')
@section('header', 'Transaksi')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3 rounded p-3 shadow">
        <a href="{{ route('transaction.create') }}" class="btn btn-primary">
            Transaksi Baru
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-sm" id="dataTable" width="100%" cellspacing="0">
            <thead class="">
                <tr>
                    {{-- <th>No</th> --}}
                    <th>Tanggal</th>
                    <th>Kode Akun</th>
                    <th>Nama Akun</th>
                    <th>Deskripsi</th>
                    <th>Debet</th>
                    <th>Kredit</th>
                    {{-- <th>Opsi</th> --}}
                </tr>
            </thead>
        </table>
    </div>

@endsection

@push('script')
    <script>
        $(document).ready(function() {

            table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('transaction.index') }}",
                    data: function(d) {
                        // d.category_id = $('#category_coa_id').val(); // ambil dari select2
                        // d.type = $("#type").val();
                    }
                },
                columns: [
                    // {
                    //     data: 'DT_RowIndex',
                    //     name: 'DT_RowIndex',
                    //     orderable: false,
                    //     searchable: false
                    // },
                    {
                        data: 'date'
                    },
                    {
                        data: 'coa_code'
                    },
                    {
                        data: 'coa_name'
                    },
                    {
                        data: 'description',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'debet'
                    },
                    {
                        data: 'credit'
                    },
                    // {
                    //     data: 'action',
                    //     orderable: false,
                    //     searchable: false,
                    // },
                ],
            });

        });
    </script>
@endpush
