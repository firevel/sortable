<?php

namespace Firevel\Sortable;

use Illuminate\Contracts\Validation\Rule;

/**
 * Validation rule to check if sort fields are valid for a given model.
 *
 * @package Firevel\Sortable
 */
class SortField implements Rule
{
    /**
     * The model class to validate against.
     *
     * @var string
     */
    protected $modelClass;

    /**
     * Invalid fields found during validation.
     *
     * @var array
     */
    protected $invalidFields = [];

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
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!class_exists($this->modelClass)) {
            return false;
        }

        $model = new $this->modelClass;

        if (!method_exists($model, 'isSortable')) {
            return false;
        }

        // Convert string to array if needed (e.g., "name,-id" -> ["name", "-id"])
        $fields = is_array($value) ? $value : explode(',', $value);

        $this->invalidFields = [];

        foreach ($fields as $field) {
            $field = trim($field);

            if (empty($field)) {
                continue;
            }

            if (!$model->isSortable($field)) {
                $this->invalidFields[] = $field;
            }
        }

        return empty($this->invalidFields);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if (count($this->invalidFields) === 1) {
            return "The sort field '{$this->invalidFields[0]}' is not allowed.";
        }

        $fields = implode(', ', array_map(function ($field) {
            return "'{$field}'";
        }, $this->invalidFields));

        return "The sort fields {$fields} are not allowed.";
    }
}
