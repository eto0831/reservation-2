@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/import_csv.css') }}">
@endsection

@section('content')
    <div class="csv-import__container">
        @if ($errors->any())
            <div class="csv-alert">
                <ul class="csv-alert__list">
                    @foreach ($errors->all() as $error)
                        <li class="csv-alert__item">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="csv-success">
                <p class="csv-success__item">{{ session('success') }}</p>
            </div>
        @endif


        <div class="csv-form__wrapper">
            <div class="csv-form__title">
                <h2 class="csv-form__title-heading">CSVインポートによる店舗追加</h2>
            </div>
            <form action="{{ route('admin.csv.store') }}" method="post" enctype="multipart/form-data" class="csv-form">
                @csrf
                <div class="csv-form__group">
                    <label for="csv-file" class="csv-form__label">CSVファイル <span class="required">*</span></label>
                    <input type="file" name="csv" id="csv-file" class="csv-form__input">
                    @error('csv')
                        <p class="csv-form__error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="csv-form__button">
                    <button type="submit" class="csv-form__button-submit">インポート</button>
                </div>
            </form>

        </div>
        <div class="back__button">
            <a class="back__button-submit" href="/admin/dashboard">戻る</a>
        </div>
    </div>
@endsection
