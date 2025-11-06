<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => 'required',
            'name' => 'required|unique:products,name|min:3|max:255',
            'category_id' => 'required',
            'price' => 'required|integer|gte:0',
            'sale' => 'integer|gte:0|lte:100',
            'content' => 'required|min:3',
            'description' => 'required|min:3'
        ];
    }
}
