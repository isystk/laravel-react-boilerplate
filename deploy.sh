##############################
# 
# 本番反映用のシェルスクリプト
# 
###############################

### メンテナンスモード開始
pushd htdocs
php artisan down
popd

### コード反映
git pull

pushd htdocs

### 依存関係解決
pushd htdocs
# 本番用に、.env ファイルをコピー
cp -rp .env.production .env
#composer dump-autoload
composer update

### テーブル追加
# 初期化する場合
php artisan migrate:fresh --seed
# 追加する場合
#php artisan migrate
# テストデータの追加
#php artisan db:seed --class=Database\\Seeders\\Dev\\UsersTableSeeder
#php artisan db:seed --class=Database\\Seeders\\Dev\\AdminsTableSeeder
#php artisan db:seed --class=Database\\Seeders\\Dev\\StockTableSeeder
#php artisan db:seed --class=Database\\Seeders\\Dev\\ContactFormSeeder
#php artisan db:seed --class=Database\\Seeders\\Dev\\ContactFormImageSeeder
popd

### レンタルサーバーにアクセス許可を設定
# 1つ上のルートディレクトリに移動
pushd ..
# public フォルダにシンボリックリンクを作成
ln -snf ./laravel-react-boilerplate/htdocs/public public
# アクセス許可を設定
cp -rp ./laravel-react-boilerplate/htdocs/public/.htaccess.production .htaccess
rm -Rf ./laravel-react-boilerplate/htdocs/public/.htaccess
popd

### メンテナンスモード開始
pushd htdocs
php artisan up
popd
