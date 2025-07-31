@extends('layouts.app')

@section('title', 'Chart Of Account')
@php
    $header = Route::currentRouteName() == 'master.chartofaccount.create' ? 'Tambah Kategori' : 'Edit Kategori';
@endphp
@section('header', $header)

@section('content')
    @php
        $action =
            Route::currentRouteName() == 'master.chartofaccount.create'
                ? route('master.chartofaccount.store')
                : route('master.chartofaccount.update', ['chartofaccount' => $chartofaccount]);
    @endphp
    <div class="container">
        <form action="{{ $action }}" method="POST">
            @csrf
            @if (Route::currentRouteName() == 'master.chartofaccount.edit')
                @method('PUT')
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="category_coa_id">Kategori</label>
                        <select name="category_coa_id" id="category_coa_id" class="@error('category_coa_id') is-invalid @enderror" style="width: 100%">
                            @if (@$chartofaccount)
                                <option value="{{@$chartofaccount->categoryCoa->id}}" selected>{{@$chartofaccount->categoryCoa->name}}</option>
                            @endif
                        </select>
                        @error('category_coa_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="type">Tipe Akun</label>
                        <select name="type" id="type" class="@error('type') is-invalid @enderror" style="width: 100%">
                            <option value="">Tipe</option>
                            <option value="Asset" @selected(old('type', @$chartofaccount->type) == 'Asset')>Asset</option>
                            <option value="Kewajiban" @selected(old('type', @$chartofaccount->type) == 'Kewajiban')>Kewajiban</option>
                            <option value="Ekuitas" @selected(old('type', @$chartofaccount->type) == 'Ekuitas')>Ekuitas</option>
                            <option value="Pendapatan" @selected(old('type', @$chartofaccount->type) == 'Pendapatan')>Pendapatan</option>
                            <option value="Beban" @selected(old('type', @$chartofaccount->type) == 'Beban')>Beban</option>
                        </select>
                        @error('type')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="code">Kode</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" name="code"
                            value="{{ old('code', @$chartofaccount?->code) }}" id="code">
                        @error('code')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text"
                            class="form-control @error('name')
                            is-invalid
                        @enderror"
                            name="name" value="{{ old('name', @$chartofaccount?->name) }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12 d-flex justify-content-center">
                    <button style="submit" class="btn btn-primary mx-2">Simpan</button>
                    <a href="{{ route('master.chartofaccount.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>

        </form>
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
                placeholder: "Pilih kategori",
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
        });
    </script>
@endpush
