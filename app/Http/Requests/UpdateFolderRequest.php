<?php

namespace App\Http\Requests;

use App\Rules\UniqueFolderRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $original_name
 * @property string $current_folder
 */
class UpdateFolderRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'original_name' => [
                'required',
                'string',
                'between:1,255',
                new UniqueFolderRule($this->original_name, $this->current_folder)
            ],
            'current_folder' => 'nullable|string'
        ];
    }
}
