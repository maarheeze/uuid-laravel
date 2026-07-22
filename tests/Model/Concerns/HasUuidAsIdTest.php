<?php

declare(strict_types=1);

namespace Tests\Model\Concerns;

use Illuminate\Database\Eloquent\Model;
use Maarheeze\Uuid\Laravel\Model\Casts\UuidCast;
use Maarheeze\Uuid\Laravel\Model\Concerns\HasUuidAsId;
use Maarheeze\Uuid\Uuid;
use Maarheeze\Uuid\UuidException;
use Maarheeze\Uuid\UuidInterface;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\TestModel;

class HasUuidAsIdTest extends TestCase
{
    public function testGetIncrementingReturnsFalse(): void
    {
        $model = $this->createModel();

        self::assertFalse($model->getIncrementing());
    }

    public function testGetKeyNameReturnsId(): void
    {
        $model = $this->createModel();

        self::assertSame('id', $model->getKeyName());
    }

    public function testGetKeyTypeReturnsString(): void
    {
        $model = $this->createModel();

        self::assertSame('string', $model->getKeyType());
    }

    public function testUniqueIdsReturnsIdArray(): void
    {
        $model = $this->createModel();

        self::assertSame(['id'], $model->uniqueIds());
    }

    public function testInitializeRegistersCast(): void
    {
        $model = $this->createModel();

        self::assertArrayHasKey('id', $model->getCasts());
        self::assertSame(UuidCast::class, $model->getCasts()['id']);
    }

    public function testNewUniqueIdReturnsUuidInterface(): void
    {
        $model = $this->createModel();

        self::assertInstanceOf(UuidInterface::class, $model->newUniqueId());
    }

    public function testIsValidUniqueIdReturnsTrueForValidUuid(): void
    {
        $model = $this->createModel();

        self::assertTrue($model->checkIsValidUniqueId('018e4c55-5b1a-7000-8000-000000000000'));
    }

    public function testIsValidUniqueIdThrowsForInvalidUuid(): void
    {
        $model = $this->createModel();

        $this->expectException(UuidException::class);

        $model->checkIsValidUniqueId('not-a-valid-uuid');
    }

    public function testGetKeyReturnsUuidInterface(): void
    {
        $model = $this->createModel(['id' => Uuid::fromString('018e4c55-5b1a-7000-8000-000000000000')]);

        self::assertInstanceOf(UuidInterface::class, $model->getKey());
        self::assertSame('018e4c55-5b1a-7000-8000-000000000000', $model->getKey()->toString());
    }

    public function testGetKeyThrowsWhenIdIsNotSet(): void
    {
        $model = new class extends Model {
            use HasUuidAsId;
        };

        $this->expectException(UuidException::class);
        $model->getKey();
    }

    public function testGetQueueableIdReturnsUuidString(): void
    {
        $model = $this->createModel(['id' => Uuid::fromString('018e4c55-5b1a-7000-8000-000000000000')]);

        self::assertSame('018e4c55-5b1a-7000-8000-000000000000', $model->getQueueableId());
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function createModel(array $attributes = []): TestModel
    {
        return new TestModel($attributes);
    }
}
