# Task Completion Checklist

タスク完了時に実行すべき手順です。

## PHP コード変更時

1. **Pint でフォーマット**
   ```bash
   ./vendor/bin/pint
   ```

2. **PHPStan で静的解析** (必要に応じて)
   ```bash
   ./vendor/bin/phpstan analyse
   ```

3. **PHPUnit でテスト実行**
   ```bash
   php -d memory_limit=1G ./vendor/bin/phpunit
   ```
   - 変更箇所に関連するテストのみ実行する場合:
   ```bash
   php -d memory_limit=1G ./vendor/bin/phpunit --filter=TestClassName
   php -d memory_limit=1G ./vendor/bin/phpunit path/to/TestFile.php
   ```

## TypeScript / React コード変更時

1. **ESLint でリント**
   ```bash
   npm run lint
   ```

2. **TypeScript 型チェック**
   ```bash
   npm run ts-check
   ```

3. **Vitest でテスト実行**
   ```bash
   npm run test
   ```

## Blade テンプレート変更時

1. **blade-formatter でフォーマット**
   ```bash
   npx -y blade-formatter --write
   ```

## 全体チェック (ホストマシンから)

```bash
make pre-commit      # format + test を一括実行
```

## 注意事項
- PHP関連のコマンドはDockerコンテナ内で実行する
- テストは `testing_database` (MySQL) を使用
- PHPUnit は `memory_limit=1G` オプション付きで実行する
- 新しいテストを追加した場合、`Tests\BaseTest` を継承するとファクトリヘルパーが使える
