<?php

declare(strict_types=1);

namespace Maarheeze\Uuid\Laravel\Model\Concerns;

use Maarheeze\Uuid\Laravel\Model\Casts\UuidCast;
use Maarheeze\Uuid\Uuid;
use Maarheeze\Uuid\UuidException;
use Maarheeze\Uuid\UuidInterface;

use function is_string;
use function sprintf;

/**
 * @property UuidInterface $id
 */
trait HasUuidAsId
{
    final public function initializeHasUuidAsId(): void
    {
        $this->usesUniqueIds = true;

        $this->mergeCasts([
            'id' => sprintf('%s:%s', UuidCast::class, $this->idClass()),
        ]);
    }

    public function getKey(): UuidInterface
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

    public function getRouteKey(): mixed
    {
        $routeKey = parent::getRouteKey();

        if ($routeKey instanceof UuidInterface) {
            return $routeKey->toString();
        }

        return $routeKey;
    }

    /** @phpstan-ignore method.childReturnType */
    public function newUniqueId(): UuidInterface
    {
        return $this->idClass()::generate();
    }

    /**
     * @return array<string>
     */
    final public function uniqueIds(): array
    {
        return ['id'];
    }

    /**
     * @return class-string<UuidInterface>
     */
    protected function idClass(): string
    {
        return Uuid::class;
    }

    protected function isValidUniqueId(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        $this->idClass()::fromString($value);

        return true;
    }
}
