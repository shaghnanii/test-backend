<?php

namespace App\Http\Requests\Car;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CarStoreRequest extends FormRequest
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
            'color' => ['required', 'min:2', 'max:20'],
            'model' => ['required', 'min:2', 'max:20'],
            'make' => ['required', 'min:2', 'max:20'],
            'registration_no' => ['required', 'min:2', 'max:20'],

            'category_id' => ['required', 'numeric', Rule::exists('categories', 'id')->where(function ($query) {
                $query->where('deleted_at', null);
            })],
        ];
    }

    public function messages()
    {
        return [
            'category_id.exists' => 'The :attribute is invalid, Please select a valid :attribute.',
        ];
    }

    public function attributes()
    {
        return [
            'category_id' => 'car category',
        ];
    }
}
