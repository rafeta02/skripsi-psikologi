<?php

namespace App\Http\Requests;

use App\Models\ArticleTag;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateArticleTagRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('article_tag_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'unique:article_tags,name,' . request()->route('article_tag')->id,
            ],
        ];
    }
}
