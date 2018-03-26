<?php

namespace Feikwok\LMailChimp\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LMailChimpUpdateListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'list_id' => 'required',
            'name' => 'required|max:255',
            'contact.company' => 'required|max:255',
            'contact.address1' => 'required|max:255',
            'contact.address2' => 'max:255',
            'contact.city' => 'required|max:50',
            'contact.state' => 'required|max:50',
            'contact.zip' => 'required|max:10',
            'contact.country' => 'required|max:50',
            'contact.phone' => 'max:30',
            'permission_reminder' => 'required|max:255',
            'use_archive_bar' => 'boolean',
            'campaign_defaults.from_name' => 'required|max:50',
            'campaign_defaults.from_email' => 'required|email',
            'campaign_defaults.subject' => 'required|max:50',
            'campaign_defaults.language' => 'required|max:50',
            'notify_on_subscribe' => 'email',
            'notify_on_unsubscribe' => 'email',
            'email_type_option' => 'required|boolean',
            'visibility' => 'boolean',
        ];
    }
}
