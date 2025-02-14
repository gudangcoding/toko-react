<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderItemUpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:Product,id'],
            'quantity' => ['required', 'integer'],
            'price' => ['required', 'numeric', 'between:-999999.99,999999.99'],
            'order_id' => ['required', 'integer', 'exists:orders,id'],
        ];
    }
}
