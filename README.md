# Inertia Demo with FrankenPHP

Laravel + React + Inertia.js + FrankenPHP のサンプルアプリケーション

## 環境構築

### 必要なツール

- Docker Desktop

### セットアップ手順

1. **リポジトリのクローン**

   ```bash
   git clone <repository-url>
   cd inertia-demo
   ```

2. **Docker 環境の起動**

   ```bash
   docker-compose up -d --build
   ```

3. **アプリケーションの初期化**

   コンテナが起動後に実行

   ```bash
   # Composer依存関係のインストール
   docker-compose exec app composer install
   # Laravel設定
   docker-compose exec app php artisan key:generate
   docker-compose exec app php artisan migrate
   ```

   ```bash
   # Node.js依存関係のインストール
   docker compose exec node npm install
   ```

4. **アクセス確認**

   ブラウザで http://localhost:8000 にアクセス

## 開発環境

### 使用技術

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: React 19 + [Inertia.js](https://inertiajs.com/) 2.0
- **Styling**: Tailwind CSS 4.0
- **Database**: PostgreSQL 15
- **Web Server**: [FrankenPHP](https://frankenphp.dev/) (ワーカーモード対応)
  - Go 製の高性能 PHP アプリケーションサーバー
  - 従来の PHP-FPM + Nginx より高速
  - ワーカーモードでアプリケーションを常駐化

### 開発用コマンド

```bash
# フロントエンド開発サーバー起動
docker compose exec node npm run dev

# テスト実行
docker compose exec app php artisan test
```
