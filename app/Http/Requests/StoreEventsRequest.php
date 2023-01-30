<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventsRequest extends FormRequest
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
            'days'=>'required',
            'start_date'=>'required',
            'end_date'=>'required',
            'start_at'=>'required',
            'end_at'=>'required',
            'meeting_url'=>'required',
            'title'=>'required',
        ];
    }
}
