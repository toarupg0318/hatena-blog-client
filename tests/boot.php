<?php

// init env
use Dotenv\Dotenv;

$env = Dotenv::createUnsafeImmutable(
    __DIR__ . '/../',
    '.env'
);
$env->safeLoad();