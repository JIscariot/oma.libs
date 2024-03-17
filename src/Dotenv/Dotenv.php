<?php

declare(strict_types=1);

namespace Libs\Dotenv;

use Throwable;

final class Dotenv
{
    private static ?self $instance = null;

    /**
     * @throws Throwable
     */
    public function __construct(string $path)
    {
        if (!file_exists($path)) {
            throw new DotenvException("File does not exist: {$path}");
        }
        (new \Symfony\Component\Dotenv\Dotenv())->loadEnv($path);
    }

    /**
     * @throws Throwable
     */
    public static function load(string $path): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($path);
        }
        return self::$instance;
    }
}