# Inertia Demo プロジェクト設定

## 基本情報

**必ずREADME.mdを参照してください** - プロジェクトの詳細な構成、セットアップ手順、技術スタックはREADME.mdに記載されています。

## 開発時の重要なコマンド

### テスト・品質チェック

```bash
# テスト実行
docker compose exec app composer test

# PHPStan静的解析
docker compose exec app composer phpstan

# コードスタイル確認
docker compose exec app vendor/bin/php-cs-fixer fix
docker compose exec node npx biome check
```

## プロジェクト構造

- `src/` - Laravelアプリケーション本体
- `src/resources/js/` - React/TypeScriptコード
- `src/resources/js/Pages/` - Inertia.jsページコンポーネント

## 開発ガイドライン

1. **README.md参照必須** - セットアップや技術詳細はREADME.mdを確認
2. **TypeScript型安全性** - `any`型は使用禁止
3. **コード品質** - 変更前にリンターとテストを実行
4. **Docker環境** - 開発はDockerコンテナ内で実行
