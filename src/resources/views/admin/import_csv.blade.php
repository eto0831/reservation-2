@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/import_csv.css') }}">
@endsection

@section('content')
    <div class="csv-import-container">
        @if ($errors->any())
            <div class="csv-alert">
                <ul class="csv-alert-list">
                    @foreach ($errors->all() as $error)
                        <li class="csv-alert-item">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="csv-form-wrapper">
            <div class="csv-form-title">
                <h2 class="title-heading">CSVインポートによる店舗追加</h2>
            </div>
            <form action="{{ route('admin.csv.store') }}" method="post" enctype="multipart/form-data" class="csv-form">
                @csrf
                <div class="csv-form-group">
                    <label for="csv-file" class="csv-form-label">CSVファイル <span class="required">*</span></label>
                    <input type="file" name="csv" id="csv-file" class="csv-form-input">
                    @error('csv')
                        <p class="csv-form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="csv-form-actions">
                    <button type="submit" class="csv-form-submit">インポート</button>
                </div>
            </form>

        </div>
        <div class="back__button">
            <a href="/admin/dashboard">戻る</a>
        </div>
    </div>
@endsection
