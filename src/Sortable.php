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
     * @param string|array $sortingAttributes JSON:API style string ("name,-id") or array.
     * @return Builder
     */
    public function scopeSort($query, $sortingAttributes)
    {
        if (empty($this->sortable)) {
            return $query;
        }

        $sortingAttributes = SortFields::parse($sortingAttributes);

        $sortable = $this->sortable;
        foreach ($sortable as $key) {
            $sortable[] = '-' . $key;
        }
        // Keep only allowlisted fields, preserving the requested order.
        $sortingAttributes = array_intersect($sortingAttributes, $sortable);

        // Apply default sorting if no valid sorting attributes provided
        if (empty($sortingAttributes) && !empty($this->defaultSort)) {
            $sortingAttributes = array_intersect(SortFields::parse($this->defaultSort), $sortable);
        }

        if (empty($sortingAttributes)) {
            return $query;
        }

        foreach ($sortingAttributes as $attribute) {
            $sortingDirection = strpos($attribute, '-') === 0 ? 'desc' : 'asc';
            $columnName = ltrim($attribute, '-');

            $query->orderBy(Str::slug($columnName, '_'), $sortingDirection);
        }

        return $query;
    }

    /**
     * Check if a field is sortable.
     *
     * @param string $field
     * @return bool
     */
    public function isSortable(string $field): bool
    {
        if (empty($this->sortable)) {
            return false;
        }

        $field = ltrim($field, '-');

        return in_array($field, $this->sortable);
    }
}
