<?php

declare(strict_types=1);

namespace Maarheeze\Uuid\Laravel\Model\Concerns;

use Maarheeze\Uuid\Laravel\Model\Casts\UuidCast;
use Maarheeze\Uuid\Uuid;
use Maarheeze\Uuid\UuidException;
use Maarheeze\Uuid\UuidInterface;

use function is_string;

/**
 * @property UuidInterface $id
 */
trait HasUuidAsId
{
    final public function initializeHasUuidAsId(): void
    {
        $this->usesUniqueIds = true;

        $this->mergeCasts([
            'id' => UuidCast::class,
        ]);
    }

    final public function getKey(): UuidInterface
    {
        $key = $this->getAttribute($this->getKeyName());

        if (!$key instanceof UuidInterface) {
            throw new UuidException('Invalid uuid found');
        }

        return $key;
    }

    final public function getIncrementing(): bool
    {
        return false;
    }

    public function getKeyName(): string
    {
        return 'id';
    }

    final public function getKeyType(): string
    {
        return 'string';
    }

    final public function getQueueableId(): string
    {
        return $this->getKey()->toString();
    }

    /** @phpstan-ignore method.childReturnType */
    public function newUniqueId(): UuidInterface
    {
        return Uuid::generate();
    }

    protected function isValidUniqueId(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        Uuid::fromString($value);

        return true;
    }

    /**
     * @return array<string>
     */
    final public function uniqueIds(): array
    {
        return ['id'];
    }
}
