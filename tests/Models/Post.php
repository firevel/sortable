<?php

namespace Firevel\Sortable\Tests\Models;

use Firevel\Sortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Sortable;

    protected $table = 'posts';

    protected $guarded = [];

    /**
     * Declared in an order that differs from typical request order,
     * so order-preservation can be tested meaningfully.
     *
     * @var array
     */
    protected $sortable = ['id', 'title', 'status'];

    /**
     * @var array
     */
    protected $defaultSort = ['-id'];
}
