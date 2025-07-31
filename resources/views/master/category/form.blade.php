@extends('layouts.app')

@section('title', 'Kategori Coa')
@php
    $header = Route::currentRouteName() == 'master.kategori.create' ? 'Tambah Kategori' : 'Edit Kategori'
@endphp
@section('header', $header)

@section('content')
    @php
        $action = Route::currentRouteName() == 'master.kategori.create'
            ? route('master.kategori.store')
            : route('master.kategori.update', [ 'categoryCoa' => $categoryCoa ]);
    @endphp
    <form action="{{ $action }}" method="POST">
        @csrf
        @if (Route::currentRouteName() == 'master.kategori.edit')
            @method('PUT')
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="name">Nama Kategori</label>
                    <input type="text" class="form-control @error('name')
                        is-invalid
                    @enderror" name="name" value="{{old('name', @$categoryCoa?->name)}}">
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="col-md-12 d-flex justify-content-center">
                <button style="submit" class="btn btn-primary mx-2">Simpan</button>
                <a href="{{ route('master.kategori.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>

    </form>
@endsection
