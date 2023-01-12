<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductlistRequest extends FormRequest
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
            'listNumber' => 'required|size:3',
            'memberNumber' => 'nullable|regex:/^(\d{3})-(\d{3})-(\d{3})$/',
            'isUserConfirmed' => 'nullable|boolean'
        ];
    }

    public function messages()
    {
        return [
            'listNumber.required' => 'Lijstnummer is verplicht.',
            'listNumber.size' => 'Lijstnummer moet 3 tekens bevatten.',
            'memberNumber.regex' => 'Lidnummer heeft een foute structuur.'
        ];
    }
}