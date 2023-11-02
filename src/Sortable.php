<?php

namespace Firevel\Sortable;

use Illuminate\Support\Str;

/**
 * Trait Sortable
 *
 * @package Firevel\Sortable
 */
trait Sortable
{
    /**
     * Scope a query to sort results.
     *
     * @param Builder $query
     * @param array $sortingAttributes
     * @return Builder
     */
    public function scopeSort($query, array $sortingAttributes)
    {
        if (empty($this->sortable)) {
            return $query;
        }

        $sortable = $this->sortable;
        foreach ($sortable as $key) {
            $sortable[] = '-' . $key; 
        }
        $sortingAttributes = array_intersect($sortable, $sortingAttributes);

        if (empty($sortingAttributes)) {
            return $query;
        }

        foreach ($sortingAttributes as $attribute) {
            $sortingDirection = strpos($attribute, '-') === 0 ? 'desc' : 'asc';

            $query->orderBy(Str::slug($attribute, '_'), $sortingDirection);
        }

        return $query;
    }
}
