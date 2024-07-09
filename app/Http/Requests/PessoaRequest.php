<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PessoaRequest extends FormRequest
{
    /**
     * @return string[][]
     */
    public function rules(): array
    {
        return [
            'nome' => ['required', 'max:255'],
            'cpf' => ['required', 'max:14', 'min:14'],
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
            'cpf.required' => 'O campo CPF é obrigatório.',
            'cpf.max' => 'O CPF deve ter 11 dígitos.',
            'cpf.min' => 'O CPF deve ter 11 dígitos.',
        ];
    }
}
