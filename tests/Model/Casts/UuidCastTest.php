<?php

declare(strict_types=1);

namespace Tests\Model\Casts;

use Illuminate\Database\Eloquent\Model;
use Maarheeze\Uuid\Laravel\Model\Casts\UuidCast;
use Maarheeze\Uuid\Uuid;
use Maarheeze\Uuid\UuidInterface;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

class UuidCastTest extends TestCase
{
    public function testGetReturnsNullWhenValueIsNull(): void
    {
        $cast = new UuidCast();
        $model = $this->createStub(Model::class);

        self::assertNull($cast->get($model, 'id', null, []));
    }

    public function testGetReturnsUuidInterfaceFromString(): void
    {
        $cast = new UuidCast();
        $model = $this->createStub(Model::class);

        $result = $cast->get($model, 'id', '018e4c55-5b1a-7000-8000-000000000000', []);

        self::assertInstanceOf(UuidInterface::class, $result);
        self::assertSame('018e4c55-5b1a-7000-8000-000000000000', $result->toString());
    }

    public function testGetThrowsForNonStringValue(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $model = $this->createStub(Model::class);

        $cast = new UuidCast();
        $cast->get($model, 'id', 123, []);
    }

    public function testSetReturnsNullWhenValueIsNull(): void
    {
        $cast = new UuidCast();
        $model = $this->createStub(Model::class);

        self::assertNull($cast->set($model, 'id', null, []));
    }

    public function testSetReturnsStringFromUuidInterface(): void
    {
        $uuid = Uuid::fromString('018e4c55-5b1a-7000-8000-000000000000');

        $model = $this->createStub(Model::class);

        $cast = new UuidCast();
        $result = $cast->set($model, 'id', $uuid, []);

        self::assertSame('018e4c55-5b1a-7000-8000-000000000000', $result);
    }

    public function testSetThrowsForNonUuidInterfaceValue(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $model = $this->createStub(Model::class);

        $cast = new UuidCast();
        $cast->set($model, 'id', '018e4c55-5b1a-7000-8000-000000000000', []);
    }
}
