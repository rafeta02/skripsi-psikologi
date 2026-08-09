<?php

namespace App\Http\Requests;

use App\Models\Announcement;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAnnouncementRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('announcement_edit');
    }

    public function rules()
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'body' => [
                'required',
                'string',
            ],
            'audience' => [
                'required',
                'in:' . implode(',', array_keys(Announcement::AUDIENCE_SELECT)),
            ],
            'status' => [
                'required',
                'in:' . implode(',', array_keys(Announcement::STATUS_SELECT)),
            ],
            'published_at' => [
                'nullable',
                'date',
            ],
            'expires_at' => [
                'nullable',
                'date',
            ],
            'is_pinned' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}
