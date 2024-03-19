<?php

declare(strict_types=1);

namespace Libs\Unisender;

use Exception;
use Throwable;

final class ClientException extends Exception
{
    public function __construct(string $message = "", string|int $code = 0, ?Throwable $previous = null)
    {
        if (is_string($code)) {
            $code = match ($code) {
                'unspecified' => 10404,
                default => 0,
            };
            var_dump($code);
        }

        parent::__construct($message, $code, $previous);
    }
}