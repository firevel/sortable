<?php

namespace Firevel\Sortable\Tests\Models;

use Firevel\Sortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class NonSortable extends Model
{
    use Sortable;

    protected $table = 'posts';

    protected $guarded = [];

    /**
     * No fields are sortable.
     *
     * @var array
     */
    protected $sortable = [];
}
