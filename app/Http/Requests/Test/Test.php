<?php

namespace App\Http\Requests\Test;

use Illuminate\Foundation\Http\FormRequest;

class Test extends FormRequest
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
            'name'=> 'string',
            'type_url_id'=> 'nullable|integer',
            'type_resource_id'=> 'nullable|integer',
            'resource_id'=> 'nullable|integer',
            'url'=> 'string'
        ];
    }
}
