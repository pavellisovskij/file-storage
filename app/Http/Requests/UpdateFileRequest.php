<?php

namespace App\Http\Requests;

use App\Rules\UniqueFileInFolderRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $original_name
 * @property string $current_folder
 */
class UpdateFileRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

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
                'max:255',
                new UniqueFileInFolderRule($this->original_name, $this->current_folder)
            ],
            'current_folder' => 'nullable|string'
        ];
    }
}
