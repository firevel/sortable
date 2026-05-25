<?php

namespace Firevel\Sortable\Tests\Feature;

use Firevel\Sortable\SortField;
use Firevel\Sortable\Tests\Models\Post;
use Firevel\Sortable\Tests\TestCase;
use Illuminate\Support\Facades\Validator;

class ValidationTest extends TestCase
{
    public function test_sort_fields_string_rule_passes_for_valid_fields(): void
    {
        $validator = Validator::make(
            ['sort' => 'title,-status'],
            ['sort' => ['sort_fields:' . Post::class]]
        );

        $this->assertTrue($validator->passes());
    }

    public function test_sort_fields_string_rule_fails_for_invalid_fields(): void
    {
        $validator = Validator::make(
            ['sort' => 'title,hacker_field'],
            ['sort' => ['sort_fields:' . Post::class]]
        );

        $this->assertTrue($validator->fails());
    }

    public function test_sort_field_object_passes_for_valid_string_and_array(): void
    {
        $string = Validator::make(
            ['sort' => 'title,-status'],
            ['sort' => [new SortField(Post::class)]]
        );

        $array = Validator::make(
            ['sort' => ['title', '-status']],
            ['sort' => [new SortField(Post::class)]]
        );

        $this->assertTrue($string->passes());
        $this->assertTrue($array->passes());
    }

    public function test_sort_field_object_fails_and_lists_invalid_fields(): void
    {
        $validator = Validator::make(
            ['sort' => 'title,bad_one,worse_one'],
            ['sort' => [new SortField(Post::class)]]
        );

        $this->assertTrue($validator->fails());

        $message = $validator->errors()->first('sort');
        $this->assertStringContainsString('bad_one', $message);
        $this->assertStringContainsString('worse_one', $message);
    }
}
