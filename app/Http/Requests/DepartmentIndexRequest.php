<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentIndexRequest extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules(): array {
        return [
            'page_size' => ['integer'],
            'page' => ['integer'],
        ];
    }
}