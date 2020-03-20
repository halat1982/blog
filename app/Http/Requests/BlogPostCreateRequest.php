<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class BlogPostCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|min:3|max:200|unique:blog_posts',
            'slug' => 'max:200',
            'content_row' => 'required|string|min:5|max:10000',
            'category_id' => 'required|integer|exists:blog_categories,id',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'You don\'t emter Title of articles',
            'content_row.min' => 'Minimum length of article is [:min] simbols'
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'Article title'
        ];
    }
}
