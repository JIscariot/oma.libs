<?php

declare(strict_types=1);

namespace Libs\Unisender;

use Exception;
use Throwable;

final class ClientException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if (empty($code)) {
            $code = match (true) {
                str_contains($message, "Contact not found") => 1404,
                str_contains($message, "'email' is required") => 1423,
                str_contains($message, "is not a valid email address") => 1422,
                default => 0,
            };
        }

        parent::__construct($message, $code, $previous);
    }
}