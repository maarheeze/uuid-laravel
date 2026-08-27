<?php

declare(strict_types=1);

namespace Maarheeze\Uuid\Laravel\Model\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Maarheeze\Uuid\Uuid;
use Maarheeze\Uuid\UuidInterface;
use UnexpectedValueException;

use function is_a;
use function is_string;
use function sprintf;

/**
 * @implements CastsAttributes<UuidInterface, mixed>
 */
class UuidCast implements CastsAttributes
{
    /** @var class-string<UuidInterface> */
    private string $uuidClass;

    public function __construct(string $uuidClass = Uuid::class)
    {
        if (!is_a($uuidClass, UuidInterface::class, true)) {
            throw new UnexpectedValueException(
                sprintf('Cast class %s does not implement %s', $uuidClass, UuidInterface::class),
            );
        }

        $this->uuidClass = $uuidClass;
    }

    public function get(Model $model, string $key, mixed $value, array $attributes): ?UuidInterface
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            return $this->uuidClass::fromString($value);
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
