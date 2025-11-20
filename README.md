# Laravel Sortable

A simple trait to make your Laravel Eloquent models sortable with ease. Designed for API usage where you can pass sort parameters directly from query strings (e.g., `/users?sort=-id`). 

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

    /**
     * Default sorting when no sort parameter is provided (optional).
     *
     * @var array|null
     */
    protected $defaultSort = ['-id'];
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

### Multiple Columns:
You can sort by multiple columns at once. The sorting is applied in the order specified:

```php
User::sort(['name', '-id'])->get();
```

This will sort by `name` ascending, then by `id` descending.

### API Usage:
This trait is particularly useful for API endpoints where you receive sort parameters from query strings:

```php
// Example: GET /users?sort=-id
public function index(Request $request)
{
    $sort = $request->input('sort');
    $sortArray = $sort ? explode(',', $sort) : [];

    return User::sort($sortArray)->get();
}

// Supports multiple sort parameters:
// GET /users?sort=name,-id
```

## Additional Features

### Default Sorting
You can define a default sort order that will be applied when no sort parameters are provided:

```php
class User extends Model {
    use Sortable;

    protected $sortable = ['id', 'name', 'email', 'created_at'];
    protected $defaultSort = ['-created_at'];  // Sort by newest first by default
}

// When called with no parameters, uses default sort
User::sort([])->get();  // Returns users sorted by created_at DESC
```

### Check if Field is Sortable
You can check if a specific field is sortable using the `isSortable()` method:

```php
$user = new User();

if ($user->isSortable('name')) {
    // Field is sortable
}

if ($user->isSortable('-id')) {
    // Also works with descending prefix
}
```

### Validation Rule for Form Requests

The package provides two ways to validate sort parameters:

#### String-based validation:

```php
class ListUsersRequest extends FormRequest
{
    public function rules()
    {
        return [
            'sort' => ['nullable', 'string', 'sort_fields:App\Models\User'],
        ];
    }
}
```

#### Object-based validation (provides more detailed error messages):

```php
use Firevel\Sortable\SortField;
use App\Models\User;

class ListUsersRequest extends FormRequest
{
    public function rules()
    {
        return [
            'sort' => ['nullable', 'string', new SortField(User::class)],
        ];
    }
}
```

Both approaches work with string and array inputs:

```php
// String input (e.g., from query string ?sort=name,-id)
'sort' => ['nullable', 'string', 'sort_fields:App\Models\User']

// Array input
'sort' => ['nullable', 'array', 'sort_fields:App\Models\User']
```

Example usage in a controller:

```php
public function index(ListUsersRequest $request)
{
    $sort = $request->input('sort');
    $sortArray = is_array($sort) ? $sort : explode(',', $sort);

    return User::sort($sortArray)->get();
}
```

The validation rule will automatically:
- Check if each field is in the model's `$sortable` array
- Handle both ascending (`name`) and descending (`-name`) formats
- Provide clear error messages for invalid fields