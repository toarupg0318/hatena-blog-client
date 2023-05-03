<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Contracts;

use Psr\Http\Message\ResponseInterface;
use Toarupg0318\HatenaBlogClient\Contracts\HatenaResponses\HatenaGetListResponseInterface;
use Toarupg0318\HatenaBlogClient\Contracts\HatenaResponses\HatenaPostResponseInterface;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaDOMDocument;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaDOMElement;

interface HatenaClientInterface
{
    public const CONTENT_TYPE_TEXT_PLAIN = 'text/plain';
    public const CONTENT_TYPE_TEXT_HTML = 'text/html';
    public const CONTENT_TYPE_HATENA_SYNTAX = 'text/x-hatena-syntax';

    /**
     * @see https://developer.hatena.ne.jp/ja/documents/blog/apis/atom/#%E3%83%96%E3%83%AD%E3%82%B0%E3%82%A8%E3%83%B3%E3%83%88%E3%83%AA%E3%81%AE%E4%B8%80%E8%A6%A7%E5%8F%96%E5%BE%97
     *
     * @param string|null $page
     * @return ResponseInterface&HatenaGetListResponseInterface
     */
    public function getList(string|null $page = null): ResponseInterface&HatenaGetListResponseInterface;

    // todo: ブログエントリの取得

    // todo: ブログエントリの編集
    // todo: ブログエントリの削除

    /**
     * Post blog entry.
     *
     * @see https://developer.hatena.ne.jp/ja/documents/blog/apis/atom/#%E3%83%96%E3%83%AD%E3%82%B0%E3%82%A8%E3%83%B3%E3%83%88%E3%83%AA%E3%81%AE%E6%8A%95%E7%A8%BF
     *
     * @param string|HatenaDOMDocument<int, HatenaDOMElement> $content
     * @param string|null $title    if empty, constant "■" is embedded.
     * @param self::CONTENT_TYPE_* $contentType
     * @param bool $draft
     * @param string|null $updated
     * @param string|null $customUrl
     * @param string[] $categories
     * @return ResponseInterface&HatenaPostResponseInterface
     */
    public function post(
        string|HatenaDOMDocument $content,
        string|null $title = null,
        string $contentType = 'text/plain',
        bool $draft = true,
        string|null $updated = null,
        string|null $customUrl = null,
        array $categories = []
    ): ResponseInterface&HatenaPostResponseInterface;
}