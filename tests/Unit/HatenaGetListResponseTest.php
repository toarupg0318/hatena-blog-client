<?php

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\ResponseInterface;
use Toarupg0318\HatenaBlogClient\HatenaGetListResponse;

// dummy response has 3 entries
$dummyResponseBody = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom"
      xmlns:app="http://www.w3.org/2007/app">

  <link rel="first" href="https://blog.hatena.ne.jp/toarupg0318/toarupg0318.hatenablog.com/atom/entry" />

  
  <link rel="next" href="https://blog.hatena.ne.jp/toarupg0318/toarupg0318.hatenablog.com/atom/entry?page=1683098689" />
  

  <title>hogefugablog</title>
  
  <link rel="alternate" href="https://toarupg0318.hatenablog.com/"/>
  <updated>2023-04-08T20:57:23+09:00</updated>
  <author>
    <name>toarupg0318</name>
  </author>
  <generator uri="https://blog.hatena.ne.jp/" version="bd6d08679f4e9d905ac0aea537e1e8">Hatena::Blog</generator>
  <id>hatenablog://blog/4207112889979077890</id>

  
  <entry>
<id>tag:blog.hatena.ne.jp,2013:blog-toarupg0318-4207112889979077890-4207575160645635809</id>
<link rel="edit" href="https://blog.hatena.ne.jp/toarupg0318/toarupg0318.hatenablog.com/atom/entry/4207575160645635809"/>
<link rel="alternate" type="text/html" href="https://toarupg0318.hatenablog.com/entry/__7"/>
<author><name>toarupg0318</name></author>
<title>■</title>
<updated>2023-05-03T17:04:52+09:00</updated>
<published>2023-05-03T17:04:52+09:00</published>
<app:edited>2023-05-03T17:04:52+09:00</app:edited>
<summary type="text">大見出し記法a 中見出し記法a</summary>
<content type="text/x-hatena-syntax">
*大見出し記法a
**中見出し記法a
  </content>
<hatena:formatted-content type="text/html" xmlns:hatena="http://www.hatena.ne.jp/info/xmlns#">
&lt;div class=&quot;section&quot;&gt;
    &lt;h3 id=&quot;大見出し記法a&quot;&gt;大見出し記法a&lt;/h3&gt;
    
&lt;div class=&quot;section&quot;&gt;
    &lt;h4 id=&quot;中見出し記法a&quot;&gt;中見出し記法a&lt;/h4&gt;
    &lt;p&gt;  &lt;/p&gt;

&lt;/div&gt;
&lt;/div&gt;</hatena:formatted-content>

<app:control>
  <app:draft>yes</app:draft>
</app:control>

  </entry>
  
  <entry>
<id>tag:blog.hatena.ne.jp,2013:blog-toarupg0318-4207112889979077890-4207575160645633327</id>
<link rel="edit" href="https://blog.hatena.ne.jp/toarupg0318/toarupg0318.hatenablog.com/atom/entry/4207575160645633327"/>
<link rel="alternate" type="text/html" href="https://toarupg0318.hatenablog.com/entry/__6"/>
<author><name>toarupg0318</name></author>
<title>■</title>
<updated>2023-05-03T16:54:06+09:00</updated>
<published>2023-05-03T16:54:06+09:00</published>
<app:edited>2023-05-03T16:54:06+09:00</app:edited>
<summary type="text">大見出し記法 中見出し記法 a</summary>
<content type="text/x-hatena-syntax">
*大見出し記法
**中見出し記法
a
  </content>
<hatena:formatted-content type="text/html" xmlns:hatena="http://www.hatena.ne.jp/info/xmlns#">
&lt;div class=&quot;section&quot;&gt;
    &lt;h3 id=&quot;大見出し記法&quot;&gt;大見出し記法&lt;/h3&gt;
    
&lt;div class=&quot;section&quot;&gt;
    &lt;h4 id=&quot;中見出し記法&quot;&gt;中見出し記法&lt;/h4&gt;
    &lt;p&gt;a&lt;br /&gt;
  &lt;/p&gt;

&lt;/div&gt;
&lt;/div&gt;</hatena:formatted-content>

<category term="foo" />

<app:control>
  <app:draft>yes</app:draft>
</app:control>

  </entry>
  
  <entry>
