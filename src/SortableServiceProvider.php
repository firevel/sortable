<?php

namespace Firevel\Sortable;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

/**
 * Service provider for Sortable package.
 *
 * @package Firevel\Sortable
 */
class SortableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('sort_fields', function ($attribute, $value, $parameters, $validator) {
            if (empty($parameters[0])) {
                return false;
            }

            $modelClass = $parameters[0];

            if (!class_exists($modelClass)) {
                return false;
            }

            $model = new $modelClass;

            if (!method_exists($model, 'isSortable')) {
                return false;
            }

            // Convert string to array if needed (e.g., "name,-id" -> ["name", "-id"])
            $fields = is_array($value) ? $value : explode(',', $value);

            foreach ($fields as $field) {
                $field = trim($field);

                if (empty($field)) {
                    continue;
                }

                if (!$model->isSortable($field)) {
                    return false;
                }
            }

            return true;
        });

        Validator::replacer('sort_fields', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, 'The :attribute contains invalid sort fields.');
        });
    }
}
