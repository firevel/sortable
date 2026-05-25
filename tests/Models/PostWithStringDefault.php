<?php

namespace Firevel\Sortable\Tests\Models;

use Firevel\Sortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class PostWithStringDefault extends Model
{
    use Sortable;

    protected $table = 'posts';

    protected $guarded = [];

    /**
     * @var array
     */
    protected $sortable = ['id', 'title', 'status'];

    /**
     * Default sort declared as a JSON:API style string.
     *
     * @var string
     */
    protected $defaultSort = '-status';
}
