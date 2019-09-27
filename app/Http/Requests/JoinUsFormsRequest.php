<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App;

class JoinUsFormsRequest extends FormRequest
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
                'entity_name' => 'required:max:190',
                'entity_president_name' => 'required:max:190',
                'entity_focal_point_name' => 'required:max:190',
                'entity_contact_info' => 'required:max:1000',
                'attachments' => 'required',
                'attachments.*' => 'mimes:gif,jpe,jpeg,png,doc,docx,odt,txt,pdf,zip|max:5000',
            ];
        } elseif (request('form_id') == 2) {
            return [
                'entity_name' => 'required:max:190',
                'entity_president_name' => 'required:max:190',
                'entity_focal_point_name' => 'required:max:190',
                'entity_contact_info' => 'required:max:1000',
                'independent' => 'required',
                'commitment' => 'required',
                'activities' => 'required',
                'reputation' => 'required',
                'legal_status' => 'required',
                'attachments' => 'required',
                'attachments.*' => 'mimes:gif,jpe,jpeg,png,doc,docx,odt,txt,pdf,zip|max:5000',
            ];
        } elseif (request('form_id') == 3) {
            return [
                'entity_name' => 'required:max:190',
                'entity_president_name' => 'required:max:190',
                'entity_focal_point_name' => 'required:max:190',
                'entity_contact_info' => 'required:max:1000',
                'category' => 'required:exists:join_us_observer_categories,title_' . App::getLocale(),
                'attachments' => 'required',
                'attachments.*' => 'mimes:gif,jpe,jpeg,png,doc,docx,odt,txt,pdf,zip|max:5000',
            ];
        } else {
            return [
                'name' => 'required:max:190',
                'contact_info' => 'required:max:190',
                'country' => 'required:max:190',
                'gender' => 'required:max:190',
                'age_group' => 'required:exists:join_us_friends_age_groups,title_' . App::getLocale(),
                'attachments' => 'required',
                'attachments.*' => 'mimes:gif,jpe,jpeg,png,doc,docx,odt,txt,pdf,zip|max:5000',
            ];
        }
    }
}
