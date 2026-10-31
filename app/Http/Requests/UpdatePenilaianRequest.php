<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePenilaianRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'C1x' => 'required|numeric|integer',
            'C2x' => 'required|numeric|integer',
            'C3x' => 'required|numeric|integer',
            'C4x' => 'required|numeric|integer',
            'C5x' => 'required|numeric|integer',
            'C6x' => 'required|numeric|integer',
            /*'C7x' => 'required|numeric|integer',*/
        ];
    }
}
