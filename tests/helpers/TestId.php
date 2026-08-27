<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Maarheeze\Uuid\IsUuid;
use Maarheeze\Uuid\UuidInterface;

final readonly class TestId implements UuidInterface
{
    use IsUuid;
}
