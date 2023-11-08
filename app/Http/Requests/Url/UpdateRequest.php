<?php

namespace App\Http\Requests\Url;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'type_url_id'=> 'nullable|unsignedBigInteger',
            'type_resource_id'=> 'nullable|unsignedBigInteger',
            'resource_id'=> 'nullable|unsignedBigInteger',
            'url'=> 'string'
        ];
    }
}
