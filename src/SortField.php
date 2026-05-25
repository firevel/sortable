<?php

namespace Firevel\Sortable;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validation rule to check if sort fields are valid for a given model.
 *
 * @package Firevel\Sortable
 */
class SortField implements ValidationRule
{
    /**
     * The model class to validate against.
     *
     * @var string
     */
    protected $modelClass;

    /**
     * Create a new rule instance.
     *
     * @param string $modelClass The fully qualified model class name
     * @return void
     */
    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
    }

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param \Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!class_exists($this->modelClass)) {
            $fail("The :attribute field cannot be validated against {$this->modelClass}.");
            return;
        }

        $model = new $this->modelClass;

        if (!method_exists($model, 'isSortable')) {
            $fail("The :attribute field cannot be validated against {$this->modelClass}.");
            return;
        }

        $invalidFields = [];

        foreach (SortFields::parse($value) as $field) {
            if (!$model->isSortable($field)) {
                $invalidFields[] = $field;
            }
        }

        if (empty($invalidFields)) {
            return;
        }

        if (count($invalidFields) === 1) {
            $fail("The sort field '{$invalidFields[0]}' is not allowed.");
            return;
        }

        $fields = implode(', ', array_map(function ($field) {
            return "'{$field}'";
        }, $invalidFields));

        $fail("The sort fields {$fields} are not allowed.");
    }
}
