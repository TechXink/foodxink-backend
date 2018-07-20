<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreYuedanPost extends FormRequest
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
            'title' => 'bail|required|max:255',
            'description' => 'required',
            'close_time' => 'required|integer',
            'eat_time' => 'required|integer',
            'address' => 'bail|required|string|max:255',
            'latitude' => 'required',
            'longitude' => 'required',
            'location_name' => 'bail|required|string|max:255',
            'image' => 'required|nullable'
        ];
    }

    /**
     * 获取已定义的验证规则的错误消息。
     *
     * @return array
     */
    public function messages()
    {
        return [
            //'title.required' => 'A title is required',
            'address.required'  => 'address message is required',
        ];
    }
}
