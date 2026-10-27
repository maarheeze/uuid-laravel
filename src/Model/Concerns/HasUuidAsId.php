<?php

declare(strict_types=1);

namespace Maarheeze\Uuid\Laravel\Model\Concerns;

use Illuminate\Database\Eloquent\Model;
use Maarheeze\Uuid\Laravel\Model\Casts\UuidCast;
use Maarheeze\Uuid\Uuid;
use Maarheeze\Uuid\UuidException;
use Maarheeze\Uuid\UuidInterface;

use function is_string;
use function sprintf;

/**
 * @template TId of UuidInterface = Uuid
 *
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

    /**
     * @return TId
     */
    public function getKey(): UuidInterface
    {
        $key = $this->getAttribute($this->getKeyName());
        $idClass = $this->idClass();

        if (!$key instanceof $idClass) {
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

    /**
     * @param Model|null $model
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    public function is($model): bool
    {
        if ($model === null) {
            return false;
        }

        $otherKey = $model->getKey();

        return $otherKey instanceof UuidInterface
            && $this->getKey()->equals($otherKey)
            && $this->getTable() === $model->getTable()
            && $this->getConnectionName() === $model->getConnectionName();
    }

    /**
     * @return TId
     *
     * @phpstan-ignore method.childReturnType
     */
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
     * @return class-string<TId>
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
