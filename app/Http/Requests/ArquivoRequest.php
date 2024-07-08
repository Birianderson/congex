<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ArquivoRequest extends FormRequest
{

    /**
     * @return string[][]
     */
    public function rules(): array
    {
        return [

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
