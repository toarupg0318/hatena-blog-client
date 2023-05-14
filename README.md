<p align="center">
  <img src="https://github.com/toarupg0318/hatena-blog-client/raw/master/art/example.png" width="600">
  <p align="center">
    <img src="https://img.shields.io/badge/PHPStan-Level max-cornflowerblue.svg?style=flat&logo=php" width="135" height="20">
    <img src="http://img.shields.io/badge/license-MIT-blue.svg" width="107" height="20">
    <img src="https://github.com/toarupg0318/hatena-blog-client/actions/workflows/test.yml/badge.svg" width="107" height="20">
    <a href="https://codecov.io/gh/toarupg0318/hatena-blog-client" >
      <img src="https://codecov.io/gh/toarupg0318/hatena-blog-client/branch/master/graph/badge.svg?token=RD8RAAVL06"/>
    </a>
  </p>
</p>


## 使い方

> [PHP 8.1+](https://php.net/releases/) が必要です


### 準備

はじめに、はてなブログに接続するために認証情報を[詳細設定](https://blog.hatena.ne.jp/my/config/detail)から取得してください。

設定 > 詳細設定
<img src="https://github.com/toarupg0318/hatena-blog-client/raw/master/art/hatena_secrets.png" width="600">


<br />

以下コマンドでcomposerでインストールしてください。

```bash
composer require toarupg0318/hatena-blog-client
```

以下のようにクライアントインスタンスを取得します。
```PHP
use Toarupg0318\HatenaBlogClient\HatenaClient;
...
$hatenaClient = HatenaClient::getInstance(
    'あなたのはてなID',
    'あなたのブログID',
    'あなたのAPIキー'
);
```
  
### 記事投稿
- HTMLモード（見たままモード）
- はてな記法モード

での投稿ができます。 ※ マークダウン記法は未実装です。  
以下、各モードでのサンプルコードを記載します。

#### `HTMLモード（見たままモード）`
```PHP
    $content = <<<CONTENT
<main>
    <section>
        <h2>見出し1</h2>
        <p>これは<em>サンプルの文章</em>です。
        この記事では、簡単なHTMLサンプルを紹介しています。詳細については、<a href="#">こちら</a>をクリックしてください。</p>
    </section>
    <section>
        <h2>見出し2</h2>
        <p>この<em>サンプル記事</em>は、HTMLタグをいくつか使用しています。これにより、より見栄えの良いコンテンツを作成できます。</p>
        <ul>
            <li>リスト項目1</li>
            <li>リスト項目2</li>
            <li>リスト項目3</li>
        </ul>
    </section>
</main>
CONTENT;

$response = $hatenaClient
    ->post(
        content: $content,
        title: 'HTML投稿',
        draft: false,
        categories: ['foo', 'bar']  // はてなカテゴリ編集画面で存在しないカテゴリは新規追加されます
    );

// $response->getParsedData();  // レスポンス取得
// $response->getEntryId();     // ブログのエントリID（記事のユニークID）の取得
```
投稿編集画面では以下のようになります。
<img src="https://github.com/toarupg0318/hatena-blog-client/raw/master/art/html_post.png" width="600">


#### `はてな記法モード`



### 記事一覧取得

### 記事取得

### 記事編集

### 記事削除
取得したエントリIDを引数に指定して記事を削除することができます。
```PHP
$client->deletePostByEntryId('4207575167685628272');
```

<br />

## 開発者向け

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
その後、以下コマンドで [Pest](https://pestphp.com/) によるテストを実行できます。
```bash
./vendor/bin/pest
```

### 静的解析
以下コマンドで [PHPStan](https://phpstan.org/) による静的解析を実行できます。
```bash
./vendor/bin/phpstan analyse
```
