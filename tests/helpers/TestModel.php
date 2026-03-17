<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Illuminate\Database\Eloquent\Model;
use Maarheeze\CalendarDate\Laravel\Model\Concerns\HasUuidAsId;

class TestModel extends Model
{
    use HasUuidAsId;

    protected $fillable = ['id'];

    public function checkIsValidUniqueId(mixed $value): bool
    {
        return $this->isValidUniqueId($value);
    }
}
