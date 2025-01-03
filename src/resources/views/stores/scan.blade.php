@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/stores/scan.css') }}">
@endsection

@section('content')
<div class="scan-qrcode">
  <h2>QRコードを読み込んでください</h2>

  <!-- エラーメッセージ -->
  @if(session('error'))
  <div class="alert alert-danger">
    {{ session('error') }}
  </div>
  @endif

  <!-- カメラ映像 -->
  <video id="qr-video" autoplay></video>
  <canvas id="qr-canvas" style="display:none;"></canvas>
  <p id="qr-result"></p>

  <!-- 戻るボタン -->
  <a href="/owner/dashboard" class="btn-back">戻る</a>
</div>

<script src="{{ asset('js/jsQR/dist/jsQR.js') }}"></script>
<script>
  const video = document.getElementById('qr-video');
    const canvas = document.getElementById('qr-canvas');
    const context = canvas.getContext('2d');
    const result = document.getElementById('qr-result');

    // カメラ映像を取得して表示
    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
        .then(function(stream) {
            video.srcObject = stream;
            video.setAttribute("playsinline", true); // iOSでインライン再生を有効にする
            video.play();
            requestAnimationFrame(tick);
        });

    // QRコードの読み取り
    function tick() {
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvas.height = video.videoHeight;
            canvas.width = video.videoWidth;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height);

            if (code) {
                result.textContent = code.data;

                // QRコードのデータから予約IDを取得
                let reservationId = code.data.split('/').pop();

                // 予約IDが数値でない場合は空文字列を設定
                if (!reservationId || isNaN(reservationId) || !/^\d+$/.test(reservationId)) {
                    reservationId = '';
                }

                // verifyページにリダイレクト
                window.location.href = "{{ route('reservation.verify', '') }}/" + reservationId;
            }
        }
        requestAnimationFrame(tick);
    }
</script>
@endsection