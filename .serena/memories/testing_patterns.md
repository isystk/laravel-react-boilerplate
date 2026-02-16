# Testing Patterns

## PHP テスト

### テスト基盤
- 基底クラス: `Tests\BaseTest` (extends `Illuminate\Foundation\Testing\TestCase`)
- テストDB: MySQL `testing_database` (.env.testing)
- PHPUnit 11, Mockery

### BaseTest のヘルパーメソッド
- `createDefaultUser(array $params = [])`: User ファクトリ
- `createDefaultAdmin(array $params = [])`: Admin ファクトリ
- `createDefaultStock(array $params = [])`: Stock ファクトリ
- `createDefaultCart(array $params = [])`: Cart ファクトリ (User, Stock も自動作成)
- `createDefaultOrder(array $params = [])`: Order ファクトリ (User も自動作成)
- `createDefaultOrderStock(array $params = [])`: OrderStock ファクトリ
- `createDefaultContact(array $params = [])`: Contact ファクトリ
- `createDefaultImage(array $params = [])`: Image ファクトリ
- `createDefaultImportHistory(array $params = [])`: ImportHistory ファクトリ
- `createDefaultMonthlySale(array $params = [])`: MonthlySale ファクトリ
- `setPrivateMethodTest(object $target, string $methodName)`: privateメソッドへのアクセス
- `getPrivateProperty(object $target, string $propertyName)`: privateプロパティへのアクセス
- `readCsv(string $path)`: CSV読み込み

### テスト構成
- **Unit テスト**: モック中心、DB不使用のテスト
  - Domain/Entities, Domain/Repositories, Dto, Enums, Rules, Helpers, Services, etc.
- **Feature テスト**: HTTPリクエストを通したテスト (DB使用)
  - Console/Commands, Http/Controllers (Front, Admin, Api), Middleware

### テストメソッド命名
- 日本語テストメソッド名可 (`php_unit_method_casing: false`)

## JavaScript テスト

### テスト基盤
- Vitest + jsdom environment
- @testing-library/react
- セットアップ: `.storybook/vitest.setup.ts`
- `globals: true` (describe, it, expect がグローバル)
