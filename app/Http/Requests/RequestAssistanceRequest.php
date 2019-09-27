<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App;

class RequestAssistanceRequest extends FormRequest
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
        if (request('form_id') == 1) {
            return [
                'name' => 'required|max:190',
                'title' => 'required|max:190',
                'entity' => 'required|max:190',
                'contact_info' => 'required|max:1000',
                'subject' => 'required|max:190',
                'expert' => 'required|exists:request_assistance_experties,title_' . App::getLocale(),
                'duration' => 'required|max:190',
                'funding' => 'required|max:190',
                'additional_info' => 'required|max:1000',
                'attachments' => 'required',
                'attachments.*' => 'mimes:gif,jpe,jpeg,png,doc,docx,odt,txt,pdf,zip|max:5000',
            ];
        } elseif (request('form_id') == 2) {
            return [
                'name' => 'required|max:190',
                'title' => 'required|max:190',
                'contact_info' => 'required|max:1000',
                'request_details' => 'required|max:190',
                'additional_info' => 'required|max:190',
            ];
        } else {
            return [
                'name' => 'required|max:190',
                'title' => 'required|max:190',
                'contact_info' => 'required|max:1000',
                'subject' => 'required|max:190',
                'request_details' => 'required|max:190',
                'duration' => 'required|max:190',
                'funding' => 'required|max:190',
                'attachments' => 'required',
                'attachments.*' => 'mimes:gif,jpe,jpeg,png,doc,docx,odt,txt,pdf,zip|max:5000',
            ];
        }
    }
}
