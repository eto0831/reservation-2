<!DOCTYPE html>
<html>

<head>
    <title>予約リマインドメール</title>
</head>

<body
    style="font-family: Arial, sans-serif; font-size: 14px; line-height: 1.6; color: #333; background-color: #f8f9fa; margin: 0; padding: 0;">

    <!-- メールのコンテナ -->
    <div
        style="width: 90%; max-width: 600px; margin: 20px auto; background-color: #ffffff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);">

        <!-- タイトル -->
        <h2 style="font-size: 20px; font-weight: bold; color: #007bff; margin-bottom: 10px;">予約リマインドメール</h2>

        <!-- 内容 -->
        <p style="margin: 15px 0;">{{ $reservation->user->name }}様</p>
        <p style="margin: 15px 0;">以下の内容で予約されています：</p>

        <!-- 予約詳細 -->
        <ul style="list-style: none; padding: 0;">
            <li style="margin-bottom: 10px;"><strong>店名:</strong> {{ $reservation->shop->shop_name }}</li>
            <li style="margin-bottom: 10px;">
                <strong>予約日:</strong>
                {{ \Carbon\Carbon::parse($reservation->reserve_date)->locale('ja')->isoFormat('YYYY-MM-DD (ddd)') }}
            </li>
            <li style="margin-bottom: 10px;">
                <strong>時間:</strong>
                {{ \Carbon\Carbon::parse($reservation->reserve_time)->format('H:i') }}
            </li>
            <li style="margin-bottom: 10px;"><strong>人数:</strong> {{ $reservation->guest_count }}人</li>
        </ul>

        <p style="margin: 15px 0;">お忘れなくお越しください。</p>

        <!-- フッター -->
        <p style="margin-top: 20px; font-size: 12px; color: #666; text-align: center;">
            このメールは自動送信されています。返信はできませんのでご了承ください。
        </p>
    </div>
</body>

</html>