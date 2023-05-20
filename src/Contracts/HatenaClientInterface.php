<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Contracts;

use Psr\Http\Message\ResponseInterface;
use Toarupg0318\HatenaBlogClient\Contracts\HatenaResponses\HatenaDeletePostByEntryIdResponseInterface;
use Toarupg0318\HatenaBlogClient\Contracts\HatenaResponses\HatenaEditPostByEntryIdResponseInterface;
use Toarupg0318\HatenaBlogClient\Contracts\HatenaResponses\HatenaGetListResponseInterface;
use Toarupg0318\HatenaBlogClient\Contracts\HatenaResponses\HatenaGetPostByEntryIdResponseInterface;
use Toarupg0318\HatenaBlogClient\Contracts\HatenaResponses\HatenaPostResponseInterface;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaDOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\HatenaDOMDocument;

interface HatenaClientInterface
{
    /**
     * Fetch blog entries.
     *
     * @see https://developer.hatena.ne.jp/ja/documents/blog/apis/atom/#%E3%83%96%E3%83%AD%E3%82%B0%E3%82%A8%E3%83%B3%E3%83%88%E3%83%AA%E3%81%AE%E4%B8%80%E8%A6%A7%E5%8F%96%E5%BE%97
     *
     * @return ResponseInterface&HatenaGetListResponseInterface
     */
    public function getList(string|null $page = null): ResponseInterface&HatenaGetListResponseInterface;

    /**
     * Fetch a blog entry posted by entry id.
     *
     * @see https://developer.hatena.ne.jp/ja/documents/blog/apis/atom/#%E3%83%96%E3%83%AD%E3%82%B0%E3%82%A8%E3%83%B3%E3%83%88%E3%83%AA%E3%81%AE%E5%8F%96%E5%BE%97
     *
     * @return ResponseInterface&HatenaGetPostByEntryIdResponseInterface
     */
    public function getPostByEntryId(
        string $entryId
    ): ResponseInterface&HatenaGetPostByEntryIdResponseInterface;

    /**
     * Delete existing post by entry id.
     *
     * @see https://developer.hatena.ne.jp/ja/documents/blog/apis/atom/#%E3%83%96%E3%83%AD%E3%82%B0%E3%82%A8%E3%83%B3%E3%83%88%E3%83%AA%E3%81%AE%E5%89%8A%E9%99%A4
     *
     * @return ResponseInterface&HatenaDeletePostByEntryIdResponseInterface
     */
    public function deletePostByEntryId(
        string $entryId
    ): ResponseInterface&HatenaDeletePostByEntryIdResponseInterface;

    /**
     * Post blog entry.
     *
     * @see https://developer.hatena.ne.jp/ja/documents/blog/apis/atom/#%E3%83%96%E3%83%AD%E3%82%B0%E3%82%A8%E3%83%B3%E3%83%88%E3%83%AA%E3%81%AE%E6%8A%95%E7%A8%BF
     *
     * @param string|HatenaDOMDocument<int, HatenaDOMElement> $content
     * @param string|null $title    if empty, constant "■" is embedded.
     * @param string[] $categories
     * @return ResponseInterface&HatenaPostResponseInterface
     */
    public function post(
        string|HatenaDOMDocument $content,
        string|null $title = null,
        bool $draft = true,
        string|null $updated = null,
        string|null $customUrl = null,
        array $categories = []
    ): ResponseInterface&HatenaPostResponseInterface;

    /**
     * Edit existing post entry.
     *
     * @see https://developer.hatena.ne.jp/ja/documents/blog/apis/atom#%E3%83%96%E3%83%AD%E3%82%B0%E3%82%A8%E3%83%B3%E3%83%88%E3%83%AA%E3%81%AE%E7%B7%A8%E9%9B%86
     *
     * @param string|HatenaDOMDocument<int, HatenaDOMElement> $content
     * @param string|null $title    if empty, constant "■" is embedded.
     * @param string[] $categories
     * @return HatenaEditPostByEntryIdResponseInterface&ResponseInterface
     */
    public function edit(
        string $entryId,
        string|HatenaDOMDocument $content,
        string|null $title = null,
        bool $draft = true,
        string|null $updated = null,
        string|null $customUrl = null,
        array $categories = []
    ): ResponseInterface&HatenaEditPostByEntryIdResponseInterface;

//    /**
//     * Fetch existing categories.
//     *
//     * @note unused
//     * @return ResponseInterface&HatenaGetCategoriesResponseInterface
//     */
//    public function getCategories(): ResponseInterface&HatenaGetCategoriesResponseInterface;
}