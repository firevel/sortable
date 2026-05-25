<?php

namespace Firevel\Sortable\Tests\Models;

use Firevel\Sortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class CamelCaseSortable extends Model
{
    use Sortable;

    protected $table = 'posts';

    protected $guarded = [];

    /**
     * Field names that the old Str::slug() call would have mangled
     * (camelCase -> lowercased, qualified -> dots stripped).
     *
     * @var array
     */
    protected $sortable = ['createdAt', 'users.name'];
}
