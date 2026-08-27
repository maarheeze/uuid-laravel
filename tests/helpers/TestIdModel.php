<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Illuminate\Database\Eloquent\Model;
use Maarheeze\Uuid\Laravel\Model\Casts\UuidCast;
use Maarheeze\Uuid\Laravel\Model\Concerns\HasUuidAsId;
use Maarheeze\Uuid\UuidException;

/**
 * @property TestId $id
 * @property TestId|null $related_id
 */
class TestIdModel extends Model
{
    use HasUuidAsId;

    public $timestamps = false;
    protected $fillable = ['id', 'related_id'];

    public function getKey(): TestId
    {
        $key = parent::getKey();

        if (!$key instanceof TestId) {
            throw new UuidException('Invalid uuid found');
        }

        return $key;
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'related_id' => UuidCast::class . ':' . TestId::class,
        ];
    }

    /**
     * @return class-string<TestId>
     */
    protected function idClass(): string
    {
        return TestId::class;
    }
}
