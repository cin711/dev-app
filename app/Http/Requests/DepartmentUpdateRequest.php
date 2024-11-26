<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentUpdateRequest extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function prepareForValidation() {
        $this->merge(['id' => $this->route('id')]);
    }

    public function rules(): array {
        return [
            'id' => ['integer', 'required'],
            'name' => ['string', 'unique:departments,name,' . $this->get('id'), 'max:50', 'required'],
            'parent_id' => ['integer', 'exists:departments', 'different:id', 'min:1', 'nullable'],
        ];
    }
}