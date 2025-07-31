@extends('layouts.app')

@section('title', 'Chart Of Account')
@section('header', 'Chart Of Account')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3 rounded p-3 shadow">
        <a href="{{ route('master.chartofaccount.create') }}" class="btn btn-primary">
            Tambah Chart Of Account
        </a>
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal">
            Filter
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-sm" id="dataTable" width="100%" cellspacing="0">
            <thead class="">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Tipe Akun</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Opsi</th>
                </tr>
            </thead>
        </table>
    </div>

    {{-- modal filter --}}
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Filter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" style="width: 200px">
                                <select name="type" id="type" style="width: 100%">
                                    <option value="" selected>Tipe</option>
                                    <option value="Asset">Asset</option>
                                    <option value="Kewajiban">Kewajiban</option>
                                    <option value="Ekuitas">Ekuitas</option>
                                    <option value="Pendapatan">Pendapatan</option>
                                    <option value="Beban">Beban</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group" style="width: 200px">
                                <select name="category_coa_id" id="category_coa_id" style="width: 100%">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btn-filter" data-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $("#type").select2({
                theme: "classic",
                placeholder: "Tipe Akun",
                allowClear: true,
            })

            $('#category_coa_id').select2({
                theme: "classic",
                placeholder: "Kategori",
                allowClear: true,
                ajax: {
                    url: "{!! route('option.category') !!}",
                    dataType: 'json',
                    processResults: function(data) {
                        return {
                            results: data.map(function(item) {
                                return {
                                    id: item.id,
                                    // text: `[${item.code}] ${item.name}`
                                    text: item.name
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

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
                    url: "{{ route('master.chartofaccount.index') }}",
                    data: function(d) {
                        d.category_id = $('#category_coa_id').val(); // ambil dari select2
                        d.type = $("#type").val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'code'
                    },
                    {
                        data: 'type',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'category',
                        orderable: false,
                        searchable: false,
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

            $("#btn-filter").click(function (e) {
                e.preventDefault();
                table.ajax.reload()
            });

        });
    </script>
@endpush
