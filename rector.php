<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets(sets: [
        LevelSetList::UP_TO_PHP_81,
        SetList::TYPE_DECLARATION,
        SetList::CODE_QUALITY,
    ]);
};