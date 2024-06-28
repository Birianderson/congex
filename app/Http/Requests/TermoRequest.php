<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TermoRequest extends FormRequest
{

    /**
     * @return string[][]
     */
    public function rules(): array
    {
        return [
            'valor' => ['required'],
        ];
    }

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
