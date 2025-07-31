@extends('layouts.app')

@section('title', 'Kategori Coa')
@section('header', 'Kategori Coa')

@section('content')
    <div class="flex justify-content-between mb-3 shadow rounded p-3">
        <a href="{{ route('master.kategori.create') }}" class="btn btn-primary">
            Tambah Kategori
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-sm" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Opsi</th>
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
                ajax: "{{ route('master.kategori.index') }}",
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'action'
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
