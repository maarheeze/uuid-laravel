# maarheeze/uuid-laravel

Laravel integration for [maarheeze/uuid](https://github.com/maarheeze/uuid). Provides an Eloquent cast and a trait for using uuid's as primary keys.

## Installation

```bash
composer require maarheeze/uuid-laravel
```

## Usage

### Using UUID as primary key

Add the `HasUuidAsId` trait to an Eloquent model, a uuid is automatically generated on creation.

```php
use Illuminate\Database\Eloquent\Model;
use Maarheeze\Uuid\Laravel\Model\Concerns\HasUuidAsId;

class Article extends Model
{
    use HasUuidAsId;
}
```

```php
$article = Article::create(['title' => 'Hello world']);

$article->getKey(); // returns a UuidInterface instance
```

### Casting a UUID column

Use `UuidCast` directly on any model attribute to cast it to a `UuidInterface`.

```php
use Illuminate\Database\Eloquent\Model;
use Maarheeze\Uuid\Laravel\Model\Casts\UuidCast;

class Article extends Model
{
    protected $casts = [
        'related_id' => UuidCast::class,
    ];
}
```

The cast takes the target class as a cast argument, defaulting to `Maarheeze\Uuid\Uuid`.

### Typed identifiers

A model can use its own `UuidInterface` implementation instead of the generic `Uuid`, so
that `$article->getKey()` returns an `ArticleId` and a foreign key column returns the id
type it points at. Two things are needed, and both are easy to get half-right — the
`idClass()` override drives the model's own key (the cast on `id`, the generated id and
the id validation), while every *other* uuid column needs its own cast argument.

```php
use Illuminate\Database\Eloquent\Model;
use Maarheeze\Uuid\IsUuid;
use Maarheeze\Uuid\Laravel\Model\Casts\UuidCast;
use Maarheeze\Uuid\Laravel\Model\Concerns\HasUuidAsId;
use Maarheeze\Uuid\UuidException;
use Maarheeze\Uuid\UuidInterface;

final readonly class ArticleId implements UuidInterface
{
    use IsUuid;
}

/**
 * @property ArticleId $id
 * @property AuthorId|null $author_id
 */
class Article extends Model
{
    use HasUuidAsId;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'author_id' => UuidCast::class . ':' . AuthorId::class,
        ];
    }

    /**
     * @return class-string<ArticleId>
     */
    protected function idClass(): string
    {
        return ArticleId::class;
    }
}
```

Keep the `@return class-string<...>` docblock on the `idClass()` override — without it
static analysis falls back to plain `string` and loses the id type. An argument that does
not implement `UuidInterface` throws an `UnexpectedValueException` when the model boots.

`getKey()` is deliberately not `final`, so a model can narrow its return type:

```php
public function getKey(): ArticleId
{
    $key = parent::getKey();

    if (!$key instanceof ArticleId) {
        throw new UuidException('Invalid uuid found');
    }

    return $key;
}
```

## License

MIT