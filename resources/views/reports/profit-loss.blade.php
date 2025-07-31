@extends('layouts.app')
@section('title', 'Laporan')
@section('header', 'Laporan Laba/Rugi')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between mb-2">
            <p>
                Periode: {{ date('d M Y', strtotime(request('start'))) }} s/d {{ date('d M Y', strtotime(request('end'))) }}
            </p>
            <div>
                <a href="{{ route('laporan.profit', ['start' => request('start'), 'end' => request('end'), 'export' => 1]) }}"
                    class="btn btn-success">Export Excel</a>
                <button class="btn btn-info" data-toggle="modal" data-target="#exampleModal">Filter</button>
            </div>
        </div>

        <section id="content">
            <div class="table-responsive overflow-auto">
                <table class="table table-sm">
                    <thead class="bg-dark">
                        <tr>
                            <th>Kategori</th>
                            @foreach ($allDates as $date)
                                <th>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-income">
                            <th colspan="{{ count($allDates) + 1 }}">Income</th>
                        </tr>
                        @foreach ($income['data'] as $item)
                            <tr class="@if (@$item['category'] === 'Total') font-weight-bold @endif bg-income">
                                <td class="pl-4">{{ $item['category'] }}</td>
                                @foreach ($allDates as $date)
                                    <td>{{ number_format(@$item[$date], 0, ',', '.') }}</td>
                                @endforeach
                            </tr>
                        @endforeach

                        <tr class="bg-expense">
                            <th colspan="{{ count($allDates) + 1 }}">Expense</th>
                        </tr>
                        @foreach ($expense['data'] as $item)
                            <tr class="@if (@$item['category'] === 'Total') font-weight-bold @endif bg-expense">
                                <td class="pl-4">{{ $item['category'] }}</td>
                                @foreach ($allDates as $date)
                                    <td>{{ number_format(@$item[$date], 0, ',', '.') }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-dark">
                        <th>Net Income</th>
                        @foreach ($allDates as $date)
                            <th>{{ number_format($netIncome[$date], 0, ',', '.') }}</th>
                        @endforeach
                    </tfoot>
                </table>
            </div>
        </section>

        {{-- filter modal --}}
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Period</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="form-filter">
                            <div class="form-group">
                                <label for="start" class="label">Dari</label>
                                <input type="date" class="form-control" name="start" value="{{ request('start') }}">
                            </div>
                            <div class="form-group">
                                <label for="end" class="label">Sampai</label>
                                <input type="date" class="form-control" name="end" value="{{ request('end') }}">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btn-filter">Cari</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .bg-income {
            background-color: #ddf5c2
        }

        .bg-expense {
            background-color: #f7daca
        }
    </style>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $("#btn-filter").click(function(e) {
                e.preventDefault();
                $("#form-filter").submit();
            });
        });
    </script>
@endpush
