<?php

namespace Firevel\Sortable;

/**
 * Helper for normalizing sort definitions.
 *
 * @package Firevel\Sortable
 */
class SortFields
{
    /**
     * Parse a sort definition into a normalized array of field names.
     *
     * Accepts a JSON:API style comma-separated string (e.g. "name,-id")
     * or an array. Whitespace is trimmed and empty values are removed.
     *
     * @param string|array|null $value
     * @return array
     */
    public static function parse($value): array
    {
        if (! is_array($value)) {
            $value = explode(',', (string) $value);
        }

        $value = array_map('trim', $value);

        return array_values(array_filter($value, function ($field) {
            return $field !== '';
        }));
    }
}
