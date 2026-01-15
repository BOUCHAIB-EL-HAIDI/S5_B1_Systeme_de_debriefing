<?php

$envPath = dirname(__DIR__) . '/.env';

if (!file_exists($envPath)) {
    die('.env file not found');
}

$lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $line) {
    if (str_starts_with(trim($line), '#')) {
        continue;
    }

    [$key, $value] = array_map('trim', explode('=', $line, 2));

    $_ENV[$key] = $value;
}