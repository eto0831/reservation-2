@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endsection

@section('content')
    <div class="message__content">
        <div class="message__wrapper">
            <div class="message__item">
                <p>
                    ご予約ありがとうございます
                </p>
            </div>
            <div class="return__button">
                <a class="form__button blue-button" href="/mypage">戻る</a>
            </div>
        </div>
    </div>
@endsection
