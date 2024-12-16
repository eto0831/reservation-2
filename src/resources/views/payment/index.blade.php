@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/payment/index.css') }}">
@endsection

@section('content')
<div class="container">
    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    <div class="p-5">
        <div class="col-6 card">
            <div class="card-header">Stripe決済</div>
            <div class="card-body">
                <h3>予約内容</h3>
                <p><strong>店舗名:</strong> {{ $shop->shop_name }}</p>
                <p><strong>日付:</strong> {{ $reservationData['reserve_date'] }}</p>
                <p><strong>時間:</strong> {{ $reservationData['reserve_time'] }}</p>
                <p><strong>人数:</strong> {{ $reservationData['guest_count'] }} 人</p>

                <form id="payment-form" action="{{ route('payment.process') }}" method="POST">
                    @csrf
                    <div>
                        <label for="course">コース選択</label>
                        <select id="course" name="course" class="form-control" required>
                            <option value="matsu">松 - 10000円</option>
                            <option value="take">竹 - 8000円</option>
                            <option value="ume">梅 - 5000円</option>
                        </select>
                    </div>
                    <div>
                        <label for="card-element">クレジットカード情報</label>
                        <div id="card-element" class="form-control"></div>
                    </div>
                    <div id="card-errors" class="text-danger"></div>
                    <button id="submit-button" class="mt-3 btn btn-primary" type="submit" onclick="return confirm('この内容で決済しますか？')">
                        支払い
                    </button>
                    <input type="hidden" name="payment_method" id="payment_method">
                </form>

                <!-- スピナーの追加 -->
                <div id="loading-spinner" style="display: none;">
                    <p>処理中です...</p>
                </div>

                <script src="https://js.stripe.com/v3/"></script>
                <script>
                    const stripe = Stripe('{{ config('cashier.key') }}');
                    const elements = stripe.elements();
                    const cardElement = elements.create('card');
                    cardElement.mount('#card-element');
                
                    const form = document.getElementById('payment-form');
                    const submitButton = document.getElementById('submit-button');
                    const spinner = document.getElementById('loading-spinner');
                
                    form.addEventListener('submit', async (event) => {
                        event.preventDefault();
                
                        // ボタンを無効化してスピナーを表示
                        submitButton.disabled = true;
                        spinner.style.display = 'block';
                
                        const { paymentMethod, error } = await stripe.createPaymentMethod(
                            'card', cardElement, {
                                billing_details: { name: '{{ auth()->user()->name }}' }
                            }
                        );
                
                        if (error) {
                            document.getElementById('card-errors').textContent = error.message;
                            submitButton.disabled = false; // エラー時に再度有効化
                            spinner.style.display = 'none';
                        } else {
                            document.getElementById('payment_method').value = paymentMethod.id;
                            form.submit();
                        }
                    });
                </script>

                @endsection