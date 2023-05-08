{{編集予定}}

<p align="center">
  <img src="https://github.com/toarupg0318/hatena-blog-client/raw/master/art/example.png" width="600">
  <p align="center">
    <img src="https://github.com/toarupg0318/hatena-blog-client/actions/workflows/test.yml/badge.svg" width="107" height="20">
    <img src="https://img.shields.io/badge/PHPStan-Level max-cornflowerblue.svg?style=flat&logo=php" width="135" height="20">
    <img src="http://img.shields.io/badge/license-MIT-blue.svg" width="107" height="20">
  </p>
</p>

------

## 使い方

> [PHP 8.1+](https://php.net/releases/) が必要です

はじめに、はてなブログに接続するために認証情報を[詳細設定](https://blog.hatena.ne.jp/my/config/detail)から取得してください。

```bash
composer require toarupg0318/hatena-blog-client
```

## 開発者向け

------

### 開発への参加
開発の方針がまだ定まっていないですが、プルリクエストは適宜受け付けています。

### テストの実行
プロジェクトのルートディレクトリの .env.example をコピーして .env を作成します。
```bash
cp .env.example .env
```
新規作成した .env に自分のはてなブログAtomPub接続情報を追記します。
```.env
HATENA_ID=hoge6789
HATENA_BLOG_ID=hoge6789.hatenablog.com
HATENA_API_KEY=foo78bar90
```
その後、以下コマンドで [Pest(v2.5.0)](https://pestphp.com/) によるテストを実行できます。
```bash
./vendor/bin/pest
```

### 静的解析
以下コマンドで [PHPStan(v1.10.13)](https://phpstan.org/) による静的解析を実行できます。
```bash
./vendor/bin/phpstan analyse
```