<id>tag:blog.hatena.ne.jp,2013:blog-toarupg0318-4207112889979077890-4207575160645633327</id>
<link rel="edit" href="https://blog.hatena.ne.jp/toarupg0318/toarupg0318.hatenablog.com/atom/entry/4207575160645633327"/>
<link rel="alternate" type="text/html" href="https://toarupg0318.hatenablog.com/entry/__6"/>
<author><name>toarupg0318</name></author>
<title>■</title>
<updated>2023-05-03T16:54:06+09:00</updated>
<published>2023-05-03T16:54:06+09:00</published>
<app:edited>2023-05-03T16:54:06+09:00</app:edited>
<summary type="text">大見出し記法 中見出し記法 a</summary>
<content type="text/x-hatena-syntax">
*大見出し記法
**中見出し記法
a
  </content>
<hatena:formatted-content type="text/html" xmlns:hatena="http://www.hatena.ne.jp/info/xmlns#">
&lt;div class=&quot;section&quot;&gt;
    &lt;h3 id=&quot;大見出し記法&quot;&gt;大見出し記法&lt;/h3&gt;
    
&lt;div class=&quot;section&quot;&gt;
    &lt;h4 id=&quot;中見出し記法&quot;&gt;中見出し記法&lt;/h4&gt;
    &lt;p&gt;a&lt;br /&gt;
  &lt;/p&gt;

&lt;/div&gt;
&lt;/div&gt;</hatena:formatted-content>

<category term="foo" />

<category term="bar" />

<app:control>
  <app:draft>yes</app:draft>
</app:control>

  </entry>
  
</feed>
XML;

$guzzleResponseMock = Mockery::mock(Response::class);
$guzzleStreamMock = Mockery::mock(Stream::class);
$guzzleResponseMock
    ->shouldReceive('getBody')
    ->andReturns($guzzleStreamMock);
$guzzleStreamMock
    ->shouldReceive('getContents')
    ->andReturns($dummyResponseBody);

if (! $guzzleResponseMock instanceof ResponseInterface) {
    throw new LogicException();
}
$getListResponseMock = new HatenaGetListResponse($guzzleResponseMock);

it(
    'tests extractEntriesFromGetListResponse() performs correctly.',
    function () use ($getListResponseMock, $dummyResponseBody) {
        $getListResponseReflection = new ReflectionClass($getListResponseMock);
        $extractEntriesFromGetListResponseMethod = $getListResponseReflection
            ->getMethod('extractEntriesFromGetListResponse');
        $extractEntriesFromGetListResponseMethod->setAccessible(true);
        $extractedEntries = $extractEntriesFromGetListResponseMethod
            ->invoke(
                    $getListResponseMock,
                    json_decode(
                        json_encode(
                            simplexml_load_string(
                                $dummyResponseBody
                            )
                        ),
            true
                    )
            );

        expect($extractedEntries)
            ->toMatchArray([
                0 => [
                    'editLinkUrl' => 'https://blog.hatena.ne.jp/toarupg0318/toarupg0318.hatenablog.com/atom/entry/4207575160645635809',
                    'entryId' => '4207575160645635809',
                    'authorName' => 'toarupg0318',
                    'title' => '■',
                    'updated' => '2023-05-03T17:04:52+09:00',
                    'published' => '2023-05-03T17:04:52+09:00',
                    'summary' => '大見出し記法a 中見出し記法a',
                    'categories' => [],
                ],
                1 => [
                    'editLinkUrl' => 'https://blog.hatena.ne.jp/toarupg0318/toarupg0318.hatenablog.com/atom/entry/4207575160645633327',
                    'entryId' => '4207575160645633327',
                    'authorName' => 'toarupg0318',
                    'title' => '■',
                    'updated' => '2023-05-03T16:54:06+09:00',
                    'published' => '2023-05-03T16:54:06+09:00',
                    'summary' => '大見出し記法 中見出し記法 a',
                    'categories' => ['foo'],
                ],
                2 => [
                    'editLinkUrl' => 'https://blog.hatena.ne.jp/toarupg0318/toarupg0318.hatenablog.com/atom/entry/4207575160645633327',
                    'entryId' => '4207575160645633327',
                    'authorName' => 'toarupg0318',
                    'title' => '■',
                    'updated' => '2023-05-03T16:54:06+09:00',
                    'published' => '2023-05-03T16:54:06+09:00',
                    'summary' => '大見出し記法 中見出し記法 a',
                    'categories' => ['foo', 'bar'],
                ]
            ]);
    }
);