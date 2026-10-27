<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Illuminate\Database\Eloquent\Model;
use Maarheeze\Uuid\Laravel\Model\Concerns\HasUuidAsId;

class TestModel extends Model
{
    use HasUuidAsId;

    /**
     * @var list<string>
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $fillable = ['id'];

    public function checkIsValidUniqueId(mixed $value): bool
    {
        return $this->isValidUniqueId($value);
    }
}
