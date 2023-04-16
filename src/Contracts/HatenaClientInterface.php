<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Contracts;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaDOMDocument;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaDOMElement;

interface HatenaClientInterface
{
    public const CONTENT_TYPE_TEXT_PLAIN = 'text/plain';
    public const CONTENT_TYPE_TEXT_HTML = 'text/html';
    public const CONTENT_TYPE_HATENA_SYNTAX = 'text/x-hatena-syntax';

    /**
     * @param string|null $page
     * @return ResponseInterface
     */
    public function getList(string|null $page = null): ResponseInterface;

    // todo: ブログエントリの取得

    // todo: ブログエントリの編集
    // todo: ブログエントリの削除

    /**
     * todo: optional params
     * Post blog entry
     *
     * @see https://developer.hatena.ne.jp/ja/documents/blog/apis/atom/#%E3%83%96%E3%83%AD%E3%82%B0%E3%82%A8%E3%83%B3%E3%83%88%E3%83%AA%E3%81%AE%E6%8A%95%E7%A8%BF
     * @param string|HatenaDOMDocument<int, HatenaDOMElement> $content
     * @param string $title
     * @param self::CONTENT_TYPE_* $contentType
     * @param bool $draft
     * @param string|null $updated
     * @param string|null $customUrl
     * @return ResponseInterface
     */
    public function post(
        string|HatenaDOMDocument $content,
        string $title,
        string $contentType,
        bool $draft,
        string|null $updated,
        string|null $customUrl
    ): ResponseInterface;
}