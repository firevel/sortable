# Laravel Sortable

A simple trait to make your Laravel Eloquent models sortable with ease. 

## Installation

Using Composer:

```bash
composer require firevel/sortable
```

## Setup

1. Import the `Sortable` trait in your Eloquent model.

2. Add a protected `$sortable` array property to your model. This array should list the fields you want to allow for sorting.

## Example:

```php
use Firevel\Sortable\Sortable;

class User extends Model {
    use Sortable;

    /**
     * Fields allowed for sorting.
     *
     * @var array
     */
    protected $sortable = ['id', 'name', 'email'];
}
```

## Usage

You can now easily sort your models using the `sort()` query scope.

### Ascending Order:
To sort by `name` in ascending order:

```php
User::sort(['name'])->get();
```

### Descending Order:
To sort by `id` in descending order:

```php
User::sort(['-id'])->get();
```

The `-` sign before the field name indicates descending order.