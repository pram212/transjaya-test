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
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Amount</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>

@endsection

@push('script')
    <script>
        $(document).ready(function() {
            const notifySuccess = (title = "") => {
                Swal.fire({
                    position: 'top-end',
                    toast: true,
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    icon: 'success',
                    title: title,
                })
            }

            const notifyError = (title = "") => {
                Swal.fire({
                    position: 'top-end',
                    toast: true,
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    icon: 'error',
                    title: title,
                })
            }

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
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'description'
                    },
                    {
                        data: 'details_sum_debet'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                    },
                ],
            });

            // function delete detail
            $('#dataTable tbody').on('click', 'button.btn-delete', function() {
                var tr = $(this).closest('tr');
                var data = table.row(tr).data();
                var url = $(this).data('url')
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url,
                            type: 'DELETE',
                            dataType: "json",
                            success: function(response) {
                                notifySuccess(response)
                                table.ajax.reload()
                            }
                        })
                    }
                })
            });

        });
    </script>
@endpush
