<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\CustomAttributesTrait;
use App\Http\Requests\Traits\CustomMessagesTrait;
use Illuminate\Foundation\Http\FormRequest;

class EmpresaRequest extends FormRequest
{

    /**
     * @return string[][]
     */
    public function rules(): array
    {
        return [
            'nome' => ['required', 'max:255'],
            'cnpj' => ['required', 'max:18', 'min:18'],
        ];
    }

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.max' => 'O nome não pode ter mais de 255 caracteres.',
            'cnpj.required' => 'O campo CNPJ é obrigatório.',
            'cnpj.max' => 'O CNPJ deve ter 14 dígitos.',
            'cnpj.min' => 'O CNPJ deve ter 14 dígitos.',
        ];
    }
}
