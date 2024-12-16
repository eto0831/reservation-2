@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/review/edit.css') }}">
@endsection

@section('content')
    <h1>レビュー編集</h1>
    <form action="{{ route('review.update', $review->id) }}" method="post">
        @csrf
        @method('PUT')
        <div>
            <label for="rating">評価:</label>
            <select name="rating" id="rating">
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div>
            <label for="comment">コメント:</label>
            <textarea name="comment" id="comment">{{ $review->comment }}</textarea>
        </div>
        <button type="submit" onclick="return confirm('内容この内容で更新しますか？')">更新</button>
    </form>
@endsection