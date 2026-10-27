<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Illuminate\Database\Eloquent\Model;
use Maarheeze\Uuid\Laravel\Model\Casts\UuidCast;
use Maarheeze\Uuid\Laravel\Model\Concerns\HasUuidAsId;

/**
 * @property TestId $id
 * @property TestId|null $related_id
 */
class TestIdModel extends Model
{
    /** @use HasUuidAsId<TestId> */
    use HasUuidAsId;

    /**
     * @var bool
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    public $timestamps = false;

    /**
     * @var list<string>
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $fillable = ['id', 'related_id'];

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
