@extends('layouts.app')

@section('title', 'Transaksi')
@section('header', 'Transaksi Baru')

@section('content')
    <form action="{{ route('transaction.store') }}" method="POST" class="container" id="form-transaksi">
        @csrf
        <div class="row shadow rounded mb-2 p-2">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="" class="label">Tanggal</label>
                    <input type="date" class="form-control" name="date" value="{{ old('date') }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="" class="label">Keterangan</label>
                    <input type="text" class="form-control" name="description" value="{{ old('description') }}">
                </div>
            </div>
        </div>
        <div class="row shadow rounded p-3 mb-2">
            <div class="table-responsive">
                <table class="table table-bordered" id="table-transaksi">
                    <thead>
                        <tr>
                            <th style="width: 30%">COA</th>
                            <th>Debet</th>
                            <th>Kredit</th>
                            <th style="width: 20px">
                                <button type="button" class="btn btn-sm btn-success" id="add-row">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Row akan ditambahkan dengan JS -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-right">Total</th>
                            <th><span id="total-debet">0</span></th>
                            <th><span id="total-kredit">0</span></th>
                            <th></th>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>
        <div class="row mt-5">
            <button class="btn btn-primary">Simpan</button>
        </div>
    </form>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            function createRow() {
                let row = `
                        <tr>
                            <td>
                                <select class="form-control coa-select" name="chart_of_account_id[]"></select>
                            </td>
                            <td><input type="number" class="form-control debet" name="debet[]" step="0.01" min="0"></td>
                            <td><input type="number" class="form-control credit" name="credit[]" step="0.01" min="0"></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger remove-row">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                $('#table-transaksi tbody').append(row);
                initSelect2(); // supaya select2-nya jalan di row baru
            }

            function initSelect2() {
                $('.coa-select').select2({
                    placeholder: 'Pilih Akun',
                    width: '100%',
                    ajax: {
                        url: '{!! route('option.coa') !!}', // ganti sesuai route-mu
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: data.map(item => ({
                                    id: item.id,
                                    text: `${item.code} - ${item.name}`
                                }))
                            };
                        }
                    }
                });
            }

            $('#add-row').on('click', function() {
                createRow();
            });

            $('#table-transaksi').on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
            });

            function updateTotals() {
                let totalDebet = 0;
                let totalKredit = 0;

                $('.debet').each(function() {
                    totalDebet += parseFloat($(this).val()) || 0;
                });

                $('.credit').each(function() {
                    totalKredit += parseFloat($(this).val()) || 0;
                });

                $('#total-debet').text(totalDebet.toLocaleString('id-ID', {
                    minimumFractionDigits: 2
                }));
                $('#total-kredit').text(totalKredit.toLocaleString('id-ID', {
                    minimumFractionDigits: 2
                }));
            }


            // validasi input disable
            $('#table-transaksi').on('input', 'input[name="debet[]"]', function() {
                if ($(this).val() > 0) {
                    $(this).closest('tr').find('input[name="credit[]"]').val('0').prop('readonly', true);
                } else {
                    $(this).closest('tr').find('input[name="credit[]"]').prop('readonly', false);
                }
                updateTotals();
            });

            $('#table-transaksi').on('input', 'input[name="credit[]"]', function() {
                if ($(this).val() > 0) {
                    $(this).closest('tr').find('input[name="debet[]"]').val('0').prop('readonly', true);
                } else {
                    $(this).closest('tr').find('input[name="debet[]"]').prop('readonly', false);
                }
                updateTotals();
            });

            // validasi balance
            $('#form-transaksi').on('submit', function(e) {
                let totalDebit = 0;
                let totalKredit = 0;

                // Hitung total dari input debit
                $('.debet').each(function() {
                    let val = parseFloat($(this).val()) || 0;
                    totalDebit += val;
                });

                // Hitung total dari input kredit
                $('.credit').each(function() {
                    let val = parseFloat($(this).val()) || 0;
                    totalKredit += val;
                });

                // Validasi
                if (totalDebit !== totalKredit) {
                    e.preventDefault();
                    alert('Total debit dan kredit harus sama!');
                    return false;
                }

                // Kalau sama, lanjut submit
            });

        });
    </script>
@endpush
