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

   # フロントエンド開発サーバー起動
   docker compose exec node npm run dev
   ```

4. **アクセス確認**

   ブラウザで <http://localhost:8000> にアクセス

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
- **コード品質ツール**:
  - [PHPStan](https://phpstan.org/) - PHP静的解析
  - [Rector](https://getrector.com/) - PHPコードリファクタリング
  - [PHP CS Fixer](https://cs.symfony.com/) - コードスタイル自動修正
  - [Biome](https://biomejs.dev/) - JavaScript/TypeScript リンター・フォーマッター

### 開発用コマンド

```bash
# テスト実行
docker compose exec app php artisan test

# PHPコード品質チェック
docker compose exec app composer phpstan # 静的解析
docker compose exec app vendor/bin/rector --dry-run  # リファクタリング候補確認
docker compose exec app vendor/bin/php-cs-fixer fix  # コードスタイル確認

# テスト
docker compose exec app composer test
```

## CI (GitHub Actions)

プルリクエスト時に自動実行される品質チェック：

### PHPStan静的解析

- **トリガー**: `src/**/*.php`、`src/phpstan.neon`、`src/composer.json`の変更

### PHPUnit テスト

- **トリガー**: `src/**/*.php`、`src/phpunit.xml`、`src/composer.json`の変更
