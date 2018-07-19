<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreParticipator extends FormRequest
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
            'yuedan_id' => 'bail|required|integer|min:1',
            'user_id' => 'bail|required|integer|min:1',
            'join_role' => 'bail|required|integer',
            'avatar_url' => 'bail|required|string|max:255'
        ];
    }
}
