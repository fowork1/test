<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRealtyRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name'          => 'string',
            'price_from'    => 'numeric|min:1',
            'price_to'      => 'numeric|min:1',
            'bedrooms'      => 'int|min:1',
            'bathrooms'     => 'int|min:1',
            'storeys'       => 'int|min:1',
            'garages'       => 'int|min:1'
        ];
    }
}
