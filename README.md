# Atte(勤怠管理アプリ)
勤怠管理アプリです。
![トップ画面](src/top.png)
## 作成した目的
クライアント企業からの、人事評価用の勤怠管理システム構築を想定し、
実践に近い形での開発過程をアウトプットとして記録するため作成しました。

## URL
- 開発環境ホーム：http://localhost/
- 開発環境ユーザー一覧ページ：http://localhost/users
- phpMyAdmin：http://localhost:8080/
- MailHog：http://localhost:8025/
- 本番環境ホーム：http://54.65.247.104/
- 本番環境ユーザー一覧ページ：http://54.65.247.104/users
- 本番環境 phpMyAdmin：http://54.65.247.104:8080/
- 本番環境 MailHog：http://54.65.247.104:8025/

## 使用技術(実行環境)
- PHP8.3.9
- Laravel8.83.27
- MYSQL8.0.26

## 機能一覧
会員登録、メール認証、ログイン、ログアウト、勤務の開始と終了、休憩の開始と終了、日付別勤怠情報取得、日付検索、ページネーション、ユーザー一覧情報取得、ユーザー情報検索、ユーザー別勤怠情報取得  
※ユーザー登録時にメール認証機能が必須のため、上記URLのMailHogに送信されたメールにて認証をお願いいたします。

## 環境構築
**Dockerビルド**
1. リポジトリからクローン
```bash
git clone git@github.com:eto0831/reservation-2.git
```
2. リポジトリの設定
```bash
git remote set-url origin git@github.com:eto0831/reservation-x.git
```
3. サブモジュールの更新
```bash
git submodule update --init --recursive
```
4. DockerDesktopアプリを立ち上げる
5. ドッカーのビルド
```bash
docker-compose up -d --build
```


**Laravel環境構築**
1. コンテナに入る
```bash
docker-compose exec php bash
```
2. コンポーザーのインストール
```bash
composer install
```
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更。または新しく「.env」ファイルを作成
```bash
cp .env.example .env
```
4. .envに以下の環境変数を追加
``` text
APP_NAME=Rese

BASE_URL=http://localhost/storage

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=test@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

6. アプリケーションキーの作成
``` bash
php artisan key:generate
```

7. マイグレーションの実行
``` bash
php artisan migrate
```

8. シーディングの実行
``` bash
php artisan db:seed
```
9. シンボリックリンクの作成
```bash
php artisan storage:link
```
10. 画像の貼り付け
```text
reservation-2/src/storage/app/public/images/shops
```
11.  PHPコンテナを出る
```bash
exit
```
12. ドッカーを落とす
```bash
docker-compose down
```
13. 再ビルド
```bash
docker-compose up -d --build
```
14. 権限
```bash
sudo chmod -R 775 /var/www/storage
```
```bash
sudo chmod -R 775 bootstrap/cache
```

## テーブル設計
![テーブル設計](src/table.png)

## ER図
![ER図](Rese.png)

## テストアカウントおよび確認ができるサンプルケース
シーディングを実行すると下記アカウントおよびデータの作成が行われます。

メールアドレス：popo1@example.com ～ popo35@example.com  
各ユーザー名：@の前の部分(例：popo1@example.comの場合は popo1)  
パスワード：popo1212 （共通）  

【確認ができるサンプルケース】  
AWS上で予約照合する際、カメラのアクセスを許可するため、下記URLにアクセスして設定を変更してください。（クロームをご使用ください。）その他のブラウザでは下記手順と同様の操作をご確認の上、実施してください。
chrome://flags/
Insecure origins treated as secureで検索
http://54.249.56.110/reservation/scan
を入力しタブを停止中から有効に変更し。再起動ボタンを押し、クロームを再起動してください。
再起動後カメラの許可を求められますので許可してください。
## 注意事項
.envファイルがスクールでの通常のプロジェクトの設定と少し異なっているため、
お手数ですが上記のLaravel環境構築に記載の環境変数をご入力ください。
