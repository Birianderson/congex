<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\CustomAttributesTrait;
use App\Http\Requests\Traits\CustomMessagesTrait;
use Illuminate\Foundation\Http\FormRequest;

class PagamentoRequest extends FormRequest
{

    /**
     * @return string[][]
     */
    public function rules(): array
    {
        return [
            'nome' => ['required', 'max:255', 'min:5'],
            'cnpj' => ['required'],
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
