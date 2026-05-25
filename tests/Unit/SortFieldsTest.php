<?php

namespace Firevel\Sortable\Tests\Unit;

use Firevel\Sortable\SortFields;
use PHPUnit\Framework\TestCase;

class SortFieldsTest extends TestCase
{
    public function test_it_parses_a_comma_separated_string(): void
    {
        $this->assertSame(['name', '-id'], SortFields::parse('name,-id'));
    }

    public function test_it_passes_an_array_through(): void
    {
        $this->assertSame(['name', '-id'], SortFields::parse(['name', '-id']));
    }

    public function test_it_trims_whitespace_around_fields(): void
    {
        $this->assertSame(['name', '-id'], SortFields::parse(' name , -id '));
        $this->assertSame(['name', '-id'], SortFields::parse([' name ', ' -id ']));
    }

    public function test_it_drops_empty_fields(): void
    {
        $this->assertSame(['name'], SortFields::parse('name,,  '));
        $this->assertSame(['name'], SortFields::parse(['name', '', '  ']));
    }

    public function test_it_returns_an_empty_array_for_empty_input(): void
    {
        $this->assertSame([], SortFields::parse(''));
        $this->assertSame([], SortFields::parse(null));
        $this->assertSame([], SortFields::parse([]));
    }

    public function test_it_reindexes_keys(): void
    {
        $this->assertSame(['name', '-id'], SortFields::parse(['', 'name', '', '-id']));
    }
}
