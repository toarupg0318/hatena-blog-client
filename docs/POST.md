## はてな記法による投稿
以下に使用可能な各記法の一覧を挙げます。

<br />

詳細は[公式のはてな記法一覧](https://help.hatenablog.com/entry/text-hatena-list)を参照ください。  
※ 一部の記法はサポートしていません。

### 大見出し記法
```PHP
$hatenaDOMDocument = HatenaDOMDocument::create()
    ->appendH3('見出しh3');
$hatenaClient->post(
    $hatenaDOMDocument
);
```

<img src="https://github.com/toarupg0318/hatena-blog-client/raw/master/art/hatena_syntaxes/h3.png" width="600">

### 中見出し記法
```PHP
$hatenaDOMDocument = HatenaDOMDocument::create()
    ->appendH4('見出しh4');
$hatenaClient->post(
    $hatenaDOMDocument
);
```

<img src="https://github.com/toarupg0318/hatena-blog-client/raw/master/art/hatena_syntaxes/h4.png" width="600">

### 小見出し記法
```PHP
$hatenaDOMDocument = HatenaDOMDocument::create()
    ->appendH5('見出しh5');
$hatenaClient->post(
    $hatenaDOMDocument
);
```

<img src="https://github.com/toarupg0318/hatena-blog-client/raw/master/art/hatena_syntaxes/h5.png" width="600">

### 脚注記法
特定の記法において、追加の引数を指定することによって脚注を追加することができます。

```PHP
$hatenaDOMDocument = HatenaDOMDocument::create()
    ->appendH3('概要')
    ->appendText(
        text: 'このパッケージははてなブログのPHPクライアントパッケージです。',
        footNotes: [
            'はてなブログ' => 'はてなブログは、あなたの思いや考えを残したり、さまざまな人が綴った多様な価値観に触れたりできる場所です。'
        ]
    );
$hatenaClient->post(
    $hatenaDOMDocument
);
```

<img src="https://github.com/toarupg0318/hatena-blog-client/raw/master/art/hatena_syntaxes/foot_note.png" width="600">

脚注を追加できるのは、以下のメソッドです。

| クラス               | メソッド       |
|-------------------|------------|
| HatenaDOMDocument | appendH3   |
| HatenaDOMDocument | appendH4   |
| HatenaDOMDocument | appendH5   |
| HatenaDOMDocument | appendText |

### 引用記法
```PHP
$hatenaDOMDocument = HatenaDOMDocument::create()
    ->appendBlockQuote('block quote test', 'https://github.com/toarupg0318/hatena-blog-client');
$hatenaClient->post(
    $hatenaDOMDocument
);
```

<img src="https://github.com/toarupg0318/hatena-blog-client/raw/master/art/hatena_syntaxes/block_quote.png" width="600">

### 改行記法
```PHP
$hatenaDOMDocument = HatenaDOMDocument::create()
    ->appendText('テキストtest1')
    ->appendBr()
    ->appendText('テキストtest1');
$hatenaClient->post(
    $hatenaDOMDocument
);
```

<img src="https://github.com/toarupg0318/hatena-blog-client/raw/master/art/hatena_syntaxes/br.png" width="600">

### 定義リスト記法
```PHP
$hatenaDOMDocument = HatenaDOMDocument::create()
    ->appendDt('タイトルtest', '説明test');
$hatenaClient->post(
    $hatenaDOMDocument
);
```

<img src="https://github.com/toarupg0318/hatena-blog-client/raw/master/art/hatena_syntaxes/dt.png" width="600">

### http記法
```PHP
$hatenaDOMDocument = HatenaDOMDocument::create()
        ->appendHttp('https://github.com/toarupg0318/hatena-blog-client')
        ->appendHttp('https://github.com/toarupg0318/hatena-blog-client', 'title')
        ->appendHttp('https://github.com/toarupg0318/hatena-blog-client', 'barcode');
$hatenaClient->post(
    $hatenaDOMDocument
);
```

<img src="https://github.com/toarupg0318/hatena-blog-client/raw/master/art/hatena_syntaxes/http.png" width="600">

### リスト記法
```PHP
$hatenaDOMDocument = HatenaDOMDocument::create()
    ->appendLi([
            'header' => 'ヘッダーtest1',
            'lines' => ['行1test', '行2test', '行3test']
        ], '+')
    ->appendLi([
            'header' => 'ヘッダーtest2',
            'lines' => ['行1test', '行2test', '行3test']
        ], '-');
$hatenaClient->post(
    $hatenaDOMDocument
);
```

<img src="https://github.com/toarupg0318/hatena-blog-client/raw/master/art/hatena_syntaxes/li.png" width="600">

### pre記法
```PHP
$hatenaDOMDocument = HatenaDOMDocument::create()
    ->appendPre('pre');
$hatenaClient->post(
    $hatenaDOMDocument
);
```

<img src="https://github.com/toarupg0318/hatena-blog-client/raw/master/art/hatena_syntaxes/pre.png" width="600">

[//]: # (動作確認できないためコメント化)

[//]: # (### 続きを読む記法)

[//]: # (```PHP)

[//]: # ($hatenaDOMDocument = HatenaDOMDocument::create&#40;&#41;)

[//]: # (    ->appendReadMore&#40;&#41;;)

[//]: # (```)

[//]: # (動作確認できないためコメント化)

[//]: # (### pタグ停止記法)

[//]: # (```PHP)

[//]: # ($hatenaDOMDocument = HatenaDOMDocument::create&#40;&#41;)

[//]: # (        ->appendStopP&#40;'pタグ停止記法test'&#41;;)

[//]: # ($hatenaClient->post&#40;)

[//]: # (    $hatenaDOMDocument)

[//]: # (&#41;;)

[//]: # (```)

### スーパーpre記法
```PHP
$hatenaDOMDocument = HatenaDOMDocument::create()
    ->appendSuperPre('super pre');
$hatenaClient->post(
    $hatenaDOMDocument
);
```

<img src="https://github.com/toarupg0318/hatena-blog-client/raw/master/art/hatena_syntaxes/super_pre.png" width="600">

### スーパーpre記法（シンタックス・ハイライト）
```PHP
$hatenaDOMDocument = HatenaDOMDocument::create()
    ->appendSyntaxHighLight('typescript', <<<TYPESCRIPT
        const message: string = "Hello, TypeScript!";
        console.log(message);
TYPESCRIPT
    );
$hatenaClient->post(
    $hatenaDOMDocument
);
```

<img src="https://github.com/toarupg0318/hatena-blog-client/raw/master/art/hatena_syntaxes/syntax_highlight.png" width="600">

第一引数に指定可能な言語は[こちら](https://help.hatenablog.com/entry/markup/syntaxhighlight)を参照ください。

### 表組み記法
```PHP
$hatenaDOMDocument = HatenaDOMDocument::create()
    ->appendTable([
        'headers' => ['(1,1) test', '(2,1)test'],
        'lines' => [
            ['(1,2) test', '(2,2)test'],
            ['(1,3) test', '(2,3)test'],
        ]
    ]);
$hatenaClient->post(
    $hatenaDOMDocument
);
```

<img src="https://github.com/toarupg0318/hatena-blog-client/raw/master/art/hatena_syntaxes/table.png" width="600">

### 目次記法
```PHP
$hatenaDOMDocument = HatenaDOMDocument::create()
    ->appendH3('見出しh3')
    ->appendH4('見出しh4')
    ->appendH5('見出しh5')
    ->appendTableOfContents();
$hatenaClient->post(
    $hatenaDOMDocument
);
```

<img src="https://github.com/toarupg0318/hatena-blog-client/raw/master/art/hatena_syntaxes/table_of_contents.png" width="600">

### 通常テキスト ※ はてな記法ではありません
```PHP
$hatenaDOMDocument = HatenaDOMDocument::create()
    ->appendText('テキストtest');
$hatenaClient->post(
    $hatenaDOMDocument
);
```

<img src="https://github.com/toarupg0318/hatena-blog-client/raw/master/art/hatena_syntaxes/text.png" width="600">

### tex記法
```PHP
$hatenaDOMDocument = HatenaDOMDocument::create()
    ->appendTex('\[\sin x = \sum_{n=0}^{\infty} \frac{(-1)^n}{(2n+1)!} x^{2n+1}\]');
$hatenaClient->post(
    $hatenaDOMDocument
);
```

<img src="https://github.com/toarupg0318/hatena-blog-client/raw/master/art/hatena_syntaxes/tex.png" width="600">

### Twitter記法
```PHP
$hatenaDOMDocument = HatenaDOMDocument::create()
    ->appendTwitter('1657370105372889088');
$hatenaClient->post(
    $hatenaDOMDocument
);
```

<img src="https://github.com/toarupg0318/hatena-blog-client/raw/master/art/hatena_syntaxes/twitter.png" width="600">