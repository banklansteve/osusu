<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SavingsRequest extends FormRequest
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
            'title' => 'required|max:600',
            'description' => 'required',
            'saving_amount' => 'required|numeric|min:1|max:100000',
            'total_members' => 'required|numeric|min:1|max:100',
            'savings_duration' => 'required|numeric|min:1|max:24',
            'payout_turn' => 'required|numeric',
            'payment_frequency' => 'required|string',
            'penalty_rate' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ];
    }
}
