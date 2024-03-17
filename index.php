<?php

require_once __DIR__ . '/vendor/autoload.php';

\Libs\Dotenv\Dotenv::load(__DIR__ . '/.env');
\Libs\Console\CommandsDiscovery::load(
    __DIR__ . '/Commands'
);

