<?php

namespace Firevel\Sortable\Tests;

use Firevel\Sortable\SortableServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    protected function getPackageProviders($app)
    {
        return [SortableServiceProvider::class];
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    /**
     * Reduce a query's order clauses to simple [column, direction] pairs.
     *
     * @return array<int, array{0: string, 1: string}>
     */
    protected function orders(Builder $query): array
    {
        return collect($query->getQuery()->orders ?? [])
            ->map(fn ($order) => [$order['column'], $order['direction']])
            ->all();
    }
}
