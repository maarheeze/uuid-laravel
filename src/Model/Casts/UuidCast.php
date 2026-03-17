<?php

declare(strict_types=1);

namespace Maarheeze\CalendarDate\Laravel\Model\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Maarheeze\Uuid\Uuid;
use Maarheeze\Uuid\UuidInterface;
use UnexpectedValueException;

use function is_string;

/**
 * @implements CastsAttributes<UuidInterface, mixed>
 */
class UuidCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?UuidInterface
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            return Uuid::fromString($value);
        }

        throw new UnexpectedValueException('Unable to cast value from Uuid');
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof UuidInterface) {
            return $value->toString();
        }

        throw new UnexpectedValueException('Unable to cast value to Uuid');
    }
}
