<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'keywords' => 'sometimes|array',
            'keywords.*' => 'integer|exists:keywords,id',
            'is_done' => 'sometimes|boolean',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);
        if (isset($data['is_done'])) {
            $data['is_done'] = filter_var($data['is_done'], FILTER_VALIDATE_BOOLEAN);
        } else {
            $data['is_done'] = false;
        }
        return $data;
    }
}
