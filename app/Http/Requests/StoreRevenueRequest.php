<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRevenueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount_ar' => 'required|numeric|min:0',
            'type' => 'required|in:income,expense',
            'label' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
