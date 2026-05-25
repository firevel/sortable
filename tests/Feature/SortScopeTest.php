<?php

namespace Firevel\Sortable\Tests\Feature;

use Firevel\Sortable\Tests\Models\NonSortable;
use Firevel\Sortable\Tests\Models\Post;
use Firevel\Sortable\Tests\Models\PostWithStringDefault;
use Firevel\Sortable\Tests\TestCase;

class SortScopeTest extends TestCase
{
    public function test_it_sorts_from_a_string(): void
    {
        $query = Post::sort('title');

        $this->assertSame([['title', 'asc']], $this->orders($query));
    }

    public function test_it_sorts_from_an_array(): void
    {
        $query = Post::sort(['title']);

        $this->assertSame([['title', 'asc']], $this->orders($query));
    }

    public function test_a_dash_prefix_sorts_descending(): void
    {
        $query = Post::sort('-title');

        $this->assertSame([['title', 'desc']], $this->orders($query));
    }

    public function test_it_filters_out_fields_not_in_the_allowlist(): void
    {
        $query = Post::sort('title,hacker_field,-secret');

        $this->assertSame([['title', 'asc']], $this->orders($query));
    }

    public function test_it_preserves_the_requested_order_not_the_allowlist_order(): void
    {
        // $sortable is declared ['id', 'title', 'status']; the request asks for
        // status first, then title. The applied order must follow the request.
        $query = Post::sort('status,-title');

        $this->assertSame([['status', 'asc'], ['title', 'desc']], $this->orders($query));
    }

    public function test_it_tolerates_whitespace_in_the_string(): void
    {
        $query = Post::sort('status, -title');

        $this->assertSame([['status', 'asc'], ['title', 'desc']], $this->orders($query));
    }

    public function test_it_applies_default_sort_when_no_attributes_given(): void
    {
        $this->assertSame([['id', 'desc']], $this->orders(Post::sort('')));
        $this->assertSame([['id', 'desc']], $this->orders(Post::sort([])));
    }

    public function test_it_applies_default_sort_when_only_invalid_attributes_given(): void
    {
        $query = Post::sort('hacker_field,another_bad_one');

        $this->assertSame([['id', 'desc']], $this->orders($query));
    }

    public function test_default_sort_may_be_declared_as_a_string(): void
    {
        $query = PostWithStringDefault::sort('');

        $this->assertSame([['status', 'desc']], $this->orders($query));
    }

    public function test_a_model_with_an_empty_sortable_applies_no_order(): void
    {
        $query = NonSortable::sort('title,-id');

        $this->assertSame([], $this->orders($query));
    }

    public function test_it_orders_real_rows_in_the_requested_priority(): void
    {
        Post::create(['title' => 'b', 'status' => 'draft']);
        Post::create(['title' => 'a', 'status' => 'published']);
        Post::create(['title' => 'a', 'status' => 'draft']);

        // status asc, then title desc within each status.
        $result = Post::sort('status,-title')->get();

        $this->assertSame(
            [['draft', 'b'], ['draft', 'a'], ['published', 'a']],
            $result->map(fn ($p) => [$p->status, $p->title])->all()
        );
    }
}
