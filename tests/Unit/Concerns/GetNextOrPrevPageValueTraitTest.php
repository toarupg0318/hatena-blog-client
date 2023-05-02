<?php

use Toarupg0318\HatenaBlogClient\Concerns\GetNextOrPrevPageValueTrait;
use Toarupg0318\HatenaBlogClient\Exceptions\HatenaInvalidPageUrlException;

$classUsingGetNextOrPrevPageValueTrait = new class() {
    use GetNextOrPrevPageValueTrait;

    /**
     * @param string $rawUrlValue
     * @return string
     */
    public function output(string $rawUrlValue): string
    {
        return $this->getNextOrPrevPageValue($rawUrlValue);
    }
};

it('tests GetNextOrPrevPageValueTraitTest performs correctly', function () use ($classUsingGetNextOrPrevPageValueTrait) {

    expect(
        $classUsingGetNextOrPrevPageValueTrait
            ->output('https://blog.hatena.ne.jp/hoge0318/fuga0318.hatenablog.com/atom/entry?page=1780929531')
    )
        ->toBe('1780929531')
        ->and(
            $classUsingGetNextOrPrevPageValueTrait
                ->output('?page=1780929531')
        )
        ->toBe('1780929531')
        ->and(
            $classUsingGetNextOrPrevPageValueTrait
                ->output('page=1780929531')
        )
        ->toBe('1780929531');
});

it(
    'test if passed url value is invalid, throw expected exception',
    function () use ($classUsingGetNextOrPrevPageValueTrait) {
        expect(fn () => $classUsingGetNextOrPrevPageValueTrait->output('hoge0318'))
            ->toThrow(HatenaInvalidPageUrlException::class);
    }
);