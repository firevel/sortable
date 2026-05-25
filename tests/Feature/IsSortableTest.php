<?php

namespace Firevel\Sortable\Tests\Feature;

use Firevel\Sortable\Tests\Models\NonSortable;
use Firevel\Sortable\Tests\Models\Post;
use Firevel\Sortable\Tests\TestCase;

class IsSortableTest extends TestCase
{
    public function test_it_recognises_allowlisted_fields(): void
    {
        $post = new Post;

        $this->assertTrue($post->isSortable('title'));
        $this->assertTrue($post->isSortable('status'));
    }

    public function test_it_recognises_the_descending_variant(): void
    {
        $this->assertTrue((new Post)->isSortable('-title'));
    }

    public function test_it_rejects_unknown_fields(): void
    {
        $this->assertFalse((new Post)->isSortable('password'));
        $this->assertFalse((new Post)->isSortable('-password'));
    }

    public function test_it_rejects_everything_when_sortable_is_empty(): void
    {
        $this->assertFalse((new NonSortable)->isSortable('title'));
    }
}
