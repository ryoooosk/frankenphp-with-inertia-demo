# Inertia Demo with FrankenPHP

Laravel + React + Inertia.js + FrankenPHP のサンプルアプリケーション

## 環境構築

### 必要なツール

- Docker Desktop

### セットアップ手順

1. **リポジトリのクローン**

   ```bash
   git clone <repository-url> <your-directory-name>
   cd <your-directory-name>
   ```

2. **環境設定ファイルの作成**

   このプロジェクトでは2つの`.env`ファイルが必要です：

   **a. プロジェクトルートの`.env`（PostgreSQL設定用）**

   ```bash
   # プロジェクトルートに作成
   cp .env.example .env
   ```

   **b. アプリケーション用の`.env`（Laravel設定用）**

   ```bash
   # src/ディレクトリに作成
   cp src/.env.example src/.env
   ```

3. **事前準備（重要）**

   FrankenPHPのワーカーモードを使用するため、初回のコンテナ起動前は必ずComposer依存関係をインストールしてください：

   ```bash
   # src/vendorディレクトリが存在しない場合のみ実行
   docker run --rm -v $(pwd)/src:/app composer:latest instal
   ```

   > **なぜ事前に必要か？**
   >
   > FrankenPHPのワーカーモードは、アプリケーション全体（vendor内のライブラリを含む）を起動時に一度だけメモリにロードし、その状態を保持したまま複数のリクエストを処理します。起動時にvendorディレクトリが存在しないと、アプリケーションをメモリ上に構築すること自体ができず、起動に失敗します。

4. **Docker 環境の起動**

   ```bash
   docker compose up -d --build
   ```

5. **アプリケーションの初期化**

   コンテナが起動後に実行

   ```bash
   # Laravel設定
   docker compose exec app php artisan key:generate
   docker compose exec app php artisan migrate
   ```

   ```bash
   # Node.js依存関係のインストール
   docker compose exec node npm install

   # フロントエンド開発サーバー起動
   docker compose exec node npm run dev
   ```

6. **アクセス確認**

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
  - [mago](https://github.com/carthage-software/mago) - コードスタイル自動修正
  - [Biome](https://biomejs.dev/) - JavaScript/TypeScript リンター・フォーマッター

### 開発用コマンド

```bash
# テスト実行
docker compose exec app php artisan test

# PHPコード品質チェック
docker compose exec app composer phpstan # 静的解析
docker compose exec app vendor/bin/rector --dry-run  # リファクタリング候補確認
docker compose exec app vendor/bin/mago fmt  # コードスタイル自動修正

# テスト
docker compose exec app composer test
```

### エディタ設定

.vscode/settings.json に以下を設定しています

- js,tsやjson,tsxはBiomeでフォーマット
- phpはmagoでフォーマット
  - magoはコンテナ内のものを使うため、コンテナを立ち上げないと動作しません

## CI (GitHub Actions)

プルリクエスト時に自動実行される品質チェック：

### PHPStan静的解析

- **トリガー**: `src/**/*.php`、`src/phpstan.neon`、`src/composer.json`の変更

### PHPUnit テスト

- **トリガー**: `src/**/*.php`、`src/phpunit.xml`、`src/composer.json`の変更

## 依存関係管理 (Dependabot)

このプロジェクトでは [Dependabot](https://docs.github.com/en/code-security/dependabot) を使用して依存関係の自動更新を行っています。

### 監視対象

- **PHP/Composer** (`/src/composer.json`): Laravel、Inertia.js、その他PHPライブラリ
- **JavaScript/npm** (`/src/package.json`): React、Vite、TypeScript、その他フロントエンドライブラリ
- **Docker** (`/Dockerfile`): Dockerベースイメージ
- **GitHub Actions** (`/.github/workflows/`): ワークフロー内のアクション

### 自動更新の詳細

- **更新間隔**: 週次（毎週月曜日）
- **同時PR数**: 最大10件
- **コミットメッセージ**: `deps:` プレフィックス付き
- **ラベル**: 自動的に `dependabot` と対応する技術ラベルが付与

### 設定ファイル

`.github/dependabot.yml` で設定を管理。詳細な設定変更が必要な場合は、このファイルを編集してください。

## FrankenPHP メモリリーク対策

このプロジェクトではFrankenPHPのワーカーモードで発生しうるメモリリークを防ぐため、以下の対策を実装しています。

### 環境変数設定 (Dockerfile)

```dockerfile
# メモリリーク対策
ENV FRANKENPHP_WORKER_COUNT=2      # ワーカープロセス数制限
ENV FRANKENPHP_MAX_REQUESTS=500    # リクエスト数制限（500回処理後にワーカー再起動）
ENV MAX_RUNTIME_SECONDS=3600       # 最大実行時間制限（1時間）
ENV GOMEMLIMIT=480MiB              # Go ランタイムメモリ制限
ENV PHP_INI_MEMORY_LIMIT=192M      # PHP メモリ制限
```

### メモリ監視機能

- **LogMemoryUsage ミドルウェア** (`src/app/Http/Middleware/LogMemoryUsage.php`)
  - 各リクエスト処理後のメモリ使用量をログに記録
  - ログ形式: `[GET] / - Memory: 6.00 MB`

### 設定の効果

- **メモリ使用量**: 安定した 6.00MB で推移
- **メモリリーク**: 大量リクエスト処理後も増加なし
- **自動復旧**: 500リクエスト毎にワーカー自動再起動

### メモリ使用量の確認方法

```bash
# 最新のメモリ使用ログを確認
docker compose exec app tail -n 20 storage/logs/laravel.log | grep "Memory:"

# メモリ記録数を確認
docker compose exec app grep -c "Memory:" storage/logs/laravel.log
```
