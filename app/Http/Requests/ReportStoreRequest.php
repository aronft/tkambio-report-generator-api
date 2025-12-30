<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:5|max:250',
            'date_init' => 'required|date|before_or_equal:date_end',
            'date_end' => 'required|date|after_or_equal:date_init',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'Título',
            'date_init' => 'Fecha inicial',
            'date_end' => 'Fecha final',
        ];
    }
}
