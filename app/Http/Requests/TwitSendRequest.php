<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class TwitSendRequest
 * @package App\Http\Requests
 *
 * @property int $category_id
 * @property string $username
 * @property string $message
 */
class TwitSendRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'category_id' => 'required|int|exists:categories,id',
            'username' => 'required|string|min:1',
            'message' => 'required|string|min:3',
        ];
    }
}
