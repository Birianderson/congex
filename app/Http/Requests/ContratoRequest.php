<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ContratoRequest extends FormRequest
{

    /**
     * @return string[][]
     */
    public function rules(): array
    {
        return [
            'empresa_id' => ['required'],
            'numero' => ['required'],
            'cargo' => ['required'],
            'pessoa' => ['required'],
            'data_inicio' => ['required'],
            'data_fim' => ['required'],
            'valor' => ['required', 'numeric'],
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
