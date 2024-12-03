<?php

namespace App\Http\Requests\Order;

use App\Constants\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrderRequest extends FormRequest
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
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'integer', Rule::exists('products', 'id')->where('status', ProductStatus::ACTIVE)],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'products.required' => 'The products field is required.',
            'products.array' => 'The products field must be an array.',
            'products.min' => 'You must add at least one product.',

            'products.*.product_id.required' => 'The product ID is required.',
            'products.*.product_id.integer' => 'The product ID must be a valid integer.',
            'products.*.product_id.exists' => 'The selected product ID does not exist.',

            'products.*.quantity.required' => 'The product quantity is required',
            'products.*.quantity.integer' => 'The quantity must be a valid integer.',
            'products.*.quantity.min' => 'The quantity must be at least 1.',
        ];
    }
}
