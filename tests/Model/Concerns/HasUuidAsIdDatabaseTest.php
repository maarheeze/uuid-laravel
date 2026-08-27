<?php

declare(strict_types=1);

namespace Tests\Model\Concerns;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\TestId;
use Tests\Helpers\TestIdModel;

class HasUuidAsIdDatabaseTest extends TestCase
{
    private Capsule $capsule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->capsule = new Capsule();
        $this->capsule->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $this->capsule->setAsGlobal();
        $this->capsule->bootEloquent();

        $this->capsule->schema()->create('test_id_models', static function (Blueprint $table): void {
            $table->string('id')->primary();
            $table->string('related_id')->nullable();
        });
    }

    public function testSavedModelIsRetrievedWithConfiguredIdClass(): void
    {
        $model = new TestIdModel();
        $model->save();

        $found = TestIdModel::query()->firstOrFail();

        self::assertInstanceOf(TestId::class, $found->getKey());
        self::assertTrue($found->getKey()->equals($model->getKey()));
    }

    public function testSavedModelIsRetrievedWithConfiguredCastClass(): void
    {
        $related = TestId::fromString('018e4c55-5b1a-7000-8000-000000000000');

        $model = new TestIdModel();
        $model->related_id = $related;
        $model->save();

        $found = TestIdModel::query()->firstOrFail();

        self::assertInstanceOf(TestId::class, $found->related_id);
        self::assertTrue($found->related_id->equals($related));
    }

    protected function tearDown(): void
    {
        $this->capsule->schema()->drop('test_id_models');

        parent::tearDown();
    }
}
