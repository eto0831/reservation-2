@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/payment/index.css') }}">
@endsection

@section('content')
<div class="container">
    @if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="p-5">
        <div class="card">
            <h2 class="card-header">Stripe決済</h2>
            <div class="card-body">
                <h3>予約内容</h3>
                <p class="card-text"><strong>店舗名:</strong> {{ $shop->shop_name }}</p>
                <p class="card-text"><strong>日付:</strong> {{ $reservationData['reserve_date'] }}</p>
                <p class="card-text"><strong>時間:</strong> {{ $reservationData['reserve_time'] }}</p>
                <p class="card-text"><strong>人数:</strong> {{ $reservationData['guest_count'] }} 人</p>

                <form id="payment-form" action="{{ route('payment.process') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="course">コース選択</label>
                        <select id="course" name="course" class="form-control" required>
                            <option value="matsu">松 - 10000円</option>
                            <option value="take">竹 - 8000円</option>
                            <option value="ume">梅 - 5000円</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="card-element">クレジットカード情報</label>
                        <div id="card-element" class="form-control"></div>
                        <div id="card-errors" class="text-danger"></div>
                    </div>
                    <div class="form-group">
                        <button id="submit-button" class="btn" type="submit" onclick="return confirm('この内容で決済しますか？')">
                            支払い
                        </button>
                    </div>
                    <input type="hidden" name="payment_method" id="payment_method">
                </form>

                <!-- スピナーの追加 -->
                <div id="loading-spinner" style="display: none;">
                    <p>処理中です...</p>
                </div>
            </div>
        </div>

        <!-- 戻るボタン -->
        <a href="/mypage" class="btn-back">戻る</a>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ config('cashier.key') }}');
    const elements = stripe.elements();

    const cardElement = elements.create('card', {
        hidePostalCode: true,
        style: {
            base: {
                fontSize: '16px',
                color: '#32325d',
                fontFamily: 'Arial, sans-serif',
                '::placeholder': { color: '#aab7c4' }
            },
            invalid: { color: '#fa755a', iconColor: '#fa755a' }
        }
    });

    cardElement.mount('#card-element');

    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');
    const spinner = document.getElementById('loading-spinner');
    const cardErrors = document.getElementById('card-errors');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        submitButton.disabled = true;
        spinner.style.display = 'block';
        cardErrors.textContent = '';

        const { paymentMethod, error } = await stripe.createPaymentMethod({
            type: 'card',
            card: cardElement,
            billing_details: { name: '{{ auth()->user()->name }}' }
        });

        if (error) {
            cardErrors.textContent = error.message;
            submitButton.disabled = false;
            spinner.style.display = 'none';
        } else {
            document.getElementById('payment_method').value = paymentMethod.id;
            form.submit();
        }
    });
</script>
@endsection
