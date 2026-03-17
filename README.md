# maarheeze/uuid-laravel

Laravel integration for [maarheeze/uuid](https://github.com/maarheeze/uuid). Provides an Eloquent cast and a trait for using uuid's as primary keys.

## Requirements

- PHP 8.2+
- Laravel 8–12

## Installation

```bash
composer require maarheeze/uuid-laravel
```

## Usage

### Using UUID as primary key

Add the `HasUuidAsId` trait to an Eloquent model, a uuid is automatically generated on creation.

```php
use Illuminate\Database\Eloquent\Model;
use Maarheeze\CalendarDate\Laravel\Model\Concerns\HasUuidAsId;

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
use Maarheeze\CalendarDate\Laravel\Model\Casts\UuidCast;

class Article extends Model
{
    protected $casts = [
        'related_id' => UuidCast::class,
    ];
}
```

## License

MIT