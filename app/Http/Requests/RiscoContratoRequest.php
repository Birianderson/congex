<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RiscoContratoRequest extends FormRequest
{

    /**
     * @return string[][]
     */
    public function rules(): array
    {
        return [
            'situacao' => ['required'],
            'possibilidades' => ['required'],
            'pontuacao' => ['required'],
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
