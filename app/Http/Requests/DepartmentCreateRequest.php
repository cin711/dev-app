<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentCreateRequest extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules(): array {
        return [
            'name' => ['string', 'unique:departments', 'max:50', 'required'],
            'parent_id' => ['integer', 'exists:departments,id', 'min:1', 'nullable'],
        ];
    }
}