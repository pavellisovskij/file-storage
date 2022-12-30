<?php

namespace App\Http\Requests;

use App\Helpers\Constants\StorageSizesEnum;
use App\Rules\UniqueFileInFolderRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * @property UploadedFile $file
 */
class FileRequest extends FormRequest
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
            'file' => [
                'required',
                'file',
                'max:' . (StorageSizesEnum::UPLOAD_FILE_SIZE->value / 1024),
                new UniqueFileInFolderRule(
                    $this->file->getClientOriginalName(),
                    $this->route()->parameter('folder')?->encoded_name
                )
            ]
        ];
    }
}
