@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/import_csv.css') }}">
@endsection

@section('content')
<div class="mail__alert">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

</div>


<div class="csv__form">
    <form action="{{ route('admin.csv.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">CSVファイル</span>
            </div>
            <div class="form__group-content">
                <div class="form__group__input">
                    <input type="file" name="csv">
                </div>
                <div class="form__error">
                    @error('csv')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">インポート</button>
    </form>
</div>
@endsection