<?php

declare(strict_types=1);

namespace Libs\Unisender;

final class ContactData
{
    public function __construct(
        readonly public string  $email,
        readonly public ?string $name = null,
        readonly public ?array  $lists = [],
        readonly public int  $overwrite = 2,
        readonly public int  $duplicate = 4,
    )
    {
    }

    public function getGetListsAsString(): string
    {
        return implode(',', $this->lists);
    }
}